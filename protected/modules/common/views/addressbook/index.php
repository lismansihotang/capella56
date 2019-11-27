<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'addressbookid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/addressbook/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/addressbook/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/addressbook/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/addressbook/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/addressbook/upload'),
	'downpdf'=>Yii::app()->createUrl('common/addressbook/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/addressbook/downxls'),
	'columns'=>"
		{
			field:'addressbookid',
			title:'". GetCatalog('addressbookid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'fullname',
			title:'". GetCatalog('fullname') ."',
			width:'350px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'iscustomer',
			title:'". GetCatalog('iscustomer') ."',
			width:'80px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'isemployee',
			title:'". GetCatalog('isemployee') ."',
			width:'80px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'isvendor',
			title:'". GetCatalog('isvendor') ."',
			width:'80px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'ishospital',
			title:'". GetCatalog('ishospital') ."',
			width:'80px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'isexpedisi',
			title:'". GetCatalog('isexpedisi') ."',
			width:'80px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'taxno',
			title:'". GetCatalog('taxno') ."',
			width:'100px',
			editor:{
				type:'textbox',
			},
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'". GetCatalog('recordstatus') ."',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
			}}",
	'searchfield'=> array ('addressbookid','fullname','taxno')
));
