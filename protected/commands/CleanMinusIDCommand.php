<?php
class CleanMinusIDCommand extends CConsoleCommand {
	public function run($args) {
		$connection=Yii::app()->db;
		$connection1=Yii::app()->db;
		$sql = 'call cleanminusid()';
		$command=$connection->createCommand($sql);
		$command->execute();
	}
}
