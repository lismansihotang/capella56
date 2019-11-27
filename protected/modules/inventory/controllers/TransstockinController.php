<?php
class TransstockinController extends Controller {
  public $menuname = 'transstockin';
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
  public function search() {
    header("Content-Type: application/json");
    $transstockid 		= GetSearchText(array('POST','Q'),'transstockid','','int');
    $plantid 		= GetSearchText(array('POST','GET'),'plantid','','int');
    $slocfromid 		= GetSearchText(array('POST','GET'),'slocfromid','','int');
    $addressbookid 		= GetSearchText(array('POST','GET'),'addressbookid','','int');
    $formrequestid 		= GetSearchText(array('POST','GET'),'formrequestid','','int');
    $sloctoid 		= GetSearchText(array('POST','GET'),'sloctoid','','int');
    $plantcode 		= GetSearchText(array('POST','GET'),'plantcode','','int');
    $productcode 		= GetSearchText(array('POST','Q'),'productcode');
    $productname 		= GetSearchText(array('POST','Q'),'productname');
    $slocfromcode 		= GetSearchText(array('POST','Q'),'slocfromcode');
    $transstockdate 		= GetSearchText(array('POST','Q'),'transstockdate');
    $transstockno 		= GetSearchText(array('POST','Q'),'transstockno');
    $headernote 		= GetSearchText(array('POST','Q'),'headernote');
    $formrequestno 		= GetSearchText(array('POST','Q'),'formrequestno');
    $productplanno 		= GetSearchText(array('POST','Q'),'productplanno');
    $sono 		= GetSearchText(array('POST','Q'),'sono');
    $sloctocode 		= GetSearchText(array('POST','Q'),'sloctocode');
    $customername 		= GetSearchText(array('POST','Q'),'customername');
    $recordstatus 		= GetSearchText(array('POST','Q'),'recordstatus');
    $isretur 		= GetSearchText(array('POST','GET'),'isretur');
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
						->leftjoin('formrequest e', 'e.formrequestid = t.formrequestid')
						->leftjoin('productplan f', 'f.productplanid = e.productplanid')
						->leftjoin('soheader g', 'g.soheaderid = f.soheaderid')
						->leftjoin('addressbook h', 'h.addressbookid = f.addressbookid')
						->where("
						((coalesce(transstockid,'') like :transstockid) 
						or (coalesce(b.plantcode,'') like :plantcode) 
						or (coalesce(d.sloccode,'') like :sloccode) 
						or (coalesce(t.transstockno,'') like :transstockno) 
						or (coalesce(t.headernote,'') like :headernote)  
						or (coalesce(t.transstockdate,'') like :transstockdate)) 
						and t.transstockno is not null 
						and t.recordstatus (".getUserRecordStatus('listtsin').") 
						and t.sloctoid in (".getUserObjectValues('sloc').")
						and t.plantid = ".$plantid." 
						",
					array(
						':transstockid' => '%' . $transstockid . '%',
						':plantcode' => '%' . $plantcode . '%',
						':sloccode' => '%' . $slocfromcode . '%',
						':transstockno' => '%' . $transstockno . '%',
						':headernote' => '%' . $headernote . '%',
						':formrequestno' => '%' . $formrequestid . '%',
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
						->leftjoin('formrequest e', 'e.formrequestid = t.formrequestid')
						->leftjoin('productplan f', 'f.productplanid = e.productplanid')
						->leftjoin('soheader g', 'g.soheaderid = f.soheaderid')
						->leftjoin('addressbook h', 'h.addressbookid = f.addressbookid')
					->where("
					((coalesce(transstockid,'') like :transstockid) 
					or (coalesce(b.plantcode,'') like :plantcode) 
					or (coalesce(d.sloccode,'') like :sloccode) 
					or (coalesce(transstockno,'') like :transstockno) 
					or (coalesce(t.headernote,'') like :headernote) 
					or (coalesce(t.formrequestno,'') like :formrequestno) 
					or (coalesce(t.transstockdate,'') like :transstockdate)) 
					and t.transstockno is not null 
					and t.recordstatus = getwfmaxstatbywfname('appts')
					and t.sloctoid in (".getUserObjectValues('sloc').")",
				array(
					':transstockid' => '%' . $transstockid . '%',
					':plantcode' => '%' . $plantcode . '%',
					':sloccode' => '%' . $slocfromcode . '%',
					':transstockno' => '%' . $transstockno . '%',
					':headernote' => '%' . $headernote . '%',
					':formrequestno' => '%' . $formrequestid . '%',
					':transstockdate' => '%' . $transstockdate . '%'
				))->queryScalar();
			} else {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('transstock t')
					->leftjoin('sloc a', 'a.slocid = t.slocfromid')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('sloc d', 'd.slocid = t.sloctoid')
					->leftjoin('formrequest e', 'e.formrequestid = t.formrequestid')
					->leftjoin('productplan f', 'f.productplanid = e.productplanid')
					->leftjoin('soheader g', 'g.soheaderid = f.soheaderid')
					->leftjoin('addressbook h', 'h.addressbookid = f.addressbookid')
					->leftjoin('productoutput i','i.productoutputid=t.productoutputid')
					->leftjoin('productplan j','j.productplanid = i.productplanid')
					->leftjoin('soheader l', 'l.soheaderid = j.soheaderid')
					->leftjoin('addressbook k','k.addressbookid = j.addressbookid')
					->where("
					((coalesce(transstockid,'') like :transstockid) 
					and (coalesce(b.plantcode,'') like :plantcode) 
					and (coalesce(slocfromcode,'') like :slocfromcode) 
					and (coalesce(sloctocode,'') like :sloctocode) 
					and (coalesce(transstockno,'') like :transstockno) 
					and (coalesce(t.headernote,'') like :headernote) 
					and (coalesce(t.formrequestno,'') like :formrequestno) 
					and (coalesce(t.transstockdate,'') like :transstockdate)) 
					and t.recordstatus in (".getUserRecordStatus('listtsin').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				"	and t.sloctoid in (".getUserObjectValues('sloc').") 
					",
				array(
					':transstockid' => '%' . $transstockid . '%',
					':plantcode' => '%' . $plantcode . '%',
					':slocfromcode' => '%' . $slocfromcode . '%',
					':sloctocode' => '%' . $sloctocode . '%',
					':transstockno' => '%' . $transstockno . '%',
					':headernote' => '%' . $headernote . '%',
					':formrequestno' => '%' . $formrequestid . '%',
					':transstockdate' => '%' . $transstockdate . '%'
				))->queryScalar();
			}
			$result['total'] = $cmd;
			if (isset($_GET['frts'])) {
					$cmd = Yii::app()->db->createCommand()->select('t.*,b.plantcode,f.productplanid,a.sloccode as slocfromcode,d.sloccode as sloctocode,t.headernote,b.companyid,c.companyname,
				e.formrequestno,g.sono,h.fullname,
				f.addressbookid')
						->from('transstock t')
						->leftjoin('sloc a', 'a.slocid = t.slocfromid')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('sloc d', 'd.slocid = t.sloctoid')
						->leftjoin('formrequest e', 'e.formrequestid = t.formrequestid')
						->leftjoin('productplan f', 'f.productplanid = t.productplanid')
						->leftjoin('soheader g', 'g.soheaderid = f.soheaderid')
						->leftjoin('addressbook h', 'h.addressbookid = f.addressbookid')
						->where("
						((coalesce(transstockid,'') like :transstockid) 
						or (coalesce(b.plantcode,'') like :plantcode) 
						or (coalesce(d.sloccode,'') like :sloccode) 
						or (coalesce(transstockno,'') like :transstockno) 
						or (coalesce(t.headernote,'') like :headernote) 
						or (coalesce(t.formrequestno,'') like :formrequestno) 
						or (coalesce(t.transstockdate,'') like :transstockdate)) 
						and t.transstockno is not null 
						and t.recordstatus (".getUserRecordStatus('listtsin').") 
						and t.sloctoid in (".getUserObjectValues('sloc').")
						and t.plantid = ".$plantid."
						",
					array(
						':transstockid' => '%' . $transstockid . '%',
						':plantcode' => '%' . $plantcode . '%',
						':sloccode' => '%' . $slocfromcode . '%',
						':transstockno' => '%' . $transstockno . '%',
						':headernote' => '%' . $headernote . '%',
						':formrequestno' => '%' . $formrequestid . '%',
						':transstockdate' => '%' . $transstockdate . '%'
					))->queryAll();
				} else 
			if (isset($_GET['combo'])) {
				$cmd = Yii::app()->db->createCommand()->select('t.*,b.plantcode,f.productplanid,a.sloccode as slocfromcode,d.sloccode as sloctocode,t.headernote,b.companyid,c.companyname,
				e.formrequestno,g.sono,h.fullname,f.addressbookid')
					->from('transstock t')
					->leftjoin('sloc a', 'a.slocid = t.slocfromid')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('sloc d', 'd.slocid = t.sloctoid')
						->leftjoin('formrequest e', 'e.formrequestid = t.formrequestid')
						->leftjoin('productplan f', 'f.productplanid = t.productplanid')
						->leftjoin('soheader g', 'g.soheaderid = f.soheaderid')
						->leftjoin('addressbook h', 'h.addressbookid = f.addressbookid')
					->where("
					((coalesce(transstockid,'') like :transstockid) 
					or (coalesce(b.plantcode,'') like :plantcode) 
					or (coalesce(d.sloccode,'') like :sloccode) 
					or (coalesce(transstockno,'') like :transstockno) 
					or (coalesce(t.headernote,'') like :headernote) 
					or (coalesce(t.formrequestno,'') like :formrequestno) 
					or (coalesce(t.transstockdate,'') like :transstockdate)) 
					and t.transstockno is not null 
					and t.recordstatus = getwfmaxstatbywfname('appts')
					and t.sloctoid in (".getUserObjectValues('sloc').")",
				array(
					':transstockid' => '%' . $transstockid . '%',
					':plantcode' => '%' . $plantcode . '%',
					':sloccode' => '%' . $slocfromcode . '%',
					':transstockno' => '%' . $transstockno . '%',
					':headernote' => '%' . $headernote . '%',
					':formrequestno' => '%' . $formrequestid . '%',
					':transstockdate' => '%' . $transstockdate . '%'
				))->queryAll();
			} else {
				$cmd = Yii::app()->db->createCommand()->select("t.*,b.plantcode,a.sloccode as slocfromcode,d.sloccode as sloctocode,t.headernote,b.companyid,c.companyname,
					e.formrequestno,
					(case when t.transstocktypeid = 0 then f.productplanno else j.productplanno end) as productplanno,
					(case when t.transstocktypeid = 0 then g.sono else l.sono end) as sono,
					(case when t.transstocktypeid = 0 then f.productplanid else j.productplanid end) as productplanid,
					(case when t.transstocktypeid = 0 then h.fullname else k.fullname end) as fullname
					")
					->from('transstock t')
					->leftjoin('sloc a', 'a.slocid = t.slocfromid')
					->leftjoin('plant b', 'b.plantid = t.plantid')
					->leftjoin('company c', 'c.companyid = b.companyid')
					->leftjoin('sloc d', 'd.slocid = t.sloctoid')
					->leftjoin('formrequest e', 'e.formrequestid = t.formrequestid')
					->leftjoin('productplan f', 'f.productplanid = e.productplanid')
					->leftjoin('soheader g', 'g.soheaderid = f.soheaderid')
					->leftjoin('addressbook h', 'h.addressbookid = f.addressbookid')
					->leftjoin('productoutput i','i.productoutputid=t.productoutputid')
					->leftjoin('productplan j','j.productplanid = i.productplanid')
					->leftjoin('soheader l', 'l.soheaderid = j.soheaderid')
					->leftjoin('addressbook k','k.addressbookid = j.addressbookid')
					->where("
					((coalesce(transstockid,'') like :transstockid)  
					and (coalesce(b.plantcode,'') like :plantcode) 
					and (coalesce(slocfromcode,'') like :slocfromcode) 
					and (coalesce(sloctocode,'') like :sloctocode) 
					and (coalesce(transstockno,'') like :transstockno)  
					and (coalesce(t.headernote,'') like :headernote) 
					and (coalesce(t.formrequestno,'') like :formrequestno) 
					and (coalesce(t.transstockdate,'') like :transstockdate))  
					and t.recordstatus in (".getUserRecordStatus('listtsin').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				"	and t.sloctoid in (".getUserObjectValues('sloc').")
					",
				array(
					':transstockid' => '%' . $transstockid . '%',
					':plantcode' => '%' . $plantcode . '%',
					':slocfromcode' => '%' . $slocfromcode . '%',
					':sloctocode' => '%' . $sloctocode . '%',
					':transstockno' => '%' . $transstockno . '%',
					':headernote' => '%' . $headernote . '%',
					':formrequestno' => '%' . $formrequestid . '%',
					':transstockdate' => '%' . $transstockdate . '%'
					))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
			}
			foreach ($cmd as $data) {
				$row[] = array(
					'transstockid' => $data['transstockid'],
					'transstockdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['transstockdate'])),
					'transstockno' => $data['transstockno'],
					'plantid' => $data['plantid'],
					'isretur' => $data['isretur'],
					'plantcode' => $data['plantcode'],
					'companyid' => $data['companyid'],
					'companyname' => $data['companyname'],
					'formrequestid' => $data['formrequestid'],
					'formrequestno' => $data['formrequestno'],
					'fullname' => $data['fullname'],
					'productplanno' => $data['productplanno'],
					'productplanid' => $data['productplanid'],
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
				select a.formrequestid, a.slocfromid, b.productplanno, c.sono, d.fullname
				from formrequest a
				left join productplan b on b.productplanid = a.productplanid
				left join soheader c on c.soheaderid = b.soheaderid
				left join addressbook d on d.addressbookid = c.addressbookid
				where a.formrequestid = ".$formrequestid)->queryRow();
			$result = $cmd;
		}
    return CJSON::encode($result);
	}
  public function actionSearchdetail() {
    header("Content-Type: application/json");
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
					->leftjoin('materialtype f', 'f.materialtypeid = a.materialtypeid')
					->where('t.transstockid = :transstockid',
					array(
				':transstockid' => $id
				))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productcode,a.productname,b.description as rakasal,d.description as raktujuan,e.namamesin,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code,f.materialtypecode,
						GetStdQty2(a.productid) as stdqty2,
						GetStdQty3(a.productid) as stdqty3,
						GetStdQty4(a.productid) as stdqty4,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom4id) as uom4code')
					->from('transstockdet t')
					->leftjoin('transstock m', 'm.transstockid = t.transstockid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('storagebin b', 'b.storagebinid = t.storagebinfromid')
					->leftjoin('storagebin d', 'd.storagebinid = t.storagebintoid')
					->leftjoin('mesin e', 'e.mesinid = t.mesinid')
					->leftjoin('materialtype f', 'f.materialtypeid = a.materialtypeid')
					->where('t.transstockid = :transstockid', array(
		':transstockid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'transstockdetid' => $data['transstockdetid'],
				'transstockid' => $data['transstockid'],
        'productid' => $data['productid'],
				'productcode' => $data['productcode'],
				'productname' => $data['productname'],
				'materialtypecode' => $data['materialtypecode'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'qtyreq' => Yii::app()->format->formatNumber($data['qtyreq']),
				'qty2' => Yii::app()->format->formatNumber($data['qty2']),
				'qty3' => Yii::app()->format->formatNumber($data['qty3']),
				'qty4' => Yii::app()->format->formatNumber($data['qty4']),
				'stdqty2' => Yii::app()->format->formatNumber($data['stdqty2']),
				'stdqty3' => Yii::app()->format->formatNumber($data['stdqty3']),
				'stdqty4' => Yii::app()->format->formatNumber($data['stdqty4']),
        'uomid' => $data['uomid'],
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
		$sql = 'call ModifTransstock(:vid,:vtransstockdate,:vplantid,:vformrequestid,:vslocfromid,:vsloctoid,:vheadernote,:visretur,:vdatauser)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vtransstockdate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vformrequestid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vslocfromid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vsloctoid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':visretur', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionSave() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['transstockin-transstockid'])?$_POST['transstockin-transstockid']:''),
			date(Yii::app()->params['datetodb'], strtotime($_POST['transstockin-transstockdate'])),
			$_POST['transstockin-plantid'],$_POST['transstockin-formrequestid'],$_POST['transstockin-slocfromid'],
			$_POST['transstockin-sloctoid'],$_POST['transstockin-headernote'],(isset($_POST['transstockin-isretur']) ? 1 : 0)));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
  public function actionSavedetail() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Inserttransstockdetin(:vtransstockid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,:vqty4,:vstoragebinfromid,:vstoragebintoid,
					:vmesinid,:vlotno,:vitemnote,:vdatauser)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatetransstockdetin(:vid,:vtransstockid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,:vqty4,:vstoragebinfromid,:vstoragebintoid,
					:vmesinid,:vlotno,:vitemnote,:vdatauser)';
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
			$command->bindvalue(':vstoragebintoid', $_POST['storagebintoid'], PDO::PARAM_STR);
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
  public function actionPurge() {
    header("Content-Type: application/json");
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
      GetMessage(true, 'chooseone');
    }
  }
  public function actionPurgedetail() {
    header("Content-Type: application/json");
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
      GetMessage(true, 'chooseone');
    }
  }
  public function actionReject() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call RejectTSin(:vid,:vdatauser)';
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
      GetMessage(true, 'chooseone');
    }
  }
  public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveTSin(:vid,:vdatauser)';
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
      GetMessage(true, 'chooseone');
    }
  }
}
