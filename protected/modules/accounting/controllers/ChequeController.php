<?php
class ChequeController extends Controller {
	public $menuname = 'cheque';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$chequeid = GetSearchText(array('POST','Q'),'chequeid');
		$plantcode = GetSearchText(array('POST','Q'),'plantcode');
		$currencyname = GetSearchText(array('POST','Q'),'currencyname');
		$addressbook = GetSearchText(array('POST','Q'),'addressbook');
		$bilyetgirono = GetSearchText(array('POST','Q'),'bilyetgirono');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','chequeid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('cheque t')
				->leftjoin('plant a','a.plantid = t.plantid')
				->leftjoin('company b','b.companyid = a.companyid')
				->leftjoin('currency c','c.currencyid = t.currencyid')
				->leftjoin('addressbook d','d.addressbookid = t.addressbookid')
				->where("(coalesce(t.chequeid,'') like :chequeid) 
					and (coalesce(a.plantcode,'') like :plantcode) 
					and (coalesce(c.currencyname,'') like :currencyname)
					and (coalesce(d.fullname,'') like :addressbook)
					",
				array(':chequeid'=>$chequeid,
					':plantcode'=>$plantcode,
					':currencyname'=>$currencyname,
					':addressbook'=>$addressbook
					))
				->queryScalar();
		} else
		if (!isset($_GET['trxcom'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('cheque t')
				->leftjoin('plant a','a.plantid = t.plantid')
				->leftjoin('company b','b.companyid = a.companyid')
				->leftjoin('currency c','c.currencyid = t.currencyid')
				->leftjoin('addressboo d','d.addressbookid = t.addressbookid')
				->where("
				((coalesce(t.chequeid,'') like :chequeid) 
				or (coalesce(a.plantcode,'') like :plantcode)  
				or (coalesce(c.currencyname,'') like :currencyname) 
				or (coalesce(d.fullname,'') like :addressbook))
  			and t.plantid in (".getUserObjectValues('plant').")
        and t.plantid = ".$plantid."
  					", 	
						array(':chequeid'=>$chequeid,
					':plantcode'=>$plantcode,
					':currencyname'=>$currencyname,
					':addressbook'=>$addressbook
  				))->queryScalar();
    }
		else {			
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('cheque t')
				->leftjoin('plant a','a.plantid = t.plantid')
				->leftjoin('company b','b.companyid = a.companyid')
				->leftjoin('currency c','c.currencyid = t.currencyid')
				->leftjoin('addressbook d','d.addressbookid = t.addressbookid')
				->where("(coalesce(t.chequeid,'') like :chequeid) 
					or (coalesce(a.plantcode,'') like :plantcode) 
					or (coalesce(c.currencyname,'') like :currencyname)
					or (coalesce(d.fullname,'') like :addressbook)
				",
				array(':chequeid'=>$chequeid,
					':plantcode'=>$plantcode,
					':currencyname'=>$currencyname,
					':addressbook'=>$addressbook
					))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.plantcode,b.companyname,c.currencyname,d.fullname')			
				->from('cheque t')
				->leftjoin('plant a','a.plantid = t.plantid')
				->leftjoin('company b','b.companyid = a.companyid')
				->leftjoin('currency c','c.currencyid = t.currencyid')
				->leftjoin('addressbook d','d.addressbookid = t.addressbookid')
				->where("(coalesce(t.chequeid,'') like :chequeid) 
					and (coalesce(a.plantcode,'') like :plantcode) 
					and (coalesce(c.currencyname,'') like :currencyname)
					and (coalesce(d.fullname,'') like :addressbook)
					",
				array(':chequeid'=>$chequeid,
					':plantcode'=>$plantcode,
					':currencyname'=>$currencyname,
					':addressbook'=>$addressbook
					))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		} else
		if (!isset($_GET['trxcom'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.plantcode,b.companyname,c.currencyname,d.fullname')			
				->from('cheque t')
				->leftjoin('plant a','a.plantid = t.plantid')
				->leftjoin('company b','b.companyid = a.companyid')
				->leftjoin('currency c','c.currencyid = t.currencyid')
				->leftjoin('addressbook d','d.addressbookid = t.addressbookid')
				->where("
				((coalesce(t.chequeid,'') like :chequeid) 
				or (coalesce(a.plantcode,'') like :plantcode)  
				or (coalesce(c.currencyname,'') like :currencyname) 
				or (coalesce(d.fullname,'') like :addressbook))
  			and t.plantid in (".getUserObjectValues('plant').")
        and t.plantid = ".$plantid."
  					", 	
						array(':chequeid'=>$chequeid,
					':plantcode'=>$plantcode,
					':currencyname'=>$currencyname,
					':addressbook'=>$addressbook
					))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.plantcode,b.companyname,c.currencyname,d.fullname')			
				->from('cheque t')
				->leftjoin('plant a','a.plantid = t.plantid')
				->leftjoin('company b','b.companyid = a.companyid')
				->leftjoin('currency c','c.currencyid = t.currencyid')
				->leftjoin('addressbook d','d.addressbookid = t.addressbookid')
				->where("(coalesce(t.chequeid,'') like :chequeid) 
					or (coalesce(a.plantcode,'') like :plantcode) 
					or (coalesce(c.currencyname,'') like :currencyname)
					or (coalesce(d.fullname,'') like :addressbook)
				",
				array(':chequeid'=>$chequeid,
					':plantcode'=>$plantcode,
					':currencyname'=>$currencyname,
					':addressbook'=>$addressbook
					))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
			'chequeid'=>$data['chequeid'],
			'plantid'=>$data['plantid'],
			'plantcode'=>$data['plantcode'],
			'companyname'=>$data['companyname'],
			'isin'=>$data['isin'],
			'docdate'=>date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
			'bilyetgirono'=>$data['bilyetgirono'],
			'bankname'=>$data['bankname'],
			'bilyetgirovalue'=>Yii::app()->format->formatnumber($data['bilyetgirovalue']),
			'currencyid'=>$data['currencyid'],
			'currencyname'=>$data['currencyname'],
			'chequedate'=>date(Yii::app()->params['dateviewfromdb'], strtotime($data['chequedate'])),
			'expiredate'=>date(Yii::app()->params['dateviewfromdb'], strtotime($data['expiredate'])),
			'addressbookid'=>$data['addressbookid'],
			'fullname'=>$data['fullname'],
			'iscair'=>$data['iscair'],
			'cairtolakdate'=>date(Yii::app()->params['dateviewfromdb'], strtotime($data['cairtolakdate'])),
			'description'=>$data['description'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {		
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertcheque(:vplantid,:visin,:vbilyetgirono,:vbankname,:vbilyetgirovalue,:vcurrencyid,:vchequedate,:vexpiredate,:vaddressbookid,:viscair,:vcairtolakdate,
				:vdescription,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatecheque(:vid,:vplantid,:visin,:vbilyetgirono,:vbankname,:vbilyetgirovalue,:vcurrencyid,:vchequedate,:vexpiredate,:vaddressbookid,:viscair,:vcairtolakdate,
				:vdescription,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vplantid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':visin',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vbilyetgirono',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vbankname',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vbilyetgirovalue',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vchequedate',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vexpiredate',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':viscair',$arraydata[10],PDO::PARAM_STR);
		$command->bindvalue(':vcairtolakdate',$arraydata[11],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[12],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-cheque"]["name"]);
		if (move_uploaded_file($_FILES["file-cheque"]["tmp_name"], $target_file)) {
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
					$chequecode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$chequename = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$this->ModifyData($connection,array($id,$chequecode,$chequename,$recordstatus));
				}
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    }
	}
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['chequeid'])?$_POST['chequeid']:''),
				$_POST['plantid'],
				$_POST['isin'],
				$_POST['bilyetgirono'],
				$_POST['bankname'],
				$_POST['bilyetgirovalue'],
				$_POST['currencyid'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['chequedate'])),
				date(Yii::app()->params['datetodb'], strtotime($_POST['expiredate'])),
				$_POST['addressbookid'],
				$_POST['iscair'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['cairtolakdate'])),
				$_POST['description'],
			));
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
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgecheque(:vid,:vdatauser)';
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
			GetMessage(true, getcatalog('chooseone'));
		}
	}
	public function actionDownPDF() {
	  parent::actionDownload();  
		$sql = "select t.*,a.plantcode,c.currencyname,d.fullname,c.symbol 
				from cheque t 
				left join plant a on a.plantid = t.plantid 
				left join currency c on c.currencyid = t.currencyid 
				left join addressbook d on d.addressbookid = t.addressbookid 
				";
		$chequeid = GetSearchText(array('GET'),'chequeid');
		$plantcode = GetSearchText(array('GET'),'plantcode');
		$currencyname = GetSearchText(array('GET'),'currencyname');
		$addressbook = GetSearchText(array('GET'),'addressbook');
		$bilyetgirono = GetSearchText(array('GET'),'bilyetgirono');
		$sql .= " where coalesce(t.chequeid,'') like '".$chequeid."' 
			and coalesce(t.bilyetgirono,'') like '".$bilyetgirono."'
			and coalesce(a.plantcode,'') like '".$plantcode."'
			and coalesce(c.currencyname,'') like '".$currencyname."'
			and coalesce(d.fullname,'') like '".$addressbook."'
			";
		if ($_GET['id'] !== '') {
			$sql = $sql . " and t.chequeid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by bilyetgirono asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		$this->pdf->title=GetCatalog('cheque');
		$this->pdf->AddPage('L',array(200,500));
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('chequeid'),
			GetCatalog('plantcode'),
			GetCatalog('isin'),
			GetCatalog('docdate'),
			GetCatalog('bilyetgirono'),
			GetCatalog('bankname'),
			GetCatalog('bilyetgirovalue'),
			GetCatalog('chequedate'),
			GetCatalog('expiredate'),
			GetCatalog('addressbook'),
			GetCatalog('iscair'),
			GetCatalog('cairtolakdate'),
			GetCatalog('description')
			);
		$this->pdf->setwidths(array(15,50,20,30,40,30,50,30,30,30,30,30,30,30,30,30,30,30,30,30));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['chequeid'],
			$row1['plantcode'],
			$row1['isin'],
			date(Yii::app()->params['dateviewfromdb'], strtotime($row1['docdate'])),
			$row1['bilyetgirono'],
			$row1['bankname'],
			Yii::app()->format->formatCurrency($row1['bilyetgirovalue'],$row1['symbol']),
			date(Yii::app()->params['dateviewfromdb'], strtotime($row1['chequedate'])),
			date(Yii::app()->params['dateviewfromdb'], strtotime($row1['expiredate'])),
			$row1['fullname'],
			$row1['iscair'],
			date(Yii::app()->params['dateviewfromdb'], strtotime($row1['cairtolakdate'])),
			$row1['description']
			));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='cheque';
		parent::actionDownxls();
		$sql = "select t.*,a.plantcode,c.currencyname,d.fullname 
				from cheque t 
				left join plant a on a.plantid = t.plantid 
				left join currency c on c.currencyid = t.currencyid 
				left join addressbook d on d.addressbookid = t.addressbookid 
				";
		$chequeid = GetSearchText(array('GET'),'chequeid');
		$plantcode = GetSearchText(array('GET'),'plantcode');
		$currencyname = GetSearchText(array('GET'),'currencyname');
		$addressbook = GetSearchText(array('GET'),'addressbook');
		$bilyetgirono = GetSearchText(array('GET'),'bilyetgirono');
		$sql .= " where coalesce(t.chequeid,'') like '".$chequeid."' 
			and coalesce(t.bilyetgirono,'') like '".$bilyetgirono."'
			and coalesce(a.plantcode,'') like '".$plantcode."'
			and coalesce(c.currencyname,'') like '".$currencyname."'
			and coalesce(d.fullname,'') like '".$addressbook."'
			and coalesce(t.bilyetgirono,'') like '".$bilyetgirono."'
			";
		if ($_GET['id'] !== '') {
			$sql = $sql . " and t.chequeid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by bilyetgirono asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('chequeid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('plantcode'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('isin'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('docdate'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('bilyetgirono'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('bankname'))
			->setCellValueByColumnAndRow(6,2,GetCatalog('bilyetgirovalue'))
			->setCellValueByColumnAndRow(7,2,GetCatalog('currencyname'))
			->setCellValueByColumnAndRow(8,2,GetCatalog('chequedate'))
			->setCellValueByColumnAndRow(9,2,GetCatalog('expiredate'))
			->setCellValueByColumnAndRow(10,2,GetCatalog('addressbook'))
			->setCellValueByColumnAndRow(11,2,GetCatalog('iscair'))
			->setCellValueByColumnAndRow(12,2,GetCatalog('cairtolakdate'))
			->setCellValueByColumnAndRow(13,2,GetCatalog('description'))
			;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['chequeid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['plantcode'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['isin'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['docdate'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['bilyetgirono'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['bankname'])
				->setCellValueByColumnAndRow(6, $i+1, $row1['bilyetgirovalue'])
				->setCellValueByColumnAndRow(7, $i+1, $row1['currencyname'])
				->setCellValueByColumnAndRow(8, $i+1, $row1['chequedate'])
				->setCellValueByColumnAndRow(9, $i+1, $row1['expiredate'])
				->setCellValueByColumnAndRow(10, $i+1, $row1['fullname'])
				->setCellValueByColumnAndRow(11, $i+1, $row1['iscair'])
				->setCellValueByColumnAndRow(12, $i+1, $row1['cairtolakdate'])
				->setCellValueByColumnAndRow(13, $i+1, $row1['description'])
				;
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}