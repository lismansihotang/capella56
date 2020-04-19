<?php
class GroupaccessController extends Controller {
	public $menuname = 'groupaccess';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexgroupmenu() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionsearchgroupmenu();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexuserdash() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionsearchuserdash();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$groupaccessid = GetSearchText(array('POST','Q'),'groupaccessid');
		$groupname = GetSearchText(array('POST','Q'),'groupname');
		$menuname = GetSearchText(array('POST','Q'),'menuname');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','groupaccessid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('groupaccess t') 
				->where("(coalesce(groupaccessid,'') like :groupaccessid) 
					and (coalesce(groupname,'') like :groupname)".
					(($menuname != '%%')?" and t.groupaccessid in (
					select distinct za.groupaccessid 
					from groupmenu za 
					left join menuaccess zb on zb.menuaccessid = za.menuaccessid 
					where zb.menuname like '%".$menuname."%')":''),
					array(':groupaccessid'=>$groupaccessid,':groupname'=>$groupname))			
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('groupaccess t')
				->where('((groupaccessid like :groupaccessid) or (groupname like :groupname)) 
					and t.recordstatus = 1',
					array(':groupaccessid'=>$groupaccessid,':groupname'=>$groupname))			
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('groupaccess t')
				->where("(coalesce(groupaccessid,'') like :groupaccessid) 
					and (coalesce(groupname,'') like :groupname)".
					(($menuname != '%%')?" and t.groupaccessid in (
					select distinct za.groupaccessid 
					from groupmenu za 
					left join menuaccess zb on zb.menuaccessid = za.menuaccessid 
					where zb.menuname like '%".$menuname."%')":''),
					array(':groupaccessid'=>$groupaccessid,':groupname'=>$groupname))			
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('groupaccess t')
				->where('((groupaccessid like :groupaccessid) or (groupname like :groupname)) 
					and t.recordstatus = 1',
				array(':groupaccessid'=>$groupaccessid,':groupname'=>$groupname))			
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'groupaccessid'=>$data['groupaccessid'],
				'groupname'=>$data['groupname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionsearchgroupmenu() {
		header('Content-Type: application/json');
		$id=0;
		if (isset($_POST['id']))
		{
			$id = $_POST['id'];
		}
		else 
		if (isset($_GET['id']))
		{
			$id = $_GET['id'];
		} else if (isset($_POST['groupaccessid']))
		{
			$id = $_POST['groupaccessid'];
		}
		$menuname = GetSearchText(array('POST','GET'),'menuname');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','groupmenuid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('groupmenu t')
			->leftjoin('menuaccess q','q.menuaccessid=t.menuaccessid')
			->where("t.groupaccessid = ".$id." and q.menuname like '".$menuname."'")			
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,q.menuname,q.description')	
			->from('groupmenu t')
			->leftjoin('menuaccess q','q.menuaccessid=t.menuaccessid')
			->where('t.groupaccessid = '.$id." and q.menuname like '".$menuname."'")
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();		
		foreach($cmd as $data) {	
			$row[] = array(
				'groupmenuid'=>$data['groupmenuid'],
				'groupaccessid'=>$data['groupaccessid'],
				'menuaccessid'=>$data['menuaccessid'],
				'description'=>$data['description'],
				'isread'=>$data['isread'],
				'iswrite'=>$data['iswrite'],
				'ispost'=>$data['ispost'],
				'isreject'=>$data['isreject'],
				'isupload'=>$data['isupload'],
				'isdownload'=>$data['isdownload'],
				'ispurge'=>$data['ispurge'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionsearchuserdash() {
		header('Content-Type: application/json');
		$id=0;
		if (isset($_POST['id']))
		{
			$id = $_POST['id'];
		}
		else 
		if (isset($_GET['id']))
		{
			$id = $_GET['id'];
		} else if (isset($_POST['groupaccessid']))
		{
			$id = $_POST['groupaccessid'];
		}
		$menuname = GetSearchText(array('POST','GET'),'menuname');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','userdashid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('userdash t')
			->leftjoin('menuaccess q','q.menuaccessid=t.menuaccessid')
			->leftjoin('widget r','r.widgetid=t.widgetid')
			->where("t.groupaccessid = ".$id)			
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,r.widgetname,q.menuname')	
			->from('userdash t')
			->leftjoin('menuaccess q','q.menuaccessid=t.menuaccessid')
			->leftjoin('widget r','r.widgetid=t.widgetid')
			->where('t.groupaccessid = '.$id)
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();		
		foreach($cmd as $data) {	
			$row[] = array(
				'userdashid'=>$data['userdashid'],
				'groupaccessid'=>$data['groupaccessid'],
				'widgetid'=>$data['widgetid'],
				'widgetname'=>$data['widgetname'],
				'menuaccessid'=>$data['menuaccessid'],
				'menuname'=>$data['menuname'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionGetData() {
		$id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'groupaccessid' => $id
		));
	}
	private function ModifyData($connection,$arraydata) {
		$id = (int)$arraydata[0];
		$sql = 'call Modifgroupaccess(:vid,:vgroupname,:vrecordstatus,:vdatauser)';
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		$command->bindvalue(':vgroupname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-groupaccess"]["name"]);
		if (move_uploaded_file($_FILES["file-groupaccess"]["tmp_name"], $target_file)) {
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
					$groupname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData($connection,array($id,$groupname,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['groupaccess-groupaccessid'])?$_POST['groupaccess-groupaccessid']:''),
				$_POST['groupaccess-groupname'],
				isset($_POST['groupaccess-recordstatus'])?($_POST['groupaccess-recordstatus']=="on")?1:0:0));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	private function ModifyDataGroupmenu($connection,$arraydata) {	
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertgroupmenu(:vmenuaccessid,:vgroupaccessid,:visread,:viswrite,:vispost,:visreject,:visupload,:visdownload,:vispurge,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updategroupmenu(:vid,:vmenuaccessid,:vgroupaccessid,:visread,:viswrite,:vispost,:visreject,:visupload,:visdownload,:vispurge,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		}
		$command->bindvalue(':vgroupaccessid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vmenuaccessid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':visread',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':viswrite',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vispost',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':visreject',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':visupload',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':visdownload',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vispurge',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionSaveGroupmenu() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDataGroupmenu($connection,array((isset($_POST['groupmenuid'])?$_POST['groupmenuid']:''),
				$_POST['groupaccessid'],$_POST['menuaccessid'],
				$_POST['isread'],$_POST['iswrite'],$_POST['ispost'],
				$_POST['isreject'],$_POST['isupload'],$_POST['isdownload'],$_POST['ispurge']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	private function ModifyDataUserDash($connection,$arraydata) {	
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertuserdash(:vgroupaccessid,:vwidgetid,:vmenuaccessid,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateuserdash(:vid,:vgroupaccessid,:vwidgetid,:vmenuaccessid,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		}
		$command->bindvalue(':vgroupaccessid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vwidgetid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vmenuaccessid',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser',GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionSaveUserDash() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDataUserDash($connection,array((isset($_POST['userdashid'])?$_POST['userdashid']:''),
				$_POST['groupaccessid'],
				$_POST['widgetid'],
				$_POST['menuaccessid']));
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
				$sql = 'call Purgegroupaccess(:vid,:vdatauser)';
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
	public function actionPurgegroupmenu() {
		parent::actionPurge();
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgegroupmenu(:vid,:vdatauser)';
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
	public function actionPurgeuserdash() {
		parent::actionPurge();
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeuserdash(:vid,:vdatauser)';
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
		$this->dataprint['groupname'] = GetSearchText(array('GET'),'groupname');
		$this->dataprint['menuname'] = GetSearchText(array('GET'),'menuname');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'groupaccessid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlegroupname'] = GetCatalog('groupname');
		$this->dataprint['titlemenuname'] = GetCatalog('menuname');
		$this->dataprint['titlemenuurl'] = GetCatalog('menuurl');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}