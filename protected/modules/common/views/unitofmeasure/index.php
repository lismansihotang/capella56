<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'unitofmeasureid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/unitofmeasure/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/unitofmeasure/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/unitofmeasure/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/unitofmeasure/upload'),
	'downpdf'=>Yii::app()->createUrl('common/unitofmeasure/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/unitofmeasure/downxls'),
	'columns'=>"
		{
			field:'unitofmeasureid',
			title:'". GetCatalog('unitofmeasureid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'uomcode',
			title:'". GetCatalog('uomcode') ."',
			width:'150px',
			editor:{
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
			field:'description',
			title:'". GetCatalog('description') ."',
			width:'250px',
			editor:{
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
	'searchfield'=> array ('unitofmeasureid','uomcode','description')
));
