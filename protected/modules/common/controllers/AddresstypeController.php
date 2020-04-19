<?php
class AddresstypeController extends Controller {
	public $menuname = 'addresstype';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$addresstypeid = GetSearchText(array('POST','Q'),'addresstypeid');
		$addresstypename = GetSearchText(array('POST','Q'),'addresstypename');
		$page = GetSearchText(array('POST'),'page',1,'int');
		$rows = GetSearchText(array('POST'),'rows',10,'int');
		$sort = GetSearchText(array('POST'),'sort','addresstypeid','int');
		$order = GetSearchText(array('POST'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$dependency = new CDbCacheDependency('SELECT MAX(updatedate) FROM addresstype');
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')	
				->from('addresstype t')
				->where('(addresstypeid like :addresstypeid) and (addresstypename like :addresstypename)',
					array(':addresstypeid'=>$addresstypeid,':addresstypename'=>$addresstypename))
				->queryScalar();
		}
		else  {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')	
				->from('addresstype t')
				->where('((addresstypename like :addresstypename) or (addresstypename like :addresstypename)) and t.recordstatus=1',
					array(':addresstypeid'=>$addresstypeid,':addresstypename'=>$addresstypename))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select()	
				->from('addresstype t')
				->where('(addresstypeid like :addresstypeid) and (addresstypename like :addresstypename)',
					array(':addresstypeid'=>$addresstypeid,':addresstypename'=>$addresstypename))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select()	
				->from('addresstype t')
				->where('((addresstypeid like :addresstypeid) or (addresstypename like :addresstypename)) and t.recordstatus=1',
					array(':addresstypeid'=>$addresstypeid,':addresstypename'=>$addresstypename))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'addresstypeid'=>$data['addresstypeid'],
				'addresstypename'=>$data['addresstypename'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertaddresstype(:vaddresstypename,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateaddresstype(:vid,:vaddresstypename,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vaddresstypename',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-addresstype"]["name"]);
		if (move_uploaded_file($_FILES["file-addresstype"]["tmp_name"], $target_file)) {
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
					$addresstypename = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData($connection,array($id,$addresstypename,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['addresstypeid'])?$_POST['addresstypeid']:''),$_POST['addresstypename'],$_POST['recordstatus']));
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
				$sql = 'call Purgeaddresstype(:vid,:vdatauser)';
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
			GetMessage(true,getcatalog('chooseone'));
		}
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['addresstypename'] = GetSearchText(array('GET'),'addresstypename');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'addresstypeid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titleaddresstypename'] = GetCatalog('addresstypename');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}