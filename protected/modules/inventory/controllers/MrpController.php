<?php
class MrpController extends Controller {
  public $menuname = 'mrp';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $product       = isset($_POST['product']) ? $_POST['product'] : '';
    $sloc          = isset($_POST['slocid']) ? $_POST['slocid'] : '';
    $uom           = isset($_POST['uomid']) ? $_POST['uomid'] : '';
    $mrpid           = isset($_POST['mrpid']) ? $_POST['mrpid'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'mrpid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('mrp t')
		->leftjoin('product a', 'a.productid = t.productid')
		->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
		->leftjoin('sloc c', 'c.slocid = t.slocid')
		->leftjoin('materialtype d', 'd.materialtypeid = a.materialtypeid')
		->where("(coalesce(a.productname,'') like :productname) or (coalesce(a.productcode,'') like :productcode)
		and (coalesce(b.uomcode,'') like :uomcode)
		and (coalesce(t.mrpid,'') like :mrpid)
		and (coalesce(c.sloccode,'') like :sloc) 
		and c.slocid in (".getUserObjectValues('sloc').")", array(
      ':productname' => '%' . $product . '%',
	  ':productcode' => '%' . $product . '%',
      ':sloc' => '%' . $sloc . '%',
      ':mrpid' => '%' . $mrpid . '%',
      ':uomcode' => '%' . $uom . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,d.materialtypecode,a.productcode,a.productname,b.uomcode,c.slocid,c.sloccode,c.description as slocdesc')->from('mrp t')
		->leftjoin('product a', 'a.productid = t.productid')
		->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
		->leftjoin('sloc c', 'c.slocid = t.slocid')
		->leftjoin('materialtype d', 'd.materialtypeid = a.materialtypeid')
		->where("(coalesce(a.productname,'') like :productname) or (coalesce(a.productcode,'') like :productcode)
		and (coalesce(b.uomcode,'') like :uomcode)
		and (coalesce(t.mrpid,'') like :mrpid)
		and (coalesce(c.sloccode,'') like :sloc)  and c.slocid in (".getUserObjectValues('sloc').")", array(
      ':productname' => '%' . $product . '%',
	  ':productcode' => '%' . $product . '%',
      ':sloc' => '%' . $sloc . '%',
      ':mrpid' => '%' . $mrpid . '%',
      ':uomcode' => '%' . $uom . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'mrpid' => $data['mrpid'],
        'productid' => $data['productid'],
		'productcode' => $data['productcode'],
		'materialtypecode' => $data['materialtypecode'],
        'productname' => $data['productname'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'] . '-' . $data['slocdesc'],
        'minstock' => Yii::app()->format->formatNumber($data['minstock']),
        'reordervalue' => Yii::app()->format->formatNumber($data['reordervalue']),
        'maxvalue' => Yii::app()->format->formatNumber($data['maxvalue']),
        'leadtime' => $data['leadtime'],
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'recordstatus' => $data['recordstatus']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSave() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertmrp(:vproductid,:vslocid,:vminstock,:vreordervalue,:vmaxvalue,:vleadtime,:vuomid,:vrecordstatus,:vdatauser)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatemrp(:vid,:vproductid,:vslocid,:vminstock,:vreordervalue,:vmaxvalue,:vleadtime,:vuomid,:vrecordstatus,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['mrpid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['mrpid']);
      }
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vslocid', $_POST['slocid'], PDO::PARAM_STR);
      $command->bindvalue(':vminstock', $_POST['minstock'], PDO::PARAM_STR);
      $command->bindvalue(':vreordervalue', $_POST['reordervalue'], PDO::PARAM_STR);
      $command->bindvalue(':vmaxvalue', $_POST['maxvalue'], PDO::PARAM_STR);
      $command->bindvalue(':vleadtime', $_POST['leadtime'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
      $command->bindvalue(':vrecordstatus', $_POST['recordstatus'], PDO::PARAM_STR);
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
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgemrp(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select a.mrpid,b.productcode,b.productname,concat (c.sloccode,'-',c.description)as sloc,a.minstock,a.reordervalue,a.`maxvalue`,a.leadtime,d.uomcode,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from mrp a
						left join product b on b.productid = a.productid
						left join sloc c on c.slocid = a.slocid
						left join unitofmeasure d on d.unitofmeasureid = a.uomid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.mrpid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = getCatalog('mrp');
    $this->pdf->AddPage('P', array(
      350,
      250
    ));
    $this->pdf->setFont('Arial', 'B', 10);
    $this->pdf->colalign  = array(
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
      getCatalog('mrpid'),
      getCatalog('productname'),
      getCatalog('sloc'),
      getCatalog('minstock'),
      getCatalog('reordervalue'),
      getCatalog('maxvalue'),
      getCatalog('leadtime'),
      getCatalog('uomcode'),
      getCatalog('recordstatus')
    );
    $this->pdf->setwidths(array(
      10,
      150,
      80,
      20,
      30,
      30,
      25,
      25,
      20
    ));
    $this->pdf->Rowheader();
    $this->pdf->setFont('Arial', '', 10);
    $this->pdf->coldetailalign = array(
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
        $row1['mrpid'],
        $row1['productcode'] . '-' . $row1['productname'],
        $row1['sloc'],
        $row1['minstock'],
        $row1['reordervalue'],
        $row1['maxvalue'],
        $row1['leadtime'],
        $row1['uomcode'],
        $row1['recordstatus']
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    $this->menuname = 'mrp';
    parent::actionDownxls();
    $sql = "select a.mrpid,b.productcode,b.productname,concat (c.sloccode,'-',c.description)as sloc,a.minstock,a.reordervalue,a.`maxvalue`,a.leadtime,d.uomcode,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from mrp a
						left join product b on b.productid = a.productid
						left join sloc c on c.slocid = a.slocid
						left join unitofmeasure d on d.unitofmeasureid = a.uomid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.mrpid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 3;
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)
	  ->setCellValueByColumnAndRow(0, $i, $row1['mrpid'])
	  ->setCellValueByColumnAndRow(1, $i, $row1['productcode'] . '-' . $row1['productname'])
	  ->setCellValueByColumnAndRow(2, $i, $row1['sloc'])
	  ->setCellValueByColumnAndRow(3, $i, $row1['minstock'])
	  ->setCellValueByColumnAndRow(4, $i, $row1['reordervalue'])
	  ->setCellValueByColumnAndRow(5, $i, $row1['maxvalue'])
	  ->setCellValueByColumnAndRow(6, $i, $row1['leadtime'])
	  ->setCellValueByColumnAndRow(7, $i, $row1['uomcode'])
	  ->setCellValueByColumnAndRow(8, $i, $row1['recordstatus']);
      $i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}