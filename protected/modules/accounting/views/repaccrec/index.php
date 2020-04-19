<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'formtype'=>'report',
	'isxls'=>1,
	'downpdf'=>Yii::app()->createUrl('accounting/repaccrec/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/repaccrec/downxls'),
	'headerform'=>"
		<table>
			<tr>
				<td>".getCatalog('reporttype')."</td>
				<td><select class='easyui-combobox' id='repaccrec-list' name='repaccrec-list' data-options='required:true' style='width:350px;'>
					<option value='1'>AR Aging</option>
					<option value='2'>Rekap Faktur Penjualan</option>
					<option value='3'>Kartu Piutang</option>
					<option value='4'>Rekap Piutang</option>
					<option value='5'>BKP</option>
					<option value='12'>Rekap Invoice AR Per Dokumen Belum Status Max</option>
					<option value='13'>Rekap Nota Retur Penjualan Per Dokumen Belum Status Max</option>
					<option value='14'>Rekap Pelunasan Piutang Per Dokumen Belum Status Max</option>
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('company')."</td>
				<td><select class='easyui-combogrid' id='repaccrec-companyid' name='repaccrec-companyid' style='width:350px' 
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
				<td><select class='easyui-combogrid' id='repaccrec-plantid' name='repaccrec-plantid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'plantid',
						textField: 'plantcode',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'authcomp'=>true))."',
						method: 'get',
						onBeforeLoad: function(param) {
							param.companyid = $('#repaccrec-companyid').combogrid('getValue')
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
				<td><select class='easyui-combogrid' id='repaccrec-slocid' name='repaccrec-slocid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'sloccode',
						textField: 'sloccode',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/sloc/indextrxplantsloc',array('grid'=>true))."',
						onBeforeLoad: function(param) {
							param.plantid = $('#repaccrec-plantid').combogrid('getValue')
						},
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
				<td><select class='easyui-combogrid' id='repaccrec-materialgroupid' name='repaccrec-materialgroupid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'description',
						textField: 'description',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/materialgroup/index',array('grid'=>true,'combo'=>true)) ."',
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
				<td>".GetCatalog('customer')."</td>
				<td><select class='easyui-combogrid' id='repaccrec-addressbookid' name='repaccrec-addressbookid' style='width:350px' data-options=\"
								panelWidth: '500px',
								idField: 'fullname',
								textField: 'fullname',
								pagination:true,
								mode:'remote',
								url: '".Yii::app()->createUrl('common/addressbook/index',array('grid'=>true,'combo'=>true))."',
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
				<td><select class='easyui-combogrid' id='repaccrec-productid' name='repaccrec-productid' style='width:350px' data-options=\"
						panelWidth: '600px',
						idField: 'productname',
						textField: 'productname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plant'=>true)) ."',
						method: 'get',
						onBeforeLoad: function(param) {
							param.plantid = $('#repaccrec-plantid').combogrid('getValue')
						},
						columns: [[
							{field:'productid',title:'".getCatalog('productid') ."',width:'80px'},
							{field:'productname',title:'".getCatalog('productname') ."',width:'500px'},
						]],
						fitColumns: true
				\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('sales')."</td>
				<td><select class='easyui-combogrid' id='repaccrec-employeeid' name='repaccrec-employeeid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'fullname',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('order/sales/index',array('grid'=>true,'combo'=>true)) ."',
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
				<td>".getCatalog('salesarea')."</td>
				<td><select class='easyui-combogrid' id='repaccrec-salesareaid' name='repaccrec-salesareaid' style='width:350px' data-options=\"
						panelWidth: '500px',
						idField: 'areaname',
						textField: 'areaname',
						mode:'remote',
						url: '".Yii::app()->createUrl('order/sales/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'salesareaid',title:'".getCatalog('salesareaid') ."',width:'80px'},
								{field:'areaname',title:'".getCatalog('areaname') ."',width:'200px'},
						]],
						fitColumns: true
					\">
				</select></td>
			</tr>
		 <tr>
 			<td>".getCatalog('umurinvoice')."</td>
 			<td> 				
				<input class='easyui-textbox' id='repaccrec-umurpiutang' name='repaccrec-umurpiutang' style='width:100px'>
 			</td>
 		</tr>
			<tr>
				<td>".getCatalog('date')."</td>
				<td><input class='easyui-datebox' id='repaccrec-startdate' name='repaccrec-startdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input>
				-
				<input class='easyui-datebox' id='repaccrec-enddate' name='repaccrec-enddate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input>
			</tr>
    </table>
	",
	'downscript'=>"lro='+$('#repaccrec-list').combobox('getValue') +
		'&company='+$('#repaccrec-companyid').combogrid('getValue')+
		'&plant='+$('#repaccrec-plantid').combogrid('getValue')+
		'&sloc='+$('#repaccrec-slocid').combogrid('getValue')+
		'&materialgroup='+$('#repaccrec-materialgroupid').combogrid('getValue')+
		'&customer='+$('#repaccrec-addressbookid').combogrid('getValue')+
		'&product='+$('#repaccrec-productid').combogrid('getValue')+
		'&sales='+$('#repaccrec-employeeid').combogrid('getValue')+
		'&salesarea='+$('#repaccrec-salesareaid').combogrid('getValue')+
    '&umurpiutang='+$('#repaccrec-umurpiutang').textbox('getValue')+
		'&startdate='+$('#repaccrec-startdate').datebox('getValue')+
		'&enddate='+$('#repaccrec-enddate').datebox('getValue')+
		'&per=1'
	",
));
?>