<?php
class CompanyController extends Controller {
	public $menuname = 'company';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexauth() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->searchauth();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexcombo() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->searchcombo();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$companyid = GetSearchText(array('POST','Q'),'companyid');
		$companyname = GetSearchText(array('POST','Q'),'companyname');
		$companycode = GetSearchText(array('POST','Q'),'companycode');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','companyid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;		
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = '
			from company t 
			left join city a on a.cityid = t.cityid 
			left join currency b on b.currencyid = t.currencyid';
		$where = "
			where (coalesce(companyid,'') like '".$companyid."') and (coalesce(companyname,'') like '".$companyname."') and (coalesce(companycode,'') like '".$companycode."')";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = '
			select t.companyid,t.companyname,t.companycode,t.address,t.cityid,a.cityname,t.zipcode,t.taxno,t.currencyid,b.currencyname,t.faxno,t.phoneno,t.webaddress,t.email,t.leftlogofile,t.rightlogofile,t.isholding,t.billto,t.bankacc1,t.bankacc2,t.bankacc3,t.recordstatus 
				'.$from.' '.$where;
    $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
				'companyid'=>$data['companyid'],
				'companyname'=>$data['companyname'],
				'companycode'=>$data['companycode'],
				'address'=>$data['address'],
				'cityid'=>$data['cityid'],
				'cityname'=>$data['cityname'],
				'zipcode'=>$data['zipcode'],
				'taxno'=>$data['taxno'],
				'currencyid'=>$data['currencyid'],
				'currencyname'=>$data['currencyname'],
				'faxno'=>$data['faxno'],
				'phoneno'=>$data['phoneno'],
				'webaddress'=>$data['webaddress'],
				'email'=>$data['email'],
				'leftlogofile'=>$data['leftlogofile'],
				'rightlogofile'=>$data['rightlogofile'],
				'isholding'=>$data['isholding'],
				'billto'=>$data['billto'],
				'bankacc1'=>$data['bankacc1'],
				'bankacc2'=>$data['bankacc2'],
				'bankacc3'=>$data['bankacc3'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchauth() {
		header("Content-Type: application/json");
		$companyid = GetSearchText(array('Q'),'companyid');
		$companyname = GetSearchText(array('Q'),'companyname');
		$companycode = GetSearchText(array('Q'),'companycode');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','companyid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = 'from company t';
		$where = "
			where (companyname like '".$companyname."') or (companycode like '".$companycode."')
				and t.recordstatus = 1 and companyid in (".getUserObjectValues('company').")";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.companyid,t.companycode,t.companyname '.$from.' '.$where;
    $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order)->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
				'companyid'=>$data['companyid'],
				'companycode'=>$data['companycode'],
				'companyname'=>$data['companyname'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchcombo() {
		header("Content-Type: application/json");
		$companyid = GetSearchText(array('Q'),'companyid');
		$companyname = GetSearchText(array('Q'),'companyname');
		$companycode = GetSearchText(array('Q'),'companycode');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','companyid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = 'from company t';
		$where = "
			where ((companyid like '".$companyid."') or (companyname like '".$companyname."') or (companycode like '".$companycode."'))
				and t.recordstatus = 1 and companyid in (".getUserObjectValues('company').")";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.companyid,t.companycode,t.companyname '.$from.' '.$where;
    $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order)->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
				'companyid'=>$data['companyid'],
				'companycode'=>$data['companycode'],
				'companyname'=>$data['companyname'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertcompany(:vcompanyname,:vcompanycode,:vaddress,:vcityid,:vzipcode,:vtaxno,:vcurrencyid,:vfaxno,:vphoneno,:vwebaddress,:vemail,:vleftlogofile,:vrightlogofile,:visholding,:vbillto,:vbankacc1,:vbankacc2,:vbankacc3,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatecompany(:vid,:vcompanyname,:vcompanycode,:vaddress,:vcityid,:vzipcode,:vtaxno,:vcurrencyid,:vfaxno,:vphoneno,:vwebaddress,:vemail,:vleftlogofile,:vrightlogofile,:visholding,:vbillto,:vbankacc1,:vbankacc2,:vbankacc3,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcompanycode',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vcompanyname',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vaddress',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vcityid',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vzipcode',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vtaxno',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vfaxno',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vphoneno',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vwebaddress',$arraydata[10],PDO::PARAM_STR);
		$command->bindvalue(':vemail',$arraydata[11],PDO::PARAM_STR);
		$command->bindvalue(':vleftlogofile',$arraydata[12],PDO::PARAM_STR);
		$command->bindvalue(':vrightlogofile',$arraydata[13],PDO::PARAM_STR);
		$command->bindvalue(':visholding',$arraydata[14],PDO::PARAM_STR);
		$command->bindvalue(':vbillto',$arraydata[15],PDO::PARAM_STR);
		$command->bindvalue(':vbankacc1',$arraydata[16],PDO::PARAM_STR);
		$command->bindvalue(':vbankacc2',$arraydata[17],PDO::PARAM_STR);
		$command->bindvalue(':vbankacc3',$arraydata[18],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[19],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-company"]["name"]);
		if (move_uploaded_file($_FILES["file-company"]["tmp_name"], $target_file)) {
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
					$companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$companyname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$address = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$citycode = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$cityid = Yii::app()->db->createCommand("select cityid from city where citycode = '".$citycode."'")->queryScalar();
					$zipcode = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$taxno = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$currencyname = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$currencyid = Yii::app()->db->createCommand("select currencyid from currency where currencyname = '".$currencyname."'")->queryScalar();
					$faxno = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$phoneno = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
					$webaddress = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
					$email = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
					$leftlogofile = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
					$rightlogofile = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
					$isholding = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
					$billto = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
					$bankacc1 = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
					$bankacc2 = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
					$bankacc3 = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(19, $row)->getValue();
					$this->ModifyData($connection,array($id,$companycode,$companyname,$address,$cityid,$zipcode,$taxno,$currencyid,$faxno,$phoneno,$webaddress,$email,
						$leftlogofile,$rightlogofile,$isholding,$billto,$bankacc1,$bankacc2,$bankacc3,$recordstatus));
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
	public function actionUploadLeftLogo() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/images/' . basename($_FILES["file-leftlogo"]["name"]);
		if (move_uploaded_file($_FILES["file-leftlogo"]["tmp_name"], $target_file)) {
			//$this->redirect(Yii::app()->request->urlReferrer);
		}
	}
	public function actionUploadRightLogo() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/images/' . basename($_FILES["file-rightlogo"]["name"]);
		if (move_uploaded_file($_FILES["file-rightlogo"]["tmp_name"], $target_file)) {
			//$this->redirect(Yii::app()->request->urlReferrer);
		}
	}
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['companyid'])?$_POST['companyid']:''),$_POST['companycode'],$_POST['companyname'],
				$_POST['address'],$_POST['cityid'],$_POST['zipcode'],$_POST['taxno'],$_POST['currencyid'],$_POST['faxno'],$_POST['phoneno'],
				$_POST['webaddress'],$_POST['email'],$_POST['leftlogofile'],$_POST['rightlogofile'],$_POST['isholding'],$_POST['billto'],
				$_POST['bankacc1'],$_POST['bankacc2'],$_POST['bankacc3'],$_POST['recordstatus']
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
		header("Content-Type: application/json");
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgecompany(:vid,:vcreatedby)';
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
		$this->dataprint['titleid'] = GetCatalog('companyid');
		$this->dataprint['titlecompanyname'] = GetCatalog('companyname');
		$this->dataprint['titlecompanycode'] = GetCatalog('companycode');
		$this->dataprint['titleaddress'] = GetCatalog('address');
		$this->dataprint['titlecityname'] = GetCatalog('cityname');
		$this->dataprint['titlezipcode'] = GetCatalog('zipcode');
		$this->dataprint['titletaxno'] = GetCatalog('taxno');
		$this->dataprint['titlefaxno'] = GetCatalog('faxno');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['companyname'] = GetSearchText(array('GET'),'companyname');
    $this->dataprint['companycode'] = GetSearchText(array('GET'),'companycode');
  }
}
