<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'stdfohulid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('production/stdfohul/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('production/stdfohul/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('production/stdfohul/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('production/stdfohul/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('production/stdfohul/upload'),
	'downpdf'=>Yii::app()->createUrl('production/stdfohul/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/stdfohul/downxls'),
	'columns'=>"
		{
			field:'stdfohulid',
			title:'".GetCatalog('stdfohulid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'plantid',
			title:'". GetCatalog('plant') ."',
			width:'150px',
			sortable: true,
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
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'plantid',title:'". GetCatalog('plantid')."',width:'50px'},
						{field:'plantcode',title:'". GetCatalog('plantcode')."',width:'80px'},
						{field:'description',title:'". GetCatalog('description')."',width:'200px'},
					]]
				}	
			},
			formatter: function(value,row,index){
				return row.plantcode;
		}},
		{
			field:'productid',
			title:'".GetCatalog('product') ."',
			width:'550px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'550px',
					mode : 'remote',
					method:'get',
					idField:'productid',
					textField:'productname',
					url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'productplantjasa'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'productid',title:'".GetCatalog('productid')."',width:'50px'},
						{field:'productname',title:'".GetCatalog('productname')."',width:'480px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.productname;
		}},
		{
			field:'valued',
			title:'".GetCatalog('valued') ."',
			width:'120px',
			editor:{
				type:'numberbox',
				options:{
					precision:2,
					required:true,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'uomid',
			title:'".GetCatalog('uomcode') ."',
			width:'100px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'unitofmeasureid',
					textField:'uomcode',
					required:true,
					url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
						{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'170px'},
						{field:'description',title:'".GetCatalog('description')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.uomcode;
		}},
		{
			field:'uomcid',
			title:'".GetCatalog('uomcode') ."',
			width:'100px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'unitofmeasureid',
					textField:'uomcode',
					required:true,
					url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
						{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'170px'},
						{field:'description',title:'".GetCatalog('description')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.uomccode;
		}},
		{
			field:'startdate',
			title:'".GetCatalog('startdate')."',
			editor: {
				type:'datebox',
				options:{
					required:true,
					formatter:dateformatter,
					parser:dateparser
				}
			},
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.startdate;
		}},
		{
			field:'enddate',
			title:'".GetCatalog('enddate')."',
			editor: {
				type:'datebox',
				options:{
					required:true,
					formatter:dateformatter,
					parser:dateparser
				}
			},
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.enddate;
		}},
		",
	'searchfield'=> array ('stdfohulid','plantcode','productname')
));