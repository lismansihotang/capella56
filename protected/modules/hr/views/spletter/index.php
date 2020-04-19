<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'spletterid',
	'formtype'=>'master',
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appsp',
	'url'=>Yii::app()->createUrl('hr/spletter/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/spletter/save'),
	'updateurl'=>Yii::app()->createUrl('hr/spletter/save'),
	'destroyurl'=>Yii::app()->createUrl('hr/spletter/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('hr/spletter/approve'),
	'uploadurl'=>Yii::app()->createUrl('hr/spletter/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/spletter/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/spletter/downxls'),
	'columns'=>"
		{
			field:'spletterid',
			title:'".GetCatalog('spletterid')."',
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
			field:'spletterno',
			title:'".GetCatalog('spletterno')."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'spletterdate',
			title:'".GetCatalog('spletterdate')."',
			editor: {
				type:'datebox'
			},
			sortable: true,
			width:'80px',
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
			field:'splettertypeid',
			title:'".GetCatalog('splettertype')."',
			width:'150px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'splettertypeid',
					textField:'splettername',
					pagination:true,
					url:'". Yii::app()->createUrl('hr/splettertype/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'splettertypeid',title:'".GetCatalog('splettertypeid')."',width:'80px'},
						{field:'splettername',title:'".GetCatalog('splettername')."',width:'200px'}
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.splettername;
			}
		},
		{
			field:'description',
			title:'".GetCatalog('description')."',
			editor:'textbox',
			sortable: true,
			width:'200px',
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
				return value
		}}",
	'searchfield'=> array ('spletterid','spletterno','employeename','splettername'),	
));