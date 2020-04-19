<?php
class ReportgrreturController extends Controller
{
  public $menuname = 'reportgrretur';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail() {
    if (isset($_GET['grid']))
      echo $this->actionsearchdetail();
    else
      $this->renderPartial('index', array());
  }
	public function search() {
    header('Content-Type: application/json');
    $grreturid   = isset($_POST['grreturid']) ? $_POST['grreturid'] : '';
    $grreturno   = isset($_POST['grreturno']) ? $_POST['grreturno'] : '';
    $plantid   = isset($_POST['plantid']) ? $_POST['plantid'] : '';
    $plantcode   = isset($_POST['plantcode']) ? $_POST['plantcode'] : '';
    $companyid   = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $companyname   = isset($_POST['companyname']) ? $_POST['companyname'] : '';
    $grreturdate = isset($_POST['grreturdate']) ? $_POST['grreturdate'] : '';
    $poheaderid  = isset($_POST['poheaderid']) ? $_POST['poheaderid'] : '';
    $pono  = isset($_POST['poheaderid']) ? $_POST['pono'] : '';
    $headernote  = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $supplier  = isset($_POST['supplier']) ? $_POST['supplier'] : '';
    $grreturid   = isset($_GET['q']) ? $_GET['q'] : $grreturid;
    $grreturno   = isset($_GET['q']) ? $_GET['q'] : $grreturno;
    $plantid   = isset($_GET['q']) ? $_GET['q'] : $plantid;
    $plantcode   = isset($_GET['q']) ? $_GET['q'] : $plantcode;
    $companyid   = isset($_GET['q']) ? $_GET['q'] : $companyid;
    $companyname   = isset($_GET['q']) ? $_GET['q'] : $companyname;
    $grreturdate = isset($_GET['q']) ? $_GET['q'] : $grreturdate;
    $poheaderid  = isset($_GET['q']) ? $_GET['q'] : $poheaderid;
    $pono  = isset($_GET['q']) ? $_GET['q'] : $pono;
    $headernote  = isset($_GET['q']) ? $_GET['q'] : $headernote;
    $supplier  = isset($_GET['q']) ? $_GET['q'] : $supplier;
    $page        = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows        = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort        = isset($_POST['sort']) ? strval($_POST['sort']) : 'grreturid';
    $order       = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset      = ($page - 1) * $rows;
    $page        = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows        = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort        = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order       = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset      = ($page - 1) * $rows;
    $result      = array();
    $row         = array();
		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('grretur t')->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')->leftjoin('plant c', 'c.plantid = a.plantid')->leftjoin('company d', 'd.companyid = c.companyid')->where("
			(coalesce(t.grreturid,'') like :grreturid) and 
			(coalesce(companyname,'') like :companyname) and
			(coalesce(grreturdate,'') like :grreturdate) and
			(coalesce(pono,'') like :pono) and
			(coalesce(plantcode,'') like :plantcode) and
			(coalesce(grreturno,'') like :grreturno) and
			(coalesce(t.fullname,'') like :fullname) and
			(coalesce(t.headernote,'') like :headernote) 
			and d.companyid in (".getUserObjectValues('company').")", array(
		':grreturid' => '%' . $grreturid . '%',
			':companyname' => '%' . $companyname . '%',
			':grreturdate' => '%' . $grreturdate . '%',
			':pono' => '%' . $pono . '%',
			':plantcode' => '%' . $plantcode . '%',
			':grreturno' => '%' . $grreturno . '%',
		':fullname' => '%' . $supplier . '%',
			':headernote' => '%' . $headernote . '%',
		))->queryScalar();
    $result['total'] = $cmd;
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.pono,c.plantcode,d.companyid,d.companycode')->from('grretur t')->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')->leftjoin('plant c', 'c.plantid = a.plantid')->leftjoin('company d', 'd.companyid = c.companyid')->where("
          (coalesce(t.grreturid,'') like :grreturid) and 
      (coalesce(companyname,'') like :companyname) and
      (coalesce(grreturdate,'') like :grreturdate) and
      (coalesce(pono,'') like :pono) and
      (coalesce(plantcode,'') like :plantcode) and
          (coalesce(grreturno,'') like :grreturno) and
          (coalesce(t.fullname,'') like :fullname) and
          (coalesce(t.headernote,'') like :headernote) and d.companyid in (".getUserObjectValues('company').")", array(
      ':grreturid' => '%' . $grreturid . '%',
        ':companyname' => '%' . $companyname . '%',
        ':grreturdate' => '%' . $grreturdate . '%',
        ':pono' => '%' . $pono . '%',
        ':plantcode' => '%' . $plantcode . '%',
        ':grreturno' => '%' . $grreturno . '%',
      ':fullname' => '%' . $supplier . '%',
        ':headernote' => '%' . $headernote . '%',
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'grreturid' => $data['grreturid'],
        'grreturno' => $data['grreturno'],
        'grreturdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['grreturdate'])),
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'fullname' => $data['fullname'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusgrretur' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
}