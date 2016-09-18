<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User_yiitest */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-yiitest-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'role')->dropDownList([ 'administrator' => 'Administrator', 'poweruser' => 'Poweruser', 'user-a' => 'User-a', 'user-b' => 'User-b', ], ['prompt' => '']) ?>

    <?php echo $form->field($model, 'email')->textInput(['maxlength' => 50]) ?>

    <?php echo $form->field($model, 'first_name')->textInput(['maxlength' => 50]) ?>

    <?php echo $form->field($model, 'last_name')->textInput(['maxlength' => 30]) ?>

    <?php echo $form->field($model, 'create_time')->textInput() ?>

    <?php echo $form->field($model, 'update_time')->textInput() ?>

    <?php echo $form->field($model, 'reset_token')->textInput(['maxlength' => 100]) ?>

    <?php echo $form->field($model, 'activation_key')->textInput(['maxlength' => 100]) ?>

    <?php echo $form->field($model, 'validation_key')->textInput(['maxlength' => 255]) ?>

    <?php echo $form->field($model, 'active_date')->textInput() ?>

    <?php echo $form->field($model, 'login_ip')->textInput(['maxlength' => 32]) ?>

    <?php echo $form->field($model, 'login_attemp')->textInput() ?>

    <?php echo $form->field($model, 'last_login')->textInput() ?>

    <?php echo $form->field($model, 'active')->textInput() ?>

    <?php echo $form->field($model, 'gallery_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
$('#w0').trigger('reset')
$('#w0').trigger('reset.yiiActiveForm')

$('#w0').trigger('submit')
$('#w0').trigger('submit.yiiActiveForm')

$('#w0').yiiActiveForm('validate');

$('#w0').data('yiiActiveForm').attributes

$('#w0').data('yiiActiveForm').attributes.push(attribute);

$('#w0').yiiActiveForm('add',{
			"id" : "user_yiitest-username2",
			"name" : "username2",
			"container" : ".field-user_yiitest-username2",
			"input" : "#user_yiitest-username2",validate: undefined,})
            
 $('#w0').add({
			"id" : "user_yiitest-username2",
			"name" : "username2",
			"container" : ".field-user_yiitest-username2",
			"input" : "#user_yiitest-username2",validate: undefined,})
            