<?php $model = Yii::app()->db->createCommand("select `username`,`password`,realname,email,phoneno from useraccess where lower(username) = lower('".Yii::app()->user->name."')")->queryRow();?>
<div style="padding:10px 30px 20px 30px">
<form id="UserProfile" class="easyui-form" method="post" data-options="novalidate:true">
	<table cellpadding="5">
		<tr>
			<td id='userprofile-username' style="padding:5px"></td>
			<td><input class="easyui-textbox" type="text" name="username" data-options="required:true" value="<?php echo $model['username']?>"></input></td>
		</tr>
		<tr>
			<td id='userprofile-realname' style="padding:5px"></td>
			<td><input class="easyui-textbox" type="text" name="realname" data-options="required:true" value="<?php echo $model['realname']?>" style='width:200px'></input></td>
		</tr>
		<tr>
			<td id='userprofile-email' style="padding:5px"></td>
			<td><input class="easyui-textbox" type="text" name="email" data-options="required:true" value="<?php echo $model['email']?>" style='width:300px'></input></td>
		</tr>
		<tr>
			<td id='userprofile-phoneno' style="padding:5px"></td>
			<td><input class="easyui-textbox" type="text" name="phoneno" data-options="required:true" value="<?php echo $model['phoneno']?>"></input></td>
		</tr>
		<tr>
			<td id='userprofile-password' style="padding:5px"></td>
			<td><input class="easyui-textbox" type="text" name="password" data-options="required:true" value="<?php echo $model['password']?>" style='width:300px'></input></td>
		</tr>
		<tr>
			<td style="padding:5px"><a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitProfile()">Submit</a></td>
			<td></td>
		</tr>
	</table>
</form>
</div>
<script>
	$(document).ready(function(){
		var parent=document.getElementById('userprofile-username');
		parent.innerHTML = localStorage.getItem("catalogusername");
		parent=document.getElementById('userprofile-realname');
		parent.innerHTML = localStorage.getItem("catalogrealname");
		parent=document.getElementById('userprofile-realname');
		parent.innerHTML = localStorage.getItem("catalogrealname");
		parent=document.getElementById('userprofile-email');
		parent.innerHTML = localStorage.getItem("catalogemail");
		parent=document.getElementById('userprofile-phoneno');
		parent.innerHTML = localStorage.getItem("catalogphoneno");
		parent=document.getElementById('userprofile-password');
		parent.innerHTML = localStorage.getItem("catalogpassword");
	});
	function submitProfile(){
		$('#UserProfile').form('submit',{
	url:'<?php echo Yii::app()->createUrl('site/saveprofile') ?>',
	onSubmit:function(){
			return $(this).form('enableValidation').form('validate');
	},
	success:function(data){
		var data = eval('(' + data + ')');  // change the JSON string to javascript object
		show('Pesan',data.message)
	}
});
	}
</script>