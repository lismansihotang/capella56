<?php
class RepcboutController extends Controller {
  public $menuname = 'repcbout';
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
  public function actionIndextax() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchtax();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $cashbankoutid 	= GetSearchText(array('POST'),'cashbankoutid');
    $cashbankoutdate 	= GetSearchText(array('POST'),'cashbankoutdate');
    $supplier 	= GetSearchText(array('POST'),'supplier');
    $companycode 	= GetSearchText(array('POST'),'companycode');
    $cashbankoutno 	= GetSearchText(array('POST'),'cashbankoutno');
    $invoiceapno 	= GetSearchText(array('POST'),'invoiceapno');
    $pono 	= GetSearchText(array('POST'),'pono');
    $headernote 	= GetSearchText(array('POST'),'headernote');
    $reqpayno 	= GetSearchText(array('POST'),'reqpayno');
    $recordstatus 	= GetSearchText(array('POST'),'recordstatus');
    $acccodeheader 	= GetSearchText(array('POST'),'acccodeheader');
    $acccodedetail 	= GetSearchText(array('POST'),'acccodedetail');
    $accnamedetail 	= GetSearchText(array('POST'),'accnamedetail');
    $accnameheader 	= GetSearchText(array('POST'),'accnameheader');
    $cashbankoutdate 	= GetSearchText(array('POST'),'cashbankoutdate');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','cashbankoutid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset       	= ($page - 1) * $rows;
    $result       	= array();
    $row          	= array();

		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('cashbankout t')
      ->leftjoin('company a', 'a.companyid = t.companyid')
      ->leftjoin('account c', 'c.accountid = t.accountid')
      ->leftjoin('reqpay d', 'd.reqpayid = t.reqpayid')
			->where("
				((coalesce(t.cashbankoutid,'') like '%".$cashbankoutid . "%') 
				and (coalesce(t.cashbankoutdate,'') like '%". $cashbankoutdate . "%') 
				and (coalesce(a.companycode,'') like '%" . $companycode . "%') 
				and (coalesce(t.headernote,'') like '%" . $headernote . "%') 
				and (coalesce(d.reqpayno,'') like '%" . $reqpayno . "%') 
				and (coalesce(c.accountcode,'') like '%" . $acccodeheader . "%')  
				and (coalesce(c.accountname,'') like '%" . $accnameheader . "%')  				
				and (coalesce(t.cashbankoutno,'') like '%" . $cashbankoutno . "%'))
				and a.companyid in (".getUserObjectValues('company').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				(($acccodedetail != '%%')?" and t.cashbankoutid in (
					select distinct za.cashbankoutid
					from cashbankoutjurnal za 
					left join account zb on zb.accountid = za.accountid 
					where zb.accountcode like '".$acccodedetail."')
				":'').
				(($accnamedetail != '%%')?" and t.cashbankoutid in (
					select distinct za.cashbankoutid 
					from cashbankoutjurnal za 
					left join account zb on zb.accountid = za.accountid 
					where zb.accountname like '".$accnamedetail."')
				":'').
				(($supplier != '%%')?" and t.cashbankoutid in (
				SELECT distinct cashbankoutid 
				FROM cashbankoutdetail a 
				JOIN invoiceap b ON b.invoiceapid = a.invoiceapid 
				JOIN addressbook c ON c.addressbookid = b.addressbookid 
				where coalesce(c.fullname,'') like '%".$supplier."%'
				)":'').
				(($pono != '%%')?" and t.cashbankoutid in (
				SELECT distinct cashbankoutid 
				FROM cashbankoutdetail a 
				JOIN invoiceap b ON b.invoiceapid = a.invoiceapid 
				JOIN addressbook c ON c.addressbookid = b.addressbookid 
				JOIN poheader d ON d.poheaderid = b.poheaderid 
				where coalesce(d.pono,'') like '%".$pono."%'
				)":'').
				(($invoiceapno != '%%')?" and t.cashbankoutid in (
				SELECT distinct cashbankoutid 
				FROM cashbankoutdetail a 
				JOIN invoiceap b ON b.invoiceapid = a.invoiceapid 
				where coalesce(b.invoiceapno,'') like '%".$invoiceapno."%'
				)":''), array())->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.companycode,c.accountname,d.reqpayno')
			->from('cashbankout t')
      ->leftjoin('company a', 'a.companyid = t.companyid')
      ->leftjoin('account c', 'c.accountid = t.accountid')
      ->leftjoin('reqpay d', 'd.reqpayid = t.reqpayid')
			->where("
        ((coalesce(t.cashbankoutid,'') like '%".$cashbankoutid . "%') 
				and (coalesce(t.cashbankoutdate,'') like '%". $cashbankoutdate . "%') 
				and (coalesce(a.companycode,'') like '%" . $companycode . "%') 
				and (coalesce(t.headernote,'') like '%" . $headernote . "%') 
				and (coalesce(d.reqpayno,'') like '%" . $reqpayno . "%') 
				and (coalesce(c.accountcode,'') like '%" . $acccodeheader . "%')  
				and (coalesce(c.accountname,'') like '%" . $accnameheader . "%')  				
				and (coalesce(t.cashbankoutno,'') like '%" . $cashbankoutno . "%'))
				and a.companyid in (".getUserObjectValues('company').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				(($acccodedetail != '%%')?" and t.cashbankoutid in (
					select distinct za.cashbankoutid
					from cashbankoutjurnal za 
					left join account zb on zb.accountid = za.accountid 
					where zb.accountcode like '".$acccodedetail."')
				":'').
				(($accnamedetail != '%%')?" and t.cashbankoutid in (
					select distinct za.cashbankoutid 
					from cashbankoutjurnal za 
					left join account zb on zb.accountid = za.accountid 
					where zb.accountname like '".$accnamedetail."')
				":'').
				(($supplier != '%%')?" and t.cashbankoutid in (
				SELECT distinct cashbankoutid 
				FROM cashbankoutdetail a 
				JOIN invoiceap b ON b.invoiceapid = a.invoiceapid 
				JOIN addressbook c ON c.addressbookid = b.addressbookid 
				where coalesce(c.fullname,'') like '%".$supplier."%'
				)":'').
				(($pono != '%%')?" and t.cashbankoutid in (
				SELECT distinct cashbankoutid 
				FROM cashbankoutdetail a 
				JOIN invoiceap b ON b.invoiceapid = a.invoiceapid 
				JOIN addressbook c ON c.addressbookid = b.addressbookid 
				JOIN poheader d ON d.poheaderid = b.poheaderid 
				where coalesce(d.pono,'') like '%".$pono."%'
				)":'').
				(($invoiceapno != '%%')?" and t.cashbankoutid in (
				SELECT distinct cashbankoutid 
				FROM cashbankoutdetail a 
				JOIN invoiceap b ON b.invoiceapid = a.invoiceapid 
				where coalesce(b.invoiceapno,'') like '%".$invoiceapno."%'
				)":''), array())->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cashbankoutid' => $data['cashbankoutid'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'cashbankoutdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['cashbankoutdate'])),
        'cashbankoutno' => $data['cashbankoutno'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'reqpayid' => $data['reqpayid'],
        'reqpayno' => $data['reqpayno'],
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