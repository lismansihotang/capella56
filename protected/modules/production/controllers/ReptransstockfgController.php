<?php
class ReptransstockfgController extends Controller
{
  public $menuname = 'reptransstockfg';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $transstockid 		= GetSearchText(array('POST','Q'),'transstockid','','int');
    $plantid 		= GetSearchText(array('POST','GET'),'plantid',0,'int');
    $plantcode 		= GetSearchText(array('POST','Q'),'plantcode');
    $productcode 		= GetSearchText(array('POST','Q'),'productcode');
    $productname 		= GetSearchText(array('POST','Q'),'productname');
    $slocfromid 		= GetSearchText(array('POST','GET'),'slocfromid',0,'int');
    $slocfromcode 		= GetSearchText(array('POST','Q'),'slocfromcode');
    $addressbookid 		= GetSearchText(array('POST','GET'),'addressbookid',0,'int');
    $productoutputid 		= GetSearchText(array('POST','GET'),'productoutputid',0,'int');
    $sloctoid 		= GetSearchText(array('POST','GET'),'sloctoid',0,'int');
    $sloctocode 		= GetSearchText(array('POST','Q'),'sloctocode');
    $transstockdate 		= GetSearchText(array('POST','Q'),'transstockdate');
    $transstockno 		= GetSearchText(array('POST','Q'),'transstockno');
    $headernote 		= GetSearchText(array('POST','Q'),'headernote');
    $sono 		= GetSearchText(array('POST','Q'),'sono');
    $productplanno 		= GetSearchText(array('POST','Q'),'productplanno');
    $productoutputno 		= GetSearchText(array('POST','Q'),'productoutputno');
    $productoutputno 		= GetSearchText(array('POST','Q'),'productoutputno');
    $recordstatus 		= GetSearchText(array('POST','Q'),'recordstatus');
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
					->leftjoin('productoutput e', 'e.productoutputid = t.productoutputid')
					->leftjoin('productplan f', 'f.productplanid = e.productplanid')
					->leftjoin('soheader g', 'g.soheaderid = f.soheaderid')
					->leftjoin('addressbook h', 'h.addressbookid = f.addressbookid')
					->leftjoin('sloc i', 'i.slocid = t.slocfromid')
				->where("
				((coalesce(transstockid,'') like :transstockid) 
				and (coalesce(b.plantcode,'') like :plantcode) 
				and (coalesce(d.sloccode,'') like :sloctocode) 
				and (coalesce(i.sloccode,'') like :slocfromcode) 
				and (coalesce(transstockno,'') like :transstockno) 
				and (coalesce(g.sono,'') like :sono) 
				and (coalesce(f.productplanno,'') like :productplanno) 
				and (coalesce(t.headernote,'') like :headernote) 
				and (coalesce(e.productoutputno,'') like :productoutputno) 
				and (coalesce(t.transstockdate,'') like :transstockdate))".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":''). 
					" 
				and t.transstocktypeid = 1 
				and t.slocfromid in (".getUserObjectValues('sloc').")".
					(($productname != '')?"
						and t.transstockid in (
							select distinct transstockid 
							from transstockdet z
							join product zz on zz.productid = z.productid 
							where zz.productname like '%".$productname."%' 						
						)					
					":'')
					,
			array(
				':transstockid' => '%' . $transstockid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':sloctocode' => '%' . $sloctocode . '%',
				':slocfromcode' => '%' . $slocfromcode . '%',
				':transstockno' => '%' . $transstockno . '%',
				':sono' => '%' . $sono . '%',
				':productplanno' => '%' . $productplanno . '%',
				':headernote' => '%' . $headernote . '%',
				':productoutputno' => '%' . $productoutputno . '%',
				':transstockdate' => '%' . $transstockdate . '%'
			))->queryScalar();
			$result['total'] = $cmd;
			$cmd = Yii::app()->db->createCommand()->select('t.*,a.sloccode,b.plantcode,a.sloccode as slocfromcode,d.sloccode as sloctocode,t.headernote,b.companyid,c.companyname,
				e.productoutputno,g.sono,h.fullname,f.addressbookid,f.productplanno')
				->from('transstock t')
				->leftjoin('sloc a', 'a.slocid = t.slocfromid')
					->leftjoin('plant b', 'b.plantid = t.plantid')
					->leftjoin('company c', 'c.companyid = b.companyid')
					->leftjoin('sloc d', 'd.slocid = t.sloctoid')
					->leftjoin('productoutput e', 'e.productoutputid = t.productoutputid')
					->leftjoin('productplan f', 'f.productplanid = e.productplanid')
					->leftjoin('soheader g', 'g.soheaderid = f.soheaderid')
					->leftjoin('addressbook h', 'h.addressbookid = f.addressbookid')
					->leftjoin('sloc i', 'i.slocid = t.slocfromid')
				->where("
				((coalesce(transstockid,'') like :transstockid) 
				and (coalesce(b.plantcode,'') like :plantcode) 
				and (coalesce(d.sloccode,'') like :sloctocode) 
				and (coalesce(i.sloccode,'') like :slocfromcode) 
				and (coalesce(transstockno,'') like :transstockno) 
				and (coalesce(g.sono,'') like :sono) 
				and (coalesce(f.productplanno,'') like :productplanno) 
				and (coalesce(t.headernote,'') like :headernote) 
				and (coalesce(e.productoutputno,'') like :productoutputno) 
				and (coalesce(t.transstockdate,'') like :transstockdate))".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":''). 
					" 
				and t.transstocktypeid = 1 
				and t.slocfromid in (".getUserObjectValues('sloc').")".
					(($productname != '')?"
						and t.transstockid in (
							select distinct transstockid 
							from transstockdet z
							join product zz on zz.productid = z.productid 
							where zz.productname like '%".$productname."%' 						
						)					
					":'')
					,
			array(
				':transstockid' => '%' . $transstockid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':sloctocode' => '%' . $sloctocode . '%',
				':slocfromcode' => '%' . $slocfromcode . '%',
				':transstockno' => '%' . $transstockno . '%',
				':sono' => '%' . $sono . '%',
				':productplanno' => '%' . $productplanno . '%',
				':headernote' => '%' . $headernote . '%',
				':productoutputno' => '%' . $productoutputno . '%',
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
					'productoutputid' => $data['productoutputid'],
					'productoutputno' => $data['productoutputno'],
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