<?php
class EmployeetaxController extends Controller
{
	public $menuname = 'employeetax';
	public function actionIndex()
	{
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
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
				$sql = 'call Insertemployeetax(:vemployeeid,:vtaxstartperiod,:vtaxvalue,:vtaxendperiod,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call Updateemployeetax(:vid,:vemployeeid,:vtaxstartperiod,:vtaxvalue,:vtaxendperiod,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['employeetaxid'],PDO::PARAM_STR);
                                $this->DeleteLock($this->menuname, $_POST['employeetaxid']);
			}
			$command->bindvalue(':vemployeeid',$_POST['employeeid'],PDO::PARAM_STR);
                        $command->bindvalue(':vtaxstartperiod',date(Yii::app()->params['datetodb'], strtotime($_POST['taxstartperiod'])),PDO::PARAM_STR);
                        $command->bindvalue(':vtaxvalue',$_POST['taxvalue'],PDO::PARAM_STR);
                        $command->bindvalue(':vtaxendperiod',date(Yii::app()->params['datetodb'], strtotime($_POST['taxendperiod'])),PDO::PARAM_STR);
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
				$sql = 'call Purgeemployeetax(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				
					$command->bindvalue(':vid',$id,PDO::PARAM_STR);
					$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
					$command->execute();
				
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
	
	public function search()
	{
		header('Content-Type: application/json');
		
		// search 
		$employeetaxid = isset ($_POST['employeetaxid']) ? $_POST['employeetaxid'] : '';
                $employeeid = isset ($_POST['employeeid']) ? $_POST['employeeid'] : '';
                $taxstartperiod = isset ($_POST['taxstartperiod']) ? $_POST['taxstartperiod'] : '';
                $taxvalue = isset ($_POST['taxvalue']) ? $_POST['taxvalue'] : '';
                $taxendperiod = isset ($_POST['taxendperiod']) ? $_POST['taxendperiod'] : '';
                $recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$employeetaxid = isset ($_GET['q']) ? $_GET['q'] : $employeetaxid;
                $employeeid = isset ($_GET['q']) ? $_GET['q'] : $employeeid;
                $taxstartperiod = isset ($_GET['q']) ? $_GET['q'] : $taxstartperiod;
                $taxvalue = isset ($_GET['q']) ? $_GET['q'] : $taxvalue;
                $taxendperiod = isset ($_GET['q']) ? $_GET['q'] : $taxendperiod;
                $recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		
		// pagging
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.employeetaxid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 't.employeetaxid';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
		$offset = ($page-1) * $rows;
		
		$result = array();
		$row = array();
	
		// result
		/*$criteria = new CDbCriteria;
		$criteria->compare('t.employeetaxid',$employeetaxid,true,'or');
                $criteria->compare('t.employeeid',$employeeid,true,'or');
                $criteria->compare('t.taxstartperiod',$taxstartperiod,true,'or');
                $criteria->compare('t.taxvalue',$taxvalue,true,'or');
                $criteria->compare('t.taxendperiod',$taxendperiod,true,'or');
                $criteria->compare('t.recordstatus',$recordstatus,true,'or');*/
                $cmd = Yii::app()->db->createCommand()
                                ->select('count(1) as total')	
                                ->from('employeetax t')
                                ->join('employee p','p.employeeid=t.employeeid')
                                ->where('(p.fullname like :employeeid) or 
                                        (taxstartperiod like :taxstartperiod) or 
                                        (taxvalue like :taxvalue) or 
                                        (taxendperiod like :taxendperiod)',
                                                array(':employeeid'=>'%'.$employeeid.'%',
                                                    ':taxstartperiod'=>'%'.$taxstartperiod.'%',
                                                    ':taxvalue'=>'%'.$taxvalue.'%',
                                                    ':taxendperiod'=>'%'.$taxendperiod.'%'
                                                    ))
                                ->queryRow();
	
		$result['total'] = $cmd['total'];
		
		$cmd = Yii::app()->db->createCommand()
                                ->select()	
                                ->from('employeetax t')
                                ->join('employee p','p.employeeid=t.employeeid')
                                ->where('(p.fullname like :employeeid) or 
                                        (taxstartperiod like :taxstartperiod) or 
                                        (taxvalue like :taxvalue) or 
                                        (taxendperiod like :taxendperiod)',
                                                array(':employeeid'=>'%'.$employeeid.'%',
                                                    ':taxstartperiod'=>'%'.$taxstartperiod.'%',
                                                    ':taxvalue'=>'%'.$taxvalue.'%',
                                                    ':taxendperiod'=>'%'.$taxendperiod.'%'
                                                    ))
                                ->offset($offset)
                                ->limit($rows)
                                ->order($sort.' '.$order)
                                ->queryAll();
		
		foreach($cmd as $data)
		{	
			$row[] = array(
                        'employeetaxid'=>$data['employeetaxid'],
                        'employeeid'=>$data['employeeid'],
                        'fullname'=>$data['fullname'],
                        'taxstartperiod'=>$data['taxstartperiod'],
                        'taxvalue'=>$data['taxvalue'],
                        'taxendperiod'=>$data['taxendperiod'],
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
	  $sql = "select employeeid,taxstartperiod,taxvalue,taxendperiod,recordstatus
				from employeetax a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.employeetaxid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=getCatalog('employeetax');
		$this->pdf->AddPage('P');
		//masukkan posisi judul
		$this->pdf->colalign = array('L','L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(getCatalog('employeeid'),
                getCatalog('taxstartperiod'),
                getCatalog('taxvalue'),
                getCatalog('taxendperiod'),
                getCatalog('recordstatus'));
		$this->pdf->setwidths(array(40,40,40,40,40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['employeeid'],$row1['taxstartperiod'],$row1['taxvalue'],$row1['taxendperiod'],$row1['recordstatus']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	
	public function actionDownxls()
	{
		parent::actionDownload();
		$sql = "select employeeid,taxstartperiod,taxvalue,taxendperiod,recordstatus
				from employeetax a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.employeetaxid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		 $excel=Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
		$i=1;
		$excel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0,1,getCatalog('employeeid'))
                ->setCellValueByColumnAndRow(1,1,getCatalog('taxstartperiod'))
                ->setCellValueByColumnAndRow(2,1,getCatalog('taxvalue'))
                ->setCellValueByColumnAndRow(3,1,getCatalog('taxendperiod'))
                ->setCellValueByColumnAndRow(4,1,getCatalog('recordstatus'))
                ;		foreach($dataReader as $row1)
		{
			  $excel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['employeeid'])
                                ->setCellValueByColumnAndRow(1, $i+1, $row1['taxstartperiod'])
                                ->setCellValueByColumnAndRow(2, $i+1, $row1['taxvalue'])
                                ->setCellValueByColumnAndRow(3, $i+1, $row1['taxendperiod'])
                                ->setCellValueByColumnAndRow(4, $i+1, $row1['recordstatus'])
                                ;		$i+=1;
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="employeetax.xlsx"');
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
