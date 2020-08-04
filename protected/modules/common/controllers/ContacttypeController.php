<?php
class ContacttypeController extends Controller {
	public $menuname = 'contacttype';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'contacttypeid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'contacttypeid' => 'text',
		'contacttypename' => 'text',
		'recordstatus' => 'text'
	];
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->searchcombo();
		else
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		return GetData([
			'from' => "contacttype t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'contacttypeid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'contacttypename' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
			],
		]);
	}
	public function searchcombo() {
		return GetData([
			'from' => "contacttype t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => false,
			'searchfield' => [
				'contacttypeid' => [
					'datatype' => 'Q',
					'operatortype' => 'or' 
				],
				'contacttypename' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
			],
		]);
	}
	public function actionUpload() {
		parent::actionUpload();
		UploadData($this->menuname,[
			'spinsert' => 'Insertcontacttype',
			'spupdate' => 'Updatecontacttype',
			'arraydata' => [
				'vid'=>0,
				'contacttypename'=>1,
				'recordstatus'=>2,
			]
		]);
	}
	public function actionSave() {
		parent::actionWrite();
		SaveData($this->menuname,[
			'spinsert' => 'Insertcontacttype',
			'spupdate' => 'Updatecontacttype',
			'arraydata' => [
				'vid'=>(isset($_POST['contacttypeid'])?$_POST['contacttypeid']:''),
				'contacttypename'=>$_POST['contacttypename'],
				'recordstatus'=>$_POST['recordstatus'],
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgecontacttype',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['contacttypename'] = GetSearchText(array('GET'),'contacttypename');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'contacttypeid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlecontacttypename'] = GetCatalog('contacttypename');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}