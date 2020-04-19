<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'transstockid',
	'formtype'=>'masterdetail',
	'isxls'=>1,
	'isnew'=>0,
	'ispost'=>1,
	'isreject'=>1,
	'ispurge'=>0,
	'isupload'=>0,
	'wfapp'=>'apptsin',
	'url'=>Yii::app()->createUrl('inventory/transstockin/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('inventory/transstockin/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('inventory/transstockin/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('inventory/transstockin/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('inventory/transstockin/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('inventory/transstockin/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('inventory/transstockin/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/transstock/downpdf'),
	'downxls'=>Yii::app()->createUrl('inventory/transstock/downxls'),
	'columns'=>"
		{
			field:'transstockid',
			title:'".GetCatalog('ID') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('apptsin')."
		}},
		{
			field:'companyname',
			title:'".GetCatalog('company') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'plantid',
			title:'".GetCatalog('plantcode') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.plantcode;
		}},
		{
			field:'isretur',
			title:'". GetCatalog('isretur') ."',
			align:'center',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
				} else {
				return '';
				}				
		}},
		{
			field:'fullname',
			title:'".GetCatalog('addressbook') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'transstockdate',
			title:'".GetCatalog('transstockdate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'transstockno',
			title:'".GetCatalog('transstockno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'formrequestid',
			title:'".GetCatalog('frno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.formrequestno;
		}},
		{
			field:'productplanno',
			title:'".GetCatalog('productplanno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.productplanno;
		}},
		{
			field:'sono',
			title:'".GetCatalog('sono') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.sono;
		}},
		{
			field:'slocfromid',
			title:'".GetCatalog('slocfrom') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.slocfromcode;
		}},
		{
			field:'sloctoid',
			title:'".GetCatalog('slocto') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.sloctocode;
		}},
		{
			field:'headernote',
			title:'".GetCatalog('headernote') ."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return row.recordstatusname;
		}},",
	'searchfield'=> array ('transstockid','plantcode','transstockdate','transstockno','slocfromcode','sloctocode','mesincode','headernote','recordstatus'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('transstockno')."</td>
				<td><input class='easyui-textbox' id='transstockin-transstockno' name='transstockin-transstockno' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('transstockdate')."</td>
				<td><input class='easyui-datebox' type='text' id='transstockin-transstockdate' name='transstockin-transstockdate' data-options='readonly:true,formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='transstockin-plantid' name='transstockin-plantid' style='width:150px' data-options=\"
								panelWidth: '500px',
								idField: 'plantid',
								required: true,
								textField: 'plantcode',
								readonly:true,
								mode: 'remote',
								url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'auth'=>true)) ."',
								method: 'get',
								columns: [[
										{field:'plantid',title:'".getCatalog('plantid') ."',width:'50px'},
										{field:'plantcode',title:'".getCatalog('plantcode') ."',width:'100px'},
										{field:'description',title:'".getCatalog('description') ."',width:'200px'},
								]],
								fitColumns: true
						\">
				</select></td>
				<td>".GetCatalog('isretur')."</td>
				<td><input class='easyui-checkbox' id='transstock-isretur' name='transstock-isretur' data-options=\"
				onChange: function(checked){
					if (checked == true) {
						$('#transstock-isretur-value').val(1);
					} else {
						$('#transstock-isretur-value').val(0);
					}
				}
				\"></input></td>
				</tr>
			<tr>
				<td>".GetCatalog('formrequest')."</td>
				<td>
					<select class='easyui-combogrid' id='transstockin-formrequestid' name='transstockin-formrequestid' style='width:250px' data-options=\"
						panelWidth: '700px',
						idField: 'formrequestid',
						textField: 'formrequestno',
						mode:'remote',
						url: '".Yii::app()->createUrl('inventory/formrequest/index',array('grid'=>true)) ."',
						method: 'get',
						readonly:true,
						columns: [[
								{field:'formrequestid',title:'".GetCatalog('formrequestid') ."',width:'50px'},
								{field:'formrequestno',title:'".GetCatalog('transstockno') ."',width:'120px'},
								{field:'sloccode',title:'".GetCatalog('slocfrom') ."',width:'200px'},
								{field:'mesincode',title:'".GetCatalog('mesin') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('productplanno')."</td>
				<td><input class='easyui-textbox' id='transstockin-productplanno' name='transstockin-productplanno' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('sono')."</td>
				<td><input class='easyui-textbox' id='transstockin-sono' name='transstockin-sono' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('customer')."</td>
				<td><input class='easyui-textbox' id='transstockin-fullname' name='transstockin-fullname' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('slocfrom')."</td>
				<td>
					<select class='easyui-combogrid' id='transstockin-slocfromid' name='transstockin-slocfromid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						readonly:true,
						idField: 'slocid',
						textField: 'sloccode',
						readonly:true,					
						mode:'remote',
						url: '".Yii::app()->createUrl('common/sloc/indexcombo',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'slocid',title:'".GetCatalog('slocid') ."',width:'50px'},
								{field:'sloccode',title:'".GetCatalog('sloccode') ."',width:'150px'},
								{field:'description',title:'".GetCatalog('description') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
				<td>".GetCatalog('slocto')."</td>
				<td>
					<select class='easyui-combogrid' id='transstockin-sloctoid' name='transstockin-sloctoid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'slocid',
						textField: 'sloccode',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/sloc/indexcombo',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						readonly:true,
						columns: [[
								{field:'slocid',title:'".GetCatalog('slocid') ."',width:'50px'},
								{field:'sloccode',title:'".GetCatalog('sloccode') ."',width:'150px'},
								{field:'description',title:'".GetCatalog('description') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='transstockin-headernote' name='transstockin-headernote' data-options='multiline:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addload'=>"
		$('#transstockin-transstockdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
	",
	'loadsuccess' => "
		$('#transstockin-transstockno').textbox('setValue',data.transstockno);
		$('#transstockin-transstockdate').datebox('setValue',data.transstockdate);
		$('#transstockin-plantid').combogrid('setValue',data.plantid);
		$('#transstockin-formrequestid').combogrid('setValue',data.formrequestid);
		$('#transstockin-productplanno').textbox('setValue',data.productplanno);
		$('#transstockin-sono').textbox('setValue',data.sono);
		$('#transstockin-fullname').textbox('setValue',data.fullname);
		$('#transstockin-slocfromid').combogrid('setValue',data.slocfromid);
		$('#transstockin-sloctoid').combogrid('setValue',data.sloctoid);
		$('#transstockin-headernote').textbox('setValue',data.headernote);
	",
	'columndetails'=> array (
		array(
			'id'=>'transstockdet',
			'idfield'=>'transstockdetid',
			'urlsub'=>Yii::app()->createUrl('inventory/transstockin/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/transstockin/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('inventory/transstockin/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('inventory/transstockin/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('inventory/transstockin/purgedetail',array('grid'=>true)),
			'ispurge'=>0,'isnew'=>0,'iswrite'=>1,
			'subs'=>"
				{field:'transstockdetid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qty',title:'".GetCatalog('qty') ."',formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:'".GetCatalog('uom2code') ."',width:'80px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom3code',title:'".GetCatalog('uom3code') ."',width:'80px'},
				{field:'qty4',title:'".GetCatalog('qty4') ."',formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom4code',title:'".GetCatalog('uom4code') ."',width:'80px'},
				{field:'raktujuan',title:'".GetCatalog('raktujuan') ."',width:'100px'},
				{field:'namamesin',title:'".GetCatalog('mesin') ."',width:'150px'},
				{field:'lotno',title:'".GetCatalog('lotno') ."',width:'180px'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'transstockid',
					title:'".GetCatalog('transstockid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'transstockdetid',
					title:'".GetCatalog('transstockdetid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'stdqty',
					title:'".getCatalog('stdqty') ."',
					sortable: true,
					editor: {
						type: 'numberbox',
						options: {
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
						}
					},
					hidden:true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'stdqty2',
					title:'".getCatalog('stdqty2') ."',
					sortable: true,
					editor: {
						type: 'numberbox',
						options: {
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
						}
					},
					hidden:true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'stdqty3',
					title:'".getCatalog('stdqty3') ."',
					sortable: true,
					editor: {
						type: 'numberbox',
						options: {
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
						}
					},
					hidden:true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'stdqty4',
					title:'".getCatalog('stdqty4') ."',
					sortable: true,
					editor: {
						type: 'numberbox',
						options: {
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
						}
					},
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
							required:true,
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
					field:'productid',
					title:'".GetCatalog('productname') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'productid',
							textField:'productname',
							readonly: true,
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							onChange: function(newValue, oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-transstockin-transstockdet').datagrid('getEditor', {index: index, field:'productid'});
								var stdqty = $('#dg-transstockin-transstockdet').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-transstockin-transstockdet').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-transstockin-transstockdet').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-transstockin-transstockdet').datagrid('getEditor', {index: index, field:'stdqty4'});
								var uomid = $('#dg-transstockin-transstockdet').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-transstockin-transstockdet').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-transstockin-transstockdet').datagrid('getEditor', {index: index, field:'uom3id'});
								var uom4id = $('#dg-transstockin-transstockdet').datagrid('getEditor', {index: index, field:'uom4id'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/index',array('grid'=>true,'getdata'=>true)) ."',
									'data':{'productid':$(productid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(uomid.target).combogrid('setValue',data.uom1);
										$(uom2id.target).combogrid('setValue',data.uom2);
										$(uom3id.target).combogrid('setValue',data.uom3);
										$(uom4id.target).combogrid('setValue',data.uom4);
										$(stdqty.target).numberbox('setValue',data.qty);
										$(stdqty2.target).numberbox('setValue',data.qty2);
										$(stdqty3.target).numberbox('setValue',data.qty3);
										$(stdqty4.target).numberbox('setValue',data.qty4);
									} ,
									'cache':false});
							},
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'productid',title:'".GetCatalog('productid')."',width:'50px'},
								{field:'materialtypecode',title:'".GetCatalog('materialtypecode')."',width:'150px'},
								{field:'productname',title:'".GetCatalog('productname')."',width:'400px'},
							]]
						}	
					},
					width:'350px',
					sortable: true,
					formatter: function(value,row,index){
										return row.productname;
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
								var stdqty = $('#dg-transstockin-transstockdet').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-transstockin-transstockdet').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-transstockin-transstockdet').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-transstockin-transstockdet').datagrid('getEditor', {index: index, field:'stdqty4'});								
								var qty2 = $('#dg-transstockin-transstockdet').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-transstockin-transstockdet').datagrid('getEditor', {index: index, field:'qty3'});								
								var qty4 = $('#dg-transstockin-transstockdet').datagrid('getEditor', {index: index, field:'qty4'});						
								$(qty2.target).numberbox('setValue',$(stdqty2.target).numberbox('getValue') * (newValue / $(stdqty.target).numberbox('getValue')));
								$(qty3.target).numberbox('setValue',$(stdqty3.target).numberbox('getValue') * (newValue / $(stdqty.target).numberbox('getValue')));
								$(qty4.target).numberbox('setValue',$(stdqty4.target).numberbox('getValue') * (newValue / $(stdqty.target).numberbox('getValue')));
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
					field:'storagebinfromid',
					title:'".getCatalog('storagebinfrom') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'550px',
							mode : 'remote',
							method:'get',
							idField:'storagebinid',
							readonly:true,
							textField:'description',
							url:'".Yii::app()->createUrl('common/storagebin/indexcombosloc',array('grid'=>true)) ."',
							onBeforeLoad: function(param){
								param.slocid = $('#transstockin-slocfromid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'storagebinid',title:'".getCatalog('storagebinid')."',width:'80px'},
								{field:'description',title:'".getCatalog('description')."',width:'150px'},
							]]
						}	
					},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
						return row.rakasal;
					}
				},
				{
					field:'storagebintoid',
					title:'".getCatalog('storagebinto') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'550px',
							mode : 'remote',
							method:'get',
							idField:'storagebinid',
							textField:'description',
							url:'".Yii::app()->createUrl('common/storagebin/indexcombosloc',array('grid'=>true)) ."',
							onBeforeLoad: function(param){
								param.slocid = $('#transstockin-sloctoid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'storagebinid',title:'".getCatalog('storagebinid')."',width:'80px'},
								{field:'description',title:'".getCatalog('description')."',width:'250px'},
							]]
						}	
					},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
						return row.raktujuan;
					}
				},
				{
					field:'mesinid',
					title:'".getCatalog('mesin') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'550px',
							mode : 'remote',
							method:'get',
							idField:'mesinid',
							textField:'namamesin',
							url:'".Yii::app()->createUrl('common/mesin/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'mesinid',title:'".getCatalog('mesinid')."',width:'80px'},
								{field:'namamesin',title:'".getCatalog('namamesin')."',width:'150px'},
							]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return row.namamesin;
					}
				},
				{
					field:'lotno',
					title:'".GetCatalog('lotno')."',
					editor: {
						type: 'textbox',
						options:{
							readonly:true
						}
					},
					sortable: true,
					width:'200px',
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'itemnote',
					title:'".GetCatalog('itemnote')."',
					editor: {
						type: 'textbox',
						options: {
							multiline:true,
						}
					},
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