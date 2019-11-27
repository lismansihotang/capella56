<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="language" content="en">
	<meta name=viewport content="width=device-width, initial-scale=1">	
	<meta name="Description" content="Make your business optimize with Capella ERP Indonesia The Best Web ERP Apps">
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<link rel="icon" href="<?php echo Yii::app()->request->baseUrl;?>/images/icons/favicon.ico" type="image/x-icon">	
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl;?>/css/easyui.min.css"/>	
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/css/icon.css"/>	
	<style type="text/css">
		.datagrid-row-over td{
			background:#D0E5F5;
		}
		.datagrid-row-selected td{
			background:#FBEC88;
			color: black;
		}
	</style>
</head>
<body class="easyui-layout">
	<script src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery.min.js"></script>
	<?php Yii::app()->setLanguage('id_id');echo $content; ?>	
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/easyui/jquery.easyui.min.all.js"></script>	
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl?>/js/highchart/highcharts.min.all.js"></script>	
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl?>/js/highchart/modules/exporting.min.all.js"></script>
</body>
</html>