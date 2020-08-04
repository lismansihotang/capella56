<?php
class MenuaccessController extends Controller {
	public $menuname = 'menuaccess';
	private $sort = [
		'datatype' => 'POST',
		'default' => 't.menuaccessid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'menuaccessid' => [
			'type' => 'text',
			'from' => 't'
		],
		'menuname' => [
			'type' => 'text',
			'from' => 't'
		],
		'description' => [
			'type' => 'text',
			'from' => 't'
		],
		'menuurl' => [
			'type' => 'text',
			'from' => 't'
		],
		'menuicon' => [
			'type' => 'text',
			'from' => 't'
		],
		'parentid' => [
			'type' => 'text',
			'from' => 't'
		],
		'parentname' => [
			'type' => 'text',
			'from' => 'p',
			'sourcefield' => 'menuname'
		],
		'moduleid' => [
			'type' => 'text',
			'from' => 't'
		],
		'modulename' => [
			'type' => 'text',
			'from' => 'q'
		],
		'viewcode' => [
			'type' => 'text',
			'from' => 't'
		],
		'controllercode' => [
			'type' => 'text',
			'from' => 't'
		],
		'menudep' => [
			'type' => 'text',
			'from' => 't'
		],
		'sortorder' => [
			'type' => 'text',
			'from' => 't'
		],
		'recordstatus' => [
			'type' => 'text',
			'from' => 't'
		],
	];
	public function actionIndex() {
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
			'from' => 'menuaccess t 
				left join menuaccess p on t.parentid = p.menuaccessid 
				left join modules q on t.moduleid = q.moduleid ',
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'menuaccessid' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'from'=>'t' 
				],
				'menuname' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'from'=>'t' 
				],
				'description' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'from'=>'t' 
				],
				'menuurl' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'from'=>'t' 
				],
				'menuicon' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'from'=>'t' 
				],
				'parentname' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'from'=>'p',
					'sourcefield'=>'menuname' 
				],
				'modulename' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'from'=>'q' 
				],
			]
		]);
	}
	public function searchcombo() {
		return GetData([
			'from' => 'menuaccess t 
				left join menuaccess p on t.parentid = p.menuaccessid 
				left join modules q on t.moduleid = q.moduleid ',
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => false,
			'searchfield' => [
				'menuname' => [
					'datatype' => 'Q',
					'operatortype' => 'or'
				],
				'description' => [
					'datatype' => 'Q',
					'operatortype' => 'or'
				],
				'menuurl' => [
					'datatype' => 'Q',
					'operatortype' => 'or'
				],
				'menuicon' => [
					'datatype' => 'Q',
					'operatortype' => 'or'
				],
				'parentname' => [
					'datatype' => 'Q',
					'operatortype' => 'and',
					'from'=>'p',
					'sourcefield'=>'menuname' 
				],
				'modulename' => [
					'datatype' => 'Q',
					'operatortype' => 'and',
					'from'=>'q' 
				],
			]
		]);
	}
	public function actionUpload() {
		parent::actionUpload();
		UploadData($this->menuname,[
			'spinsert' => 'Insertmenuaccess',
			'spupdate' => 'Updatemenuaccess',
			'arraydata' => [
				'vid'=>0,
				'menuname'=>1,
				'description'=>2,
				'menuurl'=>3,
				'menuicon'=>4,
				'parentid'=>[
					'column' => 5,
					'source' => 'select menuaccessid from menuaccess where menuname = '
				],
				'moduleid'=>[
					'column' => 6,
					'source' => 'select moduleid from modules where modulename = '
				],
				'sortorder'=>8,
				'recordstatus'=>9
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'Insertmenuaccess',
			'spupdate' => 'Updatemenuaccess',
			'arraydata' => [
				'vid'=>(isset($_POST['menuaccessid'])?$_POST['menuaccessid']:''),
				'menuname'=>$_POST['menuname'],
				'description'=>$_POST['description'],
				'menuurl'=>$_POST['menuurl'],
				'menuicon'=>$_POST['menuicon'],
				'parentid'=>$_POST['parentid'],
				'moduleid'=>$_POST['moduleid'],
				'sortorder'=>$_POST['sortorder'],
				'viewcode'=>$_POST['viewcode'],
				'controllercode'=>$_POST['controllercode'],
				'menudep'=>$_POST['menudep'],
				'recordstatus'=>$_POST['recordstatus']
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgemenuaccess',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['menuname'] = GetSearchText(array('GET'),'menuname');
		$this->dataprint['description'] = GetSearchText(array('GET'),'description');
		$this->dataprint['menuurl'] = GetSearchText(array('GET'),'menuurl');
		$this->dataprint['menuicon'] = GetSearchText(array('GET'),'menuicon');
		$this->dataprint['parentname'] = GetSearchText(array('GET'),'parentname');
		$this->dataprint['modulename'] = GetSearchText(array('GET'),'modulename');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'menuaccessid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlemenuname'] = GetCatalog('menuname');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titlemenuicon'] = GetCatalog('menuicon');
		$this->dataprint['titlemenuurl'] = GetCatalog('menuurl');
		$this->dataprint['titleparentname'] = GetCatalog('parentname');
		$this->dataprint['titlemodulename'] = GetCatalog('modulename');
		$this->dataprint['titlesortorder'] = GetCatalog('sortorder');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}