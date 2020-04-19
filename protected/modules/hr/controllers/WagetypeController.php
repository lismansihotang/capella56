<?php
class WagetypeController extends Controller {
	public $menuname = 'wagetype';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertwagetype(:vwagename,:vispph,:vispayroll,:visprint,:vpercentage,:vmaxvalue,:vcurrencyid,:visrutin,:vpaidbycompany,:vpphbycompany,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatewagetype(:vid,:vwagename,:vispph,:vispayroll,:visprint,:vpercentage,:vmaxvalue,:vcurrencyid,:visrutin,:vpaidbycompany,:vpphbycompany,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vwagename',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vispph',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vispayroll',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':visprint',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vpercentage',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vmaxvalue',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':visrutin',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vpaidbycompany',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vpphbycompany',$arraydata[10],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[11],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-wagetype"]["name"]);
		if (move_uploaded_file($_FILES["file-wagetype"]["tmp_name"], $target_file)) {
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
					$wagename = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$ispph = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$ispayroll = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$isprint = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$percentage = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$maxvalue = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$currencyid = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$isrutin = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$paidbycompany = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
					$pphbycompany = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
					$this->ModifyData($connection,array($id,$languagename,$recordstatus));
				}
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollBack();
				GetMessage(true,$e->getMessage());
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
			$this->ModifyData($connection,array((isset($_POST['wagetypeid'])?$_POST['wagetypeid']:''),
				$_POST['wagename'],$_POST['ispph'],$_POST['ispayroll'],$_POST['isprint'],$_POST['percentage'],
				$_POST['maxvalue'],$_POST['currencyid'],$_POST['isrutin'],$_POST['paidbycompany'],$_POST['pphbycompany'],
				$_POST['recordstatus']));
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
				$sql = 'call Purgewagetype(:vid,:vcreatedby)';
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
		$wagetypeid = isset ($_POST['wagetypeid']) ? $_POST['wagetypeid'] : '';
		$wagename = isset ($_POST['wagename']) ? $_POST['wagename'] : '';
		$wagetypeid = isset ($_GET['q']) ? $_GET['q'] : $wagetypeid;
		$wagename = isset ($_GET['q']) ? $_GET['q'] : $wagename;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.wagetypeid';
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
				->from('wagetype t')
				->join('currency p','p.currencyid=t.currencyid')
				->where('(wagename like :wagename)',
					array(':wagename'=>'%'.$wagename.'%'))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('wagetype t')
				->join('currency p','p.currencyid=t.currencyid')
				->where('((wagename like :wagename)) and t.recordstatus=1',
					array(':wagename'=>'%'.$wagename.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('wagetype t')
				->join('currency p','p.currencyid=t.currencyid')
				->where('(wagename like :wagename)',
					array(':wagename'=>'%'.$wagename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('wagetype t')
				->join('currency p','p.currencyid=t.currencyid')
				->where('((wagename like :wagename)) and t.recordstatus=1',
					array(':wagename'=>'%'.$wagename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'wagetypeid'=>$data['wagetypeid'],
				'wagename'=>$data['wagename'],
				'ispph'=>$data['ispph'],
				'ispayroll'=>$data['ispayroll'],
				'isprint'=>$data['isprint'],
				'percentage'=>$data['percentage'],
				'maxvalue'=>$data['maxvalue'],
				'currencyid'=>$data['currencyid'],
				'currencyname'=>$data['currencyname'],
				'isrutin'=>$data['isrutin'],
				'paidbycompany'=>$data['paidbycompany'],
				'pphbycompany'=>$data['pphbycompany'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select a.wagetypeid,a.wagename,
						case when a.ispph = 1 then 'Yes' else 'No' end as ispph,
						case when a.ispayroll = 1 then 'Yes' else 'No' end as ispayroll,
						case when a.isprint = 1 then 'Yes' else 'No' end as isprint,
						a.percentage,a.maxvalue,
						b.currencyname as currency,
						case when a.isrutin = 1 then 'Yes' else 'No' end as isrutin,
						case when a.paidbycompany = 1 then 'Yes' else 'No' end as paidbycompany,
						case when a.pphbycompany = 1 then 'Yes' else 'No' end as pphbycompany,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from wagetype a 
						left join currency b on b.currencyid = a.currencyid ";
		$wagetypeid = filter_input(INPUT_GET,'wagetypeid');
		$wagename = filter_input(INPUT_GET,'wagename');
		$sql .= " where coalesce(a.wagetypeid,'') like '%".$wagetypeid."%' 
			and coalesce(a.wagename,'') like '%".$wagename."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.wagetypeid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by wagename asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=getCatalog('wagetype');
		$this->pdf->AddPage('P',array(400,300));
		//masukkan posisi judul
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L','L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(getCatalog('wagetypeid'),
																	getCatalog('wagename'),
																	getCatalog('ispph'),
																	getCatalog('ispayroll'),
																	getCatalog('isprint'),
																	getCatalog('percentage'),
																	getCatalog('maxvalue'),
																	getCatalog('currency'),
																	getCatalog('isrutin'),
																	getCatalog('paidbycompany'),
																	getCatalog('pphbycompany'),
																	getCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,120,15,15,15,40,35,20,20,30,35,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','L','L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['wagetypeid'],$row1['wagename'],$row1['ispph'],$row1['ispayroll'],$row1['isprint'],$row1['percentage'],$row1['maxvalue'],$row1['currency'],$row1['isrutin'],$row1['paidbycompany'],$row1['pphbycompany'],$row1['recordstatus']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	public function actionDownxls()
	{
		$this->menuname='wagetype';
		parent::actionDownxls();
		$sql = "select a.wagetypeid,a.wagename,
						case when a.ispph = 1 then 'Yes' else 'No' end as ispph,
						case when a.ispayroll = 1 then 'Yes' else 'No' end as ispayroll,
						case when a.isprint = 1 then 'Yes' else 'No' end as isprint,
						a.percentage,a.maxvalue,
						b.currencyname as currency,
						case when a.isrutin = 1 then 'Yes' else 'No' end as isrutin,
						case when a.paidbycompany = 1 then 'Yes' else 'No' end as paidbycompany,
						case when a.pphbycompany = 1 then 'Yes' else 'No' end as pphbycompany,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from wagetype a 
						left join currency b on b.currencyid = a.currencyid ";
		$wagetypeid = filter_input(INPUT_GET,'wagetypeid');
		$wagename = filter_input(INPUT_GET,'wagename');
		$sql .= " where coalesce(a.wagetypeid,'') like '%".$wagetypeid."%' 
			and coalesce(a.wagename,'') like '%".$wagename."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.wagetypeid in (".$_GET['id'].")";
		}
			$sql = $sql . " order by wagename asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		
		foreach($dataReader as $row1)
		{
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['wagetypeid'])
				->setCellValueByColumnAndRow(1,$i,$row1['wagename'])			
				->setCellValueByColumnAndRow(2,$i,$row1['ispph'])
				->setCellValueByColumnAndRow(3,$i,$row1['ispayroll'])
				->setCellValueByColumnAndRow(4,$i,$row1['isprint'])
				->setCellValueByColumnAndRow(5,$i,$row1['percentage'])
				->setCellValueByColumnAndRow(6,$i,$row1['maxvalue'])
				->setCellValueByColumnAndRow(7,$i,$row1['currency'])
				->setCellValueByColumnAndRow(8,$i,$row1['isrutin'])
				->setCellValueByColumnAndRow(9,$i,$row1['paidbycompany'])
				->setCellValueByColumnAndRow(10,$i,$row1['pphbycompany'])
				->setCellValueByColumnAndRow(11,$i,$row1['recordstatus']);
			$i++;
		}
		
		$this->getFooterXLS($this->phpExcel);
	}
	
	
	/*public function actionDownxls()
	{
		parent::actionDownload();
		$sql = "select wagename,ispph,ispayroll,isprint,percentage,`maxvalue`,currencyid,isrutin,paidbycompany,pphbycompany,recordstatus
				from wagetype a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.wagetypeid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		 $excel=Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
		$i=1;
		$excel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0,1,getCatalog('wagename'))
                ->setCellValueByColumnAndRow(1,1,getCatalog('ispph'))
                ->setCellValueByColumnAndRow(2,1,getCatalog('ispayroll'))
                ->setCellValueByColumnAndRow(3,1,getCatalog('isprint'))
                ->setCellValueByColumnAndRow(4,1,getCatalog('percentage'))
                ->setCellValueByColumnAndRow(5,1,getCatalog('maxvalue'))
                ->setCellValueByColumnAndRow(6,1,getCatalog('currencyid'))
                ->setCellValueByColumnAndRow(7,1,getCatalog('isrutin'))
                ->setCellValueByColumnAndRow(8,1,getCatalog('paidbycompany'))
                ->setCellValueByColumnAndRow(9,1,getCatalog('pphbycompany'))
                ->setCellValueByColumnAndRow(10,1,getCatalog('recordstatus'))
                ;		foreach($dataReader as $row1)
		{
			  $excel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['wagename'])
                                ->setCellValueByColumnAndRow(1, $i+1, $row1['ispph'])
                                ->setCellValueByColumnAndRow(2, $i+1, $row1['ispayroll'])
                                ->setCellValueByColumnAndRow(3, $i+1, $row1['isprint'])
                                ->setCellValueByColumnAndRow(4, $i+1, $row1['percentage'])
                                ->setCellValueByColumnAndRow(5, $i+1, $row1['maxvalue'])
                                ->setCellValueByColumnAndRow(6, $i+1, $row1['currencyid'])
                                ->setCellValueByColumnAndRow(7, $i+1, $row1['isrutin'])
                                ->setCellValueByColumnAndRow(8, $i+1, $row1['paidbycompany'])
                                ->setCellValueByColumnAndRow(9, $i+1, $row1['pphbycompany'])
                                ->setCellValueByColumnAndRow(10, $i+1, $row1['recordstatus'])
                                ;		$i+=1;
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="wagetype.xlsx"');
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
