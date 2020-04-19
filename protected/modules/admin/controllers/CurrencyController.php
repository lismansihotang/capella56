<?php
class CurrencyController extends Controller {
	public $menuname = 'currency';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$currencyid = GetSearchText(array('POST','Q'),'currencyid');
		$countryname = GetSearchText(array('POST','Q'),'countryname');
		$currencyname = GetSearchText(array('POST','Q'),'currencyname');
		$symbol = GetSearchText(array('POST','Q'),'symbol');
		$i18n = GetSearchText(array('POST','Q'),'i18n');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','currencyid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('currency t')
				->join('country p', 't.countryid=p.countryid')
				->where('(currencyid like :currencyid) and (symbol like :symbol) and (currencyname like :currencyname) and (p.countryname like :countryname)
						and (i18n like :i18n)',
						array(':currencyid'=>$currencyid,':symbol'=>$symbol,':currencyname'=>$currencyname,':countryname'=>$countryname,
						':i18n'=>$i18n))			
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('currency t')
			->join('country p', 't.countryid=p.countryid')
			->where('((currencyid like :currencyid) or (symbol like :symbol) or (currencyname like :currencyname) or (p.countryname like :countryname)
				or (i18n like :i18n)) and t.recordstatus = 1',
					array(':currencyid'=>$currencyid,':symbol'=>$symbol,':currencyname'=>$currencyname,':countryname'=>$countryname,
					':i18n'=>$i18n))			
			->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,p.countryname')			
			->from('currency t')
			->join('country p', 't.countryid=p.countryid')
			->where('(currencyid like :currencyid) and (symbol like :symbol) and (currencyname like :currencyname) and (p.countryname like :countryname)
				and (i18n like :i18n) ',
					array(':currencyid'=>$currencyid,':symbol'=>$symbol,':currencyname'=>$currencyname,':countryname'=>$countryname,
					':i18n'=>$i18n))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
			->select('t.*,p.countryname')			
			->from('currency t')
			->join('country p', 't.countryid=p.countryid')
			->where('((currencyid like :currencyid) or (symbol like :symbol) or (currencyname like :currencyname) or (p.countryname like :countryname)
				or (i18n like :i18n)) and t.recordstatus = 1',
					array(':currencyid'=>$currencyid,':symbol'=>$symbol,':currencyname'=>$currencyname,':countryname'=>$countryname,
					':i18n'=>$i18n))
			->order($sort.' '.$order)
			->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'currencyid'=>$data['currencyid'],
				'countryid'=>$data['countryid'],
				'countryname'=>$data['countryname'],
				'currencyname'=>$data['currencyname'],
				'symbol'=>$data['symbol'],
				'i18n'=>$data['i18n'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertcurrency(:vcountryid,:vcurrencyname,:vsymbol,:vi18n,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatecurrency(:vid,:vcountryid,:vcurrencyname,:vsymbol,:vi18n,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcountryid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyname',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vsymbol',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vi18n',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-currency"]["name"]);
		if (move_uploaded_file($_FILES["file-currency"]["tmp_name"], $target_file)) {
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
					$countrycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$countryid = Yii::app()->db->createCommand("select countryid from country where countrycode = '".$countrycode."'")->queryScalar();
					$currencyname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$symbol = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$i18n = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$this->ModifyData($connection,array($id,$countryid,$currencyname,$symbol,$i18n,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['currencyid'])?$_POST['currencyid']:''),$_POST['countryid'],$_POST['currencyname'],$_POST['symbol'],$_POST['i18n'],$_POST['recordstatus']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode($e->errorInfo));
		}
	}
	public function actionPurge() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgecurrency(:vid,:vdatauser)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vdatauser',GetUserPC(),PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode($e->errorInfo));
			}
		}
		else {
			GetMessage(true,getcatalog('chooseone'));
		}
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['countryname'] = GetSearchText(array('GET'),'countryname');
		$this->dataprint['currencyname'] = GetSearchText(array('GET'),'currencyname');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'currencyid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlecountryname'] = GetCatalog('countryname');
		$this->dataprint['titlecurrencyname'] = GetCatalog('currencyname');
		$this->dataprint['titlesymbol'] = GetCatalog('symbol');
		$this->dataprint['titlei18n'] = GetCatalog('i18n');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}