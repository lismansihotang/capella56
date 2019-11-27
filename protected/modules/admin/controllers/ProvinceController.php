<?php
class ProvinceController extends Controller {
	public $menuname = 'province';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$provinceid = GetSearchText(array('POST','Q'),'provinceid');
		$countrycode = GetSearchText(array('POST','Q'),'countrycode');
		$countryname = GetSearchText(array('POST','Q'),'countryname');
		$provincecode = GetSearchText(array('POST','Q'),'provincecode');
		$provincename = GetSearchText(array('POST','Q'),'provincename');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','provinceid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('province t')
				->join('country p', 't.countryid=p.countryid')
				->where('(provinceid like :provinceid) and (provincecode like :provincecode) and (provincename like :provincename) and (p.countryname like :countryname)
					and (p.countrycode like :countrycode)',
						array(':provinceid'=>$provinceid,':provincecode'=>$provincecode,':provincename'=>$provincename,':countryname'=>$countryname,
						':countrycode'=>$countrycode))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('province t')
				->join('country p', 't.countryid=p.countryid')
				->where('((provinceid like :provinceid) or (provincecode like :provincecode) or (provincename like :provincename) or (p.countryname like :countryname)
				or (p.countrycode like :countrycode)) 
				and t.recordstatus = 1',
						array(':provinceid'=>$provinceid,':provincecode'=>$provincecode,':provincename'=>$provincename,':countryname'=>$countryname,
						':countrycode'=>$countrycode))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,p.countryname')			
				->from('province t')
				->join('country p', 't.countryid=p.countryid')
				->where('(provinceid like :provinceid) and (provincecode like :provincecode) and (provincename like :provincename) and (p.countryname like :countryname)
				and (p.countrycode like :countrycode)',
						array(':provinceid'=>$provinceid,':provincecode'=>$provincecode,':provincename'=>$provincename,':countryname'=>$countryname,
						':countrycode'=>$countrycode))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,p.countryname')			
				->from('province t')
				->join('country p', 't.countryid=p.countryid')
				->where('((provinceid like :provinceid) or (provincecode like :provincecode) or (provincename like :provincename) or (p.countryname like :countryname)
				or (p.countrycode like :countrycode)) and t.recordstatus = 1',
						array(':provinceid'=>$provinceid,':provincecode'=>$provincecode,':provincename'=>$provincename,':countryname'=>$countryname,
						':countrycode'=>$countrycode))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
			'provinceid'=>$data['provinceid'],
			'countryid'=>$data['countryid'],
			'countryname'=>$data['countryname'],
			'provincecode'=>$data['provincecode'],
			'provincename'=>$data['provincename'],
			'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertprovince(:vcountryid,:vprovincecode,:vprovincename,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateprovince(:vid,:vcountryid,:vprovincecode,:vprovincename,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcountryid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vprovincecode',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vprovincename',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-province"]["name"]);
		if (move_uploaded_file($_FILES["file-province"]["tmp_name"], $target_file)) {
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
					$countrycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$countryid = Yii::app()->db->createCommand("select countryid from country where countrycode = '".$countrycode."'")->queryScalar();
					$provincecode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$provincename = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$this->ModifyData($connection,array($id,$countryid,$provincecode,$provincename,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['provinceid'])?$_POST['provinceid']:''),$_POST['countryid'],$_POST['provincecode'],$_POST['provincename'],$_POST['recordstatus']));
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
				$sql = 'call Purgeprovince(:vid,:vdatauser)';
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
    $this->dataprint['titleid'] = GetCatalog('provinceid');
    $this->dataprint['titlecountrycode'] = GetCatalog('countrycode');
    $this->dataprint['titlecountryname'] = GetCatalog('countryname');
    $this->dataprint['titleprovincecode'] = GetCatalog('provincecode');
    $this->dataprint['titleprovincename'] = GetCatalog('provincename');
    $this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['countrycode'] = GetSearchText(array('GET'),'countrycode');
    $this->dataprint['countryname'] = GetSearchText(array('GET'),'countryname');
    $this->dataprint['provincecode'] = GetSearchText(array('GET'),'provincecode');
    $this->dataprint['provincename'] = GetSearchText(array('GET'),'provincename');
  }
}
