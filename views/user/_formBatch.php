<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
.well, well-sm{ padding: 0px}
.label-id{
    width: 50px;
    border-bottom: 1px solid #e3e3e3;
    border-right: 1px solid #e3e3e3;
    border-bottom-right-radius: 5px;
    padding: 5px;
    background-color: mintcream; /*honeydew*/
}
</style>

<div class="users-form form-group-sm">
<?php 
    $form = ActiveForm::begin([
    'id'=>'form-user', 
    //'enableClientValidation' =>false, 
    'layout' => 'horizontal',
    'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            // 'horizontalCssClasses' => [
                // 'label' => 'col-sm-3',
                // 'offset' => 'col-sm-offset-3',
                // 'wrapper' => 'col-sm-8',
                // 'error' => '',
                // 'hint' => '',
            // ],
        ],
    ]); ?>
    
    <?php foreach($models as $i=>$model) : ?>
    <div class="well well-sm">
    <div class="label-id pull-left"><b>#<?=$model->id?></b></div>
    <div class="pull-right" style="width:90%; margin: 10px 20px;">
    <?= $form->field($model, "[$i]username")->textInput(['maxlength' => 255]) ?>
    <?php //echo $form->field($model, "[$i]username")->label($model->username); ?>

    <?php if($model->isNewRecord) echo $form->field($model, "[$i]password")->passwordInput(); ?>

    <?= $form->field($model, "[$i]email")->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, "[$i]status")->dropDownList($model->getStatusList(), []) ?>
    
    <?= $form->field($model, "[$i]groups")->dropDownList($model->getGroupList(), []) ?>

    <?php echo $form->field($profiles[$i], "[$i]firstname")->textInput(["maxlength" => 255]) ?>
    
    <?php echo  $form->field($profiles[$i], "lastname")->textInput(["maxlength" => 255]) ?>
    </div>
    <div class="clearfix"></div>
    </div>
    <?php endForeach; ?>
 

    <?php //Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['onclick'=>"updateBatch(1);", 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?php //Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['onclick'=>"updateBatch(1);", 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    
    <?php if($model->isNewRecord) : ?> 
        <?=Html::submitButton(Yii::t('app', 'Create'), ['class' =>'btn btn-success', 'onclick'=>"addBatchUser('create',1);"]) ?>
        <?=Html::submitButton(Yii::t('app', 'Add'), ['class' =>'btn btn-primary', 'onclick'=>"addBatchUser('create');"]) ?>
    <?php else : ?>
        <?=Html::submitButton(Yii::t('app', 'Update'), ['class' =>'btn btn-success', 'onclick'=>"addBatchUser('update', 1);"]) ?>
    <?php endif; ?>

<?php ActiveForm::end(); ?>
</div>
