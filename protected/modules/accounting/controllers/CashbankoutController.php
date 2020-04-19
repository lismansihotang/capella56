<?php
class CashbankoutController extends Controller {
  public $menuname = 'cashbankout';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexjurnal() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchjurnal();
    else
      $this->renderPartial('index', array());
  }
	public function actionIndexacc() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchacc();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
    $id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'cashbankoutid' => $id
		));
  }
  public function search() {
    header('Content-Type: application/json');
    $cashbankoutid 	= GetSearchText(array('POST'),'cashbankoutid');
    $cashbankoutdate 	= GetSearchText(array('POST'),'cashbankoutdate');
    $supplier 	= GetSearchText(array('POST'),'supplier');
    $companycode 	= GetSearchText(array('POST'),'companycode');
    $cashbankoutno 	= GetSearchText(array('POST'),'cashbankoutno');
    $invoiceapno 	= GetSearchText(array('POST'),'invoiceapno');
    $pono 	= GetSearchText(array('POST'),'pono');
    $headernote 	= GetSearchText(array('POST'),'headernote');
    $reqpayno 	= GetSearchText(array('POST'),'reqpayno');
    $recordstatus 	= GetSearchText(array('POST'),'recordstatus');
    $acccodeheader 	= GetSearchText(array('POST'),'acccodeheader');
    $acccodedetail 	= GetSearchText(array('POST'),'acccodedetail');
    $accnamedetail 	= GetSearchText(array('POST'),'accnamedetail');
    $accnameheader 	= GetSearchText(array('POST'),'accnameheader');
    $cashbankoutdate 	= GetSearchText(array('POST'),'cashbankoutdate');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','cashbankoutid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset       	= ($page - 1) * $rows;
    $result       	= array();
    $row          	= array();

		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('cashbankout t')
      ->leftjoin('company a', 'a.companyid = t.companyid')
      ->leftjoin('account c', 'c.accountid = t.accountid')
      ->leftjoin('reqpay d', 'd.reqpayid = t.reqpayid')
			->where("
				((coalesce(t.cashbankoutid,'') like '%".$cashbankoutid . "%') 
				and (coalesce(t.cashbankoutdate,'') like '%". $cashbankoutdate . "%') 
				and (coalesce(a.companycode,'') like '%" . $companycode . "%') 
				and (coalesce(t.headernote,'') like '%" . $headernote . "%') 
				and (coalesce(d.reqpayno,'') like '%" . $reqpayno . "%')  
				and (coalesce(t.cashbankoutdate,'') like '%" . $cashbankoutdate . "%')  
				and (coalesce(c.accountcode,'') like '%" . $acccodeheader . "%')  
				and (coalesce(c.accountname,'') like '%" . $accnameheader . "%')  
				and (coalesce(t.cashbankoutno,'') like '%" . $cashbankoutno . "%'))
				and t.recordstatus in (".getUserRecordStatus('listcbout').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				(($acccodedetail != '%%')?" and t.cashbankoutid in (
					select distinct za.cashbankoutid
					from cashbankoutjurnal za 
					left join account zb on zb.accountid = za.accountid 
					where zb.accountcode like '".$acccodedetail."')
				":'').
				(($accnamedetail != '%%')?" and t.cashbankoutid in (
					select distinct za.cashbankoutid 
					from cashbankoutjurnal za 
					left join account zb on zb.accountid = za.accountid 
					where zb.accountname like '".$accnamedetail."')
				":'').
				"
				and a.companyid in (".getUserObjectValues('company').")".
				(($supplier != '%%')?" and t.cashbankoutid in (
				SELECT distinct cashbankoutid 
				FROM cashbankoutdetail a 
				JOIN invoiceap b ON b.invoiceapid = a.invoiceapid 
				JOIN addressbook c ON c.addressbookid = b.addressbookid 
				where coalesce(c.fullname,'') like '%".$supplier."%'
				)":'').
				(($pono != '%%')?" and t.cashbankoutid in (
				SELECT distinct cashbankoutid 
				FROM cashbankoutdetail a 
				JOIN invoiceap b ON b.invoiceapid = a.invoiceapid 
				JOIN addressbook c ON c.addressbookid = b.addressbookid 
				JOIN poheader d ON d.poheaderid = b.poheaderid 
				where coalesce(d.pono,'') like '%".$pono."%'
				)":'').
				(($invoiceapno != '%%')?" and t.cashbankoutid in (
				SELECT distinct cashbankoutid 
				FROM cashbankoutdetail a 
				JOIN invoiceap b ON b.invoiceapid = a.invoiceapid 
				where coalesce(b.invoiceapno,'') like '%".$invoiceapno."%'
				)":''), array())->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.companycode,c.accountname,d.reqpayno')
			->from('cashbankout t')
      ->leftjoin('company a', 'a.companyid = t.companyid')
      ->leftjoin('account c', 'c.accountid = t.accountid')
      ->leftjoin('reqpay d', 'd.reqpayid = t.reqpayid')
			->where("
        ((coalesce(t.cashbankoutid,'') like '%".$cashbankoutid . "%') 
				and (coalesce(t.cashbankoutdate,'') like '%". $cashbankoutdate . "%') 
				and (coalesce(a.companycode,'') like '%" . $companycode . "%') 
				and (coalesce(t.headernote,'') like '%" . $headernote . "%') 
				and (coalesce(d.reqpayno,'') like '%" . $reqpayno . "%') 
				and (coalesce(c.accountcode,'') like '%" . $acccodeheader . "%')  
				and (coalesce(c.accountname,'') like '%" . $accnameheader . "%')  
				and (coalesce(t.cashbankoutdate,'') like '%" . $cashbankoutdate . "%') 
				and (coalesce(t.cashbankoutno,'') like '%" . $cashbankoutno . "%'))
				and t.recordstatus in (".getUserRecordStatus('listcbout').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				(($acccodedetail != '%%')?" and t.cashbankoutid in (
					select distinct za.cashbankoutid
					from cashbankoutjurnal za 
					left join account zb on zb.accountid = za.accountid 
					where zb.accountcode like '".$acccodedetail."')
				":'').
				(($accnamedetail != '%%')?" and t.cashbankoutid in (
					select distinct za.cashbankoutid 
					from cashbankoutjurnal za 
					left join account zb on zb.accountid = za.accountid 
					where zb.accountname like '".$accnamedetail."')
				":'').
				"
				and a.companyid in (".getUserObjectValues('company').")".
				(($supplier != '%%')?" and t.cashbankoutid in (
				SELECT distinct cashbankoutid 
				FROM cashbankoutdetail a 
				JOIN invoiceap b ON b.invoiceapid = a.invoiceapid 
				JOIN addressbook c ON c.addressbookid = b.addressbookid 
				where coalesce(c.fullname,'') like '%".$supplier."%'
				)":'').
				(($pono != '%%')?" and t.cashbankoutid in (
				SELECT distinct cashbankoutid 
				FROM cashbankoutdetail a 
				JOIN invoiceap b ON b.invoiceapid = a.invoiceapid 
				JOIN addressbook c ON c.addressbookid = b.addressbookid 
				JOIN poheader d ON d.poheaderid = b.poheaderid 
				where coalesce(d.pono,'') like '%".$pono."%'
				)":'').
				(($invoiceapno != '%%')?" and t.cashbankoutid in (
				SELECT distinct cashbankoutid 
				FROM cashbankoutdetail a 
				JOIN invoiceap b ON b.invoiceapid = a.invoiceapid 
				where coalesce(b.invoiceapno,'') like '%".$invoiceapno."%'
				)":''), array())->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cashbankoutid' => $data['cashbankoutid'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'cashbankoutdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['cashbankoutdate'])),
        'cashbankoutno' => $data['cashbankoutno'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'reqpayid' => $data['reqpayid'],
        'reqpayno' => $data['reqpayno'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusname' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionsearchdetail() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'cashbankoutdetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $footer             = array();
    $cmd             = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('cashbankoutdetail t')
			->leftjoin('addressbook a', 'a.addressbookid=t.addressbookid')
			->leftjoin('invoiceap b', 'b.invoiceapid=t.invoiceapid')
			->leftjoin('currency c', 'c.currencyid=t.currencyid')
			->leftjoin('cheque d', 'd.chequeid=t.chequeid')
			->leftjoin('poheader e', 'e.poheaderid=b.poheaderid')
			->where("(cashbankoutid = :cashbankoutid)", array(
      ':cashbankoutid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
		->select('t.*,a.fullname,b.invoiceapno,c.currencyname,c.symbol,d.bilyetgirono,e.pono')
		->from('cashbankoutdetail t')
		->leftjoin('addressbook a', 'a.addressbookid=t.addressbookid')
		->leftjoin('invoiceap b', 'b.invoiceapid=t.invoiceapid')
		->leftjoin('currency c', 'c.currencyid=t.currencyid')
		->leftjoin('cheque d', 'd.chequeid=t.chequeid')
		->leftjoin('poheader e', 'e.poheaderid=b.poheaderid')
		->where("(cashbankoutid = :cashbankoutid)", array(
      ':cashbankoutid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'cashbankoutdetailid' => $data['cashbankoutdetailid'],
        'cashbankoutid' => $data['cashbankoutid'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
				'chequeid' => $data['chequeid'],
				'pono' => $data['pono'],
        'bilyetgirono' => $data['bilyetgirono'],
        'invoiceapid' => $data['invoiceapid'],
        'invoiceapno' => $data['invoiceapno'],
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'ratevalue' => Yii::app()->format->formatNumber($data['ratevalue']),
        'nobuktipotong' => $data['nobuktipotong'],
        'symbol' => $data['symbol'],
        'detailnote' => $data['detailnote']
      );
			$symbol = $data['symbol'];
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		$sql = "select sum(amount) as amount from cashbankoutdetail where cashbankoutid = ".$id;
		$cmd = Yii::app()->db->createCommand($sql)->queryRow();
		$footer[] = array(
      'accountname' => 'Total',
			'symbol' => $symbol,
      'amount' => Yii::app()->format->formatNumber($cmd['amount']),
    );
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
	public function actionsearchjurnal() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'cashbankoutjurnalid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $footer             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cashbankoutjurnal t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->leftjoin('company c', 'c.companyid = a.companyid')->where("(cashbankoutid = :cashbankoutid) and t.accountid > 0", array(
      ':cashbankoutid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.accountcode,a.accountname,b.currencyname,c.companyname,b.symbol')->from('cashbankoutjurnal t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->leftjoin('company c', 'c.companyid = a.companyid')->where("(cashbankoutid = :cashbankoutid) and t.accountid > 0", array(
      ':cashbankoutid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'cashbankoutjurnalid' => $data['cashbankoutjurnalid'],
        'cashbankoutid' => $data['cashbankoutid'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'debit' => Yii::app()->format->formatNumber($data['debit']),
        'credit' => Yii::app()->format->formatNumber($data['credit']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'symbol' => $data['symbol'],
        'companyname' => $data['companyname'],
        'ratevalue' => Yii::app()->format->formatNumber($data['ratevalue']),
        'detailnote' => $data['detailnote']
      );
			$symbol = $data['symbol'];
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		$sql = "select sum(debit) as debit, sum(credit) as credit from cashbankoutjurnal where cashbankoutid = ".$id;
		$cmd = Yii::app()->db->createCommand($sql)->queryRow();
		$footer[] = array(
      'accountname' => 'Total',
			'symbol' => $symbol,
      'debit' => Yii::app()->format->formatNumber($cmd['debit']),
      'credit' => Yii::app()->format->formatNumber($cmd['credit']),
    );
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
	public function actionsearchacc() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'cashbankoutaccid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $footer             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('cashbankoutacc t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->leftjoin('company c', 'c.companyid = a.companyid')->where("(cashbankoutid = :cashbankoutid) and t.accountid > 0", array(
      ':cashbankoutid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.accountcode,a.accountname,b.currencyname,c.companyname,b.symbol')
		->from('cashbankoutacc t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->leftjoin('company c', 'c.companyid = a.companyid')->where("(cashbankoutid = :cashbankoutid) and t.accountid > 0", array(
      ':cashbankoutid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'cashbankoutaccid' => $data['cashbankoutaccid'],
        'cashbankoutid' => $data['cashbankoutid'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'debit' => Yii::app()->format->formatNumber($data['debit']),
        'credit' => Yii::app()->format->formatNumber($data['credit']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'symbol' => $data['symbol'],
        'companyname' => $data['companyname'],
        'ratevalue' => Yii::app()->format->formatNumber($data['ratevalue']),
        'itemnote' => $data['itemnote']
      );
			$symbol = $data['symbol'];
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		$sql = "select sum(debit) as debit, sum(credit) as credit from cashbankoutacc where cashbankoutid = ".$id;
		$cmd = Yii::app()->db->createCommand($sql)->queryRow();
		$footer[] = array(
      'accountname' => 'Total',
			'symbol' => $symbol,
      'debit' => Yii::app()->format->formatNumber($cmd['debit']),
      'credit' => Yii::app()->format->formatNumber($cmd['credit']),
    );
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		$sql     = 'call Modifcashbankout(:vid,:vcompanyid,:vcashbankoutdate,:vaccountid,:vreqpayid,:vheadernote,:vdatauser)';
		$command = $connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vcompanyid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vcashbankoutdate', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vaccountid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vreqpayid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-cashbankout"]["name"]);
		if (move_uploaded_file($_FILES["file-cashbankout"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$abid = '';$nourut = '';
			$connection  = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$nourut = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
					$noref = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$journaldate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(3, $row)->getValue()));
					$journalnote = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$abid = Yii::app()->db->createCommand("select cashbankoutid from cashbankout 
						where companyid = '".$companyid."' 
						and noref = '".$noref."' 
						and journaldate = '".$journaldate."'
						and journalnote = '".$journalnote."'")->queryScalar();
					$recordstatus = findstatusbyuser('insbs');
					if ($abid == '') {					
						$this->ModifyData($connection,array('',$companyid,$journaldate,$noref,$journalnote,$recordstatus));
						//get id addressbookid
						$abid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '".$fullname."'")->queryScalar();
					}
					if ($abid != '') {
						if ($objWorksheet->getCellByColumnAndRow(5, $row)->getValue() != '') {
							$accountcode = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
							$accountid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$accountcode."'")->queryScalar();
							$debit = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
							$credit = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
							$currencyname = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
							$currencyid = Yii::app()->db->createCommand("select cityid from city where cityname = '".$cityname."'")->queryScalar();
							$ratevalue = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
							$detailnote = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
							$this->ModifyDataAddress($connection,array('',$abid,$accountid,$debit,$credit,$currencyid,$ratevalue,$detailnote));
						}
					}
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
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['cashbankout-cashbankoutid'])?$_POST['cashbankout-cashbankoutid']:''),
				$_POST['cashbankout-companyid'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['cashbankout-cashbankoutdate'])),
				$_POST['cashbankout-accountid'],
				$_POST['cashbankout-reqpayid'],
				$_POST['cashbankout-headernote']
			));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
	private function ModifyDataDetail($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertcashbankoutdetail(:vcashbankoutid,:vcashbankoutid,:vchequeid,:vnobuktipotong,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatecashbankoutdetail(:vid,:vcashbankoutid,:vchequeid,:vnobuktipotong,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcashbankoutid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vchequeid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vnobuktipotong', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vdetailnote', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavedetail() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataDetail($connection,array((isset($_POST['cashbankoutdetailid'])?$_POST['cashbankoutdetailid']:''),$_POST['cashbankoutid'],
				$_POST['chequeid'],$_POST['nobuktipotong'],$_POST['detailnote'],
			));
			$transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
  private function ModifyDataacc($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertcashbankoutacc(:vcashbankoutid,:vaccountid,:vdebit,:vcredit,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatecashbankoutacc(:vid,:vcashbankoutid,:vaccountid,:vdebit,:vcredit,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcashbankoutid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vaccountid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vdebit', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vcredit', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vratevalue', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vdetailnote', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSaveacc() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataAcc($connection,array((isset($_POST['cashbankoutaccid'])?$_POST['cashbankoutaccid']:''),
				$_POST['cashbankoutid'],$_POST['accountid'],
				$_POST['debit'],$_POST['credit'],$_POST['currencyid'],$_POST['ratevalue'],$_POST['itemnote']));
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
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
				$sql     = 'call Purgecashbankout(:vid,:vdatauser)';
				$command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  public function actionPurgedetail() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgecashbankoutdetail(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  public function actionPurgetax() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgecashbankouttax(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
       GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
  }
	public function actionPurgeacc() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgecashbankoutacc(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
       GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  public function actionReject() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Rejectcashbankout(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Approvecashbankout(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  public function actionGeneratedetail(){
    if (Yii::app()->request->isAjaxRequest) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateCboutreqpay(:vid, :vhid, :vaccountid, :vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->bindvalue(':vaccountid', $_POST['accountid'], PDO::PARAM_INT);
        $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    }
  }
	public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select a.cashbankoutid, a.cashbankoutno, a.cashbankoutdate, d.accountname, b.reqpayno, a.headernote,c.companyid						
						from cashbankout a
						left join account d on d.accountid = a.accountid
						left join reqpay b on b.reqpayid = a.reqpayid
						join plant c on c.plantid = a.plantid
						";
		$cashbankoutid = filter_input(INPUT_GET,'cashbankoutid');
		$reqpayno = filter_input(INPUT_GET,'reqpayno');
		$cashbankoutno = filter_input(INPUT_GET,'cashbankoutno');
		$accountname = filter_input(INPUT_GET,'accountname');
		$cashbankoutdate = filter_input(INPUT_GET,'cashbankoutdate');
		$sql .= " where coalesce(a.cashbankoutid,'') like '%".$cashbankoutid."%' 
			and coalesce(b.reqpayno,'') like '%".$reqpayno."%'
			and coalesce(a.cashbankoutno,'') like '%".$cashbankoutno."%'
			and coalesce(d.accountname,'') like '%".$accountname."%'
			and coalesce(a.cashbankoutdate,'') like '%".$cashbankoutdate."%'
			";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.cashbankoutid in (" . $_GET['id'] . ")";
    }
    $totalamount1 =0;
		$debit            = 0;
    $credit           = 0;    
		$command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('cashbankout');
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->AddPage('P');
    $this->pdf->setFont('Arial', 'B', 10);
    $this->pdf->AliasNBPages();
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(9);
			$this->pdf->text(15, $this->pdf->gety() + 5, 'Nama Akun ');
      $this->pdf->text(45, $this->pdf->gety() + 5, ': ' . $row['accountname']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'No Dokumen ');
      $this->pdf->text(45, $this->pdf->gety() + 10, ': ' . $row['cashbankoutno']);
      $this->pdf->text(15, $this->pdf->gety() + 15, 'Tgl Dokumen ');
      $this->pdf->text(45, $this->pdf->gety() + 15, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['cashbankoutdate'])));			
			$this->pdf->text(15, $this->pdf->gety() + 20, 'No Referensi ');
      $this->pdf->text(45, $this->pdf->gety() + 20, ': ' . $row['reqpayno']);
			
			$sql1        = "select b.accountcode,b.accountname, a.debit,a.credit,c.symbol,a.detailnote,a.ratevalue
							from cashbankoutjurnal a
							left join account b on b.accountid = a.accountid
							left join currency c on c.currencyid = a.currencyid
							where a.cashbankoutid = '" . $row['cashbankoutid'] . "'
							order by cashbankoutjurnalid ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
			 $this->pdf->setFont('Arial', 'B', 8);
			$this->pdf->text(10,$this->pdf->gety()+30,'JURNAL');
      $this->pdf->sety($this->pdf->gety() + 35);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        70,
        30,
        30,
        15,
        55
      ));
      $this->pdf->colheader = array(
        'No',
        'Account',
        'Debit',
        'Credit',
        'Rate',
        'Detail Note'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->coldetailalign = array(
        'C',
        'L',
        'R',
        'R',
        'R',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i      = $i + 1;
        $debit  = $debit + ($row1['debit'] * $row1['ratevalue']);
        $credit = $credit + ($row1['credit'] * $row1['ratevalue']);
        $this->pdf->row(array(
          $i,
          $row1['accountcode'] . ' ' . $row1['accountname'],
          Yii::app()->format->formatCurrency($row1['debit'], $row1['symbol']),
          Yii::app()->format->formatCurrency($row1['credit'], $row1['symbol']),
          Yii::app()->format->formatNumber($row1['ratevalue']),
          $row1['detailnote']
        ));
      }
      $this->pdf->row(array(
        '',
        'TOTAL',
        Yii::app()->format->formatNumber($debit),
        Yii::app()->format->formatNumber($credit),
        '',
        ''
      ));
			
      $sql1        = "select a.cashbankoutid, a.amount,a.ratevalue, a.nobuktipotong, a.detailnote,b.fullname, c.invoiceapno,d.bilyetgirono, e.currencyname,e.symbol
				from cashbankoutdetail a
				left join addressbook b on b.addressbookid = a.addressbookid
				left join invoiceap c on c.invoiceapid = a.invoiceapid
				left join cheque d on d.chequeid = a.chequeid
				left join currency e on e.currencyid = a.currencyid
				where a.cashbankoutid = '" . $row['cashbankoutid'] . "'
				order by cashbankoutid asc ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
			$totalamount =0;
			$this->pdf->setFont('Arial', 'B', 8);
			$this->pdf->text(10,$this->pdf->gety()+20,'DETAIL');
      $this->pdf->sety($this->pdf->gety() + 25);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
				'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        40,
        20,
        20,
        25,
        30,
        15,
        35,
      ));
      $this->pdf->colheader = array(
        'No',
        'Supplier',
        'No Faktur',
        'No Cek',
        'No bukti pot',
        'Nominal',
        'Kurs',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->coldetailalign = array(
        'C',
        'L',
        'C',
        'C',
        'C',
        'L',
        'L',
        'R',
        
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i      = $i + 1;
        //$debit  = $debit + ($row1['debit'] * $row1['ratevalue']);
        //$credit = $credit + ($row1['credit'] * $row1['ratevalue']);
        $this->pdf->row(array(
          $i,
          $row1['fullname'],
					 $row1['invoiceapno'],
					 $row1['bilyetgirono'],
					 $row1['nobuktipotong'],
          Yii::app()->format->formatCurrency($row1['amount'], $row1['symbol']),
					$row1['ratevalue'],
					$row1['detailnote']
        ));
				$totalamount += $row1['amount'];
      }
			$this->pdf->setFont('Arial', 'B', 8);
			$this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->row(array(
        '',
        '',
        '',
				'',
        'Jumlah',
        Yii::app()->format->formatCurrency($totalamount, $row1['symbol']),
				'','',
      ));
			$sql1        = "select b.accountcode,b.accountname, a.debit,a.credit,c.symbol,a.itemnote,a.ratevalue
							from cashbankoutacc a
							left join account b on b.accountid = a.accountid
							left join currency c on c.currencyid = a.currencyid
							where a.cashbankoutid = '" . $row['cashbankoutid'] . "'
							order by cashbankoutaccid ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
			 $this->pdf->setFont('Arial', 'B', 8);
			$this->pdf->text(10,$this->pdf->gety()+20,'TAMBAHAN');
      $this->pdf->sety($this->pdf->gety() + 22);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        70,
        30,
        30,
        15,
        45
      ));
      $this->pdf->colheader = array(
        'No',
        'Account',
        'Debit',
        'Credit',
        'Rate',
        'Detail Note'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->coldetailalign = array(
        'C',
        'L',
        'R',
        'R',
        'R',
        'L'
      );
      $i                         = 0;
			$debit = 0;
			$credit = 0;
      foreach ($dataReader1 as $row1) {
        $i      = $i + 1;
        $debit  = $debit + ($row1['debit'] * $row1['ratevalue']);
        $credit = $credit + ($row1['credit'] * $row1['ratevalue']);
        $this->pdf->row(array(
          $i,
          $row1['accountcode'] . ' ' . $row1['accountname'],
          Yii::app()->format->formatCurrency($row1['debit'], $row1['symbol']),
          Yii::app()->format->formatCurrency($row1['credit'], $row1['symbol']),
          Yii::app()->format->formatNumber($row1['ratevalue']),
          $row1['itemnote']
        ));
      }
			$this->pdf->row(array(
        '',
        'TOTAL',
        Yii::app()->format->formatCurrency($debit,$row1['symbol']),
        Yii::app()->format->formatCurrency($credit,$row1['symbol']),
        '',
        ''
      ));
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->border = false;
      $this->pdf->setwidths(array(
        20,
        175
      ));
      $this->pdf->row(array(
        'Note',
        $row['headernote']
      ));
      $this->pdf->text(10, $this->pdf->gety() + 20, '');
      $this->pdf->text(10, $this->pdf->gety() + 40, '_____________ ');
			$this->pdf->text(10, $this->pdf->gety() + 44, '      D.K / M.K');
			$this->pdf->text(50, $this->pdf->gety() + 20, 'Dibuat Oleh');
      $this->pdf->text(50, $this->pdf->gety() + 40, '_____________');
      $this->pdf->text(50, $this->pdf->gety() + 44, ' (                     )');
			$this->pdf->text(90, $this->pdf->gety() + 20, 'Diperiksa Oleh');
      $this->pdf->text(90, $this->pdf->gety() + 40, '_____________');
      $this->pdf->text(90, $this->pdf->gety() + 44, ' (                     )');
			$this->pdf->text(130, $this->pdf->gety() + 20, 'Disetujui Oleh');
      $this->pdf->text(130, $this->pdf->gety() + 40, '_____________');
      $this->pdf->text(130, $this->pdf->gety() + 44, ' (                     )');
			$this->pdf->text(170, $this->pdf->gety() + 20, 'Diterima Oleh');
      $this->pdf->text(170, $this->pdf->gety() + 40, '_____________');
      $this->pdf->text(170, $this->pdf->gety() + 44, ' (                     )');
      $this->pdf->CheckNewPage(10);
    }
    $this->pdf->Output();
  }
	public function actionDownxls() {
    $this->menuname = 'cashbankout';
    parent::actionDownxls();
		$cashbankoutid 		= GetSearchText(array('POST','GET','Q'),'cashbankoutid');
		$reqpayno 		= GetSearchText(array('POST','GET','Q'),'reqpayno');
		$cashbankoutno 		= GetSearchText(array('POST','GET','Q'),'cashbankoutno');
		$cashbankoutdate 		= GetSearchText(array('POST','GET','Q'),'cashbankoutdate');
		$sql = "select a.cashbankoutid, a.cashbankoutno, a.cashbankoutdate, d.accountname, b.reqpayno, a.headernote,c.companyid,
ba.accountcode as accountcodejurnal,ba.accountname as accountnamejurnal, aa.debit as debitjurnal,aa.credit  as creditjurnal,ca.symbol as symboljurnal,aa.detailnote as detailjurnal,aa.ratevalue as ratejurnal,	
ab.amount as amountdetail,ab.ratevalue as ratedetail, ab.nobuktipotong, ab.detailnote,bb.fullname, 
cb.invoiceapno,db.bilyetgirono, eb.currencyname,eb.symbol as symboldetail, 
bc.accountcode as accountcodetambahan,bc.accountname as accountnametambahan, ac.debit as debittambahan,ac.credit as credittambahan,cc.symbol as symboltambahan,ac.itemnote as detailtambahan,ac.ratevalue as ratetambahan				
						from cashbankout a
						left join account d on d.accountid = a.accountid
						left join reqpay b on b.reqpayid = a.reqpayid
						join plant c on c.plantid = a.plantid
							left join  cashbankoutjurnal aa on aa.cashbankoutid = a.cashbankoutid
							left join account ba on ba.accountid = aa.accountid
							left join currency ca on ca.currencyid = aa.currencyid
				left join cashbankoutdetail ab on ab.cashbankoutid = a.cashbankoutid
				left join addressbook bb on bb.addressbookid = ab.addressbookid
				left join invoiceap cb on cb.invoiceapid = ab.invoiceapid
				left join cheque db on db.chequeid = ab.chequeid
				left join currency eb on eb.currencyid = ab.currencyid
				left join cashbankoutacc ac on ac.cashbankoutid = a.cashbankoutid
							left join account bc on bc.accountid = ac.accountid
							left join currency cc on cc.currencyid = ac.currencyid 
			";    
		$sql .= " where coalesce(a.cashbankoutid,'') like '%".$cashbankoutid."%' 
			and coalesce(b.reqpayno,'') like '%".$reqpayno."%'
			and coalesce(a.cashbankoutno,'') like '%".$cashbankoutno."%'
			and coalesce(d.accountname,'') like '%".$accountname."%'
			and coalesce(a.cashbankoutdate,'') like '%".$cashbankoutdate."%'
			";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.cashbankoutid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 2;$nourut=0;$oldbom='';
		foreach ($dataReader as $row) {
			if ($oldbom != $row['cashbankoutid']) {
				$nourut+=1;
				$oldbom = $row['cashbankoutid'];
			}
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i-1, $nourut)
				->setCellValueByColumnAndRow(1, $i, $row['cashbankoutid'])
				->setCellValueByColumnAndRow(2, $i, $row['accountname'])
				->setCellValueByColumnAndRow(2, $i, $row['cashbankoutno'])
				->setCellValueByColumnAndRow(3, $i, date(Yii::app()->params['dateviewfromdb'], strtotime($row['cashbankoutdate'])))
				->setCellValueByColumnAndRow(4, $i, $row['reqpayno'])
				->setCellValueByColumnAndRow(5, $i, $row['accountnamejurnal'])
				->setCellValueByColumnAndRow(6, $i, $row['debitjurnal'])
				->setCellValueByColumnAndRow(7, $i, $row['creditjurnal'])
				->setCellValueByColumnAndRow(8, $i, $row['ratejurnal'])
				->setCellValueByColumnAndRow(9, $i, $row['detailjurnal'])
				->setCellValueByColumnAndRow(10, $i, $row['supplier'])
				->setCellValueByColumnAndRow(11, $i, $row['currencyrate'])
				->setCellValueByColumnAndRow(12, $i, $row['isexport'])
				->setCellValueByColumnAndRow(13, $i, $row['issample'])
				->setCellValueByColumnAndRow(14, $i, $row['isavalan'])
				->setCellValueByColumnAndRow(15, $i, $row['headernote'])
				->setCellValueByColumnAndRow(16, $i, $row['taxcode'])
				->setCellValueByColumnAndRow(17, $i, $row['productname'])
				->setCellValueByColumnAndRow(18, $i, Yii::app()->format->formatNumber($row['qtystock']))
				->setCellValueByColumnAndRow(19, $i, Yii::app()->format->formatNumber($row['qty']))
				->setCellValueByColumnAndRow(20, $i, Yii::app()->format->formatNumber($row['giqty']))
				->setCellValueByColumnAndRow(21, $i, Yii::app()->format->formatNumber($row['sppqty']))
				->setCellValueByColumnAndRow(22, $i, Yii::app()->format->formatNumber($row['opqty']))
				->setCellValueByColumnAndRow(23, $i, $row['uomcode'])
				->setCellValueByColumnAndRow(24, $i, Yii::app()->format->formatNumber($row['qty2']))
				->setCellValueByColumnAndRow(25, $i, $row['uom2code'])
				->setCellValueByColumnAndRow(26, $i, ($price==1)?$row['price']:'-')
				->setCellValueByColumnAndRow(27, $i, $row['toleransi'])
				->setCellValueByColumnAndRow(28, $i, date(Yii::app()->params['dateviewfromdb'], strtotime($row['delvdate'])))
				->setCellValueByColumnAndRow(29, $i, $row['bomversion'])
				->setCellValueByColumnAndRow(30, $i, $row['sloccode'])
				->setCellValueByColumnAndRow(31, $i, $row['itemnote'])
			;
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
  }
}