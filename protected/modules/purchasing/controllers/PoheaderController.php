<?php
class PoheaderController extends Controller {
  public $menuname = 'poheader';
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
  public function actionIndexpojasa() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchpojasa();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexporesult() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchporesult();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndextaxpo() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchtaxpo();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
			$id = rand(-1, -1000000000);
			echo CJSON::encode(array(
				'poheaderid' => $id,
			));
  }
	public function actionGeneratedetail() {
    if (isset($_POST['id'])) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GeneratePRPOJASA(:vid, :vhid, :vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_INT);
        $command->execute();
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    }
    Yii::app()->end();
  }
  public function search() {
    header("Content-Type: application/json");
		$plantid = GetSearchText(array('POST','GET'),'plantid',0,'int');
		$poheaderid = GetSearchText(array('POST','Q'),'poheaderid');
		$plantcode     		= GetSearchText(array('POST','Q'),'plantcode');
		$productcode   		= GetSearchText(array('POST','Q'),'productcode');
		$productname   		= GetSearchText(array('POST','Q'),'productname');		
		$isjasa   		= GetSearchText(array('GET'),'isjasa',0,'int');
    $podate    = GetSearchText(array('POST','Q'),'podate');
    $pono 		= GetSearchText(array('POST','Q'),'pono');
    $addressbookid      = GetSearchText(array('POST','GET'),'addressbookid',0,'int');
		$supplier      = GetSearchText(array('POST','Q'),'supplier');		
		$headernote 		= GetSearchText(array('POST','Q'),'headernote');
		$requestedby 		= GetSearchText(array('POST','Q'),'requestedby');
		$prno 		= GetSearchText(array('POST','Q'),'prno');
		$recordstatus 		= GetSearchText(array('POST','Q'),'recordstatus');
    $page = GetSearchText(array('POST'),'page',1,'int');
		$rows = GetSearchText(array('POST'),'rows',10,'int');
		$sort = GetSearchText(array('POST'),'sort','poheaderid','int');
		$order = GetSearchText(array('POST'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		if (!isset($_GET['getdata'])) {
			if (isset($_GET['grpo'])) {
				if (($isjasa == '0') || ($isjasa == '')) {
					$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
						->from('poheader t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addresscontact d', 'd.addresscontactid = t.addresscontactid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
						((coalesce(t.poheaderid,'') like :poheaderid) 
				or (coalesce(a.fullname,'') like :fullname) 
				or (coalesce(t.pono,'') like :pono)  
				or (coalesce(b.plantcode,'') like :plantcode)  
				or (coalesce(t.podate,'') like :podate) 
				or (coalesce(t.headernote,'') like :headernote)) 
						and t.pono is not null 
						and t.recordstatus in (".getUserRecordStatus('listpo').")
						and t.plantid = ".$plantid."
						and t.isjasa = 0
						and t.poheaderid in (select distinct z.poheaderid 
							from podetail z 
							where z.qty > z.grqty)
						",
					array(
				':poheaderid' =>  $poheaderid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $supplier ,
				':pono' =>  $pono ,
				':headernote' =>  $headernote ,
				':podate' =>  $podate 
			))->queryScalar();
				} else {
					$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
						->from('poheader t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addresscontact d', 'd.addresscontactid = t.addresscontactid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
						((coalesce(t.poheaderid,'') like :poheaderid) 
				or (coalesce(a.fullname,'') like :fullname) 
				or (coalesce(t.pono,'') like :pono)  
				or (coalesce(b.plantcode,'') like :plantcode)  
				or (coalesce(t.podate,'') like :podate) 
				or (coalesce(t.headernote,'') like :headernote))
						and t.pono is not null 
						and t.recordstatus in (".getUserRecordStatus('listpo').")
						and t.plantid = ".$plantid."
						and t.isjasa = 1
						and t.poheaderid in (select distinct z.poheaderid 
							from pojasa z 
							where z.qty > z.grqty)
						",
					array(
				':poheaderid' =>  $poheaderid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $supplier ,
				':pono' =>  $pono ,
				':headernote' =>  $headernote ,
				':podate' =>  $podate 
			))->queryScalar();
			} }
			 else if
			 (isset($_GET['invappo'])) {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('poheader t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
					((coalesce(t.poheaderid,'') like :poheaderid) 
				or (coalesce(a.fullname,'') like :fullname) 
				or (coalesce(t.pono,'') like :pono)  
				or (coalesce(b.plantcode,'') like :plantcode)  
				or (coalesce(t.headernote,'') like :headernote)) 
					and t.pono is not null 
					and t.recordstatus in (".getUserRecordStatus('listpo').")
				and t.plantid = ".$plantid." 
				and t.addressbookid = ".$addressbookid."
					",
				array(
				':poheaderid' =>  $poheaderid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $supplier ,
				':pono' =>  $pono ,
				':headernote' =>  $headernote ,
			))->queryScalar();
			}
			else
				if (isset($_GET['grretur'])) {
					$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
						->from('poheader t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addresscontact d', 'd.addresscontactid = t.addresscontactid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
						((coalesce(t.poheaderid,'') like :poheaderid) 
				or (coalesce(a.fullname,'') like :fullname) 
				or (coalesce(t.pono,'') like :pono)  
				or (coalesce(b.plantcode,'') like :plantcode)  
				or (coalesce(t.podate,'') like :podate) 
				or (coalesce(t.headernote,'') like :headernote)) 
						and t.pono is not null 
						and t.recordstatus in (".getUserRecordStatus('listpo').")
						and t.plantid = ".$plantid."
						and t.poheaderid in (select distinct z.poheaderid 
							from podetail z 
							where z.grqty > 0 and  
						z.slocid in (".getUserObjectValues('sloc')."))
						",
					array(
				':poheaderid' =>  $poheaderid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $supplier ,
				':pono' =>  $pono ,
				':headernote' =>  $headernote ,
				':podate' =>  $podate 
			))->queryScalar();
				}
			 else if
			 (isset($_GET['expappo'])) {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('poheader t')
				->join('grheader e', 'e.poheaderid = t.poheaderid')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
					((coalesce(t.poheaderid,'') like :poheaderid) 
				or (coalesce(a.fullname,'') like :fullname) 
				or (coalesce(t.pono,'') like :pono)  
				or (coalesce(b.plantcode,'') like :plantcode)  
				or (coalesce(t.podate,'') like :podate) 
				or (coalesce(t.headernote,'') like :headernote)) 
					and t.pono is not null 
					and t.recordstatus in (".getUserRecordStatus('listpo').")
				and t.plantid = '".$plantid."' 
					",
				array(
				':poheaderid' =>  $poheaderid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $supplier ,
				':pono' =>  $pono ,
				':headernote' =>  $headernote ,
				':podate' =>  $podate 
			))->queryScalar();
			} else if
			(isset($_GET['combo'])) {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('poheader t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addresscontact d', 'd.addresscontactid = t.addresscontactid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
					((coalesce(t.poheaderid,'') like :poheaderid) 
				or (coalesce(a.fullname,'') like :fullname) 
				or (coalesce(t.pono,'') like :pono)  
				or (coalesce(b.plantcode,'') like :plantcode)  
				or (coalesce(t.podate,'') like :podate) 
				or (coalesce(t.headernote,'') like :headernote)) 
					and t.pono is not null 
					and t.plantid = ".$plantid."
					and t.recordstatus in (".getUserRecordStatus('listpo').")
					",
				array(
				':poheaderid' =>  $poheaderid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $supplier ,
				':pono' =>  $pono ,
				':headernote' =>  $headernote ,
				':podate' =>  $podate 
			))->queryScalar();
			} else {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('poheader t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addresscontact d', 'd.addresscontactid = t.addresscontactid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
					((coalesce(t.poheaderid,'') like :poheaderid) 
				and (coalesce(b.plantcode,'') like :plantcode) 
				and (coalesce(a.fullname,'') like :supplier) 
				and (coalesce(t.pono,'') like :pono)  
				and (coalesce(t.podate,'') like :podate) 
				and (coalesce(t.headernote,'') like :headernote)) 				
				and t.recordstatus in (".getUserRecordStatus('listpo').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					(($productname != '%%')?"
				and t.poheaderid in (
				select distinct z.poheaderid 
				from podetail z 
				left join product za on za.productid = z.productid 
				where coalesce(za.productname,'') like '".$productname."'
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
				)":'')							
				, 
				array(
				':poheaderid' =>  $poheaderid ,
				':plantcode' =>  $plantcode ,
				':supplier' =>  $supplier ,
				':pono' =>  $pono ,
				':headernote' =>  $headernote ,
				':podate' =>  $podate 
			))->queryScalar();
			}
			$result['total'] = $cmd;
			if (isset($_GET['grpo'])) {
				if (($isjasa == '0') || ($isjasa == '')) {
					$cmd = Yii::app()->db->createCommand()->select('t.*,b.plantcode,t.headernote,b.companyid,c.companyname,a.fullname,c.billto,b.addresstoname,
				g.paycode,h.currencyname,a.creditlimit,d.addresscontactname,
				getamountbypo(t.poheaderid) as totprice')
				->from('poheader t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addresscontact d', 'd.addresscontactid = t.addresscontactid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
						((coalesce(t.poheaderid,'') like :poheaderid) 
				or (coalesce(a.fullname,'') like :fullname) 
				or (coalesce(t.pono,'') like :pono)  
				or (coalesce(b.plantcode,'') like :plantcode)  
				or (coalesce(t.podate,'') like :podate) 
				or (coalesce(t.headernote,'') like :headernote)) 
						and t.pono is not null 
						and t.recordstatus in (".getUserRecordStatus('listpo').")
						and t.plantid = ".$plantid."
						and t.isjasa = 0
						and t.poheaderid in (select distinct z.poheaderid 
							from podetail z 
							where z.qty > z.grqty)
						",
					array(
				':poheaderid' =>  $poheaderid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $supplier ,
				':pono' =>  $pono ,
				':headernote' =>  $headernote ,
				':podate' =>  $podate 
					))->queryAll();
				} else {
					$cmd = Yii::app()->db->createCommand()->select('t.*,b.plantcode,t.headernote,b.companyid,c.companyname,a.fullname,c.billto,b.addresstoname,
				g.paycode,h.currencyname,a.creditlimit,d.addresscontactname,
				getamountbypo(t.poheaderid) as totprice')
				->from('poheader t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addresscontact d', 'd.addresscontactid = t.addresscontactid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
						((coalesce(t.poheaderid,'') like :poheaderid) 
				or (coalesce(a.fullname,'') like :fullname) 
				or (coalesce(t.pono,'') like :pono)  
				or (coalesce(b.plantcode,'') like :plantcode)  
				or (coalesce(t.podate,'') like :podate) 
				or (coalesce(t.headernote,'') like :headernote)) 
						and t.pono is not null 
						and t.recordstatus in (".getUserRecordStatus('listpo').")
						and t.plantid = ".$plantid."
						and t.isjasa = 1
						and t.poheaderid in (select z.poheaderid 
							from pojasa z 
							where z.qty > z.grqty)
						",
					array(
				':poheaderid' =>  $poheaderid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $supplier ,
				':pono' =>  $pono ,
				':headernote' =>  $headernote ,
				':podate' =>  $podate 
					))->queryAll();
				}
			} else if
			(isset($_GET['invappo'])) {
				$cmd = Yii::app()->db->createCommand()->selectdistinct('t.*,b.plantcode,t.headernote,b.companyid,c.companyname,a.fullname,c.billto,b.addresstoname,
				g.paycode,h.currencyname,a.creditlimit,(null) as addresscontactname,
				getamountbypo(t.poheaderid) as totprice')
				->from('poheader t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
					((coalesce(t.poheaderid,'') like :poheaderid) 
				or (coalesce(a.fullname,'') like :fullname) 
				or (coalesce(t.pono,'') like :pono)  
				or (coalesce(b.plantcode,'') like :plantcode)  
				or (coalesce(t.headernote,'') like :headernote))
					and t.pono is not null 
				and t.recordstatus in (".getUserRecordStatus('listpo').")
				and t.plantid = ".$plantid." 
				and t.addressbookid = ".$addressbookid."
				",
				array(
				':poheaderid' =>  $poheaderid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $supplier ,
				':pono' =>  $pono ,
				':headernote' =>  $headernote ,
				))->queryAll();
			} 
			else
				if (isset($_GET['grretur'])) {
					$cmd = Yii::app()->db->createCommand()->select('t.*,b.plantcode,t.headernote,b.companyid,c.companyname,a.fullname,c.billto,b.addresstoname,
				g.paycode,h.currencyname,a.creditlimit,d.addresscontactname,
				getamountbypo(t.poheaderid) as totprice')
				->from('poheader t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addresscontact d', 'd.addresscontactid = t.addresscontactid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
						((coalesce(poheaderid,'') like :poheaderid) 
				or (coalesce(plantcode,'') like :plantcode) 
				or (coalesce(fullname,'') like :fullname) 
				or (coalesce(pono,'') like :pono)  
				or (coalesce(podate,'') like :podate) 
				or (coalesce(headernote,'') like :headernote))
						and t.pono is not null 
						and t.recordstatus in (".getUserRecordStatus('listpo').")
						and t.plantid = ".$plantid."
						and t.poheaderid in (select z.poheaderid 
							from podetail z 
							where z.grqty > 0 and  
						z.slocid in (".getUserObjectValues('sloc')."))
						",
					array(
				':poheaderid' =>  $poheaderid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $supplier ,
				':pono' =>  $pono ,
				':headernote' =>  $headernote ,
				':podate' =>  $podate 
					))->queryAll();
				}
			else if
			(isset($_GET['expappo'])) {
				$cmd = Yii::app()->db->createCommand()->selectdistinct('t.*,b.plantcode,t.headernote,b.companyid,c.companyname,a.fullname,c.billto,b.addresstoname,
				g.paycode,h.currencyname,a.creditlimit,(null) as addresscontactname,
				getamountbypo(t.poheaderid) as totprice')
				->from('poheader t')
				->join('grheader e', 'e.poheaderid = t.poheaderid')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
					((coalesce(t.poheaderid,'') like :poheaderid) 
				or (coalesce(a.fullname,'') like :fullname) 
				or (coalesce(t.pono,'') like :pono)  
				or (coalesce(b.plantcode,'') like :plantcode)  
				or (coalesce(t.podate,'') like :podate) 
				or (coalesce(t.headernote,'') like :headernote))
					and t.pono is not null 
				and t.recordstatus in (".getUserRecordStatus('listpo').")
				and t.plantid = '".$plantid."' 
				",
				array(
				':poheaderid' =>  $poheaderid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $supplier ,
				':pono' =>  $pono ,
				':headernote' =>  $headernote ,
				':podate' =>  $podate 
				))->queryAll();
			} else if
			(isset($_GET['combo'])) {
				$cmd = Yii::app()->db->createCommand()->select('t.*,b.plantcode,t.headernote,b.companyid,c.companyname,a.fullname,c.billto,b.addresstoname,
				g.paycode,h.currencyname,a.creditlimit,d.addresscontactname,
				getamountbypo(t.poheaderid) as totprice')
				->from('poheader t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addresscontact d', 'd.addresscontactid = t.addresscontactid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
					((coalesce(poheaderid,'') like :poheaderid) 
				or (coalesce(plantcode,'') like :plantcode) 
				or (coalesce(fullname,'') like :fullname) 
				or (coalesce(pono,'') like :pono)  
				or (coalesce(podate,'') like :podate) 
				or (coalesce(headernote,'') like :headernote))
					and t.pono is not null 
					and t.plantid = ".$plantid."
					and t.recordstatus in (".getUserRecordStatus('listpo').")
				",
				array(
				':poheaderid' =>  $poheaderid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $supplier ,
				':pono' =>  $pono ,
				':headernote' =>  $headernote ,
				':podate' =>  $podate 
				))->queryAll();
			} else {
				$cmd = Yii::app()->db->createCommand()->select('t.*,b.plantcode,t.headernote,b.companyid,c.companyname,a.fullname,c.billto,b.addresstoname,
				g.paycode,h.currencyname,a.creditlimit,d.addresscontactname,
				getamountbypo(t.poheaderid) as totprice')
				->from('poheader t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addresscontact d', 'd.addresscontactid = t.addresscontactid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
					((coalesce(t.poheaderid,'') like '".$poheaderid."') 
				and (coalesce(b.plantcode,'') like '".$plantcode."') 
				and (coalesce(a.fullname,'') like '".$supplier."') 
				and (coalesce(t.pono,'') like '".$pono."')  
				and (coalesce(t.podate,'') like '".$podate."') 
				and (coalesce(t.headernote,'') like '".$headernote."')) 	
				and t.recordstatus in (".getUserRecordStatus('listpo').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					(($productname != '%%')?"
				and t.poheaderid in (
				select distinct z.poheaderid 
				from podetail z 
				left join product za on za.productid = z.productid 
				where coalesce(za.productname,'') like '".$productname."'
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
				)":'')							
				, 
				array(
					))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
			}
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
		} else {
		$poheaderid = GetSearchText(array('POST','Q','GET'),'poheaderid',0,'int');
			$cmd = Yii::app()->db->createCommand("
				select a.*,b.plantcode,c.companycode,d.fullname 
				from poheader a 
				join plant b on b.plantid = a.plantid 
				join company c on c.companyid = b.companyid 
				join addressbook d on d.addressbookid = a.addressbookid
				where a.poheaderid = ".$poheaderid)->queryRow();
			$result = $cmd;
		}
    return CJSON::encode($result);
	}
	public function actionrawschedule() {
    $items = array();
    $cmd   = Yii::app()->db->createCommand()
			->select('a.productname,c.pono,t.qty,t.qty2,t.grqty,(t.qty-t.grqty) as qtyout,d.fullname as supplier,e.uomcode,t.arrivedate')
			->from('podetail t')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('poheader c', 'c.poheaderid = t.poheaderid')
			->leftjoin('addressbook d', 'd.addressbookid = c.addressbookid')
			->leftjoin('unitofmeasure e', 'e.unitofmeasureid = t.uomid')
			->where("arrivedate >= '" . $_GET['start'] . "' 
				and arrivedate <= '" . $_GET['end'] . "'  
				and c.pono is not null 
				and t.slocid in (".GetUserObjectValues('sloc').") 
				")->queryAll();
    
    foreach ($cmd as $data) {
      $items[] = array(
        'title' => 'Supplier: '.$data['supplier']."\n No PO:".$data['pono'].
					"\n Artikel: ".$data['productname'].
					"\n Qty PO: ". Yii::app()->format->formatNumber($data['qty']).''.$data['uomcode'].
					"\n Qty LPB: ".Yii::app()->format->formatNumber($data['grqty']).
					"\n Qty Out: ".Yii::app()->format->formatNumber($data['qtyout']),
        'start' => $data['arrivedate'],
        'end' => $data['arrivedate'],
        'constraint' => 'businessHours'
      );
    }
    echo CJSON::encode($items);
  }
	public function actionSearchdetail() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page = GetSearchText(array('POST'),'page',1,'int');
		$rows = GetSearchText(array('POST'),'rows',10,'int');
		$sort = GetSearchText(array('POST'),'sort','podetailid','int');
		$order = GetSearchText(array('POST'),'order','asc','int');
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('podetail t')
			->leftjoin('poheader g', 'g.poheaderid = t.poheaderid')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('requestedby b', 'b.requestedbyid = t.requestedbyid')
			->leftjoin('prheader c', 'c.prheaderid = t.prheaderid')
			->leftjoin('sloc d', 'd.slocid = t.slocid')
			->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')
			->where('t.poheaderid = :poheaderid',
			array(
				':poheaderid' => $id
			))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
					->select('t.*,a.productcode,a.productname,d.sloccode,c.prno,b.requestedbycode,t.qty-t.grqty as qtysisa,
						getamountdetailbypo(t.poheaderid,t.podetailid) as totprice,e.materialtypecode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
						GetStdqty2(a.productid) as stdqty2,
						getStock(a.productid,t.uomid,t.slocid) as qtystock
						')
					->from('podetail t')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('requestedby b', 'b.requestedbyid = t.requestedbyid')
					->leftjoin('prheader c', 'c.prheaderid = t.prheaderid')
					->leftjoin('sloc d', 'd.slocid = t.slocid')
			->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')
					->where('t.poheaderid = :poheaderid', array(
		':poheaderid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
			if ($data['qtystock'] >= $data['qty']) {
				$stockcount = 0;
			} else {
				$stockcount = 1;
			}
      $row[] = array(
        'podetailid' => $data['podetailid'],
        'poheaderid' => $data['poheaderid'],
				'prheaderid' => $data['prheaderid'],
				'prrawid' => $data['prrawid'],
				'materialtypecode' => $data['materialtypecode'],
				'prno' => $data['prno'],
        'productid' => $data['productid'],
				'productcode' => $data['productcode'],
				'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
				'qty2' => Yii::app()->format->formatNumber($data['qty2']),
        'grqty' => Yii::app()->format->formatNumber($data['grqty']),
				'qtysisa' => Yii::app()->format->formatNumber($data['qtysisa']),
				'stdqty2' => Yii::app()->format->formatNumber($data['stdqty2']),
				'qtystock' => Yii::app()->format->formatNumber($data['qtystock']),
				'stockcount' => $stockcount,
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
				'uom2id' => $data['uom2id'],
        'uom2code' => $data['uom2code'],
				'requestedbyid' => $data['requestedbyid'],
        'requestedbycode' => $data['requestedbycode'],
				'arrivedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['arrivedate'])),
				'price' => Yii::app()->format->formatCurrency($data['price']),
				'totprice' => Yii::app()->format->formatCurrency($data['totprice']),
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
				'toleransiup' => Yii::app()->format->formatNumber($data['toleransiup']),
				'toleransidown' => Yii::app()->format->formatNumber($data['toleransidown']),
				'itemnote' => $data['itemnote']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		$sql = "select getamountbypo(poheaderid) as total
			from poheader t where poheaderid = ".$id;
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
  public function actionSearchPojasa() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','pojasaid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('pojasa t')
			->leftjoin('poheader g', 'g.poheaderid = t.poheaderid')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('mesin c', 'c.mesinid = t.mesinid')
			->leftjoin('sloc d', 'd.slocid = t.sloctoid')
			->leftjoin('prheader e', 'e.prheaderid = t.prheaderid')
      ->leftjoin('materialtype f', 'f.materialtypeid = a.materialtypeid')			
      ->where('t.poheaderid = :poheaderid',
			array(
				':poheaderid' => $id
			))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
      ->select('t.*,e.prno,a.productcode,a.productname,c.namamesin,d.sloccode,t.description,f.materialtypecode,
        getamountdetailbypo(t.poheaderid,t.pojasaid) as totprice,
        (select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode')
      ->from('pojasa t')
      ->leftjoin('poheader g', 'g.poheaderid = t.poheaderid')
      ->leftjoin('product a', 'a.productid = t.productid')
      ->leftjoin('mesin c', 'c.mesinid = t.mesinid')
      ->leftjoin('sloc d', 'd.slocid = t.sloctoid')
			->leftjoin('prheader e', 'e.prheaderid = t.prheaderid')
			->leftjoin('materialtype f', 'f.materialtypeid = a.materialtypeid')
      ->where('t.poheaderid = :poheaderid', array(
		':poheaderid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'pojasaid' => $data['pojasaid'],
        'poheaderid' => $data['poheaderid'],
        'prheaderid' => $data['prheaderid'],
        'materialtypecode' => $data['materialtypecode'],
        'prno' => $data['prno'],
        'prjasaid' => $data['prjasaid'],
        'productid' => $data['productid'],
        'productcode' => $data['productcode'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
		    'reqdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['reqdate'])),
        'mesinid' => $data['mesinid'],
        'namamesin' => $data['namamesin'],
        'price' => Yii::app()->format->formatNumber($data['price']),
        'totprice' => Yii::app()->format->formatNumber($data['totprice']),
        'sloctoid' => $data['sloctoid'],
        'sloccode' => $data['sloccode'],
		    'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function actionSearchPoResult() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','poresultid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
      ->from('poresult t')
      ->leftjoin('poheader g', 'g.poheaderid = t.poheaderid')
      ->leftjoin('product a', 'a.productid = t.productid')
      ->leftjoin('prheader b', 'b.prheaderid = t.prheaderid')
      ->where('t.poheaderid = :poheaderid',
        array(':poheaderid' => $id))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
      ->select('t.*,a.productcode,a.productname,b.prno,
        (select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,t.description,
        (select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
        (select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code,
        (select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom4id) as uom4code')
      ->from('poresult t')
      ->leftjoin('poheader g', 'g.poheaderid = t.poheaderid')
      ->leftjoin('product a', 'a.productid = t.productid')
      ->leftjoin('prheader b', 'b.prheaderid = t.prheaderid')
      ->where('t.poheaderid = :poheaderid', array(
		    ':poheaderid' => $id
		  ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
	  foreach ($cmd as $data) {
      $row[] = array(
        'poresultid' => $data['poresultid'],
        'poheaderid' => $data['poheaderid'],
        'prresultid' => $data['prresultid'],
        'prheaderid' => $data['prheaderid'],
        'prno' => $data['prno'],
        'productid' => $data['productid'],
        'productcode' => $data['productcode'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'qty2' => Yii::app()->format->formatNumber($data['qty2']),
        'qty3' => Yii::app()->format->formatNumber($data['qty3']),
        'qty4' => Yii::app()->format->formatNumber($data['qty4']),
        'uomid' => $data['uomid'],
        'uom2id' => $data['uom2id'],
        'uom3id' => $data['uom3id'],
        'uom4id' => $data['uom4id'],
        'uomcode' => $data['uomcode'],
        'uom2code' => $data['uom2code'],
        'uom3code' => $data['uom3code'],
        'uom4code' => $data['uom4code'],
        'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function actionSearchTaxpo() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','taxpoid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
      ->from('taxpo t')
      ->leftjoin('tax a', 'a.taxid = t.taxid')
      ->where('t.poheaderid = :poheaderid',
        array(':poheaderid' => $id))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
      ->select('t.*,a.taxcode,a.taxvalue')
      ->from('taxpo t')
      ->leftjoin('tax a', 'a.taxid = t.taxid')
      ->where('t.poheaderid = :poheaderid', array(
		    ':poheaderid' => $id
		  ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'taxpoid' => $data['taxpoid'],
        'poheaderid' => $data['poheaderid'],
        'taxid' => $data['taxid'],
				'taxcode' => $data['taxcode'],
				'taxvalue' => $data['taxvalue']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function actionReject() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call RejectPo(:vid,:vdatauser)';
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
        $sql     = 'call ApprovePO(:vid,:vdatauser)';
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
		$sql = 'call Modifpoheader(:vid,:vpodate,:vplantid,:vaddressbookid,:vaddresscontactid,:vpaymentmethodid,
								:vcurrencyid,:vcurrencyrate,:visjasa,:visimport,:vheadernote,:vdatauser)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vpodate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vaddresscontactid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vpaymentmethodid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyrate', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':visjasa', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':visimport', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();					
	}
	public function actionSave() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['poheader-poheaderid'])?$_POST['poheader-poheaderid']:''),
			date(Yii::app()->params['datetodb'], strtotime($_POST['poheader-podate'])),
			$_POST['poheader-plantid'],$_POST['poheader-addressbookid'],$_POST['poheader-addresscontactid'],$_POST['poheader-paymentmethodid'],
			$_POST['poheader-currencyid'],$_POST['poheader-currencyrate'],(isset($_POST['poheader-isjasa']) ? 1 : 0),
			(isset($_POST['poheader-isimport']) ? 1 : 0),$_POST['poheader-headernote']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
	private function ModifyDetail($connection,$arraydata) {
		if ($arraydata[0] == '') {
			$sql     = 'call InsertPodetail(:vpoheaderid,:vprrawid,:vproductid,:vqty,:vuomid,:vqty2,:vuom2id,
					:vprice,:varrivedate,:vtoleransiup,:vtoleransidown,:vslocid,:vrequestedbyid,:vitemnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call UpdatePodetail(:vid,:vpoheaderid,:vprrawid,:vproductid,:vqty,:vuomid,:vqty2,:vuom2id,
					:vprice,:varrivedate,:vtoleransiup,:vtoleransidown,:vslocid,:vrequestedbyid,:vitemnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vpoheaderid', $arraydata[1], PDO::PARAM_STR);
	  $command->bindvalue(':vprrawid', ((isnulloremptystring($arraydata[2])!=1)?$arraydata[2]:null), PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[3], PDO::PARAM_STR);
	  $command->bindvalue(':vqty', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[5], PDO::PARAM_STR);
	  $command->bindvalue(':vqty2', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vuom2id', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vprice', $arraydata[8], PDO::PARAM_STR);
	  $command->bindvalue(':varrivedate', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vtoleransiup', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vtoleransidown', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vslocid', $arraydata[12], PDO::PARAM_STR);
		$command->bindvalue(':vrequestedbyid', ((isnulloremptystring($arraydata[13])!=1)?$arraydata[13]:null), PDO::PARAM_STR);
		$command->bindvalue(':vitemnote', $arraydata[14], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavedetail() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDetail($connection,array(
				(isset($_POST['podetailid'])?$_POST['podetailid']:''),
				$_POST['poheaderid'],
				$_POST['prrawid'],
				$_POST['productid'],
				$_POST['qty'],
				$_POST['uomid'],
				$_POST['qty2'],
				$_POST['uom2id'],
				$_POST['price'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['arrivedate'])),
				$_POST['toleransiup'],
				$_POST['toleransidown'],
				$_POST['slocid'],
				$_POST['requestedbyid'],
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
  public function actionSavepojasa() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call InsertPojasa(:vpoheaderid,:vprjasaid,:vproductid,:vuomid,:vqty,:vreqdate,
					:vsloctoid,:vmesinid,:vdescription,:vprice,:vtolerance,:vdatauser)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call UpdatePOjasa(:vid,:vpoheaderid,:vprjasaid,:vproductid,:vuomid,:vqty,:vreqdate,
					:vsloctoid,:vmesinid,:vdescription,:vprice,:vtolerance,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['pojasaid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['pojasaid']);
      }
      $command->bindvalue(':vpoheaderid', $_POST['poheaderid'], PDO::PARAM_STR);
      $command->bindvalue(':vprjasaid', $_POST['prjasaid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
			$command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
      $command->bindvalue(':vreqdate', date(Yii::app()->params['datetodb'], strtotime($_POST['reqdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vsloctoid', $_POST['sloctoid'], PDO::PARAM_STR);
			$command->bindvalue(':vmesinid', $_POST['mesinid'], PDO::PARAM_STR);
			$command->bindvalue(':vprice', $_POST['price'], PDO::PARAM_STR);
			$command->bindvalue(':vtolerance', $_POST['tolerance'], PDO::PARAM_STR);
			$command->bindvalue(':vdescription', $_POST['description'], PDO::PARAM_STR);
      $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
  public function actionSavePoresult() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call InsertPoresult(:vpoheaderid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,:vqty4,:vdescription,:vdatauser)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call UpdatePoresult(:vid,:vpoheaderid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,:vqty4,:vdescription,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['poresultid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['poresultid']);
      }
      $command->bindvalue(':vpoheaderid', $_POST['poheaderid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
      $command->bindvalue(':vuom2id', $_POST['uom2id'], PDO::PARAM_STR);
			$command->bindvalue(':vuom3id', $_POST['uom3id'], PDO::PARAM_STR);
			$command->bindvalue(':vuom4id', $_POST['uom4id'], PDO::PARAM_STR);
			$command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
			$command->bindvalue(':vqty2', $_POST['qty2'], PDO::PARAM_STR);
			$command->bindvalue(':vqty3', $_POST['qty3'], PDO::PARAM_STR);
			$command->bindvalue(':vqty4', $_POST['qty4'], PDO::PARAM_STR);
      $command->bindvalue(':vdescription', $_POST['description'], PDO::PARAM_STR);
      $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
	private function ModifyTaxPO($connection,$arraydata) {
		if ($arraydata[0] == '') {
			$sql     = 'call InsertTaxpo(:vpoheaderid,:vtaxid,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call UpdateTaxpo(:vid,:vpoheaderid,:vtaxid,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vpoheaderid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vtaxid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavetaxpo() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      $this->ModifyTaxPO($connection,array(
				(isset($_POST['taxpoid'])?$_POST['taxpoid']:''),
				$_POST['poheaderid'],
				$_POST['taxid']
			));
      $transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-poheader"]["name"]);
		if (move_uploaded_file($_FILES["file-poheader"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$oldid = '';$pid = '';$productplanid= '';
			$connection  = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				for ($row = 3; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue(); //A
					if ((int)$id > 0) {
						if ($oldid != $id) {
							$oldid = $id;
							$oldppid = '';
							$plantcode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue(); //B
							$plantid = Yii::app()->db->createCommand("select plantid from plant where plantcode = '".$plantcode."'")->queryScalar();
							$pono = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue(); //C
							$docdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(3, $row)->getValue())); //D
							$supplier = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue(); //E
							$supplierid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '".$supplier."'")->queryScalar();
							$contact = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue(); //F
							$addresscontactid = Yii::app()->db->createCommand("select addresscontactid from addresscontact where addresscontactname = '".$contact."'")->queryScalar();
							$paycode = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue(); //G
							$payid = Yii::app()->db->createCommand("select paymentmethodid from paymentmethod where paycode = '".$paycode."'")->queryScalar();
							$isjasa = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue(); //H
							$isimport = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue(); //I
							$symbol = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue(); //J
							$currencyid = Yii::app()->db->createCommand("select currencyid from currency where symbol = '".$symbol."'")->queryScalar();
							$rate = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue(); //K
							$description = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue(); //L
							$sql = "
								insert into poheader(plantid,pono,podate,addressbookid,addresscontactid,paymentmethodid,isimport,currencyid,currencyrate,headernote,isjasa,recordstatus)
								values (:plantid,:pono,:podate,:addressbookid,:addresscontactid,:paymentmethodid,:isimport,:currencyid,:currencyrate,:headernote,:isjasa,4);
							";
							$command = $connection->createCommand($sql);
							$command->bindvalue(':plantid',$plantid,PDO::PARAM_STR);
							$command->bindvalue(':pono',$pono,PDO::PARAM_STR);
							$command->bindvalue(':podate',$docdate,PDO::PARAM_STR);
							$command->bindvalue(':addressbookid',$supplierid,PDO::PARAM_STR);
							$command->bindvalue(':addresscontactid',null,PDO::PARAM_STR);
							$command->bindvalue(':paymentmethodid',$payid,PDO::PARAM_STR);
							$command->bindvalue(':isimport',$isimport,PDO::PARAM_STR);
							$command->bindvalue(':currencyid',$currencyid,PDO::PARAM_STR);
							$command->bindvalue(':currencyrate',$rate,PDO::PARAM_STR);
							$command->bindvalue(':headernote',$description,PDO::PARAM_STR);
							$command->bindvalue(':isjasa',$isjasa,PDO::PARAM_STR);
							$command->execute();
							/*$this->ModifyData($connection,array(
								-1,
								$docdate,
								$plantid,
								$supplierid,
								$addresscontactid,
								$payid,
								$currencyid,
								$rate,
								$isjasa,
								$isimport,
								$description));*/
							$sql = "
								select poheaderid 
								from poheader a
								where a.plantid = ".$plantid." 
								and a.podate = '".$docdate."' 
								and a.pono = '".$pono."' 
								and a.isjasa = ".$isjasa." 
								and a.isimport = ".$isimport." 
								and a.addressbookid = ".$supplierid." 
								limit 1
							";
							$pid = Yii::app()->db->createCommand($sql)->queryScalar();
							//throw new Exception($sql);
						} 
						$taxcode = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue(); //M
						$taxid = Yii::app()->db->createCommand("select taxid from tax where taxcode = '".$taxcode."'")->queryScalar();
						if ($taxid > 0) {
							$sql = "
								select taxpoid 
								from taxpo 
								where poheaderid = ".$pid." 
								and taxid =".$taxid." 
							";
							$taxpoid = Yii::app()->db->createCommand($sql)->queryScalar();
							if ($taxpoid > 0) {
							} else {
								$this->ModifyTaxPO($connection,array(
									'',
									$pid,
									$taxid
								));
							}
						}
						$prno = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue(); //N
						$prheaderid = Yii::app()->db->createCommand("select prheaderid from prheader where prno = '".$prno."'")->queryScalar();
						$productname = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue(); //O
						$sql = "select productid from product where productname = :productname";
						$command=$connection->createCommand($sql);
						$command->bindvalue(':productname',$productname,PDO::PARAM_STR);
						$productid = $command->queryScalar();
						if ($productid > 0) {
							$qty = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue(); //P
							$uomcode = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue(); //Q
							$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$qty2 = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue(); //R
							$uomcode = $objWorksheet->getCellByColumnAndRow(19, $row)->getValue(); //S
							$uom2id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$price = $objWorksheet->getCellByColumnAndRow(20, $row)->getValue(); //T
							$reqdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(21, $row)->getValue())); //U
							$toleransiup = $objWorksheet->getCellByColumnAndRow(22, $row)->getValue(); //V
							$toleransidown = $objWorksheet->getCellByColumnAndRow(23, $row)->getValue(); //W
							$slocto = $objWorksheet->getCellByColumnAndRow(24, $row)->getValue(); //X
							$sloctoid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$slocto."'")->queryScalar();
							$kodemesin = $objWorksheet->getCellByColumnAndRow(25, $row)->getValue(); //Y
							$mesinid = Yii::app()->db->createCommand("select mesinid from mesin where kodemesin = '".$kodemesin."'")->queryScalar();
							$reqcode = $objWorksheet->getCellByColumnAndRow(26, $row)->getValue(); //Z
							$requestedbyid = Yii::app()->db->createCommand("select requestedbyid from requestedby where requestedbycode = '".$reqcode."'")->queryScalar();
							$itemnote = $objWorksheet->getCellByColumnAndRow(27, $row)->getValue(); //AA
							$prrawid = '';// $connection->createCommand($sql)->queryScalar();
							$sql = "insert into podetail (poheaderid,prrawid,prheaderid,productid,qty,uomid,qty2,uom2id,price,arrivedate,toleransiup,toleransidown,slocid,mesinid,requestedbyid,itemnote)
								values (:poheaderid,:prrawid,:prheaderid,:productid,:qty,:uomid,:qty2,:uom2id,:price,:arrivedate,:toleransiup,:toleransidown,:slocid,:mesinid,:requestedbyid,:itemnote)";
							$command = $connection->createCommand($sql);
							$command->bindvalue(':poheaderid',$pid,PDO::PARAM_STR);
							$command->bindvalue(':prrawid',null,PDO::PARAM_STR);
							$command->bindvalue(':prheaderid',null,PDO::PARAM_STR);
							$command->bindvalue(':productid',$productid,PDO::PARAM_STR);
							$command->bindvalue(':qty',$qty,PDO::PARAM_STR);
							$command->bindvalue(':uomid',$uomid,PDO::PARAM_STR);
							$command->bindvalue(':qty2',$qty2,PDO::PARAM_STR);
							$command->bindvalue(':uom2id',((IsNullOrEmptyString($uom2id) != 1)?$uom2id:null),PDO::PARAM_STR);
							$command->bindvalue(':price',$price,PDO::PARAM_STR);
							$command->bindvalue(':arrivedate',$reqdate,PDO::PARAM_STR);
							$command->bindvalue(':toleransiup',$toleransiup,PDO::PARAM_STR);
							$command->bindvalue(':toleransidown',$toleransidown,PDO::PARAM_STR);
							$command->bindvalue(':slocid',$sloctoid,PDO::PARAM_STR);
							$command->bindvalue(':mesinid',((IsNullOrEmptyString($mesinid) != 1)?$mesinid:null),PDO::PARAM_STR);
							$command->bindvalue(':requestedbyid',((IsNullOrEmptyString($requestedbyid) != 1)?$requestedbyid:null),PDO::PARAM_STR);
							$command->bindvalue(':itemnote',$itemnote,PDO::PARAM_STR);
							$command->execute();
						} else {
							throw new Exception('Line: '.$row.' ==> Unknown Product '.$productname);
						}
						$prno = $objWorksheet->getCellByColumnAndRow(28, $row)->getValue(); //AA
						$prheaderid = Yii::app()->db->createCommand("select prheaderid from prheader where prno = '".$prno."'")->queryScalar();
						$productname = $objWorksheet->getCellByColumnAndRow(29, $row)->getValue(); //AB
						$sql = "select productid from product where productname = :productname";
						$command=$connection->createCommand($sql);
						$command->bindvalue(':productname',$productname,PDO::PARAM_STR);
						$productid = $command->queryScalar();
						if ($productid > 0) {
							$qty = $objWorksheet->getCellByColumnAndRow(30, $row)->getValue(); //AC
							$uomcode = $objWorksheet->getCellByColumnAndRow(31, $row)->getValue(); //AD
							$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$price = $objWorksheet->getCellByColumnAndRow(32, $row)->getValue(); //AE
							$reqdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(33, $row)->getValue())); //AF
							$slocto = $objWorksheet->getCellByColumnAndRow(34, $row)->getValue(); //AG
							$sloctoid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$slocto."'")->queryScalar();
							$kodemesin = $objWorksheet->getCellByColumnAndRow(35, $row)->getValue(); //AH
							$mesinid = Yii::app()->db->createCommand("select mesinid from mesin where kodemesin = '".$kodemesin."'")->queryScalar();
							$itemnote = $objWorksheet->getCellByColumnAndRow(36, $row)->getValue(); //AI
							$sql = "
								select prjasaid 
								from prjasa a 
								join prheader b on b.prheaderid = a.prheaderid 
								where a.productid = ".$productid." 
								and a.prheaderid = ".$prheaderid." 
								and a.sloctoid = ".$sloctoid." 
								and coalesce(a.description,'') = '".$itemnote."' 
								limit 1
							";
							$prjasaid = $connection->createCommand($sql)->queryScalar();
							$sql = "insert into pojasa (poheaderid,prjasaid,prheaderid,productid,qty,uomid,price,reqdate,sloctoid,mesinid,itemnote)
								values (:poheaderid,:prjasaid,:prheaderid,:productid,:qty,:uomid,:price,:reqdate,:sloctoid,:mesinid,:itemnote)";
							$command = $connection->createCommand($sql);
							$command->bindvalue(':poheaderid',$pid,PDO::PARAM_STR);
							$command->bindvalue(':prjasaid',$prjasaid,PDO::PARAM_STR);
							$command->bindvalue(':prheaderid',$prheaderid,PDO::PARAM_STR);
							$command->bindvalue(':productid',$productid,PDO::PARAM_STR);
							$command->bindvalue(':qty',$qty,PDO::PARAM_STR);
							$command->bindvalue(':uomid',$uomid,PDO::PARAM_STR);
							$command->bindvalue(':price',$price,PDO::PARAM_STR);
							$command->bindvalue(':reqdate',$reqdate,PDO::PARAM_STR);
							$command->bindvalue(':sloctoid',$sloctoid,PDO::PARAM_STR);
							$command->bindvalue(':mesinid',$mesinid,PDO::PARAM_STR);
							$command->bindvalue(':itemnote',$itemnote,PDO::PARAM_STR);
							$command->execute();
						}
						$prno = $objWorksheet->getCellByColumnAndRow(37, $row)->getValue(); //AJ
						$prheaderid = Yii::app()->db->createCommand("select prheaderid from prheader where prno = '".$prno."'")->queryScalar();
						$productname = $objWorksheet->getCellByColumnAndRow(38, $row)->getValue(); //AK
						$sql = "select productid from product where productname = :productname";
						$command=$connection->createCommand($sql);
						$command->bindvalue(':productname',$productname,PDO::PARAM_STR);
						$productid = $command->queryScalar();
						if ($productid > 0) {
							$qty = $objWorksheet->getCellByColumnAndRow(39, $row)->getValue(); //AL
							$uomcode = $objWorksheet->getCellByColumnAndRow(40, $row)->getValue(); //AM
							$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$qty2 = $objWorksheet->getCellByColumnAndRow(41, $row)->getValue(); //AN
							$uomcode = $objWorksheet->getCellByColumnAndRow(42, $row)->getValue(); //AO
							$uom2id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$itemnote = $objWorksheet->getCellByColumnAndRow(43, $row)->getValue(); //AP
							$sql = "
								select prresultid 
								from prresult a 
								join prheader b on b.prheaderid = a.prheaderid 
								where a.productid = ".$productid." 
								and a.prheaderid = ".$prheaderid." 
								and coalesce(a.description,'') = '".$itemnote."' 
								limit 1
							";
							$prresultid = $connection->createCommand($sql)->queryScalar();
							$sql = "insert into poresult (poheaderid,prresultid,prheaderid,productid,qty,uomid,qty2,uom2id,itemnote)
								values (:poheaderid,:prrawid,:prheaderid,:productid,:qty,:uomid,:qty2,:uom2id,:itemnote)";
							$command = $connection->createCommand($sql);
							$command->bindvalue(':poheaderid',$pid,PDO::PARAM_STR);
							$command->bindvalue(':prresultid',$prresultid,PDO::PARAM_STR);
							$command->bindvalue(':prheaderid',$prheaderid,PDO::PARAM_STR);
							$command->bindvalue(':productid',$productid,PDO::PARAM_STR);
							$command->bindvalue(':qty',$qty,PDO::PARAM_STR);
							$command->bindvalue(':uomid',$uomid,PDO::PARAM_STR);
							$command->bindvalue(':qty2',$qty2,PDO::PARAM_STR);
							$command->bindvalue(':uom2id',$uom2id,PDO::PARAM_STR);
							$command->bindvalue(':price',$price,PDO::PARAM_STR);
							$command->bindvalue(':arrivedate',$reqdate,PDO::PARAM_STR);
							$command->bindvalue(':toleransiup',$toleransiup,PDO::PARAM_STR);
							$command->bindvalue(':toleransidown',$toleransidown,PDO::PARAM_STR);
							$command->bindvalue(':slocid',$sloctoid,PDO::PARAM_STR);
							$command->bindvalue(':mesinid',$mesinid,PDO::PARAM_STR);
							$command->bindvalue(':requestedbyid',$requestedbyid,PDO::PARAM_STR);
							$command->bindvalue(':itemnote',$itemnote,PDO::PARAM_STR);
							$command->execute();
						}
					}
				}
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,'Line: '.$row.' ==> '.implode(" ",$e->errorInfo));
			}
    }
	}
  public function actionPurge() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgepoheader(:vid,:vdatauser)';
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
      GetMessage(true, getcatalog('chooseone'));
    }
  }
	public function actionPurgealldetail() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgepoalldetail(:vid,:vdatauser)';
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
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgepodetail(:vid,:vdatauser)';
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
  public function actionPurgepojasa() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgepojasa(:vid,:vdatauser)';
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
  public function actionPurgeresult() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeporesult(:vid,:vdatauser)';
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
  public function actionPurgeTaxpo() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgetaxpo(:vid,:vdatauser)';
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
  public function actionGeneratesupplier() {
		$sql = "select a.fullname
			from addressbook a 
			left join poheader b on b.addressbookid = a.addressbookid
			where b.poheaderid = ".$_POST['id']." 
			limit 1";
		$address = Yii::app()->db->createCommand($sql)->queryRow();
		echo CJSON::encode(array(
			'fullname' => $address['fullname'],
		));
	 Yii::app()->end();
  }
  public function actionDownPDF() {
    parent::actionDownload();
		
    $sql = "select a.plantid,d.companyid,(select companyname from company zz where zz.companyid = d.companyid) as companyname,
		b.fullname, a.pono, a.podate as docdate,b.addressbookid,a.poheaderid,c.paymentname,a.headernote,a.printke,a.poheaderid,
			ifnull(a.printke,0) as printke,a.recordstatus,d.addresstoname as shipto,e.billto,a.isjasa,a.addresscontactid 
      from poheader a
      left join addressbook b on b.addressbookid = a.addressbookid
      left join paymentmethod c on c.paymentmethodid = a.paymentmethodid
			join plant d on d.plantid = a.plantid
			join company e on e.companyid = d.companyid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.poheaderid in (" . $_GET['id'] . ")";
    }
    $price = getUserObjectValues($menuobject='currency');
		
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
		 
    $this->pdf->title = GetCatalog('');
    $this->pdf->AddPage('P', 'Letter');
    $this->pdf->isprint = true;
		
    foreach ($dataReader as $row) {
      $sql1               = "update poheader set printke = ifnull(printke,0) + 1
				where poheaderid = " . $row['poheaderid'];
      $command1           = $this->connection->createCommand($sql1);
      $this->pdf->printke = $row['printke'];
      $command1->execute();
			$isjasa = $row['isjasa'];
      $sql1        = "select b.addresstypename, a.addressname, c.cityname, a.phoneno, a.faxno
        from address a
        left join addresstype b on b.addresstypeid = a.addresstypeid
        left join city c on c.cityid = a.cityid
        where a.addressbookid = " . $row['addressbookid'] . " order by addressid " . " limit 1";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $contact     = '';
      $addressname = '';
      $phoneno     = '';
      $faxno       = '';
      foreach ($dataReader1 as $row1) {
        $addressname = $row1['addressname'];
        $phoneno     = $row1['phoneno'];
        $faxno       = $row1['faxno'];
      }
      $sql2        = "select ifnull(a.addresscontactname,'') as addresscontactname, ifnull(a.phoneno,'') as phoneno, ifnull(a.mobilephone,'') as mobilephone
					from addresscontact a
					where addresscontactid = " . $row['addresscontactid'];
      $command2    = $this->connection->createCommand($sql2);
      $dataReader2 = $command2->queryAll();
      foreach ($dataReader2 as $row2) {
        $contact = $row2['addresscontactname'];
      }
			$this->pdf->sety($this->pdf->gety() + 40);
      $this->pdf->setFont('Arial', '', 10);
      $this->pdf->Rect(10, 70, 203, 40);
      $this->pdf->text(15, 75, 'PO No');
      $this->pdf->text(40, 75, ': ' . $row['pono']);
      $this->pdf->text(120, 75, 'PO Date');
      $this->pdf->text(150, 75, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
      $this->pdf->text(15, 80, 'Supplier');
      $this->pdf->text(40, 80, ': ' . $row['fullname']);
      $this->pdf->text(15, 85, 'Attention');
      $this->pdf->text(40, 85, ': ' . $contact);
      $this->pdf->text(15, 90, 'Address');
      $this->pdf->text(40, 90, ': ' . $addressname);
      $this->pdf->text(15, 95, 'Phone No');
      $this->pdf->text(40, 95, ': ' . $phoneno);
      $this->pdf->text(15, 100, 'Fax No');
      $this->pdf->text(40, 100, ': ' . $faxno);
			
			if($isjasa==1) {
				$sql1        = "select *,(jumlah * (ifnull(taxvalue,0) / 100)) as ppn, jumlah + (jumlah * (ifnull(taxvalue,0) / 100)) as total
        from (select a.poheaderid,c.uomcode,a.qty,a.price,(a.price*a.qty*f.currencyrate) as jumlah,b.productname,
        d.symbol,d.i18n,g.taxvalue,a.description,a.reqdate
        from pojasa a
				left join poheader f on f.poheaderid = a.poheaderid
        left join product b on b.productid = a.productid
        left join unitofmeasure c on c.unitofmeasureid = a.uomid
        left join currency d on d.currencyid = f.currencyid
        left join taxpo e on e.poheaderid = a.poheaderid
        left join tax g on g.taxid = e.taxid
        where a.poheaderid = ".$row['poheaderid'].") z";
				
				$command1    = $this->connection->createCommand($sql1);
				$dataReader1 = $command1->queryAll();
				$total = 0;$jumlah = 0;$ppn = 0;
				$this->pdf->sety($this->pdf->gety() + 50);
				$this->pdf->setFont('Arial', 'B', 8);
				if($price==1) {
					$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C');
					$this->pdf->setwidths(array(15,10,70,27,27,27,27));
					$this->pdf->setbordercell(array('LTRB','LTRB','LTRB','LTRB','LTRB','LTRB','LTRB','LTRB'));
					$this->pdf->colheader = array('Qty','Units','Item', 'Unit Price','Sub Total','VAT','Total');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('R','C','L','R','R','R','R','R','R','L');
			
					$this->pdf->setFont('Arial','',8);
					$symbol = '';
					foreach ($dataReader1 as $row1) {
						$this->pdf->row(array(
							Yii::app()->format->formatCurrency($row1['qty']),
							$row1['uomcode'],
							$row1['productname']."\n"."Request Date: ".date(Yii::app()->params['dateviewfromdb'], strtotime($row1['reqdate']))."\n"."Note: ".$row1['description'],
							Yii::app()->format->formatCurrency($row1['price'], $row1['symbol']),
							Yii::app()->format->formatCurrency($row1['jumlah'], $row1['symbol']),
							Yii::app()->format->formatCurrency($row1['ppn'], $row1['symbol']),
							Yii::app()->format->formatCurrency($row1['total'], $row1['symbol']),
						));
						$jumlah = $row1['jumlah'] + $jumlah;
						$ppn = $row1['ppn'] + $ppn;
						$total = $row1['total'] + $total;
						$symbol = $row1['symbol'];
					}
					$this->pdf->row(array(
						'',
						'',
						'',
						'Grand Total',
						Yii::app()->format->formatCurrency($jumlah,$symbol),
						Yii::app()->format->formatCurrency($ppn,$symbol),
						Yii::app()->format->formatCurrency($total,$symbol),
					));
				}
				else {
					$this->pdf->colalign = array('C','C','C','C','C');
          $this->pdf->setwidths(array(20,20,100,20,42));
          $this->pdf->setbordercell(array('LTRB','LTRB','LTRB','LTRB','LTRB'));
            $this->pdf->colheader = array('Qty','Unit','Item','Request','Remarks');
          $this->pdf->RowHeader();
          $this->pdf->coldetailalign = array('R','C','L','R','L');
      
          $this->pdf->setFont('Arial','',8);
          $symbol = '';
          foreach ($dataReader1 as $row1) {
						$this->pdf->row(array(
							Yii::app()->format->formatCurrency($row1['qty']),
							$row1['uomcode'],
							iconv("UTF-8", "ISO-8859-1", $row1['productname']),
							date(Yii::app()->params['dateviewfromdb'], strtotime($row1['reqdate'])),
							$row1['description']
						));
					}
				}
			}
			else {
				$sql1        = "select *,(jumlah * (ifnull(taxvalue,0) / 100)) as ppn, jumlah + (jumlah * (ifnull(taxvalue,0) / 100)) as total
					from (select a.poheaderid,c.uomcode,a.qty,a.arrivedate,a.price,(a.price*a.qty*f.currencyrate) as jumlah,b.productname,
					d.symbol,d.i18n,g.taxvalue,a.itemnote,a.toleransiup,a.toleransidown
					from podetail a
					left join poheader f on f.poheaderid = a.poheaderid
					left join product b on b.productid = a.productid
					left join unitofmeasure c on c.unitofmeasureid = a.uomid
					left join currency d on d.currencyid = f.currencyid
					left join taxpo e on e.poheaderid = a.poheaderid
					left join tax g on g.taxid = e.taxid
					where a.poheaderid = ".$row['poheaderid'].") z";
				$command1    = $this->connection->createCommand($sql1);
				$dataReader1 = $command1->queryAll();
				$total = 0;$jumlah = 0;$ppn = 0;
				$this->pdf->sety($this->pdf->gety() + 50);
				$this->pdf->setFont('Arial', 'B', 8);
				if($price==1) {
					$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C');
					$this->pdf->setwidths(array(15,10,70,27,27,27,27,18));
					$this->pdf->setbordercell(array('LTRB','LTRB','LTRB','LTRB','LTRB','LTRB','LTRB'));
					$this->pdf->colheader = array('Qty','Unit','Item', 'Unit Price','Sub Total','VAT','Total');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('R','C','L','R','R','R','R','R','R','L');
			
					$this->pdf->setFont('Arial','',8);
					$symbol = '';
					foreach ($dataReader1 as $row1) {
						$this->pdf->row(array(
							Yii::app()->format->formatCurrency($row1['qty']),
							$row1['uomcode'],
							$row1['productname'].
							"\n"."Under Tol: ".$row1['toleransidown'].'%'.
							"\n"."Over Tol: ".$row1['toleransiup'].'%'.
							"\n"."Request Date: ".date(Yii::app()->params['dateviewfromdb'], strtotime($row1['arrivedate'])).
							"\n"."Note: ".$row1['itemnote'],
							Yii::app()->format->formatCurrency($row1['price'], $row1['symbol']),
							Yii::app()->format->formatCurrency($row1['jumlah'], $row1['symbol']),
							Yii::app()->format->formatCurrency($row1['ppn'], $row1['symbol']),
							Yii::app()->format->formatCurrency($row1['total'], $row1['symbol'])
						));
						$jumlah = $row1['jumlah'] + $jumlah;
						$ppn = $row1['ppn'] + $ppn;
						$total = $row1['total'] + $total;
						$symbol = $row1['symbol'];
					}
					$this->pdf->row(array(
						'',
						'',
						'',
						'Grand Total',
						Yii::app()->format->formatCurrency($jumlah,$symbol),
						Yii::app()->format->formatCurrency($ppn,$symbol),
						Yii::app()->format->formatCurrency($total,$symbol),
					));
				}
				else {
						$this->pdf->colalign = array('C','C','C','C','C');
						$this->pdf->setwidths(array(20,20,100,20,42));
						$this->pdf->setbordercell(array('LTRB','LTRB','LTRB','LTRB','LTRB'));
							$this->pdf->colheader = array('Qty','Unit','Item','Delivery','Remarks');
						$this->pdf->RowHeader();
						$this->pdf->coldetailalign = array('R','C','L','R','L');
				
						$this->pdf->setFont('Arial','',8);
						$symbol = '';
						foreach ($dataReader1 as $row1) {
						$this->pdf->row(array(
						Yii::app()->format->formatCurrency($row1['qty']),
						$row1['uomcode'],
						iconv("UTF-8", "ISO-8859-1", $row1['productname']),
						date(Yii::app()->params['dateviewfromdb'], strtotime($row1['arrivedate'])),
						$row1['itemnote']
					));
				}
			}
			}
      $this->pdf->title = '';
      $this->pdf->checknewpage(30);
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->setFont('Arial', 'BU', 10);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'TERM OF CONDITIONS');
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        50,
        140
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none'
      ));
      $this->pdf->colheader = array(
        'Item',
        'Remark'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->row(array(
        'Payment Term',
        $row['paymentname']
      ));
      $this->pdf->row(array(
        'Send To',
        $row['shipto']
      ));
      $this->pdf->row(array(
        'Bill To',
        $row['billto']
      ));
      $this->pdf->row(array(
        'Remarks',
        $row['headernote']
      ));
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->CheckPageBreak(20);
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'Thanking you and assuring our best attention we remain.');
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Sincerrely Yours');
      $this->pdf->text(10, $this->pdf->gety() + 15, $row['companyname']);
      $this->pdf->text(135, $this->pdf->gety() + 15, 'Confirmed and Accepted by Supplier');
      $this->pdf->text(10, $this->pdf->gety() + 35, '');
      $this->pdf->text(10, $this->pdf->gety() + 36, '____________________');
      $this->pdf->text(135, $this->pdf->gety() + 36, '__________________________');
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->text(10, $this->pdf->gety() + 40, '');
      $this->pdf->setFont('Arial', 'BU', 7);
      $this->pdf->text(10, $this->pdf->gety() + 55, '#Note: Please do not give gift or money to our staff#');
      $this->pdf->text(10, $this->pdf->gety() + 60, '#Print No: ' . $row['printke']);
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    $this->menuname = 'poheaderlist';
    parent::actionDownxls();
		$poheaderid = GetSearchText(array('POST','Q','GET'),'poheaderid');
		$plantcode     		= GetSearchText(array('POST','Q'),'plantcode');
		$productname   		= GetSearchText(array('POST','Q','GET'),'productname');
    $podate    = GetSearchText(array('POST','Q'),'podate');
    $pono 		= GetSearchText(array('POST','Q'),'pono');
		$supplier 		= GetSearchText(array('POST','GET','Q'),'supplier');
		$headernote 		= GetSearchText(array('POST','Q'),'headernote');
    $sql = "select a.poheaderid,a.pono,a.podate,g.sloccode,a.headernote,d.productname,c.qty,c.qty2,e.uomcode,i.uomcode as uom2code,
			j.fullname as supplier,k.addresscontactname,l.paycode,a.isjasa,a.isimport,m.symbol,a.currencyrate,o.taxcode,r.prno,b.plantcode,
			c.price,c.arrivedate,c.toleransiup,c.toleransidown,f.kodemesin,c.itemnote,s.requestedbycode,
			u.productname as productjasa,v.uomcode as uomjasa,t.price as pricejasa,w.sloccode as slocjasa,ifnull(t.reqdate,'-') as reqdate,x.kodemesin as kodejasa,
			t.description as itemjasa,z.prno as prnojasa,t.qty as qtyjasa, uz.productname as productresult,  
			bz.uomcode as uomresult, cz.uomcode as uom2result, dz.uomcode as uom3result, ez.uomcode as uom4result, az.description as descresult,GetStdqty2(d.productid) as stdqty2,
						getStock(d.productid,c.uomid,c.slocid) as qtystock,c.qty-c.grqty as qtysisa,c.grqty
			from poheader a
			inner join plant b on b.plantid = a.plantid  
			left join podetail c on c.poheaderid = a.poheaderid 
			left join product d on d.productid = c.productid
			left join unitofmeasure e on e.unitofmeasureid = c.uomid
			left join mesin f on f.mesinid = c.mesinid
			left join sloc g on g.slocid = c.slocid
			left join unitofmeasure i on i.unitofmeasureid = c.uom2id 
			left join addressbook	j on j.addressbookid = a.addressbookid  
			left join addresscontact k on k.addresscontactid = a.addresscontactid 
			left join paymentmethod l on l.paymentmethodid = a.paymentmethodid
			left join currency m on m.currencyid = a.currencyid 
			left join taxpo n on n.poheaderid = a.poheaderid 
			left join tax o on o.taxid = n.taxid 
			left join prraw q on q.prrawid = c.prrawid 
			left join prheader r on r.prheaderid = q.prheaderid 
			left join requestedby s on s.requestedbyid = c.requestedbyid 
			left join pojasa t on t.poheaderid = a.poheaderid 
			left join product u on u.productid = t.productid 
			left join unitofmeasure v on v.unitofmeasureid = t.uomid 
			left join sloc w on w.slocid = t.sloctoid 
			left join mesin x on x.mesinid = t.mesinid 
			left join prjasa y on y.prjasaid = t.prjasaid 
			left join prheader z on z.prheaderid = y.prheaderid 
			left join poresult az on az.poheaderid = a.poheaderid 
			left join product uz on uz.productid = az.productid 
			left join unitofmeasure bz on bz.unitofmeasureid = az.uomid 
			left join unitofmeasure cz on cz.unitofmeasureid = az.uom2id 
			left join unitofmeasure dz on dz.unitofmeasureid = az.uom3id 
			left join unitofmeasure ez on ez.unitofmeasureid = az.uom4id 
		";   
		$sql .= " where 
			coalesce(b.plantcode,'') like '".$plantcode."' 
			and coalesce(d.productname,'') like '".$productname."' 
			and coalesce(j.fullname,'') like '".$supplier."' 
			and coalesce(a.pono,'') like '".$pono."' 
			and coalesce(a.headernote,'') like '".$headernote."' 			
		";
		if ($_GET['id'] !== '') {
      $sql = $sql . " and a.poheaderid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 3;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $line, $line-1)
          ->setCellValueByColumnAndRow(1, $line, $row['plantcode'])
          ->setCellValueByColumnAndRow(2, $line, date(Yii::app()->params['dateviewfromdb'], strtotime($row['podate'])))
          ->setCellValueByColumnAndRow(3, $line, $row['pono'])
          ->setCellValueByColumnAndRow(4, $line, $row['supplier'])
          ->setCellValueByColumnAndRow(5, $line, $row['addresscontactname'])
          ->setCellValueByColumnAndRow(6, $line, $row['paycode'])
          ->setCellValueByColumnAndRow(7, $line, $row['isjasa'])
          ->setCellValueByColumnAndRow(8, $line, $row['isimport'])
          ->setCellValueByColumnAndRow(9, $line, $row['symbol'])
          ->setCellValueByColumnAndRow(10, $line, $row['currencyrate'])
          ->setCellValueByColumnAndRow(11, $line, $row['headernote'])
          ->setCellValueByColumnAndRow(12, $line, $row['taxcode'])
          ->setCellValueByColumnAndRow(13, $line, $row['prno'])
          ->setCellValueByColumnAndRow(14, $line, $row['productname'])
					->setCellValueByColumnAndRow(15, $line, Yii::app()->format->formatNumber($row['grqty']))
          ->setCellValueByColumnAndRow(16, $line, Yii::app()->format->formatNumber($row['qtysisa']))
          ->setCellValueByColumnAndRow(17, $line, Yii::app()->format->formatNumber($row['qty']))
          ->setCellValueByColumnAndRow(18, $line, $row['uomcode'])
          ->setCellValueByColumnAndRow(19, $line, Yii::app()->format->formatNumber($row['qty2']))
          ->setCellValueByColumnAndRow(20, $line, $row['uom2code'])
          ->setCellValueByColumnAndRow(21, $line, Yii::app()->format->formatNumber($row['price']))
          ->setCellValueByColumnAndRow(22, $line, date(Yii::app()->params['dateviewfromdb'], strtotime($row['arrivedate'])))
          ->setCellValueByColumnAndRow(23, $line, Yii::app()->format->formatNumber($row['toleransiup']))
          ->setCellValueByColumnAndRow(24, $line, Yii::app()->format->formatNumber($row['toleransidown']))
          ->setCellValueByColumnAndRow(25, $line, $row['sloccode'])
          ->setCellValueByColumnAndRow(26, $line, $row['requestedbycode'])
          ->setCellValueByColumnAndRow(27, $line, $row['itemnote'])
          ->setCellValueByColumnAndRow(28, $line, $row['prnojasa'])
          ->setCellValueByColumnAndRow(29, $line, $row['productjasa'])
          ->setCellValueByColumnAndRow(30, $line, $row['qtyjasa'])
          ->setCellValueByColumnAndRow(31, $line, $row['uomjasa'])
          ->setCellValueByColumnAndRow(32, $line, $row['pricejasa'])
          ->setCellValueByColumnAndRow(33, $line, date(Yii::app()->params['dateviewfromdb'], strtotime($row['reqdate'])))
			;
      $line++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}
