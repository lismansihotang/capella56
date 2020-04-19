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
			title:localStorage.getItem('catalogemployeepiutangid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appempi')."
		}},
		{
			field:'plantid',
			title:localStorage.getItem('catalogplant'),
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
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'plantid',title:localStorage.getItem('catalogplantid'),width:'80px'},
						{field:'plantcode',title:localStorage.getItem('catalogplantcode'),width:'200px'}
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
			title:localStorage.getItem('catalogemployeepiutangno'),
			editor:'textbox',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}}, 
		{
			field:'employeepiutangdate',
			title:localStorage.getItem('catalogemployeepiutangdate'),
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
			title:localStorage.getItem('catalogduedate'),
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
			title:localStorage.getItem('catalogemployee'),
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
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'employeeid',title:localStorage.getItem('catalogemployeeid'),width:'80px'},
						{field:'fullname',title:localStorage.getItem('catalogfullname'),width:'200px'}
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
			title:localStorage.getItem('catalogorgstructure'),
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
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'orgstructureid',title:localStorage.getItem('catalogorgstructureid'),width:'80px'},
						{field:'structurename',title:localStorage.getItem('catalogstructurename'),width:'200px'}
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
			title:localStorage.getItem('catalogpositionname'),
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}}, 
		{
			field:'nilai',
			title:localStorage.getItem('catalognilai'),
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
			title:localStorage.getItem('catalogdescription'),
			editor:'textbox',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return value;
		}}, 
		{
			field:'recordstatus',
			title:localStorage.getItem('catalogrecordstatus'),
			align:'left',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.statusname
		}}",
	'searchfield'=> array ('employeepiutangid','employeepiutangno','employeename','positionname','structurename'),	
));