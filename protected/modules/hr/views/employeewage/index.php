<table id="dg-employeewage"  style="width:100%;height:97%"></table>
<div id="tb-employeewage">
	<?php
 if (checkaccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-employeewage').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-employeewage').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-employeewage').edatagrid('cancelRow')"></a>
	<?php }?>
	<?php if (checkaccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-employeewage').edatagrid('destroyRow')"></a>
<?php }?>
	<?php if (checkaccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfemployeewage()"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsemployeewage()"></a>
<?php }?>
	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchemployeewage" style="width:150px">
</div>

<script type="text/javascript">
$('#dg-employeewage').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-employeewage',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoSave:false,
                url: '<?php echo $this->createUrl('employeewage/index',array('grid'=>true)) ?>',
                saveUrl: '<?php echo $this->createUrl('employeewage/save',array('grid'=>true)) ?>',
                updateUrl: '<?php echo $this->createUrl('employeewage/save',array('grid'=>true)) ?>',
                destroyUrl: '<?php echo $this->createUrl('employeewage/purge',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-employeewage').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'employeewageid'
,
		editing: <?php echo (checkaccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>,
		columns:[[
		{
field:'employeewageid',
title:'<?php echo getCatalog('employeewageid') ?>',
width:'80px',
sortable: true,
formatter: function(value,row,index){
					return value;
					}},
{
field:'employeeid',
title:'<?php echo getCatalog('employee') ?>',
width:'150px',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'wagestartperiod',
title:'<?php echo getCatalog('wagestartperiod') ?>',
width:'150px',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'wagevalue',
title:'<?php echo getCatalog('wagevalue') ?>',
width:'150px',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'wageendperiod',
title:'<?php echo getCatalog('wageendperiod') ?>',
width:'150px',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'recordstatus',
title:'<?php echo getCatalog('recordstatus') ?>',
width:'80px',
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
function searchemployeewage(value){
	$('#dg-employeewage').edatagrid('load',{
	employeewageid:value,
employeeid:value,
wagestartperiod:value,
wagevalue:value,
wageendperiod:value,
recordstatus:value,
	});
}
function downpdfemployeewage() {
	var ss = [];
	var rows = $('#dg-employeewage').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.employeewageid);
	}
	window.open('<?php echo $this->createUrl('employeewage/downpdf') ?>?id='+ss);
}
function downxlsemployeewage() {
	var ss = [];
	var rows = $('#dg-employeewage').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.employeewageid);
	}
	window.open('<?php echo $this->createUrl('employeewage/downxls') 

?>?id='+ss);
}
</script>
