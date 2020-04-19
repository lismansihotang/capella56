<?php
class NotagrrController extends Controller {
  public $menuname = 'notagrr';
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
  public function actionIndexgrr() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchgrr();
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
			'notagrrid' => $id
		));
  }
  public function search() {
    header('Content-Type: application/json');
    $notagrrid 		= GetSearchText(array('POST','GET'),'notagrrid','','int');
    $companyid 		= GetSearchText(array('POST','GET'),'companyid','0','int');
    $addressbookid 		= GetSearchText(array('POST','GET'),'addressbookid','0','int');
    $companycode 		= GetSearchText(array('POST','Q'),'companycode');
    $supplier 		= GetSearchText(array('POST','Q'),'supplier');
    $headernote 		= GetSearchText(array('POST','Q'),'headernote');
    $plantcode 		= GetSearchText(array('POST','Q'),'plantcode');
    $pono 		= GetSearchText(array('POST','Q'),'pono');
    $grrreturno 		= GetSearchText(array('POST','Q'),'grrreturno');
    $notagrrno 		= GetSearchText(array('POST','Q'),'notagrrno');
    $notagrrdate 		= GetSearchText(array('POST','Q'),'notagrrdate');
    $recordstatus 		= GetSearchText(array('POST','Q'),'recordstatus');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','notagrrid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset       	= ($page - 1) * $rows;
    $result       	= array();
    $row          	= array();

		if (!isset($_GET['combo'])) {
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('notagrr t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('plant c', 'c.plantid = t.plantid')
      ->leftjoin('company d', 'd.companyid = c.companyid')
      ->leftjoin('poheader e', 'e.poheaderid = t.poheaderid')
			->where("
				((coalesce(d.companycode,'') like :companycode) and 
				(coalesce(a.fullname,'') like :supplier) and 
				(coalesce(t.headernote,'') like :headernote) and 
				(coalesce(t.notagrrdate,'') like :notagrrdate) and 
				(coalesce(c.plantcode,'') like :plantcode) and 
				(coalesce(e.pono,'') like :pono) and 
				(coalesce(t.notagrrno,'') like :notagrrno))
					and t.recordstatus in (".getUserRecordStatus('listnotagrr').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					"
					and d.companyid in (".getUserObjectValues('company').")
					and t.poheaderid > 0
					", array(
					':companycode' => '%' . $companycode . '%',
					':supplier' => '%' . $supplier . '%',
					':headernote' => '%' . $headernote . '%',
					':notagrrdate' => '%' . $notagrrdate . '%',
					':plantcode' => '%' . $plantcode . '%',
					':pono' => '%' . $pono . '%',
					':notagrrno' => '%' . $notagrrno . '%',
				))->queryScalar();
		} else {
			$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('notagrr t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('plant c', 'c.plantid = t.plantid')
      ->leftjoin('company d', 'd.companyid = c.companyid')
      ->leftjoin('poheader e', 'e.poheaderid = t.poheaderid')
			->where("
				((coalesce(d.companycode,'') like :companycode) or 
				(coalesce(a.fullname,'') like :supplier) or 
				(coalesce(t.headernote,'') like :headernote) or 
				(coalesce(t.notagrrdate,'') like :notagrrdate) or 
				(coalesce(c.plantcode,'') like :plantcode) or 
				(coalesce(e.pono,'') like :pono) or 
				(coalesce(t.notagrrno,'') like :notagrrno))
          and t.recordstatus in (".getUserRecordStatus('listnotagrr').")".
          (($companyid != '0')?" and c.companyid = ".$companyid:'').
          (($addressbookid != '0')?" and a.addressbookid = ".$addressbookid:'').
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					"
					and d.companyid in (".getUserObjectValues('company').")
					and t.poheaderid > 0
					", array(
					':companycode' => '%' . $companycode . '%',
					':supplier' => '%' . $supplier . '%',
					':headernote' => '%' . $headernote . '%',
					':notagrrdate' => '%' . $notagrrdate . '%',
					':plantcode' => '%' . $plantcode . '%',
					':pono' => '%' . $pono . '%',
					':notagrrno' => '%' . $notagrrno . '%',
				))->queryScalar();
		}
    $result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.fullname,c.plantcode,d.companyid,d.companycode,e.pono')
			->from('notagrr t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('plant c', 'c.plantid = t.plantid')
      ->leftjoin('company d', 'd.companyid = c.companyid')
      ->leftjoin('poheader e', 'e.poheaderid = t.poheaderid')
			->where("
				((coalesce(d.companycode,'') like :companycode) and 
				(coalesce(a.fullname,'') like :supplier) and 
				(coalesce(t.headernote,'') like :headernote) and 
				(coalesce(t.notagrrdate,'') like :notagrrdate) and 
				(coalesce(c.plantcode,'') like :plantcode) and 
				(coalesce(e.pono,'') like :pono) and 
				(coalesce(t.notagrrno,'') like :notagrrno))
				and t.recordstatus in (".getUserRecordStatus('listnotagrr').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					"
				and d.companyid in (".getUserObjectValues('company').")
					and t.poheaderid > 0
					", array(
					':companycode' => '%' . $companycode . '%',
					':supplier' => '%' . $supplier . '%',
					':headernote' => '%' . $headernote . '%',
					':plantcode' => '%' . $plantcode . '%',
					':notagrrdate' => '%' . $notagrrdate . '%',
					':pono' => '%' . $pono . '%',
					':notagrrno' => '%' . $notagrrno . '%',
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		} else {
			$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.fullname,c.plantcode,d.companyid,d.companycode,e.pono')
			->from('notagrr t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('plant c', 'c.plantid = t.plantid')
      ->leftjoin('company d', 'd.companyid = c.companyid')
      ->leftjoin('poheader e', 'e.poheaderid = t.poheaderid')
			->where("
				((coalesce(d.companycode,'') like :companycode) or 
				(coalesce(a.fullname,'') like :supplier) or 
				(coalesce(t.headernote,'') like :headernote) or 
				(coalesce(t.notagrrdate,'') like :notagrrdate) or 
				(coalesce(c.plantcode,'') like :plantcode) or 
				(coalesce(e.pono,'') like :pono) or 
				(coalesce(t.notagrrno,'') like :notagrrno))
				and t.recordstatus in (".getUserRecordStatus('listnotagrr').")".
        (($companyid != '0')?" and c.companyid = ".$companyid:'').
        (($addressbookid != '0')?" and a.addressbookid = ".$addressbookid:'').
        (($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					"
				and d.companyid in (".getUserObjectValues('company').")
					and t.poheaderid > 0
					", array(
					':companycode' => '%' . $companycode . '%',
					':supplier' => '%' . $supplier . '%',
					':headernote' => '%' . $headernote . '%',
					':plantcode' => '%' . $plantcode . '%',
					':notagrrdate' => '%' . $notagrrdate . '%',
					':pono' => '%' . $pono . '%',
					':notagrrno' => '%' . $notagrrno . '%',
				))->order($sort . ' ' . $order)->queryAll();
		}
    foreach ($cmd as $data) {
      $row[] = array(
        'notagrrid' => $data['notagrrid'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'notagrrdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['notagrrdate'])),
        'notagrrno' => $data['notagrrno'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'invoiceaptaxno' => $data['invoiceaptaxno'],
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'notagrrtaxid';
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('notagrrtax t')->leftjoin('tax a', 'a.taxid = t.taxid')->where("(notagrrid = :notagrrid)", array(
      ':notagrrid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.taxcode,a.taxvalue,a.description')->from('notagrrtax t')->leftjoin('tax a', 'a.taxid = t.taxid')->where("(notagrrid = :notagrrid)", array(
      ':notagrrid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'notagrrtaxid' => $data['notagrrtaxid'],
        'notagrrid' => $data['notagrrid'],
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
	public function actionsearchgrr() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'notagrrgrrid';
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
			->from('notagrrgrr t')
			->leftjoin('grretur a', 'a.grreturid = t.grreturid')
			->where("(notagrrid = :notagrrid)", array(
      ':notagrrid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
			->select('t.*,a.grreturno,a.headernote')
			->from('notagrrgrr t')
			->leftjoin('grretur a', 'a.grreturid = t.grreturid')
			->where("(notagrrid = :notagrrid)", array(
      ':notagrrid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'notagrrgrrid' => $data['notagrrgrrid'],
        'notagrrid' => $data['notagrrid'],
        'grreturid' => $data['grreturid'],
        'grreturno' => $data['grreturno'],
        'headernote' => $data['headernote'],
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'notagrrdetailid';
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
		->from('notagrrdetail t')
		->leftjoin('product a', 'a.productid = t.productid')
		->leftjoin('currency b', 'b.currencyid = t.currencyid')
		->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uomid')
		->leftjoin('unitofmeasure d', 'd.unitofmeasureid = t.uom2id')
		->leftjoin('unitofmeasure e', 'e.unitofmeasureid = t.uom3id')
		->leftjoin('unitofmeasure f', 'f.unitofmeasureid = t.uom4id')
		->leftjoin('materialtype h', 'h.materialtypeid = a.materialtypeid')
		->leftjoin('sloc i', 'i.slocid = t.slocid')
		->where("(notagrrid = :notagrrid)", array(
      ':notagrrid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
   $cmd             = Yii::app()
		->db->createCommand()
		->select('t.*,a.productcode,b.currencyname,b.symbol,c.uomcode,d.uomcode as uom2code,e.uomcode as uom3code,f.uomcode as uom4code,
		h.materialtypecode,i.sloccode')
		->from('notagrrdetail t')
		->leftjoin('product a', 'a.productid = t.productid')
		->leftjoin('currency b', 'b.currencyid = t.currencyid')
		->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uomid')
		->leftjoin('unitofmeasure d', 'd.unitofmeasureid = t.uom2id')
		->leftjoin('unitofmeasure e', 'e.unitofmeasureid = t.uom3id')
		->leftjoin('unitofmeasure f', 'f.unitofmeasureid = t.uom4id')
		->leftjoin('materialtype h', 'h.materialtypeid = a.materialtypeid')
		->leftjoin('sloc i', 'i.slocid = t.slocid')
		->where("(notagrrid = :notagrrid)", array(
      ':notagrrid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'notagrrdetailid' => $data['notagrrdetailid'],
        'notagrrid' => $data['notagrrid'],
        'notagrrgrrid' => $data['notagrrgrrid'],
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
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'symbol' => $data['symbol'],
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
		$sql = "select sum(qty) as qty, sum(qty2) as qty2, sum(qty3) as qty3, sum(qty4) as qty4, sum(dpp) as dpp, sum(total) as total 
		from notagrrdetail where notagrrid = ".$id;
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'notagrrjurnalid';
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('notagrrjurnal t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->leftjoin('company c', 'c.companyid = a.companyid')->where("(notagrrid = :notagrrid) and t.accountid > 0", array(
      ':notagrrid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.accountcode,a.accountname,b.currencyname,c.companyname,b.symbol')->from('notagrrjurnal t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->leftjoin('company c', 'c.companyid = a.companyid')->where("(notagrrid = :notagrrid) and t.accountid > 0", array(
      ':notagrrid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'notagrrjurnalid' => $data['notagrrjurnalid'],
        'notagrrid' => $data['notagrrid'],
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
		$sql = "select sum(debit) as debit, sum(credit) as credit from notagrrjurnal where notagrrid = ".$id;
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
    $notagrrid 		= isset($_POST['notagrrid']) ? $_POST['notagrrid'] : '';
    $companycode 		= isset($_POST['companycode']) ? $_POST['companycode'] : '';
    $supplier				= isset($_POST['supplier']) ? $_POST['supplier'] : '';
    $headernote 		= isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $plantcode  		= isset($_POST['plantcode']) ? $_POST['plantcode'] : '';
    $pono 					= isset($_POST['pono']) ? $_POST['pono'] : '';
    $nobuktipotong 	= isset($_POST['nobuktipotong']) ? $_POST['nobuktipotong'] : '';
    $notagrrno  	= isset($_POST['notagrrno']) ? $_POST['notagrrno'] : '';
    $notagrrtaxno = isset($_POST['notagrrtaxno']) ? $_POST['notagrrtaxno'] : '';
    $page         	= isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows         	= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort         	= isset($_POST['sort']) ? strval($_POST['sort']) : 'notagrrid';
    $order        	= isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset       	= ($page - 1) * $rows;
    $result       	= array();
    $row          	= array();

		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('notagrr t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('cashbank b', 'b.cashbankid = t.cashbankid')
			->where("
				((coalesce(t.companycode,'') like :companycode) and 
				(coalesce(a.fullname,'') like :supplier) and 
				(coalesce(t.headernote,'') like :headernote) and 
				(coalesce(t.plantcode,'') like :plantcode) and 
				(coalesce(t.pono,'') like :pono) and 
				(coalesce(t.nobuktipotong,'') like :nobuktipotong) and 
				(coalesce(t.notagrrno,'') like :notagrrno) and 
				(coalesce(t.notagrrtaxno,'') like :notagrrtaxno))
					and t.recordstatus in (".getUserRecordStatus('listinvar').")
					and t.companyid in (".getUserObjectValues('company').")
					and t.isreqpay = 0
					and t.plantid = ".$_GET['plantid']."
					", array(
					':companycode' => '%' . $companycode . '%',
					':supplier' => '%' . $supplier . '%',
					':headernote' => '%' . $headernote . '%',
					':plantcode' => '%' . $plantcode . '%',
					':pono' => '%' . $pono . '%',
					':nobuktipotong' => '%' . $nobuktipotong . '%',
					':notagrrno' => '%' . $notagrrno . '%',
					':notagrrtaxno' => '%' . $notagrrtaxno . '%'
				))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.fullname,b.cashbankno')
			->from('notagrr t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('cashbank b', 'b.cashbankid = t.cashbankid')
			->where("
				((coalesce(t.companycode,'') like :companycode) and 
				(coalesce(a.fullname,'') like :supplier) and 
				(coalesce(t.headernote,'') like :headernote) and 
				(coalesce(t.plantcode,'') like :plantcode) and 
				(coalesce(t.pono,'') like :pono) and 
				(coalesce(t.nobuktipotong,'') like :nobuktipotong) and 
				(coalesce(t.notagrrno,'') like :notagrrno) and 
				(coalesce(t.notagrrtaxno,'') like :notagrrtaxno))
				and t.recordstatus in (".getUserRecordStatus('listinvar').")
				and t.companyid in (".getUserObjectValues('company').")
					and t.isreqpay = 0
					and t.plantid = ".$_GET['plantid']."
					", array(
					':companycode' => '%' . $companycode . '%',
					':supplier' => '%' . $supplier . '%',
					':headernote' => '%' . $headernote . '%',
					':plantcode' => '%' . $plantcode . '%',
					':pono' => '%' . $pono . '%',
					':nobuktipotong' => '%' . $nobuktipotong . '%',
					':notagrrno' => '%' . $notagrrno . '%',
					':notagrrtaxno' => '%' . $notagrrtaxno . '%'
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'notagrrid' => $data['notagrrid'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'notagrrdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['notagrrdate'])),
        'notagrrno' => $data['notagrrno'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'contractno' => $data['contractno'],
        'notagrrtaxno' => $data['notagrrtaxno'],
        'taxno' => $data['taxno'],
        'nobuktipotong' => $data['nobuktipotong'],
        'receiptdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['receiptdate'])),
        'beritaacara' => $data['beritaacara'],
        'paymentmethodid' => $data['paymentmethodid'],
        'paycode' => $data['paycode'],
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
  private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		$sql     = 'call Modifnotagrr(:vid,:vnotagrrno,:vnotagrrdate,:vplantid,:vaddressbookid,:vinvoiceaptaxno,:vpoheaderid,:vheadernote,:vdatauser)';
		$command = $connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vnotagrrno', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vnotagrrdate', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vinvoiceaptaxno', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vpoheaderid', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-notagrr"]["name"]);
		if (move_uploaded_file($_FILES["file-notagrr"]["tmp_name"], $target_file)) {
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
					$abid = Yii::app()->db->createCommand("select notagrrid from notagrr 
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
			$this->ModifyData($connection,array((isset($_POST['notagrr-notagrrid'])?$_POST['notagrr-notagrrid']:''),
			$_POST['notagrr-notagrrno'],
			date(Yii::app()->params['datetodb'], strtotime($_POST['notagrr-notagrrdate'])),
				$_POST['notagrr-plantid'],
				$_POST['notagrr-addressbookid'],
				$_POST['notagrr-invoiceaptaxno'],
				$_POST['notagrr-poheaderid'],
				$_POST['notagrr-headernote']
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
			$sql     = 'call Insertnotagrrtax(:vnotagrrid,:vtaxid,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatenotagrrtax(:vid,:vnotagrrid,:vtaxid,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vnotagrrid', $arraydata[1], PDO::PARAM_STR);
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
			$this->ModifyDataTax($connection,array((isset($_POST['notagrrtaxid'])?$_POST['notagrrtaxid']:''),
				$_POST['notagrrid'],$_POST['taxid']));
			$transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
	
	//invoiceapid,slocid,productid,qty,uomid,qty2,uom2id,qty3,uom3id,qty4,uom4id,price,discount,currencyid,ratevalue,dpp,total,detailnote
	
  private function ModifyDataDetail($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertnotagrrdetail(:vnotagrrid,:vslocid,:vproductid,:vqty,:vuomid,:vqty2,:vuom2id,:vqty3,:vuom3id,:vqty4,:vuom4id,
				:vprice,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatenotagrrdetail(:vid,:vnotagrrid,:vslocid,:vproductid,:vqty,:vuomid,:vqty2,:vuom2id,:vqty3,:vuom3id,:vqty4,:vuom4id,
				:vprice,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vnotagrrid', $arraydata[1], PDO::PARAM_STR);
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
			$this->ModifyDataDetail($connection,array((isset($_POST['notagrrdetailid'])?$_POST['notagrrdetailid']:''),
				$_POST['notagrrid'],
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
	private function ModifyDataGRR($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertnotagrrgrr(:vnotagrrid,:vgrreturid,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatenotagrrgrr(:vid,:vnotagrrid,:vgrreturid,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vnotagrrid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vgrreturid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavegrr() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDatagrr($connection,array((isset($_POST['notagrrgrrid'])?$_POST['notagrrgrrid']:''),
				$_POST['notagrrid'],$_POST['grreturid'],
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
			$sql     = 'call Insertnotagrrjurnal(:vnotagrrid,:vaccountid,:vdebit,:vcredit,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatenotagrrjurnal(:vid,:vnotagrrid,:vaccountid,:vdebit,:vcredit,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vnotagrrid', $arraydata[1], PDO::PARAM_STR);
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
			$this->ModifyDataJurnal($connection,array((isset($_POST['notagrrjurnalid'])?$_POST['notagrrjurnalid']:''),
				$_POST['notagrrid'],$_POST['accountid'],
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
				$sql     = 'call Purgenotagrr(:vid,:vdatauser)';
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
        $sql     = 'call Purgenotagrrtax(:vid,:vdatauser)';
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
  public function actionPurgegrr() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgenotagrrgrr(:vid,:vdatauser)';
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
        $sql     = 'call Purgenotagrrdetail(:vid,:vdatauser)';
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
        $sql     = 'call Purgenotagrrjurnal(:vid,:vdatauser)';
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
        $sql     = 'call Rejectnotagrr(:vid,:vdatauser)';
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
        $sql     = 'call Approvenotagrr(:vid,:vdatauser)';
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
        $sql     = 'call GenerateNGRRPO(:vid, :vhid, :vdatauser)';
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
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select notagrrid,b.companyid,a.notagrrno,a.notagrrdate,a.headernote,d.pono,e.fullname
                        from notagrr a 
						left join plant b on b.plantid = a.plantid
                        left join poheader d on d.poheaderid = a.poheaderid
                        left join addressbook e on e.addressbookid = d.addressbookid			
                        ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.notagrrid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('notagrr');
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety(), 'No ');
      $this->pdf->text(20, $this->pdf->gety(), ': ' . $row['notagrrno']);
      $this->pdf->text(50, $this->pdf->gety(), 'Tgl ');
      $this->pdf->text(60, $this->pdf->gety(), ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['notagrrdate'])));
      $this->pdf->text(90, $this->pdf->gety(), 'No. Reff. ');
      $this->pdf->text(100, $this->pdf->gety(), ': ' . $row['pono']);
      $this->pdf->text(150, $this->pdf->gety(), 'Supplier ');
      $this->pdf->text(160, $this->pdf->gety(), ': ' . $row['fullname']);
      $sql1        = "select b.productname, a.qty, c.uomcode, concat(e.sloccode,' - ',e.description) as sloccode,a.price,a.qty*a.price as jumlah
        from notagrrdetail a
        left join product b on b.productid = a.productid
        left join unitofmeasure c on c.unitofmeasureid = a.uomid 
        left join sloc e on e.slocid = a.slocid
        where notagrrid = " . $row['notagrrid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
        
      $sql_tax = "select taxvalue, taxcode
      from notagrrtax t 
      join tax tx on tx.taxid = t.taxid
      where notagrrid = ".$_GET['id'];
      $taxvalue = Yii::app()->db->createCommand($sql_tax)->queryRow();
        
      $totaljumlah = 0;
      $i           = 0;
      $ppn = 0;
      $this->pdf->sety($this->pdf->gety() + 3);
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
        90,
        20,
        15,
        30,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Unit',
        'Harga',
        'Jumlah'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'R',
        'R'
      );
      $i= 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatCurrency($row1['qty']),
          $row1['uomcode'],
          Yii::app()->format->formatCurrency($row1['price']),
          Yii::app()->format->formatCurrency($row1['jumlah'])
        ));
        $totaljumlah += $row1['jumlah'];
      }
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'Sub Total',
        Yii::app()->format->formatCurrency($totaljumlah)
      ));
        $ppn = ($taxvalue['taxvalue']*$totaljumlah)/100;
    $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'PPN '.$taxvalue['taxvalue'].'%',
        Yii::app()->format->formatCurrency($ppn)
      ));
    $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'Grand Total',
        Yii::app()->format->formatCurrency($ppn+$totaljumlah)
      ));
      $sql2        = "select b.accountname,a.debit,a.credit,c.currencyname,a.detailnote
                from notagrrjurnal a
                left join account b on b.accountid = a.accountid
                left join currency c on c.currencyid = a.currencyid
                where notagrrid = " . $row['notagrrid'];
      $command2    = $this->connection->createCommand($sql2);
      $dataReader2 = $command2->queryAll();
      $totaldebet  = 0;
      $totalcredit = 0;
      $i           = 0;
      $this->pdf->sety($this->pdf->gety() + 3);
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
        60,
        25,
        25,
        25,
        50
      ));
      $this->pdf->colheader = array(
        'No',
        'Akun',
        'Debet',
        'Credit',
        'Mata Uang',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'C',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader2 as $row2) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row2['accountname'],
          Yii::app()->format->formatNumber($row2['debit']),
          Yii::app()->format->formatNumber($row2['credit']),
          $row2['currencyname'],
          $row2['detailnote']
        ));
        $totaldebet += $row1['debet'];
        $totalcredit += $row1['credit'];
      }
      $this->pdf->row(array(
        '',
        'Total',
        Yii::app()->format->formatCurrency($totaldebet),
        Yii::app()->format->formatCurrency($totalcredit),
        '',
        ''
      ));
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        30,
        170
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none'
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Note:',
        $row['headernote']
      ));
      $this->pdf->sety($this->pdf->gety() + 3);
      $this->pdf->text(15, $this->pdf->gety(), '  Dibuat oleh,');
      $this->pdf->text(55, $this->pdf->gety(), ' Diperiksa oleh,');
      $this->pdf->text(96, $this->pdf->gety(), '  Diketahui oleh,');
      $this->pdf->text(15, $this->pdf->gety() + 18, '........................');
      $this->pdf->text(55, $this->pdf->gety() + 18, '.........................');
      $this->pdf->text(96, $this->pdf->gety() + 18, '...........................');
      $this->pdf->text(15, $this->pdf->gety() + 20, '    Admin AP');
      $this->pdf->text(55, $this->pdf->gety() + 20, '     Controller');
      $this->pdf->text(96, $this->pdf->gety() + 20, 'Chief Accounting');
    }
    $this->pdf->Output();
  }
  /*public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select a.notagrrid,
						ifnull(b.companyname,'-')as company,
						ifnull(a.journalno,'-')as journalno,
						ifnull(a.referenceno,'-')as referenceno,
						a.journaldate,a.postdate,
						ifnull(a.journalnote,'-')as journalnote,a.recordstatus,a.companyid
						from notagrr a
						left join company b on b.companyid = a.companyid ";
		$notagrrid = filter_input(INPUT_GET,'notagrrid');
		$companyname = filter_input(INPUT_GET,'companyname');
		$journalno = filter_input(INPUT_GET,'journalno');
		$referenceno = filter_input(INPUT_GET,'referenceno');
		$journaldate = filter_input(INPUT_GET,'journaldate');
		$sql .= " where coalesce(a.notagrrid,'') like '%".$notagrrid."%' 
			and coalesce(b.companyname,'') like '%".$companyname."%'
			and coalesce(a.journalno,'') like '%".$journalno."%'
			and coalesce(a.referenceno,'') like '%".$referenceno."%'
			and coalesce(a.journaldate,'') like '%".$journaldate."%'
			";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.notagrrid in (" . $_GET['id'] . ")";
    }
    $debit            = 0;
    $credit           = 0;
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('notagrr');
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->AddPage('P');
    $this->pdf->setFont('Arial', 'B', 10);
    $this->pdf->AliasNBPages();
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No Journal ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['journalno']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Ref No ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . $row['referenceno']);
      $this->pdf->text(15, $this->pdf->gety() + 15, 'Tgl Jurnal ');
      $this->pdf->text(50, $this->pdf->gety() + 15, ': ' . $row['journaldate']);
      $sql1        = "select b.accountcode,b.accountname, a.debit,a.credit,c.symbol,a.detailnote,a.ratevalue
							from journaldetail a
							left join account b on b.accountid = a.accountid
							left join currency c on c.currencyid = a.currencyid
							where a.notagrrid = '" . $row['notagrrid'] . "'
							order by journaldetailid ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 20);
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
          $row1['accountcode'] . ' ' . $row1['accountname'],
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
        $row['journalnote']
      ));
      $this->pdf->text(20, $this->pdf->gety() + 20, 'Approved By');
      $this->pdf->text(170, $this->pdf->gety() + 20, 'Proposed By');
      $this->pdf->text(20, $this->pdf->gety() + 40, '_____________ ');
      $this->pdf->text(170, $this->pdf->gety() + 40, '_____________');
      $this->pdf->CheckNewPage(10);
    }
    $this->pdf->Output();
  }*/
}