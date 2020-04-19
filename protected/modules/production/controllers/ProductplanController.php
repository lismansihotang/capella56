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
	private function GetDetailBOM($connection,$plantid,$productplanid,$parentid,$productplanfgid,$sodetailid,$productname,$orderqty,$orderqty2,$orderqty3,$stdqty,$stdqty2,$stdqty3,
		$startdate,$enddate,$productplandetailid) {
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
				",(a.qty/".$stdqty.")*".$orderqty.
				",(a.qty2/".$stdqty2.")*".$orderqty2;
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
					select a.qty,a.qty2,a.qty3,
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
					$this->GetDetailBOM($connection,$plantid,$productplanid,$productplanfgid,$productplanfgid,$sodetailid,$std['productname'],$qty,$qty2,$qty3,$std['qty'],$std['qty2'],$std['qty3'],
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
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  public function search() {
    header('Content-Type: application/json');
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
		$productcode = GetSearchText(array('POST','Q'),'productcode');
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
				and t.recordstatus = getwfmaxstatbywfname('appprodplan')
				and t.plantid in (".getUserObjectValues('plant').")".
				(($plantid != '%%')?"
				and t.plantid = ".$plantid:'')."
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
				and t.recordstatus = getwfmaxstatbywfname('appprodplan')
				and t.plantid in (".getUserObjectValues('plant').")
				and t.plantid = ".$plantid." 
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
				and t.recordstatus = getmaxstatbywfname('appprodplan')
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
				)":'').
				(($productcode != '')?"
				and t.productplanid in (
					select distinct z.productplanid 
					from productplanfg z 
					join product zz on zz.productid = z.productid 
					where coalesce(zz.productcode,'') like '%".$productcode."%'
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
			$cmd = Yii::app()->db->createCommand()->select("t.*,a.sono,b.plantcode,b.companyid,c.companyname,d.fullname,e.productplanno as parentplanno,
			(select sum(z.qty) from productplandetail z where z.productplanid = t.productplanid) as qty,
			(select sum(z.qtyres) from productplandetail z where z.productplanid = t.productplanid) as qtyres")
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
				and t.recordstatus = getwfmaxstatbywfname('appprodplan') 
				and t.plantid in (".getUserObjectValues('plant').")".
				(($plantid != '%%')?"
				and t.plantid = ".$plantid:'')."
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
			$cmd = Yii::app()->db->createCommand()->select("t.*,a.sono,b.plantcode,b.companyid,c.companyname,d.fullname,e.productplanno as parentplanno,
			(select sum(z.qty) from productplandetail z where z.productplanid = t.productplanid) as qty,
			(select sum(z.qtyres) from productplandetail z where z.productplanid = t.productplanid) as qtyres")
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
				and t.recordstatus = getwfmaxstatbywfname('appprodplan')
				and t.plantid in (".getUserObjectValues('plant').")
				and t.plantid = ".$plantid." 
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
			$cmd = Yii::app()->db->createCommand()->select("t.*, a.sono,b.plantcode,b.companyid,c.companyname,d.fullname,e.productplanno as parentplanno,
			(select sum(z.qty) from productplandetail z where z.productplanid = t.productplanid) as qty,
			(select sum(z.qtyres) from productplandetail z where z.productplanid = t.productplanid) as qtyres
			")
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
				and t.recordstatus = getwfmaxstatbywfname('appprodplan')
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
			$cmd = Yii::app()->db->createCommand()->select('t.*,a.sono,b.plantcode,b.companyid,c.companyname,
			d.fullname,e.productplanno as parentplanno,
			(select sum(z.qty) from productplandetail z where z.productplanid = t.productplanid) as qty,
			(select sum(z.qtyres) from productplandetail z where z.productplanid = t.productplanid) as qtyres')
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
				)":'').
				(($productcode != '')?"
				and t.productplanid in (
					select distinct z.productplanid 
					from productplanfg z 
					join product zz on zz.productid = z.productid 
					where coalesce(zz.productcode,'') like '%".$productcode."%'
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
				'qty' => $data['qty'],
				'qtyres' => $data['qtyres'],
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
			->select('c.productplanno,d.fullname as customer, a.productcode,a.productname,t.qty,t.startdate,t.enddate,e.uomcode,f.kodemesin,
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
					"\n Artikel: ".$data['productcode'].
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
    header('Content-Type: application/json');
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
    header('Content-Type: application/json');
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
						(
						SELECT IFNULL(SUM(zz.qty) ,0)
						FROM productoutputdetail zz
						where zz.productplandetailid = t.productplandetailid and zz.productid = t.productid 
						) as qtypakai,
						(
							SELECT IFNULL(SUM(zz.qty2) ,0)
							FROM productoutputdetail zz
							where zz.productplandetailid = t.productplandetailid and zz.productid = t.productid 
							) as qtypakai2,
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
			'qtyres' => Yii::app()->format->formatNumber($data['qtypakai']),
			'qtyres2' => Yii::app()->format->formatNumber($data['qtypakai2']),
			'qtyfr' => Yii::app()->format->formatNumber($data['qtyfr']),
			'qtyfr2' => Yii::app()->format->formatNumber($data['qtyfr2']),
			'qtyfr3' => Yii::app()->format->formatNumber($data['qtyfr3']),
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
      GetMessage(true, getcatalog('chooseone'));
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
      GetMessage(true, getcatalog('chooseone'));
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
      GetMessage(true, getcatalog('chooseone'));
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
      GetMessage(true, getcatalog('chooseone'));
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
								$bomversion = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue(); //R
								$sql = "select bomid from billofmaterial where bomversion = :bomversion and productid = :productid and plantid = :plantid";
								$command=$connection->createCommand($sql);
								$command->bindvalue(':bomversion',$bomversion,PDO::PARAM_STR);
								$command->bindvalue(':productid',$productid,PDO::PARAM_STR);
								$command->bindvalue(':plantid',$plantid,PDO::PARAM_STR);
								$bomid = $command->queryScalar();
								$processprd = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue(); //S
								$processprdid = Yii::app()->db->createCommand("select processprdid from processprd where processprdname = '".$processprd."'")->queryScalar();
								$kodemesin = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue(); //T
								$mesinid = Yii::app()->db->createCommand("select processprdid from processprd where processprdname = '".$processprd."'")->queryScalar();
								$sloccode = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue(); //U
								$slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
								$startdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(19, $row)->getValue())); //V
								$enddate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(20, $row)->getValue())); //W
								$itemnote = $objWorksheet->getCellByColumnAndRow(21, $row)->getValue(); //X
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
							$bomversion = $objWorksheet->getCellByColumnAndRow(33, $row)->getValue(); //AH
							$sql = "select bomid from billofmaterial where bomversion = :bomversion and productid = :productid and plantid = :plantid";
							$command=$connection->createCommand($sql);
							$command->bindvalue(':bomversion',$bomversion,PDO::PARAM_STR);
							$command->bindvalue(':productid',$productid,PDO::PARAM_STR);
							$command->bindvalue(':plantid',$plantid,PDO::PARAM_STR);
							$bomid = $command->queryScalar();
							$sloccode = $objWorksheet->getCellByColumnAndRow(34, $row)->getValue(); //AI
							$slocfromid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
							$sloccode = $objWorksheet->getCellByColumnAndRow(35, $row)->getValue(); //AJ
							$sloctoid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
							$itemnote = $objWorksheet->getCellByColumnAndRow(36, $row)->getValue(); //AK	
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
			$sql     = 'call InsertProductplanfg (:vproductplanid,:vproductid,:vuomid,:vuom2id,:vuom3id,:vqty,:vqty2,:vqty3,:vbomid,
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
    header('Content-Type: application/json');
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
    header('Content-Type: application/json');
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
    header('Content-Type: application/json');
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
    header('Content-Type: application/json');
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
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  public function actionPurgebahan() {
    header('Content-Type: application/json');
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
      GetMessage(true, getcatalog('chooseone'));
    }
  }
  public function actionDownPDF() {
    parent::actionDownload();
		$sql = "select a.*,b.sono,b.sodate,b.pocustno,c.fullname,a.description
      from productplan a
			left join soheader b on b.soheaderid = a.soheaderid
			left join addressbook c on c.addressbookid = a.addressbookid
			";
		if ($_GET['id'] !== '') {
      $sql = $sql . "where a.productplanid in (" . $_GET['id'] . ")";
		}
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
	  $this->pdf->title = GetCatalog('productplan');
	  $this->pdf->AddPage('L',array(210,330));
    foreach($dataReader as $row)  {
			$this->pdf->SetFontSize(10);
      /* 
			$this->pdf->text(15, $this->pdf->gety() + 5, 'No OK ');
      $this->pdf->text(40, $this->pdf->gety() + 5, ': ' . $row['productplanno']);
      $this->pdf->text(90, $this->pdf->gety() + 5, 'No SO ');
      $this->pdf->text(115, $this->pdf->gety() + 5, ': ' . $row['sono']);
			$this->pdf->text(90, $this->pdf->gety() + 10, 'Customer ');
      $this->pdf->text(115, $this->pdf->gety() + 10, ': ' . $row['fullname']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Tgl SPP ');
      $this->pdf->text(40, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])));
			$this->pdf->text(195, $this->pdf->gety() + 5, 'No PO Customer ');
      $this->pdf->text(230, $this->pdf->gety() + 5, ': ' . $row['pocustno']);
			$this->pdf->text(15, $this->pdf->gety() + 15, 'Keterangan ');
      $this->pdf->text(40, $this->pdf->gety() + 15, ': ' . $row['description']);
			*/
      $sql1 = "select b.productname, a.qty,a.qty2,a.qty3,c.uomcode,a.productplanfgid,j.bomversion,
							e.uomcode as uom2code,
							f.uomcode as uom3code,
							a.description,d.sloccode,
							d.description as slocdesc,i.processprdname,h.kodemesin,h.namamesin,a.startdate,a.enddate,
														(
							select zz.delvdate 
							from sodetail zz 
							where zz.sodetailid = a.sodetailid and zz.productid = a.productid 
							) as delvdate,
							(
							select zz.qty
							from sodetail zz 
							where zz.sodetailid = a.sodetailid and zz.productid = a.productid 
							) as qtyso
        from productplanfg a
        left join product b on b.productid = a.productid
				left join unitofmeasure c on c.unitofmeasureid = a.uomid
				left join unitofmeasure e on e.unitofmeasureid = a.uom2id
				left join unitofmeasure f on f.unitofmeasureid = a.uom3id
				left join sloc d on d.slocid = a.sloctoid
				left join processprd i on i.processprdid = a.processprdid
				left join mesin h on h.mesinid = a.mesinid 
				left join billofmaterial j on j.bomid = a.bomid 
        where a.productplanid = ".$row['productplanid']." order by productplanfgid desc,parentid asc ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $i=0;
      //$this->pdf->sety($this->pdf->gety()+25);
			$parentid = '';$proseske=0;
			
      foreach($dataReader1 as $row1) {
				$this->pdf->text(15, $this->pdf->gety() + 5, 'No OK ');
      $this->pdf->text(40, $this->pdf->gety() + 5, ': ' . $row['productplanno']);
      $this->pdf->text(90, $this->pdf->gety() + 5, 'No OS ');
      $this->pdf->text(115, $this->pdf->gety() + 5, ': ' . $row['sono']);
			$this->pdf->text(90, $this->pdf->gety() + 10, 'Customer ');
      $this->pdf->text(115, $this->pdf->gety() + 10, ': ' . $row['fullname']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Tgl OK ');
      $this->pdf->text(40, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])));
			$this->pdf->text(195, $this->pdf->gety() + 5, 'No PO Customer ');
      $this->pdf->text(230, $this->pdf->gety() + 5, ': ' . $row['pocustno']);
			$this->pdf->text(15, $this->pdf->gety() + 15, 'Keterangan ');
      $this->pdf->text(40, $this->pdf->gety() + 15, ': ' . $row['description']);
			$this->pdf->sety($this->pdf->gety()+25);
        $i=$i+1;
				$this->pdf->SetFontSize(9);$proseske+=1;
				$this->pdf->text(10,$this->pdf->gety(),'HASIL PROSES '.$proseske);
				$this->pdf->text(10,$this->pdf->gety()+5,'Product');$this->pdf->text(30,$this->pdf->gety()+5,': '.$row1['productname']);
				$this->pdf->text(10,$this->pdf->gety()+10,'Versi BOM');$this->pdf->text(30,$this->pdf->gety()+10,': '.$row1['bomversion']);
				$this->pdf->text(10,$this->pdf->gety()+15,'Qty OS');$this->pdf->text(30,$this->pdf->gety()+15,': '.Yii::app()->format->formatNumber($row1['qtyso']).'   '.$row1['uomcode']);
				$this->pdf->text(10,$this->pdf->gety()+20,'Qty');$this->pdf->text(30,$this->pdf->gety()+20,': '.Yii::app()->format->formatNumber($row1['qty']).'   '.$row1['uomcode']);
				$this->pdf->text(10,$this->pdf->gety()+25,'Qty2');$this->pdf->text(30,$this->pdf->gety()+25,': '.Yii::app()->format->formatNumber($row1['qty2']).'   '.$row1['uom2code']);
				$this->pdf->text(10,$this->pdf->gety()+30,'Qty3');$this->pdf->text(30,$this->pdf->gety()+30,': '.Yii::app()->format->formatNumber($row1['qty3']).'   '.$row1['uom3code']);
				$this->pdf->text(110,$this->pdf->gety()+15,'Mesin');$this->pdf->text(140,$this->pdf->gety()+15,': '.$row1['kodemesin']);
				$this->pdf->text(110,$this->pdf->gety()+20,'Dept Proses');$this->pdf->text(140,$this->pdf->gety()+20,': '.$row1['sloccode']);
				$this->pdf->text(110,$this->pdf->gety()+25,'Proses');$this->pdf->text(140,$this->pdf->gety()+25,': '.$row1['processprdname']);
				//$this->pdf->text(10,$this->pdf->gety()+50,'Keterangan');$this->pdf->text(25,$this->pdf->gety()+50,': '.$row1['description']);
				$this->pdf->text(110, $this->pdf->gety()+30,'Tgl Mulai');$this->pdf->text(140,$this->pdf->gety()+30,': '.date(Yii::app()->params['dateviewfromdb'], strtotime($row1['startdate'])));
				$this->pdf->text(110, $this->pdf->gety()+35,'Tgl Selesai');$this->pdf->text(140,$this->pdf->gety()+35,': '.date(Yii::app()->params['dateviewfromdb'], strtotime($row1['enddate'])));
				if ($row1['delvdate'] != null) {
					$this->pdf->text(210, $this->pdf->gety()+15,'Tgl Kirim');$this->pdf->text(240,$this->pdf->gety()+15,': '.date(Yii::app()->params['dateviewfromdb'], strtotime($row1['delvdate'])));
				}
				$this->pdf->text(10,$this->pdf->gety()+40,'Keterangan');$this->pdf->text(30,$this->pdf->gety()+40,': '.$row1['description']);
				
				$this->pdf->sety($this->pdf->gety()+40);
				$sql2 = "select b.productname,
								sum(a.qty) as qty,
								sum(a.qty2) as qty2,
								sum(a.qty3) as qty3,
								c.uomcode,
								f.uomcode as uom2code,
								g.uomcode as uom3code,
								a.description,d.bomversion,
							(select sloccode from sloc d where d.slocid = a.slocfromid) as fromsloccode,
							(select description from sloc d where d.slocid = a.slocfromid) as fromslocdesc,
							(select sloccode from sloc d where d.slocid = a.sloctoid) as tosloccode,	
							(select description from sloc d where d.slocid = a.sloctoid) as toslocdesc						
							from productplandetail a
							left join product b on b.productid = a.productid
							left join unitofmeasure c on c.unitofmeasureid = a.uomid
							left join unitofmeasure f on f.unitofmeasureid = a.uom2id
							left join unitofmeasure g on g.unitofmeasureid = a.uom3id
							left join billofmaterial d on d.bomid = a.bomid
							where b.isstock = 1 and productplanid = ".$row['productplanid']." and productplanfgid = ".$row1['productplanfgid']."   
							group by b.productname,c.uomcode,d.bomversion,fromsloccode,fromslocdesc,tosloccode,toslocdesc, productplanfgid ";
				$command2    = $this->connection->createCommand($sql2);
				$dataReader2 = $command2->queryAll();
				$this->pdf->text(10,$this->pdf->gety()+10,'BAHAN PEMBUAT');
				$this->pdf->SetFontSize(9);
				$this->pdf->sety($this->pdf->gety()+12);
				$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C');
				$this->pdf->setwidths(array(8,65,23,15,23,15,23,15,23,15,24,30,40));
				$this->pdf->colheader = array('No','Items','Qty','Unit','Qty2','Unit2','Qty3','Unit3','GD Asal','GD Proses','Remark');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('L','L','R','L','R','L','R','L','R','L','L','L','L');
				$j=0;
				foreach($dataReader2 as $row2) {
					$j=$j+1;
					$this->pdf->row(array($j,$row2['productname'],
							Yii::app()->format->formatNumber($row2['qty']),
							$row2['uomcode'],
							Yii::app()->format->formatNumber($row2['qty2']),
							$row2['uom2code'],
							Yii::app()->format->formatNumber($row2['qty3']),
							$row2['uom3code'],
							$row2['fromsloccode'],
							$row2['tosloccode'],
							$row2['bomversion'].''.$row2['description']));
				}
				//$this->pdf->sety($this->pdf->gety());
				$this->pdf->text(20,$this->pdf->gety()+15,'Approved By');$this->pdf->text(150,$this->pdf->gety()+15,'Proposed By');
				$this->pdf->text(20,$this->pdf->gety()+30,'____________ ');$this->pdf->text(150,$this->pdf->gety()+30,'____________');
				$this->pdf->CheckPageBreak(20);
				$this->pdf->AddPage('L',array(210,330));
			}      
		}
	  $this->pdf->Output();
	}
	public function actionPDFpakai() {
    parent::actionDownload();
		$sql = "select a.*,b.sono,b.sodate,b.pocustno,c.fullname,a.description
      from productplan a
			left join soheader b on b.soheaderid = a.soheaderid
			left join addressbook c on c.addressbookid = a.addressbookid
			";
		if ($_GET['id'] !== '') {
      $sql = $sql . "where a.productplanid in (" . $_GET['id'] . ")";
		}
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
	  $this->pdf->title = GetCatalog('productplan');
	  $this->pdf->AddPage('L',array(210,360));
    foreach($dataReader as $row)  {
			$this->pdf->SetFontSize(10);
      $sql1 = "select b.productname, a.qty,a.qty2,a.qty3,c.uomcode,a.productplanfgid,j.bomversion,
							e.uomcode as uom2code,
							f.uomcode as uom3code,
							a.description,d.sloccode,
							d.description as slocdesc,i.processprdname,h.kodemesin,h.namamesin,a.startdate,a.enddate,
														(
							select zz.delvdate 
							from sodetail zz 
							where zz.sodetailid = a.sodetailid and zz.productid = a.productid 
							) as delvdate,
							(
							select zz.qty
							from sodetail zz 
							where zz.sodetailid = a.sodetailid and zz.productid = a.productid 
							) as qtyso
        from productplanfg a
        left join product b on b.productid = a.productid
				left join unitofmeasure c on c.unitofmeasureid = a.uomid
				left join unitofmeasure e on e.unitofmeasureid = a.uom2id
				left join unitofmeasure f on f.unitofmeasureid = a.uom3id
				left join sloc d on d.slocid = a.sloctoid
				left join processprd i on i.processprdid = a.processprdid
				left join mesin h on h.mesinid = a.mesinid 
				left join billofmaterial j on j.bomid = a.bomid 
        where a.productplanid = ".$row['productplanid']." order by productplanfgid desc,parentid asc ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $i=0;
			$parentid = '';$proseske=0;
			
      foreach($dataReader1 as $row1) {
				$this->pdf->text(15, $this->pdf->gety() + 5, 'No OK ');
      $this->pdf->text(40, $this->pdf->gety() + 5, ': ' . $row['productplanno']);
      $this->pdf->text(90, $this->pdf->gety() + 5, 'No OS ');
      $this->pdf->text(115, $this->pdf->gety() + 5, ': ' . $row['sono']);
			$this->pdf->text(90, $this->pdf->gety() + 10, 'Customer ');
      $this->pdf->text(115, $this->pdf->gety() + 10, ': ' . $row['fullname']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Tgl OK ');
      $this->pdf->text(40, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])));
			$this->pdf->text(195, $this->pdf->gety() + 5, 'No PO Customer ');
      $this->pdf->text(230, $this->pdf->gety() + 5, ': ' . $row['pocustno']);
			$this->pdf->text(15, $this->pdf->gety() + 15, 'Keterangan ');
      $this->pdf->text(40, $this->pdf->gety() + 15, ': ' . $row['description']);
			$this->pdf->sety($this->pdf->gety()+25);
        $i=$i+1;
				$this->pdf->SetFontSize(9);$proseske+=1;
				$this->pdf->text(10,$this->pdf->gety(),'HASIL PROSES '.$proseske);
				$this->pdf->text(10,$this->pdf->gety()+5,'Product');$this->pdf->text(30,$this->pdf->gety()+5,': '.$row1['productname']);
				$this->pdf->text(10,$this->pdf->gety()+10,'Versi BOM');$this->pdf->text(30,$this->pdf->gety()+10,': '.$row1['bomversion']);
				$this->pdf->text(10,$this->pdf->gety()+15,'Qty OS');$this->pdf->text(30,$this->pdf->gety()+15,': '.Yii::app()->format->formatNumber($row1['qtyso']).'   '.$row1['uomcode']);
				$this->pdf->text(10,$this->pdf->gety()+20,'Qty');$this->pdf->text(30,$this->pdf->gety()+20,': '.Yii::app()->format->formatNumber($row1['qty']).'   '.$row1['uomcode']);
				$this->pdf->text(10,$this->pdf->gety()+25,'Qty2');$this->pdf->text(30,$this->pdf->gety()+25,': '.Yii::app()->format->formatNumber($row1['qty2']).'   '.$row1['uom2code']);
				$this->pdf->text(10,$this->pdf->gety()+30,'Qty3');$this->pdf->text(30,$this->pdf->gety()+30,': '.Yii::app()->format->formatNumber($row1['qty3']).'   '.$row1['uom3code']);
				$this->pdf->text(110,$this->pdf->gety()+15,'Mesin');$this->pdf->text(140,$this->pdf->gety()+15,': '.$row1['kodemesin']);
				$this->pdf->text(110,$this->pdf->gety()+20,'Dept Proses');$this->pdf->text(140,$this->pdf->gety()+20,': '.$row1['sloccode']);
				$this->pdf->text(110,$this->pdf->gety()+25,'Proses');$this->pdf->text(140,$this->pdf->gety()+25,': '.$row1['processprdname']);
				//$this->pdf->text(10,$this->pdf->gety()+50,'Keterangan');$this->pdf->text(25,$this->pdf->gety()+50,': '.$row1['description']);
				$this->pdf->text(110, $this->pdf->gety()+30,'Tgl Mulai');$this->pdf->text(140,$this->pdf->gety()+30,': '.date(Yii::app()->params['dateviewfromdb'], strtotime($row1['startdate'])));
				$this->pdf->text(110, $this->pdf->gety()+35,'Tgl Selesai');$this->pdf->text(140,$this->pdf->gety()+35,': '.date(Yii::app()->params['dateviewfromdb'], strtotime($row1['enddate'])));
				if ($row1['delvdate'] != null) {
					$this->pdf->text(210, $this->pdf->gety()+15,'Tgl Kirim');$this->pdf->text(240,$this->pdf->gety()+15,': '.date(Yii::app()->params['dateviewfromdb'], strtotime($row1['delvdate'])));
				}
				$this->pdf->text(10,$this->pdf->gety()+40,'Keterangan');$this->pdf->text(30,$this->pdf->gety()+40,': '.$row1['description']);
				
				$this->pdf->sety($this->pdf->gety()+40);
				$sql2 = "select b.productname,
								sum(a.qty) as qty,
								sum(a.qty2) as qty2,
								sum(a.qty3) as qty3,
								c.uomcode,
								f.uomcode as uom2code,
								g.uomcode as uom3code,
								a.description,d.bomversion,
							(select sloccode from sloc d where d.slocid = a.slocfromid) as fromsloccode,
							(select description from sloc d where d.slocid = a.slocfromid) as fromslocdesc,
							(select sloccode from sloc d where d.slocid = a.sloctoid) as tosloccode,	
							(select description from sloc d where d.slocid = a.sloctoid) as toslocdesc,
							(
								SELECT IFNULL(SUM(zz.qty) ,0)
								FROM productoutputdetail zz
								where zz.productplandetailid = a.productplandetailid and zz.productid = a.productid 
								) as qtypakai						
							from productplandetail a
							left join product b on b.productid = a.productid
							left join unitofmeasure c on c.unitofmeasureid = a.uomid
							left join unitofmeasure f on f.unitofmeasureid = a.uom2id
							left join unitofmeasure g on g.unitofmeasureid = a.uom3id
							left join billofmaterial d on d.bomid = a.bomid
							where b.isstock = 1 and productplanid = ".$row['productplanid']." and productplanfgid = ".$row1['productplanfgid']."   
							group by b.productname,c.uomcode,d.bomversion,fromsloccode,fromslocdesc,tosloccode,toslocdesc, productplanfgid ";
				$command2    = $this->connection->createCommand($sql2);
				$dataReader2 = $command2->queryAll();
				$this->pdf->text(10,$this->pdf->gety()+10,'BAHAN PEMBUAT');
				$this->pdf->SetFontSize(9);
				$this->pdf->sety($this->pdf->gety()+12);
				$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C');
				$this->pdf->setwidths(array(8,65,23,23,15,23,15,23,15,23,15,24,30,40));
				$this->pdf->colheader = array('No','Items','Qty','Qty Pakai','Unit','Qty2','Unit2','Qty3','Unit3','GD Asal','GD Proses','Remark');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('L','L','R','R','L','R','L','R','L','R','L','L','L','L');
				$j=0;
				foreach($dataReader2 as $row2) {
					$j=$j+1;
					$this->pdf->row(array($j,$row2['productname'],
							Yii::app()->format->formatNumber($row2['qty']),
							Yii::app()->format->formatNumber($row2['qtypakai']),
							$row2['uomcode'],
							Yii::app()->format->formatNumber($row2['qty2']),
							$row2['uom2code'],
							Yii::app()->format->formatNumber($row2['qty3']),
							$row2['uom3code'],
							$row2['fromsloccode'],
							$row2['tosloccode'],
							$row2['bomversion'].''.$row2['description']));
				}
				$this->pdf->text(20,$this->pdf->gety()+15,'Approved By');$this->pdf->text(150,$this->pdf->gety()+15,'Proposed By');
				$this->pdf->text(20,$this->pdf->gety()+30,'____________ ');$this->pdf->text(150,$this->pdf->gety()+30,'____________');
				$this->pdf->CheckPageBreak(20);
				$this->pdf->AddPage('L',array(210,330));
			}      
		}
	  $this->pdf->Output();
	}
	public function actionPdfOperator() {
    parent::actionDownload();
		$sql = "select a.*,b.sono,b.sodate,b.pocustno,c.fullname,a.description
      from productplan a
			left join soheader b on b.soheaderid = a.soheaderid
			left join addressbook c on c.addressbookid = a.addressbookid
			";
		if ($_GET['id'] !== '') {
      $sql = $sql . "where a.productplanid in (" . $_GET['id'] . ")";
		}
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
	  $this->pdf->title = GetCatalog('productoutput');
	  $this->pdf->AddPage('L','legal');
    foreach($dataReader as $row)  {
      $sql1 = "select b.productname, a.qty,a.qty2,a.qty3,c.uomcode,a.productplanfgid,j.bomversion,
							e.uomcode as uom2code,
							f.uomcode as uom3code,
							a.description,d.sloccode,
							d.description as slocdesc,i.processprdname,h.kodemesin,h.namamesin
        from productplanfg a
        left join product b on b.productid = a.productid
				left join unitofmeasure c on c.unitofmeasureid = a.uomid
				left join unitofmeasure e on e.unitofmeasureid = a.uom2id
				left join unitofmeasure f on f.unitofmeasureid = a.uom3id
				left join sloc d on d.slocid = a.sloctoid
				left join processprd i on i.processprdid = a.processprdid
				left join mesin h on h.mesinid = a.mesinid 
				left join billofmaterial j on j.bomid = a.bomid 
        where a.productplanid = ".$row['productplanid']." order by productplanfgid desc,parentid asc ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $i=0;
      $this->pdf->sety($this->pdf->gety());
			$proseske=0;
      foreach($dataReader1 as $row1) {
				$this->pdf->SetFontSize(10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No OK ');
      $this->pdf->text(40, $this->pdf->gety() + 5, ': ' . $row['productplanno']);
      $this->pdf->text(90, $this->pdf->gety() + 5, 'No OS ');
      $this->pdf->text(115, $this->pdf->gety() + 5, ': ' . $row['sono']);
			$this->pdf->text(90, $this->pdf->gety() + 10, 'Customer ');
      $this->pdf->text(115, $this->pdf->gety() + 10, ': ' . $row['fullname']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Tgl OK ');
      $this->pdf->text(40, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])));
			$this->pdf->text(195, $this->pdf->gety() + 5, 'No PO Customer ');
      $this->pdf->text(230, $this->pdf->gety() + 5, ': ' . $row['pocustno']);
			$this->pdf->text(15, $this->pdf->gety() + 15, 'Keterangan ');
      $this->pdf->text(40, $this->pdf->gety() + 15, ': ' . $row['description']);
			$this->pdf->sety($this->pdf->gety()+25);
        $i=$i+1;
				$this->pdf->SetFontSize(8);$proseske+=1;
				$this->pdf->text(10,$this->pdf->gety(),'HASIL PROSES '.$proseske);
				$this->pdf->text(10,$this->pdf->gety()+5,'Product');$this->pdf->text(25,$this->pdf->gety()+5,': '.$row1['productname']);
				$this->pdf->text(10,$this->pdf->gety()+10,'Versi BOM');$this->pdf->text(25,$this->pdf->gety()+10,': '.$row1['bomversion']);
				$this->pdf->text(10,$this->pdf->gety()+15,'Qty') ;$this->pdf->text(25,$this->pdf->gety()+15,':................................'.$row1['uomcode']);
				$this->pdf->text(10,$this->pdf->gety()+20,'Qty2');$this->pdf->text(25,$this->pdf->gety()+20,':................................'.$row1['uom2code']);
				$this->pdf->text(10,$this->pdf->gety()+25,'Qty3');$this->pdf->text(25,$this->pdf->gety()+25,':................................'.$row1['uom3code']);
				$this->pdf->text(10,$this->pdf->gety()+35,'Keterangan');$this->pdf->text(25,$this->pdf->gety()+35,': ');
				$this->pdf->text(110,$this->pdf->gety()+15,'Dept Proses ');$this->pdf->text(140,$this->pdf->gety()+15,': '.$row1['sloccode']);
				$this->pdf->text(110,$this->pdf->gety()+20,'Proses');$this->pdf->text(140,$this->pdf->gety()+20,': '.$row1['processprdname']);
				$this->pdf->text(110,$this->pdf->gety()+25,'Mesin');$this->pdf->text(140,$this->pdf->gety()+25,':.......................');
				$this->pdf->text(110,$this->pdf->gety()+30,'Shift');$this->pdf->text(140,$this->pdf->gety()+30,':.......................');
				$this->pdf->text(180,$this->pdf->gety()+15,'Angkatan');$this->pdf->text(195,$this->pdf->gety()+15,':....................');
				$this->pdf->text(180,$this->pdf->gety()+20,'Efektivitas');$this->pdf->text(195,$this->pdf->gety()+20,':.................... '.' '.'Menit');
				$this->pdf->text(180,$this->pdf->gety()+25,'SPV');$this->pdf->text(195,$this->pdf->gety()+25,':............................');
				$this->pdf->sety($this->pdf->gety()+45);
				$sql2 = "select b.productname,
								sum(a.qty) as qty,
								sum(a.qty2) as qty2,
								sum(a.qty3) as qty3,
								c.uomcode,
								f.uomcode as uom2code,
								g.uomcode as uom3code,
								a.description,d.bomversion,
							(select sloccode from sloc d where d.slocid = a.slocfromid) as fromsloccode,
							(select description from sloc d where d.slocid = a.slocfromid) as fromslocdesc,
							(select sloccode from sloc d where d.slocid = a.sloctoid) as tosloccode,	
							(select description from sloc d where d.slocid = a.sloctoid) as toslocdesc			
							from productplandetail a
							left join product b on b.productid = a.productid
							left join unitofmeasure c on c.unitofmeasureid = a.uomid
							left join unitofmeasure f on f.unitofmeasureid = a.uom2id
							left join unitofmeasure g on g.unitofmeasureid = a.uom3id
							left join billofmaterial d on d.bomid = a.bomid
							where b.isstock = 1 and productplanid = ".$row['productplanid']." and productplanfgid = ".$row1['productplanfgid']."   
							group by b.productname,c.uomcode,d.bomversion,fromsloccode,fromslocdesc,tosloccode,toslocdesc, productplanfgid ";
				$command2    = $this->connection->createCommand($sql2);
				$dataReader2 = $command2->queryAll();
				$this->pdf->text(10,$this->pdf->gety(),'BAHAN PEMBUAT');
				$this->pdf->sety($this->pdf->gety()+2);
				$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C');
				$this->pdf->setwidths(array(8,130,30,30,30,30,70,30,80));
				$this->pdf->colheader = array('No','Items','Qty','Qty2','Qty3','Remark');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('L','L','R','R','R','R','L','L','L','L');
				$j=0;
				foreach($dataReader2 as $row2) {
					$j=$j+1;
					$this->pdf->row(array($j,$row2['productname'],
							$row2['uomcode'],
							$row2['uom2code'],
							$row2['uom3code'],
							$row2['bomversion'].''.$row2['description']));
				}
				$this->pdf->sety($this->pdf->gety()+10);
				$this->pdf->text(10,$this->pdf->gety()+5,'Operator');
				$this->pdf->sety($this->pdf->gety()+10);
				$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C');
				$this->pdf->setwidths(array(8,55,11,25,11,25,11,25,11,25,11,24,24,30));
				$this->pdf->colheader = array('No','Nama Operator');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('L','L','L','R','L','R','L','R','L','R','L','L','L','L');
				for($j=1;$j<6;$j++) {
					$this->pdf->sety($this->pdf->gety()+2);
					$this->pdf->row(array($j,''));
				}
				$this->pdf->sety($this->pdf->gety()+5);
				$this->pdf->text(120,$this->pdf->gety()-40,'Disetujui Oleh');$this->pdf->text(170,$this->pdf->gety()-40,'Dibuat Oleh');
				$this->pdf->text(120,$this->pdf->gety()-25,'____________ ');$this->pdf->text(170,$this->pdf->gety()-25,'____________');
				$this->pdf->AddPage('L','legal');
      }
		}
	  $this->pdf->Output();
	}
	public function actionPdfFG()
  {
    parent::actionDownload();		
    $sql = "select b.fullname as customer, d.sono, c.productname,c.startdate, e.shift, e.angkatan, f.fullname as spv,a.productplanno,c.qty
						from productplan a
						join addressbook b on b.addressbookid = a.addressbookid
						left join productplanfg c on c.productplanid = a.productplanid
						left join soheader d on d.soheaderid = a.soheaderid
						left join productoutputfg e on e.productplanfgid = c.productplanfgid and e.productid = c.productid
						left join employee f on f.employeeid = e.employeeid
		";
    if ($_GET['id'] !== '') {
      $sql = $sql . " where a.productplanid in (" . $_GET['id'] . ")";
    }
		$sql .= " order by c.productplanfgid asc limit 1";
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
		
		$this->pdf->sety($this->pdf->gety()-20);
	  $this->pdf->title = '';
    $this->pdf->AddPage('P','A4');    
    $this->pdf->setFont('Arial', '', 7);
		$this->pdf->setx(5);
		$this->pdf->sety(5);
    foreach ($dataReader as $row) {
			$this->pdf->colalign = array(
				'C',
				'C',
				'C',
			);
			$this->pdf->setwidths(array(
				65,
				65,
				65
			));
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array(
				'L',
				'L',
				'L'
			);
			$this->pdf->bordercell = array(
				'LRBT',
				'LRBT',
				'LRBT'
			);
			$x = $row['qty'] / 3;
			for ($j=1;$j<=$x;$j++) {
				$this->pdf->row(array(
					"\n"."PT TUNAS ALFIN Tbk"."\n".'Customer : '.$row['customer']."\n"."Tgl Proses : ".date(Yii::app()->params['dateviewfromdb'], strtotime($row['startdate']))."\n"."No SO/OK : ".
						$row['sono'].' / '.$row['productplanno']."\n".'ITEM : '.$row['productname']."\n"."Spec : "."\n"."No Lot : "."\n"."Tgl Kirim :"."\n    ",
					"\n"."PT TUNAS ALFIN Tbk"."\n".'Customer : '.$row['customer']."\n"."Tgl Proses : ".date(Yii::app()->params['dateviewfromdb'], strtotime($row['startdate']))."\n"."No SO/OK : ".
						$row['sono'].' / '.$row['productplanno']."\n".'ITEM : '.$row['productname']."\n"."Spec : "."\n"."No Lot : "."\n"."Tgl Kirim :"."\n    ",
					"\n"."PT TUNAS ALFIN Tbk"."\n".'Customer : '.$row['customer']."\n"."Tgl Proses : ".date(Yii::app()->params['dateviewfromdb'], strtotime($row['startdate']))."\n"."No SO/OK : ".
						$row['sono'].' / '.$row['productplanno']."\n".'ITEM : '.$row['productname']."\n"."Spec : "."\n"."No Lot : "."\n"."Tgl Kirim : "."\n    ",
				));
			}
    }
    $this->pdf->Output();
  }
	
	public function actionDownxls() {
    $this->menuname = 'productplanlist';
    parent::actionDownxls();
	  $productplanid 		= GetSearchText(array('POST','GET','Q'),'productplanid');
		$sono 		= GetSearchText(array('POST','GET','Q'),'sono');
		$customer 		= GetSearchText(array('POST','GET','Q'),'customer');
		$plantcode     		= GetSearchText(array('POST','GET','Q'),'plantcode');
    $sloccode     	= GetSearchText(array('POST','GET','Q'),'sloccode');
    $productplandate    = GetSearchText(array('POST','GET','Q'),'productplandate');
    $productplanno 		= GetSearchText(array('POST','GET','Q'),'productplanno');
		$description 		= GetSearchText(array('POST','GET','Q'),'description');
		$productname 		= GetSearchText(array('POST','GET','Q'),'productname');
		$sql = "select a.*,b.sono,b.sodate,b.pocustno,c.fullname,n.plantcode,m.sloccode,e.productname,f.uomcode,g.uomcode as uom2code,
			h.uomcode as uom3code,d.qty,d.qty2,d.qty3,o.productplanno as oklanjut,j.bomversion,
			d.description as fgdesc,p.processprdname,l.kodemesin,d.startdate,d.enddate,s.uomcode as uomdetail,t.uomcode as uom2detail,
			u.uomcode as uom3detail,r.productname as productjasa,q.qty as qtydetail,q.qty2 as qty2detail,q.qty3 as qty3detail,
			w.bomversion as bomdetail,q.description as descdetail,x.sloccode as sloctodetail,y.sloccode as slocfromdetail
      from productplan a
			left join soheader b on b.soheaderid = a.soheaderid
			left join addressbook c on c.addressbookid = a.addressbookid 
			left join productplanfg d on d.productplanid = a.productplanid 
			left join product e on e.productid = d.productid 
			left join unitofmeasure f on f.unitofmeasureid = d.uomid 
			left join unitofmeasure g on g.unitofmeasureid = d.uom2id 
			left join unitofmeasure h on h.unitofmeasureid = d.uom3id 
			left join billofmaterial j on j.bomid = d.bomid 
			left join processprd k on k.processprdid = d.processprdid 
			left join mesin l on l.mesinid = d.mesinid 
			left join sloc m on m.slocid = d.sloctoid 
			left join plant n on n.plantid = a.plantid 
			left join productplan o on o.productplanid = a.parentplanid 
			left join processprd p on p.processprdid = k.processprdid  
			left join productplandetail q on q.productplanfgid = d.productplanfgid 
			left join product r on r.productid = q.productid 
			left join unitofmeasure s on s.unitofmeasureid = q.uomid 
			left join unitofmeasure t on t.unitofmeasureid = q.uom2id 
			left join unitofmeasure u on u.unitofmeasureid = q.uom3id 
			left join billofmaterial w on w.bomid = q.bomid 
			left join sloc x on x.slocid = q.sloctoid 
			left join sloc y on y.slocid = q.slocfromid 
		";
		$sql .= " where coalesce(a.productplanid,'') like '".$productplanid."' 
			and coalesce(n.plantcode,'') like '".$plantcode."' 
			and coalesce(a.productplandate,'') like '".$productplandate."' 
			and coalesce(a.productplanno,'') like '".$productplanno."' 
			and coalesce(b.sono,'') like '".$sono."' 
			and coalesce(a.description,'') like '".$description."'".
			(($productname != '%%')?"
				and coalesce(m.productname,'') like '".$productname."'
			":'')
		;		
		if ($_GET['id'] !== '') {
      $sql = $sql . " and a.productplanid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 2;$nourut=0;$oldbom='';
    foreach ($dataReader as $row) {
			if ($oldbom != $row['productplanid']) {
				$nourut+=1;
				$oldbom = $row['productplanid'];
			}
      $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $nourut)
				->setCellValueByColumnAndRow(1, $i+1, $row['plantcode'])
				->setCellValueByColumnAndRow(2, $i+1, $row['productplanno'])
				->setCellValueByColumnAndRow(3, $i+1, $row['sono'])
				->setCellValueByColumnAndRow(4, $i+1, $row['fullname'])
				->setCellValueByColumnAndRow(5, $i+1, date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])))
				->setCellValueByColumnAndRow(6, $i+1, $row['oklanjut'])
				->setCellValueByColumnAndRow(7, $i+1, $row['description'])
				->setCellValueByColumnAndRow(9, $i+1, $row['productname'])
				->setCellValueByColumnAndRow(10, $i+1, Yii::app()->format->formatNumber($row['qty']))
				->setCellValueByColumnAndRow(11, $i+1, $row['uomcode'])
				->setCellValueByColumnAndRow(12, $i+1, Yii::app()->format->formatNumber($row['qty2']))
				->setCellValueByColumnAndRow(13, $i+1, $row['uom2code'])
				->setCellValueByColumnAndRow(14, $i+1, Yii::app()->format->formatNumber($row['qty3']))
				->setCellValueByColumnAndRow(15, $i+1, $row['uom3code'])
				->setCellValueByColumnAndRow(18, $i+1, $row['bomversion'])
				->setCellValueByColumnAndRow(19, $i+1, $row['processprdname'])
				->setCellValueByColumnAndRow(20, $i+1, $row['kodemesin'])
				->setCellValueByColumnAndRow(21, $i+1, $row['sloccode'])
				->setCellValueByColumnAndRow(22, $i+1, date(Yii::app()->params['dateviewfromdb'], strtotime($row['startdate'])))
				->setCellValueByColumnAndRow(23, $i+1, date(Yii::app()->params['dateviewfromdb'], strtotime($row['enddate'])))
				->setCellValueByColumnAndRow(24, $i+1, $row['fgdesc'])
				->setCellValueByColumnAndRow(25, $i+1, $row['productjasa'])
				->setCellValueByColumnAndRow(26, $i+1, Yii::app()->format->formatNumber($row['qtydetail']))
				->setCellValueByColumnAndRow(27, $i+1, $row['uomdetail'])
				->setCellValueByColumnAndRow(28, $i+1, Yii::app()->format->formatNumber($row['qty2detail']))
				->setCellValueByColumnAndRow(29, $i+1, $row['uom2detail'])
				->setCellValueByColumnAndRow(30, $i+1, Yii::app()->format->formatNumber($row['qty3detail']))
				->setCellValueByColumnAndRow(31, $i+1, $row['uom3detail'])
				->setCellValueByColumnAndRow(34, $i+1, $row['bomdetail'])
				->setCellValueByColumnAndRow(35, $i+1, $row['sloctodetail'])
				->setCellValueByColumnAndRow(36, $i+1, $row['slocfromdetail'])
				->setCellValueByColumnAndRow(37, $i+1, $row['descdetail'])
				;
			$i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}