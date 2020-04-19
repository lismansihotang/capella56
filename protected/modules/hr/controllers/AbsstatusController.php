<?php
class AbsstatusController extends Controller {
	public $menuname = 'absstatus';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		// search 
		$absstatusid = isset ($_POST['absstatusid']) ? $_POST['absstatusid'] : '';
		$shortstat = isset ($_POST['shortstat']) ? $_POST['shortstat'] : '';
		$longstat = isset ($_POST['longstat']) ? $_POST['longstat'] : '';
		$absstatusid = isset ($_GET['q']) ? $_GET['q'] : $absstatusid;
		$shortstat = isset ($_GET['q']) ? $_GET['q'] : $shortstat;
		$longstat = isset ($_GET['q']) ? $_GET['q'] : $longstat;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.absstatusid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo']))
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('absstatus t')
				->where('(shortstat like :shortstat) and 
					(longstat like :longstat)',
						array(':shortstat'=>'%'.$shortstat.'%',
							':longstat'=>'%'.$longstat.'%',
							))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('absstatus t')
				->where('((shortstat like :shortstat) or 
					(longstat like :longstat)) and t.recordstatus=1',
						array(':shortstat'=>'%'.$shortstat.'%',
						':longstat'=>'%'.$longstat.'%',
						))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo']))
		{
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('absstatus t')
				->where('(shortstat like :shortstat) and 
					(longstat like :longstat)',
						array(':shortstat'=>'%'.$shortstat.'%',
							':longstat'=>'%'.$longstat.'%',
							))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('absstatus t')
				->where('((shortstat like :shortstat) or 
					(longstat like :longstat)) and t.recordstatus=1',
						array(':shortstat'=>'%'.$shortstat.'%',
							':longstat'=>'%'.$longstat.'%',
							))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'absstatusid'=>$data['absstatusid'],
				'shortstat'=>$data['shortstat'],
				'longstat'=>$data['longstat'],
				'isin'=>$data['isin'],
				'priority'=>$data['priority'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertabsstatus(:vshortstat,:vlongstat,:visin,:vpriority,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updateabsstatus(:vid,:vshortstat,:vlongstat,:visin,:vpriority,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vshortstat',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vlongstat',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':visin',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vpriority',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-absstatus"]["name"]);
		if (move_uploaded_file($_FILES["file-absstatus"]["tmp_name"], $target_file)) {
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
					$shortstat = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$longstat = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$isin = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$priority = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$this->ModifyData($connection,array($id,$shortstat,$longstat,$isin,$priority,$recordstatus));
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
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['absstatusid'])?$_POST['absstatusid']:''),$_POST['shortstat'],$_POST['longstat'],
				$_POST['isin'],$_POST['priority'],$_POST['recordstatus']));
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
			try
			{
				$sql = 'call Purgeabsstatus(:vid,:vcreatedby)';
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
		else
		{
			getmessage(true,getcatalog('chooseone'));
		}
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select absstatusid,shortstat,longstat,
						case when isin = 1 then 'Yes' else 'No' end as isin,priority,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from absstatus a ";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . "where a.absstatusid in (".$_GET['id'].")";
		}
		else
		{
			$sql = $sql . "order by shortstat asc, longstat asc ";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=getCatalog('absstatus');
		$this->pdf->AddPage('P');
		//masukkan posisi judul
		$this->pdf->colalign = array('L','L','L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(getCatalog('absstatusid'),
																	getCatalog('shortstat'),
																	getCatalog('longstat'),
																	getCatalog('isin'),
																	getCatalog('priority'),
																	getCatalog('recordstatus'));
		$this->pdf->setwidths(array(10,35,80,20,20,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['absstatusid'],$row1['shortstat'],$row1['longstat'],$row1['isin'],$row1['priority'],$row1['recordstatus']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	public function actionDownxls()
	{
		$this->menuname='absstatus';
		parent::actionDownxls();
		$sql = "select absstatusid,shortstat,longstat,
						case when isin = 1 then 'Yes' else 'No' end as isin,priority,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from absstatus a ";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . "where a.absstatusid in (".$_GET['id'].")";
		}
		else
		{
			$sql = $sql . "order by shortstat asc, longstat asc ";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		
		foreach($dataReader as $row1)
		{
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['absstatusid'])
				->setCellValueByColumnAndRow(1,$i,$row1['shortstat'])							
				->setCellValueByColumnAndRow(2,$i,$row1['longstat'])
				->setCellValueByColumnAndRow(3,$i,$row1['isin'])
				->setCellValueByColumnAndRow(4,$i,$row1['priority'])							
				->setCellValueByColumnAndRow(5,$i,$row1['recordstatus']);
			$i++;
		}
		
		$this->getFooterXLS($this->phpExcel);
	}
	
	
	/*public function actionDownxls()
	{
		parent::actionDownload();
		$sql = "select shortstat,longstat,isin,priority,recordstatus
				from absstatus a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.absstatusid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		 $excel=Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
		$i=1;
		$excel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0,1,getCatalog('shortstat'))
                ->setCellValueByColumnAndRow(1,1,getCatalog('longstat'))
                ->setCellValueByColumnAndRow(2,1,getCatalog('isin'))
                ->setCellValueByColumnAndRow(3,1,getCatalog('priority'))
                ->setCellValueByColumnAndRow(4,1,getCatalog('recordstatus'))
                ;		foreach($dataReader as $row1)
		{
			  $excel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['shortstat'])
                                ->setCellValueByColumnAndRow(1, $i+1, $row1['longstat'])
                                ->setCellValueByColumnAndRow(2, $i+1, $row1['isin'])
                                ->setCellValueByColumnAndRow(3, $i+1, $row1['priority'])
                                ->setCellValueByColumnAndRow(4, $i+1, $row1['recordstatus'])
                                ;		$i+=1;
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="absstatus.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$objWriter->save('php://output');
		unset($excel);
	}
	*/

}
