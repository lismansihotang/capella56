<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'kepletterid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/kepletter/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/kepletter/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/kepletter/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/kepletter/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/kepletter/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/kepletter/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/kepletter/downxls'),
	'columns'=>"
		{
			field:'kepletterid',
			title:'".getCatalog('kepletterid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'companyid',
			title:'".getCatalog('company')."',
			width:'250px',
			editor:{
					type:'combogrid',
					options:{
						panelWidth:'600px',
						mode : 'remote',
						method:'get',
						idField:'companyid',
						textField:'structurename',
						pagination:true,
						url:'".Yii::app()->createUrl('admin/company/index',array('grid'=>true,'combo'=>true))."',
						fitColumns:true,
						loadMsg: '".getCatalog('pleasewait')."',
						columns:[[
							{field:'companyid',title:'".getCatalog('companyid')."',width:'50px'},
							{field:'companyname',title:'".getCatalog('companyname')."',width:'200px'},
						]]
					}	
				},
			sortable: true,
			formatter: function(value,row,index){
				return row.companyname;
			}
		},
		{
			field:'nosurat',
			title:'".getCatalog('nosurat')."',
			align:'left',
			width:'100px',
			editor:'text',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'dateletter',
			title:'".getCatalog('dateletter')."',
			align:'left',
			width:'150px',
			editor:'text',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'docupload',
			title:'".getCatalog('docupload')."',
			align:'left',
			width:'150px',
			editor:'text',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		}",
	'searchfield'=> array ('kepletterid','companyname','nosurat')
));