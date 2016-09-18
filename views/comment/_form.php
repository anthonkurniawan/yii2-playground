<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Comment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comment-form" id="comment">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'code_id')->hiddenInput() ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 3]) ?>

    <?php //echo $form->field($model, 'status')->textInput() ?>

    <?php //echo $form->field($model, 'create_time')->textInput() ?>

    <?php //echo $form->field($model, 'author')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
