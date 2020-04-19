<?php
class RepinvapumumController extends Controller {
  public $menuname = 'repinvapumum';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $invoiceapid 		= GetSearchText(array('POST','GET'),'invoiceapid','','int');
    $companycode 		= GetSearchText(array('POST','Q'),'companycode');
    $supplier 		= GetSearchText(array('POST','Q'),'supplier');
    $headernote 		= GetSearchText(array('POST','Q'),'headernote');
    $plantcode 		= GetSearchText(array('POST','Q'),'plantcode');
    $nobuktipotong 		= GetSearchText(array('POST','Q'),'nobuktipotong');
    $invoiceapno 		= GetSearchText(array('POST','Q'),'invoiceapno');
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
			->select('count(1) as total')
			->from('invoiceap t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('cashbank b', 'b.cashbankid = t.cashbankid')
			->where("
				((coalesce(t.companycode,'') like :companycode) and 
				(coalesce(t.invoiceapid,'') like :invoiceapid) and 
				(coalesce(a.fullname,'') like :supplier) and 
				(coalesce(t.headernote,'') like :headernote) and 
				(coalesce(t.plantcode,'') like :plantcode) and 
				(coalesce(t.invoiceapdate,'') like :invoiceapdate) and 
				(coalesce(t.nobuktipotong,'') like :nobuktipotong) and 
				(coalesce(t.invoiceapno,'') like :invoiceapno) and 
				(coalesce(t.invoiceaptaxno,'') like :invoiceaptaxno))
					and t.companyid in (".getUserObjectValues('company').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					"
					and t.poheaderid is null
					", array(
					':companycode' => '%' . $companycode . '%',
					':supplier' => '%' . $supplier . '%',
					':invoiceapid' => '%' . $invoiceapid . '%',
					':headernote' => '%' . $headernote . '%',
					':plantcode' => '%' . $plantcode . '%',
					':invoiceapdate' => '%' . $invoiceapdate . '%',
					':nobuktipotong' => '%' . $nobuktipotong . '%',
					':invoiceapno' => '%' . $invoiceapno . '%',
					':invoiceaptaxno' => '%' . $invoiceaptaxno . '%'
				))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.fullname,b.cashbankno')
			->from('invoiceap t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('cashbank b', 'b.cashbankid = t.cashbankid')
			->where("
				((coalesce(t.companycode,'') like :companycode) and 
				(coalesce(t.invoiceapid,'') like :invoiceapid) and 
				(coalesce(a.fullname,'') like :supplier) and 
				(coalesce(t.headernote,'') like :headernote) and 
				(coalesce(t.plantcode,'') like :plantcode) and 
				(coalesce(t.invoiceapdate,'') like :invoiceapdate) and 
				(coalesce(t.nobuktipotong,'') like :nobuktipotong) and 
				(coalesce(t.invoiceapno,'') like :invoiceapno) and 
				(coalesce(t.invoiceaptaxno,'') like :invoiceaptaxno))
				and t.companyid in (".getUserObjectValues('company').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					"
					and t.poheaderid is null
					", array(
					':companycode' => '%' . $companycode . '%',
					':invoiceapid' => '%' . $invoiceapid . '%',
					':supplier' => '%' . $supplier . '%',
					':headernote' => '%' . $headernote . '%',
					':plantcode' => '%' . $plantcode . '%',
					':invoiceapdate' => '%' . $invoiceapdate . '%',
					':nobuktipotong' => '%' . $nobuktipotong . '%',
					':invoiceapno' => '%' . $invoiceapno . '%',
					':invoiceaptaxno' => '%' . $invoiceaptaxno . '%'
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
}