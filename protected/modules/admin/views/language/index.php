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
	'downdoc'=>Yii::app()->createUrl('admin/language/downdoc'),
	'columns'=>"
		{
			field:'languageid',
			title:localStorage.getItem('cataloglanguageid'), 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'languagename',
			title:localStorage.getItem('cataloglanguagename'), 
			editor:'text',
			width:'150px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'recordstatus',
			title:localStorage.getItem('catalogrecordstatus'),
			align:'center',
			width:'80px',
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
	'searchfield'=> array ('languageid','languagename')
));