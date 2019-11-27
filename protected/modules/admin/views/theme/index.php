<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'themeid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/theme/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/theme/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/theme/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/theme/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/theme/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/theme/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/theme/downxls'),
	'columns'=>"
		{
			field:'themeid',
			title:'".GetCatalog('themeid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'themename',
			title:'".GetCatalog('themename') ."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'description',
			title:'".GetCatalog('description') ."',
			editor:'text',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'themeprev',
			title:'".GetCatalog('themeprev') ."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus') ."',
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},",
	'searchfield'=> array ('themeid','themename','description','themeprev')
));
