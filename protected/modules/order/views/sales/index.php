<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'salesid',
	'formtype'=>'master',
	'ispdf'=>1,
	'isxls'=>1,
	'url'=>Yii::app()->createUrl('order/sales/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('order/sales/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('order/sales/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('order/sales/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('order/sales/upload'),
	'downpdf'=>Yii::app()->createUrl('order/sales/downpdf'),
	'downxls'=>Yii::app()->createUrl('order/sales/downxls'),
	'columns'=>"
		{
			field:'salesid',
			title:'".getCatalog('salesid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'plantid',
			title:'".GetCatalog('plant') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'plantid',
					textField:'plantcode',
					url:'".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'plantid',title:'".GetCatalog('plantid')."',width:'50px'},
						{field:'plantcode',title:'".GetCatalog('plantcode')."',width:'150px'},
						{field:'description',title:'".GetCatalog('description')."',width:'150px'},
					]]
				}	
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
								return row.plantcode;
			}
		},
		{
			field:'employeeid',
			title:'".GetCatalog('sales') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'employeeid',
					textField:'fullname',
					url:'".Yii::app()->createUrl('hr/employee/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'employeeid',title:'".GetCatalog('employeeid')."',width:'50px'},
						{field:'oldnik',title:'".GetCatalog('oldnik')."',width:'150px'},
						{field:'fullname',title:'".GetCatalog('fullname')."',width:'150px'},
					]]
				}	
			},
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
								return row.oldnik + ' - ' + row.fullname;
			}
		},
		{
			field:'limitsample',title:'".getCatalog('limitsample')."',
			align:'center',
			width:'150px',
			editor:{
				type:'numberbox',
				options:{
					required:true
				}
			},
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		}",
	'searchfield'=> array ('salesid','areaname')
));
