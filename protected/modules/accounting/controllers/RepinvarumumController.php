<?php
class RepinvarumumController extends Controller {
  public $menuname = 'repinvarumum';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
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
				(coalesce(a.fullname,'') like :customer) and 
				(coalesce(t.plantcode,'') like :plantcode) and 
				(coalesce(t.invoicearno,'') like :invoicearno) and 
				(coalesce(t.invoiceartaxno,'') like :invoiceartaxno))
          and t.soheaderid is null
          and t.doctype = 2
					and t.companyid in (".getUserObjectValues('company').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'')
					, array(
					':companycode' => '%' . $companycode . '%',
					':invoiceardate' => '%' . $invoiceardate . '%',
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
        (coalesce(a.fullname,'') like :customer) and 
        (coalesce(t.plantcode,'') like :plantcode) and 
        (coalesce(t.invoicearno,'') like :invoicearno) and 
        (coalesce(t.invoiceartaxno,'') like :invoiceartaxno))
        and t.soheaderid is null
        and t.doctype = 2
        and t.companyid in (".getUserObjectValues('company').")".
        (($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'')
					, array(
					':companycode' => '%' . $companycode . '%',
					':invoiceardate' => '%' . $invoiceardate . '%',
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
}