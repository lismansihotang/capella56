<?php
class ProductController extends Controller {
	public $menuname = 'product';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexproductplant() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->searchproductplant();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexproductsales() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->searchproductsales();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$productid = GetSearchText(array('POST','Q','GET'),'productid');
		$plantid = GetSearchText(array('POST','GET'),'plantid',0,'int');
		$slocid = GetSearchText(array('POST','GET'),'slocid',0,'int');
		$addressbookid = GetSearchText(array('POST','GET'),'addressbookid',0,'int');
		$materialtypedesc = GetSearchText(array('POST','Q'),'materialtypedesc');
		$materialtypecode = GetSearchText(array('POST','Q'),'materialtypecode');
		$productcode = GetSearchText(array('POST','Q'),'productcode');
		$productname = GetSearchText(array('POST','Q'),'productname');
		$productpic = GetSearchText(array('POST','Q'),'productpic');
		$barcode = GetSearchText(array('POST','Q'),'barcode');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','t.productid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$dependency = new CDbCacheDependency('SELECT MAX(updatedate) FROM product');
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')	
				->from('product t')
				->leftjoin('materialtype a','a.materialtypeid = t.materialtypeid')
				->leftjoin('unitofmeasure b','b.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure c','c.unitofmeasureid = t.uom2')
				->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom3')
				->leftjoin('materialgroup e','e.materialgroupid = t.materialgroupid')
				->where("((t.productid like :productid) 
					or (t.productcode like :productcode) 
					or (t.productname like :productname) 
					or (a.description like :materialtypedesc) 
					or (a.materialtypecode like :materialtypecode) 
					or (t.barcode like :barcode)) 
					and t.recordstatus = 1 ",
					array(':productid'=>$productid,
						':productcode'=>$productcode,
						':materialtypedesc'=>$materialtypedesc,
						':materialtypecode'=>$materialtypecode,
						':productname'=>$productname,
							':barcode'=>$barcode))
				->queryScalar();
		} else if (isset($_GET['plant'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->selectdistinct('count(1) as total')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
				->join('materialtype b','b.materialtypeid = t.materialtypeid')
				->join('sloc c','c.slocid = a.slocid')
				->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
				->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
				->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
					or (coalesce(t.productcode,'') like :productcode) 
					or (coalesce(b.description,'') like :materialtypedesc) 
					or (coalesce(b.materialtypecode,'') like :materialtypecode) 
					or (coalesce(t.productname,'') like :productname) 
					or (coalesce(t.barcode,'') like :barcode)) 
					and t.recordstatus = 1 
					and c.plantid = ".$plantid,
						array(':productid'=>$productid,
							':productcode'=>$productcode,
							':materialtypedesc'=>$materialtypedesc,
							':materialtypecode'=>$materialtypecode,
							':productname'=>$productname,
								':barcode'=>$barcode))
				->order($sort.' '.$order)
				->queryScalar();
		} else if (isset($_GET['asset'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->selectdistinct('count(1) as total')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
				->join('materialtype b','b.materialtypeid = t.materialtypeid')
				->join('sloc c','c.slocid = a.slocid')
				->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
				->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
				->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
					or (coalesce(t.productcode,'') like :productcode) 
					or (coalesce(b.description,'') like :materialtypedesc) 
					or (coalesce(b.materialtypecode,'') like :materialtypecode) 
					or (coalesce(t.productname,'') like :productname) 
					or (coalesce(t.barcode,'') like :barcode)) 
					and t.recordstatus = 1 
					and t.isasset = 1 
					and t.isstock = 1 
					and c.plantid = ".$plantid,
						array(':productid'=>$productid,
							':productcode'=>$productcode,
							':materialtypedesc'=>$materialtypedesc,
							':materialtypecode'=>$materialtypecode,
							':productname'=>$productname,
								':barcode'=>$barcode))
				->order($sort.' '.$order)
				->queryScalar();
		} else if (isset($_GET['plantplanhp'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->selectdistinct('count(1) as total')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
				->join('materialtype b','b.materialtypeid = t.materialtypeid')
				->join('sloc c','c.slocid = a.slocid')
				->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
				->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
				->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
					or (coalesce(t.productcode,'') like :productcode) 
					or (coalesce(b.description,'') like :materialtypedesc) 
					or (coalesce(b.materialtypecode,'') like :materialtypecode) 
					or (coalesce(t.productname,'') like :productname) 
					or (coalesce(t.barcode,'') like :barcode)) 
					and t.recordstatus = 1 ".
					(($plantid != '')?" and c.plantid = ".$plantid:''),
						array(':productid'=>$productid,
							':productcode'=>$productcode,
							':materialtypedesc'=>$materialtypedesc,
							':materialtypecode'=>$materialtypecode,
							':productname'=>$productname,':barcode'=>$barcode))
				->order($sort.' '.$order)
				->queryScalar();
		} else if (isset($_GET['plantplanbp'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->selectdistinct('count(1) as total')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
				->join('materialtype b','b.materialtypeid = t.materialtypeid')
				->join('sloc c','c.slocid = a.slocid')
				->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
				->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
				->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
					or (coalesce(t.productcode,'') like :productcode) 
					or (coalesce(b.description,'') like :materialtypedesc) 
					or (coalesce(b.materialtypecode,'') like :materialtypecode) 
					or (coalesce(t.productname,'') like :productname) 
					or (coalesce(t.barcode,'') like :barcode)) 
					and t.recordstatus = 1 
					and b.materialtypeid in (1,2,4,7) 
					and c.plantid = ".$plantid,
						array(':productid'=>$productid,
							':productcode'=>$productcode,
							':materialtypedesc'=>$materialtypedesc,
							':materialtypecode'=>$materialtypecode,
							':productname'=>$productname,':barcode'=>$barcode))
				->order($sort.' '.$order)
				->queryScalar();
		} else if (isset($_GET['plantfg'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->selectdistinct('count(1) as total')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
				->join('materialtype b','b.materialtypeid = t.materialtypeid')
				->join('sloc c','c.slocid = a.slocid')
				->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
				->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
				->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
					or (coalesce(t.productcode,'') like :productcode) 
					or (coalesce(b.description,'') like :materialtypedesc) 
					or (coalesce(b.materialtypecode,'') like :materialtypecode) 
					or (coalesce(t.productname,'') like :productname) 
					or (coalesce(t.barcode,'') like :barcode)) 
					and t.recordstatus = 1 
					and b.fg = 1 
					and c.plantid = ".$plantid,
						array(':productid'=>$productid,
							':productcode'=>$productcode,
							':materialtypedesc'=>$materialtypedesc,
							':materialtypecode'=>$materialtypecode,
							':productname'=>$productname,':barcode'=>$barcode))
				->order($sort.' '.$order)
				->queryScalar();
		} else if (isset($_GET['plantpo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->selectdistinct('count(1) as total')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
				->join('materialtype b','b.materialtypeid = t.materialtypeid')
				->join('sloc c','c.slocid = a.slocid')
				->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
        ->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
        ->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
					or (coalesce(t.productcode,'') like :productcode) 
					or (coalesce(b.description,'') like :materialtypedesc) 
					or (coalesce(b.materialtypecode,'') like :materialtypecode) 
					or (coalesce(t.productname,'') like :productname) 
					or (coalesce(t.barcode,'') like :barcode)) 
					and t.recordstatus = 1 
					and c.plantid = ".$plantid,
						array(':productid'=>$productid,
							':productcode'=>$productcode,
							':materialtypedesc'=>$materialtypedesc,
							':materialtypecode'=>$materialtypecode,
							':productname'=>$productname,':barcode'=>$barcode))
				->order($sort.' '.$order)
				->queryScalar();
		} else  if (isset($_GET['waste'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->selectdistinct('count(1) as total')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
				->join('materialgroup d','d.materialgroupid = a.materialgroupid')
				->join('materialtype b','b.materialtypeid = t.materialtypeid')
				->join('sloc c','c.slocid = a.slocid')
				->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
        ->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
        ->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
					or (coalesce(t.productcode,'') like :productcode) 
					or (coalesce(b.description,'') like :materialtypedesc) 
					or (coalesce(b.materialtypecode,'') like :materialtypecode) 
					or (coalesce(t.productname,'') like :productname) 
					or (coalesce(t.barcode,'') like :barcode)) 
					and t.recordstatus = 1 
					and d.materialgroupid in (282,283,284)
					and c.plantid = ".$plantid,
						array(':productid'=>$productid,
							':productcode'=>$productcode,
							':materialtypedesc'=>$materialtypedesc,
							':materialtypecode'=>$materialtypecode,
							':productname'=>$productname,':barcode'=>$barcode))
				->order($sort.' '.$order)
				->queryScalar();
		} else if (isset($_GET['trx'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')	
				->from('product t')
				->leftjoin('productstock a','a.productid = t.productid')
				->join('sloc b','b.slocid = a.slocid')
				->join('storagebin c','c.storagebinid = a.storagebinid')
				->join('materialtype d','d.storagebinid = a.storagebinid')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom2')
        ->leftjoin('unitofmeasure g','g.unitofmeasureid = t.uom3')
        ->leftjoin('materialgroup h','h.materialgroupid = t.materialgroupid')
				->where("((t.productid like :productid) 
					or (t.productcode like :productcode)
					or (d.description like :materialtypedesc) 
					or (d.materialtypecode like :materialtypecode) 
					or (t.productname like :productname) 
					or (t.barcode like :barcode)) 
						and t.recordstatus = 1 
						and a.slocid in (".getUserObjectValues('sloc').")",
						array(':productid'=>$productid,
							':materialtypedesc'=>$materialtypedesc,
							':materialtypecode'=>$materialtypecode,
							':productcode'=>$productcode,
							':productname'=>$productname,
							':barcode'=>$barcode
							))
				->queryScalar();
		} else if (isset($_GET['productplant'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->selectdistinct('count(1) as total')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
        ->join('materialtype b','b.materialtypeid = t.materialtypeid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
        ->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
        ->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
						or (coalesce(b.description,'') like :materialtypedesc)
						or (coalesce(b.materialtypecode,'') like :materialtypecode)
						or (coalesce(t.productcode,'') like :productcode)
						or (coalesce(t.productname,'') like :productname) 
						or (coalesce(t.barcode,'') like :barcode)) 
						and t.recordstatus = 1
						and t.isstock = 1",
						array(':productid'=>$productid,
							':materialtypedesc'=>$materialtypedesc,
							':materialtypecode'=>$materialtypecode,
							':productcode'=>$productcode,
							':productname'=>$productname,
							':barcode'=>$barcode
							))
				->queryScalar();
		} else if (isset($_GET['grretur'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->selectdistinct('count(1) as total')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
				->join('materialtype b','b.materialtypeid = t.materialtypeid')
        ->join('grdetail c','c.productid = t.productid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
        ->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
        ->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
						or (coalesce(b.description,'') like :materialtypedesc)
						or (coalesce(b.materialtypecode,'') like :materialtypecode)
						or (coalesce(t.productcode,'') like :productcode)
						or (coalesce(t.productname,'') like :productname) 
						or (coalesce(t.barcode,'') like :barcode)) 
						and t.recordstatus = 1
						and t.isstock = 1
					and c.grheaderid = ".$_GET['grheaderid']."
						and a.slocid in (".getUserObjectValues('sloc').")",
						array(':productid'=>$productid,
							':materialtypedesc'=>$materialtypedesc,
							':materialtypecode'=>$materialtypecode,
							':productcode'=>$productcode,
							':productname'=>$productname,
							':barcode'=>$barcode
							))
				->queryScalar();
		} else if (isset($_GET['giretur'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->selectdistinct('count(1) as total')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
				->join('materialtype b','b.materialtypeid = t.materialtypeid')
        ->join('gidetail c','c.productid = t.productid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
        ->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
        ->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
						or (coalesce(b.description,'') like :materialtypedesc)
						or (coalesce(b.materialtypecode,'') like :materialtypecode)
						or (coalesce(t.productcode,'') like :productcode)
						or (coalesce(t.productname,'') like :productname) 
						or (coalesce(t.barcode,'') like :barcode)) 
						and t.recordstatus = 1
						and t.isstock = 1
					and c.giheaderid = ".$_GET['giheaderid']."
						and a.slocid in (".getUserObjectValues('sloc').")",
						array(':productid'=>$productid,
							':materialtypedesc'=>$materialtypedesc,
							':materialtypecode'=>$materialtypecode,
							':productcode'=>$productcode,
							':productname'=>$productname,
							':barcode'=>$barcode
							))
				->queryScalar();
		} else if (isset($_GET['plantcuststock'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->selectdistinct('count(1) as total')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
        ->join('materialtype b','b.materialtypeid = t.materialtypeid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
        ->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
        ->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
						or (coalesce(b.description,'') like :materialtypedesc)
						or (coalesce(b.materialtypecode,'') like :materialtypecode)
						or (coalesce(t.productcode,'') like :productcode)
						or (coalesce(t.productname,'') like :productname) 
						or (coalesce(t.barcode,'') like :barcode)) 
						and t.recordstatus = 1
						and t.isstock = 1
						and a.slocid in (".getUserObjectValues('sloc').")",
						array(':productid'=>$productid,
							':materialtypedesc'=>$materialtypedesc,
							':materialtypecode'=>$materialtypecode,
							':addressbookid'=>$addressbookid,
							':productcode'=>$productcode,
							':productname'=>$productname,
							':barcode'=>$barcode
							))
				->queryScalar();
		} else if (isset($_GET['productplantjasa'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->selectdistinct('count(1) as total')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
        ->join('materialtype b','b.materialtypeid = t.materialtypeid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
        ->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
        ->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
						or (coalesce(b.description,'') like :materialtypedesc)
						or (coalesce(b.materialtypecode,'') like :materialtypecode)
						or (coalesce(t.productcode,'') like :productcode)
						or (coalesce(t.productname,'') like :productname) 
						or (coalesce(t.barcode,'') like :barcode)) 
						and t.recordstatus = 1
						and t.isstock = 0 ",
						array(':productid'=>$productid,
							':materialtypedesc'=>$materialtypedesc,
							':materialtypecode'=>$materialtypecode,
							':productcode'=>$productcode,
							':productname'=>$productname,
							':barcode'=>$barcode
							))
				->queryScalar();
		} else if (isset($_GET['invapumum'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->selectdistinct('count(1) as total')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
        ->join('materialtype b','b.materialtypeid = t.materialtypeid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
        ->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
        ->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
						or (coalesce(b.description,'') like :materialtypedesc)
						or (coalesce(b.materialtypecode,'') like :materialtypecode)
						or (coalesce(t.productcode,'') like :productcode)
						or (coalesce(t.productname,'') like :productname) 
						or (coalesce(t.barcode,'') like :barcode)) 
						and t.recordstatus = 1
						and t.isstock = 0 
						and a.slocid = ".$slocid." 
						and a.slocid in (".getUserObjectValues('sloc').")",
						array(':productid'=>$productid,
							':materialtypedesc'=>$materialtypedesc,
							':materialtypecode'=>$materialtypecode,
							':productcode'=>$productcode,
							':productname'=>$productname,
							':barcode'=>$barcode
							))
				->queryScalar();
		} else {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')	
				->from('product t')
        ->leftjoin('materialtype a','a.materialtypeid = t.materialtypeid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
        ->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
        ->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("(coalesce(t.productid,'') like :productid) 
					and (coalesce(t.productcode,'') like :productcode) 
					and (coalesce(a.description,'') like :materialtypedesc) 
					and (coalesce(a.materialtypecode,'') like :materialtypecode) 
					and (coalesce(t.productname,'') like :productname) 
					and (coalesce(t.barcode,'') like :barcode)
					",
					array(':productid'=>$productid,
						':productcode'=>$productcode,
						':productname'=>$productname,
						':materialtypedesc'=>$materialtypedesc,
						':materialtypecode'=>$materialtypecode,
							':barcode'=>$barcode))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
        ->select('t.*, (null) as sloccode,(null) as slocdesc, (null) as rak, a.description,t.productcode,a.materialtypecode,t.productname, 
        a.materialtypeid,(null) as grdetailid,(null) as gidetailid,d.uomcode as uom1code,e.uomcode as uom2code,f.uomcode as uom3code,g.materialgroupcode')	
				->from('product t')
        ->leftjoin('materialtype a','a.materialtypeid = t.materialtypeid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
        ->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
        ->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where('((t.productid like :productid) or (t.productcode like :productcode) or (t.productname like :productname) or (t.barcode like :barcode)) 
					and t.recordstatus = 1 ',
					array(':productid'=>$productid,':productcode'=>$productcode,':productname'=>$productname,
							':barcode'=>$barcode))
				->order($sort.' '.$order)
				->queryAll();
		} else if (isset($_GET['plant'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
        ->selectdistinct('t.*,b.materialtypecode,
        (null) as sloccode,
        (null) as slocdesc,(null) as rak, (null) as materialtypeid, (null) as description,(null) as grdetailid,(null) as gidetailid,
        d.uomcode as uom1code, e.uomcode as uom2code, f.uomcode as uom3cod,g.materialgroupcode')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
				->join('materialtype b','b.materialtypeid = t.materialtypeid')
        ->join('sloc c','c.slocid = a.slocid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
        ->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
        ->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
					or (coalesce(b.description,'') like :materialtypedesc) 
					or (coalesce(b.materialtypecode,'') like :materialtypecode) 
					or (coalesce(t.productcode,'') like :productcode) 
					or (coalesce(t.productname,'') like :productname) 
					or (coalesce(t.barcode,'') like :barcode)) 
					and t.recordstatus = 1 
					and c.plantid = ".$plantid,
						array(':productid'=>$productid,
						':materialtypedesc'=>$materialtypedesc,
						':materialtypecode'=>$materialtypecode,
						':productcode'=>$productcode,
						':productname'=>$productname,
								':barcode'=>$barcode))
				->order($sort.' '.$order)
				->queryAll();
		} else if (isset($_GET['asset'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
        ->selectdistinct('t.*,b.materialtypecode,
        (null) as sloccode,(null) as slocdesc,(null) as rak, 
        (null) as materialtypeid, (null) as description,(null) as grdetailid,(null) as gidetailid,
        d.uomcode as uom1code, e.uomcode as uom2code, f.uomcode as uom3code,g.materialgroupcode')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
				->join('materialtype b','b.materialtypeid = t.materialtypeid')
        ->join('sloc c','c.slocid = a.slocid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
				->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
				->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
					or (coalesce(b.description,'') like :materialtypedesc) 
					or (coalesce(b.materialtypecode,'') like :materialtypecode) 
					or (coalesce(t.productcode,'') like :productcode) 
					or (coalesce(t.productname,'') like :productname) 
					or (coalesce(t.barcode,'') like :barcode)) 
					and t.recordstatus = 1 
					and t.isasset = 1
					and t.isstock = 1
					and c.plantid = ".$plantid,
						array(':productid'=>$productid,
						':materialtypedesc'=>$materialtypedesc,
						':materialtypecode'=>$materialtypecode,
						':productcode'=>$productcode,
						':productname'=>$productname,
								':barcode'=>$barcode))
				->order($sort.' '.$order)
				->queryAll();
		} else  if (isset($_GET['plantplanhp'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
        ->selectdistinct('t.*,b.materialtypecode,
        (null) as sloccode,(null) as slocdesc,(null) as rak, 
        (null) as materialtypeid, (null) as description,(null) as grdetailid,(null) as gidetailid,t.qty1,t.qty2,t.qty3,
        d.uomcode as uom1code, e.uomcode as uom2code, f.uomcode as uom3code,g.materialgroupcode')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
				->join('materialtype b','b.materialtypeid = t.materialtypeid')
        ->join('sloc c','c.slocid = a.slocid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
				->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
				->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
					or (coalesce(t.productcode,'') like :productcode) 
					or (coalesce(b.description,'') like :materialtypedesc) 
					or (coalesce(b.materialtypecode,'') like :materialtypecode) 
					or (coalesce(t.productname,'') like :productname) 
					or (coalesce(t.barcode,'') like :barcode)) 
					and t.recordstatus = 1 ".
					(($plantid != '')?" and c.plantid = ".$plantid:''),						
					array(':productid'=>$productid,
							':productcode'=>$productcode,
							':materialtypedesc'=>$materialtypedesc,
							':materialtypecode'=>$materialtypecode,
							':productname'=>$productname,':barcode'=>$barcode))
				->order($sort.' '.$order)
				->queryAll();
		}
		else if (isset($_GET['plantplanbp'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
        ->selectdistinct('t.*,b.materialtypecode,
        (null) as sloccode,(null) as slocdesc,(null) as rak, 
        (null) as materialtypeid, (null) as description,(null) as grdetailid,(null) as gidetailid,
        d.uomcode as uom1code, e.uomcode as uom2code, f.uomcode as uom3code,g.materialgroupcode')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
				->join('materialtype b','b.materialtypeid = t.materialtypeid')
        ->join('sloc c','c.slocid = a.slocid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
				->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
				->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
					or (coalesce(t.productcode,'') like :productcode) 
					or (coalesce(b.description,'') like :materialtypedesc) 
					or (coalesce(b.materialtypecode,'') like :materialtypecode) 
					or (coalesce(t.productname,'') like :productname) 
					or (coalesce(t.barcode,'') like :barcode)) 
					and t.recordstatus = 1 
					and b.materialtypeid in (1,2,4,7)
					and c.plantid = ".$plantid,
						array(':productid'=>$productid,
							':productcode'=>$productcode,
							':materialtypedesc'=>$materialtypedesc,
							':materialtypecode'=>$materialtypecode,
							':productname'=>$productname,':barcode'=>$barcode))
				->order($sort.' '.$order)
				->queryAll();
		}
		else
			if (isset($_GET['plantfg'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
        ->selectdistinct('t.*,b.materialtypecode,
        (null) as sloccode,(null) as slocdesc,(null) as rak, 
        (null) as materialtypeid, (null) as description,(null) as grdetailid,(null) as gidetailid,
        d.uomcode as uom1code, e.uomcode as uom2code, f.uomcode as uom3code,g.materialgroupcode')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
				->join('materialtype b','b.materialtypeid = t.materialtypeid')
        ->join('sloc c','c.slocid = a.slocid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
				->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
				->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
					or (coalesce(t.productcode,'') like :productcode) 
					or (coalesce(b.description,'') like :materialtypedesc) 
					or (coalesce(b.materialtypecode,'') like :materialtypecode) 
					or (coalesce(t.productname,'') like :productname) 
					or (coalesce(t.barcode,'') like :barcode)) 
					and t.recordstatus = 1 
					and b.FG = 1 
					and c.plantid = ".$plantid,
						array(':productid'=>$productid,
							':productcode'=>$productcode,
							':materialtypedesc'=>$materialtypedesc,
							':materialtypecode'=>$materialtypecode,
							':productname'=>$productname,
							':barcode'=>$barcode))
				->order($sort.' '.$order)
				->queryAll();
		}
		else
			if (isset($_GET['plantpo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
        ->selectdistinct('t.*,b.materialtypecode,
        (null) as sloccode,(null) as slocdesc,(null) as rak, 
        (null) as materialtypeid, (null) as description,(null) as grdetailid,(null) as gidetailid,
        d.uomcode as uom1code, e.uomcode as uom2code, f.uomcode as uom3code,g.materialgroupcode')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
				->join('materialtype b','b.materialtypeid = t.materialtypeid')
        ->join('sloc c','c.slocid = a.slocid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
				->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
				->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
					or (coalesce(t.productcode,'') like :productcode) 
					or (coalesce(b.description,'') like :materialtypedesc) 
					or (coalesce(b.materialtypecode,'') like :materialtypecode) 
					or (coalesce(t.productname,'') like :productname) 
					or (coalesce(t.barcode,'') like :barcode)) 
					and t.recordstatus = 1 
					and c.plantid = ".$plantid,
						array(':productid'=>$productid,
							':productcode'=>$productcode,
							':materialtypedesc'=>$materialtypedesc,
							':materialtypecode'=>$materialtypecode,
							':productname'=>$productname,
							':barcode'=>$barcode))
				->order($sort.' '.$order)
				->queryAll();
		}
		else 
			if (isset($_GET['waste'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
        ->selectdistinct('t.*,b.materialtypecode,
        (null) as sloccode,(null) as slocdesc,(null) as rak, 
        (null) as materialtypeid, (null) as description,(null) as grdetailid,(null) as gidetailid,
        d.uomcode as uom1code, e.uomcode as uom2code, f.uomcode as uom3code,g.materialgroupcode')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
				->join('materialtype b','b.materialtypeid = t.materialtypeid')
				->join('materialgroup d','d.materialgroupid = a.materialgroupid')
        ->join('sloc c','c.slocid = a.slocid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
				->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
				->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
					or (coalesce(t.productcode,'') like :productcode) 
					or (coalesce(b.description,'') like :materialtypedesc) 
					or (coalesce(b.materialtypecode,'') like :materialtypecode) 
					or (coalesce(t.productname,'') like :productname) 
					or (coalesce(t.barcode,'') like :barcode)) 
					and t.recordstatus = 1 
					and d.materialgroupid in (282,283,284)
					and c.plantid = ".$plantid,
						array(':productid'=>$productid,
							':productcode'=>$productcode,
							':materialtypedesc'=>$materialtypedesc,
							':materialtypecode'=>$materialtypecode,
							':productname'=>$productname,':barcode'=>$barcode))
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		if (isset($_GET['trx'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
        ->select('t.*,b.materialtypecode,
        b.sloccode,b.description as slocdesc,c.description as rak, 
        (null) as materialtypeid, (null) as description,(null) as grdetailid,(null) as gidetailid,
        d.uomcode as uom1code, e.uomcode as uom2code, f.uomcode as uom3code,h.materialgroupcode')	
				->from('product t')
				->leftjoin('productstock a','a.productid = t.productid')
				->join('sloc b','b.slocid = a.slocid')
				->join('storagebin c','c.storagebinid = a.storagebinid')
        ->join('materialtype d','d.materialtypeid = a.materialtypeid')
        ->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom2')
				->leftjoin('unitofmeasure g','g.unitofmeasureid = t.uom3')
				->leftjoin('materialgroup h','h.materialgroupid = t.materialgroupid')
				->where("((t.productid like :productid) 
				or (d.description like :materialtypedesc) 
				or (d.materialtypecode like :materialtypecode) 
				or (t.productcode like :productcode) 
				or (t.productname like :productname) 
				or (t.barcode like :barcode)) 
					and t.recordstatus = 1 and
					a.slocid in (".getUserObjectValues('sloc').")",
						array(':productid'=>$productid,
							':materialtypedesc'=>$materialtypedesc,
							':materialtypecode'=>$materialtypecode,
							':productcode'=>$productcode,
							':productname'=>$productname,
								':barcode'=>$barcode
								))
				->order($sort.' '.$order)
				->queryAll();
		} else if (isset($_GET['productplant'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
        ->selectdistinct('t.*,b.materialtypecode,
        0 as qty,(null) as sloccode,(null) as slocdesc,
        (null) as rak, (null) as materialtypeid, (null) as description,(null) as grdetailid,
        (null) as gidetailid,d.uomcode as uom1code, e.uomcode as uom2code, 
        f.uomcode as uom3code,g.materialgroupcode')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
        ->join('materialtype b','b.materialtypeid = t.materialtypeid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
				->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
				->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
					or (coalesce(b.description,'') like :materialtypedesc) 
					or (coalesce(b.materialtypecode,'') like :materialtypecode) 
					or (coalesce(t.productcode,'') like :productcode) 
					or (coalesce(t.productname,'') like :productname) 
					or (coalesce(t.barcode,'') like :barcode)) 
					and t.recordstatus = 1
					and t.isstock = 1 					
					and a.slocid = ".$slocid,
						array(':productid'=>$productid,
						':materialtypedesc'=>$materialtypedesc,
						':materialtypecode'=>$materialtypecode,
						':productcode'=>$productcode,
						':productname'=>$productname,
								':barcode'=>$barcode))
				->order($sort.' '.$order)
				->queryAll();
		} else if (isset($_GET['grretur'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
        ->selectdistinct('t.*,b.materialtypecode,
        0 as qty,(null) as sloccode,(null) as slocdesc,(null) as rak, 
        (null) as materialtypeid, (null) as description, c.grdetailid,(null) as gidetailid,
        d.uomcode as uom1code, e.uomcode as uom2code, f.uomcode as uom3code,g.materialgroupcode')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
				->join('materialtype b','b.materialtypeid = t.materialtypeid')
        ->join('grdetail c','c.productid = t.productid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
				->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
				->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
					or (coalesce(b.description,'') like :materialtypedesc) 
					or (coalesce(b.materialtypecode,'') like :materialtypecode) 
					or (coalesce(t.productcode,'') like :productcode) 
					or (coalesce(t.productname,'') like :productname) 
					or (coalesce(t.barcode,'') like :barcode)) 
					and t.recordstatus = 1 
					and t.isstock = 1 
					and c.grheaderid = ".$_GET['grheaderid']."
					and a.slocid in (".getUserObjectValues('sloc').")",
						array(':productid'=>$productid,
						':materialtypedesc'=>$materialtypedesc,
						':materialtypecode'=>$materialtypecode,
						':productcode'=>$productcode,
						':productname'=>$productname,
								':barcode'=>$barcode))
				->order($sort.' '.$order)
				->queryAll();
		} else if (isset($_GET['giretur'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
        ->selectdistinct('t.*,b.materialtypecode,
        0 as qty,(null) as sloccode,(null) as slocdesc,(null) as rak, 
        (null) as materialtypeid, (null) as description,(null) as grdetailid, c.gidetailid,
        d.uomcode as uom1code, e.uomcode as uom2code, f.uomcode as uom3code,g.materialgroupcode')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
				->join('materialtype b','b.materialtypeid = t.materialtypeid')
        ->join('gidetail c','c.productid = t.productid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
				->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
				->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
					or (coalesce(b.description,'') like :materialtypedesc) 
					or (coalesce(b.materialtypecode,'') like :materialtypecode) 
					or (coalesce(t.productcode,'') like :productcode) 
					or (coalesce(t.productname,'') like :productname) 
					or (coalesce(t.barcode,'') like :barcode)) 
					and t.recordstatus = 1 
					and t.isstock = 1 
					and c.giheaderid = ".$_GET['giheaderid']."
					and a.slocid in (".getUserObjectValues('sloc').")",
						array(':productid'=>$productid,
						':materialtypedesc'=>$materialtypedesc,
						':materialtypecode'=>$materialtypecode,
						':productcode'=>$productcode,
						':productname'=>$productname,
								':barcode'=>$barcode))
				->order($sort.' '.$order)
				->queryAll();
		} else if (isset($_GET['plantcuststock'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
        ->selectdistinct('t.*,b.materialtypecode,
        0 as qty,(null) as sloccode,(null) as slocdesc,(null) as rak, 
        (null) as materialtypeid, (null) as description,(null) as grdetailid, (null) as gidetailid,
        d.uomcode as uom1code, e.uomcode as uom2code, f.uomcode as uom3code,g.materialgroupcode')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
        ->join('materialtype b','b.materialtypeid = t.materialtypeid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
				->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
				->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
					or (coalesce(b.description,'') like :materialtypedesc) 
					or (coalesce(b.materialtypecode,'') like :materialtypecode) 
					or (coalesce(t.productcode,'') like :productcode) 
					or (coalesce(t.productname,'') like :productname) 
					or (coalesce(t.barcode,'') like :barcode)) 
					and t.recordstatus = 1 
					and t.isstock = 1 
					and a.slocid in (".getUserObjectValues('sloc').")",
						array(':productid'=>$productid,
						':materialtypedesc'=>$materialtypedesc,
						':materialtypecode'=>$materialtypecode,
						':productcode'=>$productcode,
						':productname'=>$productname,
								':barcode'=>$barcode))
				->order($sort.' '.$order)
				->queryAll();
		} else if (isset($_GET['productplantjasa'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
        ->selectdistinct('t.*,b.materialtypecode,
        0 as qty,(null) as sloccode,(null) as slocdesc,(null) as rak, 
        (null) as materialtypeid, (null) as description,(null) as grdetailid,(null) as gidetailid,
        d.uomcode as uom1code, e.uomcode as uom2code, f.uomcode as uom3code,g.materialgroupcode')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
        ->join('materialtype b','b.materialtypeid = t.materialtypeid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
				->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
				->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
					or (coalesce(b.description,'') like :materialtypedesc) 
					or (coalesce(b.materialtypecode,'') like :materialtypecode) 
					or (coalesce(t.productcode,'') like :productcode) 
					or (coalesce(t.productname,'') like :productname) 
					or (coalesce(t.barcode,'') like :barcode)) 
					and t.recordstatus = 1
					and t.isstock = 0 					
					and a.slocid in (".getUserObjectValues('sloc').")",
						array(':productid'=>$productid,
						':materialtypedesc'=>$materialtypedesc,
						':materialtypecode'=>$materialtypecode,
						':productcode'=>$productcode,
						':productname'=>$productname,
								':barcode'=>$barcode))
				->order($sort.' '.$order)
				->queryAll();
		} else if (isset($_GET['invapumum'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
        ->selectdistinct('t.*,b.materialtypecode,
        0 as qty,(null) as sloccode,(null) as slocdesc,(null) as rak, 
        (null) as materialtypeid, (null) as description,(null) as grdetailid,(null) as gidetailid,
        d.uomcode as uom1code, e.uomcode as uom2code, f.uomcode as uom3code,g.materialgroupcode')	
				->from('product t')
				->join('productplant a','a.productid = t.productid')
        ->join('materialtype b','b.materialtypeid = t.materialtypeid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
				->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
				->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("((coalesce(t.productid,'') like :productid) 
					or (coalesce(b.description,'') like :materialtypedesc) 
					or (coalesce(t.productcode,'') like :productcode) 
					or (coalesce(t.productname,'') like :productname) 
					or (coalesce(t.barcode,'') like :barcode)) 
					and t.recordstatus = 1
					and t.isstock = 0 					
					and a.slocid = ".$slocid."  
					and a.slocid in (".getUserObjectValues('sloc').")",
						array(':productid'=>$productid,
						':materialtypedesc'=>$materialtypedesc,
						':materialtypecode'=>$materialtypecode,
						':productcode'=>$productcode,
						':productname'=>$productname,
								':barcode'=>$barcode))
				->order($sort.' '.$order)
				->queryAll();
		} 
		else
		{
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
        ->select("t.*, (0) as qty, (null) as sloccode,(null) as slocdesc,(null) as rak, a.description,
        a.materialtypeid,a.materialtypecode,(null) as grdetailid,(null) as gidetailid,
        d.uomcode as uom1code, e.uomcode as uom2code, f.uomcode as uom3code,g.materialgroupcode")	
				->from('product t')
        ->leftjoin('materialtype a','a.materialtypeid = t.materialtypeid')
        ->leftjoin('unitofmeasure d','d.unitofmeasureid = t.uom1')
				->leftjoin('unitofmeasure e','e.unitofmeasureid = t.uom2')
				->leftjoin('unitofmeasure f','f.unitofmeasureid = t.uom3')
				->leftjoin('materialgroup g','g.materialgroupid = t.materialgroupid')
				->where("(coalesce(t.productid,'') like :productid) 
					and (coalesce(t.productcode,'') like :productcode) 
					and (coalesce(a.description,'') like :materialtypedesc) 
					and (coalesce(a.materialtypecode,'') like :materialtypecode) 
					and (coalesce(t.productname,'') like :productname) 
					and (coalesce(t.barcode,'') like :barcode)",
					array(':productid'=>$productid,
						':materialtypedesc'=>$materialtypedesc,
						':materialtypecode'=>$materialtypecode,
						':productcode'=>$productcode,
						':productname'=>$productname,
							':barcode'=>$barcode))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'productid'=>$data['productid'],
				'isstock'=>$data['isstock'],
				'isasset'=>$data['isasset'],
				'qty'=>Yii::app()->format->formatNumber($data['qty']),
				'sloccode'=>$data['sloccode'],
				'materialtypeid'=>$data['materialtypeid'],
				'materialtypecode'=>$data['materialtypecode'],
				'description'=>$data['description'],
				'slocdesc'=>$data['slocdesc'],
				'rak'=>$data['rak'],
				'grdetailid'=>$data['grdetailid'],
				'gidetailid'=>$data['gidetailid'],
				'productcode'=>$data['productcode'],
				'productname'=>$data['productname'],
				'productpic'=>$data['productpic'],
				'barcode'=>$data['barcode'],
				'thickness'=>Yii::app()->format->formatNumber($data['thickness']),
				'width'=>Yii::app()->format->formatNumber($data['width']),
				'length'=>Yii::app()->format->formatNumber($data['length']),
				'qty1'=>Yii::app()->format->formatNumber($data['qty1']),
				'uom1'=>$data['uom1'],
				'uom1code'=>$data['uom1code'],
				'qty2'=>Yii::app()->format->formatNumber($data['qty2']),
				'uom2'=>$data['uom2'],
				'uom2code'=>$data['uom2code'],
				'qty3'=>Yii::app()->format->formatNumber($data['qty3']),
				'uom3'=>$data['uom3'],
				'uom3code'=>$data['uom3code'],
				'materialgroupid'=>$data['materialgroupid'],
				'materialgroupcode'=>$data['materialgroupcode'],
				'sled'=>$data['sled'],
				'isautolot'=>$data['isautolot'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchproductplant() {
		header('Content-Type: application/json');
		$id = 0;	
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
		}
		else 
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
		}
		$productplantid = GetSearchText(array('POST','Q'),'productplantid');
		$productid = GetSearchText(array('POST','Q'),'productid',0,'int');
		$productcode = GetSearchText(array('POST','Q'),'productcode');
		$productname = GetSearchText(array('POST','Q'),'productname');
		$sloc = GetSearchText(array('POST','Q'),'sloc');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','productplantid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('productplant t')
			->leftjoin('product p','p.productid=t.productid')
			->leftjoin('sloc r','r.slocid=t.slocid')
			->where("t.productid = :productid",
					array(':productid'=>$id))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,p.productcode,p.productname,r.sloccode')	
			->from('productplant t')
			->leftjoin('product p','p.productid=t.productid')
			->leftjoin('sloc r','r.slocid=t.slocid')
			->where("t.productid = :productid",
					array(':productid'=>$id))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'productplantid'=>$data['productplantid'],
				'productid'=>$data['productid'],
				'productcode'=>$data['productcode'],
				'productname'=>$data['productname'],
				'slocid'=>$data['slocid'],
				'sloccode'=>$data['sloccode'],
				'issource'=>$data['issource'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchproductsales() {
		header('Content-Type: application/json');
		$id = 0;	
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
		}
		else 
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
		}
		$productsalesid = GetSearchText(array('POST','Q'),'productsalesid');
		$productname = GetSearchText(array('POST','Q'),'productname');
		$currencyname = GetSearchText(array('POST','Q'),'currencyname');
		$currencyvalue = GetSearchText(array('POST','Q'),'currencyvalue');
		$pricecategory = GetSearchText(array('POST','Q'),'pricecategory');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','productsalesid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('productsales t')
			->leftjoin('product p','p.productid=t.productid')
			->leftjoin('currency q','q.currencyid=t.currencyid')
			->leftjoin('pricecategory r','r.pricecategoryid=t.pricecategoryid')
			->leftjoin('unitofmeasure s','s.unitofmeasureid=t.uomid')
			->where("t.productid = :productid",
					array(':productid'=>$id))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select()	
			->from('productsales t')
			->leftjoin('product p','p.productid=t.productid')
			->leftjoin('currency q','q.currencyid=t.currencyid')
			->leftjoin('pricecategory r','r.pricecategoryid=t.pricecategoryid')
			->leftjoin('unitofmeasure s','s.unitofmeasureid=t.uomid')
			->where("t.productid = :productid",
					array(':productid'=>$id))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'productsalesid'=>$data['productsalesid'],
				'productid'=>$data['productid'],
				'productname'=>$data['productname'],
				'productcode'=>$data['productcode'],
				'currencyid'=>$data['currencyid'],
				'currencyname'=>$data['currencyname'],
				'currencyvalue'=>Yii::app()->format->formatNumber($data['currencyvalue']),
				'pricecategoryid'=>$data['pricecategoryid'],
				'categoryname'=>$data['categoryname'],
				'uomid'=>$data['uomid'],
				'uomcode'=>$data['uomcode'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
  public function actionGetData() {
		$id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'productid' => $id
		));
	}
	public function actionGetProductPlant() {
		$productid = GetSearchText(array('POST','Q'),'productid',0,'int');
		$issource = GetSearchText(array('POST','Q'),'issource',0,'int');
		$cmd = Yii::app()->db->createCommand("
			select b.productid,b.uom1,c.uomcode as uom1code,b.uom2,d.uomcode as uom2code,b.uom3,e.uomcode as uom3code,
				b.productname,a.productid,b.productcode,b.qty1,b.qty2,b.qty3,
				g.bomid, g.bomversion,g.processprdid,g.mesinid,a.slocid
			from productplant a 
			join product b on b.productid = a.productid 
			left join unitofmeasure c on c.unitofmeasureid = b.uom1 
			left join unitofmeasure d on d.unitofmeasureid = b.uom2
			left join unitofmeasure e on e.unitofmeasureid = b.uom3
			left join billofmaterial g on g.productid = a.productid 
			where a.productid = ".$productid." and issource = ".$issource." limit 1"
		)->queryRow();
		echo CJSON::encode(array(
			'productid' => $cmd['productid'],
			'productcode' => $cmd['productcode'],
			'productname' => $cmd['productname'],
			'uom1' => $cmd['uom1'],
			'uom1code' => $cmd['uom1code'],
			'uom2' => $cmd['uom2'],
			'uom2code' => $cmd['uom2code'],
			'uom3' => $cmd['uom3'],
			'uom3code' => $cmd['uom3code'],
			'qty1' => $cmd['qty1'],
			'qty2' => $cmd['qty2'],
			'qty3' => $cmd['qty3'],
			'bomid' => $cmd['bomid'],
			'bomversion' => $cmd['bomversion'],
			'processprdid' => $cmd['processprdid'],
			'slocid' => $cmd['slocid'],
			'mesinid' => $cmd['mesinid']
		));
	}
	public function actiongetprice() {
		$product = null;
		$cmd='';
		if(isset($_POST['productid']) && isset($_POST['addressbookid'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.pricecategoryid')
				->from('addressbook t')
				->where('t.addressbookid = '.$_POST['addressbookid'])
				->limit(1)
				->queryRow();
			$cmd = Yii::app()->db->createCommand()
				->select('t.currencyvalue')
				->from('productsales t')
				->where('productid = '.$_POST['productid'].' and pricecategoryid = '.$cmd['pricecategoryid'])
				->limit(1)
				->queryRow();
		}
		if (Yii::app()->request->isAjaxRequest) {
			echo CJSON::encode(array(
				'status'=>'success',
				'currencyvalue'=> Yii::app()->format->formatNumber($cmd['currencyvalue']),
				));
			Yii::app()->end();
		}
	}
	public function actionGenerateDataprice() {
		$cmd = 0;
		if(isset($_POST['productid']) && isset($_POST['customerid'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.currencyid,t.currencyvalue')	
				->from('productsales t')
				->join('addressbook a','a.pricecategoryid = t.pricecategoryid')
				->where('productid = :productid and a.addressbookid = :addressbookid',
					array(':productid'=>$_POST['productid'],':addressbookid'=>$_POST['customerid']))
				->queryRow();			
		}
		if (Yii::app()->request->isAjaxRequest) {
			echo CJSON::encode(array(
				'status'=>'success',
				'price'=> Yii::app()->format->formatNumber($cmd['currencyvalue']),
				'currencyid'=>$cmd['currencyid'],
				'currencyrate'=>1
				));
			Yii::app()->end();
		}
	}
	private function ModifyData($connection,$arraydata) {
		$id = (int)$arraydata[0];
    $sql = 'call Modifproduct(:vid,:vproductcode,:vproductname,:visstock,:visasset,:vproductpic,:vbarcode,:vmaterialtypeid,:vthickness,:vwidth,:vlength,
      :vqty1,:vuom1,:vqty2,:vuom2,:vqty3,:vuom3,:vmaterialgroupid,:vsled,:visautolot,:vrecordstatus,:vdatauser)';
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		$command->bindvalue(':vproductcode',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vproductname',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vproductpic',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vbarcode',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':visstock',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':visasset',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vmaterialtypeid',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vthickness',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vwidth',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vlength',$arraydata[10],PDO::PARAM_STR);
		$command->bindvalue(':vqty1',$arraydata[11],PDO::PARAM_STR);
		$command->bindvalue(':vuom1',$arraydata[12],PDO::PARAM_STR);
		$command->bindvalue(':vqty2',$arraydata[13],PDO::PARAM_STR);
		$command->bindvalue(':vuom2',$arraydata[14],PDO::PARAM_STR);
		$command->bindvalue(':vqty3',$arraydata[15],PDO::PARAM_STR);
		$command->bindvalue(':vuom3',$arraydata[16],PDO::PARAM_STR);
		$command->bindvalue(':vmaterialgroupid',$arraydata[17],PDO::PARAM_STR);
		$command->bindvalue(':vsled',$arraydata[18],PDO::PARAM_STR);
		$command->bindvalue(':visautolot',$arraydata[19],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[20],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();		
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-product"]["name"]);
		if (move_uploaded_file($_FILES["file-product"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try
			{
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$productcode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$productname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$productpic = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$barcode = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$isstock = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$isasset = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$materialtypecode = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$materialtypeid = Yii::app()->db->createCommand("select materialtypeid from materialtype where materialtypecode = '".$materialtypecode."'")->queryScalar();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$this->ModifyData($connection,array($id,$productcode,$productname,$productpic,$barcode,$isstock,$isasset,$materialtypeid,$recordstatus));
				}
				$transaction->commit();			
				GetMessage(false,'insertsuccess');
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,'Line: '.$row.' '.implode(" ",$e->errorInfo));
			}
    }
	}
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['product-productid'])?$_POST['product-productid']:''),
				$_POST['product-productcode'],
				$_POST['product-productname'],
				$_POST['product-productpic'],
				$_POST['product-barcode'],
				isset($_POST['product-isstock'])?1:0,
        isset($_POST['product-isasset'])?1:0,
        $_POST['product-materialtypeid'],
        $_POST['product-thickness'],
        $_POST['product-width'],
        $_POST['product-length'],
        $_POST['product-qty1'],
        $_POST['product-uom1'],
        $_POST['product-qty2'],
        $_POST['product-uom2'],
        $_POST['product-qty3'],
        $_POST['product-uom3'],
        $_POST['product-materialgroupid'],
        $_POST['product-sled'],
        isset($_POST['product-isautolot'])?1:0,
				isset($_POST['product-recordstatus'])?1:0));
			$transaction->commit();			
			GetMessage(false,'insertsuccess');
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,$e->errorInfo);
		}
	}
	private function ModifyDataproductplant($connection,$arraydata) {
		$id = (int)$arraydata[0];
		if ($id == '') {
			$sql = 'call InsertProductPlant(:vproductid,:vslocid,:vissource,:vdatauser)';
			$command=$connection->createCommand($sql);
		} else {
			$sql = 'call UpdateProductPlant(:vid,:vproductid,:vslocid,:vissource,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);	
		}
		$command->bindvalue(':vproductid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vslocid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vissource',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionSaveproductplant() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDataproductplant($connection,array((isset($_POST['productplantid'])?$_POST['productplantid']:''),$_POST['productid'],$_POST['slocid'],
				$_POST['issource']));
			$transaction->commit();			
			GetMessage(false,'insertsuccess');
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	private function ModifyDataProductSales($connection,$arraydata) {
		$id = (int)$arraydata[0];
		if ($id == '') {
			$sql = 'call Insertproductsales(:vproductid,:vcurrencyid,:vcurrencyvalue,:vpricecategoryid,:vuomid,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateproductsales(:vid,:vproductid,:vcurrencyid,:vcurrencyvalue,:vpricecategoryid,:vuomid,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		}
		$command->bindvalue(':vproductid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyvalue',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vpricecategoryid',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vuomid',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionSaveProductSales() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDataProductSales($connection,array((isset($_POST['productsalesid'])?$_POST['productsalesid']:''),$_POST['productid'],$_POST['currencyid'],$_POST['currencyvalue'],
				$_POST['pricecategoryid'],$_POST['uomid']));
			$transaction->commit();
			GetMessage(false,'insertsuccess');
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	public function actionPurge() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeproduct(:vid,:vdatauser)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vdatauser',GetUserPC(),PDO::PARAM_STR);
				$command->execute();				
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
		}
		else {
			GetMessage(true, 'chooseone');
		}
	}
	public function actionPurgeproductplant() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeproductplant(:vid,:vdatauser)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vdatauser',GetUserPC(),PDO::PARAM_STR);
				$command->execute();				
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
		}
		else {
			GetMessage(true, 'chooseone');
		}
	}
	public function actionPurgeProductSales() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeproductsales(:vid,:vdatauser)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vdatauser',GetUserPC(),PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
		}
		else {
			GetMessage(true,'chooseone');
		}
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlematerialtypedesc'] = GetCatalog('materialtypedesc');
		$this->dataprint['titlematerialtypecode'] = GetCatalog('materialtypecode');
		$this->dataprint['titleproductcode'] = GetCatalog('productcode');
		$this->dataprint['titleproductname'] = GetCatalog('productname');
		$this->dataprint['titleisstock'] = GetCatalog('isstock');
		$this->dataprint['titleisasset'] = GetCatalog('isasset');
		$this->dataprint['titlebarcode'] = GetCatalog('barcode');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'productid');
		}
    $this->dataprint['materialtypedesc'] = GetSearchText(array('GET'),'materialtypedesc');
    $this->dataprint['materialtypecode'] = GetSearchText(array('GET'),'materialtypecode');
    $this->dataprint['productcode'] = GetSearchText(array('GET'),'productcode');
    $this->dataprint['productname'] = GetSearchText(array('GET'),'productname');
  }
}