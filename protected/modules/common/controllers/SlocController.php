<?php
class SlocController extends Controller {
	public $menuname = 'sloc';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexstoragebin() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->searchstoragebin();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexcombo() {
		if(isset($_GET['grid']))
			echo $this->searchcombo();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndextrxplant() {
		if(isset($_GET['grid']))
			echo $this->searchtrxplant();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndextrxplantsource() {
		if(isset($_GET['grid']))
			echo $this->searchtrxplantsource();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndextrxcomsource() {
		if(isset($_GET['grid']))
			echo $this->searchtrxcomsource();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndextrxplantsloc() {
		if(isset($_GET['grid']))
			echo $this->searchtrxplantsloc();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexslocfr() {
		if(isset($_GET['grid']))
			echo $this->searchslocfr();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexslocpp() {
		if(isset($_GET['grid']))
			echo $this->searchslocpp();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$slocid = GetSearchText(array('POST','Q'),'slocid');
		$plantcode = GetSearchText(array('POST','Q'),'plantcode');
		$sloccode = GetSearchText(array('POST','Q'),'sloccode');
		$description = GetSearchText(array('POST','Q'),'description');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','slocid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = '
			from sloc t 
			left join plant a on a.plantid = t.plantid 
			left join company b on b.companyid = a.companyid';
		$where = "
			where (coalesce(t.slocid,'') like '".$slocid."') and (coalesce(a.plantcode,'') like '".$plantcode."') and (coalesce(sloccode,'') like '".$sloccode."') 
			and (coalesce(t.description,'') like '".$description."')";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = ' select t.isprd,t.isbb,t.isbj,t.slocid,t.plantid,t.sloccode,t.description,a.plantcode,t.recordstatus '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
			'slocid'=>$data['slocid'],
			'plantid'=>$data['plantid'],
			'plantcode'=>$data['plantcode'],
			'sloccode'=>$data['sloccode'],
			'description'=>$data['description'],
			'isprd'=>$data['isprd'],
			'isbb'=>$data['isbb'],
			'isbj'=>$data['isbj'],
			'recordstatus'=>$data['recordstatus'],
		);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchstoragebin() {
		header("Content-Type: application/json");
		$id = 0;	
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
		}
		else 
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
		}
		$storagebinid = GetSearchText(array('POST','Q'),'storagebinid');
		$description = GetSearchText(array('POST','Q'),'description');
		$sloccode = GetSearchText(array('POST','Q'),'sloccode');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','storagebinid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
					->select('count(1) as total')
					->from('storagebin t')
					->leftjoin('sloc a','a.slocid = t.slocid')
					->where("((coalesce(t.storagebinid,'') like :storagebinid) or (coalesce(t.description,'') like :description) or (coalesce(a.sloccode,'') like :sloccode))  
						and t.recordstatus = 1
						and a.slocid in (".getUserObjectValues('sloc').")",
							array(':storagebinid'=>$storagebinid,':description'=>$description,':sloccode'=>$sloccode))
					->queryScalar();
		}
		else
			if (isset($_GET['single']))
		{
			$cmd = Yii::app()->db->createCommand()
					->select('count(1) as total')
					->from('storagebin t')
					->leftjoin('sloc a','a.slocid = t.slocid')
					->where("((coalesce(t.storagebinid,'') like :storagebinid) or (coalesce(t.description,'') like :description) or (coalesce(a.sloccode,'') like :sloccode)) and t.recordstatus = 1",
							array(':storagebinid'=>$storagebinid,':description'=>$description,':sloccode'=>$sloccode))
					->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
					->select('count(1) as total')
					->from('storagebin t')
					->leftjoin('sloc a','a.slocid = t.slocid')
					->where("t.slocid = :slocid",
							array(':slocid'=>$id))
					->queryScalar();
		}		
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.sloccode')
				->from('storagebin t')
				->leftjoin('sloc a','a.slocid = t.slocid')
					->where("((coalesce(t.storagebinid,'') like :storagebinid) or (coalesce(t.description,'') like :description) or (coalesce(a.sloccode,'') like :sloccode)) and t.recordstatus = 1
						and a.slocid in (".getUserObjectValues('sloc').")",
							array(':storagebinid'=>$storagebinid,':description'=>$description,':sloccode'=>$sloccode))
				->order($sort.' '.$order)
				->queryAll();
		}
		else
			if (isset($_GET['single'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.sloccode')
				->from('storagebin t')
				->leftjoin('sloc a','a.slocid = t.slocid')
					->where("((coalesce(t.storagebinid,'') like :storagebinid) or (coalesce(t.description,'') like :description) or (coalesce(a.sloccode,'') like :sloccode)) and t.recordstatus = 1",
							array(':storagebinid'=>$storagebinid,':description'=>$description,':sloccode'=>$sloccode))
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.sloccode')
				->from('storagebin t')
				->leftjoin('sloc a','a.slocid = t.slocid')
				->where("t.slocid = :slocid",
					array(':slocid'=>$id))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'storagebinid'=>$data['storagebinid'],
				'description'=>$data['description'],
				'ismultiproduct'=>$data['ismultiproduct'],
				'slocid'=>$data['slocid'],
				'sloccode'=>$data['sloccode'],
				'qtymax'=>Yii::app()->format->formatNumber($data['qtymax']),
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchcombo() {
		header("Content-Type: application/json");			
		$plantcode = GetSearchText(array('Q'),'plantcode');
		$company = GetSearchText(array('Q'),'company');
		$sloccode = GetSearchText(array('Q'),'sloccode');
		$description = GetSearchText(array('Q'),'description');
		$page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','slocid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
		$offset = ($page-1) * $rows;			
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = '
			from sloc t 
			left join plant p on p.plantid = t.plantid 
			left join company c on c.companyid = p.companyid';
		$where = "
			where ((p.plantcode like '".$plantcode."') or (sloccode like '".$sloccode."') 
			or (c.companyname like '".$company."') or (t.description like '".$description."')) and 
			t.recordstatus=1 ";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.isprd,t.slocid,t.plantid,p.plantcode,t.sloccode,t.description,t.recordstatus, concat(t.sloccode,"-",t.description) as slocdesc '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order)->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'slocid'=>$data['slocid'],
				'plantid'=>$data['plantid'],
				'plantcode'=>$data['plantcode'],
				'sloccode'=>$data['sloccode'],
				'description'=>$data['description'],
				'isprd'=>$data['isprd'],
				'recordstatus'=>$data['recordstatus'],
				'slocdesc'=>$data['slocdesc'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchtrxplant() {
		header("Content-Type: application/json");			
		$plantid = GetSearchText(array('GET'),'plantid',0,'int');
		$plantcode = GetSearchText(array('Q'),'plantcode');
		$company = GetSearchText(array('Q'),'company');
		$sloccode = GetSearchText(array('Q'),'sloccode');
		$description = GetSearchText(array('Q'),'description');
		$page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','slocid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
		$offset = ($page-1) * $rows;			
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = '
			from sloc t 
			left join plant p on p.plantid = t.plantid 
			left join company c on c.companyid = p.companyid';
		$where = "
			where ((p.plantcode like '".$plantcode."') or (sloccode like '".$sloccode."') 
			or (c.companyname like '".$company."') or (t.description like '".$description."')) and 
			t.recordstatus=1 ";
		(($plantid!='')? $where.=" and t.plantid = ".$plantid:'');		
		$where .= " and t.plantid in (".getUserObjectValues('plant').")";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.isprd,t.slocid,t.plantid,p.plantcode,t.sloccode,t.description,t.recordstatus '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order)->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
				'slocid'=>$data['slocid'],
				'plantid'=>$data['plantid'],
				'plantcode'=>$data['plantcode'],
				'sloccode'=>$data['sloccode'],
				'description'=>$data['description'],
				'isprd'=>$data['isprd'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchtrxplantsource() {
		header("Content-Type: application/json");			
		$plantid = GetSearchText(array('GET'),'plantid',0,'int');
		$productid = GetSearchText(array('GET'),'productid',0,'int');
		$plantcode = GetSearchText(array('Q'),'plantcode');
		$company = GetSearchText(array('Q'),'company');
		$sloccode = GetSearchText(array('Q'),'sloccode');
		$description = GetSearchText(array('Q'),'description');
		$page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','slocid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
		$offset = ($page-1) * $rows;			
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = '
			from sloc t 
			left join plant p on p.plantid = t.plantid 
			left join company c on c.companyid = p.companyid';
		$where = "
			where ((p.plantcode like '".$plantcode."') or (sloccode like '".$sloccode."') 
			or (c.companyname like '".$company."') or (t.description like '".$description."')) 
			and t.recordstatus=1 
			and t.slocid in (
				select distinct zz.slocid 
				from productplant zz
				join sloc zzz on zzz.slocid = zz.slocid 
				where zz.issource = 1 ".(($plantid!='')? " 
				and zzz.plantid = ".$plantid:'').
				(($productid != '')?" and zz.productid = ".$productid:'')."
			) ";
		(($plantid!='')? $where.=" and t.plantid = ".$plantid:'');		
		$where .= " and t.plantid in (".getUserObjectValues('plant').")";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.isprd,t.slocid,t.plantid,p.plantcode,t.sloccode,t.description,t.recordstatus '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order)->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
				'slocid'=>$data['slocid'],
				'plantid'=>$data['plantid'],
				'plantcode'=>$data['plantcode'],
				'sloccode'=>$data['sloccode'],
				'description'=>$data['description'],
				'isprd'=>$data['isprd'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchtrxcomsource() {
		header("Content-Type: application/json");			
		$productid = GetSearchText(array('GET'),'productid',0,'int');
		$plantcode = GetSearchText(array('Q'),'plantcode');
		$company = GetSearchText(array('Q'),'company');
		$sloccode = GetSearchText(array('Q'),'sloccode');
		$description = GetSearchText(array('Q'),'description');
		$page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','slocid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
		$offset = ($page-1) * $rows;			
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = '
			from sloc t 
			left join plant p on p.plantid = t.plantid 
			left join company c on c.companyid = p.companyid';
		$where = "
			where ((p.plantcode like '".$plantcode."') or (sloccode like '".$sloccode."') 
			or (c.companyname like '".$company."') or (t.description like '".$description."')) 
			and t.recordstatus=1 
			and t.slocid in (
				select distinct zz.slocid 
				from productplant zz
				join sloc zzz on zzz.slocid = zz.slocid 
				where zz.issource = 1 ".
				(($productid != '')?" and zz.productid = ".$productid:'')."
			) ";
		$where .= " and c.companyid  in (".getUserObjectValues('company').")";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.isprd,t.slocid,t.plantid,p.plantcode,t.sloccode,t.description,t.recordstatus '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order)->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'slocid'=>$data['slocid'],
				'plantid'=>$data['plantid'],
				'plantcode'=>$data['plantcode'],
				'sloccode'=>$data['sloccode'],
				'description'=>$data['description'],
				'isprd'=>$data['isprd'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchtrxplantsloc() {
		header("Content-Type: application/json");			
		$plantid = GetSearchText(array('GET'),'plantid',0,'int');
		$productid = GetSearchText(array('GET'),'productid',0,'int');
		$issource = GetSearchText(array('GET'),'issource',0,'int');
		$plantcode = GetSearchText(array('Q'),'plantcode');
		$company = GetSearchText(array('Q'),'company');
		$sloccode = GetSearchText(array('Q'),'sloccode');
		$description = GetSearchText(array('Q'),'description');
		$page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','slocid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
		$offset = ($page-1) * $rows;			
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = '
			from sloc t 
			left join plant p on p.plantid = t.plantid 
			left join company c on c.companyid = p.companyid';
		$where = "
			where ((p.plantcode like '".$plantcode."') or (sloccode like '".$sloccode."') 
			or (c.companyname like '".$company."') or (t.description like '".$description."')) and 
			t.recordstatus=1 ";
		(($plantid!='')? $where.=" and t.plantid = ".$plantid:'');		
		(($productid!='')? $where.=" and t.slocid in (
			select distinct a.slocid 
			from productplant a 
			join sloc b on b.slocid = a.slocid 
			where a.productid = ".$productid." 
			and b.plantid = ".$plantid.")":'');		
		$where .= " and t.plantid in (".getUserObjectValues('plant').")";
		$where .= " and t.slocid in (".getUserObjectValues('sloc').")";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.isprd,t.slocid,t.plantid,p.plantcode,t.sloccode,t.description,t.recordstatus '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order)->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
				'slocid'=>$data['slocid'],
				'plantid'=>$data['plantid'],
				'plantcode'=>$data['plantcode'],
				'sloccode'=>$data['sloccode'],
				'description'=>$data['description'],
				'isprd'=>$data['isprd'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchslocfr() {
		header("Content-Type: application/json");			
		$plantid = GetSearchText(array('GET'),'plantid',0,'int');
		$formrequestid = GetSearchText(array('GET'),'formrequestid',0,'int');
		$plantcode = GetSearchText(array('Q'),'plantcode');
		$company = GetSearchText(array('Q'),'company');
		$sloccode = GetSearchText(array('Q'),'sloccode');
		$description = GetSearchText(array('Q'),'description');
		$page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','slocid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
		$offset = ($page-1) * $rows;			
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = '
			from sloc t 
			left join plant p on p.plantid = t.plantid 
			left join company c on c.companyid = p.companyid';
		$where = "
			where ((p.plantcode like '".$plantcode."') or (sloccode like '".$sloccode."') 
			or (c.companyname like '".$company."') or (t.description like '".$description."')) and 
			t.recordstatus=1 ";
		(($plantid!='')? $where.=" and t.plantid = ".$plantid:'');		
		$where .= " and t.plantid in (".getUserObjectValues('plant').")";
		$cmd = Yii::app()->db->createCommand('select isjasa from formrequest where formrequestid = '.$formrequestid)->queryScalar();
		if ($cmd == 0) {
			$where .= " and t.slocid in (
				select distinct sloctoid
				from formrequestraw
				where formrequestid = ".$formrequestid."			
			)";
		} else {
			(($formrequestid!='')?$where .= " and t.slocid in (
				select distinct sloctoid
				from formrequestjasa
				where formrequestid = ".$formrequestid."			
			)":'');			
		}
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.isprd,t.slocid,t.plantid,p.plantcode,t.sloccode,t.description,t.recordstatus '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order)->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
				'slocid'=>$data['slocid'],
				'plantid'=>$data['plantid'],
				'plantcode'=>$data['plantcode'],
				'sloccode'=>$data['sloccode'],
				'description'=>$data['description'],
				'isprd'=>$data['isprd'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchslocpp() {
		header("Content-Type: application/json");			
		$plantid = GetSearchText(array('GET'),'plantid',0,'int');
		$productplanid = GetSearchText(array('GET'),'productplanid',0,'int');
		$plantcode = GetSearchText(array('Q'),'plantcode');
		$company = GetSearchText(array('Q'),'company');
		$sloccode = GetSearchText(array('Q'),'sloccode');
		$description = GetSearchText(array('Q'),'description');
		$page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','slocid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
		$offset = ($page-1) * $rows;			
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = '
			from sloc t 
			left join plant p on p.plantid = t.plantid 
			left join company c on c.companyid = p.companyid';
		$where = "
			where ((p.plantcode like '".$plantcode."') or (sloccode like '".$sloccode."') 
			or (c.companyname like '".$company."') or (t.description like '".$description."')) and 
			t.recordstatus=1 ";
		(($plantid != '')? $where.=" and t.plantid = ".$plantid:'');		
		$where .= " and t.plantid in (".getUserObjectValues('plant').")";
	        (($productplanid != '')?
			$where .= " and t.slocid in (
				select distinct slocfromid
				from productplandetail
				where productplanid = ".$productplanid."			
			)":"");
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.isprd,t.slocid,t.plantid,p.plantcode,t.sloccode,t.description,t.recordstatus '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order)->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
				'slocid'=>$data['slocid'],
				'plantid'=>$data['plantid'],
				'plantcode'=>$data['plantcode'],
				'sloccode'=>$data['sloccode'],
				'description'=>$data['description'],
				'isprd'=>$data['isprd'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
  }
  public function actionGetData() {
		$id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'slocid' => $id
		));
	}
	private function ModifyData($connection,$arraydata) {
		$id = (int)$arraydata[0];
		$sql = 'call Modifsloc(:vid,:vplantid,:vsloccode,:vdescription,:visprd,:visbb,:visbj,:vrecordstatus,:vdatauser)';
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		$command->bindvalue(':vplantid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vsloccode',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':visprd',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':visbb',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':visbj',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();	
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-sloc"]["name"]);
		if (move_uploaded_file($_FILES["file-sloc"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$plantcode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$plantid = Yii::app()->db->createCommand("select plantid from plant where plantcode = '".$plantcode."'")->queryScalar();
					$sloccode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$isprd = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$this->ModifyData($connection,array($id,$plantid,$sloccode,$description,$recordstatus));
				}
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,'Line: '.$row.' ==> '.implode(" ",$e->errorInfo));
			}
    }
	}
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['sloc-slocid'])?$_POST['sloc-slocid']:''),
				$_POST['sloc-plantid'],
				$_POST['sloc-sloccode'],
				$_POST['sloc-description'],
				isset($_POST['sloc-isprd'])?1:0,
				isset($_POST['sloc-isbb'])?1:0,
				isset($_POST['sloc-isbj'])?1:0,
				isset($_POST['sloc-recordstatus'])?1:0));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	private function ModifyDatastoragebin($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertstoragebin(:vdescription,:vismultiproduct,:vslocid,:vqtymax,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatestoragebin(:vid,:vdescription,:vismultiproduct,:vslocid,:vqtymax,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$this->DeleteLock($this->menuname, $arraydata[0]);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		}
		$command->bindvalue(':vdescription',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vismultiproduct',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vslocid',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vqtymax',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionSavestoragebin() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDatastoragebin($connection,array((isset($_POST['storagebinid'])?$_POST['storagebinid']:''),
				$_POST['description'], $_POST['ismultiproduct'],$_POST['slocid'],$_POST['qtymax'],
				$_POST['recordstatus']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	public function actionPurge() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgesloc(:vid,:vdatauser)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vdatauser',GetUserPC(),PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
		}
		else {
			GetMessage(true,'chooseone');
		}
	}
	public function actionPurgestoragebin() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgestoragebin(:vid,:vdatauser)';
				$command=$connection->createCommand($sql);
				foreach($id as $ids) {
					$command->bindvalue(':vid',$ids,PDO::PARAM_STR);
					$command->bindvalue(':vdatauser',GetUserPC(),PDO::PARAM_STR);
					$command->execute();
				}
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
		}
		else {
			GetMessage(true,'chooseone');
		}
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['titleid'] = GetCatalog('slocid');
		$this->dataprint['titleplantcode'] = GetCatalog('plantcode');
		$this->dataprint['titlesloccode'] = GetCatalog('sloccode');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['plantcode'] = GetSearchText(array('GET'),'plantcode');
    $this->dataprint['sloccode'] = GetSearchText(array('GET'),'sloccode');
    $this->dataprint['description'] = GetSearchText(array('GET'),'description');
  }
}

