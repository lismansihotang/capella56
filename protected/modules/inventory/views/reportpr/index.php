<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'prheaderid',
	'formtype'=>'list',
	'isxls'=>1,
	'wfapp'=>'apppr',
	'url'=>Yii::app()->createUrl('inventory/reportpr/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/prheader/downpdf'),
	'downxls'=>Yii::app()->createUrl('inventory/prheader/downxls'),
	'columns'=>"
		{
			field:'prheaderid',
			title:'".GetCatalog('ID') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('apppr')."
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
			field:'prdate',
			title:'".GetCatalog('prdate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'formrequestno',
			title:'".GetCatalog('frno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'prno',
			title:'".GetCatalog('prno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'isjasa',
			title:'". GetCatalog('isjasa') ."',
			align:'center',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
				} else {
				return '';
				}
		}},
		{
			field:'slocfromid',
			title:'".GetCatalog('slocfrom') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return row.sloccode;
		}},
		{
			field:'requestedbyid',
			title:'".GetCatalog('requestedby') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.requestedbycode;
		}},
		{
			field:'description',
			title:'".GetCatalog('description') ."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
			return row.recordstatusname;
		}},",
	'searchfield'=> array ('prheaderid','plantcode','prdate','prno','frno','sloccode','requestedbycode','description','productraw','productjasa','productresult','recordstatus'),
	'columndetails'=> array (
		array(
			'id'=>'prraw',
			'idfield'=>'prrawid',
			'urlsub'=>Yii::app()->createUrl('inventory/prheader/indexraw',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/prheader/searchraw',array('grid'=>true)),
			'subs'=>"
				{field:'prrawid',title:'".getCatalog('ID') ."',width:'60px'},
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
				{field:'qtyoutpo',title:'".GetCatalog('qtyoutpo') ."',formatter: function(value,row,index){
					return formatnumber('',value);
				},width:'100px'},	
				{field:'qtyoutfpp',title:'".GetCatalog('qtyoutfpp') ."',formatter: function(value,row,index){
					return formatnumber('',value);
				},width:'100px'},		
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:'".GetCatalog('uom2code') ."',width:'80px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom3code',title:'".GetCatalog('uom3code') ."',width:'80px'},
				{field:'qty4',title:'".GetCatalog('qty4') ."',formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom4code',title:'".GetCatalog('uom4code') ."',width:'80px'},
				{field:'reqdate',title:'".GetCatalog('reqdate') ."',width:'100px'},
				{field:'sloccode',title:'".GetCatalog('slocto') ."',width:'100px'},
				{field:'mesincode',title:'".GetCatalog('mesin') ."',width:'150px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
		),
		array(
			'id'=>'prjasa',
			'idfield'=>'prjasaid',
			'urlsub'=>Yii::app()->createUrl('inventory/prheader/indexjasa',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/prheader/searchjasa',array('grid'=>true)),
			'subs'=>"
				{field:'prjasaid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qty',title:'".GetCatalog('qty') ."',formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'reqdate',title:'".GetCatalog('reqdate') ."',width:'100px'},
				{field:'sloccode',title:'".GetCatalog('sloccode') ."',width:'100px'},
				{field:'mesincode',title:'".GetCatalog('mesin') ."',width:'150px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
		),
		array(
			'id'=>'prresult',
			'idfield'=>'prresultid',
			'urlsub'=>Yii::app()->createUrl('inventory/prheader/indexresult',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/prheader/searchresult',array('grid'=>true)),
			'subs'=>"
				{field:'prresultid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qty',title:'".GetCatalog('qty') ."',formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:'".GetCatalog('uom2code') ."',width:'80px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom3code',title:'".GetCatalog('uom3code') ."',width:'80px'},
				{field:'qty4',title:'".GetCatalog('qty4') ."',formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom4code',title:'".GetCatalog('uom4code') ."',width:'80px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
		),
	),	
));