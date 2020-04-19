<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'expeditionapid',
	'formtype'=>'list',
	'wfapp'=>'appexpap',
	'url'=>Yii::app()->createUrl('accounting/repexpeditionap/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/expeditionap/downpdf'),
	'columns'=>"
		{
			field:'expeditionapid',
			title:'".GetCatalog('expeditionapid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appexpap')."
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
			field:'expeditionapdate',
			title:'".GetCatalog('expeditionapdate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'expeditionapno',
			title:'".GetCatalog('expeditionapno') ."',
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
			field:'expeditionname',
			title:'".GetCatalog('expeditionname') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return value;
		}},
		{
			field:'amount',
			title:'".GetCatalog('expeditionamount') ."',
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
	'searchfield'=> array ('expeditionapid','expeditionname','expeditionapno','plantcode','pono','supplier','recordstatus'),
	'columndetails'=> array (
		array(
			'id'=>'expeditionapgr',
			'idfield'=>'expeditionapgrid',
			'urlsub'=>Yii::app()->createUrl('accounting/expeditionap/indexgr',array('grid'=>true)),
			'subs'=>"
				{field:'expeditionapgrid',title:'".GetCatalog('ID') ."',width:'80px'},
				{field:'grno',title:'".GetCatalog('grno') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'200px'},
				{field:'qty',title:'".GetCatalog('qty') ."',width:'150px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'150px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',width:'150px'},
				{field:'uom2code',title:'".GetCatalog('uom2code') ."',width:'150px'},
				{field:'nilaibeban',title:'".GetCatalog('nilaibeban') ."',width:'150px'},
				{field:'headernote',title:'".GetCatalog('headernote') ."',width:'300px'},
			",
			),
		array(
			'id'=>'expeditionapjurnal',
			'idfield'=>'expeditionapjurnalid',
			'urlsub'=>Yii::app()->createUrl('accounting/expeditionap/indexjurnal',array('grid'=>true)),
			'subs'=>"
				{field:'cashbankno',title:'".GetCatalog('cashbankno') ."',width:'120px'},
				{field:'accountname',title:'".GetCatalog('accountname') ."',width:'250px'},
				{field:'amount',title:'".GetCatalog('amount') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.amount);
					},width:'120px'},
				{field:'currencyname',title:'".GetCatalog('currencyname') ."',width:'100px'},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',align:'right',width:'60px'},
				{field:'detailnote',title:'".GetCatalog('detailnote') ."',width:'350px'},
			",
		),
	),
));