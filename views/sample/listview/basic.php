<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'ListView Basic');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php echo Html::a('Advanced', ['sample/listview', 'view' =>'listview\advanced']); ?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="btn-group btn-group-sm" role="group" style="margin-bottom:5px">
    <?= Html::button(Yii::t('app', 'Search'), [
        'class' => 'btn btn-default',
        'onclick'=>'search_toggle( $(this), true );'
    ])?>
</div>
    
<!------- SEARCH USERS -------->
<div id="search_div" style="display:none">
    <?php echo $this->render('../_form/_search', ['model' => $searchModel]); ?>
</div>
    
<div class="users-index">
    
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
         'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model->id), ['view', 'id' => $model->id])
                ." {$model->username} {$model->email}"
                ." (key: {$key} index: {$index})";
        },
    ]) ?>
</div>
