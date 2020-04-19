<?php
class TtntController extends Controller {
  public $menuname = 'ttnt';
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
      echo $this->actionsearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
    $id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'ttntid' => $id
		));
  }
  public function search() {
    header('Content-Type: application/json');
    $ttntid = GetSearchText(array('POST','GET'),'ttntid');
    $ttntno = GetSearchText(array('POST','Q'),'ttntno');
    $customername = GetSearchText(array('POST','Q'),'customername');
    $plantcode = GetSearchText(array('POST','Q'),'plantcode');
    $ttntdate = GetSearchText(array('POST','Q'),'ttntdate');
    $postdate = GetSearchText(array('POST','Q'),'postdate');
    $description = GetSearchText(array('POST','Q'),'description');
    $recordstatus = GetSearchText(array('POST','Q'),'recordstatus');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','ttntid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset       = ($page - 1) * $rows;
    $result       = array();
    $row          = array();

		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('ttnt t')
			->where("
				((coalesce(ttntdate,'') like :ttntdate) and 
				(coalesce(description,'') like :description) and 
				(coalesce(fullname,'') like :customername) and 
				(coalesce(plantcode,'') like :plantcode) and 
				(coalesce(ttntno,'') like :ttntno) and 
				(coalesce(ttntid,'') like :ttntid))
					and t.recordstatus in (".getUserRecordStatus('listttnt').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					"
					and t.plantid in (".getUserObjectValues('plant').")
					", array(
					':ttntdate' => '%' . $ttntdate . '%',
					':description' => '%' . $description . '%',
					':plantcode' => '%' . $plantcode . '%',
					':customername' => '%' . $customername . '%',
					':ttntno' => '%' . $ttntno . '%',
					':ttntid' => '%' . $ttntid . '%'
				))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*')
			->from('ttnt t')
			->where("
				((coalesce(ttntdate,'') like :ttntdate) 
				and (coalesce(description,'') like :description) 
				and (coalesce(fullname,'') like :customername) 
				and (coalesce(plantcode,'') like :plantcode) 
				and (coalesce(ttntno,'') like :ttntno) 
				and (coalesce(ttntid,'') like :ttntid))
				and t.recordstatus in (".getUserRecordStatus('listttnt').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					"
				and t.plantid in (".getUserObjectValues('plant').")
					", array(
					':ttntdate' => '%' . $ttntdate . '%',
					':description' => '%' . $description . '%',
					':plantcode' => '%' . $plantcode . '%',
					':customername' => '%' . $customername . '%',
					':ttntno' => '%' . $ttntno . '%',
					':ttntid' => '%' . $ttntid . '%'
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'ttntid' => $data['ttntid'],
        'ttntno' => $data['ttntno'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'addressbookid' => $data['addressbookid'],
        'customername' => $data['fullname'],
        'ttntdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['ttntdate'])),
        'description' => $data['description'],
        'recordstatus' => $data['recordstatus'],
        'statusname' => $data['statusname']
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
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'ttntdetailid';
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
		->from('ttntdetail t')
		->leftjoin('invoicear a', 'a.invoicearid = t.invoicearid')
		->where("(ttntid = :ttntid) and t.invoicearid > 0", array(
      ':ttntid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
		->select('t.*,a.invoicearno')
		->from('ttntdetail t')
		->leftjoin('invoicear a', 'a.invoicearid = t.invoicearid')
		->where("(ttntid = :ttntid) and t.invoicearid > 0", array(
      ':ttntid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'ttntdetailid' => $data['ttntdetailid'],
        'ttntid' => $data['ttntid'],
        'invoicearid' => $data['invoicearid'],
        'invoicearno' => $data['invoicearno'],
        'description' => $data['description'],
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		
    echo CJSON::encode($result);
  }
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		$sql     = 'call Modifttnt(:vid,:vttntdate,:vplantid,:vaddressbookid,:vdescription,:vcreatedby)';
		$command = $connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vttntdate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vdescription', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-ttnt"]["name"]);
		if (move_uploaded_file($_FILES["file-ttnt"]["tmp_name"], $target_file)) {
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
					$plantcode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$plantid = Yii::app()->db->createCommand("select plantid from company where plantcode = '".$plantcode."'")->queryScalar();
					$noref = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$ttntdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(3, $row)->getValue()));
					$description = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$abid = Yii::app()->db->createCommand("select ttntid from ttnt 
						where plantid = '".$plantid."' 
						and noref = '".$noref."' 
						and ttntdate = '".$ttntdate."'
						and description = '".$description."'")->queryScalar();
					$recordstatus = findstatusbyuser('insbs');
					if ($abid == '') {					
						$this->ModifyData($connection,array('',$plantid,$ttntdate,$noref,$description,$recordstatus));
						//get id addressbookid
						$abid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '".$fullname."'")->queryScalar();
					}
					if ($abid != '') {
						if ($objWorksheet->getCellByColumnAndRow(5, $row)->getValue() != '') {
							$accountcode = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
							$invoicearid = Yii::app()->db->createCommand("select invoicearid from account where accountcode = '".$accountcode."'")->queryScalar();
							$debit = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
							$credit = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
							$currencyname = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
							$currencyid = Yii::app()->db->createCommand("select cityid from city where cityname = '".$cityname."'")->queryScalar();
							$ratevalue = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
							$detailnote = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
							$this->ModifyDataAddress($connection,array('',$abid,$invoicearid,$debit,$credit,$currencyid,$ratevalue,$detailnote));
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
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['ttnt-ttntid'])?$_POST['ttnt-ttntid']:''),
				date(Yii::app()->params['datetodb'], strtotime($_POST['ttnt-ttntdate'])),
				$_POST['ttnt-plantid'],
				$_POST['ttnt-addressbookid'],$_POST['ttnt-description']));
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
			$sql     = 'call Insertttntdetail(:vttntid,:vinvoicearid,:vdescription,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatettntdetail(:vid,:vttntid,:vinvoicearid,:vdescription,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vttntid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vinvoicearid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vdescription', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavedetail() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataDetail($connection,array((isset($_POST['ttntdetailid'])?$_POST['ttntdetailid']:''),
				$_POST['ttntid'],$_POST['invoicearid'],$_POST['description']));
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
					$sql     = 'call Purgettnt(:vid,:vcreatedby)';
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
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgettntdetail(:vid,:vcreatedby)';
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
  public function actionReject() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Rejectttnt(:vid,:vcreatedby)';
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
  public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveTTNT(:vid,:vcreatedby)';
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
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select t.ttntid,t.ttntdate,t.companyid,t.ttntno,t.fullname, t.plantcode, t.description
			from ttnt t";
    if ($_GET['id'] !== '') {
      $sql = $sql . " where t.ttntid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = getCatalog('ttnt');
    $this->pdf->AddPage('P', array(
      160,
      90
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->setFontSize(9);
      $this->pdf->text(10, $this->pdf->gety(), 'No Doc');
      $this->pdf->text(30, $this->pdf->gety(), ': ' . $row['ttntno']);
      $this->pdf->text(10, $this->pdf->gety() + 4, 'Tgl Doc');
      $this->pdf->text(30, $this->pdf->gety() + 4, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['ttntdate'])));
			$this->pdf->text(10, $this->pdf->gety() + 8, 'Customer');
      $this->pdf->text(30, $this->pdf->gety() + 8,  ': ' .$row['fullname']);
  
			$sql1        = "select a.*, b.invoicearno
											from ttntdetail a
											left join invoicear b on b.invoicearid = a.invoicearid
							where a.ttntid = " . $row['ttntid'] . " group by a.ttntid";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
			 $this->pdf->setFontSize(9);
      $this->pdf->sety($this->pdf->gety() + 15);
      $this->pdf->colalign = array(
        'L',
        'L',
        'L'
      );
      $this->pdf->setwidths(array(
        10,
        40,
        80
      ));
      $this->pdf->colheader = array(
        'No',
        'No Faktur',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'C',           
        'L',      
        'L'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['invoicearno'],
          $row1['description']
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
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Dibuat oleh,');
      $this->pdf->text(50, $this->pdf->gety() + 10, 'Diperiksa oleh,');
     
      $this->pdf->text(10, $this->pdf->gety() + 30, '........................');
      $this->pdf->text(50, $this->pdf->gety() + 30, '........................');
     
    }
    $this->pdf->Output();
  }
}