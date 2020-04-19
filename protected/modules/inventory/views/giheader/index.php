<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'giheaderid',
	'formtype'=>'masterdetail',
	'isxls'=>1,
	'ispost'=>1,
	'ispurge'=>0,
	'isupload'=>0,
	'isreject'=>1,
	'wfapp'=>'appgi',
	'url'=>Yii::app()->createUrl('inventory/giheader/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('inventory/giheader/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('inventory/giheader/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('inventory/giheader/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('inventory/giheader/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('inventory/giheader/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('inventory/giheader/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/giheader/downpdf'),
	'downxls'=>Yii::app()->createUrl('inventory/giheader/downxls'),
	'columns'=>"
		{
			field:'giheaderid',
			title:'".GetCatalog('giheaderid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appgi')."
		}},
		{
			field:'plantid',
			title:'".GetCatalog('plant') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
			return row.plantcode;
		}},
		{
			field:'gidate',
			title:'".GetCatalog('gidate') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'gino',
			title:'".GetCatalog('gino') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'addressbookid',
			title:'".GetCatalog('customer') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
			return row.customername;
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
			field:'pocustno',
			title:'".GetCatalog('pocustno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'supplierid',
			title:'".GetCatalog('ekspedisi') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
			return row.suppliername;
		}},
		{
			field:'nomobil',
			title:'".GetCatalog('nomobil') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'sopir',
			title:'".GetCatalog('sopir') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'addresstoid',
			title:'".GetCatalog('addressto') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
			return row.addressname;
		}},
		{
			field:'pebno',
			title:'".GetCatalog('pebno') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
			return value;
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
	'searchfield'=> array ('giheaderid','plantcode','gidate','gino','ekspedisi','sono','customer','sopir','pocustno','nomobil','addressname','productname','headernote','recordstatus'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('gino')."</td>
				<td><input class='easyui-textbox' id='giheader-gino' name='giheader-gino' data-options='readonly:true'></input></td>
			<td>".getCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='giheader-plantid' name='giheader-plantid' style='width:150px' data-options=\"
								panelWidth: '500px',
								idField: 'plantid',
								required: true,
								textField: 'plantcode',
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
				<td>".GetCatalog('gidate')."</td>
				<td><input class='easyui-datebox' id='giheader-gidate' name='giheader-gidate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
				<td>".GetCatalog('soheader')."</td>
				<td>
					<select class='easyui-combogrid' id='giheader-soheaderid' name='giheader-soheaderid' style='width:250px' data-options=\"
						panelWidth: '700px',
						required: true,
						idField: 'soheaderid',
						textField: 'sono',
						mode:'remote',
						url: '".Yii::app()->createUrl('order/soheader/index',array('grid'=>true,'giso'=>true)) ."',
						method: 'get',
						onShowPanel: function() {
							$('#giheader-soheaderid').combogrid('grid').datagrid('reload');
						},							
						onBeforeLoad: function(param) {
							param.plantid = $('#giheader-plantid').combogrid('getValue');
						},
						onHidePanel: function(){
							jQuery.ajax({'url':'".Yii::app()->createUrl('order/soheader/index',array('grid'=>true,'getdatasogi'=>true)) ."',
								'data':{
									'soheaderid':$('#giheader-soheaderid').combogrid('getValue'),
								},
								'type':'post',
								'dataType':'json',
								'success':function(data)
								{
									$('#giheader-addressbookid').combogrid('setValue',data.addressbookid);
									$('#giheader-addresstoid').combogrid('grid').datagrid('load', {
										addressbookid: $('#giheader-addressbookid').combogrid('getValue')
									});
									$('#giheader-addresstoid').combogrid('setValue',data.addresstoid);
									$('#giheader-pocustno').textbox('setValue',data.pocustno);
									jQuery.ajax({'url':'".Yii::app()->createUrl('inventory/giheader/generatedetail') ."',
										'data':{
											'id':$('#giheader-soheaderid').combogrid('getValue'),
											'hid':$('#giheader-giheaderid').val(),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#dg-giheader-gidetail').datagrid('reload');
										},
										'cache':false});
								},
								'cache':false});
						},
						columns: [[
								{field:'soheaderid',title:'".GetCatalog('soheaderid') ."',width:'50px'},
								{field:'sono',title:'".GetCatalog('sono') ."',width:'120px'},
								{field:'fullname',title:'".GetCatalog('fullname') ."',width:'200px'}
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('customer')."</td>
				<td>
					<select class='easyui-combogrid' id='giheader-addressbookid' name='giheader-addressbookid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/customer/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						readonly: true,
						columns: [[
							{field:'addressbookid',title:'".GetCatalog('addressbookid') ."',width:'50px'},
							{field:'fullname',title:'".GetCatalog('fullname') ."',width:'350px'},
						]],
						fitColumns: true\">
					</select>
				</td>
				<td>".GetCatalog('pocustno')."</td>
				<td><input class='easyui-textbox' id='giheader-pocustno' name='giheader-pocustno' data-options='required:false' style='width:150px;height:25px'></input></td>
<tr>
				<td>".GetCatalog('addressto')."</td>
				<td>
					<select class='easyui-combogrid' id='giheader-addresstoid' name='giheader-addresstoid' style='width:250px;height:100px' data-options=\"
						panelWidth: '500px',
						multiline:true,
						required: true,
						idField: 'addressid',
						textField: 'addressname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/customer/indexaddress',array('grid'=>true)) ."',
						method: 'get',
						onBeforeLoad: function(param) {
							param.addressbookid = $('#giheader-addressbookid').combogrid('getValue');
						},
						columns: [[
							{field:'addressid',title:'".GetCatalog('addressid') ."',width:'50px'},
							{field:'addressname',title:'".GetCatalog('addressname') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
				<td>".GetCatalog('ekspedisi')."</td>
				<td>
					<select class='easyui-combogrid' id='giheader-supplierid' name='giheader-supplierid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/supplier/index',array('grid'=>true,'expedisi'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'addressbookid',title:'".GetCatalog('addressbookid') ."',width:'50px'},
								{field:'fullname',title:'".GetCatalog('fullname') ."',width:'250px'},
						]],
						fitColumns: true \">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('nokendaraan')."</td>
				<td><input class='easyui-textbox' id='giheader-nomobil' name='giheader-nomobil' data-options='required:true'></input></td>
				<td>".GetCatalog('sopir')."</td>
				<td><input class='easyui-textbox' id='giheader-sopir' name='giheader-sopir' data-options='required:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('pebno')."</td>
				<td><input class='easyui-textbox' id='giheader-pebno' name='giheader-pebno' data-options='required:false'></input></td>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='giheader-headernote' name='giheader-headernote' data-options='multiline:true,required:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addload'=>"
		$('#giheader-gidate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});	",
	'loadsuccess' => "
		$('#giheader-gino').textbox('setValue',data.gino);
		$('#giheader-gidate').datebox('setValue',data.gidate);
		$('#giheader-plantid').combogrid('setValue',data.plantid);
		$('#giheader-soheaderid').combogrid('setValue',data.soheaderid);
		$('#giheader-pocustno').textbox('setValue',data.pocustno);
		$('#giheader-addressbookid').combogrid('setValue',data.addressbookid);
		$('#giheader-addresstoid').combogrid('setValue',data.addresstoid);
		$('#giheader-supplierid').combogrid('setValue',data.supplierid);
		$('#giheader-nomobil').textbox('setValue',data.nomobil);
		$('#giheader-sopir').textbox('setValue',data.sopir);
		$('#giheader-pebno').textbox('setValue',data.pebno);
		$('#giheader-headernote').textbox('setValue',data.headernote);
	",
	'columndetails'=> array (
		array(
			'id'=>'gidetail',
			'idfield'=>'gidetailid',
			'urlsub'=>Yii::app()->createUrl('inventory/giheader/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/giheader/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('inventory/giheader/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('inventory/giheader/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('inventory/giheader/purgedetail',array('grid'=>true)),
			'isnew'=>0,
			'subs'=>"
				{field:'gidetailid',title:'".GetCatalog('ID') ."',width:'80px'},
				{field:'sodetailid',title:'".GetCatalog('ID SO') ."',width:'80px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'300px'},
				{field:'stock',title:'".GetCatalog('stock') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'100px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:'".GetCatalog('uom2code') ."',width:'100px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom3code',title:'".GetCatalog('uom3code') ."',width:'100px'},
				{field:'qty4',title:'".GetCatalog('qty4') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom4code',title:'".GetCatalog('uom4code') ."',width:'100px'},
				{field:'sloccode',title:'".GetCatalog('sloc') ."',width:'100px'},
				{field:'storagebinto',title:'".GetCatalog('storagebin') ."',width:'100px'},
				{field:'lotno',title:'".GetCatalog('lotno') ."',width:'100px'},
				{field:'certoano',title:'".GetCatalog('certoano') ."',width:'100px'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'100px'},
			",
			'columns'=>"
				{
					field:'giheaderid',
					title:'".GetCatalog('giheaderid') ."',
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
					field:'gidetailid',
					title:'".GetCatalog('gidetailid') ."',
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
					field:'sodetailid',
					title:'".GetCatalog('ID SO') ."',
					editor: {
						type: 'textbox',
						options:{
							readonly:true,
						}
					},
					sortable: true,
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
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plant'=>true)) ."',
							onBeforeLoad: function(param){
								param.plantid = $('#giheader-plantid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							onChange:function(newValue,oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-giheader-gidetail').datagrid('getEditor', {index: index, field:'productid'});
								var uomid = $('#dg-giheader-gidetail').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-giheader-gidetail').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-giheader-gidetail').datagrid('getEditor', {index: index, field:'uom3id'});
								var uom4id = $('#dg-giheader-gidetail').datagrid('getEditor', {index: index, field:'uom4id'});
								var stdqty = $('#dg-giheader-gidetail').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-giheader-gidetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-giheader-gidetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-giheader-gidetail').datagrid('getEditor', {index: index, field:'stdqty4'});													
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/index',array('grid'=>true,'getdata'=>true)) ."',
									'data':{'productid':$(productid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(uomid.target).combogrid('setValue',data.uom1);
										$(uom2id.target).combogrid('setValue',data.uom2);
										$(uom3id.target).combogrid('setValue',data.uom3);
										$(uom4id.target).combogrid('setValue',data.uom4);
										$(stdqty.target).numberbox('setValue',data.qty);
										$(stdqty2.target).numberbox('setValue',data.qty2);
										$(stdqty3.target).numberbox('setValue',data.qty3);
										$(stdqty4.target).numberbox('setValue',data.qty4);
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
					width:'300px',
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
							required:true,
							onChange: function(newValue,oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var stdqty = $('#dg-giheader-gidetail').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-giheader-gidetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-giheader-gidetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-giheader-gidetail').datagrid('getEditor', {index: index, field:'stdqty4'});								
								var qty2 = $('#dg-giheader-gidetail').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-giheader-gidetail').datagrid('getEditor', {index: index, field:'qty3'});								
								var qty4 = $('#dg-giheader-gidetail').datagrid('getEditor', {index: index, field:'qty4'});						
								$(qty2.target).numberbox('setValue',$(stdqty2.target).numberbox('getValue') * (newValue / $(stdqty.target).numberbox('getValue')));
								$(qty3.target).numberbox('setValue',$(stdqty3.target).numberbox('getValue') * (newValue / $(stdqty.target).numberbox('getValue')));
								$(qty4.target).numberbox('setValue',$(stdqty4.target).numberbox('getValue') * (newValue / $(stdqty.target).numberbox('getValue')));
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
									idField:'unitofmeasureid',
									textField:'uomcode',
									url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
									fitColumns:true,
									required:true,
									hasDownArrow:false,
									readonly:true,
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
									idField:'unitofmeasureid',
									textField:'uomcode',
									url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
									fitColumns:true,
									required:true,
									hasDownArrow:false,
									readonly:true,
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
									idField:'unitofmeasureid',
									textField:'uomcode',
									url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
									fitColumns:true,
									hasDownArrow:false,
									readonly:true,
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
									idField:'unitofmeasureid',
									textField:'uomcode',
									url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
									fitColumns:true,
									hasDownArrow:false,
									readonly:true,
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
								var slocid = $('#dg-giheader-gidetail').datagrid('getEditor', {index: index, field:'slocid'});
								$(slocid.target).combogrid('grid').datagrid('reload');
							},
							onBeforeLoad: function(param){
								param.plantid = $('#giheader-plantid').combogrid('getValue');
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
					field:'storagebinid',
					title:'".getCatalog('storagebin') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'550px',
							mode : 'remote',
							method:'get',
							idField:'storagebinid',
							textField:'description',
							url:'".Yii::app()->createUrl('common/storagebin/indexcombosloc',array('grid'=>true)) ."',
							onShowPanel: function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var slocid = $('#dg-giheader-gidetail').datagrid('getEditor', {index: index, field:'slocid'});
								var storagebinid = $('#dg-giheader-gidetail').datagrid('getEditor', {index: index, field:'storagebinid'});
								$(storagebinid.target).combogrid('grid').datagrid('load',{
									slocid:$(slocid.target).combogrid('getValue')
								});
							},
							fitColumns:true,
							required:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'storagebinid',title:'".getCatalog('storagebinid')."',width:'80px'},
								{field:'description',title:'".getCatalog('description')."',width:'150px'},
							]]
						}	
					},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
						return row.storagebinto;
					}
				},
				{
					field:'lotno',
					title:'".GetCatalog('lotno')."',
					editor: {
						type: 'textbox',
						options:{
							
						}
					},
					sortable: true,
					width:'200px',
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'certoaid',
					title:'".GetCatalog('certoa')."',
					editor: {
						type: 'textbox',
						options:{
							
						}
					},
					sortable: true,
					width:'200px',
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
			array(
				'id'=>'gijurnal',
				'idfield'=>'gijurnalid',
				'isnew'=>0,
				'ispurge'=>0,
				'urlsub'=>Yii::app()->createUrl('inventory/giheader/indexjurnal',array('grid'=>true)),
				'url'=>Yii::app()->createUrl('inventory/giheader/searchjurnal',array('grid'=>true)),
				'subs'=>"
					{field:'gijurnalid',title:'".getCatalog('ID') ."',width:'60px'},
					{field:'accountid',title:'".GetCatalog('account') ."',width:'250px',
						formatter: function(value,row,index){
							return row.accountname;
						},				
					},
					{field:'debit',title:'".GetCatalog('debit') ."',
						formatter: function(value,row,index){
							return formatnumber('',value);
						},width:'100px'},
					{field:'credit',title:'".GetCatalog('credit') ."',
						formatter: function(value,row,index){
							return formatnumber('',value);
						},width:'100px'},
					{field:'currencyid',title:'".GetCatalog('currency') ."',width:'250px',
						formatter: function(value,row,index){
							return row.currencyname;
						},				
					},
					{field:'detailnote',title:'".GetCatalog('detailnote') ."',width:'250px'},
				",
			),
	),	
));