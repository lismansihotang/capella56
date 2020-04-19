<?php
class EmployeescheduleController extends Controller {
	public $menuname = 'employeeschedule';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertemployeeschedule(:vemployeeid,:vmonth,:vyear,:vd1,:vd2,:vd3,:vd4,:vd5,:vd6,:vd7,:vd8,:vd9,:vd10,:vd11,:vd12,:vd13,:vd14,:vd15,:vd16,:vd17,:vd18,:vd19,:vd20,:vd21,:vd22,:vd23,:vd24,:vd25,:vd26,:vd27,:vd28,:vd29,:vd30,:vd31,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updateemployeeschedule(:vid,:vemployeeid,:vmonth,:vyear,:vd1,:vd2,:vd3,:vd4,:vd5,:vd6,:vd7,:vd8,:vd9,:vd10,:vd11,:vd12,:vd13,:vd14,:vd15,:vd16,:vd17,:vd18,:vd19,:vd20,:vd21,:vd22,:vd23,:vd24,:vd25,:vd26,:vd27,:vd28,:vd29,:vd30,:vd31,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vemployeeid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vmonth',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vyear',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vd1',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vd2',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vd3',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vd4',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vd5',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vd6',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vd7',$arraydata[10],PDO::PARAM_STR);
		$command->bindvalue(':vd8',$arraydata[11],PDO::PARAM_STR);
		$command->bindvalue(':vd9',$arraydata[12],PDO::PARAM_STR);
		$command->bindvalue(':vd10',$arraydata[13],PDO::PARAM_STR);
		$command->bindvalue(':vd11',$arraydata[14],PDO::PARAM_STR);
		$command->bindvalue(':vd12',$arraydata[15],PDO::PARAM_STR);
		$command->bindvalue(':vd13',$arraydata[16],PDO::PARAM_STR);
		$command->bindvalue(':vd14',$arraydata[17],PDO::PARAM_STR);
		$command->bindvalue(':vd15',$arraydata[18],PDO::PARAM_STR);
		$command->bindvalue(':vd16',$arraydata[19],PDO::PARAM_STR);
		$command->bindvalue(':vd17',$arraydata[20],PDO::PARAM_STR);
		$command->bindvalue(':vd18',$arraydata[21],PDO::PARAM_STR);
		$command->bindvalue(':vd19',$arraydata[22],PDO::PARAM_STR);
		$command->bindvalue(':vd20',$arraydata[23],PDO::PARAM_STR);
		$command->bindvalue(':vd21',$arraydata[24],PDO::PARAM_STR);
		$command->bindvalue(':vd22',$arraydata[25],PDO::PARAM_STR);
		$command->bindvalue(':vd23',$arraydata[26],PDO::PARAM_STR);
		$command->bindvalue(':vd24',$arraydata[27],PDO::PARAM_STR);
		$command->bindvalue(':vd25',$arraydata[28],PDO::PARAM_STR);
		$command->bindvalue(':vd26',$arraydata[29],PDO::PARAM_STR);
		$command->bindvalue(':vd27',$arraydata[30],PDO::PARAM_STR);
		$command->bindvalue(':vd28',$arraydata[31],PDO::PARAM_STR);
		$command->bindvalue(':vd29',$arraydata[32],PDO::PARAM_STR);
		$command->bindvalue(':vd30',$arraydata[33],PDO::PARAM_STR);
		$command->bindvalue(':vd31',$arraydata[34],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-employeeschedule"]["name"]);
		if (move_uploaded_file($_FILES["file-employeeschedule"]["tmp_name"], $target_file)) {
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
					$employee = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$employeeid = Yii::app()->db->createCommand("select employeeid from employee where fullname = '".$employee."'")->queryScalar();
					$month = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$year = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$sched = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$d1 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$d2 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$d3 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$d4 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$d5 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
					$d6 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
					$d7 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
					$d8 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
					$d9 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
					$d10 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
					$d11 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
					$d12 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
					$d13 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
					$d14 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
					$d15 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(19, $row)->getValue();
					$d16 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(20, $row)->getValue();
					$d17 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(21, $row)->getValue();
					$d18 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(22, $row)->getValue();
					$d19 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(23, $row)->getValue();
					$d20 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(24, $row)->getValue();
					$d21 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(25, $row)->getValue();
					$d22 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(26, $row)->getValue();
					$d23 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(27, $row)->getValue();
					$d24 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(28, $row)->getValue();
					$d25 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(29, $row)->getValue();
					$d26 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(30, $row)->getValue();
					$d27 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(31, $row)->getValue();
					$d28 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(32, $row)->getValue();
					$d29 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(33, $row)->getValue();
					$d30 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$sched = $objWorksheet->getCellByColumnAndRow(34, $row)->getValue();
					$d31 = Yii::app()->db->createCommand("select absscheduleid from absschedule where absschedulename = '".$sched."'")->queryScalar();
					$this->ModifyData($connection,array($id,$employeeid,$month,$year,$d1,$d2,$d3,$d4,$d5,$d6,$d7,$d8,$d9,$d10,
						$d11,$d12,$d13,$d14,$d15,$d16,$d17,$d18,$d19,$d20,$d21,$d22,$d23,$d24,$d25,$d26,$d27,$d28,$d29,$d30,$d31));
				}
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollBack();
				GetMessage(true,$e->getMessage());
			}
    }
	}
	public function actionSave() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
			$this->ModifyData($connection,array((isset($_POST['employeescheduleid'])?$_POST['employeescheduleid']:''),
				$_POST['employeeid'],$_POST['month'],$_POST['year'],$_POST['d1'],$_POST['d2'],$_POST['d3'],$_POST['d4']
				,$_POST['d5'],$_POST['d6'],$_POST['d7'],$_POST['d8'],$_POST['d9'],$_POST['d10'],$_POST['d11'],$_POST['d12']
				,$_POST['d13'],$_POST['d14'],$_POST['d15'],$_POST['d16'],$_POST['d17'],$_POST['d18'],$_POST['d19'],$_POST['d20']
				,$_POST['d21'],$_POST['d22'],$_POST['d23'],$_POST['d24'],$_POST['d25'],$_POST['d26'],$_POST['d27'],$_POST['d28']
				,$_POST['d29'],$_POST['d30'],$_POST['d31']));
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
			try
			{
				$sql = 'call Purgeemployeeschedule(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				foreach($id as $ids) {
					$command->bindvalue(':vid',$ids,PDO::PARAM_STR);
					$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
					$command->execute();
				}
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
  public function actionDelete() {
		parent::actionDelete();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call DeleteGI(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				foreach($id as $ids) {
					$command->bindvalue(':vid',$ids,PDO::PARAM_STR);
					$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
					$command->execute();
				}
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
	public function actionApprove() {
		parent::actionApprove();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call ApproveEmployeeSchedule(:vemployeescheduleid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				foreach($id as $ids) {
					$command->bindvalue(':vemployeescheduleid',$ids,PDO::PARAM_STR);
					$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
					$command->execute();
				}
				$transaction->commit();
				getmessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollback();
				getmessage(true,$e->getMessage());
			}
		}
		else
		{
			getmessage(true,getcatalog('chooseone'));
		}
	}
	public function search() {
		header('Content-Type: application/json');
		$employeescheduleid = isset ($_POST['employeescheduleid']) ? $_POST['employeescheduleid'] : '';
		$employeename = isset ($_POST['employeename']) ? $_POST['employeename'] : '';
		$month = isset ($_POST['month']) ? $_POST['month'] : '';
		$year = isset ($_POST['year']) ? $_POST['year'] : '';
		$employeescheduleid = isset ($_GET['q']) ? $_GET['q'] : $employeescheduleid;
		$employeename = isset ($_GET['q']) ? $_GET['q'] : $employeename;
		$month = isset ($_GET['q']) ? $_GET['q'] : $month;
		$year = isset ($_GET['q']) ? $_GET['q'] : $year;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'zz.employeescheduleid';
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
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('employeeschedule zz')
			->leftjoin('employee pp','pp.employeeid=zz.employeeid')
			->leftjoin('absschedule a','a.absscheduleid=zz.d1')
			->leftjoin('absschedule b','b.absscheduleid=zz.d2')
			->leftjoin('absschedule c','c.absscheduleid=zz.d3')
			->leftjoin('absschedule d','d.absscheduleid=zz.d4')
			->leftjoin('absschedule e','e.absscheduleid=zz.d5')
			->leftjoin('absschedule f','f.absscheduleid=zz.d6')
			->leftjoin('absschedule g','g.absscheduleid=zz.d7')
			->leftjoin('absschedule h','h.absscheduleid=zz.d8')
			->leftjoin('absschedule i','i.absscheduleid=zz.d9')
			->leftjoin('absschedule j','j.absscheduleid=zz.d10')
			->leftjoin('absschedule k','k.absscheduleid=zz.d11')
			->leftjoin('absschedule l','l.absscheduleid=zz.d12')
			->leftjoin('absschedule m','m.absscheduleid=zz.d13')
			->leftjoin('absschedule n','n.absscheduleid=zz.d14')
			->leftjoin('absschedule o','o.absscheduleid=zz.d15')
			->leftjoin('absschedule p','p.absscheduleid=zz.d16')
			->leftjoin('absschedule q','q.absscheduleid=zz.d17')
			->leftjoin('absschedule r','r.absscheduleid=zz.d18')
			->leftjoin('absschedule s','s.absscheduleid=zz.d19')
			->leftjoin('absschedule t','t.absscheduleid=zz.d20')
			->leftjoin('absschedule u','u.absscheduleid=zz.d21')
			->leftjoin('absschedule v','v.absscheduleid=zz.d22')
			->leftjoin('absschedule w','w.absscheduleid=zz.d23')
			->leftjoin('absschedule x','x.absscheduleid=zz.d24')
			->leftjoin('absschedule y','y.absscheduleid=zz.d25')
			->leftjoin('absschedule z','z.absscheduleid=zz.d26')
			->leftjoin('absschedule aa','aa.absscheduleid=zz.d27')
			->leftjoin('absschedule ab','ab.absscheduleid=zz.d28')
			->leftjoin('absschedule ac','ac.absscheduleid=zz.d29')
			->leftjoin('absschedule ad','ad.absscheduleid=zz.d30')
			->leftjoin('absschedule ae','ae.absscheduleid=zz.d31')
			->where('((pp.fullname like :employeename) and 
							(month like :month) and
							(year like :year)) and zz.recordstatus > 0',
							array(':employeename'=>'%'.$employeename.'%',
									':month'=>'%'.$month.'%',
									':year'=>'%'.$year.'%'))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('zz.*,pp.fullname as fullname,a.absschedulename as d1name,b.absschedulename as d2name,c.absschedulename as d3name,d.absschedulename as d4name,
							e.absschedulename as d5name,f.absschedulename as d6name,g.absschedulename as d7name,h.absschedulename as d8name,
							i.absschedulename as d9name,j.absschedulename as d10name,k.absschedulename as d11name,l.absschedulename as d12name,
							m.absschedulename as d13name,n.absschedulename as d14name,o.absschedulename as d15name,p.absschedulename as d16name,
							q.absschedulename as d17name,r.absschedulename as d18name,s.absschedulename as d19name,t.absschedulename as d20name,
							u.absschedulename as d21name,v.absschedulename as d22name,w.absschedulename as d23name,x.absschedulename as d24name,
							y.absschedulename as d25name,z.absschedulename as d26name,aa.absschedulename as d27name,ab.absschedulename as d28name,
							ac.absschedulename as d29name,ad.absschedulename as d30name,ae.absschedulename as d31name')	
			->from('employeeschedule zz')
			->leftjoin('employee pp','pp.employeeid=zz.employeeid')
			->leftjoin('absschedule a','a.absscheduleid=zz.d1')
			->leftjoin('absschedule b','b.absscheduleid=zz.d2')
			->leftjoin('absschedule c','c.absscheduleid=zz.d3')
			->leftjoin('absschedule d','d.absscheduleid=zz.d4')
			->leftjoin('absschedule e','e.absscheduleid=zz.d5')
			->leftjoin('absschedule f','f.absscheduleid=zz.d6')
			->leftjoin('absschedule g','g.absscheduleid=zz.d7')
			->leftjoin('absschedule h','h.absscheduleid=zz.d8')
			->leftjoin('absschedule i','i.absscheduleid=zz.d9')
			->leftjoin('absschedule j','j.absscheduleid=zz.d10')
			->leftjoin('absschedule k','k.absscheduleid=zz.d11')
			->leftjoin('absschedule l','l.absscheduleid=zz.d12')
			->leftjoin('absschedule m','m.absscheduleid=zz.d13')
			->leftjoin('absschedule n','n.absscheduleid=zz.d14')
			->leftjoin('absschedule o','o.absscheduleid=zz.d15')
			->leftjoin('absschedule p','p.absscheduleid=zz.d16')
			->leftjoin('absschedule q','q.absscheduleid=zz.d17')
			->leftjoin('absschedule r','r.absscheduleid=zz.d18')
			->leftjoin('absschedule s','s.absscheduleid=zz.d19')
			->leftjoin('absschedule t','t.absscheduleid=zz.d20')
			->leftjoin('absschedule u','u.absscheduleid=zz.d21')
			->leftjoin('absschedule v','v.absscheduleid=zz.d22')
			->leftjoin('absschedule w','w.absscheduleid=zz.d23')
			->leftjoin('absschedule x','x.absscheduleid=zz.d24')
			->leftjoin('absschedule y','y.absscheduleid=zz.d25')
			->leftjoin('absschedule z','z.absscheduleid=zz.d26')
			->leftjoin('absschedule aa','aa.absscheduleid=zz.d27')
			->leftjoin('absschedule ab','ab.absscheduleid=zz.d28')
			->leftjoin('absschedule ac','ac.absscheduleid=zz.d29')
			->leftjoin('absschedule ad','ad.absscheduleid=zz.d30')
			->leftjoin('absschedule ae','ae.absscheduleid=zz.d31')
			->where('((pp.fullname like :employeename) and  
					(month like :month) and
					(year like :year)) and zz.recordstatus > 0',
					array(':employeename'=>'%'.$employeename.'%',
							':month'=>'%'.$month.'%',
							':year'=>'%'.$year.'%'))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		
		foreach($cmd as $data) {	
			$row[] = array(
				'employeescheduleid'=>$data['employeescheduleid'],
				'employeeid'=>$data['employeeid'],
				'fullname'=>$data['fullname'],
				'month'=>$data['month'],
				'year'=>$data['year'],
				'd1'=>$data['d1'],
				'd1name'=>$data['d1name'],
				'd2'=>$data['d2'],
				'd2name'=>$data['d2name'],
				'd3'=>$data['d3'],
				'd3name'=>$data['d3name'],
				'd4'=>$data['d4'],
				'd4name'=>$data['d4name'],
				'd5'=>$data['d5'],
				'd5name'=>$data['d5name'],
				'd6'=>$data['d6'],
				'd6name'=>$data['d6name'],
				'd7'=>$data['d7'],
				'd7name'=>$data['d7name'],
				'd8'=>$data['d8'],
				'd8name'=>$data['d8name'],
				'd9'=>$data['d9'],
				'd9name'=>$data['d9name'],
				'd10'=>$data['d10'],
				'd10name'=>$data['d10name'],
				'd11'=>$data['d11'],
				'd11name'=>$data['d11name'],
				'd12'=>$data['d12'],
				'd12name'=>$data['d12name'],
				'd13'=>$data['d13'],
				'd13name'=>$data['d13name'],
				'd14'=>$data['d14'],
				'd14name'=>$data['d14name'],
				'd15'=>$data['d15'],
				'd15name'=>$data['d15name'],
				'd16'=>$data['d16'],
				'd16name'=>$data['d16name'],
				'd17'=>$data['d17'],
				'd17name'=>$data['d17name'],
				'd18'=>$data['d18'],
				'd18name'=>$data['d18name'],
				'd19'=>$data['d19'],
				'd19name'=>$data['d19name'],
				'd20'=>$data['d20'],
				'd20name'=>$data['d20name'],
				'd21'=>$data['d21'],
				'd21name'=>$data['d21name'],
				'd22'=>$data['d22'],
				'd22name'=>$data['d22name'],
				'd23'=>$data['d23'],
				'd23name'=>$data['d23name'],
				'd24'=>$data['d24'],
				'd24name'=>$data['d24name'],
				'd25'=>$data['d25'],
				'd25name'=>$data['d25name'],
				'd26'=>$data['d26'],
				'd26name'=>$data['d26name'],
				'd27'=>$data['d27'],
				'd27name'=>$data['d27name'],
				'd28'=>$data['d28'],
				'd28name'=>$data['d28name'],
				'd29'=>$data['d29'],
				'd29name'=>$data['d29name'],
				'd30'=>$data['d30'],
				'd30name'=>$data['d30name'],
				'd31'=>$data['d31'],
				'd31name'=>$data['d31name'],
				'recordstatus'=>findstatusname("appempsched",$data['recordstatus']),
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
  public function actioncopyemployeeschedule() {
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call CopyEmployeeschedule(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				foreach($id as $ids) {
					$command->bindvalue(':vid',$ids,PDO::PARAM_STR);
					$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
					$command->execute();
				}
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
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select b.fullname,a.month,a.year,
				c.absschedulename as d1name,
				d.absschedulename as d2name,
				e.absschedulename as d3name,
				f.absschedulename as d4name,
				g.absschedulename as d5name,
				h.absschedulename as d6name,
				i.absschedulename as d7name,
				j.absschedulename as d8name,
				k.absschedulename as d9name,
				l.absschedulename as d10name,
				m.absschedulename as d11name,
				n.absschedulename as d12name,
				o.absschedulename as d13name,
				p.absschedulename as d14name,
				q.absschedulename as d15name,
				r.absschedulename as d16name,
				s.absschedulename as d17name,
				t.absschedulename as d18name,
				u.absschedulename as d19name,
				v.absschedulename as d20name,
				w.absschedulename as d21name,
				x.absschedulename as d22name,
				y.absschedulename as d23name,
				z.absschedulename as d24name,
				aa.absschedulename as d25name,
				ab.absschedulename as d26name,
				ac.absschedulename as d27name,
				ad.absschedulename as d28name,
				ae.absschedulename as d29name,
				af.absschedulename as d30name,
				ag.absschedulename as d31name
			from employeeschedule a 
			left join employee b on b.employeeid = a.employeeid 
			left join absschedule c on c.absscheduleid = a.d1 
			left join absschedule d on d.absscheduleid = a.d2 
			left join absschedule e on e.absscheduleid = a.d3 
			left join absschedule f on f.absscheduleid = a.d4 
			left join absschedule g on g.absscheduleid = a.d5
			left join absschedule h on h.absscheduleid = a.d6
			left join absschedule i on i.absscheduleid = a.d7
			left join absschedule j on j.absscheduleid = a.d8
			left join absschedule k on k.absscheduleid = a.d9
			left join absschedule l on l.absscheduleid = a.d10
			left join absschedule m on m.absscheduleid = a.d11
			left join absschedule n on n.absscheduleid = a.d12
			left join absschedule o on o.absscheduleid = a.d13
			left join absschedule p on p.absscheduleid = a.d14
			left join absschedule q on q.absscheduleid = a.d15
			left join absschedule r on r.absscheduleid = a.d16
			left join absschedule s on s.absscheduleid = a.d17
			left join absschedule t on t.absscheduleid = a.d18
			left join absschedule u on u.absscheduleid = a.d19
			left join absschedule v on v.absscheduleid = a.d20
			left join absschedule w on w.absscheduleid = a.d21
			left join absschedule x on x.absscheduleid = a.d22
			left join absschedule y on y.absscheduleid = a.d23
			left join absschedule z on z.absscheduleid = a.d24
			left join absschedule aa on aa.absscheduleid = a.d25
			left join absschedule ab on ab.absscheduleid = a.d26
			left join absschedule ac on ac.absscheduleid = a.d27
			left join absschedule ad on ad.absscheduleid = a.d28
			left join absschedule ae on ae.absscheduleid = a.d29
			left join absschedule af on af.absscheduleid = a.d30
			left join absschedule ag on ag.absscheduleid = a.d31
			";
		$employeescheduleid = filter_input(INPUT_GET,'employeescheduleid');
		$employeename = filter_input(INPUT_GET,'employeename');
		$sql .= " where coalesce(a.employeescheduleid,'') like '%".$employeescheduleid."%' 
			and coalesce(b.fullname,'') like '%".$employeename."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.employeescheduleid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=getCatalog('employeeschedule');
		$this->pdf->AddPage('L',array(400,700));
		//masukkan posisi judul
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(
			getCatalog('employee'),
			getCatalog('month'),
			getCatalog('year'),
			getCatalog('d1'),
			getCatalog('d2'),
			getCatalog('d3'),
			getCatalog('d4'),
			getCatalog('d5'),
			getCatalog('d6'),
			getCatalog('d7'),
			getCatalog('d8'),
			getCatalog('d9'),
			getCatalog('d10'),
			getCatalog('d11'),
			getCatalog('d12'),
			getCatalog('d13'),
			getCatalog('d14'),
			getCatalog('d15'),
			getCatalog('d16'),
			getCatalog('d17'),
			getCatalog('d18'),
			getCatalog('d19'),
			getCatalog('d20'),
			getCatalog('d21'),
			getCatalog('d22'),
			getCatalog('d23'),
			getCatalog('d24'),
			getCatalog('d25'),
			getCatalog('d26'),
			getCatalog('d27'),
			getCatalog('d28'),
			getCatalog('d29'),
			getCatalog('d30'),
			getCatalog('d31'));
		$this->pdf->setwidths(array(40,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['fullname'],$row1['month'],$row1['year'],
				$row1['d1name'],$row1['d2name'],$row1['d3name'],$row1['d4name'],$row1['d5name'],
				$row1['d6name'],$row1['d7name'],$row1['d8name'],$row1['d9name'],$row1['d10name'],
				$row1['d11name'],$row1['d12name'],$row1['d13name'],$row1['d14name'],$row1['d15name'],
				$row1['d16name'],$row1['d17name'],$row1['d18name'],$row1['d19name'],$row1['d20name'],
				$row1['d21name'],$row1['d22name'],$row1['d23name'],$row1['d24name'],$row1['d25name'],
				$row1['d26name'],$row1['d27name'],$row1['d28name'],$row1['d29name'],$row1['d30name'],
				$row1['d31name']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	
	public function actionDownxls()
	{
		parent::actionDownXls();
		$sql = "select b.fullname,a.month,a.year,
				c.absschedulename as d1name,
				d.absschedulename as d2name,
				e.absschedulename as d3name,
				f.absschedulename as d4name,
				g.absschedulename as d5name,
				h.absschedulename as d6name,
				i.absschedulename as d7name,
				j.absschedulename as d8name,
				k.absschedulename as d9name,
				l.absschedulename as d10name,
				m.absschedulename as d11name,
				n.absschedulename as d12name,
				o.absschedulename as d13name,
				p.absschedulename as d14name,
				q.absschedulename as d15name,
				r.absschedulename as d16name,
				s.absschedulename as d17name,
				t.absschedulename as d18name,
				u.absschedulename as d19name,
				v.absschedulename as d20name,
				w.absschedulename as d21name,
				x.absschedulename as d22name,
				y.absschedulename as d23name,
				z.absschedulename as d24name,
				aa.absschedulename as d25name,
				ab.absschedulename as d26name,
				ac.absschedulename as d27name,
				ad.absschedulename as d28name,
				ae.absschedulename as d29name,
				af.absschedulename as d30name,
				ag.absschedulename as d31name
			from employeeschedule a 
			left join employee b on b.employeeid = a.employeeid 
			left join absschedule c on c.absscheduleid = a.d1 
			left join absschedule d on d.absscheduleid = a.d2 
			left join absschedule e on e.absscheduleid = a.d3 
			left join absschedule f on f.absscheduleid = a.d4 
			left join absschedule g on g.absscheduleid = a.d5
			left join absschedule h on h.absscheduleid = a.d6
			left join absschedule i on i.absscheduleid = a.d7
			left join absschedule j on j.absscheduleid = a.d8
			left join absschedule k on k.absscheduleid = a.d9
			left join absschedule l on l.absscheduleid = a.d10
			left join absschedule m on m.absscheduleid = a.d11
			left join absschedule n on n.absscheduleid = a.d12
			left join absschedule o on o.absscheduleid = a.d13
			left join absschedule p on p.absscheduleid = a.d14
			left join absschedule q on q.absscheduleid = a.d15
			left join absschedule r on r.absscheduleid = a.d16
			left join absschedule s on s.absscheduleid = a.d17
			left join absschedule t on t.absscheduleid = a.d18
			left join absschedule u on u.absscheduleid = a.d19
			left join absschedule v on v.absscheduleid = a.d20
			left join absschedule w on w.absscheduleid = a.d21
			left join absschedule x on x.absscheduleid = a.d22
			left join absschedule y on y.absscheduleid = a.d23
			left join absschedule z on z.absscheduleid = a.d24
			left join absschedule aa on aa.absscheduleid = a.d25
			left join absschedule ab on ab.absscheduleid = a.d26
			left join absschedule ac on ac.absscheduleid = a.d27
			left join absschedule ad on ad.absscheduleid = a.d28
			left join absschedule ae on ae.absscheduleid = a.d29
			left join absschedule af on af.absscheduleid = a.d30
			left join absschedule ag on ag.absscheduleid = a.d31
			";
		$employeescheduleid = filter_input(INPUT_GET,'employeescheduleid');
		$employeename = filter_input(INPUT_GET,'employeename');
		$sql .= " where coalesce(a.employeescheduleid,'') like '%".$employeescheduleid."%' 
			and coalesce(b.fullname,'') like '%".$employeename."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.employeescheduleid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$i=1;
		$this->phpExcel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0,1,getCatalog('employeeid'))
                ->setCellValueByColumnAndRow(1,1,getCatalog('month'))
                ->setCellValueByColumnAndRow(2,1,getCatalog('year'))
                ->setCellValueByColumnAndRow(3,1,getCatalog('d1'))
                ->setCellValueByColumnAndRow(4,1,getCatalog('d2'))
                ->setCellValueByColumnAndRow(5,1,getCatalog('d3'))
                ->setCellValueByColumnAndRow(6,1,getCatalog('d4'))
                ->setCellValueByColumnAndRow(7,1,getCatalog('d5'))
                ->setCellValueByColumnAndRow(8,1,getCatalog('d6'))
                ->setCellValueByColumnAndRow(9,1,getCatalog('d7'))
                ->setCellValueByColumnAndRow(10,1,getCatalog('d8'))
                ->setCellValueByColumnAndRow(11,1,getCatalog('d9'))
                ->setCellValueByColumnAndRow(12,1,getCatalog('d10'))
                ->setCellValueByColumnAndRow(13,1,getCatalog('d11'))
                ->setCellValueByColumnAndRow(14,1,getCatalog('d12'))
                ->setCellValueByColumnAndRow(15,1,getCatalog('d13'))
                ->setCellValueByColumnAndRow(16,1,getCatalog('d14'))
                ->setCellValueByColumnAndRow(17,1,getCatalog('d15'))
                ->setCellValueByColumnAndRow(18,1,getCatalog('d16'))
                ->setCellValueByColumnAndRow(19,1,getCatalog('d17'))
                ->setCellValueByColumnAndRow(20,1,getCatalog('d18'))
                ->setCellValueByColumnAndRow(21,1,getCatalog('d19'))
                ->setCellValueByColumnAndRow(22,1,getCatalog('d20'))
                ->setCellValueByColumnAndRow(23,1,getCatalog('d21'))
                ->setCellValueByColumnAndRow(24,1,getCatalog('d22'))
                ->setCellValueByColumnAndRow(25,1,getCatalog('d23'))
                ->setCellValueByColumnAndRow(26,1,getCatalog('d24'))
                ->setCellValueByColumnAndRow(27,1,getCatalog('d25'))
                ->setCellValueByColumnAndRow(28,1,getCatalog('d26'))
                ->setCellValueByColumnAndRow(29,1,getCatalog('d27'))
                ->setCellValueByColumnAndRow(30,1,getCatalog('d28'))
                ->setCellValueByColumnAndRow(31,1,getCatalog('d29'))
                ->setCellValueByColumnAndRow(32,1,getCatalog('d30'))
                ->setCellValueByColumnAndRow(33,1,getCatalog('d31'))
                ->setCellValueByColumnAndRow(34,1,getCatalog('recordstatus'))
                ;		foreach($dataReader as $row1)
		{
			  $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['fullname'])
                ->setCellValueByColumnAndRow(1, $i+1, $row1['month'])
                ->setCellValueByColumnAndRow(2, $i+1, $row1['year'])
                ->setCellValueByColumnAndRow(3, $i+1, $row1['d1name'])
                ->setCellValueByColumnAndRow(4, $i+1, $row1['d2name'])
                ->setCellValueByColumnAndRow(5, $i+1, $row1['d3name'])
                ->setCellValueByColumnAndRow(6, $i+1, $row1['d4name'])
                ->setCellValueByColumnAndRow(7, $i+1, $row1['d5name'])
                ->setCellValueByColumnAndRow(8, $i+1, $row1['d6name'])
                ->setCellValueByColumnAndRow(9, $i+1, $row1['d7name'])
                ->setCellValueByColumnAndRow(10, $i+1, $row1['d8name'])
                ->setCellValueByColumnAndRow(11, $i+1, $row1['d9name'])
                ->setCellValueByColumnAndRow(12, $i+1, $row1['d10name'])
                ->setCellValueByColumnAndRow(13, $i+1, $row1['d11name'])
                ->setCellValueByColumnAndRow(14, $i+1, $row1['d12name'])
                ->setCellValueByColumnAndRow(15, $i+1, $row1['d13name'])
                ->setCellValueByColumnAndRow(16, $i+1, $row1['d14name'])
                ->setCellValueByColumnAndRow(17, $i+1, $row1['d15name'])
                ->setCellValueByColumnAndRow(18, $i+1, $row1['d16name'])
                ->setCellValueByColumnAndRow(19, $i+1, $row1['d17name'])
                ->setCellValueByColumnAndRow(20, $i+1, $row1['d18name'])
                ->setCellValueByColumnAndRow(21, $i+1, $row1['d19name'])
                ->setCellValueByColumnAndRow(22, $i+1, $row1['d20name'])
                ->setCellValueByColumnAndRow(23, $i+1, $row1['d21name'])
                ->setCellValueByColumnAndRow(24, $i+1, $row1['d22name'])
                ->setCellValueByColumnAndRow(25, $i+1, $row1['d23name'])
                ->setCellValueByColumnAndRow(26, $i+1, $row1['d24name'])
                ->setCellValueByColumnAndRow(27, $i+1, $row1['d25name'])
                ->setCellValueByColumnAndRow(28, $i+1, $row1['d26name'])
                ->setCellValueByColumnAndRow(29, $i+1, $row1['d27name'])
                ->setCellValueByColumnAndRow(30, $i+1, $row1['d28name'])
                ->setCellValueByColumnAndRow(31, $i+1, $row1['d29name'])
                ->setCellValueByColumnAndRow(32, $i+1, $row1['d30name'])
                ->setCellValueByColumnAndRow(33, $i+1, $row1['d31name'])
                ;		$i+=1;
		}
		unset($this->phpExcel);
	}
	

}
