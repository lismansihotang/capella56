<?php
class EducationmajorController extends Controller
{
	public $menuname = 'educationmajor';
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
				$sql = 'call Inserteducationmajor(:veducationid,:veducationmajorname,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call Updateeducationmajor(:vid,:veducationid,:veducationmajorname,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['educationmajorid'],PDO::PARAM_STR);
                                $this->DeleteLock($this->menuname, $_POST['educationmajorid']);
			}
			$command->bindvalue(':veducationid',$_POST['educationid'],PDO::PARAM_STR);
                        $command->bindvalue(':veducationmajorname',$_POST['educationmajorname'],PDO::PARAM_STR);
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
				$sql = 'call Purgeeducationmajor(:vid,:vcreatedby)';
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
		$educationmajorid = isset ($_POST['educationmajorid']) ? $_POST['educationmajorid'] : '';
                $educationid = isset ($_POST['educationid']) ? $_POST['educationid'] : '';
                $educationmajorname = isset ($_POST['educationmajorname']) ? $_POST['educationmajorname'] : '';
                $recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$educationmajorid = isset ($_GET['q']) ? $_GET['q'] : $educationmajorid;
                $educationid = isset ($_GET['q']) ? $_GET['q'] : $educationid;
                $educationmajorname = isset ($_GET['q']) ? $_GET['q'] : $educationmajorname;
                $recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		
		// pagging
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.educationmajorid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
                $page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		
		$result = array();
		$row = array();
	
		// result
		/*$criteria = new CDbCriteria;
		$criteria->with=array('education');
		$criteria->compare('t.educationmajorid',$educationmajorid,true,'or');
                $criteria->compare('education.educationname',$educationid,true,'or');
                $criteria->compare('t.educationmajorname',$educationmajorname,true,'or');
                $criteria->compare('t.recordstatus',$recordstatus,true,'or');*/
                if (!isset($_GET['combo']))
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('educationmajor t')
                                ->join('education p','p.educationid=t.educationid')
				->where('(p.educationname like :educationid) or (educationmajorname like :educationmajorname)',
						array(':educationid'=>'%'.$educationid.'%',':educationmajorname'=>'%'.$educationmajorname.'%'
                                                    ))
				->queryRow();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('educationmajor t')
                                ->join('education p','p.educationid=t.educationid')
				->where('((p.educationname like :educationid) or (educationmajorname like :educationmajorname)) and t.recordstatus=1',
						array(':educationid'=>'%'.$educationid.'%',':educationmajorname'=>'%'.$educationmajorname.'%'
                                                    ))
				->queryRow();
		}
	
		$result['total'] = $cmd['total'];
		
		if (!isset($_GET['combo']))
		{
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('educationmajor t')
                                ->join('education p','p.educationid=t.educationid')
				->where('(p.educationname like :educationid) or (educationmajorname like :educationmajorname)',
						array(':educationid'=>'%'.$educationid.'%',':educationmajorname'=>'%'.$educationmajorname.'%'
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
				->from('educationmajor t')
                                ->join('education p','p.educationid=t.educationid')
				->where('((p.educationname like :educationid) or (educationmajorname like :educationmajorname)) and t.recordstatus=1',
						array(':educationid'=>'%'.$educationid.'%',':educationmajorname'=>'%'.$educationmajorname.'%'
                                                    ))
				->offset($offset)
                                ->limit($rows)
                                ->order($sort.' '.$order)
                                ->queryAll();
		}
		
		foreach($cmd as $data)
		{	
			$row[] = array(
                        'educationmajorid'=>$data['educationmajorid'],
                        'educationid'=>$data['educationid'],
                        'educationname'=>$data['educationname'],
                        'educationmajorname'=>$data['educationmajorname'],
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
	  $sql = "select educationid,educationmajorname,recordstatus
				from educationmajor a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.educationmajorid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=getCatalog('educationmajor');
		$this->pdf->AddPage('P');
		//masukkan posisi judul
		$this->pdf->colalign = array('L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(getCatalog('educationid'),
                getCatalog('educationmajorname'),
                getCatalog('recordstatus'));
		$this->pdf->setwidths(array(40,40,40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['educationid'],$row1['educationmajorname'],$row1['recordstatus']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	
	public function actionDownxls()
	{
		parent::actionDownload();
		$sql = "select educationid,educationmajorname,recordstatus
				from educationmajor a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.educationmajorid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		 $excel=Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
		$i=1;
		$excel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0,1,getCatalog('educationid'))
                ->setCellValueByColumnAndRow(1,1,getCatalog('educationmajorname'))
                ->setCellValueByColumnAndRow(2,1,getCatalog('recordstatus'))
                ;		foreach($dataReader as $row1)
		{
			  $excel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['educationid'])
                                ->setCellValueByColumnAndRow(1, $i+1, $row1['educationmajorname'])
                                ->setCellValueByColumnAndRow(2, $i+1, $row1['recordstatus'])
                                ;		$i+=1;
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="educationmajor.xlsx"');
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
