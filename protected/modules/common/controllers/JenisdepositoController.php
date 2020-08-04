<?php
class JenisdepositoController extends Controller {
	public $menuname = 'jenisdeposito';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'jenisdepositoid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'jenisdepositoid' => 'text',
		'namadeposito' => 'text',
		'jumlah' => 'number',
		'bunga' => 'number',
		'fixed' => 'text',
		'tenor' => 'text',
		'isauto' => 'text',
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
	public function search() {
		return GetData([
			'from' => "jenisdeposito t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'jenisdepositoid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'namadeposito' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
			],
		]);
	}
	public function searchcombo() {
		return GetData([
			'from' => "jenisdeposito t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => false,
			'searchfield' => [
				'jenisdepositoid' => [
					'datatype' => 'Q',
					'operatortype' => 'or' 
				],
				'namadeposito' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
			],
		]);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertjenisdeposito(:vnamadeposito,:vjumlah,:vbunga,:vfixed,:vtenor,:visauto,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatejenisdeposito(:vid,:vnamadeposito,:vjumlah,:vbunga,:vfixed,:vtenor,:visauto,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vnamadeposito',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vjumlah',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vbunga',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vfixed',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vtenor',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':visauto',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		UploadData($this->menuname,[
			'spinsert' => 'Insertjenisdeposito',
			'spupdate' => 'Updatejenisdeposito',
			'arraydata' => [
				'vid'=>0,
				'namadeposito'=>1,
				'jumlah'=>2,
				'bunga'=>3,
				'fixed'=>4,
				'tenor'=>5,
				'isauto'=>6,
				'recordstatus'=>7,
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData($this->menuname,[
			'spinsert' => 'Insertjenisdeposito',
			'spupdate' => 'Updatejenisdeposito',
			'arraydata' => [
				'vid'=>(isset($_POST['jenisdepositoid'])?$_POST['jenisdepositoid']:''),
				'namadeposito'=>$_POST['namadeposito'],
				'jumlah'=>$_POST['jumlah'],
				'bunga'=>$_POST['bunga'],
				'fixed'=>$_POST['fixed'],
				'tenor'=>$_POST['tenor'],
				'isauto'=>$_POST['isauto'],
				'recordstatus'=>$_POST['recordstatus'],
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgejenisdeposito',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['namadeposito'] = GetSearchText(array('GET'),'namadeposito');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'jenisdepositoid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlenamadeposito'] = GetCatalog('namadeposito');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}