<?php
class InvoicearController extends Controller {
  public $menuname = 'invoicear';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexsj() {
    parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchsj();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexpl() {
    parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchpl();
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
  public function actionIndexjurnal() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchjurnal();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexcbin() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->searchcbin();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexnotagir() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->searchnotagir();
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
    $pocustno 	= GetSearchText(array('POST','Q'),'pocustno');
    $plantcode 	= GetSearchText(array('POST','Q'),'plantcode');
    $invoicearno 	= GetSearchText(array('POST','Q'),'invoicearno');
    $sono 	= GetSearchText(array('POST','Q'),'sono');
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
				->leftjoin('soheader c', 'c.soheaderid = t.soheaderid')
				->where("
					((coalesce(t.invoicearid,'') like :invoicearid) and 
					(coalesce(t.invoiceardate,'') like :invoiceardate) and 
					(coalesce(a.fullname,'') like :customer) and 
					(coalesce(pocustno,'') like :pocustno) and 
					(coalesce(c.sono,'') like :sono) and 
					(coalesce(t.plantcode,'') like :plantcode) and 
					(coalesce(t.invoicearno,'') like :invoicearno) and 
					(coalesce(t.invoiceartaxno,'') like :invoiceartaxno))
						and t.doctype = 1
						and t.recordstatus in (".getUserRecordStatus('listinvar').")".
						(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
						"
						and t.companyid in (".getUserObjectValues('company').")
						", array(
						':invoicearid' => '%' . $invoicearid . '%',
						':invoiceardate' => '%' . $invoiceardate . '%',
						':customer' => '%' . $customer . '%',
						':pocustno' => '%' . $pocustno . '%',
						':sono' => '%' . $sono . '%',
						':plantcode' => '%' . $plantcode . '%',
						':invoicearno' => '%' . $invoicearno . '%',
						':invoiceartaxno' => '%' . $invoiceartaxno . '%'
					))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.fullname,b.cashbankno,c.pocustno,c.sono')
			->from('invoicear t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('cashbank b', 'b.cashbankid = t.cashbankid')
      ->leftjoin('soheader c', 'c.soheaderid = t.soheaderid')
			->where("
        ((coalesce(t.invoicearid,'') like :invoicearid) and 
        (coalesce(t.invoiceardate,'') like :invoiceardate) and 
        (coalesce(a.fullname,'') like :customer) and 
				(coalesce(pocustno,'') like :pocustno) and 
				(coalesce(c.sono,'') like :sono) and 
        (coalesce(t.plantcode,'') like :plantcode) and 
        (coalesce(t.invoicearno,'') like :invoicearno) and 
        (coalesce(t.invoiceartaxno,'') like :invoiceartaxno))
					and t.doctype = 1
				and t.recordstatus in (".getUserRecordStatus('listinvar').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					"
				and t.companyid in (".getUserObjectValues('company').")
					", array(
					':invoicearid' => '%' . $invoicearid . '%',
					':invoiceardate' => '%' . $invoiceardate . '%',
					':customer' => '%' . $customer . '%',
					':pocustno' => '%' . $pocustno . '%',
					':sono' => '%' . $sono . '%',
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
        'soheaderid' => $data['soheaderid'],
        'sono' => $data['sono'],
        'pocustno' => $data['pocustno'],
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
  public function actionsearchsj() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'invoicearsjid';
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('invoicearsj t')->leftjoin('giheader a', 'a.giheaderid = t.giheaderid')->where("(invoicearid = :invoicearid)", array(
      ':invoicearid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.gino')->from('invoicearsj t')->leftjoin('giheader a', 'a.giheaderid = t.giheaderid')->where("(invoicearid = :invoicearid)", array(
      ':invoicearid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'invoicearsjid' => $data['invoicearsjid'],
        'invoicearid' => $data['invoicearid'],
        'gino' => $data['gino'],
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function actionsearchpl() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'invoicearplid';
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('invoicearpl t')->leftjoin('packinglist a', 'a.packinglistid = t.packinglistid')->where("(invoicearid = :invoicearid)", array(
      ':invoicearid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.packinglistno')->from('invoicearpl t')->leftjoin('packinglist a', 'a.packinglistid = t.packinglistid')->where("(invoicearid = :invoicearid)", array(
      ':invoicearid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    $symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'invoicearplid' => $data['invoicearplid'],
        'invoicearid' => $data['invoicearid'],
        'invoicearsjid' => $data['invoicearsjid'],
        'invoicearplid' => $data['invoicearplid'],
        'packinglistno' => $data['packinglistno']
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
    $cmd             = Yii::app()->db->createCommand()
		->select('count(1) as total')
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
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
		->select('t.*,a.productcode,b.currencyname,b.symbol,c.uomcode,d.uomcode as uom2code,e.uomcode as uom3code,f.uomcode as uom4code,
		GetStdQty2(a.productid) as stdqty2,
						GetStdQty3(a.productid) as stdqty3,
						GetStdQty4(a.productid) as stdqty4,
		g.sloccode,h.materialtypecode')
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
				'stdqty2' => Yii::app()->format->formatNumber($data['stdqty2']),
				'stdqty3' => Yii::app()->format->formatNumber($data['stdqty3']),
				'stdqty4' => Yii::app()->format->formatNumber($data['stdqty4']),
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
      'total' => Yii::app()->format->formatNumber($cmd['total']),
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
	public function searchcbin() {
    header('Content-Type: application/json');
    $invoicearid 	= isset($_GET['q']) ? $_GET['q'] : '';
    $companycode 	= isset($_GET['q']) ? $_GET['q'] : '';
    $invoiceardate  = isset($_GET['q']) ? $_GET['q'] : '';
    $invoicearno  	= isset($_GET['q']) ? $_GET['q'] : '';
    $invoiceartaxno = isset($_GET['q']) ? $_GET['q'] : '';
    $companyid = isset($_GET['companyid']) ? $_GET['companyid'] : '';
    $addressbookid = isset($_GET['addressbookid']) ? $_GET['addressbookid'] : '';
    $page         	= isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows         	= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort         	= isset($_POST['sort']) ? strval($_POST['sort']) : 'invoicearid';
    $order        	= isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset       	= ($page - 1) * $rows;
    $result       	= array();
    $row          	= array();

		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('invoicear t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('cashbank b', 'b.cashbankid = t.cashbankid')
      ->leftjoin('soheader c', 'c.soheaderid = t.soheaderid')
			->where("
				((coalesce(t.companycode,'') like :companycode) 
				or (coalesce(t.invoiceardate,'') like :invoiceardate) 
				or (coalesce(t.invoicearno,'') like :invoicearno) 
				or (coalesce(t.invoiceartaxno,'') like :invoiceartaxno))
					and t.doctype > 0 
					and t.recordstatus in (".getUserRecordStatus('listinvar').")
					and t.companyid in (".getUserObjectValues('company').")
					and t.total > t.cashbankamount 
					and t.addressbookid = ".$addressbookid."
					and t.companyid = ".$companyid."
					", array(
					':companycode' => '%' . $companycode . '%',
					':invoiceardate' => '%' . $invoiceardate . '%',
					':invoicearno' => '%' . $invoicearno . '%',
					':invoiceartaxno' => '%' . $invoiceartaxno . '%'
				))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.fullname,b.cashbankno,c.pocustno,c.sono')
			->from('invoicear t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('cashbank b', 'b.cashbankid = t.cashbankid')
      ->leftjoin('soheader c', 'c.soheaderid = t.soheaderid')
			->where("
        ((coalesce(t.companycode,'') like :companycode) 
				or (coalesce(t.invoiceardate,'') like :invoiceardate) 
				or (coalesce(t.invoicearno,'') like :invoicearno) 
				or (coalesce(t.invoiceartaxno,'') like :invoiceartaxno))
					and t.doctype > 0
				and t.recordstatus in (".getUserRecordStatus('listinvar').")
				and t.companyid in (".getUserObjectValues('company').")
					and t.total > t.cashbankamount 
					and t.addressbookid = ".$addressbookid."
					and t.companyid = ".$companyid."
					", array(
					':companycode' => '%' . $companycode . '%',
					':invoiceardate' => '%' . $invoiceardate . '%',
					':invoicearno' => '%' . $invoicearno . '%',
					':invoiceartaxno' => '%' . $invoiceartaxno . '%'
				))->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'invoicearid' => $data['invoicearid'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'invoiceardate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['invoiceardate'])),
        'invoicearno' => $data['invoicearno'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'soheaderid' => $data['soheaderid'],
        'sono' => $data['sono'],
        'pocustno' => $data['pocustno'],
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
  public function searchnotagir() {
    header('Content-Type: application/json');
    $invoicearid 	= isset($_GET['q']) ? $_GET['q'] : '';
    $invoiceardate  = isset($_GET['q']) ? $_GET['q'] : '';
    $invoicearno  	= isset($_GET['q']) ? $_GET['q'] : '';
    $invoiceartaxno = isset($_GET['q']) ? $_GET['q'] : '';
		$plantid     		= GetSearchText(array('POST','GET'),'plantid',0,'int');
		$soheaderid      = GetSearchText(array('POST','GET'),'soheaderid',0,'int');
    $page         	= isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows         	= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort         	= isset($_POST['sort']) ? strval($_POST['sort']) : 'invoicearid';
    $order        	= isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset       	= ($page - 1) * $rows;
    $result       	= array();
    $row          	= array();

		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('invoicear t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('cashbank b', 'b.cashbankid = t.cashbankid')
      ->leftjoin('soheader c', 'c.soheaderid = t.soheaderid')
			->where("
				((coalesce(t.invoiceardate,'') like :invoiceardate) 
				or (coalesce(t.invoicearno,'') like :invoicearno) 
				or (coalesce(t.invoiceartaxno,'') like :invoiceartaxno))
					and t.recordstatus in (".getUserRecordStatus('listinvar').")".
					(($plantid != '')? " and t.plantid = ".$plantid : "").
					(($soheaderid != '')? " and t.soheaderid = ".$soheaderid : "")
					, array(
					':invoiceardate' => '%' . $invoiceardate . '%',
					':invoicearno' => '%' . $invoicearno . '%',
					':invoiceartaxno' => '%' . $invoiceartaxno . '%'
				))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.invoicearid,t.invoiceardate,t.invoicearno,t.invoiceartaxno')
			->from('invoicear t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('cashbank b', 'b.cashbankid = t.cashbankid')
      ->leftjoin('soheader c', 'c.soheaderid = t.soheaderid')
			->where("
        ((coalesce(t.invoiceardate,'') like :invoiceardate) 
				or (coalesce(t.invoicearno,'') like :invoicearno) 
				or (coalesce(t.invoiceartaxno,'') like :invoiceartaxno))
				and t.recordstatus in (".getUserRecordStatus('listinvar').")".
        (($plantid != '')? " and t.plantid = ".$plantid : "").
        (($soheaderid != '')? " and t.soheaderid = ".$soheaderid : ""), 
        array(
					':invoiceardate' => '%' . $invoiceardate . '%',
					':invoicearno' => '%' . $invoicearno . '%',
					':invoiceartaxno' => '%' . $invoiceartaxno . '%'
				))->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'invoicearid' => $data['invoicearid'],
        'invoiceardate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['invoiceardate'])),
        'invoicearno' => $data['invoicearno'],
        'invoiceartaxno' => $data['invoiceartaxno'],
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		$sql     = 'call Modifinvoicear(:vid,:vinvoicearno,:vinvoiceardate,:vplantid,:vaddressbookid,:vsoheaderid,:vinvoiceartaxno,:vcashbankid,:vdpamount,
			:vheadernote,:vdatauser)';
		$command = $connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vinvoicearno', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vinvoiceardate', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vsoheaderid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vinvoiceartaxno', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vcashbankid', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vdpamount', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[9], PDO::PARAM_STR);
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
			$this->ModifyData($connection,array(
				(isset($_POST['invoicear-invoicearid'])?$_POST['invoicear-invoicearid']:''),
				$_POST['invoicear-invoicearno'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['invoicear-invoiceardate'])),
				$_POST['invoicear-plantid'],
				$_POST['invoicear-addressbookid'],
				$_POST['invoicear-soheaderid'],
				$_POST['invoicear-invoiceartaxno'],
				$_POST['invoicear-cashbankid'],
				$_POST['invoicear-dpamount'],
				$_POST['invoicear-headernote']
			));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
	private function ModifyDataSj($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertinvoicearsj(:vinvoicearid,:vgiheaderid,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateinvoicearsj(:vid,:vinvoicearid,:vgiheaderid,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vinvoicearid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vgiheaderid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavesj() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataSj($connection,array((isset($_POST['invoicearsjid'])?$_POST['invoicearsjid']:''),
				$_POST['invoicearid'],$_POST['giheaderid']));
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
			$sql     = 'call InsertInvoicearUmumTax(:vinvoicearid,:vtaxid,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call UpdateInvoicearUmumTax(:vid,:vinvoicearid,:vtaxid,:vdatauser)';
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
			$sql     = 'call InsertInvoicearUmumDetail(:vinvoicearid,:vslocid,:vproductid,:vqty,:vuomid,:vqty2,:vuom2id,:vqty3,:vuom3id,:vqty4,:vuom4id,:vprice,
				:vdiscount,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call UpdateInvoicearUmumDetail(:vid,:vinvoicearid,:vslocid,:vproductid,:vqty,:vuomid,:vqty2,:vuom2id,:vqty3,:vuom3id,:vqty4,:vuom4id,
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
  private function ModifyDataJurnal($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call InsertInvoicearUmumJurnal(:vinvoicearid,:vaccountid,:vdebit,:vcredit,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call UpdateInvoicearUmumJurnal(:vid,:vinvoicearid,:vaccountid,:vdebit,:vcredit,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
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
	public function actionGeneratedetail() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $soid          = $_POST['soid'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
				$sql     = 'call Generateinvargi(:vid,:vsoid,:vdatauser)';
				$command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vsoid', $soid, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
				GetMessage(false, getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  public function actionPurgesj() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeinvoicearsj(:vid,:vdatauser)';
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
        $sql     = 'call Approveinvoicear(:vid,:vdatauser)';
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
    $sql = "select distinct a.invoicearid,c.companyname,e.paymentname,
						ifnull(a.invoicearno,'-')as invoicearno,d.pocustno,
						ifnull(d.sono,'-')as sono,h.cityname,now() as sekarang,
						a.invoiceardate,a.duedate,addressname,
						ifnull(a.headernote,'-') as headernote,a.recordstatus,b.companyid,
						a.contractno, a.invoiceartaxno, e.paycode, f.cashbankno, g.fullname as customer, a.taxno, a.dpamount
						from invoicear a
						join plant b on b.plantid = a.plantid 
						join company c on c.companyid = b.companyid
						join city h on h.cityid = c.cityid
						join soheader d on d.soheaderid = a.soheaderid
						left join paymentmethod e on e.paymentmethodid = a.paymentmethodid
						left join cashbank f on f.cashbankid = a.cashbankid
						left join addressbook g on g.addressbookid = a.addressbookid
						";
		$invoicearid = filter_input(INPUT_GET,'invoicearid');
		$companyname = filter_input(INPUT_GET,'companyname');
		$invoicearno = filter_input(INPUT_GET,'invoicearno');
		$sono = filter_input(INPUT_GET,'sono');
		$invoiceardate = filter_input(INPUT_GET,'invoiceardate');
		$sql .= " where coalesce(a.invoicearid,'') like '%".$invoicearid."%' 
			and coalesce(c.companyname,'') like '%".$companyname."%'
			and coalesce(a.invoicearno,'') like '%".$invoicearno."%'
			and coalesce(d.sono,'') like '%".$sono."%'
			and coalesce(a.invoiceardate,'') like '%".$invoiceardate."%'
			";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.invoicearid in (" . $_GET['id'] . ")";
    }
    $debit            = 0;
    $credit           = 0;
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('invoicear');
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->AddPage('P','A4');
    $this->pdf->setFont('Arial', 'B', 8);
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 9);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'NPWP ');
      $this->pdf->text(35, $this->pdf->gety() + 5, ':  ' . $row['taxno']);
			$this->pdf->text(15, $this->pdf->gety() + 10, 'Customer ');
      $this->pdf->text(35, $this->pdf->gety() + 10, ':  ' . $row['customer']);
			$this->pdf->text(15, $this->pdf->gety() + 15, 'Faktur Pajak ');
      $this->pdf->text(35, $this->pdf->gety() + 15, ':  ' . $row['invoiceartaxno']);
			
			$this->pdf->text(110, $this->pdf->gety() + 5, 'No Faktur ');
      $this->pdf->text(140, $this->pdf->gety() + 5, ':  ' . $row['invoicearno']);
      $this->pdf->text(110, $this->pdf->gety() + 10, 'Tanggal');
      $this->pdf->text(140, $this->pdf->gety() + 10, ':  ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoiceardate'])));
      $this->pdf->text(110, $this->pdf->gety() + 15, 'Nomer PO ');
      $this->pdf->text(140, $this->pdf->gety() + 15, ':  ' . $row['pocustno']);
			//$this->pdf->text(130, $this->pdf->gety() + 20, 'Surat Pengantar ');
      //$this->pdf->text(160, $this->pdf->gety() + 20, ':  ' . $row['gino']);
			$this->pdf->text(110, $this->pdf->gety() + 20, 'T.O.P ');
      $this->pdf->text(140, $this->pdf->gety() + 20, ':  ' . $row['paymentname']);
			$this->pdf->text(110, $this->pdf->gety() + 25, 'Jatuh Tempo ');
      $this->pdf->text(140, $this->pdf->gety() + 25, ':  ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['duedate'])));
			
			$sql1        = "select a.productname, a.qty, a.qty2, a.qty3, a.qty4, a.price,a.discount,c.currencyname, a.ratevalue, a.dpp,c.symbol, a.total, a.detailnote,
							d.uomcode,e.uomcode as uom2code,f.uomcode as uom3code,g.uomcode as uom4code, h.gino
							from invoiceardetail a
							left join currency c on c.currencyid = a.currencyid
							join unitofmeasure d on d.unitofmeasureid = a.uomid
							left join unitofmeasure e on e.unitofmeasureid = a.uom2id
							left join unitofmeasure f on f.unitofmeasureid = a.uom3id
							left join unitofmeasure g on g.unitofmeasureid = a.uom4id
							left join giheader h on h.giheaderid = a.giheaderid
							where a.invoicearid = '" . $row['invoicearid'] . "'
							order by productname ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
			$totalqty =0;
			$totalqty2 = 0;
			$totalqty3 = 0;
			$totalqty4 = 0;
			$totaldpp = 0;
			$total2 = 0;
			$totalvalue = 0;
			
			$this->pdf->setFont('Arial','B', 8);
			
			 $this->pdf->sety($this->pdf->gety() + 30);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
        
      );
      $this->pdf->setwidths(array(
        8,
        70,
        30,
        25,
        30,	
        35
        
      ));
			
      $this->pdf->colheader = array(
        'No',
        'Product',
        'No SP',
        'Qty',
        'Harga',
        'Total'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->coldetailalign = array(
        'C',
        'L',
        'L',
        'R',
        'R',
        'R'
        
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i      = $i + 1;
        //$debit  = $debit + ($row1['debit'] * $row1['ratevalue']);
        //$credit = $credit + ($row1['credit'] * $row1['ratevalue']);
				$totalqty += $row1['qty'];
				$totaldpp += $row1['dpp'];
				$totalvalue += $row1['total'];
				$total2 += ($row1['qty']*$row1['price']);
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          $row1['gino'],
					 Yii::app()->format->formatCurrency($row1['qty']),
         Yii::app()->format->formatCurrency($row1['price'], $row1['symbol']),
          Yii::app()->format->formatCurrency(($row1['qty']*$row1['price']), $row1['symbol'])
        ));
      }
			$this->pdf->sety($this->pdf->gety() + 5);
			 $this->pdf->setFont('Arial', 'B', 8);
			$this->pdf->row(array(
        '',
        '','',
        '',  
				'Jumlah Biaya : ',
				Yii::app()->format->formatCurrency($total2, $row1['symbol'])
      
      ));
			$this->pdf->row(array(
        '',
        '','',
        '',  
				'Diskon : ',
				Yii::app()->format->formatCurrency($row1['discount'], $row1['symbol'])
      ));
			$this->pdf->row(array(
        '',
        '','',
        '',  
				'DP : ',
				Yii::app()->format->formatCurrency(($row['dpamount']), $row1['symbol'])
      ));
			$this->pdf->row(array(
        '',
        '','',
        '',  
				'DPP : ',
				Yii::app()->format->formatCurrency($totaldpp, $row1['symbol'])
      ));
			$this->pdf->row(array(
        '',
        '','',
        '',  
				'PPn : ',
				Yii::app()->format->formatCurrency($totalvalue-$totaldpp, $row1['symbol'])
      ));
			$this->pdf->row(array(
        '',
        '','',
        '',  
				'Jumlah : ',
				Yii::app()->format->formatCurrency($totalvalue, $row1['symbol'])
      ));
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->border = false;
      $this->pdf->setwidths(array(
        20,
        175
      ));
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->text(15, $this->pdf->gety(), 'Note '.' :   '.$row['headernote']);
			$this->pdf->text(15, $this->pdf->gety() + 5, 'Sold To '.' :   '.$row['addressname']);
			
    
      $this->pdf->text(150, $this->pdf->gety() + 20,$row['cityname'].' , '.date(Yii::app()->params['dateviewfromdb'], strtotime($row['sekarang'])) );
      $this->pdf->text(150, $this->pdf->gety() + 45, '____________________');
      $this->pdf->CheckNewPage(10);
			$this->pdf->AddPage('P','A4');
    }
    $this->pdf->Output();
  }
}