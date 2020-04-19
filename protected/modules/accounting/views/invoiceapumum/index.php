<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'invoiceapid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appinvap',
	'url'=>Yii::app()->createUrl('accounting/invoiceapumum/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('accounting/invoiceapumum/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/invoiceapumum/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/invoiceapumum/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/invoiceapumum/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('accounting/invoiceapumum/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('accounting/invoiceapumum/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/invoiceapumum/downpdf'),
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
	'searchfield'=> array ('invoiceapid','plantcode','supplier','invoiceapdate','supplier','pono','nobuktipotong','invoiceapno','invoiceaptaxno','headernote','recordstatus'),
	'headerform'=> "
		<input type='hidden' name='invoiceapumum-slocid' id='invoiceapumum-slocid' />
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('invoiceapdate')."</td>
				<td><input class='easyui-datebox' type='text' id='invoiceapumum-invoiceapdate' name='invoiceapumum-invoiceapdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('invoiceapno')."</td>
				<td><input class='easyui-textbox' id='invoiceapumum-invoiceapno' name='invoiceapumum-invoiceapno' data-options='required:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('plantcode')."</td>
				<td>
					<select class='easyui-combogrid' id='invoiceapumum-plantid' name='invoiceapumum-plantid' style='width:250px' data-options=\"
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
					<select class='easyui-combogrid' id='invoiceapumum-addressbookid' name='invoiceapumum-addressbookid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/supplier/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						onHidePanel: function(){
							jQuery.ajax({'url':'". Yii::app()->createUrl('common/supplier/generateaddress') ."',
								'data':{'id':$('#invoiceapumum-addressbookid').combogrid('getValue'),
										'date':$('#invoiceapumum-receiptdate').datebox('getValue')},
								'type':'post',
								'dataType':'json',
								'success':function(data)
								{
									$('#invoiceapumum-taxno').textbox('setValue',data.taxno);
									$('#invoiceapumum-paymentmethodid').combogrid('setValue',data.paymentmethodid);
									$('#invoiceapumum-duedate').datebox('setValue',data.duedate);
								} ,
								'cache':false});
						},
						columns: [[
								{field:'addressbookid',title:'".GetCatalog('addressbookid') ."',width:'50px'},
								{field:'fullname',title:'".GetCatalog('supplier') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('contractno')."</td>
				<td><input class='easyui-textbox' id='invoiceapumum-contractno' name='invoiceapumum-contractno'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('invoiceaptaxno')."</td>
				<td><input class='easyui-textbox' id='invoiceapumum-invoiceaptaxno' name='invoiceapumum-invoiceaptaxno'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('taxno')."</td>
				<td><input class='easyui-textbox' id='invoiceapumum-taxno' name='invoiceapumum-taxno' data-options='required:true,disabled:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('nobuktipotong')."</td>
				<td><input class='easyui-textbox' id='invoiceapumum-nobuktipotong' name='invoiceapumum-nobuktipotong'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('receiptdate')."</td>
				<td><input class='easyui-datebox' type='text' id='invoiceapumum-receiptdate' name='invoiceapumum-receiptdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('beritaacara')."</td>
				<td><input class='easyui-textbox' id='invoiceapumum-beritaacara' name='invoiceapumum-beritaacara'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('paymentmethod')."</td>
				<td>
					<select class='easyui-combogrid' id='invoiceapumum-paymentmethodid' name='invoiceapumum-paymentmethodid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'paymentmethodid',
						textField: 'paycode',
						mode:'remote',
						url: '".Yii::app()->createUrl('accounting/paymentmethod/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						onHidePanel:function() {
							jQuery.ajax({'url':'". Yii::app()->createUrl('common/supplier/generateaddress') ."',
								'data':{'id':$('#invoiceapumum-addressbookid').combogrid('getValue'),
										'date':$('#invoiceapumum-receiptdate').datebox('getValue'),
										'paymentmethodid':$('#invoiceapumum-paymentmethodid').combogrid('getValue')},
								'type':'post',
								'dataType':'json',
								'success':function(data)
								{
									$('#invoiceapumum-taxno').textbox('setValue',data.taxno);
									$('#invoiceapumum-duedate').datebox('setValue',data.duedate);
								} ,
								'cache':false});
						},
						columns: [[
							{field:'paymentmethodid',title:'".GetCatalog('paymentmethodid') ."',width:'50px'},
							{field:'paycode',title:'".GetCatalog('paycode') ."',width:'120px'},
							{field:'paydays',title:'".GetCatalog('paydays') ."',width:'120px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('duedate')."</td>
				<td><input class='easyui-datebox' id='invoiceapumum-duedate' name='invoiceapumum-duedate' data-options='formatter:dateformatter,readonly:true,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('discount')."</td>
				<td><input class='easyui-numberbox' id='invoiceapumum-discount' name='invoiceapumum-discount'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('cb')."</td>
				<td>
					<select class='easyui-combogrid' id='invoiceapumum-cashbankid' name='invoiceapumum-cashbankid' style='width:250px' data-options=\"
						panelWidth: '500px',
						idField: 'cashbankid',
						textField: 'cashbankno',
						mode:'remote',
						url: '".Yii::app()->createUrl('accounting/cashbank/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'cashbankid',title:'".GetCatalog('cashbankid') ."',width:'50px'},
								{field:'cashbankno',title:'".GetCatalog('cashbankno') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('dpamount')."</td>
				<td><input class='easyui-numberbox' id='invoiceapumum-dpamount' name='invoiceapumum-dpamount'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='invoiceapumum-headernote' name='invoiceapumum-headernote' data-options='multiline:true,required:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addload'=>"
		$('#invoiceapumum-invoiceapdate').datebox({value: (new Date().toString('dd-MMM-yyyy'))});
		$('#invoiceapumum-receiptdate').datebox({value: (new Date().toString('dd-MMM-yyyy'))});
		$('#invoiceapumum-duedate').datebox({value: (new Date().toString('dd-MMM-yyyy'))});
		$('#invoiceapumum-slocid').val('');
	",
	'loadsuccess'=>"
		$('#invoiceapumum-invoiceapdate').datebox('setValue',data.invoiceapdate);
		$('#invoiceapumum-invoiceapno').textbox('setValue',data.invoiceapno);
		$('#invoiceapumum-plantid').combogrid('setValue',data.plantid);
		$('#invoiceapumum-addressbookid').combogrid('setValue',data.addressbookid);
		$('#invoiceapumum-contractno').textbox('setValue',data.contractno);
		$('#invoiceapumum-invoiceaptaxno').textbox('setValue',data.invoiceaptaxno);
		$('#invoiceapumum-taxno').textbox('setValue',data.taxno);
		$('#invoiceapumum-nobuktipotong').textbox('setValue',data.nobuktipotong);
		$('#invoiceapumum-receiptdate').datebox('setValue',data.receiptdate);
		$('#invoiceapumum-beritaacara').textbox('setValue',data.beritaacara);
		$('#invoiceapumum-paymentmethodid').combogrid('setValue',data.paymentmethodid);
		$('#invoiceapumum-duedate').datebox('setValue',data.duedate);
		$('#invoiceapumum-discount').numberbox('setValue',data.discount);
		$('#invoiceapumum-cashbankid').textbox('setValue',data.cashbankid);
		$('#invoiceapumum-dpamount').numberbox('setValue',data.dpamount);
		$('#invoiceapumum-headernote').textbox('setValue',data.headernote);
		$('#invoiceapumum-slocid').val('');
	",
	'columndetails'=> array (
		array(
			'id'=>'invoiceapumumtax',
			'idfield'=>'invoiceaptaxid',
			'urlsub'=>Yii::app()->createUrl('accounting/invoiceapumum/indextax',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/invoiceapumum/searchtax',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/invoiceapumum/savetax',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/invoiceapumum/savetax',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/invoiceapumum/purgetax',array('grid'=>true)),
			'subs'=>"
				{field:'invoiceaptaxid',title:'".GetCatalog('invoiceaptaxid') ."',align:'right',width:'60px'},
				{field:'taxcode',title:'".GetCatalog('taxcode') ."',width:'200px'},
			",
			'columns'=>"
				{
					field:'invoiceaptaxid',
					title:'".GetCatalog('invoiceaptaxid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
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
			'id'=>'invoiceapumumdetail',
			'idfield'=>'invoiceapdetailid',
			'urlsub'=>Yii::app()->createUrl('accounting/invoiceapumum/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/invoiceapumum/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/invoiceapumum/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/invoiceapumum/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/invoiceapumum/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'invoiceapdetailid',title:'".GetCatalog('invoiceapdetailid') ."',align:'right',width:'60px'},
					{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'250px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'150px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},
				{field:'uom2code',title:'".GetCatalog('uom2code') ."',width:'150px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},
				{field:'uom3code',title:'".GetCatalog('uom3code') ."',width:'150px'},
				{field:'qty4',title:'".GetCatalog('qty4') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},
				{field:'uom4code',title:'".GetCatalog('uom4code') ."',width:'150px'},
				{field:'price',title:'".GetCatalog('price') ."',
					formatter: function(value,row,index){
						return row.symbol + ' ' + row.price;
					},align:'right',width:'120px'},
				{field:'discount',title:'".GetCatalog('discount') ."',
					formatter: function(value,row,index){
						return row.symbol + ' ' + row.discount;
					},align:'right',width:'120px'},
				{field:'dpp',title:'".GetCatalog('dpp') ."',
					formatter: function(value,row,index){
						return row.dpp;
					},align:'right',width:'120px'},
				{field:'taxvalue',title:'".GetCatalog('taxvalue') ."',
					formatter: function(value,row,index){
						return row.taxvalue;
					},align:'right',width:'120px'},
				{field:'total',title:'".GetCatalog('total') ."',
					formatter: function(value,row,index){
						return row.symbol + ' ' + row.total;
					},align:'right',width:'120px'},
				{field:'currencyname',title:'".GetCatalog('currencyname') ."',width:'350px'},
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
							url:'".Yii::app()->createUrl('common/sloc/indextrxplant',array('grid'=>true)) ."',
							onBeforeLoad: function(param){
								param.plantid = $('#invoiceapumum-plantid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'slocid',title:'".GetCatalog('slocid')."',width:'50px'},
								{field:'sloccode',title:'".GetCatalog('sloccode')."',width:'150px'},
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
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'productplantjasa'=>true)) ."',
							onShowPanel: function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-invoiceapumum-invoiceapumumdetail').datagrid('getEditor', {index: index, field:'productid'});
								var slocid = $('#dg-invoiceapumum-invoiceapumumdetail').datagrid('getEditor', {index: index, field:'slocid'});
								$(productid.target).combogrid('grid').datagrid('load',{
									slocid:$(slocid.target).combogrid('getValue')
								});
							},
							fitColumns:true,
							required:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-invoiceapumum-invoiceapumumdetail').datagrid('getEditor', {index: index, field:'productid'});
								var uomid = $('#dg-invoiceapumum-invoiceapumumdetail').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-invoiceapumum-invoiceapumumdetail').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-invoiceapumum-invoiceapumumdetail').datagrid('getEditor', {index: index, field:'uom3id'});
								var uom4id = $('#dg-invoiceapumum-invoiceapumumdetail').datagrid('getEditor', {index: index, field:'uom4id'});
								var stdqty2 = $('#dg-invoiceapumum-invoiceapumumdetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-invoiceapumum-invoiceapumumdetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-invoiceapumum-invoiceapumumdetail').datagrid('getEditor', {index: index, field:'stdqty4'});
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
								var stdqty2 = $('#dg-invoiceapumum-invoiceapumumdetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-invoiceapumum-invoiceapumumdetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-invoiceapumum-invoiceapumumdetail').datagrid('getEditor', {index: index, field:'stdqty4'});								
								var qty2 = $('#dg-invoiceapumum-invoiceapumumdetail').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-invoiceapumum-invoiceapumumdetail').datagrid('getEditor', {index: index, field:'qty3'});								
								var qty4 = $('#dg-invoiceapumum-invoiceapumumdetail').datagrid('getEditor', {index: index, field:'qty4'});						
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
					width:'80px',
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
					width:'80px',
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
					width:'80px',
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
					width:'80px',
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
						}
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
											return formatnumber('',value);
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
					field:'dpp',
					title:'".GetCatalog('dpp') ."',
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
					field:'taxvalue',
					title:'".GetCatalog('taxvalue') ."',
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
					field:'total',
					title:'".GetCatalog('total') ."',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							required:true,
							value:1,
							disabled:true,
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
		array(
			'id'=>'invoiceapumumjurnal',
			'idfield'=>'invoiceapjurnalid',
			'urlsub'=>Yii::app()->createUrl('accounting/invoiceapumum/indexjurnal',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/invoiceapumum/searchjurnal',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/invoiceapumum/savejurnal',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/invoiceapumum/savejurnal',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/invoiceapumum/purgejurnal',array('grid'=>true)),
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
							url:'".$this->createUrl('account/index',array('grid'=>true,'trx'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'accountid',title:'".GetCatalog('accountid')."',width:'80px'},
								{field:'accountcode',title:'".GetCatalog('accountcode')."',width:'120px'},
								{field:'accountname',title:'".GetCatalog('accountname')."',width:'250px'},
								{field:'companyname',title:'".GetCatalog('company')."',width:'250px'},
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