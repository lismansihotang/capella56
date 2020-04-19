<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'productstockid',
	'formtype'=>'list',
	'isxls'=>1,
	'url'=>Yii::app()->createUrl('inventory/productstock/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/productstock/downpdf'),
	'downxls'=>Yii::app()->createUrl('inventory/productstock/downxls'),
	'columns'=>"
		{
			field:'productstockid',
			title: localStorage.getItem('catalogproductstockid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},		
		{
			field:'materialtypecode',
			title:localStorage.getItem('catalogmaterialtypecode'),
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.materialtypecode;
		}},
		{
			field:'materialgroupcode',
			title:localStorage.getItem('catalogmaterialgroupcode'),
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.materialgroupcode;
		}},
		{
			field:'productcode',
			title:localStorage.getItem('catalogproductcode'),
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productname',
			title:localStorage.getItem('catalogproductname'),
			width:'550px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'slocid',
			title:localStorage.getItem('catalogsloc'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.sloccode;
		}},
		{
			field:'storagebinid',
			title:localStorage.getItem('catalogstoragebin'),
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.description;
		}},
		{
			field:'qty',
			title:localStorage.getItem('catalogqty'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'unitofmeasureid',
			title:localStorage.getItem('catalogunitofmeasure'),
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uomcode;
		}},
		{
			field:'qty2',
			title:localStorage.getItem('catalogqty2'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'uom2code',
			title:localStorage.getItem('cataloguom2code'),
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uom2code;
		}},
		{
			field:'qty3',
			title:localStorage.getItem('catalogqty3'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'uom3code',
			title:localStorage.getItem('cataloguom3code'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uom3code;
		}},
		{
			field:'qtyinprogress',
			title:localStorage.getItem('catalogqtyinprogress'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'buyprice',
			title:localStorage.getItem('catalogbuyprice'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
	",
	'rowstyler'=>"
		if ((row.qty < row.minstock)) {
			return 'background-color:red;color:white;';
		} else 
		if ((row.qty >= row.minstock) && (row.qty <= row.orderstock)) {
			return 'background-color:yellow;color:black;';
		}	else 
		if ((row.qty > row.orderstock) && (row.qty <= row.maxstock)) {
			return 'background-color:green;color:black;';
		} else {
			return 'background-color:white;color:black;';
		}	
	",
	'searchfield'=> array ('productstockid','plantcode','productname','sloc','storagebin'),
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'productstockdetid',
			'urlsub'=>Yii::app()->createUrl('inventory/productstock/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'referenceno',title:localStorage.getItem('catalogreferenceno'),width:'150px'},
				{field:'transdate',title:localStorage.getItem('catalogtransdate'),width:'100px'},
				{field:'qty',title:localStorage.getItem('catalogqty'),
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:localStorage.getItem('cataloguomcode'),width:'80px'},
				{field:'qty2',title:localStorage.getItem('catalogqty2'),
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:localStorage.getItem('cataloguom2code'),width:'80px'},
				{field:'qty3',title:localStorage.getItem('catalogqty3'),
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom3code',title:localStorage.getItem('cataloguom3code'),width:'80px'},
				{field:'qty4',title:localStorage.getItem('catalogqty4'),
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom4code',title:localStorage.getItem('cataloguom4code'),width:'80px'},
			",
		),
	),	
));?>