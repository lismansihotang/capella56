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
	'rowstyler'=>"
		if (row.jumlah == 0) {
			return 'background-color:red;color:#fff;';
		}  
	",	
	'columns'=>"
		{
			field:'workflowid',
			title:'".GetCatalog('workflowid')."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'wfname',
			title:'".GetCatalog('wfname')."',
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'wfdesc',
			title:'".GetCatalog('wfdesc')."',
			width:'300px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'wfminstat',
			title:'".GetCatalog('wfminstat')."',
			editor:'text',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'wfmaxstat',
			title:'".GetCatalog('wfmaxstat')."',
			editor:'text',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus')."',
			align:'center',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
				} else {
					return '';
				}
			}},",
	'searchfield'=> array ('workflowid','wfname','wfdesc','wfminstat','wfmaxstat','groupname'),
	'headerform'=>"
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('wfname')."</td>
				<td><input class='easyui-textbox' id='workflow-wfname' name='workflow-wfname' data-options=\"required:true,width:'200px'\"></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('wfdesc')."</td>
				<td><input class='easyui-textbox' id='workflow-wfdesc' name='workflow-wfdesc' data-options=\"required:true,width:'200px'\"></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('wfminstat')."</td>
				<td><input class='easyui-numberbox' id='workflow-wfminstat' name='workflow-wfminstat' data-options=\"required:true,width:'200px'\"></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('wfmaxstat')."</td>
				<td><input class='easyui-numberbox' id='workflow-wfmaxstat' name='workflow-wfmaxstat' data-options=\"required:true,width:'200px'\"></input></td>
			</tr>
			<tr>
				<td>". GetCatalog('recordstatus')."</td>
				<td><input id='workflow-recordstatus' name='workflow-recordstatus' type='checkbox'></input></td>
			</tr>
		</table>
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
				{field:'groupname',title:'".GetCatalog('groupname')."',width:'200px'},
				{field:'wfbefstat',title:'".GetCatalog('wfbefstat')."',width:'150px'},
				{field:'wfrecstat',title:'".GetCatalog('wfrecstat')."',width:'150px'},
			",
			'columns'=>"
				{
					field:'wfgroupid',
					title:'".GetCatalog('wfgroupid') ."',
					sortable: true,
					width:'80px',
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'workflowid',
					title:'".GetCatalog('workflowid') ."',
					sortable: true,
					hidden: true,
					width:'80px',
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'groupaccessid',
					title:'".GetCatalog('groupaccess') ."',
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
								loadMsg: '".GetCatalog('pleasewait')."',
								columns:[[
									{field:'groupaccessid',title:'".GetCatalog('groupaccessid')."',width:'50px'},
									{field:'groupname',title:'".GetCatalog('groupname')."',width:'250px'},
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
					title:'".GetCatalog('wfbefstat') ."',
					editor:'numberbox',
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'wfrecstat',
					title:'".GetCatalog('wfrecstat') ."',
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
				{field:'wfstat',title:'".GetCatalog('wfstat')."',width:'150px'},
				{field:'wfstatusname',title:'".GetCatalog('wfstatusname')."',width:'250px'},
				{field:'backcolor',title:'".GetCatalog('backcolor')."',width:'250px'},
				{field:'fontcolor',title:'".GetCatalog('fontcolor')."',width:'250px'},
			",
			'columns'=>"
				{
					field:'wfstatusid',
					title:'".GetCatalog('wfgroupid') ."',
					sortable: true,
					width:'80px',
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'workflowid',
					title:'".GetCatalog('workflowid') ."',
					sortable: true,
					hidden: true,
					width:'80px',
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'wfstat',
					title:'".GetCatalog('wfstat') ."',
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
					title:'".GetCatalog('wfstatusname') ."',
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
					title:'".GetCatalog('backcolor') ."',
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
					title:'".GetCatalog('fontcolor') ."',
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
