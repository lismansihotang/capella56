<?php
class ReportaccController extends Controller {
	public $menuname = 'reportacc';
	public function actionIndex() {
		$this->renderPartial('index',array());
	}
	public function actionDownPDF() {
		parent::actionDownload();
		if ($_GET['company'] == '') {
			echo getcatalog('emptycompany');
		} else 
		if ($_GET['lro'] == '') {
			GetMessage(true,'choosereport');
		} else 
		if ($_GET['startdate'] == '') {
			GetMessage(true,'emptystartdate');
		} else 
		if ($_GET['enddate'] == '') {
			GetMessage(true,'emptyenddate');
		} else {			
			switch ($_GET['lro']) {
				case 1:
					$this->RincianJurnalTransaksi($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['employee'],$_GET['product'],$_GET['account'], $_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 2:
					$this->BukuBesar($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['employee'],$_GET['product'],$_GET['account'], $_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 3:
					$this->NeracaUjiCoba($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['employee'],$_GET['product'],$_GET['account'], $_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 4:
					$this->LabaRugiUjiCoba($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['employee'],$_GET['product'],$_GET['account'], $_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 5:
					$this->LaporanAsset($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['employee'],$_GET['product'],$_GET['account'], $_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 14:
					$this->RekapJurnalUmumPerDokumenBelumStatusMax($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['employee'],$_GET['product'],$_GET['account'], $_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 15:
					$this->RekapPenerimaanKasBankPerDokumentBelumStatusMax($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['employee'],$_GET['product'],$_GET['account'], $_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 16:
					$this->RekapPengeluaranKasBankPerDokumentBelumStatusMax($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['employee'],$_GET['product'],$_GET['account'], $_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 17:
					$this->RekapCashBankPerDokumentBelumStatusMax($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['employee'],$_GET['product'],$_GET['account'], $_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 18:
					$this->LampiranNeraca1($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['employee'],$_GET['product'],$_GET['account'], $_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 19:
					$this->LampiranNeraca2($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['employee'],$_GET['product'],$_GET['account'], $_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 20:
					$this->LampiranPiutangKaryawan($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['employee'],$_GET['product'],$_GET['account'], $_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 21:
					$this->laporankasbankharian($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['employee'],$_GET['product'],$_GET['account'], $_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 22:
					$this->piutangkaryawan($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['employee'],$_GET['product'],$_GET['account'], $_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				default:
					echo getCatalog('reportdoesnotexist');
			}
		}
	}
	//22
	public function piutangkaryawan($companyid,$plant,$sloc,$materialgroup,$customer,$employee,$product,$account,$startdate,$enddate,$per) {
    parent::actionDownload();
    $totalawal1=0;$totaldebit1=0;$totalcredit1=0;
    $sql = "select a.employeeid, a.fullname, a.oldnik, b.positionname
						from employee a
						left join position b on b.positionid = a.positionid
						left join employeepiutang c on c.employeeid = a.employeeid
						where c.recordstatus = 3 ".
						(($plant != '')?" and c.plantid = ".$plant:'').
						" and coalesce(a.fullname,'') like '%".$employee."%'
						and c.employeepiutangdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'";
    $command=$this->connection->createCommand($sql);
    $dataReader=$command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title='Laporan Piutang Karyawan';
    $this->pdf->subtitle = 'Dari Tgl : '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('L');
    foreach($dataReader as $row) {
      $sql1 = "select sum(awalpay-awalpiutang) as saldoawal
									from (select a.employeeid, a.fullname, a.oldnik,
									ifnull((select sum(xx.nilai)
									from employeepiutang xx
									where xx.employeeid = a.employeeid and xx.employeepiutangdate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'
									),0) as awalpiutang,
									ifnull((select sum(xx.nilai)
									from employeepay xx
									join employeepiutang xxx on xxx.employeepiutangid = xx.employeepiutangid
									where xxx.employeeid = a.employeeid and xx.employeepaydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'
									),0) as awalpay
									from employee a
									where a.employeeid = ".$row['employeeid']."
									) z";
			$command1=$this->connection->createCommand($sql1);
			$dataReader1=$command1->queryAll();
			
      foreach($dataReader1 as $row1) {
        $this->pdf->SetFont('Arial','',10);
        $this->pdf->text(235,$this->pdf->gety()+5,'Saldo Awal :  '.Yii::app()->format->formatCurrency($row1['saldoawal']/$per));
        $sql2 = "select *, case when kode = 'PIU' then nilai end as kredit,
									case when kode = 'PAY' then nilai end as debit
									from (select 'PIU' as kode, a.employeepiutangno as nomerbukti, a.employeepiutangdate as tanggal, a.nilai, a.employeeid as karyawan,
									b.fullname as namakaryawan,a.description
									from employeepiutang a
									join employee b on b.employeeid = a.employeeid
									where a.recordstatus = 3 ".
									(($plant != '')?" and a.plantid = ".$plant:'').
									" and coalesce(b.fullname,'') like '%".$employee."%'
									and a.employeepiutangdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
									and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'

									union

									select 'PAY' as kode, a.employeepayno as nomerbukti, a.employeepaydate as tanggal, a.nilai, b.employeeid as karyawan,c.fullname as namakaryawan,a.description
									from employeepay a
									left join employeepiutang b on b.employeepiutangid = a.employeepiutangid
									join employee c on c.employeeid = b.employeeid
									where a.recordstatus = 3 ". 
									(($plant != '')?" and a.plantid = ".$plant:'').
									" and coalesce(c.fullname,'') like '%".$employee."%'
									and a.employeepaydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
									and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
									)z where karyawan = '".$row['employeeid']."'";
									
        $command2=$this->connection->createCommand($sql2);
        $dataReader2=$command2->queryAll();
        $saldo=0;$i=0;
        $this->pdf->setFont('Arial','B',8);
        $this->pdf->sety($this->pdf->gety()+7);
        $this->pdf->colalign = array('C','C','C','C','C','C','C','C','C');
        $this->pdf->setwidths(array(10,20,17,25,30,90,30,30,30));
        $this->pdf->colheader = array('No','Tanggal','No Dokumen','Penjelasan','Keterangan','Debet','Credit','Saldo');
        $this->pdf->RowHeader();
        $this->pdf->coldetailalign = array('R','C','C','C','L','L','R','R','R');		
        $saldo=0;$i=0;$totaldebit=0;$totalcredit=0;
        foreach($dataReader2 as $row2) {
          $i+=1;
          $this->pdf->setFont('Arial','',8);
          $this->pdf->row(array(
            $i,
            date(Yii::app()->params['dateviewfromdb'], strtotime($row2['tanggal'])),
            $row2['nomerbukti'],
						'Setoran pinjaman dari'.' '.$row2['namakaryawan'],
            $row2['description'],
            Yii::app()->format->formatCurrency($row2['debit']/$per),
            Yii::app()->format->formatCurrency($row2['credit']/$per),
						''
          ));
          $totaldebit += $row2['debit']/$per;
          $totalcredit += $row2['credit']/$per;		               
        }
				$this->pdf->setFont('Arial','B',8);
        $this->pdf->row(array(
          '','','','','','',
          Yii::app()->format->formatCurrency($totaldebit),
          Yii::app()->format->formatCurrency($totalcredit),
          Yii::app()->format->formatCurrency(($row1['saldoawal']/$per) + $totaldebit - $totalcredit)
        ));
        
        $this->pdf->checkPageBreak(10);
				}
				}
    $this->pdf->Output();
  }
	//21
	public function laporankasbankharian($companyid,$plant,$sloc,$materialgroup,$customer,$employee,$product,$account,$startdate,$enddate,$per) {
		parent::actionDownload();
		$sql = "select *, ((awalin+awalcbin) - (awalout+awalcbout)) as saldowal
						from (select *, case when kode = 'CBP' then amount end as pengeluaran,
						case when kode = 'CBR' then amount end as penerimaan,
						ifnull((select sum(xx.amount)
						from cashbankindetail xx
						join cashbankin ax on ax.cashbankinid = xx.cashbankinid
						left join account cx on cx.accountid = ax.accountid
						left join plant dx on dx.plantid = ax.plantid
						left join invoicear ex on ex.invoicearid = xx.invoicearid
						left join addressbook fx on fx.addressbookid = ex.addressbookid
						where ax.recordstatus > 2 ". 
						(($plant != '')?" and ax.plantid = ".$plant:'').
						" and coalesce(fx.fullname,'') like '%".$customer."%' and coalesce(cx.accountname,'') like '%".$account."%' and
						ax.cashbankindate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),0) as awalin,
						ifnull((select sum(xx.amount)
						from cashbankoutdetail xx
						join cashbankout ax on ax.cashbankoutid = xx.cashbankoutid
						left join account cx on cx.accountid = ax.accountid
						left join plant dx on dx.plantid = ax.plantid
						left join invoicear ex on ex.invoicearid = xx.invoiceapid
						left join addressbook fx on fx.addressbookid = ex.addressbookid
						where ax.recordstatus > 2 ".
						(($plant != '')?" and ax.plantid = ".$plant:'').
						" and coalesce(fx.fullname,'') like '%".$customer."%' and coalesce(cx.accountname,'') like '%".$account."%' and
						ax.cashbankoutdate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),0) as awalout,
						ifnull((select sum(xx.amount)
						from cashbankdetail xx
						join cashbank ax on ax.cashbankid = xx.cashbankid
						left join account cx on cx.accountid = ax.accountid 
						left join plant dx on dx.plantid = ax.plantid 
						left join addressbook ex on ex.addressbookid = ax.addressbookid
						where ax.recordstatus > 2 ".
						(($plant != '')?" and ax.plantid = ".$plant:'').
						" and coalesce(ex.fullname,'') like '%".$customer."%' and coalesce(cx.accountname,'') like '%".$account."%' 
						and ax.isin = 0 and ax.cashbankdate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),0) as awalcbout,
						ifnull((select sum(xx.amount)
						from cashbankdetail xx
						join cashbank ax on ax.cashbankid = xx.cashbankid
						left join account cx on cx.accountid = ax.accountid 
						left join plant dx on dx.plantid = ax.plantid 
						left join addressbook ex on ex.addressbookid = ax.addressbookid
						where ax.recordstatus > 2 ".
						(($plant != '')?" and ax.plantid = ".$plant:'').
						" and coalesce(ex.fullname,'') like '%".$customer."%' and coalesce(cx.accountname,'') like '%".$account."%' 
						and ax.isin = 1 and ax.cashbankdate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),0) as awalcbin
						from (
						select 'CBR' as kode, a.cashbankinno as cashbankno,c.accountcode,c.accountname,d.plantcode,e.invoicearno as invoice,f.fullname,
						b.amount,a.cashbankindate as cashbankdate, e.invoicearno as referensino, a.headernote,g.symbol
						from cashbankin a
						left join cashbankindetail b on b.cashbankinid = a.cashbankinid
						left join account c on c.accountid = a.accountid
						left join plant d on d.plantid = a.plantid
						left join invoicear e on e.invoicearid = b.invoicearid
						left join addressbook f on f.addressbookid = e.addressbookid
						left join currency g on g.currencyid = b.currencyid
						where a.recordstatus > 2 ".
						(($plant != '')?" and a.plantid = ".$plant:'').
						" and coalesce(f.fullname,'') like '%".$customer."%' and coalesce(c.accountname,'') like '%".$account."%'
						and a.cashbankindate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'

						union 

						select 'CBP', a.cashbankoutno as cashbankno ,c.accountcode,c.accountname,d.plantcode,e.invoiceapno as invoice,f.fullname,
						b.amount,a.cashbankoutdate as cashbankdate, e.invoiceapno as referensino, a.headernote,g.symbol
						from cashbankout a
						left join cashbankoutdetail b on b.cashbankoutid = a.cashbankoutid
						left join account c on c.accountid = a.accountid
						left join plant d on d.plantid = a.plantid
						left join invoiceap e on e.invoiceapid = b.invoiceapid
						left join addressbook f on f.addressbookid = e.addressbookid
						left join currency g on g.currencyid = b.currencyid
						where a.recordstatus > 2 ".
						(($plant != '')?" and a.plantid = ".$plant:'').
						" and coalesce(f.fullname,'') like '%".$customer."%' and coalesce(c.accountname,'') like '%".$account."%'
						and a.cashbankoutdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
						
						union 
						
						select case when a.isin = 0 then 'CBP' else 'CBR' end, a.cashbankno,c.accountcode,c.accountname,d.plantcode,'',e.fullname,
						b.amount,a.cashbankdate,a.cashbankno,a.headernote,g.symbol
						from cashbank a 
						left join cashbankdetail b on b.cashbankid = a.cashbankid 
						left join account c on c.accountid = a.accountid 
						left join plant d on d.plantid = a.plantid 
						left join addressbook e on e.addressbookid = a.addressbookid
						left join currency g on g.currencyid = b.currencyid
						where a.recordstatus > 2 ".
						(($plant != '')?" and a.plantid = ".$plant:'').
						" and coalesce(e.fullname,'') like '%".$customer."%' and coalesce(c.accountname,'') like '%".$account."%'
						and a.cashbankdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
						) z where cashbankno is not null order by kode = 'CBP', cashbankdate asc)zz ";

		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$totin        = 0;
		$totout        = 0;
		$totawal        = 0;
		$this->pdf->companyid = $companyid;
		$this->pdf->title='Rekap Cash Bank Harian';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L');
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->sety($this->pdf->gety()+10);
		$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(8,32,32,20,33,38,28,28,70));
		$this->pdf->colheader = array('No','Akun Bank','No Transaksi','Tanggal','No Referensi','Pihak ke-3','Penerimaan','Pengeluaran','Keterangan');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','R','R','L');		
		$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
		foreach($dataReader as $row) {
			$i+=1;
			$this->pdf->setFont('Arial','',7);
			$this->pdf->row(array(
				$i,$row['accountname'],
				$row['cashbankno'],
				date(Yii::app()->params['dateviewfromdb'], strtotime($row['cashbankdate'])),
				$row['referensino'],
				$row['fullname'],
				Yii::app()->numberFormatter->formatCurrency($row['penerimaan']/$per, $row['symbol']),
				Yii::app()->numberFormatter->formatCurrency($row['pengeluaran']/$per, $row['symbol']),
				$row['headernote']
			));
			 $totin += $row['penerimaan'];
			 $totout += $row['pengeluaran'];
			 $totawal = $row['saldowal'];
			
		}
		$this->pdf->setFont('Arial','B',7);
		$this->pdf->row(array(
        '',
        'Saldo Awal :',
			Yii::app()->numberFormatter->formatCurrency($totawal/$per, $row['symbol']),
			'',
			'',
			'Total :',
       Yii::app()->numberFormatter->formatCurrency($totin/$per, $row['symbol']),
       Yii::app()->numberFormatter->formatCurrency($totout/$per, $row['symbol'])
       
      ));
		$this->pdf->checkPageBreak(20);
		$this->pdf->Output();
	}
	
	public function RincianJurnalTransaksi($companyid,$plant,$sloc,$materialgroup,$customer,$employee,$product,$account,$startdate,$enddate,$per) {
    parent::actionDownload();
    $debit=0;$credit=0;
    $sql = "select distinct a.genjournalid,e.symbol,
			ifnull(b.companyname,'-')as company,
			ifnull(a.journalno,'-')as journalno,
			ifnull(a.referenceno,'-')as referenceno,
			a.journaldate,a.postdate,
			ifnull(a.journalnote,'-')as journalnote,a.recordstatus
			from genjournal a
			left join company b on b.companyid = a.companyid
			left join genledger c on c.genjournalid = a.genjournalid
			left join account d on d.accountid = c.accountid
			left join currency e on e.currencyid = d.currencyid
			where a.recordstatus = 3 ".
			(($plant != '')?" and a.plantid = ".$plant:'').
			" and coalesce(d.accountname,'') like '%".$account."%'
			and a.journaldate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
			and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'";
		$command=$this->connection->createCommand($sql);
    $dataReader=$command->queryAll();
		$this->pdf->companyid = $companyid;
		$this->pdf->title = 'Rincian Jurnal Transaksi';
    $this->pdf->AddPage('P','A4');
    foreach ($dataReader as $row) {
			$this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No Journal ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['journalno']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Ref No ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . $row['referenceno']);
      $this->pdf->text(15, $this->pdf->gety() + 15, 'Tgl Jurnal ');
      $this->pdf->text(50, $this->pdf->gety() + 15, ': ' . $row['journaldate']);
      $sql1 = "select b.accountcode,b.accountname, a.debit,a.credit,c.symbol,a.detailnote,a.ratevalue
				from journaldetail a
				left join account b on b.accountid = a.accountid
				left join currency c on c.currencyid = a.currencyid
				where coalesce(b.accountname,'') like '%".$account."%' and a.genjournalid = '" . $row['genjournalid'] . "'
				order by journaldetailid ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 20);
      $this->pdf->colalign = array('C','C','C','C','C','C');
      $this->pdf->setwidths(array(10,60,30,30,10,55));
      $this->pdf->colheader = array('No','Account','Debit','Credit','Rate','Detail Note');
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->coldetailalign = array('C','L','R','R','R','L');
      $i=0;
      foreach ($dataReader1 as $row1) {
        $i=$i+1;
        $debit  = $debit + ($row1['debit']/$per * $row1['ratevalue']);
        $credit = $credit + ($row1['credit']/$per * $row1['ratevalue']);
        $this->pdf->row(array(
          $i,
          $row1['accountcode'] . ' ' . $row1['accountname'],
          Yii::app()->format->formatNumber($row1['debit']/$per),
          Yii::app()->format->formatNumber($row1['credit']/$per),
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
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->border = false;
      $this->pdf->setwidths(array(
        20,
        175
      ));
      $this->pdf->row(array(
        'Note',
        $row['journalnote']
      ));
      $this->pdf->AddPage('P','A4');
    }
    $this->pdf->Output();
  }
	public function BukuBesar($companyid,$plant,$sloc,$materialgroup,$customer,$employee,$product,$account,$startdate,$enddate,$per) {
    parent::actionDownload();
    $totalawal1=0;$totaldebit1=0;$totalcredit1=0;
    $sql = "select distinct b.accountid,b.accountname,b.accountcode
			from genledger b 
			where b.accountname like '%".$account."%'".
			(($plant != '')?" and b.plantid = ".$plant:'').
			" and b.journaldate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
    $sql = $sql . " order by b.accountcode";
    $command=$this->connection->createCommand($sql);
    $dataReader=$command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title='Buku Besar';
    $this->pdf->subtitle = 'Dari Tgl : '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('L');
    foreach($dataReader as $row) {
      $sql1 = "select sum(ifnull(zz.debit,0)-ifnull(zz.credit,0)) as saldoawal
            from genledger zz 
            where zz.accountid = ".$row['accountid'].
						(($plant != '')?" and zz.plantid = ".$plant:'').
						" and zz.journaldate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'";
			$command1=$this->connection->createCommand($sql1);
			$dataReader1=$command1->queryAll();
      foreach($dataReader1 as $row1) {
        $this->pdf->SetFont('Arial','',10);
        //$this->pdf->text(10,$this->pdf->gety()+10,$row['accountcode']);
        $this->pdf->text(10,$this->pdf->gety()+5,$row['accountcode']);
        $this->pdf->text(40,$this->pdf->gety()+5,' '.$row['accountname']);
        $this->pdf->text(235,$this->pdf->gety()+5,'Saldo Awal :  '.Yii::app()->format->formatCurrency($row1['saldoawal']/$per));
        $sql2 = "select b.journalno,a.journaldate,b.referenceno,b.journalnote,a.debit,a.credit,a.detailnote
					 from genledger a
					 left join genjournal b on b.genjournalid = a.genjournalid
					 where a.accountid = '".$row['accountid']."'". 
					 (($plant != '')?" and a.plantid = ".$plant:'').
					 " and a.journaldate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
					 and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
					 order by journaldate";
        $command2=$this->connection->createCommand($sql2);
        $dataReader2=$command2->queryAll();
        $saldo=0;$i=0;
        $this->pdf->setFont('Arial','B',8);
        $this->pdf->sety($this->pdf->gety()+7);
        $this->pdf->colalign = array('C','C','C','C','C','C','C','C','C');
        $this->pdf->setwidths(array(10,30,17,30,50,50,30,30,30));
        $this->pdf->colheader = array('No','Dokumen','Tanggal','Referensi','Keterangan','Uraian','Debet','Credit','Saldo');
        $this->pdf->RowHeader();
        $this->pdf->coldetailalign = array('R','C','C','C','L','L','R','R','R');		
        $saldo=0;$i=0;$totaldebit=0;$totalcredit=0;
        foreach($dataReader2 as $row2) {
          $i+=1;
          $this->pdf->setFont('Arial','',8);
          $this->pdf->row(array(
            $i,
            $row2['journalno'],
            date(Yii::app()->params['dateviewfromdb'], strtotime($row2['journaldate'])),
            $row2['referenceno'],$row2['journalnote'],
            $row2['detailnote'],
            Yii::app()->format->formatCurrency($row2['debit']/$per),
            Yii::app()->format->formatCurrency($row2['credit']/$per),'-'
          ));
          $totaldebit += $row2['debit']/$per;
          $totalcredit += $row2['credit']/$per;		               
        }
				$this->pdf->setFont('Arial','B',8);
        $this->pdf->row(array(
          '','','','','','TOTAL '.$row['accountname'],
          Yii::app()->format->formatCurrency($totaldebit),
          Yii::app()->format->formatCurrency($totalcredit),
          Yii::app()->format->formatCurrency(($row1['saldoawal']/$per) + $totaldebit - $totalcredit)
        ));
        $totalawal1 += $row1['saldoawal']/$per;
        $totaldebit1 += $totaldebit;
        $totalcredit1 += $totalcredit;

        $this->pdf->sety($this->pdf->gety()+5);
        $this->pdf->checkPageBreak(10);
      }
    }
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->sety($this->pdf->gety()+5);
		$this->pdf->setwidths(array(10,50,5,35));
		$this->pdf->coldetailalign = array('C','L','C','R');
		
		$this->pdf->row(array(
			'','TOTAL SALDO AWAL ',':',
			Yii::app()->format->formatCurrency($totalawal1)
			));
		$this->pdf->row(array(
			'','TOTAL MUTASI DEBIT ',':',
			Yii::app()->format->formatCurrency($totaldebit1)
			));
		$this->pdf->row(array(
			'','TOTAL MUTASI CREDIT ',':',
			Yii::app()->format->formatCurrency($totalcredit1)
			));
		$this->pdf->row(array(
			'','TOTAL SALDO AKHIR ',':',
			Yii::app()->format->formatCurrency($totalawal1 + $totaldebit1 - $totalcredit1)
			));		
						
    $this->pdf->Output();
  }
	public function NeracaUjiCoba($companyid,$plant,$sloc,$materialgroup,$customer,$employee,$product,$account,$startdate,$enddate,$per) {
		parent::actionDownload();
		$i=0;$bulanini=0;$bulanlalu=0;
		$sql = "select * from(select a.accountid,a.companyid,a.accountcode,a.accountname,a.parentaccountid,a.currencyid,a.accounttypeid,a.recordstatus,
					ifnull((select sum(b.debit*b.ratevalue)-sum(b.credit*b.ratevalue)
					from genledger b
					where b.accountid = a.accountid and b.journaldate <= last_day('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
					group by accountid asc),0) as bulanini,
					ifnull((select sum(b.debit*b.ratevalue)-sum(b.credit*b.ratevalue)
					from genledger b
					where b.accountid = a.accountid and b.journaldate <= last_day(date_sub('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',interval 1 month))
					group by accountid asc),0) as bulanlalu
					from account a
					where coalesce(a.accountname,'') like '%".$account."%' and  a.companyid = ".$companyid." and a.accountcode < '19%') z 
					where z.bulanini <> 0 or z.bulanlalu <> 0
					order by accountcode asc";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->companyid = $companyid;
		$this->pdf->title='Neraca - Uji Coba';
		$this->pdf->subtitle = 'Per Tanggal : '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P', 'A4');
		$this->pdf->sety($this->pdf->gety());
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->colalign = array('C','C','C','C','C');
		$this->pdf->setwidths(array(10,80,30,35,35));
		$this->pdf->colheader = array('No','Nama Akun','Kode Akun','Bulan Ini','Bulan Lalu');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('R','L','L','R','R');
		foreach($dataReader as $row) {
			$this->pdf->SetFont('Arial','',8);
			$i+=1;
			$this->pdf->row(array(
				$i,$row['accountname'],
				$row['accountcode'],
				Yii::app()->format->formatCurrency($row['bulanini']/$per),
				Yii::app()->format->formatCurrency($row['bulanlalu']/$per),
			));
			$bulanini += $row['bulanini']/$per;
			$bulanlalu += $row['bulanlalu']/$per;
			$this->pdf->checkPageBreak(10);
		}
		$this->pdf->SetFont('Arial','BI',8);
		$this->pdf->row(array(
			'','',
			'TOTAL AKTIVA',
			Yii::app()->format->formatCurrency($bulanini),
			Yii::app()->format->formatCurrency($bulanlalu),
		));
		$i=0;$bulanini=0;$bulanlalu=0;
		$sql = "select * from(select a.accountid,a.companyid,a.accountcode,a.accountname,a.parentaccountid,a.currencyid,a.accounttypeid,a.recordstatus,
					ifnull((select sum(b.debit*b.ratevalue)-sum(b.credit*b.ratevalue)
					from genledger b
					where b.accountid = a.accountid and b.journaldate <= last_day('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
					group by accountid asc),0) as bulanini,
					ifnull((select sum(b.debit*b.ratevalue)-sum(b.credit*b.ratevalue)
					from genledger b
					where b.accountid = a.accountid and b.journaldate <= last_day(date_sub('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',interval 1 month))
					group by accountid asc),0) as bulanlalu
					from account a
					where a.companyid = ".$companyid." and a.accountcode between '2%' and '29%'
					order by a.accountcode asc) z where z.bulanini <> 0 or z.bulanlalu <> 0";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		foreach($dataReader as $row) {
				$this->pdf->companyid = $companyid;
		}
		$this->pdf->title='Neraca - Uji Coba';
		$this->pdf->subtitle = 'Per Tanggal : '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P','A4');
		$this->pdf->sety($this->pdf->gety());
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->colalign = array('C','C','C','C','C');
		$this->pdf->setwidths(array(10,80,30,35,35));
		$this->pdf->colheader = array('No','Nama Akun','Kode Akun','Bulan Ini','Bulan Lalu');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('R','L','L','R','R');
		foreach($dataReader as $row) {
			$this->pdf->SetFont('Arial','',8);
			$i+=1;
			$this->pdf->row(array(
				$i,$row['accountname'],
				$row['accountcode'],
				Yii::app()->format->formatCurrency($row['bulanini']/$per),
				Yii::app()->format->formatCurrency($row['bulanlalu']/$per),
			));
			$bulanini += $row['bulanini']/$per;
			$bulanlalu += $row['bulanlalu']/$per;
			$this->pdf->checkPageBreak(10);
		}
		$this->pdf->SetFont('Arial','BI',8);
		$this->pdf->row(array(
			'','',
			'TOTAL PASIVA',
			Yii::app()->format->formatCurrency($bulanini),
			Yii::app()->format->formatCurrency($bulanlalu),
		));	
		
		$this->pdf->Output();
	}
	public function LabaRugiUjiCoba($companyid,$plant,$sloc,$materialgroup,$customer,$employee,$product,$account,$startdate,$enddate,$per) {
		parent::actionDownload();
		$i=0;$bulanini=0;$bulanlalu=0;
		$sql = "select * from(select a.accountid,a.companyid,a.accountcode,a.accountname,a.parentaccountid,a.currencyid,a.accounttypeid,a.recordstatus,
					ifnull((select -1*(sum(b.debit*b.ratevalue)-sum(b.credit*b.ratevalue))
					from genledger b
					where b.accountid = a.accountid and month(b.journaldate) = month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
					and year(b.journaldate) = year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
					group by accountid asc),0) as bulanini,
					ifnull((select -1*(sum(b.debit*b.ratevalue)-sum(b.credit*b.ratevalue))
					from genledger b
					where b.accountid = a.accountid and month(b.journaldate) = month(last_day(date_sub('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',interval 1 month)))
					and year(b.journaldate) = year(last_day(date_sub('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',interval 1 month)))
					group by accountid asc),0) as bulanlalu
					from account a
					where coalesce(a.accountname,'') like '%".$account."%' and a.companyid = ".$companyid." and a.accountcode > '3%'
					order by a.accountcode asc) z where z.bulanini <> 0 or z.bulanlalu <> 0";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->companyid = $companyid;
		$this->pdf->title='Laba (Rugi) - Uji Coba';
		$this->pdf->subtitle = 'Per Tanggal : '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P','A4');
		$this->pdf->sety($this->pdf->gety());
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->colalign = array('C','C','C','C','C');
		$this->pdf->setwidths(array(10,80,30,35,35));
		$this->pdf->colheader = array('No','Nama Akun','Kode Akun','Bulan Ini','Bulan Lalu');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('R','L','L','R','R');
		foreach($dataReader as $row) {
			$this->pdf->SetFont('Arial','',8);
			$i+=1;
			$this->pdf->row(array(
				$i,$row['accountname'],
				$row['accountcode'],
				Yii::app()->format->formatCurrency($row['bulanini']/$per),
				Yii::app()->format->formatCurrency($row['bulanlalu']/$per),
			));
			$bulanini += $row['bulanini']/$per;
			$bulanlalu += $row['bulanlalu']/$per;
			$this->pdf->checkPageBreak(10);
		}
		$this->pdf->SetFont('Arial','BI',8);
		$this->pdf->row(array(
			'','LABA (RUGI) BERSIH',
			'',
			Yii::app()->format->formatCurrency($bulanini),
			Yii::app()->format->formatCurrency($bulanlalu),
		));
				
		$this->pdf->Output();
	}
	public function LaporanAsset($companyid,$plant,$sloc,$materialgroup,$customer,$employee,$product,$account,$startdate,$enddate,$per) {
		parent::actionDownload();
		$sql = "
			SELECT DISTINCT a.accperolehan,b.accountcode,b.accountname
			FROM fixasset a 
			left join account b on b.accountid = a.accperolehan 
			where plantid like '%".$plant."%' 
			and b.accountname like '%".$account."%' 
			ORDER BY a.accperolehan ASC
		";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->companyid = $companyid;
		$this->pdf->isrepeat = 1;
		$this->pdf->title='Laporan Aktiva';
		$this->pdf->subtitle = 'Per Tanggal : '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','A3');
		foreach($dataReader as $row) {
			$sql1 = "
				SELECT zz.accperolehan,zz.productname,zz.description,zz.qty,zz.uomcode,zz.buydate,zz.ratesusut,zz.price,
				zz.nilairesidu,
				zz.saldoawal,
				zz.nilaisusut,
				case when saldoakhir IS NULL then nilairesidu ELSE saldoakhir end AS saldoakhir
				FROM
				(
				SELECT a.accperolehan,b.productname,a.description,a.qty,c.uomcode,a.buydate,a.ratesusut,a.price,a.nilairesidu,
				(
				SELECT IFNULL(za.nilaibuku,0)
				FROM fahistory za 
				WHERE za.fixassetid = a.fixassetid 
				AND za.susutdate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'
				order by za.susutdate desc 
				limit 1
				) AS saldoawal,	
				(
				SELECT IFNULL(SUM(za.beban),0)
				FROM fahistory za 
				WHERE za.fixassetid = a.fixassetid 
				AND MONTH(za.susutdate) BETWEEN MONTH('".date('Y', strtotime($startdate))."-01-01') and MONTH('".date(Yii::app()->params['datetodb'], strtotime($startdate))."')
				AND YEAR(za.susutdate) = YEAR('2019-01-28')
				) AS nilaisusut,
				(
				SELECT IFNULL(za.nilaibuku,0)
				FROM fahistory za 
				WHERE za.fixassetid = a.fixassetid 
				AND MONTH(za.susutdate) = MONTH('".date(Yii::app()->params['datetodb'], strtotime($startdate))."') 
				AND YEAR(za.susutdate) = YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."') 
				) AS saldoakhir
				FROM fixasset a
				LEFT JOIN product b ON b.productid = a.productid
				LEFT JOIN unitofmeasure c ON c.unitofmeasureid = a.uomid
				) zz
				where zz.accperolehan = ".$row['accperolehan'];
			$this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'Kode Akun : '.$row['accountcode']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Nama Akun : '.$row['accountname']);
			$this->pdf->sety($this->pdf->gety()+15);
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(10,100,80,30,20,20,20,30,30,30,30));
			$this->pdf->colheader = array('No','Artikel','Keterangan','Quantity','Satuan','Tgl Beli','% (bln)','Harga Beli','Saldo Awal','Penyusutan','Nilai Buku');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('L','L','L','R','L','L','R','R','R','R','R');
			$this->pdf->SetFont('Arial','',8);
			$i=1;$totalbeli=0;$totalawal=0;$totalsusut=0;$totalnilaibuku=0;$saldoawal=0;$nilaibuku=0;$saldoakhir=0;
			$dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
			foreach($dataReader1 as $row1) {
				if ($row1['saldoawal'] == 0) {
					$saldoawal = $row1['nilairesidu'];
				} else {
					$saldoawal = $row1['saldoawal'];
				}
				$saldoakhir = $saldoawal - $row1['nilaisusut'];
				$totalbeli += $row1['price'];
				$totalawal += ($row1['saldoawal']!= 0)?$row1['saldoawal']:$row1['nilairesidu'];
				$totalsusut += $row1['nilaisusut'];
				$totalnilaibuku += $saldoakhir;
				$this->pdf->row(array(
					$i,$row1['productname'],
					$row1['description'],
					Yii::app()->format->formatCurrency($row1['qty']),
					$row1['uomcode'],
					date(Yii::app()->params['dateviewfromdb'], strtotime($row1['buydate'])),
					Yii::app()->format->formatCurrency($row1['ratesusut']),
					Yii::app()->format->formatCurrency($row1['price']),
					Yii::app()->format->formatCurrency(($row1['saldoawal']!= 0)?$row1['saldoawal']:$row1['nilairesidu']),
					Yii::app()->format->formatCurrency($row1['nilaisusut']),
					Yii::app()->format->formatCurrency($saldoakhir)
				));
				$i++;
			}
			$this->pdf->SetFont('Arial','B',8);
			$this->pdf->row(array(
				'','TOTAL',
				'',
				'',
				'',
				'',
				'',
				Yii::app()->format->formatCurrency($totalbeli),
				Yii::app()->format->formatCurrency($totalawal),
				Yii::app()->format->formatCurrency($totalsusut),
				Yii::app()->format->formatCurrency($totalnilaibuku)
			));
			$this->pdf->sety($this->pdf->gety()+5);
			$this->pdf->checkPageBreak(15);
		}
				
		$this->pdf->Output();
	}
	public function RekapJurnalUmumPerDokumenBelumStatusMax($companyid,$plant,$sloc,$materialgroup,$customer,$employee,$product,$account,$startdate,$enddate,$per) {
		parent::actionDownload();
		$sql = "select distinct a.genjournalid,a.journalno,a.referenceno,a.journaldate,a.journalnote,a.recordstatus
						from genjournal a
						left join journaldetail b on b.genjournalid = a.genjournalid
						where a.recordstatus between 1 and (3-1)
						and a.referenceno is not null ".
						(($plant != '')?" and a.plantid like '".$plant:'').
						" order by a.journaldate,a.journalno";
		
			$command=$this->connection->createCommand($sql);
			$dataReader=$command->queryAll();
			$this->pdf->companyid = $companyid;
			$this->pdf->title='Rekap Jurnal Umum Per Dokumen Belum Status Max';
			$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->AddPage('P','A4');
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->sety($this->pdf->gety()+10);
			$this->pdf->colalign = array('C','C','C','C','C','L','L');
			$this->pdf->setwidths(array(10,20,25,25,25,60,25,25));
			$this->pdf->colheader = array('No','ID Transaksi','No Transaksi','Tanggal','No Referensi','Keterangan','Status');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('C','C','C','C','C','L','L',);		
			$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
			foreach($dataReader as $row) {
				$i+=1;
				$this->pdf->setFont('Arial','',7);
				$this->pdf->row(array(
					$i,$row['genjournalid'],$row['journalno'],
					date(Yii::app()->params['dateviewfromdb'], strtotime($row['journaldate'])),
					$row['referenceno'],$row['journalnote'],findstatusname("apppayreq",$row['recordstatus'])
				));
				$this->pdf->checkPageBreak(20);
			}
			$this->pdf->Output();
	}
	public function RekapPenerimaanKasBankPerDokumentBelumStatusMax($companyid,$plant,$sloc,$materialgroup,$customer,$employee,$product,$account,$startdate,$enddate,$per) {
		parent::actionDownload();
		$sql = "select distinct a.cashbankinid,a.cashbankinno,a.cashbankindate,a.headernote,a.recordstatus,c.invoicearno,a.statusname
						from cashbankin a
						left join cashbankindetail b on b.cashbankinid = a.cashbankinid
						left join invoicear c on c.invoicearid = b.invoicearid
						where a.recordstatus between 1 and (3-1) ".
						(($plant != '')?" and a.plantid = ".$plant:'').
						" order by a.cashbankindate,a.cashbankinno";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->companyid = $companyid;
		$this->pdf->title='Rekap Penerimaan Kas/Bank Per Dokumen Belum Status Max';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P','A4');
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->sety($this->pdf->gety()+10);
		$this->pdf->colalign = array('C','C','C','C','C','L','L');
		$this->pdf->setwidths(array(10,20,30,25,25,60,25,25));
		$this->pdf->colheader = array('No','ID Transaksi','No Transaksi','Tanggal','No Referensi','Keterangan','Status');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('C','C','C','C','C','L','L',);		
		$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
		foreach($dataReader as $row) {
			$i+=1;
			$this->pdf->setFont('Arial','',7);
			$this->pdf->row(array(
				$i,$row['cashbankinid'],$row['cashbankinno'],
				date(Yii::app()->params['dateviewfromdb'], strtotime($row['cashbankindate'])),
				$row['invoicearno'],$row['headernote'],$row['statusname']
			));
			$this->pdf->checkPageBreak(20);
		}
		$this->pdf->Output();
	}
	public function RekapPengeluaranKasBankPerDokumentBelumStatusMax($companyid,$plant,$sloc,$materialgroup,$customer,$employee,$product,$account,$startdate,$enddate,$per) {
		parent::actionDownload();
		$sql="select a.cashbankoutid,a.cashbankoutno,a.cashbankoutdate,a.headernote,a.recordstatus,a.statusname
					from cashbankout a
					where a.recordstatus between 1 and (3-1) ".
					(($plant != '')?" and a.plantid = ".$plant:'').
					" order by a.cashbankoutdate,a.cashbankoutno";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->companyid = $companyid;
		$this->pdf->title='Rekap Pengeluaran Kas/Bank Per Dokumen Belum Status Max';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P','A4');
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->sety($this->pdf->gety()+10);
		$this->pdf->colalign = array('C','C','C','C','C','L','L');
		$this->pdf->setwidths(array(10,20,30,25,60,25));
		$this->pdf->colheader = array('No','ID Transaksi','No Transaksi','Tanggal','Keterangan','Status');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('C','C','C','C','L','L',);		
		$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
		foreach($dataReader as $row) {
			$i+=1;
			$this->pdf->setFont('Arial','',7);
			$this->pdf->row(array(
				$i,$row['cashbankoutid'],$row['cashbankoutno'],
				date(Yii::app()->params['dateviewfromdb'], strtotime($row['cashbankoutdate'])),
				$row['headernote'],$row['statusname']
			));
			$this->pdf->checkPageBreak(20);
		}
		$this->pdf->Output();
	}
	public function RekapCashBankPerDokumentBelumStatusMax($companyid,$plant,$sloc,$materialgroup,$customer,$employee,$product,$account,$startdate,$enddate,$per) {
		parent::actionDownload();
		$sql ="select distinct a.cashbankid,a.cashbankno,a.cashbankdate,a.receiptno,a.headernote,a.recordstatus,a.statusname
						from cashbank a
						where a.recordstatus between 1 and (3-1) ".
						(($plant != '')?" and a.plantid = ".$plant:'').
						" order by a.cashbankdate,a.cashbankno ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->companyid = $companyid;
		$this->pdf->title='Rekap Cash Bank Per Dokumen Belum Status Max';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P','A4');
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->sety($this->pdf->gety()+10);
		$this->pdf->colalign = array('C','C','C','C','C','L','L');
		$this->pdf->setwidths(array(10,20,30,25,80,25));
		$this->pdf->colheader = array('No','ID Transaksi','No Transaksi','Tanggal','Keterangan','Status');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('C','C','C','C','L','L',);		
		$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
		foreach($dataReader as $row) {
			$i+=1;
			$this->pdf->setFont('Arial','',7);
			$this->pdf->row(array(
				$i,$row['cashbankid'],$row['cashbankno'],
				date(Yii::app()->params['dateviewfromdb'], strtotime($row['cashbankdate'])),
				$row['headernote'],$row['statusname']
			));
			$this->pdf->checkPageBreak(20);
		}
		$this->pdf->Output();
	}
	public function LampiranNeraca1($companyid,$plant,$sloc,$materialgroup,$customer,$employee,$product,$account,$startdate,$enddate,$per) {
		parent::actionDownload();
		$totalawal1=0;$totaldebit1=0;$totalcredit1=0;
		$sql = "SELECT a.accountname, a.accountcode
						from repneraca a
						left join account b on b.accountid = a.accountid
						where a.companyid = ".$companyid." and coalesce(b.accountname,'') like '%".$account."%'
						AND LOWER(b.accountname) <> LOWER('AKTIVA LANCAR') 
						AND LOWER(b.accountname) <> LOWER('AKTIVA TETAP') 
						AND LOWER(b.accountname) <> LOWER('AKTIVA LAIN-LAIN') 
						AND LOWER(b.accountname) <> LOWER('AKTIVA') 
						AND LOWER(b.accountname) <> LOWER('KEWAJIBAN LANCAR') 
						AND LOWER(b.accountname) <> LOWER('KEWAJIBAN JANGKA PANJANG') 
						AND LOWER(b.accountname) <> LOWER('EKUITAS') 
						AND LOWER(b.accountname) <> LOWER('PASIVA') 
						AND LOWER(b.accountname) <> LOWER('PERSEDIAAN')";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->companyid = $companyid;
		$this->pdf->title='Lampiran Neraca';
		$this->pdf->subtitle = 'Dari : '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) .' - '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P','A4');
		foreach($dataReader as $row)	{
			$this->pdf->SetFont('Arial','B',10);
			$this->pdf->text(10,$this->pdf->gety()+3,'MUTASI '.$row['accountname']);
			$sql1 = "select a.accountname,a.accountcode
				from account a
				where a.recordstatus = 1 and 
				a.parentaccountid = (SELECT b.accountid FROM account b WHERE b.accountcode= '".$row['accountcode']."' AND b.companyid='".$companyid."')
				order by a.accountid";
			$command1=$this->connection->createCommand($sql1);
			$dataReader1=$command1->queryAll();
			$saldo=0;$i=0;
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->sety($this->pdf->gety()+7);
			$this->pdf->colalign = array('C','C','C','C','C','C');
			$this->pdf->setwidths(array(10,70,28,28,28,28));
			$this->pdf->colheader = array('No','Keterangan','Saldo Awal','Debit','Kredit','Saldo Akhir');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('C','L','R','R','R','R');		
			$saldo=0;$i=0;$totaldebit=0;$totalcredit=0;
			$sql2 = "SELECT SUM(b.debit-b.credit) FROM genledger b  
			WHERE     b.companyid=".$companyid." AND b.accountcode BETWEEN '".$row['accountcode']."' 
			AND concat('".$row['accountcode']."','9999999999') AND b.journaldate < '".date(Yii::app()->params['datetodb'], strtotime($startdate)) ."' ";
			$command2=$this->connection->createCommand($sql2);
			$saldoawal1=$command2->queryScalar();

			$sql3 = "SELECT SUM(b.debit) FROM genledger b  
			WHERE  b.companyid=".$companyid." AND b.accountcode BETWEEN '".$row['accountcode']."' 
			AND concat('".$row['accountcode']."','9999999999') AND b.journaldate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate)) ."' AND '".date(Yii::app()->params['datetodb'], strtotime($enddate)) ."' ";
			$command3=$this->connection->createCommand($sql3);
			$debit1=$command3->queryScalar();

			$sql4 = "SELECT SUM(b.credit) FROM genledger b  
			WHERE  b.companyid=".$companyid." AND b.accountcode BETWEEN '".$row['accountcode']."' 
			AND concat('".$row['accountcode']."','9999999999') AND b.journaldate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate)) ."' AND '".date(Yii::app()->params['datetodb'], strtotime($enddate)) ."' ";
			$command4=$this->connection->createCommand($sql4);
			$credit1=$command4->queryScalar();

			$sql5 = "SELECT SUM(b.debit-b.credit) FROM genledger b  
			WHERE     b.companyid=".$companyid." AND b.accountcode BETWEEN '".$row['accountcode']."' 
			AND concat('".$row['accountcode']."','9999999999') AND b.journaldate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate)) ."' ";
			$command5=$this->connection->createCommand($sql5);
			$saldoakhir1=$command5->queryScalar();

			$this->pdf->setFont('Arial','B',8);
			$this->pdf->row(array(
			'',$row['accountname'],
			Yii::app()->format->formatCurrency($saldoawal1/$per),
			Yii::app()->format->formatCurrency($debit1/$per),
			Yii::app()->format->formatCurrency($credit1/$per),
			Yii::app()->format->formatCurrency($saldoakhir1/$per)
			));	

			foreach($dataReader1 as $row1) 	{
				$sql6 = "SELECT SUM(b.debit-b.credit) FROM genledger b  
				WHERE     b.companyid=".$companyid." AND b.accountcode BETWEEN '".$row1['accountcode']."' AND concat('".$row1['accountcode']."','9999999999') 
				AND b.journaldate < '".date(Yii::app()->params['datetodb'], strtotime($startdate)) ."' ";
				$command6=$this->connection->createCommand($sql6);
				$saldoawal2=$command6->queryScalar();

				$sql7 = "SELECT SUM(b.debit) FROM genledger b  
				WHERE  b.companyid=".$companyid." AND b.accountcode BETWEEN '".$row1['accountcode']."' AND concat('".$row1['accountcode']."','9999999999') 
				AND b.journaldate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate)) ."' AND '".date(Yii::app()->params['datetodb'], strtotime($enddate)) ."' ";
				$command7=$this->connection->createCommand($sql7);
				$debit2=$command7->queryScalar();

				$sql8 = "SELECT SUM(b.credit) FROM genledger b  
				WHERE  b.companyid=".$companyid." AND b.accountcode BETWEEN '".$row1['accountcode']."' AND concat('".$row1['accountcode']."','9999999999') 
				AND b.journaldate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate)) ."' AND '".date(Yii::app()->params['datetodb'], strtotime($enddate)) ."' ";
				$command8=$this->connection->createCommand($sql8);
				$credit2=$command8->queryScalar();

				$sql9 = "SELECT SUM(b.debit-b.credit) FROM genledger b  
				WHERE     b.companyid=".$companyid." AND b.accountcode BETWEEN '".$row1['accountcode']."' AND concat('".$row1['accountcode']."','9999999999') 
				AND b.journaldate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate)) ."' ";
				$command9=$this->connection->createCommand($sql9);
				$saldoakhir2=$command9->queryScalar();

					$i+=1;
					$this->pdf->setFont('Arial','',8);
					$this->pdf->row(array(
					$i,$row1['accountname'],
					Yii::app()->format->formatCurrency($saldoawal2/$per),
					Yii::app()->format->formatCurrency($debit2/$per),
					Yii::app()->format->formatCurrency($credit2/$per),
					Yii::app()->format->formatCurrency($saldoakhir2/$per)
					));	    
			}
				$this->pdf->checkPageBreak(250);
		}
		$this->pdf->Output();
	}	
	public function actionDownXLS() {
    parent::actionDownload();
		if ($_GET['company'] == '') {
			echo getcatalog('emptycompany');
		} else 
		if ($_GET['lro'] == '') {
			GetMessage(true,'choosereport');
		} else 
		if ($_GET['plant'] == '') {
			GetMessage(true,'emptyplant');
		} else 
		if ($_GET['startdate'] == '') {
			GetMessage(true,'emptystartdate');
		} else 
		if ($_GET['enddate'] == '') {
			GetMessage(true,'emptyenddate');
		} else {			      
			switch ($_GET['lro']) {
				case 21:
					$this->laporankasbankharianXls($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['employee'],$_GET['product'],$_GET['account'], $_GET['startdate'],$_GET['enddate'],$_GET['per']);
					break;
				default:
					echo GetCatalog('reportdoesnotexist');
			}
		}
	}
	
	public function laporankasbankharianXls($companyid,$plant,$sloc,$materialgroup,$customer,$employee,$product,$account,$startdate,$enddate,$per) {
		$this->menuname='cashbankharian';
    parent::actionDownxls();
		$sql = "
		select *, case when (instr(cashbankno,'CBP')) then amount end as pengeluaran,
						case when (instr(cashbankno,'CBR')) then amount end as penerimaan
						from (
						select a.cashbankinno as cashbankno,c.accountcode,c.accountname,d.plantcode,e.invoicearno as invoice,f.fullname,
						b.amount,a.cashbankindate as cashbankdate, e.invoicearno as referensino, a.headernote,g.symbol
						from cashbankin a
						left join cashbankindetail b on b.cashbankinid = a.cashbankinid
						left join account c on c.accountid = a.accountid
						left join plant d on d.plantid = a.plantid
						left join invoicear e on e.invoicearid = b.invoicearid
						left join addressbook f on f.addressbookid = e.addressbookid
						left join currency g on g.currencyid = b.currencyid
						where a.recordstatus > 2 and a.plantid = ".$plant." and coalesce(f.fullname,'') like '%".$customer."%' and coalesce(c.accountname,'') like '%".$account."%'
						and a.cashbankindate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'

						union 

						select a.cashbankoutno as cashbankno ,c.accountcode,c.accountname,d.plantcode,e.invoiceapno as invoice,f.fullname,
						b.amount,a.cashbankoutdate as cashbankdate, e.invoiceapno as referensino, a.headernote,g.symbol
						from cashbankout a
						left join cashbankoutdetail b on b.cashbankoutid = a.cashbankoutid
						left join account c on c.accountid = a.accountid
						left join plant d on d.plantid = a.plantid
						left join invoiceap e on e.invoiceapid = b.invoiceapid
						left join addressbook f on f.addressbookid = e.addressbookid
						left join currency g on g.currencyid = b.currencyid
						where a.recordstatus > 2 and a.plantid = ".$plant." and coalesce(f.fullname,'') like '%".$customer."%' and coalesce(c.accountname,'') like '%".$account."%'
						and a.cashbankoutdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
						) z where cashbankno is not null order by accountname, cashbankno, cashbankdate";
						
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();	
		$i=2;$ppid = 0;$proses=0;
		foreach($dataReader as $row){
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $i-1)
				->setCellValueByColumnAndRow(1, $i+1, $row['accountname'])
				->setCellValueByColumnAndRow(2, $i+1, $row['cashbankno'])
				->setCellValueByColumnAndRow(3, $i+1, ($row['cashbankdate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['cashbankdate'])))
				->setCellValueByColumnAndRow(4, $i+1, $row['referensino'])
				->setCellValueByColumnAndRow(5, $i+1, $row['fullname'])
				->setCellValueByColumnAndRow(6, $i+1, $row['headernote'])
				->setCellValueByColumnAndRow(7, $i+1, $row['penerimaan'])
				->setCellValueByColumnAndRow(8, $i+1, $row['pengeluaran'])
			;
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}