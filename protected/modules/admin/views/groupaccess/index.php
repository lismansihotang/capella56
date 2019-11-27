<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'groupaccessid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('admin/groupaccess/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('admin/groupaccess/getData'),
	'saveurl'=>Yii::app()->createUrl('admin/groupaccess/save'),
	'updateurl'=>Yii::app()->createUrl('admin/groupaccess/save'),
	'destroyurl'=>Yii::app()->createUrl('admin/groupaccess/purge'),
	'uploadurl'=>Yii::app()->createUrl('admin/groupaccess/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/groupaccess/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/groupaccess/downxls'),
	'columns'=>"
		{
			field:'groupaccessid',
			title:'".GetCatalog('groupaccessid')."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'groupname',
			title:'".GetCatalog('groupname')."',
			editor:'text',
			width:'600px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus')."',
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/icons/ok.png'."\"></img>';
				} else {
					return '';
				}
			}}",
	'searchfield'=> array ('groupaccessid','groupname','menuname'),
	'headerform'=>"
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('groupname')."</td>
				<td><input class='easyui-textbox' id='groupaccess-groupname' name='groupaccess-groupname' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td>". GetCatalog('recordstatus')."</td>
				<td><input id='groupaccess-recordstatus' name='groupaccess-recordstatus' type='checkbox'></input></td>
			</tr>
		</table>
	",
	'loadsuccess' => "
		$('#groupaccess-groupname').textbox('setValue',data.groupname);
		if (data.recordstatus == 1)
		{
			$('#groupaccess-recordstatus').prop('checked', true);
		} else
		{
			$('#groupaccess-recordstatus').prop('checked', false);
		}
	",
	'columndetails'=> array (
		array(
			'id'=>'groupmenu',
			'idfield'=>'groupmenuid',
			'urlsub'=>Yii::app()->createUrl('admin/groupaccess/indexgroupmenu',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('admin/groupaccess/indexgroupmenu',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('admin/groupaccess/savegroupmenu'),
			'updateurl'=>Yii::app()->createUrl('admin/groupaccess/savegroupmenu'),
			'destroyurl'=>Yii::app()->createUrl('admin/groupaccess/purgegroupmenu'),
			'subs'=>"
				{field:'description',title:'".GetCatalog('menudesc')."',width:'300px'},
				{
					field:'isread',
					title:'".GetCatalog('isread')."',
					align:'center',
					width:'50px',
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/icons/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'iswrite',
					title:'".GetCatalog('iswrite')."',
					align:'center',
					width:'50px',
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/icons/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'ispost',
					title:'".GetCatalog('ispost')."',
					align:'center',
					width:'50px',
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/icons/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'isreject',
					title:'".GetCatalog('isreject')."',
					align:'center',
					width:'50px',
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/icons/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'isupload',
					title:'".GetCatalog('isupload')."',
					align:'center',
					width:'50px',
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/icons/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'isdownload',
					title:'".GetCatalog('isdownload')."',
					align:'center',
					width:'50px',
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/icons/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'ispurge',
					title:'".GetCatalog('ispurge')."',
					align:'center',
					width:'50px',
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/icons/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
			",
			'searchfield'=> array ('menuname'),
			'columns'=>"
				{
					field:'groupmenuid',
					title:'".GetCatalog('groupmenuid')."',
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'groupaccessid',
					title:'".GetCatalog('groupaccessid')."',
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'menuaccessid',
					title:'".GetCatalog('menuaccess') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'700px',
							mode : 'remote',
							method:'get',
							idField:'menuaccessid',
							required:true,
							textField:'menuname',
							url:'".$this->createUrl('menuaccess/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'menuaccessid',title:'".GetCatalog('menuaccessid')."',width:'50px'},
								{field:'menuname',title:'".GetCatalog('menuname')."',width:'200px'},
								{field:'description',title:'".GetCatalog('description')."',width:'300px'},
								{field:'menuurl',title:'".GetCatalog('description')."',width:'250px'},
							]]
						}	
					},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
						return row.description;
				}},
				{
					field:'isread',
					title:'".GetCatalog('isread')."',
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					width:'50px',
					align: 'center',
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/icons/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'iswrite',
					title:'".GetCatalog('iswrite')."',
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					width:'50px',
					align: 'center',
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/icons/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'ispost',
					title:'".GetCatalog('ispost')."',
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					width:'50px',
					align: 'center',
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/icons/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'isreject',
					title:'".GetCatalog('isreject')."',
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					width:'50px',
					align: 'center',
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/icons/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'isupload',
					title:'".GetCatalog('isupload')."',
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					width:'50px',
					align: 'center',
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/icons/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'isdownload',
					title:'".GetCatalog('isdownload')."',
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					width:'50px',
					align: 'center',
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/icons/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'ispurge',
					title:'".GetCatalog('ispurge')."',
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					width:'50px',
					align: 'center',
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/icons/ok.png'."\"></img>';
						} else {
							return '';
						}
					}},
			"
		),
		array(
			'id'=>'userdash',
			'idfield'=>'userdashid',
			'urlsub'=>Yii::app()->createUrl('admin/groupaccess/indexuserdash',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('admin/groupaccess/indexuserdash',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('admin/groupaccess/saveuserdash'),
			'updateurl'=>Yii::app()->createUrl('admin/groupaccess/saveuserdash'),
			'destroyurl'=>Yii::app()->createUrl('admin/groupaccess/purgeuserdash'),
			'subs'=>"
				{
					field:'widgetid',
					title:'".GetCatalog('widget') ."',
					formatter: function(value,row,index){
						return row.widgetname;
				}},
				{
					field:'width',
					title:'".GetCatalog('width') ."',
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return row.width;
				}},
				{
					field:'height',
					title:'".GetCatalog('height') ."',
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return row.height;
				}},
				{
					field:'left',
					title:'".GetCatalog('left') ."',
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return row.left;
				}},
				{
					field:'top',
					title:'".GetCatalog('top') ."',
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return row.top;
				}},
			",
			'searchfield'=> array ('menuname'),
			'columns'=>"
			{
				field:'userdashid',
				title:'".GetCatalog('userdashid') ."',
				sortable: true,
				width:'50px',
				formatter: function(value,row,index){
						return value;
			}},
			{
				field:'groupaccessid',
				title:'".GetCatalog('groupaccessid')."',
				width:'80px',
				hidden:true,
				sortable: true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'widgetid',
				title:'".GetCatalog('widget') ."',
				editor:{
					type:'combogrid',
					options:{
						panelWidth:'450px',
						mode : 'remote',
						method:'get',
						idField:'widgetid',
						textField:'widgetname',
						pagination:true,
						required:true,
						queryParams: {
							combo:true,
						},
						url:'".$this->createUrl('widget/index',array('grid'=>true)) ."',
						fitColumns:true,
						loadMsg: '".GetCatalog('pleasewait')."',
						columns:[[
							{field:'widgetid',title:'".GetCatalog('widgetid')."',width:'50px'},
							{field:'widgetname',title:'".GetCatalog('widgetname')."',width:'150px'},
						]]
					}	
				},
				width:'250px',
				sortable: true,
				formatter: function(value,row,index){
					return row.widgetname;
			}},
			{
				field:'menuaccessid',
				title:'".GetCatalog('menuaccess') ."',
				editor:{
					type:'combogrid',
					options:{
						panelWidth:'450px',
						mode : 'remote',
						method:'get',
						idField:'menuaccessid',
						textField:'menuname',
						pagination:true,
						required:true,
						queryParams: {
							combo:true,
						},
						url:'".$this->createUrl('menuaccess/index',array('grid'=>true)) ."',
						fitColumns:true,
						loadMsg: '".GetCatalog('pleasewait')."',
						columns:[[
							{field:'menuaccessid',title:'".GetCatalog('menuaccessid')."',width:'50px'},
							{field:'menuname',title:'".GetCatalog('menuname')."',width:'150px'},
						]]
					}	
				},
				width:'200px',
				sortable: true,
				formatter: function(value,row,index){
					return row.menuname;
			}},
			{
				field:'width',
				title:'".GetCatalog('width') ."',
				editor:{
					type:'validatebox',
					options:{
						required:true,
					}
				},
				width:'80px',
				sortable: true,
				formatter: function(value,row,index){
					return value;
			}},
			{
				field:'height',
				title:'".GetCatalog('height') ."',
				editor:{
					type:'validatebox',
					options:{
						required:true,
					}
				},
				width:'100px',
				sortable: true,
				formatter: function(value,row,index){
					return value;
			}},
			{
				field:'left',
				title:'".GetCatalog('left') ."',
				editor:{
					type:'validatebox',
					options:{
						required:true,
					}
				},
				width:'100px',
				sortable: true,
				formatter: function(value,row,index){
					return value;
			}},
			{
				field:'top',
				title:'".GetCatalog('top') ."',
				editor:{
					type:'validatebox',
					options:{
						required:true,
					}
				},
				width:'100px',
				sortable: true,
				formatter: function(value,row,index){
					return value;
			}},"
		)
	)
));
