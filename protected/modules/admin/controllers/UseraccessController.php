<?php
class UseraccessController extends Controller {
	public $menuname = 'useraccess';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexusergroup() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionSearchusergroup();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexuserfav() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionSearchuserfav();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$useraccessid	= GetSearchText(array('POST','Q'),'useraccessid');
		$username = GetSearchText(array('POST','Q'),'username');
		$realname = GetSearchText(array('POST','Q'),'realname');
		$password = GetSearchText(array('POST','Q'),'password');
		$email = GetSearchText(array('POST','Q'),'email');
		$phoneno = GetSearchText(array('POST','Q'),'phoneno');
		$languagename = GetSearchText(array('POST','Q'),'languagename');
		$themename = GetSearchText(array('POST','Q'),'themename');
		$groupname = GetSearchText(array('POST','Q'),'groupname');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','useraccessid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('useraccess t')
				->leftjoin('language l', 'l.languageid=t.languageid')
				->leftjoin('theme h', 'h.themeid=t.themeid')
				->where("
					((coalesce(t.useraccessid,'') like :useraccessid) 
					or (coalesce(t.username,'') like :username) 
					or (coalesce(t.realname,'') like :realname) 
					or (coalesce(t.email,'') like :email) 
					or (coalesce(t.phoneno,'') like :phoneno) 
					or (coalesce(l.languagename,'') like :languagename) 
					or (coalesce(h.themename,'') like :themename)) 
					and t.recordstatus=1",
						array(':useraccessid'=>$useraccessid,':username'=>$username,':realname'=>$realname,':email'=>$email,':phoneno'=>$phoneno,':languagename'=>$languagename,':themename'=>$themename,':groupname'=>$groupname))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('useraccess t')
				->leftjoin('language l', 'l.languageid=t.languageid')
				->leftjoin('theme h', 'h.themeid=t.themeid')
				->where("
					(coalesce(t.useraccessid,'') like :useraccessid) 
					and (coalesce(t.username,'') like :username) 
					and (coalesce(t.realname,'') like :realname) 
					and (coalesce(t.email,'') like :email) 
					and (coalesce(t.phoneno,'') like :phoneno) 
					and (coalesce(l.languagename,'') like :languagename) 
          and (coalesce(h.themename,'') like :themename)".
          (($groupname != '%%')?"
					and t.useraccessid in 
					(
					select distinct p.useraccessid 
					from usergroup p 
					left join groupaccess q on q.groupaccessid = p.groupaccessid
					where (coalesce(q.groupname,'') like '".$groupname."')
					) ":""),
						array(':useraccessid'=>$useraccessid,':username'=>$username,':realname'=>$realname,':email'=>$email,':phoneno'=>$phoneno,':languagename'=>$languagename,':themename'=>$themename))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->selectdistinct("t.*,l.languagename,h.themename, (select count(1) from usergroup a where a.useraccessid = t.useraccessid) as jumlah")			
				->from('useraccess t')
				->leftjoin('language l', 'l.languageid=t.languageid')
				->leftjoin('theme h', 'h.themeid=t.themeid')
				->where("
					((coalesce(t.useraccessid,'') like :useraccessid) 
					or (coalesce(t.username,'') like :username) 
					or (coalesce(t.realname,'') like :realname) 
					or (coalesce(t.email,'') like :email) 
					or (coalesce(t.phoneno,'') like :phoneno) 
					or (coalesce(l.languagename,'') like :languagename) 
					or (coalesce(h.themename,'') like :themename)) 
					and t.recordstatus=1",
            array(':useraccessid'=>$useraccessid,':username'=>$username,':realname'=>$realname,
              ':email'=>$email,':phoneno'=>$phoneno,':languagename'=>$languagename,':themename'=>$themename,
              ':groupname'=>$groupname))
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->selectdistinct('t.*,l.languagename,h.themename,(select count(1) from usergroup a where a.useraccessid = t.useraccessid) as jumlah')			
				->from('useraccess t')
				->leftjoin('language l', 'l.languageid=t.languageid')
				->leftjoin('theme h', 'h.themeid=t.themeid')
				->leftjoin('usergroup p', 'p.useraccessid=t.useraccessid')
				->leftjoin('groupaccess q', 'q.groupaccessid=p.groupaccessid')
				->where("
					(coalesce(t.useraccessid,'') like :useraccessid) 
					and (coalesce(t.username,'') like :username) 
					and (coalesce(t.realname,'') like :realname) 
					and (coalesce(t.email,'') like :email) 
					and (coalesce(t.phoneno,'') like :phoneno) 
					and (coalesce(l.languagename,'') like :languagename) 
					and (coalesce(h.themename,'') like :themename)".
					(($groupname != '%%')?"
					and t.useraccessid in 
					(
					select distinct p.useraccessid 
					from usergroup p 
					left join groupaccess q on q.groupaccessid = p.groupaccessid
					where (coalesce(q.groupname,'') like '".$groupname."')
					)":""),
						array(':useraccessid'=>$useraccessid,':username'=>$username,':realname'=>$realname,':email'=>$email,':phoneno'=>$phoneno,':languagename'=>$languagename,':themename'=>$themename))
				->order($sort.' '.$order)
				->offset($offset)
				->limit($rows)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'useraccessid'=>$data['useraccessid'],
				'username'=>$data['username'],
				'realname'=>$data['realname'],
				'password'=>$data['password'],
				'email'=>$data['email'],
				'phoneno'=>$data['phoneno'],
				'languageid'=>$data['languageid'],
				'languagename'=>$data['languagename'],
				'themeid'=>$data['themeid'],
				'themename'=>$data['themename'],
				'isonline'=>$data['isonline'],
				'jumlah'=>$data['jumlah'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionsearchusergroup() {
		header("Content-Type: application/json");
		$id = 0;	
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
		}
		else 
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
		}
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.usergroupid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('usergroup t')
			->leftjoin('useraccess p', 'p.useraccessid=t.useraccessid')
			->leftjoin('groupaccess q', 'q.groupaccessid=t.groupaccessid')
			->where('t.useraccessid = '.$id)
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select()	
			->from('usergroup t')
			->leftjoin('useraccess p', 'p.useraccessid=t.useraccessid')
			->leftjoin('groupaccess q', 'q.groupaccessid=t.groupaccessid')
			->where('t.useraccessid = '.$id)
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'usergroupid'=>$data['usergroupid'],
				'useraccessid'=>$data['useraccessid'],
				'username'=>$data['username'],
				'groupaccessid'=>$data['groupaccessid'],
				'groupname'=>$data['groupname'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}	
	public function actionsearchuserfav() {
		header("Content-Type: application/json");
		$id = 0;	
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
		}
		else 
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
		}
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.userfavid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('userfav t')
			->leftjoin('menuaccess p', 'p.menuaccessid=t.menuaccessid')
			->where('t.useraccessid = '.$id)
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select()	
			->from('usergroup t')
			->from('userfav t')
			->leftjoin('menuaccess p', 'p.menuaccessid=t.menuaccessid')
			->where('t.useraccessid = '.$id)
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'userfavid'=>$data['userfavid'],
				'useraccessid'=>$data['useraccessid'],
				'username'=>$data['username'],
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
			'useraccessid' => $id
		));
	}
	private function ModifyData($connection,$arraydata) {
		$id = (int)$arraydata[0];
		$sql = 'call Modifuseraccess(:vid,:vusername,:vrealname,:vpassword,:vemail,:vphoneno,:vlanguageid,:vthemeid,:vrecordstatus,:vdatauser)';
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		$command->bindvalue(':vusername',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrealname',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser',getuserpc(),PDO::PARAM_STR);
		$password = '';
		if ($id >0) {
			$sql = "select `password` from useraccess where username = '".$arraydata[1]."'";
			$password = Yii::app()->db->createCommand($sql)->queryScalar();
		}
		$newpass = md5($arraydata[3]);
		if ($password !== $arraydata[3])
		{
			$command->bindvalue(':vpassword',$newpass,PDO::PARAM_STR);
		}
		else
		{
			$command->bindvalue(':vpassword',$password,PDO::PARAM_STR);
		}
		$command->bindvalue(':vemail',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vphoneno',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vlanguageid',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vthemeid',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[8],PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-useraccess"]["name"]);
		if (move_uploaded_file($_FILES["file-useraccess"]["tmp_name"], $target_file)) {
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
					$username = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$realname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$password = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$email = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$phoneno = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$languagename = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$languageid = Yii::app()->db->createCommand("select languageid from language where languagename = '".$languagename."'")->queryScalar();
					$themename = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$themeid = Yii::app()->db->createCommand("select themeid from theme where themename = '".$themename."'")->queryScalar();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData($connection,array($id,$username,$realname,$password,$email,$phoneno,$languageid,$themeid,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['useraccess-useraccessid'])?$_POST['useraccess-useraccessid']:''),
				$_POST['useraccess-username'],$_POST['useraccess-realname'],$_POST['useraccess-password'],
				$_POST['useraccess-email'],$_POST['useraccess-phoneno'],$_POST['useraccess-languageid'],$_POST['useraccess-themeid'],
				isset($_POST['useraccess-recordstatus'])?1:0));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}	
	}
	private function ModifyDataUsergroup($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertusergroup(:vuseraccessid,:vgroupaccessid,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateusergroup(:vid,:vuseraccessid,:vgroupaccessid,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vuseraccessid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vgroupaccessid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionSaveusergroup() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDataUsergroup($connection,array((isset($_POST['usergroupid'])?$_POST['usergroupid']:''),$_POST['useraccessid'],$_POST['groupaccessid']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}	
	}
	private function ModifyDataUserfav($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertuserfav(:vuseraccessid,:vmenuaccessid,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateuserfav(:vid,:vuseraccessid,:vmenuaccessid,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vuseraccessid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vmenuaccessid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionSaveuserfav() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDataUserfav($connection,array((isset($_POST['userfavid'])?$_POST['userfavid']:''),
			$_POST['useraccessid'],$_POST['menuaccessid']));
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
				$sql = 'call Purgeuseraccess(:vid,:vdatauser)';
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
			GetMessage(true,'chooseone');
		}
	}
	public function actionPurgeUserGroup() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeusergroup(:vid,:vdatauser)';
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
			GetMessage(true,'chooseone');
		}
	}
	public function actionPurgeUserfav() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeuserfav(:vid,:vdatauser)';
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
			GetMessage(true,'chooseone');
		}
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['titleid'] = GetCatalog('useraccessid');
		$this->dataprint['titleusername'] = GetCatalog('username');
		$this->dataprint['titlegroupname'] = GetCatalog('groupname');
		$this->dataprint['titlerealname'] = GetCatalog('realname');
		$this->dataprint['titlepassword'] = GetCatalog('password');
		$this->dataprint['titleemail'] = GetCatalog('email');
		$this->dataprint['titlephoneno'] = GetCatalog('phoneno');
		$this->dataprint['titlelanguagename'] = GetCatalog('languagename');
		$this->dataprint['titlethemename'] = GetCatalog('themename');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['namauser'] = GetSearchText(array('GET'),'username');
    $this->dataprint['realname'] = GetSearchText(array('GET'),'realname');
    $this->dataprint['email'] = GetSearchText(array('GET'),'email');
    $this->dataprint['phoneno'] = GetSearchText(array('GET'),'phoneno');
    $this->dataprint['languagename'] = GetSearchText(array('GET'),'languagename');
    $this->dataprint['themename'] = GetSearchText(array('GET'),'themename');
    $this->dataprint['groupname'] = GetSearchText(array('GET'),'groupname');
  }
}
