<?php
class JenissimpananController extends Controller {
	public $menuname = 'jenissimpanan';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'jenissimpananid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'jenissimpananid' => 'text',
		'namasimpanan' => 'text',
		'jumlah' => 'number',
		'bunga' => 'number',
		'fixed' => 'text',
		'tenor' => 'text',
		'isauto' => 'text',
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
			'from' => "jenissimpanan t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'jenissimpananid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'namasimpanan' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
			],
		]);
	}
	public function searchcombo() {
		return GetData([
			'from' => "jenissimpanan t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => false,
			'searchfield' => [
				'jenissimpananid' => [
					'datatype' => 'Q',
					'operatortype' => 'or' 
				],
				'namasimpanan' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
			],
		]);
	}
	public function actionUpload() {
		parent::actionUpload();
		UploadData($this->menuname,[
			'spinsert' => 'Insertjenissimpanan',
			'spupdate' => 'Updatejenissimpanan',
			'arraydata' => [
				'vid'=>0,
				'namasimpanan'=>1,
				'jumlah'=>2,
				'bunga'=>3,
				'fixed'=>4,
				'tenor'=>5,
				'isauto'=>6,
				'recordstatus'=>7,
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData($this->menuname,[
			'spinsert' => 'Insertjenissimpanan',
			'spupdate' => 'Updatejenissimpanan',
			'arraydata' => [
				'vid'=>(isset($_POST['jenissimpananid'])?$_POST['jenissimpananid']:''),
				'namasimpanan'=>$_POST['namasimpanan'],
				'jumlah'=>$_POST['jumlah'],
				'bunga'=>$_POST['bunga'],
				'fixed'=>$_POST['fixed'],
				'tenor'=>$_POST['tenor'],
				'isauto'=>$_POST['isauto'],
				'recordstatus'=>$_POST['recordstatus'],
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgejenissimpanan',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['namasimpanan'] = GetSearchText(array('GET'),'namasimpanan');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'jenissimpananid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlenamasimpanan'] = GetCatalog('namasimpanan');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}