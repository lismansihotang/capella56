<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'cashbankid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appcb',
	'url'=>Yii::app()->createUrl('accounting/cashbank/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('accounting/cashbank/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/cashbank/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/cashbank/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/cashbank/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('accounting/cashbank/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('accounting/cashbank/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/cashbank/downpdf'),
	'columns'=>"
		{
			field:'cashbankid',
			title:localStorage.getItem('catalogcashbankid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appcb')."
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
			field:'cashbankdate',
			title:localStorage.getItem('catalogcashbankdate'),
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
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
			field:'cashbankno',
			title:localStorage.getItem('catalogcashbankno'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'isin',
			title:localStorage.getItem('catalogisin'),
			align:'center',
			width:'90px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}		
		}},
		{
			field:'addressbookid',
			title:localStorage.getItem('catalogaddressbook'),
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'chequeid',
			title:localStorage.getItem('catalogbilyetgirono'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return row.bilyetgirono;
		}},
		{
			field:'amount',
			title:localStorage.getItem('catalogamount'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
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
	'searchfield'=> array ('cashbankid','cashbankdate','addressbook','companycode','cashbankno','accountname','headernote','recordstatus'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td id='cashbanktext-cashbankno'></td>
				<td><input class='easyui-textbox' id='cashbank-cashbankno' name='cashbank-cashbankno' data-options='required:true,disabled:true'></input></td>
				<td id='cashbanktext-cashbankdate'></td>
				<td><input class='easyui-datebox' type='text' id='cashbank-cashbankdate' name='cashbank-cashbankdate' data-options='readonly:false,formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td id='cashbanktext-company'></td>
				<td>
					<select class='easyui-combogrid' id='cashbank-companyid' name='cashbank-companyid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'companyid',
						textField: 'companycode',
						mode:'remote',
						url: '".Yii::app()->createUrl('admin/company/index',array('grid'=>true,'auth'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'companyid',title:localStorage.getItem('catalogcompanyid'),width:'50px'},
								{field:'companycode',title:localStorage.getItem('catalogcompanycode'),width:'200px'},
								{field:'companyname',title:localStorage.getItem('catalogcompanyname'),width:'300px'},
						]],
						fitColumns: true\">
					</select>
				</td>
				<td id='cashbanktext-isin'></td>
				<td><input class='easyui-checkbox' name='cashbank-isin' id='cashbank-isin'></input></td>
			</tr>
			<tr>
				<td id='cashbanktext-addressbook'></td>
				<td>
					<select class='easyui-combogrid' id='cashbank-addressbookid' name='cashbank-addressbookid' style='width:250px' data-options=\"
						panelWidth: '700px',
						required: true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/addressbook/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'addressbookid',title:localStorage.getItem('catalogaddressbookid'),width:'50px'},
								{field:'fullname',title:localStorage.getItem('catalogaddressbook'),width:'250px'},
								{field:'iscustomer',title:localStorage.getItem('catalogiscustomer'),width:'100px'},
								{field:'isvendor',title:localStorage.getItem('catalogisvendor'),width:'100px'},
								{field:'isemployee',title:localStorage.getItem('catalogisemployee'),width:'100px'},
						]],
						fitColumns: true\">
					</select>
				</td>
				<td id='cashbanktext-accountname'></td>
				<td>
					<select class='easyui-combogrid' id='cashbank-accountid' name='cashbank-accountid' style='width:250px' data-options=\"
						panelWidth: '700px',
						required: true,
						idField: 'accountid',
						textField: 'accountname',
						mode:'remote',
						url: '".Yii::app()->createUrl('accounting/account/index',array('grid'=>true,'trxcom'=>true)) ."',
						method: 'get',
						onShowPanel:function() {
							$('#cashbank-accountid').combogrid('grid').datagrid('reload');
						},
						onBeforeLoad: function(param) {
							 param.companyid = $('#cashbank-companyid').combogrid('getValue');
						},
						columns: [[
								{field:'accountid',title:localStorage.getItem('catalogaccountid'),width:'80px'},
								{field:'accountcode',title:localStorage.getItem('catalogaccountcode'),width:'120px'},
								{field:'accountname',title:localStorage.getItem('catalogaccountname'),width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td id='cashbanktext-receiptno'></td>
				<td><input class='easyui-textbox' id='cashbank-receiptno' name='cashbank-receiptno'></input></td>
				<td id='cashbanktext-bilyetgirono'></td>
				<td>
					<select class='easyui-combogrid' id='cashbank-chequeid' name='cashbank-chequeid' style='width:250px' data-options=\"
						panelWidth: '500px',
						idField: 'chequeid',
						textField: 'bilyetgirono',
						mode:'remote',
						url: '".Yii::app()->createUrl('accounting/cheque/index',array('grid'=>true,'trxcom'=>true)) ."',
						method: 'get',
						onBeforeLoad: function(param) {
							 param.companyid = $('#cashbank-companyid').combogrid('getValue');
						},
						columns: [[
								{field:'chequeid',title:localStorage.getItem('catalogchequeid'),width:'80px'},
								{field:'bilyetgirono',title:localStorage.getItem('catalogbilyetgirono'),width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td id='cashbanktext-amount'></td>
					<td><input class='easyui-numberbox' id='cashbank-amount' name='cashbank-amount' data-options=\"
					required:true,
					precision:4,
					decimalSeparator:',',
					groupSeparator:'.',
					value:1,
					\"></input>
				</td>				
				<td id='cashbanktext-currencyname'></td>
				<td>
					<select class='easyui-combogrid' id='cashbank-currencyid' name='cashbank-currencyid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'currencyid',
						textField: 'currencyname',
						mode:'remote',
						url: '".Yii::app()->createUrl('admin/currency/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'currencyid',title:localStorage.getItem('catalogcurrencyid'),width:'50px'},
								{field:'currencyname',title:localStorage.getItem('catalogcurrencyname'),width:'150px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
				<td id='cashbanktext-ratevalue'></td>
				<td><input class='easyui-numberbox' id='cashbank-ratevalue' name='cashbank-ratevalue' data-options=\"
				required:true,
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				value:1,
				\"></input></td>
				<td id = 'cashbanktext-headernote'></td>
				<td><input class='easyui-textbox' id='cashbank-headernote' name='cashbank-headernote' data-options='multiline:true,required:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addonscripts' => "
		$(document).ready(function(){
			var parel = document.getElementById('cashbanktext-cashbankno');
			parel.innerHTML = localStorage.getItem('catalogcashbankno');
			parel = document.getElementById('cashbanktext-company');
			parel.innerHTML = localStorage.getItem('catalogcompany');
			parel = document.getElementById('cashbanktext-isin');
			parel.innerHTML = localStorage.getItem('catalogisin');
			parel = document.getElementById('cashbanktext-addressbook');
			parel.innerHTML = localStorage.getItem('catalogaddressbook');
			parel = document.getElementById('cashbanktext-accountname');
			parel.innerHTML = localStorage.getItem('catalogaccountname');
			parel = document.getElementById('cashbanktext-receiptno');
			parel.innerHTML = localStorage.getItem('catalogreceiptno');
			parel = document.getElementById('cashbanktext-bilyetgirono');
			parel.innerHTML = localStorage.getItem('catalogbilyetgirono');
			parel = document.getElementById('cashbanktext-amount');
			parel.innerHTML = localStorage.getItem('catalogamount');
			parel = document.getElementById('cashbanktext-currencyname');
			parel.innerHTML = localStorage.getItem('catalogcurrencyname');
			parel = document.getElementById('cashbanktext-ratevalue');
			parel.innerHTML = localStorage.getItem('catalogratevalue');
			parel = document.getElementById('cashbanktext-headernote');
			parel.innerHTML = localStorage.getItem('catalogheadernote');
		});
	",
	'addload'=>"
		$('#cashbank-cashbankdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});	",
	'loadsuccess'=>"
		$('#cashbank-cashbankdate').datebox('setValue',data.cashbankdate);
		$('#cashbank-cashbankno').textbox('setValue',data.cashbankno);
		$('#cashbank-plantid').combogrid('setValue',data.plantid);
		$('#cashbank-addressbookid').combogrid('setValue',data.addressbookid);
		$('#cashbank-accountid').combogrid('setValue',data.accountid);
		$('#cashbank-receiptno').textbox('setValue',data.receiptno);
		$('#cashbank-chequeid').combogrid('setValue',data.chequeid);
		$('#cashbank-currencyid').combogrid('setValue',data.currencyid);
		$('#cashbank-ratevalue').numberbox('setValue',data.ratevalue);
		$('#cashbank-headernote').textbox('setValue',data.headernote);
		if (data.isin == 1)
		{
			$(\"input[name='cashbank-isin']\").prop('checked', true);
		} else
		{
			$(\"input[name='cashbank-isin']\").prop('checked', false);
		}
	",
	'columndetails'=> array (
		array(
			'id'=>'tax',
			'idfield'=>'cashbanktaxid',
			'urlsub'=>Yii::app()->createUrl('accounting/cashbank/indextax',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/cashbank/searchtax',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/cashbank/savetax',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/cashbank/savetax',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/cashbank/purgetax',array('grid'=>true)),
			'subs'=>"
				{field:'cashbanktaxid',title:localStorage.getItem('catalogcashbanktaxid'),align:'right',width:'60px'},
				{field:'taxcode',title:localStorage.getItem('catalogtaxcode'),width:'250px'},
				{field:'nobuktipotong',title:localStorage.getItem('catalognobuktipotong'),width:'200px'},
			",
			'columns'=>"
				{
					field:'cashbanktaxid',
					title:localStorage.getItem('catalogcashbanktaxid'),
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'cashbankid',
					title:localStorage.getItem('catalogcashbankid'),
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'taxid',
					title:localStorage.getItem('catalogtax'),
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
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'taxid',title:localStorage.getItem('catalogtaxid'),width:'80px'},
								{field:'taxcode',title:localStorage.getItem('catalogtaxcode'),width:'100px'},
								{field:'taxvalue',title:localStorage.getItem('catalogtaxvalue'),width:'120px'},
							]]
						}	
					},
					width:'300px',
					sortable: true,
					formatter: function(value,row,index){
						return row.taxcode;
					}
				},
				{
					field:'nobuktipotong',
					title:localStorage.getItem('catalognobuktipotong'),
					editor: {
						type: 'textbox',
						options: {
							required:true
						}
					},
					sortable: true,
					width:'300px',
					formatter: function(value,row,index){
										return value;
					}
				},
			",
		),
		array(
			'id'=>'detail',
			'idfield'=>'cashbankdetailid',
			'urlsub'=>Yii::app()->createUrl('accounting/cashbank/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/cashbank/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/cashbank/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/cashbank/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/cashbank/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'cashbankdetailid',title:localStorage.getItem('catalogcashbankdetailid'),align:'right',width:'60px'},
				{field:'sloccode',title:localStorage.getItem('catalogsloccode'),width:'250px'},
				{field:'accountname',title:localStorage.getItem('catalogaccountname'),width:'250px'},
				{field:'amount',title:localStorage.getItem('catalogamount'),width:'200px',
					formatter: function(value,row,index){
						return formatnumber(row.symbol, row.amount);
					},
				},
				{field:'currencyname',title:localStorage.getItem('catalogcurrencyname'),width:'150px'},
				{field:'ratevalue',title:localStorage.getItem('catalogratevalue'),align:'right',width:'60px'},
			",
			'columns'=>"
				{
					field:'cashbankid',
					title:localStorage.getItem('catalogcashbankid'),
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'cashbankdetailid',
					title:localStorage.getItem('catalogcashbankdetailid'),
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'slocid',
					title:localStorage.getItem('catalogsloccode'),
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'slocid',
							textField:'sloccode',
							url:'".Yii::app()->createUrl('common/sloc/Indextrxcom',array('grid'=>true)) ."',
							onBeforeLoad: function(param){
								param.companyid = $('#cashbank-companyid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'slocid',title:localStorage.getItem('catalogslocid'),width:'50px'},
								{field:'sloccode',title:localStorage.getItem('catalogsloccode'),width:'150px'},
								{field:'description',title:localStorage.getItem('catalogdescription'),width:'250px'},
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
								 param.companyid = $('#cashbank-companyid').combogrid('getValue');
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