<?php
class PermitinController extends Controller {
	public $menuname = 'permitin';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertpermitin(:vpermitinname,:vsnroid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatepermitin(:vid,:vpermitinname,:vsnroid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vpermitinname',$arraydata[1],PDO::PARAM_STR);
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
			$this->ModifyData($connection,array((isset($_POST['permitinid'])?$_POST['permitinid']:''),$_POST['permitinname'],
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
				$sql = 'call Purgepermitin(:vid,:vcreatedby)';
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
		$permitinid = isset ($_POST['permitinid']) ? $_POST['permitinid'] : '';
		$permitinname = isset ($_POST['permitinname']) ? $_POST['permitinname'] : '';
		$permitinid = isset ($_GET['q']) ? $_GET['q'] : $permitinid;
		$permitinname = isset ($_GET['q']) ? $_GET['q'] : $permitinname;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.permitinid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo']))
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('permitin t')
				->join('snro p','p.snroid=t.snroid')
				->where('(permitinname like :permitinname)',
					array(':permitinname'=>'%'.$permitinname.'%'))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('permitin t')
				->join('snro p','p.snroid=t.snroid')
				->where('((permitinname like :permitinname)) and t.recordstatus=1',
					array(':permitinname'=>'%'.$permitinname.'%'))
				->queryScalar();
		}	
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('permitin t')
				->join('snro p','p.snroid=t.snroid')
				->where('(permitinname like :permitinname)',
					array(':permitinname'=>'%'.$permitinname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('permitin t')
				->join('snro p','p.snroid=t.snroid')
				->where('((permitinname like :permitinname)) and t.recordstatus=1',
					array(':permitinname'=>'%'.$permitinname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'permitinid'=>$data['permitinid'],
				'permitinname'=>$data['permitinname'],
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
	  $sql = "select a.permitinid,a.permitinname,b.description as snro,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from permitin a
						left join snro b on b.snroid = a.snroid ";
		$permitinid = filter_input(INPUT_GET,'permitinid');
		$permitinname = filter_input(INPUT_GET,'permitinname');
		$sql .= " where coalesce(a.permitinid,'') like '%".$permitinid."%' 
			and coalesce(a.permitinname,'') like '%".$permitinname."%'";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.permitinid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('permitin');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L');
		$this->pdf->colheader = array(getCatalog('permitinid'),
																	getCatalog('permitinname'),
																	getCatalog('snro'),
																	getCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,77,77,25));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['permitinid'],$row1['permitinname'],$row1['snro'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='permitin';
		parent::actionDownxls();
		$sql = "select a.permitinid,a.permitinname,b.description as snro,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from permitin a
						left join snro b on b.snroid = a.snroid ";
		$permitinid = filter_input(INPUT_GET,'permitinid');
		$permitinname = filter_input(INPUT_GET,'permitinname');
		$sql .= " where coalesce(a.permitinid,'') like '%".$permitinid."%' 
			and coalesce(a.permitinname,'') like '%".$permitinname."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.permitinid in (".$_GET['id'].")";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['permitinid'])
				->setCellValueByColumnAndRow(1,$i,$row1['permitinname'])				
				->setCellValueByColumnAndRow(2,$i,$row1['snro'])
				->setCellValueByColumnAndRow(3,$i,$row1['recordstatus']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}