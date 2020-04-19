<?php
class RecalljurnalController extends Controller {
	public $menuname = 'recalljurnal';
	public function actionIndex() {
		$this->renderPartial('index',array());
	}
	public function actionRun() {
		header('Content-Type: application/json');
		$begindate = date_parse_from_format('d-m-Y',$_POST['startdate']);  //date(Yii::app()->params['datetodb'], strtotime(isset ($_POST['startdate']) ? $_POST['startdate'] : 'now'));
		$month = $begindate['month'];
		$year = $begindate['year'];
		$transacttype = $_POST['transtype'];
		$companyid = $_POST['companyid'];
		$connection=Yii::app()->db;
		$transaction = $connection->beginTransaction();
		$status = '';
		try {
			if ($transacttype == 0) {
				$sql = "truncate table journaldetail";
				$command=$connection->createCommand($sql);
				$command->execute();
				$sql = "truncate table genjournal";
				$command=$connection->createCommand($sql);
				$command->execute();
				$sql = "delete from snrodet where snroid = 36 and plantid in (select zz.plantid from plant zz where zz.companyid = '".$companyid."')";
				$command=$connection->createCommand($sql);
				$command->execute();
				$sql = "delete FROM genledger WHERE postdate >= '2019-10-01' and companyid = '".$companyid."'";
				$command=$connection->createCommand($sql);
				$command->execute();					
				$sql = "delete FROM snrodet WHERE snroid = 36 and plantid in (select zz.plantid from plant zz where zz.companyid = '".$companyid."')";
				$command=$connection->createCommand($sql);
				$command->execute();					
			} else
			if ($transacttype == 1) {
				$sql = "delete from journaldetail where genjournalid in (select genjournalid from genjournal where companyid = ".$companyid." and referenceno <> '-' and month(journaldate) = ".$month." and year(journaldate) = '".$year."')";
				$command=$connection->createCommand($sql);
				$command->execute();
				$sql = "delete from genjournal where companyid = ".$companyid." and referenceno <> '-' and month(journaldate) = '".$month."' and year(journaldate) = '".$year."'";
				$command=$connection->createCommand($sql);
				$command->execute();
				$sql = "delete FROM genledger WHERE postdate >= '2019-10-01' and companyid = ".$companyid." and month(journaldate) = '".$month."' and year(journaldate) = '".$year."'";
				$command=$connection->createCommand($sql);
				$command->execute();					
				$sql = "delete FROM snrodet WHERE snroid = 36 and curmm = ".$month." and curyy = ".$year." and plantid in (select zz.plantid from plant zz where zz.companyid = '".$companyid."')";
				$command=$connection->createCommand($sql);
				$command->execute();					
			}
			$sql = 'alter table journaldetail auto_increment = 1';
			$command=$connection->createCommand($sql);
			$command->execute();				
			$sql = 'alter table genjournal auto_increment = 1';
			$command=$connection->createCommand($sql);
			$command->execute();				
			$sql = 'alter table genledger auto_increment = 1';
			$command=$connection->createCommand($sql);
			$command->execute();				
		  
			$transaction->commit();
			getmessage(false,'Jurnal sudah di generate');
		}
		catch (Exception $e) {
			$transaction->rollback();
			getmessage(true,'Status: '.$status.' Error: '.$e->getMessage());
		}
	}

