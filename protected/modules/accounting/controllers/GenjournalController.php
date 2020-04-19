<?php
class GenjournalController extends Controller {
  public $menuname = 'genjournal';
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
			'genjournalid' => $id
		));
  }
  public function search() {
    header('Content-Type: application/json');
    $genjournalid = GetSearchText(array('POST'),'genjournalid');
    $journalno    = GetSearchText(array('POST'),'journalno');
    $referenceno    = GetSearchText(array('POST'),'referenceno');
    $companycode    = GetSearchText(array('POST'),'companycode');
    $plantcode    = GetSearchText(array('POST'),'plantcode');
    $journaldate    = GetSearchText(array('POST'),'journaldate');
    $postdate    = GetSearchText(array('POST'),'postdate');
    $journalnote    = GetSearchText(array('POST'),'journalnote');
    $recordstatus    = GetSearchText(array('POST'),'recordstatus');
    $accountcode    = GetSearchText(array('POST'),'accountcode');
    $accountname    = GetSearchText(array('POST'),'accountname');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','genjournalid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset       = ($page - 1) * $rows;
    $result       = array();
    $row          = array();

		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('genjournal t')
			->where("
				((coalesce(journaldate,'') like :journaldate) and 
				(coalesce(journalnote,'') like :journalnote) and 
				(coalesce(referenceno,'') like :referenceno) and 
				(coalesce(companycode,'') like :companycode) and 
				(coalesce(plantcode,'') like :plantcode) and 
				(coalesce(journalno,'') like :journalno) and 
				(coalesce(genjournalid,'') like :genjournalid))
					and t.recordstatus in (".getUserRecordStatus('listjournal').")".
					(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					(($accountcode != '%%')?" and t.genjournalid in (
				select distinct genjournalid 
				from journaldetail z 
				join account za on za.accountid = z.accountid 
				where za.accountcode like '".$accountcode."')":'').
				(($accountname != '%%')?" and t.genjournalid in (
				select distinct genjournalid 
				from journaldetail z 
				join account za on za.accountid = z.accountid 
				where za.accountname like '".$accountname."')":'').
					" and t.companyid in (".getUserObjectValues('company').")
					", array(
					':journaldate' => '%' . $journaldate . '%',
					':journalnote' => '%' . $journalnote . '%',
					':companycode' => '%' . $companycode . '%',
					':plantcode' => '%' . $plantcode . '%',
					':referenceno' => '%' . $referenceno . '%',
					':journalno' => '%' . $journalno . '%',
					':genjournalid' => '%' . $genjournalid . '%'
				))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,
				(select sum(debit) from journaldetail z where z.genjournalid = t.genjournalid) as debit,
				(select sum(credit) from journaldetail z where z.genjournalid = t.genjournalid) as credit')
			->from('genjournal t')
			->where("
				((coalesce(journaldate,'') like :journaldate) 
				and (coalesce(journalnote,'') like :journalnote) 
				and (coalesce(referenceno,'') like :referenceno) 
				and (coalesce(companycode,'') like :companycode) 
				and (coalesce(plantcode,'') like :plantcode) 
				and (coalesce(journalno,'') like :journalno) 
				and (coalesce(genjournalid,'') like :genjournalid))
				and t.recordstatus in (".getUserRecordStatus('listjournal').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				(($accountcode != '%%')?" and t.genjournalid in (
				select distinct genjournalid 
				from journaldetail z 
				join account za on za.accountid = z.accountid 
				where za.accountcode like '".$accountcode."')":'').
				(($accountname != '%%')?" and t.genjournalid in (
				select distinct genjournalid 
				from journaldetail z 
				join account za on za.accountid = z.accountid 
				where za.accountname like '".$accountname."')":'').
				" and t.companyid in (".getUserObjectValues('company').")
					", array(
					':journaldate' => '%' . $journaldate . '%',
					':journalnote' => '%' . $journalnote . '%',
					':companycode' => '%' . $companycode . '%',
					':plantcode' => '%' . $plantcode . '%',
					':referenceno' => '%' . $referenceno . '%',
					':journalno' => '%' . $journalno . '%',
					':genjournalid' => '%' . $genjournalid . '%'
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'genjournalid' => $data['genjournalid'],
        'journalno' => $data['journalno'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'plantcode' => $data['plantcode'],
        'plantid' => $data['plantid'],
        'referenceno' => $data['referenceno'],
        'journaldate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['journaldate'])),
        'journalnote' => $data['journalnote'],
        'debit' => $data['debit'],
        'credit' => $data['credit'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusname' => $data['statusname']
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'debit';
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('journaldetail t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->leftjoin('company c', 'c.companyid = a.companyid')->where("(genjournalid = :genjournalid) and t.accountid > 0", array(
      ':genjournalid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.accountcode,a.accountname,b.currencyname,c.companyname,b.symbol')->from('journaldetail t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->leftjoin('company c', 'c.companyid = a.companyid')->where("(genjournalid = :genjournalid) and t.accountid > 0", array(
      ':genjournalid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		$symbol = '';
    foreach ($cmd as $data) {
      $row[] = array(
        'journaldetailid' => $data['journaldetailid'],
        'genjournalid' => $data['genjournalid'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'debit' => Yii::app()->format->formatNumber($data['debit']),
        'credit' => Yii::app()->format->formatNumber($data['credit']),
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
		$sql = "select sum(debit) as debit, sum(credit) as credit from journaldetail where genjournalid = ".$id;
		$cmd = Yii::app()->db->createCommand($sql)->queryRow();
		$footer[] = array(
      'accountname' => 'Total',
			'symbol' => $symbol,
      'debit' => Yii::app()->format->formatNumber($cmd['debit']),
      'credit' => Yii::app()->format->formatNumber($cmd['credit']),
    );
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		$sql     = 'call Modifgenjournal(:vid,:vplantid,:vreferenceno,:vjournaldate,:vjournalnote,:vdatauser)';
		$command = $connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vplantid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vjournaldate', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vreferenceno', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vjournalnote', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-genjournal"]["name"]);
		if (move_uploaded_file($_FILES["file-genjournal"]["tmp_name"], $target_file)) {
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
					$plantid = Yii::app()->db->createCommand("select plantid from plant where plantcode = '".$plantcode."'")->queryScalar();
					$noref = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$journaldate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(3, $row)->getValue()));
					$journalnote = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$abid = Yii::app()->db->createCommand("select genjournalid from genjournal 
						where plantid = '".$plantid."' 
						and noref = '".$noref."' 
						and journaldate = '".$journaldate."'
						and journalnote = '".$journalnote."'")->queryScalar();
					$recordstatus = findstatusbyuser('insgenjournal');
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
			$this->ModifyData($connection,array((isset($_POST['genjournal-genjournalid'])?$_POST['genjournal-genjournalid']:''),
				$_POST['genjournal-plantid'],date(Yii::app()->params['datetodb'], strtotime($_POST['genjournal-journaldate'])),
				$_POST['genjournal-referenceno'],$_POST['genjournal-journalnote']));
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
			$sql     = 'call Insertjournaldetail(:vgenjournalid,:vaccountid,:vdebit,:vcredit,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatejournaldetail(:vid,:vgenjournalid,:vaccountid,:vdebit,:vcredit,:vcurrencyid,:vratevalue,:vdetailnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vgenjournalid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vaccountid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vdebit', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vcredit', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vratevalue', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vdetailnote', $arraydata[7], PDO::PARAM_STR);
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
			$this->ModifyDataDetail($connection,array((isset($_POST['journaldetailid'])?$_POST['journaldetailid']:''),
				$_POST['genjournalid'],$_POST['accountid'],
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
					$sql     = 'call Purgegenjournal(:vid,:vdatauser)';
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
        $sql     = 'call Purgejournaldetail(:vid,:vdatauser)';
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
  public function actionReject() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call RejectGenJournal(:vid,:vdatauser)';
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
        $sql     = 'call ApproveJournal(:vid,0,:vdatauser)';
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
    $sql = "select a.genjournalid,
						ifnull(b.companyname,'-')as company,
						ifnull(a.journalno,'-')as journalno,
						ifnull(a.referenceno,'-')as referenceno,
						a.journaldate,a.postdate,
						ifnull(a.journalnote,'-')as journalnote,a.recordstatus,a.companyid
						from genjournal a
						left join company b on b.companyid = a.companyid ";
		$genjournalid = filter_input(INPUT_GET,'genjournalid');
		$companyname = filter_input(INPUT_GET,'companyname');
		$journalno = filter_input(INPUT_GET,'journalno');
		$referenceno = filter_input(INPUT_GET,'referenceno');
		$journaldate = filter_input(INPUT_GET,'journaldate');
		$sql .= " where coalesce(a.genjournalid,'') like '%".$genjournalid."%' 
			and coalesce(b.companyname,'') like '%".$companyname."%'
			and coalesce(a.journalno,'') like '%".$journalno."%'
			and coalesce(a.referenceno,'') like '%".$referenceno."%'
			and coalesce(a.journaldate,'') like '%".$journaldate."%'
			";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.genjournalid in (" . $_GET['id'] . ")";
    }
    $debit            = 0;
    $credit           = 0;
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('genjournal');
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
							where a.genjournalid = '" . $row['genjournalid'] . "'
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
        15,
        50
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
          Yii::app()->format->formatNumber($row1['ratevalue']),
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