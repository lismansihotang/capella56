<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'emergencytypeid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/emergencytype/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/emergencytype/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/emergencytype/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/emergencytype/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/emergencytype/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/emergencytype/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/emergencytype/downxls'),
	'columns'=>"
		{
			field:'emergencytypeid',
			title:'".getCatalog('emergencytypeid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'emergencyname',
			title:'".getCatalog('emergencyname')."', 
			editor:'text',
			width:'150px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'reporttype',
			title:'". getCatalog('reporttype') ."',
			width:'250px',
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
	'searchfield'=> array ('emergencytypeid','emergencyname','reporttype')
));