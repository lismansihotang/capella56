<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'absstatusid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/absstatus/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/absstatus/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/absstatus/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/absstatus/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/absstatus/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/absstatus/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/absstatus/downxls'),
	'columns'=>"
		{
			field:'absstatusid',
			title:'". getCatalog('absstatusid') ."',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'shortstat',
			title:'". getCatalog('shortstat') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'longstat',
			title:'". getCatalog('longstat') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'isin',
			title:'". getCatalog('isin') ."',
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
			field:'priority',
			title:'". getCatalog('priority') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'". getCatalog('recordstatus') ."',
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
		}
	}",
	'searchfield'=> array ('absstatusid','shortstat','longstat')
));