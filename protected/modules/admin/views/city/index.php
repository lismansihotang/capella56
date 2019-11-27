<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'cityid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/city/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/city/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/city/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/city/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/city/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/city/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/city/downxls'),
	'columns'=>"
		{
			field:'cityid',
			title:'".GetCatalog('cityid')."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'provinceid',
			title:'". GetCatalog('province') ."',
			editor:{
				type:'combogrid',
				options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'provinceid',
						textField:'provincename',
						url:'". $this->createUrl('province/index',array('grid'=>true,'combo'=>true)) ."',
						fitColumns:true,
						required:true,
						loadMsg: '". GetCatalog('pleasewait')."',
						columns:[[
							{field:'provinceid',title:'". GetCatalog('provinceid')."',width:'80px'},
							{field:'provincename',title:'". GetCatalog('provincename')."',width:'250px'},
						]]
				}	
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.provincename;
		}},
		{
			field:'citycode',
			title:'".GetCatalog('citycode')."',
			editor:'numberbox',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'cityname',
			title:'".GetCatalog('cityname')."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus')."',
			align:'center',
			width:'80px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/icons/ok.png'."\"></img>';
				} else {
				return '';
				}
			}
		},",
	'searchfield'=> array ('cityid','provincename','citycode','cityname')
));
