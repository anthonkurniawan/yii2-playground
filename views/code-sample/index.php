<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CodeSampleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Code Samples');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="code-sample-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <?php if (Yii::$app->session->hasFlash('msg')): ?>
    <div class="alert alert-danger" style='margin-bottom:10px'>
        <a data-dismiss="alert" href="#" class="close"> x </a>
        <span class="glyphicon.glyphicon-warning-sign" style="margin-right:5px"></span>
        <strong><?=Yii::$app->session->getFlash('msg') ?></strong>
    </div>
    <?php endif; ?>
    

    <?= Html::a(Yii::t('app', 'Create Code Sample'), ['create'], ['class' => 'btn btn-sm btn-default pull-right', 'style'=>'margin-bottom:5px']) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            //'class_id',
            'id',
            [
                'class' => 'yii\grid\DataColumn', //DataColumn::className(),
                'attribute' => 'classname',
                'format' => 'text',
                'filter'=>true,
                'value'=>'class.classname'
            ],
            'title',
            //'content:ntext',
            [
                'attribute' => 'content',  
                'format' =>'ntext',
                'value'=>function ($model, $key, $index, $column){  
                   return substr( $model->content, 0, 100) . '...';
                },
            ],
            'tags',
            [
                'label'=>'Create By',
                'attribute' => 'createby',  // buta sort & filter
                'value'=>function($model){ 
                    return ($model->users) ? $model->users->username : 'anomousy';
                },
            ],
            // 'create_by',
            //'update_by',
            [
                'label'=>'Update By',
                'attribute' => 'updateby',   // buta sort & filter
                'value'=>function($model){ 
                   //return ($user = $model->getUsers('update_by')->one()) ? $user->username : 'anomousy';
                   return ($user = $model->updateByUser) ? $user->username : '<i class="text-muted">anomousy</i>';
                },
                'format'=>'raw',
            ],
            //'create_time:datetime',
            //'update_time:date',
            [
                'attribute' => 'update_time', 
                'format' => ["date", "d/M/Y"],
            ],
            // 'update_time:datetime',
            ['attribute'=>'likes', 'filter'=>false],
            [
                'label' => 'Comments',
                'value'=>function($model){ 
                    return $model->getCommentCount($model->id);
                },
            ],
            
            ['class' => 'yii\grid\ActionColumn'],
        ],
        'pager'=>[  //'class'=>'yii\widgets\LinkPager'
            'options' => ['class' => 'pagination'],
            'linkOptions' => [],
            'firstPageLabel' => 'pertama',   // default: false
            'lastPageLabel' => 'terakhir',    // default: false
            'registerLinkTags' => false,     // default: false
            'hideOnSinglePage' => true,   // default: true
            'maxButtonCount'=> 3,
        ],  // default  use 'yii\widgets\LinkPager'
    ]); ?>

</div>
