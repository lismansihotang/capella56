<?php
class bonmealController extends Controller {
	public $menuname = 'bonmeal';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexdetail() {
		if(isset($_GET['grid']))
			echo $this->actionSearchdetail();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header('Content-Type: application/json');
		$bonmealid 		= GetSearchText(array('POST','GET','Q'),'bonmealid');
		$plantcode     		= GetSearchText(array('POST','GET','Q'),'plantcode');
		$fullname   	= GetSearchText(array('POST','GET','Q'),'fullname');
		$taxcode   	= GetSearchText(array('POST','GET','Q'),'taxcode');
    $bonmealdate  = GetSearchText(array('POST','GET','Q'),'bonmealdate');
    $bonmealno 		= GetSearchText(array('POST','GET','Q'),'bonmealno');
    $mealtypename 		= GetSearchText(array('POST','GET','Q'),'mealtypename');
    $description 			= GetSearchText(array('POST','GET','Q'),'description');
    $page 	= GetSearchText(array('POST','GET'),'page',1,'int');
		$rows 	= GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort 	= GetSearchText(array('POST','GET'),'sort','bonmealid','int');
		$order 	= GetSearchText(array('POST','GET'),'order','desc','int');
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')
			->from('bonmeal t')
			->leftjoin('addressbook a','a.addressbookid = t.addressbookid')
			->leftjoin('plant c','c.plantid = t.plantid')
			->leftjoin('tax d','d.taxid = t.taxid')
			->leftjoin('mealtype e','e.mealtypeid = t.mealtypeid')
			->where("coalesce(bonmealid,'') like :bonmealid 
				and coalesce(bonmealno,'') like :bonmealno 
				and coalesce(a.fullname,'') like :fullname 
				and coalesce(taxcode,'') like :taxcode
				and coalesce(plantcode,'') like :plantcode
				",
					array(
					':bonmealid'=>'%'.$bonmealid.'%',
					':bonmealno'=>'%'.$bonmealno.'%',
					':fullname'=>'%'.$fullname.'%',
					':taxcode'=>'%'.$taxcode.'%',
					':plantcode'=>'%'.$plantcode.'%'
					))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.fullname,c.plantcode,e.mealtypename,d.taxcode,d.taxvalue')			
			->from('bonmeal t')
			->leftjoin('addressbook a','a.addressbookid = t.addressbookid')
			->leftjoin('plant c','c.plantid = t.plantid')
			->leftjoin('tax d','d.taxid = t.taxid')
			->leftjoin('mealtype e','e.mealtypeid = t.mealtypeid')
			->where("coalesce(bonmealid,'') like :bonmealid 
				and coalesce(bonmealno,'') like :bonmealno 
				and coalesce(a.fullname,'') like :fullname 
				and coalesce(taxcode,'') like :taxcode
				and coalesce(plantcode,'') like :plantcode
				",
					array(
					':bonmealid'=>'%'.$bonmealid.'%',
					':bonmealno'=>'%'.$bonmealno.'%',
					':fullname'=>'%'.$fullname.'%',
					':taxcode'=>'%'.$taxcode.'%',
					':plantcode'=>'%'.$plantcode.'%'
					))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
			'bonmealid'=>$data['bonmealid'],
			'bonmealno'=>$data['bonmealno'],
			'addressbookid'=>$data['addressbookid'],
			'plantid'=>$data['plantid'],
			'plantcode'=>$data['plantcode'],
			'description'=>$data['description'],
			'fullname'=>$data['fullname'],
			'mealtypeid'=>$data['mealtypeid'],
			'mealtypename'=>$data['mealtypename'],
			'taxid'=>$data['taxid'],
			'taxcode'=>$data['taxcode'],
			'bonmealdate'=>date(Yii::app()->params['dateviewfromdb'], strtotime($data['bonmealdate'])),
			'price' => Yii::app()->format->formatNumber($data['price']),
			'taxvalue' => Yii::app()->format->formatNumber($data['taxvalue']),
			'qty'=>Yii::app()->format->formatNumber($data['qty']),
			'jumlah'=>Yii::app()->format->formatNumber($data['jumlah']),
			'recordstatus' => $data['recordstatus'],
			'statusname' => $data['statusname']
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {	
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertbonmeal(:vbonmealdate,:vbonmealno,:vplantid,:vmealtypeid,:vaddressbookid,:vtaxid,:vprice,:vqty,:vdescription,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatebonmeal(:vid,:vbonmealdate,:vbonmealno,:vplantid,:vmealtypeid,:vaddressbookid,:vtaxid,:vprice,:vqty,:vdescription,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vbonmealdate',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vbonmealno',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vplantid',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vmealtypeid',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vtaxid',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vprice',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vqty',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionSave() {
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['bonmealid'])?$_POST['bonmealid']:''),
				date(Yii::app()->params['datetodb'], strtotime($_POST['bonmealdate'])),
				$_POST['bonmealno'],
				$_POST['plantid'],
				$_POST['mealtypeid'],
				$_POST['addressbookid'],
				$_POST['taxid'],
				$_POST['price'],
				$_POST['qty'],
				$_POST['description']
				));
			$transaction->commit();
			GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
	}
	public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Approvebonmeal(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
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
	public function actionDelete() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Deletebonmeal(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
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
	public function actionPurge() {
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try
			{
				$sql = 'call Purgebonmeal(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();				
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollback();
				GetMessage(true,$e->getMessage());
			}
		}
		else {
			GetMessage(true,getcatalog('chooseone'));
		}
	}
	public function actionPurgedetail() {
		header('Content-Type: application/json');
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgebonmealdetail(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e) {
				$transaction->rollback();
				GetMessage(true,$e->getMessage());
			}
		}
		else {
			GetMessage(true,getcatalog('chooseone'));
		}
	}	
	public function actionDownPDF() {
	  parent::actionDownload();
	  $sql = "select bonmealno,bonmealname,bonmealdate,useraccess
						from bonmeal a ";						
		$bonmealid = filter_input(INPUT_GET,'bonmealid');
		$bonmealno = filter_input(INPUT_GET,'bonmealno');
		$bonmealname = filter_input(INPUT_GET,'bonmealname');
		$bonmealdate = filter_input(INPUT_GET,'bonmealdate');
		$useraccess = filter_input(INPUT_GET,'useraccess');
		$sql .= " and coalesce(a.bonmealid,'') like '%".$bonmealid."%' 
			and coalesce(a.bonmealno,'') like '%".$bonmealno."%'
			and coalesce(a.bonmealname,'') like '%".$bonmealname."%'
			and coalesce(a.bonmealdate,'') like '%".$bonmealdate."%'
			and coalesce(a.useraccess,'') like '%".$useraccess."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.bonmealid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by bonmealid asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('supplier');
		$this->pdf->AddPage('P',array(400,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('bonmealno'),
																	GetCatalog('bonmealname'),
																	GetCatalog('bonmealdate'),
																	GetCatalog('useraccess'),
																	GetCatalog('bankname'));
		$this->pdf->setwidths(array(15,90,40,55,40,40,80,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L');		
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['bonmealid'],$row1['bonmealno'],$row1['taxno'],$row1['bankaccountno'],$row1['bankname'],$row1['accountowner'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='supplier';
		parent::actionDownxls();
		$sql = "select a.addressbookid,a.fullname,
						ifnull((a.taxno),'-') as taxno,
						ifnull((a.bankaccountno),'-') as bankaccountno,
						ifnull((a.bankname),'-') as bankname,
						ifnull((a.accountowner),'-') as accountowner,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from addressbook a
						where a.isvendor = 1 ";
		$addressbookid = filter_input(INPUT_GET,'addressbookid');
		$fullname = filter_input(INPUT_GET,'fullname');
		$bankname = filter_input(INPUT_GET,'bankname');
		$accountowner = filter_input(INPUT_GET,'accountowner');
		$sql .= " and coalesce(a.addressbookid,'') like '%".$addressbookid."%' 
			and coalesce(a.fullname,'') like '%".$fullname."%'
			and coalesce(a.bankname,'') like '%".$bankname."%'
			and coalesce(a.accountowner,'') like '%".$accountowner."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.addressbookid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by fullname asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('addressbookid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('fullname'))			
			->setCellValueByColumnAndRow(2,2,GetCatalog('taxno'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('bankaccountno'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('bankname'))
			->setCellValueByColumnAndRow(6,2,GetCatalog('accountowner'))
			->setCellValueByColumnAndRow(7,2,GetCatalog('recordstatus'));
			
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['addressbookid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['fullname'])				
				->setCellValueByColumnAndRow(2, $i+1, $row1['taxno'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['bankaccountno'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['bankname'])
				->setCellValueByColumnAndRow(6, $i+1, $row1['accountowner'])
				->setCellValueByColumnAndRow(7, $i+1, $row1['recordstatus']);
			$i+=1;
		}		
		$this->getFooterXLS($this->phpExcel);
	}
}
