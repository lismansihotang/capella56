<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'addresstypeid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/addresstype/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/addresstype/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/addresstype/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/addresstype/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/addresstype/upload'),
	'downpdf'=>Yii::app()->createUrl('common/addresstype/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/addresstype/downxls'),
	'downdoc'=>Yii::app()->createUrl('common/addresstype/downdoc'),
	'columns'=>"
		{
			field:'addresstypeid',
			title:'". GetCatalog('addresstypeid') ."',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'addresstypename',
			title:'". GetCatalog('addresstypename') ."',
			width:'200px',
			editor: {
				type: 'textbox',
				options:{
					required:true
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
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
			}},",
	'searchfield'=> array ('addresstypeid','addresstypename')
));