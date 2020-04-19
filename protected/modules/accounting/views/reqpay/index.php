<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'reqpayid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'isupload'=>0,
	'wfapp'=>'appreqpay',
	'url'=>Yii::app()->createUrl('accounting/reqpay/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('accounting/reqpay/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/reqpay/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/reqpay/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/reqpay/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('accounting/reqpay/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('accounting/reqpay/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/reqpay/downpdf'),
	'columns'=>"
		{
			field:'reqpayid',
			title:'".GetCatalog('reqpayid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appreqpay')."
		}},
		{
			field:'companyid',
			title:'".GetCatalog('companycode') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return row.companycode;
		}},
		{
			field:'reqpaydate',
			title:'".GetCatalog('reqpaydate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'reqpayno',
			title:'".GetCatalog('reqpayno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'addressbookid',
			title:'".GetCatalog('supplier') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return row.fullname;
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
	'searchfield'=> array ('reqpayid','companycode','reqpaydate','reqpayno','invoiceapno','supplier','headernote','recordstatus'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('reqpayno')."</td>
				<td><input class='easyui-textbox' id='reqpay-reqpayno' name='reqpay-reqpayno' data-options='disabled:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('reqpaydate')."</td>
				<td><input class='easyui-datebox' type='text' id='reqpay-reqpaydate' name='reqpay-reqpaydate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('companycode')."</td>
				<td>
					<select class='easyui-combogrid' id='reqpay-companyid' name='reqpay-companyid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'companyid',
						textField: 'companycode',
						mode:'remote',
						url: '".Yii::app()->createUrl('admin/company/index',array('grid'=>true,'auth'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'companyid',title:'".GetCatalog('companyid') ."',width:'50px'},
								{field:'companycode',title:'".GetCatalog('companycode') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('supplier')."</td>
				<td>
					<select class='easyui-combogrid' id='reqpay-addressbookid' name='reqpay-addressbookid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/supplier/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'addressbookid',title:'".GetCatalog('addressbookid') ."',width:'50px'},
								{field:'fullname',title:'".GetCatalog('fullname') ."',width:'350px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='reqpay-headernote' name='reqpay-headernote' data-options='multiline:true,required:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addload'=>"
		$('#reqpay-reqpaydate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});	",
	'loadsuccess'=>"
		$('#reqpay-reqpaydate').datebox('setValue',data.reqpaydate);
		$('#reqpay-reqpayno').textbox('setValue',data.reqpayno);
		$('#reqpay-plantid').combogrid('setValue',data.plantid);
		$('#reqpay-headernote').textbox('setValue',data.headernote);
	",
	'columndetails'=> array (
		array(
			'id'=>'reqpaydetail',
			'idfield'=>'reqpayinvid',
			'urlsub'=>Yii::app()->createUrl('accounting/reqpay/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/reqpay/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/reqpay/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/reqpay/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/reqpay/purgeinvoice',array('grid'=>true)),
			'subs'=>"
				{field:'invoiceapno',title:'".GetCatalog('invoiceapno') ."',width:'150px'},
				{field:'notagrrno',title:'".GetCatalog('notagrrno') ."',width:'150px'},
				{field:'duedate',title:'".GetCatalog('duedate') ."',width:'150px'},
				{field:'amount',title:'".GetCatalog('amount') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},width:'150px'},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},width:'100px'},
				{field:'headernote',title:'".GetCatalog('headernote') ."',width:'150px'},
			",
			'columns'=>"
				{
					field:'reqpayid',
					title:'".GetCatalog('reqpayid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'reqpaydetailid',
					title:'".GetCatalog('reqpaydetailid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'invoiceapid',
					title:'".GetCatalog('invoiceapno') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'850px',
							mode : 'remote',
							method:'get',
							idField:'invoiceapid',
							textField:'invoiceapno',
							url:'".Yii::app()->createUrl('accounting/invoiceap/indexreqpay',array('grid'=>true)) ."',
							fitColumns:true,
							onBeforeLoad: function(param){
								param.companyid = $('#reqpay-companyid').combogrid('getValue');
								param.addressbookid = $('#reqpay-addressbookid').combogrid('getValue');
							},
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var invoiceapid = $('#dg-reqpay-reqpaydetail').datagrid('getEditor', {index: index, field:'invoiceapid'});
								var duedate = $('#dg-reqpay-reqpaydetail').datagrid('getEditor', {index: index, field:'duedate'});
								var receiptdate = $('#dg-reqpay-reqpaydetail').datagrid('getEditor', {index: index, field:'receiptdate'});
								var amount = $('#dg-reqpay-reqpaydetail').datagrid('getEditor', {index: index, field:'amount'});
								var currencyid = $('#dg-reqpay-reqpaydetail').datagrid('getEditor', {index: index, field:'currencyid'});
								var ratevalue = $('#dg-reqpay-reqpaydetail').datagrid('getEditor', {index: index, field:'ratevalue'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('accounting/reqpay/generateinvoiceapdetail',array('grid'=>true)) ."',
									'data':{'invoiceapid':$(invoiceapid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(duedate.target).textbox('setValue',data.duedate);
										$(amount.target).numberbox('setValue',data.amount);
										$(currencyid.target).combogrid('setValue',data.currencyid);
										$(ratevalue.target).numberbox('setValue',data.ratevalue);
									} ,
									'cache':false});
							},
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'invoiceapid',title:'".GetCatalog('invoiceapid')."',width:'50px'},
								{field:'invoiceapno',title:'".GetCatalog('invoiceapno')."',width:'150px'},
								{field:'pono',title:'".GetCatalog('pono')."',width:'150px'},
								{field:'duedate',title:'".GetCatalog('duedate')."',width:'150px'},
								{field:'receiptdate',title:'".GetCatalog('receiptdate')."',width:'150px'},
								{field:'fullname',title:'".GetCatalog('fullname')."',width:'350px'},
							
							]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
										return row.invoiceapno;
					}
				},
				{
					field:'notagrrid',
					title:'".GetCatalog('notagrr') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'850px',
							mode : 'remote',
							method:'get',
							idField:'notagrrid',
							textField:'notagrrno',
							url:'".Yii::app()->createUrl('accounting/notagrr/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							onBeforeLoad: function(param){
								param.companyid = $('#reqpay-companyid').combogrid('getValue');
								param.addressbookid = $('#reqpay-addressbookid').combogrid('getValue');
							},
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var notagrrid = $('#dg-reqpay-reqpaydetail').datagrid('getEditor', {index: index, field:'notagrrid'});
								var duedate = $('#dg-reqpay-reqpaydetail').datagrid('getEditor', {index: index, field:'duedate'});
								var receiptdate = $('#dg-reqpay-reqpaydetail').datagrid('getEditor', {index: index, field:'receiptdate'});
								var amount = $('#dg-reqpay-reqpaydetail').datagrid('getEditor', {index: index, field:'amount'});
								var currencyid = $('#dg-reqpay-reqpaydetail').datagrid('getEditor', {index: index, field:'currencyid'});
								var ratevalue = $('#dg-reqpay-reqpaydetail').datagrid('getEditor', {index: index, field:'ratevalue'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('accounting/reqpay/generatenotagrr',array('grid'=>true)) ."',
									'data':{'notagrrid':$(notagrrid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(duedate.target).textbox('setValue',data.duedate);
										$(amount.target).numberbox('setValue',data.amount);
										$(currencyid.target).combogrid('setValue',data.currencyid);
										$(ratevalue.target).numberbox('setValue',data.ratevalue);
									} ,
									'cache':false});
							},
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'notagrrid',title:'".GetCatalog('notagrrid')."',width:'50px'},
								{field:'notagrrno',title:'".GetCatalog('notagrrno')."',width:'150px'},
								{field:'pono',title:'".GetCatalog('pono')."',width:'150px'},
								{field:'fullname',title:'".GetCatalog('fullname')."',width:'350px'},
							
							]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
										return row.notagrrno;
					}
				},
				{
					field:'duedate',
					title:'".GetCatalog('duedate') ."',
					width:'100px',
					editor: {
						type: 'textbox',
						options:{
							disabled:'true',
						},
					},
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'amount',
					title:'".GetCatalog('amount') ."',
					width:'150px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							disabled:true,
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
