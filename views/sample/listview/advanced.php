<?php
use yii\helpers\Html;
use yii\widgets\ListView;


$this->title = Yii::t('app', 'ListView Advanced');
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
.sorter{}
ul.sorter{
  display: -webkit-box;
  list-style-type: none;
  margin:0px;
  -webkit-padding-start: 0px;
}
ul.sorter li{ margin-right: 10px}
</style>

<?php echo Html::a('Basic', ['sample/listview', 'view' =>'listview\basic']); ?>

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
    <?php
    echo Html::beginForm();
    echo "Page Size :";
    echo Html::dropDownList('pageSize',
        Yii::$app->session->get('pageSize'), //Yii::$app->params['pageSize'],
        [2=>2, 5=>5,10=>10,20=>20],
        ['onchange'=>'$(form).submit();']	#OR : 'onchange'=>"$.fn.yiiGridView.update('user-grid',{ data:{pageSize: $(this).val() }})",
    );
    echo Html::endForm();
    ?>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= ListView::widget([
        'id'=>'listview_adv',
        'dataProvider' => $dataProvider,
        'showOnEmpty' => false,
        'emptyText'=>'Data Provider kosong',
        'itemOptions' => ['class' => 'item'],
        'itemView' => '_items',
        'viewParams' => ['param1'=>'test'],  # send param to view item
        'itemOptions' =>['style'=>'color:red'],
        'emptyTextOptions' => ['class' => 'empty'],
        'separator' => "\n",
        'summary'=>'Showing <b>{begin, number}-{end, number}</b> of <b>{totalCount, number}</b> {totalCount, plural, one{item} other{items}}',  // default: {begin} {end} {count} {totalCount} {page} {pageCount}
        'summaryOptions' => ['class' => 'summary'],
        'layout' => '<span class="pull-left text-muted">{summary}</span> <span class="pull-left text-muted" style="margin-left:10px">Sort By : </span> {sorter} <div class="clearfix"></div>{items} {pager}', // default: "{summary}\n{items}\n{pager}",
        'sorter'=>[  // default use 'yii\widgets\LinkSorter
            'attributes'=>['id', 'username'], // bisa juga di define pada "activeDataProvider"
            'options' => ['class' => 'sorter'],
            // GA ADA TEMPLATE NYA (HARUS KITA EXTEND SENDIRI "renderSortLinks()")
        ],  
        'pager'=>[  //'class'=>'yii\widgets\LinkPager'
            'options' => ['class' => 'pagination'],
            'linkOptions' => [],
            'firstPageLabel' => 'pertama',   // default: false
            'lastPageLabel' => 'terakhir',    // default: false
            'registerLinkTags' => false,     // default: false
            'hideOnSinglePage' => true,   // default: true
        ],  // default  use 'yii\widgets\LinkPager'
        'options'=>['class' => 'list-view'],  // default : ['class' => 'list-view'];
    ]) ?>
</div>
