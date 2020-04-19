<?php
class EmployeestatusController extends Controller {
	public $menuname = 'employeestatus';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertemployeestatus(:vemployeestatusname,:vtaxvalue,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updateemployeestatus(:vid,:vemployeestatusname,:vtaxvalue,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vemployeestatusname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vtaxvalue',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[3],PDO::PARAM_STR);
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
			$this->ModifyData($connection,array((isset($_POST['employeestatusid'])?$_POST['employeestatusid']:''),
				$_POST['employeestatusname'],$_POST['taxvalue'],$_POST['recordstatus']));			
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	public function actionPurge() {
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeemployeestatus(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
		}
		else {
			getmessage(true,getcatalog('chooseone'));
		}
	}
	public function search() {
		header('Content-Type: application/json');
		$employeestatusid = isset ($_POST['employeestatusid']) ? $_POST['employeestatusid'] : '';
		$employeestatusname = isset ($_POST['employeestatusname']) ? $_POST['employeestatusname'] : '';
		$taxvalue = isset ($_POST['taxvalue']) ? $_POST['taxvalue'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$employeestatusid = isset ($_GET['q']) ? $_GET['q'] : $employeestatusid;
		$employeestatusname = isset ($_GET['q']) ? $_GET['q'] : $employeestatusname;
		$taxvalue = isset ($_GET['q']) ? $_GET['q'] : $taxvalue;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.employeestatusid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
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
				->from('employeestatus t')
				->where('(employeestatusname like :employeestatusname) and 
						(taxvalue like :taxvalue)',
						array(':employeestatusname'=>'%'.$employeestatusname.'%',
							':taxvalue'=>'%'.$taxvalue.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('employeestatus t')
				->where('((employeestatusname like :employeestatusname) or 
            (taxvalue like :taxvalue)) and t.recordstatus=1',
						array(':employeestatusname'=>'%'.$employeestatusname.'%',
						':taxvalue'=>'%'.$taxvalue.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('employeestatus t')
				->where('(employeestatusname like :employeestatusname) and 
						(taxvalue like :taxvalue)',
						array(':employeestatusname'=>'%'.$employeestatusname.'%',
             ':taxvalue'=>'%'.$taxvalue.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('employeestatus t')
				->where('((employeestatusname like :employeestatusname) or 
						(taxvalue like :taxvalue)) and t.recordstatus=1',
						array(':employeestatusname'=>'%'.$employeestatusname.'%',
						':taxvalue'=>'%'.$taxvalue.'%'))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'employeestatusid'=>$data['employeestatusid'],
				'employeestatusname'=>$data['employeestatusname'],
				'taxvalue'=>Yii::app()->format->formatNumber($data['taxvalue']),
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select employeestatusid,employeestatusname,taxvalue,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from employeestatus a ";
		$employeestatusid = filter_input(INPUT_GET,'employeestatusid');
		$employeestatusname = filter_input(INPUT_GET,'employeestatusname');
		$sql .= " where coalesce(a.employeestatusid,'') like '%".$employeestatusid."%' 
			and coalesce(a.employeestatusname,'') like '%".$employeestatusname."%'";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.employeestatusid in (".$_GET['id'].")";
		}
		$sql = $sql . "order by employeestatusname asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('employeestatus');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L');
		$this->pdf->colheader = array(getCatalog('employeestatusid'),
																	getCatalog('employeestatusname'),
																	getCatalog('taxvalue'),
																	getCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,60,100,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['employeestatusid'],$row1['employeestatusname'],
				Yii::app()->format->formatCurrency($row1['taxvalue']),$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='employeestatus';
		parent::actionDownxls();
		$sql = "select employeestatusid,employeestatusname,taxvalue,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from employeestatus a ";
		$employeestatusid = filter_input(INPUT_GET,'employeestatusid');
		$employeestatusname = filter_input(INPUT_GET,'employeestatusname');
		$sql .= " where coalesce(a.employeestatusid,'') like '%".$employeestatusid."%' 
			and coalesce(a.employeestatusname,'') like '%".$employeestatusname."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.employeestatusid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by employeestatusname asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['employeestatusid'])
				->setCellValueByColumnAndRow(1,$i,$row1['employeestatusname'])							
				->setCellValueByColumnAndRow(2,$i,$row1['taxvalue'])
				->setCellValueByColumnAndRow(3,$i,$row1['recordstatus']);
			$i++;
		}
		
		$this->getFooterXLS($this->phpExcel);
	}
}