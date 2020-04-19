<?php
class CatalogsysController extends Controller {
	public $menuname = 'catalogsys';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$catalogsysid = GetSearchText(array('POST'),'catalogsysid');
		$languagename = GetSearchText(array('POST'),'languagename');
		$catalogname = GetSearchText(array('POST'),'catalogname');
		$catalogval = GetSearchText(array('POST'),'catalogval');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','catalogsysid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$selectcount = ' select count(1) as total ';
		$select = ' select t.*,p.languagename ';
		$from = ' from catalogsys t 
			left join language p on t.languageid = p.languageid ';
		$where = " where ((p.languagename like '". $languagename ."') 
			and (catalogname like '". $catalogname ."') 
			and (catalogval like '". $catalogval ."')) ";
		$sql = $selectcount . $from . $where;
		$cmd = Yii::app()->db->createCommand($sql)->queryScalar();
		$result['total'] = $cmd;
		$sql = $select . $from . $where . ' Order By ' . $sort . ' '. $order . ' limit ' . $offset . ',' . $rows;
		$cmd = Yii::app()->db->createCommand($sql)->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'catalogsysid'=>$data['catalogsysid'],
				'languageid'=>$data['languageid'],
				'languagename'=>$data['languagename'],
				'catalogname'=>$data['catalogname'],
				'catalogval'=>$data['catalogval'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertcatalogsys(:vlanguageid,:vcatalogname,:vcatalogval,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatecatalogsys(:vid,:vlanguageid,:vcatalogname,:vcatalogval,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vlanguageid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vcatalogname',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcatalogval',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-catalogsys"]["name"]);
		if (move_uploaded_file($_FILES["file-catalogsys"]["tmp_name"], $target_file)) {
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
					$languageid = Yii::app()->db->createCommand("select languageid from language where languagename = '".$languagename."'")->queryScalar();
					$catalogname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$catalogval = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$this->ModifyData($connection,array($id,$languageid,$catalogname,$catalogval));
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
			$this->ModifyData($connection,array((isset($_POST['catalogsysid'])?$_POST['catalogsysid']:''),
			$_POST['languageid'],
			$_POST['catalogname'],
			$_POST['catalogval']));
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
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgecatalogsys(:vid,:vdatauser)';
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
			GetMessage(true,getcatalog('chooseone'));
		}
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['languagename'] = GetSearchText(array('GET'),'languagename');
		$this->dataprint['catalogname'] = GetSearchText(array('GET'),'catalogname');
		$this->dataprint['catalogval'] = GetSearchText(array('GET'),'catalogval');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'languageid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlelanguagename'] = GetCatalog('languagename');
		$this->dataprint['titlecatalogname'] = GetCatalog('catalogname');
		$this->dataprint['titlecatalogval'] = GetCatalog('catalogval');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}