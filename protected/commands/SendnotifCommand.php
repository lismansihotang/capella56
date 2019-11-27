<?php
class SendnotifCommand extends CConsoleCommand
{
	public function run($args)
	{
		$connection=Yii::app()->db;
		$connection1=Yii::app()->db;
		$sql = 'delete from userinbox where useremail is null';
		$command=$connection->createCommand($sql);
		$command->execute();
		$sql = 'select distinct a.username,a.useremail
			from userinbox a where a.useremail is not null ';
		$command=$connection->createCommand($sql);
		$datareader = $command->queryAll();
		foreach ($datareader as $row)
		{
			$sql = "select * from userinbox where username = '".$row['username']."'";
			$command = $connection->createCommand($sql);
			$datareader1 = $command->queryAll();
			$messages = '';
			foreach($datareader1 as $row1)
			{
				$messages .= $row1['usermessages'].'<br>';
				$sql1 = 'delete from userinbox where userinboxid = '.$row1['userinboxid'];
				$command1=$connection1->createCommand($sql1);
				$command1->execute();
			}
			try
			{
				$message            = new YiiMailMessage;
				$message->view 			= "notif";     
				$params              = array('messages'=>$messages,'to'=>$row['username']);
				$message->subject    = 'Capella ERP Indonesia - Notification System';
				$message->setBody($params, 'text/html');                
				$message->addTo($row['useremail']);
				$message->from = 'it.notification@kangaroospringbed.com';   
				Yii::app()->mail->send($message); 
			}
			catch(Exception $e)
			{
				
			}
		}
	}
}
