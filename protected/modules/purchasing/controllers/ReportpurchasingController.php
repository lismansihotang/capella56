<?php
class ReportpurchasingController extends Controller {
  public $menuname = 'reportpurchasing';
  public function actionIndex() {
    $this->renderPartial('index', array());
  }
  public function actionDownPDF() {
    parent::actionDownload();
		if ($_GET['companyid'] == '') {
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
				case 1 :
					$this->RincianPOPerDokumen($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 2 :
					$this->RekapPOPerDokumen($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 3 :
					$this->RekapPOPerSupplier($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 4 :
					$this->RekapPOPerBarang($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 5 :
					$this->RincianPembelianPerDokumen($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 6 :
					$this->RekapPembelianPerDokumen($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 7 :
					$this->RekapPembelianPerSupplier($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 8 :
					$this->RekapPembelianPerBarang($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 9 :
					$this->RincianReturPembelianPerDokumen($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 10 :
					$this->RekapReturPembelianPerDokumen($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 11 :
					$this->RekapReturPembelianPerSupplier($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 12 :
					$this->RekapReturPembelianPerBarang($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 13 :
					$this->RincianSelisihPembelianReturPerDokumen($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 14 :
					$this->RekapSelisihPembelianReturPerDokumen($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 15 :
					$this->RekapSelisihPembelianReturPerSupplier($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 16 :
					$this->RekapSelisihPembelianReturPerBarang($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 17 :
					$this->PendinganPOPerDokumen($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 18 :
					$this->RincianPendinganPOPerBarang($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 19 :
					$this->RekapPendinganPOPerBarang($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 20 :
					$this->LaporanPOStatusBelumMax($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 21 :
					$this->RekapPembelianPerBarangPerTanggal($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 22 :
					$this->LaporanPembelianPerSupplierPerBulanPerTahun($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 23 :
					$this->KomparasiPO($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;
				default:
					echo GetCatalog('reportdoesnotexist');
			}
    }
  }
  public function RincianPOPerDokumen($companyid, $plant,$supplier, $product,$sloc, $startdate,$enddate) {
    parent::actionDownload();
    $price = getUserObjectValues('currency');
    $sql = "select distinct a.poheaderid, a.pono, c.fullname, d.paydays, a.podate
						from poheader a
						join podetail b on b.poheaderid = a.poheaderid
						join addressbook c on c.addressbookid = a.addressbookid
						join paymentmethod d on d.paymentmethodid = a.paymentmethodid
						join product e on e.productid = b.productid
						join plant f on f.plantid = a.plantid
						left join sloc g on g.slocid = b.slocid
						where a.recordstatus>=4 
						and f.companyid = " . $companyid . " 
						and f.plantid = " . $plant . " 
						and a.pono is not null and g.sloccode like '%" . $sloc . "%'
						and e.productname like '%" . $product . "%' and c.fullname like '%" . $supplier . "%' 
						and a.podate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
						and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' order by pono";
    $command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rincian PO Per Dokumen';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->text(10, $this->pdf->gety() + 10, 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    $totalallqty=0;$totalallrp=0;
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'No Bukti');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['pono']);
      $this->pdf->text(10, $this->pdf->gety() + 15, 'Supplier');
      $this->pdf->text(30, $this->pdf->gety() + 15, ': ' . $row['fullname']);
      $this->pdf->text(150, $this->pdf->gety() + 10, 'Tgl Bukti');
      $this->pdf->text(180, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['podate'])));
      $this->pdf->text(150, $this->pdf->gety() + 15, 'T.O.P');
      $this->pdf->text(180, $this->pdf->gety() + 15, ': ' . $row['paydays'] . ' HARI');
      $sql1 = "
				select *,(netto-nominal) as ppn 
				from (
				select a.poheaderid,a.pono,g.productname,f.qty,f.price,h.uomcode,f.itemnote,k.prno,
							(f.qty*f.price) as nominal,getamountdetailbypo(a.poheaderid,f.podetailid) as netto
							from poheader a
							join addressbook d on d.addressbookid=a.addressbookid
							join paymentmethod e on e.paymentmethodid=a.paymentmethodid
							join podetail f on f.poheaderid = a.poheaderid
							join product g on g.productid = f.productid
							join unitofmeasure h on h.unitofmeasureid = f.uomid
							join plant i on i.plantid = a.plantid
							left join sloc j on j.slocid = f.slocid
							left join prheader k on k.prheaderid = f.prheaderid 
							where a.pono is not null and g.productname like '%" . $product . "%' and d.fullname like '%" . $supplier . "%'  
							and i.companyid = " . $companyid . " 
							and i.plantid = " . $plant . " and j.sloccode like '%" . $sloc . "%'
							and a.poheaderid = " . $row['poheaderid'] . "
							and a.podate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
							and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') zz";
      $command1=$this->connection->createCommand($sql1);$dataReader1=$command1->queryAll();
      $total=0;$i=0;$totalqty=0;
      $this->pdf->sety($this->pdf->gety() + 20);
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->colalign = array('C','C','C','C','C','C','C','C');
      $this->pdf->setwidths(array(10,45,19,18,12,25,30,38));
      $this->pdf->colheader = array('No','Nama Barang','No FPP','Qty','Satuan','Harga','Jumlah','Keterangan');
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array('L','L','L','R','C','R','R','R');
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          $row1['prno'],
          Yii::app()->format->formatnumber($row1['qty']),
          $row1['uomcode'],
          Yii::app()->format->formatnumber($row1['price']),
          Yii::app()->format->formatnumber(($row1['price'] * $row1['qty'])),
          $row1['itemnote']
        ));
        $totalqty += $row1['qty'];
        $total += ($row1['price'] * $row1['qty']);
      }
      $this->pdf->row(array(
        '',
        '',
        '',
        Yii::app()->format->formatnumber($totalqty),
        '',
        '',
        'NOMINAL',
        Yii::app()->format->formatnumber($total)
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        '',
        '',
        'PPN',
        Yii::app()->format->formatnumber($row1['ppn'])
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        '',
        '',
        'NETTO',
        Yii::app()->format->formatnumber($row1['netto'])
      ));			
			$totalallqty += $row1['qty'];
			$totalallrp += $row1['netto'];
    }
    $this->pdf->setFont('Arial', 'B', 9);
    $this->pdf->colalign = array('C','C','C','C','C','C');
    $this->pdf->setwidths(array(25,20,50,25,20,50));
    $this->pdf->row(array(
      '',
      'Total Qty ',
      Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalallqty),
      '',
      'Total Netto',
      Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalallrp)
    ));
    $this->pdf->Output();
  }
	public function RekapPOPerDokumen($companyid, $plant,$supplier, $product,$sloc, $startdate,$enddate) {
    parent::actionDownload();
    $sql        = "
			select *, (netto-nominal) as ppn 
			from (
			select a.poheaderid,a.pono,a.podate,d.fullname,(select sum(b.qty*b.price) 
			from podetail b where b.poheaderid=a.poheaderid) as nominal,getamountbypo(a.poheaderid) as netto
			from poheader a
			inner join addressbook d on d.addressbookid=a.addressbookid
			join podetail e on e.poheaderid = a.poheaderid
			join product f on f.productid = e.productid
			join plant g on g.plantid = a.plantid
			left join sloc j on j.slocid = e.slocid
			where a.recordstatus>=4 
			and a.pono is not null 
			and f.productname like '%" . $product . "%' 
			and d.fullname like '%" . $supplier . "%' 
			and g.companyid = " . $companyid . " 
			and g.plantid = " . $plant . " 
			and a.podate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
			and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z order by pono";
    $command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap PO Per Dokumen';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
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
      'C'
    );
    $this->pdf->setwidths(array(
      10,
      23,
      23,
      55,
      30,
      25,
      25,
      25
    ));
    $this->pdf->colheader = array(
      'No',
      'No Bukti',
      'Tanggal',
      'Supplier',
      'Nominal',
      'PPN',
      'Total'
    );
    $this->pdf->RowHeader();
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'C',
      'L',
      'R',
      'R',
      'R',
      'R'
    );
    $total                     = 0;
    $i                         = 0;
    $totalnetto                = 0;
    $totalppn                  = 0;
    foreach ($dataReader as $row) {
      $i += 1;
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->row(array(
        $i,
        $row['pono'],
        date(Yii::app()->params['dateviewfromdb'], strtotime($row['podate'])),
        $row['fullname'],
        Yii::app()->format->formatNumber($row['nominal']),
        Yii::app()->format->formatNumber($row['ppn']),
        Yii::app()->format->formatNumber($row['netto'])
      ));
      $total += $row['nominal'];
      $totalppn += $row['ppn'];
      $totalnetto += $row['netto'];
    }
		$this->pdf->setFont('Arial', 'B', 7);
    $this->pdf->row(array(
      '',
      '',
      'Grand Total',
      '',
      Yii::app()->format->formatNumber($total),
      Yii::app()->format->formatNumber($totalppn),
      Yii::app()->format->formatNumber($totalnetto)
    ));
    $this->pdf->Output();
  }
	public function RekapPOPerSupplier($companyid, $plant,$supplier, $product,$sloc, $startdate,$enddate) {
    parent::actionDownload();
    $sql          = "select fullname,sum(nominal) as nominal,sum(ppn) as ppn,sum(netto) as netto
                    from
                    (select *,(netto-nominal) as ppn
                    from 
                    (select a.poheaderid,a.addressbookid,a.pono,a.podate,d.fullname,
										(select sum(b.qty*b.price) 
                    from podetail b 
										join product e on e.productid=b.productid 
										where b.poheaderid=a.poheaderid and e.productname like '%" . $product . "%' ) as nominal, getamountbypo(a.poheaderid) as netto
                    from poheader a
                    inner join addressbook d on d.addressbookid=a.addressbookid
                    inner join plant e on e.plantid=a.plantid
                    where a.recordstatus>=4 
										and a.pono is not null 
										and d.fullname like '%" . $supplier . "%' 
										and e.plantid = " . $plant . " 
										and a.podate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') zz) x
                    group by fullname";
    $command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
    $totalppn     = 0;
    $totalnominal = 0;
    $total        = 0;
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap PO Per Supplier';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
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
      'C'
    );
    $this->pdf->setwidths(array(
      10,
      70,
      30,
      30,
      30
    ));
    $this->pdf->colheader = array(
      'No',
      'Nama Supplier',
      'Nominal',
      'PPN',
      'Total'
    );
    $this->pdf->RowHeader();
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'R',
      'R',
      'R',
    );
    $i                         = 0;
    foreach ($dataReader as $row) {
      $i += 1;
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->row(array(
        $i,
        $row['fullname'],
        Yii::app()->format->formatNumber($row['nominal']),
        Yii::app()->format->formatNumber($row['ppn']),
        Yii::app()->format->formatNumber($row['netto'])
      ));
      $totalnominal += $row['nominal'];
      $totalppn += $row['ppn'];
      $total += $row['netto'];
    }
		$this->pdf->setFont('Arial', 'B', 7);
    $this->pdf->row(array(
      '',
      'GRAND TOTAL',
      Yii::app()->format->formatNumber($totalnominal),
      Yii::app()->format->formatNumber($totalppn),
      Yii::app()->format->formatNumber($total)
    ));
    $this->pdf->checkPageBreak(20);
    $this->pdf->Output();
  }
	public function RekapPOPerBarang($companyid, $plant,$supplier, $product,$sloc, $startdate,$enddate) {
    parent::actionDownload();
            
    $sql        = "select distinct g.materialgroupid, g.description
                    from poheader a
                    join podetail b on b.poheaderid = a.poheaderid
                    join addressbook c on c.addressbookid = a.addressbookid
                    join paymentmethod d on d.paymentmethodid = a.paymentmethodid
                    join product e on e.productid = b.productid
                    join productplant f on f.productid = b.productid
                    join materialgroup g on g.materialgroupid = f.materialgroupid
                    join plant h on h.plantid = a.plantid
                    where h.companyid = " . $companyid . " 
										and h.plantid = ".$plant." 
										and a.pono is not null
                    and e.productname like '%" . $product . "%' 
										and c.fullname like '%" . $supplier . "%' 
                    and a.podate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'";
    $command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
		$totalqty1     = 0;
		$totalppn1     = 0;
		$totalnominal1 = 0;
		$total1        = 0;
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap PO Per Barang';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', 'B', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Grup Material');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . $row['description']);
      $this->pdf->SetFont('Arial', '', 10);
      $sql1         = "select distinct productname,uomcode,sum(qty) as poqty,sum(price) as netprice,sum(nominal) as nominal,sum(ppn) as ppn,sum(nominal+ppn) as total	
								from (select distinct f.podetailid,g.productname,f.qty,f.price,h.uomcode,f.qty*f.price as nominal,getamountdetailbypo(a.poheaderid,f.podetailid) as netto,
									(getamountdetailbypo(a.poheaderid,f.podetailid) - (f.qty*f.price)) as ppn
                from poheader a
                join addressbook d on d.addressbookid=a.addressbookid
                join paymentmethod e on e.paymentmethodid=a.paymentmethodid
                join podetail f on f.poheaderid = a.poheaderid
                join product g on g.productid = f.productid
                join unitofmeasure h on h.unitofmeasureid = f.uomid
                join productplant i on i.productid = f.productid
                join plant k on k.plantid=a.plantid
                where a.recordstatus>=4 and a.pono is not null and g.productname like '%" . $product . "%' and d.fullname like '%" . $supplier . "%' 
                and k.companyid = " . $companyid . " 
                and k.plantid = " . $plant . " 
								and i.materialgroupid = " . $row['materialgroupid'] . "
                and a.podate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') zz group by productname";
      $command1=$this->connection->createCommand($sql1);$dataReader1=$command1->queryAll();
      $i          = 0;
      $totalqty     = 0;
      $totalppn     = 0;
      $totalnominal = 0;
      $total        = 0;
      $this->pdf->sety($this->pdf->gety() + 15);
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
        60,
        30,
        30,
        30,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'QTY',
        'Nominal',
        'PPN',
        'Total'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['poqty']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['nominal']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['ppn']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['total'])
        ));
        $totalqty += $row1['poqty'];
        $totalnominal += $row1['nominal'];
        $totalppn += $row1['ppn'];
        $total += $row1['total'];
      }
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->row(array(
        '',
        'Total',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalnominal),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalppn),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $total)
      ));			
			$totalqty1 += $totalqty;
			$totalnominal1 += $totalnominal;
			$totalppn1 += $totalppn;
			$total1 += $total;
      $this->pdf->checkPageBreak(20);
    }
		$this->pdf->sety($this->pdf->gety() + 5);
		$this->pdf->row(array(
			'',
			'Grand Total',
			Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty1),
			Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalnominal1),
			Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalppn1),
			Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $total1)
		));
    
    $this->pdf->Output();
  }
	public function RincianPembelianPerDokumen($companyid, $plant,$supplier, $product,$sloc, $startdate,$enddate) {
    parent::actionDownload();
    
    $totalinvoice = 0;
    $sql          = "select distinct a.invoiceapid,b.grheaderid,ifnull(a.invoiceapno,0) as invno,a.invoiceapdate,e.paydays,c.grno,c.grdate,
						f.fullname,d.pono,d.podate as podate,c.poheaderid,i.companyid
						from invoiceap a
						left join invoiceapgr b on b.invoiceapid = a.invoiceapid 
						left join grheader c on c.grheaderid=b.grheaderid
						left join poheader d on d.poheaderid=a.poheaderid
						left join paymentmethod e on e.paymentmethodid=a.paymentmethodid
						left join addressbook f on f.addressbookid=d.addressbookid
						left join grdetail g on g.grheaderid = c.grheaderid
						left join product h on h.productid = g.productid
						left join plant i on i.plantid = a.plantid
						where a.recordstatus = 3 
						and i.companyid = " . $companyid . " 
						and i.plantid = " . $plant . " 
						and f.fullname like '%" . $supplier . "%' 
						and g.productname like '%" . $product . "%'
						and a.receiptdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
						and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
						order by receiptdate,grno";
    $command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rincian Pembelian Per Dokumen';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->text(10, $this->pdf->gety() + 10, 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    $totalallqty = 0;
    $totalallrp  = 0;
    
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 8);
      $this->pdf->text(10, $this->pdf->gety() + 0, 'No Invoice');
      $this->pdf->text(30, $this->pdf->gety() + 0, ': ' . $row['invno']);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'Tanggal');
      $this->pdf->text(30, $this->pdf->gety() + 5, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoiceapdate'])));
      $this->pdf->text(10, $this->pdf->gety() + 10, 'T.O.P.');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['paydays'] . ' HARI');
      $this->pdf->text(80, $this->pdf->gety() + 0, 'No LPB');
      $this->pdf->text(100, $this->pdf->gety() + 0, ': ' . $row['grno']);
      $this->pdf->text(80, $this->pdf->gety() + 5, 'Tanggal');
      $this->pdf->text(100, $this->pdf->gety() + 5, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])));
      $this->pdf->text(80, $this->pdf->gety() + 10, 'Supplier');
      $this->pdf->text(100, $this->pdf->gety() + 10, ': ' . $row['fullname']);
      $this->pdf->text(150, $this->pdf->gety() + 0, 'No PO');
      $this->pdf->text(180, $this->pdf->gety() + 0, ': ' . $row['pono']);
      $this->pdf->text(150, $this->pdf->gety() + 5, 'Tanggal');
      $this->pdf->text(180, $this->pdf->gety() + 5, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['podate'])));
      $sql1        = "select distinct a.grdetailid,g.productname,a.qty,h.uomcode,c.price,(a.qty * c.price) as jumlah,
										a.itemnote,getamountdetailbygr(a.grheaderid,a.grdetailid,c.podetailid) as PPN,
										(select zz.price
										from invoiceapdetail zz 
										left join invoiceapgr za on za.invoiceapid = za.invoiceapid = zz.invoiceapid
										where zz.productid = a.productid and zz.uomid = a.uomid limit 1) as amount
										from grdetail a
										left join podetail c on c.podetailid=a.podetailid
										left join poheader d on d.poheaderid=c.poheaderid
										left join paymentmethod e on e.paymentmethodid=d.paymentmethodid
										left join addressbook f on f.addressbookid=d.addressbookid
										left join product g on g.productid=a.productid
										left join unitofmeasure h on h.unitofmeasureid=a.uomid
							where d.plantid = " . $plant . " 
							and g.productname like '%" . $product . "%' 
							and f.fullname like '%" . $supplier . "%' 
							and a.grheaderid = " . $row['grheaderid'];
      $command1=$this->connection->createCommand($sql1);$dataReader1=$command1->queryAll();
      $total       = 0;
      $i           = 0;
      $totalqty    = 0;
      $this->pdf->sety($this->pdf->gety() + 12);
      $this->pdf->setFont('Arial', 'B', 8);
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
        50,
        20,
        15,
        30,
        30,
        38
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Satuan',
        'Harga',
        'Jumlah',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'R',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty']),
          $row1['uomcode'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['price']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['jumlah']),
          $row1['itemnote']
        ));
        $totalqty += $row1['qty'];
        $total += $row1['jumlah'];
        $totalallqty += $row1['qty'];
        $totalallrp += $row1['jumlah'];
      }
      $this->pdf->row(array(
        '',
        'KETERANGAN',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        '',
        '',
        'NOMINAL',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $total)
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        '',
        'PPN',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['PPN'])
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        '',
        'NETTO',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $total + ($row1['PPN']))
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        '',
        'ADJUSMENT',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], ($row1['amount']) - ($total + ($row1['PPN'])))
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        '',
        'NILAI INVOICE',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], ($row1['amount']))
      ));
      $totalinvoice += $row1['amount'];
      $this->pdf->checkPageBreak(20);
      $this->pdf->sety($this->pdf->gety() + 10);
    }
    $this->pdf->setFont('Arial', 'B', 9);
    $this->pdf->colalign = array(
      'C',
      'C',
      'C',
      'C',
      'C',
      'C'
    );
    $this->pdf->setwidths(array(
      25,
      40,
      30,
      25,
      40,
      30
    ));
    $this->pdf->row(array(
      '',
      'Total Qty ',
      Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalallqty),
      '',
      'Total Netto',
      Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalallrp)
    ));
    $this->pdf->row(array(
      '',
      'Total Adjustment ',
      Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalinvoice - $totalallrp),
      '',
      'Total Nilai Invoice',
      Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalinvoice)
    ));
    
    $this->pdf->Output();
  }
	public function RekapPembelianPerDokumen($companyid, $plant,$supplier, $product,$sloc, $startdate,$enddate) {
    parent::actionDownload();
    $sql        = "select distinct invoiceapno,grno,receiptdate,fullname,sum(jum) as jumlah,sum(pajak) as PPN,itemnote from
							(select distinct a.grdetailid,b.grheaderid,j.grno,k.invoiceapno,k.receiptdate,f.fullname,(a.qty * c.price) as jum,
							a.itemnote,(getamountdetailbygr(a.grheaderid,a.grdetailid,c.podetailid) - (a.qty * c.price)) as pajak 
							from grdetail a
							left join invoiceapgr b on b.grheaderid=a.grheaderid
							left join invoiceap k on k.invoiceapid=b.invoiceapid
							left join podetail c on c.podetailid=a.podetailid
							left join poheader d on d.poheaderid=k.poheaderid
							left join paymentmethod e on e.paymentmethodid=d.paymentmethodid
							left join addressbook f on f.addressbookid=d.addressbookid
							left join product g on g.productid=a.productid
							left join unitofmeasure h on h.unitofmeasureid=a.uomid
							left join grheader j on j.grheaderid=b.grheaderid
							left join plant l on l.plantid=k.plantid
							where k.recordstatus=3 
							and l.companyid = " . $companyid . " 
							and l.plantid = " . $plant . " 
							and g.productname like '%" . $product . "%' 
							and f.fullname like '%" . $supplier . "%' 
							and k.invoiceapdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
							and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z
							group by invoiceapno,grheaderid order by grno";
    $command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Pembelian Per Dokumen';
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
      'C',
      'C',
      'C'
    );
    $this->pdf->setwidths(array(
      10,
      20,
      17,
      15,
      45,
      20,
      18,
      20,
      25
    ));
    $this->pdf->colheader = array(
      'No',
      'No Invoice',
      'No LPB',
      'Tanggal',
      'Supplier',
      'Nominal',
      'PPN',
      'Netto',
      'Keterangan'
    );
    $this->pdf->RowHeader();
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'C',
      'C',
      'L',
      'R',
      'R',
      'R',
      'L'
    );
    $totalnetto1               = 0;
    $i                         = 0;
    $totaldisc1                = 0;
    $totaljumlah1              = 0;
    foreach ($dataReader as $row) {
      $i += 1;
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->row(array(
        $i,
        $row['invoiceapno'],
        $row['grno'],
        date(Yii::app()->params['dateviewfromdb'], strtotime($row['receiptdate'])),
        $row['fullname'],
        Yii::app()->format->formatCurrency($row['jumlah']),
        Yii::app()->format->formatCurrency($row['PPN']),
        Yii::app()->format->formatCurrency(($row['jumlah'] + $row['PPN'])),
        $row['itemnote']
      ));
      $totaljumlah1 += $row['jumlah'];
      $totaldisc1 += $row['PPN'];
      $totalnetto1 += ($row['jumlah'] + $row['PPN']);
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->colalign = array(
      'C',
      'C',
      'C',
      'C'
    );
    $this->pdf->setwidths(array(
      30,
      50,
      50,
      50
    ));
    $this->pdf->setFont('Arial', 'B', 9);
    $this->pdf->row(array(
      'TOTAL',
      'NOMINAL : ' . Yii::app()->format->formatCurrency($totaljumlah1),
      'PPN : ' . Yii::app()->format->formatCurrency($totaldisc1),
      'NETTO : ' . Yii::app()->format->formatCurrency($totalnetto1)
    ));
    
    $this->pdf->Output();
  }
	public function RekapPembelianPerSupplier($companyid, $plant,$supplier, $product,$sloc, $startdate,$enddate) {
    parent::actionDownload();
    $sql        = "select fullname,sum(nom) as nominal,sum(pajak) as PPN from 
						(select distinct a.grdetailid,f.fullname,a.qty,c.price,(a.qty * c.price) as nom,
						(getamountdetailbygr(a.grheaderid,a.grdetailid,c.podetailid) - (a.qty*c.price))as pajak 
						from grdetail a
						left join invoiceapgr b on b.grheaderid=a.grheaderid
						left join invoiceap j on j.invoiceapid=b.invoiceapid
						left join podetail c on c.podetailid=a.podetailid
						left join poheader d on d.poheaderid=j.poheaderid
						left join paymentmethod e on e.paymentmethodid=d.paymentmethodid
						left join addressbook f on f.addressbookid=d.addressbookid
						left join product g on g.productid=a.productid
						left join unitofmeasure h on h.unitofmeasureid=a.uomid
						left join plant i on i.plantid=j.plantid
						where j.recordstatus = 3 
						and i.companyid = " . $companyid . " 
						and i.plantid = " . $plant . " 
						and g.productname like '%" . $product . "%' 
						and f.fullname like '%" . $supplier . "%'
						and j.receiptdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
						and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z
						group by fullname order by fullname";
    $command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Pembelian Per Supplier';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    
    $totalnominal = 0;
    $totalppn     = 0;
    $totaljumlah  = 0;
    $i            = 0;
    $this->pdf->sety($this->pdf->gety() + 3);
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
      80,
      35,
      30,
      35
    ));
    $this->pdf->colheader = array(
      'No',
      'Nama Supplier',
      'Nominal',
      'PPN',
      'Total'
    );
    $this->pdf->RowHeader();
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'R',
      'R',
      'R'
    );
    $this->pdf->setFont('Arial', '', 8);
    foreach ($dataReader as $row) {
      $i += 1;
      $this->pdf->row(array(
        $i,
        $row['fullname'],
        Yii::app()->format->formatCurrency($row['nominal']),
        Yii::app()->format->formatCurrency($row['PPN']),
        Yii::app()->format->formatCurrency(($row['nominal'] + $row['PPN'])/ $plant)
      ));
      $totalnominal += $row['nominal'];
      $totalppn += $row['PPN'];
      $totaljumlah += ($row['nominal'] + $row['PPN']);
    }
    $this->pdf->sety($this->pdf->gety() + 3);
    $this->pdf->setFont('Arial', 'B', 9);
    $this->pdf->row(array(
      '',
      'GRAND TOTAL',
      Yii::app()->format->formatCurrency($totalnominal),
      Yii::app()->format->formatCurrency($totalppn),
      Yii::app()->format->formatCurrency($totaljumlah)
    ));
    
    $this->pdf->Output();
  }
	public function RekapPembelianPerBarang($companyid, $plant,$supplier, $product,$sloc, $startdate,$enddate) {
    parent::actionDownload();
    $totalqty1     = 0;
    $totalnominal1 = 0;
    $totalppn1     = 0;
    $sql           = "select distinct a.materialgroupid,a.description
				from materialgroup a
				join productplant b on b.materialgroupid = a.materialgroupid
				join product c on c.productid = b.productid
				join sloc d on d.slocid = b.slocid
				join plant e on e.plantid = d.plantid
				join company f on f.companyid = e.companyid
				where f.companyid = " . $companyid . " 
				and e.plantid = ".$plant." 
				and b.productid in
				(select distinct a.productid 
							from grdetail a
							left join invoiceapgr b on b.grheaderid=a.grheaderid
							left join invoiceap i on i.invoiceapid=b.invoiceapid
							left join podetail c on c.podetailid=a.podetailid
							left join poheader d on d.poheaderid=i.poheaderid
							left join paymentmethod e on e.paymentmethodid=d.paymentmethodid
							left join addressbook f on f.addressbookid=d.addressbookid
							left join product g on g.productid=a.productid
							left join unitofmeasure h on h.unitofmeasureid=a.uomid
							left join grheader j on j.grheaderid=b.grheaderid
							left join plant k on k.plantid=i.plantid
							where i.recordstatus=3 
							and k.plantid = " . $plant . " 
							and g.productname like '%" . $product . "%' 
							and f.fullname like '%" . $supplier . "%' 
							and i.receiptdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
							and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')";
    $command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Pembelian Per Barang';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Material Group');
      $this->pdf->text(40, $this->pdf->gety() + 10, ': ' . $row['description']);
      $sql1         = "select productname,sum(nom) as nominal,sum(pajak) as PPN, sum(qty) as qty,uomcode,price from 
						(select distinct a.grdetailid,g.productname,a.qty,c.price,(a.qty * c.price) as nom,h.uomcode,
						(getamountdetailbygr(a.grheaderid,a.grdetailid,c.podetailid) - (a.qty*c.price))as pajak 
						from grdetail a
							left join invoiceapgr k on k.grheaderid=a.grheaderid
							left join invoiceap b on b.invoiceapid = k.invoiceapid
							left join podetail c on c.podetailid=a.podetailid
							left join poheader d on d.poheaderid=b.poheaderid
							left join paymentmethod e on e.paymentmethodid=d.paymentmethodid
							left join addressbook f on f.addressbookid=d.addressbookid
							left join product g on g.productid=a.productid
							left join unitofmeasure h on h.unitofmeasureid=a.uomid
							left join productplant j on j.productid=a.productid and j.slocid=a.slocid
							where b.recordstatus = 3 and d.plantid = " . $plant . " and g.productname like '%" . $product . "%' and f.fullname like '%" . $supplier . "%' 
						  and j.materialgroupid = " . $row['materialgroupid'] . "
						  and b.receiptdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
						  and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' ) z
							group by productname order by productname";
      $command1=$this->connection->createCommand($sql1);$dataReader1=$command1->queryAll();
      $totalqty     = 0;
      $totalnetto   = 0;
      $totaldisc    = 0;
      $totalnominal = 0;
      $i            = 0;
      $this->pdf->sety($this->pdf->gety() + 15);
      $this->pdf->setFont('Arial', 'B', 8);
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
        60,
        20,
        15,
        28,
        25,
        33
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'QTY',
        'Satuan',
        'Harga',
        'PPN',
        'Jumlah'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'R',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      $totalnominal = 0;
      $totalqty     = 0;
      $totalppn     = 0;
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatCurrency($row1['qty']),
          $row1['uomcode'],
          Yii::app()->format->formatCurrency($row1['price']),
          Yii::app()->format->formatCurrency($row1['PPN']),
          Yii::app()->format->formatCurrency(($row1['nominal'] + $row1['PPN']))
        ));
        $totalqty += $row1['qty'];
        $totalppn += $row1['PPN'];
        $totalnominal += ($row1['nominal'] + $row1['PPN']);
      }
      $this->pdf->setFont('Arial', 'BI', 8);
      $this->pdf->row(array(
        '',
        'TOTAL ' . $row['description'],
        Yii::app()->format->formatCurrency($totalqty),
        '',
        '',
        Yii::app()->format->formatCurrency($totalppn),
        Yii::app()->format->formatCurrency($totalnominal)
      ));
      $totalqty1 += $totalqty;
      $totalnominal1 += $totalnominal;
      $totalppn1 += $totalppn;
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->sety($this->pdf->gety() + 5);
    $this->pdf->colalign = array(
      'C',
      'R',
      'R',
      'R'
    );
    $this->pdf->setwidths(array(
      70,
      38,
      50,
      40
    ));
    $this->pdf->setFont('Arial', 'B', 9);
    $this->pdf->row(array(
      'GRAND TOTAL',
      Yii::app()->format->formatCurrency($totalqty1),
      Yii::app()->format->formatCurrency($totalppn1),
      Yii::app()->format->formatCurrency($totalnominal1)
    ));
    
    $this->pdf->Output();
  }
	public function RincianReturPembelianPerDokumen($companyid, $plant,$supplier, $product,$sloc, $startdate,$enddate) {
    parent::actionDownload();
    $sql  = "	select distinct *
							from 
							(select a.grreturid,j.notagrrno as grreturno,g.fullname as supplier,j.notagrrdate as grreturdate,h.paycode,a.headernote							
							from grretur a
							join grreturdetail c on c.grreturid=a.grreturid
							join product d on d.productid=c.productid
							join podetail e on e.podetailid=c.podetailid
							join poheader f on f.poheaderid=e.poheaderid
							join addressbook g on g.addressbookid=f.addressbookid
							join paymentmethod h on h.paymentmethodid=f.paymentmethodid
							join notagrrgrr i on i.grreturid=a.grreturid
							join notagrr j on j.notagrrid=i.notagrrid
							join plant k on k.plantid=j.plantid
							where j.recordstatus = 3 
							and d.productname like '%" . $product . "%' 
							and g.fullname like '%" . $supplier . "%' 
							and k.companyid = " . $companyid . " 
							and k.plantid = " . $plant . " 
							and j.notagrrdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
							and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z";
    $command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
		
    foreach ($dataReader as $row) 
		{
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rincian Retur Pembelian Per Dokumen';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Dokumen');
      $this->pdf->text(40, $this->pdf->gety() + 10, ': ' . $row['grreturno']);
      $this->pdf->text(10, $this->pdf->gety() + 15, 'Supplier');
      $this->pdf->text(40, $this->pdf->gety() + 15, ': ' . $row['supplier']);
      $this->pdf->text(130, $this->pdf->gety() + 10, 'Tanggal');
      $this->pdf->text(160, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['grreturdate'])));
      $this->pdf->text(130, $this->pdf->gety() + 15, 'T.O.P');
      $this->pdf->text(160, $this->pdf->gety() + 15, ': ' . $row['paycode'] . ' HARI');
      $sql1 = "select distinct *,(nominal+ppn) as netto
               from (select distinct b.productname,a.qty,c.price,(a.qty*c.price) as jumlah,a.itemnote,
               (
								select sum(b.price*a.qty) 
								from podetail b
								where b.podetailid=c.podetailid 
								and b.productid=c.productid
								) as nominal,
                (select nominal*i.taxvalue/100 from tax i where i.taxid=g.taxid) as ppn
							 from grreturdetail a
							 left join product b on b.productid=a.productid
								left join podetail c on c.podetailid=a.podetailid
								left join poheader f on f.poheaderid = c.poheaderid
								left join taxpo g on g.poheaderid = c.poheaderid
								left join unitofmeasure d on d.unitofmeasureid=a.uomid
							 where a.grreturid = " . $row['grreturid'] . ")z";
      $command1=$this->connection->createCommand($sql1);$dataReader1=$command1->queryAll();
      $i           = 0;
      $totalqty    = 0;
      $totalppn    = 0;
      $totalnetto    = 0;
      $totalnominal    = 0;
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
        60,
        25,
        25,
        25,
        45
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Harga',
        'Jumlah',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) 
			{
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty']),
          Yii::app()->format->formatCurrency($row1['price']),
          Yii::app()->format->formatCurrency($row1['jumlah']),
          $row1['itemnote']
        ));
        $totalqty += $row1['qty'];
        $totalppn += $row1['ppn'];
        $totalnetto += $row1['netto'];
        $totalnominal += $row1['nominal'];
      }
      $this->pdf->row(array(
        '',
        'Keterangan : ' . $row['headernote'],
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        '',
        'NOMINAL',
        Yii::app()->format->formatCurrency($totalnominal),
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'PPN',
        Yii::app()->format->formatCurrency($totalppn)
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'NETTO',
		Yii::app()->format->formatCurrency($totalnetto) 
      )); 
      $this->pdf->checkPageBreak(20);
    }
    
    $this->pdf->Output();
  }
	public function RekapReturPembelianPerDokumen($companyid, $plant,$supplier, $product,$sloc, $startdate,$enddate) {
    parent::actionDownload();
    
    $sql  = "select *, ( netto - nominal)as ppn
						from
						(select ff.notagrrno as grreturno,a.grreturdate as grreturdate,c.fullname as supplier,a.grreturid,g.grreturdetailid,a.poheaderid,
						(select sum(d.qty*f.price) 
						from grreturdetail d
						join podetail f on f.podetailid=d.podetailid
						where d.grreturid=a.grreturid) as nominal,
						(getamountdetailbygrr(a.grreturid,g.grreturdetailid,b.poheaderid)) as netto
						from grretur a
						join grreturdetail g on g.grreturid = a.grreturid
						join poheader b on b.poheaderid=a.poheaderid 
						join addressbook c on c.addressbookid=b.addressbookid
						join podetail d on d.poheaderid = b.poheaderid
						join product e on e.productid = d.productid
						join notagrr ff on ff.poheaderid=b.poheaderid
						where ff.recordstatus = 3 and b.plantid = " . $plant . " and c.fullname like '%" . $supplier . "%' and e.productname like '%" . $product . "%'  and 
						a.grreturdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
						and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z order by grreturno";
    $command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
    foreach ($dataReader as $row) 
		{
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Retur Pembelian Per Dokumen';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    
    $i       = 0;
    $nominal = 0;
    $ppn     = 0;
    $total   = 0;
    $this->pdf->sety($this->pdf->gety() + 10);
    $this->pdf->setFont('Arial', 'B', 10);
    $this->pdf->colalign = array('C','C','C','C','C','C','C');
    $this->pdf->setwidths(array(10,25,20,40,35,25,30));
    $this->pdf->colheader = array(
      'No',
      'Dokumen',
      'Tanggal',
      'Supplier',
      'Nominal',
      'PPN',
      'Total'
    );
    $this->pdf->RowHeader();
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'L',
      'L',
      'R',
      'R',
      'R'
    );
    foreach ($dataReader as $row) {
      $i += 1;
      $this->pdf->SetFont('Arial', '', 8);
      $this->pdf->row(array(
        $i,
        $row['grreturno'],
        date(Yii::app()->params['dateviewfromdb'], strtotime($row['grreturdate'])),
        $row['supplier'],
        Yii::app()->format->formatCurrency($row['nominal']),
        Yii::app()->format->formatCurrency($row['ppn']),
        Yii::app()->format->formatCurrency($row['netto'])
      ));
      $nominal += $row['nominal'];
      $ppn += $row['ppn'];
      $total += $row['netto'];
      $this->pdf->checkPageBreak(20);
    }
		 $this->pdf->SetFont('Arial', 'B', 8);
    $this->pdf->row(array(
      '',
      'Grand Total',
      '',
      '',
      Yii::app()->format->formatCurrency($nominal),
      Yii::app()->format->formatCurrency($ppn),
      Yii::app()->format->formatCurrency($total)
    ));
    
    $this->pdf->Output();
  }
	public function RekapReturPembelianPerSupplier($companyid, $plant,$supplier, $product,$sloc, $startdate,$enddate) {
    parent::actionDownload();
    
    $sql = "select supplier,sum(nominal) as nominal,sum(ppn) as ppn,sum(netto) as netto
						from (select distinct *,(netto - nominal) as ppn
						from (select *
						from
						(select ff.notagrrno as grreturno,a.grreturdate as grreturdate,c.fullname as supplier,
						(getamountdetailbygrr(a.grreturid,g.grreturdetailid,b.poheaderid)) as netto,
						(select sum(d.qty*f.price) 
						from grreturdetail d
						join podetail f on f.podetailid=d.podetailid
						where d.grreturid=a.grreturid
						) as nominal
						from grretur a
						left join grreturdetail g on g.grreturid = a.grreturid
						left join poheader b on b.poheaderid=a.poheaderid 
						left join addressbook c on c.addressbookid=b.addressbookid
						left join podetail d on d.poheaderid = b.poheaderid
						left join product e on e.productid = d.productid
						left join notagrr ff on ff.poheaderid=b.poheaderid
						where ff.recordstatus = 3 and b.plantid = " . $plant . " and c.fullname like '%" . $supplier . "%' and e.productname like '%" . $product . "%'  and 
						a.grreturdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
						and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z ) zz 
            order by grreturno) zzz group by supplier";
    $command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
    foreach ($dataReader as $row) 
		{
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Retur Pembelian Per Supplier';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    
    $i       = 0;
    $nominal = 0;
    $ppn     = 0;
    $total   = 0;
    $this->pdf->sety($this->pdf->gety() + 10);
    $this->pdf->setFont('Arial', 'B', 10);
    $this->pdf->colalign = array(
      'C',
      'C',
      'C',
      'C',
      'C'
    );
    $this->pdf->setwidths(array(
      10,
      60,
      40,
      30,
      40
    ));
    $this->pdf->colheader = array(
      'No',
      'Supplier',
      'Nominal',
      'PPN',
      'Total'
    );
    $this->pdf->RowHeader();
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'R',
      'R',
      'R'
    );
    foreach ($dataReader as $row) {
      $i += 1;
      $this->pdf->SetFont('Arial', '', 9);
      $this->pdf->row(array(
        $i,
        $row['supplier'],
        Yii::app()->format->formatCurrency($row['nominal']),
        Yii::app()->format->formatCurrency($row['ppn']),
        Yii::app()->format->formatCurrency($row['netto'])
      ));
      $nominal += $row['nominal'];
      $nominal += $row['ppn'];
      $total += $row['netto'];
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->SetFont('Arial', 'B', 10);
    $this->pdf->row(array(
      '',
      'GRAND TOTAL',
      Yii::app()->format->formatCurrency($nominal),
      Yii::app()->format->formatCurrency($ppn),
      Yii::app()->format->formatCurrency($total)
    ));
    
    $this->pdf->Output();
  }
	public function RekapReturPembelianPerBarang($companyid, $plant,$supplier, $product,$sloc, $startdate,$enddate) {
    parent::actionDownload();
    $sql        = "select distinct h.materialgroupid, h.description
                    from grreturdetail a
                    join product b on b.productid=a.productid
                    join podetail c on c.podetailid=a.podetailid
                    join poheader d on d.poheaderid=c.poheaderid
                    join unitofmeasure e on e.unitofmeasureid=a.uomid
                    join grretur f on f.grreturid=a.grreturid
                    join productplant g on g.productid=a.productid
                    join materialgroup h on h.materialgroupid=g.materialgroupid
                    join unitofmeasure i on i.unitofmeasureid=a.uomid
                    join notagrr j on j.poheaderid=c.poheaderid
                    where j.recordstatus = 3 and  b.productname like '%" . $product . "%' " . "and d.podate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' and d.plantid = " . $plant . "
                    group by b.productname";
    $command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Retur Pembelian Per Barang';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Divisi');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['description']);
      $sql1        = "select *,sum(netto - nominal) as ppn
                      from
                      (select *
                      from
                      (select distinct b.productname,a.qty,i.uomcode,(a.qty*c.price) as nominal,h.description,
											(getamountdetailbygrr(a.grreturid,a.grreturdetailid,d.poheaderid)) as netto
                      from grreturdetail a
                      join product b on b.productid=a.productid
                      join podetail c on c.podetailid=a.podetailid
                      join poheader d on d.poheaderid=c.poheaderid
                      join unitofmeasure e on e.unitofmeasureid=a.uomid
                      join grretur f on f.grreturid=a.grreturid
                      join productplant g on g.productid=a.productid
                      join materialgroup h on h.materialgroupid=g.materialgroupid
                      join unitofmeasure i on i.unitofmeasureid=a.uomid
                      join notagrr j on j.poheaderid=d.poheaderid
                      where j.recordstatus = 3 and  b.productname like '%" . $product . "%' and d.podate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                      and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' and d.plantid = " . $plant . " and h.materialgroupid = " . $row['materialgroupid'] . " ) z) zz group by productname";
      $command1=$this->connection->createCommand($sql1);$dataReader1=$command1->queryAll();
      $totalqty    = 0;
      $nominal     = 0;
      $ppn         = 0;
      $total       = 0;
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
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        60,
        20,
        15,
        28,
        25,
        33
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Satuan',
        'Harga',
        'PPN',
        'Jumlah'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'R',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty']),
          $row1['uomcode'],
          Yii::app()->format->formatCurrency($row1['nominal']),
          Yii::app()->format->formatCurrency($row1['ppn']),
          Yii::app()->format->formatCurrency(($row1['netto'] + $row1['ppn']))
        ));
        $totalqty += $row1['qty'];
        $ppn += $row1['ppn'];
        $nominal += $row1['nominal'];
        $total += ($row1['netto'] + $row1['ppn']);
      }
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->row(array(
        '',
        'TOTAL ' . $row['description'],
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        '',
        Yii::app()->format->formatCurrency($nominal),
        Yii::app()->format->formatCurrency($ppn),
        Yii::app()->format->formatCurrency($total)
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
	public function RincianSelisihPembelianReturPerDokumen($companyid, $plant,$supplier, $product,$sloc, $startdate,$enddate) {
    parent::actionDownload();
    
    $totalinvoice = 0;
    $sql          = "select distinct a.invoiceapid,b.grheaderid,ifnull(a.invoiceapno,0) as invno,a.invoiceapdate,d.paydays,b.grno,b.grdate,
						e.fullname,c.pono,c.podate as podate,c.poheaderid,a.plantid
						from invoiceap a
						left join invoiceapgr z on z.invoiceapid = a.invoiceapid
						left join grheader b on b.grheaderid=z.grheaderid
						left join poheader c on c.poheaderid=b.poheaderid
						left join paymentmethod d on d.paymentmethodid=c.paymentmethodid
						left join addressbook e on e.addressbookid=c.addressbookid
						left join podetail f on f.poheaderid = c.poheaderid
						join product g on g.productid = f.productid
						where a.recordstatus = 3 and a.plantid = " . $plant . " and e.fullname like '%" . $supplier . "%' and g.productname like '%" . $product . "%'
						and a.receiptdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
						and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
						order by receiptdate,grno";
    $command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rincian Pembelian - Retur Per Dokumen';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    
    $totalallqty = 0;
    $totalallrp  = 0;
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 8);
      $this->pdf->text(10, $this->pdf->gety() + 0, 'No Invoice');
      $this->pdf->text(30, $this->pdf->gety() + 0, ': ' . $row['invno']);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'Tanggal');
      $this->pdf->text(30, $this->pdf->gety() + 5, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoiceapdate'])));
      $this->pdf->text(10, $this->pdf->gety() + 10, 'T.O.P.');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['paydays'] . ' HARI');
      $this->pdf->text(80, $this->pdf->gety() + 0, 'No LPB');
      $this->pdf->text(100, $this->pdf->gety() + 0, ': ' . $row['grno']);
      $this->pdf->text(80, $this->pdf->gety() + 5, 'Tanggal');
      $this->pdf->text(100, $this->pdf->gety() + 5, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])));
      $this->pdf->text(80, $this->pdf->gety() + 10, 'Supplier');
      $this->pdf->text(100, $this->pdf->gety() + 10, ': ' . $row['fullname']);
      $this->pdf->text(150, $this->pdf->gety() + 0, 'No PO');
      $this->pdf->text(180, $this->pdf->gety() + 0, ': ' . $row['pono']);
      $this->pdf->text(150, $this->pdf->gety() + 5, 'Tanggal');
      $this->pdf->text(180, $this->pdf->gety() + 5, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['podate'])));
      $sql1        = "select *,(netto - jumlah) as ppn
							from (select distinct a.grdetailid,g.productname,a.qty,h.uomcode,j.total as price,(a.qty * c.price) as jumlah,
							a.itemnote,GetAmountDetailByGR(a.grheaderid,a.grdetailid,b.poheaderid) as netto
							from grdetail a
							left join invoiceapgr i on i.grheaderid = a.grheaderid
							left join invoiceap b on b.invoiceapid = i.invoiceapid
							left join invoiceapdetail j on j.invoiceapid = b.invoiceapid
							left join podetail c on c.podetailid=a.podetailid
							left join poheader d on d.poheaderid=b.poheaderid
							left join paymentmethod e on e.paymentmethodid=d.paymentmethodid
							left join addressbook f on f.addressbookid=d.addressbookid
							left join product g on g.productid=a.productid
							left join unitofmeasure h on h.unitofmeasureid=a.uomid
							where d.plantid =  " . $plant . " and g.productname like '%" . $product . "%' and f.fullname like '%" . $supplier . "%' and a.grheaderid = " . $row['grheaderid'].") z group by productname";
      $command1=$this->connection->createCommand($sql1);$dataReader1=$command1->queryAll();
      $total       = 0;
      $i           = 0;
      $totalqty    = 0;
      $this->pdf->sety($this->pdf->gety() + 12);
      $this->pdf->setFont('Arial', 'B', 8);
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
        50,
        20,
        15,
        30,
        30,
        38
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Satuan',
        'Harga',
        'Jumlah',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'R',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty']),
          $row1['uomcode'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['price']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['jumlah']),
          $row1['itemnote']
        ));
        $totalqty += $row1['qty'];
        $total += $row1['jumlah'];
        $totalallqty += $row1['qty'];
        $totalallrp += $row1['jumlah'];
      }
      $this->pdf->row(array(
        '',
        'KETERANGAN',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        '',
        '',
        'NOMINAL',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $total)
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        '',
        'PPN',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['ppn'])
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        '',
        'NETTO',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $total + ($row1['netto']))
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        '',
        'ADJUSMENT',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], ($row1['price']) - ($total + ($row1['ppn'])))
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        '',
        'NILAI INVOICE',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], ($row1['price']))
      ));
      $totalinvoice += $row1['price'];
      $this->pdf->checkPageBreak(70);
      $this->pdf->sety($this->pdf->gety() + 10);
      
    }
    $sql2  = "	select distinct *
							from 
							(select a.grreturid,a.grreturno,g.fullname as supplier,a.grreturdate,h.paycode							
							from grretur a
							left join grreturdetail c on c.grreturid=a.grreturid
							left join product d on d.productid=c.productid
							left join podetail e on e.podetailid=c.podetailid
							left join poheader f on f.poheaderid=e.poheaderid
							left join addressbook g on g.addressbookid=f.addressbookid
							left join paymentmethod h on h.paymentmethodid=f.paymentmethodid
							left join notagrr i on i.poheaderid = f.poheaderid
							where i.recordstatus = 3 and d.productname like '%" . $product . "%' and g.fullname like '%" . $supplier . "%' and f.plantid = " . $plant . " and 
							a.grreturdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
							and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z";
    $command2=$this->connection->createCommand($sql2);$dataReader2=$command2->queryAll();
		
    foreach ($dataReader2 as $row2) 
		{
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rincian Pembelian - Retur Per Dokumen';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    foreach ($dataReader2 as $row2) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Dokumen');
      $this->pdf->text(40, $this->pdf->gety() + 10, ': ' . $row2['grreturno']);
      $this->pdf->text(10, $this->pdf->gety() + 15, 'Supplier');
      $this->pdf->text(40, $this->pdf->gety() + 15, ': ' . $row2['supplier']);
      $this->pdf->text(130, $this->pdf->gety() + 10, 'Tanggal');
      $this->pdf->text(160, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row2['grreturdate'])));
      $this->pdf->text(130, $this->pdf->gety() + 15, 'T.O.P');
      $this->pdf->text(160, $this->pdf->gety() + 15, ': ' . $row2['paycode'] . ' HARI');
      $sql3 = "select distinct *,(netto - nominal) as ppn
               from (select distinct b.productname,a.qty,c.price,(a.qty*c.price) as jumlah,a.itemnote,
               (
								select sum(b.price*a.qty) 
								from podetail b
								where b.podetailid=c.podetailid 
								and b.productid=c.productid
								) as nominal,
               	(getamountdetailbygrr(a.grreturid,a.grreturdetailid,c.poheaderid)) as netto
							 from grreturdetail a
							 left join product b on b.productid=a.productid
							 left join podetail c on c.podetailid=a.podetailid
							 left join poheader f on f.poheaderid = c.poheaderid
							 left join unitofmeasure d on d.unitofmeasureid=a.uomid
							 where a.grreturid = " . $row2['grreturid'] . ")z";
      $command3=$this->connection->createCommand($sql3);$dataReader3=$command3->queryAll();
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
        70,
        10,
        20,
        30,
        50
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Harga',
        'Jumlah',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader3 as $row3) 
			{
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row3['productname'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row3['qty']),
          Yii::app()->format->formatCurrency($row3['price']),
          Yii::app()->format->formatCurrency($row3['jumlah']),
          $row3['itemnote']
        ));
        $totalqty += $row3['qty'];
      }
      $this->pdf->row(array(
        '',
        'Keterangan : ' . $row3['itemnote'],
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        '',
        'NOMINAL',
        Yii::app()->format->formatCurrency($row3['nominal'])
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'PPN',
        Yii::app()->format->formatCurrency($row3['ppn'])
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'NETTO',
        Yii::app()->format->formatCurrency($row3['netto'])
      ));
      $this->pdf->checkPageBreak(50);
    }
    
    $this->pdf->Output();
  }
	public function RekapSelisihPembelianReturPerDokumen($companyid, $plant,$supplier, $product,$sloc, $startdate,$enddate) {
    parent::actionDownload();
    
    $sql        = "select distinct invoiceapno,grno,receiptdate,fullname,sum(jum) as jumlah,sum(net) as netto,sum(net - jum) as ppn,itemnote from
							(select distinct a.grdetailid,a.grheaderid,j.grno,b.invoiceapno,b.receiptdate,f.fullname,(a.qty * c.price) as jum,
							a.itemnote,GetAmountDetailByGR(a.grheaderid,a.grdetailid,d.poheaderid) as net 
							from grdetail a
							left join invoiceapgr z on z.grheaderid = a.grheaderid
							left join invoiceap b on b.invoiceapid = z.invoiceapid
							left join podetail c on c.podetailid=a.podetailid
							left join poheader d on d.poheaderid=b.poheaderid
							left join paymentmethod e on e.paymentmethodid=d.paymentmethodid
							left join addressbook f on f.addressbookid=d.addressbookid
							left join product g on g.productid=a.productid
							left join unitofmeasure h on h.unitofmeasureid=a.uomid
							left join grheader j on j.grheaderid=z.grheaderid
							where b.recordstatus=3 and d.plantid = " . $plant . " and g.productname like '%" . $product . "%' and f.fullname like '%" . $supplier . "%' 
							and b.receiptdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
							and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z
							group by invoiceapno,grheaderid order by grno";
    $command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap (Pembelian-Retur) Per Dokumen';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    
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
      'C'
    );
    $this->pdf->setwidths(array(
      10,
      20,
      20,
      15,
      45,
      22,
      20,
      22,
      20
    ));
    $this->pdf->colheader = array(
      'No',
      'No Invoice',
      'No LPB',
      'Tanggal',
      'Supplier',
      'Nominal',
      'PPN',
      'Netto',
      'Keterangan'
    );
    $this->pdf->RowHeader();
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'C',
      'C',
      'L',
      'R',
      'R',
      'R',
      'L'
    );
    $i                         = 0;
    $totalnominal              = 0;
    $totalppn                  = 0;
    $totalnetto                = 0;
    foreach ($dataReader as $row) {
      $i += 1;
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->row(array(
        $i,
        $row['invoiceapno'],
        $row['grno'],
        date(Yii::app()->params['dateviewfromdb'], strtotime($row['receiptdate'])),
        $row['fullname'],
        Yii::app()->format->formatCurrency($row['jumlah']),
        Yii::app()->format->formatCurrency($row['ppn']),
        Yii::app()->format->formatCurrency($row['netto']),
        $row['itemnote']));
      $totalnominal += $row['jumlah'];
      $totalppn += $row['ppn'];
      $totalnetto += ($row['netto']);
    }
    $sql1  = "select distinct *,(netto+nominal) as ppn
						from
						(select *
						from
						(select a.grreturno,a.grreturdate,c.fullname as supplier,
						(
						select sum(d.qty*f.price) 
						from grreturdetail d
						join podetail f on f.podetailid=d.podetailid
						where d.grreturid=a.grreturid
						) as nominal,
						(getamountdetailbygrr(a.grreturid,g.grreturdetailid,d.poheaderid)) as netto
						from grretur a
						join grreturdetail g on g.grreturid=a.grreturid                   
						join poheader b on b.poheaderid=a.poheaderid                   
						join addressbook c on c.addressbookid=b.addressbookid
						join podetail d on d.poheaderid = b.poheaderid
						join product e on e.productid = d.productid
            join notagrr j on j.poheaderid=b.poheaderid
						where a.recordstatus = 3 and b.plantid = " . $plant . " and c.fullname like '%" . $supplier . "%' and e.productname like '%" . $product . "%'  and 
						a.grreturdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
						and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z) zz order by grreturno";
    $command1=$this->connection->createCommand($sql1);$dataReader1=$command1->queryAll();
    foreach ($dataReader1 as $row1) {
      $i += 1;
      $this->pdf->SetFont('Arial', '', 7);
      $this->pdf->row(array(
        $i,'-',
        $row1['grreturno'],
        date(Yii::app()->params['dateviewfromdb'], strtotime($row1['grreturdate'])),
        $row1['supplier'],
        Yii::app()->format->formatCurrency(-$row1['nominal']),
        Yii::app()->format->formatCurrency(-$row1['ppn']),
        Yii::app()->format->formatCurrency(-$row1['netto'])
      ));
      $totalnominal -= $row1['nominal'];
      $totalppn -= $row1['ppn'];
      $totalnetto -= $row1['netto'];
    }
    $this->pdf->checkPageBreak(10);
    $this->pdf->SetFont('Arial', 'B', 8);
    $this->pdf->row(array(
      '',
      '',
      '',
      '',
      'GRAND TOTAL',
      Yii::app()->format->formatCurrency($totalnominal),
      Yii::app()->format->formatCurrency($totalppn),
      Yii::app()->format->formatCurrency($totalnetto)
    ));
    
    $this->pdf->Output();
  }
	public function RekapSelisihPembelianReturPerSupplier($companyid, $plant,$supplier, $product,$sloc, $startdate,$enddate) {
    parent::actionDownload();
    
    $sql          = "select *
										from (select fullname,nominalgr-nominalretur as nom, ppn-ppnretur as pajak, nettogr-nettoretur as total
										from (select *,nettogr - nominalgr as ppn,nettoretur-nominalretur as ppnretur
										from (select a.fullname,
										ifnull((select sum(b.qty * e.price)
										from grdetail b
										left join grheader c on c.grheaderid=b.grheaderid
										left join poheader d on d.poheaderid=c.poheaderid
										left join podetail e on e.podetailid=b.podetailid
										left join invoiceapgr g on g.grheaderid=c.grheaderid
										where c.recordstatus=3 and d.plantid = " . $plant . " and d.addressbookid=a.addressbookid
										and c.grdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0) as nominalgr,
										(select distinct sum(GetAmountDetailByGR(bb.grheaderid,bb.grdetailid,dd.poheaderid))
										from grdetail bb
										left join grheader cc on cc.grheaderid=bb.grheaderid
										left join poheader dd on dd.poheaderid=cc.poheaderid
										left join podetail ee on ee.podetailid=bb.podetailid and ee.productid = bb.productid and ee.uomid = bb.uomid and ee.slocid = bb.slocid
										left join invoiceapgr gg on gg.grheaderid=cc.grheaderid
										where cc.recordstatus=3 and dd.plantid = " . $plant . " and dd.addressbookid=a.addressbookid 
										and cc.grdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') as nettogr,
										ifnull((select sum(ba.qty * ea.price)
										from grreturdetail ba
										left join grretur ca on ca.grreturid=ba.grreturid
										left join poheader da on da.poheaderid=ca.poheaderid
										left join podetail ea on ea.podetailid=ba.podetailid
										left join notagrr ga on ga.poheaderid = da.poheaderid
										where ga.recordstatus=3 and da.plantid = " . $plant . " and da.addressbookid=a.addressbookid
										and ca.grreturdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0) as nominalretur,
										ifnull((select distinct sum((getamountdetailbygrr(cc.grreturid,bc.grreturdetailid,dc.poheaderid)))
										from grreturdetail bc
										left join grretur cc on cc.grreturid=bc.grreturid
										left join poheader dc on dc.poheaderid=cc.poheaderid
										left join podetail ec on ec.podetailid=bc.podetailid
										left join notagrr gc on gc.poheaderid = dc.poheaderid
										where gc.recordstatus=3 and dc.plantid = " . $plant . " and dc.addressbookid=a.addressbookid
										and cc.grreturdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0) as nettoretur
										from addressbook a
										where a.isvendor=1
										and a.fullname like '%".$supplier."%') z) zz) zzz
										where nom <> 0 
										order by fullname";
    $command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
    $totalppn     = 0;
    $totalnominal = 0;
    $total        = 0;
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap (Pembelian-Retur) Per Supplier';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    
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
      'C'
    );
    $this->pdf->setwidths(array(
      10,
      70,
      40,
      25,
      40
    ));
    $this->pdf->colheader = array(
      'No',
      'Nama Supplier',
      'Nominal',
      'PPN',
      'Total'
    );
    $this->pdf->RowHeader();
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'R',
      'R',
      'R',
      'R',
      'L'
    );
    $i                         = 0;
    foreach ($dataReader as $row) {
      $i += 1;
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->row(array(
        $i,
        $row['fullname'],
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row['nom']),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row['pajak']),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row['total'])
      ));
      $totalnominal += $row['nom'];
      $totalppn += $row['pajak'];
      $total += $row['total'];
    }
    $this->pdf->setFont('Arial', 'B', 8);
    $this->pdf->row(array(
      '',
      'GRAND TOTAL',
      Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalnominal),
      Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalppn),
      Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $total)
    ));
    $this->pdf->checkPageBreak(20);
    
    $this->pdf->Output();
  }
	public function RekapSelisihPembelianReturPerBarang($companyid, $plant,$supplier, $product,$sloc, $startdate,$enddate) {
		parent::actionDownload();
        
    
		$sql = "
		select distinct *
							from (select distinct aa.materialgroupid,aa.description
							from materialgroup aa
							join productplant ba on ba.materialgroupid = aa.materialgroupid
							join product ca on ca.productid = ba.productid
							join sloc da on da.slocid = ba.slocid
							where da.plantid = " . $plant . " and ba.productid in
							(select distinct a.productid 
							from grdetail a
							left join invoiceapgr b on b.grheaderid=a.grheaderid
							left join invoiceap k on k.invoiceapid = b.invoiceapid
							left join podetail c on c.podetailid=a.podetailid
							left join poheader d on d.poheaderid=c.poheaderid
							left join paymentmethod e on e.paymentmethodid=d.paymentmethodid
							left join addressbook f on f.addressbookid=d.addressbookid
							left join product g on g.productid=a.productid
							left join unitofmeasure h on h.unitofmeasureid=a.uomid
							left join grheader j on j.grheaderid=a.grheaderid
							where k.recordstatus=3 and d.plantid = " . $plant . " and g.productname like '%" . $product . "%' and f.fullname like '%" . $supplier . "%' and j.grdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
              and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
							union
							select distinct h.materialgroupid, h.description
							from grreturdetail a
							join product b on b.productid=a.productid
							join podetail c on c.podetailid=a.podetailid
							join poheader d on d.poheaderid=c.poheaderid
							join unitofmeasure e on e.unitofmeasureid=a.uomid
							join grretur f on f.grreturid=a.grreturid
							join productplant g on g.productid=a.productid
							join materialgroup h on h.materialgroupid=g.materialgroupid
							join unitofmeasure i on i.unitofmeasureid=a.uomid
							join notagrr ja on ja.poheaderid = d.poheaderid
							where ja.recordstatus = " . $plant . " and  b.productname like '%" . $product . "%' " . " and f.grreturdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
              and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' and d.plantid = " . $plant . ") z
									";
    $command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
    foreach ($dataReader as $row) 
		{
      $this->pdf->companyid = $companyid;
    }
		$this->pdf->title    = 'Rekap (Pembelian-Retur) Per Barang';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    
    $this->pdf->sety($this->pdf->gety() + 5);
		
   $i = 0;
    $totalqty1     = 0;
    $totalppn1     = 0;
    $totalnominal1 = 0;
    $total1        = 0;
		
		foreach ($dataReader as $row) {
		$this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Divisi');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['description']);
      $sql1 = "select distinct productname, sum(qty) as totalqty, uomcode, sum(nominal) as nom, sum(netto-nominal) as pajak, sum(netto) as nett
						from (select g.productname,a.qty,h.uomcode,sum(a.qty * c.price) as nominal,sum(GetAmountDetailByGR(a.grheaderid,a.grdetailid,d.poheaderid)) as netto 
						from grdetail a
						left join invoiceapgr b on b.grheaderid=a.grheaderid
						left join invoiceap k on k.invoiceapid = b.invoiceapid
						left join podetail c on c.podetailid=a.podetailid
						left join poheader d on d.poheaderid=k.poheaderid
						left join paymentmethod e on e.paymentmethodid=d.paymentmethodid
						left join addressbook f on f.addressbookid=d.addressbookid
						left join product g on g.productid=a.productid
						left join unitofmeasure h on h.unitofmeasureid=a.uomid
						left join productplant j on j.productid=a.productid and j.slocid=a.slocid
						where k.recordstatus = 3 and d.plantid = " . $plant . " and g.productname like '%" . $product . "%' and f.fullname like '%" . $supplier . "%' 
						and j.materialgroupid = " . $row['materialgroupid'] . "
						and k.receiptdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
              and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')zz
						group by productname
						union
						select distinct productname,-qty,uomcode,-nominal,-pajak, -netto
						from
						(select *,(netto-nominal) as pajak
						from
						(select ba.productname,aa.qty,ia.uomcode,(aa.qty*ca.price) as nominal,ha.description,getamountdetailbygrr(aa.grreturid,aa.grreturdetailid,da.poheaderid) as netto
						from grreturdetail aa
						join product ba on ba.productid=aa.productid
						join podetail ca on ca.podetailid=aa.podetailid
						join poheader da on da.poheaderid=ca.poheaderid
						join unitofmeasure ea on ea.unitofmeasureid=aa.uomid
						join grretur fa on fa.grreturid=aa.grreturid
						join productplant ga on ga.productid=aa.productid
						join materialgroup ha on ha.materialgroupid=ga.materialgroupid
						join unitofmeasure ia on ia.unitofmeasureid=aa.uomid
						join notagrr jj on jj.poheaderid=da.poheaderid
						where jj.recordstatus = 3 and ba.productname like '%%' and fa.grreturdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
              and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' and da.plantid = " . $plant . " and ha.materialgroupid = " . $row['materialgroupid'] . " ) z
						) x group by productname        ";
							
							$command1=$this->connection->createCommand($sql1);$dataReader1=$command1->queryAll();

			 $totalqty     = 0;
      $totalppn     = 0;
      $totalnominal = 0;
      $total        = 0;
      $this->pdf->sety($this->pdf->gety() + 13);
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
        60,
        30,
        30,
        30,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'QTY',
        'Nominal',
        'PPN',
        'Total'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
			
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['totalqty']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['nom']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['pajak']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['nett'])
        ));
        $totalqty += $row1['totalqty'];
        $totalnominal += $row1['nom'];
        $totalppn += $row1['pajak'];
        $total += $row1['nett'];
      }
			
			$this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->row(array(
        '',
        'TOTAL',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalnominal),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalppn),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $total)
      ));
      $totalqty1 += $totalqty;
      $totalnominal1 += $totalnominal;
      $totalppn1 += $totalppn;
      $total1 += $total;
     
    }
    $this->pdf->sety($this->pdf->gety()+5);
    $this->pdf->row(array(
      '',
      'GRAND TOTAL',
      Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty1),
      Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalnominal1),
      Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalppn1),
      Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $total1)
    ));
    	
    
		$this->pdf->Output();
	}
	public function PendinganPOPerDokumen($companyid, $plant,$supplier, $product,$sloc, $startdate,$enddate) {
		parent::actionDownload();
        
    
		$sql = "select distinct a.poheaderid, a.pono, c.fullname, d.paydays, a.podate
						from poheader a
						join podetail b on b.poheaderid = a.poheaderid
						join addressbook c on c.addressbookid = a.addressbookid
						left join paymentmethod d on d.paymentmethodid = a.paymentmethodid
						join product e on e.productid = b.productid
						where a.plantid = " . $plant . " and a.pono is not null
						and a.recordstatus=4
						and b.qty > (b.grqty + b.tsqty)
						and e.productname like '%" . $product . "%' and c.fullname like '%" . $supplier . "%' 
						and a.podate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
						and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' order by pono";
    $command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
    foreach ($dataReader as $row) 
		{
      $this->pdf->companyid = $companyid;
    }
		$this->pdf->title    = 'Pendingan PO Per Dokumen';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->text(10, $this->pdf->gety() + 10, 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
    $this->pdf->AddPage('P');
    
    $this->pdf->sety($this->pdf->gety() + 5);
		
    $totalallqty=0;$totalnetto1=0;
		
		foreach ($dataReader as $row) 
		{
			$this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'No Bukti');
      $this->pdf->text(30, $this->pdf->gety() + 5, ': ' . $row['pono']);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Supplier');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['fullname']);
      $this->pdf->text(150, $this->pdf->gety() + 5, 'Tgl Bukti');
      $this->pdf->text(180, $this->pdf->gety() + 5, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['podate'])));
      $this->pdf->text(150, $this->pdf->gety() + 10, 'T.O.P');
      $this->pdf->text(180, $this->pdf->gety() + 10, ': ' . $row['paydays'] . ' HARI');
			
			$sql1 = "select b.productname,a.qty,(a.grqty + a.tsqty) as qtyres,a.qty-(a.grqty + a.tsqty) as sisa,c.uomcode,a.price,(a.qty-(a.grqty + a.tsqty)) * a.price as jumlah,a.itemnote,(GetAmountDetailByPO(a.poheaderid,a.podetailid)-((a.qty-(a.grqty + a.tsqty))*a.price)) as ppn
							from podetail a
							join product b on b.productid=a.productid
							join unitofmeasure c on c.unitofmeasureid=a.uomid
							join poheader d on d.poheaderid=a.poheaderid
							where b.productname like '%" . $product . "%' 
							and a.qty > (a.grqty + a.tsqty) and a.poheaderid = " . $row['poheaderid'] . " ";
      $command1=$this->connection->createCommand($sql1);$dataReader1=$command1->queryAll();

			$totalnominal=0;$i=0;$totalqty=0;$totalppn=0;$totalnetto=0;$totalgrqty=0;$totalsisa=0;
			
      $this->pdf->sety($this->pdf->gety() + 13);
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->colalign = array('C','C','C','C','C','C','C','C','C');
      $this->pdf->setwidths(array(10,50,20,20,20,12,20,22,20));
      $this->pdf->colheader = array('No','Nama Barang','Qty','Qty GR','Sisa','Satuan','Harga','Jumlah','Keterangan');
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array('L','L','R','R','R','C','R','R','R');
      $this->pdf->setFont('Arial', '', 8);
			
      foreach ($dataReader1 as $row1)
			{
				$i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty']),
					Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qtyres']),
					Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['sisa']),
          $row1['uomcode'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['price']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], ($row1['jumlah'])),
          $row1['itemnote']
        ));
        $totalqty += $row1['qty'];
				$totalgrqty += $row1['qtyres'];
				$totalsisa += $row1['sisa'];
        $totalnominal += ($row1['jumlah']);
				$totalppn += $row1['ppn'];        
        $totalnetto += ($row1['jumlah'] + $row1['ppn']);
				
				
			}
			
			$this->pdf->row(array(
        '',
        'Total',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalgrqty),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalsisa),
        '',
        'NOMINAL',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalnominal)
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        '',
        '',
        'PPN',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalppn)
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        '',
        '',
        'NETTO',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalnetto)
      ));
			$this->pdf->sety($this->pdf->gety() + 8);
			$this->pdf->checkPageBreak(30);
			
			$totalallqty += ($totalqty - $totalgrqty);
			$totalnetto1 += $totalnetto;
    }
		
    $this->pdf->setFont('Arial', 'B', 9);
    $this->pdf->colalign = array('C','C','C','C','C','C');
    $this->pdf->setwidths(array(25,20,50,25,20,50));
    $this->pdf->row(array(
      '',
      'Total Qty ',
      Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalallqty),
      '',
      'Total Netto',
      Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalnetto1)
    ));		
    
		$this->pdf->Output();
	}
	public function RincianPendinganPOPerBarang($companyid, $plant,$supplier, $product,$sloc, $startdate,$enddate) {
    parent::actionDownload();
    $subtotalqty       = 0;
    $subtotalqtyoutput = 0;
    $subtotalselisih   = 0;
    $sql               = "select distinct description,slocid
				from (select distinct d.description,d.slocid,b.qty,
				ifnull((select sum(f.qty) 
				from grdetail f 
				join grheader h on h.grheaderid=f.grheaderid 
				where h.recordstatus = 3 and f.podetailid=b.podetailid),0) as grqty
				from poheader a
				join podetail b on b.poheaderid = a.poheaderid
				join product c on c.productid = b.productid
				join sloc d on d.slocid = b.slocid
				join addressbook e on e.addressbookid = a.addressbookid
				where a.recordstatus = 4
				and a.plantid = " . $plant . "  and c.productname like '%" . $product . "%' 
				and e.fullname like '%" . $supplier . "%'
				and a.podate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
				and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z
				where qty>grqty
				";
    $command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rincian Pendingan PO Per Barang';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', 'B', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'GUDANG');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['description']);
      $this->pdf->SetFont('Arial', '', 9);
      $sql1           = "select distinct productname,productid from (
				select distinct c.productname,c.productid,b.qty,
				ifnull((select sum(f.qty) 
				from grdetail f 
				join grheader h on h.grheaderid=f.grheaderid 
				where h.recordstatus = 3 and f.podetailid=b.podetailid),0) as grqty
				from poheader a
				join podetail b on b.poheaderid = a.poheaderid
				join product c on c.productid = b.productid
				join sloc d on d.slocid = b.slocid
				join addressbook e on e.addressbookid = a.addressbookid
				where a.recordstatus = 4 
				and a.plantid = " . $plant . "  and c.productname like '%" . $product . "%' 
				and e.fullname like '%" . $supplier . "%' and d.slocid = " . $row['slocid'] . "
				and a.podate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
				and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z
				where qty>grqty
				";
      $command1=$this->connection->createCommand($sql1);$dataReader1=$command1->queryAll();
      $totalqty       = 0;
      $totalqtyoutput = 0;
      $totalselisih   = 0;
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $this->pdf->checkPageBreak(30);
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->text(10, $this->pdf->gety() + 15, 'Nama Barang ');
        $this->pdf->text(33, $this->pdf->gety() + 15, ': ' . $row1['productname']);
        $sql2        = "select *
				from (select pono, podate, uomcode, qty, grqty, (qty-grqty) as selisih
						from (select b.pono, b.podate, d.uomcode, a.qty, 
						ifnull((select sum(c.qty) 
						from grdetail c 
						join grheader h on h.grheaderid=c.grheaderid 
						where h.recordstatus = 3 and c.podetailid=a.podetailid),0) as grqty
						from podetail a
						join poheader b on b.poheaderid = a.poheaderid
						join unitofmeasure d on d.unitofmeasureid = a.uomid
						join product e on e.productid = a.productid
						join addressbook f on f.addressbookid = b.addressbookid
						join sloc g on g.slocid = a.slocid
						where b.recordstatus = 4
						and b.plantid = " . $plant . " and e.productname like '%" . $product . "%' 
						and f.fullname like '%" . $supplier . "%' 
						and a.slocid = " . $row['slocid'] . "
						and a.productid = " . $row1['productid'] . "
						and b.podate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
						and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z
						where qty>grqty) zz";
        $command2=$this->connection->createCommand($sql2);$dataReader2=$command2->queryAll();
        $this->pdf->sety($this->pdf->gety() + 18);
        $this->pdf->setFont('Arial', 'B', 8);
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
          40,
          25,
          15,
          35,
          35,
          35
        ));
        $this->pdf->colheader = array(
          'No',
          'No PO',
          'Tgl PO',
          'Satuan',
          'Qty PO',
          'Qty GR',
          'Selisih'
        );
        $this->pdf->RowHeader();
        $this->pdf->coldetailalign = array(
          'C',
          'L',
          'C',
          'C',
          'R',
          'R',
          'R'
        );
        $i                         = 0;
        $jumlahqty                 = 0;
        $jumlahqtyoutput           = 0;
        $jumlahselisih             = 0;
        foreach ($dataReader2 as $row2) {
          $i += 1;
          $this->pdf->setFont('Arial', '', 8);
          $this->pdf->row(array(
            $i,
            $row2['pono'],
            $row2['podate'],
            $row2['uomcode'],
            Yii::app()->format->formatNumber($row2['qty']),
            Yii::app()->format->formatNumber($row2['grqty']),
            Yii::app()->format->formatNumber($row2['selisih'])
          ));
          $jumlahqty += $row2['qty'];
          $jumlahqtyoutput += $row2['grqty'];
          $jumlahselisih += $row2['selisih'];
        }
        $this->pdf->setFont('Arial', 'BI', 8);
        $this->pdf->setwidths(array(
          10,
          80,
          35,
          35,
          35
        ));
        $this->pdf->coldetailalign = array(
          'C',
          'R',
          'R',
          'R',
          'R'
        );
        $this->pdf->row(array(
          '',
          'JUMLAH ' . $row1['productname'],
          Yii::app()->format->formatNumber($jumlahqty),
          Yii::app()->format->formatNumber($jumlahqtyoutput),
          Yii::app()->format->formatNumber($jumlahselisih)
        ));
        $totalqty += $jumlahqty;
        $totalqtyoutput += $jumlahqtyoutput;
        $totalselisih += $jumlahselisih;
      }
      $this->pdf->setFont('Arial', 'BI', 9);
      $this->pdf->row(array(
        '',
        'TOTAL GUDANG ' . $row['description'],
        Yii::app()->format->formatNumber($totalqty),
        Yii::app()->format->formatNumber($totalqtyoutput),
        Yii::app()->format->formatNumber($totalselisih)
      ));
      $subtotalqty += $totalqty;
      $subtotalqtyoutput += $totalqtyoutput;
      $subtotalselisih += $totalselisih;
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->sety($this->pdf->gety() + 5);
    $this->pdf->setFont('Arial', 'B', 11);
    $this->pdf->row(array(
      '',
      'Grand Total ',
      Yii::app()->format->formatNumber($subtotalqty),
      Yii::app()->format->formatNumber($subtotalqtyoutput),
      Yii::app()->format->formatNumber($subtotalselisih)
    ));
    $this->pdf->Output();
  }
	public function RekapPendinganPOPerBarang($companyid, $plant,$supplier, $product,$sloc, $startdate,$enddate) {
    parent::actionDownload();
    $subtotalqty       = 0;
    $subtotalqtyoutput = 0;
    $subtotalselisih   = 0;
    $sql               = "select distinct description,slocid
				from (select distinct d.description,d.slocid,b.qty,b.grqty
				from poheader a
				join podetail b on b.poheaderid = a.poheaderid
				join product c on c.productid = b.productid
				join sloc d on d.slocid = b.slocid
				join addressbook e on e.addressbookid = a.addressbookid
				where a.recordstatus = 4
				and a.plantid = " . $plant . "  and c.productname like '%" . $product . "%' 
				and e.fullname like '%" . $supplier . "%'
				and a.podate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
				and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z
				where qty>grqty
					";
    $command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Pendingan PO Per Barang';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', 'B', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'GUDANG');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['description']);
      $sql1           = "select *
				from (select distinct productname, uomcode, sum(qty) as qty, sum(grqty) as grqty, sum(qty-grqty) as selisih
						from (select e.productname, d.uomcode, a.qty, a.grqty
						from podetail a
						join poheader b on b.poheaderid = a.poheaderid
						join unitofmeasure d on d.unitofmeasureid = a.uomid
						join product e on e.productid = a.productid
						join addressbook f on f.addressbookid = b.addressbookid
						join sloc g on g.slocid = a.slocid
						where b.recordstatus = 4 
						and b.plantid = " . $plant . " and e.productname like '%" . $product . "%' 
						and f.fullname like '%" . $supplier . "%' 
						and a.slocid = " . $row['slocid'] . "
						and b.podate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
						and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z
						where qty>grqty
						group by productname) zz
						";
      $command=$this->connection->createCommand($sql1);$dataReader1=$command->queryAll();
      $totalqty       = 0;
      $i              = 0;
      $totalqtyoutput = 0;
      $totalselisih   = 0;
      $this->pdf->sety($this->pdf->gety() + 12);
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
        70,
        25,
        30,
        30,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Satuan',
        'Qty PO',
        'Qty GR',
        'Selisih'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'C',
        'L',
        'C',
        'R',
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
          Yii::app()->format->formatNumber($row1['qty']),
          Yii::app()->format->formatNumber($row1['grqty']),
          Yii::app()->format->formatNumber($row1['selisih'])
        ));
        $totalqty += $row1['qty'];
        $totalqtyoutput += $row1['grqty'];
        $totalselisih += $row1['selisih'];
      }
      $this->pdf->setFont('Arial', 'BI', 9);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        95,
        30,
        30,
        30
      ));
      $this->pdf->coldetailalign = array(
        'C',
        'R',
        'R',
        'R',
        'R'
      );
      $this->pdf->row(array(
        '',
        'TOTAL GUDANG ' . $row['description'],
        Yii::app()->format->formatNumber($totalqty),
        Yii::app()->format->formatNumber($totalqtyoutput),
        Yii::app()->format->formatNumber($totalselisih)
      ));
      $subtotalqty += $totalqty;
      $subtotalqtyoutput += $totalqtyoutput;
      $subtotalselisih += $totalselisih;
    }
    $this->pdf->sety($this->pdf->gety() + 5);
    $this->pdf->setFont('Arial', 'BI', 11);
    $this->pdf->row(array(
      '',
      'GRAND TOTAL ',
      Yii::app()->format->formatNumber($subtotalqty),
      Yii::app()->format->formatNumber($subtotalqtyoutput),
      Yii::app()->format->formatNumber($subtotalselisih)
    ));
    $this->pdf->Output();
  }
  public function LaporanPOStatusBelumMax($companyid,$plant,$supplier,$product,$sloc,$startdate,$enddate) {
        parent::actionDownload();
        $sql = "SELECT a.poheaderid,c.companycode, a.pono, a.podate, b.fullname,d.addresstoname, c.billto, a.statusname, e.paycode
								FROM poheader a 
								LEFT JOIN addressbook b ON b.addressbookid = a.addressbookid
								left join address f on f.addressbookid = b.addressbookid
								left join plant d on d.plantid = a.plantid
								LEFT JOIN company c ON c.companyid = d.companyid
								LEFT JOIN paymentmethod e ON e.paymentmethodid = a.paymentmethodid
								WHERE a.recordstatus between 1 and 3
                AND podate BETWEEN ('".date(Yii::app()->params['datetodb'], strtotime($startdate))."') 
                AND ('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
                AND a.plantid=".$plant."
                AND b.fullname LIKE '%".$supplier."%'
                AND b.isvendor=1";
        $command=$this->connection->createCommand($sql);$dataReader=$command->queryAll();
        foreach ($dataReader as $row) 
		{
            $this->pdf->companyid = $companyid;
        }
        
        $this->pdf->title    = 'Laporan PO Status Belum Max';
        $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
        $this->pdf->text(10, $this->pdf->gety() + 10, 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
        $this->pdf->AddPage('P','A4');
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
      'C'
    );
    $this->pdf->setwidths(array(
      15,
      30,
      25,
      80,
      20,
      20,
      40
    ));
    $this->pdf->colheader = array(
      'ID',
      'NO PO',
      'Tanggal PO',
      'Supplier',
      'Tempo',
      'Status'
    );
    $this->pdf->RowHeader();        
    $i=1;
    $this->pdf->coldetailalign = array(
      'R',
      'L',
      'L',
      'L',
      'L',
      'L'
    );
    foreach($dataReader as $row){
         $this->pdf->row(array(
        $row['poheaderid'],
        $row['pono'],
        date(Yii::app()->params['dateviewfromdb'], strtotime($row['podate'])),
        $row['fullname'],
        $row['paycode'],
        $row['statusname']
            ));
        $i++;
    }
    $this->pdf->Output();
    }
	public function RekapPembelianPerBarangPerTanggal($companyid,$plant,$supplier,$product,$sloc,$startdate,$enddate) {
    parent::actionDownload();
    
    
		$sql        = "select distinct g.productid,g.productname,e.companyid
								from grdetail a
								join grheader b on b.grheaderid=a.grheaderid
								join podetail c on c.podetailid=a.podetailid
								join poheader d on d.poheaderid=c.poheaderid and d.poheaderid=b.poheaderid
								left join plant e on e.plantid = d.plantid
								join addressbook f on f.addressbookid=d.addressbookid
								join product g on g.productid=a.productid
								where b.recordstatus = 3 and d.plantid = " . $plant . " and f.fullname like '%" . $supplier . "%' and g.productname like '%" . $product . "%' and b.grdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
								order by productname ";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) 
		{
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Pembelian Per Barang Per Tanggal';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    
    foreach ($dataReader as $row) 
		{
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 3, 'NAMA BARANG :');
      $this->pdf->text(40, $this->pdf->gety() + 3, $row['productname']);
      $sql1        = "select a.arrivedate, a.price 
                    from podetail a
                    join poheader b on a.poheaderid=b.poheaderid
                    WHERE b.plantid = " . $plant . " AND arrivedate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' and productid=".$row['productid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $totalqty    = 0;
      $i           = 0;
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        40,
        40
      ));
      $this->pdf->colheader = array(
        'No',
        'Tanggal',
        'Harga'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'C',
        'C',
        'C');
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['arrivedate'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['price']),
          
        ));
        //$totalqty += $row1['qty'];
      }
      /*$this->pdf->row(array(
        '',
        'Total -> ' . $row['description'],
        '',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty)
      ));*/
      $this->pdf->checkPageBreak(20);
      $this->pdf->sety($this->pdf->gety() + 8);
    }
    
    $this->pdf->Output();
  }
  public function LaporanPembelianPerSupplierPerBulanPerTahun($companyid,$plant,$supplier,$product,$sloc,$startdate,$enddate) {
		parent::actionDownload();
        
		$sql = "select * from
					(select z.fullname,
					(select sum(netto-nom) from 
					(select distinct f.addressbookid, f.isvendor, a.grdetailid,f.fullname,a.qty,c.price,(a.qty * c.price) as nom,
						GetAmountDetailByGR(a.grheaderid,a.grdetailid,d.poheaderid) as netto 
						from grdetail a
						left join invoiceapgr b on b.grheaderid=a.grheaderid
						left join invoiceap j on j.invoiceapid=b.invoiceapid
						left join podetail c on c.podetailid=a.podetailid
						left join poheader d on d.poheaderid=d.poheaderid
						left join addressbook f on f.addressbookid=d.addressbookid
						left join product g on g.productid=a.productid
						where j.recordstatus = 3 and d.plantid =" . $plant . "  and g.productname like '%%' and f.fullname like '%%' and  month(j.receiptdate)=" . $plant . " and year(j.receiptdate)=year('". date(Yii::app()->params['datetodb'], strtotime($startdate))."')) zx where zx.addressbookid = z.addressbookid and zx.isvendor=1  
group by fullname order by fullname) as januari,

(select sum(netto-nom) from 
					(select distinct f.addressbookid, f.isvendor, a.grdetailid,f.fullname,a.qty,c.price,(a.qty * c.price) as nom,
						GetAmountDetailByGR(a.grheaderid,a.grdetailid,d.poheaderid) as netto 
						from grdetail a
						left join invoiceapgr b on b.grheaderid=a.grheaderid
						left join invoiceap j on j.invoiceapid=b.invoiceapid
						left join podetail c on c.podetailid=a.podetailid
						left join poheader d on d.poheaderid=d.poheaderid
						left join addressbook f on f.addressbookid=d.addressbookid
						left join product g on g.productid=a.productid
						
						where j.recordstatus = 3 and d.plantid =" . $plant . "  and g.productname like '%%' and f.fullname like '%%' and  month(j.receiptdate)=2 and year(j.receiptdate)=year('". date(Yii::app()->params['datetodb'], strtotime($startdate))."')) zx where zx.addressbookid = z.addressbookid and zx.isvendor=1  
group by fullname order by fullname) as februari,

(select sum(netto-nom) from 
					(select distinct f.addressbookid, f.isvendor, a.grdetailid,f.fullname,a.qty,c.price,(a.qty * c.price) as nom,
						GetAmountDetailByGR(a.grheaderid,a.grdetailid,d.poheaderid) as netto 
						from grdetail a
						left join invoiceapgr b on b.grheaderid=a.grheaderid
						left join invoiceap j on j.invoiceapid=b.invoiceapid
						left join podetail c on c.podetailid=a.podetailid
						left join poheader d on d.poheaderid=d.poheaderid
						left join addressbook f on f.addressbookid=d.addressbookid
						left join product g on g.productid=a.productid
						
						where j.recordstatus = 3 and d.plantid =" . $plant . "  and g.productname like '%%' and f.fullname like '%%' and  month(j.receiptdate)=3 and year(j.receiptdate)=year('". date(Yii::app()->params['datetodb'], strtotime($startdate))."')) zx where zx.addressbookid = z.addressbookid and zx.isvendor=1  
group by fullname order by fullname) as maret,

(select sum(netto-nom) from 
					(select distinct f.addressbookid, f.isvendor, a.grdetailid,f.fullname,a.qty,c.price,(a.qty * c.price) as nom,
						GetAmountDetailByGR(a.grheaderid,a.grdetailid,d.poheaderid) as netto 
						from grdetail a
						left join invoiceapgr b on b.grheaderid=a.grheaderid
						left join invoiceap j on j.invoiceapid=b.invoiceapid
						left join podetail c on c.podetailid=a.podetailid
						left join poheader d on d.poheaderid=d.poheaderid
						left join addressbook f on f.addressbookid=d.addressbookid
						left join product g on g.productid=a.productid
						
						where j.recordstatus = 3 and d.plantid =" . $plant . "  and g.productname like '%%' and f.fullname like '%%' and  month(j.receiptdate)=4 and year(j.receiptdate)=year('". date(Yii::app()->params['datetodb'], strtotime($startdate))."')) zx where zx.addressbookid = z.addressbookid and zx.isvendor=1  
group by fullname order by fullname) as april,

(select sum(netto-nom) from 
					(select distinct f.addressbookid, f.isvendor, a.grdetailid,f.fullname,a.qty,c.price,(a.qty * c.price) as nom,
						GetAmountDetailByGR(a.grheaderid,a.grdetailid,d.poheaderid) as netto 
						from grdetail a
						left join invoiceapgr b on b.grheaderid=a.grheaderid
						left join invoiceap j on j.invoiceapid=b.invoiceapid
						left join podetail c on c.podetailid=a.podetailid
						left join poheader d on d.poheaderid=d.poheaderid
						left join addressbook f on f.addressbookid=d.addressbookid
						left join product g on g.productid=a.productid
						
						where j.recordstatus = 3 and d.plantid =" . $plant . "  and g.productname like '%%' and f.fullname like '%%' and  month(j.receiptdate)=5 and year(j.receiptdate)=year('". date(Yii::app()->params['datetodb'], strtotime($startdate))."')) zx where zx.addressbookid = z.addressbookid and zx.isvendor=1  
group by fullname order by fullname) as mei,

(select sum(netto-nom) from 
					(select distinct f.addressbookid, f.isvendor, a.grdetailid,f.fullname,a.qty,c.price,(a.qty * c.price) as nom,
						GetAmountDetailByGR(a.grheaderid,a.grdetailid,d.poheaderid) as netto 
						from grdetail a
						left join invoiceapgr b on b.grheaderid=a.grheaderid
						left join invoiceap j on j.invoiceapid=b.invoiceapid
						left join podetail c on c.podetailid=a.podetailid
						left join poheader d on d.poheaderid=d.poheaderid
						left join addressbook f on f.addressbookid=d.addressbookid
						left join product g on g.productid=a.productid
						
						where j.recordstatus = 3 and d.plantid =" . $plant . "  and g.productname like '%%' and f.fullname like '%%' and  month(j.receiptdate)=6 and year(j.receiptdate)=year('". date(Yii::app()->params['datetodb'], strtotime($startdate))."')) zx where zx.addressbookid = z.addressbookid and zx.isvendor=1  
group by fullname order by fullname) as juni,

(select sum(netto-nom) from 
					(select distinct f.addressbookid, f.isvendor, a.grdetailid,f.fullname,a.qty,c.price,(a.qty * c.price) as nom,
						GetAmountDetailByGR(a.grheaderid,a.grdetailid,d.poheaderid) as netto 
						from grdetail a
						left join invoiceapgr b on b.grheaderid=a.grheaderid
						left join invoiceap j on j.invoiceapid=b.invoiceapid
						left join podetail c on c.podetailid=a.podetailid
						left join poheader d on d.poheaderid=d.poheaderid
						left join addressbook f on f.addressbookid=d.addressbookid
						left join product g on g.productid=a.productid
						
						where j.recordstatus = 3 and d.plantid =" . $plant . "  and g.productname like '%%' and f.fullname like '%%' and  month(j.receiptdate)=7 and year(j.receiptdate)=year('". date(Yii::app()->params['datetodb'], strtotime($startdate))."')) zx where zx.addressbookid = z.addressbookid and zx.isvendor=1  
group by fullname order by fullname) as juli,

(select sum(netto-nom) from 
					(select distinct f.addressbookid, f.isvendor, a.grdetailid,f.fullname,a.qty,c.price,(a.qty * c.price) as nom,
						GetAmountDetailByGR(a.grheaderid,a.grdetailid,d.poheaderid) as netto 
						from grdetail a
						left join invoiceapgr b on b.grheaderid=a.grheaderid
						left join invoiceap j on j.invoiceapid=b.invoiceapid
						left join podetail c on c.podetailid=a.podetailid
						left join poheader d on d.poheaderid=d.poheaderid
						left join addressbook f on f.addressbookid=d.addressbookid
						left join product g on g.productid=a.productid
						
						where j.recordstatus = 3 and d.plantid =" . $plant . "  and g.productname like '%%' and f.fullname like '%%' and  month(j.receiptdate)=8 and year(j.receiptdate)=year('". date(Yii::app()->params['datetodb'], strtotime($startdate))."')) zx where zx.addressbookid = z.addressbookid and zx.isvendor=1  
group by fullname order by fullname) as agustus,

(select sum(netto-nom) from 
					(select distinct f.addressbookid, f.isvendor, a.grdetailid,f.fullname,a.qty,c.price,(a.qty * c.price) as nom,
						GetAmountDetailByGR(a.grheaderid,a.grdetailid,d.poheaderid) as netto 
						from grdetail a
						left join invoiceapgr b on b.grheaderid=a.grheaderid
						left join invoiceap j on j.invoiceapid=b.invoiceapid
						left join podetail c on c.podetailid=a.podetailid
						left join poheader d on d.poheaderid=d.poheaderid
						left join addressbook f on f.addressbookid=d.addressbookid
						left join product g on g.productid=a.productid
						
						where j.recordstatus = 3 and d.plantid =" . $plant . "  and g.productname like '%%' and f.fullname like '%%' and  month(j.receiptdate)=9 and year(j.receiptdate)=year('". date(Yii::app()->params['datetodb'], strtotime($startdate))."')) zx where zx.addressbookid = z.addressbookid and zx.isvendor=1  
group by fullname order by fullname) as september,

(select sum(netto-nom) from 
					(select distinct f.addressbookid, f.isvendor, a.grdetailid,f.fullname,a.qty,c.price,(a.qty * c.price) as nom,
						GetAmountDetailByGR(a.grheaderid,a.grdetailid,d.poheaderid) as netto 
						from grdetail a
						left join invoiceapgr b on b.grheaderid=a.grheaderid
						left join invoiceap j on j.invoiceapid=b.invoiceapid
						left join podetail c on c.podetailid=a.podetailid
						left join poheader d on d.poheaderid=d.poheaderid
						left join addressbook f on f.addressbookid=d.addressbookid
						left join product g on g.productid=a.productid
						
						where j.recordstatus = 3 and d.plantid =" . $plant . "  and g.productname like '%%' and f.fullname like '%%' and  month(j.receiptdate)=10 and year(j.receiptdate)=year('". date(Yii::app()->params['datetodb'], strtotime($startdate))."')) zx where zx.addressbookid = z.addressbookid and zx.isvendor=1  
group by fullname order by fullname) as oktober,

(select sum(netto-nom) from 
					(select distinct f.addressbookid, f.isvendor, a.grdetailid,f.fullname,a.qty,c.price,(a.qty * c.price) as nom,
						GetAmountDetailByGR(a.grheaderid,a.grdetailid,d.poheaderid) as netto 
						from grdetail a
						left join invoiceapgr b on b.grheaderid=a.grheaderid
						left join invoiceap j on j.invoiceapid=b.invoiceapid
						left join podetail c on c.podetailid=a.podetailid
						left join poheader d on d.poheaderid=d.poheaderid
						left join addressbook f on f.addressbookid=d.addressbookid
						left join product g on g.productid=a.productid
						
						where j.recordstatus = 3 and d.plantid =" . $plant . "  and g.productname like '%%' and f.fullname like '%%' and  month(j.receiptdate)=11 and year(j.receiptdate)=year('". date(Yii::app()->params['datetodb'], strtotime($startdate))."')) zx where zx.addressbookid = z.addressbookid and zx.isvendor=1  
group by fullname order by fullname) as november,
(select sum(netto-nom) from 

(select distinct f.addressbookid, f.isvendor, a.grdetailid,f.fullname,a.qty,c.price,(a.qty * c.price) as nom,
						GetAmountDetailByGR(a.grheaderid,a.grdetailid,d.poheaderid) as netto 
						from grdetail a
						left join invoiceapgr b on b.grheaderid=a.grheaderid
						left join invoiceap j on j.invoiceapid=b.invoiceapid
						left join podetail c on c.podetailid=a.podetailid
						left join poheader d on d.poheaderid=d.poheaderid
						left join addressbook f on f.addressbookid=d.addressbookid
						left join product g on g.productid=a.productid
						
						where j.recordstatus = 3 and d.plantid =" . $plant . "  and g.productname like '%%' and f.fullname like '%%' and  month(j.receiptdate)=12 and year(j.receiptdate)=year('". date(Yii::app()->params['datetodb'], strtotime($startdate))."')) zx where zx.addressbookid = z.addressbookid and zx.isvendor=1  
group by fullname order by fullname) as desember,
(select sum(netto-nom) from 
					(select distinct f.addressbookid, f.isvendor, a.grdetailid,f.fullname,a.qty,c.price,(a.qty * c.price) as nom,
						GetAmountDetailByGR(a.grheaderid,a.grdetailid,d.poheaderid) as netto 
						from grdetail a
						left join invoiceapgr b on b.grheaderid=a.grheaderid
						left join invoiceap j on j.invoiceapid=b.invoiceapid
						left join podetail c on c.podetailid=a.podetailid
						left join poheader d on d.poheaderid=d.poheaderid
						left join addressbook f on f.addressbookid=d.addressbookid
						left join product g on g.productid=a.productid
						
						where j.recordstatus = 3 and d.plantid =" . $plant . "  and g.productname like '%%' and f.fullname like '%%' and  year(j.receiptdate)=year('". date(Yii::app()->params['datetodb'], strtotime($startdate))."')) zx where zx.addressbookid = z.addressbookid and zx.isvendor=1  
group by fullname order by fullname) as jumlah
					from addressbook z
					where z.recordstatus=1 and z.isvendor=1 and z.fullname is not null order by fullname asc) zz
					where zz.jumlah <> 0";
		
			$command=$this->connection->createCommand($sql);
			$i=0;$totaljanuari=0;$totalfebruari=0;$totalmaret=0;$totalapril=0;$totalmei=0;$totaljuni=0;$totaljuli=0;$totalagustus=0;$totalseptember=0;$totaloktober=0;$totalnopember=0;$totaldesember=0;$totaljumlah=0;
			$dataReader=$command->queryAll();
			
			foreach($dataReader as $row)
			{
				$this->pdf->companyid = $companyid;
			}
			$this->pdf->title='Laporan Pembelian Per Supplier Per Bulan PerTahun';
			$this->pdf->subtitle='Per Tahun '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate));
			$this->pdf->AddPage('P',array(400,170));
			
           
			$this->pdf->setFont('Arial','B',8);
            $this->pdf->sety($this->pdf->gety()+0);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(10,40,25,25,25,25,25,25,25,25,25,25,25,25,30));
			$this->pdf->colheader = array('No','Supplier','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember','Total');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('L','L','R','R','R','R','R','R','R','R','R','R','R','R','R');		
			
			foreach($dataReader as $row)
			{
				$this->pdf->setFont('Arial','',7);
				$i=$i+1;
				$this->pdf->row(array(
					$i,$row['fullname'],
					Yii::app()->format->formatCurrency($row['januari']/$plant),
					Yii::app()->format->formatCurrency($row['februari']/$plant),
					Yii::app()->format->formatCurrency($row['maret']/$plant),
					Yii::app()->format->formatCurrency($row['april']/$plant),
					Yii::app()->format->formatCurrency($row['mei']/$plant),
					Yii::app()->format->formatCurrency($row['juni']/$plant),
					Yii::app()->format->formatCurrency($row['juli']/$plant),
					Yii::app()->format->formatCurrency($row['agustus']/$plant),
					Yii::app()->format->formatCurrency($row['september']/$plant),
					Yii::app()->format->formatCurrency($row['oktober']/$plant),
					Yii::app()->format->formatCurrency($row['november']/$plant),
					Yii::app()->format->formatCurrency($row['desember']/$plant),
					Yii::app()->format->formatCurrency($row['jumlah']/$plant)
				));
				$totaljanuari += $row['januari']/$plant;
        $totalfebruari += $row['februari']/$plant;
				$totalmaret += $row['maret']/$plant;
				$totalapril += $row['april']/$plant;
				$totalmei += $row['mei']/$plant;
				$totaljuni += $row['juni']/$plant;
				$totaljuli += $row['juli']/$plant;
				$totalagustus += $row['agustus']/$plant;
				$totalseptember += $row['september']/$plant;
				$totaloktober += $row['oktober']/$plant;
				$totalnopember += $row['november']/$plant;
				$totaldesember += $row['desember']/$plant;
				$totaljumlah += $row['jumlah']/$plant;
				$this->pdf->checkPageBreak(20);
			}
			$this->pdf->colalign = array('L','L','R','R','R','R','R','R','R','R','R','R','R','R','R');
			$this->pdf->setwidths(array(10,40,25,25,25,25,25,25,25,25,25,25,25,25,30));
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->row(array(
					'','TOTAL',
						Yii::app()->format->formatCurrency($totaljanuari),
						Yii::app()->format->formatCurrency($totalfebruari),
						Yii::app()->format->formatCurrency($totalmaret),
						Yii::app()->format->formatCurrency($totalapril),
						Yii::app()->format->formatCurrency($totalmei),
						Yii::app()->format->formatCurrency($totaljuni),
						Yii::app()->format->formatCurrency($totaljuli),
						Yii::app()->format->formatCurrency($totalagustus),
						Yii::app()->format->formatCurrency($totalseptember),
						Yii::app()->format->formatCurrency($totaloktober),
						Yii::app()->format->formatCurrency($totalnopember),
						Yii::app()->format->formatCurrency($totaldesember),
						Yii::app()->format->formatCurrency($totaljumlah),
			));
        
        
      $this->pdf->Output();
	}
	public function KomparasiPO($companyid,$plant,$supplier,$product,$sloc,$startdate,$enddate) {
		parent::actionDownload();
		$this->pdf->isrepeat = 1;
		$sql = "SELECT *
			FROM 
			(
			SELECT 
			h.plantcode,
			(
			SELECT zb.description
			FROM productplant za
			LEFT JOIN materialgroup zb ON zb.materialgroupid = za.materialgroupid
			WHERE za.productid = d.productid 
			AND za.addressbookid = c.addressbookid
			LIMIT 1
			) AS materialgroupdesc,
			d.productname,
			a.pono,
			c.fullname,
			e.paycode,
			a.podate,
			b.qty,
			i.uomcode,
			b.price,
			f.symbol,
			a.currencyrate
			FROM poheader a
			LEFT JOIN podetail b ON b.poheaderid = a.poheaderid 
			LEFT JOIN addressbook c ON c.addressbookid = a.addressbookid
			LEFT JOIN product d ON d.productid = b.productid
			LEFT JOIN paymentmethod e ON e.paymentmethodid = a.paymentmethodid
			LEFT JOIN currency f ON f.currencyid = a.currencyid
			LEFT JOIN sloc g ON g.slocid = b.slocid
			left join plant h on h.plantid = a.plantid 
			left join unitofmeasure i on i.unitofmeasureid = b.uomid 
			WHERE a.recordstatus = 4
			AND a.podate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
			AND h.plantcode like '%".$plant."%'
			AND c.fullname LIKE '%".$supplier."%'
			AND d.productname LIKE '%".$product."%'
			AND g.sloccode LIKE '%".$sloc."%'

			UNION 

			SELECT 
			g.plantcode,
			(
			SELECT zb.description
			FROM productplant za
			LEFT JOIN materialgroup zb ON zb.materialgroupid = za.materialgroupid
			WHERE za.productid = d.productid 
			AND za.addressbookid = c.addressbookid
			LIMIT 1
			) AS materialgroupdesc,
			d.productname,
			a.pono,
			c.fullname,
			e.paycode,
			a.podate,
			b.qty,
			h.uomcode,
			b.price,
			f.symbol,
			a.currencyrate
			FROM poheader a
			LEFT JOIN pojasa b ON b.poheaderid = a.poheaderid 
			LEFT JOIN addressbook c ON c.addressbookid = a.addressbookid
			LEFT JOIN product d ON d.productid = b.productid
			LEFT JOIN paymentmethod e ON e.paymentmethodid = a.paymentmethodid
			LEFT JOIN currency f ON f.currencyid = a.currencyid
			left join plant g on g.plantid = a.plantid 
			left join unitofmeasure h on h.unitofmeasureid = b.uomid 
			WHERE a.recordstatus >= 4
			AND a.podate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
			AND g.plantcode like '%".$plant."%' 
			AND c.fullname LIKE '%".$supplier."%'
			AND d.productname LIKE '%".$product."%'
			) zz
			ORDER BY zz.productname asc,zz.price,zz.podate,zz.pono";
		
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->companyid = $companyid;
		
		$this->pdf->title    = 'Laporan Komparasi PO';
		$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->text(10, $this->pdf->gety() + 10, 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
		$this->pdf->AddPage('L','A3');
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
			'C'
		);
		$this->pdf->setwidths(array(
			10,
			12,
			35,
			120,
			35,
			70,
			40,
			22,
			30,
			30,
		));
		$this->pdf->colheader = array(
			'No',
			'Plant',
			'Grup Artikel',
			'Nama Artikel',
			'NO PO',
			'Vendor',
			'Metode Pembayaran',
			'Tgl PO',
			'Qty PO',
			'Harga PO'
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
			'R',
			'R',
		);
		$i=1;
		foreach($dataReader as $row){
			$this->pdf->row(array(
				$i,
				$row['plantcode'],
				$row['materialgroupdesc'],
				$row['productname'],
				$row['pono'],
				$row['fullname'],
				$row['paycode'],
				date(Yii::app()->params['dateviewfromdb'], strtotime($row['podate'])),
				Yii::app()->format->formatCurrency($row['qty']).' '.$row['uomcode'],
				Yii::app()->format->formatCurrency($row['price'],$row['symbol']),
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
		if ($_GET['startdate'] == '') {
			GetMessage(true,'emptystartdate');
		} else 
		if ($_GET['enddate'] == '') {
			GetMessage(true,'emptyenddate');
		} else {			      
			switch ($_GET['lro']) {
				case 23 :
					$this->KomparasiPOXls($_GET['companyid'], $_GET['plant'], $_GET['supplier'], $_GET['product'],$_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
					break;				
				default:
					echo GetCatalog('reportdoesnotexist');
			}
		}
	}	
	public function KomparasiPOXls($companyid,$plant,$supplier,$product,$sloc,$startdate,$enddate) {
		$this->menuname='komparasipo';
    parent::actionDownxls();
		$sql = "SELECT *
			FROM 
			(
			SELECT 
			h.plantcode,
			(
			SELECT zb.description
			FROM productplant za
			LEFT JOIN materialgroup zb ON zb.materialgroupid = za.materialgroupid
			WHERE za.productid = d.productid 
			AND za.addressbookid = c.addressbookid
			LIMIT 1
			) AS materialgroupdesc,
			d.productname,
			a.pono,
			c.fullname,
			e.paycode,
			a.podate,
			b.qty,
			i.uomcode,
			b.price,
			f.symbol,
			a.currencyrate
			FROM poheader a
			LEFT JOIN podetail b ON b.poheaderid = a.poheaderid 
			LEFT JOIN addressbook c ON c.addressbookid = a.addressbookid
			LEFT JOIN product d ON d.productid = b.productid
			LEFT JOIN paymentmethod e ON e.paymentmethodid = a.paymentmethodid
			LEFT JOIN currency f ON f.currencyid = a.currencyid
			LEFT JOIN sloc g ON g.slocid = b.slocid
			left join plant h on h.plantid = a.plantid 
			left join unitofmeasure i on i.unitofmeasureid = b.uomid 
			WHERE a.recordstatus = 4
			AND a.podate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
			AND h.plantcode like '%".$plant."%'
			AND c.fullname LIKE '%".$supplier."%'
			AND d.productname LIKE '%".$product."%'
			AND g.sloccode LIKE '%".$sloc."%'

			UNION 

			SELECT 
			g.plantcode,
			(
			SELECT zb.description
			FROM productplant za
			LEFT JOIN materialgroup zb ON zb.materialgroupid = za.materialgroupid
			WHERE za.productid = d.productid 
			AND za.addressbookid = c.addressbookid
			LIMIT 1
			) AS materialgroupdesc,
			d.productname,
			a.pono,
			c.fullname,
			e.paycode,
			a.podate,
			b.qty,
			h.uomcode,
			b.price,
			f.symbol,
			a.currencyrate
			FROM poheader a
			LEFT JOIN pojasa b ON b.poheaderid = a.poheaderid 
			LEFT JOIN addressbook c ON c.addressbookid = a.addressbookid
			LEFT JOIN product d ON d.productid = b.productid
			LEFT JOIN paymentmethod e ON e.paymentmethodid = a.paymentmethodid
			LEFT JOIN currency f ON f.currencyid = a.currencyid
			left join plant g on g.plantid = a.plantid 
			left join unitofmeasure h on h.unitofmeasureid = b.uomid 
			WHERE a.recordstatus >= 4
			AND a.podate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
			AND g.plantcode like '%".$plant."%' 
			AND c.fullname LIKE '%".$supplier."%'
			AND d.productname LIKE '%".$product."%'
			) zz
			ORDER BY zz.productname asc,zz.price,zz.podate,zz.pono";
		
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();	
		$i=2;$ppid = 0;$proses=0;
		foreach($dataReader as $row){
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $i-1)
				->setCellValueByColumnAndRow(1, $i+1, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $i+1, $row['materialgroupdesc'])
				->setCellValueByColumnAndRow(3, $i+1, $row['productname'])
				->setCellValueByColumnAndRow(4, $i+1, $row['pono'])
				->setCellValueByColumnAndRow(5, $i+1, $row['fullname'])
				->setCellValueByColumnAndRow(6, $i+1, $row['paycode'])
				->setCellValueByColumnAndRow(7, $i+1, $row['podate'])
				->setCellValueByColumnAndRow(8, $i+1, $row['qty'])
				->setCellValueByColumnAndRow(9, $i+1, $row['uomcode'])
				->setCellValueByColumnAndRow(10, $i+1, $row['symbol'])
				->setCellValueByColumnAndRow(11, $i+1, $row['price'])
			;
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}