<?php
class ParameterController extends Controller {
	public $menuname = 'parameter';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'parameterid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'parameterid' => 'text',
		'moduleid' => [
			'type' => 'text',
			'from' => 't'
		],
		'modulename' => [
			'type' => 'text',
			'from' => 'p'
		],
		'paramname' => 'text',
		'paramvalue' => 'text',
		'description' => 'text',
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
			'from' => 'parameter t 
				left join modules p on t.moduleid = p.moduleid ',
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'parameterid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'paramname' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'paramvalue' => [
					'datatype' => 'POST',
					'operatortype' => 'and'
				],
				'description' => [
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
	public function actionUpload() {
		parent::actionUpload();
		UploadData($this->menuname,[
			'spinsert' => 'Insertparameter',
			'spupdate' => 'Updateparameter',
			'arraydata' => [
				'vid'=>0,
				'paramname'=>1,
				'paramvalue'=>2,
				'description'=>3,
				'moduleid'=>[
					'column' => 4,
					'source' => 'select moduleid from modules where modulename = '
				],
				'recordstatus'=>5
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'Insertparameter',
			'spupdate' => 'Updateparameter',
			'arraydata' => [
				'vid'=>(isset($_POST['parameterid'])?$_POST['parameterid']:''),
				'paramname'=>$_POST['paramname'],
				'paramvalue'=>$_POST['paramvalue'],
				'description'=>$_POST['description'],
				'moduleid'=>$_POST['moduleid'],
				'recordstatus'=>$_POST['recordstatus'],
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'PurgeParameter',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['paramname'] = GetSearchText(array('GET'),'paramname');
		$this->dataprint['paramvalue'] = GetSearchText(array('GET'),'paramvalue');
		$this->dataprint['description'] = GetSearchText(array('GET'),'description');
		$this->dataprint['modulename'] = GetSearchText(array('GET'),'modulename');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'paramid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titleparamname'] = GetCatalog('paramname');
		$this->dataprint['titleparamvalue'] = GetCatalog('paramvalue');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titlemodulename'] = GetCatalog('modulename');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}