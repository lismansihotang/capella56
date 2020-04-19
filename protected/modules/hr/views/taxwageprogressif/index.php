<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'taxwageprogressifid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/taxwageprogressif/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/taxwageprogressif/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/taxwageprogressif/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/taxwageprogressif/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/taxwageprogressif/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/taxwageprogressif/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/taxwageprogressif/downxls'),
	'columns'=>"
		{
			field:'taxwageprogressifid',
			title:'".getCatalog('taxwageprogressifid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'description',
			title:'".getCatalog('description')."', 
			editor:'text',
			width:'150px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'minvalue',
			title:'".getCatalog('minvalue') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'maxvalue',
			title:'".getCatalog('maxvalue') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'valuepercent',
			title:'".getCatalog('valuepercent') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
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
	'searchfield'=> array ('taxwageprogressifid','description')
));