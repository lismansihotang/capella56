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
	'downdoc'=>Yii::app()->createUrl('admin/useraccess/downdoc'),
	'rowstyler'=>"
		if (row.jumlah == 0) {
			return 'background-color:red;color:#fff;';
		}  
	",
	'columns'=>"
		{
			field:'useraccessid',
			title:localStorage.getItem('cataloguseraccessid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'username',
			title:localStorage.getItem('catalogusername'),
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'realname',
			title:localStorage.getItem('catalogrealname'),
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'password',
			title:localStorage.getItem('catalogpassword'),
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'email',
			title:localStorage.getItem('catalogemail'),
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'phoneno',
			title:localStorage.getItem('catalogphoneno'),
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'languageid',
			title:localStorage.getItem('cataloglanguage'),
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.languagename;
		}},
		{
			field:'themeid',
			title:localStorage.getItem('catalogtheme'),
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return row.themename;
		}},
		{
			field:'recordstatus',
			title:localStorage.getItem('catalogrecordstatus'),
			align:'center',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == '1'){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
			}},",
	'searchfield'=> array ('useraccessid','username','realname','email','phoneno','languagename','themename','groupname'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td id='useraccesstext-username'></td>
				<td><input class='easyui-textbox' id='useraccess-username' name='useraccess-username' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td id='useraccesstext-realname'></td>
				<td><input class='easyui-textbox' id='useraccess-realname' name='useraccess-realname' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td id='useraccesstext-password'></td>
				<td><input class='easyui-textbox' id='useraccess-password' name='useraccess-password' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>			
			<tr>
				<td id='useraccesstext-email'></td>
				<td><input class='easyui-textbox' id='useraccess-email' name='useraccess-email' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td id='useraccesstext-phoneno'></td>
				<td><input class='easyui-textbox' id='useraccess-phoneno' name='useraccess-phoneno' data-options=\"width:'300px'\"></input></td>
			</tr>
			<tr>
				<td id='useraccesstext-language'></td>
				<td><select class='easyui-combogrid' id='useraccess-languageid' name='useraccess-languageid' style='width:300px' data-options=\"
							panelWidth: '500px',
							idField: 'languageid',
							textField: 'languagename',
							required:true,
							url: '". Yii::app()->createUrl('admin/language/index',array('grid'=>true,'combo'=>true)) ."',
							method: 'get',
							mode: 'remote',
							columns: [[
								{field:'languageid',title:localStorage.getItem('cataloglanguageid'),width:'80px'},
								{field:'languagename',title:localStorage.getItem('cataloglanguagename'),width:'150px'},
							]],
							fitColumns: true
					\">
			</select></td>
			</tr>
			<tr>
				<td id='useraccesstext-theme'></td>
				<td><select class='easyui-combogrid' id='useraccess-themeid' name='useraccess-themeid' style='width:300px' data-options=\"
							panelWidth: '500px',
							idField: 'themeid',
							textField: 'themename',
							required:true,
							url: '". Yii::app()->createUrl('admin/theme/index',array('grid'=>true,'combo'=>true)) ."',
							method: 'get',
							mode: 'remote',
							columns: [[
									{field:'themeid',title:localStorage.getItem('catalogthemeid'),width:'80px'},
									{field:'themename',title:localStorage.getItem('catalogthemename'),width:'150px'},
							]],
							fitColumns: true
					\">
			</select></td>
			</tr>
			<tr>
				<td id='useraccesstext-recordstatus'></td>
				<td><input id='useraccess-recordstatus' name='useraccess-recordstatus' type='checkbox'></input></td>
			</tr>
		</table>
	",
	'addonscripts' => "
	$(document).ready(function(){
		var parel = document.getElementById('useraccesstext-username');
		parel.innerHTML = localStorage.getItem('catalogusername');
		parel = document.getElementById('useraccesstext-email');
		parel.innerHTML = localStorage.getItem('catalogemail');
		parel = document.getElementById('useraccesstext-realname');
		parel.innerHTML = localStorage.getItem('catalogrealname');
		parel = document.getElementById('useraccesstext-password');
		parel.innerHTML = localStorage.getItem('catalogpassword');
		parel = document.getElementById('useraccesstext-theme');
		parel.innerHTML = localStorage.getItem('catalogtheme');
		parel = document.getElementById('useraccesstext-phoneno');
		parel.innerHTML = localStorage.getItem('catalogphoneno');
		parel = document.getElementById('useraccesstext-language');
		parel.innerHTML = localStorage.getItem('cataloglanguage');
	});
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
				{field:'groupname',title:localStorage.getItem('cataloggroupname'),width:'200px'},
			",
			'columns'=>"
				{
					field:'usergroupid',
					title:localStorage.getItem('catalogusergroupid'),
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'useraccessid',
					title:localStorage.getItem('cataloguseraccessid'),
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
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
							required:true,
							textField:'groupname',
							url:'".$this->createUrl('groupaccess/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'groupaccessid',title:localStorage.getItem('cataloggroupaccessid'),width:'50px'},
								{field:'groupname',title:localStorage.getItem('cataloggroupname'),width:'200px'},
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
				{field:'menuname',title:localStorage.getItem('catalogmenuname'),width:'350px'},
			",
			'columns'=>"
				{
					field:'userfavid',
					title:localStorage.getItem('cataloguserfavid'),
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'useraccessid',
					title:localStorage.getItem('cataloguseraccessid'),
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
								panelWidth:'450px',
								mode : 'remote',
								method:'get',
								idField:'menuaccessid',
								textField:'menuname',
								pagination:true,
								required:true,
								url:'".$this->createUrl('menuaccess/index',array('grid'=>true))."',
								fitColumns:true,
								loadMsg: localStorage.getItem('catalogpleasewait'),
								columns:[[
									{field:'menuaccessid',title:localStorage.getItem('catalogmenuaccessid'),width:'80px'},
									{field:'menuname',title:localStorage.getItem('catalogmenuname'),width:'350px'},
								]]
						}	
					},
					width:'350px',
					sortable: true,
					formatter: function(value,row,index){
						return row.menuname;
				}}
			"
		)
	)
));