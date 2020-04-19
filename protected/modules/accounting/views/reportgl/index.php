<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'genjournalid',
	'formtype'=>'list',
	'wfapp'=>'appjournal',
	'url'=>Yii::app()->createUrl('accounting/reportgl/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/genjournal/downpdf'),
	'columns'=>"
		{
			field:'genjournalid',
			title:'".GetCatalog('genjournalid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appjournal')."
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
			field:'plantid',
			title:'".GetCatalog('plant') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.plantcode;
		}},
		{
			field:'journalno',
			title:'".GetCatalog('journalno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'referenceno',
			title:'".GetCatalog('referenceno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return value;
					}},
		{
			field:'journaldate',
			title:'".GetCatalog('journaldate') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'journalnote',
			title:'".GetCatalog('journalnote') ."',
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatusgenjournal',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},",
	'searchfield'=> array ('genjournalid','journalno','referenceno','journaldate','journalnote','accountcode','accountname','recordstatus'),
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'journaldetailid',
			'urlsub'=>Yii::app()->createUrl('accounting/genjournal/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'accountname',title:'".GetCatalog('accountname') ."',width:'250px'},
				{field:'debit',title:'".GetCatalog('debit') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},width:'150px'},
				{field:'credit',title:'".GetCatalog('credit') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},width:'150px'},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'80px'},
				{field:'detailnote',title:'".GetCatalog('detailnote') ."',width:'350px'},
			",
		),
	),	
));