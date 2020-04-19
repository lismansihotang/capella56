<?php
class EmployeewageController extends Controller
{
	public $menuname = 'employeewage';
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
				$sql = 'call Insertemployeewage(:vemployeeid,:vwagestartperiod,:vwagevalue,:vwageendperiod,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call Updateemployeewage(:vid,:vemployeeid,:vwagestartperiod,:vwagevalue,:vwageendperiod,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['employeewageid'],PDO::PARAM_STR);
			}
			$command->bindvalue(':vemployeeid',$_POST['employeeid'],PDO::PARAM_STR);
                        $command->bindvalue(':vwagestartperiod',date(Yii::app()->params['datetodb'], strtotime($_POST['wagestartperiod'])),PDO::PARAM_STR);
                        $command->bindvalue(':vwagevalue',$_POST['wagevalue'],PDO::PARAM_STR);
                        $command->bindvalue(':vwageendperiod',date(Yii::app()->params['datetodb'], strtotime($_POST['wageendperiod'])),PDO::PARAM_STR);
                        $command->bindvalue(':vrecordstatus',$_POST['recordstatus'],PDO::PARAM_STR);
			$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
			$command->execute();
			$transaction->commit();
			$this->DeleteLock($this->menuname, $_POST['employeewageid']);
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
				$sql = 'call Purgeemployeewage(:vid,:vcreatedby)';
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
	
	public function search()
	{
		header('Content-Type: application/json');
		
		// search 
		$employeewageid = isset ($_POST['employeewageid']) ? $_POST['employeewageid'] : '';
                $employeeid = isset ($_POST['employeeid']) ? $_POST['employeeid'] : '';
                $wagestartperiod = isset ($_POST['wagestartperiod']) ? $_POST['wagestartperiod'] : '';
                $wagevalue = isset ($_POST['wagevalue']) ? $_POST['wagevalue'] : '';
                $wageendperiod = isset ($_POST['wageendperiod']) ? $_POST['wageendperiod'] : '';
                $recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$employeewageid = isset ($_GET['q']) ? $_GET['q'] : $employeewageid;
                $employeeid = isset ($_GET['q']) ? $_GET['q'] : $employeeid;
                $wagestartperiod = isset ($_GET['q']) ? $_GET['q'] : $wagestartperiod;
                $wagevalue = isset ($_GET['q']) ? $_GET['q'] : $wagevalue;
                $wageendperiod = isset ($_GET['q']) ? $_GET['q'] : $wageendperiod;
                $recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		
		// pagging
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.employeewageid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		
		$result = array();
		$row = array();
	
		// result
		/*$criteria = new CDbCriteria;
		$criteria->compare('t.employeewageid',$employeewageid,true,'or');
                $criteria->compare('t.employeeid',$employeeid,true,'or');
                $criteria->compare('t.wagestartperiod',$wagestartperiod,true,'or');
                $criteria->compare('t.wagevalue',$wagevalue,true,'or');
                $criteria->compare('t.wageendperiod',$wageendperiod,true,'or');
                $criteria->compare('t.recordstatus',$recordstatus,true,'or');*/
                if (!isset($_GET['combo']))
                {
                        $cmd = Yii::app()->db->createCommand()
                                ->select('count(1) as total')	
                                ->from('employeewage t')
                                ->join('employee p','p.employeeid=t.employeeid')
                                ->where('(p.fullname like :employeeid) or 
                                        (wagestartperiod like :wagestartperiod) or 
                                        (wagevalue like :wagevalue) or 
                                        (wageendperiod like :wageendperiod)',
                                                array(':employeeid'=>'%'.$employeeid.'%',
                                                    ':wagestartperiod'=>'%'.$wagestartperiod.'%',
                                                    ':wagevalue'=>'%'.$wagevalue.'%',
                                                    ':wageendperiod'=>'%'.$wageendperiod.'%'
                                                    ))
                                ->queryRow();
                }
                else
                {
                        $cmd = Yii::app()->db->createCommand()
                                ->select('count(1) as total')	
                                ->from('employeewage t')
                                ->join('employee p','p.employeeid=t.employeeid')
                                ->where('((p.fullname like :employeeid) or 
                                        (wagestartperiod like :wagestartperiod) or 
                                        (wagevalue like :wagevalue) or 
                                        (wageendperiod like :wageendperiod)) and t.recordstatus=1',
                                                array(':employeeid'=>'%'.$employeeid.'%',
                                                    ':wagestartperiod'=>'%'.$wagestartperiod.'%',
                                                    ':wagevalue'=>'%'.$wagevalue.'%',
                                                    ':wageendperiod'=>'%'.$wageendperiod.'%'
                                                    ))
                                ->queryRow();
                }
	
		$result['total'] = $cmd['total'];
		
		if (!isset($_GET['combo']))
                {
                        $cmd = Yii::app()->db->createCommand()
                                ->select()	
                                ->from('employeewage t')
                                ->join('employee p','p.employeeid=t.employeeid')
                                ->where('(p.fullname like :employeeid) or 
                                        (wagestartperiod like :wagestartperiod) or 
                                        (wagevalue like :wagevalue) or 
                                        (wageendperiod like :wageendperiod)',
                                                array(':employeeid'=>'%'.$employeeid.'%',
                                                    ':wagestartperiod'=>'%'.$wagestartperiod.'%',
                                                    ':wagevalue'=>'%'.$wagevalue.'%',
                                                    ':wageendperiod'=>'%'.$wageendperiod.'%'
                                                    ))
                                ->offset($offset)
                                ->limit($rows)
                                ->order($sort.' '.$order)
                                ->queryAll();
                }
                else
                {
                        $cmd = Yii::app()->db->createCommand()
                                ->select()	
                                ->from('employeewage t')
                                ->join('employee p','p.employeeid=t.employeeid')
                                ->where('((p.fullname like :employeeid) or 
                                        (wagestartperiod like :wagestartperiod) or 
                                        (wagevalue like :wagevalue) or 
                                        (wageendperiod like :wageendperiod)) and t.recordstatus=1',
                                                array(':employeeid'=>'%'.$employeeid.'%',
                                                    ':wagestartperiod'=>'%'.$wagestartperiod.'%',
                                                    ':wagevalue'=>'%'.$wagevalue.'%',
                                                    ':wageendperiod'=>'%'.$wageendperiod.'%'
                                                    ))
                                ->offset($offset)
                                ->limit($rows)
                                ->order($sort.' '.$order)
                                ->queryAll();
                }
		
		foreach($cmd as $data)
		{	
			$row[] = array(
                        'employeewageid'=>$data['employeewageid'],
                        'employeeid'=>$data['employeeid'],
                        'fullname'=>$data['fullname'],
                        'wagestartperiod'=>$data['wagestartperiod'],
                        'wagevalue'=>$data['wagevalue'],
                        'wageendperiod'=>$data['wageendperiod'],
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
	  $sql = "select employeeid,wagestartperiod,wagevalue,wageendperiod,recordstatus
				from employeewage a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.employeewageid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=getCatalog('employeewage');
		$this->pdf->AddPage('P');
		//masukkan posisi judul
		$this->pdf->colalign = array('L','L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(getCatalog('employeeid'),
                getCatalog('wagestartperiod'),
                getCatalog('wagevalue'),
                getCatalog('wageendperiod'),
                getCatalog('recordstatus'));
		$this->pdf->setwidths(array(40,40,40,40,40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['employeeid'],$row1['wagestartperiod'],$row1['wagevalue'],$row1['wageendperiod'],$row1['recordstatus']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	
	public function actionDownxls()
	{
		parent::actionDownload();
		$sql = "select employeeid,wagestartperiod,wagevalue,wageendperiod,recordstatus
				from employeewage a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.employeewageid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		 $excel=Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
		$i=1;
		$excel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0,1,getCatalog('employeeid'))
                ->setCellValueByColumnAndRow(1,1,getCatalog('wagestartperiod'))
                ->setCellValueByColumnAndRow(2,1,getCatalog('wagevalue'))
                ->setCellValueByColumnAndRow(3,1,getCatalog('wageendperiod'))
                ->setCellValueByColumnAndRow(4,1,getCatalog('recordstatus'))
                ;		foreach($dataReader as $row1)
		{
			  $excel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['employeeid'])
                                ->setCellValueByColumnAndRow(1, $i+1, $row1['wagestartperiod'])
                                ->setCellValueByColumnAndRow(2, $i+1, $row1['wagevalue'])
                                ->setCellValueByColumnAndRow(3, $i+1, $row1['wageendperiod'])
                                ->setCellValueByColumnAndRow(4, $i+1, $row1['recordstatus'])
                                ;		$i+=1;
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="employeewage.xlsx"');
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
