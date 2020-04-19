<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'bsheaderid',
	'formtype'=>'list',
	'isxls'=>1,
	'wfapp'=>'appbs',
	'url'=>Yii::app()->createUrl('inventory/reportbs/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/bsheader/downpdf'),
	'downxls'=>Yii::app()->createUrl('inventory/bsheader/downxls'),
	'columns'=>"
		{
			field:'bsheaderid',
			title:'".GetCatalog('bsheaderid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appbs')."
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
			field:'bsdate',
			title:'".GetCatalog('bsdate') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'bsheaderno',
			title:'".GetCatalog('bsheaderno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'slocid',
			title:'".GetCatalog('sloc') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.sloccode;
		}},
		{
			field:'recordstatusbsheader',
			title:'".getCatalog('recordstatus') ."',
			align:'left',
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.recordstatusbsheader;
		}},
	",
	'searchfield'=> array ('bsheaderid','plantcode','bsdate','bsheaderno','sloccode','productname','headernote','recordstatus'),
	'columndetails'=> array (
		array(
			'id'=>'bsdetail',
			'idfield'=>'bsdetailid',
			'urlsub'=>Yii::app()->createUrl('inventory/bsheader/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'bsdetailid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'delvdate',title:'".GetCatalog('delvdate') ."',width:'100px'},
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
				{field:'description',title:'".GetCatalog('description') ."',width:'150px'},
				{field:'materialstatusname',title:'".GetCatalog('materialstatusname') ."',width:'150px'},
				{field:'ownershipname',title:'".GetCatalog('ownershipname') ."',width:'100px'},
				{field:'lotno',title:'".GetCatalog('lotno') ."',width:'100px'},
				{field:'buyprice',title:'".GetCatalog('buyprice') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'buydate',title:'".GetCatalog('buydate') ."',width:'100px'},
				{field:'currencyname',title:'".GetCatalog('currencyname') ."',width:'150px'},
				{field:'currencyrate',title:'".GetCatalog('currencyrate') ."',width:'150px'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'100px'},
			",
		),
	),	
));