	public function actionRunStockOpname()
	{
		header('Content-Type: application/json');
		$begindate = date_parse_from_format('d-m-Y',$_POST['startdate']);  //date(Yii::app()->params['datetodb'], strtotime(isset ($_POST['startdate']) ? $_POST['startdate'] : 'now'));
		$month = $begindate['month'];
		$year = $begindate['year'];
		$transacttype = $_POST['transtype'];
		$companyid = $_POST['companyid'];
		$connection=Yii::app()->db;
		$transaction = $connection->beginTransaction();
		$status = '';
		try {
			$sql = "
				select a.bsheaderid 
				from bsheader a
				left join plant b on b.plantid = a.plantid 
				where month(a.bsdate) = ".$month." 
				and year(a.bsdate) = ".$year." 
				and b.companyid = ".$companyid." 
				and a.recordstatus = 4
				and a.bsheaderno not in (
					select distinct za.referenceno 
					from genjournal za 
				)
				order by bsdate asc
			";
			$cmd = $connection->createCommand($sql)->queryAll();
			foreach ($cmd as $data) {
				$status = 'Stock Opname ID: '.$data['bsheaderid'];
				$sql1 = "call approvebsjurnal(".$data['bsheaderid'].",'system')";
				$cmd1 = $connection->createCommand($sql1)->execute();
				$sql1 = "call approvejournalbs(".$data['bsheaderid'].",'system')";
				$cmd1 = $connection->createCommand($sql1)->execute();
			}			
			$transaction->commit();
			getmessage(false,'Jurnal Stock Opname telah digenerate');
		}
		catch (Exception $e) {
			$transaction->rollback();
			getmessage(true,'Status: '.$status.' Error: '.$e->getMessage());
		}
	}

	public function actionRunGR()
	{
		header('Content-Type: application/json');
		$begindate = date_parse_from_format('d-m-Y',$_POST['startdate']);  //date(Yii::app()->params['datetodb'], strtotime(isset ($_POST['startdate']) ? $_POST['startdate'] : 'now'));
		$month = $begindate['month'];
		$year = $begindate['year'];
		$transacttype = $_POST['transtype'];
		$companyid = $_POST['companyid'];
		$connection=Yii::app()->db;
		$transaction = $connection->beginTransaction();
		$status = '';
		try {
			$sql = "
				SELECT a.grheaderid 
				FROM grheader a
				left join plant b on b.plantid = a.plantid 
				WHERE month(a.grdate) = ".$month." 
				and year(a.grdate) = ".$year." 
				and b.companyid = ".$companyid." 
				and a.recordstatus = 3
				and a.grno not in (
					select distinct za.referenceno 
					from genjournal za 
				)
				order by grdate asc
			";
			$cmd = $connection->createCommand($sql)->queryAll();
			foreach ($cmd as $data) {
				$status = 'LPB ID: '.$data['grheaderid'];
				$sql1 = "call approvejournalgr(".$data['grheaderid'].",'system')";
				$cmd1 = $connection->createCommand($sql1)->execute();
			}			
			$transaction->commit();
			getmessage(false,'Jurnal LPB telah digenerate');
		}
		catch (Exception $e) {
			$transaction->rollback();
			getmessage(true,'Status: '.$status.' Error: '.$e->getMessage());
		}
	}

	public function actionRunGRretur()
	{
		header('Content-Type: application/json');
		$begindate = date_parse_from_format('d-m-Y',$_POST['startdate']);  //date(Yii::app()->params['datetodb'], strtotime(isset ($_POST['startdate']) ? $_POST['startdate'] : 'now'));
		$month = $begindate['month'];
		$year = $begindate['year'];
		$transacttype = $_POST['transtype'];
		$companyid = $_POST['companyid'];
		$connection=Yii::app()->db;
		$transaction = $connection->beginTransaction();
		$status = '';
		try {
			$sql = "
				SELECT a.grreturid 
				FROM grretur a 
				left join plant b on b.plantid = a.plantid 
				WHERE month(a.grreturdate) = ".$month." 
				and year(a.grreturdate) = ".$year." 
				and b.companyid = ".$companyid." 
				and a.recordstatus = 3
				and a.grreturno not in (
					select distinct za.referenceno 
					from genjournal za 
				)
				order by grreturdate asc
			";
			$cmd = $connection->createCommand($sql)->queryAll();
			foreach ($cmd as $data) {
				$status = 'Retur Pembelian ID: '.$data['grreturid'];
				$sql1 = "call approvejournalgrretur(".$data['grreturid'].",'system')";
				$cmd1 = $connection->createCommand($sql1)->execute();
			}	
			$transaction->commit();
			getmessage(false,'Jurnal Retur Pembelian telah digenerate');
		}
		catch (Exception $e) {
			$transaction->rollback();
			getmessage(true,'Status: '.$status.' Error: '.$e->getMessage());
		}
	}

