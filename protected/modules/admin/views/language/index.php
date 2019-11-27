<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'languageid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/language/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/language/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/language/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/language/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/language/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/language/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/language/downxls'),
	'columns'=>"
		{
			field:'languageid',
			title:'".getCatalog('languageid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'languagename',
			title:'".getCatalog('languagename')."', 
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
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/icons/ok.png'."\"></img>';
				} else {
					return '';
				}
			}
		}",
	'searchfield'=> array ('languageid','languagename')
));
