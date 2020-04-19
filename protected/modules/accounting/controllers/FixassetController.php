<?php
class FixassetController extends Controller {
  public $menuname = 'fixasset';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
	public function actionIndexfahistory() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchfahistory();
    else
      $this->renderPartial('index', array());
  }
	public function actionIndexfajurnal() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchfajurnal();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $fixassetid   = isset($_POST['fixassetid']) ? $_POST['fixassetid'] : '';
    $poheaderid   = isset($_POST['poheaderid']) ? $_POST['poheaderid'] : '';
    $assetno   = isset($_POST['assetno']) ? $_POST['assetno'] : '';
    $productname    = isset($_POST['productname']) ? $_POST['productname'] : '';
    $qty          = isset($_POST['qty']) ? $_POST['qty'] : '';
    $price        = isset($_POST['price']) ? $_POST['price'] : '';
    $nilairesidu  = isset($_POST['nilairesidu']) ? $_POST['nilairesidu'] : '';
    $currencyid   = isset($_POST['currencyid']) ? $_POST['currencyid'] : '';
    $currencyrate = isset($_POST['currencyrate']) ? $_POST['currencyrate'] : '';
    $accakum      = isset($_POST['accakum']) ? $_POST['accakum'] : '';
    $accbiaya     = isset($_POST['accbiaya']) ? $_POST['accbiaya'] : '';
    $accperolehan = isset($_POST['accperolehan']) ? $_POST['accperolehan'] : '';
    $umur         = isset($_POST['umur']) ? $_POST['umur'] : '';
    $acckorpem    = isset($_POST['acckorpem']) ? $_POST['acckorpem'] : '';
    $metode       = isset($_POST['metode']) ? $_POST['metode'] : '';
    $plantcode       = isset($_POST['plantcode']) ? $_POST['plantcode'] : '';
    $fixassetid   = isset($_GET['q']) ? $_GET['q'] : $fixassetid;
    $poheaderid   = isset($_GET['q']) ? $_GET['q'] : $poheaderid;
    $assetno   = isset($_GET['q']) ? $_GET['q'] : $assetno;
    $productname    = isset($_GET['q']) ? $_GET['q'] : $productname;
    $qty          = isset($_GET['q']) ? $_GET['q'] : $qty;
    $price        = isset($_GET['q']) ? $_GET['q'] : $price;
    $nilairesidu  = isset($_GET['q']) ? $_GET['q'] : $nilairesidu;
    $currencyid   = isset($_GET['q']) ? $_GET['q'] : $currencyid;
    $currencyrate = isset($_GET['q']) ? $_GET['q'] : $currencyrate;
    $accakum      = isset($_GET['q']) ? $_GET['q'] : $accakum;
    $accbiaya     = isset($_GET['q']) ? $_GET['q'] : $accbiaya;
    $accperolehan = isset($_GET['q']) ? $_GET['q'] : $accperolehan;
    $umur         = isset($_GET['q']) ? $_GET['q'] : $umur;
    $acckorpem    = isset($_GET['q']) ? $_GET['q'] : $acckorpem;
    $metode       = isset($_GET['q']) ? $_GET['q'] : $metode;
    $plantcode       = isset($_GET['q']) ? $_GET['q'] : $plantcode;
    $page         = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows         = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort         = isset($_POST['sort']) ? strval($_POST['sort']) : 'fixassetid';
    $order        = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset       = ($page - 1) * $rows;
    $page         = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows         = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort         = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order        = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset       = ($page - 1) * $rows;
    $result       = array();
    $row          = array();
      $cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('fixasset t')
			->leftjoin('product a', 'a.productid=t.productid')
			->leftjoin('account b', 'b.accountid=t.accakum')
			->leftjoin('account c', 'c.accountid=t.accbiaya')
			->leftjoin('account d', 'd.accountid=t.accperolehan')
			->leftjoin('account e', 'e.accountid=t.acckorpem')
			->leftjoin('poheader f', 'f.poheaderid=t.poheaderid')
			->leftjoin('currency g', 'g.currencyid=t.currencyid')
			->leftjoin('unitofmeasure h', 'h.unitofmeasureid=t.uomid')
			->leftjoin('unitofmeasure i', 'i.unitofmeasureid=t.uomid')
			->leftjoin('famethod j', 'j.famethodid=t.famethodid')
			->leftjoin('materialtype k', 'k.materialtypeid = a.materialtypeid')
					->leftjoin('plant l', 'l.plantid = t.plantid')
			->where("(coalesce(a.productname,'') like :productname) and 
				(coalesce(b.accountname,'') like :accakum) and
				(coalesce(c.accountname,'') like :accbiaya) and 
				(coalesce(d.accountname,'') like :accperolehan) and 
				(coalesce(e.accountname,'') like :acckorpem) and
				(coalesce(t.fixassetid,'') like :fixassetid) and
				(coalesce(t.assetno,'') like :assetno) and
				(coalesce(l.plantcode,'') like :plantcode) and
				(coalesce(f.pono,'') like :poheaderid)", array(
        ':productname' => '%' . $productname . '%',
        ':accakum' => '%' . $accakum . '%',
        ':accbiaya' => '%' . $accbiaya . '%',
        ':accperolehan' => '%' . $accperolehan . '%',
        ':acckorpem' => '%' . $acckorpem . '%',
        ':fixassetid' => '%' . $fixassetid . '%',
        ':assetno' => '%' . $assetno . '%',
        ':plantcode' => '%' . $plantcode . '%',
        ':poheaderid' => '%' . $poheaderid . '%'
      ))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
		->select('t.*,k.materialtypecode,a.productname,b.accountname as accakumcode,c.accountname as accbiayacode,d.accountname as accperolehancode,e.accountname as acckorpemcode,f.pono,g.currencyname,h.uomcode,i.uomcode as uom2code,j.methodname')
		->from('fixasset t')
		->leftjoin('product a', 'a.productid=t.productid')
		->leftjoin('account b', 'b.accountid=t.accakum')
		->leftjoin('account c', 'c.accountid=t.accbiaya')
		->leftjoin('account d', 'd.accountid=t.accperolehan')
		->leftjoin('account e', 'e.accountid=t.acckorpem')
		->leftjoin('poheader f', 'f.poheaderid=t.poheaderid')
		->leftjoin('currency g', 'g.currencyid=t.currencyid')
		->leftjoin('unitofmeasure h', 'h.unitofmeasureid=t.uomid')
		->leftjoin('unitofmeasure i', 'i.unitofmeasureid=t.uomid')
		->leftjoin('famethod j', 'j.famethodid=t.famethodid')
		->leftjoin('materialtype k', 'k.materialtypeid = a.materialtypeid')
		->leftjoin('plant l', 'l.plantid = t.plantid')
		->where("(coalesce(a.productname,'') like :productname) and 
			(coalesce(b.accountname,'') like :accakum) and
			(coalesce(c.accountname,'') like :accbiaya) and 
			(coalesce(d.accountname,'') like :accperolehan) and 
			(coalesce(e.accountname,'') like :acckorpem) and
			(coalesce(e.accountname,'') like :acckorpem) and
			(coalesce(t.fixassetid,'') like :fixassetid) and
			(coalesce(t.assetno,'') like :assetno) and
			(coalesce(l.plantcode,'') like :plantcode) and
			(coalesce(f.pono,'') like :poheaderid)", array(
			':productname' => '%' . $productname . '%',
			':accakum' => '%' . $accakum . '%',
			':accbiaya' => '%' . $accbiaya . '%',
			':accperolehan' => '%' . $accperolehan . '%',
			':fixassetid' => '%' . $fixassetid . '%',
			':acckorpem' => '%' . $acckorpem . '%',
			':assetno' => '%' . $assetno . '%',
			':plantcode' => '%' . $plantcode . '%',
			':poheaderid' => '%' . $poheaderid . '%'
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'fixassetid' => $data['fixassetid'],
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'assetno' => $data['assetno'],
				'materialtypecode' => $data['materialtypecode'],
				'buydate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['buydate'])),
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'description' => $data['description'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'qty2' => Yii::app()->format->formatNumber($data['qty2']),
        'ratesusut' => Yii::app()->format->formatNumber($data['ratesusut']),
				'uomid' => $data['uomid'],
				'uomcode' => $data['uomcode'],
				'uom2id' => $data['uom2id'],
				'uom2code' => $data['uom2code'],
        'price' => Yii::app()->format->formatNumber($data['price']),
        'nilairesidu' => Yii::app()->format->formatNumber($data['nilairesidu']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'accakum' => $data['accakum'],
        'accakumcode' => $data['accakumcode'],
        'accbiaya' => $data['accbiaya'],
        'accbiayacode' => $data['accbiayacode'],
        'accperolehan' => $data['accperolehan'],
        'accperolehancode' => $data['accperolehancode'],
        'umur' => $data['umur'],
        'acckorpem' => $data['acckorpem'],
        'acckorpemcode' => $data['acckorpemcode'],
        'famethodid' => $data['famethodid'],
        'methodname' => $data['methodname'],
        'recordstatus' => $data['recordstatus']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	public function actionSearchFahistory() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','fahistoryid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('fahistory t')
					->leftjoin('product a', 'a.productid = t.productid')
					->where('t.fixassetid = :fixassetid',
					array(
						':fixassetid' => $id
					))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
					->select('t.*,a.productname')
					->from('fahistory t')
					->leftjoin('product a', 'a.productid = t.productid')
					->where('t.fixassetid = :fixassetid', array(
		':fixassetid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'fahistoryid' => $data['fahistoryid'],
        'fixassetid' => $data['fixassetid'],
        'productid' => $data['productid'],
				'productname' => $data['productname'],
				'bulanke' => $data['bulanke'],
				'susutdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['susutdate'])),
				'nilai' =>  Yii::app()->format->formatNumber($data['nilai']),
				'beban' =>  Yii::app()->format->formatNumber($data['beban']),
				'nilaiakum' =>  Yii::app()->format->formatNumber($data['nilaiakum']),
				'nilaibuku' =>  Yii::app()->format->formatNumber($data['nilaibuku'])
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
	public function actionSearchFajurnal() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page = GetSearchText(array('GET'),'page',1,'int');
		$rows = GetSearchText(array('GET'),'rows',10,'int');
		$sort = GetSearchText(array('GET'),'sort','fajurnalid','int');
		$order = GetSearchText(array('GET'),'order','desc','int');
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('fajurnal t')
					->leftjoin('account a', 'a.accountid = t.accountid')
					->leftjoin('currency b', 'b.currencyid = t.currencyid')
					->where('t.fixassetid = :fixassetid',
					array(
						':fixassetid' => $id
					))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
					->select('t.*,a.accountname,b.currencyname')
					->from('fajurnal t')
					->leftjoin('account a', 'a.accountid = t.accountid')
					->leftjoin('currency b', 'b.currencyid = t.currencyid')
					->where('t.fixassetid = :fixassetid', array(
		':fixassetid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'fajurnalid' => $data['fajurnalid'],
        'fixassetid' => $data['fixassetid'],
        'accountid' => $data['accountid'],
				'accountname' => $data['accountname'],
				'currencyid' => $data['currencyid'],
				'currencyname' => $data['currencyname'],
				'debet' =>  Yii::app()->format->formatNumber($data['debet']),
				'credit' =>  Yii::app()->format->formatNumber($data['credit']),
				'currencyrate' =>  Yii::app()->format->formatNumber($data['currencyrate'])
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
  public function actionSave() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertfixasset(:vplantid,:vbuydate,:vpoheaderid,:vproductid,:vdescription,:vqty,:vuomid,:vqty2,:vuom2id,:vprice,:vnilairesidu,:vcurrencyid,:vcurrencyrate,
					:vaccakum,:vaccbiaya,:vaccperolehan,:vumur,:vacckorpem,:vfamethodid,:vratesusut,:vrecordstatus,:vdatauser)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatefixasset(:vid,:vplantid,:vbuydate,:vpoheaderid,:vproductid,:vdescription,:vqty,:vuomid,:vqty2,:vuom2id,:vprice,:vnilairesidu,:vcurrencyid,
					:vcurrencyrate,:vaccakum,:vaccbiaya,:vaccperolehan,:vumur,:vacckorpem,:vfamethodid,:vratesusut,:vrecordstatus,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['fixassetid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['fixassetid']);
      }
      $command->bindvalue(':vplantid', $_POST['plantid'], PDO::PARAM_STR);
      $command->bindvalue(':vbuydate', date(Yii::app()->params['datetodb'], strtotime($_POST['buydate'])), PDO::PARAM_STR);
      $command->bindvalue(':vpoheaderid', $_POST['poheaderid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vdescription', $_POST['description'], PDO::PARAM_STR);
      $command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
      $command->bindvalue(':vqty2', $_POST['qty2'], PDO::PARAM_STR);
      $command->bindvalue(':vuom2id', $_POST['uom2id'], PDO::PARAM_STR);
      $command->bindvalue(':vprice', $_POST['price'], PDO::PARAM_STR);
      $command->bindvalue(':vnilairesidu', $_POST['nilairesidu'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyid', $_POST['currencyid'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyrate', $_POST['currencyrate'], PDO::PARAM_STR);
      $command->bindvalue(':vaccakum', $_POST['accakum'], PDO::PARAM_STR);
      $command->bindvalue(':vaccbiaya', $_POST['accbiaya'], PDO::PARAM_STR);
      $command->bindvalue(':vaccperolehan', $_POST['accperolehan'], PDO::PARAM_STR);
      $command->bindvalue(':vumur', $_POST['umur'], PDO::PARAM_STR);
      $command->bindvalue(':vacckorpem', $_POST['acckorpem'], PDO::PARAM_STR);
      $command->bindvalue(':vfamethodid', $_POST['famethodid'], PDO::PARAM_STR);
      $command->bindvalue(':vratesusut', $_POST['ratesusut'], PDO::PARAM_STR);
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
        $sql     = 'call Purgefixasset(:vid,:vdatauser)';
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
    $id          = $_POST['id'];
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      foreach ($id as $ids) {
        $sql     = 'call ApproveFixasset(:vid, :vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindValue(':vid', $ids, PDO::PARAM_INT);
        $command->bindValue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
        $command->execute();
      }
      $transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select a.fixassetid, a.plantcode, a.assetno, a.buydate, a.description, a.qty, a.qty2, a.price, 
						a.nilairesidu, a.currencyrate, a.umur,b.productname,c.currencyname,c.symbol,d.accountcode,d.accountname,
						e.accountcode as accbiayacode,e.accountname as accbiaya ,f.accountcode as accperolehancode,f.accountname as accperolehan,
						g.accountcode as acckorpemcode,g.accountname as acckorpem,
						h.methodname,i.uomcode,j.uomcode as uom2code
						from fixasset a
						left join product b on b.productid = a.productid
						left join currency c on c.currencyid = a.currencyid
						left join account d on d.accountid = a.accakum
						left join account e on e.accountid = a.accbiaya
						left join account f on f.accountid = a.accperolehan
						left join account g on g.accountid = a.acckorpem
						left join famethod h on h.famethodid = a.famethodid
						left join unitofmeasure i on i.unitofmeasureid = a.uomid
						left join unitofmeasure j on j.unitofmeasureid = a.uom2id";
    if ($_GET['id'] !== '') {
      $sql = $sql . " where a.fixassetid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = getCatalog('fixasset');
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNBPages();
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->text(15, $this->pdf->gety(), 'No ');
      $this->pdf->text(30, $this->pdf->gety(), ': ' . $row['assetno']);
      $this->pdf->text(15, $this->pdf->gety()+5, 'Tgl Beli');
      $this->pdf->text(30, $this->pdf->gety()+5, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['buydate'])));
      $this->pdf->text(15, $this->pdf->gety()+10, 'Artikel ');
      $this->pdf->text(30, $this->pdf->gety()+10, ': ' . $row['productname']);
			$this->pdf->text(15, $this->pdf->gety()+15, 'Uraian ');
      $this->pdf->text(30, $this->pdf->gety()+15, ': ' . $row['description']);
			$this->pdf->text(15, $this->pdf->gety()+20, 'QTY ');
      $this->pdf->text(30, $this->pdf->gety()+20, ': ' . Yii::app()->format->formatNumber($row['qty']).'  '.$row['uomcode']);
			$this->pdf->text(15, $this->pdf->gety()+25, 'QTY 2 ');
      $this->pdf->text(30, $this->pdf->gety()+25, ': ' . Yii::app()->format->formatNumber($row['qty2']).'  '.$row['uom2code']);
			$this->pdf->text(15, $this->pdf->gety()+30, 'Nominal ');
      $this->pdf->text(30, $this->pdf->gety()+30, ': ' . Yii::app()->format->formatNumber($row['qty2']));
			$this->pdf->text(15, $this->pdf->gety()+35, 'RESIDU ');
      $this->pdf->text(30, $this->pdf->gety()+35, ': ' . Yii::app()->format->formatNumber($row['nilairesidu']));
			
			$this->pdf->text(90, $this->pdf->gety(), 'Umur Aset');
			$this->pdf->text(115, $this->pdf->gety(), ': ' . $row['umur']);
			$this->pdf->text(90, $this->pdf->gety()+5, 'Metode ');
			$this->pdf->text(115, $this->pdf->gety()+5, ': ' . $row['methodname']);
			$this->pdf->text(90, $this->pdf->gety()+30, 'Kurs ');
			$this->pdf->text(115, $this->pdf->gety()+30, ': ' . $row['currencyrate']);
			$this->pdf->text(90, $this->pdf->gety()+35, 'Mata Uang ');
			$this->pdf->text(115, $this->pdf->gety()+35, ': ' . $row['currencyname']);
		
      $i           = 0;
      $totalqty    = 0;
			$totalqty2    = 0;
			$totalqty3    = 0;
			$totalqty4    = 0;
      $totaljumlah = 0;
			//$this->pdf->title = 'tes judul';
      
			$sql1        = "select a.bulanke, a.susutdate, a.nilai, a.beban, a.nilaiakum, a.nilaibuku, a.productid
											from fahistory a
											where a.fixassetid = " . $row['fixassetid'] . " order by bulanke ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
			$this->pdf->text(10,$this->pdf->gety()+43,'HISTORY');
      $this->pdf->sety($this->pdf->gety() + 45);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setFont('Arial', 'B', 7);
      $this->pdf->setwidths(array(
        7,
        25,
        30,
				30,
				30,
				30,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Bulan',
        'Tgl Susut',
        'Nilai',
				'Nilai Beban',
				'Nilai Akumulasi',
				'Nilai Buku'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->coldetailalign = array(
        'L',
        'C',
        'L',
        'R',
        'R',
        'R',
        'R'
      );
      foreach ($dataReader1 as $row1) {
          $i = $i + 1;
          $this->pdf->row(array(
            $i,
           $row1['bulanke'],
					 date(Yii::app()->params['dateviewfromdb'], strtotime($row1['susutdate'])),
            Yii::app()->format->formatNumber($row1['nilai']),
			Yii::app()->format->formatNumber($row1['beban']),
			Yii::app()->format->formatNumber($row1['nilaiakum']),
			Yii::app()->format->formatNumber($row1['nilaibuku'])
          ));
          $totalqty += $row1['nilai'];
		  $totalqty2 += $row1['beban'];
		  $totalqty3 += $row1['nilaiakum'];
		  $totalqty4 += $row1['nilaibuku'];
      }
			
			$sql1        = "select a.debet, a.credit, a.currencyrate, a.susutdate as susut, b.accountname, c.currencyname
											from fajurnal a
											left join account b on b.accountid = a.accountid
											left join currency c on c.currencyid = a.currencyid
											where a.fixassetid = " . $row['fixassetid'] . " order by susutdate ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->text(10,$this->pdf->gety()+43,'JURNAL');
      $this->pdf->sety($this->pdf->gety() + 45);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
				'C',
				'C',
        'C'
      );
      $this->pdf->setFont('Arial', 'B', 7);
      $this->pdf->setwidths(array(
        7,
        45,
        20,
        22,
        25,
        30,
        20
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Akun',
        'Debet',
        'Credit',
        'Tgl Susut',
        'Mata Uang',
        'Kurs'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'C',
        'R',
        'C',
        'L',
        'L'
      );
      foreach ($dataReader1 as $row1) {
          $i = $i + 1;
          $this->pdf->row(array(
            $i,
           $row1['accountname'],
            Yii::app()->format->formatNumber($row1['debet']),
            Yii::app()->format->formatNumber($row1['credit']),
						date(Yii::app()->params['dateviewfromdb'], strtotime($row1['susut'])),
            $row1['currencyname'],
            $row1['currencyrate']
          ));
          $totalqty += $row1['debet'];
          $totalqty += $row1['credit'];
      }
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->setFont('Arial', 'B', 7);
      $this->pdf->coldetailalign = array(
        'L',
        'R',
        'R',
        'R',
        'R',
        'C',
        'L'
       
      );
      $this->pdf->row(array(
        '',
        'TOTAL',
        Yii::app()->format->formatNumber($totalqty),
		Yii::app()->format->formatNumber($totalqty2),
		Yii::app()->format->formatNumber($totalqty3),
		Yii::app()->format->formatNumber($totalqty4),
        ''
        
      ));
			 $this->pdf->sety($this->pdf->gety()+10);
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        50,
        140
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Note',
        $row['description']
      ));
			 $this->pdf->setFont('Arial', '', 7);
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        50,
        140
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->row(array(
        'AKUN'.' - '.$row['accountcode'],
        $row['accountname']
      ));
			$this->pdf->row(array(
        'AKUN'.' - '.$row['accbiayacode'],
        $row['accbiaya']
      ));
			$this->pdf->row(array(
        'AKUN'.' - '.$row['accperolehancode'],
        $row['accperolehan']
      ));
			$this->pdf->row(array(
        'AKUN'.' - '.$row['acckorpemcode'],
        $row['acckorpem']
      ));
      $this->pdf->setFont('Arial', '', 8);
			$this->pdf->CheckNewPage(30);
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select poheaderid,productid,qty,price,nilairesidu,currencyid,currencyrate,accakum,accbiaya,accperolehan,umur,acckorpem,metode,recordstatus
				from fixasset a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.fixassetid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('poheaderid'))->setCellValueByColumnAndRow(1, 1, GetCatalog('productid'))->setCellValueByColumnAndRow(2, 1, GetCatalog('qty'))->setCellValueByColumnAndRow(3, 1, GetCatalog('price'))->setCellValueByColumnAndRow(4, 1, GetCatalog('nilairesidu'))->setCellValueByColumnAndRow(5, 1, GetCatalog('currencyid'))->setCellValueByColumnAndRow(6, 1, GetCatalog('currencyrate'))->setCellValueByColumnAndRow(7, 1, GetCatalog('accakum'))->setCellValueByColumnAndRow(8, 1, GetCatalog('accbiaya'))->setCellValueByColumnAndRow(9, 1, GetCatalog('accperolehan'))->setCellValueByColumnAndRow(10, 1, GetCatalog('umur'))->setCellValueByColumnAndRow(11, 1, GetCatalog('acckorpem'))->setCellValueByColumnAndRow(12, 1, GetCatalog('metode'))->setCellValueByColumnAndRow(13, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['poheaderid'])->setCellValueByColumnAndRow(1, $i + 1, $row1['productid'])->setCellValueByColumnAndRow(2, $i + 1, $row1['qty'])->setCellValueByColumnAndRow(3, $i + 1, $row1['price'])->setCellValueByColumnAndRow(4, $i + 1, $row1['nilairesidu'])->setCellValueByColumnAndRow(5, $i + 1, $row1['currencyid'])->setCellValueByColumnAndRow(6, $i + 1, $row1['currencyrate'])->setCellValueByColumnAndRow(7, $i + 1, $row1['accakum'])->setCellValueByColumnAndRow(8, $i + 1, $row1['accbiaya'])->setCellValueByColumnAndRow(9, $i + 1, $row1['accperolehan'])->setCellValueByColumnAndRow(10, $i + 1, $row1['umur'])->setCellValueByColumnAndRow(11, $i + 1, $row1['acckorpem'])->setCellValueByColumnAndRow(12, $i + 1, $row1['metode'])->setCellValueByColumnAndRow(13, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="fixasset.xlsx"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $objWriter->save('php://output');
    unset($excel);
  }
}