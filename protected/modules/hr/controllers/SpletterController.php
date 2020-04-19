<?php
class SpletterController extends Controller {
	public $menuname = 'spletter';
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
		$spletterid = isset ($_POST['spletterid']) ? $_POST['spletterid'] : '';
		$spletterno = isset ($_POST['spletterno']) ? $_POST['spletterno'] : '';
		$employeename = isset ($_POST['employeename']) ? $_POST['employeename'] : '';
		$splettername = isset ($_POST['splettername']) ? $_POST['splettername'] : '';
		$spletterid = isset ($_GET['q']) ? $_GET['q'] : $spletterid;
		$spletterno = isset ($_GET['q']) ? $_GET['q'] : $spletterno;
		$employeename = isset ($_GET['q']) ? $_GET['q'] : $employeename;
		$splettername = isset ($_GET['q']) ? $_GET['q'] : $splettername;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.spletterid';
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
			->from('spletter t')
			->leftjoin('employee a','a.employeeid = t.employeeid')
			->leftjoin('splettertype b','b.splettertypeid = t.splettertypeid')
			->leftjoin('plant c','c.plantid = t.plantid')
			->where("coalesce(spletterid,'') like :spletterid 
				and coalesce(spletterno,'') like :spletterno 
				and coalesce(a.fullname,'') like :employeename 
				and coalesce(splettername,'') like :splettername
				and t.recordstatus in (".getUserRecordStatus('listspletter').")",
					array(
					':spletterid'=>'%'.$spletterid.'%',
					':spletterno'=>'%'.$spletterno.'%',
					':employeename'=>'%'.$employeename.'%',
					':splettername'=>'%'.$splettername.'%'
					))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.fullname,b.splettername,c.plantcode')			
			->from('spletter t')
			->leftjoin('employee a','a.employeeid = t.employeeid')
			->leftjoin('splettertype b','b.splettertypeid = t.splettertypeid')
			->leftjoin('plant c','c.plantid = t.plantid')
			->where("coalesce(spletterid,'') like :spletterid 
				and coalesce(spletterno,'') like :spletterno 
				and coalesce(a.fullname,'') like :employeename 
				and coalesce(splettername,'') like :splettername
				and t.recordstatus in (".getUserRecordStatus('listspletter').")",
					array(
					':spletterid'=>'%'.$spletterid.'%',
					':spletterno'=>'%'.$spletterno.'%',
					':employeename'=>'%'.$employeename.'%',
					':splettername'=>'%'.$splettername.'%'
					))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
			'spletterid'=>$data['spletterid'],
			'plantid'=>$data['plantid'],
			'plantcode'=>$data['plantcode'],
			'spletterno'=>$data['spletterno'],
			'employeeid'=>$data['employeeid'],
			'description'=>$data['description'],
			'fullname'=>$data['fullname'],
			'splettertypeid'=>$data['splettertypeid'],
			'splettername'=>$data['splettername'],
			'spletterdate'=>date(Yii::app()->params['dateviewfromdb'], strtotime($data['spletterdate'])),
			'recordstatus'=>$data['statusname'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {	
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertspletter(:vspletterdate,:vplantid,:vsplettertypeid,:vemployeeid,:vdescription,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatespletter(:vid,:vspletterdate,:vplantid,:vsplettertypeid,:vemployeeid,:vdescription,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vspletterdate',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vplantid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vsplettertypeid',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vemployeeid',$arraydata[4],PDO::PARAM_STR);
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
			$this->ModifyData($connection,array((isset($_POST['spletterid'])?$_POST['spletterid']:''),
				date(Yii::app()->params['datetodb'], strtotime($_POST['spletterdate'])),
				$_POST['plantid'],
				$_POST['splettertypeid'],
				$_POST['employeeid'],
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
	 public function actionApprove()
  {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Approvespletter(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
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
        $sql     = 'call Deletespletter(:vid,:vcreatedby)';
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
				$sql = 'call Purgespletter(:vid,:vcreatedby)';
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
				$sql = 'call Purgespletterdetail(:vid,:vcreatedby)';
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
	  $sql = "select spletterno,splettername,spletterdate,useraccess
						from spletter a ";						
		$spletterid = filter_input(INPUT_GET,'spletterid');
		$spletterno = filter_input(INPUT_GET,'spletterno');
		$splettername = filter_input(INPUT_GET,'splettername');
		$spletterdate = filter_input(INPUT_GET,'spletterdate');
		$useraccess = filter_input(INPUT_GET,'useraccess');
		$sql .= " and coalesce(a.spletterid,'') like '%".$spletterid."%' 
			and coalesce(a.spletterno,'') like '%".$spletterno."%'
			and coalesce(a.splettername,'') like '%".$splettername."%'
			and coalesce(a.spletterdate,'') like '%".$spletterdate."%'
			and coalesce(a.useraccess,'') like '%".$useraccess."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.spletterid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by spletterid asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('supplier');
		$this->pdf->AddPage('P',array(400,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('spletterno'),
																	GetCatalog('splettername'),
																	GetCatalog('spletterdate'),
																	GetCatalog('useraccess'),
																	GetCatalog('bankname'));
		$this->pdf->setwidths(array(15,90,40,55,40,40,80,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L');		
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['spletterid'],$row1['spletterno'],$row1['taxno'],$row1['bankaccountno'],$row1['bankname'],$row1['accountowner'],$row1['recordstatus']));
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
