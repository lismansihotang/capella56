<?php
class RepexpeditionarController extends Controller {
  public $menuname = 'repexpeditionar';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $expeditionarid 		= GetSearchText(array('POST','GET'),'expeditionarid');
    $companycode 		= GetSearchText(array('POST','Q'),'companycode');
    $expeditionname 		= GetSearchText(array('POST','Q'),'expeditionname');
    $expeditionarno 		= GetSearchText(array('POST','Q'),'expeditionarno');
    $plantcode 		= GetSearchText(array('POST','Q'),'plantcode');
    $sono 		= GetSearchText(array('POST','Q'),'sono');
    $supplier 		= GetSearchText(array('POST','Q'),'supplier');
    $recordstatus 		= GetSearchText(array('POST','Q'),'recordstatus');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','expeditionarid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset       	= ($page - 1) * $rows;
    $result       	= array();
    $row          	= array();

		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('expeditionar t')
      ->leftjoin('plant a', 'a.plantid = t.plantid')
      ->leftjoin('company b', 'b.companyid = a.companyid')
      ->leftjoin('soheader c', 'c.soheaderid = t.soheaderid')
      ->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
      ->leftjoin('addressbook e', 'e.addressbookid = t.addressbookexpid')
			->where("
				((coalesce(b.companycode,'') like :companycode) and 
				(coalesce(e.fullname,'') like :expeditionname) and 
				(coalesce(t.expeditionarno,'') like :expeditionarno) and 
				(coalesce(a.plantcode,'') like :plantcode) and 
				(coalesce(c.sono,'') like :sono) and 
				(coalesce(d.fullname,'') like :supplier))
					and a.companyid in (".getUserObjectValues('company').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'')
					, array(
					':companycode' => '%' . $companycode . '%',
					':expeditionname' => '%' . $expeditionname . '%',
					':expeditionarno' => '%' . $expeditionarno . '%',
					':plantcode' => '%' . $plantcode . '%',
					':sono' => '%' . $sono . '%',
					':supplier' => '%' . $supplier . '%'
				))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.plantcode,a.companyid,b.companycode,c.sono,d.fullname, e.fullname as expeditionname')
			->from('expeditionar t')
      ->leftjoin('plant a', 'a.plantid = t.plantid')
      ->leftjoin('company b', 'b.companyid = a.companyid')
      ->leftjoin('soheader c', 'c.soheaderid = t.soheaderid')
      ->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
      ->leftjoin('addressbook e', 'e.addressbookid = t.addressbookexpid')
			->where("
				((coalesce(b.companycode,'') like :companycode) and 
				(coalesce(e.fullname,'') like :expeditionname) and 
				(coalesce(t.expeditionarno,'') like :expeditionarno) and 
				(coalesce(a.plantcode,'') like :plantcode) and 
				(coalesce(c.sono,'') like :sono) and 
				(coalesce(d.fullname,'') like :supplier))
				and a.companyid in (".getUserObjectValues('company').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'')
					, array(
					':companycode' => '%' . $companycode . '%',
					':expeditionname' => '%' . $expeditionname . '%',
					':expeditionarno' => '%' . $expeditionarno . '%',
					':plantcode' => '%' . $plantcode . '%',
					':sono' => '%' . $sono . '%',
					':supplier' => '%' . $supplier . '%'
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'expeditionarid' => $data['expeditionarid'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'soheaderid' => $data['soheaderid'],
        'sono' => $data['sono'],
        'expeditionardate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['expeditionardate'])),
        'expeditionarno' => $data['expeditionarno'],
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
}