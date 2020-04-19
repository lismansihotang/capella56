<?php
class ReqpayController extends Controller {
  public $menuname = 'reqpay';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail() {
    if (isset($_GET['grid']))
      echo $this->actionsearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
    $id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'reqpayid' => $id
		));
  }
  public function search() {
    header('Content-Type: application/json');
		$reqpayid 		= GetSearchText(array('POST','GET','Q'),'reqpayid');
		$companycode     		= GetSearchText(array('POST','GET','Q'),'companycode');
		$companyid     		= GetSearchText(array('GET'),'companyid',0,'int');
		$invoiceapno   		= GetSearchText(array('POST','GET','Q'),'invoiceapno');
		$supplier   		= GetSearchText(array('POST','GET','Q'),'supplier');
		$reqpaydate    = GetSearchText(array('POST','GET','Q'),'reqpaydate');
		$reqpayno 		= GetSearchText(array('POST','GET','Q'),'reqpayno');
		$headernote      = GetSearchText(array('POST','GET','Q'),'headernote');
		$recordstatus 		= GetSearchText(array('POST','GET','Q'),'recordstatus');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','reqpayid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
				
    if (isset($_GET['cashbankout'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
				->from('reqpay t')
        ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('company b', 'b.companyid = t.companyid')
				->where("((reqpaydate like '".$reqpaydate."') or 
            (reqpayno like '".$reqpayno."') or 
            (fullname like '".$supplier."')) 
						and t.recordstatus=getwfmaxstatbywfname('appreqpay') 
						and b.companyid = ".$companyid."
						and t.recordstatus in (".getUserRecordStatus('listreqpay').")")->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('reqpay t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('company b', 'b.companyid = t.companyid')
			->where("(coalesce(reqpaydate,'') like :reqpaydate) 
			and (coalesce(reqpayno,'') like :reqpayno) 
			and (coalesce(headernote,'') like :headernote) 
			and (coalesce(reqpayid,'') like :reqpayid) 
			and (coalesce(fullname,'') like :supplier) 
			and (coalesce(companycode,'') like :companycode) 
			and t.recordstatus in (".getUserRecordStatus('listreqpay').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					" 
			and t.companyid in (".getUserObjectValues('company').")". 
				(($invoiceapno != '%%')?"
				and t.reqpayid in (
				select distinct z.reqpayid 
				from reqpayinv z 
				join invoiceap za on za.invoiceapid = z.invoiceapid 
				where za.invoiceapno like '".$invoiceapno."'
				)":''),
			array(
        ':reqpaydate' => '%' . $reqpaydate . '%',
      ':headernote' => '%' . $headernote . '%',
      ':reqpayid' => '%' . $reqpayid . '%',
      ':supplier' => '%' . $supplier . '%',
      ':companycode' => '%' . $companycode . '%',
      ':reqpayno' => '%' . $reqpayno . '%'
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['cashbankout'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.fullname,b.companyid,b.companycode')
      ->from('reqpay t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('company b', 'b.companyid = t.companyid')
      ->where("((reqpaydate like '".$reqpaydate."') or
                            (reqpayno like '".$reqpayno."') or 
                            (fullname like '".$supplier."')
                            ) 
                            and t.recordstatus=getwfmaxstatbywfname('appreqpay') 
                            and b.companyid = ".$companyid."
                            and t.recordstatus in (".getUserRecordStatus('listreqpay').")")->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.fullname,b.companyid,b.companycode')
      ->from('reqpay t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('company b', 'b.companyid = t.companyid')
		->where("(coalesce(reqpaydate,'') like :reqpaydate) 
			and (coalesce(reqpayno,'') like :reqpayno) 
			and (coalesce(headernote,'') like :headernote) 
			and (coalesce(reqpayid,'') like :reqpayid) 
			and (coalesce(fullname,'') like :supplier) 
			and (coalesce(companycode,'') like :companycode) 
			and t.recordstatus in (".getUserRecordStatus('listreqpay').") ".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					"
			and t.companyid in (".getUserObjectValues('company').")".
				(($invoiceapno != '%%')?"
				and t.reqpayid in (
				select distinct z.reqpayid 
				from reqpayinv z 
				join invoiceap za on za.invoiceapid = z.invoiceapid 
				where za.invoiceapno like '".$invoiceapno."'
				)":''), 
			array(
        ':reqpaydate' => '%' . $reqpaydate . '%',
      ':headernote' => '%' . $headernote . '%',
      ':reqpayid' => '%' . $reqpayid . '%',
      ':supplier' => '%' . $supplier . '%',
      ':companycode' => '%' . $companycode . '%',
      ':reqpayno' => '%' . $reqpayno . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'reqpayid' => $data['reqpayid'],
        'reqpaydate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['reqpaydate'])),
        'reqpayno' => $data['reqpayno'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
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
  public function actionSearchdetail() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page       = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows       = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort       = isset($_POST['sort']) ? strval($_POST['sort']) : 'reqpayinvid';
    $order      = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset     = ($page - 1) * $rows;
    $page       = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows       = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order      = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
		->from('reqpayinv t')
		->leftjoin('invoiceap a', 'a.invoiceapid=t.invoiceapid')
		->leftjoin('poheader c', 'c.poheaderid=a.poheaderid')
		->leftjoin('paymentmethod d', 'd.paymentmethodid=c.paymentmethodid')
		->leftjoin('currency e', 'e.currencyid=t.currencyid')
		->leftjoin('notagrr f', 'f.notagrrid=t.notagrrid')
		->leftjoin('addressbook g', 'g.addressbookid=f.addressbookid')
		->where('t.reqpayid = :reqpayid', array(
      ':reqpayid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.invoiceapno,t.amount,e.currencyname,e.symbol,a.receiptdate,f.notagrrno,f.notagrrdate')
		->from('reqpayinv t')
		->leftjoin('invoiceap a', 'a.invoiceapid=t.invoiceapid')
		->leftjoin('poheader c', 'c.poheaderid=a.poheaderid')
		->leftjoin('paymentmethod d', 'd.paymentmethodid=c.paymentmethodid')
		->leftjoin('currency e', 'e.currencyid=t.currencyid')
		->leftjoin('notagrr f', 'f.notagrrid=t.notagrrid')
		->where('t.reqpayid = :reqpayid', array(
      ':reqpayid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    $symbol = '';
    foreach ($cmd as $data) {
			$receiptdate = $data['receiptdate'];
			if ($receiptdate == '') {
				$receiptdate = $data['notagrrdate'];
			}
      $row[] = array(
        'reqpayinvid' => $data['reqpayinvid'],
        'reqpayid' => $data['reqpayid'],
        'invoiceapid' => $data['invoiceapid'],
        'invoiceapno' => $data['invoiceapno'],
        'duedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['duedate'])),
        'receiptdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($receiptdate)),
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'symbol' => $data['symbol'],
        'ratevalue' => Yii::app()->format->formatNumber($data['ratevalue']),
        'detailnote' => $data['detailnote'],
        'notagrrid' => $data['notagrrid'],
        'notagrrno' => $data['notagrrno']
      );
      $symbol = $data['symbol'];
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    $cmd             = Yii::app()->db->createCommand()->select('sum(t.amount) as amount')->from('reqpayinv t')
		->leftjoin('invoiceap a', 'a.invoiceapid=t.invoiceapid')
		->leftjoin('currency e', 'e.currencyid=t.currencyid')->where('t.reqpayid = :reqpayid', array(
      ':reqpayid' => $id
    ))->queryRow();
		$footer[] = array(
      'invoiceapno' => 'Total',
      'symbol' => $symbol,
      'amount' => Yii::app()->format->formatNumber($cmd['amount']),
    );
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		$sql     = 'call Modifreqpay(:vid,:vcompanyid,:vaddressbookid,:vreqpaydate,:vheadernote,:vdatauser)';
		$command = $connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vcompanyid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vreqpaydate', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSave() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['reqpay-reqpayid'])?$_POST['reqpay-reqpayid']:''),
				$_POST['reqpay-companyid'],$_POST['reqpay-addressbookid'],date(Yii::app()->params['datetodb'], strtotime($_POST['reqpay-reqpaydate'])),
				$_POST['reqpay-headernote']));
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
			$sql     = 'call Insertreqpayinv(:vreqpayid,:vinvoiceapid,:vnotagrrid,:vduedate,:vamount,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatereqpayinv(:vid,:vreqpayid,:vinvoiceapid,:vnotagrrid,:vduedate,:vamount,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vreqpayid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vinvoiceapid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vnotagrrid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vduedate', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vamount', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vratevalue', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vdetailnote', $arraydata[8], PDO::PARAM_STR);
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
			$this->ModifyDataDetail($connection,array((isset($_POST['reqpayinvid'])?$_POST['reqpayinvid']:''),
				$_POST['reqpayid'],$_POST['invoiceapid'],$_POST['notagrrid'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['duedate'])),$_POST['amount'],$_POST['currencyid'],$_POST['ratevalue'],$_POST['detailnote']));
			$transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
  public function actionReject() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call RejectReqpay(:vid,:vdatauser)';
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
        $sql     = 'call ApproveReqpay(:vid,:vdatauser)';
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
  public function actionPurge() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgereqpay(:vid,:vdatauser)';
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
  public function actionPurgeinvoice() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgereqpayinv(:vid,:vdatauser)';
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
  public function actiongeneratebank() {
    if (isset($_POST['invoiceapid'])) {
      $taxid         = '';
      $taxno         = '';
      $taxdate       = '';
      $bankaccountno = '';
      $bankname      = '';
      $bankowner     = '';
      $cmd           = Yii::app()->db->createCommand()->select('t.*,b.bankaccountno,b.bankname,b.accountowner')->from('invoiceap t')->join('poheader a', 'a.poheaderid=t.poheaderid')->join('addressbook b', 'b.addressbookid=t.addressbookid')->where("invoiceapid = '" . $_POST['invoiceapid'] . "' ")->limit(1)->queryRow();
      $taxid         = $cmd['taxid'];
      $taxno         = $cmd['taxno'];
      $taxdate       = $cmd['taxdate'];
      $bankaccountno = $cmd['bankaccountno'];
      $bankname      = $cmd['bankname'];
      $bankowner     = $cmd['accountowner'];
    }
    if (Yii::app()->request->isAjaxRequest) {
      echo CJSON::encode(array(
        'status' => 'success',
        'currencyid' => 40,
        'currencyrate' => 1,
        'bankaccountno' => $bankaccountno,
        'bankname' => $bankname,
        'bankowner' => $bankowner
      ));
      Yii::app()->end();
    }
  }
  public function actionGenerateinvoiceapdetail() {
		$sql = "select b.addressbookid,b.fullname,date_add(a.invoiceapdate,interval c.paydays day) as duedate,	
			getamountbyinvoiceapumum(a.invoiceapid) as amount,d.currencyid,d.ratevalue,e.currencyname
			from invoiceap a 
			left join addressbook b on b.addressbookid = a.addressbookid 
			left join paymentmethod c on c.paymentmethodid=a.paymentmethodid
			left join invoiceapdetail d on d.invoiceapid=a.invoiceapid
			left join currency e on e.currencyid=d.currencyid
			where a.invoiceapid = ".$_POST['invoiceapid']." 
			limit 1";
		$data = Yii::app()->db->createCommand($sql)->queryRow();
		echo CJSON::encode(array(
			'addressbookid' => $data['addressbookid'],
			'fullname' => $data['fullname'],
			'duedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['duedate'])),
			'amount' => $data['amount'],
			'currencyid' => $data['currencyid'],
			'currencyname' => $data['currencyname'],
			'ratevalue' => $data['ratevalue'],
		));
		Yii::app()->end();
  }
	public function actionGeneratenotagrr() {
		$sql = "	SELECT b.addressbookid,b.fullname,notagrrdate as duedate,getamountbynotagrr(a.notagrrid) as amount,e.currencyid,GetKurs(a.notagrrdate, e.currencyid) AS ratevalue,e.currencyname
	FROM notagrr a
	left join addressbook b on b.addressbookid = a.addressbookid 
	left JOIN poheader f ON f.poheaderid = a.poheaderid
	left join paymentmethod c on c.paymentmethodid=f.paymentmethodid
	left join currency e on e.currencyid=f.currencyid
			where a.notagrrid = ".$_POST['notagrrid']." 
			limit 1";
		$data = Yii::app()->db->createCommand($sql)->queryRow();
		echo CJSON::encode(array(
			'addressbookid' => $data['addressbookid'],
			'fullname' => $data['fullname'],
			'duedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['duedate'])),
			'receiptdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['duedate'])),
			'amount' => $data['amount']*-1,
			'currencyid' => $data['currencyid'],
			'currencyname' => $data['currencyname'],
			'ratevalue' => $data['ratevalue'],
		));
		Yii::app()->end();
  }
	public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select distinct a.reqpayid,a.reqpaydate,d.fullname as supplier,d.bankname,d.bankaccountno,a.companyid,d.accountowner,a.reqpayno,
	  (select sum(zc.total) 
				from invoiceap za
        join reqpayinv zb on zb.invoiceapid = za.invoiceapid 
        join invoiceapdetail zc on zc.invoiceapid = za.invoiceapid 
				where zb.reqpayid = a.reqpayid) as nilai,a.headernote
				from reqpay a 
				join addressbook d on d.addressbookid = a.addressbookid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.reqpayid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->title = GetCatalog('reqpay');
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety() + 2, 'No. Dokumen ');
      $this->pdf->text(40, $this->pdf->gety() + 2, ': ' . $row['reqpayno']);
      $this->pdf->text(10, $this->pdf->gety() + 6, 'Dibayarkan kepada ');
      $this->pdf->text(40, $this->pdf->gety() + 6, ': ' . $row['supplier']);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Sejumlah Rp. ');
      $this->pdf->text(40, $this->pdf->gety() + 10, ': ' . Yii::app()->format->formatCurrency($row['nilai']));
      $this->pdf->text(120, $this->pdf->gety() + 6, 'Bank ');
      $this->pdf->text(140, $this->pdf->gety() + 6, ': ' . $row['bankname']);
      $this->pdf->text(120, $this->pdf->gety() + 10, 'A/N ');
      $this->pdf->text(140, $this->pdf->gety() + 10, ': ' . $row['accountowner']);
      $this->pdf->SetFontSize(9);
      $this->pdf->text(120, $this->pdf->gety() + 14, 'No Rekening');
      $this->pdf->text(140, $this->pdf->gety() + 14, ': ' . $row['bankaccountno']);
      $this->pdf->SetFontSize(8);
      $this->pdf->text(120, $this->pdf->gety() + 2, 'Tgl Dokumen ');
      $this->pdf->text(140, $this->pdf->gety() + 2, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['reqpaydate'])));
      $this->pdf->text(10, $this->pdf->gety() + 18, 'Terbilang ');
      $this->pdf->text(40, $this->pdf->gety() + 18, ': ');
      $this->pdf->sety($this->pdf->gety() + 15);
      $this->pdf->setaligns(array(
        'C',
        'L'
      ));
      $this->pdf->setwidths(array(
        31,
        160
      ));
      $this->pdf->row(array(
        '',
        strtoupper(eja($row['nilai']))
      ));
      $sql1        = "select b.invoiceapno,d.fullname as supplier,b.invoiceapdate,adddate(b.invoiceapdate,e.paydays) as duedate,a.amount,b.taxno,a.detailnote
        from reqpayinv a
        left join invoiceap b on b.invoiceapid = a.invoiceapid
        left join poheader c on c.poheaderid = b.poheaderid 
				left join addressbook d on d.addressbookid = c.addressbookid
        left join paymentmethod e on e.paymentmethodid = c.paymentmethodid
        where reqpayid = " . $row['reqpayid'] . "
        order by reqpayinvid ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 2);
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
        30,
        25,
        35,
        50
      ));
      $this->pdf->colheader = array(
        'No',
        'No Invoice',
        'Tgl Invoice',
        'Nilai',
        'Jth Tempo',
        'No Faktur pajak',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'C',
        'C',
        'C',
        'R',
        'C',
        'C',
        'L'
      );
      $i                         = 0;
      $total                     = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['invoiceapno'],
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoiceapdate'])),
          Yii::app()->format->formatCurrency($row1['amount']),
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['duedate'])),
          $row1['taxno'],
          $row1['detailnote']
        ));
        $total += $row1['amount'];
      }
      $this->pdf->SetFontSize(10);
      $this->pdf->setaligns(array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'L',
        'R'
      ));
      $this->pdf->setwidths(array(
        10,
        25,
        25,
        25,
        25,
        25,
        35
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        '',
        'TOTAL :',
        Yii::app()->format->formatCurrency($total)
      ));
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'Keterangan ');
      $this->pdf->text(25, $this->pdf->gety() + 5, ': ');
      $this->pdf->text(30, $this->pdf->gety() + 5, ': ' . $row['headernote']);
      $this->pdf->checkNewPage(30);
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety(), 'Diajukan oleh');
      $this->pdf->text(45, $this->pdf->gety(), 'Diperiksa oleh');
      $this->pdf->text(85, $this->pdf->gety(), 'Diketahui oleh');
      $this->pdf->text(125, $this->pdf->gety(), 'Disetujui oleh');
      $this->pdf->text(165, $this->pdf->gety(), 'Dibayar oleh');
      $this->pdf->text(10, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(45, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(85, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(125, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(165, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(10, $this->pdf->gety() + 20, 'Adm H/D');
      $this->pdf->text(42, $this->pdf->gety() + 20, 'Divisi Acc & Finance');
      $this->pdf->text(85, $this->pdf->gety() + 20, 'Branch Manager');
      $this->pdf->text(125, $this->pdf->gety() + 20, 'Dir. Keuangan');
      $this->pdf->text(165, $this->pdf->gety() + 20, 'Bag. Bank pusat');
      $this->pdf->text(10, $this->pdf->gety() + 25, 'Tgl :');
      $this->pdf->text(42, $this->pdf->gety() + 25, 'Tgl :');
      $this->pdf->text(85, $this->pdf->gety() + 25, 'Tgl :');
      $this->pdf->text(125, $this->pdf->gety() + 25, 'Tgl :');
      $this->pdf->text(165, $this->pdf->gety() + 25, 'Tgl :');
      $this->pdf->setFontSize(7);
      $this->pdf->text(10, $this->pdf->gety() + 33, 'NB :Faktur pajak wajib diisi jika pembayaran melalui Legal (Tanpa melampirkan faktur pajak lagi)');
      $this->pdf->text(10, $this->pdf->gety() + 38, '     :Dibuat rangkap 3, putih untuk Bag.Bank/Kasir, setelah dibayar diserahkan ke Adm H/D,Rangkap 2 utk Bag.Pajak,rangkap 3 Arsip H/D');
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    parent::actionDownload();
    $sql = "select reqpaydate,reqpayno,headernote,recordstatus
				from reqpay a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.reqpayid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('reqpaydate'))->setCellValueByColumnAndRow(1, 1, GetCatalog('reqpayno'))->setCellValueByColumnAndRow(2, 1, GetCatalog('headernote'))->setCellValueByColumnAndRow(3, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['reqpaydate'])->setCellValueByColumnAndRow(1, $i + 1, $row1['reqpayno'])->setCellValueByColumnAndRow(2, $i + 1, $row1['headernote'])->setCellValueByColumnAndRow(3, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reqpay.xlsx"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $objWriter->save('php://output');
    unset($excel);
  }
}
