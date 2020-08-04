<?php
class MaterialstatusController extends Controller {
	public $menuname = 'materialstatus';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$materialstatusid = GetSearchText(array('POST','Q'),'materialstatusid');
		$materialstatusname = GetSearchText(array('POST','Q'),'materialstatusname');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','materialstatusid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;	
		$result = array();
		$row = array();
		$dependency = new CDbCacheDependency('SELECT MAX(updatedate) FROM materialstatus');
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')	
				->from('materialstatus t')
				->where('(materialstatusid like :materialstatusid) and (materialstatusname like :materialstatusname)',
					array(':materialstatusid'=>$materialstatusid,
					':materialstatusname'=>$materialstatusname))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')	
				->from('materialstatus t')
				->where('((materialstatusid like :materialstatusid) or (materialstatusname like :materialstatusname)) and t.recordstatus=1',
					array(':materialstatusid'=>$materialstatusid,
					':materialstatusname'=>$materialstatusname))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select()	
				->from('materialstatus t')
				->where('(materialstatusid like :materialstatusid) and (materialstatusname like :materialstatusname)',
					array(':materialstatusid'=>$materialstatusid,':materialstatusname'=>$materialstatusname))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select()	
				->from('materialstatus t')
				->where('((materialstatusid like :materialstatusid) or (materialstatusname like :materialstatusname)) and t.recordstatus=1',
					array(':materialstatusid'=>$materialstatusid,':materialstatusname'=>$materialstatusname))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'materialstatusid'=>$data['materialstatusid'],
				'materialstatusname'=>$data['materialstatusname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertmaterialstatus(:vmaterialstatusname,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updatematerialstatus(:vid,:vmaterialstatusname,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vmaterialstatusname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-materialstatus"]["name"]);
		if (move_uploaded_file($_FILES["file-materialstatus"]["tmp_name"], $target_file)) {
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
					$materialstatusname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData($connection,array($id,$materialstatusname,$recordstatus));
				}
				$transaction->commit();			
				GetMessage(false,'insertsuccess');
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
			$this->ModifyData($connection,array((isset($_POST['materialstatusid'])?$_POST['materialstatusid']:''),$_POST['materialstatusname'],$_POST['recordstatus']));
			$transaction->commit();			
			GetMessage(false,'insertsuccess');
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
				$sql = 'call Purgematerialstatus(:vid,:vdatauser)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vdatauser',GetUserPC(),PDO::PARAM_STR);
				$command->execute();				
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
		}
		else {
			GetMessage(true, 'chooseone');
		}
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['materialstatusname'] = GetSearchText(array('GET'),'materialstatusname');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'materialstatusid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlematerialstatusname'] = GetCatalog('materialstatusname');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}