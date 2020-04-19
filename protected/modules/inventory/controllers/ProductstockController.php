<?php
class ProductstockController extends Controller {
  public $menuname = 'productstock';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexhome() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->searchhome();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchdetail();
    else
      $this->renderPartial('index', array());
  }
	public function actionIndexaloc() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchaloc();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $productstockid   	= GetSearchText(array('POST'),'productstockid');
    $productname   	= GetSearchText(array('POST'),'productname');
    $sloc        	 	= GetSearchText(array('POST'),'sloc');
    $plantcode      = GetSearchText(array('POST'),'plantcode');
    $storagebin  		= GetSearchText(array('POST'),'storagebin');
		$unitofmeasure  = GetSearchText(array('POST'),'unitofmeasure');
		$referenceno  = GetSearchText(array('POST'),'referenceno');
		$page 					= GetSearchText(array('POST','GET'),'page',1,'int');
		$rows 					= GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort 					= GetSearchText(array('POST','GET'),'sort','productstockid','int');
		$order 					= GetSearchText(array('POST','GET'),'order','desc','int');
		$offset       	= ($page - 1) * $rows;
		$result       	= array();
		$row          	= array();
		$query = "
			from productstock t 
			inner join sloc c on c.slocid = t.slocid 
			inner join product h on h.productid = t.productid 
			inner join materialtype i on i.materialtypeid = h.materialtypeid 
			inner join plant j on j.plantid = c.plantid 
			where (coalesce(t.productname,'') like '".$productname."') 
				and (coalesce(t.sloccode,'') like '".$sloc."') 
				and (coalesce(t.productstockid,'') like '".$productstockid."') 
				and (coalesce(t.storagedesc,'') like '".$storagebin."') 
				and (coalesce(t.uomcode,'') like '".$unitofmeasure."') 
				and c.slocid in (".GetUserObjectValues('sloc').") 
				and j.plantcode like '".$plantcode."'".
				(($referenceno != '%%')?"
				and t.productstockid in (
					select distinct za.productstockid 
					from productstockdet za 
					where coalesce(za.referenceno,'') like '".$referenceno."'
				)":'');
		$sqlcount = ' select count(distinct t.productstockid) as total '.$query;
		$sql = '
			select distinct i.materialtypecode,t.productstockid, t.productid,t.productcode, t.productname, t.slocid, t.sloccode, t.storagebinid, t.storagedesc,
				t.qty, t.qty2, ifnull(t.qty3,0) as qty3, t.uomid, t.uomcode, t.uom2id,t.uom2code,t.uom3id,t.uom3code,t.qtyinprogress,c.description as slocdesc,
				ifnull((select z.minstock from mrp z where z.productid = t.productid and z.slocid = t.slocid limit 1),0) as minstock,
				ifnull((select z.reordervalue from mrp z where z.productid = t.productid and z.slocid = t.slocid limit 1),0) as orderstock,qtyalokasi,
				ifnull((select z.maxvalue from mrp z where z.productid = t.productid and z.slocid = t.slocid limit 1),0) as maxstock,
				GetMaterialgroup(t.productid) as materialgroupcode,
				(
					select averageprice
					from productstockdet zz 
					where zz.productid = t.productid and zz.slocid = t.slocid and zz.storagebinid = t.storagebinid and zz.productstockid = t.productstockid limit 1
				)	buyprice			
				'.$query;
    $result['total'] = Yii::app()->db->createCommand($sqlcount)->queryScalar();
		$cmd = Yii::app()->db->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
		$price = getUserObjectValues($menuobject='currency');
    foreach ($cmd as $data) {
      $row[] = array(
        'productstockid' => $data['productstockid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
        'slocdesc' => $data['slocdesc'],
        'storagebinid' => $data['storagebinid'],
        'description' => $data['storagedesc'],
        'materialtypecode' => $data['materialtypecode'],
        'materialgroupcode' => $data['materialgroupcode'],
				'buyprice'=>(($price == 1)?Yii::app()->format->formatNumber($data['buyprice']):Yii::app()->format->formatNumber(0)),
        'qty' => Yii::app()->format->formatNumber($data['qty']),
				'qty2' => Yii::app()->format->formatNumber($data['qty2']),
				'qty3' => Yii::app()->format->formatNumber($data['qty3']),
				'qtyalokasi' => Yii::app()->format->formatNumber($data['qtyalokasi']),
        'qtyinprogress' => Yii::app()->format->formatNumber($data['qtyinprogress']),
        'minstock' => Yii::app()->format->formatNumber($data['minstock']),
        'orderstock' => Yii::app()->format->formatNumber($data['orderstock']),
        'maxstock' => Yii::app()->format->formatNumber($data['maxstock']),
        'uomid' => $data['uomid'],
        'uom2id' => $data['uom2id'],
        'uom3id' => $data['uom3id'],
				'uomcode' => $data['uomcode'],
				'uom2code' => $data['uom2code'],
				'uom3code' => $data['uom3code'],
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	public function searchhome() {
    header('Content-Type: application/json');
    $productstockid   	= GetSearchText(array('POST'),'productstockid');
    $productname   	= GetSearchText(array('POST'),'productname');
    $sloc        	 	= GetSearchText(array('POST'),'sloc');
    $plantcode      = GetSearchText(array('POST'),'plantcode');
    $storagebin  		= GetSearchText(array('POST'),'storagebin');
		$unitofmeasure  = GetSearchText(array('POST'),'unitofmeasure');
		$referenceno  = GetSearchText(array('POST'),'referenceno');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','productstockid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset       = ($page - 1) * $rows;
		$result       = array();
		$row          = array();
		$query = "
			from productstock t 
			inner join sloc c on c.slocid = t.slocid 
			inner join product h on h.productid = t.productid 
			inner join materialtype i on i.materialtypeid = h.materialtypeid 
			inner join plant j on j.plantid = c.plantid  
			where (coalesce(t.productname,'') like '".$productname."') 
				and (coalesce(t.sloccode,'') like '".$sloc."') 
				and (coalesce(t.storagedesc,'') like '".$storagebin."') 
				and (coalesce(t.uomcode,'') like '".$unitofmeasure."') 
				and c.slocid in (".GetUserObjectValues('sloc').") 
				and j.plantcode like '".$plantcode."'".
				(($referenceno != '%%')?"
				and t.productstockid in (
					select distinct za.productstockid 
					from productstockdet za 
					where coalesce(za.referenceno,'') like '".$referenceno."'
				)":''); 
		$sqlcount = ' select count(distinct t.productstockid) as total '.$query;
		$sql = '
			select distinct t.productstockid, t.productid,t.productcode, t.productname, t.slocid, t.sloccode, t.storagebinid, t.storagedesc,
				t.qty, t.qty2, ifnull(t.qty3,0) as qty3, t.uomid, t.uomcode, t.uom2id,t.uom2code,t.uom3id,t.uom3code,t.qtyinprogress,c.description as slocdesc,
				ifnull((select z.minstock from mrp z where z.productid = t.productid and z.slocid = t.slocid limit 1),0) as minstock,
				ifnull((select z.reordervalue from mrp z where z.productid = t.productid and z.slocid = t.slocid limit 1),0) as orderstock,qtyalokasi,
				GetMaterialgroup(t.productid) as materialgroupcode,
				(
					select averageprice
					from productstockdet zz 
					where zz.productid = t.productid and zz.slocid = t.slocid and zz.storagebinid = t.storagebinid and zz.productstockid = t.productstockid limit 1
				)	buyprice,
				ifnull((select z.maxvalue from mrp z where z.productid = t.productid and z.slocid = t.slocid limit 1),0) as maxstock '.$query;
    $result['total'] = Yii::app()->db->createCommand($sqlcount)->queryScalar();
		$cmd = Yii::app()->db->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
		$price = getUserObjectValues($menuobject='currency');
    foreach ($cmd as $data) {
      $row[] = array(
        'productstockid' => $data['productstockid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
        'slocdesc' => $data['slocdesc'],
        'storagebinid' => $data['storagebinid'],
        'description' => $data['storagedesc'],
				'buyprice'=>(($price == 1)?Yii::app()->format->formatNumber($data['buyprice']):Yii::app()->format->formatNumber(0)),
        'materialgroupcode' => $data['materialgroupcode'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
				'qty2' => Yii::app()->format->formatNumber($data['qty2']),
				'qty3' => Yii::app()->format->formatNumber($data['qty3']),
				'qtyalokasi' => Yii::app()->format->formatNumber($data['qtyalokasi']),
        'qtyinprogress' => $data['qtyinprogress'],
        'minstock' => $data['minstock'],
        'orderstock' => $data['orderstock'],
        'maxstock' => $data['maxstock'],
        'uomid' => $data['uomid'],
        'uom2id' => $data['uom2id'],
        'uom3id' => $data['uom3id'],
				'uomcode' => $data['uomcode'],
				'uom2code' => $data['uom2code'],
				'uom3code' => $data['uom3code'],
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionsearchdetail() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','productstockdetid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $footer          = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('productstockdet t')->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
			->leftjoin('sloc d', 'd.slocid = t.slocid')
			->where('t.productstockid = :productstockid', array(
      ':productstockid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productcode,a.productname,b.uomcode,f.sloccode,c.uomcode as uom2code,
			d.uomcode as uom3code')
			->from('productstockdet t')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
			->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uom2id')
			->leftjoin('unitofmeasure d', 'd.unitofmeasureid = t.uom3id')
			->leftjoin('sloc f', 'f.slocid = t.slocid')
			->where('t.productstockid = :productstockid', array(
      ':productstockid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$price = getUserObjectValues($menuobject='currency');
    foreach ($cmd as $data) {
      $row[] = array(
        'referenceno' => $data['referenceno'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
				'qty2' => Yii::app()->format->formatNumber($data['qty2']),
				'qty3' => Yii::app()->format->formatNumber($data['qty3']),
				'averageprice'=>(($price == 1)?Yii::app()->format->formatNumber($data['averageprice']):Yii::app()->format->formatNumber(0)),
				'uomcode' => $data['uomcode'],
				'uom2code' => $data['uom2code'],
				'uom3code' => $data['uom3code'],
        'transdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['transdate']))
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
	public function actionsearchaloc() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','productstockdetid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $footer          = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('productstockaloc t')->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
			->leftjoin('sloc d', 'd.slocid = t.slocid')
			->where('t.productstockid = :productstockid', array(
      ':productstockid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productcode,a.productname,b.uomcode,f.sloccode,c.uomcode as uom2code,
			d.uomcode as uom3code,e.uomcode as uom4code')
			->from('productstockaloc t')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
			->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uom2id')
			->leftjoin('unitofmeasure d', 'd.unitofmeasureid = t.uom3id')
			->leftjoin('unitofmeasure e', 'e.unitofmeasureid = t.uom4id')
			->leftjoin('sloc f', 'f.slocid = t.slocid')
			->where('t.productstockid = :productstockid', array(
      ':productstockid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$price = getUserObjectValues($menuobject='currency');
    foreach ($cmd as $data) {
      $row[] = array(
        'referenceno' => $data['referenceno'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
				'qty2' => Yii::app()->format->formatNumber($data['qty2']),
				'qty3' => Yii::app()->format->formatNumber($data['qty3']),
				'uomcode' => $data['uomcode'],
				'uom2code' => $data['uom2code'],
				'uom3code' => $data['uom3code'],
        'transdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['transdate']))
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select a.productstockid,b.productname,c.sloccode as sloc,c.description,a.qty,d.uomcode,e.description as storagebin,
			a.qtyinprogress,h.uomcode as uom3code, g.uomcode as uom2code,a.qty2,a.qty3,
			from productstock a
			join product b on b.productid = a.productid
			join sloc c on c.slocid = a.slocid
			join unitofmeasure d on d.unitofmeasureid = a.uomid 
			left join unitofmeasure g on g.unitofmeasureid = a.uom2id
			left join unitofmeasure h on h.unitofmeasureid = a.uom3id
			join storagebin e on e.storagebinid = a.storagebinid 
			join plant f on f.plantid = c.plantid 
		";
		$productname   	= GetSearchText(array('POST','GET'),'productname');
    $sloc        	 	= GetSearchText(array('POST','GET'),'sloc');
    $plantcode      = GetSearchText(array('POST','GET'),'plantcode');
    $storagebin  		= GetSearchText(array('POST','GET'),'storagebin');
		$unitofmeasure  = GetSearchText(array('POST','GET'),'unitofmeasure');
		$sql .= "
			where coalesce(f.plantcode,'') like '".$plantcode."' 
			and coalesce(c.sloccode,'') like '".$sloc."' 
			and coalesce(e.description,'') like '".$storagebin."' 
			and coalesce(d.uomcode,'') like '".$unitofmeasure."' 
			and c.slocid in (".GetUserObjectValues('sloc').") 
		";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.productstockid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = getCatalog('productstock');
    $this->pdf->AddPage('P', array(
      470,
      250
    ));
    $this->pdf->setFont('Arial', 'B', 10);
    $this->pdf->colalign  = array(
      'L',
      'L',
      'L',
	  'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L'
    );
    $this->pdf->colheader = array(
      getCatalog('productname'),
      getCatalog('sloc'),
      getCatalog('storagebin'),
      getCatalog('qty'),
	  getCatalog('qty2'),
	  getCatalog('qty3'),
      getCatalog('qtyinprogress')
    );
    $this->pdf->setwidths(array(
      170,
      30,
      50,
      40,
      40,
	  40,
	  40,
	  40,
	  40,
	  40,
	  40,
	  40,
	  40,
      40,
      30
    ));
    $this->pdf->Rowheader();
    $this->pdf->setFont('Arial', '', 10);
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'L',
      'L',
	  'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R'
    );
    foreach ($dataReader as $row1) {
      $this->pdf->row(array(
        $row1['productname'],
        $row1['sloc'],
        $row1['storagebin'],
        Yii::app()->format->formatNumber($row1['qty']).' '.$row1['uomcode'],
		Yii::app()->format->formatNumber($row1['qty2']).' '.$row1['uom2code'],
		Yii::app()->format->formatNumber($row1['qty3']).' '.$row1['uom3code'],
        Yii::app()->format->formatNumber($row1['qtyinprogress'])
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    $this->menuname = 'productstock';
    parent::actionDownxls();
    $sql = "select a.productstockid,b.productname,c.sloccode as sloc,c.description,a.qty,d.uomcode,e.description as storagebin,
			a.qtyinprogress,h.uomcode as uom3code, g.uomcode as uom2code,a.qty2,a.qty3,
			from productstock a
			join product b on b.productid = a.productid
			join sloc c on c.slocid = a.slocid
			join unitofmeasure d on d.unitofmeasureid = a.uomid 
			left join unitofmeasure g on g.unitofmeasureid = a.uom2id
			left join unitofmeasure h on h.unitofmeasureid = a.uom3id
			join storagebin e on e.storagebinid = a.storagebinid 
			join plant f on f.plantid = c.plantid 
		";
		$productname   	= GetSearchText(array('POST','GET'),'productname');
    $sloc        	 	= GetSearchText(array('POST','GET'),'sloc');
    $plantcode      = GetSearchText(array('POST','GET'),'plantcode');
    $storagebin  		= GetSearchText(array('POST','GET'),'storagebin');
		$unitofmeasure  = GetSearchText(array('POST','GET'),'unitofmeasure');
		$sql .= "
			where coalesce(f.plantcode,'') like '".$plantcode."' 
			and coalesce(c.sloccode,'') like '".$sloc."' 
			and coalesce(e.description,'') like '".$storagebin."' 
			and coalesce(d.uomcode,'') like '".$unitofmeasure."' 
			and c.slocid in (".GetUserObjectValues('sloc').") 
		";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.productstockid in (" . $_GET['id'] . ")";
    }
		$sql .= " order by b.productname ";
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 2;
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i, $row1['productstockid'])
			->setCellValueByColumnAndRow(1, $i, $row1['productname'])
			->setCellValueByColumnAndRow(2, $i, $row1['sloc'])
			->setCellValueByColumnAndRow(3, $i, $row1['qty'])
			->setCellValueByColumnAndRow(4, $i, $row1['uomcode'])
			->setCellValueByColumnAndRow(5, $i, $row1['qty2'])
			->setCellValueByColumnAndRow(6, $i, $row1['uom2code'])
			->setCellValueByColumnAndRow(7, $i, $row1['qty3'])
			->setCellValueByColumnAndRow(8, $i, $row1['uom3code'])
			->setCellValueByColumnAndRow(9, $i, $row1['storagebin'])
			->setCellValueByColumnAndRow(10, $i, $row1['qtyinprogress']);
      $i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}