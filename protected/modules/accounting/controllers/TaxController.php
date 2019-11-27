<?php
class TaxController extends Controller {
	public $menuname = 'tax';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$taxid = isset ($_POST['taxid']) ? $_POST['taxid'] : '';
		$taxcode = isset ($_POST['taxcode']) ? $_POST['taxcode'] : '';
		$taxvalue = isset ($_POST['taxvalue']) ? $_POST['taxvalue'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$taxid = isset ($_GET['q']) ? $_GET['q'] : $taxid;
		$taxcode = isset ($_GET['q']) ? $_GET['q'] : $taxcode;
		$taxvalue = isset ($_GET['q']) ? $_GET['q'] : $taxvalue;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.taxid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';		
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.taxid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();		
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('tax t')
				->where("((taxid like :taxid) 
				or (taxcode like :taxcode)
				or (description like :description)
				or (taxvalue like :taxvalue)) 
				and t.recordstatus = 1", array(
						':taxid' => '%' . $taxid . '%',
						':taxvalue' => '%' . $taxvalue . '%',
						':description' => '%' . $description . '%',
						':taxcode' => '%' . $taxcode . '%',
				))->queryScalar();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('tax t')
				->where("((taxid like :taxid) 
				and (taxcode like :taxcode)
				and (description like :description)
				and (taxvalue like :taxvalue))
				", array(
						':taxid' => '%' . $taxid . '%',
						':taxvalue' => '%' . $taxvalue . '%',
						':description' => '%' . $description . '%',
						':taxcode' => '%' . $taxcode . '%',
				))->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')
				->from('tax t')
				->where("((taxid like :taxid) 
				or (taxcode like :taxcode)
				or (description like :description)
				or (taxvalue like :taxvalue)) 
				and t.recordstatus = 1", array(
						':taxid' => '%' . $taxid . '%',
						':taxvalue' => '%' . $taxvalue . '%',
						':description' => '%' . $description . '%',
						':taxcode' => '%' . $taxcode . '%',
				))->order($sort . ' ' . $order)->queryAll();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')
				->from('tax t')
				->where("((taxid like :taxid) 
				and (taxcode like :taxcode)
				and (description like :description)
				and (taxvalue like :taxvalue))
				", array(
						':taxid' => '%' . $taxid . '%',
						':taxvalue' => '%' . $taxvalue . '%',
						':description' => '%' . $description . '%',
						':taxcode' => '%' . $taxcode . '%',
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		}
		foreach ($cmd as $data) { 
			$row[] = array(
				'taxid'=>$data['taxid'],
				'taxcode'=>$data['taxcode'],
				'taxvalue'=>$data['taxvalue'],
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
			$sql = 'call Inserttax(:vtaxcode,:vtaxvalue,:vdescription,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatetax(:vid,:vtaxcode,:vtaxvalue,:vdescription,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vtaxcode',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vtaxvalue',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-tax"]["name"]);
		if (move_uploaded_file($_FILES["file-tax"]["tmp_name"], $target_file)) {
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
					$taxcode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$taxvalue = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$this->ModifyData($connection,array($id,$taxcode,$taxvalue,$description,$recordstatus));
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
		header("Content-Type: application/json");
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['taxid'])?$_POST['taxid']:''),$_POST['taxcode'],$_POST['taxvalue'],$_POST['description'],$_POST['recordstatus']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	public function actionPurge() {
		header("Content-Type: application/json");		
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgetax(:vid,:vdatauser)';
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
		$this->dataprint['titleid'] = GetCatalog('taxid');
		$this->dataprint['titletaxcode'] = GetCatalog('taxcode');
		$this->dataprint['titletaxvalue'] = GetCatalog('taxvalue');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['taxcode'] = GetSearchText(array('GET'),'taxcode');
    $this->dataprint['taxvalue'] = GetSearchText(array('GET'),'taxvalue');
    $this->dataprint['description'] = GetSearchText(array('GET'),'description');
  }
}
