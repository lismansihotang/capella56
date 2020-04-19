<?php
class ReportorderController extends Controller {
	public $menuname = 'reportorder';
	public function actionIndex() {
		$this->renderPartial('index',array());
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
					$this->RincianSalesOrderOutstanding($_GET['companyid'],$_GET['plantid'],$_GET['sloc'],$_GET['customer'],$_GET['sales'],$_GET['product'],$_GET['salesarea'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
					break;
				case 2 :
					$this->Topdownsales($_GET['companyid'],$_GET['plantid'],$_GET['sloc'],$_GET['customer'],$_GET['sales'],$_GET['product'],$_GET['salesarea'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
					break;
				case 3 :
					$this->Orderobtain($_GET['companyid'],$_GET['plantid'],$_GET['sloc'],$_GET['customer'],$_GET['sales'],$_GET['product'],$_GET['salesarea'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
					break;
				case 4 :
					$this->RekapSOPerDokumentBelumStatusMax($_GET['companyid'],$_GET['plantid'],$_GET['sloc'],$_GET['customer'],$_GET['sales'],$_GET['product'],$_GET['salesarea'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
					break;
				case 5 :
					$this->Topdownsalestac($_GET['companyid'],$_GET['plantid'],$_GET['sloc'],$_GET['customer'],$_GET['sales'],$_GET['product'],$_GET['salesarea'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
					break;
				default :
					echo getCatalog('reportdoesnotexist');
			}
		}
	}
	//1
	public function RincianSalesOrderOutstanding($companyid,$plantid,$sloc,$customer,$employee,$product,$salesarea,$startdate,$enddate,$per) {
		parent::actionDownload();
		$totalnominal2=0;$totalqty2=0;$totaldisc2=0;$totalnetto2=0;
		$totalsoqty22 = 0;
		$totalgiqty2 = 0;
		$totalgiqty22 = 0;
		$totalsppqty2 = 0;
		$totalsppqty22 = 0;
		$totalopqty2 = 0;
		$totalopqty22 = 0;
		$totalsqty2 = 0;
		$totalsqty22 = 0;
	
		$sql = "select distinct l.employeeid, l.fullname
			from sodetail c 
			left join soheader h on h.soheaderid=c.soheaderid
			join product e on e.productid=c.productid
			left join addressbook i on i.addressbookid=h.addressbookid
			left join paymentmethod j on j.paymentmethodid=h.paymentmethodid
			join sloc k on k.slocid=c.slocid
			left join sales o on o.salesid = h.salesid
			left join employee l on l.employeeid = o.employeeid
			where k.sloccode like '%".$sloc."%' and h.sono is not null
			and h.recordstatus = 3 and i.fullname like '%".$customer."%' and 
			h.plantid = ".$plantid." and e.productname like '%".$product."%' and l.fullname like '%".$employee."%' and
			h.sodate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
			and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' order by fullname";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		foreach($dataReader as $row) {
			$this->pdf->companyid = $companyid;
		}
		$this->pdf->title='Rincian Order & Shipment Outstanding (Rincian)';
		$this->pdf->subtitle = 'Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','A3'); 
		foreach($dataReader as $row) {
			$this->pdf->SetFont('Arial','B',10);
			$this->pdf->text(10,$this->pdf->gety()+5,'Sales');$this->pdf->text(40,$this->pdf->gety()+5,': '.$row['fullname']);
			$this->pdf->sety($this->pdf->gety()+8);
			$this->pdf->setFont('Arial','B',7);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(7,25,25,20,17,95,22,22,22,22,22,22,22,22,22,22));
			$this->pdf->colheader = array('No','OS','PO','Tgl OS','Tgl Delv','Nama Barang','OSqty','OSqty2','SJqty','SJqty2','OKqty','OKqty2','HPqty','HPqty2','Sisaqty','Sisa2qty');
			$this->pdf->RowHeader();
			$totalnominal1=0;$totalqty1=0;$totaldisc1=0;$totalnetto1=0;
			$totalsoqty21 = 0;
			$totalgiqty1 = 0;
			$totalgiqty21 = 0;
			$totalsppqty1 = 0;
			$totalsppqty21 = 0;
			$totalopqty1 = 0;
			$totalopqty21 = 0;
			$totalsqty1 = 0;
			$totalsqty21 = 0;
			
			$sql1 = " select distinct addressbookid, fullname,uomcode,uom2code
				from (select distinct sono, addressbookid, fullname,uomcode,uom2code
				from
				(select distinct r.addressbookid, r.fullname, a.qty,a.qty2,a.giqty,a.giqty2,g.uomcode,h.uomcode as uom2code,
				a.sppqty,a.sppqty2,a.opqty,a.opqty2,s.sono,s.pocustno,a.itemnote, (sum(a.qty)-sum(a.giqty)) as sisa, (sum(a.qty2)-sum(a.giqty2)) as sisa2
				from sodetail a
				join product f on f.productid = a.productid
				left join unitofmeasure g on g.unitofmeasureid = a.uomid
				left join unitofmeasure h on h.unitofmeasureid = a.uom2id
				join sloc j on j.slocid = a.slocid
				left join giheader p on p.soheaderid = a.soheaderid
				left join soheader s on s.soheaderid = a.soheaderid
				left join addressbook r on r.addressbookid = s.addressbookid
				left join salesarea t on t.salesareaid = r.salesareaid
				left join productplant n on n.productid = f.productid
				left join sales m on m.salesid = s.salesid
				where s.plantid = ".$plantid." and s.recordstatus = 3 and j.sloccode like '%".$sloc."%' 
				and f.productname like '%".$product."%' and m.employeeid = ".$row['employeeid']."
				and s.sodate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
				and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' group by sono
				order by sono )z where sisa > 0 )zz ";
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
			foreach($dataReader1 as $row1) {
				$this->pdf->SetFont('Arial','BI',9);
				$this->pdf->text(10,$this->pdf->gety()+5,'Customer');$this->pdf->text(40,$this->pdf->gety()+5,': '.$row1['fullname']);
				$sql2 = " select distinct sodate,delvdate,sono,pocustno,productname, uomcode,uom2code, sum(qty) as soqty,sum(qty2) as soqty2,
									sum(giqty) as giqty, sum(giqty2) as giqty2, sum(sppqty) as sppqty, sum(sppqty2) as sppqty2
									, sum(opqty) as opqty, sum(opqty2) as opqty2, (sum(qty)-sum(giqty)) as sisa, (sum(qty2)-sum(giqty2)) as sisa2 ,itemnote
								from
								(select distinct s.sodate,a.delvdate,f.productname ,g.uomcode,h.uomcode as uom2code, a.qty,a.qty2,a.giqty,a.giqty2,
								a.sppqty,a.sppqty2,a.opqty,a.opqty2,s.sono,s.pocustno,a.itemnote
								from sodetail a
								join product f on f.productid = a.productid
								join unitofmeasure g on g.unitofmeasureid = a.uomid
								join unitofmeasure h on h.unitofmeasureid = a.uom2id
								join sloc j on j.slocid = a.slocid
								left join giheader p on p.soheaderid = a.soheaderid
								left join soheader s on s.soheaderid = a.soheaderid
								left join addressbook r on r.addressbookid = s.addressbookid
								left join salesarea t on t.salesareaid = r.salesareaid
								left join productplant n on n.productid = f.productid
								left join sales m on m.salesid = s.salesid
								where s.plantid = ".$plantid." and s.recordstatus = 3 and j.sloccode like '%".$sloc."%' 
								and f.productname like '%".$product."%' and m.employeeid = ".$row['employeeid']." and s.addressbookid = ".$row1['addressbookid']."
								and s.sodate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
								order by sono )z group by sono,productname";
				$dataReader2=Yii::app()->db->createCommand($sql2)->queryAll();
				$totaldisc=0;$totalqty=0;$totalnominal=0;$totalnetto=0;$i=0;
				$totalsoqty2 = 0;
				$totalgiqty = 0;
				$totalgiqty2 = 0;
				$totalsppqty = 0;
				$totalsppqty2 = 0;
				$totalopqty = 0;
				$totalopqty2 = 0;
				$totalsqty = 0;
				$totalsqty2 = 0;
				$this->pdf->sety($this->pdf->gety()+7);
				$this->pdf->coldetailalign = array('L','L','L','L','C','L','R','R','R','R','R','R','R','R','R','R');
				$this->pdf->setFont('Arial','',7);
				$totalnominal = 0;$totalqty = 0;$totaldisc = 0;$totalnetto = 0;
				$totalsoqty2 = 0;
				$totalgiqty = 0;
				$totalgiqty2 = 0;
				$totalsppqty = 0;
				$totalsppqty2 = 0;
				$totalopqty = 0;
				$totalopqty2 = 0;
				$totalsqty = 0;
				$totalsqty2 = 0;
				//('No','OS','Tgl OS','Tgl Delv','Nama Barang','SOqty','SOqty2','GIqty','GIqty2','OKqty','OKqty2','HPqty','HPqty2','Sisaqty','Sisa2qty')
				foreach($dataReader2 as $row2) {
					$i+=1;
					$this->pdf->row(array(
						$i,
						$row2['sono'],
						$row2['pocustno'],
						date(Yii::app()->params['dateviewfromdb'], strtotime($row2['sodate'])),
						date(Yii::app()->params['dateviewfromdb'], strtotime($row2['delvdate'])),
						$row2['productname'],
						Yii::app()->format->formatCurrency($row2['soqty']) .' '.$row2['uomcode'],
						Yii::app()->format->formatCurrency($row2['soqty2']) .' '.$row2['uom2code'],
						Yii::app()->format->formatCurrency($row2['giqty']) .' '.$row2['uomcode'],
						Yii::app()->format->formatCurrency($row2['giqty2']) .' '.$row2['uom2code'],
						Yii::app()->format->formatCurrency($row2['sppqty']) .' '.$row2['uomcode'],
						Yii::app()->format->formatCurrency($row2['sppqty2']) .' '.$row2['uom2code'],
						Yii::app()->format->formatCurrency($row2['opqty']) .' '.$row2['uomcode'],
						Yii::app()->format->formatCurrency($row2['opqty2']) .' '.$row2['uom2code'],
						Yii::app()->format->formatCurrency($row2['sisa']) .' '.$row2['uomcode'],
						Yii::app()->format->formatCurrency($row2['sisa2']) .' '.$row2['uom2code']
					));
					$totalqty += $row2['soqty'];
					$totalsoqty2 += $row2['soqty2'];
					$totalgiqty += $row2['giqty'];
					$totalgiqty2 += $row2['giqty2'];
					$totalsppqty += $row2['sppqty'];
					$totalsppqty2 += $row2['sppqty2'];
					$totalopqty += $row2['opqty'];
					$totalopqty2 += $row2['opqty2'];
					$totalsqty += $row2['sisa'];
					$totalsqty2 += $row2['sisa2'];
				}
				$this->pdf->setFont('Arial','B',7);
				$this->pdf->row(array(
					'','','','','','TOTAL '.$row1['fullname'],
					Yii::app()->format->formatCurrency($totalqty) .' '.$row1['uomcode'],
					Yii::app()->format->formatCurrency($totalsoqty2) .' '.$row1['uom2code'],
					Yii::app()->format->formatCurrency($totalgiqty) .' '.$row1['uomcode'],
					Yii::app()->format->formatCurrency($totalgiqty2) .' '.$row1['uom2code'],
					Yii::app()->format->formatCurrency($totalsppqty) .' '.$row1['uomcode'],
					Yii::app()->format->formatCurrency($totalsppqty2) .' '.$row1['uom2code'],
					Yii::app()->format->formatCurrency($totalopqty) .' '.$row1['uomcode'],
					Yii::app()->format->formatCurrency($totalopqty2) .' '.$row1['uom2code'],
					Yii::app()->format->formatCurrency($totalsqty) .' '.$row1['uomcode'],
					Yii::app()->format->formatCurrency($totalsqty2) .' '.$row1['uom2code']
				));
				$totalqty1 += $totalqty;
				$totalsoqty21 += $totalsoqty2;
				$totalgiqty1 += $totalgiqty;
				$totalgiqty21 += $totalqty2;
				$totalsppqty1 += $totalsppqty;
				$totalsppqty21 += $totalsppqty2;
				$totalopqty1 += $totalopqty;
				$totalopqty21 += $totalopqty2;
				$totalsqty1 += $totalsqty;
				$totalsqty21 += $totalsqty2;
			}
			$this->pdf->setFont('Arial','BI',8,5);
			$this->pdf->row(array(
				'','','','','','TOTAL '.$row['fullname'],
					Yii::app()->format->formatCurrency($totalqty1) .' '.$row1['uomcode'],
					Yii::app()->format->formatCurrency($totalsoqty21) .' '.$row1['uom2code'],
					Yii::app()->format->formatCurrency($totalgiqty1) .' '.$row1['uomcode'],
					Yii::app()->format->formatCurrency($totalgiqty21) .' '.$row1['uom2code'],
					Yii::app()->format->formatCurrency($totalsppqty1) .' '.$row1['uomcode'],
					Yii::app()->format->formatCurrency($totalsppqty21) .' '.$row1['uom2code'],
					Yii::app()->format->formatCurrency($totalopqty1) .' '.$row1['uomcode'],
					Yii::app()->format->formatCurrency($totalopqty21) .' '.$row1['uom2code'],
					Yii::app()->format->formatCurrency($totalsqty1) .' '.$row1['uomcode'],
					Yii::app()->format->formatCurrency($totalsqty21) .' '.$row1['uom2code']
			));
			$totalqty2 += $totalqty1;
			$totalsoqty22 += $totalsoqty21;
			$totalgiqty2 += $totalgiqty1;
			$totalgiqty22 += $totalgiqty21;
			$totalsppqty2 += $totalsppqty1;
			$totalsppqty2 += $totalsppqty21;
			$totalopqty2 += $totalopqty1;
			$totalopqty22 += $totalopqty21;
			$totalsqty2 += $totalsqty1;
			$totalsqty22 += $totalsqty21;
			$this->pdf->sety($this->pdf->gety()+5);
			$this->pdf->checkPageBreak(20);
		}
		$this->pdf->colalign = array('C','R','R','R','R','R','R','R','R','R','R');
		$this->pdf->setwidths(array(25,40,40,40,40,40,40,40,40,40,40));
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->row(array(
			'GRAND TOTAL',
			'OSqty'.' '.Yii::app()->format->formatCurrency($totalqty2),
			'OSqty2'.' '.Yii::app()->format->formatCurrency($totalsoqty22),
			'SJqty'.' '.Yii::app()->format->formatCurrency($totalgiqty2),
			'SJqty'.' '.Yii::app()->format->formatCurrency($totalgiqty22),
			'OKqty'.' '.Yii::app()->format->formatCurrency($totalsppqty2),
			'OKqty'.' '.Yii::app()->format->formatCurrency($totalsppqty22),
			'HPqty'.' '.Yii::app()->format->formatCurrency($totalsppqty2),
			'HPqty'.' '.Yii::app()->format->formatCurrency($totalopqty22),
			'SISAqty'.' '.Yii::app()->format->formatCurrency($totalsqty2),
			'SISAqty'.' '.Yii::app()->format->formatCurrency($totalsqty22),
		));
		$this->pdf->Output();
	}
	public function RekapSOPerDokumentBelumStatusMax($companyid,$plantid,$sloc,$customer,$employee,$product,$salesarea,$startdate,$enddate,$per) {
		parent::actionDownload();
		$sql = "select a.soheaderid, f.plantcode, a.sono, a.sodate, b.companyid, a.addressbookid, a.recordstatus, a.statusname, b.companyname, c.fullname
		from soheader a
		join plant f on f.plantid = a.plantid 
        join company b on b.companyid = f.companyid
        join addressbook c on c.addressbookid = a.addressbookid
        join sales e on e.salesid = a.salesid and e.plantid = a.plantid 
        join employee d on d.employeeid = e.employeeid
        where a.recordstatus < 3 and a.recordstatus <> 0
        and f.companyid=".$companyid."
        and f.plantid=".$plantid."
        and c.fullname like '%".$customer."%'
        and d.fullname like '%".$employee."%'
	and a.sodate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
		and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'";
        
            $dataReader=Yii::app()->db->createCommand($sql)->queryAll();

       
			$this->pdf->title='Rekap SO Per Dokumen Status Belum Max';
			$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			    $this->pdf->AddPage('P');
				$this->pdf->sety($this->pdf->gety()+10);
				$this->pdf->setFont('Arial','B',8);
				$this->pdf->colalign = array('C','C','C','C','C','C');
				$this->pdf->setwidths(array(10,20,20,50,45,45));
				$this->pdf->colheader = array('No','NO SO','Tgl SO','Perusahaan','Customer','Status');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('L','L','L','L','L','L');
				$this->pdf->setFont('Arial','',8);	
        $i=0;
        foreach($dataReader as $row){
            $i+=1;
            $this->pdf->setFont('Arial','',8);
            $this->pdf->row(array(
                $row['soheaderid'],
                $row['sono'],
               date(Yii::app()->params['dateviewfromdb'],strtotime($row['sodate'])),
                $row['companyname'],
                $row['fullname'],
                $row['statusname']));
                                  
        }
		$this->pdf->Output();	
	}
	public function Topdownsales($companyid,$plantid,$sloc,$customer,$employee,$product,$salesarea,$startdate,$enddate,$per) {
		parent::actionDownload();
		$totalqty1=0;
		$totalqty12=0;
		$totalqty13=0;
		$totalqty14=0;
		$totalnominal1=0;
		$totalnetto1=0;
		$sql = "
			SELECT *,totalvalue / qty1 AS hargapersatuan1
			FROM 
			(
			SELECT b.addressbookid,c.fullname,a.uomid,d.uomcode, SUM(e.qty) AS qty1,SUM(e.qty * a.price) AS totalvalue
			FROM sodetail a 
			JOIN soheader b ON b.soheaderid = a.soheaderid 
			JOIN addressbook c ON c.addressbookid = b.addressbookid
			JOIN unitofmeasure d ON d.unitofmeasureid = a.uomid 
			join gidetail e on e.sodetailid = a.sodetailid and e.productid = a.productid 
			join giheader f on f.giheaderid = e.giheaderid and f.soheaderid = b.soheaderid 
			WHERE b.recordstatus = 3
			and f.recordstatus = 3 
			and f.gidate BETWEEN '". date(Yii::app()->params['datetodb'], strtotime($startdate))."'
			and	'".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND b.plantid = ".$plantid."
			and coalesce(c.fullname,'') like '%".$customer."%' 
			GROUP BY b.addressbookid,c.fullname,a.uomid
			) z order by totalvalue desc,fullname asc";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$this->pdf->companyid = $companyid;
		$this->pdf->title='Top Down Sales Performance';
		$this->pdf->subtitle = 'Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P','Legal'); 
		$i=1; $totalgrandvalue = 0; $totalvalueroll = 0;$totalvaluemtr = 0;$totalvaluepcs = 0;$totalqtyroll = 0;$totalqtymtr = 0;$totalqtypcs = 0;
		$totalvaluekg = 0;$totalqtykg = 0;$totalvaluedrum = 0;$totalqtydrum = 0;$totalvaluelbr = 0;$totalqtylbr = 0;$totalvaluerim = 0;$totalqtyrim = 0;
		$totalvaluebtg = 0;$totalqtybtg = 0;
		$this->pdf->sety($this->pdf->gety()+10);
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->colalign = array('C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(8,60,25,30,30,20,30));
		$this->pdf->colheader = array('No','Pelanggan','Satuan','Qty 1','Total','%','Harga/Satuan');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('L','L','R','R','R','R','R');
		$this->pdf->setFont('Arial','',8);	
		foreach($dataReader as $row) {
			switch ($row['uomid']) {
				case 1:
					$totalvaluekg += $row['totalvalue'];
					$totalqtykg += $row['qty1'];
					break;
				case 2:
					$totalvaluepcs += $row['totalvalue'];
					$totalqtypcs += $row['qty1'];
					break;
				case 6:
					$totalvaluemtr += $row['totalvalue'];
					$totalqtymtr += $row['qty1'];
					break;
				case 7:
					$totalvalueroll += $row['totalvalue'];
					$totalqtyroll += $row['qty1'];
					break;
				case 20:
					$totalvaluelbr += $row['totalvalue'];
					$totalqtylbr += $row['qty1'];
					break;
				case 21:
					$totalvaluebtg += $row['totalvalue'];
					$totalqtybtg += $row['qty1'];
					break;
				case 22:
					$totalvaluebtg += $row['totalvalue'];
					$totalqtybtg += $row['qty1'];
					break;
				case 29:
					$totalvaluerim += $row['totalvalue'];
					$totalqtyrim += $row['qty1'];
					break;
				case 32:
					$totalvaluedrum += $row['totalvalue'];
					$totalqtydrum += $row['qty1'];
					break;
			}
			$totalgrandvalue += $row['totalvalue'];
		}
		foreach($dataReader as $row) {
			$this->pdf->row(array(
				$i,
				$row['fullname'],
				$row['uomcode'],
				Yii::app()->format->formatNumber($row['qty1']),
				Yii::app()->format->formatCurrency($row['totalvalue']),
				Yii::app()->format->formatNumber((($row['totalvalue'] / $totalgrandvalue) * 100)).' %',
				Yii::app()->format->formatCurrency($row['hargapersatuan1']),
			));
			$i++;
		}
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->row(array(
			'',
			'Total',
			'',
			'',
			Yii::app()->format->formatCurrency($totalgrandvalue),
			'',
			'',
		));
		if ($totalqtyroll > 0) {
			$this->pdf->row(array(
				'',
				'Total Roll',
				'',
				Yii::app()->format->formatNumber($totalqtyroll),
				Yii::app()->format->formatCurrency($totalvalueroll),
				'',
				'',
			));
		}
		if ($totalqtykg > 0) {
			$this->pdf->row(array(
				'',
				'Total Kg',
				'',
				Yii::app()->format->formatNumber($totalqtykg),
				Yii::app()->format->formatCurrency($totalvaluekg),
				'',
				'',
			));
		}
		if ($totalqtypcs > 0) {
			$this->pdf->row(array(
				'',
				'Total Pcs',
				'',
				Yii::app()->format->formatNumber($totalqtypcs),
				Yii::app()->format->formatCurrency($totalvaluepcs),
				'',
				'',
			));
		}
		if ($totalqtymtr > 0) {
			$this->pdf->row(array(
				'',
				'Total Mtr',
				'',
				Yii::app()->format->formatNumber($totalqtymtr),
				Yii::app()->format->formatCurrency($totalvaluemtr),
				'',
				'',
			));
		}
		if ($totalqtydrum > 0) {
			$this->pdf->row(array(
				'',
				'Total Drum',
				'',
				Yii::app()->format->formatNumber($totalqtydrum),
				Yii::app()->format->formatCurrency($totalvaluedrum),
				'',
				'',
			));
		}
		if ($totalqtylbr > 0) {
			$this->pdf->row(array(
				'',
				'Total Lbr',
				'',
				Yii::app()->format->formatNumber($totalqtylbr),
				Yii::app()->format->formatCurrency($totalvaluelbr),
				'',
				'',
			));
		}
		if ($totalqtyrim > 0) {
			$this->pdf->row(array(
				'',
				'Total Rim',
				'',
				Yii::app()->format->formatNumber($totalqtyrim),
				Yii::app()->format->formatCurrency($totalvaluerim),
				'',
				'',
			));
		}
		if ($totalqtybtg > 0) {
			$this->pdf->row(array(
				'',
				'Total Btg',
				'',
				Yii::app()->format->formatNumber($totalqtybtg),
				Yii::app()->format->formatCurrency($totalvaluebtg),
				'',
				'',
			));
		}
		$this->pdf->Output();
	}
	public function Topdownsalestac($companyid,$plantid,$sloc,$customer,$employee,$product,$salesarea,$startdate,$enddate,$per) {
		parent::actionDownload();
		$totalqty1=0;
		$totalqty12=0;
		$totalqty13=0;
		$totalqty14=0;
		$totalqty3=0;
		$totalnominal1=0;
		$totalnetto1=0;
		$sql = "
			SELECT *,totalvalue / qty1 AS hargapersatuan1,totalvalue / qty3 as hargapersatuan3 
			FROM 
			(
			SELECT b.addressbookid,c.fullname,a.uomid,d.uomcode,g.uomcode as uom3code, SUM(e.qty) AS qty1,SUM(e.qty * a.price) AS totalvalue, sum(e.qty3) as qty3
			FROM sodetail a 
			JOIN soheader b ON b.soheaderid = a.soheaderid 
			JOIN addressbook c ON c.addressbookid = b.addressbookid
			JOIN unitofmeasure d ON d.unitofmeasureid = a.uomid 
			join gidetail e on e.sodetailid = a.sodetailid and e.productid = a.productid 
			join giheader f on f.giheaderid = e.giheaderid and f.soheaderid = b.soheaderid 
			left JOIN unitofmeasure g ON g.unitofmeasureid = a.uom3id 
			WHERE b.recordstatus = 3
			and f.recordstatus = 3 
			and f.gidate BETWEEN '". date(Yii::app()->params['datetodb'], strtotime($startdate))."'
			and	'".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND b.plantid = ".$plantid."
			and coalesce(c.fullname,'') like '%".$customer."%' 
			GROUP BY b.addressbookid,c.fullname,a.uomid
			) z order by totalvalue desc,fullname asc";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$this->pdf->companyid = $companyid;
		$this->pdf->title='Top Down Sales Performance (TAC)';
		$this->pdf->subtitle = 'Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','A4'); 
		$i=1; $totalgrandvalue = 0; $totalvalueroll = 0;$totalvaluemtr = 0;$totalvaluepcs = 0;$totalqtyroll = 0;$totalqtymtr = 0;$totalqtypcs = 0;
		$totalvaluekg = 0;$totalqtykg = 0;$totalvaluedrum = 0;$totalqtydrum = 0;$totalvaluelbr = 0;$totalqtylbr = 0;$totalvaluerim = 0;$totalqtyrim = 0;
		$totalvaluebtg = 0;$totalqtybtg = 0;
		$this->pdf->sety($this->pdf->gety()+10);
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(8,70,25,25,25,20,30,20,30,30));
		$this->pdf->colheader = array('No','Pelanggan','Satuan','Qty 1','Qty 3','Satuan 3','Total','%','Harga/Satuan 1','Harga/Satuan 3');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('L','L','R','R','R','L','R','R','R','R');
		$this->pdf->setFont('Arial','',8);	
		foreach($dataReader as $row) {
			$totalqty3 += $row['qty3'];
			switch ($row['uomid']) {
				case 1:
					$totalvaluekg += $row['totalvalue'];
					$totalqtykg += $row['qty1'];
					break;
				case 2:
					$totalvaluepcs += $row['totalvalue'];
					$totalqtypcs += $row['qty1'];
					break;
				case 6:
					$totalvaluemtr += $row['totalvalue'];
					$totalqtymtr += $row['qty1'];
					break;
				case 7:
					$totalvalueroll += $row['totalvalue'];
					$totalqtyroll += $row['qty1'];
					break;
				case 20:
					$totalvaluelbr += $row['totalvalue'];
					$totalqtylbr += $row['qty1'];
					break;
				case 21:
					$totalvaluebtg += $row['totalvalue'];
					$totalqtybtg += $row['qty1'];
					break;
				case 22:
					$totalvaluebtg += $row['totalvalue'];
					$totalqtybtg += $row['qty1'];
					break;
				case 29:
					$totalvaluerim += $row['totalvalue'];
					$totalqtyrim += $row['qty1'];
					break;
				case 32:
					$totalvaluedrum += $row['totalvalue'];
					$totalqtydrum += $row['qty1'];
					break;
			}
			$totalgrandvalue += $row['totalvalue'];
		}
		foreach($dataReader as $row) {
			$this->pdf->row(array(
				$i,
				$row['fullname'],
				$row['uomcode'],
				Yii::app()->format->formatNumber($row['qty1']),
				Yii::app()->format->formatNumber($row['qty3']),
				$row['uom3code'],
				Yii::app()->format->formatCurrency($row['totalvalue']),
				Yii::app()->format->formatNumber((($row['totalvalue'] / $totalgrandvalue) * 100)).' %',
				Yii::app()->format->formatCurrency($row['hargapersatuan1']),
				Yii::app()->format->formatCurrency($row['hargapersatuan3']),
			));
			$i++;
		}
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->row(array(
			'',
			'Total',
			'',
			'',
			'',
			'',
			Yii::app()->format->formatCurrency($totalgrandvalue),
			'',
			'',
			'',
		));
		if ($totalqtyroll > 0) {
			$this->pdf->row(array(
				'',
				'Total Roll',
				'',
				'',
				'',
				Yii::app()->format->formatNumber($totalqtyroll),
				Yii::app()->format->formatCurrency($totalvalueroll),
				'',
				'',
				'',
			));
		}
		if ($totalqtykg > 0) {
			$this->pdf->row(array(
				'',
				'Total Kg',
				'',
				'',
				'',
				Yii::app()->format->formatNumber($totalqtykg),
				Yii::app()->format->formatCurrency($totalvaluekg),
				'',
				'',
				'',
			));
		}
		if ($totalqtypcs > 0) {
			$this->pdf->row(array(
				'',
				'Total Pcs',
				'',
				'',
				'',
				Yii::app()->format->formatNumber($totalqtypcs),
				Yii::app()->format->formatCurrency($totalvaluepcs),
				'',
				'',
				'',
			));
		}
		if ($totalqtymtr > 0) {
			$this->pdf->row(array(
				'',
				'Total Mtr',
				'',
				'',
				'',
				Yii::app()->format->formatNumber($totalqtymtr),
				Yii::app()->format->formatCurrency($totalvaluemtr),
				'',
				'',
				'',
			));
		}
		if ($totalqtydrum > 0) {
			$this->pdf->row(array(
				'',
				'Total Drum',
				'',
				'',
				'',
				Yii::app()->format->formatNumber($totalqtydrum),
				Yii::app()->format->formatCurrency($totalvaluedrum),
				'',
				'',
				'',
			));
		}
		if ($totalqtylbr > 0) {
			$this->pdf->row(array(
				'',
				'Total Lbr',
				'',
				'',
				'',
				Yii::app()->format->formatNumber($totalqtylbr),
				Yii::app()->format->formatCurrency($totalvaluelbr),
				'',
				'',
				'',
			));
		}
		if ($totalqtyrim > 0) {
			$this->pdf->row(array(
				'',
				'Total Rim',
				'',
				'',
				'',
				Yii::app()->format->formatNumber($totalqtyrim),
				Yii::app()->format->formatCurrency($totalvaluerim),
				'',
				'',
				'',
			));
		}
		if ($totalqtybtg > 0) {
			$this->pdf->row(array(
				'',
				'Total Btg',
				'',
				'',
				'',
				Yii::app()->format->formatNumber($totalqtybtg),
				Yii::app()->format->formatCurrency($totalvaluebtg),
				'',
				'',
				'',
			));
		}
		$this->pdf->Output();
	}
	public function Orderobtain($companyid,$plantid,$sloc,$customer,$employee,$product,$salesarea,$startdate,$enddate,$per) {
		parent::actionDownload();
		$this->pdf->isrepeat = 1;
		$sql = "
			SELECT distinct a.salesid,b.fullname
			FROM sales a
			LEFT JOIN employee b ON b.employeeid = a.employeeid
			where a.plantid = ".$_GET['plantid'];
		$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
		$this->pdf->companyid = $companyid;
		$this->pdf->title='Order Obtain';
		$this->pdf->subtitle = 'Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','legal'); 
		$totalgrandqty=0;
		$totalgrandqty2=0;
		$totalgrandqty3=0;
		$totalgrandvalue=0;
		foreach($dataReader as $row) {
			$this->pdf->SetFont('Arial','B',10);
			$this->pdf->text(10,$this->pdf->gety()+5,'Sales');$this->pdf->text(40,$this->pdf->gety()+5,': '.$row['fullname']);
			$totalsalesqty=0;
			$totalsalesqty2=0;
			$totalsalesqty3=0;
			$totalsalesvalue=0;

			$sql1 = "
				select distinct a.addressbookid,a.fullname
				from addressbook a 
				where a.addressbookid in (
					select distinct za.addressbookid
					from soheader za 
					left join addressbook zb on zb.addressbookid = za.addressbookid 
					where za.plantid = ".$plantid." 
					and za.recordstatus >= 3
					and za.sodate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
					and zb.fullname like '%".$customer."%' 
					and za.salesid = ".$row['salesid'].
				")";
			$dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
			foreach($dataReader1 as $row1) {
				$this->pdf->SetFont('Arial','B',10);
				$this->pdf->text(10,$this->pdf->gety()+10,'Customer');$this->pdf->text(40,$this->pdf->gety()+10,': '.$row1['fullname']);
				$this->pdf->sety($this->pdf->gety()+15);

				$totalqty1 = 0;
				$totalqty2 = 0;
				$totalqty3 = 0;
				$totalvalue = 0;
				
				$sql2 = "
					select a.sono,a.sodate,a.pocustno,c.productname,b.qty,d.uomcode,b.qty2,e.uomcode as uom2code,b.price,b.qty*b.price as total,b.delvdate,b.qty3,f.uomcode as uom3code 
					from soheader a 
					left join sodetail b on b.soheaderid = a.soheaderid 
					left join product c on c.productid = b.productid 
					left join unitofmeasure d on d.unitofmeasureid = b.uomid 
					left join unitofmeasure e on e.unitofmeasureid = b.uom2id 
					left join unitofmeasure f on f.unitofmeasureid = b.uom3id 
					where a.plantid = ".$plantid." 
					and a.recordstatus >= 3
					and a.sodate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
					and a.addressbookid = ".$row1['addressbookid']." 
					and a.salesid = ".$row['salesid'];
				$dataReader2 = Yii::app()->db->createCommand($sql2)->queryAll();
				$i=1;
				$this->pdf->SetFont('Arial','',8);
				$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C');
				$this->pdf->setwidths(array(7,35,30,20,100,20,25,25,25,25,30,35));
				$this->pdf->colheader = array('No','OS','PO','Tgl OS','Nama Artikel','Tgl Kirim','Qty 1','Qty 2','Qty3','Harga','Total');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','R','R','R','R');
				foreach($dataReader2 as $row2) {
					$this->pdf->row(array(
						$i,
						$row2['sono'],
						$row2['pocustno'],
						date(Yii::app()->params['dateviewfromdb'], strtotime($row2['sodate'])),
						$row2['productname'],
						date(Yii::app()->params['dateviewfromdb'], strtotime($row2['delvdate'])),
						Yii::app()->format->formatNumber($row2['qty']).' '.$row2['uomcode'],
						Yii::app()->format->formatNumber($row2['qty2']).' '.$row2['uom2code'],
						Yii::app()->format->formatNumber($row2['qty3']).' '.$row2['uom3code'],
						Yii::app()->format->formatCurrency($row2['price']),
						Yii::app()->format->formatCurrency($row2['total']),
					));
					$totalgrandqty += $row2['qty'];
					$totalgrandqty2 += $row2['qty2'];
					$totalgrandqty3 += $row2['qty3'];
					$totalgrandvalue += $row2['total'];
					$totalsalesqty += $row2['qty'];
					$totalsalesqty2 += $row2['qty2'];
					$totalsalesqty3 += $row2['qty3'];
					$totalsalesvalue += $row2['total'];
					$totalqty1 += $row2['qty'];
					$totalqty2 += $row2['qty2'];
					$totalvalue += $row2['total'];
					$i++;
				}
				$this->pdf->sety($this->pdf->gety()+5);
				$this->pdf->SetFont('Arial','B',8);
				$this->pdf->row(array(
					'',
					'',
					'',
					'',
					'Total '.$row1['fullname'],
					'',
					Yii::app()->format->formatCurrency($totalqty1),
					Yii::app()->format->formatCurrency($totalqty2),
					Yii::app()->format->formatCurrency($totalqty3),
					'',
					Yii::app()->format->formatCurrency($totalvalue),
				));
				$this->pdf->CheckNewPage(30);
			}
			$this->pdf->sety($this->pdf->gety()+5);
			$this->pdf->CheckNewPage(30);
			$this->pdf->SetFont('Arial','B',8);
			$this->pdf->row(array(
				'',
				'',
				'',
				'',
				'Total Sales '.$row['fullname'],
				'',
				Yii::app()->format->formatCurrency($totalsalesqty),
				Yii::app()->format->formatCurrency($totalsalesqty2),
				Yii::app()->format->formatCurrency($totalsalesqty3),
				'',
				Yii::app()->format->formatCurrency($totalsalesvalue),
			));
			$this->pdf->AddPage('L','legal'); 
		}
		$this->pdf->SetFont('Arial','B',8);
		$this->pdf->row(array(
			'',
			'',
			'',
			'',
			'Grand Total ',
			'',
			Yii::app()->format->formatCurrency($totalgrandqty),
			Yii::app()->format->formatCurrency($totalgrandqty2),
			Yii::app()->format->formatCurrency($totalgrandqty3),
			'',
			Yii::app()->format->formatCurrency($totalgrandvalue),
		));
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
				case 1 :
					$this->RincianSalesOrderOutstandingXls($_GET['companyid'],$_GET['plantid'],$_GET['sloc'],$_GET['customer'],$_GET['sales'],$_GET['product'],$_GET['salesarea'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
					break;
				case 2 :
					$this->TopdownsalesXls($_GET['companyid'],$_GET['plantid'],$_GET['sloc'],$_GET['customer'],$_GET['sales'],$_GET['product'],$_GET['salesarea'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
					break;
				case 3 :
					$this->OrderobtainXls($_GET['companyid'],$_GET['plantid'],$_GET['sloc'],$_GET['customer'],$_GET['sales'],$_GET['product'],$_GET['salesarea'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
					break;
				default :
					echo getCatalog('reportdoesnotexist');
			}
		}
	}
	public function RincianSalesOrderOutstandingXls($companyid,$plantid,$sloc,$customer,$employee,$product,$salesarea,$startdate,$enddate,$per) {
		$this->menuname='rinciansooutstanding';
    parent::actionDownxls();
		$sql = "SELECT l.fullname AS salesname,i.fullname as customername,h.sono,h.pocustno,h.sodate,c.delvdate,e.productname,c.qty AS qtyos,c.qty2 AS qtyos2,
			c.giqty,c.giqty2,c.opqty,c.opqty2,c.qty - c.giqty AS qtysisa,c.qty2 - c.giqty2 AS qtysisa2,
			m.uomcode,n.uomcode AS uom2code,c.sppqty,c.sppqty2,p.plantcode
			from sodetail c 
			left join soheader h on h.soheaderid=c.soheaderid
			left join product e on e.productid=c.productid
			left join addressbook i on i.addressbookid=h.addressbookid
			left join paymentmethod j on j.paymentmethodid=h.paymentmethodid
			left join sloc k on k.slocid=c.slocid
			left join sales o on o.salesid = h.salesid
			left join employee l on l.employeeid = o.employeeid 
			LEFT JOIN unitofmeasure m ON m.unitofmeasureid = c.uomid
			LEFT JOIN unitofmeasure n ON n.unitofmeasureid = c.uom2id
			left join plant p on p.plantid = k.plantid 
			WHERE h.sodate BETWEEN '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' AND '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
			and p.plantid = ".$plantid." 
			and coalesce(k.sloccode,'') like '%".$sloc."%' 
			and coalesce(e.productname,'') like '%".$product."%'
			and coalesce(i.fullname,'') like '%".$customer."%'
			and h.recordstatus >= 3
			ORDER BY h.sodate asc,h.sono asc";
		
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();	
		$i          = 2;
    foreach ($dataReader as $row) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $i-1)
				->setCellValueByColumnAndRow(1, $i+1, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $i+1, $row['salesname'])
				->setCellValueByColumnAndRow(3, $i+1, $row['customername'])
				->setCellValueByColumnAndRow(4, $i+1, $row['sono'])
				->setCellValueByColumnAndRow(5, $i+1, $row['pocustno'])
				->setCellValueByColumnAndRow(6, $i+1, $row['sodate'])
				->setCellValueByColumnAndRow(7, $i+1, $row['delvdate'])
				->setCellValueByColumnAndRow(8, $i+1, $row['productname'])
				->setCellValueByColumnAndRow(9, $i+1, $row['uomcode'])
				->setCellValueByColumnAndRow(10, $i+1, $row['qtyos'])
				->setCellValueByColumnAndRow(11, $i+1, $row['giqty'])
				->setCellValueByColumnAndRow(12, $i+1, $row['sppqty'])
				->setCellValueByColumnAndRow(13, $i+1, $row['opqty'])
				->setCellValueByColumnAndRow(14, $i+1, $row['qtysisa'])
				->setCellValueByColumnAndRow(15, $i+1, $row['uom2code'])
				->setCellValueByColumnAndRow(16, $i+1, $row['qtyos2'])
				->setCellValueByColumnAndRow(17, $i+1, $row['giqty2'])
				->setCellValueByColumnAndRow(18, $i+1, $row['sppqty2'])
				->setCellValueByColumnAndRow(19, $i+1, $row['opqty2'])
				->setCellValueByColumnAndRow(20, $i+1, $row['qtysisa2'])
			;
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
	public function OrderobtainXls($companyid,$plantid,$sloc,$customer,$employee,$product,$salesarea,$startdate,$enddate,$per) {
		$this->menuname='orderobtain';
    parent::actionDownxls();
		$totalqty1=0;
		$totalqty12=0;
		$totalqty13=0;
		$totalqty14=0;
		$totalnominal1=0;
		$totalnetto1=0;
		$sql = "
			SELECT h.plantcode, g.fullname AS salesname, c.fullname as customername, b.sono,b.pocustno,b.sodate,e.productname,d.uomcode, a.qty AS qty1,a.qty * a.price AS totalvalue,
			a.qty2,a.price,a.delvdate,i.uomcode as uom2code,a.qty3 ,j.uomcode as uom3code
			FROM sodetail a 
			LEFT JOIN soheader b ON b.soheaderid = a.soheaderid 
			LEFT JOIN addressbook c ON c.addressbookid = b.addressbookid
			LEFT JOIN unitofmeasure d ON d.unitofmeasureid = a.uomid
			LEFT JOIN product e ON e.productid = a.productid 
			LEFT JOIN sales f ON f.salesid = b.salesid 
			LEFT JOIN employee g ON g.employeeid = f.employeeid
			left join plant h on h.plantid = b.plantid 
			left join unitofmeasure i on i.unitofmeasureid = uom2id
			left join unitofmeasure j on j.unitofmeasureid = uom3id
			WHERE b.recordstatus >= 3
			and b.sodate BETWEEN '". date(Yii::app()->params['datetodb'], strtotime($startdate))."'
				and	'".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND b.plantid = ".$plantid."
			and coalesce(c.fullname,'') like '%".$customer."%' 
			and coalesce(g.fullname,'') like '%".$employee."%'";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();	
		$i          = 2;
    foreach ($dataReader as $row) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $i-1)
				->setCellValueByColumnAndRow(1, $i+1, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $i+1, $row['salesname'])
				->setCellValueByColumnAndRow(3, $i+1, $row['customername'])
				->setCellValueByColumnAndRow(4, $i+1, $row['sono'])
				->setCellValueByColumnAndRow(5, $i+1, $row['pocustno'])
				->setCellValueByColumnAndRow(6, $i+1, $row['sodate'])
				->setCellValueByColumnAndRow(7, $i+1, $row['delvdate'])
				->setCellValueByColumnAndRow(8, $i+1, $row['productname'])
				->setCellValueByColumnAndRow(9, $i+1, $row['qty1'])
				->setCellValueByColumnAndRow(10, $i+1, $row['uomcode'])
				->setCellValueByColumnAndRow(11, $i+1, $row['qty2'])
				->setCellValueByColumnAndRow(12, $i+1, $row['uom2code'])
				->setCellValueByColumnAndRow(13, $i+1, $row['qty3'])
				->setCellValueByColumnAndRow(14, $i+1, $row['uom3code'])
				->setCellValueByColumnAndRow(15, $i+1, $row['price'])
				->setCellValueByColumnAndRow(16, $i+1, $row['totalvalue'])
			;
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
	public function TopdownsalesXls($companyid,$plantid,$sloc,$customer,$employee,$product,$salesarea,$startdate,$enddate,$per) {
		$this->menuname='topdownsales';
    parent::actionDownxls();
		$totalqty1=0;
		$totalqty12=0;
		$totalqty13=0;
		$totalqty14=0;
		$totalnominal1=0;
		$totalnetto1=0;
		$sql = "
			SELECT *,totalvalue / qty1 AS hargapersatuan1
			FROM 
			(
			SELECT e.plantcode,b.addressbookid,c.fullname,a.uomid,d.uomcode, SUM(f.qty) AS qty1, SUM(f.qty * a.price) AS totalvalue,
			sum(f.qty3) as qty3
			FROM sodetail a 
			LEFT JOIN soheader b ON b.soheaderid = a.soheaderid 
			LEFT JOIN addressbook c ON c.addressbookid = b.addressbookid
			LEFT JOIN unitofmeasure d ON d.unitofmeasureid = a.uomid 
			left join plant e on e.plantid = b.plantid 
			join gidetail f on f.sodetailid = a.sodetailid and f.productid = a.productid 
			join giheader g on g.giheaderid = f.giheaderid and g.soheaderid = b.soheaderid 
			WHERE b.recordstatus = 3
			and g.recordstatus = 3 
			and g.gidate BETWEEN '". date(Yii::app()->params['datetodb'], strtotime($startdate))."'
				and	'".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
			AND b.plantid = ".$plantid."
			and coalesce(c.fullname,'') like '%".$customer."%' 
			GROUP BY b.addressbookid,c.fullname,a.uomid,e.plantcode
			) z order by totalvalue desc,fullname asc";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();	
		$i          = 2;
		$totalgrandvalue = 0; $totalvalueroll = 0;$totalvaluemtr = 0;$totalvaluepcs = 0;$totalqtyroll = 0;$totalqtymtr = 0;$totalqtypcs = 0;
		$totalvaluekg = 0;$totalqtykg = 0;$totalvaluedrum = 0;$totalqtydrum = 0;$totalvaluelbr = 0;$totalqtylbr = 0;$totalvaluerim = 0;$totalqtyrim = 0;
		$totalvaluebtg = 0;$totalqtybtg = 0;
		foreach($dataReader as $row) {
			switch ($row['uomid']) {
				case 1:
					$totalvaluekg += $row['totalvalue'];
					$totalqtykg += $row['qty1'];
					break;
				case 2:
					$totalvaluepcs += $row['totalvalue'];
					$totalqtypcs += $row['qty1'];
					break;
				case 6:
					$totalvaluemtr += $row['totalvalue'];
					$totalqtymtr += $row['qty1'];
					break;
				case 7:
					$totalvalueroll += $row['totalvalue'];
					$totalqtyroll += $row['qty1'];
					break;
				case 20:
					$totalvaluelbr += $row['totalvalue'];
					$totalqtylbr += $row['qty1'];
					break;
				case 21:
					$totalvaluebtg += $row['totalvalue'];
					$totalqtybtg += $row['qty1'];
					break;
				case 22:
					$totalvaluebtg += $row['totalvalue'];
					$totalqtybtg += $row['qty1'];
					break;
				case 29:
					$totalvaluerim += $row['totalvalue'];
					$totalqtyrim += $row['qty1'];
					break;
				case 32:
					$totalvaluedrum += $row['totalvalue'];
					$totalqtydrum += $row['qty1'];
					break;
			}
			$totalgrandvalue += $row['totalvalue'];
		}
    foreach ($dataReader as $row) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $i-1)
				->setCellValueByColumnAndRow(1, $i+1, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $i+1, $row['fullname'])
				->setCellValueByColumnAndRow(3, $i+1, $row['uomcode'])
				->setCellValueByColumnAndRow(4, $i+1, $row['qty1'])
				->setCellValueByColumnAndRow(5, $i+1, $row['qty3'])
				->setCellValueByColumnAndRow(6, $i+1, $row['totalvalue'])
				->setCellValueByColumnAndRow(7, $i+1, (($row['totalvalue'] / $totalgrandvalue) * 100))
				->setCellValueByColumnAndRow(8, $i+1, ($row['totalvalue'] / $row['qty1']))
			;
			$i++;
		}
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(1, $i+1, 'Total')
			->setCellValueByColumnAndRow(4, $i+1, $totalgrandvalue);
		$i++;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(1, $i+1, 'Total Roll')
			->setCellValueByColumnAndRow(3, $i+1, $totalqtyroll)
			->setCellValueByColumnAndRow(4, $i+1, $totalvalueroll);
		$i++;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(1, $i+1, 'Total Kg')
			->setCellValueByColumnAndRow(3, $i+1, $totalqtykg)
			->setCellValueByColumnAndRow(4, $i+1, $totalvaluekg);
		$i++;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(1, $i+1, 'Total Pcs')
			->setCellValueByColumnAndRow(3, $i+1, $totalqtypcs)
			->setCellValueByColumnAndRow(4, $i+1, $totalvaluepcs);
		$i++;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(1, $i+1, 'Total Mtr')
			->setCellValueByColumnAndRow(3, $i+1, $totalqtymtr)
			->setCellValueByColumnAndRow(4, $i+1, $totalvaluemtr);
		$i++;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(1, $i+1, 'Total Drum')
			->setCellValueByColumnAndRow(3, $i+1, $totalqtydrum)
			->setCellValueByColumnAndRow(4, $i+1, $totalvaluedrum);
		$i++;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(1, $i+1, 'Total Lbr')
			->setCellValueByColumnAndRow(3, $i+1, $totalqtylbr)
			->setCellValueByColumnAndRow(4, $i+1, $totalvaluelbr);
		$i++;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(1, $i+1, 'Total Btg')
			->setCellValueByColumnAndRow(3, $i+1, $totalqtybtg)
			->setCellValueByColumnAndRow(4, $i+1, $totalvaluebtg);
		$i++;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(1, $i+1, 'Total Rim')
			->setCellValueByColumnAndRow(3, $i+1, $totalqtyrim)
			->setCellValueByColumnAndRow(4, $i+1, $totalvaluerim);
			
		$this->getFooterXLS($this->phpExcel);
	}
}