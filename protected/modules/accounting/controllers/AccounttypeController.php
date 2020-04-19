<?php
class AccounttypeController extends Controller {
  public $menuname = 'accounttype';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
	public function search() {
    header('Content-Type: application/json');
    $accounttypeid   = GetSearchText(array('POST','Q'),'accounttypeid');
    $accounttypename = GetSearchText(array('POST','Q'),'accounttypename');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','accounttypeid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('accounttype t')
				->where("((accounttypeid like :accounttypeid) or (accounttypename like :accounttypename)) and t.recordstatus = 1", array(
						':accounttypeid' => '%' . $accounttypeid . '%',
						':accounttypename' => '%' . $accounttypename . '%',
				))->queryScalar();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('accounttype t')
				->where("((accounttypeid like :accounttypeid) and (accounttypename like :accounttypename))", array(
						':accounttypeid' => '%' . $accounttypeid . '%',
						':accounttypename' => '%' . $accounttypename . '%',
				))->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')
				->from('accounttype t')
				->where("((accounttypeid like :accounttypeid) or (accounttypename like :accounttypename)) and t.recordstatus = 1", array(
						':accounttypeid' => '%' . $accounttypeid . '%',
						':accounttypename' => '%' . $accounttypename . '%',
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')
				->from('accounttype t')
				->where("((accounttypeid like :accounttypeid) and (accounttypename like :accounttypename))", array(
						':accounttypeid' => '%' . $accounttypeid . '%',
						':accounttypename' => '%' . $accounttypename . '%',
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		}
		foreach ($cmd as $data) {      
			$row[] = array(
        'accounttypeid' => $data['accounttypeid'],
        'accounttypename' => $data['accounttypename'],
        'recordstatus' => $data['recordstatus']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	protected function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertaccounttype(:vaccounttypename,:vrecordstatus,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateaccounttype(:vid,:vaccounttypename,:vrecordstatus,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vaccounttypename', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-accounttype"]["name"]);
		if (move_uploaded_file($_FILES["file-accounttype"]["tmp_name"], $target_file)) {
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
					$accounttypename = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData($connection,array($id,$accounttypename,$recordstatus));
				}
				$transaction->commit();     
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    }
	}
  public function actionSave() {
		parent::actionWrite();
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      $this->ModifyData($connection,array((isset($_POST['accounttypeid'])?$_POST['accounttypeid']:''),
				$_POST['accounttypename'],$_POST['recordstatus']));
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
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeaccounttype(:vid,:vdatauser)';
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
  protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['accounttypename'] = GetSearchText(array('GET'),'accounttypename');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'accounttypeid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titleaccounttypename'] = GetCatalog('accounttypename');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}