<?php
class ReportfrController extends Controller {
  public $menuname = 'reportfr';
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
  public function search() {
    header('Content-Type: application/json');
     $formrequestid 		= GetSearchText(array('POST','GET','Q'),'formrequestid','','int');
		$plantcode     		= GetSearchText(array('POST','GET','Q'),'plantcode');
		$productname     		= GetSearchText(array('POST','GET','Q'),'productname');
		$formrequestno   		= GetSearchText(array('POST','GET','Q'),'formrequestno');
		$requestedbycode   		= GetSearchText(array('POST','GET','Q'),'requestedbycode');
		$formrequestdate    = GetSearchText(array('POST','GET','Q'),'formrequestdate');
		$sloccode 		= GetSearchText(array('POST','GET','Q'),'sloccode');
		$description      = GetSearchText(array('POST','GET','Q'),'description');
		$recordstatus      = GetSearchText(array('POST','GET','Q'),'recordstatus');
		$isjasa   		= GetSearchText(array('GET'),'isjasa',0,'int');
		$isretur   		= GetSearchText(array('GET'),'isretur',0,'int');
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
			->where("
			((coalesce(formrequestid,'') like :formrequestid) 
			and (coalesce(plantcode,'') like :plantcode) 
			and (coalesce(sloccode,'') like :sloccode) 
			and (coalesce(formrequestno,'') like :formrequestno) 
			and (coalesce(t.description,'') like :description) 
			and (coalesce(requestedbycode,'') like :requestedbycode) 
			and (coalesce(formrequestdate,'') like :formrequestdate))
			and t.plantid in (".getUserObjectValues('plant').")
			and t.formrequestid in (
						select distinct zz.formrequestid 
						from formrequestraw zz 
						left join product zzz on zzz.productid = zz.productid 
						where zz.sloctoid in (".getUserObjectValues('sloc').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
					(($productname != '')?"	and zzz.productname like '%".$productname."%'":'').	"
						union 
						
						select distinct zz.formrequestid 
						from formrequestjasa zz 
						left join product zzz on zzz.productid = zz.productid 
						where zz.sloctoid in (".getUserObjectValues('sloc').")".

					(($productname != '')?" and zzz.productname like '%".$productname."%'":'').")",
			array(
				':formrequestid' => '%' . $formrequestid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':sloccode' => '%' . $sloccode . '%',
				':formrequestno' => '%' . $formrequestno . '%',
				':description' => '%' . $description . '%',
				':requestedbycode' => '%' . $requestedbycode . '%',
				':formrequestdate' => '%' . $formrequestdate . '%'
			))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()->select('t.*,a.sloccode,b.plantcode,a.description as slocdesc,b.companyid,c.companyname,d.requestedbycode,t.description')
			->from('formrequest t')
			->leftjoin('sloc a', 'a.slocid = t.slocfromid')
			->leftjoin('plant b', 'b.plantid = t.plantid')
			->leftjoin('company c', 'c.companyid = b.companyid')
			->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
			->where("((coalesce(formrequestid,'') like :formrequestid) 
				and (coalesce(plantcode,'') like :plantcode) 
				and (coalesce(sloccode,'') like :sloccode) 
				and (coalesce(formrequestno,'') like :formrequestno) 
				and (coalesce(t.description,'') like :description) 
				and (coalesce(requestedbycode,'') like :requestedbycode) 
				and (coalesce(formrequestdate,'') like :formrequestdate))
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
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productcode,a.productname,c.namamesin,d.sloccode,t.description,e.materialtypecode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code,
						GetStdQty2(a.productid) as stdqty2,
						GetStdQty3(a.productid) as stdqty3,
						GetStdQty4(a.productid) as stdqty4,
						GetStock(a.productid,t.uomid,t.sloctoid) as qtystock,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom4id) as uom4code')
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
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'prqty' => Yii::app()->format->formatNumber($data['prqty']),
        'tsqty' => Yii::app()->format->formatNumber($data['tsqty']),
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
}