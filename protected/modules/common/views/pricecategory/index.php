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
	'downdoc'=>Yii::app()->createUrl('common/pricecategory/downdoc'),
	'columns'=>"
		{
			field:'pricecategoryid',
			title: localStorage.getItem('catalogpricecategoryid'),
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'categoryname',
			title: localStorage.getItem('catalogcategoryname'),
			width:'300px',
			editor: {
        type: 'textbox',
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
	'searchfield'=> array ('pricecategoryid','categoryname')
));