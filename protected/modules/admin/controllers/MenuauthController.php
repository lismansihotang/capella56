<?php
class MenuauthController extends Controller {
	public $menuname = 'menuauth';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'menuauthid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'menuauthid' => 'text',
		'menuobject' => 'text',
		'jumlah' => [
			'from'=>'other',
			'source'=> "(select ifnull(count(1),0) from groupmenuauth a where a.menuauthid = t.menuauthid)"
		],
		'recordstatus' => 'text'
	];
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexGroupmenuauth() {
		if(isset($_GET['grid']))
			echo $this->actionsearchgroupmenuauth();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		return GetData([
			'from' => 'menuauth t',
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'menuauthid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'menuobject' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'groupname' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'from' => 'other',
					'source' => "and t.menuauthid in (
						select distinct za.menuauthid 
						from groupmenuauth za 
						left join groupaccess zb on zb.groupaccessid = za.groupaccessid 
						where coalesce(zb.groupname,'') like P{groupname})"
				],
				'menuvalueid' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'from' => 'other',
					'source' => "and t.menuauthid in (
						select distinct za.menuauthid 
						from groupmenuauth za 
						where coalesce(za.menuvalueid,'') like P{menuvalueid})"
				],
			]
		]);
	}
	public function actionsearchgroupmenuauth() {
		return GetData([
			'from' => 'groupmenuauth t 
				left join groupaccess q on q.groupaccessid = t.groupaccessid 
				left join menuauth r on r.menuauthid = t.menuauthid',
			'sort' => [
				'datatype' => 'POST',
				'default' => 'groupmenuauthid'
			],
			'order' => [
				'datatype' => 'POST',
				'default' => 'desc'
			],
			'viewfield' => [
				'groupmenuauthid'=>[
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
				'menuauthid'=>[
					'type'=>'text',
					'from'=>'r'
				],
				'menuvalueid'=>[
					'type'=>'text',
					'from'=>'t'
				],
			],
			'paging' => true,
			'searchfield' => [
				'id' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'sourcefield' => 'menuauthid',
					'strict' => '=',
				],
			]
		]);
	}
	public function actionGetData() {
		return GetRandomHeader([
			'key' => 'menuauthid',
			'table' => 'menuauth'
		]);
	}
	public function actionUpload() {
		parent::actionUpload();
		UploadData([
			'spinsert' => 'Modifmenuauth',
			'spupdate' => 'Modifmenuauth',
			'arraydata' => [
				'vid'=>0,
				'menuobject'=>1,
				'recordstatus'=>2,
			]
		]);
	} 
	public function actionSave() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'Modifmenuauth',
			'spupdate' => 'Modifmenuauth',
			'arraydata' => [
				'vid'=>(isset($_POST['menuauth-menuauthid'])?$_POST['menuauth-menuauthid']:''),
				'menuobject'=>$_POST['menuauth-menuobject'],
				'recordstatus'=>(isset($_POST['menuauth-recordstatus'])?($_POST['menuauth-recordstatus']=="on")?1:0:0),
			]
		]);
	}
	public function actionSaveGroupmenuauth() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'Insertgroupmenuauth',
			'spupdate' => 'Updategroupmenuauth',
			'arraydata' => [
				'vid'=>(isset($_POST['groupmenuauthid'])?$_POST['groupmenuauthid']:''),
				'groupaccessid'=>$_POST['groupaccessid'],
				'menuauthid'=>$_POST['menuauthid'],
				'menuvalueid'=>$_POST['menuvalueid']
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgemenuauth',			
		]);
	}
	public function actionPurgeGroupmenuauth() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgegroupmenuauth',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['menuobject'] = GetSearchText(array('GET'),'menuobject');
		$this->dataprint['groupname'] = GetSearchText(array('GET'),'groupname');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'menuauthid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlemenuobject'] = GetCatalog('menuobject');
		$this->dataprint['titlegroupname'] = GetCatalog('groupname');
		$this->dataprint['titlemenuvalue'] = GetCatalog('menuvalue');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}