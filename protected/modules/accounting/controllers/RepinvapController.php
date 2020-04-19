<?php
class RepinvapController extends Controller {
  public $menuname = 'repinvap';
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
    $pono 		= GetSearchText(array('POST','Q'),'pono');
    $nobuktipotong 		= GetSearchText(array('POST','Q'),'nobuktipotong');
    $invoiceapno 		= GetSearchText(array('POST','Q'),'invoiceapno');
    $sjsupplier 		= GetSearchText(array('POST','Q'),'sjsupplier');
    $invoiceaptaxno 		= GetSearchText(array('POST','Q'),'invoiceaptaxno');
    $recordstatus 		= GetSearchText(array('POST','Q'),'recordstatus');
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
      ->leftjoin('invoiceapgr c', 'c.invoiceapid = t.invoiceapid')
	  ->leftjoin('grheader d', 'd.grheaderid = c.grheaderid')
			->where("
				(coalesce(t.invoiceapid,'') like :invoiceapid) and 
				(coalesce(a.fullname,'') like :supplier) and 
				(coalesce(t.headernote,'') like :headernote) and 
				(coalesce(t.plantcode,'') like :plantcode) and 
				(coalesce(t.pono,'') like :pono) and 
				(coalesce(t.nobuktipotong,'') like :nobuktipotong) and 
        (coalesce(t.invoiceapno,'') like :invoiceapno) and 
        (coalesce(d.sjsupplier,'') like :sjsupplier) and
				(coalesce(t.invoiceaptaxno,'') like :invoiceaptaxno) and
					t.companyid in (".getUserObjectValues('company').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'')
					, array(
					':invoiceapid' => '%' . $invoiceapid . '%',
					':supplier' => '%' . $supplier . '%',
					':headernote' => '%' . $headernote . '%',
					':plantcode' => '%' . $plantcode . '%',
					':pono' => '%' . $pono . '%',
					':nobuktipotong' => '%' . $nobuktipotong . '%',
					':invoiceapno' => '%' . $invoiceapno . '%',
					':sjsupplier' => '%' . $sjsupplier . '%',
					':invoiceaptaxno' => '%' . $invoiceaptaxno . '%'
				))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.fullname,b.cashbankno')
			->from('invoiceap t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('cashbank b', 'b.cashbankid = t.cashbankid')
      ->leftjoin('invoiceapgr c', 'c.invoiceapid = t.invoiceapid')
	  ->leftjoin('grheader d', 'd.grheaderid = c.grheaderid')
			->where("
				(coalesce(t.invoiceapid,'') like :invoiceapid) and 
				(coalesce(a.fullname,'') like :supplier) and 
				(coalesce(t.headernote,'') like :headernote) and 
				(coalesce(t.plantcode,'') like :plantcode) and 
				(coalesce(t.pono,'') like :pono) and 
				(coalesce(t.nobuktipotong,'') like :nobuktipotong) and 
        (coalesce(t.invoiceapno,'') like :invoiceapno) and 
        (coalesce(d.sjsupplier,'') like :sjsupplier) and
				(coalesce(t.invoiceaptaxno,'') like :invoiceaptaxno) and
				t.companyid in (".getUserObjectValues('company').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'')
					, array(
					':invoiceapid' => '%' . $invoiceapid . '%',
					':supplier' => '%' . $supplier . '%',
					':headernote' => '%' . $headernote . '%',
					':plantcode' => '%' . $plantcode . '%',
					':pono' => '%' . $pono . '%',
					':nobuktipotong' => '%' . $nobuktipotong . '%',
					':invoiceapno' => '%' . $invoiceapno . '%',
					':sjsupplier' => '%' . $sjsupplier . '%',
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