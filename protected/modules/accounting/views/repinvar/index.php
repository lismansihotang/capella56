<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'invoicearid',
	'formtype'=>'list',
	'wfapp'=>'appinvar',
	'url'=>Yii::app()->createUrl('accounting/repinvar/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/invoicear/downpdf'),
	'columns'=>"
		{
			field:'invoicearid',
			title:'".GetCatalog('invoicearid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appinvar')."
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
			field:'invoiceardate',
			title:'".GetCatalog('invoiceardate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'invoicearno',
			title:'".GetCatalog('invoicearno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
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
			field:'fullname',
			title:'".GetCatalog('customer') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return value;
		}},
		{
			field:'pocustno',
			title:'".GetCatalog('pocustno') ."',
			sortable: true,
			width:'350px',
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
	'rowstyler'=>"
		if (row.debit != row.credit){
			return 'background-color:blue;color:#fff;';
		}
	",
	'searchfield'=> array ('invoicearid','invoiceardate','customer','plantcode','invoicearno','sono','pocustno','invoiceartaxno','recordstatus'),
	'columndetails'=> array (
		array(
			'id'=>'invoicearsj',
			'idfield'=>'invoicearsjid',
			'urlsub'=>Yii::app()->createUrl('accounting/invoicear/indexsj',array('grid'=>true)),
			'subs'=>"
				{field:'invoicearsjid',title:'".GetCatalog('invoicearsjid') ."',align:'right',width:'60px'},
				{field:'gino',title:'".GetCatalog('gino') ."',width:'200px'},
			"
		),
		array(
			'id'=>'invoicearpl',
			'idfield'=>'invoicearplid',
			'urlsub'=>Yii::app()->createUrl('accounting/invoicear/indexpl',array('grid'=>true)),
			'subs'=>"
				{field:'invoicearplid',title:'".GetCatalog('invoicearplid') ."',align:'right',width:'60px'},
				{field:'packinglistno',title:'".GetCatalog('packinglistno') ."',width:'200px'},
			",
		),
		array(
			'id'=>'invoiceardetail',
			'idfield'=>'invoiceardetailid',
			'urlsub'=>Yii::app()->createUrl('accounting/invoicear/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'invoiceardetailid',title:'".GetCatalog('invoiceardetailid') ."',align:'right',width:'60px'},
				{field:'gino',title:'".GetCatalog('gino') ."',width:'250px'},
				{field:'productcode',title:'".GetCatalog('productcode') ."',width:'250px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'250px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},width:'150px'},
				{field:'uomcode2',title:'".GetCatalog('uomcode2') ."',width:'80px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},width:'150px'},
				{field:'uomcode3',title:'".GetCatalog('uomcode3') ."',width:'80px'},
				{field:'qty4',title:'".GetCatalog('qty4') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},width:'150px'},
				{field:'uomcode4',title:'".GetCatalog('uomcode4') ."',width:'80px'},
				{field:'price',title:'".GetCatalog('price') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},width:'120px'},
				{field:'discount',title:'".GetCatalog('discount') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},width:'120px'},
				{field:'dpp',title:'".GetCatalog('dpp') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},width:'120px'},
				{field:'total',title:'".GetCatalog('total') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.total);
					},width:'120px'},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'60px'},
				{field:'detailnote',title:'".GetCatalog('detailnote') ."',width:'350px'},
			",
		),
		array(
			'id'=>'invoiceartax',
			'idfield'=>'invoiceartaxid',
			'urlsub'=>Yii::app()->createUrl('accounting/invoicear/indextax',array('grid'=>true)),
			'subs'=>"
				{field:'invoiceartaxid',title:'".GetCatalog('invoiceartaxid') ."',align:'right',width:'60px'},
				{field:'taxcode',title:'".GetCatalog('taxcode') ."',width:'200px'},
			",
		),
		array(
			'id'=>'invoicearjurnal',
			'idfield'=>'invoicearjurnalid',
			'urlsub'=>Yii::app()->createUrl('accounting/invoicear/indexjurnal',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/invoicear/searchjurnal',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/invoicear/savejurnal',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/invoicear/savejurnal',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/invoicear/purgejurnal',array('grid'=>true)),
			'subs'=>"
				{field:'accountname',title:'".GetCatalog('accountname') ."',width:'250px'},
				{field:'debit',title:'".GetCatalog('debit') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},align:'right',width:'120px'},
				{field:'credit',title:'".GetCatalog('credit') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.credit);
					},align:'right',width:'120px'},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'60px'},
				{field:'detailnote',title:'".GetCatalog('detailnote') ."',width:'350px'},
			",
		),
	),
));