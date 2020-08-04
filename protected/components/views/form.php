<script src="<?php echo Yii::app()->request->baseUrl;?>/js/easyui/plugins/datagrid-detailview.min.js"></script>
<?php if (Yii::app()->user->id !== '') { ?>
<?php if ($this->formtype == 'master') { ?>
<table id="dg-<?php echo $this->menuname?>" style="width:100%;height100%"></table>
<div id="tb-<?php echo $this->menuname?>">
	<?php if ($this->iswrite == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'iswrite') == 1) {?>
			<?php if ($this->isnew == 1) { ?>
				<a id="add-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add<?php echo $this->menuname?>()"></a>
			<?php }?>
			<a id="copy-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="copydetail easyui-linkbutton" data-options="iconCls:'icon-copy',plain:true" onclick="copyRow<?php echo $this->menuname?>()"></a>
			<a id="save-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="save<?php echo $this->menuname?>()"></a>
			<a id="cancel-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="cancel<?php echo $this->menuname?>()"></a>
			<?php echo $this->writebuttons?>
		<?php }?>
	<?php }?>
	<?php if ($this->ispost == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'ispost') == 1) {  ?>
			<a id="approve-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approve<?php echo $this->menuname?>()"></a>
			<?php echo $this->postbuttons?>
		<?php }?>
	<?php }?>
	<?php if ($this->isreject == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'isreject') == 1) {  ?>
			<a id="reject-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancel<?php echo $this->menuname?>()"></a>
			<?php echo $this->rejectbuttons?>
		<?php }?>
	<?php }?>
	<?php if ($this->isdownload == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'isdownload') == 1) {?>
			<?php if ($this->ispdf == 1) { ?>
				<a id="pdf-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdf<?php echo $this->menuname?>()"></a>
			<?php }?>
			<?php if ($this->isxls == 1) { ?>
				<a id="xls-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxls<?php echo $this->menuname?>()"></a>
			<?php }?>
			<?php if ($this->isdoc == 1) { ?>
				<a id="doc-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-doc" plain="true" onclick="downdoc<?php echo $this->menuname?>()"></a>
			<?php }?>
			<?php echo $this->downloadbuttons?>
		<?php }?>
	<?php }?>
	<?php if ($this->isupload == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'isupload') == 1) {?>
			<form id="form-<?php echo $this->menuname?>" method="post" enctype="multipart/form-data" style="display:inline" data-options="novalidate:true">
				<input type="file" name="file-<?php echo $this->menuname?>" id="file-<?php echo $this->menuname?>" style="display:inline">
				<input type="submit" value='' id="submit-<?php echo $this->menuname?>" style="display:inline">
			</form>
		<?php }?>
	<?php }?>
	<a id="search-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="search<?php echo $this->menuname?>()"></a>
	<?php if ($this->ispurge == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'ispurge') == 1) {?>
			<a id="history-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-history" plain="true" onclick="history<?php echo $this->menuname?>()"></a>
			<a id="purge-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="purge<?php echo $this->menuname?>()"></a>
		<?php }?>
	<?php } ?>
	<?php echo $this->otherbuttons?>
	<table>
	<?php 
	$i=0;$searchscript='';$searchgridscript='';$searcharray='';
	foreach ($this->searchfield as $field) {
		if ($i == 0) {
			echo '<tr>';
		}
		echo "<td id='textsearch-".$this->menuname.$field."'></td>";
		echo '<td><input class="easyui-textbox" id="'.$this->menuname.'_search_'.$field.'" style="width:150px"></td>';
		$i++;
		if (($i % 3) == 0) {
			echo '</tr>';
			$i=0;
		}			
		$searchscript .= "$('#".$this->menuname."_search_".$field."').textbox({
				inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
					keyup:function(e){
						if (e.keyCode == 13) {
							search".$this->menuname."();
						}
					}
				})
			});";
		$searchgridscript .= $field.":$('#".$this->menuname."_search_".$field."').val(),";
		$searcharray .= "\n+'&".$field."='+$('#".$this->menuname."_search_".$field."').textbox('getValue')";
	}	
	?>
	<?php echo $this->addonsearchfield?>
	</table>
