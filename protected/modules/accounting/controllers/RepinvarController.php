<?php
class RepinvarController extends Controller {
  public $menuname = 'repinvar';
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
					and t.doctype = 1".
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
					and t.doctype = 1".
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
}