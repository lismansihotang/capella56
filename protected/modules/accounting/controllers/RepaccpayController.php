<?php
class RepaccpayController extends Controller {
	public $menuname = 'repaccpay';
	public function actionIndex() {
		$this->renderPartial('index', array());
	}
	public function actionDownPDF() {
		parent::actionDownload();
		if ($_GET['company'] == '') {
			echo getcatalog('emptycompany');
		}
		else if ($_GET['lro'] == '') {
			echo getcatalog('choosereport');
		}
		else if ($_GET['startdate'] == '') {
			echo getcatalog('emptystartdate');
		}
		else if ($_GET['enddate'] == '') {
			echo getcatalog('emptyenddate');
		}
		else {
			switch ($_GET['lro']) {
				case 1:
					$this->RincianBiayaEkspedisiPerDokumen($_GET['company'], $_GET['plant'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
				break;
				case 2:
					$this->RekapBiayaEkspedisiPerDokumen($_GET['company'], $_GET['plant'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
				break;
				case 4:
					$this->RincianPembayaranHutangPerDokumen($_GET['company'], $_GET['plant'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
				break;
				case 5:
					$this->KartuHutang($_GET['company'], $_GET['plant'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
				break;
				case 6:
					$this->RekapHutangPerSupplier($_GET['company'], $_GET['plant'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
				break;
				case 7:
					$this->RincianPembeliandanReturBeliBelumLunas($_GET['company'], $_GET['plant'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
				break;
				case 8:
					$this->RincianUmurHutangperSTTB($_GET['company'], $_GET['plant'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
				break;
				case 9:
					$this->RekapUmurHutangperSupplier($_GET['company'], $_GET['plant'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
				break;
				case 10:
					$this->RekapInvoiceAPPerDokumenBelumStatusMax($_GET['company'], $_GET['plant'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
				break;
				case 11:
					$this->RekapPermohonanPembayaranPerDokumenBelumStatusMax($_GET['company'], $_GET['plant'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
				break;
				case 12:
					$this->RekapNotaReturPembelianPerDokumenBelumStatusMax($_GET['company'], $_GET['plant'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);	
				break;
				case 13:
					$this->ApAging($_GET['company'], $_GET['plant'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
				break;
				case 14:
					$this->bukupembelian($_GET['company'], $_GET['plant'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
				break;
				case 15:
					$this->bukupembelianinvoice($_GET['company'], $_GET['plant'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
				break;
				default:
					echo GetCatalog('reportdoesnotexist');
			}
		}
	}
	//buku pembelian
	public function bukupembelianinvoice($company, $plant, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per) {
		parent::actionDownload();
		$this->pdf->isrepeat = 1;
		$sql = "
		SELECT a.invoiceapid,a.plantcode,a.invoiceapdate,a.invoiceapno,a.invoiceaptaxno,c.fullname as supplier,e.sjsupplier,a.duedate,
		b.podate,a.pono,e.grdate,e.grno,f.productname,f.price,f.price*f.qty as totprice,f.qty as invqty,h.uomcode
FROM invoiceap a
LEFT JOIN poheader b ON b.poheaderid = a.poheaderid
LEFT JOIN addressbook c ON c.addressbookid = a.addressbookid
LEFT JOIN invoiceapgr d ON d.invoiceapid = a.invoiceapid 
LEFT JOIN grheader e ON e.grheaderid = d.grheaderid 
LEFT JOIN invoiceapdetail f ON f.invoiceapid = a.invoiceapid 
LEFT JOIN product g ON g.productid = f.productid
LEFT JOIN unitofmeasure h ON h.unitofmeasureid = f.uomid
WHERE a.invoiceapdate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
AND a.recordstatus = 3 ".
(($plant != '')?"AND a.plantcode = ".$plant:'').
(($supplier != '')?"AND c.fullname = '".$supplier."'":'').
" order by a.plantcode asc, c.fullname asc,a.invoiceapdate asc ";
		
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->companyid = $company;
		
		$this->pdf->title    = 'Buku Pembelian';
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
			'C'
		);
		$this->pdf->setwidths(array(
			10,
			12,
			60,
			40,
			25,
			25,
			40,
			35,
			24,
			37,
			21,
			50,
			90,
			25,
			15,
			30,
			36
		));
		$this->pdf->colheader = array(
			'No',
			'Plant',
			'Supplier',
			'No INV',
			'Tgl INV',
			'Jatuh Tempo',
			'Fak Pajak',
			'No PO',
			'Tgl PO',
			'NO LPB',
			'Tgl LPB',
			'SJ Suplier',
			'Artikel',
			'Qty INV',
			'Satuan',
			'Harga',
			'Total'
		);
		$this->pdf->RowHeader();        
		$i=1;
		$this->pdf->coldetailalign = array(
			'L','L','L','L','L','L','L','L','L','L','L','L','L','R','L','R','R'
		);
		$i=1;$total=0;$totsupplier=0;$supplier='';
		foreach($dataReader as $row){
			if ($supplier == '') {
				$supplier = $row['supplier'];
				$this->pdf->row(array($i,
					$row['plantcode'],
					$row['supplier'],
					$row['invoiceapno'],
					($row['invoiceapdate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoiceapdate'])),
					($row['duedate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['duedate'])),
					$row['invoiceaptaxno'],
					$row['pono'],
					($row['podate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['podate'])),
					$row['grno'],
					($row['grdate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])),
					$row['sjsupplier'],
					$row['productname'],
					Yii::app()->format->formatCurrency($row['invqty']),
					$row['uomcode'],
					Yii::app()->format->formatCurrency($row['price']),
					Yii::app()->format->formatCurrency($row['totprice'])
				));
				$totsupplier = $row['totprice'];
				$total = $row['totprice'];
			} else 
			if ($supplier == $row['supplier']) {
				$totsupplier += $row['totprice'];
				$total += $row['totprice'];
				$this->pdf->row(array($i,
					$row['plantcode'],
					$row['supplier'],
					$row['invoiceapno'],
					($row['invoiceapdate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoiceapdate'])),
					($row['duedate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['duedate'])),
					$row['invoiceaptaxno'],
					$row['pono'],
					($row['podate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['podate'])),
					$row['grno'],
					($row['grdate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])),
					$row['sjsupplier'],
					$row['productname'],
					Yii::app()->format->formatCurrency($row['invqty']),
					$row['uomcode'],
					Yii::app()->format->formatCurrency($row['price']),
					Yii::app()->format->formatCurrency($row['totprice'])
				));
			} else 
			if ($supplier != $row['supplier']) {
				$this->pdf->SetFont('Arial','B',10);
				$this->pdf->row(array('',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'Total '.$supplier,
					'',
					'',
					'',
					Yii::app()->format->formatCurrency($totsupplier)
				));
				$this->pdf->row(array('',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					''
				));
				$this->pdf->SetFont('Arial','',10);
				$supplier = $row['supplier'];
				$totsupplier = $row['totprice'];
				$total += $row['totprice'];
				$this->pdf->row(array($i,
					$row['plantcode'],
					$row['supplier'],
					$row['invoiceapno'],
					($row['invoiceapdate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoiceapdate'])),
					($row['duedate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['duedate'])),
					$row['invoiceaptaxno'],
					$row['pono'],
					($row['podate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['podate'])),
					$row['grno'],
					($row['grdate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])),
					$row['sjsupplier'],
					$row['productname'],
					Yii::app()->format->formatCurrency($row['invqty']),
					$row['uomcode'],
					Yii::app()->format->formatCurrency($row['price']),
					Yii::app()->format->formatCurrency($row['totprice'])
				));
			}
			$i++;
		}
		$this->pdf->SetFont('Arial','B',10);
		$this->pdf->SetFont('Arial','B',10);
				$this->pdf->row(array('',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'Total',
					'',
					'',
					'',
					Yii::app()->format->formatCurrency($totsupplier)
				));
		$this->pdf->row(array('',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'Total',
					'',
					'',
					'',
					Yii::app()->format->formatCurrency($total)
				));
		$this->pdf->Output();
	}
	public function bukupembelian($company, $plant, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per) {
		parent::actionDownload();
		$this->pdf->isrepeat = 1;
		$sql = "
		select *
			from (
		SELECT j.plantcode, a.pono, a.podate, c.grno, c.grdate, d.invoiceapno, d.invoiceapdate, d.invoiceaptaxno, l.fullname as supplier, 
		c.sjsupplier, d.duedate, e.productname, b.qty as poqty, f.qty as grqty, k.qty as invqty, b.qty2 as poqty2, f.qty2 as grqty2, k.qty2 as invqty2,
		h.uomcode as uom, i.uomcode as uom2, b.price, getamountdetailbypo(a.poheaderid,b.podetailid) as totprice, a.headernote
			FROM poheader a
			left JOIN podetail b ON b.poheaderid = a.poheaderid
			LEFT JOIN grheader c on c.poheaderid = a.poheaderid
			LEFT JOIN invoiceap d on d.poheaderid = a.poheaderid
			LEFT JOIN product e ON e.productid = b.productid
			left join grdetail f on f.podetailid = b.podetailid and f.productid = b.productid
			left join invoiceapgr g on g.grheaderid = c.grheaderid
			left join unitofmeasure h on h.unitofmeasureid = b.uomid 
			left join unitofmeasure i on i.unitofmeasureid = b.uom2id
			left join plant j on j.plantid = a.plantid
			left join addressbook l on l.addressbookid = a.addressbookid
			left join invoiceapdetail k on k.invoiceapgrid = g.invoiceapgrid and k.productid = f.productid
			left join sloc m on m.slocid = b.slocid  
			WHERE a.podate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'".
			(($plant != '')?" and a.plantid = ".$plant:'').
			" and coalesce(m.sloccode,'') like '%".$sloc."%'  
			and coalesce(e.productname,'') like '%".$product."%'
			and coalesce(d.invoiceapno,'') like '%".$invoice."%' 

			UNION

			SELECT j.plantcode, a.pono, a.podate, c.grno, c.grdate, '', '', '', l.fullname as supplier, 
		c.sjsupplier, '', e.productname, b.qty as poqty, ff.qty as grqty, '', '', '', '',
		h.uomcode as uom, '', b.price, getamountdetailbypo(a.poheaderid,b.pojasaid) as totprice, a.headernote 
			FROM poheader a
			left JOIN pojasa b ON b.poheaderid = a.poheaderid
			LEFT JOIN grheader c on c.poheaderid = a.poheaderid
			LEFT JOIN product e ON e.productid = b.productid
			left join grjasa ff on ff.pojasaid = b.pojasaid and ff.productid = b.productid
			left join unitofmeasure h on h.unitofmeasureid = b.uomid 
			left join plant j on j.plantid = a.plantid
			left join addressbook l on l.addressbookid = a.addressbookid
			left join sloc m on m.slocid = b.sloctoid
			WHERE a.podate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'".
			(($plant != '')?" and a.plantid = ".$plant:'').
			" and coalesce(m.sloccode,'') like '%".$sloc."%' 
			and coalesce(e.productname,'') like '%".$product."%'
			) zz where zz.productname is not null order by pono asc
			";
		
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->companyid = $company;
		
		$this->pdf->title    = 'Buku Pembelian';
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
			'C'
		);
		$this->pdf->setwidths(array(
			8,
			12,
			34,
			25,
			40,
			25,
			50,
			33,
			25,
			25,
			25,
			37,
			75,
			20,
			20,
			20,
			14,
			27,
			27,
			30
		));
		$this->pdf->colheader = array(
			'No',
			'Plant',
			'No PO',
			'Tgl PO',
			'NO LPB',
			'Tgl LPB',
			'Supplier',
			'SJ Suplier',
			'No INV',
			'Tgl INV',
			'Jatuh Tempo',
			'Fak Pajak',
			'Artikel',
			'Qty PO',
			'Qty LPB',
			'Qty INV',
			'Satuan',
			'Harga',
			'Bayar',
			'Total'
		);
		$this->pdf->RowHeader();        
		$i=1;
		$this->pdf->coldetailalign = array(
			'L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','R','R','R'
		);
		$i=1;
		foreach($dataReader as $row){
			$this->pdf->row(array($i,
				$row['plantcode'],
				$row['pono'],
				($row['podate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['podate'])),
				$row['grno'],
				($row['grdate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])),
				$row['supplier'],
				$row['sjsupplier'],
				$row['invoiceapno'],
				($row['invoiceapdate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoiceapdate'])),
				($row['duedate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['duedate'])),
				$row['invoiceaptaxno'],
				$row['productname'],
				Yii::app()->format->formatCurrency($row['poqty']),
				Yii::app()->format->formatCurrency($row['grqty']),
				Yii::app()->format->formatCurrency($row['invqty']),
				$row['uom'],
				Yii::app()->format->formatCurrency($row['price']),
				Yii::app()->format->formatCurrency(($row['poqty']*$row['price'])),
				Yii::app()->format->formatCurrency($row['totprice'])
			));
			$i++;
		}
		$this->pdf->Output();
	}
	//ap aging
	public function ApAging($company, $plant, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per) {
		parent::actionDownload();
		$sql = "select az.*,
									case when umur > 0 and umur <= 30 then amount else 0 end as 1sd30,
									case when umur > 30 and umur <= 60 then amount else 0 end as 30sd60,
									case when umur > 60 and umur <= 90 then amount else 0 end as 60sd90,
									case when umur > 90 and umur <= 120 then amount else 0 end as 90sd120,
									case when umur > 120 then amount else 0 end as sd120
									from
									(select c.fullname,aa.invoiceapno,aa.invoiceapdate,d.paydays,
									date_add(aa.invoiceapdate,interval d.paydays day) as dropdate,
									datediff(date_add(aa.invoiceapdate,interval d.paydays day),current_date()) as diffdate,
									datediff(current_date(),aa.invoiceapdate) as umur,
									(sum(a.total)) as amount
									from invoiceapdetail a
									left join invoiceap aa on aa.invoiceapid = a.invoiceapid
									left join addressbook c on c.addressbookid = aa.addressbookid
									left join paymentmethod d on d.paymentmethodid = aa.paymentmethodid
									where c.fullname like '%" . $supplier . "%'".
									(($plant != '')?" and a.plantid = ".$plant:'').
									" and aa.invoiceapid not in (
									select zz.invoiceapid from cashbankoutdetail zz 
									left join cashbankout zzz on zzz.cashbankoutid = zz.cashbankoutid
									where zzz.recordstatus = 3) and aa.recordstatus > 1 group by invoiceapno) az order by fullname,umur";
		$command = $this->connection->createCommand($sql);
		$dataReader = $command->queryAll();
		$this->pdf->title = 'AP AGING';
		$this->pdf->isrepeat = 1;
		$this->pdf->AddPage('L', 'Legal');
		$this->pdf->setFont('Arial', 'B', 9);
		$this->pdf->colalign = array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C');
		$this->pdf->setwidths(array(65, 35, 25, 25, 20, 32, 32, 32, 32, 32));
		$this->pdf->colheader = array('Supplier', 'Invoice No', 'Invoice Date', 'Expired Date', 'Umur (Hari)', '1 - 30', '31 - 60', '61 - 90', '91 - 120', '>= 121');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L', 'R', 'R', 'R', 'R', 'R', 'R');
		$totalexpamount = 0;
		$total130 = 0;
		$total3160 = 0;
		$total6190 = 0;
		$total91120 = 0;
		$total121 = 0;
		foreach ($dataReader as $row) {
			$this->pdf->setFont('Arial', '', 8);
			$total130 += $row['1sd30'];
			$total3160 += $row['30sd60'];
			$total6190 += $row['60sd90'];
			$total91120 += $row['90sd120'];
			$total121 += $row['sd120'];
			$totalexpamount += $row['1sd30'] + $row['30sd60'] + $row['60sd90'] + $row['90sd120'] + $row['sd120'];
			$this->pdf->row(array($row['fullname'], $row['invoiceapno'], date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoiceapdate'])), date(Yii::app()->params['dateviewfromdb'], strtotime($row['dropdate'])), $row['umur'], Yii::app()->numberFormatter->formatCurrency($row['1sd30'], getcurrencysymbol()), Yii::app()->numberFormatter->formatCurrency($row['30sd60'], getcurrencysymbol()), Yii::app()->numberFormatter->formatCurrency($row['60sd90'], getcurrencysymbol()), Yii::app()->numberFormatter->formatCurrency($row['90sd120'], getcurrencysymbol()), Yii::app()->numberFormatter->formatCurrency($row['sd120'], getcurrencysymbol())));
		}
		$this->pdf->setFont('Arial', 'B', 8);
		$this->pdf->row(array('', 'Total', '', '', '', Yii::app()->numberFormatter->formatCurrency($total130, getcurrencysymbol()), Yii::app()->numberFormatter->formatCurrency($total3160, getcurrencysymbol()), Yii::app()->numberFormatter->formatCurrency($total6190, getcurrencysymbol()), Yii::app()->numberFormatter->formatCurrency($total91120, getcurrencysymbol()), Yii::app()->numberFormatter->formatCurrency($total121, getcurrencysymbol())));
		$this->pdf->row(array('', 'Grand Total', '', '', '', Yii::app()->numberFormatter->formatCurrency($totalexpamount, getcurrencysymbol()), '', '', '', ''));
		$this->pdf->Output();
	}
	//1
	public function RincianBiayaEkspedisiperDokumen($company, $plant, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per) {
		parent::actionDownload();
		$grandqty = 0;
		$grandqty2 = 0;
		$grandbiaya = 0;
		$sql = " select distinct a.expeditionapid, a.expeditionapno, a.expeditionapdate, b.pono,a.amount
										from  expeditionap a
										left join poheader b on b.poheaderid = a.poheaderid
										left join addressbook c on c.addressbookid = a.addressbookid
										left join expeditionapgr d on d.expeditionapid = a.expeditionapid
										left join product e on e.productid = d.productid
										where a.recordstatus = 3 ".
										(($plant != '')? " and a.plantid = " . $plant:'').
										" and c.fullname like '%" . $supplier . "%' and e.productname like '%" . $product . "%'
										and a.expeditionapdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'";
		$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($dataReader as $row) {
			$this->pdf->company = $company;
		}
		$this->pdf->title = 'Rincian Biaya Ekspedisi Per Dokumen';
		$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');
		$this->pdf->sety($this->pdf->gety() + 5);
		foreach ($dataReader as $row) {
			$this->pdf->SetFont('Arial', '', 10);
			$this->pdf->text(10, $this->pdf->gety() + 10, 'Dokumen');
			$this->pdf->text(40, $this->pdf->gety() + 10, ': ' . $row['expeditionapno']);
			$this->pdf->text(10, $this->pdf->gety() + 15, 'No. PO');
			$this->pdf->text(40, $this->pdf->gety() + 15, ': ' . $row['pono']);
			$this->pdf->text(130, $this->pdf->gety() + 10, 'Amount');
			$this->pdf->text(160, $this->pdf->gety() + 10, ': ' . Yii::app()->format->formatNumber($row['amount']));
			$this->pdf->text(130, $this->pdf->gety() + 15, 'Tanggal');
			$this->pdf->text(160, $this->pdf->gety() + 15, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['expeditionapdate'])));
			$sql1 = "select f.grno, c.productname, d.uomcode, e.uomcode as uom2code, a.nilaibeban as biaya,a.qty,a.qty2
											from expeditionapgr a
											left join expeditionap b on b.expeditionapid = a.expeditionapid
											left join product c on c.productid = a.productid
											left join unitofmeasure d on d.unitofmeasureid = a.uomid
											left join unitofmeasure e on e.unitofmeasureid = a.uom2id
											left join grheader f on f.grheaderid = a.grheaderid
											where a.plantid = " . $plant . " and b.recordstatus = 3
											and b.expeditionapid = '" . $row['expeditionapid'] . "' and c.productname like '%" . $product . "%'
											and b.expeditionapdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                        and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' group by productname";
			$dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
			$i = 0;
			$totalqty = 0;
			$totalqty2 = 0;
			$totbiaya = 0;
			$this->pdf->sety($this->pdf->gety() + 18);
			$this->pdf->setFont('Arial', 'B', 8);
			$this->pdf->colalign = array('C', 'C', 'C', 'C', 'C', 'C');
			$this->pdf->setwidths(array(10, 20, 60, 30, 30, 30));
			$this->pdf->colheader = array('No', 'No Referensi', 'Nama Barang', 'Qty', 'Qty 2', 'Biaya');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('L', 'L', 'L', 'R', 'R', 'R');
			$this->pdf->setFont('Arial', '', 8);
			foreach ($dataReader1 as $row1) {
				$i += 1;
				$this->pdf->row(array($i, $row1['grno'], $row1['productname'], Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty']) . ' ' . $row1['uomcode'], Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty2']) . ' ' . $row1['uom2code'], Yii::app()->format->formatNumber($row1['biaya'])));
				$totalqty += $row1['qty'];
				$totalqty2 += $row1['qty2'];
				$totbiaya += $row1['biaya'];
			}
			$this->pdf->row(array('', '', 'Total', Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty), Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty2), Yii::app()->format->formatNumber($totbiaya)));
			$grandqty += $totalqty;
			$grandqty2 += $totalqty2;
			$grandbiaya += $totbiaya;
			$this->pdf->checkPageBreak(20);
		}
		$this->pdf->sety($this->pdf->gety() + 5);
		$this->pdf->colalign = array('C', 'R', 'R', 'R');
		$this->pdf->setwidths(array(15, 45, 60, 60));
		$this->pdf->setFont('Arial', 'B', 9);
		$this->pdf->row(array('', 'GRAND QTY:  ' . Yii::app()->format->formatCurrency($grandqty), 'GRAND QTY 2:   ' . Yii::app()->format->formatCurrency($grandqty2), 'GRAND BIAYA:  ' . Yii::app()->format->formatCurrency($grandbiaya)));
		$this->pdf->Output();
	}
	//2
	public function RekapBiayaEkspedisiPerDokumen($company, $plant, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per) {
		parent::actionDownload();
		$sql = "select distinct a.expeditionapid, a.expeditionapno, a.expeditionapdate as tanggal, b.pono,a.amount as jumlah,
		c.fullname as suppliername, cc.fullname as ekpedisi,a.headernote
										from  expeditionap a
										left join poheader b on b.poheaderid = a.poheaderid
										left join addressbook c on c.addressbookid = a.addressbookid
										left join addressbook cc on cc.addressbookid = a.addressbookexpid
										left join expeditionapgr d on d.expeditionapid = a.expeditionapid
										left join product e on e.productid = d.productid
										where a.recordstatus = 3 ".
										(($plant != '')? " and a.plantid = " . $plant:'').
										" and c.fullname like '%" . $supplier . "%' and e.productname like '%" . $product . "%'
										and a.expeditionapdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'";
		$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($dataReader as $row) {
			$this->pdf->company = $company;
		}
		$this->pdf->title = 'Rekap Biaya Ekspedisi Per Dokumen';
		$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');
		$this->pdf->setFont('Arial', 'B', 8);
		$this->pdf->sety($this->pdf->gety() + 10);
		$this->pdf->colalign = array('C', 'C', 'C', 'C', 'C', 'C', 'C');
		$this->pdf->setwidths(array(10, 20, 45, 15, 45, 20, 30));
		$this->pdf->colheader = array('No', 'No Dokumen', 'Nama Ekpedisi', 'Tanggal', 'Supplier', 'No.PO', 'Biaya Ekspedisi');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('L', 'L', 'C', 'C', 'C', 'R', 'R');
		$total = 0;
		$i = 0;
		$biaya = 0;
		foreach ($dataReader as $row) {
			$i += 1;
			$this->pdf->setFont('Arial', '', 8);
			$this->pdf->row(array($i, $row['expeditionapno'], $row['ekpedisi'], date(Yii::app()->params['dateviewfromdb'], strtotime($row['tanggal'])), $row['suppliername'], $row['pono'], Yii::app()->format->formatCurrency($row['jumlah'])));
			$total += $row['jumlah'];
		}
		$this->pdf->sety($this->pdf->gety() + 5);
		$this->pdf->setFont('Arial', 'B', 8);
		$this->pdf->row(array('', 'GRAND TOTAL', '', '', Yii::app()->format->formatNumber($total)));
		$this->pdf->checkPageBreak(20);
		$this->pdf->Output();
	}
	//4
	public function RincianPembayaranHutangPerDokumen($company, $plant,$sloc, $product, $supplier, $invoice, $startdate, $enddate, $per) {
		parent::actionDownload();
		$totalamount1 = 0;
		$totalpayamount1 = 0;
		$totalsisa1 = 0;
		$sql = "select distinct e.cashbankoutno,e.cashbankoutid,e.cashbankoutdate as cbdate,f.reqpayno,f.reqpaydate as rpdate
              from cashbankout e
              join cashbankoutdetail a on a.cashbankoutid=e.cashbankoutid
              join invoiceap b on b.invoiceapid=a.invoiceapid
              join addressbook c on c.addressbookid=b.addressbookid
              join reqpay f on f.reqpayid=e.reqpayid
							join plant g on g.plantid = e.plantid 
              where e.recordstatus=3 ".
							(($plant != '')? " and a.plantid = " . $plant:''). 
							" and c.fullname like '%" . $supplier . "%' and e.cashbankoutno like '%" . $invoice . "%'
              and e.cashbankoutdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'
              and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
              order by cashbankoutno";
		$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($dataReader as $row) {
			$this->pdf->company = $company;
		}
		$this->pdf->title = 'Rincian Pembayaran Hutang Per Dokumen';
		$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L', 'A4');
		$this->pdf->setFont('Arial');
		foreach ($dataReader as $row) {
			$this->pdf->setFont('Arial', 'B', 9);
			$this->pdf->text(10, $this->pdf->gety() + 2, 'No ');
			$this->pdf->text(30, $this->pdf->gety() + 2, ': ' . $row['cashbankoutno']);
			$this->pdf->text(160, $this->pdf->gety() + 2, 'No ');
			$this->pdf->text(170, $this->pdf->gety() + 2, ': ' . $row['reqpayno']);
			$this->pdf->text(10, $this->pdf->gety() + 6, 'Tgl ');
			$this->pdf->text(30, $this->pdf->gety() + 6, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['cbdate'])));
			$this->pdf->text(160, $this->pdf->gety() + 6, 'Tgl ');
			$this->pdf->text(170, $this->pdf->gety() + 6, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['rpdate'])));
			$sql1 = " select b.invoiceapno,c.fullname,b.invoiceapdate,getamountbyinvoiceapumum(b.invoiceapid) as amount,a.amount as payamount,getamountbyinvoiceapumum(b.invoiceapid) - a.amount as sisa
                    from cashbankoutdetail a
                    join invoiceap b on b.invoiceapid=a.invoiceapid
                    join addressbook c on c.addressbookid=b.addressbookid
                    where a.cashbankoutid = '" . $row['cashbankoutid'] . "'  ";
			$dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
			$this->pdf->setFont('Arial', '', 8);
			$this->pdf->sety($this->pdf->gety() + 10);
			$this->pdf->colalign = array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C');
			$this->pdf->setwidths(array(10, 30, 80, 20, 35, 35, 50, 35));
			$this->pdf->colheader = array('No', 'No Invoice', 'Customer', 'Tgl Invoice', 'Saldo Invoice', 'Nilai bayar', 'Sisa');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('C', 'L', 'L', 'C', 'R', 'R', 'R', 'R', 'R');
			$i = 0;
			$totalamount = 0;
			$totalpayamount = 0;
			$totalsisa = 0;
			foreach ($dataReader1 as $row1) {
				$i = $i + 1;
				$this->pdf->row(array($i, $row1['invoiceapno'], $row1['fullname'], date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoiceapdate'])), Yii::app()->format->formatCurrency($row1['amount'] / $per), Yii::app()->format->formatCurrency($row1['payamount'] / $per), Yii::app()->format->formatCurrency($row1['sisa'] / $per),));
				$totalamount += ($row1['amount'] / $per);
				$totalpayamount += ($row1['payamount'] / $per);
				$totalsisa += ($row1['sisa'] / $per);
			}
			$this->pdf->setFont('Arial', 'BI', 9);
			$this->pdf->row(array('', 'TOTAL ', $row['cashbankoutno'], '', Yii::app()->format->formatCurrency($totalamount), Yii::app()->format->formatCurrency($totalpayamount), Yii::app()->format->formatCurrency($totalsisa)));
			$this->pdf->sety($this->pdf->gety() + 10);
			$totalamount1 += $totalamount;
			$totalpayamount1 += $totalpayamount;
			$totalsisa1 += $totalsisa;
			$this->pdf->checkPageBreak(25);
		}
		$this->pdf->sety($this->pdf->gety() + 10);
		$this->pdf->setFont('Arial', 'BI', 10);
		$this->pdf->setwidths(array(10, 30, 80, 20, 35, 35, 50, 35));
		$this->pdf->row(array('', '', 'GRAND TOTAL', '', Yii::app()->format->formatCurrency($totalamount1), Yii::app()->format->formatCurrency($totalpayamount1), Yii::app()->format->formatCurrency($totalsisa1)));
		$this->pdf->Output();
	}
	//5
	public function KartuHutang($company, $plant, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per) {
		parent::actionDownload();
		$penambahan1 = 0;
		$dibayar1 = 0;
		$bank1 = 0;
		$diskon1 = 0;
		$retur1 = 0;
		$ob1 = 0;
		$saldo1 = 0;
		$sql = "
			SELECT xx.*,totalinvoice-dibayar AS saldoawal
			FROM 
			(
			SELECT z.addressbookid,z.fullname,SUM(totalinvoice) AS totalinvoice, sum(dibayar) AS dibayar
			FROM
			(
			select a.addressbookid,a.fullname,b.invoiceapno,b.invoiceapdate,
			GetAmountByInvoiceAPUmum(b.invoiceapid) AS totalinvoice,
			(
			SELECT ifnull(SUM(ifnull(zza.amount,0)),0)
			FROM cashbankout zz 
			LEFT JOIN cashbankoutdetail zza ON zza.cashbankoutid = zz.cashbankoutid
			WHERE zza.addressbookid = a.addressbookid 
			AND zza.invoiceapid = b.invoiceapid
			AND zz.cashbankoutdate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' ".
			(($plant != '')? " and zz.plantid = " . $plant:''). 
			"
			) AS dibayar
			from addressbook a 
			left JOIN invoiceap b ON b.addressbookid = a.addressbookid
			where a.fullname like '%".$supplier."%' 
			AND b.invoiceapdate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' ".
			(($plant != '')? " and b.plantid = " . $plant:''). 
			"
			) z
			GROUP BY z.addressbookid,z.fullname
			) xx";
		$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
		$this->pdf->company = $company;
		$this->pdf->title = 'Kartu Hutang';
		$this->pdf->subtitle = 'Dari Tgl : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');
		foreach ($dataReader as $row) {
			$this->pdf->SetFont('Arial', 'B', 10);
			$this->pdf->text(10, $this->pdf->gety() + 3, $row['fullname']);
			$this->pdf->setFont('Arial', 'B', 8);
			$this->pdf->sety($this->pdf->gety() + 5);
			$this->pdf->colalign = array('C', 'C', 'C', 'C', 'C', 'C');
			$this->pdf->setwidths(array(17, 32, 40, 35, 35, 35));
			$this->pdf->colheader = array('Tanggal', 'No Bukti', 'Keterangan', 'Debet', 'Credit', 'Saldo');
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
				(select a.invoiceapno as dokumen,a.invoiceapdate as tanggal,ifnull(c.pono,'-') as ref,ifnull(sum(aa.total),0) as penambahan,'0' as dibayar
				from invoiceap a
				left join invoiceapdetail aa on aa.invoiceapid = a.invoiceapid
				left join poheader c on c.poheaderid=a.poheaderid
				where a.recordstatus=3 ". 
				(($plant != '')? " and a.plantid=" . $plant :''). 
				" and a.addressbookid=" . $row['addressbookid'] . "
				and a.invoiceapdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
				and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' group by invoiceapno
					union
				select d.cashbankoutno as dokumen,d.cashbankoutdate as tanggal,concat(ifnull(h.pono,'-'),' / ',ifnull(g.invoiceapno,'-')) as ref,'0' as penambahan,c.amount as dibayar
				from cashbankoutdetail c
				left join cashbankout d on d.cashbankoutid=c.cashbankoutid
				left join invoiceap g on g.invoiceapid=c.invoiceapid
				left join poheader h on h.poheaderid=g.poheaderid
				where d.recordstatus=3 ".
				(($plant != '')? " and d.plantid=".$plant:'').
				" and d.cashbankoutdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
				and c.invoiceapid in (select b.invoiceapid
				from invoiceap b
				left join poheader f on f.poheaderid=b.poheaderid
				where b.recordstatus=3 ".
				(($plant != '')? " and b.plantid=" . $plant:''). 
				" and b.addressbookid = " . $row['addressbookid'] . "
				and b.invoiceapdate <='" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
				) z
				order by tanggal,dokumen";
			$dataReader2 = Yii::app()->db->createCommand($sql2)->queryAll();
			foreach ($dataReader2 as $row2) {
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->row(array(date(Yii::app()->params['dateviewfromdb'], strtotime($row2['tanggal'])), $row2['dokumen'], $row2['ref'], Yii::app()->format->formatCurrency(-$row2['dibayar'] / $per),Yii::app()->format->formatCurrency($row2['penambahan'] / $per), ''));
				$penambahan += $row2['penambahan'] / $per;
				$dibayar += $row2['dibayar'] / $per;
			}
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->setwidths(array(90, 35, 35, 35, 30, 30, 30, 30));
			$this->pdf->coldetailalign = array('C', 'R', 'R', 'R', 'R', 'R', 'R', 'R');
			$this->pdf->row(array('TOTAL ' . $row['fullname'], Yii::app()->format->formatCurrency(-$dibayar),Yii::app()->format->formatCurrency($penambahan),  Yii::app()->format->formatCurrency($row['saldoawal'] / $per + $penambahan - $dibayar)));
			$penambahan1 += $penambahan;
			$dibayar1 += $dibayar;
			$saldo1 += $row['saldoawal'] / $per;
			$this->pdf->sety($this->pdf->gety() + 5);
			$this->pdf->checkPageBreak(5);
		}
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->setwidths(array(50, 35, 35, 35, 35, 30, 30, 30, 30));
		$this->pdf->coldetailalign = array('C', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R');
		$this->pdf->row(array('', 'Saldo Awal', 'Debet', 'Credit', 'Saldo Akhir'));
		$this->pdf->SetFont('Arial', 'BI', 8);
		$this->pdf->setwidths(array(50, 35, 35, 35, 35, 30, 30, 30, 30));
		$this->pdf->coldetailalign = array('C', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R');
		$this->pdf->row(array('GRAND TOTAL', Yii::app()->format->formatCurrency($saldo1), Yii::app()->format->formatCurrency(-$dibayar1),Yii::app()->format->formatCurrency($penambahan1),  Yii::app()->format->formatCurrency($saldo1 + $penambahan1 - $dibayar1)));
		$this->pdf->Output();
	}
	//6
	public function RekapHutangPerSupplier($company, $plant, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per) {
		parent::actionDownload();
		$sql = "select *
						from (select a.fullname,
						ifnull((select sum(ifnull(dd.total,0)-ifnull((select sum(ifnull(b.amount,0))
						from cashbankoutdetail b
						left join cashbankout c on c.cashbankoutid=b.cashbankoutid
						where c.recordstatus=3 and b.invoiceapid=d.invoiceapid ".
						(($plant != '')? " and c.plantid=".$plant:'').
						" and c.cashbankoutdate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'),0))
						from invoiceap d
						left join invoiceapdetail dd on dd.invoiceapid = d.invoiceapid
						left join poheader f on f.poheaderid=d.poheaderid
						where d.recordstatus=3 ".
						(($plant != '')? " and d.plantid=" . $plant:''). 
						" and d.addressbookid=a.addressbookid 
						and d.receiptdate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'),0) as saldoawal,
						ifnull((select sum(ifnull(dd.total,0))
						from invoiceap d
						left join invoiceapdetail dd on dd.invoiceapid = d.invoiceapid
						left join poheader f on f.poheaderid=d.poheaderid
						where d.recordstatus=3 ".
						(($plant != '')? " and d.plantid=" . $plant:''). 
						" and d.addressbookid=a.addressbookid 
						and d.receiptdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0) as hutang,
						ifnull((select sum(ifnull((select sum(ifnull(b.amount,0))
						from cashbankoutdetail b
						left join cashbankout c on c.cashbankoutid=b.cashbankoutid
						where c.recordstatus=3 and b.invoiceapid=d.invoiceapid ".
						(($plant != '')? " and c.plantid = ".$plant:'').
						" and c.cashbankoutdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0))
						from invoiceap d
						left join poheader f on f.poheaderid=d.poheaderid
						where d.recordstatus=3 ".
						(($plant != '')? " and d.plantid=" . $plant:''). 
						" and d.addressbookid=a.addressbookid 
						and d.receiptdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0) as dibayar
						from addressbook a where a.fullname like '%" . $supplier . "%' group by fullname) z
						where z.saldoawal<>0 or z.hutang<>0 or z.dibayar<>0
						order by fullname";
		$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($dataReader as $row) {
			$this->pdf->company = $company;
		}
		$this->pdf->title = 'Rekap Hutang Per Supplier';
		$this->pdf->subtitle = 'Dari Tgl : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');
		$this->pdf->setFont('Arial', 'B', 8);
		$this->pdf->sety($this->pdf->gety() + 0);
		$this->pdf->colalign = array('C', 'C', 'C', 'C', 'C', 'C');
		$this->pdf->setwidths(array(10, 50, 30, 30, 30, 40));
		$this->pdf->colheader = array('No', 'Supplier', 'Saldo Awal', 'Debit', 'Credit', 'Saldo Akhir');
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
	//7
	public function RincianPembeliandanReturBeliBelumLunas($company, $plant, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per) {
		parent::actionDownload();
		$nilaitot1 = 0;
		$dibayar1 = 0;
		$sisa1 = 0;
		$sql = "select distinct addressbookid,fullname
					from (select *
					from (select d.addressbookid, d.fullname, sum(aa.total) as amount,
					ifnull((select sum(amount) from cashbankoutdetail j
					left join cashbankout k on k.cashbankoutid=j.cashbankoutid
					where k.recordstatus=3 and j.invoiceapid=a.invoiceapid ".
					(($plant != '')? " and k.plantid = ".$plant:'').
					" and k.cashbankoutdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
					group by invoiceapid),0) as payamount
					from invoiceap a
					join invoiceapdetail aa on aa.invoiceapid = a.invoiceapid
					join poheader c on c.poheaderid = a.poheaderid
					join addressbook d on d.addressbookid = c.addressbookid
					where a.recordstatus=3 and a.invoiceapno is not null ".
					(($plant != '')? " and c.plantid = " . $plant:'').
					" and d.fullname like '%" . $supplier . "%'
					and a.receiptdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' group by fullname) z
					where amount > payamount) zz
					order by fullname";
		$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($dataReader as $row) {
			$this->pdf->company = $company;
		}
		$this->pdf->title = 'Rincian Pembelian & Retur Beli Belum Lunas';
		$this->pdf->subtitle = 'Per Tanggal : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P','A4');
		$this->pdf->sety($this->pdf->gety() + 0);
		foreach ($dataReader as $row) {
			$this->pdf->SetFont('Arial', '', 10);
			$this->pdf->text(10, $this->pdf->gety() + 5, $row['fullname']);
			$sql1 = "select *
				from (select a.invoiceapno,c.pono,a.invoiceapdate,e.paydays,
				date_add(a.invoiceapdate,interval e.paydays day) as jatuhtempo,
				datediff('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "',a.invoiceapdate) as umur,sum(aa.total) as amount, 
				ifnull((select sum(amount) from cashbankoutdetail j
				left join cashbankout k on k.cashbankoutid=j.cashbankoutid
				where k.recordstatus=3 and j.invoiceapid=a.invoiceapid ".
				(($plant != '')? " and k.plantid=".$plant:'').
				" and k.cashbankoutdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
				group by invoiceapid),0) as payamount
				from invoiceap a
				join invoiceapdetail aa on aa.invoiceapid = a.invoiceapid
				inner join poheader c on c.poheaderid = a.poheaderid
				inner join addressbook d on d.addressbookid = c.addressbookid
				inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
				where a.recordstatus=3 and a.invoiceapno is not null ".
				(($plant != '')? " and c.plantid = " . $plant:'').
				" and d.addressbookid = '" . $row['addressbookid'] . "'						
				and a.receiptdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' group by invoiceapno) z
				where z.amount > z.payamount
				order by invoiceapdate,invoiceapno";
			$dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
			$this->pdf->sety($this->pdf->gety() + 7);
			$this->pdf->setFont('Arial', 'B', 8);
			$this->pdf->colalign = array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C');
			$this->pdf->setwidths(array(10, 20, 22, 20, 20, 10, 30, 30, 30));
			$this->pdf->colheader = array('No', 'Dokumen', 'Referensi', 'Tanggal', 'j_tempo', 'Umur', 'Nilai', 'Total Bayar', 'Sisa');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('C', 'L', 'C', 'C', 'C', 'C', 'R', 'R', 'R');
			$this->pdf->setFont('Arial', '', 8);
			$i = 0;
			$nilaitot = 0;
			$dibayar = 0;
			$sisa = 0;
			foreach ($dataReader1 as $row1) {
				$i += 1;
				$this->pdf->row(array($i, $row1['invoiceapno'], $row1['pono'], date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoiceapdate'])), date(Yii::app()->params['dateviewfromdb'], strtotime($row1['jatuhtempo'])), $row1['umur'], Yii::app()->format->formatCurrency($row1['amount'] / $per), Yii::app()->format->formatCurrency($row1['payamount'] / $per), Yii::app()->format->formatCurrency(($row1['amount'] / $per) - ($row1['payamount'] / $per))));
				$nilaitot += $row1['amount'] / $per;
				$dibayar += $row1['payamount'] / $per;
				$sisa += (($row1['amount'] / $per) - ($row1['payamount'] / $per));
				$this->pdf->checkPageBreak(20);
			}
			$this->pdf->setFont('Arial', 'B', 8);
			$this->pdf->row(array('', '', '', '', 'Total:', '', Yii::app()->format->formatCurrency($nilaitot), Yii::app()->format->formatCurrency($dibayar), Yii::app()->format->formatCurrency($sisa)));
			$nilaitot1 += $nilaitot;
			$dibayar1 += $dibayar;
			$sisa1 += $sisa;
		}
		$this->pdf->sety($this->pdf->gety() + 5);
		$this->pdf->setFont('Arial', 'BI', 9);
		$this->pdf->coldetailalign = array('R', 'R', 'R', 'R');
		$this->pdf->setwidths(array(95, 30, 35, 30));
		$this->pdf->row(array('GRAND TOTAL', Yii::app()->format->formatCurrency($nilaitot1), Yii::app()->format->formatCurrency($dibayar1), Yii::app()->format->formatCurrency($sisa1)));
		$this->pdf->Output();
	}
	//8
	public function RincianUmurHutangperSTTB($company, $plant, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per) {
		parent::actionDownload();
		$total2sd0 = 0;
		$total20sd30 = 0;
		$total231sd60 = 0;
		$total261sd90 = 0;
		$total2sd90 = 0;
		$totaltempo2 = 0;
		$total2 = 0;
		$sql = "select distinct addressbookid,fullname
					from (select *
					from (select d.addressbookid, d.fullname, getamountbyinvoiceapumum(a.invoiceapid) as amount,
					ifnull((select sum(amount) from cashbankoutdetail j
					left join cashbankout k on k.cashbankoutid=j.cashbankoutid
					where k.recordstatus=3 and j.invoiceapid=a.invoiceapid ".
					(($plant != '')? " and k.plantid = ".$plant:'').
					" and k.cashbankoutdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
					group by invoiceapid),0) as payamount
					from invoiceap a
					join poheader c on c.poheaderid = a.poheaderid
					join addressbook d on d.addressbookid = c.addressbookid
					join plant e on e.plantid = a.plantid 
					where a.recordstatus=3 and a.invoiceapno is not null ".
					(($plant != '')? " and e.plantid = " . $plant:'').
					" and d.fullname like '%" . $supplier . "%'
					and a.invoiceapdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z
					) zz where amount > payamount
					order by fullname";
		$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($dataReader as $row) {
			$this->pdf->company = $company;
		}
		$this->pdf->title = 'Rincian Umur Hutang Per LPB';
		$this->pdf->subtitle = 'Per Tanggal : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L');
		$this->pdf->sety($this->pdf->gety() + 0);
		foreach ($dataReader as $row) {
			$this->pdf->SetFont('Arial', '', 10);
			$this->pdf->text(10, $this->pdf->gety() + 3, 'Supplier');
			$this->pdf->text(30, $this->pdf->gety() + 3, ': ' . $row['fullname']);
			$sql1 = "select z.*,
														case when umurtempo < 0 then totamount else 0 end as sd0,
														case when umurtempo >= 0 and umurtempo <= 30 then totamount else 0 end as 0sd30,
														case when umurtempo > 30 and umurtempo <= 60 then totamount else 0 end as 31sd60,
														case when umurtempo > 60 and umurtempo <= 90 then totamount else 0 end as 61sd90,
														case when umurtempo > 90 then totamount else 0 end as sd90
												from
												(select concat(ifnull(a.invoiceapno,'-'),' / ',ifnull(b.grno,'-')) as invoiceapno, a.invoiceapdate,
												datediff('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "',a.invoiceapdate) as umur,
												datediff('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "',date_add(a.invoiceapdate,interval e.paydays day)) as umurtempo,
												date_add(a.invoiceapdate,interval e.paydays day) as jatuhtempo,
												getamountbyinvoiceapumum(a.invoiceapid)-ifnull((select sum(amount) from cashbankoutdetail j
												left join cashbankout k on k.cashbankoutid=j.cashbankoutid
												where k.recordstatus=3 and j.invoiceapid=a.invoiceapid
												and k.cashbankoutdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
												group by invoiceapid),0) as totamount,e.paydays
												from invoiceap a
												inner join poheader g on g.poheaderid = a.poheaderid 
												inner join grheader b on b.poheaderid = g.poheaderid
												inner join addressbook d on d.addressbookid = g.addressbookid
												inner join paymentmethod e on e.paymentmethodid = g.paymentmethodid
												inner join plant f on f.plantid = a.plantid 
												where a.recordstatus=3 and a.invoiceapno is not null ".
												(($plant != '')? " and f.plantid = " . $plant:'').
												" and d.addressbookid = '" . $row['addressbookid'] . "'						
												and a.invoiceapdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')z
												where totamount > 0";
			$dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
			$this->pdf->sety($this->pdf->gety() + 6);
			$this->pdf->setFont('Arial', '', 8);
			$this->pdf->colalign = array('L', 'L', 'L', 'L', 'L', 'L', 'C', 'R', 'R');
			$this->pdf->setwidths(array(10, 55, 12, 12, 27, 27, 81, 27, 32));
			$this->pdf->colheader = array('|', '|', '|', '|', '|', '|', 'Sudah Jatuh Tempo', '|', '|');
			$this->pdf->RowHeader();
			$this->pdf->colalign = array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C');
			$this->pdf->setwidths(array(10, 55, 12, 12, 27, 27, 27, 27, 27, 27, 32));
			$this->pdf->colheader = array('No', 'No Invoice', 'T.O.P', 'Umur', 'Belum JTT', '0-30 Hari', '31-60 Hari', '61-90 Hari', '> 90 Hari', 'Jumlah', 'Total');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('L', 'C', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R');
			$this->pdf->setFont('Arial', '', 8);
			$totalsd0 = 0;
			$total0sd30 = 0;
			$total31sd60 = 0;
			$total61sd90 = 0;
			$totalsd90 = 0;
			$totaltempo = 0;
			$total = 0;
			$i = 0;
			foreach ($dataReader1 as $row1) {
				$i += 1;
				$this->pdf->row(array($i, $row1['invoiceapno'], $row1['paydays'], $row1['umur'], Yii::app()->format->formatCurrency($row1['sd0'] / $per), Yii::app()->format->formatCurrency($row1['0sd30'] / $per), Yii::app()->format->formatCurrency($row1['31sd60'] / $per), Yii::app()->format->formatCurrency($row1['61sd90'] / $per), Yii::app()->format->formatCurrency($row1['sd90'] / $per), Yii::app()->format->formatCurrency(($row1['0sd30'] + $row1['31sd60'] + $row1['61sd90'] + $row1['sd90']) / $per), Yii::app()->format->formatCurrency(($row1['sd0'] + $row1['0sd30'] + $row1['31sd60'] + $row1['61sd90'] + $row1['sd90']) / $per)));
				$totalsd0 += $row1['sd0'] / $per;
				$total0sd30 += $row1['0sd30'] / $per;
				$total31sd60 += $row1['31sd60'] / $per;
				$total61sd90 += $row1['61sd90'] / $per;
				$totalsd90 += $row1['sd90'] / $per;
				$totaltempo += ($row1['0sd30'] + $row1['31sd60'] + $row1['61sd90'] + $row1['sd90']) / $per;
				$total += ($row1['sd0'] + $row1['0sd30'] + $row1['31sd60'] + $row1['61sd90'] + $row1['sd90']) / $per;
			}
			$this->pdf->setFont('Arial', 'B', 8);
			$this->pdf->row(array('', 'TOTAL ' . $row['fullname'], '', '', Yii::app()->format->formatCurrency($totalsd0), Yii::app()->format->formatCurrency($total0sd30), Yii::app()->format->formatCurrency($total31sd60), Yii::app()->format->formatCurrency($total61sd90), Yii::app()->format->formatCurrency($totalsd90), Yii::app()->format->formatCurrency($totaltempo), Yii::app()->format->formatCurrency($total)));
			$total2sd0 += $totalsd0;
			$total20sd30 += $total0sd30;
			$total231sd60 += $total31sd60;
			$total261sd90 += $total61sd90;
			$total2sd90 += $totalsd90;
			$totaltempo2 += $totaltempo;
			$total2 += $total;
			$this->pdf->sety($this->pdf->gety() + 5);
			$this->pdf->checkPageBreak(30);
		}
		$this->pdf->sety($this->pdf->gety() + 5);
		$this->pdf->coldetailalign = array('C', 'R', 'R', 'R', 'R', 'R', 'R', 'R');
		$this->pdf->setwidths(array(89, 27, 27, 27, 27, 27, 27, 32));
		$this->pdf->setFont('Arial', 'BI', 9);
		$this->pdf->row(array('Grand Total :', Yii::app()->format->formatCurrency($total2sd0), Yii::app()->format->formatCurrency($total20sd30), Yii::app()->format->formatCurrency($total231sd60), Yii::app()->format->formatCurrency($total261sd90), Yii::app()->format->formatCurrency($total2sd90), Yii::app()->format->formatCurrency($totaltempo2), Yii::app()->format->formatCurrency($total2)));
		$this->pdf->Output();
	}
	//9
	public function RekapUmurHutangperSupplier($company, $plant, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per) {
		parent::actionDownload();
		$sql = "select *,sum(sd0) as belumjatuhtempo, sum(0sd30) as sum0sd30,sum(31sd60) as sum31sd60, sum(61sd90) as sum61sd90, sum(sd90) as sumsd90
				from (select *,
				case when umurtempo < 0 then totamount else 0 end as sd0,
				case when umurtempo >= 0 and umurtempo <= 30 then totamount else 0 end as 0sd30,
				case when umurtempo > 30 and umurtempo <= 60 then totamount else 0 end as 31sd60,
				case when umurtempo > 60 and umurtempo <= 90 then totamount else 0 end as 61sd90,
				case when umurtempo > 90 then totamount else 0 end as sd90
				from (select a.invoiceapdate,
				datediff('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "',a.invoiceapdate) as umur,
				datediff('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "', date_add(a.invoiceapdate,interval e.paydays day)) as umurtempo,
				date_add(a.invoiceapdate,interval e.paydays day) as jatuhtempo,d.fullname,
				sum(aa.total)-ifnull((select sum(amount) from cashbankoutdetail j
				left join cashbankout k on k.cashbankoutid=j.cashbankoutid
				where k.recordstatus=3 and j.invoiceapid=a.invoiceapid ".
				(($plant != '')? " and k.plantid = ".$plant:'').
				" and k.cashbankoutdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
				group by invoiceapid),0) as totamount,e.paydays
				from invoiceap a
				join invoiceapdetail aa on aa.invoiceapid = a.invoiceapid
				inner join poheader c on c.poheaderid = a.poheaderid
				inner join addressbook d on d.addressbookid = c.addressbookid
				inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
				where a.recordstatus=3 and a.invoiceapno is not null ".
				(($plant != '')? " and c.plantid = " . $plant:'').
				" and a.receiptdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' group by invoiceapno)z 
				where totamount>0)zz
				group by fullname
				order by fullname";
		$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($dataReader as $row) {
			$this->pdf->company = $company;
		}
		$this->pdf->title = 'Rekap Umur Hutang Per Supplier';
		$this->pdf->subtitle = 'Per Tanggal : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L');
		$this->pdf->setFont('Arial', 'B', 8);
		$this->pdf->sety($this->pdf->gety() + 10);
		$this->pdf->colalign = array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C');
		$this->pdf->setwidths(array(10, 75, 30, 30, 30, 30, 30, 35));
		$this->pdf->colheader = array('No', 'Nama Supplier', 'Belum Jatuh Tempo', '0-30 Hari', '31-60 Hari', '61-90 Hari', '> 90 Hari', 'Total');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('L', 'L', 'R', 'R', 'R', 'R', 'R', 'R');
		$totalsd0 = 0;
		$total0sd30 = 0;
		$total31sd60 = 0;
		$total61sd90 = 0;
		$totalsd90 = 0;
		$total = 0;
		$i = 0;
		foreach ($dataReader as $row) {
			$i += 1;
			$this->pdf->setFont('Arial', '', 8);
			$this->pdf->row(array($i, $row['fullname'], Yii::app()->format->formatCurrency($row['belumjatuhtempo'] / $per), Yii::app()->format->formatCurrency($row['sum0sd30'] / $per), Yii::app()->format->formatCurrency($row['sum31sd60'] / $per), Yii::app()->format->formatCurrency($row['sum61sd90'] / $per), Yii::app()->format->formatCurrency($row['sumsd90'] / $per), Yii::app()->format->formatCurrency(($row['belumjatuhtempo'] + $row['sum0sd30'] + $row['sum31sd60'] + $row['sum61sd90'] + $row['sumsd90']) / $per)));
			$totalsd0 += $row['belumjatuhtempo'] / $per;
			$total0sd30 += $row['sum0sd30'] / $per;
			$total31sd60 += $row['sum31sd60'] / $per;
			$total61sd90 += $row['sum61sd90'] / $per;
			$totalsd90 += $row['sumsd90'] / $per;
			$total += ($row['belumjatuhtempo'] + $row['sum0sd30'] + $row['sum31sd60'] + $row['sum61sd90'] + $row['sumsd90']) / $per;
		}
		$this->pdf->sety($this->pdf->gety() + 5);
		$this->pdf->setFont('Arial', 'B', 9);
		$this->pdf->row(array('', 'Total :', Yii::app()->format->formatCurrency($totalsd0), Yii::app()->format->formatCurrency($total0sd30), Yii::app()->format->formatCurrency($total31sd60), Yii::app()->format->formatCurrency($total61sd90), Yii::app()->format->formatCurrency($totalsd90), Yii::app()->format->formatCurrency($total)));
		$this->pdf->checkPageBreak(20);
		$this->pdf->Output();
	}
	//10
	public function RekapInvoiceAPPerDokumenBelumStatusMax($company, $plant, $sloc, $product, $supplier, $invoice, $startdate, $enddate) {
		parent::actionDownload();
		$sql = "select distinct a.invoiceapid, a.invoiceapno,a.invoiceapdate, b.pono, b.headernote, a.statusname
				from invoiceap a
				left join poheader b on b.poheaderid = a.poheaderid
				where a.recordstatus between 1 and 2
				and b.pono is not null ".
				(($plant != '')? " and a.plantid = " . $plant:'').
				" order by a.recordstatus";
		$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($dataReader as $row) {
			$this->pdf->company = $company;
		}
		$this->pdf->title = 'Rekap Invoice AP Per Dokumen Belum Status Max';
		$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');
		$this->pdf->setFont('Arial', 'B', 8);
		$this->pdf->sety($this->pdf->gety() + 10);
		$this->pdf->colalign = array('C', 'C', 'C', 'C', 'C', 'L', 'L');
		$this->pdf->setwidths(array(10, 20, 25, 25, 25, 60, 25, 25));
		$this->pdf->colheader = array('No', 'ID Transaksi', 'No Transaksi', 'Tanggal', 'No Referensi', 'Keterangan', 'Status');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('C', 'C', 'C', 'C', 'C', 'L', 'L');
		$totalnominal1 = 0;
		$i = 0;
		$totaldisc1 = 0;
		$totaljumlah1 = 0;
		foreach ($dataReader as $row) {
			$i += 1;
			$this->pdf->setFont('Arial', '', 7);
			$this->pdf->row(array($i, $row['invoiceapid'], $row['invoiceapno'], date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoiceapdate'])), $row['pono'], $row['headernote'], $row['statusname']));
			$this->pdf->checkPageBreak(20);
		}
		$this->pdf->Output();
	}
	//11
	public function RekapPermohonanPembayaranPerDokumenBelumStatusMax($company, $plant, $sloc, $product, $supplier, $invoice, $startdate, $enddate) {
		parent::actionDownload();
		$sql = "select a.reqpayid, a.reqpayno, a.reqpaydate, a.headernote, a.recordstatus
				from reqpay a
				where a.recordstatus between 1 and 2 ".
				(($plant != '')? " and a.plantid = " . $plant:'').
				" order by a.recordstatus";
		$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($dataReader as $row) {
			$this->pdf->company = $company;
		}
		$this->pdf->title = 'Rekap Permohonan Pembayaran Per Dokumen Belum Status Max';
		$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');
		$this->pdf->setFont('Arial', 'B', 8);
		$this->pdf->sety($this->pdf->gety() + 10);
		$this->pdf->colalign = array('C', 'C', 'C', 'C', 'C', 'L', 'L');
		$this->pdf->setwidths(array(10, 20, 25, 25, 25, 60, 25, 25));
		$this->pdf->colheader = array('No', 'ID Transaksi', 'No Transaksi', 'Tanggal', 'No Referensi', 'Keterangan', 'Status');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('C', 'C', 'C', 'C', 'C', 'L', 'L');
		$totalnominal1 = 0;
		$i = 0;
		$totaldisc1 = 0;
		$totaljumlah1 = 0;
		foreach ($dataReader as $row) {
			$i += 1;
			$this->pdf->setFont('Arial', '', 7);
			$this->pdf->row(array($i, $row['reqpayid'], $row['reqpayno'], date(Yii::app()->params['dateviewfromdb'], strtotime($row['reqpaydate'])), '', $row['headernote'], findstatusname("appreqpay", $row['recordstatus'])));
			$this->pdf->checkPageBreak(20);
		}
		$this->pdf->Output();
	}
	//12
	public function RekapNotaReturPembelianPerDokumenBelumStatusMax($company, $plant, $sloc, $product, $supplier, $invoice, $startdate, $enddate) {
		parent::actionDownload();
		$sql = "select a.notagrrid,a.notagrrno,a.notagrrdate,b.pono,a.headernote,a.recordstatus
				from notagrr a
				join poheader b on b.poheaderid=a.poheaderid
				where a.recordstatus between 1 and (3-1) ".
				(($plant != '')? " and a.plantid = " . $plant:'').
				" order by a.recordstatus";
		$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($dataReader as $row) {
			$this->pdf->company = $company;
		}
		$this->pdf->title = 'Rekap Nota Retur Pembelian Per Dokumen Belum Status Max';
		$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');
		$this->pdf->setFont('Arial', 'B', 8);
		$this->pdf->sety($this->pdf->gety() + 10);
		$this->pdf->colalign = array('C', 'C', 'C', 'C', 'C', 'L', 'L');
		$this->pdf->setwidths(array(10, 20, 25, 25, 25, 60, 25, 25));
		$this->pdf->colheader = array('No', 'ID Transaksi', 'No Transaksi', 'Tanggal', 'No Referensi', 'Keterangan', 'Status');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('C', 'C', 'C', 'C', 'C', 'L', 'L');
		$totalnominal1 = 0;
		$i = 0;
		$totaldisc1 = 0;
		$totaljumlah1 = 0;
		foreach ($dataReader as $row) {
			$i += 1;
			$this->pdf->setFont('Arial', '', 7);
			$this->pdf->row(array($i, $row['notagrrid'], $row['notagrrno'], date(Yii::app()->params['dateviewfromdb'], strtotime($row['notagrrdate'])), $row['pono'], $row['headernote'], findstatusname("appcutar", $row['recordstatus'])));
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
				case 15:
					$this->bukupembelianXls($_GET['company'], $_GET['plant'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
					break;
				case 16:
					$this->bukupembelianInvoiceXls($_GET['company'], $_GET['plant'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
					break;
				default:
					echo GetCatalog('reportdoesnotexist');
			}
		}
	}
	
	public function bukupembelianXls($company, $plant, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per) {
		$this->menuname='bukupembelian';
    parent::actionDownxls();
		$sql = "
		select *
			from (
		SELECT j.plantcode, a.pono, a.podate, c.grno, c.grdate, d.invoiceapno, d.invoiceapdate, d.invoiceaptaxno, l.fullname as supplier, 
		c.sjsupplier, d.duedate, e.productname, b.qty as poqty, f.qty as grqty, k.qty as invqty, b.qty2 as poqty2, f.qty2 as grqty2, k.qty2 as invqty2,
		h.uomcode as uom, i.uomcode as uom2, b.price, getamountdetailbypo(a.poheaderid,b.podetailid) as totprice, a.headernote
			FROM poheader a
			left JOIN podetail b ON b.poheaderid = a.poheaderid
			LEFT JOIN grheader c on c.poheaderid = a.poheaderid
			LEFT JOIN invoiceap d on d.poheaderid = a.poheaderid
			LEFT JOIN product e ON e.productid = b.productid
			left join grdetail f on f.podetailid = b.podetailid and f.productid = b.productid
			left join invoiceapgr g on g.grheaderid = c.grheaderid
			left join unitofmeasure h on h.unitofmeasureid = b.uomid 
			left join unitofmeasure i on i.unitofmeasureid = b.uom2id
			left join plant j on j.plantid = a.plantid
			left join addressbook l on l.addressbookid = a.addressbookid
			left join invoiceapdetail k on k.invoiceapgrid = g.invoiceapgrid and k.productid = f.productid
			left join sloc m on m.slocid = b.slocid  
			WHERE a.podate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' ".
			(($plant != '')?" and a.plantid = ".$plant:'').
			" and coalesce(m.sloccode,'') like '%".$sloc."%'  
			and coalesce(e.productname,'') like '%".$product."%'
			and coalesce(d.invoiceapno,'') like '%".$invoice."%' 

			UNION

			SELECT j.plantcode, a.pono, a.podate, c.grno, c.grdate, '', '', '', l.fullname as supplier, 
		c.sjsupplier, '', e.productname, b.qty as poqty, ff.qty as grqty, '', '', '', '',
		h.uomcode as uom, '', b.price, getamountdetailbypo(a.poheaderid,b.pojasaid) as totprice, a.headernote 
			FROM poheader a
			left JOIN pojasa b ON b.poheaderid = a.poheaderid
			LEFT JOIN grheader c on c.poheaderid = a.poheaderid
			LEFT JOIN product e ON e.productid = b.productid
			left join grjasa ff on ff.pojasaid = b.pojasaid and ff.productid = b.productid
			left join unitofmeasure h on h.unitofmeasureid = b.uomid 
			left join plant j on j.plantid = a.plantid
			left join addressbook l on l.addressbookid = a.addressbookid
			left join sloc m on m.slocid = b.sloctoid
			WHERE a.podate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' ".
			(($plant != '')?" and a.plantid = ".$plant:'').
		" and coalesce(m.sloccode,'') like '%".$sloc."%' 
			and coalesce(e.productname,'') like '%".$product."%'
			) zz where zz.productname is not null order by pono asc
			";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();	
		$i=2;$ppid = 0;$proses=0;
		foreach($dataReader as $row){
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $i-1)
				->setCellValueByColumnAndRow(1, $i+1, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $i+1, $row['pono'])
				->setCellValueByColumnAndRow(3, $i+1, ($row['podate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['podate'])))
				->setCellValueByColumnAndRow(4, $i+1, $row['grno'])
				->setCellValueByColumnAndRow(5, $i+1, ($row['grdate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])))
				->setCellValueByColumnAndRow(6, $i+1, $row['supplier'])
				->setCellValueByColumnAndRow(7, $i+1, $row['sjsupplier'])
				->setCellValueByColumnAndRow(8, $i+1, $row['invoiceapno'])
				->setCellValueByColumnAndRow(9, $i+1,($row['invoiceapdate'] == null)?'': date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoiceapdate'])))
				->setCellValueByColumnAndRow(10, $i+1,($row['duedate'] == null)?'': date(Yii::app()->params['dateviewfromdb'], strtotime($row['duedate'])))
				->setCellValueByColumnAndRow(11, $i+1, $row['invoiceaptaxno'])
				->setCellValueByColumnAndRow(12, $i+1, $row['productname'])
				->setCellValueByColumnAndRow(13, $i+1, $row['poqty'])
				->setCellValueByColumnAndRow(14, $i+1, $row['grqty'])
				->setCellValueByColumnAndRow(15, $i+1, $row['invqty'])
				->setCellValueByColumnAndRow(16, $i+1, $row['uom'])
				->setCellValueByColumnAndRow(17, $i+1, $row['price'])
				->setCellValueByColumnAndRow(18, $i+1, ($row['price']*$row['poqty']))
				->setCellValueByColumnAndRow(19, $i+1, $row['totprice'])
			;
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
	
	public function bukupembelianInvoiceXls($company, $plant, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per) {
		$this->menuname='bukupembelianinvoice';
    parent::actionDownxls();
		$sql = "
		SELECT a.invoiceapid,a.plantcode,a.invoiceapdate,a.invoiceapno,a.invoiceaptaxno,c.fullname as supplier,e.sjsupplier,a.duedate,
			b.podate,a.pono,e.grdate,e.grno,f.productname,f.price,f.price*f.qty as totprice,f.qty as invqty,h.uomcode
			FROM invoiceap a
			LEFT JOIN poheader b ON b.poheaderid = a.poheaderid
			LEFT JOIN addressbook c ON c.addressbookid = b.addressbookid
			LEFT JOIN invoiceapgr d ON d.invoiceapid = a.invoiceapid 
			LEFT JOIN grheader e ON e.grheaderid = d.grheaderid 
			LEFT JOIN invoiceapdetail f ON f.invoiceapid = a.invoiceapid AND f.invoiceapgrid = d.invoiceapgrid
			LEFT JOIN product g ON g.productid = f.productid
			LEFT JOIN unitofmeasure h ON h.unitofmeasureid = f.uomid
			WHERE a.invoiceapdate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
			AND a.recordstatus = 3 ".
			(($plant != '')?"AND a.plantcode = ".$plant:'');
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();	
		$i=2;$ppid = 0;$proses=0;
		foreach($dataReader as $row){
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $i-1)
				->setCellValueByColumnAndRow(1, $i+1, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $i+1, $row['supplier'])
				->setCellValueByColumnAndRow(3, $i+1, $row['invoiceapno'])
				->setCellValueByColumnAndRow(4, $i+1,($row['invoiceapdate'] == null)?'': date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoiceapdate'])))
				->setCellValueByColumnAndRow(5, $i+1,($row['duedate'] == null)?'': date(Yii::app()->params['dateviewfromdb'], strtotime($row['duedate'])))
				->setCellValueByColumnAndRow(6, $i+1, $row['invoiceaptaxno'])
				->setCellValueByColumnAndRow(7, $i+1, $row['pono'])
				->setCellValueByColumnAndRow(8, $i+1, ($row['podate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['podate'])))
				->setCellValueByColumnAndRow(9, $i+1, $row['grno'])
				->setCellValueByColumnAndRow(10, $i+1, ($row['grdate'] == null)?'':date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])))
				->setCellValueByColumnAndRow(11, $i+1, $row['sjsupplier'])
				->setCellValueByColumnAndRow(12, $i+1, $row['productname'])
				->setCellValueByColumnAndRow(13, $i+1, $row['invqty'])
				->setCellValueByColumnAndRow(14, $i+1, $row['uomcode'])
				->setCellValueByColumnAndRow(15, $i+1, $row['price'])
				->setCellValueByColumnAndRow(16, $i+1, $row['totprice'])
			;
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}