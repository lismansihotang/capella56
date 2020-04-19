<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'wagetypeid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/wagetype/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/wagetype/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/wagetype/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/wagetype/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/wagetype/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/wagetype/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/wagetype/downxls'),
	'columns'=>"
		{
			field:'wagetypeid',
			title:'".getCatalog('wagetypeid') ."',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'wagename',
			title:'".getCatalog('wagename') ."',
			width:'350px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'ispph',
			title:'".getCatalog('ispph') ."',
			width:'80px',
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'ispayroll',
			title:'".getCatalog('ispayroll') ."',
			width:'80px',
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'isprint',
			title:'".getCatalog('isprint') ."',
			width:'80px',
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'percentage',
			title:'".getCatalog('percentage') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'maxvalue',
			title:'".getCatalog('maxvalue') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'currencyid',
			title:'".getCatalog('currency') ."',
			width:'150px',
			pagination:true,
			required:true,
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'currencyid',
					textField:'currencyname',
					url:'".Yii::app()->createUrl('admin/currency/index',array('grid'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'currencyid',title:'".getCatalog('currencyid')."',width:'50px'},
						{field:'currencyname',title:'".getCatalog('currencyname')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.currencyname;
		}},
		{
			field:'isrutin',
			title:'".getCatalog('isrutin') ."',
			width:'80px',
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'paidbycompany',
			title:'".getCatalog('paidbycompany') ."',
			width:'80px',
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'pphbycompany',
			title:'".getCatalog('pphbycompany') ."',
			width:'80px',
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'recordstatus',
			title:'".getCatalog('recordstatus') ."',
			width:'80px',
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
	'searchfield'=> array ('wagetypeid','wagename')
));