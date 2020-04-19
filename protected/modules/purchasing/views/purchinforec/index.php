<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'purchinforecid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('purchasing/purchinforec/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('purchasing/purchinforec/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('purchasing/purchinforec/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('purchasing/purchinforec/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('purchasing/purchinforec/upload'),
	'downpdf'=>Yii::app()->createUrl('purchasing/purchinforec/downpdf'),
	'downxls'=>Yii::app()->createUrl('purchasing/purchinforec/downxls'),
	'columns'=>"
		{
			field:'purchinforecid',
			title:'".GetCatalog('purchinforecid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'addressbookid',
			title:'".GetCatalog('supplier') ."',
			width:'250px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'addressbookid',
					textField:'fullname',
					url:'".Yii::app()->createUrl('common/supplier/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'addressbookid',title:'".GetCatalog('addressbookid')."',width:'50px'},
						{field:'fullname',title:'".GetCatalog('fullname')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'poheaderid',
			title:'".GetCatalog('poheader') ."',
			width:'250px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'poheaderid',
					textField:'pono',
					url:'".Yii::app()->createUrl('purchasing/poheader/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'poheaderid',title:'".GetCatalog('poheaderid')."',width:'50px'},
						{field:'pono',title:'".GetCatalog('pono')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.pono;
		}},
		{
			field:'productid',
			title:'".GetCatalog('product') ."',
			width:'450px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'productid',
					textField:'productname',
					url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'productid',title:'".GetCatalog('productid')."',width:'50px'},
						{field:'productname',title:'".GetCatalog('productname')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.productname;
		}},
		{
			field:'price',
			title:'".GetCatalog('price') ."',
			width:'120px',
			editor:{
				type:'numberbox',
				options:{
					precision:2,
					required:true,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'currencyid',
			title:'".GetCatalog('currency') ."',
			width:'100px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'currencyid',
					textField:'currencyname',
					required:true,
					url:'".Yii::app()->createUrl('admin/currency/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'currencyid',title:'".GetCatalog('currencyid')."',width:'50px'},
						{field:'symbol',title:'".GetCatalog('symbol')."',width:'100px'},
						{field:'currencyname',title:'".GetCatalog('currencyname')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.currencyname;
		}},
		{
			field:'biddate',
			title:'".GetCatalog('biddate') ."',
			width:'120px',
			editor:{
				type:'datebox',
				options:{
					required:true,
				},
			},
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'deliverytime',
			title:'".GetCatalog('deliverytime') ."',
			width:'110px',
			editor:{
				type:'numberbox',
				options:{
					precision:0,
					required:true,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'purchasinggroupid',
			title:'".GetCatalog('purchasinggroup') ."',
			width:'150px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'purchasinggroupid',
					textField:'purchasinggroupcode',
					url:'".$this->createUrl('purchasinggroup/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'purchasinggroupid',title:'".GetCatalog('purchasinggroupid')."',width:'50px'},
						{field:'purchasinggroupcode',title:'".GetCatalog('purchasinggroupcode')."',width:'100px'},
						{field:'description',title:'".GetCatalog('description')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.purchasinggroupcode;
		}},
		{
			field:'toleransidown',
			title:'".GetCatalog('toleransidown') ."',
			width:'150px',
			editor:{
				type:'numberbox',
				options:{
					precision:2,
					required:true,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'toleransiup',
			title:'".GetCatalog('toleransiup') ."',
			width:'150px',
			editor:{
				type:'numberbox',
				options:{
					precision:2,
					required:true,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
			{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus') ."',
			width:80,
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}}",
	'searchfield'=> array ('purchinforecid','supplier','productname')
));