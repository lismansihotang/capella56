<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'pricecategoryid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/pricecategory/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/pricecategory/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/pricecategory/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/pricecategory/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/pricecategory/upload'),
	'downpdf'=>Yii::app()->createUrl('common/pricecategory/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/pricecategory/downxls'),
	'columns'=>"
		{
			field:'pricecategoryid',
			title:'". GetCatalog('pricecategoryid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'categoryname',
			title:'". GetCatalog('categoryname') ."',
			width:'250px',
			editor:'text',
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
	'searchfield'=> array ('pricecategoryid','categoryname')
));
