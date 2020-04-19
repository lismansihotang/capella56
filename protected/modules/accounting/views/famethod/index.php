<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'famethodid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('accounting/famethod/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/famethod/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/famethod/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/famethod/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('accounting/famethod/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/famethod/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/famethod/downxls'),
	'columns'=>"
		{
			field:'famethodid',
			title:localStorage.getItem('catalogfamethodid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'methodname',
			title:localStorage.getItem('catalogmethodname'),
			editor:{
				type: 'textbox',
				options: {
					required:true
				}
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'recordstatus',
			title:localStorage.getItem('catalogrecordstatus'),
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
			}
		},",
	'searchfield'=> array ('famethodid','methodname')
));