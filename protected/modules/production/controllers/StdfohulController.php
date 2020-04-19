<?php
class StdfohulController extends Controller {
  public $menuname = 'stdfohul';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  
  public function search() {
    header('Content-Type: application/json');
    $stdfohulid    = GetSearchText(array('POST','Q'),'stdfohulid');
    $plantcode     = GetSearchText(array('POST','Q'),'plantcode');
    $productname         = GetSearchText(array('POST','Q'),'productname');
		$plantid = GetSearchText(array('POST','GET'),'plantid',0,'int');
		$materialtypecode = GetSearchText(array('POST','Q'),'materialtypecode');
		$page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','stdfohulid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
    $offset            = ($page - 1) * $rows;
    $result            = array();
    $row               = array();
    $cmd               = Yii::app()->db->createCommand()->select('count(1) as total')
		->from('stdfohul t')
		->leftjoin('plant a', 'a.plantid = t.plantid')
		->leftjoin('product b', 'b.productid = t.productid')
		->leftjoin('unitofmeasure c','c.unitofmeasureid=t.uomcid')
		->leftjoin('materialtype d', 'd.materialtypeid = b.materialtypeid')
		->leftjoin('unitofmeasure e','e.unitofmeasureid=t.uomid')
		
		->where("((a.plantcode like :plantcode) and 
			(b.productname like :productname) and 
			(t.stdfohulid like :stdfohulid))
			and t.plantid in (".getUserObjectValues('plant').")
			", array(
      ':plantcode' =>  $plantcode ,
      ':productname' =>  $productname ,
      ':stdfohulid' =>  $stdfohulid 
      
    ))->queryScalar();
    $result['total']   = $cmd;
    $cmd               = Yii::app()->db->createCommand()->select('t.*,a.plantcode,b.productname,c.uomcode as uomccode,e.uomcode,d.materialtypecode')
		->from('stdfohul t')
		->leftjoin('plant a', 'a.plantid = t.plantid')
		->leftjoin('product b', 'b.productid = t.productid')
		->leftjoin('unitofmeasure c','c.unitofmeasureid=t.uomcid')
		->leftjoin('materialtype d', 'd.materialtypeid = b.materialtypeid')
		->leftjoin('unitofmeasure e','e.unitofmeasureid=t.uomid')
		->where("((a.plantcode like :plantcode) and 
			(b.productname like :productname) and 
			(t.stdfohulid like :stdfohulid))
			
			and t.plantid in (".getUserObjectValues('plant').")
		
			", 
			array(':plantcode' =>  $plantcode ,
      ':productname' =>  $productname ,
      ':stdfohulid' =>  $stdfohulid 
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'stdfohulid' => $data['stdfohulid'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'valued' => Yii::app()->format->formatCurrency($data['valued']),
        'uomcid' => $data['uomcid'],
        'uomccode' => $data['uomccode'],
				'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
				'startdate' => date(Yii::app()->params["dateviewfromdb"], strtotime($data['startdate'])),
				'enddate' => date(Yii::app()->params["dateviewfromdb"], strtotime($data['enddate'])),
				'materialtypecode' => $data['materialtypecode']
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
        $sql     = 'call Insertstdfohul(:vplantid,:vproductid,:vvalued,:vuomid,:vuomcid,:vstartdate,:venddate,:vdatauser)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatestdfohul(:vid,:vplantid,:vproductid,:vvalued,:vuomid,:vuomcid,:vstartdate,:venddate,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['stdfohulid'], PDO::PARAM_STR);
      }
      $command->bindvalue(':vplantid', $_POST['plantid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vvalued', $_POST['valued'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
      $command->bindvalue(':vuomcid', $_POST['uomcid'], PDO::PARAM_STR);
      $command->bindvalue(':vstartdate', date(Yii::app()->params['datetodb'], strtotime($_POST['startdate'])), PDO::PARAM_STR);
      $command->bindvalue(':venddate', date(Yii::app()->params['datetodb'], strtotime($_POST['enddate'])), PDO::PARAM_STR);
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
        $sql     = 'call PurgeStdfohul(:vid,:vdatauser)';
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
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select addressbookid,productid,deliverytime,purchasinggroupid,toleransidown,toleransiup,price,currencyid,biddate,recordstatus
				from stdfohul a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.stdfohulid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('stdfohul');
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
				from stdfohul a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.stdfohulid in (" . $_GET['id'] . ")";
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