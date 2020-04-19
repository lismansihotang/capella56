<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'soheaderid',
	'formtype'=>'list',
	'isxls'=>1,
	'wfapp'=>'appso',
	'url'=>Yii::app()->createUrl('order/reportso/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('order/soheader/downpdf'),
	'downxls'=>Yii::app()->createUrl('order/soheader/downxls'),
	'columns'=>"
		{
			field:'soheaderid',
			title:'".GetCatalog('ID') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appso')."
		}},
		{
			field:'companyname',
			title:'".GetCatalog('company') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.companyname;
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
			field:'sodate',
			title:'".GetCatalog('sodate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'pocustdate',
			title:'".GetCatalog('pocustdate') ."',
			sortable: true,
			width:'100px',
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
			field:'addressbookid',
			title:'".GetCatalog('customer') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'pocustno',
			title:'".GetCatalog('pocustno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'employeeid',
			title:'".GetCatalog('sales') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.sales;
		}},
		{
			field:'addresstoid',
			title:'".GetCatalog('addressto') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.addresstoname;
		}},
		{
			field:'addresspayid',
			title:'".GetCatalog('addresspay') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.addresspayname;
		}},".((GetMenuAuth('currency') != 0)?"
		{
			field:'totprice',
			title:'".GetCatalog('totprice') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},":'')."
		{
			field:'isexport',
			title:'". GetCatalog('isexport') ."',
			align:'center',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}		
		}},
		{
			field:'issample',
			title:'". GetCatalog('issample') ."',
			align:'center',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}		
		}},
		{
			field:'isavalan',
			title:'". GetCatalog('isavalan') ."',
			align:'center',
			width:'80px',
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
	'searchfield'=> array ('soheaderid','plantcode','sodate','sono','customer','sales','pocustno',
		'headernote','productname','recordstatus'),
	'columndetails'=> array (
		array(
			'id'=>'taxso',
			'idfield'=>'taxsoid',
			'urlsub'=>Yii::app()->createUrl('order/soheader/indextaxso',array('grid'=>true)),
			'subs'=>"
				{field:'taxcode',title:'".GetCatalog('taxcode') ."',width:'300px'},
			"
		),		
		array(
			'id'=>'sodetail',
			'idfield'=>'sodetailid',
			'urlsub'=>Yii::app()->createUrl('order/soheader/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'120px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'400px'},
				{field:'delvdate',title:'".GetCatalog('delvdate') ."',width:'80px'},
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
				{field:'giqty',title:'".GetCatalog('giqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'sppqty',title:'".GetCatalog('sppqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'opqty',title:'".GetCatalog('qtyprod') ."',
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
				{field:'uom4code',title:'".GetCatalog('uom4code') ."',width:'80px'},"				
				.((GetMenuAuth('currency') != 0)?"
				{field:'price',title:'".GetCatalog('price') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'totprice',title:'".GetCatalog('totprice') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},":'')."
				{field:'bomversion',title:'".GetCatalog('bomversion') ."',width:'200px'},
				{field:'toleransi',title:'".GetCatalog('toleransi') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'sloccode',title:'".GetCatalog('sloc') ."',width:'100px'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'100px'},
			",
		),
	),	
));