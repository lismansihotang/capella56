<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'formtype'=>'report',
	'isxls'=>1,
	'downpdf'=>Yii::app()->createUrl('purchasing/reportpurchasing/downpdf'),
	'downxls'=>Yii::app()->createUrl('purchasing/reportpurchasing/downxls'),
	'headerform'=>"
		<table>
			<tr>
				<td>".getCatalog('reporttype')."</td>
				<td><select class='easyui-combobox' id='reportpurchasing-list' name='reportpurchasing-list' data-options='required:true' style='width:350px;'>
        <option value='1'>Rincian PO Per Dokumen</option>
        <option value='17'>Pendingan PO Per Dokumen</option>
        <option value='22'>Laporan Pembelian Per Supplier Per Bulan Per Tahun</option>
        <option value='23'>Komparasi PO</option>
        <option value='20'>Dokumen PO yang belum Max</option>
    </select></td>
			</tr>
			<tr>
				<td>".getCatalog('company')."</td>
				<td><select class='easyui-combogrid' id='reportpurchasing-companyid' name='reportpurchasing-companyid' style='width:350px' 
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
							$('#reportpurchasing-plantid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'authcomp'=>true))."',
								queryParams: {
									companyid: $('#reportpurchasing-companyid').combogrid('getValue')
								}
							});  
							$('#reportpurchasing-addressbookid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/supplier/index',array('grid'=>true,'combo'=>true))."',
								queryParams: {
									companyid: $('#reportpurchasing-companyid').combogrid('getValue')
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
				<td><select class='easyui-combogrid' id='reportpurchasing-plantid' name='reportpurchasing-plantid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'plantid',
						textField: 'plantcode',
						mode:'remote',
						method: 'get',
						onHidePanel: function(){
							$('#reportpurchasing-slocid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/sloc/indextrxplantsloc',array('grid'=>true))."',
								queryParams: {
									plantid: $('#reportpurchasing-plantid').combogrid('getValue')
								}
							});    
							$('#reportpurchasing-productid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plant'=>true)) ."',
								queryParams: {
									plantid: $('#reportpurchasing-plantid').combogrid('getValue')
								}
							});                               
						},
						columns: [[
								{field:'plantid',title:'".getCatalog('plantid')."',width:'80px'},
								{field:'plantcode',title:'".getCatalog('plantcode')."',width:'200px'},
						]],
						fitColumns: true
					\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('sloc')."</td>
				<td><select class='easyui-combogrid' id='reportpurchasing-slocid' name='reportpurchasing-slocid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'sloccode',
						textField: 'sloccode',
						mode:'remote',						
						method: 'get',
						columns: [[
							{field:'slocid',title:'".getCatalog('slocid')."',width:'80px'},
							{field:'sloccode',title:'".getCatalog('sloccode')."',width:'120px'},
							{field:'description',title:'".getCatalog('description')."',width:'200px'},
						]],
						fitColumns: true
						\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('supplier')."</td>
				<td><select class='easyui-combogrid' id='reportpurchasing-addressbookid' name='reportpurchasing-addressbookid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'fullname',
						textField: 'fullname',
						mode:'remote',
						method: 'get',
						columns: [[
								{field:'addressbookid',title:'".getCatalog('addressbookid')."',width:'80px'},
								{field:'fullname',title:'".getCatalog('fullname')."',width:'400px'},
						]],
						fitColumns: true
					\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('product')."</td>
				<td><select class='easyui-combogrid' id='reportpurchasing-productid' name='reportpurchasing-productid' style='width:350px' data-options=\"
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
				<td>".getCatalog('date')."</td>
				<td><input class='easyui-datebox' id='reportpurchasing-startdate' name='reportpurchasing-startdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input>
				-
				<input class='easyui-datebox' id='reportpurchasing-enddate' name='reportpurchasing-enddate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input>
			</tr>
    </table>
	",
	'downscript'=>"lro='+$('#reportpurchasing-list').combobox('getValue') +
		'&companyid='+$('#reportpurchasing-companyid').combogrid('getValue')+
		'&plant='+$('#reportpurchasing-plantid').combogrid('getValue')+
		'&sloc='+$('#reportpurchasing-slocid').combogrid('getValue')+
		'&supplier='+$('#reportpurchasing-addressbookid').combogrid('getValue')+
		'&product='+$('#reportpurchasing-productid').combogrid('getValue')+
		'&startdate='+$('#reportpurchasing-startdate').datebox('getValue')+
		'&enddate='+$('#reportpurchasing-enddate').datebox('getValue')+
		'&plantid='+$('#reportpurchasing-plantid').combogrid('getValue')
	",
));