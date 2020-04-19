<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'poheaderid',
	'formtype'=>'masterdetail',
	'isxls'=>1,
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'apppo',
	'url'=>Yii::app()->createUrl('purchasing/poheader/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('purchasing/poheader/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('purchasing/poheader/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('purchasing/poheader/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('purchasing/poheader/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('purchasing/poheader/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('purchasing/poheader/reject',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('purchasing/poheader/upload'),
	'downpdf'=>Yii::app()->createUrl('purchasing/poheader/downpdf'),
	'downxls'=>Yii::app()->createUrl('purchasing/poheader/downxls'),
	'columns'=>"
		{
			field:'poheaderid',
			title:'".GetCatalog('ID') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('apppo')."
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
			field:'podate',
			title:'".GetCatalog('podate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'pono',
			title:'".GetCatalog('pono') ."',
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
		$('#poheader-podate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});	",
	'searchfield'=> array ('poheaderid','plantcode','podate','pono','supplier','prno','requestedby','headernote','productcode','productname','recordstatus'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('pono')."</td>
				<td><input class='easyui-textbox' id='poheader-pono' name='poheader-pono' data-options=''></input></td>
				<td>".GetCatalog('podate')."</td>
				<td><input class='easyui-datebox' id='poheader-podate' name='poheader-podate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='poheader-plantid' name='poheader-plantid' style='width:150px' data-options=\"
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
											'plantid':$('#poheader-plantid').combogrid('getValue'),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#poheader-billto').textbox('setValue',data.billto);
											$('#poheader-addresstoname').textbox('setValue',data.addresstoname);
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
				<td><input class='easyui-textbox' id='poheader-billto' name='poheader-billto' data-options='multiline:true,readonly:true' style='width:250px;height:80px'></input></td>
			<td>".GetCatalog('addresstoname')."</td>
				<td><input class='easyui-textbox' id='poheader-addresstoname' name='poheader-addresstoname' data-options='multiline:true,readonly:true' style='width:250px;height:80px'></input></td>
			</tr>
				<tr>
				<td>".GetCatalog('supplier')."</td>
				<td>
					<select class='easyui-combogrid' id='poheader-addressbookid' name='poheader-addressbookid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/supplier/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
								onHidePanel: function(){
									jQuery.ajax({'url':'".Yii::app()->createUrl('common/supplier/index',array('grid'=>true,'getdata'=>true)) ."',
										'data':{
											'addressbookid':$('#poheader-addressbookid').combogrid('getValue'),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#poheader-paymentmethodid').combogrid('setValue',data.paymentmethodid);
										},
										'cache':false});
								},
						columns: [[
							{field:'addressbookid',title:'".GetCatalog('addressbookid') ."',width:'50px'},
							{field:'fullname',title:'".GetCatalog('fullname') ."',width:'450px'},
						]],
						fitColumns: true\">
					</select>
				</td>
				</tr>			
			<tr>
			<td>".GetCatalog('addresscontact')."</td>
				<td>
					<select class='easyui-combogrid' id='poheader-addresscontactid' name='poheader-addresscontactid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: false,
						multiline:true,
						idField: 'addresscontactid',
						textField: 'addresscontactname',
						mode:'remote',
						onShowPanel:function() {
							$('#poheader-addresscontactid').combogrid('grid').datagrid('reload');
						},
						onBeforeLoad: function(param){
							param.id = $('#poheader-addressbookid').combogrid('getValue');
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
					<select class='easyui-combogrid' id='poheader-paymentmethodid' name='poheader-paymentmethodid' style='width:250px' data-options=\"
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
					<select class='easyui-combogrid' id='poheader-currencyid' name='poheader-currencyid' style='width:250px' data-options=\"
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
				<td><input class='easyui-numberbox' id='poheader-currencyrate' name='poheader-currencyrate' data-options=\"required:true,value:'1'\" style='width:100px;height:25px'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('isjasa')."</td>
				<td><input class='easyui-checkbox' id='poheader-isjasa' name='poheader-isjasa' data-options=\"
				onChange: function(checked){
					if (checked == true) {
						$('#tabdetails-poheader').tabs('enableTab',1);
						$('#tabdetails-poheader').tabs('enableTab',2);
						$('#tabdetails-poheader').tabs('enableTab',3);
						$('#poheader-isjasa-value').val(1);
						$('#adddetail-poheader-podetail').linkbutton('disable');
						$('#savedetail-poheader-podetail').linkbutton('disable');
						$('#canceldetail-poheader-podetail').linkbutton('disable');
						$('#purgedetail-poheader-podetail').linkbutton('disable');
						$('#dg-poheader-podetail').edatagrid('disableEditing');
					} else {
						$('#tabdetails-poheader').tabs('enableTab',1);
						$('#tabdetails-poheader').tabs('enableTab',2);
						$('#tabdetails-poheader').tabs('disableTab',3);
						$('#poheader-isjasa-value').val(0);
						$('#adddetail-poheader-podetail').linkbutton('enable');
						$('#savedetail-poheader-podetail').linkbutton('enable');
						$('#canceldetail-poheader-podetail').linkbutton('enable');
						$('#purgedetail-poheader-podetail').linkbutton('enable');
						$('#dg-poheader-podetail').edatagrid('enableEditing');
					}
					$('#tabdetails-poheader').tabs('select',0);
				}
				\"></input></td>
				<td>".GetCatalog('isimport')."</td>
				<td><input class='easyui-checkbox' name='poheader-isimport' id='poheader-isimport'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='poheader-headernote' name='poheader-headernote' data-options='multiline:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addload'=>"
		$('#poheader-podate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});	
		$('#poheader-isjasa-value').val(0);
	",
	'addonscripts'=>"
		function purgepoalldetail(\$id) {
			jQuery.ajax({'url':'".Yii::app()->createUrl('purchasing/poheader/purgealldetail') ."',
				'data':{
					'id':\$id,
				},
				'type':'post',
				'dataType':'json',
				'success':function(data)
				{
					$('#dg-poheader-podetail').datagrid('reload');
					$('#dg-poheader-pojasa').datagrid('reload');
					$('#dg-poheader-poresult').datagrid('reload');
				},
				'cache':false});
		}
	",
	'loadsuccess'=>"
		$('#poheader-pono').textbox('setValue',data.pono);
		$('#poheader-podate').textbox('setValue',data.podate);
		$('#poheader-plantid').combogrid('setValue',data.plantid);
		$('#poheader-addressbookid').combogrid('setValue',data.addressbookid);
		$('#poheader-billto').textbox('setValue',data.billto);
		$('#poheader-addresstoname').textbox('setValue',data.addresstoname);
		$('#poheader-addresscontactid').combogrid('setValue',data.addresscontactid);
		$('#poheader-paymentmethodid').combogrid('setValue',data.paymentmethodid);
		$('#poheader-currencyid').combogrid('setValue',data.currencyid);
		$('#poheader-currencyrate').numberbox('setValue',data.currencyrate);
		$('#poheader-headernote').textbox('setValue',data.headernote);
		if (data.isimport == 1) {
			$('#poheader-isimport').prop('checked', true);
		} else {
			$('#poheader-isimport').prop('checked', false);
		}
		if (data.isjasa == 1)
		{
			$('#poheader-isjasa-value').val(1);
			$('#poheader-isjasa').prop('checked', true);
			$('#tabdetails-poheader').tabs('disableTab',1);
			$('#tabdetails-poheader').tabs('enableTab',2);
			$('#tabdetails-poheader').tabs('disableTab',3);
		}
		else {
			$('#poheader-isjasa-value').val(0);
			$('#poheader-isjasa').prop('checked', false);
			$('#tabdetails-poheader').tabs('enableTab',1);
			$('#tabdetails-poheader').tabs('disableTab',2);
			$('#tabdetails-poheader').tabs('disableTab',3);
		}
	",
	'columndetails'=> array (
	array(
			'id'=>'taxpo',
			'idfield'=>'taxpoid',
			'urlsub'=>Yii::app()->createUrl('purchasing/poheader/indextaxpo',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('purchasing/poheader/searchtaxpo',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('purchasing/poheader/savetaxpo',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('purchasing/poheader/savetaxpo',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('purchasing/poheader/purgetaxpo',array('grid'=>true)),
			'subs'=>"
				{field:'taxcode',title:'".GetCatalog('taxcode') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'poheaderid',
					title:'".GetCatalog('poheaderid') ."',
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
							panelWidth:'700px',
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
								{field:'taxcode',title:'".GetCatalog('taxcode')."',width:'150px'},
								{field:'taxvalue',title:'".GetCatalog('taxvalue')."'},
								{field:'description',title:'".GetCatalog('description')."',width:'400px'},
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
			'id'=>'podetail',
			'idfield'=>'podetailid',
			'urlsub'=>Yii::app()->createUrl('purchasing/poheader/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('purchasing/poheader/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('purchasing/poheader/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('purchasing/poheader/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('purchasing/poheader/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'prno',title:'".GetCatalog('prno') ."',width:'150px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productcode',title:'".GetCatalog('productcode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'450px'},
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
					field:'poheaderid',
					title:'".GetCatalog('poheaderid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'podetailid',
					title:'".GetCatalog('podetailid') ."',
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
					field:'stdqty',
					title:'".getCatalog('stdqty') ."',
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
					field:'prrawid',
					title:'".GetCatalog('prheader') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'950px',
							mode : 'remote',
							method:'get',
							idField:'prrawid',
							textField:'prno',
							url: '".Yii::app()->createUrl('inventory/prheader/indexprpo',array('grid'=>true))."',		
							onShowPanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var prrawid = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'prrawid'});
								$(prrawid.target).combogrid('grid').datagrid('reload');
							},
							onBeforeLoad: function(param) {
								param.plantid = $('#poheader-plantid').combogrid('getValue');
								param.addressbookid = $('#poheader-addressbookid').combogrid('getValue');
								param.isjasa = 0;
							},
							onHidePanel: function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'productid'});
								var prrawid = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'prrawid'});
								var prheaderid = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'prheaderid'});
								var qty = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'qty'});
								var qty2 = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'qty2'});
								var uomid = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'uom2id'});
								var slocid = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'slocid'});
								var arrivedate = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'arrivedate'});
								var requestedbyid = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'requestedbyid'});
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
							required:false,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'prheaderid',title:'".GetCatalog('prheaderid')."',width:'50px'},
								{field:'prno',title:'".GetCatalog('prno')."',width:'120px'},
								{field:'productname',title:'".GetCatalog('product')."',width:'650px'},
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
							panelWidth:'950px',
							mode: 'remote',
							method:'get',
							idField:'productid',
							textField:'productname',
							readonly:false,
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plantpo'=>true)) ."',
							fitColumns:true,
							required:true,
							onBeforeLoad: function(param){
								param.plantid = $('#poheader-plantid').combogrid('getValue');
								param.addressbookid = $('#poheader-addressbookid').combogrid('getValue');
							},
							onHidePanel: function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'productid'});
								var uomid = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'uom2id'});
								var stdqty = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var price = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'price'});
								var slocid = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'slocid'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/product/getproductplant') ."',
									'data':{'productid':$(productid.target).combogrid('getValue'),'issource':1},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(slocid.target).combogrid('setValue',data.slocid);
										$(uomid.target).combogrid('setValue',data.uom1);
										$(uom2id.target).combogrid('setValue',data.uom2);
										$(stdqty.target).numberbox('setValue',data.qty1);
										$(stdqty2.target).numberbox('setValue',data.qty2);
									} ,
									'cache':false});
								jQuery.ajax({'url':'".Yii::app()->createUrl('purchasing/purchinforec/getprice') ."',
									'data':{'productid':$(productid.target).combogrid('getValue'),
									'addressbookid':$('#poheader-addressbookid').combogrid('getValue')
									},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(price.target).numberbox('setValue',data.currencyvalue);
									} ,
									'cache':false});
							},
	
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'productid',title:'".getCatalog('productid')."',width:'50px'},
								{field:'materialtypecode',title:'".getCatalog('materialtypecode')."',width:'150px'},
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
								var productid = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'productid'});
								var productname = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'productname'});
								var stdqty = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var qty2 = $('#dg-poheader-podetail').datagrid('getEditor', {index: index, field:'qty2'});								
								$(qty2.target).numberbox('setValue',$(stdqty2.target).numberbox('getValue') * (newValue / $(stdqty.target).numberbox('getValue')));
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
									onBeforeLoad: function(param){
										param.plantid = $('#poheader-plantid').combogrid('getValue');
									},
									fitColumns:true,
									required:true,
									readonly:false,
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
					field:'requestedbyid',
					title:'".GetCatalog('requestedby') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							readonly:false,
							idField:'requestedbyid',
							textField:'requestedbycode',
							url:'".Yii::app()->createUrl('common/requestedby/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:false,
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
					editor: {
						type: 'textbox',
						options: {
							multiline:true,
							height:40
						}
					},
					sortable: true,
					width:'300px',
					formatter: function(value,row,index){
										return value;
					}
				},
			"
		),
		array(
			'id'=>'pojasa',
			'idfield'=>'pojasaid',
			'urlsub'=>Yii::app()->createUrl('purchasing/poheader/indexpojasa',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('purchasing/poheader/searchpojasa',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('purchasing/poheader/savepojasa',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('purchasing/poheader/savepojasa',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('purchasing/poheader/purgepojasa',array('grid'=>true)),
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
					field:'poheaderid',
					title:'".GetCatalog('poheaderid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'pojasaid',
					title:'".GetCatalog('pojasaid') ."',
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
							url:'".Yii::app()->createUrl('inventory/prheader/indexprpo',array('grid'=>true,'prpo'=>true)) ."',
							onShowPanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var prjasaid = $('#dg-poheader-pojasa').datagrid('getEditor', {index: index, field:'prjasaid'});
								$(prjasaid.target).combogrid('grid').datagrid('reload');
							},
							onBeforeLoad: function(param){
								param.plantid = $('#poheader-plantid').combogrid('getValue');
								param.addressbookid = $('#poheader-addressbookid').combogrid('getValue');
								param.isjasa = 1;
							},
							onHidePanel: function() {
									var tr = $(this).closest('tr.datagrid-row');
									var index = parseInt(tr.attr('datagrid-row-index'));
									var productid = $('#dg-poheader-pojasa').datagrid('getEditor', {index: index, field:'productid'});
									var prheaderid = $('#dg-poheader-pojasa').datagrid('getEditor', {index: index, field:'prheaderid'});
									var prjasaid = $('#dg-poheader-pojasa').datagrid('getEditor', {index: index, field:'prjasaid'});									
									var qty = $('#dg-poheader-pojasa').datagrid('getEditor', {index: index, field:'qty'});
									var uomid = $('#dg-poheader-pojasa').datagrid('getEditor', {index: index, field:'uomid'});
									var slocid = $('#dg-poheader-pojasa').datagrid('getEditor', {index: index, field:'sloctoid'});
									var mesinid = $('#dg-poheader-pojasa').datagrid('getEditor', {index: index, field:'mesinid'});
									var reqdate = $('#dg-poheader-pojasa').datagrid('getEditor', {index: index, field:'reqdate'});
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
											$('#dg-poheader-podetail').datagrid('reload');
											$('#dg-poheader-poresult').datagrid('reload');
										} ,
										'cache':false});
							},
							fitColumns:true,
							required:false,
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
							readonly:false,
							textField:'productname',
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'productplantjasa'=>true)) ."',
							fitColumns:true,
							onHidePanel: function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-poheader-pojasa').datagrid('getEditor', {index: index, field:'productid'});
								var uomid = $('#dg-poheader-pojasa').datagrid('getEditor', {index: index, field:'uomid'})
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/product/getproductplant') ."',
									'data':{'productid':$(productid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(uomid.target).combogrid('setValue',data.uom1);
									} ,
									'cache':false});
							},
							required:false,
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
					field:'tolerance',
					title:'".GetCatalog('tolerance') ."',
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
					field:'reqdate',
					title:'".GetCatalog('reqdate')."',
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
										param.plantid = $('#poheader-plantid').combogrid('getValue');
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
					editor: {
						type: 'textbox',
						options: {
							multiline:true,
							height:40
						}
					},
					sortable: true,
					width:'100px',
					formatter: function(value,row,index){
										return value;
					}
				},
			"
		),
		array(
			'id'=>'poresult',
			'idfield'=>'poresultid',
			'urlsub'=>Yii::app()->createUrl('purchasing/poheader/indexporesult',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('purchasing/poheader/searchporesult',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('purchasing/poheader/saveporesult',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('purchasing/poheader/saveporesult',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('purchasing/poheader/purgeporesult',array('grid'=>true)),
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
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'poheaderid',
					title:'".GetCatalog('poheaderid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'poresultid',
					title:'".GetCatalog('poresultid') ."',
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
					field:'productcode',
					title:'".getCatalog('productcode') ."',
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
										return row.productcode;
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
					width:'450px',
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
					field:'description',
					title:'".GetCatalog('description')."',
					editor: {
						type: 'textbox',
						options: {
							multiline:true,
							height:40
						}
					},
					sortable: true,
					width:'100px',
					formatter: function(value,row,index){
										return value;
					}
				},
			"
		)
	),	
));