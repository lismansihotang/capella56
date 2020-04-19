<?php
class SexController extends Controller {
	public $menuname = 'sex';
	public function actionIndex() {
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
		try {
			if (isset($_POST['isNewRecord'])) {
				$sql = 'call Insertsex(:vsexname,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else {
				$sql = 'call Updatesex(:vid,:vsexname,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['sexid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['sexid']);
			}
			$command->bindvalue(':vsexname',$_POST['sexname'],PDO::PARAM_STR);
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
				$sql = 'call Purgesex(:vid,:vcreatedby)';
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
		$sexid = isset ($_POST['sexid']) ? $_POST['sexid'] : '';
		$sexname = isset ($_POST['sexname']) ? $_POST['sexname'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$sexid = isset ($_GET['q']) ? $_GET['q'] : $sexid;
		$sexname = isset ($_GET['q']) ? $_GET['q'] : $sexname;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		// pagging
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.sexid';
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
				->from('sex t')
				->where('(sexname like :sexname)',
						array(':sexname'=>'%'.$sexname.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('sex t')
				->where('((sexname like :sexname)) and t.recordstatus=1',
						array(':sexname'=>'%'.$sexname.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('sex t')
				->where('(sexname like :sexname)',
						array(':sexname'=>'%'.$sexname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('sex t')
				->where('((sexname like :sexname)) and t.recordstatus=1',
						array(':sexname'=>'%'.$sexname.'%'))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'sexid'=>$data['sexid'],
				'sexname'=>$data['sexname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select sexid,sexname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from sex a ";
		$sexid = filter_input(INPUT_GET,'sexid');
		$sexname = filter_input(INPUT_GET,'sexname');
		$sql .= " where coalesce(a.sexid,'') like '%".$sexid."%' 
			and coalesce(a.sexname,'') like '%".$sexname."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.sexid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('sex');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L');
		$this->pdf->colheader = array(getCatalog('sexid'),
																	getCatalog('sexname'),
																	getCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,155,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['sexid'],$row1['sexname'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='sex';
		parent::actionDownxls();
		$sql = "select sexid,sexname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from sex a ";
		$sexid = filter_input(INPUT_GET,'sexid');
		$sexname = filter_input(INPUT_GET,'sexname');
		$sql .= " where coalesce(a.sexid,'') like '%".$sexid."%' 
			and coalesce(a.sexname,'') like '%".$sexname."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.sexid in (".$_GET['id'].")";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['sexid'])
				->setCellValueByColumnAndRow(1,$i,$row1['sexname'])							
				->setCellValueByColumnAndRow(2,$i,$row1['recordstatus']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}