<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="Description" content="Make your business optimize with Capella ERP Indonesia The Best Web ERP Apps">
	<meta name="msapplication-square310x310logo" content="<?php echo Yii::app()->request->baseUrl;?>/images/launcher-icon-192192.png">
	<meta name="theme-color" content="#f00">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl;?>/css/main.min.css">
	<link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl;?>/images/icons/favicon.ico"/>
	<link rel="manifest" href="<?php echo Yii::app()->request->baseUrl;?>/manifest.json">
	<link rel="icon" sizes="192x192" href="<?php echo Yii::app()->request->baseUrl;?>/images/launcher-icon-192192.png">
	<link rel="apple-touch-icon" sizes="192x192" href="<?php echo Yii::app()->request->baseUrl;?>/images/launcher-icon-192192.png">
	<script src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery.min.js"></script>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>	
<script>
var isFirefox = typeof InstallTrigger !== 'undefined';
var isIE = /*@cc_on!@*/false || !!document.documentMode;
var isEdge = !isIE && !!window.StyleMedia;
document.onreadystatechange = function(e) {
	if (document.readyState === 'complete') {
		if (isIE == true) {
			document.body.innerHTML = '<h1>Maaf, hanya Opera atau Chrome yang diperbolehkan</h1>';
		} else 
		if (isEdge == true) {
			document.body.innerHTML = '<h1>Maaf, hanya Opera atau Chrome yang diperbolehkan</h1>';
		} else 
		if (isFirefox == true) {
			document.body.innerHTML = '<h1>Maaf, hanya Opera atau Chrome yang diperbolehkan</h1>';
		}
	}
};
</script>
	<?php Yii::app()->setLanguage('id_id');echo $content; ?>
</body>
</html>