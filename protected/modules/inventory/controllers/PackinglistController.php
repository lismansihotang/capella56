<?php
class PackinglistController extends Controller {
  public $menuname = 'packinglist';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
		$id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'packinglistid' => $id,
		));
  }
	 public function actionGeneratedetail() {
    if (isset($_POST['id'])) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateGIPL(:vid,:vhid,:vdatauser)';
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
  }
  public function search() {
    header('Content-Type: application/json');
		$packinglistid 		= GetSearchText(array('POST','GET','Q'),'packinglistid');
		$plantid     		= GetSearchText(array('POST','GET'),'plantid',0,'int');
		$plantcode     		= GetSearchText(array('POST','GET','Q'),'plantcode');
		$packinglistdate    = GetSearchText(array('POST','GET','Q'),'packinglistdate');
		$packinglistno 		= GetSearchText(array('POST','GET','Q'),'packinglistno');
		$gino 		= GetSearchText(array('POST','GET','Q'),'gino');
		$sono 		= GetSearchText(array('POST','GET','Q'),'sono');
		$addressbookid      = GetSearchText(array('POST','GET'),'addressbookid',0,'int');
		$customername      = GetSearchText(array('POST','GET','Q'),'customername');
		$addressname 		= GetSearchText(array('POST','GET','Q'),'addressname');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','packinglistid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('packinglist t')
			->leftjoin('addressbook b', 'b.addressbookid = t.addressbookid')
			->leftjoin('address c', 'c.addressid = t.addresstoid')
			->leftjoin('plant d', 'd.plantid = t.plantid')
			->leftjoin('company e', 'e.companyid = d.companyid')
			->leftjoin('addressbook f', 'f.addressbookid = t.supplierid')
			->where("
			((coalesce(packinglistid,'') like :packinglistid) 
			and (coalesce(packinglistdate,'') like :packinglistdate) 
			and (coalesce(packinglistno,'') like :packinglistno) 
			and (coalesce(d.plantcode,'') like :plantcode) 
			and (coalesce(gino,'') like :gino) 
			and (coalesce(b.fullname,'') like :customername) 
			and (coalesce(c.addressname,'') like :addressname)
			and (coalesce(sono,'') like :sono))
			and t.recordstatus in (".getUserRecordStatus('listpacklist').") 
			and t.plantid in (".getUserObjectValues('plant').")
			",
			array(
			':packinglistid' => '%' . $packinglistid . '%',
			':packinglistdate' => '%' . $packinglistdate . '%',
			':packinglistno' => '%' . $packinglistno . '%',
			':plantcode' => '%' . $plantcode . '%',
			':gino' => '%' . $gino . '%',
			':customername' => '%' . $customername . '%',
			':addressname' => '%' . $addressname . '%',
			':sono' => '%' . $sono . '%'
		))->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()->select('t.*,b.fullname as customername, c.addressname, d.plantcode,d.companyid, e.companyname,f.fullname as suppliername')
			->from('packinglist t')
			->leftjoin('addressbook b', 'b.addressbookid = t.addressbookid')
			->leftjoin('addressbook f', 'f.addressbookid = t.supplierid')
			->leftjoin('address c', 'c.addressid = t.addresstoid')
			->leftjoin('plant d', 'd.plantid = t.plantid')
			->leftjoin('company e', 'e.companyid = d.companyid')
			->where("
			((coalesce(packinglistid,'') like :packinglistid) 
			and (coalesce(packinglistdate,'') like :packinglistdate) 
			and (coalesce(packinglistno,'') like :packinglistno) 
			and (coalesce(d.plantcode,'') like :plantcode) 
			and (coalesce(gino,'') like :gino) 
			and (coalesce(b.fullname,'') like :customername) 
			and (coalesce(c.addressname,'') like :addressname)
			and (coalesce(sono,'') like :sono)) 
			and t.recordstatus in (".getUserRecordStatus('listpacklist').") 
			and t.plantid in (".getUserObjectValues('plant').")
			",
			array(
			':packinglistid' => '%' . $packinglistid . '%',
			':packinglistdate' => '%' . $packinglistdate . '%',
			':packinglistno' => '%' . $packinglistno . '%',
			':plantcode' => '%' . $plantcode . '%',
			':gino' => '%' . $gino . '%',
			':customername' => '%' . $customername . '%',
			':addressname' => '%' . $addressname . '%',
			':sono' => '%' . $sono . '%',
			))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		foreach ($cmd as $data) {
			$row[] = array(
				'packinglistid' => $data['packinglistid'],
				'packinglistdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['packinglistdate'])),
				'packinglistno' => $data['packinglistno'],
				'plantid' => $data['plantid'],
				'plantcode' => $data['plantcode'],
				'companyid' => $data['companyid'],
				'companyname' => $data['companyname'],
				'giheaderid' => $data['giheaderid'],
				'gino' => $data['gino'],
				'addressbookid' => $data['addressbookid'],
				'customername' => $data['customername'],
				'supplierid' => $data['supplierid'],
				'suppliername' => $data['suppliername'],
				'addresstoid' => $data['addresstoid'],
				'addressname' => $data['addressname'],
				'soheaderid' => $data['soheaderid'],
				'sono' => $data['sono'],
				'pebno' => $data['pebno'],
				'nomobil' => $data['nomobil'],
				'sopir' => $data['sopir'],
				'recordstatus' => $data['recordstatus'],
				'statusname' => $data['statusname']
			);
		}
		$result = array_merge($result, array(
			'rows' => $row
		));
    return CJSON::encode($result);
	}
	public function actionSearchDetail() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'packlistdetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('packlistdetail t')
			->leftjoin('product b', 'b.productid = t.productid')
			->leftjoin('materialtype c', 'c.materialtypeid = b.materialtypeid')
			->where('t.packinglistid = :packinglistid',
			array(
				':packinglistid' => $id
			))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,b.productname,c.materialtypecode')
			->from('packlistdetail t')
			->leftjoin('product b', 'b.productid = t.productid')
			->leftjoin('materialtype c', 'c.materialtypeid = b.materialtypeid')
			->where('t.packinglistid = :packinglistid', array(
				':packinglistid' => $id
			))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'packlistdetailid' => $data['packlistdetailid'],
        'packinglistid' => $data['packinglistid'],
        'certoaid' => $data['certoaid'],
				'certoano' => $data['certoano'],
				'materialtypecode' => $data['materialtypecode'],
				'batchno' => $data['batchno'],
				'lotno' => $data['lotno'],
				'productid' => $data['productid'],
				'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
				'qty2' => Yii::app()->format->formatNumber($data['qty2']),
				'gross' => Yii::app()->format->formatNumber($data['gross']),
				'net' => Yii::app()->format->formatNumber($data['net']),
        'uomid' => $data['uomid'],
				'uom2id' => $data['uom2id'],
				'grossuom' => $data['grossuom'],
				'grossuomcode' => $data['grossuomcode'],
				'netuom' => $data['netuom'],
				'netuomcode' => $data['netuomcode'],
        'uomcode' => $data['uomcode'],
				'uom2code' => $data['uom2code']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function actionReject() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call RejectPackingList(:vid,:vdatauser)';
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
        $sql     = 'call ApprovePackinglist(:vid,:vdatauser)';
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
  private function ModifyData($connection,$arraydata) {
		$id = (int)$arraydata[0];
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$sql = 'call ModifPackingList(:vid,:vpackinglistdate,:vplantid,:vgiheaderid,:vsoheaderid,:vaddressbookid,:vaddresstoid,:vsupplierid,:vpocustno,:vpebno,:vnomobil,:vsopir,
			:vheadernote,:vdatauser)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vpackinglistdate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vgiheaderid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vsoheaderid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vaddresstoid', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vsupplierid', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vpocustno', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vpebno', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vnomobil', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vsopir', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[12], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionSave() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['packinglist-packinglistid'])?$_POST['packinglist-packinglistid']:''),
			date(Yii::app()->params['datetodb'], strtotime($_POST['packinglist-packinglistdate'])),
			$_POST['packinglist-plantid'],
			$_POST['packinglist-giheaderid'],
			$_POST['packinglist-soheaderid'],
			$_POST['packinglist-addressbookid'],
			$_POST['packinglist-addresstoid'],
			$_POST['packinglist-supplierid'],
			$_POST['packinglist-pocustno'],
			$_POST['packinglist-pebno'],
			$_POST['packinglist-nomobil'],
			$_POST['packinglist-sopir'],
			$_POST['packinglist-headernote'])
			);
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }	
  public function actionSavedetail() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertpacklistdetail(:vpackinglistid,:vbatchno,:vlotno,:vqty,:vuomid,:vqty2,:vuom2id,:vgross,:vgrossuom,:vnet,:vnetuom,:vdescription,:vdatauser)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatepacklistdetail(:vid,:vpackinglistid,:vbatchno,:vlotno,:vqty,:vuomid,:vqty2,:vuom2id,:vgross,:vgrossuom,:vnet,:vnetuom,:vdescription,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['packlistdetailid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['packlistdetailid']);
      }
      $command->bindvalue(':vpackinglistid', $_POST['packinglistid'], PDO::PARAM_STR);
      $command->bindvalue(':vbatchno', $_POST['batchno'], PDO::PARAM_STR);
      $command->bindvalue(':vlotno', $_POST['lotno'], PDO::PARAM_STR);
			$command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
			$command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
			$command->bindvalue(':vqty2', $_POST['qty2'], PDO::PARAM_STR);
			$command->bindvalue(':vuom2id', $_POST['uom2id'], PDO::PARAM_STR);
      $command->bindvalue(':vgross', $_POST['gross'], PDO::PARAM_STR);
			$command->bindvalue(':vgrossuom', $_POST['grossuom'], PDO::PARAM_STR);
      $command->bindvalue(':vnet', $_POST['net'], PDO::PARAM_STR);
      $command->bindvalue(':vnetuom', $_POST['netuom'], PDO::PARAM_STR);
      $command->bindvalue(':vdescription', $_POST['description'], PDO::PARAM_STR);
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
  public function actionPurge()
  {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call PurgePackingList(:vid,:vdatauser)';
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
  public function actionPurgedetail()
  {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgepacklistdetail(:vid,:vdatauser)';
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
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select t.packinglistid,t.packinglistdate,d.companyid,t.packinglistno,t.gino,t.sono,t.pocustno,t.nomobil,t.sopir,b.fullname as customername, c.addressname,t.headernote, d.plantcode,e.companyname,f.fullname as suppliername
			from packinglist t
			left join addressbook b on b.addressbookid = t.addressbookid
			left join addressbook f on f.addressbookid = t.supplierid
			left join address c on c.addressid = t.addresstoid
			left join plant d on d.plantid = t.plantid
			left join company e on e.companyid = d.companyid";
    if ($_GET['id'] !== '') {
      $sql = $sql . " where t.packinglistid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = getCatalog('packinglist');
    $this->pdf->AddPage('P', array(
      295,
      90
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->setFontSize(9);
      $this->pdf->text(10, $this->pdf->gety(), 'No Doc');
      $this->pdf->text(30, $this->pdf->gety(), ': ' . $row['packinglistno']);
      $this->pdf->text(10, $this->pdf->gety() + 4, 'Tgl Doc');
      $this->pdf->text(30, $this->pdf->gety() + 4, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['packinglistdate'])));
			$this->pdf->text(10, $this->pdf->gety() + 8, 'Ekpedisi');
      $this->pdf->text(30, $this->pdf->gety() + 8,  ': ' .$row['suppliername']);
			$this->pdf->text(10, $this->pdf->gety() + 13, 'Alamat Kirim');
      $this->pdf->text(30, $this->pdf->gety() + 13, ': ' .$row['addressname']);
			
      $this->pdf->text(68, $this->pdf->gety(), 'No GI');
      $this->pdf->text(88, $this->pdf->gety(), ': ' . $row['gino']);
			$this->pdf->text(68, $this->pdf->gety() + 4, 'No SO');
      $this->pdf->text(88, $this->pdf->gety() + 4, ': '. $row['sono']);
			$this->pdf->text(68, $this->pdf->gety() + 8, 'PO Customer');
      $this->pdf->text(88, $this->pdf->gety() + 8, ': '. $row['pocustno']);
			
      $this->pdf->text(125, $this->pdf->gety(), 'No Kendaraan');
      $this->pdf->text(147, $this->pdf->gety(), ': ' . $row['nomobil']);
      $this->pdf->text(125, $this->pdf->gety() + 4, 'Supir');
      $this->pdf->text(147, $this->pdf->gety() + 4, ': ' . $row['sopir']);
			$this->pdf->text(125, $this->pdf->gety() + 8, 'Customer');
      $this->pdf->text(147, $this->pdf->gety() + 8, ': ' . $row['customername']);
      
			$sql1        = "select *
											from packlistdetail a
							where a.packinglistid = " . $row['packinglistid'] . " group by a.packinglistid";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
			 $this->pdf->setFontSize(8);
      $this->pdf->sety($this->pdf->gety() + 15);
      $this->pdf->colalign = array(
        'L',
        'L',
        'L',
        'L',
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
      $this->pdf->setwidths(array(
        10,
        50,
        18,
        18,
        18,
        18,
        19,
        18,
        19,
        18,
        19,
        18,
        22,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Material',
        'No COA',
        'No Batch',
        'No LOT',
        'Qty',
        'Satuan',
        'Qty 2',
        'Satuan 2',
        'Net',
        'Satuan Net',
        'Gross',
        'Satuan Gross',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'C',           
        'R',      
        'R',      
        'R',      
        'R',      
        'L',            
        'R',            
        'L',
        'R',
				'L',            
        'R',            
        'L',
        'R',
        'R'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          $row1['certoano'],
          $row1['batchno'],
          $row1['lotno'],
          $row1['qty'],
          $row1['uomcode'],
          $row1['qty2'],
          $row1['uom2code'],
          $row1['net'],
          $row1['netuomcode'],
          $row1['gross'],
          $row1['grossuomcode'],
          $row1['description']
        ));
      }
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        30,
        160
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none'
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Keterangan',
        $row['headernote']
      ));
			$this->pdf->CheckPageBreak(30);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'Dibuat oleh,');
      $this->pdf->text(50, $this->pdf->gety() + 5, 'Diperiksa oleh,');
     
      $this->pdf->text(10, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(50, $this->pdf->gety() + 15, '........................');
     
    }
    $this->pdf->Output();
  }
  public function actionDownPDFminus()
  {
    parent::actionDownload();
    $sql = "select a.packinglistid,a.packinglistno,a.formrequestdate,b.sloccode,a.description
						from formrequest a
						inner join sloc b on b.slocid = a.slocfromid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.packinglistid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = "Produk Yang Akan Minus Setelah Koreksi";
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNBPages();
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['packinglistno']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Date ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['formrequestdate'])));
      $this->pdf->text(135, $this->pdf->gety() + 5, 'Gudang ');
      $this->pdf->text(170, $this->pdf->gety() + 5, ': ' . $row['sloccode']);
      $i           = 0;
      $totalqty    = 0;
      $totaljumlah = 0;
      $sql1        = "select * from (select *,qty+qtystock as selisih
						from (select formrequestrawid,d.productname,sum(a.qty) as qty,
						sum(a.qtystdkg) as qtystdkg,sum(a.qtystdmtr) as qtystdmtr,
						ifnull((select ifnull(b.qty,0) from productstock b 
						where b.productid=a.productid 
						and b.slocid=c.slocid 
						and b.unitofmeasureid=a.unitofmeasureid 
						and b.storagebinid=a.storagebinid),0) as qtystock
						from formrequestraw a
						join formrequest c on c.packinglistid=a.packinglistid
						join product d on d.productid=a.productid
						where a.packinglistid in (" . $_GET['id'] . ")
						group by a.productid,unitofmeasureid,storagebinid) z) zz
						where selisih < 0";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
        $this->pdf->sety($this->pdf->gety() + 15);
        $this->pdf->colalign = array('C','C','C','C','C','C','C');
        $this->pdf->setFont('Arial', 'B', 8);
        $this->pdf->setwidths(array(7,115,30,30,30,30,30));
        $this->pdf->colheader = array('No','Nama Barang','Qty Koreksi','Qty KG Koreksi','Qty Meter Koreksi','Qty Stock','Qty Setelah Koreksi');
        $this->pdf->RowHeader();
        $this->pdf->setFont('Arial', '', 8);
        $this->pdf->coldetailalign = array('R','L','R','R','R','R','R');
      foreach ($dataReader1 as $row1) {
        $i                         = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productcode'] . '-' . $row1['productname'],
          Yii::app()->format->formatNumber($row1['qty']),
		  Yii::app()->format->formatNumber($row1['qtystdkg']),
		  Yii::app()->format->formatNumber($row1['qtystdmtr']),
          Yii::app()->format->formatNumber($row1['qtystock']),
          Yii::app()->format->formatNumber($row1['selisih'])
        ));
      }
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    $this->menuname = 'formrequest';
    parent::actionDownxls();
    $sql = "select a.packinglistid,a.packinglistno,a.formrequestdate,b.sloccode,a.description
						from formrequest a
						inner join sloc b on b.slocid = a.slocid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.packinglistid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 3;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $line, 'No')
          ->setCellValueByColumnAndRow(1, $line, ': ' . $row['packinglistno']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $line, 'Date')
          ->setCellValueByColumnAndRow(1, $line, ': ' . $row['formrequestdate']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $line, 'Gudang')
          ->setCellValueByColumnAndRow(1, $line, ': ' . $row['sloccode']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $line, 'No')
          ->setCellValueByColumnAndRow(1, $line, 'Nama Barang')
          ->setCellValueByColumnAndRow(2, $line, 'Qty')
          ->setCellValueByColumnAndRow(3, $line, 'Satuan')
          ->setCellValueByColumnAndRow(4, $line, 'Buyprice')
          ->setCellValueByColumnAndRow(5, $line, 'Jumlah')
          ->setCellValueByColumnAndRow(6, $line, 'Rak')
          ->setCellValueByColumnAndRow(7, $line, 'Status')
          ->setCellValueByColumnAndRow(8, $line, 'Keterangan');
      $line++;
      $sql1        = "select b.productname,a.qty,c.uomcode,a.description,a.location,d.namamesin,a.expiredate,e.sloccode,f.description,a.buyprice,(a.qty*a.buyprice) as jumlah
							from formrequestraw a
							inner join product b on b.productid = a.productid
							inner join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
							inner join mesin d on d.mesinid = a.mesinid
							inner join sloc e on e.slocid = a.slocid
							inner join storagebin f on f.storagebinid = a.storagebinid
							where packinglistid = " . $row['packinglistid'] . " order by formrequestrawid";
      $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
      $i           = 0;
      foreach ($dataReader1 as $row1) {
        $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(0, $line, $i += 1)
            ->setCellValueByColumnAndRow(1, $line, $row1['productname'])
            ->setCellValueByColumnAndRow(2, $line, $row1['qty'])
            ->setCellValueByColumnAndRow(3, $line, $row1['uomcode'])
            ->setCellValueByColumnAndRow(4, $line, $row1['buyprice'])
            ->setCellValueByColumnAndRow(5, $line, $row1['jumlah'])
            ->setCellValueByColumnAndRow(6, $line, $row1['description'])
            ->setCellValueByColumnAndRow(7, $line, $row1['sloccode'])
            ->setCellValueByColumnAndRow(8, $line, $row1['description']);
        $line++;
      }
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Note : ')->setCellValueByColumnAndRow(1, $line, $row['description']);
      $line += 2;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Dibuat oleh, ')->setCellValueByColumnAndRow(1, $line, 'Diperiksa oleh, ')->setCellValueByColumnAndRow(2, $line, 'Diketahui oleh, ')->setCellValueByColumnAndRow(3, $line, 'Disetujui oleh, ');
      $line += 5;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, '........................')->setCellValueByColumnAndRow(1, $line, '........................')->setCellValueByColumnAndRow(2, $line, '........................')->setCellValueByColumnAndRow(3, $line, '........................');
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Admin')->setCellValueByColumnAndRow(1, $line, 'Supervisor')->setCellValueByColumnAndRow(2, $line, 'Chief Accounting')->setCellValueByColumnAndRow(3, $line, 'Manager Accounting');
      $line++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}
