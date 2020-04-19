<?php
class EmployeebenefitController extends Controller {
	public $menuname = 'employeebenefit';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexdetail() {
		if(isset($_GET['grid']))
			echo $this->actionsearchdetail();
		else
			$this->renderPartial('index',array());
	}
	public function actionGetData() {
		if (isset($_GET['id']))
		{
		}
		else
		{
			$model = new Employeebenefit;
			if ($model->save())
			{
				echo CJSON::encode(array(
					'employeebenefitid'=>$model->employeebenefitid,
				));
			}
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
				$sql = 'call Insertemployeebenefit(:vemployeeid,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call Updateemployeebenefit(:vid,:vemployeeid,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['employeebenefitid'],PDO::PARAM_STR);
			}
			$command->bindvalue(':vemployeeid',$_POST['employeeid'],PDO::PARAM_STR);
			$status = isset($_POST['recordstatus'])?($_POST['recordstatus']=="on")?1:0:0;
			$command->bindvalue(':vrecordstatus',$status,PDO::PARAM_STR);
			$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
			$command->execute();
			$transaction->commit();
			$this->DeleteLock($this->menuname, $_POST['employeebenefitid']);
			getmessage(true,getcatalog('insertsuccess'),1);
		}
		catch (Exception $e) {
			$transaction->rollBack();
			getmessage(true,$e->getMessage());
		}
	}
	public function actionSavedetail() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
			if (isset($_POST['isNewRecord']))
			{
				$sql = 'call Insertemployeebenefitdetail(:vemployeebenefitid,:vwagetypeid,:vstartdate,:venddate,:vamount,:vcurrencyid,:vratevalue,:visfinal,:vreason,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call Updateemployeebenefitdetail(:vid,:vemployeebenefitid,:vwagetypeid,:vstartdate,:venddate,:vamount,:vcurrencyid,:vratevalue,:visfinal,:vreason,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['employeebenefitdetailid'],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $_POST['employeebenefitdetailid']);
			}
			$command->bindvalue(':vemployeebenefitid',$_POST['employeebenefitid'],PDO::PARAM_STR);
			$command->bindvalue(':vwagetypeid',$_POST['wagetypeid'],PDO::PARAM_STR);
			$command->bindvalue(':vstartdate',date(Yii::app()->params['datetodb'], strtotime($_POST['startdate'])),PDO::PARAM_STR);
			$command->bindvalue(':venddate',date(Yii::app()->params['datetodb'], strtotime($_POST['enddate'])),PDO::PARAM_STR);
			$command->bindvalue(':vamount',$_POST['amount'],PDO::PARAM_STR);
			$command->bindvalue(':vcurrencyid',$_POST['currencyid'],PDO::PARAM_STR);
			$command->bindvalue(':vratevalue',$_POST['ratevalue'],PDO::PARAM_STR);
			$command->bindvalue(':visfinal',$_POST['isfinal'],PDO::PARAM_STR);
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
	public function actionPurge() {
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeemployeebenefit(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				foreach($id as $ids) {
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
	public function search() {
		header('Content-Type: application/json');
		$employeebenefitid = isset ($_POST['employeebenefitid']) ? $_POST['employeebenefitid'] : '';
		$employeename = isset ($_POST['employeename']) ? $_POST['employeename'] : '';
		$employeebenefitid = isset ($_GET['q']) ? $_GET['q'] : $employeebenefitid;
		$employeename = isset ($_GET['q']) ? $_GET['q'] : $employeename;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.employeebenefitid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('employeebenefit t')
				->join('employee p','p.employeeid=t.employeeid')
				->where('(p.fullname like :employeename)',
					array(':employeename'=>'%'.$employeename.'%'))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('employeebenefit t')
				->join('employee p','p.employeeid=t.employeeid')
				->where('((p.fullname like :employeename)) and t.recordstatus=1',
					array(':employeename'=>'%'.$employeename.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('employeebenefit t')
				->join('employee p','p.employeeid=t.employeeid')
				->where('(p.fullname like :employeename)',
					array(':employeename'=>'%'.$employeename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('employeebenefit t')
				->join('employee p','p.employeeid=t.employeeid')
				->where('((p.fullname like :employeename)) and t.recordstatus=1',
					array(':employeename'=>'%'.$employeename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'employeebenefitid'=>$data['employeebenefitid'],
				'employeeid'=>$data['employeeid'],
				'fullname'=>$data['fullname'],
				'recordstatusbenefit'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionsearchdetail() {
		header('Content-Type: application/json');
		$id = 0;	
		if (isset($_POST['id']))
		{
			$id = $_POST['id'];
		}
		else
		if (isset($_GET['id']))
		{
			$id = $_GET['id'];
		}
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.employeebenefitdetailid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
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
				->from('employeebenefitdetail t')
				->leftjoin('wagetype a','a.wagetypeid = t.wagetypeid')
				->leftjoin('currency b','b.currencyid = t.currencyid')
				->where('employeebenefitid = :employeebenefitid',
				array(':employeebenefitid'=>$id))
				->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
				->select()
				->from('employeebenefitdetail t')
				->leftjoin('wagetype a','a.wagetypeid = t.wagetypeid')
				->leftjoin('currency b','b.currencyid = t.currencyid')
				->where('employeebenefitid = :employeebenefitid',
				array(':employeebenefitid'=>$id))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
			'employeebenefitdetailid'=>$data['employeebenefitdetailid'],
			'employeebenefitid'=>$data['employeebenefitid'],
			'wagetypeid'=>$data['wagetypeid'],
			'wagename'=>$data['wagename'],
			'startdate'=>date(Yii::app()->params['dateviewfromdb'], strtotime($data['startdate'])),
			'enddate'=>date(Yii::app()->params['dateviewfromdb'], strtotime($data['enddate'])),
			'amount'=>Yii::app()->format->formatNumber($data['amount']),
			'currencyid'=>$data['currencyid'],
			'currencyname'=>$data['currencyname'],
			'ratevalue'=>Yii::app()->format->formatNumber($data['ratevalue']),
			'isfinal'=>$data['isfinal'],
			'reason'=>$data['reason'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));;
		echo CJSON::encode($result);
	}
	public function actionDownPDF() {
		parent::actionDownload();
		$sql = "select a.employeebenefitid,b.fullname,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from employeebenefit a
						join employee b on b.employeeid = a.employeeid ";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " where a.employeebenefitid in (".$_GET['id'].")";
		}	    
		$command=$this->connection->createCommand($sql);
    $dataReader=$command->queryAll();
	  $this->pdf->title=getCatalog('employeebenefit');
	  $this->pdf->AddPage('P');
		$this->pdf->SetFont('Arial');
		$this->pdf->AliasNBPages();
		foreach($dataReader as $row) {
			$this->pdf->SetFontSize(8);
      $this->pdf->text(15,$this->pdf->gety()+5,'Karyawan ');$this->pdf->text(50,$this->pdf->gety()+5,': '.$row['fullname']);
			$sql1 = "select a.employeebenefitdetailid,a.employeebenefitid,b.wagename,a.startdate,a.enddate,a.amount,c.currencyname,a.ratevalue,a.isfinal,a.reason
								from employeebenefitdetail a
								join wagetype b on b.wagetypeid = a.wagetypeid
								join currency c on c.currencyid = a.currencyid 
								where a.employeebenefitid = '".$row['employeebenefitid']."'
								order by employeebenefitdetailid ";
			$command1=$this->connection->createCommand($sql1);
      $dataReader1=$command1->queryAll();
			$this->pdf->sety($this->pdf->gety()+15);
      $this->pdf->colalign = array('C','C','C','C','C','C','C','C','C');
      $this->pdf->setwidths(array(10,35,20,20,30,20,20,15,20));
			$this->pdf->colheader = array('No','Jenis Penggajian','Tgl Mulai','Tgl Selesai','Nilai Invoice','Mata Uang','Kurs','Final','Keterangan');
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array('C','L','L','L','L','C','R','C','L');
      $i=0;
      foreach($dataReader1 as $row1) {
				$i=$i+1;
        $this->pdf->row(array($i,$row1['wagename'],
            $row1['startdate'],
            $row1['enddate'],
            $row1['amount'],
						$row1['currencyname'],
						$row1['ratevalue'],
						$row1['isfinal'],
						$row1['reason']));
			}
			$this->pdf->checkNewPage(10);
      $this->pdf->text(25,$this->pdf->gety()+10,'Proposed By');$this->pdf->text(150,$this->pdf->gety()+10,'Approved By');
      $this->pdf->text(25,$this->pdf->gety()+30,'____________ ');$this->pdf->text(150,$this->pdf->gety()+30,'____________');
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		parent::actionDownXls();
		$sql = "select employeeid,recordstatus
				from employeebenefit a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.employeebenefitid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$i=1;
		$this->phpExcel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0,1,getCatalog('employeeid'))
                ->setCellValueByColumnAndRow(1,1,getCatalog('recordstatus'))
                ;		foreach($dataReader as $row1)
		{
			  $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['employeeid'])
                                ->setCellValueByColumnAndRow(1, $i+1, $row1['recordstatus'])
                                ;		$i+=1;
		}
		unset($this->phpExcel);
	}
}