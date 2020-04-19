<?php
class RepcbController extends Controller {
  public $menuname = 'repcb';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $cashbankid = GetSearchText(array('POST','GET'),'cashbankid',0,'int');
    $companycode = GetSearchText(array('POST','Q'),'companycode');
    $cashbankdate = GetSearchText(array('POST','Q'),'cashbankdate');
    $addressbook = GetSearchText(array('POST','Q'),'addressbook');
    $cashbankno = GetSearchText(array('POST','Q'),'cashbankno');
    $accountname = GetSearchText(array('POST','Q'),'accountname');
    $recordstatus = GetSearchText(array('POST','Q'),'recordstatus');
    $headernote = GetSearchText(array('POST','Q'),'headernote');
    $page         	= isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows         	= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort         	= isset($_POST['sort']) ? strval($_POST['sort']) : 'cashbankid';
    $order        	= isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset       	= ($page - 1) * $rows;
    $result       	= array();
    $row          	= array();

		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('cashbank t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('cheque b', 'b.chequeid = t.chequeid')
      ->leftjoin('account c', 'c.accountid = t.accountid')
			->where("
				((coalesce(t.companycode,'') like :companycode) and 
				(coalesce(t.cashbankdate,'') like :cashbankdate) and 
				(coalesce(a.fullname,'') like :addressbook) and 
				(coalesce(c.accountname,'') like :accountname) and 
				(coalesce(t.cashbankid,'') like :cashbankid) and 
				(coalesce(t.headernote,'') like :headernote) and 
				(coalesce(t.cashbankno,'') like :cashbankno))
				and t.companyid in (".getUserObjectValues('company').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					"
					", array(
					':companycode' => '%' . $companycode . '%',
  					':cashbankdate' => '%' . $cashbankdate . '%',
  					':cashbankid' => '%' . $cashbankid . '%',
  					':addressbook' => '%' . $addressbook . '%',
  					':accountname' => '%' . $accountname . '%',
  					':headernote' => '%' . $headernote . '%',
  					':cashbankno' => '%' . $cashbankno . '%'
				))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.fullname,bilyetgirono,c.symbol')
			->from('cashbank t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('cheque b', 'b.chequeid = t.chequeid')
      ->leftjoin('currency c', 'c.currencyid = t.currencyid')
      ->leftjoin('account d', 'd.accountid = t.accountid')
			->where("
        ((coalesce(t.companycode,'') like :companycode) and 
        (coalesce(t.cashbankdate,'') like :cashbankdate) and 
        (coalesce(a.fullname,'') like :addressbook) and 
        (coalesce(d.accountname,'') like :accountname) and 
        (coalesce(t.cashbankid,'') like :cashbankid) and 
        (coalesce(t.headernote,'') like :headernote) and 
        (coalesce(t.cashbankno,'') like :cashbankno))
				and t.companyid in (".getUserObjectValues('company').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					"
					", array(
					':companycode' => '%' . $companycode . '%',
  					':cashbankdate' => '%' . $cashbankdate . '%',
  					':cashbankid' => '%' . $cashbankid . '%',
  					':addressbook' => '%' . $addressbook . '%',
  					':accountname' => '%' . $accountname . '%',
  					':headernote' => '%' . $headernote . '%',
  					':cashbankno' => '%' . $cashbankno . '%'
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cashbankid' => $data['cashbankid'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'cashbankdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['cashbankdate'])),
        'cashbankno' => $data['cashbankno'],
        'isin' => $data['isin'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'receiptno' => $data['receiptno'],
        'chequeid' => $data['chequeid'],
        'bilyetgirono' => $data['bilyetgirono'],
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'symbol' => $data['symbol'],
        'ratevalue' => Yii::app()->format->formatNumber($data['ratevalue']),
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