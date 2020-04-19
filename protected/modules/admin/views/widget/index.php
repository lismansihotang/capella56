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
	'downdoc'=>Yii::app()->createUrl('admin/widget/downdoc'),
	'columns'=>"
		{
			field: 'widgetid',
			title: localStorage.getItem('catalogwidgetid'),
			width: '30px',
			sortable: true,
			formatter: function (value, row, index) {
				return value;
		}},
		{
			field: 'widgetname',
			title: localStorage.getItem('catalogwidgetname'),
			editor: 'text',
			width: '150px',
			sortable: true,
			formatter: function (value, row, index) {
				return value;
		}},
		{
			field: 'widgettitle',
			title: localStorage.getItem('catalogwidgettitle'),
			editor: 'text',
			width: '150px',
			sortable: true,
			formatter: function (value, row, index) {
				return value;
		}},
		{
			field: 'widgetversion',
			title: localStorage.getItem('catalogwidgetversion'),
			editor: 'text',
			width: '100px',
			sortable: true,
			formatter: function (value, row, index) {
				return value;
		}},
		{
			field: 'widgetby',
			title: localStorage.getItem('catalogwidgetby'),
			editor: 'text',
			width: '150px',
			sortable: true,
			formatter: function (value, row, index) {
				return value;
		}},
		{
			field: 'description',
			title: localStorage.getItem('catalogdescription'),
			editor: 'text',
			width: '250px',
			sortable: true,
			formatter: function (value, row, index) {
				return value;
		}},
		{
			field: 'widgeturl',
			title: localStorage.getItem('catalogwidgeturl'),
			editor: 'text',
			width: '250px',
			sortable: true,
			formatter: function (value, row, index) {
				return value;
		}},
		{
			field:'moduleid',
			title:localStorage.getItem('catalogmodules'),
			sortable: true,
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'moduleid',
					pagination:true,
					required:true,
					textField:'modulename',
					url:'".$this->createUrl('modules/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'moduleid',title:localStorage.getItem('catalogmoduleid'),width:'50px'},
						{field:'modulename',title:localStorage.getItem('catalogmodulename'),width:'150px'},
					]]
				}	
			},
			width:'150px',
			formatter: function(value,row,index){
				return row.modulename;
		}},
		{
			field: 'recordstatus',
			title: localStorage.getItem('catalogrecordstatus'),
			align: 'center',
			editor: {type: 'checkbox', options: {on: '1', off: '0'}},
			sortable: true,
			formatter: function (value, row, index) {
				if (value == 1) {
					return '<img src=\"".Yii::app()->request->baseUrl ."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},",
	'searchfield'=> array ('widgetid','widgetname','widgettitle')
));