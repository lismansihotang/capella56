<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'cashbankoutid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appcbout',
	'url'=>Yii::app()->createUrl('accounting/cashbankout/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('accounting/cashbankout/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/cashbankout/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/cashbankout/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/cashbankout/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('accounting/cashbankout/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('accounting/cashbankout/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/cashbankout/downpdf'),
	'columns'=>"
		{
			field:'cashbankoutid',
			title:localStorage.getItem('catalogcashbankoutid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appcbout')."
		}},
		{
			field:'companyid',
			title:localStorage.getItem('catalogcompany'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.companycode;
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
			field:'cashbankoutdate',
			title:localStorage.getItem('catalogcashbankoutdate'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'cashbankoutno',
			title:localStorage.getItem('catalogcashbankoutno'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'reqpayid',
			title:localStorage.getItem('catalogreqpay'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.reqpayno;
		}},
		{
			field:'notagrrid',
			title:localStorage.getItem('catalognotagrr'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.notagrrno;
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
	'searchfield'=> array ('cashbankoutid','companycode','cashbankoutno','cashbankoutdate','reqpayno','supplier','pono','invoiceapno','headernote','acccodeheader','accnameheader',
		'acccodedetail','accnamedetail','recordstatus'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td id='cashbankouttext-cashbankoutno'></td>
				<td><input class='easyui-textbox' id='cashbankout-cashbankoutno' name='cashbankout-cashbankoutno' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td id='cashbankouttext-company'></td>
				<td>
					<select class='easyui-combogrid' id='cashbankout-companyid' name='cashbankout-companyid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'companyid',
						textField: 'companycode',
						mode:'remote',
						url: '".Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true,'auth'=>true)) ."',
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
				<td id='cashbankouttext-cashbankoutdate'></td>
				<td><input class='easyui-datebox' type='text' id='cashbankout-cashbankoutdate' name='cashbankout-cashbankoutdate' data-options='readonly:false,formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td id='cashbankouttext-accountname'></td>
				<td>
					<select class='easyui-combogrid' id='cashbankout-accountid' name='cashbankout-accountid' style='width:250px' data-options=\"
						panelWidth: '800px',
						required: true,
						idField: 'accountid',
						textField: 'accountname',
						mode:'remote',
						url: '".Yii::app()->createUrl('accounting/account/index',array('grid'=>true,'trxcom'=>true)) ."',
						method: 'get',
						onShowPanel:function() {
							$('#cashbankout-accountid').combogrid('grid').datagrid('reload');
						},
						onBeforeLoad: function(param) {
							param.companyid = $('#cashbankout-companyid').combogrid('getValue');
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
				<td id='cashbankouttext-reqpay'></td>
				<td>
					<select class='easyui-combogrid' id='cashbankout-reqpayid' name='cashbankout-reqpayid' style='width:250px' data-options=\"
						panelWidth: '800px',
						idField: 'reqpayid',
						textField: 'reqpayno',
						required: true,
						mode:'remote',
						url: '".Yii::app()->createUrl('accounting/reqpay/index',array('grid'=>true,'cashbankout'=>true)) ."',
						method: 'get',
						onBeforeLoad: function(param){
							param.companyid = $('#cashbankout-companyid').combogrid('getValue');
						},
						onHidePanel: function(){
							jQuery.ajax(
								{'url':'".$this->createUrl('cashbankout/generatedetail') ."',
									'data':{
										'id':$('#cashbankout-reqpayid').combogrid('getValue'),
										'hid':$('#cashbankout-cashbankoutid').val(),
										'accountid':$('#cashbankout-accountid').combogrid('getValue')},
									'type':'post',
									'dataType':'json',
									'success':function(data)
									{
										$('#dg-cashbankout-cashbankoutdetail').edatagrid('reload');
										$('#dg-cashbankout-cashbankoutjurnal').edatagrid('reload');
									},
									'cache':false},
							);
						},
						columns: [[
								{field:'reqpayid',title:localStorage.getItem('catalogreqpayid'),width:'50px'},
								{field:'reqpayno',title:localStorage.getItem('catalogreqpayno'),width:'150px'},
								{field:'fullname',title:localStorage.getItem('catalogsupplier'),width:'250px'},
								{field:'headernote',title:localStorage.getItem('catalogheadernote'),width:'350px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td id='cashbankouttext-headernote'></td>
				<td><input class='easyui-textbox' id='cashbankout-headernote' name='cashbankout-headernote' data-options='multiline:true,required:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addonscripts' => "
		$(document).ready(function(){
			var parel = document.getElementById('cashbankouttext-cashbankoutno');
			parel.innerHTML = localStorage.getItem('catalogcashbankoutno');
			parel = document.getElementById('cashbankouttext-company');
			parel.innerHTML = localStorage.getItem('catalogcompany');
			parel = document.getElementById('cashbankouttext-cashbankoutdate');
			parel.innerHTML = localStorage.getItem('catalogcashbankoutdate');
			parel = document.getElementById('cashbankouttext-accountname');
			parel.innerHTML = localStorage.getItem('catalogaccountname');
			parel = document.getElementById('cashbankouttext-reqpay');
			parel.innerHTML = localStorage.getItem('catalogreqpay');
			parel = document.getElementById('cashbankouttext-headernote');
			parel.innerHTML = localStorage.getItem('catalogheadernote');
		});
	",
	'addload'=>"
		$('#cashbankout-cashbankoutdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});	",
	'loadsuccess'=>"
		$('#cashbankout-cashbankoutdate').datebox('setValue',data.cashbankoutdate);
		$('#cashbankout-cashbankoutno').textbox('setValue',data.cashbankoutno);
		$('#cashbankout-companyid').combogrid('setValue',data.companyid);
		$('#cashbankout-reqpayid').combogrid('setValue',data.reqpayid);
		$('#cashbankout-accountid').textbox('setValue',data.accountid);
		$('#cashbankout-headernote').textbox('setValue',data.headernote);
	",
	'columndetails'=> array (
		array(
			'id'=>'cashbankoutdetail',
			'idfield'=>'cashbankoutdetailid',
			'isnew'=> 0,
			'ispurge'=> 0,
			'urlsub'=>Yii::app()->createUrl('accounting/cashbankout/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/cashbankout/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/cashbankout/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/cashbankout/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/cashbankout/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'cashbankoutdetailid',title:localStorage.getItem('catalogcashbankoutdetailid'),align:'right',width:'60px'},
				{field:'invoiceapno',title:localStorage.getItem('cataloginvoiceapno'),width:'150px'},
				{field:'pono',title:localStorage.getItem('catalogpono'),width:'150px'},
				{field:'fullname',title:localStorage.getItem('catalogsupplier'),width:'300px'},
				{field:'bilyetgirono',title:localStorage.getItem('catalogbilyetgirono'),width:'150px'},
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
					field:'cashbankoutid',
					title:localStorage.getItem('catalogcashbankoutid'),
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'cashbankoutdetailid',
					title:localStorage.getItem('catalogcashbankoutdetailid'),
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'addressbookid',
					title:localStorage.getItem('catalogsupplier'),
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'addressbookid',
							textField:'fullname',
							url: '".Yii::app()->createUrl('common/supplier/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							readonly:true,
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'addressbookid',title:localStorage.getItem('catalogaddressbookid'),width:'50px'},
								{field:'fullname',title:localStorage.getItem('catalogsupplier'),width:'150px'},
							]]
						}	
					},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
										return row.fullname;
					}
				},
				{
					field:'invoiceapid',
					title:localStorage.getItem('cataloginvoiceapno'),
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'invoiceapid',
							textField:'invoiceapno',
							url:'".Yii::app()->createUrl('accounting/invoiceap/indexreqpay',array('grid'=>true)) ."',
							fitColumns:true,
							readonly:true,
							onBeforeLoad: function(param){
								param.companyid = $('#cashbankout-companyid').combogrid('getValue');
							},
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'invoiceapid',title:localStorage.getItem('cataloginvoiceapid'),width:'50px'},
								{field:'invoiceapno',title:localStorage.getItem('cataloginvoiceapno'),width:'150px'},
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
					title:localStorage.getItem('catalognotagrr'),
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'notagrrid',
							textField:'notagrrno',
							url:'".Yii::app()->createUrl('accounting/notagrrid/index',array('grid'=>true)) ."',
							fitColumns:true,
							readonly:true,
							onBeforeLoad: function(param){
								param.companyid = $('#cashbankout-companyid').combogrid('getValue');
							},
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'notagrrid',title:localStorage.getItem('catalognotagrrid'),width:'50px'},
								{field:'notagrrno',title:localStorage.getItem('catalognotagrrno'),width:'150px'},
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
					field:'amount',
					title:localStorage.getItem('catalogvalue'),
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							required:true,
							readonly:true,
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
					field:'chequeid',
					title:localStorage.getItem('catalogchequeno'),
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'chequeid',
							textField:'bilyetgirono',
							url:'".Yii::app()->createUrl('accounting/cheque/index',array('grid'=>true,'trxcom'=>true)) ."',
							fitColumns:true,
							onBeforeLoad: function(param){
								param.companyid = $('#cashbankout-companyid').combogrid('getValue');
							},
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'chequeid',title:localStorage.getItem('catalogchequeid'),width:'50px'},
								{field:'bilyetgirono',title:localStorage.getItem('catalogbilyetgirono'),width:'150px'},
							]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
										return row.bilyetgirono;
					}
				},
				{
					field:'nobuktipotong',
					title:localStorage.getItem('catalognobuktipotong'),
					editor: 'textbox',
					sortable: true,
					width:'150px',
					formatter: function(value,row,index){
								return value;
				}},
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
			'id'=>'cashbankoutacc',
			'idfield'=>'cashbankoutaccid',
			'isnew'=> 1,
			'iswrite'=> 1,
			'ispurge'=> 1,
			'urlsub'=>Yii::app()->createUrl('accounting/cashbankout/indexacc',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/cashbankout/searchacc',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/cashbankout/saveacc',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/cashbankout/saveacc',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/cashbankout/purgeacc',array('grid'=>true)),
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
					field:'cashbankoutid',
					title:localStorage.getItem('catalognotagrr'),
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'cashbankoutjurnalid',
					title:localStorage.getItem('catalogcashbankoutjurnalid'),
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
								 param.companyid = $('#cashbankout-companyid').combogrid('getValue');
							},
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'accountid',title:localStorage.getItem('catalogaccountid'),width:'80px'},
								{field:'accountcode',title:localStorage.getItem('catalogaccountcode'),width:'120px'},
								{field:'accountname',title:localStorage.getItem('catalogaccountname'),width:'250px'},
								{field:'companyname',title:localStorage.getItem('catalogcompany'),width:'350px'},
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
			'id'=>'cashbankoutjurnal',
			'idfield'=>'cashbankoutjurnalid',
			'isnew'=> 0,
			'iswrite'=> 0,
			'ispurge'=> 0,
			'urlsub'=>Yii::app()->createUrl('accounting/cashbankout/indexjurnal',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/cashbankout/searchjurnal',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/cashbankout/savejurnal',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/cashbankout/savejurnal',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/cashbankout/purgejurnal',array('grid'=>true)),
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
					field:'cashbankoutid',
					title:localStorage.getItem('catalogcashbankoutid'),
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'cashbankoutjurnalid',
					title:localStorage.getItem('catalogcashbankoutjurnalid'),
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
								 param.companyid = $('#cashbankout-companyid').combogrid('getValue');
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