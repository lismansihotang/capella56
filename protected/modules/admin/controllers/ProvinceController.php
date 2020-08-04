<?php
class ProvinceController extends Controller {
	public $menuname = 'province';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'provinceid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'provinceid' => 'text',
		'countryid' => [
			'type' => 'text',
			'from' => 'p'
		],
		'countryname' => [
			'type' => 'text',
			'from' => 'p'
		],
		'provincecode' => 'text',
		'provincename' => 'text',
		'recordstatus' => [
			'type' => 'text',
			'from' => 't'
		]
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
			'from' => 'province t 
				left join country p on p.countryid = t.countryid ',
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'provinceid' => [
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
				'provincecode' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'provincename' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
			]
		]);
	}
	public function searchcombo() {
		return GetData([
			'from' => 'province t 
				left join country p on p.countryid = t.countryid ',
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => false,
			'searchfield' => [
				'countrycode' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'countryname' => [
					'datatype' => 'POST',
					'operatortype' => 'or'
				],
				'provincecode' => [
					'datatype' => 'POST',
					'operatortype' => 'or'
				],
				'provincename' => [
					'datatype' => 'POST',
					'operatortype' => 'or'
				],
			]
		]);
	}
	public function actionUpload() {
		parent::actionUpload();
		UploadData($this->menuname,[
			'spinsert' => 'InsertProvince',
			'spupdate' => 'UpdateProvince',
			'arraydata' => [
				'vid'=>0,
				'countryid'=>[
					'column' => 1,
					'source' => 'select countryid from country where countrycode = '
				],
				'provincecode'=>2,
				'provincename'=>3,
				'recordstatus'=>4
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'InsertProvince',
			'spupdate' => 'UpdateProvince',
			'arraydata' => [
				'vid'=>(isset($_POST['provinceid'])?$_POST['provinceid']:''),
				'countryid'=>$_POST['countryid'],
				'provincecode'=>$_POST['provincecode'],
				'provincename'=>$_POST['provincename'],
				'recordstatus'=>$_POST['recordstatus']
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgeprovince',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['provincecode'] = GetSearchText(array('GET'),'provincecode');
		$this->dataprint['provincename'] = GetSearchText(array('GET'),'provincename');
		$this->dataprint['countrycode'] = GetSearchText(array('GET'),'countrycode');
		$this->dataprint['countryname'] = GetSearchText(array('GET'),'countryname');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'provinceid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titleprovincecode'] = GetCatalog('provincecode');
		$this->dataprint['titleprovincename'] = GetCatalog('provincename');
		$this->dataprint['titlecountrycode'] = GetCatalog('countrycode');
		$this->dataprint['titlecountryname'] = GetCatalog('countryname');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}