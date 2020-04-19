<?php
class QctestController extends Controller {
  public $menuname = 'qctest';
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
			'qctestid' => $id
		));
	}
  public function search() {
    header('Content-Type: application/json');
    $qctestid       = isset($_POST['qctestid']) ? $_POST['qctestid'] : '';
    $plantcode  = isset($_POST['plantcode']) ? $_POST['plantcode'] : '';
		$productcode   = isset($_POST['productcode']) ? $_POST['productcode'] : '';
		$productname   = isset($_POST['productname']) ? $_POST['productname'] : '';   
		$customer   = isset($_POST['customer']) ? $_POST['customer'] : '';   
    $qctestid       = isset($_GET['qctestid']) ? $_GET['qctestid'] : '';
    $plantcode  = isset($_GET['plantcode']) ? $_GET['plantcode'] : '';
		$productcode   = isset($_GET['productcode']) ? $_GET['productcode'] : '';
		$productname   = isset($_GET['productname']) ? $_GET['productname'] : '';   
		$customer   = isset($_GET['customer']) ? $_GET['customer'] : '';   
    $page        = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows        = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort        = isset($_POST['sort']) ? strval($_POST['sort']) : 'qctestid';
    $order       = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset      = ($page - 1) * $rows;
    $page        = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows        = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort        = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order       = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset      = ($page - 1) * $rows;
    $result      = array();
    $row         = array();
		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('qctest t')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('plant b', 'b.plantid = t.plantid')
			->leftjoin('addressbook c', 'c.addressbookid = t.addressbookid')
			->leftjoin('company d', 'd.companyid = b.companyid')
			->where("((coalesce(qctestid,'') like :qctestid) 
			and (coalesce(b.plantcode,'') like :plantcode) 
			and (coalesce(c.fullname,'') like :customer) 
			and (coalesce(a.productcode,'') like :productcode) 
			and (coalesce(a.productname,'') like :productname))", array(
			':qctestid' => '%' . $qctestid . '%',
			':plantcode' => '%' . $plantcode . '%',
			':productcode' => '%' . $productcode . '%',
			':productname' => '%' . $productname . '%',
			':customer' => '%' . $customer . '%',
		))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.productcode,a.productname,b.plantcode,c.fullname,d.companyname')
		->from('qctest t')
		->leftjoin('product a', 'a.productid = t.productid')
		->leftjoin('plant b', 'b.plantid = t.plantid')
		->leftjoin('addressbook c', 'c.addressbookid = t.addressbookid')
		->leftjoin('company d', 'd.companyid = b.companyid')
		->where("((coalesce(qctestid,'') like :qctestid) 
		and (coalesce(b.plantcode,'') like :plantcode) 
		and (coalesce(c.fullname,'') like :customer) 
		and (coalesce(a.productcode,'') like :productcode) 
		and (coalesce(a.productname,'') like :productname))", array(
		':qctestid' => '%' . $qctestid . '%',
		':plantcode' => '%' . $plantcode . '%',
		':productcode' => '%' . $productcode . '%',
		':productname' => '%' . $productname . '%',
		':customer' => '%' . $customer . '%',
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'qctestid' => $data['qctestid'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'companyname' => $data['companyname'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'productcode' => $data['productcode'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'qctestdetailid';
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
				->from('qctestdetail t')
				->leftjoin('qcparam b', 'b.qcparamid = t.qcparamid')
				->where('t.qctestid = :qctestid', array(
      ':qctestid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
				->select('t.*,b.qcparamname')
				->from('qctestdetail t')
				->leftjoin('qcparam b', 'b.qcparamid = t.qcparamid')
				->where('t.qctestid = :qctestid', array(
      ':qctestid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) { 
      $row[] = array(
        'qctestdetailid' => $data['qctestdetailid'],
        'qctestid' => $data['qctestid'],
        'qcparamid' => $data['qcparamid'],
				'qcparamname' => $data['qcparamname'],
        'methodtest' => $data['methodtest'],
        'unittest' => $data['unittest'],
				'specmin' => $data['specmin'],
				'rangetarget' => $data['rangetarget'],
				'tolerancemin' => $data['tolerancemin'],
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
		$sql = 'call ModifQCTest(:vid,:vplantid,:vaddressbookid,:vproductid,:vcreatedby)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();			
	}	
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-qctest"]["name"]);
		if (move_uploaded_file($_FILES["file-qctest"]["tmp_name"], $target_file)) {
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
					$qctestversion = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$qctestdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(2, $row)->getValue()));
					$productname = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$productid = Yii::app()->db->createCommand("select productid from product where productname = '".$productname."'")->queryScalar();
					$qty = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
          $qtystdkg = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
          $qtystdmtr = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$uomcode = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
					$description = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$this->ModifyData($connection,array($id,$qctestversion,$productid,$qty,$qtystdkg,$qtystdmtr,$uomid,$qctestdate,$description,1));
					if ($id == '') {					
						//get id addressbookid
						$id = Yii::app()->db->createCommand("select qctestid from billofmaterial where qctestversion = '".$qctestversion."'")->queryScalar();
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
							$productqctest = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
							$productqctestid = Yii::app()->db->createCommand("select qctestid from billofmaterial where qctestversion = '".$productqctest."'")->queryScalar();
							$description = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
							$this->ModifyDataDetail($connection,array($detailid,$id,$productid,$qty,$qtystdkg,$qtystdmtr,$uomid,$productqctestid,$description));
						}
					}
				}
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    }
	}
  public function actionSave() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['qctest-qctestid'])?$_POST['qctest-qctestid']:''),
				$_POST['qctest-plantid'],$_POST['qctest-addressbookid'],$_POST['qctest-productid']));
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
			$sql     = 'call Insertqctestdetail(:vqctestid,:vqcparamid,:vmethodtest,:vunittest,:vspecmin,:vrangetarget,:vtolerancemin,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateqctestdetail(:vid,:vqctestid,:vqcparamid,:vmethodtest,:vunittest,:vspecmin,:vrangetarget,:vtolerancemin,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		}
		$command->bindvalue(':vqctestid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vqcparamid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vmethodtest', $arraydata[3], PDO::PARAM_STR);
    $command->bindvalue(':vunittest', $arraydata[4], PDO::PARAM_STR);
    $command->bindvalue(':vspecmin', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vrangetarget', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vtolerancemin', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavedetail() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataDetail($connection,array((isset($_POST['qctestdetailid'])?$_POST['qctestdetailid']:''),
			$_POST['qctestid'],
			$_POST['qcparamid'],
			$_POST['methodtest'],
			$_POST['unittest'],
			$_POST['specmin'],
			$_POST['rangetarget'],
			$_POST['tolerancemin']));
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
        $sql     = 'call Purgeqctest(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
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
        $sql     = 'call Purgeqctestdetail(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
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
		$qctestid = GetSearchText(array('GET'),'qctestid');
		$plantcode = GetSearchText(array('GET'),'plantcode');
		$customer = GetSearchText(array('GET'),'customer');
		$materialtypecode = GetSearchText(array('GET'),'materialtypecode');
		$productname = GetSearchText(array('GET'),'productname');
    $sql = "select a.qctestid,b.plantcode,c.fullname,d.productname 
				from qctest a 
				left join plant b on b.plantid = a.plantid 
				left join addressbook c on c.addressbookid = a.addressbookid 
				left join product d on d.productid = a.productid 
				left join materialtype e on e.materialtypeid = d.materialtypeid 
				";
		$sql .= " where coalesce(a.qctestid,'') like '".$qctestid."' 
			and coalesce(b.plantcode,'') like '".$plantcode."' 
			and coalesce(c.fullname,'') like '".$customer."'
			and coalesce(e.materialtypecode,'') like '".$materialtypecode."' 
			and coalesce(d.productname,'') like '".$productname."' ";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.qctestid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = getCatalog('qctest');
    $this->pdf->AddPage('P','A4');
    $this->pdf->SetFont('Arial');
    $this->pdf->AliasNBPages();
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'Kantor Cabang ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['plantcode']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Customer ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . $row['fullname']);
      $this->pdf->text(15, $this->pdf->gety() + 15, 'Material / Service');
      $this->pdf->text(50, $this->pdf->gety() + 15, ': ' . $row['productname']);
      $sql1        = "select a.qctestdetailid,b.qcparamname,a.methodtest,unittest,specmin,rangetarget,tolerancemin
        from qctestdetail a
        inner join qcparam b on b.qcparamid = a.qcparamid
        where a.qctestid = '" . $row['qctestid'] . "'
				order by qctestdetailid";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 20);
      $this->pdf->colalign = array(
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
        70,
        20,
        20,
        20,
        25,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Parameter',
        'Metode Test',
        'Unit Test',
        'Min Spec',
        'Range Target',
        'Min Toleransi'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
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
        ));
      }
      $this->pdf->checkNewPage(10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Approved By');
      $this->pdf->text(150, $this->pdf->gety() + 10, 'Proposed By');
      $this->pdf->text(10, $this->pdf->gety() + 30, '____________ ');
      $this->pdf->text(150, $this->pdf->gety() + 30, '____________');
			$this->pdf->AddPage('P','A4');
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
		$this->menuname='qctest';
    parent::actionDownxls();
    $qctestid = GetSearchText(array('GET'),'qctestid');
		$plantcode = GetSearchText(array('GET'),'plantcode');
		$customer = GetSearchText(array('GET'),'customer');
		$materialtypecode = GetSearchText(array('GET'),'materialtypecode');
		$productname = GetSearchText(array('GET'),'productname');
    $sql = "select a.qctestid,b.plantcode,c.fullname,d.productname,d.productcode
				from qctest a 
				left join plant b on b.plantid = a.plantid 
				left join addressbook c on c.addressbookid = a.addressbookid 
				left join product d on d.productid = a.productid 
				left join materialtype e on e.materialtypeid = d.materialtypeid 
				";
		$sql .= " where coalesce(a.qctestid,'') like '".$qctestid."' 
			and coalesce(b.plantcode,'') like '".$plantcode."' 
			and coalesce(c.fullname,'') like '".$customer."'
			and coalesce(e.materialtypecode,'') like '".$materialtypecode."' 
			and coalesce(d.productname,'') like '".$productname."' ";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.qctestid in (" . $_GET['id'] . ")";
    }
		$sql = $sql . " order by qctestid asc ";
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 1;$nourut=0;$oldbom='';
    foreach ($dataReader as $row) {
			if ($oldbom != $row['qctestid']) {
				$nourut+=1;
				$oldbom = $row['qctestid'];
			}
			$sql1        = "select a.qctestdetailid,b.qcparamname,a.methodtest,unittest,specmin,rangetarget,tolerancemin
        from qctestdetail a
        inner join qcparam b on b.qcparamid = a.qcparamid
        where a.qctestid = '" . $row['qctestid'] . "'
				order by qctestdetailid";
			$dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
			foreach ($dataReader1 as $row1) {
				$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0, $i+1, $nourut)
					->setCellValueByColumnAndRow(1, $i+1, $row['plantcode'])
					->setCellValueByColumnAndRow(2, $i+1, $row['fullname'])
					->setCellValueByColumnAndRow(3, $i+1, $row['productcode'])
					->setCellValueByColumnAndRow(4, $i+1, $row['productname'])
					->setCellValueByColumnAndRow(5, $i+1, $row1['qcparamname'])
					->setCellValueByColumnAndRow(6, $i+1, $row1['methodtest'])      
					->setCellValueByColumnAndRow(7, $i+1, $row1['unittest'])            
					->setCellValueByColumnAndRow(8, $i+1, $row1['specmin'])            
					->setCellValueByColumnAndRow(9, $i+1, $row1['rangetarget'])            
					->setCellValueByColumnAndRow(10, $i+1, $row1['tolerancemin']);
				$i += 1;
			}
    }
    $this->getFooterXLS($this->phpExcel);
  }
}