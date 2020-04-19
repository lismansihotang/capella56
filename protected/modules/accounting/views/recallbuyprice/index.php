<div id="tb-recallbuyprice">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<select class="easyui-combogrid" id="dlg_search_recallbuyprice_companyid" name="dlg_search_recallbuyprice_companyid" style="width:250px" data-options="
			panelWidth: 500,
			required: true,
			idField: 'companyid',
			textField: 'companyname',
			pagination:true,
			mode:'remote',
			url: '<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
			method: 'get',
			columns: [[
					{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>'},
					{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
			]],
			fitColumns: true,
			prompt:'Company'
		">
		</select>
		<input class="easyui-datebox" type="text" id="dlg_search_recallbuyprice_startdate" name="dlg_search_recallbuyprice_startdate" data-options="formatter:dateformatter,required:true,parser:dateparser,prompt:'As of Date'"></input>
		<a href="javascript:void(0)" title="Generate" class="easyui-linkbutton" iconCls="icon-bom" plain="true" onclick="recallbuyprice()"></a>
		<input class="easyui-textbox" style="width:300px" id="dlg_search_recallbuyprice_memo" name="dlg_search_recallbuyprice_memo" data-options="multiline:true,height:150" >
	<?php }?>
</div>

<script type="text/javascript">
function recallbuyprice() {
	$('#dlg_search_recallbuyprice_memo').textbox('setValue','Start .....');
	jQuery.ajax({'url':'<?php echo $this->createUrl('recallbuyprice/run') ?>',
		'data':{			
			'companyid':$('#dlg_search_recallbuyprice_companyid').combogrid('getValue'),
			'startdate':$('#dlg_search_recallbuyprice_startdate').datebox('getValue')},
		'type':'post','dataType':'json',
		'success':function(data)
		{
			$('#dlg_search_recallbuyprice_memo').textbox('setValue',data.msg);
		} ,
	'cache':false});
}
</script>