</div>
<?php if ($this->ispurge == 1) { ?>
<div id="historydlg-<?php echo $this->menuname?>" class="easyui-dialog" title='' data-options="" closed="true" style="width:800px;height:400px;padding:10px">
	<table id="historydg-<?php echo $this->menuname?>" style="width:100%;height:100%">
	<thead>
		<tr>
			<th id='historytranslogid-<?php echo $this->menuname?>' data-options="field:'translogid',width:80"></th>
			<th id='historyusername-<?php echo $this->menuname?>' data-options="field:'username',width:100"></th>
			<th id='historycreateddate-<?php echo $this->menuname?>' data-options="field:'createddate'"></th>
			<th id='historyuseraction-<?php echo $this->menuname?>' data-options="field:'useraction'"></th>
			<th id='historynewdata-<?php echo $this->menuname?>' data-options="field:'newdata'"></th>
			<th id='historyolddata-<?php echo $this->menuname?>' data-options="field:'olddata'"></th>
			<th id='historymenuname-<?php echo $this->menuname?>' data-options="field:'menuname'"></th>
			<th id='historytableid-<?php echo $this->menuname?>' data-options="field:'tableid'"></th>
			<th id='historyippublic-<?php echo $this->menuname?>' data-options="field:'ippublic'"></th>
			<th id='historyiplocal-<?php echo $this->menuname?>' data-options="field:'iplocal'"></th>
			<th id='historylat-<?php echo $this->menuname?>' data-options="field:'lat'"></th>
			<th id='historylng-<?php echo $this->menuname?>' data-options="field:'lng'"></th>
		</tr>
	</thead>
	</table>
</div>
<?php }?>
<script type="text/javascript">
$(document).ready(function(){
	$('#add-<?php echo $this->menuname?>').prop('title',getlocalmsg("add"));
	$('#copy-<?php echo $this->menuname?>').prop('title',getlocalmsg("copy"));
	$('#save-<?php echo $this->menuname?>').prop('title',getlocalmsg("save"));
	$('#cancel-<?php echo $this->menuname?>').prop('title',getlocalmsg("cancel"));
	$('#approve-<?php echo $this->menuname?>').prop('title',getlocalmsg("approve"));
	$('#reject-<?php echo $this->menuname?>').prop('title',getlocalmsg("reject"));
	$('#pdf-<?php echo $this->menuname?>').prop('title',getlocalmsg("downpdf"));
	$('#xls-<?php echo $this->menuname?>').prop('title',getlocalmsg("downxls"));
	$('#doc-<?php echo $this->menuname?>').prop('title',getlocalmsg("downdoc"));
	$('#submit-<?php echo $this->menuname?>').prop('value',getlocalmsg("uploaddata"));
	$('#search-<?php echo $this->menuname?>').prop('title',getlocalmsg("search"));
	$('#history-<?php echo $this->menuname?>').prop('title',getlocalmsg("history"));
	$('#purge-<?php echo $this->menuname?>').prop('title',getlocalmsg("purge"));
	$('#historydlg-<?php echo $this->menuname?>').prop('title',getlocalmsg("history"));
	<?php foreach ($this->searchfield as $field) {?>		var parent=document.getElementById('textsearch-<?php echo $this->menuname.$field?>');
		parent.innerHTML = getlocalmsg("<?php echo $field?>");
	<?php }?>
	parent=document.getElementById('historytranslogid-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("translogid");
	parent=document.getElementById('historyusername-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("username");
	parent=document.getElementById('historycreateddate-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("createddate");
	parent=document.getElementById('historyuseraction-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("useraction");
	parent=document.getElementById('historynewdata-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("newdata");
	parent=document.getElementById('historyolddata-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("olddata");
	parent=document.getElementById('historymenuname-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("menuname");
	parent=document.getElementById('historytableid-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("tableid");
	parent=document.getElementById('historyippublic-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("ippublic");
	parent=document.getElementById('historyiplocal-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("iplocal");
	parent=document.getElementById('historylat-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("lat");
	parent=document.getElementById('historylng-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("lng");
});
<?php echo $searchscript;?>
function copyRow<?php echo $this->menuname?>(){
	var dg = $('#dg-<?php echo $this->menuname?>');
	var row = dg.edatagrid('getSelected');
	var index = dg.datagrid('getRowIndex',row);
	row.<?php echo $this->idfield?> = null;
	dg.datagrid('appendRow',row);
	dg.datagrid('selectRow',index+1).datagrid('beginEdit', index+1).datagrid('endEdit', index+1);
}
$('#dg-<?php echo $this->menuname?>').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: false,
	toolbar:'#tb-<?php echo $this->menuname?>',
	pagination: true, pagePosition:'top',
	fitColumns:false,
	ctrlSelect:true,
	autoSave:false,
	url: '<?php echo $this->url?>',
	<?php if ($this->columndetails != null) { ?>
	view: detailview,
	detailFormatter:function(index,row){
		return '<div style="padding:2px">'+
			<?php if ($this->columndetails != null) { foreach ($this->columndetails as $detail) { ?>
			'<strong><?php echo getcatalog($detail['id'])?></strong><br><table class="ddv-<?php echo $this->menuname?>-<?php echo $detail['id']?>"></table>'+
			<?php } }?>
			'</div>';
	},
	onExpandRow: function(index,row){
		<?php $i=0;if ($this->columndetails != null) { foreach ($this->columndetails as $detail) { ?>
		var ddv<?php echo $detail['id']?> = $(this).datagrid('getRowDetail',index).find('table.ddv-<?php echo $this->menuname?>-<?php echo $detail['id']?>');
		ddv<?php echo $detail['id']?>.datagrid({
			method:'POST',
			url:'<?php echo $detail['urlsub'] ?>',
			queryParams: {
				id: row.<?php echo $this->idfield?>
			},
			fitColumns:true,
			singleSelect:true,
			rownumbers:true,
			loadMsg:getlocalmsg("pleasewait'),
			height:'auto',
			showFooter:true,
			pagination:true, pagePosition:'top',
			onSelect:function(index,row){
				<?php echo (isset($detail['onselectsub'])?$detail['onselectsub']:'')?>
			},
			columns:[[ <?php echo (isset($detail['subs'])?$detail["subs"]:'')?> ]],
			onResize:function(){
				$('#dg-<?php echo $this->menuname?>').datagrid('fixDetailRowHeight',index);
			},
			onLoadSuccess:function(){
				setTimeout(function(){
					$('#dg-<?php echo $this->menuname?>').datagrid('fixDetailRowHeight',index);
				},0);
			}
		});
		<?php $i++; } }?>
		$('#dg-<?php echo $this->menuname?>').datagrid('fixDetailRowHeight',index);
	},
	<?php }?>
	<?php if (CheckAccess($this->menuname, 'iswrite') == 1) {?>
	saveUrl: '<?php echo $this->saveurl?>',
	updateUrl: '<?php echo $this->updateurl?>',
	<?php }?>
	<?php if (CheckAccess($this->menuname, 'ispurge') == 1) {?>
	destroyUrl: '<?php echo $this->destroyurl?>',
	<?php }?>
	onSuccess: function(index,row){
		show('Pesan',getlocalmsg(row.msg),row.isError);
		$('#dg-<?php echo $this->menuname?>').edatagrid('reload');
	},
	<?php if (CheckAccess($this->menuname, 'iswrite') == 1) {?>
	onBeginEdit: function(index,row){
		var editors = $(this).datagrid('getEditors', index);
		$.each(editors, function(i, ed){
			var tb = $(ed.target).hasClass('numberbox-f') ? (($(ed.target).numberbox('getValue') == '')?$(ed.target).numberbox('setValue',0):$(ed.target).numberbox('getValue')) : $(ed.target);
			tb = $(ed.target).hasClass('datebox-f') ? (($(ed.target).datebox('getValue') == '')?$(ed.target).datebox('setValue',(new Date().toString('dd-MMM-yyyy'))):$(ed.target).datebox('getValue')) : $(ed.target);
		})
		<?php if ($this->beginedit != '') { ?>
			<?php echo $this->beginedit;?>
		<?php } ?>
	},
	onBeforeSave:function(index){
		var row = $('#dg-<?php echo $this->menuname?>').edatagrid('getSelected');
		row.clientippublic = $('#clientippublic').val();
		row.clientiplocal = $('#clientiplocal').val();
		row.clientlat = $('#clientlat').val();
		row.clientlng = $('#clientlng').val();
	},
	<?php }?>
	onError: function(index,row){
		show('Pesan',getlocalmsg(row.msg),row.isError);
	},
	idField: '<?php echo $this->idfield?>',
	<?php if ($this->iswrite == 1) { ?>
	editing: <?php echo (CheckAccess($this->menuname, 'iswrite') == 1 ? 'true' : 'false') ?>,
	<?php }?>
	<?php if ($this->rowstyler != '') {?>
	rowStyler: function(index,row){
		<?php echo $this->rowstyler;?>
	},
	<?php }?>
	columns:[[ 
	{
		field:'clientippublic',
		hidden:true,
		width:'150px',
		formatter: function(value,row,index){
			return value;
	}},	
	{
		field:'clientiplocal',
		hidden:true,
		width:'150px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'clientlat',
		hidden:true,
		width:'150px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'clientlng',
		width:'150px',
		hidden:true,
		formatter: function(value,row,index){
			return value;
	}},<?php echo $this->columns; ?> ]]
});
$("#form-<?php echo $this->menuname?>").submit(function(e) {
	e.preventDefault();    
	var formData = new FormData(this);
	$.ajax({
		url: '<?php echo $this->uploadurl ?>',
		type: 'POST',
		data: formData,
		error: function(xhr,status,error) {
			show('Pesan','An Error Occured: '+xhr.status+ ' ' +xhr.responseText,"1");
		},
		success: function (data,status,xhr) {
			if (data.msg != undefined) {
				show('Pesan',getlocalmsg(data.msg),data.isError);
			} else {
				show('Pesan',data,"0");
			}
			$('#dg-<?php echo $this->menuname?>').edatagrid('reload');
		},
		cache: false,
		contentType: false,
		processData: false
	});
});
<?php if ($this->iswrite == 1) { ?>
function add<?php echo $this->menuname?>() {
	$('#dg-<?php echo $this->menuname?>').edatagrid('addRow',0);
}
function save<?php echo $this->menuname?>() {
	openloader();
	$('#dg-<?php echo $this->menuname?>').edatagrid('saveRow');
	closeloader();
}
function cancel<?php echo $this->menuname?>() {
	openloader();
	$('#dg-<?php echo $this->menuname?>').edatagrid('cancelRow');
	closeloader();
}
<?php }?>
<?php if ($this->ispost == 1) { ?>
function approve<?php echo $this->menuname?>() {
	$.messager.confirm({
    title: 'Capella', 
    msg: 'Apakah anda yakin untuk approval ?', 
    fn: function(r){
      if (r){
        var rows = $('#dg-<?php echo $this->menuname?>').edatagrid('getSelections');
        jQuery.ajax({'url':'<?php echo $this->approveurl ?>',
          'data':{'id':rows,
            'clientippublic' : $('#clientippublic').val(),
            'clientiplocal' : $('#clientiplocal').val(),
            'clientlat' : $('#clientlat').val(),
            'clientlng' : $('#clientlng').val(),
          },'type':'post','dataType':'json',
          error: function(xhr,status,error) {
            show('Pesan','An Error Occured: '+xhr.status+ ' ' +xhr.responseText,'1');
          },
          'success':function(data) {
            show('Pesan',getlocalmsg(data.msg),data.isError);
            $('#dg-<?php echo $this->menuname?>').edatagrid('reload');				
          } ,
          'cache':false});
        }
    }
  }).window('window').css('zIndex',9999);
};
<?php }?>
<?php if ($this->isreject == 1) { ?>
function cancel<?php echo $this->menuname?>() {
	$.messager.confirm({
    title: 'Capella', 
    msg: 'Apakah anda yakin untuk reject ?', 
    fn: function(r){
		if (r){
	var rows = $('#dg-<?php echo $this->menuname?>').edatagrid('getSelection');
	jQuery.ajax({'url':'<?php echo $this->rejecturl ?>',
		'data':{'id':rows,
			'clientippublic' : $('#clientippublic').val(),
			'clientiplocal' : $('#clientiplocal').val(),
			'clientlat' : $('#clientlat').val(),
			'clientlng' : $('#clientlng').val()
		},'type':'post','dataType':'json',
		error: function(xhr,status,error) {
			show('Pesan','An Error Occured: '+xhr.status+ ' ' +xhr.responseText,'1');
		},
		'success':function(data) {
			show('Pesan',getlocalmsg(data.msg),data.isError);
			$('#dg-<?php echo $this->menuname?>').edatagrid('reload');				
		} ,
		'cache':false});
  }}
}).window('window').css('zIndex',9999);;
};
<?php }?>
<?php if ($this->ispurge == 1) { ?>
$('#historydg-<?php echo $this->menuname?>').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	editing:false,
	url: '<?php echo Yii::app()->createUrl('site/GetHistoryData')?>',
});
function history<?php echo $this->menuname?>() {
	var dg = $('#dg-<?php echo $this->menuname?>');
	var row = dg.edatagrid('getSelected');
	var index = dg.datagrid('getRowIndex',row);
	$('#historydg-<?php echo $this->menuname?>').edatagrid('load',{
		menuname: '<?php echo $this->menuname?>',
		tableid: row.<?php echo $this->idfield?>
	});
	$('#historydlg-<?php echo $this->menuname?>').dialog('open').window('window').css('zIndex',9991);
}
function purge<?php echo $this->menuname?>() {
	$('#dg-<?php echo $this->menuname?>').edatagrid('destroyRow');
}
<?php }?>
function search<?php echo $this->menuname?>(value,name){
	$('#dg-<?php echo $this->menuname?>').edatagrid('load',{
		<?php echo $searchgridscript?>});
}
<?php if ($this->isdownload == 1) { ?>
function downpdf<?php echo $this->menuname?>() {
	var ss = [];
	var rows = $('#dg-<?php echo $this->menuname?>').datagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.<?php echo $this->idfield?>);
	}
	var array = 'id='+ss<?php echo $searcharray?>;
	window.open('<?php echo $this->downpdf?>?'+array);
}
function downxls<?php echo $this->menuname?>() {
	var ss = [];
	var rows = $('#dg-<?php echo $this->menuname?>').datagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.<?php echo $this->idfield?>);
	}
	var array = 'id='+ss<?php echo $searcharray?>;
	window.open('<?php echo $this->downxls?>?'+array);
}
function downdoc<?php echo $this->menuname?>() {
	var ss = [];
	var rows = $('#dg-<?php echo $this->menuname?>').datagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.<?php echo $this->idfield?>);
	}
	var array = 'id='+ss<?php echo $searcharray?>;
	window.open('<?php echo $this->downdoc?>?'+array);
}
<?php }?>
<?php echo $this->addonscripts?>
</script>
<?php } else ?>
<?php if ($this->formtype == 'masterdetail') { ?>
<table id="dg-<?php echo $this->menuname?>"  style="width:100%;height:100%"></table>
<div id="tb-<?php echo $this->menuname?>">
	<?php if ($this->iswrite == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'iswrite') == 1) {  ?>
			<?php if ($this->isnew == 1) { ?>
			<a id="add-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add<?php echo $this->menuname?>()"></a>
			<?php }?>
			<a id="edit-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit<?php echo $this->menuname?>()"></a>
			<?php echo $this->writebuttons?>
		<?php }?>
	<?php }?>
	<?php if ($this->ispost == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'ispost') == 1) {  ?>
			<a id="approve-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approve<?php echo $this->menuname?>()"></a>
			<?php echo $this->postbuttons?>
		<?php }?>
	<?php }?>
	<?php if ($this->isreject == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'isreject') == 1) {  ?>
			<a id="reject-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancel<?php echo $this->menuname?>()"></a>
			<?php echo $this->rejectbuttons?>
		<?php }?>
	<?php }?>
	<?php if ($this->isdownload == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'isdownload') == 1) {  ?>
		<?php if ($this->ispdf == 1) { ?>
			<a id="pdf-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdf<?php echo $this->menuname?>()"></a>
		<?php }?>
		<?php if ($this->isxls == 1) { ?>
			<a id="xls-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxls<?php echo $this->menuname?>()"></a>
		<?php }?>
		<?php if ($this->isdoc == 1) { ?>
			<a id="doc-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-doc" plain="true" onclick="downdoc<?php echo $this->menuname?>()"></a>
		<?php }?>
		<?php echo $this->downloadbuttons?>
		<?php }?>
	<?php }?>
	<?php if ($this->isupload == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'isupload') == 1) {?>
			<form id="form-<?php echo $this->menuname?>" method="post" enctype="multipart/form-data" style="display:inline" data-options="novalidate:true">
				<input type="file" name="file-<?php echo $this->menuname?>" id="file-<?php echo $this->menuname?>" style="display:inline">
				<input type="submit" value='' id="submit-<?php echo $this->menuname?>" style="display:inline">
			</form>
		<?php }?>
	<?php }?>
	<?php echo $this->otherbuttons?>
	<a id="search-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="search<?php echo $this->menuname?>()"></a>
	<?php if ($this->ispurge == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'ispurge') == 1) {  ?>
			<a id="history-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-history" plain="true" onclick="history<?php echo $this->menuname?>()"></a>
			<a id="purge-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="purge<?php echo $this->menuname?>()"></a>
			<?php echo $this->purgebuttons?>
		<?php }?>
	<?php }?>
	<table>
	<?php 
	$i=0;$searchscript='';$searchgridscript='';$searcharray='';
	foreach ($this->searchfield as $field) {
		if ($i == 0) {
			echo '<tr>';
		}
		echo "<td id='textsearch-".$this->menuname.$field."'></td>";
		if ($field == 'recordstatus') {
			echo '<td><select id="'.$this->menuname.'_search_'.$field.'" class="easyui-combobox" style="width:150px;">';
			echo GetAllWfStatus($this->wfapp);
			echo '</td>';
		} else {
			echo '<td><input class="easyui-textbox" id="'.$this->menuname.'_search_'.$field.'" style="width:150px"></td>';
		}
		$i++;
		if (($i % 3) == 0) {
			echo '</tr>';
			$i=0;
		}			
		if ($field == 'recordstatus') {
			$searchscript .= " $('#".$this->menuname."_search_".$field."').combobox({
				inputEvents:$.extend({},$.fn.combobox.defaults.inputEvents,{
					keyup:function(e){
						if (e.keyCode == 13) {
							search".$this->menuname."();
						}
					}
				})
			});";
		} else {
			$searchscript .= " $('#".$this->menuname."_search_".$field."').textbox({
				inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
					keyup:function(e){
						if (e.keyCode == 13) {
							search".$this->menuname."();
						}
					}
				})
			});";
		}
		if ($field == 'recordstatus') {
			$searchgridscript .= $field.":$('#".$this->menuname."_search_".$field."').combogrid('getValue'),";
		} else {
			$searchgridscript .= $field.":$('#".$this->menuname."_search_".$field."').val(),";
		}
		$searcharray .= "\n+'&".$field."='+$('#".$this->menuname."_search_".$field."').textbox('getValue')";
	}	
	?>
	<?php echo $this->addonsearchfield?>
	</table>
