<?php
class CleargidetailramCommand extends CConsoleCommand
{
	public function run($args)
	{
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
			$sql = 'truncate table gidetailram';
			$command=$connection->createCommand($sql);
			$command->execute();
			$transaction->commit();
		}
		catch(Exception $e) // an exception is raised if a query fails
		{
			 $transaction->rollBack();
		}
	}
}
