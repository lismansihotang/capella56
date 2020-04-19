<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'emergencyid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('hr/emergency/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('hr/emergency/getData'),
	'saveurl'=>Yii::app()->createUrl('hr/emergency/save'),
	'destroyurl'=>Yii::app()->createUrl('hr/emergency/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/emergency/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/emergency/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/emergency/downxls'),
	'columns'=>"
		{
			field:'emergencyid',
			title:'".GetCatalog('emergencyid')."',
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
			field:'emergencyno',
			title:'".GetCatalog('emergencyno')."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'emergencydate',
			title:'".GetCatalog('emergencydate')."',
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'orgstructureid',
			title:'".GetCatalog('orgstructure')."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.structurename;
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
			title:'".GetCatalog('recordstatus')."',
			align:'center',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}}",
	'searchfield'=> array ('emergencyid','emergencyno','structurename','accountowner'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('emergencydate')."</td>
				<td><input class='easyui-datebox' type='text' id='emergency-emergencydate' name='emergency-emergencydate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<td>".getCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='emergency-plantid' name='emergency-plantid' style='width:150px' data-options=\"
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
				<td>".GetCatalog('emergencyno')."</td>
				<td><input class='easyui-textbox' id='emergency-emergencyno' name='emergency-emergencyno' ></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('orgstructure')."</td>
				<td>
					<select class='easyui-combogrid' id='emergency-orgstructureid' name='emergency-orgstructureid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'orgstructureid',
						textField: 'structurename',
						mode:'remote',
						url: '".Yii::app()->createUrl('hr/orgstructure/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
							{field:'orgstructureid',title:'".GetCatalog('orgstructureid') ."',width:'70px'},
							{field:'structurename',title:'".GetCatalog('structurename') ."',width:'250px'}
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
				<tr>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='emergency-headernote' name='emergency-headernote' data-options='multiline:true' style='width:300px;height:100px'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('recordstatus')."</td>
				<td><input id='emergency-recordstatus' name='emergency-recordstatus' type='checkbox'></input></td>
			</tr>
		</table>
	",
	'addload'=>"
		$('#emergency-emergencydate').datebox({value: (new Date().toString('dd-MMM-yyyy'))});
	",
	'loadsuccess' => "
		$('#emergency-emergencydate').datebox('setValue',data.emergencydate);
		$('#emergency-emergencyno').textbox('setValue',data.emergencyno);
		$('#emergency-plantid').combogrid('setValue',data.plantid);
		$('#emergency-orgstructureid').combogrid('setValue',data.orgstructureid);
		$('#emergency-headernote').textbox('setValue',data.headernote);
		if (data.recordstatus == 1) {
			$('#emergency-recordstatus').prop('checked', true);
		} else {
			$('#emergency-recordstatus').prop('checked', false);
		}
	",
	'columndetails'=> array (
		array(
			'id'=>'emergencydet',
			'idfield'=>'emergencydetid',
			'urlsub'=>Yii::app()->createUrl('hr/emergency/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('hr/emergency/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('hr/emergency/saveemergencydet',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('hr/emergency/saveemergencydet',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('hr/emergency/purgeemergencydet',array('grid'=>true)),
			'subs'=>"
				{field:'emergencydetid',title:'".GetCatalog('emergencydetid')."',width:'50px'},
				{field:'emergencyname',title:'".GetCatalog('emergencyname')."',width:'200px'},
				{field:'reporttype',title:'".GetCatalog('reporttype')."',width:'200px'},
				{field:'penjelasan',title:'".GetCatalog('penjelasan')."',width:'300px'},
				{field:'evaluasi',title:'".GetCatalog('evaluasi')."',width:'300px'},
				{field:'perbaikan',title:'".GetCatalog('perbaikan')."',width:'300px'},
				{field:'description',title:'".GetCatalog('description')."',width:'300px'}
			",
			'columns'=>"
				{
					field:'emergencyid',
					title:'".GetCatalog('emergencyid')."',
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'emergencydetid',
					title:'".GetCatalog('emergencydetid')."',
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'emergencytypeid',
					title:'".GetCatalog('emergencytype')."',
					width:'200px',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'emergencytypeid',
							textField:'emergencyname',
							url:'". Yii::app()->createUrl('hr/emergencytype/index',array('grid'=>true,'combo'=>true))."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'emergencytypeid',title:'".GetCatalog('emergencytypeid')."',width:'80px'},
								{field:'reporttype',title:'".GetCatalog('reporttype')."',width:'200px'},
								{field:'emergencyname',title:'".GetCatalog('emergencyname')."',width:'250px'}
							]]
						}	
					},
					sortable: true,
					formatter: function(value,row,index){
						return row.emergencyname;
					}
				},
				{
					field:'penjelasan',
					title:'".GetCatalog('penjelasan')."',
					width:'300px',
					editor:{
						type: 'textbox',
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'evaluasi',
					title:'".GetCatalog('evaluasi')."',
					width:'300px',
					editor:{
						type: 'textbox',
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'perbaikan',
					title:'".GetCatalog('perbaikan')."',
					width:'300px',
					editor:{
						type: 'textbox',
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'employeeid',
					title:'".getCatalog('Pelapor') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'550px',
							mode : 'remote',
							method:'get',
							idField:'employeeid',
							textField:'fullname',
							url:'".Yii::app()->createUrl('hr/employee/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'employeeid',title:'".getCatalog('employeeid')."',width:'80px'},
								{field:'fullname',title:'".getCatalog('fullname')."',width:'80px'},
								{field:'oldnik',title:'".getCatalog('oldnik')."',width:'150px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return row.fullname;
					}
				},
				{
					field:'description',
					title:'".GetCatalog('description')."',
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