<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'ojtid',
	'formtype'=>'masterdetail',
	'wfapp'=>'appojt',
	'url'=>Yii::app()->createUrl('hr/ojt/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('hr/ojt/getData'),
	'saveurl'=>Yii::app()->createUrl('hr/ojt/save'),
	'destroyurl'=>Yii::app()->createUrl('hr/ojt/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/ojt/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/ojt/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/ojt/downxls'),
	'columns'=>"
		{
			field:'ojtid',
			title:'".GetCatalog('ID')."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'plantid',
			title:'".GetCatalog('plant')."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return row.plantcode;
		}}, 		
		{
			field:'ojtno',
			title:'".GetCatalog('ojtno')."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'employeeid',
			title:'".GetCatalog('employee')."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'positionname',
			title:'".GetCatalog('positionname')."',
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'firstdate',
			title:'".GetCatalog('Tgl Mulai')."',
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'duedate',
			title:'".GetCatalog('Tgl Selesai')."',
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'headernote',
			title:'".GetCatalog('headernote')."',
			width:'350px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return row.statusname;
		}},",
		'addload'=>"
		$('#ojt-firstdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
		$('#ojt-duedate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
		",
	'searchfield'=> array ('ojtid','ojtno','structurename','accountowner'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('ojtno')."</td>
				<td><input class='easyui-textbox' id='ojt-ojtno' name='ojt-ojtno' data-options='readonly:true'></input></td>
				</tr>
			<td>".getCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='ojt-plantid' name='ojt-plantid' style='width:150px' data-options=\"
								panelWidth: '500px',
								idField: 'plantid',
								required: true,
								textField: 'plantcode',
								mode: 'remote',
								url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'auth'=>true)) ."',
								method: 'get',
								columns: [[
										{field:'plantid',title:'".getCatalog('plantid') ."',width:'50px'},
										{field:'plantcode',title:'".getCatalog('plantcode') ."',width:'120px'},
										{field:'description',title:'".getCatalog('description') ."',width:'200px'},
								]],
								fitColumns: true
						\">
				</select></td>
			<tr>
				<td>".GetCatalog('employee')."</td>
				<td>
					<select class='easyui-combogrid' id='ojt-employeeid' name='ojt-employeeid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'employeeid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('hr/employee/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
							{field:'employeeid',title:'".GetCatalog('employeeid') ."',width:'70px'},
							{field:'fullname',title:'".GetCatalog('fullname') ."',width:'250px'}
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('firstdate')."</td>
				<td><input class='easyui-datebox' type='text' id='ojt-firstdate' name='ojt-firstdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('duedate')."</td>
				<td><input class='easyui-datebox' type='text' id='ojt-duedate' name='ojt-duedate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
				<tr>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='ojt-headernote' name='ojt-headernote' data-options='multiline:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'loadsuccess' => "
		$('#ojt-firstdate').datebox('setValue',data.firstdate);
		$('#ojt-duedate').datebox('setValue',data.duedate);
		$('#ojt-ojtno').textbox('setValue',data.ojtno);
		$('#ojt-plantid').combogrid('setValue',data.plantid);
		$('#ojt-employeeid').combogrid('setValue',data.employeeid);
		$('#ojt-headernote').textbox('setValue',data.headernote);
	",
	'columndetails'=> array (
		array(
			'id'=>'ojtdet',
			'idfield'=>'ojtdetid',
			'urlsub'=>Yii::app()->createUrl('hr/ojt/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('hr/ojt/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('hr/ojt/saveojtdet',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('hr/ojt/saveojtdet',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('hr/ojt/purgeojtdet',array('grid'=>true)),
			'subs'=>"
				{field:'ojtdetid',title:'".GetCatalog('ID')."',width:'50px'},
				{field:'criteriaojtname',title:'".GetCatalog('criteriaojtname')."',width:'400px'},
				{field:'ojtval',title:'".GetCatalog('ojtval') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
					{field:'correctionval',title:'".GetCatalog('correctionval') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
					{field:'totalval',title:'".GetCatalog('totalval') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
					{field:'averageval',title:'".GetCatalog('averageval') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'devisiondesc',title:'".GetCatalog('devisiondesc')."',width:'300px'},
				{field:'departdesc',title:'".GetCatalog('departdesc')."',width:'300px'}
			",
			'columns'=>"
				{
					field:'ojtid',
					title:'".GetCatalog('ojtid')."',
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'ojtdetid',
					title:'".GetCatalog('ojtdetid')."',
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'criteriaojtid',
					title:'".GetCatalog('criteriaojt')."',
					width:'500px',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'criteriaojtid',
							textField:'criteriaojtname',
							url:'". Yii::app()->createUrl('hr/criteriaojt/index',array('grid'=>true,'combo'=>true))."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'criteriaojtid',title:'".GetCatalog('criteriaojtid')."',width:'80px'},
								{field:'criteriaojtname',title:'".GetCatalog('criteriaojtname')."',width:'450px'}
							]]
						}	
					},
					sortable: true,
					formatter: function(value,row,index){
						return row.criteriaojtname;
					}
				},
				{
					field:'ojtval',
					title:'".getCatalog('Nilai') ."',
					sortable: true,
					editor: {
						type: 'numberbox',
						options: {
							precision:1,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
						}
					},
					hidden:false,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'correctionval',
					title:'".getCatalog('Koreksi') ."',
					sortable: true,
					editor: {
						type: 'numberbox',
						options: {
							precision:1,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
						}
					},
					hidden:false,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'devisiondesc',
					title:'".GetCatalog('devisiondesc')."',
					width:'250px',
					editor:{
						type: 'textbox',
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'departdesc',
					title:'".GetCatalog('departdesc')."',
					width:'250px',
					editor:{
						type: 'textbox',
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				}
			"
		)		
	),	
));