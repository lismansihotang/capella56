<?php
class AbsruleController extends Controller {
	public $menuname = 'absrule';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertabsrule(:vabsscheduleid,:vdifftimein,:vdifftimeout,:vabsstatusid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateabsrule(:vid,:vabsscheduleid,:vdifftimein,:vdifftimeout,:vabsstatusid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vabsscheduleid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vdifftimein',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdifftimeout',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vabsstatusid',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
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
			$this->ModifyData($connection,array((isset($_POST['absruleid'])?$_POST['absruleid']:''),$_POST['absscheduleid'],$_POST['difftimein'],
				$_POST['difftimeout'],$_POST['absstatusid'],$_POST['recordstatus']));
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
				$sql = 'call Purgeabsrule(:vid,:vcreatedby)';
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
		$absruleid = isset ($_POST['absruleid']) ? $_POST['absruleid'] : '';
		$absschedulename = isset ($_POST['absschedulename']) ? $_POST['absschedulename'] : '';
		$absstatusname = isset ($_POST['absstatusname']) ? $_POST['absstatusname'] : '';
		$absruleid = isset ($_GET['q']) ? $_GET['q'] : $absruleid;
		$absschedulename = isset ($_GET['q']) ? $_GET['q'] : $absschedulename;
		$absstatusname = isset ($_GET['q']) ? $_GET['q'] : $absstatusname;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.absruleid';
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
				->from('absrule t')
				->join('absschedule p','p.absscheduleid=t.absscheduleid')
				->join('absstatus q','q.absstatusid=t.absstatusid')
				->where('(p.absschedulename like :absschedulename) and 
						(q.longstat like :absstatusname)',
							array(':absschedulename'=>'%'.$absschedulename.'%',
								':absstatusname'=>'%'.$absstatusname.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('absrule t')
				->join('absschedule p','p.absscheduleid=t.absscheduleid')
				->join('absstatus q','q.absstatusid=t.absstatusid')
				->where('((p.absschedulename like :absschedulename) or 
					(q.longstat like :absstatusname)) and t.recordstatus=1',
						array(':absschedulename'=>'%'.$absschedulename.'%',
								':absstatusname'=>'%'.$absstatusname.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('absrule t')
				->join('absschedule p','p.absscheduleid=t.absscheduleid')
				->join('absstatus q','q.absstatusid=t.absstatusid')
				->where('(p.absschedulename like :absschedulename) and 
					(q.longstat like :absstatusname)',
						array(':absschedulename'=>'%'.$absschedulename.'%',
							':absstatusname'=>'%'.$absstatusname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('absrule t')
				->join('absschedule p','p.absscheduleid=t.absscheduleid')
				->join('absstatus q','q.absstatusid=t.absstatusid')
				->where('((p.absschedulename like :absschedulename) or 
					(q.longstat like :absstatusname)) and t.recordstatus=1',
						array(':absschedulename'=>'%'.$absschedulename.'%',
							':absstatusname'=>'%'.$absstatusname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'absruleid'=>$data['absruleid'],
				'absscheduleid'=>$data['absscheduleid'],
				'absschedulename'=>$data['absschedulename'],
				'difftimein'=>$data['difftimein'],
				'difftimeout'=>$data['difftimeout'],
				'absstatusid'=>$data['absstatusid'],
				'longstat'=>$data['longstat'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select a.absruleid,c.absschedulename as absschedule,a.difftimein,a.difftimeout,b.longstat as absstatus,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from absrule a
						left join absstatus b on b.absstatusid = a.absstatusid
						left join absschedule c on c.absscheduleid = a.absscheduleid ";
		$absruleid = filter_input(INPUT_GET,'absruleid');
		$absschedulename = filter_input(INPUT_GET,'absschedulename');
		$absstatusname = filter_input(INPUT_GET,'absstatusname');
		$sql .= " where coalesce(a.absruleid,'') like '%".$absruleid."%' 
			and coalesce(c.absschedulename,'') like '%".$absschedulename."%'
			and coalesce(b.absstatusname,'') like '%".$absstatusname."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.absruleid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by absschedulename asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('absrule');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L','L','L');
		$this->pdf->colheader = array(getCatalog('absruleid'),
			getCatalog('absschedule'),
			getCatalog('difftimein'),
			getCatalog('difftimeout'),
			getCatalog('absstatus'),
			getCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,40,40,40,40,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['absruleid'],$row1['absschedule'],$row1['difftimein'],$row1['difftimeout'],$row1['absstatus'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='absschedule';
		parent::actionDownxls();
		$sql = "select a.absruleid,c.absschedulename as absschedule,a.difftimein,a.difftimeout,b.longstat as absstatus,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from absrule a
						left join absstatus b on b.absstatusid = a.absstatusid
						left join absschedule c on c.absscheduleid = a.absscheduleid ";
		$absruleid = filter_input(INPUT_GET,'absruleid');
		$absschedulename = filter_input(INPUT_GET,'absschedulename');
		$absstatusname = filter_input(INPUT_GET,'absstatusname');
		$sql .= " where coalesce(a.absruleid,'') like '%".$absruleid."%' 
			and coalesce(c.absschedulename,'') like '%".$absschedulename."%'
			and coalesce(b.absstatusname,'') like '%".$absstatusname."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.absruleid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by absschedulename asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['absruleid'])
				->setCellValueByColumnAndRow(1,$i,$row1['absschedule'])				
				->setCellValueByColumnAndRow(2,$i,$row1['difftimein'])
				->setCellValueByColumnAndRow(3,$i,$row1['difftimeout'])
				->setCellValueByColumnAndRow(4,$i,$row1['absstatus'])				
				->setCellValueByColumnAndRow(5,$i,$row1['recordstatus']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}