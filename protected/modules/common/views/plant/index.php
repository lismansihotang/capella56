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
	'downdoc'=>Yii::app()->createUrl('common/plant/downdoc'),
	'columns'=>"
		{
			field:'plantid',
			title: localStorage.getItem('catalogplantid'),
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'companyid',
			title: localStorage.getItem('catalogcompany'),
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
					loadMsg:  localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'companyid',title: localStorage.getItem('catalogcompanyid'),width:'50px'},
						{field:'companyname',title: localStorage.getItem('catalogcompanyname'),width:'200px'},
					]]
				}	
			},
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'plantcode',
			title: localStorage.getItem('catalogplantcode'),
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
			title: localStorage.getItem('catalogaddressto'),
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
			field:'limitpo',
			title: localStorage.getItem('cataloglimitpo'),
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
			title: localStorage.getItem('catalogdescription'),
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
			title: localStorage.getItem('catalogrecordstatus'),
			width:'50px',
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
			}}",
	'searchfield'=> array ('plantid','company','plantcode','description')
));