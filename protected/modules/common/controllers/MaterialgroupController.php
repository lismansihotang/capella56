<?php
class MaterialgroupController extends Controller {
	public $menuname = 'materialgroup';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndextrx() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->searchtrx();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexfg() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->searchfg();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$materialgroupid = GetSearchText(array('POST','Q'),'materialgroupid');
		$materialgroupcode = GetSearchText(array('POST','Q'),'materialgroupcode');
		$description = GetSearchText(array('POST','Q'),'description');
		$parentmatgroupcode = GetSearchText(array('POST','Q'),'parentmatgroupcode');
		$parentmatgroupdesc = GetSearchText(array('POST','Q'),'parentmatgroupdesc');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','materialgroupid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$dependency = new CDbCacheDependency('SELECT MAX(updatedate) FROM materialgroup');
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')	
				->from('materialgroup t')
				->leftjoin('materialgroup a','a.materialgroupid = t.parentmatgroupid')
				->where("(coalesce(t.materialgroupid,'') like :materialgroupid) 
						and (coalesce(t.materialgroupcode,'') like :materialgroupcode) 
						and (coalesce(t.description,'') like :description) 
						and (coalesce(a.materialgroupcode,'') like :parentmatgroupcode)
						and (coalesce(a.description,'') like :parentmatgroupdesc)
					",
					array(
						':materialgroupid'=>$materialgroupid,
						':materialgroupcode'=>$materialgroupcode,
						':description'=>$description,
						':parentmatgroupcode'=>$parentmatgroupcode,
						':parentmatgroupdesc'=>$parentmatgroupdesc
				))
			->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')	
				->from('materialgroup t')
				->leftjoin('materialgroup a','a.materialgroupid = t.parentmatgroupid')
				->where("((coalesce(t.materialgroupid,'') like :materialgroupid) 
					or (coalesce(t.materialgroupcode,'') like :materialgroupcode) 
					or (coalesce(t.description,'') like :description) 
					or (coalesce(a.materialgroupcode,'') like :parentmatgroupcode)
					or (coalesce(a.description,'') like :parentmatgroupdesc)
					) and t.recordstatus=1",
					array(':materialgroupid'=>$materialgroupid,
						':materialgroupcode'=>$materialgroupcode,
						':description'=>$description,
						':parentmatgroupcode'=>$parentmatgroupcode,
						':parentmatgroupdesc'=>$parentmatgroupdesc
				))
			->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('t.*,(select description from materialgroup z where z.materialgroupid = t.parentmatgroupid) as parentmatdesc')	
				->from('materialgroup t')
				->leftjoin('materialgroup a','a.materialgroupid = t.parentmatgroupid')
				->where("(coalesce(t.materialgroupid,'') like :materialgroupid) 
						and (coalesce(t.materialgroupcode,'') like :materialgroupcode) 
						and (coalesce(t.description,'') like :description) 
						and (coalesce(a.materialgroupcode,'') like :parentmatgroupcode)
						and (coalesce(a.description,'') like :parentmatgroupdesc)
					",
					array(':materialgroupid'=>$materialgroupid,
						':materialgroupcode'=>$materialgroupcode,
						':description'=>$description,
						':parentmatgroupcode'=>$parentmatgroupcode,
						':parentmatgroupdesc'=>$parentmatgroupdesc
				))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		}
		else {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('t.*,(select description from materialgroup z where z.materialgroupid = t.parentmatgroupid) as parentmatdesc')	
				->from('materialgroup t')
				->leftjoin('materialgroup a','a.materialgroupid = t.parentmatgroupid')
				->where("((coalesce(t.materialgroupid,'') like :materialgroupid) 
					or (coalesce(t.materialgroupcode,'') like :materialgroupcode) 
					or (coalesce(t.description,'') like :description) 
					or (coalesce(a.materialgroupcode,'') like :parentmatgroupcode)
					or (coalesce(a.description,'') like :parentmatgroupdesc)
				) and t.recordstatus=1",
					array(':materialgroupid'=>$materialgroupid,
						':materialgroupcode'=>$materialgroupcode,
						':description'=>$description,
						':parentmatgroupcode'=>$parentmatgroupcode,
						':parentmatgroupdesc'=>$parentmatgroupdesc
				))
			->order($sort.' '.$order)
			->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'materialgroupid'=>$data['materialgroupid'],
				'materialgroupcode'=>$data['materialgroupcode'],
				'description'=>$data['description'],
				'parentmatgroupid'=>$data['parentmatgroupid'],
				'parentmatgroupdesc'=>$data['parentmatdesc'],
				'isfg'=>$data['isfg'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchtrx() {
		header('Content-Type: application/json');
		$materialgroupid = GetSearchText(array('POST','Q'),'materialgroupid');
		$materialgroupcode = GetSearchText(array('POST','Q'),'materialgroupcode');
		$description = GetSearchText(array('POST','Q'),'description');
		$parentmatgroupcode = GetSearchText(array('POST','Q'),'parentmatgroupcode');
		$parentmatgroupdesc = GetSearchText(array('POST','Q'),'parentmatgroupdesc');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','materialgroupid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('materialgroup t')
			->leftjoin('materialgroup a','a.materialgroupid = t.parentmatgroupid')
			->where("((t.materialgroupcode like :materialgroupcode) or (t.description like :description) or (a.materialgroupcode like :parentmatgroupcode)
			or (a.description like :parentmatgroupdesc))	 
			and t.isfg = 0 and t.recordstatus = 1",
				array(':materialgroupcode'=>$materialgroupcode,
					':description'=>$description,
					':parentmatgroupcode'=>$parentmatgroupcode,
					':parentmatgroupdesc'=>$parentmatgroupdesc
			))
		->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,(select description from materialgroup z where z.materialgroupid = t.parentmatgroupid) as parentmatdesc')	
			->from('materialgroup t')
			->leftjoin('materialgroup a','a.materialgroupid = t.parentmatgroupid')
			->where('((t.materialgroupcode like :materialgroupcode) or (t.description like :description) or (a.materialgroupcode like :parentmatgroupcode)
			or (a.description like :parentmatgroupdesc)) 
			and t.isfg = 0 and t.recordstatus = 1',
				array(':materialgroupcode'=>$materialgroupcode,
					':description'=>$description,
					':parentmatgroupcode'=>$parentmatgroupcode,
					':parentmatgroupdesc'=>$parentmatgroupdesc
			))
		->order($sort.' '.$order)
		->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'materialgroupid'=>$data['materialgroupid'],
				'materialgroupcode'=>$data['materialgroupcode'],
				'description'=>$data['description'],
				'parentmatgroupid'=>$data['parentmatgroupid'],
				'parentmatgroupdesc'=>$data['parentmatdesc'],
				'isfg'=>$data['isfg'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchfg() {
		header('Content-Type: application/json');
		$materialgroupid = GetSearchText(array('POST','Q'),'materialgroupid');
		$materialgroupcode = GetSearchText(array('POST','Q'),'materialgroupcode');
		$description = GetSearchText(array('POST','Q'),'description');
		$parentmatgroupcode = GetSearchText(array('POST','Q'),'parentmatgroupcode');
		$parentmatgroupdesc = GetSearchText(array('POST','Q'),'parentmatgroupdesc');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','materialgroupid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('materialgroup t')
			->leftjoin('materialgroup a','a.materialgroupid = t.parentmatgroupid')
			->where('((materialgroupcode like :materialgroupcode) or (t.description like :description) or (a.materialgroupcode like :parentmatgroupcode)
			or (a.description like :parentmatgroupdesc)) 
			and t.isfg = 1 and t.recordstatus = 1',
				array(':materialgroupcode'=>$materialgroupcode,
					':description'=>$description,
					':parentmatgroupcode'=>$parentmatgroupcode,
					':parentmatgroupdesc'=>$parentmatgroupdesc
			))
		->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,(select description from materialgroup z where z.materialgroupid = t.parentmatgroupid) as parentmatdesc')	
			->from('materialgroup t')
			->leftjoin('materialgroup a','a.materialgroupid = t.parentmatgroupid')
			->where('((materialgroupcode like :materialgroupcode) or (t.description like :description) or (a.materialgroupcode like :parentmatgroupcode)
			or (a.description like :parentmatgroupdesc)) 
			and t.isfg = 1 and t.recordstatus = 1',
				array(':materialgroupcode'=>$materialgroupcode,
					':description'=>$description,
					':parentmatgroupcode'=>$parentmatgroupcode,
					':parentmatgroupdesc'=>$parentmatgroupdesc
			))
		->order($sort.' '.$order)
		->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'materialgroupid'=>$data['materialgroupid'],
				'materialgroupcode'=>$data['materialgroupcode'],
				'description'=>$data['description'],
				'parentmatgroupid'=>$data['parentmatgroupid'],
				'parentmatgroupdesc'=>$data['parentmatdesc'],
				'isfg'=>$data['isfg'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertmaterialgroup(:vmaterialgroupcode,:vparentmatgroupid,:vdescription,:visfg,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatematerialgroup(:vid,:vmaterialgroupcode,:vparentmatgroupid,:vdescription,:visfg,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vmaterialgroupcode',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vparentmatgroupid',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':visfg',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-materialgroup"]["name"]);
		if (move_uploaded_file($_FILES["file-materialgroup"]["tmp_name"], $target_file)) {
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
					$materialgroupcode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$parentmatgroup = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$parentid = Yii::app()->db->createCommand("select materialgroupid from materialgroup where materialgroupcode = '".$parentmatgroup."'")->queryScalar();
					$isfg = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$this->ModifyData($connection,array($id,$materialgroupcode,$description,$parentid,$isfg,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['materialgroupid'])?$_POST['materialgroupid']:''),$_POST['materialgroupcode'],$_POST['description'],$_POST['parentmatgroupid'],$_POST['isfg'],$_POST['recordstatus']));
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
				$sql = 'call Purgematerialgroup(:vid,:vdatauser)';
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
		$this->dataprint['materialgroupcode'] = GetSearchText(array('GET'),'materialgroupcode');
		$this->dataprint['description'] = GetSearchText(array('GET'),'description');
		$this->dataprint['parentmatgroupcode'] = GetSearchText(array('GET'),'parentmatgroupcode');
		$this->dataprint['parentmatgroupdesc'] = GetSearchText(array('GET'),'parentmatgroupdesc');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'materialgroupid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlematerialgroupcode'] = GetCatalog('materialgroupcode');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titleparentmatgroupcode'] = GetCatalog('parentmatgroupcode');
		$this->dataprint['titleparentmatgroupdesc'] = GetCatalog('parentmatgroupdesc');
		$this->dataprint['titlefg'] = GetCatalog('isfg');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}