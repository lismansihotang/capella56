<?php
class ReportgireturController extends Controller {
  public $menuname = 'reportgiretur';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
	public function search() {
    header('Content-Type: application/json');
    $gireturid   = GetSearchText(array('POST','Q'),'gireturid','','int');
    $gireturno   = GetSearchText(array('POST','Q'),'gireturno');
    $plantid   = GetSearchText(array('POST','GET'),'plantid','','int');
    $plantcode   = GetSearchText(array('POST','Q'),'plantcode');
    $companyid   = GetSearchText(array('POST','GET'),'companyid','','int');
    $companyname   = GetSearchText(array('POST','Q'),'companyname');
    $gireturdate   = GetSearchText(array('POST','Q'),'gireturdate');
    $soheaderid   = GetSearchText(array('POST','GET'),'soheaderid','','int');
    $sono   = GetSearchText(array('POST','Q'),'sono');
    $headernote   = GetSearchText(array('POST','Q'),'headernote');
    $supplier   = GetSearchText(array('POST','Q'),'supplier');
    $recordstatus   = GetSearchText(array('POST','Q'),'recordstatus');
    $customer   = GetSearchText(array('POST','Q'),'customer');
    $plantid   = GetSearchText(array('POST','GET'),'plantid','','int');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','gireturid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset      = ($page - 1) * $rows;
    $result      = array();
    $row         = array();
		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('giretur t')
			->leftjoin('giheader e', 'e.giheaderid = t.giheaderid')
			->leftjoin('soheader a', 'a.soheaderid = e.soheaderid')
			->leftjoin('addressbook b', 'b.addressbookid = a.addressbookid')
			->leftjoin('plant c', 'c.plantid = t.plantid')
			->where("
          (coalesce(t.gireturid,'') like :gireturid) 
				and (coalesce(t.gireturdate,'') like :gireturdate) 
				and (coalesce(a.sono,'') like :sono) 
				and (coalesce(c.plantcode,'') like :plantcode) 
				and (coalesce(t.gireturno,'') like :gireturno) 
				and (coalesce(b.fullname,'') like :fullname) 
				and (coalesce(t.headernote,'') like :headernote)".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				" and t.plantid in (".getUserObjectValues('plant').")", 
				array(
					':gireturid' => '%' . $gireturid . '%',
					':gireturdate' => '%' . $gireturdate . '%',
					':sono' => '%' . $sono . '%',
					':plantcode' => '%' . $plantcode . '%',
					':gireturno' => '%' . $gireturno . '%',
					':fullname' => '%' . $customer . '%',
					':headernote' => '%' . $headernote . '%',
      ))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()->select('t.*,b.fullname,a.sono,c.plantcode,e.gino')
			->from('giretur t')
			->leftjoin('giheader e', 'e.giheaderid = t.giheaderid')
			->leftjoin('soheader a', 'a.soheaderid = e.soheaderid')
			->leftjoin('addressbook b', 'b.addressbookid = a.addressbookid')
			->leftjoin('plant c', 'c.plantid = t.plantid')
			->where("
          (coalesce(t.gireturid,'') like :gireturid) 
				and (coalesce(t.gireturdate,'') like :gireturdate) 
				and (coalesce(a.sono,'') like :sono) 
				and (coalesce(c.plantcode,'') like :plantcode) 
				and (coalesce(t.gireturno,'') like :gireturno) 
				and (coalesce(b.fullname,'') like :fullname) 
				and (coalesce(t.headernote,'') like :headernote)".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				"	and t.plantid in (".getUserObjectValues('plant').")", 
				array(
					':gireturid' => '%' . $gireturid . '%',
					':gireturdate' => '%' . $gireturdate . '%',
					':sono' => '%' . $sono . '%',
					':plantcode' => '%' . $plantcode . '%',
					':gireturno' => '%' . $gireturno . '%',
					':fullname' => '%' . $customer . '%',
					':headernote' => '%' . $headernote . '%',
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'gireturid' => $data['gireturid'],
        'gireturno' => $data['gireturno'],
        'giheaderid' => $data['giheaderid'],
        'gino' => $data['gino'],
        'fullname' => $data['fullname'],
        'gireturdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['gireturdate'])),
        'sono' => $data['sono'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusgiretur' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
}