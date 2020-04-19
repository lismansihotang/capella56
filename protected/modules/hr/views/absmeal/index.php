<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'absmealid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('hr/absmeal/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('hr/absmeal/getData'),
	'saveurl'=>Yii::app()->createUrl('hr/absmeal/save'),
	'destroyurl'=>Yii::app()->createUrl('hr/absmeal/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/absmeal/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/absmeal/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/absmeal/downxls'),
	'columns'=>"
		{
			field:'absmealid',
			title:'".GetCatalog('absmealid')."',
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
			field:'absmealdate',
			title:'".GetCatalog('absmealdate')."',
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'mealtypeid',
			title:'".GetCatalog('mealtype')."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.mealtypename;
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
	'searchfield'=> array ('absmealid','mealtypename','absmealdate'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('absmealdate')."</td>
				<td><input class='easyui-datebox' type='text' id='absmeal-absmealdate' name='absmeal-absmealdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<td>".getCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='absmeal-plantid' name='absmeal-plantid' style='width:150px' data-options=\"
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
				<td>".GetCatalog('mealtype')."</td>
				<td>
					<select class='easyui-combogrid' id='absmeal-mealtypeid' name='absmeal-mealtypeid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'mealtypeid',
						textField: 'mealtypename',
						mode:'remote',
						url: '".Yii::app()->createUrl('hr/mealtype/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
							{field:'mealtypeid',title:'".GetCatalog('mealtypeid') ."',width:'70px'},
							{field:'mealtypename',title:'".GetCatalog('mealtypename') ."',width:'250px'}
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
				<tr>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='absmeal-headernote' name='absmeal-headernote' data-options='multiline:true' style='width:300px;height:100px'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('recordstatus')."</td>
				<td><input id='absmeal-recordstatus' name='absmeal-recordstatus' type='checkbox'></input></td>
			</tr>
		</table>
	",
	'addload'=>"
		$('#absmeal-absmealdate').datebox({value: (new Date().toString('dd-MMM-yyyy'))});
	",
	'loadsuccess' => "
		$('#absmeal-absmealdate').datebox('setValue',data.absmealdate);
		$('#absmeal-plantid').combogrid('setValue',data.plantid);
		$('#absmeal-mealtypeid').combogrid('setValue',data.mealtypeid);
		$('#absmeal-headernote').textbox('setValue',data.headernote);
		if (data.recordstatus == 1) {
			$('#absmeal-recordstatus').prop('checked', true);
		} else {
			$('#absmeal-recordstatus').prop('checked', false);
		}
	",
	'columndetails'=> array (
		array(
			'id'=>'absmealdet',
			'idfield'=>'absmealdetid',
			'urlsub'=>Yii::app()->createUrl('hr/absmeal/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('hr/absmeal/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('hr/absmeal/saveabsmealdet',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('hr/absmeal/saveabsmealdet',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('hr/absmeal/purgeabsmealdet',array('grid'=>true)),
			'subs'=>"
				{field:'absmealdetid',title:'".GetCatalog('absmealdetid')."',width:'50px'},
				{field:'fullname',title:'".GetCatalog('fullname')."',width:'300px'},
				{field:'positionname',title:'".GetCatalog('positionname')."',width:'200px'},
				{field:'description',title:'".GetCatalog('description')."',width:'300px'}
			",
			'columns'=>"
				{
					field:'absmealid',
					title:'".GetCatalog('absmealid')."',
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'absmealdetid',
					title:'".GetCatalog('absmealdetid')."',
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'employeeid',
					title:'".getCatalog('Karyawan') ."',
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
								{field:'fullname',title:'".getCatalog('fullname')."',width:'200px'},
								{field:'oldnik',title:'".getCatalog('oldnik')."',width:'150px'},
							]]
						}	
					},
					width:'300px',
					sortable: true,
					formatter: function(value,row,index){
						return row.fullname;
					}
				},
				{
					field:'positionname',
					title:'".GetCatalog('Posisi')."',
					width:'150px',
					hidden:false,
					sortable: true,
					formatter: function(value,row,index){
						return value;
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