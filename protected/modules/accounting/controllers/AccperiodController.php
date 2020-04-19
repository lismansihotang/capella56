<?php
class AccperiodController extends Controller {
  public $menuname = 'accperiod';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
	public function search() {
    header('Content-Type: application/json');
    $accperiodid     = GetSearchText(array('POST','Q'),'accperiodid');
    $period          = GetSearchText(array('POST','Q'),'period');
    $recordstatus    = GetSearchText(array('POST','Q'),'recordstatus');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','accperiodid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('accperiod t')->where('(accperiodid like :accperiodid) and (period like :period)', array(
      ':accperiodid' => '%' . $accperiodid . '%',
      ':period' => '%' . $period . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*')->from('accperiod t')->where('(accperiodid like :accperiodid) and (period like :period)', array(
      ':accperiodid' => '%' . $accperiodid . '%',
      ':period' => '%' . $period . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'accperiodid' => $data['accperiodid'],
        'period' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['period'])),
        'recordstatus' => $data['recordstatus']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSave() {
		parent::actionWrite();
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertaccperiod(:vperiod,:vrecordstatus,:vdatauser)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateaccperiod(:vid,:vperiod,:vrecordstatus,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['accperiodid'], PDO::PARAM_STR);
      }
      $command->bindvalue(':vperiod', date(Yii::app()->params['datetodb'], strtotime($_POST['period'])), PDO::PARAM_STR);
      $command->bindvalue(':vrecordstatus', $_POST['recordstatus'], PDO::PARAM_STR);
      $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      $this->DeleteLock($this->menuname, $_POST['accperiodid']);
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
        $sql     = 'call Purgeaccperiod(:vid,:vdatauser)';
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
		$this->dataprint['period'] = GetSearchText(array('GET'),'period');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'accperiodid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titleperiod'] = GetCatalog('period');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}