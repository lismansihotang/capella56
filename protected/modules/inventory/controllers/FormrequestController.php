<?php
class FormrequestController extends Controller {
  public $menuname = 'formrequest';
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
  public function search() {
    header("Content-Type: application/json");
    $formrequestid 		= GetSearchText(array('POST','GET','Q'),'formrequestid','','int');
		$plantid     		= GetSearchText(array('POST','GET'),'plantid',0,'int');
		$plantcode     		= GetSearchText(array('POST','Q'),'plantcode');
		$productname     		= GetSearchText(array('POST','Q'),'productname');
		$formrequestno   		= GetSearchText(array('POST','Q'),'formrequestno');
		$requestedbycode   		= GetSearchText(array('POST','Q'),'requestedbycode');
		$formrequestdate    = GetSearchText(array('POST','Q'),'formrequestdate');
		$sloccode 		= GetSearchText(array('POST','Q'),'sloccode');
		$description      = GetSearchText(array('POST','Q'),'description');
		$recordstatus      = GetSearchText(array('POST','Q'),'recordstatus');
		$isjasa   		= GetSearchText(array('POST','GET'),'isjasa',0,'int');
		$isretur   		= GetSearchText(array('POST','GET'),'isretur',0,'int');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','formrequestid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		if (!isset($_GET['getdataTS'])) {
    if (!isset($_GET['getdata'])) {
			if (isset($_GET['fpp'])) {
				if ($isjasa == '0') {
					$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
						->from('formrequest t')
						->leftjoin('sloc a', 'a.slocid = t.slocfromid')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
						->where("
						((coalesce(t.formrequestid,'') like :formrequestid) 
						or (coalesce(b.plantcode,'') like :plantcode) 
						or (coalesce(a.sloccode,'') like :sloccode) 
						or (coalesce(t.formrequestno,'') like :formrequestno) 
						or (coalesce(t.description,'') like :description) 
						or (coalesce(requestedbycode,'') like :requestedbycode) 
						or (coalesce(formrequestdate,'') like :formrequestdate)) 
						and t.formrequestno is not null 
						and t.recordstatus in (".getUserRecordStatus('listda').")
						and b.plantid in (".getUserObjectValues('plant').")".
(($plantid != '')? "and t.plantid = ".$plantid:'')."
						and t.isjasa = 0
						",
					array(
						':formrequestid' => '%' . $formrequestid . '%',
						':plantcode' => '%' . $plantcode . '%',
						':sloccode' => '%' . $sloccode . '%',
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
						->where("
						((coalesce(formrequestid,'') like :formrequestid) 
						or (coalesce(plantcode,'') like :plantcode) 
						or (coalesce(sloccode,'') like :sloccode) 
						or (coalesce(t.formrequestno,'') like :formrequestno) 
						or (coalesce(t.description,'') like :description) 
						or (coalesce(requestedbycode,'') like :requestedbycode) 
						or (coalesce(formrequestdate,'') like :formrequestdate)) 
						and t.formrequestno is not null 
						and t.recordstatus in (".getUserRecordStatus('listda').")
						and b.plantid in (".getUserObjectValues('plant').")".
(($plantid != '')? "and t.plantid = ".$plantid:'')."						
and t.isjasa = 1
						",
					array(
						':formrequestid' => '%' . $formrequestid . '%',
						':plantcode' => '%' . $plantcode . '%',
						':sloccode' => '%' . $sloccode . '%',
						':formrequestno' => '%' . $formrequestno . '%',
						':description' => '%' . $description . '%',
						':requestedbycode' => '%' . $requestedbycode . '%',
						':formrequestdate' => '%' . $formrequestdate . '%'
					))->queryScalar();
				}
			} else 
				if (isset($_GET['frts'])) {
				if ($isretur == '0') {
					$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
						->from('formrequest t')
						->leftjoin('sloc a', 'a.slocid = t.slocfromid')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
						->where("
						(
						(coalesce(t.formrequestno,'') like :formrequestno) 
						or (coalesce(t.formrequestid,'') like :formrequestid) 
						) 
						and t.formrequestno is not null 
						and t.recordstatus in (".getUserRecordStatus('listda').")
						and t.slocfromid in (".getUserObjectValues('sloc').")".
(($plantid != '')? "and t.plantid = ".$plantid:'')."
						and t.formrequestid in (
										select zz.formrequestid
										from formrequestraw zz
										where zz.qty > zz.tsqty and zz.formrequestid > 0)
										and t.isjasa = 0
										
										union
										
						select count(1) as total
						from formrequest zt
						left join sloc za on za.slocid = zt.slocfromid
						left join plant zb on zb.plantid = zt.plantid
						left join company zc on zc.companyid = zb.companyid
						left join requestedby zd on zd.requestedbyid = zt.requestedbyid
						where
						(
						(coalesce(zt.formrequestno,'') like :formrequestno) 
						or (coalesce(zt.formrequestid,'') like :formrequestid) 
						) 
						and zt.formrequestno is not null 
						and zt.recordstatus in (".getUserRecordStatus('listda').")
						and zt.slocfromid in (".getUserObjectValues('sloc').")". 
(($plantid != '')? "and zt.plantid = ".$plantid:'')."
						and zt.formrequestid in (
										select zz.formrequestid
										from formrequestjasa zz
										where zz.qty > zz.tsqty and zz.formrequestid > 0)
										and zt.isjasa = 1		
						",
					array(
						':formrequestno' => '%' . $formrequestno . '%',
						':formrequestid' => '%' . $formrequestid . '%',
					))->queryScalar();
				} else {
					$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
						->from('formrequest t')
						->leftjoin('sloc a', 'a.slocid = t.slocfromid')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
						->where("
						((coalesce(t.formrequestno,'') like :formrequestno) 
						or (coalesce(plantcode,'') like :plantcode) 
						or (coalesce(sloccode,'') like :sloccode) 
						or (coalesce(t.formrequestid,'') like :formrequestid) 
						or (coalesce(t.description,'') like :description) 
						or (coalesce(requestedbycode,'') like :requestedbycode) 
						or (coalesce(formrequestdate,'') like :formrequestdate)) 
						and t.formrequestno is not null 
						and t.recordstatus in (".getUserRecordStatus('listda').")
						and t.slocfromid in (".getUserObjectValues('sloc').")".
(($plantid != '')? "and t.plantid = ".$plantid:'')."
						and t.formrequestid in (
										select zz.formrequestid
										from formrequestraw zz
										where zz.tsqty > 0 and zz.formrequestid > 0)
										and t.isjasa = 0
										
										union
										
						select count(1) as total
						from formrequest zt
						left join sloc za on za.slocid = zt.slocfromid
						left join plant zb on zb.plantid = zt.plantid
						left join company zc on zc.companyid = zb.companyid
						left join requestedby zd on zd.requestedbyid = zt.requestedbyid
						where
						zt.formrequestno is not null 
						and zt.recordstatus in (".getUserRecordStatus('listda').")
						and zt.slocfromid in (".getUserObjectValues('sloc').")".
(($plantid != '')? "and zt.plantid = ".$plantid:'')."
						and zt.formrequestid in (
										select zz.formrequestid
										from formrequestjasa zz
										where zz.tsqty > 0 and zz.formrequestid > 0)
										and zt.isjasa = 1		
						",
					array(
						':formrequestid' => '%' . $formrequestid . '%',
						':plantcode' => '%' . $plantcode . '%',
						':sloccode' => '%' . $sloccode . '%',
						':formrequestno' => '%' . $formrequestno . '%',
						':description' => '%' . $description . '%',
						':requestedbycode' => '%' . $requestedbycode . '%',
						':formrequestdate' => '%' . $formrequestdate . '%'
					))->queryScalar();
				} }
				else
			if (isset($_GET['combo'])) {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('formrequest t')
					->leftjoin('sloc a', 'a.slocid = t.slocfromid')
					->leftjoin('plant b', 'b.plantid = t.plantid')
					->leftjoin('company c', 'c.companyid = b.companyid')
					->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
					->where("
					((coalesce(formrequestid,'') like :formrequestid) 
					or (coalesce(plantcode,'') like :plantcode) 
					or (coalesce(sloccode,'') like :sloccode) 
					or (coalesce(t.formrequestno,'') like :formrequestno) 
					or (coalesce(t.description,'') like :description) 
					or (coalesce(requestedbycode,'') like :requestedbycode) 
					or (coalesce(formrequestdate,'') like :formrequestdate)) 
					and t.formrequestno is not null 
					and t.recordstatus in (".getUserRecordStatus('listda').")
					",
				array(
					':formrequestid' => '%' . $formrequestid . '%',
					':plantcode' => '%' . $plantcode . '%',
					':sloccode' => '%' . $sloccode . '%',
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
					->where("
					((coalesce(formrequestid,'') like :formrequestid) 
					and (coalesce(plantcode,'') like :plantcode) 
					and (coalesce(sloccode,'') like :sloccode) 
					and (coalesce(t.formrequestno,'') like :formrequestno) 
					and (coalesce(t.description,'') like :description) 
					and (coalesce(requestedbycode,'') like :requestedbycode) 
					and (coalesce(formrequestdate,'') like :formrequestdate))
					and t.formreqtype = 0					
					and t.recordstatus in (".getUserRecordStatus('listda').") 
					and t.plantid in (".getUserObjectValues('plant').") 
					and t.useraccessid in (".getUserObjectValues('useraccess').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				"
					and t.formrequestid in (
						select distinct zz.formrequestid 
						from formrequestraw zz 
						left join product zzz on zzz.productid = zz.productid 
						where zz.sloctoid in (".getUserObjectValues('sloc').")".

					(($productname != '%%')?"
						and zzz.productname like '%".$productname."%'":'').
					"
						union 
						
						select distinct zz.formrequestid 
						from formrequestjasa zz 
						left join product zzz on zzz.productid = zz.productid 
						where zz.sloctoid in (".getUserObjectValues('sloc').")".

					(($productname != '%%')?"
						and zzz.productname like '%".$productname."%'":'').")",
				array(
					':formrequestid' => '%' . $formrequestid . '%',
					':plantcode' => '%' . $plantcode . '%',
					':sloccode' => '%' . $sloccode . '%',
					':formrequestno' => '%' . $formrequestno . '%',
					':description' => '%' . $description . '%',
					':requestedbycode' => '%' . $requestedbycode . '%',
					':formrequestdate' => '%' . $formrequestdate . '%'
				))->queryScalar();
				}
			$result['total'] = $cmd;
			if (isset($_GET['fpp'])) {
				if ($isjasa == '0') {
					$cmd = Yii::app()->db->createCommand()->select('t.*,a.sloccode,b.plantcode,a.description as slocdesc,t.description,b.companyid,c.companyname,d.requestedbycode')
						->from('formrequest t')
						->leftjoin('sloc a', 'a.slocid = t.slocfromid')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
						->where("
						((coalesce(formrequestid,'') like :formrequestid) 
						or (coalesce(plantcode,'') like :plantcode) 
						or (coalesce(sloccode,'') like :sloccode) 
						or (coalesce(t.formrequestno,'') like :formrequestno) 
						or (coalesce(t.description,'') like :description) 
						or (coalesce(requestedbycode,'') like :requestedbycode) 
						or (coalesce(formrequestdate,'') like :formrequestdate)) 
						and t.formrequestno is not null 
						and t.recordstatus in (".getUserRecordStatus('listda').")
						and b.plantid in (".getUserObjectValues('plant').")".
(($plantid != '')? "and t.plantid = ".$plantid:'')."
						and t.isjasa = 0
						",
					array(
						':formrequestid' => '%' . $formrequestid . '%',
						':plantcode' => '%' . $plantcode . '%',
						':sloccode' => '%' . $sloccode . '%',
						':formrequestno' => '%' . $formrequestno . '%',
						':description' => '%' . $description . '%',
						':requestedbycode' => '%' . $requestedbycode . '%',
						':formrequestdate' => '%' . $formrequestdate . '%'
					))->order($sort . ' ' . $order)->queryAll();
				} else {
					$cmd = Yii::app()->db->createCommand()->select('t.*,a.sloccode,b.plantcode,a.description as slocdesc,t.description,b.companyid,c.companyname,d.requestedbycode')
						->from('formrequest t')
						->leftjoin('sloc a', 'a.slocid = t.slocfromid')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
						->where("
						((coalesce(formrequestid,'') like :formrequestid) 
						or (coalesce(plantcode,'') like :plantcode) 
						or (coalesce(sloccode,'') like :sloccode) 
						or (coalesce(t.formrequestno,'') like :formrequestno) 
						or (coalesce(t.description,'') like :description) 
						or (coalesce(requestedbycode,'') like :requestedbycode) 
						or (coalesce(formrequestdate,'') like :formrequestdate)) 
						and t.formrequestno is not null 
						and t.recordstatus in (".getUserRecordStatus('listda').")
						and b.plantid in (".getUserObjectValues('plant').")".
(($plantid != '')? "and t.plantid = ".$plantid:'')."						
and t.isjasa = 1
						",
					array(
						':formrequestid' => '%' . $formrequestid . '%',
						':plantcode' => '%' . $plantcode . '%',
						':sloccode' => '%' . $sloccode . '%',
						':formrequestno' => '%' . $formrequestno . '%',
						':description' => '%' . $description . '%',
						':requestedbycode' => '%' . $requestedbycode . '%',
						':formrequestdate' => '%' . $formrequestdate . '%'
					))->order($sort . ' ' . $order)->queryAll();
				}
			} else 
				if (isset($_GET['frts'])) {
				if ($isretur == '0') {
					$cmd = Yii::app()->db->createCommand()->select('t.*,a.sloccode,b.plantcode,a.description as slocdesc,c.companyid,c.companyname,d.requestedbycode')
						->from('formrequest t')
						->leftjoin('sloc a', 'a.slocid = t.slocfromid')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
						->where("
						(
						(coalesce(t.formrequestno,'') like :formrequestno) 
						or (coalesce(t.formrequestid,'') like :formrequestid) 
						) 
						and t.formrequestno is not null 
						and t.recordstatus in (".getUserRecordStatus('listda').")
						and t.slocfromid in (".getUserObjectValues('sloc').")".
(($plantid != '')? "and t.plantid = ".$plantid:'')."						and t.formrequestid in (
										select zz.formrequestid
										from formrequestraw zz
										where zz.qty > zz.tsqty and zz.formrequestid > 0)
										and t.isjasa = 0
										
										union
										
						select zt.*,za.sloccode,zb.plantcode,za.description as slocdesc,zc.companyid,zc.companyname,zd.requestedbycode
						from formrequest zt
						left join sloc za on za.slocid = zt.slocfromid
						left join plant zb on zb.plantid = zt.plantid
						left join company zc on zc.companyid = zb.companyid
						left join requestedby zd on zd.requestedbyid = zt.requestedbyid
						where
						(
						(coalesce(zt.formrequestno,'') like :formrequestno) 
						or (coalesce(zt.formrequestid,'') like :formrequestid) 
						) 
						and zt.formrequestno is not null 
						and zt.recordstatus in (".getUserRecordStatus('listda').")
						and zt.slocfromid in (".getUserObjectValues('sloc').")".
(($plantid != '')? "and zt.plantid = ".$plantid:'')."						and zt.formrequestid in (
										select zz.formrequestid
										from formrequestjasa zz
										where zz.qty > zz.tsqty and zz.formrequestid > 0)
										and zt.isjasa = 1		
						",
					array(
						':formrequestno' => '%' . $formrequestno . '%',
						':formrequestid' => '%' . $formrequestid . '%',
					))->queryAll();
				} else {
					$cmd = Yii::app()->db->createCommand()->select('t.*,a.sloccode,b.plantcode,a.description as slocdesc,c.companyid,c.companyname,d.requestedbycode')
						->from('formrequest t')
						->leftjoin('sloc a', 'a.slocid = t.slocfromid')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
						->where("
						((coalesce(t.formrequestno,'') like :formrequestno) 
						or (coalesce(plantcode,'') like :plantcode) 
						or (coalesce(sloccode,'') like :sloccode) 
						or (coalesce(t.formrequestid,'') like :formrequestid) 
						or (coalesce(t.description,'') like :description) 
						or (coalesce(requestedbycode,'') like :requestedbycode) 
						or (coalesce(formrequestdate,'') like :formrequestdate)) 
						and t.formrequestno is not null 
						and t.recordstatus in (".getUserRecordStatus('listda').")
						and t.slocfromid in (".getUserObjectValues('sloc').")".
(($plantid != '')? "and t.plantid = ".$plantid:'')."						and t.formrequestid in (
										select zz.formrequestid
										from formrequestraw zz
										where zz.tsqty > 0 and zz.formrequestid > 0)
										and t.isjasa = 0
										
										union
										
						select zt.*,za.sloccode,zb.plantcode,za.description as slocdesc,zc.companyid,zc.companyname,zd.requestedbycode
						from formrequest zt
						left join sloc za on za.slocid = zt.slocfromid
						left join plant zb on zb.plantid = zt.plantid
						left join company zc on zc.companyid = zb.companyid
						left join requestedby zd on zd.requestedbyid = zt.requestedbyid
						where
						zt.formrequestno is not null 
						and zt.recordstatus in (".getUserRecordStatus('listda').")
						and zt.slocfromid in (".getUserObjectValues('sloc').")".
(($plantid != '')? "and zb.plantid = ".$plantid:'')."							and zt.formrequestid in (
										select zz.formrequestid
										from formrequestjasa zz
										where zz.tsqty > 0 and zz.formrequestid > 0)
										and zt.isjasa = 1		
						",
					array(
						':formrequestid' => '%' . $formrequestid . '%',
						':plantcode' => '%' . $plantcode . '%',
						':sloccode' => '%' . $sloccode . '%',
						':formrequestno' => '%' . $formrequestno . '%',
						':description' => '%' . $description . '%',
						':requestedbycode' => '%' . $requestedbycode . '%',
						':formrequestdate' => '%' . $formrequestdate . '%'
					))->queryAll();
				}} else 
			if (isset($_GET['combo'])) {
				$cmd = Yii::app()->db->createCommand()->select('t.*,a.sloccode,b.plantcode,a.description as slocdesc,b.companyid,c.companyname,d.requestedbycode')
					->from('formrequest t')
					->leftjoin('sloc a', 'a.slocid = t.slocfromid')
					->leftjoin('plant b', 'b.plantid = t.plantid')
					->leftjoin('company c', 'c.companyid = b.companyid')
					->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
					->where("
					((coalesce(formrequestid,'') like :formrequestid) 
					or (coalesce(plantcode,'') like :plantcode) 
					or (coalesce(sloccode,'') like :sloccode) 
					or (coalesce(t.formrequestno,'') like :formrequestno) 
					or (coalesce(t.description,'') like :description) 
					or (coalesce(requestedbycode,'') like :requestedbycode) 
					or (coalesce(formrequestdate,'') like :formrequestdate)) 
					and t.formrequestno is not null 
					and t.recordstatus in (".getUserRecordStatus('listda').")
					and t.slocfromid in (".getUserObjectValues('sloc').")",
				array(
					':formrequestid' => '%' . $formrequestid . '%',
					':plantcode' => '%' . $plantcode . '%',
					':sloccode' => '%' . $sloccode . '%',
					':formrequestno' => '%' . $formrequestno . '%',
					':description' => '%' . $description . '%',
					':requestedbycode' => '%' . $requestedbycode . '%',
					':formrequestdate' => '%' . $formrequestdate . '%'
				))->order($sort . ' ' . $order)->queryAll();
			} else {
				$cmd = Yii::app()->db->createCommand()->select('t.*,a.sloccode,b.plantcode,a.description as slocdesc,b.companyid,c.companyname,d.requestedbycode')
					->from('formrequest t')
					->leftjoin('sloc a', 'a.slocid = t.slocfromid')
					->leftjoin('plant b', 'b.plantid = t.plantid')
					->leftjoin('company c', 'c.companyid = b.companyid')
					->leftjoin('requestedby d', 'd.requestedbyid = t.requestedbyid')
					->where("
					((coalesce(formrequestid,'') like :formrequestid) 
					and (coalesce(plantcode,'') like :plantcode) 
					and (coalesce(sloccode,'') like :sloccode) 
					and (coalesce(t.formrequestno,'') like :formrequestno) 
					and (coalesce(t.description,'') like :description) 
					and (coalesce(requestedbycode,'') like :requestedbycode) 
					and (coalesce(formrequestdate,'') like :formrequestdate))
					and t.formreqtype = 0					
					and t.recordstatus in (".getUserRecordStatus('listda').") 
					and t.plantid in (".getUserObjectValues('plant').") 
					and t.useraccessid in (".getUserObjectValues('useraccess').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				"
					and t.formrequestid in (
						select distinct zz.formrequestid 
						from formrequestraw zz 
						left join product zzz on zzz.productid = zz.productid 
						where zz.sloctoid in (".getUserObjectValues('sloc').")".

					(($productname != '%%')?"
						and zzz.productname like '%".$productname."%'":'').
					"
						union 
						
						select distinct zz.formrequestid 
						from formrequestjasa zz 
						left join product zzz on zzz.productid = zz.productid 
						where zz.sloctoid in (".getUserObjectValues('sloc').")".

					(($productname != '%%')?" 
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
			}
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
		} else {
			$formrequestid     		= GetSearchText(array('POST','GET'),'formrequestid',0,'int');
			$cmd = Yii::app()->db->createCommand("
				select a.*,b.plantcode,c.companycode,d.sloccode,e.requestedbycode 
				from formrequest a 
				join plant b on b.plantid = a.plantid 
				join company c on c.companyid = b.companyid 
				join sloc d on d.slocid = a.slocfromid 
				left join requestedby e on e.requestedbyid = a.requestedbyid 
				where a.formrequestid = ".$formrequestid)->queryRow();
			$result = $cmd;
		}
		} else {
			$formrequestid     		= GetSearchText(array('POST','GET'),'formrequestid',0,'int');
			$cmd = Yii::app()->db->createCommand("
				select a.formrequestid, b.productplanno, c.sono, d.fullname, a.slocfromid,
				(select z.sloctoid
				from formrequestjasa z
				where z.formrequestid = a.formrequestid limit 1) as sloctoid
				from formrequest a
				left join productplan b on b.productplanid = a.productplanid
				left join soheader c on c.soheaderid = b.soheaderid
				left join addressbook d on d.addressbookid = c.addressbookid
				where a.isjasa = 1 and a.formrequestid = ".$formrequestid."
				
				union
				
				select a.formrequestid, b.productplanno, c.sono, d.fullname, a.slocfromid,
				(select z.sloctoid
				from formrequestraw z
				where z.formrequestid = a.formrequestid limit 1) as sloctoid
				from formrequest a
				left join productplan b on b.productplanid = a.productplanid
				left join soheader c on c.soheaderid = b.soheaderid
				left join addressbook d on d.addressbookid = c.addressbookid
				where a.isjasa = 0 and a.formrequestid = ".$formrequestid
				)
				->queryRow();
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
					->select('t.*,a.productcode,a.productname,c.namamesin,d.sloccode,t.description,e.materialtypecode,
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
        'prqty' => Yii::app()->format->formatNumber($data['prqty']),
        'tsqty' => Yii::app()->format->formatNumber($data['tsqty']),
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
					->from('formrequestresult t')
					->leftjoin('formrequest g', 'g.formrequestid = t.formrequestid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')
					->where('t.formrequestid = :formrequestid',
					array(
				':formrequestid' => $id
				))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
					->select('t.*,a.productcode,a.productname,e.materialtypecode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,t.description,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code')
					->from('formrequestresult t')
					->leftjoin('formrequest g', 'g.formrequestid = t.formrequestid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('materialtype e', 'e.materialtypeid = a.materialtypeid')
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
				'materialtypecode' => $data['materialtypecode'],
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
  public function actionApprove()
  {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveFR(:vid,:vdatauser)';
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
		$sql = 'call ModifFormrequest(:vid,:vformrequestdate,:vplantid,:vslocfromid,:visjasa,:vrequestedbyid,:vdescription,:vdatauser)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vformrequestdate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vslocfromid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':visjasa', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vrequestedbyid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vdescription', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionSave() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array(
				(isset($_POST['formrequest-formrequestid'])?$_POST['formrequest-formrequestid']:''),
				date(Yii::app()->params['datetodb'], strtotime($_POST['formrequest-formrequestdate'])),
				$_POST['formrequest-plantid'],$_POST['formrequest-slocfromid'],(isset($_POST['formrequest-isjasa']) ? 1 : 0),
				$_POST['formrequest-requestedbyid'],$_POST['formrequest-description'])
			);
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
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-formrequest"]["name"]);
		if (move_uploaded_file($_FILES["file-formrequest"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$oldid = '';$pid = '';
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
							$slocfrom = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue(); //D
							$slocfromid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$slocfrom."'")->queryScalar();
							$isjasa = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue(); //E
							$requestedby = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue(); //F
							$requestedbyid = Yii::app()->db->createCommand("select requestedbyid from requestedby where requestedbycode = '".$requestedby."'")->queryScalar();
							$description = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue(); //G
							$this->ModifyData($connection,array(
								-1,
								$docdate,
								$plantid,
								$slocfromid,
								$isjasa,
								$requestedbyid,
								$description
							));
							$pid = Yii::app()->db->createCommand("
								select formrequestid 
								from formrequest a
								where a.plantid = ".$plantid." 
								and a.formrequestdate = '".$docdate."' 
								and a.slocfromid = ".$slocfromid." 
								and a.isjasa = ".$isjasa." 
								and a.requestedbyid = ".$requestedbyid." 
								and a.description = '".$description."' 
								and a.formreqtype = 0 
								limit 1
							")->queryScalar();
						} 
						$productname = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue(); //H
						$sql = "select productid from product where productname = :productname";
						$command=$connection->createCommand($sql);
						$command->bindvalue(':productname',$productname,PDO::PARAM_STR);
						$productid = $command->queryScalar();
						if ($productid > 0) {
							$qty = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue(); //I
							$uomcode = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue(); //J
							$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$qty2 = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue(); //K
							$uomcode = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue(); //L
							$uom2id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$qty3 = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue(); //M
							$uomcode = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue(); //N
							$uom3id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$qty4 = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue(); //O
							$uomcode = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue(); //P
							$uom4id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$reqdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(16, $row)->getValue())); //Q
							$slocto = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue(); //R
							$sloctoid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$slocto."'")->queryScalar();
							$kodemesin = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue(); //S
							$mesinid = Yii::app()->db->createCommand("select mesinid from mesin where kodemesin = '".$kodemesin."'")->queryScalar();
							$itemnote = $objWorksheet->getCellByColumnAndRow(19, $row)->getValue(); //T
							$this->ModifyRaw($connection,array(
								'',
								$pid,
								$productid,
								$uomid,
								$uom2id,
								$uom3id,
								$uom4id,
								$qty,
								$qty2,
								$qty3,
								$qty4,
								$reqdate,
								$sloctoid,
								$mesinid,
								$itemnote
							));
						}
						$productname = $objWorksheet->getCellByColumnAndRow(20, $row)->getValue(); //U
						$sql = "select productid from product where productname = :productname";
						$command=$connection->createCommand($sql);
						$command->bindvalue(':productname',$productname,PDO::PARAM_STR);
						$productid = $command->queryScalar();
						if ($productid > 0) {
							$qty = $objWorksheet->getCellByColumnAndRow(21, $row)->getValue(); //V
							$uomcode = $objWorksheet->getCellByColumnAndRow(22, $row)->getValue(); //W
							$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$kodemesin = $objWorksheet->getCellByColumnAndRow(23, $row)->getValue(); //X
							$mesinid = Yii::app()->db->createCommand("select mesinid from mesin where kodemesin = '".$kodemesin."'")->queryScalar();
							$reqdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(24, $row)->getValue())); //Y
							$slocto = $objWorksheet->getCellByColumnAndRow(25, $row)->getValue(); //Z
							$sloctoid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$slocto."'")->queryScalar();
							$itemnote = $objWorksheet->getCellByColumnAndRow(26, $row)->getValue(); //AA
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
						$productname = $objWorksheet->getCellByColumnAndRow(27, $row)->getValue(); //AB
						$sql = "select productid from product where productname = :productname";
						$command=$connection->createCommand($sql);
						$command->bindvalue(':productname',$productname,PDO::PARAM_STR);
						$productid = $command->queryScalar();
						if ($productid > 0) {
							$qty = $objWorksheet->getCellByColumnAndRow(28, $row)->getValue(); //AC
							$uomcode = $objWorksheet->getCellByColumnAndRow(29, $row)->getValue(); //AD
							$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$qty2 = $objWorksheet->getCellByColumnAndRow(30, $row)->getValue(); //AE
							$uomcode = $objWorksheet->getCellByColumnAndRow(31, $row)->getValue(); //AF
							$uom2id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$qty3 = $objWorksheet->getCellByColumnAndRow(32, $row)->getValue(); //AG
							$uomcode = $objWorksheet->getCellByColumnAndRow(33, $row)->getValue(); //AH
							$uom3id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$qty4 = $objWorksheet->getCellByColumnAndRow(34, $row)->getValue(); //AI
							$uomcode = $objWorksheet->getCellByColumnAndRow(35, $row)->getValue(); //AJ
							$uom4id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$itemnote = $objWorksheet->getCellByColumnAndRow(36, $row)->getValue(); //AK
							$this->ModifyResult($connection,array(
								'',
								$pid,
								$productid,
								$uomid,
								$uom2id,
								$uom3id,
								$uom4id,
								$qty,
								$qty2,
								$qty3,
								$qty4,
								$itemnote
							));
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
	private function ModifyRaw($connection,$arraydata) {
		if ($arraydata[0]=='') {
			$sql     = 'call Insertfrraw(:vformrequestid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,:vqty4,:vreqdate,
				:vsloctoid,:vnamamesin,:vdescription,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatefrraw(:vid,:vformrequestid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,:vqty4,
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
	  $command->bindvalue(':vuom4id', $arraydata[6], PDO::PARAM_STR);
	  $command->bindvalue(':vqty', $arraydata[7], PDO::PARAM_STR);
	  $command->bindvalue(':vqty2', $arraydata[8], PDO::PARAM_STR);
	  $command->bindvalue(':vqty3', $arraydata[9], PDO::PARAM_STR);
	  $command->bindvalue(':vqty4', $arraydata[10], PDO::PARAM_STR);
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
		if ($arraydata[0]=='') {
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
			$sql     = 'call InsertFRresult(:vformrequestid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,:vqty4,:vdescription,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call UpdateFRresult(:vid,:vformrequestid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vuom4id,:vqty,:vqty2,:vqty3,:vqty4,:vdescription,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vformrequestid', $arraydata[1], PDO::PARAM_STR);
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
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeformrequest(:vid,:vdatauser)';
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
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgefralldetail(:vid,:vdatauser)';
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
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeformrequestjasa(:vid,:vdatauser)';
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
    $sql = "select a.formrequestid,a.formrequestno,a.formrequestdate,b.sloccode,a.description
						from formrequest a
						inner join sloc b on b.slocid = a.slocfromid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.formrequestid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = getCatalog('formrequest');
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNBPages();
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->text(15, $this->pdf->gety(), 'No ');
      $this->pdf->text(20, $this->pdf->gety(), ': ' . $row['formrequestno']);
      $this->pdf->text(70, $this->pdf->gety(), 'Tgl ');
      $this->pdf->text(75, $this->pdf->gety(), ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['formrequestdate'])));
      $this->pdf->text(120, $this->pdf->gety(), 'Gudang ');
      $this->pdf->text(130, $this->pdf->gety(), ': ' . $row['sloccode']);
      $i           = 0;
      $totalqty    = 0;
			$totalqty2    = 0;
			$totalqty3    = 0;
			$totalqty4    = 0;
      $totaljumlah = 0;
			//$this->pdf->title = 'tes judul';
      $sql1        = "select b.productcode,b.productname,a.qty,a.qty2,a.qty3,a.qty4,c.uomcode,g.uomcode as uom2code,
			h.uomcode as uom3code,i.uomcode as uom4code,a.description,d.namamesin,e.sloccode,a.reqdate
							from formrequestraw a
							inner join product b on b.productid = a.productid
							inner join unitofmeasure c on c.unitofmeasureid = a.uomid
							left join unitofmeasure g on g.unitofmeasureid = a.uom2id
							left join unitofmeasure h on h.unitofmeasureid = a.uom3id
							left join unitofmeasure i on i.unitofmeasureid = a.uom4id
							left join mesin d on d.mesinid = a.mesinid
							left join sloc e on e.slocid = a.sloctoid
							where formrequestid = " . $row['formrequestid'] . " order by formrequestrawid ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
			$this->pdf->text(10,$this->pdf->gety()+8,'RM');
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
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setFont('Arial', 'B', 7);
      $this->pdf->setwidths(array(
        7,
        42,
        18,
				20,
				20,
        20,
        20,
        20,
        20,
        20,
        20,
        20,
        20,
        20,
        20
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Tgl Req',
        'Qty 1',
		'Qty 2',
		'Qty 3',
		'Qty 4',
        'Mesin',
        'Tujuan',
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
        'L'
      );
      foreach ($dataReader1 as $row1) {
          $i = $i + 1;
          $this->pdf->row(array(
            $i,
           $row1['productname'],
					 date(Yii::app()->params['dateviewfromdb'], strtotime($row1['reqdate'])),
            Yii::app()->format->formatNumber($row1['qty']).' '.$row1['uomcode'],
			Yii::app()->format->formatNumber($row1['qty2']).' '.$row1['uom2code'],
			Yii::app()->format->formatNumber($row1['qty3']).' '.$row1['uom3code'],
			Yii::app()->format->formatNumber($row1['qty4']).' '.$row1['uom4code'],
            $row1['namamesin'],
						$row1['sloccode'],
            $row1['description']
          ));
          $totalqty += $row1['qty'];
		  $totalqty2 += $row1['qty2'];
		  $totalqty3 += $row1['qty3'];
		  $totalqty4 += $row1['qty4'];
      }
			$sql1        = "select b.productcode,b.productname,a.qty,c.uomcode,a.description,d.namamesin,e.sloccode
							from formrequestjasa a
							inner join product b on b.productid = a.productid
							inner join unitofmeasure c on c.unitofmeasureid = a.uomid
							left join mesin d on d.mesinid = a.mesinid
							left join sloc e on e.slocid = a.sloctoid
							where formrequestid = " . $row['formrequestid'] . " order by formrequestjasaid ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->text(10,$this->pdf->gety()+8,'JASA');
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
        20,
        20,
        20,
        20,
        20,
        20
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Mesin',
        'Tujuan',
        'Keterangan'
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
            $row1['productcode'] . '-' . $row1['productname'],
            Yii::app()->format->formatNumber($row1['qty']).' '.$row1['uomcode'],
            $row1['namamesin'],
						$row1['sloccode'],
            $row1['description']
          ));
          $totalqty += $row1['qty'];
      }
			$sql1        = "select b.productcode,b.productname,a.qty,c.uomcode,a.qty2,c.uomcode as uom2code,a.qty3,c.uomcode as uom3code,a.qty4,c.uomcode as uom4code,a.description
							from formrequestresult a
							inner join product b on b.productid = a.productid
							inner join unitofmeasure c on c.unitofmeasureid = a.uomid
							where formrequestid = " . $row['formrequestid'] . " order by formrequestresultid ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
			$this->pdf->text(10,$this->pdf->gety()+8,'FG');
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
        22,
        25,
        30,
        20,
        20,
        20,
        20
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty 1',
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
        'C',
        'R',
        'R',
        'R',
        'R',
        'C',
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
        ''
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
        'Note',
        $row['description']
      ));
      $this->pdf->setFont('Arial', '', 8);
			$this->pdf->CheckNewPage(30);
      $this->pdf->sety($this->pdf->gety() + 15);
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
	public function actionDownxls() {
    $this->menuname = 'formrequestlist';
    parent::actionDownxls();
    $formrequestid = GetSearchText(array('POST','GET','Q'),'formrequestid');
		$plantcode   = GetSearchText(array('POST','GET','Q'),'plantcode');
		$productname   = GetSearchText(array('POST','GET','Q'),'productname');
    $formrequestdate     = GetSearchText(array('POST','GET','Q'),'formrequestdate');
    $sloccode = GetSearchText(array('POST','GET','Q'),'sloccode');
    $formrequestno = GetSearchText(array('POST','GET','Q'),'formrequestno');
    $description = GetSearchText(array('POST','GET','Q'),'description');
		$sql = "select ab.plantcode,a.formrequestid,a.formrequestno,a.formrequestdate,b.sloccode,a.description,aa.requestedbycode,GetStock(c.productid,c.uomid,c.sloctoid) as rawqtystock,
			a.isjasa,d.productname as rawproductname,c.qty as rawqty,e.uomcode as rawuom,c.qty2 as rawqty2,f.uomcode as rawuom2,c.qty3 as rawqty3,g.uomcode as rawuom3,
			c.qty4 as rawqty4,h.uomcode as rawuom4,i.sloccode as rawsloc,j.kodemesin as rawmesin,c.description as rawdescription,c.prqty as rawprqty,c.prqty2 as rawprqty2,
			c.reqdate as rawreqdate,l.productname as jasaproductname,k.qty as jasaqty,m.uomcode as jasauomcode,k.reqdate as jasareqdate,k.prqty as jasaprqty,k.description as jasadescription,
			n.kodemesin as jasamesin,o.sloccode as jasasloc
			from formrequest a
			left join sloc b on b.slocid = a.slocfromid 
			left join requestedby aa on aa.requestedbyid = a.requestedbyid 
			left join plant ab on ab.plantid = a.plantid 
			left join formrequestraw c on c.formrequestid = a.formrequestid 
			left join product d on d.productid = c.productid 
			left join unitofmeasure e on e.unitofmeasureid = c.uomid 
			left join unitofmeasure f on f.unitofmeasureid = c.uom2id 
			left join unitofmeasure g on g.unitofmeasureid = c.uom3id 
			left join unitofmeasure h on h.unitofmeasureid = c.uom4id 
			left join sloc i on i.slocid = c.sloctoid 
			left join mesin j on j.mesinid = c.mesinid 
			left join formrequestjasa k on k.formrequestid = a.formrequestid 
			left join product l on l.productid = k.productid 
			left join unitofmeasure m on m.unitofmeasureid = k.uomid 
			left join mesin n on n.mesinid = k.mesinid 
			left join sloc o on o.slocid = k.sloctoid 
		";
		$sql .= " where coalesce(a.formrequestid,'') like '".$formrequestid."' 
			and coalesce(ab.plantcode,'') like '".$plantcode."' 
			and coalesce(a.formrequestdate,'') like '".$formrequestdate."' 
			and coalesce(a.formrequestno,'') like '".$formrequestno."' 
			and coalesce(a.description,'') like '".$description."'".
			(($productname != '%%')?"
				and coalesce(d.productname,'') like '".$productname."'
			":'')
		;
		if ($_GET['id'] !== '') {
      $sql = $sql . " and a.formrequestid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 3;$nourut=0;$oldbom='';
    foreach ($dataReader as $row) {
			if ($oldbom != $row['formrequestid']) {
				$nourut+=1;
				$oldbom = $row['formrequestid'];
			}
      $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i, $nourut)
				->setCellValueByColumnAndRow(1, $i, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $i, date(Yii::app()->params['dateviewfromdb'], strtotime($row['formrequestdate'])))
				->setCellValueByColumnAndRow(3, $i, $row['formrequestno'])
				->setCellValueByColumnAndRow(4, $i, $row['isjasa'])
				->setCellValueByColumnAndRow(5, $i, $row['sloccode'])
				->setCellValueByColumnAndRow(6, $i, $row['requestedbycode'])
				->setCellValueByColumnAndRow(7, $i, $row['description'])
				->setCellValueByColumnAndRow(8, $i, $row['rawproductname'])
				->setCellValueByColumnAndRow(9, $i, Yii::app()->format->formatNumber($row['rawqtystock']))
				->setCellValueByColumnAndRow(10, $i, Yii::app()->format->formatNumber($row['rawqty']))
				->setCellValueByColumnAndRow(11, $i, Yii::app()->format->formatNumber($row['rawprqty']))
				->setCellValueByColumnAndRow(12, $i, $row['rawuom'])
				->setCellValueByColumnAndRow(13, $i, Yii::app()->format->formatNumber($row['rawqty2']))
				->setCellValueByColumnAndRow(14, $i, $row['rawuom2'])
				->setCellValueByColumnAndRow(15, $i, Yii::app()->format->formatNumber($row['rawqty3']))
				->setCellValueByColumnAndRow(16, $i, $row['rawuom3'])
				->setCellValueByColumnAndRow(17, $i, Yii::app()->format->formatNumber($row['rawqty4']))
				->setCellValueByColumnAndRow(18, $i, $row['rawuom4'])
				->setCellValueByColumnAndRow(19, $i, date(Yii::app()->params['dateviewfromdb'], strtotime($row['rawreqdate'])))
				->setCellValueByColumnAndRow(20, $i, $row['rawsloc'])
				->setCellValueByColumnAndRow(21, $i, $row['rawmesin'])
				->setCellValueByColumnAndRow(22, $i, $row['rawdescription'])
				->setCellValueByColumnAndRow(23, $i, $row['jasaproductname'])
				->setCellValueByColumnAndRow(24, $i, Yii::app()->format->formatNumber($row['jasaqty']))
				->setCellValueByColumnAndRow(25, $i, Yii::app()->format->formatNumber($row['jasaprqty']))
				->setCellValueByColumnAndRow(26, $i, $row['jasauomcode'])
				->setCellValueByColumnAndRow(27, $i, $row['jasamesin'])
				->setCellValueByColumnAndRow(28, $i, $row['jasasloc'])
				->setCellValueByColumnAndRow(29, $i, $row['jasadescription'])
			;
			$i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}
