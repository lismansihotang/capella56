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
	'downdoc'=>Yii::app()->createUrl('admin/parameter/downdoc'),
	'columns'=>"
		{
		field:'parameterid',
		title:localStorage.getItem('catalogparameterid'),
		sortable: true,
		width:'80px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'paramname',
		title:localStorage.getItem('catalogparamname'),
		editor: {type: 'textbox', options:{required:true}},
		width:'250px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'paramvalue',
		title:localStorage.getItem('catalogparamvalue'),
		editor:{type: 'textbox', options:{required:true}},
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'description',
		title:localStorage.getItem('catalogdescription'),
		editor:{type: 'textbox', options:{required:true}},
		width:'300px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'moduleid',
		title:localStorage.getItem('catalogmodules'),
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
				loadMsg: localStorage.getItem('catalogpleasewait'),
				columns:[[
					{field:'moduleid',title:localStorage.getItem('catalogmoduleid'),width:'50px'},
					{field:'modulename',title:localStorage.getItem('catalogrmodulename'),width:'100px'},
					{field:'moduledesc',title:localStorage.getItem('catalogmoduledesc'),width:'250px'},
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
		title:localStorage.getItem('catalogrecordstatus'),
		align:'center',
		width:'80px',
		editor:{type:'checkbox',options:{on:'1',off:'0'}},
		sortable: true,
		formatter: function(value,row,index){
			if (value == 1){
				return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
			} else {
				return '';
			}
		}},",
	'searchfield'=> array ('parameterid','paramname','paramvalue','description','modulename')
));