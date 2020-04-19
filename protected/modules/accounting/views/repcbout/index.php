<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'cashbankoutid',
	'formtype'=>'list',
	'wfapp'=>'appcbout',
	'url'=>Yii::app()->createUrl('accounting/repcbout/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/cashbankout/downpdf'),
	'columns'=>"
		{
			field:'cashbankoutid',
			title:'".GetCatalog('cashbankoutid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appcbout')."
		}},
		{
			field:'companyid',
			title:'".GetCatalog('company') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.companycode;
		}},
		{
			field:'accountid',
			title:'".GetCatalog('accountname') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return row.accountname;
		}},
		{
			field:'cashbankoutdate',
			title:'".GetCatalog('cashbankoutdate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'cashbankoutno',
			title:'".GetCatalog('cashbankoutno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'reqpayid',
			title:'".GetCatalog('reqpay') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.reqpayno;
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
	'searchfield'=> array ('cashbankoutid','companycode','cashbankoutno','cashbankoutdate','reqpayno','supplier','pono','invoiceapno','headernote','acccodeheader','accnameheader',
		'acccodedetail','accnamedetail','recordstatus'),
	'columndetails'=> array (
		array(
			'id'=>'cashbankoutdetail',
			'idfield'=>'cashbankoutdetailid',
			'urlsub'=>Yii::app()->createUrl('accounting/cashbankout/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'cashbankoutdetailid',title:'".GetCatalog('cashbankoutdetailid') ."',align:'right',width:'60px'},
				{field:'invoiceapno',title:'".GetCatalog('invoiceapno') ."',width:'120px'},
				{field:'pono',title:'".GetCatalog('pono') ."',width:'150px'},
				{field:'fullname',title:'".GetCatalog('supplier') ."',width:'300px'},
				{field:'amount',title:'".GetCatalog('amount') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.amount);
					},
				},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',align:'right',width:'60px'},
				{field:'detailnote',title:'".GetCatalog('detailnote') ."',width:'200px'},
			",
		),
		array(
			'id'=>'cashbankoutjurnal',
			'idfield'=>'cashbankoutjurnalid',
			'urlsub'=>Yii::app()->createUrl('accounting/cashbankout/indexjurnal',array('grid'=>true)),
			'subs'=>"
				{field:'accountname',title:'".GetCatalog('accountname') ."',width:'250px'},
				{field:'debit',title:'".GetCatalog('debit') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value)
					},width:'120px'},
				{field:'credit',title:'".GetCatalog('credit') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value)
					},width:'120px'},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value)
					},width:'60px'},
				{field:'detailnote',title:'".GetCatalog('detailnote') ."',width:'350px'},
			",
		),
	),
));