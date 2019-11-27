<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'countryid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/country/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/country/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/country/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/country/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/country/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/country/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/country/downxls'),
	'columns'=>"
		{
			field:'countryid',
			title:'".GetCatalog('countryid')."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'countrycode',
			title:'".GetCatalog('countrycode')."',
			editor:'text',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'countryname',
			title:'".GetCatalog('countryname')."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus')."',
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/icons/ok.png'."\"></img>';
				} else {
					return '';
				}
			}
		},",
	'searchfield'=> array ('countryid','countrycode','countryname')
));
