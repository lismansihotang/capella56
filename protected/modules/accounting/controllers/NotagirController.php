<?php
class NotagirController extends Controller {
  public $menuname = 'notagir';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndextrxcb() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->searchtrxcb();
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
			'notagirid' => $id
		));
  }
  public function search() {
    header('Content-Type: application/json');
    $notagirid 		= GetSearchText(array('POST','GET'),'notagirid','','int');
    $companycode 		= GetSearchText(array('POST','Q'),'companycode');
    $addressbookid 		= GetSearchText(array('POST','Q'),'addressbookid');
    $customer 		= GetSearchText(array('POST','Q'),'customer');
    $headernote 		= GetSearchText(array('POST','Q'),'headernote');
    $notagirdate 		= GetSearchText(array('POST','Q'),'notagirdate');
    $plantcode 		= GetSearchText(array('POST','Q'),'plantcode');
    $sono 		= GetSearchText(array('POST','Q'),'sono');
    $notagirno 		= GetSearchText(array('POST','Q'),'notagirno');
    $invoicearno 		= GetSearchText(array('POST','Q'),'invoicearno');
    $invoiceartaxno 		= GetSearchText(array('POST','Q'),'invoiceartaxno');
    $recordstatus 		= GetSearchText(array('POST','Q'),'recordstatus');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','notagirid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset       	= ($page - 1) * $rows;
    $result       	= array();
    $row          	= array();

		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('notagir t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('plant c', 'c.plantid = t.plantid')
      ->leftjoin('company d', 'd.companyid = c.companyid')
      ->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
      ->leftjoin('giretur f', 'f.gireturid = t.gireturid')
      ->leftjoin('invoicear g', 'g.invoicearid = t.invoicearid')
			->where("
				((coalesce(d.companycode,'') like :companycode) and 
				(coalesce(t.notagirid,'') like :notagirid) and 
				(coalesce(a.fullname,'') like :customer) and 
				(coalesce(t.headernote,'') like :headernote) and 
				(coalesce(c.plantcode,'') like :plantcode) and 
				(coalesce(e.sono,'') like :sono) and 
				(coalesce(t.notagirdate,'') like :notagirdate) and 
				(coalesce(g.invoicearno,'') like :invoicearno) and 
				(coalesce(g.invoiceartaxno,'') like :invoiceartaxno) and 
				(coalesce(t.notagirno,'') like :notagirno))
					and t.recordstatus in (".getUserRecordStatus('listnotagir').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					(($addressbookid != '%%')?" and t.addressbookid = '".$addressbookid."'":'').
					"
					and d.companyid in (".getUserObjectValues('company').")
					", array(
					':notagirid' => '%' . $notagirid . '%',
					':companycode' => '%' . $companycode . '%',
					':customer' => '%' . $customer . '%',
					':headernote' => '%' . $headernote . '%',
					':plantcode' => '%' . $plantcode . '%',
					':sono' => '%' . $sono . '%',
					':notagirdate' => '%' . $notagirdate . '%',
					':invoicearno' => '%' . $invoicearno . '%',
					':invoiceartaxno' => '%' . $invoiceartaxno . '%',
					':notagirno' => '%' . $notagirno . '%',
				))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.fullname,c.plantcode,d.companyid,d.companycode,e.sono,f.gireturno,g.invoicearno,g.invoiceartaxno')
			->from('notagir t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('plant c', 'c.plantid = t.plantid')
      ->leftjoin('company d', 'd.companyid = c.companyid')
      ->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
			  ->leftjoin('giretur f', 'f.gireturid = t.gireturid')
        ->leftjoin('invoicear g', 'g.invoicearid = t.invoicearid')
        ->where("
          ((coalesce(d.companycode,'') like :companycode) and 
          (coalesce(t.notagirid,'') like :notagirid) and 
          (coalesce(a.fullname,'') like :customer) and 
          (coalesce(t.headernote,'') like :headernote) and 
          (coalesce(c.plantcode,'') like :plantcode) and 
          (coalesce(e.sono,'') like :sono) and 
          (coalesce(t.notagirdate,'') like :notagirdate) and 
          (coalesce(g.invoicearno,'') like :invoicearno) and 
          (coalesce(g.invoiceartaxno,'') like :invoiceartaxno) and 
          (coalesce(t.notagirno,'') like :notagirno))
            and t.recordstatus in (".getUserRecordStatus('listnotagir').")".
            (($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
            (($addressbookid != '%%')?" and t.addressbookid = '".$addressbookid."'":'').
            "
            and d.companyid in (".getUserObjectValues('company').")
            ", array(
            ':notagirid' => '%' . $notagirid . '%',
            ':companycode' => '%' . $companycode . '%',
            ':customer' => '%' . $customer . '%',
            ':headernote' => '%' . $headernote . '%',
            ':plantcode' => '%' . $plantcode . '%',
            ':sono' => '%' . $sono . '%',
            ':notagirdate' => '%' . $notagirdate . '%',
            ':invoicearno' => '%' . $invoicearno . '%',
            ':invoiceartaxno' => '%' . $invoiceartaxno . '%',
            ':notagirno' => '%' . $notagirno . '%',
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'notagirid' => $data['notagirid'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'soheaderid' => $data['soheaderid'],
        'sono' => $data['sono'],
				'gireturid' => $data['gireturid'],
        'gireturno' => $data['gireturno'],
        'notagirdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['notagirdate'])),
        'notagirno' => $data['notagirno'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'pocustno' => $data['pocustno'],
        'invoicearid' => $data['invoicearid'],
        'invoicearno' => $data['invoicearno'],
        'invoiceartaxno' => $data['invoiceartaxno'],
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
  public function searchtrxcb() {
    header('Content-Type: application/json');
    $notagirid 		= GetSearchText(array('Q'),'notagirid','','int');
    $addressbookid 		= GetSearchText(array('GET'),'addressbookid',0,'int');
    $plantid 		= GetSearchText(array('GET'),'plantid',0,'int');
    $companyid 		= GetSearchText(array('GET'),'companyid',0,'int');
    $customer 		= GetSearchText(array('Q'),'customer');
    $headernote 		= GetSearchText(array('Q'),'headernote');
    $sono 		= GetSearchText(array('Q'),'sono');
    $notagirno 		= GetSearchText(array('Q'),'notagirno');
    $result       	= array();
    $row          	= array();

		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('notagir t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('plant c', 'c.plantid = t.plantid')
      ->leftjoin('company d', 'd.companyid = c.companyid')
      ->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
      ->leftjoin('giretur f', 'f.gireturid = t.gireturid')
      ->leftjoin('invoicear g','g.invoicearid = t.invoicearid')
			->where("
				((coalesce(t.notagirid,'') like :notagirid) or
				(coalesce(a.fullname,'') like :customer) or 
				(coalesce(t.headernote,'') like :headernote) or 
				(coalesce(e.sono,'') like :sono) or 
				(coalesce(t.notagirno,'') like :notagirno))
					and t.recordstatus in (".getUserRecordStatus('listnotagir').")".
					(($plantid != 0)?" and t.plantid = ".$plantid : '').
					(($companyid != 0)?" and c.companyid = ".$companyid : '').
					" and t.addressbookid = ".$addressbookid." 
					and d.companyid in (".getUserObjectValues('company').")
					", array(
					':notagirid' => '%' . $notagirid . '%',
					':customer' => '%' . $customer . '%',
					':headernote' => '%' . $headernote . '%',
					':sono' => '%' . $sono . '%',
					':notagirno' => '%' . $notagirno . '%',
				))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.fullname,c.plantcode,d.companyid,d.companycode,e.sono,f.gireturno,g.invoicearno,g.invoiceartaxno')
			->from('notagir t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('plant c', 'c.plantid = t.plantid')
      ->leftjoin('company d', 'd.companyid = c.companyid')
      ->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
      ->leftjoin('giretur f', 'f.gireturid = t.gireturid')
      ->leftjoin('invoicear g','g.invoicearid = t.invoicearid')
			->where("
      ((coalesce(t.notagirid,'') like :notagirid) or
      (coalesce(a.fullname,'') like :customer) or 
      (coalesce(t.headernote,'') like :headernote) or 
      (coalesce(e.sono,'') like :sono) or 
      (coalesce(t.notagirno,'') like :notagirno))
        and t.recordstatus in (".getUserRecordStatus('listnotagir').")".
        (($plantid != 0)?" and t.plantid = ".$plantid : '').
        (($companyid != 0)?" and c.companyid = ".$companyid : '').
        " and t.addressbookid = ".$addressbookid." 
        and d.companyid in (".getUserObjectValues('company').")
        ", array(
        ':notagirid' => '%' . $notagirid . '%',
        ':customer' => '%' . $customer . '%',
        ':headernote' => '%' . $headernote . '%',
        ':sono' => '%' . $sono . '%',
        ':notagirno' => '%' . $notagirno . '%',
      ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'notagirid' => $data['notagirid'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'soheaderid' => $data['soheaderid'],
        'sono' => $data['sono'],
				'gireturid' => $data['gireturid'],
        'gireturno' => $data['gireturno'],
        'notagirdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['notagirdate'])),
        'notagirno' => $data['notagirno'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'pocustno' => $data['pocustno'],
        'invoiceartaxno' => $data['invoiceartaxno'],
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'notagirtaxid';
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('notagirtax t')->leftjoin('tax a', 'a.taxid = t.taxid')->where("(notagirid = :notagirid)", array(
      ':notagirid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.taxcode,a.taxvalue,a.description')->from('notagirtax t')->leftjoin('tax a', 'a.taxid = t.taxid')->where("(notagirid = :notagirid)", array(
      ':notagirid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'notagirtaxid' => $data['notagirtaxid'],
        'notagirid' => $data['notagirid'],
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'notagirdetailid';
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
		->from('notagirdetail t')
		->leftjoin('product a', 'a.productid = t.productid')
		->leftjoin('currency b', 'b.currencyid = t.currencyid')
		->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uomid')
		->leftjoin('unitofmeasure d', 'd.unitofmeasureid = t.uom2id')
		->leftjoin('unitofmeasure e', 'e.unitofmeasureid = t.uom3id')
		->leftjoin('unitofmeasure f', 'f.unitofmeasureid = t.uom4id')
		->leftjoin('materialtype h', 'h.materialtypeid = a.materialtypeid')
		->leftjoin('sloc i', 'i.slocid = t.slocid')
		->where("(notagirid = :notagirid)", array(
      ':notagirid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()
		->db->createCommand()
		->select('t.*,a.productcode,b.currencyname,b.symbol,c.uomcode,d.uomcode as uom2code,e.uomcode as uom3code,f.uomcode as uom4code,i.sloccode,
		h.materialtypecode')
		->from('notagirdetail t')
		->leftjoin('product a', 'a.productid = t.productid')
		->leftjoin('currency b', 'b.currencyid = t.currencyid')
		->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uomid')
		->leftjoin('unitofmeasure d', 'd.unitofmeasureid = t.uom2id')
		->leftjoin('unitofmeasure e', 'e.unitofmeasureid = t.uom3id')
		->leftjoin('unitofmeasure f', 'f.unitofmeasureid = t.uom4id')
			->leftjoin('materialtype h', 'h.materialtypeid = a.materialtypeid')
			->leftjoin('sloc i', 'i.slocid = t.slocid')
		->where("(notagirid = :notagirid)", array(
      ':notagirid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'notagirdetailid' => $data['notagirdetailid'],
        'notagirid' => $data['notagirid'],
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
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
        'price' => Yii::app()->format->formatNumber($data['price']),
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
		$sql = "select sum(qty) as qty, sum(qty2) as qty2, sum(qty3) as qty3, sum(qty4) as qty4, sum(dpp) as dpp, sum(GetAmountDetailByNotaGIR(zz.notagirid,zz.notagirdetailid)) as total  
		from notagirdetail zz where zz.notagirid = ".$id;
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'notagirjurnalid';
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('notagirjurnal t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->leftjoin('company c', 'c.companyid = a.companyid')->where("(notagirid = :notagirid) and t.accountid > 0", array(
      ':notagirid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.accountcode,a.accountname,b.currencyname,c.companyname,b.symbol')->from('notagirjurnal t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->leftjoin('company c', 'c.companyid = a.companyid')->where("(notagirid = :notagirid) and t.accountid > 0", array(
      ':notagirid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'notagirjurnalid' => $data['notagirjurnalid'],
        'notagirid' => $data['notagirid'],
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
		$sql = "select sum(debit) as debit, sum(credit) as credit from notagirjurnal where notagirid = ".$id;
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
		$sql     = 'call Modifnotagir(:vid,:vnotagirno,:vnotagirdate,:vplantid,:vaddressbookid,:vsoheaderid,:vpocustno,:vgireturid,:vinvoicearid,:vheadernote,:vdatauser)';
		$command = $connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vnotagirno', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vnotagirdate', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vsoheaderid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vpocustno', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vgireturid', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vinvoicearid', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-notagir"]["name"]);
		if (move_uploaded_file($_FILES["file-notagir"]["tmp_name"], $target_file)) {
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
					$abid = Yii::app()->db->createCommand("select notagirid from notagir 
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
				GetMessage(true,implode(" ",$e->errorInfo));
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
			$this->ModifyData($connection,array((isset($_POST['notagir-notagirid'])?$_POST['notagir-notagirid']:''),
			$_POST['notagir-notagirno'],
			date(Yii::app()->params['datetodb'], strtotime($_POST['notagir-notagirdate'])),
				$_POST['notagir-plantid'],
				$_POST['notagir-addressbookid'],
				$_POST['notagir-soheaderid'],
				$_POST['notagir-pocustno'],
				$_POST['notagir-gireturid'],
				$_POST['notagir-invoicearid'],
				$_POST['notagir-headernote']
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
			$sql     = 'call Insertnotagirtax(:vnotagirid,:vtaxid,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatenotagirtax(:vid,:vnotagirid,:vtaxid,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vnotagirid', $arraydata[1], PDO::PARAM_STR);
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
			$this->ModifyDataTax($connection,array((isset($_POST['notagirtaxid'])?$_POST['notagirtaxid']:''),
				$_POST['notagirid'],$_POST['taxid']));
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
			$sql     = 'call Insertnotagirdetail(:vnotagirid,:vslocid,:vproductid,:vqty,:vuomid,:vqty2,:vuom2id,:vqty3,:vuom3id,:vqty4,:vuom4id,
				:vprice,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatenotagirdetail(:vid,:vnotagirid,:vslocid,:vproductid,:vqty,:vuomid,:vqty2,:vuom2id,:vqty3,:vuom3id,:vqty4,:vuom4id,
				:vprice,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vnotagirid', $arraydata[1], PDO::PARAM_STR);
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
		$command->bindvalue(':vcurrencyid', $arraydata[13], PDO::PARAM_STR);
		$command->bindvalue(':vratevalue', $arraydata[14], PDO::PARAM_STR);
		$command->bindvalue(':vdetailnote', $arraydata[15], PDO::PARAM_STR);
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
			$this->ModifyDataDetail($connection,array((isset($_POST['notagirdetailid'])?$_POST['notagirdetailid']:''),
				$_POST['notagirid'],
				$_POST['slocid'],
				$_POST['productid'],
				$_POST['qty'],$_POST['uomid'],
				$_POST['qty2'],$_POST['uom2id'],
				$_POST['qty3'],$_POST['uom3id'],
				$_POST['qty4'],$_POST['uom4id'],
				$_POST['price'],
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
			$sql     = 'call Insertnotagirjurnal(:vnotagirid,:vaccountid,:vdebit,:vcredit,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatenotagirjurnal(:vid,:vnotagirid,:vaccountid,:vdebit,:vcredit,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vnotagirid', $arraydata[1], PDO::PARAM_STR);
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
			$this->ModifyDataJurnal($connection,array((isset($_POST['notagirjurnalid'])?$_POST['notagirjurnalid']:''),
				$_POST['notagirid'],$_POST['accountid'],
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
				$sql     = 'call Purgenotagir(:vid,:vdatauser)';
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
        $sql     = 'call Purgenotagirtax(:vid,:vdatauser)';
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
        $sql     = 'call Purgenotagirdetail(:vid,:vdatauser)';
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
  public function actionPurgejurnal() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgenotagirjurnal(:vid,:vdatauser)';
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
        $sql     = 'call Rejectnotagir(:vid,:vdatauser)';
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
        $sql     = 'call Approvenotagir(:vid,:vdatauser)';
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
        $sql     = 'call GenerateNGIRGIR(:vid, :vhid, :vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_INT);
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
  public function actionGenerateinvoiceartaxno() {
		$sql = "select a.taxno,concat(c.addressname,' - ',d.cityname) as addressname,a.paymentmethodid,date_add('".$newDate."',interval b.paydays day) as duedate
			from addressbook a 
			join paymentmethod b on b.paymentmethodid = a.paymentmethodid 
			left join address c on c.addressbookid = a.addressbookid
			left join city d on d.cityid = c.cityid
			where a.addressbookid = ".$_POST['id']." 
			limit 1";
		$address = Yii::app()->db->createCommand($sql)->queryRow();
    if (Yii::app()->request->isAjaxRequest) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $command = $connection->createCommand($sql);
        $command->execute();
        $transaction->commit();
      }
      catch (Exception $e) {
        $transaction->rollBack();
      }
      echo CJSON::encode(array(
        'taxno' => $address['taxno'],
        'addressname' => $address['addressname'],
        'paymentmethodid' => $address['paymentmethodid'],
        'duedate' => $address['duedate'],
      ));
      Yii::app()->end();
    }
  }
	public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select a.notagirid,
						ifnull(c.companyname,'-')as companyname,
						ifnull(a.notagirno,'-')as notagirno,
						d.invoiceartaxno,
						ifnull(a.pocustno,'-')as pocustno,
						a.notagirdate,
						ifnull(a.headernote,'-')as note,a.recordstatus,c.companyid
						from notagir a
						left join plant b on b.plantid = a.plantid
            left join company c on c.companyid = b.companyid 
            left join invoicear d on d.invoicearid = a.invoicearid 
            ";
		$notagirid = filter_input(INPUT_GET,'notagirid');
		$companyname = filter_input(INPUT_GET,'companyname');
		$notagirno = filter_input(INPUT_GET,'notagirno');
		$invoiceartaxno = filter_input(INPUT_GET,'invoiceartaxno');
		$notagirdate = filter_input(INPUT_GET,'notagirdate');
		$sql .= " where coalesce(a.notagirid,'') like '%".$notagirid."%' 
			and coalesce(c.companyname,'') like '%".$companyname."%'
			and coalesce(a.notagirno,'') like '%".$notagirno."%'
			and coalesce(d.invoiceartaxno,'') like '%".$invoiceartaxno."%'
			and coalesce(a.notagirdate,'') like '%".$notagirdate."%'
			";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.notagirid in (" . $_GET['id'] . ")";
    }
    $debit            = 0;
    $credit           = 0;
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('notagir');
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->AddPage('L');
    $this->pdf->AliasNBPages();
    foreach ($dataReader as $row) {
       $this->pdf->setFont('Arial','B', 9);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No Dokumen ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['notagirno']);
      $this->pdf->text(100, $this->pdf->gety() + 5, 'Ref No ');
      $this->pdf->text(125, $this->pdf->gety() + 5, ': ' . $row['invoiceartaxno']);
			$this->pdf->text(100, $this->pdf->gety() + 10, 'PO Customer ');
      $this->pdf->text(125, $this->pdf->gety() + 10, ': ' . $row['pocustno']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Tgl Dokumen ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['notagirdate'])));
			
      $sql1        = "select a.productname, a.qty, a.qty2, a.qty3, a.qty4, a.price,c.currencyname, a.ratevalue, a.dpp,c.symbol, a.total, a.detailnote,
							d.uomcode,e.uomcode as uom2code,f.uomcode as uom3code,g.uomcode as uom4code
							from notagirdetail a
							left join currency c on c.currencyid = a.currencyid
							join unitofmeasure d on d.unitofmeasureid = a.uomid
							left join unitofmeasure e on e.unitofmeasureid = a.uom2id
							left join unitofmeasure f on f.unitofmeasureid = a.uom3id
							left join unitofmeasure g on g.unitofmeasureid = a.uom4id
							where a.notagirid = '" . $row['notagirid'] . "'
							order by productname ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
			$totalqty =0;
				$totalqty2 = 0;
				$totalqty3 = 0;
				$totalqty4 = 0;
				$totaldpp = 0;
				$total2 = 0;
      $this->pdf->sety($this->pdf->gety() + 15);
			 $this->pdf->setFont('Arial','B', 8);
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
        'C'
      );
      $this->pdf->setwidths(array(
        7,
        55,
        20,
        20,
        20,
        20,
        24,	
        25,	
        25,	
        20,	
        13,	
        35
      ));
      $this->pdf->colheader = array(
        'No',
        'Product',
        'Qty',
        'Qty 2',
        'Qty 3',
        'Qty 4',
        'Price',
        'DPP',
        'Total',
        'Currency',
        'Rate',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->coldetailalign = array(
        'C',
        'L',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'C',
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
					 Yii::app()->format->formatCurrency($row1['qty']),
           Yii::app()->format->formatCurrency($row1['qty2']),
					 Yii::app()->format->formatCurrency($row1['qty3']),
          Yii::app()->format->formatCurrency($row1['qty4']),
         Yii::app()->format->formatCurrency($row1['price'], $row1['symbol']),
          Yii::app()->format->formatCurrency($row1['dpp'], $row1['symbol']),
          Yii::app()->format->formatCurrency($row1['total'], $row1['symbol']),
					$row1['currencyname'],
         Yii::app()->format->formatCurrency($row1['ratevalue']),
          $row1['detailnote']
        ));
      }
			$this->pdf->row(array(
        '',
        'Total',
        Yii::app()->format->formatCurrency($totalqty),
        Yii::app()->format->formatCurrency($totalqty2),
        Yii::app()->format->formatCurrency($totalqty3),
        Yii::app()->format->formatCurrency($totalqty4),
        '',
				Yii::app()->format->formatCurrency($totaldpp),
				Yii::app()->format->formatCurrency($total2),
        '','',''
      ));
			$sql1 = "select ifnull(ba.accountcode,'-') as acccode ,ifnull(ba.accountname,'-') as accname, aa.debit,aa.credit,ca.symbol,aa.detailnote,aa.ratevalue
							from notagirjurnal aa
							left join account ba on ba.accountid = aa.accountid
							left join currency ca on ca.currencyid = aa.currencyid
							where aa.notagirid = '" . $row['notagirid'] . "'
							order by notagirjurnalid ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
			$this->pdf->setFont('Arial','B', 8);
			$this->pdf->text(10,$this->pdf->gety()+8,'JURNAL');
      $this->pdf->sety($this->pdf->gety() + 10);
			 
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
        25,
        25,
        10,
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
          $row1['acccode'] . ' ' . $row1['accname'],
          Yii::app()->format->formatCurrency($row1['debit'], $row1['symbol']),
          Yii::app()->format->formatCurrency($row1['credit'], $row1['symbol']),
          Yii::app()->format->format(Yii::app()->params["defaultnumberprice"], $row1['ratevalue']),
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
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->border = false;
      $this->pdf->setwidths(array(
        20,
        175
      ));
      $this->pdf->row(array(
        'Note',
        $row['note']
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