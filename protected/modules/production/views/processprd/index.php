<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'processprdid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('production/processprd/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('production/processprd/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('production/processprd/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('production/processprd/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('production/processprd/upload'),
	'downpdf'=>Yii::app()->createUrl('production/processprd/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/processprd/downxls'),
	'columns'=>"
		{
			field:'processprdid',
			title:'".getCatalog('processprdid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'processprdname',
			title:'".getCatalog('processprdname')."', 
			editor:'text',
			width:'150px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'recordstatus',title:'".getCatalog('recordstatus')."',
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable:'true',
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
				} else {
					return '';
				}
			}
		}",
	'searchfield'=> array ('processprdid','processprdname')
));