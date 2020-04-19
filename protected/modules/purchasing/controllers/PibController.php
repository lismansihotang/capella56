<?php
class PibController extends Controller {
	public $menuname = 'pib';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$pibid = GetSearchText(array('POST','Q'),'pibid');
		$pibno = GetSearchText(array('POST','Q'),'pibno');
		$lcno = GetSearchText(array('POST','Q'),'lcno');
		$pono = GetSearchText(array('POST','Q'),'pono');
		$page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','pibid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('pib t') 
				->leftjoin('poheader a','a.poheaderid=t.poheaderid')
				->where('
					(pibno like :pibno)
					and (lcno like :lcno) 
					and (pibid like :pibid) 
					and (pono like :pono)
					',
					array(
						':pibno'=>$pibno,
						':lcno'=>$lcno,
						':pono'=>$pono,
						':pibid'=>$pibid,
					))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('pib t') 
				->leftjoin('poheader a','a.poheaderid=t.poheaderid')
				->where('
					(pibno like :pibno)
					or (lcno like :lcno) 
					or (pibid like :pibid) 
					or (pono like :pono)
					',
					array(
						':pibno'=>$pibno,
						':pibid'=>$pibid,
						':lcno'=>$lcno,
						':pono'=>$pono,
					
					))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo']))
		{
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.pono')	
				->from('pib t') 
				->leftjoin('poheader a','a.poheaderid=t.poheaderid')
				->where('
					(pibno like :pibno)
					and (lcno like :lcno) 
					and (pibid like :pibid) 
					and (pono like :pono)
					',
					array(
						':pibno'=>$pibno,
						':lcno'=>$lcno,
						':pibid'=>$pibid,
						':pono'=>$pono,
					))				
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')	
				->from('pib t') 
				->leftjoin('poheader a','a.poheaderid=t.poheaderid')
				->where('
					(pibno like :pibno)
					or (lcno like :lcno) 
					or (pibid like :pibid) 
					or (pono like :pono)
					',
					array(
						':pibno'=>$pibno,
						':lcno'=>$lcno,
						':pono'=>$pono,
						':pibid'=>$pibid,
					))				
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'pibid'=>$data['pibid'],
				'poheaderid'=>$data['poheaderid'],
				'pono'=>$data['pono'],
				'pibno'=>$data['pibno'],
				'lcno'=>$data['lcno'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertpib(:vpoheaderid,:vpibno,:vlcno,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatepib(:vid,:vpoheaderid,:vpibno,:vlcno,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vpoheaderid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vpibno',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vlcno',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-pib"]["name"]);
		if (move_uploaded_file($_FILES["file-pib"]["tmp_name"], $target_file)) {
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
					$pono = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$poheaderid = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$pibno = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$lcno = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$this->ModifyData($connection,array($id,$poheaderid,$pibno,$lcno));
				}
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    }
	}
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['pibid'])?$_POST['pibid']:''),
				$_POST['poheaderid'],
				$_POST['pibno'],
				$_POST['lcno']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}	
	}
	public function actionPurge() {
		parent::actionPurge();
		if (isset($_POST['id'])) 	{
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgepib(:vid,:vcreatedby)';
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
			GetMessage(true,getcatalog('chooseone'));
		}
	}
	public function actionDownPDF() {
	  parent::actionDownload();
	  $sql = "select pibid,pibno,b.pono,
						case when lcno = 1 then 'Yes' else 'No' end as lcno
				from pib a 
				left join poheader b on b.poheaderid = a.poheaderid 
				";
		$pibid = GetSearchText(array('POST','Q'),'pibid');
		$pibno = GetSearchText(array('POST','Q'),'pibno');
		$lcno = GetSearchText(array('POST','Q'),'lcno');
		$pono = GetSearchText(array('POST','Q'),'pono');
		$sql .= " where coalesce(a.pibid,'') like '".$pibid."' 
			and coalesce(a.pibno,'') like '".$pibno."'
			and coalesce(a.pono,'') like '".$pono."'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.pibid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by pibno asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('pib');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L');
		$this->pdf->colheader = array(GetCatalog('pibid'),
																	GetCatalog('pibno'),
																	GetCatalog('lcno'));
		$this->pdf->setwidths(array(15,155,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['pibid'],$row1['pibno'],$row1['lcno']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='pib';
		parent::actionDownxls();
		$sql = "select pibid,pibno,
						case when lcno = 1 then 'Yes' else 'No' end as lcno
						from pib a ";
		$pibid = GetSearchText(array('POST','Q'),'pibid');
		$pibno = GetSearchText(array('POST','Q'),'pibno');
		$lcno = GetSearchText(array('POST','Q'),'lcno');
		$pono = GetSearchText(array('POST','Q'),'pono');
		$sql .= " where coalesce(a.pibid,'') like '".$pibid."' 
			and coalesce(a.pibno,'') like '".$pibno."'
			and coalesce(a.pono,'') like '".$pono."'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.pibid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by pibno asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('pibid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('pibno'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('lcno'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['pibid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['pibno'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['lcno']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}