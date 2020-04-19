<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'gireturid',
	'formtype'=>'list',
	'wfapp'=>'appgiretur',
	'url'=>Yii::app()->createUrl('inventory/reportgiretur/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/giretur/downpdf'),
	'columns'=>"
		{
			field:'gireturid',
			title:'".GetCatalog('gireturid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appgiretur')."
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
			field:'gireturdate',
			title:'".GetCatalog('gireturdate') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'gireturno',
			title:'".GetCatalog('gireturno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'giheaderid',
			title:'".GetCatalog('gino') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return row.gino;
		}},
		{
			field:'addressbookid',
			title:'".GetCatalog('customer') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'headernote',
			title:'".GetCatalog('headernote') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatusgiretur',
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
	'searchfield'=> array ('gireturid','plantcode','gino','gireturdate','gireturno','customer','headernote','recordstatus'),
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'gireturdetailid',
			'urlsub'=>Yii::app()->createUrl('inventory/giretur/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'grno',title:'".GetCatalog('grno') ."',width:'100px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'100px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:'".GetCatalog('uom2code') ."',width:'100px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom3code',title:'".GetCatalog('uom3code') ."',width:'100px'},
				{field:'qty4',title:'".GetCatalog('qty4') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom4code',title:'".GetCatalog('uom4code') ."',width:'100px'},
				{field:'sloccode',title:'".GetCatalog('sloccode') ."',width:'60px'},
				{field:'description',title:'".GetCatalog('storagebin') ."',width:'100px'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'100px'},
			",
		),
	),	
));