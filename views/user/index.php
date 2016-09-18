<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\web\JsExpression;
//use kartik\export\ExportMenu;

$this->title = Yii::t('app', 'User');
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
.btn-selected{ display:none; margin-right:5px}
.alert pre{font-size:11px}
</style>

<?= $this->render('_modal');?>

<?php $this->beginBlock('tools') ?>
    <div class="pull-left">
        <strong>GridView Advandce</strong> 
        <img class="loading" src="<?=Yii::$app->homeUrl?>/images/loader/box_loading.gif" style="display:none"/>
    </div>
    
    <div class="pull-right">
    <!---- PAGE SIZE ---->
    <?=Html::beginForm('/yii2-test/user', 'get', ['id'=>'form-tools']); ?>
        <strong>Page Size</strong>
        <?= Html::dropDownList('pageSize',
            Yii::$app->session->get('pageSize'), //Yii::$app->params['pageSize'],
            [2=>2, 5=>5,10=>10,20=>20],
            ['onchange'=>'$("#form-tools").submit(); ']	#OR : 'onchange'=>"$.fn.yiiGridView.update('user-grid',{ data:{pageSize: $(this).val() }})",
        );
        ?>
    <?=Html::endForm(); ?>
    </div>
    <?=Html::button('Delete all',['class'=>'btn-selected btn btn-xs btn-danger pull-right', 'onclick'=>'deleteAll();']);?>
    <?=Html::button('Update all',['class'=>'btn-selected btn btn-xs btn-primary pull-right', 'onclick'=>'updateBatch();']);?>
<?php $this->endBlock() ?>


<div class="btn-group btn-group-sm pull-left" role="group" style="margin-bottom:5px">
    <?= Html::a(Yii::t('app', 'Create'), 'user/create',[
            'title'=>'Create',
            'class' => 'btn btn-default',
            'onclick'=>'loadGridAction($(this));'
        ])?>
    <?= Html::button(Yii::t('app', 'Search'), [
        'class' => 'btn btn-default',
        'onclick'=>'search_toggle( $(this), true );'
    ])?>
</div>

<div class="clearfix"></div>
    
<!------- SEARCH USERS -------->
<div id="search_div" style="display:none">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
</div>

<!----- LOADER ----->
<?php Pjax::begin([
    'id'=>'pjax-con', 
    'linkSelector'=>'#pjax-con a',
    'formSelector'=>'.gridview-filter-form, #user-search, #form-tools', 
    'timeout'=>0, 
    'enablePushState' => false]); ?>


<div class="alert alert-warning" style='display:none;margin-bottom:10px;padding:5px'>
    <a data-dismiss="alert" href="#" class="close pull-right"> x </a>
    <span class="glyphicon glyphicon-warning-sign pull-left" style="margin-right:5px"></span>
    <div id="msg" style="height:100px; overflow:auto"></div>
