<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'expeditionapid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appexpap',
	'url'=>Yii::app()->createUrl('accounting/expeditionap/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('accounting/expeditionap/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/expeditionap/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/expeditionap/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/expeditionap/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('accounting/expeditionap/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('accounting/expeditionap/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/expeditionap/downpdf'),
	'columns'=>"
		{
			field:'expeditionapid',
			title:localStorage.getItem('catalogexpeditionapid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appexpap')."
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
			field:'expeditionapdate',
			title:localStorage.getItem('catalogexpeditionapdate'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'expeditionapno',
			title:localStorage.getItem('catalogexpeditionapno'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'pono',
			title:localStorage.getItem('catalogpono'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return value;
		}},
		{
			field:'fullname',
			title:localStorage.getItem('catalogsupplier'),
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
	'searchfield'=> array ('expeditionapid','expeditionname','expeditionapno','plantcode','pono','supplier','recordstatus'),
	'headerform'=> "
		<input type='hidden' name='expeditionap-grheaderid' id='expeditionap-grheaderid' />
		<table cellpadding='5'>
			<tr>
				<td id='expeditionaptext-expeditionapdate'></td>
				<td><input class='easyui-datebox' type='text' id='expeditionap-expeditionapdate' name='expeditionap-expeditionapdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td id='expeditionaptext-expeditionapno'></td>
				<td><input class='easyui-textbox' id='expeditionap-expeditionapno' name='expeditionap-expeditionapno' data-options='required:true'></input></td>
			</tr>
			<tr>
				<td id='expeditionaptext-plant'></td>
				<td>
					<select class='easyui-combogrid' id='expeditionap-plantid' name='expeditionap-plantid' style='width:250px' data-options=\"
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
				<td id='expeditionaptext-pono'></td>
				<td>
					<select class='easyui-combogrid' id='expeditionap-poheaderid' name='expeditionap-poheaderid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'poheaderid',
						textField: 'pono',
						mode:'remote',
						url: '".Yii::app()->createUrl('purchasing/poheader/index',array('grid'=>true,'expappo'=>true)) ."',
						method: 'get',
						onBeforeLoad: function(param){
							param.plantid = $('#expeditionap-plantid').combogrid('getValue');
						},
						onHidePanel: function(){
							jQuery.ajax({'url':'". Yii::app()->createUrl('accounting/expeditionap/generatesupplier') ."',
									'data':{'poheaderid':$('#expeditionap-poheaderid').combogrid('getValue')
									},
									'type':'post',
									'dataType':'json',
									'success':function(data)
									{
										$('#expeditionap-addressbookid').combogrid('setValue',data.addressbookid);
									},
									'cache':false});
						},
						columns: [[
								{field:'poheaderid',title:localStorage.getItem('catalogpoheaderid'),width:'50px'},
								{field:'pono',title:localStorage.getItem('catalogpono'),width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td id='expeditionaptext-supplier'></td>
				<td>
					<select class='easyui-combogrid' id='expeditionap-addressbookid' name='expeditionap-addressbookid' style='width:250px' data-options=\"
						panelWidth: '500px',
						disabled: true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/supplier/index',array('grid'=>true,'combo'=>true)) ."',
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
				<td id='expeditionaptext-expeditionname'></td>
				<td>
					<select class='easyui-combogrid' id='expeditionap-addressbookexpid' name='expeditionap-addressbookexpid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/supplier/index',array('grid'=>true,'combo'=>true)) ."',
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
				<td id='expeditionaptext-expeditionamount'></td>
				<td><input class='easyui-numberbox' id='expeditionap-amount' name='expeditionap-amount' data-options=\"
				required:true,
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				value:1,
				\"></input></td>
			</tr>
			<tr>
				<td id='expeditionaptext-headernote'></td>
				<td><input class='easyui-textbox' id='expeditionap-headernote' name='expeditionap-headernote' data-options='multiline:true,required:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addonscripts' => "
		$(document).ready(function(){
			var parel = document.getElementById('expeditionaptext-expeditionapdate');
			parel.innerHTML = localStorage.getItem('catalogexpeditionapdate');
			parel = document.getElementById('expeditionaptext-expeditionapno');
			parel.innerHTML = localStorage.getItem('catalogexpeditionapno');
			parel = document.getElementById('expeditionaptext-plant');
			parel.innerHTML = localStorage.getItem('catalogplant');
			parel = document.getElementById('expeditionaptext-pono');
			parel.innerHTML = localStorage.getItem('catalogpono');
			parel = document.getElementById('expeditionaptext-supplier');
			parel.innerHTML = localStorage.getItem('catalogsupplier');
			parel = document.getElementById('expeditionaptext-expeditionname');
			parel.innerHTML = localStorage.getItem('catalogexpeditionname');
			parel = document.getElementById('expeditionaptext-expeditionamount');
			parel.innerHTML = localStorage.getItem('catalogexpeditionamount');
			parel = document.getElementById('expeditionaptext-headernote');
			parel.innerHTML = localStorage.getItem('catalogheadernote');
		});
	",
	'addload'=>"
		$('#expeditionap-expeditionapdate').datebox({value: (new Date().toString('dd-MMM-yyyy'))});
		$('#expeditionap-grheaderid').val('');
	",
	'loadsuccess'=>"
		$('#expeditionap-expeditionapdate').datebox('setValue',data.expeditionapdate);
		$('#expeditionap-expeditionapno').textbox('setValue',data.expeditionapno);
		$('#expeditionap-plantid').combogrid('setValue',data.plantid);
		$('#expeditionap-poheaderid').combogrid('setValue',data.poheaderid);
		$('#expeditionap-addressbookid').combogrid('setValue',data.addressbookid);
		$('#expeditionap-addressbookexpid').combogrid('setValue',data.addressbookexpid);
		$('#expeditionap-amount').numberbox('setValue',data.amount);
		$('#expeditionap-headernote').textbox('setValue',data.headernote);
		$('#expeditionap-grheaderid').val('');
	",
	'columndetails'=> array (
		array(
			'id'=>'expeditionapgr',
			'idfield'=>'expeditionapgrid',
			'urlsub'=>Yii::app()->createUrl('accounting/expeditionap/indexgr',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/expeditionap/searchgr',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/expeditionap/savegr',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/expeditionap/savegr',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/expeditionap/purgegr',array('grid'=>true)),
			'subs'=>"
				{field:'expeditionapgrid',title:localStorage.getItem('catalogexpeditionapgrid'),width:'80px'},
				{field:'grno',title:localStorage.getItem('cataloggrno'),width:'150px'},
				{field:'productcode',title:localStorage.getItem('catalogproductcode'),width:'120px'},
				{field:'productname',title:localStorage.getItem('catalogproductname'),width:'200px'},
				{field:'qty',title:localStorage.getItem('catalogqty'),width:'150px'},
				{field:'uomcode',title:localStorage.getItem('cataloguomcode'),width:'150px'},
				{field:'qty2',title:localStorage.getItem('catalogqty2'),width:'150px'},
				{field:'uom2code',title:localStorage.getItem('cataloguom2code'),width:'150px'},
				{field:'nilaibeban',title:'".GetCatalog('nilaibeban') ."',width:'150px'},
				{field:'headernote',title:'".GetCatalog('headernote') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'expeditionapgrid',
					title:'".GetCatalog('expeditionapgrid') ."',
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'grheaderid',
					title:localStorage.getItem('cataloggrno'),
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'800px',
							mode : 'remote',
							method:'get',
							idField:'grheaderid',
							textField:'grno',
							url:'".Yii::app()->createUrl('inventory/grheader/index',array('grid'=>true,'grretur'=>true))."',
							onBeforeLoad:function(param) {
								param.plantid = $('#expeditionap-plantid').combogrid('getValue');
								param.poheaderid = $('#expeditionap-poheaderid').combogrid('getValue');
								var row = $('#dg-expeditionap-expeditionapgr').edatagrid('getSelected');
								if(row==null){
									$(\"input[name='expeditionap-grheaderid']\").val('0');
								}
							},
							onSelect: function(index,row){
								var grheaderid = row.grheaderid;
								$(\"input[name='expeditionap-grheaderid']\").val(row.grheaderid);
							},
							onChange: function(newValue,oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var grdetailid = $('#dg-expeditionap-expeditionapgr').datagrid('getEditor', {index: index, field:'grdetailid'});
								
								var uomid = $('#dg-expeditionap-expeditionapgr').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-expeditionap-expeditionapgr').datagrid('getEditor', {index: index, field:'uom2id'});
								var qty = $('#dg-expeditionap-expeditionapgr').datagrid('getEditor', {index: index, field:'qty'});
								var qty2 = $('#dg-expeditionap-expeditionapgr').datagrid('getEditor', {index: index, field:'qty2'});
								$(grdetailid.target).combogrid('setValue','');
						
								$(uomid.target).combogrid('setValue','');
								$(uom2id.target).combogrid('setValue','');
								$(qty.target).numberbox('setValue','');
								$(qty2.target).numberbox('setValue','');
							},
							fitColumns:true,
							required:true,
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'grheaderid',title:localStorage.getItem('cataloggrheaderid'),width:'80px'},
								{field:'grno',title:localStorage.getItem('cataloggrno'),width:'350px'},
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
					field:'grdetailid',
					title:localStorage.getItem('catalogproductname'),
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'650px',
							mode: 'remote',
							method:'get',
							idField:'grdetailid',
							textField:'productname',
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'grretur'=>true)) ."',
							onBeforeLoad: function(param){
								var grheaderid = $(\"input[name='expeditionap-grheaderid']\").val();
								if(grheaderid==''){
									var row = $('#dg-expeditionap-expeditionapgr').datagrid('getSelected');
									param.grheaderid = row.grheaderid;
									}else{
									param.grheaderid = $(\"input[name='expeditionap-grheaderid']\").val(); }
							},
							fitColumns:true,
							required:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var grdetailid = $('#dg-expeditionap-expeditionapgr').datagrid('getEditor', {index: index, field:'grdetailid'});
								var uomid = $('#dg-expeditionap-expeditionapgr').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-expeditionap-expeditionapgr').datagrid('getEditor', {index: index, field:'uom2id'});
								var qty = $('#dg-expeditionap-expeditionapgr').datagrid('getEditor', {index: index, field:'qty'});
								var qty2 = $('#dg-expeditionap-expeditionapgr').datagrid('getEditor', {index: index, field:'qty2'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('inventory/grretur/generateproductcode') ."',
									'data':{'grheaderid':$(\"input[name='expeditionap-grheaderid']\").val(),
													'grdetailid':$(grdetailid.target).combogrid('getValue')
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
								{field:'grdetailid',title:localStorage.getItem('cataloggrdetail'),width:'50px'},
								{field:'productid',title:localStorage.getItem('catalogproductid'),width:'50px'},
								{field:'productcode',title:localStorage.getItem('catalogproductcode'),width:'120px'},
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
								var qty2 = $('#dg-expeditionap-expeditionapgr').datagrid('getEditor', {index: index, field:'qty2'});
								var qty2 = $('#dg-expeditionap-expeditionapgr').datagrid('getEditor', {index: index, field:'qty2'});
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
			'id'=>'expeditionapjurnal',
			'idfield'=>'expeditionapjurnalid',
			'isnew'=>0,
			'iswrite'=>0,
			'ispurge'=>0,
			'urlsub'=>Yii::app()->createUrl('accounting/expeditionap/indexjurnal',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/expeditionap/searchjurnal',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/expeditionap/savejurnal',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/expeditionap/savejurnal',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/expeditionap/purgejurnal',array('grid'=>true)),
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
					field:'expeditionapid',
					title:localStorage.getItem('catalogexpeditionapid'),
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'expeditionapjurnalid',
					title:localStorage.getItem('catalogexpeditionapjurnalid'),
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
								 param.companyid = $('#expeditionap-plantid').combogrid('getValue');
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
								 param.companyid = $('#expeditionap-plantid').combogrid('getValue');
							},
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'accountid',title:localStorage.getItem('catalogaccountid'),width:'80px'},
								{field:'companyname',title:localStorage.getItem('catalogcompany'),width:'250px'},
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