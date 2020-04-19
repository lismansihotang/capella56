<?php
class TransoutController extends Controller
{
	public $menuname = 'transout';
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
		$transoutid = isset ($_POST['transoutid']) ? $_POST['transoutid'] : '';
                $docdate = isset ($_POST['docdate']) ? $_POST['docdate'] : '';
                $employeeid = isset ($_POST['employeeid']) ? $_POST['employeeid'] : '';
                $startdate = isset ($_POST['startdate']) ? $_POST['startdate'] : '';
                $enddate = isset ($_POST['enddate']) ? $_POST['enddate'] : '';
                $description = isset ($_POST['description']) ? $_POST['description'] : '';
                $recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$transoutid = isset ($_GET['q']) ? $_GET['q'] : $transoutid;
                $docdate = isset ($_GET['q']) ? $_GET['q'] : $docdate;
                $employeeid = isset ($_GET['q']) ? $_GET['q'] : $employeeid;
                $startdate = isset ($_GET['q']) ? $_GET['q'] : $startdate;
                $enddate = isset ($_GET['q']) ? $_GET['q'] : $enddate;
                $description = isset ($_GET['q']) ? $_GET['q'] : $description;
                $recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		
		// pagging
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'transoutid';
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
                        ->from('transout t')
                        ->leftjoin('employee p','p.employeeid=t.employeeid')
                        ->where('(p.fullname like :employeeid)',
                                array(':employeeid'=>'%'.$employeeid.'%'))
                        ->queryRow();
	
		$result['total'] = $cmd['total'];
		
		$cmd = Yii::app()->db->createCommand()
                        ->select('t.*,p.fullname')
                        ->from('transout t')
                        ->leftjoin('employee p','p.employeeid=t.employeeid')
                        ->where('(p.fullname like :employeeid)',
                                array(':employeeid'=>'%'.$employeeid.'%'))
                        ->offset($offset)
                        ->limit($rows)
                        ->order($sort.' '.$order)
                        ->queryAll();
		
		foreach($cmd as $data)
		{	
			$row[] = array(
                        'transoutid'=>$data['transoutid'],
                        'docdate'=>$data['docdate'],
                        'employeeid'=>$data['employeeid'],
                        'fullname'=>$data['fullname'],
                        'startdate'=>$data['startdate'],
                        'enddate'=>$data['enddate'],
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
				$sql = 'call Inserttransout(:vdocdate,:vemployeeid,:vstartdate,:venddate,:vdescription,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call Updatetransout(:vid,:vdocdate,:vemployeeid,:vstartdate,:venddate,:vdescription,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['transoutid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['transoutid']);
			}
			$command->bindvalue(':vdocdate',date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])),PDO::PARAM_STR);
                        $command->bindvalue(':vemployeeid',$_POST['employeeid'],PDO::PARAM_STR);
                        $command->bindvalue(':vstartdate',date(Yii::app()->params['datetimetodb'], strtotime($_POST['startdate'])),PDO::PARAM_STR);
                        $command->bindvalue(':venddate',date(Yii::app()->params['datetimetodb'], strtotime($_POST['enddate'])),PDO::PARAM_STR);
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
				$sql = 'call Purgetransout(:vid,:vcreatedby)';
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
	
	public function actionDownPDF()
	{
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select docdate,employeeid,startdate,enddate,description,recordstatus
				from transout a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.transoutid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=getCatalog('transout');
		$this->pdf->AddPage('P');
		//masukkan posisi judul
		$this->pdf->colalign = array('L','L','L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(getCatalog('docdate'),
                getCatalog('employeeid'),
                getCatalog('startdate'),
                getCatalog('enddate'),
                getCatalog('description'),
                getCatalog('recordstatus'));
		$this->pdf->setwidths(array(40,40,40,40,40,40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['docdate'],$row1['employeeid'],$row1['startdate'],$row1['enddate'],$row1['description'],$row1['recordstatus']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	

}
