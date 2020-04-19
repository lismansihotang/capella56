<?php
class ReppackinglistController extends Controller {
  public $menuname = 'reppackinglist';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
		$packinglistid 		= GetSearchText(array('POST','GET','Q'),'packinglistid');
		$plantid     		= GetSearchText(array('POST','GET'),'plantid',0,'int');
		$plantcode     		= GetSearchText(array('POST','GET','Q'),'plantcode');
		$packinglistdate    = GetSearchText(array('POST','GET','Q'),'packinglistdate');
		$packinglistno 		= GetSearchText(array('POST','GET','Q'),'packinglistno');
		$gino 		= GetSearchText(array('POST','GET','Q'),'gino');
		$sono 		= GetSearchText(array('POST','GET','Q'),'sono');
		$addressbookid      = GetSearchText(array('POST','GET'),'addressbookid',0,'int');
		$customername      = GetSearchText(array('POST','GET','Q'),'customername');
		$addressname 		= GetSearchText(array('POST','GET','Q'),'addressname');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','packinglistid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('packinglist t')
			->leftjoin('addressbook b', 'b.addressbookid = t.addressbookid')
			->leftjoin('address c', 'c.addressid = t.addresstoid')
			->leftjoin('plant d', 'd.plantid = t.plantid')
			->leftjoin('company e', 'e.companyid = d.companyid')
			->leftjoin('addressbook f', 'f.addressbookid = t.supplierid')
			->where("
			((coalesce(packinglistid,'') like :packinglistid) 
			and (coalesce(packinglistdate,'') like :packinglistdate) 
			and (coalesce(packinglistno,'') like :packinglistno) 
			and (coalesce(d.plantcode,'') like :plantcode) 
			and (coalesce(gino,'') like :gino) 
			and (coalesce(b.fullname,'') like :customername) 
			and (coalesce(c.addressname,'') like :addressname)
			and (coalesce(sono,'') like :sono))
			and t.plantid in (".getUserObjectValues('plant').")
			",
			array(
			':packinglistid' => '%' . $packinglistid . '%',
			':packinglistdate' => '%' . $packinglistdate . '%',
			':packinglistno' => '%' . $packinglistno . '%',
			':plantcode' => '%' . $plantcode . '%',
			':gino' => '%' . $gino . '%',
			':customername' => '%' . $customername . '%',
			':addressname' => '%' . $addressname . '%',
			':sono' => '%' . $sono . '%'
		))->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()->select('t.*,b.fullname as customername, c.addressname, d.plantcode,d.companyid, e.companyname,f.fullname as suppliername')
			->from('packinglist t')
			->leftjoin('addressbook b', 'b.addressbookid = t.addressbookid')
			->leftjoin('addressbook f', 'f.addressbookid = t.supplierid')
			->leftjoin('address c', 'c.addressid = t.addresstoid')
			->leftjoin('plant d', 'd.plantid = t.plantid')
			->leftjoin('company e', 'e.companyid = d.companyid')
			->where("
			((coalesce(packinglistid,'') like :packinglistid) 
			and (coalesce(packinglistdate,'') like :packinglistdate) 
			and (coalesce(packinglistno,'') like :packinglistno) 
			and (coalesce(d.plantcode,'') like :plantcode) 
			and (coalesce(gino,'') like :gino) 
			and (coalesce(b.fullname,'') like :customername) 
			and (coalesce(c.addressname,'') like :addressname)
			and (coalesce(sono,'') like :sono)) 
			and t.plantid in (".getUserObjectValues('plant').")
			",
			array(
			':packinglistid' => '%' . $packinglistid . '%',
			':packinglistdate' => '%' . $packinglistdate . '%',
			':packinglistno' => '%' . $packinglistno . '%',
			':plantcode' => '%' . $plantcode . '%',
			':gino' => '%' . $gino . '%',
			':customername' => '%' . $customername . '%',
			':addressname' => '%' . $addressname . '%',
			':sono' => '%' . $sono . '%',
			))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		foreach ($cmd as $data) {
			$row[] = array(
				'packinglistid' => $data['packinglistid'],
				'packinglistdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['packinglistdate'])),
				'packinglistno' => $data['packinglistno'],
				'plantid' => $data['plantid'],
				'plantcode' => $data['plantcode'],
				'companyid' => $data['companyid'],
				'companyname' => $data['companyname'],
				'giheaderid' => $data['giheaderid'],
				'gino' => $data['gino'],
				'addressbookid' => $data['addressbookid'],
				'customername' => $data['customername'],
				'supplierid' => $data['supplierid'],
				'suppliername' => $data['suppliername'],
				'addresstoid' => $data['addresstoid'],
				'addressname' => $data['addressname'],
				'soheaderid' => $data['soheaderid'],
				'sono' => $data['sono'],
				'pebno' => $data['pebno'],
				'nomobil' => $data['nomobil'],
				'sopir' => $data['sopir'],
				'recordstatus' => $data['recordstatus'],
				'statusname' => $data['statusname']
			);
		}
		$result = array_merge($result, array(
			'rows' => $row
		));
    return CJSON::encode($result);
	}
}