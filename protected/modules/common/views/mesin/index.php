<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'mesinid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/mesin/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/mesin/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/mesin/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/mesin/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/mesin/upload'),
	'downpdf'=>Yii::app()->createUrl('common/mesin/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/mesin/downxls'),
	'columns'=>"
		{
			field:'mesinid',
			title:'".getCatalog('mesinid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'plantid',
			title:'". GetCatalog('plant') ."',
			editor:{
				type:'combogrid',
				options:{
						panelWidth:'550x',
						mode : 'remote',
						method:'get',
						idField:'plantid',
						textField:'plantcode',
						url:'". Yii::app()->createUrl('common/plant/index',array('grid'=>true,'auth'=>true)) ."',
						fitColumns:true,
						required:true,
						loadMsg: '". GetCatalog('pleasewait')."',
						onChange: function(newValue, oldValue){
							if ((newValue !== oldValue) && (newValue !== ''))
							{
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var slocid = $('#dg-mesin').datagrid('getEditor', {index: index, field:'slocid'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'getdata'=>true)) ."',
									'data':{
										'plantid':newValue,
									},
									'type':'post',
									'dataType':'json',
									'success':function(data)
									{
										var g = $(slocid.target).combogrid('grid');
										g.datagrid({queryParams: {
											plantid: data.plantid
										}});
									},
									'cache':false});
							}
						},
						columns:[[
							{field:'plantid',title:'". GetCatalog('plantid')."',width:'80px'},
							{field:'plantcode',title:'". GetCatalog('plantcode')."',width:'100px'},
							{field:'description',title:'". GetCatalog('description')."',width:'300px'},
						]]
				}	
			},
			width:'110px',
			sortable: true,
			formatter: function(value,row,index){
				return row.plantcode;
		}},
		{
			field:'slocid',
			title:'". GetCatalog('sloc') ."',
			editor:{
				type:'combogrid',
				options:{
						panelWidth:'550x',
						mode : 'remote',
						method:'get',
						idField:'slocid',
						textField:'sloccode',
						url:'". Yii::app()->createUrl('common/sloc/indextrxplant',array('grid'=>true)) ."',
						fitColumns:true,
						required:true,
						loadMsg: '". GetCatalog('pleasewait')."',
						columns:[[
							{field:'slocid',title:'". GetCatalog('slocid')."',width:'80px'},
							{field:'sloccode',title:'". GetCatalog('sloccode')."',width:'100px'},
							{field:'description',title:'". GetCatalog('description')."',width:'300px'},
						]]
				}	
			},
			width:'110px',
			sortable: true,
			formatter: function(value,row,index){
				return row.sloccode;
		}},
		{
			field:'kodemesin',
			title:'".getCatalog('kodemesin')."', 
			editor:{
				type:'validatebox',
				options:{
					required:true
				}
			},
			width:'100px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'namamesin',
			title:'".getCatalog('namamesin')."', 
			editor:{
				type:'validatebox',
				options:{
					required:true
				}
			},
			width:'300px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'tahunoperasional',
			title:'".getCatalog('tahunoperasional')."', 
			editor:{
				type:'numberbox',
				options:{
					required:true
				}
			},
			width:'120px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'buatan',
			title:'".getCatalog('buatan')."', 
			editor:{
				type:'validatebox',
				options:{
					required:true
				}
			},
			width:'150px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'kwh',
			title:'".getCatalog('kwh')."', 
			editor:{
				type:'numberbox',
				options:{
					required:true,
					decimalSeparator:',',
					groupSeparator:'.',
					precision:2
				}
			},
			width:'100px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'orgpershift',
			title:'".getCatalog('orgpershift')."', 
			editor:{
				type:'numberbox',
				options:{
					required:true
				}
			},
			width:'100px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'shiftperhari',
			title:'".getCatalog('shiftperhari')."', 
			editor:{
				type:'numberbox',
				options:{
					required:true
				}
			},
			width:'100px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'speedpermin',
			title:'".getCatalog('speedpermin')."', 
			editor:{
				type:'numberbox',
				options:{
					required:true
				}
			},
			width:'100px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'speedperjam',
			title:'".getCatalog('speedperjam')."', 
			editor:{
				type:'numberbox',
				options:{
					required:true
				}
			},
			width:'100px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'rpm',
			title:'".getCatalog('rpm')."', 
			editor:{
				type:'numberbox',
				options:{
					required:true,
					decimalSeparator:',',
					groupSeparator:'.',
					precision:2
				}
			},
			width:'100px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'lebarbahan',
			title:'".getCatalog('lebarbahan')."', 
			editor:{
				type:'numberbox',
				options:{
					required:true,
					decimalSeparator:',',
					groupSeparator:'.',
					precision:2
				}
			},
			width:'100px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'rpm2',
			title:'".getCatalog('rpm2')."', 
			editor:{
				type:'numberbox',
				options:{
					required:true,
					decimalSeparator:',',
					groupSeparator:'.',
					precision:2
				}
			},
			width:'100px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'description',
			title:'".getCatalog('description')."', 
			editor:{
				type:'text',
				options:{
				}
			},
			width:'200px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		}
		",
	'searchfield'=> array ('mesinid','plantcode','sloccode','kodemesin','namamesin','buatan','tahun')
));