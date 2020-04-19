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
<table id="dg-hpp" style="width:auto;height:97%">
</table>
<div id="tb-hpp">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-hpp').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-hpp').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-hpp').edatagrid('cancelRow')"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-hpp').edatagrid('destroyRow')"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
	<select class="easyui-combogrid" id="dlg_search_hpp_companyid" name="dlg_search_hpp_companyid" style="width:250px" data-options="
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
		<input class="easyui-datebox" type="text" id="dlg_search_hpp_bsdate" name="dlg_search_hpp_bsdate" data-options="formatter:dateformatter,required:true,parser:dateparser,prompt:'As of Date'"></input>
		<a href="javascript:void(0)" title="Generate" class="easyui-linkbutton" iconCls="icon-bom" plain="true" onclick="generatehpp()"></a>
		<a href="javascript:void(0)" title="PDF Neraca" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfhpp()"></a>
	<?php }?>
	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchhpp" style="width:150px">
</div>

<script type="text/javascript">
$('#dg-hpp').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: false,
	toolbar:'#tb-hpp',
	pagination: true,
	fitColumns:true,
	ctrlSelect:true,
	autoSave:false,
	url: '<?php echo $this->createUrl('hpp/index',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('hpp/save',array('grid'=>true)) ?>',
	updateUrl: '<?php echo $this->createUrl('hpp/save',array('grid'=>true)) ?>',
	destroyUrl: '<?php echo $this->createUrl('hpp/purge',array('grid'=>true)) ?>',
	onSuccess: function(index,row){
		show('Message',row.msg,0);
		$('#dg-hpp').edatagrid('reload');
	},
	onError: function(index,row){
		show('Message',row.msg,1);
		$('#dg-hpp').edatagrid('reload');
	},
	idField:'rephppid',
	editing: <?php echo (CheckAccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>,
	columns:[[
	{
		field:'rephppid',
		title:localStorage.getItem('catalogrephppid'),
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
		field:'plantcode',
		title:'<?php echo GetCatalog('plantcode') ?>',
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
					{field:'counttypeid',title:'<?php echo GetCatalog('counttypeid')?>',width:'50px'},
					{field:'counttypename',title:'<?php echo GetCatalog('counttypename')?>',width:'200px'},
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
function searchhpp(value){
	$('#dg-hpp').edatagrid('load',{
		repneracaid:value,
		companyid:value,
		accountid:value,
		isdebet:value,
		nourut:value,
		recordstatus:value,
	});
}
function generatehpp() {
	$('.ajax-loader').css('visibility', 'visible');
	jQuery.ajax({'url':'<?php echo $this->createUrl('hpp/generatebs') ?>',
		'data':{			
			'companyid':$('#dlg_search_hpp_companyid').combogrid('getValue'),
			'bsdate':$('#dlg_search_hpp_bsdate').datebox('getValue')},
		'type':'post','dataType':'json',
		'success':function(data)
		{
				$('.ajax-loader').css("visibility", "hidden");
			show('Message',data.msg);
			$('#dg-hpp').edatagrid('reload');				
		} ,
	'cache':false});
}
function downpdfhpp() {
	var ss = [];
	var rows = $('#dg-hpp').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.repneracaid);
	}
	var bsdate = $('#dlg_search_hpp_bsdate').datebox('getValue');
	var companyid = $('#dlg_search_hpp_companyid').combogrid('getValue');
	window.open('<?php echo $this->createUrl('hpp/downpdf') ?>?company='+companyid+'&date='+bsdate+'&per='+1);
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