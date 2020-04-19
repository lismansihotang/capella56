<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'formtype'=>'report',
	'isxls'=>1,
	'downpdf'=>Yii::app()->createUrl('accounting/repaccpers/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/repaccpers/downxls'),
	'headerform'=>"
		<table>
			<tr>
				<td>".getCatalog('reporttype')."</td>
				<td><select class='easyui-combobox' id='repaccpers-list' name='repaccpers-list' data-options='required:true' style='width:350px;'>
				<option value='2'>Rekap Persediaan (Jenis Transaksi)</option>
				<option value='1'>Rekap Persediaan (Grup Transaksi)</option>
				<option value='19'>Kartu Stock Barang (Nilai)</option>
				<option value='20'>Rekap OK (Nilai) (Nilai)</option>
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('company')."</td>
				<td><select class='easyui-combogrid' id='repaccpers-companyid' name='repaccpers-companyid' style='width:350px' 
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
							$('#repaccpers-plantid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'authcomp'=>true))."',
								queryParams: {
									companyid: $('#repaccpers-companyid').combogrid('getValue')
								}
							});  
							$('#repaccpers-accountid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('accounting/account/index',array('grid'=>true,'trxcom'=>true)) ."',
								queryParams: {
									companyid: $('#repaccpers-companyid').combogrid('getValue')
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
				<td><select class='easyui-combogrid' id='repaccpers-plantid' name='repaccpers-plantid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'plantid',
						textField: 'plantcode',
						mode:'remote',
						method: 'get',
						onHidePanel: function(){
							$('#repaccpers-slocid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/sloc/indextrxplantsloc',array('grid'=>true))."',
								queryParams: {
									plantid: $('#repaccpers-plantid').combogrid('getValue')
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
				<td><select class='easyui-combogrid' id='repaccpers-slocid' name='repaccpers-slocid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'sloccode',
						textField: 'sloccode',
						mode:'remote',
						method: 'get',
						onHidePanel: function(){
							$('#repaccpers-storagebinid').combogrid('grid').datagrid({
								url: '".Yii::app()->createUrl('common/storagebin/index',array('grid'=>true,'single'=>true))."',
								queryParams: {
									slocid: $('#repaccpers-slocid').combogrid('getValue')
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
				<td>".getCatalog('materialtype')."</td>
				<td><select class='easyui-combogrid' id='repaccpers-materialtypeid' name='repaccpers-materialtypeid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'materialgroupcode',
						textField: 'materialgroupcode',
						url: '".Yii::app()->createUrl('common/materialtype/index',array('grid'=>true,'combo'=>true)) ."',
						mode:'remote',
						method: 'get',
						columns: [[
								{field:'materialtypeid',title:'".getCatalog('materialtypeid') ."',width:'80px'},
								{field:'materialtypecode',title:'".getCatalog('materialtypecode') ."',width:'150px'},
								{field:'description',title:'".getCatalog('description') ."',width:'300px'},
						]],
						fitColumns: true
					\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('materialgroup')."</td>
				<td><select class='easyui-combogrid' id='repaccpers-materialgroupid' name='repaccpers-materialgroupid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'materialgroupcode',
						textField: 'materialgroupcode',
						url: '".Yii::app()->createUrl('common/materialgroup/index',array('grid'=>true,'combo'=>true)) ."',
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
				<td>".getCatalog('storagebin')."</td>
				<td><select class='easyui-combogrid' id='repaccpers-storagebinid' name='repaccpers-storagebinid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'description',
						textField: 'description',
						pagination:true,
						mode:'remote',
						method: 'get',
						columns: [[
								{field:'storagebinid',title:'".getCatalog('storagebinid') ."',width:'80px'},
								{field:'description',title:'".getCatalog('description') ."',width:'200px'},
						]],
						fitColumns: true
					\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('product')."</td>
				<td><select class='easyui-combogrid' id='repaccpers-productid' name='repaccpers-productid' style='width:350px' data-options=\"
						panelWidth: '700px',
						url: '".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plant'=>true)) ."',
						idField: 'productname',
						textField: 'productname',
						mode:'remote',
						method: 'get',
						columns: [[
							{field:'productid',title:'".getCatalog('productid') ."',width:'80px'},
							{field:'productname',title:'".getCatalog('productname') ."',width:'600px'},
						]],
						fitColumns: true
				\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('account')."</td>
				<td><select class='easyui-combogrid' id='repaccpers-accountid' name='repaccpers-accountid' style='width:350px' data-options=\"
						panelWidth: '930px',
						idField: 'accountname',
						textField: 'accountname',
						mode:'remote',
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
 			<td>".getCatalog('Qty Keluar')."</td>
 			<td> 				
				<input class='easyui-textbox' id='repaccpers-keluar3' name='repaccpers-keluar3' style='width:100px'>
 			</td>
 		</tr>
			<tr>
				<td>".getCatalog('date')."</td>
				<td><input class='easyui-datebox' id='repaccpers-startdate' name='repaccpers-startdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input>
				-
				<input class='easyui-datebox' id='repaccpers-enddate' name='repaccpers-enddate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input>
			</tr>
    </table>
	",
	'downscript'=>"lro='+$('#repaccpers-list').combobox('getValue') +
		'&company='+$('#repaccpers-companyid').combogrid('getValue')+
		'&plant='+$('#repaccpers-plantid').combogrid('getValue')+
		'&sloc='+$('#repaccpers-slocid').combogrid('getValue')+
		'&materialgroup='+$('#repaccpers-materialgroupid').combogrid('getValue')+
		'&storagebin='+$('#repaccpers-storagebinid').combogrid('getValue')+
		'&product='+$('#repaccpers-productid').combogrid('getValue')+
		'&account='+$('#repaccpers-accountid').combogrid('getValue')+
    '&keluar3='+$('#repaccpers-keluar3').textbox('getValue')+
		'&startdate='+$('#repaccpers-startdate').datebox('getValue')+
		'&enddate='+$('#repaccpers-enddate').datebox('getValue')+
		'&per=1'
	",
));
?>