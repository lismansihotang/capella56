<?php
class CurrencyController extends Controller {
	public $menuname = 'currency';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'currencyid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'currencyid' => 'text',
		'countryid' => [
			'type' => 'text',
			'from' => 't'
		],
		'countryname' => [
			'type' => 'text',
			'from' => 'p'
		],
		'currencyname' => 'text',
		'symbol' => 'text',
		'i18n' => 'text',
		'recordstatus' => [
			'type' => 'text',
			'from' => 'p'
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
			'from' => 'currency t 
				left join country p on t.countryid = p.countryid ',
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'currencyid' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'countrycode' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'countrycode' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'currencyname' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'symbol' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
			]
		]);
	}
	public function searchcombo() {
		return GetData([
			'from' => 'currency t 
				join country p on t.countryid = p.countryid ',
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => false,
			'searchfield' => [
				'countrycode' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
					'from'=> 'p'
				],
				'currencyname' => [
					'datatype' => 'Q',
					'operatortype' => 'or'
				],
				'symbol' => [
					'datatype' => 'Q',
					'operatortype' => 'or'
				],
			]
		]);
	}
	public function actionUpload() {
		parent::actionUpload();
		UploadData($this->menuname,[
			'spinsert' => 'Insertcurrency',
			'spupdate' => 'Updatecurrency',
			'arraydata' => [
				'vid'=>0,
				'countryid'=>[
					'column' => 1,
					'source' => 'select countryid from country where countrycode = '
				],
				'currencyname'=>2,
				'symbol'=>3,
				'i18n'=>4,
				'recordstatus'=>5
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'Insertcurrency',
			'spupdate' => 'Updatecurrency',
			'arraydata' => [
				'vid'=>(isset($_POST['currencyid'])?$_POST['currencyid']:''),
				'countryid'=>$_POST['countryid'],
				'currencyname'=>$_POST['currencyname'],
				'symbol'=>$_POST['symbol'],
				'i18n'=>$_POST['i18n'],
				'recordstatus'=>$_POST['recordstatus'],
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgecurrency',			
		]);
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