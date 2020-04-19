<?php
class WidgetController extends Controller {
	public $menuname = 'widget';
	public function actionIndex() {
		parent::actionIndex();
		if (isset($_GET['grid'])) 
			echo $this->search();
		else 
			$this->renderPartial('index', array());
	}
	public function search() {
		header('Content-Type: application/json');
		$widgetid			 = GetSearchText(array('POST','Q'),'widgetid');
		$widgetname		 = GetSearchText(array('POST','Q'),'widgetname');
		$widgettitle		 = GetSearchText(array('POST','Q'),'widgettitle');
		$page = GetSearchText(array('POST','GET'),'page',1,'int');
		$rows = GetSearchText(array('POST','GET'),'rows',10,'int');
		$sort = GetSearchText(array('POST','GET'),'sort','widgetid','int');
		$order = GetSearchText(array('POST','GET'),'order','desc','int');
		$offset	 = ($page - 1) * $rows;
		$result	 = array();
		$row		 = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('widget t')
				->leftjoin('modules a','a.moduleid = t.moduleid')
				->where('(widgetid like :widgetid) and (widgetname like :widgetname) and (widgettitle like :widgettitle)',
					array(':widgetid' => $widgetid, ':widgetname' => $widgetname, ':widgettitle' => $widgettitle))
				->queryScalar();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('widget t')
				->leftjoin('modules a','a.moduleid = t.moduleid')
				->where('(widgetid like :widgetid) or (widgetname like :widgetname) or (widgettitle like :widgettitle)',
					array(':widgetid' => $widgetid, ':widgetname' => $widgetname, ':widgettitle' => $widgettitle))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.modulename')
				->from('widget t')
				->leftjoin('modules a','a.moduleid = t.moduleid')
				->where('(widgetid like :widgetid) and (widgetname like :widgetname) and (widgettitle like :widgettitle)',
					array(':widgetid' => $widgetid, ':widgetname' => $widgetname, ':widgettitle' => $widgettitle))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.modulename')
				->from('widget t')
				->leftjoin('modules a','a.moduleid = t.moduleid')
				->where('(widgetid like :widgetid) or (widgetname like :widgetname) or (widgettitle like :widgettitle)',
					array(':widgetid' => $widgetid, ':widgetname' => $widgetname, ':widgettitle' => $widgettitle))
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach ($cmd as $data) {
			$row[] = array(
				'widgetid' => $data['widgetid'],
				'widgetname' => $data['widgetname'],
				'widgettitle' => $data['widgettitle'],
				'widgetversion' => $data['widgetversion'],
				'widgetby' => $data['widgetby'],
				'description' => $data['description'],
				'widgeturl' => $data['widgeturl'],
				'modulename' => $data['modulename'],
				'moduleid' => $data['moduleid'],
				'recordstatus' => $data['recordstatus'],
			);
		}
		$result = array_merge($result, array('rows' => $row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql		 = 'call Insertwidget(:vwidgetname,:vwidgettitle,:vwidgetversion,:vwidgetby,:vdescription,:vwidgeturl,:vmoduleid,:vrecordstatus,:vdatauser)';
			$command = $connection->createCommand($sql);
		} else {
			$sql		 = 'call Updatewidget(:vid,:vwidgetname,:vwidgettitle,:vwidgetversion,:vwidgetby,:vdescription,:vwidgeturl,:vmoduleid,:vrecordstatus,:vdatauser)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vwidgetname', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vwidgettitle', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vwidgetversion', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vwidgetby', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vdescription', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vwidgeturl', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vmoduleid', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(), PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-widget"]["name"]);
		if (move_uploaded_file($_FILES["file-widget"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection	 = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$widgetname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$widgettitle = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$widgetversion = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$widgetby = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$widgeturl = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$modulename = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$moduleid = Yii::app()->db->createCommand("select moduleid from modules where modulename = '".$modulename."'")->queryScalar();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$this->ModifyData($connection,array($id,$widgetname,$widgettitle,$widgetversion,
						$widgetby,$description,$widgeturl,$moduleid,$recordstatus));
				}
				$transaction->commit();
				GetMessage(false, getcatalog('insertsuccess'));
			} catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode($e->errorInfo));
			}
    }
	}
	public function actionSave() {
		parent::actionWrite();
		$connection	 = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['widgetid'])?$_POST['widgetid']:''),$_POST['widgetname'],$_POST['widgettitle'],$_POST['widgetversion'],
				$_POST['widgetby'],$_POST['description'],$_POST['widgeturl'],$_POST['moduleid'],$_POST['recordstatus']));
			$transaction->commit();
			GetMessage(false, getcatalog('insertsuccess'));
		} catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode($e->errorInfo));
		}
	}
	public function actionPurge() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgewidget(:vid,:vdatauser)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vdatauser',GetUserPC(),PDO::PARAM_STR);
				$command->execute();				
				$transaction->commit();
				GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode($e->errorInfo));
			}
		}
		else {
			GetMessage(true,getcatalog('chooseone'));
		}
	}
	protected function actionDataPrint() {
		parent::actionDataPrint();
		$this->dataprint['widgetname'] = GetSearchText(array('GET'),'widgetname');
		$this->dataprint['widgettitle'] = GetSearchText(array('GET'),'widgettitle');
		$id = GetSearchText(array('GET'),'id');
		if ($id != '%%') {
			$this->dataprint['id'] = $id;
		} else {
			$this->dataprint['id'] = GetSearchText(array('GET'),'widgetid');
		}
		$this->dataprint['titleid'] = GetCatalog('id');
		$this->dataprint['titlewidgetname'] = GetCatalog('widgetname');
		$this->dataprint['titlewidgettitle'] = GetCatalog('widgettitle');
		$this->dataprint['titlewidgetversion'] = GetCatalog('widgetversion');
		$this->dataprint['titlewidgetby'] = GetCatalog('widgetby');
		$this->dataprint['titledescription'] = GetCatalog('description');
		$this->dataprint['titlewidgeturl'] = GetCatalog('widgeturl');
		$this->dataprint['titlemodulename'] = GetCatalog('modulename');
		$this->dataprint['titleinstalldate'] = GetCatalog('installdate');
		$this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
  }
}