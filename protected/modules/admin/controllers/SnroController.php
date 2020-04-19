<?php
class SnroController extends Controller {
	public $menuname = 'snro';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexsnrodet() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionsearchsnrodet();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$snroid = GetSearchText(array('POST','Q'),'snroid');
		$description = GetSearchText(array('POST','Q'),'description');
		$formatdoc = GetSearchText(array('POST','Q'),'formatdoc');
		$formatno = GetSearchText(array('POST','Q'),'formatno');
		$repeatby = GetSearchText(array('POST','Q'),'repeatby');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','snroid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('snro t')
				->where('(snroid like :snroid) and (description like :description) and (formatdoc like :formatdoc) and (formatno like :formatno) and (repeatby like :repeatby)',
						array(':snroid'=>$snroid,':description'=>$description,':formatdoc'=>$formatdoc,':formatno'=>$formatno,':repeatby'=>$repeatby))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('snro t')
				->where('((snroid like :snroid) or (description like :description) or (formatdoc like :formatdoc) or (formatno like :formatno) or (repeatby like :repeatby)) and t.recordstatus=1',
						array(':snroid'=>$snroid,':description'=>$description,':formatdoc'=>$formatdoc,':formatno'=>$formatno,':repeatby'=>$repeatby))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,(select count(1) from snrodet z where z.snroid = t.snroid) as jumlah')	
				->from('snro t')
				->where('(snroid like :snroid) and (description like :description) and (formatdoc like :formatdoc) and (formatno like :formatno) and (repeatby like :repeatby)',
						array(':snroid'=>$snroid,':description'=>$description,':formatdoc'=>$formatdoc,':formatno'=>$formatno,':repeatby'=>$repeatby))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,(select count(1) from snrodet z where z.snroid = t.snroid) as jumlah')	
				->from('snro t')
				->where('((snroid like :snroid) or (description like :description) or (formatdoc like :formatdoc) or (formatno like :formatno) or (repeatby like :repeatby)) and t.recordstatus=1',
						array(':snroid'=>$snroid,':description'=>$description,':formatdoc'=>$formatdoc,':formatno'=>$formatno,':repeatby'=>$repeatby))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'snroid'=>$data['snroid'],
				'description'=>$data['description'],
				'formatdoc'=>$data['formatdoc'],
				'formatno'=>$data['formatno'],
				'repeatby'=>$data['repeatby'],
				'jumlah'=>$data['jumlah'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionsearchsnrodet() {
		header('Content-Type: application/json');
		$id = 0;	
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
		}
		else 
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
		}
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','snrodid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();		
		$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('snrodet t')
				->join('snro p','p.snroid=t.snroid')
				->join('plant a','a.plantid=t.plantid')
				->where('p.snroid = '.$id)
				->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
				->select('t.*,p.description,a.plantcode')	
				->from('snrodet t')
        ->join('snro p','p.snroid=t.snroid')
				->join('plant a','a.plantid=t.plantid')
				->where('p.snroid = '.$id)
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'snrodid'=>$data['snrodid'],
				'snroid'=>$data['snroid'],
				'description'=>$data['description'],
				'plantid'=>$data['plantid'],
				'plantcode'=>$data['plantcode'],
				'curdd'=>$data['curdd'],
				'curmm'=>$data['curmm'],
				'curyy'=>$data['curyy'],
				'curvalue'=>$data['curvalue'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionGetData() {
		$id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'snroid' => $id
		));
	}
	private function ModifyData($connection,$arraydata) {
		$id = (int)$arraydata[0];
		$sql = 'call Modifsnro(:vid,:vdescription,:vformatdoc,:vformatno,:vrepeatby,:vrecordstatus,:vdatauser)';
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vformatdoc',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vformatno',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vrepeatby',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-snro"]["name"]);
		if (move_uploaded_file($_FILES["file-snro"]["tmp_name"], $target_file)) {
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
					$description = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$formatdoc = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$formatno = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$repeatby = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$this->ModifyData($connection,array($id,$description,$formatdoc,$formatno,$repeatby,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['snro-snroid'])?$_POST['snro-snroid']:''),
				$_POST['snro-description'],$_POST['snro-formatdoc'],$_POST['snro-formatno'],
				$_POST['snro-repeatby'],((isset($_POST['snro-recordstatus']))?1:0)));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}	
	}
	private function ModifyDataSnrodet($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertsnrodet(:vplantid,:vsnroid,:vcurdd,:vcurmm,:vcuryy,:vcurvalue,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatesnrodet(:vid,:vsnroid,:vplantid,:vcurdd,:vcurmm,:vcuryy,:vcurvalue,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vplantid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vsnroid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcurdd',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vcurmm',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vcuryy',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vcurvalue',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionSavesnrodet() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDatasnrodet($connection,array((isset($_POST['snrodid'])?$_POST['snrodid']:''),$_POST['plantid'],$_POST['snroid'],$_POST['curdd'],$_POST['curmm'],$_POST['curyy'],
				$_POST['curvalue']));
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
				$sql = 'call Purgesnro(:vid,:vdatauser)';
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
	public function actionPurgesnrodet() {
		parent::actionIndex();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgesnrodet(:vid,:vdatauser)';
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
		$this->dataprint['description'] = GetSearchText(array('GET'),'description');
		$this->dataprint['formatdoc'] = GetSearchText(array('GET'),'formatdoc');
		$this->dataprint['formatno'] = GetSearchText(array('GET'),'formatno');
		$this->dataprint['repeatby'] = GetSearchText(array('GET'),'repeatby');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'languageid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titleformatdoc'] = GetCatalog('formatdoc');
		$this->dataprint['titleformatno'] = GetCatalog('formatno');
		$this->dataprint['titlerepeatby'] = GetCatalog('repeatby');
		$this->dataprint['titleplantcode'] = GetCatalog('plantcode');
		$this->dataprint['titlecurdd'] = GetCatalog('curdd');
		$this->dataprint['titlecurmm'] = GetCatalog('curmm');
		$this->dataprint['titlecuryy'] = GetCatalog('curyy');
		$this->dataprint['titlecurvalue'] = GetCatalog('curvalue');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}