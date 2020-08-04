<?php
class ProductplantController extends Controller {
	public $menuname = 'productplant';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$productplantid = GetSearchText(array('POST','Q'),'productplantid');
		$productid = GetSearchText(array('POST','Q'),'productid',0,'int');
		$productcode = GetSearchText(array('POST','Q'),'productcode');
		$productname = GetSearchText(array('POST','Q'),'productname');
		$materialtypecode = GetSearchText(array('POST','Q'),'materialtypecode');
		$sloc = GetSearchText(array('POST','Q'),'sloc');
		$uom1 = GetSearchText(array('POST','Q'),'uom1');
		$uom2 = GetSearchText(array('POST','Q'),'uom2');
		$uom3 = GetSearchText(array('POST','Q'),'uom3');
		$uom4 = GetSearchText(array('POST','Q'),'uom4');
		$sled = GetSearchText(array('POST','Q'),'sled');
		$materialgroup = GetSearchText(array('POST','Q'),'materialgroup');
		$addressbook= GetSearchText(array('POST','Q'),'addressbook');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','productplantid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['getdata'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('productplant t')
				->leftjoin('product p','p.productid=t.productid')
				->leftjoin('sloc r','r.slocid=t.slocid')
				->leftjoin('unitofmeasure s','s.unitofmeasureid=t.uom1')
				->leftjoin('unitofmeasure w','w.unitofmeasureid=t.uom2')
				->leftjoin('unitofmeasure u','u.unitofmeasureid=t.uom3')
				->leftjoin('unitofmeasure v','v.unitofmeasureid=t.uom4')
				->leftjoin('materialgroup a','a.materialgroupid=t.materialgroupid')			
				->leftjoin('materialtype b','b.materialtypeid=p.materialtypeid')			
				->leftjoin('addressbook d','d.addressbookid=t.addressbookid')			
				->where("(coalesce(t.productplantid,'') like :productplantid) 
					and (coalesce(p.productcode,'') like :productcode) 
					and (coalesce(p.productname,'') like :productname) 
					and (coalesce(r.sloccode,'') like :sloc) 
					and (coalesce(s.uomcode,'') like :uom1) 
					and (coalesce(w.uomcode,'') like :uom2) 
					and (coalesce(u.uomcode,'') like :uom3) 
					and (coalesce(v.uomcode,'') like :uom4) 
					and (coalesce(b.materialtypecode,'') like :materialtypecode) 
					and (coalesce(a.materialgroupcode,'') like :materialgroup)
					and (coalesce(d.fullname,'') like :addressbook)
					",
						array(':productcode'=>$productcode,
								':productname'=>$productname,
								':sloc'=>$sloc,
								':uom1'=>$uom1,
								':uom2'=>$uom2,
								':uom3'=>$uom3,
								':uom4'=>$uom4,
								':materialtypecode'=>$materialtypecode,
								':materialgroup'=>$materialgroup,
								':addressbook'=>$addressbook,
								':productplantid'=>$productplantid))
				->queryScalar();
			$result['total'] = $cmd;
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,p.productcode,p.productname,r.sloccode,b.materialtypecode, r.description as slocdesc,s.uomcode,w.uomcode as uom2code,u.uomcode as uom3code,
								v.uomcode as uom4code,a.materialgroupcode,a.description,d.fullname')	
				->from('productplant t')
				->leftjoin('product p','p.productid=t.productid')
				->leftjoin('sloc r','r.slocid=t.slocid')
				->leftjoin('unitofmeasure s','s.unitofmeasureid=t.uom1')
				->leftjoin('unitofmeasure w','w.unitofmeasureid=t.uom2')
				->leftjoin('unitofmeasure u','u.unitofmeasureid=t.uom3')
				->leftjoin('unitofmeasure v','v.unitofmeasureid=t.uom4')
				->leftjoin('materialgroup a','a.materialgroupid=t.materialgroupid')
				->leftjoin('materialtype b','b.materialtypeid=p.materialtypeid')
				->leftjoin('addressbook d','d.addressbookid=t.addressbookid')
				->where("(coalesce(t.productplantid,'') like :productplantid) and
					(coalesce(p.productcode,'') like :productcode) and
					(coalesce(p.productname,'') like :productname) and 
					(coalesce(r.sloccode,'') like :sloc) and 
					(coalesce(s.uomcode,'') like :uom1) and 
					(coalesce(w.uomcode,'') like :uom2) and 
					(coalesce(u.uomcode,'') like :uom3) and 
					(coalesce(v.uomcode,'') like :uom4) and 
					(coalesce(b.materialtypecode,'') like :materialtypecode) and 
					(coalesce(a.materialgroupcode,'') like :materialgroup) and
					(coalesce(d.fullname,'') like :addressbook)",
						array(':productcode'=>$productcode,
								':productname'=>$productname,
								':sloc'=>$sloc,
								':uom1'=>$uom1,
								':uom2'=>$uom2,
								':uom3'=>$uom3,
								':uom4'=>$uom4,
								':materialtypecode'=>$materialtypecode,
								':materialgroup'=>$materialgroup,
								':addressbook'=>$addressbook,
								':productplantid'=>$productplantid))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
			foreach($cmd as $data) {	
				$row[] = array(
					'productplantid'=>$data['productplantid'],
					'productid'=>$data['productid'],
					'productcode'=>$data['productcode'],
					'productname'=>$data['productname'],
					'slocid'=>$data['slocid'],
					'sloccode'=>$data['sloccode'],
					'uom1'=>$data['uom1'],
					'uomcode'=>$data['uomcode'],
					'uom2'=>$data['uom2'],
					'uom2code'=>$data['uom2code'],
					'uom3'=>$data['uom3'],
					'uom3code'=>$data['uom3code'],
					'uom4'=>$data['uom4'],
					'materialtypecode'=>$data['materialtypecode'],
					'uom4code'=>$data['uom4code'],
					'qty'=>Yii::app()->format->formatNumber($data['qty']),
					'qty2'=>Yii::app()->format->formatNumber($data['qty2']),
					'qty3'=>Yii::app()->format->formatNumber($data['qty3']),
					'qty4'=>Yii::app()->format->formatNumber($data['qty4']),
					'isautolot'=>$data['isautolot'],
					'sled'=>$data['sled'],
					'issource'=>$data['issource'],
					'materialgroupid'=>$data['materialgroupid'],
					'materialgroupcode'=>$data['materialgroupcode'],
					'addressbookid'=>$data['addressbookid'],
					'fullname'=>$data['fullname'],
				);
			}
			$result=array_merge($result,array('rows'=>$row));
		} else {
			$cmd = Yii::app()->db->createCommand("
				select b.productid,a.uom1,c.uomcode as uom1code,a.uom2,d.uomcode as uom2code,a.uom3,e.uomcode as uom3code,
					f.uomcode as uom4code,a.uom4,b.productname,a.productid,b.productcode,a.qty,a.qty2,a.qty3,
					a.qty4,g.bomid, g.bomversion,g.processprdid,g.mesinid
				from productplant a 
				join product b on b.productid = a.productid 
				left join unitofmeasure c on c.unitofmeasureid = a.uom1 
				left join unitofmeasure d on d.unitofmeasureid = a.uom2
				left join unitofmeasure e on e.unitofmeasureid = a.uom3
				left join unitofmeasure f on f.unitofmeasureid = a.uom4
				left join billofmaterial g on g.productid = a.productid 
				where a.productid = ".$productid." and a.issource = 1"
			)->queryRow();
			$result = $cmd;
		}
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (int)$arraydata[0];
		if ($id == '') {
			$sql = 'call InsertProductPlant(:vproductid,:vslocid,:vuom1,:vuom2,:vuom3,:vuom4,:vqty,:vqty2,:vqty3,:vqty4,:visautolot,:vsled,:vmaterialgroupid,:vaddressbookid,
				:vissource,:vdatauser)';
			$command=$connection->createCommand($sql);
		} else {
			$sql = 'call UpdateProductPlant(:vid,:vproductid,:vslocid,:vuom1,:vuom2,:vuom3,:vuom4,:vqty,:vqty2,:vqty3,:vqty4,:visautolot,:vsled,:vmaterialgroupid,:vaddressbookid,
				:vissource,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);	
		}
		$command->bindvalue(':vproductid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vslocid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vuom1',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vuom2',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vuom3',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vuom4',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vqty',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vqty2',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vqty3',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vqty4',$arraydata[10],PDO::PARAM_STR);
		$command->bindvalue(':visautolot',$arraydata[11],PDO::PARAM_STR);
		$command->bindvalue(':vsled',$arraydata[12],PDO::PARAM_STR);
		$command->bindvalue(':vmaterialgroupid',$arraydata[13],PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid',$arraydata[14],PDO::PARAM_STR);
		$command->bindvalue(':vissource',$arraydata[15],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-productplant"]["name"]);
		if (move_uploaded_file($_FILES["file-productplant"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$fullname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$addressbookid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '".$fullname."'")->queryScalar();
					$productcode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$productname = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$sql = "select productid from product where productname = '".addslashes($productname)."'";
					$productid = Yii::app()->db->createCommand($sql)->queryScalar();
					$sloccode = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
					$qty = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$uomcode = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
					$qty2 = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$uom2code = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$uom2id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uom2code."'")->queryScalar();
					$qty3 = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
					$uom3code = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
					if ($uom3code != '') {
					$uom3id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uom3code."'")->queryScalar();
					} else {
						$uom3id = '';
					}
					$qty4 = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
					$uom4code = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
					if ($uom4code != '') {
					$uom4id = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uom4code."'")->queryScalar();
					} else {
						$uom4id = '';
					}
					$isautolot = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
					$sled = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
					$materialgroupcode = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
					$materialgroupid = Yii::app()->db->createCommand("select materialgroupid from materialgroup where materialgroupcode = '".$materialgroupcode."'")->queryScalar();
					$issource = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
					$this->ModifyData($connection,array($id,$productid,$slocid,$uomid,$uom2id,$uom3id,$uom4id,$qty,
						$qty2,$qty3,$qty4,$isautolot,$sled,$materialgroupid,$addressbookid,$issource));
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
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['productplantid'])?$_POST['productplantid']:''),$_POST['productid'],$_POST['slocid'],
					$_POST['uom1'],$_POST['uom2'],$_POST['uom3'],$_POST['uom4'],
					$_POST['qty'],$_POST['qty2'],$_POST['qty3'],$_POST['qty4'],
				$_POST['isautolot'],$_POST['sled'],$_POST['materialgroupid'],$_POST['addressbookid'],$_POST['issource']));
			$transaction->commit();			
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
	public function actionPurge() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeproductplant(:vid,:vdatauser)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vdatauser',GetUserPC(),PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
		}
		else {
			GetMessage(true,'chooseone');
		}
	}
	public function actionDownPDF() {
	  parent::actionDownload();
	  $sql = "select a.productplantid,b.productname,c.sloccode,a.qty,a.qty2,a.qty3,a.qty4,
					e.uomcode as uom1code,f.uomcode as uom2code,g.uomcode as uom3code,h.uomcode as uom4code,isautolot,sled,
					i.materialgroupcode,j.fullname
				from productplant a 
				left join product b on b.productid = a.productid 
				left join sloc c on c.slocid = a.slocid 
				left join unitofmeasure e on e.unitofmeasureid = a.uom1 
				left join unitofmeasure f on f.unitofmeasureid = a.uom2
				left join unitofmeasure g on g.unitofmeasureid = a.uom3
				left join unitofmeasure h on h.unitofmeasureid = a.uom4
				left join materialgroup i on i.materialgroupid = a.materialgroupid
				left join addressbook j on j.addressbookid = a.addressbookid
				";
		$productplantid = GetSearchText(array('GET'),'productplantid');
		$productid = GetSearchText(array('GET'),'productid');
		$productcode = GetSearchText(array('GET'),'productcode');
		$productname = GetSearchText(array('GET'),'productname');
		$materialtypecode = GetSearchText(array('GET'),'materialtypecode');
		$sloc = GetSearchText(array('GET'),'sloc');
		$uom1 = GetSearchText(array('GET'),'uom1');
		$uom2 = GetSearchText(array('GET'),'uom2');
		$uom3 = GetSearchText(array('GET'),'uom3');
		$uom4 = GetSearchText(array('GET'),'uom4');
		$sled = GetSearchText(array('GET'),'sled');
		$addressbookid = GetSearchText(array('GET'),'addressbookid');
		$addressbook = GetSearchText(array('GET'),'addressbook');
		$materialgroup = GetSearchText(array('GET'),'materialgroup');
		$sql .= " where coalesce(a.productplantid,'') like '".$productplantid."' 
			and coalesce(b.productname,'') like '".$productname."'
			and coalesce(c.sloccode,'') like '".$sloc."'
			and coalesce(e.uomcode,'') like '".$uom1."'
			and coalesce(f.uomcode,'') like '".$uom2."'
			and coalesce(g.uomcode,'') like '".$uom3."'
			and coalesce(h.uomcode,'') like '".$uom4."'
			and coalesce(j.fullname,'') like '".$addressbook."'
			and coalesce(i.materialgroupcode,'') like '".$materialgroup."'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.productplantid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('productplant');
		$this->pdf->AddPage('L',array(400,600));
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L','L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('product'),
                GetCatalog('sloc'),
                GetCatalog('qty1'),
                GetCatalog('uom1'),
                GetCatalog('qty2'),
                GetCatalog('uom2'),
                GetCatalog('qty3'),
                GetCatalog('uom3'),
                GetCatalog('qty4'),
                GetCatalog('uom4'),
                GetCatalog('materialgroup'),
                GetCatalog('addressbook'),
                GetCatalog('isautolot'),
                GetCatalog('sled'));
		$this->pdf->setwidths(array(120,40,30,20,20,30,30,30,30,30,30,80,20,60,40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','L','L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['productname'],$row1['sloccode'],$row1['qty'],
			$row1['uom1code'],$row1['qty2'],$row1['uom2code'],$row1['qty3'],$row1['uom3code'],$row1['qty4'],$row1['uom4code'],
			$row1['materialgroupcode'],$row1['fullname'],$row1['isautolot'],$row1['sled']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		parent::actionDownxls();
		 $sql = "select j.fullname,a.productplantid,b.productname,c.sloccode,a.qty,a.qty2,a.qty3,a.qty4,
					e.uomcode as uom1code,f.uomcode as uom2code,g.uomcode as uom3code,h.uomcode as uom4code,isautolot,sled,
					i.materialgroupcode,b.productcode,a.issource
				from productplant a 
				left join product b on b.productid = a.productid 
				left join sloc c on c.slocid = a.slocid 
				left join unitofmeasure e on e.unitofmeasureid = a.uom1 
				left join unitofmeasure f on f.unitofmeasureid = a.uom2
				left join unitofmeasure g on g.unitofmeasureid = a.uom3
				left join unitofmeasure h on h.unitofmeasureid = a.uom4 
				left join materialgroup i on i.materialgroupid = a.materialgroupid 
				left join addressbook j on j.addressbookid = a.addressbookid
				";
		$productplantid = GetSearchText(array('GET'),'productplantid');
		$productid = GetSearchText(array('GET'),'productid');
		$productcode = GetSearchText(array('GET'),'productcode');
		$productname = GetSearchText(array('GET'),'productname');
		$materialtypecode = GetSearchText(array('GET'),'materialtypecode');
		$sloc = GetSearchText(array('GET'),'sloc');
		$uom1 = GetSearchText(array('GET'),'uom1');
		$uom2 = GetSearchText(array('GET'),'uom2');
		$uom3 = GetSearchText(array('GET'),'uom3');
		$uom4 = GetSearchText(array('GET'),'uom4');
		$sled = GetSearchText(array('GET'),'sled');
		$materialgroup = GetSearchText(array('GET'),'materialgroup');
		$sql .= " where coalesce(a.productplantid,'') like '".$productplantid."' 
			and coalesce(b.productname,'') like '".$productname."'
			and coalesce(c.sloccode,'') like '".$sloc."'
			and coalesce(e.uomcode,'') like '".$uom1."'
			and coalesce(f.uomcode,'') like '".$uom2."'
			and coalesce(g.uomcode,'') like '".$uom3."'
			and coalesce(h.uomcode,'') like '".$uom4."'
			and coalesce(i.materialgroupcode,'') like '".$materialgroup."'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.productplantid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		 $this->phpExcel=Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
		$i=1;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,1,GetCatalog('productplantid'))
			->setCellValueByColumnAndRow(1,1,GetCatalog('addressbook'))
			->setCellValueByColumnAndRow(2,1,GetCatalog('productcode'))
			->setCellValueByColumnAndRow(3,1,GetCatalog('productname'))
			->setCellValueByColumnAndRow(4,1,GetCatalog('sloc'))
			->setCellValueByColumnAndRow(5,1,GetCatalog('qty1'))
			->setCellValueByColumnAndRow(6,1,GetCatalog('uom1'))
			->setCellValueByColumnAndRow(7,1,GetCatalog('qty2'))
			->setCellValueByColumnAndRow(8,1,GetCatalog('uom2'))
			->setCellValueByColumnAndRow(9,1,GetCatalog('qty3'))
			->setCellValueByColumnAndRow(10,1,GetCatalog('uom3'))
			->setCellValueByColumnAndRow(11,1,GetCatalog('qty4'))
			->setCellValueByColumnAndRow(12,1,GetCatalog('uom4'))
			->setCellValueByColumnAndRow(13,1,GetCatalog('isautolot'))
			->setCellValueByColumnAndRow(14,1,GetCatalog('sled'))
			->setCellValueByColumnAndRow(15,1,GetCatalog('materialgroup'))
			->setCellValueByColumnAndRow(16,1,GetCatalog('issource'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['productplantid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['fullname'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['productcode'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['productname'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['sloccode'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['qty'])
				->setCellValueByColumnAndRow(6, $i+1, $row1['uom1code'])
				->setCellValueByColumnAndRow(7, $i+1, $row1['qty2'])
				->setCellValueByColumnAndRow(8, $i+1, $row1['uom2code'])
				->setCellValueByColumnAndRow(9, $i+1, $row1['qty3'])
				->setCellValueByColumnAndRow(10, $i+1, $row1['uom3code'])
				->setCellValueByColumnAndRow(11, $i+1, $row1['qty4'])
				->setCellValueByColumnAndRow(12, $i+1, $row1['uom4code'])
				->setCellValueByColumnAndRow(13, $i+1, $row1['isautolot'])
				->setCellValueByColumnAndRow(14, $i+1, $row1['sled'])
				->setCellValueByColumnAndRow(15, $i+1, $row1['materialgroupcode'])
				->setCellValueByColumnAndRow(16, $i+1, $row1['issource']);
			$i+=1;
		}
		$this->getfooterXls($this->phpExcel);
	}
}