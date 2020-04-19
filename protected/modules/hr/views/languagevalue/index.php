<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'languagevalueid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/languagevalue/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/languagevalue/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/languagevalue/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/languagevalue/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/languagevalue/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/languagevalue/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/languagevalue/downxls'),
	'columns'=>"
		{
			field:'languagevalueid',
			title:'".getCatalog('languagevalueid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'languagevaluename',
			title:'".getCatalog('languagevaluename')."', 
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
	'searchfield'=> array ('languagevalueid','languagevaluename')
));