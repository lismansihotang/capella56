<?php
class EmployeetypeController extends Controller {
	public $menuname = 'employeetype';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$employeetypeid = isset ($_POST['employeetypeid']) ? $_POST['employeetypeid'] : '';
		$employeetypename = isset ($_POST['employeetypename']) ? $_POST['employeetypename'] : '';
		$snro = isset ($_POST['snro']) ? $_POST['snro'] : '';
		$sicksnro = isset ($_POST['sicksnro']) ? $_POST['sicksnro'] : '';
		$sickstatusid = isset ($_POST['sickstatusid']) ? $_POST['sickstatusid'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$employeetypeid = isset ($_GET['q']) ? $_GET['q'] : $employeetypeid;
		$employeetypename = isset ($_GET['q']) ? $_GET['q'] : $employeetypename;
		$snro = isset ($_GET['q']) ? $_GET['q'] : $snro;
		$sicksnro = isset ($_GET['q']) ? $_GET['q'] : $sicksnro;
		$sickstatusid = isset ($_GET['q']) ? $_GET['q'] : $sickstatusid;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.employeetypeid';
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
				->from('employeetype t')
				->leftjoin('snro p','p.snroid=t.snroid')
				->leftjoin('snro q','q.snroid=t.sicksnroid')
				->leftjoin('absstatus r','r.absstatusid=t.sickstatusid')
				->where('(employeetypename like :employeetypename) and 
						(p.description like :snro) and 
						(q.description like :sicksnro)',
						array(':employeetypename'=>'%'.$employeetypename.'%',
						':snro'=>'%'.$snro.'%',
						':sicksnro'=>'%'.$sicksnro.'%',
						))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('employeetype t')
				->leftjoin('snro p','p.snroid=t.snroid')
				->leftjoin('snro q','q.snroid=t.sicksnroid')
				->join('absstatus r','r.absstatusid=t.sickstatusid')
				->where('((employeetypename like :employeetypename) or 
						(p.description like :snro) or 
						(q.description like :sicksnro)) and t.recordstatus=1',
						array(':employeetypename'=>'%'.$employeetypename.'%',
						':snro'=>'%'.$snro.'%',
						':sicksnro'=>'%'.$sicksnro.'%'
						))
				->queryScalar();
		}
		$result['total'] = $cmd;		
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,p.description as snrodesc, q.description as sicksnrodesc,r.longstat')	
				->from('employeetype t')
				->leftjoin('snro p','p.snroid=t.snroid')
				->leftjoin('snro q','q.snroid=t.sicksnroid')
				->leftjoin('absstatus r','r.absstatusid=t.sickstatusid')
				->where('(employeetypename like :employeetypename) and 
						(p.description like :snro) and 
						(q.description like :sicksnro)',
						array(':employeetypename'=>'%'.$employeetypename.'%',
						':snro'=>'%'.$snro.'%',
						':sicksnro'=>'%'.$sicksnro.'%'
						))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,p.description as snrodesc, q.description as sicksnrodesc,r.longstat')	
				->from('employeetype t')
				->leftjoin('snro p','p.snroid=t.snroid')
				->leftjoin('snro q','q.snroid=t.sicksnroid')
				->join('absstatus r','r.absstatusid=t.sickstatusid')
				->where('((employeetypename like :employeetypename) or 
						(p.description like :snro) or 
						(q.description like :sicksnro)) and t.recordstatus=1',
						array(':employeetypename'=>'%'.$employeetypename.'%',
						':snro'=>'%'.$snro.'%',
						':sicksnro'=>'%'.$sicksnro.'%'
						))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'employeetypeid'=>$data['employeetypeid'],
				'employeetypename'=>$data['employeetypename'],
				'snroid'=>$data['snroid'],
				'snrodesc'=>$data['snrodesc'],
				'sicksnroid'=>$data['sicksnroid'],
				'sicksnrodesc'=>$data['sicksnrodesc'],
				'sickstatusid'=>$data['sickstatusid'],
				'sicklongstat'=>$data['longstat'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertemployeetype(:vemployeetypename,:vsnroid,:vsicksnroid,:vsickstatusid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updateemployeetype(:vid,:vemployeetypename,:vsnroid,:vsicksnroid,:vsickstatusid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vemployeetypename',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vsnroid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vsicksnroid',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vsickstatusid',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionSave() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
			$this->ModifyData($connection,array((isset($_POST['employeetypeid'])?$_POST['employeetypeid']:''),
				$_POST['employeetypename'],$_POST['snroid'],$_POST['sicksnroid'],$_POST['sickstatusid'],$_POST['recordstatus']));
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
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeemployeetype(:vid,:vcreatedby)';
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
			GetMessage(true,getcatalog('chooseone'));
		}
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select a.employeetypeid,a.employeetypename,b.description as snro,d.description as sicksnro,
						c.longstat as sickstatus,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from employeetype a
						left join snro b on b.snroid = a.snroid
						left join absstatus c on c.absstatusid = a.sickstatusid
						left join snro d on d.snroid = a.sicksnroid ";
		$employeetypeid = filter_input(INPUT_GET,'employeetypeid');
		$employeetypename = filter_input(INPUT_GET,'employeetypename');
		$sql .= " where coalesce(a.employeetypeid,'') like '%".$employeetypeid."%' 
			and coalesce(a.employeetypename,'') like '%".$employeetypename."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.employeetypeid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by employeetypename asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('employeetype');
		$this->pdf->AddPage('P',array(350,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L');
		$this->pdf->colheader = array(getCatalog('employeetypeid'),
																	getCatalog('employeetypename'),
																	getCatalog('snro'),
																	getCatalog('sicksnro'),
																	getCatalog('sickstatus'),																	
																	getCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,75,75,70,70,25));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['employeetypeid'],$row1['employeetypename'],$row1['snro'],$row1['sicksnro'],$row1['sickstatus'],$row1['recordstatus']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	public function actionDownxls()
	{
		$this->menuname='employeetype';
		parent::actionDownxls();
		$sql = "select a.employeetypeid,a.employeetypename,b.description as snro,d.description as sicksnro,
						c.longstat as sickstatus,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from employeetype a
						left join snro b on b.snroid = a.snroid
						left join absstatus c on c.absstatusid = a.sickstatusid
						left join snro d on d.snroid = a.sicksnroid ";
		$employeetypeid = filter_input(INPUT_GET,'employeetypeid');
		$employeetypename = filter_input(INPUT_GET,'employeetypename');
		$sql .= " where coalesce(a.employeetypeid,'') like '%".$employeetypeid."%' 
			and coalesce(a.employeetypename,'') like '%".$employeetypename."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.employeetypeid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by employeetypename asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['employeetypeid'])
				->setCellValueByColumnAndRow(1,$i,$row1['employeetypename'])							
				->setCellValueByColumnAndRow(2,$i,$row1['snro'])
				->setCellValueByColumnAndRow(3,$i,$row1['sicksnro'])
				->setCellValueByColumnAndRow(4,$i,$row1['sickstatus'])							
				->setCellValueByColumnAndRow(5,$i,$row1['recordstatus']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}