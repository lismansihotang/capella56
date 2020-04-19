<?php
class ReporttsController extends Controller
{
  public $menuname = 'reportts';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail()
  {
    if (isset($_GET['grid']))
      echo $this->actionsearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $transstockid 		= GetSearchText(array('POST','Q'),'transstockid','','int');
    $plantcode 		= GetSearchText(array('POST','Q'),'plantcode','','int');
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
			and (coalesce(b.plantcode,'') like :plantcode) 
			and (coalesce(slocfromcode,'') like :slocfromcode) 
			and (coalesce(sloctocode,'') like :sloctocode) 
			and (coalesce(transstockno,'') like :transstockno) 
			and (coalesce(t.headernote,'') like :headernote) 
			and (coalesce(e.formrequestno,'') like :formrequestno) 
			and (coalesce(f.productplanno,'') like :productplanno)
			and (coalesce(g.sono,'') like :sono)			
			and (coalesce(t.transstockdate,'') like :transstockdate))".
			(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
			" 
			and b.plantid in (".getUserObjectValues('plant').")
			and transstocktypeid = 0 
			",
		array(
			':transstockid' => '%' . $transstockid . '%',
			':plantcode' => '%' . $plantcode . '%',
			':slocfromcode' => '%' . $slocfromcode . '%',
			':sloctocode' => '%' . $sloctocode . '%',
			':transstockno' => '%' . $transstockno . '%',
			':headernote' => '%' . $headernote . '%',
			':formrequestno' => '%' . $formrequestno . '%',
			':productplanno' => '%' . $productplanno . '%',
			':sono' => '%' . $sono . '%',
			':transstockdate' => '%' . $transstockdate . '%'
		))->queryScalar();
			$result['total'] = $cmd;
				$cmd = Yii::app()->db->createCommand()->select('t.*,a.sloccode,b.plantcode,a.sloccode as slocfromcode,d.sloccode as sloctocode,t.headernote,b.companyid,c.companyname,
					e.formrequestno,g.sono,h.fullname,f.addressbookid,f.productplanno')
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
					((coalesce(transstockid,'') like :transstockid) and 
					(coalesce(b.plantcode,'') like :plantcode) and 
					(coalesce(slocfromcode,'') like :slocfromcode) and 
					(coalesce(sloctocode,'') like :sloctocode) and 
					(coalesce(transstockno,'') like :transstockno) and 
					(coalesce(t.headernote,'') like :headernote) and 
					(coalesce(e.formrequestno,'') like :formrequestno) 
					and (coalesce(g.sono,'') like :sono)
					and (coalesce(f.productplanno,'') like :productplanno)  
					and (coalesce(t.transstockdate,'') like :transstockdate))".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				" 
					and b.plantid in (".getUserObjectValues('plant').")
					and transstocktypeid = 0 
					",
				array(
					':transstockid' => '%' . $transstockid . '%',
					':plantcode' => '%' . $plantcode . '%',
					':slocfromcode' => '%' . $slocfromcode . '%',
					':sloctocode' => '%' . $sloctocode . '%',
					':transstockno' => '%' . $transstockno . '%',
					':headernote' => '%' . $headernote . '%',
					':formrequestno' => '%' . $formrequestno . '%',
					':productplanno' => '%' . $productplanno . '%',
					':sono' => '%' . $sono . '%',
					':transstockdate' => '%' . $transstockdate . '%'
					))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
			foreach ($cmd as $data) {
				$row[] = array(
					'transstockid' => $data['transstockid'],
					'transstockdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['transstockdate'])),
					'transstockno' => $data['transstockno'],
					'plantid' => $data['plantid'],
					'plantcode' => $data['plantcode'],
					'companyid' => $data['companyid'],
					'companyname' => $data['companyname'],
					'formrequestid' => $data['formrequestid'],
					'formrequestno' => $data['formrequestno'],
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
    return CJSON::encode($result);
	}
}