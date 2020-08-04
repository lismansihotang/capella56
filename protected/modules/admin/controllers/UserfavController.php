<?php
class UserfavController extends Controller {
	public $menuname = 'userfav';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
			if (isset($_POST['isNewRecord'])) {
				$sql = 'call Insertuserfav(:vuseraccessid,:vmenuaccessid,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else {
				$sql = 'call Updateuserfav(:vid,:vuseraccessid,:vmenuaccessid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['userfavid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['userfavid']);
			}
			$command->bindvalue(':vuseraccessid',$_POST['useraccessid'],PDO::PARAM_STR);
			$command->bindvalue(':vmenuaccessid',$_POST['menuaccessid'],PDO::PARAM_STR);
			$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
			$command->execute();
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
				$sql = 'call Purgeuserfav(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
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
	public function search() {
		header("Content-Type: application/json");
		$userfavid = GetSearchText(array('POST','Q'),'userfavid');
		$useraccessid = GetSearchText(array('POST','Q'),'useraccessid');
		$menuaccessid = GetSearchText(array('POST','Q'),'menuaccessid');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','userfavid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('userfav t')
			->join('useraccess p', 'p.useraccessid=t.useraccessid')
			->join('menuaccess q', 'q.menuaccessid=t.menuaccessid')
			->where('(p.username like :useraccessid) or (q.menuname like :menuaccessid)',
				array(':useraccessid'=>$useraccessid,':menuaccessid'=>$menuaccessid))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select()	
			->from('userfav t')
			->join('useraccess p', 'p.useraccessid=t.useraccessid')
			->join('menuaccess q', 'q.menuaccessid=t.menuaccessid')
			->where('(p.username like :useraccessid) or (q.menuname like :menuaccessid)',
				array(':useraccessid'=>$useraccessid,':menuaccessid'=>$menuaccessid))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'userfavid'=>$data['userfavid'],
				'useraccessid'=>$data['useraccessid'],
				'username'=>$data['username'],
				'menuaccessid'=>$data['menuaccessid'],
				'menuname'=>$data['menuname'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionDownPDF() {
	  parent::actionDownload();
	  $sql = "select useraccessid,menuaccessid
				from userfav a ";
		$userfavid = GetSearchText(array('GET'),'userfavid');
		$useraccessid = GetSearchText(array('GET'),'useraccessid');
		$menuaccessid = GetSearchText(array('GET'),'menuaccessid');		
		$sql .= " where coalesce(a.userfavid,'') like '".$userfavid."' 
			and coalesce(a.useraccessid,'') like '".$useraccessid."'
			and coalesce(a.menuaccessid,'') like '".$menuaccessid."'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.userfavid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('userfav');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L');
		$this->pdf->colheader = array(GetCatalog('useraccessid'),
                GetCatalog('menuaccessid'));
		$this->pdf->setwidths(array(40,40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['useraccessid'],$row1['menuaccessid']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		parent::actionDownXls();
		$sql = "select useraccessid,menuaccessid
				from userfav a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.userfavid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$i=1;
		$this->phpExcel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0,1,GetCatalog('useraccessid'))
		->setCellValueByColumnAndRow(1,1,GetCatalog('menuaccessid'));
		foreach($dataReader as $row1)
		{
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['useraccessid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['menuaccessid']);
			$i+=1;
		}
		unset($this->phpExcel);
	}
}
