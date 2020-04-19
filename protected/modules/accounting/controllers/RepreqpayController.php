<?php
class RepreqpayController extends Controller
{
  public $menuname = 'repreqpay';
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
  public function search() {
    header('Content-Type: application/json');
    $reqpayid 		= GetSearchText(array('POST','GET','Q'),'reqpayid');
		$companycode     		= GetSearchText(array('POST','GET','Q'),'companycode');
		$invoiceapno   		= GetSearchText(array('POST','GET','Q'),'invoiceapno');
		$supplier   		= GetSearchText(array('POST','GET','Q'),'supplier');
		$reqpaydate    = GetSearchText(array('POST','GET','Q'),'reqpaydate');
		$reqpayno 		= GetSearchText(array('POST','GET','Q'),'reqpayno');
		$headernote      = GetSearchText(array('POST','GET','Q'),'headernote');
		$customer 		= GetSearchText(array('POST','GET','Q'),'customer');
		$recordstatus 		= GetSearchText(array('POST','GET','Q'),'recordstatus');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','reqpayid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();				
		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('reqpay t')
    ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
    ->leftjoin('company b', 'b.companyid = t.companyid')
  ->where("(coalesce(reqpaydate,'') like :reqpaydate) 
			and (coalesce(reqpayno,'') like :reqpayno) 
			and (coalesce(headernote,'') like :headernote) 
			and (coalesce(reqpayid,'') like :reqpayid) 
			and (coalesce(fullname,'') like :supplier) 
			and (coalesce(companycode,'') like :companycode) ".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
			"and t.companyid in (".getUserObjectValues('company').")", array(
        ':reqpaydate' => '%' . $reqpaydate . '%',
      ':headernote' => '%' . $headernote . '%',
      ':reqpayid' => '%' . $reqpayid . '%',
      ':supplier' => '%' . $supplier . '%',
      ':companycode' => '%' . $companycode . '%',
      ':reqpayno' => '%' . $reqpayno . '%'
      ))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()->select('t.*,b.companyid,b.companycode,a.fullname')->from('reqpay t')
    ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
    ->leftjoin('company b', 'b.companyid = t.companyid')
  ->where("(coalesce(reqpaydate,'') like :reqpaydate) 
				and (coalesce(reqpayno,'') like :reqpayno) 
				and (coalesce(headernote,'') like :headernote) 
				and (coalesce(reqpayid,'') like :reqpayid) 
        and (coalesce(fullname,'') like :supplier) 
        and (coalesce(companycode,'') like :companycode) ".
            (($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					" 
				and t.companyid in (".getUserObjectValues('company').")", array(
					':reqpaydate' => '%' . $reqpaydate . '%',
				':headernote' => '%' . $headernote . '%',
				':reqpayid' => '%' . $reqpayid . '%',
        ':supplier' => '%' . $supplier . '%',
        ':companycode' => '%' . $companycode . '%',
          ':reqpayno' => '%' . $reqpayno . '%'
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('reqpayinv t')->join('invoiceap a', 'a.invoiceapid=t.invoiceapid')->join('addressbook b', 'b.addressbookid=a.addressbookid')->join('poheader c', 'c.poheaderid=a.poheaderid')->join('paymentmethod d', 'd.paymentmethodid=c.paymentmethodid')->join('currency e', 'e.currencyid=t.currencyid')->where('t.reqpayid = :reqpayid', array(
      ':reqpayid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.invoiceapno,b.fullname,t.amount,e.currencyname,e.symbol')->from('reqpayinv t')->join('invoiceap a', 'a.invoiceapid=t.invoiceapid')->join('addressbook b', 'b.addressbookid=a.addressbookid')->join('poheader c', 'c.poheaderid=a.poheaderid')->join('paymentmethod d', 'd.paymentmethodid=c.paymentmethodid')->join('currency e', 'e.currencyid=t.currencyid')->where('t.reqpayid = :reqpayid', array(
      ':reqpayid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    $symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'reqpayinvid' => $data['reqpayinvid'],
        'reqpayid' => $data['reqpayid'],
        'invoiceapid' => $data['invoiceapid'],
        'invoiceapno' => $data['invoiceapno'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'duedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['duedate'])),
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'symbol' => $data['symbol'],
        'ratevalue' => Yii::app()->format->formatNumber($data['ratevalue']),
        'detailnote' => $data['detailnote']
      );
      $symbol = $data['symbol'];
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    $cmd             = Yii::app()->db->createCommand()->select('sum(t.amount) as amount')->from('reqpayinv t')->join('invoiceap a', 'a.invoiceapid=t.invoiceapid')->join('addressbook b', 'b.addressbookid=a.addressbookid')->join('poheader c', 'c.poheaderid=a.poheaderid')->join('paymentmethod d', 'd.paymentmethodid=c.paymentmethodid')->join('currency e', 'e.currencyid=t.currencyid')->where('t.reqpayid = :reqpayid', array(
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
}
