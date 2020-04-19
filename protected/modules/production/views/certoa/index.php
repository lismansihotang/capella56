<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'certoaid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'isupload'=>0,
	'wfapp'=>'appcoa',
	'url'=>Yii::app()->createUrl('production/certoa/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('production/certoa/getData'),
	'saveurl'=>Yii::app()->createUrl('production/certoa/save'),
	'destroyurl'=>Yii::app()->createUrl('production/certoa/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('production/certoa/upload'),
	'approveurl'=>Yii::app()->createUrl('production/certoa/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('production/certoa/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('production/certoa/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/certoa/downxls'),
	'columns'=>"
		{
			field:'certoaid',
			title:'".getCatalog('certoaid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".getStatusColor('appcoa')."
		}},
		{
			field:'companyname',
			title:'".getCatalog('company') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'plantid',
			title:'".getCatalog('plantcode') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.plantcode;
		}},
		{
			field:'certoano',
			title:'".getCatalog('certoano') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'certoadate',
			title:'".getCatalog('certoadate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'sono',
			title:'".getCatalog('soheader') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'fullname',
			title:'".getCatalog('customer') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'materialtypecode',
			title:'".getCatalog('materialtypecode') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productname',
			title:'".getCatalog('productname') ."',
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'bomversion',
			title:'".getCatalog('bom') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'jumkirim',
			title:'".getCatalog('jumkirim') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'status',
			title:'".GetCatalog('status')."',
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'description',
			title:'".getCatalog('description') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'statusname',
			title:'".getCatalog('recordstatusname') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}},
	",
	'addload'=>"
		$('#certoa-certoadate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		}),
		$('#certoa-productiondate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
	",
	'searchfield'=> array ('certoaid','certoano','certoadate','productiondate','plantcode','customer','productcode','productname','bomversion','sono'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('certoano')."</td>
				<td><input class='easyui-textbox' id='certoa-certoano' name='certoa-certoano' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('certoadate')."</td>
				<td><input class='easyui-datebox' id='certoa-certoadate' name='certoa-certoadate' data-options='formatter:dateformatter,readonly:false,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='certoa-plantid' name='certoa-plantid' style='width:150px' data-options=\"
								panelWidth: '500px',
								idField: 'plantid',
								required: true,
								textField: 'plantcode',
								mode: 'remote',
								url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'combo'=>true)) ."',
								method: 'get',
								onHidePanel: function(){
									jQuery.ajax({'url':'".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'getdata'=>true)) ."',
										'data':{
											'plantid':$('#certoa-plantid').combogrid('getValue'),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#certoa-companyname').textbox('setValue',data.companyname);
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
				<td>".GetCatalog('company')."</td>
				<td><input class='easyui-textbox' id='certoa-companyname' name='certoa-companyname' style='width:280px' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('productiondate')."</td>
				<td><input class='easyui-datebox' id='certoa-productiondate' name='certoa-productiondate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('productcode')."</td>
				<td><select class='easyui-combogrid' id='certoa-productid' name='certoa-productid' style='width:150px' data-options=\"
								panelWidth: '700px',
								idField: 'productid',
								required: true,
								textField: 'productcode',
								mode: 'remote',
								url: '".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plant'=>true)) ."',
								onShowPanel:function() {
									$('#certoa-productid').combogrid('grid').datagrid('reload');
								},
								onBeforeLoad: function(param) {
									param.plantid = $('#certoa-plantid').combogrid('getValue');
								},
								method: 'get',
								onHidePanel: function() {
									jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/index',array('grid'=>true,'getdata'=>true)) ."',
										'data':{
											'productid':$('#certoa-productid').combogrid('getValue'),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#certoa-productname').textbox('setValue',data.productname);
										},
										'cache':false});
								},
								columns: [[
										{field:'productid',title:'".getCatalog('productid') ."',width:'50px'},
										{field:'productcode',title:'".getCatalog('productcode') ."',width:'120px'},
										{field:'productname',title:'".getCatalog('productname') ."',width:'450px'},
								]],
								fitColumns: true
						\">
				</select></td>
				<td>".GetCatalog('productname')."</td>
				<td><input class='easyui-textbox' id='certoa-productname' name='certoa-productname' style='width:280px' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('soheader')."</td>
				<td><select class='easyui-combogrid' id='certoa-soheaderid' name='certoa-soheaderid' style='width:200px' data-options=\"
								panelWidth: '500px',
								idField: 'soheaderid',
								required: true,
								textField: 'sono',
								mode : 'remote',
								url:'".Yii::app()->createUrl('order/soheader/index',array('grid'=>true,'socoa'=>true)) ."',
								method: 'get',
								onShowPanel:function() {
									$('#certoa-soheaderid').combogrid('grid').datagrid('reload');
								},
								onBeforeLoad: function(param) {
									param.productid = $('#certoa-productid').combogrid('getValue');
								},
								onHidePanel: function(){
							jQuery.ajax({'url':'".Yii::app()->createUrl('order/soheader/index',array('grid'=>true,'getdatacoa'=>true)) ."',
								'data':{
									'soheaderid':$('#certoa-soheaderid').combogrid('getValue'),
									'productid':$('#certoa-productid').combogrid('getValue')
								},
								'type':'post',
								'dataType':'json',
								'success':function(data)
								{
									$('#certoa-addressbookid').combogrid('setValue',data.addressbookid);
									$('#certoa-bomid').combogrid('setValue',data.bomid);
									$('#certoa-jumkirim').numberbox('setValue',data.qty);
									
								},
								'cache':false});
						},
								columns: [[
										{field:'soheaderid',title:'".getCatalog('soheaderid') ."',width:'80px'},
										{field:'sono',title:'".getCatalog('sono') ."',width:'200px'},
										{field:'bomversion',title:'".getCatalog('bomversion') ."',width:'200px'},
								]],
								fitColumns: true
						\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('customer')."</td>
				<td><select class='easyui-combogrid' id='certoa-addressbookid' name='certoa-addressbookid' style='width:200px' data-options=\"
								panelWidth: '500px',
								idField: 'addressbookid',
								required: true,
								textField: 'fullname',
								mode : 'remote',
								url:'".Yii::app()->createUrl('common/customer/index',array('grid'=>true,'combo'=>true)) ."',
								method: 'get',
								columns: [[
										{field:'addressbookid',title:'".getCatalog('addressbookid') ."',width:'80px'},
										{field:'fullname',title:'".getCatalog('fullname') ."',width:'200px'},
								]],
								fitColumns: true
						\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('bom')."</td>
				<td><select class='easyui-combogrid' id='certoa-bomid' name='certoa-bomid' style='width:200px' data-options=\"
								panelWidth: '500px',
								idField: 'bomid',
								required: true,
								textField: 'bomversion',
								mode : 'remote',
								url:'".Yii::app()->createUrl('production/bom/index',array('grid'=>true,'combo'=>true)) ."',
								method: 'get',
								columns: [[
										{field:'bomid',title:'".getCatalog('bomid') ."',width:'80px'},
										{field:'bomversion',title:'".getCatalog('bomversion') ."',width:'200px'},
								]],
								fitColumns: true
						\">
				</select></td>
			</tr>
			<tr>
				<td>".GetCatalog('jumkirim')."</td>
				<td><input class='easyui-numberbox' id='certoa-jumkirim' name='certoa-jumkirim' data-options='required:true'></input></td>
			</tr>
			<tr>
			<td>".GetCatalog('status')."</td>
			<td><input class='easyui-checkbox' name='certoa-status' id='certoa-status'></input></td>
			</tr>
			<tr>
			<td>".getCatalog('description')."</td>
				<td><input class='easyui-textbox' id='certoa-description' name='certoa-description' data-options='multiline:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'loadsuccess'=>"
		$('#certoa-certoadate').datebox('setValue',data.certoadate);
		$('#certoa-productiondate').datebox('setValue',data.productiondate);
		$('#certoa-plantid').combogrid('setValue',data.plantid);
		$('#certoa-companyname').textbox('setValue',data.companyname);
		$('#certoa-productid').combogrid('setValue',data.productid);
		$('#certoa-productname').textbox('setValue',data.productname);
		$('#certoa-addressbookid').combogrid('setValue',data.addressbookid);
		$('#certoa-soheaderid').combogrid('setValue',data.soheaderid);
		$('#certoa-bomid').combogrid('setValue',data.bomid);
		$('#certoa-jumkirim').numberbox('setValue',data.jumkirim);
		$('#certoa-description').textbox('setValue',data.description);
		if (data.status == 1)
		{
			$('#certoa-status').prop('checked', true);
		} else {
			$('#certoa-status').prop('checked', false);
		}
	",
	'columndetails'=> array (
		array(
			'id'=>'certoadetail',
			'idfield'=>'certoadetailid',
			'urlsub'=>Yii::app()->createUrl('production/certoa/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('production/certoa/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('production/certoa/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('production/certoa/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('production/certoa/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'qcparamname',title:'".getCatalog('qcparamname') ."',width:'150px'},
				{field:'methodtest',title:'".getCatalog('methodtest') ."',width:'200px'},
				{field:'unittest',title:'".getCatalog('unittest') ."',align:'right',width:'80px'},
				{field:'specmin',title:'".getCatalog('specmin') ."',align:'right',width:'80px'},
				{field:'rangetarget',title:'".getCatalog('rangetarget') ."',align:'right',width:'80px'},
				{field:'tolerancemin',title:'".getCatalog('tolerancemin') ."',align:'right',width:'80px'},
			",
			'columns'=>"
				{
					field:'certoaid',
					title:'".getCatalog('certoaid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'certoadetailid',
					title:'".getCatalog('certoadetailid') ."',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'qcparamid',
					title:'".getCatalog('qcparam') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'550px',
							mode: 'remote',
							method:'get',
							idField:'qcparamid',
							textField:'qcparamname',
							url:'".Yii::app()->createUrl('production/qcparam/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'qcparamid',title:'".getCatalog('qcparamid')."',width:'50px'},
								{field:'qcparamname',title:'".getCatalog('qcparamname')."',width:'250px'},
							]]
						}	
					},
					width:'200px',
					sortable: true,
					formatter: function(value,row,index){
										return row.qcparamname;
					}
				},
				{
					field:'methodtest',
					title:'".getCatalog('methodtest') ."',
					editor: {
						type: 'textbox',
						options: {
							required: true
					}},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'unittest',
					title:'".getCatalog('unittest') ."',
					editor: {
						type: 'textbox',
						options: {
							required: true
					}},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'specmin',
					title:'".getCatalog('specmin') ."',
					editor: {
						type: 'textbox',
						options: {
							required: true
					}},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'rangetarget',
					title:'".getCatalog('rangetarget') ."',
					editor: {
						type: 'textbox',
						options: {
							required: true
					}},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'tolerancemin',
					title:'".getCatalog('tolerancemin') ."',
					editor: {
						type: 'textbox',
						options: {
							required: true
					}},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'resulttest',
					title:'".getCatalog('resulttest') ."',
					editor: {
						type: 'textbox',
						options: {
							required: true
					}},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
			"
		),
	),	
));