<?php
class PositionController extends Controller {
	public $menuname = 'position';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');		
		$positionid = isset ($_POST['positionid']) ? $_POST['positionid'] : '';
		$positionname = isset ($_POST['positionname']) ? $_POST['positionname'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$positionid = isset ($_GET['q']) ? $_GET['q'] : $positionid;
		$positionname = isset ($_GET['q']) ? $_GET['q'] : $positionname;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.positionid';
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
				->from('position t')
				->where('(positionname like :positionname)',
					array(':positionname'=>'%'.$positionname.'%'))
				->queryScalar();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('position t')
				->where('((positionname like :positionname)) and t.recordstatus=1',
					array(':positionname'=>'%'.$positionname.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('position t')
				->where('(positionname like :positionname)',
					array(':positionname'=>'%'.$positionname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('position t')
				->where('((positionname like :positionname)) and t.recordstatus=1',
					array(':positionname'=>'%'.$positionname.'%'))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'positionid'=>$data['positionid'],
				'positionname'=>$data['positionname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		if (isset($_POST['isNewRecord'])) {
			$sql = 'call Insertposition(:vpositionname,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateposition(:vid,:vpositionname,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$_POST['positionid'],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $_POST['positionid']);
		}
		$command->bindvalue(':vpositionname',$_POST['positionname'],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$_POST['recordstatus'],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-position"]["name"]);
		if (move_uploaded_file($_FILES["file-position"]["tmp_name"], $target_file)) {
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
					$languagename = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData($connection,array($id,$languagename,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['positionid'])?$_POST['positionid']:''),
				$_POST['positionname'],$_POST['recordstatus']));
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
				$sql = 'call Purgeposition(:vid,:vdatauser)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vdatauser',GetUserPC(),PDO::PARAM_STR);
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
			getmessage(true, getcatalog('chooseone'));
		}
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select positionid,positionname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from position a ";
		$positionid = filter_input(INPUT_GET,'positionid');
		$positionname = filter_input(INPUT_GET,'positionname');
		$sql .= " where coalesce(a.positionid,'') like '%".$positionid."%' 
			and coalesce(a.positionname,'') like '%".$positionname."%'";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.positionid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by positionname asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('position');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L');
		$this->pdf->colheader = array(getCatalog('positionid'),
			getCatalog('positionname'),
			getCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,155,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['positionid'],$row1['positionname'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='position';
		parent::actionDownxls();
		$sql = "select positionid,positionname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from position a ";
		$positionid = filter_input(INPUT_GET,'positionid');
		$positionname = filter_input(INPUT_GET,'positionname');
		$sql .= " where coalesce(a.positionid,'') like '%".$positionid."%' 
			and coalesce(a.positionname,'') like '%".$positionname."%'";
		if ($_GET['id'] !== '') {
			$sql = $sql . " and a.positionid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by positionname asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['positionid'])
				->setCellValueByColumnAndRow(1,$i,$row1['positionname'])							
				->setCellValueByColumnAndRow(2,$i,$row1['recordstatus']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}