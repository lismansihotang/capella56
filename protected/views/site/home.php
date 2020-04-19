<div class="easyui-tabs" style="width:100%;height:100%">
	<div title="<?php echo getcatalog('userprofile')?>" style="padding:10px">
	<?php $model = Yii::app()->db->createCommand("select `username`,`password`,realname,email,phoneno,themeid from useraccess where lower(username) = lower('".Yii::app()->user->name."')")->queryRow();?>
<div id="userprofile" class="easyui-panel" border="false" style="width:950px;height:450px;padding:10px;">
	<div class="easyui-layout" fit="true">
		<div data-options="region:'east'" border="false" style="width:500px;">
			<?php if (file_exists('images/useraccess/'.$model['username'].'.jpg')) { ?>
				<img alt="User Access Photo" src="<?php echo Yii::app()->baseUrl.'/images/useraccess/'.$model['username'].'.jpg'?>"/>
			<?php } else { ?>
			<img alt="User Access Photo" src="<?php echo Yii::app()->baseUrl.'/images/useraccess/man.jpg'?>"/>
			<?php } ?>
		</div>
		<div data-options="region:'center'" border="false">
			<form id="UserProfile" class="easyui-form" method="post" data-options="novalidate:true">
				<table cellpadding="5">
					<tr>
						<td style="padding:5px"><?php echo GetCatalog('username')?></td>
						<td><input aria-label="User Name" class="easyui-textbox" type="text" name="username" data-options="required:true" value="<?php echo $model['username']?>"></input></td>
					</tr>
					<tr>
						<td style="padding:5px"><?php echo GetCatalog('realname')?></td>
						<td><input aria-label="Real Name" class="easyui-textbox" type="text" name="realname" data-options="required:true" value="<?php echo $model['realname']?>" style='width:200px'></input></td>
					</tr>
					<tr>
						<td style="padding:5px"><?php echo GetCatalog('email')?></td>
						<td><input aria-label="Email" class="easyui-textbox" type="text" name="email" data-options="required:true" value="<?php echo $model['email']?>" style='width:300px'></input></td>
					</tr>
					<tr>
						<td style="padding:5px"><?php echo GetCatalog('phoneno')?></td>
						<td><input aria-label="Phone No" class="easyui-textbox" type="text" name="phoneno" data-options="required:true" value="<?php echo $model['phoneno']?>"></input></td>
					</tr>
					<tr>
						<td style="padding:5px"><?php echo GetCatalog('password')?></td>
						<td><input aria-label="Password" class="easyui-textbox" type="text" name="password" data-options="required:true" value="<?php echo $model['password']?>" style='width:300px'></input></td>
					</tr>
					<tr>
						<td style="padding:5px"><?php echo GetCatalog('theme')?></td>
						<td><select aria-label="Theme" class='easyui-combogrid' id='themeid' name='themeid' style='width:300px' data-options="
								panelWidth: '500px',
								idField: 'themeid',
								required: true,
								value:'<?php echo Yii::app()->user->themeid?>',
								textField: 'themename',
								mode: 'remote',
								url: '<?php echo Yii::app()->createUrl('admin/theme/index',array('grid'=>true,'combo'=>true))?>',
								method: 'get',
								columns: [[
										{field:'themeid',title:'<?php echo getCatalog('themeid')?>',width:'50px'},
										{field:'themename',title:'<?php echo getCatalog('themename')?>',width:'120px'},
										{field:'description',title:'<?php echo getCatalog('description')?>',width:'250px'},
								]],
								fitColumns: true
						">
				</select></td>
					</tr>
					<tr>
						<td style="padding:5px"><?php echo GetCatalog('language')?></td>
						<td><select aria-label="Language" class='easyui-combogrid' id='languageid' name='languageid' style='width:300px' data-options="
								panelWidth: '200px',
								idField: 'languageid',
								required: true,
								textField: 'languagename',
								value:'<?php echo Yii::app()->user->languageid?>',
								mode: 'remote',
								url: '<?php echo Yii::app()->createUrl('admin/language/index',array('grid'=>true,'combo'=>true))?>',
								method: 'get',
								columns: [[
										{field:'languageid',title:'<?php echo getCatalog('languageid')?>',width:'50px'},
										{field:'languagename',title:'<?php echo getCatalog('languagename')?>',width:'200px'},
								]],
								fitColumns: true
						">
				</select></td>
					</tr>
					<tr>
						<td style="padding:5px"><a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitProfile()">Submit</a></td>
						<td></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
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
				show('Pesan',data.msg);
				minirefresh();
			}
		});
	}
</script>
	</div>
</div>