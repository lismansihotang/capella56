<?php
class RepexpeditionapController extends Controller {
  public $menuname = 'repexpeditionap';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
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
  public function actionIndexjurnal() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchjurnal();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $expeditionapid 		= isset($_POST['expeditionapid']) ? $_POST['expeditionapid'] : '';
    $companycode 		= isset($_POST['companycode']) ? $_POST['companycode'] : '';
    $expeditionname				= isset($_POST['expeditionname']) ? $_POST['expeditionname'] : '';
    $expeditionapno 		= isset($_POST['expeditionapno']) ? $_POST['expeditionapno'] : '';
    $plantcode  		= isset($_POST['plantcode']) ? $_POST['plantcode'] : '';
    $pono 					= isset($_POST['pono']) ? $_POST['pono'] : '';
    $supplier 	= isset($_POST['supplier']) ? $_POST['supplier'] : '';
    $page         	= isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows         	= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort         	= isset($_POST['sort']) ? strval($_POST['sort']) : 'expeditionapid';
    $order        	= isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset       	= ($page - 1) * $rows;
    $result       	= array();
    $row          	= array();

		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('expeditionap t')
      ->leftjoin('plant a', 'a.plantid = t.plantid')
      ->leftjoin('company b', 'b.companyid = a.companyid')
      ->leftjoin('poheader c', 'c.poheaderid = t.poheaderid')
      ->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
      ->leftjoin('addressbook e', 'e.addressbookid = t.addressbookexpid')
			->where("
				((coalesce(b.companycode,'') like :companycode) and 
				(coalesce(e.fullname,'') like :expeditionname) and 
				(coalesce(t.expeditionapno,'') like :expeditionapno) and 
				(coalesce(a.plantcode,'') like :plantcode) and 
				(coalesce(c.pono,'') like :pono) and 
				(coalesce(d.fullname,'') like :supplier))
					and t.recordstatus in (".getUserRecordStatus('listexpap').")
					and a.companyid in (".getUserObjectValues('company').")
					and t.poheaderid > 0
					", array(
					':companycode' => '%' . $companycode . '%',
					':expeditionname' => '%' . $expeditionname . '%',
					':expeditionapno' => '%' . $expeditionapno . '%',
					':plantcode' => '%' . $plantcode . '%',
					':pono' => '%' . $pono . '%',
					':supplier' => '%' . $supplier . '%'
				))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.plantcode,a.companyid,b.companycode,c.pono,d.fullname, e.fullname as expeditionname')
			->from('expeditionap t')
      ->leftjoin('plant a', 'a.plantid = t.plantid')
      ->leftjoin('company b', 'b.companyid = a.companyid')
      ->leftjoin('poheader c', 'c.poheaderid = t.poheaderid')
      ->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
      ->leftjoin('addressbook e', 'e.addressbookid = t.addressbookexpid')
			->where("
				((coalesce(b.companycode,'') like :companycode) and 
				(coalesce(e.fullname,'') like :expeditionname) and 
				(coalesce(t.expeditionapno,'') like :expeditionapno) and 
				(coalesce(a.plantcode,'') like :plantcode) and 
				(coalesce(c.pono,'') like :pono) and 
				(coalesce(d.fullname,'') like :supplier))
				and t.recordstatus in (".getUserRecordStatus('listexpap').")
				and a.companyid in (".getUserObjectValues('company').")
					and t.poheaderid > 0
					", array(
					':companycode' => '%' . $companycode . '%',
					':expeditionname' => '%' . $expeditionname . '%',
					':expeditionapno' => '%' . $expeditionapno . '%',
					':plantcode' => '%' . $plantcode . '%',
					':pono' => '%' . $pono . '%',
					':supplier' => '%' . $supplier . '%'
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'expeditionapid' => $data['expeditionapid'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'expeditionapdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['expeditionapdate'])),
        'expeditionapno' => $data['expeditionapno'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'addressbookexpid' => $data['addressbookexpid'],
        'expeditionname' => $data['expeditionname'],
        'amount' => Yii::app()->format->formatNumber($data['amount']),
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'expeditionapgrid';
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
			->from('expeditionapgr t')
			->leftjoin('grheader a', 'a.grheaderid = t.grheaderid')
			->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
			->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uom2id')
			->where("(expeditionapid = :expeditionapid)", array(
      ':expeditionapid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
			->select('t.*,a.grno,b.uomcode,c.uomcode as uom2code')
			->from('expeditionapgr t')
			->leftjoin('grheader a', 'a.grheaderid = t.grheaderid')
			->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
			->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uom2id')
			->where("(expeditionapid = :expeditionapid)", array(
      ':expeditionapid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'expeditionapgrid' => $data['expeditionapgrid'],
        'expeditionapid' => $data['expeditionapid'],
        'grheaderid' => $data['grheaderid'],
        'grno' => $data['grno'],
        'grdetailid' => $data['grdetailid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'qty2' => Yii::app()->format->formatNumber($data['qty2']),
        'uom2id' => $data['uom2id'],
        'uom2code' => $data['uom2code'],
        'nilaibeban' => Yii::app()->format->formatNumber($data['nilaibeban']),
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'expeditionapjurnalid';
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('expeditionapjurnal t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->leftjoin('company c', 'c.companyid = a.companyid')->where("(expeditionapid = :expeditionapid) and t.accountid > 0", array(
      ':expeditionapid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.accountcode,a.accountname,b.currencyname,c.companyname,b.symbol')->from('expeditionapjurnal t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->leftjoin('company c', 'c.companyid = a.companyid')->where("(expeditionapid = :expeditionapid) and t.accountid > 0", array(
      ':expeditionapid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'expeditionapjurnalid' => $data['expeditionapjurnalid'],
        'expeditionapid' => $data['expeditionapid'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'amount' => Yii::app()->format->formatNumber($data['amount']),
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
		$sql = "select sum(amount) as amount from expeditionapjurnal where expeditionapid = ".$id;
		$cmd = Yii::app()->db->createCommand($sql)->queryRow();
		$footer[] = array(
      'accountname' => 'Total',
			'symbol' => $symbol,
      'amount' => Yii::app()->format->formatNumber($cmd['amount'])
    );
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
}