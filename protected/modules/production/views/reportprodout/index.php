<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'productoutputid',
	'formtype'=>'list',
	'isxls'=>1,
	'wfapp'=>'appop',
	'url'=>Yii::app()->createUrl('production/reportprodout/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('production/productoutput/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/productoutput/downxls'),
	'columns'=>"
		{
			field:'productoutputid',
			title:'".GetCatalog('ID') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appop')."
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
			field:'productoutputdate',
			title:'".GetCatalog('productoutputdate') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productoutputno',
			title:'".GetCatalog('productoutputno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productplanid',
			title:'".GetCatalog('productplanno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.productplanno;
		}},
		{
			field:'soheaderid',
			title:'".GetCatalog('sono') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.sono;
		}},
		{
			field:'addressbookid',
			title:'".GetCatalog('customer') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'headernote',
			title:'".GetCatalog('headernote') ."',
			sortable: true,
			width:'250px',
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
	'searchfield'=> array ('productoutputid','plantcode','productoutputno','productoutputdate','sono','productplanno','customer','productname','headernote',
		'processprdname','sloccode','recordstatus'),
	'columndetails'=> array (
		array(
			'id'=>'productoutputfg',
			'idfield'=>'productoutputfgid',
			'urlsub'=>Yii::app()->createUrl('production/productoutput/indexfg',array('grid'=>true)),
			'subs'=>"
				{field:'productoutputfgid',title:'".getCatalog('ID FG') ."',width:'60px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qtystock',title:'".GetCatalog('qtystock') ."',formatter: function(value,row,index){
					if (row.stockcount == '1') {
					return '<div style=\"background-color:cyan\">'+formatnumber('',value)+'</div>';
					} else {
						return formatnumber('',value);
					}
				},width:'100px'},
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
				{field:'fullname',title:'".GetCatalog('fullname') ."',width:'100px'},
				{field:'processprdname',title:'".GetCatalog('processprdname') ."',width:'100px'},
				{field:'namamesin',title:'".GetCatalog('mesin') ."',width:'150px'},
				{field:'sloccode',title:'".GetCatalog('sloccode') ."',width:'100px'},
				{field:'storagebinname',title:'".GetCatalog('storagebindesc') ."',width:'100px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'100px'}
			",
		),
		array(
			'id'=>'productoutputdetail',
			'idfield'=>'productoutputdetailid',
			'urlsub'=>Yii::app()->createUrl('production/productoutput/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'productoutputdetailid',title:'".GetCatalog('productoutputdetailid') ."',width:'60px'},
				{field:'productoutputfgid',title:'".GetCatalog('ID FG') ."',width:'60px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qtystock',title:'".GetCatalog('qtystock') ."',formatter: function(value,row,index){
					if (row.stockcount == '1') {
					return '<div style=\"background-color:cyan\">'+formatnumber('',value)+'</div>';
					} else {
						return formatnumber('',value);
					}
				},width:'100px'},
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
				{field:'bomversion',title:'".GetCatalog('bomversion') ."',width:'200px'},
				{field:'slocfromcode',title:'".GetCatalog('slocfromcode') ."',width:'150px'},
				{field:'sloctocode',title:'".GetCatalog('sloctocode') ."',width:'150px'},
				{field:'storagebinto',title:'".GetCatalog('storagebinto') ."',width:'150px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'250px'},
			",
		),
		array(
			'id'=>'productoutputwaste',
			'idfield'=>'productoutputwasteid',
			'urlsub'=>Yii::app()->createUrl('production/productoutput/indexwaste',array('grid'=>true)),
			'subs'=>"
				{field:'productoutputwasteid',title:'".GetCatalog('ID Detail') ."',width:'50px'},
				{field:'productoutputfgid',title:'".GetCatalog('ID FG') ."',width:'50px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qtystock',title:'".GetCatalog('qtystock') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
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
				{field:'sloccode',title:'".GetCatalog('sloctocode') ."',width:'150px'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'250px'},
			",
			),
		array(
			'id'=>'productoutputemployee',
			'idfield'=>'productoutputemployeeid',
			'urlsub'=>Yii::app()->createUrl('production/productoutput/indexoperator',array('grid'=>true)),
			'subs'=>"
				{field:'productoutputfgid',title:'".GetCatalog('ID FG') ."',width:'80px'},
				{field:'fullname',title:'".GetCatalog('fullname') ."',width:'200px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
		),
		array(
			'id'=>'productoutputjurnal',
			'idfield'=>'productoutputjurnalid',
			'urlsub'=>Yii::app()->createUrl('production/productoutput/indexjurnal',array('grid'=>true)),
			'subs'=>"
				{field:'accountname',title:'".GetCatalog('accountname') ."',width:'300px'},
				{field:'debit',title:'".GetCatalog('debit') ."',width:'150px',
				formatter: function(value,row,index){
						return formatnumber('',value);
					}},
				{field:'credit',title:'".GetCatalog('credit') ."',width:'150px',
				formatter: function(value,row,index){
						return formatnumber('',value);
					}},
			",
		),
	),	
));