<input type='hidden' id='soheader-productid' name='soheader-productid' value=''></input>
<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'soheaderid',
	'formtype'=>'masterdetail',
	'isxls'=>1,
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appso',
	'url'=>Yii::app()->createUrl('order/soheader/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('order/soheader/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('order/soheader/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('order/soheader/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('order/soheader/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('order/soheader/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('order/soheader/reject',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('order/soheader/upload'),
	'downpdf'=>Yii::app()->createUrl('order/soheader/downpdf'),
	'downxls'=>Yii::app()->createUrl('order/soheader/downxls'),
	'columns'=>"
		{
			field:'soheaderid',
			title:'".GetCatalog('ID') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appso')."
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
			field:'sodate',
			title:'".GetCatalog('sodate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'pocustdate',
			title:'".GetCatalog('pocustdate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'sono',
			title:'".GetCatalog('sono') ."',
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
			field:'pocustno',
			title:'".GetCatalog('pocustno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'employeeid',
			title:'".GetCatalog('sales') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.sales;
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
		$('#soheader-sodate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});	
		$('#soheader-currencyid').combogrid('setValue',40);
		$('#soheader-currencyrate').numberbox('setValue',1);
	",
	'postbuttons'=>"
		<a href='javascript:void(0)' title='".getCatalog('hold')."' class='easyui-linkbutton' iconCls='icon-lock' plain='true' onclick='holdso()'></a>
		<a href='javascript:void(0)' title='".getCatalog('open')."' class='easyui-linkbutton' iconCls='icon-more' plain='true' onclick='openso()'></a>
		<a href='javascript:void(0)' title='".getCatalog('close')."' class='easyui-linkbutton' iconCls='icon-tip' plain='true' onclick='closeso()'></a>
	",
	'addonscripts'=>"
		function holdso() {
			var rows = $('#dg-soheader').edatagrid('getSelected');
			jQuery.ajax({'url':'".$this->createUrl('soheader/holdso') ."',
				'data':{'id':rows.soheaderid},'type':'post','dataType':'json',
				'success':function(data)
				{
					show('Pesan',data.msg);
					$('#dg-soheader').edatagrid('reload');				
				} ,
				'cache':false});
		};
		function closeso() {
			var rows = $('#dg-soheader').edatagrid('getSelected');
			jQuery.ajax({'url':'".$this->createUrl('soheader/closeso') ."',
				'data':{'id':rows.soheaderid},'type':'post','dataType':'json',
				'success':function(data)
				{
					show('Pesan',data.msg);
					$('#dg-soheader').edatagrid('reload');				
				} ,
				'cache':false});
		};
		function openso() {
			var rows = $('#dg-soheader').edatagrid('getSelected');
			jQuery.ajax({'url':'".$this->createUrl('soheader/openso') ."',
				'data':{'id':rows.soheaderid},'type':'post','dataType':'json',
				'success':function(data)
				{
					show('Pesan',data.msg);
					$('#dg-soheader').edatagrid('reload');				
				} ,
				'cache':false});
		};
	",
	'searchfield'=> array ('soheaderid','plantcode','sodate','sono','customer','sales','pocustno','headernote','productname','recordstatus'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('sono')."</td>
				<td><input class='easyui-textbox' id='soheader-sono' name='soheader-sono' data-options=''></input></td>
				<td>".GetCatalog('sodate')."</td>
				<td><input class='easyui-datebox' id='soheader-sodate' name='soheader-sodate' style='width:250px' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='soheader-plantid' name='soheader-plantid' style='width:250px' data-options=\"
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
				<td>".GetCatalog('customer')."</td>
				<td>
					<select class='easyui-combogrid' id='soheader-addressbookid' name='soheader-addressbookid' style='width:250px' data-options=\"
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
									'addressbookid':$('#soheader-addressbookid').combogrid('getValue'),
								},
								'type':'post',
								'dataType':'json',
								'success':function(data)
								{
									$('#soheader-paymentmethodid').combogrid('setValue',data.paymentmethodid);
									$('#soheader-addresstoid').combogrid('grid').datagrid('reload');
									$('#soheader-addresspayid').combogrid('grid').datagrid('reload');
									$('#soheader-addresspayid').combogrid('setValue',data.addresspayid);
									$('#soheader-addresstoid').combogrid('setValue',data.addresstoid);
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
				</tr>
				<tr>
				<td>".GetCatalog('pocustno')."</td>
				<td><input class='easyui-textbox' id='soheader-pocustno' name='soheader-pocustno' data-options='required:false' style='width:150px;height:25px'></input></td>
				<td>".GetCatalog('pocustdate')."</td>
				<td><input class='easyui-datebox' id='soheader-pocustdate' name='soheader-pocustdate' style='width:250px' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
				</tr>			
			<tr>
				<td>".GetCatalog('addressto')."</td>
				<td>
					<select class='easyui-combogrid' id='soheader-addresstoid' name='soheader-addresstoid' style='width:250px;height:150px' data-options=\"
						panelWidth: '500px',
						multiline:true,
						required: true,
						idField: 'addressid',
						textField: 'addressname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/customer/indexaddressto',array('grid'=>true)) ."',
						method: 'get',
						onShowPanel: function() {
							$('#soheader-addresstoid').combogrid('grid').datagrid('reload');
						},
						onBeforeLoad: function(param){
							param.id = $('#soheader-addressbookid').combogrid('getValue');
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
					<select class='easyui-combogrid' id='soheader-addresspayid' name='soheader-addresspayid' style='width:250px;height:150px' data-options=\"
						panelWidth: '500px',
						required: true,
						multiline:true,
						idField: 'addressid',
						textField: 'addressname',
						mode:'remote',
						onShowPanel: function() {
							$('#soheader-addresspayid').combogrid('grid').datagrid('reload');
						},
						onBeforeLoad: function(param){
							param.id = $('#soheader-addressbookid').combogrid('getValue');
						},
						url: '".Yii::app()->createUrl('common/customer/indexaddresspay',array('grid'=>true)) ."',
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
					<select class='easyui-combogrid' id='soheader-salesid' name='soheader-salesid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'salesid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('order/sales/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						onShowPanel:function() {
							$('#soheader-salesid').combogrid('grid').datagrid('reload');
						},
						onBeforeLoad:function(param) {
							param.plantid= $('#soheader-plantid').combogrid('getValue');
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
					<select class='easyui-combogrid' id='soheader-paymentmethodid' name='soheader-paymentmethodid' style='width:250px' data-options=\"
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
					<select class='easyui-combogrid' id='soheader-currencyid' name='soheader-currencyid' style='width:250px' data-options=\"
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
				<td><input class='easyui-numberbox' id='soheader-currencyrate' name='soheader-currencyrate' data-options=\"required:true,value:'1'\" style='width:100px;height:25px'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='soheader-headernote' name='soheader-headernote' data-options='multiline:true' style='width:300px;height:100px'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('isexport')."</td>
				<td><input class='easyui-checkbox' name='soheader-isexport' id='soheader-isexport'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('issample')."</td>
				<td><input class='easyui-checkbox' name='soheader-issample' id='soheader-issample'></input></td>
			</tr>
			<tr>
			<td>".GetCatalog('isavalan')."</td>
			<td><input class='easyui-checkbox' name='soheader-isavalan' id='soheader-isavalan'></input></td>
			</tr>
		</table>
	",
	'loadsuccess'=>"
		$('#soheader-sodate').datebox('setValue',data.sodate);
		$('#soheader-sono').textbox('setValue',data.sono);
		$('#soheader-plantid').combogrid('setValue',data.plantid);
		$('#soheader-addressbookid').combogrid('setValue',data.addressbookid);
		$('#soheader-pocustno').textbox('setValue',data.pocustno);
		$('#soheader-pocustdate').datebox('setValue',data.pocustdate);
		$('#soheader-addresstoid').combogrid('setValue',data.addresstoid);
		$('#soheader-addresspayid').combogrid('setValue',data.addresspayid);
		$('#soheader-salesid').combogrid('setValue',data.salesid);
		$('#soheader-paymentmethodid').combogrid('setValue',data.paymentmethodid);
		$('#soheader-currencyid').combogrid('setValue',data.currencyid);
		$('#soheader-currencyrate').numberbox('setValue',data.currencyrate);
		$('#soheader-headernote').textbox('setValue',data.headernote);
		if (data.isexport == 1)
		{
			$('#soheader-isexport').prop('checked', true);
		} else
		{
			$('#soheader-isexport').prop('checked', false);
		}
		if (data.issample == 1)
		{
			$('#soheader-issample').prop('checked', true);
		} else
		{
			$('#soheader-issample').prop('checked', false);
		}
		if (data.isavalan == 1)
		{
			$('#soheader-isavalan').prop('checked', true);
		} else
		{
			$('#soheader-isavalan').prop('checked', false);
		}
	",
	'columndetails'=> array (
		array(
			'id'=>'taxso',
			'idfield'=>'taxsoid',
			'urlsub'=>Yii::app()->createUrl('order/soheader/indextaxso',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('order/soheader/searchtaxso',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('order/soheader/savetaxso',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('order/soheader/savetaxso',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('order/soheader/purgetaxso',array('grid'=>true)),
			'subs'=>"
				{field:'taxcode',title:'".GetCatalog('taxcode') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'taxsoid',
					title:'".GetCatalog('taxsoid') ."',
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
					field:'soheaderid',
					title:'".GetCatalog('soheaderid') ."',
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
							panelWidth:'600px',
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
								{field:'taxvalue',title:'".GetCatalog('taxvalue')."',width:'150px',},
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
			'id'=>'sodetail',
			'idfield'=>'sodetailid',
			'urlsub'=>Yii::app()->createUrl('order/soheader/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('order/soheader/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('order/soheader/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('order/soheader/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('order/soheader/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'sodetailid',title:'".GetCatalog('sodetailid') ."',width:'120px'},
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
				{field:'giqty',title:'".GetCatalog('giqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'sppqty',title:'".GetCatalog('sppqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'opqty',title:'".GetCatalog('qtyprod') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:'".GetCatalog('uom2code') ."',width:'80px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom3code',title:'".GetCatalog('uom3code') ."',width:'80px'},"		
				.((GetMenuAuth('currency') != 0)?"
				{field:'price',title:'".GetCatalog('price') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'totprice',title:'".GetCatalog('totprice') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},":'')."
				{field:'bomversion',title:'".GetCatalog('bomversion') ."',width:'200px'},
				{field:'toleransi',title:'".GetCatalog('toleransi') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'delvdate',title:'".GetCatalog('delvdate') ."',width:'80px'},
				{field:'sloccode',title:'".GetCatalog('sloc') ."',width:'100px'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'100px'},
			",
			'columns'=>"
				{
					field:'soheaderid',
					title:'".GetCatalog('soheaderid') ."',
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
					field:'sodetailid',
					title:'".GetCatalog('sodetailid') ."',
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
							panelWidth:'750px',
							mode: 'remote',
							method:'get',
							idField:'productid',
							textField:'productname',
							required:true,
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plantfg'=>true)) ."',
							onShowPanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-soheader-sodetail').datagrid('getEditor', {index: index, field:'productid'});
								$(productid.target).combogrid('grid').datagrid('reload');
							},
							onBeforeLoad: function(param){
								param.plantid = $('#soheader-plantid').combogrid('getValue');
								param.addressbookid = $('#soheader-addressbookid').combogrid('getValue');
								param.issource = 0;
							},
							fitColumns:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-soheader-sodetail').datagrid('getEditor', {index: index, field:'productid'});
								var uomid = $('#dg-soheader-sodetail').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-soheader-sodetail').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-soheader-sodetail').datagrid('getEditor', {index: index, field:'uom3id'});
								var bomid = $('#dg-soheader-sodetail').datagrid('getEditor', {index: index, field:'bomid'});
								var stdqty = $('#dg-soheader-sodetail').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-soheader-sodetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-soheader-sodetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var price = $('#dg-soheader-sodetail').datagrid('getEditor', {index: index, field:'price'});
								var slocid = $('#dg-soheader-sodetail').datagrid('getEditor', {index: index, field:'slocid'});
								$(bomid.target).combogrid('grid').datagrid('reload');
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/product/getproductplant') ."',
									'data':{'productid':$(productid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(uomid.target).combogrid('setValue',data.uom1);
										$(uom2id.target).combogrid('setValue',data.uom2);
										$(uom3id.target).combogrid('setValue',data.uom3);
										$(bomid.target).combogrid('setValue',data.bomid);
										$(stdqty.target).numberbox('setValue',data.qty1);
										$(stdqty2.target).numberbox('setValue',data.qty2);
										$(stdqty3.target).numberbox('setValue',data.qty3);
										$(slocid.target).combogrid('setValue',data.slocid);
										$('#soheader-productid').val(data.productid);
									} ,
									'cache':false});
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/product/getprice') ."',
									'data':{'productid':$(productid.target).combogrid('getValue'),
										'addressbookid':$('#soheader-addressbookid').combogrid('getValue')},
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
								var stdqty = $('#dg-soheader-sodetail').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-soheader-sodetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-soheader-sodetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var qty2 = $('#dg-soheader-sodetail').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-soheader-sodetail').datagrid('getEditor', {index: index, field:'qty3'});													
								$(qty2.target).numberbox('setValue',$(stdqty2.target).numberbox('getValue') * (newValue / $(stdqty.target).numberbox('getValue')));
								$(qty3.target).numberbox('setValue',$(stdqty3.target).numberbox('getValue') * (newValue / $(stdqty.target).numberbox('getValue')));
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
					title:'".GetCatalog('uom3') ."',
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
					field:'bomid',
					title:'".GetCatalog('bom') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'bomid',
							textField:'bomversion',
							url:'".Yii::app()->createUrl('production/bom/index',array('grid'=>true,'combo'=>true)) ."',
							onShowPanel: function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var bomid = $('#dg-soheader-sodetail').datagrid('getEditor', {index: index, field:'bomid'});
								$(bomid.target).combogrid('grid').datagrid('reload');
							},
							onBeforeLoad: function(param){
								param.plantid = $('#soheader-plantid').combogrid('getValue');
								param.addressbookid = $('#soheader-addressbookid').combogrid('getValue');
								param.productid = $('#soheader-productid').val();
							},
							fitColumns:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'bomid',title:'".GetCatalog('bomid')."',width:'50px'},
								{field:'bomversion',title:'".GetCatalog('bomversion')."',width:'250px'},
							]]
						}	
					},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
										return row.bomversion;
					}
				},
				{
					field:'toleransi',
					title:'".GetCatalog('toleransi') ."',
					width:'100px',
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
						return formatnumber('',value);
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
							url:'".Yii::app()->createUrl('common/sloc/indextrxplantsloc',array('grid'=>true)) ."',
							onShowPanel: function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var slocid = $('#dg-soheader-sodetail').datagrid('getEditor', {index: index, field:'slocid'});
								$(slocid.target).combogrid('grid').datagrid('reload');
							},
							onBeforeLoad: function(param){
								param.plantid = $('#soheader-plantid').combogrid('getValue');
								param.productid = $('#soheader-productid').val();
							},
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
					field:'delvdate',
					title:'".GetCatalog('delvdate')."',
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