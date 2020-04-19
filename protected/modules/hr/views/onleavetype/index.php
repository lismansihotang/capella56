<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'onleavetypeid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/onleavetype/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/onleavetype/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/onleavetype/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/onleavetype/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/onleavetype/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/onleavetype/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/onleavetype/downxls'),
	'columns'=>"
		{
			field:'onleavetypeid',
			title:'".getCatalog('onleavetypeid') ."',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'onleavename',
			title:'".getCatalog('onleavename') ."',
			width:'200px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'cutimax',
			title:'".getCatalog('cutimax') ."',
			width:150,
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'cutistart',
			title:'".getCatalog('cutistart') ."',
			width:150,
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'snroid',
			title:'".getCatalog('snro') ."',
			width:'150px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'snroid',
					textField:'description',
					pagination:true,
					url:'".Yii::app()->createUrl('admin/snro/index',array('grid'=>true)) ."',
					fitColumns:true,
					pagination: true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'snroid',title:'".getCatalog('snroid')."',width:'50px'},
						{field:'description',title:'".getCatalog('description')."',width:'200px'}
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.description;
		}},
		{
			field:'absstatusid',
			title:'".getCatalog('absstatus') ."',
			width:'150px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absstatusid',
					textField:'longstat',
					pagination:true,
					url:'".$this->createUrl('absstatus/index',array('grid'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absstatusid',title:'".getCatalog('absstatusid')."',width:'50px'},
						{field:'longstat',title:'".getCatalog('longstat')."',width:'200px'}
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.longstat;
		}},
		{
			field:'recordstatus',
			title:'".getCatalog('recordstatus') ."',
			width:80,
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}}",
	'searchfield'=> array ('onleavetypeid','onleavename','snrodesc','absstatus')
));