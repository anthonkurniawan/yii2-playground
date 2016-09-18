<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CodeSample */

$this->title = Yii::t('app', 'Create Code Sample');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Code Samples'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="code-sample-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelClass'=>$modelClass,
    ]) ?>

</div>
