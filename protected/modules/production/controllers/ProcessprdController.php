<?php
class ProcessprdController extends Controller {
	public $menuname = 'processprd';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search()
	{
		header('Content-Type: application/json');
		$processprdid = isset ($_POST['processprdid']) ? $_POST['processprdid'] : '';
		$processprdname = isset ($_POST['processprdname']) ? $_POST['processprdname'] : '';
		$processprdid = isset ($_GET['q']) ? $_GET['q'] : $processprdid;
		$processprdname = isset ($_GET['q']) ? $_GET['q'] : $processprdname;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'processprdid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('processprd t')
				->where('((t.processprdid like :processprdid) or (t.processprdname like :processprdname)) and t.recordstatus=1',
					array(':processprdid'=>'%'.$processprdid.'%',':processprdname'=>'%'.$processprdname.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('processprd t')
				->where('(t.processprdid like :processprdid) and (t.processprdname like :processprdname)',
					array(':processprdid'=>'%'.$processprdid.'%',':processprdname'=>'%'.$processprdname.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('processprd t')
				->where('((t.processprdid like :processprdid) or (t.processprdname like :processprdname)) and t.recordstatus=1',
					array(':processprdid'=>'%'.$processprdid.'%',':processprdname'=>'%'.$processprdname.'%'))
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('processprd t')
				->where('(t.processprdid like :processprdid) and (t.processprdname like :processprdname)',
					array(':processprdid'=>'%'.$processprdid.'%',':processprdname'=>'%'.$processprdname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'processprdid'=>$data['processprdid'],
				'processprdname'=>$data['processprdname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call InsertProcessprd(:vprocessprdname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call UpdateProcessprd(:vid,:vprocessprdname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vprocessprdname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-processprd"]["name"]);
		if (move_uploaded_file($_FILES["file-processprd"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$processprdname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData($connection,array($id,$languagename,$recordstatus));
				}
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,'Line: '.$row.' ==> '.implode(" ",$e->errorInfo));
			}
    }
	}
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['processprdid'])?$_POST['processprdid']:''),
				$_POST['processprdname'],$_POST['recordstatus']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	public function actionPurge() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeprocessprd(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
		}
		else {
			GetMessage(true,getcatalog('chooseone'));
		}
	}
	public function actionDownPDF() {
	  parent::actionDownload();
	  $sql = "select processprdid, processprdname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from processprd a ";
		$processprdid = filter_input(INPUT_GET,'processprdid');
		$processprdname = filter_input(INPUT_GET,'processprdname');
		$sql .= " where coalesce(a.processprdid,'') like '%".$processprdid."%' 
			and coalesce(a.processprdname,'') like '%".$processprdname."%'
			";
		if ($_GET['id'] !== '')  {
				$sql = $sql . " and a.processprdid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by processprdname asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('processprd');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('processprdid'),
																	GetCatalog('processprdname'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,55,100,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['processprdid'],$row1['processprdname'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='processprd';
		parent::actionDownxls();
		$sql = "select processprdid, processprdname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from processprd a ";
		$processprdid = filter_input(INPUT_GET,'processprdid');
		$processprdname = filter_input(INPUT_GET,'processprdname');
		$sql .= " where coalesce(a.processprdid,'') like '%".$processprdid."%' 
			and coalesce(a.processprdname,'') like '%".$processprdname."%'
			";
		if ($_GET['id'] !== '')  {
				$sql = $sql . " and a.processprdid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by processprdname asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=1;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('processprdid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('processprdname'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['processprdid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['processprdname'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);	
	}
}