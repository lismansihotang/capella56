<?php
class AbsmealController extends Controller {
	public $menuname = 'absmeal';
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
		$absmealid = GetSearchText(array('POST','Q'),'absmealid');
		$absmealdate = GetSearchText(array('POST','Q'),'absmealdate');
		$mealtypename = GetSearchText(array('POST','Q'),'mealtypename');
		$headernote = GetSearchText(array('POST','Q'),'headernote');
		$page       = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows       = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort       = isset($_POST['sort']) ? strval($_POST['sort']) : 'absmealid';
    $order      = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset     = ($page - 1) * $rows;
    $page       = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows       = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort       = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order      = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		$dependency = new CDbCacheDependency('SELECT MAX(updatedate) FROM absmeal');
		if (!isset($_GET['getdata'])) {
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')
				->from('absmeal t')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('mealtype c','c.mealtypeid = t.mealtypeid')
				->where("((coalesce(absmealdate,'') like :absmealdate) 
				or (coalesce(absmealid,'') like :absmealid)
				or (coalesce(c.mealtypename,'') like :mealtypename)
				) 
				and t.recordstatus = 1",
						array(
						':absmealdate'=>$absmealdate,
						':absmealid'=>$absmealid,
						':mealtypename'=>$mealtypename
						))
				->queryScalar();
		}
		else if (isset($_GET['expedisi'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')
				->from('absmeal t')
				->from('absmeal t')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('mealtype c','c.mealtypeid = t.mealtypeid')
				->where("((coalesce(absmealdate,'') like :absmealdate) 
				or (coalesce(absmealid,'') like :absmealid)
				or (coalesce(c.mealtypename,'') like :mealtypename)
				) 
				and t.recordstatus = 1",
						array(
						':absmealdate'=>$absmealdate,
						':absmealid'=>$absmealid,
						':mealtypename'=>$mealtypename
						))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('count(1) as total')
				->from('absmeal t')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('mealtype c','c.mealtypeid = t.mealtypeid')
				->where("coalesce(absmealid,'') like :absmealid 
					and coalesce(absmealdate,'') like :absmealdate 
					and coalesce(c.mealtypename,'') like :mealtypename
					and t.recordstatus > 0",
						array(
						':absmealid'=>$absmealid,
						':absmealdate'=>$absmealdate,
						':mealtypename'=>$mealtypename
						))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('t.*,c.mealtypename,b.plantcode')			
				->from('absmeal t')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('mealtype c','c.mealtypeid = t.mealtypeid')
				->where("((coalesce(absmealdate,'') like :absmealdate) 
				or (coalesce(absmealid,'') like :absmealid)
				or (coalesce(mealtypename,'') like :mealtypename)
				) 
				and t.recordstatus > 0",
						array(
						':absmealdate'=>$absmealdate,
						':absmealid'=>$absmealid,
						':mealtypename'=>$mealtypename
						))
				->order($sort.' '.$order)
				->queryAll();			
		}
		else if (isset($_GET['expedisi'])) {
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('t.*,c.mealtypename,b.plantcode')			
				->from('absmeal t')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('mealtype c','c.mealtypeid = t.mealtypeid')
				->where("((coalesce(absmealdate,'') like :absmealdate) 
				or (coalesce(absmealid,'') like :absmealid)
				or (coalesce(mealtypename,'') like :mealtypename)
				) 
				 and t.recordstatus = 1",
						array(
						':absmealdate'=>$absmealdate,
						':absmealid'=>$absmealid,
						':mealtypename'=>$mealtypename
						))
				->order($sort.' '.$order)
				->queryAll();			
		}
		else 
		{
			$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
				->select('t.*,c.mealtypename,b.plantcode')			
				->from('absmeal t')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('mealtype c','c.mealtypeid = t.mealtypeid')
				->where("coalesce(absmealid,'') like :absmealid 
					and coalesce(absmealdate,'') like :absmealdate  
					and coalesce(mealtypename,'') like :mealtypename
					and t.recordstatus > 0",
						array(
						':absmealid'=>$absmealid,
						':absmealdate'=>$absmealdate,
						':mealtypename'=>$mealtypename
						))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
			'absmealid'=>$data['absmealid'],
			'plantid'=>$data['plantid'],
			'plantcode'=>$data['plantcode'],
			'headernote'=>$data['headernote'],
			'mealtypeid'=>$data['mealtypeid'],
			'mealtypename'=>$data['mealtypename'],
			'absmealdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['absmealdate'])),
			'recordstatus'=>$data['recordstatus']
			);
			}
			$result = array_merge($result, array(
				'rows' => $row
			));
	} else {
		$absmealid = GetSearchText(array('POST','Q','GET'),'absmealid',0,'int');
			$cmd = Yii::app()->db->createCommand("
				select a.absmealid
				from absmeal a 
				where a.absmealid = ".$absmealid)->queryRow();
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
		$sort = GetSearchText(array('GET'),'sort','absmealdetid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('absmealdet t')
				->join('employee c','c.employeeid = t.employeeid')
				->join('position d','d.positionid = c.positionid')
				->where('absmealid = :abid',
						array(':abid'=>$id))
				->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
				->select('t.*,c.fullname,d.positionname')			
				->from('absmealdet t')
				->join('employee c','c.employeeid = t.employeeid')
				->join('position d','d.positionid = c.positionid')
				->where('absmealid = :abid',
						array(':abid'=>$id))
				->order($sort.' '.$order)
				->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
			'absmealdetid'=>$data['absmealdetid'],
			'absmealid'=>$data['absmealid'],
			'employeeid'=>$data['employeeid'],
			'positionid'=>$data['positionid'],
			'positionname'=>$data['positionname'],
			'fullname'=>$data['fullname'],
			'description'=>$data['description']
			);
		}
		$result=array_merge($result,array('rows'=>$row));;
		echo CJSON::encode($result);
	}
	
	public function actiongetdata() {
		$id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'absmealid' => $id
		));
	}
	private function ModifyData($connection,$arraydata) {	
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		$sql = 'call Modifabsmeal(:vid,:vplantid,:vabsmealdate,:vheadernote,:vmealtypeid,:vrecordstatus,:vcreatedby)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vplantid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vabsmealdate',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vheadernote',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vmealtypeid',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-absmeal"]["name"]);
		if (move_uploaded_file($_FILES["file-absmeal"]["tmp_name"], $target_file)) {
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
					$absmealno = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$abid = Yii::app()->db->createCommand("select absmealid from absmeal where absmealno = '".$absmealno."'")->queryScalar();
					if ($abid == '') {					
						$absmealdate = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
						$bankaccountno = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
						$absmealname = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
						$mealtypename = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
						$recordstatus = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
						$this->ModifyData($connection,array('',$absmealno,$absmealdate,$bankaccountno,$absmealname,$mealtypename,'',$recordstatus));
						//get id absmealid
						$abid = Yii::app()->db->createCommand("select absmealid from absmeal where absmealno = '".$absmealno."'")->queryScalar();
					}
					if ($abid != '') {
						if ($objWorksheet->getCellByColumnAndRow(7, $row)->getValue() != '') {
							$absmealname = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
							$absmealtypeid = Yii::app()->db->createCommand("select absmealtypeid from absmealtype where absmealname = '".$absmealname."'")->queryScalar();
							$absmealname = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
							$evaluasi = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
							$perbaikan = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
							$fullname = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
							$employeeid = Yii::app()->db->createCommand("select employeeid from city where fullname = '".$fullname."'")->queryScalar();
							$penjelasan = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
							$description = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
							$lat = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
							$lng = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
							$this->ModifyDataabsmealdet($connection,array('',$abid,$absmealtypeid,$absmealname,$evaluasi,$perbaikan,$employeeid,$penjelasan,$description,$lat,$lng));
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
			
			$this->ModifyData($connection,array((isset($_POST['absmeal-absmealid'])?$_POST['absmeal-absmealid']:''),
			
				$_POST['absmeal-plantid'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['absmeal-absmealdate'])),
				$_POST['absmeal-headernote'],
				$_POST['absmeal-mealtypeid'],
				isset($_POST['absmeal-recordstatus'])?1:0));
			$transaction->commit();
			GetMessage(false,getcatalog('inseevaluasisuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	private function ModifyDataabsmealdet($connection,$arraydata) {		
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertabsmealdet(:vabsmealid,:vemployeeid,:vdescription,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updateabsmealdet(:vid,:vabsmealid,:vemployeeid,:vdescription,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		}
		$command->bindvalue(':vabsmealid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vemployeeid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionsaveabsmealdet() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDataabsmealdet($connection,array((isset($_POST['absmealdetid'])?$_POST['absmealdetid']:''),
			$_POST['absmealid'],
			$_POST['employeeid'],$_POST['description']));
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
				$sql = 'call Purgeabsmeal(:vid,:vcreatedby)';
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
	public function actionPurgeabsmealdet() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeabsmealdet(:vid,:vcreatedby)';
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
	
	public function actionGenerateabsmealdet() {
		if (!isset($_POST['absmealtypeidid'])) {
			$sql = "select a.absmealdate,concat(c.absmealname,' - ',d.fullname) as absmealname,a.absmealtypeidid,
				date_add('".date(Yii::app()->params['datetodb'], stevaluasiotime($_POST['date']))."',interval b.paydays day) as duedate
				from absmeal a 
				left join absmealtypeid b on b.absmealtypeidid = a.absmealtypeidid 
				left join absmealdet c on c.absmealid = a.absmealid
				left join city d on d.employeeid = c.employeeid
				where a.absmealid = ".$_POST['id']." 
				limit 1";
			$absmealdet = Yii::app()->db->createCommand($sql)->queryRow();
      echo CJSON::encode(array(
        'absmealdate' => $absmealdet['absmealdate'],
        'absmealname' => $absmealdet['absmealname'],
        'absmealtypeidid' => $absmealdet['absmealtypeidid'],
        'duedate' => date(Yii::app()->params['dateviewfromdb'], stevaluasiotime($absmealdet['duedate'])),
      ));
		} else {
			$sql = "select date_add('".date(Yii::app()->params['datetodb'], stevaluasiotime($_POST['date']))."',interval paydays day) as duedate
				from absmealtypeid 
				where absmealtypeidid = ".$_POST['absmealtypeidid'];
			$absmealdet = Yii::app()->db->createCommand($sql)->queryRow();
      echo CJSON::encode(array(
        'duedate' => date(Yii::app()->params['dateviewfromdb'], stevaluasiotime($absmealdet['duedate'])),
      ));
		}
     Yii::app()->end();
  }
	public function actionDownPDF() {
	  parent::actionDownload();
	  $sql = "select a.absmealid,a.absmealno,
						ifnull((a.absmealdate),'-') as absmealdate,
						ifnull((a.bankaccountno),'-') as bankaccountno,
						ifnull((a.absmealname),'-') as absmealname,
						ifnull((a.mealtypename),'-') as mealtypename,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from absmeal a
						where a.isvendor = 1 ";						
		$absmealid = GetSearchText(array('GET'),'absmealid');
		$absmealno = GetSearchText(array('GET'),'absmealno');
		$absmealname = GetSearchText(array('GET'),'absmealname');
		$mealtypename = GetSearchText(array('GET'),'mealtypename');
		$sql .= " and coalesce(a.absmealid,'') like '".$absmealid."' 
			and coalesce(a.absmealno,'') like '".$absmealno."'
			and coalesce(a.absmealname,'') like '".$absmealname."'
			and coalesce(a.mealtypename,'') like '".$mealtypename."'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.absmealid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by absmealno asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('absmeal');
		$this->pdf->AddPage('P',array(400,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('absmealid'),
																	GetCatalog('absmealno'),
																	GetCatalog('absmealdate'),
																	GetCatalog('bankaccountno'),
																	GetCatalog('absmealname'),
																	GetCatalog('mealtypename'),																	
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,90,40,55,40,40,80,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L');		
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['absmealid'],$row1['absmealno'],$row1['absmealdate'],$row1['bankaccountno'],$row1['absmealname'],$row1['mealtypename'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='absmeal';
		parent::actionDownxls();
		$sql = "select a.absmealid,a.absmealno,
						ifnull((a.absmealdate),'-') as absmealdate,
						ifnull((a.bankaccountno),'-') as bankaccountno,
						ifnull((a.absmealname),'-') as absmealname,
						ifnull((a.mealtypename),'-') as mealtypename,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from absmeal a
						where a.isvendor = 1 ";
		$absmealid = GetSearchText(array('GET'),'absmealid');
		$absmealno = GetSearchText(array('GET'),'absmealno');
		$absmealname = GetSearchText(array('GET'),'absmealname');
		$mealtypename = GetSearchText(array('GET'),'mealtypename');
		$sql .= " and coalesce(a.absmealid,'') like '".$absmealid."' 
			and coalesce(a.absmealno,'') like '".$absmealno."'
			and coalesce(a.absmealname,'') like '".$absmealname."'
			and coalesce(a.mealtypename,'') like '".$mealtypename."'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.absmealid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by absmealno asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('absmealid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('absmealno'))			
			->setCellValueByColumnAndRow(2,2,GetCatalog('absmealdate'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('bankaccountno'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('absmealname'))
			->setCellValueByColumnAndRow(6,2,GetCatalog('mealtypename'))
			->setCellValueByColumnAndRow(7,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['absmealid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['absmealno'])				
				->setCellValueByColumnAndRow(2, $i+1, $row1['absmealdate'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['bankaccountno'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['absmealname'])
				->setCellValueByColumnAndRow(6, $i+1, $row1['mealtypename'])
				->setCellValueByColumnAndRow(7, $i+1, $row1['recordstatus']);
			$i+=1;
		}		
		$this->getFooterXLS($this->phpExcel);
	}
}