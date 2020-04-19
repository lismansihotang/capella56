<?php
class JobsController extends Controller {
	public $menuname = 'jobs';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$jobsid = isset ($_POST['jobsid']) ? $_POST['jobsid'] : '';
		$structurename = isset ($_POST['structurename']) ? $_POST['structurename'] : '';
		$jobdesc = isset ($_POST['jobdesc']) ? $_POST['jobdesc'] : '';
		$qualification = isset ($_POST['qualification']) ? $_POST['qualification'] : '';
		$positionname = isset ($_POST['positionname']) ? $_POST['positionname'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$jobsid = isset ($_GET['q']) ? $_GET['q'] : $jobsid;
		$structurename = isset ($_GET['q']) ? $_GET['q'] : $structurename;
		$jobdesc = isset ($_GET['q']) ? $_GET['q'] : $jobdesc;
		$qualification = isset ($_GET['q']) ? $_GET['q'] : $qualification;
		$positionname = isset ($_GET['q']) ? $_GET['q'] : $positionname;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.jobsid';
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
				->selectdistinct('count(1) as total')	
				->from('jobs t')
				->leftjoin('orgstructure p','p.orgstructureid=t.orgstructureid')
				->leftjoin('position q','q.positionid=t.positionid')
				->where("coalesce(p.structurename,'') like :structurename and 
						coalesce(jobdesc,'') like :jobdesc and 
						coalesce(qualification,'') like :qualification and 
						coalesce(q.positionname,'') like :positionname",
						array(':structurename'=>'%'.$structurename.'%',
					':jobdesc'=>'%'.$jobdesc.'%',
					':qualification'=>'%'.$qualification.'%',
					':positionname'=>'%'.$positionname.'%'
					))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->selectdistinct('count(1) as total')	
				->from('jobs t')
				->join('orgstructure p','p.orgstructureid=t.orgstructureid')
				->join('position q','q.positionid=t.positionid')
				->where('((p.structurename like :structurename) or 
						(jobdesc like :jobdesc) or 
						(qualification like :qualification) or 
						(q.positionname like :positionname)) and t.recordstatus=1',
						array(':structurename'=>'%'.$structurename.'%',
						':jobdesc'=>'%'.$jobdesc.'%',
						':qualification'=>'%'.$qualification.'%',
						':positionname'=>'%'.$positionname.'%'
						))
				->queryScalar();
		}
		$result['total'] = $cmd;		
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,p.structurename,q.positionname')	
				->from('jobs t')
				->leftjoin('orgstructure p','p.orgstructureid=t.orgstructureid')
				->leftjoin('position q','q.positionid=t.positionid')
				->where("(coalesce(p.structurename,'') like :structurename) and 
						(coalesce(jobdesc,'') like :jobdesc) and 
						(coalesce(qualification,'') like :qualification) and 
						(coalesce(q.positionname,'') like :positionname)",
						array(':structurename'=>'%'.$structurename.'%',
						':jobdesc'=>'%'.$jobdesc.'%',
						':qualification'=>'%'.$qualification.'%',
						':positionname'=>'%'.$positionname.'%'
						))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,p.structurename,q.positionname')	
				->from('jobs t')
				->join('orgstructure p','p.orgstructureid=t.orgstructureid')
				->join('position q','q.positionid=t.positionid')
				->where('((p.structurename like :orgstructureid) or 
						(jobdesc like :jobdesc) or 
						(qualification like :qualification) or 
						(q.positionname like :positionname)) and t.recordstatus=1',
						array(':orgstructureid'=>'%'.$orgstructureid.'%',
						':jobdesc'=>'%'.$jobdesc.'%',
						':qualification'=>'%'.$qualification.'%',
						':positionname'=>'%'.$positionname.'%'
						))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'jobsid'=>$data['jobsid'],
				'orgstructureid'=>$data['orgstructureid'],
				'structurename'=>$data['structurename'],
				'jobdesc'=>$data['jobdesc'],
				'qualification'=>$data['qualification'],
				'positionid'=>$data['positionid'],
				'positionname'=>$data['positionname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionSave() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
			if (isset($_POST['isNewRecord'])) {
				$sql = 'call Insertjobs(:vorgstructureid,:vjobdesc,:vqualification,:vpositionid,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else {
				$sql = 'call Updatejobs(:vid,:vorgstructureid,:vjobdesc,:vqualification,:vpositionid,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['jobsid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['jobsid']);
			}
			$command->bindvalue(':vorgstructureid',$_POST['orgstructureid'],PDO::PARAM_STR);
			$command->bindvalue(':vjobdesc',$_POST['jobdesc'],PDO::PARAM_STR);
			$command->bindvalue(':vqualification',$_POST['qualification'],PDO::PARAM_STR);
			$command->bindvalue(':vpositionid',$_POST['positionid'],PDO::PARAM_STR);
			$command->bindvalue(':vrecordstatus',$_POST['recordstatus'],PDO::PARAM_STR);
			$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
			$command->execute();
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
				$sql = 'call Purgejobs(:vid,:vcreatedby)';
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
			GetMessage(true,getcatalog('chooseone'));
		}
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select structurename,jobdesc,qualification,positionname,a.recordstatus
				from jobs a 
				left join orgstructure b on b.orgstructureid = a.orgstructureid 
				left join position c on c.positionid = a.positionid ";
		$structurename = filter_input(INPUT_GET,'structurename');
		$jobdesc = filter_input(INPUT_GET,'jobdesc');
		$qualification = filter_input(INPUT_GET,'qualification');
		$positionname = filter_input(INPUT_GET,'positionname');
		$sql .= " where coalesce(b.structurename,'') like '%".$structurename."%' 
			and coalesce(a.jobdesc,'') like '%".$jobdesc."%'
			and coalesce(a.qualification,'') like '%".$qualification."%'
			and coalesce(c.positionname,'') like '%".$positionname."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.jobsid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('jobs');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L','L');
		$this->pdf->colheader = array(getCatalog('orgstructure'),
                getCatalog('jobdesc'),
                getCatalog('qualification'),
                getCatalog('position'),
                getCatalog('recordstatus'));
		$this->pdf->setwidths(array(40,40,40,40,40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['structurename'],$row1['jobdesc'],$row1['qualification'],$row1['positionname'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		parent::actionDownxls();
		$sql = "select structurename,jobdesc,qualification,positionname,a.recordstatus
				from jobs a 
				left join orgstructure b on b.orgstructureid = a.orgstructureid 
				left join position c on c.positionid = a.positionid ";
		$structurename = filter_input(INPUT_GET,'structurename');
		$jobdesc = filter_input(INPUT_GET,'jobdesc');
		$qualification = filter_input(INPUT_GET,'qualification');
		$positionname = filter_input(INPUT_GET,'positionname');
		$sql .= " where coalesce(b.structurename,'') like '%".$structurename."%' 
			and coalesce(a.jobdesc,'') like '%".$jobdesc."%'
			and coalesce(a.qualification,'') like '%".$qualification."%'
			and coalesce(c.positionname,'') like '%".$positionname."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.jobsid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$i=1;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,1,getCatalog('orgstructure'))
			->setCellValueByColumnAndRow(1,1,getCatalog('jobdesc'))
			->setCellValueByColumnAndRow(2,1,getCatalog('qualification'))
			->setCellValueByColumnAndRow(3,1,getCatalog('position'))
			->setCellValueByColumnAndRow(4,1,getCatalog('recordstatus'));		
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['structurename'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['jobdesc'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['qualification'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['positionname'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['recordstatus']);		
				$i+=1;
		}
		$this->getFooterxls($this->phpExcel);
	}
}