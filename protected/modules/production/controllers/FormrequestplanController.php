<?php
class FormrequestplanController extends Controller {
  public $menuname = 'formrequestplan';
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
  public function actionGetData() {
		$id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'formrequestid' => $id,
		));
  }
	public function actionGeneratedetail() {
    if (isset($_POST['id'])) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GeneratePPFP(:vid,:vhid,:vjid,:vkid,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->bindvalue(':vjid', $_POST['jid'], PDO::PARAM_INT);
        $command->bindvalue(':vkid', $_POST['kid'], PDO::PARAM_INT);
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
  public function search() {
    header("Content-Type: application/json");
    $formrequestid 		= Getsearchtext(array('POST','Q'),'formrequestid','','int');
    $plantid 		= Getsearchtext(array('POST','GET'),'plantid',0,'int');
    $plantcode 		= Getsearchtext(array('POST','Q'),'plantcode');
    $productcode 		= Getsearchtext(array('POST','Q'),'productcode');
    $productname 		= Getsearchtext(array('POST','Q'),'productname');
    $productplanid 		= Getsearchtext(array('POST','GET'),'productplanid',0,'int');
    $productplanno 		= Getsearchtext(array('POST','Q'),'productplanno');
    $sloccode 		= Getsearchtext(array('POST','Q'),'sloccode');
    $formrequestdate 		= Getsearchtext(array('POST','Q'),'formrequestdate');
    $formrequestno 		= Getsearchtext(array('POST','Q'),'formrequestno');
    $requestedbyid 		= Getsearchtext(array('POST','GET'),'requestedbyid',0,'int');
    $requestedbycode 		= Getsearchtext(array('POST','Q'),'requestedbycode');
    $description 		= Getsearchtext(array('POST','Q'),'description');
    $recordstatus = GetSearchText(array('POST','Q'),'recordstatus');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','formrequestid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
    if (!isset($_GET['getdata'])) {
			if (isset($_GET['fpp'])) {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('formrequest t')
					->leftjoin('sloc a', 'a.slocid = t.slocfromid')
					->leftjoin('plant b', 'b.plantid = t.plantid')
					->leftjoin('company c', 'c.companyid = b.companyid')
					->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
					->leftjoin('productplan e', 'e.productplanid = t.productplanid')
					->where("
					((coalesce(formrequestid,'') like :formrequestid) 
					or (coalesce(plantcode,'') like :plantcode) 
					or (coalesce(sloccode,'') like :sloccode)
					or (coalesce(productplanno,'') like :productplanno)					
					or (coalesce(formrequestno,'') like :formrequestno) 
					or (coalesce(t.description,'') like :description) 
					or (coalesce(requestedbycode,'') like :requestedbycode) 
					or (coalesce(formrequestdate,'') like :formrequestdate)) 
					and t.formrequestno is not null 
					and t.recordstatus = getwfmaxstatbywfname('appdaok')
					and t.slocfromid in (".getUserObjectValues('sloc').")
					and t.plantid = ".$plantid."
					and t.productplanid is not null					
					and t.formrequestid in (select z.formrequestid 
						from formrequestraw z 
						where z.formrequestid = t.formrequestid and qty > prqty)
					",
				array(
					':formrequestid' => '%' . $formrequestid . '%',
					':plantcode' => '%' . $plantcode . '%',
					':sloccode' => '%' . $sloccode . '%',
					':productplanno' => '%' . $productplanno . '%',
					':formrequestno' => '%' . $formrequestno . '%',
					':description' => '%' . $description . '%',
					':requestedbycode' => '%' . $requestedbycode . '%',
					':formrequestdate' => '%' . $formrequestdate . '%'
				))->queryScalar();
			} else 
			if (isset($_GET['combo'])) {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('formrequest t')
					->leftjoin('sloc a', 'a.slocid = t.slocfromid')
					->leftjoin('plant b', 'b.plantid = t.plantid')
					->leftjoin('company c', 'c.companyid = b.companyid')
					->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
					->leftjoin('productplan e', 'e.productplanid = t.productplanid')
					->where("
					((coalesce(formrequestid,'') like :formrequestid) 
					or (coalesce(plantcode,'') like :plantcode) 
					or (coalesce(productplanno,'') like :productplanno)
					or (coalesce(sloccode,'') like :sloccode) 
					or (coalesce(formrequestno,'') like :formrequestno) 
					or (coalesce(t.description,'') like :description) 
					or (coalesce(requestedbycode,'') like :requestedbycode) 
					or (coalesce(formrequestdate,'') like :formrequestdate)) 
					and t.formrequestno is not null 
					and t.productplanid is not null	
					and t.recordstatus = getwfmaxstatbywfname('appdaok')
					and t.plantid in (".getUserObjectValues('plant').")",
				array(
					':formrequestid' => '%' . $formrequestid . '%',
					':plantcode' => '%' . $plantcode . '%',
					':sloccode' => '%' . $sloccode . '%',
					':productplanno' => '%' . $productplanno . '%',
					':formrequestno' => '%' . $formrequestno . '%',
					':description' => '%' . $description . '%',
					':requestedbycode' => '%' . $requestedbycode . '%',
					':formrequestdate' => '%' . $formrequestdate . '%'
				))->queryScalar();
			} else {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('formrequest t')
					->leftjoin('sloc a', 'a.slocid = t.slocfromid')
					->leftjoin('plant b', 'b.plantid = t.plantid')
					->leftjoin('company c', 'c.companyid = b.companyid')
					->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
					->leftjoin('productplan e', 'e.productplanid = t.productplanid')
					->where("
					((coalesce(t.formrequestid,'') like :formrequestid) 
					and (coalesce(b.plantcode,'') like :plantcode) 
					and (coalesce(a.sloccode,'') like :sloccode)
					and (coalesce(e.productplanno,'') like :productplanno)					
					and (coalesce(t.formrequestno,'') like :formrequestno) 
					and (coalesce(t.description,'') like :description) 
					and (coalesce(d.requestedbycode,'') like :requestedbycode) 
					and (coalesce(t.formrequestdate,'') like :formrequestdate)) 
					and t.recordstatus in (".getUserRecordStatus('listdaok').") 
					and t.formreqtype = 1
					and t.plantid in (".getUserObjectValues('plant').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					" and t.formrequestid in (
						select distinct zz.formrequestid 
						from formrequestraw zz 
						left join product zzz on zzz.productid = zz.productid 
						where zz.sloctoid in (".getUserObjectValues('sloc').")".

					(($productname != '')?"
						and zzz.productname like '%".$productname."%'":'').
					"
						union 
						
						select distinct zz.formrequestid 
						from formrequestjasa zz 
						left join product zzz on zzz.productid = zz.productid 
						where zz.sloctoid in (".getUserObjectValues('sloc').")".

					(($productname != '')?" 
					and zzz.productname like '%".$productname."%'":'').")",
				array(
					':formrequestid' => '%' . $formrequestid . '%',
					':plantcode' => '%' . $plantcode . '%',
					':sloccode' => '%' . $sloccode . '%',
					':productplanno' => '%' . $productplanno . '%',
					':formrequestno' => '%' . $formrequestno . '%',
					':description' => '%' . $description . '%',
					':requestedbycode' => '%' . $requestedbycode . '%',
					':formrequestdate' => '%' . $formrequestdate . '%'
				))->queryScalar();
			}
			$result['total'] = $cmd;
			if (isset($_GET['fpp'])) {
				$cmd = Yii::app()->db->createCommand()->select('t.*,a.sloccode,e.productplanno,b.plantcode,a.description as slocdesc,t.description,b.companyid,c.companyname,d.requestedbycode')
					->from('formrequest t')
					->leftjoin('sloc a', 'a.slocid = t.slocfromid')
					->leftjoin('plant b', 'b.plantid = t.plantid')
					->leftjoin('company c', 'c.companyid = b.companyid')
					->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
					->leftjoin('productplan e', 'e.productplanid = t.productplanid')
					->where("
					((coalesce(formrequestid,'') like :formrequestid) 
					or (coalesce(plantcode,'') like :plantcode) 
					or (coalesce(sloccode,'') like :sloccode)
					or (coalesce(productplanno,'') like :productplanno)					
					or (coalesce(formrequestno,'') like :formrequestno) 
					or (coalesce(t.description,'') like :description) 
					or (coalesce(requestedbycode,'') like :requestedbycode) 
					or (coalesce(formrequestdate,'') like :formrequestdate)) 
					and t.formrequestno is not null 
					and t.recordstatus = getwfmaxstatbywfname('appdaok')
					and t.slocfromid in (".getUserObjectValues('sloc').")
					and t.plantid = ".$plantid." 
					and t.productplanid is not null	
					and t.formrequestid in (select z.formrequestid 
						from formrequestraw z 
						where z.formrequestid = t.formrequestid and qty > prqty)
					",
				array(
					':formrequestid' => '%' . $formrequestid . '%',
					':plantcode' => '%' . $plantcode . '%',
					':sloccode' => '%' . $sloccode . '%',
					':formrequestno' => '%' . $formrequestno . '%',
					':productplanno' => '%' . $productplanno . '%',
					':description' => '%' . $description . '%',
					':requestedbycode' => '%' . $requestedbycode . '%',
					':formrequestdate' => '%' . $formrequestdate . '%'
				))->queryAll();
			} else 
			if (isset($_GET['combo'])) {
				$cmd = Yii::app()->db->createCommand()->select('t.*,a.sloccode,e.productplanno,b.plantcode,a.description as slocdesc,t.description,b.companyid,c.companyname,d.requestedbycode')
					->from('formrequest t')
					->leftjoin('sloc a', 'a.slocid = t.slocfromid')
					->leftjoin('plant b', 'b.plantid = t.plantid')
					->leftjoin('company c', 'c.companyid = b.companyid')
					->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
					->leftjoin('productplan e', 'e.productplanid = t.productplanid')
					->where("
					((coalesce(formrequestid,'') like :formrequestid) 
					or (coalesce(plantcode,'') like :plantcode) 
					or (coalesce(sloccode,'') like :sloccode) 
					or (coalesce(productplanno,'') like :productplanno)
					or (coalesce(formrequestno,'') like :formrequestno) 
					or (coalesce(t.description,'') like :description) 
					or (coalesce(requestedbycode,'') like :requestedbycode) 
					or (coalesce(formrequestdate,'') like :formrequestdate)) 
					and t.formrequestno is not null
					and t.productplanid is not null					
					and t.recordstatus = getwfmaxstatbywfname('appdaok')
					and t.plantid in (".getUserObjectValues('plant').")",
				array(
					':formrequestid' => '%' . $formrequestid . '%',
					':plantcode' => '%' . $plantcode . '%',
					':sloccode' => '%' . $sloccode . '%',
					':productplanno' => '%' . $productplanno . '%',
					':formrequestno' => '%' . $formrequestno . '%',
					':description' => '%' . $description . '%',
					':requestedbycode' => '%' . $requestedbycode . '%',
					':formrequestdate' => '%' . $formrequestdate . '%'
				))->queryAll();
			} else {
				$cmd = Yii::app()->db->createCommand()->select('t.*,a.sloccode,e.productplanno,b.plantcode,a.description as slocdesc,t.description,b.companyid,c.companyname,d.requestedbycode')
					->from('formrequest t')
					->leftjoin('sloc a', 'a.slocid = t.slocfromid')
					->leftjoin('plant b', 'b.plantid = t.plantid')
					->leftjoin('company c', 'c.companyid = b.companyid')
					->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
					->leftjoin('productplan e', 'e.productplanid = t.productplanid')
					->where("
					((coalesce(t.formrequestid,'') like :formrequestid) 
					and (coalesce(b.plantcode,'') like :plantcode) 
					and (coalesce(a.sloccode,'') like :sloccode)
					and (coalesce(e.productplanno,'') like :productplanno)					
					and (coalesce(t.formrequestno,'') like :formrequestno) 
					and (coalesce(t.description,'') like :description) 
					and (coalesce(d.requestedbycode,'') like :requestedbycode) 
					and (coalesce(t.formrequestdate,'') like :formrequestdate)) 
					and t.formreqtype = 1 
					and t.recordstatus in (".getUserRecordStatus('listdaok').")
					and t.plantid in (".getUserObjectValues('plant').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').	
					" and t.formrequestid in (
						select distinct zz.formrequestid 
						from formrequestraw zz 
						left join product zzz on zzz.productid = zz.productid 
						where zz.sloctoid in (".getUserObjectValues('sloc').")".

					(($productname != '')?"
						and zzz.productname like '%".$productname."%'":'').
					"
						union 
						
						select distinct zz.formrequestid 
						from formrequestjasa zz 
						left join product zzz on zzz.productid = zz.productid 
						where zz.sloctoid in (".getUserObjectValues('sloc').")".

					(($productname != '')?" 
					and zzz.productname like '%".$productname."%'":'').")",
				array(
					':formrequestid' => '%' . $formrequestid . '%',
					':plantcode' => '%' . $plantcode . '%',
					':sloccode' => '%' . $sloccode . '%',
					':productplanno' => '%' . $productplanno . '%',
					':formrequestno' => '%' . $formrequestno . '%',
					':description' => '%' . $description . '%',
					':requestedbycode' => '%' . $requestedbycode . '%',
					':formrequestdate' => '%' . $formrequestdate . '%'
					))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
			}
			foreach ($cmd as $data) {
				$row[] = array(
					'formrequestid' => $data['formrequestid'],
					'formrequestdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['formrequestdate'])),
					'formrequestno' => $data['formrequestno'],
					'plantid' => $data['plantid'],
					'plantcode' => $data['plantcode'],
					'productplanid' => $data['productplanid'],
					'productplanno' => $data['productplanno'],
					'companyid' => $data['companyid'],
					'companyname' => $data['companyname'],
					'slocfromid' => $data['slocfromid'],
					'sloccode' => $data['sloccode'],
					'requestedbyid' => $data['requestedbyid'],
					'requestedbycode' => $data['requestedbycode'],
					'isjasa' => $data['isjasa'],
					'description' => $data['description'],
					'recordstatus' => $data['recordstatus'],
					'recordstatusname' => $data['statusname']
				);
			}
			$result = array_merge($result, array(
				'rows' => $row
			));
		} else {
			$cmd = Yii::app()->db->createCommand("
				select a.*,b.plantcode,c.companycode,d.sloccode,e.requestedbycode 
				from formrequest a s
				join plant b on b.plantid = a.plantid 
				join company c on c.companyid = b.companyid 
				join sloc d on d.slocid = a.slocfromid 
				left join requestedby e on e.requestedbyid = a.requestedbyid 
				where a.formrequestid = ".$formrequestid)->queryRow();
			$result = $cmd;
		}
    return CJSON::encode($result);
	}
	public function actionSearchRaw() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'formrequestrawid';
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
					->from('formrequestraw t')
					->leftjoin('formrequest g', 'g.formrequestid = t.formrequestid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('mesin c', 'c.mesinid = t.mesinid')
					->leftjoin('sloc d', 'd.slocid = t.sloctoid')
					->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')
					->where('t.formrequestid = :formrequestid',
					array(
				':formrequestid' => $id
				))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productcode,a.productname,c.namamesin,d.sloccode,t.description,c.kodemesin,e.materialtypecode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code,
						getstdqty(a.productid) as stdqty,
						getstdqty2(a.productid) as stdqty2,
						getstdqty3(a.productid) as stdqty3,
						getstock(a.productid,t.uomid,t.sloctoid) as qtystock
						')
					->from('formrequestraw t')
					->leftjoin('formrequest g', 'g.formrequestid = t.formrequestid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('mesin c', 'c.mesinid = t.mesinid')
					->leftjoin('sloc d', 'd.slocid = t.sloctoid')
					->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')
					->where('t.formrequestid = :formrequestid', array(
		':formrequestid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
			if ($data['qtystock'] >= $data['qty']) {
				$stockcount = 0;
			} else {
				$stockcount = 1;
			}
      $row[] = array(
        'formrequestrawid' => $data['formrequestrawid'],
        'formrequestid' => $data['formrequestid'],
        'productid' => $data['productid'],
				'productcode' => $data['productcode'],
				'productname' => $data['productname'],
				'materialtypecode' => $data['materialtypecode'],
				'stdqty' => Yii::app()->format->formatNumber($data['stdqty']),
				'stdqty2' => Yii::app()->format->formatNumber($data['stdqty2']),
				'stdqty3' => Yii::app()->format->formatNumber($data['stdqty3']),
        'qty' => Yii::app()->format->formatNumber($data['qty']),
				'qty2' => Yii::app()->format->formatNumber($data['qty2']),
				'qty3' => Yii::app()->format->formatNumber($data['qty3']),
				'prqty' => Yii::app()->format->formatNumber($data['prqty']),
				'tsqty' => Yii::app()->format->formatNumber($data['tsqty']),
				'qtystock' => Yii::app()->format->formatNumber($data['qtystock']),
				'stockcount' => $stockcount,
        'uomid' => $data['uomid'],
				'uom2id' => $data['uom2id'],
				'uom3id' => $data['uom3id'],
        'uomcode' => $data['uomcode'],
				'uom2code' => $data['uom2code'],
				'uom3code' => $data['uom3code'],
				'reqdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['reqdate'])),
        'mesinid' => $data['mesinid'],
        'namamesin' => $data['namamesin'],
        'kodemesin' => $data['kodemesin'],
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
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'formrequestjasaid';
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
			->from('formrequestjasa t')
			->leftjoin('formrequest g', 'g.formrequestid = t.formrequestid')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('mesin c', 'c.mesinid = t.mesinid')
			->leftjoin('sloc d', 'd.slocid = t.sloctoid')
			->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')
			->where('t.formrequestid = :formrequestid',
			array(
				':formrequestid' => $id
			))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
					->select('t.*,a.productname,c.namamesin,d.sloccode,t.description,e.materialtypecode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode')
					->from('formrequestjasa t')
					->leftjoin('formrequest g', 'g.formrequestid = t.formrequestid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('mesin c', 'c.mesinid = t.mesinid')
					->leftjoin('sloc d', 'd.slocid = t.sloctoid')
			->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')
					->where('t.formrequestid = :formrequestid', array(
		':formrequestid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'formrequestjasaid' => $data['formrequestjasaid'],
        'formrequestid' => $data['formrequestid'],
        'productid' => $data['productid'],
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
    ;
    echo CJSON::encode($result);
  }
  public function actionSearchResult() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'formrequestresultid';
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
					->from('formrequestresult t')
					->leftjoin('formrequest g', 'g.formrequestid = t.formrequestid')
					->leftjoin('product a', 'a.productid = t.productid')
					->where('t.formrequestid = :formrequestid',
					array(
				':formrequestid' => $id
				))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
					->select('t.*,a.productcode,a.productname,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,t.description,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom4id) as uom4code')
					->from('formrequestresult t')
					->leftjoin('formrequest g', 'g.formrequestid = t.formrequestid')
					->leftjoin('product a', 'a.productid = t.productid')
					->where('t.formrequestid = :formrequestid', array(
		':formrequestid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
	foreach ($cmd as $data) {
      $row[] = array(
        'formrequestresultid' => $data['formrequestresultid'],
        'formrequestid' => $data['formrequestid'],
        'productid' => $data['productid'],
		'productcode' => $data['productcode'],
		'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
		'qty2' => Yii::app()->format->formatNumber($data['qty2']),
		'qty3' => Yii::app()->format->formatNumber($data['qty3']),
        'uomid' => $data['uomid'],
		'uom2id' => $data['uom2id'],
		'uom3id' => $data['uom3id'],
        'uomcode' => $data['uomcode'],
		'uom2code' => $data['uom2code'],
		'uom3code' => $data['uom3code'],
		'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
  public function actionReject() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call RejectFR(:vid,:vdatauser)';
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
        $sql     = 'call ApproveFROK(:vid,:vdatauser)';
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
		$sql = 'call ModifFormrequestplan(:vid,:vformrequestdate,:vplantid,:vproductplanid,:vslocfromid,:visjasa,:vrequestedbyid,:vdescription,:vdatauser)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vformrequestdate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vproductplanid', $arraydata[3], PDO::PARAM_STR);
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
			$this->ModifyData($connection,array(
				(isset($_POST['formrequestplan-formrequestid'])?$_POST['formrequestplan-formrequestid']:''),
				date(Yii::app()->params['datetodb'], strtotime($_POST['formrequestplan-formrequestdate'])),
				$_POST['formrequestplan-plantid'],$_POST['formrequestplan-productplanid'],$_POST['formrequestplan-slocfromid'],(isset($_POST['formrequestplan-isjasa']) ? 1 : 0),
				$_POST['formrequestplan-requestedbyid'],$_POST['formrequestplan-description']
			));
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
			$sql     = 'call Insertfrraw(:vformrequestid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,:vreqdate,
				:vsloctoid,:vnamamesin,:vdescription,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatefrraw(:vid,:vformrequestid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,
				:vreqdate,:vsloctoid,:vnamamesin,:vdescription,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vformrequestid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vuom2id', $arraydata[4], PDO::PARAM_STR);
	  $command->bindvalue(':vuom3id', $arraydata[5], PDO::PARAM_STR);
	  $command->bindvalue(':vqty', $arraydata[7], PDO::PARAM_STR);
	  $command->bindvalue(':vqty2', $arraydata[8], PDO::PARAM_STR);
	  $command->bindvalue(':vqty3', $arraydata[9], PDO::PARAM_STR);
	  $command->bindvalue(':vreqdate', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vsloctoid', $arraydata[12], PDO::PARAM_STR);
	  $command->bindvalue(':vnamamesin', $arraydata[13], PDO::PARAM_STR);
		$command->bindvalue(':vdescription', $arraydata[14], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSaveraw() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyRaw($connection,array(
				(isset($_POST['formrequestrawid'])?$_POST['formrequestrawid']:''),
				$_POST['formrequestid'],
				$_POST['productid'],
				$_POST['uomid'],
				$_POST['uom2id'],
				$_POST['uom3id'],
				$_POST['qty'],
				$_POST['qty2'],
				$_POST['qty3'],
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
			$sql     = 'call InsertFRjasa(:vformrequestid,:vproductid,:vuomid,:vqty,:vreqdate,
				:vsloctoid,:vmesinid,:vdescription,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call UpdateFRjasa(:vid,:vformrequestid,:vproductid,:vuomid,:vqty,:vreqdate,
				:vsloctoid,:vmesinid,:vdescription,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vformrequestid', $arraydata[1], PDO::PARAM_STR);
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
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyJasa($connection,array(
				(isset($_POST['formrequestjasaid'])?$_POST['formrequestjasaid']:''),
				$_POST['formrequestid'],
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
			$sql     = 'call InsertFRresult(:vformrequestid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vqty,:vqty2,:vqty3,:vdescription,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call UpdateFRresult(:vid,:vformrequestid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vqty,:vqty2,:vqty3,:vdescription,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vformrequestid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vuom2id', $arraydata[4], PDO::PARAM_STR);
	  $command->bindvalue(':vuom3id', $arraydata[5], PDO::PARAM_STR);
	  $command->bindvalue(':vqty', $arraydata[7], PDO::PARAM_STR);
	  $command->bindvalue(':vqty2', $arraydata[8], PDO::PARAM_STR);
	  $command->bindvalue(':vqty3', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vdescription', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSaveresult() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyResult($connection,array(
				(isset($_POST['formrequestresultid'])?$_POST['formrequestresultid']:''),
				$_POST['formrequestid'],
				$_POST['productid'],
				$_POST['uomid'],
				$_POST['uom2id'],
				$_POST['uom3id'],
				$_POST['qty'],
				$_POST['qty2'],
				$_POST['qty3'],
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
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-formrequestplan"]["name"]);
		if (move_uploaded_file($_FILES["file-formrequestplan"]["tmp_name"], $target_file)) {
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
							$prodplanno = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue(); //D
							$productplanid = Yii::app()->db->createCommand("select productplanid from productplan where productplanno = '".$prodplanno."'")->queryScalar();
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
								$productplanid,
								$slocfromid,
								$isjasa,
								$requestedbyid,
								$description
							));
							$sql = "
								select formrequestid 
								from formrequest a
								where a.plantid = ".$plantid." 
								and a.formrequestdate = '".$docdate."' 
								and a.slocfromid = ".$slocfromid." 
								and a.isjasa = ".$isjasa." 
								and a.requestedbyid = ".$requestedbyid." 
								and coalesce(a.description,'') = '".$description."' 
								and a.productplanid = '".$productplanid."' 
								and a.formreqtype = 1 
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
							$reqdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(17, $row)->getValue())); //R
							$slocto = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue(); //S
							$sloctoid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$slocto."'")->queryScalar();
							$kodemesin = $objWorksheet->getCellByColumnAndRow(19, $row)->getValue(); //T
							$mesinid = Yii::app()->db->createCommand("select mesinid from mesin where kodemesin = '".$kodemesin."'")->queryScalar();
							$itemnote = $objWorksheet->getCellByColumnAndRow(20, $row)->getValue(); //U
							$sql = "
								select productplandetailid 
								from productplandetail a
								where a.productid = ".$productid." 
								and a.productplanid = ".$productplanid." 
								and a.sloctoid = ".$sloctoid." 
								and coalesce(a.description,'') = '".$itemnote."' 
								limit 1
							";
							$plandetailid = $connection->createCommand($sql)->queryScalar();
							$sql = "insert into formrequestraw (formrequestid,productplandetailid,productid,qty,uomid,qty2,uom2id,qty3,uom3id,reqdate,sloctoid,mesinid,description)
								values (:formrequestid,:productplandetailid,:productid,:qty,:uomid,:qty2,:uom2id,:qty3,:uom3id,:reqdate,:sloctoid,:mesinid,:description)";
							$command = $connection->createCommand($sql);
							$command->bindvalue(':formrequestid',$pid,PDO::PARAM_STR);
							$command->bindvalue(':productplandetailid',$plandetailid,PDO::PARAM_STR);
							$command->bindvalue(':productid',$productid,PDO::PARAM_STR);
							$command->bindvalue(':qty',$qty,PDO::PARAM_STR);
							$command->bindvalue(':uomid',$uomid,PDO::PARAM_STR);
							$command->bindvalue(':qty2',$qty2,PDO::PARAM_STR);
							$command->bindvalue(':uom2id',$uom2id,PDO::PARAM_STR);
							$command->bindvalue(':qty3',$qty3,PDO::PARAM_STR);
							$command->bindvalue(':uom3id',((IsNullOrEmptyString($uom3id) != 1)?$uom3id:null),PDO::PARAM_STR);
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
							$this->ModifyJasa($connection,array(
								'',
								$pid,
								$productid,
								$uomid,
								$qty,
								$reqdate,
								$sloctoid,
								$mesinid,
								$itemnote
							));
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
							$itemnote = $objWorksheet->getCellByColumnAndRow(37, $row)->getValue(); //AL
							$plandetailid = $connection->createCommand("
								select productplanfgid 
								from productplandetail a
								where a.productid = ".$productid." 
								and a.productplanid = ".$productplanid." 
								and a.mesinid = ".$mesinid." 
								and a.sloctoid = ".$sloctoid." 
								and coalesce(a.itemnote,'') = '".$itemnote."' 
								limit 1
							")->queryScalar();
							$connection->createCommand("
								insert into formrequestresult (formrequestid,productplanfgid,productid,qty,uomid,qty2,uom2id,qty3,uom3id,description)
								values ('".$pid."','".$plandetailid."','".$productid."','".$qty."','".$uomid."','".$qty2."','".$uom2id."',
									'".$qty3."','".$uom3id."','".$itemnote."')")->execute();
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
	public function actionCloseFpb() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call CloseFPB(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  public function actionPurge() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeformrequestok(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  public function actionPurgeraw()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeformrequestraw(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  public function actionPurgejasa()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeformrequestjasa(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  public function actionPurgeresult()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeformrequestresult(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
  }
	public function actionPurgeAllDetail() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call purgefralldetail(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
          $command->bindvalue(':vid', $id, PDO::PARAM_STR);
          $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
          $command->execute();
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['titleid'] = GetCatalog('formrequestid');
		$this->dataprint['titleplantcode'] = GetCatalog('plantcode');
		$this->dataprint['titleproductcode'] = GetCatalog('productcode');
		$this->dataprint['titleproductname'] = GetCatalog('productname');
		$this->dataprint['titleproductplanno'] = GetCatalog('productplanno');
		$this->dataprint['titlesloccode'] = GetCatalog('sloccode');
		$this->dataprint['titleformrequestdate'] = GetCatalog('formrequestdate');
		$this->dataprint['titleformrequestno'] = GetCatalog('formrequestno');
		$this->dataprint['titlerequestedbycode'] = GetCatalog('requestedbycode');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titlefullname'] = GetCatalog('customer');
		$this->dataprint['titlesono'] = GetCatalog('sono');
		$this->dataprint['titlepocustno'] = GetCatalog('pocustno');
		$this->dataprint['titlesodate'] = GetCatalog('sodate');
		$this->dataprint['titleproductname'] = GetCatalog('productname');
		$this->dataprint['titleqty'] = GetCatalog('qty');
		$this->dataprint['titleqty2'] = GetCatalog('qty2');
		$this->dataprint['titleqty3'] = GetCatalog('qty3');
		$this->dataprint['titlekodemesin'] = GetCatalog('kodemesin');
		$this->dataprint['titleproductcode'] = GetCatalog('productcode');
		$this->dataprint['titlesloccode'] = GetCatalog('sloccode');
		$this->dataprint['titlenamamesin'] = GetCatalog('namamesin');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['plantcode'] = GetSearchText(array('GET'),'plantcode');
    $this->dataprint['productcode'] = GetSearchText(array('GET'),'productcode');
    $this->dataprint['productname'] = GetSearchText(array('GET'),'productname');
    $this->dataprint['productplanno'] = GetSearchText(array('GET'),'productplanno');
    $this->dataprint['sloccode'] = GetSearchText(array('GET'),'sloccode');
    $this->dataprint['formrequestdate'] = GetSearchText(array('GET'),'formrequestdate');
    $this->dataprint['formrequestno'] = GetSearchText(array('GET'),'formrequestno');
    $this->dataprint['requestedbycode'] = GetSearchText(array('GET'),'requestedbycode');
    $this->dataprint['description'] = GetSearchText(array('GET'),'description');
  }
}
