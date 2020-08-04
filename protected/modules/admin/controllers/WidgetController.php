<?php
class WidgetController extends Controller {
	public $menuname = 'widget';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'widgetid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'widgetid' => 'text',
		'moduleid' => [
			'type' => 'text',
			'from' => 't'
		],
		'modulename' => [
			'type' => 'text',
			'from' => 'p'
		],
		'widgetname' => 'text',
		'widgettitle' => 'text',
		'widgetversion' => 'text',
		'widgetby' => 'text',
		'description' => 'text',
		'widgeturl' => 'text',
		'recordstatus' => [
			'type' => 'text',
			'from' => 't'
		]
	];
	public function actionIndex() {
		parent::actionIndex();
		if (isset($_GET['combo'])) 
			echo $this->search();
		else
		if (isset($_GET['grid'])) 
			echo $this->search();
		else 
			$this->renderPartial('index', array());
	}
	public function search() {
		return GetData([
			'from' => 'widget t 
				left join modules p on t.moduleid = p.moduleid ',
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'widgetid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'widgetname' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'widgettitle' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'widgetversion' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'widgetby' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'description' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'widgeturl' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'modulename' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
			]
		]);
	}
	public function searchcombo() {
		return GetData([
			'from' => 'widget t 
				left join modules p on t.moduleid = p.moduleid ',
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => false,
			'searchfield' => [
				'widgetname' => [
					'datatype' => 'POST',
					'operatortype' => 'or'
				],
				'widgettitle' => [
					'datatype' => 'POST',
					'operatortype' => 'or'
				],
				'widgetversion' => [
					'datatype' => 'POST',
					'operatortype' => 'or'
				],
				'widgetby' => [
					'datatype' => 'POST',
					'operatortype' => 'or'
				],
				'description' => [
					'datatype' => 'POST',
					'operatortype' => 'or'
				],
				'widgeturl' => [
					'datatype' => 'POST',
					'operatortype' => 'or'
				],
				'modulename' => [
					'datatype' => 'POST',
					'operatortype' => 'or'
				],
			]
		]);
	}
	public function actionUpload() {
		parent::actionUpload();
		UploadData($this->menuname,[
			'spinsert' => 'insertwidget',
			'spupdate' => 'updatewidget',
			'arraydata' => [
				'vid'=>0,
				'widgetname'=>1,
				'widgettitle'=>2,
				'widgetversion'=>3,
				'widgetby'=>4,
				'description'=>5,
				'widgeturl'=>6,
				'moduleid'=>[
					'column' => 7,
					'source' => 'select moduleid from modules where modulename = '
				],
				'recordstatus'=>8,
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'insertcatalogsys',
			'spupdate' => 'updatecatalogsys',
			'arraydata' => [
				'vid'=>(isset($_POST['widgetid'])?$_POST['widgetid']:''),
				'widgetname'=>$_POST['widgetname'],
				'widgettitle'=>$_POST['widgettitle'],
				'widgetversion'=>$_POST['widgetversion'],
				'widgetby'=>$_POST['widgetby'],
				'description'=>$_POST['description'],
				'widgeturl'=>$_POST['widgeturl'],
				'moduleid'=>$_POST['moduleid'],
				'recordstatus'=>$_POST['recordstatus'],
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'PurgeWidget',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['widgetname'] = GetSearchText(array('GET'),'widgetname');
		$this->dataprint['widgettitle'] = GetSearchText(array('GET'),'widgettitle');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'widgetid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlewidgetname'] = GetCatalog('widgetname');
		$this->dataprint['titlewidgettitle'] = GetCatalog('widgettitle');
		$this->dataprint['titlewidgetversion'] = GetCatalog('widgetversion');
		$this->dataprint['titlewidgetby'] = GetCatalog('widgetby');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titlewidgeturl'] = GetCatalog('widgeturl');
		$this->dataprint['titlemodulename'] = GetCatalog('modulename');
		$this->dataprint['titleinstalldate'] = GetCatalog('installdate');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}