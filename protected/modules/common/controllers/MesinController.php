<?php
class MesinController extends Controller {
	public $menuname = 'mesin';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$mesinid = GetSearchText(array('POST','Q'),'mesinid');
		$plantid = GetSearchText(array('POST','GET'),'plantid',0,'int');
		$slocid = GetSearchText(array('POST','Q','GET'),'slocid',0,'int');
		$plantcode = GetSearchText(array('POST','Q'),'plantcode');
		$sloccode = GetSearchText(array('POST','Q'),'sloccode');
		$kodemesin = GetSearchText(array('POST','Q'),'kodemesin');
		$namamesin = GetSearchText(array('POST','Q'),'namamesin');
		$buatan = GetSearchText(array('POST','Q'),'buatan');
		$tahun = GetSearchText(array('POST','Q'),'tahun');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','mesinid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$dependency = new CDbCacheDependency('SELECT MAX(a.updatedate) FROM mesin a');
		if (!isset($_GET['getdata'])) {
			if (isset($_GET['combo'])) {
				$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
					->select('count(1) as total')	
					->from('mesin t')
					->leftjoin('plant p','p.plantid = t.plantid')
					->leftjoin('sloc s','s.slocid = t.slocid')
					->where("((coalesce(t.mesinid,'') like :mesinid) 
					or (coalesce(p.plantcode,'') like :plantcode) 
					or (coalesce(s.sloccode,'') like :sloccode) 
					or (coalesce(t.namamesin,'') like :namamesin) 
					or (coalesce(t.kodemesin,'') like :kodemesin) 
						or (coalesce(t.buatan,'') like :buatan) or (coalesce(t.tahunoperasional,'') like :tahun)) and t.recordstatus=1",
						array(':tahun'=>$tahun,':mesinid'=>$mesinid,':plantcode'=>$plantcode,':sloccode'=>$sloccode,':namamesin'=>$namamesin,':kodemesin'=>$kodemesin,':buatan'=>$buatan))
					->queryScalar();
			} 
			else 
			if (isset($_GET['mesinplant'])) {
				$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
					->select('count(1) as total')	
					->from('mesin t')
					->leftjoin('plant p','p.plantid = t.plantid')
					->leftjoin('sloc s','s.slocid = t.slocid')
					->where("((coalesce(t.mesinid,'') like :mesinid) 
					or (coalesce(p.plantcode,'') like :plantcode) 
					or (coalesce(s.sloccode,'') like :sloccode) 
					or (coalesce(t.namamesin,'') like :namamesin) 
					or (coalesce(t.kodemesin,'') like :kodemesin) 
						or (coalesce(t.buatan,'') like :buatan) or (coalesce(t.tahunoperasional,'') like :tahun)) 
						and t.plantid = ".$plantid."
						and t.recordstatus=1",
						array(':tahun'=>$tahun,':mesinid'=>$mesinid,':plantcode'=>$plantcode,':sloccode'=>$sloccode,':namamesin'=>$namamesin,':kodemesin'=>$kodemesin,':buatan'=>$buatan))
					->queryScalar();
			} 
			else if (isset($_GET['mesinsloc'])) {
				$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
					->select('count(1) as total')	
					->from('mesin t')
					->leftjoin('plant p','p.plantid = t.plantid')
					->leftjoin('sloc s','s.slocid = t.slocid')
					->where("((coalesce(t.mesinid,'') like :mesinid) 
					or (coalesce(p.plantcode,'') like :plantcode) 
					or (coalesce(s.sloccode,'') like :sloccode) 
					or (coalesce(t.namamesin,'') like :namamesin) 
					or (coalesce(t.kodemesin,'') like :kodemesin) 
						or (coalesce(t.buatan,'') like :buatan) or (coalesce(t.tahunoperasional,'') like :tahun)) 
						and t.slocid = ".$slocid."
						and t.recordstatus=1",
						array(':tahun'=>$tahun,':mesinid'=>$mesinid,':plantcode'=>$plantcode,':sloccode'=>$sloccode,':namamesin'=>$namamesin,':kodemesin'=>$kodemesin,':buatan'=>$buatan))
					->queryScalar();
			} 
			else {
				$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
					->select('count(1) as total')	
					->from('mesin t')
					->leftjoin('plant p','p.plantid = t.plantid')
					->leftjoin('sloc s','s.slocid = t.slocid')
					->where("((coalesce(t.mesinid,'') like :mesinid) 
					and (coalesce(p.plantcode,'') like :plantcode) 
					and (coalesce(s.sloccode,'') like :sloccode) 
					and (coalesce(t.namamesin,'') like :namamesin) 
					and (coalesce(t.kodemesin,'') like :kodemesin) 
						and (coalesce(t.buatan,'') like :buatan) and (coalesce(t.tahunoperasional,'') like :tahun))",
						array(':tahun'=>$tahun,':mesinid'=>$mesinid,':sloccode'=>$sloccode,':plantcode'=>$plantcode,':namamesin'=>$namamesin,':kodemesin'=>$kodemesin,':buatan'=>$buatan))
					->queryScalar();
			}
			$result['total'] = $cmd;
			if (isset($_GET['combo'])) {
				$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
					->select('t.*,p.plantcode,s.sloccode')	
					->from('mesin t')
					->join('plant p','p.plantid = t.plantid')
					->leftjoin('sloc s','s.slocid = t.slocid')
					->where("((coalesce(t.mesinid,'') like :mesinid) 
					or (coalesce(p.plantcode,'') like :plantcode) 
					or (coalesce(s.sloccode,'') like :sloccode) 
					or (coalesce(t.namamesin,'') like :namamesin) 
					or (coalesce(t.kodemesin,'') like :kodemesin) 
						or (coalesce(t.buatan,'') like :buatan) or (coalesce(t.tahunoperasional,'') like :tahun)) and t.recordstatus=1",
						array(':tahun'=>$tahun,':mesinid'=>$mesinid,':sloccode'=>$sloccode,':plantcode'=>$plantcode,':namamesin'=>$namamesin,':kodemesin'=>$kodemesin,':buatan'=>$buatan))
					->order($sort.' '.$order)
					->queryAll();
			}
			else 
			if (isset($_GET['mesinplant'])) {
				$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
					->select('t.*,p.plantcode,s.sloccode')	
					->from('mesin t')
					->leftjoin('plant p','p.plantid = t.plantid')
					->leftjoin('sloc s','s.slocid = t.slocid')
					->where("((coalesce(t.mesinid,'') like :mesinid) 
					or (coalesce(p.plantcode,'') like :plantcode) 
					or (coalesce(s.sloccode,'') like :sloccode) 
					or (coalesce(t.namamesin,'') like :namamesin) 
					or (coalesce(t.kodemesin,'') like :kodemesin) 
						or (coalesce(t.buatan,'') like :buatan) or (coalesce(t.tahunoperasional,'') like :tahun)) 
						and t.plantid = ".$plantid."
						and t.recordstatus=1",
						array(':tahun'=>$tahun,':mesinid'=>$mesinid,':plantcode'=>$plantcode,':sloccode'=>$sloccode,':namamesin'=>$namamesin,':kodemesin'=>$kodemesin,':buatan'=>$buatan))
					->order($sort.' '.$order)->queryAll();
			} 
			else if (isset($_GET['mesinsloc'])) {
				$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
					->select('t.*,p.plantcode,s.sloccode')	
					->from('mesin t')
					->leftjoin('plant p','p.plantid = t.plantid')
					->leftjoin('sloc s','s.slocid = t.slocid')
					->where("((coalesce(t.mesinid,'') like :mesinid) 
					or (coalesce(p.plantcode,'') like :plantcode) 
					or (coalesce(s.sloccode,'') like :sloccode) 
					or (coalesce(t.namamesin,'') like :namamesin) 
					or (coalesce(t.kodemesin,'') like :kodemesin) 
						or (coalesce(t.buatan,'') like :buatan) or (coalesce(t.tahunoperasional,'') like :tahun))
						and t.slocid = ".$slocid."
						and t.recordstatus=1",
						array(':tahun'=>$tahun,':mesinid'=>$mesinid,':plantcode'=>$plantcode,':sloccode'=>$sloccode,':namamesin'=>$namamesin,':kodemesin'=>$kodemesin,':buatan'=>$buatan))
					->order($sort.' '.$order)->queryAll();
			} else {
				$cmd = Yii::app()->db->cache(1000,$dependency)->createCommand()
					->select('t.*,p.plantcode,s.sloccode')	
					->from('mesin t')
					->leftjoin('plant p','p.plantid = t.plantid')
					->leftjoin('sloc s','s.slocid = t.slocid')
					->where("((coalesce(t.mesinid,'') like :mesinid) 
					and (coalesce(p.plantcode,'') like :plantcode) 
					and (coalesce(s.sloccode,'') like :sloccode) 
					and (coalesce(t.namamesin,'') like :namamesin) 
					and (coalesce(t.kodemesin,'') like :kodemesin) 
						and (coalesce(t.buatan,'') like :buatan) and (coalesce(t.tahunoperasional,'') like :tahun))",
							array(':tahun'=>$tahun,':mesinid'=>$mesinid,':sloccode'=>$sloccode,':plantcode'=>$plantcode,':namamesin'=>$namamesin,':kodemesin'=>$kodemesin,':buatan'=>$buatan))
					->offset($offset)
					->limit($rows)
					->order($sort.' '.$order)
					->queryAll();
			}
			foreach($cmd as $data) {	
				$row[] = array(
					'mesinid'=>$data['mesinid'],
					'plantid'=>$data['plantid'],
					'plantcode'=>$data['plantcode'],
					'slocid'=>$data['slocid'],
					'sloccode'=>$data['sloccode'],
					'kodemesin'=>$data['kodemesin'],
					'namamesin'=>$data['namamesin'],
					'tahunoperasional'=>$data['tahunoperasional'],
					'buatan'=>$data['buatan'],
					'kwh'=>$data['kwh'],
					'orgpershift'=>$data['orgpershift'],
					'shiftperhari'=>$data['shiftperhari'],
					'speedpermin'=>$data['speedpermin'],
					'speedperjam'=>$data['speedperjam'],
					'rpm'=>$data['rpm'],
					'lebarbahan'=>$data['lebarbahan'],
					'rpm2'=>$data['rpm2'],
					'description'=>$data['description'],
					'recordstatus'=>$data['recordstatus'],
				);
			}
			$result=array_merge($result,array('rows'=>$row));
		} else {
			$cmd = Yii::app()->db->createCommand("
				select orgpershift 
				from mesin
				where mesinid = ".$mesinid
			)->queryRow();
			$result = $cmd;
		}
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call InsertMesin(:vplantid,:vslocid,:vkodemesin,:vnamamesin,:vtahunoperasional,:vbuatan,:vkwh,:vorgpershift,:vshiftperhari,:vspeedpermin,:vspeedperjam,:vrpm,
				:vlebarbahan,:vrpm2,:vdescription,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call UpdateMesin(:vid,:vplantid,:vslocid,:vkodemesin,:vnamamesin,:vtahunoperasional,:vbuatan,:vkwh,:vorgpershift,:vshiftperhari,:vspeedpermin,:vspeedperjam,:vrpm,
				:vlebarbahan,:vrpm2,:vdescription,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vplantid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vslocid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vkodemesin',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vnamamesin',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vtahunoperasional',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vbuatan',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vkwh',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vorgpershift',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vshiftperhari',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vspeedpermin',$arraydata[10],PDO::PARAM_STR);
		$command->bindvalue(':vspeedperjam',$arraydata[11],PDO::PARAM_STR);
		$command->bindvalue(':vrpm',$arraydata[12],PDO::PARAM_STR);
		$command->bindvalue(':vlebarbahan',$arraydata[13],PDO::PARAM_STR);
		$command->bindvalue(':vrpm2',$arraydata[14],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[15],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-mesin"]["name"]);
		if (move_uploaded_file($_FILES["file-mesin"]["tmp_name"], $target_file)) {
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
					$plantcode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$plantid = Yii::app()->db->createCommand("select plantid from plant where plantcode = '".$plantcode."'")->queryScalar();
					$sloccode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
					$kodemesin = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$namamesin = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$tahunoperasional = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$buatan = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$kwh = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$orgpershift = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$shiftperhari = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
					$speedpermin = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
					$speedperjam = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
					$rpm = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
					$lebarbahan = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
					$rpm2 = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
					$this->ModifyData($connection,array($id,$plantid,$slocid,$kodemesin,$namamesin,$tahunoperasional,$buatan,$kwh,$orgpershift,
						$shiftperhari,$speedpermin,$speedperjam,$rpm,$lebarbahan,$rpm2,$description));
				}
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,'Line: '.$row.' '.implode(" ",$e->errorInfo));
			}
    }
	}
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['mesinid'])?$_POST['mesinid']:''),$_POST['plantid'],$_POST['slocid'],$_POST['kodemesin'],
				$_POST['namamesin'],$_POST['tahunoperasional'],$_POST['buatan'],$_POST['kwh'],$_POST['orgpershift'],
				$_POST['shiftperhari'],$_POST['speedpermin'],$_POST['speedperjam'],$_POST['rpm'],$_POST['lebarbahan'],
				$_POST['rpm2'],$_POST['description']));
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
				$sql = 'call Purgemesin(:vid,:vdatauser)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vdatauser',GetUserPC(),PDO::PARAM_STR);
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
			GetMessage(true,'chooseone');
		}
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['titleid'] = GetCatalog('mesinid');
		$this->dataprint['titleplantcode'] = GetCatalog('plantcode');
		$this->dataprint['titlesloccode'] = GetCatalog('sloccode');
		$this->dataprint['titlekodemesin'] = GetCatalog('kodemesin');
		$this->dataprint['titlenamamesin'] = GetCatalog('namamesin');
		$this->dataprint['titlebuatan'] = GetCatalog('buatan');
		$this->dataprint['titletahun'] = GetCatalog('tahun');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['plantcode'] = GetSearchText(array('GET'),'plantcode');
    $this->dataprint['sloccode'] = GetSearchText(array('GET'),'sloccode');
    $this->dataprint['kodemesin'] = GetSearchText(array('GET'),'kodemesin');
    $this->dataprint['namamesin'] = GetSearchText(array('GET'),'namamesin');
    $this->dataprint['buatan'] = GetSearchText(array('GET'),'buatan');
    $this->dataprint['tahun'] = GetSearchText(array('GET'),'tahun');
  }
}
