<?php
class ReportbsController extends Controller {
  public $menuname = 'reportbs';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $bsheaderid = GetSearchText(array('POST','GET','Q'),'bsheaderid','','int');
		$plantcode     = GetSearchText(array('POST','GET','Q'),'plantcode');
		$productname   = GetSearchText(array('POST','GET','Q'),'productname');
    $sloccode     = GetSearchText(array('POST','GET','Q'),'sloccode');
    $bsdate     = GetSearchText(array('POST','GET','Q'),'bsdate');
    $bsheaderno = GetSearchText(array('POST','GET','Q'),'bsheaderno');
    $headernote = GetSearchText(array('POST','GET','Q'),'headernote');
    $recordstatus = GetSearchText(array('POST','GET','Q'),'recordstatus');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','bsheaderid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('bsheader t')
			->leftjoin('sloc a', 'a.slocid = t.slocid')
			->leftjoin('plant b', 'b.plantid = t.plantid')
			->leftjoin('company c', 'c.companyid = b.companyid')
			->where("
			((coalesce(bsheaderid,'') like :bsheaderid) 
			and (coalesce(plantcode,'') like :plantcode) 
			and (coalesce(sloccode,'') like :sloccode) 
			and (coalesce(bsheaderno,'') like :bsheaderno) 
			and (coalesce(headernote,'') like :headernote) 
			and (coalesce(bsdate,'') like :bsdate))
			and t.slocid in (".getUserObjectValues('sloc').")".
			(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
			(($productname !== '%%')?"
				and t.bsheaderid in (
					select distinct z.bsheaderid 
					from bsdetail z 
					join product za on za.productid = z.productid 
					where coalesce(za.productname,'') like '".$productname."'
				)
			":''), 
			array(
				':bsheaderid' => '%' . $bsheaderid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':sloccode' => '%' . $sloccode . '%',
				':bsheaderno' => '%' . $bsheaderno . '%',
				':headernote' => '%' . $headernote . '%',
				':bsdate' => '%' . $bsdate . '%'
			))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()->select('t.*,a.sloccode,b.plantcode,a.description as slocdesc,b.companyid,c.companyname')
			->from('bsheader t')
			->leftjoin('sloc a', 'a.slocid = t.slocid')
			->leftjoin('plant b', 'b.plantid = t.plantid')
			 ->leftjoin('company c', 'c.companyid = b.companyid')
			->where("
			((coalesce(bsheaderid,'') like :bsheaderid) 
			and (coalesce(plantcode,'') like :plantcode) 
			and (coalesce(sloccode,'') like :sloccode) 
			and (coalesce(bsheaderno,'') like :bsheaderno) 
			and (coalesce(headernote,'') like :headernote) 
			and (coalesce(bsdate,'') like :bsdate))
			and t.slocid in (".getUserObjectValues('sloc').") ".
			(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
			(($productname !== '%%')?"
				and t.bsheaderid in (
					select distinct z.bsheaderid 
					from bsdetail z 
					join product za on za.productid = z.productid 
					where coalesce(za.productname,'') like '".$productname."'
				)
			":''), 
			array(
				':bsheaderid' => '%' . $bsheaderid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':sloccode' => '%' . $sloccode . '%',
				':bsheaderno' => '%' . $bsheaderno . '%',
				':headernote' => '%' . $headernote . '%',
				':bsdate' => '%' . $bsdate . '%'
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		foreach ($cmd as $data) {
			$row[] = array(
				'bsheaderid' => $data['bsheaderid'],
				'plantid' => $data['plantid'],
				'plantcode' => $data['plantcode'],
				'companyid' => $data['companyid'],
				'companyname' => $data['companyname'],
				'slocid' => $data['slocid'],
				'sloccode' => $data['sloccode'],
				'slocdesc' => $data['slocdesc'],
				'bsdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['bsdate'])),
				'bsheaderno' => $data['bsheaderno'],
				'headernote' => $data['headernote'],
				'recordstatus' => $data['recordstatus'],
				'recordstatusbsheader' => $data['statusname']
			);
		}
    $result = array_merge($result, array(
      'rows' => $row
	));
    return CJSON::encode($result);
	}
}