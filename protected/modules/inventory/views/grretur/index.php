<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'grreturid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appgrretur',
	'url'=>Yii::app()->createUrl('inventory/grretur/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('inventory/grretur/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('inventory/grretur/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('inventory/grretur/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('inventory/grretur/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('inventory/grretur/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('inventory/grretur/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/grretur/downpdf'),
	'columns'=>"
		{
			field:'grreturid',
			title:'".GetCatalog('grreturid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appgrretur')."
		}},
		{
			field:'companyid',
			title:'".GetCatalog('company') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
			return row.companycode;
		}},
		{
			field:'plantid',
			title:'".GetCatalog('plantcode') ."',
			sortable: true,
			width:'125px',
			formatter: function(value,row,index){
			return row.plantcode;
		}},
		{
			field:'grreturdate',
			title:'".GetCatalog('grreturdate') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'grreturno',
			title:'".GetCatalog('grreturno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'poheaderid',
			title:'".GetCatalog('pono') ."',
			sortable: true,
			width:'130px',
			formatter: function(value,row,index){
			return row.pono;
		}},
		{
			field:'fullname',
			title:'".GetCatalog('supplier') ."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'headernote',
			title:'".GetCatalog('headernote') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatusgrretur',
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
	'searchfield'=> array ('companyname','grreturdate','pono','plantcode','grreturno','supplier','headernote'),
	'headerform'=> "
		<input type='hidden' name='grretur-grheaderid' id='grretur-grheaderid' />
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('grreturno')."</td>
				<td><input class='easyui-textbox' id='grretur-grreturno' name='grretur-grreturno' data-options='disabled:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('grreturdate')."</td>
				<td><input class='easyui-datebox' type='text' id='grretur-grreturdate' name='grretur-grreturdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('plantcode')."</td>
				<td>
					<select class='easyui-combogrid' id='grretur-plantid' name='grretur-plantid' style='width:250px' data-options=\"
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
				<td>".GetCatalog('poheader')."</td>
				<td><select class='easyui-combogrid' name='grretur-poheaderid' id='grretur-poheaderid' style='width:250px' data-options=\"
								panelWidth: '500px',
								required: true,
								idField: 'poheaderid',
								textField: 'pono',
								mode:'remote',
								url: '".Yii::app()->createUrl('purchasing/poheader/index',array('grid'=>true,'grretur'=>true))."',
								method: 'get',
								onBeforeLoad: function(param){
									param.plantid = $('#grretur-plantid').combogrid('getValue');
								},
								onShowPanel: function() {
							$('#grretur-poheaderid').combogrid('grid').datagrid('reload');
						},	
								onHidePanel: function(){
									jQuery.ajax(
										{'url':'". Yii::app()->createUrl('purchasing/poheader/generatesupplier') ."',
											'data':{'id':$('#grretur-poheaderid').combogrid('getValue')},
											'type':'post',
											'dataType':'json',
											'success':function(data)
											{
												$('#grretur-fullname').textbox('setValue',data.fullname);
											} ,
											'cache':false},
									);
								},
								columns: [[
									{field:'poheaderid',title:'".GetCatalog('poheaderid') ."',width:'50px'},
									{field:'pono',title:'".GetCatalog('pono') ."',width:'250px'},
								]],
								fitColumns: true\">
				</select></td>
			</tr>
			<tr>
				<td>".GetCatalog('supplier')."</td>
				<td><input class='easyui-textbox' id='grretur-fullname' name='grretur-fullname' data-options='disabled:true,
								required:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='grretur-headernote' name='grretur-headernote' data-options='multiline:true,required:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addload'=>"
		$('#grretur-grreturdate').datebox({value: (new Date().toString('dd-MMM-yyyy'))});
		$('#grretur-grheaderid').val('');	
	",
	'loadsuccess'=>"
		$('#grretur-grreturdate').datebox('setValue',data.grreturdate);
		$('#grretur-grreturno').textbox('setValue',data.grreturno);
		$('#grretur-plantid').combogrid('setValue',data.plantid);
		$('#grretur-poheaderid').combogrid('setValue',data.poheaderid);
		$('#grretur-fullname').textbox('setValue',data.fullname);
		$('#grretur-headernote').textbox('setValue',data.headernote);
		$('#grretur-grheaderid').val('');	
	",
	'columndetails'=> array (
		array(
			'id'=>'grreturdetail',
			'idfield'=>'grreturdetailid',
			'urlsub'=>Yii::app()->createUrl('inventory/grretur/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/grretur/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('inventory/grretur/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('inventory/grretur/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('inventory/grretur/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'grno',title:'".GetCatalog('grno') ."',width:'130px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty2',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty3',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom3code',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty4',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom4code',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'sloccode',title:'".GetCatalog('sloccode') ."',width:'60px'},
				{field:'description',title:'".GetCatalog('storagebin') ."',width:'100px'},
				{field:'SJsupplier',title:'".GetCatalog('SJsupplier') ."',width:'100px'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'100px'},
			",
			'columns'=>"
				{
					field:'grreturid',
					title:'".GetCatalog('grreturid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'grreturdetailid',
					title:'".GetCatalog('grreturdetailid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'grheaderid',
					title:'".GetCatalog('grno') ."',
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
								param.plantid = $('#grretur-plantid').combogrid('getValue');
								param.poheaderid = $('#grretur-poheaderid').combogrid('getValue');
								var row = $('#dg-grretur-grreturdetail').edatagrid('getSelected');
								if(row==null){
									$(\"input[name='grretur-grheaderid']\").val('0');
								}
							},
							onSelect: function(index,row){
								var grheaderid = row.grheaderid;
								$(\"input[name='grretur-grheaderid']\").val(row.grheaderid);
							},
							onChange: function(newValue,oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var grdetailid = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'grdetailid'});
								
								var uomid = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'uom3id'});
								var uom4id = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'uom4id'});
								var qty = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'qty'});
								var qty2 = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'qty2'});
								var qty3 = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'qty3'});
								var qty4 = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'qty4'});
								var slocid = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'slocid'});
								var storagebinid = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'storagebinid'});
								var sjsupplier = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'sjsupplier'});
								$(grdetailid.target).combogrid('setValue','');
						
								$(uomid.target).combogrid('setValue','');
								$(uom2id.target).combogrid('setValue','');
								$(uom3id.target).combogrid('setValue','');
								$(uom4id.target).combogrid('setValue','');
								$(qty.target).numberbox('setValue','');
								$(qty2.target).numberbox('setValue','');
								$(qty3.target).numberbox('setValue','');
								$(qty4.target).numberbox('setValue','');
								$(slocid.target).combogrid('setValue','');
								$(storagebinid.target).combogrid('setValue','');
								$(sjsupplier.target).textbox('setValue','');
							},
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'grheaderid',title:'".GetCatalog('grheaderid')."',width:'80px'},
								{field:'grno',title:'".GetCatalog('grno')."',width:'350px'},
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
					field:'grdetailid',
					title:'".getCatalog('productname') ."',
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
								var grheaderid = $(\"input[name='grretur-grheaderid']\").val();
								if(grheaderid==''){
									var row = $('#dg-grretur-grreturdetail').datagrid('getSelected');
									param.grheaderid = row.grheaderid;
									}else{
									param.grheaderid = $(\"input[name='grretur-grheaderid']\").val(); }
							},
							fitColumns:true,
							required:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var grdetailid = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'grdetailid'});
								var uomid = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'uom3id'});
								var uom4id = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'uom4id'});
								var qty = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'qty'});
								var qty2 = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'qty2'});
								var qty3 = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'qty3'});
								var qty4 = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'qty4'});
								var slocid = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'slocid'});
								var storagebinid = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'storagebinid'});
								var sjsupplier = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'sjsupplier'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('inventory/grretur/generateproductcode') ."',
									'data':{'grheaderid':$(\"input[name='grretur-grheaderid']\").val(),
													'grdetailid':$(grdetailid.target).combogrid('getValue')
									},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(uomid.target).combogrid('setValue',data.uomid);
										$(uom2id.target).combogrid('setValue',data.uom2id);
										$(uom3id.target).combogrid('setValue',data.uom3id);
										$(uom4id.target).combogrid('setValue',data.uom4id);
										$(qty.target).numberbox('setValue',data.qty);
										$(qty2.target).numberbox('setValue',data.qty2);
										$(qty3.target).numberbox('setValue',data.qty3);
										$(qty4.target).numberbox('setValue',data.qty4);
										$(slocid.target).combogrid('setValue',data.slocid);
										$(storagebinid.target).combogrid('setValue',data.storagebinid);
										$(sjsupplier.target).textbox('setValue',data.sjsupplier);
									} ,
									'cache':false});
							},
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'grdetailid',title:'".getCatalog('grdetail')."',width:'50px'},
								{field:'productid',title:'".getCatalog('product')."',width:'50px'},
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
								var qty2 = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'qty2'});
								var qty3 = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'qty3'});
								var qty4 = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'qty4'});								
								var qty2 = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'qty3'});								
								var qty4 = $('#dg-grretur-grreturdetail').datagrid('getEditor', {index: index, field:'qty4'});						
								$(qty2.target).numberbox('setValue',$(qty2.target).numberbox('getValue') * (newValue/oldValue));
								$(qty3.target).numberbox('setValue',$(qty3.target).numberbox('getValue') * (newValue/oldValue));
								$(qty4.target).numberbox('setValue',$(qty4.target).numberbox('getValue') * (newValue/oldValue));
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
							onBeforeLoad: function(param){
								param.plantid = $('#grretur-plantid').combogrid('getValue');
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
					title:'".GetCatalog('storagebin') ."',
					editor:{
							type:'combogrid',
							options:{
									panelWidth:'500px',
									mode : 'remote',
									method:'get',
									idField:'storagebinid',
									textField:'description',
									url:'".Yii::app()->createUrl('common/storagebin/index',array('grid'=>true)) ."',
									fitColumns:true,
									pagination:true,
									required:true,
									queryParams:{
										combo:true
									},
									loadMsg: '".GetCatalog('pleasewait')."',
									columns:[[
										{field:'storagebinid',title:'".GetCatalog('storagebinid')."',width:'50px'},
										{field:'description',title:'".GetCatalog('description')."',width:'150px'},
									]]
							}	
						},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.description;
					}
				},
				{
					field:'sjsupplier',
					title:'".GetCatalog('sjsupplier')."',
					editor: 'textbox',
					sortable: true,
					width:'100px',
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'itemnote',
					title:'".GetCatalog('itemnote')."',
					editor: 'textbox',
					sortable: true,
					width:'100px',
					formatter: function(value,row,index){
										return value;
					}
				},
			"
		),
	),	
));