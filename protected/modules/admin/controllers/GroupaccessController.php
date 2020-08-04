<?php
class GroupaccessController extends Controller {
	public $menuname = 'groupaccess';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'groupaccessid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'groupaccessid' => 'text',
		'groupname' => 'text',
		'jumlah' => [
			'from'=>'other',
			'source'=> "(select ifnull(count(1),0) from groupmenu a where a.groupaccessid = t.groupaccessid)"
		],
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
	public function actionIndexgroupmenu() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionsearchgroupmenu();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexuserdash() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionsearchuserdash();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		return GetData([
			'from' => "groupaccess t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'groupaccessid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'groupname' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
				'menuname' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'from' => 'other',
					'source' => "and t.groupaccessid in (
						select distinct za.groupaccessid 
						from groupmenu za 
						left join menuaccess zb on zb.menuaccessid = za.menuaccessid 
						where zb.menuname like P{menuname})"
				],
			],
		]);
	}
	public function searchcombo() {
		return GetData([
			'from' => "groupaccess t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => false,
			'searchfield' => [
				'groupname' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
			],
		]);
	}
	public function actionsearchgroupmenu() {
		return GetData([
			'from' => 'groupmenu t 
				left join menuaccess p on t.menuaccessid = p.menuaccessid ',
			'sort' => [
				'datatype' => 'POST',
				'default' => 'groupmenuid'
			],
			'order' => [
				'datatype' => 'POST',
				'default' => 'desc'
			],
			'viewfield' => [
				'groupmenuid'=>[
					'type'=>'text'
				],
				'groupaccessid'=>[
					'type'=>'text'
				],
				'menuaccessid'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'description'=>[
					'type'=>'text',
					'from'=>'p'
				],
				'isread'=>[
					'type'=>'text'
				],
				'iswrite'=>[
					'type'=>'text'
				],
				'ispost'=>[
					'type'=>'text'
				],
				'isreject'=>[
					'type'=>'text'
				],
				'isupload'=>[
					'type'=>'text'
				],
				'isdownload'=>[
					'type'=>'text'
				],
				'ispurge'=>[
					'type'=>'text'
				],
			],
			'paging' => true,
			'searchfield' => [
				'id' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'sourcefield' => 'groupaccessid',
					'strict' => '=',
				],
			]
		]);
	}
	public function actionsearchuserdash() {
		return GetData([
			'from' => 'userdash t 
				left join menuaccess q on q.menuaccessid = t.menuaccessid 
				left join widget r on r.widgetid = t.widgetid',
			'sort' => [
				'datatype' => 'POST',
				'default' => 'userdashid'
			],
			'order' => [
				'datatype' => 'POST',
				'default' => 'desc'
			],
			'viewfield' => [
				'userdashid'=>[
					'type'=>'text'
				],
				'groupaccessid'=>[
					'type'=>'text'
				],
				'widgetid'=>[
					'type'=>'text',
					'from'=>'r'
				],
				'widgetname'=>[
					'type'=>'text',
					'from'=>'r'
				],
				'menuaccessid'=>[
					'type'=>'text',
					'from'=>'q'
				],
				'menuname'=>[
					'type'=>'text',
					'from'=>'q'
				],
			],
			'paging' => true,
			'searchfield' => [
				'id' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'sourcefield' => 'groupaccessid',
					'strict' => '=',
				],
			]
		]);
	}
	public function actionGetData() {
		return GetRandomHeader([
			'key' => 'groupaccessid',
			'table' => 'groupaccess'
		]);
	}
	public function actionUpload() {
		parent::actionUpload();
		UploadData($this->menuname,[
			'spinsert' => 'Modifgroupaccess',
			'spupdate' => 'Modifgroupaccess',
			'arraydata' => [
				'vid'=>0,
				'groupname'=>1
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'Modifgroupaccess',
			'spupdate' => 'Modifgroupaccess',
			'arraydata' => [
				'vid'=>(isset($_POST['groupaccess-groupaccessid'])?$_POST['groupaccess-groupaccessid']:''),
				'groupname'=>$_POST['groupaccess-groupname'],
				'recordstatus'=>isset($_POST['groupaccess-recordstatus'])?($_POST['groupaccess-recordstatus']=="on")?1:0:0,
			]
		]);
	}
	public function actionSaveGroupmenu() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'Insertgroupmenu',
			'spupdate' => 'Updategroupmenu',
			'arraydata' => [
				'vid'=>(isset($_POST['groupmenuid'])?$_POST['groupmenuid']:''),
				'menuaccessid'=>$_POST['menuaccessid'],
				'groupaccessid'=>$_POST['groupaccessid'],
				'isread'=>$_POST['isread'],
				'iswrite'=>$_POST['iswrite'],
				'ispost'=>$_POST['ispost'],
				'isreject'=>$_POST['isreject'],
				'isupload'=>$_POST['isupload'],
				'isdownload'=>$_POST['isdownload'],
				'ispurge'=>$_POST['ispurge'],
			]
		]);
	}
	public function actionSaveUserDash() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'Insertuserdash',
			'spupdate' => 'Updateuserdash',
			'arraydata' => [
				'vid'=>(isset($_POST['userdashid'])?$_POST['userdashid']:''),
				'groupaccessid'=>$_POST['groupaccessid'],
				'widgetid'=>$_POST['widgetid'],
				'menuaccessid'=>$_POST['menuaccessid'],
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgegroupaccess',			
		]);
	}
	public function actionPurgegroupmenu() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgegroupmenu',			
		]);
	}	
	public function actionPurgeuserdash() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgeuserdash',			
		]);
	}	
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['groupname'] = GetSearchText(array('GET'),'groupname');
		$this->dataprint['menuname'] = GetSearchText(array('GET'),'menuname');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'groupaccessid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlegroupname'] = GetCatalog('groupname');
		$this->dataprint['titlemenuname'] = GetCatalog('menuname');
		$this->dataprint['titlemenuurl'] = GetCatalog('menuurl');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}