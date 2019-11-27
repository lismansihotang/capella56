<?php
class ThemeController extends Controller {
	public $menuname = 'theme';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$themeid = GetSearchText(array('POST','Q'),'themeid');
		$themename = GetSearchText(array('POST','Q'),'themename');
		$description = GetSearchText(array('POST','Q'),'description');
		$themeprev = GetSearchText(array('POST','Q'),'themeprev');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','themeid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('theme t')
				->where('(themeid like :themeid) and (themename like :themename) and (description like :description) and (themeprev like :themeprev)',
					array(':themeid'=>$themeid,':themename'=>$themename,':description'=>$description,':themeprev'=>$themeprev))
				->queryScalar();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('theme t')
				->where('((themeid like :themeid) or (themename like :themename) or (description like :description) or (themeprev like :themeprev)) and t.recordstatus=1',
						array(':themeid'=>$themeid,':themename'=>$themename,':description'=>$description,':themeprev'=>$themeprev))
				->queryScalar();
		}		
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()			
				->from('theme t')
				->where('(themeid like :themeid) and (themename like :themename) and (description like :description) and (themeprev like :themeprev)',
												array(':themeid'=>$themeid,':themename'=>$themename,':description'=>$description,':themeprev'=>$themeprev))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		} else {
				$cmd = Yii::app()->db->createCommand()
					->select()			
					->from('theme t')
					->where('((themeid like :themeid) or (themename like :themename) or (description like :description) or (themeprev like :themeprev)) and t.recordstatus=1',
													array(':themeid'=>$themeid,':themename'=>$themename,':description'=>$description,':themeprev'=>$themeprev))
					->order($sort.' '.$order)
					->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'themeid'=>$data['themeid'],
				'themename'=>$data['themename'],
				'description'=>$data['description'],
				'themeprev'=>$data['themeprev'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Inserttheme(:vthemename,:vdescription,:vthemeprev,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatetheme(:vid,:vthemename,:vdescription,:vthemeprev,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vthemename',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vthemeprev',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-theme"]["name"]);
		if (move_uploaded_file($_FILES["file-theme"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$themename = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$themeprev = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$this->ModifyData($connection,array($id,$themename,$description,$themeprev,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['themeid'])?$_POST['themeid']:''),$_POST['themename'],$_POST['description'],$_POST['themeprev'],$_POST['recordstatus']));
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
				$sql = 'call Purgetheme(:vid,:vdatauser)';
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
		$this->dataprint['titleid'] = GetCatalog('themeid');
		$this->dataprint['titlethemename'] = GetCatalog('themename');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titlethemeprev'] = GetCatalog('themeprev');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
		$this->dataprint['url'] = Yii::app()->params['baseUrl'];
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['themename'] = GetSearchText(array('GET'),'themename');
    $this->dataprint['themeprev'] = GetSearchText(array('GET'),'themeprev');
  }
}
