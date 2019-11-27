<?php
class MaterialtypeController extends Controller {
	public $menuname = 'materialtype';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$materialtypeid = GetSearchText(array('POST','Q'),'materialtypeid');
		$materialtypecode = GetSearchText(array('POST','Q'),'materialtypecode');
		$description = GetSearchText(array('POST','Q'),'description');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','materialtypeid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$dependency = new CDbCacheDependency('SELECT MAX(updatedate) FROM materialtype');
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')	
				->from('materialtype t')
				->where('(materialtypeid like :materialtypeid) and (materialtypecode like :materialtypecode) and 
								(description like :description)',
												array(':materialtypeid'=>$materialtypeid,':materialtypecode'=>$materialtypecode,
														':description'=>$description))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')	
				->from('materialtype t')
				->where('((materialtypeid like :materialtypeid) or (materialtypecode like :materialtypecode) or 
								(description like :description)) and 
								t.recordstatus=1',
												array(':materialtypeid'=>$materialtypeid,':materialtypecode'=>$materialtypecode,
														':description'=>$description))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select()	
				->from('materialtype t')
				->where('(materialtypeid like :materialtypeid) and (materialtypecode like :materialtypecode) and 
								(description like :description)',
												array(':materialtypeid'=>$materialtypeid,':materialtypecode'=>$materialtypecode,
														':description'=>$description))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select()	
				->from('materialtype t')
				->where('((materialtypeid like :materialtypeid) or (materialtypecode like :materialtypecode) or 
								(description like :description)) and 
								t.recordstatus=1',
												array(':materialtypeid'=>$materialtypeid,':materialtypecode'=>$materialtypecode,
														':description'=>$description))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'materialtypeid'=>$data['materialtypeid'],
				'materialtypecode'=>$data['materialtypecode'],
				'description'=>$data['description'],
				'fg'=>$data['fg'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertmaterialtype(:vmaterialtypecode,:vdescription,:vfg,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else 	{
			$sql = 'call Updatematerialtype(:vid,:vmaterialtypecode,:vdescription,:vfg,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vmaterialtypecode',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vfg',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-materialtype"]["name"]);
		if (move_uploaded_file($_FILES["file-materialtype"]["tmp_name"], $target_file)) {
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
					$materialtypecode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$fg = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$this->ModifyData($connection,array($id,$materialtypecode,$description,$fg,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['materialtypeid'])?$_POST['materialtypeid']:''),$_POST['materialtypecode'],$_POST['description'],$_POST['fg'],
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
				$sql = 'call Purgematerialtype(:vid,:vdatauser)';
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
			GetMessage(true,'chooseone');
		}
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['titleid'] = GetCatalog('materialtypeid');
		$this->dataprint['titlematerialtypecode'] = GetCatalog('materialtypecode');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titlefg'] = GetCatalog('fg');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['materialtypecode'] = GetSearchText(array('GET'),'materialtypecode');
    $this->dataprint['description'] = GetSearchText(array('GET'),'description');
  }
}
