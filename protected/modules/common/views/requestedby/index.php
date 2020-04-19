<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'requestedbyid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/requestedby/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/requestedby/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/requestedby/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/requestedby/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/requestedby/upload'),
	'downpdf'=>Yii::app()->createUrl('common/requestedby/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/requestedby/downxls'),
	'downdoc'=>Yii::app()->createUrl('common/requestedby/downdoc'),
	'columns'=>"
		{
			field:'requestedbyid',
			title:'".getCatalog('requestedbyid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'requestedbycode',
			title:'".getCatalog('requestedbycode')."', 
			editor:{
				type: 'textbox',
				options:{
					required:true
				}
			},
			width:'150px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'description',
			title:'".getCatalog('description')."', 
			editor:{
				type: 'textbox',
				options:{
					required:true
				}
			},
			width:'250px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'recordstatus',
			title:'".getCatalog('recordstatus')."',
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
	'searchfield'=> array ('requestedbyid','requestedbycode','description')
));