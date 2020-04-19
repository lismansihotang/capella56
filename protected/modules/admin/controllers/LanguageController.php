<?php
class LanguageController extends Controller {
	public $menuname = 'language';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$languageid = GetSearchText(array('POST','Q'),'languageid');
		$languagename = GetSearchText(array('POST','Q'),'languagename');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','languageid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$selectcount = ' select count(1) as total ';
		$select = ' select languageid,languagename,recordstatus ';
		$from = ' from language t ';
		$where = ' where ';
		if (!isset($_GET['combo'])) {
			$where .= " (languageid like '".$languageid."') 
				and (languagename like '". $languagename ."') ";
		} else {
			$where .= " ((languageid like '".$languageid."') 
				or (languagename like '".$languagename."')) 
				and recordstatus = 1 ";
		}
		$sql = $selectcount . $from . $where;
		$cmd = Yii::app()->db->createCommand($sql)->queryScalar();
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$sql = $select . $from . $where . ' Order By ' . $sort . ' '. $order . ' limit ' . $offset . ',' . $rows;
		} else {
			$sql = $select . $from . $where . ' Order By ' . $sort . ' '. $order;
		}
		$cmd = Yii::app()->db->createCommand($sql)->queryAll();

		foreach($cmd as $data) {	
			$row[] = array(
				'languageid'=>$data['languageid'],
				'languagename'=>$data['languagename'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = "call InsertLanguage(:vlanguagename,:vrecordstatus,:vdatauser)";
			$command = $connection->createCommand($sql);
		} else {
			$sql = "call UpdateLanguage(:vlanguageid,:vlanguagename,:vrecordstatus,:vdatauser)";
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vlanguageid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vlanguagename',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-language"]["name"]);
		if (move_uploaded_file($_FILES["file-language"]["tmp_name"], $target_file)) {
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
					$languagename = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
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
			$this->ModifyData($connection,array((isset($_POST['languageid'])?$_POST['languageid']:''),$_POST['languagename'],$_POST['recordstatus']));
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
				$sql = 'call PurgeLanguage(:vid,:vdatauser)';
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
			GetMessage(true,getcatalog('chooseone'));
		}
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['languagename'] = GetSearchText(array('GET'),'languagename');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'languageid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlelanguagename'] = GetCatalog('languagename');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}