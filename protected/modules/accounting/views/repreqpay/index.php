<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'reqpayid',
	'formtype'=>'list',
	'wfapp'=>'appreqpay',
	'url'=>Yii::app()->createUrl('accounting/repreqpay/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/reqpay/downpdf'),
	'columns'=>"
		{
			field:'reqpayid',
			title:'".GetCatalog('reqpayid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appreqpay')."
		}},
		{
			field:'companyid',
			title:'".GetCatalog('companycode') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return row.companycode;
		}},
		{
			field:'reqpaydate',
			title:'".GetCatalog('reqpaydate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'reqpayno',
			title:'".GetCatalog('reqpayno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'addressbookid',
			title:'".GetCatalog('supplier') ."',
			sortable: true,
			width:'250px',
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
	'searchfield'=> array ('reqpayid','companycode','reqpaydate','reqpayno','invoiceapno','supplier','headernote','recordstatus'),
	'columndetails'=> array (
		array(
			'id'=>'reqpaydetail',
			'idfield'=>'reqpayinvid',
			'urlsub'=>Yii::app()->createUrl('accounting/reqpay/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/reqpay/searchdetail',array('grid'=>true)),
			'subs'=>"
				{field:'invoiceapno',title:'".GetCatalog('invoiceapno') ."',width:'100px'},
				{field:'duedate',title:'".GetCatalog('duedate') ."',width:'100px'},
				{field:'amount',title:'".GetCatalog('amount') ."',
					formatter: function(value,row,index){
						return row.symbol + ' ' + row.amount;
					},align:'right',width:'120px'},
				{field:'currencyname',title:'".GetCatalog('currencyname') ."',width:'100px'},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',align:'right',width:'100px'},
				{field:'headernote',title:'".GetCatalog('headernote') ."',width:'150px'},
			",
		),
	),	
));