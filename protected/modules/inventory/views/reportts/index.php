<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'transstockid',
	'formtype'=>'list',
	'isxls'=>1,
	'wfapp'=>'apptsall',
	'url'=>Yii::app()->createUrl('inventory/reportts/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/transstock/downpdf'),
	'downxls'=>Yii::app()->createUrl('inventory/transstock/downxls'),
	'columns'=>"
		{
			field:'transstockid',
			title:'".GetCatalog('ID') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('apptsall')."
		}},
		{
			field:'companyname',
			title:'".GetCatalog('company') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'plantid',
			title:'".GetCatalog('plantcode') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.plantcode;
		}},
		{
			field:'fullname',
			title:'".GetCatalog('addressbook') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'transstockdate',
			title:'".GetCatalog('transstockdate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'transstockno',
			title:'".GetCatalog('transstockno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'formrequestid',
			title:'".GetCatalog('frno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.formrequestno;
		}},
		{
			field:'productplanno',
			title:'".GetCatalog('productplanno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.productplanno;
		}},
		{
			field:'sono',
			title:'".GetCatalog('sono') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.sono;
		}},
		{
			field:'slocfromid',
			title:'".GetCatalog('slocfrom') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.slocfromcode;
		}},
		{
			field:'sloctoid',
			title:'".GetCatalog('slocto') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.sloctocode;
		}},
		{
			field:'headernote',
			title:'".GetCatalog('headernote') ."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return row.recordstatusname;
		}},",
	'searchfield'=> array ('transstockid','plantcode','transstockdate','transstockno','formrequestno','productplanno','sono','slocfromcode','sloctocode','customername','headernote','recordstatus'),
	'columndetails'=> array (
		array(
			'id'=>'transstockdet',
			'idfield'=>'transstockdetid',
			'urlsub'=>Yii::app()->createUrl('inventory/transstock/indexdetail',array('grid'=>true)),
			'ispurge'=>1,'isnew'=>0,
			'subs'=>"
				{field:'transstockdetid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qty',title:'".GetCatalog('qty') ."',formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:'".GetCatalog('uom2code') ."',width:'80px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom3code',title:'".GetCatalog('uom3code') ."',width:'80px'},
				{field:'qty4',title:'".GetCatalog('qty4') ."',formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom4code',title:'".GetCatalog('uom4code') ."',width:'80px'},
				{field:'rakasal',title:'".GetCatalog('rakasal') ."',width:'100px'},
				{field:'namamesin',title:'".GetCatalog('mesin') ."',width:'150px'},
				{field:'lotno',title:'".GetCatalog('lotno') ."',width:'180px'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'300px'},
			",
		),
	),	
));