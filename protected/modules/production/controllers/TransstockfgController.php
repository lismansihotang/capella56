<?php
class TransstockfgController extends Controller {
  public $menuname = 'transstockfg';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail() {
    if (isset($_GET['grid']))
      echo $this->actionsearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
		$id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'transstockid' => $id,
		));
  }
	public function actionGeneratedetail() {
    if (isset($_POST['id'])) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateOPTS(:vid, :vhid, :vdatauser)';
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
    Yii::app()->end();
  }
  public function search() {
    header('Content-Type: application/json');
    $transstockid 		= GetSearchText(array('POST','Q'),'transstockid','','int');
    $plantid 		= GetSearchText(array('POST','GET'),'plantid',0,'int');
    $plantcode 		= GetSearchText(array('POST','Q'),'plantcode');
    $productcode 		= GetSearchText(array('POST','Q'),'productcode');
    $productname 		= GetSearchText(array('POST','Q'),'productname');
    $slocfromid 		= GetSearchText(array('POST','GET'),'slocfromid',0,'int');
    $slocfromcode 		= GetSearchText(array('POST','Q'),'slocfromcode');
    $addressbookid 		= GetSearchText(array('POST','GET'),'addressbookid',0,'int');
    $productoutputid 		= GetSearchText(array('POST','GET'),'productoutputid',0,'int');
    $sloctoid 		= GetSearchText(array('POST','GET'),'sloctoid',0,'int');
    $sloctocode 		= GetSearchText(array('POST','Q'),'sloctocode');
    $transstockdate 		= GetSearchText(array('POST','Q'),'transstockdate');
    $transstockno 		= GetSearchText(array('POST','Q'),'transstockno');
    $headernote 		= GetSearchText(array('POST','Q'),'headernote');
    $sono 		= GetSearchText(array('POST','Q'),'sono');
    $productplanno 		= GetSearchText(array('POST','Q'),'productplanno');
    $productoutputno 		= GetSearchText(array('POST','Q'),'productoutputno');
    $productoutputno 		= GetSearchText(array('POST','Q'),'productoutputno');
    $recordstatus 		= GetSearchText(array('POST','Q'),'recordstatus');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','transstockid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
    if (!isset($_GET['getdata'])) {
			if (isset($_GET['frts'])) {
					$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
						->from('transstock t')
						->leftjoin('sloc a', 'a.slocid = t.slocfromid')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('sloc d', 'd.slocid = t.sloctoid')
						->leftjoin('productoutput e', 'e.productoutputid = t.productoutputid')
						->leftjoin('productplan f', 'f.productplanid = e.productplanid')
						->leftjoin('soheader g', 'g.soheaderid = f.soheaderid')
						->leftjoin('addressbook h', 'h.addressbookid = f.addressbookid')
						->where("
						((coalesce(transstockid,'') like :transstockid) 
						or (coalesce(b.plantcode,'') like :plantcode) 
						or (coalesce(d.sloccode,'') like :sloccode) 
						or (coalesce(t.transstockno,'') like :transstockno) 
						or (coalesce(t.headernote,'') like :headernote)  
						or (coalesce(f.productplanno,'') like :productplanno)  
						or (coalesce(g.sono,'') like :sono)  
						or (coalesce(t.transstockdate,'') like :transstockdate)) 
						and t.transstockno is not null 
						and t.recordstatus = getwfmaxstatbywfname('appts')
						and t.slocfromid in (".getUserObjectValues('sloc').")
						and t.plantid = ".$plantid." 
						and t.transstockid in (select z.transstockid 
							from transstockdet z 
							where z.transstockid = t.transstockid)
						",
					array(
						':transstockid' => '%' . $transstockid . '%',
						':plantcode' => '%' . $plantcode . '%',
						':sloccode' => '%' . $sloccode . '%',
						':transstockno' => '%' . $transstockno . '%',
						':headernote' => '%' . $headernote . '%',
						':sono' => '%' . $sono . '%',
						':productplanno' => '%' . $productplanno . '%',
						':productoutputno' => '%' . $productoutputno . '%',
						':transstockdate' => '%' . $transstockdate . '%'
					))->queryScalar();
				
			} else 
			if (isset($_GET['combo'])) {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('transstock t')
					->leftjoin('sloc a', 'a.slocid = t.slocfromid')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('sloc d', 'd.slocid = t.sloctoid')
						->leftjoin('productoutput e', 'e.productoutputid = t.productoutputid')
						->leftjoin('productplan f', 'f.productplanid = e.productplanid')
						->leftjoin('soheader g', 'g.soheaderid = f.soheaderid')
						->leftjoin('addressbook h', 'h.addressbookid = f.addressbookid')
					->where("
					((coalesce(transstockid,'') like :transstockid) 
					or (coalesce(b.plantcode,'') like :plantcode) 
					or (coalesce(d.sloccode,'') like :sloccode) 
					or (coalesce(transstockno,'') like :transstockno) 
					or (coalesce(t.headernote,'') like :headernote) 
					or (coalesce(g.sono,'') like :sono) 
					or (coalesce(f.productplanno,'') like :productplanno) 
					or (coalesce(e.productoutputno,'') like :productoutputno) 
					or (coalesce(t.transstockdate,'') like :transstockdate)) 
					and t.transstockno is not null 
					and t.recordstatus = getwfmaxstatbywfname('appts')
					and t.slocfromid in (".getUserObjectValues('sloc').")",
				array(
					':transstockid' => '%' . $transstockid . '%',
					':plantcode' => '%' . $plantid . '%',
					':sloccode' => '%' . $slocfromid . '%',
					':transstockno' => '%' . $transstockno . '%',
					':headernote' => '%' . $headernote . '%',
					':sono' => '%' . $sono . '%',
					':productplanno' => '%' . $productplanno . '%',
					':productoutputno' => '%' . $productoutputno . '%',
					':transstockdate' => '%' . $transstockdate . '%'
				))->queryScalar();
			} else {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('transstock t')
					->leftjoin('sloc a', 'a.slocid = t.slocfromid')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('sloc d', 'd.slocid = t.sloctoid')
						->leftjoin('productoutput e', 'e.productoutputid = t.productoutputid')
						->leftjoin('productplan f', 'f.productplanid = e.productplanid')
						->leftjoin('soheader g', 'g.soheaderid = f.soheaderid')
						->leftjoin('addressbook h', 'h.addressbookid = f.addressbookid')
					->where("
					((coalesce(transstockid,'') like :transstockid) 
					and (coalesce(b.plantcode,'') like :plantcode) 
					and (coalesce(d.sloccode,'') like :sloccode) 
					and (coalesce(transstockno,'') like :transstockno) 
					and (coalesce(g.sono,'') like :sono) 
					and (coalesce(t.headernote,'') like :headernote) 
					and (coalesce(f.productplanno,'') like :productplanno) 
					and (coalesce(e.productoutputno,'') like :productoutputno) 
					and (coalesce(t.transstockdate,'') like :transstockdate))
					and t.recordstatus in (".getUserRecordStatus('listts').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":''). 
					" and t.transstocktypeid = 1
					and t.slocfromid in (".getUserObjectValues('sloc').")".
					(($productname != '%%')?"
						and t.transstockid in (
							select distinct transstockid 
							from transstockdet z
							join product zz on zz.productid = z.productid 
							where zz.productname like '%".$productname."%' 						
						)					
					":'')
					,
				array(
					':transstockid' => '%' . $transstockid . '%',
					':plantcode' => '%' . $plantcode . '%',
					':sloccode' => '%' . $sloctocode . '%',
					':transstockno' => '%' . $transstockno . '%',
					':sono' => '%' . $sono . '%',
					':productplanno' => '%' . $productplanno . '%',
					':headernote' => '%' . $headernote . '%',
					':productoutputno' => '%' . $productoutputno . '%',
					':transstockdate' => '%' . $transstockdate . '%'
				))->queryScalar();
			}
			$result['total'] = $cmd;
			if (isset($_GET['fpp'])) {
					$cmd = Yii::app()->db->createCommand()->select('t.*,a.sloccode,b.plantcode,a.description as slocdesc,t.headernote,b.companyid,c.companyname,e.productoutputno,g.sono,h.fullname,f.addressbookid')
						->from('transstock t')
						->leftjoin('sloc a', 'a.slocid = t.slocfromid')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('sloc d', 'd.slocid = t.sloctoid')
						->leftjoin('productoutput e', 'e.productoutputid = t.productoutputid')
						->leftjoin('productplan f', 'f.productplanid = e.productplanid')
						->leftjoin('soheader g', 'g.soheaderid = f.soheaderid')
						->leftjoin('addressbook h', 'h.addressbookid = f.addressbookid')
						->where("
						((coalesce(transstockid,'') like :transstockid) 
						or (coalesce(b.plantcode,'') like :plantcode) 
						or (coalesce(d.sloccode,'') like :sloccode) 
						or (coalesce(transstockno,'') like :transstockno) 
						or (coalesce(g.sono,'') like :sono) 
						or (coalesce(t.headernote,'') like :headernote) 
						or (coalesce(f.productplanno,'') like :productplanno) 
						or (coalesce(e.productoutputno,'') like :productoutputno) 
						or (coalesce(t.transstockdate,'') like :transstockdate)) 
						and t.transstockno is not null 
						and t.recordstatus = getwfmaxstatbywfname('appts')
						and t.slocfromid in (".getUserObjectValues('sloc').")
						and t.plantid = ".$plantid."
						and t.transstockid in (select z.transstockid 
							from transstockdet z 
							where z.transstockid = t.transstockid)
						",
					array(
						':transstockid' => '%' . $transstockid . '%',
						':plantcode' => '%' . $plantcode . '%',
						':sloccode' => '%' . $sloctocode . '%',
						':transstockno' => '%' . $transstockno . '%',
						':headernote' => '%' . $headernote . '%',
						':sono' => '%' . $sono . '%',
						':productplanno' => '%' . $productplanno . '%',
						':productoutputno' => '%' . $productoutputno . '%',
						':transstockdate' => '%' . $transstockdate . '%'
					))->queryAll();
				} else 
			if (isset($_GET['combo'])) {
				$cmd = Yii::app()->db->createCommand()->select('t.*,a.sloccode,b.plantcode,a.sloccode as slocfromcode,d.sloccode as sloctocode,t.headernote,b.companyid,c.companyname,
				e.productoutputno,g.sono,h.fullname,f.addressbookid')
					->from('transstock t')
					->leftjoin('sloc a', 'a.slocid = t.slocfromid')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('sloc d', 'd.slocid = t.sloctoid')
						->leftjoin('productoutput e', 'e.productoutputid = t.productoutputid')
						->leftjoin('productplan f', 'f.productplanid = e.productplanid')
						->leftjoin('soheader g', 'g.soheaderid = f.soheaderid')
						->leftjoin('addressbook h', 'h.addressbookid = f.addressbookid')
					->where("
					((coalesce(transstockid,'') like :transstockid) 
					or (coalesce(b.plantcode,'') like :plantcode) 
					or (coalesce(d.sloccode,'') like :sloccode) 
					or (coalesce(transstockno,'') like :transstockno) 
					or (coalesce(f.productplanno,'') like :productplanno) 
					or (coalesce(t.headernote,'') like :headernote) 
					or (coalesce(g.sono,'') like :sono) 
					or (coalesce(e.productoutputno,'') like :productoutputno) 
					or (coalesce(t.transstockdate,'') like :transstockdate)) 
					and t.transstockno is not null 
					and t.recordstatus = getwfmaxstatbywfname('appts')
					and t.slocfromid in (".getUserObjectValues('sloc').")",
				array(
					':transstockid' => '%' . $transstockid . '%',
					':plantcode' => '%' . $plantid . '%',
					':sloccode' => '%' . $slocfromid . '%',
					':transstockno' => '%' . $transstockno . '%',
					':sono' => '%' . $sono . '%',
					':productplanno' => '%' . $productplanno . '%',
					':headernote' => '%' . $headernote . '%',
					':productoutputno' => '%' . $productoutputno . '%',
					':transstockdate' => '%' . $transstockdate . '%'
				))->queryAll();
			} else {
				$cmd = Yii::app()->db->createCommand()->select('t.*,a.sloccode,b.plantcode,a.sloccode as slocfromcode,d.sloccode as sloctocode,t.headernote,b.companyid,c.companyname,
					e.productoutputno,g.sono,h.fullname,f.addressbookid,f.productplanno')
					->from('transstock t')
					->leftjoin('sloc a', 'a.slocid = t.slocfromid')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('sloc d', 'd.slocid = t.sloctoid')
						->leftjoin('productoutput e', 'e.productoutputid = t.productoutputid')
						->leftjoin('productplan f', 'f.productplanid = e.productplanid')
						->leftjoin('soheader g', 'g.soheaderid = f.soheaderid')
						->leftjoin('addressbook h', 'h.addressbookid = f.addressbookid')
					->where("
					((coalesce(transstockid,'') like :transstockid) 
					and (coalesce(b.plantcode,'') like :plantcode) 
					and (coalesce(d.sloccode,'') like :sloccode) 
					and (coalesce(transstockno,'') like :transstockno) 
					and (coalesce(g.sono,'') like :sono) 
					and (coalesce(f.productplanno,'') like :productplanno) 
					and (coalesce(t.headernote,'') like :headernote) 
					and (coalesce(e.productoutputno,'') like :productoutputno) 
					and (coalesce(t.transstockdate,'') like :transstockdate))
					and t.recordstatus in (".getUserRecordStatus('listts').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":''). 
					" and t.transstocktypeid = 1
					and t.slocfromid in (".getUserObjectValues('sloc').")".
					(($productname != '')?"
						and t.transstockid in (
							select distinct transstockid
							from transstockdet z
							join product zz on zz.productid = z.productid 
							where zz.productname like '%".$productname."%' 						
						)					
					":'')
					,
				array(
					':transstockid' => '%' . $transstockid . '%',
					':plantcode' => '%' . $plantcode . '%',
					':sloccode' => '%' . $sloctocode . '%',
					':transstockno' => '%' . $transstockno . '%',
					':headernote' => '%' . $headernote . '%',
					':sono' => '%' . $sono . '%',
					':productplanno' => '%' . $productplanno . '%',
					':productoutputno' => '%' . $productoutputno . '%',
					':transstockdate' => '%' . $transstockdate . '%'
					))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
			}
			foreach ($cmd as $data) {
				$row[] = array(
					'transstockid' => $data['transstockid'],
					'transstockdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['transstockdate'])),
					'transstockno' => $data['transstockno'],
					'plantid' => $data['plantid'],
					'plantcode' => $data['plantcode'],
					'companyid' => $data['companyid'],
					'companyname' => $data['companyname'],
					'productoutputid' => $data['productoutputid'],
					'productoutputno' => $data['productoutputno'],
					'fullname' => $data['fullname'],
					'productplanno' => $data['productplanno'],
					'sono' => $data['sono'],
					'slocfromid' => $data['slocfromid'],
					'slocfromcode' => $data['slocfromcode'],
					'sloctoid' => $data['sloctoid'],
					'sloctocode' => $data['sloctocode'],
					'headernote' => $data['headernote'],
					'recordstatus' => $data['recordstatus'],
					'recordstatusname' => $data['statusname']
				);
			}
			$result = array_merge($result, array(
				'rows' => $row
			));
		} else {
			$cmd = Yii::app()->db->createCommand("
				select a.productoutputid, a.slocfromid, b.productplanno, c.sono, d.fullname
				from productoutput a
				left join productplan b on b.productplanid = a.productplanid
				left join soheader c on c.soheaderid = b.soheaderid
				left join addressbook d on d.addressbookid = c.addressbookid
				where a.productoutputid = ".$productoutputid)->queryRow();
			$result = $cmd;
		}
    return CJSON::encode($result);
	}
  public function actionSearchdetail() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'transstockdetid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('transstockdet t')
					->leftjoin('transstock m', 'm.transstockid = t.transstockid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('storagebin b', 'b.storagebinid = t.storagebinfromid')
					->leftjoin('storagebin d', 'd.storagebinid = t.storagebintoid')
					->leftjoin('mesin e', 'e.mesinid = t.mesinid')
					->leftjoin('productoutputfg f', 'f.productoutputfgid = t.productoutputfgid')
					->leftjoin('materialtype g', 'g.materialtypeid = a.materialtypeid')
					->where('t.transstockid = :transstockid',
					array(
				':transstockid' => $id
				))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productcode,a.productname,g.materialtypecode,b.description as rakasal,d.description as raktujuan,e.namamesin,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom4id) as uom4code,
						GetStdQty2(a.productid) as stdqty2,
						GetStdQty3(a.productid) as stdqty3,
						GetStdQty4(a.productid) as stdqty4,
						GetStock(a.productid,t.uomid,m.sloctoid) as qtystock
						')
					->from('transstockdet t')
					->leftjoin('transstock m', 'm.transstockid = t.transstockid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('storagebin b', 'b.storagebinid = t.storagebinfromid')
					->leftjoin('storagebin d', 'd.storagebinid = t.storagebintoid')
					->leftjoin('mesin e', 'e.mesinid = t.mesinid')
					->leftjoin('productoutputfg f', 'f.productoutputfgid = t.productoutputfgid')
					->leftjoin('materialtype g', 'g.materialtypeid = a.materialtypeid')					
					->where('t.transstockid = :transstockid', array(
		':transstockid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
			if ($data['qtystock'] >= $data['qty']) {
				$stockcount = 0;
			} else {
				$stockcount = 1;
			}
      $row[] = array(
        'transstockdetid' => $data['transstockdetid'],
				'transstockid' => $data['transstockid'],
				'productoutputfgid' => $data['productoutputfgid'],
        'productid' => $data['productid'],
				'productcode' => $data['productcode'],
				'productname' => $data['productname'],
				'materialtypecode' => $data['materialtypecode'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
				'qty2' => Yii::app()->format->formatNumber($data['qty2']),
				'qty3' => Yii::app()->format->formatNumber($data['qty3']),
				'qty4' => Yii::app()->format->formatNumber($data['qty4']),
				'stdqty2' => Yii::app()->format->formatNumber($data['stdqty2']),
				'stdqty3' => Yii::app()->format->formatNumber($data['stdqty3']),
				'stdqty4' => Yii::app()->format->formatNumber($data['stdqty4']),
				'qtystock' => Yii::app()->format->formatNumber($data['qtystock']),
        'uomid' => $data['uomid'],
				'stockcount' => $stockcount,
				'uom2id' => $data['uom2id'],
				'uom3id' => $data['uom3id'],
				'uom4id' => $data['uom4id'],
        'uomcode' => $data['uomcode'],
				'uom2code' => $data['uom2code'],
				'uom3code' => $data['uom3code'],
				'uom4code' => $data['uom4code'],
				'mesinid' => $data['mesinid'],
				'namamesin' => $data['namamesin'],
				'storagebintoid' => $data['storagebintoid'],
				'raktujuan' => $data['raktujuan'],
				'storagebinfromid' => $data['storagebinfromid'],
				'rakasal' => $data['rakasal'],
				'lotno' => $data['lotno'],
				'itemnote' => $data['itemnote'],
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  private function ModifyData($connection,$arraydata) {
		$id = (int)$arraydata[0];
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$sql = 'call ModifTransstockfg(:vid,:vtransstockdate,:vplantid,:vproductoutputid,:vslocfromid,:vsloctoid,:vheadernote,:vdatauser)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vtransstockdate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vproductoutputid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vslocfromid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vsloctoid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();			
	}
	
	public function actionSave() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['transstockfg-transstockid'])?$_POST['transstockfg-transstockid']:''),
			date(Yii::app()->params['datetodb'], strtotime($_POST['transstockfg-transstockdate'])),
			$_POST['transstockfg-plantid'],$_POST['transstockfg-productoutputid'],$_POST['transstockfg-slocfromid'],
			$_POST['transstockfg-sloctoid'],$_POST['transstockfg-headernote']));
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
        $sql     = 'call Inserttransstockdet(:vtransstockid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,:vqty4,:vstoragebinfromid,:vmesinid,:vlotno,
					:vitemnote,:vdatauser)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatetransstockdet(:vid,:vtransstockid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,:vqty4,:vstoragebinfromid,:vmesinid,:vlotno,
					:vitemnote,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['transstockdetid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['transstockdetid']);
      }
			$command->bindvalue(':vtransstockid', $_POST['transstockid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
      $command->bindvalue(':vuom2id', $_POST['uom2id'], PDO::PARAM_STR);
			$command->bindvalue(':vuom3id', $_POST['uom3id'], PDO::PARAM_STR);
			$command->bindvalue(':vuom4id', $_POST['uom4id'], PDO::PARAM_STR);
			$command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
			$command->bindvalue(':vqty2', $_POST['qty2'], PDO::PARAM_STR);
			$command->bindvalue(':vqty3', $_POST['qty3'], PDO::PARAM_STR);
			$command->bindvalue(':vqty4', $_POST['qty4'], PDO::PARAM_STR);
      $command->bindvalue(':vstoragebinfromid', $_POST['storagebinfromid'], PDO::PARAM_STR);
			$command->bindvalue(':vmesinid', $_POST['mesinid'], PDO::PARAM_STR);
			$command->bindvalue(':vlotno', $_POST['lotno'], PDO::PARAM_STR);
      $command->bindvalue(':vitemnote', $_POST['itemnote'], PDO::PARAM_STR);
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
	public function actionReject() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call RejectTS(:vid,:vdatauser)';
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
  public function actionPurge() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgetransstock(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
          $command->execute();
        }
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
  public function actionPurgedetail() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgetransstockdet(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_STR);
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
  public function actionDelete()
  {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call DeleteTS(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
          $command->execute();
        }
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
  public function actionApprove()
  {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveTsfg(:vid,:vdatauser)';
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
    $sql = "select a.*, c.companyid,b.productoutputno,
						(select concat(z.sloccode,' - ',z.description) from sloc z where z.slocid = a.slocfromid) as fromsloc,
						(select concat(zz.sloccode,' - ',zz.description) from sloc zz where zz.slocid = a.sloctoid) as tosloc,
						d.productplanno, b.productoutputno 
						from transstock a
						left join productoutput b on b.productoutputid = a.productoutputid
						left join productplan d on d.productplanid = b.productplanid 
						inner join plant c on c.plantid = a.plantid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . " where a.transstockid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = getCatalog('transstockfg');
    $this->pdf->AddPage('L', array(
      210,
      330
    ));    
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->setFontSize(8);
      $this->pdf->text(10, $this->pdf->gety(), 'No ');
      $this->pdf->text(20, $this->pdf->gety(), ': ' . $row['transstockno']);
      $this->pdf->text(10, $this->pdf->gety() + 4, 'Tgl ');
      $this->pdf->text(20, $this->pdf->gety() + 4, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['transstockdate'])));
      $this->pdf->text(65, $this->pdf->gety(), 'No HP');
      $this->pdf->text(85, $this->pdf->gety(), ': ' . $row['productoutputno']);
      $this->pdf->text(65, $this->pdf->gety() + 4, 'No OK');
      $this->pdf->text(85, $this->pdf->gety() + 4, ': ' . $row['productplanno']);
      $this->pdf->text(140, $this->pdf->gety(), 'Gd Asal');
      $this->pdf->text(160, $this->pdf->gety(), ': ' . $row['fromsloc']);
      $this->pdf->text(140, $this->pdf->gety() + 4, 'Gd Tujuan');
      $this->pdf->text(160, $this->pdf->gety() + 4, ': ' . $row['tosloc']);
      $sql1        = "select b.productname, 
											sum(ifnull(a.qty,0)) as vqty, 
											sum(ifnull(a.qty2,0)) as vqty2, 
											sum(ifnull(a.qty3,0)) as vqty3, 
											sum(ifnull(a.qty4,0)) as vqty4, 
											c.uomcode,
											d.uomcode as uom2code,
											e.uomcode as uom3code,
											f.uomcode as uom4code,lotno,
							(select description from storagebin z where z.storagebinid = a.storagebinfromid) as storagebinfrom,
							(select description from storagebin z where z.storagebinid = a.storagebintoid) as storagebinto
							from transstockdet a
							inner join product b on b.productid = a.productid
							inner join unitofmeasure c on c.unitofmeasureid = a.uomid
							left join unitofmeasure d on d.unitofmeasureid = a.uom2id
							left join unitofmeasure e on e.unitofmeasureid = a.uom3id
							left join unitofmeasure f on f.unitofmeasureid = a.uom4id
							where transstockid = " . $row['transstockid'] . " group by b.productname order by transstockdetid";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 8);
      $this->pdf->colalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'R',
        'C',
        'C',
        'C',
        'C',
        'L',
        'L'
        
      );
      $this->pdf->setwidths(array(
        10,
        100,
        25,
        25,
        25,
        25,
        40,
				50,       
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Qty2',
        'Qty3',
        'Qty4',
        'Rak Asal',
				'No Lot',
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'R',
        'L',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['vqty']).' '.$row1['uomcode'],
          Yii::app()->format->formatNumber($row1['vqty2']).' '.$row1['uom2code'],
          Yii::app()->format->formatNumber($row1['vqty3']).' '.$row1['uom3code'],
          Yii::app()->format->formatNumber($row1['vqty4']).' '.$row1['uom4code'],
          $row1['storagebinfrom'],
					$row1['lotno']
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
      $this->pdf->text(50, $this->pdf->gety() + 5, 'Diserahkan oleh,');
      $this->pdf->text(120, $this->pdf->gety() + 5, 'Diketahui oleh,');
      $this->pdf->text(170, $this->pdf->gety() + 5, 'Diterima oleh,');
      $this->pdf->text(10, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(50, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(120, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(170, $this->pdf->gety() + 15, '........................');
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    $this->menuname = 'transstockfglist';
    parent::actionDownxls();
    $transstockid = GetSearchText(array('POST','GET','Q'),'transstockid');
		$plantcode   = GetSearchText(array('POST','GET','Q'),'plantcode');
		$productoutputno   = GetSearchText(array('POST','GET','Q'),'productoutputno');
    $transstockdate     = GetSearchText(array('POST','GET','Q'),'transstockdate');
    $transstockno = GetSearchText(array('POST','GET','Q'),'transstockno');
    $headernote = GetSearchText(array('POST','GET','Q'),'headernote');
		$sql = "select a.*, c.companyid,b.productoutputno,d.sloccode as fromsloc,e.sloccode as tosloc,c.plantcode,
		g.productname, 
				f.qty, 
				f.qty2, 
				f.qty3, 
				f.qty4, 
				h.uomcode,
				i.uomcode as uom2code,
				j.uomcode as uom3code,
				k.uomcode as uom4code,
				(select description from storagebin z where z.storagebinid = f.storagebinfromid) as storagebinfrom,
				(select description from storagebin z where z.storagebinid = f.storagebintoid) as storagebinto,
				l.kodemesin,f.itemnote
			from transstock a
			left join productoutput b on b.productoutputid = a.productoutputid
			left join plant c on c.plantid = a.plantid 
			left join sloc d on d.slocid = a.slocfromid 
			left join sloc e on e.slocid = a.sloctoid 
			left join transstockdet f on f.transstockid = a.transstockid 
			inner join product g on g.productid = f.productid
			inner join unitofmeasure h on h.unitofmeasureid = f.uomid
			left join unitofmeasure i on i.unitofmeasureid = f.uom2id
			left join unitofmeasure j on j.unitofmeasureid = f.uom3id
			left join unitofmeasure k on k.unitofmeasureid = f.uom4id
			left join mesin l on l.mesinid = f.mesinid 
		";
		$sql .= " where coalesce(a.transstockid,'') like '".$transstockid."' 
			and coalesce(c.plantcode,'') like '".$plantcode."' 
			and coalesce(a.transstockdate,'') like '".$transstockdate."' 
			and coalesce(a.productoutputno,'') like '".$productoutputno."' 
			and coalesce(a.transstockno,'') like '".$transstockno."' 
			and coalesce(a.headernote,'') like '".$headernote."'
			and a.transstocktypeid = 1 "
		;
		if ($_GET['id'] !== '') {
      $sql = $sql . " and a.transstockid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 2;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $line, $line-1)
				->setCellValueByColumnAndRow(1, $line, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $line, date(Yii::app()->params['dateviewfromdb'], strtotime($row['transstockdate'])))
				->setCellValueByColumnAndRow(3, $line, $row['transstockno'])
				->setCellValueByColumnAndRow(4, $line, $row['productoutputno'])
				->setCellValueByColumnAndRow(5, $line, $row['fromsloc'])
				->setCellValueByColumnAndRow(6, $line, $row['tosloc'])
				->setCellValueByColumnAndRow(7, $line, $row['headernote'])
				->setCellValueByColumnAndRow(8, $line, $row['productname'])
				->setCellValueByColumnAndRow(9, $line, Yii::app()->format->formatNumber($row['qty']))
				->setCellValueByColumnAndRow(10, $line, $row['uomcode'])
				->setCellValueByColumnAndRow(11, $line, Yii::app()->format->formatNumber($row['qty2']))
				->setCellValueByColumnAndRow(12, $line, $row['uom2code'])
				->setCellValueByColumnAndRow(13, $line, Yii::app()->format->formatNumber($row['qty3']))
				->setCellValueByColumnAndRow(14, $line, $row['uom3code'])
				->setCellValueByColumnAndRow(15, $line, Yii::app()->format->formatNumber($row['qty4']))
				->setCellValueByColumnAndRow(16, $line, $row['uom4code'])
				->setCellValueByColumnAndRow(17, $line, $row['kodemesin'])
				->setCellValueByColumnAndRow(18, $line, $row['itemnote'])
			;
			$line++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}