<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'qctestid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('production/qctest/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('production/qctest/getData'),
	'saveurl'=>Yii::app()->createUrl('production/qctest/save'),
	'destroyurl'=>Yii::app()->createUrl('production/qctest/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('production/qctest/upload'),
	'downpdf'=>Yii::app()->createUrl('production/qctest/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/qctest/downxls'),
	'columns'=>"
		{
			field:'qctestid',
			title:'".getCatalog('qctestid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
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
			field:'addressbookid',
			title:'".getCatalog('customer') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return row.fullname;
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
			width:'450px',
			formatter: function(value,row,index){
				return value;
		}}",
	'searchfield'=> array ('qctestid','plantcode','customer','materialtypecode','productname'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td>".getCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='qctest-plantid' name='qctest-plantid' style='width:150px' data-options=\"
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
											'plantid':$('#qctest-plantid').combogrid('getValue'),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#qctest-companyname').textbox('setValue',data.companyname);
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
				<td><input class='easyui-textbox' id='qctest-companyname' name='qctest-companyname' style='width:280px' data-options='disabled:true'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('productname')."</td>
				<td><select class='easyui-combogrid' id='qctest-productid' name='qctest-productid' style='width:300px' data-options=\"
								panelWidth: '700px',
								idField: 'productid',
								required: true,
								textField: 'productname',
								mode: 'remote',
								url: '".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plant'=>true)) ."',
								onShowPanel: function() {
											$('#qctest-productid').combogrid('grid').datagrid('reload');
								},	
								onBeforeLoad: function(param) {
									param.plantid = $('#qctest-plantid').combogrid('getValue');
								},
								method: 'get',
								onHidePanel: function() {
									jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/index',array('grid'=>true,'getdata'=>true)) ."',
										'data':{
											'productid':$('#qctest-productid').combogrid('getValue'),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#qctest-productname').textbox('setValue',data.productname);
										},
										'cache':false});
								},
								columns: [[
										{field:'productid',title:'".getCatalog('productid') ."',width:'50px'},
										{field:'materialtypecode',title:'".getCatalog('materialtypecode') ."',width:'100px'},
										{field:'productname',title:'".getCatalog('productname') ."',width:'350px'},
								]],
								fitColumns: true
						\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('customer')."</td>
				<td><select class='easyui-combogrid' id='qctest-addressbookid' name='qctest-addressbookid' style='width:300px' data-options=\"
								panelWidth: '500px',
								idField: 'addressbookid',
								required: true,
								textField: 'fullname',
								mode : 'remote',
								url:'".Yii::app()->createUrl('common/customer/index',array('grid'=>true,'combo'=>true)) ."',
								method: 'get',
								columns: [[
										{field:'addressbookid',title:'".getCatalog('addressbookid') ."',width:'80px'},
										{field:'fullname',title:'".getCatalog('fullname') ."',width:'400px'},
								]],
								fitColumns: true
						\">
				</select></td>
			</tr>
		</table>
	",
	'columndetails'=> array (
		array(
			'id'=>'qcdetail',
			'idfield'=>'qctestdetailid',
			'urlsub'=>Yii::app()->createUrl('production/qctest/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('production/qctest/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('production/qctest/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('production/qctest/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('production/qctest/purgedetail',array('grid'=>true)),
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
					field:'qctestid',
					title:'".getCatalog('qctestid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'qctestdetailid',
					title:'".getCatalog('qctestdetailid') ."',
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
							textField:'productcode',
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
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
			"
		),
	),	
));