<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'purchasinggroupid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('purchasing/purchasinggroup/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('purchasing/purchasinggroup/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('purchasing/purchasinggroup/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('purchasing/purchasinggroup/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('purchasing/purchasinggroup/upload'),
	'downpdf'=>Yii::app()->createUrl('purchasing/purchasinggroup/downpdf'),
	'downxls'=>Yii::app()->createUrl('purchasing/purchasinggroup/downxls'),
	'columns'=>"
		{
			field:'purchasinggroupid',
			title:'".GetCatalog('purchasinggroupid') ."',
			width:30,
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'purchasingorgid',
			title:'".GetCatalog('purchasingorg') ."',
			width:150,
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'purchasingorgid',
					textField:'purchasingorgcode',
					url:'".$this->createUrl('purchasingorg/index',array('grid'=>true)) ."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'purchasingorgid',title:'".GetCatalog('purchasingorgid')."',width:'50px'},
						{field:'purchasingorgcode',title:'".GetCatalog('purchasingorgcode')."',width:'100px'},
						{field:'description',title:'".GetCatalog('description')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.purchasingorgcode;
		}},
		{
			field:'purchasinggroupcode',
			title:'".GetCatalog('purchasinggroupcode') ."',
			width:150,
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'description',
			title:'".GetCatalog('description') ."',
			width:150,
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus') ."',
			width:80,
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}}",
	'searchfield'=> array ('purchasinggroupid','purchasingorgcode','purchasinggroupcode','description')
));