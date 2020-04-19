<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'formtype'=>'report',
	'isxls'=>1,
	'downpdf'=>Yii::app()->createUrl('production/repprod/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/repprod/downxls'),
	'headerform'=>"
		<table>
			<tr>
				<td>".getCatalog('reporttype')."</td>
				<td><select class='easyui-combobox' id='repprod-list' name='repprod-list' data-options='required:true' style='width:350px;'>
					<option value='20'>Jadwal Produksi (Mesin)</option>
					<option value='25'>Rekap OK</option>
					<option value='8'>Pendingan Produksi</option>
					<option value='5'>Perbandingan Planning vs Output</option>				
					<option value='22'>Rekonsiliasi OK, OS, dan Hasil Produksi</option>
					<option value='23'>Laporan Waste</option>
					<option value='24'>Laporan Alokasi Barang</option>
					<option value='15'>OK yang belum status Max</option>
					<option value='21'>Hasil Produksi yang belum status Max</option>
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('company')."</td>
				<td><select class='easyui-combogrid' id='repprod-companyid' name='repprod-companyid' style='width:350px' 
					data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'companyid',
						textField: 'companyname',
						mode:'remote',
						url: '".Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true))."',
						method: 'get',
						required: true,
						onHidePanel: function(){
							$('#repprod-plantid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'authcomp'=>true))."',
								queryParams: {
									companyid: $('#repprod-companyid').combogrid('getValue')
								}
							}); 
							$('#repprod-employeeid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('order/sales/index',array('grid'=>true,'combo'=>true)) ."',
							});  
							$('#repprod-addressbookid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/addressbook/index',array('grid'=>true,'combo'=>true))."',
							});  
							$('#repprod-salesareaid').combogrid('grid').datagrid({
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
				<td><select class='easyui-combogrid' id='repprod-plantid' name='repprod-plantid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'plantid',
						textField: 'plantcode',
						mode:'remote',
						method: 'get',
						required: true,
						onHidePanel: function(){
							$('#repprod-slocid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/sloc/indextrxplantsloc',array('grid'=>true))."',
								queryParams: {
									plantid: $('#repprod-plantid').combogrid('getValue')
								}
							});                               
							$('#repprod-productid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plant'=>true)) ."',
								queryParams: {
									plantid: $('#repprod-plantid').combogrid('getValue')
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
				<td>".getCatalog('sloc')."</td>
				<td><select class='easyui-combogrid' id='repprod-slocid' name='repprod-slocid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'sloccode',
						textField: 'sloccode',
						mode:'remote',
						method: 'get',
						onHidePanel: function(){
							$('#repprod-storagebinid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/storagebin/index',array('grid'=>true,'single'=>true))."',
								queryParams: {
									slocid: $('#repprod-slocid').combogrid('getValue')
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
				<td><select class='easyui-combogrid' id='repprod-storagebinid' name='repprod-storagebinid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'description',
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
				<td><select class='easyui-combogrid' id='repprod-employeeid' name='repprod-employeeid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'fullname',
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
				<td><select class='easyui-combogrid' id='repprod-addressbookid' name='repprod-addressbookid' style='width:350px' data-options=\"
						panelWidth: '500px',
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
				<td><select class='easyui-combogrid' id='repprod-productid' name='repprod-productid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'productname',
						textField: 'productname',
						mode:'remote',
						method: 'get',
						columns: [[
							{field:'productid',title:'".getCatalog('productid') ."',width:'80px'},
							{field:'productname',title:'".getCatalog('productname') ."',width:'400px'},
						]],
						fitColumns: true
				\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('salesarea')."</td>
				<td><select class='easyui-combogrid' id='repprod-salesareaid' name='repprod-salesareaid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'areaname',
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
				<td><input class='easyui-datebox' id='repprod-startdate' name='repprod-startdate' data-options=\"formatter:dateformatter,
				required:true,parser:dateparser\"></input>
				-
				<input class='easyui-datebox' id='repprod-enddate' name='repprod-enddate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input>
			</tr>
    </table>
	",
	'downscript'=>"lro='+$('#repprod-list').combobox('getValue') +
		'&companyid='+$('#repprod-companyid').combogrid('getValue')+
		'&plantid='+$('#repprod-plantid').combogrid('getValue')+
		'&sloc='+$('#repprod-slocid').combogrid('getValue')+
		'&storagebin='+$('#repprod-storagebinid').combogrid('getValue')+
		'&sales='+$('#repprod-employeeid').combogrid('getValue')+
		'&customer='+$('#repprod-addressbookid').combogrid('getValue')+
		'&product='+$('#repprod-productid').combogrid('getValue')+
		'&salesarea='+$('#repprod-salesareaid').combogrid('getValue')+
		'&startdate='+$('#repprod-startdate').datebox('getValue')+
		'&enddate='+$('#repprod-enddate').datebox('getValue')+
		'&per=1'
	",
));