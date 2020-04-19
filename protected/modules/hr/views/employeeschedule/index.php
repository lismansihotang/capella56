<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'employeescheduleid',
	'formtype'=>'master',
	'ispost'=>1,
	'isreject'=>1,
	'url'=>Yii::app()->createUrl('hr/employeeschedule/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/employeeschedule/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/employeeschedule/save',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('hr/employeeschedule/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('hr/employeeschedule/reject',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/employeeschedule/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/employeeschedule/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/employeeschedule/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/employeeschedule/downxls'),
	'writebuttons'=>"
		<a href='javascript:void(0)' title='".Getcatalog('copy')."' class='easyui-linkbutton' iconCls='icon-bom' plain='true' onclick='copyemployeeschedule()'></a>
	",
	'addonscripts'=>"
		function copyemployeeschedule() {
			var ss = [];
			var rows = $('#dg-employeeschedule').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss.push(row.employeescheduleid);
			}
			jQuery.ajax({'url':'".$this->createUrl('employeeschedule/copyemployeeschedule')."',
				'data':{'id':ss},'type':'post','dataType':'json',
				'success':function(data)
				{
					show('Pesan',data.msg);
					$('#dg-employeeschedule').edatagrid('reload');				
				} ,
				'cache':false});
		};
	",
	'columns'=>"
		{
			field:'employeescheduleid',
			title:'".getCatalog('id') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'employeeid',
			title:'".getCatalog('employee') ."',
			width:'250px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'employeeid',
					textField:'fullname',
					pagination:true,
					required:true,
					url:'".$this->createUrl('employee/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'employeeid',title:'".getCatalog('employeeid')."',width:'50px'},
						{field:'fullname',title:'".getCatalog('fullname')."',width:'250px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
			return row.fullname;
		}},
		{
			field:'month',
			title:'".getCatalog('month') ."',
			width:'50px',
			editor:'text',
			required:true,
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'year',
			title:'".getCatalog('year') ."',
			width:'50px',
			required:true,
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'d1',
			title:'".getCatalog('d1') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d1name;
		}},
		{
			field:'d2',
			title:'".getCatalog('d2') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d2name;
		}},
		{
			field:'d3',
			title:'".getCatalog('d3') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d3name;
		}},
		{
			field:'d4',
			title:'".getCatalog('d4') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d4name;
		}},
		{
			field:'d5',
			title:'".getCatalog('d5') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d5name;
		}},
		{
			field:'d6',
			title:'".getCatalog('d6') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d6name;
		}},
		{
			field:'d7',
			title:'".getCatalog('d7') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}		
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d7name;
		}},
		{
			field:'d8',
			title:'".getCatalog('d8') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}		
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d8name;
		}},
		{
			field:'d9',
			title:'".getCatalog('d9') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}		
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d9name;
		}},
		{
			field:'d10',
			title:'".getCatalog('d10') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}		
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d10name;
		}},
		{
			field:'d11',
			title:'".getCatalog('d11') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}		
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d11name;
		}},
		{
			field:'d12',
			title:'".getCatalog('d12') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d12name;
		}},
		{
			field:'d13',
			title:'".getCatalog('d13') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d13name;
		}},
		{
			field:'d14',
			title:'".getCatalog('d14') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d14name;
		}},
		{
			field:'d15',
			title:'".getCatalog('d15') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d15name;
		}},
		{
			field:'d16',
			title:'".getCatalog('d16') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}		
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d16name;
		}},
		{
			field:'d17',
			title:'".getCatalog('d17') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}		
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d17name;
		}},
		{
			field:'d18',
			title:'".getCatalog('d18') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d18name;
		}},
		{
			field:'d19',
			title:'".getCatalog('d19') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d19name;
		}},
		{
			field:'d20',
			title:'".getCatalog('d20') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}		
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d20name;
		}},
		{
			field:'d21',
			title:'".getCatalog('d21') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d21name;
		}},
		{
			field:'d22',
			title:'".getCatalog('d22') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}		
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d22name;
		}},
		{
			field:'d23',
			title:'".getCatalog('d23') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d23name;
		}},
		{
			field:'d24',
			title:'".getCatalog('d24') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d24name;
		}},
		{
			field:'d25',
			title:'".getCatalog('d25') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d25name;
		}},
		{
			field:'d26',
			title:'".getCatalog('d26') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}		
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d26name;
		}},
		{
			field:'d27',
			title:'".getCatalog('d27') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}		
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d27name;
		}},
		{
			field:'d28',
			title:'".getCatalog('d28') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}		
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d28name;
		}},
		{
			field:'d29',
			title:'".getCatalog('d29') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}		
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d29name;
		}},
		{
			field:'d30',
			title:'".getCatalog('d30') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}		
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d30name;
		}},
		{
			field:'d31',
			title:'".getCatalog('d31') ."',
			width:'50px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'absscheduleid',
					textField:'absschedulename',
					pagination:true,
					required:true,
					url:'".$this->createUrl('absschedule/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'absscheduleid',title:'".getCatalog('absscheduleid')."',width:'50px'},
						{field:'absschedulename',title:'".getCatalog('absschedulename')."',width:'150px'},
					]]
				}		
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.d31name;
		}},
		{
			field:'recordstatus',
			title:'".getCatalog('recordstatus') ."',
			align:'left',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value
		}},",
	'searchfield'=> array ('employeescheduleid','employeename','month','year')
));