<?php
class RepprodController extends Controller {
  public $menuname = 'repprod';
  public function actionIndex() {
    $this->renderPartial('index', array());
  }
  public function actionDownPDF() {
		parent::actionDownload();
		$companyid = $_GET['companyid'];
		$lro = $_GET['lro'];
		$plantid = $_GET['plantid'];
		$startdate = $_GET['startdate'];
		$enddate = $_GET['enddate'];
		$sloc = $_GET['sloc'];
		$customer = $_GET['customer'];
		$product = $_GET['product'];
		if ($companyid == '') {
			echo getcatalog('emptycompany');
		} else 
		if ($lro == '') {
			GetMessage(true,'choosereport');
		} else 
		if ($plantid == '') {
			GetMessage(true,'emptyplant');
		} else 
		if ($startdate == '') {
			GetMessage(true,'emptystartdate');
		} else 
		if ($enddate == '') {
			GetMessage(true,'emptyenddate');
		} else {			      
			switch ($lro) {
				case 1:
					$this->RincianProduksiPerDokumen($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);
					break;
				case 2:
					$this->RekapProduksiPerBarang($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);
					break;
				case 3:
					$this->RincianPemakaianPerDokumen($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);
					break;
				case 4:
					$this->RekapPemakaianPerBarang($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);
					break;
				case 5:
					$this->PerbandinganPlanningOutput($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);
					break;
				case 6:
					$this->RwBelumAdaGudangAsal($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);
					break;
				case 7:
					$this->RwBelumAdaGudangTujuan($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);
					break;
				case 8:
					$this->PendinganProduksi($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);
					break;
				case 9:
					$this->RincianPendinganProduksiPerBarang($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);					
					break;
				case 10:
					$this->RekapPendinganProduksiPerBarang($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);					
					break;
				case 11:
					$this->RekapProduksiPerBarangPerHari($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);					
					break;
				case 12:
					$this->RekapHasilProduksiPerDokumentBelumStatusMax($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);					
					break;
				case 13:
					$this->RekapProduksiPerBarangPerBulan($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);					
					break;
				case 14:
					$this->JadwalProduksi($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);					
					break;
				case 15:
					$this->LaporanSPPStatusBelumMax($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);					
					break;
				case 16:
					$this->LaporanMaterialSPP($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);					
					break;
				case 17:
					$this->RincianTransferGudangKeluarPerDokumen($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);					
					break;
				case 18:
					$this->RekapTransferHasilProduksiPerBarang($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);					
					break;
				case 19:
					$this->RincianFPBOKPerDokumen($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);					
					break;
				case 20:
					$this->JadwalProduksiMesin($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);					
					break;
				case 21:
					$this->HasilProduksiBelumMax($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);					
					break;
				case 22:
					$this->RekonsiliasiOKOSHP($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);					
					break;
				case 23:
					$this->LaporanWaste($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);					
					break;
				case 24:
					$this->LaporanAlokasiBarang($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);					
					break;
					case 25:
					$this->rekapok($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate);					
					break;
				default:
					echo GetCatalog('reportdoesnotexist');
			}
    }
  }
	public function rekapok($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
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
			WHERE a.productplandate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
			and a.plantid = ".$plantid." 
			and coalesce(j.sloccode,'') like '%".$sloc."%' 
			and coalesce(c.productname,'') like '%".$product."%'
			and coalesce(e.fullname,'') like '%".$customer."%'
			and a.recordstatus >= 3
			ORDER BY a.productplandate asc,a.productplanno asc";
		
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->companyid = $companyid;
		$price = getUserObjectValues($menuobject='currency');
		$this->pdf->title    = 'Laporan Rekap OK';
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
	public function RincianProduksiPerDokumen($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
    parent::actionDownload();
    $sql        = "select distinct a.productoutputno,a.productoutputdate,a.productoutputid,e.productplanno as ok,h.fullname,i.sono,i.pocustno
				from productoutput a
				left join productoutputfg b on b.productoutputid = a.productoutputid
				left join product c on c.productid = b.productid
				left join sloc d on d.slocid = b.slocid
				left join productplan e on e.productplanid = a.productplanid
				left join plant f on f.plantid = a.plantid
				left join company g on g.companyid = f.companyid
				left join addressbook h on h.addressbookid = e.addressbookid 
				left join soheader i on i.soheaderid = e.soheaderid 
				where a.recordstatus = 3 
					and a.productoutputno is not null 
					and d.sloccode like '%" . $sloc . "%' 
					and g.companyid = " . $companyid . " 
					and f.plantid = " . $plantid . " 
					and c.productname like '%" . $product . "%' 
					and a.productoutputdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
					and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' order by productoutputdate";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rincian Produksi Per Dokumen';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('L','legal');
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'No Hasil Produksi');
      $this->pdf->text(40, $this->pdf->gety() + 10, ': ' . $row['productoutputno']);
      $this->pdf->text(125, $this->pdf->gety() + 10, 'Tanggal');
      $this->pdf->text(150, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['productoutputdate'])));
      $this->pdf->text(235, $this->pdf->gety() + 10, 'No OK');
      $this->pdf->text(270, $this->pdf->gety() + 10, ': ' . $row['ok']);
      $this->pdf->text(10, $this->pdf->gety() + 15, 'No OS');
      $this->pdf->text(40, $this->pdf->gety() + 15, ': ' . $row['sono']);
      $this->pdf->text(125, $this->pdf->gety() + 15, 'Pelanggan');
      $this->pdf->text(150, $this->pdf->gety() + 15, ': ' . $row['fullname']);
      $this->pdf->text(235, $this->pdf->gety() + 15, 'No PO Customer');
      $this->pdf->text(270, $this->pdf->gety() + 15, ': ' . $row['pocustno']);
      $sql1        = "select distinct b.productname,a.qty,c.uomcode,a.description ,d.description as sloc,a.shift,a.efektivitas,a.angkatan,e.kodemesin,f.processprdname,g.fullname as spv,
			(select ifnull(zz.qty,0)
from productplanfg zz
left join sodetail za on za.sodetailid = zz.sodetailid
where zz.productplanfgid = a.productplanfgid) as soqty
						from productoutputfg a
						join product b on b.productid = a.productid
						left join unitofmeasure c on c.unitofmeasureid = a.uomid
						left join sloc d on d.slocid = a.slocid
						left join mesin e on e.mesinid = a.mesinid
							left join processprd f on f.processprdid = a.processprdid
							left join employee g on g.employeeid = a.employeeid
						where b.productname like '%" . $product . "%' and a.productoutputid = " . $row['productoutputid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $total       = 0;
      $i           = 0;
      $totalqty    = 0;
      $this->pdf->sety($this->pdf->gety() + 20);
      $this->pdf->setFont('Arial', 'B', 8);
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
        28,
        25,
        20,
        25,
        13,
        22,
        20,
        40
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Qty OS',
        'Satuan',
        'Gudang',
        'Proses',
        'Mesin',
        'SPV',
        'Shift',
        'Efektivitas',
        'Angkatan',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'C',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['qty']),
          Yii::app()->format->formatNumber($row1['soqty']),
          $row1['uomcode'],
          $row1['sloc'],
          $row1['processprdname'],
          $row1['kodemesin'],
          $row1['spv'],
          $row1['shift'],
          $row1['efektivitas'],
          $row1['angkatan'],
          $row1['description']
        ));
        $totalqty += $row1['qty'];
      }
      $this->pdf->row(array(
        '',
        'Total',
        Yii::app()->format->formatNumber($totalqty),
        '',
        ''
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
	public function RekapProduksiPerBarang($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
    parent::actionDownload();
    $sql        = "select distinct g.materialgroupid,g.description
				from productoutput a
				join productoutputfg b on b.productoutputid = a.productoutputid
				join product c on c.productid = b.productid
				join sloc d on d.slocid = b.slocid
				join productplan e on e.productplanid = a.productplanid
				join productplant f on f.productid = b.productid
				join materialgroup g on g.materialgroupid = f.materialgroupid
				join plant h on h.plantid = a.plantid
				join company i on i.companyid = h.companyid
				where a.productoutputno is not null 
				and i.companyid = " . $companyid . " 
				and h.plantid = " . $plantid . " 
				and a.recordstatus = 3
				and d.sloccode like '%" . $sloc . "%' 
				and c.productname like '%" . $product . "%' 
				and a.productoutputdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
				and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Produksi Per Barang';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Divisi');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['description']);
      $sql1        = "select distinct productoutputno,productname,uomcode,materialgroupid,sum(qty) as qtyoutput from 
					(select distinct b.productname,a.qty,e.uomcode,c.materialgroupid,a.productoutputfgid,d.productoutputno
					from productoutputfg a
					inner join product b on b.productid = a.productid
					inner join productoutput d on d.productoutputid = a.productoutputid
					inner join unitofmeasure e on e.unitofmeasureid = a.uomid
					inner join productplant c on c.productid = a.productid and c.slocid = a.slocid and c.uom1 = a.uomid
					join sloc f on f.slocid = a.slocid
					join productplan g on g.productplanid = d.productplanid 
					join plant h on h.plantid = d.plantid
					join company i on i.companyid = h.companyid
					where b.productname like '%" . $product . "%' and d.recordstatus = 3 and f.sloccode like '%" . $sloc . "%'
					and i.companyid = " . $companyid . " and d.productoutputdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
					and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' and c.materialgroupid = " . $row['materialgroupid'] . ") z 
					group by productname,uomcode,materialgroupid";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $totalqty    = 0;
      $i           = 0;
      $this->pdf->sety($this->pdf->gety() + 15);
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        30,
        80,
        20,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'No HP',
        'Nama Barang',
        'Satuan',
        'Qty'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'L',
        'C',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,$row1['productoutputno'],
          $row1['productname'],
          $row1['uomcode'],
          Yii::app()->format->formatNumber($row1['qtyoutput'])
        ));
        $totalqty += $row1['qtyoutput'];
      }
      $this->pdf->row(array(
        '',
        'Total ' . $row['description'],
        '',
        '',
        Yii::app()->format->formatNumber($totalqty)
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
	public function RincianPemakaianPerDokumen($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
    parent::actionDownload();
    $sql        = "select distinct a.productoutputid,a.productoutputno as dokumen,a.productoutputdate as tanggal,
e.sloccode,b.productname,h.fullname
from productoutput a
left join productoutputfg b on b.productoutputid = a.productoutputid
left join productoutputdetail c on c.productoutputfgid = b.productoutputfgid
left join product d on d.productid = c.productid
left join sloc e on e.slocid = c.sloctoid 
left join plant f on f.plantid = a.plantid 
left join company g on g.companyid = f.companyid
left join addressbook h on h.addressbookid = a.addressbookid 
				where a.productoutputno is not null 
				and g.companyid = " . $companyid . " 
				and f.plantid = " . $plantid . " 
				and e.sloccode like '%" . $sloc . "%' 
				and d.productname like '%" . $product . "%' 
				and a.productoutputdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
				and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' order by productoutputdate";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title = 'Rincian Pemakaian Per Dokumen';
    $this->pdf->text(10, $this->pdf->gety() + 10, 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
    $this->pdf->AddPage('L','legal');
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Dokumen');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['dokumen']);
			$this->pdf->text(200, $this->pdf->gety() + 10, 'Customer');
      $this->pdf->text(220, $this->pdf->gety() + 10, ': ' . $row['fullname']);
      $this->pdf->text(80, $this->pdf->gety() + 10, 'Tanggal');
      $this->pdf->text(95, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['tanggal'])));
			$this->pdf->text(10, $this->pdf->gety() + 15, 'FG');
      $this->pdf->text(30, $this->pdf->gety() + 15, ': ' . $row['productname']);
      $this->pdf->text(130, $this->pdf->gety() + 10, 'Gudang');
      $this->pdf->text(150, $this->pdf->gety() + 10, ': ' . $row['sloccode']);
      $sql1        = "select distinct b.productname,a.qty,c.uomcode,a.qty2,i.uomcode as uom2code,a.qty3,j.uomcode as uom3code,
a.qty4,k.uomcode as uom4code,e.description as rak,a.description,
getsloccode(a.slocfromid) as asal,l.bomversion,m.sloccode as tujuan
from productoutputdetail a
join product b on b.productid = a.productid
join unitofmeasure c on c.unitofmeasureid = a.uomid
join productplant d on d.productid = a.productid
join storagebin e on e.storagebinid = a.storagebintoid
join sloc f on f.slocid = d.slocid
join productoutput g on g.productoutputid = a.productoutputid
join productplan h on h.productplanid = g.productplanid
left join unitofmeasure i on i.unitofmeasureid = a.uom2id
left join unitofmeasure j on j.unitofmeasureid = a.uom3id
left join unitofmeasure k on j.unitofmeasureid = a.uom4id
left join billofmaterial l on l.bomid = a.bomid
join sloc m on m.slocid = a.sloctoid
				where a.productoutputid = " . $row['productoutputid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $total       = 0;
      $i           = 0;
      $totalqty    = 0;
      $this->pdf->sety($this->pdf->gety() + 20);
      $this->pdf->setFont('Arial', 'B', 8);
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
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        65,
        22,
        15,
        22,
        15,
        22,
        15,
        22,
        15,
        38,
        25,
        25,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Satuan',
        'Qty2',
        'Satuan 2',
        'Qty 3',
        'Satuan 3',
        'Qty 4',
        'Satuan 4',
        'Komposisi',
        'GD Asal',
        'GD Tujuan',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'L',
        'R',
        'L',
        'R',
        'L',
        'R',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          Yii::app()->format->formatNumber($row1['qty2']),
          $row1['uom2code'],
          Yii::app()->format->formatNumber($row1['qty3']),
          $row1['uom3code'],
          Yii::app()->format->formatNumber($row1['qty4']),
          $row1['uom4code'],
          $row1['bomversion'],
          $row1['asal'],
          $row1['tujuan'],
          $row1['description']
        ));
        $totalqty += $row1['qty'];
      }
      $this->pdf->row(array(
        '',
        'Total',
        Yii::app()->format->formatNumber($totalqty),
        '',
        '',
        '',
        '',
        ''
      ));
      $this->pdf->checkPageBreak(25);
    }
    $this->pdf->Output();
  }
	public function RekapPemakaianPerBarang($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
    parent::actionDownload();
    $sql        = "select distinct a.sloctoid,a.slocfromid, e.sloccode fromsloccode, e.description as fromslocdesc,
			f.sloccode as tosloccode,	f.description as toslocdesc
			from productoutputdetail a
			join product b on b.productid = a.productid
			join productoutput c on c.productoutputid = a.productoutputid
			join sloc e on e.slocid = a.slocfromid
			join sloc f on f.slocid = a.sloctoid
			join plant g on g.plantid = c.plantid
			join company h on h.companyid = g.companyid
			where c.recordstatus = 3 
			and (e.sloccode like '%" . $sloc . "%' or f.sloccode like '%" . $sloc . "%') 
			and g.plantid = " . $plantid . "
			and h.companyid = " . $companyid . " 
			and b.productname like '%" . $product . "%' 
			and c.productoutputdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
			and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Pemakaian Per Barang';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('L','A4');
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Asal');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['fromsloccode'] . ' - ' . $row['fromslocdesc']);
      $this->pdf->text(10, $this->pdf->gety() + 15, 'Tujuan');
      $this->pdf->text(30, $this->pdf->gety() + 15, ': ' . $row['tosloccode'] . ' - ' . $row['toslocdesc']);
      $sql1        = "select distinct a.productid,b.productname,d.uomcode,e.uomcode as uom2code,f.uomcode as uom3code,g.uomcode as uom4code,sum(a.qty) as qty,sum(a.qty2) as qty2, sum(a.qty3) as qty3, 
				sum(a.qty4) as qty4
				from productoutputdetail a
				join product b on b.productid = a.productid
				join productoutput c on c.productoutputid = a.productoutputid
				join unitofmeasure d on d.unitofmeasureid = a.uomid
				left join unitofmeasure e on e.unitofmeasureid = a.uom2id
				left join unitofmeasure f on f.unitofmeasureid = a.uom3id
				left join unitofmeasure g on g.unitofmeasureid = a.uom4id
				where c.recordstatus = 3 
				and a.slocfromid = " . $row['slocfromid'] . " 
				and a.sloctoid = " . $row['sloctoid'] . " 
				and b.productname like '%" . $product . "%' 
				and c.productoutputdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
				and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
				group by productid,productname";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $totalqty    = 0;
      $i           = 0;
      $this->pdf->sety($this->pdf->gety() + 20);
      $this->pdf->setFont('Arial', 'B', 8);
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
        10,
        120,
        30,
        30,
        30,
        30,
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Qty 2',
        'Qty 3',
        'Qty 4',
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'R',
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['qty']).' '.$row1['uomcode'],
          Yii::app()->format->formatNumber($row1['qty2']).' '.$row1['uom2code'],
          Yii::app()->format->formatNumber($row1['qty3']).' '.$row1['uom3code'],
          Yii::app()->format->formatNumber($row1['qty4']).' '.$row1['uom4code'],
        ));
        $totalqty += $row1['qty'];
      }
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
	public function PerbandinganPlanningOutput($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
    parent::actionDownload();
    $sql        = "select distinct a.productplanno,a.productplandate,a.productplanid,e.plantcode,g.fullname 
				from productplan a
				join plant e on e.plantid = a.plantid 
				join company f on f.companyid = e.companyid
				left join addressbook g on g.addressbookid = a.addressbookid 				
				where a.recordstatus >= 3 
				and a.productplanno is not null 
				and f.companyid = " . $companyid . " 
				and e.plantid = " . $plantid . " 
				and a.productplandate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
				and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Perbandingan Planning Output';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('L','legal');
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Dokumen');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['productplanno']);
      $this->pdf->text(100, $this->pdf->gety() + 10, 'Plant');
      $this->pdf->text(110, $this->pdf->gety() + 10, ': ' . $row['plantcode']);
      $this->pdf->text(160, $this->pdf->gety() + 10, 'Tanggal');
      $this->pdf->text(180, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])));
			 $this->pdf->text(220, $this->pdf->gety() + 10, 'Customer');
      $this->pdf->text(240, $this->pdf->gety() + 10, ': ' . $row['fullname']);
      $this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->text(10, $this->pdf->gety() + 22, 'FG');
      $sql1         = "select b.productname,a.qty as qtyplan, a.qty2 as qty2plan,a.qty3 as qty3plan,a.qty4 as qty4plan, (
					select ifnull(sum(ifnull(c.qty,0)),0)
					from productoutputfg c
					join productoutput g on g.productoutputid = c.productoutputid 
					where c.productplanfgid = a.productplanfgid and g.recordstatus = 3
					) as qtyout,
					(
					select ifnull(sum(ifnull(c.qty2,0)),0)
					from productoutputfg c
					join productoutput g on g.productoutputid = c.productoutputid 
					where c.productplanfgid = a.productplanfgid and g.recordstatus = 3
					) as qty2out,
					(
					select ifnull(sum(ifnull(c.qty3,0)),0)
					from productoutputfg c
					join productoutput g on g.productoutputid = c.productoutputid 
					where c.productplanfgid = a.productplanfgid and g.recordstatus = 3
					) as qty3out,
					(
					select ifnull(sum(ifnull(c.qty4,0)),0)
					from productoutputfg c
					join productoutput g on g.productoutputid = c.productoutputid 
					where c.productplanfgid = a.productplanfgid and g.recordstatus = 3
					) as qty4out,
					(
					select c.efektivitas
					from productoutputfg c
					join productoutput g on g.productoutputid = c.productoutputid 
					where c.productplanfgid = a.productplanfgid and g.recordstatus = 3 and c.productplanid = a.productplanid limit 1
					) as efektivitas,
					d.uomcode,f.sloccode,f.description as slocdesc,e.uomcode as uom2code,g.uomcode as uom3code,h.uomcode as uom4code
					from productplanfg a 
					inner join product b on b.productid = a.productid 
					inner join unitofmeasure d on d.unitofmeasureid = a.uomid
					left join unitofmeasure e on e.unitofmeasureid = a.uom2id
					left join unitofmeasure g on g.unitofmeasureid = a.uom3id
					left join unitofmeasure h on h.unitofmeasureid = a.uom4id
					inner join sloc f on f.slocid = a.sloctoid
					where a.productplanid = " . $row['productplanid']." group by a.productplanid,b.productid";
      $command1     = $this->connection->createCommand($sql1);
      $dataReader1  = $command1->queryAll();
      $totalqtyplan = 0;
      $i            = 0;
      $totalqtyout  = 0;
      $this->pdf->sety($this->pdf->gety() + 25);
      $this->pdf->setFont('Arial', 'B', 8);
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
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        7,
        80,
        25,
        25,
        25,
        
        25,
        25,
        
        25,
        25,
        
        25,
        25,
        
        25,
        25,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty Plan',
        'Qty Out',
        'Sisa',
        
        'Qty 2 Plan',
        'Qty 2 Out',
        
        'Qty 3 Plan',
        'Qty 3 Out',
        
        'Qty 4 Plan',
        'Qty 4 Out',
        'Efektivitas'
        
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'L',
        'R',
        'R',
        
        'R',
        'R',
        
        'R',
        'R',
        
        'R',
        'R',
        
        'R',
        'R',
        'L'
      );
      $this->pdf->setFont('Arial', '', 7);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['qtyplan']) .' '. $row1['uomcode'],
          Yii::app()->format->formatNumber($row1['qtyout']).' '. $row1['uomcode'],
          Yii::app()->format->formatNumber($row1['qtyplan']-$row1['qtyout']).' '. $row1['uomcode'],
          Yii::app()->format->formatNumber($row1['qty2plan']).' '. $row1['uom2code'],
          Yii::app()->format->formatNumber($row1['qty2out']).' '. $row1['uom2code'],
          Yii::app()->format->formatNumber($row1['qty3plan']).' '. $row1['uom3code'],
          Yii::app()->format->formatNumber($row1['qty3out']).' '. $row1['uom3code'],
          Yii::app()->format->formatNumber($row1['qty4plan']).' '. $row1['uom4code'],
          Yii::app()->format->formatNumber($row1['qty4out']).' '. $row1['uom4code'],
					$row1['efektivitas'].' '.'Menit'
        ));
        $totalqtyplan += $row1['qtyplan'];
        $totalqtyout += $row1['qtyout'];
      }
      $this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'Detail');
      $sql2          = "select distinct b.productname, a.qty as qtyplan,a.qty2 as qty2plan,a.qty3 as qty3plan, a.qty4 as qty4plan,
				ifnull(sum(ifnull(f.qty,0)),0) as qtyout, 
				ifnull(sum(ifnull(f.qty2,0)),0) as qty2out, 
				ifnull(sum(ifnull(f.qty3,0)),0) as qty3out, 
				ifnull(sum(ifnull(f.qty4,0)),0) as qty4out, 
				c.uomcode,  h.uomcode as uom2code, i.uomcode as uom3code, j.uomcode as uom4code,
				a.description		
				from productplandetail a
				left join productoutputdetail f on f.productplandetailid = a.productplandetailid
				left join product b on b.productid = a.productid
				left join unitofmeasure c on c.unitofmeasureid = a.uomid
				left join billofmaterial d on d.bomid = a.bomid
				left join sloc e on e.slocid = a.slocfromid 
				left join productoutput g on g.productoutputid=f.productoutputid
				left join unitofmeasure h on h.unitofmeasureid = a.uom2id
				left join unitofmeasure i on i.unitofmeasureid = a.uom3id
				left join unitofmeasure j on j.unitofmeasureid = a.uom4id
				where g.recordstatus >= 3 and b.isstock = 1 and a.productplanid = " . $row['productplanid']. "
				group by a.productplanid,b.productid";
      $command2      = $this->connection->createCommand($sql2);
      $dataReader2   = $command2->queryAll();
      $totalqtyplan1 = 0;
      $ii            = 0;
      $totalqtyout1  = 0;
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->setFont('Arial', 'B', 8);
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
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        7,
        70,
        25,
        25,
        15,
        25,
        25,
        15,
        25,
        25,
        15,
        25,
        25,
        15,
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty Plan',
        'Qty Out',
        'Satuan',
        'Qty 2 Plan',
        'Qty 2 Out',
        'Satuan',
        'Qty 3 Plan',
        'Qty 3 Out',
        'Satuan',
        'Qty 4 Plan',
        'Qty 4 Out',
        'Satuan',
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'L',
        'R',
        'R',
        'L',
        'R',
        'R',
        'L',
        'R',
        'R',
        'L'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader2 as $row2) {
        $ii += 1;
        $this->pdf->row(array(
          $ii,
          $row2['productname'],
          Yii::app()->format->formatNumber($row2['qtyplan']),
          Yii::app()->format->formatNumber($row2['qtyout']),
          $row2['uomcode'],
          Yii::app()->format->formatNumber($row2['qty2plan']),
          Yii::app()->format->formatNumber($row2['qty2out']),
          $row2['uom2code'],
          Yii::app()->format->formatNumber($row2['qty3plan']),
          Yii::app()->format->formatNumber($row2['qty3out']),
          $row2['uom3code'],
          Yii::app()->format->formatNumber($row2['qty4plan']),
          Yii::app()->format->formatNumber($row2['qty4out']),
          $row2['uom4code'],
        ));
        $totalqtyplan1 += $row2['qtyplan'];
        $totalqtyout1 += $row2['qtyout'];
      }
			$this->pdf->sety($this->pdf->gety() + 15);
			$this->pdf->checkNewPage(30);
    }
    $this->pdf->Output();
  }
	public function RwBelumAdaGudangAsal($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
    parent::actionDownload();
    $sql        = "select distinct a.productplanno,a.productplandate,a.productplanid
				from productplan a
				join productplandetail b on b.productplanid = a.productplanid
				join product c on c.productid = b.productid
				join sloc d on d.slocid = b.slocfromid
				join plant e on e.plantid = a.plantid 
				join company f on f.companyid = e.companyid 
				where a.recordstatus > 0 and d.sloccode like '%" . $sloc . "%' 
				and f.companyid = " . $companyid . " 
				and e.plantid = " . $plantid . " 
				and c.productname like '%" . $product . "%'		
				and b.slocfromid not in (select xx.slocid from productplant xx where xx.productid = b.productid)";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title = 'Raw Material Gudang Asal Belum Ada di Data Gudang - OK';
    $this->pdf->text(10, $this->pdf->gety() + 10, 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
    $this->pdf->AddPage('P');
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Dokumen');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['productplanno']);
      $this->pdf->text(10, $this->pdf->gety() + 15, 'Tanggal');
      $this->pdf->text(30, $this->pdf->gety() + 15, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])));
      $sql1        = "select distinct b.productname,a.qty,c.uomcode,d.description,d.productplandate,
				(select sloccode from sloc xx where xx.slocid = a.slocfromid) as sloc
				from productplandetail a
				join product b on b.productid = a.productid
				join unitofmeasure c on c.unitofmeasureid = a.uomid
				join productplan d on d.productplanid = a.productplanid
				join sloc e on e.slocid = a.slocfromid
				join plant f on f.plantid = d.plantid 
				join company g on g.companyid = f.companyid 
				where b.productname like '%" . $product . "%' 
				and e.sloccode like '%" . $sloc . "%' 
				and g.companyid = " . $companyid . " 
				and d.recordstatus > 0
			  and a.productplanid = " . $row['productplanid'] . "
				and a.slocfromid not in (select x.slocid from productplant x where x.productid = a.productid)";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $total       = 0;
      $i           = 0;
      $totalqty    = 0;
      $this->pdf->sety($this->pdf->gety() + 25);
      $this->pdf->setFont('Arial', 'B', 8);
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
        80,
        20,
        20,
        30,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Satuan',
        'Gudang Asal',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'L',
        'L'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          $row1['sloc'],
          $row1['description']
        ));
        $totalqty += $row1['qty'];
      }
      $this->pdf->row(array(
        '',
        'Total',
        Yii::app()->format->formatNumber($totalqty),
        '',
        ''
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
	public function RwBelumAdaGudangTujuan($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
    parent::actionDownload();
    $sql        = "select distinct a.productplanno,a.productplandate,a.productplanid,a.recordstatus
				from productplan a
				join productplandetail b on b.productplanid = a.productplanid
				join product c on c.productid = b.productid
				join sloc d on d.slocid = b.sloctoid
				join plant e on e.plantid = a.plantid
				where d.sloccode like '%" . $sloc . "%' 
				and a.recordstatus > 0 
				and e.companyid = " . $companyid . " 
				and e.plantid = " . $plantid . " 
				and c.productname like '%" . $product . "%'
				and b.sloctoid not in (select xx.slocid from productplant xx where xx.productid = b.productid)";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title = 'Raw Material Gudang Tujuan Belum Ada di Data Gudang - OK';
    $this->pdf->text(10, $this->pdf->gety() + 10, 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
    $this->pdf->AddPage('P');
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Dokumen');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['productplanno']);
      $this->pdf->text(10, $this->pdf->gety() + 15, 'Tanggal');
      $this->pdf->text(30, $this->pdf->gety() + 15, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])));
      $sql1        = "select distinct b.productname,a.qty,c.uomcode,d.description,
						(select sloccode from sloc xx where xx.slocid = a.sloctoid) as sloc
						from productplandetail a
						join product b on b.productid = a.productid
						join unitofmeasure c on c.unitofmeasureid = a.uomid
						join productplan d on d.productplanid = a.productplanid
						join sloc e on e.slocid = a.sloctoid
						join plant f on f.plantid = d.plantid
						where b.productname like '%" . $product . "%' 
						and e.sloccode like '%" . $sloc . "%'
						and f.companyid = " . $companyid . " 
						and f.plantid = " . $plantid . " 
						and d.recordstatus > 0 and a.productplanid = " . $row['productplanid'] . "
						and a.sloctoid not in (select x.slocid from productplant x where x.productid = a.productid)";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $total       = 0;
      $i           = 0;
      $totalqty    = 0;
      $this->pdf->sety($this->pdf->gety() + 25);
      $this->pdf->setFont('Arial', 'B', 8);
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
        80,
        20,
        20,
        30,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Satuan',
        'Gudang Tujuan',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'L',
        'L'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          $row1['sloc'],
          $row1['description']
        ));
        $totalqty += $row1['qty'];
      }
      $this->pdf->row(array(
        '',
        'Total',
        Yii::app()->format->formatNumber($totalqty),
        '',
        ''
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
	public function PendinganProduksi($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
    parent::actionDownload();
    $sql        = "select distinct a.productplanno,a.productplandate,a.productplanid,f.fullname,g.sono
			from productplan a
			join productplanfg b on b.productplanid = a.productplanid
			join product c on c.productid = b.productid
			join sloc d on d.slocid = b.sloctoid
			join plant e on e.plantid = a.plantid
			left join addressbook f on f.addressbookid = a.addressbookid
			left join soheader g on g.soheaderid = a.soheaderid
			where a.recordstatus = 4 and a.productplanno is not null 
			and d.sloccode like '%" . $sloc . "%' 
			and e.companyid = " . $companyid . " 
			and e.plantid = " . $plantid . " 
			and c.productname like '%" . $product . "%' 
			and b.qty > b.qtyres
			order by productplanno";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title = 'Pendingan Produksi Per Dokumen';
    $this->pdf->text(10, $this->pdf->gety() + 10, 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
    $this->pdf->AddPage('L',array(200,500));
    $alltotalqty       = 0;
    $alltotalqtyoutput = 0;
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Dokumen');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['productplanno']);
      $this->pdf->text(10, $this->pdf->gety() + 15, 'Tanggal');
      $this->pdf->text(30, $this->pdf->gety() + 15, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])));
			$this->pdf->text(75, $this->pdf->gety() + 10, 'No OS');
      $this->pdf->text(95, $this->pdf->gety() + 10, ': ' . $row['sono']);
			$this->pdf->text(75, $this->pdf->gety() + 15, 'Customer');
      $this->pdf->text(95, $this->pdf->gety() + 15, ': ' . $row['fullname']);
      $sql1           = "select distinct b.productname,a.qty,a.qtyres as qtyoutput,a.qty2res as qty2output,a.qty3res as qty3output,
				a.qty4res as qty4output,(a.qty-a.qtyres) as selisih,a.qty2,a.qty3,a.qty4,h.kodemesin,a.description,i.processprdname,
				c.uomcode,d.sloccode,e.uomcode as uom2code,f.uomcode as uom3code,f.uomcode as uom4code,(a.qty2-a.qty2res) as selisih2,
				(a.qty3-a.qty3res) as selisih3,
				(a.qty4-a.qty4res) as selisih4 
				from productplanfg a						
				join product b on b.productid = a.productid						
				join unitofmeasure c on c.unitofmeasureid = a.uomid						
				join sloc d on d.slocid = a.sloctoid
				left join unitofmeasure e on e.unitofmeasureid = a.uom2id						
				left join unitofmeasure f on f.unitofmeasureid = a.uom3id						
				left join unitofmeasure g on g.unitofmeasureid = a.uom4id						
				left join mesin h on h.mesinid = a.mesinid						
				left join processprd i on i.processprdid = a.processprdid						
				where b.productname like '%" . $product . "%' 
				and d.sloccode like '%" . $sloc . "%' 
				and a.qty > a.qtyres
				and a.productplanid = " . $row['productplanid'];
      $command1       = $this->connection->createCommand($sql1);
      $dataReader1    = $command1->queryAll();
      $total          = 0;
      $i              = 0;
      $totalqty       = 0;
      $totalqtyoutput = 0;
      $this->pdf->sety($this->pdf->gety() + 20);
      $this->pdf->setFont('Arial', 'B', 8);
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
        10,
        70,
        25,
        25,
        25,
      
        25,
        25,
        25,
      
        25,
        25,
        25,
      
        25,
        25,
        25,
        
        25,
        20,
        20,
        40
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Qty Output',
        'Selisih',
      
        'Qty 2',
        'Qty 2 Output',
        'Selisih 2',
       
        'Qty 3',
        'Qty 3 Output',
        'Selisih 3',
        
        'Qty 4',
        'Qty 4 Output',
        'Selisih 4',
        
        'Gudang',
        'Mesin',
        'Proses',
        'Keterangan',
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
     
        'R',
        'R',
        'R',
     
        'R',
        'R',
        'R',
        
        'R',
        'R',
        'R',
        
        'R',
        'R',
        'R',
       
        'R',
        'R',
        'R',
        
        'L',
        'L',
        'L',
        'L',
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['qty']).' '. $row1['uomcode'],
          Yii::app()->format->formatNumber($row1['qtyoutput']).' '. $row1['uomcode'],
          Yii::app()->format->formatNumber($row1['selisih']).' '. $row1['uomcode'],
          
          Yii::app()->format->formatNumber($row1['qty2']).' '. $row1['uom2code'],
          Yii::app()->format->formatNumber($row1['qty2output']).' '. $row1['uom2code'],
          Yii::app()->format->formatNumber($row1['selisih2']).' '. $row1['uom2code'],
          
          Yii::app()->format->formatNumber($row1['qty3']).' '. $row1['uom3code'],
          Yii::app()->format->formatNumber($row1['qty3output']).' '. $row1['uom3code'],
          Yii::app()->format->formatNumber($row1['selisih3']).' '. $row1['uom3code'],
          
          Yii::app()->format->formatNumber($row1['qty4']).' '. $row1['uom4code'],
          Yii::app()->format->formatNumber($row1['qty4output']).' '. $row1['uom4code'],
          Yii::app()->format->formatNumber($row1['selisih4']).' '. $row1['uom4code'],
          
          $row1['sloccode'],
          $row1['kodemesin'],
          $row1['processprdname'],
          $row1['description'],
        ));
        $totalqty += $row1['qty'];
        $totalqtyoutput += $row1['qtyoutput'];
        $alltotalqty += $row1['qty'];
        $alltotalqtyoutput += $row1['qtyoutput'];
      }
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
	public function RincianPendinganProduksiPerBarang($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
    parent::actionDownload();
    $subtotalqty       = 0;
    $subtotalqtyoutput = 0;
    $subtotalselisih   = 0;
    $sql               = "select distinct d.description,d.slocid
			from productplan a
			join productplanfg b on b.productplanid = a.productplanid
			join product c on c.productid = b.productid
			join sloc d on d.slocid = b.sloctoid
			join plant e on e.plantid = a.plantid
			where a.recordstatus >= 3 
			and d.sloccode like '%" . $sloc . "%' 
			and e.companyid = " . $companyid . " 
			and e.plantid = " . $plantid . " 
			and c.productname like '%" . $product . "%' and b.qty > b.qtyres";
    $command           = $this->connection->createCommand($sql);
    $dataReader        = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rincian Pendingan Produksi Per Barang';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('L',array(200,510));
    foreach ($dataReader as $row) {
			$this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'GUDANG');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . $row['description']);
      $this->pdf->SetFont('Arial', '', 9);
      $sql1           = "select distinct b.productname,b.productid
				from productplanfg a	
				join product b on b.productid = a.productid	
				join unitofmeasure c on c.unitofmeasureid = a.uomid	
				join sloc d on d.slocid = a.sloctoid
				join productplan e on e.productplanid = a.productplanid	
				join plant f on f.plantid = e.plantid	
				where b.productname like '%" . $product . "%' and d.sloccode like '%" . $sloc . "%' and a.qty > a.qtyres
				and f.companyid = " . $companyid . " and e.recordstatus >= 3
				and e.productplanno is not null
				and a.sloctoid = " . $row['slocid'] . " ";
      $command1       = $this->connection->createCommand($sql1);
      $dataReader1    = $command1->queryAll();
      $totalqty       = 0;
      $totalqtyoutput = 0;
      $totalselisih   = 0;
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->text(10, $this->pdf->gety() + 15, 'Nama Barang ');
        $this->pdf->text(50, $this->pdf->gety() + 15, ': ' . $row1['productname']);
        $sql2        = "select distinct e.productplanid,b.productname,a.qty,a.qtyres as qtyoutput,(a.qty-a.qtyres) as selisih,
					c.uomcode,a.qty2,a.qty2res as qty2output,(a.qty2-a.qty2res) as selisih2,
					g.uomcode as uom2code,a.qty3,a.qty3res as qty3output,(a.qty3-a.qty3res) as selisih3,
					h.uomcode as uom3code,a.qty4,a.qty4res as qty4output,(a.qty4-a.qty4res) as selisih4,
					i.uomcode as uom4code,d.description as sloc,e.productplanno,e.productplandate,a.startdate
					from productplanfg a	
					join product b on b.productid = a.productid	
					join unitofmeasure c on c.unitofmeasureid = a.uomid	
					join sloc d on d.slocid = a.sloctoid
					join productplan e on e.productplanid = a.productplanid	
					join plant f on f.plantid = e.plantid	
					left join unitofmeasure g on g.unitofmeasureid = a.uom2id	
					left join unitofmeasure h on h.unitofmeasureid = a.uom3id	
					left join unitofmeasure i on i.unitofmeasureid = a.uom4id	
					where b.productname like '%" . $product . "%' and d.sloccode like '%" . $sloc . "%' and a.qty > a.qtyres
					and f.companyid = " . $companyid . " 
					and f.plantid = " . $plantid . " 
					and e.recordstatus >= 3
					and e.productplanno is not null
					and b.productid = " . $row1['productid'] . " and d.slocid = " . $row['slocid'] . "";
        $command2    = $this->connection->createCommand($sql2);
        $dataReader2 = $command2->queryAll();
        $this->pdf->sety($this->pdf->gety() + 18);
        $this->pdf->setFont('Arial', '', 8);
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
          10,
          40,
          25,
          30,
          30,
          30,
          15,
          30,
          30,
          30,
          15,
          30,
          30,
          30,
          15,
          30,
          30,
          30,
          15,
        ));
        $this->pdf->colheader = array(
          'No',
          'No OK',
          'Tgl Mulai',
          'Qty OK',
          'Qty HP',
          'Selisih',
          'Satuan',
          'Qty 2 OK',
          'Qty 2 HP',
          'Selisih 2',
          'Satuan 2',
          'Qty 3 OK',
          'Qty 3 HP',
          'Selisih 3',
          'Satuan 3',
          'Qty 4 OK',
          'Qty 4 HP',
          'Selisih 4',
          'Satuan 4',
        );
        $this->pdf->RowHeader();
        $this->pdf->coldetailalign = array(
          'C',
          'L',
          'C',
          'R',
          'R',
          'R',
          'L',
          'R',
          'R',
          'R',
          'L',
          'R',
          'R',
          'R',
          'L',
          'R',
          'R',
          'R',
          'L',
        );
        $i                         = 0;
        $jumlahqty                 = 0;
        $jumlahqtyoutput           = 0;
        $jumlahselisih             = 0;
        foreach ($dataReader2 as $row2) {
          $i += 1;
          $this->pdf->row(array(
            $i,
            $row2['productplanno'],
            $row2['startdate'],
            Yii::app()->format->formatNumber($row2['qty']),
            Yii::app()->format->formatNumber($row2['qtyoutput']),
            Yii::app()->format->formatNumber($row2['selisih']),
            $row2['uomcode'],
            Yii::app()->format->formatNumber($row2['qty2']),
            Yii::app()->format->formatNumber($row2['qty2output']),
            Yii::app()->format->formatNumber($row2['selisih2']),
            $row2['uom2code'],
            Yii::app()->format->formatNumber($row2['qty3']),
            Yii::app()->format->formatNumber($row2['qty3output']),
            Yii::app()->format->formatNumber($row2['selisih3']),
            $row2['uom3code'],
            Yii::app()->format->formatNumber($row2['qty4']),
            Yii::app()->format->formatNumber($row2['qty4output']),
            Yii::app()->format->formatNumber($row2['selisih4']),
            $row2['uom4code'],
          ));
          $jumlahqty += $row2['qty'];
          $jumlahqtyoutput += $row2['qtyoutput'];
          $jumlahselisih += $row2['selisih'];
        }
        $this->pdf->setFont('Arial', 'B', 8);
        $totalqty += $jumlahqty;
        $totalqtyoutput += $jumlahqtyoutput;
        $totalselisih += $jumlahselisih;
				$this->pdf->checknewpage(30);
      }
      $this->pdf->setFont('Arial', 'B', 9);
      $subtotalqty += $totalqty;
      $subtotalqtyoutput += $totalqtyoutput;
      $subtotalselisih += $totalselisih;
      $this->pdf->checknewpage(20);
    }
    $this->pdf->Output();
  }
	public function RekapPendinganProduksiPerBarang($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
    parent::actionDownload();
    $subtotalqty       = 0;
    $subtotalqtyoutput = 0;
    $subtotalselisih   = 0;
    $sql               = "select distinct d.description,d.slocid
						 from productplan a
						 join productplanfg b on b.productplanid = a.productplanid
						 join product c on c.productid = b.productid
						 join sloc d on d.slocid = b.sloctoid
						 join plant e on e.plantid = a.plantid
						 where a.recordstatus >= 3 
						 and d.sloccode like '%" . $sloc . "%' 
						 and e.companyid = " . $companyid . " 
						 and e.plantid = " . $plantid . " 
						 and c.productname like '%" . $product . "%' and b.qty > b.qtyres
						 order by productplanno";
    $command           = $this->connection->createCommand($sql);
    $dataReader        = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Pendingan Produksi Per Barang';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('L',array(200,510));
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', 'B', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'GUDANG');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['description']);
      $sql1           = "select *,sum(qty) as sumqty,sum(qtyoutput) as sumqtyoutput,sum(selisih) as sumselisih,
				sum(qty2) as sumqty2,sum(qty2output) as sumqty2output,sum(selisih2) as sumselisih2,
				sum(qty3) as sumqty3,sum(qty3output) as sumqty3output,sum(selisih3) as sumselisih3,
				sum(qty4) as sumqty4,sum(qty4output) as sumqty4output,sum(selisih4) as sumselisih4
				from
				 (select distinct e.productplanid,b.productname,a.qty,a.qtyres as qtyoutput,(a.qty-a.qtyres) as selisih,
				c.uomcode,d.description as sloc,e.productplanno,e.productplandate,
				a.qty2,a.qty2res as qty2output,	(a.qty2-a.qty2res) as selisih2,g.uomcode as uom2code,
				a.qty3,a.qty3res as qty3output,	(a.qty3-a.qty3res) as selisih3,h.uomcode as uom3code,
				a.qty4,a.qty4res as qty4output,	(a.qty4-a.qty4res) as selisih4,i.uomcode as uom4code
				from productplanfg a	
				join product b on b.productid = a.productid	
				join unitofmeasure c on c.unitofmeasureid = a.uomid	
				join sloc d on d.slocid = a.sloctoid
				join productplan e on e.productplanid = a.productplanid	
				join plant f on f.plantid = e.plantid	
				left join unitofmeasure g on g.unitofmeasureid = a.uom2id	
				left join unitofmeasure h on h.unitofmeasureid = a.uom3id	
				left join unitofmeasure i on i.unitofmeasureid = a.uom4id	
				where b.productname like '%" . $product . "%' and d.sloccode like '%" . $sloc . "%' and a.qty > a.qtyres
				and f.companyid = " . $companyid . " 
				and f.plantid = " . $plantid . " 
				and e.recordstatus >= 3
				and e.productplanno is not null
				and a.sloctoid = " . $row['slocid'] . " order by productname) z group by productname";
      $command1       = $this->connection->createCommand($sql1);
      $dataReader1    = $command1->queryAll();
      $totalqty       = 0;
      $i              = 0;
      $totalqtyoutput = 0;
      $totalselisih   = 0;
      $this->pdf->sety($this->pdf->gety() + 15);
      $this->pdf->setFont('Arial', 'B', 8);
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
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        70,
        30,
        30,
        30,
        15,
        30,
        30,
        30,
        15,
        30,
        30,
        30,
        15,
        30,
        30,
        30,
        15,
        30,
        30,
        30,
        15,
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty OK',
        'Qty HP',
        'Selisih',
        'Satuan',
        'Qty 2 OK',
        'Qty 2 HP',
        'Selisih 2',
        'Satuan 2',
        'Qty 3 OK',
        'Qty 3 HP',
        'Selisih 3',
        'Satuan 3',
        'Qty 4 OK',
        'Qty 4 HP',
        'Selisih 4',
        'Satuan 4',
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'C',
        'L',
        'R',
        'R',
        'R',
        'L',
        'R',
        'R',
        'R',
        'L',
        'R',
        'R',
        'R',
        'L',
        'R',
        'R',
        'R',
        'L',
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['sumqty']),
          Yii::app()->format->formatNumber($row1['sumqtyoutput']),
          Yii::app()->format->formatNumber($row1['sumselisih']),
          $row1['uomcode'],
          Yii::app()->format->formatNumber($row1['sumqty2']),
          Yii::app()->format->formatNumber($row1['sumqty2output']),
          Yii::app()->format->formatNumber($row1['sumselisih2']),
          $row1['uom2code'],
          Yii::app()->format->formatNumber($row1['sumqty3']),
          Yii::app()->format->formatNumber($row1['sumqty3output']),
          Yii::app()->format->formatNumber($row1['sumselisih3']),
          $row1['uom3code'],
          Yii::app()->format->formatNumber($row1['sumqty4']),
          Yii::app()->format->formatNumber($row1['sumqty4output']),
          Yii::app()->format->formatNumber($row1['sumselisih4']),
          $row1['uom4code'],
        ));
        $totalqty += $row1['sumqty'];
        $totalqtyoutput += $row1['sumqtyoutput'];
        $totalselisih += $row1['sumselisih'];
      }
      $subtotalqty += $totalqty;
      $subtotalqtyoutput += $totalqtyoutput;
      $subtotalselisih += $totalselisih;
    }
    $this->pdf->sety($this->pdf->gety() + 5);
    $this->pdf->setFont('Arial', 'B', 11);
    $this->pdf->Output();
  }
	public function RekapProduksiPerBarangPerHari($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
    parent::actionDownload();
    $sql        = "select distinct g.materialgroupid,g.description
				from productoutput a
				join productoutputfg b on b.productoutputid = a.productoutputid
				join product c on c.productid = b.productid
				join sloc d on d.slocid = b.slocid
				join productplan e on e.productplanid = a.productplanid
				join productplant f on f.productid = b.productid
				join materialgroup g on g.materialgroupid = f.materialgroupid
				join plant h on h.plantid = a.plantid
				where a.productoutputno is not null 
				and h.companyid = " . $companyid . " 
				and h.plantid = " . $plantid . " 
				and a.recordstatus = 3
				and d.sloccode like '%" . $sloc . "%' 
				and c.productname like '%" . $product . "%' 
				and a.productoutputdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
				and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Produksi Per Barang Per Hari';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('L', array(200,550));
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 7);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Grup Material');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . $row['description']);
      $sql1        = "select distinct productname,productid,uomcode,materialgroupid,sum(qty) as qtyoutput,d1, 																						d2,d3,d4,d5,d6,d7,d8,d9,d10,d11,d12,d13,d14,d15,d16,d17,d18,d19,d20,d21,d22,d23,d24,d25,d26,d27,d28,d29,d30,d31 from 
								(select distinct b.productname,b.productid,a.qty,e.uomcode,c.materialgroupid,a.productoutputfgid,(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and day(l.productoutputdate) = 1 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d1,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and day(l.productoutputdate) = 2 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d2,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 3 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d3,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and day(l.productoutputdate) = 4 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d4,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 5 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d5,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 6 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d6,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 7 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d7,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 8 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d8,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 9 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d9,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 10 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d10,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 11 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d11,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 12 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d12,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 13 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d13,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 14 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d14,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 15 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d15,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 16 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d16,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 17
								and l.recordstatus = 3 and k.productid = a.productid
								) as d17,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 18 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d18,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 19 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d19,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 20 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d20,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 21 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d21,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 22 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d22,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 23 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d23,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 24 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d24,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 25 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d25,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 26 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d26,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 27 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d27,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 28 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d28,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 29 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d29,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 30 
								and l.recordstatus = 3 and k.productid = a.productid
								) as d30,

								(select ifnull(sum(k.qty),0)
								from productoutputfg k
								join productoutput l on l.productoutputid = k.productoutputid
								where year(l.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') 
								and month(l.productoutputdate) = month('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
								and day(l.productoutputdate) = 31 
								and l.recordstatus = 3 and k.productid = a.productid
								)as d31

								from productoutputfg a
								inner join product b on b.productid = a.productid
								inner join productoutput d on d.productoutputid = a.productoutputid
								inner join unitofmeasure e on e.unitofmeasureid = a.uomid
								inner join productplant c on c.productid = a.productid and c.slocid = a.slocid and c.uom1 = a.uomid
								join sloc f on f.slocid = a.slocid
								join productplan g on g.productplanid = d.productplanid 
								join plant h on h.plantid = f.plantid 
								where b.productname like '%" . $product . "%' and d.recordstatus = 3 and f.sloccode like '%" . $sloc . "%'
								and h.companyid = " . $companyid . " 
								and h.plantid = " . $plantid . " 
								and d.productoutputdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
								and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' and c.materialgroupid = " . $row['materialgroupid'] . " ) z 
								group by productname,uomcode,materialgroupid";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $totalqty    = 0;
      $i           = 0;
      $this->pdf->sety($this->pdf->gety() + 15);
      $this->pdf->setFont('Arial', 'B', 6);
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
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        8,
        35,
        10,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15,
        15
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Satuan',
        'Qty',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        '10',
        '11',
        '12',
        '13',
        '14',
        '15',
        '16',
        '17',
        '18',
        '19',
        '20',
        '21',
        '22',
        '23',
        '24',
        '25',
        '26',
        '27',
        '28',
        '29',
        '30',
        '31'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'C',
        'L',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', '', 6);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          $row1['uomcode'],
          Yii::app()->format->formatNum($row1['qtyoutput']),
          Yii::app()->format->formatNum($row1['d1']),
          Yii::app()->format->formatNum($row1['d2']),
          Yii::app()->format->formatNum($row1['d3']),
          Yii::app()->format->formatNum($row1['d4']),
          Yii::app()->format->formatNum($row1['d5']),
          Yii::app()->format->formatNum($row1['d6']),
          Yii::app()->format->formatNum($row1['d7']),
          Yii::app()->format->formatNum($row1['d8']),
          Yii::app()->format->formatNum($row1['d9']),
          Yii::app()->format->formatNum($row1['d10']),
          Yii::app()->format->formatNum($row1['d11']),
          Yii::app()->format->formatNum($row1['d12']),
          Yii::app()->format->formatNum($row1['d13']),
          Yii::app()->format->formatNum($row1['d14']),
          Yii::app()->format->formatNum($row1['d15']),
          Yii::app()->format->formatNum($row1['d16']),
          Yii::app()->format->formatNum($row1['d17']),
          Yii::app()->format->formatNum($row1['d18']),
          Yii::app()->format->formatNum($row1['d19']),
          Yii::app()->format->formatNum($row1['d20']),
          Yii::app()->format->formatNum($row1['d21']),
          Yii::app()->format->formatNum($row1['d22']),
          Yii::app()->format->formatNum($row1['d23']),
          Yii::app()->format->formatNum($row1['d24']),
          Yii::app()->format->formatNum($row1['d25']),
          Yii::app()->format->formatNum($row1['d26']),
          Yii::app()->format->formatNum($row1['d27']),
          Yii::app()->format->formatNum($row1['d28']),
          Yii::app()->format->formatNum($row1['d29']),
          Yii::app()->format->formatNum($row1['d30']),
          Yii::app()->format->formatNum($row1['d31'])
        ));
        $totalqty += $row1['qtyoutput'];
      }
      $this->pdf->row(array(
        '',
        'Total ' . $row['description'],
        '',
        Yii::app()->format->formatNumber($totalqty)
      ));
      $this->pdf->checkPageBreak(30);
    }
    $this->pdf->Output();
  }
	public function RekapHasilProduksiPerDokumentBelumStatusMax($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
    parent::actionDownload();
    $sql        = "select distinct b.productoutputid,b.productoutputid,b.recordstatus,
					b.productoutputno,b.productoutputdate,c.productplanno,b.headernote 
					from productoutput b
					join productplan c on c.productplanid = b.productplanid
					join productoutputfg d on d.productoutputid = b.productoutputid
					join product e on e.productid = d.productid
					join sloc f on f.slocid = d.slocid
					join plant g on g.plantid = b.plantid
					where e.productname like '%" . $product . "%' and f.sloccode like '%" . $sloc . "%' 
					and g.companyid = " . $companyid . "
					and g.plantid = " . $plantid . "
					and b.recordstatus between 1 and (3-1) 
					order by b.recordstatus";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Hasil Produksi Per Dokumen Status Belum Max';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->setFont('Arial', 'B', 8);
    $this->pdf->sety($this->pdf->gety() + 10);
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
      25,
      25,
      30,
      30,
      50,
      20
    ));
    $this->pdf->colheader = array(
      'No',
      'ID Transaksi',
      'No Transaksi',
      'Tanggal',
      'No Referensi',
      'Keterangan',
      'Status'
    );
    $this->pdf->RowHeader();
    $this->pdf->coldetailalign = array(
      'C',
      'C',
      'C',
      'C',
      'C',
      'L',
      'C'
    );
    $totalnominal1             = 0;
    $i                         = 0;
    $totaldisc1                = 0;
    $totaljumlah1              = 0;
    foreach ($dataReader as $row) {
      $i += 1;
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->row(array(
        $i,
        $row['productoutputid'],
        $row['productoutputno'],
        date(Yii::app()->params['dateviewfromdb'], strtotime($row['productoutputdate'])),
        $row['productplanno'],
        $row['headernote'],
        findstatusname("appop", $row['recordstatus'])
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->colalign = array(
      'C',
      'C',
      'C',
      'C'
    );
    $this->pdf->setwidths(array(
      40,
      50,
      40,
      40
    ));
    $this->pdf->setFont('Arial', 'B', 9);
    $this->pdf->Output();
  }
	public function RekapProduksiPerBarangPerBulan($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
    parent::actionDownload();
    $sql        = "select distinct g.materialgroupid,g.description
				from productoutput a
				join productoutputfg b on b.productoutputid = a.productoutputid
				join product c on c.productid = b.productid
				join sloc d on d.slocid = b.slocid
				join productplan e on e.productplanid = a.productplanid
				join productplant f on f.productid = b.productid
				join materialgroup g on g.materialgroupid = f.materialgroupid
				join plant h on h.plantid = a.plantid
				where a.productoutputno is not null 
				and h.companyid = " . $companyid . " 
				and h.plantid = " . $plantid . " 
				and a.recordstatus = 3
				and d.sloccode like '%" . $sloc . "%' and c.productname like '%" . $product . "%' 
				and year(a.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Produksi Per Barang Per Bulan';
    $this->pdf->subtitle = 'Per Tahun : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('L',array(200,410));
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Divisi');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['description']);
      $sql1        = "select *
					from (select distinct b.productname,e.uomcode,i.uomcode as uom2code,j.uomcode as uom3code,k.uomcode as uom4code,
					ifnull((select sum(k.qty)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 1 and m.materialgroupid = c.materialgroupid),0) as januari,
					ifnull((select sum(k.qty)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 2 and m.materialgroupid = c.materialgroupid),0) as februari,
					ifnull((select sum(k.qty)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 3 and m.materialgroupid = c.materialgroupid),0) as maret,
					ifnull((select sum(k.qty)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 4 and m.materialgroupid = c.materialgroupid),0) as april,
					ifnull((select sum(k.qty)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 5 and m.materialgroupid = c.materialgroupid),0) as mei,
					ifnull((select sum(k.qty)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 6 and m.materialgroupid = c.materialgroupid),0) as juni,
					ifnull((select sum(k.qty)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 7 and m.materialgroupid = c.materialgroupid),0) as juli,
					ifnull((select sum(k.qty)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 8 and m.materialgroupid = c.materialgroupid),0) as agustus,
					ifnull((select sum(k.qty)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 9 and m.materialgroupid = c.materialgroupid),0) as september,
					ifnull((select sum(k.qty)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 10 and m.materialgroupid = c.materialgroupid),0) as oktober,
					ifnull((select sum(k.qty)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 11 and m.materialgroupid = c.materialgroupid),0) as nopember,
					ifnull((select sum(k.qty)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 12 and m.materialgroupid = c.materialgroupid),0) as desember,
					ifnull((select sum(k.qty)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and m.materialgroupid = c.materialgroupid),0) as jumlah,
					ifnull((select sum(k.qty2)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 1 and m.materialgroupid = c.materialgroupid),0) as januari2,
					ifnull((select sum(k.qty2)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 2 and m.materialgroupid = c.materialgroupid),0) as februari2,
					ifnull((select sum(k.qty2)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 3 and m.materialgroupid = c.materialgroupid),0) as maret2,
					ifnull((select sum(k.qty2)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 4 and m.materialgroupid = c.materialgroupid),0) as april2,
					ifnull((select sum(k.qty2)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 5 and m.materialgroupid = c.materialgroupid),0) as mei2,
					ifnull((select sum(k.qty2)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 6 and m.materialgroupid = c.materialgroupid),0) as juni2,
					ifnull((select sum(k.qty2)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 7 and m.materialgroupid = c.materialgroupid),0) as juli2,
					ifnull((select sum(k.qty2)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 8 and m.materialgroupid = c.materialgroupid),0) as agustus2,
					ifnull((select sum(k.qty2)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 9 and m.materialgroupid = c.materialgroupid),0) as september2,
					ifnull((select sum(k.qty2)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 10 and m.materialgroupid = c.materialgroupid),0) as oktober2,
					ifnull((select sum(k.qty2)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 11 and m.materialgroupid = c.materialgroupid),0) as nopember2,
					ifnull((select sum(k.qty2)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and month(n.productoutputdate) = 12 and m.materialgroupid = c.materialgroupid),0) as desember2,
					ifnull((select sum(k.qty2)
						from productoutputfg k
						join product l on l.productid = k.productid
						join productoutput n on n.productoutputid = k.productoutputid
						join unitofmeasure o on o.unitofmeasureid = k.uomid
						join productplant m on m.productid = k.productid and m.slocid = k.slocid and m.uom1 = k.uomid
						join sloc p on p.slocid = k.slocid
						join productplan q on q.productplanid = n.productplanid 
						join plant r on r.plantid = n.plantid
						where n.recordstatus = 3 and k.productid = a.productid and k.slocid = a.slocid and r.companyid = h.companyid and r.plantid = h.plantid
						and year(n.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
						and m.materialgroupid = c.materialgroupid),0) as jumlah2
					from productoutputfg a
					inner join product b on b.productid = a.productid
					inner join productoutput d on d.productoutputid = a.productoutputid
					inner join unitofmeasure e on e.unitofmeasureid = a.uomid
					inner join productplant c on c.productid = a.productid and c.slocid = a.slocid and c.uom1 = a.uomid
					join sloc f on f.slocid = a.slocid
					join productplan g on g.productplanid = d.productplanid 
					join plant h on h.plantid = d.plantid 
					left join unitofmeasure i on i.unitofmeasureid = a.uom2id
					left join unitofmeasure j on j.unitofmeasureid = a.uom3id
					left join unitofmeasure k on k.unitofmeasureid = a.uom4id
					where b.productname like '%" . $product . "%' and d.recordstatus = 3 
					and f.sloccode like '%" . $sloc . "%'
					and h.companyid = " . $companyid . " 
					and h.plantid = " . $plantid . " 
					and year(d.productoutputdate) = year('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and c.materialgroupid = " . $row['materialgroupid'] . ") z 
					group by productname,uomcode";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $totaljanuari=0;$totalfebruari=0;$totalmaret=0;$totalapril=0;$totalmei=0;$totaljuni=0;$totaljuli=0;$totalagustus=0;$totalseptember=0;$totaloktober=0;$totalnopember=0;$totaldesember=0;$totaljumlah=0;
      $i           = 0;
      $this->pdf->sety($this->pdf->gety() + 15);
      $this->pdf->setFont('Arial', 'B', 8);
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
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        90,
        20,
        20,
        20,
        20,
        20,
        20,
        20,
        20,
        20,
        20,
        20,
        20,
        20,
        20
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Satuan',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        '10',
        '11',
        '12',
        'Jumlah',
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'C',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          $row1['uomcode'],
          Yii::app()->format->formatCurrency($row1['januari']),
          Yii::app()->format->formatCurrency($row1['februari']),
          Yii::app()->format->formatCurrency($row1['maret']),
          Yii::app()->format->formatCurrency($row1['april']),
          Yii::app()->format->formatCurrency($row1['mei']),
          Yii::app()->format->formatCurrency($row1['juni']),
          Yii::app()->format->formatCurrency($row1['juli']),
          Yii::app()->format->formatCurrency($row1['agustus']),
          Yii::app()->format->formatCurrency($row1['september']),
          Yii::app()->format->formatCurrency($row1['oktober']),
          Yii::app()->format->formatCurrency($row1['nopember']),
          Yii::app()->format->formatCurrency($row1['desember']),
          Yii::app()->format->formatCurrency($row1['jumlah']),
        ));
				$this->pdf->row(array(
          '',
          '',
          $row1['uom2code'],
          Yii::app()->format->formatCurrency($row1['januari2']),
          Yii::app()->format->formatCurrency($row1['februari2']),
          Yii::app()->format->formatCurrency($row1['maret2']),
          Yii::app()->format->formatCurrency($row1['april2']),
          Yii::app()->format->formatCurrency($row1['mei2']),
          Yii::app()->format->formatCurrency($row1['juni2']),
          Yii::app()->format->formatCurrency($row1['juli2']),
          Yii::app()->format->formatCurrency($row1['agustus2']),
          Yii::app()->format->formatCurrency($row1['september2']),
          Yii::app()->format->formatCurrency($row1['oktober2']),
          Yii::app()->format->formatCurrency($row1['nopember2']),
          Yii::app()->format->formatCurrency($row1['desember2']),
          Yii::app()->format->formatCurrency($row1['jumlah2']),
        ));
        $totaljanuari += $row1['januari'];
        $totalfebruari += $row1['februari'];
        $totalmaret += $row1['maret'];
        $totalapril += $row1['april'];
        $totalmei += $row1['mei'];
        $totaljuni += $row1['juni'];
        $totaljuli += $row1['juli'];
        $totalagustus += $row1['agustus'];
        $totalseptember += $row1['september'];
        $totaloktober += $row1['oktober'];
        $totalnopember += $row1['nopember'];
        $totaldesember += $row1['desember'];
        $totaljumlah += $row1['jumlah'];
      }
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
	public function JadwalProduksi($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
    parent::actionDownload();
    $sql        = "select distinct startdate
                  from productplanfg a
                  join productplan b on b.productplanid=a.productplanid
                  join sloc c on c.slocid=a.sloctoid
                  join product d on d.productid=a.productid
                  join plant e on e.plantid=b.plantid
                  where b.productplanno is not null 
									and e.companyid = " . $companyid . " 
									and e.plantid = " . $plantid . " 
									and b.recordstatus = 4
                  and c.sloccode like '%" . $sloc . "%' and d.productname like '%" . $product . "%' 
                  and a.startdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'  and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' ";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) 
    {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Jadwal Produksi (OK)';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P','A4');
    foreach ($dataReader as $row) 
    {
      $this->pdf->SetFont('Arial', 'B', 10);
      $this->pdf->text(10, $this->pdf->gety()+5, 'Tanggal');
      $this->pdf->text(25, $this->pdf->gety()+5, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['startdate'])));
      $sql1        = "select distinct f.materialgroupid,f.description
                      from productplanfg a
                      join productplan b on b.productplanid=a.productplanid
                      join sloc c on c.slocid=a.sloctoid
                      join product d on d.productid=a.productid
                      join productplant e on e.productid=a.productid and e.slocid=a.sloctoid and e.uom1=a.uomid
                      join materialgroup f on f.materialgroupid=e.materialgroupid
                      join plant g on g.plantid=b.plantid
                      where b.productplanno is not null 
											and g.companyid = " . $companyid . " 
											and g.plantid = " . $plantid . " 
											and b.recordstatus = 4
                      and c.sloccode like '%" . $sloc . "%' and d.productname like '%" . $product . "%' and a.startdate = '".$row['startdate']."'
                      and a.startdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'  and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' 
                      order by description";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      foreach ($dataReader1 as $row1)
      {
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->sety($this->pdf->gety()+5);
        $this->pdf->text(10, $this->pdf->gety()+5, 'Material Group');
        $this->pdf->text(35, $this->pdf->gety()+5, ': ' . $row1['description']);
        $this->pdf->sety($this->pdf->gety()+7);        
        $this->pdf->setFont('Arial', 'B', 8);
        $this->pdf->colalign = array('C','C','C','C','C','C','C');
        $this->pdf->setwidths(array(8,22,90,15,20,20,20));
        $this->pdf->colheader = array('No','OK','Nama Barang','Satuan','Qty','Tgl Mulai','Tgl Selesai');
        $this->pdf->RowHeader();
        $this->pdf->coldetailalign = array('C','C','L','C','R','C','C');
        
        $sql2        = "select b.productplanno,d.productname,g.uomcode,a.qty,a.startdate,a.enddate
                        from productplanfg a
                        join productplan b on b.productplanid=a.productplanid
                        join sloc c on c.slocid=a.sloctoid
                        join product d on d.productid=a.productid
                        join productplant e on e.productid=a.productid and e.slocid=a.sloctoid and e.uom1=a.uomid
                        join materialgroup f on f.materialgroupid=e.materialgroupid
                        join unitofmeasure g on g.unitofmeasureid=a.uomid
                        join plant h on h.plantid=b.plantid
                        where b.productplanno is not null and h.plantid = " . $plantid . " and h.companyid = " . $companyid . " and b.recordstatus = 4
                        and c.sloccode like '%" . $sloc . "%' and d.productname like '%" . $product . "%' and f.materialgroupid = ".$row1['materialgroupid']."
                        and a.startdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'  and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' ";
        $command2    = $this->connection->createCommand($sql2);
        $dataReader2 = $command2->queryAll();
        $i=0;$totalqty=0;
        foreach ($dataReader2 as $row2)
        {
          $this->pdf->SetFont('Arial', '', 8);
          $i += 1;
          $this->pdf->row(array(
            $i,
            $row2['productplanno'],
            $row2['productname'],
            $row2['uomcode'],
            Yii::app()->format->formatCurrency($row2['qty']),
            date(Yii::app()->params['dateviewfromdb'], strtotime($row2['startdate'])),
            date(Yii::app()->params['dateviewfromdb'], strtotime($row2['enddate']))
          ));
          $totalqty += $row2['qty'];
        }
        $this->pdf->SetFont('Arial', 'BI', 8);
        $this->pdf->coldetailalign = array('C','C','R','C','R','C','C');
        $this->pdf->row(array(
          '','',
          'JUMLAH '.$row1['description'],
          '>>>>>',
          Yii::app()->format->formatCurrency($totalqty),
          '',''
        ));
        $this->pdf->checkPageBreak(20);
      }
      $this->pdf->sety($this->pdf->gety()+5);
    }
    $this->pdf->Output();
  }
	public function LaporanSPPStatusBelumMax($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
		parent::actionDownload();
		$sql = "SELECT a.productplanid,e.companyid, a.productplanno, a.statusname, productplandate, a.description, a.recordstatus, c.productname, d.sloccode, f.companycode
						FROM productplan a
						left JOIN productplanfg b ON b.productplanid = a.productplanid
						left JOIN product c ON c.productid = b.productid
						left JOIN sloc d ON d.slocid = b.sloctoid
						left JOIN plant e ON e.plantid = a.plantid
						left JOIN company f ON f.companyid = e.companyid
						WHERE a.recordstatus BETWEEN ('1') AND ('2')
						AND e.companyid = ".$companyid."
						AND e.plantid = ".$plantid."
						AND d.sloccode LIKE '%".$sloc."%'
						AND c.productname LIKE '%".$product."%'
						GROUP BY a.productplanid 
						ORDER BY a.productplanid asc";
		
		$command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
		foreach ($dataReader as $row) 
		{
				$this->pdf->companyid = $companyid;
		}
		
		$this->pdf->title    = 'Laporan OK Status Belum Max';
		$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->text(10, $this->pdf->gety() + 10, 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
		$this->pdf->AddPage('L');
		$this->pdf->sety($this->pdf->gety() + 5);
		$this->pdf->SetFont('Arial','',9);
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
			15,
			35,
			25,
			25,
			70,
			30,
			40,
			30
		));
		$this->pdf->colheader = array(
			'ID',
			'Perusahaan',
			'Tanggal OK',
			'NO OK',
			'Product',
			'Gudang',
			'Keterangan',
			'Status'
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
			'L'
		);
		foreach($dataReader as $row){
			$this->pdf->row(array(
				$row['productplanid'],
				$row['companycode'],
				date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])),
				$row['productplanno'],
				$row['productname'],
				$row['sloccode'],
				$row['description'],
				$row['statusname']
			));
			$i++;
		}
		$this->pdf->Output();
	}
	public function LaporanMaterialSPP($companyid,$plantid,$sloc,$product,$startdate,$enddate) {
			parent::actionDownload();
			$id = '';
			 $sql = "SELECT DISTINCT CONCAT(b.productplanfgid,',') as id, SUM(d.qty) as qtyop, SUM(b.qty) as qtyok
							FROM productplan a 
							JOIN productplanfg b ON a.productplanid = b.productplanid
							JOIN productoutput c ON c.productplanid = a.productplanid
							JOIN productoutputfg d ON d.productoutputid = c.productoutputid AND d.productplanfgid = b.productplanfgid
							WHERE a.productplandate BETWEEN ('".date(Yii::app()->params['datetodb'], strtotime($startdate))."') AND ('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
							AND a.recordstatus >= 3 AND c.recordstatus = 3
							GROUP BY b.productplanfgid
							HAVING qtyok > qtyop";
			
			$command=Yii::app()->db->createCommand($sql);
			$dataReader=$command->queryAll();
			foreach ($dataReader as $row) 
	{
					$this->pdf->companyid = $companyid;
			}
			foreach($dataReader as $row){
					$id .= $row['id'];
			}
			$id .= '-1';
			$this->pdf->title    = 'Laporan Material Check OK';
			$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->text(10, $this->pdf->gety() + 10, 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
			$this->pdf->AddPage('L','A4');
			$this->pdf->sety($this->pdf->gety() + 5);
			$this->pdf->SetFont('Arial','',10);
			$y = $this->pdf->getY();   
			
			$this->pdf->colalign = array(
		'C',
		'L',
		'C',
		'C',
		'C',
		'C',
		'C'
	);
	$this->pdf->setwidths(array(
		15,
		80,
		25,
		25,
		25,
		27,
		30
	));
			
	$this->pdf->colheader = array(
		'NO',
		'Product Name',
		'Satuan',
		'Jumlah (OK)',
		'Qty Needed',
		'Stock',
		'Plus/Minus'
	);

	$this->pdf->RowHeader();        
	$i=1;
	$this->pdf->coldetailalign = array(
		'C',
		'L',
		'C',
		'C',
		'R',
		'R',
		'R'
	);
			$sql1 = "SELECT b.productid, b.productname, SUM(qty-qtyres) as qtyneed, c.uomcode,GROUP_CONCAT(DISTINCT a.productplanid) as count
							FROM productplandetail a
							JOIN product b ON a.productid = b.productid
							JOIN unitofmeasure c ON c.unitofmeasureid = a.uomid
							WHERE productplanfgid IN (".$id.") AND b.isstock = 1
							GROUP BY productid 
							HAVING qtyneed > 0
							ORDER BY productname ";
			
			$cmd1 = Yii::app()->db->createCommand($sql1)->queryAll();
			foreach($cmd1 as $row1){
					$explode = explode(',',$row1['count'],-1);
					$count = count($explode);
					$sqlstock = "SELECT SUM(qty+qtyinprogress)
											FROM productstock
											WHERE productid =".$row1['productid']."";
					$stock = Yii::app()->db->createCommand($sqlstock)->queryScalar();
			$this->pdf->row(array(
					$i,
					$row1['productname'].$row1['productid'],
					$row1['uomcode'],
					($count+1),
					number_format($row1['qtyneed'],4),
					number_format($stock,4),
					number_format($stock - $row1['qtyneed'],4)));
			$i++;
	}
			
			$this->pdf->Output();
	}
	public function RincianTransferGudangKeluarPerDokumen($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
    parent::actionDownload();
    $sql        = "select distinct b.transstockid,b.transstockno,					
					(select concat(z.sloccode,' - ',z.description) from sloc z where z.slocid = b.slocfromid) as fromsloc,
					(select concat(zz.sloccode,' - ',zz.description) from sloc zz where zz.slocid = b.sloctoid) as tosloc,
					a.formrequestdate,a.formrequestno
                    from formrequest a
                    join transstock b on b.formrequestid=a.formrequestid
                    join sloc c on c.slocid = b.slocfromid
                    join plant d on d.plantid = c.plantid
                    join transstockdet e on e.transstockid = b.transstockid
					where b.transstockno is not null 
					and b.transstocktypeid = 1 
					and d.plantid = " . $plantid . " 
					and b.recordstatus > (3-1) 
					and c.sloccode like '%" . $sloc . "%' 
					and e.productid in (select x.productid 
					from productplant x join product xx on xx.productid = x.productid 
					where xx.productname like '%" . $product . "%')";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rincian Transfer Hasil Produksi Per Dokumen (Qty)';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 2);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'No. TS ');
      $this->pdf->text(25, $this->pdf->gety() + 5, ': ' . $row['transstockno']);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'No. FPB ');
      $this->pdf->text(25, $this->pdf->gety() + 10, ': ' . $row['formrequestno']);
      $this->pdf->text(90, $this->pdf->gety() + 5, 'Gudang Asal ');
      $this->pdf->text(120, $this->pdf->gety() + 5, ': ' . $row['fromsloc']);
      $this->pdf->text(90, $this->pdf->gety() + 10, 'Gudang Tujuan ');
      $this->pdf->text(120, $this->pdf->gety() + 10, ': ' . $row['tosloc']);
      $sql1        = "select c.productname, a.qty,d.uomcode,a.itemnote,e.headernote
                        from transstockdet a 
                        join product c on c.productid = a.productid
                        join unitofmeasure d on d.unitofmeasureid = a.uomid
                        join transstock e on e.transstockid=a.transstockid
                        where c.productname like '%" . $product . "%' and a.transstockid = " . $row['transstockid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $i           = 0;
      $totalqty    = 0;
      $this->pdf->sety($this->pdf->gety() + 13);
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        75,
        20,
        20,
        70
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Satuan',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'L'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty']),
          $row1['uomcode'],
          $row1['itemnote']
        ));
        $totalqty += $row1['qty'];
      }
      $this->pdf->row(array(
        '',
        '',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        '',
        ''
      ));
      $this->pdf->row(array(
        '',
        'Keterangan : ' . $row1['headernote'],
        '',
        '',
        ''
      ));
      $this->pdf->checkPageBreak(20);
      $this->pdf->sety($this->pdf->gety() + 10);
    }
    $this->pdf->Output();
  }
	public function RekapTransferHasilProduksiPerBarang($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
    parent::actionDownload();
    $sql        = "select distinct a.sloctoid,a.slocfromid,a.transstockno,
					(select sloccode from sloc d where d.slocid = a.slocfromid) as fromsloccode,
					(select description from sloc d where d.slocid = a.slocfromid) as fromslocdesc,
					(select sloccode from sloc d where d.slocid = a.sloctoid) as tosloccode,	
					(select description from sloc d where d.slocid = a.sloctoid) as toslocdesc
					from transstock a
					join transstockdet b on b.transstockid = a.transstockid
					join product c on c.productid = b.productid
					join sloc e on e.slocid = a.slocfromid
					where a.recordstatus > (3-1) and a.transstocktypeid = 1 and e.sloccode like '%" . $sloc . "%' and c.productname like '%" . $product . "%'
					and a.transstockdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
					and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Transfer Hasil Produksi Per Barang (Qty)';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Asal');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['fromsloccode'] . ' - ' . $row['fromslocdesc']);
      $this->pdf->text(10, $this->pdf->gety() + 15, 'Tujuan');
      $this->pdf->text(30, $this->pdf->gety() + 15, ': ' . $row['tosloccode'] . ' - ' . $row['toslocdesc']);
			$this->pdf->text(120, $this->pdf->gety() + 15, 'No Doc');
      $this->pdf->text(135, $this->pdf->gety() + 15, ': ' . $row['transstockno']);
      $sql1        = "select distinct a.productid,b.productname,d.uomcode,sum(a.qty) as qty
						from transstockdet a
						join product b on b.productid = a.productid
						join transstock c on c.transstockid = a.transstockid
						join unitofmeasure d on d.unitofmeasureid = a.uomid
						join sloc e on e.slocid = c.slocfromid
						where c.recordstatus > (3-1) and c.slocfromid = " . $row['slocfromid'] . " and e.sloccode like '%" . $sloc . "%' 
						and c.sloctoid = " . $row['sloctoid'] . " and b.productname like '%" . $product . "%' 
						and c.transstockdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
						and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' group by productid,productname";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $totalqty    = 0;
      $i           = 0;
      $this->pdf->sety($this->pdf->gety() + 20);
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        120,
        30,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Satuan',
        'Qty'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          $row1['uomcode'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty'])
        ));
        $totalqty += $row1['qty'];
      }
      $this->pdf->row(array(
        '',
        'TOTAL',
        '',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty)
      ));
      $this->pdf->checkPageBreak(20);
      $this->pdf->sety($this->pdf->gety() + 8);
    }
    $this->pdf->Output();
  }
	public function RincianFPBOKPerDokumen($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
    parent::actionDownload();
    $sql        = "select a.formrequestid, a.formrequestno, a.formrequestdate, b.productplanno, 
					c.sloccode as slocfrom, e.requestedbycode, f.sono, g.fullname as customer,d.companyid, a.description
					from formrequest a
					left join productplan b on b.productplanid = a.productplanid
					join sloc c on c.slocid = a.slocfromid
					join plant d on d.plantid = a.plantid
					left join requestedby e on e.requestedbyid = a.requestedbyid
					left join soheader f on f.soheaderid = b.soheaderid
					left join addressbook g on g.addressbookid = b.addressbookid
					where a.formrequestno is not null and a.formreqtype = 1 and d.plantid = " . $plantid . " and a.recordstatus = 3 and 
					c.sloccode like '%" . $sloc . "%' and a.formrequestdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
					and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
					and a.formrequestid in 
					(select x.formrequestid 
					from formrequestraw x 
					join product xx on xx.productid = x.productid 
					where xx.productname like '%" . $product . "%'
					union
					select x.formrequestid 
					from formrequestjasa x 
					join product xx on xx.productid = x.productid 
					where xx.productname like '%" . $product . "%'
					)";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rincian FPB OK Per Dokumen (Qty)';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('L','legal');
    $this->pdf->sety($this->pdf->gety() + 2);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'No. Doc ');
      $this->pdf->text(25, $this->pdf->gety() + 5, ': ' . $row['formrequestno']);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'No. OK ');
      $this->pdf->text(25, $this->pdf->gety() + 10, ': ' . $row['productplanno']);
			$this->pdf->text(10, $this->pdf->gety() + 15, 'No. OS ');
      $this->pdf->text(25, $this->pdf->gety() + 15, ': ' . $row['sono']);
      $this->pdf->text(90, $this->pdf->gety() + 5, 'Tgl Doc ');
      $this->pdf->text(120, $this->pdf->gety() + 5, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['formrequestdate'])));
      $this->pdf->text(90, $this->pdf->gety() + 10, 'Customer ');
      $this->pdf->text(120, $this->pdf->gety() + 10, ': ' . $row['customer']);
      $sql1        = "select a.formrequestrawid, a.productname, a.qty, a.qty2, a.qty3, a.qty4, a.reqdate,
											b.sloccode, c.uomcode, d.uomcode as uom2code, e.uomcode as uom3code, f.uomcode as uom4code, g.kodemesin,a.description
											from formrequestraw a
											left join sloc b on b.slocid = a.sloctoid
											left join unitofmeasure c on c.unitofmeasureid = a.uomid
											left join unitofmeasure d on d.unitofmeasureid = a.uom2id
											left join unitofmeasure e on e.unitofmeasureid = a.uom3id
											left join unitofmeasure f on f.unitofmeasureid = a.uom4id
											left join mesin g on g.mesinid = a.mesinid
                        where a.productname like '%" . $product . "%' and a.formrequestid = " . $row['formrequestid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $i           = 0;
      $totalqty    = 0;
      $this->pdf->sety($this->pdf->gety() + 20);
      $this->pdf->setFont('Arial', 'B', 8);
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
      );
      $this->pdf->setwidths(array(
        10,
        75,
        30,
        30,
        30,
        30,
        25,
        25,
        25,
        40
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Qty 2',
        'Qty 3',
        'Qty 4',
        'GD Tujuan',
				'Tgl Req',
				'Mesin',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'R',
        'C',
        'C',
        'C',
        'L',
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty']).' '.$row1['uomcode'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty2']).' '.$row1['uom2code'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty3']).' '.$row1['uom3code'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty4']).' '.$row1['uom4code'],
          $row1['sloccode'],
					$row1['reqdate'],
					$row1['kodemesin'],
          $row1['description']
        ));
        $totalqty += $row1['qty'];
      }
      $this->pdf->row(array(
        '',
        '',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        '',
        ''
      ));
      $this->pdf->row(array(
        '',
        'Keterangan : ' . $row1['description'],
        '',
        '',
        ''
      ));
      $this->pdf->checkPageBreak(20);
      $this->pdf->sety($this->pdf->gety() + 10);
    }
    $this->pdf->Output();
  }
	public function JadwalProduksiMesin($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
    parent::actionDownload();
    $sql        = "
			SELECT a.mesinid,a.kodemesin,a.namamesin
			FROM mesin a
			WHERE a.mesinid IN 
			(
			SELECT distinct za.mesinid
			FROM productplanfg za
			LEFT JOIN productplan zb ON zb.productplanid = za.productplanid 
			LEFT JOIN sloc zc ON zc.slocid = za.sloctoid
			LEFT JOIN plant zd ON zd.plantid = zc.plantid 
			LEFT JOIN product ze ON ze.productid = za.productid
			WHERE zb.recordstatus = 4 
			AND za.startdate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
			AND zc.sloccode LIKE '%".$sloc."%'
			AND zb.plantid = ".$plantid."
			AND zd.companyid = ".$companyid."
			AND ze.productname LIKE '%".$product."%'
			)
		";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) 
    {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Jadwal Produksi (Mesin)';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('L','A4');
    foreach ($dataReader as $row) 
    {
      $this->pdf->SetFont('Arial', 'B', 10);
      $this->pdf->text(10, $this->pdf->gety()+5, 'Mesin');
      $this->pdf->text(25, $this->pdf->gety()+5, ': ' . $row['namamesin']);
      $sql1        = "select b.productplanno,d.productname,g.uomcode,a.qty,a.startdate,a.enddate,a.qty2,a.qty3,a.qty4,i.uomcode as uom2code,j.uomcode as uom3code,k.uomcode as uom4code
				from productplanfg a
				join productplan b on b.productplanid=a.productplanid
				join sloc c on c.slocid=a.sloctoid
				join product d on d.productid=a.productid
				join productplant e on e.productid=a.productid and e.slocid=a.sloctoid and e.uom1=a.uomid
				join materialgroup f on f.materialgroupid=e.materialgroupid
				join unitofmeasure g on g.unitofmeasureid=a.uomid
				join unitofmeasure i on i.unitofmeasureid=a.uom2id
				left join unitofmeasure j on j.unitofmeasureid=a.uom3id
				left join unitofmeasure k on k.unitofmeasureid=a.uom4id
				join plant h on h.plantid=b.plantid
				where b.productplanno is not null 
				and h.plantid = " . $plantid . " 
				and h.companyid = " . $companyid . " 
				and b.recordstatus = 4
				and c.sloccode like '%" . $sloc . "%' 
				and d.productname like '%" . $product . "%' 
				and a.mesinid = ".$row['mesinid']."
				and a.startdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'  and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
				order by a.productplanfgid";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
			$this->pdf->sety($this->pdf->gety()+10);
			$this->pdf->setFont('Arial', 'B', 8);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(8,30,90,25,25,25,25,30,20,20,20));
			$this->pdf->colheader = array('No','OK','Nama Barang','Qty','Qty2','Qty3','Qty4','Tgl Mulai','Tgl Selesai');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('L','L','L','R','R','R','R','C','C');
			$i=0;$totalqty = 0;
      foreach ($dataReader1 as $row1)
      {        
        $this->pdf->SetFont('Arial', '', 8);
          $i += 1;
          $this->pdf->row(array(
            $i,
            $row1['productplanno'],
            $row1['productname'],
            Yii::app()->format->formatCurrency($row1['qty']).' '.$row1['uomcode'],
            Yii::app()->format->formatCurrency($row1['qty2']).' '.$row1['uom2code'],
            Yii::app()->format->formatCurrency($row1['qty3']).' '.$row1['uom3code'],
            Yii::app()->format->formatCurrency($row1['qty4']).' '.$row1['uom4code'],
            date(Yii::app()->params['dateviewfromdb'], strtotime($row1['startdate'])),
            date(Yii::app()->params['dateviewfromdb'], strtotime($row1['enddate']))
          ));
          $totalqty += $row1['qty'];
			}
			$this->pdf->AddPage('L','A4');
    }
    $this->pdf->Output();
  }
	public function HasilProduksiBelumMax($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
		parent::actionDownload();
		$sql = "SELECT a.productoutputid,e.companyid, a.productoutputno, a.statusname, productoutputdate, a.headernote, a.recordstatus, c.productname, d.sloccode, f.companycode,e.plantcode 
						FROM productoutput a
						left JOIN productoutputfg b ON b.productoutputid = a.productoutputid
						left JOIN product c ON c.productid = b.productid
						left JOIN sloc d ON d.slocid = b.slocid
						left JOIN plant e ON e.plantid = a.plantid
						left JOIN company f ON f.companyid = e.companyid
						WHERE a.recordstatus BETWEEN ('1') AND ('2')
						AND e.companyid = ".$companyid."
						AND e.plantid = ".$plantid."
						AND d.sloccode LIKE '%".$sloc."%'
						AND c.productname LIKE '%".$product."%'
						GROUP BY a.productoutputid 
						ORDER BY a.productoutputdate asc";
		
		$command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
		foreach ($dataReader as $row) 
		{
				$this->pdf->companyid = $companyid;
		}
		
		$this->pdf->title    = 'Laporan Hasil Produksi Status Belum Max';
		$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->text(10, $this->pdf->gety() + 10, 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
		$this->pdf->AddPage('L');
		$this->pdf->sety($this->pdf->gety() + 5);
		$this->pdf->SetFont('Arial','',9);
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
			'C'
		);
		$this->pdf->setwidths(array(
			15,
			15,
			25,
			25,
			35,
			70,
			30,
			30,
			30
		));
		$this->pdf->colheader = array(
			'No',
			'ID',
			'Kantor Cabang',
			'Tanggal HP',
			'NO HP',
			'Product',
			'Gudang',
			'Keterangan',
			'Status'
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
			'L',
			'L'
		);
		foreach($dataReader as $row){
			$this->pdf->row(array(
				$i,
				$row['productoutputid'],
				$row['plantcode'],
				date(Yii::app()->params['dateviewfromdb'], strtotime($row['productoutputdate'])),
				$row['productoutputno'],
				$row['productname'],
				$row['sloccode'],
				$row['headernote'],
				$row['statusname']
			));
			$i++;
		}
		$this->pdf->Output();
	}
	public function LaporanWaste($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
		parent::actionDownload();
		$sql = "SELECT k.plantcode,a.productplanid,a.productplanno,d.sono,d.pocustno,e.fullname,g.productoutputno,f.qty,c.productname,
			i.productname AS productwaste,h.qty as qtywaste,(h.qty / f.qty2 * 100) AS persenwaste
			FROM productplan a
			LEFT JOIN productplanfg b ON b.productplanid = a.productplanid
			LEFT JOIN product c ON c.productid = b.productid
			LEFT JOIN soheader d ON d.soheaderid = a.soheaderid
			LEFT JOIN addressbook e ON e.addressbookid = a.addressbookid
			LEFT JOIN productoutputfg f ON f.productplanfgid = b.productplanfgid AND f.productid = b.productid
			LEFT JOIN productoutput g ON g.productoutputid = f.productoutputid 
			LEFT JOIN productoutputwaste h ON h.productoutputfgid = f.productoutputfgid 
			LEFT JOIN product i ON i.productid = h.productid
			left join sloc j on j.slocid = b.sloctoid 
			left join plant k on k.plantid = j.plantid 
			WHERE a.productplandate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
			and a.plantid = ".$plantid." 
			and coalesce(j.sloccode,'') like '%".$sloc."%' 
			and coalesce(h.productname,'') like '%".$product."%'
			and coalesce(e.fullname,'') like '%".$customer."%'
			and a.recordstatus >= 3
			and g.recordstatus = 3 
			ORDER BY a.productplandate asc,a.productplanno asc,f.productoutputfgid asc";
		
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->companyid = $companyid;
		
		$this->pdf->title    = 'Laporan Waste';
		$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->text(10, $this->pdf->gety() + 10, 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
		$this->pdf->AddPage('L','A3');
		$this->pdf->sety($this->pdf->gety() + 5);
		$this->pdf->SetFont('Arial','',8);
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
			'C',
			'C',
			'C',
			'C'
		);
		$this->pdf->setwidths(array(
			10,
			12,
			30,
			30,
			30,
			70,
			70,
			30,
			70,
			30,
			20,
		));
		$this->pdf->colheader = array(
			'ID',
			'Plant',
			'No OK',
			'NO OS',
			'NO PO Customer',
			'Customer',
			'Artikel Hasil',
			'Qty Hasil Produksi',
			'Artikel Waste',
			'Qty Waste',
			'% Waste'
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
			'L',
			'R',
			'R',
		);
		foreach($dataReader as $row){
			$this->pdf->row(array(
				$row['productplanid'],
				$row['plantcode'],
				$row['productplanno'],
				$row['sono'],
				$row['pocustno'],
				$row['fullname'],
				$row['productname'],
				Yii::app()->format->formatCurrency($row['qty']),
				$row['productwaste'],
				Yii::app()->format->formatCurrency($row['qtywaste']),
				Yii::app()->format->formatCurrency($row['persenwaste']),
			));
			$i++;
		}
		$this->pdf->Output();
	}
	public function RekonsiliasiOKOSHP($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
		parent::actionDownload();
		$this->pdf->isrepeat = 1;
		$sql = "SELECT h.plantcode,a.productplanid,c.pocustno,a.productplanno,c.sono,f.fullname,e.productname,d.qty AS qtyos,b.qty AS qtyok,b.qtyres AS qtyop,
			b.qty2,b.qty3,b.qty4,a.statusname,i.uomcode,j.uomcode as uom2code,k.uomcode as uom3code,l.uomcode as uom4code
			FROM productplan a
			LEFT JOIN productplanfg b ON b.productplanid = a.productplanid
			LEFT JOIN soheader c ON c.soheaderid = a.soheaderid 
			LEFT JOIN sodetail d ON d.soheaderid = c.soheaderid AND d.productid = b.productid AND d.sodetailid = b.sodetailid
			LEFT JOIN product e ON e.productid = b.productid 
			LEFT JOIN addressbook f ON f.addressbookid =a.addressbookid
			left join sloc g on g.slocid = b.sloctoid 
			left join plant h on h.plantid = g.plantid 
			left join unitofmeasure i on i.unitofmeasureid = b.uomid 
			left join unitofmeasure j on j.unitofmeasureid = b.uom2id
			left join unitofmeasure k on k.unitofmeasureid = b.uom3id
			left join unitofmeasure l on l.unitofmeasureid = b.uom4id
			WHERE a.productplandate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
			and a.plantid = ".$plantid." 
			and coalesce(g.sloccode,'') like '%".$sloc."%' 
			and coalesce(e.productname,'') like '%".$product."%'
			and coalesce(f.fullname,'') like '%".$customer."%'
			ORDER BY a.productplanid ASC,b.productplanfgid desc";
		
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->companyid = $companyid;
		
		$this->pdf->title    = 'Rekonsiliasi OK, OS, dan Hasil Produksi';
		$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->text(10, $this->pdf->gety() + 10, 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
		$this->pdf->AddPage('L','A2');
		$this->pdf->sety($this->pdf->gety() + 5);
		$this->pdf->SetFont('Arial','',10);
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
			10,
			12,
			35,
			35,
			35,
			70,
			80,
			25,
			25,
			25,
			18,
			25,
			18,
			25,
			18,
			25,
			18,
			20,
			50,
		));
		$this->pdf->colheader = array(
			'No',
			'Plant',
			'No OK',
			'NO OS',
			'NO PO Customer',
			'Customer',
			'Artikel Hasil',
			'Qty OS',
			'Qty OK',
			'Qty Produksi',
			'Satuan',
			'Qty 2',
			'Satuan 2',
			'Qty 3',
			'Satuan 3',
			'Qty 4',
			'Satuan 4',
			'No Proses',
			'Status Dokumen'
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
			'L',
			'R',
			'L',
			'R',
			'R',
			'R',
			'R',
			'R',
			'R',
		);
		$i=1;$ppid = 0;$proses=0;
		foreach($dataReader as $row){
			if ($ppid == 0) {
				$ppid = $row['productplanid'];
				$proses = 1;
			} else if ($ppid != $row['productplanid']) {
				$ppid = $row['productplanid'];
				$proses = 1;
			}	else {
				$proses++;
			}
			$this->pdf->row(array(
				$i,
				$row['plantcode'],
				$row['productplanno'],
				$row['sono'],
				$row['pocustno'],
				$row['fullname'],
				$row['productname'],
				Yii::app()->format->formatCurrency($row['qtyos']),
				Yii::app()->format->formatCurrency($row['qtyok']),
				Yii::app()->format->formatCurrency($row['qtyop']),
				$row['uomcode'],
				Yii::app()->format->formatCurrency($row['qty2']),
				$row['uom2code'],
				Yii::app()->format->formatCurrency($row['qty3']),
				$row['uom3code'],
				Yii::app()->format->formatCurrency($row['qty4']),
				$row['uom4code'],
				$proses,
				$row['statusname'],
			));
			$i++;
		}
		$this->pdf->Output();
	}
	public function LaporanAlokasiBarang($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
		parent::actionDownload();
		$this->pdf->isrepeat = 1;
		$sql = "SELECT *,(qty + qtysisapo - qtyokpakai) AS qtyfree
			FROM
			(
			SELECT c.plantcode,a.productname,a.sloccode,sum(a.qty) as qty,
			(
			SELECT ifnull(sum(za.qty),0)
			FROM productplandetail za 
			left join productplan zb on zb.productplanid = za.productplanid 
			WHERE za.qtyres = 0 AND za.productid = a.productid AND za.slocfromid = a.slocid
			and zb.recordstatus = 4 and zb.plantid = c.plantid 
			) AS qtyokpakai,
			(
			SELECT IFNULL(SUM(zza.qty-zza.grqty),0)
			FROM podetail zza 
			left join poheader zzb on zzb.poheaderid = zza.poheaderid 
			WHERE zza.productid = a.productid AND zza.slocid = a.slocid
			and zzb.recordstatus = 4 and zzb.plantid = c.plantid 
			) AS qtysisapo,
			e.uomcode
			FROM productstock a 
			LEFT JOIN product b ON b.productid = a.productid 
			left join sloc d on d.slocid = a.slocid 
			left join plant c on c.plantid = d.plantid 
			left join unitofmeasure e on e.unitofmeasureid = a.uomid 
			WHERE b.materialtypeid = 1
			and c.plantid = ".$plantid." 
			and coalesce(d.sloccode,'') like '%".$sloc."%' 
			and coalesce(b.productname,'') like '%".$product."%'
			group by c.plantcode,a.productname,a.sloccode,e.uomcode
			) zzz
			";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->companyid = $companyid;
		$this->pdf->title    = 'Laporan Alokasi Barang';
		$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->text(10, $this->pdf->gety() + 10, 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
		$this->pdf->AddPage('L','A4');
		$this->pdf->sety($this->pdf->gety() + 5);
		$this->pdf->SetFont('Arial','',8);
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
		);
		$this->pdf->setwidths(array(
			10,
			12,
			100,
			30,
			20,
			25,
			25,
			25,
			25,
		));
		$this->pdf->colheader = array(
			'No',
			'Plant',
			'Artikel',
			'Gudang',
			'Satuan',
			'Qty',
			'Qty OK Pakai',
			'Qty Sisa PO',
			'Qty Free',
		);
		$this->pdf->RowHeader();        
		$i=1;
		$this->pdf->coldetailalign = array(
			'L',
			'L',
			'L',
			'L',
			'L',
			'R',
			'R',
			'R',
			'R',
		);
		$i=1;
		foreach($dataReader as $row){
			$this->pdf->row(array(
				$i,
				$row['plantcode'],
				$row['productname'],
				$row['sloccode'],
				$row['uomcode'],
				Yii::app()->format->formatCurrency($row['qty']),
				Yii::app()->format->formatCurrency($row['qtyokpakai']),
				Yii::app()->format->formatCurrency($row['qtysisapo']),
				Yii::app()->format->formatCurrency($row['qtyfree']),
			));
			$i++;
		}
		$this->pdf->Output();
	}

	public function actionDownXLS() {
    parent::actionDownload();
		if ($_GET['companyid'] == '') {
			echo getcatalog('emptycompany');
		} else 
		if ($_GET['lro'] == '') {
			GetMessage(true,'choosereport');
		} else 
		if ($_GET['plantid'] == '') {
			GetMessage(true,'emptyplant');
		} else 
		if ($_GET['startdate'] == '') {
			GetMessage(true,'emptystartdate');
		} else 
		if ($_GET['enddate'] == '') {
			GetMessage(true,'emptyenddate');
		} else {			      
			switch ($_GET['lro']) {
				case 22:
					$this->RekonsiliasiOKOSHPXls($_GET['companyid'],$_GET['plantid'],$_GET['sloc'],$_GET['customer'],$_GET['product'],$_GET['startdate'],$_GET['enddate']);					
					break;
				case 23:
					$this->LaporanWasteXls($_GET['companyid'],$_GET['plantid'],$_GET['sloc'],$_GET['customer'],$_GET['product'],$_GET['startdate'],$_GET['enddate']);					
					break;
				case 24:
					$this->LaporanAlokasiBarangXls($_GET['companyid'],$_GET['plantid'],$_GET['sloc'],$_GET['customer'],$_GET['product'],$_GET['startdate'],$_GET['enddate']);					
					break;
					case 25:
					$this->RekapokXls($_GET['companyid'],$_GET['plantid'],$_GET['sloc'],$_GET['customer'],$_GET['product'],$_GET['startdate'],$_GET['enddate']);					
					break;
				default:
					echo GetCatalog('reportdoesnotexist');
			}
		}
	}	
	
	public function LaporanWasteXls($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
		$this->menuname='laporanwaste';
    parent::actionDownxls();
		$sql = "SELECT k.plantcode,a.productplanid,a.productplanno,d.sono,d.pocustno,e.fullname,g.productoutputno,f.qty,c.productname,
			i.productname AS productwaste,h.qty as qtywaste,(h.qty / f.qty * 100) AS persenwaste
			FROM productplan a
			LEFT JOIN productplanfg b ON b.productplanid = a.productplanid
			LEFT JOIN product c ON c.productid = b.productid
			LEFT JOIN soheader d ON d.soheaderid = a.soheaderid
			LEFT JOIN addressbook e ON e.addressbookid = a.addressbookid
			LEFT JOIN productoutputfg f ON f.productplanfgid = b.productplanfgid AND f.productid = b.productid
			LEFT JOIN productoutput g ON g.productoutputid = f.productoutputid 
			LEFT JOIN productoutputwaste h ON h.productoutputfgid = f.productoutputfgid 
			LEFT JOIN product i ON i.productid = h.productid
			left join sloc j on j.slocid = b.sloctoid 
			left join plant k on k.plantid = j.plantid 
			WHERE a.productplandate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
			and a.plantid = ".$plantid." 
			and coalesce(j.sloccode,'') like '%".$sloc."%' 
			and coalesce(h.productname,'') like '%".$product."%'
			and coalesce(e.fullname,'') like '%".$customer."%'
			and a.recordstatus >= 3
			and g.recordstatus = 3 
			ORDER BY a.productplandate asc,a.productplanno asc,f.productoutputfgid asc";
		
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();	
		$i          = 2;
    foreach ($dataReader as $row) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $i-1)
				->setCellValueByColumnAndRow(1, $i+1, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $i+1, $row['productplanno'])
				->setCellValueByColumnAndRow(3, $i+1, $row['sono'])
				->setCellValueByColumnAndRow(4, $i+1, $row['pocustno'])
				->setCellValueByColumnAndRow(5, $i+1, $row['fullname'])
				->setCellValueByColumnAndRow(6, $i+1, $row['productname'])
				->setCellValueByColumnAndRow(7, $i+1, $row['qty'])
				->setCellValueByColumnAndRow(8, $i+1, $row['productwaste'])
				->setCellValueByColumnAndRow(9, $i+1, $row['qtywaste'])
				->setCellValueByColumnAndRow(10, $i+1, $row['persenwaste'])
			;
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
	
	public function RekapokXls($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
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
			and coalesce(e.fullname,'') like '%".$customer."%'
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
	
	public function RekonsiliasiOKOSHPXls($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
		$this->menuname='rekonokoshp';
    parent::actionDownxls();
		$sql = "SELECT h.plantcode,a.productplanid,c.pocustno,a.productplanno,c.sono,f.fullname,e.productname,d.qty AS qtyos,b.qty AS qtyok,b.qtyres AS qtyop,
			b.qty2,b.qty3,b.qty4,a.statusname,i.uomcode,j.uomcode as uom2code,k.uomcode as uom3code,l.uomcode as uom4code
			FROM productplan a
			LEFT JOIN productplanfg b ON b.productplanid = a.productplanid
			LEFT JOIN soheader c ON c.soheaderid = a.soheaderid 
			LEFT JOIN sodetail d ON d.soheaderid = c.soheaderid AND d.productid = b.productid AND d.sodetailid = b.sodetailid
			LEFT JOIN product e ON e.productid = b.productid 
			LEFT JOIN addressbook f ON f.addressbookid =a.addressbookid
			left join sloc g on g.slocid = b.sloctoid 
			left join plant h on h.plantid = g.plantid 
			left join unitofmeasure i on i.unitofmeasureid = b.uomid 
			left join unitofmeasure j on j.unitofmeasureid = b.uom2id
			left join unitofmeasure k on k.unitofmeasureid = b.uom3id
			left join unitofmeasure l on l.unitofmeasureid = b.uom4id
			WHERE a.productplandate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
			and a.plantid = ".$plantid." 
			and coalesce(g.sloccode,'') like '%".$sloc."%' 
			and coalesce(e.productname,'') like '%".$product."%'
			and coalesce(f.fullname,'') like '%".$customer."%'
			ORDER BY a.productplanid ASC,b.productplanfgid desc";
		
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();	
		$i=2;$ppid = 0;$proses=0;
		foreach($dataReader as $row){
			if ($ppid == 0) {
				$ppid = $row['productplanid'];
				$proses = 1;
			} else if ($ppid != $row['productplanid']) {
				$ppid = $row['productplanid'];
				$proses = 1;
			}	else {
				$proses++;
			}
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $i-1)
				->setCellValueByColumnAndRow(1, $i+1, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $i+1, $row['productplanno'])
				->setCellValueByColumnAndRow(3, $i+1, $row['sono'])
				->setCellValueByColumnAndRow(4, $i+1, $row['pocustno'])
				->setCellValueByColumnAndRow(5, $i+1, $row['fullname'])
				->setCellValueByColumnAndRow(6, $i+1, $row['productname'])
				->setCellValueByColumnAndRow(7, $i+1, $row['qtyos'])
				->setCellValueByColumnAndRow(8, $i+1, $row['qtyok'])
				->setCellValueByColumnAndRow(9, $i+1, $row['qtyop'])
				->setCellValueByColumnAndRow(10, $i+1, $row['uomcode'])
				->setCellValueByColumnAndRow(11, $i+1, $row['qty2'])
				->setCellValueByColumnAndRow(12, $i+1, $row['uom2code'])
				->setCellValueByColumnAndRow(13, $i+1, $row['qty3'])
				->setCellValueByColumnAndRow(14, $i+1, $row['uom3code'])
				->setCellValueByColumnAndRow(15, $i+1, $row['qty4'])
				->setCellValueByColumnAndRow(16, $i+1, $row['uom4code'])
				->setCellValueByColumnAndRow(17, $i+1, $proses)
				->setCellValueByColumnAndRow(18, $i+1, $row['statusname'])
			;
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
	public function LaporanAlokasiBarangXls($companyid,$plantid,$sloc,$customer,$product,$startdate,$enddate) {
		$this->menuname='laporanalokasibarang';
    parent::actionDownxls();
		$sql = "SELECT *,(qty + qtysisapo - qtyokpakai) AS qtyfree
			FROM
			(
			SELECT c.plantcode,a.productname,a.sloccode,a.qty,
			(
			SELECT ifnull(sum(za.qty),0)
			FROM productplandetail za 
			left join productplan zb on zb.productplanid = za.productplanid 
			WHERE za.qtyres = 0 AND za.productid = a.productid AND za.slocfromid = a.slocid
			and zb.recordstatus = 4 and zb.plantid = c.plantid 
			) AS qtyokpakai,
			(
			SELECT IFNULL(SUM(zza.qty-zza.grqty),0)
			FROM podetail zza 
			left join poheader zzb on zzb.poheaderid = zza.poheaderid 
			WHERE zza.productid = a.productid AND zza.slocid = a.slocid
			and zzb.recordstatus = 4 and zzb.plantid = c.plantid 
			) AS qtysisapo,
			e.uomcode
			FROM productstock a 
			LEFT JOIN product b ON b.productid = a.productid 
			left join sloc d on d.slocid = a.slocid 
			left join plant c on c.plantid = d.plantid 
			left join unitofmeasure e on e.unitofmeasureid = a.uomid 
			WHERE b.materialtypeid = 1
			and c.plantid = ".$plantid." 
			and coalesce(d.sloccode,'') like '%".$sloc."%' 
			and coalesce(b.productname,'') like '%".$product."%'
			group by c.plantcode,a.productname,a.sloccode,e.uomcode
			) zzz
			";
		
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();	
		$i=2;
		foreach($dataReader as $row){
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $i-1)
				->setCellValueByColumnAndRow(1, $i+1, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $i+1, $row['productname'])
				->setCellValueByColumnAndRow(3, $i+1, $row['sloccode'])
				->setCellValueByColumnAndRow(4, $i+1, $row['uomcode'])
				->setCellValueByColumnAndRow(5, $i+1, $row['qty'])
				->setCellValueByColumnAndRow(6, $i+1, $row['qtyokpakai'])
				->setCellValueByColumnAndRow(7, $i+1, $row['qtysisapo'])
				->setCellValueByColumnAndRow(8, $i+1, $row['qtyfree'])
			;
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}