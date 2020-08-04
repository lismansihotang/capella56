<?php
class CustomerController extends Controller {
	public $menuname = 'customer';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'addressbookid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'addressbookid' => 'text',
		'fullname' => 'text',
		'recordstatus' => 'text'
	];
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexaddressto() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionSearchAddressto();
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
	public function actionIndexaddresspay() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionSearchAddresspay();
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
		$addressbookid = GetSearchText(array('POST','Q'),'addressbookid',0,'int');
		$fullname = GetSearchText(array('POST','Q'),'fullname');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','addressbookid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$dependency = new CDbCacheDependency('SELECT MAX(a.updatedate) FROM addressbook a');		
		if (!isset($_GET['getdata'])) {
			if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')
				->from('addressbook t')
				->leftjoin('salesarea b','b.salesareaid = t.salesareaid')
				->leftjoin('pricecategory c','c.pricecategoryid = t.pricecategoryid')
				->leftjoin('groupcustomer d','d.groupcustomerid = t.groupcustomerid')
				->leftjoin('paymentmethod e','e.paymentmethodid = t.paymentmethodid')
				->where("coalesce(fullname,'') like :fullname and iscustomer = 1",
						array(':fullname'=>$fullname))
				->queryScalar();
		} else
		{
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')
				->from('addressbook t')
				->leftjoin('salesarea b','b.salesareaid = t.salesareaid')
				->leftjoin('pricecategory c','c.pricecategoryid = t.pricecategoryid')
				->leftjoin('groupcustomer d','d.groupcustomerid = t.groupcustomerid')
				->leftjoin('paymentmethod e','e.paymentmethodid = t.paymentmethodid')
				->where("(coalesce(fullname,'') like :fullname) and iscustomer = 1",
						array(':fullname'=>$fullname))
				->queryScalar();
		}
			$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('t.*,b.areaname,c.categoryname,d.groupname,e.paycode')			
				->from('addressbook t')
				->leftjoin('salesarea b','b.salesareaid = t.salesareaid')
				->leftjoin('pricecategory c','c.pricecategoryid = t.pricecategoryid')
				->leftjoin('groupcustomer d','d.groupcustomerid = t.groupcustomerid')
				->leftjoin('paymentmethod e','e.paymentmethodid = t.paymentmethodid')
				->where("coalesce(fullname,'') like :fullname and iscustomer = 1",
						array(':fullname'=>$fullname))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else 	{
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('t.*,b.areaname,c.categoryname,d.groupname,e.paycode')			
				->from('addressbook t')
				->leftjoin('salesarea b','b.salesareaid = t.salesareaid')
				->leftjoin('pricecategory c','c.pricecategoryid = t.pricecategoryid')
				->leftjoin('groupcustomer d','d.groupcustomerid = t.groupcustomerid')
				->leftjoin('paymentmethod e','e.paymentmethodid = t.paymentmethodid')
				->where("(coalesce(fullname,'') like :fullname) and iscustomer = 1",
						array(':fullname'=>$fullname))
				->order($sort.' '.$order)
				->queryAll();
			}
		foreach($cmd as $data) {	
			$row[] = array(
				'addressbookid'=>$data['addressbookid'],
				'fullname'=>$data['fullname'],
				'taxno'=>$data['taxno'],
				'ktpno'=>$data['ktpno'],
				'creditlimit'=>Yii::app()->format->formatNumber($data['creditlimit']),
				'currentlimit'=>Yii::app()->format->formatNumber($data['currentlimit']),
				'isstrictlimit'=>$data['isstrictlimit'],
				'salesareaid'=>$data['salesareaid'],
				'areaname'=>$data['areaname'],
				'pricecategoryid'=>$data['pricecategoryid'],
				'categoryname'=>$data['categoryname'],
				'groupcustomerid'=>$data['groupcustomerid'],
				'groupname'=>$data['groupname'],
				'bankaccountno'=>$data['bankaccountno'],
				'bankname'=>$data['bankname'],
				'accountowner'=>$data['accountowner'],
				'paymentmethodid'=>$data['paymentmethodid'],
				'paycode'=>$data['paycode'],
				'recordstatus' => $data['recordstatus']
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		} else {
			$result = Yii::app()->db->createCommand("
				select a.addressbookid,b.paymentmethodid,a.fullname,b.paycode,
					(select z.addressid from address z where z.addressbookid = a.addressbookid and z.addresstypeid = 10 limit 1) as addresstoid, 
					(select z.addressid from address z where z.addressbookid = a.addressbookid and z.addresstypeid = 8 limit 1) as addresspayid
				from addressbook a  
				left join paymentmethod b on b.paymentmethodid = a.paymentmethodid
				where a.addressbookid = ".$addressbookid)->queryRow();
		}
		return CJSON::encode($result);
	}
	public function actionSearchAddress() {
		return GetData([
			'from' => 'address t 
				left join addresstype b on b.addresstypeid = t.addresstypeid 
				left join city c on c.cityid = t.cityid',
			'sort' => [
				'datatype' => 'POST',
				'default' => 'addressid'
			],
			'order' => [
				'datatype' => 'POST',
				'default' => 'desc'
			],
			'viewfield' => [
				'addressid'=>[
					'type'=>'text'
				],
				'addressbookid'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'addressname'=>[
					'type'=>'text',
				],
				'addresstypeid'=>[
					'type'=>'text',
					'from'=>'b'
				],
				'addresstypename'=>[
					'type'=>'text',
					'from'=>'b'
				],
				'rt'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'rw'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'cityid'=>[
					'type'=>'text',
					'from'=>'c'
				],
				'cityname'=>[
					'type'=>'text',
					'from'=>'c'
				],
				'phoneno'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'faxno'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'lat'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'lng'=>[
					'type'=>'text',
					'from'=>'t'
				],
			],
			'paging' => true,
			'searchfield' => [
				'id' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'sourcefield' => 'addressbookid',
					'strict' => '=',
				],
			]
		]);
	}
	public function actionSearchAddressto() {
		return GetData([
			'from' => 'address t 
				left join addresstype b on b.addresstypeid = t.addresstypeid 
				left join city c on c.cityid = t.cityid',
			'sort' => [
				'datatype' => 'POST',
				'default' => 'addressid'
			],
			'order' => [
				'datatype' => 'POST',
				'default' => 'desc'
			],
			'viewfield' => [
				'addressid'=>[
					'type'=>'text'
				],
				'addressbookid'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'addressname'=>[
					'type'=>'text',
				],
				'addresstypeid'=>[
					'type'=>'text',
					'from'=>'b'
				],
				'addresstypename'=>[
					'type'=>'text',
					'from'=>'b'
				],
				'rt'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'rw'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'cityid'=>[
					'type'=>'text',
					'from'=>'c'
				],
				'cityname'=>[
					'type'=>'text',
					'from'=>'c'
				],
				'phoneno'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'faxno'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'lat'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'lng'=>[
					'type'=>'text',
					'from'=>'t'
				],
			],
			'paging' => false,
			'searchfield' => [
				'id' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'sourcefield' => 'addressbookid',
					'strict' => '=',
				],
			],
			'addonsearch' => 'b.addresstypeid = 2'
		]);
	}
	public function actionSearchAddresspay() {
		return GetData([
			'from' => 'address t 
				left join addresstype b on b.addresstypeid = t.addresstypeid 
				left join city c on c.cityid = t.cityid',
			'sort' => [
				'datatype' => 'POST',
				'default' => 'addressid'
			],
			'order' => [
				'datatype' => 'POST',
				'default' => 'desc'
			],
			'viewfield' => [
				'addressid'=>[
					'type'=>'text'
				],
				'addressbookid'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'addressname'=>[
					'type'=>'text',
				],
				'addresstypeid'=>[
					'type'=>'text',
					'from'=>'b'
				],
				'addresstypename'=>[
					'type'=>'text',
					'from'=>'b'
				],
				'rt'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'rw'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'cityid'=>[
					'type'=>'text',
					'from'=>'c'
				],
				'cityname'=>[
					'type'=>'text',
					'from'=>'c'
				],
				'phoneno'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'faxno'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'lat'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'lng'=>[
					'type'=>'text',
					'from'=>'t'
				],
			],
			'paging' => false,
			'searchfield' => [
				'id' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'sourcefield' => 'addressbookid',
					'strict' => '=',
				],
			],
			'addonsearch' => 'b.addresstypeid = 1'
		]);
	}
	public function actionSearchcontact() {
		return GetData([
			'from' => 'addresscontact t 
				left join contacttype b on b.contacttypeid = t.contacttypeid',
			'sort' => [
				'datatype' => 'POST',
				'default' => 'addresscontactid'
			],
			'order' => [
				'datatype' => 'POST',
				'default' => 'desc'
			],
			'viewfield' => [
				'addresscontactid'=>[
					'type'=>'text'
				],
				'addressbookid'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'contacttypeid'=>[
					'type'=>'text',
					'from'=>'b'
				],
				'contacttypename'=>[
					'type'=>'text',
					'from'=>'b'
				],
				'addresscontactname'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'phoneno'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'phoneno'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'mobilephone'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'emailaddress'=>[
					'type'=>'text',
					'from'=>'t'
				],
			],
			'paging' => false,
			'searchfield' => [
				'id' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'sourcefield' => 'addressbookid',
					'strict' => '=',
				],
			],
		]);
	}
	public function actiongetdata() {
		return GetRandomHeader([
			'key' => 'addressbookid',
			'table' => 'addressbook'
		]);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		$sql = 'call ModifCustomer(:vid,:vfullname,:vtaxno,:vktpno,:vcreditlimit,:visstrictlimit,:vsalesareaid,:vpricecategoryid,:vgroupcustomerid,:vbankname,:vbankaccountno,
			:vaccountowner,:vpaymentmethodid,:vrecordstatus,:vdatauser)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vfullname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vtaxno',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vktpno',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vcreditlimit',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':visstrictlimit',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vsalesareaid',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vpricecategoryid',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vgroupcustomerid',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vbankname',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vbankaccountno',$arraydata[10],PDO::PARAM_STR);
		$command->bindvalue(':vaccountowner',$arraydata[11],PDO::PARAM_STR);
		$command->bindvalue(':vpaymentmethodid',$arraydata[12],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[13],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();		
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-customer"]["name"]);
		if (move_uploaded_file($_FILES["file-customer"]["tmp_name"], $target_file)) {
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
						$ktpno = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
						$kreditlimit = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
						$strictlimit = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
						$salesareaname = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
						$salesareaid = Yii::app()->db->createCommand("select salesareaid from salesarea where areaname = '".$salesareaname."'")->queryScalar();
						$pricecategoryname = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
						$pricecategoryid = Yii::app()->db->createCommand("select pricecategoryid from pricecategory where categoryname = '".$pricecategoryname."'")->queryScalar();
						$groupcustomername = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
						$groupcustomerid = Yii::app()->db->createCommand("select groupcustomerid from groupcustomer where groupname = '".$groupcustomername."'")->queryScalar();
						$bankaccountno = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
						$bankname = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
						$accountowner = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
						$paycode = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
						$paymentmethodid = Yii::app()->db->createCommand("select paymentmethodid from paymentmethod where paycode = '".$paycode."'")->queryScalar();						
						$recordstatus = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
						$this->ModifyData($connection,array('',$fullname,$taxno,$ktpno,$kreditlimit,$strictlimit,$salesareaid,$pricecategoryid,$groupcustomerid, 
							$bankaccountno,$bankname,$accountowner,$paymentmethodid,$recordstatus));
						//get id addressbookid
						$abid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '".$fullname."'")->queryScalar();
					}
					if ($abid != '') {
						if ($objWorksheet->getCellByColumnAndRow(14, $row)->getValue() != '') {
							$addresstypename = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
							$addresstypeid = Yii::app()->db->createCommand("select addresstypeid from addresstype where addresstypename = '".$addresstypename."'")->queryScalar();
							$addressname = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
							$rt = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
							$rw = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
							$cityname = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
							$cityid = Yii::app()->db->createCommand("select cityid from city where cityname = '".$cityname."'")->queryScalar();
							$phoneno = $objWorksheet->getCellByColumnAndRow(19, $row)->getValue();
							$faxno = $objWorksheet->getCellByColumnAndRow(20, $row)->getValue();
							$lat = $objWorksheet->getCellByColumnAndRow(21, $row)->getValue();
							$lng = $objWorksheet->getCellByColumnAndRow(22, $row)->getValue();
							$this->ModifyDataAddress($connection,array('',$abid,$addresstypeid,$addressname,$rt,$rw,$cityid,$phoneno,$faxno,$lat,$lng));
						}
						if ($objWorksheet->getCellByColumnAndRow(23, $row)->getValue() != '') {
							$contacttypename = $objWorksheet->getCellByColumnAndRow(23, $row)->getValue();
							$contacttypeid = Yii::app()->db->createCommand("select contacttypeid from contacttype where contacttypename = '".$contacttypename."'")->queryScalar();
							$contactname = $objWorksheet->getCellByColumnAndRow(24, $row)->getValue();
							$contactph = $objWorksheet->getCellByColumnAndRow(25, $row)->getValue();
							$contacthp = $objWorksheet->getCellByColumnAndRow(26, $row)->getValue();
							$contactemail = $objWorksheet->getCellByColumnAndRow(27, $row)->getValue();
							$this->ModifyDataContact($connection,array('',$abid, $contacttypeid,$contactname,$contacthp,$contactph,$contactemail));
						}
						if ($objWorksheet->getCellByColumnAndRow(28, $row)->getValue() != '') {
							$discvalue = $objWorksheet->getCellByColumnAndRow(28, $row)->getValue();
							$this->ModifyDataDisc($connection,array('',$abid,$discvalue));
						}
					}
				}
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,'Line: '.$row.' ==> '.implode(" ",$e->errorInfo));
			}
    }
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'ModifCustomer',
			'spupdate' => 'ModifCustomer',
			'arraydata' => [
				'vid'=>(isset($_POST['customer-addressbookid'])?$_POST['customer-addressbookid']:''),
				'fullname'=>$_POST['customer-fullname'],
				'taxno'=>$_POST['customer-taxno'],
				'ktpno'=>$_POST['customer-ktpno'],
				'creditlimit'=>$_POST['customer-creditlimit'],
				'isstrictlimit'=>(isset($_POST['customer-isstrictlimit'])?1:0),
				'salesareaid'=>$_POST['customer-salesareaid'],
				'pricecategoryid'=>$_POST['customer-pricecategoryid'],
				'groupcustomerid'=>$_POST['customer-groupcustomerid'],
				'bankname'=>$_POST['customer-bankname'],
				'bankaccountno'=>$_POST['customer-bankaccountno'],
				'accountowner'=>$_POST['customer-accountowner'],
				'paymentmethodid'=>$_POST['customer-paymentmethodid'],
				'recordstatus'=>isset($_POST['customer-recordstatus'])?1:0,
			]
		]);
	}
	public function actionsaveaddress() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'Insertaddress',
			'spupdate' => 'Updateaddress',
			'arraydata' => [
				'vid'=>(isset($_POST['addressid'])?$_POST['addressid']:''),
				'addressbookid'=>$_POST['addressbookid'],
				'addresstypeid'=>$_POST['addresstypeid'],
				'addressname'=>$_POST['addressname'],
				'rt'=>$_POST['rt'],
				'rw'=>$_POST['rw'],
				'cityid'=>$_POST['cityid'],
				'phoneno'=>$_POST['phoneno'],
				'faxno'=>$_POST['faxno'],
				'lat'=>$_POST['lat'],
				'lng'=>$_POST['lng'],
			]
		]);
	}
	public function actionsavecontact() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'Insertaddresscontact',
			'spupdate' => 'Updateaddresscontact',
			'arraydata' => [
				'vid'=>(isset($_POST['addresscontactid'])?$_POST['addresscontactid']:''),
				'addressbookid'=>$_POST['addressbookid'],
				'contacttypeid'=>$_POST['contacttypeid'],
				'addresscontactname'=>$_POST['addresscontactname'],
				'mobilephone'=>$_POST['mobilephone'],
				'phoneno'=>$_POST['phoneno'],
				'emailaddress'=>$_POST['emailaddress'],
			]
		]);
	}
  private function ModifyDataDisc($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call (:vaddressbookid,:vdiscvalue,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call (:vid,:vaddressbookid,:vdiscvalue,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		}
		$command->bindvalue(':vaddressbookid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vdiscvalue',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}		
	public function actionsavedisc() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'Insertcustomerdisc',
			'spupdate' => 'Updatecustomerdisc',
			'arraydata' => [
				'vid'=>(isset($_POST['custdiscid'])?$_POST['custdiscid']:''),
				'addressbookid'=>$_POST['addressbookid'],
				'discvalue'=>$_POST['discvalue'],
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgecustomer',			
		]);
	}
	public function actionPurgeaddress() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgeaddress',			
		]);
	}
	public function actionPurgecontact() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgecontact',			
		]);
	}
	public function actionPurgedisc() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgedisc',			
		]);
	}
	public function actionGenerateaddress() {
		$newDate = date("Y-m-d", strtotime($_POST['date']));
		$sql = "select a.taxno,concat(c.addressname,' - ',d.cityname) as addressname,a.paymentmethodid,date_add('".$newDate."',interval b.paydays day) as duedate
			from addressbook a 
			join paymentmethod b on b.paymentmethodid = a.paymentmethodid 
			left join address c on c.addressbookid = a.addressbookid
			left join city d on d.cityid = c.cityid
			where a.addressbookid = ".$_POST['id']." 
			limit 1";
		$address = Yii::app()->db->createCommand($sql)->queryRow();
    if (Yii::app()->request->isAjaxRequest) {
      echo CJSON::encode(array(
        'taxno' => $address['taxno'],
        'addressname' => $address['addressname'],
        'paymentmethodid' => $address['paymentmethodid'],
        'duedate' => $address['duedate'],
      ));
      Yii::app()->end();
    }
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
		$this->dataprint['titlebankaccountno'] = GetCatalog('bankaccountno');
		$this->dataprint['titleaddressname'] = GetCatalog('addressname');
		$this->dataprint['titleaddresstypename'] = GetCatalog('addresstypename');
		$this->dataprint['titleaddresscontactname'] = GetCatalog('addresscontactname');
		$this->dataprint['titlecontacttypename'] = GetCatalog('contacttypename');
		$this->dataprint['titlemobilephone'] = GetCatalog('mobilephone');
		$this->dataprint['titleemailaddress'] = GetCatalog('emailaddress');
		$this->dataprint['titlektp'] = GetCatalog('ktp');
		$this->dataprint['titlephoneno'] = GetCatalog('phoneno');
		$this->dataprint['titlefaxno'] = GetCatalog('faxno');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}