<?php
class ReporthrController extends Controller
{
	public $menuname = 'reporthr';
	public function actionIndex()
	{
		$this->renderPartial('index',array());
	}
    public function actionDownPDF()
	{
		parent::actionDownload();
		if(isset($_GET['lro']))
		{
		
			if ($_GET['lro'] == 1){
				$this->LaporanEvaluasi($_GET['company'],$_GET['employeeid'],$_GET['empplanid'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}else
            if($_GET['lro']== 2){
                $this->RekapLaporaEvaluasiPerDokumen($_GET['company'],$_GET['employeeid'],$_GET['empplanid'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
            }else
            if ($_GET['lro'] == 3){
				$this->LaporanKontrakSudahPerpanjang($_GET['company'],$_GET['employeeid'],$_GET['empplanid'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}else
            if($_GET['lro'] == 4){
                $this->LaporanKontrakYangBerakhir($_GET['company'],$_GET['employeeid'],$_GET['empplanid'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
            }else
            if($_GET['lro'] == 5){
                $this->LaporanKejadianKaryawan($_GET['company'],$_GET['employeeid'],$_GET['empplanid'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
            }else
            if($_GET['lro']== 6){
                $this->RekapDataKaryawan($_GET['company'],$_GET['employeeid'],$_GET['religionid'],$_GET['employeetypeid'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
            }else
            if($_GET['lro']== 7){
                $this->RekapJenisKaryawan($_GET['company'],$_GET['employeeid'],$_GET['religionid'],$_GET['employeetypeid'],$_GET['empplanid'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
            }else
            if($_GET['lro']== 8){
                $this->RekapThrKaryawan($_GET['company'],$_GET['employeeid'],$_GET['religionid'],$_GET['employeetypeid'],$_GET['empplanid'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
            }else
            if($_GET['lro']== 9){
                $this->RekapUlangTahunKaryawan($_GET['company'],$_GET['employeeid'],$_GET['religionid'],$_GET['employeetypeid'],$_GET['empplanid'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
            }else
            if($_GET['lro']== 10){
                $this->RincianStrukturKaryawan($_GET['company'],$_GET['employeeid'],$_GET['religionid'],$_GET['employeetypeid'],$_GET['empplanid'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
            }
		}
	}
	//1
	public function LaporanEvaluasi($companyid,$employeeid,$empplanid,$startdate,$enddate)
    {
			parent::actionDownload();
			
			$connection = Yii::app()->db;
			$this->pdf->title='Laporan Evaluasi Karyawan';
			$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->AddPage('L','A4');
			
			$this->pdf->SetFont('Arial','',11);
			$this->pdf->sety($this->pdf->gety()+5);

			$this->pdf->colalign = array('L','C','C','C','L','C','C','C','L');
			$this->pdf->setwidths(array(7,40,40,20,25,35,45,45,25));
			$this->pdf->colheader = array('No','Nama Kegiatan','Details','Karyawan','Tgl Realisasi','Realisasi','Permasalahan','Solusi','Target');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','L');		
			
			$this->pdf->SetFont('Arial','',10);
			/*
			$this->pdf->row(array(1,'Nama Kegiatan goes here','Detail goes here','01-05-2017','Realisasi goes here','Permasalahan goes here','Solusi goes here','01-05-2017')); 
			*/
			
			$sql = "SELECT a.empplanid, a.empplanname, b.description as details, b.objvalue as nilai_plan, b.enddate, c.realplanid, IFNULL(c.description,'-') as realisasi, IFNULL(c.objvalue,'-') as nilai_real, IFNULL(c.dateline,'-') as dateline, IFNULL(c.hambatan,'-') as hambatan, IFNULL(c.solusi,'-') as solusi, d.fullname
			FROM empplan a 
			LEFT JOIN empplandetail b ON a.empplanid = b.empplanid
			LEFT JOIN realplan c ON b.empplandetailid = c.empplandetailid 
			LEFT JOIN employee d ON d.employeeid = b.employeeid
			LEFT JOIN employeeorgstruc e on e.employeeid=d.employeeid
			LEFT JOIN orgstructure f on f.orgstructureid=e.orgstructureid
			LEFT JOIN company g on g.companyid=f.companyid ";
			
			$where = "WHERE a.recordstatus > 0 AND c.recordstatus = 2";
			if(isset($employeeid) && $employeeid!=''){
					$where .= " AND b.employeeid='".$employeeid."' ";
			}
			if(isset($empplanid) && $empplanid!=''){
					$where .= " AND a.empplanid='".$empplanid."' ";
			}
			if(isset($companyid) && $companyid!=''){
					$where .= " AND g.companyid='".$companyid."'";
			}
			#$where .= " AND b.startdate BETWEEN '".$startdate."' AND '".$enddate."' ORDER BY a.empplanid DESC";
			$where .= " AND a.empplandate BETWEEN '".$startdate."' AND '".$enddate."' ORDER BY a.empplanid DESC";
			
			$result = $connection->createCommand($sql.$where)->queryAll();
			$i=1;
			foreach($result as $row){
					$y = $this->pdf->setY($this->pdf->getY()+1);
					$this->pdf->row(array($i,$row['empplanname'],$row['details'].' => '. Yii::app()->format->formatNumber($row['nilai_plan']),$row['fullname'],date(Yii::app()->params['dateviewfromdb'], strtotime($row['enddate'])),$row['realisasi'].' => '.Yii::app()->format->formatNumber($row['nilai_real']),$row['hambatan'],$row['solusi'],date(Yii::app()->params['dateviewfromdb'], strtotime($row['dateline']))));
					$this->pdf->text(10,$this->pdf->gety(),'________________________________________________________________________________________________________________________________________________');
					$i++;
			}
			$this->pdf->Output();
	}	
	public function RekapLaporaEvaluasiPerDokumen($companyid,$employeeid,$empplanid,$startdate,$enddate,$per)
    {
			parent::actionDownload();
			//$this->no_result();
			
			$connection = Yii::app()->db;
			$this->pdf->title='Laporan Evaluasi Karyawan';
			$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->AddPage('P','A4');
			
			
			if(isset($employeeid) && ($employeeid != '')){
			$getemployee = "SELECT GetEmpNameByEmpID($employeeid)";
			$employeename = $connection->createCommand($getemployee)->queryScalar();
			$result['employeeid'] = $employeeid;
			}else{
					$getemployee = "SELECT distinct c.employeeid, fullname 
									FROM employee a 
									JOIN empplan b
									JOIN empplandetail c 
									ON b.empplanid = c.empplanid AND c.employeeid = a.employeeid
									LEFT JOIN employeeorgstruc e on e.employeeid=a.employeeid
									LEFT JOIN orgstructure f on f.orgstructureid=e.orgstructureid
									LEFT JOIN company g on g.companyid=f.companyid 
													WHERE b.recordstatus='2' AND g.companyid='".$companyid."' AND b.empplandate BETWEEN ('".$startdate."') AND ('".$enddate."') ";
					if(isset($empplanid) && ($empplanid!='')){
							$whereemployee = " AND b.empplanid='".$empplanid."' ";
					}else{
							$whereemployee = " ";
					}
					$whereemployee .= "ORDER BY b.empplandate ASC";
					$employeename = $connection->createCommand($getemployee.$whereemployee)->queryAll();
			}
			if(isset($employeename) && empty($employeename)){
					$this->no_result();
			}else{
					if(isset($employeename) && !is_array($employeename)){
							$employeename = array('fullname'=>$employeename);
					}
					foreach($employeename as $result){
							if(!(is_array($result))){
									$result=array();
									$result['fullname'] = $employeename['fullname'];
									$result['employeeid'] = $employeeid;
							}
							$this->pdf->SetFont('Arial','',14);
							$this->pdf->text(10, $this->pdf->gety() + 5, 'Nama Karyawan : '.$result['fullname']);
							$this->pdf->sety($this->pdf->gety()+2);

							$getrow = "SELECT COUNT(1), a.useraccess, a.empplandate
												 FROM empplan a 
												 LEFT JOIN empplandetail b ON a.empplanid = b.empplanid 
												 LEFT JOIN realplan c ON c.empplanid = a.empplanid 
												 WHERE b.employeeid='".$result['employeeid']."' AND empplandate BETWEEN ('".$startdate."') AND ('".$enddate."')";
							if($empplanid!=''){
									$where1 = " AND c.empplanid='".$empplanid."' AND a.recordstatus='2'";
							}else{
									$where1 = " AND a.recordstatus='2'";
							}
							$where1 .= " GROUP BY useraccess,empplandate ORDER BY a.empplandate ASC";
							$rows = $connection->createCommand($getrow.$where1)->queryAll();

							foreach($rows as $row){
									$i=1;
									$this->pdf->SetFont('Arial','',11);
									$this->pdf->text(10, $this->pdf->gety() + 12, 'Nama Pembuat : '.$row['useraccess']);
									$this->pdf->text(160, $this->pdf->gety() + 12, 'Tgl Rencana : '.date(Yii::app()->params['dateviewfromdb'], strtotime($row['empplandate'])));

									$this->pdf->setFont('Arial','',10);
									$this->pdf->sety($this->pdf->gety()+15);

									$this->pdf->colalign = array('L','L','L','L','L','L','L');
									$this->pdf->setwidths(array(7,45,30,30,25,25,40));
									$this->pdf->colheader = array('No','Nama Plan','Tanggal Awal','Tanggal Target','Nilai Rencana','Nilai Realisasi','Keterangan');
									$this->pdf->RowHeader();
									$this->pdf->coldetailalign = array('L','L','L','L','L','L','L');		

									$sqldata = "SELECT distinct a.empplanid, a.empplanname, a.empplandate, b.startdate, b.enddate, b.objvalue, b.description
															FROM empplan a
															LEFT JOIN empplandetail b ON a.empplanid = b.empplanid
															WHERE b.employeeid IN ('".$result['employeeid']."') 
															AND a.empplandate = '".$row['empplandate']."' AND a.recordstatus='2' AND empplandate BETWEEN ('".$startdate."') AND ('".$enddate."') ";
									if($empplanid==''){
											$where = " AND a.empplanid LIKE '%%'";
									}else{
											$where = " AND a.empplanid IN('".$empplanid."')";
									}
									//$text = rtrim($arraynama,", ");
									$getdata = $connection->createCommand($sqldata.$where)->queryAll();
									foreach($getdata as $results){

											$sqlgetrealplan = "SELECT distinct c.objvalue as nilai_target, c.description as description_target
																				 FROM empplan a
																				 LEFT JOIN empplandetail b ON a.empplanid = b.empplanid 
																				 LEFT JOIN realplan c ON c.empplanid = a.empplanid
																				 WHERE c.empplanid= '".$results['empplanid']."' AND c.employeeid='".$result['employeeid']."' AND a.recordstatus='2'
																				 AND empplandate BETWEEN ('".$startdate."') AND ('".$enddate."')";
											$datarealplan = $connection->createCommand($sqlgetrealplan)->queryAll();
											$countrealplan = count($datarealplan);
											$this->pdf->setFont('Arial','',9);
											foreach($datarealplan as $row1){  
													$this->pdf->row(array($i,$results['empplanname'].'('.$results['description'].')',$results['startdate'],$results['startdate'],$results['objvalue'],$row1['nilai_target'],$row1['description_target'])); 
													$i++;
											}
											if($countrealplan==0){
													$this->pdf->row(array($i,$results['empplanname'].'('.$results['description'].')',$results['startdate'],$results['startdate'],$results['objvalue'],'-','-')); 
												 }

									}
							}
							$this->pdf->setY($this->pdf->gety()+5);
					}
					$this->pdf->checkPageBreak(110);
			}
			$this->pdf->Output();
	}	
	public function LaporanKontrakSudahPerpanjang($companyid,$employeeid,$empplanid,$startdate,$enddate)
    {
			parent::actionDownload();
			
			$connection = Yii::app()->db;
			$this->pdf->title='Laporan Kontrak Yang Sudah Diperpanjang';
			$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->AddPage('L','A4');
			
			$this->pdf->SetFont('Arial','',11);
			$this->pdf->sety($this->pdf->gety()+5);
			
			if(isset($companyid) && $companyid!=''){
					$companyname = Yii::app()->db->createCommand("SELECT companycode FROM company WHERE companyid='".$companyid."'")->queryScalar();
					$this->pdf->text(10,18,'Perusahaan: '.$companyname);
					$this->pdf->setY($this->pdf->gety()+5);
			}
			if($companyid==''){
					$this->pdf->colalign = array('L','C','C','L','C','C','C','L','L');
					$this->pdf->setwidths(array(7,50,25,30,30,35,40,40,25));
					$this->pdf->colheader = array('No','Nama Karyawan','Kontrak Ke','Tanggal Mulai','Tanggal Selesai','Jenis Karyawan','Structure Organisasi','Keterangan','Perusahaan');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','L');		
			}else{
					$this->pdf->colalign = array('L','C','C','L','C','C','C','L');
					$this->pdf->setwidths(array(7,50,25,30,30,40,45,50));
					$this->pdf->colheader = array('No','Nama Karyawan','Kontrak Ke','Tanggal Mulai','Tanggal Selesai','Jenis Karyawan','Structure Organisasi','Keterangan');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L');
			}
					
			
			$this->pdf->SetFont('Arial','',10);
			
			$sql = "SELECT DISTINCT a.*, b.fullname, c.employeetypename, d.employeeorgstrucid, d.orgstructureid, substring_index(substring_index(structurename, ',', 1), ',', - 1) as structurename, f.companycode
							FROM employeecontract a 
							JOIN employee b ON b.employeeid = a.employeeid
							JOIN employeetype c ON c.employeetypeid = b.employeetypeid
							JOIN employeeorgstruc d ON d.employeeid = b.employeeid AND d.employeeid = a.employeeid
							JOIN orgstructure e ON e.orgstructureid = d.orgstructureid
							JOIN company f ON f.companyid = e.companyid ";        
			$where = "WHERE a.recordstatus > 0 ";
			if(isset($employeeid) && $employeeid!=''){
					$where .= " AND b.employeeid='".$employeeid."' ";
			}
			if(isset($companyid) && $companyid!=''){
					$where .= " AND e.companyid='".$companyid."'";
			}
			if(isset($empplanid) && $empplanid!=''){
					$where .= " AND a.='".$empplanid."' ";
			}
			$where .= " AND a.startdate BETWEEN '".$startdate."' AND '".$enddate."' GROUP BY d.employeeid ORDER BY a.contractid DESC";
			
			$result = $connection->createCommand($sql.$where)->queryAll();
			$i=1;
			foreach($result as $row){
					if($companyid==''){
							$this->pdf->row(array($i,$row['fullname'],$row['contracttype'], date(Yii::app()->params['dateviewfromdb'], strtotime($row['startdate'])),date(Yii::app()->params['dateviewfromdb'], strtotime($row['enddate'])),$row['employeetypename'],$row['structurename'],$row['description'],$row['companycode'])); 
					}else{
							$this->pdf->row(array($i,$row['fullname'],$row['contracttype'], date(Yii::app()->params['dateviewfromdb'], strtotime($row['startdate'])),date(Yii::app()->params['dateviewfromdb'], strtotime($row['enddate'])),$row['employeetypename'],$row['structurename'],$row['description'])); 
					}
					$i++;
			}
			$this->pdf->Output();
	}	
	public function LaporanKontrakYangBerakhir($companyid,$employeeid,$empplanid,$startdate,$enddate)
    {
			parent::actionDownload();
			
			$connection = Yii::app()->db;
			$this->pdf->title='Laporan Kontrak Yang Akan Berakhir';
			$this->pdf->subtitle='Periode :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->AddPage('L','A4');
			
			$this->pdf->SetFont('Arial','',11);
			$this->pdf->sety($this->pdf->gety()+5);
			
			if(isset($companyid) && $companyid!=''){
					$companyname = Yii::app()->db->createCommand("SELECT companycode FROM company WHERE companyid='".$companyid."'")->queryScalar();
					$this->pdf->text(10,18,'Perusahaan: '.$companyname);
					$this->pdf->setY($this->pdf->gety()+5);
			}
			if($companyid==''){
					$this->pdf->colalign = array('L','C','C','L','C','C','C','L','L');
					$this->pdf->setwidths(array(7,50,25,30,30,35,40,40,25));
					$this->pdf->colheader = array('No','Nama Karyawan','Kontrak Ke','Tanggal Mulai','Tanggal Selesai','Jenis Karyawan','Structure Organisasi','Keterangan','Perusahaan');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','L');		
			}else{
					$this->pdf->colalign = array('L','C','C','L','C','C','C','L');
					$this->pdf->setwidths(array(7,50,25,30,30,40,45,50));
					$this->pdf->colheader = array('No','Nama Karyawan','Kontrak Ke','Tanggal Mulai','Tanggal Selesai','Jenis Karyawan','Structure Organisasi','Keterangan');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L');
			}
					
			
			$this->pdf->SetFont('Arial','',10);
			
			$sql = "SELECT DISTINCT a.*, b.fullname, c.employeetypename, d.employeeorgstrucid, d.orgstructureid, substring_index(substring_index(structurename, ',', 1), ',', - 1) as structurename, f.companycode
							FROM employeecontract a 
							JOIN employee b ON b.employeeid = a.employeeid
							JOIN employeetype c ON c.employeetypeid = b.employeetypeid
							JOIN employeeorgstruc d ON d.employeeid = b.employeeid AND d.employeeid = a.employeeid
							JOIN orgstructure e ON e.orgstructureid = d.orgstructureid
							JOIN company f ON f.companyid = e.companyid ";        
			$where = "WHERE a.recordstatus > 0  and (b.resigndate is null or b.resigndate = '1970-01-01')";
			if(isset($employeeid) && $employeeid!=''){
					$where .= " AND b.employeeid='".$employeeid."' ";
			}
			if(isset($companyid) && $companyid!=''){
					$where .= " AND e.companyid='".$companyid."'";
			}
			if(isset($empplanid) && $empplanid!=''){
					$where .= " AND a.='".$empplanid."' ";
			}
			$where .= " AND a.enddate BETWEEN '".$startdate."' AND '".$enddate."' GROUP BY d.employeeid ORDER BY a.enddate ASC";
			
			$result = $connection->createCommand($sql.$where)->queryAll();
			$i=1;
			foreach($result as $row){
					if($companyid==''){
							$this->pdf->row(array($i,$row['fullname'],$row['contracttype'], date(Yii::app()->params['dateviewfromdb'], strtotime($row['startdate'])),date(Yii::app()->params['dateviewfromdb'], strtotime($row['enddate'])),$row['employeetypename'],$row['structurename'],$row['description'],$row['companycode'])); 
					}else{
							$this->pdf->row(array($i,$row['fullname'],$row['contracttype'], date(Yii::app()->params['dateviewfromdb'], strtotime($row['startdate'])),date(Yii::app()->params['dateviewfromdb'], strtotime($row['enddate'])),$row['employeetypename'],$row['structurename'],$row['description'])); 
					}
					$i++;
			}
			$this->pdf->Output();
	}	
	public function LaporanKejadianKaryawan($companyid,$employeeid,$empplanid,$startdate,$enddate)
    {
			parent::actionDownload();
			
			$connection = Yii::app()->db;
			$this->pdf->title='Laporan Rincian Kejadian Karyawan';
			$this->pdf->subtitle='Periode :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->AddPage('L','A4');
			
			$this->pdf->SetFont('Arial','',11);
			$this->pdf->sety($this->pdf->gety()+5);
			
			if(isset($companyid) && $companyid!=''){
					$companyname = Yii::app()->db->createCommand("SELECT companycode FROM company WHERE companyid='".$companyid."'")->queryScalar();
					$this->pdf->text(10,18,'Perusahaan: '.$companyname);
					$this->pdf->setY($this->pdf->gety()+5);
			}
			if($companyid==''){
					$this->pdf->colalign = array('L','L','C','C','C','L','C');
					$this->pdf->setwidths(array(7,30,50,30,40,75,25));
					$this->pdf->colheader = array('No','No. Document','Nama Karyawan','Tanggal','Reward/Pusnishment','Description','Perusahaan');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('L','L','L','C','L','L','C');		
			}else{
					$this->pdf->colalign = array('L','L','C','C','C','L');
					$this->pdf->setwidths(array(7,30,50,30,40,85));
					$this->pdf->colheader = array('No','No. Document','Nama Karyawan','Tanggal','Reward/Pusnishment','Description');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('L','L','L','C','L','L');
			}
					
			
			$this->pdf->SetFont('Arial','',10);
			$employee = '';
			$company = '';
			$sql = "SELECT b.rewardno, a.fullname, b.date, b.description, CONCAT('Reward') as category, g.companycode
							FROM employee a
							LEFT JOIN employeereward b ON b.employeeid = a.employeeid
							LEFT JOIN employeeorgstruc e on e.employeeid=a.employeeid
							LEFT JOIN orgstructure f on f.orgstructureid=e.orgstructureid
							LEFT JOIN company g on g.companyid=f.companyid 
							WHERE b.date BETWEEN ('".$startdate."') AND ('".$enddate."') ";
			if(isset($employeeid) && $employeeid!=''){
					$sql .= " AND a.employeeid='".$employeeid."' ";
			}
			if(isset($companyid) && $companyid!=''){
					$sql .= " AND g.companyid='".$companyid."'";
			}
			$sql .= " UNION ALL
							SELECT c.spletterno, a.fullname, c.spletterdate as date, c.description, CONCAT('Punishment') as category, g.companycode
							FROM employee a
							LEFT JOIN spletter c ON c.employeeid = a.employeeid
							LEFT JOIN employeeorgstruc e on e.employeeid=a.employeeid
							LEFT JOIN orgstructure f on f.orgstructureid=e.orgstructureid
							LEFT JOIN company g on g.companyid=f.companyid 
							WHERE c.spletterdate BETWEEN ('".$startdate."') AND ('".$enddate."') ";
			if(isset($employeeid) && $employeeid!=''){
					$sql .= " AND a.employeeid='".$employeeid."' ";
			}
			if(isset($companyid) && $companyid!=''){
					$sql .= " AND g.companyid='".$companyid."'";
			}       
			$sql .= " UNION ALL
							SELECT d.lessonletterno, a.fullname, d.date, d.description, CONCAT('Lesson') as category, g.companycode
							FROM employee a
							LEFT JOIN lessonletter d ON d.employeeid = a.employeeid
							LEFT JOIN employeeorgstruc e on e.employeeid=a.employeeid
							LEFT JOIN orgstructure f on f.orgstructureid=e.orgstructureid
							LEFT JOIN company g on g.companyid=f.companyid 
							WHERE d.date BETWEEN ('".$startdate."') AND ('".$enddate."') ";
			if(isset($employeeid) && $employeeid!=''){
					$sql .= " AND a.employeeid='".$employeeid."' ";
			}
			if(isset($companyid) && $companyid!=''){
					$sql .= " AND g.companyid='".$companyid."'";
			}
			$where = " ORDER BY date ASC";
			
			$result = $connection->createCommand($sql.$where)->queryAll();
			$i=1;
			foreach($result as $row){
					if($companyid==''){
							$this->pdf->row(array($i,$row['rewardno'],$row['fullname'], date(Yii::app()->params['dateviewfromdb'], strtotime($row['date'])),$row['category'],$row['description'],$row['companycode'])); 
					}else{
							 $this->pdf->row(array($i,$row['rewardno'],$row['fullname'], date(Yii::app()->params['dateviewfromdb'], strtotime($row['date'])),$row['category'],$row['description'])); ; 
					}
					$i++;
			}
			$this->pdf->Output();
	}	
    //6
	public function RekapDataKaryawan($companyid,$employeeid,$religionid,$employeetypeid,$empplanid,$startdate,$enddate)
    {
        parent::actionDownload();
        $this->pdf->title='Rekap Data Karyawan';
        $this->pdf->subtitle='Periode :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			
        $sql = "select distinct
        a0.employeeid, a0.fullname, a0.oldnik, a0.birthdate, a0.referenceby, a0.joindate, case when a0.istrial = 1 then 'Ya' else 'Tidak' end as istrial,a0.phoneno,a0.alternateemail,
        case when a0.resigndate is not null or a0.resigndate <> '1970-01-01' then
            a0.resigndate
        else
            '-'
        end as resigndate,
        ifnull(a0.email,'-') as email,ifnull(a0.hpno,'-') as hpno, ifnull(a0.taxno,'-') as taxno,ifnull(a0.dplkno,'-') as dplkno,ifnull(a0.bpjskesno,'-') as bpjskesno,
        ifnull(a0.hpno2,'-') as hpno2, ifnull(a0.accountno,'-') as accountno,
        a3.positionname, a10.levelorgname,a4.employeetypename,a5.sexname,a6.cityname as citybirth, a7.religionname, a8.maritalstatusname,a9.employeestatusname
        from employee a0
        left join position a3 on a3.positionid = a0.positionid
        left join employeetype a4 on a4.employeetypeid = a0.employeetypeid
        left join sex a5 on a5.sexid = a0.sexid
        left join city a6 on a6.cityid = a0.birthcityid
        left join religion a7 on a7.religionid = a0.religionid
        left join maritalstatus a8 on a8.maritalstatusid = a0.maritalstatusid
        left join employeestatus a9 on a9.employeestatusid = a0.employeestatusid
        left join levelorg a10 on a10.levelorgid = a0.levelorgid 
        left join employeeorgstruc a11 on a11.employeeid = a0.employeeid 
        left join orgstructure a2 on a2.orgstructureid = a11.orgstructureid
        left join company a1 on a1.companyid = a2.companyid 
        where a2.companyid = {$companyid} ";
        $where = "";
        
        if (isset($employeeid) && $employeeid!='')
        {
            $where .= " and a0.employeeid = ".$employeeid;
        }
        if(isset($religionid) && $religionid!='')
        {
            $where .= " and a0.religionid = ".$religionid;
        }
        if(isset($employeetypeid) && $employeetypeid!='')
        {
            $where .= " and a0.employeetypeid = ".$employeetypeid;
        }
            
        $connection = Yii::app()->db;
        $command=$connection->createCommand($sql.$where.' order by a0.employeeid asc');
        $dataReader=$command->queryAll();

        //masukkan judul
        //$this->pdf->title=$this->getcatalog('employee');
            $this->pdf->AddPage('P');
        $this->pdf->AliasNBPages();
        $this->pdf->SetFont('Arial','',10);
			
        foreach($dataReader as $row)
        {
            //masukkan baris untuk cetak
            $this->pdf->checkNewPage(125);
            $this->pdf->text(10,$this->pdf->gety()+5,'Nama ');$this->pdf->text(75,$this->pdf->gety()+5,': '.$row['fullname']);
            $this->pdf->text(10,$this->pdf->gety()+10,'Posisi');$this->pdf->text(75,$this->pdf->gety()+10,': '.$row['positionname']);
            $this->pdf->setY($this->pdf->getY()-5);
            $this->pdf->text(10,$this->pdf->gety()+20,'Level ');$this->pdf->text(75,$this->pdf->gety()+20,': '.$row['levelorgname']);
            $this->pdf->text(10,$this->pdf->gety()+25,'NIK Lama ');$this->pdf->text(75,$this->pdf->gety()+25,': '.$row['oldnik']);
            $this->pdf->text(10,$this->pdf->gety()+30,'NIK Baru ');$this->pdf->text(75,$this->pdf->gety()+30,': '.$row['oldnik']);
            $this->pdf->text(10,$this->pdf->gety()+35,'Jenis Karyawan');$this->pdf->text(75,$this->pdf->gety()+35,': '.$row['employeetypename']);
            $this->pdf->text(10,$this->pdf->gety()+40,'Jenis Kelamin');$this->pdf->text(75,$this->pdf->gety()+40,': '.$row['sexname']);
            $this->pdf->text(10,$this->pdf->gety()+45,'Kota Kelahiran');$this->pdf->text(75,$this->pdf->gety()+45,': '.$row['citybirth']);
            $this->pdf->text(10,$this->pdf->gety()+50,'Tanggal Lahir');$this->pdf->text(75,$this->pdf->gety()+50,": ".date(Yii::app()->params['dateviewfromdb'],strtotime($row['birthdate'])));
            $this->pdf->text(10,$this->pdf->gety()+55,'Agama');$this->pdf->text(75,$this->pdf->gety()+55,': '.$row['religionname']);
            $this->pdf->text(10,$this->pdf->gety()+60,'Status Nikah');$this->pdf->text(75,$this->pdf->gety()+60,': '.$row['maritalstatusname']);
            $this->pdf->text(10,$this->pdf->gety()+65,'Referensi');$this->pdf->text(75,$this->pdf->gety()+65,': '.$row['referenceby']);
            $this->pdf->text(10,$this->pdf->gety()+70,'Tgl Gabung');$this->pdf->text(75,$this->pdf->gety()+70,": ".date(Yii::app()->params['dateviewfromdb'],strtotime($row['joindate'])));
            $this->pdf->text(10,$this->pdf->gety()+75,'Status Karywan');$this->pdf->text(75,$this->pdf->gety()+75,': '.$row['employeestatusname']);
            $this->pdf->text(10,$this->pdf->gety()+80,'Percobaaan');$this->pdf->text(75,$this->pdf->gety()+80,': '.$row['istrial']);
            $this->pdf->text(10,$this->pdf->gety()+85,'Tanggal Resign');$this->pdf->text(75,$this->pdf->gety()+85,': '.$row['resigndate']);
            $this->pdf->text(10,$this->pdf->gety()+90,'Email');$this->pdf->text(75,$this->pdf->gety()+90,': '.$row['email']);
            $this->pdf->text(10,$this->pdf->gety()+95,'Telp');$this->pdf->text(75,$this->pdf->gety()+95,': '.$row['phoneno']);
            $this->pdf->text(10,$this->pdf->gety()+100,'Email Cadangan');$this->pdf->text(75,$this->pdf->gety()+100,': '.$row['alternateemail']);
            $this->pdf->text(10,$this->pdf->gety()+105,'No HP');$this->pdf->text(75,$this->pdf->gety()+105,': '.$row['hpno']);
            $this->pdf->text(10,$this->pdf->gety()+110,'No HP ke-2');$this->pdf->text(75,$this->pdf->gety()+110,': '.$row['hpno2']);
            $this->pdf->text(10,$this->pdf->gety()+115,'NPWP');$this->pdf->text(75,$this->pdf->gety()+115,': '.$row['taxno']);
            $this->pdf->text(10,$this->pdf->gety()+120,getCatalog('dplkno'));$this->pdf->text(75,$this->pdf->gety()+120,': '.$row['dplkno']);
            $this->pdf->text(10,$this->pdf->gety()+125,getCatalog('bpjskesno'));$this->pdf->text(75,$this->pdf->gety()+125,': '.$row['bpjskesno']);
            $this->pdf->text(10,$this->pdf->gety()+130,'No. Rekening Bank');$this->pdf->text(75,$this->pdf->gety()+130,': '.$row['accountno']);
            $this->pdf->text(10,$this->pdf->gety()+131,'_________________________________________________________________________________________________');
                /*
                if (isset($employeeid) && $employeeid!='') {
                $where1 =  " where a3.employeeid in (".$employeeid.") ";
        }else{
                        $where1 = " where a3.employeeid in (".$row['employeeid'].")";
                }
                */
            //this->pdf->Ln(5);
            $this->pdf->setY($this->pdf->getY()+140);
            $this->pdf->checkNewPage(25);
            $this->pdf->text(10,$this->pdf->gety(),'Struktur Organisasi');
            $sqly = "select substring_index(substring_index(a1.structurename, ',', 1), ',', - 1) as structurename, substring_index(substring_index(a1.structurename, ',', 2), ',', - 1) as dept, 
            substring_index(substring_index(a1.structurename, ',', 3), ',', - 1) as divisi, a2.companycode
            from employeeorgstruc a0 
            left join orgstructure a1 on a1.orgstructureid = a0.orgstructureid
            left join company a2 on a2.companyid = a1.companyid
            where a0.employeeid = ".$row['employeeid']."
            and a0.recordstatus = 1
            order by a1.structurename asc";

            $commandy=$connection->createCommand($sqly);
            $dataReadery=$commandy->queryAll();
            $this->pdf->sety($this->pdf->gety()+2);
            $this->pdf->setFont('Arial','',9);
            $this->pdf->colalign = array('L','L','L','L','C');
            $this->pdf->setwidths(array(10,50,50,50,20));
            $this->pdf->colheader = array('No','Struktur','Deparment','Divisi','Perusahaan');
            $this->pdf->RowHeader();
            $this->pdf->coldetailalign = array('L','L','L','L','C');
            $i3y=0;
            foreach($dataReadery as $rowy)
            {
                $i3y+=1;
                $this->pdf->row(array(
                $i3y,
                $rowy['structurename'],
                $rowy['dept'],
                $rowy['divisi'],
                $rowy['companycode']));

            }

            $this->pdf->setY($this->pdf->getY()+5);
            $this->pdf->SetFont('Arial','',10);
            //$connection = Yii::app()->db;
            //$command=$connection->createCommand($this->sqldataaddress.$where1);
            $sqladdress = "select a3.addressname,a3.rt,a3.rw,a3.phoneno,a3.faxno,a3.lat,a3.lng,a3.foto,a1.addresstypename,a2.cityname
            from address a3 
            left join addresstype a1 on a1.addresstypeid = a3.addresstypeid
            left join city a2 on a2.cityid = a3.cityid
            left join employee a0 on a0.addressbookid = a3.addressbookid 
            where a0.employeeid = ".$row['employeeid'];

            $command = $connection->createCommand($sqladdress);
            $getaddress=$command->queryAll();
            $this->pdf->checkNewPage(25);
            $this->pdf->text(10,$this->pdf->gety()+3,'Alamat Karyawan');
            $this->pdf->setY($this->pdf->getY()+5);
            $this->pdf->setFont('Arial','',9);
            $this->pdf->colalign = array('L','L','L','L','L','L');
            $this->pdf->setwidths(array(7,45,90,20,15,15));
            $this->pdf->colheader = array('No','Jenis Alamat','Alamat','Kota','Lat','Lng');
            $this->pdf->RowHeader();
            $this->pdf->coldetailalign = array('C','L','L','L','L','L');
            $xy=0;
            foreach($getaddress as $res)
            {
                $xy+=1;
                $this->pdf->row(array($xy,
                $res['addresstypename'],
                $res['addressname'].' RT '.$res['rt'].' RW '.$res['rw'],
                $res['cityname'],
                $res['lat'],
                $res['lng']));

            }
            /*
            foreach($getaddress as $res)
            {
                $this->pdf->text(10,$this->pdf->gety()+135,'Jenis Alamat');$this->pdf->text(48,$this->pdf->gety()+135,': '.$res['addresstypename']);
                $this->pdf->text(10,$this->pdf->gety()+140,'Alamat');$this->pdf->text(48,$this->pdf->gety()+140,': '.$res['addressname']);
                $this->pdf->text(10,$this->pdf->gety()+145,'RT');$this->pdf->text(48,$this->pdf->gety()+145,': '.$res['rt']);
                $this->pdf->text(10,$this->pdf->gety()+150,'RW');$this->pdf->text(48,$this->pdf->gety()+150,': '.$res['rw']);
                $this->pdf->text(10,$this->pdf->gety()+155,'Kota');$this->pdf->text(48,$this->pdf->gety()+155,': '.$res['cityname']);
                $this->pdf->text(10,$this->pdf->gety()+160,'Lattitude');$this->pdf->text(48,$this->pdf->gety()+160,': '.$res['lat']);
                $this->pdf->text(10,$this->pdf->gety()+165,'Longitude');$this->pdf->text(48,$this->pdf->gety()+165,': '.$res['lng']);
                            $this->pdf->sety($this->pdf->getY()+40);

            }*/

            /*
            $this->pdf->checkNewPage(25);
            $this->pdf->text(10,$this->pdf->gety()+13,'Kemampuan Bahasa Karyawan');
            $sql3 = "select c.languagename, d.languagevaluename
                    from employee a
                    left join employeeforeignlanguage b on b.employeeid = a.employeeid
                    left join language c on c.languageid = b.languageid 
                    left join languagevalue d on d.languagevalueid = b.languagevalueid
                    where a.employeeid = ".$row['employeeid'];
            $command3=$connection->createCommand($sql3);
            $dataReader3=$command3->queryAll();
            $this->pdf->sety($this->pdf->gety()+15);
            $this->pdf->setFont('Arial','',9);
            $this->pdf->colalign = array('L','L','L');
            $this->pdf->setwidths(array(10,40,30));
            $this->pdf->colheader = array('No','Bahasa','Nilai Bahasa');
            $this->pdf->RowHeader();
            $this->pdf->coldetailalign = array('L','L','L');
            $i3=0;
            foreach($dataReader3 as $row3)
            {
                $i3+=1;
                $this->pdf->row(array($i3,
                $row3['languagename'],
                $row3['languagevaluename']));

            }
            */
            $this->pdf->checkNewPage(25);
            $this->pdf->setFont('Arial','',10);
            $this->pdf->setY($this->pdf->getY()+5);
            $this->pdf->text(10,$this->pdf->getY(),'Identitas Keluarga Karyawan');
            $sql1 = "select b.familyname,c.sexname,d.cityname,b.birthdate,e.educationname,f.occupationname, g.familyrelationname,b.bpjskesno
            from employee a
            left join employeefamily b on b.employeeid = a.employeeid
            left join sex c on c.sexid = b.sexid
            left join city d on d.cityid = b.cityid
            left join education e on e.educationid = b.educationid
            left join occupation f on f.occupationid = b.occupationid
            left join familyrelation g on g.familyrelationid = b.familyrelationid
            where b.recordstatus = 1 and a.employeeid = ".$row['employeeid'];
            $command1=$connection->createCommand($sql1);
            $dataReader1=$command1->queryAll();
            $this->pdf->sety($this->pdf->gety()+2);
            $this->pdf->checkNewPage(25);
            $this->pdf->setFont('Arial','',9);
            $this->pdf->colalign = array('L','L','L','L','L','L','L','L','L');
            $this->pdf->setwidths(array(10,25,15,23,20,25,15,25,40));
            $this->pdf->colheader = array('No','Nama','Hub','Jenis Kel','Kota','Tanggal Lahir','Pend','Bpjs Kesehatan','Pekerjaan');
            $this->pdf->RowHeader();
            $this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','L');
            //$this->pdf->setFont('Arial','',8);
            $i=0;
            $this->pdf->setFont('Arial','',9);
            foreach($dataReader1 as $row1)
            {
                $i+=1;
                //masukkan baris untuk cetak
                $this->pdf->row(array(
                $i,
                $row1['familyname'],
                $row1['familyrelationname'],
                $row1['sexname'],
                $row1['cityname'],
                date(Yii::app()->params['dateviewfromdb'],strtotime($row1['birthdate'])),
                $row1['educationname'],
                $row1['bpjskesno'],
                $row1['occupationname'],
                ));
            }

            $this->pdf->checkNewPage(25);
            $this->pdf->setFont('Arial','',10);
            $this->pdf->setY($this->pdf->getY()+5);
            $this->pdf->setY($this->pdf->getY()+8);
            $this->pdf->text(10,$this->pdf->gety(),'Pendidikan Karyawan');
            $sql2 = "select c.educationname, b.schoolname, d.cityname, b.yeargraduate, case when b.isdiploma = 1 then 'Ada' else 'Tidak Ada' end as isdiploma, b.schooldegree
            from employee a
            left join employeeeducation b on b.employeeid = a.employeeid
            left join education c on c.educationid = b.educationid
            left join city d on d.cityid = b.cityid
            where b.recordstatus = 1 and a.employeeid = ".$row['employeeid'];
            $command2=$connection->createCommand($sql2);
            $dataReader2=$command2->queryAll();
            $this->pdf->sety($this->pdf->gety()+2);
            $this->pdf->setFont('Arial','',9);
            $this->pdf->colalign = array('L','L','L','L','L','L','L');
            $this->pdf->setwidths(array(10,30,30,30,30,20,40));
            $this->pdf->colheader = array('No','Pendidikan','Nama Sekolah','Asal Sekolah ','Tanggal Lulus','Sertifikat','Gelar');
            $this->pdf->RowHeader();
            $this->pdf->coldetailalign = array('L','L','L','L','L','C','L');
            $ii=0;
            $this->pdf->setFont('Arial','',9);
            foreach($dataReader2 as $row2)
            {
                $y = $this->pdf->gety()+20;
                $ii+=1;
                $this->pdf->row(array($ii,
                $row2['educationname'],
                $row2['schoolname'],
                $row2['cityname'],
                $row2['yeargraduate'],
                $row2['isdiploma'],
                $row2['schooldegree']));
            }

            $this->pdf->setFont('Arial','',10);
            $this->pdf->checkNewPage(25);
            $this->pdf->setY($this->pdf->getY()+5);
            $this->pdf->text(10,$this->pdf->gety()+8,'Identitas Karyawan');
            $sql4 = "select distinct c.identitytypename,b.identityname
            from employee a
            left join employeeidentity b on b.employeeid = a.employeeid
            left join identitytype c on c.identitytypeid = b.identitytypeid
            where b.recordstatus = 1 and a.employeeid = ".$row['employeeid'];
            $command4=$connection->createCommand($sql4);
            $dataReader4=$command4->queryAll();
            $this->pdf->sety($this->pdf->gety()+10);
            $this->pdf->setFont('Arial','',9);
            $this->pdf->colalign = array('L','L','L');
            $this->pdf->setwidths(array(10,58,40));
            $this->pdf->colheader = array('No','Jenis Idetitas','Nomor Identitas');
            $this->pdf->RowHeader();
            $this->pdf->coldetailalign = array('L','L','L');
            $i4=0;
            $this->pdf->setFont('Arial','',9);
            foreach($dataReader4 as $row4)
            {
                $i4+=1;
                $this->pdf->row(array(
                $i4,
                $row4['identitytypename'],
                $row4['identityname']));
            }

            $this->pdf->setFont('Arial','',10);
            $this->pdf->checkNewPage(25);
            $this->pdf->setY($this->pdf->getY()+5);
            $this->pdf->text(10,$this->pdf->gety()+5,'Informal Karyawan');
            $sql5 = "select b.informalname, b.organizer, b.period, case when b.isdiploma = 1 then 'Ada' else 'Tidak Ada' end as isdiploma, case when b.sponsoredby = 1 then 'Ada' else 'Tidak Ada' end as sponsoredby
            from employee a
            join employeeinformal b on b.employeeid = a.employeeid
            where b.recordstatus = 1 and a.employeeid = ".$row['employeeid'];
            $command5=$connection->createCommand($sql5);
            $dataReader5=$command5->queryAll();
            $this->pdf->sety($this->pdf->gety()+8);
            $this->pdf->setFont('Arial','',9);
            $this->pdf->colalign = array('L','L','L','L','L','L');
            $this->pdf->setwidths(array(10,30,30,25,35,30));
            $this->pdf->colheader = array('No','Kursus','Pelaksana','Periode','Sertifikat','Biaya Sponsor');
            $this->pdf->RowHeader();
            $this->pdf->coldetailalign = array('L','L','L','L','L','L');
            $i5=0;
                            $this->pdf->setFont('Arial','',9);
            foreach($dataReader5 as $row5)
            {
                $i5+=1;
                $this->pdf->row(array($i5,
                $row5['informalname'],
                $row5['organizer'],
                $row5['period'],
                $row5['isdiploma'],
                $row5['sponsoredby']));
            }

            $this->pdf->checkNewPage(25);
            $this->pdf->setFont('Arial','',10);
            $this->pdf->text(10,$this->pdf->gety()+15,'Pengalaman Kerja Karyawan');
            $sql6 = "select b.informalname, b.organizer, b.period, b.isdiploma, b.sponsoredby
            from employee a
            join employeewo b on b.employeeid = a.employeeid
            where b.recordstatus = 1 and a.employeeid = ".$row['employeeid'];
            $command6=$connection->createCommand($sql6);
            $dataReader6=$command6->queryAll();
            $this->pdf->sety($this->pdf->gety()+18);
            $this->pdf->setFont('Arial','',9);
            $this->pdf->colalign = array('L','L','L','L','L','L');
            $this->pdf->setwidths(array(10,30,30,25,35,30));
            $this->pdf->colheader = array('No','Kegiatan','Pelaksana','Periode','Sertifikat','Biaya Sponsor');
            $this->pdf->RowHeader();
            $this->pdf->coldetailalign = array('L','L','L','L','L','L');
            $i6=0;
            $this->pdf->setFont('Arial','',9);
            foreach($dataReader6 as $row6)
            {
                $i6+=1;
                $this->pdf->row(array($i6,
                $row6['informalname'],
                $row6['organizer'],
                $row6['period'],
                $row6['isdiploma'],
                $row6['sponsoredby']));
            }


            $this->pdf->checkNewPage(25);
            $this->pdf->setFont('Arial','',10);
            $this->pdf->text(10,$this->pdf->gety()+15,'History Karyawan');
            $sql7 = "select b.fullname, a.contracttype, a.startdate, a.enddate, a.description
                    from employeecontract a
                    left join employee b on b.employeeid = a.employeeid
                    where a.recordstatus = 1 and b.employeeid = '".$row['employeeid']."'";
            $command7=$connection->createCommand($sql7);
            $dataReader7=$command7->queryAll();
            $this->pdf->sety($this->pdf->gety()+17);
            $this->pdf->setFont('Arial','',9);
            $this->pdf->colalign = array('L','C','C','C','C');
            $this->pdf->setwidths(array(10,40,30,30,70));
            $this->pdf->colheader = array('No','Kontrak Ke','Tanggal Mulai','Tanggal Akhir','Keterangan');
            $this->pdf->RowHeader();
            $this->pdf->coldetailalign = array('L','C','C','C','C');
            $i7=0;
            $this->pdf->setFont('Arial','',9);
            foreach($dataReader7 as $row7)
            {
                    $i7+=1;
                    $this->pdf->row(array($i7,
                    $row7['contracttype'],
                    $row7['startdate'],
                    $row7['enddate'],
                    $row7['description']));
            }
            $this->pdf->sety($this->pdf->gety()+8);
            $this->pdf->text(10,$this->pdf->gety(),'______________________________________________________________________________________________________');
        }
			//$this->pdf->checkPageBreak(250);			
    $this->pdf->Output();
    }	
	public function RekapJenisKaryawan($companyid,$employeeid,$religionid,$employeetypeid,$empplanid,$startdate,$enddate)
	{
		parent::actionDownload();
		$sql = "  select                      a.fullname,a.joindate,b.religionname,substring_index(substring_index(structurename, ',', 1), ',', - 1) as structurename,d.employeetypename,f.educationname,j.companyid,b.religionid,d.employeetypeid
							from employee a
							join religion b on b.religionid=a.religionid
							join employeetype d on d.employeetypeid=a.employeetypeid
							join employeeeducation g on g.employeeid=a.employeeid
							join education f on f.educationid=g.educationid 
							LEFT JOIN employeeorgstruc h on h.employeeid=a.employeeid
							LEFT JOIN orgstructure i on i.orgstructureid=h.orgstructureid
							LEFT JOIN company j on j.companyid=i.companyid 
							 ";
		
		$where = "where a.fullname is not null";
		
		if(isset($employeeid) && $employeeid!=''){
					$where .= " AND a.employeeid='".$employeeid."' ";
			}
		if(isset($companyid) && $companyid!=''){
					$where .= " AND j.companyid='".$companyid."'";
			}
		 if(isset($religionid) && $religionid!=''){
					$where .= " AND b.religionid='".$religionid."'";
			}
		 if(isset($employeetypeid) && $employeetypeid!=''){
					$where .= " AND d.employeetypeid='".$employeetypeid."'";
			}
		$where .= " order by a.fullname asc ";
		
		$dataReader=Yii::app()->db->createCommand($sql.$where)->queryAll();
		
		$connection = Yii::app()->db;
		$this->pdf->title='Rekap Jenis Karyawan';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','A4');

		$this->pdf->SetFont('Arial','',11);
		$this->pdf->sety($this->pdf->gety()+5);
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->colalign = array('C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(10,70,20,35,70,40,30));
		$this->pdf->colheader = array('No','Nama Karyawan','Tgl Gabung','Agama','Dept','Jenis Karyawan','Pendidikan Terakhir');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L');
		$i=0;
		
		$this->pdf->setFont('Arial','',8);
		foreach($dataReader as $row)
		{
			$i+=1;
			$this->pdf->row(array(
					$i,$row['fullname'],date(Yii::app()->params['dateviewfromdb'], strtotime($row['joindate'])),
					$row['religionname'],$row['structurename'],$row['employeetypename'],$row['educationname']
					));
		}
		$this->pdf->Output();
	}	
	public function RekapThrKaryawan($companyid,$employeeid,$religionid,$employeetypeid,$empplanid,$startdate,$enddate)
    {
			parent::actionDownload();
			
			$connection = Yii::app()->db;
			$this->pdf->title='Rekap THR Karyawan';
			$this->pdf->subtitle='Periode :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		 
			$this->pdf->AddPage('L','A4');
			$this->pdf->text(10,$this->pdf->gety()+2,'AGAMA');
			$sqlreligionname = "select religionname from religion where religionid ='".$religionid."'";
			$religionname = Yii::app()->db->createCommand($sqlreligionname)->queryScalar();
			$this->pdf->text(27,$this->pdf->gety()+2,$religionname);
	
			$this->pdf->SetFont('Arial','',11);
			$this->pdf->sety($this->pdf->gety()+5);

			$this->pdf->colalign = array('L','L','C','C','C','C','C');
			$this->pdf->setwidths(array(10,50,60,45,50,25,30));
			$this->pdf->colheader = array('No','Nama Karyawan','Perusahaan','Struktur','Posisi','Tgl Gabung','Lama Bekerja');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('L','L','C','C','C','C','C');		
			
			$this->pdf->SetFont('Arial','',10);
																		
			$sql = "select a0.employeeid,a0.fullname,g.companyname as companyname,f.structurename,a3.positionname as positionname, a0.joindate, timestampdiff( YEAR, a0.joindate, now() ) as year, timestampdiff( month, a0.joindate, now() )  % 12 as month, FLOOR( timestampdiff( day, a0.joindate, now() ) % 30.4375 ) as day
			from employee a0 
			left join religion a7 on a7.religionid = a0.religionid
			left join position a3 on a3.positionid = a0.positionid
			LEFT JOIN employeeorgstruc e on e.employeeid=a0.employeeid
			LEFT JOIN orgstructure f on f.orgstructureid=e.orgstructureid
			LEFT JOIN company g on g.companyid=f.companyid 
			 ";  
			
			$where = " where timestampdiff(month,a0.joindate, now()) > 4";
			if(isset($religionid) && $religionid!=''){
					$where .= " and a0.religionid='".$religionid."' ";
			}                            
			
			$result = $connection->createCommand($sql.$where)->queryAll();
			$i=1;
			foreach($result as $row){
					$this->pdf->row(array($i,$row['fullname'],$row['companyname'],$row['structurename'],$row['positionname'],date(Yii::app()->params['dateviewfromdb'], strtotime($row['joindate'])),$row['year'].' Th '.$row['month'].' Bln' )); 
					$i++;
			}
			$this->pdf->Output();
	}	
	public function RekapUlangTahunKaryawan($companyid,$employeeid,$religionid,$employeetypeid,$empplanid,$startdate,$enddate)
	{
		 parent::actionDownload();
			$sql = "SELECT a.fullname,a.birthdate,TIMESTAMPDIFF( YEAR, a.birthdate, now() ) as year,
							d.companyid,CONCAT(DATE_FORMAT(a.birthdate,'%m-%d-'),YEAR(CURDATE())) as ulangtahun
							FROM employee a
							join employeeorgstruc b on b.employeeid=a.employeeid
							join orgstructure c on c.orgstructureid=b.orgstructureid
							JOIN company d on d.companyid=c.companyid
							WHERE d.companyid = ".$companyid." and DATE_FORMAT(a.birthdate,'%m %d') BETWEEN DATE_FORMAT('".$startdate."','%m %d') 
							AND DATE_FORMAT('".$enddate."','%m %d') " ;
		
			$where = "AND a.fullname is not null";
			$where .= " ORDER BY DATE_FORMAT(a.birthdate,'%m-%d-'); "; 
		
			$dataReader=Yii::app()->db->createCommand($sql.$where)->queryAll();
		
			if(isset($employeeid) && $employeeid!=''){
					$where .= " AND a.employeeid='".$employeeid."' ";
			}
			if(isset($companyid) && $companyid!=''){
					$where .= " AND d.companyid='".$companyid."'";
			}
		
			$this->pdf->title='Rekap Ulang Tahun Karyawan';
			$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->AddPage('P','A4');

			$this->pdf->SetFont('Arial','',11);
			$this->pdf->sety($this->pdf->gety()+5);
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->colalign = array('C','C','C','C','C');
			$this->pdf->setwidths(array(10,90,35,35,20));
			$this->pdf->colheader = array('No','Nama Karyawan','Tgl Lahir','Tgl Ulang Tahun','Umur');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('L','L','C','C','C');
			$i=0;
		
			$this->pdf->setFont('Arial','',8);
			foreach($dataReader as $row)
			{
				$i+=1;
				$this->pdf->row(array($i,$row['fullname'],date(Yii::app()->params['dateviewfromdb'], strtotime($row['birthdate'])),$row['ulangtahun'],$row['year']));
			}
		$this->pdf->Output();
	}
	public function RincianStrukturKaryawan($companyid,$employeeid,$religionid,$employeetypeid,$empplanid,$startdate,$enddate)
	{
			parent::actionDownload();
			$sql = "select distinct a.employeeid, a.fullname, a.oldnik, c.companycode, substring_index(substring_index(d.structurename, ',', 1), ',', - 1) as structurename, e.positionname, f.employeetypename
							from employee a
							join employeeorgstruc b on b.employeeid = a.employeeid
							join orgstructure d on d.orgstructureid = b.orgstructureid
							join company c on c.companyid = d.companyid
							join position e on e.positionid = a.positionid
							join employeetype f on f.employeetypeid = a.employeetypeid
							where d.companyid = ".$companyid." order by a.fullname asc";
			$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
			$i=0;
			$this->pdf->title='Rincian Struktur Karyawan';
			$this->pdf->AddPage('P','A4');

			$this->pdf->SetFont('Arial','',11);
			$this->pdf->sety($this->pdf->gety()+5);
			$this->pdf->setFont('Arial','B',9);
			$this->pdf->colalign = array('C','C','C','C','C','C');
			$this->pdf->setwidths(array(10,53,15,58,35,20));
			$this->pdf->colheader = array('No','Nama Karyawan','NIK','Struktur','Posisi','Perusahaan');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('L','L','C','L','L','C');
			$this->pdf->setFont('Arial','',8);
			foreach($dataReader as $row)
			{
				$i+=1;
				$this->pdf->row(array($i,$row['fullname'],$row['oldnik'],$row['structurename'],$row['positionname'],$row['companycode']));
			}
			
			$this->pdf->Output();
	}
    
    public function actionDownXls()
    {
        parent::actionDownXLS();
        if(isset($_GET['lro']))
		{
		
			if ($_GET['lro'] == 1){
				$this->LaporanEvaluasiXLS($_GET['company'],$_GET['employeeid'],$_GET['empplanid'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}else
            if($_GET['lro']== 2){
                $this->RekapLaporaEvaluasiPerDokumenXLS($_GET['company'],$_GET['employeeid'],$_GET['empplanid'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
            }else
            if ($_GET['lro'] == 3){
				$this->LaporanKontrakSudahPerpanjangXLS($_GET['company'],$_GET['employeeid'],$_GET['empplanid'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}else
            if($_GET['lro'] == 4){
                $this->LaporanKontrakYangBerakhirXLS($_GET['company'],$_GET['employeeid'],$_GET['empplanid'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
            }else
            if($_GET['lro'] == 5){
                $this->LaporanKejadianKaryawanXLS($_GET['company'],$_GET['employeeid'],$_GET['empplanid'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
            }else
            if($_GET['lro']== 6){
                $this->RekapDataKaryawanXLS($_GET['company'],$_GET['employeeid'],$_GET['religionid'],$_GET['employeetypeid'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
            }else
            if($_GET['lro']== 7){
                $this->RekapJenisKaryawanXLS($_GET['company'],$_GET['employeeid'],$_GET['religionid'],$_GET['employeetypeid'],$_GET['empplanid'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
            }else
            if($_GET['lro']== 8){
                $this->RekapThrKaryawanXLS($_GET['company'],$_GET['employeeid'],$_GET['religionid'],$_GET['employeetypeid'],$_GET['empplanid'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
            }else
            if($_GET['lro']== 9){
                $this->RekapUlangTahunKaryawanXLS($_GET['company'],$_GET['employeeid'],$_GET['religionid'],$_GET['employeetypeid'],$_GET['empplanid'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
            }else
            if($_GET['lro']== 10){
                $this->RincianStrukturKaryawanXLS($_GET['company'],$_GET['employeeid'],$_GET['religionid'],$_GET['employeetypeid'],$_GET['empplanid'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
            }
		}
    }
    
    public function RekapDataKaryawanXLS($companyid,$employeeid,$religionid,$employeetypeid,$startdate,$enddate,$per)
    {
        $this->menuname='rekapdatakaryawan';
        parent::actionDownxls();
        
        $this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
			->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
			//->setCellValueByColumnAndRow(5,1,);
        $line=4;
        
        $sql = "select distinct
        a0.employeeid, a0.fullname, a0.oldnik, a0.birthdate, a0.referenceby, a0.joindate, case when a0.istrial = 1 then 'Ya' else 'Tidak' end as istrial,a0.phoneno,a0.alternateemail,
        case when a0.resigndate is not null or a0.resigndate <> '1970-01-01' then
            a0.resigndate
        else
            '-'
        end as resigndate,
        ifnull(a0.email,'-') as email,ifnull(a0.hpno,'-') as hpno, ifnull(a0.taxno,'-') as taxno,ifnull(a0.dplkno,'-') as dplkno,ifnull(a0.bpjskesno,'-') as bpjskesno,
        ifnull(a0.hpno2,'-') as hpno2, ifnull(a0.accountno,'-') as accountno,
        a3.positionname, a10.levelorgname,a4.employeetypename,a5.sexname,a6.cityname as citybirth, a7.religionname, a8.maritalstatusname,a9.employeestatusname
        from employee a0
        left join position a3 on a3.positionid = a0.positionid
        left join employeetype a4 on a4.employeetypeid = a0.employeetypeid
        left join sex a5 on a5.sexid = a0.sexid
        left join city a6 on a6.cityid = a0.birthcityid
        left join religion a7 on a7.religionid = a0.religionid
        left join maritalstatus a8 on a8.maritalstatusid = a0.maritalstatusid
        left join employeestatus a9 on a9.employeestatusid = a0.employeestatusid
        left join levelorg a10 on a10.levelorgid = a0.levelorgid 
        left join employeeorgstruc a11 on a11.employeeid = a0.employeeid 
        left join orgstructure a2 on a2.orgstructureid = a11.orgstructureid
        left join company a1 on a1.companyid = a2.companyid 
        where a2.companyid = {$companyid} ";
        $where = "";
        
        if (isset($employeeid) && $employeeid!='')
        {
            $where .= " and a0.employeeid = ".$employeeid;
        }
        if(isset($religionid) && $religionid!='')
        {
            $where .= " and a0.religionid = ".$religionid;
        }
        if(isset($employeetypeid) && $employeetypeid!='')
        {
            $where .= " and a0.employeetypeid = ".$employeetypeid;
        }
            
        $connection = Yii::app()->db;
        $command=$connection->createCommand($sql.$where.' order by a0.employeeid asc');
        $dataReader=$command->queryAll();
        
        foreach($dataReader as $row)
        {
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Nama Lengkap')
				->setCellValueByColumnAndRow(3,$line,': '.$row['fullname']);
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Posisi')
				->setCellValueByColumnAndRow(3,$line,': '.$row['positionname']);
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Level')
				->setCellValueByColumnAndRow(3,$line,': '.$row['levelorgname']);
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'NIK Lama')
				->setCellValueByColumnAndRow(3,$line,': '.$row['oldnik']);
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Jenis Karyawan')
				->setCellValueByColumnAndRow(3,$line,': '.$row['employeetypename']);
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Jenis Kelamin')
				->setCellValueByColumnAndRow(3,$line,': '.$row['sexname']);
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Kota Kelahiran')
				->setCellValueByColumnAndRow(3,$line,': '.$row['citybirth']);
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Tanggal Lahir')
				->setCellValueByColumnAndRow(3,$line,": ".date(Yii::app()->params['dateviewfromdb'],strtotime($row['birthdate'])));
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Agama')
				->setCellValueByColumnAndRow(3,$line,': '.$row['religionname']);
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Status Nikah')
				->setCellValueByColumnAndRow(3,$line,': '.$row['maritalstatusname']);
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Referensi')
				->setCellValueByColumnAndRow(3,$line,': '.$row['referenceby']);
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Tanggal Gabung')
				->setCellValueByColumnAndRow(3,$line,": ".date(Yii::app()->params['dateviewfromdb'],strtotime($row['joindate'])));
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Status Karyawan')
				->setCellValueByColumnAndRow(3,$line,': '.$row['employeestatusname']);
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Percobaan')
				->setCellValueByColumnAndRow(3,$line,': '.$row['istrial']);
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Tanggal Resign')
				->setCellValueByColumnAndRow(3,$line,": ".date(Yii::app()->params['dateviewfromdb'],strtotime($row['resigndate'])));
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Email')
				->setCellValueByColumnAndRow(3,$line,': '.$row['email']);
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Telp')
				->setCellValueByColumnAndRow(3,$line,': '.$row['phoneno']);
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Email Cadangan')
				->setCellValueByColumnAndRow(3,$line,': '.$row['alternateemail']);
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'No HP')
				->setCellValueByColumnAndRow(3,$line,': '.$row['hpno']);
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'No HP ke-2')
				->setCellValueByColumnAndRow(3,$line,': '.$row['hpno2']);
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'NPWP')
				->setCellValueByColumnAndRow(3,$line,': '.$row['taxno']);
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,getCatalog('dplkno'))
				->setCellValueByColumnAndRow(3,$line,': '.$row['dplkno']);
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,getCatalog('bpjskesno'))
				->setCellValueByColumnAndRow(3,$line,': '.$row['bpjskesno']);
            $line++;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'No. Rekening Bank')
				->setCellValueByColumnAndRow(3,$line,': '.$row['accountno']);
            $line++;
            
            
            // New Header 1
            $line = $line + 1;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Struktur Organisasi');
            $line++;
            $sqly = "select substring_index(substring_index(a1.structurename, ',', 1), ',', - 1) as structurename, substring_index(substring_index(a1.structurename, ',', 2), ',', - 1) as dept, 
            substring_index(substring_index(a1.structurename, ',', 3), ',', - 1) as divisi, a2.companycode
            from employeeorgstruc a0 
            left join orgstructure a1 on a1.orgstructureid = a0.orgstructureid
            left join company a2 on a2.companyid = a1.companyid
            where a0.employeeid = ".$row['employeeid']."
            and a0.recordstatus = 1
            order by a1.structurename asc";
            $dataReadery=$connection->createCommand($sqly)->queryAll();
            
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'No')
				->setCellValueByColumnAndRow(1,$line,'Struktur')
				->setCellValueByColumnAndRow(2,$line,'Department')
				->setCellValueByColumnAndRow(3,$line,'Divisi')
				->setCellValueByColumnAndRow(4,$line,'Perusahaan');
            $line++;
            
            $i3y=1;
            foreach($dataReadery as $rowy)
            {
                $this->phpExcel->setActiveSheetIndex(0)
				    ->setCellValueByColumnAndRow(0,$line,$i3y)
				    ->setCellValueByColumnAndRow(1,$line,$rowy['structurename'])
				    ->setCellValueByColumnAndRow(2,$line,$rowy['dept'])
				    ->setCellValueByColumnAndRow(3,$line,$rowy['divisi'])
				    ->setCellValueByColumnAndRow(4,$line,$rowy['companycode']);
                $line++;
                $i3y++;
            }
            
            //New Line 2
            $line = $line + 1;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Alamat Karyawan');
            $line++;
            $sqladdress = "select a3.addressname,a3.rt,a3.rw,a3.phoneno,a3.faxno,a3.lat,a3.lng,a3.foto,a1.addresstypename,a2.cityname
            from address a3 
            left join addresstype a1 on a1.addresstypeid = a3.addresstypeid
            left join city a2 on a2.cityid = a3.cityid
            left join employee a0 on a0.addressbookid = a3.addressbookid 
            where a0.employeeid = ".$row['employeeid'];
            $getaddress = $connection->createCommand($sqladdress)->queryAll();
            
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'No')
				->setCellValueByColumnAndRow(1,$line,'Jenis Alamat')
				->setCellValueByColumnAndRow(2,$line,'Alamat')
				->setCellValueByColumnAndRow(3,$line,'Kota')
				->setCellValueByColumnAndRow(4,$line,'Lat')
				->setCellValueByColumnAndRow(5,$line,'Lng');
            $line++;
            
            $xy=1;
            foreach($getaddress as $res)
            {
                $this->phpExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow(0,$line,$xy)
                    ->setCellValueByColumnAndRow(1,$line,$res['addresstypename'])
                    ->setCellValueByColumnAndRow(2,$line,$res['addressname'].' RT '.$res['rt'].' RW '.$res['rw'])
                    ->setCellValueByColumnAndRow(3,$line,$res['cityname'])
                    ->setCellValueByColumnAndRow(4,$line,$res['lat'])
                    ->setCellValueByColumnAndRow(5,$line,$res['lng']);
                $line++;
                $xy++;
            }
            
            // New Line 3
            $line = $line + 1;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Identitas Keluarga Karyawan');
            $line++;
            $sql1 = "select b.familyname,c.sexname,d.cityname,b.birthdate,e.educationname,f.occupationname, g.familyrelationname,b.bpjskesno
            from employee a
            left join employeefamily b on b.employeeid = a.employeeid
            left join sex c on c.sexid = b.sexid
            left join city d on d.cityid = b.cityid
            left join education e on e.educationid = b.educationid
            left join occupation f on f.occupationid = b.occupationid
            left join familyrelation g on g.familyrelationid = b.familyrelationid
            where b.recordstatus = 1 and a.employeeid = ".$row['employeeid'];
            $dataReader1 = $connection->createCommand($sql1)->queryAll();
            
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'No')
				->setCellValueByColumnAndRow(1,$line,'Nama')
				->setCellValueByColumnAndRow(2,$line,'Hubungan')
				->setCellValueByColumnAndRow(3,$line,'Jenis Kelamin')
				->setCellValueByColumnAndRow(4,$line,'Kota')
				->setCellValueByColumnAndRow(5,$line,'Tanggal Lahir')
				->setCellValueByColumnAndRow(6,$line,'Pendidikan')
				->setCellValueByColumnAndRow(7,$line,'BPJS Kesehatan')
				->setCellValueByColumnAndRow(8,$line,'Pekerjaan');
            $line++;
            $i=1;
            foreach($dataReader1 as $row1)
            {
                $this->phpExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow(0,$line,$i)
                    ->setCellValueByColumnAndRow(1,$line,$row1['familyname'])
                    ->setCellValueByColumnAndRow(2,$line,$row1['familyrelationname'])
                    ->setCellValueByColumnAndRow(3,$line,$row1['sexname'])
                    ->setCellValueByColumnAndRow(4,$line,$row1['cityname'])
                    ->setCellValueByColumnAndRow(5,$line,$row1['birthdate'])
                    ->setCellValueByColumnAndRow(6,$line,$row1['educationname'])
                    ->setCellValueByColumnAndRow(7,$line,$row1['bpjskesno'])
                    ->setCellValueByColumnAndRow(8,$line,$row1['occupationname']);
                $line++;
                $i++;
            }
            
            // New Line 4
            $line = $line + 1;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Pendidikan Karyawan');
            $line++;
            $sql2 = "select c.educationname, b.schoolname, d.cityname, b.yeargraduate, case when b.isdiploma = 1 then 'Ada' else 'Tidak Ada' end as isdiploma, b.schooldegree
            from employee a
            left join employeeeducation b on b.employeeid = a.employeeid
            left join education c on c.educationid = b.educationid
            left join city d on d.cityid = b.cityid
            where b.recordstatus = 1 and a.employeeid = ".$row['employeeid'];
            $dataReader2 = $connection->createCommand($sql2)->queryAll();
            
            $this->phpExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow(0,$line,'No')
                    ->setCellValueByColumnAndRow(1,$line,'Pendidikan')
                    ->setCellValueByColumnAndRow(2,$line,'Nama Sekolah')
                    ->setCellValueByColumnAndRow(3,$line,'Asal Sekolah')
                    ->setCellValueByColumnAndRow(4,$line,'Tanggal Lulus')
                    ->setCellValueByColumnAndRow(5,$line,'Sertifikat')
                    ->setCellValueByColumnAndRow(6,$line,'Gelar');
            $line++;
            $ii=1;
            foreach($dataReader2 as $row2)
            {
                $this->phpExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow(0,$line,$ii)
                    ->setCellValueByColumnAndRow(1,$line,$row2['educationname'])
                    ->setCellValueByColumnAndRow(2,$line,$row2['schoolname'])
                    ->setCellValueByColumnAndRow(3,$line,$row2['cityname'])
                    ->setCellValueByColumnAndRow(4,$line,$row2['yeargraduate'])
                    ->setCellValueByColumnAndRow(5,$line,$row2['isdiploma'])
                    ->setCellValueByColumnAndRow(6,$line,$row2['schooldegree']);
                $line++;
                $ii;
            }
            
            // New Line 5
            $line = $line+ 1;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Identitas Keluarga Karyawan');
            $line++;
            $sql4 = "select distinct c.identitytypename,b.identityname
            from employee a
            left join employeeidentity b on b.employeeid = a.employeeid
            left join identitytype c on c.identitytypeid = b.identitytypeid
            where b.recordstatus = 1 and a.employeeid = ".$row['employeeid'];
            $dataReader4 = $connection->createCommand($sql4)->queryAll();
            
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'No')
				->setCellValueByColumnAndRow(1,$line,'Jenis Identitas')
				->setCellValueByColumnAndRow(2,$line,'Nomor Identitas');
            $line++;
            $i4=1;
            foreach($dataReader4 as $row4)
            {
                $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,$i4)
				->setCellValueByColumnAndRow(1,$line,$row4['identitytypename'])
				->setCellValueByColumnAndRow(2,$line,$row4['identityname']);
                $line++;
                $i4++;
            }
            
            // New Line 6
            $line = $line+ 1;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Informal Karyawan');
            $line++;
            $sql5 = "select b.informalname, b.organizer, b.period, case when b.isdiploma = 1 then 'Ada' else 'Tidak Ada' end as isdiploma, case when b.sponsoredby = 1 then 'Ada' else 'Tidak Ada' end as sponsoredby
            from employee a
            join employeeinformal b on b.employeeid = a.employeeid
            where b.recordstatus = 1 and a.employeeid = ".$row['employeeid'];
            $dataReader5 = $connection->createCommand($sql5)->queryAll();
            
            $this->phpExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow(0,$line,'No')
                    ->setCellValueByColumnAndRow(1,$line,'Kursus')
                    ->setCellValueByColumnAndRow(2,$line,'Pelaksana')
                    ->setCellValueByColumnAndRow(3,$line,'Periode')
                    ->setCellValueByColumnAndRow(4,$line,'Sertifikat')
                    ->setCellValueByColumnAndRow(5,$line,'Biaya Sponsor');
            $line++;
            $i5=1;
            foreach($dataReader5 as $row5)
            {
                $this->phpExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow(0,$line,$i5)
                    ->setCellValueByColumnAndRow(1,$line,$row5['informalname'])
                    ->setCellValueByColumnAndRow(2,$line,$row5['organizer'])
                    ->setCellValueByColumnAndRow(3,$line,$row5['period'])
                    ->setCellValueByColumnAndRow(4,$line,$row5['isdiploma'])
                    ->setCellValueByColumnAndRow(5,$line,$row5['sponsoredby']);
                $line++;
                $i5++;
            }
            
            // New Line 7
            $line = $line+ 1;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Pengalaman Kerja Karyawan');
            $line++;
            $sql6= "select b.informalname, b.organizer, b.period, b.isdiploma, b.sponsoredby
            from employee a
            join employeewo b on b.employeeid = a.employeeid
            where b.recordstatus = 1 and a.employeeid = ".$row['employeeid'];
            $dataReader6 = $connection->createCommand($sql6)->queryAll();
            
            $this->phpExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow(0,$line,'No')
                    ->setCellValueByColumnAndRow(1,$line,'Kegiatan')
                    ->setCellValueByColumnAndRow(2,$line,'Pelaksana')
                    ->setCellValueByColumnAndRow(3,$line,'Periode')
                    ->setCellValueByColumnAndRow(4,$line,'Sertifikat')
                    ->setCellValueByColumnAndRow(5,$line,'Biaya Sponsor');
            $line++;
            $i6=1;
            foreach($dataReader6 as $row6)
            {
                $this->phpExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow(0,$line,$i6)
                    ->setCellValueByColumnAndRow(1,$line,$row6['informalname'])
                    ->setCellValueByColumnAndRow(2,$line,$row6['organizer'])
                    ->setCellValueByColumnAndRow(3,$line,$row6['period'])
                    ->setCellValueByColumnAndRow(4,$line,$row6['isdiploma'])
                    ->setCellValueByColumnAndRow(5,$line,$row6['sponsoredby']);
                $line++;
                $i6++;
            }
            
            // New Line 8
            $line = $line+ 1;
            $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Histrory Karyawan');
            $line++;
            $sql7 = "select b.fullname, a.contracttype, a.startdate, a.enddate, a.description
            from employeecontract a
            left join employee b on b.employeeid = a.employeeid
            where a.recordstatus = 1 and b.employeeid = ".$row['employeeid'];
            $dataReader7 = $connection->createCommand($sql7)->queryAll();
            
            $this->phpExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(0,$line,'No')
                ->setCellValueByColumnAndRow(1,$line,'Kontrak ke')
                ->setCellValueByColumnAndRow(2,$line,'Tanggal Mulai')
                ->setCellValueByColumnAndRow(3,$line,'Tanggal Akhir')
                ->setCellValueByColumnAndRow(4,$line,'Keterangan');
            $line++;
            $i7=1;
            foreach($dataReader7 as $row7)
            {
                $this->phpExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow(0,$line,$i7)
                    ->setCellValueByColumnAndRow(1,$line,$row7['contracttype'])
                    ->setCellValueByColumnAndRow(2,$line,$row7['startdate'])
                    ->setCellValueByColumnAndRow(3,$line,$row7['enddate'])
                    ->setCellValueByColumnAndRow(4,$line,$row7['description']);
                $line++;
                $i7++;
            }
            $line = $line + 2;            
        }
        $this->getFooterXLS($this->phpExcel);
    }
}