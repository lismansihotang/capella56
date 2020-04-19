<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'empplanid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'isupload'=>0,
	'url'=>Yii::app()->createUrl('hr/empplan/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('hr/empplan/getData'),
	'saveurl'=>Yii::app()->createUrl('hr/empplan/save'),
	'destroyurl'=>Yii::app()->createUrl('hr/empplan/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('hr/empplan/approve'),
	'rejecturl'=>Yii::app()->createUrl('hr/empplan/delete'),
	'uploadurl'=>Yii::app()->createUrl('hr/empplan/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/empplan/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/empplan/downxls'),
	'columns'=>"
		{
			field:'empplanid',
			title:'".GetCatalog('empplanid')."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'empplanno',
			title:'".GetCatalog('empplanno')."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'empplanname',
			title:'".GetCatalog('empplanname')."',
			sortable: true,
			width:'400px',
			formatter: function(value,row,index){
				return value;
		}}, 
		{
			field:'empplandate',
			title:'".GetCatalog('empplandate')."',
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'useraccess',
			title:'".GetCatalog('useraccess')."',
			editor:'text',
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus')."',
			align:'left',
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}}",
	'searchfield'=> array ('empplanid','empplanno','empplanname','empplandate'),
	'headerform'=> "
		<input type='hidden' name='empplanid' id='empplanid'></input>
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('empplandate')."</td>
				<td><input class='easyui-datebox' name='empplandate' id='empplandate' ></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('empplanname')."</td>
				<td><input class='easyui-textbox' name='empplanname' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
		</table>
	",
	'loadsuccess' => "
	",
	'addload' => "
		$('#empplandate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'empplandetailid',
			'urlsub'=>Yii::app()->createUrl('hr/empplan/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('hr/empplan/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('hr/empplan/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('hr/empplan/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('hr/empplan/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'fullname',title:'".GetCatalog('employee')."',width:'200px'},
				{field:'description',title:'".GetCatalog('description')."',width:'200px'},
				{field:'objvalue',title:'".GetCatalog('objvalue')."',width:'200px'},
				{field:'startdate',title:'".GetCatalog('startdate')."',width:'200px'},
				{field:'enddate',title:'".GetCatalog('enddate')."',width:'200px'},
			",
			'columns'=>"
				{
					field:'empplanid',
					title:'".GetCatalog('empplanid')."',
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'empplandetailid',
					title:'".GetCatalog('empplandetailid')."',
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
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
							url:'". Yii::app()->createUrl('hr/employee/index',array('grid'=>true))."',
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
					field:'description',
					title:'".GetCatalog('description')."',
					width:'300px',
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'objvalue',
					title:'".GetCatalog('objvalue')."',
					width:'100px',
					editor:'numberbox',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'startdate',
					title:'".GetCatalog('startdate')."',
					width:'120px',
					editor: {
						type: 'datebox',
						options:{
						formatter:dateformatter,
						required:true,
						parser:dateparser
						}
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'enddate',
					title:'".GetCatalog('enddate')."',
					width:'120px',
					editor: {
						type: 'datebox',
						options:{
						formatter:dateformatter,
						required:true,
						parser:dateparser
						}
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				}
			"
		),
	),	
));