<?php
class MenuauthController extends Controller {
	public $menuname = 'menuauth';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexGroupmenuauth() {
		if(isset($_GET['grid']))
			echo $this->actionsearchgroupmenuauth();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$menuauthid = GetSearchText(array('POST','Q'),'menuauthid');
		$menuobject = GetSearchText(array('POST','Q'),'menuobject');
		$menuvalueid = GetSearchText(array('POST','Q'),'menuvalueid');
		$groupname = GetSearchText(array('POST','Q'),'groupname');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','menuauthid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('menuauth t')
			->where('(menuauthid like :menuauthid) and (menuobject like :menuobject) '.
			(($groupname != '%%')?" and menuauthid in (
				select distinct a.menuauthid 
				from groupmenuauth a 
				join groupaccess b on b.groupaccessid = a.groupaccessid 
				where b.groupname like '".$groupname."' 
			)":'').
			(($menuvalueid != '%%')?" and menuauthid in (
				select distinct a.menuauthid 
				from groupmenuauth a 
				where a.menuvalueid like '".$menuvalueid."' 
			)":''),
					array(':menuauthid'=>$menuauthid,':menuobject'=>$menuobject))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select("t.*,(select ifnull(count(1),0) from groupmenuauth a where a.menuauthid = t.menuauthid) as jumlah")	
			->from('menuauth t')
			->where('(menuauthid like :menuauthid) and (menuobject like :menuobject) '.
			(($groupname != '%%')?" and menuauthid in (
				select distinct a.menuauthid 
				from groupmenuauth a 
				join groupaccess b on b.groupaccessid = a.groupaccessid 
				where b.groupname like '".$groupname."' 
			)":'').
			(($menuvalueid != '%%')?" and menuauthid in (
				select distinct a.menuauthid 
				from groupmenuauth a 
				where a.menuvalueid like '".$menuvalueid."' 
			)":''),
					array(':menuauthid'=>$menuauthid,':menuobject'=>$menuobject))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'menuauthid'=>$data['menuauthid'],
				'menuobject'=>$data['menuobject'],
				'jumlah'=>$data['jumlah'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionsearchgroupmenuauth() {
		header("Content-Type: application/json");
		$id = 0;	
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
		}
		else if (isset($_GET['id'])) {
			$id = $_GET['id'];
		}
		else 
		if (isset($_POST['menuauthid'])) {
			$id = $_POST['menuauthid'];
		}
		else 
		if (isset($_GET['menuauthid'])) {
			$id = $_GET['menuauthid'];
		}
		$groupname = GetSearchText(array('POST'),'groupname');
		$menuvalueid = GetSearchText(array('POST'),'menuvalueid');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','t.groupmenuauthid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('groupmenuauth t')
			->leftjoin('groupaccess p','t.groupaccessid=p.groupaccessid')
			->leftjoin('menuauth q','t.menuauthid=q.menuauthid')
			->where("q.menuauthid = ".$id." and t.menuvalueid like '".$menuvalueid."' and p.groupname like '".$groupname."'")
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,p.groupname,q.menuobject')	
			->from('groupmenuauth t')
			->leftjoin('groupaccess p','t.groupaccessid=p.groupaccessid')
			->leftjoin('menuauth q','t.menuauthid=q.menuauthid')
			->where("q.menuauthid = ".$id." and t.menuvalueid like '".$menuvalueid."' and p.groupname like '".$groupname."'")
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'groupmenuauthid'=>$data['groupmenuauthid'],
				'groupaccessid'=>$data['groupaccessid'],
				'groupname'=>$data['groupname'],
				'menuauthid'=>$data['menuauthid'],
				'menuobject'=>$data['menuobject'],
				'menuvalueid'=>$data['menuvalueid'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionGetData() {
		$id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'menuauthid' => $id
		));
	}
	private function ModifyData($connection,$arraydata) {
		$id = (int)$arraydata[0];
		$sql = 'call Modifmenuauth(:vid,:vmenuobject,:vrecordstatus,:vdatauser)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vmenuobject',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	private function ModifyDataGroupmenuauth($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertgroupmenuauth(:vgroupaccessid,:vmenuauthid,:vmenuvalueid,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updategroupmenuauth(:vid,:vgroupaccessid,:vmenuauthid,:vmenuvalueid,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vgroupaccessid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vmenuauthid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vmenuvalueid',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-menuauth"]["name"]);
		if (move_uploaded_file($_FILES["file-menuauth"]["tmp_name"], $target_file)) {
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
					$menuobject = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData(array($id,$menuobject,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['menuauth-menuauthid'])?$_POST['menuauth-menuauthid']:''),
				$_POST['menuauth-menuobject'],
				isset($_POST['menuauth-recordstatus'])?($_POST['menuauth-recordstatus']=="on")?1:0:0));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}	
	}
	public function actionSaveGroupmenuauth() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDatagroupmenuauth($connection,array((isset($_POST['groupmenuauthid'])?$_POST['groupmenuauthid']:''),$_POST['groupaccessid'],$_POST['menuauthid'],$_POST['menuvalueid']));
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
				$sql = 'call Purgemenuauth(:vid,:vdatauser)';
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
	public function actionPurgeGroupmenuauth() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgegroupmenuauth(:vid,:vdatauser)';
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
		$this->dataprint['titleid'] = GetCatalog('menuauthid');
		$this->dataprint['titlemenuobject'] = GetCatalog('menuobject');
		$this->dataprint['titlegroupname'] = GetCatalog('groupname');
		$this->dataprint['titlemenuvalue'] = GetCatalog('menuvalueid');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['menuobject'] = GetSearchText(array('GET'),'menuobject');
    $this->dataprint['groupname'] = GetSearchText(array('GET'),'groupname');
  }
}
