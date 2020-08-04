<?php
class CompanyController extends Controller {
	public $menuname = 'company';
	private $sort = [
		'datatype' => 'POST',
		'default' => 'companyid'
	];
	private $order = [
		'default' => 'desc'
	];
	private $viewfield = [
		'companyid' => 'text',
		'companycode' => 'text',
		'companyname' => 'text',
		'address' => 'text',
		'cityid' => [
			'type'=>'text',
			'from'=>'b'
		],
		'cityname' => [
			'type'=>'text',
			'from'=>'b'
		],
		'zipcode' => 'text',
		'taxno' => 'text',
		'currencyid' => [
			'type'=>'text',
			'from'=>'c'
		],
		'currencyname' => [
			'type'=>'text',
			'from'=>'c'
		],
		'faxno' => 'text',
		'phoneno' => 'text',
		'webaddress' => 'text',
		'email' => 'text',
		'leftlogofile' => 'text',
		'rightlogofile' => 'text',
		'isholding' => 'text',
		'billto' => 'text',
		'bankacc1' => 'text',
		'bankacc2' => 'text',
		'bankacc3' => 'text',
		'recordstatus' => [
			'type'=>'text',
			'from'=>'t'
		]
	];
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexauth() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->searchauth();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexcombo() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->searchcombo();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		return GetData([
			'from' => "company t 
				left join city b on b.cityid = t.cityid 
				left join currency c on c.currencyid = t.currencyid ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'companyid' => [
					'datatype' => 'POST',
					'operatortype' => 'and' 
				],
				'companyname' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
				'companycode' => [
					'datatype' => 'POST',
					'operatortype' => 'and',
				],
			],
		]);
	}
	public function searchauth() {
		return GetData([
			'from' => "company t 
				left join city b on b.cityid = t.cityid 
				left join currency c on c.currencyid = t.currencyid ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'companyname' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
				'companycode' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
			],
			'addonsearch' => "and t.recordstatus = 1 and companyid in (".getUserObjectValues('company').")"
		]);
	}
	public function searchcombo() {
		return GetData([
			'from' => "company t 
				left join city b on b.cityid = t.cityid 
				left join currency c on c.currencyid = t.currencyid ",
			'sort' => $this->sort,
			'order' => $this->order,
			'viewfield' => $this->viewfield ,
			'paging' => true,
			'searchfield' => [
				'companyname' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
				'companycode' => [
					'datatype' => 'Q',
					'operatortype' => 'or',
				],
			],
		]);
	}
	public function actionUpload() {
		parent::actionUpload();
		UploadData($this->menuname,[
			'spinsert' => 'Insertcompany',
			'spupdate' => 'Updatecompany',
			'arraydata' => [
				'vid'=>0,
				'companycode'=>1,
				'companyname'=>2,
				'address'=>3,
				'cityid'=>[
					'column'=>4,
					'source'=>"select cityid from city where citycode = "
				],
				'zipcode'=>5,
				'taxno'=>6,
				'currencyid'=>[
					'column'=>7,
					'source'=>"select currencyid from currency where currencyname = "
				],
				'faxno'=>8,
				'phoneno'=>9,
				'webaddress'=>10,
				'email'=>11,
				'leftlogofile'=>12,
				'rightlogofile'=>13,
				'isholding'=>14,
				'billto'=>15,
				'bankacc1'=>16,
				'bankacc2'=>17,
				'bankacc3'=>18,
				'recordstatus'=>19,
			]
		]);
	}
	public function actionUploadLeftLogo() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/images/' . basename($_FILES["file-leftlogo"]["name"]);
		if (move_uploaded_file($_FILES["file-leftlogo"]["tmp_name"], $target_file)) {
			//$this->redirect(Yii::app()->request->urlReferrer);
		}
	}
	public function actionUploadRightLogo() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/images/' . basename($_FILES["file-rightlogo"]["name"]);
		if (move_uploaded_file($_FILES["file-rightlogo"]["tmp_name"], $target_file)) {
			//$this->redirect(Yii::app()->request->urlReferrer);
		}
	}
	public function actionSave() {
		parent::actionWrite();
		SaveDAta($this->menuname,[
			'spinsert' => 'Insertcompany',
			'spupdate' => 'Updatecompany',
			'arraydata' => [
				'vid'=>(isset($_POST['companyid'])?$_POST['companyid']:''),
				'companycode'=>$_POST['companycode'],
				'companyname'=>$_POST['companyname'],
				'address'=>$_POST['address'],
				'cityid'=>$_POST['cityid'],
				'zipcode'=>$_POST['zipcode'],
				'taxno'=>$_POST['taxno'],
				'currencyid'=>$_POST['currencyid'],
				'faxno'=>$_POST['faxno'],
				'phoneno'=>$_POST['phoneno'],
				'webaddress'=>$_POST['webaddress'],
				'email'=>$_POST['email'],
				'leftlogofile'=>$_POST['leftlogofile'],
				'rightlogofile'=>$_POST['rightlogofile'],
				'isholding'=>$_POST['isholding'],
				'billto'=>$_POST['billto'],
				'bankacc1'=>$_POST['bankacc1'],
				'bankacc2'=>$_POST['bankacc2'],
				'bankacc3'=>$_POST['bankacc3'],
				'recordstatus'=>$_POST['recordstatus'],
			]
		]);
	}
	public function actionPurge() {
		parent::actionPurge();
		ExecData([
			'spname' => 'Purgecompany',			
		]);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['companycode'] = GetSearchText(array('GET'),'companycode');
		$this->dataprint['companyname'] = GetSearchText(array('GET'),'companyname');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'languageid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlecompanycode'] = GetCatalog('companycode');
		$this->dataprint['titlecompanyname'] = GetCatalog('companyname');
		$this->dataprint['titleaddress'] = GetCatalog('address');
		$this->dataprint['titlecityname'] = GetCatalog('cityname');
		$this->dataprint['titlezipcode'] = GetCatalog('zipcode');
		$this->dataprint['titletaxno'] = GetCatalog('taxno');
		$this->dataprint['titlecurrencyname'] = GetCatalog('currencyname');
		$this->dataprint['titlefaxno'] = GetCatalog('faxno');
		$this->dataprint['titlephoneno'] = GetCatalog('phoneno');
		$this->dataprint['titlewebaddress'] = GetCatalog('webaddress');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}