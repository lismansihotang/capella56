<?php
class ReportgrController extends Controller {
  public $menuname = 'reportgr';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
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
				and (coalesce(grdate,'') like :grdate))".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				" and t.plantid in (".getUserObjectValues('plant').")".
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
    $result['total'] = $cmd;
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
				and (coalesce(grdate,'') like :grdate))".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				" and t.plantid in (".getUserObjectValues('plant').")".
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
    header('Content-Type: application/json');
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
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom4id) as uom4code,
						GetStdQty2(a.productid) as stdqty2,
						GetStdQty3(a.productid) as stdqty3,
						GetStdQty4(a.productid) as stdqty4,
						GetStock(a.productid,t.uomid,t.slocid) as qtystock,e.qty as poqty, e.qty-t.qty as sisaqty
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
				'qty4' => Yii::app()->format->formatNumber($data['qty4']),
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
}