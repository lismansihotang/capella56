<?php
class ReportprodoutController extends Controller
{
  public $menuname = 'reportprodout';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
	public function search() {
    header('Content-Type: application/json');
    $productoutputid 				= GetSearchText(array('POST','Q'),'productoutputid','','int');
    $productplanno 				= GetSearchText(array('POST','Q'),'productplanno');
    $sono 				= GetSearchText(array('POST','Q'),'sono');
    $customer 				= GetSearchText(array('POST','Q'),'customer');
    $plantcode 				= GetSearchText(array('POST','Q'),'plantcode');
    $sloccode 				= GetSearchText(array('POST','Q'),'sloccode');
    $productoutputdate 				= GetSearchText(array('POST','Q'),'productoutputdate');
    $productoutputno 				= GetSearchText(array('POST','Q'),'productoutputno');
    $headernote 				= GetSearchText(array('POST','Q'),'headernote');
    $recordstatus 				= GetSearchText(array('POST','Q'),'recordstatus');
    $productname 				= GetSearchText(array('POST','Q'),'productname');
    $processprdname 				= GetSearchText(array('POST','Q'),'processprdname');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','productoutputid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
				->from('productoutput t')
				->leftjoin('soheader a', 'a.soheaderid = t.soheaderid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
				->leftjoin('productplan e', 'e.productplanid= t.productplanid')
				->where("
				((coalesce(productoutputid,'') like :productoutputid) 
				and (coalesce(b.plantcode,'') like :plantcode) 
				and (coalesce(e.productplanno,'') like :productplanno) 
				and (coalesce(t.productoutputno,'') like :productoutputno) 
				and (coalesce(t.headernote,'') like :headernote) 
				and (coalesce(a.sono,'') like :sono) 
				and (coalesce(d.fullname,'') like :customer) 
				and (coalesce(productoutputdate,'') like :productoutputdate)) ".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				" 
				and t.plantid in (".getUserObjectValues('plant').")".
				(($productname!='')?"
				and t.productoutputid in (
					select distinct productoutputid 
					from productoutputfg zz
					join product zzz on zzz.productid = zz.productid 
					where zzz.productname like '%".$productname."%'
				)":'').
				(($sloccode!='')?"
				and t.productoutputid in (
					select distinct productoutputid 
					from productoutputfg zz
					join sloc zzz on zzz.slocid = zz.slocid 
					where zzz.sloccode like '%".$sloccode."%'
				)":'').
					(($processprdname != '%%')?" and t.productoutputid in (
						SELECT DISTINCT z.productoutputid  
						FROM productoutputfg z
						JOIN processprd zz ON zz.processprdid = z.processprdid 
						where zz.processprdname like '".$processprdname."'
					)":''),
				array(
        ':productoutputid' => '%' . $productoutputid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':customer' => '%' . $customer . '%',
				':productoutputno' => '%' . $productoutputno . '%',
				':headernote' => '%' . $headernote . '%',
				':sono' => '%' . $sono . '%',
				':productplanno' => '%' . $productplanno . '%',
				':productoutputdate' => '%' . $productoutputdate . '%'
				))->queryScalar();
    $result['total'] = $cmd;
    			$cmd = Yii::app()->db->createCommand()->select('t.*,a.sono,b.plantcode,b.companyid,c.companyname,d.fullname')
				->from('productoutput t')
				->leftjoin('soheader a', 'a.soheaderid = t.soheaderid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
				->leftjoin('productplan e', 'e.productplanid= t.productplanid')
				->where("
				((coalesce(productoutputid,'') like :productoutputid) 
				and (coalesce(b.plantcode,'') like :plantcode) 
				and (coalesce(e.productplanno,'') like :productplanno) 
				and (coalesce(productoutputno,'') like :productoutputno) 
				and (coalesce(t.headernote,'') like :headernote) 
				and (coalesce(a.sono,'') like :sono) 
				and (coalesce(d.fullname,'') like :customer) 
				and (coalesce(productoutputdate,'') like :productoutputdate)) ".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				" 
				and t.plantid in (".getUserObjectValues('plant').")".
				(($productname!='')?"
				and t.productoutputid in (
					select distinct productoutputid 
					from productoutputfg zz
					join product zzz on zzz.productid = zz.productid 
					where zzz.productname like '%".$productname."%'
				)
				":'').(($sloccode!='')?"
				and t.productoutputid in (
					select distinct productoutputid 
					from productoutputfg zz
					join sloc zzz on zzz.slocid = zz.slocid 
					where zzz.sloccode like '%".$sloccode."%'
				)":'').
					(($processprdname != '%%')?" and t.productoutputid in (
						SELECT DISTINCT z.productoutputid  
						FROM productoutputfg z
						JOIN processprd zz ON zz.processprdid = z.processprdid 
						where zz.processprdname like '".$processprdname."'
					)":''),
				array(
        ':productoutputid' => '%' . $productoutputid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':customer' => '%' . $customer . '%',
				':productoutputno' => '%' . $productoutputno . '%',
				':headernote' => '%' . $headernote . '%',
				':sono' => '%' . $sono . '%',
				':productplanno' => '%' . $productplanno . '%',
				':productoutputdate' => '%' . $productoutputdate . '%'
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		foreach ($cmd as $data) {
			$row[] = array(
				'productoutputid' => $data['productoutputid'],
				'productoutputdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['productoutputdate'])),
				'productoutputno' => $data['productoutputno'],
				'plantid' => $data['plantid'],
				'plantcode' => $data['plantcode'],
				'companyid' => $data['companyid'],
				'companyname' => $data['companyname'],
				'soheaderid' => $data['soheaderid'],
				'sono' => $data['sono'],
				'addressbookid' => $data['addressbookid'],
				'fullname' => $data['fullname'],
				'productplanid' => $data['productplanid'],
				'productplanno' => $data['productplanno'],
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