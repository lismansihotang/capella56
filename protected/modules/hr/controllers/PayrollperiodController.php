<?php
class PayrollperiodController extends Controller {
	public $menuname = 'payrollperiod';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
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
				$sql = 'call Insertpayrollperiod(:vpayrollperiodname,:vstartdate,:venddate,:vparentperiodid,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call Updatepayrollperiod(:vid,:vpayrollperiodname,:vstartdate,:venddate,:vparentperiodid,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['payrollperiodid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['payrollperiodid']);
			}
			$command->bindvalue(':vpayrollperiodname',$_POST['payrollperiodname'],PDO::PARAM_STR);
			$command->bindvalue(':vstartdate',date(Yii::app()->params['datetodb'], strtotime($_POST['startdate'])),PDO::PARAM_STR);
			$command->bindvalue(':venddate',date(Yii::app()->params['datetodb'], strtotime($_POST['enddate'])),PDO::PARAM_STR);
			$command->bindvalue(':vparentperiodid',$_POST['parentperiodid'],PDO::PARAM_STR);
			$command->bindvalue(':vrecordstatus',$_POST['recordstatus'],PDO::PARAM_STR);
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
			try {
				$sql = 'call Purgepayrollperiod(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				getmessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollback();
				getmessage(true,$e->getMessage());
			}
		}
		else {
			getmessage(true,getcatalog('chooseone'));
		}
	}
	public function search() {
		header('Content-Type: application/json');
		$payrollperiodid = isset ($_POST['payrollperiodid']) ? $_POST['payrollperiodid'] : '';
		$payrollperiodname = isset ($_POST['payrollperiodname']) ? $_POST['payrollperiodname'] : '';
		$payrollperiodid = isset ($_GET['q']) ? $_GET['q'] : $payrollperiodid;
		$payrollperiodname = isset ($_GET['q']) ? $_GET['q'] : $payrollperiodname;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'payrollperiodid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort,'t.') > 0)?$sort:'t.'.$sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('payrollperiod t')
				->leftjoin('payrollperiod p','p.payrollperiodid=t.parentperiodid')
				->where('(t.payrollperiodname like :payrollperiodname)',
					array(':payrollperiodname'=>'%'.$payrollperiodname.'%'))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('payrollperiod t')
				->leftjoin('payrollperiod p','p.payrollperiodid=t.parentperiodid')
				->where('((t.payrollperiodname like :payrollperiodname)) and t.recordstatus=1',
					array(':payrollperiodname'=>'%'.$payrollperiodname.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.payrollperiodid,t.payrollperiodname,t.startdate,t.enddate,t.parentperiodid,p.payrollperiodname as periodparent,t.recordstatus')	
				->from('payrollperiod t')
				->leftjoin('payrollperiod p','p.payrollperiodid=t.parentperiodid')
				->where('(t.payrollperiodname like :payrollperiodname)',
					array(':payrollperiodname'=>'%'.$payrollperiodname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('t.payrollperiodid,t.payrollperiodname,t.startdate,t.enddate,t.parentperiodid,p.payrollperiodname as periodparent,t.recordstatus')	
				->from('payrollperiod t')
				->leftjoin('payrollperiod p','p.payrollperiodid=t.parentperiodid')
				->where('((t.payrollperiodname like :payrollperiodname)) and t.recordstatus=1',
					array(':payrollperiodname'=>'%'.$payrollperiodname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'payrollperiodid'=>$data['payrollperiodid'],
				'payrollperiodname'=>$data['payrollperiodname'],
				'startdate'=>($data['startdate']!==null)?date(Yii::app()->params['dateviewfromdb'], strtotime($data['startdate'])):"",
				'enddate'=>($data['enddate']!==null)?date(Yii::app()->params['dateviewfromdb'], strtotime($data['enddate'])):"",
				'parentperiodid'=>$data['parentperiodid'],
				'parentname'=>$data['periodparent'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}	
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select payrollperiodid,payrollperiodname,startdate,enddate,
						ifnull((select z.payrollperiodname from payrollperiod z where z.payrollperiodid = a.parentperiodid),'-') as parent,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from payrollperiod a ";
		$payrollperiodid = filter_input(INPUT_GET,'payrollperiodid');
		$payrollperiodname = filter_input(INPUT_GET,'payrollperiodname');
		$sql .= " where coalesce(a.payrollperiodid,'') like '%".$payrollperiodid."%' 
			and coalesce(a.payrollperiodname,'') like '%".$payrollperiodname."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.payrollperiodid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=getCatalog('payrollperiod');
		$this->pdf->AddPage('P');
		//masukkan posisi judul
		$this->pdf->colalign = array('L','L','L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(getCatalog('payrollperiodid'),
																	getCatalog('payrollperiodname'),
																	getCatalog('startdate'),
																	getCatalog('enddate'),
																	getCatalog('parent'),
																	getCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,45,35,35,45,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['payrollperiodid'],$row1['payrollperiodname'],$row1['startdate'],$row1['enddate'],$row1['parent'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='payrollperiod';
		parent::actionDownxls();
		$sql = "select payrollperiodid,payrollperiodname,startdate,enddate,
						ifnull((select z.payrollperiodname from payrollperiod z where z.payrollperiodid = a.parentperiodid),'-') as parent,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from payrollperiod a    ";
		$payrollperiodid = filter_input(INPUT_GET,'payrollperiodid');
		$payrollperiodname = filter_input(INPUT_GET,'payrollperiodname');
		$sql .= " where coalesce(a.payrollperiodid,'') like '%".$payrollperiodid."%' 
			and coalesce(a.payrollperiodname,'') like '%".$payrollperiodname."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.payrollperiodid in (".$_GET['id'].")";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['payrollperiodid'])
				->setCellValueByColumnAndRow(1,$i,$row1['payrollperiodname'])			
				->setCellValueByColumnAndRow(2,$i,$row1['startdate'])
				->setCellValueByColumnAndRow(3,$i,$row1['enddate'])
				->setCellValueByColumnAndRow(4,$i,$row1['parent'])
				->setCellValueByColumnAndRow(5,$i,$row1['recordstatus']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}