<?php
class MenuaccessController extends Controller {
	public $menuname = 'menuaccess';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$menuaccessid = GetSearchText(array('POST','Q'),'menuaccessid');
		$menuname = GetSearchText(array('POST','Q'),'menuname');
		$description = GetSearchText(array('POST','Q'),'description');
		$menuurl = GetSearchText(array('POST','Q'),'menuurl');
		$menuicon = GetSearchText(array('POST','Q'),'menuicon');
		$parentname = GetSearchText(array('POST','Q'),'parentname');
		$modulename = GetSearchText(array('POST','Q'),'modulename');
		$sortorder = GetSearchText(array('POST','Q'),'sortorder');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','menuaccessid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->selectdistinct('count(1) as total')	
				->from('menuaccess t')
				->leftjoin('menuaccess p', 't.parentid=p.menuaccessid')
				->join('modules m', 'm.moduleid=t.moduleid')
				->where("(coalesce(t.menuname,'') like :menuname) 
					and (coalesce(t.menuaccessid,'') like :menuaccessid) 
					and (coalesce(t.description,'') like :description) 
					and (coalesce(t.menuurl,'') like :menuurl) 
					and (coalesce(t.menuicon,'') like :menuicon) 
					and (coalesce(p.menuname,'') like :parentname) 
					and (coalesce(m.modulename,'') like :modulename)",
						array(':menuaccessid'=>$menuaccessid,':menuname'=>$menuname,':description'=>$description,':menuurl'=>$menuurl,':menuicon'=>$menuicon,':parentname'=>$parentname,':modulename'=>$modulename))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->selectdistinct('count(1) as total')	
				->from('menuaccess t')
				->leftjoin('menuaccess p', 't.parentid=p.menuaccessid')
				->join('modules m', 'm.moduleid=t.moduleid')
				->where("((coalesce(t.menuname,'') like :menuname) 
					or (coalesce(t.menuaccessid,'') like :menuaccessid) 
					or (coalesce(t.description,'') like :description) 
					or (coalesce(t.menuurl,'') like :menuurl) 
					or (coalesce(t.menuicon,'') like :menuicon) 
					or (coalesce(p.menuname,'') like :parentname) 
					or (coalesce(m.modulename,'') like :modulename)) and t.recordstatus = 1",
						array(':menuaccessid'=>$menuaccessid,':menuname'=>$menuname,':description'=>$description,':menuurl'=>$menuurl,':menuicon'=>$menuicon,':parentname'=>$parentname,':modulename'=>$modulename))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->selectdistinct('t.*,p.description as parentdesc,m.modulename')						
				->from('menuaccess t')
				->leftjoin('menuaccess p', 't.parentid=p.menuaccessid')
				->join('modules m', 'm.moduleid=t.moduleid')
				->where("(coalesce(t.menuname,'') like :menuname) 
					and (coalesce(t.menuaccessid,'') like :menuaccessid) 
					and (coalesce(t.description,'') like :description) 
					and (coalesce(t.menuurl,'') like :menuurl) 
					and (coalesce(t.menuicon,'') like :menuicon) 
					and (coalesce(p.menuname,'') like :parentname) 
					and (coalesce(m.modulename,'') like :modulename)",
						array(':menuaccessid'=>$menuaccessid,':menuname'=>$menuname,':description'=>$description,':menuurl'=>$menuurl,':menuicon'=>$menuicon,':parentname'=>$parentname,':modulename'=>$modulename))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->selectdistinct('t.*,p.description as parentdesc,m.modulename')			
				->from('menuaccess t')
				->leftjoin('menuaccess p', 't.parentid=p.menuaccessid')
				->join('modules m', 'm.moduleid=t.moduleid')
				->where("((coalesce(t.menuname,'') like :menuname) 
					or (coalesce(t.menuaccessid,'') like :menuaccessid) 
					or (coalesce(t.description,'') like :description) 
					or (coalesce(t.menuurl,'') like :menuurl) 
					or (coalesce(t.menuicon,'') like :menuicon) 
					or (coalesce(p.menuname,'') like :parentname) 
					or (coalesce(m.modulename,'') like :modulename)) 
					and t.recordstatus = 1",
						array(':menuaccessid'=>$menuaccessid,':menuname'=>$menuname,':description'=>$description,':menuurl'=>$menuurl,':menuicon'=>$menuicon,':parentname'=>$parentname,':modulename'=>$modulename))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'menuaccessid'=>$data['menuaccessid'],
				'menuname'=>$data['menuname'],
				'description'=>$data['description'],
				'menuurl'=>$data['menuurl'],
				'menuicon'=>$data['menuicon'],
				'parentid'=>$data['parentid'],
				'parentdesc'=>$data['parentdesc'], 
				'moduleid'=>$data['moduleid'],
				'modulename'=>$data['modulename'],
				'sortorder'=>$data['sortorder'],
				'viewcode'=>$data['viewcode'],
				'menudep'=>$data['menudep'],
				'controllercode'=>$data['controllercode'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertmenuaccess(:vmenuname,:vdescription,:vmenuurl,:vmenuicon,:vparentid,:vmoduleid,:vsortorder,:vviewcode,:vcontrollercode,:vmenudep,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatemenuaccess(:vid,:vmenuname,:vdescription,:vmenuurl,:vmenuicon,:vparentid,:vmoduleid,:vsortorder,:vviewcode,:vcontrollercode,:vmenudep,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vmenuname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vmenuurl',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vmenuicon',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vparentid',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vmoduleid',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vsortorder',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vviewcode',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vcontrollercode',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vmenudep',$arraydata[10],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[11],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-menuaccess"]["name"]);
		if (move_uploaded_file($_FILES["file-menuaccess"]["tmp_name"], $target_file)) {
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
					$menuname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$menuurl = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$menuicon = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$parentname = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$parentid = Yii::app()->db->createCommand("select menuaccessid from menuaccess where menuname = '".$parentname."'")->queryScalar();
					$modulename = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$moduleid = Yii::app()->db->createCommand("select moduleid from modules where modulename = '".$modulename."'")->queryScalar();
					$sortorder = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$viewcode = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$controllercode = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
					$menudep= $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
					$this->ModifyData($connection,array($id,$menuname,$description,$menuurl,$menuicon,$parentid,$moduleid,$sortorder,$viewcode,$controllercode,$menudep,$recordstatus));
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
			CreateCode($_POST['menuname']);
			$this->ModifyData($connection,array((isset($_POST['menuaccessid'])?$_POST['menuaccessid']:''),$_POST['menuname'],$_POST['description'],$_POST['menuurl'],
				$_POST['menuicon'],$_POST['parentid'],$_POST['moduleid'],$_POST['sortorder'],$_POST['viewcode'],$_POST['controllercode'],$_POST['menudep'],$_POST['recordstatus']));
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
				$sql = 'call Purgemenuaccess(:vid,:vdatauser)';
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
		$this->dataprint['titleid'] = GetCatalog('menuaccessid');
		$this->dataprint['titlemenuname'] = GetCatalog('menuname');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titlemenuurl'] = GetCatalog('menuurl');
		$this->dataprint['titlemenuicon'] = GetCatalog('menuicon');
		$this->dataprint['titleparentname'] = GetCatalog('parentname');
		$this->dataprint['titlemodulename'] = GetCatalog('modulename');
		$this->dataprint['titlesortorder'] = GetCatalog('sortorder');
		$this->dataprint['url'] = Yii::app()->params['baseUrl'];
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['menuname'] = GetSearchText(array('GET'),'menuname');
    $this->dataprint['description'] = GetSearchText(array('GET'),'description');
    $this->dataprint['menuurl'] = GetSearchText(array('GET'),'menuurl');
    $this->dataprint['menuicon'] = GetSearchText(array('GET'),'menuicon');
    $this->dataprint['parentname'] = GetSearchText(array('GET'),'parentname');
    $this->dataprint['modulename'] = GetSearchText(array('GET'),'modulename');
    $this->dataprint['sortorder'] = GetSearchText(array('GET'),'sortorder');
  }
}
