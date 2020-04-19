<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'reportperday',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/reportperday/index',array('grid'=>true)),
	'iswrite'=>0,
    'ispost'=>0,
	'downpdf'=>Yii::app()->createUrl('hr/reportperday/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/reportperday/downxls'),
	'columns'=>"
		{
			field:'reportperdayid',
			title:'". GetCatalog('reportperdayid') ."',
			sortable: true,
			width:'50',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'employeeid',
			title:'". GetCatalog('employeeid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'fullname',
			title:'". GetCatalog('fullname') ."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'oldnik',
			title:'". GetCatalog('oldnik') ."',
			editor:'text',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'fulldivision',
			title:'". GetCatalog('fulldivision') ."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'absdate',
			title:'". GetCatalog('absdate') ."',
			editor:'text',
			width:'75px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'hourin',
			title:'". GetCatalog('hourin') ."',
			editor:'text',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},{
			field:'hourout',
			title:'". GetCatalog('hourout') ."',
			editor:'text',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'schedulename',
			title:'". GetCatalog('schedulename') ."',
			editor:'text',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'statusin',
			title:'". GetCatalog('statusin') ."',
			editor:'text',
			width:'60px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'statusout',
			title:'". GetCatalog('statusout') ."',
			editor:'text',
			width:'60px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'reason',
			title:'". GetCatalog('description') ."',
			editor:'text',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
    ",
	'searchfield'=> array('fullname','fulldivision','absdate')
    ));