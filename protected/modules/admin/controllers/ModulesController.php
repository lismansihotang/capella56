<?php
class ModulesController extends Controller {
	public $menuname = 'modules';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$moduleid = GetSearchText(array('POST','Q'),'moduleid');
		$modulename = GetSearchText(array('POST','Q'),'modulename');
		$moduledesc = GetSearchText(array('POST','Q'),'moduledesc');
		$moduleicon = GetSearchText(array('POST','Q'),'moduleicon');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','moduleid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('modules t')
				->where('(moduleid like :moduleid) and (modulename like :modulename) and (moduledesc like :moduledesc) and (moduleicon like :moduleicon)',
					array(':moduleid'=>$moduleid,':modulename'=>$modulename,':moduledesc'=>$moduledesc,':moduleicon'=>$moduleicon))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('modules t')
				->where('((moduleid like :moduleid) or (modulename like :modulename) or (moduledesc like :moduledesc) or (moduleicon like :moduleicon)) and t.recordstatus=1',
					array(':moduleid'=>$moduleid,':modulename'=>$modulename,':moduledesc'=>$moduledesc,':moduleicon'=>$moduleicon))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()			
				->from('modules t')
				->where('(moduleid like :moduleid) and (modulename like :modulename) and (moduledesc like :moduledesc) and (moduleicon like :moduleicon)',
												array(':moduleid'=>$moduleid,':modulename'=>$modulename,':moduledesc'=>$moduledesc,':moduleicon'=>$moduleicon))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()			
				->from('modules t')
				->where('((moduleid like :moduleid) or (modulename like :modulename) or (moduledesc like :moduledesc) or (moduleicon like :moduleicon)) and t.recordstatus=1',
					array(':moduleid'=>$moduleid,':modulename'=>$modulename,':moduledesc'=>$moduledesc,':moduleicon'=>$moduleicon))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'moduleid'=>$data['moduleid'],
				'modulename'=>$data['modulename'],
				'moduledesc'=>$data['moduledesc'],
				'moduleicon'=>$data['moduleicon'],
				'isinstall'=>$data['isinstall'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertmodules(:vmodulename,:vmoduledesc,:vmoduleicon,:visinstall,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatemodules(:vid,:vmodulename,:vmoduledesc,:vmoduleicon,:visinstall,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vmodulename',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vmoduledesc',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vmoduleicon',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':visinstall',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-modules"]["name"]);
		if (move_uploaded_file($_FILES["file-modules"]["tmp_name"], $target_file)) {
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
					$modulename = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$moduledesc = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$moduleicon = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$isinstall = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$this->ModifyData($connection,array($id,$modulename,$moduledesc,$moduleicon,$isinstall,$recordstatus));
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
		header("Content-Type: application/json");
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->Modifydata($connection,array((isset($_POST['moduleid'])?$_POST['moduleid']:''),$_POST['modulename'],$_POST['moduledesc'],$_POST['moduleicon'],$_POST['isinstall'],$_POST['recordstatus']));
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
		header("Content-Type: application/json");
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgemodules(:vid,:vcreatedby)';
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
			GetMessage(true,'chooseone');
		}
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['titleid'] = GetCatalog('moduleid');
		$this->dataprint['titlemodulename'] = GetCatalog('modulename');
		$this->dataprint['titlemoduledesc'] = GetCatalog('moduledesc');
		$this->dataprint['titlemoduleicon'] = GetCatalog('moduleicon');
		$this->dataprint['titleisinstall'] = GetCatalog('isinstall');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
		$this->dataprint['url'] = Yii::app()->params['baseUrl'];
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['modulename'] = GetSearchText(array('GET'),'modulename');
    $this->dataprint['moduledesc'] = GetSearchText(array('GET'),'moduledesc');
    $this->dataprint['moduleicon'] = GetSearchText(array('GET'),'moduleicon');
  }
}
