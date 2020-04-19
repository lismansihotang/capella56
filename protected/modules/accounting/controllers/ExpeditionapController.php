<?php
class ExpeditionapController extends Controller {
  public $menuname = 'expeditionap';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexgr() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchgr();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexjurnal() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchjurnal();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
    $id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'expeditionapid' => $id
		));
  }
  public function search() {
    header('Content-Type: application/json');
    $expeditionapid 		= isset($_POST['expeditionapid']) ? $_POST['expeditionapid'] : '';
    $companycode 		= isset($_POST['companycode']) ? $_POST['companycode'] : '';
    $expeditionname				= isset($_POST['expeditionname']) ? $_POST['expeditionname'] : '';
    $expeditionapno 		= isset($_POST['expeditionapno']) ? $_POST['expeditionapno'] : '';
    $plantcode  		= isset($_POST['plantcode']) ? $_POST['plantcode'] : '';
    $pono 					= isset($_POST['pono']) ? $_POST['pono'] : '';
    $supplier 	= isset($_POST['supplier']) ? $_POST['supplier'] : '';
    $page         	= isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows         	= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort         	= isset($_POST['sort']) ? strval($_POST['sort']) : 'expeditionapid';
    $order        	= isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset       	= ($page - 1) * $rows;
    $result       	= array();
    $row          	= array();

		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('expeditionap t')
      ->leftjoin('plant a', 'a.plantid = t.plantid')
      ->leftjoin('company b', 'b.companyid = a.companyid')
      ->leftjoin('poheader c', 'c.poheaderid = t.poheaderid')
      ->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
      ->leftjoin('addressbook e', 'e.addressbookid = t.addressbookexpid')
			->where("
				((coalesce(b.companycode,'') like :companycode) and 
				(coalesce(e.fullname,'') like :expeditionname) and 
				(coalesce(t.expeditionapno,'') like :expeditionapno) and 
				(coalesce(a.plantcode,'') like :plantcode) and 
				(coalesce(c.pono,'') like :pono) and 
				(coalesce(d.fullname,'') like :supplier))
					and t.recordstatus in (".getUserRecordStatus('listexpap').")
					and a.companyid in (".getUserObjectValues('company').")
					and t.poheaderid > 0
					", array(
					':companycode' => '%' . $companycode . '%',
					':expeditionname' => '%' . $expeditionname . '%',
					':expeditionapno' => '%' . $expeditionapno . '%',
					':plantcode' => '%' . $plantcode . '%',
					':pono' => '%' . $pono . '%',
					':supplier' => '%' . $supplier . '%'
				))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.plantcode,a.companyid,b.companycode,c.pono,d.fullname, e.fullname as expeditionname')
			->from('expeditionap t')
      ->leftjoin('plant a', 'a.plantid = t.plantid')
      ->leftjoin('company b', 'b.companyid = a.companyid')
      ->leftjoin('poheader c', 'c.poheaderid = t.poheaderid')
      ->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
      ->leftjoin('addressbook e', 'e.addressbookid = t.addressbookexpid')
			->where("
				((coalesce(b.companycode,'') like :companycode) and 
				(coalesce(e.fullname,'') like :expeditionname) and 
				(coalesce(t.expeditionapno,'') like :expeditionapno) and 
				(coalesce(a.plantcode,'') like :plantcode) and 
				(coalesce(c.pono,'') like :pono) and 
				(coalesce(d.fullname,'') like :supplier))
				and t.recordstatus in (".getUserRecordStatus('listexpap').")
				and a.companyid in (".getUserObjectValues('company').")
					and t.poheaderid > 0
					", array(
					':companycode' => '%' . $companycode . '%',
					':expeditionname' => '%' . $expeditionname . '%',
					':expeditionapno' => '%' . $expeditionapno . '%',
					':plantcode' => '%' . $plantcode . '%',
					':pono' => '%' . $pono . '%',
					':supplier' => '%' . $supplier . '%'
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'expeditionapid' => $data['expeditionapid'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'expeditionapdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['expeditionapdate'])),
        'expeditionapno' => $data['expeditionapno'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'addressbookexpid' => $data['addressbookexpid'],
        'expeditionname' => $data['expeditionname'],
        'amount' => Yii::app()->format->formatNumber($data['amount']),
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
  public function actionsearchgr() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'expeditionapgrid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $footer             = array();
    $cmd             = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('expeditionapgr t')
			->leftjoin('grheader a', 'a.grheaderid = t.grheaderid')
			->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
			->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uom2id')
			->where("(expeditionapid = :expeditionapid)", array(
      ':expeditionapid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
			->select('t.*,a.grno,b.uomcode,c.uomcode as uom2code')
			->from('expeditionapgr t')
			->leftjoin('grheader a', 'a.grheaderid = t.grheaderid')
			->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
			->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uom2id')
			->where("(expeditionapid = :expeditionapid)", array(
      ':expeditionapid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'expeditionapgrid' => $data['expeditionapgrid'],
        'expeditionapid' => $data['expeditionapid'],
        'grheaderid' => $data['grheaderid'],
        'grno' => $data['grno'],
        'grdetailid' => $data['grdetailid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'qty2' => Yii::app()->format->formatNumber($data['qty2']),
        'uom2id' => $data['uom2id'],
        'uom2code' => $data['uom2code'],
        'nilaibeban' => Yii::app()->format->formatNumber($data['nilaibeban']),
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
	public function actionsearchjurnal() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'expeditionapjurnalid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $footer             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('expeditionapjurnal t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->leftjoin('company c', 'c.companyid = a.companyid')->where("(expeditionapid = :expeditionapid) and t.accountid > 0", array(
      ':expeditionapid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.accountcode,a.accountname,b.currencyname,c.companyname,b.symbol')->from('expeditionapjurnal t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->leftjoin('company c', 'c.companyid = a.companyid')->where("(expeditionapid = :expeditionapid) and t.accountid > 0", array(
      ':expeditionapid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'expeditionapjurnalid' => $data['expeditionapjurnalid'],
        'expeditionapid' => $data['expeditionapid'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'symbol' => $data['symbol'],
        'companyname' => $data['companyname'],
        'ratevalue' => Yii::app()->format->formatNumber($data['ratevalue']),
        'detailnote' => $data['detailnote']
      );
			$symbol = $data['symbol'];
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		$sql = "select sum(amount) as amount from expeditionapjurnal where expeditionapid = ".$id;
		$cmd = Yii::app()->db->createCommand($sql)->queryRow();
		$footer[] = array(
      'accountname' => 'Total',
			'symbol' => $symbol,
      'amount' => Yii::app()->format->formatNumber($cmd['amount'])
    );
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		$sql     = 'call Modifexpeditionap(:vid,:vexpeditionapdate,:vexpeditionapno,:vplantid,:vpoheaderid,:vaddressbookexpid,:vamount,:vheadernote,:vdatauser)';
		$command = $connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vexpeditionapdate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vexpeditionapno', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vpoheaderid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookexpid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vamount', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-expeditionap"]["name"]);
		if (move_uploaded_file($_FILES["file-expeditionap"]["tmp_name"], $target_file)) {
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
					$nourut = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
					$noref = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$journaldate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(3, $row)->getValue()));
					$journalnote = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$abid = Yii::app()->db->createCommand("select expeditionapid from expeditionap 
						where companyid = '".$companyid."' 
						and noref = '".$noref."' 
						and journaldate = '".$journaldate."'
						and journalnote = '".$journalnote."'")->queryScalar();
					$recordstatus = findstatusbyuser('insbs');
					if ($abid == '') {					
						$this->ModifyData($connection,array('',$companyid,$journaldate,$noref,$journalnote,$recordstatus));
						//get id addressbookid
						$abid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '".$fullname."'")->queryScalar();
					}
					if ($abid != '') {
						if ($objWorksheet->getCellByColumnAndRow(5, $row)->getValue() != '') {
							$accountcode = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
							$accountid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$accountcode."'")->queryScalar();
							$debit = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
							$credit = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
							$currencyname = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
							$currencyid = Yii::app()->db->createCommand("select cityid from city where cityname = '".$cityname."'")->queryScalar();
							$ratevalue = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
							$detailnote = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
							$this->ModifyDataAddress($connection,array('',$abid,$accountid,$debit,$credit,$currencyid,$ratevalue,$detailnote));
						}
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
  public function actionSave() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['expeditionap-expeditionapid'])?$_POST['expeditionap-expeditionapid']:''),
			date(Yii::app()->params['datetodb'], strtotime($_POST['expeditionap-expeditionapdate'])),
				$_POST['expeditionap-expeditionapno'],
				$_POST['expeditionap-plantid'],
				$_POST['expeditionap-poheaderid'],
				$_POST['expeditionap-addressbookexpid'],
				$_POST['expeditionap-amount'],
				$_POST['expeditionap-headernote']
			));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
	private function ModifyDataGR($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertexpeditionapgr(:vexpeditionapid,:vgrheaderid,:vgrdetailid,:vqty,:vuomid,:vqty2,:vuom2id,:vnilaibeban,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateexpeditionapgr(:vid,:vexpeditionapid,:vgrheaderid,:vgrdetailid,:vqty,:vuomid,:vqty2,:vuom2id,:vnilaibeban,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vexpeditionapid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vgrheaderid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vgrdetailid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vqty', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vqty2', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vuom2id', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vnilaibeban', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavegr() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDatagr($connection,array((isset($_POST['expeditionapgrid'])?$_POST['expeditionapgrid']:''),
				$_POST['expeditionapid'],$_POST['grheaderid'],$_POST['grdetailid'],$_POST['qty'],$_POST['uomid'],$_POST['qty2'],$_POST['uom2id'],$_POST['nilaibeban'],
			));
			$transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
  private function ModifyDataJurnal($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertexpeditionapjurnal(:vexpeditionapid,:vaccountid,:vdebit,:vcredit,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateexpeditionapjurnal(:vid,:vexpeditionapid,:vaccountid,:vdebit,:vcredit,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vexpeditionapid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vaccountid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vdebit', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vcredit', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vratevalue', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vdetailnote', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavejurnal() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataJurnal($connection,array((isset($_POST['expeditionapjurnalid'])?$_POST['expeditionapjurnalid']:''),
				$_POST['expeditionapid'],$_POST['accountid'],
				$_POST['debit'],$_POST['credit'],$_POST['currencyid'],$_POST['ratevalue'],$_POST['detailnote']));
			$transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
  public function actionPurge() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
				$sql     = 'call Purgeexpeditionap(:vid,:vdatauser)';
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
  public function actionPurgegr() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeexpeditionapgr(:vid,:vdatauser)';
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
  public function actionPurgejurnal() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeexpeditionapjurnal(:vid,:vdatauser)';
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
  public function actionReject() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call RejectExpeditionap(:vid,:vdatauser)';
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
  public function actionGeneratedetail(){
    if (Yii::app()->request->isAjaxRequest) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateInvapGR(:vid, :vhid)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->execute();
        $transaction->commit();
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    }
  }
	public function actionGeneratesupplier(){
		$sql = "select a.addressbookid
				from poheader a
				where a.poheaderid = ".$_POST['poheaderid']." 
				limit 1";
		$address = Yii::app()->db->createCommand($sql)->queryRow();
		echo CJSON::encode(array(
			'addressbookid' => $address['addressbookid'],
		));
  }
  public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Approveexpeditionap(:vid,:vdatauser)';
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
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select a.expeditionapid,
						ifnull(b.companyname,'-') as companyname,
						ifnull(a.expeditionapno,'-') as expeditionapno,
						ifnull(d.pono,'-') as pono,
						a.expeditionapdate,a.createddate,a.updatedate,ifnull(amount,0) as biaya,
						ifnull(a.headernote,'-')as headernote,a.recordstatus,c.companyid,e.fullname as supplier, f.fullname as ekspedisi
						from expeditionap a
						left join plant c on c.plantid = a.plantid
						left join company b on b.companyid = c.companyid
						left join poheader d on d.poheaderid = a.poheaderid
						left join addressbook e on e.addressbookid = a.addressbookid
						left join addressbook f on f.addressbookid = a.addressbookexpid
						";
		$expeditionapid = filter_input(INPUT_GET,'expeditionapid');
		$companyname = filter_input(INPUT_GET,'companyname');
		$expeditionapno = filter_input(INPUT_GET,'expeditionapno');
		$pono = filter_input(INPUT_GET,'pono');
		$expeditionapdate = filter_input(INPUT_GET,'expeditionapdate');
		$sql .= " where coalesce(a.expeditionapid,'') like '%".$expeditionapid."%' 
			and coalesce(b.companyname,'') like '%".$companyname."%'
			and coalesce(a.expeditionapno,'') like '%".$expeditionapno."%'
			and coalesce(d.pono,'') like '%".$pono."%'
			and coalesce(a.expeditionapdate,'') like '%".$expeditionapdate."%'
			";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.expeditionapid in (" . $_GET['id'] . ")";
    }
    $debit            = 0;
    $credit           = 0;
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('expeditionap');
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->AddPage('P');
    $this->pdf->setFont('Arial', 'B', 8);
    $this->pdf->AliasNBPages();
    foreach ($dataReader as $row) {
     
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No Doc ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['expeditionapno']);
      $this->pdf->text(15, $this->pdf->gety() + 15, 'No Ref ');
      $this->pdf->text(50, $this->pdf->gety() + 15, ': ' . $row['pono']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Tgl Doc ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . $row['expeditionapdate']);
			
			$this->pdf->text(110, $this->pdf->gety() + 5, 'Supplier ');
      $this->pdf->text(130, $this->pdf->gety() + 5, ': ' . $row['supplier']);
      $this->pdf->text(110, $this->pdf->gety() + 10, 'Ekspedisi ');
      $this->pdf->text(130, $this->pdf->gety() + 10, ': ' . $row['ekspedisi']);
      $this->pdf->text(110, $this->pdf->gety() + 15, 'Biaya ');
      $this->pdf->text(130, $this->pdf->gety() + 15, ': ' . $row['biaya']);
      $sql1        = "select b.accountcode,b.accountname, a.amount,c.symbol,a.detailnote,a.ratevalue, d.cashbankno
							from expeditionapjurnal a
							left join account b on b.accountid = a.accountid
							left join currency c on c.currencyid = a.currencyid
							left join cashbank d on d.cashbankid = a.cashbankid
							where a.expeditionapid = '" . $row['expeditionapid'] . "'
							order by expeditionapjurnalid ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 20);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        50,
        25,
        25,
        20,
        50
      ));
      $this->pdf->colheader = array(
        'No',
        'Account',
        'No Kas/Bank',
        'Credit',
        'Rate',
        'Detail Note'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->coldetailalign = array(
        'C',
        'L',
        'R',
        'R',
        'R',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i      = $i + 1;
        //$debit  = $debit + ($row1['debit'] * $row1['ratevalue']);
       // $credit = $credit + ($row1['credit'] * $row1['ratevalue']);
        $this->pdf->row(array(
          $i,
          $row1['accountcode'] . ' ' . $row1['accountname'],$row1['cashbankno'],
           Yii::app()->format->formatCurrency($row1['amount'], $row1['symbol']),
         Yii::app()->format->formatCurrency($row1['ratevalue']),
          $row1['detailnote']
        ));
      }
      $this->pdf->row(array(
        '',
        'Total',
        Yii::app()->format->formatNumber($debit),
        Yii::app()->format->formatNumber($credit),
        '',
        ''
      ));
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->border = false;
      $this->pdf->setwidths(array(
        20,
        175
      ));
      $this->pdf->row(array(
        'Note',
        $row['headernote']
      ));
      $this->pdf->text(20, $this->pdf->gety() + 20, 'Approved By');
      $this->pdf->text(170, $this->pdf->gety() + 20, 'Proposed By');
      $this->pdf->text(20, $this->pdf->gety() + 40, '_____________ ');
      $this->pdf->text(170, $this->pdf->gety() + 40, '_____________');
      $this->pdf->CheckNewPage(10);
    }
    $this->pdf->Output();
  }
}