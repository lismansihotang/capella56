<?php
class BsheaderController extends Controller {
  public $menuname = 'bsheader';
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
  public function actionIndexjurnal() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchjurnal();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
			$id = rand(-1, -1000000000);
			echo CJSON::encode(array(
				'bsheaderid' => $id,
			));
  }
  public function search() {
    header('Content-Type: application/json');
    $bsheaderid = GetSearchText(array('POST','GET'),'bsheaderid','','int');
		$plantcode     = GetSearchText(array('POST','Q'),'plantcode');
		$productname   = GetSearchText(array('POST','Q'),'productname');
    $sloccode     = GetSearchText(array('POST','Q'),'sloccode');
    $bsdate     = GetSearchText(array('POST','Q'),'bsdate');
    $bsheaderno = GetSearchText(array('POST','Q'),'bsheaderno');
    $headernote = GetSearchText(array('POST','Q'),'headernote');
    $recordstatus = GetSearchText(array('POST','Q'),'recordstatus');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','bsheaderid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('bsheader t')
			->leftjoin('sloc a', 'a.slocid = t.slocid')
			->leftjoin('plant b', 'b.plantid = t.plantid')
			->leftjoin('company c', 'c.companyid = b.companyid')
			->where("
			((coalesce(bsheaderid,'') like '%".$bsheaderid."%') 
			and (coalesce(plantcode,'') like '%".$plantcode."%') 
			and (coalesce(sloccode,'') like '%".$sloccode."%') 
			and (coalesce(bsheaderno,'') like '%".$bsheaderno."%') 
			and (coalesce(headernote,'') like '%".$headernote."%') 
			and (coalesce(bsdate,'') like '%".$bsdate."%'))
			and t.recordstatus in (".getUserRecordStatus('listbs').")".
			(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
			" and t.slocid in (".getUserObjectValues('sloc').") ".
			(($productname !== '%%')?"
				and t.bsheaderid in (
					select distinct z.bsheaderid 
					from bsdetail z 
					join product za on za.productid = z.productid 
					where coalesce(za.productname,'') like '".$productname."'
				)
			":''), 
			array())->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()->select('t.*,a.sloccode,b.plantcode,a.description as slocdesc,b.companyid,c.companyname')
			->from('bsheader t')
			->leftjoin('sloc a', 'a.slocid = t.slocid')
			->leftjoin('plant b', 'b.plantid = t.plantid')
			 ->leftjoin('company c', 'c.companyid = b.companyid')
			->where("
			((coalesce(bsheaderid,'') like '%".$bsheaderid."%') 
			and (coalesce(plantcode,'') like '%".$plantcode."%') 
			and (coalesce(sloccode,'') like '%".$sloccode."%') 
			and (coalesce(bsheaderno,'') like '%".$bsheaderno."%') 
			and (coalesce(headernote,'') like '%".$headernote."%') 
			and (coalesce(bsdate,'') like '%".$bsdate."%'))
			and t.recordstatus in (".getUserRecordStatus('listbs').")".
			(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
			" and t.slocid in (".getUserObjectValues('sloc').") ".
			(($productname !== '%%')?"
				and t.bsheaderid in (
					select distinct z.bsheaderid 
					from bsdetail z 
					join product za on za.productid = z.productid 
					where coalesce(za.productname,'') like '".$productname."'
				)
			":''), 
			array())->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		foreach ($cmd as $data) {
			$row[] = array(
				'bsheaderid' => $data['bsheaderid'],
				'plantid' => $data['plantid'],
				'plantcode' => $data['plantcode'],
				'companyid' => $data['companyid'],
				'companyname' => $data['companyname'],
				'slocid' => $data['slocid'],
				'sloccode' => $data['sloccode'],
				'slocdesc' => $data['slocdesc'],
				'bsdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['bsdate'])),
				'bsheaderno' => $data['bsheaderno'],
				'headernote' => $data['headernote'],
				'recordstatus' => $data['recordstatus'],
				'recordstatusbsheader' => $data['statusname']
			);
		}
    $result = array_merge($result, array(
      'rows' => $row
		));
    return CJSON::encode($result);
	}
	public function actionSearchDetail() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'bsdetailid';
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
					->from('bsdetail t')
					->leftjoin('bsheader g', 'g.bsheaderid = t.bsheaderid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('ownership c', 'c.ownershipid = t.ownershipid')
					->leftjoin('materialstatus d', 'd.materialstatusid = t.materialstatusid')
					->leftjoin('storagebin e', 'e.storagebinid = t.storagebinid')
					->leftjoin('currency f', 'f.currencyid = t.currencyid')
					->leftjoin('materialtype h', 'h.materialtypeid = a.materialtypeid')
					->where('t.bsheaderid = :bsheaderid',
					array(
				':bsheaderid' => $id
				))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,t.qty2,a.productcode,a.productname,c.ownershipname,d.materialstatusname,
						e.description,getstock(t.productid,t.uomid,g.slocid) as qtystock,ifnull(t.buyprice,0) as buypricestock,f.currencyname,f.symbol,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code,h.materialtypecode,
						GetStdQty2(a.productid) as stdqty2,
						GetStdQty3(a.productid) as stdqty3,
						GetStdQty4(a.productid) as stdqty4,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom4id) as uom4code')
					->from('bsdetail t')
					->leftjoin('bsheader g', 'g.bsheaderid = t.bsheaderid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('ownership c', 'c.ownershipid = t.ownershipid')
					->leftjoin('materialstatus d', 'd.materialstatusid = t.materialstatusid')
					->leftjoin('storagebin e', 'e.storagebinid = t.storagebinid')
					->leftjoin('currency f', 'f.currencyid = t.currencyid')
					->leftjoin('materialtype h', 'h.materialtypeid = a.materialtypeid')
					->where('t.bsheaderid = :bsheaderid', array(
		':bsheaderid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'bsdetailid' => $data['bsdetailid'],
        'bsheaderid' => $data['bsheaderid'],
        'productid' => $data['productid'],
				'productcode' => $data['productcode'],
				'productname' => $data['productname'],
				'materialtypecode' => $data['materialtypecode'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
				'qty2' => Yii::app()->format->formatNumber($data['qty2']),
				'qty3' => Yii::app()->format->formatNumber($data['qty3']),
				'qty4' => Yii::app()->format->formatNumber($data['qty4']),
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
				'lotno' => $data['lotno'],
        'ownershipid' => $data['ownershipid'],
        'ownershipname' => $data['ownershipname'],
        'materialstatusid' => $data['materialstatusid'],
        'materialstatusname' => $data['materialstatusname'],
        'storagebinid' => $data['storagebinid'],
				'description' => $data['description'],
			  'currencyid' => $data['currencyid'],
				'currencyname' => $data['currencyname'],
				'symbol' => $data['symbol'],
			  'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'buyprice' => Yii::app()->format->formatNumber($data['buyprice']),
        'buypricestock' => Yii::app()->format->formatNumber($data['buypricestock']),
        'totalvalue' => Yii::app()->format->formatNumber($data['totalvalue']),
        'buydate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['buydate'])),
        'itemnote' => $data['itemnote']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
	}
	public function actionSearchJurnal() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'bsjurnalid';
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
			->from('bsjurnal t')
			->leftjoin('bsheader g', 'g.bsheaderid = t.bsheaderid')
			->leftjoin('account a', 'a.accountid = t.accountid')
			->leftjoin('currency c', 'c.currencyid = t.currencyid')
			->where('t.bsheaderid = :bsheaderid',
			array(
				':bsheaderid' => $id
		))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.accountcode,a.accountname,c.currencyname')
			->from('bsjurnal t')
			->leftjoin('bsheader g', 'g.bsheaderid = t.bsheaderid')
			->leftjoin('account a', 'a.accountid = t.accountid')
			->leftjoin('currency c', 'c.currencyid = t.currencyid')
			->where('t.bsheaderid = :bsheaderid', array(
				':bsheaderid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'bsjurnalid' => $data['bsjurnalid'],
        'bsheaderid' => $data['bsheaderid'],
        'accountid' => $data['accountid'],
				'accountcode' => $data['accountcode'],
				'accountname' => $data['accountname'],
				'currencyid' => $data['currencyid'],
				'currencyname' => $data['currencyname'],
        'debit' => Yii::app()->format->formatNumber($data['debit']),
				'credit' => Yii::app()->format->formatNumber($data['credit']),
				'detailnote' => $data['detailnote'],
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
		));
		$sql = "select sum(debit) as debit, sum(credit) as credit from bsjurnal where bsheaderid = ".$id;
		$cmd = Yii::app()->db->createCommand($sql)->queryRow();
		$footer[] = array(
      'accountname' => 'Total',
      'debit' => Yii::app()->format->formatNumber($cmd['debit']),
      'credit' => Yii::app()->format->formatNumber($cmd['credit']),
    );
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionReject() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call RejectBS(:vid,:vdatauser)';
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
  public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveBS(:vid,:vdatauser)';
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
		$sql = 'call ModifBsheader(:vid,:vbsdate,:vplantid,:vslocid,:vheadernote,:vdatauser)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$command->bindvalue(':vbsdate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vslocid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionSave() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['bsheader-bsheaderid'])?$_POST['bsheader-bsheaderid']:''),
			date(Yii::app()->params['datetodb'], strtotime($_POST['bsheader-bsdate'])),
			$_POST['bsheader-plantid'],$_POST['bsheader-slocid'],$_POST['bsheader-headernote']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
	public function ModifyDataDetail($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertbsdetail(:vbsheaderid,:vproductid,:vunitofmeasureid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,:vqty4,:vstoragebinid,
				:vmaterialstatusid,:vownershipid,:vlotno,:vbuyprice,:vcurrencyid,:vcurrencyrate,:vbuydate,:vitemnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatebsdetail(:vid,:vbsheaderid,:vproductid,:vunitofmeasureid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,:vqty4,:vstoragebinid,
				:vmaterialstatusid,:vownershipid,:vlotno,:vbuyprice,:vcurrencyid,:vcurrencyrate,:vbuydate,:vitemnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		}
		$command->bindvalue(':vbsheaderid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vunitofmeasureid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vuom2id', $arraydata[4], PDO::PARAM_STR);
	  $command->bindvalue(':vuom3id', $arraydata[5], PDO::PARAM_STR);
	  $command->bindvalue(':vuom4id', $arraydata[6], PDO::PARAM_STR);
	  $command->bindvalue(':vqty', $arraydata[7], PDO::PARAM_STR);
	  $command->bindvalue(':vqty2', $arraydata[8], PDO::PARAM_STR);
	  $command->bindvalue(':vqty3', $arraydata[9], PDO::PARAM_STR);
	  $command->bindvalue(':vqty4', $arraydata[10], PDO::PARAM_STR);
	  $command->bindvalue(':vstoragebinid', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vmaterialstatusid', $arraydata[12], PDO::PARAM_STR);
		$command->bindvalue(':vownershipid', $arraydata[13], PDO::PARAM_STR);
	  $command->bindvalue(':vlotno', $arraydata[14], PDO::PARAM_STR);
	  $command->bindvalue(':vbuyprice',$arraydata[15], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid', $arraydata[16], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyrate', $arraydata[17], PDO::PARAM_STR);
		$command->bindvalue(':vbuydate', $arraydata[18], PDO::PARAM_STR);
		$command->bindvalue(':vitemnote', $arraydata[19], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavedetail() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataDetail($connection,array((isset($_POST['bsdetailid'])?$_POST['bsdetailid']:''),
				$_POST['bsheaderid'],
				$_POST['productid'],
				$_POST['uomid'],
				$_POST['uom2id'],
				$_POST['uom3id'],
				$_POST['uom4id'],
				$_POST['qty'],
				$_POST['qty2'],
				$_POST['qty3'],
				$_POST['qty4'],
				$_POST['storagebinid'],
				$_POST['materialstatusid'],
				$_POST['ownershipid'],
				$_POST['lotno'],
				$_POST['buyprice'],
				$_POST['currencyid'],
				$_POST['currencyrate'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['buydate'])),
				$_POST['itemnote']
			));
      $transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-bsheader"]["name"]);
		if (move_uploaded_file($_FILES["file-bsheader"]["tmp_name"], $target_file)) {
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
					$sloccode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
					$bsdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(3, $row)->getValue()));
					$description = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$sql = "select count(0) 
						from bsheader 
						where plantid = '".$plantid."' 
						and slocid = '".$slocid."' 
						and bsdate = '".$bsdate."'
						and headernote = '".$description."' 
						and recordstatus = 1
						";
					$pid = Yii::app()->db->createCommand($sql)->queryScalar();
					if ($pid == 0) {
						$this->ModifyData($connection,array(-1,$bsdate,$plantid,$slocid,$description));
					} 
					$pid = Yii::app()->db->createCommand("select bsheaderid 
							from bsheader 
							where plantid = '".$plantid."' 
							and slocid = '".$slocid."' 
							and bsdate = '".$bsdate."'
							and headernote = '".$description."'  
							and recordstatus = 1 
							")->queryScalar();
					if ($pid > 0) {
						$productname = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
						$sql = "select productid from product where productname = :productname";
						$command=$connection->createCommand($sql);
						$command->bindvalue(':productname',$productname,PDO::PARAM_STR);
						$productid = $command->queryScalar();
						$qty = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
						$uomcode = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
						$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$qty2 = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
						$uomcode = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
						$uom2id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$qty3 = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
						$uomcode = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
						$uom3id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$qty4 = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
						$uomcode = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
						$uom4id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$storagedesc = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
						$storagebinid = Yii::app()->db->createCommand("select storagebinid from storagebin where description = '".$storagedesc."'")->queryScalar();
						$materialstatusname = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
						$materialstatusid = Yii::app()->db->createCommand("select materialstatusid from materialstatus where materialstatusname = '".$materialstatusname."'")->queryScalar();
						$ownershipname = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
						$ownershipid = Yii::app()->db->createCommand("select ownershipid from ownership where ownershipname = '".$ownershipname."'")->queryScalar();
						$lotno = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
						$buyprice = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
						$buydate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(19, $row)->getValue()));
						$symbol = $objWorksheet->getCellByColumnAndRow(20, $row)->getValue();
						$currencyid = Yii::app()->db->createCommand("select currencyid from currency where symbol = '".$symbol."'")->queryScalar();
						$currencyrate = $objWorksheet->getCellByColumnAndRow(21, $row)->getValue();
						$description = $objWorksheet->getCellByColumnAndRow(22, $row)->getValue();
						$this->ModifyDataDetail($connection,array('',$pid,$productid,$uomid,$uom2id,$uom3id,$uom4id,$qty,$qty2,$qty3,$qty4,
							$storagebinid,$materialstatusid,$ownershipid,$lotno,$buyprice,$currencyid,$currencyrate,$buydate,$description));
					}
				}
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,'Line: '.$row.' ==> '.implode(" ",$e->errorInfo));
			}
    }
	}
  public function actionPurge() {
		parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgebs(:vid,:vdatauser)';
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
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call PurgeBsdetail(:vid,:vdatauser)';
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
    $sql = "select a.bsheaderid,a.bsheaderno,a.bsdate,b.sloccode,a.headernote
						from bsheader a
						inner join sloc b on b.slocid = a.slocid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.bsheaderid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = getCatalog('bsheader');
    $this->pdf->AddPage('P', array(
			330,
      210
    ));
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->text(15, $this->pdf->gety(), 'No ');
      $this->pdf->text(30, $this->pdf->gety(), ': ' . $row['bsheaderno']);
      $this->pdf->text(70, $this->pdf->gety(), 'Tgl ');
      $this->pdf->text(95, $this->pdf->gety(), ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['bsdate'])));
      $this->pdf->text(120, $this->pdf->gety(), 'Gudang ');
      $this->pdf->text(150, $this->pdf->gety(), ': ' . $row['sloccode']);
      $i           = 0;
      $totalqty    = 0;
	  $totalqty2    = 0;
	  $totalqty3    = 0;
	  $totalqty4    = 0;
      $totaljumlah = 0;
      $sql1        = "select b.productcode,b.productname,a.qty,a.qty2,a.qty3,a.qty4,a.buyprice,c.uomcode,g.uomcode as uom2code,
				h.uomcode as uom3code, i.uomcode as uom4code,a.itemnote,d.ownershipname,a.buydate,e.materialstatusname,f.description,a.totalvalue
				from bsdetail a
				inner join product b on b.productid = a.productid
				inner join unitofmeasure c on c.unitofmeasureid = a.uomid
				left join unitofmeasure g on g.unitofmeasureid = a.uom2id
				left join unitofmeasure h on h.unitofmeasureid = a.uom3id
				left join unitofmeasure i on i.unitofmeasureid = a.uom4id
				inner join ownership d on d.ownershipid = a.ownershipid
				inner join materialstatus e on e.materialstatusid = a.materialstatusid
				inner join storagebin f on f.storagebinid = a.storagebinid
				where bsheaderid = " . $row['bsheaderid'] . " order by bsdetailid ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 3);
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
		'C',
        'C',
        'C'
      );
      $this->pdf->setFont('Arial', 'B', 7);
      $this->pdf->setwidths(array(
        7,
        80,
        22,
		22,
		22,
        15,
        22,
        30,
        30,
        30,
				20
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
		'Qty 2',
		'Qty 3',
		'Qty 4',
        'Buyprice',
        'Jumlah',
        'Rak',
        'Status',
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
        'R',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
			);
			$total = 0;
      foreach ($dataReader1 as $row1) {
				$i = $i + 1;
				$total = $total + ($row1['totalvalue']);
				$this->pdf->row(array(
					$i,
					$row1['productname'],
					Yii::app()->format->formatNumber($row1['qty']).' '.$row1['uomcode'],
					Yii::app()->format->formatNumber($row1['qty2']).' '.$row1['uom2code'],
					Yii::app()->format->formatNumber($row1['qty3']).' '.$row1['uom3code'],
					Yii::app()->format->formatNumber($row1['qty4']).' '.$row1['uom4code'],
					Yii::app()->format->formatCurrency($row1['buyprice']),
					Yii::app()->format->formatCurrency($row1['totalvalue']),
					$row1['description'],
					$row1['ownershipname'] . '-' . $row1['materialstatusname'],
					$row1['itemnote']
				));
				$totalqty += $row1['qty'];
				$totalqty2 += $row1['qty2'];
				$totalqty3 += $row1['qty3'];
				$totalqty4 += $row1['qty4'];
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
				Yii::app()->format->formatNumber($totalqty4),
        '',
				Yii::app()->format->formatCurrency($total),
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
			$this->pdf->CheckPageBreak(40);
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
    }
    $this->pdf->Output();
  }
	public function actionDownxls() {
    $this->menuname = 'stokopnamelist';
    parent::actionDownxls();
    $bsheaderid = GetSearchText(array('POST','GET','Q'),'bsheaderid');
		$plantcode   = GetSearchText(array('POST','GET','Q'),'plantcode');
		$productname   = GetSearchText(array('POST','GET','Q'),'productname');
    $bsdate     = GetSearchText(array('POST','GET','Q'),'bsdate');
    $bsheaderno = GetSearchText(array('POST','GET','Q'),'bsheaderno');
    $headernote = GetSearchText(array('POST','GET','Q'),'headernote');
		$sql = "select a.bsheaderid,a.bsheaderno,a.bsdate,b.sloccode,a.headernote,d.plantcode,
				e.productname,c.qty,c.qty2,c.qty3,c.qty4,c.buyprice,f.uomcode,g.uomcode as uom2code,c.buyprice,
				h.uomcode as uom3code, i.uomcode as uom4code,c.itemnote,j.ownershipname,c.buydate,k.materialstatusname,l.description,
				c.lotno,m.symbol,c.currencyrate
			from bsheader a
			left join sloc b on b.slocid = a.slocid 
			left join bsdetail c on c.bsheaderid = a.bsheaderid 
			left join plant d on d.plantid = a.plantid 
			left join product e on e.productid = c.productid
			left join unitofmeasure f on f.unitofmeasureid = c.uomid
			left join unitofmeasure g on g.unitofmeasureid = c.uom2id
			left join unitofmeasure h on h.unitofmeasureid = c.uom3id
			left join unitofmeasure i on i.unitofmeasureid = c.uom4id
			left join ownership j on j.ownershipid = c.ownershipid
			left join materialstatus k on k.materialstatusid = c.materialstatusid
			left join storagebin l on l.storagebinid = c.storagebinid
			left join currency m on m.currencyid = c.currencyid
		";
		$sql .= " where coalesce(a.bsheaderid,'') like '".$bsheaderid."' 
			and coalesce(d.plantcode,'') like '".$plantcode."' 
			and coalesce(a.bsdate,'') like '".$bsdate."' 
			and coalesce(a.bsheaderno,'') like '".$bsheaderno."' 
			and coalesce(a.headernote,'') like '".$headernote."'".
			(($productname != '%%')?"
				and coalesce(e.productname,'') like '".$productname."'
			":'')
		;
		if ($_GET['id'] !== '') {
      $sql = $sql . " and a.bsheaderid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 2;$oldtax = '';
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $line, $line-1)
				->setCellValueByColumnAndRow(1, $line, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $line, $row['sloccode'])
				->setCellValueByColumnAndRow(3, $line, date(Yii::app()->params['dateviewfromdb'], strtotime($row['bsdate'])))
				->setCellValueByColumnAndRow(4, $line, $row['headernote'])
				->setCellValueByColumnAndRow(5, $line, $row['productname'])
				->setCellValueByColumnAndRow(6, $line, Yii::app()->format->formatNumber($row['qty']))
				->setCellValueByColumnAndRow(7, $line, $row['uomcode'])
				->setCellValueByColumnAndRow(8, $line, Yii::app()->format->formatNumber($row['qty2']))
				->setCellValueByColumnAndRow(9, $line, $row['uom2code'])
				->setCellValueByColumnAndRow(10, $line, Yii::app()->format->formatNumber($row['qty3']))
				->setCellValueByColumnAndRow(11, $line, $row['uom3code'])
				->setCellValueByColumnAndRow(12, $line, Yii::app()->format->formatNumber($row['qty4']))
				->setCellValueByColumnAndRow(13, $line, $row['uom4code'])
				->setCellValueByColumnAndRow(14, $line, $row['description'])
				->setCellValueByColumnAndRow(15, $line, $row['materialstatusname'])
				->setCellValueByColumnAndRow(16, $line, $row['ownershipname'])
				->setCellValueByColumnAndRow(17, $line, $row['lotno'])
				->setCellValueByColumnAndRow(18, $line, Yii::app()->format->formatNumber($row['buyprice']))
				->setCellValueByColumnAndRow(19, $line, date(Yii::app()->params['dateviewfromdb'], strtotime($row['buydate'])))
				->setCellValueByColumnAndRow(20, $line, $row['symbol'])
				->setCellValueByColumnAndRow(21, $line, $row['currencyrate'])
				->setCellValueByColumnAndRow(22, $line, $row['itemnote'])
				;
			$line++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}