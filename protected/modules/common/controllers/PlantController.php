<?php
class PlantController extends Controller {
	public $menuname = 'plant';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$plantid = GetSearchText(array('POST','Q'),'plantid');
		$plantcode = GetSearchText(array('POST','Q'),'plantcode');
		$description = GetSearchText(array('POST','Q'),'description');
		$company = GetSearchText(array('POST','Q'),'company');
		$companyid = GetSearchText(array('POST','GET','Q'),'companyid',0,'int');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','plantid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['getdata'])) {
			if (isset($_GET['combo'])) {
				$cmd = Yii::app()->db->createCommand()
					->select('count(1) as total')	
					->from('plant t')
					->join('company p','p.companyid=t.companyid')
					->where("((coalesce(plantid,'') like :plantid) or (coalesce(plantcode,'') like :plantcode) or 
									(coalesce(description,'') like :description) or 
									(coalesce(p.companyname,'') like :company)) and 
									t.recordstatus=1",
													array(':plantid'=>$plantid,':plantcode'=>$plantcode,
															':description'=>$description,
															':company'=>$company))
					->queryScalar();
			} else 
				if (isset($_GET['authcomp'])) {
				$cmd = Yii::app()->db->createCommand()
					->select('count(1) as total')	
					->from('plant t')
					->join('company p','p.companyid=t.companyid')
					->where("((coalesce(plantid,'') like :plantid) or (coalesce(plantcode,'') like :plantcode) or 
									(coalesce(description,'') like :description) or 
									(coalesce(p.companyname,'') like :company)) 
									and t.plantid in (".getUserObjectValues('plant').")".
									(($companyid != '')?" and t.companyid = ".$companyid:"") 
									." and t.recordstatus=1",
													array(':plantid'=>$plantid,':plantcode'=>$plantcode,
															':description'=>$description,
															':company'=>$company))
					->queryScalar();
			} else 
				if (isset($_GET['auth'])) {
				$cmd = Yii::app()->db->createCommand()
					->select('count(1) as total')	
					->from('plant t')
					->join('company p','p.companyid=t.companyid')
					->where("((coalesce(plantid,'') like :plantid) or (coalesce(plantcode,'') like :plantcode) or 
									(coalesce(description,'') like :description) or 
									(coalesce(p.companyname,'') like :company)) and 
									t.plantid in (".getUserObjectValues('plant').") and 
									t.recordstatus=1",
													array(':plantid'=>$plantid,':plantcode'=>$plantcode,
															':description'=>$description,
															':company'=>$company))
					->queryScalar();
			} else {
				$cmd = Yii::app()->db->createCommand()
					->select('count(1) as total')	
					->from('plant t')
					->join('company p','p.companyid=t.companyid')
					->where("((coalesce(plantid,'') like :plantid) and (coalesce(plantcode,'') like :plantcode) and 
									(coalesce(description,'') like :description) and 
									(coalesce(p.companyname,'') like :company))",
													array(':plantid'=>$plantid,':plantcode'=>$plantcode,':description'=>$description,':company'=>$company))
					->queryScalar();
			}
			$result['total'] = $cmd;
			if (isset($_GET['combo'])) {
				$cmd = Yii::app()->db->createCommand()
					->select('t.*,p.companyname')	
					->from('plant t')
					->join('company p','p.companyid=t.companyid')
					->where("((coalesce(plantid,'') like :plantid) or (coalesce(plantcode,'') like :plantcode) or 
									(coalesce(description,'') like :description) or 
									(coalesce(p.companyname,'') like :company)) and 
									t.recordstatus=1",
													array(':plantid'=>$plantid,':plantcode'=>$plantcode,
															':description'=>$description,
															':company'=>$company))
					->order($sort.' '.$order)
					->queryAll();
			} else 
				if (isset($_GET['authcomp'])) {
				$cmd = Yii::app()->db->createCommand()
					->select('t.*,p.companyname')	
					->from('plant t')
					->join('company p','p.companyid=t.companyid')
					->where("((coalesce(plantid,'') like :plantid) or (coalesce(plantcode,'') like :plantcode) or 
									(coalesce(description,'') like :description) or 
									(coalesce(p.companyname,'') like :company)) 
									and t.plantid in (".getUserObjectValues('plant').")".
									(($companyid != '')?"and t.companyid = ".$companyid:"") 
									." and t.recordstatus=1",
													array(':plantid'=>$plantid,':plantcode'=>$plantcode,
															':description'=>$description,
															':company'=>$company))
					->queryAll();
			} else 
			if (isset($_GET['auth'])) {
				$cmd = Yii::app()->db->createCommand()
					->select('t.*,p.companyname')	
					->from('plant t')
					->join('company p','p.companyid=t.companyid')
					->where("((coalesce(plantid,'') like :plantid) or (coalesce(plantcode,'') like :plantcode) or 
									(coalesce(description,'') like :description) or 
									(coalesce(p.companyname,'') like :company)) and 
									t.plantid in (".getUserObjectValues('plant').") and 
									t.recordstatus=1",
													array(':plantid'=>$plantid,':plantcode'=>$plantcode,
															':description'=>$description,
															':company'=>$company))
					->queryAll();
			}
			else {
				$cmd = Yii::app()->db->createCommand()
					->select('t.*,p.companyname')	
					->from('plant t')
					->join('company p','p.companyid=t.companyid')
					->where("((coalesce(plantid,'') like :plantid) and (coalesce(plantcode,'') like :plantcode) and 
									(coalesce(description,'') like :description) and 
									(coalesce(p.companyname,'') like :company))",
							array(':plantid'=>$plantid,':plantcode'=>$plantcode,
									':description'=>$description,
									':company'=>$company))
					->offset($offset)
					->limit($rows)
					->order($sort.' '.$order)
					->queryAll();
			}
			foreach($cmd as $data) {	
				$row[] = array(
					'plantid'=>$data['plantid'],
					'plantcode'=>$data['plantcode'],
					'addresstoname'=>$data['addresstoname'],
					'description'=>$data['description'],
					'companyid'=>$data['companyid'],
					'companyname'=>$data['companyname'],
					'limitpo'=>Yii::app()->format->formatNumber($data['limitpo']),
					'recordstatus'=>$data['recordstatus'],
				);
			}
			$result=array_merge($result,array('rows'=>$row));
		} else {
			$plantid = GetSearchText(array('POST','Q'),'plantid',0,'int');
			$result = Yii::app()->db->createCommand("
				select a.plantid, a.plantcode, b.companyname,a.addresstoname,b.billto 
				from plant a 
				join company b on b.companyid = a.companyid 
				where a.plantid = ".$plantid)->queryRow();
		}
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertplant(:vcompanyid,:vplantcode,:vaddresstoname,:vdescription,:vlimitpo,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateplant(:vid,:vcompanyid,:vplantcode,:vaddresstoname,:vdescription,:vlimitpo,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcompanyid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vplantcode',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vaddresstoname',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vlimitpo',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-plant"]["name"]);
		if (move_uploaded_file($_FILES["file-plant"]["tmp_name"], $target_file)) {
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
					$companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
					$plantcode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$limitpo = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$this->ModifyData($connection,array($id,$languagename,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['plantid'])?$_POST['plantid']:''),$_POST['companyid'],$_POST['plantcode'],$_POST['addresstoname'],
			$_POST['description'],$_POST['limitpo'],
			$_POST['recordstatus']));
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
				$sql = 'call Purgeplant(:vid,:vdatauser)';
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
			GetMessage(true, getcatalog('chooseone'));
		}
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['titleid'] = GetCatalog('plantid');
		$this->dataprint['titleplantcode'] = GetCatalog('plantcode');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titlecompanyname'] = GetCatalog('companyname');
		$this->dataprint['titleaddresstoname'] = GetCatalog('addresstoname');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['plantcode'] = GetSearchText(array('GET'),'plantcode');
    $this->dataprint['description'] = GetSearchText(array('GET'),'description');
    $this->dataprint['company'] = GetSearchText(array('GET'),'company');
  }
}
