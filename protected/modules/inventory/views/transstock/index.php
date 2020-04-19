<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'transstockid',
	'formtype'=>'masterdetail',
	'isxls'=>1,
	'ispost'=>1,
	'isreject'=>1,
	'ispurge'=>0,
	'isupload'=>0,
	'wfapp'=>'appts',
	'url'=>Yii::app()->createUrl('inventory/transstock/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('inventory/transstock/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('inventory/transstock/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('inventory/transstock/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('inventory/transstock/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('inventory/transstock/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('inventory/transstock/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/transstock/downpdf'),
	'downxls'=>Yii::app()->createUrl('inventory/transstock/downxls'),
	'columns'=>"
		{
			field:'transstockid',
			title:'".GetCatalog('ID') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appts')."
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
			title:'".GetCatalog('customer') ."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'transstockdate',
			title:'".GetCatalog('transstockdate') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'transstockno',
			title:'".GetCatalog('transstockno') ."',
			sortable: true,
			width:'180px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'formrequestid',
			title:'".GetCatalog('formrequestno') ."',
			sortable: true,
			width:'180px',
			formatter: function(value,row,index){
				return row.formrequestno;
		}},
		{
			field:'productplanno',
			title:'".GetCatalog('productplanno') ."',
			sortable: true,
			width:'180px',
			formatter: function(value,row,index){
				return row.productplanno;
		}},
		{
			field:'sono',
			title:'".GetCatalog('sono') ."',
			sortable: true,
			width:'180px',
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
	'searchfield'=> array ('transstockid','plantcode','transstockdate','transstockno','formrequestno','productplanno','sono','slocfromcode','sloctocode','customername','headernote','recordstatus'),
	'headerform'=> "
	<input type='hidden' id='transstock-isretur-value' name='transstock-isretur-value' />
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('transstockno')."</td>
				<td><input class='easyui-textbox' id='transstock-transstockno' name='transstock-transstockno' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('transstockdate')."</td>
				<td><input class='easyui-datebox' type='text' id='transstock-transstockdate' name='transstock-transstockdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='transstock-plantid' name='transstock-plantid' style='width:150px' data-options=\"
								panelWidth: '500px',
								idField: 'plantid',
								required: true,
								textField: 'plantcode',
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
					<select class='easyui-combogrid' id='transstock-formrequestid' name='transstock-formrequestid' style='width:250px' data-options=\"
						panelWidth: '700px',
						required: true,
						idField: 'formrequestid',
						textField: 'formrequestno',
						mode:'remote',
						url: '".Yii::app()->createUrl('inventory/formrequest/index',array('grid'=>true,'frts'=>true)) ."',
						method: 'get',
						onShowPanel: function() {
							$('#transstock-formrequestid').combogrid('grid').datagrid('reload');
						},							
						onBeforeLoad: function(param) {
							param.plantid = $('#transstock-plantid').combogrid('getValue');
							param.isretur = $('#transstock-isretur-value').val();
						},
						onHidePanel: function(){
							jQuery.ajax({'url':'".Yii::app()->createUrl('inventory/formrequest/index',array('grid'=>true,'getdataTS'=>true)) ."',
								'data':{
									'formrequestid':$('#transstock-formrequestid').combogrid('getValue'),
									'sloctoid':$('#transstock-sloctoid').combogrid('getValue'),
								},
								'type':'post',
								'dataType':'json',
								'success':function(data)
								{
									$('#transstock-slocfromid').combogrid('setValue',data.slocfromid);
									$('#transstock-sloctoid').combogrid('setValue',data.sloctoid);
									$('#transstock-sono').textbox('setValue',data.sono);
									$('#transstock-productplanno').textbox('setValue',data.productplanno);
									$('#transstock-fullname').textbox('setValue',data.fullname);
									jQuery.ajax({'url':'".Yii::app()->createUrl('inventory/transstock/generatedetail') ."',
										'data':{
											'id':$('#transstock-formrequestid').combogrid('getValue'),
											'hid':$('#transstock-transstockid').val(),
											'jid':$('#transstock-isretur-value').val(),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#dg-transstock-transstockdet').datagrid('reload');
										},
										'cache':false});
								},
								'cache':false});
						},
						columns: [[
								{field:'formrequestid',title:'".GetCatalog('formrequestid') ."',width:'50px'},
								{field:'formrequestno',title:'".GetCatalog('formrequestno') ."',width:'200px'},
								{field:'sloccode',title:'".GetCatalog('slocfrom') ."',width:'200px'},
								{field:'mesincode',title:'".GetCatalog('mesin') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('productplanno')."</td>
				<td><input class='easyui-textbox' id='transstock-productplanno' name='transstock-productplanno' data-options='readonly:true' style='width:200px'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('sono')."</td>
				<td><input class='easyui-textbox' id='transstock-sono' name='transstock-sono' data-options='readonly:true' style='width:200px'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('customer')."</td>
				<td><input class='easyui-textbox' id='transstock-fullname' name='transstock-fullname' data-options='readonly:true' style='width:250px'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('slocfrom')."</td>
				<td>
					<select class='easyui-combogrid' id='transstock-slocfromid' name='transstock-slocfromid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						readonly:true,
						idField: 'slocid',
						textField: 'sloccode',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/sloc/indextrxplant',array('grid'=>true)) ."',
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
					<select class='easyui-combogrid' id='transstock-sloctoid' name='transstock-sloctoid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'slocid',
						textField: 'sloccode',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/sloc/indexslocfr',array('grid'=>true)) ."',
						method: 'get',
						onShowPanel: function() {
							$('#transstock-sloctoid').combogrid('grid').datagrid('reload');
						},
						onBeforeLoad: function(param) {
							param.formrequestid = $('#transstock-formrequestid').combogrid('getValue');
						},
						onHidePanel: function(){
							jQuery.ajax({'url':'".Yii::app()->createUrl('inventory/formrequest/index',array('grid'=>true,'getdataTS'=>true)) ."',
								'data':{
									'formrequestid':$('#transstock-formrequestid').combogrid('getValue'),
									'sloctoid':$('#transstock-sloctoid').combogrid('getValue'),
								},
								'type':'post',
								'dataType':'json',
								'success':function(data)
								{
									jQuery.ajax({'url':'".Yii::app()->createUrl('inventory/transstock/generatedetail') ."',
										'data':{
											'id':$('#transstock-formrequestid').combogrid('getValue'),
											'hid':$('#transstock-transstockid').val(),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#dg-transstock-transstockdet').datagrid('reload');
										},
										'cache':false});
								},
								'cache':false});
						},
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
				<td><input class='easyui-textbox' id='transstock-headernote' name='transstock-headernote' data-options='multiline:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addload'=>"
		$('#transstock-transstockdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
	",
	'loadsuccess' => "
		$('#transstock-transstockno').textbox('setValue',data.transstockno);
		$('#transstock-transstockdate').datebox('setValue',data.transstockdate);
		$('#transstock-plantid').combogrid('setValue',data.plantid);
		$('#transstock-formrequestid').combogrid('setValue',data.formrequestid);
		$('#transstock-productplanno').textbox('setValue',data.productplanno);
		$('#transstock-sono').textbox('setValue',data.sono);
		$('#transstock-fullname').textbox('setValue',data.fullname);
		$('#transstock-slocfromid').combogrid('setValue',data.slocfromid);
		$('#transstock-sloctoid').combogrid('setValue',data.sloctoid);
		$('#transstock-headernote').textbox('setValue',data.headernote);
	",
	'columndetails'=> array (
		array(
			'id'=>'transstockdet',
			'idfield'=>'transstockdetid',
			'urlsub'=>Yii::app()->createUrl('inventory/transstock/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/transstock/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('inventory/transstock/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('inventory/transstock/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('inventory/transstock/purgedetail',array('grid'=>true)),
			'ispurge'=>1,'isnew'=>0,
			'subs'=>"
				{field:'transstockdetid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qtystock',title:'".GetCatalog('qtystock') ."',formatter: function(value,row,index){
						if (row.stockcount == '1') {
						return '<div style=\"background-color:cyan\">'+formatnumber('',value)+'</div>';
						} else {
							return formatnumber('',value);
						}
					},width:'100px'},
					{field:'qtyreq',title:'".GetCatalog('qtyreq') ."',formatter: function(value,row,index){
						if (row.stockcount == '1') {
						return '<div style=\"background-color:cyan\">'+formatnumber('',value)+'</div>';
						} else {
							return formatnumber('',value);
						}
					},width:'100px'},
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
				{field:'rakasal',title:'".GetCatalog('rakasal') ."',width:'100px'},
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
					field:'productcode',
					title:'".GetCatalog('productcode') ."',
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
										return row.productcode;
					}
				},
				{
					field:'productid',
					title:'".GetCatalog('productname') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'800px',
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
								var productid = $('#dg-transstock-transstockdet').datagrid('getEditor', {index: index, field:'productid'});
								var stdqty = $('#dg-transstock-transstockdet').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-transstock-transstockdet').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-transstock-transstockdet').datagrid('getEditor', {index: index, field:'stdqty3'});
								var uomid = $('#dg-transstock-transstockdet').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-transstock-transstockdet').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-transstock-transstockdet').datagrid('getEditor', {index: index, field:'uom3id'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/index',array('grid'=>true,'getdata'=>true)) ."',
									'data':{'productid':$(productid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(uomid.target).combogrid('setValue',data.uom1);
										$(uom2id.target).combogrid('setValue',data.uom2);
										$(uom3id.target).combogrid('setValue',data.uom3);
										$(stdqty.target).numberbox('setValue',data.qty1);
										$(stdqty2.target).numberbox('setValue',data.qty2);
										$(stdqty3.target).numberbox('setValue',data.qty3);
									} ,
									'cache':false});
							},
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'productid',title:'".GetCatalog('productid')."',width:'50px'},
								{field:'materialtypecode',title:'".GetCatalog('materialtypecode')."',width:'150px'},
								{field:'productcode',title:'".GetCatalog('productcode')."',width:'150px'},
								{field:'productname',title:'".GetCatalog('productname')."',width:'400px'},
							]]
						}	
					},
					width:'450px',
					sortable: true,
					formatter: function(value,row,index){
										return row.productname;
					}
				},
				{
					field:'qtystock',
					title:'".GetCatalog('qtystock') ."',
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return formatnumber('',value);
					}
				},
				{
					field:'qtyreq',
					title:'".GetCatalog('qtyreq') ."',
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return formatnumber('',value);
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
								var stdqty = $('#dg-transstock-transstockdet').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-transstock-transstockdet').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-transstock-transstockdet').datagrid('getEditor', {index: index, field:'stdqty3'});
								var qty2 = $('#dg-transstock-transstockdet').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-transstock-transstockdet').datagrid('getEditor', {index: index, field:'qty3'});								
								$(qty2.target).numberbox('setValue',$(stdqty2.target).numberbox('getValue') * (newValue / $(stdqty.target).numberbox('getValue')));
								$(qty3.target).numberbox('setValue',$(stdqty3.target).numberbox('getValue') * (newValue / $(stdqty.target).numberbox('getValue')));
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
					field:'storagebinfromid',
					title:'".getCatalog('storagebinfrom') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'550px',
							mode : 'remote',
							method:'get',
							idField:'storagebinid',
							textField:'description',
							url:'".Yii::app()->createUrl('common/storagebin/indexcombosloc',array('grid'=>true)) ."',
							onBeforeLoad: function(param) {
								param.slocid = $('#transstock-slocfromid').combogrid('getValue');
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