<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserSearch_yiitest */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-yiitest-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'password') ?>

    <?= $form->field($model, 'role') ?>

    <?= $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'first_name') ?>

    <?php // echo $form->field($model, 'last_name') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'update_time') ?>

    <?php // echo $form->field($model, 'reset_token') ?>

    <?php // echo $form->field($model, 'activation_key') ?>

    <?php // echo $form->field($model, 'validation_key') ?>

    <?php // echo $form->field($model, 'active_date') ?>

    <?php // echo $form->field($model, 'login_ip') ?>

    <?php // echo $form->field($model, 'login_attemp') ?>

    <?php // echo $form->field($model, 'last_login') ?>

    <?php // echo $form->field($model, 'active') ?>

    <?php // echo $form->field($model, 'gallery_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
