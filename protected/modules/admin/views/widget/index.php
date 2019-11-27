<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'widgetid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/widget/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/widget/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/widget/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/widget/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/widget/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/widget/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/widget/downxls'),
	'columns'=>"
		{
			field: 'widgetid',
			title: '".GetCatalog('widgetid') ."',
			width: '30px',
			sortable: true,
			formatter: function (value, row, index) {
				return value;
		}},
		{
			field: 'widgetname',
			title: '".GetCatalog('widgetname') ."',
			editor: 'text',
			width: '150px',
			sortable: true,
			formatter: function (value, row, index) {
				return value;
		}},
		{
			field: 'widgettitle',
			title: '".GetCatalog('widgettitle') ."',
			editor: 'text',
			width: '150px',
			sortable: true,
			formatter: function (value, row, index) {
				return value;
		}},
		{
			field: 'widgetversion',
			title: '".GetCatalog('widgetversion') ."',
			editor: 'text',
			width: '100px',
			sortable: true,
			formatter: function (value, row, index) {
				return value;
		}},
		{
			field: 'widgetby',
			title: '".GetCatalog('widgetby') ."',
			editor: 'text',
			width: '150px',
			sortable: true,
			formatter: function (value, row, index) {
				return value;
		}},
		{
			field: 'description',
			title: '".GetCatalog('description') ."',
			editor: 'text',
			width: '250px',
			sortable: true,
			formatter: function (value, row, index) {
				return value;
		}},
		{
			field: 'widgeturl',
			title: '".GetCatalog('widgeturl') ."',
			editor: 'text',
			width: '250px',
			sortable: true,
			formatter: function (value, row, index) {
				return value;
		}},
		{
			field:'moduleid',
			title:'".GetCatalog('modules') ."',
			sortable: true,
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'moduleid',
					pagination:true,
					queryParams: {
						combo:true
					},
					required:true,
					textField:'modulename',
					url:'".$this->createUrl('modules/index',array('grid'=>true)) ."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'moduleid',title:'".GetCatalog('moduleid')."',width:'50px'},
						{field:'modulename',title:'".GetCatalog('modulename')."',width:'150px'},
					]]
				}	
			},
			width:'150px',
			formatter: function(value,row,index){
				return row.modulename;
		}},
		{
			field: 'recordstatus',
			title: '".GetCatalog('recordstatus') ."',
			align: 'center',
			editor: {type: 'checkbox', options: {on: '1', off: '0'}},
			sortable: true,
			formatter: function (value, row, index) {
				if (value == 1) {
					return '<img src=\"".Yii::app()->request->baseUrl ."/images/icons/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},",
	'searchfield'=> array ('widgetid','widgetname','widgettitle')
));
