<?php
class ojtController extends Controller {
	public $menuname = 'ojt';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexdetail() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionSearchDetail();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$ojtid = GetSearchText(array('POST','Q'),'ojtid');
		$ojtno = GetSearchText(array('POST','Q'),'ojtno');
		$fullname = GetSearchText(array('POST','Q'),'fullname');
		$positionname = GetSearchText(array('POST','Q'),'positionname');
		$plantcode = GetSearchText(array('POST','Q'),'plantcode');
		$headernote = GetSearchText(array('POST','Q'),'headernote');
		$page       = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows       = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort       = isset($_POST['sort']) ? strval($_POST['sort']) : 'ojtid';
    $order      = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset     = ($page - 1) * $rows;
    $page       = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows       = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort       = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order      = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		$dependency = new CDbCacheDependency('SELECT MAX(updatedate) FROM ojt');
		if (!isset($_GET['getdata'])) {
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')
				->from('ojt t')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('employee c','c.employeeid = t.employeeid')
				->leftjoin('position d','d.positionid = c.positionid')
				->where("((coalesce(ojtno,'') like :ojtno) 
				or (coalesce(ojtid,'') like :ojtid)
				or (coalesce(c.fullname,'') like :fullname)
				or (coalesce(d.positionname,'') like :positionname)
				or (coalesce(b.plantcode,'') like :plantcode)
				) 
				and t.recordstatus = 1",
						array(
						':ojtno'=>$ojtno,
						':ojtid'=>$ojtid,
						':fullname'=>$fullname,
						':positionname'=>$positionname,
						':plantcode'=>$plantcode
						))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')
				->from('ojt t')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('employee c','c.employeeid = t.employeeid')
				->leftjoin('position d','d.positionid = c.positionid')
				->where("coalesce(ojtid,'') like :ojtid 
					and coalesce(ojtno,'') like :ojtno 
					and coalesce(c.fullname,'') like :fullname
					and coalesce(d.positionname,'') like :positionname
					and coalesce(b.plantcode,'') like :plantcode
					and t.recordstatus > 0",
						array(
						':ojtno'=>$ojtno,
						':ojtid'=>$ojtid,
						':fullname'=>$fullname,
						':positionname'=>$positionname,
						':plantcode'=>$plantcode
						))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('t.*,c.fullname,b.plantcode,d.positionname')			
				->from('ojt t')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('employee c','c.employeeid = t.employeeid')
				->leftjoin('position d','d.positionid = c.positionid')
				->where("((coalesce(ojtno,'') like :ojtno) 
				or (coalesce(ojtid,'') like :ojtid)
				or (coalesce(c.fullname,'') like :fullname)
				or (coalesce(d.positionname,'') like :positionname)
				or (coalesce(b.plantcode,'') like :plantcode)
				) 
				and t.recordstatus > 0",
						array(
						':ojtno'=>$ojtno,
						':ojtid'=>$ojtid,
						':fullname'=>$fullname,
						':positionname'=>$positionname,
						':plantcode'=>$plantcode
						))
				->order($sort.' '.$order)
				->queryAll();			
		}
		else 
		{
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('t.*,c.fullname,b.plantcode,d.positionname')			
				->from('ojt t')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('employee c','c.employeeid = t.employeeid')
				->leftjoin('position d','d.positionid = c.positionid')
				->where("coalesce(ojtid,'') like :ojtid 
					and coalesce(ojtno,'') like :ojtno 
					and coalesce(c.fullname,'') like :fullname
					and coalesce(d.positionname,'') like :positionname
					and coalesce(b.plantcode,'') like :plantcode
					and t.recordstatus > 0",
						array(
						':ojtno'=>$ojtno,
						':ojtid'=>$ojtid,
						':fullname'=>$fullname,
						':positionname'=>$positionname,
						':plantcode'=>$plantcode
						))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
			'ojtid'=>$data['ojtid'],
			'plantid'=>$data['plantid'],
			'plantcode'=>$data['plantcode'],
			'ojtno'=>$data['ojtno'],
			'headernote'=>$data['headernote'],
			'firstdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['firstdate'])),
			'duedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['duedate'])),
			'employeeid'=>$data['employeeid'],
			'fullname'=>$data['fullname'],
			'positionid'=>$data['positionid'],
			'positionname'=>$data['positionname'],
			'recordstatus'=>$data['recordstatus'],
			'statusname'=>$data['statusname']
			);
			}
			$result = array_merge($result, array(
				'rows' => $row
			));
	} else {
		$ojtid = GetSearchText(array('POST','Q','GET'),'ojtid',0,'int');
			$cmd = Yii::app()->db->createCommand("
				select a.ojtid
				from ojt a 
				where a.ojtid = ".$ojtid)->queryRow();
			$result = $cmd;
		}
    return CJSON::encode($result);
	}
	public function actionSearchDetail() {
		header('Content-Type: application/json');
		$id = 0;	
		if (isset($_POST['id']))
		{
			$id = $_POST['id'];
		}
		else 
		if (isset($_GET['id']))
		{
			$id = $_GET['id'];
		}
		$page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','ojtdetid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('ojtdet t')
				->join('criteriaojt b','b.criteriaojtid = t.criteriaojtid')
				->where('ojtid = :abid',
						array(':abid'=>$id))
				->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
				->select('t.*,b.criteriaojtname')			
				->from('ojtdet t')
				->join('criteriaojt b','b.criteriaojtid = t.criteriaojtid')
				->where('ojtid = :abid',
						array(':abid'=>$id))
				->order($sort.' '.$order)
				->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
			'ojtdetid'=>$data['ojtdetid'],
			'ojtid'=>$data['ojtid'],
			'criteriaojtid'=>$data['criteriaojtid'],
			'criteriaojtname'=>$data['criteriaojtname'],
			'ojtval' => Yii::app()->format->formatNumber($data['ojtval']),
			'correctionval' => Yii::app()->format->formatNumber($data['correctionval']),
			'totalval' => Yii::app()->format->formatNumber($data['totalval']),
			'averageval' => Yii::app()->format->formatNumber($data['averageval']),
			'devisiondesc'=>$data['devisiondesc'],
			'departdesc'=>$data['departdesc']
			);
		}
		$result=array_merge($result,array('rows'=>$row));;
		echo CJSON::encode($result);
	}
	
	public function actiongetdata() {
		$id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'ojtid' => $id
		));
	}
	private function ModifyData($connection,$arraydata) {	
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		$sql = 'call Modifojt(:vid,:vplantid,:vemployeeid,:vfirstdate,:vduedate,:vheadernote,:vcreatedby)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vplantid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vemployeeid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vfirstdate',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vduedate',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vheadernote',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-ojt"]["name"]);
		if (move_uploaded_file($_FILES["file-ojt"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$abid = '';$nourut = '';
				for ($row = 2; $row <= $highestRow; ++$row) {
					$nourut = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$ojtno = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$abid = Yii::app()->db->createCommand("select ojtid from ojt where ojtno = '".$ojtno."'")->queryScalar();
					if ($abid == '') {					
						$ojtdate = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
						$bankaccountno = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
						$ojtname = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
						$fullname = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
						$recordstatus = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
						$this->ModifyData($connection,array('',$ojtno,$ojtdate,$bankaccountno,$ojtname,$fullname,'',$recordstatus));
						//get id ojtid
						$abid = Yii::app()->db->createCommand("select ojtid from ojt where ojtno = '".$ojtno."'")->queryScalar();
					}
					if ($abid != '') {
						if ($objWorksheet->getCellByColumnAndRow(7, $row)->getValue() != '') {
							$ojtname = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
							$criteriaojtid = Yii::app()->db->createCommand("select criteriaojtid from criteriaojtname where ojtname = '".$ojtname."'")->queryScalar();
							$ojtname = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
							$criteriaojtname = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
							$perbaikan = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
							$ojt = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
							$employeeid = Yii::app()->db->createCommand("select employeeid from city where ojt = '".$ojt."'")->queryScalar();
							$penjelasan = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
							$description = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
							$lat = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
							$lng = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
							$this->ModifyDataojtdet($connection,array('',$abid,$criteriaojtid,$ojtname,$criteriaojtname,$perbaikan,$employeeid,$penjelasan,$description,$lat,$lng));
						}
						if ($objWorksheet->getCellByColumnAndRow(16, $row)->getValue() != '') {
							$contacttypename = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
							$contacttypeid = Yii::app()->db->createCommand("select contacttypeid from contacttype where contacttypename = '".$contacttypename."'")->queryScalar();
							$contactname = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
							$contactph = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
							$contacthp = $objWorksheet->getCellByColumnAndRow(19, $row)->getValue();
							$contactemail = $objWorksheet->getCellByColumnAndRow(20, $row)->getValue();
							$this->ModifyDataContact($connection,array('',$abid, $contacttypeid,$contactname,$contacthp,$contactph,$contactemail));
						}
					}
				}
				$transaction->commit();
				GetMessage(false,getcatalog('insecriteriaojtnamesuccess'));
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
			
			$this->ModifyData($connection,array((isset($_POST['ojt-ojtid'])?$_POST['ojt-ojtid']:''),
				$_POST['ojt-plantid'],
				$_POST['ojt-employeeid'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['ojt-firstdate'])),
				date(Yii::app()->params['datetodb'], strtotime($_POST['ojt-duedate'])),
				$_POST['ojt-headernote']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	private function ModifyDataojtdet($connection,$arraydata) {		
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertojtdet(:vojtid,:vcriteriaojtid,:vojtval,:vcorrectionval,:vdevisiondesc,:vdepartdesc,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updateojtdet(:vid,:vojtid,:vcriteriaojtid,:vojtval,:vcorrectionval,:vdevisiondesc,:vdepartdesc,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		}
		$command->bindvalue(':vojtid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vcriteriaojtid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vojtval',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vcorrectionval',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vdevisiondesc',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vdepartdesc',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionsaveojtdet() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDataojtdet($connection,array((isset($_POST['ojtdetid'])?$_POST['ojtdetid']:''),
			$_POST['ojtid'],
			$_POST['criteriaojtid'],
				$_POST['ojtval'],
				$_POST['correctionval'],
				$_POST['devisiondesc'],
				$_POST['departdesc']));
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
				$sql = 'call Purgeojt(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();				
				$transaction->commit();
				GetMessage(false,getcatalog('insecriteriaojtnamesuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
		}
		else {
			GetMessage(true,getcatalog('chooseone'));
		}
	}
	public function actionPurgeojtdet() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeojtdet(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,getcatalog('insecriteriaojtnamesuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
		}
		else {
			GetMessage(true,getcatalog('chooseone'));
		}
	}	
	
	public function actionGenerateojtdet() {
		if (!isset($_POST['criteriaojtidid'])) {
			$sql = "select a.ojtdate,concat(c.ojtname,' - ',d.ojt) as ojtname,a.criteriaojtidid,
				date_add('".date(Yii::app()->params['datetodb'], stcriteriaojtnameotime($_POST['date']))."',interval b.paydays day) as duedate
				from ojt a 
				left join criteriaojtid b on b.criteriaojtidid = a.criteriaojtidid 
				left join ojtdet c on c.ojtid = a.ojtid
				left join city d on d.employeeid = c.employeeid
				where a.ojtid = ".$_POST['id']." 
				limit 1";
			$ojtdet = Yii::app()->db->createCommand($sql)->queryRow();
      echo CJSON::encode(array(
        'ojtdate' => $ojtdet['ojtdate'],
        'ojtname' => $ojtdet['ojtname'],
        'criteriaojtidid' => $ojtdet['criteriaojtidid'],
        'duedate' => date(Yii::app()->params['dateviewfromdb'], stcriteriaojtnameotime($ojtdet['duedate'])),
      ));
		} else {
			$sql = "select date_add('".date(Yii::app()->params['datetodb'], stcriteriaojtnameotime($_POST['date']))."',interval paydays day) as duedate
				from criteriaojtid 
				where criteriaojtidid = ".$_POST['criteriaojtidid'];
			$ojtdet = Yii::app()->db->createCommand($sql)->queryRow();
      echo CJSON::encode(array(
        'duedate' => date(Yii::app()->params['dateviewfromdb'], stcriteriaojtnameotime($ojtdet['duedate'])),
      ));
		}
     Yii::app()->end();
  }
	public function actionDownPDF() {
	  parent::actionDownload();
	  $sql = "select a.ojtid,a.ojtno,
						ifnull((a.ojtdate),'-') as ojtdate,
						ifnull((a.bankaccountno),'-') as bankaccountno,
						ifnull((a.ojtname),'-') as ojtname,
						ifnull((a.fullname),'-') as fullname,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from ojt a
						where a.isvendor = 1 ";						
		$ojtid = GetSearchText(array('GET'),'ojtid');
		$ojtno = GetSearchText(array('GET'),'ojtno');
		$ojtname = GetSearchText(array('GET'),'ojtname');
		$fullname = GetSearchText(array('GET'),'fullname');
		$sql .= " and coalesce(a.ojtid,'') like '".$ojtid."' 
			and coalesce(a.ojtno,'') like '".$ojtno."'
			and coalesce(a.ojtname,'') like '".$ojtname."'
			and coalesce(a.fullname,'') like '".$fullname."'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.ojtid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by ojtno asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('ojt');
		$this->pdf->AddPage('P',array(400,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('ojtid'),
																	GetCatalog('ojtno'),
																	GetCatalog('ojtdate'),
																	GetCatalog('bankaccountno'),
																	GetCatalog('ojtname'),
																	GetCatalog('fullname'),																	
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,90,40,55,40,40,80,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L');		
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['ojtid'],$row1['ojtno'],$row1['ojtdate'],$row1['bankaccountno'],$row1['ojtname'],$row1['fullname'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='ojt';
		parent::actionDownxls();
		$sql = "select a.ojtid,a.ojtno,
						ifnull((a.ojtdate),'-') as ojtdate,
						ifnull((a.bankaccountno),'-') as bankaccountno,
						ifnull((a.ojtname),'-') as ojtname,
						ifnull((a.fullname),'-') as fullname,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from ojt a
						where a.isvendor = 1 ";
		$ojtid = GetSearchText(array('GET'),'ojtid');
		$ojtno = GetSearchText(array('GET'),'ojtno');
		$ojtname = GetSearchText(array('GET'),'ojtname');
		$fullname = GetSearchText(array('GET'),'fullname');
		$sql .= " and coalesce(a.ojtid,'') like '".$ojtid."' 
			and coalesce(a.ojtno,'') like '".$ojtno."'
			and coalesce(a.ojtname,'') like '".$ojtname."'
			and coalesce(a.fullname,'') like '".$fullname."'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.ojtid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by ojtno asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('ojtid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('ojtno'))			
			->setCellValueByColumnAndRow(2,2,GetCatalog('ojtdate'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('bankaccountno'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('ojtname'))
			->setCellValueByColumnAndRow(6,2,GetCatalog('fullname'))
			->setCellValueByColumnAndRow(7,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['ojtid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['ojtno'])				
				->setCellValueByColumnAndRow(2, $i+1, $row1['ojtdate'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['bankaccountno'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['ojtname'])
				->setCellValueByColumnAndRow(6, $i+1, $row1['fullname'])
				->setCellValueByColumnAndRow(7, $i+1, $row1['recordstatus']);
			$i+=1;
		}		
		$this->getFooterXLS($this->phpExcel);
	}
}