<?php
class InventoryModule extends CWebModule {
	public function init() {
		$this->setImport(array(
		));
	}
	public function beforeControllerAction($controller, $action) {
		if(parent::beforeControllerAction($controller, $action)) {
			return true;
		}
		else
			return false;
	}
}