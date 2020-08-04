<?php
class CatalogsysController extends Controller {
	public $menuname = 'catalogsys';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'catalogsysid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'catalogsysid' => 'text',
		'languageid' => [
			'type' => 'text',
			'from' => 't'
		],
		'languagename' => [
			'type' => 'text',
			'from' => 'p'
		],
		'catalogname' => 'text',
		'catalogval' => 'text'
	];
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		return GetData([
			'from' => 'catalogsys t 
				left join language p on t.languageid = p.languageid ',
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'catalogsysid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'languagename' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'from' => 'p'
				],
				'catalogname' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'catalogval' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
			]
		]);
	}
	public function actionUpload() {
		parent::actionUpload();
		UploadData($this->menuname,[
			'spinsert' => 'insertcatalogsys',
			'spupdate' => 'updatecatalogsys',
			'arraydata' => [
				'vid'=>0,
				'languageid'=>[
					'column' => 1,
					'source' => 'select languageid from language where languagename = '
				],
				'catalogname'=>2,
				'catalogval'=>3
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'insertcatalogsys',
			'spupdate' => 'updatecatalogsys',
			'arraydata' => [
				'vid'=>(isset($_POST['catalogsysid'])?$_POST['catalogsysid']:''),
				'languageid'=>$_POST['languageid'],
				'catalogname'=>$_POST['catalogname'],
				'catalogval'=>$_POST['catalogval']
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgecatalogsys',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['languagename'] = GetSearchText(array('GET'),'languagename');
		$this->dataprint['catalogname'] = GetSearchText(array('GET'),'catalogname');
		$this->dataprint['catalogval'] = GetSearchText(array('GET'),'catalogval');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'languageid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlelanguagename'] = GetCatalog('languagename');
		$this->dataprint['titlecatalogname'] = GetCatalog('catalogname');
		$this->dataprint['titlecatalogval'] = GetCatalog('catalogval');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}