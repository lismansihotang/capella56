<?php
class EmergencytypeController extends Controller {
	public $menuname = 'emergencytype';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertemergencytype(:vemergencyname,:vreporttype,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updateemergencytype(:vid,:vemergencyname,:vreporttype,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vemergencyname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vreporttype',$arraydata[2],PDO::PARAM_STR);
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
			$this->ModifyData($connection,array((isset($_POST['emergencytypeid'])?$_POST['emergencytypeid']:''),$_POST['emergencyname'],$_POST['reporttype'],
			$_POST['recordstatus']));			
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
				$sql = 'call Purgeemergencytype(:vid,:vcreatedby)';
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
		$emergencytypeid = isset ($_POST['emergencytypeid']) ? $_POST['emergencytypeid'] : '';
		$emergencyname = isset ($_POST['emergencyname']) ? $_POST['emergencyname'] : '';
		$reporttype = isset ($_POST['reporttype']) ? $_POST['reporttype'] : '';
		
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$emergencytypeid = isset ($_GET['q']) ? $_GET['q'] : $emergencytypeid;
		$emergencyname = isset ($_GET['q']) ? $_GET['q'] : $emergencyname;
		$reporttype = isset ($_GET['q']) ? $_GET['q'] : $reporttype;
		
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.emergencytypeid';
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
				->from('emergencytype t')
				->where('(emergencyname like :emergencyname) and
						(reporttype like :reporttype)
						',
						array(':emergencyname'=>'%'.$emergencyname.'%',
						':reporttype'=>'%'.$reporttype.'%',
						))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('emergencytype t')
				->where('((emergencyname like :emergencyname) or
						(reporttype like :reporttype)
						) and t.recordstatus=1',
						array(':emergencyname'=>'%'.$emergencyname.'%',
						':reporttype'=>'%'.$reporttype.'%',
						))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('emergencytype t')
				->where('(emergencyname like :emergencyname) and
						(reporttype like :reporttype)
						',
						array(':emergencyname'=>'%'.$emergencyname.'%',
						':reporttype'=>'%'.$reporttype.'%',
						))
				->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('emergencytype t')
				->where('((emergencyname like :emergencyname) or
						(reporttype like :reporttype)
						) and t.recordstatus=1',
						array(':emergencyname'=>'%'.$emergencyname.'%',
						':reporttype'=>'%'.$reporttype.'%',
						))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'emergencytypeid'=>$data['emergencytypeid'],
				'emergencyname'=>$data['emergencyname'],
				'reporttype'=>$data['reporttype'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select emergencytypeid,emergencyname,reporttype,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from emergencytype a ";
		$emergencytypeid = filter_input(INPUT_GET,'emergencytypeid');
		$emergencyname = filter_input(INPUT_GET,'emergencyname');
		$reporttype = filter_input(INPUT_GET,'reporttype');
		$validperiod = filter_input(INPUT_GET,'validperiod');
		$sql .= " where coalesce(a.emergencytypeid,'') like '%".$emergencytypeid."%' 
			and coalesce(a.emergencyname,'') like '%".$emergencyname."%'
			and coalesce(a.reporttype,'') like '%".$reporttype."%'
			
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.emergencytypeid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by emergencyname asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('emergencytype');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L','L');
		$this->pdf->colheader = array(getCatalog('emergencytypeid'),
																	getCatalog('emergencyname'),
																	getCatalog('reporttype'),
																
																	getCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,63,63,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['emergencytypeid'],$row1['emergencyname'],$row1['reporttype'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='emergencytype';
		parent::actionDownxls();
		$sql = "select emergencytypeid,emergencyname,reporttype,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from emergencytype a ";
		$emergencytypeid = filter_input(INPUT_GET,'emergencytypeid');
		$emergencyname = filter_input(INPUT_GET,'emergencyname');
		$reporttype = filter_input(INPUT_GET,'reporttype');
		
		$sql .= " where coalesce(a.emergencytypeid,'') like '%".$emergencytypeid."%' 
			and coalesce(a.emergencyname,'') like '%".$emergencyname."%'
			and coalesce(a.reporttype,'') like '%".$reporttype."%'
		
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.emergencytypeid in (".$_GET['id'].")";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['emergencytypeid'])
				->setCellValueByColumnAndRow(1,$i,$row1['emergencyname'])				
				->setCellValueByColumnAndRow(2,$i,$row1['reporttype'])
				
				->setCellValueByColumnAndRow(3,$i,$row1['recordstatus']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}