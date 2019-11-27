<?php
class RunningfaCommand extends CConsoleCommand {
	public function run($args) {
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$sql = 'call RunningFA(date(now()))';
			$command=$connection->createCommand($sql);
			$command->execute();
			$transaction->commit();
		}
		catch(Exception $e) {
			 $transaction->rollBack();
		}
	}
}