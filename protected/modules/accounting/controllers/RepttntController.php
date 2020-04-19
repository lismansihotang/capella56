<?php
class RepttntController extends Controller {
  public $menuname = 'repttnt';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $ttntid = GetSearchText(array('POST','GET'),'ttntid');
    $ttntno = GetSearchText(array('POST','Q'),'ttntno');
    $customername = GetSearchText(array('POST','Q'),'customername');
    $plantcode = GetSearchText(array('POST','Q'),'plantcode');
    $ttntdate = GetSearchText(array('POST','Q'),'ttntdate');
    $postdate = GetSearchText(array('POST','Q'),'postdate');
    $description = GetSearchText(array('POST','Q'),'description');
    $recordstatus = GetSearchText(array('POST','Q'),'recordstatus');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','ttntid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset       = ($page - 1) * $rows;
    $result       = array();
    $row          = array();

		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('ttnt t')
			->where("
				((coalesce(ttntdate,'') like :ttntdate) and 
				(coalesce(description,'') like :description) and 
				(coalesce(fullname,'') like :customername) and 
				(coalesce(plantcode,'') like :plantcode) and 
				(coalesce(ttntno,'') like :ttntno) and 
				(coalesce(ttntid,'') like :ttntid))
					and t.plantid in (".getUserObjectValues('plant').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'')
					, array(
					':ttntdate' => '%' . $ttntdate . '%',
					':description' => '%' . $description . '%',
					':plantcode' => '%' . $plantcode . '%',
					':customername' => '%' . $customername . '%',
					':ttntno' => '%' . $ttntno . '%',
					':ttntid' => '%' . $ttntid . '%'
				))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*')
			->from('ttnt t')
			->where("
				((coalesce(ttntdate,'') like :ttntdate) 
				and (coalesce(description,'') like :description) 
				and (coalesce(fullname,'') like :customername) 
				and (coalesce(plantcode,'') like :plantcode) 
				and (coalesce(ttntno,'') like :ttntno) 
				and (coalesce(ttntid,'') like :ttntid))
				and t.plantid in (".getUserObjectValues('plant').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'')
					, array(
					':ttntdate' => '%' . $ttntdate . '%',
					':description' => '%' . $description . '%',
					':plantcode' => '%' . $plantcode . '%',
					':customername' => '%' . $customername . '%',
					':ttntno' => '%' . $ttntno . '%',
					':ttntid' => '%' . $ttntid . '%'
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'ttntid' => $data['ttntid'],
        'ttntno' => $data['ttntno'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'addressbookid' => $data['addressbookid'],
        'customername' => $data['fullname'],
        'ttntdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['ttntdate'])),
        'description' => $data['description'],
        'recordstatus' => $data['recordstatus'],
        'statusname' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
}