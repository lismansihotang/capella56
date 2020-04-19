<?php
class GenledgerController extends Controller {
  public $menuname = 'genledger';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $genledgerid    = GetSearchText(array('POST'),'genledgerid');
    $accountcode		= GetSearchText(array('POST'),'accountcode');
    $accountname     = GetSearchText(array('POST'),'accountname');
    $journalno       = GetSearchText(array('POST'),'journalno');
    $postdate       = GetSearchText(array('POST'),'postdate');
    $companycode       = GetSearchText(array('POST'),'companycode');
    $plantcode       = GetSearchText(array('POST'),'plantcode');
    $detailnote       = GetSearchText(array('POST'),'detailnote');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','genledgerid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
		->from('genledger t')
		->leftjoin('genjournal a', 'a.genjournalid = t.genjournalid')
		->where("((coalesce(t.accountname,'') like :accountname) and 
		(coalesce(t.accountcode,'') like :accountcode) and 
		(coalesce(a.journalno,'') like :journalno) and 
		(coalesce(t.companycode,'') like :companycode) and 
		(coalesce(t.plantcode,'') like :plantcode) and 
		(coalesce(t.genledgerid,'') like :genledgerid) and 
		(coalesce(t.detailnote,'') like :detailnote) and 
		(coalesce(t.journaldate,'') like :postdate)) 
		and t.accountid > 0", array(
      ':accountcode' =>  $accountcode ,
      ':genledgerid' =>  $genledgerid ,
      ':accountname' =>  $accountname ,
      ':journalno' =>  $journalno ,
      ':detailnote' =>  $detailnote ,
      ':companycode' =>  $companycode ,
      ':plantcode' =>  $plantcode ,
      ':postdate' =>  $postdate 
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.journalno')
		->from('genledger t')
		->leftjoin('genjournal a', 'a.genjournalid = t.genjournalid')
		->where("((coalesce(t.accountname,'') like :accountname) and 
		(coalesce(t.accountcode,'') like :accountcode) and 
		(coalesce(a.journalno,'') like :journalno) and 
		(coalesce(t.companycode,'') like :companycode) and 
		(coalesce(t.plantcode,'') like :plantcode) and 
		(coalesce(t.genledgerid,'') like :genledgerid) and 
		(coalesce(t.detailnote,'') like :detailnote) and 
		(coalesce(t.journaldate,'') like :postdate)) 
    and t.accountid > 0", array(
      ':accountcode' =>  $accountcode ,
      ':accountname' =>  $accountname ,
      ':genledgerid' =>  $genledgerid ,
      ':detailnote' =>  $detailnote ,
      ':journalno' =>  $journalno ,
      ':companycode' =>  $companycode ,
      ':plantcode' =>  $plantcode ,
      ':postdate' =>  $postdate 
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'genledgerid' => $data['genledgerid'],
        'accountid' => $data['accountid'],
        'accountcode' => $data['accountcode'],
        'accountname' => $data['accountname'],
        'genjournalid' => $data['genjournalid'],
        'journalno' => $data['journalno'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'plantcode' => $data['plantcode'],
        'plantid' => $data['plantid'],
        'debit' => Yii::app()->format->formatNumber($data['debit']),
        'credit' => Yii::app()->format->formatNumber($data['credit']),
        'postdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['postdate'])),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'symbol' => $data['symbol'],
        'detailnote' => $data['detailnote'],
        'ratevalue' => Yii::app()->format->formatNumber($data['ratevalue'])
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-genledger"]["name"]);
		if (move_uploaded_file($_FILES["file-genledger"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$plantcode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$plantid = Yii::app()->db->createCommand("select plantid from plant where plantcode = '".$plantcode."'")->queryScalar();
					$journalno = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$accountcode = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$accountname = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$companyid = Yii::app()->db->createCommand("select companyid from plant where plantcode = '".$plantcode."'")->queryScalar();
					$accountid = Yii::app()->db->createCommand("
						select distinct accountid 
						from account a
						join company b on b.companyid = a.companyid 
						where a.accountcode = '".$accountcode."'
						and a.companyid = ".$companyid)->queryScalar();
					$genjournalid = Yii::app()->db->createCommand("select genjournalid from genjournal
						where companyid = ".$companyid." 
						and journalno = '".$journalno."'")->queryScalar();
					$debit = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$credit = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$postdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(7, $row)->getValue()));
					$journaldate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(8, $row)->getValue()));
					$currencyname = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
					$currencyid = Yii::app()->db->createCommand("select currencyid from currency where symbol = '".$currencyname."' and recordstatus = 1")->queryScalar();
					$ratevalue = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
					$detailnote = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
					$sql = "
						select genledgerid 
						from genledger 
						where companyid = ".$companyid." 
						and plantid = ".$plantid." 
						and postdate = '".$postdate."' 
						and accountid = ".$accountid." 
						and currencyid = ".$currencyid." 
					";
					$k = Yii::app()->db->createCommand($sql)->queryScalar();
					if($k > 0) {
						$sql = "update genledger 
							set debit = ".$debit.",
								credit = ".$credit." 							
							where
								genledgerid = ".$k; 
						$connection->createCommand($sql)->execute();
					} else {
						$sql = "insert into genledger (companyid,plantid,accountid,genjournalid,debit,credit,postdate,journaldate,currencyid,ratevalue,detailnote) 
							values (:companyid,:plantid,:accountid,:genjournalid,:debit,:credit,:postdate,:journaldate,:currencyid,:ratevalue,:detailnote)";
						$command = $connection->createCommand($sql);
						$command->bindvalue(':companyid',$companyid,PDO::PARAM_STR);
						$command->bindvalue(':plantid',$plantid,PDO::PARAM_STR);
						$command->bindvalue(':accountid',$accountid,PDO::PARAM_STR);
						$command->bindvalue(':genjournalid',null,PDO::PARAM_STR);
						$command->bindvalue(':debit',$debit,PDO::PARAM_STR);
						$command->bindvalue(':credit',$credit,PDO::PARAM_STR);
						$command->bindvalue(':postdate',$postdate,PDO::PARAM_STR);
						$command->bindvalue(':journaldate',$journaldate,PDO::PARAM_STR);
						$command->bindvalue(':currencyid',$currencyid,PDO::PARAM_STR);
						$command->bindvalue(':ratevalue',$ratevalue,PDO::PARAM_STR);
						$command->bindvalue(':detailnote',$detailnote,PDO::PARAM_STR);
						$command->execute();
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
  public function actionDownPDF() {
    parent::actionDownload();
    $connection = Yii::app()->db;
    $sql        = "select t.genledgerid, c.accountname, t.genjournalid, t.debit, t.credit, d.symbol, t.postdate, 
			d.currencyname, t.ratevalue,t.companyid,b.journalno,t.companycode,a.plantcode,t.detailnote
			from genledger t 
			left join account c on c.accountid = t.accountid 
			left join currency d on d.currencyid = t.currencyid
			LEFT JOIN genjournal b ON b.genjournalid = t.genjournalid 
			left join plant a on a.plantid = b.plantid ";
		$genledgerid = filter_input(INPUT_GET,'genledgerid');
		$accountname = filter_input(INPUT_GET,'accountname');
		$accountcode = filter_input(INPUT_GET,'accountcode');
		$journalno = filter_input(INPUT_GET,'journalno');
		$postdate = filter_input(INPUT_GET,'postdate');
		$detailnote = filter_input(INPUT_GET,'detailnote');
		$sql .= " where coalesce(t.genledgerid,'') like '%".$genledgerid."%' 
			and coalesce(c.accountname,'') like '%".$accountname."%'
			and coalesce(c.accountcode,'') like '%".$accountcode."%'
			and coalesce(b.journalno,'') like '%".$journalno."%'
			and coalesce(t.postdate,'') like '%".$postdate."%'
			and coalesce(t.detailnote,'') like '%".$detailnote."%'
			";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and t.genledgerid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('Genledger');
    $this->pdf->AddPage('L','A4');
		$this->pdf->setFont('Arial', '', 10);
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
      'L'
    );
    $this->pdf->colheader = array(
      GetCatalog('company'),
      GetCatalog('plant'),
      GetCatalog('accountname'),
      GetCatalog('journalno'),
      GetCatalog('debit'),
      GetCatalog('credit'),
      GetCatalog('postdate'),
      GetCatalog('currencyname'),
      GetCatalog('ratevalue'),
      GetCatalog('detailnote'),
    );
    $this->pdf->setwidths(array(
      22,
      27,
      40,
      30,
      30,
      30,
      20,
      30,
      20,
			35
    ));
    $this->pdf->Rowheader();
    $this->pdf->setFont('Arial', '', 8);
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'L',
      'L',
      'R',
      'R',
      'L',
      'L',
      'R',
			'L'
    );
    foreach ($dataReader as $row1) {
      $this->pdf->row(array(
        $row1['companycode'],
        $row1['plantcode'],
        $row1['accountname'],
        $row1['journalno'],
        Yii::app()->format->formatCurrency($row1['debit']),
        Yii::app()->format->formatCurrency($row1['credit']),
        $row1['postdate'],
        $row1['currencyname'],
        Yii::app()->format->formatCurrency($row1['ratevalue']),
				$row1['detailnote'],
      ));
    }
    $this->pdf->Output();
  }
}