	public function actionRunTransStock()
	{
		header('Content-Type: application/json');
		$begindate = date_parse_from_format('d-m-Y',$_POST['startdate']);  //date(Yii::app()->params['datetodb'], strtotime(isset ($_POST['startdate']) ? $_POST['startdate'] : 'now'));
		$month = $begindate['month'];
		$year = $begindate['year'];
		$transacttype = $_POST['transtype'];
		$companyid = $_POST['companyid'];
		$connection=Yii::app()->db;
		$transaction = $connection->beginTransaction();
		$status = '';
		try {
			$sql = "
				SELECT a.transstockid 
				FROM transstock a
				left join plant b on b.plantid = a.plantid 
				WHERE month(a.transstockdate) = ".$month." 
				and year(a.transstockdate) = ".$year." 
				and b.companyid = ".$companyid." 
				and a.recordstatus = 5
				and a.transstockno not in (
					select distinct za.referenceno 
					from genjournal za 
				)
				order by transstockdate asc
			";
			$cmd = $connection->createCommand($sql)->queryAll();
			foreach ($cmd as $data) {
				$status = 'Transfer Gudang ID: '.$data['transstockid'];
				$sql1 = "call approvejournalts(".$data['transstockid'].",'system')";
				$cmd1 = $connection->createCommand($sql1)->execute();
			}
			$transaction->commit();
			getmessage(false,'Jurnal Retur Pembelian telah digenerate');
		}
		catch (Exception $e) {
			$transaction->rollback();
			getmessage(true,'Status: '.$status.' Error: '.$e->getMessage());
		}
	}

	public function actionRunGiheader()
	{
		header('Content-Type: application/json');
		$begindate = date_parse_from_format('d-m-Y',$_POST['startdate']);  //date(Yii::app()->params['datetodb'], strtotime(isset ($_POST['startdate']) ? $_POST['startdate'] : 'now'));
		$month = $begindate['month'];
		$year = $begindate['year'];
		$transacttype = $_POST['transtype'];
		$companyid = $_POST['companyid'];
		$connection=Yii::app()->db;
		$transaction = $connection->beginTransaction();
		$status = '';
		try {
			$sql = "
				SELECT a.giheaderid
				FROM giheader a
				left join plant b on b.plantid = a.plantid 
				WHERE month(a.gidate) = ".$month." 
				and year(a.gidate) = ".$year." 
				and b.companyid = ".$companyid." 
				and a.recordstatus = 3
				and a.gino not in (
					select distinct za.referenceno 
					from genjournal za 
				)
				order by gidate asc
			";
			$cmd = $connection->createCommand($sql)->queryAll();
			foreach ($cmd as $data) {
				$status = 'Surat Jalan ID: '.$data['giheaderid'];
				$sql1 = "call approvegijurnal(".$data['giheaderid'].",'system')";
				$cmd1 = $connection->createCommand($sql1)->execute();
				$sql1 = "call approvejournalgi(".$data['giheaderid'].",'system')";
				$cmd1 = $connection->createCommand($sql1)->execute();
			}
			$transaction->commit();
			getmessage(false,'Jurnal Retur Pembelian telah digenerate');
		}
		catch (Exception $e) {
			$transaction->rollback();
			getmessage(true,'Status: '.$status.' Error: '.$e->getMessage());
		}
	}

