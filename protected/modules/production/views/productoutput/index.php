<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'productoutputid',
	'formtype'=>'masterdetail',
	'isxls'=>1,
	'ispost'=>1,
	'isreject'=>1,
	'isupload'=>1,
	'ispurge'=>1,
	'wfapp'=>'appop',
	'url'=>Yii::app()->createUrl('production/productoutput/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('production/productoutput/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('production/productoutput/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('production/productoutput/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('production/productoutput/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('production/productoutput/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('production/productoutput/reject',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('production/productoutput/upload'),
	'downpdf'=>Yii::app()->createUrl('production/productoutput/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/productoutput/downxls'),
	'columns'=>"
		{
			field:'productoutputid',
			title:'".GetCatalog('ID') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appop')."
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
			field:'productoutputdate',
			title:'".GetCatalog('productoutputdate') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productoutputno',
			title:'".GetCatalog('productoutputno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productplanid',
			title:'".GetCatalog('productplanno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.productplanno;
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
			field:'addressbookid',
			title:'".GetCatalog('customer') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'headernote',
			title:'".GetCatalog('headernote') ."',
			sortable: true,
			width:'250px',
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
		$('#productoutput-productoutputdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
	",
	'searchfield'=> array ('productoutputid','plantcode','productoutputno','productoutputdate','sono','productplanno','customer','headernote',
		'processprdname','sloccode','recordstatus'),
	'headerform'=> "
		<table cellpadding='5'>
		<tr>
				<td>".GetCatalog('productoutputno')."</td>
				<td><input class='easyui-textbox' id='productoutput-productoutputno' name='productoutput-productoutputno' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('productoutputdate')."</td>
				<td><input class='easyui-datebox' id='productoutput-productoutputdate' name='productoutput-productoutputdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='productoutput-plantid' name='productoutput-plantid' style='width:150px' data-options=\"
								panelWidth: '500px',
								idField: 'plantid',
								required: true,
								textField: 'plantcode',
								mode: 'remote',
								url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'auth'=>true)) ."',
								method: 'get',
								onHidePanel: function(){
									jQuery.ajax({'url':'".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'getdata'=>true)) ."',
										'data':{
											'plantid':$('#productoutput-plantid').combogrid('getValue'),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											var h = $('#productplanid').combogrid('grid');
											h.datagrid({queryParams: {
												plantid: data.plantid}
											});
										},
										'cache':false});
								},
								columns: [[
										{field:'plantid',title:'".getCatalog('plantid') ."',width:'50px'},
										{field:'plantcode',title:'".getCatalog('plantcode') ."',width:'150px'},
										{field:'description',title:'".getCatalog('description') ."',width:'200px'},
								]],
								fitColumns: true
						\">
				</select></td>
			<td>".GetCatalog('productplan')."</td>
				<td>
					<select class='easyui-combogrid' id='productoutput-productplanid' name='productoutput-productplanid' style='width:250px' data-options=\"
						panelWidth: '700px',
						idField: 'productplanid',
						textField: 'productplanno',
						required:true,
						mode:'remote',
						url: '".Yii::app()->createUrl('production/productplan/index',array('grid'=>true,'ppop'=>true)) ."',
						method: 'get',
						onShowPanel: function() {
							$('#productoutput-productplanid').combogrid('grid').datagrid('reload');
						},							
						onBeforeLoad: function(param) {
							param.plantid = $('#productoutput-plantid').combogrid('getValue');
						},
						onHidePanel: function(){
							jQuery.ajax({'url':'".Yii::app()->createUrl('production/productplan/index',array('grid'=>true,'getdata'=>true)) ."',
								'data':{
									'productplanid':$('#productoutput-productplanid').combogrid('getValue')
								},
								'type':'post',
								'dataType':'json',
								'success':function(data)
								{
									$('#productoutput-addressbookid').combogrid('setValue',data.addressbookid);
									$('#productoutput-soheaderid').combogrid('setValue',data.soheaderid);
									$('#productoutput-description').combogrid('setValue',data.description);
									jQuery.ajax({'url':'".Yii::app()->createUrl('production/productoutput/generatedetail') ."',
										'data':{
											'id':$('#productoutput-productplanid').combogrid('getValue'),
											'hid':$('#productoutput-productoutputid').val(),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#dg-productoutput-productoutputdetail').datagrid('reload');
											$('#dg-productoutput-productoutputfg').datagrid('reload');
											$('#dg-productoutput-productoutputemployee').datagrid('reload');
											
										},
										'cache':false});
								},
								'cache':false});
						},
						columns: [[
								{field:'productplanid',title:'".GetCatalog('productplanid') ."',width:'50px'},
								{field:'productplanno',title:'".GetCatalog('productplanno') ."',width:'150px'},
								{field:'fullname',title:'".GetCatalog('fullname') ."',width:'350px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('soheader')."</td>
				<td>
					<select class='easyui-combogrid' id='productoutput-soheaderid' name='productoutput-soheaderid' style='width:250px' data-options=\"
						panelWidth: '500px',
						readonly: true,
						idField: 'soheaderid',
						textField: 'sono',
						mode:'remote',
						url: '".Yii::app()->createUrl('order/soheader/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'soheaderid',title:'".GetCatalog('soheaderid') ."',width:'50px'},
								{field:'sono',title:'".GetCatalog('sono') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('customer')."</td>
				<td>
					<select class='easyui-combogrid' id='productoutput-addressbookid' name='productoutput-addressbookid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/customer/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'addressbookid',title:'".GetCatalog('addressbookid') ."',width:'50px'},
								{field:'fullname',title:'".GetCatalog('fullname') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='productoutput-headernote' name='productoutput-headernote' data-options='required:false,multiline:true' style='width:300px;height:100px'/></td>
			</tr>
		</table>
	",
	'loadsuccess' => "
		/*$('#productoutput-productoutputno').textbox('setValue',data.productoutputno);
		$('#productoutput-productoutputdate').datebox('setValue',data.productoutputdate);
		$('#productoutput-plantid').combogrid('setValue',data.plantid);
		$('#productoutput-soheaderid').combogrid('setValue',data.soheaderid);
		$('#productoutput-addressbookid').combogrid('setValue',data.addressbookid);
		$('#productoutput-headernote').textbox('setValue',data.headernote);*/
	",
	'columndetails'=> array (
		array(
			'id'=>'productoutputfg',
			'idfield'=>'productoutputfgid',
			'urlsub'=>Yii::app()->createUrl('production/productoutput/indexfg',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('production/productoutput/searchfg',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('production/productoutput/savefg',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('production/productoutput/savefg',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('production/productoutput/purgedetailfg',array('grid'=>true)),
			'isnew'=>0,
			'subs'=>"
				{field:'productoutputfgid',title:'".getCatalog('ID FG') ."',width:'60px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
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
				{field:'uom3code',title:'".GetCatalog('uom3code') ."',width:'80px'},
				{field:'qty4',title:'".GetCatalog('qty4') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom4code',title:'".GetCatalog('uom4code') ."',width:'80px'},
				{field:'fullname',title:'".GetCatalog('fullname') ."',width:'100px'},
				{field:'processprdname',title:'".GetCatalog('processprdname') ."',width:'100px'},
				{field:'namamesin',title:'".GetCatalog('mesin') ."',width:'150px'},
				{field:'sloccode',title:'".GetCatalog('sloccode') ."',width:'100px'},
				{field:'storagebinname',title:'".GetCatalog('storagebindesc') ."',width:'100px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'100px'}
			",
			'ondestroy'=>"
				$('#dg-productoutput-productoutputdetail').edatagrid('reload');
				$('#dg-productoutput-productoutputwaste').edatagrid('reload');
				$('#dg-productoutput-productoutputemployee').edatagrid('reload');
			",
			'columns'=>"
				{
					field:'productoutputid',
					title:'".GetCatalog('productoutputid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'productoutputfgid',
					title:'".GetCatalog('productoutputfg') ."',
					readonly:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'productplanfgid',
					title:'".GetCatalog('productplanfgid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'sodetailid',
					title:'".GetCatalog('sodetailid') ."',
					hidden:true,
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
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plantplanhp'=>true)) ."',
							onBeforeLoad: function(param){
								param.plantid = $('#productoutput-plantid').combogrid('getValue');
								param.addressbookid = $('#productoutput-addressbookid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							onChange:function(newValue,oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-productoutput-productoutputfg').datagrid('getEditor', {index: index, field:'productid'});
								var uomid = $('#dg-productoutput-productoutputfg').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-productoutput-productoutputfg').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-productoutput-productoutputfg').datagrid('getEditor', {index: index, field:'uom3id'});
								var uom4id = $('#dg-productoutput-productoutputfg').datagrid('getEditor', {index: index, field:'uom4id'});
								var stdqty = $('#dg-productoutput-productoutputfg').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-productoutput-productoutputfg').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-productoutput-productoutputfg').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-productoutput-productoutputfg').datagrid('getEditor', {index: index, field:'stdqty4'});
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
								var stdqty = $('#dg-productoutput-productoutputfg').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-productoutput-productoutputfg').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-productoutput-productoutputfg').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-productoutput-productoutputfg').datagrid('getEditor', {index: index, field:'stdqty4'});								
								var qty2 = $('#dg-productoutput-productoutputfg').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-productoutput-productoutputfg').datagrid('getEditor', {index: index, field:'qty3'});								
								var qty4 = $('#dg-productoutput-productoutputfg').datagrid('getEditor', {index: index, field:'qty4'});						
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
					width:'80px',
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
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uom4code;
					}
				},
				{
					field:'employeeid',
					title:'".getCatalog('Supervisor') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'550px',
							mode : 'remote',
							method:'get',
							idField:'employeeid',
							textField:'fullname',
							url:'".Yii::app()->createUrl('hr/employee/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'employeeid',title:'".getCatalog('employeeid')."',width:'80px'},
								{field:'fullname',title:'".getCatalog('fullname')."',width:'80px'},
								{field:'oldnik',title:'".getCatalog('oldnik')."',width:'150px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return row.fullname;
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
										param.plantid = $('#productoutput-plantid').combogrid('getValue');
									},
									fitColumns:true,
									required:true,
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
								var slocid = $('#dg-productoutput-productoutputfg').datagrid('getEditor', {index: index, field:'slocid'});
								var storagebinid = $('#dg-productoutput-productoutputfg').datagrid('getEditor', {index: index, field:'storagebinid'});
								$(storagebinid.target).combogrid('grid').datagrid('load',{
									slocid:$(slocid.target).combogrid('getValue')
								});
							},
							fitColumns:true,
							required:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'storagebinid',title:'".getCatalog('storagebinid')."',width:'80px'},
								{field:'description',title:'".getCatalog('description')."',width:'250px'},
							]]
						}	
					},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
						return row.storagebinname;
					}
				},
				{
					field:'processprdid',
					title:'".GetCatalog('processprd') ."',
					editor:{
							type:'combogrid',
							options:{
									panelWidth:'500px',
									mode : 'remote',
									method:'get',
									idField:'processprdid',
									textField:'processprdname',
									url:'".Yii::app()->createUrl('production/processprd/index',array('grid'=>true,'combo'=>true)) ."',
									fitColumns:true,
									required:true,
									loadMsg: '".GetCatalog('pleasewait')."',
									columns:[[
										{field:'processprdid',title:'".GetCatalog('processprdid')."',width:'50px'},
										{field:'processprdname',title:'".GetCatalog('processprdname')."',width:'250px'},
									]]
							}	
						},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.processprdname;
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
							url:'".Yii::app()->createUrl('common/mesin/index',array('grid'=>true,'mesinplant'=>true)) ."',
							onBeforeLoad: function(param) {
								param.plantid = $('#productoutput-plantid').combogrid('getValue');
							},
							fitColumns:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'mesinid',title:'".getCatalog('mesinid')."',width:'80px'},
								{field:'kodemesin',title:'".getCatalog('kodemesin')."',width:'150px'},
								{field:'namamesin',title:'".getCatalog('namamesin')."',width:'200px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return row.namamesin;
					}
				},
				{
					field:'shift',
					title:'".GetCatalog('shift') ."',
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
					field:'efektivitas',
					title:'".GetCatalog('efektivitas') ."',
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
					field:'angkatan',
					title:'".GetCatalog('angkatan') ."',
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
					field:'description',
					title:'".GetCatalog('description')."',
					editor: 'textbox',
					sortable: true,
					width:'200px',
					formatter: function(value,row,index){
										return value;
					}
				},
			"
		),
		array(
			'id'=>'productoutputdetail',
			'idfield'=>'productoutputdetailid',
			'urlsub'=>Yii::app()->createUrl('production/productoutput/indexdetail',array('grid'=>true)),
			'url'=> Yii::app()->createUrl('production/productoutput/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('production/productoutput/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('production/productoutput/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('production/productoutput/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'productoutputdetailid',title:'".GetCatalog('ID Detail') ."',width:'50px'},
				{field:'productoutputfgid',title:'".GetCatalog('ID FG') ."',width:'50px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qtystock',title:'".GetCatalog('qtystock') ."',
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
				{field:'bomversion',title:'".GetCatalog('bomversion') ."',width:'200px'},
				{field:'slocfromcode',title:'".GetCatalog('slocfromcode') ."',width:'150px'},
				{field:'sloctocode',title:'".GetCatalog('sloctocode') ."',width:'150px'},
				{field:'storagebinto',title:'".GetCatalog('storagebinto') ."',width:'150px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'250px'},
			",
			'onbeginedit'=>"
				row.productoutputid = $('#productoutputid').val();
				var rowx = $('#dg-productoutput-productoutputdetail').edatagrid('getSelected');
				if (rowx)
				{
					row.productoutputdetailid = rowx.productoutputdetailid;
				}
			",
			'columns'=>"
			{
				field:'productoutputdetailid',
				title:'".GetCatalog('productoutputdetailid') ."',
				sortable: true,
				hidden:true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'productoutputid',
				title:'".GetCatalog('productoutputid') ."',
				hidden:true,
				sortable: true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'productoutputdetailid',
				title:'".GetCatalog('productoutputdetailid') ."',
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
					field:'productoutputfgid',
					title:'".GetCatalog('ID FG') ."',
					editor:{
							type:'combogrid',
							options:{
								panelWidth:'500px',
								mode : 'remote',
								method:'get',
								idField:'productoutputfgid',
								textField:'productname',
								url:'".Yii::app()->createUrl('production/productoutput/indexfg',array('grid'=>true,'combo'=>true)) ."',
								onShowPanel: function() {
									var tr = $(this).closest('tr.datagrid-row');
									var index = parseInt(tr.attr('datagrid-row-index'));
									var productoutputfgid = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'productoutputfgid'});
									$(productoutputfgid.target).combogrid('grid').datagrid('load',{
										productoutputid:$('#productoutput-productoutputid').val()
									});
								},
								fitColumns:true,
								required:true,
								loadMsg: '".GetCatalog('pleasewait')."',
								columns:[[
									{field:'productoutputfgid',title:'".GetCatalog('productoutputfgid')."',width:'50px'},
									{field:'productname',title:'".GetCatalog('productname')."',width:'450px'},
								]]
							}	
						},
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
										return row.productoutputfgid;
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
								param.plantid = $('#productoutput-plantid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							onChange:function(newValue,oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'productid'});
								var uomid = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'uom3id'});
								var uom4id = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'uom4id'});
								var stdqty = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'stdqty4'});
								var bomid = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'bomid'});														
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
										$(bomid.target).combogrid('setValue',data.bomid);
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
								var stdqty = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'stdqty4'});								
								var qty2 = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'qty3'});								
								var qty4 = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'qty4'});						
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
						url:'".$this->createUrl('bom/index',array('grid'=>true,'combo'=>true)) ."',
						fitColumns:true,
						width:'280px',
						loadMsg: '".GetCatalog('pleasewait')."',
						columns:[[
							{field:'bomid',title:'".GetCatalog('bomid')."',width:'50px'},
							{field:'bomversion',title:'".GetCatalog('bomversion')."',width:'200px'},
							{field:'productname',title:'".GetCatalog('productname')."',width:'250px'},
						]]
					}	
				},
				width:'300px',
				sortable: true,
				formatter: function(value,row,index){
					return row.bomversion;
				}
			},
			{
					field:'slocfromid',
					title:'".GetCatalog('slocfrom') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'slocid',
							textField:'sloccode',
							url:'".Yii::app()->createUrl('common/sloc/indextrxplantsource',array('grid'=>true)) ."',
							onShowPanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var slocfromid = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'slocfromid'});
								var productid = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'productid'});
								$(slocfromid.target).combogrid('grid').datagrid('load',{
									plantid :$('#productoutput-plantid').combogrid('getValue'),
									productid: $(productid.target).combogrid('getValue')
								});
							},
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'slocid',title:'".GetCatalog('slocid')."',width:'50px'},
								{field:'sloccode',title:'".GetCatalog('sloccode')."',width:'150px'},
								{field:'description',title:'".GetCatalog('description')."',width:'250px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.slocfromcode;
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
							url:'".Yii::app()->createUrl('common/sloc/indextrxplantsource',array('grid'=>true)) ."',
							onShowPanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var slocfromid = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'slocfromid'});
								var productid = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'productid'});
								$(slocfromid.target).combogrid('grid').datagrid('load',{
									plantid :$('#productoutput-plantid').combogrid('getValue'),
									productid: $(productid.target).combogrid('getValue')
								});
							},
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'slocid',title:'".GetCatalog('slocid')."',width:'50px'},
								{field:'sloccode',title:'".GetCatalog('sloccode')."',width:'150px'},
								{field:'description',title:'".GetCatalog('description')."',width:'250px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.sloctocode;
					}
				},
				{
					field:'storagebintoid',
					title:'".getCatalog('storagebinto') ."',
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
								var storagebintoid = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'storagebintoid'});
								var slocid = $('#dg-productoutput-productoutputdetail').datagrid('getEditor', {index: index, field:'sloctoid'});
								$(storagebintoid.target).combogrid('grid').datagrid('load',{
									slocid:$(slocid.target).combogrid('getValue')
								});
							},
							fitColumns:true,
							required:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'storagebinid',title:'".getCatalog('storagebinid')."',width:'80px'},
								{field:'description',title:'".getCatalog('description')."',width:'250px'},
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
				field:'description',
				title:'".GetCatalog('description')."',
				editor:{
					type: 'textbox',
					options:{
					}
				},
				sortable: true,
				width:'250px',
				formatter: function(value,row,index){
					return value;
				}
			},	
			"
		),
		array(
			'id'=>'productoutputwaste',
			'idfield'=>'productoutputwasteid',
			'urlsub'=>Yii::app()->createUrl('production/productoutput/indexwaste',array('grid'=>true)),
			'url'=> Yii::app()->createUrl('production/productoutput/searchwaste',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('production/productoutput/savewaste',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('production/productoutput/savewaste',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('production/productoutput/purgewaste',array('grid'=>true)),
			'subs'=>"
				{field:'productoutputwasteid',title:'".GetCatalog('ID Detail') ."',width:'50px'},
				{field:'productoutputfgid',title:'".GetCatalog('ID FG') ."',width:'50px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qtystock',title:'".GetCatalog('qtystock') ."',
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
				{field:'sloccode',title:'".GetCatalog('sloctcode') ."',width:'150px'},
				{field:'storagebinname',title:'".GetCatalog('storagebin') ."',width:'150px'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'250px'},
			",
			'onbeginedit'=>"
				row.productoutputid = $('#productoutputid').val();
				var rowx = $('#dg-productoutput-productoutputwaste').edatagrid('getSelected');
				if (rowx)
				{
					row.productoutputwasteid = rowx.productoutputwasteid;
				}
			",
			'columns'=>"
			{
				field:'productoutputwasteid',
				title:'".GetCatalog('productoutputwasteid') ."',
				sortable: true,
				hidden:true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'productoutputid',
				title:'".GetCatalog('productoutputid') ."',
				hidden:true,
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
					field:'productoutputfgid',
					title:'".GetCatalog('ID FG') ."',
					editor:{
							type:'combogrid',
							options:{
									panelWidth:'500px',
									mode : 'remote',
									method:'get',
									idField:'productoutputfgid',
									textField:'productname',
									url:'".Yii::app()->createUrl('production/productoutput/indexfg',array('grid'=>true)) ."',
									onShowPanel: function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productoutputfgid = $('#dg-productoutput-productoutputwaste').datagrid('getEditor', {index: index, field:'productoutputfgid'});
								$(productoutputfgid.target).combogrid('grid').datagrid('load',{
									productoutputid:$('#productoutput-productoutputid').val()
								});
							},
									fitColumns:true,
									required:true,
									loadMsg: '".GetCatalog('pleasewait')."',
									columns:[[
										{field:'productoutputfgid',title:'".GetCatalog('productoutputfgid')."',width:'50px'},
										{field:'productname',title:'".GetCatalog('productname')."',width:'450px'},
									]]
							}	
						},
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
										return row.productoutputfgid;
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
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'waste'=>true)) ."',
							onBeforeLoad: function(param){
								param.plantid = $('#productoutput-plantid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							onChange:function(newValue,oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-productoutput-productoutputwaste').datagrid('getEditor', {index: index, field:'productid'});
								var uomid = $('#dg-productoutput-productoutputwaste').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-productoutput-productoutputwaste').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-productoutput-productoutputwaste').datagrid('getEditor', {index: index, field:'uom3id'});
								var uom4id = $('#dg-productoutput-productoutputwaste').datagrid('getEditor', {index: index, field:'uom4id'});
								var stdqty = $('#dg-productoutput-productoutputwaste').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-productoutput-productoutputwaste').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-productoutput-productoutputwaste').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-productoutput-productoutputwaste').datagrid('getEditor', {index: index, field:'stdqty4'});												
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
								var stdqty = $('#dg-productoutput-productoutputwaste').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-productoutput-productoutputwaste').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-productoutput-productoutputwaste').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-productoutput-productoutputwaste').datagrid('getEditor', {index: index, field:'stdqty4'});								
								var qty2 = $('#dg-productoutput-productoutputwaste').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-productoutput-productoutputwaste').datagrid('getEditor', {index: index, field:'qty3'});								
								var qty4 = $('#dg-productoutput-productoutputwaste').datagrid('getEditor', {index: index, field:'qty4'});						
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
							url:'".Yii::app()->createUrl('common/sloc/indextrxplantsource',array('grid'=>true)) ."',
							onShowPanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var slocid = $('#dg-productoutput-productoutputwaste').datagrid('getEditor', {index: index, field:'slocid'});
								var productid = $('#dg-productoutput-productoutputwaste').datagrid('getEditor', {index: index, field:'productid'});
								$(slocid.target).combogrid('grid').datagrid('load',{
									plantid :$('#productoutput-plantid').combogrid('getValue'),
									productid: $(productid.target).combogrid('getValue')
								});
							},
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'slocid',title:'".GetCatalog('slocid')."',width:'50px'},
								{field:'sloccode',title:'".GetCatalog('sloccode')."',width:'150px'},
								{field:'description',title:'".GetCatalog('description')."',width:'250px'},
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
								var storagebinid = $('#dg-productoutput-productoutputwaste').datagrid('getEditor', {index: index, field:'storagebinid'});
								var slocid = $('#dg-productoutput-productoutputwaste').datagrid('getEditor', {index: index, field:'slocid'});
								$(storagebinid.target).combogrid('grid').datagrid('load',{
									slocid:$(slocid.target).combogrid('getValue')
								});
							},
							fitColumns:true,
							required:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'storagebinid',title:'".getCatalog('storagebinid')."',width:'80px'},
								{field:'description',title:'".getCatalog('description')."',width:'250px'},
							]]
						}	
					},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
						return row.storagebinname;
					}
				},
			{
				field:'itemnote',
				title:'".GetCatalog('itemnote')."',
				editor:{
					type: 'textbox',
					options:{
					}
				},
				sortable: true,
				width:'250px',
				formatter: function(value,row,index){
					return value;
				}
			},	
			"
		),
		array(
			'id'=>'productoutputemployee',
			'idfield'=>'productoutputemployeeid',
			'urlsub'=>Yii::app()->createUrl('production/productoutput/indexoperator',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('production/productoutput/searchoperator',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('production/productoutput/saveoperator',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('production/productoutput/saveoperator',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('production/productoutput/purgeoperator',array('grid'=>true)),
			'subs'=>"
				{field:'productoutputfgid',title:'".GetCatalog('ID FG') ."',width:'80px'},
				{field:'fullname',title:'".GetCatalog('fullname') ."',width:'200px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'productoutputid',
					title:'".GetCatalog('productoutput') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'productoutputemployeeid',
					title:'".GetCatalog('productoutputemployeeid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'productoutputfgid',
					title:'".GetCatalog('ID FG') ."',
					editor:{
							type:'combogrid',
							options:{
									panelWidth:'500px',
									mode : 'remote',
									method:'get',
									idField:'productoutputfgid',
									textField:'productname',
									url:'".Yii::app()->createUrl('production/productoutput/indexfg',array('grid'=>true)) ."',
									onShowPanel: function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productoutputfgid = $('#dg-productoutput-productoutputemployee').datagrid('getEditor', {index: index, field:'productoutputfgid'});
								$(productoutputfgid.target).combogrid('grid').datagrid('load',{
									productoutputid:$('#productoutput-productoutputid').val()
								});
							},
									fitColumns:true,
									required:true,
									loadMsg: '".GetCatalog('pleasewait')."',
									columns:[[
										{field:'productoutputfgid',title:'".GetCatalog('productoutputfgid')."',width:'50px'},
										{field:'productname',title:'".GetCatalog('productname')."',width:'450px'},
									]]
							}	
						},
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
										return row.productoutputfgid;
					}
				},
				{
					field:'productid',
					title:'".GetCatalog('productname') ."',
					width:'350px',
					sortable: true,
					formatter: function(value,row,index){
						return row.productname;
				}},
				{
					field:'employeeid',
					title:'".GetCatalog('employee') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'employeeid',
							textField:'fullname',
							url:'".Yii::app()->createUrl('hr/employee/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'employeeid',title:'".GetCatalog('employeeid')."',width:'50px'},
								{field:'fullname',title:'".GetCatalog('fullname')."',width:'100px'},
								{field:'oldnik',title:'".GetCatalog('oldnik')."'}
							]]
						}	
					},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
						return row.fullname;
				}},
				{
					field:'description',
					title:'".GetCatalog('description')."',
					editor: 'textbox',
					sortable: true,
					width:'200px',
					formatter: function(value,row,index){
										return value;
					}
				},
			"
		),
		array(
			'id'=>'productoutputjurnal',
			'idfield'=>'productoutputjurnalid',
			'urlsub'=>Yii::app()->createUrl('production/productoutput/indexjurnal',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('production/productoutput/indexjurnal',array('grid'=>true)),
			'subs'=>"
				{field:'accountname',title:'".GetCatalog('accountname') ."',width:'300px'},
				{field:'debit',title:'".GetCatalog('debit') ."',width:'150px',
				formatter: function(value,row,index){
						return formatnumber('',value);
					}},
				{field:'credit',title:'".GetCatalog('credit') ."',width:'150px',
				formatter: function(value,row,index){
						return formatnumber('',value);
					}},
			",
		),
	),	
));