<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'catalogsysid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/catalogsys/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/catalogsys/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/catalogsys/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/catalogsys/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/catalogsys/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/catalogsys/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/catalogsys/downxls'),
	'downdoc'=>Yii::app()->createUrl('admin/catalogsys/downdoc'),
	'columns'=>"
		{
		field:'catalogsysid',
		title:localStorage.getItem('catalogcatalogsysid'),
		sortable: true,
		width:'80px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'languageid',
		title:localStorage.getItem('cataloglanguage'),
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'languageid',
				textField:'languagename',
				url:'".$this->createUrl('language/index',array('grid'=>true,'combo'=>true))."',
				fitColumns:true,
				required:true,
				loadMsg: localStorage.getItem('catalogpleasewait'),
				columns:[[
					{field:'languageid',title:localStorage.getItem('cataloglanguageid'),width:'50px'},
					{field:'languagename',title:localStorage.getItem('cataloglanguagename'),width:'200px'},
				]]
			}	
		},
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
			return row.languagename;
	}},
	{
		field:'catalogname',
		title:localStorage.getItem('catalogcatalogname'),
		editor: {
			type:'text',
			options:{
				required:true
			}
		},
		width:'300px',
		sortable: true,
		formatter: function(value,row,index){
					return value;
	}},
	{
		field:'catalogval',
		title:localStorage.getItem('catalogcatalogval'),
		editor: {
			type:'text',
			options:{
				required:true
			}
		},
		width:'450px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
		}},",
	'searchfield'=> array ('catalogsysid','languagename','catalogname','catalogval')
));