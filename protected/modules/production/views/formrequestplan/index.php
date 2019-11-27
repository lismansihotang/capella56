<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'formrequestid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'ispurge'=>1,
	'wfapp'=>'appdaok',
	'url'=>Yii::app()->createUrl('production/formrequestplan/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('production/formrequestplan/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('production/formrequestplan/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('production/formrequestplan/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('production/formrequestplan/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('production/formrequestplan/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('production/formrequestplan/reject',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('production/formrequestplan/upload'),
	'downpdf'=>Yii::app()->createUrl('production/formrequestplan/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/formrequestplan/downxls'),
	'columns'=>"
		{
			field:'formrequestid',
			title:'".GetCatalog('ID') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".getStatusColor('appdaok')."
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
			field:'formrequestdate',
			title:'".GetCatalog('formrequestdate') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'formrequestno',
			title:'".GetCatalog('formrequestno') ."',
			sortable: true,
			width:'180px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productplanid',
			title:'".GetCatalog('productplan') ."',
			sortable: true,
			width:'180px',
			formatter: function(value,row,index){
				return row.productplanno;
		}},
		{
			field:'isjasa',
			title:'". GetCatalog('isjasa') ."',
			align:'center',
			width:'60px',
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
			width:'100px',
			formatter: function(value,row,index){
				return row.sloccode;
		}},
		{
			field:'requestedbyid',
			title:'".GetCatalog('requestedby') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return row.requestedbycode;
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
		$('#formrequestplan-formrequestdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});	
$('#formrequestplan-plantid').combogrid('setValue',".Yii::app()->user->defaultplant.");
		$('#tabdetails-formrequestplan').tabs('disableTab',1);
		$('#tabdetails-formrequestplan').tabs('disableTab',2);
	",
	'searchfield'=> array ('formrequestid','plantcode','formrequestdate','formrequestno','productplanno','sloccode','requestedbycode','productname','description','recordstatus'),
	'headerform'=> "
	<input type='hidden' id='formrequestplan-isjasa-value' name='formrequestplan-isjasa-value'></input>
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('formrequestdate')."</td>
				<td><input class='easyui-datebox' type='text' id='formrequestplan-formrequestdate' name='formrequestplan-formrequestdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
				<td>".getCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='formrequestplan-plantid' name='formrequestplan-plantid' style='width:150px' data-options=\"
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
				<td>".GetCatalog('productplan')."</td>
				<td>
					<select class='easyui-combogrid' id='formrequestplan-productplanid' name='formrequestplan-productplanid' style='width:250px' data-options=\"
						panelWidth: '700px',
						idField: 'productplanid',
						textField: 'productplanno',
						mode:'remote',
						required:true,
						url: '".Yii::app()->createUrl('production/productplan/index',array('grid'=>true,'ppfpbok'=>true)) ."',
						method: 'get',
						onShowPanel: function() {
							$('#formrequestplan-productplanid').combogrid('grid').datagrid('reload');
						},							
						onBeforeLoad: function(param) {
							param.plantid = $('#formrequestplan-plantid').combogrid('getValue');
						},
						columns: [[
								{field:'productplanid',title:'".GetCatalog('productplanid') ."',width:'50px'},
								{field:'productplanno',title:'".GetCatalog('productplanno') ."',width:'200px'},
								{field:'fullname',title:'".GetCatalog('fullname') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
<td>".GetCatalog('isjasa')."</td>
				<td><input class='easyui-checkbox' id='formrequestplan-isjasa' name='formrequestplan-isjasa' data-options=\"
				onChange: function(checked){
					if (checked == true) {
						$('#formrequestplan-isjasa-value').val(1);
						$('#tabdetails-formrequestplan').tabs('enableTab',0);
						$('#tabdetails-formrequestplan').tabs('enableTab',1);
						$('#tabdetails-formrequestplan').tabs('enableTab',2);
					} else {
						$('#formrequestplan-isjasa-value').val(0);
						$('#tabdetails-formrequestplan').tabs('enableTab',0);
						$('#tabdetails-formrequestplan').tabs('disableTab',1);
						$('#tabdetails-formrequestplan').tabs('disableTab',2);
					}
					purgefralldetail($('#formrequestplan-formrequestid').val());
					$('#tabdetails-formrequestplan').tabs('select',0);
				}
				\"></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('slocfrom')."</td>
				<td>
					<select class='easyui-combogrid' id='formrequestplan-slocfromid' name='formrequestplan-slocfromid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'slocid',
						textField: 'sloccode',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/sloc/indexslocpp',array('grid'=>true)) ."',
						onShowPanel: function() {
							$('#formrequestplan-slocfromid').combogrid('grid').datagrid('reload');
						},							
						onBeforeLoad: function(param) {
							param.productplanid = $('#formrequestplan-productplanid').combogrid('getValue');
						},
						onHidePanel: function(){
									jQuery.ajax({'url':'".Yii::app()->createUrl('production/formrequestplan/generatedetail') ."',
										'data':{
											'id':$('#formrequestplan-productplanid').combogrid('getValue'),
											'hid':$('#formrequestplan-formrequestid').val(),
											'jid':$('#formrequestplan-slocfromid').combogrid('getValue'),
											'kid':$('#formrequestplan-isjasa-value').val()
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#dg-formrequestplan-formrequestresult').datagrid('reload');
											$('#dg-formrequestplan-formrequestraw').datagrid('reload');
										},
										'cache':false});
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
<td>".GetCatalog('requestedby')."</td>
				<td>
					<select class='easyui-combogrid' id='formrequestplan-requestedbyid' name='formrequestplan-requestedbyid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
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
				<td><input class='easyui-textbox' id='formrequestplan-description' name='formrequestplan-description' data-options='multiline:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'writebuttons'=>"
		
		<a href='javascript:void(0)' title='".getCatalog('closefpb')."' class='easyui-linkbutton' iconCls='icon-tip' plain='true' onclick='closefpb()'></a>
		
	",
	'addonscripts'=>"
		
		function closefpb() {
			var rows = $('#dg-formrequestplan').edatagrid('getSelected');
			jQuery.ajax({'url':'".$this->createUrl('formrequestplan/closefpb') ."',
				'data':{'id':rows.formrequestid},'type':'post','dataType':'json',
				'success':function(data)
				{
					show('Pesan',data.msg);
					$('#dg-formrequestplan').edatagrid('reload');				
				} ,
				'cache':false});
		};
		
		",
	'loadsuccess' => "
		$('#tabdetails-formrequest').tabs('disableTab',1);
		$('#tabdetails-formrequest').tabs('disableTab',2);
		$('#tabdetails-formrequestplan').tabs('select',0);
		$('#formrequestplan-formrequestno').textbox('setValue',data.formrequestno);
		$('#formrequestplan-formrequestdate').datebox('setValue',data.formrequestdate);
		$('#formrequestplan-plantid').combogrid('setValue',data.plantid);
		if (data.isjasa == 0) {
			$('#tabdetails-formrequest').tabs('enableTab',0);
			$('#tabdetails-formrequest').tabs('disableTab',1);
			$('#tabdetails-formrequest').tabs('disableTab',2);
		} else {
			$('#tabdetails-formrequest').tabs('enableTab',0);
			$('#tabdetails-formrequest').tabs('enableTab',1);
			$('#tabdetails-formrequest').tabs('enableTab',2);
		}
		$('#formrequestplan-slocfromid').combogrid('setValue',data.slocfromid);
		$('#formrequestplan-requestedbyid').combogrid('setValue',data.requestedbyid);
		$('#formrequestplan-description').textbox('setValue',data.description);
	",
	'columndetails'=> array (
		array(
			'id'=>'formrequestraw',
			'idfield'=>'formrequestrawid',
			'urlsub'=>Yii::app()->createUrl('production/formrequestplan/indexraw',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('production/formrequestplan/searchraw',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('production/formrequestplan/saveraw',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('production/formrequestplan/saveraw',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('production/formrequestplan/purgeraw',array('grid'=>true)),
			'subs'=>"
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
				{field:'prqty',title:'".GetCatalog('prqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'tsqty',title:'".GetCatalog('tsqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uomcode);
					},width:'100px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uom2code);
					},width:'100px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uom3code);
					},width:'100px'},
				{field:'reqdate',title:'".GetCatalog('reqdate') ."',width:'100px'},
				{field:'sloccode',title:'".GetCatalog('sloccode') ."',width:'100px'},
				{field:'namamesin',title:'".GetCatalog('mesin') ."',width:'150px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'formrequestid',
					title:'".GetCatalog('formrequestid') ."',
					hidden:true,
					sortable: true,
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
								param.slocid = $('#formrequestplan-slocfromid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-formrequestplan-formrequestraw').datagrid('getEditor', {index: index, field:'productid'});
								var uomid = $('#dg-formrequestplan-formrequestraw').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-formrequestplan-formrequestraw').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-formrequestplan-formrequestraw').datagrid('getEditor', {index: index, field:'uom3id'});
								var stdqty = $('#dg-formrequestplan-formrequestraw').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-formrequestplan-formrequestraw').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-formrequestplan-formrequestraw').datagrid('getEditor', {index: index, field:'stdqty3'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/index',array('grid'=>true,'getdata'=>true)) ."',
									'data':{'productid':$(productid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(uomid.target).combogrid('setValue',data.uom1);
										$(uom2id.target).combogrid('setValue',data.uom2);
										$(uom3id.target).combogrid('setValue',data.uom3);
										$(stdqty.target).numberbox('setValue',data.qty1);
										$(stdqty2.target).numberbox('setValue',data.qty2);
										$(stdqty3.target).numberbox('setValue',data.qty3);
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
								var stdqty = $('#dg-formrequestplan-formrequestraw').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-formrequestplan-formrequestraw').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-formrequestplan-formrequestraw').datagrid('getEditor', {index: index, field:'stdqty3'});
								var qty2 = $('#dg-formrequestplan-formrequestraw').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-formrequestplan-formrequestraw').datagrid('getEditor', {index: index, field:'qty3'});								
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
							onBeforeLoad: function(param){
								param.plantid = $('#formrequestplan-plantid').combogrid('getValue');
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
							textField:'kodemesin',
							url:'".Yii::app()->createUrl('common/mesin/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'mesinid',title:'".getCatalog('mesinid')."',width:'80px'},
								{field:'namamesin',title:'".getCatalog('namamesin')."',width:'150px'},
								{field:'kodemesin',title:'".getCatalog('kodemesin')."',width:'150px'},
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
			'id'=>'formrequestjasa',
			'idfield'=>'formrequestjasaid',
			'urlsub'=>Yii::app()->createUrl('production/formrequestplan/indexjasa',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('production/formrequestplan/searchjasa',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('production/formrequestplan/savejasa',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('production/formrequestplan/savejasa',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('production/formrequestplan/purgejasa',array('grid'=>true)),
			'subs'=>"
				{field:'formrequestjasaid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'prqty',title:'".GetCatalog('prqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'tsqty',title:'".GetCatalog('tsqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'150px'},
				{field:'reqdate',title:'".GetCatalog('reqdate') ."',width:'100px'},
				{field:'sloccode',title:'".GetCatalog('slocto') ."',width:'100px'},
				{field:'namamesin',title:'".GetCatalog('mesin') ."',width:'150px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'formrequestid',
					title:'".GetCatalog('formrequestid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'formrequestjasaid',
					title:'".GetCatalog('formrequestjasaid') ."',
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
					width:'250px',
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
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'productplantjasa'=>true)) ."',
							onBeforeLoad: function(param){
								param.slocid = $('#formrequestplan-slocfromid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-formrequestplan-formrequestjasa').datagrid('getEditor', {index: index, field:'productid'});
								var uomid = $('#dg-formrequestplan-formrequestjasa').datagrid('getEditor', {index: index, field:'uomid'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/index',array('grid'=>true,'getdata'=>true)) ."',
									'data':{'productid':$(productid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(uomid.target).combogrid('setValue',data.uom1);
										
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
										param.plantid = $('#formrequestplan-plantid').combogrid('getValue');
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
							url:'".Yii::app()->createUrl('common/mesin/index',array('grid'=>true,'sloc'=>true)) ."',
							onBeforeLoad: function(param) {
								param.plantid = $('#formrequestplan-plantid').combogrid('getValue');
							},
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
			'id'=>'formrequestresult',
			'idfield'=>'formrequestresultid',
			'urlsub'=>Yii::app()->createUrl('production/formrequestplan/indexresult',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('production/formrequestplan/searchresult',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('production/formrequestplan/saveresult',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('production/formrequestplan/saveresult',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('production/formrequestplan/purgeresult',array('grid'=>true)),
			'subs'=>"
				{field:'formrequestresultid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'productcode',title:'".GetCatalog('productcode') ."',width:'100px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'250px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uomcode);
					},width:'100px'},
				{field:'prqty',title:'".GetCatalog('prqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uomcode);
					},width:'100px'},
				{field:'tsqty',title:'".GetCatalog('tsqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uomcode);
					},width:'100px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uom2code);
					},width:'100px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uom3code);
					},width:'100px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'formrequestid',
					title:'".GetCatalog('formrequestid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'formrequestresultid',
					title:'".GetCatalog('formrequestresultid') ."',
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
					field:'productid',
					title:'".getCatalog('productname') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'650px',
							mode: 'remote',
							method:'get',
							idField:'productid',
							textField:'productcode',
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'productplant'=>true)) ."',
							onBeforeLoad: function(param){
								param.slocid = $('#formrequestplan-slocfromid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-formrequestplan-formrequestresult').datagrid('getEditor', {index: index, field:'productid'});
								var uomid = $('#dg-formrequestplan-formrequestresult').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-formrequestplan-formrequestresult').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-formrequestplan-formrequestresult').datagrid('getEditor', {index: index, field:'uom3id'});
								var uom4id = $('#dg-formrequestplan-formrequestresult').datagrid('getEditor', {index: index, field:'uom4id'});
								var stdqty = $('#dg-formrequestplan-formrequestresult').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-formrequestplan-formrequestresult').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-formrequestplan-formrequestresult').datagrid('getEditor', {index: index, field:'stdqty3'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/index',array('grid'=>true,'getdata'=>true)) ."',
									'data':{'productid':$(productid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(uomid.target).combogrid('setValue',data.uom1);
										$(uom2id.target).combogrid('setValue',data.uom2);
										$(uom3id.target).combogrid('setValue',data.uom3);
										$(stdqty.target).numberbox('setValue',data.qty1);
										$(stdqty2.target).numberbox('setValue',data.qty2);
										$(stdqty3.target).numberbox('setValue',data.qty3);
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
								var stdqty = $('#dg-formrequestplan-formrequestresult').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-formrequestplan-formrequestresult').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-formrequestplan-formrequestresult').datagrid('getEditor', {index: index, field:'stdqty3'});
								var qty2 = $('#dg-formrequestplan-formrequestresult').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-formrequestplan-formrequestresult').datagrid('getEditor', {index: index, field:'qty3'});								
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
