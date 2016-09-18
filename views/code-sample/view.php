<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CodeSample */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Code Samples'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>      
<div class="code-sample-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            //'class_id',
            [
                'label'=>'Classname',
                'value'=> $classname = $model->class->classname,
            ],
            'title',
            'content:ntext',
            'tags',
            'create_by',
            'update_by',
            'create_time:datetime',
            'update_time:datetime',
            [
                'label'=>'Comment count',
                //'value'=>app\models\Comment::jumlah($model->id),  //$model->comments->id, //getCount(3),  // VIA MODEL
                 'value'=> $commentCount = $model->getCommentCount($model->id),  // VIA CONTROLLER
                //'format'=>'raw',
            ],
        ],
    ]) ?>
</div>
                                       
<div id="comments">
	<?php if($commentCount >=1): ?>
		<h3><?php echo $commentCount >1 ? $commentCount . ' comments' : 'One comment'; ?></h3>
		<?= $this->render('_comments', ['post'=>$model,'comments'=>$model->comments]); ?>
	<?php endif; ?>

	
	<h3>Leave a Comment</h3>
    <?php if(Yii::$app->session->hasFlash('msg')) : ?>
        <div class="alert alert-success" style='margin-bottom:10px'>
            <a data-dismiss="alert" href="#" class="close"> x </a>
            <span class="glyphicon.glyphicon-warning-sign" style="margin-right:5px"></span>
            <strong><?=Yii::$app->session->getFlash('msg') ?></strong>
        </div>
	<?php else: ?>
    <?php
        $comment->code_id = $model->id;
        echo $this->render('/comment/_form', ['model'=>$comment] ); 
    ?>
	<?php endif; ?>

</div><!-- comments -->