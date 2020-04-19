<?php
class ReportsfController extends Controller {
  public $menuname = 'reportsf';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  
  public function search() {
    header('Content-Type: application/json');
    $sfheaderid 		= GetSearchText(array('POST','GET','Q'),'sfheaderid');
		$plantid     		= GetSearchText(array('POST','GET'),'plantid',0,'int');
		$productid     		= GetSearchText(array('POST','GET'),'productid',0,'int');
		$plantcode     		= GetSearchText(array('POST','GET','Q'),'plantcode');
		$productname   		= GetSearchText(array('POST','GET','Q'),'productname');
		$sfdate    = GetSearchText(array('POST','GET','Q'),'sfdate');
		$sfno 		= GetSearchText(array('POST','GET','Q'),'sfno');
		$addressbookid      = GetSearchText(array('POST','GET'),'addressbookid',0,'int');
		$fullname      = GetSearchText(array('POST','GET','Q'),'fullname');
		$pocustno      = GetSearchText(array('POST','GET','Q'),'pocustno');
		$headernote 		= GetSearchText(array('POST','GET','Q'),'headernote');
		$customer 		= GetSearchText(array('POST','GET','Q'),'customer');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','sfheaderid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		if (isset($_GET['authso'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
				->from('sfheader t')
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
				((coalesce(sfheaderid,'') like :sfheaderid) 
				or (coalesce(b.plantcode,'') like :plantcode) 
				or (coalesce(d.fullname,'') like :fullname) 
				or (coalesce(sfno,'') like :sfno) 
				or (coalesce(pocustno,'') like :pocustno) 
				or (coalesce(sfdate,'') like :sfdate) 
				or (coalesce(a.fullname,'') like :customer) 
				or (coalesce(headernote,'') like :headernote)) 
				and t.recordstatus in (".getUserRecordStatus('listsf').")
				and t.plantid in (".getUserObjectValues('plant').") 
				and t.plantid = '".$plantid."' 
				",
			array(
				':sfheaderid' =>  $sfheaderid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $fullname ,
				':sfno' =>  $sfno ,
				':headernote' =>  $headernote ,
				':pocustno' =>  $pocustno ,
				':customer' =>  $customer ,
				':sfdate' =>  $sfdate
			))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
				->from('sfheader t')
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
				((coalesce(sfheaderid,'') like :sfheaderid) 
				and (coalesce(b.plantcode,'') like :plantcode) 
				and (coalesce(d.fullname,'') like :fullname) 
				and (coalesce(sfno,'') like :sfno) 
				and (coalesce(pocustno,'') like :pocustno) 
				and (coalesce(sfdate,'') like :sfdate) 
				and (coalesce(a.fullname,'') like :customer) 
				and (coalesce(headernote,'') like :headernote))
				and t.plantid in (".getUserObjectValues('plant').")".
				(($productname != '%%')?"
				and t.sfheaderid in (
				select distinct z.sfheaderid 
				from sfdetail z 
				join product za on za.productid = z.productid 
				where za.productname like '".$productname."'
				)":''), 
				array(
				':sfheaderid' =>  $sfheaderid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $fullname ,
				':sfno' =>  $sfno ,
				':headernote' =>  $headernote ,
				':customer' =>  $customer ,
				':pocustno' =>  $pocustno ,
				':sfdate' =>  $sfdate
      ))->queryScalar();
    }
    $result['total'] = $cmd;
     if (isset($_GET['authso'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,b.plantcode,d.employeeid,t.headernote,b.companyid,c.companyname,d.fullname as sales,a.fullname,
				e.addressname as addresstoname, f.addressname as addresspayname,g.paycode,h.currencyname,a.creditlimit,
				getamountbyso(t.sfheaderid) as totprice,
				(select sum(z.price) from sfdetail z where z.sfheaderid = t.sfheaderid and t.recordstatus > 1 and z.qty != z.giqty ) as sopriceoutstanding')
				->from('sfheader t')
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
				((coalesce(sfheaderid,'') like :sfheaderid) 
				or (coalesce(b.plantcode,'') like :plantcode) 
				or (coalesce(d.fullname,'') like :fullname) 
				or (coalesce(sfno,'') like :sfno) 
				or (coalesce(pocustno,'') like :pocustno) 
				or (coalesce(sfdate,'') like :sfdate) 
				or (coalesce(a.fullname,'') like :customer) 
				or (coalesce(headernote,'') like :headernote)) 
				and t.recordstatus in (".getUserRecordStatus('listsf').")
				and t.plantid in (".getUserObjectValues('plant').") 
				and t.plantid = '".$plantid."' 
				",
			array(
				':sfheaderid' =>  $sfheaderid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $fullname ,
				':sfno' =>  $sfno ,
				':headernote' =>  $headernote ,
				':pocustno' =>  $pocustno ,
				':customer' =>  $customer ,
				':sfdate' =>  $sfdate
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		} else {
			$cmd = Yii::app()->db->createCommand()->select('t.*,d.employeeid,b.plantcode,t.headernote,b.companyid,c.companyname,d.fullname as sales,a.fullname,
													e.addressname as addresstoname, f.addressname as addresspayname,g.paycode,h.currencyname,a.creditlimit,
				getamountbyso(t.sfheaderid) as totprice')
				->from('sfheader t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('sales i', 'i.salesid = t.salesid')
				->leftjoin('employee d', 'd.employeeid = i.employeeid')
				->leftjoin('address e', 'e.addressid = t.addresstoid')
				->leftjoin('address f', 'f.addressid = t.addresspayid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
			->where("((coalesce(sfheaderid,'') like :sfheaderid) 
				and (coalesce(b.plantcode,'') like :plantcode) 
				and (coalesce(d.fullname,'') like :fullname) 
				and (coalesce(sfno,'') like :sfno) 
				and (coalesce(pocustno,'') like :pocustno) 
				and (coalesce(sfdate,'') like :sfdate) 
				and (coalesce(a.fullname,'') like :customer) 
				and (coalesce(headernote,'') like :headernote))
				and t.plantid in (".getUserObjectValues('plant').")".
					(($productname != '%%')?"
				and t.sfheaderid in (
				select distinct z.sfheaderid 
				from sfdetail z 
				join product za on za.productid = z.productid 
				where za.productname like '".$productname."'
				)":''), 
				array(
				':sfheaderid' =>  $sfheaderid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $fullname ,
				':sfno' =>  $sfno ,
				':headernote' =>  $headernote ,
				':pocustno' =>  $pocustno ,
				':customer' =>  $customer ,
				':sfdate' =>  $sfdate 
			))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		}
		foreach ($cmd as $data) {
			$row[] = array(
				'sfheaderid' => $data['sfheaderid'],
				'sfdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['sfdate'])),
				'sfno' => $data['sfno'],
				'plantid' => $data['plantid'],
				'plantcode' => $data['plantcode'],
				'companyid' => $data['companyid'],
				'companyname' => $data['companyname'],
				'totprice' => Yii::app()->format->formatCurrency($data['totprice']),
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