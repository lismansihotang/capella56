<?php
class OnleavetransController extends Controller
{
	public $menuname = 'onleavetrans';
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
		$onleavetransid = isset ($_POST['onleavetransid']) ? $_POST['onleavetransid'] : '';
                $docdate = isset ($_POST['docdate']) ? $_POST['docdate'] : '';
                $employeeid = isset ($_POST['employeeid']) ? $_POST['employeeid'] : '';
                $onleavetypeid = isset ($_POST['onleavetypeid']) ? $_POST['onleavetypeid'] : '';
                $startdate = isset ($_POST['startdate']) ? $_POST['startdate'] : '';
                $enddate = isset ($_POST['enddate']) ? $_POST['enddate'] : '';
                $description = isset ($_POST['description']) ? $_POST['description'] : '';
                $recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$onleavetransid = isset ($_GET['q']) ? $_GET['q'] : $onleavetransid;
                $docdate = isset ($_GET['q']) ? $_GET['q'] : $docdate;
                $employeeid = isset ($_GET['q']) ? $_GET['q'] : $employeeid;
                $onleavetypeid = isset ($_GET['q']) ? $_GET['q'] : $onleavetypeid;
                $startdate = isset ($_GET['q']) ? $_GET['q'] : $startdate;
                $enddate = isset ($_GET['q']) ? $_GET['q'] : $enddate;
                $description = isset ($_GET['q']) ? $_GET['q'] : $description;
                $recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		
		// pagging
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'onleavetransid';
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
                        ->from('onleavetrans t')
                        ->leftjoin('employee p','p.employeeid=t.employeeid')
                        ->leftjoin('onleavetype q','q.onleavetypeid=t.onleavetypeid')
                        ->where('(p.fullname like :employeeid) or
                                (q.onleavename like :onleavetypeid)',
                                array(':employeeid'=>'%'.$employeeid.'%',
                                    ':onleavetypeid'=>'%'.$onleavetypeid.'%'))
                        ->queryRow();
	
		$result['total'] = $cmd['total'];
		
		$cmd = Yii::app()->db->createCommand()
                        ->select('t.*,p.fullname,q.onleavename')
                        ->from('onleavetrans t')
                        ->leftjoin('employee p','p.employeeid=t.employeeid')
                        ->leftjoin('onleavetype q','q.onleavetypeid=t.onleavetypeid')
                        ->where('(p.fullname like :employeeid) or
                                (q.onleavename like :onleavetypeid)',
                                array(':employeeid'=>'%'.$employeeid.'%',
                                    ':onleavetypeid'=>'%'.$onleavetypeid.'%'))
                        ->offset($offset)
                        ->limit($rows)
                        ->order($sort.' '.$order)
                        ->queryAll();
		
		foreach($cmd as $data)
		{	
			$row[] = array(
        		'onleavetransid'=>$data['onleavetransid'],
                        'docdate'=>$data['docdate'],
                        'employeeid'=>$data['employeeid'],
                        'fullname'=>$data['fullname'],
                        'onleavetypeid'=>$data['onleavetypeid'],
                        'onleavename'=>$data['onleavename'],
                        'startdate'=>$data['startdate'],
                        'enddate'=>$data['enddate'],
                        'description'=>$data['description'],
                        'recordstatus'=>findstatusname("apponleavetrans",$data['recordstatus']),
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
				$sql = 'call Insertonleavetrans(:vdocdate,:vemployeeid,:vonleavetypeid,:vstartdate,:venddate,:vdescription,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call Updateonleavetrans(:vid,:vdocdate,:vemployeeid,:vonleavetypeid,:vstartdate,:venddate,:vdescription,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['onleavetransid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['onleavetransid']);
			}
			$command->bindvalue(':vdocdate',date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])),PDO::PARAM_STR);
                        $command->bindvalue(':vemployeeid',$_POST['employeeid'],PDO::PARAM_STR);
                        $command->bindvalue(':vonleavetypeid',$_POST['onleavetypeid'],PDO::PARAM_STR);
                        $command->bindvalue(':vstartdate',date(Yii::app()->params['datetodb'], strtotime($_POST['startdate'])),PDO::PARAM_STR);
                        $command->bindvalue(':venddate',date(Yii::app()->params['datetodb'], strtotime($_POST['enddate'])),PDO::PARAM_STR);
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
				$sql = 'call Purgeonleavetrans(:vid,:vcreatedby)';
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
				$sql = 'call DeleteOnleaveTrans(:vid,:vcreatedby)';
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
				$sql = 'call ApproveOnleaveTrans(:vid,:vcreatedby)';
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
	  $sql = "select docdate,employeeid,onleavetypeid,startdate,enddate,description,recordstatus
				from onleavetrans a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.onleavetransid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=getCatalog('onleavetrans');
		$this->pdf->AddPage('P');
		//masukkan posisi judul
		$this->pdf->colalign = array('L','L','L','L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(getCatalog('docdate'),
                getCatalog('employeeid'),
                getCatalog('onleavetypeid'),
                getCatalog('startdate'),
                getCatalog('enddate'),
                getCatalog('description'),
                getCatalog('recordstatus'));
		$this->pdf->setwidths(array(40,40,40,40,40,40,40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['docdate'],$row1['employeeid'],$row1['onleavetypeid'],$row1['startdate'],$row1['enddate'],$row1['description'],$row1['recordstatus']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}	

}
