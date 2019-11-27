<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'groupcustomerid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/groupcustomer/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/groupcustomer/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/groupcustomer/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/groupcustomer/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/groupcustomer/upload'),
	'downpdf'=>Yii::app()->createUrl('common/groupcustomer/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/groupcustomer/downxls'),
	'columns'=>"
		{
			field:'groupcustomerid',
			title:'".getCatalog('groupcustomerid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'groupname',
			title:'".getCatalog('groupname')."', 
			editor:'text',
			width:'150px',
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
	'searchfield'=> array ('groupcustomerid','groupname')
));
