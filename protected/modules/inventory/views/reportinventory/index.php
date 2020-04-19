<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'formtype'=>'report',
	'isxls'=>1,
	'downpdf'=>Yii::app()->createUrl('inventory/reportinventory/downpdf'),
	'downxls'=>Yii::app()->createUrl('inventory/reportinventory/downxls'),
	'headerform'=>"
		<table>
			<tr>
				<td>".getCatalog('reporttype')."</td>
				<td><select class='easyui-combobox' id='reportinventory-list' name='reportinventory-list' data-options='required:true' style='width:350px;'>
					<option value='1'>Rincian Histori Barang</option>
					<option value='2'>Rekap Histori Barang</option>
					<option value='3'>Kartu Stok Barang</option>
					<option value='4'>Rekap Stok Barang</option>
					<option value='5'>Rincian Surat Jalan Per Dokumen</option>
					<option value='6'>Daftar Surat Jalan</option>
					<option value='7'>Rincian Transfer Gudang Keluar Per Dokumen</option>
					<option value='8'>Rincian Transfer Gudang Masuk Per Dokumen</option>
					<option value='9'>Daftar Surat Penyerahan Hasil Jadi</option>
					<option value='10'>Daftar Surat Penyerahan Bahan Baku</option>
					<option value='14'>Rincian LPB Per Dokumen</option>
					<option value='15'>Daftar Laporan Penerimaan Barang</option>
					<option value='40'>Rekonsiliasi FPP, PO, dan LPB</option>
					<option value='27'>Rekap LPB Per Dokumen Belum Status Max</option>
					<option value='28'>Rekap Retur Beli Per Dokumen Belum Status Max</option>
					<option value='29'>Rekap Surat Jalan Per Dokumen Belum Status Max</option>
					<option value='30'>Rekap Retur Penjualan Per Dokumen Belum Status Max</option>
					<option value='31'>Rekap Transfer Per Dokumen Belum Status Max</option>
					<option value='32'>Rekap Stock Opname Per Dokumen Belum Status Max</option>
					<option value='38'>Rekap FPB Belum Status Max</option>
					<option value='25'>Rekap FPP Belum Status Max</option>
			</tr>
			<tr>
				<td>".getCatalog('company')."</td>
				<td><select class='easyui-combogrid' id='reportinventory-companyid' name='reportinventory-companyid' style='width:350px' 
					data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'companyid',
						textField: 'companyname',
						mode:'remote',
						url: '".Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true))."',
						method: 'get',
						required: true,
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
				<td><select class='easyui-combogrid' id='reportinventory-plantid' name='reportinventory-plantid' style='width:350px' data-options=\"
						panelWidth: '600px',
						idField: 'plantid',
						textField: 'plantcode',
						mode:'remote',						
						method: 'get',
						required: true,
						url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'authcomp'=>true))."',
						onBeforeLoad:function(param) {
							param.companyid= $('#reportinventory-companyid').combogrid('getValue')
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
				<td><select class='easyui-combogrid' id='reportinventory-slocid' name='reportinventory-slocid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'sloccode',
						textField: 'sloccode',
						mode:'remote',
						method: 'get',
						url: '".Yii::app()->createUrl('common/sloc/indextrxplantsloc',array('grid'=>true))."',
						onBeforeLoad:function(param) {
							param.plantid= $('#reportinventory-plantid').combogrid('getValue')
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
				<td><select class='easyui-combogrid' id='reportinventory-storagebinid' name='reportinventory-storagebinid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'description',
						textField: 'description',
						mode:'remote',
						method: 'get',
						url: '".Yii::app()->createUrl('common/storagebin/index',array('grid'=>true,'single'=>true))."',
						onBeforeLoad:function(param) {
							param.slocid= $('#reportinventory-slocid').combogrid('getValue')
						},
						columns: [[
								{field:'storagebinid',title:'".getCatalog('storagebinid')."',width:'80px'},
								{field:'description',title:'".getCatalog('description')."',width:'200px'},
						]],
						fitColumns: true
					\">
				</select></td>
			</tr>
			<tr>
				<td>".GetCatalog('addressbook')."</td>
				<td><select class='easyui-combogrid' id='reportinventory-addressbookid' name='reportinventory-addressbookid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'fullname',
						textField: 'fullname',
						mode:'remote',
						method: 'get',
						url: '".Yii::app()->createUrl('common/addressbook/index',array('grid'=>true,'combo'=>true))."',
						columns: [[
								{field:'addressbookid',title:'".GetCatalog('addressbookid')."',width:'80px'},
								{field:'fullname',title:'".GetCatalog('fullname')."',width:'300px'},
						]],
						fitColumns: true
				\">
				</select><br/></td>
			</tr>
			<tr>
				<td>".getCatalog('sales')."</td>
				<td><select class='easyui-combogrid' id='reportinventory-employeeid' name='reportinventory-employeeid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'fullname',
						textField: 'fullname',
						mode:'remote',
						method: 'get',
						url: '".Yii::app()->createUrl('order/sales/index',array('grid'=>true,'combo'=>true)) ."',
						columns: [[
							{field:'salesid',title:'".getCatalog('salesid') ."',width:'80px'},
							{field:'fullname',title:'".getCatalog('fullname') ."',width:'200px'},
						]],
						fitColumns: true
					\">
				</select></td>
			</tr>
			<tr>
				<td>".GetCatalog('pono')."</td>
				<td><input class='easyui-textbox' id='reportinventory-pono' name='reportinventory-pono' style='width:350px' data-options=''></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('prno')."</td>
				<td><input class='easyui-textbox' id='reportinventory-prno' name='reportinventory-prno' style='width:350px' data-options=''></input></td>
			</tr>
			<tr>
				<td>".getCatalog('product')."</td>
				<td><select class='easyui-combogrid' id='reportinventory-productid' name='reportinventory-productid' style='width:350px' data-options=\"
						panelWidth: '630px',
						idField: 'productname',
						textField: 'productname',
						mode:'remote',
						method: 'get',
						url: '".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plant'=>true)) ."',
						onBeforeLoad:function(param) {
							param.plantid = $('#reportinventory-plantid').combogrid('getValue')
						},
						columns: [[
							{field:'productid',title:'".getCatalog('productid') ."',width:'80px'},
							{field:'productcode',title:'".getCatalog('productcode') ."',width:'150px'},
							{field:'productname',title:'".getCatalog('productname') ."',width:'400px'},
						]],
						fitColumns: true
				\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('salesarea')."</td>
				<td><select class='easyui-combogrid' id='reportinventory-salesareaid' name='reportinventory-salesareaid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'areaname',
						textField: 'areaname',
						mode:'remote',
						method: 'get',
						url: '".Yii::app()->createUrl('common/salesarea/index',array('grid'=>true,'combo'=>true)) ."',
						columns: [[
								{field:'salesareaid',title:'".getCatalog('salesareaid') ."',width:'80px'},
								{field:'areaname',title:'".getCatalog('areaname') ."',width:'300px'},
						]],
						fitColumns: true
				\">
				</select></td>
			</tr>
			<tr>
				<td> ".getCatalog('qtykeluar')."</td>
				<td><input class='easyui-numberbox' id='reportinventory-keluar3' name='reportinventory-keluar3' style='width:350px' data-options=''></input></td>
			</tr>
			<tr>
				<td>".getCatalog('date')."</td>
				<td><input class='easyui-datebox' id='reportinventory-startdate' name='reportinventory-startdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input>
				-
				<input class='easyui-datebox' id='reportinventory-enddate' name='reportinventory-enddate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input>
			</tr>
    </table>
	",
	'downscript'=>"lro='+$('#reportinventory-list').combobox('getValue') +
		'&companyid='+$('#reportinventory-companyid').combogrid('getValue')+
		'&plantid='+$('#reportinventory-plantid').combogrid('getValue')+
		'&sloc='+$('#reportinventory-slocid').combogrid('getValue')+
		'&storagebin='+$('#reportinventory-storagebinid').combogrid('getValue')+
		'&sales='+$('#reportinventory-employeeid').combogrid('getValue')+
		'&product='+$('#reportinventory-productid').combogrid('getValue')+
		'&prno='+$('#reportinventory-prno').textbox('getValue')+
		'&pono='+$('#reportinventory-pono').textbox('getValue')+
		'&salesarea='+$('#reportinventory-salesareaid').combogrid('getValue')+
		'&addressbook='+$('#reportinventory-addressbookid').combogrid('getValue')+
		'&startdate='+$('#reportinventory-startdate').datebox('getValue')+
		'&enddate='+$('#reportinventory-enddate').datebox('getValue')+
		'&keluar3='+$('#reportinventory-keluar3').val()+
		'&per=1'
	",
));