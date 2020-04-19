<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'gireturid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appgiretur',
	'url'=>Yii::app()->createUrl('inventory/giretur/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('inventory/giretur/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('inventory/giretur/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('inventory/giretur/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('inventory/giretur/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('inventory/giretur/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('inventory/giretur/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/giretur/downpdf'),
	'columns'=>"
		{
			field:'gireturid',
			title:'".GetCatalog('gireturid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appgiretur')."
		}},
		{
			field:'plantid',
			title:'".GetCatalog('plantcode') ."',
			sortable: true,
			width:'125px',
			formatter: function(value,row,index){
			return row.plantcode;
		}},
		{
			field:'gireturdate',
			title:'".GetCatalog('gireturdate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'gireturno',
			title:'".GetCatalog('gireturno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'giheaderid',
			title:'".GetCatalog('gino') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
			return row.gino;
		}},
		{
			field:'fullname',
			title:'".GetCatalog('customer') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'headernote',
			title:'".GetCatalog('headernote') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatusgiretur',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},",
	'rowstyler'=>"
		if (row.debit != row.credit){
			return 'background-color:blue;color:#fff;';
		}
	",
	'searchfield'=> array ('gireturid','plantcode','gireturdate','gino','gireturno','customer','headernote','recordstatus'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('gireturno')."</td>
				<td><input class='easyui-textbox' id='giretur-gireturno' name='giretur-gireturno' data-options='disabled:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('gireturdate')."</td>
				<td><input class='easyui-datebox' type='text' id='giretur-gireturdate' name='giretur-gireturdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('plantcode')."</td>
				<td>
					<select class='easyui-combogrid' id='giretur-plantid' name='giretur-plantid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'plantid',
						textField: 'plantcode',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'auth'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'plantid',title:'".GetCatalog('plantid') ."',width:'50px'},
								{field:'plantcode',title:'".GetCatalog('plantcode') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('giheader')."</td>
				<td><select class='easyui-combogrid' name='giretur-giheaderid' id='giretur-giheaderid' style='width:250px' data-options=\"
								panelWidth: '500px',
								required: true,
								idField: 'giheaderid',
								textField: 'gino',
								mode:'remote',
								url: '".Yii::app()->createUrl('inventory/giheader/index',array('grid'=>true,'gir'=>true))."',
								method: 'get',
								onBeforeLoad: function(param){
									param.plantid = $('#giretur-plantid').combogrid('getValue');
								},
								onShowPanel: function() {
							$('#giretur-giheaderid').combogrid('grid').datagrid('reload');
						},		
								onHidePanel: function(){
									jQuery.ajax(
										{'url':'". Yii::app()->createUrl('inventory/giretur/generatecustomer') ."',
											'data':{'id':$('#giretur-giheaderid').combogrid('getValue')},
											'type':'post',
											'dataType':'json',
											'success':function(data)
											{
												$('#giretur-fullname').textbox('setValue',data.fullname);
											} ,
											'cache':false},
									);
									jQuery.ajax({'url':'". Yii::app()->createUrl('inventory/giretur/generatedetail') ."',
											'data':{'id':$('#giretur-giheaderid').combogrid('getValue'),
															'hid':$('#giretur-gireturid').val()
											},
											'type':'post',
											'dataType':'json',
											'success':function(data)
											{
												$('#dg-giretur-gireturdetail').edatagrid('reload');
											},
											'cache':false},
									);
								},
								columns: [[
									{field:'giheaderid',title:'".GetCatalog('giheaderid') ."',width:'50px'},
									{field:'gino',title:'".GetCatalog('gino') ."',width:'250px'},
								]],
								fitColumns: true\">
				</select></td>
			</tr>
			<tr>
				<td>".GetCatalog('customer')."</td>
				<td><input class='easyui-textbox' id='giretur-fullname' name='giretur-fullname' data-options='disabled:true,
								required:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='giretur-headernote' name='giretur-headernote' data-options='multiline:true,required:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addload'=>"
		$('#giretur-gireturdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
	",
	'loadsuccess'=>"
		$('#giretur-gireturdate').datebox('setValue',data.gireturdate);
		$('#giretur-gireturno').textbox('setValue',data.gireturno);
		$('#giretur-plantid').combogrid('setValue',data.plantid);
		$('#giretur-poheaderid').combogrid('setValue',data.poheaderid);
		$('#giretur-fullname').textbox('setValue',data.fullname);
		$('#giretur-headernote').textbox('setValue',data.headernote);
		$('#giretur-grheaderid').val('');	
	",
	'columndetails'=> array (
		array(
			'id'=>'gireturdetail',
			'idfield'=>'gireturdetailid',
			'isnew'=>0,
			'urlsub'=>Yii::app()->createUrl('inventory/giretur/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/giretur/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('inventory/giretur/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('inventory/giretur/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('inventory/giretur/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty2',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty3',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom3code',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty4',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom4code',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'sloccode',title:'".GetCatalog('sloccode') ."',width:'60px'},
				{field:'description',title:'".GetCatalog('storagebin') ."',width:'100px'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'100px'},
			",
			'columns'=>"
				{
					field:'gireturid',
					title:'".GetCatalog('gireturid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'gireturdetailid',
					title:'".GetCatalog('gireturdetailid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'gidetailid',
					title:'".GetCatalog('gidetailid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'materialtypecode',
					title:'".GetCatalog('materialtypecode') ."',
					editor: {
						type: 'textbox',
						options:{
							readonly:true,
						}
					},
					sortable: true,
					width:'150px',
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'productname',
					title:'".GetCatalog('productname') ."',
					editor: {
						type: 'textbox',
						options:{
							readonly:true,
						}
					},
					sortable: true,
					width:'250px',
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'qty',
					title:'".GetCatalog('qty') ."',
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							required:true,
							onChange: function(newValue,oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var qty2 = $('#dg-giretur-gireturdetail').datagrid('getEditor', {index: index, field:'qty2'});
								var qty3 = $('#dg-giretur-gireturdetail').datagrid('getEditor', {index: index, field:'qty3'});
								var qty4 = $('#dg-giretur-gireturdetail').datagrid('getEditor', {index: index, field:'qty4'});								
								var qty2 = $('#dg-giretur-gireturdetail').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-giretur-gireturdetail').datagrid('getEditor', {index: index, field:'qty3'});								
								var qty4 = $('#dg-giretur-gireturdetail').datagrid('getEditor', {index: index, field:'qty4'});						
								$(qty2.target).numberbox('setValue',$(qty2.target).numberbox('getValue') * (newValue/oldValue));
								$(qty3.target).numberbox('setValue',$(qty3.target).numberbox('getValue') * (newValue/oldValue));
								$(qty4.target).numberbox('setValue',$(qty4.target).numberbox('getValue') * (newValue/oldValue));
							}
						}
					},
					sortable: true,
					formatter: function(value,row,index){
						return formatnumber('',value);
					}
				},
				{
					field:'uomid',
					title:'".GetCatalog('uomcode') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							readonly:true,
							hasDownArrow:false,
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
								{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'150px'},
							]]
						}		
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uomcode;
					}
				},
				{
					field:'qty2',
					title:'".GetCatalog('qty2') ."',
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							required:true,
						}
					},
					sortable: true,
					formatter: function(value,row,index){
											return formatnumber('',value);
					}
				},
				{
					field:'uom2id',
					title:'".GetCatalog('uom2code') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							readonly:true,
							hasDownArrow:false,
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
								{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'150px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uom2code;
					}
				},
				{
					field:'qty3',
					title:'".GetCatalog('qty3') ."',
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							
						}
					},
					sortable: true,
					formatter: function(value,row,index){
											return formatnumber('',value);
					}
				},
				{
					field:'uom3id',
					title:'".GetCatalog('uom3code') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							readonly:true,
							hasDownArrow:false,
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
								{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'150px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uom3code;
					}
				},
				{
					field:'qty4',
					title:'".GetCatalog('qty4') ."',
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
						}
					},
					sortable: true,
					formatter: function(value,row,index){
											return formatnumber('',value);
					}
				},
				{
					field:'uom4id',
					title:'".GetCatalog('uom4code') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							readonly:true,
							hasDownArrow:false,
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
								{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'150px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uom4code;
					}
				},
				{
					field:'slocid',
					title:'".GetCatalog('sloc') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'slocid',
							textField:'sloccode',
							url:'".Yii::app()->createUrl('common/sloc/indextrxplantsloc',array('grid'=>true)) ."',
							onBeforeLoad: function(param){
								param.plantid = $('#giretur-plantid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'slocid',title:'".GetCatalog('slocid')."',width:'50px'},
								{field:'sloccode',title:'".GetCatalog('sloccode')."',width:'150px'},
								{field:'description',title:'".GetCatalog('description')."',width:'250px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.sloccode;
					}
				},
				{
					field:'storagebinid',
					title:'".GetCatalog('storagebin') ."',
					editor:{
							type:'combogrid',
							options:{
									panelWidth:'500px',
									mode : 'remote',
									method:'get',
									idField:'storagebinid',
									textField:'description',
									url:'".Yii::app()->createUrl('common/storagebin/index',array('grid'=>true)) ."',
									fitColumns:true,
									pagination:true,
									required:true,
									queryParams:{
										combo:true
									},
									loadMsg: '".GetCatalog('pleasewait')."',
									columns:[[
										{field:'storagebinid',title:'".GetCatalog('storagebinid')."',width:'50px'},
										{field:'description',title:'".GetCatalog('description')."',width:'150px'},
									]]
							}	
						},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.description;
					}
				},
				{
					field:'itemnote',
					title:'".GetCatalog('itemnote')."',
					editor: 'textbox',
					sortable: true,
					width:'100px',
					formatter: function(value,row,index){
										return value;
					}
				},
			"
		),
	),	
));