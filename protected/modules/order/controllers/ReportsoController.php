<?php
class ReportsoController extends Controller {
  public $menuname = 'reportso';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
 public function search() {
    header("Content-Type: application/json");
    $soheaderid 		= GetSearchText(array('POST','GET','Q'),'soheaderid');
		$plantcode     		= GetSearchText(array('POST','GET','Q'),'plantcode');
		$productname   		= GetSearchText(array('POST','GET','Q'),'productname');
		$sodate    = GetSearchText(array('POST','GET','Q'),'sodate');
		$sono 		= GetSearchText(array('POST','GET','Q'),'sono');
		$sales      = GetSearchText(array('POST','GET','Q'),'sales');
		$pocustno      = GetSearchText(array('POST','GET','Q'),'pocustno');
		$headernote 		= GetSearchText(array('POST','GET','Q'),'headernote');
		$customer 		= GetSearchText(array('POST','GET','Q'),'customer');
		$recordstatus 		= GetSearchText(array('POST','GET','Q'),'recordstatus');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','soheaderid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
				->from('soheader t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('sales i', 'i.salesid = t.salesid')
				->leftjoin('employee d', 'd.employeeid = i.employeeid')
				->leftjoin('address e', 'e.addressid = t.addresstoid')
				->leftjoin('address f', 'f.addressid = t.addresspayid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
				((coalesce(soheaderid,'') like :soheaderid) 
				and (coalesce(b.plantcode,'') like :plantcode) 
				and (coalesce(d.fullname,'') like :sales) 
				and (coalesce(sono,'') like :sono) 
				and (coalesce(pocustno,'') like :pocustno) 
				and (coalesce(sodate,'') like :sodate) 
				and (coalesce(a.fullname,'') like :customer) 
				and (coalesce(headernote,'') like :headernote))".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":''). 
					"
				and t.plantid in (".getUserObjectValues('plant').")".
				(($productname != '%%')?"
				and t.soheaderid in (
				select distinct z.soheaderid 
				from sodetail z 
				join product za on za.productid = z.productid 
				where coalesce(za.productname,'') like '".$productname."'
				)":''), 
				array(
				':soheaderid' =>  $soheaderid ,
				':plantcode' =>  $plantcode ,
				':sales' =>  $sales ,
				':sono' =>  $sono ,
				':headernote' =>  $headernote ,
				':customer' =>  $customer ,
				':pocustno' =>  $pocustno ,
				':sodate' =>  $sodate
      ))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()->select('t.*,d.employeeid,b.plantcode,t.headernote,b.companyid,c.companyname,d.fullname as sales,a.fullname,
													e.addressname as addresstoname, f.addressname as addresspayname,g.paycode,h.currencyname,a.creditlimit,
				getamountbyso(t.soheaderid) as totprice,
				(select sum(z.price) from sodetail z where z.soheaderid = t.soheaderid and t.recordstatus > 1 and z.qty != z.giqty ) as sopriceoutstanding')
				->from('soheader t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('sales i', 'i.salesid = t.salesid')
				->leftjoin('employee d', 'd.employeeid = i.employeeid')
				->leftjoin('address e', 'e.addressid = t.addresstoid')
				->leftjoin('address f', 'f.addressid = t.addresspayid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
			->where("((coalesce(soheaderid,'') like :soheaderid) 
				and (coalesce(b.plantcode,'') like :plantcode) 
				and (coalesce(d.fullname,'') like :sales) 
				and (coalesce(sono,'') like :sono) 
				and (coalesce(pocustno,'') like :pocustno) 
				and (coalesce(sodate,'') like :sodate) 
				and (coalesce(a.fullname,'') like :customer) 
				and (coalesce(headernote,'') like :headernote))".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":''). 
					"
				and t.plantid in (".getUserObjectValues('plant').")".
					(($productname != '%%')?"
				and t.soheaderid in (
				select distinct z.soheaderid 
				from sodetail z 
				join product za on za.productid = z.productid 
				where coalesce(za.productname,'') like '".$productname."'
				)":''), 
				array(
				':soheaderid' =>  $soheaderid ,
				':plantcode' =>  $plantcode ,
				':sales' =>  $sales ,
				':sono' =>  $sono ,
				':headernote' =>  $headernote ,
				':pocustno' =>  $pocustno ,
				':customer' =>  $customer ,
				':sodate' =>  $sodate 
			))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		foreach ($cmd as $data) {
			$row[] = array(
				'soheaderid' => $data['soheaderid'],
				'sodate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['sodate'])),
				'pocustdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['pocustdate'])),
				'sono' => $data['sono'],
				'plantid' => $data['plantid'],
				'plantcode' => $data['plantcode'],
				'companyid' => $data['companyid'],
				'companyname' => $data['companyname'],
				'totprice' => Yii::app()->format->formatCurrency($data['totprice']),
				'sopriceoutstanding' => Yii::app()->format->formatCurrency($data['sopriceoutstanding']),
				'addresstoid' => $data['addresstoid'],
				'addresstoname' => $data['addresstoname'],
				'addresspayid' => $data['addresspayid'],
				'addresspayname' => $data['addresspayname'],
				'pocustno' => $data['pocustno'],
				'addressbookid' => $data['addressbookid'],
				'fullname' => $data['fullname'],
				'salesid' => $data['salesid'],
				'employeeid' => $data['employeeid'],
				'sales' => $data['sales'],
				'isexport' => $data['isexport'],
				'issample' => $data['issample'],
				'isavalan' => $data['isavalan'],
				'paymentmethodid' => $data['paymentmethodid'],
				'paycode' => $data['paycode'],
				'currencyid' => $data['currencyid'],
				'currencyname' => $data['currencyname'],
				'currencyrate' => $data['currencyrate'],
				'creditlimit' => $data['creditlimit'],
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
