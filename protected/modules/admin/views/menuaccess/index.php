<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'menuaccessid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/menuaccess/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/menuaccess/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/menuaccess/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/menuaccess/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/menuaccess/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/menuaccess/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/menuaccess/downxls'),
	'columns'=>"
		{
			field:'menuaccessid',
			title:'".GetCatalog('menuaccessid')."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
								return value;
		}},
		{
			field:'menuname',
			title:'".GetCatalog('menuname')."',
			editor: {
				type: 'validatebox',
				options:{
					required:true
				}
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
									return value;
		}},
		{
			field:'description',
			title:'".GetCatalog('description')."',
			editor: {
				type: 'validatebox',
				options:{
					required:true
				}
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
									return value;
		}},
		{
			field:'menuurl',
			title:'".GetCatalog('menuurl')."',
			editor: {
				type: 'validatebox',
				options:{
					required:true
				}
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
									return value;
		}},
		{
			field:'menuicon',
			title:'".GetCatalog('menuicon')."',
			editor: {
				type: 'validatebox',
				options:{
					required:true
				}
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
									return value;
		}},
		{
			field:'parentid',
			title:'".GetCatalog('parent')."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'menuaccessid',
					textField:'menuname',
					url:'".$this->createUrl('menuaccess/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'menuaccessid',title:'".GetCatalog('menuaccessid')."',width:'50px'},
						{field:'menuname',title:'".GetCatalog('menuname')."',width:'150px'},
						{field:'description',title:'".GetCatalog('description')."',width:'250px'},
						{field:'modulename',title:'".GetCatalog('modulename')."',width:'250px'},
					]]
				}	
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
									return row.parentdesc;
		}},
		{
			field:'moduleid',
			title:'".GetCatalog('module')."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'moduleid',
					textField:'modulename',
					url:'".$this->createUrl('modules/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'moduleid',title:'".GetCatalog('moduleid')."',width:'50px'},
						{field:'modulename',title:'".GetCatalog('modulename')."',width:'150px'},
						{field:'moduledesc',title:'".GetCatalog('moduledesc')."',width:'200px'},
					]]
				}	
			},
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return row.modulename;
		}},
		{
			field:'viewcode',
			title:'".GetCatalog('viewcode')."',
			editor: {
				type: 'textbox',
				options:{
					multiline:true,
					required:true,
height:'400px',
				}
			},
			width:'900px',
			sortable: true,
			formatter: function(value,row,index){
									return value;
		}},
{
			field:'controllercode',
			title:'".GetCatalog('controllercode')."',
			editor: {
				type: 'textbox',
				options:{
					multiline:true,
					required:true,
height:'400px',
				}
			},
			width:'900px',
			sortable: true,
			formatter: function(value,row,index){
									return value;
		}},
{
			field:'menudep',
			title:'".GetCatalog('menudep')."',
			editor: {
				type: 'textbox',
				options:{
					
				}
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
									return value;
		}},
		{
			field:'sortorder',
			title:'".GetCatalog('sortorder')."',
			editor: {
				type: 'numberbox',
				options:{
					required:true
				}
			},
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus')."',
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
				} else {
					return '';
				}
			}},",
	'searchfield'=> array ('menuaccessid','menuname','description','menuurl','parentname','modulename')
));
