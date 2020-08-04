<?php
class RequestedbyController extends Controller {
  public $menuname = 'requestedby';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $requestedbyid = GetSearchText(array('POST','Q'),'requestedbyid');
    $requestedbycode = GetSearchText(array('POST','Q'),'requestedbycode');
    $description     = GetSearchText(array('POST','Q'),'description');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','requestedbyid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    if (!isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('requestedby t')
			->where("(coalesce(requestedbyid,'') like :requestedbyid) and (coalesce(requestedbycode,'') like :requestedbycode) and (coalesce(description,'') like :description)", array(
        ':requestedbyid' =>  $requestedbyid ,
        ':requestedbycode' =>  $requestedbycode ,
        ':description' =>  $description 
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('requestedby t')
			->where("(coalesce(requestedbyid,'') like :requestedbyid) or (coalesce(requestedbycode,'') like :requestedbycode) or (coalesce(description,'') like :description)", array(
        ':requestedbyid' =>  $requestedbyid ,
        ':requestedbycode' =>  $requestedbycode ,
        ':description' =>  $description 
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (!isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*')->from('requestedby t')
			->where("(coalesce(requestedbyid,'') like :requestedbyid) and (coalesce(requestedbycode,'') like :requestedbycode) and (coalesce(description,'') like :description)", array(
        ':requestedbyid' =>  $requestedbyid ,
        ':requestedbycode' =>  $requestedbycode ,
        ':description' =>  $description 
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*')->from('requestedby t')
			->where("(coalesce(requestedbyid,'') like :requestedbyid) or (coalesce(requestedbycode,'') like :requestedbycode) or (coalesce(description,'') like :description)", array(
        ':requestedbyid' =>  $requestedbyid ,
        ':requestedbycode' =>  $requestedbycode ,
        ':description' =>  $description 
      ))->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'requestedbyid' => $data['requestedbyid'],
        'requestedbycode' => $data['requestedbycode'],
        'description' => $data['description'],
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
        $sql     = 'call Insertrequestedby(:vrequestedbycode,:vdescription,:vrecordstatus,:vdatauser)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updaterequestedby(:vid,:vrequestedbycode,:vdescription,:vrecordstatus,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['requestedbyid'], PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['requestedbyid']);
      }
      $command->bindvalue(':vrequestedbycode', $_POST['requestedbycode'], PDO::PARAM_STR);
      $command->bindvalue(':vdescription', $_POST['description'], PDO::PARAM_STR);
      $command->bindvalue(':vrecordstatus', $_POST['recordstatus'], PDO::PARAM_STR);
      $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(false,'insertsuccess');
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
        $sql     = 'call Purgerequestedby(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false,'insertsuccess');
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
		$this->dataprint['requestedbycode'] = GetSearchText(array('GET'),'requestedbycode');
		$this->dataprint['description'] = GetSearchText(array('GET'),'description');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'requestedbyid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlerequestedbycode'] = GetCatalog('requestedbycode');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}