<?php
class LamaangsuranController extends Controller {
	public $menuname = 'lamaangsuran';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$lamaangsuranid = GetSearchText(array('POST','Q'),'lamaangsuranid');
		$kodelamaangsuran = GetSearchText(array('POST','Q'),'kodelamaangsuran');
		$page = GetSearchText(array('POST'),'page',1,'int');
		$rows = GetSearchText(array('POST'),'rows',10,'int');
		$sort = GetSearchText(array('POST'),'sort','lamaangsuranid','int');
		$order = GetSearchText(array('POST'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$dependency = new CDbCacheDependency('SELECT MAX(updatedate) FROM lamaangsuran');
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')	
				->from('lamaangsuran t')
				->where('(lamaangsuranid like :lamaangsuranid) and (kodelamaangsuran like :kodelamaangsuran)',
					array(':lamaangsuranid'=>$lamaangsuranid,':kodelamaangsuran'=>$kodelamaangsuran))
				->queryScalar();
		}
		else  {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')	
				->from('lamaangsuran t')
				->where('((lamaangsuranid like :lamaangsuranid) or (kodelamaangsuran like :kodelamaangsuran)) and t.recordstatus=1',
					array(':lamaangsuranid'=>$lamaangsuranid,':kodelamaangsuran'=>$kodelamaangsuran))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select()	
				->from('lamaangsuran t')
				->where('(lamaangsuranid like :lamaangsuranid) and (kodelamaangsuran like :kodelamaangsuran)',
					array(':lamaangsuranid'=>$lamaangsuranid,':kodelamaangsuran'=>$kodelamaangsuran))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select()	
				->from('lamaangsuran t')
				->where('((lamaangsuranid like :lamaangsuranid) or (kodelamaangsuran like :kodelamaangsuran)) and t.recordstatus=1',
					array(':lamaangsuranid'=>$lamaangsuranid,':kodelamaangsuran'=>$kodelamaangsuran))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'lamaangsuranid'=>$data['lamaangsuranid'],
				'kodelamaangsuran'=>$data['kodelamaangsuran'],
				'jangkawaktu'=>$data['jangkawaktu'],
				'isauto'=>$data['isauto'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertlamaangsuran(:vkodelamaangsuran,:vjangkawaktu,:visauto,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatelamaangsuran(:vid,:vkodelamaangsuran,:vjangkawaktu,:visauto,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vkodelamaangsuran',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vjangkawaktu',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':visauto',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-lamaangsuran"]["name"]);
		if (move_uploaded_file($_FILES["file-lamaangsuran"]["tmp_name"], $target_file)) {
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
					$vkodelamaangsuran = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$vjangkawaktu = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$visauto = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$this->ModifyData($connection,array($id,$vkodelamaangsuran,$vjangkawaktu,$visauto,$recordstatus));
				}
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,'Line: '.$row.' ==> '.implode(" ",$e->errorInfo));
			}
    }
	}
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['lamaangsuranid'])?$_POST['lamaangsuranid']:''),
				$_POST['kodelamaangsuran'],
				$_POST['jangkawaktu'],
				$_POST['isauto'],
				$_POST['recordstatus']));
			$transaction->commit();
			GetMessage(false,'insertsuccess');
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
				$sql = 'call Purgelamaangsuran(:vid,:vdatauser)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vdatauser',GetUserPC(),PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,'insertsuccess');
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
		$this->dataprint['kodelamaangsuran'] = GetSearchText(array('GET'),'kodelamaangsuran');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'lamaangsuranid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlekodelamaangsuran'] = GetCatalog('kodelamaangsuran');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}