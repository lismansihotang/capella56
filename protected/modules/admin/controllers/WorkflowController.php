<?php
class WorkflowController extends Controller {
	public $menuname = 'workflow';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'workflowid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'workflowid' => 'text',
		'wfname' => 'text',
		'wfdesc' => 'text',
		'wfminstat' => 'text',
		'wfmaxstat' => 'text',
		'jumlah' => [
			'from'=>'other',
			'source'=> "(select ifnull(count(1),0) from wfgroup a where a.workflowid = t.workflowid)"
		],
		'recordstatus' => 'text'
	];
	public function actionGetData() {
		return GetRandomHeader([
			'key' => 'workflowid',
			'table' => 'workflow'
		]);
	}
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
	public function actionIndexwfgroup() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionsearchwfgroup();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexwfstatus() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionsearchwfstatus();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		return GetData([
			'from' => "workflow t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'workflowid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'wfname' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
				'wfdesc' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
				'wfminstat' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
				'wfmaxstat' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
				'groupname' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'from' => 'other',
					'source' => "and t.workflowid in (
						select distinct za.workflowid 
						from wfgroup za 
						left join groupaccess zb on zb.groupaccessid = za.groupaccessid 
						where zb.groupname like P{groupname})"
				],
			],
		]);
	}	
	public function searchcombo() {
		return GetData([
			'from' => "workflow t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => false,
			'searchfield' => [
				'wfname' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
				'wfdesc' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
				'wfminstat' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
				'wfmaxstat' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
			],
		]);
	}	
	public function actionsearchwfgroup() {
		return GetData([
			'from' => 'wfgroup t 
				left join workflow p on p.workflowid = t.workflowid 
				left join groupaccess q on q.groupaccessid=t.groupaccessid',
			'sort' => [
				'datatype' => 'POST',
				'default' => 'wfgroupid'
			],
			'order' => [
				'datatype' => 'POST',
				'default' => 'desc'
			],
			'viewfield' => [
				'wfgroupid'=>[
					'type'=>'text'
				],
				'groupaccessid'=>[
					'type'=>'text',
					'from'=>'q'
				],
				'groupname'=>[
					'type'=>'text',
					'from'=>'q'
				],
				'wfbefstat'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'wfrecstat'=>[
					'type'=>'text',
					'from'=>'t'
				],
			],
			'paging' => true,
			'searchfield' => [
				'id' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'sourcefield' => 'workflowid',
					'strict' => '=',
				],
			]
		]);
	}
	public function actionsearchwfstatus() {
		return GetData([
			'from' => 'wfstatus t 
				left join workflow p on p.workflowid = t.workflowid ',
			'sort' => [
				'datatype' => 'POST',
				'default' => 'wfstatusid'
			],
			'order' => [
				'datatype' => 'POST',
				'default' => 'desc'
			],
			'viewfield' => [
				'wfstatusid'=>[
					'type'=>'text'
				],
				'workflowid'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'wfstat'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'wfstatusname'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'backcolor'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'fontcolor'=>[
					'type'=>'text',
					'from'=>'t'
				],
			],
			'paging' => true,
			'searchfield' => [
				'id' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'sourcefield' => 'workflowid',
					'strict' => '=',
				],
			]
		]);
	}
	public function actionUpload() {
		parent::actionUpload();
		UploadData([
			'spinsert' => 'ModifWorkflow',
			'spupdate' => 'ModifWorkflow',
			'arraydata' => [
				'vid'=>0,
				'wfname'=>1,
				'wfdesc'=>2,
				'wfminstat'=>3,
				'wfmaxstat'=>4,
				'recordstatus'=>5,
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'ModifWorkflow',
			'spupdate' => 'ModifWorkflow',
			'arraydata' => [
				'vid'=>(isset($_POST['workflow-workflowid'])?$_POST['workflow-workflowid']:''),
				'wfname'=>$_POST['workflow-wfname'],
				'wfdesc'=>$_POST['workflow-wfdesc'],
				'wfminstat'=>$_POST['workflow-wfminstat'],
				'wfmaxstat'=>$_POST['workflow-wfmaxstat'],
				'recordstatus'=>isset($_POST['workflow-recordstatus'])?1:0,
			]
		]);
	}
	public function actionSaveWfgroup() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'Insertwfgroup',
			'spupdate' => 'Updatewfgroup',
			'arraydata' => [
				'vid'=>(isset($_POST['wfgroupid'])?$_POST['wfgroupid']:''),
				'workflowid'=>$_POST['workflowid'],
				'groupaccessid'=>$_POST['groupaccessid'],
				'wfbefstat'=>$_POST['wfbefstat'],
				'wfrecstat'=>$_POST['wfrecstat'],
			]
		]);
	}
	public function actionSavewfstatus() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'Insertwfstatus',
			'spupdate' => 'Updatewfstatus',
			'arraydata' => [
				'vid'=>(isset($_POST['wfstatusid'])?$_POST['wfstatusid']:''),
				'workflowid'=>$_POST['workflowid'],
				'wfstat'=>$_POST['wfstat'],
				'wfstatusname'=>$_POST['wfstatusname'],
				'backcolor'=>$_POST['backcolor'],
				'fontcolor'=>$_POST['fontcolor'],
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgeworkflow',			
		]);
	}	
	public function actionPurgewfgroup() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgewfgroup',			
		]);
	}
	public function actionPurgewfstatus() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgewfstatus',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['wfname'] = GetSearchText(array('GET'),'wfname');
		$this->dataprint['wfdesc'] = GetSearchText(array('GET'),'wfdesc');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'workflowid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlewfname'] = GetCatalog('wfname');
		$this->dataprint['titlewfdesc'] = GetCatalog('wfdesc');
		$this->dataprint['titlewfminstat'] = GetCatalog('wfminstat');
		$this->dataprint['titlewfmaxstat'] = GetCatalog('wfmaxstat');
		$this->dataprint['titlegroupname'] = GetCatalog('groupname');
		$this->dataprint['titlewfbefstat'] = GetCatalog('wfbefstat');
		$this->dataprint['titlewfrecstat'] = GetCatalog('wfrecstat');
		$this->dataprint['titlecuryy'] = GetCatalog('curyy');
		$this->dataprint['titlecurvalue'] = GetCatalog('curvalue');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}