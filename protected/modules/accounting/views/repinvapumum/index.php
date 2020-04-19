<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'invoiceapid',
	'formtype'=>'list',
	'wfapp'=>'appinvap',
	'url'=>Yii::app()->createUrl('accounting/repinvapumum/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/invoiceapumum/downpdf'),
	'columns'=>"
		{
			field:'invoiceapid',
			title:'".GetCatalog('invoiceapid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appinvap')."
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
			field:'invoiceapdate',
			title:'".GetCatalog('invoiceapdate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'invoiceapno',
			title:'".GetCatalog('invoiceapno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'fullname',
			title:'".GetCatalog('supplier') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return value;
		}},
		{
			field:'invoiceaptaxno',
			title:'".GetCatalog('invoiceaptaxno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return value;
		}},
		{
			field:'nobuktipotong',
			title:'".GetCatalog('nobuktipotong') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return value;
		}},
		{
			field:'paymentmethodid',
			title:'".GetCatalog('paymentmethod') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return row.paycode;
		}},
		{
			field:'duedate',
			title:'".GetCatalog('duedate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'headernote',
			title:'".GetCatalog('headernote') ."',
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatusname',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},",
	'rowstyler'=>"
		if (row.debit != row.credit){
			return 'background-color:blue;color:#fff;';
		}
	",
	'searchfield'=> array ('invoiceapid','plantcode','supplier','invoiceapdate','pono','nobuktipotong','invoiceapno','invoiceaptaxno','headernote','recordstatus'),
	'columndetails'=> array (
		array(
			'id'=>'invoiceapumumtax',
			'idfield'=>'invoiceaptaxid',
			'urlsub'=>Yii::app()->createUrl('accounting/invoiceapumum/indextax',array('grid'=>true)),
			'subs'=>"
				{field:'invoiceaptaxid',title:'".GetCatalog('invoiceaptaxid') ."',align:'right',width:'60px'},
				{field:'taxcode',title:'".GetCatalog('taxcode') ."',width:'200px'},
			",
		),
		array(
			'id'=>'invoiceapumumdetail',
			'idfield'=>'invoiceapdetailid',
			'urlsub'=>Yii::app()->createUrl('accounting/invoiceapumum/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'invoiceapdetailid',title:'".GetCatalog('invoiceapdetailid') ."',align:'right',width:'60px'},
				{field:'productcode',title:'".GetCatalog('productcode') ."',width:'100px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'250px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'150px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},
				{field:'uom2code',title:'".GetCatalog('uom2code') ."',width:'150px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},
				{field:'uom3code',title:'".GetCatalog('uom3code') ."',width:'150px'},
				{field:'qty4',title:'".GetCatalog('qty4') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},
				{field:'uom4code',title:'".GetCatalog('uom4code') ."',width:'150px'},
				{field:'price',title:'".GetCatalog('price') ."',
					formatter: function(value,row,index){
						return row.symbol + ' ' + row.price;
					},align:'right',width:'120px'},
				{field:'discount',title:'".GetCatalog('discount') ."',
					formatter: function(value,row,index){
						return row.symbol + ' ' + row.discount;
					},align:'right',width:'120px'},
				{field:'dpp',title:'".GetCatalog('dpp') ."',
					formatter: function(value,row,index){
						return row.symbol + ' ' + row.dpp;
					},align:'right',width:'120px'},
				{field:'total',title:'".GetCatalog('total') ."',
					formatter: function(value,row,index){
						return row.symbol + ' ' + row.total;
					},align:'right',width:'120px'},
				{field:'currencyname',title:'".GetCatalog('currencyname') ."',width:'350px'},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',align:'right',width:'60px'},
			",
		),
		array(
			'id'=>'invoiceapumumjurnal',
			'idfield'=>'invoiceapjurnalid',
			'isnew'=>0,
			'iswrite'=>0,
			'ispurge'=>0,
			'urlsub'=>Yii::app()->createUrl('accounting/invoiceapumum/indexjurnal',array('grid'=>true)),
			'subs'=>"
				{field:'accountname',title:'".GetCatalog('accountname') ."',width:'250px'},
				{field:'debit',title:'".GetCatalog('debit') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.debit);
					},width:'120px'},
				{field:'credit',title:'".GetCatalog('credit') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.credit);
					},width:'120px'},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',align:'right',width:'60px'},
				{field:'detailnote',title:'".GetCatalog('detailnote') ."',width:'350px'},
			",
		),
	),
));