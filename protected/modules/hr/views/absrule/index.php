<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'absruleid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/absrule/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/absrule/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/absrule/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/absrule/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/absrule/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/absrule/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/absrule/downxls'),
	'columns'=>"
		{
			field:'absruleid',
			title:'".getCatalog('absruleid')."',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'absscheduleid',
			title:'".getCatalog('absschedule')."',
			width:'150px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					url:'".$this->createUrl('absschedule/index',array('grid'=>true))."',
					fitColumns:true,
					pagination:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.absschedulename;
		}},
		{
			field:'difftimein',
			title:'".getCatalog('difftimein')."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'difftimeout',
			title:'".getCatalog('difftimeout')."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'absstatusid',
			title:'".getCatalog('absstatus')."',
			width:'150px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'50px',
					mode : 'remote',
					method:'get',
					idField:'absstatusid',
					textField:'longstat',
					url:'".$this->createUrl('absstatus/index',array('grid'=>true))."',
					fitColumns:true,
					pagination:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absstatusid',title:'".getCatalog('absstatusid')."',width:'50px'},
						{field:'shortstat',title:'".getCatalog('longstat')."',width:'50px'},
						{field:'longstat',title:'".getCatalog('longstat')."',width:'80px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.longstat;
		}},
		{
			field:'recordstatus',
			title:'".getCatalog('recordstatus')."',
			width:'80px',
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}
	},",
	'searchfield'=> array ('absruleid','absschedulename','absstatusname')
));