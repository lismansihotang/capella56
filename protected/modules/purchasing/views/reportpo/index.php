<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'poheaderid',
	'formtype'=>'list',
	'isxls'=>1,
	'wfapp'=>'apppo',
	'url'=>Yii::app()->createUrl('purchasing/reportpo/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('purchasing/poheader/downpdf'),
	'downxls'=>Yii::app()->createUrl('purchasing/poheader/downxls'),
	'columns'=>"
		{
			field:'poheaderid',
			title:'".GetCatalog('ID') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('apppo')."
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
			field:'podate',
			title:'".GetCatalog('podate') ."',
			sortable: true,
			width:'100px',
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
			field:'addressbookid',
			title:'".GetCatalog('supplier') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'billto',
			title:'".GetCatalog('billto') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return row.billto;
		}},
		{
			field:'addresstoname',
			title:'".GetCatalog('addresstoname') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return row.addresstoname;
		}},".((GetMenuAuth('currency')!=0)?"
		{
			field:'totprice',
			title:'".GetCatalog('totprice') ."',
			sortable: true,
			align: 'right',
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},":'')."
		{
			field:'paymentmethodid',
			title:'".GetCatalog('paymentmethod') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return row.paycode;
		}},
		{
			field:'isjasa',
			title:'". GetCatalog('isjasa') ."',
			align:'center',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}		
		}},
		{
			field:'isimport',
			title:'". GetCatalog('isimport') ."',
			align:'center',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}		
		}},
		{
			field:'headernote',
			title:'".GetCatalog('headernote') ."',
			sortable: true,
			width:'300px',
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
	'searchfield'=> array ('poheaderid','plantcode','podate','pono','supplier','prno','requestedby','headernote','productname','recordstatus'),
	'columndetails'=> array (
		array(
			'id'=>'podetail',
			'idfield'=>'podetailid',
			'urlsub'=>Yii::app()->createUrl('purchasing/poheader/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('purchasing/poheader/searchdetail',array('grid'=>true)),
			'subs'=>"
				{field:'prno',title:'".GetCatalog('prno') ."',width:'150px'},
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
				{field:'grqty',title:'".GetCatalog('grqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'qtysisa',title:'".GetCatalog('qtysisa') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:'".GetCatalog('uom2code') ."',width:'80px'},".((GetMenuAuth('currency') != 0)?"
				{field:'price',title:'".GetCatalog('price') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'60px'},
				{field:'totprice',title:'".GetCatalog('totprice') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				":'')."
				{field:'arrivedate',title:'".GetCatalog('arrivedate') ."',width:'100px'},
				{field:'toleransiup',title:'".GetCatalog('overdelvtol') ."',width:'120px'},
				{field:'toleransidown',title:'".GetCatalog('underdelvtol') ."',width:'130px'},
				{field:'sloccode',title:'".GetCatalog('sloc') ."',width:'100px'},
				{field:'requestedbycode',title:'".GetCatalog('requestedbycode') ."',width:'100px'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'100px'},
			",
		),
		array(
			'id'=>'pojasa',
			'idfield'=>'pojasaid',
			'urlsub'=>Yii::app()->createUrl('purchasing/poheader/indexpojasa',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('purchasing/poheader/searchpojasa',array('grid'=>true)),
			'subs'=>"
				{field:'prno',title:'".GetCatalog('prno') ."',width:'150px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'250px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'100px'},
				".((GetMenuAuth('currency') != 0)?"
				{field:'price',title:'".GetCatalog('price') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'80px'},
				{field:'totprice',title:'".GetCatalog('totprice') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				":'')."						
				{field:'reqdate',title:'".GetCatalog('reqdate') ."',width:'100px'},
				{field:'sloccode',title:'".GetCatalog('slocto') ."',width:'100px'},
				{field:'mesincode',title:'".GetCatalog('mesin') ."',width:'150px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
		),
		array(
			'id'=>'poresult',
			'idfield'=>'poresultid',
			'urlsub'=>Yii::app()->createUrl('purchasing/poheader/indexporesult',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('purchasing/poheader/searchporesult',array('grid'=>true)),
			'subs'=>"
				{field:'poresultid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'productcode',title:'".GetCatalog('productcode') ."',width:'100px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'250px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'150px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:'".GetCatalog('uom2code') ."',width:'150px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom3code',titl21e:'".GetCatalog('uom3code') ."',width:'150px'},
				{field:'qty4',title:'".GetCatalog('qty4') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom4code',title:'".GetCatalog('uom4code') ."',width:'150px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
		),
		array(
			'id'=>'taxpo',
			'idfield'=>'taxpoid',
			'urlsub'=>Yii::app()->createUrl('purchasing/poheader/indextaxpo',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('purchasing/poheader/searchtaxpo',array('grid'=>true)),
			'subs'=>"
				{field:'taxcode',title:'".GetCatalog('taxcode') ."',width:'300px'},
			"
		)
	),	
));
