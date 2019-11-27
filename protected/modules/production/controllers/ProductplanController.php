<?php
class ProductplanController extends Controller {
  public $menuname = 'productplan';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexhasil() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchhasil();
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
  public function actionGetData() {
			$id = rand(-1, -1000000000);
			echo CJSON::encode(array(
				'productplanid' => $id
			));
  }
	public function actionGeneratedetail() {
    if (isset($_POST['id'])) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateSOPP(:vid, :vhid, :vdatauser)';
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
    Yii::app()->end();
  }
	private function GetDetailBOM($connection,$plantid,$productplanid,$parentid,$productplanfgid,$sodetailid,$productname,$orderqty,$orderqty2,$orderqty3,$stdqty,$stdqty2,$stdqty3,$startdate,$enddate,$productplandetailid) {
		if ($productplandetailid == '') {
			$cmd = $connection->createCommand("
				select a.* 
				from productplandetail a
				where a.isread = 0 
					and a.productplanid = ".$productplanid." 
					and a.productplanfgid = ".$productplanfgid." 
					and a.bomid is not null 
				order by a.productplandetailid asc
			")->queryAll();
		} else {
			$cmd = $connection->createCommand("select a.* 
				from productplandetail a
				where a.isread = 0 
					and a.productplandetailid = ".$productplandetailid." 
				order by a.productplandetailid asc
			")->queryAll();
		}
		foreach ($cmd as $data) {
			$cmd1 = $connection->createCommand("
				update productplandetail 
				set isread = 1 
				where productplandetailid = ".$data['productplandetailid'])->execute();
			$sql = "
				insert into productplanfg (productplanid,parentid,sodetailid,productid,qty,qty2,qty3,uomid,uom2id,uom3id,bomid,processprdid,mesinid,sloctoid,qtyres,description,isread,startdate,enddate)
				select distinct ".$productplanid.",".$parentid.",".(($sodetailid != '')?$sodetailid:'null').",".$data['productid'].
				",(a.qty/".$stdqty.")*".$orderqty;
if ($stdqty2 != 0) {
				$sql .= ",(a.qty2/".$stdqty2.")*".$orderqty2;
			} 
			else {
				$sql .= ",0";
			}
			if ($stdqty3 != 0) {
				$sql .= ",(a.qty3/".$stdqty3.")*".$orderqty3;
			} 
			else {
				$sql .= ",0";
			}
			  $sql .= ",a.uomid,a.uom2id,a.uom3id,a.bomid,a.processprdid,a.mesinid,getsloc(".$plantid.",a.productid,1,1),0,:productname,0,
				'".date(Yii::app()->params['datetodb'], strtotime($startdate))."','".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
				from billofmaterial a
				where a.bomid = ".$data['bomid']." 
				and a.productid = ".$data['productid']." 
				and a.plantid = ".$plantid." 
				";
			$cmd1 = $connection->createCommand($sql);
			$cmd1->bindvalue(':productname',$productname,PDO::PARAM_STR);
			$cmd1->execute();
			$cmd1 = $connection->createCommand("
				select distinct b.productid,b.productbomid 
				from productplanfg z 
				left join billofmaterial a on a.bomid = z.bomid 
				left join bomdetail b on b.bomid = a.bomid 
				left join product c on c.productid = z.productid 
				where a.bomid = ".$data['bomid']." and a.productid = ".$data['productid']." 
				and a.plantid = ".$plantid." 
				and c.isstock = 1 
				and z.productplanid = ".$productplanid."
				and z.productplanfgid not in (
					select productplanfgid 
					from productplandetail 
					where productplanid = ".$productplanid." 
				)")->queryRow();
			if ($cmd1 != null) {
				$sql = "
					select a.qty,a.qty2,a.qty3
					from billofmaterial a
					where a.productid = ".$cmd1['productid']." and a.plantid = ".$plantid;
				$std = $connection->createCommand($sql)->queryRow();
				$sql = "insert into productplandetail (productplanid,productplanfgid,productid,qty,qty2,qty3,uomid,uom2id,uom3id,bomid,slocfromid,sloctoid,isread)
					select distinct ".$productplanid.",z.productplanfgid,b.productid".
					",case when a.qty > 0 then (b.qty/a.qty)*z.qty else 0 end ".
					",case when a.qty2 > 0 then (b.qty2/a.qty2)*z.qty2 else 0 end ".
					",case when a.qty3 > 0 then (b.qty3/a.qty3)*z.qty3 else 0 end ".
				  ",b.uomid,b.uom2id,b.uom3id,b.productbomid,
					case when b.bomid is null then getsloc(".$plantid.",b.productid,0,1) else getsloc(".$plantid.",b.productid,1,1) end ,	
					getsloc(".$plantid.",a.productid,1,1)
					,0 
					from productplanfg z 
					left join billofmaterial a on a.bomid = z.bomid 
					left join bomdetail b on b.bomid = a.bomid
					left join product c on c.productid = z.productid 
					where a.bomid = ".$data['bomid']." and a.productid = ".$data['productid']." 
					and a.plantid = ".$plantid." 
					and c.isstock = 1 
					and z.productplanid = ".$productplanid."
					and z.productplanfgid not in (
						select productplanfgid 
						from productplandetail 
						where productplanid = ".$productplanid." 
					)";
				$command = $connection->createCommand($sql)->execute();
				$sql="update productplanfg 
					set isread = 1 
					where isread = 0 
					and productplanid = ".$productplanid;
				$command = $connection->createCommand($sql)->execute();
				$cmd1 = $connection->createCommand("
					select a.* 
					from productplandetail a
					where a.productplanid = ".$productplanid." 
						and a.bomid is not null 
						and a.isread = 0 
					order by a.productplandetailid asc
				")->queryAll();
				foreach ($cmd1 as $data1) {
					$this->GetDetailBOM($connection,$plantid,$productplanid,$productplanfgid,$data1['productplanfgid'],
						$sodetailid,$productname,$orderqty,$orderqty2,$orderqty3,$stdqty,$stdqty2,
						$stdqty3,$startdate,$enddate,$data1['productplandetailid']);
				}
			}
		}
	}
	public function actionStartUp() {
		$productplanid          = $_POST['hid']; 
		/*$command = Yii::app()->db->createCommand("
			delete from productplandetail 
			where productplanid = ".$productplanid)->execute();*/
		GetMessage(false, getcatalog('insertsuccess'));
	}
	public function actionGenerateBOMPP() {
		parent::actionIndex();
    if (isset($_POST['id'])) {
      $plantid          = $_POST['plantid'];
      $productplanid    = $_POST['hid']; 
			$ids = $_POST['id'];
      $connection  = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			/*$command = $connection->createCommand("
				delete from productplandetail 
				where productplanid = ".$productplanid)->execute();*/
			try {
				foreach ($ids as $id) {
					$bom = null;
					$productplanfgid = $id['productplanfgid'];
					$productid = $id['productid'];
					$sodetailid = $id['sodetailid'];
					$bomid = $id['bomid'];
					$startdate = $id['startdate'];
					$enddate = $id['enddate'];
					$qty = Yii::app()->format->unformatNumber($id['qty']);
					$qty2 = Yii::app()->format->unformatNumber($id['qty2']);
					$qty3 = Yii::app()->format->unformatNumber($id['qty3']);
					$sql = "
						select a.qty,a.qty2,ifnull(a.qty3,0) as qty3,b.productcode,b.productname
						from billofmaterial a 
						join product b on b.productid = a.productid 
						where a.bomid = ".$bomid." and b.isstock = 1 and a.productid = ".$productid." and a.plantid = ".$plantid;
					$std = $connection->createCommand($sql)->queryRow();	
					$sql = "
						insert into productplandetail (productplanid,productplanfgid,productid,qty,qty2,qty3,uomid,uom2id,uom3id,bomid,slocfromid,sloctoid,isread)
						select ".$productplanid.",".$productplanfgid.",b.productid,
						case when a.qty > 0 then (b.qty/a.qty)*".$qty." else 0 end ".
						",case when a.qty2 > 0 then (b.qty2/a.qty2)*".$qty2." else 0 end ".
						",case when a.qty3 > 0 then (b.qty3/a.qty3)*".(($qty3!='')?$qty3:0)." else 0 end ".
						",b.uomid,b.uom2id,b.uom3id,b.productbomid,
						case when b.bomid is null then getsloc(".$plantid.",b.productid,0,1) else getsloc(".$plantid.",b.productid,1,1) end ,	
						getsloc(".$plantid.",a.productid,1,1),0 
						from billofmaterial a 
						left join bomdetail b on b.bomid = a.bomid  
						left join product c on c.productid = b.productid 
						where c.isstock = 1 and a.bomid = ".$bomid." and a.productid = ".$productid." and a.plantid = ".$plantid;
					$command = $connection->createCommand($sql)->execute();
					$this->GetDetailBOM($connection,$plantid,$productplanid,$productplanfgid,$productplanfgid,$sodetailid,$std['addressbookid'],$std['productname'],$qty,$qty2,$qty3,$std['qty'],$std['qty2'],$std['qty3'],
						$startdate,$enddate,'');
					$transaction->commit();
				}
				GetMessage(false, getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollback();
				GetMessage(true, $e->getMessage());
			}
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  public function search() {
    header("Content-Type: application/json");
		$productplanid = GetSearchText(array('POST','Q'),'productplanid','','int');
		$productplanfg = GetSearchText(array('POST','Q'),'productplanfg',0,'int');
		$plantid = GetSearchText(array('POST','GET'),'plantid',0,'int');
		$addressbookid = GetSearchText(array('POST','Q'),'addressbookid',0,'int');
		$soheaderid = GetSearchText(array('POST','GET'),'soheaderid',0,'int');
		$sono = GetSearchText(array('POST','Q'),'sono');
		$customer = GetSearchText(array('POST','Q'),'customer');
		$plantcode = GetSearchText(array('POST','Q'),'plantcode');
		$sloccode = GetSearchText(array('POST','Q'),'sloccode');
		$productplandate = GetSearchText(array('POST','Q'),'productplandate');
		$productplanno = GetSearchText(array('POST','Q'),'productplanno');
		$description = GetSearchText(array('POST','Q'),'description');
		$productname = GetSearchText(array('POST','Q'),'productname');
		$recordstatus = GetSearchText(array('POST','Q'),'recordstatus');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','productplanid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
	if (!isset($_GET['getdata'])) {
    if (isset($_GET['ppop'])) {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('productplan t')
				->leftjoin('soheader a', 'a.soheaderid = t.soheaderid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
				->leftjoin('productplan e', 'e.productplanid = t.parentplanid')
				->where("
					((coalesce(t.productplanid,'') like :productplanid) 
				or (coalesce(b.plantcode,'') like :plantcode) 
				or (coalesce(d.fullname,'') like :customer) 
				or (coalesce(t.productplanno,'') like :productplanno) 
				or (coalesce(t.description,'') like :description) 
				or (coalesce(sono,'') like :sono) 
				or (coalesce(t.productplandate,'') like :productplandate)) 
				and t.recordstatus = getWfmaxstatbywfname('appop') 
				and t.plantid in (".getUserObjectValues('plant').")".
				(($plantid != '')?"and t.plantid = ".$plantid:'')."
				and t.productplanid in (select zz.productplanid 
					from productplanfg zz 
					where zz.qty > zz.qtyres and zz.sloctoid in (".getUserObjectValues('sloc')."))",
				array(
        ':productplanid' => '%' . $productplanid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':customer' => '%' . $customer . '%',
				':productplanno' => '%' . $productplanno . '%',
				':description' => '%' . $description . '%',
				':sono' => '%' . $sono . '%',
				':productplandate' => '%' . $productplandate . '%'
				))->queryScalar();
			} else
			if (isset($_GET['ppfpbok'])) {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('productplan t')
				->leftjoin('soheader a', 'a.soheaderid = t.soheaderid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
				->leftjoin('productplan e', 'e.productplanid = t.parentplanid')
				->where("
					((coalesce(t.productplanid,'') like :productplanid) 
				or (coalesce(b.plantcode,'') like :plantcode) 
				or (coalesce(d.fullname,'') like :customer) 
				or (coalesce(t.productplanno,'') like :productplanno) 
				or (coalesce(t.description,'') like :description) 
				or (coalesce(a.sono,'') like :sono)) 
				and t.recordstatus = getWfmaxstatbywfname('appop') 
				and t.plantid in (".getUserObjectValues('plant').")".
				(($plantid != '')?"and t.plantid = ".$plantid:'')."
				and t.productplanid in (select zz.productplanid 
					from productplanfg zz 
					where zz.sloctoid in (".getUserObjectValues('sloc')."))",
				array(
        ':productplanid' => '%' . $productplanid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':customer' => '%' . $customer . '%',
				':productplanno' => '%' . $productplanno . '%',
				':description' => '%' . $description . '%',
				':sono' => '%' . $sono . '%',
				))->queryScalar();
			}
    else if (isset($_GET['lintaspp'])) {
				$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
					->from('productplan t')
				->leftjoin('soheader a', 'a.soheaderid = t.soheaderid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
				->leftjoin('productplan e', 'e.productplanid = t.parentplanid')
				->where("
					((coalesce(d.fullname,'') like :customer) 
				or (coalesce(t.productplanno,'') like :productplanno) 
				or (coalesce(a.sono,'') like :sono)) 
				and t.recordstatus = getWfmaxstatbywfname('appop') 
				and t.productplanid in (select zz.productplanid 
					from productplanfg zz 
					where zz.qty > zz.qtyres)",
				array(
				':customer' => '%' . $customer . '%',
				':productplanno' => '%' . $productplanno . '%',
				':sono' => '%' . $sono . '%',
				))->queryScalar();
			} else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
				->from('productplan t')
				->leftjoin('soheader a', 'a.soheaderid = t.soheaderid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
				->leftjoin('productplan e', 'e.productplanid = t.parentplanid')
				->where("
				((coalesce(t.productplanid,'') like :productplanid) 
				and (coalesce(b.plantcode,'') like :plantcode) 
				and (coalesce(d.fullname,'') like :customer) 
				and (coalesce(t.productplanno,'') like :productplanno) 
				and (coalesce(t.description,'') like :description) 
				and (coalesce(a.sono,'') like :sono) 
				and (coalesce(t.productplandate,'') like :productplandate)) 
				and t.recordstatus in (".getUserRecordStatus('listprodplan').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				" and t.plantid in (".getUserObjectValues('plant').")".
				(($sloccode != '%%')?"
				and t.productplanid in (
					select distinct z.productplanid 
					from productplanfg z 
					join sloc zz on zz.slocid = z.sloctoid 
					where zz.sloccode like '%".$sloccode."%'
				)":'').
				(($productname != '')?"
				and t.productplanid in (
					select distinct z.productplanid 
					from productplanfg z 
					join product zz on zz.productid = z.productid 
					where zz.productname like '%".$productname."%'
				)":'')
				,				
				array(
        ':productplanid' => '%' . $productplanid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':customer' => '%' . $customer . '%',
				':productplanno' => '%' . $productplanno . '%',
				':description' => '%' . $description . '%',
				':sono' => '%' . $sono . '%',
				':productplandate' => '%' . $productplandate . '%'
				))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['ppop'])) {
			$cmd = Yii::app()->db->createCommand()->select('t.*,a.sono,b.plantcode,b.companyid,c.companyname,d.fullname,e.productplanno as parentplanno')
				->from('productplan t')
				->leftjoin('soheader a', 'a.soheaderid = t.soheaderid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
				->leftjoin('productplan e', 'e.productplanid = t.parentplanid')
				->where("
					((coalesce(t.productplanid,'') like :productplanid) 
				or (coalesce(b.plantcode,'') like :plantcode) 
				or (coalesce(d.fullname,'') like :customer) 
				or (coalesce(t.productplanno,'') like :productplanno) 
				or (coalesce(t.description,'') like :description) 
				or (coalesce(sono,'') like :sono) 
				or (coalesce(t.productplandate,'') like :productplandate)) 
				and t.recordstatus = getWfmaxstatbywfname('appop') 
				and t.plantid in (".getUserObjectValues('plant').")".
				(($plantid != '%%')?"and t.plantid = ".$plantid:'')."
				and t.productplanid in (select zz.productplanid 
					from productplanfg zz 
					where zz.qty > zz.qtyres and zz.sloctoid in (".getUserObjectValues('sloc')."))
				",
				array(
        ':productplanid' => '%' . $productplanid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':customer' => '%' . $customer . '%',
				':productplanno' => '%' . $productplanno . '%',
				':description' => '%' . $description . '%',
				':sono' => '%' . $sono . '%',
				':productplandate' => '%' . $productplandate . '%'
				))->order($sort . ' ' . $order)->queryAll();
		}else 
					if (isset($_GET['ppfpbok'])) {
			$cmd = Yii::app()->db->createCommand()->select('t.*,a.sono,b.plantcode,b.companyid,c.companyname,d.fullname,e.productplanno as parentplanno')
				->from('productplan t')
				->leftjoin('soheader a', 'a.soheaderid = t.soheaderid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
				->leftjoin('productplan e', 'e.productplanid = t.parentplanid')
				->where("
					((coalesce(t.productplanid,'') like :productplanid) 
				or (coalesce(b.plantcode,'') like :plantcode) 
				or (coalesce(d.fullname,'') like :customer) 
				or (coalesce(t.productplanno,'') like :productplanno) 
				or (coalesce(t.description,'') like :description) 
				or (coalesce(a.sono,'') like :sono)) 
				and t.recordstatus = getWfmaxstatbywfname('appop') 
				and t.plantid in (".getUserObjectValues('plant').")".
				(($plantid != '')?"and t.plantid = ".$plantid:'')." 
				and t.productplanid in (select zz.productplanid 
					from productplanfg zz 
					where zz.sloctoid in (".getUserObjectValues('sloc')."))
				",
				array(
        ':productplanid' => '%' . $productplanid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':customer' => '%' . $customer . '%',
				':productplanno' => '%' . $productplanno . '%',
				':description' => '%' . $description . '%',
				':sono' => '%' . $sono . '%',
				))->queryAll();
		} else if (isset($_GET['lintaspp'])) {
			$cmd = Yii::app()->db->createCommand()->select('t.*,a.sono,b.plantcode,b.companyid,c.companyname,d.fullname,e.productplanno as parentplanno')
				->from('productplan t')
				->leftjoin('soheader a', 'a.soheaderid = t.soheaderid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
				->leftjoin('productplan e', 'e.productplanid = t.parentplanid')
				->where("
					((coalesce(d.fullname,'') like :customer) 
				or (coalesce(t.productplanno,'') like :productplanno) 
				or (coalesce(a.sono,'') like :sono))  
				and t.recordstatus = getWfmaxstatbywfname('appop') 
				and t.productplanid in (select zz.productplanid 
					from productplanfg zz 
					where zz.qty > zz.qtyres)
				",
				array(
				':customer' => '%' . $customer . '%',
				':productplanno' => '%' . $productplanno . '%',
				':sono' => '%' . $sono . '%',
				))->queryAll();
		} else {
			$cmd = Yii::app()->db->createCommand()->select('t.*,a.sono,b.plantcode,b.companyid,c.companyname,d.fullname,e.productplanno as parentplanno')
				->from('productplan t')
				->leftjoin('soheader a', 'a.soheaderid = t.soheaderid')
				->leftjoin('plant b', 'b.plantid = t.plantid')
				->leftjoin('company c', 'c.companyid = b.companyid')
				->leftjoin('addressbook d', 'd.addressbookid = t.addressbookid')
				->leftjoin('productplan e', 'e.productplanid = t.parentplanid')
				->where("
				((coalesce(t.productplanid,'') like :productplanid) 
				and (coalesce(b.plantcode,'') like :plantcode) 
				and (coalesce(d.fullname,'') like :customer) 
				and (coalesce(t.productplanno,'') like :productplanno) 
				and (coalesce(t.description,'') like :description) 
				and (coalesce(sono,'') like :sono) 
				and (coalesce(t.productplandate,'') like :productplandate)) 
				and t.recordstatus in (".getUserRecordStatus('listprodplan').")".
				(($recordstatus != '%%')?" and t.recordstatus like '".$recordstatus."'":'').
				" and t.plantid in (".getUserObjectValues('plant').")".
				(($sloccode != '%%')?"
				and t.productplanid in (
					select distinct z.productplanid 
					from productplanfg z 
					join sloc zz on zz.slocid = z.sloctoid 
					where zz.sloccode like '%".$sloccode."%'
				)":'').
				(($productname != '%%')?"
				and t.productplanid in (
					select distinct z.productplanid 
					from productplanfg z 
					join product zz on zz.productid = z.productid 
					where zz.productname like '%".$productname."%'
				)":'')
				,				
				array(
        ':productplanid' => '%' . $productplanid . '%',
				':plantcode' => '%' . $plantcode . '%',
				':customer' => '%' . $customer . '%',
				':productplanno' => '%' . $productplanno . '%',
				':description' => '%' . $description . '%',
				':sono' => '%' . $sono . '%',
				':productplandate' => '%' . $productplandate . '%'
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		}
		foreach ($cmd as $data) {
			$row[] = array(
				'productplanid' => $data['productplanid'],
				'productplandate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['productplandate'])),
				'productplanno' => $data['productplanno'],
				'plantid' => $data['plantid'],
				'plantcode' => $data['plantcode'],
				'companyid' => $data['companyid'],
				'companyname' => $data['companyname'],
				'parentplanid' => $data['parentplanid'],
				'parentplanno' => $data['parentplanno'],
				'soheaderid' => $data['soheaderid'],
				'sono' => $data['sono'],
				'addressbookid' => $data['addressbookid'],
				'fullname' => $data['fullname'],
				'description' => $data['description'],
				'recordstatus' => $data['recordstatus'],
				'recordstatusname' => $data['statusname']
      );
		}
		$result = array_merge($result, array(
			'rows' => $row
		));
	} else {
			$cmd = Yii::app()->db->createCommand("
				select a.productplanid,a.soheaderid, a.addressbookid,a.description
				from productplan a
				where a.productplanid = ".$productplanid)->queryRow();
			$result = $cmd;
		}
    return CJSON::encode($result);
	}
	public function actionproductionfg() {
    $items = array();
    $cmd   = Yii::app()->db->createCommand()
			->select('c.productplanno,d.fullname as customer, a.productname,t.qty,t.startdate,t.enddate,e.uomcode,f.kodemesin,
			(t.qty-t.qtyres) as qtyout,t.qtyres,g.processprdname')
			->from('productplanfg t')
			->leftjoin('product a', 'a.productid = t.productid')
			->leftjoin('productplan c', 'c.productplanid = t.productplanid')
			->leftjoin('addressbook d', 'd.addressbookid = c.addressbookid')
			->leftjoin('unitofmeasure e', 'e.unitofmeasureid = t.uomid')
			->leftjoin('mesin f', 'f.mesinid = t.mesinid')
			->leftjoin('processprd g', 'g.processprdid = t.processprdid ')
			->where("startdate >= '" . $_GET['start'] . "' 
				and enddate <= '" . $_GET['end'] . "'  
				and c.productplanno is not null 
				and t.sloctoid in (".GetUserObjectValues('sloc').") 
				")->queryAll();
    
    foreach ($cmd as $data) {
      $items[] = array(
        'title' => 'Customer: '.$data['customer']."\n No OK:".$data['productplanno'].
					"\n Artikel: ".$data['productname'].
					"\n Qty OK: ". Yii::app()->format->formatNumber($data['qty']).''.$data['uomcode'].
					"\n Qty Prod: ".Yii::app()->format->formatNumber($data['qtyres']).
					"\n Qty Out: ".Yii::app()->format->formatNumber($data['qtyout']).
					"\n Mesin: ".$data['kodemesin'].
					"\n Process: ".$data['processprdname'],
        'start' => $data['startdate'],
        'end' => $data['enddate'],
        'constraint' => 'businessHours'
      );
    }
    echo CJSON::encode($items);
  }
  public function actionSearchhasil() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'productplanfgid';
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
					->from('productplanfg t')
					->leftjoin('productplan g', 'g.productplanid = t.productplanid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('billofmaterial b', 'b.bomid = t.bomid')
					->leftjoin('mesin c', 'c.mesinid = t.mesinid')
					->leftjoin('processprd d', 'd.processprdid = t.processprdid')
					->leftjoin('sloc e', 'e.slocid = t.sloctoid')
					->leftjoin('materialtype f', 'f.materialtypeid = a.materialtypeid')
					->where('t.productplanid = :productplanid',
					array(
				':productplanid' => $id
				))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
					->select('t.*,a.productcode,a.productname,b.bomversion,d.processprdname,e.sloccode,c.kodemesin,(t.qty-t.qtyres) as qtyout,f.materialtypecode,
						(
							SELECT SUM(z.qty-z.qtyres)
							FROM productplanfg z  
							JOIN productplan zb ON zb.productplanid = z.productplanid 
							WHERE z.productid = t.productid AND zb.soheaderid IS null and zb.recordstatus >= 3
						) AS qtyokfree,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,t.description,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code,
						GetStdQty(a.productid) as stdqty,
						GetStdQty2(a.productid) as stdqty2,
						GetStdQty3(a.productid) as stdqty3,
						GetStock(a.productid,t.uomid,t.sloctoid) as qtystock,
						(select zz.delvdate from sodetail zz where zz.sodetailid = t.sodetailid and zz.productid = t.productid) as delvdate,
						(select zz.qty from sodetail zz where zz.sodetailid = t.sodetailid and zz.productid = t.productid) as qtyso
						')
					->from('productplanfg t')
					->leftjoin('productplan g', 'g.productplanid = t.productplanid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('billofmaterial b', 'b.bomid = t.bomid')
					->leftjoin('mesin c', 'c.mesinid = t.mesinid')
					->leftjoin('processprd d', 'd.processprdid = t.processprdid')
					->leftjoin('sloc e', 'e.slocid = t.sloctoid')
					->leftjoin('materialtype f', 'f.materialtypeid = a.materialtypeid')
					->where('t.productplanid = :productplanid', array(
		':productplanid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
	foreach ($cmd as $data) {
		if ($data['qtystock'] >= $data['qty']) {
				$stockcount = 0;
			} else {
				$stockcount = 1;
			}
		$row[] = array(
			'productplanfgid' => $data['productplanfgid'],
			'productplanid' => $data['productplanid'],
			'sodetailid' => $data['sodetailid'],
			'productid' => $data['productid'],
			'productcode' => $data['productcode'],
			'productname' => $data['productname'],
			'materialtypecode' => $data['materialtypecode'],
			'qty' => Yii::app()->format->formatNumber($data['qty']),
			'qtyres' => Yii::app()->format->formatNumber($data['qtyres']),
			'qtyout' => Yii::app()->format->formatNumber($data['qtyout']),
			'qtyokfree' => Yii::app()->format->formatNumber($data['qtyokfree']),
			'tsqty' => Yii::app()->format->formatNumber($data['tsqty']),
			'qty2' => Yii::app()->format->formatNumber($data['qty2']),
			'qty3' => Yii::app()->format->formatNumber($data['qty3']),
			'stdqty' => Yii::app()->format->formatNumber($data['stdqty']),
			'stdqty2' => Yii::app()->format->formatNumber($data['stdqty2']),
			'stdqty3' => Yii::app()->format->formatNumber($data['stdqty3']),
			'qtystock' => Yii::app()->format->formatNumber($data['qtystock']),
			'qtyso' => Yii::app()->format->formatNumber($data['qtyso']),
			'stockcount' => $stockcount,
			'uomid' => $data['uomid'],
			'uom2id' => $data['uom2id'],
			'uom3id' => $data['uom3id'],
			'uomcode' => $data['uomcode'],
			'uom2code' => $data['uom2code'],
			'uom3code' => $data['uom3code'],
			'bomid' => $data['bomid'],
			'bomversion' => $data['bomversion'],
			'mesinid' => $data['mesinid'],
			'namamesin' => $data['kodemesin'],
			'processprdid' => $data['processprdid'],
			'processprdname' => $data['processprdname'],
			'sloctoid' => $data['sloctoid'],
			'sloccode' => $data['sloccode'],
			'startdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['startdate'])),
			'enddate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['enddate'])),
			'devldate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['delvdate'])),
			'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
  public function actionSearchdetail() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'productplandetailid';
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
					->from('productplandetail t')
					->leftjoin('productplan g', 'g.productplanid = t.productplanid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('billofmaterial b', 'b.bomid = t.bomid')
					->leftjoin('sloc c', 'c.slocid = t.slocfromid')
					->leftjoin('sloc e', 'e.slocid = t.sloctoid')
					->leftjoin('materialtype f', 'f.materialtypeid = a.materialtypeid')
					->where('t.productplanid = :productplanid',
					array(
				':productplanid' => $id
				))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
					->select('t.*,a.productcode,a.productname,b.bomversion,c.sloccode as slocfromcode,e.sloccode as sloctocode,f.materialtypecode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,t.description,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom2id) as uom2code,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uom3id) as uom3code,
						(
						SELECT IFNULL(SUM(zz.qty) ,0)
						FROM formrequestraw zz
						WHERE zz.productplandetailid = t.productplandetailid AND zz.productid = t.productid
						) as qtyfr,						
						(
						SELECT IFNULL(SUM(zz.qty2) ,0)
						FROM formrequestraw zz
						WHERE zz.productplandetailid = t.productplandetailid AND zz.productid = t.productid
						) as qtyfr2,
						(
						SELECT IFNULL(SUM(zz.qty3) ,0)
						FROM formrequestraw zz
						WHERE zz.productplandetailid = t.productplandetailid AND zz.productid = t.productid
						) as qtyfr3,
						(
						SELECT IFNULL(SUM(zz.qty) ,0)
						FROM transstockdet zz
						WHERE zz.productplandetailid = t.productplandetailid AND zz.productid = t.productid
						) as qtytrf,
						(
						SELECT IFNULL(SUM(zz.qty2) ,0)
						FROM transstockdet zz
						WHERE zz.productplandetailid = t.productplandetailid AND zz.productid = t.productid
						) as qtytrf2,
						GetStdQty(a.productid) as stdqty,
						GetStdQty2(a.productid) as stdqty2,
						GetStdQty3(a.productid) as stdqty3,
						GetStock(a.productid,t.uomid,t.sloctoid) as qtystock
						')
					->from('productplandetail t')
					->leftjoin('productplan g', 'g.productplanid = t.productplanid')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('billofmaterial b', 'b.bomid = t.bomid')
					->leftjoin('sloc c', 'c.slocid = t.slocfromid')
					->leftjoin('sloc e', 'e.slocid = t.sloctoid')
					->leftjoin('materialtype f', 'f.materialtypeid = a.materialtypeid')
					->where('t.productplanid = :productplanid', array(
		':productplanid' => $id
		))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
	foreach ($cmd as $data) {
		if ($data['qtyfr'] > $data['qty']) {
			$frcount = 1;
		} else {
			$frcount = 0;
		}
		if ($data['qtytrf'] > $data['qty']) {
			$trfcount = 1;
		} else {
			$trfcount = 0;
		}
		if ($data['qtyres'] > $data['qty']) {
			$rescount = 1;
		} else {
			$rescount = 0;
		}
		if ($data['qtystock'] >= $data['qty']) {
			$stockcount = 0;
		} else {
			$stockcount = 1;
		}
		$row[] = array(
			'productplandetailid' => $data['productplandetailid'],
			'productplanid' => $data['productplanid'],
			'productplanfgid' => $data['productplanfgid'],
			'productid' => $data['productid'],
			'productcode' => $data['productcode'],
			'productname' => $data['productname'],
			'materialtypecode' => $data['materialtypecode'],
			'qty' => Yii::app()->format->formatNumber($data['qty']),
			'qty2' => Yii::app()->format->formatNumber($data['qty2']),
			'qty3' => Yii::app()->format->formatNumber($data['qty3']),
			'qtyres' => Yii::app()->format->formatNumber($data['qtyres']),
			'qtyfr' => Yii::app()->format->formatNumber($data['qtyfr']),
			'qtyfr2' => Yii::app()->format->formatNumber($data['qtyfr2']),
			'qtyfr3' => Yii::app()->format->formatNumber($data['qtyfr3']),
			'stdqty' => Yii::app()->format->formatNumber($data['stdqty']),
			'stdqty2' => Yii::app()->format->formatNumber($data['stdqty2']),
			'stdqty3' => Yii::app()->format->formatNumber($data['stdqty3']),
			'qtystock' => Yii::app()->format->formatNumber($data['qtystock']),
			'frcount' => $frcount,
			'trfcount' => $trfcount,
			'rescount' => $rescount,
			'stockcount' => $stockcount,
			'uomid' => $data['uomid'],
			'uom2id' => $data['uom2id'],
			'uom3id' => $data['uom3id'],
			'uomcode' => $data['uomcode'],
			'uom2code' => $data['uom2code'],
			'uom3code' => $data['uom3code'],
			'bomid' => $data['bomid'],
			'bomversion' => $data['bomversion'],
			'slocfromid' => $data['slocfromid'],
			'slocfromcode' => $data['slocfromcode'],
			'sloctoid' => $data['sloctoid'],
			'sloctocode' => $data['sloctocode'],
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
        $sql     = 'call Rejectproductplan(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, 'chooseone');
    }
  }	
  public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveProductPlan(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  public function actionHoldOk() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call HoldOK(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  public function actionOpenOk() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call OpenOK(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, 'chooseone');
    }
  }
	public function actionCopyOk() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call CopyOK(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  public function actionCloseOk() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call CloseOK(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $id, PDO::PARAM_STR);
				$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
				$command->execute();
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  private function ModifyData($connection,$arraydata) {
		$id = (int)$arraydata[0];
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$sql = 'call ModifProductplan (:vid,:vproductplandate,:vplantid,:vsoheaderid,:vaddressbookid,:vparentplanid,:vdescription,:vdatauser)';
		$command=$connection->createCommand($sql);
		$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		$command->bindvalue(':vproductplandate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vsoheaderid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vparentplanid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vdescription', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionSave() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['productplan-productplanid'])?$_POST['productplan-productplanid']:''),
				date(Yii::app()->params['datetodb'], strtotime($_POST['productplan-productplandate'])),
				$_POST['productplan-plantid'],$_POST['productplan-soheaderid'],$_POST['productplan-addressbookid'],$_POST['productplan-parentplanid'],
				$_POST['productplan-description']));
			$transaction->commit();
			GetMessage(false, getcatalog('insertsuccess'));
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true, $e->getMessage());
		}
  }
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-productplan"]["name"]);
		if (move_uploaded_file($_FILES["file-productplan"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$oldid = '';$pid = '';$ppid = '';$oldppid = '';$soheaderid = ''; 
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
							$sono = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue(); //C
							$soheaderid = Yii::app()->db->createCommand("select soheaderid from soheader where sono = '".$sono."'")->queryScalar();
							$customer = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue(); //D
							$customerid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '".$customer."'")->queryScalar();
							$docdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(4, $row)->getValue())); //E
							$planno = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue(); //F
							$planid = Yii::app()->db->createCommand("select productplanid from productplan where productplanno = '".$planno."'")->queryScalar();
							$description = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue(); //G
							$this->ModifyData($connection,array(-1,
								$docdate,$plantid,$soheaderid,$customerid,$planid,
								$description));
							$pid = Yii::app()->db->createCommand("
								select productplanid 
								from productplan a
								where a.plantid = ".$plantid." 
								and a.productplandate = '".$docdate."' 
								and a.addressbookid = ".$customerid." 
								and a.soheaderid = '".$soheaderid."' 
								and a.description = '".$description."' 
								limit 1
							")->queryScalar();
						} 
						$sid = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue(); //H
						if ((int)$sid > 0) {
							if ($oldppid != $sid) {
								$oldppid = $sid;
								$productname = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue(); //I
								$sql = "select productid from product where productname = :productname";
								$command=$connection->createCommand($sql);
								$command->bindvalue(':productname',$productname,PDO::PARAM_STR);
								$productid = $command->queryScalar();
								$qty = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue(); //J
								$uomcode = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue(); //K
								$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
								$qty2 = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue(); //L
								$uomcode = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue(); //M
								$uom2id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
								$qty3 = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue(); //N
								$uomcode = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue(); //O
								$uom3id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
								$bomversion = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue(); //P
								$sql = "select bomid from billofmaterial where bomversion = :bomversion and productid = :productid and plantid = :plantid";
								$command=$connection->createCommand($sql);
								$command->bindvalue(':bomversion',$bomversion,PDO::PARAM_STR);
								$command->bindvalue(':productid',$productid,PDO::PARAM_STR);
								$command->bindvalue(':plantid',$plantid,PDO::PARAM_STR);
								$bomid = $command->queryScalar();
								$processprd = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue(); //Q
								$processprdid = Yii::app()->db->createCommand("select processprdid from processprd where processprdname = '".$processprd."'")->queryScalar();
								$kodemesin = $objWorksheet->getCellByColumnAndRow(19, $row)->getValue(); //R
								$mesinid = Yii::app()->db->createCommand("select processprdid from processprd where processprdname = '".$processprd."'")->queryScalar();
								$sloccode = $objWorksheet->getCellByColumnAndRow(20, $row)->getValue(); //S
								$slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
								$startdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(21, $row)->getValue())); //T
								$enddate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(22, $row)->getValue())); //U
								$itemnote = $objWorksheet->getCellByColumnAndRow(23, $row)->getValue(); //V
								$this->ModifyHasil($connection,array(
									'',
									$pid,
									$productid,
									$uomid,
									$uom2id,
									$uom3id,
									$qty,
									$qty2,
									$qty3,
									$bomid,
									$processprdid,
									$mesinid,
									$slocid,
									$startdate,
									$enddate,
									$description
								));
								$ppid = Yii::app()->db->createCommand("
									select productplanfgid 
									from productplanfg a
									join productplan b on b.productplanid = a.productplanid 
									where a.productid = ".$productid." 
									and a.productplanid = '".$pid."' 
									and a.startdate = '".$startdate."'
									and a.bomid = '".$bomid."' 
									and b.soheaderid = '".$soheaderid."' 
									limit 1
								")->queryScalar();
							}
							$productname = $objWorksheet->getCellByColumnAndRow(24, $row)->getValue(); //Y
							$sql = "select productid from product where productname = :productname";
							$command=$connection->createCommand($sql);
							$command->bindvalue(':productname',$productname,PDO::PARAM_STR);
							$productid = $command->queryScalar();
							$qty = $objWorksheet->getCellByColumnAndRow(25, $row)->getValue(); //Z
							$uomcode = $objWorksheet->getCellByColumnAndRow(26, $row)->getValue(); //AA
							$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$qty2 = $objWorksheet->getCellByColumnAndRow(27, $row)->getValue(); //AB
							$uomcode = $objWorksheet->getCellByColumnAndRow(28, $row)->getValue(); //AC
							$uom2id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$qty3 = $objWorksheet->getCellByColumnAndRow(29, $row)->getValue(); //AD
							$uomcode = $objWorksheet->getCellByColumnAndRow(30, $row)->getValue(); //AE
							$uom3id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$bomversion = $objWorksheet->getCellByColumnAndRow(33, $row)->getValue(); //AF
							$sql = "select bomid from billofmaterial where bomversion = :bomversion and productid = :productid and plantid = :plantid";
							$command=$connection->createCommand($sql);
							$command->bindvalue(':bomversion',$bomversion,PDO::PARAM_STR);
							$command->bindvalue(':productid',$productid,PDO::PARAM_STR);
							$command->bindvalue(':plantid',$plantid,PDO::PARAM_STR);
							$bomid = $command->queryScalar();
							$sloccode = $objWorksheet->getCellByColumnAndRow(34, $row)->getValue(); //AG
							$slocfromid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
							$sloccode = $objWorksheet->getCellByColumnAndRow(35, $row)->getValue(); //AH
							$sloctoid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
							$itemnote = $objWorksheet->getCellByColumnAndRow(36, $row)->getValue(); //AI	
							$this->ModifyDetail($connection,array(
								'',
								$pid,
								$ppid,
								$productid,
								$uomid,
								$uom2id,
								$uom3id,
								$qty,
								$qty2,
								$qty3,
								$bomid,
								$slocfromid,
								$sloctoid,
								$description
							));	
						}
					}
				}
				$transaction->commit();
				GetMessage(false, getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollBack();
				GetMessage(true, 'Line: '.$row.' ==> '.$e->getMessage());
			}
    }
	}
	private function ModifyHasil($connection,$arraydata) {
		if ($arraydata[0]=='') {
			$sql     = 'call InsertProductplanhasil (:vproductplanid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vqty,:vqty2,:vqty3,:vbomid,
				:vprocessprdid,:vmesinid,:vsloctoid,:vstartdate,:venddate,:vdescription,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call UpdateProductplanhasil (:vid,:vproductplanid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vqty,:vqty2,:vqty3,:vbomid,
				:vprocessprdid,:vmesinid,:vsloctoid,:vstartdate,:venddate,:vdescription,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vproductplanid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vuom2id', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vuom3id', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vqty', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vqty2', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vqty3', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vbomid', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vprocessprdid', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vmesinid', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vsloctoid', $arraydata[12], PDO::PARAM_STR);
		$command->bindvalue(':vstartdate', $arraydata[13], PDO::PARAM_STR);
		$command->bindvalue(':venddate', $arraydata[14], PDO::PARAM_STR);
		$command->bindvalue(':vdescription', $arraydata[15], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSaveHasil() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      $this->ModifyHasil($connection,array(
				(isset($_POST['productplanfgid'])?$_POST['productplanfgid']:''),
				$_POST['productplanid'],
				$_POST['productid'],
				$_POST['uomid'],
				$_POST['uom2id'],
				$_POST['uom3id'],
				$_POST['qty'],
				$_POST['qty2'],
				$_POST['qty3'],
				$_POST['bomid'],
				$_POST['processprdid'],
				$_POST['mesinid'],
				$_POST['sloctoid'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['startdate'])),
				date(Yii::app()->params['datetodb'], strtotime($_POST['enddate'])),
				$_POST['description']
			));
      $transaction->commit();
      GetMessage(false, getcatalog('insertsuccess'));
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
	private function ModifyDetail($connection,$arraydata) {
		if ($arraydata[0] == '') {
			$sql     = 'call InsertProductplanbahan (:vproductplanid,:vproductplanfgid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vqty,:vqty2,:vqty3,
				:vbomid,:vslocfromid,:vsloctoid,:vdescription,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call UpdateProductplanbahan (:vid,:vproductplanid,:vproductplanfgid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vqty,:vqty2,:vqty3,
				:vbomid,:vslocfromid,:vsloctoid,:vdescription,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vproductplanid', $arraydata[1], PDO::PARAM_STR);
	  $command->bindvalue(':vproductplanfgid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vuom2id', $arraydata[5], PDO::PARAM_STR);
	  $command->bindvalue(':vuom3id', $arraydata[6], PDO::PARAM_STR);
	  $command->bindvalue(':vqty', $arraydata[7], PDO::PARAM_STR);
	  $command->bindvalue(':vqty2', $arraydata[8], PDO::PARAM_STR);
	  $command->bindvalue(':vqty3', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vbomid', $arraydata[10], PDO::PARAM_STR);
	  $command->bindvalue(':vslocfromid', $arraydata[11], PDO::PARAM_STR);
	  $command->bindvalue(':vsloctoid', $arraydata[12], PDO::PARAM_STR);
		$command->bindvalue(':vdescription', $arraydata[13], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
  public function actionsavedetail() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDetail($connection,array(
				(isset($_POST['productplandetailid'])?$_POST['productplandetailid']:''),
				$_POST['productplanid'],
				$_POST['productplanfgid'],
				$_POST['productid'],
				$_POST['uomid'],
				$_POST['uom2id'],
				$_POST['uom3id'],
				$_POST['qty'],
				$_POST['qty2'],
				$_POST['qty3'],
				$_POST['bomid'],
				$_POST['slocfromid'],
				$_POST['sloctoid'],
				$_POST['description']
			));
      $transaction->commit();
      GetMessage(false, getcatalog('insertsuccess'));
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
  public function actionPurge() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeproductplan(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
          $command->bindvalue(':vid', $id, PDO::PARAM_STR);
          $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
          $command->execute();
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  public function actionPurgehasil() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeproductplanfg(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  public function actionPurgebahan() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeproductplandetail(:vid,:vdatauser)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, 'chooseone');
    }
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['titleid'] = GetCatalog('productplanid');
		$this->dataprint['titleproductplanno'] = GetCatalog('productplanno');
		$this->dataprint['titleplantcode'] = GetCatalog('plantcode');
		$this->dataprint['titleproductplandate'] = GetCatalog('productplandate');
		$this->dataprint['titlesono'] = GetCatalog('sono');
		$this->dataprint['titlesodate'] = GetCatalog('sodate');
		$this->dataprint['titlepocustno'] = GetCatalog('pocustno');
		$this->dataprint['titlefullname'] = GetCatalog('customer');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titlehasilproses'] = GetCatalog('hasilproses');
		$this->dataprint['titleproductname'] = GetCatalog('productname');
		$this->dataprint['titleproductcode'] = GetCatalog('productcode');
		$this->dataprint['titleqty'] = GetCatalog('qty');
		$this->dataprint['titleqty2'] = GetCatalog('qty2');
		$this->dataprint['titleqty3'] = GetCatalog('qty3');
		$this->dataprint['titleuomcode'] = GetCatalog('uomcode');
		$this->dataprint['titleuom2code'] = GetCatalog('uom2code');
		$this->dataprint['titleuom3code'] = GetCatalog('uom3code');
		$this->dataprint['titlefromsloccode'] = GetCatalog('slocfrom');
		$this->dataprint['titletosloccode'] = GetCatalog('slocprocess');
		$this->dataprint['titlekodemesin'] = GetCatalog('kodemesin');
		$this->dataprint['titleprocessprdname'] = GetCatalog('processprdname');
		$this->dataprint['titlebomversion'] = GetCatalog('bomversion');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['productplanno'] = GetSearchText(array('GET'),'productplanno');
    $this->dataprint['plantcode'] = GetSearchText(array('GET'),'plantcode');
    $this->dataprint['productplandate'] = GetSearchText(array('GET'),'productplandate');
    $this->dataprint['sono'] = GetSearchText(array('GET'),'sono');
    $this->dataprint['sodate'] = GetSearchText(array('GET'),'sodate');
    $this->dataprint['pocustno'] = GetSearchText(array('GET'),'pocustno');
    $this->dataprint['customer'] = GetSearchText(array('GET'),'customer');
  }
} 