	public function actionRunGiretur()
	{
		header('Content-Type: application/json');
		$begindate = date_parse_from_format('d-m-Y',$_POST['startdate']);  //date(Yii::app()->params['datetodb'], strtotime(isset ($_POST['startdate']) ? $_POST['startdate'] : 'now'));
		$month = $begindate['month'];
		$year = $begindate['year'];
		$transacttype = $_POST['transtype'];
		$companyid = $_POST['companyid'];
		$connection=Yii::app()->db;
		$transaction = $connection->beginTransaction();
		$status = '';
		try {
			$sql = "
				SELECT a.gireturid
				FROM giretur a 
				left join plant b on b.plantid = a.plantid 
				WHERE month(a.gireturdate) = ".$month." 
				and year(a.gireturdate) = ".$year." 
				and b.companyid = ".$companyid." 
				and a.recordstatus = 3
				and a.gireturno not in (
					select distinct za.referenceno 
					from genjournal za 
				)
				order by gireturdate asc
			";
			$cmd = $connection->createCommand($sql)->queryAll();
			foreach ($cmd as $data) {
				$status = 'Retur Penjualan ID: '.$data['gireturid'];
				$sql1 = "call approvejournalgiretur(".$data['gireturid'].",'system')";
				$cmd1 = $connection->createCommand($sql1)->execute();
			}
			$transaction->commit();
			getmessage(false,'Jurnal Retur Penjualan telah digenerate');
		}
		catch (Exception $e) {
			$transaction->rollback();
			getmessage(true,'Status: '.$status.' Error: '.$e->getMessage());
		}
	}

	public function actionRuninvoiceap()
	{
		header('Content-Type: application/json');
		$begindate = date_parse_from_format('d-m-Y',$_POST['startdate']);  //date(Yii::app()->params['datetodb'], strtotime(isset ($_POST['startdate']) ? $_POST['startdate'] : 'now'));
		$month = $begindate['month'];
		$year = $begindate['year'];
		$transacttype = $_POST['transtype'];
		$companyid = $_POST['companyid'];
		$connection=Yii::app()->db;
		$transaction = $connection->beginTransaction();
		$status = '';
		try {
			$sql = "
				SELECT a.invoiceapid
				FROM invoiceap a 
				WHERE month(a.invoiceapdate) = ".$month." 
				and year(a.invoiceapdate) = ".$year." 
				and a.companyid = ".$companyid." 
				and a.recordstatus = 3
				and a.invoiceapno not in (
					select distinct za.referenceno 
					from genjournal za 
				)
				order by invoiceapdate asc
			";
			$cmd = $connection->createCommand($sql)->queryAll();
			foreach ($cmd as $data) {
				$status = 'Faktur Pembelian ID: '.$data['invoiceapid'];
				$sql1 = "call approvejournalinvap(".$data['invoiceapid'].",'system')";
				$cmd1 = $connection->createCommand($sql1)->execute();
			}

			$transaction->commit();
			getmessage(false,'Jurnal Faktur Pembelian telah digenerate');
		}
		catch (Exception $e) {
			$transaction->rollback();
			getmessage(true,'Status: '.$status.' Error: '.$e->getMessage());
		}
	}

	public function actionRunCashBankOut()
	{
		header('Content-Type: application/json');
		$begindate = date_parse_from_format('d-m-Y',$_POST['startdate']);  //date(Yii::app()->params['datetodb'], strtotime(isset ($_POST['startdate']) ? $_POST['startdate'] : 'now'));
		$month = $begindate['month'];
		$year = $begindate['year'];
		$transacttype = $_POST['transtype'];
		$companyid = $_POST['companyid'];
		$connection=Yii::app()->db;
		$transaction = $connection->beginTransaction();
		$status = '';
		try {
			$sql = "
				SELECT a.cashbankoutid
				FROM cashbankout a
				WHERE month(a.cashbankoutdate) = ".$month." 
				and year(a.cashbankoutdate) = ".$year." 
				and a.companyid = ".$companyid." 
				and a.recordstatus = 3
				and a.cashbankoutno not in (
					select distinct za.referenceno 
					from genjournal za 
				)
				order by cashbankoutdate asc
			";
			$cmd = $connection->createCommand($sql)->queryAll();
			foreach ($cmd as $data) {
				$status = 'Pengeluaran Kas/Bank ID: '.$data['cashbankoutid'];
				$sql1 = "call approvejournalcashbankout(".$data['cashbankoutid'].",'system')";
				$cmd1 = $connection->createCommand($sql1)->execute();
			}

			$transaction->commit();
			getmessage(false,'Jurnal Pengeluaran Kas/Bank telah digenerate');
		}
		catch (Exception $e) {
			$transaction->rollback();
			getmessage(true,'Status: '.$status.' Error: '.$e->getMessage());
		}
	}

