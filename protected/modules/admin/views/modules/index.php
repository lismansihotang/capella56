<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'moduleid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/modules/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/modules/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/modules/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/modules/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/modules/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/modules/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/modules/downxls'),
	'downdoc'=>Yii::app()->createUrl('admin/modules/downdoc'),
	'columns'=>"
		{
		field:'moduleid',
		title:localStorage.getItem('catalogmoduleid'),
		sortable: true,
		width:'50px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'modulename',
		title:localStorage.getItem('catalogmodulename'),
		editor:'text',
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'moduledesc',
		title:localStorage.getItem('catalogmoduledesc'),
		editor:'text',
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'moduleicon',
		title:localStorage.getItem('catalogmoduleicon'),
		editor:'text',
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'isinstall',
		title:localStorage.getItem('catalogisinstall'),
		editor:{type:'checkbox',options:{on:'1',off:'0'}},
		align:'center',
		width:'70px',
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
		title:localStorage.getItem('catalogrecordstatus'),
		align:'center',
		width:'50px',
		editor:{type:'checkbox',options:{on:'1',off:'0'}},
		sortable: true,
		formatter: function(value,row,index){
			if (value == 1){
				return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
			} else {
				return '';
			}
	}},",
	'searchfield'=> array ('moduleid','modulename','moduledesc','moduleicon')
));