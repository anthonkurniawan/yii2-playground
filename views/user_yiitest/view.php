<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User_yiitest */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Yiitests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-yiitest-view">

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
            'username',
            'password',
            'role',
            'email:email',
            'first_name',
            'last_name',
            'create_time',
            'update_time',
            'reset_token',
            'activation_key',
            'validation_key',
            'active_date',
            'login_ip',
            'login_attemp',
            'last_login',
            'active',
            'gallery_id',
        ],
    ]) ?>

</div>
