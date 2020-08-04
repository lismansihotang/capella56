<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'lamaangsuranid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/lamaangsuran/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/lamaangsuran/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/lamaangsuran/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/lamaangsuran/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/lamaangsuran/upload'),
	'downpdf'=>Yii::app()->createUrl('common/lamaangsuran/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/lamaangsuran/downxls'),
	'downdoc'=>Yii::app()->createUrl('common/lamaangsuran/downdoc'),
	'columns'=>"
		{
			field:'lamaangsuranid',
			title: localStorage.getItem('cataloglamaangsuranid'),
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'kodelamaangsuran',
			title: localStorage.getItem('catalogkodelamaangsuran'),
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
			field:'jangkawaktu',
			title: localStorage.getItem('catalogjangkawaktu'),
			width:'200px',
			editor: {
				type: 'numberbox',
				options:{
					value:0,
					required:true
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return value;
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
	'searchfield'=> array ('lamaangsuranid','lamaangsuranname')
));