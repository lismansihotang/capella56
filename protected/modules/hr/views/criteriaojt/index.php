<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'criteriaojtid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/criteriaojt/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/criteriaojt/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/criteriaojt/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/criteriaojt/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/criteriaojt/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/criteriaojt/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/criteriaojt/downxls'),
	'columns'=>"
		{
			field:'criteriaojtid',
			title:'".getCatalog('criteriaojtid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'criteriaojtname',
			title:'".getCatalog('criteriaojtname')."', 
			editor:'text',
			width:'500px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'description',
			title:'". getCatalog('description') ."',
			width:'350px',
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
	'searchfield'=> array ('criteriaojtid','criteriaojtname','description')
));