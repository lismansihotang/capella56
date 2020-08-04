<?php
class CityController extends Controller {
	public $menuname = 'city';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'cityid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'cityid' => 'text',
		'provinceid' => [
			'type' => 'text',
			'from' => 't'
		],
		'provincename' => [
			'type' => 'text',
			'from' => 'p'
		],
		'citycode' => 'text',
		'cityname' => 'text',
		'recordstatus' => [
			'type' => 'text',
			'from' => 't'
		],
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
			'from' => 'city t 
				left join province p on t.provinceid = p.provinceid ',
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'cityid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'provincename' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'citycode' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'cityname' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
			]
		]);
	}
	public function searchcombo() {
		return GetData([
			'from' => 'city t 
				left join province p on t.provinceid = p.provinceid ',
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => false,
			'searchfield' => [
				'provincename' => [
					'datatype' => 'POST',
					'operatortype' => 'or',
					'from' => 'p'
				],
				'citycode' => [
					'datatype' => 'POST',
					'operatortype' => 'or'
				],
				'cityname' => [
					'datatype' => 'POST',
					'operatortype' => 'or'
				],
			]
		]);
	}
	public function actionUpload() {
		parent::actionUpload();
		UploadData($this->menuname,[
			'spinsert' => 'insertcity',
			'spupdate' => 'updatecity',
			'arraydata' => [
				'vid'=>0,
				'provinceid'=>[
					'column' => 1,
					'source' => 'select provinceid from province where provincecode = '
				],
				'citycode'=>2,
				'cityname'=>3,
				'recordstatus'=>4
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'insertcity',
			'spupdate' => 'updatecity',
			'arraydata' => [
				'vid'=>(isset($_POST['cityid'])?$_POST['cityid']:''),
				'provinceid'=>$_POST['provinceid'],
				'citycode'=>$_POST['citycode'],
				'cityname'=>$_POST['cityname'],
				'recordstatus'=>$_POST['recordstatus'],
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgecity',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['provincecode'] = GetSearchText(array('GET'),'provincecode');
		$this->dataprint['provincename'] = GetSearchText(array('GET'),'provincename');
		$this->dataprint['citycode'] = GetSearchText(array('GET'),'citycode');
		$this->dataprint['cityname'] = GetSearchText(array('GET'),'cityname');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'provinceid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titleprovincecode'] = GetCatalog('provincecode');
		$this->dataprint['titleprovincename'] = GetCatalog('provincename');
		$this->dataprint['titlecitycode'] = GetCatalog('citycode');
		$this->dataprint['titlecityname'] = GetCatalog('cityname');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}