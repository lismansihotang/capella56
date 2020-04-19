<?php
class TaxwageprogressifController extends Controller {
	public $menuname = 'taxwageprogressif';
	public function actionIndex() {
		parent::actionIndex();
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
		try
		{
			if (isset($_POST['isNewRecord'])) {
				$sql = 'call Inserttaxwageprogressif(:vdescription,:vminvalue,:vmaxvalue,:vvaluepercent,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else {
				$sql = 'call Updatetaxwageprogressif(:vid,:vdescription,:vminvalue,:vmaxvalue,:vvaluepercent,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['taxwageprogressifid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['taxwageprogressifid']);
			}
			$command->bindvalue(':vdescription',$_POST['description'],PDO::PARAM_STR);
			$command->bindvalue(':vminvalue',$_POST['minvalue'],PDO::PARAM_STR);
			$command->bindvalue(':vmaxvalue',$_POST['maxvalue'],PDO::PARAM_STR);
			$command->bindvalue(':vvaluepercent',$_POST['valuepercent'],PDO::PARAM_STR);
			$command->bindvalue(':vrecordstatus',$_POST['recordstatus'],PDO::PARAM_STR);
			$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
			$command->execute();
			$transaction->commit();
			getmessage(false,getcatalog('insertsuccess'));
		}
		catch (Exception $e)
		{
			$transaction->rollBack();
			getmessage(true,$e->getMessage());
		}
	}
	public function actionPurge() {
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgetaxwageprogressif(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				getmessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollback();
				getmessage(true,$e->getMessage());
			}
		}
		else {
			getmessage(true,getcatalog('chooseone'));
		}
	}
	public function search() {
		header('Content-Type: application/json');
		$taxwageprogressifid = isset ($_POST['taxwageprogressifid']) ? $_POST['taxwageprogressifid'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$taxwageprogressifid = isset ($_GET['q']) ? $_GET['q'] : $taxwageprogressifid;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.taxwageprogressifid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
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
				->from('taxwageprogressif t')
				->where('(description like :description)',
					array(':description'=>'%'.$description.'%'))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('taxwageprogressif t')
				->where('((description like :description) or 
					(minvalue like :minvalue) or 
					(`maxvalue` like :maxvalue) or 
					(valuepercent like :valuepercent)) and t.recordstatus=1',
						array(':description'=>'%'.$description.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo']))
		{
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('taxwageprogressif t')
				->where('(description like :description)',
					array(':description'=>'%'.$description.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('taxwageprogressif t')
				->where('((description like :description)) and t.recordstatus=1',
					array(':description'=>'%'.$description.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'taxwageprogressifid'=>$data['taxwageprogressifid'],
				'description'=>$data['description'],
				'minvalue'=>$data['minvalue'],
				'maxvalue'=>$data['maxvalue'],
				'valuepercent'=>$data['valuepercent'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	
	public function actionDownPDF()
	{
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select taxwageprogressifid,description,minvalue,`maxvalue`,valuepercent,
						case when recordstatus =1 then 'Yes' else 'No' end as recordstatus
						from taxwageprogressif a ";
		$taxwageprogressifid = filter_input(INPUT_GET,'taxwageprogressifid');
		$description = filter_input(INPUT_GET,'description');
		$sql .= " where coalesce(a.taxwageprogressifid,'') like '%".$taxwageprogressifid."%' 
			and coalesce(a.description,'') like '%".$description."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.taxwageprogressifid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('taxwageprogressif');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L','L','L');
		$this->pdf->colheader = array(getCatalog('taxwageprogressifid'),
																	getCatalog('description'),
																	getCatalog('minvalue'),
																	getCatalog('maxvalue'),
																	getCatalog('valuepercent'),
																	getCatalog('recordstatus'));
		$this->pdf->setwidths(array(10,35,45,60,25,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['taxwageprogressifid'],$row1['description'],$row1['minvalue'],$row1['maxvalue'],$row1['valuepercent'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='taxwageprogressif';
		parent::actionDownxls();
		$sql = "select taxwageprogressifid,description,minvalue,`maxvalue`,valuepercent,
						case when recordstatus =1 then 'Yes' else 'No' end as recordstatus
						from taxwageprogressif a ";
		$taxwageprogressifid = filter_input(INPUT_GET,'taxwageprogressifid');
		$description = filter_input(INPUT_GET,'description');
		$sql .= " where coalesce(a.taxwageprogressifid,'') like '%".$taxwageprogressifid."%' 
			and coalesce(a.description,'') like '%".$description."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.taxwageprogressifid in (".$_GET['id'].")";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['taxwageprogressifid'])
				->setCellValueByColumnAndRow(1,$i,$row1['description'])			
				->setCellValueByColumnAndRow(2,$i,$row1['minvalue'])
				->setCellValueByColumnAndRow(3,$i,$row1['maxvalue'])
				->setCellValueByColumnAndRow(4,$i,$row1['valuepercent'])
				->setCellValueByColumnAndRow(5,$i,$row1['recordstatus']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}