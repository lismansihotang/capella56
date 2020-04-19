<?php
class AccountController extends Controller {
  public $menuname = 'account';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
	public function actionIndexcombosloc() {
		if(isset($_GET['grid']))
				echo $this->searchcombosloc();
		else
				$this->renderPartial('index',array());
  }
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertaccount(:vaccountcode,:vaccountname,:vparentaccountid,:vcurrencyid,:vaccounttypeid,:vcompanyid,:visdebit,:vrecordstatus,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateaccount(:vid,:vaccountcode,:vaccountname,:vparentaccountid,:vcurrencyid,:vaccounttypeid,:vcompanyid,:visdebit,:vrecordstatus,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vaccountcode', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vaccountname', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vparentaccountid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vaccounttypeid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vcompanyid', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':visdebit', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-account"]["name"]);
		if (move_uploaded_file($_FILES["file-account"]["tmp_name"], $target_file)) {
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
					$companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
					$accountcode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$accountname = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$parentaccountcode = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$parentaccountid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$parentaccountcode."'")->queryScalar();
					$currencyname = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$currencyid = Yii::app()->db->createCommand("select currencyid from currency where currencyname = '".$currencyname."'")->queryScalar();
					$accounttypename = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$accounttypeid = Yii::app()->db->createCommand("select accounttypeid from accounttype where accounttypename = '".$accounttypename."'")->queryScalar();
					$isdebit = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$this->ModifyData($connection,array($id,$accountcode,$accountname,$parentaccountid,$currencyid,$accounttypeid,$companyid,$isdebit,$recordstatus));
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
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['accountid'])?$_POST['accountid']:''),$_POST['accountcode'],$_POST['accountname'],$_POST['parentaccountid'],
				$_POST['currencyid'],$_POST['accounttypeid'],$_POST['companyid'],$_POST['isdebit'],$_POST['recordstatus']));
			$transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
  }
  public function actionPurge() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeaccount(:vid,:vdatauser)';
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
  public function search() {
    header('Content-Type: application/json');
    $accountid       = GetSearchText(array('POST','Q'),'accountid');
		$companyid     		= GetSearchText(array('POST','GET'),'companyid',0,'int');
    $companyname       = GetSearchText(array('POST','Q'),'companyname');
    $accountcode     = GetSearchText(array('POST','Q'),'accountcode');
    $accountname     = GetSearchText(array('POST','Q'),'accountname');
    $accounttypename = GetSearchText(array('POST','Q'),'accounttypename');
    $parentaccountcode = GetSearchText(array('POST','Q'),'parentaccountcode');
    $parentaccountname = GetSearchText(array('POST','Q'),'parentaccountname');
    $currencyname      = GetSearchText(array('POST','Q'),'currencyname');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','t.accountid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
		$dependency = new CDbCacheDependency("select max(updatedate) from account");
    if (isset($_GET['combo'])) {
      $cmd = Yii::app()->db->cache(1000, $dependency)->createCommand()->select('count(1) as total')
			->from('account t')
			->leftjoin('company a', 'a.companyid=t.companyid')
			->leftjoin('currency b', 'b.currencyid=t.currencyid')
			->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')
			->leftjoin('account d', 'd.accountid = t.parentaccountid')
			->where("((coalesce(t.accountcode,'') like :accountcode) 
				or (coalesce(d.accountcode,'') like :parentaccountcode) 
				or (coalesce(d.accountname,'') like :parentaccountname) 
				or (coalesce(c.accounttypename,'') like :accounttypename) 
				or (coalesce(a.companyname,'') like :companyname) 
				or (coalesce(b.currencyname,'') like :currencyname) 
				or (coalesce(t.accountname,'') like :accountname))
				and t.recordstatus=1 
				and t.companyid in (".getUserObjectValues('company').")", 
				array(
        ':accountcode' =>  $accountcode ,
        ':parentaccountcode' =>  $parentaccountcode ,
        ':parentaccountname' =>  $parentaccountname ,
        ':accounttypename' =>  $accounttypename ,
        ':companyname' =>  $companyname ,
        ':currencyname' =>  $currencyname ,
        ':accountname' =>  $accountname 
      ))->queryScalar();
    } else if (isset($_GET['trx'])) {
      $cmd = Yii::app()->db->cache(1000, $dependency)->createCommand()->select('count(1) as total')
				->from('account t')
				->leftjoin('company a', 'a.companyid=t.companyid')
				->leftjoin('currency b', 'b.currencyid=t.currencyid')
				->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')
				->leftjoin('account d', 'd.accountid = t.parentaccountid')
				->where("((coalesce(t.accountcode,'') like :accountcode) 
					or (coalesce(d.accountcode,'') like :parentaccountcode) 
					or (coalesce(d.accountname,'') like :parentaccountname) 
					or (coalesce(c.accounttypename,'') like :accounttypename) 
					or (coalesce(a.companyname,'') like :companyname) 
					or (coalesce(b.currencyname,'') like :currencyname) 
					or (coalesce(t.accountname,'') like :accountname)) 
					and t.recordstatus = 1 
					and t.accounttypeid = 2 
					and t.companyid in (".getUserObjectValues('company').")", 
					array(
        ':accountcode' =>  $accountcode ,
        ':parentaccountcode' =>  $parentaccountcode ,
        ':parentaccountname' =>  $parentaccountname ,
        ':accounttypename' =>  $accounttypename ,
        ':companyname' =>  $companyname ,
        ':currencyname' =>  $currencyname ,
        ':accountname' =>  $accountname 
      ))->queryScalar();
    } else if (isset($_GET['trxcom'])) {
      $cmd = Yii::app()->db->cache(1000, $dependency)->createCommand()->select('count(1) as total')
			->from('account t')
			->leftjoin('company a', 'a.companyid=t.companyid')
			->leftjoin('currency b', 'b.currencyid=t.currencyid')
			->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')
			->leftjoin('account d', 'd.accountid = t.parentaccountid')
			->where("((coalesce(t.accountcode,'') like :accountcode) 
					or (coalesce(d.accountcode,'') like :parentaccountcode) 
					or (coalesce(d.accountname,'') like :parentaccountname) 
					or (coalesce(c.accounttypename,'') like :accounttypename) 
					or (coalesce(a.companyname,'') like :companyname) 
					or (coalesce(b.currencyname,'') like :currencyname) 
					or (coalesce(t.accountname,'') like :accountname)) 
					and t.companyid = ".$companyid."
					and t.recordstatus=1 and t.accounttypeid = 2", 
				array(
        ':accountcode' =>  $accountcode ,
        ':parentaccountcode' =>  $parentaccountcode ,
        ':parentaccountname' =>  $parentaccountname ,
        ':accounttypename' =>  $accounttypename ,
        ':companyname' =>  $companyname ,
        ':currencyname' =>  $currencyname ,
        ':accountname' =>  $accountname 
      ))->queryScalar();
    } else if (isset($_GET['trxplant'])) {
      $cmd = Yii::app()->db->cache(1000, $dependency)->createCommand()->select('count(1) as total')
			->from('account t')
			->leftjoin('company a', 'a.companyid=t.companyid')
			->leftjoin('currency b', 'b.currencyid=t.currencyid')
			->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')
			->leftjoin('account d', 'd.accountid = t.parentaccountid')
			->leftjoin('plant e', 'e.companyid = t.companyid')
			->where("((coalesce(t.accountcode,'') like :accountcode) 
					or (coalesce(d.accountcode,'') like :parentaccountcode) 
					or (coalesce(d.accountname,'') like :parentaccountname) 
					or (coalesce(c.accounttypename,'') like :accounttypename) 
					or (coalesce(a.companyname,'') like :companyname) 
					or (coalesce(b.currencyname,'') like :currencyname) 
					or (coalesce(t.accountname,'') like :accountname)) 
					and e.plantid = '".$_GET['plantid']."' 
					and t.recordstatus=1 
					and t.accounttypeid = 2 ", 
				array(
        ':accountcode' =>  $accountcode ,
        ':parentaccountcode' =>  $parentaccountcode ,
        ':parentaccountname' =>  $parentaccountname ,
        ':accounttypename' =>  $accounttypename ,
        ':companyname' =>  $companyname ,
        ':currencyname' =>  $currencyname ,
        ':accountname' =>  $accountname 
      ))->queryScalar();
    } else if (isset($_GET['trxplantcbu'])) {
      $cmd = Yii::app()->db->cache(1000, $dependency)->createCommand()->select('count(1) as total')
			->from('account t')
			->leftjoin('company a', 'a.companyid=t.companyid')
			->leftjoin('currency b', 'b.currencyid=t.currencyid')
			->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')
			->leftjoin('account d', 'd.accountid = t.parentaccountid')
			->leftjoin('plant e', 'e.companyid = t.companyid')
			->where("((coalesce(t.accountcode,'') like :accountcode) 
					or (coalesce(d.accountcode,'') like :parentaccountcode) 
					or (coalesce(d.accountname,'') like :parentaccountname) 
					or (coalesce(c.accounttypename,'') like :accounttypename) 
					or (coalesce(a.companyname,'') like :companyname) 
					or (coalesce(b.currencyname,'') like :currencyname) 
					or (coalesce(t.accountname,'') like :accountname)) 
					and e.plantid = '".$_GET['plantid']."' 
					and t.recordstatus=1 
					and t.accountid in (".GetUserObjectValues('accountcashbank').")
					and t.accounttypeid = 2 ", 
				array(
        ':accountcode' =>  $accountcode ,
        ':parentaccountcode' =>  $parentaccountcode ,
        ':parentaccountname' =>  $parentaccountname ,
        ':accounttypename' =>  $accounttypename ,
        ':companyname' =>  $companyname ,
        ':currencyname' =>  $currencyname ,
        ':accountname' =>  $accountname 
      ))->queryScalar();
    } else if (isset($_GET['trxsloc'])) {
      $cmd = Yii::app()->db->cache(1000, $dependency)->createCommand()->select('count(1) as total')
			->from('account t')
			->leftjoin('company a', 'a.companyid=t.companyid')
			->leftjoin('currency b', 'b.currencyid=t.currencyid')
			->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')
			->leftjoin('plant d', 'd.companyid = t.companyid')
			->leftjoin('sloc e', 'e.plantid = d.plantid')
			->leftjoin('account f', 'f.accountid = t.parentaccountid')
			->where("((coalesce(t.accountcode,'') like :accountcode) 
				or (coalesce(f.parentaccountcode,'') like :parentaccountcode) 
				or (coalesce(f.parentaccountname,'') like :parentaccountname) 
				or (coalesce(c.accounttypename,'') like :accounttypename) 
				or (coalesce(a.companyname,'') like :companyname) 
				or (coalesce(b.currencyname,'') like :currencyname) 
				or (coalesce(t.accountname,'') like :accountname)) 
				and e.slocid = '".$_GET['slocid']."' 
				and t.recordstatus=1 and t.accounttypeid = 2 
				and t.companyid in (".getUserObjectValues('company').")", 
				array(
        ':accountcode' =>  $accountcode ,
        ':parentaccountcode' =>  $parentaccountcode ,
        ':parentaccountname' =>  $parentaccountname ,
        ':accounttypename' =>  $accounttypename ,
        ':companyname' =>  $companyname ,
        ':currencyname' =>  $currencyname ,
        ':accountname' =>  $accountname 
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->cache(1000, $dependency)->createCommand()->select('count(1) as total')
				->from('account t')
				->leftjoin('company a', 'a.companyid=t.companyid')
				->leftjoin('currency b', 'b.currencyid=t.currencyid')
				->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')
				->leftjoin('account d', 'd.accountid = t.parentaccountid')
				->where("(coalesce(t.accountcode,'') like :accountcode) 
				and (coalesce(d.accountcode,'') like :parentaccountcode)
				and (coalesce(d.accountname,'') like :parentaccountname)
				and (coalesce(c.accounttypename,'') like :accounttypename) 
				and (coalesce(a.companyname,'') like :companyname) 
				and (coalesce(b.currencyname,'') like :currencyname) 
				and (coalesce(t.accountname,'') like :accountname)", 
				array(
        ':accountcode' =>  $accountcode ,
        ':parentaccountcode' =>  $parentaccountcode ,
        ':parentaccountname' =>  $parentaccountname ,
        ':accounttypename' =>  $accounttypename ,
        ':companyname' =>  $companyname ,
        ':currencyname' =>  $currencyname ,
        ':accountname' =>  $accountname 
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['combo'])) {
      $cmd = Yii::app()->db->cache(1000, $dependency)->createCommand()
				->select('t.*,a.companyname,b.currencyname,c.accounttypename,d.accountcode as parentaccountcode,
					d.accountname as parentaccountname')
				->from('account t')
				->leftjoin('company a', 'a.companyid=t.companyid')
				->leftjoin('currency b', 'b.currencyid=t.currencyid')
				->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')
				->leftjoin('account d', 'd.accountid = t.parentaccountid')
				->where("((coalesce(t.accountcode,'') like :accountcode) 
					or (coalesce(d.accountcode,'') like :parentaccountcode) 
					or (coalesce(d.accountname,'') like :parentaccountname) 
					or (coalesce(c.accounttypename,'') like :accounttypename) 
					or (coalesce(a.companyname,'') like :companyname) 
					or (coalesce(b.currencyname,'') like :currencyname) 
					or (coalesce(t.accountname,'') like :accountname)) 
					and t.recordstatus=1 
					and t.companyid in (".getUserObjectValues('company').")", 
				array(
					':accountcode' =>  $accountcode ,
					':parentaccountcode' =>  $parentaccountcode ,
					':parentaccountname' =>  $parentaccountname ,
					':accounttypename' =>  $accounttypename ,
					':companyname' =>  $companyname ,
					':currencyname' =>  $currencyname ,
					':accountname' =>  $accountname 
				))->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['trx'])) {
      $cmd = Yii::app()->db->cache(1000, $dependency)->createCommand()->select('t.*,a.companyname,b.currencyname,c.accounttypename,
				d.accountcode as parentaccountcode, d.accountname as parentaccountname ')
				->from('account t')
				->leftjoin('company a', 'a.companyid=t.companyid')
				->leftjoin('currency b', 'b.currencyid=t.currencyid')
				->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')
				->leftjoin('account d', 'd.accountid = t.parentaccountid')
				->where("((coalesce(t.accountcode,'') like :accountcode) 
				or (coalesce(d.accountcode,'') like :parentaccountcode) 
				or (coalesce(d.accountname,'') like :parentaccountname) 
				or (coalesce(c.accounttypename,'') like :accounttypename) 
				or (coalesce(a.companyname,'') like :companyname) 
				or (coalesce(b.currencyname,'') like :currencyname) 
				or (coalesce(t.accountname,'') like :accountname)) 
				and t.recordstatus = 1 
					and t.accounttypeid = 2 
					and t.companyid in (".getUserObjectValues('company').")", 
				array(
        ':accountcode' =>  $accountcode ,
        ':parentaccountcode' =>  $parentaccountcode ,
        ':parentaccountname' =>  $parentaccountname ,
        ':accounttypename' =>  $accounttypename ,
        ':companyname' =>  $companyname ,
        ':currencyname' =>  $currencyname ,
        ':accountname' =>  $accountname 
      ))->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['trxcom'])) {
      $cmd = Yii::app()->db->cache(1000, $dependency)->createCommand()->select('t.*,a.companyname,b.currencyname,c.accounttypename,
				d.accountcode as parentaccountcode,d.accountname as parentaccountname')
				->from('account t')
				->leftjoin('company a', 'a.companyid=t.companyid')
				->leftjoin('currency b', 'b.currencyid=t.currencyid')
				->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')
				->leftjoin('account d', 'd.accountid = t.parentaccountid')
				->where("((coalesce(t.accountcode,'') like :accountcode) 
				or (coalesce(d.accountcode,'') like :parentaccountcode) 
				or (coalesce(d.accountname,'') like :parentaccountname) 
				or (coalesce(c.accounttypename,'') like :accounttypename) 
				or (coalesce(a.companyname,'') like :companyname) 
				or (coalesce(b.currencyname,'') like :currencyname) 
				or (coalesce(t.accountname,'') like :accountname)) 
				and t.companyid = ".$companyid."
				and t.recordstatus=1 and t.accounttypeid = 2", array(
        ':accountcode' =>  $accountcode ,
        ':parentaccountcode' =>  $parentaccountcode ,
        ':parentaccountname' =>  $parentaccountname ,
        ':accounttypename' =>  $accounttypename ,
        ':companyname' =>  $companyname ,
        ':currencyname' =>  $currencyname ,
        ':accountname' =>  $accountname 
      ))->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['trxplant'])) {
      $cmd = Yii::app()->db->cache(1000, $dependency)->createCommand()->select('t.*,a.companyname,b.currencyname,c.accounttypename,
				d.accountcode as parentaccountcode,d.accountname as parentaccountname')
				->from('account t')
				->leftjoin('company a', 'a.companyid=t.companyid')
				->leftjoin('currency b', 'b.currencyid=t.currencyid')
				->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')
				->leftjoin('account d', 'd.accountid = t.parentaccountid')
			->leftjoin('plant e', 'e.companyid = t.companyid')
				->where("((coalesce(t.accountcode,'') like :accountcode) 
				or (coalesce(d.accountcode,'') like :parentaccountcode) 
				or (coalesce(d.accountname,'') like :parentaccountname) 
				or (coalesce(c.accounttypename,'') like :accounttypename) 
				or (coalesce(a.companyname,'') like :companyname) 
				or (coalesce(b.currencyname,'') like :currencyname) 
				or (coalesce(t.accountname,'') like :accountname)) 
				and e.plantid = '".$_GET['plantid']."' 
				and t.recordstatus=1 and t.accounttypeid = 2", array(
        ':accountcode' =>  $accountcode ,
        ':parentaccountcode' =>  $parentaccountcode ,
        ':parentaccountname' =>  $parentaccountname ,
        ':accounttypename' =>  $accounttypename ,
        ':companyname' =>  $companyname ,
        ':currencyname' =>  $currencyname ,
        ':accountname' =>  $accountname 
      ))->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['trxplantcbu'])) {
      $cmd = Yii::app()->db->cache(1000, $dependency)->createCommand()->select('t.*,a.companyname,b.currencyname,c.accounttypename,
				d.accountcode as parentaccountcode,d.accountname as parentaccountname')
				->from('account t')
				->leftjoin('company a', 'a.companyid=t.companyid')
				->leftjoin('currency b', 'b.currencyid=t.currencyid')
				->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')
				->leftjoin('account d', 'd.accountid = t.parentaccountid')
			->leftjoin('plant e', 'e.companyid = t.companyid')
				->where("((coalesce(t.accountcode,'') like :accountcode) 
				or (coalesce(d.accountcode,'') like :parentaccountcode) 
				or (coalesce(d.accountname,'') like :parentaccountname) 
				or (coalesce(c.accounttypename,'') like :accounttypename) 
				or (coalesce(a.companyname,'') like :companyname) 
				or (coalesce(b.currencyname,'') like :currencyname) 
				or (coalesce(t.accountname,'') like :accountname)) 
				and e.plantid = '".$_GET['plantid']."' 
				and t.accountid in (".GetUserObjectValues('accountcashbank').")
				and t.recordstatus=1 and t.accounttypeid = 2", array(
        ':accountcode' =>  $accountcode ,
        ':parentaccountcode' =>  $parentaccountcode ,
        ':parentaccountname' =>  $parentaccountname ,
        ':accounttypename' =>  $accounttypename ,
        ':companyname' =>  $companyname ,
        ':currencyname' =>  $currencyname ,
        ':accountname' =>  $accountname 
      ))->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['trxsloc'])) {
      $cmd = Yii::app()->db->cache(1000, $dependency)->createCommand()->select('t.*,a.companyname,b.currencyname,c.accounttypename,
				f.accountcode as parentaccountcode,f.accountname as parentaccountname')
				->from('account t')
				->leftjoin('company a', 'a.companyid=t.companyid')
				->leftjoin('currency b', 'b.currencyid=t.currencyid')
				->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')
				->leftjoin('plant d', 'd.companyid = t.companyid')
				->leftjoin('sloc e', 'e.plantid = d.plantid')
				->leftjoin('account f', 'f.accountid = t.parentaccountid')
				->where("((coalesce(t.accountcode,'') like :accountcode) 
				or (coalesce(f.accountcode,'') like :parentaccountcode) 
				or (coalesce(f.accountname,'') like :parentaccountname) 
				or (coalesce(c.accounttypename,'') like :accounttypename) 
				or (coalesce(a.companyname,'') like :companyname) 
				or (coalesce(b.currencyname,'') like :currencyname) 
				or (coalesce(t.accountname,'') like :accountname)) 
				and e.slocid = '".$_GET['slocid']."' 
				and t.recordstatus=1 and t.accounttypeid = 2 
				and t.companyid in (".getUserObjectValues('company').")", array(
        ':accountcode' =>  $accountcode ,
        ':parentaccountcode' =>  $parentaccountcode ,
        ':parentaccountname' =>  $parentaccountname ,
        ':accounttypename' =>  $accounttypename ,
        ':currencyname' =>  $currencyname ,
        ':companyname' =>  $companyname ,
        ':accountname' =>  $accountname 
      ))->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->cache(1000, $dependency)->createCommand()->select('t.*,a.companyname,b.currencyname,c.accounttypename,
				d.accountcode as parentaccountcode,d.accountname as parentaccountname')
				->from('account t')
				->leftjoin('company a', 'a.companyid=t.companyid')
				->leftjoin('currency b', 'b.currencyid=t.currencyid')
				->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')
				->leftjoin('account d', 'd.accountid = t.parentaccountid')
				->where("(coalesce(t.accountcode,'') like :accountcode) 
				and (coalesce(t.accountcode,'') like :parentaccountcode) 
				and (coalesce(t.accountname,'') like :parentaccountname) 
				and (coalesce(c.accounttypename,'') like :accounttypename) 
				and (coalesce(a.companyname,'') like :companyname) 
				and (coalesce(b.currencyname,'') like :currencyname) 
				and (coalesce(t.accountname,'') like :accountname)", array(
        ':accountcode' =>  $accountcode ,
        ':parentaccountcode' =>  $parentaccountcode ,
        ':parentaccountname' =>  $parentaccountname ,
        ':accounttypename' =>  $accounttypename ,
        ':companyname' =>  $companyname ,
        ':currencyname' =>  $currencyname ,
        ':accountname' =>  $accountname 
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'accountid' => $data['accountid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'accountcode' => $data['accountcode'],
        'accountname' => $data['accountname'],
        'parentaccountid' => $data['parentaccountid'],
        'parentaccountcode' => $data['parentaccountcode'],
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'accounttypeid' => $data['accounttypeid'],
        'accounttypename' => $data['accounttypename'],
        'recordstatus' => $data['recordstatus'],
        'isdebit' => $data['isdebit']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	public function searchcombosloc() {
		header('Content-Type: application/json');
		$description = isset ($_GET['q']) ? $_GET['q'] : '';
		$accountcode = isset($_GET['accountcode']) ? $_GET['accountcode'] : '';
		$accountname = isset($_GET['accountname']) ? $_GET['accountname'] : '';
		$slocid = isset ($_GET['slocid']) ? $_GET['slocid'] : '';
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'a.accountid';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'desc' ;
		$offset = ($page-1) * $rows;		
		$result = array();
		$row = array();
		$sqlcount = "SELECT COUNT(1) as total 
								FROM account a 
								JOIN company b ON b.companyid = a.companyid
								JOIN plant c ON c.companyid = b.companyid
								JOIN sloc d ON d.plantid = c.plantid
								WHERE a.recordstatus = 1 AND a.accounttypeid = 2 
								AND a.accountname LIKE '%".$description."%'
								AND d.slocid = ".$slocid."";
		$cmd = Yii::app()->db->createCommand($sqlcount)->queryScalar();
		$result['total'] = $cmd;
		$sqldata = "SELECT a.accountid, a.accountcode, a.accountname, b.companyname
								FROM account a 
								JOIN company b ON b.companyid = a.companyid
								JOIN plant c ON c.companyid = b.companyid
								JOIN sloc d ON d.plantid = c.plantid
								WHERE a.recordstatus = 1 AND a.accounttypeid = 2 
								AND a.accountname LIKE '%".$description."%'
								AND d.slocid = ".$slocid;
		$cmd = Yii::app()->db->createCommand($sqldata)->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'companyname'=>$data['companyname'],
				'accountname'=>$data['accountname'],
				'accountcode'=>$data['accountcode'],
				'accountid'=>$data['accountid'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
  public function actionGetAccount() {
		header('Content-Type: application/json');
		$accheaderid = isset($_POST['accheaderid']) ? $_POST['accheaderid'] : '';
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()->select('accountcode, accountname')->from('account t')->where('accountid = :accountid', array(
				':accountid' => $accheaderid))->queryRow();
		$result['accountname'] = $cmd['accountname'];
		$result['accountcode'] = $cmd['accountcode'];
		echo CJSON::encode($result);
  }
	public function actionAccountCompany() {
		header('Content-Type: application/json');
		$accountcode = isset($_GET['accountcode']) ? $_GET['accountcode'] : '';
		$accountname = isset($_GET['accountname']) ? $_GET['accountname'] : '';
		$accountname = isset ($_GET['q']) ? $_GET['q'] : '';
		$companyid = isset ($_GET['companyid']) ? $_GET['companyid'] : '';
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'a.accountid';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'desc' ;
		$offset = ($page-1) * $rows;		
		$result = array();
		$row = array();
		$sqlcount = "select count(1) as total 
								from account a 
								where a.recordstatus = 1 
								and a.accountname LIKE '%".$accountname."%'
								AND a.companyid = ".$companyid;
    $cmd = Yii::app()->db->createCommand($sqlcount)->queryScalar();
		$result['total'] = $cmd;
		$sqldata = "select a.accountid, a.accountcode, a.accountname, b.companyname
								FROM account a 
								JOIN company b ON b.companyid = a.companyid
								WHERE a.recordstatus = 1 
								AND a.accountname LIKE '%".$accountname."%'
								AND a.companyid = ".$companyid." LIMIT ".$offset.",".$rows;
		$cmd = Yii::app()->db->createCommand($sqldata)->queryAll();    
		foreach($cmd as $data) {	
			$row[] = array(
				'companyname'=>$data['companyname'],
				'accountname'=>$data['accountname'],
				'accountcode'=>$data['accountcode'],
				'accountid'=>$data['accountid'],
			);
		}
    $result=array_merge($result,array('rows'=>$row));
		echo CJSON::encode($result);
	}
	public function actionAccountFormula() {        
		header('Content-Type: application/json');
		$accountcode = isset($_GET['accountcode']) ? $_GET['accountcode'] : '';
		$accountname = isset($_GET['accountname']) ? $_GET['accountname'] : '';
		$accountname = isset ($_GET['q']) ? $_GET['q'] : '';
		$companyid = isset ($_GET['companyid']) ? $_GET['companyid'] : '';
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'a.accountid';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'desc' ;
		$offset = ($page-1) * $rows;		
		$result = array();
		$row = array();        
		$sqlcount = "select count(1) as total 
								from account a 
								where a.recordstatus = 1 
								and a.accountname LIKE '%".$accountname."%'
								AND a.companyid = ".$companyid;		
		$cmd = Yii::app()->db->createCommand($sqlcount)->queryScalar();
		$result['total'] = $cmd;    
		$sqldata = "select a.accountid, a.accountcode, a.accountname, b.companyname
								FROM account a 
								JOIN company b ON b.companyid = a.companyid
								WHERE a.recordstatus = 1 
								AND a.accountname LIKE '%".$accountname."%'
								AND a.companyid = ".$companyid." LIMIT ".$offset.",".$rows;
		$cmd = Yii::app()->db->createCommand($sqldata)->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'companyname'=>$data['companyname'],
				'accountname'=>$data['accountname'],
				'accountcode'=>$data['accountcode'],
				'accountid'=>$data['accountid'],
			);
		}
    $result=array_merge($result,array('rows'=>$row));
		echo CJSON::encode($result);
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['companyname'] = GetSearchText(array('GET'),'companyname');
		$this->dataprint['accountcode'] = GetSearchText(array('GET'),'accountcode');
		$this->dataprint['accountname'] = GetSearchText(array('GET'),'accountname');
		$this->dataprint['accounttypename'] = GetSearchText(array('GET'),'accounttypename');
		$this->dataprint['parentaccountcode'] = GetSearchText(array('GET'),'parentaccountcode');
		$this->dataprint['parentaccountname'] = GetSearchText(array('GET'),'parentaccountname');
		$this->dataprint['currencyname'] = GetSearchText(array('GET'),'currencyname');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'accountid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlecompanyname'] = GetCatalog('company');
		$this->dataprint['titleaccountcode'] = GetCatalog('accountcode');
		$this->dataprint['titleaccountname'] = GetCatalog('accountname');
		$this->dataprint['titleaccounttypename'] = GetCatalog('accounttypename');
		$this->dataprint['titleparentaccount'] = GetCatalog('parentaccount');
		$this->dataprint['titlecurrencyname'] = GetCatalog('currencyname');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}