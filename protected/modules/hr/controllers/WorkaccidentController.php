<?php
class workaccidentController extends Controller {
	public $menuname = 'workaccident';
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
		$workaccidentid 		= GetSearchText(array('POST','GET','Q'),'workaccidentid');
		$plantcode     		= GetSearchText(array('POST','GET','Q'),'plantcode');
		$employeename   	= GetSearchText(array('POST','GET','Q'),'employeename');
    $structurename     = GetSearchText(array('POST','GET','Q'),'structurename');
    $workaccidentdate  = GetSearchText(array('POST','GET','Q'),'workaccidentdate');
    $workaccidentno 		= GetSearchText(array('POST','GET','Q'),'workaccidentno');
    $workaccidenttypename 		= GetSearchText(array('POST','GET','Q'),'workaccidenttypename');
    $description 			= GetSearchText(array('POST','GET','Q'),'description');
    $page 	= GetSearchText(array('POST','GET'),'page',1,'int');
		$rows 	= GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort 	= GetSearchText(array('POST','GET'),'sort','workaccidentid','int');
		$order 	= GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('workaccident t')
			->leftjoin('employee a','a.employeeid = t.employeeid')
			->leftjoin('plant c','c.plantid = t.plantid')
			->leftjoin('orgstructure d','d.orgstructureid = t.orgstructureid')
			->leftjoin('workaccidenttype e','e.workaccidenttypeid = t.workaccidenttypeid')
			->where("coalesce(workaccidentid,'') like :workaccidentid 
				and coalesce(workaccidentno,'') like :workaccidentno 
				and coalesce(a.fullname,'') like :employeename 
				and coalesce(structurename,'') like :structurename
				and coalesce(plantcode,'') like :plantcode
				",
					array(
					':workaccidentid'=>'%'.$workaccidentid.'%',
					':workaccidentno'=>'%'.$workaccidentno.'%',
					':employeename'=>'%'.$employeename.'%',
					':structurename'=>'%'.$structurename.'%',
					':plantcode'=>'%'.$plantcode.'%'
					))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.fullname,c.plantcode,e.workaccidenttypename,d.structurename')			
			->from('workaccident t')
			->leftjoin('employee a','a.employeeid = t.employeeid')
			->leftjoin('plant c','c.plantid = t.plantid')
			->leftjoin('orgstructure d','d.orgstructureid = t.orgstructureid')
			->leftjoin('workaccidenttype e','e.workaccidenttypeid = t.workaccidenttypeid')
			->where("coalesce(workaccidentid,'') like :workaccidentid 
				and coalesce(workaccidentno,'') like :workaccidentno 
				and coalesce(a.fullname,'') like :employeename 
				and coalesce(structurename,'') like :structurename
				and coalesce(plantcode,'') like :plantcode
				",
					array(
					':workaccidentid'=>'%'.$workaccidentid.'%',
					':workaccidentno'=>'%'.$workaccidentno.'%',
					':employeename'=>'%'.$employeename.'%',
					':structurename'=>'%'.$structurename.'%',
					':plantcode'=>'%'.$plantcode.'%'
					))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
			'workaccidentid'=>$data['workaccidentid'],
			'workaccidentno'=>$data['workaccidentno'],
			'employeeid'=>$data['employeeid'],
			'plantid'=>$data['plantid'],
			'plantcode'=>$data['plantcode'],
			'description'=>$data['description'],
			'fullname'=>$data['fullname'],
			'workaccidenttypeid'=>$data['workaccidenttypeid'],
			'workaccidenttypename'=>$data['workaccidenttypename'],
			'orgstructureid'=>$data['orgstructureid'],
			'structurename'=>$data['structurename'],
			'accidentreport'=>$data['accidentreport'],
			'workaccidentdate'=>date(Yii::app()->params['dateviewfromdb'], strtotime($data['workaccidentdate'])),
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
			$sql = 'call Insertworkaccident(:vworkaccidentdate,:vworkaccidentno,:vplantid,:vworkaccidenttypeid,:vemployeeid,:vorgstructureid,:vaccidentreport,:vdescription,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateworkaccident(:vid,:vworkaccidentdate,:vworkaccidentno,:vplantid,:vworkaccidenttypeid,:vemployeeid,:vorgstructureid,:vaccidentreport,:vdescription,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vworkaccidentdate',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vworkaccidentno',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vplantid',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vworkaccidenttypeid',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vemployeeid',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vorgstructureid',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vaccidentreport',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[8],PDO::PARAM_STR);
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
			$this->ModifyData($connection,array((isset($_POST['workaccidentid'])?$_POST['workaccidentid']:''),
				date(Yii::app()->params['datetodb'], strtotime($_POST['workaccidentdate'])),
				$_POST['workaccidentno'],
				$_POST['plantid'],
				$_POST['workaccidenttypeid'],
				$_POST['employeeid'],
				$_POST['orgstructureid'],
				$_POST['accidentreport'],
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
        $sql     = 'call Approveworkaccident(:vid,:vcreatedby)';
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
        $sql     = 'call Deleteworkaccident(:vid,:vcreatedby)';
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
				$sql = 'call Purgeworkaccident(:vid,:vcreatedby)';
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
				$sql = 'call Purgeworkaccidentdetail(:vid,:vcreatedby)';
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
	  $sql = "select workaccidentno,workaccidentname,workaccidentdate,useraccess
						from workaccident a ";						
		$workaccidentid = filter_input(INPUT_GET,'workaccidentid');
		$workaccidentno = filter_input(INPUT_GET,'workaccidentno');
		$workaccidentname = filter_input(INPUT_GET,'workaccidentname');
		$workaccidentdate = filter_input(INPUT_GET,'workaccidentdate');
		$useraccess = filter_input(INPUT_GET,'useraccess');
		$sql .= " and coalesce(a.workaccidentid,'') like '%".$workaccidentid."%' 
			and coalesce(a.workaccidentno,'') like '%".$workaccidentno."%'
			and coalesce(a.workaccidentname,'') like '%".$workaccidentname."%'
			and coalesce(a.workaccidentdate,'') like '%".$workaccidentdate."%'
			and coalesce(a.useraccess,'') like '%".$useraccess."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.workaccidentid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by workaccidentid asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('supplier');
		$this->pdf->AddPage('P',array(400,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('workaccidentno'),
																	GetCatalog('workaccidentname'),
																	GetCatalog('workaccidentdate'),
																	GetCatalog('useraccess'),
																	GetCatalog('bankname'));
		$this->pdf->setwidths(array(15,90,40,55,40,40,80,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L');		
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['workaccidentid'],$row1['workaccidentno'],$row1['taxno'],$row1['bankaccountno'],$row1['bankname'],$row1['accountowner'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='supplier';
		parent::actionDownxls();
		$sql = "select a.addressbookid,a.fullname,
						ifnull((a.taxno),'-') as taxno,
						ifnull((a.bankaccountno),'-') as bankaccountno,
						ifnull((a.bankname),'-') as bankname,
						ifnull((a.accountowner),'-') as accountowner,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from addressbook a
						where a.isvendor = 1 ";
		$addressbookid = filter_input(INPUT_GET,'addressbookid');
		$fullname = filter_input(INPUT_GET,'fullname');
		$bankname = filter_input(INPUT_GET,'bankname');
		$accountowner = filter_input(INPUT_GET,'accountowner');
		$sql .= " and coalesce(a.addressbookid,'') like '%".$addressbookid."%' 
			and coalesce(a.fullname,'') like '%".$fullname."%'
			and coalesce(a.bankname,'') like '%".$bankname."%'
			and coalesce(a.accountowner,'') like '%".$accountowner."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.addressbookid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by fullname asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('addressbookid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('fullname'))			
			->setCellValueByColumnAndRow(2,2,GetCatalog('taxno'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('bankaccountno'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('bankname'))
			->setCellValueByColumnAndRow(6,2,GetCatalog('accountowner'))
			->setCellValueByColumnAndRow(7,2,GetCatalog('recordstatus'));
			
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['addressbookid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['fullname'])				
				->setCellValueByColumnAndRow(2, $i+1, $row1['taxno'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['bankaccountno'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['bankname'])
				->setCellValueByColumnAndRow(6, $i+1, $row1['accountowner'])
				->setCellValueByColumnAndRow(7, $i+1, $row1['recordstatus']);
			$i+=1;
		}		
		$this->getFooterXLS($this->phpExcel);
	}
}
