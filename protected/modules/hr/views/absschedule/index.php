<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'absscheduleid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/absschedule/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/absschedule/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/absschedule/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/absschedule/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/absschedule/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/absschedule/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/absschedule/downxls'),
	'columns'=>"
		{
			field:'absscheduleid',
			title:'".getCatalog('absscheduleid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'absschedulename',
			title:'".getCatalog('absschedulename') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'absin',
			title:'".getCatalog('absin') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'absout',
			title:'".getCatalog('absout') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'absstatusid',
			title:'".getCatalog('absstatus') ."',
			width:'200px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absstatusid',
					textField:'shortstat',
					url:'".$this->createUrl('absstatus/index',array('grid'=>true)) ."',
					fitColumns:true,
					pagination:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absstatusid',title:'".getCatalog('absstatusid')."',width:50},
						{field:'shortstat',title:'".getCatalog('shortstat')."',width:50},
						{field:'longstat',title:'".getCatalog('longstat')."',width:200},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.shortstat;
		}},
		{
			field:'recordstatus',
			title:'".getCatalog('recordstatus') ."',
			width:80,
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},",
	'searchfield'=> array ('absscheduleid','absschedulename','absstatus')
));