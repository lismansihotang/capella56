<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'employeetypeid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/employeetype/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/employeetype/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/employeetype/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/employeetype/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/employeetype/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/employeetype/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/employeetype/downxls'),
	'columns'=>"
		{
			field:'employeetypeid',
			title:'". getCatalog('employeetypeid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'employeetypename',
			title:'". getCatalog('employeetypename') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'snroid',
			title:'". getCatalog('snro') ."',
			width:'250px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'snroid',
					textField:'description',
					url:'". Yii::app()->createUrl('admin/snro/index',array('grid'=>true)) ."',
					fitColumns:true,
					pagination: true,
					loadMsg: '". getCatalog('pleasewait')."',
					columns:[[
						{field:'snroid',title:'". getCatalog('snroid')."',width:'50px'},
						{field:'description',title:'". getCatalog('description')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.snrodesc;
		}},
		{
			field:'sicksnroid',
			title:'". getCatalog('sicksnroid') ."',
			width:'250px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'snroid',
					textField:'description',
					url:'". Yii::app()->createUrl('admin/snro/index',array('grid'=>true)) ."',
					fitColumns:true,
					pagination: true,
					loadMsg: '". getCatalog('pleasewait')."',
					columns:[[
						{field:'snroid',title:'". getCatalog('snroid')."',width:'50px'},
						{field:'description',title:'". getCatalog('description')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.sicksnrodesc;
		}},
		{
			field:'sickstatusid',
			title:'". getCatalog('sickstatus') ."',
			width:'250px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absstatusid',
					textField:'longstat',
					url:'". $this->createUrl('absstatus/index',array('grid'=>true)) ."',
					fitColumns:true,
					pagination: true,
					loadMsg: '". getCatalog('pleasewait')."',
					columns:[[
						{field:'absstatusid',title:'". getCatalog('absstatusid')."',width:'50px'},
						{field:'shortstat',title:'". getCatalog('shortstat')."',width:'50px'},
						{field:'longstat',title:'". getCatalog('longstat')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.sicklongstat;
		}},
		{
			field:'recordstatus',
			title:'". getCatalog('recordstatus') ."',
			width:'80px',
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}}",
	'searchfield'=> array ('employeetypeid','employeetypename','snro','sicksnro')
));