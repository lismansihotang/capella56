<?php
class SplettertypeController extends Controller {
	public $menuname = 'splettertype';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertsplettertype(:vsplettername,:vdescription,:vvalidperiod,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updatesplettertype(:vid,:vsplettername,:vdescription,:vvalidperiod,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vsplettername',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vvalidperiod',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionSave() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['splettertypeid'])?$_POST['splettertypeid']:''),$_POST['splettername'],$_POST['description'],
				$_POST['validperiod'],$_POST['recordstatus']));			
			$transaction->commit();
			getmessage(false,getcatalog('insertsuccess'));
		}
		catch (Exception $e) {
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
				$sql = 'call Purgesplettertype(:vid,:vcreatedby)';
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
		$splettertypeid = isset ($_POST['splettertypeid']) ? $_POST['splettertypeid'] : '';
		$splettername = isset ($_POST['splettername']) ? $_POST['splettername'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$validperiod = isset ($_POST['validperiod']) ? $_POST['validperiod'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$splettertypeid = isset ($_GET['q']) ? $_GET['q'] : $splettertypeid;
		$splettername = isset ($_GET['q']) ? $_GET['q'] : $splettername;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$validperiod = isset ($_GET['q']) ? $_GET['q'] : $validperiod;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.splettertypeid';
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
				->from('splettertype t')
				->where('(splettername like :splettername) and
						(description like :description) and
						(validperiod like :validperiod)',
						array(':splettername'=>'%'.$splettername.'%',
						':description'=>'%'.$description.'%',
						':validperiod'=>'%'.$validperiod.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('splettertype t')
				->where('((splettername like :splettername) or
						(description like :description) or
						(validperiod like :validperiod)) and t.recordstatus=1',
						array(':splettername'=>'%'.$splettername.'%',
						':description'=>'%'.$description.'%',
						':validperiod'=>'%'.$validperiod.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('splettertype t')
				->where('(splettername like :splettername) and
						(description like :description) and
						(validperiod like :validperiod)',
						array(':splettername'=>'%'.$splettername.'%',
						':description'=>'%'.$description.'%',
						':validperiod'=>'%'.$validperiod.'%'))
				->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('splettertype t')
				->where('((splettername like :splettername) or
						(description like :description) or
						(validperiod like :validperiod)) and t.recordstatus=1',
						array(':splettername'=>'%'.$splettername.'%',
						':description'=>'%'.$description.'%',
						':validperiod'=>'%'.$validperiod.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'splettertypeid'=>$data['splettertypeid'],
				'splettername'=>$data['splettername'],
				'description'=>$data['description'],
				'validperiod'=>$data['validperiod'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select splettertypeid,splettername,description,validperiod,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from splettertype a ";
		$splettertypeid = filter_input(INPUT_GET,'splettertypeid');
		$splettername = filter_input(INPUT_GET,'splettername');
		$description = filter_input(INPUT_GET,'description');
		$validperiod = filter_input(INPUT_GET,'validperiod');
		$sql .= " where coalesce(a.splettertypeid,'') like '%".$splettertypeid."%' 
			and coalesce(a.splettername,'') like '%".$splettername."%'
			and coalesce(a.description,'') like '%".$description."%'
			and coalesce(a.validperiod,'') like '%".$validperiod."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.splettertypeid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by splettername asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('splettertype');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L','L');
		$this->pdf->colheader = array(getCatalog('splettertypeid'),
																	getCatalog('splettername'),
																	getCatalog('description'),
																	getCatalog('validperiod'),
																	getCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,63,63,30,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['splettertypeid'],$row1['splettername'],$row1['description'],$row1['validperiod'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='splettertype';
		parent::actionDownxls();
		$sql = "select splettertypeid,splettername,description,validperiod,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from splettertype a ";
		$splettertypeid = filter_input(INPUT_GET,'splettertypeid');
		$splettername = filter_input(INPUT_GET,'splettername');
		$description = filter_input(INPUT_GET,'description');
		$validperiod = filter_input(INPUT_GET,'validperiod');
		$sql .= " where coalesce(a.splettertypeid,'') like '%".$splettertypeid."%' 
			and coalesce(a.splettername,'') like '%".$splettername."%'
			and coalesce(a.description,'') like '%".$description."%'
			and coalesce(a.validperiod,'') like '%".$validperiod."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.splettertypeid in (".$_GET['id'].")";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['splettertypeid'])
				->setCellValueByColumnAndRow(1,$i,$row1['splettername'])				
				->setCellValueByColumnAndRow(2,$i,$row1['description'])
				->setCellValueByColumnAndRow(3,$i,$row1['validperiod'])
				->setCellValueByColumnAndRow(4,$i,$row1['recordstatus']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}