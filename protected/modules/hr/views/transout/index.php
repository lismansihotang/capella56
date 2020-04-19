<table id="dg-transout" style="height:97%;width:100%"></table>
<div id="tb-transout">
	<?php
 if (checkaccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-transout').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-transout').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-transout').edatagrid('cancelRow')"></a>
	<?php }?>
        <?php if (checkaccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvetransout()"></a>
        <?php }?>
        <?php if (checkaccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="canceltransout()"></a>
        <?php }?>
	<?php if (checkaccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-transout').edatagrid('destroyRow')"></a>
<?php }?>
	<?php if (checkaccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdftransout()"></a>
<?php }?>
	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchtransout" style="width:150px">
</div>

<script type="text/javascript">
$('#dg-transout').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-transout',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoSave:false,
                url: '<?php echo $this->createUrl('transout/index',array('grid'=>true)) ?>',
                saveUrl: '<?php echo $this->createUrl('transout/save',array('grid'=>true)) ?>',
                updateUrl: '<?php echo $this->createUrl('transout/save',array('grid'=>true)) ?>',
                destroyUrl: '<?php echo $this->createUrl('transout/purge',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-transout').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'transoutid',
		editing: <?php echo (checkaccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>,
		columns:[[
		{
field:'transoutid',
title:'<?php echo getCatalog('transoutid') ?>',
sortable: true,
width:'80px',
formatter: function(value,row,index){
					return value;
					}},
{
field:'docdate',
title:'<?php echo getCatalog('docdate') ?>',
editor:'datebox',
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
field:'startdate',
title:'<?php echo getCatalog('startdate') ?>',
editor:'datetimebox',
width:'100px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'enddate',
title:'<?php echo getCatalog('enddate') ?>',
editor:'datetimebox',
width:'100px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'description',
title:'<?php echo getCatalog('description') ?>',
editor:'text',
width:'150px',
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
function searchtransout(value){
	$('#dg-transout').edatagrid('load',{
	transoutid:value,
        docdate:value,
        employeeid:value,
        startdate:value,
        enddate:value,
        description:value,
        recordstatus:value,
	});
}
function approvetransout() {
	var ss = [];
	var rows = $('#dg-transout').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.transoutid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('transout/approve') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-transout').edatagrid('reload');				
		} ,
		'cache':false});
};
function canceltransout() {
	var ss = [];
	var rows = $('#dg-transout').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.transoutid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('transout/delete') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-transout').edatagrid('reload');				
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
function downpdftransout() {
	var ss = [];
	var rows = $('#dg-transout').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.transoutid);
	}
	window.open('<?php echo $this->createUrl('transout/downpdf') 

?>?id='+ss);
}
</script>
