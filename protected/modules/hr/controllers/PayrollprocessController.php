<?php
class PayrollprocessController extends Controller
{
        public $menuname = 'payrollprocess';
        public function actionIndex() 
        {
                $this->renderPartial('index',array());
        }
        
		public function actionApprove()
	{
		parent::actionApprove();
		if (isset($_POST['id']))
		{
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try
			{
				$sql = 'call payrollprocess(:vpayrollperiodid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				
					$command->bindvalue(':vpayrollperiodid',$id,PDO::PARAM_STR);
					$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
					$command->execute();
				$transaction->commit();
				getmessage(false,getcatalog('insertsuccess'),1);
			}
			catch (Exception $e)
			{
				$transaction->rollback();
				getmessage(true,$e->getMessage(),1);
			}
		}
		else
		{
			getmessage(true,getcatalog('chooseone'),1);
		}
	}
}

