<?php
class AddresstypeController extends Controller {
	public $menuname = 'addresstype';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'addresstypeid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'addresstypeid' => 'text',
		'addresstypename' => 'text',
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
			'from' => "addresstype t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'addresstypeid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'addresstypename' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
			],
		]);
	}
	public function searchcombo() {
		return GetData([
			'from' => "addresstype t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => false,
			'searchfield' => [
				'addresstypename' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
			],
		]);
	}
	public function actionUpload() {
		parent::actionUpload();
		UploadData($this->menuname,[
			'spinsert' => 'Insertaddresstype',
			'spupdate' => 'Updateaddresstype',
			'arraydata' => [
				'vid'=>0,
				'addresstypename'=>1,
				'recordstatus'=>3,
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'Insertaddresstype',
			'spupdate' => 'Updateaddresstype',
			'arraydata' => [
				'vid'=>(isset($_POST['addresstypeid'])?$_POST['addresstypeid']:''),
				'fullname'=>$_POST['addresstypename'],
				'recordstatus'=>$_POST['recordstatus'],
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgeaddresstype',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['addresstypename'] = GetSearchText(array('GET'),'addresstypename');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'addresstypeid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titleaddresstypename'] = GetCatalog('addresstypename');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}