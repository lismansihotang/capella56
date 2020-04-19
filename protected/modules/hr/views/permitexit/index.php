<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'permitexitid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/permitexit/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/permitexit/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/permitexit/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/permitexit/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/permitexit/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/permitexit/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/permitexit/downxls'),
	'columns'=>"
		{
			field:'permitexitid',
			title:'".getCatalog('permitexitid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'permitexitname',
			title:'".getCatalog('permitexitname')."', 
			editor:'text',
			width:'250px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'snroid',
			title:'".getCatalog('snro') ."',
			width:'150px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'snroid',
					textField:'description',
					pagination:true,
					url:'".Yii::app()->createUrl('admin/snro/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'snroid',title:'".getCatalog('snroid')."',width:'50px'},
						{field:'description',title:'".getCatalog('description')."',width:'200px'}
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.description;
		}},
		{
			field:'recordstatus',
			title:'".getCatalog('recordstatus')."',
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable:'true',
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
				} else {
					return '';
				}
			}
		}",
	'searchfield'=> array ('permitexitid','permitexitname')
));