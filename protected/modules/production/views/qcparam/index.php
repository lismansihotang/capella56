<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'qcparamid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('production/qcparam/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('production/qcparam/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('production/qcparam/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('production/qcparam/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('production/qcparam/upload'),
	'downpdf'=>Yii::app()->createUrl('production/qcparam/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/qcparam/downxls'),
	'columns'=>"
		{
			field:'qcparamid',
			title:'".GetCatalog('qcparamid')."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'qcparamname',
			title:'".GetCatalog('qcparamname')."',
			editor: {
				type: 'textbox',
				options:{
					required: true
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
			title:'".GetCatalog('recordstatus')."',
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
				} else {
					return '';
				}
			}
		},",
	'searchfield'=> array ('qcparamid','qcparamname')
));