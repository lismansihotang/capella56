<?php
class InvoicearumumController extends Controller {
  public $menuname = 'invoicearumum';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
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
  public function actionGetData() {
    $id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'invoicearid' => $id
		));
  }
  public function search() {
    header('Content-Type: application/json');
		$invoicearid 	= GetSearchText(array('POST','GET'),'invoicearid');
    $companycode 	= GetSearchText(array('POST','Q'),'companycode');
    $invoiceardate 	= GetSearchText(array('POST','Q'),'invoiceardate');
    $customer 	= GetSearchText(array('POST','Q'),'customer');
    $plantcode 	= GetSearchText(array('POST','Q'),'plantcode');
    $invoicearno 	= GetSearchText(array('POST','Q'),'invoicearno');
    $invoiceartaxno 	= GetSearchText(array('POST','Q'),'invoiceartaxno');
    $recordstatus 	= GetSearchText(array('POST','Q'),'recordstatus');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','invoicearid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset       	= ($page - 1) * $rows;
    $result       	= array();
    $row          	= array();

		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('invoicear t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('cashbank b', 'b.cashbankid = t.cashbankid')
			->where("
				((coalesce(t.companycode,'') like :companycode) and 
				(coalesce(t.invoiceardate,'') like :invoiceardate) and 
				(coalesce(t.invoicearid,'') like :invoicearid) and 
				(coalesce(a.fullname,'') like :customer) and 
				(coalesce(t.plantcode,'') like :plantcode) and 
				(coalesce(t.invoicearno,'') like :invoicearno) and 
				(coalesce(t.invoiceartaxno,'') like :invoiceartaxno))
          and t.doctype = 2
          and t.soheaderid is null 
					and t.recordstatus in (".getUserRecordStatus('listinvar').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					"
					and t.companyid in (".getUserObjectValues('company').")
					", array(
					':companycode' => '%' . $companycode . '%',
					':invoiceardate' => '%' . $invoiceardate . '%',
					':invoicearid' => '%' . $invoicearid . '%',
					':customer' => '%' . $customer . '%',
					':plantcode' => '%' . $plantcode . '%',
					':invoicearno' => '%' . $invoicearno . '%',
					':invoiceartaxno' => '%' . $invoiceartaxno . '%'
				))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.fullname,b.cashbankno')
			->from('invoicear t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('cashbank b', 'b.cashbankid = t.cashbankid')
			->where("
        ((coalesce(t.companycode,'') like :companycode) and 
        (coalesce(t.invoiceardate,'') like :invoiceardate) and 
        (coalesce(t.invoicearid,'') like :invoicearid) and 
        (coalesce(a.fullname,'') like :customer) and 
        (coalesce(t.plantcode,'') like :plantcode) and 
        (coalesce(t.invoicearno,'') like :invoicearno) and 
        (coalesce(t.invoiceartaxno,'') like :invoiceartaxno))
					and t.doctype = 2
				and t.recordstatus in (".getUserRecordStatus('listinvar').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					"
				and t.companyid in (".getUserObjectValues('company').")
					", array(
					':companycode' => '%' . $companycode . '%',
					':invoiceardate' => '%' . $invoiceardate . '%',
					':invoicearid' => '%' . $invoicearid . '%',
					':customer' => '%' . $customer . '%',
					':plantcode' => '%' . $plantcode . '%',
					':invoicearno' => '%' . $invoicearno . '%',
					':invoiceartaxno' => '%' . $invoiceartaxno . '%'
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'invoicearid' => $data['invoicearid'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'invoiceardate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['invoiceardate'])),
        'invoicearno' => $data['invoicearno'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'contractno' => $data['contractno'],
        'invoiceartaxno' => $data['invoiceartaxno'],
        'taxno' => $data['taxno'],
        'addressname' => $data['addressname'],
        'paymentmethodid' => $data['paymentmethodid'],
        'duedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['duedate'])),
        'cashbankid' => $data['cashbankid'],
        'cashbankno' => $data['cashbankno'],
        'dpamount' => Yii::app()->format->formatNumber($data['dpamount']),
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'invoiceartaxid';
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('invoiceartax t')->leftjoin('tax a', 'a.taxid = t.taxid')->where("(invoicearid = :invoicearid)", array(
      ':invoicearid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.taxcode,a.taxvalue,a.description')->from('invoiceartax t')->leftjoin('tax a', 'a.taxid = t.taxid')->where("(invoicearid = :invoicearid)", array(
      ':invoicearid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'invoiceartaxid' => $data['invoiceartaxid'],
        'invoicearid' => $data['invoicearid'],
        'taxid' => $data['taxid'],
        'taxcode' => $data['taxcode'],
        'taxvalue' => Yii::app()->format->formatNumber($data['taxvalue']),
        'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'invoiceardetailid';
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
		->select('count(1) as total')->from('invoiceardetail t')
		->leftjoin('product a', 'a.productid = t.productid')
		->leftjoin('currency b', 'b.currencyid = t.currencyid')
		->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uomid')
		->leftjoin('unitofmeasure d', 'd.unitofmeasureid = t.uom2id')
		->leftjoin('unitofmeasure e', 'e.unitofmeasureid = t.uom3id')
		->leftjoin('unitofmeasure f', 'f.unitofmeasureid = t.uom4id')
		->leftjoin('sloc g', 'g.slocid = t.slocid')
		->leftjoin('materialtype h', 'h.materialtypeid = a.materialtypeid')
		->where("(invoicearid = :invoicearid)", array(
      ':invoicearid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
		->select('t.*,a.productcode,b.currencyname,b.symbol,c.uomcode,d.uomcode as uom2code,e.uomcode as uom3code,f.uomcode as uom4code,
							g.sloccode,h.materialtypecode,a.productname')
		->from('invoiceardetail t')
		->leftjoin('product a', 'a.productid = t.productid')
		->leftjoin('currency b', 'b.currencyid = t.currencyid')
		->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uomid')
		->leftjoin('unitofmeasure d', 'd.unitofmeasureid = t.uom2id')
		->leftjoin('unitofmeasure e', 'e.unitofmeasureid = t.uom3id')
		->leftjoin('unitofmeasure f', 'f.unitofmeasureid = t.uom4id')
		->leftjoin('sloc g', 'g.slocid = t.slocid')
		->leftjoin('materialtype h', 'h.materialtypeid = a.materialtypeid')
		->where("(invoicearid = :invoicearid)", array(
      ':invoicearid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'invoiceardetailid' => $data['invoiceardetailid'],
        'invoicearid' => $data['invoicearid'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
				'materialtypecode' => $data['materialtypecode'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'qty2' => Yii::app()->format->formatNumber($data['qty2']),
        'uom2id' => $data['uom2id'],
        'uom2code' => $data['uom2code'],
        'qty3' => Yii::app()->format->formatNumber($data['qty3']),
        'uom3id' => $data['uom3id'],
        'uom3code' => $data['uom3code'],
        'qty4' => Yii::app()->format->formatNumber($data['qty4']),
        'uom4id' => $data['uom4id'],
        'uom4code' => $data['uom4code'],
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'symbol' => $data['symbol'],
        'price' => Yii::app()->format->formatNumber($data['price']),
        'discount' => Yii::app()->format->formatNumber($data['discount']),
        'ratevalue' => Yii::app()->format->formatNumber($data['ratevalue']),
        'dpp' => Yii::app()->format->formatNumber($data['dpp']),
        'total' => Yii::app()->format->formatNumber($data['total']),
        'detailnote' => $data['detailnote']
      );
			$symbol = $data['symbol'];
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		$sql = "select sum(qty) as qty, sum(qty2) as qty2, sum(qty3) as qty3, sum(qty4) as qty4,
			sum(dpp) as dpp, 
			sum(total) as total
			from invoiceardetail where invoicearid = ".$id;
		$cmd = Yii::app()->db->createCommand($sql)->queryRow();
		$footer[] = array(
      'productname' => 'Total',
			'symbol' => $symbol,
      'qty' => Yii::app()->format->formatNumber($cmd['qty']),
      'qty2' => Yii::app()->format->formatNumber($cmd['qty2']),
      'qty3' => Yii::app()->format->formatNumber($cmd['qty3']),
      'qty4' => Yii::app()->format->formatNumber($cmd['qty4']),
      'dpp' => Yii::app()->format->formatNumber($cmd['dpp']),
      'total' => Yii::app()->format->formatNumber($cmd['total'])
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'invoicearjurnalid';
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('invoicearjurnal t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->leftjoin('company c', 'c.companyid = a.companyid')->where("(invoicearid = :invoicearid) and t.accountid > 0", array(
      ':invoicearid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.accountcode,a.accountname,b.currencyname,c.companyname,b.symbol')->from('invoicearjurnal t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->leftjoin('company c', 'c.companyid = a.companyid')->where("(invoicearid = :invoicearid) and t.accountid > 0", array(
      ':invoicearid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'invoicearjurnalid' => $data['invoicearjurnalid'],
        'invoicearid' => $data['invoicearid'],
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
		$sql = "select sum(debit) as debit, sum(credit) as credit from invoicearjurnal where invoicearid = ".$id;
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
		$sql     = 'call Modifinvoicearumum(:vid,:vinvoiceardate,:vplantid,:vinvoicearno,:vaddressbookid,:vcontractno,:vinvoiceartaxno,:vaddressname,:vpaymentmethodid,
			:vcashbankid,:vdpamount,:vheadernote,:vdatauser)';
		$command = $connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vinvoiceardate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vinvoicearno', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vcontractno', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vinvoiceartaxno', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vaddressname', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vpaymentmethodid', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vcashbankid', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vdpamount', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-invoicear"]["name"]);
		if (move_uploaded_file($_FILES["file-invoicear"]["tmp_name"], $target_file)) {
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
					$abid = Yii::app()->db->createCommand("select invoicearid from invoicear 
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
			$this->ModifyData($connection,array((isset($_POST['invoicearumum-invoicearid'])?$_POST['invoicearumum-invoicearid']:''),
			date(Yii::app()->params['datetodb'], strtotime($_POST['invoicearumum-invoiceardate'])),
				$_POST['invoicearumum-plantid'],
				$_POST['invoicearumum-invoicearno'],
				$_POST['invoicearumum-addressbookid'],
				$_POST['invoicearumum-contractno'],
				$_POST['invoicearumum-invoiceartaxno'],
				$_POST['invoicearumum-addressname'],
				$_POST['invoicearumum-paymentmethodid'],
				$_POST['invoicearumum-cashbankid'],
				$_POST['invoicearumum-dpamount'],
				$_POST['invoicearumum-headernote']
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
			$sql     = 'call Insertinvoicearumumtax(:vinvoicearid,:vtaxid,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateinvoicearumumtax(:vid,:vinvoicearid,:vtaxid,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vinvoicearid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vtaxid', $arraydata[2], PDO::PARAM_STR);
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
			$this->ModifyDataTax($connection,array((isset($_POST['invoiceartaxid'])?$_POST['invoiceartaxid']:''),
				$_POST['invoicearid'],$_POST['taxid']));
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
			$sql     = 'call Insertinvoicearumumdetail(:vinvoicearid,:vslocid,:vproductid,:vqty,:vuomid,:vqty2,:vuom2id,:vqty3,:vuom3id,:vqty4,:vuom4id,:vprice,
				:vdiscount,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateinvoicearumumdetail(:vid,:vinvoicearid,:vslocid,:vproductid,:vqty,:vuomid,:vqty2,:vuom2id,:vqty3,:vuom3id,:vqty4,:vuom4id,
				:vprice,:vdiscount,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vinvoicearid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vslocid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vqty', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vqty2', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vuom2id', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vqty3', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vuom3id', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vqty4', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vuom4id', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vprice', $arraydata[12], PDO::PARAM_STR);
		$command->bindvalue(':vdiscount', $arraydata[13], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid', $arraydata[14], PDO::PARAM_STR);
		$command->bindvalue(':vratevalue', $arraydata[15], PDO::PARAM_STR);
		$command->bindvalue(':vdetailnote', $arraydata[16], PDO::PARAM_STR);
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
			$this->ModifyDataDetail($connection,array((isset($_POST['invoiceardetailid'])?$_POST['invoiceardetailid']:''),
				$_POST['invoicearid'],$_POST['slocid'],
				$_POST['productid'],
				$_POST['qty'],$_POST['uomid'],
				$_POST['qty2'],$_POST['uom2id'],
				$_POST['qty3'],$_POST['uom3id'],
				$_POST['qty4'],$_POST['uom4id'],
				$_POST['price'],$_POST['discount'],
				$_POST['currencyid'],
				$_POST['ratevalue'],
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
  private function ModifyDataJurnal($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertinvoicearumumjurnal(:vinvoicearid,:vaccountid,:vdebit,:vcredit,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateinvoicearumumjurnal(:vid,:vinvoicearid,:vaccountid,:vdebit,:vcredit,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vinvoicearid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vaccountid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vdebit', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vcredit', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vratevalue', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vdetailnote', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavejurnal() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataJurnal($connection,array((isset($_POST['invoicearjurnalid'])?$_POST['invoicearjurnalid']:''),
				$_POST['invoicearid'],$_POST['accountid'],
				$_POST['debit'],$_POST['credit'],$_POST['currencyid'],$_POST['ratevalue'],$_POST['detailnote']));
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
				$sql     = 'call Purgeinvoicear(:vid,:vdatauser)';
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
      GetMessage(true, 'chooseone');
    }
  }
  public function actionPurgetax() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeinvoicearumumtax(:vid,:vdatauser)';
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
      GetMessage(true, 'chooseone');
    }
  }
  public function actionPurgedetail() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeinvoicearumumdetail(:vid,:vdatauser)';
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
      GetMessage(true, 'chooseone');
    }
  }
  public function actionPurgejurnal() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeinvoicearumumjurnal(:vid,:vdatauser)';
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
      GetMessage(true, 'chooseone');
    }
  }
  public function actionReject() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call RejectInvoicear(:vid,:vdatauser)';
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
      GetMessage(true, 'chooseone');
    }
  }
  public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Approveinvoicearumum(:vid,:vdatauser)';
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
      GetMessage(true, 'chooseone');
    }
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select a.invoicearid,a.companyid,a.dpamount,g.symbol,currencyrate,invoicearno,f.sono,d.fullname as customer,a.invoiceardate,a.headernote, a.taxno,a.recordstatus,
k.addressname as shipto,j.cityname,
		 a.recordstatus,date_add(a.invoiceardate, INTERVAL e.paydays day) as duedate,f.sono,f.soheaderid,h.fullname as sales,i.bankacc1,i.bankacc2,i.bankacc3
		from invoicear a 
		left join soheader f on f.soheaderid = a.soheaderid
		left join currency g on g.currencyid = f.currencyid
		left join addressbook d on d.addressbookid = f.addressbookid
		left join paymentmethod e on e.paymentmethodid = f.paymentmethodid
		left join employee h on h.employeeid = f.salesid
		left join company i on i.companyid = a.companyid
		left join city j on j.cityid = i.cityid
		left join address k on k.addressid = f.addresstoid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . " where a.invoicearid in (" . $_GET['id'] . ")";
    }
    $sql        = $sql . " order by invoicearid";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = 'Faktur Penjualan Umum';
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AddFont('tahoma', '', 'tahoma.php');
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('tahoma');
    foreach ($dataReader as $row) {
      $this->pdf->setFontSize(9);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        20,
        70,
        20,
        10,
        10,
        70
      ));
      $this->pdf->row(array(
        'No',
        ' : ' . $row['invoicearno'],
        '',
        '',
        '',
        $row['cityname'] . ', ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoiceardate']))
      ));
      $this->pdf->row(array(
        'T.O.P. ',
        ' : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['duedate'])),
        '',
        '',
        '',
        $row['shipto']
      ));
      $sql1        = "select * 
			from (select h.invoicearid,d.productname,sum(t.qty) as qty,c.uomcode,t.price,b.symbol,
			t.discount,t.detailnote, t.ratevalue, t.dpp,t.total
			from invoiceardetail t
			left join invoicear h on h.invoicearid = t.invoicearid
			left join product d on d.productid = t.productid
			left join currency b on b.currencyid = t.currencyid
			left join unitofmeasure c on c.unitofmeasureid = t.uomid
			where t.invoicearid = " . $row['invoicearid'] . " group by d.productname ) zz";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->SetY($this->pdf->gety() + 3);
      $this->pdf->setFontSize(9);
      $this->pdf->colalign = array(
        'L',
        'L',
        'C',
        'C',
        'C',
        'C',
        'L',
        'L'
      );
      $this->pdf->setwidths(array(
        7,
        90,
        13,
        15,
        35,
        35
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Unit',
        'Price',
        'Total'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'R',
        'R',
        'R',
        'R'
      );
      $i                         = 0;
      $total                     = 0;
      $b                         = '';
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $b = $row1['symbol'];
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatCurrency($row1['qty']),
          $row1['uomcode'],
          Yii::app()->format->formatCurrency($row1['price'], $row1['symbol']),
          Yii::app()->format->formatCurrency(($row1['price'] * $row1['qty']), $row1['symbol'])
        ));
        $total += ($row1['price'] * $row1['qty']);
      }
      $this->pdf->setaligns(array(
        'L',
        'R',
        'L',
        'R',
        'C',
        'R',
        'R',
        'R'
      ));
     // $bilangan                  = explode(".", $row1['price']);
      $this->pdf->iscustomborder = true;
      $this->pdf->setbordercell(array(
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        ''
      ));
      $this->pdf->colalign = array(
        'C'
      );
      $this->pdf->setwidths(array(
        150
      ));
      $this->pdf->coldetailalign = array(
        'L'
      );
     // $this->pdf->row(array(
   //     'Terbilang : ' . eja($bilangan[0])
    //  ));
      $this->pdf->row(array(
        'NOTE : ' . $row['headernote']
      ));
      $this->pdf->checkNewPage(20);
      $this->pdf->text(25, $this->pdf->gety() + 5, 'Approved By');
      $this->pdf->text(170, $this->pdf->gety() + 5, 'Proposed By');
      $this->pdf->text(25, $this->pdf->gety() + 25, '_____________ ');
      $this->pdf->text(170, $this->pdf->gety() + 25, '_____________');
      $this->pdf->text(10, $this->pdf->gety() + 30, 'Catatan:');
      $this->pdf->text(25, $this->pdf->gety() + 30, '- Pembayaran dengan Cek/Giro dianggap lunas apabila telah dicairkan');
      if ($row['bankacc1'] !== null ){
      $this->pdf->text(25, $this->pdf->gety() + 35, '- Transfer Bank ke:');
      $this->pdf->text(55, $this->pdf->gety() + 35, '~ Rekening '.$row['bankacc1']);}
      if ($row['bankacc2'] !== null ){
      $this->pdf->text(55, $this->pdf->gety() + 40, '~ Rekening '.$row['bankacc2']);}
      if ($row['bankacc3'] !== null ){
      $this->pdf->text(55, $this->pdf->gety() + 45, '~ Rekening '.$row['bankacc3']);}
    }
    $this->pdf->Output();
  }
}