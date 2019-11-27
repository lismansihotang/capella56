<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'menuauthid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('admin/menuauth/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('admin/menuauth/getData'),
	'saveurl'=>Yii::app()->createUrl('admin/menuauth/save'),
	'updateurl'=>Yii::app()->createUrl('admin/menuauth/save'),
	'destroyurl'=>Yii::app()->createUrl('admin/menuauth/purge'),
	'uploadurl'=>Yii::app()->createUrl('admin/menuauth/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/menuauth/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/menuauth/downxls'),
	'rowstyler'=>"
		if (row.jumlah == 0) {
			return 'background-color:red;color:#fff;';
		}  
	",
	'columns'=>"
		{
			field:'menuauthid',
			title:'".GetCatalog('menuauthid')."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'menuobject',
			title:'".GetCatalog('menuobject')."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus')."',
			align:'center',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
				} else {
					return '';
				}
			}},",
	'searchfield'=> array ('menuauthid','menuobject','groupname','menuvalueid'),
	'loadsuccess' => "
		$('#menuauth-menuobject').textbox('setValue',data.menuobject);
		if (data.recordstatus == 1)
		{
			$('#menuauth-recordstatus').prop('checked', true);
		} else
		{
			$('#menuauth-recordstatus').prop('checked', false);
		}
	",
	'headerform'=>"
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('menuobject')."</td>
				<td><input class='easyui-textbox' id='menuauth-menuobject' name='menuauth-menuobject' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td>". GetCatalog('recordstatus')."</td>
				<td><input id='menuauth-recordstatus' name='menuauth-recordstatus' type='checkbox'></input></td>
			</tr>
		</table>
	",
	'columndetails'=> array(
		array(
			'id'=>'groupmenuauth',
			'idfield'=>'groupmenuauthid',
			'urlsub'=>Yii::app()->createUrl('admin/menuauth/indexgroupmenuauth',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('admin/menuauth/indexgroupmenuauth',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('admin/menuauth/savegroupmenuauth'),
			'updateurl'=>Yii::app()->createUrl('admin/menuauth/savegroupmenuauth'),
			'destroyurl'=>Yii::app()->createUrl('admin/menuauth/purgegroupmenuauth'),
			'subs'=>"
				{field:'groupname',title:'".GetCatalog('groupname')."',width:'200px'},
				{field:'menuvalueid',title:'".GetCatalog('menuvalueid')."',width:'200px'},
			",
			'searchfield'=> array ('groupname','menuvalueid'),
			'columns'=>"
				{
					field:'groupmenuauthid',
					title:'".GetCatalog('groupmenuauthid')."',
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'menuauthid',
					title:'".GetCatalog('menuauthid')."',
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
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
							required:true,
							textField:'groupname',
							url:'".$this->createUrl('groupaccess/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'groupaccessid',title:'".GetCatalog('groupaccessid')."',width:'50px'},
								{field:'groupname',title:'".GetCatalog('groupname')."',width:'200px'},
							]]
						}	
					},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
						return row.groupname;
				}},
				{
					field:'menuvalueid',
					title:'".GetCatalog('menuvalueid') ."',
					editor:{
						type:'validatebox',
						options:{
							required:true
						}
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
					return value;
				}}
			"
		)
	)
));
