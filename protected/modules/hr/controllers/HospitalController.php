<?php
class HospitalController extends Controller {
	public $menuname = 'hospital';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexaddress() {
		if(isset($_GET['grid']))
			echo $this->actionSearchAddress();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexcontact() {
		if(isset($_GET['grid']))
			echo $this->actionSearchcontact();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$addressbookid = isset ($_POST['addressbookid']) ? $_POST['addressbookid'] : '';
		$fullname = isset ($_POST['fullname']) ? $_POST['fullname'] : '';
		$bankname = isset ($_POST['bankname']) ? $_POST['bankname'] : '';
		$accountowner = isset ($_POST['accountowner']) ? $_POST['accountowner'] : '';
		$addressbookid = isset ($_GET['q']) ? $_GET['q'] : $addressbookid;
		$fullname = isset ($_GET['q']) ? $_GET['q'] : $fullname;
		$bankname = isset ($_GET['q']) ? $_GET['q'] : $bankname;
		$accountowner = isset ($_GET['q']) ? $_GET['q'] : $accountowner;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.addressbookid';
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
				->from('addressbook t')
				->leftjoin('account a','a.accountid = t.acchutangid')
				->where('addressbookid like :addressbookid 
					and fullname like :fullname 
					and bankname like :bankname 
					and accountowner like :accountowner
					and ishospital = 1',
						array(
						':addressbookid'=>'%'.$addressbookid.'%',
						':fullname'=>'%'.$fullname.'%',
						':bankname'=>'%'.$bankname.'%',
						':accountowner'=>'%'.$accountowner.'%'
						))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('addressbook t')
				->leftjoin('account a','a.accountid = t.acchutangid')
				->where('((fullname like :fullname) 
				or (addressbookid like :addressbookid)
				or (bankname like :bankname)
				or (accountowner like :accountowner)
				) 
				and t.ishospital = 1 and t.recordstatus = 1',
						array(
						':fullname'=>'%'.$fullname.'%',
						':addressbookid'=>'%'.$addressbookid.'%',
						':bankname'=>'%'.$bankname.'%',
						':accountowner'=>'%'.$accountowner.'%'
						))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.accountname')			
				->from('addressbook t')
				->leftjoin('account a','a.accountid = t.acchutangid')
				->where('addressbookid like :addressbookid 
					and fullname like :fullname 
					and bankname like :bankname 
					and accountowner like :accountowner
					and ishospital = 1',
						array(
						':addressbookid'=>'%'.$addressbookid.'%',
						':fullname'=>'%'.$fullname.'%',
						':bankname'=>'%'.$bankname.'%',
						':accountowner'=>'%'.$accountowner.'%'
						))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else 
		{
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.accountname')			
				->from('addressbook t')
				->leftjoin('account a','a.accountid = t.acchutangid')
				->where('((fullname like :fullname) 
				or (addressbookid like :addressbookid)
				or (bankname like :bankname)
				or (accountowner like :accountowner)
				) 
				and t.ishospital = 1 and t.recordstatus = 1',
						array(
						':fullname'=>'%'.$fullname.'%',
						':addressbookid'=>'%'.$addressbookid.'%',
						':bankname'=>'%'.$bankname.'%',
						':accountowner'=>'%'.$accountowner.'%'
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
			'acchutangid'=>$data['acchutangid'],
			'acchutangname'=>$data['accountname'],
			'bankaccountno'=>$data['bankaccountno'],
			'bankname'=>$data['bankname'],
			'accountowner'=>$data['accountowner'],
			'recordstatussupplier'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionSearchAddress() {
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
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.addressid';
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
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
			'addressid'=>$data['addressid'],
			'addressbookid'=>$data['addressbookid'],
			'addressname'=>$data['addressname'],
			'addresstypename'=>$data['addresstypename'],
			'rt'=>$data['rt'],
			'rw'=>$data['rw'],
			'cityname'=>$data['cityname'],
			'phoneno'=>$data['phoneno'],
			'faxno'=>$data['faxno'],
			'lat'=>$data['lat'],
			'lng'=>$data['lng'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));;
		echo CJSON::encode($result);
	}
	public function actionSearchcontact() {
		header('Content-Type: application/json');
		$id=0;	
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
		}
		else
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
		}
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.addresscontactid';
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
  public function actionGetData() {
		if (!isset($_GET['id'])) {
			$dadate              = new DateTime('now');
			$sql = "insert into addressbook (ishospital,recordstatus) values (1,1)";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'addressbookid' => $id
			));
		}
	}
	private function ModifyData($connection,$arraydata) {	
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call InsertHospital(:vfullname,:vtaxno,:vbankaccountno,:vbankname,:vaccountowner,:vlogo,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call UpdateHospital(:vid,:vfullname,:vtaxno,:vbankaccountno,:vbankname,:vaccountowner,:vlogo,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vfullname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vtaxno',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vbankaccountno',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vbankname',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vaccountowner',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vlogo',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-hospital"]["name"]);
		if (move_uploaded_file($_FILES["file-hospital"]["tmp_name"], $target_file)) {
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
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollBack();
				GetMessage(true,$e->getMessage());
			}
    }
	}
	public function actionSave() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['addressbookid'])?$_POST['addressbookid']:''),$_POST['fullname'],
				$_POST['taxno'],$_POST['bankaccountno'],$_POST['bankname'],$_POST['accountowner'],'',(($_POST['recordstatussupplier']=="on")?1:0)));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
	}
	private function ModifyDataAddress($connection,$arraydata) {		
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertaddress(:vaddressbookid,:vaddresstypeid,:vaddressname,:vrt,:vrw,:vcityid,:vphoneno,:vfaxno,:vlat,:vlng,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updateaddress(:vid,:vaddressbookid,:vaddresstypeid,:vaddressname,:vrt,:vrw,:vcityid,:vphoneno,:vfaxno,:vlat,:vlng,:vcreatedby)';
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
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionsaveaddress()
	{
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDataAddress($connection,array((isset($_POST['addressid'])?$_POST['addressid']:''),$_POST['addressbookid'],$_POST['addresstypeid'],$_POST['addressname'],
				$_POST['rt'],$_POST['rw'],$_POST['cityid'],$_POST['phoneno'],$_POST['faxno'],$_POST['lat'],$_POST['lng']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
	}
	private function ModifyDataContact($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertaddresscontact(:vaddressbookid,:vcontacttypeid,:vaddresscontactname,:vphoneno,:vmobilephone,:vemailaddress,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateaddresscontact(:vid,:vaddressbookid,:vcontacttypeid,:vaddresscontactname,:vphoneno,:vmobilephone,:vemailaddress,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		}
		$command->bindvalue(':vaddressbookid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vcontacttypeid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vaddresscontactname',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vmobilephone',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vphoneno',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vemailaddress',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionsavecontact() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDataContact(array((isset($_POST['addresscontactid'])?$_POST['addresscontactid']:''),
				$_POST['addressbookid'],$_POST['contacttypeid'],$_POST['addresscontactname'],$_POST['mobilephone'],
				$_POST['phoneno'],$_POST['emailaddress']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
	}
	public function actionPurge() {
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try
			{
				$sql = 'call Purgesupplier(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();				
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollback();
				GetMessage(true,$e->getMessage());
			}
		}
		else {
			GetMessage(true,getcatalog('chooseone'));
		}
	}
	public function actionPurgeaddress() {
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeaddress(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollback();
				GetMessage(true,$e->getMessage());
			}
		}
		else {
			GetMessage(true,getcatalog('chooseone'));
		}
	}	
	public function actionPurgecontact() {
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgecontact(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();				
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollback();
				GetMessage(true,$e->getMessage());
			}
		}
		else {
			GetMessage(true,getcatalog('chooseone'));
		}
	}
	public function actionDownPDF() {
	  parent::actionDownload();
	  $sql = "select a.addressbookid,a.fullname,
						ifnull((a.taxno),'-') as taxno,
						ifnull((a.bankaccountno),'-') as bankaccountno,
						ifnull((a.bankname),'-') as bankname,
						ifnull((a.accountowner),'-') as accountowner,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from addressbook a
						where a.isvendor = 1 ";						
		$addressbookid = filter_input(INPUT_GET,'addressbookid');
		$fullname = filter_input(INPUT_GET,'fullname');
		$bankname = filter_input(INPUT_GET,'bankname');
		$accountowner = filter_input(INPUT_GET,'accountowner');
		$sql .= " and coalesce(a.addressbookid,'') like '%".$addressbookid."%' 
			and coalesce(a.fullname,'') like '%".$fullname."%'
			and coalesce(a.bankname,'') like '%".$bankname."%'
			and coalesce(a.accountowner,'') like '%".$accountowner."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.addressbookid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by fullname asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('supplier');
		$this->pdf->AddPage('P',array(400,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('addressbookid'),
																	GetCatalog('fullname'),
																	GetCatalog('taxno'),
																	GetCatalog('bankaccountno'),
																	GetCatalog('bankname'),
																	GetCatalog('accountowner'),																	
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,90,40,55,40,40,80,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L');		
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['addressbookid'],$row1['fullname'],$row1['taxno'],$row1['bankaccountno'],$row1['bankname'],$row1['accountowner'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='supplier';
		parent::actionDownxls();
		$sql = "select a.addressbookid,a.fullname,
						ifnull((a.taxno),'-') as taxno,
						ifnull((a.bankaccountno),'-') as bankaccountno,
						ifnull((a.bankname),'-') as bankname,
						ifnull((a.accountowner),'-') as accountowner,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from addressbook a
						where a.isvendor = 1 ";
		$addressbookid = filter_input(INPUT_GET,'addressbookid');
		$fullname = filter_input(INPUT_GET,'fullname');
		$bankname = filter_input(INPUT_GET,'bankname');
		$accountowner = filter_input(INPUT_GET,'accountowner');
		$sql .= " and coalesce(a.addressbookid,'') like '%".$addressbookid."%' 
			and coalesce(a.fullname,'') like '%".$fullname."%'
			and coalesce(a.bankname,'') like '%".$bankname."%'
			and coalesce(a.accountowner,'') like '%".$accountowner."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.addressbookid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by fullname asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('addressbookid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('fullname'))			
			->setCellValueByColumnAndRow(2,2,GetCatalog('taxno'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('bankaccountno'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('bankname'))
			->setCellValueByColumnAndRow(6,2,GetCatalog('accountowner'))
			->setCellValueByColumnAndRow(7,2,GetCatalog('recordstatus'));
			
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['addressbookid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['fullname'])				
				->setCellValueByColumnAndRow(2, $i+1, $row1['taxno'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['bankaccountno'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['bankname'])
				->setCellValueByColumnAndRow(6, $i+1, $row1['accountowner'])
				->setCellValueByColumnAndRow(7, $i+1, $row1['recordstatus']);
			$i+=1;
		}		
		$this->getFooterXLS($this->phpExcel);
	}
}