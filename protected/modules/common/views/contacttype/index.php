<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'contacttypeid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/contacttype/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/contacttype/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/contacttype/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/contacttype/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/contacttype/upload'),
	'downpdf'=>Yii::app()->createUrl('common/contacttype/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/contacttype/downxls'),
	'downdoc'=>Yii::app()->createUrl('common/contacttype/downdoc'),
	'columns'=>"
		{
			field:'contacttypeid',
			title: localStorage.getItem('catalogcontacttypeid'), 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'contacttypename',
			title: localStorage.getItem('catalogcontacttypename'), 
			editor:{
        type: 'textbox',
      },
			width:'150px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'recordstatus',title: localStorage.getItem('catalogrecordstatus'),
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
	'searchfield'=> array ('contacttypeid','contacttypename')
));