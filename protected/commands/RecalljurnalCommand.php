<?php
class RecalljurnalCommand extends CConsoleCommand {
	public function run($args) {
		$begindate = new DateTime($args[0]);
		$enddate = new DateTime($args[1]);
		$transacttype = $args[2];
		$connection=Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
			if ($transacttype == "0") {
				$sql = 'truncate table journaldetail';
				$command=$connection->createCommand($sql);
				$command->execute();
				$sql = 'truncate table genjournal';
				$command=$connection->createCommand($sql);
				$command->execute();
				$sql = 'delete from snrodet where snroid = 36';
				$command=$connection->createCommand($sql);
				$command->execute();
				$sql = 'delete FROM genledger WHERE genledgerid > 468';
				$command=$connection->createCommand($sql);
				$command->execute();					
			}
			$sql = 'alter table genledger auto_increment = 1';
			$command=$connection->createCommand($sql);
			$command->execute();				
			$sql = 'alter table genjournal auto_increment = 1';
			$command=$connection->createCommand($sql);
			$command->execute();				
			$sql = 'alter table journaldetail auto_increment = 1';
			$command=$connection->createCommand($sql);
			$command->execute();				
			$interval = DateInterval::createFromDateString('1 day');
			$period = new DatePeriod($begindate, $interval, $enddate);
			foreach ($period as $dt) {
				echo $dt->format('d-m-Y')."\n";
				
				$sql = "
					select a.bsheaderid 
					from bsheader a
					where date(a.bsdate) = date('".$dt->format('Y-m-d')."')
					and a.recordstatus = 4
					and a.bsheaderno not in (
						select distinct za.referenceno 
						from genjournal za 
					)
					order by bsheaderid asc
				";
				$cmd = $connection->createCommand($sql)->queryAll();
				echo "Stock Opname\n";
				foreach ($cmd as $data) {
					echo 'ID: '.$data['bsheaderid']."\n";
					$sql1 = "call approvejournalbs(".$data['bsheaderid'].",'system')";
					$cmd1 = $connection->createCommand($sql1)->execute();
				}			
				
				$sql = "
					SELECT a.grheaderid 
					FROM grheader a
					WHERE date(a.grdate) = date('".$dt->format('Y-m-d')."')
					and recordstatus = 3
					and a.grno not in (
						select distinct za.referenceno 
						from genjournal za 
					)
					order by grheaderid asc
				";
				$cmd = $connection->createCommand($sql)->queryAll();
				echo "Goods Receipt\n";
				foreach ($cmd as $data) {
					echo 'ID: '.$data['grheaderid']."\n";
					$sql1 = "call approvejournalgr(".$data['grheaderid'].",'system')";
					$cmd1 = $connection->createCommand($sql1)->execute();
				}		
				
				$sql = "
					SELECT a.grreturid 
					FROM grretur a
					WHERE date(a.grreturdate) = date('".$dt->format('Y-m-d')."')
					and recordstatus = 3
					and a.grreturno not in (
						select distinct za.referenceno 
						from genjournal za 
					)
					order by grreturid asc
				";
				$cmd = $connection->createCommand($sql)->queryAll();
				echo "Goods Receipt Retur\n";
				foreach ($cmd as $data) {
					echo 'ID: '.$data['grreturid']."\n";
					$sql1 = "call approvejournalgrretur(".$data['grreturid'].",'system')";
					$cmd1 = $connection->createCommand($sql1)->execute();
				}		
				
				$sql = "
					SELECT a.transstockid 
					FROM transstock a
					WHERE date(a.transstockdate) = date('".$dt->format('Y-m-d')."')
					and recordstatus = 5
					and a.transstockno not in (
						select distinct za.referenceno 
						from genjournal za 
					)
					order by transstockid asc
				";
				$cmd = $connection->createCommand($sql)->queryAll();
				echo "Transfer Stock Exchange\n";
				foreach ($cmd as $data) {
					echo 'ID: '.$data['transstockid']."\n";
					$sql1 = "call approvejournalts(".$data['transstockid'].",'system')";
					$cmd1 = $connection->createCommand($sql1)->execute();
				}		
				
				$sql = "
					SELECT a.giheaderid
					FROM giheader a
					WHERE date(a.gidate) = date('".$dt->format('Y-m-d')."')
					and recordstatus = 3
					and a.gino not in (
						select distinct za.referenceno 
						from genjournal za 
					)
					order by giheaderid asc
				";
				$cmd = $connection->createCommand($sql)->queryAll();
				echo "Goods Issue\n";
				foreach ($cmd as $data) {
					echo 'ID: '.$data['giheaderid']."\n";
					$sql1 = "call approvejournalgi(".$data['giheaderid'].",'system')";
					$cmd1 = $connection->createCommand($sql1)->execute();
				}
				
				$sql = "
					SELECT a.gireturid
					FROM giretur a
					WHERE date(a.gireturdate) = date('".$dt->format('Y-m-d')."')
					and recordstatus = 3
					and a.gireturno not in (
						select distinct za.referenceno 
						from genjournal za 
					)
					order by gireturid asc
				";
				$cmd = $connection->createCommand($sql)->queryAll();
				echo "Goods Issue Retur\n";
				foreach ($cmd as $data) {
					echo 'ID: '.$data['gireturid']."\n";
					$sql1 = "call approvejournalgiretur(".$data['gireturid'].",'system')";
					$cmd1 = $connection->createCommand($sql1)->execute();
				}
				
				$sql = "
					SELECT a.productoutputid
					FROM productoutput a
					WHERE date(a.productoutputdate) = date('".$dt->format('Y-m-d')."')
					and recordstatus = 4
					and a.productoutputno not in (
						select distinct za.referenceno 
						from genjournal za 
					)
					order by productoutputid asc
				";
				$cmd = $connection->createCommand($sql)->queryAll();
				echo "Production Output\n";
				foreach ($cmd as $data) {
					echo 'ID: '.$data['productoutputid']."\n";
					$sql1 = "call approvejournalop(".$data['productoutputid'].",'system')";
					$cmd1 = $connection->createCommand($sql1)->execute();
				}
				
				$sql = "
					SELECT a.invoiceapid
					FROM invoiceap a
					WHERE date(a.invoiceapdate) = date('".$dt->format('Y-m-d')."')
					and recordstatus = 3
					and a.invoiceapno not in (
						select distinct za.referenceno 
						from genjournal za 
					)
					order by invoiceapid asc
				";
				$cmd = $connection->createCommand($sql)->queryAll();
				echo "Invoice AP\n";
				foreach ($cmd as $data) {
					echo 'ID: '.$data['invoiceapid']."\n";
					$sql1 = "call approvejournalinvap(".$data['invoiceapid'].",'system')";
					$cmd1 = $connection->createCommand($sql1)->execute();
				}
				
				$sql = "
					SELECT a.cashbankoutid
					FROM cashbankout a
					WHERE date(a.cashbankoutdate) = date('".$dt->format('Y-m-d')."')
					and recordstatus = 3
					and a.cashbankoutno not in (
						select distinct za.referenceno 
						from genjournal za 
					)
					order by cashbankoutid asc
				";
				$cmd = $connection->createCommand($sql)->queryAll();
				echo "Cash Bank Out\n";
				foreach ($cmd as $data) {
					echo 'ID: '.$data['cashbankoutid']."\n";
					$sql1 = "call approvejournalcashbankout(".$data['cashbankoutid'].",'system')";
					$cmd1 = $connection->createCommand($sql1)->execute();
				}
				
				$sql = "
					SELECT a.cashbankinid
					FROM cashbankin a
					WHERE date(a.cashbankindate) = date('".$dt->format('Y-m-d')."')
					and recordstatus = 3
					and a.cashbankinno not in (
						select distinct za.referenceno 
						from genjournal za 
					)
					order by cashbankinid asc
				";
				$cmd = $connection->createCommand($sql)->queryAll();
				echo "Cash Bank In\n";
				foreach ($cmd as $data) {
					echo 'ID: '.$data['cashbankinid']."\n";
					$sql1 = "call approvejournalcashbankin(".$data['cashbankinid'].",'system')";
					$cmd1 = $connection->createCommand($sql1)->execute();
				}
				
				$sql = "
					SELECT a.cashbankid
					FROM cashbank a
					WHERE date(a.cashbankdate) = date('".$dt->format('Y-m-d')."')
					and recordstatus = 3
					and a.cashbankno not in (
						select distinct za.referenceno 
						from genjournal za 
					)
					order by cashbankid asc
				";
				$cmd = $connection->createCommand($sql)->queryAll();
				echo "Cash Bank \n";
				foreach ($cmd as $data) {
					echo 'ID: '.$data['cashbankid']."\n";
					$sql1 = "call approvejournalcashbank(".$data['cashbankid'].",'system')";
					$cmd1 = $connection->createCommand($sql1)->execute();
				}
				
				$sql = "
					SELECT a.fixassetid 
					FROM fixasset a
					WHERE a.fixassetid in 
					(
						select distinct z.fixassetid 
						from fajurnal z 
						where date(z.susutdate) = date('".$dt->format('Y-m-d')."')
					)
					order by a.fixassetid asc
				";
				$cmd = $connection->createCommand($sql)->queryAll();
				echo "Fix Asset \n";
				foreach ($cmd as $data) {
					echo 'ID: '.$data['fixassetid']."\n";
					$sql1 = "call approvejournalfa(".$data['fixassetid'].",'system')";
					$cmd1 = $connection->createCommand($sql1)->execute();
				}
				
				echo "---------------\n";
			}
			$transaction->commit();
			echo GetMessageConsole('Done');
		}
		catch (Exception $e) {
			$transaction->rollback();
			echo GetMessageConsole($e->getMessage());
		}
	}
}
