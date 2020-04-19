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
<table id="dg-balancesheet" style="width:auto;height:97%">
</table>
<div id="tb-balancesheet">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-balancesheet').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-balancesheet').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-balancesheet').edatagrid('cancelRow')"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-balancesheet').edatagrid('destroyRow')"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
	<select class="easyui-combogrid" id="dlg_search_balancesheet_companyid" name="dlg_search_balancesheet_companyid" style="width:250px" data-options="
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
		<input class="easyui-datebox" type="text" id="dlg_search_balancesheet_bsdate" name="dlg_search_balancesheet_bsdate" data-options="formatter:dateformatter,required:true,parser:dateparser,prompt:'As of Date'"></input>
		<a href="javascript:void(0)" title="Generate" class="easyui-linkbutton" iconCls="icon-bom" plain="true" onclick="generatebalancesheet()"></a>
		<a href="javascript:void(0)" title="PDF Neraca" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfbalancesheet()"></a>
	<?php }?>
	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchbalancesheet" style="width:150px">
</div>

<script type="text/javascript">
$('#dg-balancesheet').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: false,
	toolbar:'#tb-balancesheet',
	pagination: true,
	fitColumns:true,
	ctrlSelect:true,
	autoSave:false,
	url: '<?php echo $this->createUrl('balancesheet/index',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('balancesheet/save',array('grid'=>true)) ?>',
	updateUrl: '<?php echo $this->createUrl('balancesheet/save',array('grid'=>true)) ?>',
	destroyUrl: '<?php echo $this->createUrl('balancesheet/purge',array('grid'=>true)) ?>',
	onSuccess: function(index,row){
		show('Message',row.msg,0);
		$('#dg-balancesheet').edatagrid('reload');
	},
	onError: function(index,row){
		show('Message',row.msg,1);
		$('#dg-balancesheet').edatagrid('reload');
	},
	idField:'repneracaid',
	editing: <?php echo (CheckAccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>,
	columns:[[
	{
		field:'repneracaid',
		title:localStorage.getItem('catalogrepneracaid'),
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
			if (value > 0){return value} else {return ''};
	}},
	{
		field:'accountcoded',
		title:localStorage.getItem('catalogaccountcoded'),
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
		field:'keterangand',
		title:localStorage.getItem('catalogketerangand'),
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
		field:'iscountd',
		title:localStorage.getItem('catalogiscountd'),
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
		field:'isboldd',
		title:localStorage.getItem('catalogisboldd'),
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
		field:'counttyped',
		title:localStorage.getItem('catalogcounttyped'),
		editor: {
			type: 'combogrid',
			options: {
				required: true,
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'counttype',
				textField:'counttypename',
				url:'<?php echo Yii::app()->createUrl('accounting/balancesheet/indexcounttype',array('grid'=>true)) ?>',
				fitColumns:true,
				required:true,
				loadMsg: localStorage.getItem('catalogpleasewait'),
				columns:[[
					{field:'counttype',title:localStorage.getItem('catalogcounttypeid'),width:'50px'},
					{field:'counttypename',title:localStorage.getItem('catalogcounttypename'),width:'200px'},
				]]
			}
		},
		width:'120px',
		sortable: true,
		formatter: function(value,row,index){
			return row.counttypenamed;
	}},
	{
		field:'accountcodek',
		title:localStorage.getItem('catalogaccountcodek'),
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
		field:'keterangank',
		title:localStorage.getItem('catalogketerangank'),
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
		field:'iscountk',
		title:localStorage.getItem('catalogiscountk'),
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
		field:'isboldk',
		title:localStorage.getItem('catalogisboldk'),
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
		field:'counttypek',
		title:localStorage.getItem('catalogcounttypek'),
		editor: {
			type: 'combogrid',
			options: {
				required: true,
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'counttype',
				textField:'counttypename',
				url:'<?php echo Yii::app()->createUrl('accounting/balancesheet/indexcounttype',array('grid'=>true)) ?>',
				fitColumns:true,
				required:true,
				loadMsg: localStorage.getItem('catalogpleasewait'),
				columns:[[
					{field:'counttype',title:localStorage.getItem('catalogcounttypeid'),width:'50px'},
					{field:'counttypename',title:localStorage.getItem('catalogcounttypename'),width:'200px'},
				]]
			}
		},
		width:'120px',
		sortable: true,
		formatter: function(value,row,index){
			return row.counttypenamek;
	}},
	]]
});
function searchbalancesheet(value){
	$('#dg-balancesheet').edatagrid('load',{
		repneracaid:value,
		companyid:value,
		accountcoded:value,
		nourut:value,
	});
}
function generatebalancesheet() {
	$('.ajax-loader').css('visibility', 'visible');
	jQuery.ajax({'url':'<?php echo $this->createUrl('balancesheet/generatebs') ?>',
		'data':{			
			'companyid':$('#dlg_search_balancesheet_companyid').combogrid('getValue'),
			'bsdate':$('#dlg_search_balancesheet_bsdate').datebox('getValue')},
		'type':'post','dataType':'json',
		'success':function(data)
		{
				$('.ajax-loader').css("visibility", "hidden");
			show('Message',data.msg);
			$('#dg-balancesheet').edatagrid('reload');				
		} ,
	'cache':false});
}
function downpdfbalancesheet() {
	var ss = [];
	var rows = $('#dg-balancesheet').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.repneracaid);
	}
	var bsdate = $('#dlg_search_balancesheet_bsdate').datebox('getValue');
	var companyid = $('#dlg_search_balancesheet_companyid').combogrid('getValue');
	window.open('<?php echo $this->createUrl('balancesheet/downpdf') ?>?company='+companyid+'&date='+bsdate+'&per='+1);
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