<?php
class GeneratePLCommand extends CConsoleCommand
{
	public function run($args)
	{
		$begindate = new DateTime($args[0]);
		$companyid = $args[1];
		$sql = "call InsertPLMonthRecLajur(".$companyid.", '".$begindate->format('Y-m-d')."')";
  	Yii::app()->db->createCommand($sql)->execute();
	}
}
