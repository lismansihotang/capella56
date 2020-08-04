<?php
class UserdashController extends Controller {
	public $menuname = 'userdash';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$userdashid = GetSearchText(array('POST','Q'),'userdashid');
		$groupname = GetSearchText(array('POST','Q'),'groupname');
		$menuname = GetSearchText(array('POST','Q'),'menuname');
		$widget = GetSearchText(array('POST','Q'),'widget');
		$position = GetSearchText(array('POST','Q'),'position');
		$webformat = GetSearchText(array('POST','Q'),'webformat');
		$dashgroup = GetSearchText(array('POST','Q'),'dashgroup');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','userdashid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('userdash t')
				->leftjoin('groupaccess a','a.groupaccessid = t.groupaccessid')
				->leftjoin('widget b','b.widgetid = t.widgetid')
				->leftjoin('menuaccess c','c.menuaccessid = t.menuaccessid')
				->where('(userdashid like :userdashid) and (groupname like :groupname) and (widgetname like :widget) and (menuname like :menuname)',
						array(':userdashid'=>$userdashid,':groupname'=>$groupname,':widget'=>$widget,':menuname'=>$menuname))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('userdash t')
				->leftjoin('groupaccess a','a.groupaccessid = t.groupaccessid')
				->leftjoin('widget b','b.widgetid = t.widgetid')
				->leftjoin('menuaccess c','c.menuaccessid = t.menuaccessid')
				->where('((userdashid like :userdashid) or (groupname like :groupname) or (widgetname like :widget) or (menuname like :menuname)) and t.recordstatus = 1',
						array(':userdashid'=>$userdashid,':groupname'=>$groupname,':widget'=>$widget,':menuname'=>$menuname))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('*')			
				->from('userdash t')
				->leftjoin('groupaccess a','a.groupaccessid = t.groupaccessid')
				->leftjoin('widget b','b.widgetid = t.widgetid')
				->leftjoin('menuaccess c','c.menuaccessid = t.menuaccessid')
				->where('(t.userdashid like :userdashid) and (a.groupname like :groupname) and (widgetname like :widget) and (c.menuname like :menuname)',
						array(':userdashid'=>$userdashid,':groupname'=>$groupname,':widget'=>$widget,':menuname'=>$menuname))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
				$cmd = Yii::app()->db->createCommand()
				->select('*')			
				->from('userdash t')
				->leftjoin('groupaccess a','a.groupaccessid = t.groupaccessid')
				->leftjoin('widget b','b.widgetid = t.widgetid')
				->leftjoin('menuaccess c','c.menuaccessid = t.menuaccessid')
				->where('(t.userdashid like :userdashid) or (a.groupname like :groupname) or (widgetname like :widgetname) or (c.menuname like :menuname)',
						array(':userdashid'=>$userdashid,':groupname'=>$groupname,':widgetname'=>$widgetname,':menuname'=>$menuname))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
			'userdashid'=>$data['userdashid'],
			'groupaccessid'=>$data['groupaccessid'],
			'groupname'=>$data['groupname'],
			'menuaccessid'=>$data['menuaccessid'],
			'menuname'=>$data['menuname'],
			'widgetid'=>$data['widgetid'],
			'widgetname'=>$data['widgetname'],
			'position'=>$data['position'],
			'webformat'=>$data['webformat'],
			'dashgroup'=>$data['dashgroup'],
			'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'insert into userdash (groupaccessid, widgetid,menuaccessid, position, webformat, dashgroup) 
				values (:vgroupaccessid, :vwidgetid, :vmenuaccessid, :vposition, :vwebformat, :vdashgroup)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'update userdash set groupaccessid = :vgroupaccessid, widgetid = :vwidgetid, menuaccessid = :vmenuaccessid, position = :vposition, 
				webformat = :vwebformat, dashgroup = :vdashgroup where userdashid = :vid';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vgroupaccessid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vwidgetid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vmenuaccessid',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vposition',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vwebformat',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vdashgroup',$arraydata[6],PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-userdash"]["name"]);
		if (move_uploaded_file($_FILES["file-userdash"]["tmp_name"], $target_file)) {
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
					$groupname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$groupid = Yii::app()->db->createCommand("select groupaccessid from groupaccess where groupname = '".$groupname."'")->queryScalar();
					$widgetname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$widgetid = Yii::app()->db->createCommand()->queryScalar();
					$menuname = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$menuid = Yii::app()->db->createCommand()->queryScalar();
					$position = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$webformat = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$dashgroup = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$this->ModifyData($connection,array($id,$groupid,$widgetid,$menuid,$position,$webformat,$dashgroup));
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
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['userdashid'])?$_POST['userdashid']:''),
				$_POST['groupaccessid'],
				$_POST['widgetid'],
				$_POST['menuaccessid'],
				$_POST['position'],
				$_POST['webformat'],
				$_POST['dashgroup']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}	
	}
	public function actionPurge() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeuserdash(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode($e->errorInfo));
			}
		}
		else {
			GetMessage(true,'chooseone');
		}
	}
	public function actionDownPDF() {
	  parent::actionDownload();  
		$sql = "select a.userdashid,b.groupname,c.menuname,d.widgetname 
				from userdash a 
				left join groupaccess b on b.groupaccessid = a.groupaccessid 
				left join menuaccess c on c.menuaccessid = a.menuaccessid 
				left join widget d on d.widgetid = a.widgetid ";
		$userdashid = GetSearchText(array('GET'),'userdashid');
		$groupname = GetSearchText(array('GET'),'groupname');
		$menuname = GetSearchText(array('GET'),'menuname');
		$widget = GetSearchText(array('GET'),'widget');
		$position = GetSearchText(array('GET'),'position');
		$webformat = GetSearchText(array('GET'),'webformat');
		$dashgroup = GetSearchText(array('GET'),'dashgroup');
		$sql .= " where coalesce(a.userdashid,'') like '".$userdashid."' 
			and coalesce(b.groupname,'') like '".$groupname."'
			and coalesce(c.menuname,'') like '".$menuname."'
			and coalesce(d.widgetname,'') like '".$widget."'
			and coalesce(a.position,'') like '".$position."'
			and coalesce(a.webformat,'') like '".$webformat."'
			and coalesce(a.dashgroup,'') like '".$dashgroup."'
			";
		if ($_GET['id'] !== '') {
			$sql = $sql . " and a.userdashid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by a.userdashid asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		$this->pdf->title=GetCatalog('userdash');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('userdashid'),
									GetCatalog('groupaccess'),
									GetCatalog('menuaccess'),
									GetCatalog('widget'));
		$this->pdf->setwidths(array(15,60,60,60));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L');
		
		foreach($dataReader as $row1)
		{
		  $this->pdf->row(array($row1['userdashid'],$row1['groupname'],$row1['menuname'],$row1['widgetname']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='userdash';
		parent::actionDownxls();
		$sql = "select a.userdashid,b.groupname,c.menuname,d.widgetname 
				from userdash a 
				left join groupaccess b on b.groupaccessid = a.groupaccessid 
				left join menuaccess c on c.menuaccessid = a.menuaccessid 
				left join widget d on d.widgetid = a.widgetid ";
		$userdashid = GetSearchText(array('GET'),'userdashid');
		$groupname = GetSearchText(array('GET'),'groupname');
		$menuname = GetSearchText(array('GET'),'menuname');
		$widget = GetSearchText(array('GET'),'widget');
		$position = GetSearchText(array('GET'),'position');
		$webformat = GetSearchText(array('GET'),'webformat');
		$dashgroup = GetSearchText(array('GET'),'dashgroup');
		$sql .= " where coalesce(a.userdashid,'') like '".$userdashid."' 
			and coalesce(b.groupname,'') like '".$groupname."'
			and coalesce(c.menuname,'') like '".$menuname."'
			and coalesce(d.widgetname,'') like '".$widget."'
			and coalesce(a.position,'') like '".$position."'
			and coalesce(a.webformat,'') like '".$webformat."'
			and coalesce(a.dashgroup,'') like '".$dashgroup."'
			";
		if ($_GET['id'] !== '') {
			$sql = $sql . " and a.userdashid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by a.userdashid asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;
		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('userdashid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('groupaccess'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('menuaccess'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('widget'));
			
		foreach($dataReader as $row1)
		{
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['userdashid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['groupname'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['menuname'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['widgetname']);
			$i+=1;
		}
		
		$this->getFooterXLS($this->phpExcel);
	}
	
	
	/*public function actionDownxls()
	{
		parent::actionDownload();
		$sql = "select groupaccessid,menuaccessid,recordstatus
				from userdash a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.userdashid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		 $excel=Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
		$i=1;
		$excel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0,1,GetCatalog('groupaccessid'))
                ->setCellValueByColumnAndRow(1,1,GetCatalog('menuaccessid'))
                ->setCellValueByColumnAndRow(2,1,GetCatalog('recordstatus'))
                ;		foreach($dataReader as $row1)
		{
			  $excel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['groupaccessid'])
                                ->setCellValueByColumnAndRow(1, $i+1, $row1['menuaccessid'])
                                ->setCellValueByColumnAndRow(2, $i+1, $row1['recordstatus'])
                                ;		$i+=1;
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="userdash.xlsx"');
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
