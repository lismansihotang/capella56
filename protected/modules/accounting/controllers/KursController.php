<?php
class KursController extends Controller {
	public $menuname = 'kurs';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$kursid = GetSearchText(array('POST','Q'),'kursid');
		$kursdate = GetSearchText(array('POST','Q'),'kursdate');
		$currencyname = GetSearchText(array('POST','Q'),'kursdate');
		$taxrate = GetSearchText(array('POST','Q'),'taxrate');
		$birate = GetSearchText(array('POST','Q'),'birate');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','kursid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;		
		$result = array();
		$row = array();	
		// result
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('kurs t')
				->leftjoin('currency a','a.currencyid=t.currencyid')
				->where('(kursid like :kursid) and (kursdate like :kursdate)',
												array(':kursid'=>$kursid,':kursdate'=>$kursdate))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('kurs t')
				->leftjoin('currency a','a.currencyid=t.currencyid')
				->where('((kursid like :kursid) or (kursdate like :kursdate)) and t.recordstatus=1',
												array(':kursid'=>$kursid,':kursdate'=>$kursdate))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.currencyname')	
				->from('kurs t')
				->leftjoin('currency a','a.currencyid=t.currencyid')
				->where('(kursid like :kursid) and (kursdate like :kursdate)',
					array(':kursid'=>$kursid,':kursdate'=>$kursdate))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.currencyname')	
				->from('kurs t')
				->leftjoin('currency a','a.currencyid=t.currencyid')
				->where('((kursid like :kursid) or (kursdate like :kursdate)) and t.recordstatus=1',
												array(':kursid'=>$kursid,':kursdate'=>$kursdate))
				->order($sort.' '.$order)
				->queryAll();
		}		
		foreach($cmd as $data) {	
			$row[] = array(
				'kursid'=>$data['kursid'],
				'currencyid'=>$data['currencyid'],
				'currencyname'=>$data['currencyname'],
				'taxrate' => Yii::app()->format->formatNumber($data['taxrate']),
				'birate' => Yii::app()->format->formatNumber($data['birate']),
				'kursdate'=>date(Yii::app()->params['dateviewfromdb'], strtotime($data['kursdate'])),
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertkurs(:vkursdate,:vcurrencyid,:vtaxrate,:vbirate,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatekurs(:vid,:vkursdate,:vcurrencyid,:vtaxrate,:vbirate,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $_POST['kursid']);
		}
		$command->bindvalue(':vkursdate',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vtaxrate',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vbirate',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {		
			$this->ModifyData($connection,array((isset($_POST['kursid'])?$_POST['kursid']:''),
			date(Yii::app()->params['datetodb'], strtotime($_POST['kursdate'])),
			$_POST['currencyid'],
			$_POST['taxrate'],
			$_POST['birate']));
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
				$sql = 'call Purgekurs(:vid,:vdatauser)';
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
		$this->dataprint['kursdate'] = GetSearchText(array('GET'),'kursdate');
		$this->dataprint['currencyname'] = GetSearchText(array('GET'),'currencyname');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'kursid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlekursdate'] = GetCatalog('kursdate');
		$this->dataprint['titletaxrate'] = GetCatalog('taxrate');
		$this->dataprint['titlebirate'] = GetCatalog('birate');
		$this->dataprint['titlecurrencyname'] = GetCatalog('currencyname');
  }
}