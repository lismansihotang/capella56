<?php
class EmployeepiutangController extends Controller {
	public $menuname = 'employeepiutang';
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
	public function search() {
		header('Content-Type: application/json');
		$employeepiutangid 		= GetSearchText(array('POST','GET','Q'),'employeepiutangid');
		$plantcode     		= GetSearchText(array('POST','GET','Q'),'plantcode');
		$employeename   	= GetSearchText(array('POST','GET','Q'),'employeename');
    $positionname     = GetSearchText(array('POST','GET','Q'),'positionname');
    $employeepiutangdate  = GetSearchText(array('POST','GET','Q'),'employeepiutangdate');
    $employeepiutangno 		= GetSearchText(array('POST','GET','Q'),'employeepiutangno');
    $description 			= GetSearchText(array('POST','GET','Q'),'description');
    $page 	= GetSearchText(array('POST','GET'),'page',1,'int');
		$rows 	= GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort 	= GetSearchText(array('POST','GET'),'sort','employeepiutangid','int');
		$order 	= GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('employeepiutang t')
			->leftjoin('employee a','a.employeeid = t.employeeid')
			->leftjoin('plant c','c.plantid = t.plantid')
			->leftjoin('orgstructure d','d.orgstructureid = t.orgstructureid')
			->where("coalesce(employeepiutangid,'') like :employeepiutangid 
				and coalesce(employeepiutangno,'') like :employeepiutangno 
				and coalesce(a.fullname,'') like :employeename 
				and coalesce(positionname,'') like :positionname
				and coalesce(plantcode,'') like :plantcode
				",
					array(
					':employeepiutangid'=>'%'.$employeepiutangid.'%',
					':employeepiutangno'=>'%'.$employeepiutangno.'%',
					':employeename'=>'%'.$employeename.'%',
					':positionname'=>'%'.$positionname.'%',
					':plantcode'=>'%'.$plantcode.'%'
					))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.fullname,c.plantcode,d.structurename')			
			->from('employeepiutang t')
			->leftjoin('employee a','a.employeeid = t.employeeid')
			->leftjoin('plant c','c.plantid = t.plantid')
			->leftjoin('orgstructure d','d.orgstructureid = t.orgstructureid')
			->where("coalesce(employeepiutangid,'') like :employeepiutangid 
				and coalesce(employeepiutangno,'') like :employeepiutangno 
				and coalesce(a.fullname,'') like :employeename 
				and coalesce(positionname,'') like :positionname
				and coalesce(plantcode,'') like :plantcode
				",
					array(
					':employeepiutangid'=>'%'.$employeepiutangid.'%',
					':employeepiutangno'=>'%'.$employeepiutangno.'%',
					':employeename'=>'%'.$employeename.'%',
					':positionname'=>'%'.$positionname.'%',
					':plantcode'=>'%'.$plantcode.'%'
					))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
			'employeepiutangid'=>$data['employeepiutangid'],
			'employeepiutangno'=>$data['employeepiutangno'],
			'employeeid'=>$data['employeeid'],
			'plantid'=>$data['plantid'],
			'plantcode'=>$data['plantcode'],
			'description'=>$data['description'],
			'fullname'=>$data['fullname'],
			'positionname'=>$data['positionname'],
			'orgstructureid'=>$data['orgstructureid'],
			'structurename'=>$data['structurename'],
			'employeepiutangdate'=>date(Yii::app()->params['dateviewfromdb'], strtotime($data['employeepiutangdate'])),
			'duedate'=>date(Yii::app()->params['dateviewfromdb'], strtotime($data['duedate'])),
			 'nilai' => Yii::app()->format->formatNumber($data['nilai']),
			'recordstatus' => $data['recordstatus'],
			'statusname' => $data['statusname']
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {	
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertemployeepiutang(:vemployeepiutangdate,:vemployeepiutangno,:vduedate,:vplantid,:vemployeeid,:vorgstructureid,:vnilai,:vdescription,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateemployeepiutang(:vid,:vemployeepiutangdate,:vemployeepiutangno,:vduedate,:vplantid,:vemployeeid,:vorgstructureid,:vnilai,:vdescription,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vemployeepiutangdate',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vemployeepiutangno',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vduedate',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vplantid',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vemployeeid',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vorgstructureid',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vnilai',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionSave() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['employeepiutangid'])?$_POST['employeepiutangid']:''),
				date(Yii::app()->params['datetodb'], strtotime($_POST['employeepiutangdate'])),
				$_POST['employeepiutangno'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['duedate'])),
				$_POST['plantid'],
				$_POST['employeeid'],
				$_POST['orgstructureid'],
				$_POST['nilai'],
				$_POST['description']
				));
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
        $sql     = 'call Approveemployeepiutang(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
				$command->execute();
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
        $sql     = 'call Deleteemployeepiutang(:vid,:vcreatedby)';
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
				$sql = 'call Purgeemployeepiutang(:vid,:vcreatedby)';
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
				$sql = 'call Purgeemployeepiutangdetail(:vid,:vcreatedby)';
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
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['employeepiutangno'] = GetSearchText(array('GET'),'employeepiutangno');
		$this->dataprint['employeename'] = GetSearchText(array('GET'),'employeename');
		$this->dataprint['positionname'] = GetSearchText(array('GET'),'positionname');
		$this->dataprint['structurename'] = GetSearchText(array('GET'),'structurename');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'employeepiutangid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titleplantcode'] = GetCatalog('plant');
		$this->dataprint['titleemployeepiutangno'] = GetCatalog('employeepiutangno');
		$this->dataprint['titleduedate'] = GetCatalog('duedate');
		$this->dataprint['titleemployeepiutangdate'] = GetCatalog('employeepiutangdate');
		$this->dataprint['titlepositionname'] = GetCatalog('positionname');
		$this->dataprint['titlenilai'] = GetCatalog('nilai');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titleemployeename'] = GetCatalog('employee');
		$this->dataprint['titlestructurename'] = GetCatalog('structurename');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}
