<?php
class CashbankinController extends Controller {
  public $menuname = 'cashbankin';
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
	public function actionIndexacc() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchacc();
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
	public function actionIndexjurnal() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchjurnal();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
    $id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'cashbankinid' => $id
		));
  }
  public function search() {
    header('Content-Type: application/json');
    $cashbankinid 	= GetSearchText(array('POST','GET'),'cashbankinid');
    $companycode 	= GetSearchText(array('POST','Q'),'companycode');
    $cashbankindate 	= GetSearchText(array('POST','Q'),'cashbankindate');
    $customer 	= GetSearchText(array('POST','Q'),'customer');
    $accountname 	= GetSearchText(array('POST','Q'),'accountname');
    $plantcode 	= GetSearchText(array('POST','Q'),'plantcode');
    $cashbankinno 	= GetSearchText(array('POST','Q'),'cashbankinno');
    $recordstatus 	= GetSearchText(array('POST','Q'),'recordstatus');
    $invoicearno 	= GetSearchText(array('POST','Q'),'invoicearno');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','cashbankinid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset       	= ($page - 1) * $rows;
    $result       	= array();
    $row          	= array();

		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('cashbankin t')
      ->leftjoin('company b', 'b.companyid = t.companyid')
      ->leftjoin('account c', 'c.accountid = t.accountid')
      ->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
			->where("
				((coalesce(b.companycode,'') like :companycode) and 
				(coalesce(t.cashbankindate,'') like :cashbankindate) and 
				(coalesce(d.fullname,'') like :customer) and 
				(coalesce(c.accountname,'') like :accountname) and 
				(coalesce(t.cashbankinno,'') like :cashbankinno))
					and t.recordstatus in (".getUserRecordStatus('listcbout').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					(($invoicearno != '%%')?" and t.cashbankinid in (
					select distinct z.cashbankinid
					from cashbankindetail z 
					join invoicear za on za.invoicearid = z.invoicearid 
					where za.invoicearno like '%".$invoicearno."%'
					)					
					":'').
					"
					and t.companyid in (".getUserObjectValues('company').")
					", array(
					':companycode' => '%' . $companycode . '%',
					':cashbankindate' => '%' . $cashbankindate . '%',
					':customer' => '%' . $customer . '%',
					':accountname' => '%' . $accountname . '%',
					':cashbankinno' => '%' . $cashbankinno . '%'
				))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,b.companyid,b.companycode,c.accountname,d.fullname')
			->from('cashbankin t')
      ->leftjoin('company b', 'b.companyid = t.companyid')
      ->leftjoin('account c', 'c.accountid = t.accountid')
      ->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
			->where("
        ((coalesce(b.companycode,'') like :companycode) and 
        (coalesce(t.cashbankindate,'') like :cashbankindate) and 
        (coalesce(d.fullname,'') like :customer) and 
        (coalesce(c.accountname,'') like :accountname) and 
        (coalesce(t.cashbankinno,'') like :cashbankinno))
				and t.recordstatus in (".getUserRecordStatus('listcbout').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					(($invoicearno != '%%')?" and t.cashbankinid in (
					select distinct z.cashbankinid
					from cashbankindetail z 
					join invoicear za on za.invoicearid = z.invoicearid 
					where za.invoicearno like '%".$invoicearno."%'
					)					
					":'').
					"
				and t.companyid in (".getUserObjectValues('company').")
					", array(
					':companycode' => '%' . $companycode . '%',
					':accountname' => '%' . $accountname . '%',
					':cashbankindate' => '%' . $cashbankindate . '%',
					':customer' => '%' . $customer . '%',
					':cashbankinno' => '%' . $cashbankinno . '%'
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cashbankinid' => $data['cashbankinid'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'cashbankindate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['cashbankindate'])),
        'cashbankinno' => $data['cashbankinno'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'cashbankindetailid';
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
			->from('cashbankindetail t')
			->leftjoin('invoicear b', 'b.invoicearid=t.invoicearid')
			->leftjoin('currency c', 'c.currencyid=t.currencyid')
			->leftjoin('notagir d', 'd.notagirid=t.notagirid')
			->where("(cashbankinid = :cashbankinid)", array(
      ':cashbankinid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
			->select('t.*,b.invoicearno,c.currencyname,c.symbol,d.notagirno')
			->from('cashbankindetail t')
			->leftjoin('invoicear b', 'b.invoicearid=t.invoicearid')
			->leftjoin('currency c', 'c.currencyid=t.currencyid')
			->leftjoin('notagir d', 'd.notagirid=t.notagirid')
			->where("(cashbankinid = :cashbankinid)", array(
      ':cashbankinid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'cashbankindetailid' => $data['cashbankindetailid'],
        'cashbankinid' => $data['cashbankinid'],
        'invoicearid' => $data['invoicearid'],
        'invoicearno' => $data['invoicearno'],
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'ratevalue' => Yii::app()->format->formatNumber($data['ratevalue']),
        'symbol' => $data['symbol'],
        'notagirid' => $data['notagirid'],
        'notagirno' => $data['notagirno'],
        'detailnote' => $data['detailnote']
      );
			$symbol = $data['symbol'];
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		$sql = "select sum(amount) as amount from cashbankindetail where cashbankinid = ".$id;
		$cmd = Yii::app()->db->createCommand($sql)->queryRow();
		$footer[] = array(
      'invoicearno' => 'Total',
			'symbol' => $symbol,
      'amount' => Yii::app()->format->formatNumber($cmd['amount']),
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'cashbankinaccid';
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
			->from('cashbankinacc t')
			->leftjoin('account b','b.accountid=t.accountid')
			->leftjoin('currency c', 'c.currencyid=t.currencyid')
			->where("(cashbankinid = :cashbankinid)", array(
				':cashbankinid' => $id
			))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,b.accountname,c.currencyname,c.symbol')
			->from('cashbankinacc t')
			->leftjoin('account b','b.accountid=t.accountid')
			->leftjoin('currency c', 'c.currencyid=t.currencyid')
			->where("(cashbankinid = :cashbankinid)", array(
				':cashbankinid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'cashbankinaccid' => $data['cashbankinaccid'],
        'cashbankinid' => $data['cashbankinid'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'debit' => Yii::app()->format->formatNumber($data['debit']),
        'credit' => Yii::app()->format->formatNumber($data['credit']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'ratevalue' => Yii::app()->format->formatNumber($data['ratevalue']),
        'symbol' => $data['symbol'],
        'itemnote' => $data['itemnote']
      );
			$symbol = $data['symbol'];
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		$sql = "select sum(debit) as debit, sum(credit) as credit from cashbankinacc where cashbankinid = ".$id;
		$cmd = Yii::app()->db->createCommand($sql)->queryRow();
		$footer[] = array(
      'accountid' => 'Total',
			'symbol' => $symbol,
      'debit' => Yii::app()->format->formatNumber($cmd['debit']),
      'credit' => Yii::app()->format->formatNumber($cmd['credit']),
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'cashbankinjurnalid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
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
      ->from('cashbankinjurnal t')->leftjoin('account a', 'a.accountid = t.accountid')
      ->leftjoin('currency b', 'b.currencyid = t.currencyid')
      ->leftjoin('company c', 'c.companyid = a.companyid')
      ->where("(cashbankinid = :cashbankinid) and t.accountid > 0", array(
      ':cashbankinid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.accountcode,a.accountname,b.currencyname,c.companyname,b.symbol')->from('cashbankinjurnal t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->leftjoin('company c', 'c.companyid = a.companyid')->where("(cashbankinid = :cashbankinid) and t.accountid > 0", array(
      ':cashbankinid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'cashbankinjurnalid' => $data['cashbankinjurnalid'],
        'cashbankinid' => $data['cashbankinid'],
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
		$sql = "select sum(debit) as debit, sum(credit) as credit from cashbankinjurnal where cashbankinid = ".$id;
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
		$sql     = 'call Modifcashbankin(:vid,:vcompanyid,:vcashbankindate,:vaccountid,:vaddressbookid,:vheadernote,:vdatauser)';
		$command = $connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vcompanyid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vcashbankindate', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vaccountid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-cashbankin"]["name"]);
		if (move_uploaded_file($_FILES["file-cashbankin"]["tmp_name"], $target_file)) {
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
					$abid = Yii::app()->db->createCommand("select cashbankinid from cashbankin 
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
			$this->ModifyData($connection,array((isset($_POST['cashbankin-cashbankinid'])?$_POST['cashbankin-cashbankinid']:''),
				$_POST['cashbankin-companyid'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['cashbankin-cashbankindate'])),
				$_POST['cashbankin-accountid'],
				$_POST['cashbankin-addressbookid'],
				$_POST['cashbankin-headernote']
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
			$sql     = 'call Insertcashbankindetail(:vcashbankinid,:vnotagirid,:vinvoicearid,:vamount,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatecashbankindetail(:vid,:vnotagirid,:vcashbankinid,:vinvoicearid,:vamount,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcashbankinid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vnotagirid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vinvoicearid', $arraydata[3], PDO::PARAM_STR);
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
			$this->ModifyDataDetail($connection,array((isset($_POST['cashbankindetailid'])?$_POST['cashbankindetailid']:''),$_POST['cashbankinid'],
				$_POST['notagirid'],$_POST['invoicearid'],$_POST['amount'],$_POST['currencyid'],$_POST['ratevalue'],$_POST['detailnote'],
			));
			$transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
  private function ModifyDataAcc($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertcashbankinacc(:vcashbankinid,:vaccountid,:vdebit,:vcredit,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatecashbankinacc(:vid,:vcashbankinid,:vaccountid,:vdebit,:vcredit,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcashbankinid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vaccountid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vdebit', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vcredit', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vratevalue', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vdetailnote', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSaveAcc() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataAcc($connection,array((isset($_POST['cashbankinaccid'])?$_POST['cashbankinaccid']:''),
				$_POST['cashbankinid'],$_POST['accountid'],
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
				$sql     = 'call Purgecashbankin(:vid,:vdatauser)';
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
        $sql     = 'call Purgecashbankindetail(:vid,:vdatauser)';
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
        $sql     = 'call Purgecashbankintax(:vid,:vdatauser)';
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
        $sql     = 'call Purgecashbankinacc(:vid,:vdatauser)';
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
        $sql     = 'call RejectCashBankIn(:vid,:vdatauser)';
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
        $sql     = 'call Approvecashbankin(:vid,:vdatauser)';
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
  public function actionGenerateinvar() {
		$sql = "select (ifnull(a.total,0) - ifnull(a.cashbankamount,0)) as amount,b.currencyid,b.ratevalue,a.headernote
			from invoicear a
			left join invoiceardetail b on b.invoicearid=a.invoicearid
			where a.invoicearid = ".$_POST['invoicearid']." 
			limit 1";
		$data = Yii::app()->db->createCommand($sql)->queryRow();
    if (Yii::app()->request->isAjaxRequest) {
      echo CJSON::encode(array(
        'amount' => $data['amount'],
        'currencyid' => $data['currencyid'],
        'ratevalue' => $data['ratevalue'],
        'detailnote' => $data['headernote'],
      ));
      Yii::app()->end();
    }
  }
	public function actionGenerateNotagir() {
    $connection  = Yii::app()->db;
    try {
      $sql     = "select a.invoicearid,getamountbynotagir(a.notagirid)*-1 as nilai,b.currencyid,GetKurs(a.notagirdate, b.currencyid) as ratevalue,concat(coalesce(a.headernote,''),' ',b.detailnote) as headernote 
        from notagir a 
        left join notagirdetail b on b.notagirid = a.notagirid
        where a.notagirid = ".$_POST['vid'];
      $command = $connection->createCommand($sql);
      $data = $command->queryRow();
      echo CJSON::encode(array(
        'invoicearid' => $data['invoicearid'],
        'currencyid' => $data['currencyid'],
        'amount' => $data['nilai'],
        'ratevalue' => $data['ratevalue'],
        'detailnote' => $data['headernote'],
      ));
      Yii::app()->end();
    }
    catch (CDbException $e) {
      GetMessage(true,implode(" ",$e->errorInfo));
    }
  }
	public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select a.cashbankinid,
						ifnull(b.companyname,'-')as company,
						ifnull(a.cashbankinno,'-')as cashbankinno,
						ifnull(d.fullname,'-')as fullname,
						a.cashbankindate,e.accountname,
						ifnull(a.headernote,'-')as headernote,a.recordstatus,b.companyid
						from cashbankin a
						left join company b on b.companyid = a.companyid
						left join addressbook d on d.addressbookid = a.addressbookid
						join account e on e.accountid = a.accountid
						";
		$cashbankinid = filter_input(INPUT_GET,'cashbankinid');
		$companyname = filter_input(INPUT_GET,'companyname');
		$cashbankinno = filter_input(INPUT_GET,'cashbankinno');
		$fullname = filter_input(INPUT_GET,'fullname');
		$cashbankindate = filter_input(INPUT_GET,'cashbankindate');
		$sql .= " where coalesce(a.cashbankinid,'') like '%".$cashbankinid."%' 
			and coalesce(b.companyname,'') like '%".$companyname."%'
			and coalesce(a.cashbankinno,'') like '%".$cashbankinno."%'
			and coalesce(d.fullname,'') like '%".$fullname."%'
			and coalesce(a.cashbankindate,'') like '%".$cashbankindate."%'
			";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.cashbankinid in (" . $_GET['id'] . ")";
    }
    $totalbiaya       = 0;
    $debit            = 0;
    $credit           = 0;
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('cashbankin');
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->AddPage('P');
    $this->pdf->setFont('Arial', 'B', 9);
    $this->pdf->AliasNBPages();
    foreach ($dataReader as $row) {
      //$this->pdf->SetFontSize(10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No Doc ');
      $this->pdf->text(35, $this->pdf->gety() + 5, ': ' . $row['cashbankinno']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Tgl Doc ');
      $this->pdf->text(35, $this->pdf->gety() + 10, ': ' . $row['cashbankindate']);
			$this->pdf->text(90, $this->pdf->gety() + 5, 'Customer ');
      $this->pdf->text(110, $this->pdf->gety() + 5, ': ' . $row['fullname']);
			$this->pdf->text(90, $this->pdf->gety() + 10, 'Akun ');
      $this->pdf->text(110, $this->pdf->gety() + 10, ': ' . $row['accountname']);
      $sql1        = "select b.accountcode,b.accountname, a.debit,a.credit,c.symbol,a.detailnote,a.ratevalue
							from cashbankinjurnal a
							left join account b on b.accountid = a.accountid
							left join currency c on c.currencyid = a.currencyid
							where a.cashbankinid = '" . $row['cashbankinid'] . "'
							order by cashbankinjurnalid ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
			 $this->pdf->setFont('Arial', 'B', 8);
			$this->pdf->text(10,$this->pdf->gety()+20,'JURNAL');
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
        40
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
			if ($dataReader1 != null) {
      $this->pdf->row(array(
        '',
        'TOTAL',
        Yii::app()->format->formatCurrency($debit, $row1['symbol']),
        Yii::app()->format->formatCurrency($credit, $row1['symbol']),
        '',
        ''
      ));
			}
			$sql1        = "select da.notagirno,ifnull(aa.amount,0) as biaya, ca.currencyname, aa.ratevalue, aa.detailnote, ba.invoicearno,ca.symbol 
							from cashbankindetail aa
							left join invoicear ba on ba.invoicearid = aa.invoicearid
							left join currency ca on ca.currencyid = aa.currencyid
							left join notagir da on da.notagirid = aa.notagirid
							where aa.cashbankinid = '" . $row['cashbankinid'] . "'
							order by cashbankindetailid ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
			 $this->pdf->setFont('Arial', 'B', 8);
			$this->pdf->text(10,$this->pdf->gety()+10,'DETAIL');
      $this->pdf->sety($this->pdf->gety() + 12);
      $this->pdf->colalign = array(
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
        25,
        25,
        35,
        25,
        25,
        45
      ));
      $this->pdf->colheader = array(
        'No',
        'No Nota Retur',
        'No Faktur',
        'Biaya',
        'Currency',
        'Rate',
        'Detail Note'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->coldetailalign = array(
        'C',
        'L',
        'L',
        'R',
        'R',
        'R',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i      = $i + 1;
			$totalbiaya += $row1['biaya'];
        $this->pdf->row(array(
          $i,
          $row1['notagirno'],
          $row1['invoicearno'],
          Yii::app()->format->formatCurrency($row1['biaya'], $row1['symbol']),
          $row1['currencyname'],
          Yii::app()->format->formatCurrency($row1['ratevalue']),
          $row1['detailnote']
        ));
      }
			$this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->row(array(
        '',
        '',
        'TOTAL',
       Yii::app()->format->formatCurrency($totalbiaya, $row1['symbol']),
       '',
        '',
        ''
      ));
			 $sql1        = "select b.accountcode,b.accountname, a.debit,a.credit,c.symbol,a.itemnote,a.ratevalue
							from cashbankinacc a
							left join account b on b.accountid = a.accountid
							left join currency c on c.currencyid = a.currencyid
							where a.cashbankinid = '" . $row['cashbankinid'] . "'
							order by cashbankinaccid ";
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
        40
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
        Yii::app()->format->formatCurrency($debit, $row1['symbol']),
        Yii::app()->format->formatCurrency($credit, $row1['symbol']),
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
        'Note :',
        $row['headernote']
      ));
      $this->pdf->text(20, $this->pdf->gety() + 20, 'Approved By');
      $this->pdf->text(170, $this->pdf->gety() + 20, 'Proposed By');
      $this->pdf->text(20, $this->pdf->gety() + 40, '_____________ ');
      $this->pdf->text(170, $this->pdf->gety() + 40, '_____________');
      $this->pdf->CheckNewPage(10);
    }
    $this->pdf->Output();
  }
}