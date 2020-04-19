<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'packinglistid',
	'formtype'=>'list',
	'url'=>Yii::app()->createUrl('inventory/reppackinglist/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/packinglist/downpdf'),
	'columns'=>"
		{
			field:'packinglistid',
			title:'".GetCatalog('packinglistid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatuscolor('apppacklist')."
		}},
		{
			field:'companyid',
			title:'".GetCatalog('company') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
			return row.companyname;
		}},
		{
			field:'plantid',
			title:'".GetCatalog('plant') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return row.plantcode;
		}},
		{
			field:'packinglistdate',
			title:'".GetCatalog('packinglistdate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'packinglistno',
			title:'".GetCatalog('packinglistno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'giheaderid',
			title:'".GetCatalog('giheader') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return row.gino;
		}},
		{
			field:'sono',
			title:'".GetCatalog('soheader') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'pocustno',
			title:'".GetCatalog('pocustno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'addressbookid',
			title:'".GetCatalog('customer') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
			return row.customername;
		}},
		{
			field:'supplierid',
			title:'".GetCatalog('ekspedisi') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
			return row.suppliername;
		}},
		{
			field:'addresstoid',
			title:'".GetCatalog('addressto') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
			return row.addressname;
		}},
		{
			field:'nomobil',
			title:'".GetCatalog('nomobil') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'sopir',
			title:'".GetCatalog('sopir') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'pebno',
			title:'".GetCatalog('pebno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'statusname',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},",
	'searchfield'=> array ('packinglistid','plantcode','packinglistno','gino','customername','addressname','productname'),
	'columndetails'=> array (
		array(
			'id'=>'packlistdetail',
			'idfield'=>'packlistdetailid',
			'urlsub'=>Yii::app()->createUrl('inventory/packinglist/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/packinglist/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('inventory/packinglist/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('inventory/packinglist/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('inventory/packinglist/purgedetail',array('grid'=>true)),
			'ispurge'=>1,'isnew'=>0,
			'subs'=>"
			{field:'packlistdetailid',title:'".GetCatalog('ID') ."',width:'80px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:'".GetCatalog('uom2code') ."',width:'80px'},
				{field:'gross',title:'".GetCatalog('gross') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'grossuomcode',title:'".GetCatalog('grossuomcode') ."',width:'80px'},
				{field:'net',title:'".GetCatalog('net') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'netuomcode',title:'".GetCatalog('netuomcode') ."',width:'80px'},
				{field:'nolot',title:'".GetCatalog('nolot') ."',width:'160px'},
				{field:'batchno',title:'".GetCatalog('batchno') ."',width:'160px'},
				{field:'certoano',title:'".GetCatalog('certoano') ."',width:'160px'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'300px'},
			",
		),
	),	
));