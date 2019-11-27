<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'productsalesid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/productsales/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/productsales/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/productsales/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/productsales/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/productsales/upload'),
	'downpdf'=>Yii::app()->createUrl('common/productsales/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/productsales/downxls'),
	'columns'=>"
		{
			field:'productsalesid',
			title:'". GetCatalog('productsalesid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productid',
			title:'". GetCatalog('productname') ."',
			editor:{
			type:'combogrid',
			options:{
				panelWidth:'700px',
				mode : 'remote',
				method:'get',
				idField:'productid',
				textField:'productcode',
				url:'". $this->createUrl('product/index',array('grid'=>true,'combo'=>true)) ."',
				fitColumns:true,
				required:true,
				onHidePanel:function() {
					var tr = $(this).closest('tr.datagrid-row');
					var index = parseInt(tr.attr('datagrid-row-index'));
					var productid = $(\"#dg-productsales\").datagrid(\"getEditor\", {index: index, field:\"productid\"});
					var currencyid = $(\"#dg-productsales\").datagrid(\"getEditor\", {index: index, field:\"currencyid\"});
					var currencyvalue = $(\"#dg-productsales\").datagrid(\"getEditor\", {index: index, field:\"currencyvalue\"});
					var pricecategoryid = $(\"#dg-productsales\").datagrid(\"getEditor\", {index: index, field:\"pricecategoryid\"});
					var uomid = $(\"#dg-productsales\").datagrid(\"getEditor\", {index: index, field:\"uomid\"});
					jQuery.ajax({'url':'". Yii::app()->createUrl('common/productplant/getdatasales') ."',
						'data':{'productid':$(productid.target).combogrid(\"getValue\"), 'type': 'productsales'},
						'type':'post','dataType':'json',
						'success':function(data)
						{
							$(uomid.target).combogrid('setValue',data.uomid);
						} ,
						'cache':false});
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'productid',title:'". GetCatalog('productid')."',width:'50px'},
					{field:'materialtypecode',title:'". GetCatalog('materialtypecode')."',width:'150px'},
					{field:'productname',title:'". GetCatalog('productname')."',width:'500px'},
				]]
			}	
		},
			width:'550px',
			sortable: true,
			formatter: function(value,row,index){
				return row.productname;
		}},
	{
		field:'currencyid',
		title:'". GetCatalog('currency') ."',
		width:'100px',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'currencyid',
				textField:'currencyname',
				url:'". Yii::app()->createUrl('admin/currency/index',array('grid'=>true,'combo'=>true)) ."',
				fitColumns:true,
				required:true,
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'currencyid',title:'". GetCatalog('currencyid')."',width:'50px'},
					{field:'currencyname',title:'". GetCatalog('currencyname')."',width:'200px'},
				]]
			}	
		},
		sortable: true,
		formatter: function(value,row,index){
			return row.currencyname;
	}},
	{
		field:'currencyvalue',
		title:'". GetCatalog('currencyvalue') ."',
		width:'150px',
		editor:{
			type:'numberbox',
			options:{
				precision:2,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
			}
		},
		align: 'right',
		sortable: true,
		formatter: function(value,row,index){
		return formatnumber('',value);
	}},
	{
		field:'pricecategoryid',
		title:'". GetCatalog('pricecategory') ."',
		pagination:true,
		width:'150px',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'pricecategoryid',
				textField:'categoryname',
				url:'". Yii::app()->createUrl('common/pricecategory/index',array('grid'=>true,'combo'=>true)) ."',
				fitColumns:true,
				required:true,
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'pricecategoryid',title:'". GetCatalog('pricecategoryid')."',width:'50px'},
					{field:'categoryname',title:'". GetCatalog('categoryname')."',width:'200px'},
				]]
			}	
		},
		sortable: true,
		formatter: function(value,row,index){
			return row.categoryname;
	}},
	{
		field:'uomid',
		title:'". GetCatalog('uom') ."',
		pagination:true,
		width:'100px',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'unitofmeasureid',
				textField:'uomcode',
				url:'". Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
				fitColumns:true,
				required:true,
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'unitofmeasureid',title:'". GetCatalog('unitofmeasureid')."',width:'50px'},
					{field:'uomcode',title:'". GetCatalog('uomcode')."',width:'200px'},
				]]
			}	
		},
		sortable: true,
		formatter: function(value,row,index){
			return row.uomcode;
	}}",
	'searchfield'=> array ('productsalesid','product','currencyname','pricecategory')
));
