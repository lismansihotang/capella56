<?php
class FamethodController extends Controller {
  public $menuname = 'famethod';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
	public function search() {
    header("Content-Type: application/json");
    $famethodid   = isset($_POST['famethodid']) ? $_POST['famethodid'] : '';
    $methodname = isset($_POST['methodname']) ? $_POST['methodname'] : '';
    $famethodid   = isset($_GET['q']) ? $_GET['q'] : $famethodid;
    $methodname = isset($_GET['q']) ? $_GET['q'] : $methodname;
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 't.famethodid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('famethod t')
				->where("((famethodid like :famethodid) or (methodname like :methodname)) and t.recordstatus = 1", array(
						':famethodid' => '%' . $famethodid . '%',
						':methodname' => '%' . $methodname . '%',
				))->queryScalar();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('famethod t')
				->where("((famethodid like :famethodid) and (methodname like :methodname))", array(
						':famethodid' => '%' . $famethodid . '%',
						':methodname' => '%' . $methodname . '%',
				))->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')
				->from('famethod t')
				->where("((famethodid like :famethodid) or (methodname like :methodname)) and t.recordstatus = 1", array(
						':famethodid' => '%' . $famethodid . '%',
						':methodname' => '%' . $methodname . '%',
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')
				->from('famethod t')
				->where("((famethodid like :famethodid) and (methodname like :methodname))", array(
						':famethodid' => '%' . $famethodid . '%',
						':methodname' => '%' . $methodname . '%',
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		}
		foreach ($cmd as $data) {      
			$row[] = array(
        'famethodid' => $data['famethodid'],
        'methodname' => $data['methodname'],
        'recordstatus' => $data['recordstatus']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	protected function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertfamethod(:vmethodname,:vrecordstatus,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatefamethod(:vid,:vmethodname,:vrecordstatus,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vmethodname', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-famethod"]["name"]);
		if (move_uploaded_file($_FILES["file-famethod"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection  = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$methodname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData($connection,array($id,$methodname,$recordstatus));
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
      $this->ModifyData($connection,array((isset($_POST['famethodid'])?$_POST['famethodid']:''),
				$_POST['methodname'],$_POST['recordstatus']));
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
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgefamethod(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
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
    $sql = "select famethodid,methodname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from famethod a ";
		$famethodid = filter_input(INPUT_GET,'famethodid');
		$methodname = filter_input(INPUT_GET,'methodname');
		$sql .= " where coalesce(a.famethodid,'') like '%".$famethodid."%' 
			and coalesce(a.methodname,'') like '%".$methodname."%'";
		if ($_GET['id'] !== '') {
			$sql = $sql . " and a.famethodid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by methodname asc ";
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('famethod');
    $this->pdf->AddPage('P');
    $this->pdf->colalign  = array(
      'L',
      'L',
      'L'
    );
    $this->pdf->colheader = array(
      GetCatalog('famethodid'),
      GetCatalog('methodname'),
      GetCatalog('recordstatus')
    );
    $this->pdf->setwidths(array(
      15,
      155,
      20
    ));
    $this->pdf->Rowheader();
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'L'
    );
    foreach ($dataReader as $row1) {
      $this->pdf->row(array(
        $row1['famethodid'],
        $row1['methodname'],
        $row1['recordstatus']
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    $this->menuname = 'famethod';
    parent::actionDownxls();
    $sql = "select famethodid,methodname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from famethod a ";
    $famethodid = filter_input(INPUT_GET,'famethodid');
		$methodname = filter_input(INPUT_GET,'methodname');
		$sql .= " where coalesce(a.famethodid,'') like '%".$famethodid."%' 
			and coalesce(a.methodname,'') like '%".$methodname."%'";
		if ($_GET['id'] !== '') {
			$sql = $sql . " and a.famethodid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by methodname asc ";
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 3;
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i, $row1['famethodid'])->setCellValueByColumnAndRow(1, $i, $row1['methodname'])->setCellValueByColumnAndRow(2, $i, $row1['recordstatus']);
      $i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}