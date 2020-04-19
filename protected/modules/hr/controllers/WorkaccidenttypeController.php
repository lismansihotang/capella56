<?php
class workaccidenttypeController extends Controller {
	public $menuname = 'workaccidenttype';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertworkaccidenttype(:vworkaccidenttypename,:vdescription,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updateworkaccidenttype(:vid,:vworkaccidenttypename,:vdescription,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vworkaccidenttypename',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[2],PDO::PARAM_STR);
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
			$this->ModifyData($connection,array((isset($_POST['workaccidenttypeid'])?$_POST['workaccidenttypeid']:''),$_POST['workaccidenttypename'],$_POST['description'],
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
				$sql = 'call Purgeworkaccidenttype(:vid,:vcreatedby)';
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
		$workaccidenttypeid = isset ($_POST['workaccidenttypeid']) ? $_POST['workaccidenttypeid'] : '';
		$workaccidenttypename = isset ($_POST['workaccidenttypename']) ? $_POST['workaccidenttypename'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$workaccidenttypeid = isset ($_GET['q']) ? $_GET['q'] : $workaccidenttypeid;
		$workaccidenttypename = isset ($_GET['q']) ? $_GET['q'] : $workaccidenttypename;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.workaccidenttypeid';
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
				->from('workaccidenttype t')
				->where('(workaccidenttypename like :workaccidenttypename) and
						(description like :description)
						',
						array(':workaccidenttypename'=>'%'.$workaccidenttypename.'%',
						':description'=>'%'.$description.'%',
						))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('workaccidenttype t')
				->where('((workaccidenttypename like :workaccidenttypename) or
						(description like :description)
						) and t.recordstatus=1',
						array(':workaccidenttypename'=>'%'.$workaccidenttypename.'%',
						':description'=>'%'.$description.'%',
						))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('workaccidenttype t')
				->where('(workaccidenttypename like :workaccidenttypename) and
						(description like :description)
						',
						array(':workaccidenttypename'=>'%'.$workaccidenttypename.'%',
						':description'=>'%'.$description.'%',
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
				->from('workaccidenttype t')
				->where('((workaccidenttypename like :workaccidenttypename) or
						(description like :description)
						) and t.recordstatus=1',
						array(':workaccidenttypename'=>'%'.$workaccidenttypename.'%',
						':description'=>'%'.$description.'%',
						))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'workaccidenttypeid'=>$data['workaccidenttypeid'],
				'workaccidenttypename'=>$data['workaccidenttypename'],
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
	  $sql = "select workaccidenttypeid,workaccidenttypename,description,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from workaccidenttype a ";
		$workaccidenttypeid = filter_input(INPUT_GET,'workaccidenttypeid');
		$workaccidenttypename = filter_input(INPUT_GET,'workaccidenttypename');
		$description = filter_input(INPUT_GET,'description');
		$validperiod = filter_input(INPUT_GET,'validperiod');
		$sql .= " where coalesce(a.workaccidenttypeid,'') like '%".$workaccidenttypeid."%' 
			and coalesce(a.workaccidenttypename,'') like '%".$workaccidenttypename."%'
			and coalesce(a.description,'') like '%".$description."%'
			
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.workaccidenttypeid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by workaccidenttypename asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('workaccidenttype');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L','L');
		$this->pdf->colheader = array(getCatalog('workaccidenttypeid'),
																	getCatalog('workaccidenttypename'),
																	getCatalog('description'),
																
																	getCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,63,63,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['workaccidenttypeid'],$row1['workaccidenttypename'],$row1['description'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='workaccidenttype';
		parent::actionDownxls();
		$sql = "select workaccidenttypeid,workaccidenttypename,description,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from workaccidenttype a ";
		$workaccidenttypeid = filter_input(INPUT_GET,'workaccidenttypeid');
		$workaccidenttypename = filter_input(INPUT_GET,'workaccidenttypename');
		$description = filter_input(INPUT_GET,'description');
		
		$sql .= " where coalesce(a.workaccidenttypeid,'') like '%".$workaccidenttypeid."%' 
			and coalesce(a.workaccidenttypename,'') like '%".$workaccidenttypename."%'
			and coalesce(a.description,'') like '%".$description."%'
		
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.workaccidenttypeid in (".$_GET['id'].")";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['workaccidenttypeid'])
				->setCellValueByColumnAndRow(1,$i,$row1['workaccidenttypename'])				
				->setCellValueByColumnAndRow(2,$i,$row1['description'])
				
				->setCellValueByColumnAndRow(3,$i,$row1['recordstatus']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}