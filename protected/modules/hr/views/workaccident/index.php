<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'workaccidentid',
	'formtype'=>'master',
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appwa',
	'url'=>Yii::app()->createUrl('hr/workaccident/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/workaccident/save'),
	'updateurl'=>Yii::app()->createUrl('hr/workaccident/save'),
	'destroyurl'=>Yii::app()->createUrl('hr/workaccident/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('hr/workaccident/approve'),
	'uploadurl'=>Yii::app()->createUrl('hr/workaccident/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/workaccident/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/workaccident/downxls'),
	'columns'=>"
		{
			field:'workaccidentid',
			title:'".GetCatalog('workaccidentid')."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'plantid',
			title:'".GetCatalog('plant')."',
			width:'200px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'plantid',
					textField:'plantcode',
					pagination:true,
					url:'". Yii::app()->createUrl('common/plant/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'plantid',title:'".GetCatalog('plantid')."',width:'80px'},
						{field:'plantcode',title:'".GetCatalog('plantcode')."',width:'200px'}
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.plantcode;
			}
		},
		{
			field:'workaccidentno',
			title:'".GetCatalog('workaccidentno')."',
			editor:'textbox',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}}, 
		{
			field:'workaccidentdate',
			title:'".GetCatalog('workaccidentdate')."',
			editor: {
				type:'datebox'
			},
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'employeeid',
			title:'".GetCatalog('employee')."',
			width:'200px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'employeeid',
					textField:'fullname',
					pagination:true,
					url:'". Yii::app()->createUrl('hr/employee/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'employeeid',title:'".GetCatalog('employeeid')."',width:'80px'},
						{field:'fullname',title:'".GetCatalog('fullname')."',width:'200px'}
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.fullname;
			}
		},
		{
			field:'orgstructureid',
			title:'".GetCatalog('orgstructure')."',
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
					url:'". Yii::app()->createUrl('hr/orgstructure/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'orgstructureid',title:'".GetCatalog('orgstructureid')."',width:'80px'},
						{field:'structurename',title:'".GetCatalog('structurename')."',width:'200px'}
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.structurename;
			}
		},
		{
			field:'workaccidenttypeid',
			title:'".GetCatalog('workaccidenttype')."',
			width:'150px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'workaccidenttypeid',
					textField:'workaccidenttypename',
					pagination:true,
					url:'". Yii::app()->createUrl('hr/workaccidenttype/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'workaccidenttypeid',title:'".GetCatalog('workaccidenttypeid')."',width:'80px'},
						{field:'workaccidenttypename',title:'".GetCatalog('workaccidenttypename')."',width:'200px'}
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.workaccidenttypename;
			}
		},
		{
			field:'accidentreport',
			title:'".GetCatalog('Sebab - Akibat')."',
			editor:'textbox',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return value;
		}}, 
		{
			field:'description',
			title:'".GetCatalog('description')."',
			editor:'textbox',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return value;
		}}, 
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus')."',
			align:'left',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.statusname
		}}",
	'searchfield'=> array ('workaccidentid','workaccidentno','employeename','workaccidenttypename','structurename'),	
));