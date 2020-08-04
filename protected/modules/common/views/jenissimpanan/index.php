<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'jenissimpananid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/jenissimpanan/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/jenissimpanan/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/jenissimpanan/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/jenissimpanan/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/jenissimpanan/upload'),
	'downpdf'=>Yii::app()->createUrl('common/jenissimpanan/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/jenissimpanan/downxls'),
	'downdoc'=>Yii::app()->createUrl('common/jenissimpanan/downdoc'),
	'columns'=>"
		{
			field:'jenissimpananid',
			title: localStorage.getItem('catalogjenissimpananid'),
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'namasimpanan',
			title: localStorage.getItem('catalognamasimpanan'),
			width:'200px',
			editor: {
				type: 'textbox',
				options:{
					required:true
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'jumlah',
			title: localStorage.getItem('catalogjumlah'),
			width:'200px',
			editor: {
				type: 'numberbox',
				options:{
					precision:2,
					decimalSeparator:',',
					groupSeparator:'.',
					value:0,
					required:true
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'bunga',
			title: localStorage.getItem('catalogbunga'),
			width:'200px',
			editor: {
				type: 'numberbox',
				options:{
					required:true,
					precision: 2,
					decimalSeparator:',',
					groupSeparator:'.',
					value:0,
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'fixed',
			title: localStorage.getItem('catalogfixed'),
			width:'80px',
			align:'center',
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
			field:'tenor',
			title: localStorage.getItem('catalogtenor'),
			width:'80px',
			align:'center',
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
			field:'isauto',
			title: localStorage.getItem('catalogisauto'),
			width:'80px',
			align:'center',
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
			field:'recordstatus',
			title: localStorage.getItem('catalogrecordstatus'),
			width:'80px',
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
			}},
			",
	'searchfield'=> array ('jenissimpananid','namasimpanan')
));