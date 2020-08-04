<?php
class IdentitytypeController extends Controller {
	public $menuname = 'identitytype';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'identitytypeid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'identitytypeid' => 'text',
		'identitytypename' => 'text',
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
			'from' => "identitytype t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'identitytypeid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'identitytypename' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
			],
		]);
	}
	public function searchcombo() {
		return GetData([
			'from' => "identitytype t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => false,
			'searchfield' => [
				'identitytypename' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
			],
		]);
	}
	public function actionUpload() {
		parent::actionUpload();
		UploadData($this->menuname,[
			'spinsert' => 'Insertidentitytype',
			'spupdate' => 'Updateidentitytype',
			'arraydata' => [
				'vid'=>0,
				'identitytypename'=>1,
				'recordstatus'=>2
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData($this->menuname,[
			'spinsert' => 'Insertidentitytype',
			'spupdate' => 'Updateidentitytype',
			'arraydata' => [
				'vid'=>(isset($_POST['identitytypeid'])?$_POST['identitytypeid']:''),
				'identitytypename'=>$_POST['identitytypename'],
				'recordstatus'=>$_POST['recordstatus']
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgeidentitytype',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['identitytypename'] = GetSearchText(array('GET'),'identitytypename');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'identitytypeid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titleidentitytypename'] = GetCatalog('identitytypename');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}