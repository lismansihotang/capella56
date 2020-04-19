<?php
class DocstnkController extends Controller {
	public $menuname = 'docstnk';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$docid = isset ($_POST['docid']) ? $_POST['docid'] : '';
		$companyname = isset ($_POST['companyname']) ? $_POST['companyname'] : '';
		$namedoc = isset ($_POST['namedoc']) ? $_POST['namedoc'] : '';
		$nodoc = isset ($_POST['nodoc']) ? $_POST['nodoc'] : '';
		$exdate = isset ($_POST['exdate']) ? $_POST['exdate'] : '';
		$cost = isset ($_POST['cost']) ? $_POST['cost'] : '';
		$docupload = isset ($_POST['docupload']) ? $_POST['docupload'] : '';
		$docid = isset ($_GET['q']) ? $_GET['q'] : $docid;
		$companyname = isset ($_GET['q']) ? $_GET['q'] : $companyname;
		$namedoc = isset ($_GET['q']) ? $_GET['q'] : $namedoc;
		$nodoc = isset ($_GET['q']) ? $_GET['q'] : $nodoc;
		$exdate = isset ($_GET['q']) ? $_GET['q'] : $exdate;
		$docupload = isset ($_GET['q']) ? $_GET['q'] : $docupload;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.docid';
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
				->selectdistinct('count(1) as total')	
				->from('docstnk t')
				->join('company p','p.companyid=t.companyid')
				->where('(p.companyname like :companyname) and 
						(namedoc like :namedoc) and 
						(nodoc like :nodoc) 
						',
						array(':companyname'=>'%'.$companyname.'%',
					':namedoc'=>'%'.$namedoc.'%',
					':nodoc'=>'%'.$nodoc.'%'
					))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->selectdistinct('count(1) as total')	
				->from('docstnk t')
				->join('company p','p.companyid=t.companyid')
				->where('(p.companyname like :companyname) or 
						(namedoc like :namedoc) or 
						(nodoc like :nodoc) 
						',
						array(':companyname'=>'%'.$companyname.'%',
					':namedoc'=>'%'.$namedoc.'%',
					':nodoc'=>'%'.$nodoc.'%'
					))
				->queryScalar();
		}
		$result['total'] = $cmd;		
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('docstnk t')
				->join('company p','p.companyid=t.companyid')
				->where('(p.companyname like :companyname) and 
						(namedoc like :namedoc) and 
						(nodoc like :nodoc) 
						',
						array(':companyname'=>'%'.$companyname.'%',
					':namedoc'=>'%'.$namedoc.'%',
					':nodoc'=>'%'.$nodoc.'%'
					))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('docstnk t')
				->join('company p','p.companyid=t.companyid')
				->where('(p.companyname like :companyname) or 
						(namedoc like :namedoc) or 
						(nodoc like :nodoc)  
						',
						array(':companyname'=>'%'.$companyname.'%',
					':namedoc'=>'%'.$namedoc.'%',
					':nodoc'=>'%'.$nodoc.'%'
					))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'docid'=>$data['docid'],
				'companyid'=>$data['companyid'],
				'companyname'=>$data['companyname'],
				'namedoc'=>$data['namedoc'],
				'nodoc'=>$data['nodoc'],
				'exdate'=>date(Yii::app()->params['dateviewfromdb'], strtotime($data['exdate'])),
				'cost'=>Yii::app()->format->formatNumber($data['cost']),
				'docupload'=>$data['docupload'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionSave() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
			if (isset($_POST['isNewRecord'])) {
				$sql = 'call Insertdocstnk(:vcompanyid,:vnamadoc,:vnodoc,:vexdate,:vcost,:vdocupload,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else {
				$sql = 'call Updatedocstnk(:vid,:vcompanyid,:vnamadoc,:vnodoc,:vexdate,:vcost,:vdocupload,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['docid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['docid']);
			}
			$command->bindvalue(':vcompanyid',$_POST['companyid'],PDO::PARAM_STR);
			$command->bindvalue(':vnamadoc',$_POST['namedoc'],PDO::PARAM_STR);
			$command->bindvalue(':vnodoc',$_POST['nodoc'],PDO::PARAM_STR);
			$command->bindvalue(':vexdate',$_POST['exdate'],PDO::PARAM_STR);
			$command->bindvalue(':vcost',$_POST['cost'],PDO::PARAM_STR);
			$command->bindvalue(':vdocupload',$_POST['docupload'],PDO::PARAM_STR);
			$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
			$command->execute();
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
	}
	public function actionPurge() {
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgedocstnk(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollback();
				GetMessage(true,$e->getMessage());
			}
		}
		else {
			GetMessage(true,getcatalog('chooseone'));
		}
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select companyname,jobdesc,namadoc,nodoc,exdate,cost,docupload
				from docstnk a 
				left join company b on b.companyid = a.companyid ";
		$docid = filter_input(INPUT_GET,'docid');
		$companyname = filter_input(INPUT_GET,'companyname');
		$namadoc = filter_input(INPUT_GET,'namadoc');
		$nodoc = filter_input(INPUT_GET,'nodoc');
		$sql .= " where coalesce(a.docid,'') like '%".$docid."%' 
			and coalesce(b.companyname,'') like '%".$companyname."%'
			and coalesce(a.namadoc,'') like '%".$namadoc."%'
			and coalesce(c.nodoc,'') like '%".$nodoc."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.docid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('jobs');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L','L');
		$this->pdf->colheader = array(getCatalog('orgstructureid'),
                getCatalog('jobdesc'),
                getCatalog('qualification'),
                getCatalog('positionid'),
                getCatalog('recordstatus'));
		$this->pdf->setwidths(array(40,40,40,40,40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['orgstructureid'],$row1['jobdesc'],$row1['qualification'],$row1['positionid'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		parent::actionDownxls();
		$sql = "select companyname,jobdesc,namadoc,nodoc,exdate,cost,docupload
				from docstnk a 
				left join company b on b.companyid = a.companyid ";
		$docid = filter_input(INPUT_GET,'docid');
		$companyname = filter_input(INPUT_GET,'companyname');
		$namadoc = filter_input(INPUT_GET,'namadoc');
		$nodoc = filter_input(INPUT_GET,'nodoc');
		$sql .= " where coalesce(a.docid,'') like '%".$docid."%' 
			and coalesce(b.companyname,'') like '%".$companyname."%'
			and coalesce(a.namadoc,'') like '%".$namadoc."%'
			and coalesce(c.nodoc,'') like '%".$nodoc."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.docid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->phpExcel=Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
		$i=1;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,1,getCatalog('orgstructureid'))
			->setCellValueByColumnAndRow(1,1,getCatalog('jobdesc'))
			->setCellValueByColumnAndRow(2,1,getCatalog('qualification'))
			->setCellValueByColumnAndRow(3,1,getCatalog('positionid'))
			->setCellValueByColumnAndRow(4,1,getCatalog('recordstatus'));		
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['orgstructureid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['jobdesc'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['qualification'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['positionid'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['recordstatus']);		
				$i+=1;
		}
		$this->getFooterxls($this->phpExcel);
	}
}