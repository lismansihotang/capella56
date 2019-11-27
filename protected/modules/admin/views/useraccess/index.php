<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'useraccessid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('admin/useraccess/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('admin/useraccess/getData'),
	'saveurl'=>Yii::app()->createUrl('admin/useraccess/save'),
	'updateurl'=>Yii::app()->createUrl('admin/useraccess/save'),
	'destroyurl'=>Yii::app()->createUrl('admin/useraccess/purge'),
	'uploadurl'=>Yii::app()->createUrl('admin/useraccess/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/useraccess/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/useraccess/downxls'),
	'rowstyler'=>"
		if (row.jumlah == 0) {
			return 'background-color:red;color:#fff;';
		}  
	",
	'columns'=>"
		{
			field:'useraccessid',
			title:'".GetCatalog('useraccessid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'username',
			title:'".GetCatalog('username') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'realname',
			title:'".GetCatalog('realname') ."',
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'password',
			title:'".GetCatalog('password') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'email',
			title:'".GetCatalog('email') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'phoneno',
			title:'".GetCatalog('phoneno') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'languageid',
			title:'".GetCatalog('language') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.languagename;
		}},
		{
			field:'themeid',
			title:'".GetCatalog('theme') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return row.themename;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus') ."',
			align:'center',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == '1'){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
				} else {
					return '';
				}
			}},",
	'searchfield'=> array ('useraccessid','username','realname','email','phoneno','languagename','themename','groupname'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('username')."</td>
				<td><input class='easyui-textbox' id='useraccess-username' name='useraccess-username' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('realname')."</td>
				<td><input class='easyui-textbox' id='useraccess-realname' name='useraccess-realname' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('password')."</td>
				<td><input class='easyui-passwordbox' id='useraccess-password' name='useraccess-password' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>			
			<tr>
				<td>".GetCatalog('email')."</td>
				<td><input class='easyui-textbox' id='useraccess-email' name='useraccess-email' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('phoneno')."</td>
				<td><input class='easyui-textbox' id='useraccess-phoneno' name='useraccess-phoneno' data-options=\"width:'300px'\"></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('language')."</td>
				<td><select class='easyui-combogrid' id='useraccess-languageid' name='useraccess-languageid' style='width:300px' data-options=\"
							panelWidth: '500px',
							idField: 'languageid',
							textField: 'languagename',
							required:true,
							url: '". Yii::app()->createUrl('admin/language/index',array('grid'=>true,'combo'=>true)) ."',
							method: 'get',
							mode: 'remote',
							columns: [[
									{field:'languageid',title:'". GetCatalog('languageid') ."',width:'80px'},
									{field:'languagename',title:'". GetCatalog('languagename') ."',width:'150px'},
							]],
							fitColumns: true
					\">
			</select></td>
			</tr>
			<tr>
				<td>".GetCatalog('theme')."</td>
				<td><select class='easyui-combogrid' id='useraccess-themeid' name='useraccess-themeid' style='width:300px' data-options=\"
							panelWidth: '500px',
							idField: 'themeid',
							textField: 'themename',
							required:true,
							url: '". Yii::app()->createUrl('admin/theme/index',array('grid'=>true,'combo'=>true)) ."',
							method: 'get',
							mode: 'remote',
							columns: [[
									{field:'themeid',title:'". GetCatalog('themeid') ."',width:'80px'},
									{field:'themename',title:'". GetCatalog('themename') ."',width:'150px'},
							]],
							fitColumns: true
					\">
			</select></td>
			</tr>
			<tr>
				<td>". GetCatalog('recordstatus')."</td>
				<td><input id='useraccess-recordstatus' name='useraccess-recordstatus' type='checkbox'></input></td>
			</tr>
		</table>
	",
	'loadsuccess' => "
		$('#useraccess-username').textbox('setValue',data.username);
		$('#useraccess-realname').textbox('setValue',data.realname);
		$('#useraccess-password').textbox('setValue',data.password);
		$('#useraccess-email').textbox('setValue',data.email);
		$('#useraccess-phoneno').textbox('setValue',data.phoneno);
		$('#useraccess-themeid').textbox('setValue',data.themeid);
		$('#useraccess-languageid').textbox('setValue',data.languageid);
		if (data.recordstatus == 1)
		{
			$('#useraccess-recordstatus').prop('checked', true);
		} else
		{
			$('#useraccess-recordstatus').prop('checked', false);
		}
	",
	'columndetails'=> array (
		array(
			'id'=>'usergroup',
			'idfield'=>'usergroupid',
			'urlsub'=>Yii::app()->createUrl('admin/useraccess/indexusergroup',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('admin/useraccess/indexusergroup',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('admin/useraccess/saveusergroup'),
			'updateurl'=>Yii::app()->createUrl('admin/useraccess/saveusergroup'),
			'destroyurl'=>Yii::app()->createUrl('admin/useraccess/purgeusergroup'),
			'subs'=>"
				{field:'groupname',title:'".GetCatalog('groupname')."',width:'200px'},
			",
			'columns'=>"
				{
					field:'usergroupid',
					title:'".GetCatalog('usergroupid')."',
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'useraccessid',
					title:'".GetCatalog('useraccessid')."',
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
				}}
			"
		),
		array(
			'id'=>'userfav',
			'idfield'=>'userfavid',
			'urlsub'=>Yii::app()->createUrl('admin/useraccess/indexuserfav',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('admin/useraccess/indexuserfav',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('admin/useraccess/saveuserfav'),
			'updateurl'=>Yii::app()->createUrl('admin/useraccess/saveuserfav'),
			'destroyurl'=>Yii::app()->createUrl('admin/useraccess/purgeuserfav'),
			'subs'=>"
				{field:'menuname',title:'".GetCatalog('menuname')."',width:'200px'},
			",
			'columns'=>"
				{
					field:'userfavid',
					title:'".GetCatalog('userfavid')."',
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'useraccessid',
					title:'".GetCatalog('useraccessid')."',
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'menuaccessid',
					title:'".GetCatalog('menuaccess')."',
					editor:{
						type:'combogrid',
						options:{
								panelWidth:450,
								mode : 'remote',
								method:'get',
								idField:'menuaccessid',
								textField:'menuname',
								pagination:true,
								required:true,
								url:'".$this->createUrl('menuaccess/index',array('grid'=>true,'combo'=>true))."',
								fitColumns:true,
								loadMsg: '".GetCatalog('pleasewait')."',
								columns:[[
									{field:'menuaccessid',title:'".GetCatalog('menuaccessid')."',width:'50px'},
									{field:'menuname',title:'".GetCatalog('menuname')."',width:'200px'},
								]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return row.menuname;
				}},
			"
		)
	)
));
