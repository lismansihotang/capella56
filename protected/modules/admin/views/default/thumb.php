<?php $form = $this->beginWidget(
    'booster.widgets.TbActiveForm',
    array(
        'id' => $data->menuname.'Form',
    )
);?>
<?php echo CHtml::HiddenField('id', $data->menuname); ?>
<div class="col-sm-8 col-md-4">
    <div class="thumbnail">
        <a href="<?php echo Yii::app()->createUrl($data->menuurl);?>"><img style="width:64px;height:64px" src="<?php echo Yii::app()->baseUrl.'/images/'.$data->menuicon;?>" alt="<?php echo $data->menuname;?>"></a>
        <div class="caption">
            <p><?php echo GetCatalog($data->menuname) ?></p>	
<?php $this->endWidget();
unset($form);
?>		
        </div>
    </div>
</div>