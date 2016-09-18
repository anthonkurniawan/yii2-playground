<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var mdm\admin\models\AuthItem $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div>

    <?php $form = ActiveForm::begin(['options'=>['id'=>'form-auth', 'class'=>'form-group-sm']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'className')->textInput() ?>

    <div class="modal-footer">
        <div class="form-group">
            <?php //echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['onclick'=>'submitAuth(event);', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?php 
                if($model->isNewRecord)
                    echo Html::submitButton(Yii::t('app', 'Create'), ['onclick'=>"submitAuth('./authTest/auth/create/Rule', '#auth-list');", 'class' => 'btn btn-success']);
                else
                    echo Html::submitButton(Yii::t('app', 'Update'), ['onclick'=>"submitAuth('./authTest/auth/update/$model->name/Rule', '#auth-list');", 'class' => 'btn btn-primary']);
            ?>
        </div>
    </div> 
</div>
<?php ActiveForm::end(); ?>
