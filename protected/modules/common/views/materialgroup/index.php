<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'materialgroupid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/materialgroup/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/materialgroup/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/materialgroup/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/materialgroup/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/materialgroup/upload'),
	'downpdf'=>Yii::app()->createUrl('common/materialgroup/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/materialgroup/downxls'),
	'downdoc'=>Yii::app()->createUrl('common/materialgroup/downdoc'),
	'columns'=>"
		{
			field:'materialgroupid',
			title: localStorage.getItem('catalogmaterialgroupid'),
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'materialgroupcode',
			title: localStorage.getItem('catalogmaterialgroupcode'),
			width:'150px',
			editor:{
				type: 'validatebox',
				options: {
					required: true
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'description',
			title: localStorage.getItem('catalogdescription'),
			width:'350px',
			editor:{
				type: 'validatebox',
				options: {
					required: true
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'parentmatgroupid',
			title: localStorage.getItem('catalogparentmatgroup'),
			width:'400px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'materialgroupid',
					textField:'description',
					url:'". $this->createUrl('materialgroup/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg:  localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'materialgroupid',title: localStorage.getItem('catalogmaterialgroupid'),width:'50px'},
						{field:'materialgroupcode',title: localStorage.getItem('catalogmaterialgroupcode'),width:'150px'},
						{field:'description',title: localStorage.getItem('catalogdescription'),width:'250px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.parentmatgroupdesc;
		}},
		{
			field:'isfg',
			title: localStorage.getItem('catalogisfg'),
			width:'50px',
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'recordstatus',
			title: localStorage.getItem('catalogrecordstatus'),
			width:'50px',
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
	'searchfield'=> array ('materialgroupid','materialgroupcode','description','parentmatgroupcode','parentmatgroupdesc')
));