<?php
class UseraccessController extends Controller {
	public $menuname = 'useraccess';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'useraccessid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'useraccessid' => 'text',
		'username' => 'text',
		'realname' => 'text',
		'password' => 'text',
		'email' => 'text',
		'phoneno' => 'text',
		'languageid' => [
			'type' => 'text',
			'from' => 'l'
		],
		'languagename' => [
			'type' => 'text',
			'from' => 'l'
		],
		'themeid' => [
			'type' => 'text',
			'from' => 'h'
		],
		'themename' => [
			'type' => 'text',
			'from' => 'h'
		],
		'isonline' => 'text',
		'jumlah' => [
			'from'=>'other',
			'source'=> "(select ifnull(count(1),0) from usergroup a where a.useraccessid = t.useraccessid)"
		],
		'recordstatus' => [
			'type' => 'text',
			'from' => 't'
		]
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
	public function actionIndexusergroup() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionSearchusergroup();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexuserfav() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionSearchuserfav();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		return GetData([
			'from' => 'useraccess t 
				left join language l on l.languageid=t.languageid
				left join theme h on h.themeid=t.themeid',
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'useraccessid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'username' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'realname' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'email' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'phoneno' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'languagename' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'from' => 'l'
				],
				'groupname' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'from' => 'other',
					'source' => "and t.useraccessid in (
						select distinct za.useraccessid 
						from usergroup za 
						left join groupaccess zb on zb.groupaccessid = za.groupaccessid 
						where coalesce(zb.groupname,'') like P{groupname})"
				],
			]
		]);
	}
	public function actionsearchusergroup() {
		return GetData([
			'from' => 'usergroup t 
				left join groupaccess q on q.groupaccessid = t.groupaccessid',
			'sort' => [
				'datatype' => 'POST',
				'default' => 'usergroupid'
			],
			'order' => [
				'datatype' => 'POST',
				'default' => 'desc'
			],
			'viewfield' => [
				'usergroupid'=>[
					'type'=>'text'
				],
				'useraccessid'=>[
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
			],
			'paging' => true,
			'searchfield' => [
				'id' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'sourcefield' => 'useraccessid',
					'strict' => '=',
				],
			]
		]);
	}	
	public function actionsearchuserfav() {
		return GetData([
			'from' => 'userfav t 
				left join useraccess p on p.useraccessid = t.useraccessid
				left join menuaccess q on q.menuaccessid = t.menuaccessid',
			'sort' => [
				'datatype' => 'POST',
				'default' => 'userfavid'
			],
			'order' => [
				'datatype' => 'POST',
				'default' => 'desc'
			],
			'viewfield' => [
				'userfavid'=>[
					'type'=>'text'
				],
				'useraccessid'=>[
					'type'=>'text'
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
					'sourcefield' => 'useraccessid',
					'strict' => '=',
				],
			]
		]);
	}	
	public function actionGetData() {
		return GetRandomHeader([
			'key' => 'useraccessid',
			'table' => 'useraccess'
		]);
	}
	public function actionUpload() {
		parent::actionUpload();
		SaveData([
			'spinsert' => 'Modifuseraccess',
			'spupdate' => 'Modifuseraccess',
			'arraydata' => [
				'vid'=>0,
				'username'=>1,
				'realname'=>2,
				'password'=>3,
				'email'=>4,
				'phoneno'=>5,
				'languageid'=>[
					'column' => 6,
					'source' => 'select languageid from language where languagename = '
				],
				'languageid'=>[
					'column' => 7,
					'source' => 'select themeid from theme where themename = '
				],
				'recordstatus'=>8,
			]
		]);	
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'Modifuseraccess',
			'spupdate' => 'Modifuseraccess',
			'arraydata' => [
				'vid'=>(isset($_POST['useraccess-useraccessid'])?$_POST['useraccess-useraccessid']:''),
				'username'=>$_POST['useraccess-username'],
				'realname'=>$_POST['useraccess-realname'],
				'password'=>$_POST['useraccess-password'],
				'email'=>$_POST['useraccess-email'],
				'phoneno'=>$_POST['useraccess-phoneno'],
				'languageid'=>$_POST['useraccess-languageid'],
				'themeid'=>$_POST['useraccess-themeid'],
				'recordstatus'=>(isset($_POST['useraccess-recordstatus'])?1:0),
			]
		]);	
	}
	public function actionSaveusergroup() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'Insertusergroup',
			'spupdate' => 'Updateusergroup',
			'arraydata' => [
				'vid'=>(isset($_POST['usergroupid'])?$_POST['usergroupid']:''),
				'useraccessid'=>$_POST['useraccessid'],
				'groupaccessid'=>$_POST['groupaccessid'],
			]
		]);
	}
	public function actionSaveuserfav() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'Insertuserfav',
			'spupdate' => 'Updateuserfav',
			'arraydata' => [
				'vid'=>(isset($_POST['userfavid'])?$_POST['userfavid']:''),
				'useraccessid'=>$_POST['useraccessid'],
				'menuaccessid'=>$_POST['menuaccessid'],
			]
		]);
	}
	public function actionSaveShortcut(){
		SaveData([
			'spinsert' => 'UpdateShortcut',
			'spupdate' => 'UpdateShortcut',
			'arraydata' => [
				'useraccessid'=>Yii::app()->user->getuseraccessid(),
				'menuaccessid'=>$_POST['name'],
			]
		]);
	}
	public function actionRemoveShortcut(){
		SaveData([
			'spinsert' => 'RemoveShortcut',
			'spupdate' => 'RemoveShortcut',
			'arraydata' => [
				'useraccessid'=>Yii::app()->user->getuseraccessid(),
				'menuaccessid'=>$_POST['name'],
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgeuseraccess',			
		]);
	}
	public function actionPurgeUserGroup() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgeusergroup',			
		]);
	}
	public function actionPurgeUserfav() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgeuserfav',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['namauser'] = GetSearchText(array('GET'),'username');
		$this->dataprint['realname'] = GetSearchText(array('GET'),'realname');
		$this->dataprint['email'] = GetSearchText(array('GET'),'email');
		$this->dataprint['phoneno'] = GetSearchText(array('GET'),'phoneno');
		$this->dataprint['languagename'] = GetSearchText(array('GET'),'languagename');
		$this->dataprint['themename'] = GetSearchText(array('GET'),'themename');
		$this->dataprint['groupname'] = GetSearchText(array('GET'),'groupname');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'useraccessid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlelanguagename'] = GetCatalog('languagename');
		$this->dataprint['titleusername'] = GetCatalog('username');
		$this->dataprint['titlerealname'] = GetCatalog('realname');
		$this->dataprint['titleemail'] = GetCatalog('email');
		$this->dataprint['titlephoneno'] = GetCatalog('phoneno');
		$this->dataprint['titlethemename'] = GetCatalog('themename');
		$this->dataprint['titlegroupname'] = GetCatalog('groupname');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}