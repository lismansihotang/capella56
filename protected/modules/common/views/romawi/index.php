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
	'downdoc'=>Yii::app()->createUrl('common/romawi/downdoc'),
	'columns'=>"
		{
			field:'romawiid',
			title: localStorage.getItem('catalogromawiid'),
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'monthcal',
			title: localStorage.getItem('catalogmonthcal'),
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
			title: localStorage.getItem('catalogmonthrm'),
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
			title: localStorage.getItem('catalogrecordstatus'),
			width:'80px',
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
	'searchfield'=> array ('romawiid','monthcal','monthrm')
));