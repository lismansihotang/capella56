<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'orgstructureid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/orgstructure/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/orgstructure/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/orgstructure/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/orgstructure/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/orgstructure/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/orgstructure/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/orgstructure/downxls'),
	'columns'=>"
		{
			field:'orgstructureid',
			title:'". getCatalog('orgstructureid') ."',
			width:'30px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'companyid',
			title:'". getCatalog('company') ."',
			width:'300px',
			sortable: true,
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'companyid',
					textField:'companyname',
					url:'". Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ."',
					fitColumns:true,
					required:true,
					pagination:true,
					loadMsg: '". getCatalog('pleasewait')."',
					columns:[[
						{field:'companyid',title:'". getCatalog('companyid')."',width:'80px'},
						{field:'companyname',title:'". getCatalog('companyname')."',width:'200px'},
					]]
				}	
			},
			width:'250px',
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'structurename',
			title:'". getCatalog('structurename') ."',
			width:'250px',
			editor:{
					type:'validatebox',
					options:{
						required:true
					}
				},
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'parentid',
			title:'". getCatalog('parent') ."',
			width:'300px',
			sortable: true,
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'orgstructureid',
					textField:'structurename',
					url:'". Yii::app()->createUrl('hr/orgstructure/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					pagination:true,
					loadMsg: '". getCatalog('pleasewait')."',
					columns:[[
						{field:'orgstructureid',title:'". getCatalog('orgstructureid')."',width:'80px'},
						{field:'structurename',title:'". getCatalog('structurename')."',width:'200px'},
					]]
				}	
			},
			width:'250px',
			formatter: function(value,row,index){
				return row.parentname;
		}},
		{
			field:'recordstatus',
			title:'". getCatalog('recordstatus') ."',
			width:80,
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
	'searchfield'=> array ('orgstructureid','structurename','companyname')
));