<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'invoicearid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appinvar',
	'url'=>Yii::app()->createUrl('accounting/invoicear/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('accounting/invoicear/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/invoicear/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/invoicear/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/invoicear/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('accounting/invoicear/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('accounting/invoicear/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/invoicear/downpdf'),
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
			field:'pocustno',
			title:'".GetCatalog('pocustno') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return value;
		}},		
		{
			field:'fullname',
			title:'".GetCatalog('customer') ."',
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
						return value;
		}},
		{
			field:'soheaderid',
			title:'".GetCatalog('sono') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return row.sono;
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
	'searchfield'=> array ('invoicearid','invoiceardate','customer','plantcode','invoicearno','sono','pocustno','invoiceartaxno','recordstatus'),
	'headerform'=> "
		<input type='hidden' name='invoicear-slocid' id='invoicear-slocid' />
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('invoiceardate')."</td>
				<td><input class='easyui-datebox' type='text' id='invoicear-invoiceardate' name='invoicear-invoiceardate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
				<td>".GetCatalog('invoicearno')."</td>
				<td><input class='easyui-textbox' id='invoicear-invoicearno' name='invoicear-invoicearno'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('plantcode')."</td>
				<td>
					<select class='easyui-combogrid' id='invoicear-plantid' name='invoicear-plantid' style='width:250px' data-options=\"
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
				<td>".GetCatalog('customer')."</td>
				<td>
					<select class='easyui-combogrid' id='invoicear-addressbookid' name='invoicear-addressbookid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/customer/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						onHidePanel: function(){
							jQuery.ajax({'url':'". Yii::app()->createUrl('common/customer/generateaddress') ."',
								'data':{'id':$('#invoicear-addressbookid').combogrid('getValue'),
										'date':$('#invoicear-invoiceardate').val()},
								'type':'post',
								'dataType':'json',
								'success':function(data)
								{
									$('#invoicear-taxno').textbox('setValue',data.taxno);
									$('#invoicear-addressname').textbox('setValue',data.addressname);
									$('#invoicear-paymentmethodid').textbox('setValue',data.paymentmethodid);
									$('#invoicear-duedate').textbox('setValue',data.duedate);
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
				<td>".GetCatalog('sono')."</td>
				<td>
					<select class='easyui-combogrid' id='invoicear-soheaderid' name='invoicear-soheaderid' style='width:250px' data-options=\"
						panelWidth: '700px',
						required: true,
						idField: 'soheaderid',
						textField: 'sono',
						mode:'remote',
						url: '".Yii::app()->createUrl('order/soheader/index',array('grid'=>true,'invso'=>true)) ."',
						method: 'get',
						onBeforeLoad: function(param){
							param.plantid = $('#invoicear-plantid').combogrid('getValue');
							param.addressbookid = $('#invoicear-addressbookid').combogrid('getValue');
						},
						onHidePanel: function(){
							jQuery.ajax({'url':'". Yii::app()->createUrl('order/soheader/generatepocustno') ."',
								'data':{'id':$('#invoicear-soheaderid').combogrid('getValue')},
								'type':'post',
								'dataType':'json',
								'success':function(data)
								{
									$('#invoicear-pocustno').textbox('setValue',data.pocustno);
									jQuery.ajax({'url':'". Yii::app()->createUrl('accounting/invoicear/generatedetail') ."',
								'data':{
									'id':$('#invoicear-invoicearid').val(),
									'soid':$('#invoicear-soheaderid').combogrid('getValue')
								},
								'type':'post',
								'dataType':'json',
								'success':function(data)
								{
									$('#dg-invoicear-invoiceartax').datagrid('reload');
									$('#dg-invoicear-invoicearsj').datagrid('reload');
									$('#dg-invoicear-invoiceardetail').datagrid('reload');
								} ,
								'cache':false});
								} ,
								'cache':false});
							
						},
						columns: [[
								{field:'soheaderid',title:'".GetCatalog('soheaderid') ."',width:'50px'},
								{field:'sono',title:'".GetCatalog('sono') ."',width:'200px'},
								{field:'pocustno',title:'".GetCatalog('pocustno') ."',width:'200px'},
								{field:'sodate',title:'".GetCatalog('sodate') ."',width:'200px'},
						]],
						fitColumns: true\">
					</select>
				</td>
				<td>".GetCatalog('pocustno')."</td>
				<td><input class='easyui-textbox' id='invoicear-pocustno' name='invoicear-pocustno' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('invoiceartaxno')."</td>
				<td><input class='easyui-textbox' id='invoicear-invoiceartaxno' name='invoicear-invoiceartaxno' data-options='required:true'></input></td>
				<td>".GetCatalog('taxno')."</td>
				<td><input class='easyui-textbox' id='invoicear-taxno' name='invoicear-taxno' data-options='required:true,readonly:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('address')."</td>
				<td><input class='easyui-textbox' id='invoicear-addressname' name='invoicear-addressname' data-options='multiline:true,required:true,readonly:true' style='width:300px;height:100px'></input></td>
				<td>".GetCatalog('paymentmethod')."</td>
				<td>
					<select class='easyui-combogrid' id='invoicear-paymentmethodid' name='invoicear-paymentmethodid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						readonly:true,
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
				<td><input class='easyui-textbox' id='invoicear-duedate' name='invoicear-duedate' data-options='required:true,readonly:true'></input></td>
				<td>".GetCatalog('cb')."</td>
				<td>
					<select class='easyui-combogrid' id='invoicear-cashbankid' name='invoicear-cashbankid' style='width:250px' data-options=\"
						panelWidth: '500px',
						idField: 'cashbankid',
						textField: 'cashbankno',
						mode:'remote',
						url: '".Yii::app()->createUrl('accounting/cashbank/index',array('grid'=>true,'trxplant'=>true)) ."',
						onBeforeLoad: function(param){
							param.plantid = $('#invoicear-plantid').combogrid('getValue');
						},
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
				<td><input class='easyui-numberbox' id='invoicear-dpamount' name='invoicear-dpamount'></input></td>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='invoicear-headernote' name='invoicear-headernote' data-options='multiline:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addload'=>"
		$('#invoicear-invoiceardate').datebox({value: (new Date().toString('dd-MMM-yyyy'))});
		$('#invoicear-duedate').datebox({value: (new Date().toString('dd-MMM-yyyy'))});
		$('#invoicear-slocid').val('');	
	",
	'loadsuccess'=>"
		$('#invoicear-invoiceardate').datebox('setValue',data.invoiceardate);
		$('#invoicear-invoicearno').textbox('setValue',data.invoicearno);
		$('#invoicear-plantid').combogrid('setValue',data.plantid);
		$('#invoicear-addressbookid').combogrid('setValue',data.addressbookid);
		$('#invoicear-contractno').textbox('setValue',data.contractno);
		$('#invoicear-invoiceartaxno').textbox('setValue',data.invoiceartaxno);
		$('#invoicear-taxno').textbox('setValue',data.taxno);
		$('#invoicear-addressname').textbox('setValue',data.addressname);
		$('#invoicear-paymentmethodid').combogrid('setValue',data.paymentmethodid);
		$('#invoicear-duedate').textbox('setValue',data.duedate);
		$('#invoicear-cashbankid').textbox('setValue',data.cashbankid);
		$('#invoicear-dpamount').numberbox('setValue',data.dpamount);
		$('#invoicear-headernote').textbox('setValue',data.headernote);
		$('#invoicear-slocid').val('');
	",
	'columndetails'=> array (
	array(
			'id'=>'invoiceartax',
			'idfield'=>'invoiceartaxid',
			'urlsub'=>Yii::app()->createUrl('accounting/invoicear/indextax',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/invoicear/searchtax',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/invoicear/savetax',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/invoicear/savetax',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/invoicear/purgetax',array('grid'=>true)),
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
			'id'=>'invoicearsj',
			'idfield'=>'invoicearsjid',
			'urlsub'=>Yii::app()->createUrl('accounting/invoicear/indexsj',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/invoicear/searchsj',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/invoicear/savesj',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/invoicear/savesj',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/invoicear/purgesj',array('grid'=>true)),
			'subs'=>"
				{field:'invoicearsjid',title:'".GetCatalog('invoicearsjid') ."',align:'right',width:'60px'},
				{field:'gino',title:'".GetCatalog('gino') ."',width:'200px'},
			",
			'columns'=>"
				{
					field:'invoicearsjid',
					title:'".GetCatalog('invoicearsjid') ."',
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
					field:'giheaderid',
					title:'".GetCatalog('gino') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'800px',
							mode : 'remote',
							method:'get',
							idField:'giheaderid',
							textField:'gino',
							url:'".Yii::app()->createUrl('inventory/giheader/index',array('grid'=>true,'invoice'=>true))."',
							onBeforeLoad: function(param){
								param.soheaderid = $('#invoicear-soheaderid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'giheaderid',title:'".GetCatalog('giheaderid')."',width:'80px'},
								{field:'gino',title:'".GetCatalog('gino')."',width:'350px'},
							]]
						}	
					},
					width:'300px',
					sortable: true,
					formatter: function(value,row,index){
						return row.gino;
					}
				},
			",
			'onsuccess'=>"
			$('#dg-invoicear-invoicearpl').datagrid('reload');
			$('#dg-invoicear-invoiceardetail').datagrid('reload');
			$('#dg-invoicear-invoiceartax	').datagrid('reload');
			",
			/*'ondestroy'=>"
			$('#dg-invoicear-invoicearpl').datagrid('reload');
			$('#dg-invoicear-invoiceardetail').datagrid('reload');
			$('#dg-invoicear-invoiceartax	').datagrid('reload');
			",*/
		),
		
		array(
			'id'=>'invoicearpl',
			'idfield'=>'invoicearplid',
			'isnew'=>0,
			'iswrite'=>0,
			'ispurge'=>0,
			'urlsub'=>Yii::app()->createUrl('accounting/invoicear/indexpl',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/invoicear/searchpl',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/invoicear/savepl',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/invoicear/savepl',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/invoicear/purgepl',array('grid'=>true)),
			'subs'=>"
				{field:'invoicearplid',title:'".GetCatalog('invoicearplid') ."',align:'right',width:'60px'},
				{field:'packinglistno',title:'".GetCatalog('packinglistno') ."',width:'200px'},
			",
			'columns'=>"
				{
					field:'invoicearplid',
					title:'".GetCatalog('invoicearplid') ."',
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
					field:'packinglistid',
					title:'".GetCatalog('packinglist') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'800px',
							mode : 'remote',
							method:'get',
							idField:'packinglistid',
							textField:'packinglistno',
							url:'".Yii::app()->createUrl('inventory/packinglist/index',array('grid'=>true))."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'packinglistid',title:'".GetCatalog('packinglistid')."',width:'80px'},
								{field:'packinglistno',title:'".GetCatalog('packinglistno')."',width:'350px'},
							]]
						}	
					},
					width:'300px',
					sortable: true,
					formatter: function(value,row,index){
						return row.packinglistno;
					}
				},
			"
		),
		array(
			'id'=>'invoiceardetail',
			'idfield'=>'invoiceardetailid',
			'urlsub'=>Yii::app()->createUrl('accounting/invoicear/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/invoicear/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/invoicear/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/invoicear/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/invoicear/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'invoiceardetailid',title:'".GetCatalog('invoiceardetailid') ."',align:'right',width:'60px'},
				{field:'gino',title:'".GetCatalog('gino') ."',width:'250px'},
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
						return formatnumber(row.symbol,row.total);
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
					field:'invoiceardetailid',
					title:'".GetCatalog('invoiceardetailid') ."',
					sortable: true,
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
								param.plantid = $('#invoicear-plantid').combogrid('getValue');
								var row = $('#dg-invoicear-invoiceardetail').edatagrid('getSelected');
								if(row==null){
									$(\"input[name='invoicear-slocid']\").val('0');
								}
							},
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-invoicear-invoiceardetail').datagrid('getEditor', {index: index, field:'productid'});
								$(productid.target).combogrid('setValue','');
								$(\"input[name='invoicear-slocid']\").val(row.slocid);
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
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plantfg'=>true)) ."',
							onBeforeLoad: function(param){
								param.plantid = $('#invoicear-plantid').combogrid('getValue');
								param.addressbookid = $('#invoicear-addressbookid').combogrid('getValue');
								param.issource = 0;
							},
							fitColumns:true,
							required:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-invoicear-invoiceardetail').datagrid('getEditor', {index: index, field:'productid'});
								var stdqty2 = $('#dg-invoicear-invoiceardetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-invoicear-invoiceardetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-invoicear-invoiceardetail').datagrid('getEditor', {index: index, field:'stdqty4'});
								var uomid = $('#dg-invoicear-invoiceardetail').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-invoicear-invoiceardetail').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-invoicear-invoiceardetail').datagrid('getEditor', {index: index, field:'uom3id'});
								var uom4id = $('#dg-invoicear-invoiceardetail').datagrid('getEditor', {index: index, field:'uom4id'});
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
								{field:'productcode',title:'".getCatalog('productcode')."',width:'150px'},
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
								var stdqty2 = $('#dg-invoicear-invoiceardetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-invoicear-invoiceardetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-invoicear-invoiceardetail').datagrid('getEditor', {index: index, field:'stdqty4'});								
								var qty2 = $('#dg-invoicear-invoiceardetail').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-invoicear-invoiceardetail').datagrid('getEditor', {index: index, field:'qty3'});								
								var qty4 = $('#dg-invoicear-invoiceardetail').datagrid('getEditor', {index: index, field:'qty4'});						
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
							readonly:true,
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
							readonly:true,
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
			'id'=>'invoicearjurnal',
			'idfield'=>'invoicearjurnalid',
			'urlsub'=>Yii::app()->createUrl('accounting/invoicear/indexjurnal',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/invoicear/searchjurnal',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/invoicear/savejurnal',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/invoicear/savejurnal',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/invoicear/purgejurnal',array('grid'=>true)),
			'subs'=>"
				{field:'accountname',title:'".GetCatalog('accountname') ."',width:'250px'},
				{field:'debit',title:'".GetCatalog('debit') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},align:'right',width:'120px'},
				{field:'credit',title:'".GetCatalog('credit') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.credit);
					},align:'right',width:'120px'},
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
							url:'".$this->createUrl('account/index',array('grid'=>true,'trxcom'=>true)) ."',
							fitColumns:true,
							required:true,
							onBeforeLoad: function(param) {
								 param.companyid = $('#invoicear-plantid').combogrid('getValue');
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