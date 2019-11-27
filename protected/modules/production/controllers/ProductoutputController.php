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
		header("Content-Type: application/json");			
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
    header("Content-Type: application/json");
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
					where zz.qty > zz.qtyres)"
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
					where zz.qty > zz.qtyres)
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
    header("Content-Type: application/json");
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
    header("Content-Type: application/json");
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
    header("Content-Type: application/json");
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
    header("Content-Type: application/json");
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
    header("Content-Type: application/json");
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
    header("Content-Type: application/json");
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
    header("Content-Type: application/json");
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
    header("Content-Type: application/json");
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
    header("Content-Type: application/json");
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
    header("Content-Type: application/json");
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
    header("Content-Type: application/json");
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
    header("Content-Type: application/json");
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
    header("Content-Type: application/json");
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
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['titleid'] = GetCatalog('productplanid');
		$this->dataprint['titleproductplanno'] = GetCatalog('productplanno');
		$this->dataprint['titleplantcode'] = GetCatalog('plantcode');
		$this->dataprint['titleproductplandate'] = GetCatalog('productplandate');
		$this->dataprint['titlesono'] = GetCatalog('sono');
		$this->dataprint['titlesodate'] = GetCatalog('sodate');
		$this->dataprint['titlepocustno'] = GetCatalog('pocustno');
		$this->dataprint['titlefullname'] = GetCatalog('customer');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titlehasilproses'] = GetCatalog('hasilproses');
		$this->dataprint['titleproductname'] = GetCatalog('productname');
		$this->dataprint['titleproductcode'] = GetCatalog('productcode');
		$this->dataprint['titleqty'] = GetCatalog('qty');
		$this->dataprint['titleqty2'] = GetCatalog('qty2');
		$this->dataprint['titleqty3'] = GetCatalog('qty3');
		$this->dataprint['titleqty4'] = GetCatalog('qty4');
		$this->dataprint['titleuomcode'] = GetCatalog('uomcode');
		$this->dataprint['titleuom2code'] = GetCatalog('uom2code');
		$this->dataprint['titleuom3code'] = GetCatalog('uom3code');
		$this->dataprint['titleuom4code'] = GetCatalog('uom4code');
		$this->dataprint['titlefromsloccode'] = GetCatalog('slocfrom');
		$this->dataprint['titletosloccode'] = GetCatalog('slocprocess');
		$this->dataprint['titlekodemesin'] = GetCatalog('kodemesin');
		$this->dataprint['titleprocessprdname'] = GetCatalog('processprdname');
		$this->dataprint['titlebomversion'] = GetCatalog('bomversion');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['productoutputno'] = GetSearchText(array('GET'),'productoutputno');
    $this->dataprint['plantcode'] = GetSearchText(array('GET'),'plantcode');
    $this->dataprint['productoutputdate'] = GetSearchText(array('GET'),'productoutputdate');
    $this->dataprint['sono'] = GetSearchText(array('GET'),'sono');
    $this->dataprint['sodate'] = GetSearchText(array('GET'),'sodate');
    $this->dataprint['pocustno'] = GetSearchText(array('GET'),'pocustno');
    $this->dataprint['customer'] = GetSearchText(array('GET'),'customer');
  }
}
