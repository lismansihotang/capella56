<?php
class SfheaderController extends Controller {
  public $menuname = 'sfheader';
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
  public function actionIndextaxsf() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchtaxsf();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
			$id = rand(-1, -1000000000);
			echo CJSON::encode(array(
				'sfheaderid' => $id,
			));
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
				and t.recordstatus in (".getUserRecordStatus('listsf').")
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
				and t.recordstatus in (".getUserRecordStatus('listsf').")
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
	public function actionGetTahunJalan() {
		$data=array();
		$sql = "
			SELECT SUM(jumlah) AS jan
			FROM 
			(
			SELECT a.sfno,getamountbyso(a.sfheaderid) AS jumlah
			FROM sfheader a
			WHERE a.sfno IS NOT NULL
			AND a.recordstatus >= 3
			AND a.sfheaderid IN
			(
			SELECT distinct z.sfheaderid 
			FROM sfdetail z
			WHERE month(z.delvdate) = 1 AND YEAR(z.delvdate) = YEAR(NOW())
			)
			AND a.plantid IN (1)
			) z
		";
		$jan = Yii::app()->db->createCommand($sql)->queryScalar();
		$sql = "
			SELECT SUM(jumlah) AS jan
			FROM 
			(
			SELECT a.sfno,getamountbyso(a.sfheaderid) AS jumlah
			FROM sfheader a
			WHERE a.sfno IS NOT NULL
			AND a.recordstatus >= 3
			AND a.sfheaderid IN
			(
			SELECT distinct z.sfheaderid 
			FROM sfdetail z
			WHERE month(z.delvdate) = 2 AND YEAR(z.delvdate) = YEAR(NOW())
			)
			AND a.plantid IN (1)
			) z
		";
		$feb = Yii::app()->db->createCommand($sql)->queryScalar();
		$data = array($jan,$feb);
		echo CJSON::encode($data);
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'sfdetailid';
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
			->from('sfdetail t')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')
			->where('t.sfheaderid = :sfheaderid',
			array(
				':sfheaderid' => $id
			))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
					->select('t.*,a.productcode,a.productname,e.materialtypecode,
						getamountdetailbysf(t.sfheaderid,t.sfdetailid) as totprice,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
						getstdqty2(a.productid) as stdqty2
						')
					->from('sfdetail t')
					->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')
					->where('t.sfheaderid = :sfheaderid', array(
		':sfheaderid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'sfdetailid' => $data['sfdetailid'],
        'sfheaderid' => $data['sfheaderid'],
        'productid' => $data['productid'],
				'productcode' => $data['productcode'],
				'productname' => $data['productname'],
				'materialtypecode' => $data['materialtypecode'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'stdqty2' => Yii::app()->format->formatNumber($data['stdqty2']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'qty2' => Yii::app()->format->formatNumber($data['qty2']),
        'uom2id' => $data['uom2id'],
        'uomcode' => $data['uomcode'],
        'uom2code' => $data['uom2code'],
				'price' => Yii::app()->format->formatCurrency($data['price']),
				'totprice' => Yii::app()->format->formatCurrency($data['totprice']),
				'itemnote' => $data['itemnote']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		$sql = "select getamountbyso(t.sfheaderid) as total from sfheader t where sfheaderid = ".$id;
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
  public function actionSearchTaxsf() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'taxsfid';
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
					->from('taxsf t')
					->leftjoin('tax a', 'a.taxid = t.taxid')
					->where('t.sfheaderid = :sfheaderid',
					array(
						':sfheaderid' => $id
					))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
					->select('t.*,a.taxcode,a.taxvalue')
					->from('taxsf t')
					->leftjoin('tax a', 'a.taxid = t.taxid')
					->where('t.sfheaderid = :sfheaderid', array(
		':sfheaderid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'taxsfid' => $data['taxsfid'],
        'sfheaderid' => $data['sfheaderid'],
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
        $sql     = 'call RejectSo(:vid,:vcreatedby)';
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
        $sql     = 'call ApproveSf(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
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
        $sql     = 'call HoldSo(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
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
        $sql     = 'call OpenSo(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
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
        $sql     = 'call CloseSo(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  private function ModifyData($connection,$arraydata) {
		$id = (int)$arraydata[0];
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$sql = 'call Modifsfheader(:vid,:vsfdate,:vplantid,:vaddressbookid,:vpocustno,:vsalesid,
							:vpaymentmethodid,:vaddresstoid,:vaddresspayid,:vcurrencyid,:vcurrencyrate,:visexport,:vissample,
							:visavalan,
							:vheadernote,:vcreatedby)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vsfdate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vpocustno', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vsalesid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vpaymentmethodid', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vaddresstoid', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vaddresspayid', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyrate', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':visexport', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vissample', $arraydata[12], PDO::PARAM_STR);
		$command->bindvalue(':visavalan', $arraydata[13], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[14], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();					
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-sfheader"]["name"]);
		if (move_uploaded_file($_FILES["file-sfheader"]["tmp_name"], $target_file)) {
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
							$sfdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(2, $row)->getValue())); //C
							$customer = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue(); //D
							$customerid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '".$customer."'")->queryScalar();
							$pocustno = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue(); //E
							$addressto = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue(); //F
							$addresstoid = Yii::app()->db->createCommand("select addressid from address where addressname = '".$addressto."'")->queryScalar();
							$addresspay = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue(); //G
							$addresspayid = Yii::app()->db->createCommand("select addressid from address where addressname = '".$addresspay."'")->queryScalar();
							$sales = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue(); //H
							$salesid = Yii::app()->db->createCommand("select salesid from sales a 
								join employee b on b.employeeid = a.employeeid 
								where b.fullname = '".$sales."' 
								and a.plantid = ".$plantid)->queryScalar();
							$paycode = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue(); //I
							$payid = Yii::app()->db->createCommand("select paymentmethodid from paymentmethod where paycode = '".$paycode."'")->queryScalar();
							$symbol = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue(); //J
							$currencyid = Yii::app()->db->createCommand("select currencyid from currency where symbol = '".$symbol."'")->queryScalar();
							$currencyrate = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue(); //K 
							$isexport = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue(); //L
							$issample = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue(); //M
							$isavalan = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue(); //N 
							$description = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue(); //O
							$this->ModifyData($connection,array(-1,
								$sfdate,$plantid,$customerid,$pocustno,
								$salesid,$payid,$addresstoid,$addresspayid,
								$currencyid,$currencyrate,$isexport,$issample,$isavalan,$description));
							$pid = Yii::app()->db->createCommand("
								select sfheaderid 
								from sfheader a
								where a.plantid = ".$plantid." 
								and a.sfdate = '".$sfdate."' 
								and a.addressbookid = ".$customerid." 
								and a.pocustno = '".$pocustno."' 
								and a.salesid = ".$salesid." 
								limit 1
							")->queryScalar();
							$taxcode = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue(); //P
							$taxid = Yii::app()->db->createCommand("select taxid from tax where taxcode = '".$taxcode."'")->queryScalar();
							$this->Modifytaxsf($connection,array('',
								$pid,
								$taxid
							));
						} 
						$productname = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue(); //Q
						$sql = "select productid from product where productname = :productname";
						$command=$connection->createCommand($sql);
						$command->bindvalue(':productname',$productname,PDO::PARAM_STR);
						$productid = $command->queryScalar();
						$qty = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue(); //R
						$uomcode = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue(); //S
						$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$qty2 = $objWorksheet->getCellByColumnAndRow(19, $row)->getValue(); //T
						$uomcode = $objWorksheet->getCellByColumnAndRow(20, $row)->getValue(); //U
						$uom2id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$price = $objWorksheet->getCellByColumnAndRow(21, $row)->getValue(); //V
						$sql = "select bomid from billofmaterial where bomversion = :bomversion and productid = :productid and plantid = :plantid";
						$command=$connection->createCommand($sql);
						$command->bindvalue(':bomversion',$bomversion,PDO::PARAM_STR);
						$command->bindvalue(':productid',$productid,PDO::PARAM_STR);
						$command->bindvalue(':plantid',$plantid,PDO::PARAM_STR);
						$bomid = $command->queryScalar();
						$itemnote = $objWorksheet->getCellByColumnAndRow(26, $row)->getValue(); //Z
						$this->ModifyDetail($connection,array(
							'',
							$pid,
							$productid,
							$qty,
							$uomid,
							$qty2,
							$uom2id,
							$price,
							$itemnote
						));
					}
				}
				$transaction->commit();
				GetMessage(false, getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollBack();
				GetMessage(true, 'Line: '.$row.' ==> '.$e->getMessage());
			}
    }
	}
	public function actionSave() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['sfheader-sfheaderid'])?$_POST['sfheader-sfheaderid']:''),
				date(Yii::app()->params['datetodb'], strtotime($_POST['sfheader-sfdate'])),
				$_POST['sfheader-plantid'],$_POST['sfheader-addressbookid'],$_POST['sfheader-pocustno'],
				$_POST['sfheader-salesid'],$_POST['sfheader-paymentmethodid'],
				$_POST['sfheader-addresstoid'],$_POST['sfheader-addresspayid'],$_POST['sfheader-currencyid'],$_POST['sfheader-currencyrate'],
				(isset($_POST['sfheader-isexport']) ? 1 : 0),(isset($_POST['sfheader-issample']) ? 1 : 0),
				(isset($_POST['sfheader-isavalan']) ? 1 : 0),$_POST['sfheader-headernote']));
			$transaction->commit();
			GetMessage(false, getcatalog('insertsuccess'));
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true, $e->getMessage());
		}
  }
	private function ModifyDetail($connection,$arraydata){
		if ($arraydata[0] == '') {
			$sql     = 'call InsertSfdetail(:vsfheaderid,:vproductid,:vqty,:vuomid,:vqty2,:vuom2id,
					:vprice,:vitemnote,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call UpdateSfdetail(:vid,:vsfheaderid,:vproductid,:vqty,:vuomid,:vqty2,:vuom2id,
					:vprice,:vitemnote,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vsfheaderid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vqty', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vqty2', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vuom2id', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vprice', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vitemnote', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
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
				(isset($_POST['sfdetailid'])?$_POST['sfdetailid']:''),
				$_POST['sfheaderid'],
				$_POST['productid'],
				$_POST['qty'],
				$_POST['uomid'],
				$_POST['qty2'],
				$_POST['uom2id'],
				$_POST['price'],
				$_POST['itemnote']
			));
      $transaction->commit();
      GetMessage(false, getcatalog('insertsuccess'));
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
	private function Modifytaxsf($connection,$arraydata) {
		if ($arraydata[0] == '') {
			$sql     = 'call InsertTaxsf(:vsfheaderid,:vtaxid,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call UpdateTaxsf(:vid,:vsfheaderid,:vtaxid,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vsfheaderid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vtaxid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavetaxsf() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->Modifytaxsf($connection,array(
				(isset($_POST['taxsfid'])?$_POST['taxsfid']:''),
				$_POST['sfheaderid'],
				$_POST['taxid'],
			));
      $transaction->commit();
      GetMessage(false, getcatalog('insertsuccess'));
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
  public function actionPurge() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call PurgeSOheader(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
          $command->bindvalue(':vid', $id, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
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
        $sql     = 'call Purgesfdetail(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
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
        $sql     = 'call Purgetaxsf(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, 'chooseone');
    }
  }
	public function actionGeneratepocustno() {
		$sql = "select a.pocustno,b.invoiceartaxno
			from sfheader a 
			left join invoicear b on b.sfheaderid=a.sfheaderid
			where a.sfheaderid = ".$_POST['id']." 
			";
		$data = Yii::app()->db->createCommand($sql)->queryRow();
    if (Yii::app()->request->isAjaxRequest) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = '';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->execute();
        $transaction->commit();
      }
      catch (Exception $e) {
        $transaction->rollBack();
      }
      echo CJSON::encode(array(
        'pocustno' => $data['pocustno'],
        'invoiceartaxno' => $data['invoiceartaxno'],
      ));
      Yii::app()->end();
    }
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select g.companyid, a.sfheaderid,a.sfno, b.fullname as customername, a.sfdate, c.paymentname, h.phoneno, 
			a.addressbookid, a.headernote,a.recordstatus,h.addressname,i.addressname as addresspayname,j.fullname as salesname,
			a.currencyrate,k.symbol
      from sfheader a
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
      $sql = $sql . "where a.sfheaderid in (" . $_GET['id'] . ")";
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
        '',
        'Sales Order No',
        ' : ' . $row['sfno']
      ));
      $this->pdf->row(array(
        'Name',
        ' : ' . $row['customername'],
        'SO Date',
        ' : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['sfdate']))
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
      $sql1        = "select a.sfheaderid,c.uomcode,a.qty,a.price * ".$row['currencyrate']." as price,(qty * price * ".$row['currencyrate'].") as total,b.productname,
			a.itemnote
			from sfdetail a
			left join sfheader f on f.sfheaderid = a.sfheaderid 
			left join product b on b.productid = a.productid
			left join unitofmeasure c on c.unitofmeasureid = a.uomid
			where a.sfheaderid = " . $row['sfheaderid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $total       = 0;
      $totalqty    = 0;
      $this->pdf->sety($this->pdf->gety() +0);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C'
       
      );
      $this->pdf->setwidths(array(
        15,
        15,
        110,
        30
       
      ));
      $this->pdf->colheader = array(
        'Qty',
        'Units',
        'Description',
        'Item Note'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'R',
        'C',
        'L',
        'L',
        'R'
        
      );
      foreach ($dataReader1 as $row1) {
        $this->pdf->row(array(
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          $row1['productname'],
          $row1['itemnote']
         
        ));
        $total    = $row1['total'] + $total;
        $totalqty = $row1['qty'] + $totalqty;
      }
      $this->pdf->row(array(
        Yii::app()->format->formatNumber($totalqty),
        '',
        'Total',
        ''
        
      ));
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->colalign = array(
        'C',
        'C',
      );
      $this->pdf->setwidths(array(
        35,
        200,
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
		$sfheaderid 		= GetSearchText(array('POST','GET','Q'),'sfheaderid');
		$plantcode     		= GetSearchText(array('POST','GET','Q'),'plantcode');
		$productname   		= GetSearchText(array('POST','GET','Q'),'productname');
		$sfdate    = GetSearchText(array('POST','GET','Q'),'sfdate');
		$sfno 		= GetSearchText(array('POST','GET','Q'),'sfno');
		$fullname      = GetSearchText(array('POST','GET','Q'),'fullname');
		$pocustno      = GetSearchText(array('POST','GET','Q'),'pocustno');
		$headernote 		= GetSearchText(array('POST','GET','Q'),'headernote');
		$customer 		= GetSearchText(array('POST','GET','Q'),'customer');
		$sql = "select g.companyid, a.sfheaderid,a.sfno, b.fullname as customername, a.sfdate, c.paycode, h.phoneno, 
			a.addressbookid, a.headernote,a.recordstatus,h.addressname,i.addressname as addresspayname,j.fullname as salesname,
			a.currencyrate,k.symbol,f.plantcode,a.pocustno,a.isexport,a.isavalan,a.issample,s.taxcode,m.productname,n.uomcode,
			o.uomcode as uom2code,l.qty,l.qty2,l.price,l.itemnote
      from sfheader a
      left join addressbook b on b.addressbookid = a.addressbookid
		  left join sales d on d.salesid = a.salesid
			left join employee j on j.employeeid = d.employeeid 
      left join paymentmethod c on c.paymentmethodid = a.paymentmethodid
			left join plant f on f.plantid = a.plantid 
			left join company g on g.companyid = f.companyid 
			left join address h on h.addressid = a.addresstoid
			left join address i on i.addressid = a.addresspayid
			left join currency k on k.currencyid = a.currencyid 
			left join sfdetail l on l.sfheaderid = a.sfheaderid 
			left join product m on m.productid = l.productid 
			left join unitofmeasure n on n.unitofmeasureid = l.uomid 
			left join unitofmeasure o on o.unitofmeasureid = l.uom2id 
			left join taxsf r on r.sfheaderid = a.sfheaderid 
			left join tax s on s.taxid = r.taxid 
			";    
		$sql .= " where coalesce(a.sfheaderid,'') like '".$sfheaderid."' 
			and coalesce(f.plantcode,'') like '".$plantcode."' 
			and coalesce(a.sfdate,'') like '".$sfdate."' 
			and coalesce(b.fullname,'') like '".$customer."' 
			and coalesce(a.pocustno,'') like '".$pocustno."' 
			and coalesce(a.headernote,'') like '".$headernote."' 
			and coalesce(a.sfno,'') like '".$sfno."'".
			(($productname != '%%')?"
				and coalesce(m.productname,'') like '".$productname."'
			":'')
		;
		if ($_GET['id'] !== '') {
      $sql = $sql . " and a.sfheaderid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 2;$oldtax = '';
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $line, $line-1)
				->setCellValueByColumnAndRow(1, $line, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $line, date(Yii::app()->params['dateviewfromdb'], strtotime($row['sfdate'])))
				->setCellValueByColumnAndRow(3, $line, $row['customername'])
				->setCellValueByColumnAndRow(4, $line, $row['pocustno'])
				->setCellValueByColumnAndRow(5, $line, $row['addressname'])
				->setCellValueByColumnAndRow(6, $line, $row['addresspayname'])
				->setCellValueByColumnAndRow(7, $line, $row['salesname'])
				->setCellValueByColumnAndRow(8, $line, $row['paycode'])
				->setCellValueByColumnAndRow(9, $line, $row['symbol'])
				->setCellValueByColumnAndRow(10, $line, $row['currencyrate'])
				->setCellValueByColumnAndRow(11, $line, $row['isexport'])
				->setCellValueByColumnAndRow(12, $line, $row['issample'])
				->setCellValueByColumnAndRow(13, $line, $row['isavalan'])
				->setCellValueByColumnAndRow(14, $line, $row['headernote'])
				->setCellValueByColumnAndRow(15, $line, $row['taxcode'])
				->setCellValueByColumnAndRow(16, $line, $row['productname'])
				->setCellValueByColumnAndRow(17, $line, Yii::app()->format->formatNumber($row['qty']))
				->setCellValueByColumnAndRow(18, $line, $row['uomcode'])
				->setCellValueByColumnAndRow(19, $line, Yii::app()->format->formatNumber($row['qty2']))
				->setCellValueByColumnAndRow(20, $line, $row['uom2code'])
				->setCellValueByColumnAndRow(21, $line, $row['price'])
				->setCellValueByColumnAndRow(22, $line, $row['itemnote'])
			;
			$line++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}