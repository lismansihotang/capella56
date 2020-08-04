<?php
class TranslogController extends Controller {
	public $menuname = 'translog';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'translogid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'translogid' => 'text',
		'username' => 'text',
		'createddate' => 'text',
		'useraction' => 'text',
		'newdata' => 'text',
		'olddata' => 'text',
		'menuname' => 'text',
		'tableid' => 'text',
		'tablehid' => 'text',
		'ippublic' => 'text',
		'iplocal' => 'text',
		'lat' => 'text',
		'lng' => 'text',
		'recordstatus' => 'text'
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
			'from' => "translog t ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'translogid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'username' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
				'createddate' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
				'useraction' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
				'newdata' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
				'olddata' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
				'menuname' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
				'tableid' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
			],
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgetranslog',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['namauser'] = GetSearchText(array('GET'),'username');
		$this->dataprint['createddate'] = GetSearchText(array('GET'),'createddate');
		$this->dataprint['useraction'] = GetSearchText(array('GET'),'useraction');
		$this->dataprint['olddata'] = GetSearchText(array('GET'),'olddata');
		$this->dataprint['newdata'] = GetSearchText(array('GET'),'newdata');
		$this->dataprint['menuname'] = GetSearchText(array('GET'),'menuname');
		$this->dataprint['tableid'] = GetSearchText(array('GET'),'tableid');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'translogid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlelanguagename'] = GetCatalog('languagename');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}