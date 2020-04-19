<?php
class MealtypeController extends Controller {
	public $menuname = 'mealtype';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertmealtype(:vmealtypename,:vdescription,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updatemealtype(:vid,:vmealtypename,:vdescription,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vmealtypename',$arraydata[1],PDO::PARAM_STR);
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
			$this->ModifyData($connection,array((isset($_POST['mealtypeid'])?$_POST['mealtypeid']:''),
			$_POST['mealtypename'],
			$_POST['description'],
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
				$sql = 'call Purgemealtype(:vid,:vcreatedby)';
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
		$mealtypeid = isset ($_POST['mealtypeid']) ? $_POST['mealtypeid'] : '';
		$mealtypename = isset ($_POST['mealtypename']) ? $_POST['mealtypename'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$mealtypeid = isset ($_GET['q']) ? $_GET['q'] : $mealtypeid;
		$mealtypename = isset ($_GET['q']) ? $_GET['q'] : $mealtypename;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.mealtypeid';
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
				->from('mealtype t')
				->where('(mealtypename like :mealtypename)
						',
						array(':mealtypename'=>'%'.$mealtypename.'%'
						))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('mealtype t')
				->where('((mealtypename like :mealtypename)
						) and t.recordstatus=1',
						array(':mealtypename'=>'%'.$mealtypename.'%'
						))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('mealtype t')
				->where('(mealtypename like :mealtypename)
						',
						array(':mealtypename'=>'%'.$mealtypename.'%'
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
				->from('mealtype t')
				->where('((mealtypename like :mealtypename)
						) and t.recordstatus=1',
						array(':mealtypename'=>'%'.$mealtypename.'%'
						))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'mealtypeid'=>$data['mealtypeid'],
				'mealtypename'=>$data['mealtypename'],
				'description'=>$data['description'],
				'recordstatus'=>$data['recordstatus']
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select mealtypeid,mealtypename,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from mealtype a ";
		$mealtypeid = filter_input(INPUT_GET,'mealtypeid');
		$mealtypename = filter_input(INPUT_GET,'mealtypename');
		$sql .= " where coalesce(a.mealtypeid,'') like '%".$mealtypeid."%' 
			and coalesce(a.mealtypename,'') like '%".$mealtypename."%'
			
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.mealtypeid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by mealtypename asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('mealtype');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L','L');
		$this->pdf->colheader = array(getCatalog('mealtypeid'),
																	getCatalog('mealtypename'),
																	
																	getCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,63,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['mealtypeid'],$row1['mealtypename'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='mealtype';
		parent::actionDownxls();
		$sql = "select mealtypeid,mealtypename,reporttype,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from mealtype a ";
		$mealtypeid = filter_input(INPUT_GET,'mealtypeid');
		$mealtypename = filter_input(INPUT_GET,'mealtypename');
		$reporttype = filter_input(INPUT_GET,'reporttype');
		
		$sql .= " where coalesce(a.mealtypeid,'') like '%".$mealtypeid."%' 
			and coalesce(a.mealtypename,'') like '%".$mealtypename."%'
			and coalesce(a.reporttype,'') like '%".$reporttype."%'
		
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.mealtypeid in (".$_GET['id'].")";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['mealtypeid'])
				->setCellValueByColumnAndRow(1,$i,$row1['mealtypename'])				
				->setCellValueByColumnAndRow(2,$i,$row1['reporttype'])
				
				->setCellValueByColumnAndRow(3,$i,$row1['recordstatus']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}