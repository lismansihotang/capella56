<?php
class RepprodplanController extends Controller {
  public $menuname = 'repprodplan';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $productplanid = GetSearchText(array('POST','Q'),'productplanid');
		$sono = GetSearchText(array('POST','Q'),'sono');
		$customer = GetSearchText(array('POST','Q'),'customer');
		$plantcode = GetSearchText(array('POST','Q'),'plantcode');
		$sloccode = GetSearchText(array('POST','Q'),'sloccode');
		$productplandate = GetSearchText(array('POST','Q'),'productplandate');
		$productplanno = GetSearchText(array('POST','Q'),'productplanno');
		$description = GetSearchText(array('POST','Q'),'description');
		$productname = GetSearchText(array('POST','Q'),'productname');
		$recordstatus = GetSearchText(array('POST','Q'),'recordstatus');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','productplanid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
				->from('productplan t')
				->leftjoin('soheader a', 'a.soheaderid = t.soheaderid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
				->leftjoin('productplan e', 'e.productplanid = t.parentplanid')
				->where("
				((coalesce(t.productplanid,'') like :productplanid) 
				and (coalesce(b.plantcode,'') like :plantcode) 
				and (coalesce(fullname,'') like :customer) 
				and (coalesce(t.productplanno,'') like :productplanno) 
				and (coalesce(t.description,'') like :description) 
				and (coalesce(sono,'') like :sono) 
				and (coalesce(t.productplandate,'') like :productplandate)) 
				and t.plantid in (".getUserObjectValues('plant').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				(($sloccode != '%%')?"
				and t.productplanid in (
					select distinct z.productplanid 
					from productplanfg z 
					join sloc zz on zz.slocid = z.sloctoid 
					where zz.sloccode like '%".$sloccode."%'
				)":'').
				(($productname != '%%')?"
				and t.productplanid in (
					select distinct z.productplanid 
					from productplanfg z 
					join product zz on zz.productid = z.productid 
					where zz.productname like '%".$productname."%'
				)":'')
				,				
				array(
        ':productplanid' => '%' . $productplanid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':customer' => '%' . $customer . '%',
				':productplanno' => '%' . $productplanno . '%',
				':description' => '%' . $description . '%',
				':sono' => '%' . $sono . '%',
				':productplandate' => '%' . $productplandate . '%'
				))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()->select('t.*,a.sono,b.plantcode,b.companyid,c.companyname,d.fullname,e.productplanno as parentplanno,
		(select sum(z.qty) from productplandetail z where z.productplanid = t.productplanid) as qty,
			(select sum(z.qtyres) from productplandetail z where z.productplanid = t.productplanid) as qtyres')
				->from('productplan t')
				->leftjoin('soheader a', 'a.soheaderid = t.soheaderid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
				->leftjoin('productplan e', 'e.productplanid = t.parentplanid')
				->where("
				((coalesce(t.productplanid,'') like :productplanid) 
				and (coalesce(b.plantcode,'') like :plantcode) 
				and (coalesce(fullname,'') like :customer) 
				and (coalesce(t.productplanno,'') like :productplanno) 
				and (coalesce(t.description,'') like :description) 
				and (coalesce(sono,'') like :sono) 
				and (coalesce(t.productplandate,'') like :productplandate))
				and t.plantid in (".getUserObjectValues('plant').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				(($sloccode != '%%')?"
				and t.productplanid in (
					select distinct z.productplanid 
					from productplanfg z 
					join sloc zz on zz.slocid = z.sloctoid 
					where zz.sloccode like '%".$sloccode."%'
				)":'').
				(($productname != '%%')?"
				and t.productplanid in (
					select distinct z.productplanid 
					from productplanfg z 
					join product zz on zz.productid = z.productid 
					where zz.productname like '%".$productname."%'
				)":'')
				,				
				array(
        ':productplanid' => '%' . $productplanid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':customer' => '%' . $customer . '%',
				':productplanno' => '%' . $productplanno . '%',
				':description' => '%' . $description . '%',
				':sono' => '%' . $sono . '%',
				':productplandate' => '%' . $productplandate . '%'
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		foreach ($cmd as $data) {
			$row[] = array(
				'productplanid' => $data['productplanid'],
				'productplandate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['productplandate'])),
				'productplanno' => $data['productplanno'],
				'plantid' => $data['plantid'],
				'plantcode' => $data['plantcode'],
				'companyid' => $data['companyid'],
				'companyname' => $data['companyname'],
				'soheaderid' => $data['soheaderid'],
				'sono' => $data['sono'],
				'qty' => $data['qty'],
				'qtyres' => $data['qtyres'],
				'addressbookid' => $data['addressbookid'],
				'fullname' => $data['fullname'],
				'description' => $data['description'],
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