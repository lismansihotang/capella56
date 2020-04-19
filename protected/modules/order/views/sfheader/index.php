<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'sfheaderid',
	'formtype'=>'masterdetail',
	'isxls'=>1,
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appso',
	'url'=>Yii::app()->createUrl('order/sfheader/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('order/sfheader/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('order/sfheader/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('order/sfheader/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('order/sfheader/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('order/sfheader/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('order/sfheader/reject',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('order/sfheader/upload'),
	'downpdf'=>Yii::app()->createUrl('order/sfheader/downpdf'),
	'downxls'=>Yii::app()->createUrl('order/sfheader/downxls'),
	'columns'=>"
		{
			field:'sfheaderid',
			title:'".GetCatalog('ID') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appso')."
		}},
		{
			field:'companyname',
			title:'".GetCatalog('company') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.companyname;
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
			field:'sfdate',
			title:'".GetCatalog('sfdate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'sfno',
			title:'".GetCatalog('sfno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'addressbookid',
			title:'".GetCatalog('customer') ."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'addresstoid',
			title:'".GetCatalog('addressto') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.addresstoname;
		}},
		{
			field:'addresspayid',
			title:'".GetCatalog('addresspay') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.addresspayname;
		}},".((GetMenuAuth('currency') != 0)?"
		{
			field:'totprice',
			title:'".GetCatalog('totprice') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},":'')."
		{
			field:'isexport',
			title:'". GetCatalog('isexport') ."',
			align:'center',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}		
		}},
		{
			field:'issample',
			title:'". GetCatalog('issample') ."',
			align:'center',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}		
		}},
		{
			field:'isavalan',
			title:'". GetCatalog('isavalan') ."',
			align:'center',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}		
		}},
		{
			field:'headernote',
			title:'".GetCatalog('headernote') ."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return row.recordstatusname;
		}},",
	'addload'=>"
		$('#sfheader-sfdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});	
		$('#sfheader-currencyid').combogrid('setValue',40);
		$('#sfheader-currencyrate').numberbox('setValue',1);
	",
	'downloadbuttons'=>"
		<a href='javascript:void(0)' title='".getCatalog('hold')."' class='easyui-linkbutton' iconCls='icon-lock' plain='true' onclick='holdso()'></a>
		<a href='javascript:void(0)' title='".getCatalog('open')."' class='easyui-linkbutton' iconCls='icon-more' plain='true' onclick='openso()'></a>
		<a href='javascript:void(0)' title='".getCatalog('close')."' class='easyui-linkbutton' iconCls='icon-tip' plain='true' onclick='closeso()'></a>
	",
	'addonscripts'=>"
		function holdso() {
			var rows = $('#dg-sfheader').edatagrid('getSelected');
			jQuery.ajax({'url':'".$this->createUrl('sfheader/holdso') ."',
				'data':{'id':rows.sfheaderid},'type':'post','dataType':'json',
				'success':function(data)
				{
					show('Pesan',data.msg);
					$('#dg-sfheader').edatagrid('reload');				
				} ,
				'cache':false});
		};
		function closeso() {
			var rows = $('#dg-sfheader').edatagrid('getSelected');
			jQuery.ajax({'url':'".$this->createUrl('sfheader/closeso') ."',
				'data':{'id':rows.sfheaderid},'type':'post','dataType':'json',
				'success':function(data)
				{
					show('Pesan',data.msg);
					$('#dg-sfheader').edatagrid('reload');				
				} ,
				'cache':false});
		};
		function openso() {
			var rows = $('#dg-sfheader').edatagrid('getSelected');
			jQuery.ajax({'url':'".$this->createUrl('sfheader/openso') ."',
				'data':{'id':rows.sfheaderid},'type':'post','dataType':'json',
				'success':function(data)
				{
					show('Pesan',data.msg);
					$('#dg-sfheader').edatagrid('reload');				
				} ,
				'cache':false});
		};
	",
	'searchfield'=> array ('sfheaderid','plantcode','sfdate','sfno','customer','pocustno','headernote','productname'),
	'headerform'=> "
		<input type='hidden' id='sfheader-productid' name='sfheader-productid' value=''></input>
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('sfno')."</td>
				<td><input class='easyui-textbox' id='sfheader-sfno' name='sfheader-sfno' data-options='readonly:true'></input></td>
				<td>".GetCatalog('sfdate')."</td>
				<td><input class='easyui-datebox' id='sfheader-sfdate' name='sfheader-sfdate' style='width:250px' data-options='formatter:dateformatter,readonly:true,required:true,missingMessage:\"halo\", parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='sfheader-plantid' name='sfheader-plantid' style='width:250px' data-options=\"
								panelWidth: '500px',
								idField: 'plantid',
								required: true,
								textField: 'plantcode',
								invalidMessage:'aa',
								mode: 'remote',
								url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'auth'=>true)) ."',
								method: 'get',
								columns: [[
										{field:'plantid',title:'".getCatalog('plantid') ."',width:'50px'},
										{field:'plantcode',title:'".getCatalog('plantcode') ."',width:'120px'},
										{field:'description',title:'".getCatalog('description') ."',width:'200px'},
								]],
								fitColumns: true
						\">
				</select></td>
				</tr>				
				<tr>
				<td>".GetCatalog('customer')."</td>
				<td>
					<select class='easyui-combogrid' id='sfheader-addressbookid' name='sfheader-addressbookid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/customer/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						onHidePanel: function(){
							jQuery.ajax({'url':'".Yii::app()->createUrl('common/customer/index',array('grid'=>true,'getdata'=>true)) ."',
								'data':{
									'addressbookid':$('#sfheader-addressbookid').combogrid('getValue'),
								},
								'type':'post',
								'dataType':'json',
								'success':function(data)
								{
									$('#sfheader-paymentmethodid').combogrid('setValue',data.paymentmethodid);
									$('#sfheader-addresstoid').combogrid('grid').datagrid('reload');
									$('#sfheader-addresspayid').combogrid('grid').datagrid('reload');
									$('#sfheader-addresspayid').combogrid('setValue',data.addresspayid);
									$('#sfheader-addresstoid').combogrid('setValue',data.addresstoid);
								},
								'cache':false});
						},
						columns: [[
							{field:'addressbookid',title:'".GetCatalog('addressbookid') ."',width:'50px'},
							{field:'fullname',title:'".GetCatalog('fullname') ."',width:'350px'},
						]],
						fitColumns: true\">
					</select>
				</td>
				<td>".GetCatalog('pocustno')."</td>
				<td><input class='easyui-textbox' id='sfheader-pocustno' name='sfheader-pocustno' data-options='required:false' style='width:150px;height:25px'></input></td>
				</tr>			
			<tr>
				<td>".GetCatalog('addressto')."</td>
				<td>
					<select class='easyui-combogrid' id='sfheader-addresstoid' name='sfheader-addresstoid' style='width:250px;height:150px' data-options=\"
						panelWidth: '500px',
						multiline:true,
						required: true,
						idField: 'addressid',
						textField: 'addressname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/customer/indexaddress',array('grid'=>true)) ."',
						method: 'get',
						onShowPanel: function() {
							$('#sfheader-addresstoid').combogrid('grid').datagrid('reload');
						},
						onBeforeLoad: function(param){
							param.id = $('#sfheader-addressbookid').combogrid('getValue');
						},
						columns: [[
							{field:'addressid',title:'".GetCatalog('addressid') ."',width:'50px'},
							{field:'addressname',title:'".GetCatalog('addressname') ."',width:'450px'},
						]],
						fitColumns: true\">
					</select>
				</td>
				<td>".GetCatalog('addresspay')."</td>
				<td>
					<select class='easyui-combogrid' id='sfheader-addresspayid' name='sfheader-addresspayid' style='width:250px;height:150px' data-options=\"
						panelWidth: '500px',
						required: true,
						multiline:true,
						idField: 'addressid',
						textField: 'addressname',
						mode:'remote',
						onShowPanel: function() {
							$('#sfheader-addresspayid').combogrid('grid').datagrid('reload');
						},
						onBeforeLoad: function(param){
							param.id = $('#sfheader-addressbookid').combogrid('getValue');
						},
						url: '".Yii::app()->createUrl('common/customer/indexaddress',array('grid'=>true)) ."',
						method: 'get',
						columns: [[
							{field:'addressid',title:'".GetCatalog('addressid') ."',width:'50px'},
							{field:'addressname',title:'".GetCatalog('addressname') ."',width:'450px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('sales')."</td>
				<td>
					<select class='easyui-combogrid' id='sfheader-salesid' name='sfheader-salesid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'salesid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('order/sales/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						onShowPanel:function() {
							$('#sfheader-salesid').combogrid('grid').datagrid('reload');
						},
						onBeforeLoad:function(param) {
							param.plantid= $('#sfheader-plantid').combogrid('getValue');
						},
						columns: [[
							{field:'salesid',title:'".GetCatalog('salesid') ."',width:'50px'},
							{field:'oldnik',title:'".GetCatalog('oldnik') ."',width:'50px'},
							{field:'fullname',title:'".GetCatalog('fullname') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
				<td>".GetCatalog('paymentmethod')."</td>
				<td>
					<select class='easyui-combogrid' id='sfheader-paymentmethodid' name='sfheader-paymentmethodid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'paymentmethodid',
						textField: 'paycode',
						mode:'remote',
						url: '".Yii::app()->createUrl('accounting/paymentmethod/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
							{field:'paymentmethodid',title:'".GetCatalog('paymentmethodid') ."',width:'50px'},
							{field:'paycode',title:'".GetCatalog('paycode') ."',width:'100px'},
							{field:'paydays',title:'".GetCatalog('paydays') ."',width:'100px'},
							{field:'paymentname',title:'".GetCatalog('paymentname') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('currency')."</td>
				<td>
					<select class='easyui-combogrid' id='sfheader-currencyid' name='sfheader-currencyid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'currencyid',
						textField: 'symbol',
						mode:'remote',
						url: '".Yii::app()->createUrl('admin/currency/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
							{field:'currencyid',title:'".GetCatalog('currencyid') ."',width:'50px'},
							{field:'symbol',title:'".GetCatalog('symbol') ."',width:'100px'},
							{field:'currencyname',title:'".GetCatalog('currencyname') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
				<td>".GetCatalog('currencyrate')."</td>
				<td><input class='easyui-numberbox' id='sfheader-currencyrate' name='sfheader-currencyrate' data-options=\"required:true,value:'1'\" style='width:100px;height:25px'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='sfheader-headernote' name='sfheader-headernote' data-options='multiline:true' style='width:300px;height:100px'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('isexport')."</td>
				<td><input class='easyui-checkbox' name='sfheader-isexport' id='sfheader-isexport'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('issample')."</td>
				<td><input class='easyui-checkbox' name='sfheader-issample' id='sfheader-issample'></input></td>
			</tr>
			<tr>
			<td>".GetCatalog('isavalan')."</td>
			<td><input class='easyui-checkbox' name='sfheader-isavalan' id='sfheader-isavalan'></input></td>
			</tr>
		</table>
	",
	'loadsuccess'=>"
		$('#sfheader-sfdate').datebox('setValue',data.sfdate);
		$('#sfheader-sfno').textbox('setValue',data.sfno);
		$('#sfheader-plantid').combogrid('setValue',data.plantid);
		$('#sfheader-addressbookid').combogrid('setValue',data.addressbookid);
		$('#sfheader-pocustno').textbox('setValue',data.pocustno);
		$('#sfheader-addresstoid').combogrid('setValue',data.addresstoid);
		$('#sfheader-addresspayid').combogrid('setValue',data.addresspayid);
		$('#sfheader-salesid').combogrid('setValue',data.salesid);
		$('#sfheader-paymentmethodid').combogrid('setValue',data.paymentmethodid);
		$('#sfheader-currencyid').combogrid('setValue',data.currencyid);
		$('#sfheader-currencyrate').numberbox('setValue',data.currencyrate);
		$('#sfheader-headernote').textbox('setValue',data.headernote);
		if (data.isexport == 1)
		{
			$('#sfheader-isexport').prop('checked', true);
		} else
		{
			$('#sfheader-isexport').prop('checked', false);
		}
		if (data.issample == 1)
		{
			$('#sfheader-issample').prop('checked', true);
		} else
		{
			$('#sfheader-issample').prop('checked', false);
		}
		if (data.isavalan == 1)
		{
			$('#sfheader-isavalan').prop('checked', true);
		} else
		{
			$('#sfheader-isavalan').prop('checked', false);
		}
	",
	'columndetails'=> array (
		array(
			'id'=>'taxsf',
			'idfield'=>'taxsfid',
			'urlsub'=>Yii::app()->createUrl('order/sfheader/indextaxsf',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('order/sfheader/searchtaxsf',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('order/sfheader/savetaxsf',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('order/sfheader/savetaxsf',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('order/sfheader/purgetaxsf',array('grid'=>true)),
			'subs'=>"
				{field:'taxcode',title:'".GetCatalog('taxcode') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'taxsfid',
					title:'".GetCatalog('taxsfid') ."',
					sortable: true,
					editor: {
						type: 'textbox'
					},
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'sfheaderid',
					title:'".GetCatalog('sfheaderid') ."',
					editor: {
						type: 'textbox'
					},
					hidden:true,
					sortable: true,
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
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'taxid',
							textField:'taxcode',
							url:'".Yii::app()->createUrl('accounting/tax/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'taxid',title:'".GetCatalog('taxid')."',width:'50px'},
								{field:'taxcode',title:'".GetCatalog('taxcode')."',width:'100px'},
								{field:'taxvalue',title:'".GetCatalog('taxvalue')."'},
								{field:'description',title:'".GetCatalog('description')."',width:'200px'},
							]]
						}	
					},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
						return row.taxcode;
				}},
			"
		),		
		array(
			'id'=>'sfdetail',
			'idfield'=>'sfdetailid',
			'urlsub'=>Yii::app()->createUrl('order/sfheader/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('order/sfheader/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('order/sfheader/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('order/sfheader/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('order/sfheader/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'120px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'400px'},
				{field:'qtystock',title:'".GetCatalog('qtystock') ."',formatter: function(value,row,index){
					if (row.stockcount == '1') {
					return '<div style=\"background-color:cyan\">'+formatnumber('',value)+'</div>';
					} else {
						return formatnumber('',value);
					}
				},width:'100px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:'".GetCatalog('uom2code') ."',width:'80px'},".((GetMenuAuth('currency') != 0)?"
				{field:'price',title:'".GetCatalog('price') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'totprice',title:'".GetCatalog('totprice') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},":'')."
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'100px'},
			",
			'columns'=>"
				{
					field:'sfheaderid',
					title:'".GetCatalog('sfheaderid') ."',
					editor: {
						type: 'textbox'
					},
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'sfdetailid',
					title:'".GetCatalog('sfdetailid') ."',
					editor: {
						type: 'textbox'
					},
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
							panelWidth:'750px',
							mode: 'remote',
							method:'get',
							idField:'productid',
							textField:'productname',
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plantfg'=>true)) ."',
							onShowPanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-sfheader-sfdetail').datagrid('getEditor', {index: index, field:'productid'});
								$(productid.target).combogrid('grid').datagrid('reload');
							},
							onBeforeLoad: function(param){
								param.plantid = $('#sfheader-plantid').combogrid('getValue');
								param.addressbookid = $('#sfheader-addressbookid').combogrid('getValue');
								param.issource = 0;
							},
							fitColumns:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-sfheader-sfdetail').datagrid('getEditor', {index: index, field:'productid'});
								var uomid = $('#dg-sfheader-sfdetail').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-sfheader-sfdetail').datagrid('getEditor', {index: index, field:'uom2id'});
								
								var stdqty2 = $('#dg-sfheader-sfdetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-sfheader-sfdetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-sfheader-sfdetail').datagrid('getEditor', {index: index, field:'stdqty4'});
								$(bomid.target).combogrid('grid').datagrid('reload');
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/index',array('grid'=>true,'getdata'=>true)) ."',
									'data':{'productid':$(productid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(uomid.target).combogrid('setValue',data.uom1);
										$(uom2id.target).combogrid('setValue',data.uom2);
									
										$(stdqty2.target).numberbox('setValue',data.qty2);
										$(stdqty3.target).numberbox('setValue',data.qty3);
										$(stdqty4.target).numberbox('setValue',data.qty4);
										$('#sfheader-productid').val(data.productid);
									} ,
									'cache':false});
							},
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'productid',title:'".getCatalog('productid')."',width:'50px'},
								{field:'materialtypecode',title:'".getCatalog('materialtypecode')."',width:'150px'},
								{field:'productname',title:'".getCatalog('productname')."',width:'500px'},
							]]
						}	
					},
					width:'350px',
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
								var stdqty2 = $('#dg-sfheader-sfdetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var qty2 = $('#dg-sfheader-sfdetail').datagrid('getEditor', {index: index, field:'qty2'});								
								$(qty2.target).numberbox('setValue',$(stdqty2.target).numberbox('getValue') * newValue);
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
					title:'".GetCatalog('uom2') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							readonly:true,
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
					field:'price',
					title:'".GetCatalog('price') ."',
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
					field:'totprice',
					title:'".GetCatalog('totprice') ."',
					width:'200px',
					sortable: true,
					formatter: function(value,row,index){
						return formatnumber('',value);
					}
				},
				{
					field:'itemnote',
					title:'".GetCatalog('itemnote')."',
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