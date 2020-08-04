<?php
class LanguageController extends Controller {
	public $menuname = 'language';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'languageid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'languageid' => 'text',
		'languagename' => 'text',
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
	private function search() {
		return GetData([
			'from' => 'language t',
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'languageid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'languagename' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
			]
		]);
	}
	private function searchcombo() {
		return GetData([
			'from' => 'language t',
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => false,
			'searchfield' => [
				'languagename' => [
					'datatype' => 'Q',
					'operatortype' => 'or'
				],
			],
			'addonsearch' => ' and recordstatus = 1'
		]);
	}
	public function actionUpload() {
		parent::actionUpload();
		UploadData($this->menuname,[
			'spinsert' => 'InsertLanguage',
			'spupdate' => 'UpdateLanguage',
			'arraydata' => [
				'vid'=>0,
				'languagename'=>1,
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'InsertLanguage',
			'spupdate' => 'UpdateLanguage',
			'arraydata' => [
				'vid'=>(isset($_POST['languageid'])?$_POST['languageid']:''),
				'languagename'=>$_POST['languagename'],
				'recordstatus'=>$_POST['recordstatus']
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'PurgeLanguage',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['languagename'] = GetSearchText(array('GET'),'languagename');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'languageid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlelanguagename'] = GetCatalog('languagename');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}