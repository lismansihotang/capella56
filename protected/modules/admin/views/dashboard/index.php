<div class="easyui-tabs" id="regtabdashboard" style="width:100%;height:100%">
<?php $i; foreach($datas as $data) {
	$i = explode('.',$data['widgeturl']);
	if ($data['widgetname'] != 'userprofile') {
	?>
	<div title="<?php echo $data['widgettitle']?>" style="padding:10px">	
		<?php echo $this->renderPartial($data['widgetname']);?>		
	</div>	
	<?php }}?>
</div>