</div>
<?php if ($this->iswrite == 1) { ?>
<div id="dlg-<?php echo $this->menuname?>" class="easyui-dialog" title='<?php echo getCatalog($this->menuname)?>' data-options="
  closed:true,
  height:'490px',
  resizable:true,
  maximizable:true,
	collapsible:false,
	modal:true,
  maximized:true,
  shadow:true,
	toolbar: [
		{
			id:'save-<?php echo $this->menuname?>',
			iconCls:'icon-save',
			handler:function(){
					submitform<?php echo $this->menuname?>();
			}
		},
	]	
	">
	<form id="ff-<?php echo $this->menuname?>-modif" class="easyui-form" method="post" style="padding:5px" data-options="novalidate:true">
	<input type='hidden' name='<?php echo $this->menuname?>-<?php echo $this->idfield?>' id='<?php echo $this->menuname?>-<?php echo $this->idfield?>' data-options='hidden:true'></input>
	<?php echo $this->headerform?>
	</form>
	<div class="easyui-tabs" style="width:100%;height:90%" id="tabdetails-<?php echo $this->menuname?>">
		<?php $i=0; foreach ($this->columndetails as $detail) {?>
			<div id="detailtab-<?php echo $detail['id']?>" title='<?php echo getCatalog($detail['id'])?>' style="padding:5px" >
				<table class="easyui-edatagrid mytable" id="dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>" style="width:auto;height:95%"></table>			
				<div id="tb-<?php echo $this->menuname?>-<?php echo $detail['id']?>">
				<?php $a = (isset($detail['isnew'])?$detail['isnew']:1); if ($a == 1) { ?>
					<a id="adddetail-<?php echo $this->menuname?>-<?php echo $detail['id']?>" href="#" title='' class="adddetail easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>').edatagrid('addRow',0)"></a>
				<?php }?>
				<?php $a = (isset($detail['iswrite'])?$detail['iswrite']:1); if ($a == 1) { ?>
					<a id="savedetail-<?php echo $this->menuname?>-<?php echo $detail['id']?>" href="#" title='' class="savedetail easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>').edatagrid('saveRow')"></a>
				<?php }?>
				<?php $a = (isset($detail['iswrite'])?$detail['iswrite']:1); if ($a == 1) { ?>
					<a id="copydetail-<?php echo $this->menuname?>-<?php echo $detail['id']?>" href="#" title='' class="copydetail easyui-linkbutton" data-options="iconCls:'icon-copy',plain:true" onclick="copyRow<?php echo $this->menuname.$detail['id']?>()"></a>
				<?php }?>
				<?php $a = (isset($detail['iswrite'])?$detail['iswrite']:1); if ($a == 1) { ?>
					<a id="canceldetail-<?php echo $this->menuname?>-<?php echo $detail['id']?>" href="#" title='' class="canceldetail easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>').edatagrid('cancelRow')"></a>
				<?php }?>
				<?php $a = (isset($detail['ispurge'])?$detail['ispurge']:1); if ($a == 1) { ?>
					<a id="purgedetail-<?php echo $this->menuname?>-<?php echo $detail['id']?>" href="#" title='' class="purgedetail easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>').edatagrid('destroyRow')"></a>
				<?php }?>
				<?php $a = (isset($detail['isnew'])?$detail['isnew']:1); if ($a == 1) { ?>
				<?php echo (isset($detail['addonbuttons'])?$detail['addonbuttons']:'')?>
				<?php }?>
				<?php 
				$searchscriptdetail='';$searchgridscriptdetail='';
				$searchgridscriptdetail=$this->idfield.":$('#".$this->menuname.'-'.$this->idfield."').val(),";
				$searcharraydetail='';
				if (isset($detail['searchfield'])) {
				foreach ($detail['searchfield'] as $field) {
		if ($i == 0) {
			echo '<tr>';
		}
		echo '<td>'.getCatalog($field).'</td>';
		echo '<td><input class="easyui-textbox" id="'.$this->menuname.'_search_'.$detail['id']."_".$field.'" style="width:150px"></td>';
		$i++;
		if (($i % 3) == 0) {
			echo '</tr>';
			$i=0;
		}			
		$searchscriptdetail .= "$('#".$this->menuname."_search_".$detail['id']."_".$field."').textbox({
				inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
					keyup:function(e){
						if (e.keyCode == 13) {
							search".$this->menuname.$detail['id']."();
						}
					}
				})
			});";
		$searchgridscriptdetail .= $field.":$('#".$this->menuname."_search_".$detail['id']."_".$field."').val(),";
		$searcharraydetail .= "\n+'&".$field."='+$('#".$this->menuname."_search_".$detail['id']."_".$field."').textbox('getValue')";
				}}	?>
				</div>
			</div>			
		<?php $i++;}?>
	</div>
