<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'translogid',
	'formtype'=>'master',
	'iswrite'=>0,
	'isupload'=>0,
	'url'=>Yii::app()->createUrl('admin/translog/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/translog/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/translog/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/translog/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/translog/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/translog/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/translog/downxls'),
	'columns'=>"
		{
			field:'translogid',
			title:'".GetCatalog('translogid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'username',
			title:'".GetCatalog('username') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'ippublic',
			title:'".GetCatalog('ippublic') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'iplocal',
			title:'".GetCatalog('iplocal') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'createddate',
			title:'".GetCatalog('createddate') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'useraction',
			title:'".GetCatalog('useraction') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'newdata',
			title:'".GetCatalog('newdata') ."',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
		field:'olddata',
		title:'".GetCatalog('olddata') ."',
		sortable: true,
		formatter: function(value,row,index){
								return value;
							}},
		{
		field:'menuname',
		title:'".GetCatalog('menuname') ."',
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
							}},
		{
		field:'tableid',
		title:'".GetCatalog('tableid') ."',
		width:'50px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
							}},",
	'searchfield'=> array ('translogid','useraction','username','createddate','newdata','olddata','menuname','tableid')
));
