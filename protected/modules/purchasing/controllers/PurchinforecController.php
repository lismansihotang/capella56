<?php
class PurchinforecController extends Controller {
  public $menuname = 'purchinforec';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionSave() {
		parent::actionWrite();
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertpurchinforec(:vaddressbookid,:vpoheaderid,:vproductid,:vtoleransidown,:vtoleransiup,:vprice,:vcurrencyid,:vcurrencyrate,:vbiddate,:vdatauser)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatepurchinforec(:vid,:vaddressbookid,:vpoheaderid,:vproductid,:vtoleransidown,:vtoleransiup,:vprice,:vcurrencyid,:vcurrencyrate,:vbiddate,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['purchinforecid'], PDO::PARAM_STR);
      }
      $command->bindvalue(':vaddressbookid', $_POST['addressbookid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vpoheaderid', $_POST['poheaderid'], PDO::PARAM_STR);
      $command->bindvalue(':vtoleransidown', $_POST['toleransidown'], PDO::PARAM_STR);
      $command->bindvalue(':vtoleransiup', $_POST['toleransiup'], PDO::PARAM_STR);
      $command->bindvalue(':vprice', $_POST['price'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyid', $_POST['currencyid'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyrate', $_POST['currencyrate'], PDO::PARAM_STR);
      $command->bindvalue(':vbiddate', date(Yii::app()->params['datetodb'], strtotime($_POST['biddate'])), PDO::PARAM_STR);
      $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
      $command->execute();
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
        $sql     = 'call PurgePurcInforec(:vid,:vdatauser)';
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
    $purchinforecid    = GetSearchText(array('POST','Q'),'purchinforecid');
    $supplier     = GetSearchText(array('POST','Q'),'supplier');
    $productname         = GetSearchText(array('POST','Q'),'productname');
		$page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','purchinforecid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
    $offset            = ($page - 1) * $rows;
    $result            = array();
    $row               = array();
    $cmd               = Yii::app()->db->createCommand()->select('count(1) as total')->from('purchinforec t')
		->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
		->leftjoin('product b', 'b.productid = t.productid')
		->leftjoin('poheader c', 'c.poheaderid = t.poheaderid')
		->leftjoin('currency d', 'd.currencyid = t.currencyid')
		->where("((a.fullname like :supplier) and 
			(b.productname like :productname) and 
			(t.purchinforecid like :purchinforecid))", array(
      ':supplier' =>  $supplier ,
      ':productname' =>  $productname ,
      ':purchinforecid' =>  $purchinforecid 
      
    ))->queryScalar();
    $result['total']   = $cmd;
    $cmd               = Yii::app()->db->createCommand()->select('t.*,a.fullname,b.productname,c.pono,d.currencyname')->from('purchinforec t')
		->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
		->leftjoin('product b', 'b.productid = t.productid')
		->leftjoin('poheader c', 'c.poheaderid = t.poheaderid')
		->leftjoin('currency d', 'd.currencyid = t.currencyid')
		->where("((a.fullname like :supplier) and 
			(b.productname like :productname) and 
			(t.purchinforecid like :purchinforecid))", 
			array(':supplier' =>  $supplier ,
      ':productname' =>  $productname ,
      ':purchinforecid' =>  $purchinforecid 
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'purchinforecid' => $data['purchinforecid'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'toleransidown' => $data['toleransidown'],
        'toleransiup' => $data['toleransiup'],
        'price' => Yii::app()->format->formatCurrency($data['price']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => $data['currencyrate'],
        'biddate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['biddate'])),
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actiongetprice() {
		$product = null;
		$cmd='';
		if(isset($_POST['productid']) && isset($_POST['addressbookid'])) {
      $cmd = Yii::app()->db->createCommand("select coalesce(price ,0) as price
      from purchinforec 
      where addressbookid = ".$_POST['addressbookid'] ." and productid = ".$_POST['productid']." 
      order by biddate desc limit 1")->queryRow();
    }
    if ($cmd['price'] == null) {
      $cmd['price'] = 0;
    }
    echo CJSON::encode(array(
      'status'=>'success',
      'currencyvalue'=> Yii::app()->format->formatNumber($cmd['price']),
      ));
    Yii::app()->end();
	}
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select addressbookid,productid,deliverytime,purchasinggroupid,toleransidown,toleransiup,price,currencyid,biddate,recordstatus
				from purchinforec a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.purchinforecid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('purchinforec');
    $this->pdf->AddPage('P');
    $this->pdf->colalign  = array(
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L'
    );
    $this->pdf->colheader = array(
      GetCatalog('addressbookid'),
      GetCatalog('productid'),
      GetCatalog('deliverytime'),
      GetCatalog('purchasinggroupid'),
      GetCatalog('toleransidown'),
      GetCatalog('toleransiup'),
      GetCatalog('price'),
      GetCatalog('currencyid'),
      GetCatalog('biddate'),
      GetCatalog('recordstatus')
    );
    $this->pdf->setwidths(array(
      40,
      40,
      40,
      40,
      40,
      40,
      40,
      40,
      40,
      40
    ));
    $this->pdf->Rowheader();
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L'
    );
    foreach ($dataReader as $row1) {
      $this->pdf->row(array(
        $row1['addressbookid'],
        $row1['productid'],
        $row1['deliverytime'],
        $row1['purchasinggroupid'],
        $row1['toleransidown'],
        $row1['toleransiup'],
        $row1['price'],
        $row1['currencyid'],
        $row1['biddate'],
        $row1['recordstatus']
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    parent::actionDownXls();
    $sql = "select addressbookid,productid,deliverytime,purchasinggroupid,toleransidown,toleransiup,price,currencyid,biddate,recordstatus
				from purchinforec a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.purchinforecid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $i          = 1;
    $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('addressbookid'))->setCellValueByColumnAndRow(1, 1, GetCatalog('productid'))->setCellValueByColumnAndRow(2, 1, GetCatalog('deliverytime'))->setCellValueByColumnAndRow(3, 1, GetCatalog('purchasinggroupid'))->setCellValueByColumnAndRow(4, 1, GetCatalog('toleransidown'))->setCellValueByColumnAndRow(5, 1, GetCatalog('toleransiup'))->setCellValueByColumnAndRow(6, 1, GetCatalog('price'))->setCellValueByColumnAndRow(7, 1, GetCatalog('currencyid'))->setCellValueByColumnAndRow(8, 1, GetCatalog('biddate'))->setCellValueByColumnAndRow(9, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['addressbookid'])->setCellValueByColumnAndRow(1, $i + 1, $row1['productid'])->setCellValueByColumnAndRow(2, $i + 1, $row1['deliverytime'])->setCellValueByColumnAndRow(3, $i + 1, $row1['purchasinggroupid'])->setCellValueByColumnAndRow(4, $i + 1, $row1['toleransidown'])->setCellValueByColumnAndRow(5, $i + 1, $row1['toleransiup'])->setCellValueByColumnAndRow(6, $i + 1, $row1['price'])->setCellValueByColumnAndRow(7, $i + 1, $row1['currencyid'])->setCellValueByColumnAndRow(8, $i + 1, $row1['biddate'])->setCellValueByColumnAndRow(9, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    $this->getFooterXls($this->phpExcel);
  }
}