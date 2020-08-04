<?php
class AddressbookController extends Controller {
	public $menuname = 'addressbook';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'addressbookid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'addressbookid' => 'text',
		'fullname' => 'text',
		'iscustomer' => 'text',
		'isvendor' => 'text',
		'ishospital' => 'text',
		'isexpedisi' => 'text',
		'taxno' => 'text',
		'jumlah' => [
			'from'=>'other',
			'source'=> "(select ifnull(count(1),0) from address a where a.addressbookid = t.addressbookid)"
		],
		'recordstatus' => 'text'
	];
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['customer']))
			echo $this->searchcustomer();
		else
		if(isset($_GET['vendor']))
			echo $this->searchvendor();
		else
		if(isset($_GET['expedisi']))
			echo $this->searchexpedisi();
		else
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
			'from' => "addressbook t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'addressbookid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'fullname' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
				'taxno' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
			],
		]);
	}
	public function searchexpedisi() {
		return GetData([
			'from' => "addressbook t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => false,
			'searchfield' => [
				'addressbookid' => [
					'datatype' => 'Q',
					'operatortype' => 'or' 
				],
				'fullname' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
				'taxno' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
			],
			'addonsearch' => 'and isexpedisi = 1'
		]);
	}
	public function searchvendor() {
		return GetData([
			'from' => "addressbook t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => false,
			'searchfield' => [
				'addressbookid' => [
					'datatype' => 'Q',
					'operatortype' => 'or' 
				],
				'fullname' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
				'taxno' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
			],
			'addonsearch' => 'and isvendor = 1'
		]);
	}
	public function searchcustomer() {
		return GetData([
			'from' => "addressbook t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => false,
			'searchfield' => [
				'addressbookid' => [
					'datatype' => 'Q',
					'operatortype' => 'or' 
				],
				'fullname' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
				'taxno' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
			],
			'addonsearch' => 'and iscustomer = 1'
		]);
	}
	public function actionUpload() {
		parent::actionUpload();
		UploadData($this->menuname,[
			'spinsert' => 'Insertaddressbook',
			'spupdate' => 'Updateaddressbook',
			'arraydata' => [
				'vid'=>0,
				'fullname'=>1,
				'iscustomer'=>2,
				'isemployee'=>3,
				'isvendor'=>4,
				'ishospital'=>5,
				'isexpedisi'=>6,
				'recordstatus'=>7,
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'Insertaddressbook',
			'spupdate' => 'Updateaddressbook',
			'arraydata' => [
				'vid'=>(isset($_POST['addressbookid'])?$_POST['addressbookid']:''),
				'fullname'=>$_POST['fullname'],
				'iscustomer'=>$_POST['iscustomer'],
				'isemployee'=>$_POST['isemployee'],
				'isvendor'=>$_POST['isvendor'],
				'ishospital'=>$_POST['ishospital'],
				'isexpedisi'=>$_POST['isexpedisi'],
				'recordstatus'=>$_POST['recordstatus'],
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgeaddressbook',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['fullname'] = GetSearchText(array('GET'),'fullname');
		$this->dataprint['taxno'] = GetSearchText(array('GET'),'taxno');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'languageid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlefullname'] = GetCatalog('fullname');
		$this->dataprint['titletaxno'] = GetCatalog('taxno');
		$this->dataprint['titleiscustomer'] = GetCatalog('iscustomer');
		$this->dataprint['titleisemployee'] = GetCatalog('isemployee');
		$this->dataprint['titleisvendor'] = GetCatalog('isvendor');
		$this->dataprint['titleishospital'] = GetCatalog('ishospital');
		$this->dataprint['titleisexpedisi'] = GetCatalog('isexpedisi');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}