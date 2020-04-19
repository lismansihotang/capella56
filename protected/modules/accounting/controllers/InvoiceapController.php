<?php
class InvoiceapController extends Controller {
  public $menuname = 'invoiceap';
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
  public function actionIndexgr() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchgr();
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
  public function actionIndexreqpay() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchreqpay();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
    $id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'invoiceapid' => $id
		));
  }
  public function search() {
    header('Content-Type: application/json');
    $invoiceapid 		= GetSearchText(array('POST','GET'),'invoiceapid','','int');
    $companycode 		= GetSearchText(array('POST','Q'),'companycode');
    $supplier 		= GetSearchText(array('POST','Q'),'supplier');
    $headernote 		= GetSearchText(array('POST','Q'),'headernote');
    $plantcode 		= GetSearchText(array('POST','Q'),'plantcode');
    $pono 		= GetSearchText(array('POST','Q'),'pono');
    $nobuktipotong 		= GetSearchText(array('POST','Q'),'nobuktipotong');
    $invoiceapno 		= GetSearchText(array('POST','Q'),'invoiceapno');
	  $sjsupplier 		= GetSearchText(array('POST','Q'),'sjsupplier');
    $invoiceaptaxno 		= GetSearchText(array('POST','Q'),'invoiceaptaxno');
    $recordstatus 		= GetSearchText(array('POST','Q'),'recordstatus');
    $invoiceapdate 		= GetSearchText(array('POST','Q'),'invoiceapdate');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','invoiceapid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset       	= ($page - 1) * $rows;
    $result       	= array();
    $row          	= array();

		$cmd = Yii::app()->db->createCommand()
			->selectdistinct('count(1) as total')
			->from('invoiceap t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('cashbank b', 'b.cashbankid = t.cashbankid')
	  //test penambahan Victor 2019-11-12
	  ->leftjoin('invoiceapgr c', 'c.invoiceapid = t.invoiceapid')
	  ->leftjoin('grheader d', 'd.grheaderid = c.grheaderid')
	  //end test penambahan Victor 2019-11-12
			->where("
				((coalesce(t.invoiceapid,'') like :invoiceapid) 
				and (coalesce(a.fullname,'') like :supplier) 
				and (coalesce(t.headernote,'') like :headernote) 
				and (coalesce(t.invoiceapdate,'') like :invoiceapdate) 
				and (coalesce(t.plantcode,'') like :plantcode) 
				and (coalesce(t.pono,'') like :pono) 
				and (coalesce(t.nobuktipotong,'') like :nobuktipotong) 
				and (coalesce(t.invoiceapno,'') like :invoiceapno) 
				and (coalesce(t.invoiceaptaxno,'') like :invoiceaptaxno)
				and (coalesce(d.sjsupplier,'') like :sjsupplier))
				and t.doctype = 1 
					and t.recordstatus in (".getUserRecordStatus('listinvap').")
					and t.companyid in (".getUserObjectValues('company').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'')
					, array(
					':invoiceapid' => '%' . $invoiceapid . '%',
					':supplier' => '%' . $supplier . '%',
					':headernote' => '%' . $headernote . '%',
					':plantcode' => '%' . $plantcode . '%',
					':pono' => '%' . $pono . '%',
					':nobuktipotong' => '%' . $nobuktipotong . '%',
					':invoiceapdate' => '%' . $invoiceapdate . '%',
					':invoiceapno' => '%' . $invoiceapno . '%',
					':invoiceaptaxno' => '%' . $invoiceaptaxno . '%',
					':sjsupplier' => '%' . $sjsupplier . '%'
				))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->selectdistinct('t.*,a.fullname,b.cashbankno')
			->from('invoiceap t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('cashbank b', 'b.cashbankid = t.cashbankid')
	    //test penambahan Victor 2019-11-12
	  ->leftjoin('invoiceapgr c', 'c.invoiceapid = t.invoiceapid')
	  ->leftjoin('grheader d', 'd.grheaderid = c.grheaderid')
	  //end test penambahan Victor 2019-11-12
			->where("
				((coalesce(t.invoiceapid,'') like :invoiceapid) 
				and (coalesce(a.fullname,'') like :supplier) 
				and (coalesce(t.headernote,'') like :headernote) 
				and (coalesce(t.plantcode,'') like :plantcode) 
				and (coalesce(t.pono,'') like :pono) 
				and (coalesce(t.invoiceapdate,'') like :invoiceapdate) 
				and (coalesce(t.nobuktipotong,'') like :nobuktipotong) 
				and (coalesce(t.invoiceapno,'') like :invoiceapno) 
				and (coalesce(t.invoiceaptaxno,'') like :invoiceaptaxno)
				and (coalesce(d.sjsupplier,'') like :sjsupplier))
				and t.doctype = 1  
				and t.recordstatus in (".getUserRecordStatus('listinvap').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					"
				and t.companyid in (".getUserObjectValues('company').")"
				, array(
					':invoiceapid' => '%' . $invoiceapid . '%',
					':supplier' => '%' . $supplier . '%',
					':headernote' => '%' . $headernote . '%',
					':plantcode' => '%' . $plantcode . '%',
					':pono' => '%' . $pono . '%',
					':invoiceapdate' => '%' . $invoiceapdate . '%',
					':nobuktipotong' => '%' . $nobuktipotong . '%',
					':invoiceapno' => '%' . $invoiceapno . '%',
					':invoiceaptaxno' => '%' . $invoiceaptaxno . '%',
					':sjsupplier' => '%' . $sjsupplier . '%'
        ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'invoiceapid' => $data['invoiceapid'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'invoiceapdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['invoiceapdate'])),
        'invoiceapno' => $data['invoiceapno'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'contractno' => $data['contractno'],
        'invoiceaptaxno' => $data['invoiceaptaxno'],
        'taxno' => $data['taxno'],
        'nobuktipotong' => $data['nobuktipotong'],
        'receiptdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['receiptdate'])),
        'beritaacara' => $data['beritaacara'],
        'paymentmethodid' => $data['paymentmethodid'],
        'paycode' => $data['paycode'],
        'duedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['duedate'])),
        'cashbankid' => $data['cashbankid'],
        'cashbankno' => $data['cashbankno'],
        'discount' => Yii::app()->format->formatNumber($data['discount']),
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'invoiceaptaxid';
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('invoiceaptax t')->leftjoin('tax a', 'a.taxid = t.taxid')->where("(invoiceapid = :invoiceapid)", array(
      ':invoiceapid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.taxcode,a.taxvalue,a.description')->from('invoiceaptax t')->leftjoin('tax a', 'a.taxid = t.taxid')->where("(invoiceapid = :invoiceapid)", array(
      ':invoiceapid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'invoiceaptaxid' => $data['invoiceaptaxid'],
        'invoiceapid' => $data['invoiceapid'],
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
	public function actionsearchgr() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'invoiceapgrid';
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
			->from('invoiceapgr t')
			->leftjoin('grheader a', 'a.grheaderid = t.grheaderid')
			->where("(invoiceapid = :invoiceapid)", array(
      ':invoiceapid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
			->select('t.*,a.grno,a.sjsupplier,a.supir,a.headernote')
			->from('invoiceapgr t')
			->leftjoin('grheader a', 'a.grheaderid = t.grheaderid')
			->where("(invoiceapid = :invoiceapid)", array(
      ':invoiceapid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'invoiceapgrid' => $data['invoiceapgrid'],
        'invoiceapid' => $data['invoiceapid'],
        'grheaderid' => $data['grheaderid'],
        'sjsupplier' => $data['sjsupplier'],
        'supir' => $data['supir'],
        'headernote' => $data['headernote'],
        'grno' => $data['grno'],
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'invoiceapdetailid';
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
		->select('count(1) as total')->from('invoiceapdetail t')
		->leftjoin('product a', 'a.productid = t.productid')
		->leftjoin('currency b', 'b.currencyid = t.currencyid')
		->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uomid')
		->leftjoin('unitofmeasure d', 'd.unitofmeasureid = t.uom2id')
		->leftjoin('unitofmeasure e', 'e.unitofmeasureid = t.uom3id')
		->leftjoin('unitofmeasure f', 'f.unitofmeasureid = t.uom4id')
		->leftjoin('sloc g', 'g.slocid = t.slocid')
			->leftjoin('materialtype h', 'h.materialtypeid = a.materialtypeid')
		->where("(invoiceapid = :invoiceapid)", array(
      ':invoiceapid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
		->select('t.*,a.productcode,b.currencyname,b.symbol,c.uomcode,d.uomcode as uom2code,e.uomcode as uom3code,f.uomcode as uom4code,g.sloccode,h.materialtypecode')
		->from('invoiceapdetail t')
		->leftjoin('product a', 'a.productid = t.productid')
		->leftjoin('currency b', 'b.currencyid = t.currencyid')
		->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uomid')
		->leftjoin('unitofmeasure d', 'd.unitofmeasureid = t.uom2id')
		->leftjoin('unitofmeasure e', 'e.unitofmeasureid = t.uom3id')
		->leftjoin('unitofmeasure f', 'f.unitofmeasureid = t.uom4id')
		->leftjoin('sloc g', 'g.slocid = t.slocid')
			->leftjoin('materialtype h', 'h.materialtypeid = a.materialtypeid')
		->where("(invoiceapid = :invoiceapid)", array(
      ':invoiceapid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'invoiceapdetailid' => $data['invoiceapdetailid'],
        'invoiceapid' => $data['invoiceapid'],
        'invoiceapgrid' => $data['invoiceapgrid'],
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
        'taxvalue' => Yii::app()->format->formatNumber($data['taxvalue']),
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
		sum(total) as total from invoiceapdetail t where invoiceapid = ".$id;
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'invoiceapjurnalid';
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('invoiceapjurnal t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->leftjoin('company c', 'c.companyid = a.companyid')->where("(invoiceapid = :invoiceapid) and t.accountid > 0", array(
      ':invoiceapid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.accountcode,a.accountname,b.currencyname,c.companyname,b.symbol')->from('invoiceapjurnal t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->leftjoin('company c', 'c.companyid = a.companyid')->where("(invoiceapid = :invoiceapid) and t.accountid > 0", array(
      ':invoiceapid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'invoiceapjurnalid' => $data['invoiceapjurnalid'],
        'invoiceapid' => $data['invoiceapid'],
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
		$sql = "select sum(debit) as debit, sum(credit) as credit from invoiceapjurnal where invoiceapid = ".$id;
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
	public function actionsearchreqpay() {
    header('Content-Type: application/json');
    $invoiceapno = isset($_GET['q']) ? $_GET['q'] : '';
    $result       	= array();
    $row          	= array();

		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('invoiceap t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
			->where("
				((coalesce(t.invoiceapno,'') like :invoiceapno))
					and t.recordstatus in (".getUserRecordStatus('listinvap').")
					and t.companyid in (".getUserObjectValues('company').")
					and t.isreqpay = 1
					and t.companyid = ".$_GET['companyid']."
					and t.addressbookid = ".$_GET['addressbookid']."
					", array(
					':invoiceapno' => '%' . $invoiceapno . '%'
				))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.fullname')
			->from('invoiceap t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
			->where("
				((coalesce(t.invoiceapno,'') like :invoiceapno))
				and t.recordstatus in (".getUserRecordStatus('listinvap').")
        and t.companyid in (".getUserObjectValues('company').")
				and t.isreqpay = 1
        and t.companyid = ".$_GET['companyid']."
        and t.addressbookid = ".$_GET['addressbookid']."
        ", array(
					':invoiceapno' => '%' . $invoiceapno . '%'
				))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'invoiceapid' => $data['invoiceapid'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'invoiceapdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['invoiceapdate'])),
        'invoiceapno' => $data['invoiceapno'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'contractno' => $data['contractno'],
        'invoiceaptaxno' => $data['invoiceaptaxno'],
        'taxno' => $data['taxno'],
        'nobuktipotong' => $data['nobuktipotong'],
        'receiptdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['receiptdate'])),
        'beritaacara' => $data['beritaacara'],
        'paymentmethodid' => $data['paymentmethodid'],
        'paycode' => $data['paycode'],
        'duedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['duedate'])),
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
  private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		$sql     = 'call Modifinvoiceap(:vid,:vinvoiceapdate,:vinvoiceapno,:vplantid,:vaddressbookid,:vpoheaderid,:vinvoiceaptaxno,:vnobuktipotong,
			:vreceiptdate,:vdiscount,:vheadernote,:vdatauser)';
		$command = $connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vinvoiceapdate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vinvoiceapno', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vpoheaderid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vinvoiceaptaxno', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vnobuktipotong', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vreceiptdate', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vdiscount', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-invoiceap"]["name"]);
		if (move_uploaded_file($_FILES["file-invoiceap"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$oldid = '';$pid = '';
			$connection  = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue(); //A
					if ((int)$id > 0) {
						if ($oldid != $id) {
							$oldid = $id;
							$plantcode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue(); //B
							$plantid = Yii::app()->db->createCommand("select plantid from plant where plantcode = '".$plantcode."'")->queryScalar();
							$companyid = Yii::app()->db->createCommand("select companyid from plant where plantcode = '".$plantcode."' limit 1")->queryScalar();
							$invoiceapno = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue(); //C
							$pono = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue(); //D
							$poid = Yii::app()->db->createCommand("
								select poheaderid 
								from poheader 
								where pono = '".$pono."'")->queryScalar();
							$supplier = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue(); //E
							$supplierid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '".$supplier."'")->queryScalar();
							$contractno = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue(); //F
							$invoiceaptaxno = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue(); //G
							$taxno = Yii::app()->db->createCommand("select taxno from addressbook where fullname = '".$supplier."'")->queryScalar();
							$nobuktipotong = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue(); //H
							$invoicedate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(8, $row)->getValue())); //I
							$beritaacara = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue(); //J
							$paycode = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue(); //K							
							$payid = Yii::app()->db->createCommand("select paymentmethodid from paymentmethod where paycode = '".$paycode."'")->queryScalar();
							$duedate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(11, $row)->getValue())); //L
							$diskon = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue(); //M
							$cashbankno = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue(); //N
							$cashbankid = Yii::app()->db->createCommand("select cashbankid from cashbank where cashbankno = '".$cashbankno."'")->queryScalar();
							$dpamount = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue(); //O
							$headernote = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue(); //P
							$sql = "
								insert into invoiceap (invoiceapdate,invoiceapno,poheaderid,pono,companyid,plantid,addressbookid,contractno,invoiceaptaxno,
									taxno,nobuktipotong,receiptdate,beritaacara,paymentmethodid,duedate,discount,cashbankid,dpamount,recordstatus)
								values (:invoiceapdate,:invoiceapno,:poheaderid,:pono,:companyid,:plantid,:addressbookid,:contractno,:invoiceaptaxno,
									:taxno,:nobuktipotong,:receiptdate,:beritaacara,:paymentmethodid,:duedate,:discount,:cashbankid,:dpamount,:recordstatus)
							";
							$command = $connection->createCommand($sql);
							$command->bindvalue(':invoiceapdate',$invoicedate,PDO::PARAM_STR);
							$command->bindvalue(':invoiceapno',$invoiceapno,PDO::PARAM_STR);
							$command->bindvalue(':poheaderid',((isnulloremptystring($poid)!=1)?$poid:null),PDO::PARAM_STR);
							$command->bindvalue(':pono',$pono,PDO::PARAM_STR);
							$command->bindvalue(':companyid',$companyid,PDO::PARAM_STR);
							$command->bindvalue(':plantid',$plantid,PDO::PARAM_STR);
							$command->bindvalue(':addressbookid',$supplierid,PDO::PARAM_STR);
							$command->bindvalue(':contractno',((isnulloremptystring($contractno)!=1)?$contractno:null),PDO::PARAM_STR);
							$command->bindvalue(':invoiceaptaxno',$invoiceaptaxno,PDO::PARAM_STR);
							$command->bindvalue(':taxno',$taxno,PDO::PARAM_STR);
							$command->bindvalue(':nobuktipotong',((isnulloremptystring($nobuktipotong)!=1)?$nobuktipotong:null),PDO::PARAM_STR);
							$command->bindvalue(':receiptdate',$invoicedate,PDO::PARAM_STR);
							$command->bindvalue(':beritaacara',((isnulloremptystring($beritaacara)!=1)?$beritaacara:null),PDO::PARAM_STR);
							$command->bindvalue(':paymentmethodid,',$payid,PDO::PARAM_STR);
							$command->bindvalue(':duedate,',$duedate,PDO::PARAM_STR);
							$command->bindvalue(':discount,',$diskon,PDO::PARAM_STR);
							$command->bindvalue(':cashbankid,',$cashbankid,PDO::PARAM_STR);
							$command->bindvalue(':dpamount,',$dpamount,PDO::PARAM_STR);
							$command->bindvalue(':recordstatus,',1,PDO::PARAM_STR);
							$command->execute();
							$pid = Yii::app()->db->createCommand("
								select invoiceapid 
								from invoiceap a
								where a.plantid = ".$plantid." 
								and a.invoiceapno = '".$invoiceapno."' 
								and a.addressbookid = ".$supplierid." 
								limit 1
							")->queryScalar();
						} 
						if ($pid > 0) {
							$taxcode = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue(); //Q
							$taxid = Yii::app()->db->createCommand("select taxid from tax where taxcode = '".$taxcode."'")->queryScalar();
							$sql = "
								select a.invoiceaptaxid 
								from invoiceaptax a
								where a.invoiceapid = ".$pid." 
								and a.taxid = ".$taxid;
							$ptaxid = Yii::app()->db->createCommand($sql)->queryScalar();
							if ($ptaxid > 0) {
							} else {
								$this->Modifytaxso($connection,array('',
									$pid,
									$taxid
								));
							}
							$sloccode = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue(); //R
							$slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
							$productname = $objWorksheet->getCellByColumnAndRow(19, $row)->getValue(); //T
							$sql = "select productid from product where productname = :productname";
							$command=$connection->createCommand($sql);
							$command->bindvalue(':productname',$productname,PDO::PARAM_STR);
							$productid = $command->queryScalar();
							$qty = $objWorksheet->getCellByColumnAndRow(20, $row)->getValue(); //U
							$uomcode = $objWorksheet->getCellByColumnAndRow(21, $row)->getValue(); //V
							$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$qty2 = $objWorksheet->getCellByColumnAndRow(22, $row)->getValue(); //W
							$uomcode = $objWorksheet->getCellByColumnAndRow(23, $row)->getValue(); //X
							$uom2id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$qty3 = $objWorksheet->getCellByColumnAndRow(24, $row)->getValue(); //Y
							$uomcode = $objWorksheet->getCellByColumnAndRow(25, $row)->getValue(); //Z
							$uom3id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$qty4 = $objWorksheet->getCellByColumnAndRow(26, $row)->getValue(); //AA
							$uomcode = $objWorksheet->getCellByColumnAndRow(27, $row)->getValue(); //AB
							$uom4id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$price = $objWorksheet->getCellByColumnAndRow(28, $row)->getValue(); //AC
							$diskon = $objWorksheet->getCellByColumnAndRow(29, $row)->getValue(); //AD
							$symbol = $objWorksheet->getCellByColumnAndRow(30, $row)->getValue(); //AE
							$currencyid = Yii::app()->db->createCommand("select currencyid from currency where symbol = '".$symbol."'")->queryScalar();
							$rate = $objWorksheet->getCellByColumnAndRow(31, $row)->getValue(); //AF
							$itemnote = $objWorksheet->getCellByColumnAndRow(32, $row)->getValue(); //AG
							$sql = "
								insert into invoiceapdetail (invoiceapid,slocid,productid,qty,qty2,qty3,qty4,uomid,uom2id,uom3id,uom4id,price,discount,currencyid,ratevalue,detailnote)
								values (:invoiceapid,:slocid,:productid,:qty,:qty2,:qty3,:qty4,:uomid,:uom2id,:uom3id,:uom4id,:price,:discount,:currencyid,:ratevalue,:detailnote)";
							$command=$connection->createCommand($sql);
							$command->bindvalue(':invoiceapid',$pid,PDO::PARAM_STR);
							$command->bindvalue(':slocid',$slocid,PDO::PARAM_STR);
							$command->bindvalue(':productid',$productid,PDO::PARAM_STR);
							$command->bindvalue(':qty',$qty,PDO::PARAM_STR);
							$command->bindvalue(':qty2',$qty2,PDO::PARAM_STR);
							$command->bindvalue(':qty3',$qty3,PDO::PARAM_STR);
							$command->bindvalue(':qty4',$qty4,PDO::PARAM_STR);
							$command->bindvalue(':uomid',$uomid,PDO::PARAM_STR);
							$command->bindvalue(':uom2id',$uom2id,PDO::PARAM_STR);
							$command->bindvalue(':uom3id',$uom3id,PDO::PARAM_STR);
							$command->bindvalue(':uom4id',$uom4id,PDO::PARAM_STR);
							$command->bindvalue(':price',$price,PDO::PARAM_STR);
							$command->bindvalue(':discount',$discount,PDO::PARAM_STR);
							$command->bindvalue(':currencyid',$currencyid,PDO::PARAM_STR);
							$command->bindvalue(':ratevalue',$ratevalue,PDO::PARAM_STR);
							$command->bindvalue(':detailnote',$detailnote,PDO::PARAM_STR);
							$command->execute();
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
			$this->ModifyData($connection,array((isset($_POST['invoiceap-invoiceapid'])?$_POST['invoiceap-invoiceapid']:''),
			date(Yii::app()->params['datetodb'], strtotime($_POST['invoiceap-invoiceapdate'])),
				$_POST['invoiceap-invoiceapno'],
				$_POST['invoiceap-plantid'],
				$_POST['invoiceap-addressbookid'],
				$_POST['invoiceap-poheaderid'],
				$_POST['invoiceap-invoiceaptaxno'],
				$_POST['invoiceap-nobuktipotong'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['invoiceap-receiptdate'])),
				$_POST['invoiceap-discount'],
				$_POST['invoiceap-headernote']
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
			$sql     = 'call Insertinvoiceaptax(:vinvoiceapid,:vtaxid,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateinvoiceaptax(:vid,:vinvoiceapid,:vtaxid,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vinvoiceapid', $arraydata[1], PDO::PARAM_STR);
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
			$this->ModifyDataTax($connection,array((isset($_POST['invoiceaptaxid'])?$_POST['invoiceaptaxid']:''),
				$_POST['invoiceapid'],$_POST['taxid']));
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
			$sql     = 'call Insertinvoiceapumumdetail(:vinvoiceapid,:vslocid,:vproductid,:vqty,:vuomid,:vqty2,:vuom2id,:vqty3,:vuom3id,:vqty4,:vuom4id,
				:vprice,:vdiscount,:vcurrencyid,:vratevalue,:vdpp,:vtaxvalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateinvoiceapumumdetail(:vid,:vinvoiceapid,:vslocid,:vproductid,:vqty,:vuomid,:vqty2,:vuom2id,:vqty3,:vuom3id,:vqty4,:vuom4id,
				:vprice,:vdiscount,:vcurrencyid,:vratevalue,:vdpp,:vtaxvalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vinvoiceapid', $arraydata[1], PDO::PARAM_STR);
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
		$command->bindvalue(':vdpp', $arraydata[16], PDO::PARAM_STR);
		$command->bindvalue(':vtaxvalue', $arraydata[17], PDO::PARAM_STR);
		$command->bindvalue(':vdetailnote', $arraydata[18], PDO::PARAM_STR);
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
			$this->ModifyDataDetail($connection,array((isset($_POST['invoiceapdetailid'])?$_POST['invoiceapdetailid']:''),
				$_POST['invoiceapid'],$_POST['slocid'],
				$_POST['productid'],
				$_POST['qty'],$_POST['uomid'],
				$_POST['qty2'],$_POST['uom2id'],
				$_POST['qty3'],$_POST['uom3id'],
				$_POST['qty4'],$_POST['uom4id'],
				$_POST['price'],$_POST['discount'],
				$_POST['currencyid'],$_POST['ratevalue'],$_POST['dpp'],$_POST['taxvalue'],
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
	private function ModifyDataGR($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertinvoiceapgr(:vinvoiceapid,:vgrheaderid,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateinvoiceapgr(:vid,:vinvoiceapid,:vgrheaderid,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vinvoiceapid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vgrheaderid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavegr() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDatagr($connection,array((isset($_POST['invoiceapgrid'])?$_POST['invoiceapgrid']:''),
				$_POST['invoiceapid'],$_POST['grheaderid'],
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
			$sql     = 'call Insertinvoiceapjurnal(:vinvoiceapid,:vaccountid,:vdebit,:vcredit,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateinvoiceapjurnal(:vid,:vinvoiceapid,:vaccountid,:vdebit,:vcredit,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vinvoiceapid', $arraydata[1], PDO::PARAM_STR);
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
			$this->ModifyDataJurnal($connection,array((isset($_POST['invoiceapjurnalid'])?$_POST['invoiceapjurnalid']:''),
				$_POST['invoiceapid'],$_POST['accountid'],
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
				$sql     = 'call Purgeinvoiceap(:vid,:vdatauser)';
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
        $sql     = 'call Purgeinvoiceaptax(:vid,:vdatauser)';
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
  public function actionPurgegr() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeinvoiceapgr(:vid,:vdatauser)';
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
        $sql     = 'call Purgeinvoiceapdetail(:vid,:vdatauser)';
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
        $sql     = 'call Purgeinvoiceapjurnal(:vid,:vdatauser)';
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
        $sql     = 'call RejectInvoiceAP(:vid,:vdatauser)';
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
  public function actionGeneratedetail(){
    if (Yii::app()->request->isAjaxRequest) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateInvapGR(:vid, :vhid, :vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_INT);
        $command->execute();
        $transaction->commit();
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    }
  }
	public function actionGeneratepaymentmethod(){
		$sql = "select a.paymentmethodid,
				date_add('".date(Yii::app()->params['datetodb'], strtotime($_POST['date']))."',interval b.paydays day) as duedate
				from addressbook a 
				left join paymentmethod b on b.paymentmethodid = a.paymentmethodid 
				left join address c on c.addressbookid = a.addressbookid
				left join city d on d.cityid = c.cityid
				where a.addressbookid = ".$_POST['addressbookid']." 
				limit 1";
		$address = Yii::app()->db->createCommand($sql)->queryRow();
		echo CJSON::encode(array(
			'paymentmethodid' => $address['paymentmethodid'],
			'duedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($address['duedate'])),
		));
  }
  public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Approveinvoiceap(:vid,:vdatauser)';
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
    $sql = "select a.invoiceapid,
						ifnull(b.companyname,'-')as company,
						ifnull(a.invoiceapno,'-')as invoiceapno,
						ifnull(a.pono,'-')as pono,d.fullname,
						a.invoiceapdate,a.receiptdate,a.duedate,
						ifnull(a.headernote,'-')as headernote,a.recordstatus,a.companyid,
						a.contractno, a.invoiceaptaxno, a.taxno, a.nobuktipotong, a.beritaacara, a.paycode, a.discount, a.dpamount
						from invoiceap a
						left join plant c on c.plantid = a.plantid
						left join company b on b.companyid = c.companyid
						left join addressbook d on d.addressbookid = a.addressbookid
						";
		$invoiceapid = filter_input(INPUT_GET,'invoiceapid');
		$companyname = filter_input(INPUT_GET,'companyname');
		$invoiceapno = filter_input(INPUT_GET,'invoiceapno');
		$pono = filter_input(INPUT_GET,'pono');
		$invoiceapdate = filter_input(INPUT_GET,'invoiceapdate');
		$sql .= " where coalesce(a.invoiceapid,'') like '%".$invoiceapid."%' 
			and coalesce(b.companyname,'') like '%".$companyname."%'
			and coalesce(a.invoiceapno,'') like '%".$invoiceapno."%'
			and coalesce(a.pono,'') like '%".$pono."%'
			and coalesce(a.invoiceapdate,'') like '%".$invoiceapdate."%'
			";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.invoiceapid in (" . $_GET['id'] . ")";
    }
    $debit            = 0;
    $credit           = 0;
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('invoiceap');
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->AddPage('L','legal');
    $this->pdf->setFont('Arial', 'B', 9);
    $this->pdf->AliasNBPages();
    foreach ($dataReader as $row) {
      //$this->pdf->SetFontSize(10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No Doc ');
      $this->pdf->text(40, $this->pdf->gety() + 5, ': ' . $row['invoiceapno']);
			$this->pdf->text(15, $this->pdf->gety() + 10, 'Tgl Doc ');
      $this->pdf->text(40, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoiceapdate'])));
      $this->pdf->text(15, $this->pdf->gety() + 15, 'Ref No ');
      $this->pdf->text(40, $this->pdf->gety() + 15, ': ' . $row['pono']);
			$this->pdf->text(15, $this->pdf->gety() + 20, 'No Faktur ');
      $this->pdf->text(40, $this->pdf->gety() + 20, ': ' . $row['invoiceaptaxno']);
			$this->pdf->text(15, $this->pdf->gety() + 25, 'NPWP ');
      $this->pdf->text(40, $this->pdf->gety() + 25, ': ' . $row['taxno']);
      $this->pdf->text(15, $this->pdf->gety() + 30, 'Supplier ');
      $this->pdf->text(40, $this->pdf->gety() + 30, ': ' . $row['fullname']);
			
			$this->pdf->text(90, $this->pdf->gety() + 5, 'No Kontrak ');
      $this->pdf->text(110, $this->pdf->gety() + 5, ': ' . $row['contractno']);
			$this->pdf->text(90, $this->pdf->gety() + 10, 'No Bukti Pot ');
      $this->pdf->text(110, $this->pdf->gety() + 10, ': ' . $row['nobuktipotong']);
      $this->pdf->text(90, $this->pdf->gety() + 15, 'Tgl Terima ');
      $this->pdf->text(110, $this->pdf->gety() + 15, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['receiptdate'])));
			$this->pdf->text(90, $this->pdf->gety() + 20, 'Tgl Akhir ');
      $this->pdf->text(110, $this->pdf->gety() + 20, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['duedate'])));
			$this->pdf->text(90, $this->pdf->gety() + 25, 'T.O.P ');
      $this->pdf->text(110, $this->pdf->gety() + 25, ': ' . $row['paycode']);
			
			$this->pdf->text(150, $this->pdf->gety() + 5, 'DP ');
      $this->pdf->text(170, $this->pdf->gety() + 5, ': ' . $row['dpamount']);
			$this->pdf->text(150, $this->pdf->gety() + 10, 'Diskon ');
      $this->pdf->text(170, $this->pdf->gety() + 10, ': ' . $row['discount']);
			$this->pdf->text(150, $this->pdf->gety() + 15, 'Berita Acara ');
      $this->pdf->text(170, $this->pdf->gety() + 15, ': ' . $row['beritaacara']);

      $sql1   = "select b.accountcode,b.accountname, a.debit,a.credit,c.symbol,a.detailnote,a.ratevalue
							from invoiceapjurnal a
							left join account b on b.accountid = a.accountid
							left join currency c on c.currencyid = a.currencyid
							where a.invoiceapid = '" . $row['invoiceapid'] . "'
							order by invoiceapjurnalid ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
			$this->pdf->setFont('Arial', 'B', 8);
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
        80,
        40,
        40,
        10,
        150
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
          Yii::app()->format->formatCurrency($row1['ratevalue']),
          $row1['detailnote']
        ));
      }
      $this->pdf->row(array(
        '',
        'Total',
        Yii::app()->format->formatNumber($debit),
        Yii::app()->format->formatNumber($credit),
        '',
        ''
      ));
			$sql1        = "select b.productname, a.qty, a.qty2, a.qty3, a.qty4, a.price,a.discount,c.currencyname, a.ratevalue, a.dpp,c.symbol, a.total, a.detailnote,
							d.uomcode,e.uomcode as uom2code,f.uomcode as uom3code,g.uomcode as uom4code, j.grno
							from invoiceapdetail a
							left join currency c on c.currencyid = a.currencyid
							join unitofmeasure d on d.unitofmeasureid = a.uomid
							left join unitofmeasure e on e.unitofmeasureid = a.uom2id
							left join unitofmeasure f on f.unitofmeasureid = a.uom3id
							left join unitofmeasure g on g.unitofmeasureid = a.uom4id
							left join invoiceapgr i on i.invoiceapgrid = a.invoiceapgrid
							left join grheader j on j.grheaderid = i.grheaderid
							join product b on b.productid = a.productid
							where a.invoiceapid = '" . $row['invoiceapid'] . "'
							order by productname ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
			$totalqty =0;
				$totalqty2 = 0;
				$totalqty3 = 0;
				$totalqty4 = 0;
				$totaldpp = 0;
				$total2 = 0;
			
			 $this->pdf->setFont('Arial','B', 8);
			 $this->pdf->text(10,$this->pdf->gety()+8,'DETAIL');
			 $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
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
        7,
        70,
        20,
        18,
        18,
        18,
        18,
        20,	
        20,	
        30,	
        30,		
        15,	
        40
      ));
      $this->pdf->colheader = array(
        'No',
        'Product',
        'No LPB',
        'Qty',
        'Qty 2',
        'Qty 3',
        'Qty 4',
        'Price',
        'Diskon',
        'DPP',
        'Total',
        'Rate',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->coldetailalign = array(
        'C',
        'L',
        'L',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'C',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i      = $i + 1;
        //$debit  = $debit + ($row1['debit'] * $row1['ratevalue']);
        //$credit = $credit + ($row1['credit'] * $row1['ratevalue']);
				$totalqty += $row1['qty'];
				$totalqty2 += $row1['qty2'];
				$totalqty3 += $row1['qty3'];
				$totalqty4 += $row1['qty4'];
				$totaldpp += $row1['dpp'];
				$total2 += $row1['total'];
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          $row1['grno'],
					 Yii::app()->format->formatCurrency($row1['qty']),
           Yii::app()->format->formatCurrency($row1['qty2']),
					 Yii::app()->format->formatCurrency($row1['qty3']),
          Yii::app()->format->formatCurrency($row1['qty4']),
         Yii::app()->format->formatCurrency($row1['price'], $row1['symbol']),
				  Yii::app()->format->formatCurrency($row1['discount']),
          Yii::app()->format->formatCurrency($row1['dpp']),
          Yii::app()->format->formatCurrency($row1['total']),
         Yii::app()->format->formatCurrency($row1['ratevalue']), 
          $row1['detailnote']
        ));
      }
			$this->pdf->row(array(
        '',
        'Total','',
        '',
        '',
        '',
        '',
        '',
				'',
				Yii::app()->format->formatCurrency($totaldpp),
				Yii::app()->format->formatCurrency($total2)
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
      $this->pdf->text(20, $this->pdf->gety() + 20, 'Approved By');
      $this->pdf->text(170, $this->pdf->gety() + 20, 'Proposed By');
      $this->pdf->text(20, $this->pdf->gety() + 40, '_____________ ');
      $this->pdf->text(170, $this->pdf->gety() + 40, '_____________');
      $this->pdf->CheckNewPage(10);
    }
    $this->pdf->Output();
  }
}