	public function actionRunProduction()
	{
		header('Content-Type: application/json');
		$begindate = date_parse_from_format('d-m-Y',$_POST['startdate']);  //date(Yii::app()->params['datetodb'], strtotime(isset ($_POST['startdate']) ? $_POST['startdate'] : 'now'));
		$month = $begindate['month'];
		$year = $begindate['year'];
		$transacttype = $_POST['transtype'];
		$companyid = $_POST['companyid'];
		$connection=Yii::app()->db;
		$transaction = $connection->beginTransaction();
		$status = '';
		try {
			$sql = "
				SELECT a.productoutputid
				FROM productoutput a 
				left join plant b on b.plantid = a.plantid 
				WHERE month(a.productoutputdate) = ".$month." 
				and year(a.productoutputdate) = ".$year." 
				and b.companyid = ".$companyid." 
				and a.recordstatus >= 3
				and a.productoutputno not in (
					select distinct za.referenceno 
					from genjournal za 
				)
				order by productoutputdate asc
			";
			$cmd = $connection->createCommand($sql)->queryAll();
			foreach ($cmd as $data) {
				$status = 'Hasil Produksi ID: '.$data['productoutputid'];
				$sql1 = "call approveopj1(".$data['productoutputid'].",'system')";
				$cmd1 = $connection->createCommand($sql1)->execute();
				$sql1 = "call approvejournalop(".$data['productoutputid'].",'system')";
				$cmd1 = $connection->createCommand($sql1)->execute();
			}

			$transaction->commit();
			getmessage(false,'Jurnal Hasil Produksi telah digenerate');
		}
		catch (Exception $e) {
			$transaction->rollback();
			getmessage(true,'Status: '.$status.' Error: '.$e->getMessage());
		}
	}

	public function actionRunCashbankin()
	{
		header('Content-Type: application/json');
		$begindate = date_parse_from_format('d-m-Y',$_POST['startdate']);  //date(Yii::app()->params['datetodb'], strtotime(isset ($_POST['startdate']) ? $_POST['startdate'] : 'now'));
		$month = $begindate['month'];
		$year = $begindate['year'];
		$transacttype = $_POST['transtype'];
		$companyid = $_POST['companyid'];
		$connection=Yii::app()->db;
		$transaction = $connection->beginTransaction();
		$status = '';
		try {
			$sql = "
				SELECT a.cashbankinid
				FROM cashbankin a 
				WHERE month(a.cashbankindate) = ".$month." 
				and year(a.cashbankindate) = ".$year." 
				and a.companyid = ".$companyid." 
				and a.recordstatus = 3
				and a.cashbankinno not in (
					select distinct za.referenceno 
					from genjournal za 
				)
				order by cashbankindate asc
			";
			$cmd = $connection->createCommand($sql)->queryAll();
			foreach ($cmd as $data) {
				$status = 'Penerimaan Kas/Bank ID: '.$data['cashbankinid'];
				$sql1 = "call approvejournalcashbankin(".$data['cashbankinid'].",'system')";
				$cmd1 = $connection->createCommand($sql1)->execute();
			}

			$transaction->commit();
			getmessage(false,'Jurnal Retur Pembelian telah digenerate');
		}
		catch (Exception $e) {
			$transaction->rollback();
			getmessage(true,'Status: '.$status.' Error: '.$e->getMessage());
		}
	}

