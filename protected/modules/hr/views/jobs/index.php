<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'jobsid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/jobs/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/jobs/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/jobs/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/jobs/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/jobs/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/jobs/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/jobs/downxls'),
	'columns'=>"
		{
			field:'jobsid',
			title:'". getCatalog('jobsid') ."',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'orgstructureid',
			title:'". getCatalog('orgstructure') ."',
			width:'150px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'orgstructureid',
					textField:'structurename',
					pagination:true,
					url:'". $this->createUrl('orgstructure/index',array('grid'=>true)) ."',
					fitColumns:true,
					loadMsg: '". getCatalog('pleasewait')."',
					columns:[[
						{field:'orgstructureid',title:'". getCatalog('orgstructureid')."',width:'50px'},
						{field:'structurename',title:'". getCatalog('structurename')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.structurename;
		}},
		{
			field:'jobdesc',
			title:'". getCatalog('jobdesc') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'qualification',
			title:'". getCatalog('qualification') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'positionid',
			title:'". getCatalog('position') ."',
			width:'150px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'positionid',
					textField:'positionname',
					pagination:true,
					url:'". $this->createUrl('position/index',array('grid'=>true)) ."',
					fitColumns:true,
					loadMsg: '". getCatalog('pleasewait')."',
					columns:[[
						{field:'positionid',title:'". getCatalog('positionid')."',width:'50px'},
						{field:'positionname',title:'". getCatalog('positionname')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.positionname;
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
		}}",	
		'searchfield'=> array ('jobsid','structurename','jobdesc','qualification','positionname')
));