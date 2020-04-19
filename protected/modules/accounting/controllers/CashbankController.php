<?php
class CashbankController extends Controller {
  public $menuname = 'cashbank';
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
  public function actionIndextax() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchtax();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
    $id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'cashbankid' => $id
		));
  }
  public function search() {
    header('Content-Type: application/json');
    $cashbankid = GetSearchText(array('POST','GET'),'cashbankid',0,'int');
    $companycode = GetSearchText(array('POST','Q'),'companycode');
    $cashbankdate = GetSearchText(array('POST','Q'),'cashbankdate');
    $addressbook = GetSearchText(array('POST','Q'),'addressbook');
    $cashbankno = GetSearchText(array('POST','Q'),'cashbankno');
    $accountname = GetSearchText(array('POST','Q'),'accountname');
    $recordstatus = GetSearchText(array('POST','Q'),'recordstatus');
    $headernote = GetSearchText(array('POST','Q'),'headernote');
    $page         	= isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows         	= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort         	= isset($_POST['sort']) ? strval($_POST['sort']) : 'cashbankid';
    $order        	= isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset       	= ($page - 1) * $rows;
    $result       	= array();
    $row          	= array();

    if (isset($_GET['trxplant'])) {
  		$cmd = Yii::app()->db->createCommand()
  			->select('count(1) as total')
  			->from('cashbank t')
        ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
        ->leftjoin('cheque b', 'b.chequeid = t.chequeid')
        ->leftjoin('account c', 'c.accountid = t.accountid')
  			->where("
  				((coalesce(t.companycode,'') like :companycode) or 
  				(coalesce(t.cashbankdate,'') like :cashbankdate) or 
  				(coalesce(a.fullname,'') like :addressbook) or 
  				(coalesce(t.cashbankid,'') like :cashbankid) or 
  				(coalesce(c.accountname,'') like :accountname) or 
  				(coalesce(t.headernote,'') like :headernote) or 
  				(coalesce(t.cashbankno,'') like :cashbankno))
  					and t.recordstatus in (".getUserRecordStatus('listcb').")
  					and t.companyid in (".getUserObjectValues('company').")
            and t.plantid = ".$plantid."
  					", array(
  					':companycode' => '%' . $companycode . '%',
  					':cashbankdate' => '%' . $cashbankdate . '%',
  					':cashbankid' => '%' . $cashbankid . '%',
  					':addressbook' => '%' . $addressbook . '%',
  					':accountname' => '%' . $accountname . '%',
  					':headernote' => '%' . $headernote . '%',
  					':cashbankno' => '%' . $cashbankno . '%'
  				))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()
        ->select('count(1) as total')
        ->from('cashbank t')
        ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
        ->leftjoin('cheque b', 'b.chequeid = t.chequeid')
        ->leftjoin('account c', 'c.accountid = t.accountid')
        ->where("
          ((coalesce(t.companycode,'') like :companycode) and 
          (coalesce(t.cashbankdate,'') like :cashbankdate) and 
          (coalesce(a.fullname,'') like :addressbook) and 
          (coalesce(t.cashbankid,'') like :cashbankid) and 
          (coalesce(c.accountname,'') like :accountname) and 
          (coalesce(t.headernote,'') like :headernote) and 
          (coalesce(t.cashbankno,'') like :cashbankno))
            and t.recordstatus in (".getUserRecordStatus('listcb').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					"
            and t.companyid in (".getUserObjectValues('company').")
            ", array(
            ':companycode' => '%' . $companycode . '%',
            ':cashbankdate' => '%' . $cashbankdate . '%',
            ':cashbankid' => '%' . $cashbankid . '%',
						':accountname' => '%' . $accountname . '%',
            ':addressbook' => '%' . $addressbook . '%',
            ':headernote' => '%' . $headernote . '%',
            ':cashbankno' => '%' . $cashbankno . '%'
          ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['trxplant'])) {
  		$cmd = Yii::app()->db->createCommand()
  			->select('t.*,a.fullname,bilyetgirono,c.symbol')
  			->from('cashbank t')
        ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
        ->leftjoin('cheque b', 'b.chequeid = t.chequeid')
        ->leftjoin('currency c', 'c.currencyid = t.currencyid')
        ->leftjoin('account d', 'd.accountid = t.accountid')
  			->where("
          ((coalesce(t.companycode,'') like :companycode) or 
          (coalesce(t.cashbankdate,'') like :cashbankdate) or 
          (coalesce(a.fullname,'') like :addressbook) or 
          (coalesce(t.cashbankid,'') like :cashbankid) or 
          (coalesce(d.accountname,'') like :accountname) or 
          (coalesce(t.headernote,'') like :headernote) or 
          (coalesce(t.cashbankno,'') like :cashbankno))
  				and t.recordstatus in (".getUserRecordStatus('listcb').")
  				and t.companyid in (".getUserObjectValues('company').")
            and t.plantid = ".$plantid."
  					", array(
  					':companycode' => '%' . $companycode . '%',
  					':cashbankdate' => '%' . $cashbankdate . '%',
  					':cashbankid' => '%' . $cashbankid . '%',
  					':accountname' => '%' . $accountname . '%',
  					':addressbook' => '%' . $addressbook . '%',
  					':headernote' => '%' . $headernote . '%',
  					':cashbankno' => '%' . $cashbankno . '%'
  				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()
        ->select('t.*,a.fullname,bilyetgirono,c.symbol')
        ->from('cashbank t')
        ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
        ->leftjoin('cheque b', 'b.chequeid = t.chequeid')
        ->leftjoin('currency c', 'c.currencyid = t.currencyid')
        ->leftjoin('account d', 'd.accountid = t.accountid')
        ->where("
          ((coalesce(t.companycode,'') like :companycode) and 
          (coalesce(t.cashbankdate,'') like :cashbankdate) and 
          (coalesce(t.cashbankid,'') like :cashbankid) and 
          (coalesce(a.fullname,'') like :addressbook) and 
          (coalesce(d.accountname,'') like :accountname) and 
          (coalesce(t.headernote,'') like :headernote) and 
          (coalesce(t.cashbankno,'') like :cashbankno))
          and t.recordstatus in (".getUserRecordStatus('listcb').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					"
          and t.companyid in (".getUserObjectValues('company').")
            ", array(
            ':companycode' => '%' . $companycode . '%',
            ':cashbankdate' => '%' . $cashbankdate . '%',
            ':cashbankid' => '%' . $cashbankid . '%',
            ':accountname' => '%' . $accountname . '%',
            ':addressbook' => '%' . $addressbook . '%',
            ':headernote' => '%' . $headernote . '%',
            ':cashbankno' => '%' . $cashbankno . '%'
          ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'cashbankid' => $data['cashbankid'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'cashbankdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['cashbankdate'])),
        'cashbankno' => $data['cashbankno'],
        'isin' => $data['isin'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'receiptno' => $data['receiptno'],
        'chequeid' => $data['chequeid'],
        'bilyetgirono' => $data['bilyetgirono'],
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'symbol' => $data['symbol'],
        'ratevalue' => Yii::app()->format->formatNumber($data['ratevalue']),
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'cashbankdetailid';
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cashbankdetail t')->where("(cashbankid = :cashbankid)", array(
      ':cashbankid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*')->from('cashbankdetail t')->where("(cashbankid = :cashbankid)", array(
      ':cashbankid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'cashbankdetailid' => $data['cashbankdetailid'],
        'cashbankid' => $data['cashbankid'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
				'ratevalue' => Yii::app()->format->formatNumber($data['ratevalue']),
        'symbol' => $data['symbol'],
        'detailnote' => $data['detailnote']
      );
			$symbol = $data['symbol'];
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		$sql = "select sum(amount) as amount from cashbankdetail where cashbankid = ".$id;
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
	public function actionsearchtax() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'cashbanktaxid';
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cashbanktax t')->leftjoin('tax a', 'a.taxid = t.taxid')->where("(cashbankid = :cashbankid)", array(
      ':cashbankid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.taxcode,a.taxvalue,a.description')->from('cashbanktax t')->leftjoin('tax a', 'a.taxid = t.taxid')->where("(cashbankid = :cashbankid)", array(
      ':cashbankid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'cashbanktaxid' => $data['cashbanktaxid'],
        'cashbankid' => $data['cashbankid'],
        'taxid' => $data['taxid'],
        'taxcode' => $data['taxcode'],
        'taxvalue' => Yii::app()->format->formatNumber($data['taxvalue']),
        'description' => $data['description'],
        'nobuktipotong' => $data['nobuktipotong']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		$sql     = 'call Modifcashbank(:vid,:vcashbankdate,:vcompanyid,:visin,:vaddressbookid,:vaccountid,:vreceiptno,:vchequeid,
			:vamount,:vcurrencyid,:vratevalue,:vheadernote,:vdatauser)';
		$command = $connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vcashbankdate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vcompanyid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':visin', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vaccountid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vreceiptno', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vchequeid', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vamount', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vratevalue', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-cashbank"]["name"]);
		if (move_uploaded_file($_FILES["file-cashbank"]["tmp_name"], $target_file)) {
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
					$abid = Yii::app()->db->createCommand("select cashbankid from cashbank 
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
			$this->ModifyData($connection,array((isset($_POST['cashbank-cashbankid'])?$_POST['cashbank-cashbankid']:''),
			date(Yii::app()->params['datetodb'], strtotime($_POST['cashbank-cashbankdate'])),
				$_POST['cashbank-companyid'],
				(isset($_POST['cashbank-isin']) ? 1 : 0),
				$_POST['cashbank-addressbookid'],
				$_POST['cashbank-accountid'],
				$_POST['cashbank-receiptno'],
				$_POST['cashbank-chequeid'],
				$_POST['cashbank-amount'],
				$_POST['cashbank-currencyid'],
				$_POST['cashbank-ratevalue'],
				$_POST['cashbank-headernote']
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
			$sql     = 'call Insertcashbankdetail(:vcashbankid,:vslocid,:vaccountid,:vamount,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatecashbankdetail(:vid,:vcashbankid,:vslocid,:vaccountid,:vamount,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcashbankid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vslocid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vaccountid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vamount', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vratevalue', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vdetailnote', $arraydata[7], PDO::PARAM_STR);
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
			$this->ModifyDataDetail($connection,array((isset($_POST['cashbankdetailid'])?$_POST['cashbankdetailid']:''),$_POST['cashbankid'],
				$_POST['slocid'],$_POST['accountid'],
				$_POST['amount'],
				$_POST['currencyid'],$_POST['ratevalue'],
				$_POST['detailnote'],
			));
			$transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
  private function ModifyDataTax($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertcashbanktax(:vcashbankid,:vtaxid,:vnobuktipotong,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatecashbanktax(:vid,:vcashbankid,:vtaxid,:vnobuktipotong,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcashbankid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vtaxid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vnobuktipotong', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavetax() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataTax($connection,array((isset($_POST['cashbanktaxid'])?$_POST['cashbanktaxid']:''),
				$_POST['cashbankid'],$_POST['taxid'],$_POST['nobuktipotong']));
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
				$sql     = 'call Purgecashbank(:vid,:vdatauser)';
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
        $sql     = 'call Purgecashbankdetail(:vid,:vdatauser)';
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
        $sql     = 'call Purgecashbanktax(:vid,:vdatauser)';
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
        $sql     = 'call RejectCashBank(:vid,:vdatauser)';
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
        $sql     = 'call Approvecashbank(:vid,:vdatauser)';
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
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select a.cashbankid, a.cashbankno, a.cashbankdate, b.fullname, a.accountname,a.headernote,a.companyid,e.symbol,
						a.receiptno, a.amount, a.currencyname, a.ratevalue, c.bilyetgirono, c.bankname, c.bilyetgirovalue, d.accountcode,d.accountname 
						from cashbank a
						left join addressbook b on b.addressbookid = a.addressbookid
						left join cheque c on c.chequeid = a.chequeid
						left join account d on d.accountid = a.accountid
						left join currency e on e.currencyid = a.currencyid
						";
		$cashbankid = filter_input(INPUT_GET,'cashbankid');
		$fullname = filter_input(INPUT_GET,'fullname');
		$cashbankno = filter_input(INPUT_GET,'cashbankno');
		$bilyetgirono = filter_input(INPUT_GET,'bilyetgirono');
		$cashbankdate = filter_input(INPUT_GET,'cashbankdate');
		$sql .= " where coalesce(a.cashbankid,'') like '%".$cashbankid."%' 
			and coalesce(b.fullname,'') like '%".$fullname."%'
			and coalesce(a.cashbankno,'') like '%".$cashbankno."%'
			and coalesce(c.bilyetgirono,'') like '%".$bilyetgirono."%'
			and coalesce(a.cashbankdate,'') like '%".$cashbankdate."%'
			";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.cashbankid in (" . $_GET['id'] . ")";
    }
    $totalamount1 =0;
    $credit           = 0;
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('cashbank');
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->AddPage('P');
    $this->pdf->setFont('Arial', 'B', 10);
    $this->pdf->AliasNBPages();
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(9);
			$this->pdf->text(15, $this->pdf->gety() + 5, 'Dibayar Kepada ');
      $this->pdf->text(45, $this->pdf->gety() + 5, ': ' . $row['fullname']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'No Dokumen ');
      $this->pdf->text(45, $this->pdf->gety() + 10, ': ' . $row['cashbankno']);
      $this->pdf->text(15, $this->pdf->gety() + 15, 'Tgl Dokumen ');
      $this->pdf->text(45, $this->pdf->gety() + 15, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['cashbankdate'])));			
			$this->pdf->text(15, $this->pdf->gety() + 20, 'Akun ');
      $this->pdf->text(45, $this->pdf->gety() + 20, ': ' . $row['accountname']);
			$this->pdf->text(15, $this->pdf->gety() + 25, 'Nominal ');
      $this->pdf->text(45, $this->pdf->gety() + 25, ': ' . Yii::app()->format->formatCurrency($row['amount'], $row['symbol']));
			
      $sql1        = "select a.accountname, a.detailnote, a.amount, b.accountcode, c.symbol,a.ratevalue
				from cashbankdetail a
				left join account b on b.accountid = a.accountid
				left join currency c on c.currencyid = a.currencyid
				where a.cashbankid = '" . $row['cashbankid'] . "'
				order by detailnote asc ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
			$totalamount =0;
			$this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->sety($this->pdf->gety() + 30);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
      );
      $this->pdf->setwidths(array(
        10,
        40,
        80,
        40,
        20,
      ));
      $this->pdf->colheader = array(
        'No',
        'Dibebankan Pada',
        'Uraian',
        'Nominal',
        'Rate',
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->coldetailalign = array(
        'C',
        'L',
        'L',
        'R',
        'R'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i      = $i + 1;
        //$debit  = $debit + ($row1['debit'] * $row1['ratevalue']);
        //$credit = $credit + ($row1['credit'] * $row1['ratevalue']);
        $this->pdf->row(array(
          $i,
          $row1['accountcode'] . ' - ' . $row1['accountname'],
					 $row1['detailnote'],
          Yii::app()->format->formatCurrency($row1['amount'], $row1['symbol']),
          Yii::app()->format->formatNumber($row1['ratevalue'])
        ));
				$totalamount += $row1['amount'];
      }
			$this->pdf->setFont('Arial', 'B', 8);
			$this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->row(array(
        '',
        '',
        'Jumlah',
        Yii::app()->format->formatCurrency($totalamount, $row1['symbol'])
      ));
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->border = false;
      $this->pdf->setwidths(array(
        20,
        300
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
}