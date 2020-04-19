<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'payrollperiodid',
	'formtype'=>'master',
	'isupload'=>0,
	'url'=>Yii::app()->createUrl('hr/payrollperiod/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/payrollperiod/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/payrollperiod/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/payrollperiod/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/payrollperiod/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/payrollperiod/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/payrollperiod/downxls'),
	'columns'=>"
		{
			field:'payrollperiodid',
			title:'".getCatalog('payrollperiodid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'payrollperiodname',
			title:'".getCatalog('payrollperiodname')."', 
			editor:'text',
			width:'150px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'startdate',
			title:'".getCatalog('startdate') ."',
			width:'150px',
			editor:{type:'datebox'},
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'enddate',
			title:'".getCatalog('enddate') ."',
			width:'150px',
			editor:{type:'datebox'},
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'parentperiodid',
			title:'".getCatalog('parent') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					pagination:true,
					idField:'payrollperiodid',
					textField:'payrollperiodname',
					url:'".$this->createUrl('payrollperiod/index',array('grid'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'payrollperiodid',title:'".getCatalog('payrollperiodid')."',width:'50px'},
						{field:'payrollperiodname',title:'".getCatalog('payrollperiodname')."',width:'150px'},
					]]
				}	
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.parentname;
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
	'searchfield'=> array ('payrollperiodid','payrollperiodname')
));