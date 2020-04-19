<?php
class SlocaccountingController extends Controller {
	public $menuname = 'slocaccounting';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$slocaccid = GetSearchText(array('POST','Q'),'slocaccid');
		$sloccode = GetSearchText(array('POST','Q'),'sloccode');
		$materialgroupname = GetSearchText(array('POST','Q'),'materialgroupname');
		$materialgroupcode = GetSearchText(array('POST','Q'),'materialgroupcode');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','slocaccid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('slocaccounting t')
			->leftjoin('sloc p','p.slocid=t.slocid')
			->leftjoin('materialgroup q','q.materialgroupid=t.materialgroupid')
			->leftjoin('account a','a.accountid=t.accpersediaan')
			->leftjoin('account b','b.accountid=t.accreturpembelian')
			->leftjoin('account c','c.accountid=t.accdiscpembelian')
			->leftjoin('account d','d.accountid=t.accbiayapembelian')
			->leftjoin('account e','e.accountid=t.accexpedisipembelian')
			->leftjoin('account f','f.accountid=t.accpenjualan')
			->leftjoin('account g','g.accountid=t.accreturpenjualan')
			->leftjoin('account h','h.accountid=t.accdiscpenjualan')
			->leftjoin('account i','i.accountid=t.accbiayapenjualan')
			->leftjoin('account j','j.accountid=t.accexpedisipenjualan')
			->leftjoin('account k','k.accountid=t.hpp')
			->leftjoin('account l','l.accountid=t.accupahlembur')
			->leftjoin('account m','m.accountid=t.foh')
			->leftjoin('account n','n.accountid=t.accpembelian')
			->where("(coalesce(p.sloccode,'') like :sloccode) 
				and (coalesce(q.description,'') like :materialgroupname)
				and (coalesce(q.materialgroupcode,'') like :materialgroupcode)
				",
				array(':sloccode'=>'%'.$sloccode.'%',
						':materialgroupname'=>'%'.$materialgroupname.'%',
						':materialgroupcode'=>'%'.$materialgroupcode.'%'
						))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,p.sloccode,q.materialgroupcode,a.accountname as accpersediaanname,b.accountname as accreturpembelianname,
				c.accountname as accdiscpembelianname,d.accountname as accbiayapembelianname, e.accountname as accexpedisipembelianname,
				f.accountname as accpenjualanname, g.accountname as accreturpenjualanname, h.accountname as accdiscpenjualanname,
				i.accountname as accbiayapenjualanname, j.accountname as accexpedisipenjualanname, k.accountname as acchppname,
				l.accountname as accupahlemburname, m.accountname as accfohname,n.accountname as accpembelianname')
			->from('slocaccounting t')
			->leftjoin('sloc p','p.slocid=t.slocid')
			->leftjoin('materialgroup q','q.materialgroupid=t.materialgroupid')
			->leftjoin('account a','a.accountid=t.accpersediaan')
			->leftjoin('account b','b.accountid=t.accreturpembelian')
			->leftjoin('account c','c.accountid=t.accdiscpembelian')
			->leftjoin('account d','d.accountid=t.accbiayapembelian')
			->leftjoin('account e','e.accountid=t.accexpedisipembelian')
			->leftjoin('account f','f.accountid=t.accpenjualan')
			->leftjoin('account g','g.accountid=t.accreturpenjualan')
			->leftjoin('account h','h.accountid=t.accdiscpenjualan')
			->leftjoin('account i','i.accountid=t.accbiayapenjualan')
			->leftjoin('account j','j.accountid=t.accexpedisipenjualan')
			->leftjoin('account k','k.accountid=t.hpp')
			->leftjoin('account l','l.accountid=t.accupahlembur')
			->leftjoin('account m','m.accountid=t.foh')
			->leftjoin('account n','n.accountid=t.accpembelian')
			->where("(coalesce(p.sloccode,'') like :sloccode) 
				and (coalesce(q.description,'') like :materialgroupname)
				and (coalesce(q.materialgroupcode,'') like :materialgroupcode)
				",
								array(':sloccode'=>'%'.$sloccode.'%',
										':materialgroupname'=>'%'.$materialgroupname.'%',
										':materialgroupcode'=>'%'.$materialgroupcode.'%'
										))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		
		foreach($cmd as $data) {	
			$row[] = array(
				'slocaccid'=>$data['slocaccid'],
				'slocid'=>$data['slocid'],
				'sloccode'=>$data['sloccode'],
				'materialgroupid'=>$data['materialgroupid'],
				'description'=>$data['materialgroupcode'],
				'accpembelian'=>$data['accpembelian'],
				'accpembelianname'=>$data['accpembelianname'],
				'accpersediaan'=>$data['accpersediaan'],
				'accpersediaanname'=>$data['accpersediaanname'],
				'accreturpembelian'=>$data['accreturpembelian'],
				'accreturpembelianname'=>$data['accreturpembelianname'],
				'accdiscpembelian'=>$data['accdiscpembelian'],
				'accdiscpembelianname'=>$data['accdiscpembelianname'],
				'accbiayapembelian'=>$data['accbiayapembelian'],
				'accbiayapembelianname'=>$data['accbiayapembelianname'],
				'accexpedisipembelian'=>$data['accexpedisipembelian'],
				'accexpedisipembelianname'=>$data['accexpedisipembelianname'],
				'accpenjualan'=>$data['accpenjualan'],
				'accpenjualanname'=>$data['accpenjualanname'],
				'accreturpenjualan'=>$data['accreturpenjualan'],
				'accreturpenjualanname'=>$data['accreturpenjualanname'],
				'accdiscpenjualan'=>$data['accdiscpenjualan'],
				'accdiscpenjualanname'=>$data['accdiscpenjualanname'],
				'accbiayapenjualan'=>$data['accbiayapenjualan'],
				'accbiayapenjualanname'=>$data['accbiayapenjualanname'],
				'accexpedisipenjualan'=>$data['accexpedisipenjualan'],
				'accexpedisipenjualanname'=>$data['accexpedisipenjualanname'],
				'hpp'=>$data['hpp'],
				'acchppname'=>$data['acchppname'],
				'accupahlembur'=>$data['accupahlembur'],
				'accupahlemburname'=>$data['accupahlemburname'],
				'foh'=>$data['foh'],
				'accfohname'=>$data['accfohname'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertslocaccounting(:vslocid,:vmaterialgroupid,
				:vaccpembelian,:vaccpersediaan,:vaccreturpembelian,:vaccdiscpembelian,:vaccbiayapembelian,:vaccexpedisipembelian,
				:vaccpenjualan,:vaccreturpenjualan,:vaccdiscpenjualan,:vaccbiayapenjualan,:vaccexpedisipenjualan,
				:vhpp,:vaccupahlembur,:vfoh,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateslocaccounting(:vid,:vslocid,:vmaterialgroupid,
				:vaccpembelian,:vaccpersediaan,:vaccreturpembelian,:vaccdiscpembelian,:vaccbiayapembelian,:vaccexpedisipembelian,
				:vaccpenjualan,:vaccreturpenjualan,:vaccdiscpenjualan,:vaccbiayapenjualan,:vaccexpedisipenjualan,
				:vhpp,:vaccupahlembur,:vfoh,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vslocid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vmaterialgroupid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vaccpembelian',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vaccpersediaan',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vaccreturpembelian',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vaccdiscpembelian',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vaccbiayapembelian',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vaccexpedisipembelian',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vaccpenjualan',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vaccreturpenjualan',$arraydata[10],PDO::PARAM_STR);
		$command->bindvalue(':vaccdiscpenjualan',$arraydata[11],PDO::PARAM_STR);
		$command->bindvalue(':vaccbiayapenjualan',$arraydata[12],PDO::PARAM_STR);
		$command->bindvalue(':vaccexpedisipenjualan',$arraydata[13],PDO::PARAM_STR);
		$command->bindvalue(':vhpp',$arraydata[14],PDO::PARAM_STR);
		$command->bindvalue(':vaccupahlembur',$arraydata[15],PDO::PARAM_STR);
		$command->bindvalue(':vfoh',$arraydata[16],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-slocaccounting"]["name"]);
		if (move_uploaded_file($_FILES["file-slocaccounting"]["tmp_name"], $target_file)) {
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
					$sloccode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
					$materialgroupcode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$materialgroupid = Yii::app()->db->createCommand("select materialgroupid from materialgroup where materialgroupcode = '".$materialgroupcode."'")->queryScalar();
					$pembelian = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$pembelianid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$pembelian."'")->queryScalar();
					$persediaan = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$persediaanid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$persediaan."'")->queryScalar();
					$returpembelian = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$returpembelianid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$returpembelian."'")->queryScalar();
					$discpembelian = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$discpembelianid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$discpembelian."'")->queryScalar();
					$biayapembelian = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$biayapembelianid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$biayapembelian."'")->queryScalar();
					$expedisipembelian = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$expedisipembelianid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$expedisipembelian."'")->queryScalar();
					$penjualan = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
					$penjualanid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$penjualan."'")->queryScalar();
					$returpenjualan = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
					$returpenjualanid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$returpenjualan."'")->queryScalar();
					$discpenjualan = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
					$discpenjualanid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$discpenjualan."'")->queryScalar();
					$biayapenjualan = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
					$biayapenjualanid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$biayapenjualan."'")->queryScalar();
					$expedisipenjualan = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
					$expedisipenjualanid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$expedisipenjualan."'")->queryScalar();
					$hpp = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
					$hppid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$hpp."'")->queryScalar();
					$upahlembur = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
					$upahlemburid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$upahlembur."'")->queryScalar();
					$foh = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
					$fohid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$foh."'")->queryScalar();
					$this->ModifyData($connection,array($id,$slocid,$materialgroupid,$pembelianid,$persediaanid,$returpembelianid,$discpembelianid,$biayapembelianid,$expedisipembelianid,
					$penjualanid,$returpenjualanid,$discpenjualanid,$biayapenjualanid,$expedisipenjualanid,$hppid,$upahlemburid,$fohid));
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
			$this->ModifyData($connection,array((isset($_POST['slocaccid'])?$_POST['slocaccid']:''),$_POST['slocid'],$_POST['materialgroupid'],
				$_POST['accpembelian'],$_POST['accpersediaan'],$_POST['accreturpembelian'],$_POST['accdiscpembelian'],$_POST['accbiayapembelian'],$_POST['accexpedisipembelian'],
				$_POST['accpenjualan'],$_POST['accreturpenjualan'],$_POST['accdiscpenjualan'],$_POST['accbiayapenjualan'],$_POST['accexpedisipenjualan'],
				$_POST['hpp'],$_POST['accupahlembur'],$_POST['foh']));
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
				$sql = 'call Purgeslocaccounting(:vid,:vdatauser)';
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
			GetMessage(true,getcatalog('chooseone'));
		}
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['sloccode'] = GetSearchText(array('GET'),'sloccode');
		$this->dataprint['materialgroupdesc'] = GetSearchText(array('GET'),'materialgroupdesc');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'slocaccid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlesloccode'] = GetCatalog('sloccode');
		$this->dataprint['titlematerialgroupdesc'] = GetCatalog('materialgroup');
		$this->dataprint['titleaccpembelian'] = GetCatalog('accpembelian');
		$this->dataprint['titleaccpersediaan'] = GetCatalog('accpersediaan');
		$this->dataprint['titleaccreturpembelian'] = GetCatalog('accreturpembelian');
		$this->dataprint['titleaccdiscpembelian'] = GetCatalog('accdiscpembelian');
		$this->dataprint['titleaccbiayapembelian'] = GetCatalog('accbiayapembelian');
		$this->dataprint['titleaccexpedisipembelian'] = GetCatalog('accexpedisipembelian');
		$this->dataprint['titleaccpenjualan'] = GetCatalog('accpenjualan');
		$this->dataprint['titleaccreturpenjualan'] = GetCatalog('accreturpenjualan');
		$this->dataprint['titleaccdiscpenjualan'] = GetCatalog('accdiscpenjualan');
		$this->dataprint['titleaccbiayapenjualan'] = GetCatalog('accbiayapenjualan');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}