<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'invoicearid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appinvar',
	'url'=>Yii::app()->createUrl('accounting/invoicearumum/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('accounting/invoicearumum/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/invoicearumum/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/invoicearumum/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/invoicearumum/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('accounting/invoicearumum/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('accounting/invoicearumum/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/invoicearumum/downpdf'),
	'columns'=>"
		{
			field:'invoicearid',
			title:'".GetCatalog('invoicearid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appinvar')."
		}},
		{
			field:'companyid',
			title:'".GetCatalog('company') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return row.companycode;
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
			field:'invoiceardate',
			title:'".GetCatalog('invoiceardate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'invoicearno',
			title:'".GetCatalog('invoicearno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'fullname',
			title:'".GetCatalog('customer') ."',
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
	'searchfield'=> array ('invoicearid','invoiceardate','customer','plantcode','invoicearno','invoiceartaxno','recordstatus'),
	'headerform'=> "
		<input type='hidden' name='invoicearumum-slocid' id='invoicearumum-slocid' />
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('invoiceardate')."</td>
				<td><input class='easyui-datebox' type='text' id='invoicearumum-invoiceardate' name='invoicearumum-invoiceardate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('invoicearno')."</td>
				<td><input class='easyui-textbox' id='invoicearumum-invoicearno' name='invoicearumum-invoicearno' ></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('plantcode')."</td>
				<td>
					<select class='easyui-combogrid' id='invoicearumum-plantid' name='invoicearumum-plantid' style='width:250px' data-options=\"
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
				<td>".GetCatalog('customer')."</td>
				<td>
					<select class='easyui-combogrid' id='invoicearumum-addressbookid' name='invoicearumum-addressbookid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/customer/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						onHidePanel: function(){
								jQuery.ajax({'url':'". Yii::app()->createUrl('common/customer/generateaddress') ."',
									'data':{'id':$('#invoicearumum-addressbookid').combogrid('getValue'),
											'date':$('#invoicearumum-invoiceardate').val()},
									'type':'post',
									'dataType':'json',
									'success':function(data)
									{
										$('#invoicearumum-taxno').textbox('setValue',data.taxno);
										$('#invoicearumum-addressname').textbox('setValue',data.addressname);
										$('#invoicearumum-paymentmethodid').textbox('setValue',data.paymentmethodid);
										$('#invoicearumum-duedate').textbox('setValue',data.duedate);
									} ,
									'cache':false});
						},
						columns: [[
								{field:'addressbookid',title:'".GetCatalog('addressbookid') ."',width:'50px'},
								{field:'fullname',title:'".GetCatalog('customer') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('contractno')."</td>
				<td><input class='easyui-textbox' id='invoicearumum-contractno' name='invoicearumum-contractno''></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('invoiceartaxno')."</td>
				<td><input class='easyui-textbox' id='invoicearumum-invoiceartaxno' name='invoicearumum-invoiceartaxno' data-options='required:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('taxno')."</td>
				<td><input class='easyui-textbox' id='invoicearumum-taxno' name='invoicearumum-taxno' data-options='required:true,readonly:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('address')."</td>
				<td><input class='easyui-textbox' id='invoicearumum-addressname' name='invoicearumum-addressname' data-options='multiline:true,required:true' style='width:300px;height:100px'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('paymentmethod')."</td>
				<td>
					<select class='easyui-combogrid' id='invoicearumum-paymentmethodid' name='invoicearumum-paymentmethodid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'paymentmethodid',
						textField: 'paycode',
						mode:'remote',
						url: '".Yii::app()->createUrl('accounting/paymentmethod/index',array('grid'=>true,'combo'=>true)) ."',
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
				<td><input class='easyui-textbox' id='invoicearumum-duedate' name='invoicearumum-duedate' data-options='required:true,disabled:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('cb')."</td>
				<td>
					<select class='easyui-combogrid' id='invoicearumum-cashbankid' name='invoicearumum-cashbankid' style='width:250px' data-options=\"
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
				<td><input class='easyui-numberbox' id='invoicearumum-dpamount' name='invoicearumum-dpamount'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='invoicearumum-headernote' name='invoicearumum-headernote' data-options='multiline:true,required:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addload'=>"
		$('#invoicearumum-invoiceardate').datebox({value: (new Date().toString('dd-MMM-yyyy'))});
		$('#invoicearumum-duedate').datebox({value: (new Date().toString('dd-MMM-yyyy'))});
		$('#invoicearumum-slocid').val('');	
	",
	'loadsuccess'=>"
		$('#invoicearumum-invoiceardate').datebox('setValue',data.invoiceardate);
		$('#invoicearumum-invoicearno').textbox('setValue',data.invoicearno);
		$('#invoicearumum-plantid').combogrid('setValue',data.plantid);
		$('#invoicearumum-addressbookid').combogrid('setValue',data.addressbookid);
		$('#invoicearumum-contractno').textbox('setValue',data.contractno);
		$('#invoicearumum-invoiceartaxno').textbox('setValue',data.invoiceartaxno);
		$('#invoicearumum-taxno').textbox('setValue',data.taxno);
		$('#invoicearumum-addressname').textbox('setValue',data.addressname);
		$('#invoicearumum-paymentmethodid').combogrid('setValue',data.paymentmethodid);
		$('#invoicearumum-duedate').textbox('setValue',data.duedate);
		$('#invoicearumum-cashbankid').textbox('setValue',data.cashbankid);
		$('#invoicearumum-dpamount').numberbox('setValue',data.dpamount);
		$('#invoicearumum-headernote').textbox('setValue',data.headernote);
		$('#invoicearumum-slocid').val('');
	",
	'columndetails'=> array (
		array(
			'id'=>'invoicearumumtax',
			'idfield'=>'invoiceartaxid',
			'urlsub'=>Yii::app()->createUrl('accounting/invoicearumum/indextax',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/invoicearumum/searchtax',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/invoicearumum/savetax',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/invoicearumum/savetax',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/invoicearumum/purgetax',array('grid'=>true)),
			'subs'=>"
				{field:'invoiceartaxid',title:'".GetCatalog('invoiceartaxid') ."',align:'right',width:'60px'},
				{field:'taxcode',title:'".GetCatalog('taxcode') ."',width:'200px'},
			",
			'columns'=>"
				{
					field:'invoiceartaxid',
					title:'".GetCatalog('invoiceartaxid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'invoicearid',
					title:'".GetCatalog('invoicearid') ."',
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
			'id'=>'invoicearumumdetail',
			'idfield'=>'invoiceardetailid',
			'urlsub'=>Yii::app()->createUrl('accounting/invoicearumum/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/invoicearumum/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/invoicearumum/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/invoicearumum/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/invoicearumum/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'invoiceardetailid',title:'".GetCatalog('invoiceardetailid') ."',align:'right',width:'60px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'250px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},
				{field:'uom2code',title:'".GetCatalog('uom2code') ."',width:'80px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},
				{field:'uom3code',title:'".GetCatalog('uom3code') ."',width:'80px'},
				{field:'qty4',title:'".GetCatalog('qty4') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},
				{field:'uom4code',title:'".GetCatalog('uom4code') ."',width:'80px'},
				{field:'price',title:'".GetCatalog('price') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},width:'120px'},
				{field:'discount',title:'".GetCatalog('discount') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},width:'120px'},
				{field:'dpp',title:'".GetCatalog('dpp') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},width:'120px'},
				{field:'total',title:'".GetCatalog('total') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},width:'120px'},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'60px'},
			",
			'columns'=>"
				{
					field:'invoicearid',
					title:'".GetCatalog('invoicearid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'invoiceardetailid',
					title:'".GetCatalog('invoiceardetailid') ."',
					sortable: true,
					hidden:true,
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
								param.plantid = $('#invoicearumum-plantid').combogrid('getValue');
								var row = $('#dg-invoicearumum-invoicearumumdetail').edatagrid('getSelected');
								if(row==null){
									$(\"input[name='invoicearumum-slocid']\").val('0');
								}
							},
							onSelect: function(index,row){
								var slocid = row.slocid;
								$(\"input[name='invoicearumum-slocid']\").val(row.slocid);
							},
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var slocid = $('#dg-invoicearumum-invoicearumumdetail').datagrid('getEditor', {index: index, field:'slocid'});
								var productid = $('#dg-invoicearumum-invoicearumumdetail').datagrid('getEditor', {index: index, field:'productid'});
							
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
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'productplantjasa'=>true)) ."',
							onShowPanel: function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-invoicearumum-invoicearumumdetail').datagrid('getEditor', {index: index, field:'productid'});
								var slocid = $('#dg-invoicearumum-invoicearumumdetail').datagrid('getEditor', {index: index, field:'slocid'});
								$(productid.target).combogrid('grid').datagrid('load',{
									slocid:$(slocid.target).combogrid('getValue')
								});
							},
							fitColumns:true,
							required:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-invoicearumum-invoicearumumdetail').datagrid('getEditor', {index: index, field:'productid'});
								var uomid = $('#dg-invoicearumum-invoicearumumdetail').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-invoicearumum-invoicearumumdetail').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-invoicearumum-invoicearumumdetail').datagrid('getEditor', {index: index, field:'uom3id'});
								var uom4id = $('#dg-invoicearumum-invoicearumumdetail').datagrid('getEditor', {index: index, field:'uom4id'});
								var stdqty2 = $('#dg-invoicearumum-invoicearumumdetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-invoicearumum-invoicearumumdetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-invoicearumum-invoicearumumdetail').datagrid('getEditor', {index: index, field:'stdqty4'});
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
								var stdqty2 = $('#dg-invoicearumum-invoicearumumdetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-invoicearumum-invoicearumumdetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-invoicearumum-invoicearumumdetail').datagrid('getEditor', {index: index, field:'stdqty4'});								
								var qty2 = $('#dg-invoicearumum-invoicearumumdetail').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-invoicearumum-invoicearumumdetail').datagrid('getEditor', {index: index, field:'qty3'});								
								var qty4 = $('#dg-invoicearumum-invoicearumumdetail').datagrid('getEditor', {index: index, field:'qty4'});						
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
					field:'total',
					title:'".GetCatalog('total') ."',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
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
			'id'=>'invoicearumumjurnal',
			'idfield'=>'invoicearjurnalid',
			'isnew'=>0,
			'iswrite'=>0,
			'ispurge'=>0,
			'urlsub'=>Yii::app()->createUrl('accounting/invoicearumum/indexjurnal',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/invoicearumum/searchjurnal',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/invoicearumum/savejurnal',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/invoicearumum/savejurnal',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/invoicearumum/purgejurnal',array('grid'=>true)),
			'subs'=>"
				{field:'accountname',title:'".GetCatalog('accountname') ."',width:'250px'},
				{field:'debit',title:'".GetCatalog('debit') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},width:'120px'},
				{field:'credit',title:'".GetCatalog('credit') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},width:'120px'},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'60px'},
				{field:'detailnote',title:'".GetCatalog('detailnote') ."',width:'350px'},
			",
			'columns'=>"
				{
					field:'invoicearid',
					title:'".GetCatalog('invoicearid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'invoicearjurnalid',
					title:'".GetCatalog('invoicearjurnalid') ."',
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
							url:'".$this->createUrl('account/index',array('grid'=>true,'trxplant'=>true)) ."',
							fitColumns:true,
							required:true,
							onBeforeLoad: function(param) {
								 param.plantid = $('#invoicearumum-plantid').combogrid('getValue');
							},
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