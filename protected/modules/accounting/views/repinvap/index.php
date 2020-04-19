<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'invoiceapid',
	'formtype'=>'list',
	'wfapp'=>'appinvap',
	'url'=>Yii::app()->createUrl('accounting/repinvap/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/invoiceap/downpdf'),
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
			field:'companyid',
			title:'".GetCatalog('company') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return row.companycode;
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
			field:'pono',
			title:'".GetCatalog('pono') ."',
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
			field:'receiptdate',
			title:'".GetCatalog('receiptdate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'taxno',
			title:'".GetCatalog('taxno') ."',
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
	'searchfield'=> array ('invoiceapid','supplier','headernote','plantcode','pono','nobuktipotong','invoiceapno','sjsupplier','invoiceaptaxno','recordstatus'),
	'columndetails'=> array (
		array(
			'id'=>'invoiceaptax',
			'idfield'=>'invoiceaptaxid',
			'urlsub'=>Yii::app()->createUrl('accounting/invoiceap/indextax',array('grid'=>true)),
			'subs'=>"
				{field:'taxcode',title:'".GetCatalog('taxcode') ."',width:'200px'},
			",
		),
		array(
			'id'=>'invoiceapgr',
			'idfield'=>'invoiceapgrid',
			'urlsub'=>Yii::app()->createUrl('accounting/invoiceap/indexgr',array('grid'=>true)),
			'subs'=>"
				{field:'grno',title:'".GetCatalog('grno') ."',width:'150px'},
				{field:'sjsupplier',title:'".GetCatalog('sjsupplier') ."',width:'200px'},
				{field:'supir',title:'".GetCatalog('supir') ."',width:'200px'},
				{field:'headernote',title:'".GetCatalog('headernote') ."',width:'400px'},
			",
		),
		array(
			'id'=>'invoiceapdetail',
			'idfield'=>'invoiceapdetailid',
			'isnew'=>0,
			'ispurge'=>0,
			'urlsub'=>Yii::app()->createUrl('accounting/invoiceap/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'productcode',title:'".GetCatalog('productcode') ."',width:'250px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'250px'},
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
				{field:'price',title:'".GetCatalog('price') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.price);
					},width:'150px'},
				{field:'discount',title:'".GetCatalog('discount') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.discount);
					},width:'120px'},
				{field:'dpp',title:'".GetCatalog('dpp') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.dpp);
					},width:'150px'},
				{field:'total',title:'".GetCatalog('total') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.total);
					},width:'150px'},
				{field:'currencyname',title:'".GetCatalog('currencyname') ."',width:'350px'},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',align:'right',width:'60px'},
			",
		),
		array(
			'id'=>'invoiceapjurnal',
			'idfield'=>'invoiceapjurnalid',
			'isnew'=>0,
			'iswrite'=>0,
			'ispurge'=>0,
			'urlsub'=>Yii::app()->createUrl('accounting/invoiceap/indexjurnal',array('grid'=>true)),
			'subs'=>"
				{field:'accountname',title:'".GetCatalog('accountname') ."',width:'250px'},
				{field:'debit',title:'".GetCatalog('debit') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.debit);
					},width:'200px'},
				{field:'credit',title:'".GetCatalog('credit') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.credit);
					},width:'200px'},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',align:'right',width:'60px'},
				{field:'detailnote',title:'".GetCatalog('detailnote') ."',width:'450px'},
			",
		),
	),
));