<?php
class ParameterController extends Controller {
	public $menuname = 'parameter';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$parameterid = GetSearchText(array('POST','Q'),'parameterid');
		$paramname = GetSearchText(array('POST','Q'),'paramname');
		$paramvalue = GetSearchText(array('POST','Q'),'paramvalue');
		$description = GetSearchText(array('POST','Q'),'description');
		$modulename = GetSearchText(array('POST','Q'),'modulename');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','parameterid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('parameter t')
				->join('modules p','t.moduleid=p.moduleid')
				->where('(paramname like :paramname) and (paramvalue like :paramvalue) and (description like :description) and (p.modulename like :modulename)',
					array(':paramname'=>$paramname,':paramvalue'=>$paramvalue,':description'=>$description,':modulename'=>$modulename))			
				->queryScalar();
		}
    else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('parameter t')
				->join('modules p','t.moduleid=p.moduleid')
				->where('((paramname like :paramname) or (paramvalue like :paramvalue) or (description like :description) or (p.modulename like :modulename)) and t.recordstatus=1',
					array(':paramname'=>$paramname,':paramvalue'=>$paramvalue,':description'=>$description,':modulename'=>$modulename))			
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('parameter t')
				->join('modules p','t.moduleid=p.moduleid')
				->where('(paramname like :paramname) and (paramvalue like :paramvalue) and (description like :description) and (p.modulename like :modulename)',
						array(':paramname'=>$paramname,':paramvalue'=>$paramvalue,':description'=>$description,':modulename'=>$modulename))			
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
    }
    else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('parameter t')
				->join('modules p','t.moduleid=p.moduleid')
				->where('((paramname like :paramname) or (paramvalue like :paramvalue) or (description like :description) or (p.modulename like :modulename)) and t.recordstatus=1',
												array(':paramname'=>$paramname,':paramvalue'=>$paramvalue,':description'=>$description,':modulename'=>$modulename))			
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'parameterid'=>$data['parameterid'],
				'paramname'=>$data['paramname'],
				'paramvalue'=>$data['paramvalue'],
				'description'=>$data['description'],
				'moduleid'=>$data['moduleid'],
				'modulename'=>$data['modulename'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertparameter(:vparamname,:vparamvalue,:vdescription,:vmoduleid,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateparameter(:vid,:vparamname,:vparamvalue,:vdescription,:vmoduleid,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vparamname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vparamvalue',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vmoduleid',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-parameter"]["name"]);
		if (move_uploaded_file($_FILES["file-parameter"]["tmp_name"], $target_file)) {
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
					$paramname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$paramvalue = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$modulename = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$moduleid = Yii::app()->db->createCommand("select moduleid from modules where modulename = '".$modulename."'")->queryScalar();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$this->ModifyData($connection,array($id,$paramname,$paramvalue,$description,$moduleid,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['parameterid'])?$_POST['parameterid']:''),$_POST['paramname'],$_POST['paramvalue'],$_POST['description'],
				$_POST['moduleid'],$_POST['recordstatus']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode($e->errorInfo));
			}
	}
	public function actionPurge() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeparameter(:vid,:vdatauser)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vdatauser',GetUserPC(),PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode($e->errorInfo));
			}
		}
		else {
			GetMessage(true,'chooseone');
		}
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['titleid'] = GetCatalog('languageid');
		$this->dataprint['titleparamname'] = GetCatalog('paramname');
		$this->dataprint['titleparamvalue'] = GetCatalog('paramvalue');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titlemodulename'] = GetCatalog('modulename');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['paramname'] = GetSearchText(array('GET'),'paramname');
    $this->dataprint['paramvalue'] = GetSearchText(array('GET'),'paramvalue');
    $this->dataprint['description'] = GetSearchText(array('GET'),'description');
    $this->dataprint['modulename'] = GetSearchText(array('GET'),'modulename');
  }
}
