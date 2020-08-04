<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'slocid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/sloc/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/sloc/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/sloc/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/sloc/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/sloc/upload'),
	'downpdf'=>Yii::app()->createUrl('common/sloc/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/sloc/downxls'),
	'downdoc'=>Yii::app()->createUrl('common/sloc/downdoc'),
	'columns'=>"
		{
			field:'slocid',
			title: localStorage.getItem('catalogslocid'),
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'plantid',
			title: localStorage.getItem('catalogplant'),
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
					url:'". $this->createUrl('plant/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg:  localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'plantid',title: localStorage.getItem('catalogplantid'),width:'50px'},
						{field:'plantcode',title: localStorage.getItem('catalogplantcode'),width:'80px'},
						{field:'description',title: localStorage.getItem('catalogdescription'),width:'200px'},
					]]
				}	
			},
			formatter: function(value,row,index){
				return row.plantcode;
		}},
		{
			field:'sloccode',
			title: localStorage.getItem('catalogsloccode'),
			width:'150px',
			editor:{
				type: 'validatebox', 
				options: {
					required: true,
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'description',
			title: localStorage.getItem('catalogdescription'),
			width:'250px',
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
			field:'isprd',
			title: localStorage.getItem('catalogisprd'),
			width:'80px',
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
			field:'isbb',
			title: localStorage.getItem('catalogisbb'),
			width:'80px',
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
			field:'isbj',
			title: localStorage.getItem('catalogisbj'),
			width:'80px',
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
	'searchfield'=> array ('slocid','plantcode','sloccode','description')
));