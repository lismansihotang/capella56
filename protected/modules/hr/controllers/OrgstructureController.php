<?php
class OrgstructureController extends Controller {
	public $menuname = 'orgstructure';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$orgstructureid = isset ($_POST['orgstructureid']) ? $_POST['orgstructureid'] : '';
		$structurename = isset ($_POST['structurename']) ? $_POST['structurename'] : '';
		$companyname = isset ($_POST['companyname']) ? $_POST['companyname'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$orgstructureid = isset ($_GET['q']) ? $_GET['q'] : $orgstructureid;
		$structurename = isset ($_GET['q']) ? $_GET['q'] : $structurename;
		$companyname = isset ($_GET['q']) ? $_GET['q'] : $companyname;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.orgstructureid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
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
				->from('orgstructure t')
				->leftjoin('orgstructure p','t.parentid=p.orgstructureid')
				->leftjoin('company c','c.companyid=t.companyid')
				->where('(t.structurename like :structurename) and 
					(c.companyname like :companyname)',
					array(':structurename'=>'%'.$structurename.'%',
					':companyname'=>'%'.$companyname.'%'))
				->queryScalar();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->selectdistinct('count(1) as total')	
				->from('orgstructure t')
				->leftjoin('orgstructure p','t.parentid=p.orgstructureid')
				->leftjoin('company c','c.companyid=t.companyid')
				->where('((t.structurename like :structurename) or 
						(c.companyname like :companyname)) and t.recordstatus=1',
						array(':structurename'=>'%'.$structurename.'%',
						':companyname'=>'%'.$companyname.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,c.companyname,t.recordstatus,p.structurename as parentname,p.orgstructureid as parentid')	
				->from('orgstructure t')
				->leftjoin('orgstructure p','t.parentid=p.orgstructureid')
				->leftjoin('company c','c.companyid=t.companyid')
				->where('(t.structurename like :structurename) and 
					(c.companyname like :companyname)',
					array(':structurename'=>'%'.$structurename.'%',
					':companyname'=>'%'.$companyname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->selectdistinct('t.*,c.companyname,p.structurename as parentname,p.orgstructureid as parentid')	
				->from('orgstructure t')
				->leftjoin('orgstructure p','t.parentid=p.orgstructureid')
				->leftjoin('company c','c.companyid=t.companyid')
				->where('((t.structurename like :structurename) or 
					(c.companyname like :companyname)) and t.recordstatus=1',
					array(':structurename'=>'%'.$structurename.'%',
					':companyname'=>'%'.$companyname.'%'))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'orgstructureid'=>$data['orgstructureid'],
				'structurename'=>$data['structurename'],
				'parentid'=>$data['parentid'],
				'parentname'=>$data['parentname'],
				'companyid'=>$data['companyid'],
				'companyname'=>$data['companyname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertorgstructure(:vstructurename,:vparentid,:vcompanyid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateorgstructure(:vid,:vstructurename,:vparentid,:vcompanyid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vstructurename',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vparentid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcompanyid',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-orgstructure"]["name"]);
		if (move_uploaded_file($_FILES["file-orgstructure"]["tmp_name"], $target_file)) {
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
					$structurename = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$parentname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$parentid = Yii::app()->db->createCommand("select orgstructureid from orgstructure where structurename = '".$parentname."'")->queryScalar();
					$companycode = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$this->ModifyData($connection,array($id,$structurename,$parentid,$companyid,$recordstatus));
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
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['orgstructureid'])?$_POST['orgstructureid']:''),
				$_POST['structurename'],$_POST['parentid'],$_POST['companyid'],$_POST['recordstatus']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	public function actionPurge() {
		header('Content-Type: application/json');
		if (isset($_POST['id']))	{
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeorgstructure(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
		}
		else {
			getmessage(true,getcatalog('chooseone'));
		}
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select a.orgstructureid,b.companyname,a.structurename,
						(select z.structurename from orgstructure z where z.orgstructureid = a.parentid ) as parent,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from orgstructure a
						left join company b on b.companyid = a.companyid ";
		$orgstructureid = filter_input(INPUT_GET,'orgstructureid');
		$companyname = filter_input(INPUT_GET,'companyname');
		$structurename = filter_input(INPUT_GET,'structurename');
		$sql .= " where coalesce(a.orgstructureid,'') like '%".$orgstructureid."%' 
			and coalesce(b.companyname,'') like '%".$companyname."%'
			and coalesce(a.structurename,'') like '%".$structurename."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.orgstructureid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by companyname asc, structurename asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('orgstructure');
		$this->pdf->AddPage('P',array(350,250));
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->colalign = array('L','L','L','L','L');
		$this->pdf->colheader = array(getCatalog('orgstructureid'),
																	getCatalog('companyname'),
																	getCatalog('structurename'),
																	getCatalog('parent'),
																	getCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,110,95,95,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',8);
		$this->pdf->coldetailalign = array('L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['orgstructureid'],$row1['companyname'],$row1['structurename'],$row1['parent'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='orgstructure';
		parent::actionDownxls();
		$sql = "select a.orgstructureid,b.companyname,a.structurename,
						(select z.structurename from orgstructure z where z.orgstructureid = a.parentid ) as parent,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from orgstructure a
						left join company b on b.companyid = a.companyid ";
		$orgstructureid = filter_input(INPUT_GET,'orgstructureid');
		$companyname = filter_input(INPUT_GET,'companyname');
		$structurename = filter_input(INPUT_GET,'structurename');
		$sql .= " where coalesce(a.orgstructureid,'') like '%".$orgstructureid."%' 
			and coalesce(b.companyname,'') like '%".$companyname."%'
			and coalesce(a.structurename,'') like '%".$structurename."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.orgstructureid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by companyname asc, structurename asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['orgstructureid'])
				->setCellValueByColumnAndRow(1,$i,$row1['companyname'])							
				->setCellValueByColumnAndRow(2,$i,$row1['structurename'])
				->setCellValueByColumnAndRow(3,$i,$row1['parent'])
				->setCellValueByColumnAndRow(4,$i,$row1['recordstatus']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}