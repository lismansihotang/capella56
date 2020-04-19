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
        if(isset($_POST['absdate']) && $_POST['absdate']!='')
        {
            $absdate = $_POST['absdate'];
        }
        else
        {
            $absdate = '';
        }
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
        /*
		$criteria = new CDbCriteria;
		$criteria->compare('t.reportperdayid',$reportperdayid,true,'or');
$criteria->compare('t.employeeid',$employeeid,true,'or');
$criteria->compare('t.fullname',$fullname,true,'or');
$criteria->compare('t.oldnik',$oldnik,true,'or');
$criteria->compare('t.fulldivision',$fulldivision,true,'or');
$criteria->compare('t.absdate',$absdate,true,'or');
$criteria->compare('t.hourin',$hourin,true,'or');
$criteria->compare('t.hourout',$hourout,true,'or');
$criteria->compare('t.absscheduleid',$absscheduleid,true,'or');
$criteria->compare('t.schedulename',$schedulename,true,'or');
$criteria->compare('t.statusin',$statusin,true,'or');
$criteria->compare('t.statusout',$statusout,true,'or');
$criteria->compare('t.reason',$reason,true,'or');
	
		$result['total'] = count(Reportperday::model()->findAll($criteria));
		
		$criteria->offset=$offset;
		$criteria->limit=$rows;
		$criteria->order=$sort.' '.$order;
		*/
        if (isset($_GET['combo']))
        {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('reportperday t')
				->where('((t.fullname like :fullname) or (t.fulldivision like :fulldivision) or (t.absdate like :absdate)) ',
					array(':fullname'=>'%'.$fullname.'%',':fulldivision'=>'%'.$fulldivision.'%',
							':absdate'=>'%'.$absdate.'%'))
				->queryScalar();
		}
        else
        {
            $cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('reportperday t')
				->where("(coalesce(t.fullname,'') like :fullname) and (coalesce(t.fulldivision,'') like :fulldivision) and (coalesce(t.absdate,'') like :absdate)",
					array(':fullname'=>'%'.$fullname.'%',':fulldivision'=>'%'.$fulldivision.'%',
							':absdate'=>'%'.$absdate.'%'))
				->queryScalar();
        }
        $result['total'] = $cmd;
        if (isset($_GET['combo']))
        {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')	
				->from('reportperday t')
				->where('((t.fullname like :fullname) or (t.fulldivision like :fulldivision) or (t.month like :month) or (t.year like :year)) ',
					array(':fullname'=>'%'.$fullname.'%',':fulldivision'=>'%'.$fulldivision.'%',
							':absdate'=>'%'.$absdate.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
        else
        {
            $cmd = Yii::app()->db->createCommand()
				->select("t.*")	
				->from('reportperday t')
				->where("(coalesce(t.fullname,'') like :fullname) and (coalesce(t.fulldivision,'') like :fulldivision) and (coalesce(t.absdate,'') like :absdate)",
					array(':fullname'=>'%'.$fullname.'%',':fulldivision'=>'%'.$fulldivision.'%',
							':absdate'=>'%'.$absdate.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
        }
        
		//foreach(Reportperday::model()->findAll($criteria) as $data)
        foreach($cmd as $data)
		{	
			$row[] = array(
		'reportperdayid'=>$data['reportperdayid'],
'employeeid'=>$data['employeeid'],
'fullname'=>$data['fullname'],
'oldnik'=>$data['oldnik'],
'fulldivision'=>$data['fulldivision'],
'absdate'=>date(Yii::app()->params['dateviewfromdb'],strtotime($data['absdate'])),
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
	  $sql = "select   employeeid,fullname,oldnik,fulldivision,absdate,hourin,hourout,absscheduleid,schedulename,statusin,statusout,reason
        from reportperday a ";
		$employee = filter_input(INPUT_GET,'fullname');
        $fulldivision = filter_input(INPUT_GET,'fulldivision');
		$absdate = filter_input(INPUT_GET,'absdate');
		//$year = filter_input(INPUT_GET,'year');
        
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.reportperday in (".$_GET['id'].")";
		}
        $sql .= " where coalesce(a.fullname,'') like '%".$employee."%' 
			 and coalesce(a.fulldivision,'') like '%".$fulldivision."%'";
        if(isset($absdate) && $absdate!='')
        {
            $sql .= " and a.absdate = '".date(Yii::app()->params['datetodb'],strtotime($absdate))."'";
        }
			
		$sql = $sql . " order by fullname";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=getCatalog('reportperday');
		$this->pdf->AddPage('L');
        $this->pdf->setFont('Arial','',9);
		//masukkan posisi judul
        $this->pdf->setwidths(array(40,25,40,25,15,15,30,15,15,60));
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(
getCatalog('fullname'),
getCatalog('oldnik'),
getCatalog('fulldivision'),
getCatalog('absdate'),
getCatalog('hourin'),
getCatalog('hourout'),
getCatalog('schedulename'),
getCatalog('statusin'),
getCatalog('statusout'),
getCatalog('reason'));
		//$this->pdf->setwidths(array(40,40,40,40,40,40,40,40,40,40,40,40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['fullname'],$row1['oldnik'],$row1['fulldivision'],$row1['absdate'],$row1['hourin'],$row1['hourout'],$row1['schedulename'],$row1['statusin'],$row1['statusout'],$row1['reason']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	
	public function actionDownxls()
	{
        $this->menuname='reportperday';
		parent::actionDownxls();
		$sql = "select employeeid,fullname,oldnik,fulldivision,absdate,hourin,hourout,absscheduleid,schedulename,statusin,statusout,reason
        from reportperday a ";
        
		$employee = filter_input(INPUT_GET,'fullname');
        $fulldivision = filter_input(INPUT_GET,'fulldivision');
		$absdate = filter_input(INPUT_GET,'absdate');
		//$year = filter_input(INPUT_GET,'year');
        
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.reportinid in (".$_GET['id'].")";
		}
        $sql .= " where coalesce(a.fullname,'') like '%".$employee."%' 
			 and coalesce(a.fulldivision,'') like '%".$fulldivision."%' 
			 and coalesce(a.absdate,'') like '%".$absdate."%'"; 
		$sql = $sql . " order by fullname";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$i=1;
        $line=2;
		$this->phpExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(0,$line,getCatalog('employeeid'))
                ->setCellValueByColumnAndRow(1,$line,getCatalog('fullname'))
                ->setCellValueByColumnAndRow(2,$line,getCatalog('oldnik'))
                ->setCellValueByColumnAndRow(3,$line,getCatalog('fulldivision'))
                ->setCellValueByColumnAndRow(4,$line,getCatalog('absdate'))
                ->setCellValueByColumnAndRow(5,$line,getCatalog('hourin'))
                ->setCellValueByColumnAndRow(6,$line,getCatalog('hourout'))
                ->setCellValueByColumnAndRow(7,$line,getCatalog('schedulename'))
                ->setCellValueByColumnAndRow(8,$line,getCatalog('statusin'))
                ->setCellValueByColumnAndRow(9,$line,getCatalog('statusout'))
                ->setCellValueByColumnAndRow(10,$line,getCatalog('reason'));		
        $line++;
        foreach($dataReader as $row1)
		{
			  $this->phpExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow(0, $line, $row1['employeeid'])
                    ->setCellValueByColumnAndRow(1, $line, $row1['fullname'])
                    ->setCellValueByColumnAndRow(2, $line, $row1['oldnik'])
                    ->setCellValueByColumnAndRow(3, $line, $row1['fulldivision'])
                    ->setCellValueByColumnAndRow(4, $line, $row1['absdate'])
                    ->setCellValueByColumnAndRow(5, $line, $row1['hourin'])
                    ->setCellValueByColumnAndRow(6, $line, $row1['hourout'])
                    ->setCellValueByColumnAndRow(7, $line, $row1['schedulename'])
                    ->setCellValueByColumnAndRow(8, $line, $row1['statusin'])
                    ->setCellValueByColumnAndRow(9, $line, $row1['statusout'])
                    ->setCellValueByColumnAndRow(10, $line, $row1['reason']);		
            $i+=1;
            $line++;
		}
        /*
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
        */
        $this->getFooterXLS($this->phpExcel);
	}
	

}
