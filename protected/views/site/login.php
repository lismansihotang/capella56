<div class="limiter">
	<div class="container-login100" style="background-image: url('<?php echo Yii::app()->baseUrl.'/images/wallpaper/bg-01.jpg'?>');">
		<div class="wrap-login100">
		<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'login-form',
				'enableClientValidation'=>true,
				'clientOptions'=>array(
						'validateOnSubmit'=>true,
						'class'=>"login100-form validate-form"
				),
		)); ?>
		<span class="login100-form-logo">
					<img alt="Capella ERP Indonesia" src="<?php echo Yii::app()->baseUrl.'/images/logo.jpg';?>"></img>
				</span>
			<div class="wrap-input100 validate-input">
				<?php echo $form->textField($model,'username',array('class'=>'input100','placeholder'=>"Username",'aria-label'=>'User Name')); ?>
				<?php echo $form->error($model,'username'); ?>
			</div>
			<div class="wrap-input100 validate-input" data-validate = "Password is required">
				<?php echo $form->passwordField($model,'password',array('class'=>"input100",'placeholder'=>"Password",'aria-label'=>'Password')); ?>
				<?php echo $form->error($model,'password'); ?>
			</div>
			<div class="container-login100-form-btn">
				<button class="login100-form-btn">
					Login
				</button>
			</div>
		<?php $this->endWidget(); ?>
		</div>
	</div>
</div>