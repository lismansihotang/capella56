<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'notagrrid',
	'formtype'=>'list',
	'wfapp'=>'appnotagrr',
	'url'=>Yii::app()->createUrl('accounting/repnotagrr/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/notagrr/downpdf'),
	'columns'=>"
		{
			field:'notagrrid',
			title:'".GetCatalog('notagrrid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appnotagrr')."
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
			field:'notagrrdate',
			title:'".GetCatalog('notagrrdate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'notagrrno',
			title:'".GetCatalog('notagrrno') ."',
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
			field:'invpiceaptaxno',
			title:'".GetCatalog('invpiceaptaxno') ."',
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
			field:'grreturno',
			title:'".GetCatalog('grreturno') ."',
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
	'searchfield'=> array ('notagrrid','plantcode','notagrrdate','supplier','pono','grreturno','notagrrno','headernote','recordstatus'),
	'columndetails'=> array (
		array(
			'id'=>'notagrrtax',
			'idfield'=>'notagrrtaxid',
			'urlsub'=>Yii::app()->createUrl('accounting/notagrr/indextax',array('grid'=>true)),
			'subs'=>"
				{field:'taxcode',title:'".GetCatalog('taxcode') ."',width:'200px'},
			",
		),
		array(
			'id'=>'notagrrgrr',
			'idfield'=>'notagrrgrrid',
			'urlsub'=>Yii::app()->createUrl('accounting/notagrr/indexgrr',array('grid'=>true)),
			'subs'=>"
				{field:'grreturno',title:'".GetCatalog('grreturno') ."',width:'150px'},
				{field:'headernote',title:'".GetCatalog('headernote') ."',width:'400px'},
			",
		),
		array(
			'id'=>'notagrrdetail',
			'idfield'=>'notagrrdetailid',
			'urlsub'=>Yii::app()->createUrl('accounting/notagrr/indexdetail',array('grid'=>true)),
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
	),
));