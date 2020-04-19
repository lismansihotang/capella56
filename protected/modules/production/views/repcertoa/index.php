<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'certoaid',
	'formtype'=>'list',
	'url'=>Yii::app()->createUrl('production/repcertoa/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('production/certoa/downpdf'),
	'columns'=>"
		{
			field:'certoaid',
			title:'".getCatalog('certoaid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".getStatusColor('appcoa')."
		}},
		{
			field:'companyname',
			title:'".getCatalog('company') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'plantid',
			title:'".getCatalog('plantcode') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.plantcode;
		}},
		{
			field:'certoano',
			title:'".getCatalog('certoano') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'certoadate',
			title:'".getCatalog('certoadate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'sono',
			title:'".getCatalog('soheader') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'fullname',
			title:'".getCatalog('customer') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'materialtypecode',
			title:'".getCatalog('materialtypecode') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productname',
			title:'".getCatalog('productname') ."',
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'bomversion',
			title:'".getCatalog('bom') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'jumkirim',
			title:'".getCatalog('jumkirim') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'status',
			title:'".GetCatalog('status')."',
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'description',
			title:'".getCatalog('description') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'statusname',
			title:'".getCatalog('recordstatusname') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}},
	",
	'searchfield'=> array ('certoaid','certoano','certoadate','productiondate','plantcode','customer','productcode','productname','bomversion','sono'),
	'columndetails'=> array (
		array(
			'id'=>'certoadetail',
			'idfield'=>'certoadetailid',
			'urlsub'=>Yii::app()->createUrl('production/certoa/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'qcparamname',title:'".getCatalog('qcparamname') ."',width:'150px'},
				{field:'methodtest',title:'".getCatalog('methodtest') ."',width:'200px'},
				{field:'unittest',title:'".getCatalog('unittest') ."',align:'right',width:'80px'},
				{field:'specmin',title:'".getCatalog('specmin') ."',align:'right',width:'80px'},
				{field:'rangetarget',title:'".getCatalog('rangetarget') ."',align:'right',width:'80px'},
				{field:'tolerancemin',title:'".getCatalog('tolerancemin') ."',align:'right',width:'80px'},
			",
		),
	),	
));