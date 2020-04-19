<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'employeestatusid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/employeestatus/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/employeestatus/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/employeestatus/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/employeestatus/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/employeestatus/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/employeestatus/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/employeestatus/downxls'),
	'columns'=>"
		{
			field:'employeestatusid',
			title:'".getCatalog('employeestatusid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'employeestatusname',
			title:'".getCatalog('employeestatusname')."', 
			editor: {
				type: 'textbox',
				options: {
					required: true
				}
			},
			width:'150px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'taxvalue',
			title:'".getCatalog('taxvalue')."', 
			editor: {
				type: 'numberbox',
				options: {
					required:true,
					precision:4,
					decimalSeparator:',',
					groupSeparator:'.',
					value:0,
				},
			},
			width:'150px',
			sortable:'true',
			formatter: function(value,row,index){
				return formatnumber('',value);
			}
		},
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
	'searchfield'=> array ('employeestatusid','employeestatusname')
));