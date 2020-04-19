<?php
class TransinController extends Controller
{
	public $menuname = 'transin';
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
		$transinid = isset ($_POST['transinid']) ? $_POST['transinid'] : '';
		$docdate = isset ($_POST['docdate']) ? $_POST['docdate'] : '';
		$employeeid = isset ($_POST['employeeid']) ? $_POST['employeeid'] : '';
		$description = isset ($_POST['permitinid']) ? $_POST['permitinid'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$transinid = isset ($_GET['q']) ? $_GET['q'] : $transinid;
		$docdate = isset ($_GET['q']) ? $_GET['q'] : $docdate;
		$employeeid = isset ($_GET['q']) ? $_GET['q'] : $employeeid;
		$permitinid = isset ($_GET['q']) ? $_GET['q'] : $permitinid;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		
		// pagging
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'transinid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort,'t.')>0)?$sort:'t.'.$sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order ;
		$offset = ($page-1) * $rows;
		
		$result = array();
		$row = array();
	
		// result
		$cmd = Yii::app()->db->createCommand()
		->select('count(1) as total')
		->from('transin t')
		->leftjoin('employee p','p.employeeid=t.employeeid')
		->where('(p.fullname like :employeeid)',
						array(':employeeid'=>'%'.$employeeid.'%'))
		->queryRow();
	
		$result['total'] = $cmd['total'];
		
		$cmd = Yii::app()->db->createCommand()
	->select('t.*,p.fullname,q.permitinname')
	->from('transin t')
	->leftjoin('employee p','p.employeeid=t.employeeid')
	->leftjoin('permitin q','q.permitinid=t.permitinid')
	->where('(p.fullname like :employeeid)',
					array(':employeeid'=>'%'.$employeeid.'%'))
	->offset($offset)
	->limit($rows)
	->order($sort.' '.$order)
	->queryAll();
		
		foreach($cmd as $data)
		{	
			$row[] = array(
						'transinid'=>$data['transinid'],
						'docdate'=>$data['docdate'],
						'employeeid'=>$data['employeeid'],
						'fullname'=>$data['fullname'],
						'permitinid'=>$data['permitinid'],
						'permitinname'=>$data['permitinname'],
						'description'=>$data['description'],
						'recordstatus'=>findstatusname("apptransout",$data['recordstatus']),
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
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
				$sql = 'call Inserttransin(:vdocdate,:vemployeeid,:vpermitinid,:vdescription,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call Updatetransin(:vid,:vdocdate,:vemployeeid,:vpermitinid,:vdescription,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['transinid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['transinid']);
			}
			$command->bindvalue(':vdocdate',date(Yii::app()->params['datetimetodb'], strtotime($_POST['docdate'])),PDO::PARAM_STR);
			$command->bindvalue(':vemployeeid',$_POST['employeeid'],PDO::PARAM_STR);
			$command->bindvalue(':vpermitinid',$_POST['permitinid'],PDO::PARAM_STR);
			$command->bindvalue(':vdescription',$_POST['description'],PDO::PARAM_STR);
			$command->bindvalue(':vrecordstatus',$_POST['recordstatus'],PDO::PARAM_STR);
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
				$sql = 'call Purgetransin(:vid,:vcreatedby)';
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
				$sql = 'call ApproveTransin(:vonleavetransid,:vlastupdateby)';
				$command=$connection->createCommand($sql);
				foreach($id as $ids)
				{
					$command->bindvalue(':vonleavetransid',$ids,PDO::PARAM_STR);
					$command->bindvalue(':vlastupdateby',Yii::app()->user->name,PDO::PARAM_STR);
					$command->execute();
				}
				$transaction->commit();
				getmessage(true,getcatalog('insertsuccess'),1);
			}
			catch (Exception $e)
			{
				$transaction->rollback();
				getmessage(false,$e->getMessage(),1);
			}
		}
		else
		{
			getmessage(false,getcatalog('chooseone'),1);
		}
	}
	public function actionDownPDF()
	{
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select docdate,employeeid,description,recordstatus
				from transin a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.transinid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=getCatalog('transin');
		$this->pdf->AddPage('P');
		//masukkan posisi judul
		$this->pdf->colalign = array('L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(getCatalog('docdate'),
                getCatalog('employeeid'),
                getCatalog('description'),
                getCatalog('recordstatus'));
		$this->pdf->setwidths(array(40,40,40,40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['docdate'],$row1['employeeid'],$row1['description'],$row1['recordstatus']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	

}
