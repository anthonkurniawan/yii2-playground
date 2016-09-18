<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\User_yiitest */

$this->title = Yii::t('app', 'Create User Yiitest');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Yiitests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-yiitest-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
