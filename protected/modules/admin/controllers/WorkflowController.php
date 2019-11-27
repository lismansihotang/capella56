<?php
class WorkflowController extends Controller {
	public $menuname = 'workflow';
	public function actionGetData() {
		$id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'workflowid' => $id
		));
	}
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexwfgroup() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionsearchwfgroup();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexwfstatus() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionsearchwfstatus();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$workflowid = GetSearchText(array('POST','Q'),'workflowid');
		$wfname = GetSearchText(array('POST','Q'),'wfname');
		$wfdesc = GetSearchText(array('POST','Q'),'wfdesc');
		$wfminstat = GetSearchText(array('POST','Q'),'wfminstat');
		$wfmaxstat = GetSearchText(array('POST','Q'),'wfmaxstat');
		$groupname = GetSearchText(array('POST','Q'),'groupname');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','workflowid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('workflow t')
				->where('(workflowid like :workflowid) and (wfname like :wfname) and (wfdesc like :wfdesc) and (wfminstat like :wfminstat) 
					and (wfmaxstat like :wfmaxstat) '.
					(($groupname != '%%')?
						" and workflowid in (
								select distinct a.workflowid 
								from wfgroup a 
								join groupaccess b on b.groupaccessid = a.groupaccessid
								where b.groupname like '".$groupname."' 
							)":''),
					array(':workflowid'=>$workflowid,':wfname'=>$wfname,':wfdesc'=>$wfdesc,':wfminstat'=>$wfminstat,':wfmaxstat'=>$wfmaxstat))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('workflow t')
				->where('((workflowid like :workflowid) or (wfname like :wfname) or (wfdesc like :wfdesc) or (wfminstat like :wfminstat) or (wfmaxstat like :wfmaxstat)) and t.recordstatus=1',
					array(':workflowid'=>$workflowid,':wfname'=>$wfname,':wfdesc'=>$wfdesc,':wfminstat'=>$wfminstat,':wfmaxstat'=>$wfmaxstat))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,(select count(1) from wfgroup z where z.workflowid = t.workflowid) as jumlah')	
				->from('workflow t')
				->where('(workflowid like :workflowid) and (wfname like :wfname) and (wfdesc like :wfdesc) and (wfminstat like :wfminstat) 
					and (wfmaxstat like :wfmaxstat) '.
					(($groupname != '%%')?
						" and workflowid in (
								select distinct a.workflowid 
								from wfgroup a 
								join groupaccess b on b.groupaccessid = a.groupaccessid
								where b.groupname like '".$groupname."' 
							)":''),
					array(':workflowid'=>$workflowid,':wfname'=>$wfname,':wfdesc'=>$wfdesc,':wfminstat'=>$wfminstat,':wfmaxstat'=>$wfmaxstat))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,(select count(1) from wfgroup z where z.workflowid = t.workflowid) as jumlah')	
				->from('workflow t')
				->where('((workflowid like :workflowid) or (wfname like :wfname) or (wfdesc like :wfdesc) or (wfminstat like :wfminstat) or (wfmaxstat like :wfmaxstat)) and t.recordstatus=1',
						array(':workflowid'=>$workflowid,':wfname'=>$wfname,':wfdesc'=>$wfdesc,':wfminstat'=>$wfminstat,':wfmaxstat'=>$wfmaxstat))
				->order($sort.' '.$order)
				->queryAll();
		}		
		foreach($cmd as $data) {	
			$row[] = array(
				'workflowid'=>$data['workflowid'],
				'wfname'=>$data['wfname'],
				'wfdesc'=>$data['wfdesc'],
				'wfminstat'=>$data['wfminstat'],
				'wfmaxstat'=>$data['wfmaxstat'],
				'jumlah'=>$data['jumlah'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}	
	public function actionsearchwfgroup() {
		header("Content-Type: application/json");
		$id = 0;	
		if (isset($_POST['id']))
		{
			$id = $_POST['id'];
		}
		else 
		if (isset($_GET['id']))
		{
			$id = $_GET['id'];
		}
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','wfgroupid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('wfgroup t')
			->join('workflow p', 'p.workflowid=t.workflowid')
			->join('groupaccess q', 'q.groupaccessid=t.groupaccessid')
			->where('p.workflowid = '.$id)
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,p.wfdesc,q.groupname')	
			->from('wfgroup t')
			->join('workflow p', 'p.workflowid=t.workflowid')
			->join('groupaccess q', 'q.groupaccessid=t.groupaccessid')
			->where('p.workflowid = '.$id)
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();	
		foreach($cmd as $data) {	
			$row[] = array(
				'wfgroupid'=>$data['wfgroupid'],
				'workflowid'=>$data['workflowid'],
				'wfdesc'=>$data['wfdesc'],
				'groupaccessid'=>$data['groupaccessid'],
				'groupname'=>$data['groupname'],
				'wfbefstat'=>$data['wfbefstat'],
				'wfrecstat'=>$data['wfrecstat'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionsearchwfstatus() {
		header("Content-Type: application/json");
		$id = 0;	
		if (isset($_POST['id']))
		{
			$id = $_POST['id'];
		}
		else 
		if (isset($_GET['id']))
		{
			$id = $_GET['id'];
		}
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','wfstatusid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('wfstatus t')
			->join('workflow p', 'p.workflowid=t.workflowid')
			->where('p.workflowid = '.$id)
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select()	
			->from('wfstatus t')
			->join('workflow p', 'p.workflowid=t.workflowid')
			->where('p.workflowid = '.$id)
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'wfstatusid'=>$data['wfstatusid'],
				'workflowid'=>$data['workflowid'],
				'wfdesc'=>$data['wfdesc'],
				'wfstat'=>$data['wfstat'],
				'wfstatusname'=>$data['wfstatusname'],
				'backcolor'=>$data['backcolor'],
				'fontcolor'=>$data['fontcolor'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (int)$arraydata[0];
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$sql = 'call ModifWorkflow(:vid,:vwfname,:vwfdesc,:vwfminstat,:vwfmaxstat,:vrecordstatus,:vdatauser)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		$command->bindvalue(':vwfname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vwfdesc',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vwfminstat',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vwfmaxstat',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-workflow"]["name"]);
		if (move_uploaded_file($_FILES["file-workflow"]["tmp_name"], $target_file)) {
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
					$wfname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$wfdesc = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$wfminstat = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$wfmaxstat = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$this->ModifyData($connection,array($id,$wfname,$wfdesc,$wfminstat,$wfmaxstat,$recordstatus));
				}
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode($e->errorInfo));
			}
    }
	}
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['workflow-workflowid'])?$_POST['workflow-workflowid']:''),
				$_POST['workflow-wfname'],
				$_POST['workflow-wfdesc'],
				$_POST['workflow-wfminstat'],
				$_POST['workflow-wfmaxstat'],
				isset($_POST['workflow-recordstatus'])?1:0));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode($e->errorInfo));
		}
	}
	private function ModifyDatawfgroup($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertwfgroup(:vworkflowid,:vgroupaccessid,:vwfbefstat,:vwfrecstat,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatewfgroup(:vid,:vworkflowid,:vgroupaccessid,:vwfbefstat,:vwfrecstat,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vworkflowid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vgroupaccessid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vwfbefstat',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vwfrecstat',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionSaveWfgroup() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDatawfgroup($connection,
				array((isset($_POST['wfgroupid'])?$_POST['wfgroupid']:''),
				$_POST['workflowid'],
				$_POST['groupaccessid'],
				$_POST['wfbefstat'],
				$_POST['wfrecstat']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode($e->errorInfo));
		}
	}
	private function ModifyDatawfstatus($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertwfstatus(:vworkflowid,:vwfstat,:vwfstatusname,:vbackcolor,:vfontcolor,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatewfstatus(:vid,:vworkflowid,:vwfstat,:vwfstatusname,:vbackcolor,:vfontcolor,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vworkflowid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vwfstat',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vwfstatusname',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vbackcolor',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vfontcolor',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionSavewfstatus() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDatawfstatus($connection,array((isset($_POST['wfstatusid'])?$_POST['wfstatusid']:''),
				$_POST['workflowid'],$_POST['wfstat'],$_POST['wfstatusname'],$_POST['backcolor'],$_POST['fontcolor']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode($e->errorInfo));
		}
	}
	public function actionPurge() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeworkflow(:vid,:vdatauser)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vdatauser',GetUserPC(),PDO::PARAM_STR);
				$command->execute();				
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode($e->errorInfo));
			}
		}
		else {
			GetMessage(true,'chooseone');
		}
	}	
	public function actionPurgewfgroup() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgewfgroup(:vid,:vdatauser)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vdatauser',GetUserPC(),PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode($e->errorInfo));
			}
		}
		else {
			GetMessage(true,'chooseone');
		}
	}
	public function actionPurgewfstatus() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgewfstatus(:vid,:vdatauser)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vdatauser',GetUserPC(),PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode($e->errorInfo));
			}
		}
		else {
			GetMessage(true,'chooseone');
		}
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['titleid'] = GetCatalog('workflowid');
		$this->dataprint['titlewfname'] = GetCatalog('wfname');
		$this->dataprint['titlewfdesc'] = GetCatalog('wfdesc');
		$this->dataprint['titlewfmaxstat'] = GetCatalog('wfmaxstat');
		$this->dataprint['titlewfminstat'] = GetCatalog('wfminstat');
		$this->dataprint['titlewfbefstat'] = GetCatalog('wfbefstat');
		$this->dataprint['titlewfrecstat'] = GetCatalog('wfrecstat');
		$this->dataprint['titlegroupname'] = GetCatalog('groupname');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['wfname'] = GetSearchText(array('GET'),'wfname');
    $this->dataprint['wfdesc'] = GetSearchText(array('GET'),'wfdesc');
    $this->dataprint['wfminstat'] = GetSearchText(array('GET'),'wfminstat');
    $this->dataprint['wfmaxstat'] = GetSearchText(array('GET'),'wfmaxstat');
  }
}
