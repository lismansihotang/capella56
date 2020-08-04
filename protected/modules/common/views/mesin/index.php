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
			title: localStorage.getItem('catalogmesinid'), 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'plantid',
			title: localStorage.getItem('catalogplant'),
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
						loadMsg:  localStorage.getItem('catalogpleasewait'),
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
							{field:'plantid',title: localStorage.getItem('catalogplantid'),width:'80px'},
							{field:'plantcode',title: localStorage.getItem('catalogplantcode'),width:'100px'},
							{field:'description',title: localStorage.getItem('catalogdescription'),width:'300px'},
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
			title: localStorage.getItem('catalogsloc'),
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
						loadMsg:  localStorage.getItem('catalogpleasewait'),
						columns:[[
							{field:'slocid',title: localStorage.getItem('catalogslocid'),width:'80px'},
							{field:'sloccode',title: localStorage.getItem('catalogsloccode'),width:'100px'},
							{field:'description',title: localStorage.getItem('catalogdescription'),width:'300px'},
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
			title: localStorage.getItem('catalogkodemesin'), 
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
			title: localStorage.getItem('catalognamamesin'), 
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
			title: localStorage.getItem('catalogtahunoperasional'), 
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
			title: localStorage.getItem('catalogbuatan'), 
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
			title: localStorage.getItem('catalogkwh'), 
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
			title: localStorage.getItem('catalogorgpershift'), 
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
			title: localStorage.getItem('catalogshiftperhari'), 
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
			title: localStorage.getItem('catalogspeedpermin'), 
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
			title: localStorage.getItem('catalogspeedperjam'), 
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
			title: localStorage.getItem('catalogrpm'), 
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
			title: localStorage.getItem('cataloglebarbahan'), 
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
			title: localStorage.getItem('catalogrpm2'), 
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
			title: localStorage.getItem('catalogdescription'), 
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