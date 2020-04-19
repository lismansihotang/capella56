<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'materialstatusid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/materialstatus/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/materialstatus/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/materialstatus/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/materialstatus/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/materialstatus/upload'),
	'downpdf'=>Yii::app()->createUrl('common/materialstatus/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/materialstatus/downxls'),
	'downdoc'=>Yii::app()->createUrl('common/materialstatus/downdoc'),
	'columns'=>"
		{
			field:'materialstatusid',
			title:'".getCatalog('materialstatusid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'materialstatusname',
			title:'".getCatalog('materialstatusname')."', 
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
	'searchfield'=> array ('materialstatusid','materialstatusname')
));