<?php
class EmployeeController extends Controller {
	public $menuname = 'employee';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexdetail() {
		if(isset($_GET['grid']))
			echo $this->actionSearchDetail();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexfamily() {
		if(isset($_GET['grid']))
			echo $this->actionSearchFamily();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexeducation() {
		if(isset($_GET['grid']))
			echo $this->actionSearchEducation();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexinformal() {
		if(isset($_GET['grid']))
			echo $this->actionSearchInformal();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexwo() {
		if(isset($_GET['grid']))
			echo $this->actionSearchWo();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexorgstruc() {
		if(isset($_GET['grid']))
			echo $this->actionSearchorgstruc();
		else
			$this->renderPartial('index',array());
	}
	public function actiongetdata() {
		$id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'addressbookid' => $id,
			'employeeid' => $id
		));
	}
	public function ModifData($connection,$arraydata) {
		$sql = 'call Modifemployee(:vid,:vfullname,:vaddressbookid,:voldnik,:vpositionid,:vemployeetypeid,:vsexid,:vbirthcityid,
			:vbirthdate,:vreligionid,:vmaritalstatusid,:vreferenceby,:vjoindate,:vemployeestatusid,:vistrial,:vbarcode,:vphoto,
			:vresigndate,:vlevelorgid,:vemail,:vphoneno,:valternateemail,:vhpno,:vtaxno,:vdplkno,:vhpno2,:vaccountno,:vrecordstatus,:vcreatedby)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vfullname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':voldnik',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vpositionid',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vemployeetypeid',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vsexid',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vbirthcityid',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vbirthdate',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vreligionid',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vmaritalstatusid',$arraydata[10],PDO::PARAM_STR);
		$command->bindvalue(':vreferenceby',$arraydata[11],PDO::PARAM_STR);
		$command->bindvalue(':vjoindate',$arraydata[12],PDO::PARAM_STR);
		$command->bindvalue(':vemployeestatusid',$arraydata[13],PDO::PARAM_STR);
		$command->bindvalue(':vistrial',$arraydata[14],PDO::PARAM_STR);
		$command->bindvalue(':vbarcode',$arraydata[15],PDO::PARAM_STR);
		$command->bindvalue(':vphoto',$arraydata[16],PDO::PARAM_STR);
		$command->bindvalue(':vresigndate',$arraydata[17],PDO::PARAM_STR);
		$command->bindvalue(':vlevelorgid',$arraydata[18],PDO::PARAM_STR);
		$command->bindvalue(':vemail',$arraydata[19],PDO::PARAM_STR);
		$command->bindvalue(':vphoneno',$arraydata[20],PDO::PARAM_STR);
		$command->bindvalue(':valternateemail',$arraydata[21],PDO::PARAM_STR);
		$command->bindvalue(':vhpno',$arraydata[22],PDO::PARAM_STR);
		$command->bindvalue(':vtaxno',$arraydata[23],PDO::PARAM_STR);
		$command->bindvalue(':vdplkno',$arraydata[24],PDO::PARAM_STR);
		$command->bindvalue(':vhpno2',$arraydata[25],PDO::PARAM_STR);
		$command->bindvalue(':vaccountno',$arraydata[26],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[27],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();		
	}
	public function actionSave() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
			$this->ModifData($connection,array(
				$_POST['employee-employeeid'],
				$_POST['employee-fullname'],
				$_POST['employee-addressbookid'],
				$_POST['employee-oldnik'],
				$_POST['employee-positionid'],
				$_POST['employee-employeetypeid'],
				$_POST['employee-sexid'],
				$_POST['employee-birthcityid'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['employee-birthdate'])),
				$_POST['employee-religionid'],
				$_POST['employee-maritalstatusid'],
				$_POST['employee-referenceby'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['employee-joindate'])),
				$_POST['employee-employeestatusid'],
				isset($_POST['employee-istrial'])?($_POST['employee-istrial']=="on")?1:0:0,
				$_POST['employee-barcode'],
				$_POST['employee-photo'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['employee-resigndate'])),
				$_POST['employee-levelorgid'],
				$_POST['employee-email'],
				$_POST['employee-phoneno'],
				$_POST['employee-alternateemail'],
				$_POST['employee-hpno'],
				$_POST['employee-taxno'],
				$_POST['employee-dplkno'],
				$_POST['employee-hpno'],
				$_POST['employee-accountno'],
				$_POST['employee-recordstatus']
			));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	public function actionIndexcompany() {
		header('Content-Type: application/json');
		$employeeid = isset ($_POST['employeeid']) ? $_POST['employeeid'] : '';
		$fullname = isset ($_POST['fullname']) ? $_POST['fullname'] : '';
		$employeeid = isset ($_GET['q']) ? $_GET['q'] : $employeeid;
		$fullname = isset ($_GET['q']) ? $_GET['q'] : $fullname;
		$companyid = isset($_REQUEST['companyid']) ? $_REQUEST['companyid'] : '';
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'a.employeeid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;		
		$result = array();
		$row = array();
		$sqlcount = 'select distinct count(1) as total ';
		$from = 'from employee a
						 left join employeeorgstruc b on a.employeeid = b.employeeid 
						 left join orgstructure c on c.orgstructureid = b.orgstructureid ';
		$where = 'where c.companyid = '.$companyid.' and a.fullname like "%'.$fullname.'%"';
		$sqldata = 'select distinct a.fullname, a.employeeid ';
		$cmd = Yii::app()->db->createCommand($sqlcount.$from.$where)->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand($sqldata.$from.$where.' order by '.$sort.' desc'.' limit '.$offset.','.$rows)->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'employeeid'=>$data['employeeid'],
				'fullname'=>$data['fullname']
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		echo CJSON::encode($result);
	}
	public function actionSavedetail() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
			if (isset($_POST['isNewRecord'])) {
				$sql = 'call Insertemployeeidentity(:vemployeeid,:videntitytypeid,:videntityname,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else {
				$sql = 'call Updateemployeeidentity(:vid,:vemployeeid,:videntitytypeid,:videntityname,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['employeeidentityid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['employeeidentityid']);
			}
			$command->bindvalue(':vemployeeid',$_POST['employeeid'],PDO::PARAM_STR);
			$command->bindvalue(':videntitytypeid',$_POST['identitytypeid'],PDO::PARAM_STR);
			$command->bindvalue(':videntityname',$_POST['identityname'],PDO::PARAM_STR);
			$command->bindvalue(':vrecordstatus',$_POST['recordstatus'],PDO::PARAM_STR);
			$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
			$command->execute();
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	public function actionSavefamily() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			if (isset($_POST['isNewRecord'])) {
				$sql = 'call Insertemployeefamily(:vemployeeid,:vfamilyrelationid,:vfamilyname,:vsexid,:vcityid,:vbirthdate,:veducationid,:voccupationid,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call Updateemployeefamily(:vid,:vemployeeid,:vfamilyrelationid,:vfamilyrelationid,:vfamilyname,:vsexid,:vcityid,:vbirthdate,:veducationid,:voccupationid,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['employeefamilyid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['employeefamilyid']);
			}
			$command->bindvalue(':vemployeeid',$_POST['employeeid'],PDO::PARAM_STR);
			$command->bindvalue(':vfamilyrelationid',$_POST['familyrelationid'],PDO::PARAM_STR);
			$command->bindvalue(':vfamilyname',$_POST['familyname'],PDO::PARAM_STR);
			$command->bindvalue(':vsexid',$_POST['sexid'],PDO::PARAM_STR);
			$command->bindvalue(':vcityid',$_POST['cityid'],PDO::PARAM_STR);
			$command->bindvalue(':vbirthdate',date(Yii::app()->params['datetodb'], strtotime($_POST['birthdate'])),PDO::PARAM_STR);
			$command->bindvalue(':veducationid',$_POST['educationid'],PDO::PARAM_STR);
			$command->bindvalue(':voccupationid',$_POST['occupationid'],PDO::PARAM_STR);
			$command->bindvalue(':vrecordstatus',$_POST['recordstatus'],PDO::PARAM_STR);
			$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
			$command->execute();
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	public function actionSaveeducation() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			if (isset($_POST['isNewRecord'])) {
				$sql = 'call Insertemployeeeducation(:vemployeeid,:veducationid,:vschoolname,:vcityid,:vyeargraduate,:visdiploma,:vschooldegree,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else {
				$sql = 'call Updateemployeeeducation(:vid,:vemployeeid,:veducationid,:vschoolname,:vcityid,:vyeargraduate,:visdiploma,:vschooldegree,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['employeeeducationid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['employeeeducationid']);
			}
			$command->bindvalue(':vemployeeid',$_POST['employeeid'],PDO::PARAM_STR);
			$command->bindvalue(':veducationid',$_POST['educationid'],PDO::PARAM_STR);
			$command->bindvalue(':vschoolname',$_POST['schoolname'],PDO::PARAM_STR);
			$command->bindvalue(':vcityid',$_POST['cityid'],PDO::PARAM_STR);
			$command->bindvalue(':vyeargraduate',date(Yii::app()->params['datetodb'], strtotime($_POST['yeargraduate'])),PDO::PARAM_STR);
			$command->bindvalue(':visdiploma',$_POST['isdiploma'],PDO::PARAM_STR);
			$command->bindvalue(':vschooldegree',$_POST['schooldegree'],PDO::PARAM_STR);
			$command->bindvalue(':vrecordstatus',$_POST['recordstatus'],PDO::PARAM_STR);
			$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
			$command->execute();
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	public function actionSaveinformal() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			if (isset($_POST['isNewRecord'])) {
				$sql = 'call Insertemployeeinformal(:vemployeeid,:vinformalname,:vorganizer,:vperiod,:visdiploma,:vsponsoredby,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else {
				$sql = 'call Updateemployeeinformal(:vid,:vemployeeid,:vinformalname,:vorganizer,:vperiod,:visdiploma,:vsponsoredby,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['employeeinformalid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['employeeinformalid']);
			}
			$command->bindvalue(':vemployeeid',$_POST['employeeid'],PDO::PARAM_STR);
			$command->bindvalue(':vinformalname',$_POST['informalname'],PDO::PARAM_STR);
			$command->bindvalue(':vorganizer',$_POST['organizer'],PDO::PARAM_STR);
			$command->bindvalue(':vperiod',$_POST['period'],PDO::PARAM_STR);
			$command->bindvalue(':visdiploma',$_POST['isdiploma'],PDO::PARAM_STR);
			$command->bindvalue(':vsponsoredby',$_POST['sponsoredby'],PDO::PARAM_STR);
			$command->bindvalue(':vrecordstatus',$_POST['recordstatus'],PDO::PARAM_STR);
			$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
			$command->execute();
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	public function actionSavewo() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			if (isset($_POST['isNewRecord'])) {
				$sql = 'call Insertemployeewo(:vemployeeid,:vinformalname,:vorganizer,:vperiod,:visdiploma,:vsponsoredby,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else {
				$sql = 'call Updateemployeewo(:vid,:vemployeeid,:vinformalname,:vorganizer,:vperiod,:visdiploma,:vsponsoredby,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['employeewoid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['employeewoid']);
			}
			$command->bindvalue(':vemployeeid',$_POST['employeeid'],PDO::PARAM_STR);
			$command->bindvalue(':vinformalname',$_POST['informalname'],PDO::PARAM_STR);
			$command->bindvalue(':vorganizer',$_POST['organizer'],PDO::PARAM_STR);
			$command->bindvalue(':vperiod',$_POST['period'],PDO::PARAM_STR);
			$command->bindvalue(':visdiploma',$_POST['isdiploma'],PDO::PARAM_STR);
			$command->bindvalue(':vsponsoredby',$_POST['sponsoredby'],PDO::PARAM_STR);
			$command->bindvalue(':vrecordstatus',$_POST['recordstatus'],PDO::PARAM_STR);
			$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
			$command->execute();
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	public function actionSaveorgstruc() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			if (isset($_POST['isNewRecord'])) {
				$sql = 'call Insertemployeeorgstruc(:vemployeeid,:vorgstructureid,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else {
				$sql = 'call Updateemployeeorgstruc(:vid,:vemployeeid,:vorgstructureid,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['employeeorgstrucid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['employeeorgstrucid']);
			}
			$command->bindvalue(':vemployeeid',$_POST['employeeid'],PDO::PARAM_STR);
			$command->bindvalue(':vorgstructureid',$_POST['orgstructureid'],PDO::PARAM_STR);
			$command->bindvalue(':vrecordstatus',$_POST['recordstatus'],PDO::PARAM_STR);
			$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
			$command->execute();
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
				$sql = 'call Purgeemployee(:vid,:vcreatedby)';
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
			getmessage(true,getcatalog('chooseone'));
		}
	}
	public function actionPurgedetail() {
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeemployeeidentity(:vid,:vcreatedby)';
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
			getmessage(true,getcatalog('chooseone'));
		}
	}
	public function actionPurgefamily() {
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeemployeefamily(:vid,:vcreatedby)';
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
			getmessage(true,getcatalog('chooseone'));
		}
	}
	public function actionPurgeeducation() {
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeemployeeeducation(:vid,:vcreatedby)';
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
			getmessage(true,getcatalog('chooseone'));
		}
	}
	public function actionPurgeinformal() {
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeemployeeinformal(:vid,:vcreatedby)';
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
			getmessage(true,getcatalog('chooseone'));
		}
	}
	public function actionPurgewo() {
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeemployeewo(:vid,:vcreatedby)';
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
			getmessage(true,getcatalog('chooseone'));
		}
	}
	public function actionPurgeorgstruc() {
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeemployeeorgstruc(:vid,:vcreatedby)';
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
			getmessage(true,getcatalog('chooseone'));
		}
	}
	public function search() {
		header('Content-Type: application/json');
		// search 
		$employeeid = isset ($_POST['employeeid']) ? $_POST['employeeid'] : '';
		$fullname = isset ($_POST['fullname']) ? $_POST['fullname'] : '';
		$oldnik = isset ($_POST['oldnik']) ? $_POST['oldnik'] : '';
		$positionname = isset ($_POST['positionname']) ? $_POST['positionname'] : '';
		$employeetypename = isset ($_POST['employeetypename']) ? $_POST['employeetypename'] : '';
		$sexname = isset ($_POST['sexname']) ? $_POST['sexname'] : '';
		$religionname = isset ($_POST['religionname']) ? $_POST['religionname'] : '';
		$maritalstatusname = isset ($_POST['maritalstatusname']) ? $_POST['maritalstatusname'] : '';
		$levelorgname = isset ($_POST['levelorgname']) ? $_POST['levelorgname'] : '';
		$employeeid = isset ($_GET['q']) ? $_GET['q'] : $employeeid;
		$fullname = isset ($_GET['q']) ? $_GET['q'] : $fullname;
		$oldnik = isset ($_GET['q']) ? $_GET['q'] : $oldnik;
		$positionname = isset ($_GET['q']) ? $_GET['q'] : $positionname;
		$employeetypename = isset ($_GET['q']) ? $_GET['q'] : $employeetypename;
		$sexname = isset ($_GET['q']) ? $_GET['q'] : $sexname;
		$religionname = isset ($_GET['q']) ? $_GET['q'] : $religionname;
		$maritalstatusname = isset ($_GET['q']) ? $_GET['q'] : $maritalstatusname;
		$levelorgname = isset ($_GET['q']) ? $_GET['q'] : $levelorgname;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.employeeid';
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
			->from('employee t')
			->leftjoin('position q','q.positionid=t.positionid')
			->leftjoin('employeetype r','r.employeetypeid=t.employeetypeid')
			->leftjoin('sex s','s.sexid=t.sexid')
			->leftjoin('city u','u.cityid=t.birthcityid')
			->leftjoin('religion v','v.religionid=t.religionid')
			->leftjoin('maritalstatus w','w.maritalstatusid=t.maritalstatusid')
			->leftjoin('employeestatus x','x.employeestatusid=t.employeestatusid')
			->leftjoin('levelorg y','y.levelorgid=t.levelorgid')
			->where("(coalesce(fullname,'') like :fullname) and 
							(coalesce(oldnik,'') like :oldnik) and
							(coalesce(q.positionname,'') like :positionname) and
							(coalesce(r.employeetypename,'') like :employeetypename) and
							(coalesce(s.sexname,'') like :sexname) and
							(coalesce(v.religionname,'') like :religionname) and
							(coalesce(w.maritalstatusname,'') like :maritalstatusname) and
							(coalesce(y.levelorgname,'') like :levelorgname)",
											array(':fullname'=>'%'.$fullname.'%',
													':oldnik'=>'%'.$oldnik.'%',
													':positionname'=>'%'.$positionname.'%',
													':employeetypename'=>'%'.$employeetypename.'%',
													':sexname'=>'%'.$sexname.'%',
													':religionname'=>'%'.$religionname.'%',
													':maritalstatusname'=>'%'.$maritalstatusname.'%',
													':levelorgname'=>'%'.$levelorgname.'%',
													))
			->queryScalar();
		} else {
			$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('employee t')
			->leftjoin('position q','q.positionid=t.positionid')
			->leftjoin('employeetype r','r.employeetypeid=t.employeetypeid')
			->leftjoin('sex s','s.sexid=t.sexid')
			->leftjoin('city u','u.cityid=t.birthcityid')
			->leftjoin('religion v','v.religionid=t.religionid')
			->leftjoin('maritalstatus w','w.maritalstatusid=t.maritalstatusid')
			->leftjoin('employeestatus x','x.employeestatusid=t.employeestatusid')
			->leftjoin('levelorg y','y.levelorgid=t.levelorgid')
			->where('(fullname like :fullname) or 
							(oldnik like :oldnik) or
							(q.positionname like :positionname) or
							(r.employeetypename like :employeetypename) or
							(s.sexname like :sexname) or
							(v.religionname like :religionname) or
							(w.maritalstatusname like :maritalstatusname) or
							(y.levelorgname like :levelorgname)',
											array(':fullname'=>'%'.$fullname.'%',
													':oldnik'=>'%'.$oldnik.'%',
													':positionname'=>'%'.$positionname.'%',
													':employeetypename'=>'%'.$employeetypename.'%',
													':sexname'=>'%'.$sexname.'%',
													':religionname'=>'%'.$religionname.'%',
													':maritalstatusname'=>'%'.$maritalstatusname.'%',
													':levelorgname'=>'%'.$levelorgname.'%',
													))
			->queryScalar();
		}
	
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,q.positionname,r.employeetypename,s.sexname,u.cityname,v.religionname,w.maritalstatusname,x.employeestatusname,y.levelorgname')	
			->from('employee t')
			->leftjoin('position q','q.positionid=t.positionid')
			->leftjoin('employeetype r','r.employeetypeid=t.employeetypeid')
			->leftjoin('sex s','s.sexid=t.sexid')
			->leftjoin('city u','u.cityid=t.birthcityid')
			->leftjoin('religion v','v.religionid=t.religionid')
			->leftjoin('maritalstatus w','w.maritalstatusid=t.maritalstatusid')
			->leftjoin('employeestatus x','x.employeestatusid=t.employeestatusid')
			->leftjoin('levelorg y','y.levelorgid=t.levelorgid')
			->where("(coalesce(fullname,'') like :fullname) and 
							(coalesce(oldnik,'') like :oldnik) and
							(coalesce(q.positionname,'') like :positionname) and
							(coalesce(r.employeetypename,'') like :employeetypename) and
							(coalesce(s.sexname,'') like :sexname) and
							(coalesce(v.religionname,'') like :religionname) and
							(coalesce(w.maritalstatusname,'') like :maritalstatusname) and
							(coalesce(y.levelorgname,'') like :levelorgname)",
											array(':fullname'=>'%'.$fullname.'%',
													':oldnik'=>'%'.$oldnik.'%',
													':positionname'=>'%'.$positionname.'%',
													':employeetypename'=>'%'.$employeetypename.'%',
													':sexname'=>'%'.$sexname.'%',
													':religionname'=>'%'.$religionname.'%',
													':maritalstatusname'=>'%'.$maritalstatusname.'%',
													':levelorgname'=>'%'.$levelorgname.'%',
													))
							->offset($offset)
							->limit($rows)
							->order($sort.' '.$order)
							->queryAll();
		} else {
			$cmd = Yii::app()->db->createCommand()
			->select('t.*,q.positionname,r.employeetypename,s.sexname,u.cityname,v.religionname,w.maritalstatusname,x.employeestatusname,y.levelorgname')	
			->from('employee t')
			->leftjoin('position q','q.positionid=t.positionid')
			->leftjoin('employeetype r','r.employeetypeid=t.employeetypeid')
			->leftjoin('sex s','s.sexid=t.sexid')
			->leftjoin('city u','u.cityid=t.birthcityid')
			->leftjoin('religion v','v.religionid=t.religionid')
			->leftjoin('maritalstatus w','w.maritalstatusid=t.maritalstatusid')
			->leftjoin('employeestatus x','x.employeestatusid=t.employeestatusid')
			->leftjoin('levelorg y','y.levelorgid=t.levelorgid')
			->where('(fullname like :fullname) or 
							(oldnik like :oldnik) or
							(q.positionname like :positionname) or
							(r.employeetypename like :employeetypename) or
							(s.sexname like :sexname) or
							(v.religionname like :religionname) or
							(w.maritalstatusname like :maritalstatusname) or
							(y.levelorgname like :levelorgname)',
											array(':fullname'=>'%'.$fullname.'%',
													':oldnik'=>'%'.$oldnik.'%',
													':positionname'=>'%'.$positionname.'%',
													':employeetypename'=>'%'.$employeetypename.'%',
													':sexname'=>'%'.$sexname.'%',
													':religionname'=>'%'.$religionname.'%',
													':maritalstatusname'=>'%'.$maritalstatusname.'%',
													':levelorgname'=>'%'.$levelorgname.'%',
													))
							->order($sort.' '.$order)
							->queryAll();
		}
		
		foreach($cmd as $data) {	
			$row[] = array(
				'employeeid'=>$data['employeeid'],
				'addressbookid'=>$data['addressbookid'],
				'fullname'=>$data['fullname'],
				'oldnik'=>$data['oldnik'],
				'positionid'=>$data['positionid'],
				'positionname'=>$data['positionname'],
				'employeetypeid'=>$data['employeetypeid'],
				'employeetypename'=>$data['employeetypename'],
				'sexid'=>$data['sexid'],
				'sexname'=>$data['sexname'],
				'birthcityid'=>$data['birthcityid'],
				'birthcityname'=>$data['cityname'],
				'birthdate'=>date(Yii::app()->params['dateviewfromdb'], strtotime($data['birthdate'])),
				'religionid'=>$data['religionid'],
				'religionname'=>$data['religionname'],
				'maritalstatusid'=>$data['maritalstatusid'],
				'maritalstatusname'=>$data['maritalstatusname'],
				'referenceby'=>$data['referenceby'],
				'joindate'=>date(Yii::app()->params['dateviewfromdb'], strtotime($data['joindate'])),
				'employeestatusid'=>$data['employeestatusid'],
				'employeestatusname'=>$data['employeestatusname'],
				'istrial'=>$data['istrial'],
				'barcode'=>$data['barcode'],
				'photo'=>$data['photo'],
				'resigndate'=>date(Yii::app()->params['dateviewfromdb'], strtotime($data['resigndate'])),
				'levelorgid'=>$data['levelorgid'],
				'levelorgname'=>$data['levelorgname'],
				'email'=>$data['email'],
				'phoneno'=>$data['phoneno'],
				'alternateemail'=>$data['alternateemail'],
				'hpno'=>$data['hpno'],
				'taxno'=>$data['taxno'],
				'dplkno'=>$data['dplkno'],
				'hpno2'=>$data['hpno2'],
				'accountno'=>$data['accountno'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
  public function actionSearchDetail() {
		header('Content-Type: application/json');
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
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'employeeidentityid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
		$offset = ($page-1) * $rows;
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort,'t.') > 0)?$sort:'t.'.$sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('employeeidentity t')
			->leftjoin('identitytype a','a.identitytypeid = t.identitytypeid')
			->where('employeeid = :employeeid',
			array(':employeeid'=>$id))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.identitytypename')
			->from('employeeidentity t')
			->leftjoin('identitytype a','a.identitytypeid = t.identitytypeid')
			->where('employeeid = :employeeid',
			array(':employeeid'=>$id))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
			'employeeidentityid'=>$data['employeeidentityid'],
			'employeeid'=>$data['employeeid'],
			'identitytypeid'=>$data['identitytypeid'],
			'identitytypename'=>$data['identitytypename'],
			'identityname'=>$data['identityname'],
			'recordstatus'=>$data['recordstatus']
			);
		}
		$result=array_merge($result,array('rows'=>$row));;
		echo CJSON::encode($result);
	}
  public function actionSearchFamily() {
		header('Content-Type: application/json');
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
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'employeefamilyid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
		$offset = ($page-1) * $rows;
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort,'t.') > 0)?$sort:'t.'.$sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('employeefamily t')
			->leftjoin('familyrelation a','a.familyrelationid = t.familyrelationid')
			->leftjoin('sex b','b.sexid = t.sexid')
			->leftjoin('city c','c.cityid = t.cityid')
			->leftjoin('education d','d.educationid = t.educationid')
			->leftjoin('occupation e','e.occupationid = t.occupationid')
			->where('employeeid = :employeeid',
			array(':employeeid'=>$id))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.familyrelationname,b.sexname,c.cityname,d.educationname,e.occupationname')
			->from('employeefamily t')
			->leftjoin('familyrelation a','a.familyrelationid = t.familyrelationid')
			->leftjoin('sex b','b.sexid = t.sexid')
			->leftjoin('city c','c.cityid = t.cityid')
			->leftjoin('education d','d.educationid = t.educationid')
			->leftjoin('occupation e','e.occupationid = t.occupationid')
			->where('employeeid = :employeeid',
			array(':employeeid'=>$id))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
			'employeefamilyid'=>$data['employeefamilyid'],
			'employeeid'=>$data['employeeid'],
			'familyrelationid'=>$data['familyrelationid'],
			'familyrelationname'=>$data['familyrelationname'],
			'familyname'=>$data['familyname'],
			'sexid'=>$data['sexid'],
			'sexname'=>$data['sexname'],
			'cityid'=>$data['cityid'],
			'cityname'=>$data['cityname'],
			'birthdate'=>$data['birthdate'],
			'educationid'=>$data['educationid'],
			'educationname'=>$data['educationname'],
			'occupationid'=>$data['occupationid'],
			'occupationname'=>$data['occupationname'],
			'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));;
		echo CJSON::encode($result);
	}
	public function actionSearchEducation() {
		header('Content-Type: application/json');
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
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'employeeeducationid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
		$offset = ($page-1) * $rows;
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort,'t.') > 0)?$sort:'t.'.$sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('employeeeducation t')
			->leftjoin('education a','a.educationid = t.educationid')
			->leftjoin('city b','b.cityid = t.cityid')
			->where('employeeid = :employeeid',
			array(':employeeid'=>$id))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select()
			->from('employeeeducation t')
			->leftjoin('education a','a.educationid = t.educationid')
			->leftjoin('city b','b.cityid = t.cityid')
			->where('employeeid = :employeeid',
			array(':employeeid'=>$id))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
				'employeeeducationid'=>$data['employeeeducationid'],
				'employeeid'=>$data['employeeid'],
				'educationid'=>$data['educationid'],
				'educationname'=>$data['educationname'],
				'schoolname'=>$data['schoolname'],
				'cityid'=>$data['cityid'],
				'cityname'=>$data['cityname'],
				'yeargraduate'=>$data['yeargraduate'],
				'isdiploma'=>$data['isdiploma'],
				'schooldegree'=>$data['schooldegree'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));;
		echo CJSON::encode($result);
	}
	public function actionSearchInformal() {
		header('Content-Type: application/json');
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
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'employeeinformalid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
		$offset = ($page-1) * $rows;
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort,'t.') > 0)?$sort:'t.'.$sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('employeeinformal t')
			->where('employeeid = :employeeid',
			array(':employeeid'=>$id))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*')
			->from('employeeinformal t')
			->where('employeeid = :employeeid',
			array(':employeeid'=>$id))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();		
		foreach($cmd as $data)
		{	
			$row[] = array(
				'employeeinformalid'=>$data['employeeinformalid'],
				'employeeid'=>$data['employeeid'],
				'informalname'=>$data['informalname'],
				'organizer'=>$data['organizer'],
				'period'=>$data['period'],
				'isdiploma'=>$data['isdiploma'],
				'sponsoredby'=>$data['sponsoredby'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));;
		echo CJSON::encode($result);
	}
	public function actionSearchWo() {
		header('Content-Type: application/json');
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
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'employeewoid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
		$offset = ($page-1) * $rows;
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort,'t.') > 0)?$sort:'t.'.$sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('employeewo t')
			->where('employeeid = :employeeid',
			array(':employeeid'=>$id))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*')
			->from('employeewo t')
			->where('employeeid = :employeeid',
			array(':employeeid'=>$id))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
				'employeewoid'=>$data['employeewoid'],
				'employeeid'=>$data['employeeid'],
				'informalname'=>$data['informalname'],
				'organizer'=>$data['organizer'],
				'period'=>$data['period'],
				'isdiploma'=>$data['isdiploma'],
				'sponsoredby'=>$data['sponsoredby'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));;
		echo CJSON::encode($result);
	}
	public function actionSearchOrgstruc() {
		header('Content-Type: application/json');
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
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'employeeorgstrucid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
		$offset = ($page-1) * $rows;
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort,'t.') > 0)?$sort:'t.'.$sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('employeeorgstruc t')
			->leftjoin('orgstructure a','a.orgstructureid = t.orgstructureid')
			->where('employeeid = :employeeid',
			array(':employeeid'=>$id))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.structurename')
			->from('employeeorgstruc t')
			->leftjoin('orgstructure a','a.orgstructureid = t.orgstructureid')
			->where('employeeid = :employeeid',
			array(':employeeid'=>$id))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();		
		foreach($cmd as $data)
		{	
			$row[] = array(
				'employeeorgstrucid'=>$data['employeeorgstrucid'],
				'employeeid'=>$data['employeeid'],
				'orgstructureid'=>$data['orgstructureid'],
				'structurename'=>$data['structurename'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));;
		echo CJSON::encode($result);
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
		$sql = "select fullname,b.positionname,d.levelorgname,a.employeeid
				from employee a
				left outer join position b on b.positionid = a.positionid
				left outer join levelorg d on d.levelorgid = a.levelorgid ";
		$employeeid = filter_input(INPUT_GET,'employeeid');
		$positionname = filter_input(INPUT_GET,'positionname');
		$levelorgname = filter_input(INPUT_GET,'levelorgname');
		$oldnik = filter_input(INPUT_GET,'oldnik');
		$sql .= " where coalesce(a.employeeid,'') like '%".$employeeid."%' 
			and coalesce(d.levelorgname,'') like '%".$levelorgname."%'
			and coalesce(b.positionname,'') like '%".$positionname."%'
			and coalesce(a.oldnik,'') like '%".$oldnik."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.employeeid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('employee');
		$this->pdf->AddPage('P');
		$this->pdf->AliasNBPages();
		foreach($dataReader as $row) {
			$this->pdf->text(10,$this->pdf->gety()+5,'Nama ');$this->pdf->text(48,$this->pdf->gety()+5,': '.$row['fullname']);
			$this->pdf->text(10,$this->pdf->gety()+10,'Posisi ');$this->pdf->text(48,$this->pdf->gety()+10,': '.$row['positionname']);
			$this->pdf->text(10,$this->pdf->gety()+15,'Level ');$this->pdf->text(48,$this->pdf->gety()+15,': '.$row['levelorgname']);
			$this->pdf->text(10,$this->pdf->gety()+20,'Struktur Organisasi');
			$sql1 = "select a.employeeorgstrucid,b.structurename,c.companyname 
					from employeeorgstruc a
					left join orgstructure b on b.orgstructureid = a.orgstructureid 
					left join company c on c.companyid = b.companyid 
					where a.employeeid = ".$row['employeeid'];
				$command1=$this->connection->createCommand($sql1);
				$dataReader1=$command1->queryAll();
				$this->pdf->sety($this->pdf->gety()+29);
				$this->pdf->setFont('Arial','B',8);
				$this->pdf->colalign = array('L','L','L');
				$this->pdf->setwidths(array(10,30,30,30,30,20,40));
				$this->pdf->colheader = array('No','Company','Struktur Organisasi');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('L','L','L');
				$i=0;
				foreach($dataReader1 as $row1)
				{
					$i+=1;
					$this->pdf->row(array($i,
					$row1['companyname'],
					$row1['structurename']
					));
				}
			$this->pdf->text(10,$this->pdf->gety()+20,'Family');
			$sql1 = "select b.familyname,c.sexname,d.cityname,b.birthdate,e.educationname,f.occupationname
					from employee a
					left join employeefamily b on b.employeeid = a.employeeid
					left join sex c on c.sexid = a.sexid
					left join city d on d.cityid = b.cityid
					left join education e on e.educationid = b.educationid
					left join occupation f on f.occupationid = b.occupationid
					where a.employeeid = ".$row['employeeid'];
				$command1=$this->connection->createCommand($sql1);
				$dataReader1=$command1->queryAll();
				$this->pdf->sety($this->pdf->gety()+29);
				$this->pdf->setFont('Arial','B',8);
				$this->pdf->colalign = array('L','L','L','L','L','L','L');
				$this->pdf->setwidths(array(10,30,30,30,30,20,40));
				$this->pdf->colheader = array('No','Nama Family','Jenis Kelamin','Tempat Tinggal','Tanggal Lahir','Pendidikan','Pekerjaan');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('L','L','L','L','L','C','L');
				$i=0;
				foreach($dataReader1 as $row1)
				{
					$i+=1;
					$this->pdf->row(array($i,
					$row1['familyname'],
					$row1['sexname'],
					$row1['cityname'],
					$row1['birthdate'],
					$row1['educationname'],
					$row1['occupationname'],
					));
				}
				$this->pdf->text(10,$this->pdf->gety()+27,'Pendidikan Karyawan');
				$sql2 = "select c.educationname, b.schoolname, d.cityname, b.yeargraduate, b.isdiploma, b.schooldegree
						from employee a
						left join employeeeducation b on b.employeeid = a.employeeid
						left join education c on c.educationid = b.educationid
						left join city d on d.cityid = b.cityid
						where a.employeeid = ".$row['employeeid'];
				$command2=$this->connection->createCommand($sql2);
				$dataReader2=$command2->queryAll();
				$this->pdf->sety($this->pdf->gety()+29);
				$this->pdf->setFont('Arial','B',8);
				$this->pdf->colalign = array('L','L','L','L','L','L','L');
				$this->pdf->setwidths(array(10,30,30,30,30,20,40));
				$this->pdf->colheader = array('No','Pendidikan','Nama Sekolah','Asal Sekolah ','Tanggal Lulus','Sertifikat','Gelar');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('L','L','L','L','L','C','L');
				$ii=0;
				foreach($dataReader2 as $row2) {
					$ii+=1;
					$this->pdf->row(array($ii,
					$row2['educationname'],
					$row2['schoolname'],
					$row2['cityname'],
					$row2['yeargraduate'],
					$row2['isdiploma'],
					$row2['schooldegree']));
				}
				$this->pdf->text(10,$this->pdf->gety()+27,'Kemampuan Bahasa Karyawan');
				$sql3 = "select c.languagename, d.languagevaluename
						from employee a
						left join employeeforeignlanguage b on b.employeeid = a.employeeid
						left join language c on c.languageid = b.languageid 
						left join languagevalue d on d.languagevalueid = b.languagevalueid
						where a.employeeid = ".$row['employeeid'];
				$command3=$this->connection->createCommand($sql3);
				$dataReader3=$command3->queryAll();
				$this->pdf->sety($this->pdf->gety()+29);
				$this->pdf->setFont('Arial','B',8);
				$this->pdf->colalign = array('L','L','L');
				$this->pdf->setwidths(array(10,40,30));
				$this->pdf->colheader = array('No','Bahasa','Nilai Bahasa');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('L','L','L');
				$i3=0;
				foreach($dataReader3 as $row3) {
					$i3+=1;
					$this->pdf->row(array($i3,
					$row3['languagename'],
					$row3['languagevaluename']));
				}
				$this->pdf->text(10,$this->pdf->gety()+27,'Identitas Karyawan');
				$sql4 = "select distinct c.identitytypename,b.identityname
						from employee a
						left join employeeidentity b on b.employeeid = a.employeeid
						left join identitytype c on c.identitytypeid = b.identitytypeid
						where a.employeeid = ".$row['employeeid'];
				$command4=$this->connection->createCommand($sql4);
				$dataReader4=$command4->queryAll();
				$this->pdf->sety($this->pdf->gety()+29);
				$this->pdf->setFont('Arial','B',8);
				$this->pdf->colalign = array('L','L','L');
				$this->pdf->setwidths(array(10,50,40));
				$this->pdf->colheader = array('No','Jenis Idetitas','Nomor Identitas');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('L','L','L');
				$i4=0;
				foreach($dataReader4 as $row4) {
					$i4+=1;
					$this->pdf->row(array($i4,
					$row4['identitytypename'],
					$row4['identityname']));
				}
				$this->pdf->text(10,$this->pdf->gety()+27,'Informal Karyawan');
				$sql5 = "select b.informalname, b.organizer, b.period, b.isdiploma, b.sponsoredby
						from employee a
						join employeeinformal b on b.employeeid = a.employeeid
						where a.employeeid = ".$row['employeeid'];
				$command5=$this->connection->createCommand($sql5);
				$dataReader5=$command5->queryAll();
				$this->pdf->sety($this->pdf->gety()+29);
				$this->pdf->setFont('Arial','B',8);
				$this->pdf->colalign = array('L','L','L','L','L','L');
				$this->pdf->setwidths(array(10,30,30,25,35,30));
				$this->pdf->colheader = array('No','Kursus','Pelaksana','Periode','Sertifikat','Biaya Sponsor');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('L','L','L','L','L','L');
				$i5=0;
				foreach($dataReader5 as $row5) {
					$i5+=1;
					$this->pdf->row(array($i5,
					$row5['informalname'],
					$row5['organizer'],
					$row5['period'],
					$row5['isdiploma'],
					$row5['sponsoredby']));
				}
				$this->pdf->text(10,$this->pdf->gety()+27,'Pengalaman Kerja Karyawan');
				$sql6 = "select b.informalname, b.organizer, b.period, b.isdiploma, b.sponsoredby
						from employee a
						join employeewo b on b.employeeid = a.employeeid
						where a.employeeid = ".$row['employeeid'];
				$command6=$this->connection->createCommand($sql6);
				$dataReader6=$command6->queryAll();
				$this->pdf->sety($this->pdf->gety()+29);
				$this->pdf->setFont('Arial','B',8);
				$this->pdf->colalign = array('L','L','L','L','L','L');
				$this->pdf->setwidths(array(10,30,30,25,35,30));
				$this->pdf->colheader = array('No','Kegiatan','Pelaksana','Periode','Sertifikat','Biaya Sponsor');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('L','L','L','L','L','L');
				$i6=0;
				foreach($dataReader6 as $row6) {
					$i6+=1;
					$this->pdf->row(array($i6,
					$row6['informalname'],
					$row6['organizer'],
					$row6['period'],
					$row6['isdiploma'],
					$row6['sponsoredby']));
				}
				$sql7 = "select *
					from employee a
					join reportin b on b.employeeid = a.employeeid
					where a.employeeid = ".$row['employeeid'].' order by year asc,month asc';
				$command7=$this->connection->createCommand($sql7);
				$dataReader7=$command7->queryAll();
				$this->pdf->isheader=false;
				$this->pdf->AddPage('L','Legal');
				$this->pdf->text(10,$this->pdf->gety()+0,'Absensi');
				$this->pdf->sety($this->pdf->gety()+2);
				$this->pdf->setFont('Arial','',7);
				$this->pdf->colalign = array('L','L','L','L','L','L','L','L','L','L',
											'L','L','L','L','L','L','L','L','L','L',
											'L','L','L','L','L','L','L','L','L','L',
											'L','L');
				$this->pdf->setwidths(array(9,9,9,9,9,9,9,9,9,9,
											9,9,9,9,9,9,9,9,10,10,
											10,10,10,10,10,10,10,10,10,10,
											10,10));
				$this->pdf->colheader = array('s1 / d1','s2 / d2','s3 / d3','s4 / d4','s5 / d5','s6 / d6','s7 / d7','s8 / d8','s9 / d9','s10 / d10',
											's11 / d11','s12 / d12','s13 / d13','s14 / d14',
											's15 / d15','s16 / d16','s17 / d17','s18 / d18','s19 / d19','s20 / d20','s21 / d21','s22 / d22','s23 / d23','s24 / d24',
											's25 / d25','s26 / d26','s27 / d27','s28 / d28','s29 / d29','s30 / d30','s31 / d31');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','L','L',
													'L','L','L','L','L','L','L','L','L','L',
													'L','L','L','L','L','L','L','L','L','L',
													'L','L');
				foreach($dataReader7 as $row7) {
					$this->pdf->text(10,$this->pdf->gety()+5,'Bulan '.$row7['month'].' Tahun '.$row7['year']);
					$this->pdf->row(array(
						$row7['s1'],$row7['s2'],$row7['s3'],$row7['s4'],$row7['s5'],$row7['s6'],$row7['s7'],$row7['s8'],$row7['s9'],$row7['s10'],
						$row7['s11'],$row7['s12'],$row7['s13'],$row7['s14'],$row7['s15'],$row7['s16'],$row7['s17'],$row7['s18'],$row7['s19'],$row7['s20'],
						$row7['s21'],$row7['s22'],$row7['s23'],$row7['s24'],$row7['s25'],$row7['s26'],$row7['s27'],$row7['s28'],$row7['s29'],$row7['s30'],$row7['s31'],
					));
					
					$this->pdf->row(array(
						$row7['d1'],$row7['d2'],$row7['d3'],$row7['d4'],$row7['d5'],$row7['d6'],$row7['d7'],$row7['d8'],$row7['d9'],$row7['d10'],
						$row7['d11'],$row7['d12'],$row7['d13'],$row7['d14'],$row7['d15'],$row7['d16'],$row7['d17'],$row7['d18'],$row7['d19'],$row7['d20'],
						$row7['d21'],$row7['d22'],$row7['d23'],$row7['d24'],$row7['d25'],$row7['d26'],$row7['d27'],$row7['d28'],$row7['d29'],$row7['d30'],$row7['d31'],
					));
				}
				$this->pdf->checkPageBreak(20);
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		parent::actionDownXls();
		$sql = "select fullname,addressbookid,oldnik,orgstructureid,positionid,employeetypeid,sexid,birthcityid,birthdate,religionid,maritalstatusid,referenceby,joindate,employeestatusid,istrial,barcode,photo,resigndate,levelorgid,email,phoneno,alternateemail,hpno,taxno,dplkno,hpno2,accountno
				from employee a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.employeeid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		 $excel=Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
		$i=1;
		$excel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0,1,getCatalog('fullname'))
                ->setCellValueByColumnAndRow(1,1,getCatalog('addressbookid'))
                ->setCellValueByColumnAndRow(2,1,getCatalog('oldnik'))
                ->setCellValueByColumnAndRow(4,1,getCatalog('orgstructureid'))
                ->setCellValueByColumnAndRow(5,1,getCatalog('positionid'))
                ->setCellValueByColumnAndRow(6,1,getCatalog('employeetypeid'))
                ->setCellValueByColumnAndRow(7,1,getCatalog('sexid'))
                ->setCellValueByColumnAndRow(8,1,getCatalog('birthcityid'))
                ->setCellValueByColumnAndRow(9,1,getCatalog('birthdate'))
                ->setCellValueByColumnAndRow(10,1,getCatalog('religionid'))
                ->setCellValueByColumnAndRow(11,1,getCatalog('maritalstatusid'))
                ->setCellValueByColumnAndRow(12,1,getCatalog('referenceby'))
                ->setCellValueByColumnAndRow(13,1,getCatalog('joindate'))
                ->setCellValueByColumnAndRow(14,1,getCatalog('employeestatusid'))
                ->setCellValueByColumnAndRow(15,1,getCatalog('istrial'))
                ->setCellValueByColumnAndRow(16,1,getCatalog('barcode'))
                ->setCellValueByColumnAndRow(17,1,getCatalog('photo'))
                ->setCellValueByColumnAndRow(18,1,getCatalog('resigndate'))
                ->setCellValueByColumnAndRow(19,1,getCatalog('levelorgid'))
                ->setCellValueByColumnAndRow(20,1,getCatalog('email'))
                ->setCellValueByColumnAndRow(21,1,getCatalog('phoneno'))
                ->setCellValueByColumnAndRow(22,1,getCatalog('alternateemail'))
                ->setCellValueByColumnAndRow(23,1,getCatalog('hpno'))
                ->setCellValueByColumnAndRow(24,1,getCatalog('taxno'))
                ->setCellValueByColumnAndRow(25,1,getCatalog('dplkno'))
                ->setCellValueByColumnAndRow(26,1,getCatalog('hpno2'))
                ->setCellValueByColumnAndRow(27,1,getCatalog('accountno'))
                ;		foreach($dataReader as $row1)
		{
			  $excel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['fullname'])
                ->setCellValueByColumnAndRow(1, $i+1, $row1['addressbookid'])
                ->setCellValueByColumnAndRow(2, $i+1, $row1['oldnik'])
                ->setCellValueByColumnAndRow(4, $i+1, $row1['orgstructureid'])
                ->setCellValueByColumnAndRow(5, $i+1, $row1['positionid'])
                ->setCellValueByColumnAndRow(6, $i+1, $row1['employeetypeid'])
                ->setCellValueByColumnAndRow(7, $i+1, $row1['sexid'])
                ->setCellValueByColumnAndRow(8, $i+1, $row1['birthcityid'])
                ->setCellValueByColumnAndRow(9, $i+1, $row1['birthdate'])
                ->setCellValueByColumnAndRow(10, $i+1, $row1['religionid'])
                ->setCellValueByColumnAndRow(11, $i+1, $row1['maritalstatusid'])
                ->setCellValueByColumnAndRow(12, $i+1, $row1['referenceby'])
                ->setCellValueByColumnAndRow(13, $i+1, $row1['joindate'])
                ->setCellValueByColumnAndRow(14, $i+1, $row1['employeestatusid'])
                ->setCellValueByColumnAndRow(15, $i+1, $row1['istrial'])
                ->setCellValueByColumnAndRow(16, $i+1, $row1['barcode'])
                ->setCellValueByColumnAndRow(17, $i+1, $row1['photo'])
                ->setCellValueByColumnAndRow(18, $i+1, $row1['resigndate'])
                ->setCellValueByColumnAndRow(19, $i+1, $row1['levelorgid'])
                ->setCellValueByColumnAndRow(20, $i+1, $row1['email'])
                ->setCellValueByColumnAndRow(21, $i+1, $row1['phoneno'])
                ->setCellValueByColumnAndRow(22, $i+1, $row1['alternateemail'])
                ->setCellValueByColumnAndRow(23, $i+1, $row1['hpno'])
                ->setCellValueByColumnAndRow(24, $i+1, $row1['taxno'])
                ->setCellValueByColumnAndRow(25, $i+1, $row1['dplkno'])
                ->setCellValueByColumnAndRow(26, $i+1, $row1['hpno2'])
                ->setCellValueByColumnAndRow(27, $i+1, $row1['accountno'])
                ;		$i+=1;
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="employee.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$objWriter->save('php://output');
		unset($excel);
	}
	

}