</div>

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
            //['class' => 'yii\grid\SerialColumn', 'header' => '#'],  # ADD COLUMN INDEX / NUMBER
            # `"attribute:format:label"`. sample: `"username:text:Name User"`.
            [
                'class' => 'yii\grid\DataColumn', //DataColumn::className(), (default set if not set)
                'attribute'=>'id', 
                'format' => 'text',  # default :'text', available: 'text','ntext','html','paragraphs','email','image','url','bollean', 'raw', 'date', 'datetime', etc..SEE : http://localhost/yii2doc/guide-output-formatter.html
                'filter'=>true,   # default : true
                'filter'=>yii\helpers\ArrayHelper::map(app\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
                'filterInputOptions'=>['class'=>'form-control', 'style'=>'padding:0px; width:40px'],
                'headerOptions'=>[],
                'contentOptions'=>[],
                'footerOptions'=>[],
                'filterOptions' =>[],  // td opts
            ],
            [
                'attribute' => 'username',
                //'filter'=>yii\helpers\ArrayHelper::map(app\models\User::find()->orderBy('username')->asArray()->all(), 'username', 'username'),
                'filter'=>yii\jui\AutoComplete::widget([
                    'model' => $searchModel,
                    //'name'=>"UserSearch[username]",
                    'attribute' => 'username',
                    'clientOptions' => [
                       //'source' => ['admin', 'RUS'],
                       //'source' => array_keys(app\models\User::find()->orderBy('username')->asArray()->all()),
                       'source'=>$model::getSuggest(),
                       'select'=>new JsExpression('function( event, ui ) {   
                            console.log(event, ui);
                            this.value = ui.item.value;
                            $("#gridview_ajax").yiiGridView("applyFilter"); 
                        }'),
                    ],
                    'options' => ['id'=>'usersearch-username-autocomplete', 'class' => 'form-control ',],
                ]),
                //'label' => 'Name',
            ],
            'email:email',
            //'status', 'groups',
            [
                'attribute' => 'status',
                'filter'=>$model->getStatusList(true),
                'value'=> function ($model, $key, $index, $column){  
                    //return $this->context->getAssignUser($key, true); //( !empty($role)) ? $role->name : null;
                    return $model->getStatusText($model->status);
                }
            ],
            [
                'attribute' => 'groups',
                'filter'=>$model->getGroupList(true),
                'value'=> function($model){  
                    return $model->getGroupText($model->groups);
                }
            ],
            //'update_at',
            //'updated_at:relativeTime',
            [
                'attribute'=>'updated_at',  // 'lastname'
                'format'=>'relativeTime',
                'contentOptions'=>['class'=>'text-muted'],
                //'value'=>'profiles.lastname',
            ],
            [
                'attribute'=>'profiles_lastname',  // 'lastname'
                'label' => 'Lastname',
                'value'=>'profiles.lastname',
            ],
            [
                'attribute' => 'profiles_fullname', //'fullname',
                'value'=>'profiles.fullname',
            ],
            // [
                // "attribute" => "status_id",
                // 'filter' => ArrayHelper::map(Status::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                // "value" => function($model){
                    // if ($rel = $model->getStatus()->one()) {
                        // return yii\helpers\Html::a($rel->name,["crud/status/view", 'id' => $rel->id,],["data-pjax"=>0]);
                    // } else {
                        // return '';
                    // }
                // },
                // "format" => "raw",
            // ],
            
            ['class' => 'yii\grid\CheckboxColumn', 'name' => 'selection', 'checkboxOptions'=> [], ],
            [
                'class' => 'yii\grid\ActionColumn', 
                'template' => '{view} {update} {delete}',
                'buttonOptions' => ['onclick'=>'loadGridAction($(this));'],
                'buttons' =>[
                    'delete' => function ($url, $model, $key) {
                        return Html::a("<span class='glyphicon glyphicon-trash text-primary'></span>", $url, ['title' => Yii::t('yii', 'Delete'),'data-pjax'=>'0','onclick'=>'loadGridAction($(this));']);
                    }
                ],
            ],
        ],
        'caption'=>$this->blocks['tools'], 
        'captionOptions' => [],
        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style'=>'font-size: 85%'],
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
        'layout' => '<span class="pull-left text-muted">{summary}</span> <span class="pull-left text-muted" style="margin-left:10px">Sort By : </span> {sorter} <div class="clearfix"></div> {errors} {items} {pager}',  // default: '{summary}{items}{pager}'
        //'layout' => '<span class="pull-left text-muted" style="margin-bottom:5px">{summary}</span> <span class="pull-left text-muted" style="margin-left:10px">Sort By :&nbsp;</span> {sorter} {errors} {items} {pager}',  // default: '{summary}{items}{pager}'
        'pager'=>[  //'class'=>'yii\widgets\LinkPager'
            'options' => ['class' => 'pagination'],
            'linkOptions' => [],
            'firstPageLabel' => 'pertama',   // default: false
            'lastPageLabel' => 'terakhir',    // default: false
            'registerLinkTags' => false,     // default: false
            'hideOnSinglePage' => true,   // default: true
            'maxButtonCount'=> 3,
            'prevPageCssClass'=> 'prev',
            'nextPageCssClass'=> 'next',
            'activePageCssClass'=> 'active',
            'disabledPageCssClass'=> 'disabled',
            'nextPageLabel'=> '&raquo,',
            'prevPageLabel'=> '&laquo,',
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

<?php
// $gridColumns = [
    // ['class' => 'yii\grid\SerialColumn'],
    // 'id',
    // 'username',
    // ['class' => 'yii\grid\ActionColumn'],
// ];

// echo ExportMenu::widget([
    // 'dataProvider' => $dataProvider,
    // 'columns' => $gridColumns
// ]);
?>

<?php
$this->registerJs(
   "$('document').ready(function(){ 
        jQuery(document).on('pjax:send', function(xhr, options) {    //console.log('PJAX SEND : ', xhr, options);
           $('.loading').show();  
        })
        .on('pjax:success', function(data, status, xhr) {   //console.log('PJAX SUCCESS : ', 'data :', data, 'status :', status, 'xhr : ', xhr);
        })
        .on('pjax:error', function(event, xhr) {  console.log('PJAX ERROR : ', xhr);
            $('#msg').html(xhr.responseText).parent().show(100);  
            return false;
        })
        .on('pjax:complete', function(event, xhr) {   console.log('PJAX COMPLETE : ', event, xhr);
            $('.loading').hide();  
            if($.pjax.options.container=='#user-form'){
                if(xhr.responseText==1){            console.log('RELOAD..');
                    $.pjax.reload({container:'#pjax-con', url: location.href, timeout:0, replace:false});
                    $('#modal-user').modal('toggle');  
                }
                else if($('.has-error').length > 0){
                    var pos = $('.has-error').position();   console.log('ERROR POSISTION : ', pos);
                    $('.modal-body').scrollTop( pos.top);
                }
            }
        })
        .on('pjax:end', function(event) {   console.log('PJAX END', event);
            $('#gridview_ajax input[name=\"selection_all\"]').on('change', checkAll);
            $('#gridview_ajax input[name=\"selection[]\"]').on('change', check);
        });
        
        // checkbox selection
        $('#gridview_ajax input[name=\"selection_all\"]').on('change', checkAll);
        $('#gridview_ajax input[name=\"selection[]\"]').on('change', check);
        
        function checkAll(){    console.log('check', $(this));
            if($(this)[0].checked)
                $('.btn-selected').show(100);
            else 
                $('.btn-selected').hide(100);
            var checked = $('#gridview_ajax').find('input[name=\"selection[]\"]:checked').length;  console.log('CHECKED :', checked);
        }
        
        function check(){
            var checked = $('#gridview_ajax').find('input[name=\"selection[]\"]:checked').length;  console.log('CHECKED :', checked);
            if(checked !==0)  
                $('.btn-selected').show(100);
            else 
                $('.btn-selected').hide(100);
        }
    });
    
    function createUser(){      
        event.preventDefault();
        var form = $('#form-user');
        $.pjax({url: form.attr('action'), type:'post', data: form.serializeArray(), container: '#user-form', timeout:0,replace:false, push:false});
        //$.pjax.submit(event, '#form-user', {container: '#user-form', dataType:'json', timeout:0, replace:false, push:true})

        // $.post( form.attr('action'), form.serializeArray(), function(data){  
            // console.log('DATA : ', data); 
            // // if(data.status)  
                // // $.pjax.reload({container:'#auth-list', url: './authTest/load-auth', timeout:0, replace:false});
        // }, 'json');
    }
    
    function addBatchUser(action, save){      
        event.preventDefault();
        var form = $('#form-user');
        if(action=='create')
            var url = (save) ? '/yii2-test/user/create-batch?save='+save : '/yii2-test/user/create-batch'; 
        else{
            var keys = $('#gridview_ajax').yiiGridView('getSelectedRows'); 
            var url = (save) ? '/yii2-test/user/update-batch?keys='+keys+'&save='+save : '/yii2-test/user/update-batch?keys='+keys;
        }
        $.pjax({url: url, type:'post', data: form.serializeArray(), container: '#user-form', timeout:0,replace:false, push:false});
    }
    
    function updateBatch(){         
        event.preventDefault();
        $('#user-form').parent().css({'overflow':'auto', 'max-height': window.innerHeight -100});
        var keys = $('#gridview_ajax').yiiGridView('getSelectedRows');  console.log(keys);
        setModal('Update All '+keys);
        $.pjax({url: '/yii2-test/user/update-batch?keys='+keys, container: '#user-form', timeout:0,replace:false, push:false});  
    }
    
    function loadGridAction(el){       console.log('LOAD ACTION : ', el, el[0].href);
        event.preventDefault();
        setModal(el[0].title);
        var type = (el[0].title=='Hapus') ? 'post' : 'get';        
        
        if(el[0].title=='Create')
            $('#user-form').load(el[0].href);
        else
            $.pjax({url: el[0].href, type: type, container: '#user-form', timeout:0,replace:false, push:false});
    }

    function deleteAll(){
        var keys = $('#gridview_ajax').yiiGridView('getSelectedRows');  console.log(keys);
        setModal('Delete '+keys);
        $.pjax({url: '/yii2-test/user/delete?id='+keys, type: 'post', container: '#user-form', timeout:0,replace:false, push:false});
    }
    
    function setModal(title){
        $('#modal-tilte').text(title);
        $('#modal-user').modal(true);
    }
    
    function create_toggle(obj, toggle){   
        var txt = (obj.text()=='Create') ? 'Cancel' : 'Create'; 
        obj.text(txt);
        if(toggle)
            $('#create_div').toggle(100);
        else $('#create_div').hide(100);
    }
    ",  \yii\web\View::POS_END
);
?>
