<?php
class GireturController extends Controller {
  public $menuname = 'giretur';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail() {
    if (isset($_GET['grid']))
      echo $this->actionsearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
		$id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'gireturid' => $id,
		));
  }
	public function search() {
    header('Content-Type: application/json');
    $gireturid   = GetSearchText(array('POST','Q'),'gireturid','','int');
    $gireturno   = GetSearchText(array('POST','Q'),'gireturno');
    $plantid   = GetSearchText(array('POST','GET'),'plantid','','int');
    $plantcode   = GetSearchText(array('POST','Q'),'plantcode');
    $companyid   = GetSearchText(array('POST','GET'),'companyid','','int');
    $customer   = GetSearchText(array('POST','Q'),'customer');
    $gireturdate   = GetSearchText(array('POST','Q'),'gireturdate');
    $soheaderid   = GetSearchText(array('POST','GET'),'soheaderid','','int');
    $sono   = GetSearchText(array('POST','Q'),'sono');
    $headernote   = GetSearchText(array('POST','Q'),'headernote');
    $supplier   = GetSearchText(array('POST','Q'),'supplier');
    $recordstatus   = GetSearchText(array('POST','Q'),'recordstatus');
    $plantid   = GetSearchText(array('POST','GET'),'plantid','','int');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','gireturid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset      = ($page - 1) * $rows;
    $result      = array();
    $row         = array();
		
    if (isset($_GET['notagir'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('giretur t')
			->leftjoin('giheader e', 'e.giheaderid = t.giheaderid')
			->leftjoin('soheader a', 'a.soheaderid = e.soheaderid')
			->leftjoin('addressbook b', 'b.addressbookid = a.addressbookid')
			->leftjoin('plant c', 'c.plantid = t.plantid')
			->leftjoin('company d', 'd.companyid = c.companyid')
			->where("
				((coalesce(t.gireturid,'') like :gireturid) 
				or (coalesce(b.fullname,'') like :customer) 
				or (coalesce(t.gireturdate,'') like :gireturdate) 
				or (coalesce(a.sono,'') like :sono) 
				or (coalesce(c.plantcode,'') like :plantcode) 
				or (coalesce(t.gireturno,'') like :gireturno) 
				or (coalesce(b.fullname,'') like :fullname) 
				or (coalesce(t.headernote,'') like :headernote)) 
				and t.recordstatus in (".getUserRecordStatus('listgiretur').") 
				and d.companyid in (".getUserObjectValues('company').")
				
        and e.soheaderid = ".$soheaderid."
        and t.plantid = ".$plantid."
				", array(
				':gireturid' => '%' . $gireturid . '%',
				':customer' => '%' . $customer . '%',
				':gireturdate' => '%' . $gireturdate . '%',
				':sono' => '%' . $sono . '%',
				':plantcode' => '%' . $plantcode . '%',
				':gireturno' => '%' . $gireturno . '%',
				':fullname' => '%' . $supplier . '%',
        ':headernote' => '%' . $headernote . '%',
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('giretur t')
			->leftjoin('giheader e', 'e.giheaderid = t.giheaderid')
			->leftjoin('soheader a', 'a.soheaderid = e.soheaderid')
			->leftjoin('addressbook b', 'b.addressbookid = a.addressbookid')
			->leftjoin('plant c', 'c.plantid = t.plantid')
			->leftjoin('company d', 'd.companyid = c.companyid')
			->where("
				(coalesce(t.gireturid,'') like :gireturid) 
				and (coalesce(b.fullname,'') like :customer) 
				and (coalesce(t.gireturdate,'') like :gireturdate) 
				and (coalesce(a.sono,'') like :sono) 
				and (coalesce(c.plantcode,'') like :plantcode) 
				and (coalesce(t.gireturno,'') like :gireturno) 
				and (coalesce(b.fullname,'') like :fullname) 
				and (coalesce(t.headernote,'') like :headernote)".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				"	and t.recordstatus in (".getUserRecordStatus('listgiretur').") 
				and t.plantid in (".getUserObjectValues('plant').")
				", array(
					':gireturid' => '%' . $gireturid . '%',
					':customer' => '%' . $customer . '%',
					':gireturdate' => '%' . $gireturdate . '%',
					':sono' => '%' . $sono . '%',
					':plantcode' => '%' . $plantcode . '%',
					':gireturno' => '%' . $gireturno . '%',
					':fullname' => '%' . $supplier . '%',
					':headernote' => '%' . $headernote . '%',
      ))->queryScalar();
		}
    $result['total'] = $cmd;
    if (isset($_GET['notagir'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.sono,c.plantcode,d.companyid,d.companycode,e.gino,b.fullname')
			->from('giretur t')
			->leftjoin('giheader e', 'e.giheaderid = t.giheaderid')
			->leftjoin('soheader a', 'a.soheaderid = e.soheaderid')
			->leftjoin('addressbook b', 'b.addressbookid = a.addressbookid')
			->leftjoin('plant c', 'c.plantid = t.plantid')
			->leftjoin('company d', 'd.companyid = c.companyid')
			->where("((coalesce(t.gireturid,'') like :gireturid) 
				or (coalesce(b.fullname,'') like :customer) 
				or (coalesce(t.gireturdate,'') like :gireturdate) 
				or (coalesce(a.sono,'') like :sono) 
				or (coalesce(c.plantcode,'') like :plantcode) 
				or (coalesce(t.gireturno,'') like :gireturno) 
				or (coalesce(b.fullname,'') like :fullname) 
				or (coalesce(t.headernote,'') like :headernote)) 
				and t.recordstatus in (".getUserRecordStatus('listgiretur').") 
				and d.companyid in (".getUserObjectValues('company').")
				".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				" and e.soheaderid = ".$soheaderid."
        and t.plantid = ".$plantid."
				", array(
				':gireturid' => '%' . $gireturid . '%',
				':customer' => '%' . $customer . '%',
				':gireturdate' => '%' . $gireturdate . '%',
				':sono' => '%' . $sono . '%',
				':plantcode' => '%' . $plantcode . '%',
				':gireturno' => '%' . $gireturno . '%',
				':fullname' => '%' . $supplier . '%',
        ':headernote' => '%' . $headernote . '%',
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.sono,c.plantcode,d.companyid,d.companycode,e.gino,b.fullname')
			->from('giretur t')
			->leftjoin('giheader e', 'e.giheaderid = t.giheaderid')
			->leftjoin('soheader a', 'a.soheaderid = e.soheaderid')
			->leftjoin('addressbook b', 'b.addressbookid = a.addressbookid')
			->leftjoin('plant c', 'c.plantid = t.plantid')
			->leftjoin('company d', 'd.companyid = c.companyid')
			->where("
				(coalesce(t.gireturid,'') like :gireturid) 
				and (coalesce(b.fullname,'') like :customer) 
				and (coalesce(t.gireturdate,'') like :gireturdate) 
				and (coalesce(a.sono,'') like :sono) 
				and (coalesce(c.plantcode,'') like :plantcode) 
				and (coalesce(t.gireturno,'') like :gireturno) 
				and (coalesce(b.fullname,'') like :fullname) 
				and (coalesce(t.headernote,'') like :headernote) 
				and t.recordstatus in (".getUserRecordStatus('listgiretur').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				"	and t.plantid in (".getUserObjectValues('plant').")", 
				array(
				':gireturid' => '%' . $gireturid . '%',
        ':customer' => '%' . $customer . '%',
        ':gireturdate' => '%' . $gireturdate . '%',
        ':sono' => '%' . $sono . '%',
        ':plantcode' => '%' . $plantcode . '%',
        ':gireturno' => '%' . $gireturno . '%',
				':fullname' => '%' . $supplier . '%',
        ':headernote' => '%' . $headernote . '%',
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'gireturid' => $data['gireturid'],
        'gireturno' => $data['gireturno'],
        'giheaderid' => $data['giheaderid'],
        'gino' => $data['gino'],
        'fullname' => $data['fullname'],
        'gireturdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['gireturdate'])),
        'sono' => $data['sono'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusgiretur' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionsearchdetail() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'gireturdetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('gireturdetail t')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
			->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uom2id')
			->leftjoin('unitofmeasure d', 'd.unitofmeasureid = t.uom3id')
			->leftjoin('unitofmeasure e', 'e.unitofmeasureid = t.uom4id')
			->leftjoin('sloc f', 'f.slocid = t.slocid')
			->leftjoin('storagebin g', 'g.storagebinid = t.storagebinid')
			->leftjoin('materialtype h', 'h.materialtypeid = a.materialtypeid')
			->where('gireturid = :gireturid', array(
      ':gireturid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
			->select('t.*,a.productcode,a.productname,b.uomcode,h.materialtypecode,c.uomcode as uom2code,
			GetStdQty2(a.productid) as stdqty2,
						GetStdQty3(a.productid) as stdqty3,
						GetStdQty4(a.productid) as stdqty4,
				d.uomcode as uom3code,e.uomcode as uom4code,f.sloccode,f.description as slocdesc,g.description')
			->from('gireturdetail t')->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
			->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uom2id')
			->leftjoin('unitofmeasure d', 'd.unitofmeasureid = t.uom3id')
			->leftjoin('unitofmeasure e', 'e.unitofmeasureid = t.uom4id')
			->leftjoin('sloc f', 'f.slocid = t.slocid')
			->leftjoin('storagebin g', 'g.storagebinid = t.storagebinid')
			->leftjoin('materialtype h', 'h.materialtypeid = a.materialtypeid')
			->where('gireturid = :gireturid', array(
      ':gireturid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'gireturdetailid' => $data['gireturdetailid'],
        'gireturid' => $data['gireturid'],
        'gidetailid' => $data['gidetailid'],
        'productcode' => $data['productcode'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'materialtypecode' => $data['materialtypecode'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
				'qty2' => Yii::app()->format->formatNumber($data['qty2']),
				'qty3' => Yii::app()->format->formatNumber($data['qty3']),
				'qty4' => Yii::app()->format->formatNumber($data['qty4']),
				'stdqty2' => Yii::app()->format->formatNumber($data['stdqty2']),
				'stdqty3' => Yii::app()->format->formatNumber($data['stdqty3']),
				'stdqty4' => Yii::app()->format->formatNumber($data['stdqty4']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'uom2id' => $data['uom2id'],
        'uom2code' => $data['uom2code'],
        'uom3id' => $data['uom3id'],
        'uom3code' => $data['uom3code'],
        'uom4id' => $data['uom4id'],
        'uom4code' => $data['uom4code'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'].' - '.$data['slocdesc'],
        'storagebinid' => $data['storagebinid'],
        'description' => $data['description'],
        'itemnote' => $data['itemnote']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
  private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		$sql     = 'call Modifgiretur(:vid,:vplantid,:vgireturdate,:vgiheaderid,:vheadernote,:vdatauser)';
		$command = $connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vplantid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vgireturdate', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vgiheaderid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
	public function actionSave() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['giretur-gireturid'])?$_POST['giretur-gireturid']:''),
				$_POST['giretur-plantid'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['giretur-gireturdate'])),
				$_POST['giretur-giheaderid'],
				$_POST['giretur-headernote']
			));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
	private function ModifyDataDetail($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertgireturdetail(:vqty,:vqty2,:vqty3,:vqty4,:vslocid,:vstoragebinid,:vitemnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updategireturdetail(:vid,:vqty,:vqty2,:vqty3,:vqty4,:vslocid,:vstoragebinid,:vitemnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vqty', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vqty2', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vqty3', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vqty4', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vslocid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vstoragebinid', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vitemnote', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavedetail() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataDetail($connection,array((isset($_POST['gireturdetailid'])?$_POST['gireturdetailid']:''),
				$_POST['qty'],$_POST['qty2'],
				$_POST['qty3'],$_POST['qty4'],
				$_POST['slocid'],$_POST['storagebinid'],
				$_POST['itemnote'],
			));
			$transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
  public function actionPurge() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgegiretur(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  public function actionPurgedetail() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgegireturdetail(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  public function actionReject() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Rejectgiretur(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Approvegiretur(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  public function actionGeneratecustomer(){
		$sql = "select a.fullname
				from addressbook a 
				left join soheader b on b.addressbookid = a.addressbookid
				where b.soheaderid = ".$_POST['id']." 
				limit 1";
		$address = Yii::app()->db->createCommand($sql)->queryRow();
		echo CJSON::encode(array(
			'fullname' => $address['fullname'],
		));
  }
	public function actionGeneratedetail() {
    if (isset($_POST['id'])) {
      $sql = "select headernote from soheader where soheaderid = ".$_POST['id'];
			$header = Yii::app()->db->createCommand($sql)->queryScalar();
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateGIRGI(:vid, :vhid, :vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_INT);
        $command->execute();
        $transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
      }
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    }
    Yii::app()->end();
  }
  public function actionGenerateproductcode(){
		$sql = "select a.uomid,c.uomcode as uom1code,a.uom2id,d.uomcode as uom2code,a.uom3id,e.uomcode as uom3code,
					f.uomcode as uom4code,a.uom4id,b.productname,a.productid,b.productcode,a.qty-a.qtyretur as qty,a.qty2 * ((a.qty-a.qtyretur)/a.qty) as qty2,a.qty3 * ((a.qty-a.qtyretur)/a.qty) as qty3,
					a.qty4 * ((a.qty-a.qtyretur)/a.qty) as qty4,a.slocid,g.sloccode,a.storagebinid,h.description
				from gidetail a 
				join product b on b.productid = a.productid 
				left join unitofmeasure c on c.unitofmeasureid = a.uomid 
				left join unitofmeasure d on d.unitofmeasureid = a.uom2id
				left join unitofmeasure e on e.unitofmeasureid = a.uom3id
				left join unitofmeasure f on f.unitofmeasureid = a.uom4id
				left join sloc g on g.slocid = a.slocid 
				left join storagebin h on h.storagebinid = a.storagebinid
				left join giheader i on i.giheaderid = a.giheaderid
				where a.giheaderid = ".$_POST['giheaderid']." and a.gidetailid = ".$_POST['gidetailid']."
		";
		$data = Yii::app()->db->createCommand($sql)->queryRow();
		echo CJSON::encode(array(
			'uomid' => $data['uomid'],
			'uom1code' => $data['uom1code'],
			'uom2id' => $data['uom2id'],
			'uom2code' => $data['uom2code'],
			'uom3id' => $data['uom3id'],
			'uom3code' => $data['uom3code'],
			'uom4id' => $data['uom4id'],
			'uom4code' => $data['uom4code'],
			'productname' => $data['productname'],
			'productcode' => $data['productcode'],
			'qty' => $data['qty'],
			'qty2' => $data['qty2'],
			'qty3' => $data['qty3'],
			'qty4' => $data['qty4'],
			'slocid' => $data['slocid'],
			'sloccode' => $data['sloccode'],
			'storagebinid' => $data['storagebinid'],
			'description' => $data['description'],
		));
  }
 public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select e.companyid, a.gireturno,a.gireturdate,b.gino ,a.gireturid,a.headernote,d.fullname,f.addressname as shipto,a.recordstatus
						from giretur a
						left join giheader b on b.giheaderid = a.giheaderid 
						left join soheader c on c.soheaderid = b.soheaderid 
						left join addressbook d on d.addressbookid=c.addressbookid
						left join plant e on e.plantid=b.plantid
						left join address f on f.addressid = b.addresstoid
						";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.gireturid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = getCatalog('giretur');
    $this->pdf->AddPage('P', array(
      250,
      80
    ));
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->text(10, $this->pdf->gety(), 'No ');
      $this->pdf->text(15, $this->pdf->gety(), ': ' . $row['gireturno']);
      $this->pdf->text(50, $this->pdf->gety(), 'Tgl ');
      $this->pdf->text(55, $this->pdf->gety(), ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['gireturdate'])));
      $this->pdf->text(75, $this->pdf->gety(), 'SJ');
      $this->pdf->text(80, $this->pdf->gety(), ': ' . $row['gino']);
      $this->pdf->text(120, $this->pdf->gety(), 'Customer ');
      $this->pdf->text(135, $this->pdf->gety(), ': ' . $row['fullname']);
      $sql1        = "select b.productname, sum(ifnull(a.qty,0)) as vqty,
											sum(ifnull(a.qty2,0)) as vqty2,
											sum(ifnull(a.qty3,0)) as vqty3,
											sum(ifnull(a.qty4,0)) as vqty4,
											c.uomcode,
											c.uomcode as uom2code,
											c.uomcode as uom3code,
											c.uomcode as uom4code,
											d.description,
											f.description as rak
											from gireturdetail a
											inner join product b on b.productid = a.productid
											inner join unitofmeasure c on c.unitofmeasureid = a.uomid
											left join unitofmeasure i on i.unitofmeasureid = a.uom2id
											left join unitofmeasure j on j.unitofmeasureid = a.uom3id
											left join unitofmeasure k on k.unitofmeasureid = a.uom4id
											left join sloc d on d.slocid = a.slocid
											left join storagebin f on f.storagebinid = a.storagebinid
											left join gidetail g on g.gidetailid = a.gidetailid
											left join sodetail h on h.sodetailid = g.sodetailid
											where gireturid = " . $row['gireturid'] . " group by b.productname order by h.sodetailid";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 3);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setFont('Arial', 'B', 7);
      $this->pdf->setwidths(array(
        10,
        80,
        15,
        15,
        15,
        15,
        10,
        10,
        10,
        10,
        40
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Qty2',
        'Qty3',
        'Qty4',
        'Unit',
        'Unit2',
        'Unit3',
        'Unit4',
        'Gudang'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'R',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['vqty']),
          Yii::app()->format->formatNumber($row1['vqty2']),
          Yii::app()->format->formatNumber($row1['vqty3']),
          Yii::app()->format->formatNumber($row1['vqty4']),
          $row1['uomcode'],
          $row1['uom2code'],
          $row1['uom3code'],
          $row1['uom4code'],
          $row1['description'] . ' - ' . $row1['rak']
        ));
      }
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        50,
        140
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Note',
        $row['headernote']
      ));
			$this->pdf->CheckPageBreak(20);
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->text(10, $this->pdf->gety(), '');
      $this->pdf->text(20, $this->pdf->gety(), ' Dibuat Oleh,');
      $this->pdf->text(70, $this->pdf->gety(), '  Dibawa Oleh,');
      $this->pdf->text(120, $this->pdf->gety(), 'Diserahkan,');
      $this->pdf->text(170, $this->pdf->gety(), 'Diterima Oleh,');
      $this->pdf->text(10, $this->pdf->gety() + 15, '');
      $this->pdf->text(20, $this->pdf->gety() + 15, '.........................');
      $this->pdf->text(70, $this->pdf->gety() + 15, '............................');
      $this->pdf->text(120, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(170, $this->pdf->gety() + 15, '.............................');
      $this->pdf->text(10, $this->pdf->gety() + 18, '');
      $this->pdf->text(20, $this->pdf->gety() + 18, '  Adm Gudang');
      $this->pdf->text(70, $this->pdf->gety() + 18, ' Ekspedisi/ Supir');
      $this->pdf->text(120, $this->pdf->gety() + 18, '    Customer');
      $this->pdf->text(170, $this->pdf->gety() + 18, ' Kepala Gudang');
			$this->pdf->AddPage('P', array(
				250,
				80
			));
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    $this->menuname = 'giretur';
    parent::actionDownxls();
    $sql = "select a.gireturid,b.companyid,a.gireturno,a.gireturdate,a.soheaderid,b.sono,c.fullname,a.recordstatus,a.headernote
						from giretur a
						left join soheader b on b.soheaderid = a.soheaderid
						left join addressbook c on c.addressbookid = b.addressbookid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.gireturid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 3;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, ': ' . $row['gireturno']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Date')->setCellValueByColumnAndRow(1, $line, ': ' . $row['gireturdate']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'PO No')->setCellValueByColumnAndRow(1, $line, ': ' . $row['sono']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Vendor')->setCellValueByColumnAndRow(1, $line, ': ' . $row['fullname']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, 'Nama Barang')->setCellValueByColumnAndRow(2, $line, 'Qty')->setCellValueByColumnAndRow(3, $line, 'Unit')->setCellValueByColumnAndRow(4, $line, 'Gudang');
      $line++;
      $sql1        = "select b.productname, a.qty, c.uomcode,d.description,b.productcode,a.qtystdkg,a.qtystdmtr
								from gireturdetail a
								left join product b on b.productid = a.productid
								left join unitofmeasure c on c.unitofmeasureid = a.uomid
								left join sloc d on d.slocid = a.slocid
								where gireturid = " . $row['gireturid'];
      $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
      $i           = 0;
      foreach ($dataReader1 as $row1) {
        $this->phpExcel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0, $line, $i += 1)
		->setCellValueByColumnAndRow(1, $line, $row1['productcode'] . '-' . $row1['productname'])
		->setCellValueByColumnAndRow(2, $line, $row1['qty'])
		->setCellValueByColumnAndRow(3, $line, $row1['vqtystdkg'])
		->setCellValueByColumnAndRow(4, $line, $row1['vqtystdmtr'])
		->setCellValueByColumnAndRow(5, $line, $row1['uomcode'])
		->setCellValueByColumnAndRow(6, $line, $row1['description']);
        $line++;
      }
      $this->phpExcel->setActiveSheetIndex(0)
	  ->setCellValueByColumnAndRow(0, $line, 'Note : ')
	  ->setCellValueByColumnAndRow(1, $line, $row['headernote']);
      $line += 2;
      $this->phpExcel->setActiveSheetIndex(0)
	  ->setCellValueByColumnAndRow(0, $line, 'Dibuat oleh, ')
	  ->setCellValueByColumnAndRow(1, $line, 'Disetujui oleh, ')
	  ->setCellValueByColumnAndRow(2, $line, 'Dibawa oleh, ')
	  ->setCellValueByColumnAndRow(3, $line, 'Diterima oleh, ');
      $line += 5;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, '........................')
	  ->setCellValueByColumnAndRow(1, $line, '........................')
	  ->setCellValueByColumnAndRow(2, $line, '........................')
	  ->setCellValueByColumnAndRow(3, $line, '........................');
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)
	  ->setCellValueByColumnAndRow(0, $line, 'Admin Gudang')
	  ->setCellValueByColumnAndRow(1, $line, 'Kepala Gudang')
	  ->setCellValueByColumnAndRow(2, $line, 'Supir')
	  ->setCellValueByColumnAndRow(3, $line, 'Customer/Toko');
      $line++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}