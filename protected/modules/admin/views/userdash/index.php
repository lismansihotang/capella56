<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'userdashid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/userdash/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/userdash/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/userdash/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/userdash/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/userdash/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/userdash/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/userdash/downxls'),
	'columns'=>"
		{
			field:'userdashid',
			title:'".GetCatalog('userdashid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
					return value;
		}},
		{
			field:'groupaccessid',
			title:'".GetCatalog('groupaccess') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'450px',
					mode : 'remote',
					method:'get',
					idField:'groupaccessid',
					textField:'groupname',
					pagination:true,
					required:true,
					queryParams: {
						combo:true,
					},
					url:'".$this->createUrl('groupaccess/index',array('grid'=>true)) ."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'groupaccessid',title:'".GetCatalog('groupaccessid')."',width:'50px'},
						{field:'groupname',title:'".GetCatalog('groupname')."',width:'150px'},
					]]
				}	
			},
			width:'250px',
			sortable: true,
			formatter: function(value,row,index){
				return row.groupname;
		}},
		{
			field:'widgetid',
			title:'".GetCatalog('widget') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'450px',
					mode : 'remote',
					method:'get',
					idField:'widgetid',
					textField:'widgetname',
					pagination:true,
					required:true,
					queryParams: {
						combo:true,
					},
					url:'".$this->createUrl('widget/index',array('grid'=>true)) ."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'widgetid',title:'".GetCatalog('widgetid')."',width:'50px'},
						{field:'widgetname',title:'".GetCatalog('widgetname')."',width:'150px'},
					]]
				}	
			},
			width:'250px',
			sortable: true,
			formatter: function(value,row,index){
				return row.widgetname;
		}},
		{
			field:'menuaccessid',
			title:'".GetCatalog('menuaccess') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'450px',
					mode : 'remote',
					method:'get',
					idField:'menuaccessid',
					textField:'menuname',
					pagination:true,
					required:true,
					queryParams: {
						combo:true,
					},
					url:'".$this->createUrl('menuaccess/index',array('grid'=>true)) ."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'menuaccessid',title:'".GetCatalog('menuaccessid')."',width:'50px'},
						{field:'menuname',title:'".GetCatalog('menuname')."',width:'150px'},
					]]
				}	
			},
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.menuname;
		}},
		{
			field:'position',
			title:'".GetCatalog('position') ."',
			editor:'text',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return row.position;
		}},
		{
			field:'webformat',
			title:'".GetCatalog('webformat') ."',
			editor:'text',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.webformat;
		}},
		{
			field:'dashgroup',
			title:'".GetCatalog('dashgroup') ."',
			editor:'text',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.dashgroup;
		}},",
	'searchfield'=> array ('userdashid','groupname','menuname','widget')
));