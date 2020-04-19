<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'employeeoverid',
	'formtype'=>'masterdetail',
	
	'url'=>Yii::app()->createUrl('hr/employeeover/index',array('grid'=>true)),
	//'urlgetdata'=>Yii::app()->createUrl('hr/employeeover/getData'),
	'approveurl'=>Yii::app()->createUrl('hr/employeeover/approve'),
	'rejecturl'=>Yii::app()->createUrl('hr/employeeover/reject'),
	'saveurl'=>Yii::app()->createUrl('hr/employeeover/save'),
	'destroyurl'=>Yii::app()->createUrl('hr/employeeover/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/employeeover/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/employeeover/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/employeeover/downxls'),
	'columns'=>"
		{
			field:'employeeoverid',
			title:'".getCatalog('employeeoverid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'companyid',
			title:'".getCatalog('company') ."',
			width:'250px',
			sortable: true,
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'overtimeno',
			title:'".getCatalog('overtimeno') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'overtimedate',
			title:'".getCatalog('overtimedate') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'headernote',
			title:'".getCatalog('headernote') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".getCatalog('recordstatus') ."',
			width:'150px',
			align:'left',
			sortable: true,
			formatter: function(value,row,index){
				return value
		}},",
	'searchfield'=> array ('employeeoverid','overtimeno','overtimedate'),
	'headerform'=> "
		<input type='hidden' name='employeeoverid' id='employeeoverid'></input>
			<table cellpadding='5'>
			<tr>
				<td>".getCatalog('overtimedate')."</td>
				<td><input class='easyui-datebox' id='overtimedate' name='overtimedate' ></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('company')."</td>
				<td><select class='easyui-combogrid' id='companyid' name='companyid' style='width:250px' data-options=\"
								panelWidth: '500px',
								required: true,
								idField: 'companyid',
								textField: 'companyname',
								pagination:true,
								mode:'remote',
								method: 'get',
								url:'".Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ."',
								columns: [[
										{field:'companyid',title:'".GetCatalog('companyid') ."'},
										{field:'companyname',title:'".GetCatalog('companyname') ."'},
								]],
								fitColumns: true
						\">
				</select></td>
			</tr>			
			<tr>
				<td>".getCatalog('headernote')."</td>
				<td><input class='easyui-textbox' name='headernote' data-options='multiline:true,required:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'loadsuccess' => "

	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'employeeoverdetid',
			'urlsub'=>Yii::app()->createUrl('hr/employeeover/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('hr/employeeover/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('hr/employeeover/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('hr/employeeover/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('hr/employeeover/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'fullname',title:'".getCatalog('employee') ."',width:150},
				{field:'overtimestart',title:'".getCatalog('overtimestart') ."',width:200},
				{field:'overtimeend',title:'".getCatalog('overtimeend') ."',width:200},
				{field:'reason',title:'".getCatalog('reason') ."',width:200}
			",
			'columns'=>"
				{
					field:'employeeoverid',
					title:'".getCatalog('employeeoverid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'employeeoverdetid',
					title:'".getCatalog('employeeoverdetid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'employeeid',
					title:'".getCatalog('employee') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'employeeid',
							textField:'fullname',
							pagination:true,
							url:'".Yii::app()->createUrl('hr/employee/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'employeeid',title:'".getCatalog('employeeid')."',width:'50px'},
								{field:'fullname',title:'".getCatalog('fullname')."',width:'200px'}
							]]
						}	
					},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
						return row.fullname;
					}
				},
				{
					field:'overtimestart',
					title:'".getCatalog('overtimestart') ."',
					editor:'datetimebox',
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
				{
					field:'overtimeend',
					title:'".getCatalog('overtimeend') ."',
					editor:'datetimebox',
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
				{
					field:'reason',
					title:'".getCatalog('reason') ."',
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				}
			"
		)
	),	
));