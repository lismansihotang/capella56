<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'formtype'=>'report',
	'isxls'=>1,
	'downpdf'=>Yii::app()->createUrl('accounting/reportacc/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/reportacc/downxls'),
	'headerform'=>"
		<table>
			<tr>
				<td>".getCatalog('reporttype')."</td>
				<td><select class='easyui-combobox' id='reportacc-list' name='reportacc-list' data-options='required:true' style='width:350px;'>
					<option value='5'>Laporan Aktiva (Asset)</option>
					<option value='1'>Rincian Jurnal Transaksi</option>
					<option value='2'>Buku Besar</option>
					<option value='3'>Neraca - Uji Coba</option>
					<option value='4'>Laba (Rugi) - Uji Coba</option>
					<option value='18'>Lampiran Neraca 1</option>
					<option value='21'>Rekap Cash Bank Harian </option>
					<option value='22'>Laporan Piutang Karyawan </option>
					<option value='14'>Rekap Jurnal Umum Per Dokumen Belum Status Max</option>
					<option value='15'>Rekap Penerimaan Kas/Bank Per Dokumen Belum Status Max</option>
					<option value='16'>Rekap Pengeluaran Kas/Bank Per Dokumen Belum Status Max</option>
					<option value='17'>Rekap Cash Bank Per Dokumen Belum Status Max</option>
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('company')."</td>
				<td><select class='easyui-combogrid' id='reportacc-companyid' name='reportacc-companyid' style='width:350px' 
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
							$('#reportacc-plantid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'authcomp'=>true))."',
								queryParams: {
									companyid: $('#reportacc-companyid').combogrid('getValue')
								}
							});
							$('#reportacc-materialgroupid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/materialgroup/index',array('grid'=>true,'combo'=>true)) ."'
							}); 
						$('#reportacc-addressbookid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/customer/index',array('grid'=>true,'combo'=>true)) ."'
							}); 
							$('#reportacc-addressbookid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/supplier/index',array('grid'=>true,'combo'=>true)) ."'
							}); 
							$('#reportacc-employeeid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('order/sales/index',array('grid'=>true,'combo'=>true)) ."',
							}); 
							$('#reportacc-startacccode').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('accounting/account/index',array('grid'=>true,'combo'=>true)) ."',
								queryParams: {
									companyid: $('#reportacc-companyid').combogrid('getValue')
								}
							});   
							$('#reportacc-endacccode').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('accounting/account/index',array('grid'=>true,'combo'=>true)) ."',
								queryParams: {
									companyid: $('#reportacc-companyid').combogrid('getValue')
								}
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
				<td><select class='easyui-combogrid' id='reportacc-plantid' name='reportacc-plantid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'plantid',
						textField: 'plantcode',
						mode:'remote',
						method: 'get',
						onHidePanel: function(){
							$('#reportacc-slocid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/sloc/indextrxplantsloc',array('grid'=>true))."',
								queryParams: {
									plantid: $('#reportacc-plantid').combogrid('getValue')
								}
							});
							$('#reportacc-productid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plant'=>true)) ."',
								queryParams: {
									plantid: $('#reportacc-plantid').combogrid('getValue')
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
				<td><select class='easyui-combogrid' id='reportacc-slocid' name='reportacc-slocid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'sloccode',
						textField: 'sloccode',
						mode:'remote',
						method: 'get',
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
				<td>".getCatalog('materialgroup')."</td>
				<td><select class='easyui-combogrid' id='reportacc-materialgroupid' name='reportacc-materialgroupid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'description',
						textField: 'description',
						mode:'remote',
						method: 'get',
						columns: [[
								{field:'materialgroupid',title:'".getCatalog('materialgroupid') ."',width:'80px'},
								{field:'materialgroupcode',title:'".getCatalog('materialgroupcode') ."',width:'150px'},
								{field:'description',title:'".getCatalog('description') ."',width:'200px'},
						]],
						fitColumns: true
					\">
				</select></td>
			</tr>
			<tr>
				<td>".GetCatalog('addressbook')."</td>
				<td><select class='easyui-combogrid' id='reportacc-addressbookid' name='reportorder-addressbookid' style='width:350px' data-options=\"
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
				<td>".getCatalog('sales')."</td>
				<td><select class='easyui-combogrid' id='reportacc-employeeid' name='reportacc-employeeid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'fullname',
						textField: 'fullname',
						mode:'remote',
						method: 'get',
						columns: [[
								{field:'salesid',title:'".getCatalog('salesid') ."',width:'80px'},
								{field:'fullname',title:'".getCatalog('fullname') ."',width:'300px'},
						]],
						fitColumns: true
					\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('product')."</td>
				<td><select class='easyui-combogrid' id='reportacc-productid' name='reportacc-productid' style='width:350px' data-options=\"
						panelWidth: '600px',
						idField: 'productname',
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
 <td>".GetCatalog('accountcode')."</td>
 <td><select class='easyui-combogrid' id='reportacc-account' name='reportacc-account' style='width:350px' data-options=\"
						panelWidth: '1000px',
						idField: 'accountname',
						textField: 'accountname',
						mode:'remote',
						url: '".Yii::app()->createUrl('accounting/account/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'accountid',title:'".getCatalog('accountid') ."',width:'80px'},
								{field:'accountcode',title:'".getCatalog('accountcode') ."',width:'150px'},
								{field:'accountname',title:'".getCatalog('accountname') ."',width:'550px'},
								{field:'companyname',title:'".getCatalog('companyname') ."',width:'150px'},
						]],
						fitColumns: true
						\"> 
				</select></td>
 </tr>			
			<tr>
				<td>".getCatalog('date')."</td>
				<td><input class='easyui-datebox' id='reportacc-startdate' name='reportacc-startdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input>
				-
				<input class='easyui-datebox' id='reportacc-enddate' name='reportacc-enddate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input>
			</tr>
    </table>
	",
	'downscript'=>"lro='+$('#reportacc-list').combobox('getValue') +
		'&company='+$('#reportacc-companyid').combogrid('getValue')+
		'&plant='+$('#reportacc-plantid').combogrid('getValue')+
		'&sloc='+$('#reportacc-slocid').combogrid('getValue')+
		'&materialgroup='+$('#reportacc-materialgroupid').combogrid('getValue')+
		'&customer='+$('#reportacc-addressbookid').combogrid('getValue')+
		'&employee='+$('#reportacc-employeeid').combogrid('getValue')+
		'&product='+$('#reportacc-productid').combogrid('getValue')+
		'&account='+$('#reportacc-account').combogrid('getValue')+
		'&startdate='+$('#reportacc-startdate').datebox('getValue')+
		'&enddate='+$('#reportacc-enddate').datebox('getValue')+
		'&per=1'
	",
));
?>