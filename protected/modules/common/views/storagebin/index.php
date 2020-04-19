<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'languageid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/storagebin/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/storagebin/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/storagebin/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/storagebin/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/storagebin/upload'),
	'downpdf'=>Yii::app()->createUrl('common/storagebin/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/storagebin/downxls'),
	'columns'=>"
		{
			field:'storagebinid',
			title:'". GetCatalog('storagebinid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'slocid',
			title:'". GetCatalog('sloc') ."',
			width:'150px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'slocid',
					textField:'sloccode',
					url:'". Yii::app()->createUrl('common/sloc/indexcombo',array('grid'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'slocid',title:'". GetCatalog('slocid')."',width:'50px'},
						{field:'sloccode',title:'". GetCatalog('sloccode')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.sloccode;
		}},
		{
			field:'description',
			title:'". GetCatalog('description') ."',
			editor:{
				type: 'validatebox',
				options:{
					required:true
				}
			},
			width:'250px',
			sortable: true,
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'ismultiproduct',
			title:'". GetCatalog('ismultiproduct') ."',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
			if (value == 1){
				return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
			} else {
				return '';
			}
		}},
		{
			field:'qtymax',
			title:'". GetCatalog('qtymax') ."',
			editor:{
				type:'numberbox',
				options:{
					required:true,
					precision: 4,
					decimalSeparator:',',
					groupSeparator:'.',
				}
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'recordstatus',
			title:'". GetCatalog('recordstatus') ."',
			align:'center',
			width:'80px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
			}}",
	'searchfield'=> array ('storagebinid','sloccode','description'),
));