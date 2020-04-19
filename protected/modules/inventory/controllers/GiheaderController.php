<?php
class GiheaderController extends Controller {
  public $menuname = 'giheader';
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
	public function actionIndexjurnal() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchjurnal();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
		$id = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'giheaderid' => $id,
		));
  }
  public function search() {
    header('Content-Type: application/json');
    $giheaderid = GetSearchText(array('POST','GET'),'giheaderid','','int');
    $plantid = GetSearchText(array('POST','GET'),'plantid',0,'int');
    $soheaderid = GetSearchText(array('POST','GET'),'soheaderid',0,'int');
    $plantcode = GetSearchText(array('POST','GET'),'plantcode');
    $productname = GetSearchText(array('POST','GET'),'productname');
    $addressname = GetSearchText(array('POST','GET','Q'),'addressname');
    $pocustno = GetSearchText(array('POST','Q'),'pocustno');
    $nomobil = GetSearchText(array('POST','Q'),'nomobil');
    $sono = GetSearchText(array('POST','GET'),'sono');
    $ekspedisi = GetSearchText(array('POST','Q'),'ekspedisi');
    $gidate = GetSearchText(array('POST','Q'),'gidate');
    $gino = GetSearchText(array('POST','Q'),'gino');
    $headernote = GetSearchText(array('POST','Q'),'headernote');
    $customer = GetSearchText(array('POST','Q'),'customer');
    $pocustno = GetSearchText(array('POST','Q'),'pocustno');
    $sopir = GetSearchText(array('POST','Q'),'sopir');
    $productname = GetSearchText(array('POST','Q'),'productname');
    $recordstatus = GetSearchText(array('POST','Q'),'recordstatus');
    $page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','giheaderid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		if (!isset($_GET['getdata'])) {
			if (isset($_GET['gipl'])) {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('giheader t')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('address a', 'a.addressid = t.addresstoid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('addressbook d', 'd.addressbookid = t.supplierid')
						->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
						->leftjoin('addressbook f', 'f.addressbookid = t.addressbookid')
				->where("
						((coalesce(giheaderid,'') like :giheaderid) 
						or (coalesce(b.plantcode,'') like :plantcode) 
						or (coalesce(t.gino,'') like :gino) 
						or (coalesce(t.headernote,'') like :headernote)  
						or (coalesce(e.sono,'') like :sono)  
						or (coalesce(t.pocustno,'') like :pocustno)  
						or (coalesce(suppliername,'') like :ekspedisi)  
						or (coalesce(customername,'') like :customer)  
						or (coalesce(t.sopir,'') like :sopir)  
						or (coalesce(t.nomobil,'') like :nomobil)  
						or (coalesce(a.addressname,'') like :addressname) 
						or (coalesce(t.gidate,'') like :gidate)) 
						and t.gino is not null 
						and t.plantid = ".$plantid."
						and t.recordstatus = getwfmaxstatbywfname('appgi')
						and t.plantid in (".getUserObjectValues('plant').")
						",
				array(
						':giheaderid' =>  $giheaderid ,
						':plantcode' =>  $plantcode ,
						':gino' =>  $gino ,
						':sono' =>  $sono ,
						':pocustno' =>  $pocustno ,
						':sopir' =>  $sopir ,
						':nomobil' =>  $nomobil ,
						':addressname' =>  $addressname ,
						':ekspedisi' =>  $ekspedisi ,
						':customer' =>  $customer ,
						':headernote' =>  $headernote ,
						':gidate' =>  $gidate 
					))->queryScalar();
			} else
			if (isset($_GET['gir'])) {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('giheader t')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('address a', 'a.addressid = t.addresstoid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('addressbook d', 'd.addressbookid = t.supplierid')
						->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
						->leftjoin('addressbook f', 'f.addressbookid = t.addressbookid')
				->where("
						((coalesce(giheaderid,'') like :giheaderid) 
						or (coalesce(b.plantcode,'') like :plantcode) 
						or (coalesce(t.gino,'') like :gino) 
						or (coalesce(t.headernote,'') like :headernote)  
						or (coalesce(e.sono,'') like :sono)  
						or (coalesce(t.pocustno,'') like :pocustno)  
						or (coalesce(suppliername,'') like :ekspedisi)  
						or (coalesce(customername,'') like :customer)  
						or (coalesce(t.sopir,'') like :sopir)  
						or (coalesce(t.nomobil,'') like :nomobil)  
						or (coalesce(a.addressname,'') like :addressname)  
						or (coalesce(t.gidate,'') like :gidate)) 
						and t.gino is not null 
						and t.plantid = ".$plantid."
						and t.recordstatus = 3
						and t.plantid in (".getUserObjectValues('plant').")
						and t.giheaderid in (select zz.giheaderid 
					from gidetail zz 
					where zz.qty > zz.qtyretur)",
				array(
						':giheaderid' =>  $giheaderid ,
						':plantcode' =>  $plantcode ,
						':gino' =>  $gino ,
						':sono' =>  $sono ,
						':pocustno' =>  $pocustno ,
						':sopir' =>  $sopir ,
						':nomobil' =>  $nomobil ,
						':addressname' =>  $addressname ,
						':ekspedisi' =>  $ekspedisi ,
						':customer' =>  $customer ,
						':headernote' =>  $headernote ,
						':gidate' =>  $gidate 
					))->queryScalar();
			}
			else if (isset($_GET['invoice'])) {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('giheader t')
					->leftjoin('plant b', 'b.plantid = t.plantid')
					->leftjoin('address a', 'a.addressid = t.addresstoid')
					->leftjoin('company c', 'c.companyid = b.companyid')
					->leftjoin('addressbook d', 'd.addressbookid = t.supplierid')
					->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
					->leftjoin('addressbook f', 'f.addressbookid = t.addressbookid')
					->where("
						((coalesce(giheaderid,'') like :giheaderid) 
						or (coalesce(b.plantcode,'') like :plantcode) 
						or (coalesce(t.gino,'') like :gino) 
						or (coalesce(t.headernote,'') like :headernote)  
						or (coalesce(e.sono,'') like :sono)  
						or (coalesce(t.pocustno,'') like :pocustno)  
						or (coalesce(suppliername,'') like :ekspedisi)  
						or (coalesce(customername,'') like :customer)  
						or (coalesce(t.sopir,'') like :sopir)  
						or (coalesce(t.nomobil,'') like :nomobil)  
						or (coalesce(a.addressname,'') like :addressname)  
						or (coalesce(t.gidate,'') like :gidate)) 
						and t.gino is not null 
						and t.recordstatus = getwfmaxstatbywfname('appgi')
						and t.plantid in (".getUserObjectValues('plant').")
						and t.soheaderid = ".$soheaderid." 
						",
				array(
						':giheaderid' =>  $giheaderid ,
						':plantcode' =>  $plantcode ,
						':gino' =>  $gino ,
						':sono' =>  $sono ,
						':pocustno' =>  $pocustno ,
						':sopir' =>  $sopir ,
						':nomobil' =>  $nomobil ,
						':addressname' =>  $addressname ,
						':ekspedisi' =>  $ekspedisi ,
						':customer' =>  $customer ,
						':headernote' =>  $headernote ,
						':gidate' =>  $gidate 
					))->queryScalar();
			}
			else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
				->from('giheader t')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('address a', 'a.addressid = t.addresstoid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('addressbook d', 'd.addressbookid = t.supplierid')
						->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
						->leftjoin('addressbook f', 'f.addressbookid = t.addressbookid')
				->where("
						((coalesce(giheaderid,'') like :giheaderid) 
						and (coalesce(b.plantcode,'') like :plantcode) 
						and (coalesce(t.gino,'') like :gino) 
						and (coalesce(t.headernote,'') like :headernote)  
						and (coalesce(e.sono,'') like :sono)  
						and (coalesce(t.pocustno,'') like :pocustno)  
						and (coalesce(suppliername,'') like :ekspedisi)  
						and (coalesce(customername,'') like :customer)  
						and (coalesce(t.sopir,'') like :sopir)  
						and (coalesce(t.nomobil,'') like :nomobil)  
						and (coalesce(a.addressname,'') like :addressname)  
						and (coalesce(t.gidate,'') like :gidate)) 
						and t.plantid in (".getUserObjectValues('plant').")".
				(($productname != '%%')?" and t.giheaderid in (
					select distinct giheaderid 
					from gidetail za 
					left join product zb on zb.productid = za.productid 
					where zb.productname like '%".$productname."%'
				)":'').
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				" and t.recordstatus in (".getUserRecordStatus('listgi').")
						",
				array(
						':giheaderid' =>  '%'.$giheaderid.'%' ,
						':plantcode' =>  $plantcode ,
						':gino' =>  $gino ,
						':sono' =>  $sono ,
						':pocustno' =>  $pocustno ,
						':sopir' =>  $sopir ,
						':nomobil' =>  $nomobil ,
						':addressname' =>  $addressname ,
						':ekspedisi' =>  $ekspedisi ,
						':customer' =>  $customer ,
						':headernote' =>  $headernote ,
						':gidate' =>  $gidate 
					))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['gipl'])) {
			$cmd = Yii::app()->db->createCommand()->select('t.*,e.sono,b.plantcode,a.addressname,b.companyid,c.companyname,d.fullname as suppliername,f.fullname as customername')
				->from('giheader t')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('address a', 'a.addressid = t.addresstoid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('addressbook d', 'd.addressbookid = t.supplierid')
						->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
						->leftjoin('addressbook f', 'f.addressbookid = t.addressbookid')
				->where("
						((coalesce(giheaderid,'') like :giheaderid) 
						or (coalesce(b.plantcode,'') like :plantcode) 
						or (coalesce(t.gino,'') like :gino) 
						or (coalesce(t.headernote,'') like :headernote)  
						or (coalesce(e.sono,'') like :sono)  
						or (coalesce(t.pocustno,'') like :pocustno)  
						or (coalesce(suppliername,'') like :ekspedisi)  
						or (coalesce(customername,'') like :customer)  
						or (coalesce(t.sopir,'') like :sopir)  
						or (coalesce(t.nomobil,'') like :nomobil)  
						or (coalesce(a.addressname,'') like :addressname)  
						or (coalesce(t.gidate,'') like :gidate)) 
						and t.gino is not null 
						and t.plantid = ".$plantid."
						and t.recordstatus = getwfmaxstatbywfname('appgi')
						and t.plantid in (".getUserObjectValues('plant').")
						",
				array(
						':giheaderid' =>  $giheaderid ,
						':plantcode' =>  $plantcode ,
						':gino' =>  $gino ,
						':sono' =>  $sono ,
						':pocustno' =>  $pocustno ,
						':sopir' =>  $sopir ,
						':nomobil' =>  $nomobil ,
						':addressname' =>  $addressname ,
						':ekspedisi' =>  $ekspedisi ,
						':customer' =>  $customer ,
						':headernote' =>  $headernote ,
						':gidate' =>  $gidate 
				))->order($sort . ' ' . $order)->queryAll();
		} 
		else if (isset($_GET['gir'])) {
			$cmd = Yii::app()->db->createCommand()->select('t.*,e.sono,b.plantcode,a.addressname,b.companyid,c.companyname,d.fullname as suppliername,f.fullname as customername')
				->from('giheader t')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('address a', 'a.addressid = t.addresstoid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('addressbook d', 'd.addressbookid = t.supplierid')
						->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
						->leftjoin('addressbook f', 'f.addressbookid = t.addressbookid')
				->where("
						((coalesce(giheaderid,'') like :giheaderid) 
						or (coalesce(b.plantcode,'') like :plantcode) 
						or (coalesce(t.gino,'') like :gino) 
						or (coalesce(t.headernote,'') like :headernote)  
						or (coalesce(e.sono,'') like :sono)  
						or (coalesce(t.pocustno,'') like :pocustno)  
						or (coalesce(suppliername,'') like :ekspedisi)  
						or (coalesce(customername,'') like :customer)  
						or (coalesce(t.sopir,'') like :sopir)  
						or (coalesce(t.nomobil,'') like :nomobil)  
						or (coalesce(a.addressname,'') like :addressname)  
						or (coalesce(t.gidate,'') like :gidate)) 
						and t.gino is not null 
						and t.plantid = ".$plantid."
						and t.recordstatus = 3
						and t.plantid in (".getUserObjectValues('plant').")
						and t.giheaderid in (select zz.giheaderid 
					from gidetail zz 
					where zz.qty > zz.qtyretur)",
				array(
						':giheaderid' =>  $giheaderid ,
						':plantcode' =>  $plantcode ,
						':gino' =>  $gino ,
						':sono' =>  $sono ,
						':pocustno' =>  $pocustno ,
						':sopir' =>  $sopir ,
						':nomobil' =>  $nomobil ,
						':addressname' =>  $addressname ,
						':ekspedisi' =>  $ekspedisi ,
						':customer' =>  $customer ,
						':headernote' =>  $headernote ,
						':gidate' =>  $gidate 
				))->order($sort . ' ' . $order)->queryAll();
		}else 
    if (isset($_GET['invoice'])) {
			$cmd = Yii::app()->db->createCommand()->select('t.*,e.sono,b.plantcode,a.addressname,b.companyid,c.companyname,d.fullname as suppliername,f.fullname as customername')
				->from('giheader t')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('address a', 'a.addressid = t.addresstoid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('addressbook d', 'd.addressbookid = t.supplierid')
						->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
						->leftjoin('addressbook f', 'f.addressbookid = t.addressbookid')
				->where("
						((coalesce(giheaderid,'') like :giheaderid) 
						or (coalesce(b.plantcode,'') like :plantcode) 
						or (coalesce(t.gino,'') like :gino) 
						or (coalesce(t.headernote,'') like :headernote)  
						or (coalesce(e.sono,'') like :sono)  
						or (coalesce(t.pocustno,'') like :pocustno)  
						or (coalesce(suppliername,'') like :ekspedisi)  
						or (coalesce(customername,'') like :customer)  
						or (coalesce(t.sopir,'') like :sopir)  
						or (coalesce(t.nomobil,'') like :nomobil)  
						or (coalesce(a.addressname,'') like :addressname)  
						or (coalesce(t.gidate,'') like :gidate)) 
						and t.gino is not null 
						and t.recordstatus = getwfmaxstatbywfname('appgi')
						and t.plantid in (".getUserObjectValues('plant').")
						and t.soheaderid = ".$soheaderid." 
						",
				array(
						':giheaderid' =>  $giheaderid ,
						':plantcode' =>  $plantcode ,
						':gino' =>  $gino ,
						':sono' =>  $sono ,
						':pocustno' =>  $pocustno ,
						':sopir' =>  $sopir ,
						':nomobil' =>  $nomobil ,
						':addressname' =>  $addressname ,
						':ekspedisi' =>  $ekspedisi ,
						':customer' =>  $customer ,
						':headernote' =>  $headernote ,
						':gidate' =>  $gidate 			
				))->order($sort . ' ' . $order)->queryAll();
		} else {
			$cmd = Yii::app()->db->createCommand()->select('t.*,e.sono,b.plantcode,a.addressname,b.companyid,c.companyname,d.fullname as suppliername,f.fullname as customername')
				->from('giheader t')
						->leftjoin('plant b', 'b.plantid = t.plantid')
						->leftjoin('address a', 'a.addressid = t.addresstoid')
						->leftjoin('company c', 'c.companyid = b.companyid')
						->leftjoin('addressbook d', 'd.addressbookid = t.supplierid')
						->leftjoin('soheader e', 'e.soheaderid = t.soheaderid')
						->leftjoin('addressbook f', 'f.addressbookid = t.addressbookid')
						->where("
						((coalesce(giheaderid,'') like :giheaderid) 
						and (coalesce(b.plantcode,'') like :plantcode) 
						and (coalesce(t.gino,'') like :gino) 
						and (coalesce(t.headernote,'') like :headernote)  
						and (coalesce(e.sono,'') like :sono)  
						and (coalesce(t.pocustno,'') like :pocustno)  
						and (coalesce(suppliername,'') like :ekspedisi)  
						and (coalesce(customername,'') like :customer)  
						and (coalesce(t.sopir,'') like :sopir)  
						and (coalesce(t.nomobil,'') like :nomobil)  
						and (coalesce(a.addressname,'') like :addressname)  
						and (coalesce(t.gidate,'') like :gidate)) 
						and t.plantid in (".getUserObjectValues('plant').")".
				(($productname != '%%')?" and t.giheaderid in (
					select distinct giheaderid 
					from gidetail za 
					left join product zb on zb.productid = za.productid 
					where zb.productname like '%".$productname."%'
				)":'').
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				"	and t.recordstatus in (".getUserRecordStatus('listgi').")
						",
				array(
						':giheaderid' =>  '%'.$giheaderid.'%' ,
						':plantcode' =>  $plantcode ,
						':gino' =>  $gino ,
						':sono' =>  $sono ,
						':pocustno' =>  $pocustno ,
						':sopir' =>  $sopir ,
						':nomobil' =>  $nomobil ,
						':addressname' =>  $addressname ,
						':ekspedisi' =>  $ekspedisi ,
						':customer' =>  $customer ,
						':headernote' =>  $headernote ,
						':gidate' =>  $gidate 				
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		}
		 foreach ($cmd as $data) {
      $row[] = array(
        'giheaderid' => $data['giheaderid'],
        'gino' => $data['gino'],
        'gidate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['gidate'])),
        'soheaderid' => $data['soheaderid'],
        'sono' => $data['sono'],
        'pocustno' => $data['pocustno'],
        'addressbookid' => $data['addressbookid'],
        'customername' => $data['customername'],
				'suppliername' => $data['suppliername'],
        'supplierid' => $data['supplierid'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'addresstoid' => $data['addresstoid'],
        'addressname' => $data['addressname'],
        'sopir' => $data['sopir'],
        'nomobil' => $data['nomobil'],
        'pebno' => $data['pebno'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusname' => $data['statusname']
      );
			}
			$result = array_merge($result, array(
				'rows' => $row
			));
	} else {
    $giheaderid = GetSearchText(array('POST','Q','GET'),'giheaderid',0,'int');
			$cmd = Yii::app()->db->createCommand("
				select a.giheaderid, a.soheaderid, a.pocustno, a.addressbookid, a.addresstoid, a.suppliername, a.pebno, a.sopir, 
				a.nomobil, b.sono,a.supplierid
				from giheader a
				left join soheader b on b.soheaderid = a.soheaderid
				where a.giheaderid = ".$giheaderid)->queryRow();
			$result = $cmd;
		}
    return CJSON::encode($result);
	}
  public function actionSearchdetail() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'sodetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('gidetail t')
					->leftjoin('giheader g', 'g.giheaderid = t.giheaderid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('storagebin b', 'b.storagebinid = t.storagebinid')
					->leftjoin('sloc d', 'd.slocid = t.slocid')
					->leftjoin('productoutput e', 'e.productoutputid = t.productoutputid')
					->leftjoin('materialtype f', 'f.materialtypeid = a.materialtypeid')
					->where('t.giheaderid = :giheaderid',
			array(
				':giheaderid' => $id
			))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
					->select('t.*,a.productcode,a.productname,d.sloccode,b.description as storagebinto, t.certoaid, e.productoutputno,f.materialtypecode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code,
						GetStdQty2(a.productid) as stdqty2,
						GetStdQty3(a.productid) as stdqty3,
						GetStdQty4(a.productid) as stdqty4,
						Getstockbin(t.productid,t.uomid,t.slocid,t.storagebinid) as stock,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom4id) as uom4code')
					->from('gidetail t')
					->leftjoin('giheader g', 'g.giheaderid = t.giheaderid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('storagebin b', 'b.storagebinid = t.storagebinid')
					->leftjoin('sloc d', 'd.slocid = t.slocid')
					->leftjoin('productoutput e', 'e.productoutputid = t.productoutputid')
					->leftjoin('materialtype f', 'f.materialtypeid = a.materialtypeid')
					->where('t.giheaderid = :giheaderid', array(
		':giheaderid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'gidetailid' => $data['gidetailid'],
        'giheaderid' => $data['giheaderid'],
        'sodetailid' => $data['sodetailid'],
        'productid' => $data['productid'],
				'productcode' => $data['productcode'],
				'productname' => $data['productname'],
				'materialtypecode' => $data['materialtypecode'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'qty2' => Yii::app()->format->formatNumber($data['qty2']),
				'qty3' => Yii::app()->format->formatNumber($data['qty3']),
				'qty4' => Yii::app()->format->formatNumber($data['qty4']),
				'stdqty2' => Yii::app()->format->formatNumber($data['stdqty2']),
				'stdqty3' => Yii::app()->format->formatNumber($data['stdqty3']),
				'stdqty4' => Yii::app()->format->formatNumber($data['stdqty4']),
				'stock' => Yii::app()->format->formatNumber($data['stock']),
				'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'uom2id' => $data['uom2id'],
        'uom2code' => $data['uom2code'],
				'uom3id' => $data['uom3id'],
        'uom3code' => $data['uom3code'],
				'uom4id' => $data['uom4id'],
        'uom4code' => $data['uom4code'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
				'storagebinid' => $data['storagebinid'],
        'storagebinto' => $data['storagebinto'],
        'productoutputid' => $data['productoutputid'],
        'productoutputno' => $data['productoutputno'],
        'lotno' => $data['lotno'],
				'certoaid' => $data['certoaid'],
				'itemnote' => $data['itemnote']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
	}
	public function actionSearchJurnal() {
    header('Content-Type: application/json');
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'gijurnalid';
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
			->from('gijurnal t')
			->leftjoin('account a', 'a.accountid = t.accountid')
			->leftjoin('currency c', 'c.currencyid = t.currencyid')
			->where('t.giheaderid = :giheaderid',
			array(
				':giheaderid' => $id
		))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.accountcode,a.accountname,c.currencyname')
			->from('gijurnal t')
			->leftjoin('account a', 'a.accountid = t.accountid')
			->leftjoin('currency c', 'c.currencyid = t.currencyid')
			->where('t.giheaderid = :giheaderid', array(
				':giheaderid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'gijurnalid' => $data['gijurnalid'],
        'giheaderid' => $data['giheaderid'],
        'accountid' => $data['accountid'],
				'accountcode' => $data['accountcode'],
				'accountname' => $data['accountname'],
				'currencyid' => $data['currencyid'],
				'currencyname' => $data['currencyname'],
        'debit' => Yii::app()->format->formatNumber($data['debit']),
				'credit' => Yii::app()->format->formatNumber($data['credit']),
				'detailnote' => $data['detailnote'],
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
		));
		$sql = "select sum(debit) as debit, sum(credit) as credit from gijurnal where giheaderid = ".$id;
		$cmd = Yii::app()->db->createCommand($sql)->queryRow();
		$footer[] = array(
      'accountname' => 'Total',
      'debit' => Yii::app()->format->formatNumber($cmd['debit']),
      'credit' => Yii::app()->format->formatNumber($cmd['credit']),
    );
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionGeneratedetail() {
    if (isset($_POST['id'])) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateGISO(:vid,:vhid,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_INT);
        $command->execute();
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    }
  }
	private function ModifyData($connection,$arraydata) {
		$id = (int)$arraydata[0];
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$sql     = 'call Modifgiheader(:vid,:vgidate,:vplantid,:vsoheaderid,:vpocustno,:vaddressbookid,:vsupplierid,:vnomobil,:vsopir,:vaddresstoid,:vpebno,
			:vheadernote,:vdatauser)';
		$command = $connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vgidate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vsoheaderid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vpocustno', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vsupplierid', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vnomobil', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vsopir', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vaddresstoid', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vpebno', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["FileGiheader"]["name"]);
		if (move_uploaded_file($_FILES["FileGiheader"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$abid = '';$nourut = '';
			$connection  = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$nourut = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
					$gino = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$gidate = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$sono = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$soheaderid = Yii::app()->db->createCommand("select soheaderid from soheader where sono = '".$sono."' and companyid = ".$companyid)->queryScalar();
					$abid = Yii::app()->db->createCommand("select giheaderid from giheader where gino = '".$gino."' and companyid = ".$companyid)->queryScalar();
					if ($abid == '') {		
						$headernote = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
						$recordstatus = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
						$this->ModifyData(array('',date(Yii::app()->params['datetodb'], strtotime($gidate)),$gino,$soheaderid,$headernote,$recordstatus));
						$abid = Yii::app()->db->createCommand("select giheaderid from giheader where gino = '".$gino."' and companyid = ".$companyid)->queryScalar();
					}
					if ($abid != '') {
						if ($objWorksheet->getCellByColumnAndRow(6, $row)->getValue() != '') {
							$productname = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
							$productid = Yii::app()->db->createCommand("select productid from product where productname = '".$productname."'")->queryScalar();
							$qty = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
							$qtystdkg = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
							$qtystdmtr = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
							$sloccode = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
							$slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
							$storagebin = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
							$storagebinid = Yii::app()->db->createCommand("select storagebinid from storagebin where slocid = '".$slocid."' and description = '".$storagebin."'")->queryScalar();
							$itemnote = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
							$this->ModifyDataDetail(array('',$abid,$productid,$qty,$slocid,$storagebinid,$itemnote));
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
 public function actionSave() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['giheader-giheaderid'])?$_POST['giheader-giheaderid']:''),
				date(Yii::app()->params['datetodb'], strtotime($_POST['giheader-gidate'])),
				$_POST['giheader-plantid'],
				$_POST['giheader-soheaderid'],
				$_POST['giheader-pocustno'],
				$_POST['giheader-addressbookid'],
				$_POST['giheader-supplierid'],
				$_POST['giheader-nomobil'],
				$_POST['giheader-sopir'],
				$_POST['giheader-addresstoid'],
				$_POST['giheader-pebno'],
				$_POST['giheader-headernote']));
			$transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
	public function actionSavedetail() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call InsertGidetail(:vgiheaderid,:vproductid,:vqty,:vqty2,:vqty3,:vqty4,:vuomid,:vuom2id,:vuom3id,:vuom4id,
						:vslocid,:vstoragebinid,:vlotno,:vcertoaid,:vitemnote,:vdatauser)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call UpdateGidetail(:vid,:vgiheaderid,:vproductid,:vqty,:vqty2,:vqty3,:vqty4,:vuomid,:vuom2id,:vuom3id,:vuom4id,
						:vslocid,:vstoragebinid,:vlotno,:vcertoaid,:vitemnote,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['gidetailid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['gidetailid']);
      }
      $command->bindvalue(':vgiheaderid', $_POST['giheaderid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
			$command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
			$command->bindvalue(':vqty2', $_POST['qty2'], PDO::PARAM_STR);
			$command->bindvalue(':vqty3', $_POST['qty3'], PDO::PARAM_STR);
			$command->bindvalue(':vqty4', $_POST['qty4'], PDO::PARAM_STR);
			$command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
      $command->bindvalue(':vuom2id', $_POST['uom2id'], PDO::PARAM_STR);
      $command->bindvalue(':vuom3id', $_POST['uom3id'], PDO::PARAM_STR);
      $command->bindvalue(':vuom4id', $_POST['uom4id'], PDO::PARAM_STR);
			$command->bindvalue(':vslocid', $_POST['slocid'], PDO::PARAM_STR);
			$command->bindvalue(':vstoragebinid', $_POST['storagebinid'], PDO::PARAM_STR);
			$command->bindvalue(':vlotno', $_POST['lotno'], PDO::PARAM_STR);
			$command->bindvalue(':vcertoaid', $_POST['certoaid'], PDO::PARAM_STR);
			$command->bindvalue(':vitemnote', $_POST['itemnote'], PDO::PARAM_STR);
      $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
  public function actionReject() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call RejectGI(:vid,:vdatauser)';
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
      GetMessage(true, 'chooseone');
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
        $sql     = 'call ApproveGi(:vid,:vdatauser)';
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
      GetMessage(true, 'chooseone');
    }
  }
  public function actionPurge() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgegiheader(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  public function actionPurgedetail() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call PurgeGidetail(:vid,:vdatauser)';
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
      GetMessage(true, 'chooseone');
    }
  }
	public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select e.companyid, a.gino,a.gidate,b.sono ,a.giheaderid,a.headernote,k.plantcode,b.pocustno,
						a.recordstatus,c.fullname as customer,h.fullname as sales,f.cityname,g.addressname as shipto,
						(
						select distinct g.mobilephone
						from addresscontact g 
						where g.addressbookid = c.addressbookid
						limit 1 
						) as hp,
							(
						select distinct h.phoneno
						from address h
						where h.addressbookid = c.addressbookid
						limit 1 
						) as phone, i.fullname as expedisi,a.nomobil,a.sopir,a.pebno,l.productname,j.qty,j.qty2,j.qty3,j.qty4,
						m.uomcode,n.uomcode as uom2code,o.uomcode as uom3code,p.uomcode as uom4code,q.sloccode,r.description as storagedesc,
						j.certoaid,j.lotno,j.itemnote,b.pocustno 
						from giheader a
						left join soheader b on b.soheaderid = a.soheaderid 
						left join addressbook c on c.addressbookid = b.addressbookid
						left join sales d on d.salesid = b.salesid
						left join plant k on k.plantid = a.plantid
						left join company e on e.companyid = k.companyid
						left join city f on f.cityid = e.cityid
						left join address g on g.addressid = a.addresstoid  
						left join employee h on h.employeeid = d.employeeid  
						left join addressbook i on i.addressbookid = a.supplierid 
						left join gidetail j on j.giheaderid = a.giheaderid 
						left join product l on l.productid = j.productid 
						left join unitofmeasure m on m.unitofmeasureid = j.uomid 
						left join unitofmeasure n on n.unitofmeasureid = j.uom2id
						left join unitofmeasure o on o.unitofmeasureid = j.uom3id
						left join unitofmeasure p on p.unitofmeasureid = j.uom4id
						left join sloc q on q.slocid = j.slocid
						left join storagebin r on r.storagebinid = j.storagebinid 
		";
		$giheaderid = GetSearchText(array('POST'),'giheaderid');
    $plantcode = GetSearchText(array('POST','GET'),'plantcode');
    $productname = GetSearchText(array('POST','GET'),'productname');
    $addressname = GetSearchText(array('POST','GET','Q'),'addressname');
    $pocustno = GetSearchText(array('POST','Q'),'pocustno');
    $nomobil = GetSearchText(array('POST','Q'),'nomobil');
    $sono = GetSearchText(array('POST','GET'),'sono');
    $ekspedisi = GetSearchText(array('POST','GET'),'ekspedisi');
    $gidate = GetSearchText(array('POST','GET'),'gidate');
    $gino = GetSearchText(array('POST','GET'),'gino');
    $headernote = GetSearchText(array('POST','GET'),'headernote');
    $customer = GetSearchText(array('POST','GET'),'customer');
    $sopir = GetSearchText(array('POST','GET'),'sopir');
    $gidate = GetSearchText(array('POST','GET'),'gidate');
    $recordstatus = GetSearchText(array('POST','GET'),'recordstatus');		
		$sql .= "
			where coalesce(a.gino,'') like '".$gino."' 
			and coalesce(k.plantcode,'') like '".$plantcode."' 
			and coalesce(g.addressname,'') like '".$addressname."' 
			and coalesce(b.pocustno,'') like '".$pocustno."' 
			and coalesce(a.nomobil,'') like '".$nomobil."' 
			and coalesce(b.sono,'') like '".$sono."' 
			and coalesce(i.fullname,'') like '".$ekspedisi."' 
			and coalesce(c.fullname,'') like '".$customer."' 
			and coalesce(a.sopir,'') like '".$sopir."' 
		";
		if ($recordstatus != '%%') {
			$sql .= " and a.recordstatus like '".$recordstatus."'";
		}
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.giheaderid in (" . $_GET['id'] . ")";
    }
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.giheaderid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = getCatalog('giheader');
    $this->pdf->AddPage('P', array(
      320,
      140
    ));
    $this->pdf->AddFont('tahoma', '', 'tahoma.php');
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('tahoma');
    foreach ($dataReader as $row) {
      $this->pdf->setFontSize(8);
      $this->pdf->text(10, $this->pdf->gety() + 0, 'No ');
      $this->pdf->text(25, $this->pdf->gety() + 0, ': ' . $row['gino']);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'Sales ');
      $this->pdf->text(25, $this->pdf->gety() + 5, ': ' . $row['sales']);
      $this->pdf->text(80, $this->pdf->gety() + 0, 'No. SO ');
      $this->pdf->text(100, $this->pdf->gety() + 0, ': ' . $row['sono']);
      $this->pdf->text(80, $this->pdf->gety() + 5, 'No. PO Cust ');
      $this->pdf->text(100, $this->pdf->gety() + 5, ': ' . $row['pocustno']);
      $this->pdf->text(160, $this->pdf->gety() + 0, $row['cityname'] . ', ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['gidate'])));
      $this->pdf->text(160, $this->pdf->gety() + 5, 'Kepada Yth, ');
      $this->pdf->text(160, $this->pdf->gety() + 10, $row['customer']);
      $this->pdf->text(10, $this->pdf->gety() + 15, 'Dengan hormat,');
      $this->pdf->text(10, $this->pdf->gety() + 20, 'Bersama ini kami kirimkan barang-barang sebagai berikut:');
      $sql1        = "select b.productname, 
								sum(ifnull(a.qty,0)) as vqty,
								sum(ifnull(a.qty2,0)) as vqty2,
								sum(ifnull(a.qty3,0)) as vqty3,
								sum(ifnull(a.qty4,0)) as vqty4, 
								c.uomcode,
								g.uomcode as uom2code,
								h.uomcode as uom3code,
								i.uomcode as uom4code,
								d.description,f.description as rak,itemnote,a.lotno
								from gidetail a
								inner join product b on b.productid = a.productid
								inner join unitofmeasure c on c.unitofmeasureid = a.uomid
								inner join unitofmeasure g on g.unitofmeasureid = a.uom2id
								left join unitofmeasure h on h.unitofmeasureid = a.uom3id
								left join unitofmeasure i on i.unitofmeasureid = a.uom4id
								inner join sloc d on d.slocid = a.slocid
								left join storagebin f on f.storagebinid = a.storagebinid
								where giheaderid = " . $row['giheaderid'] . " group by b.productname,a.sodetailid order by sodetailid";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 25);
      $this->pdf->colalign = array(
        'L',
        'L',
        'C',
        'C',
        'C',
        'C',
        'L',
        'L',
        'L',
        'L',
        'L',
        'C',
        'C',
        'L'
      );
      $this->pdf->setwidths(array(
        8,
        70,
        20,
        20,
        20,
        20,
        15,
        15,
        15,
        15,
        35,
        25,
        30     ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
				'Qty2',
				'Qty3',
				'Qty4',
        'Unit',
        'Unit2',
        'Unit3',
        'Unit4',
        'Gudang - Rak',
        'No Lot',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'R',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatCurrency($row1['vqty']),
          Yii::app()->format->formatCurrency($row1['vqty2']),
          Yii::app()->format->formatCurrency($row1['vqty3']),
          Yii::app()->format->formatCurrency($row1['vqty4']),
          $row1['uomcode'],
          $row1['uom2code'],
          $row1['uom3code'],
          $row1['uom4code'],
          $row1['description'] . ' - ' . $row1['rak'],
					 $row1['lotno'],
          $row1['itemnote']
        ));
      }
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        20,
        170
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Ship To',
        $row['shipto'] . ' / ' . $row['phone'] . ' / ' . $row['hp']
      ));
      $this->pdf->row(array(
        'Note',
        $row['headernote']
      ));
      $this->pdf->colalign = array(
        'C'
      );
      $this->pdf->setwidths(array(
        150
      ));
      $this->pdf->coldetailalign = array(
        'L'
      );
      $this->pdf->row(array(
        'Barang-barang tersebut diatas kami (saya) periksa dan terima dengan baik serta cukup.'
      ));
      $this->pdf->checkNewPage(40);
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->text(15, $this->pdf->gety(), '  Dibuat oleh,');
      $this->pdf->text(55, $this->pdf->gety(), ' Disetujui oleh,');
      $this->pdf->text(96, $this->pdf->gety(), '  Diketahui oleh,');
      $this->pdf->text(137, $this->pdf->gety(), 'Dibawa oleh,');
      $this->pdf->text(178, $this->pdf->gety(), ' Diterima oleh,');
      $this->pdf->text(15, $this->pdf->gety() + 22, '........................');
      $this->pdf->text(55, $this->pdf->gety() + 22, '.........................');
      $this->pdf->text(96, $this->pdf->gety() + 22, '........................');
      $this->pdf->text(137, $this->pdf->gety() + 22, '........................');
      $this->pdf->text(178, $this->pdf->gety() + 22, '........................');
      $this->pdf->text(15, $this->pdf->gety() + 25, 'Admin Gudang');
      $this->pdf->text(55, $this->pdf->gety() + 25, ' Kepala Gudang');
      $this->pdf->text(96, $this->pdf->gety() + 25, '     Distribusi');
      $this->pdf->text(137, $this->pdf->gety() + 25, '        Supir');
      $this->pdf->text(178, $this->pdf->gety() + 25, 'Customer/Toko');
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    $this->menuname = 'giheaderlist';
    parent::actionDownxls();
    $sql = "select e.companyid, a.gino,a.gidate,b.sono ,a.giheaderid,a.headernote,k.plantcode,b.pocustno,
			a.recordstatus,c.fullname as customer,h.fullname as sales,f.cityname,g.addressname as shipto,
			(
			select distinct g.mobilephone
			from addresscontact g 
			where g.addressbookid = c.addressbookid
			limit 1 
			) as hp,
				(
			select distinct h.phoneno
			from address h
			where h.addressbookid = c.addressbookid
			limit 1 
			) as phone, i.fullname as expedisi,a.nomobil,a.sopir,a.pebno,l.productname,j.qty,j.qty2,j.qty3,j.qty4,
			m.uomcode,n.uomcode as uom2code,o.uomcode as uom3code,p.uomcode as uom4code,q.sloccode,r.description as storagedesc,
			j.certoaid,j.lotno,j.itemnote,
			Getstockbin(l.productid,j.uomid,q.slocid,r.storagebinid) as stock
			from giheader a
			left join soheader b on b.soheaderid = a.soheaderid 
			left join addressbook c on c.addressbookid = b.addressbookid
			left join sales d on d.salesid = b.salesid
			left join plant k on k.plantid = a.plantid
			left join company e on e.companyid = k.companyid
			left join city f on f.cityid = e.cityid
			left join address g on g.addressid = a.addresstoid  
			left join employee h on h.employeeid = d.employeeid  
			left join addressbook i on i.addressbookid = a.supplierid 
			left join gidetail j on j.giheaderid = a.giheaderid 
			left join product l on l.productid = j.productid 
			left join unitofmeasure m on m.unitofmeasureid = j.uomid 
			left join unitofmeasure n on n.unitofmeasureid = j.uom2id
			left join unitofmeasure o on o.unitofmeasureid = j.uom3id
			left join unitofmeasure p on p.unitofmeasureid = j.uom4id
			left join sloc q on q.slocid = j.slocid
			left join storagebin r on r.storagebinid = j.storagebinid 
		";
		$giheaderid = GetSearchText(array('POST'),'giheaderid');
    $plantcode = GetSearchText(array('POST','GET'),'plantcode');
    $productname = GetSearchText(array('POST','GET'),'productname');
    $addressname = GetSearchText(array('POST','GET','Q'),'addressname');
    $pocustno = GetSearchText(array('POST','Q'),'pocustno');
    $nomobil = GetSearchText(array('POST','Q'),'nomobil');
    $sono = GetSearchText(array('POST','GET'),'sono');
    $ekspedisi = GetSearchText(array('POST','GET'),'ekspedisi');
    $gidate = GetSearchText(array('POST','GET'),'gidate');
    $gino = GetSearchText(array('POST','GET'),'gino');
    $headernote = GetSearchText(array('POST','GET'),'headernote');
    $customer = GetSearchText(array('POST','GET'),'customer');
    $sopir = GetSearchText(array('POST','GET'),'sopir');
    $gidate = GetSearchText(array('POST','GET'),'gidate');
    $recordstatus = GetSearchText(array('POST','GET'),'recordstatus');
		$sql .= "
			where coalesce(a.gino,'') like '".$gino."' 
			and coalesce(k.plantcode,'') like '".$plantcode."' 
			and coalesce(g.addressname,'') like '".$addressname."' 
			and coalesce(b.pocustno,'') like '".$pocustno."' 
			and coalesce(a.nomobil,'') like '".$nomobil."' 
			and coalesce(b.sono,'') like '".$sono."' 
			and coalesce(i.fullname,'') like '".$ekspedisi."' 
			and coalesce(c.fullname,'') like '".$customer."' 
			and coalesce(a.sopir,'') like '".$sopir."' 
		";
		if ($gidate != '%%') {
			$sql .= " and a.gidate like '".$gidate."'";
		}
		if ($recordstatus != '%%') {
			$sql .= " and a.recordstatus like '".$recordstatus."'";
		}
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.giheaderid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 2;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0, $line, $line-1)
			->setCellValueByColumnAndRow(1, $line, $row['plantcode'])
			->setCellValueByColumnAndRow(2, $line, date(Yii::app()->params['dateviewfromdb'], strtotime($row['gidate'])))
			->setCellValueByColumnAndRow(3, $line, $row['gino'])
			->setCellValueByColumnAndRow(4, $line, $row['customer'])
			->setCellValueByColumnAndRow(5, $line, $row['sono'])
			->setCellValueByColumnAndRow(6, $line, $row['pocustno'])
			->setCellValueByColumnAndRow(7, $line, $row['expedisi'])
			->setCellValueByColumnAndRow(8, $line, $row['nomobil'])
			->setCellValueByColumnAndRow(9, $line, $row['sopir'])
			->setCellValueByColumnAndRow(10, $line, $row['shipto'])
			->setCellValueByColumnAndRow(11, $line, $row['pebno'])
			->setCellValueByColumnAndRow(12, $line, $row['headernote'])
			->setCellValueByColumnAndRow(13, $line, $row['productname'])
			->setCellValueByColumnAndRow(14, $line, $row['stock'])
			->setCellValueByColumnAndRow(15, $line, $row['qty'])
			->setCellValueByColumnAndRow(16, $line, $row['uomcode'])
			->setCellValueByColumnAndRow(17, $line, $row['qty2'])
			->setCellValueByColumnAndRow(18, $line, $row['uom2code'])
			->setCellValueByColumnAndRow(19, $line, $row['qty3'])
			->setCellValueByColumnAndRow(20, $line, $row['uom3code'])
			->setCellValueByColumnAndRow(21, $line, $row['qty4'])
			->setCellValueByColumnAndRow(22, $line, $row['uom4code'])
			->setCellValueByColumnAndRow(23, $line, $row['sloccode'])
			->setCellValueByColumnAndRow(24, $line, $row['storagedesc'])
			->setCellValueByColumnAndRow(25, $line, $row['lotno'])
			->setCellValueByColumnAndRow(26, $line, $row['certoaid'])
			;
			$line++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}