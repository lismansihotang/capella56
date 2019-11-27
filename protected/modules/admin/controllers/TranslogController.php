<?php
class TranslogController extends Controller {
	public $menuname = 'translog';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$translogid = GetSearchText(array('POST','Q'),'translogid');
		$username = GetSearchText(array('POST','Q'),'username');
		$createddate = GetSearchText(array('POST','Q'),'createddate');
		$useraction = GetSearchText(array('POST','Q'),'useraction');
		$newdata = GetSearchText(array('POST','Q'),'newdata');
		$olddata = GetSearchText(array('POST','Q'),'olddata');
		$menuname = GetSearchText(array('POST','Q'),'menuname');
		$tableid = GetSearchText(array('POST','Q'),'tableid');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','translogid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;		
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('translog t')
			->where("(coalesce(translogid,'') like :translogid) 
				and (coalesce(username,'') like :username) 
				and (coalesce(createddate,'') like :createddate) 
				and (coalesce(useraction,'') like :useraction) 
				and (coalesce(newdata,'') like :newdata) 
				and (coalesce(olddata,'') like :olddata) 
				and (coalesce(menuname,'') like :menuname) 
				and (coalesce(tableid,'') like :tableid)",
				array(':translogid'=>'%'.$translogid.'%',':username'=>'%'.$username.'%',':createddate'=>'%'.$createddate.'%',':useraction'=>'%'.$useraction.'%',':newdata'=>'%'.$newdata.'%',':olddata'=>'%'.$olddata.'%',':menuname'=>'%'.$menuname.'%',':tableid'=>'%'.$tableid.'%'))			
			->queryScalar();	
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select()	
			->from('translog t')
			->where("(coalesce(translogid,'') like :translogid) 
				and (coalesce(username,'') like :username) 
				and (coalesce(createddate,'') like :createddate) 
				and (coalesce(useraction,'') like :useraction) 
				and (coalesce(newdata,'') like :newdata) 
				and (coalesce(olddata,'') like :olddata) 
				and (coalesce(menuname,'') like :menuname) 
				and (coalesce(tableid,'') like :tableid)",
				array(':translogid'=>'%'.$translogid.'%',':username'=>'%'.$username.'%',':createddate'=>'%'.$createddate.'%',':useraction'=>'%'.$useraction.'%',':newdata'=>'%'.$newdata.'%',':olddata'=>'%'.$olddata.'%',':menuname'=>'%'.$menuname.'%',':tableid'=>'%'.$tableid.'%'))			
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'translogid'=>$data['translogid'],
				'username'=>$data['username'],
				'createddate'=>$data['createddate'],
				'useraction'=>$data['useraction'],
				'newdata'=>$data['newdata'],
				'olddata'=>$data['olddata'],
				'menuname'=>$data['menuname'],
				'tableid'=>$data['tableid'],
				'ippublic'=>$data['ippublic'],
				'iplocal'=>$data['iplocal'],
				'lat'=>$data['lat'],
				'lng'=>$data['lng'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionPurge() {
		parent::actionPurge();
		if (isset($_POST['id'])) 	{
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgetranslog(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
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
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['titleid'] = GetCatalog('translogid');
		$this->dataprint['titleusername'] = GetCatalog('username');
		$this->dataprint['titlecreateddate'] = GetCatalog('createddate');
		$this->dataprint['titleuseraction'] = GetCatalog('useraction');
		$this->dataprint['titlenewdata'] = GetCatalog('newdata');
		$this->dataprint['titleolddata'] = GetCatalog('olddata');
		$this->dataprint['titlemenuname'] = GetCatalog('menuname');
		$this->dataprint['titletableid'] = GetCatalog('tableid');
		$this->dataprint['titleippublic'] = GetCatalog('ippublic');
		$this->dataprint['titleiplocal'] = GetCatalog('iplocal');
		$this->dataprint['titlelat'] = GetCatalog('lat');
		$this->dataprint['titlelng'] = GetCatalog('lng');
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['namauser'] = GetSearchText(array('GET'),'username');
    $this->dataprint['createddate'] = GetSearchText(array('GET'),'createddate');
    $this->dataprint['useraction'] = GetSearchText(array('GET'),'useraction');
    $this->dataprint['newdata'] = GetSearchText(array('GET'),'newdata');
    $this->dataprint['olddata'] = GetSearchText(array('GET'),'olddata');
    $this->dataprint['menuname'] = GetSearchText(array('GET'),'menuname');
    $this->dataprint['tableid'] = GetSearchText(array('GET'),'tableid');
  }
}
