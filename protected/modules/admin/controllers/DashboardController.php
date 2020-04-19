<?php
class DashboardController extends Controller {
	public $menuname = 'dashboard';
	public function actionIndex() {
		parent::actionIndex();
		$sql = "select distinct d.widgetname,d.widgettitle,d.widgeturl,a.dashgroup,a.webformat,a.position, (
			select count(1)
			from userdash d0
			where d0.dashgroup = a.dashgroup and d0.menuaccessid = a.menuaccessid and d0.groupaccessid = b.groupaccessid
			) dashcount
			from userdash a
			join usergroup b on b.groupaccessid = a.groupaccessid 
			join useraccess c on c.useraccessid = b.useraccessid
			join widget d on d.widgetid = a.widgetid 
			join menuaccess e on e.menuaccessid = a.menuaccessid 
			where lower(menuname) = lower('dashboard') and c.username = '".Yii::app()->user->name."'
			order by dashgroup asc, position asc ";
		$datas = Yii::app()->db->createCommand($sql)->queryAll();
		$this->renderPartial('index',array('datas'=>$datas));
	}
	public function actionUsertodo(){
		$this->renderPartial('usertodo');
	}
	public function actionUserprofile(){
		$this->renderPartial('userprofile');
	}
	public function actionMaterialstockoverview(){
		$this->renderPartial('materialstockoverview');
	}
	public function actionPeriodicsales(){
		$this->renderPartial('periodicsales');
	}
	public function actionPooutstanding(){
		$this->renderPartial('pooutstanding');
	}
	public function actionServer(){
		$this->renderPartial('server');
	}
	public function actionDeliveryschedule(){
		$this->renderPartial('deliveryschedule');
	}
	public function actionProductionschedule(){
		$this->renderPartial('productionschedule');
	}
}