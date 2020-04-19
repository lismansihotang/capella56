<?php
class ExpeditionarController extends Controller {
  public $menuname = 'expeditionar';
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
			'expeditionarid' => $id
		));
  }
  public function search() {
    header('Content-Type: application/json');
    $expeditionarid 		= GetSearchText(array('POST','GET'),'expeditionarid');
    $companycode 		= GetSearchText(array('POST','Q'),'companycode');
    $expeditionname 		= GetSearchText(array('POST','Q'),'expeditionname');
    $expeditionarno 		= GetSearchText(array('POST','Q'),'expeditionarno');
    $plantcode 		= GetSearchText(array('POST','Q'),'plantcode');
    $sono 		= GetSearchText(array('POST','Q'),'sono');
    $supplier 		= GetSearchText(array('POST','Q'),'supplier');
    $recordstatus 		= GetSearchText(array('POST','Q'),'recordstatus');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','expeditionarid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset       	= ($page - 1) * $rows;
    $result       	= array();
    $row          	= array();

		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('expeditionar t')
      ->leftjoin('plant a', 'a.plantid = t.plantid')
      ->leftjoin('company b', 'b.companyid = a.companyid')
      ->leftjoin('soheader c', 'c.soheaderid = t.soheaderid')
      ->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
      ->leftjoin('addressbook e', 'e.addressbookid = t.addressbookexpid')
			->where("
				((coalesce(b.companycode,'') like :companycode) and 
				(coalesce(e.fullname,'') like :expeditionname) and 
				(coalesce(t.expeditionarno,'') like :expeditionarno) and 
				(coalesce(a.plantcode,'') like :plantcode) and 
				(coalesce(c.sono,'') like :sono) and 
				(coalesce(d.fullname,'') like :supplier))
					and t.recordstatus in (".getUserRecordStatus('listexpap').")
					and a.companyid in (".getUserObjectValues('company').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'')
					, array(
					':companycode' => '%' . $companycode . '%',
					':expeditionname' => '%' . $expeditionname . '%',
					':expeditionarno' => '%' . $expeditionarno . '%',
					':plantcode' => '%' . $plantcode . '%',
					':sono' => '%' . $sono . '%',
					':supplier' => '%' . $supplier . '%'
				))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.plantcode,a.companyid,b.companycode,c.sono,d.fullname, e.fullname as expeditionname')
			->from('expeditionar t')
      ->leftjoin('plant a', 'a.plantid = t.plantid')
      ->leftjoin('company b', 'b.companyid = a.companyid')
      ->leftjoin('soheader c', 'c.soheaderid = t.soheaderid')
      ->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
      ->leftjoin('addressbook e', 'e.addressbookid = t.addressbookexpid')
			->where("
				((coalesce(b.companycode,'') like :companycode) and 
				(coalesce(e.fullname,'') like :expeditionname) and 
				(coalesce(t.expeditionarno,'') like :expeditionarno) and 
				(coalesce(a.plantcode,'') like :plantcode) and 
				(coalesce(c.sono,'') like :sono) and 
				(coalesce(d.fullname,'') like :supplier))
				and t.recordstatus in (".getUserRecordStatus('listexpap').")
				and a.companyid in (".getUserObjectValues('company').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'')
					, array(
					':companycode' => '%' . $companycode . '%',
					':expeditionname' => '%' . $expeditionname . '%',
					':expeditionarno' => '%' . $expeditionarno . '%',
					':plantcode' => '%' . $plantcode . '%',
					':sono' => '%' . $sono . '%',
					':supplier' => '%' . $supplier . '%'
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'expeditionarid' => $data['expeditionarid'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'soheaderid' => $data['soheaderid'],
        'sono' => $data['sono'],
        'expeditionardate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['expeditionardate'])),
        'expeditionarno' => $data['expeditionarno'],
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'expeditionargiid';
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
			->from('expeditionargi t')
			->leftjoin('giheader a', 'a.giheaderid = t.giheaderid')
			->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
			->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uom2id')
			->where("(expeditionarid = :expeditionarid)", array(
      ':expeditionarid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
			->select('t.*,a.gino,b.uomcode,c.uomcode as uom2code')
			->from('expeditionargi t')
			->leftjoin('giheader a', 'a.giheaderid = t.giheaderid')
			->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
			->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uom2id')
			->where("(expeditionarid = :expeditionarid)", array(
      ':expeditionarid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'expeditionargiid' => $data['expeditionargiid'],
        'expeditionarid' => $data['expeditionarid'],
        'giheaderid' => $data['giheaderid'],
        'gino' => $data['gino'],
        'gidetailid' => $data['gidetailid'],
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'expeditionarjurnalid';
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('expeditionarjurnal t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->leftjoin('company c', 'c.companyid = a.companyid')->where("(expeditionarid = :expeditionarid) and t.accountid > 0", array(
      ':expeditionarid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.accountcode,a.accountname,b.currencyname,c.companyname,b.symbol')->from('expeditionarjurnal t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->leftjoin('company c', 'c.companyid = a.companyid')->where("(expeditionarid = :expeditionarid) and t.accountid > 0", array(
      ':expeditionarid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'expeditionarjurnalid' => $data['expeditionarjurnalid'],
        'expeditionarid' => $data['expeditionarid'],
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
		$sql = "select sum(amount) as amount from expeditionarjurnal where expeditionarid = ".$id;
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
		$sql     = 'call Modifexpeditionar(:vid,:vexpeditionardate,:vexpeditionarno,:vplantid,:vsoheaderid,:vaddressbookexpid,:vamount,:vheadernote,:vdatauser)';
		$command = $connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vexpeditionardate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vexpeditionarno', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vsoheaderid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookexpid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vamount', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-expeditionar"]["name"]);
		if (move_uploaded_file($_FILES["file-expeditionar"]["tmp_name"], $target_file)) {
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
					$abid = Yii::app()->db->createCommand("select expeditionarid from expeditionar 
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
			$this->ModifyData($connection,array((isset($_POST['expeditionar-expeditionarid'])?$_POST['expeditionar-expeditionarid']:''),
			date(Yii::app()->params['datetodb'], strtotime($_POST['expeditionar-expeditionardate'])),
				$_POST['expeditionar-expeditionarno'],
				$_POST['expeditionar-plantid'],
				$_POST['expeditionar-soheaderid'],
				$_POST['expeditionar-addressbookexpid'],
				$_POST['expeditionar-amount'],
				$_POST['expeditionar-headernote']
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
			$sql     = 'call Insertexpeditionargi(:vexpeditionarid,:vgiheaderid,:vgidetailid,:vqty,:vuomid,:vqty2,:vuom2id,:vnilaibeban,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateexpeditionargi(:vid,:vexpeditionarid,:vgiheaderid,:vgidetailid,:vqty,:vuomid,:vqty2,:vuom2id,:vnilaibeban,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vexpeditionarid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vgiheaderid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vgidetailid', $arraydata[3], PDO::PARAM_STR);
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
			$this->ModifyDatagr($connection,array((isset($_POST['expeditionargiid'])?$_POST['expeditionargiid']:''),
				$_POST['expeditionarid'],$_POST['giheaderid'],$_POST['gidetailid'],$_POST['qty'],$_POST['uomid'],$_POST['qty2'],$_POST['uom2id'],$_POST['nilaibeban'],
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
			$sql     = 'call Insertexpeditionarjurnal(:vexpeditionarid,:vaccountid,:vdebit,:vcredit,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateexpeditionarjurnal(:vid,:vexpeditionarid,:vaccountid,:vdebit,:vcredit,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vexpeditionarid', $arraydata[1], PDO::PARAM_STR);
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
			$this->ModifyDataJurnal($connection,array((isset($_POST['expeditionarjurnalid'])?$_POST['expeditionarjurnalid']:''),
				$_POST['expeditionarid'],$_POST['accountid'],
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
				$sql     = 'call Purgeexpeditionar(:vid,:vdatauser)';
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
        $sql     = 'call Purgeexpeditionargi(:vid,:vdatauser)';
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
        $sql     = 'call Purgeexpeditionarjurnal(:vid,:vdatauser)';
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
        $sql     = 'call Rejectexpeditionar(:vid,:vdatauser)';
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
	public function actionGeneratecustomer(){
		$sql = "select a.addressbookid
				from soheader a
				where a.soheaderid = ".$_POST['soheaderid']." 
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
        $sql     = 'call Approveexpeditionar(:vid,:vdatauser)';
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
    $sql = "select a.expeditionarid,
						ifnull(b.companyname,'-')as company,
						ifnull(a.journalno,'-')as journalno,
						ifnull(a.referenceno,'-')as referenceno,
						a.journaldate,a.postdate,
						ifnull(a.journalnote,'-')as journalnote,a.recordstatus,a.companyid
						from expeditionar a
						left join company b on b.companyid = a.companyid ";
		$expeditionarid = filter_input(INPUT_GET,'expeditionarid');
		$companyname = filter_input(INPUT_GET,'companyname');
		$journalno = filter_input(INPUT_GET,'journalno');
		$referenceno = filter_input(INPUT_GET,'referenceno');
		$journaldate = filter_input(INPUT_GET,'journaldate');
		$sql .= " where coalesce(a.expeditionarid,'') like '%".$expeditionarid."%' 
			and coalesce(b.companyname,'') like '%".$companyname."%'
			and coalesce(a.journalno,'') like '%".$journalno."%'
			and coalesce(a.referenceno,'') like '%".$referenceno."%'
			and coalesce(a.journaldate,'') like '%".$journaldate."%'
			";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.expeditionarid in (" . $_GET['id'] . ")";
    }
    $debit            = 0;
    $credit           = 0;
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('expeditionar');
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->AddPage('P');
    $this->pdf->setFont('Arial', 'B', 10);
    $this->pdf->AliasNBPages();
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No Journal ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['journalno']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Ref No ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . $row['referenceno']);
      $this->pdf->text(15, $this->pdf->gety() + 15, 'Tgl Jurnal ');
      $this->pdf->text(50, $this->pdf->gety() + 15, ': ' . $row['journaldate']);
      $sql1        = "select b.accountcode,b.accountname, a.debit,a.credit,c.symbol,a.detailnote,a.ratevalue
							from journaldetail a
							left join account b on b.accountid = a.accountid
							left join currency c on c.currencyid = a.currencyid
							where a.expeditionarid = '" . $row['expeditionarid'] . "'
							order by journaldetailid ";
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
        70,
        25,
        25,
        10,
        55
      ));
      $this->pdf->colheader = array(
        'No',
        'Account',
        'Debit',
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
        $debit  = $debit + ($row1['debit'] * $row1['ratevalue']);
        $credit = $credit + ($row1['credit'] * $row1['ratevalue']);
        $this->pdf->row(array(
          $i,
          $row1['accountcode'] . ' ' . $row1['accountname'],
          Yii::app()->format->formatCurrency($row1['debit'], $row1['symbol']),
          Yii::app()->format->formatCurrency($row1['credit'], $row1['symbol']),
          Yii::app()->format->format(Yii::app()->params["defaultnumberprice"], $row1['ratevalue']),
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
        $row['journalnote']
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