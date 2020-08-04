<?php
class ModulesController extends Controller {
	public $menuname = 'modules';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'moduleid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'moduleid' => 'text',
		'modulename' => 'text',
		'moduledesc' => 'text',
		'moduleicon' => 'text',
		'isinstall' => 'text',
		'recordstatus' => 'text',
	];
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		return GetData([
			'from' => 'modules t',
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'moduleid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'modulename' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'moduledesc' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'moduleicon' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
			]
		]);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertmodules(:vmodulename,:vmoduledesc,:vmoduleicon,:visinstall,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatemodules(:vid,:vmodulename,:vmoduledesc,:vmoduleicon,:visinstall,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vmodulename',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vmoduledesc',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vmoduleicon',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':visinstall',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		UploadData($this->menuname,[
			'spinsert' => 'insertmodules',
			'spupdate' => 'updatemodules',
			'arraydata' => [
				'vid'=>0,
				'modulename'=>1,
				'moduledesc'=>2,
				'moduleicon'=>3,
				'isinstall'=>4,
				'recordstatus'=>5
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'insertcountry',
			'spupdate' => 'updatecountry',
			'arraydata' => [
				'vid'=>(isset($_POST['moduleid'])?$_POST['moduleid']:''),
				'modulename'=>$_POST['modulename'],
				'moduledesc'=>$_POST['moduledesc'],
				'moduleicon'=>$_POST['moduleicon'],
				'isinstall'=>$_POST['isinstall'],
				'recordstatus'=>$_POST['recordstatus']
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgemodules',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['modulename'] = GetSearchText(array('GET'),'modulename');
		$this->dataprint['moduledesc'] = GetSearchText(array('GET'),'moduledesc');
		$this->dataprint['moduleicon'] = GetSearchText(array('GET'),'moduleicon');
		$this->dataprint['url'] = GetSearchText(array('GET'),'url');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'moduleid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlemodulename'] = GetCatalog('modulename');
		$this->dataprint['titlemoduledesc'] = GetCatalog('moduledesc');
		$this->dataprint['titlemoduleicon'] = GetCatalog('moduleicon');
		$this->dataprint['titleurl'] = GetCatalog('url');
		$this->dataprint['titleisinstall'] = GetCatalog('isinstall');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}