<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'cashbankinid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appcbin',
	'url'=>Yii::app()->createUrl('accounting/cashbankin/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('accounting/cashbankin/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/cashbankin/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/cashbankin/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/cashbankin/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('accounting/cashbankin/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('accounting/cashbankin/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/cashbankin/downpdf'),
	'columns'=>"
		{
			field:'cashbankinid',
			title:localStorage.getItem('catalogcashbankinid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appcbin')."
		}},
		{
			field:'companyid',
			title:localStorage.getItem('catalogcompany'),
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return row.companycode;
		}},
		{
			field:'cashbankindate',
			title:localStorage.getItem('catalogcashbankindate'),
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'cashbankinno',
			title:localStorage.getItem('catalogcashbankinno'),
			sortable: true,
			width:'180px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'addressbookid',
			title:localStorage.getItem('catalogcustomer'),
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'chequeid',
			title:localStorage.getItem('catalogchequeno'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return row.chequeno;
		}},
		{
			field:'accountid',
			title:localStorage.getItem('catalogaccountname'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return row.accountname;
		}},
		{
			field:'headernote',
			title:localStorage.getItem('catalogheadernote'),
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatusname',
			title:localStorage.getItem('catalogrecordstatus'),
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
	'searchfield'=> array ('cashbankinid','cashbankindate','customer','plantcode','cashbankinno','invoicearno','accountname','recordstatus'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td id='cashbankintext-cashbankinno'></td>
				<td><input class='easyui-textbox' id='cashbankin-cashbankinno' name='cashbankin-cashbankinno' data-options='disabled:true'></input></td>
			</tr>
			<tr>
				<td id='cashbankintext-company'></td>
				<td>
					<select class='easyui-combogrid' id='cashbankin-companyid' name='cashbankin-companyid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'companyid',
						textField: 'companycode',
						mode:'remote',
						url: '".Yii::app()->createUrl('admin/company/index',array('grid'=>true,'auth'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'companyid',title:localStorage.getItem('catalogcompanyid'),width:'50px'},
								{field:'companycode',title:localStorage.getItem('catalogcompanycode'),width:'150px'},
								{field:'companyname',title:localStorage.getItem('catalogcompanyname'),width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td id='cashbankintext-cashbankindate'></td>
				<td><input class='easyui-datebox' type='text' id='cashbankin-cashbankindate' name='cashbankin-cashbankindate' data-options='readonly:false,formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td id='cashbankintext-accountname'></td>
				<td>
					<select class='easyui-combogrid' id='cashbankin-accountid' name='cashbankin-accountid' style='width:250px' data-options=\"
						panelWidth: '800px',
						required: true,
						idField: 'accountid',
						textField: 'accountname',
						mode:'remote',
						url: '".Yii::app()->createUrl('accounting/account/index',array('grid'=>true,'trxcom'=>true)) ."',
						method: 'get',
						onShowPanel:function() {
							$('#cashbankin-accountid').combogrid('grid').datagrid('reload');
						},
						onBeforeLoad: function(param) {
							param.companyid = $('#cashbankin-companyid').combogrid('getValue');
						},
						columns: [[
								{field:'accountid',title:localStorage.getItem('catalogaccountid'),width:'80px'},
								{field:'accountcode',title:localStorage.getItem('catalogaccountcode'),width:'120px'},
								{field:'accountname',title:localStorage.getItem('catalogaccountname'),width:'250px'},
								{field:'companyname',title:localStorage.getItem('catalogcompany'),width:'350px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td id='cashbankintext-customer'></td>
				<td>
					<select class='easyui-combogrid' id='cashbankin-addressbookid' name='cashbankin-addressbookid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/customer/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'addressbookid',title:localStorage.getItem('catalogaddressbookid'),width:'50px'},
								{field:'fullname',title:localStorage.getItem('catalogfullname'),width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td id='cashbankintext-headernote'></td>
				<td><input class='easyui-textbox' id='cashbankin-headernote' name='cashbankin-headernote' data-options='multiline:true,required:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addonscripts' => "
		$(document).ready(function(){
			var parel = document.getElementById('cashbankintext-cashbankinno');
			parel.innerHTML = localStorage.getItem('catalogcashbankinno');
			parel = document.getElementById('cashbankintext-company');
			parel.innerHTML = localStorage.getItem('catalogcompany');
			parel = document.getElementById('cashbankintext-cashbankindate');
			parel.innerHTML = localStorage.getItem('catalogcashbankindate');
			parel = document.getElementById('cashbankintext-accountname');
			parel.innerHTML = localStorage.getItem('catalogaccountname');
			parel = document.getElementById('cashbankintext-customer');
			parel.innerHTML = localStorage.getItem('catalogcustomer');
			parel = document.getElementById('cashbankintext-headernote');
			parel.innerHTML = localStorage.getItem('catalogheadernote');
		});
	",
	'addload'=>"
		$('#cashbankin-cashbankindate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});	",
	'loadsuccess'=>"
		$('#cashbankin-cashbankindate').datebox('setValue',data.cashbankindate);
		$('#cashbankin-cashbankinno').textbox('setValue',data.cashbankinno);
		$('#cashbankin-companyid').combogrid('setValue',data.companyid);
		$('#cashbankin-addressbookid').combogrid('setValue',data.addressbookid);
		$('#cashbankin-accountid').textbox('setValue',data.accountid);
		$('#cashbankin-headernote').textbox('setValue',data.headernote);
	",
	'columndetails'=> array (
		array(
			'id'=>'cashbankindetail',
			'idfield'=>'cashbankindetailid',
			'urlsub'=>Yii::app()->createUrl('accounting/cashbankin/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/cashbankin/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/cashbankin/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/cashbankin/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/cashbankin/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'invoicearno',title:localStorage.getItem('cataloginvoicearno'),width:'120px'},
				{field:'amount',title:localStorage.getItem('catalogamount'),
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.amount);
					},
				},
				{field:'ratevalue',title:localStorage.getItem('catalogratevalue'),align:'right',width:'60px'},
				{field:'detailnote',title:localStorage.getItem('catalogdetailnote'),width:'200px'},
			",
			'columns'=>"
				{
					field:'cashbankinid',
					title:localStorage.getItem('catalogcashbankinid'),
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'cashbankindetailid',
					title:localStorage.getItem('catalogcashbankindetailid'),
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'notagirid',
					title:localStorage.getItem('catalognotagir'),
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'900px',
							mode : 'remote',
							method:'get',
							idField:'notagirid',
							textField:'invoicearno',
							url: '".Yii::app()->createUrl('accounting/notagir/indextrxcb',array('grid'=>true)) ."',
							fitColumns:true,
							onBeforeLoad: function(param){
								param.companyid = $('#cashbankin-companyid').combogrid('getValue');
								param.addressbookid = $('#cashbankin-addressbookid').combogrid('getValue');
							},
							onHidePanel: function(){
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var notagirid = $('#dg-cashbankin-cashbankindetail').datagrid('getEditor', {index: index, field:'notagirid'});
								var invoicearid = $('#dg-cashbankin-cashbankindetail').datagrid('getEditor', {index: index, field:'invoicearid'});
								var amount = $('#dg-cashbankin-cashbankindetail').datagrid('getEditor', {index: index, field:'amount'});
								var currencyid = $('#dg-cashbankin-cashbankindetail').datagrid('getEditor', {index: index, field:'currencyid'});
								var ratevalue = $('#dg-cashbankin-cashbankindetail').datagrid('getEditor', {index: index, field:'ratevalue'});
								var detailnote = $('#dg-cashbankin-cashbankindetail').datagrid('getEditor', {index: index, field:'detailnote'});
								jQuery.ajax({'url':'". Yii::app()->createUrl('accounting/cashbankin/generatenotagir') ."',
									'data':{
										'vid':$(notagirid.target).combogrid('getValue'),
										'hid':$('#cashbankin-cashbankinid').val(),
									},
									'type':'post',
									'dataType':'json',
									'success':function(data)
									{
										$(invoicearid.target).combogrid('setValue',data.invoicearid);
										$(amount.target).numberbox('setValue',data.amount);
										$(currencyid.target).combogrid('setValue',data.currencyid);
										$(ratevalue.target).numberbox('setValue',data.ratevalue);
										$(detailnote.target).textbox('setValue',data.detailnote);
									} ,
									'cache':false});
							},
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'invoicearid',title:localStorage.getItem('cataloginvoicearid'),width:'50px'},
								{field:'invoicearno',title:localStorage.getItem('cataloginvoicearno'),width:'150px'},
								{field:'notagirno',title:localStorage.getItem('catalognotagirno'),width:'250px'},
								{field:'sono',title:localStorage.getItem('catalogsono'),width:'150px'},
								{field:'invoiceartaxno',title:localStorage.getItem('cataloginvoiceartaxno'),width:'150px'},
							]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
										return row.notagirno;
					}
				},
				{
					field:'invoicearid',
					title:localStorage.getItem('cataloginvoicearno'),
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'900px',
							mode : 'remote',
							method:'get',
							idField:'invoicearid',
							textField:'invoicearno',
							url:'".Yii::app()->createUrl('accounting/invoicear/indexcbin',array('grid'=>true,'invar'=>true)) ."',
							fitColumns:true,
							required:true,
							onBeforeLoad: function(param){
								param.companyid = $('#cashbankin-companyid').combogrid('getValue');
								param.addressbookid = $('#cashbankin-addressbookid').combogrid('getValue');
							},
							onHidePanel: function(){
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var invoicearid = $('#dg-cashbankin-cashbankindetail').datagrid('getEditor', {index: index, field:'invoicearid'});
								var amount = $('#dg-cashbankin-cashbankindetail').datagrid('getEditor', {index: index, field:'amount'});
								var currencyid = $('#dg-cashbankin-cashbankindetail').datagrid('getEditor', {index: index, field:'currencyid'});
								var ratevalue = $('#dg-cashbankin-cashbankindetail').datagrid('getEditor', {index: index, field:'ratevalue'});
								var detailnote = $('#dg-cashbankin-cashbankindetail').datagrid('getEditor', {index: index, field:'detailnote'});
								jQuery.ajax({'url':'". Yii::app()->createUrl('accounting/cashbankin/generateinvar') ."',
									'data':{'invoicearid':$(invoicearid.target).combogrid('getValue')},
									'type':'post',
									'dataType':'json',
									'success':function(data)
									{
										$(amount.target).numberbox('setValue',data.amount);
										$(currencyid.target).combogrid('setValue',data.currencyid);
										$(ratevalue.target).numberbox('setValue',data.ratevalue);
										$(detailnote.target).textbox('setValue',data.detailnote);
									} ,
									'cache':false});
							},
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'invoicearid',title:localStorage.getItem('cataloginvoicearid'),width:'50px'},
								{field:'invoicearno',title:localStorage.getItem('cataloginvoicearno'),width:'150px'},
								{field:'fullname',title:localStorage.getItem('catalogcustomer'),width:'250px'},
								{field:'contractno',title:localStorage.getItem('catalogcontractno'),width:'150px'},
								{field:'sono',title:localStorage.getItem('catalogsono'),width:'150px'},
								{field:'invoiceartaxno',title:localStorage.getItem('cataloginvoiceartaxno'),width:'150px'},
							]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
										return row.invoicearno;
					}
				},
				{
					field:'amount',
					title:localStorage.getItem('catalogvalue'),
					width:'150px',
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
						return formatnumber(row.symbol,value);
					}
				},
				{
					field:'currencyid',
					title:localStorage.getItem('catalogcurrency'),
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
							readonly:true,
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'currencyid',title:localStorage.getItem('catalogcurrencyid'),width:'50px'},
								{field:'currencyname',title:localStorage.getItem('catalogcurrencyname'),width:'150px'},
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
					title:localStorage.getItem('catalogratevalue'),
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							required:true,
							readonly:true,
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
					title:localStorage.getItem('catalogdetailnote'),
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
			'id'=>'cashbankinacc',
			'idfield'=>'cashbankinaccid',
			'isnew'=> 1,
			'iswrite'=> 1,
			'ispurge'=> 1,
			'urlsub'=>Yii::app()->createUrl('accounting/cashbankin/indexacc',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/cashbankin/searchacc',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/cashbankin/saveacc',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/cashbankin/saveacc',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/cashbankin/purgeacc',array('grid'=>true)),
			'subs'=>"
				{field:'accountname',title:localStorage.getItem('catalogaccountname'),width:'250px'},
				{field:'debit',title:localStorage.getItem('catalogdebit'),
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value)
					},width:'120px'},
				{field:'credit',title:localStorage.getItem('catalogcredit'),
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value)
					},width:'120px'},
				{field:'ratevalue',title:localStorage.getItem('catalogratevalue'),
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value)
					},width:'60px'},
				{field:'detailnote',title:localStorage.getItem('catalogdetailnote'),width:'350px'},
			",
			'columns'=>"
				{
					field:'cashbankinid',
					title:localStorage.getItem('catalogcashbankinid'),
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'cashbankinjurnalid',
					title:localStorage.getItem('catalogcashbankinjurnalid'),
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'accountid',
					title:localStorage.getItem('catalogaccountname'),
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'850px',
							mode : 'remote',
							method:'get',
							idField:'accountid',
							textField:'accountname',
							url:'".$this->createUrl('account/index',array('grid'=>true,'trxcom'=>true)) ."',
							fitColumns:true,
							onBeforeLoad: function(param) {
								 param.companyid = $('#cashbankin-companyid').combogrid('getValue');
							},
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'accountid',title:localStorage.getItem('catalogaccountid'),width:'80px'},
								{field:'accountcode',title:localStorage.getItem('catalogaccountcode'),width:'120px'},
								{field:'accountname',title:localStorage.getItem('catalogaccountname'),width:'400px'},
								{field:'companyname',title:localStorage.getItem('catalogcompany'),width:'300px'},
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
					title:localStorage.getItem('catalogdebit'),
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
					title:localStorage.getItem('catalogcredit'),
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
					title:localStorage.getItem('catalogcurrency'),
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
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'currencyid',title:localStorage.getItem('catalogcurrencyid'),width:'50px'},
								{field:'currencyname',title:localStorage.getItem('catalogcurrencyname'),width:'150px'},
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
					title:localStorage.getItem('catalogratevalue'),
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
					field:'itemnote',
					title:localStorage.getItem('catalogitemnote'),
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
			'id'=>'cashbankinjurnal',
			'idfield'=>'cashbankinjurnalid',
			'isnew'=> 0,
			'iswrite'=> 0,
			'ispurge'=> 0,
			'urlsub'=>Yii::app()->createUrl('accounting/cashbankin/indexjurnal',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/cashbankin/searchjurnal',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/cashbankin/savejurnal',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/cashbankin/savejurnal',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/cashbankin/purgejurnal',array('grid'=>true)),
			'subs'=>"
				{field:'accountname',title:localStorage.getItem('catalogaccountname'),width:'250px'},
				{field:'debit',title:localStorage.getItem('catalogdebit'),
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value)
					},width:'120px'},
				{field:'credit',title:localStorage.getItem('catalogcredit'),
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value)
					},width:'120px'},
				{field:'ratevalue',title:localStorage.getItem('catalogratevalue'),
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value)
					},width:'60px'},
				{field:'detailnote',title:localStorage.getItem('catalogdetailnote'),width:'350px'},
			",
			'columns'=>"
				{
					field:'cashbankinid',
					title:localStorage.getItem('catalogcashbankinid'),
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'cashbankinjurnalid',
					title:localStorage.getItem('catalogcashbankinjurnalid'),
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'accountid',
					title:localStorage.getItem('catalogaccountname'),
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
								 param.companyid = $('#cashbankin-companyid').combogrid('getValue');
							},
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'accountid',title:localStorage.getItem('catalogaccountid'),width:'80px'},
								{field:'companyname',title:localStorage.getItem('catalogcompany'),width:'350px'},
								{field:'accountcode',title:localStorage.getItem('catalogaccountcode'),width:'120px'},
								{field:'accountname',title:localStorage.getItem('catalogaccountname'),width:'250px'},
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
					title:localStorage.getItem('catalogdebit'),
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
					title:localStorage.getItem('catalogcredit'),
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
					title:localStorage.getItem('catalogcurrency'),
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
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'currencyid',title:localStorage.getItem('catalogcurrencyid'),width:'50px'},
								{field:'currencyname',title:localStorage.getItem('catalogcurrencyname'),width:'150px'},
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
					title:localStorage.getItem('catalogratevalue'),
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
					title:localStorage.getItem('catalogdetailnote'),
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