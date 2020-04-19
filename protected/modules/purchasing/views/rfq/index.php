<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'rfqid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'apprfq',
	'url'=>Yii::app()->createUrl('purchasing/rfq/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('purchasing/rfq/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('purchasing/rfq/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('purchasing/rfq/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('purchasing/rfq/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('purchasing/rfq/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('purchasing/rfq/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('purchasing/rfq/downpdf'),
	'columns'=>"
		{
			field:'rfqid',
			title:'".GetCatalog('ID') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('apprfq')."
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
			field:'rfqdate',
			title:'".GetCatalog('rfqdate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'rfqno',
			title:'".GetCatalog('rfqno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'addressbookid',
			title:'".GetCatalog('supplier') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'billto',
			title:'".GetCatalog('billto') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return row.billto;
		}},
		{
			field:'addresstoname',
			title:'".GetCatalog('addresstoname') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return row.addresstoname;
		}},".((GetMenuAuth('currency')!=0)?"
		{
			field:'totprice',
			title:'".GetCatalog('totprice') ."',
			sortable: true,
			align: 'right',
			width:'100px',
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},":'')."
		{
			field:'paymentmethodid',
			title:'".GetCatalog('paymentmethod') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return row.paycode;
		}},
		{
			field:'isjasa',
			title:'". GetCatalog('isjasa') ."',
			align:'center',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}		
		}},
		{
			field:'isimport',
			title:'". GetCatalog('isimport') ."',
			align:'center',
			width:'50px',
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
		$('#rfq-rfqdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});	",
	'searchfield'=> array ('rfqid','plantcode','rfqdate','rfqno','supplier','headernote'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('rfqno')."</td>
				<td><input class='easyui-textbox' id='rfq-rfqno' name='rfq-rfqno' data-options='readonly:true'></input></td>
				<td>".GetCatalog('rfqdate')."</td>
				<td><input class='easyui-datebox' id='rfq-rfqdate' name='rfq-rfqdate' data-options='formatter:dateformatter,required:true,readonly:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='rfq-plantid' name='rfq-plantid' style='width:150px' data-options=\"
								panelWidth: '500px',
								idField: 'plantid',
								required: true,
								textField: 'plantcode',
								mode: 'remote',
								url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'combo'=>true)) ."',
								method: 'get',
								onHidePanel: function(){
									jQuery.ajax({'url':'".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'getdata'=>true)) ."',
										'data':{
											'plantid':$('#rfq-plantid').combogrid('getValue'),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#rfq-billto').textbox('setValue',data.billto);
											$('#rfq-addresstoname').textbox('setValue',data.addresstoname);
										},
										'cache':false});
								},
								columns: [[
										{field:'plantid',title:'".getCatalog('plantid') ."',width:'50px'},
										{field:'plantcode',title:'".getCatalog('plantcode') ."',width:'100px'},
										{field:'description',title:'".getCatalog('description') ."',width:'200px'},
								]],
								fitColumns: true
						\">
				</select></td>
				</tr>
				<tr>
				<td>".GetCatalog('billto')."</td>
				<td><input class='easyui-textbox' id='rfq-billto' name='rfq-billto' data-options='multiline:true,readonly:true' style='width:250px;height:80px'></input></td>
			<td>".GetCatalog('addresstoname')."</td>
				<td><input class='easyui-textbox' id='rfq-addresstoname' name='rfq-addresstoname' data-options='multiline:true,readonly:true' style='width:250px;height:80px'></input></td>
			</tr>
				<tr>
				<td>".GetCatalog('supplier')."</td>
				<td>
					<select class='easyui-combogrid' id='rfq-addressbookid' name='rfq-addressbookid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/supplier/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
							{field:'addressbookid',title:'".GetCatalog('addressbookid') ."',width:'50px'},
							{field:'fullname',title:'".GetCatalog('fullname') ."',width:'150px'},
						]],
						fitColumns: true\">
					</select>
				</td>
				</tr>			
			<tr>
			<td>".GetCatalog('addresscontact')."</td>
				<td>
					<select class='easyui-combogrid' id='rfq-addresscontactid' name='rfq-addresscontactid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						multiline:true,
						idField: 'addresscontactid',
						textField: 'addresscontactname',
						mode:'remote',
						onShowPanel:function() {
							$('#rfq-addresscontactid').combogrid('grid').datagrid('reload');
						},
						onBeforeLoad: function(param){
							param.id = $('#rfq-addressbookid').combogrid('getValue');
						},
						url: '".Yii::app()->createUrl('common/supplier/indexcontact',array('grid'=>true)) ."',
						method: 'get',
						columns: [[
							{field:'addresscontactid',title:'".GetCatalog('addresscontactid') ."',width:'50px'},
							{field:'addresscontactname',title:'".GetCatalog('addresscontactname') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
				<td>".GetCatalog('paymentmethod')."</td>
				<td>
					<select class='easyui-combogrid' id='rfq-paymentmethodid' name='rfq-paymentmethodid' style='width:250px' data-options=\"
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
					<select class='easyui-combogrid' id='rfq-currencyid' name='rfq-currencyid' style='width:250px' data-options=\"
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
				<td><input class='easyui-numberbox' id='rfq-currencyrate' name='rfq-currencyrate' data-options=\"required:true,value:'1'\" style='width:100px;height:25px'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('isjasa')."</td>
				<td><input class='easyui-checkbox' id='rfq-isjasa' name='rfq-isjasa' data-options=\"
				onChange: function(checked){
					if (checked == true) {
						$('#tabdetails-rfq').tabs('enableTab',0);
						$('#tabdetails-rfq').tabs('enableTab',1);
						$('#tabdetails-rfq').tabs('enableTab',2);
						$('#rfq-isjasa-value').val(1);
						$('#adddetail-rfq-rfqdetail').linkbutton('disable');
						$('#savedetail-rfq-rfqdetail').linkbutton('disable');
						$('#canceldetail-rfq-rfqdetail').linkbutton('disable');
						$('#purgedetail-rfq-rfqdetail').linkbutton('disable');
						$('#dg-rfq-rfqdetail').edatagrid('disableEditing');
					} else {
						$('#tabdetails-rfq').tabs('enableTab',0);
						$('#tabdetails-rfq').tabs('disableTab',1);
						$('#tabdetails-rfq').tabs('disableTab',2);
						$('#rfq-isjasa-value').val(0);
						$('#adddetail-rfq-rfqdetail').linkbutton('enable');
						$('#savedetail-rfq-rfqdetail').linkbutton('enable');
						$('#canceldetail-rfq-rfqdetail').linkbutton('enable');
						$('#purgedetail-rfq-rfqdetail').linkbutton('enable');
						$('#dg-rfq-rfqdetail').edatagrid('enableEditing');
					}
					purgerfqalldetail($('#rfq-rfqid').val());
					$('#tabdetails-rfq').tabs('select',0);
				}
				\"></input></td>
				<td>".GetCatalog('isimport')."</td>
				<td><input class='easyui-checkbox' name='rfq-isimport' id='rfq-isimport'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='rfq-headernote' name='rfq-headernote' data-options='multiline:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addload'=>"
		$('#rfq-rfqdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});	
		$('#rfq-isjasa-value').val(0);
	",
	'addonscripts'=>"
		function purgerfqalldetail(\$id) {
			jQuery.ajax({'url':'".Yii::app()->createUrl('purchasing/rfq/purgealldetail') ."',
				'data':{
					'id':\$id,
				},
				'type':'post',
				'dataType':'json',
				'success':function(data)
				{
					$('#dg-rfq-rfqdetail').datagrid('reload');
					$('#dg-rfq-rfqjasa').datagrid('reload');
					$('#dg-rfq-rfqresult').datagrid('reload');
				},
				'cache':false});
		}
	",
	'loadsuccess'=>"
		$('#rfq-rfqno').textbox('setValue',data.rfqno);
		$('#rfq-rfqdate').textbox('setValue',data.rfqdate);
		$('#rfq-plantid').combogrid('setValue',data.plantid);
		$('#rfq-addressbookid').combogrid('setValue',data.addressbookid);
		$('#rfq-billto').textbox('setValue',data.billto);
		$('#rfq-addresstoname').textbox('setValue',data.addresstoname);
		$('#rfq-addresscontactid').combogrid('setValue',data.addresscontactid);
		$('#rfq-paymentmethodid').combogrid('setValue',data.paymentmethodid);
		$('#rfq-currencyid').combogrid('setValue',data.currencyid);
		$('#rfq-currencyrate').numberbox('setValue',data.currencyrate);
		$('#rfq-headernote').textbox('setValue',data.headernote);
		if (data.isimport == 1) {
			$('#rfq-isimport').prop('checked', true);
		} else {
			$('#rfq-isimport').prop('checked', false);
		}
		if (data.isjasa == 1)
		{
			$('#rfq-isjasa-value').val(1);
			$('#rfq-isjasa').prop('checked', true);
			$('#tabdetails-rfq').tabs('disableTab',0);
			$('#tabdetails-rfq').tabs('enableTab',1);
			$('#tabdetails-rfq').tabs('disableTab',2);
		}
		else {
			$('#rfq-isjasa-value').val(0);
			$('#rfq-isjasa').prop('checked', false);
			$('#tabdetails-rfq').tabs('enableTab',0);
			$('#tabdetails-rfq').tabs('disableTab',1);
			$('#tabdetails-rfq').tabs('disableTab',2);
		}
	",
	'columndetails'=> array (
		array(
			'id'=>'rfqdetail',
			'idfield'=>'rfqdetailid',
			'urlsub'=>Yii::app()->createUrl('purchasing/rfq/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('purchasing/rfq/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('purchasing/rfq/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('purchasing/rfq/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('purchasing/rfq/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'prno',title:'".GetCatalog('prno') ."',width:'150px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'grqty',title:'".GetCatalog('grqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'qtysisa',title:'".GetCatalog('qtysisa') ."',
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
					},width:'60px'},
				{field:'totprice',title:'".GetCatalog('totprice') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				":'')."
				{field:'arrivedate',title:'".GetCatalog('arrivedate') ."',width:'100px'},
				{field:'toleransiup',title:'".GetCatalog('overdelvtol') ."',width:'120px'},
				{field:'toleransidown',title:'".GetCatalog('underdelvtol') ."',width:'130px'},
				{field:'sloccode',title:'".GetCatalog('sloc') ."',width:'100px'},
				{field:'requestedbycode',title:'".GetCatalog('requestedbycode') ."',width:'100px'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'100px'},
			",
			'columns'=>"
				{
					field:'rfqid',
					title:'".GetCatalog('rfqid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'rfqdetailid',
					title:'".GetCatalog('rfqdetailid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'prheaderid',
					title:'".GetCatalog('prheader') ."',
					sortable: true,
					editor: {
						type: 'textbox',
					},
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
					field:'prrawid',
					title:'".GetCatalog('prheader') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'700px',
							mode : 'remote',
							method:'get',
							idField:'prrawid',
							textField:'prno',
							url: '".Yii::app()->createUrl('inventory/prheader/indexprpo',array('grid'=>true))."',		
							onShowPanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var prrawid = $('#dg-rfq-rfqdetail').datagrid('getEditor', {index: index, field:'prrawid'});
								$(prrawid.target).combogrid('grid').datagrid('reload');
							},
							onBeforeLoad: function(param) {
								param.plantid = $('#rfq-plantid').combogrid('getValue');
								param.addressbookid = $('#rfq-addressbookid').combogrid('getValue');
								param.isjasa = 0;
							},
							onHidePanel: function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-rfq-rfqdetail').datagrid('getEditor', {index: index, field:'productid'});
								var prrawid = $('#dg-rfq-rfqdetail').datagrid('getEditor', {index: index, field:'prrawid'});
								var prheaderid = $('#dg-rfq-rfqdetail').datagrid('getEditor', {index: index, field:'prheaderid'});
								var qty = $('#dg-rfq-rfqdetail').datagrid('getEditor', {index: index, field:'qty'});
								var qty2 = $('#dg-rfq-rfqdetail').datagrid('getEditor', {index: index, field:'qty2'});
								var uomid = $('#dg-rfq-rfqdetail').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-rfq-rfqdetail').datagrid('getEditor', {index: index, field:'uom2id'});
								var slocid = $('#dg-rfq-rfqdetail').datagrid('getEditor', {index: index, field:'slocid'});
								var arrivedate = $('#dg-rfq-rfqdetail').datagrid('getEditor', {index: index, field:'arrivedate'});
								var requestedbyid = $('#dg-rfq-rfqdetail').datagrid('getEditor', {index: index, field:'requestedbyid'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('inventory/prheader/index',array('grid'=>true,'getdata'=>true)) ."',
									'data':{
										'prrawid':$(prrawid.target).textbox('getValue'),
									},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(prheaderid.target).textbox('setValue',data.prheaderid);
										$(productid.target).combogrid('setValue',data.productid);
										$(qty.target).numberbox('setValue',data.qty);
										$(qty2.target).numberbox('setValue',data.qty2);
										$(requestedbyid.target).combogrid('setValue',data.requestedbyid);
										$(slocid.target).combogrid('setValue',data.slocfromid);
										$(uomid.target).combogrid('setValue',data.uomid);
										$(uom2id.target).combogrid('setValue',data.uom2id);
										$(arrivedate.target).datebox('setValue',data.reqdate);
									} ,
									'cache':false});
							},
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'prheaderid',title:'".GetCatalog('prheaderid')."',width:'50px'},
								{field:'prno',title:'".GetCatalog('prno')."',width:'120px'},
								{field:'productname',title:'".GetCatalog('product')."',width:'250px'},
								{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'100px'},
								{field:'qty',title:'".GetCatalog('qty')."',width:'100px'},
								{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'100px'},
							]]
						}		
					},
					width:'200px',
					sortable: true,
					formatter: function(value,row,index){
										return row.prno;
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
							readonly:true,
							textField:'productname',
							readonly:true,
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							onChange: function(newValue, oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-rfq-rfqdetail').datagrid('getEditor', {index: index, field:'productid'});
								var productname = $('#dg-rfq-rfqdetail').datagrid('getEditor', {index: index, field:'productname'});
								var stdqty2 = $('#dg-rfq-rfqdetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/index',array('grid'=>true,'getdata'=>true)) ."',
									'data':{'productid':$(productid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(stdqty2.target).numberbox('setValue',data.qty2);
									} ,
									'cache':false});
							},
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'productid',title:'".getCatalog('productid')."',width:'50px'},
								{field:'materialtypecode',title:'".getCatalog('materialtypecode')."',width:'150px'},
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
								var productid = $('#dg-rfq-rfqdetail').datagrid('getEditor', {index: index, field:'productid'});
								var productname = $('#dg-rfq-rfqdetail').datagrid('getEditor', {index: index, field:'productname'});
								var stdqty2 = $('#dg-rfq-rfqdetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var qty2 = $('#dg-rfq-rfqdetail').datagrid('getEditor', {index: index, field:'qty2'});								
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
					title:'".GetCatalog('uom2code') ."',
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
					field:'arrivedate',
					title:'".GetCatalog('arrivedate')."',
					editor: {
						type: 'datebox',
						options:{
							required:true,
							formatter:dateformatter,
							parser:dateparser
						}
					},
					sortable: true,
					width:'150px',
					formatter: function(value,row,index){
										return value;
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
				".((GetMenuAuth('currency')!=0)?"
		{
			field:'totprice',
			title:'".GetCatalog('totprice') ."',
			sortable: true,
			align: 'right',
			width:'100px',
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},":'')."
				{
					field:'toleransiup',
					title:'".GetCatalog('overdelvtol') ."',
					width:'120px',
					editor: {
						type: 'numberbox',
						options:{
							precision:0,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							required:true,
						}
					},
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
				{
					field:'toleransidown',
					title:'".GetCatalog('underdelvtol') ."',
					width:'130px',
					editor: {
						type: 'numberbox',
						options:{
							precision:0,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							required:true,
						}
					},
					sortable: true,
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
									readonly:true,
									url:'".Yii::app()->createUrl('common/sloc/index',array('grid'=>true,'combo'=>true)) ."',
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
					field:'requestedbyid',
					title:'".GetCatalog('requestedby') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							readonly:true,
							idField:'requestedbyid',
							textField:'requestedbycode',
							url:'".Yii::app()->createUrl('common/requestedby/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'requestedbyid',title:'".GetCatalog('requestedbyid')."',width:'50px'},
								{field:'requestedbycode',title:'".GetCatalog('requestedbycode')."',width:'150px'},
							]]
						}		
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.requestedbycode;
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
		array(
			'id'=>'rfqjasa',
			'idfield'=>'rfqjasaid',
			'urlsub'=>Yii::app()->createUrl('purchasing/rfq/indexrfqjasa',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('purchasing/rfq/searchrfqjasa',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('purchasing/rfq/saverfqjasa',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('purchasing/rfq/saverfqjasa',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('purchasing/rfq/purgerfqjasa',array('grid'=>true)),
			'subs'=>"
				{field:'prno',title:'".GetCatalog('prno') ."',width:'150px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'150px'},
				".((GetMenuAuth('currency') != 0)?"
				{field:'price',title:'".GetCatalog('price') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'60px'},
				{field:'totprice',title:'".GetCatalog('totprice') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				":'')."						
				{field:'reqdate',title:'".GetCatalog('reqdate') ."',width:'100px'},
				{field:'sloccode',title:'".GetCatalog('slocto') ."',width:'100px'},
				{field:'mesincode',title:'".GetCatalog('mesin') ."',width:'150px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'rfqid',
					title:'".GetCatalog('rfqid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'rfqjasaid',
					title:'".GetCatalog('rfqjasaid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'prrawid',
					title:'".GetCatalog('prraw') ."',
					sortable: true,
					editor: {
						type: 'textbox',
					},
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'prheaderid',
					title:'".GetCatalog('prjasa') ."',
					sortable: true,
					editor: {
						type: 'textbox',
					},
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'prjasaid',
					title:'".GetCatalog('prheader') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'700px',
							mode : 'remote',
							method:'get',
							idField:'prjasaid',
							textField:'prno',
							url: '".Yii::app()->createUrl('inventory/prheader/indexprpo',array('grid'=>true))."',
							onShowPanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var prjasaid = $('#dg-rfq-rfqjasa').datagrid('getEditor', {index: index, field:'prjasaid'});
								$(prjasaid.target).combogrid('grid').datagrid('reload');
							},
							onBeforeLoad: function(param){
								param.plantid = $('#rfq-plantid').combogrid('getValue');
								param.addressbookid = $('#rfq-addressbookid').combogrid('getValue');
								param.isjasa = 1;
							},
							onHidePanel: function() {
									var tr = $(this).closest('tr.datagrid-row');
									var index = parseInt(tr.attr('datagrid-row-index'));
									var productid = $('#dg-rfq-rfqjasa').datagrid('getEditor', {index: index, field:'productid'});
									var prheaderid = $('#dg-rfq-rfqjasa').datagrid('getEditor', {index: index, field:'prheaderid'});
									var prjasaid = $('#dg-rfq-rfqjasa').datagrid('getEditor', {index: index, field:'prjasaid'});
									var qty = $('#dg-rfq-rfqjasa').datagrid('getEditor', {index: index, field:'qty'});
									var uomid = $('#dg-rfq-rfqjasa').datagrid('getEditor', {index: index, field:'uomid'});
									var slocid = $('#dg-rfq-rfqjasa').datagrid('getEditor', {index: index, field:'sloctoid'});
									var mesinid = $('#dg-rfq-rfqjasa').datagrid('getEditor', {index: index, field:'mesinid'});
									var reqdate = $('#dg-rfq-rfqjasa').datagrid('getEditor', {index: index, field:'reqdate'});
									jQuery.ajax({'url':'".Yii::app()->createUrl('inventory/prheader/index',array('grid'=>true,'getdatajasa'=>true)) ."',
										'data':{
											'prjasaid':$(prjasaid.target).combogrid('getValue'), 
											},
										'type':'post','dataType':'json',
										'success':function(data)
										{
											$(prheaderid.target).textbox('setValue',data.prheaderid);
											$(productid.target).combogrid('setValue',data.productid);
											$(qty.target).numberbox('setValue',data.qty);
											$(mesinid.target).combogrid('setValue',data.mesinid);
											$(slocid.target).combogrid('setValue',data.sloctoid);
											$(uomid.target).combogrid('setValue',data.uomid);
											$(reqdate.target).datebox('setValue',data.reqdate);
											$('#dg-rfq-rfqdetail').datagrid('reload');
											$('#dg-rfq-rfqresult').datagrid('reload');
										} ,
										'cache':false});
							},
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'prheaderid',title:'".GetCatalog('prheaderid')."',width:'50px'},
								{field:'prno',title:'".GetCatalog('prno')."',width:'120px'},
								{field:'productname',title:'".GetCatalog('product')."',width:'250px'},
								{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'100px'},
								{field:'qty',title:'".GetCatalog('qty')."',width:'100px'},
								{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'100px'},
							]]
						}		
					},
					width:'200px',
					sortable: true,
					formatter: function(value,row,index){
										return row.prno;
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
							readonly:true,
							textField:'productname',
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'productid',title:'".getCatalog('productid')."',width:'50px'},
								{field:'productcode',title:'".getCatalog('productcode')."',width:'150px'},
								{field:'productname',title:'".getCatalog('productname')."',width:'450px'},
							]]
						}	
					},
					width:'250px',
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
					field:'reqdate',
					title:'".GetCatalog('reqdate')."',
					editor: {
						type: 'datebox',
					},
					sortable: true,
					width:'150px',
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'sloctoid',
					title:'".GetCatalog('slocto') ."',
					editor:{
							type:'combogrid',
							options:{
									panelWidth:'500px',
									mode : 'remote',
									method:'get',
									idField:'slocid',
									textField:'sloccode',
									url:'".Yii::app()->createUrl('common/sloc/indextrxplantsloc',array('grid'=>true)) ."',
									fitColumns:true,
									required:true,
									onBeforeLoad: function(param){
										param.plantid = $('#rfq-plantid').combogrid('getValue');
									},
									loadMsg: '".GetCatalog('pleasewait')."',
									columns:[[
										{field:'slocid',title:'".GetCatalog('slocid')."',width:'50px'},
										{field:'sloccode',title:'".GetCatalog('sloccode')."',width:'150px'},
										{field:'description',title:'".GetCatalog('description')."',width:'150px'},
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
					field:'mesinid',
					title:'".getCatalog('mesin') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'550px',
							mode : 'remote',
							method:'get',
							idField:'mesinid',
							textField:'namamesin',
							url:'".Yii::app()->createUrl('common/mesin/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'mesinid',title:'".getCatalog('mesinid')."',width:'80px'},
								{field:'namamesin',title:'".getCatalog('namamesin')."',width:'150px'},
							]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return row.namamesin;
					}
				},
				{
					field:'description',
					title:'".GetCatalog('description')."',
					editor: 'textbox',
					sortable: true,
					width:'100px',
					formatter: function(value,row,index){
										return value;
					}
				},
			"
		),
		array(
			'id'=>'rfqresult',
			'idfield'=>'rfqresultid',
			'urlsub'=>Yii::app()->createUrl('purchasing/rfq/indexrfqresult',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('purchasing/rfq/searchrfqresult',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('purchasing/rfq/saverfqresult',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('purchasing/rfq/saverfqresult',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('purchasing/rfq/purgerfqresult',array('grid'=>true)),
			'isnew'=>0,'iswrite'=>0,'ispurge'=>0,
			'subs'=>"
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'150px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:'".GetCatalog('uom2code') ."',width:'150px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom3code',titl21e:'".GetCatalog('uom3code') ."',width:'150px'},
				{field:'qty4',title:'".GetCatalog('qty4') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom4code',title:'".GetCatalog('uom4code') ."',width:'150px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'rfqid',
					title:'".GetCatalog('rfqid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'rfqresultid',
					title:'".GetCatalog('rfqresultid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'prresultid',
					title:'".GetCatalog('prresult') ."',
					sortable: true,
					editor: {
						type: 'textbox',
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
							readonly:true,
							textField:'productname',
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'productsales'=>true)) ."',
							fitColumns:true,
							required:true,
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
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uom4code;
					}
				},
				{
					field:'description',
					title:'".GetCatalog('description')."',
					editor: 'textbox',
					sortable: true,
					width:'100px',
					formatter: function(value,row,index){
										return value;
					}
				},
			"
		),
		array(
			'id'=>'taxrfq',
			'idfield'=>'taxrfqid',
			'urlsub'=>Yii::app()->createUrl('purchasing/rfq/indextaxrfq',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('purchasing/rfq/searchtaxrfq',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('purchasing/rfq/savetaxrfq',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('purchasing/rfq/savetaxrfq',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('purchasing/rfq/purgetaxrfq',array('grid'=>true)),
			'subs'=>"
				{field:'taxcode',title:'".GetCatalog('taxcode') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'rfqid',
					title:'".GetCatalog('rfqid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'sotaxid',
					title:'".GetCatalog('sotaxid') ."',
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
		)
	),	
));