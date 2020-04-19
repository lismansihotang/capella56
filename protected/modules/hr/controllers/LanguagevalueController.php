<?php
class LanguagevalueController extends Controller {
	public $menuname = 'languagevalue';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionSave() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			if (isset($_POST['isNewRecord'])) {
				$sql = 'call Insertlanguagevalue(:vlanguagevaluename,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else {
				$sql = 'call Updatelanguagevalue(:vid,:vlanguagevaluename,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['languagevalueid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['languagevalueid']);
			}
			$command->bindvalue(':vlanguagevaluename',$_POST['languagevaluename'],PDO::PARAM_STR);
			$command->bindvalue(':vrecordstatus',$_POST['recordstatus'],PDO::PARAM_STR);
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
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgelanguagevalue(:vid,:vcreatedby)';
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
			getmessage(true,getcatalog('chooseone'));
		}
	}
	public function search() {
		header('Content-Type: application/json');
		$languagevalueid = isset ($_POST['languagevalueid']) ? $_POST['languagevalueid'] : '';
		$languagevaluename = isset ($_POST['languagevaluename']) ? $_POST['languagevaluename'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$languagevalueid = isset ($_GET['q']) ? $_GET['q'] : $languagevalueid;
		$languagevaluename = isset ($_GET['q']) ? $_GET['q'] : $languagevaluename;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.languagevalueid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('languagevalue t')
				->where('(languagevaluename like :languagevaluename)',
						array(':languagevaluename'=>'%'.$languagevaluename.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('languagevalue t')
				->where('((languagevaluename like :languagevaluename)) and t.recordstatus=1',
						array(':languagevaluename'=>'%'.$languagevaluename.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('languagevalue t')
				->where('(languagevaluename like :languagevaluename)',
						array(':languagevaluename'=>'%'.$languagevaluename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('languagevalue t')
				->where('((languagevaluename like :languagevaluename)) and t.recordstatus=1',
						array(':languagevaluename'=>'%'.$languagevaluename.'%'))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'languagevalueid'=>$data['languagevalueid'],
				'languagevaluename'=>$data['languagevaluename'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}	
	public function actionDownPDF() {
	  parent::actionDownload();
	  $sql = "select languagevalueid,languagevaluename,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from languagevalue a ";
		$languagevalueid = filter_input(INPUT_GET,'languagevalueid');
		$languagevaluename = filter_input(INPUT_GET,'languagevaluename');
		$sql .= " where coalesce(a.languagevalueid,'') like '%".$languagevalueid."%' 
			and coalesce(a.languagevaluename,'') like '%".$languagevaluename."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.languagevalueid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('languagevalue');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L');
		$this->pdf->colheader = array(getCatalog('languagevalueid'),
																	getCatalog('languagevaluename'),
																	getCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,155,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['languagevalueid'],$row1['languagevaluename'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='languagevalue';
		parent::actionDownxls();
		$sql = "select languagevalueid,languagevaluename,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from languagevalue a ";
		$languagevalueid = filter_input(INPUT_GET,'languagevalueid');
		$languagevaluename = filter_input(INPUT_GET,'languagevaluename');
		$sql .= " where coalesce(a.languagevalueid,'') like '%".$languagevalueid."%' 
			and coalesce(a.languagevaluename,'') like '%".$languagevaluename."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.languagevalueid in (".$_GET['id'].")";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['languagevalueid'])
				->setCellValueByColumnAndRow(1,$i,$row1['languagevaluename'])				
				->setCellValueByColumnAndRow(2,$i,$row1['recordstatus']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}