<?php
class ReportgiController extends Controller {
  public $menuname = 'reportgi';	
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $giheaderid = GetSearchText(array('POST','Q'),'giheaderid','','int');
    $plantcode = GetSearchText(array('POST','Q'),'plantcode');
    $productcode = GetSearchText(array('POST','Q'),'productcode');
    $productname = GetSearchText(array('POST','Q'),'productname');
    $addressname = GetSearchText(array('POST','Q'),'addressname');
    $pocustno = GetSearchText(array('POST','Q'),'pocustno');
    $nomobil = GetSearchText(array('POST','Q'),'nomobil');
    $sono = GetSearchText(array('POST','Q'),'sono');
    $ekspedisi = GetSearchText(array('POST','Q'),'ekspedisi');
    $gidate = GetSearchText(array('POST','Q'),'gidate');
    $gino = GetSearchText(array('POST','Q'),'gino');
    $pocustno = GetSearchText(array('POST','Q'),'pocustno');
    $headernote = GetSearchText(array('POST','Q'),'headernote');
    $customer = GetSearchText(array('POST','Q'),'customer');
    $ekspedisi = GetSearchText(array('POST','Q'),'ekspedisi');
    $sopir = GetSearchText(array('POST','Q'),'sopir');
    $recordstatus = GetSearchText(array('POST','Q'),'recordstatus');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','giheaderid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
	$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
		->from('giheader t')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('address a', 'a.addressid = t.addresstoid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addressbook d', 'd.addressbookid = t.supplierid')
				->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
				->leftjoin('addressbook f', 'f.addressbookid = t.addressbookid')
		->where("
					((coalesce(giheaderid,'') like '%".$giheaderid."%') 
					and (coalesce(b.plantcode,'') like '".$plantcode."') 
					and (coalesce(t.gino,'') like '".$gino."') 
					and (coalesce(t.headernote,'') like '".$headernote."')  
					and (coalesce(e.sono,'') like '".$sono."')  
					and (coalesce(t.pocustno,'') like '".$pocustno."')  
					and (coalesce(suppliername,'') like '".$ekspedisi."')  
					and (coalesce(customername,'') like '".$customer."')  
					and (coalesce(t.sopir,'') like '".$sopir."')  
					and (coalesce(t.nomobil,'') like '".$nomobil."')  
					and (coalesce(a.addressname,'') like '".$addressname."')  
					and (coalesce(t.gidate,'') like '".$gidate."'))".
					(($productname != '%%')?" and t.giheaderid in (
					select distinct giheaderid 
					from gidetail za 
					left join product zb on zb.productid = za.productid 
					where zb.productname like '%".$productname."%'
				)":'').
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				"	and t.plantid in (".getUserObjectValues('plant').")",
			array())->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()->select('t.*,e.sono,b.plantcode,a.addressname,b.companyid,c.companyname,d.fullname as suppliername,f.fullname as customername')
			->from('giheader t')
			->leftjoin('plant b', 'b.plantid = t.plantid')
			->leftjoin('address a', 'a.addressid = t.addresstoid')
			->leftjoin('company c', 'c.companyid = b.companyid')
			->leftjoin('addressbook d', 'd.addressbookid = t.supplierid')
			->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
			->leftjoin('addressbook f', 'f.addressbookid = t.addressbookid')
			->where("
					((coalesce(giheaderid,'') like '%".$giheaderid."%') 
					and (coalesce(b.plantcode,'') like '".$plantcode."') 
					and (coalesce(t.gino,'') like '".$gino."') 
					and (coalesce(t.headernote,'') like '".$headernote."')  
					and (coalesce(e.sono,'') like '".$sono."')  
					and (coalesce(t.pocustno,'') like '".$pocustno."')  
					and (coalesce(suppliername,'') like '".$ekspedisi."')  
					and (coalesce(customername,'') like '".$customer."')  
					and (coalesce(t.sopir,'') like '".$sopir."')  
					and (coalesce(t.nomobil,'') like '".$nomobil."')  
					and (coalesce(a.addressname,'') like '".$addressname."')  
					and (coalesce(t.gidate,'') like '".$gidate."'))".
					(($productname != '%%')?" and t.giheaderid in (
					select distinct giheaderid 
					from gidetail za 
					left join product zb on zb.productid = za.productid 
					where zb.productname like '%".$productname."%'
				)":'').
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				"	and t.plantid in (".getUserObjectValues('plant').")",
			array())->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		foreach ($cmd as $data) {
		$row[] = array(
			'giheaderid' => $data['giheaderid'],
			'gino' => $data['gino'],
			'gidate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['gidate'])),
			'soheaderid' => $data['soheaderid'],
			'sono' => $data['sono'],
			'pocustno' => $data['pocustno'],
			'addressbookid' => $data['addressbookid'],
			'customername' => $data['customername'],
			'suppliername' => $data['suppliername'],
			'supplierid' => $data['supplierid'],
			'plantid' => $data['plantid'],
			'plantcode' => $data['plantcode'],
			'companyid' => $data['companyid'],
			'companyname' => $data['companyname'],
			'addresstoid' => $data['addresstoid'],
			'addressname' => $data['addressname'],
			'sopir' => $data['sopir'],
			'nomobil' => $data['nomobil'],
			'pebno' => $data['pebno'],
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