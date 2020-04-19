<table id="dg-transsickness" style="height:97%;width:100%">
</table>
<div id="tb-transsickness">
	<?php
 if (checkaccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-transsickness').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-transsickness').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-transsickness').edatagrid('cancelRow')"></a>
	<?php }?>
        <?php if (checkaccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvetranssickness()"></a>
        <?php }?>
        <?php if (checkaccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="canceltranssickness()"></a>
        <?php }?>
	<?php if (checkaccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-transsickness').edatagrid('destroyRow')"></a>
<?php }?>
	<?php if (checkaccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdftranssickness()"></a>
<?php }?>
	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchtranssickness" style="width:150px">
</div>

<script type="text/javascript">
$('#dg-transsickness').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-transsickness',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoSave:false,
                url: '<?php echo $this->createUrl('transsickness/index',array('grid'=>true)) ?>',
                saveUrl: '<?php echo $this->createUrl('transsickness/save',array('grid'=>true)) ?>',
                updateUrl: '<?php echo $this->createUrl('transsickness/save',array('grid'=>true)) ?>',
                destroyUrl: '<?php echo $this->createUrl('transsickness/purge',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-transsickness').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'transsicknessid',
		editing: <?php echo (checkaccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>,
		columns:[[
		{
field:'transsicknessid',
title:'<?php echo getCatalog('transsicknessid') ?>',
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
field:'startdate',
title:'<?php echo getCatalog('startdate') ?>',
editor:'datetimebox',
width:'150px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'enddate',
title:'<?php echo getCatalog('enddate') ?>',
editor:'datetimebox',
width:'150px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'description',
title:'<?php echo getCatalog('description') ?>',
editor:'text',
width:'200px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'recordstatus',
title:'<?php echo getCatalog('recordstatus') ?>',
align:'left',
width:'100px',
editor:{type:'checkbox',options:{on:'1',off:'0'}},
sortable: true,
formatter: function(value,row,index){
						return value
					}},
		]]
});
function searchtranssickness(value){
	$('#dg-transsickness').edatagrid('load',{
	transsicknessid:value,
        docdate:value,
        employeeid:value,
        startdate:value,
        enddate:value,
        description:value,
        recordstatus:value,
	});
}
function approvetranssickness() {
	var ss = [];
	var rows = $('#dg-transsickness').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.transsicknessid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('transsickness/approve') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-transsickness').edatagrid('reload');				
		} ,
		'cache':false});
};
function canceltranssickness() {
	var ss = [];
	var rows = $('#dg-transsickness').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.transsicknessid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('transsickness/delete') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-transsickness').edatagrid('reload');				
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
function downpdftranssickness() {
	var ss = [];
	var rows = $('#dg-transsickness').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.transsicknessid);
	}
	window.open('<?php echo $this->createUrl('transsickness/downpdf') ?>?id='+ss);
}
function downxlstranssickness() {
	var ss = [];
	var rows = $('#dg-transsickness').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.transsicknessid);
	}
	window.open('<?php echo $this->createUrl('transsickness/downxls') 

?>?id='+ss);
}
</script>
