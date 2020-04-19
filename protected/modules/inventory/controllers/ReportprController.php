<?php
class ReportprController extends Controller {
  public $menuname = 'reportpr';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
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
				)":'')
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
    $result['total'] = $cmd;
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
				)":'')
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
        'sloccode' => $data['sloccode'] . ' - ' . $data['slocdesc'],
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
}