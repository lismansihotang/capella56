<?php
class SupplierController extends Controller {
	public $menuname = 'supplier';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexaddress() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionSearchAddress();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexcontact() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionSearchcontact();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$addressbookid = GetSearchText(array('POST','Q'),'addressbookid');
		$fullname = GetSearchText(array('POST','Q'),'fullname');
		$bankname = GetSearchText(array('POST','Q'),'bankname');
		$accountowner = GetSearchText(array('POST','Q'),'accountowner');
		$page       = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows       = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort       = isset($_POST['sort']) ? strval($_POST['sort']) : 'addressbookid';
    $order      = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset     = ($page - 1) * $rows;
    $page       = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows       = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort       = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order      = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		$dependency = new CDbCacheDependency('SELECT MAX(updatedate) FROM addressbook');
		if (!isset($_GET['getdata'])) {
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')
				->from('addressbook t')
				->leftjoin('paymentmethod b','b.paymentmethodid = t.paymentmethodid')
				->where("((coalesce(fullname,'') like :fullname) 
				or (coalesce(addressbookid,'') like :addressbookid)
				or (coalesce(bankname,'') like :bankname)
				or (coalesce(accountowner,'') like :accountowner)
				) 
				and t.isvendor = 1 and t.recordstatus = 1",
						array(
						':fullname'=>$fullname,
						':addressbookid'=>$addressbookid,
						':bankname'=>$bankname,
						':accountowner'=>$accountowner
						))
				->queryScalar();
		}
		else if (isset($_GET['expedisi'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')
				->from('addressbook t')
				->leftjoin('paymentmethod b','b.paymentmethodid = t.paymentmethodid')
				->where("((coalesce(fullname,'') like :fullname) 
				or (coalesce(addressbookid,'') like :addressbookid)
				or (coalesce(bankname,'') like :bankname)
				or (coalesce(accountowner,'') like :accountowner)
				) 
				and t.isexpedisi = 1 and t.recordstatus = 1",
						array(
						':fullname'=>$fullname,
						':addressbookid'=>$addressbookid,
						':bankname'=>$bankname,
						':accountowner'=>$accountowner
						))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')
				->from('addressbook t')
				->leftjoin('paymentmethod b','b.paymentmethodid = t.paymentmethodid')
				->where("coalesce(addressbookid,'') like :addressbookid 
					and coalesce(fullname,'') like :fullname 
					and coalesce(bankname,'') like :bankname 
					and coalesce(accountowner,'') like :accountowner
					and isvendor = 1",
						array(
						':addressbookid'=>$addressbookid,
						':fullname'=>$fullname,
						':bankname'=>$bankname,
						':accountowner'=>$accountowner
						))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('t.*,b.paycode')			
				->from('addressbook t')
				->leftjoin('paymentmethod b','b.paymentmethodid = t.paymentmethodid')
				->where("((coalesce(fullname,'') like :fullname) 
				or (coalesce(addressbookid,'') like :addressbookid)
				or (coalesce(bankname,'') like :bankname)
				or (coalesce(accountowner,'') like :accountowner)
				) 
				and t.isvendor = 1 and t.recordstatus = 1",
						array(
						':fullname'=>$fullname,
						':addressbookid'=>$addressbookid,
						':bankname'=>$bankname,
						':accountowner'=>$accountowner
						))
				->order($sort.' '.$order)
				->queryAll();			
		}
		else if (isset($_GET['expedisi'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('t.*,b.paycode')			
				->from('addressbook t')
				->leftjoin('paymentmethod b','b.paymentmethodid = t.paymentmethodid')
				->where("((coalesce(fullname,'') like :fullname) 
				or (coalesce(addressbookid,'') like :addressbookid)
				or (coalesce(bankname,'') like :bankname)
				or (coalesce(accountowner,'') like :accountowner)
				) 
				and t.isexpedisi = 1 and t.recordstatus = 1",
						array(
						':fullname'=>$fullname,
						':addressbookid'=>$addressbookid,
						':bankname'=>$bankname,
						':accountowner'=>$accountowner
						))
				->order($sort.' '.$order)
				->queryAll();			
		}
		else 
		{
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('t.*,b.paycode')			
				->from('addressbook t')
				->leftjoin('paymentmethod b','b.paymentmethodid = t.paymentmethodid')
				->where("coalesce(addressbookid,'') like :addressbookid 
					and coalesce(fullname,'') like :fullname 
					and coalesce(bankname,'') like :bankname 
					and coalesce(accountowner,'') like :accountowner
					and isvendor = 1",
						array(
						':addressbookid'=>$addressbookid,
						':fullname'=>$fullname,
						':bankname'=>$bankname,
						':accountowner'=>$accountowner
						))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
			'addressbookid'=>$data['addressbookid'],
			'fullname'=>$data['fullname'],
			'taxno'=>$data['taxno'],
			'bankaccountno'=>$data['bankaccountno'],
			'bankname'=>$data['bankname'],
			'accountowner'=>$data['accountowner'],
			'paymentmethodid'=>$data['paymentmethodid'],
			'paycode'=>$data['paycode'],
			'recordstatus'=>$data['recordstatus'],
			);
			}
			$result = array_merge($result, array(
				'rows' => $row
			));
	} else {
		$addressbookid = GetSearchText(array('POST','Q','GET'),'addressbookid',0,'int');
			$cmd = Yii::app()->db->createCommand("
				select a.addressbookid,a.paymentmethodid, b.paycode
				from addressbook a
				left join paymentmethod b on b.paymentmethodid = a.paymentmethodid 
				where a.addressbookid = ".$addressbookid)->queryRow();
			$result = $cmd;
		}
    return CJSON::encode($result);
	}
	public function actionSearchAddress() {
		header('Content-Type: application/json');
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
		$page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','addressid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('address t')
				->join('addresstype b','b.addresstypeid = t.addresstypeid')
				->join('city c','c.cityid = t.cityid')
				->where('addressbookid = :abid',
						array(':abid'=>$id))
				->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
				->select('t.*,b.addresstypename,c.cityname')			
				->from('address t')
				->join('addresstype b','b.addresstypeid = t.addresstypeid')
				->join('city c','c.cityid = t.cityid')
				->where('addressbookid = :abid',
						array(':abid'=>$id))
				->order($sort.' '.$order)
				->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
			'addressid'=>$data['addressid'],
			'addressbookid'=>$data['addressbookid'],
			'addressname'=>$data['addressname'],
			'addresstypeid'=>$data['addresstypeid'],
			'addresstypename'=>$data['addresstypename'],
			'rt'=>$data['rt'],
			'rw'=>$data['rw'],
			'cityid'=>$data['cityid'],
			'cityname'=>$data['cityname'],
			'phoneno'=>$data['phoneno'],
			'faxno'=>$data['faxno'],
			'lat'=>$data['lat'],
			'lng'=>$data['lng']
			);
		}
		$result=array_merge($result,array('rows'=>$row));;
		echo CJSON::encode($result);
	}
	public function actionSearchcontact() {
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
		}
		$page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','addresscontactid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('addresscontact t')
				->leftjoin('contacttype b','b.contacttypeid = t.contacttypeid')
				->where('addressbookid = :abid',
						array(':abid'=>$id))
				->queryScalar();
		$result['total'] = $cmd;		
		$cmd = Yii::app()->db->createCommand()
				->select('t.*,b.contacttypename')			
				->from('addresscontact t')
				->leftjoin('contacttype b','b.contacttypeid = t.contacttypeid')
				->where('addressbookid = :abid',
						array(':abid'=>$id))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
			'addresscontactid'=>$data['addresscontactid'],
			'addressbookid'=>$data['addressbookid'],
			'contacttypeid'=>$data['contacttypeid'],
			'contacttypename'=>$data['contacttypename'],
			'addresscontactname'=>$data['addresscontactname'],
			'phoneno'=>$data['phoneno'],
			'mobilephone'=>$data['mobilephone'],
			'emailaddress'=>$data['emailaddress']
			);
		}
		$result=array_merge($result,array('rows'=>$row));;
		echo CJSON::encode($result);
	}
	public function actiongetdata() {
		$id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'addressbookid' => $id
		));
	}
	private function ModifyData($connection,$arraydata) {	
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		$sql = 'call ModifSupplier(:vid,:vfullname,:vtaxno,:vbankaccountno,:vbankname,:vaccountowner,:vpaymentmethodid,:vrecordstatus,:vdatauser)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vfullname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vtaxno',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vbankaccountno',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vbankname',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vaccountowner',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vpaymentmethodid',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-supplier"]["name"]);
		if (move_uploaded_file($_FILES["file-supplier"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$abid = '';$nourut = '';
				for ($row = 2; $row <= $highestRow; ++$row) {
					$nourut = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$fullname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$abid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '".$fullname."'")->queryScalar();
					if ($abid == '') {					
						$taxno = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
						$bankaccountno = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
						$bankname = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
						$accountowner = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
						$recordstatus = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
						$this->ModifyData($connection,array('',$fullname,$taxno,$bankaccountno,$bankname,$accountowner,'',$recordstatus));
						//get id addressbookid
						$abid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '".$fullname."'")->queryScalar();
					}
					if ($abid != '') {
						if ($objWorksheet->getCellByColumnAndRow(7, $row)->getValue() != '') {
							$addresstypename = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
							$addresstypeid = Yii::app()->db->createCommand("select addresstypeid from addresstype where addresstypename = '".$addresstypename."'")->queryScalar();
							$addressname = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
							$rt = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
							$rw = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
							$cityname = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
							$cityid = Yii::app()->db->createCommand("select cityid from city where cityname = '".$cityname."'")->queryScalar();
							$phoneno = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
							$faxno = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
							$lat = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
							$lng = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
							$this->ModifyDataAddress($connection,array('',$abid,$addresstypeid,$addressname,$rt,$rw,$cityid,$phoneno,$faxno,$lat,$lng));
						}
						if ($objWorksheet->getCellByColumnAndRow(16, $row)->getValue() != '') {
							$contacttypename = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
							$contacttypeid = Yii::app()->db->createCommand("select contacttypeid from contacttype where contacttypename = '".$contacttypename."'")->queryScalar();
							$contactname = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
							$contactph = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
							$contacthp = $objWorksheet->getCellByColumnAndRow(19, $row)->getValue();
							$contactemail = $objWorksheet->getCellByColumnAndRow(20, $row)->getValue();
							$this->ModifyDataContact($connection,array('',$abid, $contacttypeid,$contactname,$contacthp,$contactph,$contactemail));
						}
					}
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
			$this->ModifyData($connection,array((isset($_POST['supplier-addressbookid'])?$_POST['supplier-addressbookid']:''),
				$_POST['supplier-fullname'],
				$_POST['supplier-taxno'],
				$_POST['supplier-bankaccountno'],
				$_POST['supplier-bankname'],
				$_POST['supplier-accountowner'],
				$_POST['supplier-paymentmethodid'],
				isset($_POST['supplier-recordstatus'])?1:0));
			$transaction->commit();
			GetMessage(false,'insertsuccess');
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	private function ModifyDataAddress($connection,$arraydata) {		
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertaddress(:vaddressbookid,:vaddresstypeid,:vaddressname,:vrt,:vrw,:vcityid,:vphoneno,:vfaxno,:vlat,:vlng,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updateaddress(:vid,:vaddressbookid,:vaddresstypeid,:vaddressname,:vrt,:vrw,:vcityid,:vphoneno,:vfaxno,:vlat,:vlng,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		}
		$command->bindvalue(':vaddressbookid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vaddresstypeid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vaddressname',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vrt',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrw',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vcityid',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vphoneno',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vfaxno',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vlat',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vlng',$arraydata[10],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionsaveaddress() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDataAddress($connection,array((isset($_POST['addressid'])?$_POST['addressid']:''),$_POST['addressbookid'],$_POST['addresstypeid'],$_POST['addressname'],
				$_POST['rt'],$_POST['rw'],$_POST['cityid'],$_POST['phoneno'],$_POST['faxno'],$_POST['lat'],$_POST['lng']));
			$transaction->commit();
			GetMessage(false,'insertsuccess');
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	private function ModifyDataContact($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertaddresscontact(:vaddressbookid,:vcontacttypeid,:vaddresscontactname,:vphoneno,:vmobilephone,:vemailaddress,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateaddresscontact(:vid,:vaddressbookid,:vcontacttypeid,:vaddresscontactname,:vphoneno,:vmobilephone,:vemailaddress,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		}
		$command->bindvalue(':vaddressbookid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vcontacttypeid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vaddresscontactname',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vmobilephone',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vphoneno',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vemailaddress',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionsavecontact() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDataContact($connection,array((isset($_POST['addresscontactid'])?$_POST['addresscontactid']:''),
				$_POST['addressbookid'],$_POST['contacttypeid'],$_POST['addresscontactname'],$_POST['mobilephone'],
				$_POST['phoneno'],$_POST['emailaddress']));
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
				$sql = 'call Purgesupplier(:vid,:vdatauser)';
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
	public function actionPurgeaddress() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeaddress(:vid,:vdatauser)';
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
	public function actionPurgecontact() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgecontact(:vid,:vdatauser)';
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
	public function actionGenerateaddress() {
		if (!isset($_POST['paymentmethodid'])) {
			$sql = "select a.taxno,concat(c.addressname,' - ',d.cityname) as addressname,a.paymentmethodid,
				date_add('".date(Yii::app()->params['datetodb'], strtotime($_POST['date']))."',interval b.paydays day) as duedate
				from addressbook a 
				left join paymentmethod b on b.paymentmethodid = a.paymentmethodid 
				left join address c on c.addressbookid = a.addressbookid
				left join city d on d.cityid = c.cityid
				where a.addressbookid = ".$_POST['id']." 
				limit 1";
			$address = Yii::app()->db->createCommand($sql)->queryRow();
      echo CJSON::encode(array(
        'taxno' => $address['taxno'],
        'addressname' => $address['addressname'],
        'paymentmethodid' => $address['paymentmethodid'],
        'duedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($address['duedate'])),
      ));
		} else {
			$sql = "select date_add('".date(Yii::app()->params['datetodb'], strtotime($_POST['date']))."',interval paydays day) as duedate
				from paymentmethod 
				where paymentmethodid = ".$_POST['paymentmethodid'];
			$address = Yii::app()->db->createCommand($sql)->queryRow();
      echo CJSON::encode(array(
        'duedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($address['duedate'])),
      ));
		}
     Yii::app()->end();
  }
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['fullname'] = GetSearchText(array('GET'),'fullname');
		$this->dataprint['bankname'] = GetSearchText(array('GET'),'bankname');
		$this->dataprint['accountowner'] = GetSearchText(array('GET'),'accountowner');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'addressbookid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlefullname'] = GetCatalog('fullname');
		$this->dataprint['titlebankname'] = GetCatalog('bankname');
		$this->dataprint['titleaccountowner'] = GetCatalog('accountowner');
		$this->dataprint['titletaxno'] = GetCatalog('taxno');
		$this->dataprint['titlebankname'] = GetCatalog('bankname');
		$this->dataprint['titleaccountowner'] = GetCatalog('accountowner');
		$this->dataprint['titlebankaccountno'] = GetCatalog('bankaccountno');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}