<?php
class Controller extends CController {
  public $layout = '//layouts/columngeneral';
  public $menu = array();
  public $breadcrumbs = array();
  protected $iswrite = 'iswrite';
  protected $isread = 'isread';
  protected $ispost = 'ispost';
  protected $isreject = 'isreject';
  protected $isupload = 'isupload';
  protected $isdownload = 'isdownload';
  protected $ispurge = 'ispurge';
  protected $txt = '_help';
  protected $messages = '';
  protected $connection;
  protected $pdf;
  protected $wfprint = '';
  protected $menuname = '';
  protected $folder = '';
  protected $filename = '';
  protected $phpExcel;
  protected $EAN13;
	protected $sql = '';
  protected $menuconfig = '';
  protected $dataprint;
  public function actionIndex() {
		if (Yii::app()->user->name == 'Guest') {
			$this->redirect(Yii::app()->createUrl('site/login'));
		} else {
			$this->connection = Yii::app()->db;
		}
  }
  public function actionCreate() {
    if (checkAccess($this->menuname, $this->iswrite) == false) {
      getmessage(true, 'youarenotauthorizedcreate');
    } else {
			if(!Yii::app()->request->isPostRequest)
				throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
			header('Content-Type: application/json');
		}
  }
  public function actionUpdate() {
    if (checkAccess($this->menuname, $this->iswrite) == false) {
      getmessage(true, 'youarenotauthorizedupdate');
    } else {
			if(!Yii::app()->request->isPostRequest)
				throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
			header('Content-Type: application/json');
		}
  }
  public function actionWrite() {
    if (checkAccess($this->menuname, $this->iswrite) == false) {
      getmessage(true, 'youarenotauthorizedwrite');
    } else {
			if(!Yii::app()->request->isPostRequest)
				throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
			header('Content-Type: application/json');
		}
  }
  public function actionDelete() {
    if (checkAccess($this->menuname, $this->isreject) == false) {
      getmessage(true, 'youarenotauthorizeddelete');
    } else {
			if(!Yii::app()->request->isPostRequest)
				throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
			header('Content-Type: application/json');
		}
  }
  public function actionApprove() {
    if (checkAccess($this->menuname, $this->ispost) == false) {
      getmessage(true, 'youarenotauthorizedapprove');
    } else {
			if(!Yii::app()->request->isPostRequest)
				throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
			header('Content-Type: application/json');
		}
  }
  public function actionHistory() {
    if (checkAccess($this->menuname, $this->isread) == false) {
      getmessage(true, 'youarenotauthorizedhistory');
    } else {
			if(!Yii::app()->request->isPostRequest)
				throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
			header('Content-Type: application/json');
		}
  }
  public function actionPurge() {
    if (checkAccess($this->menuname, $this->ispurge) == false) {
      getmessage(true, 'youarenotauthorizedpurge');
    } else {
			if(!Yii::app()->request->isPostRequest)
				throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
			header('Content-Type: application/json');
		}
  }
  public function actionUpload() {
		if ((Yii::app()->user->id == '') || (Yii::app()->user->id == null)) {
			$this->redirect(Yii::app()->createUrl('site/login'));
		} else {
			if (checkAccess($this->menuname, $this->isupload) == false) {
				getmessage(true, 'youarenotauthorizedupload');
			}
			if(!Yii::app()->request->isPostRequest)
				throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
			Yii::import('ext.PHPExcel.XPHPExcel');
			$this->phpExcel = XPHPExcel::createPHPExcel();
		}
  }
  public function actionDownpdf() {
		if ((Yii::app()->user->id == '') || (Yii::app()->user->id == null)) {
			$this->redirect(Yii::app()->createUrl('site/login'));
		} else {
			if (checkAccess($this->menuname, $this->isdownload) == false) {
				getmessage(true, 'youarenotauthorized');
			} else {
        $this->actionDataPrint();
        PrintPDF($this->menuname,$this->dataprint);
			}
		}
  }
  protected function actionDataPrint() {
    $this->dataprint=array();
  }
  public function actionDownxls() {
		if ((Yii::app()->user->id == '') || (Yii::app()->user->id == null)) {
			$this->redirect(Yii::app()->createUrl('site/login'));
		} else {
      $this->actionDataPrint();
      PrintXLS($this->menuname,$this->dataprint);
		}
  }
  public function actionDowndoc() {
		if ((Yii::app()->user->id == '') || (Yii::app()->user->id == null)) {
			$this->redirect(Yii::app()->createUrl('site/login'));
		} else {
      $this->actionDataPrint();
      PrintDoc($this->menuname,$this->dataprint);
		}
  }
  public function actionHelp() {
    getmessage('success', '');
  }
}
