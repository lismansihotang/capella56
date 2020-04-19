<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'purchasingorgid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('purchasing/purchasingorg/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('purchasing/purchasingorg/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('purchasing/purchasingorg/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('purchasing/purchasingorg/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('purchasing/purchasingorg/upload'),
	'downpdf'=>Yii::app()->createUrl('purchasing/purchasingorg/downpdf'),
	'downxls'=>Yii::app()->createUrl('purchasing/purchasingorg/downxls'),
	'columns'=>"
		{
			field:'purchasingorgid',
			title:'".GetCatalog('purchasingorgid') ."',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'purchasingorgcode',
			title:'".GetCatalog('purchasingorgcode') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'description',
			title:'".GetCatalog('description') ."',
			width:'250px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus') ."',
			width:'80px',
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},",
	'searchfield'=> array ('purchasingorgid','purchasingorgcode','description')
));