<?php
class PrheaderController extends Controller {
  public $menuname = 'prheader';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexraw() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchraw();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexjasa() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchjasa();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexresult() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchresult();
    else
      $this->renderPartial('index', array());
  }
	public function actionIndexprpo() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchprpo();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
		$id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'prheaderid' => $id,
		));
  }
	public function actionGeneratedetail() {
    if (isset($_POST['id'])) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GeneratePRFR(:vid,:vhid,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
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
    Yii::app()->end();
  }
  public function actionsearchprpo() {
    header('Content-Type: application/json');
    $prheaderid 	= isset($_POST['prheaderid']) ? $_POST['prheaderid'] : '';
    $plantid 			= isset($_POST['plantid']) ? $_POST['plantid'] : '';
    $addressbookid 			= isset($_POST['addressbookid']) ? $_POST['addressbookid'] : '';
    $prrawid 			= isset($_POST['prrawid']) ? $_POST['prrawid'] : '';
    $isjasa 			= isset($_POST['isjasa']) ? $_POST['isjasa'] : '';
    $prjasaid 		= isset($_POST['prjasaid']) ? $_POST['prjasaid'] : '';
    $prheaderid 	= isset($_GET['prheaderid']) ? $_GET['prheaderid'] : $prheaderid;
    $plantid 			= isset($_GET['plantid']) ? $_GET['plantid'] : $plantid;
    $addressbookid 			= isset($_GET['addressbookid']) ? $_GET['addressbookid'] : $addressbookid;
    $prrawid 			= isset($_GET['prrawid']) ? $_GET['prrawid'] : $prrawid;
    $prjasaid 		= isset($_GET['prjasaid']) ? $_GET['prjasaid'] : $prjasaid;
    $isjasa 			= isset($_GET['isjasa']) ? $_GET['isjasa'] : $isjasa;
    $prno 				= isset($_GET['q']) ? $_GET['q'] : '';
		$result     	= array();
    $row        	= array();
		if ($isjasa == '0') {
			$cmd = Yii::app()->db->createCommand("
				select a.prheaderid,a.prno,b.prrawid,(null) as prjasaid,c.productname,c.productid,b.qty,b.uomid,d.uomcode,e.kodemesin,b.mesinid,f.slocid,f.sloccode
				from prheader a 
				join prraw b on b.prheaderid = a.prheaderid 
				join product c on c.productid = b.productid 
				left join unitofmeasure d on d.unitofmeasureid = b.uomid 
				left join mesin e on e.mesinid = b.mesinid 
				left join sloc f on f.slocid = b.sloctoid				
				left join productplant g on g.productid = c.productid	and g.slocid = f.slocid and g.uom1 = d.unitofmeasureid	
				left join addressbook j on j.addressbookid = g.addressbookid
				where a.recordstatus = 5 
					and a.isjasa = 0 
					and a.plantid = ".$plantid." 
					and coalesce(a.prno,'') like '%".$prno."%' 
					and j.addressbookid = ".$addressbookid." 
					and b.qty > b.poqty
			")->queryAll();
		} else {
			$cmd = Yii::app()->db->createCommand("
				select a.prheaderid,a.prno,b.prjasaid,(null) as prrawid,c.productname,c.productid,b.qty,b.uomid,d.uomcode,e.kodemesin,b.mesinid,f.slocid,f.sloccode
				from prheader a 
				join prjasa b on b.prheaderid = a.prheaderid 
				join product c on c.productid = b.productid 
				left join unitofmeasure d on d.unitofmeasureid = b.uomid 
				left join mesin e on e.mesinid = b.mesinid 
				left join sloc f on f.slocid = b.sloctoid
				left join productplant g on g.productid = c.productid	and g.slocid = f.slocid and g.uom1 = d.unitofmeasureid		
				left join addressbook j on j.addressbookid = g.addressbookid
				where a.recordstatus = 5 
					and a.isjasa = 1
					and a.plantid = ".$plantid."
					and j.addressbookid = ".$addressbookid."
					and coalesce(a.prno,'') like '%".$prno."%' 
					and b.qty > b.poqty
			")->queryAll();
		}
		foreach ($cmd as $data) {
			$row[] = array(
				'prheaderid' => $data['prheaderid'],
				'prno' => $data['prno'],
				'productid' => $data['productid'],
				'productname' => $data['productname'],
				'prjasaid' => $data['prjasaid'],
				'prrawid' => $data['prrawid'],
				'qty' => $data['qty'],
				'uomid' => $data['uomid'],
				'uomcode' => $data['uomcode'],
				'mesinid' => $data['mesinid'],
				'kodemesin' => $data['kodemesin'],
				'slocid' => $data['slocid'],
				'sloccode' => $data['sloccode'],
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
		));
    return CJSON::encode($result);
	}
  public function search() {
    header('Content-Type: application/json');
    $prheaderid 		= GetSearchText(array('POST','Q'),'prheaderid','','int');
    $prrawid 		= GetSearchText(array('POST','GET'),'prrawid','','int');
    $prjasaid 		= GetSearchText(array('POST','GET'),'prjasaid','','int');
    $plantid 		= GetSearchText(array('POST','GET'),'plantid','','int');
    $addressbookid 		= GetSearchText(array('POST','GET'),'addressbookid','','int');
    $plantcode 		= GetSearchText(array('POST','Q'),'plantcode');
    $sloccode 		= GetSearchText(array('POST','Q'),'sloccode');
    $productraw 		= GetSearchText(array('POST','Q'),'productraw');
    $productjasa 		= GetSearchText(array('POST','Q'),'productjasa');
    $productresult 		= GetSearchText(array('POST','Q'),'productresult');
    $prdate 		= GetSearchText(array('POST','Q'),'prdate');
    $prno 		= GetSearchText(array('POST','Q'),'prno');
    $requestedbycode 		= GetSearchText(array('POST','Q'),'requestedbycode');
    $description 		= GetSearchText(array('POST','Q'),'description');
    $frno 		= GetSearchText(array('POST','Q'),'frno');
    $recordstatus 		= GetSearchText(array('POST','Q'),'recordstatus');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','prheaderid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		if (!isset($_GET['getdatajasa'])) {
		if (!isset($_GET['getdata'])) {
			if (isset($_GET['prpo'])) {
				if ($_GET['isjasa'] == 0) {
					$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
						->from('prheader t')
						->leftjoin('sloc a', 'a.slocid = t.slocfromid')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
						->leftjoin('formrequest e', 'e.formrequestid = t.formrequestid')
						->leftjoin('productplan f', 'f.productplanid = e.productplanid')
						->leftjoin('soheader g', 'g.soheaderid = f.soheaderid')
						->leftjoin('addressbook h', 'h.addressbookid = g.addressbookid')
						->where("
						((coalesce(t.prheaderid,'') like :prheaderid) 
						or (coalesce(b.plantcode,'') like :plantcode) 
						or (coalesce(a.sloccode,'') like :sloccode) 
						or (coalesce(t.prno,'') like :prno) 
						or (coalesce(t.description,'') like :description) 
						or (coalesce(d.requestedbycode,'') like :requestedbycode) 
						or (coalesce(e.formrequestno,'') like :frno) 
						or (coalesce(t.prdate,'') like :prdate)) 
						and t.recordstatus in (".getUserRecordStatus('listpr').")
						and t.isjasa = 0 
						and t.plantid = ".$plantid." 
						and g.addressbookid = ".$addressbookid." 
						and t.prheaderid in (select zz.prheaderid from prraw zz where zz.qty > zz.poqty) 
						",
					array(
						':prheaderid' => '%' . $prheaderid . '%',
						':plantcode' => '%' . $plantcode . '%',
						':sloccode' => '%' . $sloccode . '%',
						':prno' => '%' . $prno . '%',
						':frno' => '%' . $frno . '%',
						':description' => '%' . $description . '%',
						':requestedbycode' => '%' . $requestedbycode . '%',
						':prdate' => '%' . $prdate . '%'
					))->queryScalar();
				} else {
					$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
						->from('prheader t')
						->leftjoin('sloc a', 'a.slocid = t.slocfromid')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
						->leftjoin('formrequest e', 'e.formrequestid = t.formrequestid')
						->leftjoin('productplan f', 'f.productplanid = e.productplanid')
						->leftjoin('soheader g', 'g.soheaderid = f.soheaderid')
						->leftjoin('addressbook h', 'h.addressbookid = g.addressbookid')
						->where("
						((coalesce(t.prheaderid,'') like :prheaderid) 
						or (coalesce(b.plantcode,'') like :plantcode) 
						or (coalesce(a.sloccode,'') like :sloccode) 
						or (coalesce(t.prno,'') like :prno) 
						or (coalesce(t.description,'') like :description) 
						or (coalesce(d.requestedbycode,'') like :requestedbycode) 
						or (coalesce(e.formrequestno,'') like :frno) 
						or (coalesce(t.prdate,'') like :prdate)) 
						and t.recordstatus in (".getUserRecordStatus('listpr').")
						and t.isjasa = 1 
						and t.plantid = ".$plantid." 
						and g.addressbookid = ".$addressbookid." 
						and t.prheaderid in (select zz.prheaderid from prjasa zz where zz.qty > zz.poqty) 
						",
					array(
						':prheaderid' => '%' . $prheaderid . '%',
						':plantcode' => '%' . $plantcode . '%',
						':sloccode' => '%' . $sloccode . '%',
						':prno' => '%' . $prno . '%',
						':frno' => '%' . $frno . '%',
						':description' => '%' . $description . '%',
						':requestedbycode' => '%' . $requestedbycode . '%',
						':prdate' => '%' . $prdate . '%'
					))->queryScalar();
				}
			} else {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('prheader t')
					->leftjoin('sloc a', 'a.slocid = t.slocfromid')
					->leftjoin('plant b', 'b.plantid = t.plantid')
					->leftjoin('company c', 'c.companyid = b.companyid')
					->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
					->leftjoin('formrequest e', 'e.formrequestid = t.formrequestid')
					->leftjoin('productplan f', 'f.productplanid = e.productplanid')
					->leftjoin('soheader g', 'g.soheaderid = f.soheaderid')
					->leftjoin('addressbook h', 'h.addressbookid = g.addressbookid')
					->where("
					((coalesce(t.prheaderid,'') like :prheaderid) 
					and (coalesce(b.plantcode,'') like :plantcode) 
					and (coalesce(a.sloccode,'') like :sloccode) 
					and (coalesce(t.prno,'') like :prno) 
					and (coalesce(t.description,'') like :description) 
					and (coalesce(d.requestedbycode,'') like :requestedbycode) 
					and (coalesce(e.formrequestno,'') like :frno) 
					and (coalesce(t.prdate,'') like :prdate))
					and t.recordstatus in (".getUserRecordStatus('listpr').")  
					and t.plantid in (".getUserObjectValues('plant').")".
				(($productraw != '%%')?"
				and t.prheaderid in (
					select distinct z.prheaderid 
					from prraw z 
					join product zz on zz.productid = z.productid 
					where zz.productname like '%".$productraw."%'
				)":'').
				(($productresult != '%%')?"
				and t.prheaderid in (
					select distinct z.prheaderid 
					from prresult z 
					join product zz on zz.productid = z.productid 
					where zz.productname like '%".$productresult."%'
				)":'').
				(($productjasa != '%%')?"
				and t.prheaderid in (
					select distinct z.prheaderid 
					from prjasa z 
					join product zz on zz.productid = z.productid 
					where zz.productname like '%".$productjasa."%'
				)":'').
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'')
				,			 
					array(
					':prheaderid' => '%' . $prheaderid . '%',
					':plantcode' => '%' . $plantcode . '%',
					':sloccode' => '%' . $sloccode . '%',
					':prno' => '%' . $prno . '%',
					':frno' => '%' . $frno . '%',
					':description' => '%' . $description . '%',
					':requestedbycode' => '%' . $requestedbycode . '%',
					':prdate' => '%' . $prdate . '%'
				))->queryScalar();
			}
			$result['total'] = $cmd;
			if (isset($_GET['prpo'])) {
				if ($_GET['isjasa'] == '0') {
					$cmd = Yii::app()->db->createCommand()->select('t.*,e.formrequestno,a.sloccode,b.plantcode,a.description as slocdesc,t.description,b.companyid,c.companyname,d.requestedbycode')
						->from('prheader t')
						->leftjoin('sloc a', 'a.slocid = t.slocfromid')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
						->leftjoin('formrequest e', 'e.formrequestid = t.formrequestid')
						->leftjoin('productplan f', 'f.productplanid = e.productplanid')
						->leftjoin('soheader g', 'g.soheaderid = f.soheaderid')
						->leftjoin('addressbook h', 'h.addressbookid = g.addressbookid')
						->where("
						((coalesce(t.prheaderid,'') like :prheaderid) 
						or (coalesce(b.plantcode,'') like :plantcode) 
						or (coalesce(a.sloccode,'') like :sloccode) 
						or (coalesce(t.prno,'') like :prno) 
						or (coalesce(t.description,'') like :description) 
						or (coalesce(e.formrequestno,'') like :frno) 
						or (coalesce(d.requestedbycode,'') like :requestedbycode) 
						or (coalesce(t.prdate,'') like :prdate)) 
						and t.recordstatus in (".getUserRecordStatus('listpr').") 
						and t.isjasa = 0
						and t.plantid = ".$plantid." 
						and g.addressbookid = ".$addressbookid." 						
						and t.prheaderid in (select zz.prheaderid from prraw zz where zz.qty > zz.poqty) 
						",
					array(
						':prheaderid' => '%' . $prheaderid . '%',
						':plantcode' => '%' . $plantcode . '%',
						':sloccode' => '%' . $sloccode . '%',
						':prno' => '%' . $prno . '%',
						':frno' => '%' . $frno . '%',
						':description' => '%' . $description . '%',
						':requestedbycode' => '%' . $requestedbycode . '%',
						':prdate' => '%' . $prdate . '%'
						))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
				} else {
					$cmd = Yii::app()->db->createCommand()->select('t.*,e.formrequestno,a.sloccode,b.plantcode,a.description as slocdesc,
						t.description,b.companyid,c.companyname,d.requestedbycode')
						->from('prheader t')
						->leftjoin('sloc a', 'a.slocid = t.slocfromid')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
						->leftjoin('formrequest e', 'e.formrequestid = t.formrequestid')
						->leftjoin('productplan f', 'f.productplanid = e.productplanid')
						->leftjoin('soheader g', 'g.soheaderid = f.soheaderid')
						->leftjoin('addressbook h', 'h.addressbookid = g.addressbookid')
						->where("
						((coalesce(t.prheaderid,'') like :prheaderid) 
						or (coalesce(b.plantcode,'') like :plantcode) 
						or (coalesce(a.sloccode,'') like :sloccode) 
						or (coalesce(t.prno,'') like :prno) 
						or (coalesce(e.formrequestno,'') like :frno) 
						or (coalesce(t.description,'') like :description) 
						or (coalesce(d.requestedbycode,'') like :requestedbycode) 
						or (coalesce(t.prdate,'') like :prdate)) 
						and t.recordstatus in (".getUserRecordStatus('listpr').")
						and t.isjasa = 1
						and t.plantid = ".$plantid." 
						and g.addressbookid = ".$addressbookid." 
						and t.prheaderid in (select zz.prheaderid from prraw zz where zz.qty > zz.poqty) 
						",
					array(
						':prheaderid' => '%' . $prheaderid . '%',
						':plantcode' => '%' . $plantcode . '%',
						':sloccode' => '%' . $sloccode . '%',
						':prno' => '%' . $prno . '%',
						':frno' => '%' . $frno . '%',
						':description' => '%' . $description . '%',
						':requestedbycode' => '%' . $requestedbycode . '%',
						':prdate' => '%' . $prdate . '%'
						))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
				}
			} else {
				$cmd = Yii::app()->db->createCommand()->select('t.*,e.formrequestno,a.sloccode,b.plantcode,a.description as slocdesc,
					b.companyid,c.companyname,d.requestedbycode,t.description')
					->from('prheader t')
					->leftjoin('sloc a', 'a.slocid = t.slocfromid')
					->leftjoin('plant b', 'b.plantid = t.plantid')
					->leftjoin('company c', 'c.companyid = b.companyid')
					->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
					->leftjoin('formrequest e', 'e.formrequestid = t.formrequestid')
					->leftjoin('productplan f', 'f.productplanid = e.productplanid')
					->leftjoin('soheader g', 'g.soheaderid = f.soheaderid')
					->leftjoin('addressbook h', 'h.addressbookid = g.addressbookid')
					->where("((coalesce(t.prheaderid,'') like :prheaderid) 
						and (coalesce(a.sloccode,'') like :sloccode) 
						and (coalesce(t.prno,'') like :prno) 
						and (coalesce(b.plantcode,'') like :plantcode) 
						and (coalesce(t.description,'') like :description) 
						and (coalesce(e.formrequestno,'') like :frno) 
						and (coalesce(d.requestedbycode,'') like :requestedbycode) 
						and (prdate like :prdate))
						and t.recordstatus in (".getUserRecordStatus('listpr').")
					and t.plantid in (".getUserObjectValues('plant').")".
				(($productraw != '%%')?"
				and t.prheaderid in (
					select distinct z.prheaderid 
					from prraw z 
					join product zz on zz.productid = z.productid 
					where zz.productname like '%".$productraw."%'
				)":'').
				(($productresult != '%%')?"
				and t.prheaderid in (
					select distinct z.prheaderid 
					from prresult z 
					join product zz on zz.productid = z.productid 
					where zz.productname like '%".$productresult."%'
				)":'').
				(($productjasa != '%%')?"
				and t.prheaderid in (
					select distinct z.prheaderid 
					from prjasa z 
					join product zz on zz.productid = z.productid 
					where zz.productname like '%".$productjasa."%'
				)":'').
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'')
				,			 
					array(
						':prheaderid' => '%' . $prheaderid . '%',
						':plantcode' => '%' . $plantcode . '%',
						':sloccode' => '%' . $sloccode . '%',
						':prno' => '%' . $prno . '%',
						':frno' => '%' . $frno . '%',
						':description' => '%' . $description . '%',
						':requestedbycode' => '%' . $requestedbycode . '%',
						':prdate' => '%' . $prdate . '%'
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
			}
			foreach ($cmd as $data) {
				$row[] = array(
					'prheaderid' => $data['prheaderid'],
					'prdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['prdate'])),
					'prno' => $data['prno'],
					'plantid' => $data['plantid'],
					'plantcode' => $data['plantcode'],
					'formrequestid' => $data['formrequestid'],
					'formrequestno' => $data['formrequestno'],
					'companyid' => $data['companyid'],
					'companyname' => $data['companyname'],
					'slocfromid' => $data['slocfromid'],
					'sloccode' => $data['sloccode'],
					'requestedbyid' => $data['requestedbyid'],
					'requestedbycode' => $data['requestedbycode'],
					'isjasa' => $data['isjasa'],
					'description' => $data['description'],
					'recordstatus' => $data['recordstatus'],
					'statusname' => $data['statusname']
			 );
			}
			$result = array_merge($result, array(
				'rows' => $row
			));
		} else {
			$cmd = Yii::app()->db->createCommand("
				select a.prrawid,a.prheaderid, a.productid, b.productname,a.uomid,a.uom2id,date_format(a.reqdate,'%d-%m-%Y') as reqdate,
				a.qty - a.poqty as qty, a.qty2 - a.poqty2 as qty2,g.slocfromid,g.requestedbyid
				from  prraw a 
				join product b on b.productid = a.productid
				join prheader g on g.prheaderid = a.prheaderid
				where a.prrawid = ".$prrawid)->queryRow();
			$result = $cmd;
		}
		} else {
			$cmd = Yii::app()->db->createCommand("
				select a.prjasaid,a.prheaderid, a.productid, b.productname,a.uomid,a.qty - a.poqty as qty,g.slocfromid,a.mesinid,a.reqdate,a.sloctoid,a.mesinid
				from  prjasa a 
				join product b on b.productid = a.productid
				join prheader g on g.prheaderid = a.prheaderid
				where a.prjasaid = ".$prjasaid)->queryRow();
			$result = $cmd;
		}
    return CJSON::encode($result);
	}
	public function actionSearchRaw() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'prrawid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('prraw t')
			->leftjoin('prheader g', 'g.prheaderid = t.prheaderid')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('mesin c', 'c.mesinid = t.mesinid')
			->leftjoin('sloc d', 'd.slocid = t.sloctoid')
			->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')
			->where('t.prheaderid = :prheaderid',
			array(
				':prheaderid' => $id
		))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productcode,a.productname,c.namamesin,d.sloccode,t.description,e.materialtypecode,
			(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,
			(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
			(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code,
			GetStdQty2(a.productid) as stdqty2,
						GetStdQty3(a.productid) as stdqty3,
						GetStdQty4(a.productid) as stdqty4,
						GetStock(a.productid,t.uomid,t.sloctoid) as qtystock,
						(
SELECT ifnull(sum(ifnull(z.qty,0)),0)
FROM podetail z
WHERE z.prrawid = t.prrawid AND z.productid = a.productid and z.poheaderid > 0
) AS qtypo,
(
SELECT ifnull(sum(ifnull(zz.qty,0)),0)
FROM grdetail zz
JOIN podetail zzz ON zzz.podetailid = zz.podetailid
WHERE zzz.prrawid = t.prrawid AND zz.productid = zzz.productid and zz.grheaderid > 0
) AS qtygr,
			(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom4id) as uom4code')
			->from('prraw t')
			->leftjoin('prheader g', 'g.prheaderid = t.prheaderid')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('mesin c', 'c.mesinid = t.mesinid')
			->leftjoin('sloc d', 'd.slocid = t.sloctoid')
			->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')
			->where('t.prheaderid = :prheaderid', array(
				':prheaderid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
			if ($data['qtystock'] >= $data['qty']) {
				$stockcount = 0;
			} else {
				$stockcount = 1;
			}
      $row[] = array(
        'prrawid' => $data['prrawid'],
        'prheaderid' => $data['prheaderid'],
        'productid' => $data['productid'],
				'productcode' => $data['productcode'],
				'productname' => $data['productname'],
				'materialtypecode' => $data['materialtypecode'],
				'formrequestrawid' => $data['formrequestrawid'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
				'qty2' => Yii::app()->format->formatNumber($data['qty2']),
				'qty3' => Yii::app()->format->formatNumber($data['qty3']),
				'qty4' => Yii::app()->format->formatNumber($data['qty4']),
				'poqty' => Yii::app()->format->formatNumber($data['poqty']),
				'poqty2' => Yii::app()->format->formatNumber($data['poqty2']),
				'poqty3' => Yii::app()->format->formatNumber($data['poqty3']),
				'poqty4' => Yii::app()->format->formatNumber($data['poqty4']),
				'stdqty2' => Yii::app()->format->formatNumber($data['stdqty2']),
				'stdqty3' => Yii::app()->format->formatNumber($data['stdqty3']),
				'stdqty4' => Yii::app()->format->formatNumber($data['stdqty4']),
				'qtystock' => Yii::app()->format->formatNumber($data['qtystock']),
				'qtyoutpo' => Yii::app()->format->formatNumber($data['qtypo']-$data['qtygr']),
				'qtyoutfpp' => Yii::app()->format->formatNumber($data['qty']-$data['qtypo']),
        'uomid' => $data['uomid'],
        'stockcount' => $stockcount,
				'uom2id' => $data['uom2id'],
				'uom3id' => $data['uom3id'],
				'uom4id' => $data['uom4id'],
        'uomcode' => $data['uomcode'],
				'uom2code' => $data['uom2code'],
				'uom3code' => $data['uom3code'],
				'uom4code' => $data['uom4code'],
				'reqdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['reqdate'])),
        'mesinid' => $data['mesinid'],
        'namamesin' => $data['namamesin'],
        'sloctoid' => $data['sloctoid'],
        'sloccode' => $data['sloccode'],
				'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function actionSearchJasa() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'prjasaid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('prjasa t')
			->leftjoin('prheader g', 'g.prheaderid = t.prheaderid')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('mesin c', 'c.mesinid = t.mesinid')
			->leftjoin('sloc d', 'd.slocid = t.sloctoid')
			->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')
			->where('t.prheaderid = :prheaderid',
			array(
				':prheaderid' => $id
			))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
			->select('t.*,a.productcode,a.productname,c.namamesin,d.sloccode,t.description,e.materialtypecode,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode')
			->from('prjasa t')
			->leftjoin('prheader g', 'g.prheaderid = t.prheaderid')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('mesin c', 'c.mesinid = t.mesinid')
			->leftjoin('sloc d', 'd.slocid = t.sloctoid')
			->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')
			->where('t.prheaderid = :prheaderid', array(
				':prheaderid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'prjasaid' => $data['prjasaid'],
        'prheaderid' => $data['prheaderid'],
        'productid' => $data['productid'],
				'productcode' => $data['productcode'],
				'productname' => $data['productname'],
				'materialtypecode' => $data['materialtypecode'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
				'reqdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['reqdate'])),
        'mesinid' => $data['mesinid'],
        'namamesin' => $data['namamesin'],
        'sloctoid' => $data['sloctoid'],
        'sloccode' => $data['sloccode'],
				'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function actionSearchResult() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'prresultid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('prresult t')
			->leftjoin('prheader g', 'g.prheaderid = t.prheaderid')
			->leftjoin('product a', 'a.productid = t.productid')
						->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')
			->where('t.prheaderid = :prheaderid',
				array(
				':prheaderid' => $id
		))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
			->select('t.*,a.productcode,a.productname,e.materialtypecode,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,t.description,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code,
				GetStdQty2(a.productid) as stdqty2,
						GetStdQty3(a.productid) as stdqty3,
						GetStdQty4(a.productid) as stdqty4,
				(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom4id) as uom4code')
			->from('prresult t')
			->leftjoin('prheader g', 'g.prheaderid = t.prheaderid')
			->leftjoin('product a', 'a.productid = t.productid')
						->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')
			->where('t.prheaderid = :prheaderid', array(
				':prheaderid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		foreach ($cmd as $data) {
      $row[] = array(
        'prresultid' => $data['prresultid'],
        'prheaderid' => $data['prheaderid'],
        'productid' => $data['productid'],
				'productcode' => $data['productcode'],
				'productname' => $data['productname'],
				'materialtypecode' => $data['materialtypecode'],
        'qty' => Yii::app()->format->formatNumber($data['qty1']),
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
				'description' => $data['description']
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
        $sql     = 'call RejectPR(:vid,:vdatauser)';
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
        $sql     = 'call ApprovePR(:vid,:vdatauser)';
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
		$sql = 'call Modifprheader(:vid,:vprdate,:vplantid,:vformrequestid,:vslocfromid,:visjasa,:vrequestedbyid,:vdescription,:vdatauser)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vprdate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vformrequestid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vslocfromid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':visjasa', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vrequestedbyid', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vdescription', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionSave() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['prheader-prheaderid'])?$_POST['prheader-prheaderid']:''),
				date(Yii::app()->params['datetodb'], strtotime($_POST['prheader-prdate'])),
				$_POST['prheader-plantid'],$_POST['prheader-formrequestid'],$_POST['prheader-slocfromid'],(isset($_POST['prheader-isjasa']) ? 1 : 0),
				$_POST['prheader-requestedbyid'],$_POST['prheader-description']));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
	private function ModifyRaw($connection,$arraydata) {
		if ($arraydata[0] == '') {
			$sql     = 'call Insertprraw(:vprheaderid,:vfrrawid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,:vqty4,:vreqdate,
				:vsloctoid,:vnamamesin,:vdescription,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateprraw(:vid,:vprheaderid,:vfrrawid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,:vqty4,
				:vreqdate,:vsloctoid,:vnamamesin,:vdescription,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vprheaderid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vfrrawid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vuom2id', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vuom3id', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vuom4id', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vqty', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vqty2', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vqty3', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vqty4', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vreqdate', $arraydata[12], PDO::PARAM_STR);
		$command->bindvalue(':vsloctoid', $arraydata[13], PDO::PARAM_STR);
		$command->bindvalue(':vnamamesin', $arraydata[14], PDO::PARAM_STR);
		$command->bindvalue(':vdescription', $arraydata[15], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSaveraw() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      $this->ModifyRaw($connection,array(
				(isset($_POST['prrawid'])?$_POST['prrawid']:''),
				$_POST['prheaderid'],
				$_POST['formrequestrawid'],
				$_POST['productid'],
				$_POST['uomid'],
				$_POST['uom2id'],
				$_POST['uom3id'],
				$_POST['uom4id'],
				$_POST['qty'],
				$_POST['qty2'],
				$_POST['qty3'],
				$_POST['qty4'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['reqdate'])),
				$_POST['sloctoid'],
				$_POST['mesinid'],
				$_POST['description']
			));
      $transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
	private function ModifyJasa($connection,$arraydata) {
		if ($arraydata[0] == '') {
			$sql     = 'call InsertPRjasa(:vprheaderid,:vproductid,:vuomid,:vqty,:vreqdate,
				:vsloctoid,:vmesinid,:vdescription,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call UpdatePRjasa(:vid,:vprheaderid,:vproductid,:vuomid,:vqty,:vreqdate,
				:vsloctoid,:vmesinid,:vdescription,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vprheaderid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[3], PDO::PARAM_STR);
	  $command->bindvalue(':vqty', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vreqdate', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vsloctoid', $arraydata[6], PDO::PARAM_STR);
	  $command->bindvalue(':vmesinid', $arraydata[7], PDO::PARAM_STR);
	  $command->bindvalue(':vdescription', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavejasa() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      $this->ModifyJasa($connection,array(
				(isset($_POST['prjasaid'])?$_POST['prjasaid']:''),
				$_POST['prheaderid'],
				$_POST['productid'],
				$_POST['uomid'],
				$_POST['qty'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['reqdate'])),
				$_POST['sloctoid'],
				$_POST['mesinid'],
				$_POST['description']
			));
      $transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
	private function ModifyResult($connection,$arraydata) {
		if ($arraydata[0] == '') {
			$sql     = 'call InsertPRresult(:vprheaderid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,:vqty4,:vdescription,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call UpdatePRresult(:vid,:vprheaderid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,:vqty4,:vdescription,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vprheaderid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vuom2id', $arraydata[4], PDO::PARAM_STR);
	  $command->bindvalue(':vuom3id', $arraydata[5], PDO::PARAM_STR);
	  $command->bindvalue(':vuom4id', $arraydata[6], PDO::PARAM_STR);
	  $command->bindvalue(':vqty', $arraydata[7], PDO::PARAM_STR);
	  $command->bindvalue(':vqty2', $arraydata[8], PDO::PARAM_STR);
	  $command->bindvalue(':vqty3', $arraydata[9], PDO::PARAM_STR);
	  $command->bindvalue(':vqty4', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vdescription', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSaveresult() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      $this->ModifyResult($connection,array(
				(isset($_POST['prresultid'])?$_POST['prresultid']:''),
				$_POST['prheaderid'],
				$_POST['productid'],
				$_POST['uomid'],
				$_POST['uom2id'],
				$_POST['uom3id'],
				$_POST['uom4id'],
				$_POST['qty'],
				$_POST['qty2'],
				$_POST['qty3'],
				$_POST['qty4'],
				$_POST['description']
			));
      $transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-prheader"]["name"]);
		if (move_uploaded_file($_FILES["file-prheader"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$oldid = '';$pid = '';$productplanid= '';
			$connection  = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				for ($row = 3; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue(); //A
					if ((int)$id > 0) {
						if ($oldid != $id) {
							$oldid = $id;
							$oldppid = '';
							$plantcode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue(); //B
							$plantid = Yii::app()->db->createCommand("select plantid from plant where plantcode = '".$plantcode."'")->queryScalar();
							$docdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(2, $row)->getValue())); //C
							$formreqno = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue(); //D
							$formrequestid = Yii::app()->db->createCommand("select formrequestid from formrequest where formrequestno = '".$formreqno."'")->queryScalar();
							$slocfrom = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue(); //E
							$slocfromid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$slocfrom."'")->queryScalar();
							$isjasa = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue(); //F
							$requestedby = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue(); //G
							$requestedbyid = Yii::app()->db->createCommand("select requestedbyid from requestedby where requestedbycode = '".$requestedby."'")->queryScalar();
							$description = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue(); //H
							$this->ModifyData($connection,array(
								-1,
								$docdate,
								$plantid,
								$formrequestid,
								$slocfromid,
								$isjasa,
								$requestedbyid,
								$description));
							$sql = "
								select prheaderid 
								from prheader a
								where a.plantid = ".$plantid." 
								and a.prdate = '".$docdate."' 
								and a.slocfromid = ".$slocfromid." 
								and a.isjasa = ".$isjasa." 
								and a.requestedbyid = ".$requestedbyid." 
								and coalesce(a.description,'') = '".$description."' 
								and a.formrequestid = '".$formrequestid."' 
								limit 1
							";
							$pid = Yii::app()->db->createCommand($sql)->queryScalar();
							//throw new Exception($sql);
						} 
						$productname = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue(); //I
						$sql = "select productid from product where productname = :productname";
						$command=$connection->createCommand($sql);
						$command->bindvalue(':productname',$productname,PDO::PARAM_STR);
						$productid = $command->queryScalar();
						if ($productid > 0) {
							$qty = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue(); //J
							$uomcode = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue(); //K
							$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$qty2 = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue(); //L
							$uomcode = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue(); //M
							$uom2id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$qty3 = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue(); //N
							$uomcode = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue(); //O
							$uom3id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$qty4 = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue(); //P
							$uomcode = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue(); //Q
							$uom4id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$reqdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(17, $row)->getValue())); //R
							$slocto = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue(); //S
							$sloctoid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$slocto."'")->queryScalar();
							$kodemesin = $objWorksheet->getCellByColumnAndRow(19, $row)->getValue(); //T
							$mesinid = Yii::app()->db->createCommand("select mesinid from mesin where kodemesin = '".$kodemesin."'")->queryScalar();
							$itemnote = $objWorksheet->getCellByColumnAndRow(20, $row)->getValue(); //U
							$sql = "
								select formrequestrawid 
								from formrequestraw a
								where a.productid = ".$productid." 
								and a.sloctoid = ".$sloctoid." 
								and a.reqdate = '".$reqdate."' 
								and a.formrequestid = '".$formrequestid."' 
								and coalesce(a.description,'') = '".$itemnote."' 
								limit 1
							";
							$formrequestrawid = $connection->createCommand($sql)->queryScalar();
							$sql = "insert into prraw (prheaderid,formrequestrawid,productid,qty,uomid,qty2,uom2id,qty3,uom3id,qty4,uom4id,reqdate,sloctoid,mesinid,description)
								values (:prheaderid,:formrequestrawid,:productid,:qty,:uomid,:qty2,:uom2id,:qty3,:uom3id,:qty4,:uom4id,:reqdate,:sloctoid,:mesinid,:description)";
							$command = $connection->createCommand($sql);
							$command->bindvalue(':prheaderid',$pid,PDO::PARAM_STR);
							$command->bindvalue(':formrequestrawid',$formrequestrawid,PDO::PARAM_STR);
							$command->bindvalue(':productid',$productid,PDO::PARAM_STR);
							$command->bindvalue(':qty',$qty,PDO::PARAM_STR);
							$command->bindvalue(':uomid',$uomid,PDO::PARAM_STR);
							$command->bindvalue(':qty2',$qty2,PDO::PARAM_STR);
							$command->bindvalue(':uom2id',$uom2id,PDO::PARAM_STR);
							$command->bindvalue(':qty3',$qty3,PDO::PARAM_STR);
							$command->bindvalue(':uom3id',((IsNullOrEmptyString($uom3id) != 1)?$uom3id:null),PDO::PARAM_STR);
							$command->bindvalue(':qty4',$qty4,PDO::PARAM_STR);
							$command->bindvalue(':uom4id',((IsNullOrEmptyString($uom4id) != 1)?$uom4id:null),PDO::PARAM_STR);
							$command->bindvalue(':reqdate',$reqdate,PDO::PARAM_STR);
							$command->bindvalue(':sloctoid',$sloctoid,PDO::PARAM_STR);
							$command->bindvalue(':mesinid',$mesinid,PDO::PARAM_STR);
							$command->bindvalue(':description',$description,PDO::PARAM_STR);
							$command->execute();
						}
						$productname = $objWorksheet->getCellByColumnAndRow(21, $row)->getValue(); //V
						$sql = "select productid from product where productname = :productname";
						$command=$connection->createCommand($sql);
						$command->bindvalue(':productname',$productname,PDO::PARAM_STR);
						$productid = $command->queryScalar();
						if ($productid > 0) {
							$qty = $objWorksheet->getCellByColumnAndRow(22, $row)->getValue(); //W
							$uomcode = $objWorksheet->getCellByColumnAndRow(23, $row)->getValue(); //X
							$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$kodemesin = $objWorksheet->getCellByColumnAndRow(24, $row)->getValue(); //Y
							$mesinid = Yii::app()->db->createCommand("select mesinid from mesin where kodemesin = '".$kodemesin."'")->queryScalar();
							$reqdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(25, $row)->getValue())); //Z
							$slocto = $objWorksheet->getCellByColumnAndRow(26, $row)->getValue(); //AA
							$sloctoid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$slocto."'")->queryScalar();
							$itemnote = $objWorksheet->getCellByColumnAndRow(27, $row)->getValue(); //AB
							$sql = "
								select formrequestjasaid 
								from formrequestjasa a
								where a.productid = ".$productid." 
								and a.sloctoid = ".$sloctoid." 
								and a.reqdate = '".$reqdate."' 
								and a.formrequestid = '".$formrequestid."' 
								and coalesce(a.description,'') = '".$itemnote."' 
								limit 1
							";
							$formrequestrawid = $connection->createCommand($sql)->queryScalar();
							$sql = "insert into prjasa (prheaderid,formrequestjasaid,productid,qty,uomid,reqdate,sloctoid,mesinid,description)
								values (:prheaderid,:formrequestjasaid,:productid,:qty,:uomid,:reqdate,:sloctoid,:mesinid,:description)";
							$command = $connection->createCommand($sql);
							$command->bindvalue(':prheaderid',$pid,PDO::PARAM_STR);
							$command->bindvalue(':formrequestjasaid',$formrequestrawid,PDO::PARAM_STR);
							$command->bindvalue(':productid',$productid,PDO::PARAM_STR);
							$command->bindvalue(':qty',$qty,PDO::PARAM_STR);
							$command->bindvalue(':uomid',$uomid,PDO::PARAM_STR);
							$command->bindvalue(':reqdate',$reqdate,PDO::PARAM_STR);
							$command->bindvalue(':sloctoid',$sloctoid,PDO::PARAM_STR);
							$command->bindvalue(':mesinid',$mesinid,PDO::PARAM_STR);
							$command->bindvalue(':description',$description,PDO::PARAM_STR);
							$command->execute();
						}
						$productname = $objWorksheet->getCellByColumnAndRow(28, $row)->getValue(); //AC
						$sql = "select productid from product where productname = :productname";
						$command=$connection->createCommand($sql);
						$command->bindvalue(':productname',$productname,PDO::PARAM_STR);
						$productid = $command->queryScalar();
						if ($productid > 0) {
							$qty = $objWorksheet->getCellByColumnAndRow(29, $row)->getValue(); //AD
							$uomcode = $objWorksheet->getCellByColumnAndRow(30, $row)->getValue(); //AE
							$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$qty2 = $objWorksheet->getCellByColumnAndRow(31, $row)->getValue(); //AF
							$uomcode = $objWorksheet->getCellByColumnAndRow(32, $row)->getValue(); //AG
							$uom2id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$qty3 = $objWorksheet->getCellByColumnAndRow(33, $row)->getValue(); //AH
							$uomcode = $objWorksheet->getCellByColumnAndRow(34, $row)->getValue(); //AI
							$uom3id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$qty4 = $objWorksheet->getCellByColumnAndRow(35, $row)->getValue(); //AJ
							$uomcode = $objWorksheet->getCellByColumnAndRow(36, $row)->getValue(); //AK
							$uom4id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$itemnote = $objWorksheet->getCellByColumnAndRow(37, $row)->getValue(); //AL
							$formrequestresultid = $connection->createCommand("
								select formrequestresultid
								from formrequestresult a
								where a.productid = ".$productid." 
								and a.mesinid = ".$mesinid." 
								and a.sloctoid = ".$sloctoid." 
								and coalesce(a.itemnote,'') = '".$itemnote."' 
								limit 1
							")->queryScalar();
							$sql = "insert into prresult (prheaderid,formrequestresultid,productid,qty,uomid,qty2,uom2id,qty3,uom3id,qty4,uom4id,description)
								values (:prheaderid,:formrequestresultid,:productid,:qty,:uomid,:reqdate,:sloctoid,:mesinid,:description)";
							$command = $connection->createCommand($sql);
							$command->bindvalue(':prheaderid',$pid,PDO::PARAM_STR);
							$command->bindvalue(':formrequestresultid',$formrequestresultid,PDO::PARAM_STR);
							$command->bindvalue(':productid',$productid,PDO::PARAM_STR);
							$command->bindvalue(':qty',$qty,PDO::PARAM_STR);
							$command->bindvalue(':uomid',$uomid,PDO::PARAM_STR);
							$command->bindvalue(':reqdate',$reqdate,PDO::PARAM_STR);
							$command->bindvalue(':sloctoid',$sloctoid,PDO::PARAM_STR);
							$command->bindvalue(':mesinid',$mesinid,PDO::PARAM_STR);
							$command->bindvalue(':description',$description,PDO::PARAM_STR);
							$command->execute();
						}
					}
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
	public function actionCloseFpp() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call CloseFPP(:vid,:vdatauser)';
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
        $sql     = 'call Purgeprheader(:vid,:vdatauser)';
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
  public function actionPurgeAllDetail() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgepralldetail(:vid,:vdatauser)';
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
  public function actionPurgeraw() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeprraw(:vid,:vdatauser)';
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
  public function actionPurgejasa() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeprjasa(:vid,:vcreatedby)';
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
  public function actionPurgeresult() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeprresult(:vid,:vdatauser)';
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
    $sql = "select a.prheaderid,a.prno,a.prdate,b.sloccode,a.description
						from prheader a
						inner join sloc b on b.slocid = a.slocfromid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.prheaderid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = getCatalog('prheader');
    $this->pdf->AddPage('P', array(
      210,
      330
    ));
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->text(15, $this->pdf->gety(), 'No ');
      $this->pdf->text(35, $this->pdf->gety(), ': ' . $row['prno']);
      $this->pdf->text(70, $this->pdf->gety(), 'Tgl ');
      $this->pdf->text(95, $this->pdf->gety(), ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['prdate'])));
      $this->pdf->text(120, $this->pdf->gety(), 'Gudang ');
      $this->pdf->text(150, $this->pdf->gety(), ': ' . $row['sloccode']);
      $i           = 0;
      $this->pdf->text(15, $this->pdf->gety()+5, 'Bahan Asal');
      $totalqty    = 0;
	  $totalqty2    = 0;
	  $totalqty3    = 0;
	  $totalqty4    = 0;
      $sql1        = "select b.productcode,b.productname,a.qty,a.qty2,a.qty3,a.qty4,c.uomcode,g.uomcode as uom2code,h.uomcode as uom3code,i.uomcode as uom4code,a.description,d.namamesin,e.sloccode,a.reqdate
							from prraw a
							inner join product b on b.productid = a.productid
							inner join unitofmeasure c on c.unitofmeasureid = a.uomid
							left join unitofmeasure g on g.unitofmeasureid = a.uom2id
							left join unitofmeasure h on h.unitofmeasureid = a.uom3id
							left join unitofmeasure i on i.unitofmeasureid = a.uom4id
							left join mesin d on d.mesinid = a.mesinid
							inner join sloc e on e.slocid = a.sloctoid
							where prheaderid = " . $row['prheaderid'] . " order by prrawid ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
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
		20,
		20,
        20,
        15,
        15,
				18,
				18,
				25,
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
		'Qty 2',
		'Qty 3',
		'Qty 4',
        'Mesin',
        'Gudang',
        'Tgl Minta',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
		'R',
		'R',
        'C',
        'R',
        'C',
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
      foreach ($dataReader1 as $row1) {
          $i = $i + 1;
          $this->pdf->row(array(
            $i,
            $row1['productname'],
            Yii::app()->format->formatNumber($row1['qty']).' '.$row1['uomcode'],
			Yii::app()->format->formatNumber($row1['qty2']).' '.$row1['uom2code'],
			Yii::app()->format->formatNumber($row1['qty3']).' '.$row1['uom3code'],
			Yii::app()->format->formatNumber($row1['qty4']).' '.$row1['uom4code'],
            $row1['namamesin'],
						$row1['sloccode'],
						date(Yii::app()->params['dateviewfromdb'], strtotime($row1['reqdate'])),
            $row1['description']
          ));
          $totalqty += $row1['qty'];
		  $totalqty2 += $row1['qty2'];
		  $totalqty3 += $row1['qty3'];
		  $totalqty4 += $row1['qty4'];
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
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $this->pdf->row(array(
        '',
        'TOTAL',
        Yii::app()->format->formatNumber($totalqty),
		Yii::app()->format->formatNumber($totalqty2),
		Yii::app()->format->formatNumber($totalqty3),
		Yii::app()->format->formatNumber($totalqty4),
        '',
        '',
        '',
        ''
      ));
      $this->pdf->text(15, $this->pdf->gety()+5, 'Jasa');
      $i           = 0;
      $totalqty    = 0;
	  $totalqty2    = 0;
	  $totalqty3    = 0;
	  $totalqty4    = 0;
      $sql1        = "select b.productcode,b.productname,a.qty,c.uomcode,a.description,d.namamesin,e.sloccode,a.reqdate
							from prjasa a
							inner join product b on b.productid = a.productid
							inner join unitofmeasure c on c.unitofmeasureid = a.uomid
							left join mesin d on d.mesinid = a.mesinid
							inner join sloc e on e.slocid = a.sloctoid
							where prheaderid = " . $row['prheaderid'] . " order by prjasaid ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
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
        40,
				20,
				20,
				40,
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Mesin',
        'Gudang',
        'Tgl Minta',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
		'R',
		'R',
        'C',
        'R',
        'C',
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
      foreach ($dataReader1 as $row1) {
          $i = $i + 1;
          $this->pdf->row(array(
            $i,
            $row1['productname'],
            Yii::app()->format->formatNumber($row1['qty']).' '.$row1['uomcode'],
            $row1['namamesin'],
						$row1['sloccode'],
						date(Yii::app()->params['dateviewfromdb'], strtotime($row1['reqdate'])),
            $row1['description']
          ));
          $totalqty += $row1['qty'];
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
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $this->pdf->row(array(
        '',
        'TOTAL',
        Yii::app()->format->formatNumber($totalqty),
        '',
        '',
        ''
      ));
			      $this->pdf->text(15, $this->pdf->gety()+5, 'Barang Hasil Jasa');
      $totalqty    = 0;
	  $totalqty2    = 0;
	  $totalqty3    = 0;
	  $totalqty4    = 0;
      $sql1        = "select b.productcode,b.productname,a.qty1,a.qty2,a.qty3,a.qty4,c.uomcode,g.uomcode as uom2code,h.uomcode as uom3code,i.uomcode as uom4code,a.description
							from prresult a
							inner join product b on b.productid = a.productid
							inner join unitofmeasure c on c.unitofmeasureid = a.uomid
							left join unitofmeasure g on g.unitofmeasureid = a.uom2id
							left join unitofmeasure h on h.unitofmeasureid = a.uom3id
							left join unitofmeasure i on i.unitofmeasureid = a.uom4id
							where prheaderid = " . $row['prheaderid'] . " order by prresultid ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
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
		20,
		20,
        15,
        18,
				25,
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
		'Qty 2',
		'Qty 3',
		'Qty 4',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
		'R',
		'R',
        'C',
        'R',
        'C',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      foreach ($dataReader1 as $row1) {
          $i = $i + 1;
          $this->pdf->row(array(
            $i,
            $row1['productname'],
            Yii::app()->format->formatNumber($row1['qty1']).' '.$row1['uomcode'],
			Yii::app()->format->formatNumber($row1['qty2']).' '.$row1['uom2code'],
			Yii::app()->format->formatNumber($row1['qty3']).' '.$row1['uom3code'],
			Yii::app()->format->formatNumber($row1['qty4']).' '.$row1['uom4code'],
            $row1['description']
          ));
          $totalqty += $row1['qty1'];
		  $totalqty2 += $row1['qty2'];
		  $totalqty3 += $row1['qty3'];
		  $totalqty4 += $row1['qty4'];
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
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $this->pdf->row(array(
        '',
        'TOTAL',
        Yii::app()->format->formatNumber($totalqty),
		Yii::app()->format->formatNumber($totalqty2),
		Yii::app()->format->formatNumber($totalqty3),
		Yii::app()->format->formatNumber($totalqty4),
        '',
        ''
      ));
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        30,
        600
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Note',
        $row['description']
      ));
			$this->pdf->checknewpage(30);
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->text(15, $this->pdf->gety(), '  Dibuat oleh,');
      $this->pdf->text(55, $this->pdf->gety(), ' Diperiksa oleh,');
      $this->pdf->text(96, $this->pdf->gety(), ' Diketahui oleh,');
      $this->pdf->text(137, $this->pdf->gety(), '     Disetujui oleh,');
      $this->pdf->text(15, $this->pdf->gety() + 18, '........................');
      $this->pdf->text(55, $this->pdf->gety() + 18, '.........................');
      $this->pdf->text(96, $this->pdf->gety() + 18, '.........................');
      $this->pdf->text(137, $this->pdf->gety() + 18, '.................................');
      $this->pdf->text(15, $this->pdf->gety() + 20, '       Admin');
      $this->pdf->text(55, $this->pdf->gety() + 20, '    Supervisor');
      $this->pdf->text(96, $this->pdf->gety() + 20, 'Chief Accounting');
      $this->pdf->text(137, $this->pdf->gety() + 20, 'Manager Accounting');
    }
    $this->pdf->Output();
  }
  public function actionDownPDFminus() {
    parent::actionDownload();
    $sql = "select a.prheaderid,a.prno,a.prdate,b.sloccode,a.description
						from prheader a
						inner join sloc b on b.slocid = a.slocfromid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.prheaderid in (" . $_GET['id'] . ")";
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
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['prno']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Date ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['prdate'])));
      $this->pdf->text(135, $this->pdf->gety() + 5, 'Gudang ');
      $this->pdf->text(170, $this->pdf->gety() + 5, ': ' . $row['sloccode']);
      $i           = 0;
      $totalqty    = 0;
      $totaljumlah = 0;
      $sql1        = "select * from (select *,qty+qtystock as selisih
						from (select prrawid,d.productname,sum(a.qty) as qty,
						sum(a.qtystdkg) as qtystdkg,sum(a.qtystdmtr) as qtystdmtr,
						ifnull((select ifnull(b.qty,0) from productstock b 
						where b.productid=a.productid 
						and b.slocid=c.slocid 
						and b.unitofmeasureid=a.unitofmeasureid 
						and b.storagebinid=a.storagebinid),0) as qtystock
						from prraw a
						join prheader c on c.prheaderid=a.prheaderid
						join product d on d.productid=a.productid
						where a.prheaderid in (" . $_GET['id'] . ")
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
  public function actionDownxls() {
    $this->menuname = 'prlist';
    parent::actionDownxls();
    $prheaderid = GetSearchText(array('POST','GET','Q'),'prheaderid');
		$plantcode   = GetSearchText(array('POST','GET','Q'),'plantcode');
		$productname   = GetSearchText(array('POST','GET','Q'),'productname');
    $prdate     = GetSearchText(array('POST','GET','Q'),'prdate');
    $sloccode = GetSearchText(array('POST','GET','Q'),'sloccode');
    $prno = GetSearchText(array('POST','GET','Q'),'prno');
    $description = GetSearchText(array('POST','GET','Q'),'description');
		$sql = "select ac.formrequestno,ab.plantcode,a.prheaderid,a.prno,a.prdate,b.sloccode,a.description,aa.requestedbycode,GetStock(c.productid,c.uomid,c.sloctoid) as rawqtystock,
			a.isjasa,d.productname as rawproductname,c.qty as rawqty,e.uomcode as rawuom,c.qty2 as rawqty2,f.uomcode as rawuom2,c.qty3 as rawqty3,g.uomcode as rawuom3,
			c.qty4 as rawqty4,h.uomcode as rawuom4,i.sloccode as rawsloc,j.kodemesin as rawmesin,c.description as rawdescription,c.poqty as rawpoqty,c.poqty2 as rawpoqty2,
			c.reqdate as rawreqdate,l.productname as jasaproductname,k.qty as jasaqty,m.uomcode as jasauomcode,k.reqdate as jasareqdate,k.poqty as jasapoqty,k.description as jasadescription,
			n.kodemesin as jasamesin,o.sloccode as jasasloc
			from prheader a
			left join sloc b on b.slocid = a.slocfromid 
			left join requestedby aa on aa.requestedbyid = a.requestedbyid 
			left join plant ab on ab.plantid = a.plantid 
			left join formrequest ac on ac.formrequestid = a.formrequestid 
			left join prraw c on c.prheaderid = a.prheaderid 
			left join product d on d.productid = c.productid 
			left join unitofmeasure e on e.unitofmeasureid = c.uomid 
			left join unitofmeasure f on f.unitofmeasureid = c.uom2id 
			left join unitofmeasure g on g.unitofmeasureid = c.uom3id 
			left join unitofmeasure h on h.unitofmeasureid = c.uom4id 
			left join sloc i on i.slocid = c.sloctoid 
			left join mesin j on j.mesinid = c.mesinid 
			left join prjasa k on k.prheaderid = a.prheaderid 
			left join product l on l.productid = k.productid 
			left join unitofmeasure m on m.unitofmeasureid = k.uomid 
			left join mesin n on n.mesinid = k.mesinid 
			left join sloc o on o.slocid = k.sloctoid 
		";
		$sql .= " where coalesce(a.prheaderid,'') like '".$prheaderid."' 
			and coalesce(ab.plantcode,'') like '".$plantcode."' 
			and coalesce(a.prdate,'') like '".$prdate."' 
			and coalesce(a.prno,'') like '".$prno."' 
			and coalesce(a.description,'') like '".$description."'".
			(($productname != '%%')?"
				and coalesce(d.productname,'') like '".$productname."'
			":'')
		;
		if ($_GET['id'] !== '') {
      $sql = $sql . " and a.prheaderid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 3;$nourut=0;$oldbom='';
    foreach ($dataReader as $row) {
			if ($oldbom != $row['prheaderid']) {
				$nourut+=1;
				$oldbom = $row['prheaderid'];
			}
      $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i, $nourut)
				->setCellValueByColumnAndRow(1, $i, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $i, date(Yii::app()->params['dateviewfromdb'], strtotime($row['prdate'])))
				->setCellValueByColumnAndRow(3, $i, $row['prno'])
				->setCellValueByColumnAndRow(4, $i, $row['formrequestno'])
				->setCellValueByColumnAndRow(5, $i, $row['isjasa'])
				->setCellValueByColumnAndRow(6, $i, $row['sloccode'])
				->setCellValueByColumnAndRow(7, $i, $row['requestedbycode'])
				->setCellValueByColumnAndRow(8, $i, $row['description'])
				->setCellValueByColumnAndRow(9, $i, $row['rawproductname'])
				->setCellValueByColumnAndRow(10, $i, Yii::app()->format->formatNumber($row['rawqtystock']))
				->setCellValueByColumnAndRow(11, $i, Yii::app()->format->formatNumber($row['rawqty']))
				->setCellValueByColumnAndRow(12, $i, Yii::app()->format->formatNumber($row['rawpoqty']))
				->setCellValueByColumnAndRow(13, $i, $row['rawuom'])
				->setCellValueByColumnAndRow(14, $i, Yii::app()->format->formatNumber($row['rawqty2']))
				->setCellValueByColumnAndRow(15, $i, $row['rawuom2'])
				->setCellValueByColumnAndRow(16, $i, Yii::app()->format->formatNumber($row['rawqty3']))
				->setCellValueByColumnAndRow(17, $i, $row['rawuom3'])
				->setCellValueByColumnAndRow(18, $i, Yii::app()->format->formatNumber($row['rawqty4']))
				->setCellValueByColumnAndRow(19, $i, $row['rawuom4'])
				->setCellValueByColumnAndRow(20, $i, date(Yii::app()->params['dateviewfromdb'], strtotime($row['rawreqdate'])))
				->setCellValueByColumnAndRow(21, $i, $row['rawsloc'])
				->setCellValueByColumnAndRow(22, $i, $row['rawmesin'])
				->setCellValueByColumnAndRow(23, $i, $row['rawdescription'])
				->setCellValueByColumnAndRow(24, $i, $row['jasaproductname'])
				->setCellValueByColumnAndRow(25, $i, Yii::app()->format->formatNumber($row['jasaqty']))
				->setCellValueByColumnAndRow(26, $i, Yii::app()->format->formatNumber($row['jasapoqty']))
				->setCellValueByColumnAndRow(27, $i, $row['jasauomcode'])
				->setCellValueByColumnAndRow(28, $i, $row['jasamesin'])
				->setCellValueByColumnAndRow(29, $i, $row['jasasloc'])
				->setCellValueByColumnAndRow(30, $i, $row['jasadescription'])
			;
			$i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}