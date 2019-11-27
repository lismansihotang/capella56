<?php
return array(
	'theme'=>'cerulean',
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Capella ERP Indonesia',
	//'preload'=>array('log'),
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.modules.admin.models.*',
	),
	'components'=>array(
		'clientScript' => array('scriptMap' => array('jquery.js' => false,'jquery.min.js' => false)),
		'authManager' => array(
			'class' => 'CDbAuthManager',
			'connectionID' => 'db',
    ),
		'format'=>array(
			'class'=>'application.components.Formatter',
		),
		'user'=>array(
      'class'=>'application.components.WebUser',
			'allowAutoLogin'=>true,
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
			'showScriptName'=>false,
      'caseSensitive'=>false,  
		),
		'db'=>array(
			'connectionString' => 'mysql:port=3306;dbname=capellafive;host=localhost',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'initSQLs'=>array('set names utf8'),
      'schemaCachingDuration' => 3600,
      //'enableProfiling'=>true,
      //'enableParamLogging' => true,
		),
		'cache'=>array(
			'class'=>'CRedisCache',
    ),
    'log'=>array(
      'class'=>'CLogRouter',
      'routes'=>array(
        array(
          'class'=>'CWebLogRoute',
          'categories' => 'system.db.*',
          'enabled'=>true
        ),
      )
    )
  ),
	'params'=>array(
		'themes'=>'cerulean',
		'install'=>false,
		'adminEmail'=>'romy@prismagrup.com',
		'defaultPageSize'=>10,
		'defaultYearFrom'=>date('Y')-1,
		'defaultYearTo'=>date('Y'),
		'sizeLimit'=>10*1024*1024,
		'allowedext'=>array("xls","csv","xlsx","vsd","pdf","gdb","doc","docx","jpg","gif","png","rar","zip","jpeg"),
		'language'=>1,
		'defaultnumberqty'=>'#,##0.00',
		'defaultnumberprice'=>'#,##0',
		'dateviewfromdb'=>'d-m-Y',
		'dateviewcjui'=>'dd-mm-yy',
		'dateviewgrid'=>'dd-MM-yyyy',
		'datetodb'=>'Y-m-d',
		'timeviewfromdb'=>'h:m',
		'datetimeviewfromdb'=>'d-M-Y H:i:s',
		'timeviewcjui'=>'h:m',
		'datetimeviewgrid'=>'dd-MM-yyyy H:m',
		'datetimetodb'=>'Y-m-d H:i:s',
		'title'=>'Capella ERP Indonesia',
		'baseUrl'=>'https://localhost/capellafive/',
		'baseUrlReport'=>'http://localhost:8080/jasperserver/rest_v2/reports/reports/capellafive',
		'ReportServerUser'=>'jasperadmin',
		'ReportServerPass'=>'jasperadmin',
    'ReportTimeout'=>5,
    'SysInfoServer'=>'https://localhost/sysinfo/xml.php?plugin=complete&json'
	),
);
