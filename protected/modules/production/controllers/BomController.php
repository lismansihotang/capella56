<?php
class BomController extends Controller {
  public $menuname = 'bom';
  public function actionIndex() {
		parent::actionIndex();
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
  public function actionGetData() {
		$id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'bomid' => $id
		));
	}
  public function search() {
    header('Content-Type: application/json');
		$bomid = GetSearchText(array('POST','Q','GET'),'bomid');
		$bomversion   		= GetSearchText(array('POST','Q','GET'),'bomversion');
    $plantcode  = GetSearchText(array('POST','GET'),'plantcode');
		$bomdate   = GetSearchText(array('POST','GET'),'bomdate');
		$plantid   = GetSearchText(array('POST','GET'),'plantid',0,'int');
		$productid   = GetSearchText(array('POST','GET'),'productid',0,'int');
		$productcode   = GetSearchText(array('POST','GET'),'productcode');
		$productname   = GetSearchText(array('POST','GET'),'productname');
		$kodemesin     = GetSearchText(array('POST','GET'),'kodemesin');
		$namamesin     = GetSearchText(array('POST','GET'),'namamesin');
		$processprd     = GetSearchText(array('POST','GET'),'processprd');
    $description = GetSearchText(array('POST','GET'),'description');
    $materialtypecode = GetSearchText(array('POST','GET'),'materialtypecode');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','bomid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset      = ($page - 1) * $rows;
    $result      = array();
    $row         = array();
    if (isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
				->from('billofmaterial t')
				->leftjoin('product a', 'a.productid = t.productid')
				->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
				->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uom2id')
				->leftjoin('unitofmeasure d', 'd.unitofmeasureid = t.uom3id')
				->leftjoin('mesin f', 'f.mesinid = t.mesinid')
				->leftjoin('processprd g', 'g.processprdid = t.processprdid')
				->leftjoin('plant h', 'h.plantid = t.plantid')
				->leftjoin('company i', 'i.companyid = h.companyid')
				->leftjoin('materialtype k', 'k.materialtypeid = a.materialtypeid')
				->where("((coalesce(bomid,'') like :bomid) 
				or (coalesce(h.plantcode,'') like :plantcode) 
				or (coalesce(t.bomversion,'') like :bomversion)
				or (coalesce(t.bomdate,'') like :bomdate) 
				or (coalesce(a.productcode,'') like :productcode) 
				or (coalesce(a.productname,'') like :productname) 
				or (coalesce(f.kodemesin,'') like :kodemesin) 
				or (coalesce(f.namamesin,'') like :namamesin) 
				or (coalesce(g.processprdname,'') like :processprdname) 
				or (coalesce(t.description,'') like :description)
				or (coalesce(k.materialtypecode,'') like :materialtypecode)) 
				and t.recordstatus = 1".
				(($plantid != '')?" and t.plantid = ".$plantid:'').
				(($productid != '')?" and t.productid = ".$productid:'')				
				, array(
        ':bomid' =>  $bomid ,
        ':plantcode' =>  $plantcode ,
        ':bomdate' =>  $bomdate ,
        ':bomversion' =>  $bomversion ,
        ':productcode' =>  $productcode ,
        ':productname' =>  $productname ,
				':kodemesin' =>  $kodemesin ,
				':namamesin' =>  $namamesin ,
				':processprdname' =>  $processprd ,
				':description' =>  $description ,
				':materialtypecode' =>  $materialtypecode ,
      ))->queryScalar();
		} else if
			(isset($_GET['bom'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
				->from('billofmaterial t')
				->leftjoin('product a', 'a.productid = t.productid')
				->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
				->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uom2id')
				->leftjoin('unitofmeasure d', 'd.unitofmeasureid = t.uom3id')
				->leftjoin('mesin f', 'f.mesinid = t.mesinid')
				->leftjoin('processprd g', 'g.processprdid = t.processprdid')
				->leftjoin('plant h', 'h.plantid = t.plantid')
				->leftjoin('company i', 'i.companyid = h.companyid')
				->leftjoin('materialtype k', 'k.materialtypeid = a.materialtypeid')
				->where("((coalesce(bomid,'') like :bomid) 
				or (coalesce(h.plantcode,'') like :plantcode) 
				or (coalesce(t.bomversion,'') like :bomversion)
				or (coalesce(t.bomdate,'') like :bomdate) 
				or (coalesce(a.productcode,'') like :productcode) 
				or (coalesce(a.productname,'') like :productname) 
				or (coalesce(f.kodemesin,'') like :kodemesin) 
				or (coalesce(f.namamesin,'') like :namamesin) 
				or (coalesce(g.processprdname,'') like :processprdname) 
				or (coalesce(k.materialtypecode,'') like :materialtypecode) 
				or (coalesce(t.description,'') like :description)) ", array(
        ':bomid' =>  $bomid ,
        ':plantcode' =>  $plantcode ,
        ':bomdate' =>  $bomdate ,
        ':bomversion' =>  $bomversion ,
        ':productcode' =>  $productcode ,
        ':productname' =>  $productname ,
				':kodemesin' =>  $kodemesin ,
				':namamesin' =>  $namamesin ,
				':processprdname' =>  $processprd ,
				':materialtypecode' =>  $materialtypecode ,
				':description' =>  $description ,
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
				->from('billofmaterial t')
				->leftjoin('product a', 'a.productid = t.productid')
				->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
				->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uom2id')
				->leftjoin('unitofmeasure d', 'd.unitofmeasureid = t.uom3id')
				->leftjoin('mesin f', 'f.mesinid = t.mesinid')
				->leftjoin('processprd g', 'g.processprdid = t.processprdid')
				->leftjoin('plant h', 'h.plantid = t.plantid')
				->leftjoin('company i', 'i.companyid = h.companyid')
				->leftjoin('materialtype k', 'k.materialtypeid = a.materialtypeid')
				->where("((coalesce(bomid,'') like :bomid) 
				and (coalesce(h.plantcode,'') like :plantcode) 
				and (coalesce(t.bomversion,'') like :bomversion)
				and (coalesce(t.bomdate,'') like :bomdate) 
				and (coalesce(a.productcode,'') like :productcode) 
				and (coalesce(a.productname,'') like :productname) 
				and (coalesce(f.kodemesin,'') like :kodemesin) 
				and (coalesce(f.namamesin,'') like :namamesin) 
				and (coalesce(g.processprdname,'') like :processprdname) 
				and (coalesce(k.materialtypecode,'') like :materialtypecode) 
				and (coalesce(t.description,'') like :description)) ", array(
        ':bomid' =>  $bomid ,
        ':plantcode' =>  $plantcode ,
        ':bomdate' =>  $bomdate ,
        ':bomversion' =>  $bomversion ,
        ':productcode' =>  $productcode ,
        ':productname' =>  $productname ,
				':kodemesin' =>  $kodemesin ,
				':namamesin' =>  $namamesin ,
				':processprdname' =>  $processprd ,
				':materialtypecode' =>  $materialtypecode ,
				':description' =>  $description ,
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()
				->select('t.*,h.plantcode,i.companyname,a.productname,a.productcode,b.uomcode,c.uomcode as uom2code,d.uomcode as uom3code,
					f.kodemesin,g.processprdname,(select ifnull(count(1),0) from bomdetail zz where zz.bomid = t.bomid) as jumlah,k.materialtypecode')
				->from('billofmaterial t')
				->leftjoin('product a', 'a.productid = t.productid')
				->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
				->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uom2id')
				->leftjoin('unitofmeasure d', 'd.unitofmeasureid = t.uom3id')
				->leftjoin('mesin f', 'f.mesinid = t.mesinid')
				->leftjoin('processprd g', 'g.processprdid = t.processprdid')
				->leftjoin('plant h', 'h.plantid = t.plantid')
				->leftjoin('company i', 'i.companyid = h.companyid')
				->leftjoin('materialtype k', 'k.materialtypeid = a.materialtypeid')
				->where("((coalesce(bomid,'') like :bomid) 
				or (coalesce(h.plantcode,'') like :plantcode) 
				or (coalesce(t.bomversion,'') like :bomversion)
				or (coalesce(t.bomdate,'') like :bomdate) 
				or (coalesce(a.productcode,'') like :productcode) 
				or (coalesce(a.productname,'') like :productname) 
				or (coalesce(f.kodemesin,'') like :kodemesin) 
				or (coalesce(f.namamesin,'') like :namamesin) 
				or (coalesce(g.processprdname,'') like :processprdname) 
				or (coalesce(t.description,'') like :description)
				or (coalesce(k.materialtypecode,'') like :materialtypecode)) 
				and t.recordstatus = 1".
				(($plantid != '')?" and t.plantid = ".$plantid:'').
				(($productid != '')?" and t.productid = ".$productid:'')		
				, array(
        ':bomid' =>  $bomid ,
        ':plantcode' =>  $plantcode ,
        ':bomdate' =>  $bomdate ,
        ':bomversion' =>  $bomversion ,
        ':productcode' =>  $productcode ,
        ':productname' =>  $productname ,
				':kodemesin' =>  $kodemesin ,
				':namamesin' =>  $namamesin ,
				':processprdname' =>  $processprd ,
				':materialtypecode' =>  $materialtypecode ,
				':description' =>  $description ,
      ))->order($sort . ' ' . $order)->queryAll();
    } 
		else if
			(isset($_GET['bom'])) {
				 $cmd = Yii::app()->db->createCommand()
      ->select('t.*,h.plantcode,i.companyname,a.productname,a.productcode,b.uomcode,c.uomcode as uom2code,d.uomcode as uom3code,
					f.kodemesin,g.processprdname,(select ifnull(count(1),0) from bomdetail zz where zz.bomid = t.bomid) as jumlah,k.materialtypecode')
				->from('billofmaterial t')
				->leftjoin('product a', 'a.productid = t.productid')
				->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
				->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uom2id')
				->leftjoin('unitofmeasure d', 'd.unitofmeasureid = t.uom3id')
				->leftjoin('mesin f', 'f.mesinid = t.mesinid')
				->leftjoin('processprd g', 'g.processprdid = t.processprdid')
				->leftjoin('plant h', 'h.plantid = t.plantid')
				->leftjoin('company i', 'i.companyid = h.companyid')
				->leftjoin('materialtype k', 'k.materialtypeid = a.materialtypeid')
				->where("((coalesce(bomid,'') like :bomid) 
				or (coalesce(h.plantcode,'') like :plantcode) 
				or (coalesce(t.bomversion,'') like :bomversion)
				or (coalesce(t.bomdate,'') like :bomdate) 
				or (coalesce(a.productcode,'') like :productcode) 
				or (coalesce(a.productname,'') like :productname) 
				or (coalesce(f.kodemesin,'') like :kodemesin) 
				or (coalesce(f.namamesin,'') like :namamesin) 
				or (coalesce(g.processprdname,'') like :processprdname) 
				or (coalesce(k.materialtypecode,'') like :materialtypecode) 
				or (coalesce(t.description,'') like :description)) ", array(
        ':bomid' =>  $bomid ,
        ':plantcode' =>  $plantcode ,
        ':bomdate' =>  $bomdate ,
        ':bomversion' =>  $bomversion ,
        ':productcode' =>  $productcode ,
        ':productname' =>  $productname ,
				':kodemesin' =>  $kodemesin ,
				':namamesin' =>  $namamesin ,
				':processprdname' =>  $processprd ,
				':materialtypecode' =>  $materialtypecode ,
				':description' =>  $description ,
     ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
		else {
      $cmd = Yii::app()->db->createCommand()
				->select('t.*,h.plantcode,i.companyname,a.productname,a.productcode,b.uomcode,c.uomcode as uom2code,d.uomcode as uom3code,
					f.kodemesin,g.processprdname,(select ifnull(count(1),0) from bomdetail zz where zz.bomid = t.bomid) as jumlah,k.materialtypecode')
				->from('billofmaterial t')
				->leftjoin('product a', 'a.productid = t.productid')
				->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
				->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uom2id')
				->leftjoin('unitofmeasure d', 'd.unitofmeasureid = t.uom3id')
				->leftjoin('mesin f', 'f.mesinid = t.mesinid')
				->leftjoin('processprd g', 'g.processprdid = t.processprdid')
				->leftjoin('plant h', 'h.plantid = t.plantid')
				->leftjoin('company i', 'i.companyid = h.companyid')
				->leftjoin('materialtype k', 'k.materialtypeid = a.materialtypeid')
				->where("((coalesce(bomid,'') like :bomid) 
				and (coalesce(h.plantcode,'') like :plantcode) 
				and (coalesce(t.bomversion,'') like :bomversion)
				and (coalesce(t.bomdate,'') like :bomdate) 
				and (coalesce(a.productcode,'') like :productcode) 
				and (coalesce(a.productname,'') like :productname) 
				and (coalesce(f.kodemesin,'') like :kodemesin) 
				and (coalesce(f.namamesin,'') like :namamesin) 
				and (coalesce(g.processprdname,'') like :processprdname) 
				and (coalesce(k.materialtypecode,'') like :materialtypecode) 
				and (coalesce(t.description,'') like :description)) ", array(
        ':bomid' =>  $bomid ,
        ':plantcode' =>  $plantcode ,
        ':bomdate' =>  $bomdate ,
        ':bomversion' =>  $bomversion ,
        ':productcode' =>  $productcode ,
        ':productname' =>  $productname ,
				':kodemesin' =>  $kodemesin ,
				':namamesin' =>  $namamesin ,
				':processprdname' =>  $processprd ,
				':materialtypecode' =>  $materialtypecode ,
				':description' =>  $description ,
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'bomid' => $data['bomid'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'companyname' => $data['companyname'],
        'bomversion' => $data['bomversion'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'productcode' => $data['productcode'],
				'mesinid' => $data['mesinid'],
				'kodemesin' => $data['kodemesin'],
				'numoperator' => $data['numoperator'],
				'processprdid' => $data['processprdid'],
				'processprdname' => $data['processprdname'],
        'qtyview' => Yii::app()->format->formatNumber($data['qty']),
        'qty2view' => Yii::app()->format->formatNumber($data['qty2']),
        'qty3view' => Yii::app()->format->formatNumber($data['qty3']),
        'qty' => $data['qty'],
        'qty2' => $data['qty2'],
        'qty3' => $data['qty3'],
        'uomid' => $data['uomid'],
				'uom2id' => $data['uom2id'],
				'uom3id' => $data['uom3id'],
        'uomcode' => $data['uomcode'],
				'uom2code' => $data['uom2code'],
				'uom3code' => $data['uom3code'],
        'bomdate' => date(Yii::app()->params["dateviewfromdb"], strtotime($data['bomdate'])),
        'description' => $data['description'],
        'jumlah' => $data['jumlah'],
        'materialtypecode' => $data['materialtypecode'],
        'recordstatus' => $data['recordstatus']
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
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','bomdetailid','int');
		$order = GetSearchText(array('POST','GET'),'order','asc','int');
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('bomdetail t')
				->leftjoin('product a', 'a.productid = t.productid')
				->leftjoin('materialtype b', 'b.materialtypeid = a.materialtypeid')
				->leftjoin('product c', 'c.productid = t.productparentid')
				->where('t.bomid = :bomid', array(
      ':bomid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
				->select('t.*,a.productname,a.productcode,b.materialtypecode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code,
						c.productname as parentname,
						GetStdQty2(a.productid) as stdqty2,
						GetStdQty3(a.productid) as stdqty3,
						(select c.bomversion from billofmaterial c where c.bomid = t.productbomid) as bomversion')
				->from('bomdetail t')
				->leftjoin('product a', 'a.productid = t.productid')
				->leftjoin('materialtype b', 'b.materialtypeid = a.materialtypeid')
				->leftjoin('product c', 'c.productid = t.productparentid')
				->where('t.bomid = :bomid', array(
      ':bomid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'bomdetailid' => $data['bomdetailid'],
        'bomid' => $data['bomid'],
        'productid' => $data['productid'],
				'productcode' => $data['productcode'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'qty2' => Yii::app()->format->formatNumber($data['qty2']),
        'qty3' => Yii::app()->format->formatNumber($data['qty3']),
				'stdqty2' => Yii::app()->format->formatNumber($data['stdqty2']),
				'stdqty3' => Yii::app()->format->formatNumber($data['stdqty3']),
        'uomid' => $data['uomid'],
				'uom2id' => $data['uom2id'],
				'uom3id' => $data['uom3id'],
        'uomcode' => $data['uomcode'],
				'uom2code' => $data['uom2code'],
				'uom3code' => $data['uom3code'],
        'productbomid' => $data['productbomid'],
        'productbomversion' => $data['bomversion'],
        'productparentid' => $data['productparentid'],
        'parentname' => $data['parentname'],
        'description' => $data['description'],
        'materialtypecode' => $data['materialtypecode']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function actioncopyBom() {
		parent::actionIndex();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call CopyBOM(:vid,:vdatauser)';
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
	private function ModifyData($connection,$arraydata) {
		$id = (int)$arraydata[0];
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$sql = 'call ModifBOM(:vid,:vplantid,:vbomversion,:vproductid,:vqty,:vqty2,:vqty3,:vuomid,:vuom2id,:vuom3id,
		:vbomdate,:vmesinid,:vnumoperator,:vprocessprdid,:vdescription,:vrecordstatus,:vdatauser)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vbomversion', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vqty', $arraydata[4], PDO::PARAM_STR);
    $command->bindvalue(':vqty2', $arraydata[5], PDO::PARAM_STR);
    $command->bindvalue(':vqty3', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vuom2id', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vuom3id', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vbomdate', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vmesinid', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vnumoperator', $arraydata[12], PDO::PARAM_STR);
		$command->bindvalue(':vprocessprdid', $arraydata[13], PDO::PARAM_STR);
		$command->bindvalue(':vdescription', $arraydata[14], PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus', $arraydata[15], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();			
	}	
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-bom"]["name"]);
		if (move_uploaded_file($_FILES["file-bom"]["tmp_name"], $target_file)) {
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
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$plantcode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$plantid = Yii::app()->db->createCommand("select plantid from plant where plantcode = '".$plantcode."'")->queryScalar();
					$productname = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$sql = "select productid from product where productname = :productname";
					$command=$connection->createCommand($sql);
					$command->bindvalue(':productname',$productname,PDO::PARAM_STR);
					$productid = $command->queryScalar();
					$bomversion = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$sql = "select bomid from billofmaterial where bomversion = :bomversion and productid = :productid and plantid = :plantid";
					$command=$connection->createCommand($sql);
					$command->bindvalue(':bomversion',$bomversion,PDO::PARAM_STR);
					$command->bindvalue(':productid',$productid,PDO::PARAM_STR);
					$command->bindvalue(':plantid',$plantid,PDO::PARAM_STR);
					$pid = $command->queryScalar();					
					if ($pid == false) {
						$bomdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(3, $row)->getValue()));
						$qty = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
						$uomcode = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
						$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$qty2 = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
						$uomcode = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
						$uom2id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$qty3 = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
						$uomcode = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
						$uom3id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$mesin = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
						$mesinid = Yii::app()->db->createCommand("select mesinid from mesin where kodemesin = '".$mesin."' and plantid = ".$plantid)->queryScalar();
						$numoperator = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
						$processprd = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
						$processprdid = Yii::app()->db->createCommand("select processprdid from processprd where processprdname = '".$processprd."'")->queryScalar();
						$description = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
						$recordstatus = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
						$this->ModifyData($connection,array(-1,$plantid,$bomversion,$productid,$qty,$qty2,$qty3,$uomid,$uom2id,$uom3id,
							$bomdate,$mesinid,$numoperator,$processprdid,$description,$recordstatus));
						$sql = "select bomid from billofmaterial where bomversion = :bomversion 
						and productid = :productid and plantid = :plantid";
						$command=$connection->createCommand($sql);
						$command->bindvalue(':bomversion',$bomversion,PDO::PARAM_STR);
						$command->bindvalue(':productid',$productid,PDO::PARAM_STR);
						$command->bindvalue(':plantid',$plantid,PDO::PARAM_STR);
						$pid = $command->queryScalar();					
					}
					if ($pid != false) {
						$productname = $objWorksheet->getCellByColumnAndRow(21, $row)->getValue();
						$sql = "select productid from product where productname = :productname";
						$command=$connection->createCommand($sql);
						$command->bindvalue(':productname',$productname,PDO::PARAM_STR);
						$productid = $command->queryScalar();
						$qty = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
						$uomcode = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
						$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$qty2 = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
						$uomcode = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
						$uom2id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$qty3 = $objWorksheet->getCellByColumnAndRow(19, $row)->getValue();
						$uomcode = $objWorksheet->getCellByColumnAndRow(20, $row)->getValue();
						$uom3id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$productbom = $objWorksheet->getCellByColumnAndRow(21, $row)->getValue();
						$sql = "select bomid from billofmaterial where bomversion = :bomversion and productid = :productid and plantid = :plantid";
						$command=$connection->createCommand($sql);
						$command->bindvalue(':bomversion',$productbom,PDO::PARAM_STR);
						$command->bindvalue(':productid',$productid,PDO::PARAM_STR);
						$command->bindvalue(':plantid',$plantid,PDO::PARAM_STR);
						$productbomid = $command->queryScalar();
						$productname = $objWorksheet->getCellByColumnAndRow(22, $row)->getValue();
						$sql = "select productid from product where productname = :productname";
						$command=$connection->createCommand($sql);
						$command->bindvalue(':productname',$productname,PDO::PARAM_STR);
						$productparentid = $command->queryScalar();
						$description = $objWorksheet->getCellByColumnAndRow(23, $row)->getValue();
						$this->ModifyDataDetail($connection,array('',$pid,$productid,$qty,$qty2,$qty3,$uomid,$uom2id,$uom3id,$productbomid,$productparentid,$description));
					}
				}
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,'Line '.$row.' ==> '.implode(" ",$e->errorInfo));
			}
    }
	}
  public function actionSave() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['bom-bomid'])?$_POST['bom-bomid']:''),
				$_POST['bom-plantid'],$_POST['bom-bomversion'],$_POST['bom-productid'],
				$_POST['bom-qty'],$_POST['bom-qty2'],$_POST['bom-qty3'],
				$_POST['bom-uomid'],$_POST['bom-uom2id'],$_POST['bom-uom3id'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['bom-bomdate'])),
				$_POST['bom-mesinid'],$_POST['bom-numoperator'],$_POST['bom-processprdid'],
				$_POST['bom-description'],(isset($_POST['bom-recordstatus']) ? 1 : 0)));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
	private function ModifyDataDetail($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertbomdetail(:vbomid,:vproductid,:vqty,:vqty2,:vqty3,:vuomid,:vuom2id,:vuom3id,:vproductbomid,:vproductparentid,
				:vdescription,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatebomdetail(:vid,:vbomid,:vproductid,:vqty,:vqty2,:vqty3,:vuomid,:vuom2id,:vuom3id,:vproductbomid,:vproductparentid,
				:vdescription,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		}
		$command->bindvalue(':vbomid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vqty', $arraydata[3], PDO::PARAM_STR);
    $command->bindvalue(':vqty2', $arraydata[4], PDO::PARAM_STR);
    $command->bindvalue(':vqty3', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vuom2id', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vuom3id', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vproductbomid', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vproductparentid', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vdescription', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavedetail() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataDetail($connection,array((isset($_POST['bomdetailid'])?$_POST['bomdetailid']:''),$_POST['bomid'],
			$_POST['productid'],$_POST['qty'],$_POST['qty2'],$_POST['qty3'],$_POST['uomid'],$_POST['uom2id'],$_POST['uom3id'],
			$_POST['productbomid'],$_POST['productparentid'],$_POST['description']));
			$transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
  public function actionPurge() {
    parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgebom(:vid,:vdatauser)';
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
    parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgebomdetail(:vid,:vdatauser)';
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
	private function PrintRecursivePDF($pdf,$bomid) {
		$sql = "select a.bomid,a.bomversion,a.bomdate,b.productname,a.qty,a.qty2,a.qty3,c.uomcode,d.uomcode as uom2code,
			e.uomcode as uom3code, a.description, h.kodemesin, i.processprdname,a.numoperator
				from billofmaterial a 
				left join product b on b.productid = a.productid 
				left join unitofmeasure c on c.unitofmeasureid = a.uomid 
				left join unitofmeasure d on d.unitofmeasureid = a.uom2id 
				left join unitofmeasure e on e.unitofmeasureid = a.uom3id 
				left join mesin h on h.mesinid = a.mesinid 
				left join processprd i on i.processprdid = a.processprdid 
				where a.bomid = ".$bomid;	
		$command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll(); 
    $this->pdf->AddPage('L',array(210,330));
    foreach ($dataReader as $row) {
      $pdf->SetFontSize(8);
      $pdf->text(15, $pdf->gety() + 5, 'Versi BOM');
      $pdf->text(40, $pdf->gety() + 5, ': ' . $row['bomversion']);
      $pdf->text(15, $pdf->gety() + 10, 'Tgl BOM ');
      $pdf->text(40, $pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['bomdate'])));
      $pdf->text(15, $pdf->gety() + 20, 'Material / Service');
      $pdf->text(40, $pdf->gety() + 20, ': ' . $row['productname']);
      $pdf->text(15, $pdf->gety() + 25, 'Qty');
      $pdf->text(40, $pdf->gety() + 25, ': ' . Yii::app()->format->formatNumber($row['qty']) . ' ' . $row['uomcode']);
      $pdf->text(15, $pdf->gety() + 30, 'Qty 2');
      $pdf->text(40, $pdf->gety() + 30, ': ' . Yii::app()->format->formatNumber($row['qty2']) . ' ' . $row['uom2code'] );
      $pdf->text(15, $pdf->gety() + 35, 'Qty 3');
      $pdf->text(40, $pdf->gety() + 35, ': ' . Yii::app()->format->formatNumber($row['qty3']) . ' ' . $row['uom3code'] );
      $pdf->text(15, $pdf->gety() + 45, 'Description');
      $pdf->text(40, $pdf->gety() + 45, ': ' . $row['description']);
      $pdf->text(15, $pdf->gety() + 50, 'Mesin');
      $pdf->text(40, $pdf->gety() + 50, ': ' . $row['kodemesin']);
      $pdf->text(15, $pdf->gety() + 55, 'Proses Produksi');
      $pdf->text(40, $pdf->gety() + 55, ': ' . $row['processprdname']);
      $pdf->text(15, $pdf->gety() + 60, 'Jumlah Operator');
      $pdf->text(40, $pdf->gety() + 60, ': ' . $row['numoperator']);
      $sql1        = "select a.bomid, a.bomdetailid,b.productname, a.qty, a.qty2, a.qty3, c.uomcode, e.uomcode as uom2code ,
				f.uomcode as uom3code, a.description, d.bomversion,a.productbomid,h.productname as parentname 
        from bomdetail a
        inner join product b on b.productid = a.productid
        inner join unitofmeasure c on c.unitofmeasureid = a.uomid
        left join unitofmeasure e on e.unitofmeasureid = a.uom2id
        left join unitofmeasure f on f.unitofmeasureid = a.uom3id
				left join billofmaterial d on d.bomid = a.productbomid
				left join product h on h.productid = a.productparentid 
        where a.bomid = '" . $row['bomid'] . "'
					order by bomdetailid";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $pdf->sety($pdf->gety() + 65);
      $pdf->colalign = array(
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
      $pdf->setwidths(array(
        10,
        90,
        25,
        25,
        25,
        25,
        30,
        50,
        30
      ));
      $pdf->colheader = array(
        'No',
        'Items',
        'Qty 1',
        'Qty 2',
        'Qty 3',
        'Qty 4',
        'BOM',
				'Artikel Induk',
        'Remark'
      );
      $pdf->RowHeader();
      $pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'C',
        'L',
        'L',
        'L'
      );
			$i=0;$productbom=array();
      foreach ($dataReader1 as $row1) {
				$i+=1;
        $pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['qty']).' '.$row1['uomcode'],
          Yii::app()->format->formatNumber($row1['qty2']).' '.$row1['uom2code'],
          Yii::app()->format->formatNumber($row1['qty3']).' '.$row1['uom3code'],
          $row1['bomversion'],
          $row1['parentname'],
					$row1['description']					
        ));
				if ($row1['productbomid'] != null) {
					array_push($productbom,$row1['productbomid']);
				}
      }
			for ($x=0;$x<count($productbom);$x++) {
				$this->PrintRecursivePDF($this->pdf,$productbom[$x]);
			}
		}
	}
  public function actionDownPDF() {
    parent::actionDownload();
		$sql = "select a.bomid,a.bomversion,a.bomdate,b.productname,a.qty,a.qty2,a.qty3,c.uomcode,d.uomcode as uom2code,
			e.uomcode as uom3code, a.description, h.kodemesin, i.processprdname,a.numoperator
				from billofmaterial a 
				left join product b on b.productid = a.productid 
				left join unitofmeasure c on c.unitofmeasureid = a.uomid 
				left join unitofmeasure d on d.unitofmeasureid = a.uom2id 
				left join unitofmeasure e on e.unitofmeasureid = a.uom3id 
				left join mesin h on h.mesinid = a.mesinid 
				left join processprd i on i.processprdid = a.processprdid 
				left join plant j on j.plantid = a.plantid 
				left join materialtype k on k.materialtypeid = b.materialtypeid 
				";
		$bomid       = GetSearchText(array('POST','GET'),'bomid');
    $bomversion  = GetSearchText(array('POST','GET'),'bomversion');
    $plantcode  = GetSearchText(array('POST','GET'),'plantcode');
		$bomdate   = GetSearchText(array('POST','GET'),'bomdate');
		$productcode   = GetSearchText(array('POST','GET'),'productcode');
		$productname   = GetSearchText(array('POST','GET'),'productname');
		$kodemesin     = GetSearchText(array('POST','GET'),'kodemesin');
		$namamesin     = GetSearchText(array('POST','GET'),'namamesin');
		$processprd     = GetSearchText(array('POST','GET'),'processprd');
    $description = GetSearchText(array('POST','GET'),'description');
    $materialtypecode = GetSearchText(array('POST','GET'),'materialtypecode');
		$sql.= "
			where coalesce(a.bomid,'') like '".$bomid."' 
			and coalesce(a.bomversion,'') like '".$bomversion."' 
			and coalesce(j.plantcode,'') like '".$plantcode."' 
			and coalesce(a.bomdate,'') like '".$bomdate."' 
			and coalesce(b.productcode,'') like '".$productcode."' 
			and coalesce(b.productname,'') like '".$productname."' 
			and coalesce(h.kodemesin,'') like '".$kodemesin."' 
			and coalesce(h.namamesin,'') like '".$namamesin."' 
			and coalesce(i.processprdname,'') like '".$processprd."'
			and coalesce(a.description,'') like '".$description."'
			and coalesce(k.materialtypecode,'') like '".$materialtypecode."'
		";
		if ($_GET['id'] !== '')  {
				$sql = $sql . " and a.bomid in (".$_GET['id'].")";
		}
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = getCatalog('bom');
    $this->pdf->AddPage('L',array(210,330));
    $this->pdf->SetFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'Versi BOM');
      $this->pdf->text(40, $this->pdf->gety() + 5, ': ' . $row['bomversion']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Tgl BOM');
      $this->pdf->text(40, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['bomdate'])));
      $this->pdf->text(15, $this->pdf->gety() + 20, 'Material / Service');
      $this->pdf->text(40, $this->pdf->gety() + 20, ': ' . $row['productname']);
      $this->pdf->text(15, $this->pdf->gety() + 25, 'Qty');
      $this->pdf->text(40, $this->pdf->gety() + 25, ': ' . Yii::app()->format->formatNumber($row['qty']) . ' ' . $row['uomcode']);
      $this->pdf->text(15, $this->pdf->gety() + 30, 'Qty 2');
      $this->pdf->text(40, $this->pdf->gety() + 30, ': ' . Yii::app()->format->formatNumber($row['qty2']) . ' ' . $row['uom2code'] );
      $this->pdf->text(15, $this->pdf->gety() + 35, 'Qty 3');
      $this->pdf->text(40, $this->pdf->gety() + 35, ': ' . Yii::app()->format->formatNumber($row['qty3']) . ' ' . $row['uom3code'] );
      $this->pdf->text(15, $this->pdf->gety() + 45, 'Description');
      $this->pdf->text(40, $this->pdf->gety() + 45, ': ' . $row['description']);
      $this->pdf->text(15, $this->pdf->gety() + 50, 'Mesin');
      $this->pdf->text(40, $this->pdf->gety() + 50, ': ' . $row['kodemesin']);
      $this->pdf->text(15, $this->pdf->gety() + 55, 'Proses Produksi');
      $this->pdf->text(40, $this->pdf->gety() + 55, ': ' . $row['processprdname']);
      $this->pdf->text(15, $this->pdf->gety() + 60, 'Jumlah Operator');
      $this->pdf->text(40, $this->pdf->gety() + 60, ': ' . $row['numoperator']);
      $sql1        = "select a.bomid, a.bomdetailid,b.productname, a.qty, a.qty2, a.qty3, c.uomcode, e.uomcode as uom2code ,
				f.uomcode as uom3code, a.description, d.bomversion,a.productbomid, h.productname as parentname
        from bomdetail a
        inner join product b on b.productid = a.productid
        inner join unitofmeasure c on c.unitofmeasureid = a.uomid
        left join unitofmeasure e on e.unitofmeasureid = a.uom2id
        left join unitofmeasure f on f.unitofmeasureid = a.uom3id
				left join billofmaterial d on d.bomid = a.productbomid
				left join product h on h.productid = a.productparentid 
        where a.bomid = '" . $row['bomid'] . "'
					order by bomdetailid";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 65);
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
      $this->pdf->setwidths(array(
        10,
        90,
        25,
        25,
        25,
        25,
        30,
        50,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Items',
        'Qty 1',
        'Qty 2',
        'Qty 3',
        'Qty 4',
        'BOM',
				'Artikel Induk',
        'Remark'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'C',
        'L',
        'L',
        'L'
      );
			$i=0;$productbom=array();
      foreach ($dataReader1 as $row1) {
				$i+=1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['qty']).' '.$row1['uomcode'],
          Yii::app()->format->formatNumber($row1['qty2']).' '.$row1['uom2code'],
          Yii::app()->format->formatNumber($row1['qty3']).' '.$row1['uom3code'],
          $row1['bomversion'],
					$row1['parentname'],
					$row1['description']
        ));
				if ($row1['productbomid'] != null) {
					array_push($productbom,$row1['productbomid']);
				}
      }
			for ($x=0;$x<count($productbom);$x++) {
				$this->PrintRecursivePDF($this->pdf,$productbom[$x]);
			}
      $this->pdf->checkNewPage(10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Approved By');
      $this->pdf->text(150, $this->pdf->gety() + 10, 'Proposed By');
      $this->pdf->text(10, $this->pdf->gety() + 30, '____________ ');
      $this->pdf->text(150, $this->pdf->gety() + 30, '____________');
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
		$this->menuname='bom';
    parent::actionDownxls();
    $sql = "select a.bomid,a.bomversion,a.bomdate,b.productname,a.qty,a.qty2,a.qty3,c.uomcode,d.uomcode as uom2code,
			e.uomcode as uom3code, j.plantcode,b.productcode,a.numoperator,a.recordstatus,a.description, h.kodemesin, i.processprdname,
			m.productname as detailproductname,l.qty as detailqty,l.qty2 as detailqty2,l.qty3 as detailqty3,n.uomcode as detailuom,
			o.uomcode as detailuom2,p.uomcode as detailuom3,
			r.bomversion as detailbomversion,l.description as detaildescription,m.productcode as detailproductcode
			from billofmaterial a 
			left join product b on b.productid = a.productid 
			left join unitofmeasure c on c.unitofmeasureid = a.uomid 
			left join unitofmeasure d on d.unitofmeasureid = a.uom2id 
			left join unitofmeasure e on e.unitofmeasureid = a.uom3id 
			left join mesin h on h.mesinid = a.mesinid 
			left join processprd i on i.processprdid = a.processprdid 
			left join plant j on j.plantid = a.plantid 
			left join materialtype k on k.materialtypeid = b.materialtypeid 
			left join bomdetail l on l.bomid = a.bomid
			left join product m on m.productid = a.productid 
			left join unitofmeasure n on n.unitofmeasureid = a.uomid 
			left join unitofmeasure o on o.unitofmeasureid = a.uom2id 
			left join unitofmeasure p on p.unitofmeasureid = a.uom3id 
			left join billofmaterial r on r.bomid = l.productbomid 
				";
		$bomid       = GetSearchText(array('POST','GET'),'bomid');
    $bomversion  = GetSearchText(array('POST','GET'),'bomversion');
    $plantcode  = GetSearchText(array('POST','GET'),'plantcode');
		$bomdate   = GetSearchText(array('POST','GET'),'bomdate');
		$productcode   = GetSearchText(array('POST','GET'),'productcode');
		$productname   = GetSearchText(array('POST','GET'),'productname');
		$kodemesin     = GetSearchText(array('POST','GET'),'kodemesin');
		$namamesin     = GetSearchText(array('POST','GET'),'namamesin');
		$processprd     = GetSearchText(array('POST','GET'),'processprd');
    $description = GetSearchText(array('POST','GET'),'description');
    $materialtypecode = GetSearchText(array('POST','GET'),'materialtypecode');
		$sql.= "
			where coalesce(a.bomid,'') like '".$bomid."' 
			and coalesce(a.bomversion,'') like '".$bomversion."' 
			and coalesce(j.plantcode,'') like '".$plantcode."' 
			and coalesce(a.bomdate,'') like '".$bomdate."' 
			and coalesce(b.productcode,'') like '".$productcode."' 
			and coalesce(b.productname,'') like '".$productname."' 
			and coalesce(h.kodemesin,'') like '".$kodemesin."' 
			and coalesce(h.namamesin,'') like '".$namamesin."' 
			and coalesce(i.processprdname,'') like '".$processprd."'
			and coalesce(a.description,'') like '".$description."'
			and coalesce(k.materialtypecode,'') like '".$materialtypecode."'
		";
		if ($_GET['id'] !== '')  {
				$sql = $sql . " and a.bomid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by a.bomid asc ";
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 1;$nourut=0;$oldbom='';
    foreach ($dataReader as $row) {
			if ($oldbom != $row['bomversion']) {
				$nourut+=1;
				$oldbom = $row['bomversion'];
			}
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $nourut)
				->setCellValueByColumnAndRow(1, $i+1, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $i+1, $row['bomversion'])
				->setCellValueByColumnAndRow(3, $i+1, date(Yii::app()->params['dateviewfromdb'], strtotime($row['bomdate'])))
				->setCellValueByColumnAndRow(5, $i+1, $row['productcode'])
				->setCellValueByColumnAndRow(6, $i+1, $row['productname'])
				->setCellValueByColumnAndRow(7, $i+1, Yii::app()->format->formatNumber($row['qty']))
				->setCellValueByColumnAndRow(8, $i+1, $row['uomcode'])
				->setCellValueByColumnAndRow(9, $i+1, Yii::app()->format->formatNumber($row['qty2']))
				->setCellValueByColumnAndRow(10, $i+1, $row['uom2code'])      
				->setCellValueByColumnAndRow(11, $i+1, Yii::app()->format->formatNumber($row['qty3']))
				->setCellValueByColumnAndRow(12, $i+1, $row['uom3code'])            
				->setCellValueByColumnAndRow(15, $i+1, $row['kodemesin'])            
				->setCellValueByColumnAndRow(16, $i+1, $row['numoperator'])
				->setCellValueByColumnAndRow(17, $i+1, $row['processprdname'])
				->setCellValueByColumnAndRow(18, $i+1, $row['description'])
				->setCellValueByColumnAndRow(19, $i+1, $row['recordstatus'])
				->setCellValueByColumnAndRow(20, $i+1, $row['detailproductcode'])
				->setCellValueByColumnAndRow(21, $i+1, $row['detailproductname'])
				->setCellValueByColumnAndRow(22, $i+1, Yii::app()->format->formatNumber($row['detailqty']))
				->setCellValueByColumnAndRow(23, $i+1, $row['detailuom'])
				->setCellValueByColumnAndRow(24, $i+1, Yii::app()->format->formatNumber($row['detailqty2']))
				->setCellValueByColumnAndRow(25, $i+1, $row['detailuom2'])
				->setCellValueByColumnAndRow(26, $i+1, Yii::app()->format->formatNumber($row['detailqty3']))
				->setCellValueByColumnAndRow(27, $i+1, $row['detailuom3'])
				->setCellValueByColumnAndRow(30, $i+1, $row['detailbomversion'])
				->setCellValueByColumnAndRow(31, $i+1, $row['detaildescription']);
			$i += 1;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}