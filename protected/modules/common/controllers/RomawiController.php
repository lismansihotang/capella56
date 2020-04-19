<?php
class RomawiController extends Controller {
	public $menuname = 'romawi';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$romawiid = GetSearchText(array('POST','Q'),'romawiid');
		$monthcal = GetSearchText(array('POST','Q'),'monthcal');
		$monthrm = GetSearchText(array('POST','Q'),'monthrm');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','romawiid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo']))
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('romawi t')
				->where('(romawiid like :romawiid) and (monthcal like :monthcal) and (monthrm like :monthrm)',
					array(':romawiid'=>$romawiid,':monthcal'=>$monthcal,
							':monthrm'=>$monthrm))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('romawi t')
				->where('((romawiid like :romawiid) or (monthcal like :monthcal) or (monthrm like :monthrm)) 
					and t.recordstatus=1',
					array(':romawiid'=>$romawiid,':monthcal'=>$monthcal,
							':monthrm'=>$monthrm))
				->queryScalar();
		}
		$result['total'] = $cmd;		
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('romawi t')
				->where('(romawiid like :romawiid) and (monthcal like :monthcal) and (monthrm like :monthrm)',
					array(':romawiid'=>$romawiid,':monthcal'=>$monthcal,
							':monthrm'=>$monthrm))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
			}
			else {
				$cmd = Yii::app()->db->createCommand()
					->select()	
					->from('romawi t')
					->where('((romawiid like :romawiid) or (monthcal like :monthcal) or 
							(monthrm like :monthrm)) and t.recordstatus=1',
											array(':monthcal'=>$monthcal,
													':monthrm'=>$monthrm))
					->order($sort.' '.$order)
					->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'romawiid'=>$data['romawiid'],
				'monthcal'=>$data['monthcal'],
				'monthrm'=>$data['monthrm'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertromawi(:vmonthcal,:vmonthrm,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updateromawi(:vid,:vmonthcal,:vmonthrm,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vmonthcal',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vmonthrm',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}		
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-romawi"]["name"]);
		if (move_uploaded_file($_FILES["file-romawi"]["tmp_name"], $target_file)) {
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
					$monthcal = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$monthrm = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$this->ModifyData($connection,array($id,$monthcal,$monthrm,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['romawiid'])?$_POST['romawiid']:''),$_POST['monthcal'],
			$_POST['monthrm'],$_POST['recordstatus']));
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
				$sql = 'call Purgeromawi(:vid,:vdatauser)';
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
		$this->dataprint['monthcal'] = GetSearchText(array('GET'),'monthcal');
		$this->dataprint['monthrm'] = GetSearchText(array('GET'),'monthrm');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'romawiid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlemonthcal'] = GetCatalog('monthcal');
		$this->dataprint['titlemonthrm'] = GetCatalog('monthrm');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}