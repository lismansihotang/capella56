<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'grreturid',
	'formtype'=>'list',
	'url'=>Yii::app()->createUrl('inventory/reportgrretur/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/grretur/downpdf'),
	'columns'=>"
		{
			field:'grreturid',
			title:'".GetCatalog('grreturid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appgrretur')."
		}},
		{
			field:'companyid',
			title:'".GetCatalog('company') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
			return row.companycode;
		}},
		{
			field:'plantid',
			title:'".GetCatalog('plantcode') ."',
			sortable: true,
			width:'125px',
			formatter: function(value,row,index){
			return row.plantcode;
		}},
		{
			field:'grreturdate',
			title:'".GetCatalog('grreturdate') ."',
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
			field:'poheaderid',
			title:'".GetCatalog('pono') ."',
			sortable: true,
			width:'130px',
			formatter: function(value,row,index){
			return row.pono;
		}},
		{
			field:'fullname',
			title:'".GetCatalog('supplier') ."',
			sortable: true,
			width:'300px',
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
			field:'recordstatusgrretur',
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
	'searchfield'=> array ('companyname','grreturdate','pono','plantcode','grreturno','supplier','headernote'),
	'columndetails'=> array (
		array(
			'id'=>'grreturdetail',
			'idfield'=>'grreturdetailid',
			'urlsub'=>Yii::app()->createUrl('inventory/grretur/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/grretur/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('inventory/grretur/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('inventory/grretur/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('inventory/grretur/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'grno',title:'".GetCatalog('grno') ."',width:'130px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty2',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty3',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom3code',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty4',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom4code',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'sloccode',title:'".GetCatalog('sloccode') ."',width:'60px'},
				{field:'description',title:'".GetCatalog('storagebin') ."',width:'100px'},
				{field:'SJsupplier',title:'".GetCatalog('SJsupplier') ."',width:'100px'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'100px'},
			",
		),
	),	
));