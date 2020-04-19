<?php
class EmergencyController extends Controller {
	public $menuname = 'emergency';
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
		$emergencyid = GetSearchText(array('POST','Q'),'emergencyid');
		$emergencyno = GetSearchText(array('POST','Q'),'emergencyno');
		$structurename = GetSearchText(array('POST','Q'),'structurename');
		$headernote = GetSearchText(array('POST','Q'),'headernote');
		$page       = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows       = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort       = isset($_POST['sort']) ? strval($_POST['sort']) : 'emergencyid';
    $order      = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset     = ($page - 1) * $rows;
    $page       = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows       = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort       = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order      = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		$dependency = new CDbCacheDependency('SELECT MAX(updatedate) FROM emergency');
		if (!isset($_GET['getdata'])) {
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')
				->from('emergency t')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('orgstructure c','c.orgstructureid = t.orgstructureid')
				->where("((coalesce(emergencyno,'') like :emergencyno) 
				or (coalesce(emergencyid,'') like :emergencyid)
				or (coalesce(c.structurename,'') like :structurename)
				) 
				and t.recordstatus = 1",
						array(
						':emergencyno'=>$emergencyno,
						':emergencyid'=>$emergencyid,
						':structurename'=>$structurename
						))
				->queryScalar();
		}
		else if (isset($_GET['expedisi'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')
				->from('emergency t')
				->from('emergency t')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('orgstructure c','c.orgstructureid = t.orgstructureid')
				->where("((coalesce(emergencyno,'') like :emergencyno) 
				or (coalesce(emergencyid,'') like :emergencyid)
				or (coalesce(c.structurename,'') like :structurename)
				) 
				and t.recordstatus = 1",
						array(
						':emergencyno'=>$emergencyno,
						':emergencyid'=>$emergencyid,
						':structurename'=>$structurename
						))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')
				->from('emergency t')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('orgstructure c','c.orgstructureid = t.orgstructureid')
				->where("coalesce(emergencyid,'') like :emergencyid 
					and coalesce(emergencyno,'') like :emergencyno 
					and coalesce(c.structurename,'') like :structurename
					and t.recordstatus > 0",
						array(
						':emergencyid'=>$emergencyid,
						':emergencyno'=>$emergencyno,
						':structurename'=>$structurename
						))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('t.*,c.structurename,b.plantcode')			
				->from('emergency t')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('orgstructure c','c.orgstructureid = t.orgstructureid')
				->where("((coalesce(emergencyno,'') like :emergencyno) 
				or (coalesce(emergencyid,'') like :emergencyid)
				or (coalesce(structurename,'') like :structurename)
				) 
				and t.recordstatus > 0",
						array(
						':emergencyno'=>$emergencyno,
						':emergencyid'=>$emergencyid,
						':structurename'=>$structurename
						))
				->order($sort.' '.$order)
				->queryAll();			
		}
		else if (isset($_GET['expedisi'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('t.*,c.structurename,b.plantcode')			
				->from('emergency t')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('orgstructure c','c.orgstructureid = t.orgstructureid')
				->where("((coalesce(emergencyno,'') like :emergencyno) 
				or (coalesce(emergencyid,'') like :emergencyid)
				or (coalesce(structurename,'') like :structurename)
				) 
				 and t.recordstatus = 1",
						array(
						':emergencyno'=>$emergencyno,
						':emergencyid'=>$emergencyid,
						':structurename'=>$structurename
						))
				->order($sort.' '.$order)
				->queryAll();			
		}
		else 
		{
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('t.*,c.structurename,b.plantcode')			
				->from('emergency t')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('orgstructure c','c.orgstructureid = t.orgstructureid')
				->where("coalesce(emergencyid,'') like :emergencyid 
					and coalesce(emergencyno,'') like :emergencyno  
					and coalesce(structurename,'') like :structurename
					and t.recordstatus > 0",
						array(
						':emergencyid'=>$emergencyid,
						':emergencyno'=>$emergencyno,
						':structurename'=>$structurename
						))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
			'emergencyid'=>$data['emergencyid'],
			'plantid'=>$data['plantid'],
			'plantcode'=>$data['plantcode'],
			'emergencyno'=>$data['emergencyno'],
			'headernote'=>$data['headernote'],
			'emergencydate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['emergencydate'])),
			'orgstructureid'=>$data['orgstructureid'],
			'structurename'=>$data['structurename'],
			'recordstatus'=>$data['recordstatus'],
			);
			}
			$result = array_merge($result, array(
				'rows' => $row
			));
	} else {
		$emergencyid = GetSearchText(array('POST','Q','GET'),'emergencyid',0,'int');
			$cmd = Yii::app()->db->createCommand("
				select a.emergencyid
				from emergency a 
				where a.emergencyid = ".$emergencyid)->queryRow();
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
		$sort = GetSearchText(array('GET'),'sort','emergencydetid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('emergencydet t')
				->join('emergencytype b','b.emergencytypeid = t.emergencytypeid')
				->join('employee c','c.employeeid = t.employeeid')
				->where('emergencyid = :abid',
						array(':abid'=>$id))
				->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
				->select('t.*,b.emergencyname,b.reporttype,c.fullname')			
				->from('emergencydet t')
				->join('emergencytype b','b.emergencytypeid = t.emergencytypeid')
				->join('employee c','c.employeeid = t.employeeid')
				->where('emergencyid = :abid',
						array(':abid'=>$id))
				->order($sort.' '.$order)
				->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
			'emergencydetid'=>$data['emergencydetid'],
			'emergencyid'=>$data['emergencyid'],
			'emergencyname'=>$data['emergencyname'],
			'reporttype'=>$data['reporttype'],
			'emergencytypeid'=>$data['emergencytypeid'],
			'evaluasi'=>$data['evaluasi'],
			'perbaikan'=>$data['perbaikan'],
			'employeeid'=>$data['employeeid'],
			'fullname'=>$data['fullname'],
			'penjelasan'=>$data['penjelasan'],
			'description'=>$data['description']
			);
		}
		$result=array_merge($result,array('rows'=>$row));;
		echo CJSON::encode($result);
	}
	
	public function actiongetdata() {
		$id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'emergencyid' => $id
		));
	}
	private function ModifyData($connection,$arraydata) {	
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		$sql = 'call Modifemergency(:vid,:vplantid,:vemergencyno,:vemergencydate,:vheadernote,:vorgstructureid,:vrecordstatus,:vcreatedby)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vplantid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vemergencyno',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vemergencydate',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vheadernote',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vorgstructureid',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-emergency"]["name"]);
		if (move_uploaded_file($_FILES["file-emergency"]["tmp_name"], $target_file)) {
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
					$emergencyno = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$abid = Yii::app()->db->createCommand("select emergencyid from emergency where emergencyno = '".$emergencyno."'")->queryScalar();
					if ($abid == '') {					
						$emergencydate = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
						$bankaccountno = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
						$emergencyname = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
						$structurename = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
						$recordstatus = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
						$this->ModifyData($connection,array('',$emergencyno,$emergencydate,$bankaccountno,$emergencyname,$structurename,'',$recordstatus));
						//get id emergencyid
						$abid = Yii::app()->db->createCommand("select emergencyid from emergency where emergencyno = '".$emergencyno."'")->queryScalar();
					}
					if ($abid != '') {
						if ($objWorksheet->getCellByColumnAndRow(7, $row)->getValue() != '') {
							$emergencyname = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
							$emergencytypeid = Yii::app()->db->createCommand("select emergencytypeid from emergencytype where emergencyname = '".$emergencyname."'")->queryScalar();
							$emergencyname = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
							$evaluasi = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
							$perbaikan = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
							$fullname = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
							$employeeid = Yii::app()->db->createCommand("select employeeid from city where fullname = '".$fullname."'")->queryScalar();
							$penjelasan = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
							$description = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
							$lat = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
							$lng = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
							$this->ModifyDataemergencydet($connection,array('',$abid,$emergencytypeid,$emergencyname,$evaluasi,$perbaikan,$employeeid,$penjelasan,$description,$lat,$lng));
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
				GetMessage(false,getcatalog('inseevaluasisuccess'));
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
			
			$this->ModifyData($connection,array((isset($_POST['emergency-emergencyid'])?$_POST['emergency-emergencyid']:''),
			
				$_POST['emergency-plantid'],
				$_POST['emergency-emergencyno'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['emergency-emergencydate'])),
				$_POST['emergency-headernote'],
				$_POST['emergency-orgstructureid'],
				isset($_POST['emergency-recordstatus'])?1:0));
			$transaction->commit();
			GetMessage(false,getcatalog('inseevaluasisuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	private function ModifyDataemergencydet($connection,$arraydata) {		
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertemergencydet(:vemergencyid,:vemergencytypeid,:vevaluasi,:vperbaikan,:vemployeeid,:vpenjelasan,:vdescription,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updateemergencydet(:vid,:vemergencyid,:vemergencytypeid,:vevaluasi,:vperbaikan,:vemployeeid,:vpenjelasan,:vdescription,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		}
		$command->bindvalue(':vemergencyid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vemergencytypeid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vevaluasi',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vperbaikan',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vemployeeid',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vpenjelasan',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionsaveemergencydet() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDataemergencydet($connection,array((isset($_POST['emergencydetid'])?$_POST['emergencydetid']:''),
			$_POST['emergencyid'],
			$_POST['emergencytypeid'],
				$_POST['evaluasi'],$_POST['perbaikan'],$_POST['employeeid'],$_POST['penjelasan'],$_POST['description']));
			$transaction->commit();
			GetMessage(false,getcatalog('inseevaluasisuccess'));
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
				$sql = 'call Purgeemergency(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();				
				$transaction->commit();
				GetMessage(false,getcatalog('inseevaluasisuccess'));
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
	public function actionPurgeemergencydet() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeemergencydet(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,getcatalog('inseevaluasisuccess'));
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
	
	public function actionGenerateemergencydet() {
		if (!isset($_POST['emergencytypeidid'])) {
			$sql = "select a.emergencydate,concat(c.emergencyname,' - ',d.fullname) as emergencyname,a.emergencytypeidid,
				date_add('".date(Yii::app()->params['datetodb'], stevaluasiotime($_POST['date']))."',interval b.paydays day) as duedate
				from emergency a 
				left join emergencytypeid b on b.emergencytypeidid = a.emergencytypeidid 
				left join emergencydet c on c.emergencyid = a.emergencyid
				left join city d on d.employeeid = c.employeeid
				where a.emergencyid = ".$_POST['id']." 
				limit 1";
			$emergencydet = Yii::app()->db->createCommand($sql)->queryRow();
      echo CJSON::encode(array(
        'emergencydate' => $emergencydet['emergencydate'],
        'emergencyname' => $emergencydet['emergencyname'],
        'emergencytypeidid' => $emergencydet['emergencytypeidid'],
        'duedate' => date(Yii::app()->params['dateviewfromdb'], stevaluasiotime($emergencydet['duedate'])),
      ));
		} else {
			$sql = "select date_add('".date(Yii::app()->params['datetodb'], stevaluasiotime($_POST['date']))."',interval paydays day) as duedate
				from emergencytypeid 
				where emergencytypeidid = ".$_POST['emergencytypeidid'];
			$emergencydet = Yii::app()->db->createCommand($sql)->queryRow();
      echo CJSON::encode(array(
        'duedate' => date(Yii::app()->params['dateviewfromdb'], stevaluasiotime($emergencydet['duedate'])),
      ));
		}
     Yii::app()->end();
  }
	public function actionDownPDF() {
	  parent::actionDownload();
	  $sql = "select a.emergencyid,a.emergencyno,
						ifnull((a.emergencydate),'-') as emergencydate,
						ifnull((a.bankaccountno),'-') as bankaccountno,
						ifnull((a.emergencyname),'-') as emergencyname,
						ifnull((a.structurename),'-') as structurename,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from emergency a
						where a.isvendor = 1 ";						
		$emergencyid = GetSearchText(array('GET'),'emergencyid');
		$emergencyno = GetSearchText(array('GET'),'emergencyno');
		$emergencyname = GetSearchText(array('GET'),'emergencyname');
		$structurename = GetSearchText(array('GET'),'structurename');
		$sql .= " and coalesce(a.emergencyid,'') like '".$emergencyid."' 
			and coalesce(a.emergencyno,'') like '".$emergencyno."'
			and coalesce(a.emergencyname,'') like '".$emergencyname."'
			and coalesce(a.structurename,'') like '".$structurename."'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.emergencyid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by emergencyno asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('emergency');
		$this->pdf->AddPage('P',array(400,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('emergencyid'),
																	GetCatalog('emergencyno'),
																	GetCatalog('emergencydate'),
																	GetCatalog('bankaccountno'),
																	GetCatalog('emergencyname'),
																	GetCatalog('structurename'),																	
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,90,40,55,40,40,80,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L');		
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['emergencyid'],$row1['emergencyno'],$row1['emergencydate'],$row1['bankaccountno'],$row1['emergencyname'],$row1['structurename'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='emergency';
		parent::actionDownxls();
		$sql = "select a.emergencyid,a.emergencyno,
						ifnull((a.emergencydate),'-') as emergencydate,
						ifnull((a.bankaccountno),'-') as bankaccountno,
						ifnull((a.emergencyname),'-') as emergencyname,
						ifnull((a.structurename),'-') as structurename,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from emergency a
						where a.isvendor = 1 ";
		$emergencyid = GetSearchText(array('GET'),'emergencyid');
		$emergencyno = GetSearchText(array('GET'),'emergencyno');
		$emergencyname = GetSearchText(array('GET'),'emergencyname');
		$structurename = GetSearchText(array('GET'),'structurename');
		$sql .= " and coalesce(a.emergencyid,'') like '".$emergencyid."' 
			and coalesce(a.emergencyno,'') like '".$emergencyno."'
			and coalesce(a.emergencyname,'') like '".$emergencyname."'
			and coalesce(a.structurename,'') like '".$structurename."'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.emergencyid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by emergencyno asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('emergencyid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('emergencyno'))			
			->setCellValueByColumnAndRow(2,2,GetCatalog('emergencydate'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('bankaccountno'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('emergencyname'))
			->setCellValueByColumnAndRow(6,2,GetCatalog('structurename'))
			->setCellValueByColumnAndRow(7,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['emergencyid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['emergencyno'])				
				->setCellValueByColumnAndRow(2, $i+1, $row1['emergencydate'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['bankaccountno'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['emergencyname'])
				->setCellValueByColumnAndRow(6, $i+1, $row1['structurename'])
				->setCellValueByColumnAndRow(7, $i+1, $row1['recordstatus']);
			$i+=1;
		}		
		$this->getFooterXLS($this->phpExcel);
	}
}