<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'GridView');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo Html::a('GridView Ajax', ['user/index'], ['class'=>'pull-right']); ?>

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

<?php if(Yii::$app->session->hasFlash('msg')){ ?>
<div class="alert alert-danger" style='margin-bottom:10px'>
    <a data-dismiss="alert" href="#" class="close"> x </a>
    <span class="glyphicon.glyphicon-warning-sign" style="margin-right:5px"></span>
    <strong><?=Yii::$app->session->getFlash('msg') ?></strong>
</div>
<?php } ?>

<!----- GRIDVIEW ------>
<div class="users-index">
    <?= GridView::widget([
        'id'=>'gridview_adv',
        'dataProvider' => $dataProvider,
        'emptyText'=>'Data Provider kosong',
        'emptyTextOptions' => ['class' => 'empty'],
        'filterModel' => $searchModel,  // set null if want to disabled
        'filterUrl'=>null, // default: if not set Yii::$app->request->url;
        //'filterSelector'=>'#gridview_adv input, #gridview_adv select', // default: "#$id input, #$id select", string additional jQuery selector for selecting filter input fields
        'filterPosition' => 'body', // default : header, body, footer
        'filterRowOptions' => ['class' => 'filters'],
        'filterErrorSummaryOptions' => ['class' => 'error-summary'],
        'filterErrorOptions' => ['class' => 'help-block'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' => '#'],
            'id',
            'username',
            'email:email',
            ['class' => 'yii\grid\CheckboxColumn'],
            ['class' => 'yii\grid\ActionColumn'],
        ],
        'caption'=>'GridView Basic', 
        'captionOptions' => [],
        'tableOptions' => ['class' => 'table table-striped table-bordered'],
        'options' => ['class' => 'grid-view'],
        'headerRowOptions' => [],
        'footerRowOptions' => [],
        'rowOptions' => [],
        'beforeRow'=>null, // callback
        'afterRow'=>null, // callback
        'showHeader' => true,
        'showFooter' =>false,
        'showOnEmpty' => true,
        'formatter'=>Yii::$app->getFormatter(), // default : Yii::$app->getFormatter();
        'emptyCell' => '&nbsp;',
        'layout' => 'Sort By {sorter} {summary} {errors} {items} {pager}',  // default: '{summary}{items}{pager}'
        'pager'=>[  //'class'=>'yii\widgets\LinkPager'
            'options' => ['class' => 'pagination'],
            'linkOptions' => [],
            'firstPageLabel' => 'pertama',   // default: false
            'lastPageLabel' => 'terakhir',    // default: false
            'registerLinkTags' => false,     // default: false
            'hideOnSinglePage' => true,   // default: true
        ],  // default  use 'yii\widgets\LinkPager'
        'sorter'=>[  // default use 'yii\widgets\LinkSorter
            'attributes'=>['id', 'username'], // bisa juga di define pada "activeDataProvider"
            'options' => ['class' => 'sorter'],
            // GA ADA TEMPLATE NYA (HARUS KITA EXTEND SENDIRI "renderSortLinks()")
        ],  
        'summary'=>'Showing <b>{begin, number}-{end, number}</b> of <b>{totalCount, number}</b> {totalCount, plural, one{item} other{items}}',  // default: {begin} {end} {count} {totalCount} {page} {pageCount}
        'summaryOptions' => ['class' => 'summary'],
    ]); ?>

</div>

<?php
$this->registerJs("
var keys = $('#gridview_adv').yiiGridView('getSelectedRows');  console.log(keys);
    ",  \yii\web\View::POS_READY
);
?>
