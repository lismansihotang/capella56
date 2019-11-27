<?php
class RecalljurnalCommand extends CConsoleCommand
{
	public function run($args)
	{
		$connection=Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
			$begindate = new DateTime($args[0]);
			$enddate = new DateTime($args[1]);
			$sql = "UPDATE productstockdet a
				SET averageprice = getbuyprice('stock',a.productid,a.slocid,a.referenceno)
				WHERE a.referenceno LIKE 'TSO%'";
			$command=$connection->createCommand($sql);
			$sql = "UPDATE productstockdet a
				SET averageprice = getbuyprice('gr',a.productid,a.slocid,a.referenceno)
				WHERE a.referenceno LIKE 'LPB%'";
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
