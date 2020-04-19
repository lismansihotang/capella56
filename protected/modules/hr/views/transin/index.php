<table id="dg-transin" style="height:97%;width:100%"></table>
<div id="tb-transin">
	<?php
 if (checkaccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-transin').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-transin').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-transin').edatagrid('cancelRow')"></a>
	<?php }?>
        <?php if (checkaccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvetransin()"></a>
        <?php }?>
        <?php if (checkaccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="canceltransin()"></a>
        <?php }?>
	<?php if (checkaccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-transin').edatagrid('destroyRow')"></a>
<?php }?>
	<?php if (checkaccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdftransin()"></a>
<?php }?>

	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchtransin" style="width:150px">
</div>

<script type="text/javascript">
$('#dg-transin').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-transin',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoSave:false,
                url: '<?php echo $this->createUrl('transin/index',array('grid'=>true)) ?>',
                saveUrl: '<?php echo $this->createUrl('transin/save',array('grid'=>true)) ?>',
                updateUrl: '<?php echo $this->createUrl('transin/save',array('grid'=>true)) ?>',
                destroyUrl: '<?php echo $this->createUrl('transin/purge',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-transin').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'transinid',
		editing: <?php echo (checkaccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>,
		columns:[[
		{
field:'transinid',
title:'<?php echo getCatalog('transinid') ?>',
sortable: true,
width:'80px',
formatter: function(value,row,index){
					return value;
					}},
{
field:'docdate',
title:'<?php echo getCatalog('docdate') ?>',
editor:'datetimebox',
width:'100px',
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
field:'permitinid',
title:'<?php echo getCatalog('permitin') ?>',
width:'150px',
editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'permitinid',
						textField:'permitinname',
                                                pagination:true,
                                                required:true,
						url:'<?php echo $this->createUrl('permitin/index',array('grid'=>true)) ?>',
						fitColumns:true,
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'permitinid',title:'<?php echo getCatalog('permitinid')?>',width:50},
							{field:'permitinname',title:'<?php echo getCatalog('permitinname')?>',width:50},
						]]
				}	
			},
sortable: true,
formatter: function(value,row,index){
						return row.permitinname;
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
width:'150px',
editor:{type:'checkbox',options:{on:'1',off:'0'}},
sortable: true,
formatter: function(value,row,index){
						return value
					}},
		]]
});
function searchtransin(value){
	$('#dg-transin').edatagrid('load',{
	transinid:value,
        docdate:value,
        employeeid:value,
        description:value,
        recordstatus:value,
	});
}
function approvetransin() {
	var ss = [];
	var rows = $('#dg-transin').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.transinid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('transin/approve') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-transin').edatagrid('reload');				
		} ,
		'cache':false});
};
function canceltransin() {
	var ss = [];
	var rows = $('#dg-transin').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.transinid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('transin/delete') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-transin').edatagrid('reload');				
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
function downpdftransin() {
	var ss = [];
	var rows = $('#dg-transin').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.transinid);
	}
	window.open('<?php echo $this->createUrl('transin/downpdf') 

?>?id='+ss);
}
</script>
