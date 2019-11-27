<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'provinceid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/province/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/province/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/province/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/province/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/province/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/province/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/province/downxls'),
	'columns'=>"
		{
			field:'provinceid',
			title:'".GetCatalog('provinceid')."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'countryid',
			title:'".GetCatalog('country')."',
			sortable: true,
			width:'100px',
			formatter:function(value,row,index){
				return row.countryname;
			},
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'450px',
					mode : 'remote',
					method:'get',
					idField:'countryid',
					textField:'countryname',
					required:true,
					url:'".$this->createUrl('country/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'countryid',title:'".GetCatalog('countryid')."',width:'80px'},
						{field:'countrycode',title:'".GetCatalog('countrycode')."',width:'80px'},
						{field:'countryname',title:'".GetCatalog('countryname')."',width:'250px'},
					]]
				}	
			}
		},
		{
			field:'provincecode',
			title:'".GetCatalog('provincecode')."',
			editor:{type:'numberbox',options:{precision:0,required:true,}},
			sortable: true,	
			width:'100px',			
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'provincename',
			title:'".GetCatalog('provincename')."',
			editor:'text',
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus')."',
			align:'center',
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
	'searchfield'=> array ('provinceid','countrycode','countryname','provincecode','provincename')
)); ?>
