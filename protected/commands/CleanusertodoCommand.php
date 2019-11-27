<?php
class CleanusertodoCommand extends CConsoleCommand
{
	public function run($args)
	{
		$connection=Yii::app()->db;
		$sql = 'delete from usertodo where tododate < now()';
		$command=$connection->createCommand($sql);
		$command->execute();
	}
}
