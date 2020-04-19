<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'sexid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/sex/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/sex/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/sex/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/sex/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/sex/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/sex/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/sex/downxls'),
	'columns'=>"
		{
			field:'sexid',
			title:'".getCatalog('sexid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'sexname',
			title:'".getCatalog('sexname')."', 
			editor:'text',
			width:'150px',
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
	'searchfield'=> array ('sexid','sexname')
));