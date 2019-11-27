<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="language" content="en">
	<meta name=viewport content="width=device-width, initial-scale=1">	
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/js/bootstrap/css/bootstrap.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/js/bootstrap/css/bootstrap-theme.min.css"/>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/bootstrap/js/bootstrap.min.js"></script>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body class="easyui-layout">
	<?php Yii::app()->setLanguage('id_id');echo $content; ?>
</body>
</html>
