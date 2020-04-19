<?php
class KepletterController extends Controller {
	public $menuname = 'kepletter';
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
		try
		{
			if (isset($_POST['isNewRecord'])) {
				$sql = 'call Insertkepletter(:vcompanyid,:vnosurat,:vdateletter,vdocupload,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call Updatekepletter(:vid,:vcompanyid,:vnosurat,:vdateletter,vdocupload,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['kepletterid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['kepletterid']);
			}
			$command->bindvalue(':vcompanyid',$_POST['companyid'],PDO::PARAM_STR);
			$command->bindvalue(':vnosurat',$_POST['nosurat'],PDO::PARAM_STR);
			$command->bindvalue(':vdateletter',$_POST['dateletter'],PDO::PARAM_STR);
			$command->bindvalue(':vdocupload',$_POST['docupload'],PDO::PARAM_STR);
			$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
			$command->execute();
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
				$sql = 'call Purgekepletter(:vid,:vcreatedby)';
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
		$sexid = isset ($_POST['sexid']) ? $_POST['sexid'] : '';
		$companyname = isset ($_POST['companyname']) ? $_POST['companyname'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$sexid = isset ($_GET['q']) ? $_GET['q'] : $sexid;
		$companyname = isset ($_GET['q']) ? $_GET['q'] : $companyname;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		// pagging
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.kepletterid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('kepletter t')
				->leftjoin('company a','a.companyid = t.companyid')
				->where('(companyname like :companyname)',
						array(':companyname'=>'%'.$companyname.'%'))
				->queryScalar();
		$result['total'] = $cmd;
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('kepletter t')
				->leftjoin('company a','a.companyid = t.companyid')
				->where('(companyname like :companyname)',
						array(':companyname'=>'%'.$companyname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'kepletterid'=>$data['kepletterid'],
				'companyname'=>$data['companyname'],
				'nosurat'=>$data['nosurat'],
				'dateletter'=>$data['dateletter'],
				'docupload'=>$data['docupload'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select kepletterid,companyname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from kepletter a 
				left join company b on b.companyid = a.companyid ";
		$kepletterid = filter_input(INPUT_GET,'kepletterid');
		$companyname = filter_input(INPUT_GET,'companyname');
		$sql .= " where coalesce(a.kepletterid,'') like '%".$kepletterid."%' 
			and coalesce(b.companyname,'') like '%".$companyname."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.kepletterid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('kepletter');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L');
		$this->pdf->colheader = array(getCatalog('kepletterid'),
																	getCatalog('companyname'),
																	getCatalog('nosurat'));
		$this->pdf->setwidths(array(15,155,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['kepletterid'],$row1['companyname'],$row1['nosurat']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='kepletter';
		parent::actionDownxls();
		$sql = "select kepletterid,companyname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from kepletter a 
				left join company b on b.companyid = a.companyid ";
		$kepletterid = filter_input(INPUT_GET,'kepletterid');
		$companyname = filter_input(INPUT_GET,'companyname');
		$sql .= " where coalesce(a.kepletterid,'') like '%".$kepletterid."%' 
			and coalesce(b.companyname,'') like '%".$companyname."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.kepletterid in (".$_GET['id'].")";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['kepletterid'])
				->setCellValueByColumnAndRow(1,$i,$row1['companyname'])							
				->setCellValueByColumnAndRow(2,$i,$row1['nosurat']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}