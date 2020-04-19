<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'employeepiutangid',
	'formtype'=>'master',
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appempi',
	'url'=>Yii::app()->createUrl('accounting/employeepiutang/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/employeepiutang/save'),
	'updateurl'=>Yii::app()->createUrl('accounting/employeepiutang/save'),
	'destroyurl'=>Yii::app()->createUrl('accounting/employeepiutang/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('accounting/employeepiutang/approve'),
	'uploadurl'=>Yii::app()->createUrl('accounting/employeepiutang/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/employeepiutang/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/employeepiutang/downxls'),
	'columns'=>"
		{
			field:'employeepiutangid',
			title:'".GetCatalog('ID') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appempi')."
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
			field:'employeepiutangno',
			title:'".GetCatalog('employeepiutangno')."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}}, 
		{
			field:'employeepiutangdate',
			title:'".GetCatalog('employeepiutangdate')."',
			editor: {
				type:'datebox'
			},
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'duedate',
			title:'".GetCatalog('duedate')."',
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
			field:'positionname',
			title:'".GetCatalog('positionname')."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}}, 
		{
			field:'nilai',
			title:'". GetCatalog('nilai') ."',
			editor:{
				type:'numberbox',
				options:{
					precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
				}
			},
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return formatnumber('',value);
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
	'searchfield'=> array ('employeepiutangid','employeepiutangno','employeename','positionname','structurename'),	
));