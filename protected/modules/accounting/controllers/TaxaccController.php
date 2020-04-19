<?php
class TaxaccController extends Controller {
	public $menuname = 'taxacc';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$taxaccid = GetSearchText(array('POST','Q'),'taxaccid');
		$companycode = GetSearchText(array('POST','Q'),'companycode');
		$companyname = GetSearchText(array('POST','Q'),'companyname');
		$taxcode = GetSearchText(array('POST','Q'),'taxcode');
		$description = GetSearchText(array('POST','Q'),'description');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','taxaccid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('taxacc t')
				->leftjoin('company a','a.companyid=t.companyid')
				->leftjoin('account b','b.accountid=t.accountid')
				->leftjoin('tax d','d.taxid=t.taxid')
				->where('(t.taxaccid like :taxaccid) 
					and (a.companycode like :companycode) 
					and (a.companyname like :companyname) 
					and (d.taxcode like :taxcode) 
					and (d.description like :description)
					',
					array(':taxaccid'=>'%'.$taxaccid.'%',':companycode'=>'%'.$companycode.'%',':companyname'=>'%'.$companyname.'%',
						':taxcode'=>'%'.$taxcode.'%',':description'=>'%'.$description.'%'))
				->queryScalar();
		$result['total'] = $cmd;
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,d.description,a.companycode,a.companyname,b.accountcode,d.taxcode')			
				->from('taxacc t')
				->leftjoin('company a','a.companyid=t.companyid')
				->leftjoin('account b','b.accountid=t.accountid')
				->leftjoin('tax d','d.taxid=t.taxid')
				->where('(t.taxaccid like :taxaccid) 
					and (a.companycode like :companycode) 
					and (a.companyname like :companyname) 
					and (d.taxcode like :taxcode) 
					and (d.description like :description)
					',
					array(':taxaccid'=>'%'.$taxaccid.'%',':companycode'=>'%'.$companycode.'%',':companyname'=>'%'.$companyname.'%',
						':taxcode'=>'%'.$taxcode.'%',':description'=>'%'.$description.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
			'taxaccid'=>$data['taxaccid'],
			'companyid'=>$data['companyid'],
			'companycode'=>$data['companycode'],
			'companyname'=>$data['companyname'],
			'taxid'=>$data['taxid'],
			'taxcode'=>$data['taxcode'],
			'accountid'=>$data['accountid'],
			'accountcode'=>$data['accountcode'],
			'description'=>$data['description'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {		
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Inserttaxacc(:vcompanyid,:vtaxid,:vaccountid,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatetaxacc(:vid,:vcompanyid,:vtaxid,:vaccountid,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcompanyid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vtaxid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vaccountid',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-taxacc"]["name"]);
		if (move_uploaded_file($_FILES["file-taxacc"]["tmp_name"], $target_file)) {
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
					$taxcode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$taxid = Yii::app()->db->createCommand("select taxid from tax where taxcode = '".$taxcode."'")->queryScalar();
					$accountid = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$this->ModifyData($connection,array($id,$companyid,$taxid,$accountid,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['taxaccid'])?$_POST['taxaccid']:''),
				$_POST['companyid'],$_POST['taxid'],$_POST['accountid']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
	}
	public function actionPurge() {
		parent::actionPurge();
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgetaxacc(:vid,:vdatauser)';
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
		$this->dataprint['companycode'] = GetSearchText(array('GET'),'companycode');
		$this->dataprint['companyname'] = GetSearchText(array('GET'),'companyname');
		$this->dataprint['accountcode'] = GetSearchText(array('GET'),'accountcode');
		$this->dataprint['accountname'] = GetSearchText(array('GET'),'accountname');
		$this->dataprint['taxcode'] = GetSearchText(array('GET'),'tax');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'taxaccid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlecompanycode'] = GetCatalog('companycode');
		$this->dataprint['titlecompanyname'] = GetCatalog('companyname');
		$this->dataprint['titletaxaccount'] = GetCatalog('tax');
		$this->dataprint['titletaxcode'] = GetCatalog('taxcode');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}