	public function actionRunCashbank()
	{
		header('Content-Type: application/json');
		$begindate = date_parse_from_format('d-m-Y',$_POST['startdate']);  //date(Yii::app()->params['datetodb'], strtotime(isset ($_POST['startdate']) ? $_POST['startdate'] : 'now'));
		$month = $begindate['month'];
		$year = $begindate['year'];
		$transacttype = $_POST['transtype'];
		$companyid = $_POST['companyid'];
		$connection=Yii::app()->db;
		$transaction = $connection->beginTransaction();
		$status = '';
		try {
			$sql = "
				SELECT a.cashbankid
				FROM cashbank a
				WHERE month(a.cashbankdate) = ".$month." 
				and year(a.cashbankdate) = ".$year." 
				and a.companyid = ".$companyid." 
				and a.recordstatus = 3
				and a.cashbankno not in (
					select distinct za.referenceno 
					from genjournal za 
				)
				order by cashbankdate asc
			";
			$cmd = $connection->createCommand($sql)->queryAll();
			foreach ($cmd as $data) {
				$status = 'Kas/Bank Umum ID: '.$data['cashbankid'];
				$sql1 = "call approvejournalcashbank(".$data['cashbankid'].",'system')";
				$cmd1 = $connection->createCommand($sql1)->execute();
			}		

			$transaction->commit();
			getmessage(false,'Jurnal Kas/Bank telah digenerate');
		}
		catch (Exception $e) {
			$transaction->rollback();
			getmessage(true,'Status: '.$status.' Error: '.$e->getMessage());
		}
	}

	public function actionRunInvoiceAR()
	{
		header('Content-Type: application/json');
		$begindate = date_parse_from_format('d-m-Y',$_POST['startdate']);  //date(Yii::app()->params['datetodb'], strtotime(isset ($_POST['startdate']) ? $_POST['startdate'] : 'now'));
		$month = $begindate['month'];
		$year = $begindate['year'];
		$transacttype = $_POST['transtype'];
		$companyid = $_POST['companyid'];
		$connection=Yii::app()->db;
		$transaction = $connection->beginTransaction();
		$status = '';
		try {
			$sql = "
				SELECT a.invoicearid
				FROM invoicear a
				WHERE month(a.invoiceardate) = ".$month." 
				and year(a.invoiceardate) = ".$year." 
				and a.companyid = ".$companyid." 
				and a.recordstatus = 3
				and a.invoicearno not in (
					select distinct za.referenceno 
					from genjournal za 
				)
				order by invoiceardate asc
			";
			$cmd = $connection->createCommand($sql)->queryAll();
			foreach ($cmd as $data) {
				$status = 'Faktur Penjualan ID: '.$data['invoicearid'];
				$sql1 = "call approvejournalinvar(".$data['invoicearid'].",'system')";
				$cmd1 = $connection->createCommand($sql1)->execute();
			}	

			$transaction->commit();
			getmessage(false,'Jurnal KFaktur Penjualan telah digenerate');
		}
		catch (Exception $e) {
			$transaction->rollback();
			getmessage(true,'Status: '.$status.' Error: '.$e->getMessage());
		}
	}

	public function actionRunFixAsset()
	{
		header('Content-Type: application/json');
		$begindate = date_parse_from_format('d-m-Y',$_POST['startdate']);  //date(Yii::app()->params['datetodb'], strtotime(isset ($_POST['startdate']) ? $_POST['startdate'] : 'now'));
		$month = $begindate['month'];
		$year = $begindate['year'];
		$transacttype = $_POST['transtype'];
		$companyid = $_POST['companyid'];
		$connection=Yii::app()->db;
		$transaction = $connection->beginTransaction();
		$status = '';
		try {
			$sql = "
				select distinct z.fixassetid,z.susutdate 
				from fajurnal z 
				where month(z.susutdate) = ".$month." 
				and year(z.susutdate) = ".$year." 
				and z.companyid = ".$companyid." 
			";
			$cmd = $connection->createCommand($sql)->queryAll();
			foreach ($cmd as $data) {
				$status = 'Fix Asset Jurnal ID: '.$data['fixassetid'];
				$sql1 = "call approvejournalfa(".$data['fixassetid'].",'".$data['susutdate']."','system')";
				$cmd1 = $connection->createCommand($sql1)->execute();
			}	

			$transaction->commit();
			getmessage(false,'Jurnal Fix Asset telah digenerate');
		}
		catch (Exception $e) {
			$transaction->rollback();
			getmessage(true,'Status: '.$status.' Error: '.$e->getMessage());
		}
	}

