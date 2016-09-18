<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch_yiitest */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'User Yiitests');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-yiitest-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create User Yiitest'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'password',
            'role',
            'email:email',
            // 'first_name',
            // 'last_name',
            // 'create_time',
            // 'update_time',
            // 'reset_token',
            // 'activation_key',
            // 'validation_key',
            // 'active_date',
            // 'login_ip',
            // 'login_attemp',
            // 'last_login',
            // 'active',
            // 'gallery_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
