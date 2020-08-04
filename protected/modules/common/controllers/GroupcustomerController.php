<?php
class GroupcustomerController extends Controller {
	public $menuname = 'groupcustomer';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'groupcustomerid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'groupcustomerid' => 'text',
		'groupname' => 'text',
		'recordstatus' => 'text'
	];
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['combo']))
			echo $this->searchcombo();
		else
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		return GetData([
			'from' => "groupcustomer t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'groupcustomerid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'groupname' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
			],
		]);
	}
	public function searchcombo() {
		return GetData([
			'from' => "groupcustomer t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => false,
			'searchfield' => [
				'groupcustomerid' => [
					'datatype' => 'Q',
					'operatortype' => 'or' 
				],
				'groupname' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
			],
		]);
	}
	public function actionUpload() {
		parent::actionUpload();
		UploadData($this->menuname,[
			'spinsert' => 'Insertgroupcustomer',
			'spupdate' => 'Updategroupcustomer',
			'arraydata' => [
				'vid'=>0,
				'groupname'=>1,
				'recordstatus'=>2
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData($this->menuname,[
			'spinsert' => 'Insertgroupcustomer',
			'spupdate' => 'Updategroupcustomer',
			'arraydata' => [
				'vid'=>(isset($_POST['groupcustomerid'])?$_POST['groupcustomerid']:''),
				'groupname'=>$_POST['groupname'],
				'recordstatus'=>$_POST['recordstatus']
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgegroupcustomer',			
		]);
	}	
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['groupname'] = GetSearchText(array('GET'),'groupname');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'groupcustomerid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlegroupname'] = GetCatalog('groupname');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}