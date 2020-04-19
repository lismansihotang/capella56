<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'productstockid',
	'formtype'=>'list',
	'iswrite'=>0,
	'ispurge'=>0,
	'isupload'=>0,
	'isxls'=>1,
	'url'=>Yii::app()->createUrl('inventory/productstock/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/productstock/downpdf'),
	'downxls'=>Yii::app()->createUrl('inventory/productstock/downxls'),
	'columns'=>"
		{
			field:'productstockid',
			title:'".getCatalog('productstockid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'materialtypecode',
			title:'".getCatalog('materialtypecode') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.materialtypecode;
		}},
		{
			field:'materialgroupcode',
			title:'".getCatalog('materialgroupcode') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.materialgroupcode;
		}},
		{
			field:'productname',
			title:'".getCatalog('productname') ."',
			width:'550px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'slocid',
			title:'".getCatalog('sloc') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.sloccode;
		}},
		{
			field:'storagebinid',
			title:'".getCatalog('storagebin') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.description;
		}},
		{
			field:'qty',
			title:'".getCatalog('qty') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'unitofmeasureid',
			title:'".getCatalog('unitofmeasure') ."',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uomcode;
		}},
		{
			field:'qty2',
			title:'".getCatalog('qty2') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'uom2code',
			title:'".getCatalog('uom2code') ."',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uom2code;
		}},
		{
			field:'qty3',
			title:'".getCatalog('qty3') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'uom3code',
			title:'".getCatalog('uom3code') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uom3code;
		}},
		{
			field:'qty4',
			title:'".getCatalog('qty4') ."',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'uom4code',
			title:'".getCatalog('uom4code') ."',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uom4code;
		}},
		{
			field:'qtyinprogress',
			title:'".getCatalog('qtyinprogress') ."',
			editor:'numberbox',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'buyprice',
			title:'".getCatalog('buyprice') ."',
			editor:'numberbox',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
	",
	'rowstyler'=>"
		if ((row.qty < row.minstock)) {
			return 'background-color:red;color:white;';
		} else 
		if ((row.qty >= row.minstock) && (row.qty <= row.orderstock)) {
			return 'background-color:yellow;color:black;';
		}	else 
		if ((row.qty > row.orderstock) && (row.qty <= row.maxstock)) {
			return 'background-color:green;color:black;';
		} else {
			return 'background-color:white;color:black;';
		}	
	",
	'searchfield'=> array ('productstockid','plantcode','productname','sloc','storagebin','referenceno'),
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'productstockdetid',
			'urlsub'=>Yii::app()->createUrl('inventory/productstock/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'referenceno',title:'".getCatalog('referenceno') ."',width:'150px'},
				{field:'transdate',title:'".getCatalog('transdate') ."',width:'100px'},
				{field:'averageprice',title:'".getCatalog('averageprice') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'130px'},
				{field:'qty',title:'".getCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".getCatalog('uomcode') ."',width:'80px'},
				{field:'qty2',title:'".getCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:'".getCatalog('uom2code') ."',width:'80px'},
				{field:'qty3',title:'".getCatalog('qty3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom3code',title:'".getCatalog('uom3code') ."',width:'80px'},
				{field:'qty4',title:'".getCatalog('qty4') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom4code',title:'".getCatalog('uom4code') ."',width:'80px'},
			",
		),
	),	
));