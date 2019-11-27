<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'romawiid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/romawi/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/romawi/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/romawi/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/romawi/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/romawi/upload'),
	'downpdf'=>Yii::app()->createUrl('common/romawi/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/romawi/downxls'),
	'columns'=>"
		{
			field:'romawiid',
			title:'". GetCatalog('romawiid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'monthcal',
			title:'". GetCatalog('monthcal') ."',
			width:'150px',
			editor: {
				type: 'numberbox',
				options: {
					required: true
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'monthrm',
			title:'". GetCatalog('monthrm') ."',
			width:'150px',
			editor: {
				type: 'validatebox',
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
			width:'80px',
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
	'searchfield'=> array ('romawiid','monthcal','monthrm')
));
