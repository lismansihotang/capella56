<div style="padding:10px 30px 20px 30px">
<form id="UserProfile" class="easyui-form" method="post" data-options="novalidate:true">
	<table cellpadding="5">
		<tr>
			<td style="padding:5px">Nama User</td>
			<td><input aria-label="username" class="easyui-textbox" type="text" name="username" data-options="required:true" value="<?php echo Yii::app()->user->username?>"></input></td>
		</tr>
		<tr>
			<td style="padding:5px">Nama Asli</td>
			<td><input aria-label="realname" class="easyui-textbox" type="text" name="realname" data-options="required:true" value="<?php echo Yii::app()->user->realname?>" style='width:200px'></input></td>
		</tr>
		<tr>
			<td style="padding:5px">Email</td>
			<td><input aria-label="email" class="easyui-textbox" type="text" name="email" data-options="required:true" value="<?php echo Yii::app()->user->email?>" style='width:300px'></input></td>
		</tr>
		<tr>
			<td style="padding:5px">No Telp</td>
			<td><input aria-label="phoneno" class="easyui-textbox" type="text" name="phoneno" data-options="required:true" value="<?php echo Yii::app()->user->phoneno?>"></input></td>
		</tr>
		<tr>
			<td style="padding:5px">Password</td>
			<td><input aria-label="password" class="easyui-textbox" type="text" name="password" data-options="required:true" value="<?php echo Yii::app()->user->password?>" style='width:300px'></input></td>
		</tr>
		<tr>
			<td style="padding:5px"><a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitProfile()">Submit</a></td>
			<td></td>
		</tr>
	</table>
</form>
</div>
<script>
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