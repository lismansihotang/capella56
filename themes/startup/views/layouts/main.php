<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/bootstrap.min.css">
	<?php Yii::app()->clientScript->registerCoreScript('jquery');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl."/js/bootstrap.min.js"); ?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl."/js/jquery.bootstrap.wizard.min.js"); ?>
	<title>Capella CMS - Install Mode</title>
</head>

<body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Capella CMS - Instalasi - Mudah dan Cepat, Ikuti Petunjuk Instalasi</a>
    </div>
	</div>
</div>
</nav>
<?php echo $content ?>
</body>
</html>
