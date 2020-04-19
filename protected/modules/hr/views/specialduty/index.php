<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'specialdutyid',
	'formtype'=>'master',
	'ispost'=>1,
	'isreject'=>1,
	'url'=>Yii::app()->createUrl('hr/specialduty/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/specialduty/save'),
	'updateurl'=>Yii::app()->createUrl('hr/specialduty/save'),
	'destroyurl'=>Yii::app()->createUrl('hr/specialduty/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('hr/specialduty/approve'),
	'uploadurl'=>Yii::app()->createUrl('hr/specialduty/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/specialduty/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/specialduty/downxls'),
	'columns'=>"
		{
			field:'specialdutyid',
			title:'".GetCatalog('specialdutyid')."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'plantid',
			title:'".GetCatalog('plant')."',
			width:'200px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'plantid',
					textField:'plantcode',
					pagination:true,
					url:'". Yii::app()->createUrl('common/plant/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'plantid',title:'".GetCatalog('plantid')."',width:'80px'},
						{field:'plantcode',title:'".GetCatalog('plantcode')."',width:'200px'}
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.plantcode;
			}
		},
		{
			field:'specialdutyno',
			title:'".GetCatalog('specialdutyno')."',
			editor:'textbox',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}}, 
		{
			field:'specialdutydate',
			title:'".GetCatalog('specialdutydate')."',
			editor: {
				type:'datebox'
			},
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'employeeid',
			title:'".GetCatalog('employee')."',
			width:'200px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'employeeid',
					textField:'fullname',
					pagination:true,
					url:'". Yii::app()->createUrl('hr/employee/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'employeeid',title:'".GetCatalog('employeeid')."',width:'80px'},
						{field:'fullname',title:'".GetCatalog('fullname')."',width:'200px'}
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.fullname;
			}
		},
		{
			field:'positionid',
			title:'".GetCatalog('position')."',
			width:'200px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'positionid',
					textField:'positionname',
					pagination:true,
					url:'". Yii::app()->createUrl('hr/position/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'positionid',title:'".GetCatalog('positionid')."',width:'80px'},
						{field:'positionname',title:'".GetCatalog('positionname')."',width:'200px'}
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.positionname;
			}
		},
		{
			field:'orgstructureid',
			title:'".GetCatalog('orgstructure')."',
			width:'150px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'orgstructureid',
					textField:'structurename',
					pagination:true,
					url:'". Yii::app()->createUrl('hr/orgstructure/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'orgstructureid',title:'".GetCatalog('orgstructureid')."',width:'80px'},
						{field:'structurename',title:'".GetCatalog('structurename')."',width:'200px'}
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.structurename;
			}
		},
		{
			field:'specialdutytypeid',
			title:'".GetCatalog('specialdutytype')."',
			width:'150px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'specialdutytypeid',
					textField:'specialdutytypename',
					pagination:true,
					url:'". Yii::app()->createUrl('hr/specialdutytype/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'specialdutytypeid',title:'".GetCatalog('specialdutytypeid')."',width:'80px'},
						{field:'specialdutytypename',title:'".GetCatalog('specialdutytypename')."',width:'200px'}
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.specialdutytypename;
			}
		},
		{
			field:'description',
			title:'".GetCatalog('description')."',
			editor:'textbox',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return value;
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
	'searchfield'=> array ('specialdutyid','specialdutyno','employeename','specialdutytypename'),	
));