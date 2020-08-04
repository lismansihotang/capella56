<?php
class ThemeController extends Controller {
	public $menuname = 'theme';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'themeid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'themeid' => 'text',
		'themename' => 'text',
		'description' => 'text',
		'themeprev' => 'text',
		'recordstatus' => 'text',
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
			'from' => 'theme t',
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'themeid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'themename' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'description' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'themeprev' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
			]
		]);
	}
	public function searchcombo() {
		return GetData([
			'from' => 'theme t',
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => false,
			'searchfield' => [
				'themename' => [
					'datatype' => 'Q',
					'operatortype' => 'or'
				],
				'description' => [
					'datatype' => 'Q',
					'operatortype' => 'or'
				],
				'themeprev' => [
					'datatype' => 'Q',
					'operatortype' => 'or'
				],
			]
		]);
	}
	public function actionUpload() {
		parent::actionUpload();
		UploadData($this->menuname,[
			'spinsert' => 'Inserttheme',
			'spupdate' => 'Updatetheme',
			'arraydata' => [
				'vid'=>0,
				'themename'=>1,
				'description'=>2,
				'themeprev'=>2,
				'recordstatus'=>3,
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'insertheme',
			'spupdate' => 'updatetheme',
			'arraydata' => [
				'vid'=>(isset($_POST['themeid'])?$_POST['themeid']:''),
				'themename'=>$_POST['themename'],
				'description'=>$_POST['description'],
				'themeprev'=>$_POST['themeprev'],
				'recordstatus'=>$_POST['recordstatus'],
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgetheme',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['themename'] = GetSearchText(array('GET'),'themename');
		$this->dataprint['themeprev'] = GetSearchText(array('GET'),'themeprev');
		$this->dataprint['url'] = GetSearchText(array('GET'),'url');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'themeid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlethemename'] = GetCatalog('themename');
		$this->dataprint['titlethemeprev'] = GetCatalog('themeprev');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}
