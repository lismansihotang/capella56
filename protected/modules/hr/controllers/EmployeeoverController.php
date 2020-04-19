<?php
class EmployeeoverController extends Controller {
	public $menuname = 'employeeover';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexdetail() {
		if(isset($_GET['grid']))
			echo $this->actionSearchDetail();
		else
			$this->renderPartial('index',array());
	}
	public function actiongetdata() {
		if (isset($_GET['id'])) {
		}
		else
		{
			$dadate = new DateTime('now');
			$sql = "insert into employeeover (overtimedate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('insempover').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'employeeoverid'=>$id));
		}
	}
	public function search() {
		header('Content-Type: application/json');
		$employeeoverid = isset ($_POST['employeeoverid']) ? $_POST['employeeoverid'] : '';
		$overtimeno = isset ($_POST['overtimeno']) ? $_POST['overtimeno'] : '';
		$overtimedate = isset ($_POST['overtimedate']) ? $_POST['overtimedate'] : '';
		$headernote = isset ($_POST['headernote']) ? $_POST['headernote'] : '';
		$employeeoverid = isset ($_GET['q']) ? $_GET['q'] : $employeeoverid;
		$overtimeno = isset ($_GET['q']) ? $_GET['q'] : $overtimeno;
		$overtimedate = isset ($_GET['q']) ? $_GET['q'] : $overtimedate;
		$headernote = isset ($_GET['q']) ? $_GET['q'] : $headernote;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.employeeoverid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('employeeover t')
			->leftjoin('company a','a.companyid = t.companyid')
			->where('(overtimeno like :overtimeno) and 
				(overtimedate like :overtimedate)',
				array(':overtimeno'=>'%'.$overtimeno.'%',
				':overtimedate'=>'%'.$overtimedate.'%'))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.companyname')	
			->from('employeeover t')
			->leftjoin('company a','a.companyid = t.companyid')
			->where('(overtimeno like :overtimeno) or 
				(overtimedate like :overtimedate)',
				array(':overtimeno'=>'%'.$overtimeno.'%',
				':overtimedate'=>'%'.$overtimedate.'%'))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		
		foreach($cmd as $data)
		{	
			$row[] = array(
				'employeeoverid'=>$data['employeeoverid'],
				'companyid'=>$data['companyid'],
				'companyname'=>$data['companyname'],
				'overtimeno'=>$data['overtimeno'],
				'overtimedate'=>date(Yii::app()->params['dateviewfromdb'], strtotime($data['overtimedate'])),
				'headernote'=>$data['headernote'],
				'recordstatus'=>findstatusname("appempover",$data['recordstatus']),
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionsearchdetail() {
		header('Content-Type: application/json');
		$id = 0;	
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
		}
		else
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
		}
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('employeeoverdet t')
			->leftjoin('employee p','p.employeeid=t.employeeid')
			->where('employeeoverid = '.$id)
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,p.fullname')	
			->from('employeeoverdet t')
			->leftjoin('employee p','p.employeeid=t.employeeid')
			->where('employeeoverid = '.$id)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'employeeoverdetid'=>$data['employeeoverdetid'],
				'employeeoverid'=>$data['employeeoverid'],
				'employeeid'=>$data['employeeid'],
				'fullname'=>$data['fullname'],
				'overtimestart'=>$data['overtimestart'],
				'overtimeend'=>$data['overtimeend'],
				'reason'=>$data['reason'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));;
		echo CJSON::encode($result);
	}
	public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveEmployeeovertime(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, 'chooseone');
    }
  }
	public function actionDelete() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call DeleteEmployeeovertime(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  public function actionSave() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
			if (isset($_POST['isNewRecord']))
			{
				$sql = 'call Insertemployeeover(:vcompanyid,:vovertimedate,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call Updateemployeeover(:vid,:vcompanyid,:vovertimedate,:vheadernote,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['employeeoverid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['employeeoverid']);
			}
			$command->bindvalue(':vovertimedate',date(Yii::app()->params['datetodb'], strtotime($_POST['overtimedate'])),PDO::PARAM_STR);
			$command->bindvalue(':vheadernote',$_POST['headernote'],PDO::PARAM_STR);
			$command->bindvalue(':vcompanyid',$_POST['companyid'],PDO::PARAM_STR);
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
  public function actionsavedetail() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
			if (isset($_POST['isNewRecord'])) {
				$sql = 'call Insertemployeeoverdet(:vemployeeoverid,:vemployeeid,:vovertimestart,:vovertimeend,:vreason,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call Updateemployeeoverdet(:vid,:vemployeeoverid,:vemployeeid,:vovertimestart,:vovertimeend,:vreason,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['employeeoverdetid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['employeeoverdetid']);
			}
			$command->bindvalue(':vemployeeoverid',$_POST['employeeoverid'],PDO::PARAM_STR);
			$command->bindvalue(':vemployeeid',$_POST['employeeid'],PDO::PARAM_STR);
			$command->bindvalue(':vovertimestart',date(Yii::app()->params['datetimetodb'], strtotime($_POST['overtimestart'])),PDO::PARAM_STR);
			$command->bindvalue(':vovertimeend',date(Yii::app()->params['datetimetodb'], strtotime($_POST['overtimeend'])),PDO::PARAM_STR);
			$command->bindvalue(':vreason',$_POST['reason'],PDO::PARAM_STR);
			$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
			$command->execute();
			$transaction->commit();
			getmessage(false,getcatalog('insertsuccess'));
		}
		catch (Exception $e) {
			$transaction->rollBack();
			getmessage(true,$e->getMessage());
		}
	}
  public function actionPurge() {
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try
			{
				$sql = 'call Purgeemployeeover(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				foreach($id as $ids) {
					$command->bindvalue(':vid',$ids,PDO::PARAM_STR);
					$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
					$command->execute();
				}
				$transaction->commit();
				getmessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollback();
				getmessage(true,$e->getMessage());
			}
		}
		else
		{
			getmessage(true,getcatalog('chooseone'));
		}
	}
	public function actionPurgedetail() {
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeemployeeoverdet(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				foreach($id as $ids) {
					$command->bindvalue(':vid',$ids,PDO::PARAM_STR);
					$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
					$command->execute();
				}
				$transaction->commit();
				getmessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollback();
				getmessage(true,$e->getMessage());
			}
		}
		else
		{
			getmessage(true,getcatalog('chooseone'));
		}
	}
  public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select overtimeno,overtimedate,recordstatus
				from employeeover a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.employeeoverid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=getCatalog('employeeover');
		$this->pdf->AddPage('P');
		//masukkan posisi judul
		$this->pdf->colalign = array('L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(getCatalog('overtimeno'),
                getCatalog('overtimedate'),
                getCatalog('recordstatus'));
		$this->pdf->setwidths(array(40,40,40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['overtimeno'],$row1['overtimedate'],$row1['recordstatus']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
}