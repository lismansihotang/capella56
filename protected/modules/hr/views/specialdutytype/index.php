<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'specialdutytypeid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/specialdutytype/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/specialdutytype/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/specialdutytype/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/specialdutytype/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/specialdutytype/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/specialdutytype/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/specialdutytype/downxls'),
	'columns'=>"
		{
			field:'specialdutytypeid',
			title:'".getCatalog('specialdutytypeid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'specialdutytypename',
			title:'".getCatalog('specialdutytypename')."', 
			editor:'text',
			width:'150px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'description',
			title:'". getCatalog('description') ."',
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
	'searchfield'=> array ('specialdutytypeid','specialdutytypename','description')
));