<?php
class CityController extends Controller {
	public $menuname = 'city';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$cityid = GetSearchText(array('POST','Q'),'cityid');
		$provincename = GetSearchText(array('POST','Q'),'provincename');
		$citycode = GetSearchText(array('POST','Q'),'citycode');
		$cityname = GetSearchText(array('POST','Q'),'cityname');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','cityid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('city t')
				->join('province p', 't.provinceid=p.provinceid')
				->where('(cityid like :cityid) and (citycode like :citycode) and (cityname like :cityname) and (p.provincename like :provincename)',
						array(':cityid'=>$cityid,':citycode'=>$citycode,':cityname'=>$cityname,':provincename'=>$provincename))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('city t')
				->join('province p', 't.provinceid=p.provinceid')
				->where('((cityid like :cityid) or (citycode like :citycode) or (cityname like :cityname) or (p.provincename like :provincename)) and t.recordstatus = 1',
						array(':cityid'=>$cityid,':citycode'=>$citycode,':cityname'=>$cityname,':provincename'=>$provincename))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,p.provincename')			
				->from('city t')
				->join('province p', 't.provinceid=p.provinceid')
				->where('(cityid like :cityid) and (citycode like :citycode) and (cityname like :cityname) and (p.provincename like :provincename)',
						array(':cityid'=>$cityid,':citycode'=>$citycode,':cityname'=>$cityname,':provincename'=>$provincename))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,p.provincename')			
				->from('city t')
				->join('province p', 't.provinceid=p.provinceid')
				->where('((cityid like :cityid) or (citycode like :citycode) or (cityname like :cityname) or (p.provincename like :provincename)) and t.recordstatus = 1',
						array(':cityid'=>$cityid,':citycode'=>$citycode,':cityname'=>$cityname,':provincename'=>$provincename))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'cityid'=>$data['cityid'],
				'provinceid'=>$data['provinceid'],
				'provincename'=>$data['provincename'],
				'citycode'=>$data['citycode'],
				'cityname'=>$data['cityname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertcity(:vprovinceid,:vcitycode,:vcityname,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatecity(:vid,:vprovinceid,:vcitycode,:vcityname,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vprovinceid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vcitycode',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcityname',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-city"]["name"]);
		if (move_uploaded_file($_FILES["file-city"]["tmp_name"], $target_file)) {
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
					$provincecode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$provinceid = Yii::app()->db->createCommand("select provinceid from province where provincecode = '".$provincecode."'")->queryScalar();
					$citycode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$cityname = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$this->ModifyData($connection,array($id,$provinceid,$citycode,$cityname,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['cityid'])?$_POST['cityid']:''),$_POST['provinceid'],$_POST['citycode'],$_POST['cityname'],$_POST['recordstatus']));
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
				$sql = 'call Purgecity(:vid,:vdatauser)';
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
		$this->dataprint['provincecode'] = GetSearchText(array('GET'),'provincecode');
		$this->dataprint['provincename'] = GetSearchText(array('GET'),'provincename');
		$this->dataprint['citycode'] = GetSearchText(array('GET'),'citycode');
		$this->dataprint['cityname'] = GetSearchText(array('GET'),'cityname');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'provinceid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titleprovincecode'] = GetCatalog('provincecode');
		$this->dataprint['titleprovincename'] = GetCatalog('provincename');
		$this->dataprint['titlecitycode'] = GetCatalog('citycode');
		$this->dataprint['titlecityname'] = GetCatalog('cityname');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}