<?php
class AddressaccountController extends Controller {
	public $menuname = 'addressaccount';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$addressaccountid = GetSearchText(array('POST'),'addressaccountid');
		$addressbook = GetSearchText(array('POST'),'addressbook');
		$companyname = GetSearchText(array('POST'),'companyname');
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.addressaccountid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('addressaccount t')
			->leftjoin('addressbook r','r.addressbookid=t.addressbookid')
			->leftjoin('company s','s.companyid=t.companyid')
			->leftjoin('account p','p.accountid=t.accpiutangid')
			->leftjoin('account q','q.accountid=t.acchutangid')
			->where("(coalesce(r.fullname,'') like :addressbook) and 
				(coalesce(s.companyname,'') like :companyname)",
				array(':addressbook'=>$addressbook,
					':companyname'=>$companyname,
						))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,r.fullname,s.companyname,p.accountname as accpiutang,q.accountname as acchutang')
			->from('addressaccount t')
			->leftjoin('addressbook r','r.addressbookid=t.addressbookid')
			->leftjoin('company s','s.companyid=t.companyid')
			->leftjoin('account p','p.accountid=t.accpiutangid')
			->leftjoin('account q','q.accountid=t.acchutangid')
			->where("(coalesce(r.fullname,'') like :addressbook) and 
				(coalesce(s.companyname,'') like :companyname) ",
				array(':addressbook'=>$addressbook,
					':companyname'=>$companyname,
						))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'addressaccountid'=>$data['addressaccountid'],
				'addressbookid'=>$data['addressbookid'],
				'fullname'=>$data['fullname'],
				'companyid'=>$data['companyid'],
				'companyname'=>$data['companyname'],
				'accpiutangid'=>$data['accpiutangid'],
				'accpiutang'=>$data['accpiutang'],
				'acchutangid'=>$data['acchutangid'],
				'acchutang'=>$data['acchutang'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertaddressaccount(:vaddressbookid,:vcompanyid,:vaccpiutangid,:vacchutangid,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updateaddressaccount(:vid,:vaddressbookid,:vcompanyid,:vaccpiutangid,:vacchutangid,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vaddressbookid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vcompanyid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vaccpiutangid',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vacchutangid',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-addressaccount"]["name"]);
		if (move_uploaded_file($_FILES["file-addressaccount"]["tmp_name"], $target_file)) {
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
					$addressbookname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$addressbookid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '".$addressbookname."'")->queryScalar();
					$accpiutangcode = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$accpiutangid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$accpiutangcode."'")->queryScalar();
					$acchutangcode = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$acchutangid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$acchutangcode."'")->queryScalar();
					$this->ModifyData($connection,array($id,$addressbookid,$companyid,$accpiutangid,$acchutangid));
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
			$this->ModifyData($connection,array((isset($_POST['addressaccountid'])?$_POST['addressaccountid']:''),$_POST['addressbookid'],$_POST['companyid'],$_POST['accpiutangid'],$_POST['acchutangid']));
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
				$sql = 'call Purgeaddressaccount(:vid,:vdatauser)';
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
		$this->dataprint['companyname'] = GetSearchText(array('GET'),'companyname');
		$this->dataprint['addressbook'] = GetSearchText(array('GET'),'addressbook');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'addressaccountid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlecompanycode'] = GetCatalog('companycode');
		$this->dataprint['titlefullname'] = GetCatalog('addressbook');
		$this->dataprint['titleaccpiutang'] = GetCatalog('accpiutang');
		$this->dataprint['titleacchutang'] = GetCatalog('acchutang');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}