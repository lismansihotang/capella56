<?php
class RepformreqplanController extends Controller {
  public $menuname = 'repformreqplan';
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
  public function search() {
    header('Content-Type: application/json');
    $formrequestid 		= Getsearchtext(array('POST','Q'),'formrequestid','','int');
    $plantcode 		= Getsearchtext(array('POST','Q'),'plantcode');
    $productcode 		= Getsearchtext(array('POST','Q'),'productcode');
    $productname 		= Getsearchtext(array('POST','Q'),'productname');
    $productplanno 		= Getsearchtext(array('POST','Q'),'productplanno');
    $sloccode 		= Getsearchtext(array('POST','Q'),'sloccode');
    $formrequestdate 		= Getsearchtext(array('POST','Q'),'formrequestdate');
    $formrequestno 		= Getsearchtext(array('POST','Q'),'formrequestno');
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
		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('formrequest t')
			->leftjoin('sloc a', 'a.slocid = t.slocfromid')
			->leftjoin('plant b', 'b.plantid = t.plantid')
			->leftjoin('company c', 'c.companyid = b.companyid')
			->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
			->leftjoin('productplan e', 'e.productplanid = t.productplanid')
			->where("
			((coalesce(formrequestid,'') like :formrequestid) 
			and (coalesce(plantcode,'') like :plantcode) 
			and (coalesce(sloccode,'') like :sloccode)
			and (coalesce(productplanno,'') like :productplanno)					
			and (coalesce(formrequestno,'') like :formrequestno) 
			and (coalesce(t.description,'') like :description) 
			and (coalesce(requestedbycode,'') like :requestedbycode) 
			and (coalesce(formrequestdate,'') like :formrequestdate))  
			and t.formreqtype = 1
			and t.plantid in (".getUserObjectValues('plant').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					" 			
			and t.formrequestid in (
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
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()->select('t.*,a.sloccode,e.productplanno,b.plantcode,a.description as slocdesc,t.description,b.companyid,c.companyname,d.requestedbycode')
			->from('formrequest t')
			->leftjoin('sloc a', 'a.slocid = t.slocfromid')
			->leftjoin('plant b', 'b.plantid = t.plantid')
			->leftjoin('company c', 'c.companyid = b.companyid')
			->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
			->leftjoin('productplan e', 'e.productplanid = t.productplanid')
			->where("
			((coalesce(formrequestid,'') like :formrequestid) 
			and (coalesce(plantcode,'') like :plantcode) 
			and (coalesce(sloccode,'') like :sloccode) and
			(coalesce(productplanno,'') like :productplanno) and					
			(coalesce(formrequestno,'') like :formrequestno) and 
			(coalesce(t.description,'') like :description) and
			(coalesce(requestedbycode,'') like :requestedbycode) and
			(coalesce(formrequestdate,'') like :formrequestdate)) 
			and t.formreqtype = 1
			and t.plantid in (".getUserObjectValues('plant').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					" 
			and t.formrequestid in (
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
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom4id) as uom4code,
						getstdqty2(a.productid) as stdqty2,
						getstdqty3(a.productid) as stdqty3,
						getstdqty4(a.productid) as stdqty4,
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
				'stdqty2' => Yii::app()->format->formatNumber($data['stdqty2']),
				'stdqty3' => Yii::app()->format->formatNumber($data['stdqty3']),
				'stdqty4' => Yii::app()->format->formatNumber($data['stdqty4']),
        'qty' => Yii::app()->format->formatNumber($data['qty']),
				'qty2' => Yii::app()->format->formatNumber($data['qty2']),
				'qty3' => Yii::app()->format->formatNumber($data['qty3']),
				'qty4' => Yii::app()->format->formatNumber($data['qty4']),
				'prqty' => Yii::app()->format->formatNumber($data['prqty']),
				'tsqty' => Yii::app()->format->formatNumber($data['tsqty']),
				'qtystock' => Yii::app()->format->formatNumber($data['qtystock']),
				'stockcount' => $stockcount,
        'uomid' => $data['uomid'],
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
    header('Content-Type: application/json');
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
					->select('t.*,a.productcode,a.productname,c.namamesin,d.sloccode,t.description,
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
    ;
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
		'qty4' => Yii::app()->format->formatNumber($data['qty4']),
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
    ;
    echo CJSON::encode($result);
  }
}