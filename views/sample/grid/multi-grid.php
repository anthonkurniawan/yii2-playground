<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('app', 'Grid Multi');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo Html::a('GridView Ajax', ['user/index'], ['class'=>'pull-right']); ?>
<?php $this->registerCss('.pagination{margin:0px}'); ?>

<?php
$userProvider->pagination->pageSize=3;
$userProvider->pagination->pageParam = 'user-page';
$userProvider->sort->sortParam = 'user-sort';

$codeProvider->pagination->pageSize=3;
$codeProvider->pagination->pageParam = 'code-page';
$codeProvider->sort->sortParam = 'code-sort';
?>

<!----- GRIDVIEW ------>
<div class="users-index">
    <?= GridView::widget([
        'id'=>'user-grid',
        'dataProvider' => $userProvider,
        'emptyTextOptions' => ['class' => 'empty'],
        'filterModel' => $userSearch,  // set null if want to disabled
        'filterUrl'=>null, // default: if not set Yii::$app->request->url;
        //'filterSelector'=>'#gridview_adv input, #gridview_adv select', // default: "#$id input, #$id select", string additional jQuery selector for selecting filter input fields
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' => '#'],
            'id',
            'username',
            'email:email',
            ['class' => 'yii\grid\CheckboxColumn'],
            ['class' => 'yii\grid\ActionColumn'],
        ],
        'caption'=>'User GridView', 
        'captionOptions' => [],
        'tableOptions' => ['class' => 'table table-striped table-condensed'],
        'options' => ['class' => 'grid-view'],
    ]); ?>
</div>

<!----- GRIDVIEW ------>
<div class="code-index">
    <?= GridView::widget([
        'id'=>'code-grid',
        'dataProvider' => $codeProvider,
        'emptyTextOptions' => ['class' => 'empty'],
        'filterModel' => $codeSearch,  // set null if want to disabled
        'filterUrl'=>null, // default: if not set Yii::$app->request->url;
        //'filterSelector'=>'#gridview_adv input, #gridview_adv select', // default: "#$id input, #$id select", string additional jQuery selector for selecting filter input fields
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' => '#'],
            'class_id',
            'title',
            'content:ntext',
            ['class' => 'yii\grid\CheckboxColumn'],
            ['class' => 'yii\grid\ActionColumn'],
        ],
        'caption'=>'Code Sample GridView', 
        'captionOptions' => [],
        'tableOptions' => ['class' => 'table table-responsive table-bordered'],
        'options' => ['class' => 'grid-view'],
    ]); ?>
</div>

<?php
$this->registerJs("
var keys = $('#gridview_adv').yiiGridView('getSelectedRows');  console.log(keys);
    ",  \yii\web\View::POS_READY
);
?>
