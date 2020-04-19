<style type="text/css">
.ajax-loader {
  visibility: hidden;
  background-color: rgba(255,255,255,0.7);
  position: absolute;
  z-index: +1000 !important;
  width: 100%;
  height:100%;
}

.ajax-loader img {
  position: relative;
  top:10%;
  left:10%;
}
</style>
<div class="ajax-loader">
  <img src="<?php echo Yii::app()->baseUrl?>/images/loading.gif" class="img-responsive" />
</div>
<table id="dg-profitlossfiskal" style="width:100%;height:95%">
</table>
<div id="tb-profitlossfiskal">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-profitlossfiskal').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-profitlossfiskal').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-profitlossfiskal').edatagrid('cancelRow')"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-profitlossfiskal').edatagrid('destroyRow')"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<select class="easyui-combogrid" id="dlg_search_profitlossfiskal_companyid" name="dlg_search_companyid" style="width:250px" data-options="
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
		<input class="easyui-datebox" type="text" id="dlg_search_profitlossfiskal_bsdate" name="dlg_search_pldate" data-options="formatter:dateformatter,required:true,parser:dateparser,prompt:'As of Date'"></input>
		<a href="javascript:void(0)" title="Generate"class="easyui-linkbutton" iconCls="icon-bom" plain="true" onclick="generateplfiskal()"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfprofitlossfiskal()"></a>
	<?php }?>
	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchprofitlossfiskal" style="width:150px">
</div>
<script type="text/javascript">
$('#dg-profitlossfiskal').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: false,
	toolbar:'#tb-profitlossfiskal',
	pagination: true,
	fitColumns:true,
	ctrlSelect:true,
	autoSave:false,
	url: '<?php echo $this->createUrl('profitlossfiskal/index',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('profitlossfiskal/save',array('grid'=>true)) ?>',
	updateUrl: '<?php echo $this->createUrl('profitlossfiskal/save',array('grid'=>true)) ?>',
	destroyUrl: '<?php echo $this->createUrl('profitlossfiskal/purge',array('grid'=>true)) ?>',
	onSuccess: function(index,row){
		show('Message',row.msg,0);
		$('#dg-profitlossfiskal').edatagrid('reload');
	},
	onError: function(index,row){
		show('Message',row.msg,1);
		$('#dg-profitlossfiskal').edatagrid('reload');
	},
	idField:'repprofitlossfiskalid',
	editing: <?php echo (CheckAccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>,
	columns:[[
	{
		field:'repprofitlossfiskalid',
		title:'<?php echo GetCatalog('repprofitlossfiskalid') ?>',
		sortable: true,
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'companyid',
		title:'<?php echo GetCatalog('company') ?>',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'companyid',
				textField:'companyname',
				url:'<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
				fitColumns:true,
				pagination:true,
				required:true,
				loadMsg: '<?php echo GetCatalog('pleasewait')?>',
				columns:[[
					{field:'companyid',title:'<?php echo GetCatalog('companyid')?>',width:'50px'},
					{field:'companyname',title:'<?php echo GetCatalog('companyname')?>',width:'200px'},
				]]
			}	
		},
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
			return row.companyname;
	}},
	{
		field:'accountcode',
		title:'<?php echo GetCatalog('accountcode') ?>',
		editor: {
			type: 'textbox',
			options: {
				required: true,
				multiline:true,
				height:'100px'
			}
		},
		width:'250px',
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'nourut',
		title:'<?php echo GetCatalog('nourut') ?>',
		editor: {
			type: 'numberbox',
			options: {
				required: true,
			}
		},
		width:'60px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'sourcemenu',
		title:'<?php echo GetCatalog('sourcemenu') ?>',
		editor: {
			type: 'textbox',
			options: {
			}
		},
		width:'150px',
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'iscount',
		title:'<?php echo GetCatalog('iscount') ?>',
		align:'center',
		width:'80px',
		editor:{type:'checkbox',options:{on:'1',off:'0'}},
		sortable: true,
		formatter: function(value,row,index){
			if (value == 1){
							return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
			} else {
							return '';
			}
	}},
	{
		field:'isbold',
		title:'<?php echo GetCatalog('isbold') ?>',
		align:'center',
		width:'100px',
		editor:{type:'checkbox',options:{on:'1',off:'0'}},
		sortable: true,
		formatter: function(value,row,index){
			if (value == 1){
							return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
			} else {
							return '';
			}
	}},
	{
		field:'counttypeid',
		title:'<?php echo GetCatalog('counttype') ?>',
		editor: {
			type: 'combogrid',
			options: {
				required: true,
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'counttypeid',
				textField:'counttypename',
				url:'<?php echo Yii::app()->createUrl('accounting/kas/indexcounttype',array('grid'=>true)) ?>',
				fitColumns:true,
				required:true,
				loadMsg: '<?php echo GetCatalog('pleasewait')?>',
				columns:[[
					{field:'counttypeid',title:'<?php echo GetCatalog('counttypeid')?>',width:'80px'},
					{field:'counttypename',title:'<?php echo GetCatalog('counttypename')?>',width:'200px'},
				]]
			}
		},
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
			return row.counttypename;
	}},
	{
		field:'keterangan',
		title:'<?php echo GetCatalog('description') ?>',
		editor: {
			type: 'textbox',
			options: {
				required: true,
				multiline: true,
			}
		},
		width:'250px',
		formatter: function(value,row,index){
							return value;
		}
	},
	]]
});
function searchprofitlossfiskal(value){
	$('#dg-profitlossfiskal').edatagrid('load',{
		repprofitlossid:value,
		companyid:value,
		accountid:value,
		isdebet:value,
		nourut:value,
		recordstatus:value,
	});
}
function generateplfiskal() {
	$('.ajax-loader').css('visibility', 'visible');
	jQuery.ajax({'url':'<?php echo $this->createUrl('profitlossfiskal/generatepl') ?>',
		'data':{			
			'companyid':$('#dlg_search_profitlossfiskal_companyid').combogrid('getValue'),
			'bsdate':$('#dlg_search_profitlossfiskal_bsdate').datebox('getValue')},
		'type':'post','dataType':'json',
		'success':function(data)
		{
			$('.ajax-loader').css("visibility", "hidden");
			show('Message',data.msg);
			$('#dg-profitlossfiskal').edatagrid('reload');				
		} ,
		'cache':false});
}
function downpdfprofitlossfiskal() {
	var ss = [];
	var rows = $('#dg-profitlossfiskal').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.repprofitlossid);
	}
	var companyid = $('#dlg_search_profitlossfiskal_companyid').combogrid('getValue');
	var pldate = $('#dlg_search_profitlossfiskal_bsdate').datebox('getValue');
	window.open('<?php echo $this->createUrl('profitlossfiskal/downpdf') ?>?company='+companyid+'&date='+pldate+'&per='+10);
}
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