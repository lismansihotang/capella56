<?php
class CertoaController extends Controller {
  public $menuname = 'certoa';
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
			'certoaid' => $id
		));
	}
  public function search() {
    header('Content-Type: application/json');
    $certoaid       = isset($_POST['certoaid']) ? $_POST['certoaid'] : '';
		 $soheaderid = GetSearchText(array('POST','GET','Q'),'soheaderid',0,'int');
    $plantcode  = isset($_POST['plantcode']) ? $_POST['plantcode'] : '';
		$productcode   = isset($_POST['productcode']) ? $_POST['productcode'] : '';
		$productname   = isset($_POST['productname']) ? $_POST['productname'] : '';   
		$customer   = isset($_POST['customer']) ? $_POST['customer'] : '';   
		$bomversion   = isset($_POST['bomversion']) ? $_POST['bomversion'] : '';   
		$sono   = isset($_POST['sono']) ? $_POST['sono'] : '';   
    $certoaid       = isset($_GET['certoaid']) ? $_GET['certoaid'] : $certoaid;
    $plantcode  = isset($_GET['plantcode']) ? $_GET['plantcode'] : $plantcode;
		$productcode   = isset($_GET['productcode']) ? $_GET['productcode'] : $productcode;
		$productname   = isset($_GET['productname']) ? $_GET['productname'] : $productname;   
		$customer   = isset($_GET['customer']) ? $_GET['customer'] : $customer;   
		$bomversion   = isset($_GET['bomversion']) ? $_GET['bomversion'] : $bomversion;   
		$sono   = isset($_GET['sono']) ? $_GET['sono'] : $sono;   
    $page        = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows        = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort        = isset($_POST['sort']) ? strval($_POST['sort']) : 'certoaid';
    $order       = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset      = ($page - 1) * $rows;
    $page        = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows        = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort        = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order       = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset      = ($page - 1) * $rows;
    $result      = array();
    $row         = array();
		if (isset($_GET['coapl'])) {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('certoa t')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('plant b', 'b.plantid = t.plantid')
			->leftjoin('addressbook c', 'c.addressbookid = t.addressbookid')
			->leftjoin('company d', 'd.companyid = b.companyid')
			->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
			->leftjoin('billofmaterial f', 'f.bomid = t.bomid')
			->leftjoin('materialtype g', 'g.materialtypeid = a.materialtypeid')
			->where("
					((coalesce(certoaid,'') like :certoaid) 
			or (coalesce(b.plantcode,'') like :plantcode) 
			or (coalesce(c.fullname,'') like :customer) 
			or (coalesce(a.productcode,'') like :productcode) 
			or (coalesce(a.productname,'') like :productname)
			or (coalesce(f.bomversion,'') like :bomversion)
			or (coalesce(e.sono,'') like :sono))
			and t.certoano is not null 
						and t.soheaderid = ".$soheaderid."
						and t.recordstatus = getwfmaxstatbywfname('appcoa')
						and t.plantid in (".getUserObjectValues('plant').")
						",
			array(
			':certoaid' => '%' . $certoaid . '%',
			':plantcode' => '%' . $plantcode . '%',
			':productcode' => '%' . $productcode . '%',
			':productname' => '%' . $productname . '%',
			':bomversion' => '%' . $bomversion . '%',
			':sono' => '%' . $sono . '%',
			':customer' => '%' . $customer . '%', 
					))->queryScalar();
			}
			else
		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('certoa t')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('plant b', 'b.plantid = t.plantid')
			->leftjoin('addressbook c', 'c.addressbookid = t.addressbookid')
			->leftjoin('company d', 'd.companyid = b.companyid')
			->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
			->leftjoin('billofmaterial f', 'f.bomid = t.bomid')
			->leftjoin('materialtype g', 'g.materialtypeid = a.materialtypeid')
			->where("((coalesce(certoaid,'') like :certoaid) 
			and (coalesce(b.plantcode,'') like :plantcode) 
			and (coalesce(c.fullname,'') like :customer) 
			and (coalesce(a.productcode,'') like :productcode) 
			and (coalesce(a.productname,'') like :productname)
			and (coalesce(f.bomversion,'') like :bomversion)
			and (coalesce(e.sono,'') like :sono))
			", array(
			':certoaid' => '%' . $certoaid . '%',
			':plantcode' => '%' . $plantcode . '%',
			':productcode' => '%' . $productcode . '%',
			':productname' => '%' . $productname . '%',
			':bomversion' => '%' . $bomversion . '%',
			':sono' => '%' . $sono . '%',
			':customer' => '%' . $customer . '%',
		))->queryScalar();
    $result['total'] = $cmd;
		 if (isset($_GET['coapl'])) {
			 $cmd = Yii::app()->db->createCommand()
			->select('t.*,a.productcode,a.productname,b.plantcode,c.fullname,d.companyname,e.sono,f.bomversion,g.materialtypecode')
			->from('certoa t')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('plant b', 'b.plantid = t.plantid')
			->leftjoin('addressbook c', 'c.addressbookid = t.addressbookid')
			->leftjoin('company d', 'd.companyid = b.companyid')
			->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
			->leftjoin('billofmaterial f', 'f.bomid = t.bomid')
			->leftjoin('materialtype g', 'g.materialtypeid = a.materialtypeid')
			->where("((coalesce(certoaid,'') like :certoaid) 
			or (coalesce(b.plantcode,'') like :plantcode) 
			or (coalesce(c.fullname,'') like :customer) 
			or (coalesce(a.productcode,'') like :productcode) 
			or (coalesce(a.productname,'') like :productname)
			or (coalesce(f.bomversion,'') like :bomversion)
			or (coalesce(e.sono,'') like :sono))
			and t.certoano is not null 
						and t.soheaderid = ".$soheaderid."
						and t.recordstatus = getwfmaxstatbywfname('appgi')
						and t.plantid in (".getUserObjectValues('plant').")
			", array(
			':certoaid' => '%' . $certoaid . '%',
			':plantcode' => '%' . $plantcode . '%',
			':productcode' => '%' . $productcode . '%',
			':productname' => '%' . $productname . '%',
			':bomversion' => '%' . $bomversion . '%',
			':sono' => '%' . $sono . '%',
			':customer' => '%' . $customer . '%',
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		} else
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.productcode,a.productname,b.plantcode,c.fullname,d.companyname,e.sono,f.bomversion,g.materialtypecode')
			->from('certoa t')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('plant b', 'b.plantid = t.plantid')
			->leftjoin('addressbook c', 'c.addressbookid = t.addressbookid')
			->leftjoin('company d', 'd.companyid = b.companyid')
			->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
			->leftjoin('billofmaterial f', 'f.bomid = t.bomid')
			->leftjoin('materialtype g', 'g.materialtypeid = a.materialtypeid')			
			->where("((coalesce(certoaid,'') like :certoaid) 
			and (coalesce(b.plantcode,'') like :plantcode) 
			and (coalesce(c.fullname,'') like :customer) 
			and (coalesce(a.productcode,'') like :productcode) 
			and (coalesce(a.productname,'') like :productname)
			and (coalesce(f.bomversion,'') like :bomversion)
			and (coalesce(e.sono,'') like :sono))
			", array(
			':certoaid' => '%' . $certoaid . '%',
			':plantcode' => '%' . $plantcode . '%',
			':productcode' => '%' . $productcode . '%',
			':productname' => '%' . $productname . '%',
			':bomversion' => '%' . $bomversion . '%',
			':sono' => '%' . $sono . '%',
			':customer' => '%' . $customer . '%',
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'certoaid' => $data['certoaid'],
        'certoano' => $data['certoano'],
        'certoadate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['certoadate'])),
        'productiondate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['productiondate'])),
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'companyname' => $data['companyname'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'productcode' => $data['productcode'],
        'materialtypecode' => $data['materialtypecode'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'bomid' => $data['bomid'],
        'bomversion' => $data['bomversion'],
        'soheaderid' => $data['soheaderid'],
        'sono' => $data['sono'],
        'status' => $data['status'],
        'description' => $data['description'],
        'recordstatus' => $data['recordstatus'],
        'statusname' => $data['statusname'],
				'jumkirim' => Yii::app()->format->formatNumber($data['jumkirim']),
        
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'certoadetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('certoadetail t')
				->leftjoin('qcparam b', 'b.qcparamid = t.qcparamid')
				->where('t.certoaid = :certoaid', array(
      ':certoaid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
				->select('t.*,b.qcparamname')
				->from('certoadetail t')
				->leftjoin('qcparam b', 'b.qcparamid = t.qcparamid')
				->where('t.certoaid = :certoaid', array(
      ':certoaid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) { 
      $row[] = array(
        'certoadetailid' => $data['certoadetailid'],
        'certoaid' => $data['certoaid'],
        'qcparamid' => $data['qcparamid'],
				'qcparamname' => $data['qcparamname'],
        'methodtest' => $data['methodtest'],
        'unittest' => $data['unittest'],
				'specmin' => $data['specmin'],
				'rangetarget' => $data['rangetarget'],
				'tolerancemin' => $data['tolerancemin'],
				'resulttest' => $data['resulttest'],
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
	private function ModifyData($connection,$arraydata) {
		$id = (int)$arraydata[0];
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$sql = 'call Modifcertoa(:vid,:vcertoadate,:vplantid,:vproductiondate,:vsoheaderid,:vaddressbookid,:vproductid,:vbomid,:vjumkirim,:vstatus,:vdescription,:vdatauser)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$command->bindvalue(':vcertoadate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vproductiondate', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vsoheaderid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vbomid', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vjumkirim', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vstatus', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vdescription', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();			
	}	
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-certoa"]["name"]);
		if (move_uploaded_file($_FILES["file-certoa"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$abid = '';$nourut = '';
			$connection  = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$plantcode = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$certoaversion = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$certoadate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(2, $row)->getValue()));
					$productname = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$productid = Yii::app()->db->createCommand("select productid from product where productname = '".$productname."'")->queryScalar();
					$qty = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
          $qtystdkg = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
          $qtystdmtr = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$uomcode = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
					$description = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$this->ModifyData($connection,array($id,$certoaversion,$productid,$qty,$qtystdkg,$qtystdmtr,$uomid,$certoadate,$description,1));
					if ($id == '') {					
						//get id addressbookid
						$id = Yii::app()->db->createCommand("select certoaid from billofmaterial where certoaversion = '".$certoaversion."'")->queryScalar();
					}
					if ($id != '') {
						if ($objWorksheet->getCellByColumnAndRow(9, $row)->getValue() != '') {
							$detailid = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
							$productname = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
							$productid = Yii::app()->db->createCommand("select productid from product where productname = '".$productname."'")->queryScalar();
              $qty = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
              $qtystdkg = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
              $qtystdmtr = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
							$uomcode = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
							$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$productcertoa = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
							$productcertoaid = Yii::app()->db->createCommand("select certoaid from billofmaterial where certoaversion = '".$productcertoa."'")->queryScalar();
							$description = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
							$this->ModifyDataDetail($connection,array($detailid,$id,$productid,$qty,$qtystdkg,$qtystdmtr,$uomid,$productcertoaid,$description));
						}
					}
				}
				$transaction->commit();
				GetMessage(false, getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollBack();
				GetMessage(true, $e->getMessage());
			}
    }
	}
  public function actionSave() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['certoa-certoaid'])?$_POST['certoa-certoaid']:''),
				date(Yii::app()->params['datetodb'], strtotime($_POST['certoa-certoadate'])),
				$_POST['certoa-plantid'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['certoa-productiondate'])),
				$_POST['certoa-soheaderid'],
				$_POST['certoa-addressbookid'],
				$_POST['certoa-productid'],
				$_POST['certoa-bomid'],
				$_POST['certoa-jumkirim'],
				(isset($_POST['certoa-status']) ? 1 : 0),
				$_POST['certoa-description'],
				));
			$transaction->commit();
			GetMessage(false, getcatalog('insertsuccess'));
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true, $e->getMessage());
		}
  }
	private function ModifyDataDetail($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertcertoadetail(:vcertoaid,:vqcparamid,:vmethodtest,:vunittest,:vspecmin,:vrangetarget,:vtolerancemin,:vresulttest,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatecertoadetail(:vid,:vcertoaid,:vqcparamid,:vmethodtest,:vunittest,:vspecmin,:vrangetarget,:vtolerancemin,:vresulttest,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		}
		$command->bindvalue(':vcertoaid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vqcparamid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vmethodtest', $arraydata[3], PDO::PARAM_STR);
    $command->bindvalue(':vunittest', $arraydata[4], PDO::PARAM_STR);
    $command->bindvalue(':vspecmin', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vrangetarget', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vtolerancemin', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vresulttest', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavedetail() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataDetail($connection,array((isset($_POST['certoadetailid'])?$_POST['certoadetailid']:''),
			$_POST['certoaid'],
			$_POST['qcparamid'],
			$_POST['methodtest'],
			$_POST['unittest'],
			$_POST['specmin'],
			$_POST['rangetarget'],
			$_POST['tolerancemin'],
			$_POST['resulttest']));
			$transaction->commit();
      GetMessage(false, getcatalog('insertsuccess'));
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
  public function actionPurge() {
    parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgecertoa(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  public function actionPurgedetail() {
    parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgecertoadetail(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
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
        $sql     = 'call RejectCertoa(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  public function actionApprove()
  {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveCertoa(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select t.certoaid, a.productname, c.fullname, e.sono, f.bomversion, t.certoadate, 
						t.jumkirim, t.description,t.productiondate, t.certoano,b.companyid,d.companyname
						from certoa t
						left join product a on a.productid = t.productid
						left join plant b on b.plantid = t.plantid
						left join addressbook c on c.addressbookid = t.addressbookid
						left join company d on d.companyid = b.companyid
						left join soheader e on e.soheaderid = t.soheaderid
						left join billofmaterial f on f.bomid = t.bomid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . " where t.certoaid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = getCatalog('certoa');
   $this->pdf->AddPage('L', array(
      210,
      330
    ));    
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->setFontSize(8);
      $this->pdf->text(10, $this->pdf->gety(), 'No Doc');
      $this->pdf->text(20, $this->pdf->gety(), ': ' . $row['certoano']);
      $this->pdf->text(10, $this->pdf->gety() + 4, 'Tgl Doc');
      $this->pdf->text(20, $this->pdf->gety() + 4, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['certoadate'])));
      $this->pdf->text(55, $this->pdf->gety(), 'No OS');
      $this->pdf->text(75, $this->pdf->gety(), ': ' . $row['sono']);
			$this->pdf->text(55, $this->pdf->gety() + 4, 'Tgl Produksi');
      $this->pdf->text(75, $this->pdf->gety() + 4, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['certoadate'])));
      $this->pdf->text(110, $this->pdf->gety(), 'Bom');
      $this->pdf->text(130, $this->pdf->gety(), ': ' . $row['bomversion']);
      $this->pdf->text(110, $this->pdf->gety() + 4, 'Material');
      $this->pdf->text(130, $this->pdf->gety() + 4, ': ' . $row['productname']);
      $sql1        = "select *
											from certoadetail a
											join qcparam b on b.qcparamid = a.qcparamid
							where a.certoaid = " . $row['certoaid'] . " group by a.certoaid order by a.certoadetailid";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 8);
      $this->pdf->colalign = array(
        'L',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
        
      );
      $this->pdf->setwidths(array(
        10,
        30,
        30,
        30,
        40,
        30,
        40,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Parameter',
        'Metode Test',
        'Unit Test',
        'Minimum Spesifikasi',
        'Range Target',
        'Minimum Toleransi',
        'Hasil Test',
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',      
        'L',      
        'L',      
        'L',      
        'L',      
        'L',            
        'L',            
        'L'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['qcparamname'],
          $row1['methodtest'],
          $row1['unittest'],
          $row1['specmin'],
          $row1['rangetarget'],
          $row1['tolerancemin'],
          $row1['resulttest']
        ));
      }
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        30,
        160
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none'
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Keterangan',
        $row['description']
      ));
			$this->pdf->CheckPageBreak(20);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'Dibuat oleh,');
      $this->pdf->text(50, $this->pdf->gety() + 5, 'Diperiksa oleh,');
     
      $this->pdf->text(10, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(50, $this->pdf->gety() + 15, '........................');
     
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
		$this->menuname='certoa';
    parent::actionDownxls();
    $sql = "select a.certoaid,a.certoaversion,a.certoadate,b.productname,a.qty,a.qtystdkg,a.qtystdmtr,c.uomcode,a.description
				from billofmaterial a 
				left join product b on b.productid = a.productid 
				left join unitofmeasure c on c.unitofmeasureid = a.uomid 
        where a.certoaid like '%".$_GET['certoaid']."%'
				";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.certoaid in (".$_GET['id'].") ";
    }
    if ($_GET['certoaversion'] !== '') {
      $sql = $sql . " and a.certoaversion like '%".$_GET['certoaversion']."%' ";
    }
    if ($_GET['certoadate'] !== '') {
      $sql = $sql . " and a.certoadate like '%".$_GET['certoadate']."%' ";
    }
    if ($_GET['product'] !== '') {
      $sql = $sql . " and b.productname like '%".$_GET['product']."%' ";
    }
    if ($_GET['description'] !== '') {
      $sql = $sql . " and a.description like '%".$_GET['description']."%' ";
    }
    if ($_GET['uom'] !== '') {
      $sql = $sql . " and c.uomcode like '%".$_GET['uom']."%' ";
    }
    if ($_GET['productdetail'] !== '') {
      $sql = $sql . " and a.certoaid in 
      (
      select za.certoaid
      from certoadetail za 
      left join product zb on zb.productid = za.productid 
      where zb.productname like '%".$_GET['productdetail']."%'
      ) ";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 1;
    $this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0, 1, getCatalog('certoaid'))
			->setCellValueByColumnAndRow(1, 1, getCatalog('certoaversion'))
			->setCellValueByColumnAndRow(2, 1, getCatalog('certoadate'))
			->setCellValueByColumnAndRow(3, 1, getCatalog('product'))
			->setCellValueByColumnAndRow(4, 1, getCatalog('qty'))
      ->setCellValueByColumnAndRow(5, 1, getCatalog('qtystdkg'))
      ->setCellValueByColumnAndRow(6, 1, getCatalog('qtystdmtr'))
			->setCellValueByColumnAndRow(7, 1, getCatalog('uom'))
			->setCellValueByColumnAndRow(8, 1, getCatalog('description'))
			->setCellValueByColumnAndRow(9, 1, getCatalog('certoadetailid'))
			->setCellValueByColumnAndRow(10, 1, getCatalog('product'))
      ->setCellValueByColumnAndRow(11, 1, getCatalog('qty'))
      ->setCellValueByColumnAndRow(12, 1, getCatalog('qtystdkg'))
      ->setCellValueByColumnAndRow(13, 1, getCatalog('qtystdmtr'))
			->setCellValueByColumnAndRow(14, 1, getCatalog('uom'))
			->setCellValueByColumnAndRow(15, 1, getCatalog('productcertoa'))
			->setCellValueByColumnAndRow(16, 1, getCatalog('description'))
			;
    foreach ($dataReader as $row) {
			$sql1 = "select a.certoadetailid,b.productname,a.qty,a.qtystdkg,a.qtystdmtr,c.uomcode,d.certoaversion,a.description
					from certoadetail a 
					left join product b on b.productid = a.productid 
					left join unitofmeasure c on c.unitofmeasureid = a.uomid 
					left join billofmaterial d on d.certoaid = a.productcertoaid 
					where a.certoaid = ".$row['certoaid'];
			$dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
			foreach ($dataReader1 as $row1) {
				$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0, $i + 1, $row['certoaid'])
					->setCellValueByColumnAndRow(1, $i + 1, $row['certoaversion'])
					->setCellValueByColumnAndRow(2, $i + 1, date(Yii::app()->params['dateviewfromdb'], strtotime($row['certoadate'])))
					->setCellValueByColumnAndRow(3, $i + 1, $row['productname'])
          ->setCellValueByColumnAndRow(4, $i + 1, $row['qty'])
          ->setCellValueByColumnAndRow(5, $i + 1, $row['qtystdkg'])
          ->setCellValueByColumnAndRow(6, $i + 1, $row['qtystdmtr'])
					->setCellValueByColumnAndRow(7, $i + 1, $row['uomcode'])
					->setCellValueByColumnAndRow(8, $i + 1, $row['description'])
					->setCellValueByColumnAndRow(9, $i + 1, $row1['certoadetailid'])
					->setCellValueByColumnAndRow(10, $i + 1, $row1['productname'])
          ->setCellValueByColumnAndRow(11, $i + 1, $row1['qty'])
          ->setCellValueByColumnAndRow(12, $i + 1, $row1['qtystdkg'])
          ->setCellValueByColumnAndRow(13, $i + 1, $row1['qtystdmtr'])
					->setCellValueByColumnAndRow(14, $i + 1, $row1['uomcode'])
					->setCellValueByColumnAndRow(15, $i + 1, $row1['certoaversion'])
					->setCellValueByColumnAndRow(16, $i + 1, $row1['description'])
					;
				$i += 1;
			}
    }
    $this->getFooterXLS($this->phpExcel);
  }
}