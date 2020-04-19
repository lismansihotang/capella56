<?php
class ReportpoController extends Controller {
  public $menuname = 'reportpo';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $poheaderid 		= GetSearchText(array('POST','Q'),'poheaderid');
		$plantid     		= GetSearchText(array('POST','Q','GET'),'plantid',0,'int');
		$plantcode     		= GetSearchText(array('POST','Q'),'plantcode');
		$productcode   		= GetSearchText(array('POST','Q','GET'),'productcode');
		$productname   		= GetSearchText(array('POST','Q','GET'),'productname');
		$requestedby   		= GetSearchText(array('POST','Q','GET'),'requestedby');
		$prno   		= GetSearchText(array('POST','Q','GET'),'prno');
    $podate    = GetSearchText(array('POST','Q'),'podate');
    $pono 		= GetSearchText(array('POST','Q'),'pono');
    $addressbookid      = GetSearchText(array('POST','Q','GET'),'addressbookid',0,'int');
		$supplier      = GetSearchText(array('POST','Q'),'supplier');
		$pocustno      = GetSearchText(array('POST','Q'),'pocustno');
		$headernote 		= GetSearchText(array('POST','Q'),'headernote');
		$recordstatus 		= GetSearchText(array('POST','Q'),'recordstatus');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','poheaderid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('poheader t')
			->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
			->leftjoin('plant b', 'b.plantid = t.plantid')
			->leftjoin('company c', 'c.companyid = b.companyid')
			->leftjoin('addresscontact d', 'd.addresscontactid = t.addresscontactid')
			->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
			->leftjoin('currency h', 'h.currencyid = t.currencyid')
			->where("
				((coalesce(poheaderid,'') like :poheaderid) 
				and (coalesce(plantcode,'') like :plantcode) 
				and (coalesce(fullname,'') like :fullname) 
				and (coalesce(pono,'') like :pono)  
				and (coalesce(podate,'') like :podate) 
				and (coalesce(headernote,'') like :headernote))  
				and t.plantid in (".getUserObjectValues('plant').") 
			".
							(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					(($productname != '%%')?"
				and t.poheaderid in (
				select distinct z.poheaderid 
				from podetail z 
				join product za on za.productid = z.productid 
				where za.productname like '".$productname."'
				)":'').
				(($prno != '%%')?"
				and t.poheaderid in (
				select distinct z.poheaderid 
				from podetail z 
				left join prheader za on za.prheaderid = z.prheaderid 
				where coalesce(za.prno,'') like '".$prno."'
				)":'').
				(($requestedby != '%%')?"
				and t.poheaderid in (
				select distinct z.poheaderid 
				from podetail z 
				left join requestedby za on za.requestedbyid = z.requestedbyid 
				where coalesce(za.requestedbycode,'') like '".$requestedby."'
				)":''), 
			array(
			':poheaderid' => '%' . $poheaderid . '%',
			':plantcode' => '%' . $plantcode . '%',
			':fullname' => '%' . $supplier . '%',
			':pono' => '%' . $pono . '%',
			':headernote' => '%' . $headernote . '%',
			':podate' => '%' . $podate . '%'
			))->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()->select('t.*,b.plantcode,t.headernote,b.companyid,c.companyname,a.fullname,c.billto,b.addresstoname,
			g.paycode,h.currencyname,a.creditlimit,d.addresscontactname,
			(select sum(z.price) from podetail z where z.poheaderid = t.poheaderid) as totprice,
			(select sum(z.price) from podetail z where z.poheaderid = t.poheaderid and t.recordstatus > 1 and z.qty > z.grqty ) as pooutstanding')
			->from('poheader t')
			->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
			->leftjoin('plant b', 'b.plantid = t.plantid')
			->leftjoin('company c', 'c.companyid = b.companyid')
			->leftjoin('addresscontact d', 'd.addresscontactid = t.addresscontactid')
			->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
			->leftjoin('currency h', 'h.currencyid = t.currencyid')
			->where("
				((coalesce(poheaderid,'') like :poheaderid) 
				and (coalesce(plantcode,'') like :plantcode) 
				and (coalesce(fullname,'') like :fullname) 
				and (coalesce(pono,'') like :pono)  
				and (coalesce(podate,'') like :podate) 
				and (coalesce(headernote,'') like :headernote)) 
				and t.plantid in (".getUserObjectValues('plant').") 
			".
							(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					(($productname != '%%')?"
				and t.poheaderid in (
				select distinct z.poheaderid 
				from podetail z 
				join product za on za.productid = z.productid 
				where za.productname like '".$productname."'
				)":'').
				(($prno != '%%')?"
				and t.poheaderid in (
				select distinct z.poheaderid 
				from podetail z 
				left join prheader za on za.prheaderid = z.prheaderid 
				where coalesce(za.prno,'') like '".$prno."'
				)":'').
				(($requestedby != '%%')?"
				and t.poheaderid in (
				select distinct z.poheaderid 
				from podetail z 
				left join requestedby za on za.requestedbyid = z.requestedbyid 
				where coalesce(za.requestedbycode,'') like '".$requestedby."'
				)":''), 
			array(
				':poheaderid' => '%' . $poheaderid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':fullname' => '%' . $supplier . '%',
				':pono' => '%' . $pono . '%',
				':headernote' => '%' . $headernote . '%',
				':podate' => '%' . $podate . '%'
			))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		foreach ($cmd as $data) {
			$row[] = array(
				'poheaderid' => $data['poheaderid'],
				'podate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['podate'])),
				'pono' => $data['pono'],
				'plantid' => $data['plantid'],
				'plantcode' => $data['plantcode'],
				'addresstoname' => $data['addresstoname'],
				'companyid' => $data['companyid'],
				'companyname' => $data['companyname'],
				'billto' => $data['billto'],
				'totprice' => Yii::app()->format->formatCurrency($data['totprice']),
				'addressbookid' => $data['addressbookid'],
				'fullname' => $data['fullname'],
				'addresscontactid' => $data['addresscontactid'],
				'addresscontactname' => $data['addresscontactname'],
				'isimport' => $data['isimport'],
				'isjasa' => $data['isjasa'],
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