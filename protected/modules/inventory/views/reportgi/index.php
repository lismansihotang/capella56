<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'giheaderid',
	'formtype'=>'list',
	'isxls'=>1,
	'wfapp'=>'appgi',
	'url'=>Yii::app()->createUrl('inventory/reportgi/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/giheader/downpdf'),
	'downxls'=>Yii::app()->createUrl('inventory/giheader/downxls'),
	'columns'=>"
		{
			field:'giheaderid',
			title:'".GetCatalog('giheaderid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appgi')."
		}},
		{
			field:'plantid',
			title:'".GetCatalog('plant') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
			return row.plantcode;
		}},
		{
			field:'gidate',
			title:'".GetCatalog('gidate') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'gino',
			title:'".GetCatalog('gino') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'addressbookid',
			title:'".GetCatalog('customer') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
			return row.customername;
		}},
		{
			field:'soheaderid',
			title:'".GetCatalog('sono') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
			return row.sono;
		}},
		{
			field:'pocustno',
			title:'".GetCatalog('pocustno') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'supplierid',
			title:'".GetCatalog('ekspedisi') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
			return row.suppliername;
		}},
		{
			field:'nomobil',
			title:'".GetCatalog('nomobil') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'sopir',
			title:'".GetCatalog('sopir') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'addresstoid',
			title:'".GetCatalog('addressto') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
			return row.addressname;
		}},
		{
			field:'pebno',
			title:'".GetCatalog('pebno') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'headernote',
			title:'".GetCatalog('headernote') ."',
			sortable: true,
			width:'100px',
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
	'searchfield'=> array ('giheaderid','plantcode','gidate','gino','ekspedisi','sono','customer','sopir','pocustno','nomobil','addressname','productname','headernote','recordstatus'),
	'columndetails'=> array (
		array(
			'id'=>'gidetail',
			'idfield'=>'gidetailid',
			'urlsub'=>Yii::app()->createUrl('inventory/giheader/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'gidetailid',title:'".GetCatalog('ID') ."',width:'80px'},
				{field:'sodetailid',title:'".GetCatalog('ID SO') ."',width:'80px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'300px'},
				{field:'stock',title:'".GetCatalog('stock') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
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
				{field:'qty3',title:'".GetCatalog('qty3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom3code',title:'".GetCatalog('uom3code') ."',width:'80px'},
				{field:'qty4',title:'".GetCatalog('qty4') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom4code',title:'".GetCatalog('uom4code') ."',width:'80px'},
				{field:'sloccode',title:'".GetCatalog('sloc') ."',width:'100px'},
				{field:'storagebinto',title:'".GetCatalog('storagebin') ."',width:'100px'},
				{field:'lotno',title:'".GetCatalog('lotno') ."',width:'100px'},
				{field:'certoano',title:'".GetCatalog('certoano') ."',width:'100px'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'100px'},
			",
		)
	),	
));