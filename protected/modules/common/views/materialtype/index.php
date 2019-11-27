<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'materialtypeid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/materialtype/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/materialtype/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/materialtype/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/materialtype/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/materialtype/upload'),
	'downpdf'=>Yii::app()->createUrl('common/materialtype/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/materialtype/downxls'),
	'columns'=>"
		{
			field:'materialtypeid',
			title:'". GetCatalog('materialtypeid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'materialtypecode',
			title:'". GetCatalog('materialtypecode') ."',
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
					required:true,
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'fg',
			title:'". getCatalog('fg?') ."',
			width:80,
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
				} else {
					return '';
				}
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
	'searchfield'=> array ('materialtypeid','materialtypecode','description')
));