</div>
<?php } ?>
<?php if ($this->ispurge == 1) { ?>
<div id="historydlg-<?php echo $this->menuname?>" class="easyui-dialog" title='' data-options="" closed="true" style="width:800px;height:400px;padding:10px">
	<table id="historydg-<?php echo $this->menuname?>" style="width:100%;height:100%">
	<thead>
		<tr>
      <th id='historytranslogid-<?php echo $this->menuname?>' data-options="field:'translogid',width:80"></th>
			<th id='historyusername-<?php echo $this->menuname?>' data-options="field:'username',width:100"></th>
			<th id='historycreateddate-<?php echo $this->menuname?>' data-options="field:'createddate'"></th>
			<th id='historyuseraction-<?php echo $this->menuname?>' data-options="field:'useraction'"></th>
			<th id='historynewdata-<?php echo $this->menuname?>' data-options="field:'newdata'"></th>
			<th id='historyolddata-<?php echo $this->menuname?>' data-options="field:'olddata'"></th>
			<th id='historymenuname-<?php echo $this->menuname?>' data-options="field:'menuname'"></th>
			<th id='historytableid-<?php echo $this->menuname?>' data-options="field:'tableid'"></th>
			<th id='historyippublic-<?php echo $this->menuname?>' data-options="field:'ippublic'"></th>
			<th id='historyiplocal-<?php echo $this->menuname?>' data-options="field:'iplocal'"></th>
			<th id='historylat-<?php echo $this->menuname?>' data-options="field:'lat'"></th>
			<th id='historylng-<?php echo $this->menuname?>' data-options="field:'lng'"></th>
		</tr>
	</thead>
	</table>
</div>
<?php }?>
<script type="text/javascript">
$(document).ready(function(){
	$('#add-<?php echo $this->menuname?>').prop('title',getlocalmsg("add"));
	$('#adddetail-<?php echo $this->menuname?>').prop('title',getlocalmsg("add"));
	$('#edit-<?php echo $this->menuname?>').prop('title',getlocalmsg("edit"));
	$('#copy-<?php echo $this->menuname?>').prop('title',getlocalmsg("copy"));
	$('#copydetail-<?php echo $this->menuname?>').prop('title',getlocalmsg("copy"));
	$('#save-<?php echo $this->menuname?>').prop('title',getlocalmsg("save"));
	$('#savedetail-<?php echo $this->menuname?>').prop('title',getlocalmsg("save"));
	$('#cancel-<?php echo $this->menuname?>').prop('title',getlocalmsg("cancel"));
	$('#canceldetail-<?php echo $this->menuname?>').prop('title',getlocalmsg("cancel"));
	$('#approve-<?php echo $this->menuname?>').prop('title',getlocalmsg("approve"));
	$('#reject-<?php echo $this->menuname?>').prop('title',getlocalmsg("reject"));
	$('#pdf-<?php echo $this->menuname?>').prop('title',getlocalmsg("downpdf"));
	$('#xls-<?php echo $this->menuname?>').prop('title',getlocalmsg("downxls"));
	$('#doc-<?php echo $this->menuname?>').prop('title',getlocalmsg("downdoc"));
	$('#submit-<?php echo $this->menuname?>').prop('value',getlocalmsg("uploaddata"));
	$('#search-<?php echo $this->menuname?>').prop('title',getlocalmsg("search"));
	$('#history-<?php echo $this->menuname?>').prop('title',getlocalmsg("history"));
	$('#purge-<?php echo $this->menuname?>').prop('title',getlocalmsg("purge"));
	$('#purgedetail-<?php echo $this->menuname?>').prop('title',getlocalmsg("purge"));
	$('#historydlg-<?php echo $this->menuname?>').prop('title',getlocalmsg("historydata"));
	<?php foreach ($this->searchfield as $field) {?>
		var parent=document.getElementById('textsearch-<?php echo $this->menuname.$field?>');
		parent.innerHTML = getlocalmsg("<?php echo $field?>");
  <?php }?>
  parent=document.getElementById('historytranslogid-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("translogid");
	parent=document.getElementById('historyusername-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("username");
	parent=document.getElementById('historycreateddate-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("createddate");
	parent=document.getElementById('historyuseraction-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("useraction");
	parent=document.getElementById('historynewdata-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("newdata");
	parent=document.getElementById('historyolddata-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("olddata");
	parent=document.getElementById('historymenuname-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("menuname");
	parent=document.getElementById('historytableid-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("tableid");
	parent=document.getElementById('historyippublic-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("ippublic");
	parent=document.getElementById('historyiplocal-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("iplocal");
	parent=document.getElementById('historylat-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("lat");
	parent=document.getElementById('historylng-<?php echo $this->menuname?>');
	parent.innerHTML = getlocalmsg("lng");
});
<?php echo $searchscript;?>
<?php if ($this->iswrite == 1) { echo $searchscriptdetail; }?>
$("#form-<?php echo $this->menuname?>").submit(function(e) {
	e.preventDefault();    
	var formData = new FormData(this);
	$.ajax({
		url: '<?php echo $this->uploadurl ?>',
		type: 'POST',
		data: formData,
		error: function(xhr,status,error) {
			show('Pesan','An Error Occured: '+xhr.status+ ' ' +xhr.responseText,'1');
		},
		success: function (data) {
			show('Pesan',getlocalmsg(data.msg),data.isError);
			$('#dg-<?php echo $this->menuname?>').edatagrid('reload');
		},
		cache: false,
		contentType: false,
		processData: false
	});
});
$('#dg-<?php echo $this->menuname?>').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	toolbar:'#tb-<?php echo $this->menuname?>',
	pagination: true, pagePosition:'top',
	<?php if (CheckAccess($this->menuname, 'iswrite') == 1) {  ?>
	onDblClickRow:function (index,row) {
		edit<?php echo $this->menuname?>(index);
	},
	<?php }?>
	fitColumns:true,
	editing:false,
	ctrlSelect:true,
	autoRowHeight:true,
	view: detailview,
	detailFormatter:function(index,row){
		return '<div style="padding:2px">'+
			<?php foreach ($this->columndetails as $detail) { ?>
			'<strong><?php echo getcatalog($detail['id'])?></strong><br><table class="ddv-<?php echo $this->menuname?>-<?php echo $detail['id']?>"></table>'+
			<?php }?>
			'</div>';
	},
	onExpandRow: function(index,row){
		<?php $i=0;foreach ($this->columndetails as $detail) { ?>
		var ddv<?php echo $detail['id']?> = $(this).datagrid('getRowDetail',index).find('table.ddv-<?php echo $this->menuname?>-<?php echo $detail['id']?>');
		ddv<?php echo $detail['id']?>.datagrid({
			method:'POST',
			url:'<?php echo $detail['urlsub'] ?>',
			queryParams: {
				id: row.<?php echo $this->idfield?>
			},
			fitColumns:true,
			singleSelect:true,
			rownumbers:true,
			loadMsg:getlocalmsg('pleasewait'),
			height:'auto',
			showFooter:true,
			pagination:true, pagePosition:'top',
			onSelect:function(index,row){
				<?php echo (isset($detail['onselectsub'])?$detail['onselectsub']:'')?>
			},
			columns:[[ <?php echo (isset($detail['subs'])?$detail["subs"]:'')?> ]],
			onResize:function(){
				$('#dg-<?php echo $this->menuname?>').datagrid('fixDetailRowHeight',index);
			},
			onLoadSuccess:function(){
				setTimeout(function(){
					$('#dg-<?php echo $this->menuname?>').datagrid('fixDetailRowHeight',index);
				},0);
			}
		});
		<?php $i++; } ?>
		$('#dg-<?php echo $this->menuname?>').datagrid('fixDetailRowHeight',index);
	},
	url: '<?php echo $this->url?>',
	<?php if (CheckAccess($this->menuname, 'iswrite') == 1) {  ?>
	destroyUrl: '<?php echo $this->destroyurl?>',
<?php }?>
	idField: '<?php echo $this->idfield;?>',
	<?php if ($this->rowstyler != '') {?>
	rowStyler: function(index,row) {
		<?php echo $this->rowstyler;?>
	},
	<?php }?>
	columns:[[
	{field:'_expander',expander:true,width:24,fixed:true}, 
	<?php echo $this->columns?>
	]]
});
<?php if ($this->iswrite == 1) { ?>
function add<?php echo $this->menuname?>() {
	jQuery.ajax({'url':'<?php echo $this->urlgetdata ?>',
		'type':'post','dataType':'json',
		'error': function(xhr,status,error) {
			show('Pesan','An Error Occured: '+xhr.status+ ' ' +xhr.responseText,'1');
		},
		'success':function(data) {
			if (data.<?php echo $this->idfield?> != '')  {
				$('#dlg-<?php echo $this->menuname?>').dialog('open').window('window').css('zIndex',9991);
				$('#dlg-<?php echo $this->menuname?>').dialog('center');
				$('#ff-<?php echo $this->menuname?>-modif').form('clear');
				$('#<?php echo $this->menuname?>-<?php echo $this->idfield?>').val(data.<?php echo $this->idfield?>);
				<?php foreach ($this->columndetails as $detail) {?>
				$('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>').datagrid({
					queryParams: {
						id: data.<?php echo $this->idfield?>
					}
				});
				<?php }?>
				var form = $('#ff-<?php echo $this->menuname?>-modif');
				form.find(':checkbox').each(function() {
						$(this).prop('checked', false);
				});
				form.find('.easyui-numberbox').each(function() {
						$(this).numberbox('setValue', 0);
				});
				form.find('.easyui-checkbox').each(function() {
						$(this).checkbox('uncheck');
				});
				form.find('.easyui-datebox').each(function() {
					$(this).datebox({
						value: (new Date().toString('dd-MMM-yyyy'))
					});	
				});
				form.find(':hidden').each(function() {
					try {
							if ($(this).attr('id') != '<?php echo $this->menuname?>-<?php echo $this->idfield?>') {
								$(this).val(0);
							}
						} catch {
					}
				});
				form.find('.easyui-textbox').textbox('textbox').focus();
				<?php echo $this->addload ?>
			} else {
				show('Pesan','Server sedang sibuk, tunggu beberapa menit kemudian ulangi kembali',"1");
			}
		} ,
		'cache':false});
}
function edit<?php echo $this->menuname?>($i) {
	var row = $('#dg-<?php echo $this->menuname?>').datagrid('getSelected');
	<?php if ($this->ispost == 1) { ?>
	var docmax = <?php echo CheckDoc($this->wfapp) ?>;
	var docstatus = row.recordstatus;
	<?php }?>
	if (row) {
		$('#<?php echo $this->menuname?>-<?php echo $this->idfield?>').val(row.<?php echo $this->idfield?>);
		<?php if ($this->ispost == 1) { ?>
		if (docstatus == docmax) {
			show('Pesan',getlocalmsg('docreachmaxstatus'),'1');
		} else {
			closeloader();
			$('#dlg-<?php echo $this->menuname?>').dialog('open').window('window').css('zIndex',9991);
			$('#ff-<?php echo $this->menuname?>-modif').form('load',row);
			<?php foreach ($this->columndetails as $detail) {?>
			$('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>').datagrid({
				queryParams: {
					id: $('#<?php echo $this->menuname?>-<?php echo $this->idfield?>').val()
				}
			});
			<?php }?>
		}	<?php } else {?>
			closeloader();			
			$('#dlg-<?php echo $this->menuname?>').dialog('open').window('window').css('zIndex',9991);
			$('#ff-<?php echo $this->menuname?>-modif').form('load',row);
			<?php foreach ($this->columndetails as $detail) {?>
			$('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>').datagrid({
				queryParams: {
					id: $('#<?php echo $this->menuname?>-<?php echo $this->idfield?>').val()
				}
			});
			<?php }?>
		<?php }?>
	} else {
		closeloader();			
		show('Pesan',getlocalmsg('chooseone'),'1');
	}
};
function submitform<?php echo $this->menuname?>(){
	<?php $i=0; foreach ($this->columndetails as $detail) {?>
	$('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>').edatagrid('saveRow');
	<?php }?>
	$('#ff-<?php echo $this->menuname?>-modif').form('submit',{
		url:'<?php echo $this->saveurl ?>',
		iframe: false,
		onSubmit:function(param){
			param.clientippublic = $('#clientippublic').val();
			param.clientiplocal = $('#clientiplocal').val();
			param.clientlat = $('#clientlat').val();
			param.clientlng = $('#clientlng').val();
			return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var datax = eval('(' + data + ')');  // change the JSON string to javascript object
			if (datax.isError == 1){
				show('Pesan',getlocalmsg(datax.msg),datax.isError);
			} else {
				show('Pesan',getlocalmsg(datax.msg),datax.isError);
        $('#dg-<?php echo $this->menuname?>').datagrid('reload');
        $('#dlg-<?php echo $this->menuname?>').dialog('close');
			}
    },
	});	
};
function clearform<?php echo $this->menuname?>(){
	$('#ff-<?php echo $this->menuname?>-modif').form('clear');
};
function cancelform<?php echo $this->menuname?>(){
	$('#dlg-<?php echo $this->menuname?>').dialog('close');
};
$('#ff-<?php echo $this->menuname?>-modif').form({
	onLoadSuccess: function(data) {
		<?php echo $this->loadsuccess?>
		var form = $('#ff-<?php echo $this->menuname?>-modif');
		form.find('.easyui-textbox').textbox('textbox').focus();
		form.find('.easyui-numberbox').each(function() {
			$(this).numberbox('setValue',data[$(this).attr('id').replace('<?php echo $this->menuname?>-','')]);
		});
		form.find('.easyui-textbox').each(function() {
			try {
				$(this).textbox('setValue',data[$(this).attr('id').replace('<?php echo $this->menuname?>-','')]);
			} catch {
			}
		});
		form.find('.easyui-combogrid').each(function() {
			try {
			$(this).combogrid('setValue',data[$(this).attr('id').replace('<?php echo $this->menuname?>-','')]);
			} catch {
			}
		});
		form.find('.easyui-datebox').each(function() {
			try {
				$(this).datebox('setValue',data[$(this).attr('id').replace('<?php echo $this->menuname?>-','')]);
				} catch {
			}
		});
		form.find(':hidden').each(function() {
			try {
				$(this).val(data[$(this).attr('id').replace('<?php echo $this->menuname?>-','')]);
				} catch {
			}
		});
		form.find('.easyui-checkbox').each(function() {
			if (data[$(this).attr('id').replace('<?php echo $this->menuname?>-','')] == 1) {
				$(this).checkbox('check');
			} else {
				$(this).checkbox('uncheck');
			}
		});
	}
});
<?php }?>
function search<?php echo $this->menuname?>(value,name){
	openloader();
	$('#dg-<?php echo $this->menuname?>').edatagrid('load',{
		<?php echo $searchgridscript?>});
	closeloader();
}
<?php if ($this->isdownload == 1) { ?>
function downpdf<?php echo $this->menuname?>() {
	var ss = [];
	var rows = $('#dg-<?php echo $this->menuname?>').datagrid('getSelections');
	for(var i=0; i<rows.length; i++) {
		var row = rows[i];
		ss.push(row.<?php echo $this->idfield?>);
	}
	var array = 'id='+ss<?php echo $searcharray?>;
	window.open('<?php echo $this->downpdf?>?'+array);
}
function downxls<?php echo $this->menuname?>() {
	var ss = [];
	var rows = $('#dg-<?php echo $this->menuname?>').datagrid('getSelections');
	for(var i=0; i<rows.length; i++) {
		var row = rows[i];
		ss.push(row.<?php echo $this->idfield?>);
	}
	var array = 'id='+ss<?php echo $searcharray?>;
	window.open('<?php echo $this->downxls?>?'+array);
}
function downdoc<?php echo $this->menuname?>() {
	var ss = [];
	var rows = $('#dg-<?php echo $this->menuname?>').datagrid('getSelections');
	for(var i=0; i<rows.length; i++) {
		var row = rows[i];
		ss.push(row.<?php echo $this->idfield?>);
	}
	var array = 'id='+ss<?php echo $searcharray?>;
	window.open('<?php echo $this->downdoc?>?'+array);
}
<?php }?>
<?php if ($this->ispost == 1) { ?>
function approve<?php echo $this->menuname?>() {
	$.messager.confirm({
    title: 'Capella', 
    msg: 'Apakah anda yakin untuk approve ?', 
    fn: function(r){
		if (r){
	var row = $('#dg-<?php echo $this->menuname?>').edatagrid('getSelected');
	<?php if ($this->ispost == 1) { ?>
	var docmax = <?php echo CheckDoc($this->wfapp) ?>;
	var docstatus = row.recordstatus;
	<?php }?>
	if (row) {
		if (docstatus == docmax) {
			show('Pesan',getlocalmsg('docreachmaxstatus'),'1');
		} else {
		jQuery.ajax({'url':'<?php echo $this->approveurl ?>',
			'data':{'id':row.<?php echo $this->idfield?>,
				'clientippublic' : $('#clientippublic').val(),
				'clientiplocal' : $('#clientiplocal').val(),
				'clientlat' : $('#clientlat').val(),
				'clientlng' : $('#clientlng').val()
			},'type':'post','dataType':'json',
			error: function(xhr,status,error) {
			show('Pesan','An Error Occured: '+xhr.status+ ' ' +xhr.responseText,'1');
		},
			'success':function(data) {
				show('Pesan',getlocalmsg(data.msg),data.isError);
				$('#dg-<?php echo $this->menuname?>').edatagrid('reload');				
			} ,
			'cache':false});
		}
	} else {
		show('Pesan',getlocalmsg('chooseone'),'1');
	}
  }
  },
}).dialog('dialog').css('zIndex',9992);
};
<?php }?>
<?php if ($this->isreject == 1) { ?>
function cancel<?php echo $this->menuname?>() {
	$.messager.confirm({
    title: 'Capella', 
    msg: 'Apakah anda yakin untuk reject ?', 
    fn: function(r){
		if (r){
	var row = $('#dg-<?php echo $this->menuname?>').edatagrid('getSelected');
	<?php if ($this->ispost == 1) { ?>
	var docmax = <?php echo CheckDoc($this->wfapp) ?>;
	var docstatus = row.recordstatus;
	<?php }?>
	if (row) {
		jQuery.ajax({'url':'<?php echo $this->rejecturl ?>',
			'data':{'id':row.<?php echo $this->idfield?>,
				'clientippublic' : $('#clientippublic').val(),
				'clientiplocal' : $('#clientiplocal').val(),
				'clientlat' : $('#clientlat').val(),
				'clientlng' : $('#clientlng').val(),
			},'type':'post','dataType':'json',
			error: function(xhr,status,error) {
				show('Pesan','An Error Occured: '+xhr.status+ ' ' +xhr.responseText,'1');
			},
			'success':function(data) {
				show('Pesan',getlocalmsg(data.msg),data.isError);
				$('#dg-<?php echo $this->menuname?>').edatagrid('reload');				
			} ,
			'cache':false});
	} else {
		show('Pesan',getlocalmsg('chooseone'),'1');
  }
}
  }
}).dialog('dialog').css('zIndex',9992);
};
<?php }?>
<?php if ($this->ispurge == 1) {?>
$('#historydg-<?php echo $this->menuname?>').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	editing:false,
	url: '<?php echo Yii::app()->createUrl('site/GetHistoryData')?>',
});
function history<?php echo $this->menuname?>() {
	var dg = $('#dg-<?php echo $this->menuname?>');
	var row = dg.edatagrid('getSelected');
	var index = dg.datagrid('getRowIndex',row);
	$('#historydg-<?php echo $this->menuname?>').edatagrid('load',{
		menuname: '<?php echo $this->menuname?>',
		tableid: row.<?php echo $this->idfield?>
	});
	$('#historydlg-<?php echo $this->menuname?>').dialog('open').window('window').css('zIndex',9991);
}
function purge<?php echo $this->menuname?>() {
  $.messager.confirm({
    title:'Capella', 
    msg:'Apakah anda yakin untuk menghapus ?', 
    fn: function(r){
	  	if (r){
        var row = $('#dg-<?php echo $this->menuname?>').edatagrid('getSelected');
        jQuery.ajax({'url':'<?php echo $this->destroyurl ?>',
          'data':{'id':row.<?php echo $this->idfield?>,
            'clientippublic':$('#clientippublic').val(),
            'clientiplocal':$('#clientiplocal').val(),
            'clientlat':$('#clientlat').val(),
            'clientlng':$('#clientlng').val()		
          },'type':'post','dataType':'json',
          error: function(xhr,status,error) {
            show('Pesan','An Error Occured: '+xhr.status+ ' ' +xhr.responseText,'1');
          },
          'success':function(data) {
            show('Pesan',getlocalmsg(data.msg),data.isError);
            $('#dg-<?php echo $this->menuname?>').edatagrid('reload');				
          } ,
          'cache':false});
        }
    },
  }).dialog('dialog').css('zIndex',9992);
};
<?php }?>
<?php echo $this->addonscripts?>
<?php if ($this->iswrite == 1) { ?>
<?php foreach ($this->columndetails as $detail) {?>
function search<?php echo $this->menuname.$detail['id']?>(value,name){
	$('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>').edatagrid('load',{
		<?php echo $searchgridscriptdetail?>});
}
function copyRow<?php echo $this->menuname.$detail['id']?>(){
	var dg = $('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>');
	var row = dg.edatagrid('getSelected');
	var index = dg.datagrid('getRowIndex',row);
	row.<?php echo $detail['idfield']?> = null;
	row.<?php echo $this->idfield?> = $('#<?php echo $this->menuname?>-<?php echo $this->idfield?>').val();
	dg.datagrid('appendRow',row);
	dg.datagrid('selectRow',index+1).datagrid('beginEdit', index+1).datagrid('endEdit', index+1);
}
$('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>').edatagrid({
	iconCls: 'icon-edit',	
	<?php $issingle = (isset($detail['issingle'])?$detail['issingle']:true);
	if ($issingle == 'false') {?>
	singleSelect: false,
	showFooter:true,
	ctrlSelect: true,
	<?php } else {?>
	singleSelect: true,
	<?php }?>
	idField:'<?php echo $detail['idfield']?>',
	<?php if (isset($detail['iswrite'])) { if ($detail['iswrite'] != 0) { ?>
	editing: true,
	<?php } else { ?>
	editing: false,
	<?php } } else { ?>
		editing: true, 
	<?php } ?>
	pagination: true, pagePosition:'top',
	toolbar:'#tb-<?php echo $this->menuname?>-<?php echo $detail['id']?>',
	fitColumn: false,
	url: '<?php echo $detail['url']?>',
	<?php if (isset($detail['saveurl'])) { ?>
	saveUrl: '<?php echo $detail['saveurl']?>',
	<?php }?>
	<?php if (isset($detail['saveurl'])) { ?>
	updateUrl: '<?php echo $detail['saveurl']?>',
	<?php }?>
	<?php if (isset($detail['destroyurl'])) { ?>
	destroyUrl: '<?php echo $detail['destroyurl']?>',
	<?php }?>
	onSuccess: function(index,row){
		$('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>').edatagrid('reload');
		<?php if (isset($detail['onsuccess'])) { ?>
			<?php echo $detail['onsuccess']; 
		}?>
		//show('Pesan',row.msg);
	},
	onError: function(index,row){
		show('Pesan',getlocalmsg(row.msg),row.isError);
	},
	onBeginEdit:function(index,row) {
		var dg = $(this);
		row.<?php echo $this->idfield?> = $('#<?php echo $this->menuname?>-<?php echo $this->idfield?>').val();
		var editors = $(this).datagrid('getEditors', index);
		$.each(editors, function(i, ed){
			$(ed.target).hasClass('numberbox-f') ? (($(ed.target).numberbox('getValue') == '')?$(ed.target).numberbox('setValue',0):$(ed.target).numberbox('getValue')) : $(ed.target);
			$(ed.target).hasClass('datebox-f') ? (($(ed.target).datebox('getValue') == '')?$(ed.target).datebox('setValue',(new Date().toString('dd-MMM-yyyy'))):$(ed.target).datebox('getValue')) : $(ed.target);		
		});
		row.clientippublic = $('#clientippublic').val();
		row.clientiplocal = $('#clientiplocal').val();
		row.clientlat = $('#clientlat').val();
		row.clientlng = $('#clientlng').val();
		<?php if (isset($detail['onbeginedit'])) { ?>
			<?php echo $detail['onbeginedit']; 
		}?>
	}, 
	onEndEdit:function(index,row,changes) {
		row.<?php echo $this->idfield?> = $('#<?php echo $this->menuname?>-<?php echo $this->idfield?>').val();
		<?php if (isset($detail['onendedit'])) { ?>
			<?php echo $detail['onendedit']; 
		}?>
	},
	onBeforeSave:function(index){
		var row = $('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>').edatagrid('getSelected');
		if (row) {
			row.<?php echo $this->idfield?> = $('#<?php echo $this->menuname?>-<?php echo $this->idfield?>').val();
		}
		row.clientippublic = $('#clientippublic').val();
		row.clientiplocal = $('#clientiplocal').val();
		row.clientlat = $('#clientlat').val();
		row.clientlng = $('#clientlng').val();
		<?php if (isset($detail['onbeforesave'])) { ?>
			<?php echo $detail['onbeforesave']; 
		}?>
	},
	<?php if (isset($detail['onselect'])) { ?>
	onSelect:function(index,row){ <?php echo $detail['onselect'] ?>
	},
	<?php }?>
	<?php if (isset($detail['columns'])) { ?>
	columns:[[ 
	{
		field:'clientippublic',
		hidden:true,
		width:'150px',
		formatter: function(value,row,index){
			return value;
	}},	
	{
		field:'clientiplocal',
		hidden:true,
		width:'150px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'clientlat',
		hidden:true,
		width:'150px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'clientlng',
		width:'150px',
		hidden:true,
		formatter: function(value,row,index){
			return value;
	}},
	<?php echo $detail['columns']?>
	]]
	<?php }?>
});
<?php }?>
</script>
<?php }?>
<?php } else if ($this->formtype == 'report') { ?>
<div id="tb-<?php echo $this->menuname?>">
	<?php if ($this->isdownload == 1) { ?>
		<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<?php if ($this->ispdf == 1) { ?>
			<a id="pdf-<?php echo $this->menuname?>" href="javascript:void(0)" title="<?php echo getCatalog('downpdf')?>"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdf<?php echo $this->menuname?>()"></a>
		<?php }?>
		<?php if ($this->isxls == 1) { ?>
			<a id="xls-<?php echo $this->menuname?>" href="javascript:void(0)" title="<?php echo getCatalog('downxls')?>"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxls<?php echo $this->menuname?>()"></a>
		<?php }?>
		<?php echo $this->downloadbuttons?>
		<?php }?>
	<?php }?>
</div>
<?php echo $this->headerform?>
<script>
<?php if ($this->isdownload == 1) { ?>
function downpdf<?php echo $this->menuname?>() {
	window.open('<?php echo $this->downpdf ?>?<?php echo $this->downscript?>);
}
function downxls<?php echo $this->menuname?>() {
	window.open('<?php echo $this->downxls ?>?<?php echo $this->downscript?>);
}
function downdoc<?php echo $this->menuname?>() {
	window.open('<?php echo $this->downdoc ?>?<?php echo $this->downscript?>);
}
</script>
<?php }?>
<?php } else if ($this->formtype == 'list') { ?>
<table id="dg-<?php echo $this->menuname?>"  style="width:100%;height:100%"></table>
<div id="tb-<?php echo $this->menuname?>">
	<?php if ($this->isdownload == 1) { ?>
		<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<?php if ($this->ispdf == 1) { ?>
			<a id="pdf-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdf<?php echo $this->menuname?>()"></a>
		<?php }?>
		<?php if ($this->isxls == 1) { ?>
			<a id="xls-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxls<?php echo $this->menuname?>()"></a>
		<?php }?>
		<?php if ($this->isdoc == 1) { ?>
			<a id="doc-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-doc" plain="true" onclick="downdoc<?php echo $this->menuname?>()"></a>
		<?php }?>
		<?php echo $this->downloadbuttons?>
		<?php }?>
	<?php }?>
	<?php echo $this->otherbuttons?>
	<a id="search-<?php echo $this->menuname?>" href="javascript:void(0)" title='' class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="search<?php echo $this->menuname?>()"></a>
	<table>
	<?php 
	$i=0;$searchscript='';$searchgridscript='';$searcharray='';
	if (is_array($this->searchfield)) {
	foreach ($this->searchfield as $field) {
		if ($i == 0) {
			echo '<tr>';
		}
		echo "<td id='textsearch-".$this->menuname.$field."'></td>";
		if ($field == 'recordstatus') {
			echo '<td><select id="'.$this->menuname.'_search_'.$field.'" class="easyui-combobox" style="width:150px;">';
			echo GetAllWfStatus($this->wfapp);
			echo '</td>';
		} else {
			echo '<td><input class="easyui-textbox" id="'.$this->menuname.'_search_'.$field.'" style="width:150px"></td>';
		}
		$i++;
		if (($i % 3) == 0) {
			echo '</tr>';
			$i=0;
		}			
		if ($field == 'recordstatus') {
			$searchscript .= " $('#".$this->menuname."_search_".$field."').combobox({
				inputEvents:$.extend({},$.fn.combobox.defaults.inputEvents,{
					keyup:function(e){
						if (e.keyCode == 13) {
							search".$this->menuname."();
						}
					}
				})
			});";
		} else {
			$searchscript .= " $('#".$this->menuname."_search_".$field."').textbox({
				inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
					keyup:function(e){
						if (e.keyCode == 13) {
							search".$this->menuname."();
						}
					}
				})
			});";
		}
		if ($field == 'recordstatus') {
			$searchgridscript .= $field.":$('#".$this->menuname."_search_".$field."').combogrid('getValue'),";
		} else {
			$searchgridscript .= $field.":$('#".$this->menuname."_search_".$field."').val(),";
		}
		$searcharray .= "\n+'&".$field."='+$('#".$this->menuname."_search_".$field."').textbox('getValue')";
	}		
}
	?>
	<?php echo $this->addonsearchfield?>
	</table>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#pdf-<?php echo $this->menuname?>').prop('title',getlocalmsg("downpdf"));
	$('#xls-<?php echo $this->menuname?>').prop('title',getlocalmsg("downxls"));
	$('#doc-<?php echo $this->menuname?>').prop('title',getlocalmsg("downdoc"));
	$('#search-<?php echo $this->menuname?>').prop('title',getlocalmsg("search"));
	<?php if (is_array($this->searchfield)) { foreach ($this->searchfield as $field) {?>
		var parent=document.getElementById('textsearch-<?php echo $this->menuname.$field?>');
		parent.innerHTML = getlocalmsg("<?php echo $field?>");
	<?php }}?>
});
<?php echo $searchscript;?>
$('#dg-<?php echo $this->menuname?>').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	toolbar:'#tb-<?php echo $this->menuname?>',
	pagination: true, pagePosition:'top',
	<?php if (CheckAccess($this->menuname, 'iswrite') == 1) {?>
	onDblClickRow:function (index,row) {
		edit<?php echo $this->menuname?>(index);
	},
	<?php }?>
	fitColumns:true,
	editing:false,
	ctrlSelect:true,
	autoRowHeight:true,
	view: detailview,
	detailFormatter:function(index,row){
		return '<div style="padding:2px">'+
			<?php if (is_array($this->columndetails)) { foreach ($this->columndetails as $detail) { ?>
			'<strong><?php echo getcatalog($detail['id'])?></strong><br><table class="ddv-<?php echo $this->menuname?>-<?php echo $detail['id']?>"></table>'+
			<?php }}?>
			'</div>';
	},
	onExpandRow: function(index,row){
		<?php $i=0;if (is_array($this->columndetails)) { foreach ($this->columndetails as $detail) { ?>
		var ddv<?php echo $detail['id']?> = $(this).datagrid('getRowDetail',index).find('table.ddv-<?php echo $this->menuname?>-<?php echo $detail['id']?>');
		ddv<?php echo $detail['id']?>.datagrid({
			method:'POST',
			url:'<?php echo $detail['urlsub'] ?>',
			queryParams: {
				id: row.<?php echo $this->idfield?>
			},
			fitColumns:true,
			singleSelect:true,
			rownumbers:true,
			loadMsg:getlocalmsg('pleasewait'),
			height:'auto',
			showFooter:true,
			pagination:true, pagePosition:'top',
			onSelect:function(index,row){
				<?php echo (isset($detail['onselectsub'])?$detail['onselectsub']:'')?>
			},
			columns:[[ <?php echo (isset($detail['subs'])?$detail["subs"]:'')?> ]],
			onResize:function(){
				$('#dg-<?php echo $this->menuname?>').datagrid('fixDetailRowHeight',index);
			},
			onLoadSuccess:function(){
				setTimeout(function(){
					$('#dg-<?php echo $this->menuname?>').datagrid('fixDetailRowHeight',index);
				},0);
			}
		});
		<?php $i++; }} ?>
		$('#dg-<?php echo $this->menuname?>').datagrid('fixDetailRowHeight',index);
	},
	url: '<?php echo $this->url?>',
	destroyUrl: '<?php echo $this->destroyurl?>',
	idField: '<?php echo $this->idfield;?>',
	<?php if ($this->rowstyler != '') {?>
	rowStyler: function(index,row) {
		<?php echo $this->rowstyler;?>
	},
	<?php }?>
	columns:[[
	{field:'_expander',expander:true,width:24,fixed:true}, 
	<?php echo $this->columns?>
	]]
});
function search<?php echo $this->menuname?>(value,name){
	openloader();
	$('#dg-<?php echo $this->menuname?>').edatagrid('load',{
		<?php echo $searchgridscript?>});
	closeloader();
}
<?php if ($this->isdownload == 1) { ?>
function downpdf<?php echo $this->menuname?>() {
	var ss = [];
	var rows = $('#dg-<?php echo $this->menuname?>').datagrid('getSelections');
	for(var i=0; i<rows.length; i++) {
		var row = rows[i];
		ss.push(row.<?php echo $this->idfield?>);
	}
	var array = 'id='+ss<?php echo $searcharray?>;
	window.open('<?php echo $this->downpdf?>?'+array);
}
function downxls<?php echo $this->menuname?>() {
	var ss = [];
	var rows = $('#dg-<?php echo $this->menuname?>').datagrid('getSelections');
	for(var i=0; i<rows.length; i++) {
		var row = rows[i];
		ss.push(row.<?php echo $this->idfield?>);
	}
	var array = 'id='+ss<?php echo $searcharray?>;
	window.open('<?php echo $this->downxls?>?'+array);
}
function downdoc<?php echo $this->menuname?>() {
	var ss = [];
	var rows = $('#dg-<?php echo $this->menuname?>').datagrid('getSelections');
	for(var i=0; i<rows.length; i++) {
		var row = rows[i];
		ss.push(row.<?php echo $this->idfield?>);
	}
	var array = 'id='+ss<?php echo $searcharray?>;
	window.open('<?php echo $this->downdoc?>?'+array);
}
<?php }?>
<?php echo $this->addonscripts?>
</script>
<?php } else if ($this->formtype == 'other') { ?>

<?php }} else { Yii::app()->redirect(Yii::app()->createUrl('site/login')); } ?>