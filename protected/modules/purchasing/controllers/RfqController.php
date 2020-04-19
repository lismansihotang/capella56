<?php
class RfqController extends Controller {
  public $menuname = 'rfq';
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
  public function actionIndexrfqjasa() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchrfqjasa();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexrfqresult() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchrfqresult();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndextaxrfq() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchtaxrfq();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
			$id = rand(-1, -1000000000);
			echo CJSON::encode(array(
				'rfqid' => $id,
			));
  }
	public function actionGeneratedetail() {
    if (isset($_POST['id'])) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GeneratePRrfqJASA(:vid, :vhid)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
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
    header('Content-Type: application/json');
		$plantid = GetSearchText(array('POST','Q','GET'),'plantid',0,'int');
		$rfqid = GetSearchText(array('POST','Q','GET'),'rfqid');
		$plantcode     		= GetSearchText(array('POST','Q'),'plantcode');
		$productcode   		= GetSearchText(array('POST','Q','GET'),'productcode');
		$productname   		= GetSearchText(array('POST','Q','GET'),'productname');
		$isjasa   		= GetSearchText(array('GET'),'isjasa',0,'int');
    $rfqdate    = GetSearchText(array('POST','Q'),'rfqdate');
    $rfqno 		= GetSearchText(array('POST','Q'),'rfqno');
    $addressbookid      = GetSearchText(array('POST','Q','GET'),'addressbookid',0,'int');
		$fullname      = GetSearchText(array('POST','Q'),'fullname');
		$headernote 		= GetSearchText(array('POST','Q'),'headernote');
    $page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','rfqid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		if (!isset($_GET['getdata'])) {
			if (isset($_GET['grrfq'])) {
				if ($isjasa == '0') {
					$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
						->from('rfq t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addresscontact d', 'd.addresscontactid = t.addresscontactid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
						((coalesce(t.rfqid,'') like :rfqid) 
				or (coalesce(a.fullname,'') like :fullname) 
				or (coalesce(t.rfqno,'') like :rfqno)  
				or (coalesce(b.plantcode,'') like :plantcode)  
				or (coalesce(t.rfqdate,'') like :rfqdate) 
				or (coalesce(t.headernote,'') like :headernote)) 
						and t.rfqno is not null 
						and t.recordstatus in (".getUserRecordStatus('listrfq').")
						and t.plantid = ".$plantid."
						and t.isjasa = 0
						and t.rfqid in (select z.rfqid 
							from rfqdetail z 
							where z.qty > z.grqty)
						",
					array(
				':rfqid' =>  $rfqid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $fullname ,
				':rfqno' =>  $rfqno ,
				':headernote' =>  $headernote ,
				':rfqdate' =>  $rfqdate 
			))->queryScalar();
				} else {
					$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
						->from('rfq t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addresscontact d', 'd.addresscontactid = t.addresscontactid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
						((coalesce(t.rfqid,'') like :rfqid) 
				or (coalesce(a.fullname,'') like :fullname) 
				or (coalesce(t.rfqno,'') like :rfqno)  
				or (coalesce(b.plantcode,'') like :plantcode)  
				or (coalesce(t.rfqdate,'') like :rfqdate) 
				or (coalesce(t.headernote,'') like :headernote))
						and t.rfqno is not null 
						and t.recordstatus in (".getUserRecordStatus('listrfq').")
						and t.plantid = ".$plantid."
						and t.isjasa = 1
						and t.rfqid in (select z.rfqid 
							from rfqjasa z 
							where z.qty > z.grqty)
						",
					array(
				':rfqid' =>  $rfqid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $fullname ,
				':rfqno' =>  $rfqno ,
				':headernote' =>  $headernote ,
				':rfqdate' =>  $rfqdate 
			))->queryScalar();
			} }
			 else if
			 (isset($_GET['invaprfq'])) {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('rfq t')
				->join('grheader e', 'e.rfqid = t.rfqid')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
					((coalesce(t.rfqid,'') like :rfqid) 
				or (coalesce(a.fullname,'') like :fullname) 
				or (coalesce(t.rfqno,'') like :rfqno)  
				or (coalesce(b.plantcode,'') like :plantcode)  
				or (coalesce(t.rfqdate,'') like :rfqdate) 
				or (coalesce(t.headernote,'') like :headernote)) 
					and t.rfqno is not null 
					and t.recordstatus in (".getUserRecordStatus('listrfq').")
					and e.isinvap = 0
				and t.plantid = '".$plantid."' 
				and t.addressbookid = '".$addressbookid."' 
					",
				array(
				':rfqid' =>  $rfqid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $fullname ,
				':rfqno' =>  $rfqno ,
				':headernote' =>  $headernote ,
				':rfqdate' =>  $rfqdate 
			))->queryScalar();
			} else if
			(isset($_GET['combo'])) {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('rfq t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addresscontact d', 'd.addresscontactid = t.addresscontactid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
					((coalesce(t.rfqid,'') like :rfqid) 
				or (coalesce(a.fullname,'') like :fullname) 
				or (coalesce(t.rfqno,'') like :rfqno)  
				or (coalesce(b.plantcode,'') like :plantcode)  
				or (coalesce(t.rfqdate,'') like :rfqdate) 
				or (coalesce(t.headernote,'') like :headernote)) 
					and t.rfqno is not null 
					and t.recordstatus in (".getUserRecordStatus('listrfq').")
					",
				array(
				':rfqid' =>  $rfqid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $fullname ,
				':rfqno' =>  $rfqno ,
				':headernote' =>  $headernote ,
				':rfqdate' =>  $rfqdate 
			))->queryScalar();
			} else {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('rfq t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addresscontact d', 'd.addresscontactid = t.addresscontactid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
					((coalesce(rfqid,'') like :rfqid) 
				and (coalesce(plantcode,'') like :plantcode) 
				and (coalesce(fullname,'') like :fullname) 
				and (coalesce(rfqno,'') like :rfqno)  
				and (coalesce(rfqdate,'') like :rfqdate) 
				and (coalesce(headernote,'') like :headernote)) 				
				and t.recordstatus in (".getUserRecordStatus('listrfq').") 
					",
				array(
				':rfqid' =>  $rfqid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $fullname ,
				':rfqno' =>  $rfqno ,
				':headernote' =>  $headernote ,
				':rfqdate' =>  $rfqdate 
			))->queryScalar();
			}
			$result['total'] = $cmd;
			if (isset($_GET['grrfq'])) {
				if ($isjasa == '0') {
					$cmd = Yii::app()->db->createCommand()->select('t.*,b.plantcode,t.headernote,b.companyid,c.companyname,a.fullname,c.billto,b.addresstoname,
				g.paycode,h.currencyname,a.creditlimit,d.addresscontactname,
				getamountbypo(t.rfqid) as totprice')
				->from('rfq t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addresscontact d', 'd.addresscontactid = t.addresscontactid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
						((coalesce(rfqid,'') like :rfqid) 
				or (coalesce(plantcode,'') like :plantcode) 
				or (coalesce(fullname,'') like :fullname) 
				or (coalesce(rfqno,'') like :rfqno)  
				or (coalesce(rfqdate,'') like :rfqdate) 
				or (coalesce(headernote,'') like :headernote))
						and t.rfqno is not null 
						and t.recordstatus in (".getUserRecordStatus('listrfq').")
						and t.plantid = ".$plantid."
						and t.isjasa = 0 
						and t.rfqid in (select z.rfqid 
							from rfqdetail z 
							where z.qty > z.grqty)
						",
					array(
				':rfqid' =>  $rfqid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $fullname ,
				':rfqno' =>  $rfqno ,
				':headernote' =>  $headernote ,
				':rfqdate' =>  $rfqdate 
					))->queryAll();
				} else {
					$cmd = Yii::app()->db->createCommand()->select('t.*,b.plantcode,t.headernote,b.companyid,c.companyname,a.fullname,c.billto,b.addresstoname,
				g.paycode,h.currencyname,a.creditlimit,d.addresscontactname,
				getamountbypo(t.rfqid) as totprice')
				->from('rfq t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addresscontact d', 'd.addresscontactid = t.addresscontactid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
						((coalesce(rfqid,'') like :rfqid) 
				or (coalesce(plantcode,'') like :plantcode) 
				or (coalesce(fullname,'') like :fullname) 
				or (coalesce(rfqno,'') like :rfqno)  
				or (coalesce(rfqdate,'') like :rfqdate) 
				or (coalesce(headernote,'') like :headernote)) 
						and t.rfqno is not null 
					and t.recordstatus in (".getUserRecordStatus('listrfq').")
						and t.plantid = ".$plantid."
						and t.isjasa = 1 
						and t.rfqid in (select z.rfqid 
							from rfqjasa z 
							where z.qty > z.grqty)
						",
					array(
						':rfqid' =>  $rfqid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $fullname ,
				':rfqno' =>  $rfqno ,
				':headernote' =>  $headernote ,
				':rfqdate' =>  $rfqdate 
					))->queryAll();
				}
			} else if
			(isset($_GET['invaprfq'])) {
				$cmd = Yii::app()->db->createCommand()->selectdistinct('t.*,b.plantcode,t.headernote,b.companyid,c.companyname,a.fullname,c.billto,b.addresstoname,
				g.paycode,h.currencyname,a.creditlimit,(null) as addresscontactname,
				getamountbypo(t.rfqid) as totprice')
				->from('rfq t')
				->join('grheader e', 'e.rfqid = t.rfqid')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
					((coalesce(t.rfqid,'') like :rfqid) 
				or (coalesce(a.fullname,'') like :fullname) 
				or (coalesce(t.rfqno,'') like :rfqno)  
				or (coalesce(b.plantcode,'') like :plantcode)  
				or (coalesce(t.rfqdate,'') like :rfqdate) 
				or (coalesce(t.headernote,'') like :headernote))
					and t.rfqno is not null 
				and t.recordstatus in (".getUserRecordStatus('listrfq').")
					and e.isinvap = 0
				and t.plantid = '".$plantid."' 
				and t.addressbookid = '".$addressbookid."' 
				",
				array(
				':rfqid' =>  $rfqid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $fullname ,
				':rfqno' =>  $rfqno ,
				':headernote' =>  $headernote ,
				':rfqdate' =>  $rfqdate 
				))->queryAll();
			} else if
			(isset($_GET['combo'])) {
				$cmd = Yii::app()->db->createCommand()->select('t.*,b.plantcode,t.headernote,b.companyid,c.companyname,a.fullname,c.billto,b.addresstoname,
				g.paycode,h.currencyname,a.creditlimit,d.addresscontactname,
				getamountbypo(t.rfqid) as totprice')
				->from('rfq t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addresscontact d', 'd.addresscontactid = t.addresscontactid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
					((coalesce(rfqid,'') like :rfqid) 
				or (coalesce(plantcode,'') like :plantcode) 
				or (coalesce(fullname,'') like :fullname) 
				or (coalesce(rfqno,'') like :rfqno)  
				or (coalesce(rfqdate,'') like :rfqdate) 
				or (coalesce(headernote,'') like :headernote))
					and t.rfqno is not null 
					and t.recordstatus in (".getUserRecordStatus('listrfq').")
				",
				array(
				':rfqid' =>  $rfqid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $fullname ,
				':rfqno' =>  $rfqno ,
				':headernote' =>  $headernote ,
				':rfqdate' =>  $rfqdate 
				))->queryAll();
			} else {
				$cmd = Yii::app()->db->createCommand()->select('t.*,b.plantcode,t.headernote,b.companyid,c.companyname,a.fullname,c.billto,b.addresstoname,
				g.paycode,h.currencyname,a.creditlimit,d.addresscontactname,
				getamountbypo(t.rfqid) as totprice')
				->from('rfq t')
				->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addresscontact d', 'd.addresscontactid = t.addresscontactid')
				->leftjoin('paymentmethod g', 'g.paymentmethodid = t.paymentmethodid')
				->leftjoin('currency h', 'h.currencyid = t.currencyid')
				->where("
					((coalesce(rfqid,'') like :rfqid) 
				and (coalesce(plantcode,'') like :plantcode) 
				and (coalesce(fullname,'') like :fullname) 
				and (coalesce(rfqno,'') like :rfqno)  
				and (coalesce(rfqdate,'') like :rfqdate) 
				and (coalesce(headernote,'') like :headernote)) 	
				and t.recordstatus in (".getUserRecordStatus('listrfq').")
					",
				array(
				':rfqid' =>  $rfqid ,
				':plantcode' =>  $plantcode ,
				':fullname' =>  $fullname ,
				':rfqno' =>  $rfqno ,
				':headernote' =>  $headernote ,
				':rfqdate' =>  $rfqdate 
					))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
			}
			foreach ($cmd as $data) {
			$row[] = array(
				'rfqid' => $data['rfqid'],
				'rfqdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['rfqdate'])),
				'rfqno' => $data['rfqno'],
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
		$rfqid = GetSearchText(array('POST','Q','GET'),'rfqid',0,'int');
			$cmd = Yii::app()->db->createCommand("
				select a.*,b.plantcode,c.companycode,d.fullname 
				from rfq a 
				join plant b on b.plantid = a.plantid 
				join company c on c.companyid = b.companyid 
				join addressbook d on d.addressbookid = a.addressbookid
				where a.rfqid = ".$rfqid)->queryRow();
			$result = $cmd;
		}
    return CJSON::encode($result);
	}
	public function actionSearchdetail()
	{
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','rfqdetailid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('rfqdetail t')
			->leftjoin('rfq g', 'g.rfqid = t.rfqid')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('requestedby b', 'b.requestedbyid = t.requestedbyid')
			->leftjoin('prheader c', 'c.prheaderid = t.prheaderid')
			->leftjoin('sloc d', 'd.slocid = t.slocid')
			->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')
			->where('t.rfqid = :rfqid',
			array(
				':rfqid' => $id
			))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
					->select('t.*,a.productcode,a.productname,d.sloccode,c.prno,b.requestedbycode,
						getamountdetailbypo(t.rfqid,t.rfqdetailid) as totprice,e.materialtypecode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code')
					->from('rfqdetail t')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('requestedby b', 'b.requestedbyid = t.requestedbyid')
					->leftjoin('prheader c', 'c.prheaderid = t.prheaderid')
					->leftjoin('sloc d', 'd.slocid = t.slocid')
			->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')
					->where('t.rfqid = :rfqid', array(
		':rfqid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'rfqdetailid' => $data['rfqdetailid'],
        'rfqid' => $data['rfqid'],
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
				'qtysisa' => Yii::app()->format->formatNumber($data['qty']-$data['grqty']),
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
				'toleransiup' => Yii::app()->format->formatNumber($data['toleransidown']),
				'toleransidown' => Yii::app()->format->formatNumber($data['toleransidown']),
				'itemnote' => $data['itemnote']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
  public function actionSearchrfqjasa()
	{
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','rfqjasaid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('rfqjasa t')
			->leftjoin('rfq g', 'g.rfqid = t.rfqid')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('mesin c', 'c.mesinid = t.mesinid')
			->leftjoin('sloc d', 'd.slocid = t.sloctoid')
			->leftjoin('prheader e', 'e.prheaderid = t.prheaderid')
->leftjoin('materialtype f', 'f.materialtypeid = a.materialtypeid')			
->where('t.rfqid = :rfqid',
			array(
				':rfqid' => $id
			))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
					->select('t.*,e.prno,a.productcode,a.productname,c.namamesin,d.sloccode,t.description,f.materialtypecode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode')
					->from('rfqjasa t')
					->leftjoin('rfq g', 'g.rfqid = t.rfqid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('mesin c', 'c.mesinid = t.mesinid')
					->leftjoin('sloc d', 'd.slocid = t.sloctoid')
			->leftjoin('prheader e', 'e.prheaderid = t.prheaderid')
			->leftjoin('materialtype f', 'f.materialtypeid = a.materialtypeid')
					->where('t.rfqid = :rfqid', array(
		':rfqid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'rfqjasaid' => $data['rfqjasaid'],
        'rfqid' => $data['rfqid'],
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
        'sloctoid' => $data['sloctoid'],
        'sloccode' => $data['sloccode'],
		'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
  public function actionSearchrfqResult()
	{
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','rfqresultid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('rfqresult t')
					->leftjoin('rfq g', 'g.rfqid = t.rfqid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('prheader b', 'b.prheaderid = t.prheaderid')
					->where('t.rfqid = :rfqid',
					array(
				':rfqid' => $id
				))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
					->select('t.*,a.productcode,a.productname,b.prno,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,t.description,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom4id) as uom4code')
					->from('rfqresult t')
					->leftjoin('rfq g', 'g.rfqid = t.rfqid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('prheader b', 'b.prheaderid = t.prheaderid')
					->where('t.rfqid = :rfqid', array(
		':rfqid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
	foreach ($cmd as $data) {
      $row[] = array(
        'rfqresultid' => $data['rfqresultid'],
        'rfqid' => $data['rfqid'],
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
    ;
    echo CJSON::encode($result);
  }
  public function actionSearchTaxrfq()
	{
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','taxrfqid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('taxrfq t')
					->leftjoin('tax a', 'a.taxid = t.taxid')
					->where('t.rfqid = :rfqid',
					array(
						':rfqid' => $id
					))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
					->select('t.*,a.taxcode,a.taxvalue')
					->from('taxrfq t')
					->leftjoin('tax a', 'a.taxid = t.taxid')
					->where('t.rfqid = :rfqid', array(
		':rfqid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'taxrfqid' => $data['taxrfqid'],
        'rfqid' => $data['rfqid'],
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
        $sql     = 'call Rejectrfq(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
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
        $sql     = 'call Approverfq(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
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
		$sql = 'call Modifrfq(:vid,:vrfqdate,:vplantid,:vaddressbookid,:vaddresscontactid,:vpaymentmethodid,
								:vcurrencyid,:vcurrencyrate,:visjasa,:visimport,:vheadernote,:vcreatedby)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vrfqdate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vaddresscontactid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vpaymentmethodid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyrate', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':visjasa', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':visimport', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();					
	}
	public function actionSave() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['rfq-rfqid'])?$_POST['rfq-rfqid']:''),
			date(Yii::app()->params['datetodb'], strtotime($_POST['rfq-rfqdate'])),
			$_POST['rfq-plantid'],$_POST['rfq-addressbookid'],$_POST['rfq-addresscontactid'],$_POST['rfq-paymentmethodid'],
			$_POST['rfq-currencyid'],$_POST['rfq-currencyrate'],(isset($_POST['rfq-isjasa']) ? 1 : 0),
			(isset($_POST['rfq-isimport']) ? 1 : 0),$_POST['rfq-headernote']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
  }
  public function actionSavedetail()
  {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPOSTRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertrfqdetail(:vrfqid,:vprrawid,:vproductid,:vqty,:vuomid,:vqty2,:vuom2id,
						:vprice,:varrivedate,:vtoleransiup,:vtoleransidown,:vslocid,:vrequestedbyid,:vitemnote,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updaterfqdetail(:vid,:vrfqid,:vprrawid,:vproductid,:vqty,:vuomid,:vqty2,:vuom2id,
						:vprice,:varrivedate,:vtoleransiup,:vtoleransidown,:vslocid,:vrequestedbyid,:vitemnote,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['rfqdetailid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['rfqdetailid']);
      }
      $command->bindvalue(':vrfqid', $_POST['rfqid'], PDO::PARAM_STR);
	  $command->bindvalue(':vprrawid', $_POST['prrawid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
	  $command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
	  $command->bindvalue(':vqty2', $_POST['qty2'], PDO::PARAM_STR);
      $command->bindvalue(':vuom2id', $_POST['uom2id'], PDO::PARAM_STR);
      $command->bindvalue(':vprice', $_POST['price'], PDO::PARAM_STR);
	  $command->bindvalue(':varrivedate', date(Yii::app()->params['datetodb'], strtotime($_POST['arrivedate'])), PDO::PARAM_STR);
			$command->bindvalue(':vtoleransiup', $_POST['toleransiup'], PDO::PARAM_STR);
			$command->bindvalue(':vtoleransidown', $_POST['toleransidown'], PDO::PARAM_STR);
			$command->bindvalue(':vslocid', $_POST['slocid'], PDO::PARAM_STR);
			$command->bindvalue(':vrequestedbyid', $_POST['requestedbyid'], PDO::PARAM_STR);
			$command->bindvalue(':vitemnote', $_POST['itemnote'], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
  public function actionSaverfqjasa() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPOSTRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertrfqjasa(:vrfqid,:vprjasaid,:vproductid,:vuomid,:vqty,:vreqdate,
					:vsloctoid,:vmesinid,:vdescription,:vprice,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updaterfqjasa(:vid,:vrfqid,:vprjasaid,:vproductid,:vuomid,:vqty,:vreqdate,
					:vsloctoid,:vmesinid,:vdescription,:vprice,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['rfqjasaid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['rfqjasaid']);
      }
      $command->bindvalue(':vrfqid', $_POST['rfqid'], PDO::PARAM_STR);
      $command->bindvalue(':vprjasaid', $_POST['prjasaid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
			$command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
      $command->bindvalue(':vreqdate', date(Yii::app()->params['datetodb'], strtotime($_POST['reqdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vsloctoid', $_POST['sloctoid'], PDO::PARAM_STR);
			$command->bindvalue(':vmesinid', $_POST['mesinid'], PDO::PARAM_STR);
			$command->bindvalue(':vprice', $_POST['price'], PDO::PARAM_STR);
			$command->bindvalue(':vdescription', $_POST['description'], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
  public function actionSaverfqresult() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPOSTRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertrfqresult(:vrfqid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,:vqty4,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updaterfqresult(:vid,:vrfqid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,:vqty4,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['rfqresultid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['rfqresultid']);
      }
      $command->bindvalue(':vrfqid', $_POST['rfqid'], PDO::PARAM_STR);
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
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
  public function actionSavetaxrfq() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPOSTRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call InsertTaxrfq(:vrfqid,:vtaxid,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call UpdateTaxrfq(:vid,:vrfqid,:vtaxid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['taxrfqid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['taxrfqid']);
      }
      $command->bindvalue(':vrfqid', $_POST['rfqid'], PDO::PARAM_STR);
      $command->bindvalue(':vtaxid', $_POST['taxid'], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
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
        $sql     = 'call Purgerfq(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
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
	public function actionPurgealldetail() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgerfqalldetail(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
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
        $sql     = 'call Purgerfqdetail(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
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
  public function actionPurgerfqjasa() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgerfqjasa(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
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
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgerfqresult(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
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
  public function actionPurgeTaxrfq() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgetaxrfq(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
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
			left join rfq b on b.addressbookid = a.addressbookid
			where b.rfqid = ".$_POST['id']." 
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
		b.fullname, a.rfqno, a.rfqdate as docdate,b.addressbookid,a.rfqid,c.paymentname,a.headernote,a.printke,a.rfqid,
			ifnull(a.printke,0) as printke,a.recordstatus,d.addresstoname as shipto,e.billto
      from rfq a
      left join addressbook b on b.addressbookid = a.addressbookid
      left join paymentmethod c on c.paymentmethodid = a.paymentmethodid
			join plant d on d.plantid = a.plantid
			join company e on e.companyid = d.companyid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.rfqid in (" . $_GET['id'] . ")";
    }
    $price = getUserObjectValues($menuobject='purchasing');
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('rfq');
    $this->pdf->AddPage('P', 'Letter');
    $this->pdf->AliasNbPages();
    $this->pdf->isprint = true;
    foreach ($dataReader as $row) {
      $sql1               = "update rfq set printke = ifnull(printke,0) + 1
				where rfqid = " . $row['rfqid'];
      $command1           = $this->connection->createCommand($sql1);
      $this->pdf->printke = $row['printke'];
      $command1->execute();
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
					where addressbookid = " . $row['addressbookid'] . " order by addresscontactid " . " limit 1";
      $command2    = $this->connection->createCommand($sql2);
      $dataReader2 = $command2->queryAll();
      foreach ($dataReader2 as $row2) {
        $contact = $row2['addresscontactname'];
      }
      $this->pdf->setFont('Arial', '', 10);
      $this->pdf->Rect(10, 10, 202, 30);
      $this->pdf->text(15, 15, 'Supplier');
      $this->pdf->text(40, 15, ': ' . $row['fullname']);
      $this->pdf->text(15, 20, 'Attention');
      $this->pdf->text(40, 20, ': ' . $contact);
      $this->pdf->text(15, 25, 'Address');
      $this->pdf->text(40, 25, ': ' . $addressname);
      $this->pdf->text(15, 30, 'Phone');
      $this->pdf->text(40, 30, ': ' . $phoneno);
      $this->pdf->text(15, 35, 'Fax');
      $this->pdf->text(40, 35, ': ' . $faxno);
      $this->pdf->text(120, 15, 'No Doc ');
      $this->pdf->text(150, 15, ': ' . $row['rfqno']);
      $this->pdf->text(120, 20, 'Date ');
      $this->pdf->text(150, 20, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
      $sql1        = "select *,(jumlah * (taxvalue / 100)) as ppn, jumlah + (jumlah * (taxvalue / 100)) as total
        from (select a.rfqid,c.uomcode,a.qty,a.arrivedate,a.price,(a.price*a.qty*f.currencyrate) as jumlah,b.productname,
        d.symbol,d.i18n,g.taxvalue,a.itemnote
        from rfqdetail a
				left join rfq f on f.rfqid = a.rfqid
        left join product b on b.productid = a.productid
        left join unitofmeasure c on c.unitofmeasureid = a.uomid
        left join currency d on d.currencyid = f.currencyid
        left join taxrfq e on e.rfqid = a.rfqid
        left join tax g on g.taxid = e.taxid
        where a.rfqid = ".$row['rfqid'].") z";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $total = 0;$jumlah = 0;$ppn = 0;
      $this->pdf->sety($this->pdf->gety() + 30);
      $this->pdf->setFont('Arial', 'B', 8);
      if($price==1)
      {
          $this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C');
          $this->pdf->setwidths(array(15,10,45,22,25,22,25,18,20));
          $this->pdf->setbordercell(array('LTRB','LTRB','LTRB','LTRB','LTRB','LTRB','LTRB','LTRB','LTRB','LTRB'));
            $this->pdf->colheader = array('Qty','Units','Item', 'Unit Price','Jumlah','PPN','Total','Delivery','Remarks');
          $this->pdf->RowHeader();
          $this->pdf->coldetailalign = array('R','C','L','R','R','R','R','R','R','L');
      
          $this->pdf->setFont('Arial','',8);
          $symbol = '';
          foreach ($dataReader1 as $row1) {
          $this->pdf->row(array(
          Yii::app()->format->formatCurrency($row1['qty']),
          $row1['uomcode'],
          iconv("UTF-8", "ISO-8859-1", $row1['productname']),
          Yii::app()->format->formatCurrency($row1['netprice'], iconv("UTF-8", "ISO-8859-1", $row1['symbol'])),
			           Yii::app()->format->formatCurrency($row1['jumlah'], $row1['symbol']),
			           Yii::app()->format->formatCurrency($row1['ppn'], $row1['symbol']),
			           Yii::app()->format->formatCurrency($row1['total'], $row1['symbol']),
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['arrivedate'])),
          $row1['itemnote']
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
        '',
        ''
      ));
    }
    else
    {
        $this->pdf->colalign = array('C','C','C','C','C');
          $this->pdf->setwidths(array(20,20,100,20,42));
          $this->pdf->setbordercell(array('LTRB','LTRB','LTRB','LTRB','LTRB'));
            $this->pdf->colheader = array('Qty','Units','Item','Delivery','Remarks');
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
      $this->pdf->title = '';
      $this->pdf->checknewpage(100);
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
        'Description'
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
        'Kirim ke',
        $row['shipto']
      ));
      $this->pdf->row(array(
        'Tagih ke',
        $row['billto']
      ));
      $this->pdf->row(array(
        'Keterangan',
        $row['headernote']
      ));
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->CheckPageBreak(60);
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
      $this->pdf->text(10, $this->pdf->gety() + 55, '#Note: Mohon tidak memberikan gift atau uang kepada staff kami#');
      $this->pdf->text(10, $this->pdf->gety() + 60, '#Print ke: ' . $row['printke']);
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    $this->menuname = 'rfq';
    parent::actionDownxls();
    $sql = "select a.rfqid,a.rfqno,a.rfqdate,b.sloccode,a.description
						from rfq a
						inner join sloc b on b.slocid = a.slocid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.rfqid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 3;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $line, 'No')
          ->setCellValueByColumnAndRow(1, $line, ': ' . $row['rfqno']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $line, 'Date')
          ->setCellValueByColumnAndRow(1, $line, ': ' . $row['rfqdate']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $line, 'Gudang')
          ->setCellValueByColumnAndRow(1, $line, ': ' . $row['sloccode']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $line, 'No')
          ->setCellValueByColumnAndRow(1, $line, 'Nama Barang')
          ->setCellValueByColumnAndRow(2, $line, 'Qty')
          ->setCellValueByColumnAndRow(3, $line, 'Satuan')
          ->setCellValueByColumnAndRow(4, $line, 'Buyprice')
          ->setCellValueByColumnAndRow(5, $line, 'Jumlah')
          ->setCellValueByColumnAndRow(6, $line, 'Rak')
          ->setCellValueByColumnAndRow(7, $line, 'Status')
          ->setCellValueByColumnAndRow(8, $line, 'Keterangan');
      $line++;
      $sql1        = "select b.productname,a.qty,c.uomcode,a.description,a.location,d.namamesin,a.expiredate,e.sloccode,f.description,a.buyprice,(a.qty*a.buyprice) as jumlah
							from rfqraw a
							inner join product b on b.productid = a.productid
							inner join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
							inner join mesin d on d.mesinid = a.mesinid
							inner join sloc e on e.slocid = a.slocid
							inner join storagebin f on f.storagebinid = a.storagebinid
							where rfqid = " . $row['rfqid'] . " order by rfqrawid";
      $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
      $i           = 0;
      foreach ($dataReader1 as $row1) {
        $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(0, $line, $i += 1)
            ->setCellValueByColumnAndRow(1, $line, $row1['productname'])
            ->setCellValueByColumnAndRow(2, $line, $row1['qty'])
            ->setCellValueByColumnAndRow(3, $line, $row1['uomcode'])
            ->setCellValueByColumnAndRow(4, $line, $row1['buyprice'])
            ->setCellValueByColumnAndRow(5, $line, $row1['jumlah'])
            ->setCellValueByColumnAndRow(6, $line, $row1['description'])
            ->setCellValueByColumnAndRow(7, $line, $row1['sloccode'])
            ->setCellValueByColumnAndRow(8, $line, $row1['description']);
        $line++;
      }
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Note : ')->setCellValueByColumnAndRow(1, $line, $row['description']);
      $line += 2;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Dibuat oleh, ')->setCellValueByColumnAndRow(1, $line, 'Diperiksa oleh, ')->setCellValueByColumnAndRow(2, $line, 'Diketahui oleh, ')->setCellValueByColumnAndRow(3, $line, 'Disetujui oleh, ');
      $line += 5;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, '........................')->setCellValueByColumnAndRow(1, $line, '........................')->setCellValueByColumnAndRow(2, $line, '........................')->setCellValueByColumnAndRow(3, $line, '........................');
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Admin')->setCellValueByColumnAndRow(1, $line, 'Supervisor')->setCellValueByColumnAndRow(2, $line, 'Chief Accounting')->setCellValueByColumnAndRow(3, $line, 'Manager Accounting');
      $line++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}
