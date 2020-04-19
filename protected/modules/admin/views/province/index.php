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
	'downdoc'=>Yii::app()->createUrl('admin/province/downdoc'),
	'columns'=>"
		{
			field:'provinceid',
			title:localStorage.getItem('catalogprovinceid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'countryid',
			title:localStorage.getItem('catalogcountry'),
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
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'countryid',title:localStorage.getItem('catalogcountryid'),width:'80px'},
						{field:'countrycode',title:localStorage.getItem('catalogcountrycode'),width:'80px'},
						{field:'countryname',title:localStorage.getItem('catalogcountryname'),width:'250px'},
					]]
				}	
			}
		},
		{
			field:'provincecode',
			title:localStorage.getItem('catalogprovincecode'),
			editor:{type:'numberbox',options:{precision:0,required:true,}},
			sortable: true,	
			width:'100px',			
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'provincename',
			title:localStorage.getItem('catalogprovincename'),
			editor:'text',
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'recordstatus',
			title:localStorage.getItem('catalogrecordstatus'),
			align:'center',
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
	'searchfield'=> array ('provinceid','countrycode','countryname','provincecode','provincename')
)); ?>