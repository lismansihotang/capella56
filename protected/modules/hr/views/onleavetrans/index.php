<table id="dg-onleavetrans" style="height:97%;width:100%"></table>
<div id="tb-onleavetrans">
	<?php
 if (checkaccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-onleavetrans').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-onleavetrans').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-onleavetrans').edatagrid('cancelRow')"></a>
	<?php }?>
        <?php if (checkaccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approveonleavetrans()"></a>
        <?php }?>
        <?php if (checkaccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelonleavetrans()"></a>
        <?php }?>
	<?php if (checkaccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-onleavetrans').edatagrid('destroyRow')"></a>
<?php }?>
	<?php if (checkaccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfonleavetrans()"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsonleavetrans()"></a>
<?php }?>
	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchonleavetrans" style="width:150px">
</div>

<script type="text/javascript">
$('#dg-onleavetrans').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-onleavetrans',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoSave:false,
		url: '<?php echo $this->createUrl('onleavetrans/index',array('grid'=>true)) ?>',
		saveUrl: '<?php echo $this->createUrl('onleavetrans/save',array('grid'=>true)) ?>',
		updateUrl: '<?php echo $this->createUrl('onleavetrans/save',array('grid'=>true)) ?>',
		destroyUrl: '<?php echo $this->createUrl('onleavetrans/purge',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-onleavetrans').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'onleavetransid',
		editing: <?php echo (checkaccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>,
		columns:[[
		{
field:'onleavetransid',
title:'<?php echo getCatalog('onleavetransid') ?>',
sortable: true,
width:'80px',
formatter: function(value,row,index){
					return value;
					}},
{
field:'docdate',
title:'<?php echo getCatalog('docdate') ?>',
editor:'datebox',
width:'150px',
sortable: true,
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
field:'onleavetypeid',
title:'<?php echo getCatalog('onleavetype') ?>',
width:'150px',
editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'onleavetypeid',
						textField:'onleavename',
                                                pagination:true,
                                                required:true,
						url:'<?php echo $this->createUrl('onleavetype/index',array('grid'=>true)) ?>',
						fitColumns:true,
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'onleavetypeid',title:'<?php echo getCatalog('onleavetypeid')?>',width:50},
							{field:'onleavename',title:'<?php echo getCatalog('onleavename')?>',width:50},
						]]
				}	
			},
sortable: true,
formatter: function(value,row,index){
						return row.onleavename;
					}},
{
field:'startdate',
title:'<?php echo getCatalog('startdate') ?>',
editor:'datebox',
width:'100px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'enddate',
title:'<?php echo getCatalog('enddate') ?>',
editor:'datebox',
width:'100px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'description',
title:'<?php echo getCatalog('description') ?>',
editor:'text',
width:'100px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'recordstatus',
title:'<?php echo getCatalog('recordstatus') ?>',
align:'center',
width:'80px',
editor:{type:'checkbox',options:{on:'1',off:'0'}},
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchonleavetrans(value){
	$('#dg-onleavetrans').edatagrid('load',{
	onleavetransid:value,
        docdate:value,
        employeeid:value,
        onleavetypeid:value,
        startdate:value,
        enddate:value,
        description:value,
        recordstatus:value,
	});
}
function approveonleavetrans() {
	var ss = [];
	var rows = $('#dg-onleavetrans').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.onleavetransid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('onleavetrans/approve') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-onleavetrans').edatagrid('reload');				
		} ,
		'cache':false});
};
function cancelonleavetrans() {
	var ss = [];
	var rows = $('#dg-onleavetrans').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.onleavetransid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('onleavetrans/delete') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-onleavetrans').edatagrid('reload');				
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
function downpdfonleavetrans() {
	var ss = [];
	var rows = $('#dg-onleavetrans').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.onleavetransid);
	}
	window.open('<?php echo $this->createUrl('onleavetrans/downpdf') 

?>?id='+ss);
}
</script>
