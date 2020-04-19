<table id="dg-abstrans" style="height:97%;width:100%"></table>
<div id="tb-abstrans">
	<?php
 if (checkaccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-abstrans').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-abstrans').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-abstrans').edatagrid('cancelRow')"></a>
	<?php }?>
        <?php if (checkaccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approveabstrans()"></a>
        <?php }?>
        <?php if (checkaccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelabstrans()"></a>
        <?php }?>
	<?php if (checkaccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-abstrans').edatagrid('destroyRow')"></a>
<?php }?>
	<?php if (checkaccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfabstrans()"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsabstrans()"></a>
<?php }?>
	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchabstrans" style="width:150px">
</div>

<script type="text/javascript">
$('#dg-abstrans').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-abstrans',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoSave:false,
                url: '<?php echo $this->createUrl('abstrans/index',array('grid'=>true)) ?>',
                saveUrl: '<?php echo $this->createUrl('abstrans/save',array('grid'=>true)) ?>',
                updateUrl: '<?php echo $this->createUrl('abstrans/save',array('grid'=>true)) ?>',
                destroyUrl: '<?php echo $this->createUrl('abstrans/purge',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-abstrans').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'abstransid',
		editing: <?php echo (checkaccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>,
		columns:[[
		{
field:'abstransid',
title:'<?php echo getCatalog('abstransid') ?>',
sortable: true,
width:'80px',
formatter: function(value,row,index){
					return value;
					}},
{
field:'employeeid',
title:'<?php echo getCatalog('employee') ?>',
width:'250px',
editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'employeeid',
						textField:'fullname',
						pagination:true,
						required:true,
						url:'<?php echo $this->createUrl('employee/index',array('grid'=>true)) ?>',
						fitColumns:true,
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'employeeid',title:'<?php echo getCatalog('employeeid')?>',width:50},
							{field:'fullname',title:'<?php echo getCatalog('fullname')?>',width:50},
						]]
				}	
			},
sortable: true,
formatter: function(value,row,index){
						return row.fullname;
					}},
{
field:'datetimeclock',
title:'<?php echo getCatalog('datetimeclock') ?>',
editor:{type:'datetimebox'},
width:'150px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'reason',
title:'<?php echo getCatalog('reason') ?>',
editor:'text',
width:'200px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'status',
title:'<?php echo getCatalog('status') ?>',
width:'250px',
sortable: true,
readonly: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'recordstatus',
title:'<?php echo getCatalog('recordstatus') ?>',
align:'left',
width:'150px',
editor:{type:'checkbox',options:{on:'1',off:'0'}},
sortable: true,
formatter: function(value,row,index){
						return value;						
					}},
		]]
});
function searchabstrans(value){
	$('#dg-abstrans').edatagrid('load',{
	abstransid:value,
        employeeid:value,
        datetimeclock:value,
        reason:value,
        status:value,
        recordstatus:value,
	});
}
function approveabstrans() {
	var ss = [];
	var rows = $('#dg-abstrans').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.abstransid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('abstrans/approve') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-abstrans').edatagrid('reload');				
		} ,
		'cache':false});
};
function cancelabstrans() {
	var ss = [];
	var rows = $('#dg-abstrans').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.abstransid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('abstrans/delete') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-abstrans').edatagrid('reload');				
		},
		'cache':false});
};
function dateformatter(date){
	var y = date.getFullYear();
	var m = date.getMonth()+1;
	var d = date.getDate();
	return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
}

function dateparser(s){
	if (!s) return new Date();
		var ss = (s.split('-'));
		var y = parseInt(ss[2],10);
		var m = parseInt(ss[1],10);
		var d = parseInt(ss[0],10);
		if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
				return new Date(y,m-1,d);
		} else {
				return new Date();
		}
}
function downpdfabstrans() {
	var ss = [];
	var rows = $('#dg-abstrans').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.abstransid);
	}
	window.open('<?php echo $this->createUrl('abstrans/downpdf') 

?>?id='+ss);
}
</script>
