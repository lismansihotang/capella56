<?php
class EmployeepayController extends Controller {
	public $menuname = 'employeepay';
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
		$employeepayid 		= GetSearchText(array('POST','GET','Q'),'employeepayid');
		$plantcode     		= GetSearchText(array('POST','GET','Q'),'plantcode');
    $employeepiutangno     = GetSearchText(array('POST','GET','Q'),'employeepiutangno');
    $employeepaydate  = GetSearchText(array('POST','GET','Q'),'employeepaydate');
    $employeepayno 		= GetSearchText(array('POST','GET','Q'),'employeepayno');
    $nilaipiutang 		= GetSearchText(array('POST','GET','Q'),'nilaipiutang');
    $description 			= GetSearchText(array('POST','GET','Q'),'description');
    $page 	= GetSearchText(array('POST','GET'),'page',1,'int');
		$rows 	= GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort 	= GetSearchText(array('POST','GET'),'sort','employeepayid','int');
		$order 	= GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('employeepay t')
			->leftjoin('employeepiutang a','a.employeepiutangid = t.employeepiutangid')
			->leftjoin('plant c','c.plantid = t.plantid')
			->where("coalesce(employeepayid,'') like :employeepayid 
				and coalesce(employeepayno,'') like :employeepayno 
				and coalesce(employeepiutangno,'') like :employeepiutangno
				and coalesce(plantcode,'') like :plantcode
				",
					array(
					':employeepayid'=>'%'.$employeepayid.'%',
					':employeepayno'=>'%'.$employeepayno.'%',
					':employeepiutangno'=>'%'.$employeepiutangno.'%',
					':plantcode'=>'%'.$plantcode.'%'
					))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.employeepiutangno,c.plantcode,a.nilai as nilaipiutang')			
			->from('employeepay t')
			->leftjoin('employeepiutang a','a.employeepiutangid = t.employeepiutangid')
			->leftjoin('plant c','c.plantid = t.plantid')
			->where("coalesce(employeepayid,'') like :employeepayid 
				and coalesce(employeepayno,'') like :employeepayno 
				and coalesce(employeepiutangno,'') like :employeepiutangno
				and coalesce(plantcode,'') like :plantcode
				",
					array(
					':employeepayid'=>'%'.$employeepayid.'%',
					':employeepayno'=>'%'.$employeepayno.'%',
					':employeepiutangno'=>'%'.$employeepiutangno.'%',
					':plantcode'=>'%'.$plantcode.'%'
					))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
			'employeepayid'=>$data['employeepayid'],
			'employeepayno'=>$data['employeepayno'],
			'employeepiutangid'=>$data['employeepiutangid'],
			'plantid'=>$data['plantid'],
			'plantcode'=>$data['plantcode'],
			'description'=>$data['description'],
			'employeepiutangno'=>$data['employeepiutangno'],
			'employeepaydate'=>date(Yii::app()->params['dateviewfromdb'], strtotime($data['employeepaydate'])),
			 'nilai' => Yii::app()->format->formatNumber($data['nilai']),
			 'nilaipiutang' => Yii::app()->format->formatNumber($data['nilaipiutang']),
			 'sisa' => Yii::app()->format->formatNumber($data['sisa']),
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
			$sql = 'call Insertemployeepay(:vemployeepaydate,:vplantid,:vemployeepiutangid,:vnilai,:vdescription,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateemployeepay(:vid,:vemployeepaydate,:vplantid,:vemployeepiutangid,:vnilai,:vdescription,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vemployeepaydate',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vplantid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vemployeepiutangid',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vnilai',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[5],PDO::PARAM_STR);
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
			$this->ModifyData($connection,array((isset($_POST['employeepayid'])?$_POST['employeepayid']:''),
				date(Yii::app()->params['datetodb'], strtotime($_POST['employeepaydate'])),
				$_POST['plantid'],
				$_POST['employeepiutangid'],
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
        $sql     = 'call Approveemployeepay(:vid,:vcreatedby)';
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
        $sql     = 'call Deleteemployeepay(:vid,:vcreatedby)';
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
				$sql = 'call Purgeemployeepay(:vid,:vcreatedby)';
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
				$sql = 'call Purgeemployeepaydetail(:vid,:vcreatedby)';
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
	  $sql = "select a.employeepayid, a.employeepaydate, a.employeepayno, a.nilai, a.sisa, a.description, b.employeepiutangno, c.plantcode
						from employeepay a
						left join employeepiutang b on b.employeepiutangid = a.employeepiutangid
						left join plant c on c.plantid = a.plantid
						";						
		$employeepayid = filter_input(INPUT_GET,'employeepayid');
		$employeepayno = filter_input(INPUT_GET,'employeepayno');
		$employeepiutangno = filter_input(INPUT_GET,'employeepiutangno');
		$employeepaydate = filter_input(INPUT_GET,'employeepaydate');
		$plantcode = filter_input(INPUT_GET,'plantcode');
		$sql .= " where coalesce(a.employeepayid,'') like '%".$employeepayid."%' 
			and coalesce(a.employeepayno,'') like '%".$employeepayno."%'
			and coalesce(b.employeepiutangno,'') like '%".$employeepiutangno."%'
			and coalesce(a.employeepaydate,'') like '%".$employeepaydate."%'
			and coalesce(c.plantcode,'') like '%".$plantcode."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.employeepayid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by employeepayid asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('employeepay');
		$this->pdf->AddPage('P',array(400,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('ID'),
																	GetCatalog('plantcode'),
																	GetCatalog('employeepayno'),
																	GetCatalog('employeepaydate'),
																	GetCatalog('employeepiutangno'),
																	GetCatalog('nilai'),
																	GetCatalog('sisa'),
																	GetCatalog('description'),
																	);
		$this->pdf->setwidths(array(8,40,45,45,40,60,25,50));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L');		
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['employeepayid'],
			$row1['plantcode'],
			$row1['employeepayno'],
			$row1['employeepaydate'],
			$row1['employeepiutangno'],
			Yii::app()->format->formatNumber($row1['nilai']),
			Yii::app()->format->formatNumber($row1['sisa']),
			$row1['description']
			));
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
