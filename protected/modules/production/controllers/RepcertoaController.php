<?php
class RepcertoaController extends Controller {
  public $menuname = 'repcertoa';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $certoaid       = isset($_POST['certoaid']) ? $_POST['certoaid'] : '';
    $plantcode  = isset($_POST['plantcode']) ? $_POST['plantcode'] : '';
		$productcode   = isset($_POST['productcode']) ? $_POST['productcode'] : '';
		$productname   = isset($_POST['productname']) ? $_POST['productname'] : '';   
		$customer   = isset($_POST['customer']) ? $_POST['customer'] : '';   
		$bomversion   = isset($_POST['bomversion']) ? $_POST['bomversion'] : '';   
		$sono   = isset($_POST['sono']) ? $_POST['sono'] : '';   
    $certoaid       = isset($_GET['certoaid']) ? $_GET['certoaid'] : $certoaid;
    $plantcode  = isset($_GET['plantcode']) ? $_GET['plantcode'] : $plantcode;
		$productcode   = isset($_GET['productcode']) ? $_GET['productcode'] : $productcode;
		$productname   = isset($_GET['productname']) ? $_GET['productname'] : $productname;   
		$customer   = isset($_GET['customer']) ? $_GET['customer'] : $customer;   
		$bomversion   = isset($_GET['bomversion']) ? $_GET['bomversion'] : $bomversion;   
		$sono   = isset($_GET['sono']) ? $_GET['sono'] : $sono;   
    $page        = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows        = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort        = isset($_POST['sort']) ? strval($_POST['sort']) : 'certoaid';
    $order       = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset      = ($page - 1) * $rows;
    $page        = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows        = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort        = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order       = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset      = ($page - 1) * $rows;
    $result      = array();
    $row         = array();
		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('certoa t')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('plant b', 'b.plantid = t.plantid')
			->leftjoin('addressbook c', 'c.addressbookid = t.addressbookid')
			->leftjoin('company d', 'd.companyid = b.companyid')
			->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
			->leftjoin('billofmaterial f', 'f.bomid = t.bomid')
			->leftjoin('materialtype g', 'g.materialtypeid = a.materialtypeid')			
			->where("((coalesce(certoaid,'') like :certoaid) 
			and (coalesce(b.plantcode,'') like :plantcode) 
			and (coalesce(c.fullname,'') like :customer) 
			and (coalesce(a.productcode,'') like :productcode) 
			and (coalesce(a.productname,'') like :productname)
			and (coalesce(f.bomversion,'') like :bomversion)
			and (coalesce(e.sono,'') like :sono))
			", array(
			':certoaid' => '%' . $certoaid . '%',
			':plantcode' => '%' . $plantcode . '%',
			':productcode' => '%' . $productcode . '%',
			':productname' => '%' . $productname . '%',
			':bomversion' => '%' . $bomversion . '%',
			':sono' => '%' . $sono . '%',
			':customer' => '%' . $customer . '%',
		))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.productcode,a.productname,b.plantcode,c.fullname,d.companyname,e.sono,f.bomversion,g.materialtypecode')
			->from('certoa t')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('plant b', 'b.plantid = t.plantid')
			->leftjoin('addressbook c', 'c.addressbookid = t.addressbookid')
			->leftjoin('company d', 'd.companyid = b.companyid')
			->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
			->leftjoin('billofmaterial f', 'f.bomid = t.bomid')
			->leftjoin('materialtype g', 'g.materialtypeid = a.materialtypeid')			
			->where("((coalesce(certoaid,'') like :certoaid) 
			and (coalesce(b.plantcode,'') like :plantcode) 
			and (coalesce(c.fullname,'') like :customer) 
			and (coalesce(a.productcode,'') like :productcode) 
			and (coalesce(a.productname,'') like :productname)
			and (coalesce(f.bomversion,'') like :bomversion)
			and (coalesce(e.sono,'') like :sono))
			", array(
			':certoaid' => '%' . $certoaid . '%',
			':plantcode' => '%' . $plantcode . '%',
			':productcode' => '%' . $productcode . '%',
			':productname' => '%' . $productname . '%',
			':bomversion' => '%' . $bomversion . '%',
			':sono' => '%' . $sono . '%',
			':customer' => '%' . $customer . '%',
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'certoaid' => $data['certoaid'],
        'certoano' => $data['certoano'],
        'certoadate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['certoadate'])),
        'productiondate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['productiondate'])),
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'materialtypecode' => $data['materialtypecode'],
        'companyname' => $data['companyname'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'productcode' => $data['productcode'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'bomid' => $data['bomid'],
        'bomversion' => $data['bomversion'],
        'soheaderid' => $data['soheaderid'],
        'sono' => $data['sono'],
        'status' => $data['status'],
        'description' => $data['description'],
        'recordstatus' => $data['recordstatus'],
        'statusname' => $data['statusname'],
        'jumkirim' => Yii::app()->format->formatNumber($data['jumkirim']),
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
}