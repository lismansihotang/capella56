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
	'downdoc'=>Yii::app()->createUrl('common/materialtype/downdoc'),
	'columns'=>"
		{
			field:'materialtypeid',
			title: localStorage.getItem('catalogmaterialtypeid'),
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'materialtypecode',
			title: localStorage.getItem('catalogmaterialtypecode'),
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
			title: localStorage.getItem('catalogdescription'),
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
			title: localStorage.getItem('catalogfg'),
			width:80,
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
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
	'searchfield'=> array ('materialtypeid','materialtypecode','description')
));