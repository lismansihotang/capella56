<?php
class RepaccrecController extends Controller {
	public $menuname = 'repaccrec';
	public function actionIndex() {
		$this->renderPartial('index',array());
	}
	public function actionDownPDF() {
		parent::actionDownload();
		if ($_GET['company'] == '') {
			echo getcatalog('emptycompany');
		} else 
		if ($_GET['lro'] == '') {
			echo getcatalog('choosereport');
		} else 
		if ($_GET['startdate'] == '') {
			echo getcatalog('emptystartdate');
		} else 
		if ($_GET['enddate'] == '') {
			echo getcatalog('emptyenddate');
		} else {
			switch ($_GET['lro']) {
				case 1:
					$this->ARAging($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['product'],$_GET['sales'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 2:
					$this->RekapInvoiceAR($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['product'],$_GET['sales'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 3:
					$this->KartuPiutang($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['product'],$_GET['sales'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 4:
					$this->RekapPiutang($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['product'],$_GET['sales'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 5:
					$this->BKP($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['product'],$_GET['sales'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 12:
					$this->RekapinvoicearARPerDokumenBelumStatusMax($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['product'],$_GET['sales'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 13:
					$this->RekapNotaReturPenjualanPerDokumenBelumStatusMax($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['product'],$_GET['sales'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 14:
					$this->RekapPelunasanPiutangPerDokumenBelumStatusMax($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['product'],$_GET['sales'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				default:
					echo getCatalog('reportdoesnotexist');
			}
		}
	}
	public function ARAging($companyid,$plant,$sloc,$materialgroup,$customer,$product,$sales,$salesarea,$umurpiutang,$startdate,$enddate,$per) {
	  parent::actionDownload();
		$totalall = 0;$totalall1 = 0;$totalall2 = 0;$totalall3 = 0;$totalall4 = 0;$totalall5 = 0;$totalall6 = 0;$totalall7 = 0;
		$sql = " select az.*,
			case when umur > 0 and umur <= 30 then amount else 0 end as 1sd30,
			case when umur > 30 and umur <= 60 then amount else 0 end as 30sd60,
			case when umur > 60 and umur <= 90 then amount else 0 end as 60sd90,
			case when umur > 90 and umur <= 120 then amount else 0 end as 90sd120,
			case when umur > 120 then amount else 0 end as sd120
			from
			(select f.plantcode,d.fullname,a.invoicearno,a.invoiceardate,e.paycode,
			date_add(a.invoiceardate,interval e.paydays day) as dropdate,
			datediff(date_add(a.invoiceardate,interval e.paydays day),current_date()) as diffdate,
			datediff(current_date(),a.invoiceardate) as umur,
			getamountbyinvoicearumum(a.invoicearid) AS amount
			from invoicear a
			left join soheader c on c.soheaderid = a.soheaderid
			left join addressbook d on d.addressbookid = c.addressbookid
			left join paymentmethod e on e.paymentmethodid = c.paymentmethodid 
			left join plant f on f.plantid = a.plantid 
			where 
			a.invoiceardate < '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ".
			(($plant != '')?" and a.plantid = ".$plant:'').
			" and a.invoicearid not in (
			select xx.invoicearid from cashbankindetail xx) and
			a.recordstatus = 3 and e.paymentmethodid > 1) az 
			order by fullname asc,umur desc";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$this->pdf->companyid = $companyid;
		$this->pdf->isrepeat = 1;
		$this->pdf->title='AR Aging';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','A3');
		$this->pdf->setFont('Arial','',8);
		$this->pdf->sety($this->pdf->gety()+10);    
		$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(10,15,75,30,20,20,25,10,32,32,32,32,32,32,32,32,30,30));
		$this->pdf->colheader = array('No','Cabang','Customer','Faktur','Tgl Faktur','TOP','Tgl Jatuh Tempo','Umur','Nilai','1 sd 30','30 sd 60','60 sd 90','90 sd 120','> 120');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','C','R','R','R','R','R','R');
		$i=0;$totalamount=0;$total1sd30=0;$total30sd60=0;$total60sd90=0;$total90sd120=0;$totalsd120=0;
		foreach($dataReader as $row) {
			$i=$i+1;
			$this->pdf->row(array($i,$row['plantcode'],$row['fullname'],
				$row['invoicearno'],
				date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoiceardate'])),  
				$row['paycode'],				
				date(Yii::app()->params['dateviewfromdb'], strtotime($row['dropdate'])),  
				$row['umur'],				
				Yii::app()->format->formatCurrency($row['amount']/$per),
				Yii::app()->format->formatCurrency($row['1sd30']/$per),
				Yii::app()->format->formatCurrency($row['30sd60']/$per),
				Yii::app()->format->formatCurrency($row['60sd90']/$per),
				Yii::app()->format->formatCurrency($row['90sd120']/$per),
				Yii::app()->format->formatCurrency($row['sd120']/$per),
			));
			$totalamount += $row['amount'];
			$total1sd30 += $row['1sd30'];
			$total30sd60 += $row['30sd60'];
			$total60sd90 += $row['60sd90'];
			$total90sd120 += $row['90sd120'];
			$totalsd120 += $row['sd120'];
		}		
		$this->pdf->setFont('Arial','BI',8);
		$this->pdf->row(array('','','GRAND TOTAL','',
		'','','','',
			Yii::app()->format->formatCurrency($totalamount),					
			Yii::app()->format->formatCurrency($total1sd30),
			Yii::app()->format->formatCurrency($total30sd60),
			Yii::app()->format->formatCurrency($total60sd90),
			Yii::app()->format->formatCurrency($total90sd120),
			Yii::app()->format->formatCurrency($totalsd120)));
		$this->pdf->Output();
	}
	public function RekapInvoiceAR($companyid,$plant,$sloc,$materialgroup,$customer,$product,$sales,$salesarea,$umurpiutang,$startdate,$enddate,$per) {
	  parent::actionDownload();
		$totalall = 0;$totalall1 = 0;$totalall2 = 0;$totalall3 = 0;$totalall4 = 0;$totalall5 = 0;$totalall6 = 0;$totalall7 = 0;
		$sql = " SELECT *,z.total-z.dpp AS ppn
			FROM
			(
			SELECT f.plantcode,a.invoicearno,a.invoiceartaxno,c.sono,d.fullname,c.pocustno,a.invoiceardate, e.productname,b.dpp,b.total,b.qty,a.statusname
			FROM invoicear a
			LEFT JOIN invoiceardetail b ON b.invoicearid = a.invoicearid
			LEFT JOIN soheader c ON c.soheaderid = a.soheaderid
			LEFT JOIN addressbook d ON d.addressbookid = a.addressbookid
			LEFT JOIN product e ON e.productid = b.productid
			left join plant f on f.plantid = a.plantid 
			where coalesce(d.fullname,'') like '%".$customer."%'".
			(($plant != '')?" and a.plantid = ".$plant:'').
			" and coalesce(e.productname,'') like '%".$product."%' 
			and a.invoiceardate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
			) z";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$this->pdf->companyid = $companyid;
		$this->pdf->isrepeat = 1;
		$this->pdf->title='Rekap Faktur Penjualan';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','A3');
		$this->pdf->setFont('Arial','',8);
		$this->pdf->sety($this->pdf->gety()+10);    
		$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(10,20,55,25,20,30,25,70,30,30,30,30,30,30,30,30,30,30));
		$this->pdf->colheader = array('No','Cabang','Customer','Invoice','Tgl Invoice','No OS','No PO Customer','Artikel','Qty','DPP','PPn','Total','Status');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','R','R','R','R','R','R');
		$i=0;$totaldpp=0;$totalppn=0;$totalvalue=0;$total60sd90=0;$total90sd120=0;$totalsd120=0;
		foreach($dataReader as $row) {
			$i=$i+1;
			$this->pdf->row(array($i,$row['plantcode'],$row['fullname'],
				$row['invoicearno'],
				date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoiceardate'])),  
				$row['sono'],				
				$row['pocustno'],				
				$row['productname'],				
				Yii::app()->format->formatCurrency($row['qty']/$per),
				Yii::app()->format->formatCurrency($row['dpp']/$per),
				Yii::app()->format->formatCurrency($row['ppn']/$per),
				Yii::app()->format->formatCurrency($row['total']/$per),
				$row['statusname'],				
			));
			$totaldpp += $row['dpp'];
			$totalvalue += $row['total'];
			$totalppn += $row['ppn'];
		}		
		$this->pdf->setFont('Arial','BI',8);
		$this->pdf->row(array('','','GRAND TOTAL','',
		'','','','','',
			Yii::app()->format->formatCurrency($totaldpp),					
			Yii::app()->format->formatCurrency($totalppn),
			Yii::app()->format->formatCurrency($totalvalue)));
		$this->pdf->Output();
	}
	public function KartuPiutang($companyid,$plant,$sloc,$materialgroup,$customer,$product,$sales,$salesarea,$umurpiutang,$startdate,$enddate,$per) {
		parent::actionDownload();
		$penambahan1 = 0;
		$dibayar1 = 0;
		$bank1 = 0;
		$diskon1 = 0;
		$retur1 = 0;
		$ob1 = 0;
		$saldo1 = 0;
		$sql = "select distinct addressbookid,fullname,saldoawal
						from (select a.addressbookid,a.fullname,
						ifnull((select sum(ifnull(dd.total,0)-ifnull((select sum(ifnull(b.amount,0))
						from cashbankindetail b
						left join cashbankin c on c.cashbankinid=b.cashbankinid
						where c.recordstatus=3 and b.invoicearid=d.invoicearid ".
						(($plant != '')?" and c.plantid = ".$plant:'').
						" and c.cashbankindate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'),0))
						from invoicear d
						left join invoiceardetail dd on dd.invoicearid = d.invoicearid
						left join soheader f on f.soheaderid=d.soheaderid
						where d.recordstatus=3 ".
						(($plant != '')?" and d.plantid=" . $plant:''). 
						" and d.addressbookid=a.addressbookid 
						and d.invoiceardate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' group by fullname),0) as saldoawal,
						ifnull((select sum(ifnull(dd.total,0))
						from invoicear d
						left join invoiceardetail dd on dd.invoicearid = d.invoicearid
						left join soheader f on f.soheaderid=d.soheaderid
						where d.recordstatus=3 ".
						(($plant != '')?" and d.plantid=" . $plant:'').
						" and d.addressbookid=a.addressbookid 
						and d.invoiceardate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' group by fullname),0) as piutang,
						ifnull((select sum(ifnull((select sum(ifnull(b.amount,0))
						from cashbankindetail b
						left join cashbankin c on c.cashbankinid=b.cashbankinid
						where c.recordstatus=3 and b.invoicearid=d.invoicearid 
						and c.cashbankindate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' group by fullname),0))
						from invoicear d
						left join soheader f on f.soheaderid=d.soheaderid
						where d.recordstatus=3 ".
						(($plant != '')?" and d.plantid=" . $plant:'').
						" and d.addressbookid=a.addressbookid 
						and d.invoiceardate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0) as dibayar
						from addressbook a where a.fullname like '%" . $customer . "%' group by fullname) z
						where z.saldoawal<>0 or z.piutang<>0 or z.dibayar<>0
						order by fullname";
		$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($dataReader as $row) {
			$this->pdf->company = $companyid;
		}
		$this->pdf->title = 'Kartu Piutang';
		$this->pdf->subtitle = 'Dari Tgl : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');
		foreach ($dataReader as $row) {
			$this->pdf->SetFont('Arial', 'B', 10);
			$this->pdf->text(10, $this->pdf->gety() + 3, $row['fullname']);
			$this->pdf->setFont('Arial', 'B', 8);
			$this->pdf->sety($this->pdf->gety() + 5);
			$this->pdf->colalign = array('C', 'C', 'C', 'C', 'C', 'C');
			$this->pdf->setwidths(array(20, 30, 40, 35, 35, 35));
			$this->pdf->colheader = array('Tanggal', 'Dokumen', 'Keterangan', 'Debet', 'Credit', 'Saldo');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('L', 'L', 'L', 'R', 'R', 'R');
			$this->pdf->setFont('Arial', '', 8);
			$this->pdf->sety($this->pdf->gety() + 0);
			$this->pdf->row(array('Saldo Awal', '', '', '', '', Yii::app()->format->formatCurrency($row['saldoawal'] / $per)));
			$penambahan = 0;
			$dibayar = 0;
			$bank = 0;
			$diskon = 0;
			$retur = 0;
			$ob = 0;
			$sql2 = "select * from
				(select a.invoicearno as dokumen,a.invoiceardate as tanggal,ifnull(c.sono,'-') as ref,ifnull(sum(aa.total),0) as penambahan,'0' as dibayar
				from invoicear a
				left join invoiceardetail aa on aa.invoicearid = a.invoicearid
				left join soheader c on c.soheaderid=a.soheaderid
				where a.recordstatus=3 ".
				(($plant != '')?" and a.plantid=" . $plant:'').
				" and a.addressbookid=" . $row['addressbookid'] . "
				and a.invoiceardate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
				and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' group by invoicearno
					union
				select d.cashbankinno as dokumen,d.cashbankindate as tanggal,concat(ifnull(h.sono,'-'),' / ',ifnull(g.invoicearno,'-')) as ref,'0' as penambahan,c.amount as dibayar
				from cashbankindetail c
				left join cashbankin d on d.cashbankinid=c.cashbankinid
				left join invoicear g on g.invoicearid=c.invoicearid
				left join soheader h on h.soheaderid=g.soheaderid
				where d.recordstatus=3 ".
				(($plant != '')?" and d.plantid = ".$plant:'').
				" and d.cashbankindate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
				and c.invoicearid in (select b.invoicearid
				from invoicear b
				left join soheader f on f.soheaderid=b.soheaderid
				where b.recordstatus=3 ".
				(($plant != '')?" and b.plantid=" . $plant:'').
				" and b.addressbookid = " . $row['addressbookid'] . "
				and b.invoiceardate <='" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
				) z
				order by tanggal,dokumen";
			$dataReader2 = Yii::app()->db->createCommand($sql2)->queryAll();
			foreach ($dataReader2 as $row2) {
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->row(array(date(Yii::app()->params['dateviewfromdb'], strtotime($row2['tanggal'])),$row2['dokumen'], $row2['ref'], Yii::app()->format->formatCurrency($row2['penambahan'] / $per), Yii::app()->format->formatCurrency(-$row2['dibayar'] / $per), ''));
				$penambahan += $row2['penambahan'] / $per;
				$dibayar += $row2['dibayar'] / $per;
			}
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->setwidths(array(90, 35, 35, 35, 30, 30, 30, 30));
			$this->pdf->coldetailalign = array('C', 'R', 'R', 'R', 'R', 'R', 'R', 'R');
			$this->pdf->row(array('TOTAL ' . $row['fullname'], Yii::app()->format->formatCurrency($penambahan), Yii::app()->format->formatCurrency(-$dibayar), Yii::app()->format->formatCurrency($row['saldoawal'] / $per + $penambahan - $dibayar)));
			$penambahan1 += $penambahan;
			$dibayar1 += $dibayar;
			$saldo1 += $row['saldoawal'] / $per;
			$this->pdf->sety($this->pdf->gety() + 5);
			$this->pdf->checkPageBreak(5);
		}
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->setwidths(array(50, 35, 35, 35, 35, 30, 30, 30, 30));
		$this->pdf->coldetailalign = array('C', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R');
		$this->pdf->row(array('', 'Saldo Awal', 'Penambahan', 'Dibayar', 'Saldo Akhir'));
		$this->pdf->SetFont('Arial', 'BI', 8);
		$this->pdf->setwidths(array(50, 35, 35, 35, 35, 30, 30, 30, 30));
		$this->pdf->coldetailalign = array('C', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R');
		$this->pdf->row(array('GRAND TOTAL', Yii::app()->format->formatCurrency($saldo1), Yii::app()->format->formatCurrency($penambahan1), Yii::app()->format->formatCurrency(-$dibayar1), Yii::app()->format->formatCurrency($saldo1 + $penambahan1 - $dibayar1)));
		$this->pdf->Output();
	}
	public function RekapPiutang($companyid,$plant,$sloc,$materialgroup,$customer,$product,$sales,$salesarea,$umurpiutang,$startdate,$enddate,$per) {
		parent::actionDownload();
		$sql = "select *
						from (select a.fullname,
						ifnull((select sum(ifnull(dd.total,0)-ifnull((select sum(ifnull(b.amount,0))
						from cashbankindetail b
						left join cashbankin c on c.cashbankinid=b.cashbankinid
						where c.recordstatus=3 and b.invoicearid=d.invoicearid ".
						(($plant != '')?" and c.plantid = ".$plant:'').
						" and c.cashbankindate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'),0))
						from invoicear d
						left join invoiceardetail dd on dd.invoicearid = d.invoicearid
						left join soheader f on f.soheaderid=d.soheaderid
						where d.recordstatus=3 ". 
						(($plant != '')?" and d.plantid=" . $plant:''). 
						" and d.addressbookid=a.addressbookid 
						and d.invoiceardate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'),0) as saldoawal,
						ifnull((select sum(ifnull(dd.total,0))
						from invoicear d
						left join invoiceardetail dd on dd.invoicearid = d.invoicearid
						left join soheader f on f.soheaderid=d.soheaderid
						where d.recordstatus=3 ".
						(($plant != '')?" and d.plantid=" . $plant:'').
						" and d.addressbookid=a.addressbookid 
						and d.invoiceardate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0) as hutang,
						ifnull((select sum(ifnull((select sum(ifnull(b.amount,0))
						from cashbankindetail b
						left join cashbankin c on c.cashbankinid=b.cashbankinid
						where c.recordstatus=3 and b.invoicearid=d.invoicearid 
						and c.cashbankindate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0))
						from invoicear d
						left join soheader f on f.soheaderid=d.soheaderid
						where d.recordstatus=3 ".
						(($plant != '')?" and d.plantid=" . $plant:'').
						" and d.addressbookid=a.addressbookid 
						and d.invoiceardate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0) as dibayar
						from addressbook a where a.fullname like '%" . $customer . "%' group by fullname) z
						where z.saldoawal<>0 or z.hutang<>0 or z.dibayar<>0
						order by fullname";
		$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
		$this->pdf->title = 'Rekap Piutang Per Pelanggan';
		$this->pdf->subtitle = 'Dari Tgl : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');
		$this->pdf->setFont('Arial', 'B', 8);
		$this->pdf->sety($this->pdf->gety() + 0);
		$this->pdf->colalign = array('C', 'C', 'C', 'C', 'C', 'C');
		$this->pdf->setwidths(array(10, 50, 30, 30, 30, 40));
		$this->pdf->colheader = array('No', 'Pelanggan', 'Saldo Awal', 'Debit', 'Credit', 'Saldo Akhir');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('L', 'L', 'R', 'R', 'R', 'R');
		$i = 0;
		$saldoawal = 0;
		$hutang = 0;
		$dibayar = 0;
		$saldoakhir = 0;
		foreach ($dataReader as $row) {
			$i += 1;
			$this->pdf->setFont('Arial', '', 7);
			$this->pdf->row(array($i, $row['fullname'], Yii::app()->format->formatCurrency($row['saldoawal'] / $per), Yii::app()->format->formatCurrency($row['dibayar'] / $per),Yii::app()->format->formatCurrency($row['hutang'] / $per),  Yii::app()->format->formatCurrency(($row['saldoawal'] + $row['hutang'] - $row['dibayar']) / $per)));
			$saldoawal += $row['saldoawal'] / $per;
			$hutang += $row['hutang'] / $per;
			$dibayar += $row['dibayar'] / $per;
			$saldoakhir += ($row['saldoawal'] + $row['hutang'] - $row['dibayar']) / $per;
		}
		$this->pdf->setFont('Arial', 'BI', 9);
		$this->pdf->row(array('', 'TOTAL', Yii::app()->format->formatCurrency($saldoawal), Yii::app()->format->formatCurrency($dibayar),Yii::app()->format->formatCurrency($hutang),  Yii::app()->format->formatCurrency($saldoakhir)));
		$this->pdf->Output();
	}
	public function BKP($companyid,$plant,$sloc,$materialgroup,$customer,$product,$sales,$salesarea,$umurpiutang,$startdate,$enddate,$per) {
		parent::actionDownload();
		$sql = "SELECT a.invoicearno,a.invoiceartaxno,a.invoiceardate,concat(b.paydays,' HARI') AS paydays,
		DATE_ADD(a.invoiceardate,INTERVAL b.paydays DAY) AS tgljatuhtempo, c.fullname, 
		a.addressname AS alamattagih, 
		case when d.soheaderid IS NULL then a.addressname ELSE e.addressname END AS alamatkirim,
		c.taxno, d.pocustno, f.gino,h.productcode,h.productname,x.qty,i.uomcode,j.symbol,x.price,x.discount,x.dpp,x.total, 
		x.qty2,d.sono,c.bankname,c.bankaccountno
		FROM invoicear a
		LEFT JOIN invoicearsj y ON y.invoicearid = y.invoicearid 
		LEFT JOIN invoiceardetail x ON x.invoicearid = y.invoicearid 
		LEFT JOIN paymentmethod b ON b.paymentmethodid = a.paymentmethodid
		LEFT JOIN addressbook c ON c.addressbookid = a.addressbookid
		LEFT JOIN soheader d ON d.soheaderid = a.soheaderid 
		LEFT JOIN address e ON e.addressid = d.addresstoid 
		LEFT JOIN giheader f ON f.giheaderid = y.giheaderid 
		LEFT JOIN product h ON h.productid = x.productid 
		LEFT JOIN unitofmeasure i ON i.unitofmeasureid = x.uomid
		left join currency j on j.currencyid = x.currencyid 
		WHERE invoiceardate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
		and a.companyid = ".$companyid	
		.(($plant != '')?" and a.plantid = ".$plant:'').
		" order by a.invoiceardate asc";
		$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
		$this->pdf->title = 'BKP';
		$this->pdf->subtitle = 'Dari Tgl : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','A2');
		$this->pdf->setFont('Arial', 'B', 8);
		$this->pdf->sety($this->pdf->gety() + 0);
		$this->pdf->colalign = array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C');
		$this->pdf->setwidths(array(10, 25, 30, 20, 20, 20, 30, 60, 60, 30, 25, 25, 25, 25, 25, 25, 35, 35, 35, 35));
		$this->pdf->colheader = array('No', 'No Invoice', 'No Seri FP', 'Tgl Invoice', 'Lama Bayar', 'Tgl Jt Tempo','Nama Pelanggan','Alamat Tagih','Alamat Kirim','NPWP','No PO','No SJ','Kode Rekening','Nama Barang','Qty','Harga Satuan');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L', 'L', 'L', 'L', 'L', 'L', 'L', 'L', 'L', 'L', 'L', 'R', 'R', 'R', 'R');
		$i = 0;
		foreach ($dataReader as $row) {
			$i += 1;
			$this->pdf->setFont('Arial', '', 7);
			$this->pdf->row(array($i, $row['invoicearno'],$row['invoiceartaxno'], date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoiceardate'])),
				$row['paydays'],date(Yii::app()->params['dateviewfromdb'], strtotime($row['tgljatuhtempo'])), $row['fullname'], $row['alamattagih'], $row['alamatkirim'], 
				$row['taxno'], $row['pocustno'], $row['gino'], $row['productcode'], $row['productname'], Yii::app()->format->formatNumber($row['qty']).' '.$row['uomcode'],
				Yii::app()->format->formatCurrency($row['price'],$row['symbol'])));
		}
		$this->pdf->setFont('Arial', 'BI', 9);
		$this->pdf->Output();
	}
	public function RekapinvoicearARPerDokumenBelumStatusMax($companyid,$plant,$sloc,$materialgroup,$customer,$product,$sales,$salesarea,$umurpiutang,$startdate,$enddate,$per) {
		parent::actionDownload();
			$sql ="SELECT a.invoicearid,a.invoicearno,a.invoiceardate,b.sono,b.pocustno,a.headernote,b.sodate,a.statusname
				FROM invoicear a
				LEFT JOIN soheader b ON b.soheaderid = a.soheaderid 
				LEFT JOIN addressbook c ON c.addressbookid = b.addressbookid
				where a.recordstatus between 1 and (3-1) ".
				(($plant != '')?" and a.plantid = ".$plant:'').
				" order by a.invoicearid asc";
		
			$dataReader=Yii::app()->db->createCommand($sql)->queryAll();			
			$this->pdf->companyid = $companyid;
			$this->pdf->title='Rekap Invoicear AR Per Dokumen Belum Status Max';
			$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->AddPage('P');
			
			$this->pdf->setFont('Arial','B',8);
      $this->pdf->sety($this->pdf->gety()+10);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(10,20,25,25,30,30,30,25,25));
			$this->pdf->colheader = array('No','ID Transaksi','No Transaksi','Tanggal','No Referensi','No PO Cust','Keterangan','Status');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L',);		
			$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
			foreach($dataReader as $row)
			{
				$i+=1;
				$this->pdf->setFont('Arial','',7);
				$this->pdf->row(array(
					$i,$row['invoicearid'],$row['invoicearno'],
					date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoiceardate'])),
					$row['sono'],$row['pocustno'],$row['headernote'],$row['statusname']
				));
               
				$this->pdf->checkPageBreak(20);
			}
			
			$this->pdf->Output();
	}
	public function RekapNotaReturPenjualanPerDokumenBelumStatusMax($companyid,$plant,$sloc,$materialgroup,$customer,$product,$sales,$salesarea,$umurpiutang,$startdate,$enddate,$per)	{
		parent::actionDownload();
			$sql ="select distinct a.notagirid, a.notagirno, a.notagirdate, b.gireturno, a.headernote, a.statusname
				from notagir a
				left join giretur b on b.gireturid = a.gireturid
				where a.recordstatus between 1 and (3-1) ".
				(($plant != '')?" and a.plantid = ".$plant:'').
				" order by a.recordstatus";
			$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
			$this->pdf->companyid = $companyid;
			$this->pdf->title='Rekap Nota Retur Penjualan Per Dokumen Belum Status Max';
			$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->AddPage('P');
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
					$i,$row['notagirid'],$row['notagirno'],
					date(Yii::app()->params['dateviewfromdb'], strtotime($row['cashbankindate'])),
					$row['gireturno'],$row['headernote'],$row['statusname']
				));
				$this->pdf->checkPageBreak(20);
			}
			$this->pdf->Output();
	}
	public function RekapPelunasanPiutangPerDokumenBelumStatusMax($companyid,$plant,$sloc,$materialgroup,$customer,$product,$sales,$salesarea,$umurpiutang,$startdate,$enddate,$per) {
		parent::actionDownload();
		$sql ="SELECT a.cashbankinid,a.cashbankinno,a.cashbankindate,a.headernote,a.statusname
			FROM cashbankin a
			where a.recordstatus between 1 and (3-1) ".
			(($plant != '')?" and a.plantid = ".$plant:'').
			" order by a.recordstatus";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$this->pdf->companyid = $companyid;
		$this->pdf->title='Rekap Pelunasan Piutang Per Dokumen Belum Status Max';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->sety($this->pdf->gety()+10);
		$this->pdf->colalign = array('C','C','C','C','C','L','L');
		$this->pdf->setwidths(array(10,20,25,25,80,25,25));
		$this->pdf->colheader = array('No','ID Transaksi','No Transaksi','Tanggal','Keterangan','Status');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('C','C','C','C','C','L','L',);		
		$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
		foreach($dataReader as $row) {
			$i+=1;
			$this->pdf->setFont('Arial','',7);
			$this->pdf->row(array(
				$i,$row['cashbankinid'],$row['cashbankinno'],
				date(Yii::app()->params['dateviewfromdb'], strtotime($row['cashbankindate'])),
				$row['headernote'],$row['statusname']
			));
			$this->pdf->checkPageBreak(20);
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
		if ($_GET['startdate'] == '') {
			GetMessage(true,'emptystartdate');
		} else 
		if ($_GET['enddate'] == '') {
			GetMessage(true,'emptyenddate');
		} else {			      
			switch ($_GET['lro']) {
				case 2:
					$this->RekapInvoiceARXls($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['product'],$_GET['sales'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);				
					break;
				case 5:
					$this->BKPXls($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['product'],$_GET['sales'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);				
					break;
				default:
					echo GetCatalog('reportdoesnotexist');
			}
		}
	}
	public function RekapInvoiceARXls($companyid,$plant,$sloc,$materialgroup,$customer,$product,$sales,$salesarea,$umurpiutang,$startdate,$enddate,$per) {
		$this->menuname='rekapinvoicear';
	  parent::actionDownXls();
		$sql = " SELECT *,z.total-z.dpp AS ppn
			FROM
			(
			SELECT f.plantcode,a.invoicearno,a.invoiceartaxno,c.sono,d.fullname,c.pocustno,a.invoiceardate, e.productname,b.dpp,b.total,b.qty,a.statusname
			FROM invoicear a
			LEFT JOIN invoiceardetail b ON b.invoicearid = a.invoicearid
			LEFT JOIN soheader c ON c.soheaderid = a.soheaderid
			LEFT JOIN addressbook d ON d.addressbookid = a.addressbookid
			LEFT JOIN product e ON e.productid = b.productid
			left join plant f on f.plantid = a.plantid 
			where coalesce(d.fullname,'') like '%".$customer."%'".
			(($plant != '')?" and a.plantid = ".$plant:'').
			" and coalesce(e.productname,'') like '%".$product."%' 
			and a.invoiceardate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
			) z";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;
		foreach($dataReader as $row) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $i-1)
				->setCellValueByColumnAndRow(1, $i+1, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $i+1, $row['fullname'])
				->setCellValueByColumnAndRow(3, $i+1, $row['invoicearno'])
				->setCellValueByColumnAndRow(4, $i+1, date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoiceardate'])))
				->setCellValueByColumnAndRow(5, $i+1, $row['sono'])
				->setCellValueByColumnAndRow(6, $i+1, $row['pocustno'])
				->setCellValueByColumnAndRow(7, $i+1, $row['productname'])
				->setCellValueByColumnAndRow(8, $i+1, Yii::app()->format->formatCurrency($row['qty']))
				->setCellValueByColumnAndRow(9, $i+1, Yii::app()->format->formatCurrency($row['dpp']))
				->setCellValueByColumnAndRow(10, $i+1, Yii::app()->format->formatCurrency($row['ppn']))
				->setCellValueByColumnAndRow(11, $i+1, Yii::app()->format->formatCurrency($row['total']))
			;
			$i++;
		}		
		$this->getFooterXLS($this->phpExcel);
	}
	public function BKPXls($companyid,$plant,$sloc,$materialgroup,$customer,$product,$sales,$salesarea,$umurpiutang,$startdate,$enddate,$per) {
		$this->menuname='bkp';
	  parent::actionDownXls();
		$sql = "SELECT a.invoicearno,a.invoiceartaxno,a.invoiceardate,concat(b.paydays,' HARI') AS paydays,
		DATE_ADD(a.invoiceardate,INTERVAL b.paydays DAY) AS tgljatuhtempo, c.fullname, 
		a.addressname AS alamattagih, 
		case when d.soheaderid IS NULL then a.addressname ELSE e.addressname END AS alamatkirim,
		c.taxno, d.pocustno, f.gino,h.productcode,h.productname,x.qty,i.uomcode,j.symbol,x.price,x.discount,x.dpp,x.total, 
		x.qty2,d.sono,c.bankname,c.bankaccountno
		FROM invoicear a
		LEFT JOIN invoicearsj y ON y.invoicearid = y.invoicearid 
		LEFT JOIN invoiceardetail x ON x.invoicearid = y.invoicearid 
		LEFT JOIN paymentmethod b ON b.paymentmethodid = a.paymentmethodid
		LEFT JOIN addressbook c ON c.addressbookid = a.addressbookid
		LEFT JOIN soheader d ON d.soheaderid = a.soheaderid 
		LEFT JOIN address e ON e.addressid = d.addresstoid 
		LEFT JOIN giheader f ON f.giheaderid = y.giheaderid 
		LEFT JOIN product h ON h.productid = x.productid 
		LEFT JOIN unitofmeasure i ON i.unitofmeasureid = x.uomid
		left join currency j on j.currencyid = x.currencyid 
		WHERE invoiceardate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
		and a.companyid = ".$companyid	
		.(($plant != '')?" and a.plantid = ".$plant:'').
		" order by a.invoiceardate asc";
		$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;
		foreach($dataReader as $row) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $i-1)
				->setCellValueByColumnAndRow(1, $i+1, $row['invoicearno'])
				->setCellValueByColumnAndRow(2, $i+1, $row['invoiceartaxno'])
				->setCellValueByColumnAndRow(3, $i+1, date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoiceardate'])))
				->setCellValueByColumnAndRow(4, $i+1, $row['paydays'])
				->setCellValueByColumnAndRow(5, $i+1, date(Yii::app()->params['dateviewfromdb'], strtotime($row['tgljatuhtempo'])))
				->setCellValueByColumnAndRow(6, $i+1, $row['fullname'])
				->setCellValueByColumnAndRow(7, $i+1, $row['alamattagih'])
				->setCellValueByColumnAndRow(8, $i+1, $row['alamatkirim'])
				->setCellValueByColumnAndRow(9, $i+1, $row['taxno'])
				->setCellValueByColumnAndRow(10, $i+1, $row['pocustno'])
				->setCellValueByColumnAndRow(11, $i+1, $row['gino'])
				->setCellValueByColumnAndRow(12, $i+1, $row['productcode'])
				->setCellValueByColumnAndRow(13, $i+1, '')
				->setCellValueByColumnAndRow(14, $i+1, $row['productname'])
				->setCellValueByColumnAndRow(15, $i+1, $row['qty'])
				->setCellValueByColumnAndRow(16, $i+1, $row['uomcode'])
				->setCellValueByColumnAndRow(17, $i+1, $row['price'])
				->setCellValueByColumnAndRow(18, $i+1, $row['qty']*$row['price'])
				->setCellValueByColumnAndRow(19, $i+1, $row['discount'])
				->setCellValueByColumnAndRow(20, $i+1, '')
				->setCellValueByColumnAndRow(21, $i+1, '')
				->setCellValueByColumnAndRow(22, $i+1, $row['dpp'])
				->setCellValueByColumnAndRow(23, $i+1, $row['total']-$row['dpp'])
				->setCellValueByColumnAndRow(24, $i+1, '')
				->setCellValueByColumnAndRow(25, $i+1, $row['total'])
				->setCellValueByColumnAndRow(26, $i+1, '')
				->setCellValueByColumnAndRow(27, $i+1, '')
				->setCellValueByColumnAndRow(28, $i+1, $row['qty2'])
				->setCellValueByColumnAndRow(29, $i+1, $row['sono'])
				->setCellValueByColumnAndRow(30, $i+1, $row['bankname'])
				->setCellValueByColumnAndRow(31, $i+1, $row['bankaccountno'])
			;
			$i++;
		}		
		$this->getFooterXLS($this->phpExcel);
	}
}