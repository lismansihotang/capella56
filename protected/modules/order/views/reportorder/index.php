<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'formtype'=>'report',
	'isxls'=>1,
	'downpdf'=>Yii::app()->createUrl('order/reportorder/downpdf'),
	'downxls'=>Yii::app()->createUrl('order/reportorder/downxls'),
	'headerform'=>"
		<table>
			<tr>
				<td>".getCatalog('reporttype')."</td>
				<td><select class='easyui-combobox' id='reportorder-list' name='reportorder-list' data-options='required:true' style='width:350px;'>
					<option value='1'>Rincian Order & Shipment Tanpa Value</option>
					<option value='2'>Top Down Sales (Basis Tgl SJ)</option>
					<option value='3'>Order Obtain (Basis Tgl OS)</option>
					<option value='4'>Rekap SO Per Dokumen Belum Status Max</option>
					<option value='5'>Top Down Sales (Basis Tgl SJ) - TAC</option>
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('company')."</td>
				<td><select class='easyui-combogrid' id='reportorder-companyid' name='reportorder-companyid' style='width:350px' 
					data-options=\"
						panelWidth: 500,
						required: true,
						idField: 'companyid',
						textField: 'companyname',
						mode:'remote',
						url: '".Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true))."',
						method: 'get',
						required: true,
						onHidePanel: function(){
							$('#reportorder-plantid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'authcomp'=>true))."',
								queryParams: {
									companyid: $('#reportorder-companyid').combogrid('getValue')
								}
							});
							$('#reportorder-employeeid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('order/sales/index',array('grid'=>true,'combo'=>true)) ."',
							});  
							$('#reportorder-addressbookid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/addressbook/index',array('grid'=>true,'combo'=>true))."',
							}); 
							$('#reportorder-productid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/product/index',array('grid'=>true,'combo'=>true)) ."',
							});  
							$('#reportorder-salesareaid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/salesarea/index',array('grid'=>true,'combo'=>true)) ."',
							});                               
						},
						columns: [[
								{field:'companyid',title:'".getCatalog('companyid')."',width:'80px'},
								{field:'companycode',title:'".getCatalog('companycode')."',width:'120px'},
								{field:'companyname',title:'".getCatalog('companyname')."',width:'200px'},
						]],
						fitColumns: true
					\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".getCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='reportorder-plantid' name='reportorder-plantid' style='width:350px' data-options=\"
						panelWidth: 500,
						idField: 'plantid',
						textField: 'plantcode',
						mode:'remote',
						method: 'get',
						required: true,
						onHidePanel: function(){
							$('#reportorder-slocid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/sloc/indextrxplantsloc',array('grid'=>true))."',
								queryParams: {
									plantid: $('#reportorder-plantid').combogrid('getValue')
								}
							});                               
						},
						columns: [[
								{field:'plantid',title:'".getCatalog('plantid')."',width:'80px'},
								{field:'plantcode',title:'".getCatalog('plantcode')."',width:'80px'},
								{field:'description',title:'".getCatalog('description')."',width:'200px'},
						]],
						fitColumns: true
					\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('sloc')."</td>
				<td><select class='easyui-combogrid' id='reportorder-slocid' name='reportorder-slocid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'slocid',
						textField: 'sloccode',
						mode:'remote',
						method: 'get',
						onHidePanel: function(){
							$('#reportorder-storagebinid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/storagebin/index',array('grid'=>true,'single'=>true))."',
								queryParams: {
									slocid: $('#reportorder-slocid').combogrid('getValue')
								}
							});                               
						},
						columns: [[
							{field:'slocid',title:'".getCatalog('slocid')."',width:'80px'},
							{field:'sloccode',title:'".getCatalog('sloccode')."',width:'80px'},
							{field:'description',title:'".getCatalog('description')."',width:'200px'},
						]],
						fitColumns: true
						\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('storagebin')."</td>
				<td><select class='easyui-combogrid' id='reportorder-storagebinid' name='reportorder-storagebinid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'storagebinid',
						textField: 'description',
						mode:'remote',
						method: 'get',
						columns: [[
								{field:'storagebinid',title:'".getCatalog('storagebinid')."',width:'80px'},
								{field:'description',title:'".getCatalog('description')."',width:'200px'},
						]],
						fitColumns: true
					\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('sales')."</td>
				<td><select class='easyui-combogrid' id='reportorder-employeeid' name='reportorder-employeeid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'salesid',
						textField: 'fullname',
						mode:'remote',
						method: 'get',
						columns: [[
								{field:'salesid',title:'".getCatalog('salesid') ."',width:'80px'},
								{field:'fullname',title:'".getCatalog('fullname') ."',width:'200px'},
						]],
						fitColumns: true
					\">
				</select></td>
			</tr>
			<tr>
				<td>".GetCatalog('customer')."</td>
				<td><select class='easyui-combogrid' id='reportorder-addressbookid' name='reportorder-addressbookid' style='width:350px' data-options=\"
								panelWidth: 500,
								idField: 'fullname',
								textField: 'fullname',
								mode:'remote',								
								method: 'get',
								columns: [[
										{field:'addressbookid',title:'".GetCatalog('addressbookid')."',width:'80px'},
										{field:'fullname',title:'".GetCatalog('fullname')."',width:'300px'},
								]],
								fitColumns: true
						\">
				</select><br/></td>
			</tr>
			<tr>
				<td>".getCatalog('product')."</td>
				<td><select class='easyui-combogrid' id='reportorder-productid' name='reportorder-productid' style='width:350px' data-options=\"
						panelWidth: '600px',
						idField: 'productid',
						textField: 'productname',
						mode:'remote',						
						method: 'get',
						columns: [[
							{field:'productid',title:'".getCatalog('productid') ."',width:'80px'},
							{field:'productname',title:'".getCatalog('productname') ."',width:'500px'},
						]],
						fitColumns: true
				\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('salesarea')."</td>
				<td><select class='easyui-combogrid' id='reportorder-salesareaid' name='reportorder-salesareaid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'salesareaid',
						textField: 'areaname',
						mode:'remote',						
						method: 'get',
						columns: [[
								{field:'salesareaid',title:'".getCatalog('salesareaid') ."',width:'80px'},
								{field:'areaname',title:'".getCatalog('areaname') ."',width:'150px'},
						]],
						fitColumns: true
				\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('date')."</td>
				<td><input class='easyui-datebox' id='reportorder-startdate' name='reportorder-startdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input>
				-
				<input class='easyui-datebox' id='reportorder-enddate' name='reportorder-enddate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input>
			</tr>
    </table>
	",
	'downscript'=>"lro='+$('#reportorder-list').combobox('getValue') +
		'&companyid='+$('#reportorder-companyid').combogrid('getValue')+
		'&plantid='+$('#reportorder-plantid').combogrid('getValue')+
		'&sloc='+$('#reportorder-slocid').combogrid('getValue')+
		'&storagebin='+$('#reportorder-storagebinid').combogrid('getValue')+
		'&sales='+$('#reportorder-employeeid').combogrid('getValue')+
		'&customer='+$('#reportorder-addressbookid').combogrid('getValue')+
		'&product='+$('#reportorder-productid').combogrid('getValue')+
		'&salesarea='+$('#reportorder-salesareaid').combogrid('getValue')+
		'&startdate='+$('#reportorder-startdate').datebox('getValue')+
		'&enddate='+$('#reportorder-enddate').datebox('getValue')+
		'&per=1'
	",
));
?>