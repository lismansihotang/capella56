<?php
class JenisanggotaController extends Controller {
	public $menuname = 'jenisanggota';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'jenisanggotaid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'jenisanggotaid' => 'text',
		'kodejenisanggota' => 'text',
		'namajenisanggota' => 'text',
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
			'from' => "jenisanggota t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'jenisanggotaid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'kodejenisanggota' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
				'namajenisanggota' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
			],
		]);
	}
	public function actionUpload() {
		parent::actionUpload();
		UploadData($this->menuname,[
			'spinsert' => 'Insertjenisanggota',
			'spupdate' => 'Updatejenisanggota',
			'arraydata' => [
				'vid'=>0,
				'kodejenisanggota'=>1,
				'namajenisanggota'=>2,
				'recordstatus'=>3,
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData($this->menuname,[
			'spinsert' => 'Insertjenisanggota',
			'spupdate' => 'Updatejenisanggota',
			'arraydata' => [
				'vid'=>(isset($_POST['jenisanggotaid'])?$_POST['jenisanggotaid']:''),
				'kodejenisanggota'=>$_POST['kodejenisanggota'],
				'namajenisanggota'=>$_POST['namajenisanggota'],
				'recordstatus'=>$_POST['recordstatus'],
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgejenisanggota',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['kodejenisanggota'] = GetSearchText(array('GET'),'kodejenisanggota');
		$this->dataprint['namajenisanggota'] = GetSearchText(array('GET'),'namajenisanggota');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'jenisanggotaid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlekodejenisanggota'] = GetCatalog('kodejenisanggota');
		$this->dataprint['titlenamajenisanggota'] = GetCatalog('namajenisanggota');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}