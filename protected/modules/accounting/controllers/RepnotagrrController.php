<?php
class RepnotagrrController extends Controller {
  public $menuname = 'repnotagrr';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $notagrrid 		= GetSearchText(array('POST','GET'),'notagrrid','','int');
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
					and d.companyid in (".getUserObjectValues('company').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					"
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
    $result['total'] = $cmd;
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
				and d.companyid in (".getUserObjectValues('company').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					"
					and t.poheaderid > 0
					", array(
					':companycode' => '%' . $companycode . '%',
					':supplier' => '%' . $supplier . '%',
					':headernote' => '%' . $headernote . '%',
					':notagrrdate' => '%' . $notagrrdate . '%',
					':plantcode' => '%' . $plantcode . '%',
					':pono' => '%' . $pono . '%',
					':notagrrno' => '%' . $notagrrno . '%',
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
}