<?php
class ProductoutputController extends Controller {
  public $menuname = 'productoutput';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexfg() {
    if (isset($_GET['grid']))
      echo $this->actionSearchfg();
    else
      $this->renderPartial('index', array());
  }
	 public function actionIndexfgoperator() {
    if (isset($_GET['grid']))
      echo $this->searchfgoperator();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail() {
    if (isset($_GET['grid']))
      echo $this->actionSearchdetail();
    else
      $this->renderPartial('index', array());
  }
	public function actionIndexwaste() {
    if (isset($_GET['grid']))
      echo $this->actionSearchwaste();
    else
      $this->renderPartial('index', array());
  }
	public function actionIndexoperator() {
    if (isset($_GET['grid']))
      echo $this->actionSearchoperator();
    else
      $this->renderPartial('index', array());
  }
	public function actionIndexjurnal() {
    if (isset($_GET['grid']))
      echo $this->actionSearchjurnal();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
			$id = rand(-1, -1000000000);
			echo CJSON::encode(array(
				'productoutputid' => $id,
			));
  }
	public function actionGeneratedetail() {
    if (isset($_POST['id'])) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GeneratePPOP(:vid,:vhid,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
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
    Yii::app()->end();
  }
  public function actionGenerateplan()
  {
    if (Yii::app()->request->isAjaxRequest) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateOPPP(:vid, :vhid, :vdatauser)';
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
  }
	public function searchfgoperator() {
		header('Content-Type: application/json');			
		$productoutputfgid = GetSearchText(array('GET'),'productoutputfgid',0,'int');
		$productname = GetSearchText(array('Q'),'productname');
		$namamesin = GetSearchText(array('Q'),'namamesin');
		$sloccode = GetSearchText(array('Q'),'sloccode');
		$fullname = GetSearchText(array('Q'),'fullname');
		$page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','productoutputfgid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
		$offset = ($page-1) * $rows;			
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = '
			from productoutputfg t
					left join productoutput g on g.productoutputid = t.productoutputid
					left join product a on a.productid = t.productid
					left join mesin c on c.mesinid = t.mesinid
					left join processprd d on d.processprdid = t.processprdid
					left join sloc e on e.slocid = t.slocid
					left join employee f on f.employeeid = t.employeeid
					left join storagebin h on h.storagebinid = t.storagebinid';
		$where = "
			where ((coalesce(a.productname,'') like '".$productname."') or (coalesce(c.namamesin,'') like '".$namamesin."') 
			or (coalesce(e.sloccode,'') like '".$sloccode."') or (coalesce(f.fullname,'') like '".$fullname."'))";
		(($productoutputfgid!='')? $where.=" and t.productoutputfgid = ".$productoutputfgid:'');		
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.productoutputid,t.productoutputfgid,t.productoutputid,a.productname,c.namamesin,e.sloccode,f.fullname '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order)->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
				'productoutputfgid'=>$data['productoutputfgid'],
				'productoutputid'=>$data['productoutputid'],
				'productname'=>$data['productname'],
				'namamesin'=>$data['namamesin'],
				'sloccode'=>$data['sloccode'],
				'fullname'=>$data['fullname'],
				
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
  public function search() {
    header('Content-Type: application/json');
    $productoutputid 				= GetSearchText(array('POST','Q'),'productoutputid','','int');
    $productoutputfgid 				= GetSearchText(array('POST','GET'),'productoutputfgid',0,'int');
    $productoutputdetailid 				= GetSearchText(array('POST','GET'),'productoutputdetailid',0,'int');
    $plantid 				= GetSearchText(array('POST','GET'),'plantid',0,'int');
    $addressbookid 				= GetSearchText(array('POST','GET'),'addressbookid',0,'int');
    $productplanid 				= GetSearchText(array('POST','GET'),'productplanid',0,'int');
    $productplanno 				= GetSearchText(array('POST','Q'),'productplanno');
    $soheaderid 				= GetSearchText(array('POST','GET'),'soheaderid',0,'int');
    $sono 				= GetSearchText(array('POST','Q'),'sono');
    $customer 				= GetSearchText(array('POST','Q'),'customer');
    $plantcode 				= GetSearchText(array('POST','Q'),'plantcode');
    $sloccode 				= GetSearchText(array('POST','Q'),'sloccode');
    $productoutputdate 				= GetSearchText(array('POST','Q'),'productoutputdate');
    $productoutputno 				= GetSearchText(array('POST','Q'),'productoutputno');
    $headernote 				= GetSearchText(array('POST','Q'),'headernote');
    $recordstatus 				= GetSearchText(array('POST','Q'),'recordstatus');
    $processprdname 				= GetSearchText(array('POST','Q'),'processprdname');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','productoutputid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
	if (!isset($_GET['getdata'])) {
    if (isset($_GET['opts'])) {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('productoutput t')
				->leftjoin('soheader a', 'a.soheaderid = t.soheaderid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
				->leftjoin('productplan e', 'e.productplanid= t.productplanid')
				->where("
				((coalesce(productoutputid,'') like :productoutputid) 
				or (coalesce(b.plantcode,'') like :plantcode) 
				or (coalesce(e.productplanno,'') like :productplanno) 
				or (coalesce(t.productoutputno,'') like :productoutputno) 
				or (coalesce(t.headernote,'') like :headernote) 
				or (coalesce(a.sono,'') like :sono) 
				or (coalesce(d.fullname,'') like :customer) 
				or (coalesce(productoutputdate,'') like :productoutputdate)) 
				and t.recordstatus = 3
				and t.plantid in (".getUserObjectValues('plant').")
				and t.plantid = ".$plantid." 
				and t.productoutputid in (select zz.productoutputid 
					from productoutputfg zz 
					where zz.ists = 1)"
					,
				array(
        ':productoutputid' => '%' . $productoutputid . '%',
				':plantcode' => '%' . $plantcode. '%',
				':customer' => '%' . $customer . '%',
				':productoutputno' => '%' . $productoutputno . '%',
				':headernote' => '%' . $headernote . '%',
				':sono' => '%' . $sono . '%',
				':productplanno' => '%' . $productplanno . '%',
				':productoutputdate' => '%' . $productoutputdate . '%'
				))->queryScalar();
			}
    else {
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
				and (coalesce(productoutputdate,'') like :productoutputdate)) 
				and t.recordstatus in (".getUserRecordStatus('listop').") 
				and t.plantid in (".getUserObjectValues('plant').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				" and t.productoutputid in (
					select zz.productoutputid 
					from productoutputfg zz
					where slocid in (".getUserObjectValues('sloc').")
				)
				".
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
    }
    $result['total'] = $cmd;
    if (isset($_GET['opts'])) {
			$cmd = Yii::app()->db->createCommand()->select('t.*,a.sono,b.plantcode,b.companyid,c.companyname,d.fullname')
				->from('productoutput t')
				->leftjoin('soheader a', 'a.soheaderid = t.soheaderid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
				->leftjoin('productplan e', 'e.productplanid= t.productplanid')
				->where("
				((coalesce(productoutputid,'') like :productoutputid) 
				or (coalesce(b.plantcode,'') like :plantcode) 
				or (coalesce(e.productplanno,'') like :productplanno) 
				or (coalesce(productoutputno,'') like :productoutputno) 
				or (coalesce(t.headernote,'') like :headernote) 
				or (coalesce(a.sono,'') like :sono) 
				or (coalesce(fullname,'') like :customer) 
				or (coalesce(productoutputdate,'') like :productoutputdate))  
				and t.productoutputno is not null 
				and t.recordstatus = 3
				and t.plantid in (".getUserObjectValues('plant').")
				and t.plantid = ".$plantid." 
				and t.productoutputid in (select zz.productoutputid 
					from productoutputfg zz 
					where zz.ists = 1)
				",
				array(
        ':productoutputid' => '%' . $productoutputid . '%',
				':plantcode' => '%' . $plantcode. '%',
				':customer' => '%' . $customer . '%',
				':productoutputno' => '%' . $productoutputno . '%',
				':headernote' => '%' . $headernote . '%',
				':sono' => '%' . $sono . '%',
				':productplanno' => '%' . $productplanno . '%',
				':productoutputdate' => '%' . $productoutputdate . '%'
				))->order($sort . ' ' . $order)->queryAll();
		} else {
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
				and (coalesce(productoutputdate,'') like :productoutputdate)) 
				and t.recordstatus in (".getUserRecordStatus('listop').") 
				and t.plantid in (".getUserObjectValues('plant').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				" and t.productoutputid in (
					select zz.productoutputid 
					from productoutputfg zz
					where slocid in (".getUserObjectValues('sloc').")
				)
				".
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
		}
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
	} else {
			$cmd = Yii::app()->db->createCommand("
				select a.productoutputid,a.productplanid,a.soheaderid,a.addressbookid,a.headernote,b.slocid as slocfromid,b.mesinid,e.sono,d.fullname,a.productplanno
				from  productoutput a
				join productoutputfg b on b.productoutputid = a.productoutputid
				left join addressbook d on d.addressbookid = a.addressbookid 
				left join soheader e on e.soheaderid = a.soheaderid
				where a.productoutputid = ".$productoutputid)->queryRow();
			$result = $cmd;
		}
    return CJSON::encode($result);
	}
  public function actionSearchfg() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    } else if (isset($_GET['productoutputid'])) {
      $id = $_GET['productoutputid'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'productoutputfgid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
		if (!isset($_GET['combo'])) {
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('productoutputfg t')
			->leftjoin('productoutput g', 'g.productoutputid = t.productoutputid')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('mesin c', 'c.mesinid = t.mesinid')
			->leftjoin('processprd d', 'd.processprdid = t.processprdid')
			->leftjoin('sloc e', 'e.slocid = t.slocid')
			->leftjoin('employee f', 'f.employeeid = t.employeeid')
			->leftjoin('storagebin h', 'h.storagebinid = t.storagebinid')
			->leftjoin('materialtype i', 'i.materialtypeid = a.materialtypeid')
			->where('t.productoutputid = :productoutputid',
			array(
				':productoutputid' => $id
			))->queryScalar();
		} else {
			$cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('productoutputfg t')
			->leftjoin('productoutput g', 'g.productoutputid = t.productoutputid')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('mesin c', 'c.mesinid = t.mesinid')
			->leftjoin('processprd d', 'd.processprdid = t.processprdid')
			->leftjoin('sloc e', 'e.slocid = t.slocid')
			->leftjoin('employee f', 'f.employeeid = t.employeeid')
			->leftjoin('storagebin h', 'h.storagebinid = t.storagebinid')
			->leftjoin('materialtype i', 'i.materialtypeid = a.materialtypeid')
			->where('t.productoutputid = :productoutputid',
			array(
				':productoutputid' => $id
			))->queryScalar();
		}
    $result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
    $cmd             = Yii::app()->db->createCommand()
			->select('t.*,a.productcode,a.productname,d.processprdname,e.sloccode,c.kodemesin,f.fullname,h.description as storagebinname,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,i.materialtypecode,a.materialtypeid,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code,
				GetStdQty(a.productid) as stdqty,
				GetStdQty2(a.productid) as stdqty2,
				GetStdQty3(a.productid) as stdqty3,
				GetStdQty4(a.productid) as stdqty4,
				GetStock(a.productid,t.uomid,e.slocid) as qtystock,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom4id) as uom4code')
			->from('productoutputfg t')
			->leftjoin('productoutput g', 'g.productoutputid = t.productoutputid')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('mesin c', 'c.mesinid = t.mesinid')
			->leftjoin('processprd d', 'd.processprdid = t.processprdid')
			->leftjoin('sloc e', 'e.slocid = t.slocid')
			->leftjoin('employee f', 'f.employeeid = t.employeeid')
			->leftjoin('storagebin h', 'h.storagebinid = t.storagebinid')
			->leftjoin('materialtype i', 'i.materialtypeid = a.materialtypeid')
			->where('t.productoutputid = :productoutputid', array(
				':productoutputid' => $id
			))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		} else {
			$cmd             = Yii::app()->db->createCommand()
			->select('t.*,a.productcode,a.productname,d.processprdname,e.sloccode,c.kodemesin,f.fullname,h.description as storagebinname,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,i.materialtypecode,a.materialtypeid,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code,
				GetStdQty(a.productid) as stdqty,
				GetStdQty2(a.productid) as stdqty2,
				GetStdQty3(a.productid) as stdqty3,
				GetStdQty4(a.productid) as stdqty4,
				GetStock(a.productid,t.uomid,e.slocid) as qtystock,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom4id) as uom4code')
			->from('productoutputfg t')
			->leftjoin('productoutput g', 'g.productoutputid = t.productoutputid')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('mesin c', 'c.mesinid = t.mesinid')
			->leftjoin('processprd d', 'd.processprdid = t.processprdid')
			->leftjoin('sloc e', 'e.slocid = t.slocid')
			->leftjoin('employee f', 'f.employeeid = t.employeeid')
			->leftjoin('storagebin h', 'h.storagebinid = t.storagebinid')
			->leftjoin('materialtype i', 'i.materialtypeid = a.materialtypeid')
			->where('t.productoutputid = :productoutputid', array(
				':productoutputid' => $id
			))->order($sort . ' ' . $order)->queryAll();
		}
		foreach ($cmd as $data) {
			if ($data['qtystock'] > $data['qty']) {
				$stockcount = 0;
			} else {
				$stockcount = 1;
			}
      $row[] = array(
        'productoutputfgid' => $data['productoutputfgid'],
        'productoutputid' => $data['productoutputid'],
        'productplanfgid' => $data['productplanfgid'],
        'productid' => $data['productid'],
				'productcode' => $data['productcode'],
				'productname' => $data['productname'],
				'qty' => Yii::app()->format->formatNumber($data['qty']),
				'qty2' => Yii::app()->format->formatNumber($data['qty2']),
				'qty3' => Yii::app()->format->formatNumber($data['qty3']),
				'qty4' => Yii::app()->format->formatNumber($data['qty4']),
				'stdqty' => Yii::app()->format->formatNumber($data['stdqty']),
				'stdqty2' => Yii::app()->format->formatNumber($data['stdqty2']),
				'stdqty3' => Yii::app()->format->formatNumber($data['stdqty3']),
				'stdqty4' => Yii::app()->format->formatNumber($data['stdqty4']),
				'qtystock' => Yii::app()->format->formatNumber($data['qtystock']),
        'uomid' => $data['uomid'],
        'stockcount' => $stockcount,
				'uom2id' => $data['uom2id'],
				'uom3id' => $data['uom3id'],
				'uom4id' => $data['uom4id'],
				'uomcode' => $data['uomcode'],
				'uom2code' => $data['uom2code'],
				'uom3code' => $data['uom3code'],
				'uom4code' => $data['uom4code'],
				'mesinid' => $data['mesinid'],
				'namamesin' => $data['kodemesin'],
				'materialtypeid' => $data['materialtypeid'],
				'materialtypecode' => $data['materialtypecode'],
				'processprdid' => $data['processprdid'],
				'processprdname' => $data['processprdname'],
				'slocid' => $data['slocid'],
				'sloccode' => $data['sloccode'],
				'storagebinid' => $data['storagebinid'],
				'storagebinname' => $data['storagebinname'],
				'employeeid' => $data['employeeid'],
				'fullname' => $data['fullname'],
			 'shift' => Yii::app()->format->formatNumber($data['shift']),
			 'efektivitas' => Yii::app()->format->formatNumber($data['efektivitas']),
			 'angkatan' => Yii::app()->format->formatNumber($data['angkatan']),
			'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'productoutputdetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('productoutputdetail t')
					->leftjoin('productoutput g', 'g.productoutputid = t.productoutputid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('billofmaterial c', 'c.bomid = t.bomid')
					->leftjoin('sloc d', 'd.slocid = t.slocfromid')
					->leftjoin('sloc e', 'e.slocid = t.sloctoid')
					->leftjoin('storagebin f', 'f.storagebinid = t.storagebintoid')
					->leftjoin('materialtype i', 'i.materialtypeid = a.materialtypeid')
					->where('t.productoutputid = :productoutputid',
					array(
				':productoutputid' => $id
				))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
			->select('t.*,a.productcode,a.productname,c.bomversion,d.sloccode as slocfromcode, e.sloccode as sloctocode,f.description as storagebinto,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,i.materialtypecode,a.materialtypeid,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code,
				GetStdQty(a.productid) as stdqty,
				GetStdQty2(a.productid) as stdqty2,
				GetStdQty3(a.productid) as stdqty3,
				GetStdQty4(a.productid) as stdqty4,
				GetStock(a.productid,t.uomid,t.sloctoid) as qtystock,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom4id) as uom4code')
			->from('productoutputdetail t')
			->leftjoin('productoutput g', 'g.productoutputid = t.productoutputid')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('billofmaterial c', 'c.bomid = t.bomid')
			->leftjoin('sloc d', 'd.slocid = t.slocfromid')
			->leftjoin('sloc e', 'e.slocid = t.sloctoid')
			->leftjoin('storagebin f', 'f.storagebinid = t.storagebintoid')
			->leftjoin('materialtype i', 'i.materialtypeid = a.materialtypeid')
			->where('t.productoutputid = :productoutputid', array(
				':productoutputid' => $id
			))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
	foreach ($cmd as $data) {
		if ($data['qtystock'] > $data['qty']) {
				$stockcount = 0;
			} else {
				$stockcount = 1;
			}
		$row[] = array(
			'productoutputdetailid' => $data['productoutputdetailid'],
			'productoutputid' => $data['productoutputid'],
			'productoutputfgid' => $data['productoutputfgid'],
			'productplanfgid' => $data['productplanfgid'],
			'productid' => $data['productid'],
			'productcode' => $data['productcode'],
			'productname' => $data['productname'],
			'qty' => Yii::app()->format->formatNumber($data['qty']),
			'qty2' => Yii::app()->format->formatNumber($data['qty2']),
			'qty3' => Yii::app()->format->formatNumber($data['qty3']),
			'qty4' => Yii::app()->format->formatNumber($data['qty4']),
			'stdqty' => Yii::app()->format->formatNumber($data['stdqty']),
			'stdqty2' => Yii::app()->format->formatNumber($data['stdqty2']),
			'stdqty3' => Yii::app()->format->formatNumber($data['stdqty3']),
			'stdqty4' => Yii::app()->format->formatNumber($data['stdqty4']),
			'qtystock' => Yii::app()->format->formatNumber($data['qtystock']),
			'uomid' => $data['uomid'],
			'uom2id' => $data['uom2id'],
			'uom3id' => $data['uom3id'],
			'uom4id' => $data['uom4id'],
			'uomcode' => $data['uomcode'],
			'uom2code' => $data['uom2code'],
			'uom3code' => $data['uom3code'],
			'uom4code' => $data['uom4code'],
			'materialtypeid' => $data['materialtypeid'],
			'materialtypecode' => $data['materialtypecode'],
			'slocfromid' => $data['slocfromid'],
			'slocfromcode' => $data['slocfromcode'],
			'sloctoid' => $data['sloctoid'],
			'sloctocode' => $data['sloctocode'],
			'storagebintoid' => $data['storagebintoid'],
			'storagebinto' => $data['storagebinto'],
			'bomid' => $data['bomid'],
			'bomversion' => $data['bomversion'],
			'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
	public function actionSearchwaste() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'productoutputwasteid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('productoutputwaste t')
					->leftjoin('productoutput g', 'g.productoutputid = t.productoutputid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('sloc d', 'd.slocid = t.slocid')
					->leftjoin('storagebin f', 'f.storagebinid = t.storagebinid')
					->leftjoin('materialtype i', 'i.materialtypeid = a.materialtypeid')
					->where('t.productoutputid = :productoutputid',
					array(
				':productoutputid' => $id
				))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
			->select('t.*,a.productname,d.sloccode,i.materialtypecode,f.description as storagebinname,a.materialtypeid,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom4id) as uom4code')
			->from('productoutputwaste t')
			->leftjoin('productoutput g', 'g.productoutputid = t.productoutputid')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('sloc d', 'd.slocid = t.slocid')
			->leftjoin('storagebin f', 'f.storagebinid = t.storagebinid')
			->leftjoin('materialtype i', 'i.materialtypeid = a.materialtypeid')
			->where('t.productoutputid = :productoutputid', array(
				':productoutputid' => $id
			))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
	foreach ($cmd as $data) {
		$row[] = array(
			'productoutputwasteid' => $data['productoutputwasteid'],
			'productoutputid' => $data['productoutputid'],
			'productoutputfgid' => $data['productoutputfgid'],
			'productid' => $data['productid'],
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
			'materialtypeid' => $data['materialtypeid'],
			'materialtypecode' => $data['materialtypecode'],
			'storagebinid' => $data['storagebinid'],
			'storagebinname' => $data['storagebinname'],
			'slocid' => $data['slocid'],
			'sloccode' => $data['sloccode'],
			'itemnote' => $data['itemnote']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
	public function actionSearchoperator() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'productoutputemployeeid';
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
					->from('productoutputemployee t')
					->leftjoin('employee a', 'a.employeeid = t.employeeid')
					->leftjoin('productoutputfg b', 'b.productoutputfgid = t.productoutputfgid')
					->leftjoin('product c', 'c.productid = b.productid')
					->where('t.productoutputid = :productoutputid',
					array(
						':productoutputid' => $id
					))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
					->select('t.*,a.oldnik,a.fullname,c.productname')
					->from('productoutputemployee t')
					->leftjoin('employee a', 'a.employeeid = t.employeeid')
					->leftjoin('productoutputfg b', 'b.productoutputfgid = t.productoutputfgid')
					->leftjoin('product c', 'c.productid = b.productid')
					->where('t.productoutputid = :productoutputid', array(
		':productoutputid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'productoutputemployeeid' => $data['productoutputemployeeid'],
        'productoutputid' => $data['productoutputid'],
        'productoutputfgid' => $data['productoutputfgid'],
        'productname' => $data['productname'],
        'employeeid' => $data['employeeid'],
				'oldnik' => $data['oldnik'],
				'fullname' => $data['fullname'],
				'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
	public function actionSearchjurnal() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'productoutputfgid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('productoutputjurnal t')
					->leftjoin('account a', 'a.accountid = t.accountid')
					->leftjoin('currency b', 'b.currencyid = t.currencyid')
					->leftjoin('product c', 'c.productid = t.productid')
					->where('t.productoutputid = :productoutputid',
					array(
						':productoutputid' => $id
					))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
					->select('t.*,a.accountname,b.currencyname,c.productname')
					->from('productoutputjurnal t')
					->leftjoin('account a', 'a.accountid = t.accountid')
					->leftjoin('currency b', 'b.currencyid = t.currencyid')
					->leftjoin('product c', 'c.productid = t.productid')
					->where('t.productoutputid = :productoutputid', array(
		':productoutputid' => $id
		))->offset($offset)->limit($rows)->order('productoutputfgid asc, debit desc')->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'productoutputjurnalid' => $data['productoutputjurnalid'],
        'productoutputfgid' => $data['productoutputfgid'],
        'productoutputid' => $data['productoutputid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'accountid' => $data['accountid'],
				'accountname' => $data['accountname'],
				'debit' => Yii::app()->format->formatNumber($data['debit']),
				'credit' => Yii::app()->format->formatNumber($data['credit']),
				'detailnote' => $data['detailnote']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		$sql = "select sum(debit) as totaldebit,sum(credit) as totalcredit from productoutputjurnal t where productoutputid = ".$id;
		$cmd = Yii::app()->db->createCommand($sql)->queryRow();
		$footer[] = array(
      'productname' => 'Total',
      'debit' => Yii::app()->format->formatNumber($cmd['totaldebit']),
      'credit' => Yii::app()->format->formatNumber($cmd['totalcredit'])
    );
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  private function ModifyData($connection,$arraydata) {
		$id = (int)$arraydata[0];
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$sql = 'call Modifproductoutput (:vid,:vproductoutputdate,:vplantid,:vproductplanid,:vsoheaderid,:vaddressbookid,:vheadernote,:vdatauser)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vproductoutputdate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vproductplanid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vsoheaderid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionSave() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['productoutput-productoutputid'])?$_POST['productoutput-productoutputid']:''),
			date(Yii::app()->params['datetodb'], strtotime($_POST['productoutput-productoutputdate'])),
			$_POST['productoutput-plantid'],$_POST['productoutput-productplanid'],$_POST['productoutput-soheaderid'],
			$_POST['productoutput-addressbookid'],$_POST['productoutput-headernote']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
  public function actionSavefg() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call InsertProductoutputfg (:vproductoutputid,:vproductid,:vqty,:vqty2,:vqty3,:vqty4,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vemployeeid,:vshift,
					:vprocessprdid,:vmesinid,:vslocid,vstoragebinid,:vefektivitas,:vangkatan,:vdescription,:vdatauser)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call UpdateProductoutputfg (:vid,:vproductoutputid,:vproductid,:vqty,:vqty2,:vqty3,:vqty4,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vemployeeid,:vshift,
					:vprocessprdid,:vmesinid,:vslocid,:vstoragebinid,:vefektivitas,:vangkatan,:vdescription,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['productoutputfgid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['productoutputfgid']);
      }
      $command->bindvalue(':vproductoutputid', $_POST['productoutputid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
			$command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
			$command->bindvalue(':vqty2', $_POST['qty2'], PDO::PARAM_STR);
			$command->bindvalue(':vqty3', $_POST['qty3'], PDO::PARAM_STR);
			$command->bindvalue(':vqty4', $_POST['qty4'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
      $command->bindvalue(':vuom2id', $_POST['uom2id'], PDO::PARAM_STR);
			$command->bindvalue(':vuom3id', $_POST['uom3id'], PDO::PARAM_STR);
			$command->bindvalue(':vuom4id', $_POST['uom4id'], PDO::PARAM_STR);
			$command->bindvalue(':vemployeeid', $_POST['employeeid'], PDO::PARAM_STR);
			$command->bindvalue(':vshift', $_POST['shift'], PDO::PARAM_STR);
			$command->bindvalue(':vprocessprdid', $_POST['processprdid'], PDO::PARAM_STR);
			$command->bindvalue(':vmesinid', $_POST['mesinid'], PDO::PARAM_STR);
			$command->bindvalue(':vstoragebinid', $_POST['storagebinid'], PDO::PARAM_STR);
			$command->bindvalue(':vslocid', $_POST['slocid'], PDO::PARAM_STR);
			$command->bindvalue(':vefektivitas', $_POST['efektivitas'], PDO::PARAM_STR);
			$command->bindvalue(':vangkatan', $_POST['angkatan'], PDO::PARAM_STR);
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
  public function actionsavedetail() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call InsertProductoutputdetail (:vproductoutputid,:vproductoutputfgid,:vproductid,:vqty,:vqty2,:vqty3,:vqty4,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vbomid,
					:vslocfromid,:vsloctoid,:vstoragebintoid,:vdescription,:vdatauser)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call UpdateProductoutputdetail (:vid,:vproductoutputid,:vproductoutputfgid,:vproductid,:vqty,:vqty2,:vqty3,:vqty4,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vbomid,
					:vslocfromid,:vsloctoid,:vstoragebintoid,:vdescription,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['productoutputdetailid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['productoutputdetailid']);
      }
      $command->bindvalue(':vproductoutputid', $_POST['productoutputid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductoutputfgid', $_POST['productoutputfgid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
			$command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
			$command->bindvalue(':vqty2', $_POST['qty2'], PDO::PARAM_STR);
			$command->bindvalue(':vqty3', $_POST['qty3'], PDO::PARAM_STR);
			$command->bindvalue(':vqty4', $_POST['qty4'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
      $command->bindvalue(':vuom2id', $_POST['uom2id'], PDO::PARAM_STR);
			$command->bindvalue(':vuom3id', $_POST['uom3id'], PDO::PARAM_STR);
			$command->bindvalue(':vuom4id', $_POST['uom4id'], PDO::PARAM_STR);
      $command->bindvalue(':vbomid', $_POST['bomid'], PDO::PARAM_STR);
			$command->bindvalue(':vslocfromid', $_POST['slocfromid'], PDO::PARAM_STR);
			$command->bindvalue(':vsloctoid', $_POST['sloctoid'], PDO::PARAM_STR);
			$command->bindvalue(':vstoragebintoid', $_POST['storagebintoid'], PDO::PARAM_STR);
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
	public function actionsavewaste() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call InsertProductoutputwaste (:vproductoutputid,:vproductoutputfgid,:vproductid,:vqty,:vqty2,:vqty3,:vqty4,:vuomid,:vuom2id,:vuom3id,:vuom4id,
					:vslocid,:vstoragebinid,:vitemnote,:vdatauser)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call UpdateProductoutputwaste (:vid,:vproductoutputid,:vproductoutputfgid,:vproductid,:vqty,:vqty2,:vqty3,:vqty4,:vuomid,:vuom2id,:vuom3id,:vuom4id,
					:vslocid,:vstoragebinid,:vitemnote,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['productoutputwasteid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['productoutputwasteid']);
      }
      $command->bindvalue(':vproductoutputid', $_POST['productoutputid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductoutputfgid', $_POST['productoutputfgid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
			$command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
			$command->bindvalue(':vqty2', $_POST['qty2'], PDO::PARAM_STR);
			$command->bindvalue(':vqty3', $_POST['qty3'], PDO::PARAM_STR);
			$command->bindvalue(':vqty4', $_POST['qty4'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
      $command->bindvalue(':vuom2id', $_POST['uom2id'], PDO::PARAM_STR);
			$command->bindvalue(':vuom3id', $_POST['uom3id'], PDO::PARAM_STR);
			$command->bindvalue(':vuom4id', $_POST['uom4id'], PDO::PARAM_STR);
			$command->bindvalue(':vslocid', $_POST['slocid'], PDO::PARAM_STR);
			$command->bindvalue(':vstoragebinid', $_POST['storagebinid'], PDO::PARAM_STR);
      $command->bindvalue(':vitemnote', $_POST['itemnote'], PDO::PARAM_STR);
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
	public function actionSaveoperator()
  {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call InsertOperator(:vproductoutputid,:vproductoutputfgid,:vemployeeid,:vdescription,:vdatauser)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call UpdateOperator(:vid,:vproductoutputid,:vproductoutputfgid,:vemployeeid,:vdescription,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['productoutputemployeeid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['productoutputemployeeid']);
      }
      $command->bindvalue(':vproductoutputid', $_POST['productoutputid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductoutputfgid', $_POST['productoutputfgid'], PDO::PARAM_STR);
      $command->bindvalue(':vemployeeid', $_POST['employeeid'], PDO::PARAM_STR);
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
  public function actionDelete()
  {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call RejectOP(:vid,:vdatauser)';
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
	public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveOP(:vid,:vdatauser)';
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
	public function actionPurge() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeproductoutput(:vid,:vdatauser)';
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
  public function actionPurgedetailfg() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeproductoutputfg(:vid,:vdatauser)';
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
	
	public function actionPurgedetail()
  {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeproductoutputdetail(:vid,:vdatauser)';
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
	public function actionPurgewaste()
  {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeproductoutputwaste(:vid,:vdatauser)';
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
	 public function actionReject()
  {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call RejectOP(:vid,:vdatauser)';
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
 public function actionDownPDF()
  {
    parent::actionDownload();
		$sql = "select a.*,b.productplanno,b.productplandate
      from productoutput a 
	join productplan b on b.productplanid = a.productplanid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.productoutputid in (" . $_GET['id'] . ")";
		}
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
	  $this->pdf->title = GetCatalog('productoutput');
	 $this->pdf->AddPage('L', array(
      210,
      330
    ));    
    foreach($dataReader as $row)  {
			$this->pdf->SetFont('Arial', '', 9);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No Output ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['productoutputno']);
      $this->pdf->text(120, $this->pdf->gety() + 5, 'No Plan ');
      $this->pdf->text(140, $this->pdf->gety() + 5, ': ' . $row['productplanno']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Tgl Output ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['productoutputdate'])));
      $this->pdf->text(120, $this->pdf->gety() + 10, 'Tgl Plan ');
      $this->pdf->text(140, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])));
      $sql1        = "SELECT t.*,a.productcode,a.productname,d.processprdname,e.sloccode,e.description as slocdesc,c.kodemesin,c.namamesin,f.fullname,h.description as rak,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom4id) as uom4code
					FROM productoutputfg t
					LEFT JOIN  productoutput g ON g.productoutputid = t.productoutputid
					LEFT JOIN  product a ON a.productid = t.productid
					LEFT JOIN  mesin c ON c.mesinid = t.mesinid
					LEFT JOIN  processprd d ON d.processprdid = t.processprdid
					LEFT JOIN  sloc e  ON e.slocid = t.slocid
					LEFT JOIN  employee f  ON f.employeeid = t.employeeid
					LEFT JOIN  storagebin h  ON h.storagebinid = t.storagebinid
        where t.productoutputid = " . $row['productoutputid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $i=0;
      $this->pdf->sety($this->pdf->gety()+20);
      foreach($dataReader1 as $row1) {
        $i=$i+1;
				$this->pdf->SetFontSize(8);
				$this->pdf->text(10,$this->pdf->gety(),'FG');
				$this->pdf->text(10,$this->pdf->gety()+5,'Product');$this->pdf->text(25,$this->pdf->gety()+5,': '.$row1['productname']);
				$this->pdf->text(10,$this->pdf->gety()+10,'Qty');$this->pdf->text(25,$this->pdf->gety()+10,': '.Yii::app()->format->formatNumber($row1['qty']).'   '.$row1['uomcode']);
				$this->pdf->text(10,$this->pdf->gety()+15,'Qty2');$this->pdf->text(25,$this->pdf->gety()+15,': '.Yii::app()->format->formatNumber($row1['qty2']).'   '.$row1['uom2code']);
				$this->pdf->text(10,$this->pdf->gety()+20,'Qty3');$this->pdf->text(25,$this->pdf->gety()+20,': '.Yii::app()->format->formatNumber($row1['qty3']).'   '.$row1['uom3code']);
				$this->pdf->text(10,$this->pdf->gety()+25,'Qty4');$this->pdf->text(25,$this->pdf->gety()+25,': '.Yii::app()->format->formatNumber($row1['qty4']).'   '.$row1['uom4code']);
				$this->pdf->text(105,$this->pdf->gety()+10,'Gudang');$this->pdf->text(120,$this->pdf->gety()+10,': '.$row1['sloccode']);
				$this->pdf->text(105,$this->pdf->gety()+15,'RAK');$this->pdf->text(120,$this->pdf->gety()+15,': '.$row1['rak']);
				$this->pdf->text(10,$this->pdf->gety()+40,'Keterangan');$this->pdf->text(25,$this->pdf->gety()+40,': '.$row1['description']);
				$this->pdf->text(105,$this->pdf->gety()+25,'Proses');$this->pdf->text(120,$this->pdf->gety()+25,': '.$row1['processprdname']);
				$this->pdf->text(105,$this->pdf->gety()+20,'Mesin');$this->pdf->text(120,$this->pdf->gety()+20,': '.$row1['kodemesin']);
				$this->pdf->text(105,$this->pdf->gety()+30,'Efektivitas');$this->pdf->text(120,$this->pdf->gety()+30,': '.$row1['efektivitas'].' '.'Menit');
				$this->pdf->text(105,$this->pdf->gety()+35,'SPV');$this->pdf->text(120,$this->pdf->gety()+35,': '.$row1['fullname']);
				$this->pdf->text(10,$this->pdf->gety()+30,'Angkatan');$this->pdf->text(25,$this->pdf->gety()+30,': '.$row1['angkatan']);
				$this->pdf->text(10,$this->pdf->gety()+35,'Shift');$this->pdf->text(25,$this->pdf->gety()+35,': '.$row1['shift']);
				$this->pdf->sety($this->pdf->gety()+30);
				$sql2 = "							select b.productname,
								sum(a.qty) as qty,
								sum(a.qty2) as qty2,
								sum(a.qty3) as qty3,
								sum(a.qty4) as qty4,
								c.uomcode,
								f.uomcode as uom2code,
								g.uomcode as uom3code,
								h.uomcode as uom4code,
								a.description,d.bomversion,
							(select sloccode from sloc d where d.slocid = a.slocfromid) as fromsloccode,
							(select description from sloc d where d.slocid = a.slocfromid) as fromslocdesc,
							(select sloccode from sloc d where d.slocid = a.sloctoid) as tosloccode,	
							(select description from sloc d where d.slocid = a.sloctoid) as toslocdesc			
							from productoutputdetail a
							left join product b on b.productid = a.productid
							left join unitofmeasure c on c.unitofmeasureid = a.uomid
							left join unitofmeasure f on f.unitofmeasureid = a.uom2id
							left join unitofmeasure g on g.unitofmeasureid = a.uom3id
							left join unitofmeasure h on h.unitofmeasureid = a.uom4id
							left join billofmaterial d on d.bomid = a.bomid
							where b.isstock = 1 and productoutputid = ".$row['productoutputid']." and productoutputfgid = ".$row1['productoutputfgid']."   
							group by b.productname ";
				$command2    = $this->connection->createCommand($sql2);
				$dataReader2 = $command2->queryAll();
				$this->pdf->text(10,$this->pdf->gety()+15,'RM');
				$this->pdf->sety($this->pdf->gety()+17);
				$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C');
				$this->pdf->setwidths(array(8,70,24,12,24,13,24,12,24,12,24,24,40));
				$this->pdf->colheader = array('No','Items','Qty','Unit','Qty2','Unit2','Qty3','Unit3','Qty4','Unit4','GD Asal','GD Tujuan','Remark');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('L','L','R','L','R','L','R','L','R','L','L','L','L');
				$j=0;
				foreach($dataReader2 as $row2) {
					$j=$j+1;
					$this->pdf->row(array($j,$row2['productname'],
							Yii::app()->format->formatNumber($row2['qty']),
							$row2['uomcode'],
							Yii::app()->format->formatNumber($row2['qty2']),
							$row2['uom2code'],
							Yii::app()->format->formatNumber($row2['qty3']),
							$row2['uom3code'],
							Yii::app()->format->formatNumber($row2['qty4']),
							$row2['uom4code'],
							$row2['fromsloccode'],
							$row2['tosloccode'],
							$row2['bomversion'].''.$row2['description']));
				}
				$sql2 = "				select b.productname,
								sum(a.qty) as qty,
								sum(a.qty2) as qty2,
								sum(a.qty3) as qty3,
								sum(a.qty4) as qty4,
								c.uomcode,
								f.uomcode as uom2code,
								g.uomcode as uom3code,
								h.uomcode as uom4code,
								a.itemnote,
							(select sloccode from sloc d where d.slocid = a.slocid) as sloccode,
							(select description from sloc d where d.slocid = a.slocid) as slocdesc,	
							(select description from storagebin d where d.storagebinid = a.storagebinid) as rak							
							from productoutputwaste a
							left join product b on b.productid = a.productid
							left join unitofmeasure c on c.unitofmeasureid = a.uomid
							left join unitofmeasure f on f.unitofmeasureid = a.uom2id
							left join unitofmeasure g on g.unitofmeasureid = a.uom3id
							left join unitofmeasure h on h.unitofmeasureid = a.uom4id
							where b.isstock = 1 and productoutputid = ".$row['productoutputid']." and productoutputfgid = ".$row1['productoutputfgid']."   
							group by b.productname ";
				$command2    = $this->connection->createCommand($sql2);
				$dataReader2 = $command2->queryAll();
				$this->pdf->CheckPageBreak(5);
				$this->pdf->sety($this->pdf->gety()+10);
				$this->pdf->text(10,$this->pdf->gety(),'WASTE / AVALAN');
				$this->pdf->sety($this->pdf->gety()+5);
				$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C');
				$this->pdf->setwidths(array(8,70,24,12,24,13,24,12,24,12,24,24,40));
				$this->pdf->colheader = array('No','Items','Qty','Unit','Qty2','Unit2','Qty3','Unit3','Qty4','Unit4','Gudang','Rak','Remark');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('L','L','R','L','R','L','R','L','R','L','L','L','L');
				$j=0;
				foreach($dataReader2 as $row2) {
					$j=$j+1;
					$this->pdf->row(array($j,$row2['productname'],
							Yii::app()->format->formatNumber($row2['qty']),
							$row2['uomcode'],
							Yii::app()->format->formatNumber($row2['qty2']),
							$row2['uom2code'],
							Yii::app()->format->formatNumber($row2['qty3']),
							$row2['uom3code'],
							Yii::app()->format->formatNumber($row2['qty4']),
							$row2['uom4code'],
							$row2['sloccode'],
							$row2['rak'],$row2['itemnote']));
				}
				$sql2 = "select a.productoutputemployeeid,b.fullname,a.description,a.productoutputfgid
											from productoutputemployee a
											left join employee b on b.employeeid = a.employeeid
							where a.productoutputid = ".$row['productoutputid']."
							group by a.productoutputemployeeid ";
				$command2    = $this->connection->createCommand($sql2);
				$dataReader2 = $command2->queryAll();
				$this->pdf->sety($this->pdf->gety()+5);
				$this->pdf->text(10,$this->pdf->gety(),'Operator');
				$this->pdf->sety($this->pdf->gety()+5);
				$this->pdf->colalign = array('C','C','C','C');
				$this->pdf->setwidths(array(8,13,55,35));
				$this->pdf->colheader = array('No','ID FG','Operator','Keterangan');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('L','L','L','L');
				$j=0;
				foreach($dataReader2 as $row2) {
					$j=$j+1;
					$this->pdf->row(array($j,
					$row2['productoutputfgid'],$row2['fullname'],
							$row2['description']));
				}
				$this->pdf->CheckPageBreak(20);
				$this->pdf->sety($this->pdf->gety()+15);
				$this->pdf->text(20,$this->pdf->gety()+5,'Approved By');$this->pdf->text(150,$this->pdf->gety()+5,'Proposed By');
				$this->pdf->text(20,$this->pdf->gety()+20,'____________ ');$this->pdf->text(150,$this->pdf->gety()+20,'____________');
				$this->pdf->AddPage('L', array(
					210,
					330
				));    
      }
		}
	  $this->pdf->Output();
	}
	public function actionDownxls() {
    $this->menuname = 'productoutputlist';
    parent::actionDownxls();
    $productoutputid = GetSearchText(array('POST','GET','Q'),'productoutputid');
		$plantcode   = GetSearchText(array('POST','GET','Q'),'plantcode');
		$productplanno   = GetSearchText(array('POST','GET','Q'),'productplanno');
    $productoutputdate     = GetSearchText(array('POST','GET','Q'),'productoutputdate');
    $productoutputno = GetSearchText(array('POST','GET','Q'),'productoutputno');
    $headernote = GetSearchText(array('POST','GET','Q'),'headernote');
		$sql = "select a.*,b.productplanno,b.productplandate,n.plantcode,m.sloccode,e.productname,f.uomcode,g.uomcode as uom2code,z.fullname,
			h.uomcode as uom3code,i.uomcode as uom4code,d.qty,d.qty2,d.qty3,d.qty4,o.productplanno as oklanjut,j.bomversion,
			d.description as fgdesc,p.processprdname,l.kodemesin,d.outputdate,s.uomcode as uomdetail,t.uomcode as uom2detail,
			u.uomcode as uom3detail,v.uomcode as uom4detail,r.productname as productjasa,q.qty as qtydetail,q.qty2 as qty2detail,q.qty3 as qty3detail,
			q.qty4 as qty4detail,w.bomversion as bomdetail,q.description as descdetail,x.sloccode as sloctodetail,y.sloccode as slocfromdetail,
			d.shift,d.efektivitas,d.angkatan,z.fullname,zz.description as storagebinto,ss.description as storagebinname,
			bb.productname as productwaste,bf.uomcode as uomwaste,bg.uomcode as uom2waste,
			bh.uomcode as uom3waste,bi.uomcode as uom4waste,bb.qty as qtywaste,bb.qty2 as qty2waste,bb.qty3 as qty3waste,bb.qty4 as qty4waste,
			bb.sloccode as slocwaste,ba.description as storagebinwaste,bb.itemnote as wastenote, da.fullname as employeename, da.oldnik, ca.description as operatornote
      from productoutput a 
			left join productplan b on b.productplanid = a.productplanid 
			left join soheader c on c.soheaderid = b.soheaderid
			left join addressbook z on z.addressbookid = c.addressbookid 
			left join productoutputfg d on d.productoutputid = a.productoutputid 
			left join product e on e.productid = d.productid 
			left join unitofmeasure f on f.unitofmeasureid = d.uomid 
			left join unitofmeasure g on g.unitofmeasureid = d.uom2id 
			left join unitofmeasure h on h.unitofmeasureid = d.uom3id 
			left join unitofmeasure i on i.unitofmeasureid = d.uom4id
			left join billofmaterial j on j.bomid = d.bomid 
			left join processprd k on k.processprdid = d.processprdid 
			left join mesin l on l.mesinid = d.mesinid 
			left join sloc m on m.slocid = d.slocid 
			left join plant n on n.plantid = a.plantid 
			left join productplan o on o.productplanid = b.parentplanid 
			left join processprd p on p.processprdid = k.processprdid  
			left join productoutputdetail q on q.productoutputid = d.productoutputid 
			left join product r on r.productid = q.productid 
			left join unitofmeasure s on s.unitofmeasureid = q.uomid 
			left join unitofmeasure t on t.unitofmeasureid = q.uom2id 
			left join unitofmeasure u on u.unitofmeasureid = q.uom3id 
			left join unitofmeasure v on v.unitofmeasureid = q.uom4id 
			left join billofmaterial w on w.bomid = q.bomid 
			left join sloc x on x.slocid = q.sloctoid 
			left join sloc y on y.slocid = q.slocfromid 
			left join storagebin zz on zz.storagebinid = q.storagebintoid
			left join storagebin ss on ss.storagebinid = d.storagebinid
			left join productoutputwaste bb on bb.productoutputfgid = d.productoutputfgid
			left join unitofmeasure bf on bf.unitofmeasureid = bb.uomid 
			left join unitofmeasure bg on bg.unitofmeasureid = bb.uom2id 
			left join unitofmeasure bh on bh.unitofmeasureid = bb.uom3id 
			left join unitofmeasure bi on bi.unitofmeasureid = bb.uom4id
			left join storagebin ba on ba.storagebinid = bb.storagebinid
			left join productoutputemployee ca on ca.productoutputfgid = d.productoutputfgid
			left join employee da on da.employeeid = ca.employeeid
		";
		$sql .= " where coalesce(a.productoutputid,'') like '".$productoutputid."' 
			and coalesce(n.plantcode,'') like '".$plantcode."' 
			and coalesce(b.productplanno,'') like '".$productplanno."' 
			and coalesce(a.productoutputdate,'') like '".$productoutputdate."' 
			and coalesce(a.productoutputno,'') like '".$productoutputno."' 
			and coalesce(a.headernote,'') like '".$headernote."'"
		;
		if ($_GET['id'] !== '') {
      $sql = $sql . " and a.productoutputid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 3;$nourut=0;$oldbom='';
    foreach ($dataReader as $row) {
			if ($oldbom != $row['productoutputid']) {
				$nourut+=1;
				$oldbom = $row['productoutputid'];
			}
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i, $nourut)
				->setCellValueByColumnAndRow(1, $i, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $i, date(Yii::app()->params['dateviewfromdb'], strtotime($row['productoutputdate'])))
				->setCellValueByColumnAndRow(3, $i, $row['productoutputno'])
				->setCellValueByColumnAndRow(4, $i, $row['productplanno'])
				->setCellValueByColumnAndRow(5, $i, $row['oklanjut'])
				->setCellValueByColumnAndRow(6, $i, $row['sono'])
				->setCellValueByColumnAndRow(7, $i, $row['fullname'])
				->setCellValueByColumnAndRow(8, $i, $row['headernote'])
				->setCellValueByColumnAndRow(10, $i, $row['productname'])
				->setCellValueByColumnAndRow(11, $i, Yii::app()->format->formatNumber($row['qty']))
				->setCellValueByColumnAndRow(12, $i, $row['uomcode'])
				->setCellValueByColumnAndRow(13, $i, Yii::app()->format->formatNumber($row['qty2']))
				->setCellValueByColumnAndRow(14, $i, $row['uom2code'])
				->setCellValueByColumnAndRow(15, $i, Yii::app()->format->formatNumber($row['qty3']))
				->setCellValueByColumnAndRow(16, $i, $row['uom3code'])
				->setCellValueByColumnAndRow(17, $i, Yii::app()->format->formatNumber($row['qty4']))
				->setCellValueByColumnAndRow(18, $i, $row['uom4code'])
				->setCellValueByColumnAndRow(19, $i, $row['bomversion'])
				->setCellValueByColumnAndRow(20, $i, $row['processprdname'])
				->setCellValueByColumnAndRow(21, $i, $row['kodemesin'])
				->setCellValueByColumnAndRow(22, $i, $row['sloccode'])
				->setCellValueByColumnAndRow(23, $i, $row['storagebinname'])
				->setCellValueByColumnAndRow(24, $i, $row['shift'])
				->setCellValueByColumnAndRow(25, $i, $row['efektivitas'])
				->setCellValueByColumnAndRow(26, $i, $row['angkatan'])
				->setCellValueByColumnAndRow(27, $i, $row['fgdesc'])
				->setCellValueByColumnAndRow(28, $i, $row['productjasa'])
				->setCellValueByColumnAndRow(29, $i, Yii::app()->format->formatNumber($row['qtydetail']))
				->setCellValueByColumnAndRow(30, $i, $row['uomdetail'])
				->setCellValueByColumnAndRow(31, $i, Yii::app()->format->formatNumber($row['qty2detail']))
				->setCellValueByColumnAndRow(32, $i, $row['uom2detail'])
				->setCellValueByColumnAndRow(33, $i, Yii::app()->format->formatNumber($row['qty3detail']))
				->setCellValueByColumnAndRow(34, $i, $row['uom3detail'])
				->setCellValueByColumnAndRow(35, $i, Yii::app()->format->formatNumber($row['qty4detail']))
				->setCellValueByColumnAndRow(36, $i, $row['uom4detail'])
				->setCellValueByColumnAndRow(37, $i, $row['bomdetail'])
				->setCellValueByColumnAndRow(38, $i, $row['sloctodetail'])
				->setCellValueByColumnAndRow(39, $i, $row['slocfromdetail'])
				->setCellValueByColumnAndRow(40, $i, $row['storagebinwaste'])
				->setCellValueByColumnAndRow(41, $i, $row['storagebinto'])
				->setCellValueByColumnAndRow(42, $i, $row['productwaste'])
				->setCellValueByColumnAndRow(43, $i, Yii::app()->format->formatNumber($row['qtywaste']))
				->setCellValueByColumnAndRow(44, $i, $row['uomwaste'])
				->setCellValueByColumnAndRow(45, $i, Yii::app()->format->formatNumber($row['qty2waste']))
				->setCellValueByColumnAndRow(46, $i, $row['uom2waste'])
				->setCellValueByColumnAndRow(47, $i, Yii::app()->format->formatNumber($row['qty3waste']))
				->setCellValueByColumnAndRow(48, $i, $row['uom3waste'])
				->setCellValueByColumnAndRow(49, $i, Yii::app()->format->formatNumber($row['qty4waste']))
				->setCellValueByColumnAndRow(50, $i, $row['uom4waste'])
				->setCellValueByColumnAndRow(51, $i, $row['slocwaste'])
				->setCellValueByColumnAndRow(52, $i, $row['storagebinwaste'])
				->setCellValueByColumnAndRow(53, $i, $row['wastenote'])
				->setCellValueByColumnAndRow(54, $i, $row['employeename'])
				->setCellValueByColumnAndRow(55, $i, $row['oldnik'])
				->setCellValueByColumnAndRow(56, $i, $row['operatornote'])
			;
			$i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}