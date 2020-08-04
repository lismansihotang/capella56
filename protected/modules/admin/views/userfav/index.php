<!-- Data Grid ( #dg-userfav ) -->
<table id="dg-userfav"  style="width:auto;height:400px">
</table>
<div id="tb-userfav">
	<?php





 

 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-userfav').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" title="Simpan" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-userfav').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" title="Kembali" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-userfav').edatagrid('cancelRow')"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" title="Hapus" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-userfav').edatagrid('destroyRow')"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfuserfav()"></a>
		<a href="javascript:void(0)" title="Export Ke Excell" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsuserfav()"></a>
<?php }?>

	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchuserfav" style="width:150px">
</div>

<script type="text/javascript">
$('#dg-userfav').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-userfav',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoSave:false,
    url: '<?php echo $this->createUrl('userfav/index',array('grid'=>true)) ?>',
    saveUrl: '<?php echo $this->createUrl('userfav/save',array('grid'=>true)) ?>',
    updateUrl: '<?php echo $this->createUrl('userfav/save',array('grid'=>true)) ?>',
    destroyUrl: '<?php echo $this->createUrl('userfav/purge',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-userfav').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'userfavid'
,
		editing: <?php echo (CheckAccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>,
		columns:[[
		{
field:'userfavid',
title:'<?php echo GetCatalog('userfavid') ?>',
sortable: true,
width:'50px',
formatter: function(value,row,index){
					return value;
					}},
{
field:'useraccessid',
title:'<?php echo GetCatalog('useraccess') ?>',
editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'useraccessid',
            pagination:true,
						required:true,
						textField:'username',
						url:'<?php echo $this->createUrl('useraccess/index',array('grid'=>true)) ?>',
						fitColumns:true,
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'useraccessid',title:'<?php echo GetCatalog('useraccessid')?>',width:50},
							{field:'username',title:'<?php echo GetCatalog('username')?>',width:200},
						]]
				}	
			},
			width:'150px',
sortable: true,
formatter: function(value,row,index){
						return row.username;
					}},
{
field:'menuaccessid',
title:'<?php echo GetCatalog('menuaccess') ?>',
editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'menuaccessid',
						textField:'menuname',
            pagination:true,
						required:true,
						url:'<?php echo $this->createUrl('menuaccess/index',array('grid'=>true)) ?>',
						fitColumns:true,
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'menuaccessid',title:'<?php echo GetCatalog('menuaccessid')?>',width:50},
							{field:'menuname',title:'<?php echo GetCatalog('menuname')?>',width:200},
						]]
				}	
			},
			width:'150px',
sortable: true,
formatter: function(value,row,index){
						return row.menuname;
					}},
		]]
});
function searchuserfav(value){
	$('#dg-userfav').edatagrid('load',{
	userfavid:value,
useraccessid:value,
menuaccessid:value,
	});
}
function downpdfuserfav() {
	var ss = [];
	var rows = $('#dg-userfav').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.userfavid);
	}
	window.open('<?php echo $this->createUrl('userfav/downpdf') ?>?id='+ss);
}
function downxlsuserfav() {
	var ss = [];
	var rows = $('#dg-userfav').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.userfavid);
	}
	window.open('<?php echo $this->createUrl('userfav/downxls') 

?>?id='+ss);
}
</script>