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
	'downdoc'=>Yii::app()->createUrl('admin/menuaccess/downdoc'),
	'columns'=>"
		{
			field:'menuaccessid',
			title:localStorage.getItem('catalogmenuaccessid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
								return value;
		}},
		{
			field:'menuname',
			title:localStorage.getItem('catalogmenuname'),
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
									return value;
		}},
		{
			field:'description',
			title:localStorage.getItem('catalogdescription'),
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
									return value;
		}},
		{
			field:'menuurl',
			title:localStorage.getItem('catalogmenuurl'),
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
									return value;
		}},
		{
			field:'menuicon',
			title:localStorage.getItem('catalogmenuicon'),
			editor:'text',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
									return value;
		}},
		{
			field:'parentid',
			title:localStorage.getItem('catalogparent'),
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
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'menuaccessid',title:localStorage.getItem('catalogmenuaccessid'),width:'50px'},
						{field:'menuname',title:localStorage.getItem('catalogmenuname'),width:'150px'},
						{field:'description',title:localStorage.getItem('catalogdescription'),width:'250px'},
						{field:'modulename',title:localStorage.getItem('catalogmodulename'),width:'250px'},
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
			title:localStorage.getItem('catalogmodule'),
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
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'moduleid',title:localStorage.getItem('catalogmoduleid'),width:'50px'},
						{field:'modulename',title:localStorage.getItem('catalogmodulename'),width:'150px'},
						{field:'moduledesc',title:localStorage.getItem('catalogmoduledesc'),width:'200px'},
					]]
				}	
			},
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return row.modulename;
		}},
		{
			field:'sortorder',
			title:localStorage.getItem('catalogsortorder'),
			editor:'text',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:localStorage.getItem('catalogrecordstatus'),
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
			}},",
	'searchfield'=> array ('menuaccessid','menuname','description','menuurl','parentname','modulename')
));