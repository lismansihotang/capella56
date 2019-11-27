<?php
class ReportinventoryController extends Controller {
  public $menuname = 'reportinventory';
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
				case 1 :
					$this->RincianHistoriBarang($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 2 :
					$this->RekapHistoriBarang($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 3 :
					$this->KartuStokBarang($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 5 :
					$this->RekapStokBarang($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 7 :
					$this->RekapStokBarangDenganRak($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 8 :
					$this->RincianSuratJalanPerDokumen($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 11 :
					$this->RincianReturJualPerDokumen($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 14 :
					$this->RincianTerimaBarangPerDokumen($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 17 :
					$this->RincianReturBeliPerDokumen($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 20 :
					$this->PendinganFpb($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 21 :
					$this->PendinganFpp($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 22 :
					$this->RincianTransferGudangKeluarPerDokumen($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 24 :
					$this->RincianTransferGudangMasukPerDokumen($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 25 :
					$this->RekapFPPPerDokumentBelumStatusMax($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 26 :
					$this->RekapStokBarangAdaTransaksi($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 27 :
					$this->RekapLPBPerDokumentBelumStatusMax($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 28 :
					$this->RekapReturBeliPerDokumentBelumStatusMax($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 29 :
					$this->RekapSuratJalanPerDokumentBelumStatusMax($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 30 :
					$this->RekapReturPenjualanPerDokumentBelumStatusMax($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 31 :
					$this->RekapTransferPerDokumentBelumStatusMax($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 32 :
					$this->RekapStockOpnamePerDokumentBelumStatusMax($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 34 :
					$this->RawMaterialGudangAsalBelumAdaDataGudangFPB($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 35 :
					$this->RawMaterialGudangTujuanBelumAdaDataGudangFPB($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 36 :
					$this->RekapFPBBelumTransferPerDokumen($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 37 :
					$this->RAWMaterialBelumAdaGudangStockOpname($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 38 :
					$this->LaporanFPBStatusBelumMax($_GET['companyid'],$_GET['plantid'], $_GET['sloc'], $_GET['storagebin'], $_GET['sales'], $_GET['product'], $_GET['salesarea'], $_GET['startdate'], $_GET['enddate']);
					break;
				case 39 :
					$this->LaporanKetersediaanBarang($_GET['companyid'],$_GET['plantid'],$_GET['sloc'],$_GET['storagebin'],$_GET['sales'],$_GET['product'],$_GET['salesarea'],$_GET['startdate'],$_GET['enddate']);
					break;
				case 40 :
					$this->RekonsiliasiFPPPOLPB($_GET['companyid'],$_GET['plantid'],$_GET['sloc'],$_GET['storagebin'],$_GET['sales'],$_GET['product'],$_GET['salesarea'],$_GET['prno'],$_GET['pono'],$_GET['startdate'],$_GET['enddate'], $_GET['addressbook']);
					break;
				default :
					echo getCatalog('reportdoesnotexist');
			}
    }
  }
	public function RincianHistoriBarang($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
    parent::actionDownload();
    $sql        = "select * from (select distinct a.productid,b.productname,b.productcode,c.description,d.slocid,d.plantid,d.sloccode,
			(select ifnull(sum(x.qty),0) 
				from productstockdet x
				where x.productid = a.productid 
				and x.slocid = d.slocid 
				and x.transdate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "') as awal 			
			from productplant a
			left join product b on b.productid=a.productid
			left join materialgroup c on c.materialgroupid=a.materialgroupid
			left join sloc d on d.slocid = a.slocid
			left join plant e on e.plantid = d.plantid
			left join company f on f.companyid = e.companyid
			left join productstockdet g on g.productid = a.productid and g.slocid = a.slocid
			where f.companyid = " . $companyid . " 
			and d.plantid = " . $plantid . " 
			and d.sloccode like '%" . $sloc . "%'
			and b.productname like '%" . $product . "%' 
			and g.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' 
			order by slocid asc,productname asc
			)z where awal > 0 or awal < 0";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Rincian Histori Barang';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('L');
    $this->pdf->sety($this->pdf->gety() + 5);
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
      39,
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
      'Dokumen',
      'Tanggal',
      'Saldo Awal',
      'Beli',
      'R.Jual',
      'Trf In',
      'Prod',
      'Jual',
      'R.Beli',
      'Trf Out',
      'Pemakaian',
      'Koreksi',
      'Saldo'
    );
    $this->pdf->RowHeader();
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', 'B', 10);
      $this->pdf->text(10, $this->pdf->gety() + 5, $row['productname']);
      $this->pdf->text(250, $this->pdf->gety() + 5, 'Gudang: '.$row['sloccode']);
      $this->pdf->SetFont('Arial', '', 10);
      $sql1        = "select *,(".$row['awal']."+beli+returjual+trfin+produksi+jual+returbeli+trfout+pemakaian+koreksi+konversiin+konversiout) as saldo
				from
					(select referenceno as dokumen,transdate as tanggal,slocid,
						case when (instr(referenceno,'GR') > 0) then qty else 0 end as beli,
						case when (instr(referenceno,'GIR') > 0) then qty else 0 end as returjual, 
						case when (instr(referenceno,'TFS') > 0) and (qty > 0) then qty else 0 end as trfin, 
						case when (instr(referenceno,'OP') > 0) and (qty > 0) then qty else 0 end as produksi,
						case when instr(referenceno,'SJ') > 0 then qty else 0 end as jual,
						case when instr(referenceno,'GRR') > 0 then qty else 0 end as returbeli,
						case when (instr(referenceno,'TFS') > 0) and (qty < 0) then qty else 0 end as trfout,
						case when (instr(referenceno,'OP') > 0) and (qty < 0) then qty else 0 end as pemakaian,
						case when instr(referenceno,'TSO') > 0 then qty else 0 end as koreksi,
						case when (instr(referenceno,'konversi') > 0) and (qty > 0) then qty else 0 end as konversiin,
						case when (instr(referenceno,'konversi') > 0) and (qty < 0) then qty else 0 end as konversiout
					from
					(select a.referenceno,a.transdate,a.qty,a.slocid
						from productstockdet a
						inner join sloc b on b.slocid = a.slocid
						inner join plant c on c.plantid = b.plantid
						inner join company d on d.companyid = c.companyid
						inner join product e on e.productid = a.productid
						where a.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' 
						and a.productid = '" . $row['productid'] . "'
						and c.plantid = ".$row['plantid']." 
						and b.slocid = ".$row['slocid']." 
						order by transdate desc
						)z )zz";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $awal        = 0;
      $beli        = 0;
      $r_jual      = 0;
      $trfin       = 0;
      $prod        = 0;
      $jual        = 0;
      $r_beli      = 0;
      $trfout      = 0;
      $pemakaian   = 0;
      $koreksi     = 0;
      $total       = 0;
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->coldetailalign = array(
        'L',
        'C',
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
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $this->pdf->row(array(
          $row1['dokumen'],
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['tanggal'])),
          '',
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['beli']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['returjual']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['trfin']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['produksi']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['jual']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['returbeli']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['trfout']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['pemakaian']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['koreksi'] + $row1['konversiin'] + $row1['konversiout'])
        ));
        $awal = $row['awal'];
        $beli += $row1['beli'];
        $r_jual += $row1['returjual'];
        $trfin += $row1['trfin'];
        $prod += $row1['produksi'];
        $jual += $row1['jual'];
        $r_beli += $row1['returbeli'];
        $trfout += $row1['trfout'];
        $pemakaian += $row1['pemakaian'];
        $koreksi += $row1['koreksi'] + $row1['konversiin'] + $row1['konversiout'];
        $total = $awal + $beli + $r_jual + $trfin + $prod + $jual + $r_beli + $trfout + $pemakaian + $koreksi;
      }
      $this->pdf->row(array(
        '',
        'Total',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $awal),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $beli),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $r_jual),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $trfin),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $prod),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $jual),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $r_beli),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $trfout),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $pemakaian),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $koreksi),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $total)
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
	public function RekapHistoriBarang($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
    parent::actionDownload();
    $sql        = "select a.slocid,a.sloccode
			from sloc a 
			where a.slocid in 
			(			
				select za.slocid 
				from productstockdet z
				join sloc za on za.slocid = z.slocid
				join plant zb on zb.plantid = za.plantid
				join company zc on zc.companyid = zb.companyid
				join product zd on zd.productid = z.productid 
				where zc.companyid = " . $companyid . " 
				and a.plantid = ".$plantid." 
				and za.sloccode like '%".$sloc."%' 
				and zd.productname like '%".$product."%'
				and z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
							and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
			) 
			order by a.slocid asc";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Rekap Histori Barang';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('L','A4');
    $this->pdf->sety($this->pdf->gety() + 6);
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
      50,
      15,
      15,
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
      'Nama Barang',
      'Satuan',
      'Awal',
      'Beli',
      'R.Jual',
      'Trf In',
      'Prod',
      'Jual',
      'R.Beli',
      'Trf Out',
      'Pemakaian',
      'Koreksi',
      'Saldo'
    );
    $this->pdf->RowHeader();
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', 'B', 10);
      $this->pdf->text(250, $this->pdf->gety() + 5, 'Gudang: '.$row['sloccode']);
      $this->pdf->SetFont('Arial', '', 10);
      $sql1        = "select *,(awal+beli+returjual+trfin+produksi+jual+returbeli+trfout+pemakaian+koreksi) as saldo
							from
							(select 
							(
							select distinct a.productname from product a
							where a.productid = t.productid
							) as barang,
							v.uomcode as satuan,
							(
							select ifnull(sum(aw.qty),0) 
							from productstockdet aw
							where aw.productid = t.productid and
							aw.transdate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and
							aw.slocid = t.slocid
							) as awal,
							(
							select ifnull(sum(c.qty),0) from productstockdet c
							where c.productid = t.productid and
							c.referenceno like 'GR%' and
							c.slocid = t.slocid and
							c.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
							and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
							) as beli,
							(
							select ifnull(sum(d.qty),0) from productstockdet d
							where d.productid = t.productid and
							d.referenceno like 'GIR%' and
							d.slocid = t.slocid and
							d.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
							and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
							) as returjual,
							(
							select ifnull(sum(e.qty),0) from productstockdet e
							where e.productid = t.productid and
							e.referenceno like 'TFS%' and
							e.qty > 0 and
							e.slocid = t.slocid and
							e.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
							and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
							) as trfin,
							(
							select ifnull(sum(f.qty),0) from productstockdet f
							where f.productid = t.productid and
							f.referenceno like 'OP%' and
							f.qty > 0 and
							f.slocid = t.slocid and
							f.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
							and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
							) as produksi,
							(
							select ifnull(sum(g.qty),0) from productstockdet g
							where g.productid = t.productid and
							g.referenceno like 'SJ%' and
							g.slocid = t.slocid and
							g.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
							and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
							) as jual,
							(
							select ifnull(sum(h.qty),0) from productstockdet h
							where h.productid = t.productid and
							h.referenceno like 'GRR%' and
							h.slocid = t.slocid and
							h.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
							and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
							) as returbeli,
							(
							select ifnull(sum(i.qty),0) from productstockdet i
							where i.productid = t.productid and
							i.referenceno like 'TFS%' and
							i.qty < 0 and
							i.slocid = t.slocid and
							i.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
							and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
							) as trfout,
							(
							select ifnull(sum(j.qty),0) from productstockdet j
							where j.productid = t.productid and
							j.referenceno like 'OP%' and
							j.qty < 0 and
							j.slocid = t.slocid and
							j.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
							and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
							) as pemakaian,
							(
							select ifnull(sum(k.qty),0) from productstockdet k
							where k.productid = t.productid and
							k.referenceno like 'TSO%' and
							k.slocid = t.slocid and
							k.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
							and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
							) as koreksi
							from productplant t
							join sloc u on u.slocid = t.slocid
							join unitofmeasure v on v.unitofmeasureid = t.uom1
              where t.slocid = '" . $row['slocid'] . "')z
							ORDER BY barang";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
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
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        50,
        15,
        15,
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
        'R'
      );
      $this->pdf->setFont('Arial', '', 7);
      foreach ($dataReader1 as $row1) {
				if (($row1['awal'] == 0) && ($row1['beli'] == 0) && ($row1['returjual'] == 0) && ($row1['trfin'] == 0) && ($row1['produksi'] == 0) 
					&& ($row1['jual'] == 0) && ($row1['returbeli'] == 0) && ($row1['trfout'] == 0) && ($row1['pemakaian'] == 0) && ($row1['koreksi'] == 0)
					&& ($row1['saldo'] == 0)) {
					} else {
					$this->pdf->row(array(
						$row1['barang'],
						$row1['satuan'],
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['awal']),
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['beli']),
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['returjual']),
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['trfin']),
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['produksi']),
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['jual']),
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['returbeli']),
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['trfout']),
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['pemakaian']),
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['koreksi']),
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['saldo'])
						));
				}
        $this->pdf->checkPageBreak(5);
      }
			$this->pdf->AddPage('L','A4');
			$this->pdf->RowHeader();
    }
    $this->pdf->Output();
  }
	public function KartuStokBarang($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
    parent::actionDownload();
    $sql        = "select a.slocid,a.sloccode
			from sloc a 
			where a.slocid in 
			(			
				select za.slocid 
				from productstockdet z
				join sloc za on za.slocid = z.slocid
				join plant zb on zb.plantid = za.plantid
				join company zc on zc.companyid = zb.companyid
				join product zd on zd.productid = z.productid 
				where zc.companyid = " . $companyid . " 
				and a.plantid = ".$plantid." 
				and za.sloccode like '%".$sloc."%' 
				and zd.productname like '%".$product."%'
				and z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
							and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
			) 
			order by a.slocid asc";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Kartu Stock Barang';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P','A4');
    $this->pdf->sety($this->pdf->gety() + 0);
    $this->pdf->setFont('Arial', 'B', 8);
    foreach ($dataReader as $row) {
			$this->pdf->colalign = array(
				'C',
				'C',
				'C',
				'C',
				'C',
				'C'
			);
			$this->pdf->setwidths(array(
				40,
				30,
				30,
				30,
				30,
				30
			));
			$this->pdf->colheader = array(
				'Dokumen',
				'Tanggal',
				'Saldo Awal',
				'Masuk',
				'Keluar',
				'Saldo Akhir'
			);
			$this->pdf->RowHeader();	
			$this->pdf->SetFont('Arial', 'B', 10);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'Gudang ');
      $this->pdf->text(40, $this->pdf->gety() + 5, ': ' . $row['sloccode']);
      $awal1       = 0;
      $masuk1      = 0;
      $keluar1     = 0;
      $saldo1      = 0;
      $sql1        = "select distinct productid,productname,materialgroupcode,uomcode,slocid,sloccode from
				(select productid,productname,materialgroupcode,uomcode,awal,dokumen,tanggal,slocid,sloccode,masuk,keluar,(awal+masuk+keluar) as saldo
				from
				(select productid,productname,materialgroupcode,uomcode,awal,dokumen,tanggal,slocid,sloccode,(beli+returjual+trfin+produksi+konversiin+opnamemasuk) as masuk,(jual+returbeli+trfout+pemakaian+konversiout+opnamekeluar) as keluar
				from
				(select productid,productname,materialgroupcode,uomcode,referenceno as dokumen, transdate as tanggal,slocid,sloccode,awal,
				case when instr(referenceno,'LPB') > 0 and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
				'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as beli,
				case when instr(referenceno,'GIR') > 0 and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
				'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as returjual,
				case when (instr(referenceno,'TFS') > 0) and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
				'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty > 0) then qty else 0 end as trfin,
				case when (instr(referenceno,'OP') > 0) and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
				'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty > 0) then qty else 0 end as produksi,
				case when (instr(referenceno,'konversi') > 0) and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
				'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty > 0) then qty else 0 end as konversiin,
				case when instr(referenceno,'SJ') > 0 and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
				'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as jual,
				case when instr(referenceno,'GRR') > 0 and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
				'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as returbeli,
				case when (instr(referenceno,'TFS') > 0) and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
				'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty < 0) then qty else 0 end as trfout,
				case when (instr(referenceno,'OP') > 0) and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
				'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty < 0) then qty else 0 end as pemakaian,
				case when (instr(referenceno,'konversi') > 0) and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
				'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty < 0) then qty else 0 end as konversiout,
				case when instr(referenceno,'TSO') > 0 and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
				'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as opnamemasuk,
				case when instr(referenceno,'TSO') < 0 and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
				'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as opnamekeluar
				from
				(select a.productid,g.productname,h.uomcode,a.referenceno,a.transdate,a.qty,b.slocid,b.sloccode,
				(
				select zzz.materialgroupcode
				from materialgroup zzz 
				where zzz.materialgroupid = e.materialgroupid
				) as materialgroupcode,
					(select ifnull(sum(x.qty),0) 
					from productstockdet x
					join sloc bx on bx.slocid = x.slocid
					join plant cx on cx.plantid = bx.plantid
													where cx.plantid = " . $plantid . " and x.productid = a.productid and x.slocid = a.slocid and
				x.transdate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "') as awal
				from productstockdet a
				join sloc b on b.slocid = a.slocid
				join plant c on c.plantid = b.plantid
				join company d on d.companyid = c.companyid
				left join productplant e on e.productid=a.productid and e.slocid=a.slocid and e.uom1=a.uomid
				left join storagebin f on f.storagebinid=a.storagebinid
				join product g on g.productid=a.productid
				join unitofmeasure h on h.unitofmeasureid = a.uomid 							
				where b.plantid = " . $plantid . " 
				and b.slocid = " . $row['slocid'] . " 
				and g.productname like '%" . $product . "%' 
				and a.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "
				') z) zz) zzz) zzzz
				order by productname,sloccode";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      foreach ($dataReader1 as $row1) {
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->text(10, $this->pdf->gety() + 10, $row1['productname'].' - '.$row1['uomcode']);
        $this->pdf->text(10, $this->pdf->gety() + 15, 'Grup Material : '.$row1['materialgroupcode']);
        $sql2        = "select awal,dokumen,tanggal,masuk,keluar,(awal+masuk+keluar) as saldo
                        from
                        (select awal,dokumen,tanggal,(beli+returjual+trfin+produksi+konversiin+opnamemasuk) as masuk,(jual+returbeli+trfout+pemakaian+konversiout+opnamekeluar) as keluar
                        from
                        (select referenceno as dokumen, transdate as tanggal,slocid,awal,
                        case when instr(referenceno,'LPB') > 0 and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
												'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as beli,
                        case when instr(referenceno,'GIR') > 0 and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
												'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as returjual,
                        case when (instr(referenceno,'TFS') > 0) and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
												'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty > 0) then qty else 0 end as trfin,
                        case when (instr(referenceno,'OP') > 0) and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
												'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty > 0) then qty else 0 end as produksi,
												case when (instr(referenceno,'konversi') > 0) and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
												'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty > 0) then qty else 0 end as konversiin,
                        case when instr(referenceno,'SJ') > 0 and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
												'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as jual,
                        case when instr(referenceno,'GRR') > 0 and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
												'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as returbeli,
                        case when (instr(referenceno,'TFS') > 0) and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
												'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty < 0) then qty else 0 end as trfout,
                        case when (instr(referenceno,'OP') > 0) and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
												'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty < 0) then qty else 0 end as pemakaian,
												case when (instr(referenceno,'konversi') > 0) and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
												'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') and (qty < 0) then qty else 0 end as konversiout,
                       case when instr(referenceno,'TSO') > 0 and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
												'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as opnamemasuk,
												case when instr(referenceno,'TSO') < 0 and (z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
												'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') then qty else 0 end as opnamekeluar
                        from
                        (select a.referenceno,a.transdate,a.qty,a.slocid,
													(select ifnull(sum(x.qty),0) 
													from productstockdet x
													join sloc bx on bx.slocid = x.slocid
                        join plant cx on cx.plantid = bx.plantid
													where cx.plantid = " . $plantid . " and x.productid = '" . $row1['productid'] . "' and x.slocid = '" . $row1['slocid'] . "' and
                        x.transdate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "') as awal
                        from productstockdet a
                        join sloc b on b.slocid = a.slocid
                        join plant c on c.plantid = b.plantid
                        join company d on d.companyid = c.companyid
                        where a.productid = '" . $row1['productid'] . "' and a.slocid = '" . $row1['slocid'] . "' and
												a.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and 
												'" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z) zz) zzz order by tanggal";
        $command2    = $this->connection->createCommand($sql2);
        $dataReader2 = $command2->queryAll();
        $awal        = 0;
        $masuk       = 0;
        $keluar      = 0;
        $saldo       = 0;
        $this->pdf->sety($this->pdf->gety() + 17);
         
        $this->pdf->setFont('Arial', '', 8);
        foreach ($dataReader2 as $row2) {
          $this->pdf->row(array(
            $row2['dokumen'],
            date(Yii::app()->params['dateviewfromdb'], strtotime($row2['tanggal'])),
            '',
            Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row2['masuk']),
            Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row2['keluar']),
            ''
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
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $awal),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $masuk),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $keluar),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $saldo)
        ));
        $awal1 += $awal;
        $masuk1 += $masuk;
        $keluar1 += $keluar;
        $saldo1 += $saldo;
        $this->pdf->checkPageBreak(10);
      }
			$this->pdf->AddPage('P','A4');
    }
    $this->pdf->Output();
  }
	public function RekapStokBarang($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
    parent::actionDownload();
    $awal2      = 0;
    $masuk2     = 0;
    $keluar2    = 0;
    $akhir2     = 0;
    $sql        = "select distinct c.sloccode,c.slocid
			from sloc c 
			join plant d on d.plantid = c.plantid
			join company e on e.companyid = d.companyid
			where c.plantid = " . $plantid . " 
				and c.sloccode like '%" . $sloc . "%' 
				and c.slocid in 
				(			
					select za.slocid 
					from productstockdet z
					join sloc za on za.slocid = z.slocid
					join plant zb on zb.plantid = za.plantid
					join company zc on zc.companyid = zb.companyid
					join product zd on zd.productid = z.productid 
					where zc.companyid = " . $companyid . " 
					and zb.plantid = ".$plantid." 
					and za.sloccode like '%".$sloc."%' 
					and zd.productname like '%".$product."%'
					and z.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
				)
			order by c.slocid asc ";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Rekap Stock Barang';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
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
      80,
      20,
      20,
      20,
      20,
      20
    ));
    $this->pdf->colheader = array(
      'Nama Barang',
      'Satuan',
      'Awal',
      'Masuk',
      'Keluar',
      'Akhir'
    );
    $this->pdf->RowHeader();
    foreach ($dataReader as $row) {
      $awal1   = 0;
      $masuk1  = 0;
      $keluar1 = 0;
      $akhir1  = 0;
      $this->pdf->SetFont('Arial', 'B', 10);
      $this->pdf->text(10, $this->pdf->gety() + 7, 'GUDANG');
      $this->pdf->text(28, $this->pdf->gety() + 7, ': ' . $row['sloccode']);
      $sql1        = "select distinct a.description as divisi,a.materialgroupid
                    from materialgroup a
                    join productplant b on b.materialgroupid=a.materialgroupid
										 join sloc c on c.slocid = b.slocid
										 join plant d on d.plantid = c.plantid
										 join company e on e.companyid = d.companyid
										 join product f on f.productid = b.productid
                    where d.plantid = " . $plantid . " and c.sloccode like '%" . $sloc . "%' and c.slocid = '" . $row['slocid'] . "' and 
										f.productname like '%" . $product . "%' and f.productid in
                    (select z.productid 
                    from productstockdet z
                    join sloc za on za.slocid = z.slocid
                    join plant zb on zb.plantid = za.plantid
                    join company zc on zc.companyid = zb.companyid
                    where zb.plantid = " . $plantid . " and z.slocid = c.slocid and z.uomid = b.uom1)";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 5);
      foreach ($dataReader1 as $row1) {
        $awal   = 0;
        $masuk  = 0;
        $keluar = 0;
        $akhir  = 0;
        $this->pdf->SetFont('Arial', 'BI', 9);
        $this->pdf->text(15, $this->pdf->gety() + 7, 'MATERIAL GROUP');
        $this->pdf->text(45, $this->pdf->gety() + 7, ': ' . $row1['divisi']);
        $sql2        = "select distinct b.productid
                    from materialgroup a
                    join productplant b on b.materialgroupid = a.materialgroupid
                    join sloc d on d.slocid = b.slocid
                    join plant e on e.plantid = d.plantid
                    join company f on f.companyid = e.companyid
                    join product g on g.productid = b.productid
                    where e.plantid = '" . $plantid . "' and d.sloccode like '%" . $sloc . "%' and a.materialgroupid = '" . $row1['materialgroupid'] . "' and 
										g.productname like '%" . $product . "%' and b.productid in
                    (select z.productid 
                    from productstockdet z
                    join sloc za on za.slocid = z.slocid
                    join plant zb on zb.plantid = za.plantid
                    join company zc on zc.companyid = zb.companyid
                    where zb.plantid = " . $plantid . " and z.slocid = b.slocid and z.uomid = b.uom1)";
										
        $command2    = $this->connection->createCommand($sql2);
        $dataReader2 = $command2->queryAll();
        $this->pdf->sety($this->pdf->gety() + 8);
        foreach ($dataReader2 as $row2) {
          $sql3 = "select * from
														(select barang,satuan,awal,masuk,keluar,(awal+masuk+keluar) as akhir
                            from
                            (select barang,satuan,awal,(beli+returjual+trfin+produksi+koreksiin+trs) as masuk,(abs(jual)+abs(returbeli)+abs(trfout)+abs(pemakaian)+abs(koreksiout))*-1 as keluar
                            from
                            (select 
                            (
                            select distinct a.productname 
                            from productstockdet a
                            where a.productid = t.productid and
                            a.uomid = t.uom1
														limit 1
                            ) as barang,
                            (
                            select distinct b.uomcode 
                            from productstockdet b
                            where b.productid = t.productid and
                            b.uomid = t.uom1
														limit 1
                            ) as satuan,
                            (
                            select ifnull(sum(aw.qty),0) 
                            from productstockdet aw
                            where aw.productid = t.productid and
                            aw.transdate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and
                            aw.slocid = t.slocid
                            ) as awal,
                            (
                            select ifnull(sum(c.qty),0) 
                            from productstockdet c
                            where c.productid = t.productid and
                            c.referenceno like 'GR%' and
                            c.slocid = t.slocid and
                            c.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as beli,
                            (
                            select ifnull(sum(d.qty),0) 
                            from productstockdet d
                            where d.productid = t.productid and
                            d.referenceno like 'GIR%' and
                            d.slocid = t.slocid and
                            d.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as returjual,
                            (
                            select ifnull(sum(e.qty),0) 
                            from productstockdet e
                            where e.productid = t.productid and
                            e.referenceno like 'TFS%' and
                            e.qty > 0 and
                            e.slocid = t.slocid and
                            e.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as trfin,
														(
														select ifnull(sum(ee.qty),0) 
                            from productstockdet ee
                            where ee.productid = t.productid and
                            ee.referenceno like 'TRS%' and
                            ee.qty > 0 and
                            ee.slocid = t.slocid and
                            ee.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as trs,
                            (
                            select ifnull(sum(f.qty),0) 
                            from productstockdet f
                            where f.productid = t.productid and
                            f.referenceno like 'OP%' and
                            f.qty > 0 and
                            f.slocid = t.slocid and
                            f.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as produksi,
                            (
                            select ifnull(sum(g.qty),0) 
                            from productstockdet g
                            where g.productid = t.productid and
                            g.referenceno like 'SJ%' and
                            g.slocid = t.slocid and
                            g.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as jual,
                            (
                            select ifnull(sum(h.qty),0) 
                            from productstockdet h
                            where h.productid = t.productid and
                            h.referenceno like 'GRR%' and
                            h.slocid = t.slocid and
                            h.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as returbeli,
                            (
                            select ifnull(sum(i.qty),0) 
                            from productstockdet i
                            where i.productid = t.productid and
                            i.referenceno like 'TFS%' and
                            i.qty < 0 and
                            i.slocid = t.slocid and
                            i.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as trfout,
                            (
                            select ifnull(sum(j.qty),0) 
                            from productstockdet j
                            where j.productid = t.productid and
                            j.referenceno like 'OP%' and
                            j.qty < 0 and
                            j.slocid = t.slocid and
                            j.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as pemakaian,
                            (
                            select ifnull(sum(k.qty),0) 
                            from productstockdet k
                            where k.productid = t.productid and
                            k.referenceno like 'TSO%' and
                            k.slocid = t.slocid and
														k.qty < 0 and
                            k.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as koreksiout,
														(
                            select ifnull(sum(kb.qty),0) 
                            from productstockdet kb
                            where kb.productid = t.productid and
                            kb.referenceno like 'TSO%' and
														kb.qty > 0 and
                            kb.slocid = t.slocid and
                            kb.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as koreksiin
                            from productplant t
							join materialgroup u on u.materialgroupid = t.materialgroupid
							join sloc v on v.slocid = t.slocid
              where t.productid = '" . $row2['productid'] . "' and u.materialgroupid = '" . $row1['materialgroupid'] . "' 
							and v.slocid = '" . $row['slocid'] . "' order by barang) z) zz )zzz 
							where awal <> 0 or masuk <> 0 or keluar <> 0 or akhir <> 0 order by barang asc";
          $this->pdf->sety($this->pdf->gety());
          $this->pdf->coldetailalign = array(
            'L',
            'C',
            'R',
            'R',
            'R',
            'R'
          );
          $this->pdf->setFont('Arial', '', 8);
          $command3    = $this->connection->createCommand($sql3);
          $dataReader3 = $command3->queryAll();
          foreach ($dataReader3 as $row3) {
            $this->pdf->row(array(
              $row3['barang'],
              $row3['satuan'],
              Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row3['awal']),
              Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row3['masuk']),
              Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row3['keluar']),
              Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row3['akhir'])
            ));
            $awal += $row3['awal'];
            $masuk += $row3['masuk'];
            $keluar += $row3['keluar'];
            $akhir += $row3['akhir'];
          }
        }
        $this->pdf->SetFont('Arial', 'BI', 8);
        $this->pdf->row(array(
          'JUMLAH ' . $row1['divisi'],
          '',
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $awal),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $masuk),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $keluar),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $akhir)
        ));
        $awal1 += $awal;
        $masuk1 += $masuk;
        $keluar1 += $keluar;
        $akhir1 += $akhir;
      }
      $this->pdf->row(array(
        'TOTAL ' . $row['sloccode'],
        '',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $awal1),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $masuk1),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $keluar1),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $akhir1)
      ));
      $awal2 += $awal1;
      $masuk2 += $masuk1;
      $keluar2 += $keluar1;
      $akhir2 += $akhir1;
    }
    $this->pdf->Output();
  }
  public function RekapStokBarangDenganRak($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
    parent::actionDownload();
    $sql        = "select distinct c.sloccode,c.slocid
			from materialgroup a
			join productplant b on b.materialgroupid=a.materialgroupid
			join sloc c on c.slocid = b.slocid
			join plant d on d.plantid = c.plantid
			join company e on e.companyid = d.companyid
			join product f on f.productid = b.productid
			where e.companyid = " . $companyid . " and d.plantid = ".$plantid." and c.sloccode like '%" . $sloc . "%' and 
				f.productname like '%" . $product . "%' and f.productid in
				(select z.productid 
				from productstockdet z
				join sloc za on za.slocid = z.slocid
				join plant zb on zb.plantid = za.plantid
				join company zc on zc.companyid = zb.companyid
				join storagebin zd on zd.storagebinid=z.storagebinid
				where zc.companyid = " . $companyid . " and d.plantid = ".$plantid." and zd.description like '%" . $storagebin . "%' 
				and z.slocid = c.slocid and z.uomid = b.uom1)";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Rekap Stock Barang Dengan Rak';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
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
      60,
      20,
      20,
      20,
      20,
      20,
      20
    ));
    $this->pdf->colheader = array(
      'Nama Barang',
      'Satuan',
      'Rak',
      'Awal',
      'Masuk',
      'Keluar',
      'Akhir'
    );
    $this->pdf->RowHeader();
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', 'B', 10);
      $this->pdf->text(10, $this->pdf->gety() + 7, 'GUDANG');
      $this->pdf->text(28, $this->pdf->gety() + 7, ': ' . $row['sloccode']);
      $sql1        = "select distinct a.description as divisi,a.materialgroupid
                    from materialgroup a
                    join productplant b on b.materialgroupid=a.materialgroupid
					join sloc c on c.slocid = b.slocid
					join plant d on d.plantid = c.plantid
					join company e on e.companyid = d.companyid
					join product f on f.productid = b.productid
                    where e.companyid = " . $companyid . " and d.plantid = ".$plantid." and c.slocid = '" . $row['slocid'] . "' and 
					f.productname like '%" . $product . "%' and f.productid in
                    (select z.productid 
                    from productstockdet z
                    join sloc za on za.slocid = z.slocid
                    join plant zb on zb.plantid = za.plantid
                    join company zc on zc.companyid = zb.companyid
										join storagebin zd on zd.storagebinid=z.storagebinid
                    where zc.companyid = " . $companyid . " and zb.plantid = ".$plantid." and zd.description like '%" . $storagebin . "%' and z.slocid = c.slocid and z.uomid = b.uom1)";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 5);
      foreach ($dataReader1 as $row1) {
        $this->pdf->SetFont('Arial', 'BI', 9);
        $this->pdf->text(15, $this->pdf->gety() + 7, 'MATERIAL GROUP');
        $this->pdf->text(45, $this->pdf->gety() + 7, ': ' . $row1['divisi']);
        $this->pdf->text(80, $this->pdf->gety() + 7, '');
        $this->pdf->text(165, $this->pdf->gety() + 7, '' . $row['sloccode']);
        $sql2        = "select b.productid,a.materialgroupid,a.description as divisi,d.slocid
                    from materialgroup a
                    join productplant b on b.materialgroupid = a.materialgroupid
                    join sloc d on d.slocid = b.slocid
                    join plant e on e.plantid = d.plantid
                    join company f on f.companyid = e.companyid
                    join product g on g.productid = b.productid
                    where f.companyid = '" . $companyid . "' and d.plantid = ".$plantid." and d.slocid = '" . $row['slocid'] . "' and a.materialgroupid = '" . $row1['materialgroupid'] . "' and 
					g.productname like '%" . $product . "%' and b.productid in
                    (select z.productid 
                    from productstockdet z
                    join sloc za on za.slocid = z.slocid
                    join plant zb on zb.plantid = za.plantid
                    join company zc on zc.companyid = zb.companyid
										join storagebin zd on zd.storagebinid=z.storagebinid
                    where zc.companyid = " . $companyid . " and zb.plantid = ".$plantid." and zd.description like '%" . $storagebin . "%' and z.slocid = b.slocid and z.uomid = b.uom1)";
        $command2    = $this->connection->createCommand($sql2);
        $dataReader2 = $command2->queryAll();
        $totalawal   = 0;
        $totalmasuk  = 0;
        $totalkeluar = 0;
        $totalakhir  = 0;
        $this->pdf->sety($this->pdf->gety() + 8);
        foreach ($dataReader2 as $row2) {
          $sql3 = "select * from 
							(select barang,productid,satuan,unitofmeasureid,rak,storagebinid,awal,masuk,keluar,(awal+masuk+keluar) as akhir
                            from
                            (select barang,productid,satuan,unitofmeasureid,rak,storagebinid,awal,(beli+returjual+trfin+produksi+konversiin) as masuk,(jual+returbeli+trfout+pemakaian+konversiout+koreksi) as keluar
                            from
                            (select 
                            s.productname as barang,s.productid,b.uomcode as satuan,b.unitofmeasureid,n.description as rak,n.storagebinid,
                            (
                            select ifnull(sum(aw.qty),0) 
                            from productstockdet aw
                            where aw.productid = t.productid and
                            aw.storagebinid = t.storagebinid and
                            aw.transdate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and
                            aw.slocid = t.slocid
                            ) as awal,
                            (
                            select ifnull(sum(c.qty),0) 
                            from productstockdet c
                            where c.productid = t.productid and
                            c.referenceno like 'GR%' and
                            c.slocid = t.slocid and
                            c.storagebinid = t.storagebinid and
                            c.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as beli,
                            (
                            select ifnull(sum(d.qty),0) 
                            from productstockdet d
                            where d.productid = t.productid and
                            d.referenceno like 'GIR%' and
                            d.slocid = t.slocid and
                            d.storagebinid = t.storagebinid and
                            d.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as returjual,
                            (
                            select ifnull(sum(e.qty),0) 
                            from productstockdet e
                            where e.productid = t.productid and
                            e.referenceno like 'TFS%' and
                            e.qty > 0 and
                            e.slocid = t.slocid and
                            e.storagebinid = t.storagebinid and
                            e.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as trfin,
                            (
                            select ifnull(sum(f.qty),0) 
                            from productstockdet f
                            where f.productid = t.productid and
                            f.referenceno like 'OP%' and
                            f.qty > 0 and
                            f.slocid = t.slocid and
                            f.storagebinid = t.storagebinid and
                            f.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as produksi,
                            (
                            select ifnull(sum(f.qty),0) 
                            from productstockdet f
                            where f.productid = t.productid and
                            f.referenceno like '%konversi%' and
                            f.qty > 0 and
                            f.slocid = t.slocid and
                            f.storagebinid = t.storagebinid and
                            f.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as konversiin,
                            (
                            select ifnull(sum(g.qty),0) 
                            from productstockdet g
                            where g.productid = t.productid and
                            g.referenceno like 'SJ%' and
                            g.slocid = t.slocid and
                            g.storagebinid = t.storagebinid and
                            g.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as jual,
                            (
                            select ifnull(sum(h.qty),0) 
                            from productstockdet h
                            where h.productid = t.productid and
                            h.referenceno like 'GRR%' and
                            h.slocid = t.slocid and
                            h.storagebinid = t.storagebinid and
                            h.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as returbeli,
                            (
                            select ifnull(sum(i.qty),0) 
                            from productstockdet i
                            where i.productid = t.productid and
                            i.referenceno like 'TFS%' and
                            i.qty < 0 and
                            i.slocid = t.slocid and
                            i.storagebinid = t.storagebinid and
                            i.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as trfout,
                            (
                            select ifnull(sum(j.qty),0) 
                            from productstockdet j
                            where j.productid = t.productid and
                            j.referenceno like 'OP%' and
                            j.qty < 0 and
                            j.slocid = t.slocid and
                            j.storagebinid = t.storagebinid and
                            j.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as pemakaian,
                            (
                            select ifnull(sum(j.qty),0) 
                            from productstockdet j
                            where j.productid = t.productid and
                            j.referenceno like '%konversi%' and
                            j.qty < 0 and
                            j.slocid = t.slocid and
                            j.storagebinid = t.storagebinid and
                            j.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as konversiout,
                            (
                            select ifnull(sum(k.qty),0) 
                            from productstockdet k
                            where k.productid = t.productid and
                            k.referenceno like 'TSO%' and
                            k.slocid = t.slocid and
                            k.storagebinid = t.storagebinid and
                            k.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as koreksi
                            from productstock t
							join productplant r on r.productid=t.productid and r.uom1=t.uomid and t.slocid=r.slocid
							join materialgroup u on u.materialgroupid = r.materialgroupid
							join sloc v on v.slocid = t.slocid
							join product s on s.productid=t.productid
							join unitofmeasure b on b.unitofmeasureid=t.uomid
                     join storagebin n on n.storagebinid=t.storagebinid
							where t.productid = '" . $row2['productid'] . "' and u.materialgroupid = '" . $row1['materialgroupid'] . "' 
							and n.description like '%" . $storagebin . "%' and v.slocid = '" . $row['slocid'] . "') z) zz )zzz where awal <> 0 or masuk <> 0 or keluar <> 0 or akhir <> 0 order by barang asc";
          $this->pdf->sety($this->pdf->gety());
          $this->pdf->coldetailalign = array(
            'L',
            'C',
            'C',
            'R',
            'R',
            'R',
            'R'
          );
          $this->pdf->setFont('Arial', '', 8);
          $command3    = $this->connection->createCommand($sql3);
          $dataReader3 = $command3->queryAll();
          foreach ($dataReader3 as $row3) {
            $this->pdf->setFont('Arial', '', 8);
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
              60,
              20,
              20,
              20,
              20,
              20,
              20
            ));
            $this->pdf->row(array(
              $row3['barang'],
              $row3['satuan'],
              $row3['rak'],
              Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row3['awal']),
              Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row3['masuk']),
              Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row3['keluar']),
              Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row3['akhir'])
            ));
            $totalawal += $row3['awal'];
            $totalmasuk += $row3['masuk'];
            $totalkeluar += $row3['keluar'];
            $totalakhir += $row3['akhir'];
          }
        }
        $this->pdf->setwidths(array(
          10,
          15,
          80,
          20,
          20,
          20,
          20
        ));
        $this->pdf->colalign = array(
          'L',
          'R',
          'R',
          'R',
          'R',
          'R',
          'R'
        );
        $this->pdf->setFont('Arial', 'B', 9);
        $this->pdf->row(array(
          '',
          '',
          'TOTAL ' . $row['sloccode'] . ' - ' . $row1['divisi'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalawal),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalmasuk),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalkeluar),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalakhir)
        ));
      }
    }
    $this->pdf->Output();
  }
	public function RincianSuratJalanPerDokumen($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
    parent::actionDownload();
    $sql        = "select distinct b.giheaderid,b.gino,b.gidate,a.sono,c.fullname as customer,j.fullname as sales,e.areaname 
			from giheader b 
			left join soheader a on a.soheaderid = b.soheaderid
			left join addressbook c on c.addressbookid=a.addressbookid
			left join sales d on d.salesid=a.salesid
			left join salesarea e on e.salesareaid=c.salesareaid
			left join gidetail f on f.giheaderid=b.giheaderid
			left join sloc h on h.slocid = f.slocid
			left join plant i on i.plantid = b.plantid
			left join employee j on j.employeeid = d.employeeid 
			where b.gino is not null 
				and i.companyid = " . $companyid . " 
				and i.plantid = " . $plantid . " 
				and b.recordstatus = 3 
				and h.sloccode like '%" . $sloc . "%' 
				and j.fullname like '%" . $sales . "%'  
				and e.areaname like '%" . $salesarea . "%'  
				and b.gidate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
				and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
				and f.productid in (select x.productid 
					from productplant x join product xx on xx.productid = x.productid 
					where xx.productname like '%" . $product . "%'  
					and x.slocid = f.slocid)";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Rincian Surat Jalan Per Dokumen (Qty)';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 2);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 8);
      $this->pdf->text(10, $this->pdf->gety() + 0, 'No ');
      $this->pdf->text(25, $this->pdf->gety() + 0, ': ' . $row['gino']);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'Sales ');
      $this->pdf->text(25, $this->pdf->gety() + 5, ': ' . $row['sales']);
      $this->pdf->text(120, $this->pdf->gety() + 0, $row['areaname'] . ', ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['gidate'])));
      $this->pdf->text(10, $this->pdf->gety() + 10, 'No. SO ');
      $this->pdf->text(25, $this->pdf->gety() + 10, ': ' . $row['sono']);
      $this->pdf->text(120, $this->pdf->gety() + 5, 'Kepada Yth, ');
      $this->pdf->text(120, $this->pdf->gety() + 10, $row['customer']);
      $sql1        = "select c.productname, a.qty,d.uomcode,b.itemnote,e.headernote,c.productid
                        from gidetail a 
                        join sodetail b on b.sodetailid = a.sodetailid
                        join product c on c.productid = a.productid
                        join unitofmeasure d on d.unitofmeasureid = a.uomid
                        join giheader e on e.giheaderid = a.giheaderid
                        where a.giheaderid = " . $row['giheaderid'];
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
        60
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
        'L',
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
	public function RincianReturJualPerDokumen($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
    parent::actionDownload();
    $sql        = "select distinct zd.gireturid,zd.gireturno,zg.fullname as customer,c.fullname as sales,zd.gireturdate,zi.areaname,zb.gino 
                    from giretur zd
                    left join giheader zb on zb.giheaderid = zd.giheaderid 
                    left join gireturdetail ze on ze.gireturid = zd.gireturid
                    left join soheader za on za.soheaderid = zb.soheaderid
                    left join product zf on zf.productid = ze.productid
										left join addressbook zg on zg.addressbookid = za.addressbookid
                    left join sales zh on zh.salesid = za.salesid
                    left join salesarea zi on zi.salesareaid = zg.salesareaid
                    left join sloc zj on zj.slocid = ze.slocid
                    left join productplant a on a.productid = ze.productid
                    left join materialgroup b on b.materialgroupid = a.materialgroupid
                    left join employee c on c.employeeid = zh.employeeid 
                    left join plant d on d.plantid = zd.plantid 
                    where zd.recordstatus = 3 
										and d.companyid = " . $companyid . " 
										and c.fullname like '%" . $sales . "%' 
										and zf.productname like '%" . $product . "%' 
										and zj.sloccode like '%" . $sloc . "%'
										and zi.areaname like '%" . $salesarea . "%' 
										and zd.gireturdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
										and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Rincian Retur Jual Per Dokumen (Qty)';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 2);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 0, 'No ');
      $this->pdf->text(25, $this->pdf->gety() + 0, ': ' . $row['gireturno']);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'Sales ');
      $this->pdf->text(25, $this->pdf->gety() + 5, ': ' . $row['sales']);
      $this->pdf->text(140, $this->pdf->gety() + 0, $row['areaname'] . ', ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['gireturdate'])));
      $this->pdf->text(10, $this->pdf->gety() + 10, 'No. SJ ');
      $this->pdf->text(25, $this->pdf->gety() + 10, ': ' . $row['gino']);
      $this->pdf->text(140, $this->pdf->gety() + 5, 'Customer: ');
      $this->pdf->text(140, $this->pdf->gety() + 10, $row['customer']);
      $sql1        = "select distinct ze.productname,zk.uomcode,za.itemnote,zb.headernote,
                        (
                        select sum(zzb.qty)
                        from gireturdetail zzb 
                        join giretur zzc on zzc.gireturid = zzb.gireturid
                        where zzb.productid = za.productid
                        and zzb.slocid = za.slocid
                        and zzc.recordstatus = 3 and zzc.gireturid = zb.gireturid
                        ) as qty
                        from gireturdetail za 
                        join giretur zb on zb.gireturid = za.gireturid
                        join giheader zc on zc.giheaderid = zb.giheaderid
                        join soheader zd on zd.soheaderid = zc.soheaderid
                        join product ze on ze.productid = za.productid
												join addressbook zf on zf.addressbookid = zd.addressbookid
                        join sales zg on zg.salesid = zd.salesid
                        join salesarea zh on zh.salesareaid = zf.salesareaid
                        join productplant zi on zi.productid = za.productid
                        join sloc zj on zj.slocid = za.slocid
                        join unitofmeasure zk on zk.unitofmeasureid = za.uomid 
												join plant a on a.plantid = zb.plantid 
												join employee b on b.employeeid = zg.employeeid
                        where zb.recordstatus = 3 
												and a.companyid = " . $companyid . " 
												and za.gireturid = " . $row['gireturid'] . "
                        and b.fullname like '%" . $sales . "%' 
												and zh.areaname like '%" . $salesarea . "%'  
												and ze.productname like '%" . $product . "%' 
												and zj.sloccode like '%" . $sloc . "%'   
												and zb.gireturdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
												and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' order by productname";
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
        60
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
          $row1['itemnote']
        ));
        $totalqty += $row1['qty'];
      }
      $this->pdf->row(array(
        '',
        'Total',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        '',
        ''
      ));
      $this->pdf->checkPageBreak(20);
      $this->pdf->sety($this->pdf->gety() + 10);
    }
    $this->pdf->Output();
  }
	public function RincianTerimaBarangPerDokumen($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
    parent::actionDownload();
    $sql        = "select distinct b.grheaderid,b.grno,c.fullname as supplier,b.grdate,a.pono
			from grheader b 
			left join poheader a on a.poheaderid = b.poheaderid
			left join addressbook c on c.addressbookid=a.addressbookid
			left join grdetail e on e.grheaderid = b.grheaderid
			left join sloc f on f.slocid = e.slocid
			left join product g on g.productid = e.productid
			left join plant h on h.plantid = b.plantid 
			where b.recordstatus = 3 
			and f.sloccode like '%" . $sloc . "%' 
			and b.grno is not null 
			and h.companyid = " . $companyid . " 
			and g.productname like '%" . $product . "%'
			and b.grdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
			and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' order by grno";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Rincian LPB Per Dokumen';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(20, $this->pdf->gety() + 10, 'Dokumen');
      $this->pdf->text(40, $this->pdf->gety() + 10, ': ' . $row['grno']);
      $this->pdf->text(20, $this->pdf->gety() + 15, 'Supplier');
      $this->pdf->text(40, $this->pdf->gety() + 15, ': ' . $row['supplier']);
      $this->pdf->text(130, $this->pdf->gety() + 10, 'Tanggal');
      $this->pdf->text(160, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])));
      $this->pdf->text(130, $this->pdf->gety() + 15, 'Nomor PO');
      $this->pdf->text(160, $this->pdf->gety() + 15, ': ' . $row['pono']);
      $sql1        = "select zd.productid,zd.productname,zf.uomcode,za.itemnote,zb.headernote,za.qty
                        from grdetail za
                        join grheader zb on zb.grheaderid = za.grheaderid
                        join poheader zc on zc.poheaderid = zb.poheaderid
                        join product zd on zd.productid = za.productid
                        join sloc ze on ze.slocid = za.slocid
                        join unitofmeasure zf on zf.unitofmeasureid = za.uomid
                        where zb.recordstatus = 3 and zd.productname like '%" . $product . "%'
                        and ze.sloccode like '%" . $sloc . "%' and za.grheaderid = " . $row['grheaderid'] . " ";
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
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        75,
        20,
        20,
        60
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
        'L',
        'R',
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
        'Total',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        ''
      ));
      $this->pdf->row(array(
        '',
        'Keterangan : ',
        '',
        '',
        ''
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
	public function RincianReturBeliPerDokumen($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
    parent::actionDownload();
    $sql        = "select a.grreturid,a.grreturno,c.fullname as supplier,a.grreturdate,d.slocid
                    from grretur a
                    left join poheader b on b.poheaderid=a.poheaderid
                    left join addressbook c on c.addressbookid=b.addressbookid
                    left join grreturdetail d on d.grreturid = a.grreturid
										left join sloc e on e.slocid = d.slocid
										left join plant f on f.plantid = a.plantid
                    where a.grreturno is not null 
										and e.sloccode like '%" . $sloc . "%' 
										and f.companyid = " . $companyid . " 
										and a.grreturdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' 
										and d.productid in
										(select x.productid from productplant x join product xx on xx.productid = x.productid 
										where xx.productname like '%" . $product . "%')";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Rincian Retur Pembelian Per Dokumen';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(20, $this->pdf->gety() + 10, 'Dokumen');
      $this->pdf->text(40, $this->pdf->gety() + 10, ': ' . $row['grreturno']);
      $this->pdf->text(20, $this->pdf->gety() + 15, 'Supplier');
      $this->pdf->text(40, $this->pdf->gety() + 15, ': ' . $row['supplier']);
      $this->pdf->text(130, $this->pdf->gety() + 10, 'Tanggal');
      $this->pdf->text(160, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['grreturdate'])));
      $sql1        = "select d.productname,e.uomcode,a.qty,a.itemnote,f.headernote 
														from grreturdetail a
														inner join grdetail b on b.grdetailid=a.grdetailid
														inner join podetail c on c.podetailid=a.podetailid
														inner join product d on d.productid=a.productid
														inner join unitofmeasure e on e.unitofmeasureid=a.uomid
														inner join grretur f on f.grreturid=a.grreturid
														where a.slocid = '" . $row['slocid'] . "' and a.grreturid = " . $row['grreturid'];
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
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        75,
        20,
        20,
        60
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Satuan',
        'Qty',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'L',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          $row1['uomcode'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty']),
          $row1['itemnote']
        ));
        $totalqty += $row1['qty'];
      }
      $this->pdf->row(array(
        '',
        'Keterangan : ' . $row1['headernote'],
        '',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        ''
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
	public function PendinganFpb($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
    parent::actionDownload();
    $sql        = "select distinct a.formrequestid,a.formrequestno,a.formrequestdate as tanggal,b.description,b.sloccode,
					a.description as note
					from formrequest a
					left join sloc b on b.slocid = a.slocfromid
					left join productplan c on c.productplanid = a.productplanid 
					join formrequestraw f on f.formrequestid = a.formrequestid
					join plant g on g.plantid = b.plantid
					join company h on h.companyid = g.companyid
					where a.recordstatus = 3 and h.companyid = " . $companyid . " and b.sloccode like '%" . $sloc . "%' and
					a.formrequestdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
					and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' and 
					f.productid in 
					(
					select za.productid
					from formrequestraw za
					join product zb on zb.productid = za.productid
					where za.formrequestid = a.formrequestid and 
					zb.productname like '%" . $product . "%' and
					za.tsqty < za.qty
					)";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Pendingan FPB';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 2);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 2, 'No ');
      $this->pdf->text(30, $this->pdf->gety() + 2, ': ' . $row['formrequestno']);
      $this->pdf->text(10, $this->pdf->gety() + 6, 'Tgl ');
      $this->pdf->text(30, $this->pdf->gety() + 6, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['tanggal'])));
      $this->pdf->text(110, $this->pdf->gety() + 2, 'Gudang / Departemen ');
      $this->pdf->text(160, $this->pdf->gety() + 2, ': ' . $row['sloccode']);
      $sql1         = "select b.productname, a.qty,a.tsqty,a.prqty,
						c.uomcode,a.description,(a.qty-a.tsqty) as selisih
                        from formrequestraw a 
                        join product b on b.productid = a.productid
                        join unitofmeasure c on c.unitofmeasureid = a.uomid
                        where b.productname like '%" . $product . "%' and a.formrequestid = " . $row['formrequestid'];
      $command1     = $this->connection->createCommand($sql1);
      $dataReader1  = $command1->queryAll();
      $i            = 0;
      $totalqty     = 0;
      $totaltrf     = 0;
      $totalpr      = 0;
      $totalpo      = 0;
      $totalgr      = 0;
      $totalselisih = 0;
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
        80,
        20,
        20,
        20,
        20,
        15
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Trf Qty',
        'Pr Qty',
        'Selisih',
        'Note'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'R',
        'C'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['tsqty']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['prqty']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['selisih']),
          $row1['description']
        ));
        $totalqty += $row1['qty'];
        $totaltrf += $row1['tsqty'];
        $totalpr += $row1['prqty'];
        $totalselisih += $row1['selisih'];
      }
      $this->pdf->row(array(
        '',
        '',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totaltrf),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalpr),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalselisih)
      ));
      $this->pdf->row(array(
        '',
        'Keterangan : ' . $row['note'],
        '',
        '',
        ''
      ));
      $this->pdf->checkPageBreak(20);
      $this->pdf->sety($this->pdf->gety() + 10);
    }
    $this->pdf->Output();
  }
	public function PendinganFpp($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
    parent::actionDownload();
    $sql        = "select distinct f.description,h.companyid,a.prno,a.prdate,a.description as note,a.prheaderid,a.description,b.formrequestno
											from prheader a
											left join formrequest b on b.formrequestid = a.formrequestid
											left join prraw c on c.prheaderid = a.prheaderid
											left join sloc f on f.slocid = b.slocfromid
											left join product g on g.productid = c.productid
											left join plant h on h.plantid = f.plantid
											where a.recordstatus = 4 
											and c.poqty < c.qty 
											and h.companyid = " . $companyid . " 
											and f.sloccode like '%" . $sloc . "%' 
											and g.productname like '%" . $product . "%'
											and a.prdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
											and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Pendingan FPP';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 2);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 2, 'No ');
      $this->pdf->text(30, $this->pdf->gety() + 2, ': ' . $row['prno']);
      $this->pdf->text(10, $this->pdf->gety() + 6, 'Tgl ');
      $this->pdf->text(30, $this->pdf->gety() + 6, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['prdate'])));
      $this->pdf->text(120, $this->pdf->gety() + 2, 'Gudang ');
      $this->pdf->text(140, $this->pdf->gety() + 2, ': ' . $row['description']);
      $this->pdf->text(120, $this->pdf->gety() + 6, 'No FPB ');
      $this->pdf->text(140, $this->pdf->gety() + 6, ': ' . $row['formrequestno']);
      $sql1         = "select d.productname, a.qty,a.poqty,
										(select sum(xx.qty) from formrequestraw xx
										where xx.formrequestrawid = a.formrequestrawid and xx.productid = a.productid) as daqty,
										e.uomcode,a.description,(a.qty-a.poqty) as selisih
                    from prraw a
                    join prheader b on b.prheaderid = a.prheaderid
                    join formrequest c on c.formrequestid = b.formrequestid
                    join product d on d.productid = a.productid
                    join unitofmeasure e on e.unitofmeasureid = a.unitofmeasureid
                    join sloc f on f.slocid = c.slocid
                    where d.productname like '%" . $product . "%' and a.prheaderid = " . $row['prheaderid'] . "
                    and f.sloccode like '%" . $sloc . "%' and a.poqty < a.qty
                    and b.prdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
										and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'";
      $command1     = $this->connection->createCommand($sql1);
      $dataReader1  = $command1->queryAll();
      $i            = 0;
      $totalqty     = 0;
      $totaltrf     = 0;
      $totalda      = 0;
      $totalpo      = 0;
      $totalgr      = 0;
      $totalselisih = 0;
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
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        50,
        20,
        20,
        20,
        20,
        15
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Fr Qty',
        'Po Qty',
        'Selisih',
        'Note'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'R',
        'C'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['daqty']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['poqty']),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['selisih']),
          $row1['description']
        ));
        $totalqty += $row1['qty'];
        $totalda += $row1['daqty'];
        $totalpo += $row1['poqty'];
        $totalselisih += $row1['selisih'];
      }
      $this->pdf->row(array(
        '',
        '',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalda),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalpo),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalselisih)
      ));
      $this->pdf->row(array(
        '',
        'Keterangan : ' . $row['note'],
        '',
        '',
        ''
      ));
      $this->pdf->checkPageBreak(20);
      $this->pdf->sety($this->pdf->gety() + 10);
    }
    $this->pdf->Output();
  }
	public function RincianTransferGudangKeluarPerDokumen($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
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
					where b.transstockno is not null and b.transstocktypeid = 0 and d.plantid = " . $plantid . " and b.recordstatus > (3-1) and 
					c.sloccode like '%" . $sloc . "%' and
					a.formrequestdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
					and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
					and e.productid in (select x.productid 
					from productplant x join product xx on xx.productid = x.productid 
					where xx.productname like '%" . $product . "%')";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Rincian Transfer Gudang Keluar Per Dokumen (Qty)';
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
	public function RincianTransferGudangMasukPerDokumen($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
    parent::actionDownload();
    $sql        = "select distinct b.transstockid,b.transstockno,					
											(select concat(z.sloccode,' - ',z.description) from sloc z where z.slocid = b.slocfromid) as fromsloc,
											(select concat(zz.sloccode,' - ',zz.description) from sloc zz where zz.slocid = b.sloctoid) as tosloc,
											b.transstockdate,a.formrequestno
											from formrequest a
											join transstock b on b.formrequestid=a.formrequestid
											join sloc c on c.slocid = b.sloctoid
											join plant d on d.plantid = c.plantid
											join transstockdet e on e.transstockid = b.transstockid
											where b.transstockno is not null and d.plantid = " . $plantid . " and b.recordstatus = 5 and 
											c.sloccode like '%" . $sloc . "%' and
											b.transstockdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
											and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
											and e.productid in (select x.productid 
											from productplant x join product xx on xx.productid = x.productid 
											where xx.productname like '%" . $product . "%')";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Rincian Transfer Gudang Masuk Per Dokumen (Qty)';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 2);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'No. TS ');
      $this->pdf->text(25, $this->pdf->gety() + 5, ': ' . $row['transstockno']);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'No. FPB ');
      $this->pdf->text(25, $this->pdf->gety() + 10, ': ' . $row['formrequestno']);
      $this->pdf->text(100, $this->pdf->gety() + 5, 'Gudang Asal ');
      $this->pdf->text(130, $this->pdf->gety() + 5, ': ' . $row['fromsloc']);
      $this->pdf->text(100, $this->pdf->gety() + 10, 'Gudang Tujuan ');
      $this->pdf->text(130, $this->pdf->gety() + 10, ': ' . $row['tosloc']);
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
	public function RekapStokBarangAdaTransaksi($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
    parent::actionDownload();
    $awal2      = 0;
    $masuk2     = 0;
    $keluar2    = 0;
    $akhir2     = 0;
    $sql        = "select distinct c.sloccode,c.slocid
                    from materialgroup a
                    join productplant b on b.materialgroupid=a.materialgroupid
				 join sloc c on c.slocid = b.slocid
				 join plant d on d.plantid = c.plantid
				 join company e on e.companyid = d.companyid
				 join product f on f.productid = b.productid
                    where d.plantid = ".$plantid." and e.companyid = " . $companyid . " and c.sloccode like '%" . $sloc . "%' and 
					f.productname like '%" . $product . "%' and f.productid in
                    (select z.productid 
                    from productstockdet z
                    join sloc za on za.slocid = z.slocid
                    join plant zb on zb.plantid = za.plantid
                    join company zc on zc.companyid = zb.companyid
                    where zb.plantid = " . $plantid . " and z.slocid = c.slocid and z.uomid = b.uom1)";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Rekap Stock Barang - Ada Transaksi Keluar Masuk';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
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
      80,
      20,
      20,
      20,
      20,
      20
    ));
    $this->pdf->colheader = array(
      'Nama Barang',
      'Satuan',
      'Awal',
      'Masuk',
      'Keluar',
      'Akhir'
    );
    $this->pdf->RowHeader();
    foreach ($dataReader as $row) {
      $awal1   = 0;
      $masuk1  = 0;
      $keluar1 = 0;
      $akhir1  = 0;
      $this->pdf->SetFont('Arial', 'B', 10);
      $this->pdf->text(10, $this->pdf->gety() + 7, 'GUDANG');
      $this->pdf->text(28, $this->pdf->gety() + 7, ': ' . $row['sloccode']);
      $sql1        = "select distinct a.description as divisi,a.materialgroupid
                    from materialgroup a
                    join productplant b on b.materialgroupid=a.materialgroupid
				 join sloc c on c.slocid = b.slocid
				 join plant d on d.plantid = c.plantid
				 join company e on e.companyid = d.companyid
				 join product f on f.productid = b.productid
                    where d.plantid = " . $plantid . " and c.sloccode like '%" . $sloc . "%' and c.slocid = '" . $row['slocid'] . "' and 
					f.productname like '%" . $product . "%' and f.productid in
                    (select z.productid 
                    from productstockdet z
                    join sloc za on za.slocid = z.slocid
                    join plant zb on zb.plantid = za.plantid
                    join company zc on zc.companyid = zb.companyid
                    where zb.plantid = " . $plantid . " and z.slocid = c.slocid and z.uomid = b.uom1)";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 5);
      foreach ($dataReader1 as $row1) {
        $awal   = 0;
        $masuk  = 0;
        $keluar = 0;
        $akhir  = 0;
        $this->pdf->SetFont('Arial', 'BI', 9);
        $this->pdf->text(15, $this->pdf->gety() + 7, 'MATERIAL GROUP');
        $this->pdf->text(45, $this->pdf->gety() + 7, ': ' . $row1['divisi']);
        $this->pdf->text(80, $this->pdf->gety() + 7, '');
        $this->pdf->text(165, $this->pdf->gety() + 7, '' . $row['sloccode']);
        $sql2        = "select distinct b.productid
                    from materialgroup a
                    join productplant b on b.materialgroupid = a.materialgroupid
                    join sloc d on d.slocid = b.slocid
                    join plant e on e.plantid = d.plantid
                    join company f on f.companyid = e.companyid
                    join product g on g.productid = b.productid
                    where e.plantid = '" . $plantid . "' and d.sloccode like '%" . $sloc . "%' and a.materialgroupid = '" . $row1['materialgroupid'] . "' and 
					g.productname like '%" . $product . "%' and b.productid in
                    (select z.productid 
                    from productstockdet z
                    join sloc za on za.slocid = z.slocid
                    join plant zb on zb.plantid = za.plantid
                    join company zc on zc.companyid = zb.companyid
                    where zb.plantid = " . $plantid . " and z.slocid = b.slocid and z.uomid = b.uom1)";
        $command2    = $this->connection->createCommand($sql2);
        $dataReader2 = $command2->queryAll();
        $this->pdf->sety($this->pdf->gety() + 8);
        foreach ($dataReader2 as $row2) {
          $sql3 = "select * from
														(select barang,satuan,awal,masuk,keluar,(awal+masuk-keluar) as akhir
                            from
                            (select barang,satuan,awal,(beli+returjual+trfin+produksi+koreksiin+trs) as masuk,(abs(jual)+abs(returbeli)+abs(trfout)+abs(pemakaian)+abs(koreksiout)) as keluar
                            from
                            (select 
                            (
                            select distinct a.productname 
                            from productstockdet a
                            where a.productid = t.productid and
                            a.uomid = t.uom1
														limit 1
                            ) as barang,
                            (
                            select distinct b.uomcode 
                            from productstockdet b
                            where b.productid = t.productid and
                            b.uomid = t.uom1
														limit 1
                            ) as satuan,
                            (
                            select ifnull(sum(aw.qty),0) 
                            from productstockdet aw
                            where aw.productid = t.productid and
                            aw.transdate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and
                            aw.slocid = t.slocid
                            ) as awal,
                            (
                            select ifnull(sum(c.qty),0) 
                            from productstockdet c
                            where c.productid = t.productid and
                            c.referenceno like 'GR%' and
                            c.slocid = t.slocid and
                            c.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as beli,
                            (
                            select ifnull(sum(d.qty),0) 
                            from productstockdet d
                            where d.productid = t.productid and
                            d.referenceno like 'GIR%' and
                            d.slocid = t.slocid and
                            d.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as returjual,
                            (
                            select ifnull(sum(e.qty),0) 
                            from productstockdet e
                            where e.productid = t.productid and
                            e.referenceno like 'TFS%' and
                            e.qty > 0 and
                            e.slocid = t.slocid and
                            e.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as trfin,
														(
														select ifnull(sum(ee.qty),0) 
                            from productstockdet ee
                            where ee.productid = t.productid and
                            ee.referenceno like 'TRS%' and
                            ee.qty > 0 and
                            ee.slocid = t.slocid and
                            ee.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as trs,
                            (
                            select ifnull(sum(f.qty),0) 
                            from productstockdet f
                            where f.productid = t.productid and
                            f.referenceno like 'OP%' and
                            f.qty > 0 and
                            f.slocid = t.slocid and
                            f.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as produksi,
                            (
                            select ifnull(sum(g.qty),0) 
                            from productstockdet g
                            where g.productid = t.productid and
                            g.referenceno like 'SJ%' and
                            g.slocid = t.slocid and
                            g.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as jual,
                            (
                            select ifnull(sum(h.qty),0) 
                            from productstockdet h
                            where h.productid = t.productid and
                            h.referenceno like 'GRR%' and
                            h.slocid = t.slocid and
                            h.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as returbeli,
                            (
                            select ifnull(sum(i.qty),0) 
                            from productstockdet i
                            where i.productid = t.productid and
                            i.referenceno like 'TFS%' and
                            i.qty < 0 and
                            i.slocid = t.slocid and
                            i.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as trfout,
                            (
                            select ifnull(sum(j.qty),0) 
                            from productstockdet j
                            where j.productid = t.productid and
                            j.referenceno like 'OP%' and
                            j.qty < 0 and
                            j.slocid = t.slocid and
                            j.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as pemakaian,
                            (
                            select ifnull(sum(k.qty),0) 
                            from productstockdet k
                            where k.productid = t.productid and
                            k.referenceno like 'TSO%' and
                            k.slocid = t.slocid and
														k.qty < 0 and
                            k.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as koreksiout,
														(
                            select ifnull(sum(kb.qty),0) 
                            from productstockdet kb
                            where kb.productid = t.productid and
                            kb.referenceno like 'TSO%' and
														kb.qty > 0 and
                            kb.slocid = t.slocid and
                            kb.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as koreksiin
                            from productplant t
							join materialgroup u on u.materialgroupid = t.materialgroupid
							join sloc v on v.slocid = t.slocid
              where t.productid = '" . $row2['productid'] . "' and u.materialgroupid = '" . $row1['materialgroupid'] . "' 
							and v.slocid = '" . $row['slocid'] . "' order by barang) z) zz )zzz 
							where awal <> 0 or masuk <> 0 or keluar <> 0 or akhir <> 0 order by barang asc";
          $this->pdf->sety($this->pdf->gety());
          $this->pdf->coldetailalign = array(
            'L',
            'C',
            'R',
            'R',
            'R',
            'R'
          );
          $this->pdf->setFont('Arial', '', 8);
          $command3    = $this->connection->createCommand($sql3);
          $dataReader3 = $command3->queryAll();
          foreach ($dataReader3 as $row3) {
            $this->pdf->row(array(
              $row3['barang'],
              $row3['satuan'],
              Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row3['awal']),
              Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row3['masuk']),
              Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row3['keluar']),
              Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row3['akhir'])
            ));
            $awal += $row3['awal'];
            $masuk += $row3['masuk'];
            $keluar += $row3['keluar'];
            $akhir += $row3['akhir'];
          }
        }
        $this->pdf->SetFont('Arial', 'BI', 8);
        $this->pdf->row(array(
          'JUMLAH ' . $row1['divisi'],
          '',
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $awal),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $masuk),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $keluar),
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $akhir)
        ));
        $awal1 += $awal;
        $masuk1 += $masuk;
        $keluar1 += $keluar;
        $akhir1 += $akhir;
      }
      $this->pdf->row(array(
        'TOTAL ' . $row['sloccode'],
        '',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $awal1),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $masuk1),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $keluar1),
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $akhir1)
      ));
      $awal2 += $awal1;
      $masuk2 += $masuk1;
      $keluar2 += $keluar1;
      $akhir2 += $akhir1;
    }
    $this->pdf->sety($this->pdf->gety() + 5);
    $this->pdf->SetFont('Arial', 'BI', 9);
    $this->pdf->row(array(
      'GRAND TOTAL',
      '',
      Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $awal2),
      Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $masuk2),
      Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $keluar2),
      Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $akhir2)
    ));
    $this->pdf->Output();
  }
	public function RekapLPBPerDokumentBelumStatusMax($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
    parent::actionDownload();
    $sql        = "select distinct a.grheaderid,a.grno,a.grdate,b.pono,b.headernote,a.recordstatus
						from grheader a
						left join poheader b on b.poheaderid = a.poheaderid
						left join podetail c on c.poheaderid = b.poheaderid
						left join product d on d.productid = c.productid
						left join sloc e on e.slocid = c.slocid
						left join plant f on f.plantid = a.plantid
						where a.recordstatus between 1 and (3-1)
						and e.sloccode like '%" . $sloc . "%' 
						and f.companyid =  " . $companyid . "
						and f.plantid =  " . $plantid . "
						order by a.grdate,a.recordstatus,a.grno
						";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Rekap LPB Per Dokumen Status Belum Max';
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
      'L'
    );
    $this->pdf->setwidths(array(
      10,
      20,
      25,
      25,
      25,
      50,
      40,
      25
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
      'C',
      'L'
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
        $row['grheaderid'],
        $row['grno'],
        date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])),
        $row['pono'],
        $row['headernote'],
        findstatusname("appgr", $row['recordstatus'])
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
	public function RekapFPPPerDokumentBelumStatusMax($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
    parent::actionDownload();
    $sql        = "SELECT a.prno,a.prheaderid,a.prdate,a.description,a.recordstatus,statusname
			FROM prheader a 
			left join plant b on b.plantid = a.plantid 
			WHERE a.recordstatus BETWEEN 1 AND 2
						and b.companyid =  " . $companyid . "
						and b.plantid =  " . $plantid . "
						order by a.prdate,a.prheaderid
						";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Rekap FPP Per Dokumen Status Belum Max';
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
      'L'
    );
    $this->pdf->setwidths(array(
      10,
      20,
      25,
      25,
      25,
      50,
      40,
      25
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
      'C',
      'L'
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
        $row['prheaderid'],
        $row['prno'],
        date(Yii::app()->params['dateviewfromdb'], strtotime($row['prdate'])),
				'',
        $row['description'],
        $row['statusname']
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
	public function RekapReturBeliPerDokumentBelumStatusMax($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
    parent::actionDownload();
    $sql        = "select distinct a.grreturid,a.grreturno,a.grreturdate,b.pono,b.headernote,a.recordstatus
							from grretur a
							left join poheader b on b.poheaderid = a.poheaderid
							left join grreturdetail c on c.grreturid = a.grreturid
							left join product d on d.productid = c.productid
							left join poheader e on e.poheaderid = a.poheaderid
							left join sloc f on f.slocid = c.slocid
							left join plant g on g.plantid = a.plantid
							where a.recordstatus between 1 and 2
							and a.grreturid is not null
							and g.companyid = " . $companyid . " 
							and g.plantid = " . $plantid . " 
							and d.productname like '%" . $product . "%' 
							and f.sloccode like '%" . $sloc . "%'
							order by a.grreturdate,b.recordstatus,a.grreturno";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Rekap Retur Pembelian Per Dokumen Status Belum Max';
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
      'L'
    );
    $this->pdf->setwidths(array(
      10,
      20,
      25,
      25,
      25,
      50,
      40,
      25
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
      'C',
      'L'
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
        $row['grreturid'],
        $row['grreturno'],
        date(Yii::app()->params['dateviewfromdb'], strtotime($row['grreturdate'])),
        $row['pono'],
        $row['headernote'],
        findstatusname("appgrretur", $row['recordstatus'])
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
	public function RekapSuratJalanPerDokumentBelumStatusMax($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
    parent::actionDownload();
    $sql        = "select distinct a.giheaderid,a.gino,a.gidate,b.sono,a.headernote,a.recordstatus
								from giheader a
								left join soheader b on b.soheaderid = a.soheaderid
								left join plant f on f.plantid = a.plantid
								where a.recordstatus between 1 and 2
								and f.companyid = " . $companyid . " 
								and f.plantid = " . $plantid . " 
								order by a.gidate,a.recordstatus,a.gino";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Rekap Surat Jalan Per Dokumen Status Belum Max';
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
      'L',
      'L'
    );
    $this->pdf->setwidths(array(
      10,
      20,
      25,
      25,
      25,
      60,
      25,
      25
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
      'L'
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
        $row['giheaderid'],
        $row['gino'],
        date(Yii::app()->params['dateviewfromdb'], strtotime($row['gidate'])),
        $row['sono'],
        $row['headernote'],
        findstatusname("appgi", $row['recordstatus'])
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
	public function RekapReturPenjualanPerDokumentBelumStatusMax($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
    parent::actionDownload();
    $sql        = "select distinct a.gireturid,a.gireturno,a.gireturdate,b.gino,a.headernote,a.recordstatus
					 from giretur a 
					 left join giheader b on b.giheaderid = a.giheaderid
					 left join gidetail c on c.giheaderid = a.giheaderid
					 left join product d on d.productid = c.productid
					 left join sloc e on e.slocid = c.slocid
					 left join plant f on f.plantid=e.plantid
					 where a.recordstatus between 1 and 2
					 and d.productname like '%%" . $product . "%%'
					 and e.sloccode like '%%" . $sloc . "%%'
					 and f.companyid = " . $companyid . "
					 and f.plantid = " . $plantid . "
					 order by a.gireturdate,a.recordstatus,a.gireturno";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Rekap Retur Penjualan Per Dokumen Status Belum Max';
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
      'L',
      'L'
    );
    $this->pdf->setwidths(array(
      10,
      20,
      25,
      25,
      25,
      60,
      25,
      25
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
      'L'
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
        $row['gireturid'],
        $row['gireturno'],
        date(Yii::app()->params['dateviewfromdb'], strtotime($row['gireturdate'])),
        $row['gino'],
        $row['headernote'],
        findstatusname("appgiretur", $row['recordstatus'])
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
	public function RekapTransferPerDokumentBelumStatusMax($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
    parent::actionDownload();
    $sql        = "select distinct a.transstockid,a.transstockno,a.transstockdate,b.formrequestno,a.headernote,a.recordstatus,
								e.sloccode as slocfrom,f.sloccode as slocto
							from transstock a
							left join formrequest b on b.formrequestid = a.formrequestid
							left join transstockdet c on c.transstockid = a.transstockid
							left join product d on d.productid = c.productid
							left join sloc e on e.slocid = a.slocfromid							
							left join sloc f on f.slocid = a.sloctoid
							left join plant g on g.plantid = e.plantid
							where g.plantid = " . $plantid . "
							and a.recordstatus between 1 and 4
							and b.formrequestno is not null
							and d.productname like '%" . $product . "%'
							and (e.sloccode like '%" . $sloc . "%' or f.sloccode like '%" . $sloc . "%')
							order by a.transstockdate,a.recordstatus,a.transstockno";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Rekap Transfer Per Dokumen Status Belum Max';
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
      15,
      20,
      18,
      20,
      20,
      20,
      35,
      38
    ));
    $this->pdf->colheader = array(
      'No',
      'ID',
      'No Transaksi',
      'Tanggal',
      'No Referensi',
      'Asal',
      'Tujuan',
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
      'C',
      'C',
      'L',
      'L'
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
        $row['transstockid'],
        $row['transstockno'],
        date(Yii::app()->params['dateviewfromdb'], strtotime($row['transstockdate'])),
        $row['formrequestno'],
        $row['slocfrom'],
        $row['slocto'],
        $row['headernote'],
        findstatusname("appts", $row['recordstatus'])
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
	public function RekapStockOpnamePerDokumentBelumStatusMax($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
    parent::actionDownload();
    $sql        = "select distinct a.bsheaderid,a.bsdate,a.bsheaderno,d.sloccode,a.headernote,a.recordstatus
								from bsheader a
								left join bsdetail b on b.bsheaderid = a.bsheaderid
								left join product c on c.productid = b.productid
								left join sloc d on d.slocid = a.slocid
								left join plant e on e.plantid = d.plantid
								where a.recordstatus between 1 and 3
								and c.productname like '%" . $product . "%'
								and d.sloccode like '%" . $sloc . "%'
								and e.companyid = " . $companyid . "
								and e.plantid = " . $plantid . "
								order by a.recordstatus";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Rekap Stock Opname Per Dokumen Status Belum Max';
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
      'L',
      'L'
    );
    $this->pdf->setwidths(array(
      10,
      20,
      25,
      20,
      20,
      22,
      50,
      25
    ));
    $this->pdf->colheader = array(
      'No',
      'ID Transaksi',
      'No Transaksi',
      'Tanggal',
      'No Referensi',
      'Gudang',
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
      'C',
      'L',
      'L'
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
        $row['bsheaderid'],
        $row['bsheaderno'],
        date(Yii::app()->params['dateviewfromdb'], strtotime($row['bsdate'])),
        '-',
        $row['sloccode'],
        $row['headernote'],
        findstatusname("appbs", $row['recordstatus'])
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
	public function LaporanFPBStatusBelumMax($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $startdate, $enddate) {
    parent::actionDownload();
    $sql = "SELECT 
            IF(jenis='SPP',(SELECT productplanid FROM formrequest a WHERE a.formrequestid = zz.formrequestid),
            IF(jenis='SO',(SELECT distinct zb.soheaderid FROM formrequest za join productplan zb on zb.productplanid = za.productplanid WHERE za.formrequestid = zz.formrequestid),'')) as id_jenis, 
						formrequestid, jenis, formrequestdate, formrequestno, statusname, username, headernote
            FROM (SELECT IF((y.productplanid is not null) or (y.productplanid<>''),'SPP',IF((bb.soheaderid is not null) or (bb.soheaderid<>''),'SO','')) as jenis, formrequestid, b.username, y.formrequestdate, y.formrequestno, y.statusname, y.recordstatus, y.description as headernote, y.slocfromid
                FROM formrequest y
                JOIN useraccess b ON b.useraccessid = y.useraccessid
								left join productplan bb on bb.productplanid = y.productplanid) zz
                WHERE zz.recordstatus<3 
								AND zz.recordstatus <> 0
                AND slocfromid IN (
                SELECT xa.slocid
                FROM sloc xa
                JOIN plant xb ON xb.plantid = xa.plantid
                JOIN company xc ON xc.companyid = xb.companyid
                WHERE xb.plantid = ".$plantid." AND xa.slocid = zz.slocfromid)
            ORDER BY formrequestid DESC";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $this->pdf->companyid = $companyid;
    $this->pdf->title    = 'Laporan FPB Status Belum Max';
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
      'L',
      'L',
      'L'
    );
    $this->pdf->setwidths(array(
      10,
      25,
      25,
      25,
      15,
      30,
      35,
      30
    ));
    $this->pdf->colheader = array(
      'No',
      'No FPB',
      'Tanggal Request',
      'User Request',
      'Jenis ',
      'ID Jenis',
      'Keterangan',
      'Status'
    );
    $this->pdf->RowHeader();
    $this->pdf->coldetailalign = array(
      'C',
      'L',
      'L',
      'L',
      'C',
      'L',
      'L',
      'L'
    );
      $i=0;
    foreach ($dataReader as $row) {
      $i += 1;
      $this->pdf->setFont('Arial', '', 10);
      $this->pdf->row(array(
        $i,
        $row['formrequestno'],
        date(Yii::app()->params['dateviewfromdb'], strtotime($row['formrequestdate'])),
        $row['username'],
        $row['jenis'],
        $row['id_jenis'],
        $row['headernote'],
        $row['statusname']
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
	public function RekonsiliasiFPPPOLPB($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $prno, $pono,$startdate, $enddate, $addressbook) {
		parent::actionDownload();
		$this->pdf->isrepeat = 1;
		$sql = "
		SELECT n.plantcode,a.prno,a.prdate,d.pono,d.podate,g.grno,g.grdate,e.productname,b.qty AS qtyfpp,c.qty AS qtypo,
			f.qty AS qtygr,a.statusname,h.uomcode,i.uomcode as uom2code,j.uomcode as uom3code,k.uomcode as uom4code,l.fullname as supplier
			FROM prheader a
			left JOIN prraw b ON b.prheaderid = a.prheaderid
			LEFT JOIN podetail c ON c.prrawid = b.prrawid AND c.productid = b.productid
			LEFT JOIN poheader d ON d.poheaderid = c.poheaderid
			LEFT JOIN product e ON e.productid = b.productid
			LEFT JOIN grdetail f ON f.podetailid = c.podetailid AND f.productid = c.productid
			LEFT JOIN grheader g ON g.grheaderid = f.grheaderid
			left join unitofmeasure h on h.unitofmeasureid = b.uomid 
			left join unitofmeasure i on i.unitofmeasureid = b.uom2id
			left join unitofmeasure j on j.unitofmeasureid = b.uom3id
			left join unitofmeasure k on k.unitofmeasureid = b.uom4id
			left join addressbook l on l.addressbookid = d.addressbookid 
			left join sloc m on m.slocid = a.slocfromid 
			left join plant n on n.plantid = m.plantid 
			WHERE a.prdate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
			and a.plantid = ".$plantid.
			(($addressbook != '')? " and l.fullname = '".$addressbook."'":'').
			(($sloc != '')? " and coalesce(m.sloccode,'') = '".$sloc."'":'').
			(($product != '')? " and coalesce(e.productname,'') = '".$product."'":'').
			(($prno != '')? " and coalesce(a.prno,'') like '%".$prno."%'":'').
			(($pono != '')? " and coalesce(d.pono,'') like '%".$pono."%'":'');
		$sql1 = "
			SELECT k.plantcode,a.prno,a.prdate,d.pono,d.podate,g.grno,g.grdate,e.productname,b.qty AS qtyfpp,c.qty AS qtypo,
			f.qty AS qtygr,a.statusname,h.uomcode,'','','',i.fullname as supplier
			FROM prheader a
			left JOIN prjasa b ON b.prheaderid = a.prheaderid
			LEFT JOIN pojasa c ON c.prjasaid = b.prjasaid AND c.productid = b.productid
			LEFT JOIN poheader d ON d.poheaderid = c.poheaderid
			LEFT JOIN product e ON e.productid = b.productid
			LEFT JOIN grjasa f ON f.pojasaid = c.pojasaid AND f.productid = c.productid
			LEFT JOIN grheader g ON g.grheaderid = f.grheaderid
			left join unitofmeasure h on h.unitofmeasureid = b.uomid 			
			left join addressbook i on i.addressbookid = d.addressbookid 
			left join sloc j on j.slocid = a.slocfromid 
			left join plant k on k.plantid = j.plantid
			WHERE a.prdate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
			and a.plantid = ".$plantid.
			(($addressbook != '')? " and l.fullname = '".$addressbook."'":'').
			(($sloc != '')? " and coalesce(m.sloccode,'') = '".$sloc."'":'').
			(($product != '')? " and coalesce(e.productname,'') = '".$product."'":'').
			(($prno != '')? " and coalesce(a.prno,'') like '%".$prno."%'":'').
			(($pono != '')? " and coalesce(d.pono,'') like '%".$pono."%'":'');
			//print_r($sql);
		
		$command=$this->connection->createCommand($sql);
		$command1=$this->connection->createCommand($sql1);
		$dataReader=$command->queryAll();
		$dataReader1=$command1->queryAll();
		$this->pdf->companyid = $companyid;
		
		$this->pdf->title    = 'Rekonsiliasi FPP, PO, dan LPB';
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
		);
		$this->pdf->setwidths(array(
			10,
			12,
			40,
			25,
			35,
			25,
			30,
			30,
			25,
			200,
			25,
			25,
			25,
			18,
			50,
		));
		$this->pdf->colheader = array(
			'No',
			'Plant',
			'No FPP',
			'Tgl FPP',
			'NO PO',
			'Tgl PO',
			'Supplier',
			'No LPB',
			'Tgl LPB',
			'Artikel',
			'Qty FPP',
			'Qty PO',
			'Qty LPB',
			'Satuan',
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
			'L',
			'L',
			'L',
			'L',
			'R',
			'R',
			'R',
			'L',
			'L',
		);
		$i=1;
		foreach($dataReader as $row){
			$this->pdf->row(array(
				$i,
				$row['plantcode'],
				$row['prno'],
				($row['prdate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['prdate'])),
				$row['pono'],
				($row['podate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['podate'])),
				$row['supplier'],
				$row['grno'],
				($row['grdate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])),
				$row['productname'],
				Yii::app()->format->formatCurrency($row['qtyfpp']),
				Yii::app()->format->formatCurrency($row['qtypo']),
				Yii::app()->format->formatCurrency($row['qtygr']),
				$row['uomcode'],
				$row['statusname'],
			));
			$i++;
		}
		foreach($dataReader1 as $row){
			$this->pdf->row(array(
				$i,
				$row['plantcode'],
				$row['prno'],
				($row['prdate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['prdate'])),
				$row['pono'],
				($row['podate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['podate'])),
				$row['supplier'],
				$row['grno'],
				($row['grdate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])),
				$row['productname'],
				Yii::app()->format->formatCurrency($row['qtyfpp']),
				Yii::app()->format->formatCurrency($row['qtypo']),
				Yii::app()->format->formatCurrency($row['qtygr']),
				$row['uomcode'],
				$row['statusname'],
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
				case 3:
					$this->KartuStokBarangXls($_GET['companyid'],$_GET['plantid'],$_GET['sloc'],$_GET['storagebin'],$_GET['sales'],$_GET['product'],$_GET['salesarea'],$_GET['prno'],$_GET['pono'],$_GET['startdate'],$_GET['enddate']);
					break;
				case 5:
					$this->RekapStokBarangXls($_GET['companyid'],$_GET['plantid'],$_GET['sloc'],$_GET['storagebin'],$_GET['sales'],$_GET['product'],$_GET['salesarea'],$_GET['prno'],$_GET['pono'],$_GET['startdate'],$_GET['enddate']);
					break;
				case 40:
					$this->RekonsiliasiFPPPOLPBXls($_GET['companyid'],$_GET['plantid'],$_GET['sloc'],$_GET['storagebin'],$_GET['sales'],$_GET['product'],$_GET['salesarea'],$_GET['prno'],$_GET['pono'],$_GET['startdate'],$_GET['enddate'],$_GET['addressbook']);
					break;
				default:
					echo GetCatalog('reportdoesnotexist');
			}
		}
	}
	public function RekapStokBarangXls($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $prno, $pono, $startdate, $enddate) {
		$this->menuname='rekapstokbarang';
    parent::actionDownxls();
		$sql = "select * from
														(select materialgroupcode,sloccode, barang,satuan,awal,masuk,keluar,(awal+masuk+keluar) as akhir
                            from
                            (select materialgroupcode,sloccode,barang,satuan,awal,(beli+returjual+trfin+produksi+koreksiin+trs) as masuk,(abs(jual)+abs(returbeli)+abs(trfout)+abs(pemakaian)+abs(koreksiout))*-1 as keluar
                            from
                            (select w.productname as barang,x.uomcode as satuan,v.sloccode,u.materialgroupcode,
                            (
                            select ifnull(sum(aw.qty),0) 
                            from productstockdet aw
                            where aw.productid = t.productid and
                            aw.transdate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and
                            aw.slocid = t.slocid
                            ) as awal,
                            (
                            select ifnull(sum(c.qty),0) 
                            from productstockdet c
                            where c.productid = t.productid and
                            c.referenceno like 'GR%' and
                            c.slocid = t.slocid and
                            c.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as beli,
                            (
                            select ifnull(sum(d.qty),0) 
                            from productstockdet d
                            where d.productid = t.productid and
                            d.referenceno like 'GIR%' and
                            d.slocid = t.slocid and
                            d.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as returjual,
                            (
                            select ifnull(sum(e.qty),0) 
                            from productstockdet e
                            where e.productid = t.productid and
                            e.referenceno like 'TFS%' and
                            e.qty > 0 and
                            e.slocid = t.slocid and
                            e.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as trfin,
														(
														select ifnull(sum(ee.qty),0) 
                            from productstockdet ee
                            where ee.productid = t.productid and
                            ee.referenceno like 'TRS%' and
                            ee.qty > 0 and
                            ee.slocid = t.slocid and
                            ee.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as trs,
                            (
                            select ifnull(sum(f.qty),0) 
                            from productstockdet f
                            where f.productid = t.productid and
                            f.referenceno like 'OP%' and
                            f.qty > 0 and
                            f.slocid = t.slocid and
                            f.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as produksi,
                            (
                            select ifnull(sum(g.qty),0) 
                            from productstockdet g
                            where g.productid = t.productid and
                            g.referenceno like 'SJ%' and
                            g.slocid = t.slocid and
                            g.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as jual,
                            (
                            select ifnull(sum(h.qty),0) 
                            from productstockdet h
                            where h.productid = t.productid and
                            h.referenceno like 'GRR%' and
                            h.slocid = t.slocid and
                            h.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as returbeli,
                            (
                            select ifnull(sum(i.qty),0) 
                            from productstockdet i
                            where i.productid = t.productid and
                            i.referenceno like 'TFS%' and
                            i.qty < 0 and
                            i.slocid = t.slocid and
                            i.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as trfout,
                            (
                            select ifnull(sum(j.qty),0) 
                            from productstockdet j
                            where j.productid = t.productid and
                            j.referenceno like 'OP%' and
                            j.qty < 0 and
                            j.slocid = t.slocid and
                            j.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as pemakaian,
                            (
                            select ifnull(sum(k.qty),0) 
                            from productstockdet k
                            where k.productid = t.productid and
                            k.referenceno like 'TSO%' and
                            k.slocid = t.slocid and
														k.qty < 0 and
                            k.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as koreksiout,
														(
                            select ifnull(sum(kb.qty),0) 
                            from productstockdet kb
                            where kb.productid = t.productid and
                            kb.referenceno like 'TSO%' and
														kb.qty > 0 and
                            kb.slocid = t.slocid and
                            kb.transdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                            and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                            ) as koreksiin
                            from productplant t
							join materialgroup u on u.materialgroupid = t.materialgroupid
							join sloc v on v.slocid = t.slocid 
							join product w on w.productid = t.productid 
							join unitofmeasure x on x.unitofmeasureid = t.uom1 
              where w.productname like '%" . $product . "%' 
							and v.sloccode like '%" . $sloc . "%' 
							and v.plantid = '" . $plantid . "' 
							order by barang) z) zz )zzz 
							where awal <> 0 or masuk <> 0 or keluar <> 0 or akhir <> 0 order by barang asc
			";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();	
		$i=2;$ppid = 0;$proses=0;
		foreach($dataReader as $row){
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $i-1)
				->setCellValueByColumnAndRow(1, $i+1, $row['sloccode'])
				->setCellValueByColumnAndRow(2, $i+1, $row['materialgroupcode'])
				->setCellValueByColumnAndRow(3, $i+1, $row['barang'])
				->setCellValueByColumnAndRow(4, $i+1, $row['satuan'])
				->setCellValueByColumnAndRow(5, $i+1, $row['awal'])
				->setCellValueByColumnAndRow(6, $i+1, $row['masuk'])
				->setCellValueByColumnAndRow(7, $i+1, $row['keluar'])
				->setCellValueByColumnAndRow(8, $i+1, $row['akhir'])
			;
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
	public function RekonsiliasiFPPPOLPBXls($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $prno, $pono, $startdate, $enddate, $addressbook) {
		$this->menuname='rekonfpppo';
    parent::actionDownxls();
				$sql = "
		SELECT n.plantcode,a.prno,a.prdate,d.pono,d.podate,g.grno,g.grdate,e.productname,b.qty AS qtyfpp,c.qty AS qtypo,
			f.qty AS qtygr,a.statusname,h.uomcode,i.uomcode as uom2code,j.uomcode as uom3code,k.uomcode as uom4code,l.fullname as supplier,
			(
			select za.materialgroupcode
			from productplant z 
			join materialgroup za on za.materialgroupid = z.materialgroupid
			where z.productid = e.productid 
			limit 1
			) as materialgroupcode
			FROM prheader a
			left JOIN prraw b ON b.prheaderid = a.prheaderid
			LEFT JOIN podetail c ON c.prrawid = b.prrawid AND c.productid = b.productid
			LEFT JOIN poheader d ON d.poheaderid = c.poheaderid
			LEFT JOIN product e ON e.productid = b.productid
			LEFT JOIN grdetail f ON f.podetailid = c.podetailid AND f.productid = c.productid
			LEFT JOIN grheader g ON g.grheaderid = f.grheaderid
			left join unitofmeasure h on h.unitofmeasureid = b.uomid 
			left join unitofmeasure i on i.unitofmeasureid = b.uom2id
			left join unitofmeasure j on j.unitofmeasureid = b.uom3id
			left join unitofmeasure k on k.unitofmeasureid = b.uom4id
			left join addressbook l on l.addressbookid = d.addressbookid 
			left join sloc m on m.slocid = a.slocfromid 
			left join plant n on n.plantid = m.plantid 
			WHERE a.prdate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
			and a.plantid = ".$plantid.
			(($addressbook != '')? " and l.fullname = '".$addressbook."'":'').
			(($sloc != '')? " and coalesce(m.sloccode,'') = '".$sloc."'":'').
			(($product != '')? " and coalesce(e.productname,'') = '".$product."'":'').
			(($prno != '')? " and coalesce(a.prno,'') like '%".$prno."%'":'').
			(($pono != '')? " and coalesce(d.pono,'') like '%".$pono."%'":'');
		$sql1 = "
			SELECT k.plantcode,a.prno,a.prdate,d.pono,d.podate,g.grno,g.grdate,e.productname,b.qty AS qtyfpp,c.qty AS qtypo,
			f.qty AS qtygr,a.statusname,h.uomcode,'','','',i.fullname as supplier,
			(
			select za.materialgroupcode
			from productplant z 
			join materialgroup za on za.materialgroupid = z.materialgroupid
			where z.productid = e.productid 
			limit 1
			) as materialgroupcode
			FROM prheader a
			left JOIN prjasa b ON b.prheaderid = a.prheaderid
			LEFT JOIN pojasa c ON c.prjasaid = b.prjasaid AND c.productid = b.productid
			LEFT JOIN poheader d ON d.poheaderid = c.poheaderid
			LEFT JOIN product e ON e.productid = b.productid
			LEFT JOIN grjasa f ON f.pojasaid = c.pojasaid AND f.productid = c.productid
			LEFT JOIN grheader g ON g.grheaderid = f.grheaderid
			left join unitofmeasure h on h.unitofmeasureid = b.uomid 			
			left join addressbook i on i.addressbookid = d.addressbookid 
			left join sloc j on j.slocid = a.slocfromid 
			left join plant k on k.plantid = j.plantid
			WHERE a.prdate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
			and a.plantid = ".$plantid.
			(($addressbook != '')? " and l.fullname = '".$addressbook."'":'').
			(($sloc != '')? " and coalesce(m.sloccode,'') = '".$sloc."'":'').
			(($product != '')? " and coalesce(e.productname,'') = '".$product."'":'').
			(($prno != '')? " and coalesce(a.prno,'') like '%".$prno."%'":'').
			(($pono != '')? " and coalesce(d.pono,'') like '%".$pono."%'":'');
		$command=$this->connection->createCommand($sql);
		$command1=$this->connection->createCommand($sql1);
		$dataReader=$command->queryAll();	
		$dataReader1=$command1->queryAll();	
		$i=2;$ppid = 0;$proses=0;
		foreach($dataReader as $row){
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $i-1)
				->setCellValueByColumnAndRow(1, $i+1, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $i+1, $row['prno'])
				->setCellValueByColumnAndRow(3, $i+1, ($row['prdate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['prdate'])))
				->setCellValueByColumnAndRow(4, $i+1, $row['pono'])
				->setCellValueByColumnAndRow(5, $i+1, ($row['podate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['podate'])))
				->setCellValueByColumnAndRow(6, $i+1, $row['supplier'])
				->setCellValueByColumnAndRow(7, $i+1, $row['grno'])
				->setCellValueByColumnAndRow(8, $i+1,($row['grdate'] == null)?'': date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])))
				->setCellValueByColumnAndRow(9, $i+1, $row['materialgroupcode'])
				->setCellValueByColumnAndRow(10, $i+1, $row['productname'])
				->setCellValueByColumnAndRow(11, $i+1, $row['qtyfpp'])
				->setCellValueByColumnAndRow(12, $i+1, $row['qtypo'])
				->setCellValueByColumnAndRow(13, $i+1, $row['qtygr'])
				->setCellValueByColumnAndRow(14, $i+1, $row['uomcode'])
				->setCellValueByColumnAndRow(15, $i+1, $row['statusname'])
			;
			$i++;
		}
		foreach($dataReader1 as $row){
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $i-1)
				->setCellValueByColumnAndRow(1, $i+1, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $i+1, $row['prno'])
				->setCellValueByColumnAndRow(3, $i+1, ($row['prdate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['prdate'])))
				->setCellValueByColumnAndRow(4, $i+1, $row['pono'])
				->setCellValueByColumnAndRow(5, $i+1, ($row['podate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['podate'])))
				->setCellValueByColumnAndRow(6, $i+1, $row['supplier'])
				->setCellValueByColumnAndRow(7, $i+1, $row['grno'])
				->setCellValueByColumnAndRow(8, $i+1,($row['grdate'] == null)?'': date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])))
				->setCellValueByColumnAndRow(9, $i+1, $row['materialgroupcode'])
				->setCellValueByColumnAndRow(10, $i+1, $row['productname'])
				->setCellValueByColumnAndRow(11, $i+1, $row['qtyfpp'])
				->setCellValueByColumnAndRow(12, $i+1, $row['qtypo'])
				->setCellValueByColumnAndRow(13, $i+1, $row['qtygr'])
				->setCellValueByColumnAndRow(14, $i+1, $row['uomcode'])
				->setCellValueByColumnAndRow(15, $i+1, $row['statusname'])
			;
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
	public function KartuStokBarangXls($companyid,$plantid,$sloc, $storagebin, $sales, $product, $salesarea, $prno, $pono, $startdate, $enddate) {
		$this->menuname='kartustokbarang';
    parent::actionDownxls();
		$sql = "SELECT z.sloccode,z.referenceno,materialgroupcode,z.buydate,z.productid,z.productname,z.uomcode,
(
SELECT sum(za.qty)
from productstockdet za
WHERE za.buydate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
AND za.productid = z.productid 
AND za.slocid = z.slocid 
) AS saldoawal,
case when qty >= 0 then qty ELSE 0 END AS masuk,
case when qty < 0 then qty ELSE 0 END AS keluar
FROM
(
SELECT a.slocid,a.referenceno,a.buydate,a.productid,a.productname,a.sloccode,
(
SELECT zz.materialgroupcode
FROM materialgroup zz
JOIN productplant zzz ON zzz.materialgroupid = zz.materialgroupid
WHERE zzz.productid = a.productid
LIMIT 1
) AS materialgroupcode,
a.qty,a.qty2,a.qty3,a.qty4,a.uomcode,a.uom2code,a.uom3code,a.uom4code
from productstockdet a
WHERE a.buydate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' 
AND a.productname like '%".$product."%'
AND a.sloccode like '%".$sloc."%'
) z
order by sloccode asc,productname asc
";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();	
		$i=2;$pid='';$saldoawal=0;
		foreach($dataReader as $row){
			if ($pid != $row['productid']) {
				$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0, $i+1, $i-1)
					->setCellValueByColumnAndRow(1, $i+1, $row['sloccode'])
					->setCellValueByColumnAndRow(2, $i+1, $row['productname'])
					->setCellValueByColumnAndRow(3, $i+1, $row['materialgroupcode'])
					->setCellValueByColumnAndRow(4, $i+1, $row['uomcode'])
					->setCellValueByColumnAndRow(7, $i+1, $row['saldoawal'])
				;
				$saldoawal = $row['saldoawal'];
			} else {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $i-1)
				->setCellValueByColumnAndRow(5, $i+1, $row['referenceno'])
				->setCellValueByColumnAndRow(6, $i+1, date(Yii::app()->params['dateviewfromdb'], strtotime($row['buydate'])))
				->setCellValueByColumnAndRow(8, $i+1, $row['masuk'])
				->setCellValueByColumnAndRow(9, $i+1, $row['keluar'])
			;
			$saldoawal = $saldoawal + $row['masuk'] + $row['keluar'];
			}
			$pid = $row['productid'];
			if ($pid != '') {
				$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0, $i+1, $i-1)
					->setCellValueByColumnAndRow(10, $i+1, $saldoawal)
				;
			}
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}
