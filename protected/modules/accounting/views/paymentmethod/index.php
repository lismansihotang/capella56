<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'paymentmethodid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('accounting/paymentmethod/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/paymentmethod/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/paymentmethod/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/paymentmethod/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('accounting/paymentmethod/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/paymentmethod/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/paymentmethod/downxls'),
	'columns'=>"
		{
			field:'paymentmethodid',
			title:'". GetCatalog('paymentmethodid') ."',
			width:'60px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'paycode',
			title:'". GetCatalog('paycode') ."',
			width:'120px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'paydays',
			title:'". GetCatalog('paydays') ."',
			width:'120px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'paymentname',
			title:'". GetCatalog('paymentname') ."',
			width:'250px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'". GetCatalog('recordstatus') ."',
			width:'80px',
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
				} else {
					return '';
				}
		}}",
	'searchfield'=> array ('paymentmethodid','paycode','paydays','paymentname')
));
