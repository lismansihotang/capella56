<?php
class QcparamController extends Controller {
	public $menuname = 'qcparam';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$qcparamid = isset ($_POST['qcparamid']) ? $_POST['qcparamid'] : '';
		$qcparamname = isset ($_POST['qcparamname']) ? $_POST['qcparamname'] : '';
		$qcparamid = isset ($_GET['q']) ? $_GET['q'] : $qcparamid;
		$qcparamname = isset ($_GET['q']) ? $_GET['q'] : $qcparamname;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'qcparamid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort,'t.') > 0)?$sort:'t.'.$sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('qcparam t')
				->where("(coalesce(qcparamid,'') like :qcparamid) 
					and (coalesce(qcparamname,'') like :qcparamname) 
					",
				array(':qcparamid'=>'%'.$qcparamid.'%',
					':qcparamname'=>'%'.$qcparamname.'%',
					))
				->queryScalar();
		}
		else {			
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('qcparam t')
				->where("(coalesce(qcparamid,'') like :qcparamid) 
					or (coalesce(qcparamname,'') like :qcparamname) 
				",
				array(':qcparamid'=>'%'.$qcparamid.'%',
					':qcparamname'=>'%'.$qcparamname.'%',
					))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')			
				->from('qcparam t')
				->where("(coalesce(qcparamid,'') like :qcparamid) 
					and (coalesce(qcparamname,'') like :qcparamname) 
					",
				array(':qcparamid'=>'%'.$qcparamid.'%',
					':qcparamname'=>'%'.$qcparamname.'%',
					))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')			
				->from('qcparam t')
				->where("(coalesce(qcparamid,'') like :qcparamid) 
					or (coalesce(qcparamname,'') like :qcparamname) 
				",
				array(':qcparamid'=>'%'.$qcparamid.'%',
					':qcparamname'=>'%'.$qcparamname.'%',
					))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
			'qcparamid'=>$data['qcparamid'],
			'qcparamname'=>$data['qcparamname'],
			'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {		
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertqcparam(:vqcparamname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateqcparam(:vid,:vqcparamname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vqcparamname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-qcparam"]["name"]);
		if (move_uploaded_file($_FILES["file-qcparam"]["tmp_name"], $target_file)) {
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
					$qcparamcode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$qcparamname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$this->ModifyData($connection,array($id,$qcparamcode,$qcparamname,$recordstatus));
				}
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    }
	}
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['qcparamid'])?$_POST['qcparamid']:''),
				$_POST['qcparamname'],
				$_POST['recordstatus']
			));
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
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeqcparam(:vid,:vcreatedby)';
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
		$sql = "select qcparamid,qcparamname,
				case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from qcparam a ";
		$qcparamid = filter_input(INPUT_GET,'qcparamid');
		$qcparamcode = filter_input(INPUT_GET,'qcparamcode');
		$qcparamname = filter_input(INPUT_GET,'qcparamname');
		$sql .= " where coalesce(a.qcparamid,'') like '%".$qcparamid."%' 
			and coalesce(a.qcparamname,'') like '%".$qcparamname."%'
			and coalesce(a.qcparamcode,'') like '%".$qcparamcode."%'
			";
		if ($_GET['id'] !== '') {
			$sql = $sql . " and a.qcparamid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by qcparamname asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		$this->pdf->title=GetCatalog('qcparam');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('qcparamid'),
			GetCatalog('qcparamname'),
			GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,135,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['qcparamid'],$row1['qcparamname'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='qcparam';
		parent::actionDownxls();
		$sql = "select qcparamid,qcparamname,
				case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from qcparam a ";
		$qcparamid = filter_input(INPUT_GET,'qcparamid');
		$qcparamname = filter_input(INPUT_GET,'qcparamname');
		$sql .= " where coalesce(a.qcparamid,'') like '%".$qcparamid."%' 
			and coalesce(a.qcparamname,'') like '%".$qcparamname."%'
			";
		if ($_GET['id'] !== '') {
			$sql = $sql . " and a.qcparamid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by qcparamname asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=1;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('qcparamid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('qcparamname'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['qcparamid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['qcparamname'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}