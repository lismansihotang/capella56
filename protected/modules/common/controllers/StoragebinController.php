<?php
class StoragebinController extends Controller {
	public $menuname = 'storagebin';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexcombosloc() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->searchcombosloc();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$storagebinid = GetSearchText(array('POST','Q'),'storagebinid');
		$description = GetSearchText(array('POST','Q'),'description');
		$sloccode = GetSearchText(array('POST','Q'),'sloccode');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','storagebinid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
					->select('count(1) as total')
					->from('storagebin t')
					->leftjoin('sloc a','a.slocid = t.slocid')
					->where("((coalesce(t.storagebinid,'') like :storagebinid) or (coalesce(t.description,'') like :description) or (coalesce(a.sloccode,'') like :sloccode))  
						and t.recordstatus = 1
						and a.slocid in (".getUserObjectValues('sloc').")",
							array(':storagebinid'=>$storagebinid,':description'=>$description,':sloccode'=>$sloccode))
					->queryScalar();
		}
		else
			if (isset($_GET['single']))
		{
			$cmd = Yii::app()->db->createCommand()
					->select('count(1) as total')
					->from('storagebin t')
					->leftjoin('sloc a','a.slocid = t.slocid')
					->where("((coalesce(t.storagebinid,'') like :storagebinid) or (coalesce(t.description,'') like :description) or (coalesce(a.sloccode,'') like :sloccode)) and t.recordstatus = 1",
							array(':storagebinid'=>$storagebinid,':description'=>$description,':sloccode'=>$sloccode))
					->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
					->select('count(1) as total')
					->from('storagebin t')
					->leftjoin('sloc a','a.slocid = t.slocid')
					->where("(coalesce(t.storagebinid,'') like :storagebinid) and (coalesce(t.description,'') like :description) and (coalesce(a.sloccode,'') like :sloccode)",
							array(':storagebinid'=>$storagebinid,':description'=>$description,':sloccode'=>$sloccode))
					->queryScalar();
		}		
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.sloccode')
				->from('storagebin t')
				->leftjoin('sloc a','a.slocid = t.slocid')
					->where("((coalesce(t.storagebinid,'') like :storagebinid) or (coalesce(t.description,'') like :description) or (coalesce(a.sloccode,'') like :sloccode)) and t.recordstatus = 1
						and a.slocid in (".getUserObjectValues('sloc').")",
							array(':storagebinid'=>$storagebinid,':description'=>$description,':sloccode'=>$sloccode))
				->order($sort.' '.$order)
				->queryAll();
		}
		else
			if (isset($_GET['single'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.sloccode')
				->from('storagebin t')
				->leftjoin('sloc a','a.slocid = t.slocid')
					->where("((coalesce(t.storagebinid,'') like :storagebinid) or (coalesce(t.description,'') like :description) or (coalesce(a.sloccode,'') like :sloccode)) and t.recordstatus = 1",
							array(':storagebinid'=>$storagebinid,':description'=>$description,':sloccode'=>$sloccode))
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.sloccode')
				->from('storagebin t')
				->leftjoin('sloc a','a.slocid = t.slocid')
					->where("(coalesce(t.storagebinid,'') like :storagebinid) and (coalesce(t.description,'') like :description) and (coalesce(a.sloccode,'') like :sloccode)",
							array(':storagebinid'=>$storagebinid,':description'=>$description,':sloccode'=>$sloccode))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'storagebinid'=>$data['storagebinid'],
				'description'=>$data['description'],
				'ismultiproduct'=>$data['ismultiproduct'],
				'slocid'=>$data['slocid'],
				'sloccode'=>$data['sloccode'],
				'qtymax'=>Yii::app()->format->formatNumber($data['qtymax']),
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}	
	public function searchcombosloc() {
		header('Content-Type: application/json');
		$description = GetSearchText(array('Q'),'description');
		$slocid = GetSearchText(array('GET'),'slocid',0,'int');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','storagebinid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('storagebin t')
				->leftjoin('sloc a','a.slocid = t.slocid')
				->where("(coalesce(t.description,'') like :description) and t.slocid = :slocid and t.recordstatus = 1",
						array(':slocid'=>$slocid,':description'=>$description))
				->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.sloccode')
			->from('storagebin t')
			->leftjoin('sloc a','a.slocid = t.slocid')
				->where("(coalesce(t.description,'') like :description) and (t.slocid = :slocid) and t.recordstatus = 1",
						array(':slocid'=>$slocid,':description'=>$description))
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'storagebinid'=>$data['storagebinid'],
				'description'=>$data['description'],
				'ismultiproduct'=>$data['ismultiproduct'],
				'slocid'=>$data['slocid'],
				'sloccode'=>$data['sloccode'],
				'qtymax'=>$data['qtymax'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertstoragebin(:vdescription,:vismultiproduct,:vslocid,:vqtymax,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatestoragebin(:vid,:vdescription,:vismultiproduct,:vslocid,:vqtymax,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$this->DeleteLock($this->menuname, $arraydata[0]);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		}
		$command->bindvalue(':vdescription',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vismultiproduct',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vslocid',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vqtymax',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-storagebin"]["name"]);
		if (move_uploaded_file($_FILES["file-storagebin"]["tmp_name"], $target_file)) {
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
					$sloccode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
					$description = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$ismultiproduct = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$qtymax = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$this->ModifyData($connection,array($id,$description,$ismultiproduct,$slocid,$qtymax,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['storagebinid'])?$_POST['storagebinid']:''),
				$_POST['description'], $_POST['ismultiproduct'],$_POST['slocid'],$_POST['qtymax'],
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
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgestoragebin(:vid,:vdatauser)';
				$command=$connection->createCommand($sql);
				foreach($id as $ids) {
					$command->bindvalue(':vid',$ids,PDO::PARAM_STR);
					$command->bindvalue(':vdatauser',GetUserPC(),PDO::PARAM_STR);
					$command->execute();
				}
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
		//masukkan perintah download
	  $sql = "select a.storagebinid,b.sloccode,a.description,
						case when a.ismultiproduct = 1 then 'Yes' else 'No' end as ismultiproduct,
						a.qtymax,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from storagebin a
						left join sloc b on b.slocid = a.slocid ";
		$storagebinid = GetSearchText(array('GET'),'storagebinid');
		$description = GetSearchText(array('GET'),'description');
		$sloccode = GetSearchText(array('GET'),'sloccode');
		$sql .= " where coalesce(a.storagebinid,'') like '".$storagebinid."' 
			and coalesce(b.sloccode,'') like '".$sloccode."'
			and coalesce(a.description,'') like '".$description."'
			";				
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.storagebinid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by a.storagebinid asc";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('storagebin');
		$this->pdf->AddPage('P',array(350,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('storagebinid'),
																	GetCatalog('sloccode'),
																	GetCatalog('description'),
																	GetCatalog('ismultiproduct'),
																	GetCatalog('qtymax'),																	
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,60,155,40,40,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['storagebinid'],$row1['sloccode'],$row1['description'],$row1['ismultiproduct'],$row1['qtymax'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='storagebin';
		parent::actionDownxls();
		$sql = "select a.storagebinid,b.sloccode,a.description,
						case when a.ismultiproduct = 1 then 'Yes' else 'No' end as ismultiproduct,
						a.qtymax,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from storagebin a
						left join sloc b on b.slocid = a.slocid ";
		$storagebinid = GetSearchText(array('GET'),'storagebinid');
		$description = GetSearchText(array('GET'),'description');
		$sloccode = GetSearchText(array('GET'),'sloccode');
		$sql .= " where coalesce(a.storagebinid,'') like '".$storagebinid."' 
			and coalesce(b.sloccode,'') like '".$sloccode."'
			and coalesce(a.description,'') like '".$description."'
			";					
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.storagebinid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by storagebinid asc";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('storagebinid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('sloccode'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('description'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('ismultiproduct'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('qtymax'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['storagebinid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['sloccode'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['description'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['ismultiproduct'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['qtymax'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);	
	}
}