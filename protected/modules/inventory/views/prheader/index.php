<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'prheaderid',
	'formtype'=>'masterdetail',
	'isxls'=>1,
	'ispost'=>1,
	'isreject'=>1,
	'ispurge'=>1,
	'isupload'=>1,
	'wfapp'=>'apppr',
	'url'=>Yii::app()->createUrl('inventory/prheader/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('inventory/prheader/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('inventory/prheader/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('inventory/prheader/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('inventory/prheader/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('inventory/prheader/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('inventory/prheader/reject',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('inventory/prheader/upload'),
	'downpdf'=>Yii::app()->createUrl('inventory/prheader/downpdf'),
	'downxls'=>Yii::app()->createUrl('inventory/prheader/downxls'),
	'columns'=>"
		{
			field:'prheaderid',
			title:'".GetCatalog('ID') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('apppr')."
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
			field:'prdate',
			title:'".GetCatalog('prdate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'formrequestno',
			title:'".GetCatalog('frno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'prno',
			title:'".GetCatalog('prno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'isjasa',
			title:'". GetCatalog('isjasa') ."',
			align:'center',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
				} else {
				return '';
				}
		}},
		{
			field:'slocfromid',
			title:'".GetCatalog('slocfrom') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.sloccode;
		}},
		{
			field:'addressbookid',
			title:'".GetCatalog('customer') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'requestedbyid',
			title:'".GetCatalog('requestedby') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.requestedbycode;
		}},
		{
			field:'description',
			title:'".GetCatalog('description') ."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'statusname',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return row.statusname;
		}},",
		'addload'=>"
		$('#prheader-prdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});	
		$('#tabdetails-prheader').tabs('disableTab',1);
		$('#tabdetails-prheader').tabs('disableTab',2);
	",
	'searchfield'=> array ('prheaderid','plantcode','prdate','prno','frno','sloccode','requestedbycode','description','productraw','productjasa','productresult','recordstatus'),
	'headerform'=> "
		<input type='hidden' id='prheader-isjasa-value' name='prheader-isjasa-value' />
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('prno')."</td>
				<td><input class='easyui-textbox' id='prheader-prno' name='prheader-prno' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('prdate')."</td>
				<td><input class='easyui-datebox' type='text' id='prheader-prdate' name='prheader-prdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='prheader-plantid' name='prheader-plantid' style='width:150px' data-options=\"
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
											'plantid':$('#prheader-plantid').combogrid('getValue'),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											var g = $('#prheaderid-slocfromid').combogrid('grid');
											var h = $('#prheaderid-formrequestid').combogrid('grid');
											g.datagrid({queryParams: {
												plantid: data.plantid}
											});
											h.datagrid({queryParams: {
												plantid: data.plantid}
											});
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
				<td>".GetCatalog('isjasa')."</td>
				<td><input class='easyui-checkbox' id='prheader-isjasa' name='prheader-isjasa' data-options=\"
				onChange: function(checked){
					if (checked == true) {
						$('#prheader-isjasa-value').val(1);
						$('#tabdetails-prheader').tabs('enableTab',0);
						$('#tabdetails-prheader').tabs('enableTab',1);
						$('#tabdetails-prheader').tabs('enableTab',2);
					} else {
						$('#prheader-isjasa-value').val(0);
						$('#tabdetails-prheader').tabs('enableTab',0);
						$('#tabdetails-prheader').tabs('disableTab',1);
						$('#tabdetails-prheader').tabs('disableTab',2);
					}
					purgepralldetail($('#prheader-prheaderid').val());
					$('#tabdetails-prheader').tabs('select',0);
				}
				\"></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('formrequest')."</td>
				<td>
					<select class='easyui-combogrid' id='prheader-formrequestid' name='prheader-formrequestid' style='width:250px' data-options=\"
						panelWidth: '700px',
						required: true,
						idField: 'formrequestid',
						textField: 'formrequestno',
						mode:'remote',
						url: '".Yii::app()->createUrl('inventory/formrequest/index',array('grid'=>true,'fpp'=>true)) ."',
						method: 'get',
						onShowPanel: function() {
							$('#prheader-formrequestid').combogrid('grid').datagrid('reload');
						},							
						onBeforeLoad: function(param) {
							param.plantid = $('#prheader-plantid').combogrid('getValue');
							param.isjasa = $('#prheader-isjasa-value').val();
						},
						onHidePanel: function(){
							jQuery.ajax({'url':'".Yii::app()->createUrl('inventory/formrequest/index',array('grid'=>true,'getdata'=>true)) ."',
								'data':{
									'formrequestid':$('#prheader-formrequestid').combogrid('getValue')
								},
								'type':'post',
								'dataType':'json',
								'success':function(data)
								{
									$('#prheader-slocfromid').combogrid('setValue',data.slocfromid);
									$('#prheader-requestedbyid').combogrid('setValue',data.requestedbyid);
									$('#prheader-description').textbox('setValue',data.description);
									jQuery.ajax({'url':'".Yii::app()->createUrl('inventory/prheader/generatedetail') ."',
										'data':{
											'id':$('#prheader-formrequestid').combogrid('getValue'),
											'hid':$('#prheader-prheaderid').val(),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#dg-prheader-prjasa').datagrid('reload');
											$('#dg-prheader-prresult').datagrid('reload');
											$('#dg-prheader-prraw').datagrid('reload');
										},
										'cache':false});
								},
								'cache':false});
						},
						columns: [[
								{field:'formrequestid',title:'".GetCatalog('formrequestid') ."',width:'50px'},
								{field:'formrequestno',title:'".GetCatalog('frno') ."',width:'200px'},
								{field:'sloccode',title:'".GetCatalog('slocfrom') ."',width:'220px'},
								{field:'requestedbycode',title:'".GetCatalog('requestedby') ."',width:'150px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('slocfrom')."</td>
				<td>
					<select class='easyui-combogrid' id='prheader-slocfromid' name='prheader-slocfromid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						readonly:true,
						idField: 'slocid',
						textField: 'sloccode',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/sloc/indextrxplant',array('grid'=>true)) ."',
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
				<td>".GetCatalog('requestedby')."</td>
				<td>
					<select class='easyui-combogrid' id='prheader-requestedbyid' name='prheader-requestedbyid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						readonly:true,
						idField: 'requestedbyid',
						textField: 'requestedbycode',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/requestedby/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'requestedbyid',title:'".GetCatalog('requestedbyid') ."',width:'50px'},
								{field:'requestedbycode',title:'".GetCatalog('requestedbycode') ."',width:'150px'},
								{field:'description',title:'".GetCatalog('description')."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('description')."</td>
				<td><input class='easyui-textbox' id='prheader-description' name='prheader-description' data-options='multiline:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addonscripts'=>"
		function purgepralldetail(\$id) {
			jQuery.ajax({'url':'".Yii::app()->createUrl('inventory/prheader/purgealldetail') ."',
				'data':{
					'id':\$id,
				},
				'type':'post',
				'dataType':'json',
				'success':function(data)
				{
					$('#dg-prheader-prraw').datagrid('reload');
					$('#dg-prheader-prjasa').datagrid('reload');
					$('#dg-prheader-prresult').datagrid('reload');
				},
				'cache':false});
		}
	",
	'purgebuttons'=>"
		
		<a href='javascript:void(0)' title='".getCatalog('closefpp')."' class='easyui-linkbutton' iconCls='icon-lock' plain='true' onclick='closefpp()'></a>
		
	",
	'addonscripts'=>"
		
		function closefpp() {
			var rows = $('#dg-prheader').edatagrid('getSelected');
			jQuery.ajax({'url':'".$this->createUrl('prheader/closefpp') ."',
				'data':{'id':rows.prheaderid},'type':'post','dataType':'json',
				'success':function(data)
				{
					show('Pesan',data.msg);
					$('#dg-prheader').edatagrid('reload');				
				} ,
				'cache':false});
		};
		
		",
	'loadsuccess' => "
		$('#tabdetails-prheader').tabs('disableTab',1);
		$('#tabdetails-prheader').tabs('disableTab',2);
		$('#tabdetails-prheader').tabs('select',0);
		$('#prheader-prno').textbox('setValue',data.prno);
		$('#prheader-prdate').datebox('setValue',data.prdate);
		$('#prheader-plantid').combogrid('setValue',data.plantid);
		$('#prheader-formrequestid').combogrid('setValue',data.formrequestid);
		if (data.isjasa == 0) {
			$('#tabdetails-prheader').tabs('enableTab',0);
			$('#tabdetails-prheader').tabs('disableTab',1);
			$('#tabdetails-prheader').tabs('disableTab',2);
		} else {
			$('#tabdetails-prheader').tabs('enableTab',0);
			$('#tabdetails-prheader').tabs('enableTab',1);
			$('#tabdetails-prheader').tabs('enableTab',2);
		}
		$('#prheader-slocfromid').combogrid('setValue',data.slocfromid);
		$('#prheader-requestedbyid').combogrid('setValue',data.requestedbyid);
		$('#prheader-description').textbox('setValue',data.description);
	",
	'columndetails'=> array (
		array(
			'id'=>'prraw',
			'idfield'=>'prrawid',
			'urlsub'=>Yii::app()->createUrl('inventory/prheader/indexraw',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/prheader/searchraw',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('inventory/prheader/saveraw',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('inventory/prheader/saveraw',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('inventory/prheader/purgeraw',array('grid'=>true)),
			'ispurge'=>1,'isnew'=>0,'iswrite'=>0,
			'subs'=>"
				{field:'prrawid',title:'".getCatalog('ID') ."',width:'60px'},
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
				{field:'qtyoutpo',title:'".GetCatalog('qtyoutpo') ."',formatter: function(value,row,index){
					return formatnumber('',value);
				},width:'100px'},	
				{field:'qtyoutfpp',title:'".GetCatalog('qtyoutfpp') ."',formatter: function(value,row,index){
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
				{field:'reqdate',title:'".GetCatalog('reqdate') ."',width:'100px'},
				{field:'sloccode',title:'".GetCatalog('slocto') ."',width:'100px'},
				{field:'mesincode',title:'".GetCatalog('mesin') ."',width:'150px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'prheaderid',
					title:'".GetCatalog('prheaderid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'prrawid',
					title:'".GetCatalog('prrawid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'formrequestrawid',
					title:'".GetCatalog('formrequestrawid') ."',
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
				},{
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
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'productplant'=>true)) ."',
							onBeforeLoad: function(param){
								param.slocid = $('#prheader-slocfromid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-prheader-prraw').datagrid('getEditor', {index: index, field:'productid'});
								var uomid = $('#dg-prheader-prraw').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-prheader-prraw').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-prheader-prraw').datagrid('getEditor', {index: index, field:'uom3id'});
								var uom4id = $('#dg-prheader-prraw').datagrid('getEditor', {index: index, field:'uom4id'});
								var stdqty = $('#dg-prheader-prraw').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-prheader-prraw').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-prheader-prraw').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-prheader-prraw').datagrid('getEditor', {index: index, field:'stdqty4'});
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
								var stdqty = $('#dg-prheader-prraw').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-prheader-prraw').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-prheader-prraw').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-prheader-prraw').datagrid('getEditor', {index: index, field:'stdqty4'});								
								var qty2 = $('#dg-prheader-prraw').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-prheader-prraw').datagrid('getEditor', {index: index, field:'qty3'});								
								var qty4 = $('#dg-prheader-prraw').datagrid('getEditor', {index: index, field:'qty4'});						
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
					width:'80px',
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
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uom4code;
					}
				},
				{
					field:'reqdate',
					title:'".GetCatalog('reqdate')."',
					editor: {
						type: 'datebox',
						options: {
							required:true
						}
					},
					sortable: true,
					width:'100px',
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
							onBeforeLoad: function(param){
								param.plantid = $('#prheader-plantid').combogrid('getValue');
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
			'id'=>'prjasa',
			'idfield'=>'prjasaid',
			'urlsub'=>Yii::app()->createUrl('inventory/prheader/indexjasa',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/prheader/searchjasa',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('inventory/prheader/savejasa',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('inventory/prheader/savejasa',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('inventory/prheader/purgejasa',array('grid'=>true)),
			'ispurge'=>1,'isnew'=>0,
			'subs'=>"
				{field:'prjasaid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'reqdate',title:'".GetCatalog('reqdate') ."',width:'100px'},
				{field:'sloccode',title:'".GetCatalog('sloccode') ."',width:'100px'},
				{field:'mesincode',title:'".GetCatalog('mesin') ."',width:'150px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'prheaderid',
					title:'".GetCatalog('prheaderid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'prjasaid',
					title:'".GetCatalog('prjasaid') ."',
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
								param.slocid = $('#prheader-slocfromid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-prheader-prjasa').datagrid('getEditor', {index: index, field:'productid'});
								var productname = $('#dg-prheader-prjasa').datagrid('getEditor', {index: index, field:'productname'});
								var uomid = $('#dg-prheader-prjasa').datagrid('getEditor', {index: index, field:'uomid'});
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
					width:'80px',
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
									param.plantid = $('#prheader-plantid').combogrid('getValue');
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
			'id'=>'prresult',
			'idfield'=>'prresultid',
			'urlsub'=>Yii::app()->createUrl('inventory/prheader/indexresult',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/prheader/searchresult',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('inventory/prheader/saveresult',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('inventory/prheader/saveresult',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('inventory/prheader/purgeresult',array('grid'=>true)),
			'ispurge'=>1,'isnew'=>0,'iswrite'=>0,
			'subs'=>"
				{field:'prresultid',title:'".getCatalog('ID') ."',width:'60px'},
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
					field:'prheaderid',
					title:'".GetCatalog('prheaderid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'prresultid',
					title:'".GetCatalog('prresultid') ."',
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
							panelWidth:'650px',
							mode: 'remote',
							method:'get',
							idField:'productid',
							textField:'productname',
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'productplant'=>true)) ."',
							onBeforeLoad: function(param){
								param.slocid = $('#prheader-slocfromid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-prheader-prresult').datagrid('getEditor', {index: index, field:'productid'});
								var productname = $('#dg-prheader-prresult').datagrid('getEditor', {index: index, field:'productname'});
								var uomid = $('#dg-prheader-prresult').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-prheader-prresult').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-prheader-prresult').datagrid('getEditor', {index: index, field:'uom3id'});
								var uom4id = $('#dg-prheader-prresult').datagrid('getEditor', {index: index, field:'uom4id'});
								var stdqty2 = $('#dg-bom-bomdetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-bom-bomdetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-bom-bomdetail').datagrid('getEditor', {index: index, field:'stdqty4'});
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
										$(stdqty2.target).numberbox('setValue',data.qty2);
										$(stdqty3.target).numberbox('setValue',data.qty3);
										$(stdqty4.target).numberbox('setValue',data.qty4);
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
							onChange: function(newValue,oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var stdqty2 = $('#dg-prheader-prresult').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-prheader-prresult').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-prheader-prresult').datagrid('getEditor', {index: index, field:'stdqty4'});								
								var qty2 = $('#dg-prheader-prresult').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-prheader-prresult').datagrid('getEditor', {index: index, field:'qty3'});								
								var qty4 = $('#dg-prheader-prresult').datagrid('getEditor', {index: index, field:'qty4'});						
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
	),	
));
