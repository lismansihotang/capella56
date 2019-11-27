<?php
class RepformreqplanController extends Controller {
  public $menuname = 'repformreqplan';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header("Content-Type: application/json");
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
}
