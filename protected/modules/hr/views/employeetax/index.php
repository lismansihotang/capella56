<table id="dg-employeetax"  style="width:800px;height:auto">
</table>
<div id="tb-employeetax">
	<?php





 

 if (checkaccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-employeetax').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-employeetax').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-employeetax').edatagrid('cancelRow')"></a>
	<?php }?>
	<?php if (checkaccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-employeetax').edatagrid('destroyRow')"></a>
<?php }?>
	<?php if (checkaccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfemployeetax()"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsemployeetax()"></a>
<?php }?>

	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchemployeetax" style="width:150px">
</div>

<script type="text/javascript">
$('#dg-employeetax').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-employeetax',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoSave:false,
                url: '<?php echo $this->createUrl('employeetax/index',array('grid'=>true)) ?>',
                saveUrl: '<?php echo $this->createUrl('employeetax/save',array('grid'=>true)) ?>',
                updateUrl: '<?php echo $this->createUrl('employeetax/save',array('grid'=>true)) ?>',
                destroyUrl: '<?php echo $this->createUrl('employeetax/purge',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-employeetax').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'employeetaxid',
		editing: <?php echo (checkaccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>,
		columns:[[
		{
field:'employeetaxid',
title:'<?php echo getCatalog('employeetaxid') ?>',
width:30,
sortable: true,
formatter: function(value,row,index){
					return value;
					}},
{
field:'employeeid',
title:'<?php echo getCatalog('employee') ?>',
width:150,
editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'employeeid',
						textField:'fullname',
						url:'<?php echo Yii::app()->createUrl('hr/employee/index',array('grid'=>true)) ?>',
						fitColumns:true,
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'employeeid',title:'<?php echo getCatalog('employeeid')?>',width:50},
							{field:'fullname',title:'<?php echo getCatalog('fullname')?>',width:200},
						]]
				}	
			},
sortable: true,
formatter: function(value,row,index){
						return row.fullname;
					}},
{
field:'taxstartperiod',
title:'<?php echo getCatalog('taxstartperiod') ?>',
width:150,
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'taxvalue',
title:'<?php echo getCatalog('taxvalue') ?>',
width:150,
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'taxendperiod',
title:'<?php echo getCatalog('taxendperiod') ?>',
width:150,
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'recordstatus',
title:'<?php echo getCatalog('recordstatus') ?>',
width:80,
align:'center',
editor:{type:'checkbox',options:{on:'1',off:'0'}},
sortable: true,
formatter: function(value,row,index){
						if (value == 1){
							return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
						} else {
							return '';
						}
					}},
		]]
});
function searchemployeetax(value){
	$('#dg-employeetax').edatagrid('load',{
	employeetaxid:value,
employeeid:value,
taxstartperiod:value,
taxvalue:value,
taxendperiod:value,
recordstatus:value,
	});
}
function downpdfemployeetax() {
	var ss = [];
	var rows = $('#dg-employeetax').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.employeetaxid);
	}
	window.open('<?php echo $this->createUrl('employeetax/downpdf') ?>?id='+ss);
}
function downxlsemployeetax() {
	var ss = [];
	var rows = $('#dg-employeetax').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.employeetaxid);
	}
	window.open('<?php echo $this->createUrl('employeetax/downxls') 

?>?id='+ss);
}
</script>