<div id="tb-repprod">
        <?php





 

 if (checkaccess($this->menuname, $this->isdownload) == 1) { ?>
        <?php echo getCatalog('payrollperiod')?>
		<select class="easyui-combogrid" id="payrollperiodid" name="payrollperiodid" style="width:250px" data-options="
								panelWidth: 500,
								required: true,
								idField: 'payrollperiodid',
								textField: 'payrollperiodname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('hr/payrollperiod/index',array('grid'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'payrollperiodid',title:'<?php echo getCatalog('payrollperiodid') ?>'},
										{field:'payrollperiodname',title:'<?php echo getCatalog('payrollperiodname') ?>'},
								]],
								fitColumns: true
						">
                </select><br/>
		<a href="javascript:void(0)" title="Proses"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvepayrollprocess()"></a>
<?php }?>
</div>

<script type="text/javascript">
function approvepayrollprocess() {
	var ss = [];
	var rows = $('#payrollperiodid').combogrid('getValue');
	jQuery.ajax({'url':'<?php echo $this->createUrl('payrollprocess/approve') 

?>',
		'data':{'id':$('#payrollperiodid').combogrid('getValue')},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);			
		} ,
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
</script>