<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'workflowid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('admin/workflow/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('admin/workflow/getData'),
	'saveurl'=>Yii::app()->createUrl('admin/workflow/save'),
	'updateurl'=>Yii::app()->createUrl('admin/workflow/save'),
	'destroyurl'=>Yii::app()->createUrl('admin/workflow/purge'),
	'uploadurl'=>Yii::app()->createUrl('admin/workflow/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/workflow/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/workflow/downxls'),
	'downdoc'=>Yii::app()->createUrl('admin/workflow/downdoc'),
	'rowstyler'=>"
		if (row.jumlah == 0) {
			return 'background-color:red;color:#fff;';
		}  
	",	
	'columns'=>"
		{
			field:'workflowid',
			title:localStorage.getItem('catalogworkflowid'),
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'wfname',
			title:localStorage.getItem('catalogwfname'),
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'wfdesc',
			title:localStorage.getItem('catalogwfdesc'),
			width:'300px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'wfminstat',
			title:localStorage.getItem('catalogwfminstat'),
			editor:'text',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'wfmaxstat',
			title:localStorage.getItem('catalogwfmaxstat'),
			editor:'text',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:localStorage.getItem('catalogrecordstatus'),
			align:'center',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
			}},",
	'searchfield'=> array ('workflowid','wfname','wfdesc','wfminstat','wfmaxstat','groupname'),
	'headerform'=>"
		<table cellpadding='5'>
			<tr>
				<td id='workflowtext-wfname'></td>
				<td><input class='easyui-textbox' id='workflow-wfname' name='workflow-wfname' data-options=\"required:true,width:'200px'\"></input></td>
			</tr>
			<tr>
				<td id='workflowtext-wfdesc'></td>
				<td><input class='easyui-textbox' id='workflow-wfdesc' name='workflow-wfdesc' data-options=\"required:true,width:'200px'\"></input></td>
			</tr>
			<tr>
				<td id='workflowtext-wfminstat'></td>
				<td><input class='easyui-numberbox' id='workflow-wfminstat' name='workflow-wfminstat' data-options=\"required:true,width:'200px'\"></input></td>
			</tr>
			<tr>
				<td id='workflowtext-wfmaxstat'></td>
				<td><input class='easyui-numberbox' id='workflow-wfmaxstat' name='workflow-wfmaxstat' data-options=\"required:true,width:'200px'\"></input></td>
			</tr>
			<tr>
				<td id='workflowtext-recordstatus'></td>
				<td><input id='workflow-recordstatus' name='workflow-recordstatus' type='checkbox'></input></td>
			</tr>
		</table>
  ",
  'addonscripts'=> "
	$(document).ready(function(){
		var parel = document.getElementById('workflowtext-wfname');
		parel.innerHTML = localStorage.getItem('catalogwfname');
		parel = document.getElementById('workflowtext-wfdesc');
		parel.innerHTML = localStorage.getItem('catalogwfdesc');
		parel = document.getElementById('workflowtext-wfminstat');
		parel.innerHTML = localStorage.getItem('catalogwfminstat');
		parel = document.getElementById('workflowtext-wfmaxstat');
		parel.innerHTML = localStorage.getItem('catalogwfmaxstat');
		parel = document.getElementById('workflowtext-recordstatus');
		parel.innerHTML = localStorage.getItem('catalogrecordstatus');
	});
	",
	'rowstyler'=>"
		if (row.jumlah == 0) {
			return 'background-color:red;color:#fff;';
		}  
	",
	'loadsuccess' => "
		$('#workflow-wfname').textbox('setValue',data.wfname);
		$('#workflow-wfdesc').textbox('setValue',data.wfdesc);
		$('#workflow-wfminstat').numberbox('setValue',data.wfminstat);
		$('#workflow-wfmaxstat').numberbox('setValue',data.wfmaxstat);
		if (data.recordstatus == 1)
		{
			$('#workflow-recordstatus').prop('checked', true);
		} else
		{
			$('#workflow-recordstatus').prop('checked', false);
		}
	",
	'columndetails'=> array (
		array(
			'id'=>'wfgroup',
			'idfield'=>'wfgroupid',
			'urlsub'=>Yii::app()->createUrl('admin/workflow/indexwfgroup',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('admin/workflow/indexwfgroup',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('admin/workflow/savewfgroup'),
			'updateurl'=>Yii::app()->createUrl('admin/workflow/savewfgroup'),
			'destroyurl'=>Yii::app()->createUrl('admin/workflow/purgewfgroup'),
			'subs'=>"
				{field:'groupname',title:localStorage.getItem('cataloggroupname'),width:'200px'},
				{field:'wfbefstat',title:localStorage.getItem('catalogwfbefstat'),width:'150px'},
				{field:'wfrecstat',title:localStorage.getItem('catalogwfrecstat'),width:'150px'},
			",
			'columns'=>"
				{
					field:'wfgroupid',
					title:localStorage.getItem('catalogwfgroupid'),
					sortable: true,
					width:'80px',
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'workflowid',
					title:localStorage.getItem('catalogworkflowid'),
					sortable: true,
					hidden: true,
					width:'80px',
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'groupaccessid',
					title:localStorage.getItem('cataloggroupaccess'),
					editor:{
						type:'combogrid',
						options:{
								panelWidth:'500px',
								mode : 'remote',
								method:'get',
								idField:'groupaccessid',
								textField:'groupname',
								url:'".$this->createUrl('groupaccess/index',array('grid'=>true,'combo'=>true)) ."',
								fitColumns:true,
								required:true,
								loadMsg: localStorage.getItem('catalogpleasewait'),
								columns:[[
									{field:'groupaccessid',title:localStorage.getItem('cataloggroupaccessid'),width:'50px'},
									{field:'groupname',title:localStorage.getItem('cataloggroupname'),width:'250px'},
								]]
						}	
					},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
						return row.groupname;
				}},
				{
					field:'wfbefstat',
					title:localStorage.getItem('catalogwfbefstat'),
					editor:'numberbox',
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'wfrecstat',
					title:localStorage.getItem('catalogwfrecstat'),
					editor:'numberbox',
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
				}},	"
		),
		array(
			'id'=>'wfstatus',
			'idfield'=>'wfstatusid',
			'urlsub'=>Yii::app()->createUrl('admin/workflow/indexwfstatus',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('admin/workflow/indexwfstatus',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('admin/workflow/savewfstatus'),
			'updateurl'=>Yii::app()->createUrl('admin/workflow/savewfstatus'),
			'destroyurl'=>Yii::app()->createUrl('admin/workflow/purgewfstatus'),
			'subs'=>"
				{field:'wfstat',title:localStorage.getItem('catalogwfstat'),width:'150px'},
				{field:'wfstatusname',title:localStorage.getItem('catalogwfstatusname'),width:'250px'},
				{field:'backcolor',title:localStorage.getItem('catalogbackcolor'),width:'250px'},
				{field:'fontcolor',title:localStorage.getItem('catalogfontcolor'),width:'250px'},
			",
			'columns'=>"
				{
					field:'wfstatusid',
					title:localStorage.getItem('catalogwfgroupid'),
					sortable: true,
					width:'80px',
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'workflowid',
					title:localStorage.getItem('catalogworkflowid'),
					sortable: true,
					hidden: true,
					width:'80px',
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'wfstat',
					title:localStorage.getItem('catalogwfstat'),
					editor:{
						type: 'numberbox',
						options: {
							required: true
						}
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'wfstatusname',
					title:localStorage.getItem('catalogwfstatusname'),
					editor:{
						type: 'textbox',
						options: {
							required: true
						}
					},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
				}},	
				{
					field:'backcolor',
					title:localStorage.getItem('catalogbackcolor'),
					editor: {
						type: 'textbox',
						options:{
							required:true,
						}
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
				}},	
				{
					field:'fontcolor',
					title:localStorage.getItem('catalogfontcolor'),
					editor: {
						type: 'textbox',
						options:{
							required:true,
						}
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
				}},	
			"
		)
	)
));