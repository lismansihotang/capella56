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
	'downdoc'=>Yii::app()->createUrl('common/addressbook/downdoc'),
	'columns'=>"
		{
			field:'addressbookid',
			title: localStorage.getItem('catalogaddressbookid'),
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'fullname',
			title: localStorage.getItem('catalogfullname'),
			width:'350px',
			editor: {
        type: 'textbox',
      },
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'iscustomer',
			title: localStorage.getItem('catalogiscustomer'),
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
			title: localStorage.getItem('catalogisemployee'),
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
			title: localStorage.getItem('catalogisvendor'),
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
			title: localStorage.getItem('catalogishospital'),
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
			title: localStorage.getItem('catalogisexpedisi'),
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
			title: localStorage.getItem('catalogtaxno'),
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
			title: localStorage.getItem('catalogrecordstatus'),
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