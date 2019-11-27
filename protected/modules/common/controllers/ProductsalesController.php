<?php
class ProductsalesController extends Controller {
	public $menuname = 'productsales';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actiongetprice() {
		$product = null;
		$cmd='';
		if(isset($_POST['productid']) && isset($_POST['addressbookid']) && isset($_POST['unitofmeasureid'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.pricecategoryid')
				->from('addressbook t')
				->where('t.addressbookid = '.$_POST['addressbookid'])
				->limit(1)
				->queryRow();
			$cmd = Yii::app()->db->createCommand()
				->select('t.currencyvalue,t.currencyid,a.currencyname')
				->from('productsales t')
				->join('currency a','a.currencyid = t.currencyid')
				->where('productid = '.$_POST['productid'].' and pricecategoryid = '.$cmd['pricecategoryid'])
				->limit(1)
				->queryRow();
		}
		if (Yii::app()->request->isAjaxRequest) {
			echo CJSON::encode(array(
				'status'=>'success',
				'currencyvalue'=> Yii::app()->format->formatNumber($cmd['currencyvalue']),
				'currencyid'=> $cmd['currencyid'],
				'currencyname'=>$cmd['currencyname'],
				'currencyrate'=>1
				));
			Yii::app()->end();
		}
	}
	public function search() {
		header("Content-Type: application/json");
		$productsalesid = GetSearchText(array('POST','Q'),'productsalesid');
		$productname = GetSearchText(array('POST','Q'),'productname');
		$currencyname = GetSearchText(array('POST','Q'),'currencyname');
		$currencyvalue = GetSearchText(array('POST','Q'),'currencyvalue');
		$pricecategory = GetSearchText(array('POST','Q'),'pricecategory');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','productsalesid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('productsales t')
			->leftjoin('product p','p.productid=t.productid')
			->leftjoin('currency q','q.currencyid=t.currencyid')
			->leftjoin('pricecategory r','r.pricecategoryid=t.pricecategoryid')
			->leftjoin('unitofmeasure s','s.unitofmeasureid=t.uomid')
			->where('
				(p.productname like :productname) and 
				(q.currencyname like :currencyname) and 
				(r.categoryname like :pricecategory)',
					array(':productname'=>$productname,
							':currencyname'=>$currencyname,
							':pricecategory'=>$pricecategory
							))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select()	
			->from('productsales t')
			->leftjoin('product p','p.productid=t.productid')
			->leftjoin('currency q','q.currencyid=t.currencyid')
			->leftjoin('pricecategory r','r.pricecategoryid=t.pricecategoryid')
			->leftjoin('unitofmeasure s','s.unitofmeasureid=t.uomid')
			->where('
				(p.productname like :productname) and 
				(q.currencyname like :currencyname) and 
				(r.categoryname like :pricecategory)',
					array(':productname'=>$productname,
							':currencyname'=>$currencyname,
							':pricecategory'=>$pricecategory))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'productsalesid'=>$data['productsalesid'],
				'productid'=>$data['productid'],
				'productname'=>$data['productname'],
				'productcode'=>$data['productcode'],
				'currencyid'=>$data['currencyid'],
				'currencyname'=>$data['currencyname'],
				'currencyvalue'=>Yii::app()->format->formatNumber($data['currencyvalue']),
				'pricecategoryid'=>$data['pricecategoryid'],
				'categoryname'=>$data['categoryname'],
				'uomid'=>$data['uomid'],
				'uomcode'=>$data['uomcode'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionGenerateData() {
		$cmd = 0;
		if(isset($_POST['productid']) && isset($_POST['customerid'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.currencyid,t.currencyvalue')	
				->from('productsales t')
				->join('addressbook a','a.pricecategoryid = t.pricecategoryid')
				->where('productid = :productid and a.addressbookid = :addressbookid',
					array(':productid'=>$_POST['productid'],':addressbookid'=>$_POST['customerid']))
				->queryRow();			
		}
		if (Yii::app()->request->isAjaxRequest) {
			echo CJSON::encode(array(
				'status'=>'success',
				'price'=> Yii::app()->format->formatNumber($cmd['currencyvalue']),
				'currencyid'=>$cmd['currencyid'],
				'currencyrate'=>1
				));
			Yii::app()->end();
		}
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertproductsales(:vproductid,:vcurrencyid,:vcurrencyvalue,:vpricecategoryid,:vuomid,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateproductsales(:vid,:vproductid,:vcurrencyid,:vcurrencyvalue,:vpricecategoryid,:vuomid,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vproductid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyvalue',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vpricecategoryid',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vuomid',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-roductsales"]["name"]);
		if (move_uploaded_file($_FILES["file-productsales"]["tmp_name"], $target_file)) {
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
					$productcode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$productid = Yii::app()->db->createCommand("select productid from product where productcode = '".$productcode."'")->queryScalar();
					$productname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$productid = Yii::app()->db->createCommand("select productid from product where productname = '".$productname."'")->queryScalar();
					$currencyname = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$currencyid = Yii::app()->db->createCommand("select currencyid from currency where currencyname = '".$currencyname."'")->queryScalar();
					$currencyvalue = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$pricecategoryname = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$pricecategoryid = Yii::app()->db->createCommand("select pricecategoryid from pricecategory where categoryname = '".$categoryname."'")->queryScalar();
					$uomcode = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
					$this->ModifyData($connection,array($id,$productid,$curencyid,$currencyvalue,$pricecategoryid,$uomid));
				}
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,'Line: '.$row.' '.implode(" ",$e->errorInfo));
			}
    }
	}
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['productsalesid'])?$_POST['productsalesid']:''),$_POST['productid'],$_POST['currencyid'],$_POST['currencyvalue'],
				$_POST['pricecategoryid'],$_POST['uomid']));
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
				$sql = 'call Purgeproductsales(:vid,:vdatauser)';
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
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['titleid'] = GetCatalog('productsalesid');
		$this->dataprint['titleproductcode'] = GetCatalog('productcode');
		$this->dataprint['titleproductname'] = GetCatalog('productname');
		$this->dataprint['titlecurrencyname'] = GetCatalog('currencyname');
		$this->dataprint['titlecurrencyvalue'] = GetCatalog('currencyvalue');
		$this->dataprint['titlepricecategory'] = GetCatalog('pricecategory');
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['productcode'] = GetSearchText(array('GET'),'productcode');
    $this->dataprint['productname'] = GetSearchText(array('GET'),'productname');
    $this->dataprint['currencyname'] = GetSearchText(array('GET'),'currencyname');
    $this->dataprint['categoryname'] = GetSearchText(array('GET'),'pricecategory');
  }
}
