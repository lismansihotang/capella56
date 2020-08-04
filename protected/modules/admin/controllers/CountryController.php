<?php
class CountryController extends Controller {
	public $menuname = 'country';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'countryid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'countryid' => 'text',
		'countrycode' => 'text',
		'countryname' => 'text',
		'recordstatus' => 'text'
	];
	public function actionIndex() {
		parent::actionIndex();
		if (isset($_GET['combo']))
			echo $this->searchcombo();
		else
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index');
	}
	private function search() {
		return GetData([
			'from' => 'country t',
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'countryid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'countrycode' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'countryname' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
			]
		]);
	}
	private function searchcombo() {
		return GetData([
			'from' => 'country t',
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => false,
			'searchfield' => [
				'countrycode' => [
					'datatype' => 'Q',
					'operatortype' => 'or'
				],
				'countryname' => [
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
			'spinsert' => 'insertcountry',
			'spupdate' => 'updatecountry',
			'arraydata' => [
				'vid'=>0,
				'countrycode'=>1,
				'countryname'=>2,
				'recordstatus'=>3
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'insertcountry',
			'spupdate' => 'updatecountry',
			'arraydata' => [
				'vid'=>(isset($_POST['countryid'])?$_POST['countryid']:''),
				'countrycode'=>$_POST['countrycode'],
				'countryname'=>$_POST['countryname'],
				'recordstatus'=>$_POST['recordstatus']
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgecountry',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['countryname'] = GetSearchText(array('GET'),'countryname');
		$this->dataprint['countrycode'] = GetSearchText(array('GET'),'countrycode');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'countryid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlecountrycode'] = GetCatalog('countrycode');
		$this->dataprint['titlecountryname'] = GetCatalog('countryname');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
	}
}