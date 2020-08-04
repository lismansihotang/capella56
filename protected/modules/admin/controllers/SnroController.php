<?php
class SnroController extends Controller {
	public $menuname = 'snro';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'snroid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'snroid' => 'text',
		'description' => 'text',
		'formatdoc' => 'text',
		'formatno' => 'text',
		'repeatby' => 'text',
		'jumlah' => [
			'from'=>'other',
			'source'=> "(select ifnull(count(1),0) from snrodet a where a.snroid = t.snroid)"
		],
		'recordstatus' => 'text'
	];
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['combo']))
			echo $this->search();
		else
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexsnrodet() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionsearchsnrodet();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		return GetData([
			'from' => "snro t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'snroid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'description' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
				'formatdoc' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
				'formatno' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
				'repeatby' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
			],
		]);
	}
	public function searchcombo() {
		return GetData([
			'from' => "snro t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => false,
			'searchfield' => [
				'description' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
				'formatdoc' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
				'formatno' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
				'repeatby' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
			],
		]);
	}
	public function actionsearchsnrodet() {
		return GetData([
			'from' => 'snrodet t 
				left join snro p on t.snroid = p.snroid 
				left join plant q on q.plantid = t.plantid ',
			'sort' => [
				'datatype' => 'POST',
				'default' => 'snrodid'
			],
			'order' => [
				'datatype' => 'POST',
				'default' => 'desc'
			],
			'viewfield' => [
				'snrodid'=>[
					'type'=>'text'
				],
				'snroid'=>[
					'type'=>'text',
					'from'=>'t'
				],
				'description'=>[
					'type'=>'text',
					'from'=>'p'
				],
				'plantid'=>[
					'type'=>'text',
					'from'=>'q'
				],
				'plantcode'=>[
					'type'=>'text',
					'from'=>'q'
				],
				'curdd'=>[
					'type'=>'text'
				],
				'curmm'=>[
					'type'=>'text'
				],
				'curyy'=>[
					'type'=>'text'
				],
				'curvalue'=>[
					'type'=>'text'
				],
			],
			'paging' => true,
			'searchfield' => [
				'id' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
					'sourcefield' => 'snroid',
					'strict' => '=',
				],
			]
		]);
	}
	public function actionGetData() {
		return GetRandomHeader([
			'key' => 'snroid',
			'table' => 'snro'
		]);
	}

	public function actionUpload() {
		parent::actionUpload();
		UploadData($this->menuname,[
			'spinsert' => 'Modifsnro',
			'spupdate' => 'Modifsnro',
			'arraydata' => [
				'vid'=>0,
				'description'=>1,
				'formatdoc'=>2,
				'formatno'=>3,
				'repeatby'=>4,
				'recordstatus'=>5,
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'Modifsnro',
			'spupdate' => 'Modifsnro',
			'arraydata' => [
				'vid'=>(isset($_POST['snro-snroid'])?$_POST['snro-snroid']:''),
				'description'=>$_POST['snro-description'],
				'formatdoc'=>$_POST['snro-formatdoc'],
				'formatno'=>$_POST['snro-formatno'],
				'repeatby'=>$_POST['snro-repeatby'],
				'recordstatus'=>(isset($_POST['snro-recordstatus'])?1:0),
			]
		]);
	}
	public function actionSavesnrodet() {
		parent::actionWrite();
		SaveData([
			'spinsert' => 'Insertsnrodet',
			'spupdate' => 'Updatesnrodet',
			'arraydata' => [
				'vid'=>(isset($_POST['snrodid'])?$_POST['snrodid']:''),
				'plantid'=>$_POST['plantid'],
				'snroid'=>$_POST['snroid'],
				'curdd'=>$_POST['curdd'],
				'curmm'=>$_POST['curmm'],
				'curyy'=>$_POST['curyy'],
				'curvalue'=>$_POST['curvalue'],
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgesnro',			
		]);
	}
	public function actionPurgesnrodet() {
		parent::actionIndex();
		ExecData([
			'spname' => 'Purgesnrodet',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['description'] = GetSearchText(array('GET'),'description');
		$this->dataprint['formatdoc'] = GetSearchText(array('GET'),'formatdoc');
		$this->dataprint['formatno'] = GetSearchText(array('GET'),'formatno');
		$this->dataprint['repeatby'] = GetSearchText(array('GET'),'repeatby');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'languageid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titleformatdoc'] = GetCatalog('formatdoc');
		$this->dataprint['titleformatno'] = GetCatalog('formatno');
		$this->dataprint['titlerepeatby'] = GetCatalog('repeatby');
		$this->dataprint['titleplantcode'] = GetCatalog('plantcode');
		$this->dataprint['titlecurdd'] = GetCatalog('curdd');
		$this->dataprint['titlecurmm'] = GetCatalog('curmm');
		$this->dataprint['titlecuryy'] = GetCatalog('curyy');
		$this->dataprint['titlecurvalue'] = GetCatalog('curvalue');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}