	public function actionRunNotaGRR()
	{
		header('Content-Type: application/json');
		$begindate = date_parse_from_format('d-m-Y',$_POST['startdate']);  //date(Yii::app()->params['datetodb'], strtotime(isset ($_POST['startdate']) ? $_POST['startdate'] : 'now'));
		$month = $begindate['month'];
		$year = $begindate['year'];
		$transacttype = $_POST['transtype'];
		$companyid = $_POST['companyid'];
		$connection=Yii::app()->db;
		$transaction = $connection->beginTransaction();
		$status = '';
		try {
			$sql = "
				SELECT a.notagrrid 
				FROM notagrr a
				left join plant b on b.plantid = a.plantid 
				WHERE month(a.notagrrdate) = ".$month." 
				and year(a.notagrrdate) = ".$year." 
				and b.companyid = ".$companyid." 
				and a.recordstatus = 3
				and a.notagrrno not in (
					select distinct za.referenceno 
					from genjournal za 
				)
				order by notagrrdate asc
			";
			$cmd = $connection->createCommand($sql)->queryAll();
			foreach ($cmd as $data) {
				$status = 'Nota Retur Pembelian ID: '.$data['notagrrid'];
				$sql1 = "call approvenotagrrjurnal(".$data['notagrrid'].",'system')";
				$cmd1 = $connection->createCommand($sql1)->execute();
				$sql1 = "call approvejournalnotagrr(".$data['notagrrid'].",'system')";
				$cmd1 = $connection->createCommand($sql1)->execute();
			}			
			$transaction->commit();
			getmessage(false,'Jurnal Nota Retur Pembelian telah digenerate');
		}
		catch (Exception $e) {
			$transaction->rollback();
			getmessage(true,'Status: '.$status.' Error: '.$e->getMessage());
		}
	}

	public function actionRunNotaGIR()
	{
		header('Content-Type: application/json');
		$begindate = date_parse_from_format('d-m-Y',$_POST['startdate']);  //date(Yii::app()->params['datetodb'], strtotime(isset ($_POST['startdate']) ? $_POST['startdate'] : 'now'));
		$month = $begindate['month'];
		$year = $begindate['year'];
		$transacttype = $_POST['transtype'];
		$companyid = $_POST['companyid'];
		$connection=Yii::app()->db;
		$transaction = $connection->beginTransaction();
		$status = '';
		try {
			$sql = "
				SELECT a.notagirid 
				FROM notagir a
				left join plant b on b.plantid = a.plantid 
				WHERE month(a.notagirdate) = ".$month." 
				and year(a.notagirdate) = ".$year." 
				and b.companyid = ".$companyid." 
				and a.recordstatus = 3
				and a.notagirno not in (
					select distinct za.referenceno 
					from genjournal za 
				)
				order by notagirdate asc
			";
			$cmd = $connection->createCommand($sql)->queryAll();
			foreach ($cmd as $data) {
				$status = 'Nota Retur Penjualan ID: '.$data['notagirid'];
				$sql1 = "call approvenotagirjurnal(".$data['notagirid'].",'system')";
				$cmd1 = $connection->createCommand($sql1)->execute();
				$sql1 = "call approvejournalnotagir(".$data['notagirid'].",'system')";
				$cmd1 = $connection->createCommand($sql1)->execute();
			}			
			$transaction->commit();
			getmessage(false,'Jurnal Nota Retur Penjualan telah digenerate');
		}
		catch (Exception $e) {
			$transaction->rollback();
			getmessage(true,'Status: '.$status.' Error: '.$e->getMessage());
		}
	}
}