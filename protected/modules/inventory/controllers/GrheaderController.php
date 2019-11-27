<?php
class GrheaderController extends Controller {
  public $menuname = 'grheader';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexgrjasa() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchgrjasa();
    else
      $this->renderPartial('index', array());
  }
	public function actionIndexresult() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchresult();
    else
      $this->renderPartial('index', array());
  } 
  public function actionGetData() {
		$id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'grheaderid' => $id,
		));
  }
	public function actionGeneratedetail() {
    if (isset($_POST['id'])) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateGRPO(:vid, :vhid, :vdatauser)';
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
		$grheaderid 		= GetSearchText(array('POST','Q'),'grheaderid','','int');
		$plantid 		= GetSearchText(array('POST','GET'),'plantid','','int');
		$plantcode 		= GetSearchText(array('POST','Q'),'plantcode');
		$grdate 		= GetSearchText(array('POST','Q'),'grdate');
		$grno 		= GetSearchText(array('POST','Q'),'grno');
		$pono 		= GetSearchText(array('POST','Q'),'pono');
		$productname 		= GetSearchText(array('POST','Q'),'productname');
		$sloccode 		= GetSearchText(array('POST','Q'),'sloccode');
		$poheaderid 		= GetSearchText(array('POST','GET'),'poheaderid','','int');
		$supplier 		= GetSearchText(array('POST','Q'),'supplier');
		$sjsupplier 		= GetSearchText(array('POST','Q'),'sjsupplier');
		$kendaraanno 		= GetSearchText(array('POST','Q'),'kendaraanno');
		$supir 		= GetSearchText(array('POST','Q'),'supir');
		$headernote 		= GetSearchText(array('POST','Q'),'headernote');
		$recordstatus 		= GetSearchText(array('POST','Q'),'recordstatus');
		$poheaderid 		= GetSearchText(array('POST','GET'),'poheaderid','','int');
		$plantid 		= GetSearchText(array('POST','GET'),'plantid','','int');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','grheaderid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset     = ($page - 1) * $rows;
		$result     = array();
		$row        = array();
    if (isset($_GET['invgr'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
				->from('grheader t')
				->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
				->where("
				((coalesce(grheaderid,'') like :grheaderid) 
				and (coalesce(plantcode,'') like :plantcode) 
				and (coalesce(sjsupplier,'') like :sjsupplier) 
				and (coalesce(grno,'') like :grno)
				and (coalesce(d.fullname,'') like :supplier)				
				and (coalesce(t.headernote,'') like :headernote) 
				and (coalesce(pono,'') like :pono)
				and (coalesce(kendaraanno,'') like :kendaraanno)				
				and (coalesce(supir,'') like :supir)
				and (coalesce(grdate,'') like :grdate)) 
				and t.recordstatus in (".getUserRecordStatus('listgr').")
				and a.poheaderid = ".$poheaderid."
				and t.grheaderid not in (
					select distinct zz.grheaderid
					from invoiceapgr zz
					join invoiceap zzz on zzz.invoiceapid = zz.invoiceapid 
					where zzz.recordstatus >= 3
				)
				",
			array(
				':grheaderid' => '%' . $grheaderid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':pono' => '%' . $pono . '%',
				':supplier' => '%' . $supplier . '%',
				':grno' => '%' . $grno . '%',
				':headernote' => '%' . $headernote . '%',
				':sjsupplier' => '%' . $sjsupplier . '%',
				':kendaraanno' => '%' . $kendaraanno . '%',
				':supir' => '%' . $supir . '%',
				':grdate' => '%' . $grdate . '%'
			))->queryScalar();
    } else
		if (isset($_GET['grretur'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
				->from('grheader t')
				->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
				->where("
				((coalesce(grheaderid,'') like :grheaderid) 
				or (coalesce(plantcode,'') like :plantcode) 
				or (coalesce(sjsupplier,'') like :sjsupplier) 
				or (coalesce(grno,'') like :grno)
				or (coalesce(fullname,'') like :supplier)				
				or (coalesce(t.headernote,'') like :headernote) 
				or (coalesce(pono,'') like :pono)
				or (coalesce(kendaraanno,'') like :kendaraanno)				
				or (coalesce(supir,'') like :supir)
				or (coalesce(grdate,'') like :grdate)) 
				and t.recordstatus in (".getUserRecordStatus('listgr').")
				and a.poheaderid = ".$poheaderid."
				and t.plantid = ".$plantid."
				",
			array(
				':grheaderid' => '%' . $grheaderid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':pono' => '%' . $pono . '%',
				':supplier' => '%' . $supplier . '%',
				':grno' => '%' . $grno . '%',
				':headernote' => '%' . $headernote . '%',
				':sjsupplier' => '%' . $sjsupplier . '%',
				':kendaraanno' => '%' . $kendaraanno . '%',
				':supir' => '%' . $supir . '%',
				':grdate' => '%' . $grdate . '%'
			))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
				->from('grheader t')
				->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
				->where("
				((coalesce(grheaderid,'') like :grheaderid) 
				and (coalesce(plantcode,'') like :plantcode) 
				and (coalesce(sjsupplier,'') like :sjsupplier) 
				and (coalesce(grno,'') like :grno)
				and (coalesce(d.fullname,'') like :supplier)				
				and (coalesce(t.headernote,'') like :headernote) 
				and (coalesce(pono,'') like :pono)
				and (coalesce(kendaraanno,'') like :kendaraanno)				
				and (coalesce(supir,'') like :supir)
				and (coalesce(grdate,'') like :grdate)) 
				and t.recordstatus in (".getUserRecordStatus('listgr').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				"	and t.plantid in (".getUserObjectValues('plant').")".
				(($sloccode != '%%')?"
				and t.grheaderid in (
					select distinct z.grheaderid 
					from grdetail z 
					join sloc zz on zz.slocid = z.slocid 
					where zz.sloccode like '%".$sloccode."%'
				)":'').
				(($productname != '%%')?"
				and t.grheaderid in (
					select distinct z.grheaderid 
					from grdetail z 
					join product zz on zz.productid = z.productid 
					where zz.productname like '%".$productname."%'
				)":'')
				,			
			array(
				':grheaderid' => '%' . $grheaderid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':pono' => '%' . $pono . '%',
				':supplier' => '%' . $supplier . '%',
				':grno' => '%' . $grno . '%',
				':headernote' => '%' . $headernote . '%',
				':sjsupplier' => '%' . $sjsupplier . '%',
				':kendaraanno' => '%' . $kendaraanno . '%',
				':supir' => '%' . $supir . '%',
				':grdate' => '%' . $grdate . '%'
			))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['invgr'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.pono,b.plantcode,c.companyname,d.fullname,t.headernote,b.companyid,a.isjasa')
				->from('grheader t')
				->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
				->where("
				((coalesce(grheaderid,'') like :grheaderid) 
				and (coalesce(plantcode,'') like :plantcode) 
				and (coalesce(sjsupplier,'') like :sjsupplier) 
				and (coalesce(grno,'') like :grno)
				and (coalesce(fullname,'') like :supplier)				
				and (coalesce(t.headernote,'') like :headernote) 
				and (coalesce(pono,'') like :pono)
				and (coalesce(kendaraanno,'') like :kendaraanno)				
				and (coalesce(supir,'') like :supir)
				and (coalesce(grdate,'') like :grdate)) 
				and t.recordstatus in (".getUserRecordStatus('listgr').")
				and a.poheaderid = ".$poheaderid."
				and t.grheaderid not in (
					select distinct zz.grheaderid
					from invoiceapgr zz
					join invoiceap zzz on zzz.invoiceapid = zz.invoiceapid 
					where zzz.recordstatus >= 3
				)
				",
			array(
        ':grheaderid' => '%' . $grheaderid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':pono' => '%' . $pono . '%',
				':supplier' => '%' . $supplier . '%',
				':grno' => '%' . $grno . '%',
				':headernote' => '%' . $headernote . '%',
				':sjsupplier' => '%' . $sjsupplier . '%',
				':kendaraanno' => '%' . $kendaraanno . '%',
				':supir' => '%' . $supir . '%',
				':grdate' => '%' . $grdate . '%'
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		} else
		if (isset($_GET['grretur'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.pono,b.plantcode,c.companyname,d.fullname,t.headernote,b.companyid,a.isjasa')
				->from('grheader t')
				->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
				->where("
				((coalesce(grheaderid,'') like :grheaderid) 
				or (coalesce(plantcode,'') like :plantcode) 
				or (coalesce(sjsupplier,'') like :sjsupplier) 
				or (coalesce(grno,'') like :grno)
				or (coalesce(fullname,'') like :supplier)				
				or (coalesce(t.headernote,'') like :headernote) 
				or (coalesce(pono,'') like :pono)
				or (coalesce(kendaraanno,'') like :kendaraanno)				
				or (coalesce(supir,'') like :supir)
				or (coalesce(grdate,'') like :grdate)) 
				and t.recordstatus in (".getUserRecordStatus('listgr').")
				and a.poheaderid = ".$poheaderid."
				and t.plantid = ".$plantid."
				",
			array(
        ':grheaderid' => '%' . $grheaderid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':pono' => '%' . $pono . '%',
				':supplier' => '%' . $supplier . '%',
				':grno' => '%' . $grno . '%',
				':headernote' => '%' . $headernote . '%',
				':sjsupplier' => '%' . $sjsupplier . '%',
				':kendaraanno' => '%' . $kendaraanno . '%',
				':supir' => '%' . $supir . '%',
				':grdate' => '%' . $grdate . '%'
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		} else {
			$cmd = Yii::app()->db->createCommand()->select('t.*,a.pono,b.plantcode,c.companyname,d.fullname,t.headernote,b.companyid,a.isjasa')
				->from('grheader t')
				->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
				->where("
				((coalesce(grheaderid,'') like :grheaderid) 
				and (coalesce(plantcode,'') like :plantcode) 
				and (coalesce(sjsupplier,'') like :sjsupplier) 
				and (coalesce(grno,'') like :grno)
				and (coalesce(d.fullname,'') like :supplier)				
				and (coalesce(t.headernote,'') like :headernote) 
				and (coalesce(pono,'') like :pono)
				and (coalesce(kendaraanno,'') like :kendaraanno)				
				and (coalesce(supir,'') like :supir)
				and (coalesce(grdate,'') like :grdate)) 
				and t.recordstatus in (".getUserRecordStatus('listgr').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				"	and t.plantid in (".getUserObjectValues('plant').")".
				(($sloccode != '%%')?"
				and t.grheaderid in (
					select distinct z.grheaderid 
					from grdetail z 
					join sloc zz on zz.slocid = z.slocid 
					where zz.sloccode like '%".$sloccode."%'
				)":'').
				(($productname != '%%')?"
				and t.grheaderid in (
					select distinct z.grheaderid 
					from grdetail z 
					join product zz on zz.productid = z.productid 
					where zz.productname like '%".$productname."%'
				)":'')
				,			
			array(
        ':grheaderid' => '%' . $grheaderid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':pono' => '%' . $pono . '%',
				':supplier' => '%' . $supplier . '%',
				':grno' => '%' . $grno . '%',
				':headernote' => '%' . $headernote . '%',
				':sjsupplier' => '%' . $sjsupplier . '%',
				':kendaraanno' => '%' . $kendaraanno . '%',
				':supir' => '%' . $supir . '%',
				':grdate' => '%' . $grdate . '%'
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		}
		foreach ($cmd as $data) {
			$row[] = array(
				'grheaderid' => $data['grheaderid'],
				'grdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['grdate'])),
				'grno' => $data['grno'],
				'plantid' => $data['plantid'],
				'plantcode' => $data['plantcode'],
				'poheaderid' => $data['poheaderid'],
				'pono' => $data['pono'],
				'isjasa' => $data['isjasa'],
				'companyid' => $data['companyid'],
				'companyname' => $data['companyname'],
				'addressbookid' => $data['addressbookid'],
				'fullname' => $data['fullname'],
				'pibno' => $data['pibno'],
				'sjsupplier' => $data['sjsupplier'],
				'kendaraanno' => $data['kendaraanno'],
				'supir' => $data['supir'],
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'grdetailid';
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
					->from('grdetail t')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('storagebin b', 'b.storagebinid = t.storagebinid')
					->leftjoin('materialtype d', 'd.materialtypeid = a.materialtypeid')					
					->leftjoin('podetail e', 'e.podetailid = t.podetailid')					
					->where('t.grheaderid = :grheaderid',
					array(
				':grheaderid' => $id
				))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
					->select('t.*,a.productcode,a.productname,c.sloccode,d.materialtypecode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,b.description as rak,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code,
						GetStdQty(a.productid) as stdqty,
						GetStdQty2(a.productid) as stdqty2,
						GetStdQty3(a.productid) as stdqty3,
						GetStock(a.productid,t.uomid,t.slocid) as qtystock,e.qty as poqty, 
						e.qty-(select sum(z.qty)
						from grdetail z
						where z.podetailid = e.podetailid
						) as sisaqty
						')
					->from('grdetail t')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('storagebin b', 'b.storagebinid = t.storagebinid')
					->leftjoin('sloc c', 'c.slocid = t.slocid')
					->leftjoin('materialtype d', 'd.materialtypeid = a.materialtypeid')
					->leftjoin('podetail e', 'e.podetailid = t.podetailid')		
					->where('t.grheaderid = :grheaderid', array(
		':grheaderid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		foreach ($cmd as $data) {
			if ($data['qtystock'] >= $data['qty']) {
				$stockcount = 0;
			} else {
				$stockcount = 1;
			}
			$row[] = array(
				'grdetailid' => $data['grdetailid'],
				'grheaderid' => $data['grheaderid'],
				'productid' => $data['productid'],
				'productcode' => $data['productcode'],
				'productname' => $data['productname'],
				'materialtypecode' => $data['materialtypecode'],
				'qty' => Yii::app()->format->formatNumber($data['qty']),
				'poqty' => Yii::app()->format->formatNumber($data['poqty']),
				'sisaqty' => Yii::app()->format->formatNumber($data['sisaqty']),
				'qty2' => Yii::app()->format->formatNumber($data['qty2']),
				'qty3' => Yii::app()->format->formatNumber($data['qty3']),
				'stdqty' => Yii::app()->format->formatNumber($data['stdqty']),
				'stdqty2' => Yii::app()->format->formatNumber($data['stdqty2']),
				'stdqty3' => Yii::app()->format->formatNumber($data['stdqty3']),
				'qtystock' => Yii::app()->format->formatNumber($data['qtystock']),
				'uomid' => $data['uomid'],
				'stockcount' => $stockcount,
				'uom2id' => $data['uom2id'],
				'uom3id' => $data['uom3id'],
				'uomcode' => $data['uomcode'],
				'uom2code' => $data['uom2code'],
				'uom3code' => $data['uom3code'],
				'storagebinid' => $data['storagebinid'],
				'rak' => $data['rak'],
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
  public function actionSearchGrjasa() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'grjasaid';
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
			->from('grjasa t')
			->leftjoin('grheader g', 'g.grheaderid = t.grheaderid')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('mesin c', 'c.mesinid = t.mesinid')
			->leftjoin('sloc d', 'd.slocid = t.sloctoid')
					->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')					
			->where('t.grheaderid = :grheaderid',
			array(
				':grheaderid' => $id
			))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
					->select('t.*,a.productcode,a.productname,c.namamesin,d.sloccode,t.description,e.materialtypecode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode')
					->from('grjasa t')
					->leftjoin('grheader g', 'g.grheaderid = t.grheaderid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('mesin c', 'c.mesinid = t.mesinid')
					->leftjoin('sloc d', 'd.slocid = t.sloctoid')
					->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')					
					->where('t.grheaderid = :grheaderid', array(
		':grheaderid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'grjasaid' => $data['grjasaid'],
        'grheaderid' => $data['grheaderid'],
        'productid' => $data['productid'],
		'productcode' => $data['productcode'],
		'productname' => $data['productname'],
		'materialtypecode' => $data['materialtypecode'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
		'reqdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['reqdate'])),
        'mesinid' => $data['mesinid'],
        'namamesin' => $data['namamesin'],
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
	public function actionSearchResult() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'grresultid';
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
					->from('grresult t')
					->leftjoin('grheader g', 'g.grheaderid = t.grheaderid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')					
					->where('t.grheaderid = :grheaderid',
					array(
				':grheaderid' => $id
				))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
					->select('t.*,a.productcode,a.productname,e.materialtypecode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,t.description,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code')
					->from('grresult t')
					->leftjoin('grheader g', 'g.grheaderid = t.grheaderid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')					
					->where('t.grheaderid = :grheaderid', array(
		':grheaderid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
	foreach ($cmd as $data) {
      $row[] = array(
        'grresultid' => $data['grresultid'],
        'grheaderid' => $data['grheaderid'],
        'productid' => $data['productid'],
		'productcode' => $data['productcode'],
		'productname' => $data['productname'],
        'qty1' => Yii::app()->format->formatNumber($data['qty1']),
		'qty2' => Yii::app()->format->formatNumber($data['qty2']),
		'qty3' => Yii::app()->format->formatNumber($data['qty3']),
        'uomid' => $data['uomid'],
		'uom2id' => $data['uom2id'],
		'uom3id' => $data['uom3id'],
        'uomcode' => $data['uomcode'],
		'uom2code' => $data['uom2code'],
		'uom3code' => $data['uom3code'],
		'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
  public function actionReject()  {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call RejectGR(:vid,:vdatauser)';
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
  public function actionApprove()  {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveGR(:vid,:vdatauser)';
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
		$sql = 'call Modifgrheader(:vid,:vgrdate,:vplantid,:visjasa,:vpoheaderid,:vaddressbookid,:vpibno,:vsjsuppier,:vkendaraanno,:vsupir,
			:vheadernote,:vdatauser)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vgrdate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':visjasa', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vpoheaderid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vpibno', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vsjsuppier', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vkendaraanno', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vsupir', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionSave() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['grheader-grheaderid'])?$_POST['grheader-grheaderid']:''),
				date(Yii::app()->params['datetodb'], strtotime($_POST['grheader-grdate'])),
				$_POST['grheader-plantid'],(isset($_POST['grheader-isjasa']) ? 1 : 0),$_POST['grheader-poheaderid'],$_POST['grheader-addressbookid'],$_POST['grheader-pibno'],$_POST['grheader-sjsupplier'],
				$_POST['grheader-kendaraanno'],$_POST['grheader-supir'],$_POST['grheader-headernote']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
  }
  public function actionSavedetail() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call InsertGrdetail(:vgrheaderid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vqty,:vqty2,:vqty3,:vslocid,:vrak,
					:vitemnote,:vdatauser)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call UpdateGrdetail(:vid,:vgrheaderid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vqty,:vqty2,:vqty3,:vslocid,:vrak,
					:vitemnote,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['grdetailid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['grdetailid']);
      }
      $command->bindvalue(':vgrheaderid', $_POST['grheaderid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
      $command->bindvalue(':vuom2id', $_POST['uom2id'], PDO::PARAM_STR);
			$command->bindvalue(':vuom3id', $_POST['uom3id'], PDO::PARAM_STR);
			$command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
			$command->bindvalue(':vqty2', $_POST['qty2'], PDO::PARAM_STR);
			$command->bindvalue(':vqty3', $_POST['qty3'], PDO::PARAM_STR);
			$command->bindvalue(':vslocid', $_POST['slocid'], PDO::PARAM_STR);
			$command->bindvalue(':vrak', $_POST['storagebinid'], PDO::PARAM_STR);
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
  public function actionSavegrjasa() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call InsertGrjasa(:vgrheaderid,:vproductid,:vuomid,:vqty,:vreqdate,
					:vsloctoid,:vmesinid,:vdescription,:vdatauser)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call UpdateGrjasa(:vid,:vgrheaderid,:vproductid,:vuomid,:vqty,:vreqdate,
					:vsloctoid,:vmesinid,:vdescription,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['grjasaid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['grjasaid']);
      }
      $command->bindvalue(':vgrheaderid', $_POST['grheaderid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
			$command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
      $command->bindvalue(':vreqdate', date(Yii::app()->params['datetodb'], strtotime($_POST['reqdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vsloctoid', $_POST['sloctoid'], PDO::PARAM_STR);
			$command->bindvalue(':vmesinid', $_POST['mesinid'], PDO::PARAM_STR);
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
	public function actionSaveresult() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call InsertGRresult(:vgrheaderid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vqty1,:vqty2,:vqty3,:vdescription,:vdatauser)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call UpdateGRresult(:vid,:vgrheaderid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vqty1,:vqty2,:vqty3,:vdescription,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['grresultid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['grresultid']);
      }
      $command->bindvalue(':vgrheaderid', $_POST['grheaderid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
      $command->bindvalue(':vuom2id', $_POST['uom2id'], PDO::PARAM_STR);
			$command->bindvalue(':vuom3id', $_POST['uom3id'], PDO::PARAM_STR);
			$command->bindvalue(':vqty1', $_POST['qty1'], PDO::PARAM_STR);
			$command->bindvalue(':vqty2', $_POST['qty2'], PDO::PARAM_STR);
			$command->bindvalue(':vqty3', $_POST['qty3'], PDO::PARAM_STR);
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
  public function actionPurge() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgegrheader(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
          $command->execute();
        }
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
	public function actionPurgeAllDetail() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgegralldetail(:vid,:vdatauser)';
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
        $sql     = 'call PurgeGrdetail(:vid,:vdatauser)';
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
  public function actionPurgeGrjasa() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgegrjasa(:vid,:vdatauser)';
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
        $sql     = 'call Purgegrresult(:vid,:vdatauser)';
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
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select a.grheaderid, b.pono, c.fullname as supplier, a.grno, a.grdate, a.sjsupplier, a.pibno, a.kendaraanno, a.supir, a.headernote,d.plantcode 
						from grheader a
						left join poheader b on b.poheaderid = a.poheaderid
						left join addressbook c on c.addressbookid = a.addressbookid 
						left join plant d on d.plantid = a.plantid 
						";
		$grheaderid 		= GetSearchText(array('GET'),'grheaderid');
		$plantcode     	= GetSearchText(array('GET'),'plantcode'); 
		$grdate    			= GetSearchText(array('GET'),'grdate');
		$grno 				= GetSearchText(array('GET'),'grno');
		$pono 				= GetSearchText(array('GET'),'pono');
		$productname 				= GetSearchText(array('GET'),'productname');
		$sloccode 				= GetSearchText(array('GET'),'sloccode');
		$supplier 				= GetSearchText(array('GET'),'supplier');
		$sjsupplier 				= GetSearchText(array('GET'),'sjsupplier');
		$kendaraanno 				= GetSearchText(array('GET'),'kendaraanno');
		$supir 				= GetSearchText(array('GET'),'supir');
		$headernote 				= GetSearchText(array('GET'),'headernote');

		$sql .= " where coalesce(grheaderid,'') like '%".$grheaderid."%' 
			and coalesce(plantcode,'') like '%".$plantcode."%' 
			and coalesce(grdate,'') like '%".$grdate."%'
			and coalesce(grno,'') like '%".$grno."%' 
			and coalesce(pono,'') like '%".$pono."%'
			and coalesce(fullname,'') like '%".$supplier."%'
			and coalesce(sjsupplier,'') like '%".$sjsupplier."%'
			and coalesce(kendaraanno,'') like '%".$kendaraanno."%' 
			and coalesce(supir,'') like '%".$supir."%' 
			and coalesce(a.headernote,'') like '%".$headernote."%' 
		";
		
		($productname != '%%')?$sql.= "
			and a.grheaderid in 
			(
				select distinct za.grheaderid 
				from grdetail za 
				left join product zb on zb.productid = za.productid 
				where coalesce(zb.productname,'') like '%".$productname."%' 
			)
		":'';
		
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.grheaderid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = getCatalog('grheader');
    $this->pdf->AddPage('L', 'A4');
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->text(15, $this->pdf->gety(), 'No ');
      $this->pdf->text(30, $this->pdf->gety(), ': ' . $row['grno']);
			$this->pdf->text(15, $this->pdf->gety()+5, 'No SJ ');
      $this->pdf->text(30, $this->pdf->gety()+5, ': ' . $row['sjsupplier']);
			$this->pdf->text(15, $this->pdf->gety()+10, 'No PIB ');
      $this->pdf->text(30, $this->pdf->gety()+10, ': ' . $row['pibno']);
			$this->pdf->text(15, $this->pdf->gety()+15, 'No PO ');
      $this->pdf->text(30, $this->pdf->gety()+15, ': ' . $row['pono']);
      $this->pdf->text(70, $this->pdf->gety(), 'Tgl ');
      $this->pdf->text(95, $this->pdf->gety(), ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])));
			$this->pdf->text(70, $this->pdf->gety()+5, 'Supplier ');
      $this->pdf->text(95, $this->pdf->gety()+5, ': ' . $row['supplier']);
			$this->pdf->text(70, $this->pdf->gety()+10, 'No Kendaraan ');
      $this->pdf->text(95, $this->pdf->gety()+10, ': ' . $row['kendaraanno']);
			$this->pdf->text(70, $this->pdf->gety()+15, 'Supir ');
      $this->pdf->text(95, $this->pdf->gety()+15, ': ' . $row['supir']);
      $i           = 0;
      $totalqty    = 0;
	  $totalqty2    = 0;
	  $totalqty3    = 0;
      $sql1        = "select b.productcode,b.productname,a.qty,a.qty2,a.qty3,c.uomcode,g.uomcode as uom2code,
											h.uomcode as uom3code,
											a.itemnote,j.description,e.sloccode
											from grdetail a
											inner join product b on b.productid = a.productid
											inner join unitofmeasure c on c.unitofmeasureid = a.uomid
											left join unitofmeasure g on g.unitofmeasureid = a.uom2id
											left join unitofmeasure h on h.unitofmeasureid = a.uom3id
											inner join storagebin j on j.storagebinid = a.storagebinid
											inner join sloc e on e.slocid = a.slocid
											where grheaderid = " . $row['grheaderid'] . " order by grdetailid ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
			$this->pdf->text(10,$this->pdf->gety()+27,'Detail');
      $this->pdf->sety($this->pdf->gety() + 28);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
		'C',
        'C',
        'C'
      );
      $this->pdf->setFont('Arial', 'B', 7);
      $this->pdf->setwidths(array(
        7,
        80,
        20,
		20,
		20,
        15,
        22,
        40,
        30,
        20,
        20,
        20,
        20,
        20,
        20,
        20
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
				'Qty 2',
				'Qty 3',
				'Qty 4',
        'Gudang',
        'Rak',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
		'R',
		'R',
        'C',
        'R',
        'C',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      foreach ($dataReader1 as $row1) {
          $i = $i + 1;
          $this->pdf->row(array(
            $i,
           $row1['productname'],
            Yii::app()->format->formatNumber($row1['qty']).' '.$row1['uomcode'],
			Yii::app()->format->formatNumber($row1['qty2']).' '.$row1['uom2code'],
			Yii::app()->format->formatNumber($row1['qty3']).' '.$row1['uom3code'],
            $row1['sloccode'],
						$row1['description'],
            $row1['itemnote']
          ));
					
					$totalqty += $row1['qty'];
		  $totalqty2 += $row1['qty2'];
		  $totalqty3 += $row1['qty3'];
      }
					$sql1        = "select b.productcode,b.productname,a.qty,c.uomcode,d.sloccode,e.namamesin,a.description as itemnote
							from grjasa a
							inner join product b on b.productid = a.productid
							inner join unitofmeasure c on c.unitofmeasureid = a.uomid
							left join sloc d on d.slocid = a.sloctoid
							left join mesin e on e.mesinid = a.mesinid
							where grheaderid = " . $row['grheaderid'] . " order by grjasaid ";
      $command1    = $this->connection->createCommand($sql1);
			$this->pdf->text(10,$this->pdf->gety()+7,'JASA');
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 8);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
				'C',
        'C',
        'C'
      );
      $this->pdf->setFont('Arial', 'B', 7);
      $this->pdf->setwidths(array(
        7,
        80,
        20,
        22,
        25,
        30,
        20,
        20,
        20,
        20,
        20,
        20,
        20
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Gudang',
        'Mesin',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'C',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      foreach ($dataReader1 as $row1) {
          $i = $i + 1;
          $this->pdf->row(array(
            $i,
            $row1['productname'],
            Yii::app()->format->formatNumber($row1['qty']).' '.$row1['uomcode'],
            $row1['sloccode'],
						$row1['namamesin'],
            $row1['itemnote']
          ));
					
          $totalqty += $row1['qty'];
		  
      }
			$sql1        = "select b.productcode,b.productname,a.qty1,a.qty2,a.qty3,c.uomcode,g.uomcode as uom2code,
											h.uomcode as uom3code,
											a.description
											from grresult a
											inner join product b on b.productid = a.productid
											inner join unitofmeasure c on c.unitofmeasureid = a.uomid
											left join unitofmeasure g on g.unitofmeasureid = a.uom2id
											left join unitofmeasure h on h.unitofmeasureid = a.uom3id
											where grheaderid = " . $row['grheaderid'] . " order by grresultid ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
			$this->pdf->text(10,$this->pdf->gety()+7,'FG');
      $this->pdf->sety($this->pdf->gety() + 8);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setFont('Arial', 'B', 7);
      $this->pdf->setwidths(array(
        7,
        80,
        20,
				20,
				20,
        20,
        22,
        25,
        30,
        20,
        20,
        20,
        20,
        20
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
				'Qty 2',
				'Qty 3',
				'Qty 4',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
				'R',
				'R',
        'R',
        'R',
        'C',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      foreach ($dataReader1 as $row1) {
          $i = $i + 1;
          $this->pdf->row(array(
            $i,
            $row1['productname'],
            Yii::app()->format->formatNumber($row1['qty1']).' '.$row1['uomcode'],
			Yii::app()->format->formatNumber($row1['qty2']).' '.$row1['uom2code'],
			Yii::app()->format->formatNumber($row1['qty3']).' '.$row1['uom3code'],
            $row1['description']
          ));
					
					$totalqty += $row1['qty1'];
		  $totalqty2 += $row1['qty2'];
		  $totalqty3 += $row1['qty3'];
      }
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->setFont('Arial', 'B', 7);
      $this->pdf->coldetailalign = array(
        'L',
        'R',
        'R',
        'R',
        'R',
        'C',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $this->pdf->row(array(
        '',
        'TOTAL',
        Yii::app()->format->formatNumber($totalqty),
		Yii::app()->format->formatNumber($totalqty2),
		Yii::app()->format->formatNumber($totalqty3),
        '',
        '',
        '',
        ''
      ));
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        50,
        140
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Note',
        $row['headernote']
      ));
			$this->pdf->checknewpage(20);
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->text(15, $this->pdf->gety(), '  Dibuat oleh,');
      $this->pdf->text(55, $this->pdf->gety(), ' Diperiksa oleh,');
      $this->pdf->text(96, $this->pdf->gety(), ' Diketahui oleh,');
      $this->pdf->text(137, $this->pdf->gety(), '     Disetujui oleh,');
      $this->pdf->text(15, $this->pdf->gety() + 18, '........................');
      $this->pdf->text(55, $this->pdf->gety() + 18, '.........................');
      $this->pdf->text(96, $this->pdf->gety() + 18, '.........................');
      $this->pdf->text(137, $this->pdf->gety() + 18, '.................................');
      $this->pdf->text(15, $this->pdf->gety() + 20, '       Admin');
      $this->pdf->text(55, $this->pdf->gety() + 20, '    Supervisor');
      $this->pdf->text(96, $this->pdf->gety() + 20, 'Chief Accounting');
      $this->pdf->text(137, $this->pdf->gety() + 20, 'Manager Accounting');
			$this->pdf->AddPage('L', 'A4');
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    $this->menuname = 'grheaderlist';
    parent::actionDownxls();
    $sql = "select a.grheaderid,a.grno,a.grdate,i.sloccode,a.headernote,a.isjasa,d.pono,k.fullname as supplier,a.pibno,i.sloccode,
			a.kendaraanno,f.productname,e.qty,e.qty2,e.qty3,g.uomcode,l.uomcode as uom2code,m.uomcode as uom3code,
			o.description as storagedesc,c.plantcode,a.sjsupplier,a.kendaraanno,a.supir,e.itemnote,q.productname as productjasa,p.qty as qtyjasa,r.uomcode as uomjasa,
			p.reqdate,s.sloccode as slocjasa,t.kodemesin,p.description as itemjasa
			from grheader a
			left join plant c on c.plantid = a.plantid 
			left join poheader d on d.poheaderid = a.poheaderid 
			left join grdetail e on e.grheaderid = a.grheaderid 
			left join product f on f.productid = e.productid
			left join unitofmeasure g on g.unitofmeasureid = e.uomid
			left join sloc i on i.slocid = e.slocid
			left join storagebin j on j.storagebinid = e.storagebinid
			left join addressbook k on k.addressbookid = d.addressbookid
			left join unitofmeasure l on l.unitofmeasureid = e.uom2id
			left join unitofmeasure m on m.unitofmeasureid = e.uom3id
			left join storagebin o on o.storagebinid = e.storagebinid 
			left join grjasa p on p.grheaderid = a.grheaderid 
			left join product q on q.productid = p.productid 
			left join unitofmeasure r on r.unitofmeasureid = p.uomid 
			left join sloc s on s.slocid = p.sloctoid
			left join mesin t on t.mesinid = p.mesinid 
		";
		$grheaderid 		= GetSearchText(array('GET'),'grheaderid');
		$plantcode     	= GetSearchText(array('GET'),'plantcode'); 
		$grdate    			= GetSearchText(array('GET'),'grdate');
		$grno 				= GetSearchText(array('GET'),'grno');
		$pono 				= GetSearchText(array('GET'),'pono');
		$productname 				= GetSearchText(array('GET'),'productname');
		$sloccode 				= GetSearchText(array('GET'),'sloccode');
		$supplier 				= GetSearchText(array('GET'),'supplier');
		$sjsupplier 				= GetSearchText(array('GET'),'sjsupplier');
		$kendaraanno 				= GetSearchText(array('GET'),'kendaraanno');
		$supir 				= GetSearchText(array('GET'),'supir');
		$headernote 				= GetSearchText(array('GET'),'headernote');
		$sql .= " where coalesce(a.grheaderid,'') like '%".$grheaderid."%' 
			and coalesce(c.plantcode,'') like '%".$plantcode."%' 
			and coalesce(a.grdate,'') like '%".$grdate."%'
			and coalesce(a.grno,'') like '%".$grno."%' 
			and coalesce(d.pono,'') like '%".$pono."%'
			and coalesce(k.fullname,'') like '%".$supplier."%'
			and coalesce(a.sjsupplier,'') like '%".$sjsupplier."%'
			and coalesce(a.kendaraanno,'') like '%".$kendaraanno."%' 
			and coalesce(a.supir,'') like '%".$supir."%' 
			and coalesce(a.headernote,'') like '%".$headernote."%' 
		";
		(($productname != '%%')?$sql.= "
			and a.grheaderid in 
			(
				select distinct za.grheaderid 
				from grdetail za 
				left join product zb on zb.productid = za.productid 
				where coalesce(zb.productname,'') like '%".$productname."%' 
			)
		":'');
		(($sloccode != '%%')?$sql.= "
			and a.grheaderid in 
			(
				select distinct za.grheaderid 
				from grdetail za 
				left join sloc zb on zb.slocid = za.slocid 
				where coalesce(zb.sloccode,'') like '%".$sloccode."%' 
			)
		":'');
		if ($_GET['id'] !== '') {
      $sql .= " and a.grheaderid in (" . $_GET['id'] . ")";
    }
		$sql = $sql . " order by grheaderid asc ";
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 3;$nourut=0;$oldbom='';
    foreach ($dataReader as $row) {
			if ($oldbom != $row['grheaderid']) {
				$nourut+=1;
				$oldbom = $row['grheaderid'];
			}
      $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i, $nourut)
				->setCellValueByColumnAndRow(1, $i, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $i, $row['grno'])
				->setCellValueByColumnAndRow(3, $i, date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])))
				->setCellValueByColumnAndRow(4, $i, $row['isjasa'])
				->setCellValueByColumnAndRow(5, $i, $row['pono'])
				->setCellValueByColumnAndRow(6, $i, $row['supplier'])
				->setCellValueByColumnAndRow(7, $i, $row['sjsupplier'])
				->setCellValueByColumnAndRow(8, $i, $row['pibno'])
				->setCellValueByColumnAndRow(9, $i, $row['kendaraanno'])
				->setCellValueByColumnAndRow(10, $i, $row['supir'])
				->setCellValueByColumnAndRow(11, $i, $row['headernote'])
				->setCellValueByColumnAndRow(12, $i, $row['productname'])
				->setCellValueByColumnAndRow(13, $i, $row['qty'])
				->setCellValueByColumnAndRow(14, $i, $row['uomcode'])
				->setCellValueByColumnAndRow(15, $i, $row['qty2'])
				->setCellValueByColumnAndRow(16, $i, $row['uom2code'])
				->setCellValueByColumnAndRow(17, $i, $row['qty3'])
				->setCellValueByColumnAndRow(18, $i, $row['uom3code'])
				->setCellValueByColumnAndRow(21, $i, $row['sloccode'])
				->setCellValueByColumnAndRow(22, $i, $row['storagedesc'])
				->setCellValueByColumnAndRow(23, $i, $row['itemnote'])
				->setCellValueByColumnAndRow(24, $i, $row['productjasa'])
				->setCellValueByColumnAndRow(25, $i, $row['qtyjasa'])
				->setCellValueByColumnAndRow(26, $i, $row['uomjasa'])
				->setCellValueByColumnAndRow(27, $i, $row['slocjasa'])
			;
			$i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}

