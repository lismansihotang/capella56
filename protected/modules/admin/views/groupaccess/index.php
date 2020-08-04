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
	'downdoc'=>Yii::app()->createUrl('admin/groupaccess/downdoc'),
	'rowstyler'=>"
		if (row.jumlah == 0) {
			return 'background-color:red;color:#fff;';
		}  
	",
	'columns'=>"
		{
			field:'groupaccessid',
			title:localStorage.getItem('cataloggroupaccessid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'groupname',
			title:localStorage.getItem('cataloggroupname'),
			editor:{type:'textbox',options:{required:true}},
			width:'600px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:localStorage.getItem('catalogrecordstatus'),
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
				} else {
					return '';
				}
			}}",
	'searchfield'=> array ('groupaccessid','groupname','menuname'),
	'headerform'=>"
		<table cellpadding='5'>
			<tr>
				<td id='groupaccesstext-groupname'></td>
				<td><input class='easyui-textbox' id='groupaccess-groupname' name='groupaccess-groupname' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td id='groupaccesstext-recordstatus'></td>
				<td><input id='groupaccess-recordstatus' name='groupaccess-recordstatus' type='checkbox'></input></td>
			</tr>
		</table>
	",
	'addonscripts'=> "
	$(document).ready(function(){
		var parel = document.getElementById('groupaccesstext-groupname');
		parel.innerHTML = localStorage.getItem('cataloggroupname');
		parel = document.getElementById('groupaccesstext-recordstatus');
		parel.innerHTML = localStorage.getItem('catalogrecordstatus');
	});
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
					title:localStorage.getItem('catalogisread'),
					align:'center',
					width:'50px',
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'iswrite',
					title:localStorage.getItem('catalogiswrite'),
					align:'center',
					width:'50px',
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'ispost',
					title:localStorage.getItem('catalogispost'),
					align:'center',
					width:'50px',
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'isreject',
					title:localStorage.getItem('catalogisreject'),
					align:'center',
					width:'50px',
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'isupload',
					title:localStorage.getItem('catalogisupload'),
					align:'center',
					width:'50px',
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'isdownload',
					title:localStorage.getItem('catalogisdownload'),
					align:'center',
					width:'50px',
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'ispurge',
					title:localStorage.getItem('catalogispurge'),
					align:'center',
					width:'50px',
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
			",
			'searchfield'=> array ('menuname'),
			'columns'=>"
				{
					field:'groupmenuid',
					title:localStorage.getItem('cataloggroupmenuid'),
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'groupaccessid',
					title:localStorage.getItem('cataloggroupaccessid'),
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'menuaccessid',
					title:localStorage.getItem('catalogmenuaccess'),
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
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'menuaccessid',title:localStorage.getItem('catalogmenuaccessid'),width:'50px'},
								{field:'menuname',title:localStorage.getItem('catalogmenuname'),width:'200px'},
								{field:'description',title:localStorage.getItem('catalogdescription'),width:'300px'},
								{field:'menuurl',title:localStorage.getItem('catalogmenuurl'),width:'250px'},
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
					title:localStorage.getItem('catalogisread'),
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					width:'50px',
					align: 'center',
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'iswrite',
					title:localStorage.getItem('catalogiswrite'),
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					width:'50px',
					align: 'center',
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'ispost',
					title:localStorage.getItem('catalogispost'),
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					width:'50px',
					align: 'center',
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'isreject',
					title:localStorage.getItem('catalogisreject'),
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					width:'50px',
					align: 'center',
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'isupload',
					title:localStorage.getItem('catalogisupload'),
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					width:'50px',
					align: 'center',
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'isdownload',
					title:localStorage.getItem('catalogisdownload'),
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					width:'50px',
					align: 'center',
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'ispurge',
					title:localStorage.getItem('catalogispurge'),
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					width:'50px',
					align: 'center',
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
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
				{field:'widgetname',title:localStorage.getItem('catalogwidgetname'),width:'300px'},
				{field:'menuname',title:localStorage.getItem('catalogmenuname'),width:'300px'},
			",
			'columns'=>"
				{
					field:'userdashid',
					title:localStorage.getItem('cataloguserdashid'),
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'groupaccessid',
					title:localStorage.getItem('cataloggroupaccessid'),
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'widgetid',
					title:localStorage.getItem('catalogwidget'),
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
							url:'".$this->createUrl('widget/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'widgetid',title:localStorage.getItem('catalogwidgetid'),width:'50px'},
								{field:'widgetname',title:localStorage.getItem('catalogwidgetname'),width:'150px'},
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
					title:localStorage.getItem('catalogmenuaccess'),
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
							url:'".$this->createUrl('menuaccess/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'menuaccessid',title:localStorage.getItem('catalogmenuaccessid'),width:'50px'},
								{field:'menuname',title:localStorage.getItem('catalogmenuname'),width:'150px'},
							]]
						}	
					},
					width:'200px',
					sortable: true,
					formatter: function(value,row,index){
						return row.menuname;
				}},
			"
		)
	)
));