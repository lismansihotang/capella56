<?php
class DashboardController extends Controller {
	public $menuname = 'dashboard';
	public function actionIndex() {
		parent::actionIndex();
		$datas = GetDashboard();
		$this->renderPartial('index',array('datas'=>$datas));
	}
	public function actionUserprofile() {
		$this->renderPartial('userprofile');
	}
	public function actionComparesogi() {
		$this->renderPartial('comparesogi');
	}
	public function actioncustomermap() {
		$this->renderPartial('customermap');
	}
	public function actiondeliveryschedule() {
		$this->renderPartial('deliveryschedule');
	}
	public function actionmaterialstockoverview() {
    CreateCode('productstock');
		$this->renderPartial('materialstockoverview');
	}
	public function actionperiodicpurchase() {
		$this->renderPartial('periodicpurchase');
	}
	public function actionrawschedule() {
		$this->renderPartial('rawschedule');
	}
	public function actionusertodo() {
		$this->renderPartial('usertodo');
	}
	public function actionproductionschedule() {
		$this->renderPartial('productionschedule');
	}
	public function actionserver() {
		$this->renderPartial('server');
	}
	public function actionperiodicsales() {
		$this->renderPartial('periodicsales');
  }
  public function actionPOoutstanding() {
    $this->renderPartial('pooutstanding');
  }
}