<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'expeditionarid',
	'formtype'=>'list',
	'wfapp'=>'appexpar',
	'url'=>Yii::app()->createUrl('accounting/expeditionar/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/expeditionar/downpdf'),
	'columns'=>"
		{
			field:'expeditionarid',
			title:'".GetCatalog('expeditionarid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appexpar')."
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
			field:'expeditionardate',
			title:'".GetCatalog('expeditionardate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'expeditionarno',
			title:'".GetCatalog('expeditionarno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'sono',
			title:'".GetCatalog('sono') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return value;
		}},
		{
			field:'fullname',
			title:'".GetCatalog('customer') ."',
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
	'searchfield'=> array ('expeditionarid','expeditionname','expeditionarno','plantcode','sono','customer','recordstatus'),
	'columndetails'=> array (
		array(
			'id'=>'expeditionargi',
			'idfield'=>'expeditionargiid',
			'urlsub'=>Yii::app()->createUrl('accounting/expeditionar/indexgr',array('grid'=>true)),
			'subs'=>"
				{field:'expeditionargiid',title:'".GetCatalog('ID') ."',width:'80px'},
				{field:'gino',title:'".GetCatalog('gino') ."',width:'150px'},
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
			'id'=>'expeditionarjurnal',
			'idfield'=>'expeditionarjurnalid',
			'urlsub'=>Yii::app()->createUrl('accounting/expeditionar/indexjurnal',array('grid'=>true)),
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