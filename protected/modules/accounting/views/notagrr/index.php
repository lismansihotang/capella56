<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'notagrrid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appnotagrr',
	'url'=>Yii::app()->createUrl('accounting/notagrr/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('accounting/notagrr/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/notagrr/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/notagrr/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/notagrr/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('accounting/notagrr/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('accounting/notagrr/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/notagrr/downpdf'),
	'columns'=>"
		{
			field:'notagrrid',
			title:'".GetCatalog('notagrrid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appnotagrr')."
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
			field:'notagrrdate',
			title:'".GetCatalog('notagrrdate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'notagrrno',
			title:'".GetCatalog('notagrrno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'fullname',
			title:'".GetCatalog('supplier') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return value;
		}},
		{
			field:'invpiceaptaxno',
			title:'".GetCatalog('invpiceaptaxno') ."',
			sortable: true,
			width:'150px',
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
			field:'grreturno',
			title:'".GetCatalog('grreturno') ."',
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
	'searchfield'=> array ('notagrrid','plantcode','notagrrdate','supplier','pono','grreturno','notagrrno','headernote','recordstatus'),
	'headerform'=> "
		<input type='hidden' name='notagrr-slocid' id='notagrr-slocid' />
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('notagrrdate')."</td>
				<td><input class='easyui-datebox' type='text' id='notagrr-notagrrdate' name='notagrr-notagrrdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('notagrrno')."</td>
				<td><input class='easyui-textbox' id='notagrr-notagrrno' name='notagrr-notagrrno' data-options='disabled:false'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('plantcode')."</td>
				<td>
					<select class='easyui-combogrid' id='notagrr-plantid' name='notagrr-plantid' style='width:250px' data-options=\"
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
				<td>".GetCatalog('supplier')."</td>
				<td>
					<select class='easyui-combogrid' id='notagrr-addressbookid' name='notagrr-addressbookid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/supplier/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'addressbookid',title:'".GetCatalog('addressbookid') ."',width:'50px'},
								{field:'fullname',title:'".GetCatalog('customer') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('invoiceaptaxno')."</td>
				<td><input class='easyui-textbox' id='notagrr-invoiceaptaxno' name='notagrr-invoiceaptaxno' data-options='required:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('poheaderid')."</td>
				<td>
					<select class='easyui-combogrid' id='notagrr-poheaderid' name='notagrr-poheaderid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'poheaderid',
						textField: 'pono',
						mode:'remote',
						url: '".Yii::app()->createUrl('purchasing/poheader/index',array('grid'=>true,'invappo'=>true)) ."',
						method: 'get',
						onBeforeLoad: function(param){
							param.plantid = $('#notagrr-plantid').combogrid('getValue');
							param.addressbookid = $('#notagrr-addressbookid').combogrid('getValue');
						},
						onHidePanel: function(){
							jQuery.ajax({'url':'". Yii::app()->createUrl('accounting/notagrr/generatedetail') ."',
									'data':{'id':$('#notagrr-poheaderid').combogrid('getValue'),
													'hid':$('#notagrr-notagrrid').val(),
													'clientippublic':$('#clientippublic').val(),
													'clientiplocal':$('#clientiplocal').val(),
													'clientlat':$('#clientlat').val(),
													'clientlng':$('#clientlng').val(),
									},
									'type':'post',
									'dataType':'json',
									'success':function(data)
									{
										$('#dg-notagrr-notagrrtax').edatagrid('reload');
										$('#dg-notagrr-notagrrgr').edatagrid('reload');
										$('#dg-notagrr-notagrrdetail').edatagrid('reload');
									},
									'cache':false});
						},
						columns: [[
								{field:'poheaderid',title:'".GetCatalog('poheaderid') ."',width:'50px'},
								{field:'pono',title:'".GetCatalog('pono') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='notagrr-headernote' name='notagrr-headernote' data-options='multiline:true,required:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addload'=>"
		$('#notagrr-notagrrdate').datebox({value: (new Date().toString('dd-MMM-yyyy'))});
	",
	'loadsuccess'=>"
		$('#notagrr-notagrrdate').datebox('setValue',data.notagrrdate);
		$('#notagrr-notagrrno').textbox('setValue',data.notagrrno);
		$('#notagrr-plantid').combogrid('setValue',data.plantid);
		$('#notagrr-addressbookid').combogrid('setValue',data.addressbookid);
		$('#notagrr-poheaderid').textbox('setValue',data.poheaderid);
		$('#notagrr-invoiceaptaxno').textbox('setValue',data.invoiceaptaxno);
		$('#notagrr-poheaderid').combogrid('setValue',data.poheaderid);
		$('#notagrr-headernote').textbox('setValue',data.headernote);
	",
	'columndetails'=> array (
		array(
			'id'=>'notagrrtax',
			'idfield'=>'notagrrtaxid',
			'urlsub'=>Yii::app()->createUrl('accounting/notagrr/indextax',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/notagrr/searchtax',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/notagrr/savetax',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/notagrr/savetax',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/notagrr/purgetax',array('grid'=>true)),
			'subs'=>"
				{field:'taxcode',title:'".GetCatalog('taxcode') ."',width:'200px'},
			",
			'columns'=>"
				{
					field:'notagrrtaxid',
					title:'".GetCatalog('notagrrtaxid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'notagrrid',
					title:'".GetCatalog('notagrrid') ."',
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
			'id'=>'notagrrgrr',
			'idfield'=>'notagrrgrrid',
			'urlsub'=>Yii::app()->createUrl('accounting/notagrr/indexgrr',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/notagrr/searchgrr',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/notagrr/savegrr',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/notagrr/savegrr',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/notagrr/purgegrr',array('grid'=>true)),
			'subs'=>"
				{field:'grreturno',title:'".GetCatalog('grreturno') ."',width:'150px'},
				{field:'headernote',title:'".GetCatalog('headernote') ."',width:'400px'},
			",
			'columns'=>"
				{
					field:'notagrrid',
					title:'".GetCatalog('notagrrid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'notagrrgrrid',
					title:'".GetCatalog('notagrrgrrid') ."',
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'grreturid',
					title:'".GetCatalog('grreturno') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'800px',
							mode : 'remote',
							method:'get',
							idField:'grreturid',
							textField:'grreturno',
							url:'".Yii::app()->createUrl('inventory/grretur/index',array('grid'=>true,'notagrr'=>true))."',
							onBeforeLoad:function(param) {
								param.plantid = $('#notagrr-plantid').combogrid('getValue');
								param.poheaderid = $('#notagrr-poheaderid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'grreturid',title:'".GetCatalog('grreturid')."',width:'80px'},
								{field:'grreturno',title:'".GetCatalog('grreturno')."',width:'350px'},
							]]
						}	
					},
					width:'300px',
					sortable: true,
					formatter: function(value,row,index){
						return row.grreturno;
					}
				},
			",
			'onsuccess'=>"$('#dg-notagrr-notagrrdetail').datagrid('reload');
			",
		),
		array(
			'id'=>'notagrrdetail',
			'idfield'=>'notagrrdetailid',
			'urlsub'=>Yii::app()->createUrl('accounting/notagrr/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/notagrr/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/notagrr/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/notagrr/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/notagrr/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'250px'},
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
				{field:'price',title:'".GetCatalog('price') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.price);
					},width:'150px'},
				{field:'dpp',title:'".GetCatalog('dpp') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.dpp);
					},width:'150px'},
				{field:'total',title:'".GetCatalog('total') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.total);
					},width:'150px'},
				{field:'currencyname',title:'".GetCatalog('currencyname') ."',width:'350px'},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',align:'right',width:'60px'},
			",
			'columns'=>"
				{
					field:'notagrrid',
					title:'".GetCatalog('notagrrid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'notagrrdetailid',
					title:'".GetCatalog('notagrrdetailid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'notagrrgrrid',
					title:'".GetCatalog('ngrrgrrid') ."',
					sortable: true,
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
								param.plantid = $('#notagrr-plantid').combogrid('getValue');
								var row = $('#dg-notagrr-notagrrdetail').edatagrid('getSelected');
								if(row==null){
									$(\"input[name='notagrr-slocid']\").val('0');
								}
							},
							onSelect: function(index,row){
								var slocid = row.slocid;
								$(\"input[name='notagrr-slocid']\").val(row.slocid);
							},
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var slocid = $('#dg-notagrr-notagrrdetail').datagrid('getEditor', {index: index, field:'slocid'});
								var productid = $('#dg-notagrr-notagrrdetail').datagrid('getEditor', {index: index, field:'productid'});
							
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
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plant'=>true)) ."',
							onShowPanel: function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-notagrr-notagrrdetail').datagrid('getEditor', {index: index, field:'productid'});
								var slocid = $('#dg-notagrr-notagrrdetail').datagrid('getEditor', {index: index, field:'slocid'});
								$(productid.target).combogrid('grid').datagrid('load',{
									plantid:$('#notagrr-plantid').combogrid('getValue')
								});
							},
							onBeforeLoad: function(param){
								param.plantid = $('#notagrr-plantid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-notagrr-notagrrdetail').datagrid('getEditor', {index: index, field:'productid'});
								var uomid = $('#dg-notagrr-notagrrdetail').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-notagrr-notagrrdetail').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-notagrr-notagrrdetail').datagrid('getEditor', {index: index, field:'uom3id'});
								var uom4id = $('#dg-notagrr-notagrrdetail').datagrid('getEditor', {index: index, field:'uom4id'});
								var stdqty2 = $('#dg-notagrr-notagrrdetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-notagrr-notagrrdetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-notagrr-notagrrdetail').datagrid('getEditor', {index: index, field:'stdqty4'});
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
								var stdqty2 = $('#dg-notagrr-notagrrdetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-notagrr-notagrrdetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-notagrr-notagrrdetail').datagrid('getEditor', {index: index, field:'stdqty4'});								
								var qty2 = $('#dg-notagrr-notagrrdetail').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-notagrr-notagrrdetail').datagrid('getEditor', {index: index, field:'qty3'});								
								var qty4 = $('#dg-notagrr-notagrrdetail').datagrid('getEditor', {index: index, field:'qty4'});						
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
							readonly:true,
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
							required:true,
							
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
							required:true,
							
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
						return formatnumber(row.symbol,value);
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
							required:true,
							value:1,
							readonly:true
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
							required:true,
							readonly:true
						}
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
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
			'id'=>'notagrrjurnal',
			'idfield'=>'notagrrjurnalid',
			'isnew'=>0,
			'iswrite'=>0,
			'ispurge'=>0,
			'urlsub'=>Yii::app()->createUrl('accounting/notagrr/indexjurnal',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/notagrr/searchjurnal',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/notagrr/savejurnal',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/notagrr/savejurnal',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/notagrr/purgejurnal',array('grid'=>true)),
			'subs'=>"
				{field:'accountname',title:'".GetCatalog('accountname') ."',width:'250px'},
				{field:'debit',title:'".GetCatalog('debit') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.debit);
					},width:'120px'},
				{field:'credit',title:'".GetCatalog('credit') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.credit);
					},width:'120px'},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',align:'right',width:'60px'},
				{field:'detailnote',title:'".GetCatalog('detailnote') ."',width:'350px'},
			",
			'columns'=>"
				{
					field:'notagrrid',
					title:'".GetCatalog('notagrrid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'notagrrjurnalid',
					title:'".GetCatalog('notagrrjurnalid') ."',
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
								 param.companyid = $('#notagrr-plantid').combogrid('getValue');
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