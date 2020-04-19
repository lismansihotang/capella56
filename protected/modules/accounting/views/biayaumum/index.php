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
<table id="dg-biayaumum" style="width:auto;height:97%">
</table>
<div id="tb-biayaumum">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-biayaumum').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-biayaumum').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-biayaumum').edatagrid('cancelRow')"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-biayaumum').edatagrid('destroyRow')"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
	<select class="easyui-combogrid" id="dlg_search_biayaumum_companyid" name="dlg_search_biayaumum_companyid" style="width:250px" data-options="
								panelWidth: 500,
								required: true,
								idField: 'companyid',
								textField: 'companyname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'companyid',title:localStorage.getItem('catalogcompanyid')},
										{field:'companyname',title:localStorage.getItem('catalogcompanyname')},
								]],
								fitColumns: true,
								prompt:'Company'
						">
				</select>
		<input class="easyui-datebox" type="text" id="dlg_search_biayaumum_bsdate" name="dlg_search_biayaumum_bsdate" data-options="formatter:dateformatter,required:true,parser:dateparser,prompt:'As of Date'"></input>
		<a href="javascript:void(0)" title="Generate" class="easyui-linkbutton" iconCls="icon-bom" plain="true" onclick="generatebiayaumum()"></a>
		<a href="javascript:void(0)" title="PDF Neraca" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfbiayaumum()"></a>
	<?php }?>
	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchbiayaumum" style="width:150px">
</div>

<script type="text/javascript">
$('#dg-biayaumum').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: false,
	toolbar:'#tb-biayaumum',
	pagination: true,
	fitColumns:true,
	ctrlSelect:true,
	autoSave:false,
	url: '<?php echo $this->createUrl('biayaumum/index',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('biayaumum/save',array('grid'=>true)) ?>',
	updateUrl: '<?php echo $this->createUrl('biayaumum/save',array('grid'=>true)) ?>',
	destroyUrl: '<?php echo $this->createUrl('biayaumum/purge',array('grid'=>true)) ?>',
	onSuccess: function(index,row){
		show('Message',row.msg,0);
		$('#dg-biayaumum').edatagrid('reload');
	},
	onError: function(index,row){
		show('Message',row.msg,1);
		$('#dg-biayaumum').edatagrid('reload');
	},
	idField:'repbiayaumumid',
	editing: <?php echo (CheckAccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>,
	columns:[[
	{
		field:'repbiayaumumid',
		title:localStorage.getItem('catalogrepbiayaumumid'),
		sortable: true,
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'companyid',
		title:localStorage.getItem('catalogcompany'),
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
				loadMsg: localStorage.getItem('catalogpleasewait'),
				columns:[[
					{field:'companyid',title:localStorage.getItem('catalogcompanyid'),width:'50px'},
					{field:'companyname',title:localStorage.getItem('catalogcompanyname'),width:'200px'},
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
		title:localStorage.getItem('catalogaccountcode'),
		editor: {
			type: 'textbox',
			options: {
				required: true,
				multiline:true,
				height:'100px'
			}
		},
		width:'200px',
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'plantcode',
		title:localStorage.getItem('catalogplantcode'),
		editor: {
			type: 'textbox',
			options: {
				required: true,
			}
		},
		width:'150px',
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'nourut',
		title:localStorage.getItem('catalognourut'),
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
		field:'iscount',
		title:localStorage.getItem('catalogiscount'),
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
		title:localStorage.getItem('catalogisbold'),
		align:'center',
		width:'90px',
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
		title:localStorage.getItem('catalogcounttype'),
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
				loadMsg: localStorage.getItem('catalogpleasewait'),
				columns:[[
					{field:'counttypeid',title:localStorage.getItem('catalogcounttypeid'),width:'50px'},
					{field:'counttypename',title:localStorage.getItem('catalogcounttypename'),width:'200px'},
				]]
			}
		},
		width:'120px',
		sortable: true,
		formatter: function(value,row,index){
			return row.counttypename;
	}},
	{
		field:'keterangan',
		title:localStorage.getItem('catalogdescription'),
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
function searchbiayaumum(value){
	$('#dg-biayaumum').edatagrid('load',{
		repneracaid:value,
		companyid:value,
		accountid:value,
		isdebet:value,
		nourut:value,
		recordstatus:value,
	});
}
function generatebiayaumum() {
	$('.ajax-loader').css('visibility', 'visible');
	jQuery.ajax({'url':'<?php echo $this->createUrl('biayaumum/generatebs') ?>',
		'data':{			
			'companyid':$('#dlg_search_biayaumum_companyid').combogrid('getValue'),
			'bsdate':$('#dlg_search_biayaumum_bsdate').datebox('getValue')},
		'type':'post','dataType':'json',
		'success':function(data)
		{
				$('.ajax-loader').css("visibility", "hidden");
			show('Message',data.msg);
			$('#dg-biayaumum').edatagrid('reload');				
		} ,
	'cache':false});
}
function downpdfbiayaumum() {
	var ss = [];
	var rows = $('#dg-biayaumum').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.repneracaid);
	}
	var bsdate = $('#dlg_search_biayaumum_bsdate').datebox('getValue');
	var companyid = $('#dlg_search_biayaumum_companyid').combogrid('getValue');
	window.open('<?php echo $this->createUrl('biayaumum/downpdf') ?>?company='+companyid+'&date='+bsdate+'&per='+1);
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