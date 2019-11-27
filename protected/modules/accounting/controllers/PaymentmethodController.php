<?php
class PaymentmethodController extends Controller {
  public $menuname = 'paymentmethod';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
	public function search() {
    header("Content-Type: application/json");
    $paycode     = isset($_POST['paycode']) ? $_POST['paycode'] : '';
    $paydays     = isset($_POST['paydays']) ? $_POST['paydays'] : '';
    $paymentname = isset($_POST['paymentname']) ? $_POST['paymentname'] : '';
    $paycode     = isset($_GET['q']) ? $_GET['q'] : $paycode;
    $paydays     = isset($_GET['q']) ? $_GET['q'] : $paydays;
    $paymentname = isset($_GET['q']) ? $_GET['q'] : $paymentname;
    $page        = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows        = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort        = isset($_POST['sort']) ? strval($_POST['sort']) : 't.paymentmethodid';
    $order       = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $page        = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows        = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort        = isset($_POST['sort']) ? strval($_POST['sort']) : $sort;
    $order       = isset($_POST['order']) ? strval($_POST['order']) : $order;
    $offset      = ($page - 1) * $rows;
    $result      = array();
    $row         = array();
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('paymentmethod t')
				->where("((paycode like :paycode) or (paydays like :paydays) or (paymentname like :paymentname)) and t.recordstatus = 1", array(
						':paycode' => '%' . $paycode . '%',
						':paydays' => '%' . $paydays . '%',
						':paymentname' => '%' . $paymentname . '%',
				))->queryScalar();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('paymentmethod t')
				->where("((paycode like :paycode) and (paydays like :paydays) and (paymentname like :paymentname))", array(
						':paycode' => '%' . $paycode . '%',
						':paydays' => '%' . $paydays . '%',
						':paymentname' => '%' . $paymentname . '%',
				))->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')
				->from('paymentmethod t')
				->where("((paycode like :paycode) or (paydays like :paydays) or (paymentname like :paymentname)) and t.recordstatus = 1", array(
						':paycode' => '%' . $paycode . '%',
						':paydays' => '%' . $paydays . '%',
						':paymentname' => '%' . $paymentname . '%',
				))->order($sort . ' ' . $order)->queryAll();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')
				->from('paymentmethod t')
				->where("((paycode like :paycode) and (paydays like :paydays) and (paymentname like :paymentname))", array(
						':paycode' => '%' . $paycode . '%',
						':paydays' => '%' . $paydays . '%',
						':paymentname' => '%' . $paymentname . '%',
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		}
		foreach ($cmd as $data) {  
      $row[] = array(
        'paymentmethodid' => $data['paymentmethodid'],
        'paycode' => $data['paycode'],
        'paydays' => $data['paydays'],
        'paymentname' => $data['paymentname'],
        'recordstatus' => $data['recordstatus']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertpaymentmethod(:vpaycode,:vpaydays,:vpaymentname,:vrecordstatus,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatepaymentmethod(:vid,:vpaycode,:vpaydays,:vpaymentname,:vrecordstatus,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vpaycode', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vpaydays', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vpaymentname', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-paymentmethod"]["name"]);
		if (move_uploaded_file($_FILES["file-paymentmethod"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection  = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$paycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$paydays = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$paymentname = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$this->ModifyData($connection,array($id,$paycode,$paydays,$paymentname,$recordstatus));
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
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['paymentmethodid'])?$_POST['paymentmethodid']:''),$_POST['paycode'],
				$_POST['paydays'],$_POST['paymentname'],$_POST['recordstatus']));
			$transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
  public function actionPurge() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgepaymentmethod(:vid,:vdatauser)';
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
      GetMessage(true, 'chooseone');
    }
  }
  protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['titleid'] = GetCatalog('paymentmethodid');
		$this->dataprint['titlepaycode'] = GetCatalog('paycode');
		$this->dataprint['titlepaydays'] = GetCatalog('paydays');
		$this->dataprint['titlepaymentname'] = GetCatalog('paymentname');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['paycode'] = GetSearchText(array('GET'),'paycode');
    $this->dataprint['paydays'] = GetSearchText(array('GET'),'paydays');
    $this->dataprint['paymentname'] = GetSearchText(array('GET'),'paymentname');
  }
}
