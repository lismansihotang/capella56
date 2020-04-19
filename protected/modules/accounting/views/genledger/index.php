<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'genledgerid',
	'formtype'=>'master',
	'iswrite'=>0,
	'ispurge'=>0,
	'isupload'=>1,
	'url'=>Yii::app()->createUrl('accounting/genledger/index',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('accounting/genledger/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/genledger/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/genledger/downxls'),
	'columns'=>"
		{
			field:'genledgerid',
			title:localStorage.getItem('cataloggenledgerid'),
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'companyid',
			title:localStorage.getItem('catalogcompany'),
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return row.companycode;
		}},
		{
			field:'plantid',
			title:localStorage.getItem('catalogplant'),
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return row.plantcode;
		}},
		{
			field:'accountcode',
			title:localStorage.getItem('catalogaccountcode'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.accountcode;
		}},
		{
			field:'accountname',
			title:localStorage.getItem('catalogaccountname'),
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.accountname;
		}},
		{
			field:'genjournalid',
			title:localStorage.getItem('catalogjournalno'),
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.journalno;
		}},
		{
			field:'debit',
			title:localStorage.getItem('catalogdebit'),
			width:'130px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber(row.symbol,value);
		}},
		{
			field:'credit',
			title:localStorage.getItem('catalogcredit'),
			width:'130px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber(row.symbol,value);
		}},
		{
			field:'postdate',
			title:localStorage.getItem('catalogpostdate'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'ratevalue',
			title:localStorage.getItem('catalogratevalue'),
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'detailnote',
			title:localStorage.getItem('catalogdetailnote'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}}",
	'searchfield'=> array ('genledgerid','companycode','plantcode','journalno','accountcode','accountname','postdate','detailnote')
));