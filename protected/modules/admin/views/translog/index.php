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
	'downdoc'=>Yii::app()->createUrl('admin/translog/downdoc'),
	'columns'=>"
		{
			field:'translogid',
			title:localStorage.getItem('catalogtranslogid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'username',
			title:localStorage.getItem('catalogusername'),
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'ippublic',
			title:localStorage.getItem('catalogippublic'),
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'iplocal',
			title:localStorage.getItem('catalogiplocal'),
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'createddate',
			title:localStorage.getItem('catalogcreateddate'),
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'useraction',
			title:localStorage.getItem('cataloguseraction'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'newdata',
			title:localStorage.getItem('catalognewdata'),
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
		field:'olddata',
		title:localStorage.getItem('catalogolddata'),
		sortable: true,
		formatter: function(value,row,index){
								return value;
							}},
		{
		field:'menuname',
		title:localStorage.getItem('catalogmenuname'),
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
							}},
		{
		field:'tableid',
		title:localStorage.getItem('catalogtableid'),
		width:'50px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
							}},",
	'searchfield'=> array ('translogid','useraction','username','createddate','newdata','olddata','menuname','tableid')
));