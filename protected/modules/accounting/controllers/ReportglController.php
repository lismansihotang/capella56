<?php
class ReportglController extends Controller {
  public $menuname = 'reportgl';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail() {
    if (isset($_GET['grid']))
      echo $this->actionsearchdetail();
    else
      $this->renderPartial('index', array());
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
    $accountcode    = GetSearchText(array('POST'),'accountcode');
    $accountname    = GetSearchText(array('POST'),'accountname');
    $recordstatus    = GetSearchText(array('POST'),'recordstatus');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','genjournalid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset       = ($page - 1) * $rows;
    $result       = array();
    $row          = array();
		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('genjournal t')->where("
				((coalesce(journaldate,'') like :journaldate) 
				and (coalesce(journalnote,'') like :journalnote) 
				and (coalesce(referenceno,'') like :referenceno) 
				and (coalesce(companycode,'') like :companycode) 
				and (coalesce(plantcode,'') like :plantcode) 
				and (coalesce(journalno,'') like :journalno) 
				and (coalesce(genjournalid,'') like :genjournalid))".
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
      $cmd = Yii::app()->db->createCommand()->select('t.*,
			(select sum(debit) from journaldetail z where z.genjournalid = t.genjournalid) as debit,
			(select sum(credit) from journaldetail z where z.genjournalid = t.genjournalid) as credit')
			->from('genjournal t')->where("
				((coalesce(journaldate,'') like :journaldate) 
				and (coalesce(journalnote,'') like :journalnote) 
				and (coalesce(referenceno,'') like :referenceno) 
				and (coalesce(companycode,'') like :companycode) 
				and (coalesce(plantcode,'') like :plantcode) 
				and (coalesce(journalno,'') like :journalno) 
				and (coalesce(genjournalid,'') like :genjournalid))".
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
        'recordstatusgenjournal' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionsearchdetail()
  {
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('journaldetail t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->where('genjournalid = :genjournalid', array(
      ':genjournalid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.accountcode,a.accountname,b.currencyname')->from('journaldetail t')->leftjoin('account a', 'a.accountid = t.accountid')->leftjoin('currency b', 'b.currencyid = t.currencyid')->where('genjournalid = :genjournalid', array(
      ':genjournalid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
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
        'ratevalue' => Yii::app()->format->formatNumber($data['ratevalue']),
        'detailnote' => $data['detailnote']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		$sql = "select sum(debit) as debit, sum(credit) as credit from journaldetail where genjournalid = ".$id;
		$cmd = Yii::app()->db->createCommand($sql)->queryRow();
		$footer[] = array(
      'accountname' => 'Total',
      'debit' => Yii::app()->format->formatNumber($cmd['debit']),
      'credit' => Yii::app()->format->formatNumber($cmd['credit']),
    );
    $result = array_merge($result, array(
      'footer' => $footer
    ));    echo CJSON::encode($result);
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select a.genjournalid,
						ifnull(b.companycode,'-')as company,
						ifnull(a.journalno,'-')as journalno,
						ifnull(a.referenceno,'-')as referenceno,
						a.journaldate,a.postdate,
						ifnull(a.journalnote,'-')as journalnote,a.recordstatus
						from genjournal a
						left join company b on b.companyid = a.companyid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.genjournalid in (" . $_GET['id'] . ")";
    }
    $debit            = 0;
    $credit           = 0;
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('genjournal');
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
          Yii::app()->numberFormatter->formatCurrency($row1['debit'], $row1['symbol']),
          Yii::app()->numberFormatter->formatCurrency($row1['credit'], $row1['symbol']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberprice"], $row1['ratevalue']),
          $row1['detailnote']
        ));
      }
      $this->pdf->row(array(
        '',
        'Total',
        Yii::app()->numberFormatter->formatCurrency($debit, $row1['symbol']),
        Yii::app()->numberFormatter->formatCurrency($credit, $row1['symbol']),
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
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select journalno,referenceno,journaldate,postdate,journalnote,recordstatus
				from genjournal a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.genjournalid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('journalno'))->setCellValueByColumnAndRow(1, 1, GetCatalog('referenceno'))->setCellValueByColumnAndRow(2, 1, GetCatalog('journaldate'))->setCellValueByColumnAndRow(3, 1, GetCatalog('postdate'))->setCellValueByColumnAndRow(4, 1, GetCatalog('journalnote'))->setCellValueByColumnAndRow(5, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['journalno'])->setCellValueByColumnAndRow(1, $i + 1, $row1['referenceno'])->setCellValueByColumnAndRow(2, $i + 1, $row1['journaldate'])->setCellValueByColumnAndRow(3, $i + 1, $row1['postdate'])->setCellValueByColumnAndRow(4, $i + 1, $row1['journalnote'])->setCellValueByColumnAndRow(5, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="genjournal.xlsx"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $objWriter->save('php://output');
    unset($excel);
  }
}
