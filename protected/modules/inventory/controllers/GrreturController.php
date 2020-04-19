<?php
class GrreturController extends Controller {
  public $menuname = 'grretur';
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
			'grreturid' => $id,
		));
  }
	public function search() {
    header('Content-Type: application/json');
    $grreturid   = isset($_POST['grreturid']) ? $_POST['grreturid'] : '';
    $grreturid   = isset($_POST['grreturid']) ? $_POST['grreturid'] : '';
    $grreturno   = isset($_POST['grreturno']) ? $_POST['grreturno'] : '';
    $plantid   = isset($_POST['plantid']) ? $_POST['plantid'] : '';
    $plantcode   = isset($_POST['plantcode']) ? $_POST['plantcode'] : '';
    $companyid   = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $companyname   = isset($_POST['companyname']) ? $_POST['companyname'] : '';
    $grreturdate = isset($_POST['grreturdate']) ? $_POST['grreturdate'] : '';
    $poheaderid  = isset($_POST['poheaderid']) ? $_POST['poheaderid'] : '';
    $pono  = isset($_POST['pono']) ? $_POST['pono'] : '';
    $headernote  = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $supplier  = isset($_POST['supplier']) ? $_POST['supplier'] : '';
    $plantid  = isset($_GET['plantid']) ? $_GET['plantid'] : '';
    $poheaderid  = isset($_GET['poheaderid']) ? $_GET['poheaderid'] : '';
    $grreturid   = isset($_GET['q']) ? $_GET['q'] : $grreturid;
    $grreturno   = isset($_GET['q']) ? $_GET['q'] : $grreturno;
    $plantcode   = isset($_GET['q']) ? $_GET['q'] : $plantcode;
    $companyid   = isset($_GET['q']) ? $_GET['q'] : $companyid;
    $companyname   = isset($_GET['q']) ? $_GET['q'] : $companyname;
    $grreturdate = isset($_GET['q']) ? $_GET['q'] : $grreturdate;
    $pono  = isset($_GET['q']) ? $_GET['q'] : $pono;
    $headernote  = isset($_GET['q']) ? $_GET['q'] : $headernote;
    $supplier  = isset($_GET['q']) ? $_GET['q'] : $supplier;
    $page        = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows        = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort        = isset($_POST['sort']) ? strval($_POST['sort']) : 'grreturid';
    $order       = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset      = ($page - 1) * $rows;
    $page        = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows        = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort        = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order       = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset      = ($page - 1) * $rows;
    $result      = array();
    $row         = array();
		
    if (isset($_GET['notagrr'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('grretur t')->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')->leftjoin('plant c', 'c.plantid = a.plantid')->leftjoin('company d', 'd.companyid = c.companyid')->where("
          (coalesce(t.grreturid,'') like :grreturid) and 
      (coalesce(companyname,'') like :companyname) and
      (coalesce(grreturdate,'') like :grreturdate) and
      (coalesce(pono,'') like :pono) and
      (coalesce(plantcode,'') like :plantcode) and
          (coalesce(grreturno,'') like :grreturno) and
          (coalesce(t.fullname,'') like :fullname) and
          (coalesce(t.headernote,'') like :headernote) and t.recordstatus in (".getUserRecordStatus('listgrretur').") and d.companyid in (".getUserObjectValues('company').")
        and a.poheaderid = ".$poheaderid."
        and t.plantid = ".$plantid."
      ", array(
      ':grreturid' => '%' . $grreturid . '%',
        ':companyname' => '%' . $companyname . '%',
        ':grreturdate' => '%' . $grreturdate . '%',
        ':pono' => '%' . $pono . '%',
        ':plantcode' => '%' . $plantcode . '%',
        ':grreturno' => '%' . $grreturno . '%',
      ':fullname' => '%' . $supplier . '%',
        ':headernote' => '%' . $headernote . '%',
      ))->queryScalar();
    } else if (!isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('grretur t')->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')->leftjoin('plant c', 'c.plantid = a.plantid')->leftjoin('company d', 'd.companyid = c.companyid')->where("
          (coalesce(t.grreturid,'') like :grreturid) and 
      (coalesce(companyname,'') like :companyname) and
      (coalesce(grreturdate,'') like :grreturdate) and
      (coalesce(pono,'') like :pono) and
      (coalesce(plantcode,'') like :plantcode) and
          (coalesce(grreturno,'') like :grreturno) and
          (coalesce(t.fullname,'') like :fullname) and
          (coalesce(t.headernote,'') like :headernote) and t.recordstatus in (".getUserRecordStatus('listgrretur').") and d.companyid in (".getUserObjectValues('company').")", array(
      ':grreturid' => '%' . $grreturid . '%',
        ':companyname' => '%' . $companyname . '%',
        ':grreturdate' => '%' . $grreturdate . '%',
        ':pono' => '%' . $pono . '%',
        ':plantcode' => '%' . $plantcode . '%',
        ':grreturno' => '%' . $grreturno . '%',
      ':fullname' => '%' . $supplier . '%',
        ':headernote' => '%' . $headernote . '%',
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('grretur t')->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')->leftjoin('plant c', 'c.plantid = a.plantid')->leftjoin('company d', 'd.companyid = c.companyid')->where("
						(grreturid like :grreturid) or
						(companyname like :companyname) or
						(grreturdate like :grreturdate) or
						(pono like :pono) or
						(plantcode like :plantcode) or
						(grreturno like :grreturno) or
						(fullname like :fullname) or
						(t.headernote like :headernote) ", array(
        ':grreturid' => '%' . $grreturid . '%',
        ':companyname' => '%' . $companyname . '%',
        ':grreturdate' => '%' . $grreturdate . '%',
        ':pono' => '%' . $pono . '%',
        ':plantcode' => '%' . $plantcode . '%',
        ':grreturno' => '%' . $grreturno . '%',
        ':fullname' => '%' . $supplier . '%',
        ':headernote' => '%' . $headernote . '%',
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['notagrr'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.pono,c.plantcode,d.companyid,d.companycode')->from('grretur t')->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')->leftjoin('plant c', 'c.plantid = a.plantid')->leftjoin('company d', 'd.companyid = c.companyid')->where("
          (coalesce(t.grreturid,'') like :grreturid) and 
      (coalesce(companyname,'') like :companyname) and
      (coalesce(grreturdate,'') like :grreturdate) and
      (coalesce(pono,'') like :pono) and
      (coalesce(plantcode,'') like :plantcode) and
          (coalesce(grreturno,'') like :grreturno) and
          (coalesce(t.fullname,'') like :fullname) and
          (coalesce(t.headernote,'') like :headernote) and t.recordstatus in (".getUserRecordStatus('listgrretur').") and d.companyid in (".getUserObjectValues('company').")
        and a.poheaderid = ".$poheaderid."
        and t.plantid = ".$plantid."
      ", array(
      ':grreturid' => '%' . $grreturid . '%',
        ':companyname' => '%' . $companyname . '%',
        ':grreturdate' => '%' . $grreturdate . '%',
        ':pono' => '%' . $pono . '%',
        ':plantcode' => '%' . $plantcode . '%',
        ':grreturno' => '%' . $grreturno . '%',
      ':fullname' => '%' . $supplier . '%',
        ':headernote' => '%' . $headernote . '%',
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else if (!isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.pono,c.plantcode,d.companyid,d.companycode')->from('grretur t')->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')->leftjoin('plant c', 'c.plantid = a.plantid')->leftjoin('company d', 'd.companyid = c.companyid')->where("
          (coalesce(t.grreturid,'') like :grreturid) and 
      (coalesce(companyname,'') like :companyname) and
      (coalesce(grreturdate,'') like :grreturdate) and
      (coalesce(pono,'') like :pono) and
      (coalesce(plantcode,'') like :plantcode) and
          (coalesce(grreturno,'') like :grreturno) and
          (coalesce(t.fullname,'') like :fullname) and
          (coalesce(t.headernote,'') like :headernote) and t.recordstatus in (".getUserRecordStatus('listgrretur').") and d.companyid in (".getUserObjectValues('company').")", array(
      ':grreturid' => '%' . $grreturid . '%',
        ':companyname' => '%' . $companyname . '%',
        ':grreturdate' => '%' . $grreturdate . '%',
        ':pono' => '%' . $pono . '%',
        ':plantcode' => '%' . $plantcode . '%',
        ':grreturno' => '%' . $grreturno . '%',
      ':fullname' => '%' . $supplier . '%',
        ':headernote' => '%' . $headernote . '%',
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.pono,c.plantcode,d.companyid,d.companycode')->from('grretur t')->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')->leftjoin('plant c', 'c.plantid = a.plantid')->leftjoin('company d', 'd.companyid = c.companyid')->where("
						(grreturid like :grreturid) or
						(companyname like :companyname) or
						(grreturdate like :grreturdate) or
						(pono like :pono) or
						(plantcode like :plantcode) or
						(grreturno like :grreturno) or
						(fullname like :fullname) or
						(t.headernote like :headernote) ", array(
        ':grreturid' => '%' . $grreturid . '%',
        ':companyname' => '%' . $companyname . '%',
        ':grreturdate' => '%' . $grreturdate . '%',
        ':pono' => '%' . $pono . '%',
        ':plantcode' => '%' . $plantcode . '%',
        ':grreturno' => '%' . $grreturno . '%',
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
        'grreturid' => $data['grreturid'],
        'grreturno' => $data['grreturno'],
        'grreturdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['grreturdate'])),
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'fullname' => $data['fullname'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusgrretur' => $data['statusname']
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'grreturdetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('grreturdetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uom2id')->leftjoin('unitofmeasure d', 'd.unitofmeasureid = t.uom3id')->leftjoin('unitofmeasure e', 'e.unitofmeasureid = t.uom4id')->leftjoin('sloc f', 'f.slocid = t.slocid')->leftjoin('storagebin g', 'g.storagebinid = t.storagebinid')->leftjoin('grheader h', 'h.grheaderid = t.grheaderid')->where('grreturid = :grreturid', array(
      ':grreturid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productcode,a.productname,b.uomcode,c.uomcode as uom2code,
			GetStdQty2(a.productid) as stdqty2,
						GetStdQty3(a.productid) as stdqty3,
						GetStdQty4(a.productid) as stdqty4,
			d.uomcode as uom3code,e.uomcode as uom4code,f.sloccode,f.description as slocdesc,g.description,h.grno')->from('grreturdetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.uom2id')->leftjoin('unitofmeasure d', 'd.unitofmeasureid = t.uom3id')->leftjoin('unitofmeasure e', 'e.unitofmeasureid = t.uom4id')->leftjoin('sloc f', 'f.slocid = t.slocid')->leftjoin('storagebin g', 'g.storagebinid = t.storagebinid')->leftjoin('grheader h', 'h.grheaderid = t.grheaderid')->where('grreturid = :grreturid', array(
      ':grreturid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'grreturdetailid' => $data['grreturdetailid'],
        'grreturid' => $data['grreturid'],
        'grheaderid' => $data['grheaderid'],
        'grno' => $data['grno'],
        'grdetailid' => $data['grdetailid'],
        'productcode' => $data['productcode'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
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
        'sjsupplier' => $data['sjsupplier'],
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
		$sql     = 'call Modifgrretur(:vid,:vplantid,:vgrreturdate,:vpoheaderid,:vheadernote,:vdatauser)';
		$command = $connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vplantid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vgrreturdate', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vpoheaderid', $arraydata[3], PDO::PARAM_STR);
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
			$this->ModifyData($connection,array((isset($_POST['grretur-grreturid'])?$_POST['grretur-grreturid']:''),
				$_POST['grretur-plantid'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['grretur-grreturdate'])),
				$_POST['grretur-poheaderid'],
				$_POST['grretur-headernote']
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
			$sql     = 'call InsertGrreturdetail(:vgrreturid,:vgrheaderid,:vgrdetailid,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,:vqty4,:vslocid,:vstoragebinid,
				:vsjsupplier,:vitemnote,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call UpdateGrreturdetail(:vid,:vgrreturid,:vgrheaderid,:vgrdetailid,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,:vqty4,:vslocid,:vstoragebinid,
				:vsjsupplier,:vitemnote,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vgrreturid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vgrheaderid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vgrdetailid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vuom2id', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vuom3id', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vuom4id', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vqty', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vqty2', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vqty3', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vqty4', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vslocid', $arraydata[12], PDO::PARAM_STR);
		$command->bindvalue(':vstoragebinid', $arraydata[13], PDO::PARAM_STR);
		$command->bindvalue(':vsjsupplier', $arraydata[14], PDO::PARAM_STR);
		$command->bindvalue(':vitemnote', $arraydata[15], PDO::PARAM_STR);
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
			$this->ModifyDataDetail($connection,array((isset($_POST['grreturdetailid'])?$_POST['grreturdetailid']:''),
				$_POST['grreturid'],$_POST['grheaderid'],
				$_POST['grdetailid'],
				$_POST['uomid'],$_POST['uom2id'],
				$_POST['uom3id'],$_POST['uom4id'],
				$_POST['qty'],$_POST['qty2'],
				$_POST['qty3'],$_POST['qty4'],
				$_POST['slocid'],$_POST['storagebinid'],
				$_POST['sjsupplier'],$_POST['itemnote'],
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
        $sql     = 'call Purgegrretur(:vid,:vdatauser)';
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
        $sql     = 'call Purgegrreturdetail(:vid,:vdatauser)';
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
      GetMessage(false, getcatalog('chooseone'), 0);
    }
  }
  public function actionReject() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call RejectGRRetur(:vid,:vdatauser)';
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
        $sql     = 'call ApproveGRRetur(:vid,:vdatauser)';
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
  public function actionGeneratesupplier(){
		$sql = "select a.fullname
				from addressbook a 
				left join poheader b on b.addressbookid = a.addressbookid
				where a.poheaderid = ".$_POST['id']." 
				limit 1";
		$address = Yii::app()->db->createCommand($sql)->queryRow();
		echo CJSON::encode(array(
			'fullname' => $address['fullname'],
		));
  }
	public function actionGeneratedetail() {
    if (isset($_POST['id'])) {
      $sql = "select headernote from poheader where poheaderid = ".$_POST['id'];
			$header = Yii::app()->db->createCommand($sql)->queryScalar();
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateGRRPO(:vid, :vhid)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->execute();
        $transaction->commit();
        if (Yii::app()->request->isAjaxRequest) {
          echo CJSON::encode(array(
            'status' => 'success',
            'headernote' => $header,
            'div' => "Data generated"
          ));
        }
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
					a.qty4 * ((a.qty-a.qtyretur)/a.qty) as qty4,a.slocid,g.sloccode,a.storagebinid,h.description,i.sjsupplier
				from grdetail a 
				join product b on b.productid = a.productid 
				left join unitofmeasure c on c.unitofmeasureid = a.uomid 
				left join unitofmeasure d on d.unitofmeasureid = a.uom2id
				left join unitofmeasure e on e.unitofmeasureid = a.uom3id
				left join unitofmeasure f on f.unitofmeasureid = a.uom4id
				left join sloc g on g.slocid = a.slocid 
				left join storagebin h on h.storagebinid = a.storagebinid
				left join grheader i on i.grheaderid = a.grheaderid
				where a.grheaderid = ".$_POST['grheaderid']." and a.grdetailid = ".$_POST['grdetailid']."
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
			'sjsupplier' => $data['sjsupplier'],
			'description' => $data['description'],
		));
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select a.grreturid,d.companyid,a.grreturno,a.grreturdate,a.poheaderid,b.pono,c.fullname,a.recordstatus,a.headernote
						from grretur a
						left join poheader b on b.poheaderid = a.poheaderid
						left join addressbook c on c.addressbookid = b.addressbookid
            left join plant d on d.plantid = a.plantid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.grreturid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = getCatalog('grretur');
    $this->pdf->AddPage('P', array(
      220,
      70
    ));
    $this->pdf->SetFont('Arial', '', 10);
    $this->pdf->AliasNBPages();
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(7);
      $this->pdf->text(10, $this->pdf->gety(), 'No');
      $this->pdf->text(20, $this->pdf->gety(), ': ' . $row['grreturno']);
      $this->pdf->text(50, $this->pdf->gety(), 'Tgl');
      $this->pdf->text(60, $this->pdf->gety(), ': ' . $row['grreturdate']);
      $this->pdf->text(90, $this->pdf->gety(), 'PO ');
      $this->pdf->text(100, $this->pdf->gety(), ': ' . $row['pono']);
      $this->pdf->text(130, $this->pdf->gety(), 'Supplier');
      $this->pdf->text(140, $this->pdf->gety(), ': ' . $row['fullname']);
      $sql1        = "select b.productname, b.productcode,a.qty,c.uomcode,d.sloccode,e.grno
								from grreturdetail a
								left join product b on b.productid = a.productid
								left join unitofmeasure c on c.unitofmeasureid = a.uomid
								left join sloc d on d.slocid = a.slocid
								left join grheader e on e.grheaderid = a.grheaderid
								where grreturid = " . $row['grreturid'];
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
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        25,
        95,
        20,
        20,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'No LPB',
        'Nama Barang',
        'Qty',
        'Unit',
        'Gudang'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->coldetailalign = array(
        'C',
        'C',
        'L',
        'R',
        'R',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
					 $row1['grno'],
         $row1['productname'],
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          $row1['sloccode']
        ));
      }
      $this->pdf->sety($this->pdf->gety() + 3);
      $this->pdf->row(array(
        'Note:',
        $row['headernote']
      ));
      $this->pdf->sety($this->pdf->gety() + 3);
      $this->pdf->text(15, $this->pdf->gety(), '  Dibuat oleh,');
      $this->pdf->text(65, $this->pdf->gety(), ' Disetujui oleh,');
      $this->pdf->text(125, $this->pdf->gety(), 'Dibawa oleh,');
      $this->pdf->text(178, $this->pdf->gety(), ' Diterima oleh,');
      $this->pdf->text(15, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(65, $this->pdf->gety() + 15, '.........................');
      $this->pdf->text(125, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(178, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(15, $this->pdf->gety() + 17, 'Admin Gudang');
      $this->pdf->text(65, $this->pdf->gety() + 17, ' Kepala Gudang');
      $this->pdf->text(125, $this->pdf->gety() + 17, '        Supir');
      $this->pdf->text(178, $this->pdf->gety() + 17, 'Supplier/Toko');
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    $this->menuname = 'grretur';
    parent::actionDownxls();
    $sql = "select a.grreturid,b.companyid,a.grreturno,a.grreturdate,a.poheaderid,b.pono,c.fullname,a.recordstatus,a.headernote
						from grretur a
						left join poheader b on b.poheaderid = a.poheaderid
						left join addressbook c on c.addressbookid = b.addressbookid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.grreturid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 3;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, ': ' . $row['grreturno']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Date')->setCellValueByColumnAndRow(1, $line, ': ' . $row['grreturdate']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'PO No')->setCellValueByColumnAndRow(1, $line, ': ' . $row['pono']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Vendor')->setCellValueByColumnAndRow(1, $line, ': ' . $row['fullname']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, 'Nama Barang')->setCellValueByColumnAndRow(2, $line, 'Qty')->setCellValueByColumnAndRow(3, $line, 'Unit')->setCellValueByColumnAndRow(4, $line, 'Gudang');
      $line++;
      $sql1        = "select b.productname, a.qty, c.uomcode,d.description,b.productcode,a.qtystdkg,a.qtystdmtr
								from grreturdetail a
								left join product b on b.productid = a.productid
								left join unitofmeasure c on c.unitofmeasureid = a.uomid
								left join sloc d on d.slocid = a.slocid
								where grreturid = " . $row['grreturid'];
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