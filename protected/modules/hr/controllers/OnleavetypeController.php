<?php
class OnleavetypeController extends Controller {
	public $menuname = 'onleavetype';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertonleavetype(:vonleavename,:vcutimax,:vcutistart,:vsnroid,:vabsstatusid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updateonleavetype(:vid,:vonleavename,:vcutimax,:vcutistart,:vsnroid,:vabsstatusid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vonleavename',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vcutimax',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcutistart',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vsnroid',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vabsstatusid',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
	}
	public function actionSave() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
			$this->ModifyData($connection,array((isset($_POST['onleavetypeid'])?$_POST['onleavetypeid']:''),
				$_POST['onleavename'],$_POST['cutimax'],$_POST['cutistart'],$_POST['snroid'],$_POST['absstatusid'],
				$_POST['recordstatus']));			
			$command->execute();
			$transaction->commit();
			getmessage(false,getcatalog('insertsuccess'));
		}
		catch (Exception $e) {
			$transaction->rollBack();
			getmessage(true,$e->getMessage());
		}
	}
	public function actionPurge() {
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeonleavetype(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				getmessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollback();
				getmessage(true,$e->getMessage());
			}
		}
		else {
			getmessage(true,getcatalog('chooseone'));
		}
	}
	public function search() {
		header('Content-Type: application/json');
		$onleavetypeid = isset ($_POST['onleavetypeid']) ? $_POST['onleavetypeid'] : '';
		$onleavename = isset ($_POST['onleavename']) ? $_POST['onleavename'] : '';
		$snrodesc = isset ($_POST['snrodesc']) ? $_POST['snrodesc'] : '';
		$absstatus = isset ($_POST['absstatus']) ? $_POST['absstatus'] : '';
		$onleavetypeid = isset ($_GET['q']) ? $_GET['q'] : $onleavetypeid;
		$onleavename = isset ($_GET['q']) ? $_GET['q'] : $onleavename;
		$snrodesc = isset ($_GET['q']) ? $_GET['q'] : $snrodesc;
		$absstatus = isset ($_GET['q']) ? $_GET['q'] : $absstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.onleavetypeid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
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
				->from('onleavetype t')
				->join('snro p','p.snroid=t.snroid')
				->join('absstatus q','q.absstatusid=t.absstatusid')
				->where('(onleavename like :onleavename) and 
						(p.description like :snrodesc) and 
						(q.longstat like :absstatus)',
							array(':onleavename'=>'%'.$onleavename.'%',
									':snrodesc'=>'%'.$snrodesc.'%',
									':absstatus'=>'%'.$absstatus.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('onleavetype t')
				->join('snro p','p.snroid=t.snroid')
				->join('absstatus q','q.absstatusid=t.absstatusid')
				->where('((onleavename like :onleavename) or 
					(p.description like :snrodesc) or 
					(q.longstat like :absstatus)) and t.recordstatus=1',
						array(':onleavename'=>'%'.$onleavename.'%',
								':snrodesc'=>'%'.$snrodesc.'%',
								':absstatus'=>'%'.$absstatus.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('onleavetype t')
				->join('snro p','p.snroid=t.snroid')
				->join('absstatus q','q.absstatusid=t.absstatusid')
				->where('(onleavename like :onleavename) and 
						(p.description like :snrodesc) and 
						(q.longstat like :absstatus)',
						array(':onleavename'=>'%'.$onleavename.'%',
								':snrodesc'=>'%'.$snrodesc.'%',
								':absstatus'=>'%'.$absstatus.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('onleavetype t')
				->join('snro p','p.snroid=t.snroid')
				->join('absstatus q','q.absstatusid=t.absstatusid')
				->where('((onleavename like :onleavename) or 
					(p.description like :snrodesc) or 
					(q.longstat like :absstatus)) and t.recordstatus=1',
					array(':onleavename'=>'%'.$onleavename.'%',
							':snrodesc'=>'%'.$snrodesc.'%',
							':absstatus'=>'%'.$absstatus.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'onleavetypeid'=>$data['onleavetypeid'],
				'onleavename'=>$data['onleavename'],
				'cutimax'=>$data['cutimax'],
				'cutistart'=>$data['cutistart'],
				'snroid'=>$data['snroid'],
				'description'=>$data['description'],
				'absstatusid'=>$data['absstatusid'],
				'longstat'=>$data['longstat'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select a.onleavetypeid,a.onleavename,a.cutimax,a.cutistart,b.description as snro,c.longstat as absstatus,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from onleavetype a 
						left join snro b on b.snroid = a.snroid
						left join absstatus c on c.absstatusid = a.absstatusid ";
		$onleavetypeid = filter_input(INPUT_GET,'onleavetypeid');
		$onleavename = filter_input(INPUT_GET,'onleavename');
		$snrodesc = filter_input(INPUT_GET,'snrodesc');
		$absstatus = filter_input(INPUT_GET,'absstatus');
		$sql .= " where coalesce(a.onleavetypeid,'') like '%".$onleavetypeid."%' 
			and coalesce(a.onleavename,'') like '%".$onleavename."%'
			and coalesce(b.description,'') like '%".$snrodesc."%'
			and coalesce(c.longstat,'') like '%".$absstatus."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.onleavetypeid in (".$_GET['id'].")";
		}
			$sql = $sql . " order by onleavename asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=getCatalog('onleavetype');
		$this->pdf->AddPage('P',array(350,250));
		//masukkan posisi judul
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(getCatalog('onleavetypeid'),
																	getCatalog('onleavename'),
																	getCatalog('cutimax'),
																	getCatalog('cutistart'),
																	getCatalog('snro'),
																	getCatalog('absstatus'),
																	getCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,65,50,50,65,65,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['onleavetypeid'],$row1['onleavename'],$row1['cutimax'],$row1['cutistart'],$row1['snro'],$row1['absstatus'],$row1['recordstatus']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	public function actionDownxls()
	{
		$this->menuname='onleavetype';
		parent::actionDownxls();
		$sql = "select a.onleavetypeid,a.onleavename,a.cutimax,a.cutistart,b.description as snro,c.longstat as absstatus,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from onleavetype a 
						left join snro b on b.snroid = a.snroid
						left join absstatus c on c.absstatusid = a.absstatusid ";
		$onleavetypeid = filter_input(INPUT_GET,'onleavetypeid');
		$onleavename = filter_input(INPUT_GET,'onleavename');
		$snrodesc = filter_input(INPUT_GET,'snrodesc');
		$absstatus = filter_input(INPUT_GET,'absstatus');
		$sql .= " where coalesce(a.onleavetypeid,'') like '%".$onleavetypeid."%' 
			and coalesce(a.onleavename,'') like '%".$onleavename."%'
			and coalesce(b.description,'') like '%".$snrodesc."%'
			and coalesce(c.longstat,'') like '%".$absstatus."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.onleavetypeid in (".$_GET['id'].")";
		}
			$sql = $sql . " order by onleavename asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		
		foreach($dataReader as $row1)
		{
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['onleavetypeid'])
				->setCellValueByColumnAndRow(1,$i,$row1['onleavename'])				
				->setCellValueByColumnAndRow(2,$i,$row1['cutimax'])
				->setCellValueByColumnAndRow(3,$i,$row1['cutistart'])
				->setCellValueByColumnAndRow(4,$i,$row1['snro'])				
				->setCellValueByColumnAndRow(5,$i,$row1['absstatus'])
				->setCellValueByColumnAndRow(6,$i,$row1['recordstatus']);
			$i++;
		}
		
		
		$this->getFooterXLS($this->phpExcel);
	}
}