<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'packinglistid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'apppacklist',
	'url'=>Yii::app()->createUrl('inventory/packinglist/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('inventory/packinglist/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('inventory/packinglist/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('inventory/packinglist/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('inventory/packinglist/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('inventory/packinglist/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('inventory/packinglist/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/packinglist/downpdf'),
	'columns'=>"
		{
			field:'packinglistid',
			title:'".GetCatalog('packinglistid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatuscolor('apppacklist')."
		}},
		{
			field:'companyid',
			title:'".GetCatalog('company') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
			return row.companyname;
		}},
		{
			field:'plantid',
			title:'".GetCatalog('plant') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return row.plantcode;
		}},
		{
			field:'packinglistdate',
			title:'".GetCatalog('packinglistdate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'packinglistno',
			title:'".GetCatalog('packinglistno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'giheaderid',
			title:'".GetCatalog('giheader') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return row.gino;
		}},
		{
			field:'sono',
			title:'".GetCatalog('soheader') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
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
			field:'addressbookid',
			title:'".GetCatalog('customer') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
			return row.customername;
		}},
		{
			field:'supplierid',
			title:'".GetCatalog('ekspedisi') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
			return row.suppliername;
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
			field:'pebno',
			title:'".GetCatalog('pebno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'statusname',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},",
	'searchfield'=> array ('packinglistid','plantcode','packinglistno','gino','customername','addressname','productname'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('packinglistno')."</td>
				<td><input class='easyui-textbox' id='packinglist-packinglistno' name='packinglist-packinglistno' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('packinglistdate')."</td>
				<td><input class='easyui-datebox' id='packinglist-packinglistdate' name='packinglist-packinglistdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('plantcode')."</td>
				<td>
					<select class='easyui-combogrid' id='packinglist-plantid' name='packinglist-plantid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'plantid',
						textField: 'plantcode',
						pagination:true,
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
				<td>".GetCatalog('giheader')."</td>
				<td>
					<select class='easyui-combogrid' id='packinglist-giheaderid' name='packinglist-giheaderid' style='width:250px' data-options=\"
						panelWidth: '700px',
						required: true,
						idField: 'giheaderid',
						textField: 'gino',
						mode:'remote',
						url: '".Yii::app()->createUrl('inventory/giheader/index',array('grid'=>true,'gipl'=>true)) ."',
						method: 'get',
						onShowPanel: function() {
							$('#packinglist-giheaderid').combogrid('grid').datagrid('reload');
						},							
						onBeforeLoad: function(param) {
							param.plantid = $('#packinglist-plantid').combogrid('getValue');
						},
						onHidePanel: function(){
							jQuery.ajax({'url':'".Yii::app()->createUrl('inventory/giheader/index',array('grid'=>true,'getdata'=>true)) ."',
								'data':{
									'giheaderid':$('#packinglist-giheaderid').combogrid('getValue'),
								},
								'type':'post',
								'dataType':'json',
								'success':function(data)
								{
									$('#packinglist-addresstoid').combogrid('setValue',data.addresstoid);
									$('#packinglist-pocustno').textbox('setValue',data.pocustno);
									$('#packinglist-addressbookid').combogrid('setValue',data.addressbookid);
									$('#packinglist-supplierid').combogrid('setValue',data.supplierid);
									$('#packinglist-pebno').textbox('setValue',data.pebno);
									$('#packinglist-sopir').textbox('setValue',data.sopir);
									$('#packinglist-nomobil').textbox('setValue',data.nomobil);
									$('#packinglist-soheaderid').combogrid('setValue',data.soheaderid);
									jQuery.ajax({'url':'".Yii::app()->createUrl('inventory/packinglist/generatedetail') ."',
										'data':{
											'id':$('#packinglist-giheaderid').combogrid('getValue'),
											'hid':$('#packinglist-packinglistid').val(),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#dg-packinglist-packlistdetail').datagrid('reload');
										},
										'cache':false});
								},
								'cache':false});
						},
						columns: [[
								{field:'giheaderid',title:'".GetCatalog('giheaderid') ."',width:'50px'},
								{field:'gino',title:'".GetCatalog('gino') ."',width:'150px'},
								{field:'sono',title:'".GetCatalog('sono') ."',width:'150px'},
								{field:'fullname',title:'".GetCatalog('fullname') ."',width:'250px'}
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('soheader')."</td>
				<td>
					<select class='easyui-combogrid' id='packinglist-soheaderid' name='packinglist-soheaderid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'soheaderid',
						textField: 'sono',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/customer/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						readonly: true,
						columns: [[
							{field:'soheaderid',title:'".GetCatalog('soheaderid') ."',width:'50px'},
							{field:'sono',title:'".GetCatalog('sono') ."',width:'350px'},
						]],
						fitColumns: true\">
					</select>
			</tr>
			<tr>
				<td>".GetCatalog('pocustno')."</td>
				<td><input class='easyui-textbox' id='packinglist-pocustno' name='packinglist-pocustno' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('customer')."</td>
				<td>
					<select class='easyui-combogrid' id='packinglist-addressbookid' name='packinglist-addressbookid' style='width:250px' data-options=\"
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
			</tr>
				<td>".GetCatalog('Ekspedisi')."</td>
				<td>
					<select class='easyui-combogrid' id='packinglist-supplierid' name='packinglist-supplierid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/supplier/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						readonly: true,
						columns: [[
							{field:'addressbookid',title:'".GetCatalog('addressbookid') ."',width:'50px'},
							{field:'fullname',title:'".GetCatalog('fullname') ."',width:'350px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('shipto')."</td>
				<td>					<select class='easyui-combogrid' id='packinglist-addresstoid' name='packinglist-addresstoid' style='width:250px' data-options=\"
						panelWidth: '500px',
						multiline:true,
						required: true,
						idField: 'addressid',
						textField: 'addressname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/customer/indexaddress',array('grid'=>true)) ."',
						method: 'get',
						onBeforeLoad: function(param){
							param.id = $('#packinglist-addressbookid').val();
						},
						columns: [[
							{field:'addressid',title:'".GetCatalog('addressid') ."',width:'50px'},
							{field:'addressname',title:'".GetCatalog('addressname') ."',width:'250px'},
						]],
						fitColumns: true \">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('nomobil')."</td>
				<td><input class='easyui-textbox' id='packinglist-nomobil' name='packinglist-nomobil' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('sopir')."</td>
				<td><input class='easyui-textbox' id='packinglist-sopir' name='packinglist-sopir' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('pebno')."</td>
				<td><input class='easyui-textbox' id='packinglist-pebno' name='packinglist-pebno' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='packinglist-headernote' name='packinglist-headernote' data-options='multiline:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addload'=>"
		$('#packinglist-packinglistdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});	",
	'loadsuccess' => "
		$('#packinglist-packinglistno').textbox('setValue',data.packinglistno);
		$('#packinglist-packinglistdate').datebox('setValue',data.packinglistdate);
		$('#packinglist-plantid').combogrid('setValue',data.plantid);
		$('#packinglist-giheaderid').combogrid('setValue',data.giheaderid);
		$('#packinglist-pocustno').textbox('setValue',data.pocustno);
		$('#packinglist-sono').textbox('setValue',data.sono);
		$('#packinglist-addressbookid').combogrid('setValue',data.addressbookid);
		$('#packinglist-addresstoid').combogrid('setValue',data.addresstoid);
		$('#packinglist-supplierid').combogrid('setValue',data.supplierid);
		$('#packinglist-nomobil').textbox('setValue',data.nomobil);
		$('#packinglist-sopir').textbox('setValue',data.sopir);
		$('#packinglist-pebno').textbox('setValue',data.pebno);
		$('#packinglist-headernote').textbox('setValue',data.headernote);
	",
	'columndetails'=> array (
		array(
			'id'=>'packlistdetail',
			'idfield'=>'packlistdetailid',
			'urlsub'=>Yii::app()->createUrl('inventory/packinglist/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/packinglist/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('inventory/packinglist/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('inventory/packinglist/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('inventory/packinglist/purgedetail',array('grid'=>true)),
			'ispurge'=>1,'isnew'=>0,
			'subs'=>"
			{field:'packlistdetailid',title:'".GetCatalog('ID') ."',width:'80px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
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
				{field:'gross',title:'".GetCatalog('gross') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'grossuomcode',title:'".GetCatalog('grossuomcode') ."',width:'80px'},
				{field:'net',title:'".GetCatalog('net') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'netuomcode',title:'".GetCatalog('netuomcode') ."',width:'80px'},
				{field:'nolot',title:'".GetCatalog('nolot') ."',width:'160px'},
				{field:'batchno',title:'".GetCatalog('batchno') ."',width:'160px'},
				{field:'certoano',title:'".GetCatalog('certoano') ."',width:'160px'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'packinglistid',
					title:'".GetCatalog('packinglistid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'packlistdetailid',
					title:'".GetCatalog('packlistdetail') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'gidetailid',
					title:'".GetCatalog('ID GI') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'productname',
					title:'".GetCatalog('productname')."',
					editor: {
						type: 'textbox',
						options:{
							readonly:true,
						}
					},
					sortable: true,
					width:'300px',
					formatter: function(value,row,index){
										return value;
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
									idField:'unitofmeasureid',
									textField:'uomcode',
									url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true)) ."',
									fitColumns:true,
									pagination:true,
									required:true,
									queryParams:{
										combo:true
									},
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
									url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true)) ."',
									fitColumns:true,
									pagination:true,
									required:true,
									queryParams:{
										combo:true
									},
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
					field:'net',
					title:'".GetCatalog('net') ."',
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
					field:'netuom',
					title:'".GetCatalog('netuom') ."',
					editor:{
							type:'combogrid',
							options:{
									panelWidth:'500px',
									mode : 'remote',
									method:'get',
									idField:'unitofmeasureid',
									textField:'uomcode',
									url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true)) ."',
									fitColumns:true,
									pagination:true,
									queryParams:{
										combo:true
									},
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
										return row.netuomcode;
					}
				},
				{
					field:'gross',
					title:'".GetCatalog('gross') ."',
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
					field:'grossuom',
					title:'".GetCatalog('grossuom') ."',
					editor:{
							type:'combogrid',
							options:{
									panelWidth:'500px',
									mode : 'remote',
									method:'get',
									idField:'unitofmeasureid',
									textField:'uomcode',
									url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true)) ."',
									fitColumns:true,
									pagination:true,
									queryParams:{
										combo:true
									},
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
										return row.grossuomcode;
					}
				},
				{
					field:'nolot',
					title:'".GetCatalog('nolot')."',
					editor: {
						type: 'textbox',
						options:{
							required:true,
						}
					},
					sortable: true,
					width:'100px',
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'certoaid',
					title:'".GetCatalog('certoa') ."',
					editor:{
							type:'combogrid',
							options:{
									panelWidth:'500px',
									mode : 'remote',
									method:'get',
									idField:'certoaid',
									textField:'certoano',
									url:'".Yii::app()->createUrl('production/certoa/index',array('grid'=>true,'coapl'=>true)) ."',
									fitColumns:true,
									onBeforeLoad: function(param) {
										param.soheaderid = $('#packinglist-soheaderid').combogrid('getValue');
									},
									pagination:true,
									queryParams:{
										combo:true
									},
									loadMsg: '".GetCatalog('pleasewait')."',
									columns:[[
										{field:'certoaid',title:'".GetCatalog('certoaid')."',width:'50px'},
										{field:'certoano',title:'".GetCatalog('certoano')."',width:'150px'},
										{field:'sono',title:'".GetCatalog('sono')."',width:'150px'},
									]]
							}	
						},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.certoano;
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