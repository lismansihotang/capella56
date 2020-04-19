<?php
class LevelorgController extends Controller {
	public $menuname = 'levelorg';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$levelorgid = isset ($_POST['levelorgid']) ? $_POST['levelorgid'] : '';
		$levelorgname = isset ($_POST['levelorgname']) ? $_POST['levelorgname'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$levelorgid = isset ($_GET['q']) ? $_GET['q'] : $levelorgid;
		$levelorgname = isset ($_GET['q']) ? $_GET['q'] : $levelorgname;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.levelorgid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('levelorg t')
				->where('(levelorgname like :levelorgname)',
					array(':levelorgname'=>'%'.$levelorgname.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('levelorg t')
				->where('((levelorgname like :levelorgname)) and t.recordstatus=1',
					array(':levelorgname'=>'%'.$levelorgname.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('levelorg t')
				->where('(levelorgname like :levelorgname)',
					array(':levelorgname'=>'%'.$levelorgname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('levelorg t')
				->where('((levelorgname like :levelorgname)) and t.recordstatus=1',
					array(':levelorgname'=>'%'.$levelorgname.'%'))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'levelorgid'=>$data['levelorgid'],
				'levelorgname'=>$data['levelorgname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertlevelorg(:vlevelorgname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatelevelorg(:vid,:vlevelorgname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vlevelorgname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-levelorg"]["name"]);
		if (move_uploaded_file($_FILES["file-levelorg"]["tmp_name"], $target_file)) {
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
					$levelorgname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData($connection,array($id,$levelorgname,$recordstatus));
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
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['levelorgid'])?$_POST['levelorgid']:''),$_POST['levelorgname'],$_POST['recordstatus']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}	
	public function actionPurge() {
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgelevelorg(:vid,:vcreatedby)';
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
		else
		{
			getmessage(true,getcatalog('chooseone'));
		}
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select levelorgid,levelorgname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from levelorg a ";
		$levelorgid = filter_input(INPUT_GET,'levelorgid');
		$levelorgname = filter_input(INPUT_GET,'levelorgname');
		$sql .= " where coalesce(a.levelorgid,'') like '%".$levelorgid."%' 
			and coalesce(a.levelorgname,'') like '%".$levelorgname."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.levelorgid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by levelorgname asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getcatalog('levelorg');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L');
		$this->pdf->colheader = array(getcatalog('levelorgid'),
																	getcatalog('levelorgname'),
																	getcatalog('recordstatus'));
		$this->pdf->setwidths(array(15,155,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['levelorgid'],$row1['levelorgname'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='levelorg';
		parent::actionDownxls();
		$sql = "select levelorgid,levelorgname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from levelorg a ";
		$levelorgid = filter_input(INPUT_GET,'levelorgid');
		$levelorgname = filter_input(INPUT_GET,'levelorgname');
		$sql .= " where coalesce(a.levelorgid,'') like '%".$levelorgid."%' 
			and coalesce(a.levelorgname,'') like '%".$levelorgname."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.levelorgid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by levelorgname asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['levelorgid'])
				->setCellValueByColumnAndRow(1,$i,$row1['levelorgname'])							
				->setCellValueByColumnAndRow(2,$i,$row1['recordstatus']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}