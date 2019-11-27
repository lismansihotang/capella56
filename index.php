<?php
date_default_timezone_set('Asia/Jakarta');
$capella=dirname(__FILE__).'/protected/components/CpHelper.php';
$yii=dirname(__FILE__).'/../framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';
define('ROOTPATH', __DIR__);
defined('YII_DEBUG') or define('YII_DEBUG',true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
require_once($capella);
require_once($yii);
class ExtendableWebApp extends CWebApplication {
	protected function init() {
		foreach (glob(dirname(__FILE__).'/protected/modules/*', GLOB_ONLYDIR) as $moduleDirectory) {
			$this->setModules(array(basename($moduleDirectory)));
		}
		return parent::init();
	}
}
$app=new ExtendableWebApp($config);
$app->run();