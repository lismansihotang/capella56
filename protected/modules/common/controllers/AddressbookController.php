<?php
class AddressbookController extends Controller {
	public $menuname = 'addressbook';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$addressbookid = GetSearchText(array('POST','Q'),'addressbookid');
		$fullname = GetSearchText(array('POST','Q'),'fullname');
		$taxno = GetSearchText(array('POST','Q'),'taxno');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','addressbookid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('addressbook t')
				->where("((coalesce(fullname,'') like :fullname) 
				or (coalesce(addressbookid,'') like :addressbookid)
				or (coalesce(taxno,'') like :taxno)) 
				and t.recordstatus=1",
					array(':fullname'=>$fullname,':addressbookid'=>$addressbookid,
							':taxno'=>$taxno))
				->queryScalar();
		}
		else 
			if (isset($_GET['productplant'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('addressbook t')
				->where("((coalesce(fullname,'') like :fullname) 
				or (coalesce(addressbookid,'') like :addressbookid)
				or (coalesce(taxno,'') like :taxno)) 
				and t.recordstatus=1 ",
					array(':fullname'=>$fullname,':addressbookid'=>$addressbookid,
							':taxno'=>$taxno))
				->queryScalar();
		} else if (isset($_GET['expedisi'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('addressbook t')
				->where("((coalesce(fullname,'') like :fullname) 
				or (coalesce(addressbookid,'') like :addressbookid)
				or (coalesce(taxno,'') like :taxno)
				or t.isexpedisi = 1) 
				and t.recordstatus=1 ",
					array(':fullname'=>$fullname,':addressbookid'=>$addressbookid,
							':taxno'=>$taxno))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('addressbook t')
				->where("(coalesce(fullname,'') like :fullname) 
				and (coalesce(addressbookid,'') like :addressbookid)
				and (coalesce(taxno,'') like :taxno)
				",
				array(':fullname'=>$fullname,
					':addressbookid'=>$addressbookid,
					':taxno'=>$taxno))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('addressbook t')
				->where("((coalesce(fullname,'') like :fullname) 
				or (coalesce(taxno,'') like :taxno)
				or (coalesce(addressbookid,'') like :addressbookid)) 
				and t.recordstatus=1",
					array(':fullname'=>$fullname,':addressbookid'=>$addressbookid,
							':taxno'=>$taxno))
				->order($sort.' '.$order)
				->queryAll();
		}
		else 
			if (isset($_GET['productplant'])) {
				$cmd = Yii::app()->db->createCommand()
				->select('t.*')	
				->from('addressbook t')
				->where("((coalesce(fullname,'') like :fullname) 
				or (coalesce(addressbookid,'') like :addressbookid)
				or (coalesce(taxno,'') like :taxno)) 
				and t.recordstatus=1 ",
					array(':fullname'=>$fullname,':addressbookid'=>$addressbookid,
							':taxno'=>$taxno))
				->order($sort.' '.$order)
				->queryAll();
		}
		else if (isset($_GET['expedisi'])) {
				$cmd = Yii::app()->db->createCommand()
				->select('t.*')	
				->from('addressbook t')
				->where("((coalesce(fullname,'') like :fullname) 
				or (coalesce(addressbookid,'') like :addressbookid)
				or (coalesce(taxno,'') like :taxno)
				or t.isexpedisi = 1) 
				and t.recordstatus=1 ",
					array(':fullname'=>$fullname,':addressbookid'=>$addressbookid,
							':taxno'=>$taxno))
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')	
				->from('addressbook t')
				->where("(coalesce(fullname,'') like :fullname) 
				and (coalesce(taxno,'') like :taxno)
				and (coalesce(addressbookid,'') like :addressbookid)
				",
					array(':fullname'=>$fullname,':addressbookid'=>$addressbookid,
							':taxno'=>$taxno))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'addressbookid'=>$data['addressbookid'],
				'fullname'=>$data['fullname'],
				'iscustomer'=>$data['iscustomer'],
				'isemployee'=>$data['isemployee'],
				'isvendor'=>$data['isvendor'],
				'ishospital'=>$data['ishospital'],
				'isexpedisi'=>$data['isexpedisi'],
				'taxno'=>$data['taxno'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertaddressbook(:vfullname,:viscustomer,:visemployee,:visvendor,:vishospital,:visexpedisi,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateaddressbook(:vid,:vfullname,:viscustomer,:visemployee,:visvendor,:vishospital,:visexpedisi,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vfullname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':viscustomer',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':visemployee',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':visvendor',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vishospital',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':visexpedisi',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-addressbook"]["name"]);
		if (move_uploaded_file($_FILES["file-addressbook"]["tmp_name"], $target_file)) {
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
					$fullname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$iscustomer = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$isemployee = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$isvendor = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$ishospital = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$this->ModifyData($connection,array($id,$fullname,$iscustomer,$isemployee,$isvendor,$ishospital,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['addressbookid'])?$_POST['addressbookid']:''),$_POST['fullname'],$_POST['iscustomer'],
				$_POST['isemployee'],$_POST['isvendor'],$_POST['ishospital'],$_POST['isexpedisi'],$_POST['recordstatus']));
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
				$sql = 'call Purgeaddressbook(:vid,:vdatauser)';
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
		$this->dataprint['fullname'] = GetSearchText(array('GET'),'fullname');
		$this->dataprint['taxno'] = GetSearchText(array('GET'),'taxno');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'languageid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlefullname'] = GetCatalog('fullname');
		$this->dataprint['titletaxno'] = GetCatalog('taxno');
		$this->dataprint['titleiscustomer'] = GetCatalog('iscustomer');
		$this->dataprint['titleisemployee'] = GetCatalog('isemployee');
		$this->dataprint['titleisvendor'] = GetCatalog('isvendor');
		$this->dataprint['titleishospital'] = GetCatalog('ishospital');
		$this->dataprint['titleisexpedisi'] = GetCatalog('isexpedisi');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}