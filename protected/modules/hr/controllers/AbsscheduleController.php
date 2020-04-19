<?php
class AbsscheduleController extends Controller {
	public $menuname = 'absschedule';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	private function ModifyData($connection,$arraydata){
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertabsschedule(:vabsschedulename,:vabsin,:vabsout,:vabsstatusid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updateabsschedule(:vid,:vabsschedulename,:vabsin,:vabsout,:vabsstatusid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vabsschedulename',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vabsin',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vabsout',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vabsstatusid',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-absschedule"]["name"]);
		if (move_uploaded_file($_FILES["file-absschedule"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$absschedulename = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$absin = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$absout = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$shortstat = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$absstatusid = Yii::app()->db->createCommand("select absstatusid from absstatus where shortstat = '".$shortstat."'")->queryScalar();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$this->ModifyData($connection,array($id,$absschedulename,$absin,$absout,$absstatusid,$recordstatus));
				}
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollBack();
				GetMessage(true,$e->getMessage());
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
			$this->ModifyData($connection,array((isset($_POST['absscheduleid'])?$_POST['absscheduleid']:''),$_POST['absschedulename'],
				$_POST['absin'],$_POST['absout'],$_POST['absstatusid'],$_POST['recordstatus']));
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
				$sql = 'call Purgeabsschedule(:vid,:vcreatedby)';
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
		$absscheduleid = isset ($_POST['absscheduleid']) ? $_POST['absscheduleid'] : '';
		$absschedulename = isset ($_POST['absschedulename']) ? $_POST['absschedulename'] : '';
		$absstatus = isset ($_POST['absstatus']) ? $_POST['absstatus'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$absscheduleid = isset ($_GET['q']) ? $_GET['q'] : $absscheduleid;
		$absschedulename = isset ($_GET['q']) ? $_GET['q'] : $absschedulename;
		$absstatus = isset ($_GET['q']) ? $_GET['q'] : $absstatus;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.absscheduleid';
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
				->from('absschedule t')
				->join('absstatus p','p.absstatusid=t.absstatusid')
				->where('(absschedulename like :absschedulename) and
					(p.shortstat like :absstatus)',
					array(':absschedulename'=>'%'.$absschedulename.'%',
						':absstatus'=>'%'.$absstatus.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('absschedule t')
				->join('absstatus p','p.absstatusid=t.absstatusid')
				->where('((absschedulename like :absschedulename) or
					(p.shortstat like :absstatus)) and t.recordstatus=1',
					array(':absschedulename'=>'%'.$absschedulename.'%',
						':absstatus'=>'%'.$absstatus.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('absschedule t')
				->join('absstatus p','p.absstatusid=t.absstatusid')
				->where('(absschedulename like :absschedulename) and
					(p.shortstat like :absstatus)',
						array(':absschedulename'=>'%'.$absschedulename.'%',
						':absstatus'=>'%'.$absstatus.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('absschedule t')
				->join('absstatus p','p.absstatusid=t.absstatusid')
				->where('((absschedulename like :absschedulename) or
					(p.shortstat like :absstatusid)) and t.recordstatus=1',
					array(':absschedulename'=>'%'.$absschedulename.'%',
					':absstatusid'=>'%'.$absstatus.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'absscheduleid'=>$data['absscheduleid'],
				'absschedulename'=>$data['absschedulename'],
				'absin'=>$data['absin'],
				'absout'=>$data['absout'],
				'absstatusid'=>$data['absstatusid'],
				'shortstat'=>$data['shortstat'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select a.absscheduleid,a.absschedulename,a.absin,a.absout,b.shortstat as absstatus,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from absschedule a
						join absstatus b on b.absstatusid = a.absstatusid ";
		$absscheduleid = filter_input(INPUT_GET,'absscheduleid');
		$absschedulename = filter_input(INPUT_GET,'absschedulename');
		$sql .= " where coalesce(a.absscheduleid,'') like '%".$absscheduleid."%' 
			and coalesce(a.absschedulename,'') like '%".$absschedulename."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.absscheduleid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by absschedulename asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('absschedule');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L','L','L');
		$this->pdf->colheader = array(getCatalog('absscheduleid'),
																	getCatalog('absschedulename'),
																	getCatalog('absin'),
																	getCatalog('absout'),
																	getCatalog('absstatus'),
																	getCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,40,40,40,40,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['absscheduleid'],$row1['absschedulename'],$row1['absin'],$row1['absout'],$row1['absstatus'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='absschedule';
		parent::actionDownxls();
		$sql = "select a.absscheduleid,a.absschedulename,a.absin,a.absout,b.shortstat as absstatus,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from absschedule a
						join absstatus b on b.absstatusid = a.absstatusid ";
		$absscheduleid = filter_input(INPUT_GET,'absscheduleid');
		$absschedulename = filter_input(INPUT_GET,'absschedulename');
		$sql .= " where coalesce(a.absscheduleid,'') like '%".$absscheduleid."%' 
			and coalesce(a.absschedulename,'') like '%".$absschedulename."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.absscheduleid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by absschedulename asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		
		foreach($dataReader as $row1)
		{
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['absscheduleid'])
				->setCellValueByColumnAndRow(1,$i,$row1['absschedulename'])				
				->setCellValueByColumnAndRow(2,$i,$row1['absin'])
				->setCellValueByColumnAndRow(3,$i,$row1['absout'])
				->setCellValueByColumnAndRow(4,$i,$row1['absstatus'])
				->setCellValueByColumnAndRow(5,$i,$row1['recordstatus']);
			$i++;
		}
		
		$this->getFooterXLS($this->phpExcel);
	}
}