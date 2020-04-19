<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'pibid',
	'formtype'=>'master',
	'isupload'=>0,
	'url'=>Yii::app()->createUrl('purchasing/pib/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('purchasing/pib/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('purchasing/pib/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('purchasing/pib/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('purchasing/pib/upload'),
	'downpdf'=>Yii::app()->createUrl('purchasing/pib/downpdf'),
	'downxls'=>Yii::app()->createUrl('purchasing/pib/downxls'),
	'columns'=>"
		{
			field:'pibid',
			title:'".getCatalog('pibid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'poheaderid',
			title:'".getCatalog('pono')."', 
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'450px',
					mode : 'remote',
					method:'get',
					idField:'poheaderid',
					textField:'pono',
					required:true,
					url:'".Yii::app()->createUrl('purchasing/poheader/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'poheaderid',title:'".GetCatalog('poheaderid')."',width:'80px'},
						{field:'pono',title:'".GetCatalog('pono')."',width:'120px'},
						{field:'fullname',title:'".GetCatalog('supplier')."',width:'250px'},
					]]
				}	
			},
			width:'250px',
			sortable:'true',
			formatter: function(value,row,index){
				return row.pono;
			}
		},
		{
			field:'pibno',
			title:'".getCatalog('pibno')."', 
			editor: {
				type: 'textbox',
				options: {
					required: true
				}
			},
			width:'250px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'lcno',
			title:'".getCatalog('lcno')."', 
			editor: {
				type: 'textbox',
				options: {
					required: true
				}
			},
			width:'250px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		",
	'searchfield'=> array ('pibid','pibno','pono','lcno')
));