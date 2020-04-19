<?php
class PermitexitController extends Controller {
	public $menuname = 'permitexit';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertpermitexit(:vpermitexitname,:vsnroid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatepermitexit(:vid,:vpermitexitname,:vsnroid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vpermitexitname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vsnroid',$arraydata[2],PDO::PARAM_STR);
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
			$this->ModifyData($connection,array((isset($_POST['permitexitid'])?$_POST['permitexitid']:''),$_POST['permitexitname'],
				$_POST['snroid'],$_POST['recordstatus']));			
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
				$sql = 'call Purgepermitexit(:vid,:vcreatedby)';
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
		$permitexitid = isset ($_POST['permitexitid']) ? $_POST['permitexitid'] : '';
		$permitexitname = isset ($_POST['permitexitname']) ? $_POST['permitexitname'] : '';
		$permitexitid = isset ($_GET['q']) ? $_GET['q'] : $permitexitid;
		$permitexitname = isset ($_GET['q']) ? $_GET['q'] : $permitexitname;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.permitexitid';
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
				->from('permitexit t')
				->join('snro p','p.snroid=t.snroid')
				->where('(permitexitname like :permitexitname)',
					array(':permitexitname'=>'%'.$permitexitname.'%'))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('permitexit t')
				->join('snro p','p.snroid=t.snroid')
				->where('((permitexitname like :permitexitname)) and t.recordstatus=1',
					array(':permitexitname'=>'%'.$permitexitname.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('permitexit t')
				->join('snro p','p.snroid=t.snroid')
				->where('(permitexitname like :permitexitname)',
					array(':permitexitname'=>'%'.$permitexitname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('permitexit t')
				->join('snro p','p.snroid=t.snroid')
				->where('((permitexitname like :permitexitname)) and t.recordstatus=1',
					array(':permitexitname'=>'%'.$permitexitname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'permitexitid'=>$data['permitexitid'],
				'permitexitname'=>$data['permitexitname'],
				'snroid'=>$data['snroid'],
				'description'=>$data['description'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select a.permitexitid,a.permitexitname,b.description as snro,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from permitexit a
						left join snro b on b.snroid = a.snroid ";
		$permitexitid = filter_input(INPUT_GET,'permitexitid');
		$permitexitname = filter_input(INPUT_GET,'permitexitname');
		$sql .= " where coalesce(a.permitexitid,'') like '%".$permitexitid."%' 
			and coalesce(a.permitexitname,'') like '%".$permitexitname."%'";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.permitexitid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('permitexit');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L');
		$this->pdf->colheader = array(getCatalog('permitexitid'),
																	getCatalog('permitexitname'),
																	getCatalog('snro'),
																	getCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,77,77,25));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['permitexitid'],$row1['permitexitname'],$row1['snro'],$row1['recordstatus']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	public function actionDownxls()
	{
		$this->menuname='permitout';
		parent::actionDownxls();
		$sql = "select a.permitexitid,a.permitexitname,b.description as snro,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from permitexit a
						left join snro b on b.snroid = a.snroid ";
		$permitexitid = filter_input(INPUT_GET,'permitexitid');
		$permitexitname = filter_input(INPUT_GET,'permitexitname');
		$sql .= " where coalesce(a.permitexitid,'') like '%".$permitexitid."%' 
			and coalesce(a.permitexitname,'') like '%".$permitexitname."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.permitexitid in (".$_GET['id'].")";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['permitexitid'])
				->setCellValueByColumnAndRow(1,$i,$row1['permitexitname'])				
				->setCellValueByColumnAndRow(2,$i,$row1['snro'])
				->setCellValueByColumnAndRow(3,$i,$row1['recordstatus']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}