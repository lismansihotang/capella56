<?php
class UnitofmeasureController extends Controller {
	public $menuname = 'unitofmeasure';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			if(isset($_GET['productplant']))
			echo $this->searchproductplant();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$unitofmeasureid = GetSearchText(array('POST','Q'),'unitofmeasureid');
		$uomcode = GetSearchText(array('POST','Q'),'uomcode');
		$description = GetSearchText(array('POST','Q'),'description');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','unitofmeasureid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('unitofmeasure t')
				->where('((unitofmeasureid like :unitofmeasureid) or (uomcode like :uomcode) or (description like :description)) and t.recordstatus=1',
					array(':unitofmeasureid'=>$unitofmeasureid,':uomcode'=>$uomcode,':description'=>$description))
				->queryScalar();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('unitofmeasure t')
				->where('(unitofmeasureid like :unitofmeasureid) and (uomcode like :uomcode) and (description like :description)',
					array(':unitofmeasureid'=>$unitofmeasureid,':uomcode'=>$uomcode,':description'=>$description))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('unitofmeasure t')
				->where('((unitofmeasureid like :unitofmeasureid) or (uomcode like :uomcode) or (description like :description)) and t.recordstatus=1',
							array(':unitofmeasureid'=>$unitofmeasureid,':uomcode'=>$uomcode,':description'=>$description))
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('unitofmeasure t')
				->where('(unitofmeasureid like :unitofmeasureid) and (uomcode like :uomcode) and (description like :description)',
						array(':unitofmeasureid'=>$unitofmeasureid,':uomcode'=>$uomcode,':description'=>$description))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'unitofmeasureid'=>$data['unitofmeasureid'],
				'uomcode'=>$data['uomcode'],
				'description'=>$data['description'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchproductplant() {
		header("Content-Type: application/json");
		$unitofmeasureid = GetSearchText(array('POST','Q'),'unitofmeasureid');
		$uomcode = GetSearchText(array('POST','Q'),'uomcode');
		$productid = GetSearchText(array('POST','Q'),'productid');
		$description = GetSearchText(array('POST','Q'),'description');
		$page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','unitofmeasureid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->selectdistinct('t.*')
			->from('unitofmeasure t')
			->join('productplant a','a.unitofissue = t.unitofmeasureid')
			->where('((uomcode like :uomcode) or (description like :description)) and (productid = :productid)',
				array(':uomcode'=>$uomcode,
					':description'=>$description,
					':productid'=>$productid
				))
			->order($sort.' '.$order)
			->queryAll();
		$result['total'] = count($cmd);
		foreach($cmd as $data) {	
			$row[] = array(
				'unitofmeasureid'=>$data['unitofmeasureid'],
				'uomcode'=>$data['uomcode'],
				'description'=>$data['description'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call InsertUOM(:vuomcode,:vdescription,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call UpdateUOM(:vid,:vuomcode,:vdescription,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vuomcode',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-unitofmeasure"]["name"]);
		if (move_uploaded_file($_FILES["file-unitofmeasure"]["tmp_name"], $target_file)) {
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
					$uomcode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$this->ModifyData($connection,array($id,$languagename,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['unitofmeasureid'])?$_POST['unitofmeasureid']:''),$_POST['uomcode'],$_POST['description'],$_POST['recordstatus']));
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
				$sql = 'call PurgeUOM(:vid,:vdatauser)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vdatauser',GetUserPC(),PDO::PARAM_STR);
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
			GetMessage(true, getcatalog('chooseone'));
		}
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['titleid'] = GetCatalog('unitofmeasureid');
		$this->dataprint['titleuomcode'] = GetCatalog('uomcode');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['uomcode'] = GetSearchText(array('GET'),'uomcode');
    $this->dataprint['description'] = GetSearchText(array('GET'),'description');
  }
}
