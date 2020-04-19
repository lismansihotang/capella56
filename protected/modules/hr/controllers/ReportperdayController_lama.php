<?php
class ReportperdayController extends Controller
{
	public $menuname = 'reportperday';
	public function actionIndex()
	{
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	
	public function search()
	{
		header('Content-Type: application/json');
		
		// search 
		$reportperdayid = isset ($_POST['reportperdayid']) ? $_POST['reportperdayid'] : '';
			$employeeid = isset ($_POST['employeeid']) ? $_POST['employeeid'] : '';
			$fullname = isset ($_POST['fullname']) ? $_POST['fullname'] : '';
			$oldnik = isset ($_POST['oldnik']) ? $_POST['oldnik'] : '';
			$fulldivision = isset ($_POST['fulldivision']) ? $_POST['fulldivision'] : '';
			$absdate = isset ($_POST['absdate']) ? $_POST['absdate'] : '';
			$hourin = isset ($_POST['hourin']) ? $_POST['hourin'] : '';
			$hourout = isset ($_POST['hourout']) ? $_POST['hourout'] : '';
			$absscheduleid = isset ($_POST['absscheduleid']) ? $_POST['absscheduleid'] : '';
			$schedulename = isset ($_POST['schedulename']) ? $_POST['schedulename'] : '';
			$statusin = isset ($_POST['statusin']) ? $_POST['statusin'] : '';
			$statusout = isset ($_POST['statusout']) ? $_POST['statusout'] : '';
			$reason = isset ($_POST['reason']) ? $_POST['reason'] : '';
		$reportperdayid = isset ($_GET['q']) ? $_GET['q'] : $reportperdayid;
			$employeeid = isset ($_GET['q']) ? $_GET['q'] : $employeeid;
			$fullname = isset ($_GET['q']) ? $_GET['q'] : $fullname;
			$oldnik = isset ($_GET['q']) ? $_GET['q'] : $oldnik;
			$fulldivision = isset ($_GET['q']) ? $_GET['q'] : $fulldivision;
			$absdate = isset ($_GET['q']) ? $_GET['q'] : $absdate;
			$hourin = isset ($_GET['q']) ? $_GET['q'] : $hourin;
			$hourout = isset ($_GET['q']) ? $_GET['q'] : $hourout;
			$absscheduleid = isset ($_GET['q']) ? $_GET['q'] : $absscheduleid;
			$schedulename = isset ($_GET['q']) ? $_GET['q'] : $schedulename;
			$statusin = isset ($_GET['q']) ? $_GET['q'] : $statusin;
			$statusout = isset ($_GET['q']) ? $_GET['q'] : $statusout;
			$reason = isset ($_GET['q']) ? $_GET['q'] : $reason;
		
		// pagging
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'reportperdayid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort,'t.')>0)?$sort:'t.'.$sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order ;
		$offset = ($page-1) * $rows;
		
		$result = array();
		$row = array();
	
		// result
		$cmd = Yii::app()->db->createCommand()
		->select('count(1) as total')
		->from('reportperday t')
		->where('(t.fullname like :fullname)',
						array(':fullname'=>'%'.$fullname.'%'))
		->queryRow();
	
		$result['total'] = $cmd['total'];
		
		$cmd = Yii::app()->db->createCommand()
		->select('t.*')
		->from('reportperday t')
		->where('(t.fullname like :fullname)',
					array(':fullname'=>'%'.$fullname.'%'))
		->offset($offset)
		->limit($rows)
		->order($sort.' '.$order)
		->queryAll();
		
		foreach($cmd as $data)
		{	
			$row[] = array(
		'reportperdayid'=>$data['reportperdayid'],
'employeeid'=>$data['employeeid'],
'fullname'=>$data['fullname'],
'oldnik'=>$data['oldnik'],
'fulldivision'=>$data['fulldivision'],
'absdate'=>$data['absdate'],
'hourin'=>$data['hourin'],
'hourout'=>$data['hourout'],
'absscheduleid'=>$data['absscheduleid'],
'schedulename'=>$data['schedulename'],
'statusin'=>$data['statusin'],
'statusout'=>$data['statusout'],
		'reason'=>$data['reason'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	
	public function actionSave()
	{
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
						if (isset($_POST['isNewRecord']))
			{
				$sql = 'call Insertreportperday(:vemployeeid,:vfullname,:voldnik,:vfulldivision,:vabsdate,:vhourin,:vhourout,:vabsscheduleid,:vschedulename,:vstatusin,:vstatusout,:vreason,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call Updatereportperday(:vid,:vemployeeid,:vfullname,:voldnik,:vfulldivision,:vabsdate,:vhourin,:vhourout,:vabsscheduleid,:vschedulename,:vstatusin,:vstatusout,:vreason,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['reportperdayid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['reportperdayid']);
			}
			$command->bindvalue(':vemployeeid',$_POST['employeeid'],PDO::PARAM_STR);
$command->bindvalue(':vfullname',$_POST['fullname'],PDO::PARAM_STR);
$command->bindvalue(':voldnik',$_POST['oldnik'],PDO::PARAM_STR);
$command->bindvalue(':vfulldivision',$_POST['fulldivision'],PDO::PARAM_STR);
$command->bindvalue(':vabsdate',date(Yii::app()->params['datetodb'], strtotime($_POST['absdate'])),PDO::PARAM_STR);
$command->bindvalue(':vhourin',$_POST['hourin'],PDO::PARAM_STR);
$command->bindvalue(':vhourout',$_POST['hourout'],PDO::PARAM_STR);
$command->bindvalue(':vabsscheduleid',$_POST['absscheduleid'],PDO::PARAM_STR);
$command->bindvalue(':vschedulename',$_POST['schedulename'],PDO::PARAM_STR);
$command->bindvalue(':vstatusin',$_POST['statusin'],PDO::PARAM_STR);
$command->bindvalue(':vstatusout',$_POST['statusout'],PDO::PARAM_STR);
$command->bindvalue(':vreason',$_POST['reason'],PDO::PARAM_STR);
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
	
	public function actionPurge()
	{
		header('Content-Type: application/json');
		
		if (isset($_POST['id']))
		{
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try
			{
				$sql = 'call Purgereportperday(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				foreach($id as $ids)
				{
					$command->bindvalue(':vid',$ids,PDO::PARAM_STR);
					$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
					$command->execute();
				}
				$transaction->commit();
				getmessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e)
			{
				$transaction->rollback();
				getmessage(true,$e->getMessage());
			}
		}
		else
		{
			getmessage(true,getcatalog('chooseone'));
		}
	}
	
	public function actionDownPDF()
	{
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select employeeid,fullname,oldnik,fulldivision,absdate,hourin,hourout,absscheduleid,schedulename,statusin,statusout,reason
				from reportperday a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.reportperdayid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=getCatalog('reportperday');
		$this->pdf->AddPage('P');
		//masukkan posisi judul
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L','L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(getCatalog('employeeid'),
getCatalog('fullname'),
getCatalog('oldnik'),
getCatalog('fulldivision'),
getCatalog('absdate'),
getCatalog('hourin'),
getCatalog('hourout'),
getCatalog('absscheduleid'),
getCatalog('schedulename'),
getCatalog('statusin'),
getCatalog('statusout'),
getCatalog('reason'));
		$this->pdf->setwidths(array(40,40,40,40,40,40,40,40,40,40,40,40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','L','L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['employeeid'],$row1['fullname'],$row1['oldnik'],$row1['fulldivision'],$row1['absdate'],$row1['hourin'],$row1['hourout'],$row1['absscheduleid'],$row1['schedulename'],$row1['statusin'],$row1['statusout'],$row1['reason']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	
	public function actionDownxls()
	{
		parent::actionDownload();
		$sql = "select employeeid,fullname,oldnik,fulldivision,absdate,hourin,hourout,absscheduleid,schedulename,statusin,statusout,reason
				from reportperday a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.reportperdayid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		 $excel=Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
		$i=1;
		$excel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0,1,getCatalog('employeeid'))
->setCellValueByColumnAndRow(1,1,getCatalog('fullname'))
->setCellValueByColumnAndRow(2,1,getCatalog('oldnik'))
->setCellValueByColumnAndRow(3,1,getCatalog('fulldivision'))
->setCellValueByColumnAndRow(4,1,getCatalog('absdate'))
->setCellValueByColumnAndRow(5,1,getCatalog('hourin'))
->setCellValueByColumnAndRow(6,1,getCatalog('hourout'))
->setCellValueByColumnAndRow(7,1,getCatalog('absscheduleid'))
->setCellValueByColumnAndRow(8,1,getCatalog('schedulename'))
->setCellValueByColumnAndRow(9,1,getCatalog('statusin'))
->setCellValueByColumnAndRow(10,1,getCatalog('statusout'))
->setCellValueByColumnAndRow(11,1,getCatalog('reason'))
;		foreach($dataReader as $row1)
		{
			  $excel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['employeeid'])
->setCellValueByColumnAndRow(1, $i+1, $row1['fullname'])
->setCellValueByColumnAndRow(2, $i+1, $row1['oldnik'])
->setCellValueByColumnAndRow(3, $i+1, $row1['fulldivision'])
->setCellValueByColumnAndRow(4, $i+1, $row1['absdate'])
->setCellValueByColumnAndRow(5, $i+1, $row1['hourin'])
->setCellValueByColumnAndRow(6, $i+1, $row1['hourout'])
->setCellValueByColumnAndRow(7, $i+1, $row1['absscheduleid'])
->setCellValueByColumnAndRow(8, $i+1, $row1['schedulename'])
->setCellValueByColumnAndRow(9, $i+1, $row1['statusin'])
->setCellValueByColumnAndRow(10, $i+1, $row1['statusout'])
->setCellValueByColumnAndRow(11, $i+1, $row1['reason'])
;		$i+=1;
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="reportperday.xlsx"');
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
	

}
