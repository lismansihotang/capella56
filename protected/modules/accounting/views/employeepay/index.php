<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'employeepayid',
	'formtype'=>'master',
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appempay',
	'url'=>Yii::app()->createUrl('accounting/employeepay/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/employeepay/save'),
	'updateurl'=>Yii::app()->createUrl('accounting/employeepay/save'),
	'destroyurl'=>Yii::app()->createUrl('accounting/employeepay/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('accounting/employeepay/approve'),
	'uploadurl'=>Yii::app()->createUrl('accounting/employeepay/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/employeepay/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/employeepay/downxls'),
	'columns'=>"
		{
			field:'employeepayid',
			title:localStorage.getItem('catalogID'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appempay')."
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
			field:'employeepayno',
			title:localStorage.getItem('catalogemployeepayno'),
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}}, 
		{
			field:'employeepaydate',
			title:localStorage.getItem('catalogemployeepaydate'),
			editor: {
				type:'datebox'
			},
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'employeepiutangid',
			title:localStorage.getItem('catalogemployeepiutang'),
			width:'200px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'employeepiutangid',
					textField:'employeepiutangno',
					pagination:true,
					url:'". Yii::app()->createUrl('hr/employeepiutang/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'employeepiutangid',title:localStorage.getItem('catalogemployeepiutangid'),width:'80px'},
						{field:'employeepiutangno',title:localStorage.getItem('catalogemployeepiutangno'),width:'200px'}
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.employeepiutangno;
			}
		},
		{
			field:'nilaipiutang',
			title:localStorage.getItem('catalognilaipiutang'),
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}}, 
		{
			field:'nilai',
			title:localStorage.getItem('catalognilaibayar'),
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
			field:'sisa',
			title:localStorage.getItem('catalogsisa'),
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
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
	'searchfield'=> array ('employeepayid','employeepayno','employeepiutangno','plantcode'),	
));