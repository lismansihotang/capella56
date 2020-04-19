<input type='hidden' id='grheader-isjasa-value' name='grheader-isjasa-value' value='0' />
<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'grheaderid',
	'formtype'=>'masterdetail',
	'isxls'=>1,
	'ispost'=>1,
	'isreject'=>1,
	'ispurge'=>0,
	'isupload'=>0,
	'wfapp'=>'appgr',
	'url'=>Yii::app()->createUrl('inventory/grheader/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('inventory/grheader/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('inventory/grheader/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('inventory/grheader/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('inventory/grheader/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('inventory/grheader/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('inventory/grheader/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/grheader/downpdf'),
	'downxls'=>Yii::app()->createUrl('inventory/grheader/downxls'),
	'columns'=>"
		{
			field:'grheaderid',
			title:'".GetCatalog('ID') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appgr')."
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
			field:'grdate',
			title:'".GetCatalog('grdate') ."',
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
			field:'grno',
			title:'".GetCatalog('grno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'isjasa',
			title:'". GetCatalog('isjasa') ."',
			align:'center',
			width:'100px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
				} else {
				return '';
				}
		}},
		{
			field:'addressbookid',
			title:'".GetCatalog('supplier') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'pibno',
			title:'".GetCatalog('pibno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'sjsupplier',
			title:'".GetCatalog('sjsupplier') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'kendaraanno',
			title:'".GetCatalog('kendaraanno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'supir',
			title:'".GetCatalog('supir') ."',
			sortable: true,
			width:'200px',
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
	'searchfield'=> array ('grheaderid','plantcode','grdate','grno','pono','supplier','pibno','sjsupplier','kendaraanno','supir','headernote','productname','sloccode','recordstatus'),
	'headerform'=> "
		
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('grno')."</td>
				<td><input class='easyui-textbox' id='grheader-grno' name='grheader-grno' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('grdate')."</td>
				<td><input class='easyui-datebox' type='text' id='grheader-grdate' name='grheader-grdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='grheader-plantid' name='grheader-plantid' style='width:150px' data-options=\"
								panelWidth: '500px',
								idField: 'plantid',
								required: true,
								textField: 'plantcode',
								mode: 'remote',
								url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'auth'=>true)) ."',
								method: 'get',
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
				<td>".GetCatalog('isjasa')."</td>
				<td><input class='easyui-checkbox' name='grheader-isjasa' id='grheader-isjasa' data-options=\"
				onChange: function(checked){
					if (checked == true) {
						$('#grheader-isjasa-value').val(1);
						$('#grheader-poheaderid').combogrid('grid').datagrid('load', {
							plantid : $('#grheader-plantid').combogrid('getValue'),
							isjasa : 1
						});
					} else {
						$('#grheader-isjasa-value').val(0);
						$('#grheader-poheaderid').combogrid('grid').datagrid('load', {
							plantid : $('#grheader-plantid').combogrid('getValue'),
							isjasa : 0
						});
					}
				}
				\"></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('poheader')."</td>
				<td>
					<select class='easyui-combogrid' id='grheader-poheaderid' name='grheader-poheaderid' style='width:250px' data-options=\"
						panelWidth: '700px',
						required: true,
						idField: 'poheaderid',
						textField: 'pono',
						mode:'remote',
						url: '".Yii::app()->createUrl('purchasing/poheader/index',array('grid'=>true,'grpo'=>true)) ."',
						method: 'get',
						onShowPanel: function() {
							$('#grheader-poheaderid').combogrid('grid').datagrid('reload');
						},							
						onBeforeLoad: function(param) {
							param.plantid = $('#grheader-plantid').combogrid('getValue');
							param.isjasa = $('#grheader-isjasa-value').val();
						},
						onHidePanel: function(){
							jQuery.ajax({'url':'".Yii::app()->createUrl('purchasing/poheader/index',array('grid'=>true,'getdata'=>true)) ."',
								'data':{
									'poheaderid':$('#grheader-poheaderid').combogrid('getValue')
								},
								'type':'post',
								'dataType':'json',
								'success':function(data)
								{
									$('#grheader-addressbookid').combogrid('setValue',data.addressbookid);
									jQuery.ajax({'url':'".Yii::app()->createUrl('inventory/grheader/generatedetail') ."',
										'data':{
											'id':$('#grheader-poheaderid').combogrid('getValue'),
											'hid':$('#grheader-grheaderid').val(),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#dg-grheader-grdetail').datagrid('reload');
											$('#dg-grheader-grresult').datagrid('reload');
											$('#dg-grheader-grjasa').datagrid('reload');
										},
										'cache':false});
								},
								'cache':false});
						},
						columns: [[
								{field:'poheaderid',title:'".GetCatalog('poheaderid') ."',width:'50px'},
								{field:'pono',title:'".GetCatalog('pono') ."',width:'120px'},
								{field:'fullname',title:'".GetCatalog('Supplier') ."',width:'300px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('supplier')."</td>
				<td>
					<select class='easyui-combogrid' id='grheader-addressbookid' name='grheader-addressbookid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						readonly:true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/supplier/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'addressbookid',title:'".GetCatalog('addressbookid') ."',width:'50px'},
								{field:'fullname',title:'".GetCatalog('fullname') ."',width:'150px'}
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('pibno')."</td>
				<td><input class='easyui-textbox' id='grheader-pibno' name='grheader-pibno' data-options='required:false'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('sjsupplier')."</td>
				<td><input class='easyui-textbox' id='grheader-sjsupplier' name='grheader-sjsupplier' data-options='required:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('kendaraanno')."</td>
				<td><input class='easyui-textbox' id='grheader-kendaraanno' name='grheader-kendaraanno' data-options='required:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('supir')."</td>
				<td><input class='easyui-textbox' id='grheader-supir' name='grheader-supir' data-options='required:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='grheader-headernote' name='grheader-headernote' data-options='multiline:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addload'=>"
		$('#grheader-grdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});	
		$('#grheader-isjasa-value').val(0);
		$('#grheader-isjasa').checkbox('uncheck');
		$('#tabdetails-grheader').tabs('enableTab',0);
		$('#tabdetails-grheader').tabs('disableTab',1);
		$('#tabdetails-grheader').tabs('disableTab',2);
	",
	'addonscripts'=>"
		function purgegralldetail(\$id) {
			jQuery.ajax({'url':'".Yii::app()->createUrl('inventory/grheader/purgealldetail') ."',
				'data':{
					'id':\$id,
				},
				'type':'post',
				'dataType':'json',
				'success':function(data)
				{
					$('#dg-grheader-prraw').datagrid('reload');
					$('#dg-grheader-prjasa').datagrid('reload');
					$('#dg-grheader-prresult').datagrid('reload');
				},
				'cache':false});
		}
	",
	'loadsuccess' => "
		$('#tabdetails-grheader').tabs('disableTab',1);
		$('#tabdetails-grheader').tabs('disableTab',2);
		$('#tabdetails-grheader').tabs('select',0);
		$('#grheader-grno').textbox('setValue',data.grno);
		$('#grheader-grdate').datebox('setValue',data.grdate);
		$('#grheader-plantid').combogrid('setValue',data.plantid);
		$('#grheader-poheaderid').combogrid('setValue',data.poheaderid);
		if (data.isjasa == 1)
		{
			$('#grheader-isjasa-value').val(1);
			$('#grheader-isjasa').checkbox('check');
			$('#tabdetails-grheader').tabs('enableTab',0);
			$('#tabdetails-grheader').tabs('enableTab',1);
			$('#tabdetails-grheader').tabs('enableTab',2);
		}
		else {
			$('#grheader-isjasa-value').val(0);
			$('#grheader-isjasa').checkbox('uncheck');
			$('#tabdetails-grheader').tabs('enableTab',0);
			$('#tabdetails-grheader').tabs('disableTab',1);
			$('#tabdetails-grheader').tabs('disableTab',2);
		}
			$('#grheader-pibno').textbox('setValue',data.pibno);
			$('#grheader-sjsupplier').textbox('setValue',data.sjsupplier);
			$('#grheader-kendaraanno').textbox('setValue',data.kendaraanno);
			$('#grheader-supir').textbox('setValue',data.supir);
			$('#grheader-headernote').textbox('setValue',data.headernote);
		$('#grheader-addressbookid').combogrid('setValue',data.addressbookid);
	",
	'columndetails'=> array (
		array(
			'id'=>'grdetail',
			'idfield'=>'grdetailid',
			'urlsub'=>Yii::app()->createUrl('inventory/grheader/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/grheader/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('inventory/grheader/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('inventory/grheader/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('inventory/grheader/purgedetail',array('grid'=>true)),
			'isnew'=>0,
			'subs'=>"
				{field:'grdetailid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qtystock',title:'".GetCatalog('qtystock') ."',formatter: function(value,row,index){
					if (row.stockcount == '1') {
					return '<div style=\"background-color:cyan\">'+formatnumber('',value)+'</div>';
					} else {
						return formatnumber('',value);
					}
				},width:'100px'},
				{field:'poqty',title:'".GetCatalog('poqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
					{field:'sisaqty',title:'".GetCatalog('Sisa qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
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
				{field:'uom3code',titl21e:'".GetCatalog('uom3code') ."',width:'80px'},
				{field:'sloccode',title:'".GetCatalog('sloccode') ."',width:'150px'},
				{field:'rak',title:'".GetCatalog('storagebin') ."',width:'150px'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'grheaderid',
					title:'".GetCatalog('grheaderid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'grdetailid',
					title:'".GetCatalog('grdetailid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'podetailid',
					title:'".GetCatalog('podetail') ."',
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
							required:true,
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
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'productplant'=>true)) ."',
							fitColumns:true,
							readonly:true,
							loadMsg: '".getCatalog('pleasewait')."',
							onHidePanel: function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-grheader-grdetail').datagrid('getEditor', {index: index, field:'productid'});
								var stdqty = $('#dg-grheader-grdetail').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-grheader-grdetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-grheader-grdetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/index',array('grid'=>true,'getdata'=>true)) ."',
									'data':{'productid':$(productid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(stdqty.target).numberbox('setValue',data.qty);
										$(stdqty2.target).numberbox('setValue',data.qty2);
										$(stdqty3.target).numberbox('setValue',data.qty3);
									} ,
									'cache':false});
							},							
							columns:[[
								{field:'productid',title:'".getCatalog('productid')."',width:'50px'},
								{field:'materialtypecode',title:'".getCatalog('materialtypecode')."',width:'150px'},
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
							onChange: function(newValue,oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var stdqty = $('#dg-grheader-grdetail').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-grheader-grdetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-grheader-grdetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var qty2 = $('#dg-grheader-grdetail').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-grheader-grdetail').datagrid('getEditor', {index: index, field:'qty3'});								
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
					field:'slocid',
					title:'".GetCatalog('sloc') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							readonly:true,
							hasDownArrow:false,
							idField:'slocid',
							textField:'sloccode',
							url:'".Yii::app()->createUrl('common/sloc/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'slocid',title:'".GetCatalog('slocid')."',width:'50px'},
								{field:'sloccode',title:'".GetCatalog('sloccode')."',width:'150px'},
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
							url:'".Yii::app()->createUrl('common/storagebin/indexcombosloc',array('grid'=>true)) ."',
							onShowPanel: function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var slocid = $('#dg-grheader-grdetail').datagrid('getEditor', {index: index, field:'slocid'});
								var storagebinid = $('#dg-grheader-grdetail').datagrid('getEditor', {index: index, field:'storagebinid'});
								$(storagebinid.target).combogrid('grid').datagrid('load',{
									slocid:$(slocid.target).combogrid('getValue')
								});
							},
							fitColumns:true,
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
										return row.rak;
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
		array(
			'id'=>'grjasa',
			'idfield'=>'grjasaid',
			'urlsub'=>Yii::app()->createUrl('inventory/grheader/indexgrjasa',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/grheader/searchgrjasa',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('inventory/grheader/savegrjasa',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('inventory/grheader/savegrjasa',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('inventory/grheader/purgegrjasa',array('grid'=>true)),
			'isnew'=>0,
			'subs'=>"
				{field:'grjasaid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'150px'},
				{field:'reqdate',title:'".GetCatalog('reqdate') ."',width:'100px'},
				{field:'sloccode',title:'".GetCatalog('slocto') ."',width:'100px'},
				{field:'mesincode',title:'".GetCatalog('mesin') ."',width:'150px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'grheaderid',
					title:'".GetCatalog('grheaderid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'grjasaid',
					title:'".GetCatalog('grjasaid') ."',
					sortable: true,
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
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'productplant'=>true)) ."',
							onBeforeLoad: function(param){
								param.slocid = $('#grheader-slocfromid').combogrid('getValue');
							},
							fitColumns:true,
							readonly:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-grheader-grjasa').datagrid('getEditor', {index: index, field:'productid'});
								var productname = $('#dg-grheader-grjasa').datagrid('getEditor', {index: index, field:'productname'});
								var uomid = $('#dg-grheader-grjasa').datagrid('getEditor', {index: index, field:'uomid'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/index',array('grid'=>true,'getdata'=>true)) ."',
									'data':{'productid':$(productid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(productname.target).textbox('setValue',data.productname);
										$(uomid.target).combogrid('setValue',data.uom1);
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
					field:'reqdate',
					title:'".GetCatalog('reqdate')."',
					editor: 'datebox',
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
										param.plantid = $('#grheader-plantid').combogrid('getValue');
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
					readonly:true,
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
			'id'=>'grresult',
			'idfield'=>'grresultid',
			'urlsub'=>Yii::app()->createUrl('inventory/grheader/indexresult',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/grheader/searchresult',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('inventory/grheader/saveresult',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('inventory/grheader/saveresult',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('inventory/grheader/purgeresult',array('grid'=>true)),
			'isnew'=>0,
			'subs'=>"
				{field:'grresultid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qty1',title:'".GetCatalog('qty1') ."',
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
					field:'grheaderid',
					title:'".GetCatalog('grheaderid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'grresultid',
					title:'".GetCatalog('grresultid') ."',
					sortable: true,
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
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'productplant'=>true)) ."',
							onBeforeLoad: function(param){
								param.slocid = $('#grheader-slocid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							readonly:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-grheader-grresult').datagrid('getEditor', {index: index, field:'productid'});
								var productname = $('#dg-grheader-grresult').datagrid('getEditor', {index: index, field:'productname'});
								var uomid = $('#dg-grheader-grresult').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-grheader-grresult').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-grheader-grresult').datagrid('getEditor', {index: index, field:'uom3id'});
								var uom4id = $('#dg-grheader-grresult').datagrid('getEditor', {index: index, field:'uom4id'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/index',array('grid'=>true,'getdata'=>true)) ."',
									'data':{'productid':$(productid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(productname.target).textbox('setValue',data.productname);
										$(uomid.target).combogrid('setValue',data.uom1);
										$(uom2id.target).combogrid('setValue',data.uom2);
										$(uom3id.target).combogrid('setValue',data.uom3);
										$(uom4id.target).combogrid('setValue',data.uom4);
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
					field:'qty1',
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
					},
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