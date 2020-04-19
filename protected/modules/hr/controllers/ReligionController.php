<?php
class ReligionController extends Controller {
	public $menuname = 'religion';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertreligion(:vreligionname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatereligion(:vid,:vreligionname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vreligionname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
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
			$this->ModifyData($connection,array((isset($_POST['religionid'])?$_POST['religionid']:''),
				$_POST['religionname'],$_POST['recordstatus']));
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
				$sql = 'call Purgereligion(:vid,:vcreatedby)';
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
		$religionid = isset ($_POST['religionid']) ? $_POST['religionid'] : '';
		$religionname = isset ($_POST['religionname']) ? $_POST['religionname'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$religionid = isset ($_GET['q']) ? $_GET['q'] : $religionid;
		$religionname = isset ($_GET['q']) ? $_GET['q'] : $religionname;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.religionid';
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
				->from('religion t')
				->where('(religionname like :religionname)',
						array(':religionname'=>'%'.$religionname.'%'))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('religion t')
				->where('((religionname like :religionname)) and t.recordstatus=1',
						array(':religionname'=>'%'.$religionname.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('religion t')
				->where('(religionname like :religionname)',
						array(':religionname'=>'%'.$religionname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('religion t')
				->where('((religionname like :religionname)) and t.recordstatus=1',
						array(':religionname'=>'%'.$religionname.'%'))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'religionid'=>$data['religionid'],
				'religionname'=>$data['religionname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}	
	public function actionDownPDF() {
	  parent::actionDownload();
	  $sql = "select religionid,religionname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from religion a ";
		$religionid = filter_input(INPUT_GET,'religionid');
		$religionname = filter_input(INPUT_GET,'religionname');
		$sql .= " where coalesce(a.religionid,'') like '%".$religionid."%' 
			and coalesce(a.religionname,'') like '%".$religionname."%'";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.religionid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by religionname asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('religion');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L');
		$this->pdf->colheader = array(getCatalog('religionid'),
																	getCatalog('religionname'),
																	getCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,155,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['religionid'],$row1['religionname'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='religion';
		parent::actionDownxls();
		$sql = "select religionid,religionname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from religion a ";
		$religionid = filter_input(INPUT_GET,'religionid');
		$religionname = filter_input(INPUT_GET,'languagename');
		$sql .= " where coalesce(a.religionid,'') like '%".$religionid."%' 
			and coalesce(a.religionname,'') like '%".$religionname."%'";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.religionid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by religionname asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		
		foreach($dataReader as $row1)
		{
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['religionid'])
				->setCellValueByColumnAndRow(1,$i,$row1['religionname'])				
				->setCellValueByColumnAndRow(2,$i,$row1['recordstatus']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}