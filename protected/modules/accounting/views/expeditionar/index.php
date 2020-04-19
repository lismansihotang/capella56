<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'expeditionarid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appexpar',
	'url'=>Yii::app()->createUrl('accounting/expeditionar/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('accounting/expeditionar/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/expeditionar/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/expeditionar/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/expeditionar/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('accounting/expeditionar/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('accounting/expeditionar/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/expeditionar/downpdf'),
	'columns'=>"
		{
			field:'expeditionarid',
			title:localStorage.getItem('catalogexpeditionarid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appexpar')."
		}},
		{
			field:'plantid',
			title:localStorage.getItem('catalogplant'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.plantcode;
		}},
		{
			field:'expeditionardate',
			title:localStorage.getItem('catalogexpeditionardate'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'expeditionarno',
			title:localStorage.getItem('catalogexpeditionarno'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'sono',
			title:localStorage.getItem('catalogsono'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return value;
		}},
		{
			field:'fullname',
			title:localStorage.getItem('catalogcustomer'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return value;
		}},
		{
			field:'expeditionname',
			title:localStorage.getItem('catalogexpeditionname'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return value;
		}},
		{
			field:'amount',
			title:localStorage.getItem('catalogexpeditionamount'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return value;
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
	'searchfield'=> array ('expeditionarid','expeditionname','expeditionarno','plantcode','sono','customer','recordstatus'),
	'headerform'=> "
		<input type='hidden' name='expeditionar-giheaderid' id='expeditionar-giheaderid' />
		<table cellpadding='5'>
			<tr>
				<td id='expeditionartext-expeditionardate'></td>
				<td><input class='easyui-datebox' type='text' id='expeditionar-expeditionardate' name='expeditionar-expeditionardate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td id='expeditionartext-expeditionarno'></td>
				<td><input class='easyui-textbox' id='expeditionar-expeditionarno' name='expeditionar-expeditionarno' data-options='required:true'></input></td>
			</tr>
			<tr>
				<td id ='expeditionartext-plant'></td>
				<td>
					<select class='easyui-combogrid' id='expeditionar-plantid' name='expeditionar-plantid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'plantid',
						textField: 'plantcode',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'auth'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'plantid',title:localStorage.getItem('catalogplantid'),width:'50px'},
								{field:'plantcode',title:localStorage.getItem('catalogplantcode'),width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td id='expeditionartext-sono'></td>
				<td>
					<select class='easyui-combogrid' id='expeditionar-soheaderid' name='expeditionar-soheaderid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'soheaderid',
						textField: 'sono',
						mode:'remote',
						url: '".Yii::app()->createUrl('order/soheader/index',array('grid'=>true,'authso'=>true)) ."',
						method: 'get',
						onBeforeLoad: function(param){
							param.plantid = $('#expeditionar-plantid').combogrid('getValue');
						},
						onHidePanel: function(){
							jQuery.ajax({'url':'". Yii::app()->createUrl('accounting/expeditionar/generatecustomer') ."',
									'data':{'soheaderid':$('#expeditionar-soheaderid').combogrid('getValue')
									},
									'type':'post',
									'dataType':'json',
									'success':function(data)
									{
										$('#expeditionar-addressbookid').combogrid('setValue',data.addressbookid);
									},
									'cache':false});
						},
						columns: [[
								{field:'soheaderid',title:localStorage.getItem('catalogsoheaderid'),width:'50px'},
								{field:'sono',title:localStorage.getItem('catalogsono'),width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td id='expeditionartext-customer'></td>
				<td>
					<select class='easyui-combogrid' id='expeditionar-addressbookid' name='expeditionar-addressbookid' style='width:250px' data-options=\"
						panelWidth: '500px',
						disabled: true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/customer/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'addressbookid',title:localStorage.getItem('catalogaddressbookid'),width:'50px'},
								{field:'fullname',title:localStorage.getItem('catalogcustomer'),width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td id='expeditionartext-expeditionname'></td>
				<td>
					<select class='easyui-combogrid' id='expeditionar-addressbookexpid' name='expeditionar-addressbookexpid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/supplier/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'addressbookid',title:localStorage.getItem('catalogaddressbookid'),width:'50px'},
								{field:'fullname',title:localStorage.getItem('catalogsupplier'),width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td id='expeditionartext-expeditionamount'></td>
				<td><input class='easyui-numberbox' id='expeditionar-amount' name='expeditionar-amount' data-options=\"
				required:true,
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				value:1,
				\"></input></td>
			</tr>
			<tr>
				<td id='expeditionartext-headernote'></td>
				<td><input class='easyui-textbox' id='expeditionar-headernote' name='expeditionar-headernote' data-options='multiline:true,required:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addonscripts' => "
		$(document).ready(function(){
			var parel = document.getElementById('expeditionartext-expeditionardate');
			parel.innerHTML = localStorage.getItem('catalogexpeditionardate');
			parel = document.getElementById('expeditionartext-expeditionarno');
			parel.innerHTML = localStorage.getItem('catalogexpeditionarno');
			parel = document.getElementById('expeditionartext-plant');
			parel.innerHTML = localStorage.getItem('catalogplant');
			parel = document.getElementById('expeditionartext-sono');
			parel.innerHTML = localStorage.getItem('catalogsono');
			parel = document.getElementById('expeditionartext-customer');
			parel.innerHTML = localStorage.getItem('catalogcustomer');
			parel = document.getElementById('expeditionartext-expeditionname');
			parel.innerHTML = localStorage.getItem('catalogexpeditionname');
			parel = document.getElementById('expeditionartext-headernote');
			parel.innerHTML = localStorage.getItem('catalogheadernote');
		});
	",
	'addload'=>"
		$('#expeditionar-expeditionardate').datebox({value: (new Date().toString('dd-MMM-yyyy'))});
		$('#expeditionar-giheaderid').val('');
	",
	'loadsuccess'=>"
		$('#expeditionar-expeditionardate').datebox('setValue',data.expeditionardate);
		$('#expeditionar-expeditionarno').textbox('setValue',data.expeditionarno);
		$('#expeditionar-plantid').combogrid('setValue',data.plantid);
		$('#expeditionar-soheaderid').combogrid('setValue',data.soheaderid);
		$('#expeditionar-addressbookid').combogrid('setValue',data.addressbookid);
		$('#expeditionar-addressbookexpid').combogrid('setValue',data.addressbookexpid);
		$('#expeditionar-amount').numberbox('setValue',data.amount);
		$('#expeditionar-headernote').textbox('setValue',data.headernote);
		$('#expeditionar-giheaderid').val('');
	",
	'columndetails'=> array (
		array(
			'id'=>'expeditionargi',
			'idfield'=>'expeditionargiid',
			'urlsub'=>Yii::app()->createUrl('accounting/expeditionar/indexgr',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/expeditionar/searchgr',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/expeditionar/savegr',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/expeditionar/savegr',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/expeditionar/purgegr',array('grid'=>true)),
			'subs'=>"
				{field:'expeditionargiid',title:localStorage.getItem('catalogexpeditionargiid'),width:'80px'},
				{field:'gino',title:localStorage.getItem('cataloggino'),width:'150px'},
				{field:'productcode',title:localStorage.getItem('catalogproductcode'),width:'120px'},
				{field:'productname',title:localStorage.getItem('catalogproductname'),width:'250px'},
				{field:'qty',title:localStorage.getItem('catalogqty'),width:'150px'},
				{field:'uomcode',title:localStorage.getItem('cataloguomcode'),width:'150px'},
				{field:'qty2',title:localStorage.getItem('catalogqty2'),width:'150px'},
				{field:'uom2code',title:localStorage.getItem('cataloguom2code'),width:'150px'},
				{field:'nilaibeban',title:localStorage.getItem('catalognilaibeban'),width:'150px'},
				{field:'headernote',title:localStorage.getItem('catalogheadernote'),width:'300px'},
			",
			'columns'=>"
				{
					field:'expeditionargiid',
					title:localStorage.getItem('catalogexpeditionargiid'),
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'giheaderid',
					title:localStorage.getItem('cataloggino'),
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'800px',
							mode : 'remote',
							method:'get',
							idField:'giheaderid',
							textField:'gino',
							url:'".Yii::app()->createUrl('inventory/giheader/index',array('grid'=>true,'grretur'=>true))."',
							onBeforeLoad:function(param) {
								param.plantid = $('#expeditionar-plantid').combogrid('getValue');
								param.soheaderid = $('#expeditionar-soheaderid').combogrid('getValue');
								var row = $('#dg-expeditionar-expeditionargi').edatagrid('getSelected');
								if(row==null){
									$(\"input[name='expeditionar-giheaderid']\").val('0');
								}
							},
							onSelect: function(index,row){
								var giheaderid = row.giheaderid;
								$(\"input[name='expeditionar-giheaderid']\").val(row.giheaderid);
							},
							onChange: function(newValue,oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var gidetailid = $('#dg-expeditionar-expeditionargi').datagrid('getEditor', {index: index, field:'gidetailid'});
								
								var uomid = $('#dg-expeditionar-expeditionargi').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-expeditionar-expeditionargi').datagrid('getEditor', {index: index, field:'uom2id'});
								var qty = $('#dg-expeditionar-expeditionargi').datagrid('getEditor', {index: index, field:'qty'});
								var qty2 = $('#dg-expeditionar-expeditionargi').datagrid('getEditor', {index: index, field:'qty2'});
								$(gidetailid.target).combogrid('setValue','');
						
								$(uomid.target).combogrid('setValue','');
								$(uom2id.target).combogrid('setValue','');
								$(qty.target).numberbox('setValue','');
								$(qty2.target).numberbox('setValue','');
							},
							fitColumns:true,
							required:true,
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'giheaderid',title:localStorage.getItem('cataloggiheaderid'),width:'80px'},
								{field:'gino',title:localStorage.getItem('cataloggino'),width:'350px'},
							]]
						}	
					},
					width:'300px',
					sortable: true,
					formatter: function(value,row,index){
						return row.gino;
					}
				},
				
				{
					field:'gidetailid',
					title:localStorage.getItem('catalogproductname'),
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'650px',
							mode: 'remote',
							method:'get',
							idField:'gidetailid',
							textField:'productname',
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'giretur'=>true)) ."',
							onBeforeLoad: function(param){
								var giheaderid = $(\"input[name='expeditionar-giheaderid']\").val();
								if(giheaderid==''){
									var row = $('#dg-expeditionar-expeditionargi').datagrid('getSelected');
									param.giheaderid = row.giheaderid;
									}else{
									param.giheaderid = $(\"input[name='expeditionar-giheaderid']\").val(); }
							},
							fitColumns:true,
							required:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var gidetailid = $('#dg-expeditionar-expeditionargi').datagrid('getEditor', {index: index, field:'gidetailid'});
								var uomid = $('#dg-expeditionar-expeditionargi').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-expeditionar-expeditionargi').datagrid('getEditor', {index: index, field:'uom2id'});
								var qty = $('#dg-expeditionar-expeditionargi').datagrid('getEditor', {index: index, field:'qty'});
								var qty2 = $('#dg-expeditionar-expeditionargi').datagrid('getEditor', {index: index, field:'qty2'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('inventory/giretur/generateproductcode') ."',
									'data':{'giheaderid':$(\"input[name='expeditionar-giheaderid']\").val(),
													'gidetailid':$(gidetailid.target).combogrid('getValue')
									},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(uomid.target).combogrid('setValue',data.uomid);
										$(uom2id.target).combogrid('setValue',data.uom2id);
										$(qty.target).numberbox('setValue',data.qty);
										$(qty2.target).numberbox('setValue',data.qty2);
									} ,
									'cache':false});
							},
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'gidetailid',title:localStorage.getItem('cataloggidetail'),width:'50px'},
								{field:'productcode',title:localStorage.getItem('catalogproductcode'),width:'50px'},
								{field:'productname',title:localStorage.getItem('catalogproductname'),width:'450px'},
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
					title:localStorage.getItem('catalogqty'),
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
								var qty2 = $('#dg-expeditionar-expeditionargi').datagrid('getEditor', {index: index, field:'qty2'});
								var qty2 = $('#dg-expeditionar-expeditionargi').datagrid('getEditor', {index: index, field:'qty2'});
								$(qty2.target).numberbox('setValue',$(qty2.target).numberbox('getValue') * (newValue/oldValue));
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
					title:localStorage.getItem('cataloguomcode'),
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
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'unitofmeasureid',title:localStorage.getItem('catalogunitofmeasureid'),width:'50px'},
								{field:'uomcode',title:localStorage.getItem('cataloguomcode'),width:'150px'},
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
					title:localStorage.getItem('catalogqty2'),
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
					title:localStorage.getItem('cataloguom2code'),
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
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'unitofmeasureid',title:localStorage.getItem('catalogunitofmeasureid'),width:'50px'},
								{field:'uomcode',title:localStorage.getItem('cataloguomcode'),width:'150px'},
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
					field:'nilaibeban',
					title:localStorage.getItem('catalognilaibeban'),
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
				}
			"
		),
		array(
			'id'=>'expeditionarjurnal',
			'idfield'=>'expeditionarjurnalid',
			'isnew'=>0,
			'iswrite'=>0,
			'ispurge'=>0,
			'urlsub'=>Yii::app()->createUrl('accounting/expeditionar/indexjurnal',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/expeditionar/searchjurnal',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/expeditionar/savejurnal',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/expeditionar/savejurnal',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/expeditionar/purgejurnal',array('grid'=>true)),
			'subs'=>"
				{field:'cashbankno',title:localStorage.getItem('catalogcashbankno'),width:'120px'},
				{field:'accountname',title:localStorage.getItem('catalogaccountname'),width:'250px'},
				{field:'amount',title:localStorage.getItem('catalogamount'),
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.amount);
					},width:'120px'},
				{field:'currencyname',title:localStorage.getItem('catalogcurrencyname'),width:'100px'},
				{field:'ratevalue',title:localStorage.getItem('catalogratevalue'),align:'right',width:'60px'},
				{field:'detailnote',title:localStorage.getItem('catalogdetailnote'),width:'350px'},
			",
			'columns'=>"
				{
					field:'expeditionarid',
					title:localStorage.getItem('catalogexpeditionarid'),
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'expeditionarjurnalid',
					title:localStorage.getItem('catalogexpeditionarjurnalid'),
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'cashbankid',
					title:localStorage.getItem('catalogcashbankno'),
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'800px',
							mode : 'remote',
							method:'get',
							idField:'cashbankid',
							textField:'cashbankno',
							url:'".$this->createUrl('cashbank/index',array('grid'=>true,'trxcom'=>true)) ."',
							fitColumns:true,
							required:true,
							onBeforeLoad: function(param) {
								 param.companyid = $('#expeditionar-plantid').combogrid('getValue');
							},
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'cashbankid',title:localStorage.getItem('catalogcashbankid'),width:'80px'},
								{field:'cashbankno',title:localStorage.getItem('catalogcashbankno'),width:'350px'},
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
								 param.companyid = $('#expeditionar-plantid').combogrid('getValue');
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
					field:'amount',
					title:localStorage.getItem('catalogamount'),
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