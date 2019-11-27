<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'parameterid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/parameter/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/parameter/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/parameter/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/parameter/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/parameter/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/parameter/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/parameter/downxls'),
	'columns'=>"
		{
		field:'parameterid',
		title:'".GetCatalog('parameterid') ."',
		sortable: true,
		width:'80px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'paramname',
		title:'".GetCatalog('paramname') ."',
		editor:'text',
		width:'250px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'paramvalue',
		title:'".GetCatalog('paramvalue') ."',
		editor:'text',
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'description',
		title:'".GetCatalog('description') ."',
		editor:'text',
		width:'300px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'moduleid',
		title:'".GetCatalog('module') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'moduleid',
				textField:'modulename',
				url:'".$this->createUrl('modules/index',array('grid'=>true)) ."',
				fitColumns:true,
				required:true,
				loadMsg: '".GetCatalog('pleasewait')."',
				columns:[[
					{field:'moduleid',title:'".GetCatalog('moduleid')."',width:'50px'},
					{field:'modulename',title:'".GetCatalog('modulename')."',width:'100px'},
					{field:'moduledesc',title:'".GetCatalog('moduledesc')."',width:'250px'},
				]]
			}	
		},
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
			return row.modulename;
	}},
	{
		field:'recordstatus',
		title:'".GetCatalog('recordstatus') ."',
		align:'center',
		width:'80px',
		editor:{type:'checkbox',options:{on:'1',off:'0'}},
		sortable: true,
		formatter: function(value,row,index){
			if (value == 1){
				return '<img src=\"".Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
			} else {
				return '';
			}
		}},",
	'searchfield'=> array ('parameterid','paramname','paramvalue','description','modulename')
));
