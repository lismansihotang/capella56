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
    header("Content-Type: application/json");
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
    $customer = GetSearchText(array('POST','GET'),'customer');
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
				':customer' =>  $customer ,
				':namamesin' =>  $namamesin ,
				':customer' =>  $customer ,
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
    header("Content-Type: application/json");
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
GetStdQty(a.productid) as stdqty,
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
				'stdqty' => Yii::app()->format->formatNumber($data['stdqty']),
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
		$sql = 'call ModifBOM(:vid,:vplantid,:vbomversion,:vproductid,:vqty,:vqty2,:vqty3,:vuomid,:vuom2id,:vuom3id,:vbomdate,:vmesinid,:vnumoperator,:vprocessprdid,:vdescription,:vrecordstatus,:vdatauser)';
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
						$customer = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
						$qty = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
						$uomcode = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
						$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$qty2 = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
						$uomcode = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
						$uom2id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$qty3 = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
						$uomcode = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
						$uom3id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$uomcode = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
						$mesin = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
						$mesinid = Yii::app()->db->createCommand("select mesinid from mesin where kodemesin = '".$mesin."' and plantid = ".$plantid)->queryScalar();
						$numoperator = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
						$processprd = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
						$processprdid = Yii::app()->db->createCommand("select processprdid from processprd where processprdname = '".$processprd."'")->queryScalar();
						$description = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
						$recordstatus = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
						$this->ModifyData($connection,array(-1,$plantid,$bomversion,$productid,$qty,$qty2,$qty3,$uomid,$uom2id,$uom3id,
							$bomdate,$mesinid,$numoperator,$processprdid,$description,$recordstatus));
						$sql = "select bomid from billofmaterial where bomversion = :bomversion and productid = :productid and plantid = :plantid";
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
						$qty = $objWorksheet->getCellByColumnAndRow(22, $row)->getValue();
						$uomcode = $objWorksheet->getCellByColumnAndRow(23, $row)->getValue();
						$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$qty2 = $objWorksheet->getCellByColumnAndRow(24, $row)->getValue();
						$uomcode = $objWorksheet->getCellByColumnAndRow(25, $row)->getValue();
						$uom2id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$qty3 = $objWorksheet->getCellByColumnAndRow(26, $row)->getValue();
						$uomcode = $objWorksheet->getCellByColumnAndRow(27, $row)->getValue();
						$uom3id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$uomcode = $objWorksheet->getCellByColumnAndRow(29, $row)->getValue();
						$productbom = $objWorksheet->getCellByColumnAndRow(30, $row)->getValue();
						$sql = "select bomid from billofmaterial where bomversion = :bomversion and productid = :productid and plantid = :plantid";
						$command=$connection->createCommand($sql);
						$command->bindvalue(':bomversion',$productbom,PDO::PARAM_STR);
						$command->bindvalue(':productid',$productid,PDO::PARAM_STR);
						$command->bindvalue(':plantid',$plantid,PDO::PARAM_STR);
						$productbomid = $command->queryScalar();
						$productname = $objWorksheet->getCellByColumnAndRow(31, $row)->getValue();
						$sql = "select productid from product where productname = :productname";
						$command=$connection->createCommand($sql);
						$command->bindvalue(':productname',$productname,PDO::PARAM_STR);
						$productparentid = $command->queryScalar();
						$description = $objWorksheet->getCellByColumnAndRow(32, $row)->getValue();
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
				$_POST['bom-qty'],$_POST['bom-qty2'],$_POST['bom-qty3'],$_POST['bom-uomid'],$_POST['bom-uom2id'],$_POST['bom-uom3id'],
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
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['titleid'] = GetCatalog('bomid');
		$this->dataprint['titlebomversion'] = GetCatalog('bomversion');
		$this->dataprint['titleplantcode'] = GetCatalog('plantcode');
		$this->dataprint['titlebomdate'] = GetCatalog('bomdate');
		$this->dataprint['titleproductcode'] = GetCatalog('productcode');
		$this->dataprint['titleproductname'] = GetCatalog('productname');
		$this->dataprint['titlekodemesin'] = GetCatalog('kodemesin');
		$this->dataprint['titlenamamesin'] = GetCatalog('namamesin');
		$this->dataprint['titleprocessprdname'] = GetCatalog('processprdname');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titlematerialtypecode'] = GetCatalog('materialtypecode');
		$this->dataprint['titleparentname'] = GetCatalog('parentname');
		$this->dataprint['titleqty'] = GetCatalog('qty');
		$this->dataprint['titleqty2'] = GetCatalog('qty2');
		$this->dataprint['titleqty3'] = GetCatalog('qty3');
		$this->dataprint['titlenumoperator'] = GetCatalog('numoperator');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
		$this->dataprint['REPORT_LOCALE'] = "id_ID";
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['bomversion'] = GetSearchText(array('GET'),'bomversion');
    $this->dataprint['plantcode'] = GetSearchText(array('GET'),'plantcode');
    $this->dataprint['bomdate'] = GetSearchText(array('GET'),'bomdate');
    $this->dataprint['productcode'] = GetSearchText(array('GET'),'productcode');
    $this->dataprint['productname'] = GetSearchText(array('GET'),'productname');
    $this->dataprint['kodemesin'] = GetSearchText(array('GET'),'kodemesin');
    $this->dataprint['namamesin'] = GetSearchText(array('GET'),'namamesin');
    $this->dataprint['processprdname'] = GetSearchText(array('GET'),'processprdname');
    $this->dataprint['description'] = GetSearchText(array('GET'),'description');
    $this->dataprint['customer'] = GetSearchText(array('GET'),'customer');
    $this->dataprint['materialtypecode'] = GetSearchText(array('GET'),'materialtypecode');
  }
}
