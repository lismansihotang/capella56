<?php
class FacilitytypeController extends Controller {
	public $menuname = 'facilitytype';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertfacilitytype(:vfacilitytypename,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updatefacilitytype(:vid,:vfacilitytypename,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vfacilitytypename',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-facilitytype"]["name"]);
		if (move_uploaded_file($_FILES["file-facilitytype"]["tmp_name"], $target_file)) {
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
					$facilitytypename = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData($connection,array($id,$facilitytypename,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['facilitytypeid'])?$_POST['facilitytypeid']:''),
				$_POST['facilitytypename'],$_POST['recordstatus']));			
			$transaction->commit();
			getmessage(false,getcatalog('insertsuccess'));
		}
		catch (Exception $e)
		{
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
				$sql = 'call Purgefacilitytype(:vid,:vcreatedby)';
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
		$facilitytypeid = isset ($_POST['facilitytypeid']) ? $_POST['facilitytypeid'] : '';
		$facilitytypename = isset ($_POST['facilitytypename']) ? $_POST['facilitytypename'] : '';
		$facilitytypeid = isset ($_GET['q']) ? $_GET['q'] : $facilitytypeid;
		$facilitytypename = isset ($_GET['q']) ? $_GET['q'] : $facilitytypename;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.facilitytypeid';
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
				->from('facilitytype t')
				->where('(facilitytypename like :facilitytypename)',
					array(':facilitytypename'=>'%'.$facilitytypename.'%'))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('facilitytype t')
				->where('((facilitytypename like :facilitytypename)) and t.recordstatus=1',
					array(':facilitytypename'=>'%'.$facilitytypename.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('facilitytype t')
				->where('(facilitytypename like :facilitytypename)',
					array(':facilitytypename'=>'%'.$facilitytypename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('facilitytype t')
				->where('((facilitytypename like :facilitytypename)) and t.recordstatus=1',
					array(':facilitytypename'=>'%'.$facilitytypename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'facilitytypeid'=>$data['facilitytypeid'],
				'facilitytypename'=>$data['facilitytypename'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select facilitytypeid,facilitytypename,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from facilitytype a ";
		$facilitytypeid = filter_input(INPUT_GET,'facilitytypeid');
		$facilitytypename = filter_input(INPUT_GET,'facilitytypename');
		$sql .= " where coalesce(a.facilitytypeid,'') like '%".$facilitytypeid."%' 
			and coalesce(a.facilitytypename,'') like '%".$facilitytypename."%'";
		if ($_GET['id'] !== '') {
			$sql = $sql . " and a.facilitytypeid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('facilitytype');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L');
		$this->pdf->colheader = array(getCatalog('facilitytypeid'),
																	getCatalog('facilitytypename'),
																	getCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,155,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['facilitytypeid'],$row1['facilitytypename'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='facilitytype';
		parent::actionDownxls();
		$sql = "select facilitytypeid,facilitytypename,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from facilitytype a ";
		$facilitytypeid = filter_input(INPUT_GET,'facilitytypeid');
		$facilitytypename = filter_input(INPUT_GET,'facilitytypename');
		$sql .= " where coalesce(a.facilitytypeid,'') like '%".$facilitytypeid."%' 
			and coalesce(a.facilitytypename,'') like '%".$facilitytypename."%'";
		if ($_GET['id'] !== '') {
			$sql = $sql . " and a.facilitytypeid in (".$_GET['id'].")";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['facilitytypeid'])
				->setCellValueByColumnAndRow(1,$i,$row1['facilitytypename'])			
				->setCellValueByColumnAndRow(2,$i,$row1['recordstatus']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}