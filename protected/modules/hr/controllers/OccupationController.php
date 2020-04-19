<?php
class OccupationController extends Controller {
	public $menuname = 'occupation';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertoccupation(:voccupationname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateoccupation(:vid,:voccupationname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$_POST['occupationid'],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $_POST['occupationid']);
		}
		$command->bindvalue(':voccupationname',$_POST['occupationname'],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$_POST['recordstatus'],PDO::PARAM_STR);
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
			$this->ModifyData($connection,array((isset($_POST['occupationid'])?$_POST['occupationid']:''),$_POST['occupationname'],
				$_POST['recordstatus']));			
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
				$sql = 'call Purgeoccupation(:vid,:vcreatedby)';
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
		$occupationid = isset ($_POST['occupationid']) ? $_POST['occupationid'] : '';
		$occupationname = isset ($_POST['occupationname']) ? $_POST['occupationname'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$occupationid = isset ($_GET['q']) ? $_GET['q'] : $occupationid;
		$occupationname = isset ($_GET['q']) ? $_GET['q'] : $occupationname;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.occupationid';
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
				->from('occupation t')
				->where('(occupationname like :occupationname)',
						array(':occupationname'=>'%'.$occupationname.'%'))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('occupation t')
				->where('((occupationname like :occupationname)) and t.recordstatus=1',
						array(':occupationname'=>'%'.$occupationname.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('occupation t')
				->where('(occupationname like :occupationname)',
						array(':occupationname'=>'%'.$occupationname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('occupation t')
				->where('((occupationname like :occupationname)) and t.recordstatus=1',
						array(':occupationname'=>'%'.$occupationname.'%'))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'occupationid'=>$data['occupationid'],
				'occupationname'=>$data['occupationname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select occupationid,occupationname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from occupation a ";
		$occupationid = filter_input(INPUT_GET,'occupationid');
		$occupationname = filter_input(INPUT_GET,'occupationname');
		$sql .= " where coalesce(a.occupationid,'') like '%".$occupationid."%' 
			and coalesce(a.occupationname,'') like '%".$occupationname."%'";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.occupationid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by occupationname asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('occupation');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L');
		$this->pdf->colheader = array(getCatalog('occupationid'),
																	getCatalog('occupationname'),
																	getCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,155,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['occupationid'],$row1['occupationname'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='occupation';
		parent::actionDownxls();
		$sql = "select occupationid,occupationname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from occupation a ";
		$occupationid = filter_input(INPUT_GET,'occupationid');
		$occupationname = filter_input(INPUT_GET,'occupationname');
		$sql .= " where coalesce(a.occupationid,'') like '%".$occupationid."%' 
			and coalesce(a.occupationname,'') like '%".$occupationname."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.occupationid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by occupationname asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['occupationid'])
				->setCellValueByColumnAndRow(1,$i,$row1['occupationname'])				
				->setCellValueByColumnAndRow(2,$i,$row1['recordstatus']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}