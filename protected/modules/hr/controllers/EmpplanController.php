<?php
class EmpplanController extends Controller {
	public $menuname = 'empplan';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexdetail() {
		if(isset($_GET['grid']))
			echo $this->actionSearchdetail();
		else
			$this->renderPartial('index',array());
	}
	public function actionGetData() {
    if (!isset($_GET['id'])) {
      $dadate              = new DateTime('now');
			$sql = "insert into empplan (empplandate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('insempplan').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'empplanid' => $id
			));
    }
  }
	public function search() {
		header('Content-Type: application/json');
		$empplanid = isset ($_POST['empplanid']) ? $_POST['empplanid'] : '';
		$empplanno = isset ($_POST['empplanno']) ? $_POST['empplanno'] : '';
		$empplanname = isset ($_POST['empplanname']) ? $_POST['empplanname'] : '';
		$empplandate = isset ($_POST['empplandate']) ? $_POST['empplandate'] : '';
		$empplanid = isset ($_GET['q']) ? $_GET['q'] : $empplanid;
		$empplanno = isset ($_GET['q']) ? $_GET['q'] : $empplanno;
		$empplanname = isset ($_GET['q']) ? $_GET['q'] : $empplanname;
		$empplandate = isset ($_GET['q']) ? $_GET['q'] : $empplandate;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.empplanid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('empplan t')
			->where("empplanid like :empplanid 
				and empplanno like :empplanno 
				and empplanname like :empplanname 
				and empplandate like :empplandate
				and t.recordstatus in (".getUserRecordStatus('listempplan').")",
					array(
					':empplanid'=>'%'.$empplanid.'%',
					':empplanno'=>'%'.$empplanno.'%',
					':empplanname'=>'%'.$empplanname.'%',
					':empplandate'=>'%'.$empplandate.'%'
					))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*')			
			->from('empplan t')
			->where("empplanid like :empplanid 
				and empplanno like :empplanno 
				and empplanname like :empplanname 
				and empplandate like :empplandate
				and t.recordstatus in (".getUserRecordStatus('listempplan').")",
					array(
					':empplanid'=>'%'.$empplanid.'%',
					':empplanno'=>'%'.$empplanno.'%',
					':empplanname'=>'%'.$empplanname.'%',
					':empplandate'=>'%'.$empplandate.'%'
					))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
			'empplanid'=>$data['empplanid'],
			'empplanno'=>$data['empplanno'],
			'empplanname'=>$data['empplanname'],
			'empplandate'=>date(Yii::app()->params['dateviewfromdb'], strtotime($data['empplandate'])),
			'useraccess'=>$data['useraccess'],
			'recordstatus'=>$data['statusname'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionSearchDetail() {
		header('Content-Type: application/json');
		$id = 0;	
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
		}
		else
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
		}
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.empplandetailid';
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
				->from('empplandetail t')
				->join('employee b','b.employeeid = t.employeeid')
				->where('empplanid = :empplanid',
						array(':empplanid'=>$id))
				->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
				->select('t.*,b.fullname')			
				->from('empplandetail t')
				->join('employee b','b.employeeid = t.employeeid')
				->where('empplanid = :empplanid',
						array(':empplanid'=>$id))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
			'empplandetailid'=>$data['empplandetailid'],
			'empplanid'=>$data['empplanid'],
			'employeeid'=>$data['employeeid'],
			'fullname'=>$data['fullname'],
			'description'=>$data['description'],
			'objvalue'=>$data['objvalue'],
			'startdate'=>date(Yii::app()->params['dateviewfromdb'], strtotime($data['startdate'])),
			'enddate'=>date(Yii::app()->params['dateviewfromdb'], strtotime($data['enddate']))
			);
		}
		$result=array_merge($result,array('rows'=>$row));;
		echo CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {	
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call InsertEmpplan(:vempplanno,:vempplandate,:vempplanname,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vempplandate',$arraydata[1],PDO::PARAM_STR);
		}
		else {
			$sql = 'call UpdateEmpplan(:vid,:vempplandate,:vempplanname,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vempplandate',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vempplanname',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionSave() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['empplanid'])?$_POST['empplanid']:''),'',
				date(Yii::app()->params['datetodb'], strtotime($_POST['empplandate'])),
				$_POST['empplanname']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
	}
	private function ModifyDataDetail($connection,$arraydata) {		
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertempplandetail(:vempplanid,:vemployeeid,:vdescription,:vobjvalue,:vstartdate,:venddate,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updateempplandetail(:vid,:vempplanid,:vemployeeid,:vdescription,:vobjvalue,:vstartdate,:venddate,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		}
		$command->bindvalue(':vempplanid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vemployeeid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vobjvalue',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vstartdate',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':venddate',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionsavedetail()
	{
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDataDetail($connection,array((isset($_POST['empplandetailid'])?$_POST['empplandetailid']:''),
				$_POST['empplanid'],$_POST['employeeid'],$_POST['description'],$_POST['objvalue'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['startdate'])),
				date(Yii::app()->params['datetodb'], strtotime($_POST['enddate']))));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
	}
	public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveEmpplan(:vid,:vcreatedby)';
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
        $sql     = 'call DeleteEmpplan(:vid,:vcreatedby)';
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
	public function actionPurge() {
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try
			{
				$sql = 'call Purgeempplan(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();				
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollback();
				GetMessage(true,$e->getMessage());
			}
		}
		else {
			GetMessage(true,getcatalog('chooseone'));
		}
	}
	public function actionPurgedetail() {
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeempplandetail(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollback();
				GetMessage(true,$e->getMessage());
			}
		}
		else {
			GetMessage(true,getcatalog('chooseone'));
		}
	}	
	public function actionDownPDF() {
	  parent::actionDownload();
	  $sql = "select empplanno,empplanname,empplandate,useraccess
						from empplan a ";						
		$empplanid = filter_input(INPUT_GET,'empplanid');
		$empplanno = filter_input(INPUT_GET,'empplanno');
		$empplanname = filter_input(INPUT_GET,'empplanname');
		$empplandate = filter_input(INPUT_GET,'empplandate');
		$useraccess = filter_input(INPUT_GET,'useraccess');
		$sql .= " and coalesce(a.empplanid,'') like '%".$empplanid."%' 
			and coalesce(a.empplanno,'') like '%".$empplanno."%'
			and coalesce(a.empplanname,'') like '%".$empplanname."%'
			and coalesce(a.empplandate,'') like '%".$empplandate."%'
			and coalesce(a.useraccess,'') like '%".$useraccess."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.empplanid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by empplanid asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('supplier');
		$this->pdf->AddPage('P',array(400,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L');
		$this->pdf->colheader = array(
			GetCatalog('empplanid'),
			GetCatalog('empplanno'),
																	GetCatalog('empplanname'),
																	GetCatalog('empplandate'),
																	GetCatalog('useraccess'));
		$this->pdf->setwidths(array(15,90,40,55,40,40,80,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L');		
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['empplanid'],$row1['empplanno'],$row1['empplanname'],$row1['empplandate'],$row1['useraccess']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='empplan';
		parent::actionDownxls();
		$sql = "select empplanno,empplanname,empplandate,useraccess
						from empplan a ";						
		$empplanid = filter_input(INPUT_GET,'empplanid');
		$empplanno = filter_input(INPUT_GET,'empplanno');
		$empplanname = filter_input(INPUT_GET,'empplanname');
		$empplandate = filter_input(INPUT_GET,'empplandate');
		$useraccess = filter_input(INPUT_GET,'useraccess');
		$sql .= " and coalesce(a.empplanid,'') like '%".$empplanid."%' 
			and coalesce(a.empplanno,'') like '%".$empplanno."%'
			and coalesce(a.empplanname,'') like '%".$empplanname."%'
			and coalesce(a.empplandate,'') like '%".$empplandate."%'
			and coalesce(a.useraccess,'') like '%".$useraccess."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.empplanid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by empplanid asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('empplanid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('empplanno'))			
			->setCellValueByColumnAndRow(2,2,GetCatalog('empplanname'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('empplandate'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('useraccess'));
			
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['empplanid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['empplanno'])				
				->setCellValueByColumnAndRow(2, $i+1, $row1['empplanname'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['empplandate'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['useraccess']);
			$i+=1;
		}		
		$this->getFooterXLS($this->phpExcel);
	}
}
