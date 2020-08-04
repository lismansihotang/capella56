<div class="panel panel-default">
  <div class="panel-heading">
		<button type="button" class="btn btn-info" data-toggle="modal" data-target="#Modal<?php echo $data['themeid']?>">Uninstall</button>
		<?php echo CHtml::encode($data['themename']); ?>
	</div>
	<div class="panel-body">
    <p>Description :<?php echo CHtml::encode($data['description']); ?></p>
    <p>Is Admin Theme? :<?php /*
			if ($data['isadmin']) { 
				echo "<label class='label label-info'>Yes</label>" ;
			} else { 
				echo "<label class='label label-info'>No</label>" ;
			} */ ?></p> 
	<p>Created By :</h4><?php// echo CHtml::encode($data['createdby']); ?></p>
    <p>Version :</h4><?php// echo CHtml::encode($data['themeversion']); ?></p>
    <p>Installed Date :</h4><?php //echo CHtml::encode($data['installdate']); ?></p>
		<form action="<?php echo Yii::app()->createUrl('admin/theme/activate') ?>" 
			method="post">
			<input type="hidden" class="btn btn-info" name="themeid" value="<?php echo $data['themeid'] ?>">
			<?php if ($data['recordstatus'] == 1) { ?>
			<input type="submit" class="btn btn-info" name="status" value="Active">
			<?php } ?>
			<?php if ($data['recordstatus'] == 0) { ?>
			<input type="submit" class="btn btn-info" name="status" value="Not Active">
			<?php } ?>
		</form>
	</div>
</div>

<!-- Modal -->
<div id="Modal<?php echo $data['themeid']?>" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Theme Editor</h4>
      </div>
			<form action="<?php echo Yii::app()->createUrl('admin/theme/uninstall')?>" method="post">
      <div class="modal-body">
				<input type="hidden" name="theme" value="<?php echo $data['themeid'] ?>">				
        <p>Are You Sure to Uninstall this theme ?</p>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary"></input>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
			</form>
    </div>
  </div>
</div>