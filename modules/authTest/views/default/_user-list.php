<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
//use kartik\export\ExportMenu;

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
#btn-deleteAll{ display:none}
.alert pre{font-size:11px}
</style>

<img class="loading" src="<?=Yii::$app->homeUrl?>/images/loader/box_loading.gif" style="display:none"/>

<div class="pull-right input-group">
    <!---- PAGE SIZE ---->
    <?=Html::beginForm('./authTest/load-user', 'post', ['id'=>'form-tools']); ?>
        <?php //echoHtml::button('Delete all',['id'=>'btn-deleteAll', 'class'=>'btn btn-xs btn-danger', 'onclick'=>'deleteAll();']);?>
        <?php //echoHtml::hiddenInput('selected',null, ['id'=>'selected']); ?>
        <strong>Page Size</strong>
        <?= Html::dropDownList('pageSize',
            Yii::$app->session->get('pageSize'), //Yii::$app->params['pageSize'],
            [2=>2, 5=>5,10=>10,20=>20],
            ['onchange'=>'$(form).submit();']	#OR : 'onchange'=>"$.fn.yiiGridView.update('user-grid',{ data:{pageSize: $(this).val() }})",
        );
        ?>
    <?=Html::endForm(); ?> 
</div> 
<!--
<div class="btn-group btn-group-sm pull-right" role="group" style="margin-bottom:5px; margin-right:5px">
    <?php 
    // Html::button(Yii::t('app', 'Create'), [
            // 'id'=>'btn_add_user',
            // 'class' => 'btn btn-primary',
            // 'onclick'=>'create_toggle( $(this), true );'
        // ]);
    ?>
</div>  
-->

    
<!----- LOADER ----->
<?php Pjax::begin([
    'id'=>'pjax-user-list', 
    'linkSelector'=>'#pjax-user-list a',
    'formSelector'=>'.gridview-filter-form, #form-tools', 
    'timeout'=>false, 
    'enablePushState' => false, 
    'clientOptions'=>['container'=>"#user-list"],
    ]); ?>

<!----- GRIDVIEW ------>
<div class="users-index">
    <?= GridView::widget([
        'id'=>'gridview_ajax',
        'dataProvider' => $dataProvider,
        'emptyText'=>'Data Provider kosong',
        'emptyTextOptions' => ['class' => 'empty'],
        'filterModel' => $searchModel,  // set null if want to disabled
        'filterUrl'=>null, // default: if not set Yii::$app->request->url;
        //'filterSelector'=>'#gridview_ajax input, #gridview_ajax select', // default: "#$id input, #$id select", string additional jQuery selector for selecting filter input fields
        'filterPosition' => 'body', // default : header, body, footer
        'filterRowOptions' => ['class' => 'filters'],
        'filterErrorSummaryOptions' => ['class' => 'error-summary'],
        'filterErrorOptions' => ['class' => 'help-block'],
        'columns' => [
            [
                'attribute'=>'id', 
                'headerOptions'=>[],
                'contentOptions'=>['width'=>30],
                'footerOptions'=>[],
                'filterOptions' => []
            ],
            [
                'class' => 'yii\grid\DataColumn', //DataColumn::className(),
                'attribute' => 'username',
                'format' => 'text',
                'filter'=>yii\helpers\ArrayHelper::map(app\models\User::find()->orderBy('username')->asArray()->all(), 'username', 'username'),
                'label' => 'Name',
            ],
            'email:email',
            [
                'label' =>'Assignments',
                'filter'=>true,     // $auth->getRolesByUser(1),
                'value'=> function ($model, $key, $index, $column){  
                    return $this->context->getAssignUser($key, true); //( !empty($role)) ? $role->name : null;
                }
            ],
            [
                'label' =>'Role',
                'filter'=>true,     // $auth->getRolesByUser(1),
                'value'=> function ($model, $key, $index, $column){  
                    return $this->context->getRoleUser($key); //( !empty($role)) ? $role->name : null;
                }
            ],
            [
                'label' =>'Permissions',
                'filter'=>true,     // $auth->getRolesByUser(1),
                'value'=> function ($model, $key, $index, $column){  
                    return $this->context->getPermitUser($key); //( !empty($role)) ? $role->name : null;
                }
            ],
            //['class' => 'yii\grid\CheckboxColumn', 'name' => 'selection'],
            [
                'class' => 'yii\grid\ActionColumn', 
                'template' => '{assign}', //'{view} {update} {delete} {assign}',
                'buttons' =>[
                    'assign' => function ($url, $model, $key) {
                        //return $model->status === 'editable' ? Html::a('Update', $url) : '';
                        return Html::a("<span class='glyphicon glyphicon-edit text-primary'></span>", $url, ['onclick'=>"authAssign($key);", 'data-pjax'=>0]);
                    }
                ],
                'buttonOptions' => [],
            ],
        ],
        //'caption'=>$this->blocks['tools'], 
        'captionOptions' => [],
        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style'=>'margin-top:5px'],
        'options' => ['class' => 'grid-view'],
        'headerRowOptions' => [],  // option <tr> tags
        'footerRowOptions' => [],
        'rowOptions' => [],
        'beforeRow'=>null, // callback
        'afterRow'=>null, // callback
        'showHeader' => true,
        'showFooter' =>false,   // defaullt: false
        'showOnEmpty' => true,
        'formatter'=>Yii::$app->getFormatter(), // default : Yii::$app->getFormatter();
        'emptyCell' => '&nbsp;',
        'layout' => '<span class="pull-left text-muted" style="margin-bottom:5px">{summary}</span> <span class="pull-left text-muted" style="margin-left:10px">Sort By :&nbsp;</span> {sorter} {errors} {items} {pager}',  // default: '{summary}{items}{pager}'
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
            'options' => ['class' => 'sorter pull-left'],
            // GA ADA TEMPLATE NYA (HARUS KITA EXTEND SENDIRI "renderSortLinks()")
        ],  
        'summary'=>'Showing <b>{begin, number}-{end, number}</b> of <b>{totalCount, number}</b> {totalCount, plural, one{item} other{items}}',  // default: {begin} {end} {count} {totalCount} {page} {pageCount}
        'summaryOptions' => ['class' => 'summary'],
    ]); ?>

</div>
<?php Pjax::end(); ?>
