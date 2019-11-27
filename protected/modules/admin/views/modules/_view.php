<script>
function Uninstall(elmnt)
{
jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('admin/modules/uninstall')?>',
		'data':{'module':elmnt},
		'type':'post','dataType':'json',
		'success':function(data) {
			if (data.status == "success")
			{
				toastr.info(data.div);
				location.reload();
			}
			else
			{
				toastr.error(data.div);
			}
		},
		'cache':false});
}
</script>

<div class="panel panel-default">
  <div class="panel-heading">
		<button type="button" class="btn btn-info" data-toggle="modal" data-target="#Modal<?php echo $data['moduleid']?>">Uninstall</button>
		<?php echo CHtml::encode($data['modulename']); ?>
	</div>
	<div class="panel-body">
    <p>Description :<?php echo CHtml::encode($data['moduledesc']); ?></p>
    <p>Dependency Module :</h4><?php// echo CHtml::encode($this->getModuleRelation($data['moduleid'])); ?></p>		
  </div>
</div>

<!-- Modal -->
<div id="Modal<?php echo $data['moduleid']?>" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
				<input type="hidden" name="module" value="<?php echo $data['moduleid'] ?>">				
        <p>Are You Sure to Uninstall this module ?</p>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" onclick="Uninstall(<?php echo $data['moduleid'] ?>)"></input>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>