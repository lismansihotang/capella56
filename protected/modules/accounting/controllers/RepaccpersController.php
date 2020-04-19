<?php
class RepaccpersController extends Controller {
	public $menuname = 'repaccpers';
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
					$this->RekapPersediaanBarangDetail($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 2:
					$this->RekapPersediaanBarangHeader($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 3:
					$this->RekapPengeluaranPersediaanBarangDetail($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 4:
					$this->HPP($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 5:
					$this->HppBillOfMaterial($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 6:
					$this->RincianNilaiPemakaianStok($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 7:
					$this->RekapNilaiPemakaianStok($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 8:
					$this->RincianNilaiStockOpname($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 9:
					$this->RekapNilaiStockOpname($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 10:
					$this->RincianHargaPokokPenjualan($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 11:
					$this->RekapHargaPokokPenjualan($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 12:
					$this->RekapPersediaanBarangDetailDataHarga($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 13:
					$this->RekapPerbandinganHPPPenjualanPerDokumen($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 14:
					$this->RekapPerbandinganHPPReturPenjualanPerDokumen($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 15:
					$this->RekapPerbandinganHPPPenjualanPerBarang($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 16:
					$this->RekapPersediaanBarangNotMoving($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 17:
					$this->RekapPersediaanBarangSlowMoving($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 18:
					$this->RekapPersediaanBarangFastMoving($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 19:
					$this->KartuStockBarang($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				case 20:
					$this->rekapok($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
				break;
				default:
					echo getCatalog('reportdoesnotexist');
			}
		}
	}
	//20
	public function rekapok($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per) {
		parent::actionDownload();
		$sql = "SELECT distinct a.productplanno,a.productplandate,e.fullname,c.productname,d.sono,b.qty,k.plantcode,a.description,d.pocustno,
			ifnull((select xx.averageprice
			from productstockdet xx
			where xx.productid = b.productid and xx.uomid = b.uomid 
			and xx.productoutputfgid = f.productoutputfgid and xx.slocid = b.sloctoid and xx.storagebinid = f.storagebinid limit 1),0) as harga
			FROM productplan a
			LEFT JOIN productplanfg b ON b.productplanid = a.productplanid
			LEFT JOIN product c ON c.productid = b.productid
			LEFT JOIN addressbook e ON e.addressbookid = a.addressbookid
			LEFT JOIN productoutputfg f ON f.productplanfgid = b.productplanfgid AND f.productid = b.productid
			left join sloc j on j.slocid = b.sloctoid 
			left join plant k on k.plantid = a.plantid 
			left join soheader d on d.soheaderid = a.soheaderid
			WHERE a.productplandate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' ".
			(($plantid != '')?" and a.plantid = ".$plantid:'').
			" and coalesce(j.sloccode,'') like '%".$sloc."%' 
			and coalesce(c.productname,'') like '%".$product."%'
			and a.recordstatus >= 3
			ORDER BY a.productplandate asc,a.productplanno asc";
		
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->companyid = $companyid;
		$price = getUserObjectValues($menuobject='currency');
		$this->pdf->title    = 'Laporan Rekap OK (Nilai)';
		$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->text(10, $this->pdf->gety() + 10, 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
		$this->pdf->AddPage('L','A3');
		$this->pdf->sety($this->pdf->gety() + 5);
		$this->pdf->SetFont('Arial','',8);
		if($price==1)
     {
		$this->pdf->colalign = array(
			'C',
			'C',
			'C',
			'C',
			'C',
			'C',
			'C',
			'C',
			'C',
			'C',
			'C'
		);
		$this->pdf->setwidths(array(
		
			12,
			28,
			28,
			28,
			25,
			50,
			80,
			30,
			30,
			35,
			50
		
		));
		$this->pdf->colheader = array(
			'Plant',
			'No OK',
			'Tgl OK',
			'NO OS',
			'NO PO Customer',
			'Customer',
			'Artikel',
			'Qty',
			'Harga',
			'Total',
			'Keterangan'
		);
		$this->pdf->RowHeader();        
		$i=1;
		$this->pdf->coldetailalign = array(
		
			'L',
			'L',
			'L',
			'L',
			'L',
			'L',
			'L',
			'R',
			'R',
			'R',
			'L'
		);
		foreach($dataReader as $row){
			$this->pdf->row(array(
				
				$row['plantcode'],
				$row['productplanno'],
				date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])),
				$row['sono'],
				$row['pocustno'],
				$row['fullname'],
				$row['productname'],
				Yii::app()->format->formatCurrency($row['qty']),
				Yii::app()->format->formatCurrency($row['harga']),
				Yii::app()->format->formatCurrency($row['qty']*$row['harga']),
				$row['description']
			));
			$i++;
		}
		 }
		 else {
			 $this->pdf->colalign = array(
			'C',
			'C',
			'C',
			'C',
			'C',
			'C',
			'C',
			'C',
		
			'C'
		);
		$this->pdf->setwidths(array(
		
			12,
			28,
			25,
			25,
			25,
			50,
			80,
			30,
		
			50
		
		));
		$this->pdf->colheader = array(
			'Plant',
			'No OK',
			'Tgl OK',
			'NO OS',
			'NO PO Customer',
			'Customer',
			'Artikel',
			'Qty',
			
			'Keterangan'
		);
		$this->pdf->RowHeader();        
		$i=1;
		$this->pdf->coldetailalign = array(
		
			'L',
			'L',
			'L',
			'L',
			'L',
			'L',
			'L',
			'R',
			
			'L'
		);
		foreach($dataReader as $row){
			$this->pdf->row(array(
				
				$row['plantcode'],
				$row['productplanno'],
				date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])),
				$row['sono'],
				$row['pocustno'],
				$row['fullname'],
				$row['productname'],
				Yii::app()->format->formatCurrency($row['qty']),
				
				$row['description']
			));
			$i++;
		}
		 }
		
		$this->pdf->Output();
	}
	
	//1
	public function RekapPersediaanBarangDetail($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per) {
		parent::actionDownload();
		$qtyawal2=0;$nilaiawal2=0;$qtymasuk2=0;$nilaimasuk2=0;$qtytersedia2=0;$nilaitersedia2=0;$qtykeluar2=0;$nilaikeluar2=0;$qtyakhir2=0;$nilaiakhir2=0;
		$sql="select distinct slocid,sloccode,materialgroupid,description,accountname
          from (select k.productname,m.uomcode,l.slocid,l.sloccode,o.materialgroupid,o.description,q.accountname,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and a.productid = j.productid and a.slocid = j.slocid and a.uomid = j.uom1),0) as qtyawal,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty > 0 and a.productid = j.productid and a.slocid = j.slocid and a.uomid = j.uom1),0) as qtymasuk,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty < 0 and a.productid = j.productid and a.slocid = j.slocid and a.uomid = j.uom1
          group by a.productid,a.slocid,a.uomid),0) as qtykeluar,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.productid = j.productid and a.slocid = j.slocid and a.uomid = j.uom1),0) as qtyakhir
          from productplant j
          join product k on k.productid=j.productid
          join sloc l on l.slocid=j.slocid
          join unitofmeasure m on m.unitofmeasureid=j.uom1
          join plant n on n.plantid=l.plantid
          join materialgroup o on o.materialgroupid=j.materialgroupid
					left join slocaccounting p on p.slocid=j.slocid and p.materialgroupid=j.materialgroupid
					left join account q on q.accountid=p.accpersediaan
          where k.isstock=1 ".
					(($plantid != '')?" and n.plantid = ".$plantid:'').
					" and coalesce(l.sloccode,'') like '%".$sloc."%' and coalesce(o.materialgroupcode,'') like '%".$materialgroup."%' and k.productname like '%".$product."%' and q.accountname like '%".$account."%') zz
          where qtyawal <> 0 or qtymasuk <> 0 or qtykeluar <> 0 or qtyakhir <> 0 
					order by sloccode, description";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->companyid = $companyid;
		$this->pdf->title='Rekap Persediaan Barang (Grup Transaksi)';
		$this->pdf->subtitle='Dari Tgl : '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','A3');
		foreach ($dataReader as $row) {
			$this->pdf->setFont('Arial','',10);
			$this->pdf->text(15,$this->pdf->gety()+5,'GUDANG');$this->pdf->text(35,$this->pdf->gety()+5,': '.$row['sloccode']);
			$this->pdf->text(80,$this->pdf->gety()+5,'MATERIAL GROUP');$this->pdf->text(115,$this->pdf->gety()+5,': '.$row['description']);
			$this->pdf->text(180,$this->pdf->gety()+5,'NAMA AKUN');$this->pdf->text(205,$this->pdf->gety()+5,': '.$row['accountname']);
			if ($storagebin == null)
			{$this->pdf->text(100,$this->pdf->gety()+5,'');$this->pdf->text(115,$this->pdf->gety()+5,'');}
			else
			{$this->pdf->text(100,$this->pdf->gety()+5,'RAK');$this->pdf->text(115,$this->pdf->gety()+5,': '.$storagebin);}
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->sety($this->pdf->gety()+7);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(6,25,100,13,61,38,61,38,38,5));
			$this->pdf->colheader = array('','','','','Awal','Penerimaan','Tersedia','Pengeluaran','Akhir','');
			$this->pdf->RowHeader();
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(6,25,100,13,15,20,23,15,23,15,23,23,15,23,15,23,5));
			$this->pdf->colheader = array('No','Kode Rekening','Artikel','Satuan','Qty','Harga','Nilai','Qty','Nilai','Qty','Harga','Nilai','Qty','Nilai','Qty','Nilai','');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('R','L','L','C','R','R','R','R','R','R','R','R','R','R','R','R','C');
			
			$i=0;$qtyawal=0;$nilaiawal=0;$qtymasuk=0;$nilaimasuk=0;$qtytersedia=0;$nilaitersedia=0;$qtykeluar=0;$nilaikeluar=0;$qtyakhir=0;$nilaiakhir=0;
			$sql1="
				select *,(hargatersedia * qtykeluar) as jumlahkeluar, (hargatersedia * qtyakhir) as jumlahakhir
				from 
				(
					select *, case qtyawal when 0 then 0 else ifnull(jumlahawal/qtyawal,0) end as hargaawal, 
					qtyawal+qtymasuk as qtytersedia, ifnull((jumlahawal+jumlahmasuk)/(qtyawal+qtymasuk),0) as hargatersedia, 
					jumlahawal+jumlahmasuk as jumlahtersedia,
          case when qtyakhir < 0 then 'X' else '' end as minus
          from (select k.productcode,k.productname,m.uomcode,l.sloccode,o.description,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and a.productid = j.productid and a.slocid = j.slocid and a.uomid = j.uom1),0) as qtyawal,
          ifnull((select sum(a.qty*abs(a.averageprice)*a.currencyrate)
          from productstockdet a
          where a.buydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and a.productid = j.productid and a.slocid = j.slocid and a.uomid = j.uom1),0) as jumlahawal,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty > 0 and a.productid = j.productid and a.slocid = j.slocid and a.uomid = j.uom1),0) as qtymasuk,
          ifnull((select sum(a.qty*abs(a.averageprice)*a.currencyrate)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty > 0 and a.productid = j.productid and a.slocid = j.slocid and a.uomid = j.uom1),0) as jumlahmasuk,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty < 0 and a.productid = j.productid and a.slocid = j.slocid and a.uomid = j.uom1
          group by a.productid,a.slocid,a.uomid),0) as qtykeluar,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.productid = j.productid and a.slocid = j.slocid and a.uomid = j.uom1),0) as qtyakhir
          from productplant j
          join product k on k.productid=j.productid
          join sloc l on l.slocid=j.slocid
          join unitofmeasure m on m.unitofmeasureid=j.uom1
          join plant n on n.plantid=l.plantid
          join materialgroup o on o.materialgroupid=j.materialgroupid
          where k.isstock=1 ".
					(($plantid != '')?" and n.plantid = ".$plantid:'').
					" and l.sloccode like '%".$sloc."%' and o.materialgroupcode like '%".$materialgroup."%' and k.productname like '%".$product."%' and l.slocid = ".$row['slocid']." and o.materialgroupid = ".$row['materialgroupid'].") zz
          where qtyawal <> 0 or jumlahawal <> 0 or qtymasuk <> 0 or jumlahmasuk <> 0 or qtykeluar <> 0 or qtyakhir <> 0 
          order by productname
			) zz";
			$command1=$this->connection->createCommand($sql1);
			$dataReader1=$command1->queryAll();
			
			foreach ($dataReader1 as $row1)
			{
				$i+=1;
				$this->pdf->setFont('Arial','',6.5);
				$this->pdf->row(array(
					$i,$row1['productcode'],$row1['productname'],$row1['uomcode'],
					Yii::app()->format->formatNumber($row1['qtyawal']),
					Yii::app()->format->formatNumber($row1['hargaawal']/$per),
					Yii::app()->format->formatNumber(($row1['jumlahawal']/$per)),
					Yii::app()->format->formatNumber($row1['qtymasuk']),
					Yii::app()->format->formatNumber($row1['jumlahmasuk']/$per),
					Yii::app()->format->formatNumber($row1['qtytersedia']),
					Yii::app()->format->formatNumber($row1['hargatersedia']/$per),
					Yii::app()->format->formatNumber($row1['jumlahtersedia']/$per),
					Yii::app()->format->formatNumber($row1['qtykeluar']),
					Yii::app()->format->formatNumber($row1['jumlahkeluar']/$per),
					Yii::app()->format->formatNumber($row1['qtyakhir']),
					Yii::app()->format->formatNumber($row1['jumlahakhir']/$per),
					$row1['minus'],
				));
				$qtyawal += $row1['qtyawal'];
				$nilaiawal += $row1['jumlahawal']/$per;
				$qtymasuk += $row1['qtymasuk'];
				$nilaimasuk += $row1['jumlahmasuk']/$per;
				$qtytersedia += $row1['qtytersedia'];
				$nilaitersedia += $row1['jumlahtersedia']/$per;
				$qtykeluar += $row1['qtykeluar'];
				$nilaikeluar += $row1['jumlahkeluar']/$per;
				$qtyakhir += $row1['qtyakhir'];
				$nilaiakhir += $row1['jumlahakhir']/$per;
			}
			$this->pdf->setFont('Arial','B',6.5);
			$this->pdf->setwidths(array(6,25,100,13,15,20,23,15,23,15,23,23,15,23,15,23,5));
			$this->pdf->coldetailalign = array('R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','C');
			$this->pdf->row(array(
				'','','TOTAL '.$row['sloccode'].' - '.$row['description'].'      >>>>>','',
				Yii::app()->format->formatNumber($qtyawal),'',
				Yii::app()->format->formatNumber($nilaiawal),
				Yii::app()->format->formatNumber($qtymasuk),
				Yii::app()->format->formatNumber($nilaimasuk),
				Yii::app()->format->formatNumber($qtytersedia),'',
				Yii::app()->format->formatNumber($nilaitersedia),
				Yii::app()->format->formatNumber($qtykeluar),
				Yii::app()->format->formatNumber($nilaikeluar),
				Yii::app()->format->formatNumber($qtyakhir),
				Yii::app()->format->formatNumber($nilaiakhir)
			));
			$qtyawal2 += $qtyawal;
			$nilaiawal2 += $nilaiawal;
			$qtymasuk2 += $qtymasuk;
			$nilaimasuk2 += $nilaimasuk;
			$qtytersedia2 += $qtytersedia;
			$nilaitersedia2 += $nilaitersedia;
			$qtykeluar2 += $qtykeluar;
			$nilaikeluar2 += $nilaikeluar;
			$qtyakhir2 += $qtyakhir;
			$nilaiakhir2 += $nilaiakhir;
			
			$this->pdf->sety($this->pdf->gety()+5);
			$this->pdf->checkNewPage(175);
		}
		$this->pdf->row(array(
			'','','GRAND TOTAL      >>>>>','',
			Yii::app()->format->formatNumber($qtyawal2),'',
			Yii::app()->format->formatNumber($nilaiawal2),
			Yii::app()->format->formatNumber($qtymasuk2),
			Yii::app()->format->formatNumber($nilaimasuk2),
			Yii::app()->format->formatNumber($qtytersedia2),'',
			Yii::app()->format->formatNumber($nilaitersedia2),
			Yii::app()->format->formatNumber($qtykeluar2),
			Yii::app()->format->formatNumber($nilaikeluar2),
			Yii::app()->format->formatNumber($qtyakhir2),
			Yii::app()->format->formatNumber($nilaiakhir2),'',
		));
		
		$this->pdf->Output();
	}
	//2
	public function RekapPersediaanBarangHeader($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$sql="SELECT DISTINCT b.slocid, b.sloccode, a.materialgroupid,c.description
		FROM productplant a
		LEFT JOIN sloc b ON b.slocid = a.slocid 
		LEFT JOIN materialgroup c ON c.materialgroupid = a.materialgroupid
		WHERE a.productid IN 
		(
		SELECT DISTINCT z.productid 
		FROM productstockdet z
		left join product za on za.productid = z.productid 
		WHERE z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		and z.qty > 0	".
		(($product != '')?" and za.productname like '%".$product."%'":'').
		")".
		(($plantid != '')?" and b.plantid = ".$plantid:'').
		(($materialgroup != '')?" and c.materialgroupcode like '%".$materialgroup."%'":'').
		(($sloc != '')?" and b.sloccode like '%".$sloc."%'":'');
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title='Rekap Persediaan Barang (Jenis Transaksi)';
		$this->pdf->subtitle='Dari Tgl : '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','A1');
		$grandawalqty=0;$grandawalnilai=0;$grandbeliqty=0;$grandbelinilai=0;$grandreturjualqty=0;$grandreturjualnilai=0;
		$grandtransferinqty=0;$grandtransferinnilai=0;$grandtransferreturqty=0;$grandtransferreturnilai=0;$grandtransferfgqty=0;
		$grandtransferfgnilai=0;$grandhasilproduksiqty=0;$grandhasilproduksinilai=0;$grandkoreksiplusqty=0;$grandkoreksiplusnilai=0;
		$grandreturbeliqty=0;$grandreturbelinilai=0;$grandtransferoutqty=0;$grandtransferoutnilai=0;
		$grandtransferoutfgqty=0;$grandtransferoutfgnilai=0;$grandhasilproduksiminqty=0;$grandhasilproduksiminnilai=0;
		$grandkoreksiminqty=0;$grandkoreksiminnilai=0;$grandsubtotalqty=0;$grandsubtotalnilai=0;
		
		foreach ($dataReader as $row)
		{
			$this->pdf->setFont('Arial','',10);
			$this->pdf->text(15,$this->pdf->gety()+5,'GUDANG');$this->pdf->text(35,$this->pdf->gety()+5,': '.$row['sloccode']);
			$this->pdf->text(200,$this->pdf->gety()+5,'MATERIAL GROUP');$this->pdf->text(235,$this->pdf->gety()+5,': '.$row['description']);
			
			if ($storagebin == null)
			{$this->pdf->text(100,$this->pdf->gety()+5,'');$this->pdf->text(115,$this->pdf->gety()+5,'');}
			else
			{$this->pdf->text(100,$this->pdf->gety()+5,'RAK');$this->pdf->text(115,$this->pdf->gety()+5,': '.$storagebin);}
			
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->sety($this->pdf->gety()+7);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(6,25,90,43,43,43,43,43,43,43,43,43,43,43,43,43,43,43,43,43,43,43,43,43,43,43,43,43,43,43,43,43,43));
			$this->pdf->colheader = array('','','','Awal','Beli','Retur Jual','Transfer In','Transfer Retur','Transfer In FG','Produksi','Koreksi(+)','Retur Beli','Transfer Out','Transfer Out FG','Pemakaian','Koreksi(-)','Total');
			$this->pdf->RowHeader();
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(6,25,80,12,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,17,26));
			$this->pdf->colheader = array('No','Kode Rekening','Artikel','Satuan','Qty','Nilai','Qty','Nilai','Qty','Nilai','Qty','Nilai','Qty','Nilai','Qty','Nilai','Qty','Nilai','Qty','Nilai','Qty','Nilai','Qty','Nilai','Qty','Nilai','Qty','Nilai','Qty','Nilai','Qty','Nilai');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('R','L','L','C','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R');
			$sql1="SELECT DISTINCT c.productcode,c.productname,d.uomcode,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'
			) AS awal,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.averageprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'
			) AS awalnilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'LPB%'
			) AS beli,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'LPB%'
			) AS belinilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'RJ%'
			) AS returjual,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'RJ%'
			) AS returjualnilai,
			(
SELECT ifnull(sum(ifnull(z.qty,0)),0)
FROM productstockdet z
WHERE z.productid = c.productid 
AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
AND z.referenceno LIKE 'TFS%'
AND z.qty >= 0
) AS transferin,
(
SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
FROM productstockdet z
WHERE z.productid = c.productid 
AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
AND z.referenceno LIKE 'TFS%'
AND z.qty >= 0
) AS transferinnilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TRS%'
			) AS transferretur,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TRS%'
			) AS transferreturnilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TFF%'
			AND z.qty >= 0
			) AS transferfg,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TFF%'
			AND z.qty >= 0
			) AS transferfgnilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'OP%'
			AND z.qty >= 0
			) AS hasilproduksi,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'OP%'
			AND z.qty >= 0
			) AS hasilproduksinilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TSO%'
			AND z.qty >= 0
			) AS koreksiplus,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TSO%'
			AND z.qty >= 0
			) AS koreksiplusnilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'RB%'
			AND z.qty < 0
			) AS returbeli,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'RB%'
			AND z.qty < 0
			) AS returbelinilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TFS%'
			AND z.qty < 0
			) AS transferout,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TFS%'
			AND z.qty < 0
			) AS transferoutnilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TFF%'
			AND z.qty < 0
			) AS transferoutfg,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TFF%'
			AND z.qty < 0
			) AS transferoutfgnilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'OP%'
			AND z.qty < 0
			) AS hasilproduksimin,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'OP%'
			AND z.qty < 0
			) AS hasilproduksiminnilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TSO%'
			AND z.qty < 0
			) AS koreksimin,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TSO%'
			AND z.qty < 0
			) AS koreksiminnilai
			FROM productplant b 
			LEFT JOIN product c ON c.productid = b.productid 
			LEFT JOIN unitofmeasure d ON d.unitofmeasureid = b.uom1
			WHERE b.slocid = ".$row['slocid']."
			AND b.materialgroupid = ".$row['materialgroupid'];
			$command1=$this->connection->createCommand($sql1);
			$dataReader1=$command1->queryAll();			
			$i=0;$awalqty=0;$awalnilai=0;$beliqty=0;$belinilai=0;$returjualqty=0;$returjualnilai=0;$transferinqty=0;$transferinnilai=0;
			$transferreturqty=0;$transferreturnilai=0;$totalqty=0;$totalnilai=0;$transferfgqty=0;$transferfgnilai=0;$hasilproduksiqty=0;
			$hasilproduksinilai=0;$koreksiplusqty=0;$koreksiplusnilai=0;$returbeliqty=0;$returbelinilai=0;$transferoutqty=0;$transferoutnilai=0;
			$transferoutfgqty=0;$transferoutfgnilai=0;$hasilproduksiminqty=0;$hasilproduksiminnilai=0;$koreksiminqty=0;$koreksiminnilai=0;
			foreach ($dataReader1 as $row1)
			{
				$i+=1;
				$awalqty += $row1['awal'];
				$awalnilai += $row1['awalnilai']/$per;
				$beliqty += $row1['beli'];
				$belinilai += $row1['belinilai']/$per;
				$returjualqty += $row1['returjual'];
				$returjualnilai += $row1['returjualnilai']/$per;
				$transferinqty += $row1['transferin'];
				$transferinnilai += $row1['transferinnilai']/$per;
				$transferreturqty += $row1['transferretur'];
				$transferreturnilai += $row1['transferreturnilai']/$per;
				$transferfgqty += $row1['transferfg'];
				$transferfgnilai += $row1['transferfgnilai']/$per;
				$hasilproduksiqty += $row1['hasilproduksi'];
				$hasilproduksinilai += $row1['hasilproduksinilai']/$per;
				$koreksiplusqty += $row1['koreksiplus'];
				$koreksiplusnilai += $row1['koreksiplusnilai']/$per;
				$returbeliqty += $row1['returbeli'];
				$returbelinilai += $row1['returbelinilai']/$per;
				$transferoutqty += $row1['transferout'];
				$transferoutnilai += $row1['transferoutnilai']/$per;
				$transferoutfgqty += $row1['transferoutfg'];
				$transferoutfgnilai += $row1['transferoutfgnilai']/$per;
				$hasilproduksiminqty += $row1['hasilproduksimin'];
				$hasilproduksiminnilai += $row1['hasilproduksiminnilai']/$per;
				$koreksiminqty += $row1['koreksimin'];
				$koreksiminnilai += $row1['koreksiminnilai']/$per;
				$totalqty = $row1['awal']+$row1['beli']+$row1['returjual']+$row1['transferin']+$row1['transferretur']+$row1['transferfg']+$row1['hasilproduksi']+
					$row1['koreksiplus']+$row1['returbeli']+$row1['transferout']+$row1['transferoutfg']+$row1['hasilproduksimin']+$row1['koreksimin'];
				$totalnilai = $row1['awalnilai']+$row1['belinilai']+$row1['returjualnilai']+$row1['transferinnilai']+$row1['transferreturnilai']+$row1['transferfgnilai']+$row1['hasilproduksinilai']+
					$row1['koreksiplusnilai']+$row1['returbelinilai']+$row1['transferoutnilai']+$row1['transferoutfgnilai']+$row1['hasilproduksiminnilai']+
					$row1['koreksiminnilai'];
				$this->pdf->setFont('Arial','',6.5);
				$this->pdf->row(array(
					$i,$row1['productcode'],$row1['productname'],$row1['uomcode'],
					Yii::app()->format->formatNumber($row1['awal']),
					Yii::app()->format->formatNumber($row1['awalnilai']/$per),
					Yii::app()->format->formatNumber($row1['beli']),
					Yii::app()->format->formatNumber($row1['belinilai']/$per),
					Yii::app()->format->formatNumber($row1['returjual']),
					Yii::app()->format->formatNumber($row1['returjualnilai']/$per),
					Yii::app()->format->formatNumber($row1['transferin']),
					Yii::app()->format->formatNumber($row1['transferinnilai']/$per),
					Yii::app()->format->formatNumber($row1['transferretur']),
					Yii::app()->format->formatNumber($row1['transferretur']/$per),
					Yii::app()->format->formatNumber($row1['transferfg']),
					Yii::app()->format->formatNumber($row1['transferfgnilai']/$per),
					Yii::app()->format->formatNumber($row1['hasilproduksi']),
					Yii::app()->format->formatNumber($row1['hasilproduksinilai']/$per),
					Yii::app()->format->formatNumber($row1['koreksiplus']),
					Yii::app()->format->formatNumber($row1['koreksiplusnilai']/$per),
					Yii::app()->format->formatNumber($row1['returbeli']),
					Yii::app()->format->formatNumber($row1['returbelinilai']/$per),
					Yii::app()->format->formatNumber($row1['transferout']),
					Yii::app()->format->formatNumber($row1['transferoutnilai']/$per),
					Yii::app()->format->formatNumber($row1['transferoutfg']),
					Yii::app()->format->formatNumber($row1['transferoutfgnilai']/$per),
					Yii::app()->format->formatNumber($row1['hasilproduksimin']),
					Yii::app()->format->formatNumber($row1['hasilproduksiminnilai']/$per),
					Yii::app()->format->formatNumber($row1['koreksimin']),
					Yii::app()->format->formatNumber($row1['koreksiminnilai']/$per),
					Yii::app()->format->formatNumber($totalqty),
					Yii::app()->format->formatNumber($totalnilai/$per),
				));
			}
			$this->pdf->setFont('Arial','B',6.75);
			$this->pdf->setwidths(array(6,25,80,12,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,15,28,17,26));
			$this->pdf->coldetailalign = array('R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R');
			$subtotalqty = $awalqty + $beliqty + $returjualqty + $transferinqty + $transferreturqty + $transferfgqty + $hasilproduksiqty + 
				$koreksiplusqty + $returbeliqty + $transferoutqty + $transferoutfgqty + $hasilproduksiminqty + $koreksiminqty; 
			$subtotalnilai = $awalnilai + $belinilai + $returjualnilai + $transferinnilai + $transferreturnilai + $transferfgnilai + $hasilproduksinilai + 
				$koreksiplusnilai + $returbelinilai + $transferoutnilai + $transferoutfgnilai + $hasilproduksiminnilai + $koreksiminnilai; 
			$this->pdf->row(array(
				'','TOTAL '.$row['sloccode'].' - '.$row['description'].'      >>>>>','',
				Yii::app()->format->formatNumber($awalqty),
				Yii::app()->format->formatNumber($awalnilai),
				Yii::app()->format->formatNumber($beliqty),
				Yii::app()->format->formatNumber($belinilai),
				Yii::app()->format->formatNumber($returjualqty),
				Yii::app()->format->formatNumber($returjualnilai),
				Yii::app()->format->formatNumber($transferinqty),
				Yii::app()->format->formatNumber($transferinnilai),
				Yii::app()->format->formatNumber($transferreturqty),
				Yii::app()->format->formatNumber($transferreturnilai),
				Yii::app()->format->formatNumber($transferfgqty),
				Yii::app()->format->formatNumber($transferfgnilai),
				Yii::app()->format->formatNumber($hasilproduksiqty),
				Yii::app()->format->formatNumber($hasilproduksinilai),
				Yii::app()->format->formatNumber($koreksiplusqty),
				Yii::app()->format->formatNumber($koreksiplusnilai),
				Yii::app()->format->formatNumber($returbeliqty),
				Yii::app()->format->formatNumber($returbelinilai),
				Yii::app()->format->formatNumber($transferoutqty),
				Yii::app()->format->formatNumber($transferoutnilai),
				Yii::app()->format->formatNumber($transferoutfgqty),
				Yii::app()->format->formatNumber($transferoutfgnilai),
				Yii::app()->format->formatNumber($hasilproduksiminqty),
				Yii::app()->format->formatNumber($hasilproduksiminnilai),
				Yii::app()->format->formatNumber($koreksiminqty),
				Yii::app()->format->formatNumber($koreksiminnilai),
				Yii::app()->format->formatNumber($subtotalqty),
				Yii::app()->format->formatNumber($subtotalnilai),
			));
			$grandawalqty += $awalqty;
			$grandawalnilai += $awalnilai;
			$grandbeliqty += $beliqty;
			$grandbelinilai += $belinilai;
			$grandreturjualqty += $returjualqty;
			$grandreturjualnilai += $returjualnilai;
			$grandtransferinqty += $transferinqty;
			$grandtransferinnilai += $transferinnilai;
			$grandtransferreturqty += $transferreturqty;
			$grandtransferreturnilai += $transferreturnilai;
			$grandtransferfgqty += $transferfgqty;
			$grandtransferfgnilai += $transferfgnilai;
			$grandhasilproduksiqty += $hasilproduksiqty;
			$grandhasilproduksinilai += $hasilproduksinilai;
			$grandkoreksiplusqty += $koreksiplusqty;
			$grandkoreksiplusnilai += $koreksiplusnilai;
			$grandreturbeliqty += $returbeliqty;
			$grandreturbelinilai += $grandreturbelinilai;
			$grandtransferoutqty += $transferoutqty;
			$grandtransferoutnilai += $transferoutnilai;
			$grandtransferoutfgqty += $transferoutfgqty;
			$grandtransferoutfgnilai += $transferoutfgnilai;
			$grandhasilproduksiminqty += $hasilproduksiminqty;
			$grandhasilproduksiminnilai += $hasilproduksiminnilai;
			$grandkoreksiminqty += $koreksiminqty;
			$grandkoreksiminnilai += $koreksiminnilai;
			$grandsubtotalqty += $subtotalqty;
			$grandsubtotalnilai += $subtotalnilai;
			$this->pdf->sety($this->pdf->gety()+5);
			$this->pdf->checkNewPage(175);
		}
		$this->pdf->row(array(
			'GRAND TOTAL      >>>>>','',
			Yii::app()->format->formatNumber($grandawalqty),
			Yii::app()->format->formatNumber($grandawalnilai),
			Yii::app()->format->formatNumber($grandbeliqty),
			Yii::app()->format->formatNumber($grandbelinilai),
			Yii::app()->format->formatNumber($grandreturjualqty),
			Yii::app()->format->formatNumber($grandreturjualnilai),
			Yii::app()->format->formatNumber($grandtransferinqty),
			Yii::app()->format->formatNumber($grandtransferinnilai),
			Yii::app()->format->formatNumber($grandtransferreturqty),
			Yii::app()->format->formatNumber($grandtransferreturnilai),
			Yii::app()->format->formatNumber($grandtransferfgqty),
			Yii::app()->format->formatNumber($grandtransferfgnilai),
			Yii::app()->format->formatNumber($grandhasilproduksiqty),
			Yii::app()->format->formatNumber($grandhasilproduksinilai),
			Yii::app()->format->formatNumber($grandkoreksiplusqty),
			Yii::app()->format->formatNumber($grandkoreksiplusnilai),
			Yii::app()->format->formatNumber($grandreturbeliqty),
			Yii::app()->format->formatNumber($grandreturbelinilai),
			Yii::app()->format->formatNumber($grandtransferoutqty),
			Yii::app()->format->formatNumber($grandtransferoutnilai),
			Yii::app()->format->formatNumber($grandtransferoutfgqty),
			Yii::app()->format->formatNumber($grandtransferoutfgnilai),
			Yii::app()->format->formatNumber($grandhasilproduksiminqty),
			Yii::app()->format->formatNumber($grandhasilproduksiminnilai),
			Yii::app()->format->formatNumber($grandkoreksiminqty),
			Yii::app()->format->formatNumber($grandkoreksiminnilai),
			Yii::app()->format->formatNumber($grandsubtotalqty),
			Yii::app()->format->formatNumber($grandsubtotalnilai),
		));
		
		$this->pdf->Output();
	}
    //3
    public function RekapPengeluaranPersediaanBarangDetail($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$qtygr2=0;$jumlahgr2=0;$qtygrr2=0;$jumlahgrr2=0;$qtytfs2=0;$jumlahtfs2=0;$qtyop2=0;$jumlahop2=0;$qtytso2=0;$jumlahtso2=0;$qtytotal2=0;$jumlahtotal2=0;
		$sql="select distinct slocid,sloccode,materialgroupid,description
          from (select a.productname,a.uomcode,a.slocid,a.sloccode,e.materialgroupid,e.description,
          case when a.referenceno like 'SJ%' then sum(a.qty) else 0 end as qtygr,
          case when a.referenceno like 'SJ%' then sum(a.qty*a.averageprice) else 0 end as jumlahgr,
          case when a.referenceno like 'GRR%' then sum(a.qty) else 0 end as qtygrr,
          case when a.referenceno like 'GRR%' then sum(a.qty*a.averageprice) else 0 end as jumlahgrr,
          case when a.referenceno like 'TFS%' then sum(a.qty) else 0 end as qtytfs,
          case when a.referenceno like 'TFS%' then sum(a.qty*a.averageprice) else 0 end as jumlahtfs,
          case when a.referenceno like 'OP%' then sum(a.qty) else 0 end as qtyop,
          case when a.referenceno like 'OP%' then sum(a.qty*a.averageprice) else 0 end as jumlahop,
          case when a.referenceno like 'TSO%' then sum(a.qty) else 0 end as qtytso,
          case when a.referenceno like 'TSO%' then sum(a.qty*a.averageprice) else 0 end as jumlahtso,
          case when a.referenceno like '%%' then sum(a.qty) else 0 end as qtytotal,
          case when a.referenceno like '%%' then sum(a.qty*a.averageprice) else 0 end as jumlahtotal
          from productstockdet a
          join productplant b on b.productid=a.productid and b.slocid=a.slocid and b.uomid=a.unitofmeasureid
          join sloc c on c.slocid=a.slocid
          join plant d on d.plantid=c.plantid
          join materialgroup e on e.materialgroupid=b.materialgroupid
          where a.qty < 0 ".
					(($plantid != '')?" and d.plantid = ".$plantid:'').
					" and a.sloccode like '%".$sloc."%' and e.materialgroupcode like '%".$materialgroup."%' and a.productname like '%".$product."%'
          and a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
          group by a.productid,a.slocid,a.unitofmeasureid
          order by productname) zz          
          where qtygr <> 0 or qtygrr <> 0 or qtytfs <> 0 or qtyop <> 0 or qtytso <> 0
          order by sloccode, description";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title='Rekap Pengeluaran Persediaan Barang (Detail)';
		$this->pdf->subtitle='Dari Tgl : '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','Legal');
		
		foreach ($dataReader as $row)
		{
			$this->pdf->setFont('Arial','',10);
			$this->pdf->text(15,$this->pdf->gety()+5,'GUDANG');$this->pdf->text(35,$this->pdf->gety()+5,': '.$row['sloccode']);
			$this->pdf->text(200,$this->pdf->gety()+5,'MATERIAL GROUP');$this->pdf->text(235,$this->pdf->gety()+5,': '.$row['description']);
			
			if ($storagebin == null)
			{$this->pdf->text(100,$this->pdf->gety()+5,'');$this->pdf->text(115,$this->pdf->gety()+5,'');}
			else
			{$this->pdf->text(100,$this->pdf->gety()+5,'RAK');$this->pdf->text(115,$this->pdf->gety()+5,': '.$storagebin);}
			
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->sety($this->pdf->gety()+7);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(6,80,12,39,39,39,39,39,43));
			$this->pdf->colheader = array('','','','Jual','Retur Beli','Transfer Out','Bahan','Koreksi','Total');
			$this->pdf->RowHeader();
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(6,80,12,15,24,15,24,15,24,15,24,15,24,17,26));
			$this->pdf->colheader = array('No','Nama Barang','Satuan','Qty','Nilai','Qty','Nilai','Qty','Nilai','Qty','Nilai','Qty','Nilai','Qty','Nilai');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('R','L','C','R','R','R','R','R','R','R','R','R','R','R','R');
			
			$i=0;$qtygr=0;$jumlahgr=0;$qtygrr=0;$jumlahgrr=0;$qtytfs=0;$jumlahtfs=0;$qtyop=0;$jumlahop=0;$qtytso=0;$jumlahtso=0;$qtytotal=0;$jumlahtotal=0;
			$sql1="select *
            from (select a.productname,a.uomcode,
            case when a.referenceno like 'SJ%' then -sum(a.qty) else 0 end as qtygr,
            case when a.referenceno like 'SJ%' then -sum(a.qty*a.averageprice) else 0 end as jumlahgr,
            case when a.referenceno like 'GRR%' then -sum(a.qty) else 0 end as qtygrr,
            case when a.referenceno like 'GRR%' then -sum(a.qty*a.averageprice) else 0 end as jumlahgrr,
            case when a.referenceno like 'TFS%' then -sum(a.qty) else 0 end as qtytfs,
            case when a.referenceno like 'TFS%' then -sum(a.qty*a.averageprice) else 0 end as jumlahtfs,
            case when a.referenceno like 'OP%' then -sum(a.qty) else 0 end as qtyop,
            case when a.referenceno like 'OP%' then -sum(a.qty*a.averageprice) else 0 end as jumlahop,
            case when a.referenceno like 'TSO%' then -sum(a.qty) else 0 end as qtytso,
            case when a.referenceno like 'TSO%' then -sum(a.qty*a.averageprice) else 0 end as jumlahtso,
            case when a.referenceno like '%%' then -sum(a.qty) else 0 end as qtytotal,
            case when a.referenceno like '%%' then -sum(a.qty*a.averageprice) else 0 end as jumlahtotal
            from productstockdet a
            join productplant b on b.productid=a.productid and b.slocid=a.slocid and b.uomid=a.unitofmeasureid
            join sloc c on c.slocid=a.slocid
            join plant d on d.plantid=c.plantid
            join materialgroup e on e.materialgroupid=b.materialgroupid
            where d.plantid = ".$plantid." and a.qty < 0 and a.sloccode like '%".$sloc."%' and e.materialgroupcode like '%".$materialgroup."%' and a.productname like '%".$product."%'
            and a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'            
            and a.slocid = ".$row['slocid']." and e.materialgroupid = ".$row['materialgroupid']."
            group by a.productid,a.slocid,a.unitofmeasureid) z
            where qtygr <> 0 or qtygrr <> 0 or qtytfs <> 0 or qtyop <> 0 or qtytso <> 0
            order by productname";
			$command1=$this->connection->createCommand($sql1);
			$dataReader1=$command1->queryAll();
			
			foreach ($dataReader1 as $row1)
			{
				$i+=1;
				$this->pdf->setFont('Arial','',6.5);
				$this->pdf->row(array(
					$i,$row1['productname'],$row1['uomcode'],
					Yii::app()->format->formatNumber($row1['qtygr']),
					Yii::app()->format->formatNumber($row1['jumlahgr']/$per),
					Yii::app()->format->formatNumber($row1['qtygrr']),
					Yii::app()->format->formatNumber($row1['jumlahgrr']/$per),
					Yii::app()->format->formatNumber($row1['qtytfs']),
					Yii::app()->format->formatNumber($row1['jumlahtfs']/$per),
					Yii::app()->format->formatNumber($row1['qtyop']),
					Yii::app()->format->formatNumber($row1['jumlahop']/$per),
					Yii::app()->format->formatNumber($row1['qtytso']),
					Yii::app()->format->formatNumber($row1['jumlahtso']/$per),
					Yii::app()->format->formatNumber($row1['qtytotal']),
					Yii::app()->format->formatNumber($row1['jumlahtotal']/$per),
				));
				$qtygr += $row1['qtygr'];
				$jumlahgr += $row1['jumlahgr']/$per;
				$qtygrr += $row1['qtygrr'];
				$jumlahgrr += $row1['jumlahgrr']/$per;
				$qtytfs += $row1['qtytfs'];
				$jumlahtfs += $row1['jumlahtfs']/$per;
				$qtyop += $row1['qtyop'];
				$jumlahop += $row1['jumlahop']/$per;
				$qtytso += $row1['qtytso'];
				$jumlahtso += $row1['jumlahtso']/$per;
				$qtytotal += $row1['qtytotal'];
				$jumlahtotal += $row1['jumlahtotal']/$per;
			}
			$this->pdf->setFont('Arial','B',6.75);
			$this->pdf->setwidths(array(98,15,24,15,24,15,24,15,24,15,24,17,26));
			$this->pdf->coldetailalign = array('R','R','R','R','R','R','R','R','R','R','R','R','R');
			$this->pdf->row(array(
				'TOTAL '.$row['sloccode'].' - '.$row['description'].'      >>>>>',
				Yii::app()->format->formatNumber($qtygr),
				Yii::app()->format->formatNumber($jumlahgr),
				Yii::app()->format->formatNumber($qtygrr),
				Yii::app()->format->formatNumber($jumlahgrr),
				Yii::app()->format->formatNumber($qtytfs),
				Yii::app()->format->formatNumber($jumlahtfs),
				Yii::app()->format->formatNumber($qtyop),
				Yii::app()->format->formatNumber($jumlahop),
				Yii::app()->format->formatNumber($qtytso),
				Yii::app()->format->formatNumber($jumlahtso),
				Yii::app()->format->formatNumber($qtytotal),
				Yii::app()->format->formatNumber($jumlahtotal),
			));
			$qtygr2 += $qtygr;
      $jumlahgr2 += $jumlahgr;
      $qtygrr2 += $qtygrr;
      $jumlahgrr2 += $jumlahgrr;
      $qtytfs2 += $qtytfs;
      $jumlahtfs2 += $jumlahtfs;
      $qtyop2 += $qtyop;
      $jumlahop2 += $jumlahop;
      $qtytso2 += $qtytso;
      $jumlahtso2 += $jumlahtso;
      $qtytotal2 += $qtytotal;
      $jumlahtotal2 += $jumlahtotal;
			
			$this->pdf->sety($this->pdf->gety()+5);
			$this->pdf->checkNewPage(175);
		}
		$this->pdf->setFont('Arial','BI',7);
		$this->pdf->colalign = array('C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(62,45,45,45,45,45,49));
		$this->pdf->colheader = array('','Jual','Retur Jual','Transfer Out','Bahan','Koreksi','Total');
		$this->pdf->RowHeader();
		$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(62,17,28,17,28,17,28,17,28,17,28,19,30));
		$this->pdf->colheader = array('Keterangan','Qty','Nilai','Qty','Nilai','Qty','Nilai','Qty','Nilai','Qty','Nilai','Qty','Nilai');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('C','R','R','R','R','R','R','R','R','R','R','R','R');
		$this->pdf->row(array(
			'GRAND TOTAL      >>>>>',
			Yii::app()->format->formatNumber($qtygr2),
			Yii::app()->format->formatNumber($jumlahgr2),
			Yii::app()->format->formatNumber($qtygrr2),
			Yii::app()->format->formatNumber($jumlahgrr2),
			Yii::app()->format->formatNumber($qtytfs2),
			Yii::app()->format->formatNumber($jumlahtfs2),
			Yii::app()->format->formatNumber($qtyop2),
			Yii::app()->format->formatNumber($jumlahop2),
			Yii::app()->format->formatNumber($qtytso2),
			Yii::app()->format->formatNumber($jumlahtso2),
			Yii::app()->format->formatNumber($qtytotal2),
			Yii::app()->format->formatNumber($jumlahtotal2),
		));
		
		$this->pdf->Output();
	}
	//12
    public function RekapPersediaanBarangDetailDataHarga($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per)
	//Masih menggunakan Data Harga
	{
		parent::actionDownload();
		$qtyawal2=0;$nilaiawal2=0;$qtymasuk2=0;$nilaimasuk2=0;$qtytersedia2=0;$nilaitersedia2=0;$qtykeluar2=0;$nilaikeluar2=0;$qtyakhir2=0;$nilaiakhir2=0;
		$sql="select distinct slocid,sloccode,materialgroupid,description from
							(select barang,satuan,awal,masuk,keluar,(awal+masuk+keluar) as akhir,slocid,sloccode,materialgroupid,description
							from
							(select barang,satuan,awal,(beli+returjual+trfin+produksi+konversiin) as masuk,(jual+returbeli+trfout+pemakaian+koreksi+konversiout) as keluar,slocid,sloccode,materialgroupid,description
							from
							(select
							(
							select distinct aa.productid 
							from productstockdet a
							join product aa on aa.productid = a.productid
							join sloc ab on ab.slocid = a.slocid
							join storagebin ac on ac.storagebinid=a.storagebinid
							where a.productid = t.productid and
							a.unitofmeasureid = t.uomid and
							ac.description like '%".$storagebin."%'
							) as barang,
							(
							select distinct bb.uomcode 
							from productstockdet b
							join unitofmeasure bb on bb.unitofmeasureid = b.unitofmeasureid
							join sloc ba on ba.slocid = b.slocid
							join storagebin bc on bc.storagebinid=b.storagebinid
							where b.productid = t.productid and
							b.unitofmeasureid = t.uomid and
							bc.description like '%".$storagebin."%'
							) as satuan,
							(
							select ifnull(sum(aw.qty),0) 
							from productstockdet aw
							join sloc aaw on aaw.slocid = aw.slocid
							join storagebin abw on abw.storagebinid=aw.storagebinid
							where aw.productid = t.productid and
							aw.transdate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and
							aw.slocid = t.slocid and
							abw.description like '%".$storagebin."%'
							) as awal,
							(
							select ifnull(sum(c.qty),0) 
							from productstockdet c
							join sloc cc on cc.slocid = c.slocid
							join storagebin ccc on ccc.storagebinid=c.storagebinid
							where c.productid = t.productid and
							c.referenceno like 'GR-%' and
							c.slocid = t.slocid and
							ccc.description like '%".$storagebin."%' and
							c.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as beli,
							(
							select ifnull(sum(d.qty),0) 
							from productstockdet d
							join sloc dd on dd.slocid = d.slocid
							join storagebin ddd on ddd.storagebinid=d.storagebinid
							where d.productid = t.productid and
							d.referenceno like 'GIR-%' and
							d.slocid = t.slocid and
							ddd.description like '%".$storagebin."%' and
							d.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as returjual,
							(
							select ifnull(sum(e.qty),0) 
							from productstockdet e
							join sloc ee on ee.slocid = e.slocid
							join storagebin eee on eee.storagebinid=e.storagebinid
							where e.productid = t.productid and
							e.referenceno like 'TFS-%' and
							e.qty > 0 and
							e.slocid = t.slocid and
							eee.description like '%".$storagebin."%' and
							e.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as trfin,
							(
							select ifnull(sum(f.qty),0) 
							from productstockdet f
							join sloc ff on ff.slocid = f.slocid
							join storagebin fff on fff.storagebinid=f.storagebinid
							where f.productid = t.productid and
							f.referenceno like 'OP-%' and
							f.qty > 0 and
							f.slocid = t.slocid and
							fff.description like '%".$storagebin."%' and
							f.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as produksi,
							(
							select ifnull(sum(g.qty),0) 
							from productstockdet g
							join sloc gg on gg.slocid = g.slocid
							join storagebin ggg on ggg.storagebinid=g.storagebinid
							where g.productid = t.productid and
							g.referenceno like 'SJ-%' and
							g.slocid = t.slocid and
							ggg.description like '%".$storagebin."%' and
							g.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as jual,
							(
							select ifnull(sum(h.qty),0) 
							from productstockdet h
							join sloc hh on hh.slocid = h.slocid
							join storagebin hhh on hhh.storagebinid=h.storagebinid
							where h.productid = t.productid and
							h.referenceno like 'GRR-%' and
							h.slocid = t.slocid and
							hhh.description like '%".$storagebin."%' and
							h.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as returbeli,
							(
							select ifnull(sum(i.qty),0) 
							from productstockdet i
							join sloc ii on ii.slocid = i.slocid
							join storagebin iii on iii.storagebinid=i.storagebinid
							where i.productid = t.productid and
							i.referenceno like 'TFS-%' and
							i.qty < 0 and
							i.slocid = t.slocid and
							iii.description like '%".$storagebin."%' and
							i.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as trfout,
							(
							select ifnull(sum(j.qty),0) 
							from productstockdet j
							join sloc jj on jj.slocid = j.slocid
							join storagebin jjj on jjj.storagebinid=j.storagebinid
							where j.productid = t.productid and
							j.referenceno like 'OP-%' and
							j.qty < 0 and
							j.slocid = t.slocid and
							jjj.description like '%".$storagebin."%' and
							j.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as pemakaian,
							(
							select ifnull(sum(k.qty),0) 
							from productstockdet k
							join sloc kk on kk.slocid = k.slocid
							join storagebin kkk on kkk.storagebinid=k.storagebinid
							where k.productid = t.productid and
							k.referenceno like 'TSO-%' and
							k.slocid = t.slocid and
							kkk.description like '%".$storagebin."%' and
							k.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as koreksi,
							(select ifnull(sum(l.qty),0) 
							from productstockdet l
							join sloc ll on ll.slocid = l.slocid
							join storagebin lll on lll.storagebinid=l.storagebinid
							where l.productid = t.productid and
							l.referenceno like '%konversi%' and
							l.qty > 0 and
							l.slocid = t.slocid and
							lll.description like '%".$storagebin."%' and
							l.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as konversiin,
							(
							select ifnull(sum(m.qty),0) 
							from productstockdet m
							join sloc mm on mm.slocid = m.slocid
							join storagebin mmm on mmm.storagebinid=m.storagebinid
							where m.productid = t.productid and
							m.referenceno like '%konversi%' and
							m.qty < 0 and
							m.slocid = t.slocid and
							mmm.description like '%".$storagebin."%' and
							m.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as konversiout,v.slocid,v.sloccode,u.materialgroupid,u.description
							from productplant t
							join materialgroup u on u.materialgroupid = t.materialgroupid
							join sloc v on v.slocid = t.slocid
							where getcompanyfromsloc(v.slocid) = ".$companyid." and v.sloccode like '%".$sloc."%'
							and u.materialgroupcode like '%".$materialgroup."%') z) zz )zzz where awal<>0 or masuk<>0 or keluar<>0 or akhir<>0
					order by materialgroupid,slocid";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		
		foreach($dataReader as $row)
		{
				$this->pdf->companyid = $companyid;
		}
		$this->pdf->title='Rekap Persediaan Barang (Detail) - Data Harga';
		$this->pdf->subtitle='Dari Tgl : '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','Legal');
		
		foreach ($dataReader as $row)
		{
			$this->pdf->setFont('Arial','',10);
			$this->pdf->text(15,$this->pdf->gety()+5,'GUDANG');$this->pdf->text(35,$this->pdf->gety()+5,': '.$row['sloccode']);
			$this->pdf->text(200,$this->pdf->gety()+5,'MATERIAL GROUP');$this->pdf->text(235,$this->pdf->gety()+5,': '.$row['description']);
			
			if ($storagebin == null)
			{$this->pdf->text(100,$this->pdf->gety()+5,'');$this->pdf->text(115,$this->pdf->gety()+5,'');}
			else
			{$this->pdf->text(100,$this->pdf->gety()+5,'RAK');$this->pdf->text(115,$this->pdf->gety()+5,': '.$storagebin);}
			
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->sety($this->pdf->gety()+7);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(6,80,12,61,38,61,38,38,5));
			$this->pdf->colheader = array('','','','Awal','Penerimaan','Tersedia','Pengeluaran','Akhir','');
			$this->pdf->RowHeader();
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(6,80,12,15,23,23,15,23,15,23,23,15,23,15,23,5));
			$this->pdf->colheader = array('No','Nama Barang','Satuan','Qty','Harga','Nilai','Qty','Nilai','Qty','Harga','Nilai','Qty','Nilai','Qty','Nilai','');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('R','L','C','R','R','R','R','R','R','R','R','R','R','R','R','C');
			
			$i=0;$qtyawal=0;$nilaiawal=0;$qtymasuk=0;$nilaimasuk=0;$qtytersedia=0;$nilaitersedia=0;$qtykeluar=0;$nilaikeluar=0;$qtyakhir=0;$nilaiakhir=0;
			$sql1="select *,case when akhir < 0 then 'X' else '' end as minus from
							(select barang,satuan,awal,masuk,keluar,(awal+masuk+keluar) as akhir,harga
							from
							(select barang,satuan,awal,(beli+returjual+trfin+produksi+konversiin) as masuk,(jual+returbeli+trfout+pemakaian+koreksi+konversiout) as keluar,harga
							from
							(select 
							(
							select distinct aa.productname 
							from productstockdet a
							join product aa on aa.productid = a.productid
							join sloc ab on ab.slocid = a.slocid
							join storagebin ac on ac.storagebinid=a.storagebinid
							where a.productid = t.productid and
							a.unitofmeasureid = t.uomid and
							ac.description like '%".$storagebin."%'
							) as barang,
							(
							select distinct bb.uomcode 
							from productstockdet b
							join unitofmeasure bb on bb.unitofmeasureid = b.unitofmeasureid
							join sloc ba on ba.slocid = b.slocid
							join storagebin bc on bc.storagebinid=b.storagebinid
							where b.productid = t.productid and
							b.unitofmeasureid = t.uomid and
							bc.description like '%".$storagebin."%'
							) as satuan,
							(
							select ifnull(sum(aw.qty),0) 
							from productstockdet aw
							join sloc aaw on aaw.slocid = aw.slocid
							join storagebin aww on aww.storagebinid=aw.storagebinid
							where aw.productid = t.productid and
							aw.transdate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and
							aw.slocid = t.slocid and
							aww.description like '%".$storagebin."%'
							) as awal,
							(
							select ifnull(sum(c.qty),0) 
							from productstockdet c
							join sloc cc on cc.slocid = c.slocid
							join storagebin ccc on ccc.storagebinid=c.storagebinid
							where c.productid = t.productid and
							c.referenceno like 'GR-%' and
							c.slocid = t.slocid and
							ccc.description like '%".$storagebin."%' and
							c.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as beli,
							(
							select ifnull(sum(d.qty),0) 
							from productstockdet d
							join sloc dd on dd.slocid = d.slocid
							join storagebin ddd on ddd.storagebinid=d.storagebinid
							where d.productid = t.productid and
							d.referenceno like 'GIR-%' and
							d.slocid = t.slocid and
							ddd.description like '%".$storagebin."%' and
							d.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as returjual,
							(
							select ifnull(sum(e.qty),0) 
							from productstockdet e
							join sloc ee on ee.slocid = e.slocid
							join storagebin eee on eee.storagebinid=e.storagebinid
							where e.productid = t.productid and
							e.referenceno like 'TFS-%' and
							e.qty > 0 and
							e.slocid = t.slocid and
							eee.description like '%".$storagebin."%' and
							e.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as trfin,
							(
							select ifnull(sum(f.qty),0) 
							from productstockdet f
							join sloc ff on ff.slocid = f.slocid
							join storagebin fff on fff.storagebinid=f.storagebinid
							where f.productid = t.productid and
							f.referenceno like 'OP-%' and
							f.qty > 0 and
							f.slocid = t.slocid and
							fff.description like '%".$storagebin."%' and
							f.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as produksi,
							(
							select ifnull(sum(g.qty),0) 
							from productstockdet g
							join sloc gg on gg.slocid = g.slocid
							join storagebin ggg on ggg.storagebinid=g.storagebinid
							where g.productid = t.productid and
							g.referenceno like 'SJ-%' and
							g.slocid = t.slocid and
							ggg.description like '%".$storagebin."%' and
							g.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as jual,
							(
							select ifnull(sum(h.qty),0) 
							from productstockdet h
							join sloc hh on hh.slocid = h.slocid
							join storagebin hhh on hhh.storagebinid=h.storagebinid
							where h.productid = t.productid and
							h.referenceno like 'GRR-%' and
							h.slocid = t.slocid and
							hhh.description like '%".$storagebin."%' and
							h.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as returbeli,
							(
							select ifnull(sum(i.qty),0) 
							from productstockdet i
							join sloc ii on ii.slocid = i.slocid
							join storagebin iii on iii.storagebinid=i.storagebinid
							where i.productid = t.productid and
							i.referenceno like 'TFS-%' and
							i.qty < 0 and
							i.slocid = t.slocid and
							iii.description like '%".$storagebin."%' and
							i.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as trfout,
							(
							select ifnull(sum(j.qty),0) 
							from productstockdet j
							join sloc jj on jj.slocid = j.slocid
							join storagebin jjj on jjj.storagebinid=j.storagebinid
							where j.productid = t.productid and
							j.referenceno like 'OP-%' and
							j.qty < 0 and
							j.slocid = t.slocid and
							jjj.description like '%".$storagebin."%' and
							j.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as pemakaian,
							(
							select ifnull(sum(k.qty),0) 
							from productstockdet k
							join sloc kk on kk.slocid = k.slocid
							join storagebin kkk on kkk.storagebinid=k.storagebinid
							where k.productid = t.productid and
							k.referenceno like 'TSO-%' and
							k.slocid = t.slocid and
							kkk.description like '%".$storagebin."%' and
							k.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as koreksi,
							(select ifnull(sum(l.qty),0) 
							from productstockdet l
							join sloc ll on ll.slocid = l.slocid
							join storagebin lll on lll.storagebinid=l.storagebinid
							where l.productid = t.productid and
							l.referenceno like '%konversi%' and
							l.qty > 0 and
							l.slocid = t.slocid and
							lll.description like '%".$storagebin."%' and
							l.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as konversiin,
							(
							select ifnull(sum(m.qty),0) 
							from productstockdet m
							join sloc mm on mm.slocid = m.slocid
							join storagebin mmm on mmm.storagebinid=m.storagebinid
							where m.productid = t.productid and
							m.referenceno like '%konversi%' and
							m.qty < 0 and
							m.slocid = t.slocid and
							m.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as konversiout,
							ifnull((select q.harga 
							from dataharga q
							where q.productid=t.productid
							),0) as harga
							from productplant t
							join materialgroup u on u.materialgroupid = t.materialgroupid
							join sloc v on v.slocid = t.slocid
									where getcompanyfromsloc(v.slocid) = ".$companyid."
              and u.materialgroupid = '".$row['materialgroupid']."' 
							and v.slocid = '".$row['slocid']."' and v.sloccode like '%".$sloc."%'
							and u.materialgroupcode like '%".$materialgroup."%' order by barang) z) zz )zzz 
							where awal<>0 or masuk<>0 or keluar<>0 or akhir<>0 order by barang asc";
			$command1=$this->connection->createCommand($sql1);
			$dataReader1=$command1->queryAll();
			
			foreach ($dataReader1 as $row1)
			{
				$i+=1;
				$this->pdf->setFont('Arial','',6.5);
				$this->pdf->row(array(
					$i,$row1['barang'],$row1['satuan'],
					Yii::app()->format->formatNumber($row1['awal']),
					Yii::app()->format->formatNumber($row1['harga']/$per),
					Yii::app()->format->formatNumber(($row1['awal'] * $row1['harga']/$per)),
					Yii::app()->format->formatNumber($row1['masuk']),
					Yii::app()->format->formatNumber(($row1['masuk'] * $row1['harga']/$per)),
					Yii::app()->format->formatNumber(($row1['awal'] + $row1['masuk'])),
					Yii::app()->format->formatNumber($row1['harga']/$per),
					Yii::app()->format->formatNumber((($row1['awal'] + $row1['masuk']) * $row1['harga']/$per)),
					Yii::app()->format->formatNumber($row1['keluar']),
					Yii::app()->format->formatNumber(($row1['keluar'] * $row1['harga']/$per)),
					Yii::app()->format->formatNumber($row1['akhir']),
					Yii::app()->format->formatNumber(($row1['akhir'] * $row1['harga']/$per)),
					$row1['minus'],
				));
				$qtyawal += $row1['awal'];
				$nilaiawal += ($row1['awal'] * $row1['harga']/$per);
				$qtymasuk += $row1['masuk'];
				$nilaimasuk += ($row1['masuk'] * $row1['harga']/$per);
				$qtytersedia += ($row1['awal'] + $row1['masuk']);
				$nilaitersedia += (($row1['awal'] + $row1['masuk']) * $row1['harga']/$per);
				$qtykeluar += $row1['keluar'];
				$nilaikeluar += ($row1['keluar'] * $row1['harga']/$per);
				$qtyakhir += $row1['akhir'];
				$nilaiakhir += ($row1['akhir'] * $row1['harga']/$per);
			}
			$this->pdf->setFont('Arial','B',6.5);
			$this->pdf->setwidths(array(98,15,23,23,15,23,15,23,23,15,23,15,23,5));
			$this->pdf->coldetailalign = array('R','R','R','R','R','R','R','R','R','R','R','R','R','C');
			$this->pdf->row(array(
				'TOTAL '.$row['sloccode'].' - '.$row['description'].'      >>>>>',
				Yii::app()->format->formatNumber($qtyawal),'',
				Yii::app()->format->formatNumber($nilaiawal),
				Yii::app()->format->formatNumber($qtymasuk),
				Yii::app()->format->formatNumber($nilaimasuk),
				Yii::app()->format->formatNumber($qtytersedia),'',
				Yii::app()->format->formatNumber($nilaitersedia),
				Yii::app()->format->formatNumber($qtykeluar),
				Yii::app()->format->formatNumber($nilaikeluar),
				Yii::app()->format->formatNumber($qtyakhir),
				Yii::app()->format->formatNumber($nilaiakhir),'',
			));
			$qtyawal2 += $qtyawal;
			$nilaiawal2 += $nilaiawal;
			$qtymasuk2 += $qtymasuk;
			$nilaimasuk2 += $nilaimasuk;
			$qtytersedia2 += $qtytersedia;
			$nilaitersedia2 += $nilaitersedia;
			$qtykeluar2 += $qtykeluar;
			$nilaikeluar2 += $nilaikeluar;
			$qtyakhir2 += $qtyakhir;
			$nilaiakhir2 += $nilaiakhir;
			
			$this->pdf->sety($this->pdf->gety()+5);
			$this->pdf->checkNewPage(175);
		}
		$this->pdf->setFont('Arial','BI',6.5);
		$this->pdf->colalign = array('C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(69,68,43,68,43,43,5));
		$this->pdf->colheader = array('','Awal','Penerimaan','Tersedia','Pengeluaran','Akhir','');
		$this->pdf->RowHeader();
		$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(69,18,25,25,18,25,18,25,25,18,25,18,25,5));
		$this->pdf->colheader = array('Keterangan','Qty','Harga','Nilai','Qty','Nilai','Qty','Harga','Nilai','Qty','Nilai','Qty','Nilai','');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('C','R','R','R','R','R','R','R','R','R','R','R','R','C');
		$this->pdf->row(array(
			'GRAND TOTAL      >>>>>',
			Yii::app()->format->formatNumber($qtyawal2),'',
			Yii::app()->format->formatNumber($nilaiawal2),
			Yii::app()->format->formatNumber($qtymasuk2),
			Yii::app()->format->formatNumber($nilaimasuk2),
			Yii::app()->format->formatNumber($qtytersedia2),'',
			Yii::app()->format->formatNumber($nilaitersedia2),
			Yii::app()->format->formatNumber($qtykeluar2),
			Yii::app()->format->formatNumber($nilaikeluar2),
			Yii::app()->format->formatNumber($qtyakhir2),
			Yii::app()->format->formatNumber($nilaiakhir2),'',
		));
		
		$this->pdf->Output();
	}
	/* tanpa filter rak
	public function RekapPersediaanBarangDetail($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per)
	//Masih menggunakan Data Harga
	{
		parent::actionDownload();
		$qtyawal2=0;$nilaiawal2=0;$qtymasuk2=0;$nilaimasuk2=0;$qtytersedia2=0;$nilaitersedia2=0;$qtykeluar2=0;$nilaikeluar2=0;$qtyakhir2=0;$nilaiakhir2=0;
		$sql="select distinct slocid,sloccode,materialgroupid,description from
							(select barang,satuan,awal,masuk,keluar,(awal+masuk+keluar) as akhir,slocid,sloccode,materialgroupid,description
							from
							(select barang,satuan,awal,(beli+returjual+trfin+produksi+konversiin) as masuk,(jual+returbeli+trfout+pemakaian+koreksi+konversiout) as keluar,slocid,sloccode,materialgroupid,description
							from
							(select
							(
							select distinct aa.productid 
							from productstockdet a
							join product aa on aa.productid = a.productid
							join sloc ab on ab.slocid = a.slocid
							where a.productid = t.productid and
							a.unitofmeasureid = t.uomid
							) as barang,
							(
							select distinct bb.uomcode 
							from productstockdet b
							join unitofmeasure bb on bb.unitofmeasureid = b.unitofmeasureid
							join sloc ba on ba.slocid = b.slocid
							where b.productid = t.productid and
							b.unitofmeasureid = t.uomid
							) as satuan,
							(
							select ifnull(sum(aw.qty),0) 
							from productstockdet aw
							join sloc aaw on aaw.slocid = aw.slocid
							where aw.productid = t.productid and
							aw.transdate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and
							aw.slocid = t.slocid
							) as awal,
							(
							select ifnull(sum(c.qty),0) 
							from productstockdet c
							join sloc cc on cc.slocid = c.slocid
							where c.productid = t.productid and
							c.referenceno like 'GR-%' and
							c.slocid = t.slocid and
							c.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as beli,
							(
							select ifnull(sum(d.qty),0) 
							from productstockdet d
							join sloc dd on dd.slocid = d.slocid
							where d.productid = t.productid and
							d.referenceno like 'GIR-%' and
							d.slocid = t.slocid and
							d.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as returjual,
							(
							select ifnull(sum(e.qty),0) 
							from productstockdet e
							join sloc ee on ee.slocid = e.slocid
							where e.productid = t.productid and
							e.referenceno like 'TFS-%' and
							e.qty > 0 and
							e.slocid = t.slocid and
							e.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as trfin,
							(
							select ifnull(sum(f.qty),0) 
							from productstockdet f
							join sloc ff on ff.slocid = f.slocid
							where f.productid = t.productid and
							f.referenceno like 'OP-%' and
							f.qty > 0 and
							f.slocid = t.slocid and
							f.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as produksi,
							(
							select ifnull(sum(g.qty),0) 
							from productstockdet g
							join sloc gg on gg.slocid = g.slocid
							where g.productid = t.productid and
							g.referenceno like 'SJ-%' and
							g.slocid = t.slocid and
							g.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as jual,
							(
							select ifnull(sum(h.qty),0) 
							from productstockdet h
							join sloc hh on hh.slocid = h.slocid
							where h.productid = t.productid and
							h.referenceno like 'GRR-%' and
							h.slocid = t.slocid and
							h.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as returbeli,
							(
							select ifnull(sum(i.qty),0) 
							from productstockdet i
							join sloc ii on ii.slocid = i.slocid
							where i.productid = t.productid and
							i.referenceno like 'TFS-%' and
							i.qty < 0 and
							i.slocid = t.slocid and
							i.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as trfout,
							(
							select ifnull(sum(j.qty),0) 
							from productstockdet j
							join sloc jj on jj.slocid = j.slocid
							where j.productid = t.productid and
							j.referenceno like 'OP-%' and
							j.qty < 0 and
							j.slocid = t.slocid and
							j.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as pemakaian,
							(
							select ifnull(sum(k.qty),0) 
							from productstockdet k
							join sloc kk on kk.slocid = k.slocid
							where k.productid = t.productid and
							k.referenceno like 'TSO-%' and
							k.slocid = t.slocid and
							k.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as koreksi,
							(select ifnull(sum(l.qty),0) 
							from productstockdet l
							join sloc ll on ll.slocid = l.slocid
							where l.productid = t.productid and
							l.referenceno like '%konversi%' and
							l.qty > 0 and
							l.slocid = t.slocid
							and l.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as konversiin,
							(
							select ifnull(sum(m.qty),0) 
							from productstockdet m
							join sloc mm on mm.slocid = m.slocid
							where m.productid = t.productid and
							m.referenceno like '%konversi%' and
							m.qty < 0 and
							m.slocid = t.slocid and
							m.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as konversiout,v.slocid,v.sloccode,u.materialgroupid,u.description
							from productplant t
							join materialgroup u on u.materialgroupid = t.materialgroupid
							join sloc v on v.slocid = t.slocid
							where getcompanyfromsloc(v.slocid) = ".$companyid." and v.sloccode like '%".$sloc."%'
							and u.materialgroupcode like '%".$materialgroup."%') z) zz )zzz where awal<>0 or masuk<>0 or keluar<>0 or akhir<>0
					order by materialgroupid,slocid";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		
		foreach($dataReader as $row)
		{
				$this->pdf->companyid = $companyid;
		}
		$this->pdf->title='Rekap Persediaan Barang (Detail)';
		$this->pdf->subtitle='Dari Tgl : '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','Legal');
		
		foreach ($dataReader as $row)
		{
			$this->pdf->setFont('Arial','',10);
			$this->pdf->text(15,$this->pdf->gety()+5,'GUDANG');$this->pdf->text(35,$this->pdf->gety()+5,': '.$row['sloccode']);
			$this->pdf->text(200,$this->pdf->gety()+5,'MATERIAL GROUP');$this->pdf->text(235,$this->pdf->gety()+5,': '.$row['description']);
			
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->sety($this->pdf->gety()+7);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(6,80,12,61,38,61,38,38,5));
			$this->pdf->colheader = array('','','','Awal','Penerimaan','Tersedia','Pengeluaran','Akhir','');
			$this->pdf->RowHeader();
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(6,80,12,15,23,23,15,23,15,23,23,15,23,15,23,5));
			$this->pdf->colheader = array('No','Nama Barang','Satuan','Qty','Harga','Nilai','Qty','Nilai','Qty','Harga','Nilai','Qty','Nilai','Qty','Nilai','');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('R','L','C','R','R','R','R','R','R','R','R','R','R','R','R','C');
			
			$i=0;$qtyawal=0;$nilaiawal=0;$qtymasuk=0;$nilaimasuk=0;$qtytersedia=0;$nilaitersedia=0;$qtykeluar=0;$nilaikeluar=0;$qtyakhir=0;$nilaiakhir=0;
			$sql1="select *,case when akhir < 0 then 'X' else '' end as minus from
							(select barang,satuan,awal,masuk,keluar,(awal+masuk+keluar) as akhir,harga
							from
							(select barang,satuan,awal,(beli+returjual+trfin+produksi+konversiin) as masuk,(jual+returbeli+trfout+pemakaian+koreksi+konversiout) as keluar,harga
							from
							(select 
							(
							select distinct aa.productname 
							from productstockdet a
							join product aa on aa.productid = a.productid
							join sloc ab on ab.slocid = a.slocid
							where a.productid = t.productid and
							a.unitofmeasureid = t.uomid
							) as barang,
							(
							select distinct bb.uomcode 
							from productstockdet b
							join unitofmeasure bb on bb.unitofmeasureid = b.unitofmeasureid
							join sloc ba on ba.slocid = b.slocid
							where b.productid = t.productid and
							b.unitofmeasureid = t.uomid
							) as satuan,
							(
							select ifnull(sum(aw.qty),0) 
							from productstockdet aw
							join sloc aaw on aaw.slocid = aw.slocid
							where aw.productid = t.productid and
							aw.transdate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and
							aw.slocid = t.slocid
							) as awal,
							(
							select ifnull(sum(c.qty),0) 
							from productstockdet c
							join sloc cc on cc.slocid = c.slocid
							where c.productid = t.productid and
							c.referenceno like 'GR-%' and
							c.slocid = t.slocid and
							c.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as beli,
							(
							select ifnull(sum(d.qty),0) 
							from productstockdet d
							join sloc dd on dd.slocid = d.slocid
							where d.productid = t.productid and
							d.referenceno like 'GIR-%' and
							d.slocid = t.slocid and
							d.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as returjual,
							(
							select ifnull(sum(e.qty),0) 
							from productstockdet e
							join sloc ee on ee.slocid = e.slocid
							where e.productid = t.productid and
							e.referenceno like 'TFS-%' and
							e.qty > 0 and
							e.slocid = t.slocid and
							e.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as trfin,
							(
							select ifnull(sum(f.qty),0) 
							from productstockdet f
							join sloc ff on ff.slocid = f.slocid
							where f.productid = t.productid and
							f.referenceno like 'OP-%' and
							f.qty > 0 and
							f.slocid = t.slocid and
							f.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as produksi,
							(
							select ifnull(sum(g.qty),0) 
							from productstockdet g
							join sloc gg on gg.slocid = g.slocid
							where g.productid = t.productid and
							g.referenceno like 'SJ-%' and
							g.slocid = t.slocid and
							g.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as jual,
							(
							select ifnull(sum(h.qty),0) 
							from productstockdet h
							join sloc hh on hh.slocid = h.slocid
							where h.productid = t.productid and
							h.referenceno like 'GRR-%' and
							h.slocid = t.slocid and
							h.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as returbeli,
							(
							select ifnull(sum(i.qty),0) 
							from productstockdet i
							join sloc ii on ii.slocid = i.slocid
							where i.productid = t.productid and
							i.referenceno like 'TFS-%' and
							i.qty < 0 and
							i.slocid = t.slocid and
							i.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as trfout,
							(
							select ifnull(sum(j.qty),0) 
							from productstockdet j
							join sloc jj on jj.slocid = j.slocid
							where j.productid = t.productid and
							j.referenceno like 'OP-%' and
							j.qty < 0 and
							j.slocid = t.slocid and
							j.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as pemakaian,
							(
							select ifnull(sum(k.qty),0) 
							from productstockdet k
							join sloc kk on kk.slocid = k.slocid
							where k.productid = t.productid and
							k.referenceno like 'TSO-%' and
							k.slocid = t.slocid and
							k.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as koreksi,
							(select ifnull(sum(l.qty),0) 
							from productstockdet l
							join sloc ll on ll.slocid = l.slocid
							where l.productid = t.productid and
							l.referenceno like '%konversi%' and
							l.qty > 0 and
							l.slocid = t.slocid
							and l.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as konversiin,
							(
							select ifnull(sum(m.qty),0) 
							from productstockdet m
							join sloc mm on mm.slocid = m.slocid
							where m.productid = t.productid and
							m.referenceno like '%konversi%' and
							m.qty < 0 and
							m.slocid = t.slocid and
							m.transdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							) as konversiout,
							ifnull((select q.harga 
							from dataharga q
							where q.productid=t.productid
							),0) as harga
							from productplant t
							join materialgroup u on u.materialgroupid = t.materialgroupid
							join sloc v on v.slocid = t.slocid
									where getcompanyfromsloc(v.slocid) = ".$companyid."
              and u.materialgroupid = '".$row['materialgroupid']."' 
							and v.slocid = '".$row['slocid']."' and v.sloccode like '%".$sloc."%'
							and u.materialgroupcode like '%".$materialgroup."%' order by barang) z) zz )zzz 
							where awal<>0 or masuk<>0 or keluar<>0 or akhir<>0 order by barang asc";
			$command1=$this->connection->createCommand($sql1);
			$dataReader1=$command1->queryAll();
			
			foreach ($dataReader1 as $row1)
			{
				$i+=1;
				$this->pdf->setFont('Arial','',6.5);
				$this->pdf->row(array(
					$i,$row1['barang'],$row1['satuan'],
					Yii::app()->format->formatNumber($row1['awal']),
					Yii::app()->format->formatNumber($row1['harga']/$per),
					Yii::app()->format->formatNumber(($row1['awal'] * $row1['harga']/$per)),
					Yii::app()->format->formatNumber($row1['masuk']),
					Yii::app()->format->formatNumber(($row1['masuk'] * $row1['harga']/$per)),
					Yii::app()->format->formatNumber(($row1['awal'] + $row1['masuk'])),
					Yii::app()->format->formatNumber($row1['harga']/$per),
					Yii::app()->format->formatNumber((($row1['awal'] + $row1['masuk']) * $row1['harga']/$per)),
					Yii::app()->format->formatNumber($row1['keluar']),
					Yii::app()->format->formatNumber(($row1['keluar'] * $row1['harga']/$per)),
					Yii::app()->format->formatNumber($row1['akhir']),
					Yii::app()->format->formatNumber(($row1['akhir'] * $row1['harga']/$per)),
					$row1['minus'],
				));
				$qtyawal += $row1['awal'];
				$nilaiawal += ($row1['awal'] * $row1['harga']/$per);
				$qtymasuk += $row1['masuk'];
				$nilaimasuk += ($row1['masuk'] * $row1['harga']/$per);
				$qtytersedia += ($row1['awal'] + $row1['masuk']);
				$nilaitersedia += (($row1['awal'] + $row1['masuk']) * $row1['harga']/$per);
				$qtykeluar += $row1['keluar'];
				$nilaikeluar += ($row1['keluar'] * $row1['harga']/$per);
				$qtyakhir += $row1['akhir'];
				$nilaiakhir += ($row1['akhir'] * $row1['harga']/$per);
			}
			$this->pdf->setFont('Arial','B',6.5);
			$this->pdf->setwidths(array(98,15,23,23,15,23,15,23,23,15,23,15,23,5));
			$this->pdf->coldetailalign = array('R','R','R','R','R','R','R','R','R','R','R','R','R','C');
			$this->pdf->row(array(
				'TOTAL '.$row['sloccode'].' - '.$row['description'].'      >>>>>',
				Yii::app()->format->formatNumber($qtyawal),'',
				Yii::app()->format->formatNumber($nilaiawal),
				Yii::app()->format->formatNumber($qtymasuk),
				Yii::app()->format->formatNumber($nilaimasuk),
				Yii::app()->format->formatNumber($qtytersedia),'',
				Yii::app()->format->formatNumber($nilaitersedia),
				Yii::app()->format->formatNumber($qtykeluar),
				Yii::app()->format->formatNumber($nilaikeluar),
				Yii::app()->format->formatNumber($qtyakhir),
				Yii::app()->format->formatNumber($nilaiakhir),'',
			));
			$qtyawal2 += $qtyawal;
			$nilaiawal2 += $nilaiawal;
			$qtymasuk2 += $qtymasuk;
			$nilaimasuk2 += $nilaimasuk;
			$qtytersedia2 += $qtytersedia;
			$nilaitersedia2 += $nilaitersedia;
			$qtykeluar2 += $qtykeluar;
			$nilaikeluar2 += $nilaikeluar;
			$qtyakhir2 += $qtyakhir;
			$nilaiakhir2 += $nilaiakhir;
			
			$this->pdf->sety($this->pdf->gety()+5);
			$this->pdf->checkNewPage(175);
		}
		$this->pdf->setFont('Arial','BI',6.5);
		$this->pdf->colalign = array('C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(69,68,43,68,43,43,5));
		$this->pdf->colheader = array('','Awal','Penerimaan','Tersedia','Pengeluaran','Akhir','');
		$this->pdf->RowHeader();
		$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(69,18,25,25,18,25,18,25,25,18,25,18,25,5));
		$this->pdf->colheader = array('Keterangan','Qty','Harga','Nilai','Qty','Nilai','Qty','Harga','Nilai','Qty','Nilai','Qty','Nilai','');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('C','R','R','R','R','R','R','R','R','R','R','R','R','C');
		$this->pdf->row(array(
			'GRAND TOTAL      >>>>>',
			Yii::app()->format->formatNumber($qtyawal2),'',
			Yii::app()->format->formatNumber($nilaiawal2),
			Yii::app()->format->formatNumber($qtymasuk2),
			Yii::app()->format->formatNumber($nilaimasuk2),
			Yii::app()->format->formatNumber($qtytersedia2),'',
			Yii::app()->format->formatNumber($nilaitersedia2),
			Yii::app()->format->formatNumber($qtykeluar2),
			Yii::app()->format->formatNumber($nilaikeluar2),
			Yii::app()->format->formatNumber($qtyakhir2),
			Yii::app()->format->formatNumber($nilaiakhir2),'',
		));
		
		$this->pdf->Output();
	}
	*/
    //4
	public function Hpp($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$sql = "select distinct a.materialgroupid,a.description
				from materialgroup a
				join productplant b on b.materialgroupid = a.materialgroupid
				join sloc c on c.slocid = b.slocid
				join plant d on d.plantid = c.plantid
				join product e on e.productid = b.productid
				where d.companyid = '".$companyid."'";

			$command=$this->connection->createCommand($sql);
			$dataReader=$command->queryAll();
			
			foreach($dataReader as $row)
			{
				$this->pdf->companyid = $companyid;
			}
			$this->pdf->title='Harga Pokok Produksi';
			$this->pdf->subtitle = 'Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->AddPage('P');
			
			foreach($dataReader as $row)
			{
				$this->pdf->SetFont('Arial','',10);
				$this->pdf->text(10,$this->pdf->gety()+10,'Divisi');$this->pdf->text(30,$this->pdf->gety()+10,': '.$row['description']);
				$sql1 = "select distinct b.productname,f.description as uom,ifnull(a.averageprice,0) as hpp
					from productdetail a
					join product b on b.productid = a.productid
					join sloc c on c.slocid = a.slocid
					join productplant d on d.productid = a.productid and d.slocid = a.slocid and d.uomid = a.unitofmeasureid
					join materialgroup e on e.materialgroupid = d.materialgroupid
					join unitofmeasure f on f.unitofmeasureid = a.unitofmeasureid
					where e.materialgroupid = '".$row['materialgroupid']."'";
				$command1=$this->connection->createCommand($sql1);
				$dataReader1=$command1->queryAll();
				$totalqty=0;$i=0;
				$this->pdf->sety($this->pdf->gety()+15);
				$this->pdf->setFont('Arial','B',8);
				$this->pdf->colalign = array('C','C','C','C');
				$this->pdf->setwidths(array(10,120,30,30));
				$this->pdf->colheader = array('No','Nama Barang','Satuan','HPP');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('L','L','C','R');
				$this->pdf->setFont('Arial','',8);
				foreach($dataReader1 as $row1)
				{
					$i+=1;
					$this->pdf->row(array(
						$i,$row1['productname'],
						$row1['uom'],
						Yii::app()->format->formatCurrency($row1['hpp']/$per)
					));
				}
				$this->pdf->checkPageBreak(20);
			}
			$this->pdf->Output();
	}
    //5
	public function HppBillOfMaterial($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per)
    {
            parent::actionDownload();
            $sql = "select distinct d.slocid,d.sloccode
					from bomdetail a
					join billofmaterial b on b.bomid=a.bomid
					join productplant aa on aa.productid=a.productid
					join productplant bb on bb.productid=b.productid
					join product aaa on aaa.productid=a.productid
					join product bbb on bbb.productid=b.productid
					left join productdetail c on c.productid=a.productid and c.slocid=aa.slocid and c.unitofmeasureid=aa.uomid
					left join sloc d on d.slocid=aa.slocid
					left join plant e on e.plantid=d.plantid
					left join company f on f.companyid=e.companyid
					left join unitofmeasure g on g.unitofmeasureid=b.uomid
					where f.companyid='".$companyid."' and bb.issource=1 and bbb.productname like '%".$product."%' and d.sloccode like '%".$sloc."%' and bb.slocid=aa.slocid order by slocid";
            $command=$this->connection->createCommand($sql);
            $dataReader=$command->queryAll();
            
            foreach($dataReader as $row)
            {
                $this->pdf->companyid = $companyid;
            }
            $this->pdf->title='HPP Berdasarkan BOM';
            $this->pdf->subtitle = 'Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
            $this->pdf->AddPage('P');

            $this->pdf->sety($this->pdf->gety());
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->colalign = array('C','C','C','C','C');
			$this->pdf->setwidths(array(110,20,20,20,20));
			$this->pdf->colheader = array('Nama Barang','Satuan','Qty','Price','Jumlah');
			$this->pdf->RowHeader();
            
            foreach($dataReader as $row)
            {
                $this->pdf->SetFont('Arial','B',10);                
				$this->pdf->text(10,$this->pdf->gety()+5,'GUDANG');$this->pdf->text(28,$this->pdf->gety()+5,': '.$row['sloccode']);
                $sql1 = "select distinct bbb.productid,bbb.productname,g.uomcode as uomname
						from bomdetail a
						join billofmaterial b on b.bomid=a.bomid
						join productplant aa on aa.productid=a.productid
						join productplant bb on bb.productid=b.productid
						join product aaa on aaa.productid=a.productid
						join product bbb on bbb.productid=b.productid
						left join productdetail c on c.productid=a.productid and c.slocid=aa.slocid and c.unitofmeasureid=aa.uomid
						left join sloc d on d.slocid=aa.slocid
						left join plant e on e.plantid=d.plantid
						left join company f on f.companyid=e.companyid
						left join unitofmeasure g on g.unitofmeasureid=b.uomid
						where f.companyid=".$companyid." and bb.issource=1 and bbb.productname like '%".$product."%' and bb.slocid='".$row['slocid']."' and bb.slocid=aa.slocid order by productname";
                $command1=$this->connection->createCommand($sql1);
                $dataReader1=$command1->queryAll();
                $this->pdf->sety($this->pdf->gety()+5);
                foreach($dataReader1 as $row1)
				
			{
                $sql2 = "select bbb.productname,bbb.productid,g.description as uomname,d.sloccode,sum(a.qty*ifnull(c.averageprice,1)) as total
						from bomdetail a
						join billofmaterial b on b.bomid=a.bomid
						join productplant aa on aa.productid=a.productid
						join productplant bb on bb.productid=b.productid
						join product aaa on aaa.productid=a.productid
						join product bbb on bbb.productid=b.productid
						left join productdetail c on c.productid=a.productid and c.slocid=aa.slocid and c.unitofmeasureid=aa.uomid
						left join sloc d on d.slocid=aa.slocid
						left join plant e on e.plantid=d.plantid
						left join company f on f.companyid=e.companyid
						left join unitofmeasure g on g.unitofmeasureid=b.uomid
						where f.companyid=".$companyid." and bb.issource=1 and bb.slocid='".$row['slocid']."' and b.productid='".$row1['productid']."' and bb.slocid=aa.slocid";
                $command2=$this->connection->createCommand($sql2);
                $dataReader2=$command2->queryAll();
                $this->pdf->sety($this->pdf->gety());
                foreach($dataReader2 as $row2)
				
			{
                $this->pdf->SetFont('Arial','BI',9);                
				$this->pdf->text(10,$this->pdf->gety()+5,$row2['productname']);$this->pdf->text(150,$this->pdf->gety()+5,$row2['uomname']);
				$this->pdf->text(180,$this->pdf->gety()+5,Yii::app()->format->formatNumber($row2['total']/$per));
                $sql3 = "select distinct aaa.productname,c.slocid,aaa.isstock,g.description as uomname,ifnull(a.qty*aaa.isstock,0) as qty,ifnull(c.averageprice,a.qty) as price,ifnull(a.qty*c.averageprice,ifnull(c.averageprice,a.qty)) as jumlah
							from bomdetail a
							join billofmaterial b on b.bomid=a.bomid
							join productplant aa on aa.productid=a.productid
							join productplant bb on bb.productid=b.productid
							join product aaa on aaa.productid=a.productid
							join product bbb on bbb.productid=b.productid
							left join productdetail c on c.productid=a.productid and c.slocid=aa.slocid and c.unitofmeasureid=aa.uomid
							left join sloc d on d.slocid=aa.slocid
							left join plant e on e.plantid=d.plantid
							left join company f on f.companyid=e.companyid
							left join unitofmeasure g on g.unitofmeasureid=a.uomid
							where f.companyid=".$companyid." and bb.issource=1 and bb.slocid='".$row['slocid']."' and b.productid='".$row1['productid']."' and bb.slocid=aa.slocid order by isstock desc,productname";
                $this->pdf->sety($this->pdf->gety()+7);
                $this->pdf->coldetailalign = array('L','C','R','R','R');
                $this->pdf->setFont('Arial','',8);
                $command3=$this->connection->createCommand($sql3);
                $dataReader3=$command3->queryAll();
                
                foreach($dataReader3 as $row3)
                {
                   $this->pdf->row(array(
                                $row3['productname'],$row3['uomname'],
                                Yii::app()->format->formatNumber($row3['qty']),
                                Yii::app()->format->formatNumber($row3['price']/$per),
                                Yii::app()->format->formatNumber($row3['jumlah']/$per),
                        ));
                }
				$this->pdf->sety($this->pdf->gety()+5);
                    $this->pdf->checkPageBreak(10);
            }}}
                $this->pdf->Output();
  
	}
    //6
	public function RincianNilaiPemakaianStok($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per)
	//Masih menggunakan dataharga
    {
		parent::actionDownload();
		$sql = "select e.slocid as fromslocid,e.sloccode as fromsloccode, e.description as fromslocdesc,
						f.slocid as toslocid, f.sloccode as tosloccode, f.description as toslocdesc
						from productoutputdetail a
						join product b on b.productid = a.productid
						join productoutput c on c.productoutputid = a.productoutputid
						join unitofmeasure d on d.unitofmeasureid = a.uomid
						join sloc e on e.slocid=a.fromslocid
						join sloc f on f.slocid=a.toslocid
						join productplan g on g.productplanid=c.productplanid
						where c.recordstatus = 3 and g.plantid = ".$plantid." and e.sloccode like '%".$sloc."%' and f.sloccode like '%".$sloc."%'
						and c.productoutputdate between 
						'".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and 
						'".date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
						group by e.slocid,f.slocid
						order by e.sloccode,f.sloccode";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		
		foreach($dataReader as $row)
		{
				$this->pdf->companyid = $companyid;
		}
		$this->pdf->title='Rincian Nilai Pemakaian Stok';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');

		foreach($dataReader as $row)
		{
			$this->pdf->SetFont('Arial','',10);
			$this->pdf->text(10,$this->pdf->gety()+5,'Asal');$this->pdf->text(30,$this->pdf->gety()+5,': '.$row['fromsloccode'].' - '.$row['fromslocdesc']);
			$this->pdf->text(10,$this->pdf->gety()+10,'Tujuan');$this->pdf->text(30,$this->pdf->gety()+10,': '.$row['tosloccode'].' - '.$row['toslocdesc']);
			
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->sety($this->pdf->gety()+12);
			$this->pdf->colalign = array('C','C','C','C','C','C');
			$this->pdf->setwidths(array(10,90,15,20,30,30));
			$this->pdf->colheader = array('No','Nama Barang','Satuan','Qty','Harga','Jumlah');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('R','L','L','R','R','R');
			
			$i=0;$total=0;
			$sql1 = "select e.sloccode as fromsloccode, f.sloccode as tosloccode, b.productname, a.qty, d.uomcode,
						(select ifnull(z.harga,0) from dataharga z where z.productid=a.productid and z.uom=a.uomid) as harga
						from productoutputdetail a
						join product b on b.productid = a.productid
						join productoutput c on c.productoutputid = a.productoutputid
						join unitofmeasure d on d.unitofmeasureid = a.uomid
						join sloc e on e.slocid=a.fromslocid
						join sloc f on f.slocid=a.toslocid
						join productplan g on g.productplanid=c.productplanid
						where c.recordstatus = 3 and g.plantid = ".$plantid." and e.slocid = '".$row['fromslocid']."' and f.slocid = '".$row['toslocid']."'
						and c.productoutputdate between 
						'".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and 
						'".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
			$command1=$this->connection->createCommand($sql1);
			$dataReader1=$command1->queryAll();
			
			foreach($dataReader1 as $row1)
			{
				$i+=1;
				$this->pdf->setFont('Arial','',8);
				$this->pdf->row(array(
					$i,$row1['productname'],
					$row1['uomcode'],
					Yii::app()->format->formatCurrency($row1['qty']),
					Yii::app()->format->formatCurrency($row1['harga']/$per),
					Yii::app()->format->formatCurrency($row1['qty'] * $row1['harga']/$per),
				));
				$total += ($row1['qty'] * $row1['harga']/$per);
			}
			/*$this->pdf->setFont('Arial','BI',9);
			$this->pdf->colalign = array('C','C','C');
			$this->pdf->setwidths(array(10,155,30));
			$this->pdf->coldetailalign = array('R','R','R');
			$this->pdf->row(array(
				'','TOTAL PEMAKAIAN '.$row['fromsloccode'].' - '.$row['tosloccode'],
				Yii::app()->format->formatCurrency($total),
			));
			$this->pdf->sety($this->pdf->gety()+10);*/
			$this->pdf->checkPageBreak(10);
		}
		
				
		$this->pdf->Output();
	}
	//7
	public function RekapNilaiPemakaianStok($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per)
    //Masih menggunakan dataharga
	{
		parent::actionDownload();
		$i=0;$total=0;
		$sql = "select e.slocid as fromslocid,e.sloccode as fromsloccode, e.description as fromslocdesc,
						f.slocid as toslocid, f.sloccode as tosloccode, f.description as toslocdesc,
						sum(ifnull(a.qty,0)*(select ifnull(z.harga,0) from dataharga z where z.productid=a.productid and z.uom=a.uomid)) as jumlah
						from productoutputdetail a
						join product b on b.productid = a.productid
						join productoutput c on c.productoutputid = a.productoutputid
						join unitofmeasure d on d.unitofmeasureid = a.uomid
						join sloc e on e.slocid=a.fromslocid
						join sloc f on f.slocid=a.toslocid
						join productplan g on g.productplanid=c.productplanid
						where c.recordstatus = 3 and g.plantid = ".$plantid." and e.sloccode like '%".$sloc."%' and f.sloccode like '%".$sloc."%'
						and c.productoutputdate between 
						'".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and 
						'".date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
						group by e.slocid,f.slocid
						order by e.sloccode,f.sloccode";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		
		foreach($dataReader as $row)
		{
				$this->pdf->companyid = $companyid;
		}
		$this->pdf->title='Rekap Nilai Pemakaian Stok';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');
		
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->sety($this->pdf->gety()+0);
		$this->pdf->colalign = array('C','C','C','C');
		$this->pdf->setwidths(array(10,70,70,35));
		$this->pdf->colheader = array('No','Gudang Asal','Gudang Tujuan','Jumlah');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('R','L','L','R');

		foreach($dataReader as $row)
		{
			$i+=1;
			$this->pdf->setFont('Arial','',8);
			$this->pdf->row(array(
				$i,
				$row['fromsloccode'].' - '.$row['fromslocdesc'],
				$row['tosloccode'].' - '.$row['toslocdesc'],
				Yii::app()->format->formatCurrency($row['jumlah']/$per),
			));
			$total += $row['jumlah']/$per;			
		}
		/*$this->pdf->setFont('Arial','BI',9);
		$this->pdf->row(array(
			'',
			'',
			'Total Pemakaian',
			Yii::app()->format->formatCurrency($total),
		));*/
		
		$this->pdf->checkPageBreak(10);		
		$this->pdf->Output();
	}
	//8
	public function RincianNilaiStockOpname($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$total2=0;
		$sql = "select distinct f.slocid,f.sloccode,f.description
						from bsdetail a
						join product b on b.productid=a.productid
						join unitofmeasure c on c.unitofmeasureid=a.unitofmeasureid
						join storagebin d on d.storagebinid=a.storagebinid
						join bsheader e on e.bsheaderid=a.bsheaderid
						join sloc f on f.slocid=e.slocid
						where e.recordstatus=5 and getcompanyfromsloc(f.slocid) = ".$companyid." and f.sloccode like '%".$sloc."%' 
						and b.productname like '%".$product."%' 
						and e.bsdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
						order by sloccode";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		
		foreach($dataReader as $row)
		{
				$this->pdf->companyid = $companyid;
		}
		$this->pdf->title='Rincian Nilai Stock Opname';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');

		foreach($dataReader as $row)
		{
			$this->pdf->SetFont('Arial','',10);
			$this->pdf->text(10,$this->pdf->gety()+5,'Gudang');$this->pdf->text(30,$this->pdf->gety()+5,': '.$row['sloccode'].' - '.$row['description']);
			
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->sety($this->pdf->gety()+12);
			$this->pdf->colalign = array('C','C','C','C','C','C');
			$this->pdf->setwidths(array(10,90,15,20,30,30));
			$this->pdf->colheader = array('No','Nama Barang','Satuan','Qty','Harga','Jumlah');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('R','L','L','R','R','R');
			
			$i=0;$total=0;
			$sql1 = "select b.productname,c.uomcode,sum(a.qty) as qty,sum(a.qty*a.averageprice)/sum(a.qty) as harga,sum(a.qty*a.averageprice) as jumlah
						from bsdetail a
						join product b on b.productid=a.productid
						join unitofmeasure c on c.unitofmeasureid=a.unitofmeasureid
						join storagebin d on d.storagebinid=a.storagebinid
						join bsheader e on e.bsheaderid=a.bsheaderid
						join sloc f on f.slocid=e.slocid
						where e.recordstatus=5 and f.slocid = '".$row['slocid']."' and f.sloccode like '%".$sloc."%' 
						and b.productname like '%".$product."%' 
						and e.bsdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
						group by productname";
			$command1=$this->connection->createCommand($sql1);
			$dataReader1=$command1->queryAll();
			
			foreach($dataReader1 as $row1)
			{
				$i+=1;
				$this->pdf->setFont('Arial','',8);
				$this->pdf->row(array(
					$i,$row1['productname'],
					$row1['uomcode'],
					Yii::app()->format->formatCurrency($row1['qty']),
					Yii::app()->format->formatCurrency($row1['harga']/$per),
					Yii::app()->format->formatCurrency($row1['jumlah']/$per),
				));
				$total += ($row1['jumlah']/$per);
			}
			$this->pdf->setFont('Arial','BI',9);
			$this->pdf->colalign = array('C','C','C');
			$this->pdf->setwidths(array(10,155,30));
			$this->pdf->coldetailalign = array('R','R','R');
			$this->pdf->row(array(
				'','TOTAL KOREKSI '.$row['sloccode'],
				Yii::app()->format->formatCurrency($total),
			));
			$total2 += $total;
			$this->pdf->sety($this->pdf->gety()+5);
			$this->pdf->checkPageBreak(10);
		}
		$this->pdf->setFont('Arial','BI',9);
		$this->pdf->colalign = array('C','C','C');
		$this->pdf->setwidths(array(10,155,30));
		$this->pdf->coldetailalign = array('R','R','R');
		$this->pdf->row(array(
			'','GRAND TOTAL KOREKSI ',
			Yii::app()->format->formatCurrency($total2),
		));
		$this->pdf->sety($this->pdf->gety()+5);
		$this->pdf->checkPageBreak(10);
				
		$this->pdf->Output();
	}
	//9
	public function RekapNilaiStockOpname($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$i=0;$total=0;
		$sql = "select distinct f.slocid,f.sloccode,f.description,sum(a.qty*a.averageprice) as jumlah
						from bsdetail a
						join product b on b.productid=a.productid
						join unitofmeasure c on c.unitofmeasureid=a.uomid
						join storagebin d on d.storagebinid=a.storagebinid
						join bsheader e on e.bsheaderid=a.bsheaderid
						join sloc f on f.slocid=e.slocid
						where e.recordstatus=4 and getcompanyfromsloc(f.slocid) = ".$companyid." and f.sloccode like '%".$sloc."%' 
						and b.productname like '%".$product."%' 
						and e.bsdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
						group by slocid order by sloccode";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		
		foreach($dataReader as $row)
		{
				$this->pdf->companyid = $companyid;
		}
		$this->pdf->title='Rekap Nilai Stock Opname';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');
		
		$this->pdf->setFont('Arial','B',8);
			$this->pdf->sety($this->pdf->gety()+0);
			$this->pdf->colalign = array('C','C','C');
			$this->pdf->setwidths(array(10,130,35));
			$this->pdf->colheader = array('No','Nama Gudang','Jumlah');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('R','L','R');

		foreach($dataReader as $row)
		{
			$i+=1;
			$this->pdf->setFont('Arial','',8);
			$this->pdf->row(array(
				$i,$row['sloccode'].' - '.$row['description'],
				Yii::app()->format->formatCurrency($row['jumlah']/$per),
			));
			$total += ($row['jumlah']/$per);
		}
		$this->pdf->setFont('Arial','BI',9);
		$this->pdf->colalign = array('C','C','C');
		$this->pdf->setwidths(array(10,135,30));
		$this->pdf->coldetailalign = array('R','R','R');
		$this->pdf->row(array(
			'','TOTAL KOREKSI ',
			Yii::app()->format->formatCurrency($total),
		));
		$this->pdf->sety($this->pdf->gety()+5);
		$this->pdf->checkPageBreak(10);
				
		$this->pdf->Output();
	}
	//10
	public function RincianHargaPokokPenjualan($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per)
    //Masih menggunakan dataharga
	{
		parent::actionDownload();
		$total2=0;
		$sql = "select distinct d.slocid,d.sloccode,d.description
						from gidetail a
						left join giheader b on b.giheaderid=a.giheaderid
						left join product c on c.productid=a.productid
						left join sloc d on d.slocid=a.slocid
						left join soheader e on e.soheaderid=b.soheaderid
						where b.recordstatus = 3 and c.isstock = 1 and e.plantid = ".$plantid." and d.sloccode like '%".$sloc."%' 
						and c.productname like '%".$product."%'and b.gidate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
						order by slocid";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		
		foreach($dataReader as $row)
		{
				$this->pdf->companyid = $companyid;
		}
		$this->pdf->title='Rincian Harga Pokok Penjualan';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');

		foreach($dataReader as $row)
		{
			$this->pdf->SetFont('Arial','',10);
			$this->pdf->text(10,$this->pdf->gety()+5,'Gudang');$this->pdf->text(30,$this->pdf->gety()+5,': '.$row['sloccode'].' - '.$row['description']);
			
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->sety($this->pdf->gety()+12);
			$this->pdf->colalign = array('C','C','C','C','C','C');
			$this->pdf->setwidths(array(10,90,15,20,30,30));
			$this->pdf->colheader = array('No','Nama Barang','Satuan','Qty','Harga','Jumlah');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('R','L','C','R','R','R');
			
			$i=0;$total=0;
			$sql1 = "select c.productname,f.uomcode,sum(ifnull(a.qty,0)) as qty,
						ifnull((select z.harga from dataharga z where z.productid=a.productid and z.uom=a.unitofmeasureid),0) as harga,
						sum(ifnull(a.qty,0)*
						ifnull((select z.harga from dataharga z where z.productid=a.productid and z.uom=a.unitofmeasureid),0)) as jumlah
						from gidetail a
						left join giheader b on b.giheaderid=a.giheaderid
						left join product c on c.productid=a.productid
						left join sloc d on d.slocid=a.slocid
						left join soheader e on e.soheaderid=b.soheaderid
						left join unitofmeasure f on f.unitofmeasureid=a.unitofmeasureid
						where b.recordstatus=3 and c.isstock=1 and d.slocid = '".$row['slocid']."' and d.sloccode like '%".$sloc."%' 
						and c.productname like '%".$product."%' 
						and b.gidate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'					
						group by productname";
			$command1=$this->connection->createCommand($sql1);
			$dataReader1=$command1->queryAll();
			
			foreach($dataReader1 as $row1)
			{
				$i+=1;
				$this->pdf->setFont('Arial','',8);
				$this->pdf->row(array(
					$i,$row1['productname'],
					$row1['uomcode'],
					Yii::app()->format->formatCurrency($row1['qty']),
					Yii::app()->format->formatCurrency($row1['harga']/$per),
					Yii::app()->format->formatCurrency($row1['jumlah']/$per),
				));
				$total += ($row1['jumlah']/$per);
			}
			$this->pdf->setFont('Arial','BI',9);
			$this->pdf->colalign = array('C','C','C');
			$this->pdf->setwidths(array(10,155,30));
			$this->pdf->coldetailalign = array('R','R','R');
			$this->pdf->row(array(
				'','TOTAL HPP '.$row['sloccode'],
				Yii::app()->format->formatCurrency($total),
			));
			$total2 += $total;
			$this->pdf->sety($this->pdf->gety()+5);
			$this->pdf->checkPageBreak(10);
		}
		$this->pdf->setFont('Arial','BI',9);
		$this->pdf->colalign = array('C','C','C');
		$this->pdf->setwidths(array(10,155,30));
		$this->pdf->coldetailalign = array('R','R','R');
		$this->pdf->row(array(
			'','GRAND TOTAL HPP ',
			Yii::app()->format->formatCurrency($total2),
		));
		$this->pdf->sety($this->pdf->gety()+5);
		$this->pdf->checkPageBreak(10);
				
		$this->pdf->Output();
	}
	//11
	public function RekapHargaPokokPenjualan($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per)
    //Masih menggunakan dataharga
	{
		parent::actionDownload();
		$i=0;$total=0;
		$sql = "select distinct d.slocid,d.sloccode,d.description,sum(ifnull(a.qty,0)*
						ifnull((select z.harga from dataharga z where z.productid=a.productid and z.uom=a.unitofmeasureid),0)) as jumlah
						from gidetail a
						left join giheader b on b.giheaderid=a.giheaderid
						left join product c on c.productid=a.productid
						left join sloc d on d.slocid=a.slocid
						left join soheader e on e.soheaderid=b.soheaderid
						where b.recordstatus = 3 and c.isstock = 1 and e.plantid = ".$plantid." and d.sloccode like '%".$sloc."%' 
						and c.productname like '%".$product."%'and b.gidate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
						group by slocid order by slocid";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		
		foreach($dataReader as $row)
		{
				$this->pdf->companyid = $companyid;
		}
		$this->pdf->title='Rekap Harga Pokok Penjualan';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');
		
		$this->pdf->setFont('Arial','B',8);
			$this->pdf->sety($this->pdf->gety()+0);
			$this->pdf->colalign = array('C','C','C');
			$this->pdf->setwidths(array(10,130,35));
			$this->pdf->colheader = array('No','Nama Gudang','Jumlah');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('R','L','R');

		foreach($dataReader as $row)
		{
			$i+=1;
			$this->pdf->setFont('Arial','',8);
			$this->pdf->row(array(
				$i,$row['sloccode'].' - '.$row['description'],
				Yii::app()->format->formatCurrency($row['jumlah']/$per),
			));
			$total += ($row['jumlah']/$per);
		}
		$this->pdf->setFont('Arial','BI',9);
		$this->pdf->colalign = array('C','C','C');
		$this->pdf->setwidths(array(10,135,30));
		$this->pdf->coldetailalign = array('R','R','R');
		$this->pdf->row(array(
			'','TOTAL KOREKSI ',
			Yii::app()->format->formatCurrency($total),
		));
		$this->pdf->sety($this->pdf->gety()+5);
		$this->pdf->checkPageBreak(10);
				
		$this->pdf->Output();
	}
	//13
	public function RekapPerbandinganHPPPenjualanPerDokumen($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$i=0;$totalhpp=0;$totaljouhpp=0;$totalinv=0;$totaljouinv=0;
		$sql = "select *,ifnull((100-((nilaihpp/nilaiinvoice)*100)),0) as gm
                from (select a.gino,
                ifnull((select sum(-c.qty*c.averageprice)
                from productstockdet c
                join sloc d on d.slocid=c.slocid
                join plant e on e.plantid=d.plantid
                where c.referenceno=a.gino and e.companyid=b.companyid),0) as nilaihpp,
                (select f.journalno
                from genjournal f
                where f.referenceno=a.gino and f.companyid=b.companyid) as journalhppno,
                ifnull((select sum(g.debit)
                from genledger g
                join genjournal h on h.genjournalid=g.genjournalid
                where h.referenceno=a.gino and h.companyid=b.companyid),0) as nilaijournalhpp,
                replace(a.gino,'SJ','INV') as invoiceno,
                ifnull((select i.amount
                from invoice i
                where i.recordstatus = 3 and i.giheaderid= a.giheaderid),0) as nilaiinvoice,
                (select j.journalno
                from genjournal j
                where j.referenceno=replace(a.gino,'SJ','INV') and j.companyid=b.companyid) as journalinvoiceno,
                ifnull((select sum(k.debit)
                from genledger k
                join genjournal l on l.genjournalid=k.genjournalid
                where l.referenceno=replace(a.gino,'SJ','INV') and l.companyid=b.companyid),0) as nilaijournalinvoice
                from giheader a
                join soheader b on b.soheaderid=a.soheaderid
                where a.recordstatus=3 and b.plantid = ".$plantid."
                and a.gidate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		
		foreach($dataReader as $row)
		{
				$this->pdf->companyid = $companyid;
		}
		$this->pdf->title='Rekap Perbandingan Nilai HPP, Nilai Penjualan dan Nilai Jurnal Per Dokumen';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');
		
		$this->pdf->setFont('Arial','B',8);
			$this->pdf->sety($this->pdf->gety()+0);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(10,20,23,20,23,21,23,20,23,15));
			$this->pdf->colheader = array('No','Surat Jalan','Nilai HPP SJ','Jurnal SJ','Nilai Jurnal SJ','Invoice','Nilai Invoice','Jurnal INV','Nilai Jurnal INV','GM');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('R','C','R','C','R','C','R','C','R','R');

		foreach($dataReader as $row)
		{
			$i+=1;
			$this->pdf->setFont('Arial','',8);
			$this->pdf->row(array(
				$i,
                $row['gino'],
				Yii::app()->format->formatCurrency($row['nilaihpp']/$per),
				$row['journalhppno'],
				Yii::app()->format->formatCurrency($row['nilaijournalhpp']/$per),
				$row['invoiceno'],
				Yii::app()->format->formatCurrency($row['nilaiinvoice']/$per),
				$row['journalinvoiceno'],
				Yii::app()->format->formatCurrency($row['nilaijournalinvoice']/$per),
				Yii::app()->format->formatCurrency($row['gm']),
			));            
			$totalhpp += ($row['nilaihpp']/$per);
			$totaljouhpp += ($row['nilaijournalhpp']/$per);
			$totalinv += ($row['nilaiinvoice']/$per);
			$totaljouinv += ($row['nilaijournalinvoice']/$per);
		}
		$this->pdf->sety($this->pdf->gety()+5);
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->row(array(
			'',
			'',
			Yii::app()->format->formatCurrency($totalhpp),
			'',
			Yii::app()->format->formatCurrency($totaljouhpp),
			'',
			Yii::app()->format->formatCurrency($totalinv),
			'',
			Yii::app()->format->formatCurrency($totaljouinv),
			Yii::app()->format->formatCurrency(100-(($totalhpp/$totalinv)*100)),
		)); 
		$this->pdf->Output();
	}
	//14
	public function RekapPerbandinganHPPReturPenjualanPerDokumen($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per)
	{
		parent::actionDownload();

		$i=0;$totalhpp=0;$totaljouhpp=0;$totalinv=0;$totaljouinv=0;
		$sql = "select *,ifnull(100-((nilaihpp/nilaiinvoice)*100),0) as gm
                from (select m.gireturno,
                ifnull((select sum(c.qty*c.averageprice)
                from productstockdet c
                join sloc d on d.slocid=c.slocid
                join plant e on e.plantid=d.plantid
                where c.referenceno=m.gireturno and e.companyid=b.companyid),0) as nilaihpp,
                (select f.journalno
                from genjournal f
                where f.referenceno=m.gireturno and f.companyid=b.companyid) as journalhppno,
                ifnull((select sum(g.debit)
                from genledger g
                join genjournal h on h.genjournalid=g.genjournalid
                where h.referenceno=m.gireturno and h.companyid=b.companyid),0) as nilaijournalhpp,
                replace(m.gireturno,'GIR','NGIR') as invoiceno,
                ifnull((select sum(i.qty*i.price)
                from notagirpro i
                join notagir n on n.notagirid=i.notagirid
                where n.recordstatus=3 and n.gireturid= m.gireturid),0) as nilaiinvoice,
                (select j.journalno
                from genjournal j
                where j.referenceno=replace(m.gireturno,'GIR','NGIR') and j.companyid=b.companyid) as journalinvoiceno,
                ifnull((select sum(k.debit)
                from genledger k
                join genjournal l on l.genjournalid=k.genjournalid
                where l.referenceno=replace(m.gireturno,'GIR','NGIR') and l.companyid=b.companyid),0) as nilaijournalinvoice
                from giretur m
                join giheader a on a.giheaderid=m.giheaderid
                join soheader b on b.soheaderid=a.soheaderid
                where a.recordstatus=3 and b.plantid = ".$plantid."
                and a.gidate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		
		foreach($dataReader as $row)
		{
				$this->pdf->companyid = $companyid;
		}
		$this->pdf->title='Rekap Perbandingan Nilai HPP, Nilai Retur Penjualan dan Nilai Jurnal Per Dokumen';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');
		
		$this->pdf->setFont('Arial','B',8);
			$this->pdf->sety($this->pdf->gety()+0);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(8,20,23,20,23,22,23,20,23,15));
			$this->pdf->colheader = array('No','Surat Jalan','Nilai HPP SJ','Jurnal SJ','Nilai Jurnal SJ','Invoice','Nilai Invoice','Jurnal INV','Nilai Jurnal INV','GM');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('R','C','R','C','R','C','R','C','R','R');

		foreach($dataReader as $row)
		{
			$i+=1;
			$this->pdf->setFont('Arial','',8);
			$this->pdf->row(array(
				$i,
        $row['gireturno'],
				Yii::app()->format->formatCurrency($row['nilaihpp']/$per),
				$row['journalhppno'],
				Yii::app()->format->formatCurrency($row['nilaijournalhpp']/$per),
				$row['invoiceno'],
				Yii::app()->format->formatCurrency($row['nilaiinvoice']/$per),
				$row['journalinvoiceno'],
				Yii::app()->format->formatCurrency($row['nilaijournalinvoice']/$per),
				Yii::app()->format->formatCurrency($row['gm']),
			));            
			$totalhpp += ($row['nilaihpp']/$per);
			$totaljouhpp += ($row['nilaijournalhpp']/$per);
			$totalinv += ($row['nilaiinvoice']/$per);
			$totaljouinv += ($row['nilaijournalinvoice']/$per);
		}
		$this->pdf->sety($this->pdf->gety()+5);
        $this->pdf->setFont('Arial','B',8);
			$this->pdf->row(array(
				'',
                '',
				Yii::app()->format->formatCurrency($totalhpp),
				'',
				Yii::app()->format->formatCurrency($totaljouhpp),
				'',
				Yii::app()->format->formatCurrency($totalinv),
				'',
				Yii::app()->format->formatCurrency($totaljouinv),
				Yii::app()->format->formatCurrency(100-(($totalhpp/$totalinv)*100)),
			)); 
		$this->pdf->Output();
	}
	//15
	public function RekapPerbandinganHPPPenjualanPerBarang($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per)
	{
		parent::actionDownload();

		$i=0;$totalqty=0;$totalhpp=0;$totalinvoice=0;
		$sql = "select productname, sum(qty) as qty, sum(qty*averageprice) as nilaihpp, sum(amount) as nilaiinvoice,
						ifnull((100-(sum(qty*averageprice)/sum(amount))*100),0) as gm
						from (select e.productname,a.qty,
						ifnull((select f.averageprice from productstockdet f where f.productid=a.productid and f.slocid=a.slocid and f.unitofmeasureid=a.unitofmeasureid and f.storagebinid=a.storagebinid and f.referenceno=c.gino limit 1),0) as averageprice,
						case when (select g.recordstatus from invoice g where g.giheaderid=c.giheaderid) = 3 then 
						getamountdiscso(d.soheaderid,a.sodetailid,a.qty) else 0 end as amount
						from gidetail a
						join sodetail b on b.sodetailid=a.sodetailid
						join giheader c on c.giheaderid=a.giheaderid
						join soheader d on d.soheaderid=c.soheaderid
						join product e on e.productid=a.productid
						join sloc h on h.slocid=a.slocid
						join productplant i on i.productid=a.productid and i.slocid=a.slocid
						join materialgroup j on j.materialgroupid=i.materialgroupid
						where c.recordstatus=3 and d.plantid = ".$plantid." and e.productname like '%".$product."%'
						and h.sloccode like '%".$sloc."%' and j.description like '%".$materialgroup."%'
						and c.gidate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
						group by productname
						order by productname ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		
		foreach($dataReader as $row)
		{
				$this->pdf->companyid = $companyid;
		}
		$this->pdf->title='Rekap Perbandingan Nilai HPP dan Nilai Penjualan Per Barang';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');
		
		$this->pdf->setFont('Arial','B',8);
			$this->pdf->sety($this->pdf->gety()+0);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(8,50,17,25,20,17,25,20,15));
			$this->pdf->colheader = array('No','Nama Barang','Qty','Total HPP','HPP/Unit','Qty','Total Jual','Jual/Unit','GM');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('R','L','R','R','R','R','R','R','R');

		foreach($dataReader as $row)
		{
			$i+=1;
			$this->pdf->setFont('Arial','',8);
			$this->pdf->row(array(
				$i,
        $row['productname'],
				Yii::app()->format->formatCurrency($row['qty']),
				Yii::app()->format->formatCurrency($row['nilaihpp']/$per),
				Yii::app()->format->formatCurrency(($row['nilaihpp']/$row['qty'])/$per),
				Yii::app()->format->formatCurrency($row['qty']),
				Yii::app()->format->formatCurrency($row['nilaiinvoice']/$per),
				Yii::app()->format->formatCurrency(($row['nilaiinvoice']/$row['qty'])/$per),
				Yii::app()->format->formatCurrency($row['gm']),
			));
			$totalqty += ($row['qty']);
			$totalhpp += ($row['nilaihpp']/$per);
			$totalinvoice += ($row['nilaiinvoice']/$per);
		}
		$this->pdf->sety($this->pdf->gety()+5);
		$this->pdf->setFont('Arial','B',8);
		$a = 0;$b = 0;$c = 0;
		if ($totalqty != 0) {
			$a = $totalhpp / $totalqty;
			$b = $totalinvoice / $totalqty;
		}
		if ($totalinvoice != 0) {
			$c = $totalhpp/$totalinvoice;
		}
		$this->pdf->row(array(
			'',
      'GRAND TOTAL',
			Yii::app()->format->formatCurrency($totalqty),
			Yii::app()->format->formatCurrency($totalhpp),
			Yii::app()->format->formatCurrency($a),
			Yii::app()->format->formatCurrency($totalqty),
			Yii::app()->format->formatCurrency($totalinvoice),
			Yii::app()->format->formatCurrency($b),
			Yii::app()->format->formatCurrency(100-(($c)*100)),
		));
		$this->pdf->Output();
	}
	//16
	public function RekapPersediaanBarangNotMoving($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$qtyawal2=0;$nilaiawal2=0;$qtymasuk2=0;$nilaimasuk2=0;$qtytersedia2=0;$nilaitersedia2=0;$qtykeluar2=0;$nilaikeluar2=0;$qtyakhir2=0;$nilaiakhir2=0;
		$sql="select distinct slocid,sloccode,materialgroupid,description,accountname
          from (select k.productname,m.uomcode,l.slocid,l.sloccode,o.materialgroupid,o.description,q.accountname,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as qtyawal,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahawal,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty > 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as qtymasuk,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty > 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahmasuk,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty < 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid
          group by a.productid,a.slocid,a.unitofmeasureid),0) as qtykeluar,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty < 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahkeluar,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as qtyakhir,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahakhir
          from productplant j
          join product k on k.productid=j.productid
          join sloc l on l.slocid=j.slocid
          join unitofmeasure m on m.unitofmeasureid=j.uomid
          join plant n on n.plantid=l.plantid
          join materialgroup o on o.materialgroupid=j.materialgroupid
					left join slocaccounting p on p.slocid=j.slocid and p.materialgroupid=j.materialgroupid
					left join account q on q.accountid=p.accpersediaan
          where k.isstock=1 and n.plantid = ".$plantid." and l.sloccode like '%".$sloc."%' and o.materialgroupcode like '%".$materialgroup."%' and k.productname like '%".$product."%' and q.accountname like '%".$account."%') zz
          where qtyawal <> 0 or jumlahawal <> 0 or qtymasuk <> 0 or jumlahmasuk <> 0 or qtykeluar <> 0 or jumlahkeluar <> 0 or qtyakhir <> 0 or jumlahakhir <> 0
          order by sloccode, description";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		
		foreach($dataReader as $row)
		{
				$this->pdf->companyid = $companyid;
		}
		$this->pdf->title='Rekap Persediaan Barang Not Moving';
		$this->pdf->subtitle='Dari Tgl : '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','Legal');
		
		foreach ($dataReader as $row)
		{
			$this->pdf->setFont('Arial','',10);
			$this->pdf->text(15,$this->pdf->gety()+5,'GUDANG');$this->pdf->text(35,$this->pdf->gety()+5,': '.$row['sloccode']);
			$this->pdf->text(80,$this->pdf->gety()+5,'MATERIAL GROUP');$this->pdf->text(115,$this->pdf->gety()+5,': '.$row['description']);
			$this->pdf->text(180,$this->pdf->gety()+5,'NAMA AKUN');$this->pdf->text(205,$this->pdf->gety()+5,': '.$row['accountname']);
			
			if ($storagebin == null)
			{$this->pdf->text(100,$this->pdf->gety()+5,'');$this->pdf->text(115,$this->pdf->gety()+5,'');}
			else
			{$this->pdf->text(100,$this->pdf->gety()+5,'RAK');$this->pdf->text(115,$this->pdf->gety()+5,': '.$storagebin);}
			
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->sety($this->pdf->gety()+7);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(6,80,12,61,38,61,38,38,5));
			$this->pdf->colheader = array('','','','Awal','Penerimaan','Tersedia','Pengeluaran','Akhir','');
			$this->pdf->RowHeader();
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(6,80,12,15,23,23,15,23,15,23,23,15,23,15,23,5));
			$this->pdf->colheader = array('No','Nama Barang','Satuan','Qty','Harga','Nilai','Qty','Nilai','Qty','Harga','Nilai','Qty','Nilai','Qty','Nilai','');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('R','L','C','R','R','R','R','R','R','R','R','R','R','R','R','C');
			
			$i=0;$qtyawal=0;$nilaiawal=0;$qtymasuk=0;$nilaimasuk=0;$qtytersedia=0;$nilaitersedia=0;$qtykeluar=0;$nilaikeluar=0;$qtyakhir=0;$nilaiakhir=0;
			$sql1="select * from (select *, ifnull((jumlahawal/qtyawal),0) as hargaawal, qtyawal+qtymasuk as qtytersedia, ifnull(((jumlahawal+jumlahmasuk)/(qtyawal+qtymasuk)),0) as hargatersedia, jumlahawal+jumlahmasuk as jumlahtersedia,
          case when qtyakhir < 0 then 'X' else '' end as minus,qtykeluar-koreksi-tfs-grr as keluar2
          from (select k.productname,m.uomcode,l.sloccode,o.description,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as qtyawal,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahawal,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty > 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as qtymasuk,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty > 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahmasuk,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty < 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid
          group by a.productid,a.slocid,a.unitofmeasureid),0) as qtykeluar,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty < 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahkeluar,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as qtyakhir,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahakhir,
            (
            select ifnull(sum(z.qty),0) 
            from productstockdet z
            where z.qty < 0 and
            z.productid = k.productid and
            z.referenceno like 'TSO-%' and
            z.slocid = j.slocid and
            z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
            ) as koreksi,
            (
            select ifnull(sum(z.qty),0) 
            from productstockdet z
            where z.qty < 0 and
            z.productid = k.productid and
            z.referenceno like 'TFS-%' and
            z.slocid = j.slocid and
            z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
            ) as tfs,
            (
            select ifnull(sum(z.qty),0) 
            from productstockdet z
            where z.qty < 0 and
            z.productid = k.productid and
            z.referenceno like 'GRR-%' and
            z.slocid = j.slocid and
            z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
            ) as grr
          
          from productplant j
          join product k on k.productid=j.productid
          join sloc l on l.slocid=j.slocid
          join unitofmeasure m on m.unitofmeasureid=j.uomid
          join plant n on n.plantid=l.plantid
          join materialgroup o on o.materialgroupid=j.materialgroupid
          where k.isstock=1 and n.plantid = ".$plantid." and l.sloccode like '%".$sloc."%' and o.materialgroupcode like '%".$materialgroup."%' and k.productname like '%".$product."%' and l.slocid = ".$row['slocid']." and o.materialgroupid = ".$row['materialgroupid'].") zz
          where qtyawal <> 0 or jumlahawal <> 0 or qtymasuk <> 0 or jumlahmasuk <> 0 or qtykeluar <> 0 or jumlahkeluar <> 0 or qtyakhir <> 0 or jumlahakhir <> 0 
          ) xa
          where keluar2=0 
          order by keluar2";
			$command1=$this->connection->createCommand($sql1);
			$dataReader1=$command1->queryAll();
			
			foreach ($dataReader1 as $row1)
			{
				$i+=1;
				$this->pdf->setFont('Arial','',6.5);
				$this->pdf->row(array(
					$i,$row1['productname'],$row1['uomcode'],
					Yii::app()->format->formatNumber($row1['qtyawal']),
					Yii::app()->format->formatNumber($row1['hargaawal']/$per),
					Yii::app()->format->formatNumber(($row1['jumlahawal']/$per)),
					Yii::app()->format->formatNumber($row1['qtymasuk']),
					Yii::app()->format->formatNumber($row1['jumlahmasuk']/$per),
					Yii::app()->format->formatNumber($row1['qtytersedia']),
					Yii::app()->format->formatNumber($row1['hargatersedia']/$per),
					Yii::app()->format->formatNumber($row1['jumlahtersedia']/$per),
					Yii::app()->format->formatNumber($row1['qtykeluar']),
					Yii::app()->format->formatNumber($row1['jumlahkeluar']/$per),
					Yii::app()->format->formatNumber($row1['qtyakhir']),
					Yii::app()->format->formatNumber($row1['jumlahakhir']/$per),
					$row1['minus'],
				));
				$qtyawal += $row1['qtyawal'];
				$nilaiawal += $row1['jumlahawal']/$per;
				$qtymasuk += $row1['qtymasuk'];
				$nilaimasuk += $row1['jumlahmasuk']/$per;
				$qtytersedia += $row1['qtytersedia'];
				$nilaitersedia += $row1['jumlahtersedia']/$per;
				$qtykeluar += $row1['qtykeluar'];
				$nilaikeluar += $row1['jumlahkeluar']/$per;
				$qtyakhir += $row1['qtyakhir'];
				$nilaiakhir += $row1['jumlahakhir']/$per;
			}
			$this->pdf->setFont('Arial','B',6.5);
			$this->pdf->setwidths(array(98,15,23,23,15,23,15,23,23,15,23,15,23,5));
			$this->pdf->coldetailalign = array('R','R','R','R','R','R','R','R','R','R','R','R','R','C');
			$this->pdf->row(array(
				'TOTAL '.$row['sloccode'].' - '.$row['description'].'      >>>>>',
				Yii::app()->format->formatNumber($qtyawal),'',
				Yii::app()->format->formatNumber($nilaiawal),
				Yii::app()->format->formatNumber($qtymasuk),
				Yii::app()->format->formatNumber($nilaimasuk),
				Yii::app()->format->formatNumber($qtytersedia),'',
				Yii::app()->format->formatNumber($nilaitersedia),
				Yii::app()->format->formatNumber($qtykeluar),
				Yii::app()->format->formatNumber($nilaikeluar),
				Yii::app()->format->formatNumber($qtyakhir),
				Yii::app()->format->formatNumber($nilaiakhir),'',
			));
			$qtyawal2 += $qtyawal;
			$nilaiawal2 += $nilaiawal;
			$qtymasuk2 += $qtymasuk;
			$nilaimasuk2 += $nilaimasuk;
			$qtytersedia2 += $qtytersedia;
			$nilaitersedia2 += $nilaitersedia;
			$qtykeluar2 += $qtykeluar;
			$nilaikeluar2 += $nilaikeluar;
			$qtyakhir2 += $qtyakhir;
			$nilaiakhir2 += $nilaiakhir;
			
			$this->pdf->sety($this->pdf->gety()+5);
			$this->pdf->checkNewPage(175);
		}
		$this->pdf->setFont('Arial','BI',6.5);
		$this->pdf->colalign = array('C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(69,68,43,68,43,43,5));
		$this->pdf->colheader = array('','Awal','Penerimaan','Tersedia','Pengeluaran','Akhir','');
		$this->pdf->RowHeader();
		$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(69,18,25,25,18,25,18,25,25,18,25,18,25,5));
		$this->pdf->colheader = array('Keterangan','Qty','Harga','Nilai','Qty','Nilai','Qty','Harga','Nilai','Qty','Nilai','Qty','Nilai','');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('C','R','R','R','R','R','R','R','R','R','R','R','R','C');
		$this->pdf->row(array(
			'GRAND TOTAL      >>>>>',
			Yii::app()->format->formatNumber($qtyawal2),'',
			Yii::app()->format->formatNumber($nilaiawal2),
			Yii::app()->format->formatNumber($qtymasuk2),
			Yii::app()->format->formatNumber($nilaimasuk2),
			Yii::app()->format->formatNumber($qtytersedia2),'',
			Yii::app()->format->formatNumber($nilaitersedia2),
			Yii::app()->format->formatNumber($qtykeluar2),
			Yii::app()->format->formatNumber($nilaikeluar2),
			Yii::app()->format->formatNumber($qtyakhir2),
			Yii::app()->format->formatNumber($nilaiakhir2),'',
		));
		
		$this->pdf->Output();
	}
	//17
	public function RekapPersediaanBarangSlowMoving($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$qtyawal2=0;$nilaiawal2=0;$qtymasuk2=0;$nilaimasuk2=0;$qtytersedia2=0;$nilaitersedia2=0;$qtykeluar2=0;$nilaikeluar2=0;$qtyakhir2=0;$nilaiakhir2=0;
		$sql="select distinct slocid,sloccode,materialgroupid,description,accountname
          from (select k.productname,m.uomcode,l.slocid,l.sloccode,o.materialgroupid,o.description,q.accountname,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as qtyawal,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahawal,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty > 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as qtymasuk,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty > 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahmasuk,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty < 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid
          group by a.productid,a.slocid,a.unitofmeasureid),0) as qtykeluar,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty < 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahkeluar,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as qtyakhir,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahakhir
          from productplant j
          join product k on k.productid=j.productid
          join sloc l on l.slocid=j.slocid
          join unitofmeasure m on m.unitofmeasureid=j.uomid
          join plant n on n.plantid=l.plantid
          join materialgroup o on o.materialgroupid=j.materialgroupid
					left join slocaccounting p on p.slocid=j.slocid and p.materialgroupid=j.materialgroupid
					left join account q on q.accountid=p.accpersediaan
          where k.isstock=1 and n.plantid = ".$plantid." and l.sloccode like '%".$sloc."%' and o.materialgroupcode like '%".$materialgroup."%' and k.productname like '%".$product."%' and q.accountname like '%".$account."%') zz
          where qtyawal <> 0 or jumlahawal <> 0 or qtymasuk <> 0 or jumlahmasuk <> 0 or qtykeluar <> 0 or jumlahkeluar <> 0 or qtyakhir <> 0 or jumlahakhir <> 0
          order by sloccode, description";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		
		foreach($dataReader as $row)
		{
				$this->pdf->companyid = $companyid;
		}
		$this->pdf->title='Rekap Persediaan Barang Slow Moving';
		$this->pdf->subtitle='Dari Tgl : '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','Legal');
           
        if($keluar3==0){
        $this->pdf->SetFont('helvetica','B',20);
        $this->pdf->text(70, 50, 'Anda Belum Mengisi');
        $this->pdf->text(90, 60, 'QTY Keluar,');
        $this->pdf->text(50, 70, 'Silahkan Isi Dahulu QTY Keluar');
    }else{  
		
		foreach ($dataReader as $row)
		{
			$this->pdf->setFont('Arial','',10);
			$this->pdf->text(15,$this->pdf->gety()+5,'GUDANG');$this->pdf->text(35,$this->pdf->gety()+5,': '.$row['sloccode']);
			$this->pdf->text(80,$this->pdf->gety()+5,'MATERIAL GROUP');$this->pdf->text(115,$this->pdf->gety()+5,': '.$row['description']);
			$this->pdf->text(180,$this->pdf->gety()+5,'NAMA AKUN');$this->pdf->text(205,$this->pdf->gety()+5,': '.$row['accountname']);
			
			if ($storagebin == null)
			{$this->pdf->text(100,$this->pdf->gety()+5,'');$this->pdf->text(115,$this->pdf->gety()+5,'');}
			else
			{$this->pdf->text(100,$this->pdf->gety()+5,'RAK');$this->pdf->text(115,$this->pdf->gety()+5,': '.$storagebin);}
			
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->sety($this->pdf->gety()+7);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(6,80,12,61,38,61,38,38,5));
			$this->pdf->colheader = array('','','','Awal','Penerimaan','Tersedia','Pengeluaran','Akhir','');
			$this->pdf->RowHeader();
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(6,80,12,15,23,23,15,23,15,23,23,15,23,15,23,5));
			$this->pdf->colheader = array('No','Nama Barang','Satuan','Qty','Harga','Nilai','Qty','Nilai','Qty','Harga','Nilai','Qty','Nilai','Qty','Nilai','');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('R','L','C','R','R','R','R','R','R','R','R','R','R','R','R','C');
			
			$i=0;$qtyawal=0;$nilaiawal=0;$qtymasuk=0;$nilaimasuk=0;$qtytersedia=0;$nilaitersedia=0;$qtykeluar=0;$nilaikeluar=0;$qtyakhir=0;$nilaiakhir=0;
			$sql1="select * from (select *, ifnull((jumlahawal/qtyawal),0) as hargaawal, qtyawal+qtymasuk as qtytersedia, ifnull(((jumlahawal+jumlahmasuk)/(qtyawal+qtymasuk)),0) as hargatersedia, jumlahawal+jumlahmasuk as jumlahtersedia,
          case when qtyakhir < 0 then 'X' else '' end as minus,qtykeluar-koreksi-tfs-grr as keluar2
          from (select k.productname,m.uomcode,l.sloccode,o.description,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as qtyawal,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahawal,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty > 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as qtymasuk,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty > 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahmasuk,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty < 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid
          group by a.productid,a.slocid,a.unitofmeasureid),0) as qtykeluar,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty < 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahkeluar,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as qtyakhir,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahakhir,
            (
            select ifnull(sum(z.qty),0) 
            from productstockdet z
            where z.qty < 0 and
            z.productid = k.productid and
            z.referenceno like 'TSO-%' and
            z.slocid = j.slocid and
            z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
            ) as koreksi,
            (
            select ifnull(sum(z.qty),0) 
            from productstockdet z
            where z.qty < 0 and
            z.productid = k.productid and
            z.referenceno like 'TFS-%' and
            z.slocid = j.slocid and
            z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
            ) as tfs,
            (
            select ifnull(sum(z.qty),0) 
            from productstockdet z
            where z.qty < 0 and
            z.productid = k.productid and
            z.referenceno like 'GRR-%' and
            z.slocid = j.slocid and
            z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
            ) as grr
          
          from productplant j
          join product k on k.productid=j.productid
          join sloc l on l.slocid=j.slocid
          join unitofmeasure m on m.unitofmeasureid=j.uomid
          join plant n on n.plantid=l.plantid
          join materialgroup o on o.materialgroupid=j.materialgroupid
          where k.isstock=1 and n.plantid = ".$plantid." and l.sloccode like '%".$sloc."%' and o.materialgroupcode like '%".$materialgroup."%' and k.productname like '%".$product."%' and l.slocid = ".$row['slocid']." and o.materialgroupid = ".$row['materialgroupid'].") zz
          where qtyawal <> 0 or jumlahawal <> 0 or qtymasuk <> 0 or jumlahmasuk <> 0 or qtykeluar <> 0 or jumlahkeluar <> 0 or qtyakhir <> 0 or jumlahakhir <> 0 
          ) xa
          where keluar2 > - ".$keluar3."  
          order by keluar2";
			$command1=$this->connection->createCommand($sql1);
			$dataReader1=$command1->queryAll();
			
			foreach ($dataReader1 as $row1)
			{
				$i+=1;
				$this->pdf->setFont('Arial','',6.5);
				$this->pdf->row(array(
					$i,$row1['productname'],$row1['uomcode'],
					Yii::app()->format->formatNumber($row1['qtyawal']),
					Yii::app()->format->formatNumber($row1['hargaawal']/$per),
					Yii::app()->format->formatNumber(($row1['jumlahawal']/$per)),
					Yii::app()->format->formatNumber($row1['qtymasuk']),
					Yii::app()->format->formatNumber($row1['jumlahmasuk']/$per),
					Yii::app()->format->formatNumber($row1['qtytersedia']),
					Yii::app()->format->formatNumber($row1['hargatersedia']/$per),
					Yii::app()->format->formatNumber($row1['jumlahtersedia']/$per),
					Yii::app()->format->formatNumber($row1['qtykeluar']),
					Yii::app()->format->formatNumber($row1['jumlahkeluar']/$per),
					Yii::app()->format->formatNumber($row1['qtyakhir']),
					Yii::app()->format->formatNumber($row1['jumlahakhir']/$per),
					$row1['minus'],
				));
				$qtyawal += $row1['qtyawal'];
				$nilaiawal += $row1['jumlahawal']/$per;
				$qtymasuk += $row1['qtymasuk'];
				$nilaimasuk += $row1['jumlahmasuk']/$per;
				$qtytersedia += $row1['qtytersedia'];
				$nilaitersedia += $row1['jumlahtersedia']/$per;
				$qtykeluar += $row1['qtykeluar'];
				$nilaikeluar += $row1['jumlahkeluar']/$per;
				$qtyakhir += $row1['qtyakhir'];
				$nilaiakhir += $row1['jumlahakhir']/$per;
			}
			$this->pdf->setFont('Arial','B',6.5);
			$this->pdf->setwidths(array(98,15,23,23,15,23,15,23,23,15,23,15,23,5));
			$this->pdf->coldetailalign = array('R','R','R','R','R','R','R','R','R','R','R','R','R','C');
			$this->pdf->row(array(
				'TOTAL '.$row['sloccode'].' - '.$row['description'].'      >>>>>',
				Yii::app()->format->formatNumber($qtyawal),'',
				Yii::app()->format->formatNumber($nilaiawal),
				Yii::app()->format->formatNumber($qtymasuk),
				Yii::app()->format->formatNumber($nilaimasuk),
				Yii::app()->format->formatNumber($qtytersedia),'',
				Yii::app()->format->formatNumber($nilaitersedia),
				Yii::app()->format->formatNumber($qtykeluar),
				Yii::app()->format->formatNumber($nilaikeluar),
				Yii::app()->format->formatNumber($qtyakhir),
				Yii::app()->format->formatNumber($nilaiakhir),'',
			));
			$qtyawal2 += $qtyawal;
			$nilaiawal2 += $nilaiawal;
			$qtymasuk2 += $qtymasuk;
			$nilaimasuk2 += $nilaimasuk;
			$qtytersedia2 += $qtytersedia;
			$nilaitersedia2 += $nilaitersedia;
			$qtykeluar2 += $qtykeluar;
			$nilaikeluar2 += $nilaikeluar;
			$qtyakhir2 += $qtyakhir;
			$nilaiakhir2 += $nilaiakhir;
			
			$this->pdf->sety($this->pdf->gety()+5);
			$this->pdf->checkNewPage(175);
		}
		$this->pdf->setFont('Arial','BI',6.5);
		$this->pdf->colalign = array('C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(69,68,43,68,43,43,5));
		$this->pdf->colheader = array('','Awal','Penerimaan','Tersedia','Pengeluaran','Akhir','');
		$this->pdf->RowHeader();
		$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(69,18,25,25,18,25,18,25,25,18,25,18,25,5));
		$this->pdf->colheader = array('Keterangan','Qty','Harga','Nilai','Qty','Nilai','Qty','Harga','Nilai','Qty','Nilai','Qty','Nilai','');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('C','R','R','R','R','R','R','R','R','R','R','R','R','C');
		$this->pdf->row(array(
			'GRAND TOTAL      >>>>>',
			Yii::app()->format->formatNumber($qtyawal2),'',
			Yii::app()->format->formatNumber($nilaiawal2),
			Yii::app()->format->formatNumber($qtymasuk2),
			Yii::app()->format->formatNumber($nilaimasuk2),
			Yii::app()->format->formatNumber($qtytersedia2),'',
			Yii::app()->format->formatNumber($nilaitersedia2),
			Yii::app()->format->formatNumber($qtykeluar2),
			Yii::app()->format->formatNumber($nilaikeluar2),
			Yii::app()->format->formatNumber($qtyakhir2),
			Yii::app()->format->formatNumber($nilaiakhir2),'',
		));}
		
		$this->pdf->Output();
	}
	//18
	public function RekapPersediaanBarangFastMoving($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$qtyawal2=0;$nilaiawal2=0;$qtymasuk2=0;$nilaimasuk2=0;$qtytersedia2=0;$nilaitersedia2=0;$qtykeluar2=0;$nilaikeluar2=0;$qtyakhir2=0;$nilaiakhir2=0;
		$sql="select distinct slocid,sloccode,materialgroupid,description,accountname
          from (select k.productname,m.uomcode,l.slocid,l.sloccode,o.materialgroupid,o.description,q.accountname,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as qtyawal,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahawal,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty > 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as qtymasuk,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty > 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahmasuk,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty < 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid
          group by a.productid,a.slocid,a.unitofmeasureid),0) as qtykeluar,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty < 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahkeluar,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as qtyakhir,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahakhir
          from productplant j
          join product k on k.productid=j.productid
          join sloc l on l.slocid=j.slocid
          join unitofmeasure m on m.unitofmeasureid=j.uomid
          join plant n on n.plantid=l.plantid
          join materialgroup o on o.materialgroupid=j.materialgroupid
					left join slocaccounting p on p.slocid=j.slocid and p.materialgroupid=j.materialgroupid
					left join account q on q.accountid=p.accpersediaan
          where k.isstock=1 and n.plantid = ".$plantid." and l.sloccode like '%".$sloc."%' and o.materialgroupcode like '%".$materialgroup."%' and k.productname like '%".$product."%' and q.accountname like '%".$account."%') zz
          where qtyawal <> 0 or jumlahawal <> 0 or qtymasuk <> 0 or jumlahmasuk <> 0 or qtykeluar <> 0 or jumlahkeluar <> 0 or qtyakhir <> 0 or jumlahakhir <> 0
          order by sloccode, description";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		
		foreach($dataReader as $row)
		{
				$this->pdf->companyid = $companyid;
		}
		$this->pdf->title='Rekap Persediaan Barang Fast Moving';
		$this->pdf->subtitle='Dari Tgl : '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','Legal');
           
        if($keluar3==0){
        $this->pdf->SetFont('helvetica','B',20);
        $this->pdf->text(70, 50, 'Anda Belum Mengisi');
        $this->pdf->text(90, 60, 'QTY Keluar,');
        $this->pdf->text(50, 70, 'Silahkan Isi Dahulu QTY Keluar');
    }else{  
		
		foreach ($dataReader as $row)
		{
			$this->pdf->setFont('Arial','',10);
			$this->pdf->text(15,$this->pdf->gety()+5,'GUDANG');$this->pdf->text(35,$this->pdf->gety()+5,': '.$row['sloccode']);
			$this->pdf->text(80,$this->pdf->gety()+5,'MATERIAL GROUP');$this->pdf->text(115,$this->pdf->gety()+5,': '.$row['description']);
			$this->pdf->text(180,$this->pdf->gety()+5,'NAMA AKUN');$this->pdf->text(205,$this->pdf->gety()+5,': '.$row['accountname']);
			
			if ($storagebin == null)
			{$this->pdf->text(100,$this->pdf->gety()+5,'');$this->pdf->text(115,$this->pdf->gety()+5,'');}
			else
			{$this->pdf->text(100,$this->pdf->gety()+5,'RAK');$this->pdf->text(115,$this->pdf->gety()+5,': '.$storagebin);}
			
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->sety($this->pdf->gety()+7);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(6,80,12,61,38,61,38,38,5));
			$this->pdf->colheader = array('','','','Awal','Penerimaan','Tersedia','Pengeluaran','Akhir','');
			$this->pdf->RowHeader();
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(6,80,12,15,23,23,15,23,15,23,23,15,23,15,23,5));
			$this->pdf->colheader = array('No','Nama Barang','Satuan','Qty','Harga','Nilai','Qty','Nilai','Qty','Harga','Nilai','Qty','Nilai','Qty','Nilai','');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('R','L','C','R','R','R','R','R','R','R','R','R','R','R','R','C');
			
			$i=0;$qtyawal=0;$nilaiawal=0;$qtymasuk=0;$nilaimasuk=0;$qtytersedia=0;$nilaitersedia=0;$qtykeluar=0;$nilaikeluar=0;$qtyakhir=0;$nilaiakhir=0;
			$sql1="select * from (select *, ifnull((jumlahawal/qtyawal),0) as hargaawal, qtyawal+qtymasuk as qtytersedia, ifnull(((jumlahawal+jumlahmasuk)/(qtyawal+qtymasuk)),0) as hargatersedia, jumlahawal+jumlahmasuk as jumlahtersedia,
          case when qtyakhir < 0 then 'X' else '' end as minus,qtykeluar-koreksi-tfs-grr as keluar2
          from (select k.productname,m.uomcode,l.sloccode,o.description,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as qtyawal,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahawal,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty > 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as qtymasuk,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty > 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahmasuk,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty < 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid
          group by a.productid,a.slocid,a.unitofmeasureid),0) as qtykeluar,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.qty < 0 and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahkeluar,
          ifnull((select sum(a.qty)
          from productstockdet a
          where a.buydate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as qtyakhir,
          ifnull((select sum(a.qty*a.averageprice)
          from productstockdet a
          where a.buydate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.productid = j.productid and a.slocid = j.slocid and a.unitofmeasureid = j.uomid),0) as jumlahakhir,
            (
            select ifnull(sum(z.qty),0) 
            from productstockdet z
            where z.qty < 0 and
            z.productid = k.productid and
            z.referenceno like 'TSO-%' and
            z.slocid = j.slocid and
            z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
            ) as koreksi,
            (
            select ifnull(sum(z.qty),0) 
            from productstockdet z
            where z.qty < 0 and
            z.productid = k.productid and
            z.referenceno like 'TFS-%' and
            z.slocid = j.slocid and
            z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
            ) as tfs,
            (
            select ifnull(sum(z.qty),0) 
            from productstockdet z
            where z.qty < 0 and
            z.productid = k.productid and
            z.referenceno like 'GRR-%' and
            z.slocid = j.slocid and
            z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
            ) as grr
          
          from productplant j
          join product k on k.productid=j.productid
          join sloc l on l.slocid=j.slocid
          join unitofmeasure m on m.unitofmeasureid=j.uomid
          join plant n on n.plantid=l.plantid
          join materialgroup o on o.materialgroupid=j.materialgroupid
          where k.isstock=1 and n.plantid = ".$plantid." and l.sloccode like '%".$sloc."%' and o.materialgroupcode like '%".$materialgroup."%' and k.productname like '%".$product."%' and l.slocid = ".$row['slocid']." and o.materialgroupid = ".$row['materialgroupid'].") zz
          where qtyawal <> 0 or jumlahawal <> 0 or qtymasuk <> 0 or jumlahmasuk <> 0 or qtykeluar <> 0 or jumlahkeluar <> 0 or qtyakhir <> 0 or jumlahakhir <> 0 
          ) xa
          where keluar2 < - " . $keluar3 . " 
          order by keluar2";
			$command1=$this->connection->createCommand($sql1);
			$dataReader1=$command1->queryAll();
			
			foreach ($dataReader1 as $row1)
			{
				$i+=1;
				$this->pdf->setFont('Arial','',6.5);
				$this->pdf->row(array(
					$i,$row1['productname'],$row1['uomcode'],
					Yii::app()->format->formatNumber($row1['qtyawal']),
					Yii::app()->format->formatNumber($row1['hargaawal']/$per),
					Yii::app()->format->formatNumber(($row1['jumlahawal']/$per)),
					Yii::app()->format->formatNumber($row1['qtymasuk']),
					Yii::app()->format->formatNumber($row1['jumlahmasuk']/$per),
					Yii::app()->format->formatNumber($row1['qtytersedia']),
					Yii::app()->format->formatNumber($row1['hargatersedia']/$per),
					Yii::app()->format->formatNumber($row1['jumlahtersedia']/$per),
					Yii::app()->format->formatNumber($row1['qtykeluar']),
					Yii::app()->format->formatNumber($row1['jumlahkeluar']/$per),
					Yii::app()->format->formatNumber($row1['qtyakhir']),
					Yii::app()->format->formatNumber($row1['jumlahakhir']/$per),
					$row1['minus'],
				));
				$qtyawal += $row1['qtyawal'];
				$nilaiawal += $row1['jumlahawal']/$per;
				$qtymasuk += $row1['qtymasuk'];
				$nilaimasuk += $row1['jumlahmasuk']/$per;
				$qtytersedia += $row1['qtytersedia'];
				$nilaitersedia += $row1['jumlahtersedia']/$per;
				$qtykeluar += $row1['qtykeluar'];
				$nilaikeluar += $row1['jumlahkeluar']/$per;
				$qtyakhir += $row1['qtyakhir'];
				$nilaiakhir += $row1['jumlahakhir']/$per;
			}
			$this->pdf->setFont('Arial','B',6.5);
			$this->pdf->setwidths(array(98,15,23,23,15,23,15,23,23,15,23,15,23,5));
			$this->pdf->coldetailalign = array('R','R','R','R','R','R','R','R','R','R','R','R','R','C');
			$this->pdf->row(array(
				'TOTAL '.$row['sloccode'].' - '.$row['description'].'      >>>>>',
				Yii::app()->format->formatNumber($qtyawal),'',
				Yii::app()->format->formatNumber($nilaiawal),
				Yii::app()->format->formatNumber($qtymasuk),
				Yii::app()->format->formatNumber($nilaimasuk),
				Yii::app()->format->formatNumber($qtytersedia),'',
				Yii::app()->format->formatNumber($nilaitersedia),
				Yii::app()->format->formatNumber($qtykeluar),
				Yii::app()->format->formatNumber($nilaikeluar),
				Yii::app()->format->formatNumber($qtyakhir),
				Yii::app()->format->formatNumber($nilaiakhir),'',
			));
			$qtyawal2 += $qtyawal;
			$nilaiawal2 += $nilaiawal;
			$qtymasuk2 += $qtymasuk;
			$nilaimasuk2 += $nilaimasuk;
			$qtytersedia2 += $qtytersedia;
			$nilaitersedia2 += $nilaitersedia;
			$qtykeluar2 += $qtykeluar;
			$nilaikeluar2 += $nilaikeluar;
			$qtyakhir2 += $qtyakhir;
			$nilaiakhir2 += $nilaiakhir;
			
			$this->pdf->sety($this->pdf->gety()+5);
			$this->pdf->checkNewPage(175);
		}
		$this->pdf->setFont('Arial','BI',6.5);
		$this->pdf->colalign = array('C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(69,68,43,68,43,43,5));
		$this->pdf->colheader = array('','Awal','Penerimaan','Tersedia','Pengeluaran','Akhir','');
		$this->pdf->RowHeader();
		$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(69,18,25,25,18,25,18,25,25,18,25,18,25,5));
		$this->pdf->colheader = array('Keterangan','Qty','Harga','Nilai','Qty','Nilai','Qty','Harga','Nilai','Qty','Nilai','Qty','Nilai','');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('C','R','R','R','R','R','R','R','R','R','R','R','R','C');
		$this->pdf->row(array(
			'GRAND TOTAL      >>>>>',
			Yii::app()->format->formatNumber($qtyawal2),'',
			Yii::app()->format->formatNumber($nilaiawal2),
			Yii::app()->format->formatNumber($qtymasuk2),
			Yii::app()->format->formatNumber($nilaimasuk2),
			Yii::app()->format->formatNumber($qtytersedia2),'',
			Yii::app()->format->formatNumber($nilaitersedia2),
			Yii::app()->format->formatNumber($qtykeluar2),
			Yii::app()->format->formatNumber($nilaikeluar2),
			Yii::app()->format->formatNumber($qtyakhir2),
			Yii::app()->format->formatNumber($nilaiakhir2),'',
		));}
		
		$this->pdf->Output();
	}
	
  public function KartuStockBarang($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$sql        = "select distinct a.description,a.materialgroupid
			from materialgroup a
			left join productplant b on b.materialgroupid=a.materialgroupid
			left join sloc c on c.slocid = b.slocid
			left join plant d on d.plantid = c.plantid
			left join company e on e.companyid = d.companyid
			left join product f on f.productid = b.productid
			where e.companyid = " . $companyid . " 
				and coalesce(a.materialgroupcode,'') like '%".$materialgroup."%'
				and coalesce(c.sloccode,'') like '%" . $sloc . "%' 
			order by description";
		$command    = $this->connection->createCommand($sql);
		$dataReader = $command->queryAll();
		$this->pdf->isrepeat = 1;
		$this->pdf->companyid = $companyid;
		$this->pdf->title    = 'Kartu Stock Barang';
		$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');
		$this->pdf->sety($this->pdf->gety() + 0);
		$this->pdf->setFont('Arial', 'B', 8);
		$this->pdf->colalign = array(
		  'C',
		  'C',
		  'C',
		  'C',
		  'C',
		  'C',
		  'C',
		  'C'
		);
		$this->pdf->setwidths(array(
		  30,
		  20,
		  20,
		  20,
		  20,
		  20,
		  30,
		  30
		));
		$this->pdf->colheader = array(
		  'Dokumen',
		  'Tanggal',
		  'Awal',
		  'Masuk',
		  'Keluar',
		  'Akhir',
		  'Harga',
		  'Jumlah'
		);
		$this->pdf->RowHeader();
		foreach ($dataReader as $row) {
		  $this->pdf->SetFont('Arial', '', 10);
		  $this->pdf->text(10, $this->pdf->gety() + 5, 'Material Group');
		  $this->pdf->text(40, $this->pdf->gety() + 5, ': ' . $row['description']);
		  $awal1       = 0;
		  $masuk1      = 0;
		  $keluar1     = 0;
		  $saldo1      = 0;
		  $sql1        = "select distinct productid,productname,slocid,sloccode from
					(select productid,productname,awal,dokumen,tanggal,slocid,sloccode,masuk,keluar,(awal+masuk+keluar) as saldo
					from
					(select productid,productname,awal,dokumen,tanggal,slocid,sloccode,(beli+returjual+trfin+produksi+konversiin) as masuk,(jual+returbeli+trfout+pemakaian+konversiout+koreksi) as keluar
					from
					(select productid,productname,referenceno as dokumen, buydate as tanggal,slocid,sloccode,awal,
					case when instr(referenceno,'LPB-') > 0 and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
					'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as beli,
					case when instr(referenceno,'GIR-') > 0 and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
					'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as returjual,
					case when (instr(referenceno,'TFS-') > 0) and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
					'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty > 0) then qty else 0 end as trfin,
					case when (instr(referenceno,'OP-') > 0) and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
					'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty > 0) then qty else 0 end as produksi,
					case when (instr(referenceno,'konversi') > 0) and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
					'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty > 0) then qty else 0 end as konversiin,
					case when instr(referenceno,'SJ-') > 0 and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
					'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as jual,
					case when instr(referenceno,'GRR-') > 0 and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
					'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as returbeli,
					case when (instr(referenceno,'TFS') > 0) and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
					'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty < 0) then qty else 0 end as trfout,
					case when (instr(referenceno,'OP-') > 0) and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
					'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty < 0) then qty else 0 end as pemakaian,
					case when (instr(referenceno,'konversi') > 0) and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
					'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty < 0) then qty else 0 end as konversiout,
					case when instr(referenceno,'TSO') > 0 and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
					'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as koreksi
					from
					(select a.productid,g.productname,a.referenceno,a.buydate,a.qty,b.slocid,b.sloccode,
						(select ifnull(sum(x.qty),0) from productstockdet x
						where x.productid = a.productid and x.slocid = a.slocid and
					x.buydate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "') as awal
					from productstockdet a
					left join sloc b on b.slocid = a.slocid
					left join plant c on c.plantid = b.plantid
					left join company d on d.companyid = c.companyid
					left join productplant e on e.productid=a.productid and e.slocid=a.slocid and e.uom1=a.uomid
					left join storagebin f on f.storagebinid=a.storagebinid
					left join product g on g.productid=a.productid
					where d.companyid = " . $companyid . " and b.sloccode like '%" . $sloc . "%' and e.materialgroupid = '" . $row['materialgroupid'] . "'
					and g.productname like '%" . $product . "%' and 
					a.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
					'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z) zz) zzz) zzzz
					order by productname,sloccode";
		  $command1    = $this->connection->createCommand($sql1);
		  $dataReader1 = $command1->queryAll();
		  foreach ($dataReader1 as $row1) {
		    $this->pdf->SetFont('Arial', '', 10);
		    $this->pdf->text(10, $this->pdf->gety() + 10, $row1['productname']);
		    $this->pdf->text(170, $this->pdf->gety() + 10, $row1['sloccode']);
		    $sql2        = "select awal,dokumen,tanggal,masuk,keluar,(awal+masuk+keluar) as saldo,averageprice,case when masuk <> 0 then (masuk * averageprice) else (keluar * averageprice) end as nilai
		                    from
		                    (select awal,dokumen,tanggal,(beli+returjual+trfin+produksi+konversiin) as masuk,(jual+returbeli+trfout+pemakaian+konversiout+koreksi) as keluar,averageprice
		                    from
		                    (select referenceno as dokumen, buydate as tanggal,slocid,awal,
		                    case when instr(referenceno,'LPB-') > 0 and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
													'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as beli,
		                    case when instr(referenceno,'GIR-') > 0 and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
													'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as returjual,
		                    case when (instr(referenceno,'TFS-') > 0) and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
													'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty > 0) then qty else 0 end as trfin,
		                    case when (instr(referenceno,'OP-') > 0) and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
													'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty > 0) then qty else 0 end as produksi,
													case when (instr(referenceno,'konversi') > 0) and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
													'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty > 0) then qty else 0 end as konversiin,
		                    case when instr(referenceno,'SJ-') > 0 and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
													'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as jual,
		                    case when instr(referenceno,'GRR-') > 0 and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
													'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as returbeli,
		                    case when (instr(referenceno,'TFS') > 0) and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
													'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty < 0) then qty else 0 end as trfout,
		                    case when (instr(referenceno,'OP-') > 0) and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
													'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty < 0) then qty else 0 end as pemakaian,
													case when (instr(referenceno,'konversi') > 0) and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
													'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty < 0) then qty else 0 end as konversiout,
		                    case when instr(referenceno,'TSO') > 0 and (z.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
													'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as koreksi,averageprice
		                    from
		                    (select a.referenceno,a.buydate,a.qty,a.slocid,
														(select ifnull(sum(x.qty),0) from productstockdet x
														where x.productid = '" . $row1['productid'] . "' and x.slocid = '" . $row1['slocid'] . "' and
		                    x.buydate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "') as awal,a.averageprice
		                    from productstockdet a
		                    join sloc b on b.slocid = a.slocid
		                    join plant c on c.plantid = b.plantid
		                    join company d on d.companyid = c.companyid
		                    where a.productid = '" . $row1['productid'] . "' and a.slocid = '" . $row1['slocid'] . "' and
													a.buydate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
													'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z) zz) zzz order by tanggal";
		    $command2    = $this->connection->createCommand($sql2);
		    $dataReader2 = $command2->queryAll();
		    $awal        = 0;
		    $masuk       = 0;
		    $keluar      = 0;
		    $saldo       = 0;
		    $this->pdf->sety($this->pdf->gety() + 12);
		    $this->pdf->coldetailalign = array(
		      'L',
		      'C',
		      'R',
		      'R',
		      'R',
		      'R',
		      'R',
		      'R'
		    );
		    $this->pdf->setFont('Arial', '', 8);
		    foreach ($dataReader2 as $row2) {
		      $this->pdf->row(array(
		        $row2['dokumen'],
		        date(Yii::app()->params['dateviewfromdb'], strtotime($row2['tanggal'])),
		        '',
		        Yii::app()->format->formatNumber( $row2['masuk']),
		        Yii::app()->format->formatNumber( $row2['keluar']),
		        '',
		        Yii::app()->format->formatNumber( $row2['averageprice']/$per),
		        Yii::app()->format->formatNumber( $row2['nilai']/$per)
		      ));
		      $awal = $row2['awal'];
		      $masuk += $row2['masuk'];
		      $keluar += $row2['keluar'];
		      $saldo = $awal + $masuk + $keluar;
		    }
		    $this->pdf->setFont('Arial', 'B', 8);
		    $this->pdf->row(array(
		      '',
		      'Total',
		      Yii::app()->format->formatNumber( $awal),
		      Yii::app()->format->formatNumber( $masuk),
		      Yii::app()->format->formatNumber( $keluar),
		      Yii::app()->format->formatNumber( $saldo),
		      '',
		      ''
		    ));
		    $awal1 += $awal;
		    $masuk1 += $masuk;
		    $keluar1 += $keluar;
		    $saldo1 += $saldo;
		    $this->pdf->checkPageBreak(20);
		  }
		  $this->pdf->setFont('Arial', 'B', 9);
		  $this->pdf->sety($this->pdf->gety() + 5);
		  $this->pdf->row(array(
		    '',
		    'Grand Total',
		    Yii::app()->format->formatNumber( $awal1),
		    Yii::app()->format->formatNumber( $masuk1),
		    Yii::app()->format->formatNumber( $keluar1),
		    Yii::app()->format->formatNumber( $saldo1),
		    '',
		    ''
		  ));
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
				case 1:
					$this->RekapPersediaanBarangGrupXls($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);				
					break;
				case 2:
					$this->RekapPersediaanBarangJenisXls($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);				
					break;
				case 25:
					$this->RekapokXls($_GET['company'],$_GET['plant'],$_GET['sloc'],$_GET['materialgroup'],$_GET['storagebin'],$_GET['product'],$_GET['account'],$_GET['keluar3'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);				
					break;
				default:
					echo GetCatalog('reportdoesnotexist');
			}
		}
	}
	public function RekapPersediaanBarangJenisXls($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per) {
		$this->menuname='rekappersediaanbarangjenis';
		parent::actionDownxls();
		$sql="SELECT DISTINCT e.sloccode,f.description AS materialgroupdesc, 
		c.productcode,c.productname,d.uomcode,
		(
		SELECT ifnull(sum(ifnull(z.qty,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'
		) AS awal,
		(
		SELECT ifnull(sum(ifnull(z.qty*z.averageprice,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'
		) AS awalnilai,
		(
		SELECT ifnull(sum(ifnull(z.qty,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'LPB%'
		) AS beli,
		(
		SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'LPB%'
		) AS belinilai,
		(
		SELECT ifnull(sum(ifnull(z.qty,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'RJ%'
		) AS returjual,
		(
		SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'RJ%'
		) AS returjualnilai,
		(
SELECT ifnull(sum(ifnull(z.qty,0)),0)
FROM productstockdet z
WHERE z.productid = c.productid 
AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
AND z.referenceno LIKE 'TFS%'
AND z.qty >= 0
) AS transferin,
(
SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
FROM productstockdet z
WHERE z.productid = c.productid 
AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
AND z.referenceno LIKE 'TFS%'
AND z.qty >= 0
) AS transferinnilai,
		(
		SELECT ifnull(sum(ifnull(z.qty,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'TRS%'
		) AS transferretur,
		(
		SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'TRS%'
		) AS transferreturnilai,
		(
		SELECT ifnull(sum(ifnull(z.qty,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'TFF%'
		AND z.qty >= 0
		) AS transferfg,
		(
		SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'TFF%'
		AND z.qty >= 0
		) AS transferfgnilai,
		(
		SELECT ifnull(sum(ifnull(z.qty,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'OP%'
		AND z.qty >= 0
		) AS hasilproduksi,
		(
		SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'OP%'
		AND z.qty >= 0
		) AS hasilproduksinilai,
		(
		SELECT ifnull(sum(ifnull(z.qty,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'TSO%'
		AND z.qty >= 0
		) AS koreksiplus,
		(
		SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'TSO%'
		AND z.qty >= 0
		) AS koreksiplusnilai,
		(
		SELECT ifnull(sum(ifnull(z.qty,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'RB%'
		AND z.qty < 0
		) AS returbeli,
		(
		SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'RB%'
		AND z.qty < 0
		) AS returbelinilai,
		(
		SELECT ifnull(sum(ifnull(z.qty,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'TFS%'
		AND z.qty < 0
		) AS transferout,
		(
		SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'TFS%'
		AND z.qty < 0
		) AS transferoutnilai,
		(
		SELECT ifnull(sum(ifnull(z.qty,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'TFF%'
		AND z.qty < 0
		) AS transferoutfg,
		(
		SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'TFF%'
		AND z.qty < 0
		) AS transferoutfgnilai,
		(
		SELECT ifnull(sum(ifnull(z.qty,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'OP%'
		AND z.qty < 0
		) AS hasilproduksimin,
		(
		SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'OP%'
		AND z.qty < 0
		) AS hasilproduksiminnilai,
		(
		SELECT ifnull(sum(ifnull(z.qty,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'TSO%'
		AND z.qty < 0
		) AS koreksimin,
		(
		SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
		FROM productstockdet z
		WHERE z.productid = c.productid and z.slocid = b.slocid
		AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
		AND z.referenceno LIKE 'TSO%'
		AND z.qty < 0
		) AS koreksiminnilai,
		(
			select za.accountname
			from slocaccounting z
			left join account za on za.accountid = z.accpersediaan
			where z.slocid = b.slocid 
			limit 1
		) as accountname
		FROM productplant b 
		LEFT JOIN product c ON c.productid = b.productid 
		LEFT JOIN unitofmeasure d ON d.unitofmeasureid = b.uom1
		left join sloc e on e.slocid = b.slocid 
		left join materialgroup f on f.materialgroupid = b.materialgroupid 
		left join plant g on g.plantid = e.plantid 
	WHERE g.companyid = ".$companyid.
	(($product != '')?" and c.productname like '%".$product."%'":'').
	(($plantid != '')?" and g.plantid = ".$plantid:'').
	(($sloc != '')?" and e.sloccode like '%".$sloc."%'":'').
	(($materialgroup != '')? " AND f.description like '%".$materialgroup."%'":'');
			$command=$this->connection->createCommand($sql);
			$dataReader=$command->queryAll();
			$i = 5;$total=0;$totalnilai=0;
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, 2, 'Dari Tgl : '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
		foreach ($dataReader as $row) {
			$total = $row['awal']+$row['beli']+$row['returjual']+$row['transferin']+$row['transferretur']+$row['transferfg']+$row['hasilproduksi']+
				$row['koreksiplus']+$row['returbeli']+$row['transferout']+$row['transferoutfg']+$row['hasilproduksimin']+$row['koreksimin'];
			$totalnilai = $row['awalnilai']+$row['belinilai']+$row['returjualnilai']+$row['transferinnilai']+$row['transferreturnilai']+$row['transferfgnilai']+$row['hasilproduksinilai']+
				$row['koreksiplusnilai']+$row['returbelinilai']+$row['transferoutnilai']+$row['transferoutfgnilai']+$row['hasilproduksiminnilai']+
				$row['koreksiminnilai'];
				$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $i-4)
				->setCellValueByColumnAndRow(1, $i+1, $row['sloccode'])
				->setCellValueByColumnAndRow(2, $i+1, $row['materialgroupdesc'])
				->setCellValueByColumnAndRow(3, $i+1, $row['accountname'])
				->setCellValueByColumnAndRow(4, $i+1, $row['productcode'])
				->setCellValueByColumnAndRow(5, $i+1, $row['productname'])
				->setCellValueByColumnAndRow(6, $i+1, $row['uomcode'])
				->setCellValueByColumnAndRow(7, $i+1, Yii::app()->format->formatnumber($row['awal']))
				->setCellValueByColumnAndRow(8, $i+1, Yii::app()->format->formatnumber($row['awalnilai']))
				->setCellValueByColumnAndRow(9, $i+1, Yii::app()->format->formatnumber($row['beli']))
				->setCellValueByColumnAndRow(10, $i+1, Yii::app()->format->formatnumber($row['belinilai']))
				->setCellValueByColumnAndRow(11, $i+1, Yii::app()->format->formatnumber($row['returjual']))
				->setCellValueByColumnAndRow(12, $i+1, Yii::app()->format->formatnumber($row['returjualnilai']))
				->setCellValueByColumnAndRow(13, $i+1, Yii::app()->format->formatnumber($row['transferin']))
				->setCellValueByColumnAndRow(14, $i+1, Yii::app()->format->formatnumber($row['transferinnilai']))
				->setCellValueByColumnAndRow(15, $i+1, Yii::app()->format->formatnumber($row['transferretur']))
				->setCellValueByColumnAndRow(16, $i+1, Yii::app()->format->formatnumber($row['transferreturnilai']))
				->setCellValueByColumnAndRow(17, $i+1, Yii::app()->format->formatnumber($row['transferfg']))
				->setCellValueByColumnAndRow(18, $i+1, Yii::app()->format->formatnumber($row['transferfgnilai']))
				->setCellValueByColumnAndRow(19, $i+1, Yii::app()->format->formatnumber($row['hasilproduksi']))
				->setCellValueByColumnAndRow(20, $i+1, Yii::app()->format->formatnumber($row['hasilproduksinilai']))
				->setCellValueByColumnAndRow(21, $i+1, Yii::app()->format->formatnumber($row['koreksiplus']))
				->setCellValueByColumnAndRow(22, $i+1, Yii::app()->format->formatnumber($row['koreksiplusnilai']))
				->setCellValueByColumnAndRow(23, $i+1, Yii::app()->format->formatnumber($row['returbeli']))
				->setCellValueByColumnAndRow(24, $i+1, Yii::app()->format->formatnumber($row['returbelinilai']))
				->setCellValueByColumnAndRow(25, $i+1, Yii::app()->format->formatnumber($row['transferout']))
				->setCellValueByColumnAndRow(26, $i+1, Yii::app()->format->formatnumber($row['transferoutnilai']))
				->setCellValueByColumnAndRow(27, $i+1, Yii::app()->format->formatnumber($row['transferoutfg']))
				->setCellValueByColumnAndRow(28, $i+1, Yii::app()->format->formatnumber($row['transferoutfgnilai']))
				->setCellValueByColumnAndRow(29, $i+1, Yii::app()->format->formatnumber($row['hasilproduksimin']))
				->setCellValueByColumnAndRow(30, $i+1, Yii::app()->format->formatnumber($row['hasilproduksiminnilai']))
				->setCellValueByColumnAndRow(31, $i+1, Yii::app()->format->formatnumber($row['koreksimin']))
				->setCellValueByColumnAndRow(32, $i+1, Yii::app()->format->formatnumber($row['koreksiminnilai']))
				->setCellValueByColumnAndRow(33, $i+1, Yii::app()->format->formatnumber($total))
				->setCellValueByColumnAndRow(34, $i+1, Yii::app()->format->formatnumber($totalnilai));
				$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
	public function RekapPersediaanBarangGrupXls($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per) {
		$this->menuname='rekappersediaanbaranggrup';
		parent::actionDownxls();
		$sql="
		SELECT DISTINCT e.sloccode,f.description AS materialgroupdesc, 
			c.productcode,c.productname,d.uomcode,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'
			) AS awal,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.averageprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'
			) AS awalnilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'LPB%'
			) AS beli,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'LPB%'
			) AS belinilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'RJ%'
			) AS returjual,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'RJ%'
			) AS returjualnilai,
			(
SELECT ifnull(sum(ifnull(z.qty,0)),0)
FROM productstockdet z
WHERE z.productid = c.productid 
AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
AND z.referenceno LIKE 'TFS%'
AND z.qty >= 0
) AS transferin,
(
SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
FROM productstockdet z
WHERE z.productid = c.productid 
AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
AND z.referenceno LIKE 'TFS%'
AND z.qty >= 0
) AS transferinnilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TRS%'
			) AS transferretur,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TRS%'
			) AS transferreturnilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TFF%'
			AND z.qty >= 0
			) AS transferfg,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TFF%'
			AND z.qty >= 0
			) AS transferfgnilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'OP%'
			AND z.qty >= 0
			) AS hasilproduksi,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'OP%'
			AND z.qty >= 0
			) AS hasilproduksinilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TSO%'
			AND z.qty >= 0
			) AS koreksiplus,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TSO%'
			AND z.qty >= 0
			) AS koreksiplusnilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'RB%'
			AND z.qty < 0
			) AS returbeli,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'RB%'
			AND z.qty < 0
			) AS returbelinilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TFS%'
			AND z.qty < 0
			) AS transferout,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TFS%'
			AND z.qty < 0
			) AS transferoutnilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TFF%'
			AND z.qty < 0
			) AS transferoutfg,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TFF%'
			AND z.qty < 0
			) AS transferoutfgnilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'OP%'
			AND z.qty < 0
			) AS hasilproduksimin,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'OP%'
			AND z.qty < 0
			) AS hasilproduksiminnilai,
			(
			SELECT ifnull(sum(ifnull(z.qty,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TSO%'
			AND z.qty < 0
			) AS koreksimin,
			(
			SELECT ifnull(sum(ifnull(z.qty*z.buyprice,0)),0)
			FROM productstockdet z
			WHERE z.productid = c.productid and z.slocid = b.slocid
			AND z.buydate BETWEEN '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND z.referenceno LIKE 'TSO%'
			AND z.qty < 0
			) AS koreksiminnilai,
			(
				select za.accountname
				from slocaccounting z
				left join account za on za.accountid = z.accpersediaan
				where z.slocid = b.slocid 
				limit 1
			) as accountname
			FROM productplant b 
			LEFT JOIN product c ON c.productid = b.productid 
			LEFT JOIN unitofmeasure d ON d.unitofmeasureid = b.uom1
			left join sloc e on e.slocid = b.slocid 
			left join materialgroup f on f.materialgroupid = b.materialgroupid 
			left join plant g on g.plantid = e.plantid 
		WHERE g.companyid = ".$companyid.
		(($product != '')?" and c.productname like '%".$product."%'":'').
		(($plantid != '')?" and g.plantid = ".$plantid:'').
		(($sloc != '')?" and e.sloccode like '%".$sloc."%'":'').
		(($materialgroup != '')? " AND f.description like '%".$materialgroup."%'":'');
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$i = 5;
		$this->phpExcel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0, 2, 'Dari Tgl : '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
		foreach ($dataReader as $row) {
			if ($row['awal'] == 0) {
				$harga = 0;
			} else {
				$harga = $row['awalnilai'] / $row['awal'];
			}
			$tersedia = $row['awal'] + $row['beli']+$row['returjual']+$row['transferin']+$row['transferretur']+$row['transferfg']+$row['hasilproduksi']+$row['koreksiplus'];
			$tersedianilai = $row['awalnilai'] + $row['belinilai']+$row['returjual']+$row['transferin']+$row['transferretur']+$row['transferfg']+$row['hasilproduksi']+$row['koreksiplus'];
			if ($tersedia == 0) {
				$hargatersedia = $harga;
			} else {
				$hargatersedia = $tersedianilai / $tersedia;
			}
			$pengeluaran = $row['returbeli']+$row['transferout']+$row['transferoutfg']+$row['hasilproduksimin']+$row['koreksimin'];
			$pengeluarannilai = $pengeluaran * $hargatersedia;
			$akhir = $tersedia-$pengeluaran;
			$akhirnilai = $akhir * $hargatersedia;
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $i-4)
				->setCellValueByColumnAndRow(1, $i+1, $row['sloccode'])
				->setCellValueByColumnAndRow(2, $i+1, $row['materialgroupdesc'])
				->setCellValueByColumnAndRow(3, $i+1, $row['accountname'])
				->setCellValueByColumnAndRow(4, $i+1, $row['productcode'])
				->setCellValueByColumnAndRow(5, $i+1, $row['productname'])
				->setCellValueByColumnAndRow(6, $i+1, $row['uomcode'])
				->setCellValueByColumnAndRow(7, $i+1, Yii::app()->format->formatnumber($row['awal']))
				->setCellValueByColumnAndRow(8, $i+1, Yii::app()->format->formatnumber($harga))
				->setCellValueByColumnAndRow(9, $i+1, Yii::app()->format->formatnumber($row['awalnilai']))
				->setCellValueByColumnAndRow(10, $i+1, Yii::app()->format->formatnumber($tersedia - $row['awal']))
				->setCellValueByColumnAndRow(11, $i+1, Yii::app()->format->formatnumber($row['belinilai']+$row['returjualnilai']+$row['transferinnilai']+$row['transferreturnilai']+$row['transferfgnilai']+$row['hasilproduksinilai']+$row['koreksiplusnilai']))
				->setCellValueByColumnAndRow(12, $i+1, Yii::app()->format->formatnumber($tersedia))
				->setCellValueByColumnAndRow(13, $i+1, Yii::app()->format->formatnumber($hargatersedia))
				->setCellValueByColumnAndRow(14, $i+1, Yii::app()->format->formatnumber($tersedianilai))
				->setCellValueByColumnAndRow(15, $i+1, Yii::app()->format->formatnumber($pengeluaran))
				->setCellValueByColumnAndRow(16, $i+1, Yii::app()->format->formatnumber($pengeluarannilai))
				->setCellValueByColumnAndRow(17, $i+1, Yii::app()->format->formatnumber($akhir))
				->setCellValueByColumnAndRow(18, $i+1, Yii::app()->format->formatnumber($akhirnilai))
				;
				$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
	

	public function RekapokXls($companyid,$plantid,$sloc,$materialgroup,$storagebin,$product,$account,$keluar3,$startdate,$enddate,$per) {
		$this->menuname='rekapok';
    parent::actionDownxls();
		$sql = "SELECT distinct a.productplanno,a.productplandate,e.fullname,c.productname,d.sono,b.qty,k.plantcode,a.description,d.pocustno,
			ifnull((select xx.averageprice
			from productstockdet xx
			where xx.productid = b.productid and xx.uomid = b.uomid 
			and xx.productoutputfgid = f.productoutputfgid and xx.slocid = b.sloctoid and xx.storagebinid = f.storagebinid limit 1),0) as harga
			FROM productplan a
			LEFT JOIN productplanfg b ON b.productplanid = a.productplanid
			LEFT JOIN product c ON c.productid = b.productid
			LEFT JOIN addressbook e ON e.addressbookid = a.addressbookid
			LEFT JOIN productoutputfg f ON f.productplanfgid = b.productplanfgid AND f.productid = b.productid
			left join sloc j on j.slocid = b.sloctoid 
			left join plant k on k.plantid = a.plantid 
			left join soheader d on d.soheaderid = a.soheaderid
			WHERE a.productplandate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
			and a.plantid = ".$plantid." 
			and coalesce(j.sloccode,'') like '%".$sloc."%' 
			and coalesce(c.productname,'') like '%".$product."%'
			
			and a.recordstatus >= 3
			ORDER BY a.productplandate asc,a.productplanno asc";
		
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$price = getUserObjectValues($menuobject='currency');
		$i          = 2;
		if($price==1) {
    foreach ($dataReader as $row) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $i-1)
				->setCellValueByColumnAndRow(1, $i+1, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $i+1, $row['productplanno'])
				->setCellValueByColumnAndRow(3, $i+1, $row['productplandate'])
				->setCellValueByColumnAndRow(4, $i+1, $row['sono'])
				->setCellValueByColumnAndRow(5, $i+1, $row['pocustno'])
				->setCellValueByColumnAndRow(6, $i+1, $row['fullname'])
				->setCellValueByColumnAndRow(7, $i+1, $row['productname'])
				->setCellValueByColumnAndRow(8, $i+1, $row['qty'])
				->setCellValueByColumnAndRow(9, $i+1, $row['harga'])
				->setCellValueByColumnAndRow(10, $i+1, $row['qty']*$row['harga'])
				->setCellValueByColumnAndRow(11, $i+1, $row['description'])
			;
			$i++;
		}
		}
		else {
			foreach ($dataReader as $row) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $i-1)
				->setCellValueByColumnAndRow(1, $i+1, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $i+1, $row['productplanno'])
				->setCellValueByColumnAndRow(3, $i+1, $row['productplandate'])
				->setCellValueByColumnAndRow(4, $i+1, $row['sono'])
				->setCellValueByColumnAndRow(5, $i+1, $row['pocustno'])
				->setCellValueByColumnAndRow(6, $i+1, $row['fullname'])
				->setCellValueByColumnAndRow(7, $i+1, $row['productname'])
				->setCellValueByColumnAndRow(8, $i+1, $row['qty'])
				->setCellValueByColumnAndRow(9, $i+1, ' - ')
				->setCellValueByColumnAndRow(10, $i+1, ' - ')
				->setCellValueByColumnAndRow(11, $i+1, $row['description'])
			;
			$i++;
		}
		}
		$this->getFooterXLS($this->phpExcel);
	}	
}