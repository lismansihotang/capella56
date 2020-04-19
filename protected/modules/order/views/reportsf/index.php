<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'sfheaderid',
	'formtype'=>'list',
	'isxls'=>1,
	'url'=>Yii::app()->createUrl('order/reportsf/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('order/sfheader/downpdf'),
	'downxls'=>Yii::app()->createUrl('order/sfheader/downxls'),
	'columns'=>"
		{
			field:'sfheaderid',
			title:'".GetCatalog('ID') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appsf')."
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
			field:'sfdate',
			title:'".GetCatalog('sfdate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'sfno',
			title:'".GetCatalog('sono') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'addressbookid',
			title:'".GetCatalog('customer') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'addresstoid',
			title:'".GetCatalog('addressto') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.addresstoname;
		}},
		{
			field:'addresspayid',
			title:'".GetCatalog('addresspay') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.addresspayname;
		}},".((GetMenuAuth('currency') != 0)?"
		{
			field:'totprice',
			title:'".GetCatalog('totprice') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},":'')."
		{
			field:'isexport',
			title:'". GetCatalog('isexport') ."',
			align:'center',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}		
		}},
		{
			field:'issample',
			title:'". GetCatalog('issample') ."',
			align:'center',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}		
		}},
		{
			field:'isavalan',
			title:'". GetCatalog('isavalan') ."',
			align:'center',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}		
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
	'searchfield'=> array ('sfheaderid','plantcode','sfdate','sfno','customer','pocustno','headernote','productname'),
	'columndetails'=> array (
		array(
			'id'=>'taxso',
			'idfield'=>'taxsoid',
			'urlsub'=>Yii::app()->createUrl('order/sfheader/indextaxso',array('grid'=>true)),
			'subs'=>"
				{field:'taxcode',title:'".GetCatalog('taxcode') ."',width:'300px'},
			"
		),		
		array(
			'id'=>'sfdetail',
			'idfield'=>'sfdetailid',
			'urlsub'=>Yii::app()->createUrl('order/sfheader/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'120px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'400px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:'".GetCatalog('uom2code') ."',width:'80px'},".((GetMenuAuth('currency') != 0)?"
				{field:'price',title:'".GetCatalog('price') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'totprice',title:'".GetCatalog('totprice') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},":'')."
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'100px'},
			",
		),
	),	
));