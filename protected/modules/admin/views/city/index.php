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
	'downdoc'=>Yii::app()->createUrl('admin/city/downdoc'),
	'columns'=>"
		{
			field:'cityid',
			title:localStorage.getItem('catalogcityid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'provinceid',
			title:localStorage.getItem('catalogprovince'),
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
						loadMsg: localStorage.getItem('catalogpleasewait'),
						columns:[[
							{field:'provinceid',title:localStorage.getItem('catalogprovinceid'),width:'80px'},
							{field:'provincename',title:localStorage.getItem('catalogprovincename'),width:'250px'},
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
			title:localStorage.getItem('catalogcitycode'),
			editor:'numberbox',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'cityname',
			title:localStorage.getItem('catalogcityname'),
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:localStorage.getItem('catalogrecordstatus'),
			align:'center',
			width:'80px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
				} else {
				return '';
				}
			}
		},",
	'searchfield'=> array ('cityid','provincename','citycode','cityname')
));