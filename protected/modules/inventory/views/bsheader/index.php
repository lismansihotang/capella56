<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'bsheaderid',
	'formtype'=>'masterdetail',
	'isxls'=>1,
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appbs',
	'url'=>Yii::app()->createUrl('inventory/bsheader/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('inventory/bsheader/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('inventory/bsheader/save',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('inventory/bsheader/upload'),
	'destroyurl'=>Yii::app()->createUrl('inventory/bsheader/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('inventory/bsheader/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('inventory/bsheader/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/bsheader/downpdf'),
	'downxls'=>Yii::app()->createUrl('inventory/bsheader/downxls'),
	'columns'=>"
		{
			field:'bsheaderid',
			title:'".GetCatalog('bsheaderid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appbs')."
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
			field:'bsdate',
			title:'".GetCatalog('bsdate') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'bsheaderno',
			title:'".GetCatalog('bsheaderno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'slocid',
			title:'".GetCatalog('sloc') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return row.sloccode;
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
			field:'recordstatusbsheader',
			title:'".getCatalog('recordstatus') ."',
			align:'left',
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.recordstatusbsheader;
		}},
	",
	'searchfield'=> array ('bsheaderid','plantcode','bsdate','bsheaderno','sloccode','productname','headernote','recordstatus'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('bsheaderno')."</td>
				<td><input class='easyui-textbox' id='bsheader-bsheaderno' name='bsheader-bsheaderno' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('bsdate')."</td>
				<td><input class='easyui-datebox' type='text' id='bsheader-bsdate' name='bsheader-bsdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='bsheader-plantid' name='bsheader-plantid' style='width:150px' data-options=\"
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
											'plantid':$('#bsheader-plantid').combogrid('getValue'),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#bsheader-companyname').textbox('setValue',data.companyname);
											var g = $('#bsheader-slocid').combogrid('grid');
											g.datagrid({queryParams: {
												plantid: data.plantid
											}});
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
				<td>".GetCatalog('company')."</td>
				<td><input class='easyui-textbox' id='bsheader-companyname' name='bsheader-companyname' style='width:280px' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('sloccode')."</td>
				<td>
					<select class='easyui-combogrid' id='bsheader-slocid' name='bsheader-slocid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'slocid',
						textField: 'sloccode',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/sloc/indextrxplantsloc',array('grid'=>true)) ."',
						onBeforeLoad:function(param) {
							param.plantid = $('#bsheader-plantid').combogrid('getValue')
						},
						method: 'get',
						columns: [[
								{field:'slocid',title:'".GetCatalog('slocid') ."',width:'50px'},
								{field:'sloccode',title:'".GetCatalog('sloccode') ."',width:'150px'},
								{field:'description',title:'".GetCatalog('description') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='bsheader-headernote' name='bsheader-headernote' data-options='multiline:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addload'=>"
		$('#bsheader-bsdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});	",
	'rowstyler'=>"
		if (row.count >= 1){
			return 'background-color:blue;color:#fff;font-weight:bold;';
		}
	",
	'loadsuccess' => "
		$('#bsheader-bsheaderno').textbox('setValue',data.bsheaderno);
		$('#bsheader-bsdate').datebox('setValue',data.bsdate);
		$('#bsheader-companyname').textbox('setValue',data.companyname);
		$('#bsheader-plantid').combogrid('setValue',data.plantid);
		$('#bsheader-slocid').combogrid('setValue',data.slocid);
		$('#bsheader-headernote').textbox('setValue',data.headernote);
	",
	'columndetails'=> array (
		array(
			'id'=>'bsdetail',
			'idfield'=>'bsdetailid',
			'urlsub'=>Yii::app()->createUrl('inventory/bsheader/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/bsheader/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('inventory/bsheader/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('inventory/bsheader/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('inventory/bsheader/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'bsdetailid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
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
				{field:'description',title:'".GetCatalog('description') ."',width:'150px'},
				{field:'materialstatusname',title:'".GetCatalog('materialstatusname') ."',width:'150px'},
				{field:'ownershipname',title:'".GetCatalog('ownershipname') ."',width:'100px'},
				{field:'lotno',title:'".GetCatalog('lotno') ."',width:'100px'},
				{field:'buyprice',title:'".GetCatalog('buyprice') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},width:'100px'},
				{field:'buydate',title:'".GetCatalog('buydate') ."',width:'100px'},
				{field:'currencyrate',title:'".GetCatalog('currencyrate') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},
				{field:'totalvalue',title:'".GetCatalog('totalvalue') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},width:'100px'},
					{field:'buydate',title:'".GetCatalog('buydate') ."',width:'100px'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'100px'},
			",
			'columns'=>"
				{
					field:'bsheaderid',
					title:'".GetCatalog('bsheaderid') ."',
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
					field:'bsdetailid',
					title:'".GetCatalog('bsdetailid') ."',
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
					field:'stdqty2',
					title:'".GetCatalog('stdqty2') ."',
					sortable: true,
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
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'stdqty',
					title:'".GetCatalog('stdqty') ."',
					sortable: true,
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
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'stdqty3',
					title:'".GetCatalog('stdqty3') ."',
					sortable: true,
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
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'stdqty4',
					title:'".GetCatalog('stdqty4') ."',
					sortable: true,
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
					width:'150px',
					sortable: true,
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
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'productplant'=>true)) ."',
							onBeforeLoad: function(param){
								param.slocid = $('#bsheader-slocid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							onChange: function(newValue, oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-bsheader-bsdetail').datagrid('getEditor', {index: index, field:'productid'});
								var uomid = $('#dg-bsheader-bsdetail').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-bsheader-bsdetail').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-bsheader-bsdetail').datagrid('getEditor', {index: index, field:'uom3id'});
								var uom4id = $('#dg-bsheader-bsdetail').datagrid('getEditor', {index: index, field:'uom4id'});
								var stdqty = $('#dg-bsheader-bsdetail').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-bsheader-bsdetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-bsheader-bsdetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-bsheader-bsdetail').datagrid('getEditor', {index: index, field:'stdqty4'});
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
							onChange: function(newValue,oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var stdqty = $('#dg-bsheader-bsdetail').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-bsheader-bsdetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-bsheader-bsdetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-bsheader-bsdetail').datagrid('getEditor', {index: index, field:'stdqty4'});								
								var qty2 = $('#dg-bsheader-bsdetail').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-bsheader-bsdetail').datagrid('getEditor', {index: index, field:'qty3'});								
								var qty4 = $('#dg-bsheader-bsdetail').datagrid('getEditor', {index: index, field:'qty4'});						
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
							onBeforeLoad: function(param){
								param.slocid = $('#bsheader-slocid').combogrid('getValue');
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
						return row.description;
					}
				},
				{
					field:'materialstatusid',
					title:'".getCatalog('materialstatusname') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'550px',
							mode : 'remote',
							method:'get',
							idField:'materialstatusid',
							textField:'materialstatusname',
							url:'".Yii::app()->createUrl('common/materialstatus/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'materialstatusid',title:'".getCatalog('materialstatusid')."',width:'80px'},
								{field:'materialstatusname',title:'".getCatalog('materialstatusname')."',width:'150px'},
							]]
						}	
					},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
						return row.materialstatusname;
					}
				},
				{
					field:'ownershipid',
					title:'".getCatalog('ownership') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'550px',
							mode : 'remote',
							method:'get',
							idField:'ownershipid',
							textField:'ownershipname',
							url:'".Yii::app()->createUrl('common/ownership/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'ownershipid',title:'".getCatalog('ownershipid')."',width:'80px'},
								{field:'ownershipname',title:'".getCatalog('ownershipname')."',width:'150px'},
							]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return row.ownershipname;
					}
				},
				{
					field:'lotno',
					title:'".GetCatalog('lotno')."',
					editor: {
						type: 'textbox',
						options:{
							required:true
						}
					},
					sortable: true,
					width:'200px',
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'buyprice',
					title:'".GetCatalog('buyprice') ."',
					width:'150px',
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
					field:'buydate',
					title:'".GetCatalog('buydate')."',
					editor: {
						type: 'datebox',
						options: {
							required:true
						}
					},
					sortable: true,
					width:'150px',
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'currencyid',
					title:'".getCatalog('currency') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'550px',
							mode : 'remote',
							method:'get',
							idField:'currencyid',
							textField:'currencyname',
							url:'".Yii::app()->createUrl('admin/currency/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'currencyid',title:'".getCatalog('currencyid')."',width:'80px'},
								{field:'currencyname',title:'".getCatalog('currencyname')."',width:'150px'},
							]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return row.currencyname;
					}
				},
				{
					field:'currencyrate',
					title:'".GetCatalog('currencyrate') ."',
					width:'150px',
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
					field:'itemnote',
					title:'".GetCatalog('itemnote')."',
					editor: {
						type: 'textbox'
					},
					sortable: true,
					width:'200px',
					formatter: function(value,row,index){
										return value;
					}
				},
			",
		),
		array(
			'id'=>'bsjurnal',
			'idfield'=>'bsjurnalid',
			'isnew'=>0,
			'ispurge'=>0,
			'urlsub'=>Yii::app()->createUrl('inventory/bsheader/indexjurnal',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/bsheader/searchjurnal',array('grid'=>true)),
			'subs'=>"
				{field:'bsjurnalid',title:'".getCatalog('ID') ."',width:'60px'},
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