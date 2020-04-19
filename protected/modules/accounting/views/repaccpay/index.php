<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'formtype'=>'report',
	'isxls'=>1,
	'downpdf'=>Yii::app()->createUrl('accounting/repaccpay/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/repaccpay/downxls'),
	'headerform'=>"
		<table>
			<tr>
				<td>".getCatalog('reporttype')."</td>
				<td><select class='easyui-combobox' id='repaccpay-list' name='repaccpay-list' data-options='required:true' style='width:350px;'>
					<option value='13'>Laporan AP Aging</option>
					<option value='14'>Buku Pembelian Basis PO</option>
					<option value='15'>Buku Pembelian Basis Faktur Pembelian</option>
					<option value='1'>Rincian Biaya Ekspedisi Per Dokumen</option>
					<option value='2'>Rekap Biaya Ekspedisi Per Dokumen</option>      
					<option value='4'>Rincian Pembayaran Hutang per Dokumen</option>
					<option value='5'>Kartu Hutang</option>
					<option value='6'>Rekap Hutang per Supplier</option>
					<option value='7'>Rincian Pembelian & Retur Beli Belum Lunas</option>
					<option value='8'>Rincian Umur Hutang per LPB</option>       
					<option value='9'>Rekap Umur Hutang per Supplier</option>
					<option value='10'>Rekap Invoice AP Per Dokumen Belum Status Max</option>
					<option value='11'>Rekap Permohonan Pembayaran Per Dokumen Belum Status Max</option>
					<option value='12'>Rekap Nota Retur Pembelian Per Dokumen Belum Status Max</option> 
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('company')."</td>
				<td><select class='easyui-combogrid' id='repaccpay-companyid' name='repaccpay-companyid' style='width:350px' 
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
							$('#repaccpay-plantid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'authcomp'=>true))."',
								queryParams: {
									companyid: $('#repaccpay-companyid').combogrid('getValue')
								}
							});
							$('#repaccpay-addressbookid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/supplier/index',array('grid'=>true,'combo'=>true))."',
							});  
							$('#repaccpay-invoiceapid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('accounting/invoiceap/index',array('grid'=>true,'combo'=>true))."',
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
				<td><select class='easyui-combogrid' id='repaccpay-plantid' name='repaccpay-plantid' style='width:350px' data-options=\"
						panelWidth: 500,
						idField: 'plantid',
						textField: 'plantcode',
						mode:'remote',
						method: 'get',
						onHidePanel: function(){
							$('#repaccpay-slocid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/sloc/indextrxplantsloc',array('grid'=>true))."',
								queryParams: {
									plantid: $('#repaccpay-plantid').combogrid('getValue')
								}
							});  
							$('#repaccpay-productid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plant'=>true)) ."',
								queryParams: {
									plantid: $('#repaccpay-plantid').combogrid('getValue')
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
				<td><select class='easyui-combogrid' id='repaccpay-slocid' name='repaccpay-slocid' style='width:350px' data-options=\"
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
				<td>".getCatalog('product')."</td>
				<td><select class='easyui-combogrid' id='repaccpay-productid' name='repaccpay-productid' style='width:350px' data-options=\"
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
				<td>".GetCatalog('invoiceap')."</td>
				<td><select class='easyui-combogrid' id='repaccpay-invoiceapid' name='repaccpay-invoiceapid' style='width:350px' data-options=\"
								panelWidth: '500px',
								idField: 'invoiceno',
								textField: 'invoiceno',
								mode:'remote',
								method: 'get',
								columns: [[
										{field:'invoiceapid',title:'".GetCatalog('invoiceapid')."',width:'80px'},
										{field:'invoiceno',title:'".GetCatalog('invoiceno')."',width:'150px'},
								]],
								fitColumns: true
						\">
				</select><br/></td>
			</tr>
			<tr>
				<td>".GetCatalog('supplier')."</td>
				<td><select class='easyui-combogrid' id='repaccpay-addressbookid' name='repaccpay-addressbookid' style='width:350px' data-options=\"
								panelWidth: '600px',
								idField: 'fullname',
								textField: 'fullname',
								mode:'remote',
								method: 'get',
								columns: [[
										{field:'addressbookid',title:'".GetCatalog('addressbookid')."',width:'80px'},
										{field:'fullname',title:'".GetCatalog('fullname')."',width:'500px'},
								]],
								fitColumns: true
						\">
				</select><br/></td>
			</tr>
			<tr>
				<td>".getCatalog('date')."</td>
				<td><input class='easyui-datebox' id='repaccpay-startdate' name='repaccpay-startdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input>
				-
				<input class='easyui-datebox' id='repaccpay-enddate' name='repaccpay-enddate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input>
			</tr>
    </table>
	",
	'downscript'=>"lro='+$('#repaccpay-list').combobox('getValue') +
		'&company='+$('#repaccpay-companyid').combogrid('getValue')+
		'&plant='+$('#repaccpay-plantid').combogrid('getValue')+
		'&sloc='+$('#repaccpay-slocid').combogrid('getValue')+
		'&product='+$('#repaccpay-productid').combogrid('getValue')+
		'&invoice='+$('#repaccpay-invoiceapid').combogrid('getValue')+
		'&supplier='+$('#repaccpay-addressbookid').combogrid('getValue')+
		'&startdate='+$('#repaccpay-startdate').datebox('getValue')+
		'&enddate='+$('#repaccpay-enddate').datebox('getValue')+
		'&per=1'
	",
));
?>