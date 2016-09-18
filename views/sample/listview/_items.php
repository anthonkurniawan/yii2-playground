<?php use yii\helpers\Html; ?>

<?php //print_r($model); ?>

<div class="well well-sm">
	<b><?php echo Html::encode($model->getAttributeLabel('id')); ?>:</b>
	<?php echo Html::a(Html::encode($model->id), ['view', 'id' => $model->id]); ?>
	<br />
    
    <b><?php echo Html::encode($model->getAttributeLabel('username')); ?>:</b>
	<span class="text-success"><?php echo Html::encode($model->username); ?></span>
	<br />
    
    <b><?php echo Html::encode($model->getAttributeLabel('email')); ?>:</b>
	<span class="text-success"><?php echo Html::encode($model->email); ?></span>
	<br />
    
    
 </div>