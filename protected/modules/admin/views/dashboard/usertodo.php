<?php $this->widget('Form',	array('menuname'=>'usertodo',
	'idfield'=>'usertodoid',
	'formtype'=>'list',
	'url'=>Yii::app()->createUrl('site/usertodo'),
	'columns'=>"
		{
			field:'usertodoid',
			title: localStorage.getItem('catalogusertodoid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},		
		{
			field:'tododate',
			title: localStorage.getItem('catalogtododate'),
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},		
		{
			field:'menuname',
			title: localStorage.getItem('catalogmenuname'),
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},		
		{
			field:'docno',
			title: localStorage.getItem('catalogdocno'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},		
		{
			field:'description',
			title: localStorage.getItem('catalogdescription'),
			sortable: true,
			width:'450px',
			formatter: function(value,row,index){
				return value;
		}},		
	",
));?>