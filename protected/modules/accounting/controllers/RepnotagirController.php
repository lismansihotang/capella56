<?php
class RepnotagirController extends Controller {
  public $menuname = 'repnotagir';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $notagirid 		= GetSearchText(array('POST','GET'),'notagirid','','int');
    $companycode 		= GetSearchText(array('POST','Q'),'companycode');
    $customer 		= GetSearchText(array('POST','Q'),'customer');
    $headernote 		= GetSearchText(array('POST','Q'),'headernote');
    $plantcode 		= GetSearchText(array('POST','Q'),'plantcode');
    $sono 		= GetSearchText(array('POST','Q'),'sono');
    $notagirdate 		= GetSearchText(array('POST','Q'),'notagirdate');
    $grrreturno 		= GetSearchText(array('POST','Q'),'grrreturno');
    $notagirno 		= GetSearchText(array('POST','Q'),'notagirno');
    $recordstatus 		= GetSearchText(array('POST','Q'),'recordstatus');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','notagirid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset       	= ($page - 1) * $rows;
    $result       	= array();
    $row          	= array();

		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('notagir t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('plant c', 'c.plantid = t.plantid')
      ->leftjoin('company d', 'd.companyid = c.companyid')
      ->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
      ->leftjoin('giretur f', 'f.gireturid = t.gireturid')
      ->leftjoin('invoicear g', 'g.invoicearid = t.invoicearid')
			->where("
				((coalesce(d.companycode,'') like :companycode) and 
				(coalesce(t.notagirid,'') like :notagirid) and 
				(coalesce(a.fullname,'') like :customer) and 
				(coalesce(t.headernote,'') like :headernote) and 
				(coalesce(t.notagirdate,'') like :notagirdate) and 
				(coalesce(c.plantcode,'') like :plantcode) and 
				(coalesce(e.sono,'') like :sono) and 
				(coalesce(t.notagirno,'') like :notagirno))".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					"
					and d.companyid in (".getUserObjectValues('company').")
					", array(
					':notagirid' => '%' . $notagirid . '%',
					':companycode' => '%' . $companycode . '%',
					':customer' => '%' . $customer . '%',
					':notagirdate' => '%' . $notagirdate . '%',
					':headernote' => '%' . $headernote . '%',
					':plantcode' => '%' . $plantcode . '%',
					':sono' => '%' . $sono . '%',
					':notagirno' => '%' . $notagirno . '%',
				))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.fullname,c.plantcode,d.companyid,d.companycode,e.sono,f.gireturno,g.invoiceartaxno')
			->from('notagir t')
      ->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
      ->leftjoin('plant c', 'c.plantid = t.plantid')
      ->leftjoin('company d', 'd.companyid = c.companyid')
      ->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
			  ->leftjoin('giretur f', 'f.gireturid = t.gireturid')
        ->leftjoin('invoicear g', 'g.invoicearid = t.invoicearid')
        ->where("
				((coalesce(d.companycode,'') like :companycode) and 
				(coalesce(a.fullname,'') like :customer) and 
				(coalesce(t.notagirid,'') like :notagirid) and 
				(coalesce(t.headernote,'') like :headernote) and 
				(coalesce(t.notagirdate,'') like :notagirdate) and 
				(coalesce(c.plantcode,'') like :plantcode) and 
				(coalesce(e.sono,'') like :sono) and 
				(coalesce(t.notagirno,'') like :notagirno))".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					"
				and d.companyid in (".getUserObjectValues('company').")
					", array(
					':companycode' => '%' . $companycode . '%',
					':notagirid' => '%' . $notagirid . '%',
					':customer' => '%' . $customer . '%',
					':headernote' => '%' . $headernote . '%',
					':plantcode' => '%' . $plantcode . '%',
					':notagirdate' => '%' . $notagirdate . '%',
					':sono' => '%' . $sono . '%',
					':notagirno' => '%' . $notagirno . '%',
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'notagirid' => $data['notagirid'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'soheaderid' => $data['soheaderid'],
        'sono' => $data['sono'],
				'gireturid' => $data['gireturid'],
        'gireturno' => $data['gireturno'],
        'notagirdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['notagirdate'])),
        'notagirno' => $data['notagirno'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'pocustno' => $data['pocustno'],
        'invoiceartaxno' => $data['invoiceartaxno'],
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