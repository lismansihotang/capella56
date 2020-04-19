<?php
class SoheaderController extends Controller {
  public $menuname = 'soheader';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexDetail() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndextaxso() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchtaxso();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
			$id = rand(-1, -1000000000);
			echo CJSON::encode(array(
				'soheaderid' => $id,
			));
  }
  public function search() {
    header('Content-Type: application/json');
    $soheaderid 		= GetSearchText(array('POST','GET','Q'),'soheaderid');
		$plantid     		= GetSearchText(array('POST','GET'),'plantid',0,'int');
		$productid     		= GetSearchText(array('POST','GET'),'productid',0,'int');
		$plantcode     		= GetSearchText(array('POST','GET','Q'),'plantcode');
		$productname   		= GetSearchText(array('POST','GET','Q'),'productname');
		$sodate    = GetSearchText(array('POST','GET','Q'),'sodate');
		$sono 		= GetSearchText(array('POST','GET','Q'),'sono');
		$addressbookid      = GetSearchText(array('POST','GET'),'addressbookid',0,'int');
		$sales      = GetSearchText(array('POST','GET','Q'),'sales');
		$pocustno      = GetSearchText(array('POST','GET','Q'),'pocustno');
		$headernote 		= GetSearchText(array('POST','GET','Q'),'headernote');
		$customer 		= GetSearchText(array('POST','GET','Q'),'customer');
		$sales 		= GetSearchText(array('POST','GET','Q'),'sales');
		$recordstatus 		= GetSearchText(array('POST','GET','Q'),'recordstatus');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','soheaderid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		if (!isset($_GET['getdatacoa'])) {
		if (!isset($_GET['getdatasogi'])) {
		if (!isset($_GET['getdata'])) {
    if (isset($_GET['ppso'])) {
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
				or (coalesce(b.plantcode,'') like :plantcode) 
				or (coalesce(d.fullname,'') like :sales) 
				or (coalesce(sono,'') like :sono) 
				or (coalesce(pocustno,'') like :pocustno) 
				or (coalesce(sodate,'') like :sodate) 
				or (coalesce(a.fullname,'') like :customer) 
				or (coalesce(headernote,'') like :headernote)) 
				and t.recordstatus in (".getUserRecordStatus('listso').")
				and t.plantid in (".getUserObjectValues('plant').") 
				and t.plantid = '".$plantid."' 
				and t.sono is not null 
				and t.soheaderid in (
					select distinct zz.soheaderid 
					from sodetail zz 
					where (zz.qty + (zz.toleransi * zz.qty / 100)) > coalesce(zz.sppqty ,0)
				)
				",
			array(
				':soheaderid' =>  $soheaderid ,
				':plantcode' =>  $plantcode ,
				':sales' =>  $sales ,
				':sono' =>  $sono ,
				':pocustno' =>  $pocustno ,
				':sodate' =>  $sodate,
				':customer' =>  $customer ,
				':headernote' =>  $headernote ,
			))->queryScalar();
    } else if (isset($_GET['giso'])) {
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
				or (coalesce(b.plantcode,'') like :plantcode) 
				or (coalesce(d.fullname,'') like :sales) 
				or (coalesce(sono,'') like :sono) 
				or (coalesce(pocustno,'') like :pocustno) 
				or (coalesce(sodate,'') like :sodate) 
				or (coalesce(a.fullname,'') like :customer) 
				or (coalesce(headernote,'') like :headernote)) 
				and t.recordstatus in (".getUserRecordStatus('listso').")
				and t.plantid in (".getUserObjectValues('plant').") 
				and t.plantid = '".$plantid."' 
				and t.sono is not null 
				and t.soheaderid in (
					select distinct zz.soheaderid 
					from sodetail zz 
					where zz.qty > coalesce(zz.giqty ,0)
				)
				",
			array(
				':soheaderid' =>  $soheaderid ,
				':plantcode' =>  $plantcode ,
				':sales' =>  $sales ,
				':sono' =>  $sono ,
				':pocustno' =>  $pocustno ,
				':sodate' =>  $sodate,
				':customer' =>  $customer ,
				':headernote' =>  $headernote ,
			))->queryScalar();}
			else if (isset($_GET['socoa'])) {
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
				->leftjoin('sodetail j', 'j.soheaderid = t.soheaderid')
				->where("
				((coalesce(t.soheaderid,'') like :soheaderid) 
				or (coalesce(b.plantcode,'') like :plantcode) 
				or (coalesce(d.fullname,'') like :sales) 
				or (coalesce(sono,'') like :sono) 
				or (coalesce(pocustno,'') like :pocustno) 
				or (coalesce(sodate,'') like :sodate) 
				or (coalesce(a.fullname,'') like :customer) 
				or (coalesce(headernote,'') like :headernote)) 
				and t.recordstatus in (".getUserRecordStatus('listso').")
				and t.plantid in (".getUserObjectValues('plant').") 
				and t.sono is not null 
				and j.productid = '".$productid."' 
				",
			array(
				':soheaderid' =>  $soheaderid ,
				':plantcode' =>  $plantcode ,
				':sales' =>  $sales ,
				':sono' =>  $sono ,
				':pocustno' =>  $pocustno ,
				':sodate' =>  $sodate,
				':customer' =>  $customer ,
				':headernote' =>  $headernote ,
			))->queryScalar();}
			else if (isset($_GET['invso'])) {
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
				or (coalesce(b.plantcode,'') like :plantcode) 
				or (coalesce(d.fullname,'') like :sales) 
				or (coalesce(sono,'') like :sono) 
				or (coalesce(pocustno,'') like :pocustno) 
				or (coalesce(sodate,'') like :sodate) 
				or (coalesce(a.fullname,'') like :customer) 
				or (coalesce(headernote,'') like :headernote)) 
				and t.recordstatus = getwfmaxstatbywfname('appso')
				and t.recordstatus in (".getUserRecordStatus('listso').")
				and t.plantid in (".getUserObjectValues('plant').") 
				and t.plantid = '".$plantid."' 
				and t.sono is not null 
				and t.addressbookid = '".$addressbookid."' 
				",
			array(
				':soheaderid' =>  $soheaderid ,
				':plantcode' =>  $plantcode ,
				':sales' =>  $sales ,
				':sono' =>  $sono ,
				':pocustno' =>  $pocustno ,
				':sodate' =>  $sodate,
				':customer' =>  $customer ,
				':headernote' =>  $headernote ,
			))->queryScalar();
    } else if (isset($_GET['authso'])) {
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
				or (coalesce(b.plantcode,'') like :plantcode) 
				or (coalesce(d.fullname,'') like :fullname) 
				or (coalesce(sono,'') like :sono) 
				or (coalesce(pocustno,'') like :pocustno) 
				or (coalesce(sodate,'') like :sodate) 
				or (coalesce(a.fullname,'') like :customer) 
				or (coalesce(headernote,'') like :headernote)) 
				and t.recordstatus in (".getUserRecordStatus('listso').")
				and t.plantid in (".getUserObjectValues('plant').") 
				and t.sono is not null 
				and t.plantid = '".$plantid."' 
				",
			array(
				':soheaderid' =>  $soheaderid ,
				':plantcode' =>  $plantcode ,
				':sales' =>  $sales ,
				':sono' =>  $sono ,
				':pocustno' =>  $pocustno ,
				':sodate' =>  $sodate,
				':customer' =>  $customer ,
				':headernote' =>  $headernote ,
			))->queryScalar();
    } else {
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
				and (coalesce(headernote,'') like :headernote))
				and t.recordstatus in (".getUserRecordStatus('listso').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":''). 
					"
				and t.plantid in (".getUserObjectValues('plant').")".
				(($productname != '%%')?"
				and t.soheaderid in (
				select distinct z.soheaderid 
				from sodetail z 
				join product za on za.productid = z.productid 
				where za.productname like '".$productname."'
				)":''), 
				array(
				':soheaderid' =>  $soheaderid ,
				':plantcode' =>  $plantcode ,
				':sales' =>  $sales ,
				':sono' =>  $sono ,
				':pocustno' =>  $pocustno ,
				':sodate' =>  $sodate,
				':customer' =>  $customer ,
				':headernote' =>  $headernote ,
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['ppso'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,b.plantcode,d.employeeid,t.headernote,b.companyid,c.companyname,d.fullname as sales,a.fullname,
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
				->where("
				((coalesce(soheaderid,'') like :soheaderid) 
				or (coalesce(b.plantcode,'') like :plantcode) 
				or (coalesce(d.fullname,'') like :sales) 
				or (coalesce(sono,'') like :sono) 
				or (coalesce(pocustno,'') like :pocustno) 
				or (coalesce(sodate,'') like :sodate) 
				or (coalesce(a.fullname,'') like :customer) 
				or (coalesce(headernote,'') like :headernote)) 
				and t.recordstatus in (".getUserRecordStatus('listso').")
				and t.plantid in (".getUserObjectValues('plant').") 
				and t.plantid = '".$plantid."' 
				and t.sono is not null 
				and t.soheaderid in (
					select distinct zz.soheaderid 
					from sodetail zz 
					where (zz.qty + (zz.toleransi * zz.qty / 100)) > coalesce(zz.sppqty ,0)
				)
				",
			array(
				':soheaderid' =>  $soheaderid ,
				':plantcode' =>  $plantcode ,
				':sales' =>  $sales ,
				':sono' =>  $sono ,
				':pocustno' =>  $pocustno ,
				':sodate' =>  $sodate,
				':customer' =>  $customer ,
				':headernote' =>  $headernote ,
				))->order($sort . ' ' . $order)->queryAll();
		} else if (isset($_GET['giso'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,b.plantcode,d.employeeid,t.headernote,b.companyid,c.companyname,d.fullname as sales,a.fullname,
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
				->where("
				((coalesce(soheaderid,'') like :soheaderid) 
				or (coalesce(b.plantcode,'') like :plantcode) 
				or (coalesce(d.fullname,'') like :sales) 
				or (coalesce(sono,'') like :sono) 
				or (coalesce(pocustno,'') like :pocustno) 
				or (coalesce(sodate,'') like :sodate) 
				or (coalesce(a.fullname,'') like :customer) 
				or (coalesce(headernote,'') like :headernote)) 
				and t.recordstatus in (".getUserRecordStatus('listso').")
				and t.plantid in (".getUserObjectValues('plant').") 
				and t.plantid = '".$plantid."' 
				and t.sono is not null 
				and t.soheaderid in (
					select distinct zz.soheaderid 
					from sodetail zz 
					where zz.qty > coalesce(zz.giqty ,0)
				)
				",
			array(
				':soheaderid' =>  $soheaderid ,
				':plantcode' =>  $plantcode ,
				':sales' =>  $sales ,
				':sono' =>  $sono ,
				':pocustno' =>  $pocustno ,
				':sodate' =>  $sodate,
				':customer' =>  $customer ,
				':headernote' =>  $headernote ,
				))->order($sort . ' ' . $order)->queryAll();
		} 
		else if (isset($_GET['socoa'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,b.plantcode,d.employeeid,t.headernote,b.companyid,c.companyname,d.fullname as sales,a.fullname,
				e.addressname as addresstoname, f.addressname as addresspayname,g.paycode,h.currencyname,a.creditlimit,
				getamountbyso(t.soheaderid) as totprice,j.productid,
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
				->leftjoin('sodetail j', 'j.soheaderid = t.soheaderid')
				->where("
				((coalesce(t.soheaderid,'') like :soheaderid) 
				or (coalesce(b.plantcode,'') like :plantcode) 
				or (coalesce(d.fullname,'') like :sales) 
				or (coalesce(sono,'') like :sono) 
				or (coalesce(pocustno,'') like :pocustno) 
				or (coalesce(sodate,'') like :sodate) 
				or (coalesce(a.fullname,'') like :customer) 
				or (coalesce(headernote,'') like :headernote)) 
				and t.recordstatus in (".getUserRecordStatus('listso').")
				and t.plantid in (".getUserObjectValues('plant').") 
				and t.sono is not null 
				and j.productid = '".$productid."' 
				",
			array(
				':soheaderid' =>  $soheaderid ,
				':plantcode' =>  $plantcode ,
				':sales' =>  $sales ,
				':sono' =>  $sono ,
				':pocustno' =>  $pocustno ,
				':sodate' =>  $sodate,
				':customer' =>  $customer ,
				':headernote' =>  $headernote ,
				))->order($sort . ' ' . $order)->queryAll(); }
		else if (isset($_GET['invso'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,b.plantcode,d.employeeid,t.headernote,b.companyid,c.companyname,d.fullname as sales,a.fullname,
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
				->where("
				((coalesce(soheaderid,'') like :soheaderid) 
				or (coalesce(b.plantcode,'') like :plantcode) 
				or (coalesce(d.fullname,'') like :sales) 
				or (coalesce(sono,'') like :sono) 
				or (coalesce(pocustno,'') like :pocustno) 
				or (coalesce(sodate,'') like :sodate) 
				or (coalesce(a.fullname,'') like :customer) 
				or (coalesce(headernote,'') like :headernote)) 
				and t.recordstatus = getwfmaxstatbywfname('appso')
				and t.recordstatus in (".getUserRecordStatus('listso').")
				and t.plantid in (".getUserObjectValues('plant').") 
				and t.plantid = '".$plantid."' 
				and t.sono is not null 
				and t.addressbookid = '".$addressbookid."' 
				",
			array(
				':soheaderid' =>  $soheaderid ,
				':plantcode' =>  $plantcode ,
				':sales' =>  $sales ,
				':sono' =>  $sono ,
				':pocustno' =>  $pocustno ,
				':sodate' =>  $sodate,
				':customer' =>  $customer ,
				':headernote' =>  $headernote ,
				))->order($sort . ' ' . $order)->queryAll();
		} else if (isset($_GET['authso'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,b.plantcode,d.employeeid,t.headernote,b.companyid,c.companyname,d.fullname as sales,a.fullname,
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
				->where("
				((coalesce(soheaderid,'') like :soheaderid) 
				or (coalesce(b.plantcode,'') like :plantcode) 
				or (coalesce(d.fullname,'') like :sales) 
				or (coalesce(sono,'') like :sono) 
				or (coalesce(pocustno,'') like :pocustno) 
				or (coalesce(sodate,'') like :sodate) 
				or (coalesce(a.fullname,'') like :customer) 
				or (coalesce(headernote,'') like :headernote)) 
				and t.recordstatus in (".getUserRecordStatus('listso').")
				and t.plantid in (".getUserObjectValues('plant').") 
				and t.sono is not null 
				and t.plantid = '".$plantid."' 
				",
			array(
				':soheaderid' =>  $soheaderid ,
				':plantcode' =>  $plantcode ,
				':sales' =>  $sales ,
				':sono' =>  $sono ,
				':pocustno' =>  $pocustno ,
				':sodate' =>  $sodate,
				':customer' =>  $customer ,
				':headernote' =>  $headernote ,
				))->order($sort . ' ' . $order)->queryAll();
		} else {
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
				and (coalesce(headernote,'') like :headernote))
				and t.recordstatus in (".getUserRecordStatus('listso').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":''). 
					"
				and t.plantid in (".getUserObjectValues('plant').")".
					(($productname != '%%')?"
				and t.soheaderid in (
				select distinct z.soheaderid 
				from sodetail z 
				join product za on za.productid = z.productid 
				where za.productname like '".$productname."'
				)":''), 
				array(
				':soheaderid' =>  $soheaderid ,
				':plantcode' =>  $plantcode ,
				':sales' =>  $sales ,
				':sono' =>  $sono ,
				':pocustno' =>  $pocustno ,
				':sodate' =>  $sodate,
				':customer' =>  $customer ,
				':headernote' =>  $headernote ,
			))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		}
		foreach ($cmd as $data) {
			$row[] = array(
				'soheaderid' => $data['soheaderid'],
				'sodate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['sodate'])),
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
				'pocustdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['pocustdate'])),
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
		} else {
				$soheaderid = GetSearchText(array('POST','Q','GET'),'soheaderid',0,'int');
			$cmd = Yii::app()->db->createCommand("
				select a.soheaderid,a.addressbookid,a.plantid,c.fullname,a.headernote
				from  soheader a 
				join addressbook c on c.addressbookid = a.addressbookid
				where a.soheaderid = ".$soheaderid)->queryRow();
			$result = $cmd;
		}
		} else {
				$soheaderid = GetSearchText(array('POST','Q','GET'),'soheaderid',0,'int');
			$cmd = Yii::app()->db->createCommand("
				select a.soheaderid, a.addressbookid,a.addresstoid,a.pocustno,a.headernote
				from soheader a
				where a.soheaderid = ".$soheaderid)->queryRow();
			$result = $cmd;
		}
			} else {
				$soheaderid = GetSearchText(array('POST','Q','GET'),'soheaderid',0,'int');
			$cmd = Yii::app()->db->createCommand("
				select a.soheaderid, a.addressbookid,b.bomid,b.qty,a.headernote
				from soheader a
				join sodetail b on b.soheaderid = a.soheaderid
				where a.soheaderid = ".$soheaderid." and b.productid = ".$productid)->queryRow();
			$result = $cmd;
		}
    return CJSON::encode($result);
	}
	public function actiondeliveryschedule() {
    $items = array();
    $cmd   = Yii::app()->db->createCommand()
			->select('b.sono,t.delvdate,c.fullname as customer,a.productname,t.qty,t.qty2,e.uomcode,t.giqty,t.giqty2,(t.qty-t.giqty) as qtyout')
			->from('sodetail t')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('soheader b', 'b.soheaderid = t.soheaderid')
			->leftjoin('addressbook c', 'c.addressbookid = b.addressbookid')
			->leftjoin('unitofmeasure e', 'e.unitofmeasureid = t.uomid')
			->where("delvdate >= '" . $_GET['start'] . "' 
				and delvdate <= '" . $_GET['end'] . "'  
				and b.sono is not null 
				and t.slocid in (".GetUserObjectValues('sloc').") 
				")->queryAll();
    
    foreach ($cmd as $data) {
      $items[] = array(
        'title' => 'Customer: '.$data['customer']."\n No OS:".$data['sono'].
					"\n Artikel: ".$data['productname'].
					"\n Tgl Kirim: ".date(Yii::app()->params['dateviewfromdb'], strtotime($data['delvdate'])).
					"\n Qty OS: ". Yii::app()->format->formatNumber($data['qty']).''.$data['uomcode'].
					"\n Qty SJ: ".Yii::app()->format->formatNumber($data['giqty']).
					"\n Qty Outs.: ".Yii::app()->format->formatNumber($data['qtyout']),
        'start' => $data['delvdate'],
        'end' => $data['delvdate'],
        'constraint' => 'businessHours'
      );
    }
    echo CJSON::encode($items);
  }
	public function actionSearchdetail() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'sodetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
		$footer             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('sodetail t')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('sloc d', 'd.slocid = t.slocid')
			->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')
			->where('t.soheaderid = :soheaderid',
			array(
				':soheaderid' => $id
			))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
			->select('t.*,a.productcode,a.productname,d.sloccode,e.materialtypecode,
				(select b.bomversion from billofmaterial b where b.bomid = t.bomid) as bomversion,
				getamountdetailbyso(t.soheaderid,t.sodetailid) as totprice,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code,
				getstdqty(a.productid) as stdqty,
				getstdqty2(a.productid) as stdqty2,
				getstdqty3(a.productid) as stdqty3,
				getstock(a.productid,t.uomid,t.slocid) as qtystock
				')
			->from('sodetail t')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('sloc d', 'd.slocid = t.slocid')
			->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')
			->where('t.soheaderid = :soheaderid', array(
			':soheaderid' => $id
			))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
			if ($data['qtystock'] >= $data['qty']) {
				$stockcount = 0;
			} else {
				$stockcount = 1;
			}
      $row[] = array(
        'sodetailid' => $data['sodetailid'],
        'soheaderid' => $data['soheaderid'],
        'productid' => $data['productid'],
				'productcode' => $data['productcode'],
				'productname' => $data['productname'],
				'materialtypecode' => $data['materialtypecode'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'giqty' => Yii::app()->format->formatNumber($data['giqty']),
        'sppqty' => Yii::app()->format->formatNumber($data['sppqty']),
        'opqty' => Yii::app()->format->formatNumber($data['opqty']),
        'qtystock' => Yii::app()->format->formatNumber($data['qtystock']),
        'stdqty' => Yii::app()->format->formatNumber($data['stdqty']),
        'stdqty2' => Yii::app()->format->formatNumber($data['stdqty2']),
        'uomid' => $data['uomid'],
        'stockcount' => $stockcount,
        'uomcode' => $data['uomcode'],
        'qty2' => Yii::app()->format->formatNumber($data['qty2']),
        'uom2id' => $data['uom2id'],
        'uom2code' => $data['uom2code'],
        'qty3' => Yii::app()->format->formatNumber($data['qty3']),
        'uom3id' => $data['uom3id'],
        'uom3code' => $data['uom3code'],
				'delvdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['delvdate'])),
				'price' => Yii::app()->format->formatCurrency($data['price']),
				'totprice' => Yii::app()->format->formatCurrency($data['totprice']),
        'bomid' => $data['bomid'],
        'bomversion' => $data['bomversion'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
				'toleransi' => Yii::app()->format->formatNumber($data['toleransi']),
				'itemnote' => $data['itemnote']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		$sql = "select getamountbyso(t.soheaderid) as total from soheader t where soheaderid = ".$id;
		$cmd = Yii::app()->db->createCommand($sql)->queryRow();
		$footer[] = array(
      'productname' => 'Total',
      'totprice' => Yii::app()->format->formatCurrency($cmd['total'])
    );
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionSearchTaxso() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'taxsoid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('taxso t')
					->leftjoin('tax a', 'a.taxid = t.taxid')
					->where('t.soheaderid = :soheaderid',
					array(
						':soheaderid' => $id
					))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
					->select('t.*,a.taxcode,a.taxvalue')
					->from('taxso t')
					->leftjoin('tax a', 'a.taxid = t.taxid')
					->where('t.soheaderid = :soheaderid', array(
		':soheaderid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'taxsoid' => $data['taxsoid'],
        'soheaderid' => $data['soheaderid'],
        'taxid' => $data['taxid'],
				'taxcode' => $data['taxcode'],
				'taxvalue' => $data['taxvalue']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
  public function actionReject() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call RejectSo(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveSo(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    } else {
      GetMessage(true, 'chooseone');
    }
  }
	public function actionHoldSO() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call HoldSo(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    } else {
      GetMessage(true, 'chooseone');
    }
  }
	public function actionOpenSO() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call OpenSo(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    } else {
      GetMessage(true, 'chooseone');
    }
  }
	public function actionCloseSO() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call CloseSo(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  private function ModifyData($connection,$arraydata) {
		$id = (int)$arraydata[0];
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$sql = 'call Modifsoheader(:vid,:vsono,:vsodate,:vplantid,:vaddressbookid,:vpocustno,:vpocustdate,:vsalesid,
							:vpaymentmethodid,:vaddresstoid,:vaddresspayid,:vcurrencyid,:vcurrencyrate,:visexport,:vissample,
							:visavalan,
							:vheadernote,:vdatauser)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vsono', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vsodate', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vpocustno', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vpocustdate', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vsalesid', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vpaymentmethodid', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vaddresstoid', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vaddresspayid', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyrate', $arraydata[12], PDO::PARAM_STR);
		$command->bindvalue(':visexport', $arraydata[13], PDO::PARAM_STR);
		$command->bindvalue(':vissample', $arraydata[14], PDO::PARAM_STR);
		$command->bindvalue(':visavalan', $arraydata[15], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[16], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();					
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-soheader"]["name"]);
		if (move_uploaded_file($_FILES["file-soheader"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$oldid = '';$pid = '';
			$connection  = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue(); //A
					if ((int)$id > 0) {
						if ($oldid != $id) {
							$oldid = $id;
							$plantcode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue(); //B
							$plantid = Yii::app()->db->createCommand("select plantid from plant where plantcode = '".$plantcode."'")->queryScalar();
							$sodate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(2, $row)->getValue())); //C
							$sono = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue(); //C
							$customer = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue(); //D
							$customerid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '".$customer."'")->queryScalar();
							$pocustno = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue(); //E
							$pocustdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(5, $row)->getValue())); //C
							$addressto = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue(); //F
							$addresstoid = Yii::app()->db->createCommand("select addressid from address where addressname = '".$addressto."'")->queryScalar();
							$addresspay = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue(); //G
							$addresspayid = Yii::app()->db->createCommand("select addressid from address where addressname = '".$addresspay."'")->queryScalar();
							$sales = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue(); //H
							$salesid = Yii::app()->db->createCommand("select salesid from sales a 
								join employee b on b.employeeid = a.employeeid 
								where b.fullname = '".$sales."' 
								and a.plantid = ".$plantid)->queryScalar();
							$paycode = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue(); //I
							$payid = Yii::app()->db->createCommand("select paymentmethodid from paymentmethod where paycode = '".$paycode."'")->queryScalar();
							$symbol = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue(); //J
							$currencyid = Yii::app()->db->createCommand("select currencyid from currency where symbol = '".$symbol."'")->queryScalar();
							$currencyrate = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue(); //K 
							$isexport = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue(); //L
							$issample = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue(); //M
							$isavalan = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue(); //N 
							$description = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue(); //O
							$this->ModifyData($connection,array(-1,$sono,
								$sodate,$plantid,$customerid,$pocustno,$pocustdate,
								$salesid,$payid,$addresstoid,$addresspayid,
								$currencyid,$currencyrate,$isexport,$issample,$isavalan,$description));
							$pid = Yii::app()->db->createCommand("
								select soheaderid 
								from soheader a
								where a.plantid = ".$plantid." 
								and a.sodate = '".$sodate."' 
								and a.addressbookid = ".$customerid." 
								and a.pocustno = '".$pocustno."' 
								and a.salesid = ".$salesid." 
								limit 1
							")->queryScalar();
							$taxcode = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue(); //P
							$taxid = Yii::app()->db->createCommand("select taxid from tax where taxcode = '".$taxcode."'")->queryScalar();
							$this->Modifytaxso($connection,array('',
								$pid,
								$taxid
							));
						} 
						$productname = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue(); //Q
						$sql = "select productid from product where productname = :productname";
						$command=$connection->createCommand($sql);
						$command->bindvalue(':productname',$productname,PDO::PARAM_STR);
						$productid = $command->queryScalar();
						$qty = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue(); //R
						$uomcode = $objWorksheet->getCellByColumnAndRow(19, $row)->getValue(); //S
						$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$qty2 = $objWorksheet->getCellByColumnAndRow(20, $row)->getValue(); //T
						$uomcode = $objWorksheet->getCellByColumnAndRow(21, $row)->getValue(); //U
						$uom2id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$qty3 = $objWorksheet->getCellByColumnAndRow(22, $row)->getValue(); //T
						$uomcode = $objWorksheet->getCellByColumnAndRow(23, $row)->getValue(); //U
						$uom3id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$price = $objWorksheet->getCellByColumnAndRow(24, $row)->getValue(); //V
						$toleransi = $objWorksheet->getCellByColumnAndRow(25, $row)->getValue(); //W
						$delvdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(26, $row)->getValue())); //X
						$bomversion = $objWorksheet->getCellByColumnAndRow(27, $row)->getValue(); //Y
						$sql = "select bomid from billofmaterial where bomversion = :bomversion and productid = :productid and plantid = :plantid";
						$command=$connection->createCommand($sql);
						$command->bindvalue(':bomversion',$bomversion,PDO::PARAM_STR);
						$command->bindvalue(':productid',$productid,PDO::PARAM_STR);
						$command->bindvalue(':plantid',$plantid,PDO::PARAM_STR);
						$bomid = $command->queryScalar();
						$sloccode = $objWorksheet->getCellByColumnAndRow(30, $row)->getValue(); //Y
						$slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
						$itemnote = $objWorksheet->getCellByColumnAndRow(31, $row)->getValue(); //Z
						$this->ModifyDetail($connection,array(
							'',
							$pid,
							$productid,
							$delvdate,
							$qty,
							$uomid,
							$qty2,
							$uom2id,
							$qty3,
							$uom3id,
							$price,
							$bomid,
							$toleransi,
							$slocid,
							$itemnote
						));
					}
				}
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    }
	}
	public function actionSave() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['soheader-soheaderid'])?$_POST['soheader-soheaderid']:''),
			$_POST['soheader-sono'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['soheader-sodate'])),
				$_POST['soheader-plantid'],$_POST['soheader-addressbookid'],$_POST['soheader-pocustno'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['soheader-pocustdate'])),
				$_POST['soheader-salesid'],$_POST['soheader-paymentmethodid'],
				$_POST['soheader-addresstoid'],$_POST['soheader-addresspayid'],$_POST['soheader-currencyid'],$_POST['soheader-currencyrate'],
				(isset($_POST['soheader-isexport']) ? 1 : 0),(isset($_POST['soheader-issample']) ? 1 : 0),
				(isset($_POST['soheader-isavalan']) ? 1 : 0),$_POST['soheader-headernote']));
			$transaction->commit();
			GetMessage(false, getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
	private function ModifyDetail($connection,$arraydata){
		if ($arraydata[0] == '') {
			$sql     = 'call InsertSodetail(:vsoheaderid,:vproductid,:vdelvdate,:vqty,:vuomid,:vqty2,:vuom2id,:vqty3,:vuom3id,
					:vprice,:vbomid,:vtoleransidown,:vslocid,:vitemnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call UpdateSodetail(:vid,:vsoheaderid,:vproductid,:vdelvdate,:vqty,:vuomid,:vqty2,:vuom2id,:vqty3,:vuom3id,
					:vprice,:vbomid,:vtoleransidown,:vslocid,:vitemnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vsoheaderid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vdelvdate', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vqty', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vqty2', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vuom2id', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vqty3', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vuom3id', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vprice', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vbomid', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vtoleransidown', $arraydata[12], PDO::PARAM_STR);
		$command->bindvalue(':vslocid', $arraydata[13], PDO::PARAM_STR);
		$command->bindvalue(':vitemnote', $arraydata[14], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavedetail() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDetail($connection,array(
				(isset($_POST['sodetailid'])?$_POST['sodetailid']:''),
				$_POST['soheaderid'],
				$_POST['productid'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['delvdate'])),
				$_POST['qty'],
				$_POST['uomid'],
				$_POST['qty2'],
				$_POST['uom2id'],
				$_POST['qty3'],
				$_POST['uom3id'],
				$_POST['price'],
				$_POST['bomid'],
				$_POST['toleransi'],
				$_POST['slocid'],
				$_POST['itemnote']
			));
      $transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
	private function Modifytaxso($connection,$arraydata) {
		if ($arraydata[0] == '') {
			$sql     = 'call InsertTaxso(:vsoheaderid,:vtaxid,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call UpdateTaxso(:vid,:vsoheaderid,:vtaxid,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vsoheaderid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vtaxid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavetaxso() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->Modifytaxso($connection,array(
				(isset($_POST['taxsoid'])?$_POST['taxsoid']:''),
				$_POST['soheaderid'],
				$_POST['taxid'],
			));
      $transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
  public function actionPurge() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call PurgeSOheader(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
          $command->bindvalue(':vid', $id, PDO::PARAM_STR);
          $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
          $command->execute();
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  public function actionPurgedetail() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgesodetail(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  public function actionPurgeTaxso() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgetaxso(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    } else {
      GetMessage(true, 'chooseone');
    }
  }
	public function actionGeneratepocustno() {
		$sql = "select a.pocustno,b.invoiceartaxno
			from soheader a 
			left join invoicear b on b.soheaderid=a.soheaderid
			where a.soheaderid = ".$_POST['id']." 
			";
		$data = Yii::app()->db->createCommand($sql)->queryRow();
		echo CJSON::encode(array(
			'pocustno' => $data['pocustno'],
			'invoiceartaxno' => $data['invoiceartaxno'],
		));
		Yii::app()->end();
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select g.companyid, a.soheaderid,a.sono, b.fullname as customername, a.sodate, c.paymentname, h.phoneno, 
			a.addressbookid, a.headernote,a.recordstatus,h.addressname,i.addressname as addresspayname,j.fullname as salesname,
			a.currencyrate,k.symbol,a.pocustno
      from soheader a
      join addressbook b on b.addressbookid = a.addressbookid
		  join sales d on d.salesid = a.salesid
			join employee j on j.employeeid = d.employeeid 
      join paymentmethod c on c.paymentmethodid = a.paymentmethodid
			join plant f on f.plantid = a.plantid 
			join company g on g.companyid = f.companyid 
			left join address h on h.addressid = a.addresstoid
			left join address i on i.addressid = a.addresspayid
			left join currency k on k.currencyid = a.currencyid 
			";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.soheaderid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = 'Sales Order';
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        20,
        100,
        30,
        60
      ));
      $this->pdf->row(array(
        'Customer',
        ' : ' . $row['customername'],
        'Sales Order No',
        ' : ' . $row['sono']
      ));
      $this->pdf->row(array(
        'Po Cust',
        ' : ' . $row['pocustno'],
        'SO Date',
        ' : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['sodate']))
      ));
      $this->pdf->row(array(
        'Phone',
        ' : ' . $row['phoneno'],
        'Sales',
        ' : ' . $row['salesname']
      ));
      $this->pdf->row(array(
        'Address',
        ' : ' . $row['addressname'],
        'Payment',
        ' : ' . $row['paymentname']
      ));
      $sql1        = "select a.soheaderid,c.uomcode,a.qty,a.price * ".$row['currencyrate']." as price,(qty * price * ".$row['currencyrate'].") as total,b.productname,
			a.itemnote,a.delvdate
			from sodetail a
			left join soheader f on f.soheaderid = a.soheaderid 
			left join product b on b.productid = a.productid
			left join unitofmeasure c on c.unitofmeasureid = a.uomid
			where a.soheaderid = " . $row['soheaderid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $total       = 0;
      $totalqty    = 0;
      $this->pdf->sety($this->pdf->gety() +0);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        20,
        15,
        110,
        30,
        30
      ));
      $this->pdf->colheader = array(
        'Qty',
        'Units',
        'Description',
        'Item Note',
        'Tgl Kirim'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'R',
        'C',
        'L',
        'L',
        'R',
        'L'
      );
      foreach ($dataReader1 as $row1) {
        $this->pdf->row(array(
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          $row1['productname'],
          $row1['itemnote'],
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['delvdate']))
        ));
        $total    = $row1['total'] + $total;
        $totalqty = $row1['qty'] + $totalqty;
      }
      $this->pdf->row(array(
        Yii::app()->format->formatNumber($totalqty),
        '',
        'Total',
        '',
        ''
      ));
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->colalign = array(
        'C',
        'C',
      );
      $this->pdf->setwidths(array(
        35,
        170,
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none',
      ));   
			$this->pdf->coldetailalign = array(
        'L',
        'L',
      );
      $this->pdf->row(array(
        'Ship To',
        $row['addressname']
      ));
      $this->pdf->row(array(
        'Note',
        $row['headernote']
      ));
      $this->pdf->checkNewPage(10);
    }
    $this->pdf->Output();
  }
	public function actionDownxls() {
    $this->menuname = 'solist';
    parent::actionDownxls();
		$soheaderid 		= GetSearchText(array('POST','GET','Q'),'soheaderid');
		$plantcode     		= GetSearchText(array('POST','GET','Q'),'plantcode');
		$productname   		= GetSearchText(array('POST','GET','Q'),'productname');
		$sodate    = GetSearchText(array('POST','GET','Q'),'sodate');
		$sono 		= GetSearchText(array('POST','GET','Q'),'sono');
		$fullname      = GetSearchText(array('POST','GET','Q'),'fullname');
		$sales      = GetSearchText(array('POST','GET','Q'),'sales');
		$pocustno      = GetSearchText(array('POST','GET','Q'),'pocustno');
		$headernote 		= GetSearchText(array('POST','GET','Q'),'headernote');
		$customer 		= GetSearchText(array('POST','GET','Q'),'customer');
		$price = getUserObjectValues($menuobject='currency');
		$sql = "select g.companyid, a.soheaderid,a.sono, b.fullname as customername, a.sodate, c.paycode, h.phoneno, 
			a.addressbookid, a.headernote,a.recordstatus,h.addressname,i.addressname as addresspayname,j.fullname as salesname,
			a.currencyrate,k.symbol,f.plantcode,a.pocustno,a.isexport,a.isavalan,a.issample,s.taxcode,m.productname,n.uomcode,
			o.uomcode as uom2code,l.qty,l.qty2,l.price,l.toleransi,l.delvdate,p.bomversion,q.sloccode,l.itemnote,
			getstock(l.productid,l.uomid,l.slocid) as qtystock,giqty,sppqty,opqty
      from soheader a
      left join addressbook b on b.addressbookid = a.addressbookid
		  left join sales d on d.salesid = a.salesid
			left join employee j on j.employeeid = d.employeeid 
      left join paymentmethod c on c.paymentmethodid = a.paymentmethodid
			left join plant f on f.plantid = a.plantid 
			left join company g on g.companyid = f.companyid 
			left join address h on h.addressid = a.addresstoid
			left join address i on i.addressid = a.addresspayid
			left join currency k on k.currencyid = a.currencyid 
			left join sodetail l on l.soheaderid = a.soheaderid 
			left join product m on m.productid = l.productid 
			left join unitofmeasure n on n.unitofmeasureid = l.uomid 
			left join unitofmeasure o on o.unitofmeasureid = l.uom2id 
			left join billofmaterial p on p.bomid = l.bomid 
			left join sloc q on q.slocid = l.slocid 
			left join taxso r on r.soheaderid = a.soheaderid 
			left join tax s on s.taxid = r.taxid 
			";    
		$sql .= " where coalesce(a.soheaderid,'') like '".$soheaderid."' 
			and coalesce(f.plantcode,'') like '".$plantcode."' 
			and coalesce(a.sodate,'') like '".$sodate."' 
			and coalesce(b.fullname,'') like '".$customer."' 
			and coalesce(j.fullname,'') like '".$sales."' 
			and coalesce(a.pocustno,'') like '".$pocustno."' 
			and coalesce(a.headernote,'') like '".$headernote."' 
			and coalesce(a.sono,'') like '".$sono."'".
			(($productname != '%%')?"
				and coalesce(m.productname,'') like '".$productname."'
			":'');
		if ($_GET['id'] !== '') {
      $sql = $sql . " and a.soheaderid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 2;$nourut=0;$oldbom='';
		foreach ($dataReader as $row) {
			if ($oldbom != $row['soheaderid']) {
				$nourut+=1;
				$oldbom = $row['soheaderid'];
			}
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i, $nourut)
				->setCellValueByColumnAndRow(1, $i, $row['soheaderid'])
				->setCellValueByColumnAndRow(2, $i, $row['plantcode'])
				->setCellValueByColumnAndRow(3, $i, date(Yii::app()->params['dateviewfromdb'], strtotime($row['sodate'])))
				->setCellValueByColumnAndRow(4, $i, $row['customername'])
				->setCellValueByColumnAndRow(5, $i, $row['pocustno'])
				->setCellValueByColumnAndRow(6, $i, $row['addressname'])
				->setCellValueByColumnAndRow(7, $i, $row['addresspayname'])
				->setCellValueByColumnAndRow(8, $i, $row['salesname'])
				->setCellValueByColumnAndRow(9, $i, $row['paycode'])
				->setCellValueByColumnAndRow(10, $i, $row['symbol'])
				->setCellValueByColumnAndRow(11, $i, $row['currencyrate'])
				->setCellValueByColumnAndRow(12, $i, $row['isexport'])
				->setCellValueByColumnAndRow(13, $i, $row['issample'])
				->setCellValueByColumnAndRow(14, $i, $row['isavalan'])
				->setCellValueByColumnAndRow(15, $i, $row['headernote'])
				->setCellValueByColumnAndRow(16, $i, $row['taxcode'])
				->setCellValueByColumnAndRow(17, $i, $row['productname'])
				->setCellValueByColumnAndRow(18, $i, Yii::app()->format->formatNumber($row['qtystock']))
				->setCellValueByColumnAndRow(19, $i, Yii::app()->format->formatNumber($row['qty']))
				->setCellValueByColumnAndRow(20, $i, Yii::app()->format->formatNumber($row['giqty']))
				->setCellValueByColumnAndRow(21, $i, Yii::app()->format->formatNumber($row['sppqty']))
				->setCellValueByColumnAndRow(22, $i, Yii::app()->format->formatNumber($row['opqty']))
				->setCellValueByColumnAndRow(23, $i, $row['uomcode'])
				->setCellValueByColumnAndRow(24, $i, Yii::app()->format->formatNumber($row['qty2']))
				->setCellValueByColumnAndRow(25, $i, $row['uom2code'])
				->setCellValueByColumnAndRow(26, $i, ($price==1)?$row['price']:'-')
				->setCellValueByColumnAndRow(27, $i, $row['toleransi'])
				->setCellValueByColumnAndRow(28, $i, date(Yii::app()->params['dateviewfromdb'], strtotime($row['delvdate'])))
				->setCellValueByColumnAndRow(29, $i, $row['bomversion'])
				->setCellValueByColumnAndRow(30, $i, $row['sloccode'])
				->setCellValueByColumnAndRow(31, $i, $row['itemnote'])
			;
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
  }
}