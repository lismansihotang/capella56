<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'cashbankid',
	'formtype'=>'list',
	'wfapp'=>'appcb',
	'url'=>Yii::app()->createUrl('accounting/repcb/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/cashbank/downpdf'),
	'columns'=>"
		{
			field:'cashbankid',
			title:'".GetCatalog('cashbankid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appcb')."
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
			field:'cashbankdate',
			title:'".GetCatalog('cashbankdate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
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
			field:'cashbankno',
			title:'".GetCatalog('cashbankno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'isin',
			title:'". GetCatalog('isin') ."',
			align:'center',
			width:'90px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}		
		}},
		{
			field:'addressbookid',
			title:'".GetCatalog('addressbook') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'chequeid',
			title:'".GetCatalog('bilyetgirono') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return row.bilyetgirono;
		}},
		{
			field:'amount',
			title:'".GetCatalog('amount') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
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
	'searchfield'=> array ('cashbankid','cashbankdate','addressbook','plantcode','cashbankno','accountname','headernote','recordstatus'),
	'columndetails'=> array (
		array(
			'id'=>'tax',
			'idfield'=>'cashbanktaxid',
			'urlsub'=>Yii::app()->createUrl('accounting/cashbank/indextax',array('grid'=>true)),
			'subs'=>"
				{field:'cashbanktaxid',title:'".GetCatalog('cashbanktaxid') ."',align:'right',width:'60px'},
				{field:'taxcode',title:'".GetCatalog('taxcode') ."',width:'200px'},
				{field:'nobuktipotong',title:'".GetCatalog('nobuktipotong') ."',width:'200px'},
			",		
		),
		array(
			'id'=>'detail',
			'idfield'=>'cashbankdetailid',
			'urlsub'=>Yii::app()->createUrl('accounting/cashbank/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/cashbank/searchdetail',array('grid'=>true)),
			'subs'=>"
				{field:'cashbankdetailid',title:'".GetCatalog('cashbankdetailid') ."',align:'right',width:'60px'},
				{field:'sloccode',title:'".GetCatalog('sloccode') ."',width:'250px'},
				{field:'accountname',title:'".GetCatalog('accountname') ."',width:'250px'},
				{field:'amount',title:'".GetCatalog('amount') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol, row.amount);
					},
				},
				{field:'currencyname',title:'".GetCatalog('currencyname') ."',width:'150px'},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',align:'right',width:'60px'},
			",
		),
	),
));