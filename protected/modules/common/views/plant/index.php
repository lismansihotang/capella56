<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'plantid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/plant/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/plant/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/plant/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/plant/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/plant/upload'),
	'downpdf'=>Yii::app()->createUrl('common/plant/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/plant/downxls'),
	'columns'=>"
		{
			field:'plantid',
			title:'". GetCatalog('plantid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'companyid',
			title:'". GetCatalog('company') ."',
			width:'250px',
			sortable: true,
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'companyid',
					textField:'companyname',
					url:'". Yii::app()->createUrl('admin/company/index',array('grid'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'companyid',title:'". GetCatalog('companyid')."',width:'50px'},
						{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
					]]
				}	
			},
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'plantcode',
			title:'". GetCatalog('plantcode') ."',
			width:'150px',
			editor:{
				type: 'textbox',
				options: {
					required: true
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'addresstoname',
			title:'". GetCatalog('addressto') ."',
			width:'250px',
			editor:{
				type: 'textbox',
				options: {
          required: true,
          multiline:true
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'limitpo',
			title:'". GetCatalog('limitpo') ."',
			editor:{
				type:'numberbox',
				options:{
					precision:4,
					decimalSeparator:',',
					groupSeparator:'.',
					value:0,
				}
			},
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
	
		{
			field:'description',
			title:'". GetCatalog('description') ."',
			width:'250px',
			editor:{
				type: 'textbox',
				options: {
					required: true
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'". GetCatalog('recordstatus') ."',
			width:'50px',
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
				} else {
					return '';
				}
			}}",
	'searchfield'=> array ('plantid','company','plantcode','description')
));
