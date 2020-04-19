<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'mealtypeid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/mealtype/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/mealtype/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/mealtype/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/mealtype/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/mealtype/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/mealtype/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/mealtype/downxls'),
	'columns'=>"
		{
			field:'mealtypeid',
			title:'".getCatalog('mealtypeid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'mealtypename',
			title:'".getCatalog('mealtypename')."', 
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
	'searchfield'=> array ('mealtypeid','mealtypename','description')
));