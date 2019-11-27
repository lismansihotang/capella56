<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'identitytypeid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/identitytype/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/identitytype/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/identitytype/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/identitytype/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/identitytype/upload'),
	'downpdf'=>Yii::app()->createUrl('common/identitytype/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/identitytype/downxls'),
	'columns'=>"
		{
			field:'identitytypeid',
			title:'".getCatalog('identitytypeid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'identitytypename',
			title:'".getCatalog('identitytypename')."', 
			editor:'text',
			width:'250px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'recordstatus',title:'".getCatalog('recordstatus')."',
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
	'searchfield'=> array ('identitytypeid','identitytypename')
));
