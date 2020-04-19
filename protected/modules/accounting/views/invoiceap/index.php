<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'invoiceapid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appinvap',
	'url'=>Yii::app()->createUrl('accounting/invoiceap/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('accounting/invoiceap/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/invoiceap/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/invoiceap/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/invoiceap/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('accounting/invoiceap/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('accounting/invoiceap/reject',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('accounting/invoiceap/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/invoiceap/downpdf'),
	'columns'=>"
		{
			field:'invoiceapid',
			title:'".GetCatalog('invoiceapid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appinvap')."
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
			field:'invoiceapdate',
			title:'".GetCatalog('invoiceapdate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'invoiceapno',
			title:'".GetCatalog('invoiceapno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'pono',
			title:'".GetCatalog('pono') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return value;
		}},
		{
			field:'fullname',
			title:'".GetCatalog('supplier') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return value;
		}},
		{
			field:'invoiceaptaxno',
			title:'".GetCatalog('invoiceaptaxno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return value;
		}},
		{
			field:'nobuktipotong',
			title:'".GetCatalog('nobuktipotong') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return value;
		}},
		{
			field:'receiptdate',
			title:'".GetCatalog('receiptdate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'taxno',
			title:'".GetCatalog('taxno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return value;
		}},
		{
			field:'paymentmethodid',
			title:'".GetCatalog('paymentmethod') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return row.paycode;
		}},
		{
			field:'duedate',
			title:'".GetCatalog('duedate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'headernote',
			title:'".GetCatalog('headernote') ."',
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatusname',
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
	'searchfield'=> array ('invoiceapid','plantcode','supplier','invoiceapdate','pono','nobuktipotong','invoiceapno','sjsupplier','invoiceaptaxno','headernote','recordstatus'),
	'headerform'=> "
		<input type='hidden' name='invoiceap-slocid' id='invoiceap-slocid' />
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('invoiceapdate')."</td>
				<td><input class='easyui-datebox' type='text' id='invoiceap-invoiceapdate' name='invoiceap-invoiceapdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('invoiceapno')."</td>
				<td><input class='easyui-textbox' id='invoiceap-invoiceapno' name='invoiceap-invoiceapno' data-options='required:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('plantcode')."</td>
				<td>
					<select class='easyui-combogrid' id='invoiceap-plantid' name='invoiceap-plantid' style='width:250px' data-options=\"
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
				<td>".GetCatalog('supplier')."</td>
				<td>
					<select class='easyui-combogrid' id='invoiceap-addressbookid' name='invoiceap-addressbookid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/supplier/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'addressbookid',title:'".GetCatalog('addressbookid') ."',width:'50px'},
								{field:'fullname',title:'".GetCatalog('customer') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('pono')."</td>
				<td>
					<select class='easyui-combogrid' id='invoiceap-poheaderid' name='invoiceap-poheaderid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'poheaderid',
						textField: 'pono',
						mode:'remote',
						url: '".Yii::app()->createUrl('purchasing/poheader/index',array('grid'=>true,'invappo'=>true)) ."',
						method: 'get',
						onBeforeLoad: function(param){
							param.plantid = $('#invoiceap-plantid').combogrid('getValue');
							param.addressbookid = $('#invoiceap-addressbookid').combogrid('getValue');
						},
						onHidePanel: function(){
							jQuery.ajax({'url':'". Yii::app()->createUrl('accounting/invoiceap/generatedetail') ."',
									'data':{'id':$('#invoiceap-poheaderid').combogrid('getValue'),
													'hid':$('#invoiceap-invoiceapid').val(),
													'clientippublic':$('#clientippublic').val(),
													'clientiplocal':$('#clientiplocal').val(),
													'clientlat':$('#clientlat').val(),
													'clientlng':$('#clientlng').val(),
									},
									'type':'post',
									'dataType':'json',
									'success':function(data)
									{
										$('#dg-invoiceap-invoiceaptax').edatagrid('reload');
										$('#dg-invoiceap-invoiceapgr').edatagrid('reload');
										$('#dg-invoiceap-invoiceapdetail').edatagrid('reload');
									},
									'cache':false});
							jQuery.ajax({'url':'". Yii::app()->createUrl('accounting/invoiceap/generatepaymentmethod') ."',
									'data':{'addressbookid':$('#invoiceap-addressbookid').combogrid('getValue'),
													'date':$('#invoiceap-invoiceapdate').datebox('getValue')
									},
									'type':'post',
									'dataType':'json',
									'success':function(data)
									{
										$('#invoiceap-paymentmethodid').combogrid('setValue',data.paymentmethodid);
										$('#invoiceap-duedate').textbox('setValue',data.duedate);
									},
									'cache':false});
						},
						columns: [[
								{field:'poheaderid',title:'".GetCatalog('poheaderid') ."',width:'50px'},
								{field:'pono',title:'".GetCatalog('pono') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('invoiceaptaxno')."</td>
				<td><input class='easyui-textbox' id='invoiceap-invoiceaptaxno' name='invoiceap-invoiceaptaxno' data-options='required:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('nobuktipotong')."</td>
				<td><input class='easyui-textbox' id='invoiceap-nobuktipotong' name='invoiceap-nobuktipotong'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('receiptdate')."</td>
				<td><input class='easyui-datebox' type='text' id='invoiceap-receiptdate' name='invoiceap-receiptdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('paymentmethod')."</td>
				<td>
					<select class='easyui-combogrid' id='invoiceap-paymentmethodid' name='invoiceap-paymentmethodid' style='width:250px' data-options=\"
						panelWidth: '500px',
						disabled: true,
						idField: 'paymentmethodid',
						textField: 'paycode',
						mode:'remote',
						url: '".Yii::app()->createUrl('accounting/paymentmethod/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'paymentmethodid',title:'".GetCatalog('paymentmethodid') ."',width:'50px'},
								{field:'paycode',title:'".GetCatalog('paycode') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('duedate')."</td>
				<td><input class='easyui-textbox' id='invoiceap-duedate' name='invoiceap-duedate' data-options='required:true,disabled:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('discount')."</td>
				<td><input class='easyui-numberbox' id='invoiceap-discount' name='invoiceap-discount' data-options=\"
				required:true,
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				value:1,
				\"></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='invoiceap-headernote' name='invoiceap-headernote' data-options='multiline:true,required:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addload'=>"
		$('#invoiceap-invoiceapdate').datebox({value: (new Date().toString('dd-MMM-yyyy'))});
		$('#invoiceap-receiptdate').datebox({value: (new Date().toString('dd-MMM-yyyy'))});
		$('#invoiceap-slocid').val('');	
	",
	'loadsuccess'=>"
		$('#invoiceap-invoiceapdate').datebox('setValue',data.invoiceapdate);
		$('#invoiceap-invoiceapno').textbox('setValue',data.invoiceapno);
		$('#invoiceap-plantid').combogrid('setValue',data.plantid);
		$('#invoiceap-addressbookid').combogrid('setValue',data.addressbookid);
		$('#invoiceap-poheaderid').textbox('setValue',data.poheaderid);
		$('#invoiceap-invoiceaptaxno').textbox('setValue',data.invoiceaptaxno);
		$('#invoiceap-taxno').textbox('setValue',data.taxno);
		$('#invoiceap-nobuktipotong').textbox('setValue',data.nobuktipotong);
		$('#invoiceap-receiptdate').datebox('setValue',data.receiptdate);
		$('#invoiceap-paymentmethodid').combogrid('setValue',data.paymentmethodid);
		$('#invoiceap-duedate').textbox('setValue',data.duedate);
		$('#invoiceap-discount').numberbox('setValue',data.discount);
		$('#invoiceap-headernote').textbox('setValue',data.headernote);
		$('#invoiceap-slocid').val('');
	",
	'columndetails'=> array (
		array(
			'id'=>'invoiceaptax',
			'idfield'=>'invoiceaptaxid',
			'urlsub'=>Yii::app()->createUrl('accounting/invoiceap/indextax',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/invoiceap/searchtax',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/invoiceap/savetax',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/invoiceap/savetax',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/invoiceap/purgetax',array('grid'=>true)),
			'subs'=>"
				{field:'taxcode',title:'".GetCatalog('taxcode') ."',width:'200px'},
			",
			'columns'=>"
				{
					field:'invoiceapid',
					title:'".GetCatalog('invoiceapid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'invoiceaptaxid',
					title:'".GetCatalog('invoiceaptaxid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'taxid',
					title:'".GetCatalog('tax') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'800px',
							mode : 'remote',
							method:'get',
							idField:'taxid',
							textField:'taxcode',
							url:'".$this->createUrl('tax/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'taxid',title:'".GetCatalog('taxid')."',width:'80px'},
								{field:'taxcode',title:'".GetCatalog('taxcode')."',width:'350px'},
								{field:'taxvalue',title:'".GetCatalog('taxvalue')."',width:'120px'},
							]]
						}	
					},
					width:'300px',
					sortable: true,
					formatter: function(value,row,index){
						return row.taxcode;
					}
				},
			"
		),
		array(
			'id'=>'invoiceapgr',
			'idfield'=>'invoiceapgrid',
			'urlsub'=>Yii::app()->createUrl('accounting/invoiceap/indexgr',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/invoiceap/searchgr',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/invoiceap/savegr',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/invoiceap/savegr',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/invoiceap/purgegr',array('grid'=>true)),
			'subs'=>"
				{field:'grno',title:'".GetCatalog('grno') ."',width:'150px'},
				{field:'sjsupplier',title:'".GetCatalog('sjsupplier') ."',width:'200px'},
				{field:'supir',title:'".GetCatalog('supir') ."',width:'200px'},
				{field:'headernote',title:'".GetCatalog('headernote') ."',width:'400px'},
			",
			'columns'=>"
				{
					field:'invoiceapid',
					title:'".GetCatalog('invoiceapid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'invoiceapgrid',
					title:'".GetCatalog('invoiceapgrid') ."',
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'grheaderid',
					title:'".GetCatalog('grno') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'800px',
							mode : 'remote',
							method:'get',
							idField:'grheaderid',
							textField:'grno',
							url:'".Yii::app()->createUrl('inventory/grheader/index',array('grid'=>true,'invgr'=>true))."',
							onBeforeLoad:function(param) {
								param.plantid = $('#invoiceap-plantid').combogrid('getValue');
								param.poheaderid = $('#invoiceap-poheaderid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'grheaderid',title:'".GetCatalog('grheaderid')."',width:'80px'},
								{field:'grno',title:'".GetCatalog('grno')."',width:'350px'},
							]]
						}	
					},
					width:'300px',
					sortable: true,
					formatter: function(value,row,index){
						return row.grno;
					}
				},
				{
					field:'sjsupplier',
					title:'".GetCatalog('sjsupplier') ."',
					sortable: true,
					width:'200px',
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'supir',
					title:'".GetCatalog('supir') ."',
					sortable: true,
					width:'200px',
					formatter: function(value,row,index){
										return value;
					}
				},
			",
			'onsuccess'=>"$('#dg-invoiceap-invoiceapdetail').datagrid('reload');
			",
		),
		array(
			'id'=>'invoiceapdetail',
			'idfield'=>'invoiceapdetailid',
			'isnew'=>1,
			'ispurge'=>1,
			'urlsub'=>Yii::app()->createUrl('accounting/invoiceap/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/invoiceap/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/invoiceap/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/invoiceap/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/invoiceap/purgedetail',array('grid'=>true)),
			'subs'=>"
					{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'250px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:'".GetCatalog('uom2code') ."',width:'80px'},
				
				{field:'price',title:'".GetCatalog('price') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.price);
					},width:'150px'},
				{field:'discount',title:'".GetCatalog('discount') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.discount);
					},width:'120px'},
				{field:'dpp',title:'".GetCatalog('dpp') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.dpp);
					},width:'150px'},
				{field:'taxvalue',title:'".GetCatalog('taxvalue') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.taxvalue);
					},width:'150px'},
				{field:'total',title:'".GetCatalog('total') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.total);
					},width:'150px'},
				{field:'currencyname',title:'".GetCatalog('currencyname') ."',width:'100px'},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',align:'right',width:'60px'},
			",
			'columns'=>"
				{
					field:'invoiceapid',
					title:'".GetCatalog('invoiceapid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'invoiceapdetailid',
					title:'".GetCatalog('invoiceapdetailid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'invoiceapgrid',
					title:'".GetCatalog('invgrid') ."',
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'slocid',
					title:'".getCatalog('sloccode') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'650px',
							mode: 'remote',
							method:'get',
							idField:'slocid',
							textField:'sloccode',
							url:'".Yii::app()->createUrl('common/sloc/indextrxplant',array('grid'=>true)) ."',
							onBeforeLoad: function(param){
								param.plantid = $('#invoiceap-plantid').combogrid('getValue');
								var row = $('#dg-invoiceap-invoiceapdetail').edatagrid('getSelected');
								if(row==null){
									$(\"input[name='invoiceap-slocid']\").val('0');
								}
							},
							onSelect: function(index,row){
								var slocid = row.slocid;
								$(\"input[name='invoiceap-slocid']\").val(row.slocid);
							},
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var slocid = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'slocid'});
								var productid = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'productid'});
							
								$(productid.target).combogrid('setValue','');
								
							},
							fitColumns:true,
							required:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
									{field:'slocid',title:'".GetCatalog('slocid') ."',width:'50px'},
									{field:'sloccode',title:'".GetCatalog('sloccode') ."',width:'150px'},
									{field:'description',title:'".GetCatalog('description') ."',width:'250px'},
							]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
										return row.sloccode;
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
					title:'".getCatalog('productname') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'650px',
							mode: 'remote',
							method:'get',
							idField:'productid',
							textField:'productname',
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plant'=>true)) ."',
							onShowPanel: function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'productid'});
								var slocid = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'slocid'});
								$(productid.target).combogrid('grid').datagrid('load',{
									plantid:$('#invoiceap-plantid').combogrid('getValue')
								});
							},
							onBeforeLoad: function(param){
								param.plantid = $('#invoiceap-plantid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'productid'});
								var uomid = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'uom3id'});
								var uom4id = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'uom4id'});
								var stdqty2 = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'stdqty4'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/index',array('grid'=>true,'getdata'=>true)) ."',
									'data':{'productid':$(productid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(uomid.target).combogrid('setValue',data.uom1);
										$(uom2id.target).combogrid('setValue',data.uom2);
										$(uom3id.target).combogrid('setValue',data.uom3);
										$(uom4id.target).combogrid('setValue',data.uom4);
										$(stdqty2.target).numberbox('setValue',data.qty2);
										$(stdqty3.target).numberbox('setValue',data.qty3);
										$(stdqty4.target).numberbox('setValue',data.qty4);
									} ,
									'cache':false});
							},
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'productid',title:'".getCatalog('productid')."',width:'50px'},
								{field:'productname',title:'".getCatalog('productname')."',width:'450px'},
							]]
						}	
					},
					width:'150px',
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
								var stdqty2 = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'stdqty4'});								
								var qty2 = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'qty3'});								
								var qty4 = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'qty4'});						
								$(qty2.target).numberbox('setValue',$(stdqty2.target).numberbox('getValue') * newValue);
								$(qty3.target).numberbox('setValue',$(stdqty3.target).numberbox('getValue') * newValue);
								$(qty4.target).numberbox('setValue',$(stdqty4.target).numberbox('getValue') * newValue);
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
							readonly:false,
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
							required:true,
							readonly:false,
						}
					},
					hidden:true,
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
					hidden:true,
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
							required:true,
							readonly:false,
						}
					},
					hidden:true,
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
					hidden:true,
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uom4code;
					}
				},
				{
					field:'price',
					title:'".GetCatalog('price') ."',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							required:true,
							value:1,
							readonly:false,
						}
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					}
				},
				{
					field:'discount',
					title:'".GetCatalog('discount') ."',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							required:false,
							
							onChange: function(newValue,oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var qty = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'qty'});
								var price = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'price'});
								var discount = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'discount'});
								var dpp = $('#dg-invoiceap-invoiceapdetail').datagrid('getEditor', {index: index, field:'dpp'});
								$(dpp.target).numberbox('setValue',$(qty.target).numberbox('getValue') * ($(price.target).numberbox('getValue') - $(discount.target).numberbox('getValue')));
							}
						}
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					}
				},
				{
					field:'currencyid',
					title:'".GetCatalog('currency') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'currencyid',
							textField:'currencyname',
							url:'".Yii::app()->createUrl('admin/currency/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							readonly:false,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'currencyid',title:'".GetCatalog('currencyid')."',width:'50px'},
								{field:'currencyname',title:'".GetCatalog('currencyname')."',width:'150px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return row.currencyname;
					}
				},
				{
					field:'ratevalue',
					title:'".GetCatalog('ratevalue') ."',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							required:true,
							readonly:false,
							value:1,
						}
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return formatnumber('',value);
					}
				},
				{
					field:'dpp',
					title:'".GetCatalog('dpp') ."',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							required:true,
							readonly:false
						}
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return formatnumber('',value);
					}
				},
				{
					field:'taxvalue',
					title:'".GetCatalog('taxvalue') ."',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							required:true,
							readonly:false
						}
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return formatnumber('',value);
					}
				},
				{
					field:'total',
					title:'".GetCatalog('total') ."',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							required:true,
							readonly:true
						}
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					}
				},
				{
					field:'detailnote',
					title:'".GetCatalog('detailnote')."',
					editor: 'textbox',
					sortable: true,
					width:'300px',
					formatter: function(value,row,index){
										return value;
					}
				},
			"
		),
		array(
			'id'=>'invoiceapjurnal',
			'idfield'=>'invoiceapjurnalid',
			'isnew'=>1,
			'iswrite'=>1,
			'ispurge'=>1,
			'urlsub'=>Yii::app()->createUrl('accounting/invoiceap/indexjurnal',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/invoiceap/searchjurnal',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/invoiceap/savejurnal',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/invoiceap/savejurnal',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/invoiceap/purgejurnal',array('grid'=>true)),
			'subs'=>"
				{field:'accountname',title:'".GetCatalog('accountname') ."',width:'250px'},
				{field:'debit',title:'".GetCatalog('debit') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.debit);
					},width:'200px'},
				{field:'credit',title:'".GetCatalog('credit') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.credit);
					},width:'200px'},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',align:'right',width:'60px'},
				{field:'detailnote',title:'".GetCatalog('detailnote') ."',width:'450px'},
			",
			'columns'=>"
				{
					field:'invoiceapid',
					title:'".GetCatalog('invoiceapid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'invoiceapjurnalid',
					title:'".GetCatalog('invoiceapjurnalid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'accountid',
					title:'".GetCatalog('accountname') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'800px',
							mode : 'remote',
							method:'get',
							idField:'accountid',
							textField:'accountname',
							url:'".$this->createUrl('account/index',array('grid'=>true,'trxcom'=>true)) ."',
							fitColumns:true,
							required:true,
							onBeforeLoad: function(param) {
								 param.companyid = $('#invoiceap-plantid').combogrid('getValue');
							},
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'accountid',title:'".GetCatalog('accountid')."',width:'80px'},
								{field:'companyname',title:'".GetCatalog('company')."',width:'350px'},
								{field:'accountcode',title:'".GetCatalog('accountcode')."',width:'120px'},
								{field:'accountname',title:'".GetCatalog('accountname')."',width:'250px'},
							]]
						}	
					},
					width:'300px',
					sortable: true,
					formatter: function(value,row,index){
						return row.accountname;
					}
				},
				{
					field:'debit',
					title:'".GetCatalog('debit') ."',
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
					field:'credit',
					title:'".GetCatalog('credit') ."',
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
					field:'currencyid',
					title:'".GetCatalog('currency') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'currencyid',
							textField:'currencyname',
							url:'".Yii::app()->createUrl('admin/currency/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'currencyid',title:'".GetCatalog('currencyid')."',width:'50px'},
								{field:'currencyname',title:'".GetCatalog('currencyname')."',width:'150px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.currencyname;
					}
				},
				{
					field:'ratevalue',
					title:'".GetCatalog('ratevalue') ."',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							required:true,
							value:1,
						}
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
											return formatnumber('',value);
					}
				},
				{
					field:'detailnote',
					title:'".GetCatalog('detailnote')."',
					editor: 'textbox',
					sortable: true,
					width:'300px',
					formatter: function(value,row,index){
										return value;
					}
				},
			"
		),
	),
));