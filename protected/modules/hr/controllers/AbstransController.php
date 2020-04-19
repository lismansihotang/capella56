<?php
class AbstransController extends Controller
{
	public $menuname = 'abstrans';
	public function actionIndex()
	{
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	
	public function search()
	{
		header('Content-Type: application/json');
		
		// search 
		$abstransid = isset ($_POST['abstransid']) ? $_POST['abstransid'] : '';
		$employeeid = isset ($_POST['employeeid']) ? $_POST['employeeid'] : '';
		$datetimeclock = isset ($_POST['datetimeclock']) ? $_POST['datetimeclock'] : '';
		$reason = isset ($_POST['reason']) ? $_POST['reason'] : '';
		$status = isset ($_POST['status']) ? $_POST['status'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		
		// pagging
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'abstransid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		
		$result = array();
		$row = array();
	
		// result
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('abstrans t')
			->leftjoin('employee p','p.employeeid=t.employeeid')
			->where('(p.fullname like :employeeid or t.status like :status)',
							array(':employeeid'=>'%'.$employeeid.'%',':status'=>'%'.$status.'%'))
			->queryRow();
	
		$result['total'] = $cmd['total'];
		
		$cmd = Yii::app()->db->createCommand()
			->from('abstrans t')
			->leftjoin('employee p','p.employeeid=t.employeeid')
			->where('(p.fullname like :employeeid or t.status like :status)',
							array(':employeeid'=>'%'.$employeeid.'%',':status'=>'%'.$status.'%'))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		
		foreach($cmd as $data)
		{	
			$row[] = array(
				'abstransid'=>$data['abstransid'],
				'employeeid'=>$data['employeeid'],
				'fullname'=>$data['fullname'],
				'datetimeclock'=>$data['datetimeclock'],
				'reason'=>$data['reason'],
				'status'=>$data['status'],
				'recordstatus'=>findstatusname("appabstrans",$data['recordstatus']),
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	
	public function actionGetLastAbsence()
	{
		header('Content-Type: application/json');
		
		// search 
		$employeeid = isset ($_POST['employeeid']) ? $_POST['employeeid'] : '';
		
		$result = array();
		$row = array();
	
		// result	
		$cmd = Yii::app()->db->createCommand('select t.*,b.fullname 
			from abstrans t 
			left join employee b on b.employeeid = t.employeeid 
			where b.employeeid = '.$employeeid.' 
			order by t.abstransid desc limit 1')
			->queryAll();
		
		foreach($cmd as $data)
		{	
			$row[] = array(
				'abstransid'=>$data['abstransid'],
				'employeeid'=>$data['employeeid'],
				'fullname'=>$data['fullname'],
				'datetimeclock'=>$data['datetimeclock'],
				'reason'=>$data['reason'],
				'status'=>$data['status']
			);
		}
		$result = array('isError'=>'false','msg'=>'');
		$result=array_merge($result,array('rows'=>$row));
		echo CJSON::encode($result);
	}
	
	public function actionUploadPhoto() {
		$data = file_get_contents('php://input');
		if (!(file_put_contents($_GET['fileName'],$data) === FALSE)) 
			echo "File xfer completed."; // file could be empty, though
		else echo "File xfer failed.";
	}
	
	public function actionSave()
	{
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
			if (isset($_POST['isNewRecord']))
			{
				$sql = 'call Insertabstrans(:vemployeeid,:vdatetimeclock,:vreason,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call Updateabstrans(:vid,:vemployeeid,:vdatetimeclock,:vreason,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['abstransid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['abstransid']);
			}
			$command->bindvalue(':vemployeeid',$_POST['employeeid'],PDO::PARAM_STR);
			$command->bindvalue(':vdatetimeclock',date(Yii::app()->params['datetimetodb'], strtotime($_POST['datetimeclock'])),PDO::PARAM_STR);
			$command->bindvalue(':vreason',$_POST['reason'],PDO::PARAM_STR);
			$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
			$command->execute();
			$transaction->commit();			
			getmessage(false,getcatalog('insertsuccess'));
		}
		catch (Exception $e)
		{
			$transaction->rollBack();
			getmessage(true,$e->getMessage());
		}
	}
	
	public function actionPurge()
	{
		header('Content-Type: application/json');
		
		if (isset($_POST['id']))
		{
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try
			{
				$sql = 'call Purgeabstrans(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				foreach($id as $ids)
				{
					$command->bindvalue(':vid',$ids,PDO::PARAM_STR);
					$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
					$command->execute();
				}
				$transaction->commit();
				getmessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e)
			{
				$transaction->rollback();
				getmessage(true,$e->getMessage());
			}
		}
		else
		{
			getmessage(true,getcatalog('chooseone'));
		}
	}
        
	public function actionDelete()
	{
		parent::actionDelete();
		if (isset($_POST['id']))
		{
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try
			{
				//masukkan perintah delete
				$sql = 'call DeleteAbsTrans(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				foreach($id as $ids)
				{
					$command->bindvalue(':vid',$ids,PDO::PARAM_STR);
					$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
					$command->execute();
				}
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
				$sql = 'call ApproveAbsTrans(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				foreach($id as $ids)
				{
					$command->bindvalue(':vid',$ids,PDO::PARAM_STR);
					$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
					$command->execute();
				}
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
	
	public function actionDownPDF()
	{
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select employeeid,datetimeclock,reason,recordstatus
				from abstrans a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.abstransid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=getCatalog('abstrans');
		$this->pdf->AddPage('P');
		//masukkan posisi judul
		$this->pdf->colalign = array('L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(getCatalog('employeeid'),
                getCatalog('datetimeclock'),
                getCatalog('reason'),
                getCatalog('recordstatus'));
		$this->pdf->setwidths(array(40,40,40,40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['employeeid'],$row1['datetimeclock'],$row1['reason'],$row1['recordstatus']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
}