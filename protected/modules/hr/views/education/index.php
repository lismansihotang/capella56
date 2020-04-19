<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'educationid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/education/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/education/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/education/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/education/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/education/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/education/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/education/downxls'),
	'columns'=>"
		{
			field:'educationid',
			title:'".getCatalog('educationid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'educationname',
			title:'".getCatalog('educationname')."', 
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
	'searchfield'=> array ('educationid','educationname')
));