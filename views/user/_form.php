<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form form-group-sm">

    <?php $form = ActiveForm::begin(['id'=>'form-user']); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>

    <?php if($model->isNewRecord) echo $form->field($model, 'password')->passwordInput(); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatusList(), []) ?>
    
    <?= $form->field($model, 'groups')->dropDownList($model->getGroupList(), []) ?>

    <?= $form->field($profile, 'firstname')->textInput(['maxlength' => 255]) ?>
    
    <?= $form->field($profile, 'lastname')->textInput(['maxlength' => 255]) ?>
    
    <div class="form-group">
        <?=Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['onclick'=>"createUser();", 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php if($model->isNewRecord) echo Html::button(Yii::t('app', 'Add'), ['class' =>'btn btn-primary', 'onclick'=>"addBatchUser('create');"]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
