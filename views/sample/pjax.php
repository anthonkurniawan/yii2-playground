<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Pjax');
$this->params['breadcrumbs'][] = $this->title;
?>

<h1>ext/Pjax</h1>
https://github.com/yiisoft/jquery-pjax <br>

<img id="loading" src="<?=Yii::$app->homeUrl?>/images/loader/box_loading.gif" style="display:none"/>

<?php 
// yii\widgets\Pjax::begin([
    // 'id' => 'pjax-test', 
    // 'linkSelector'=>"#pjax-test a, a.test", 
    // 'formSelector'=>'#pjax-form',
    // 'timeout'=>false,
// ], ['style'=>'border:1px solid red']) 

yii\widgets\Pjax::begin([
    'options'=>['id' => 'pjax-test', 'style'=>'border:1px solid red;'], 
    'linkSelector'=>"#pjax-test a, a.test", // the selector that will tribber pjax request
    'formSelector'=>'#pjax-form',  // set if the form outside the widget, and give id on the form
    'enablePushState' => true, // default : true - will remote data the url string follow the request string given
    'enableReplaceState' => false, // default : false - replace URL string without adding browser history entry
    'timeout'=>false,  // default: 1000, if exceeded will reload page
    'scrollTo'=> false, // boolean|integer how to scroll the page when pjax response is received. If false, no page scroll will be made. default: false
    'clientOptions'=>[
        'maxCacheLength'=>20,	// default :20 - maximum cache size for previous container contents
        'type'=>'GET', // default: GET
        'dataType'=>'html',	// default : "html"	see $.ajax
        // 'container'=>'#bol',  GIVE CONTAINER AT HERE		// CSS selector for the element where content should be replaced
        'fragment'=>'body',		// CSS selector for the fragment to extract from ajax response
    ] 
 ]) 
?>

<b> sample in div container </b> <br>
<?php echo Html::a('test inside container',  ['sample/pjax']); ?>
            
<?php yii\widgets\Pjax::end() ?>

<?php echo Html::a('test outside container',  ['sample/pjax'], [ 'class'=>'test']); ?>
<br>

beginForm( $action = '', $method = 'post', $options = [] )
<?= Html::beginForm( $action = '', $method = 'get', ['id'=>'pjax-form', 'data-pjax' => true, 'style'=>'border: 1px solid orange']  ) ?>
    <b> sample in form container (outside the widget)</b> <br>
    input( $type, $name = null, $value = null, $options = [] )
    <?= Html::input( 'text', 'name', 'nilai', $options = [] ) ?>
    <?= Html::submitButton('Submit', ['name' => 'contact-button']) ?>
<?= Html::endForm(); ?>

<b>State : </b> <span id="state"></span><br>
<b>State Data : </b> <span id="state_data"></span><br>

<?php
$this->registerJs(
   "$('document').ready(function(){ 
        var opt = $('#option');
        var state = $('#state');
        var st_data = $('#state_data');
        
        jQuery(document).on('pjax:click', function(event, options) {    console.log(event, options);
            state.text(event.type); 
        })
        jQuery(document).on('pjax:send', function(event, xhr) {    console.log('send : ', event, xhr);
            $('#loading').show();
            state.text(event.type); 
            st_data.text( xhr.responseText );
        })
        .on('pjax:success', function(event, data, status, xhr) {   console.log('success : ', event, data, status, xhr);
            state.text(event.type); 
            st_data.text( xhr.responseText );
        })
        .on('pjax:error', function(event, xhr, textStatus, error) {   console.log('error : ', event, xhr, textStatus, error);
            state.text(event.type); 
            st_data.text( xhr.responseText );
        })
        .on('pjax:complete', function(event, xhr, textStatus) {   console.log('complete: ', event, xhr, textStatus);
            $('#loading').hide();
            state.text(event.type); 
            st_data.text( xhr.responseText );
        })
        .on('pjax:end', function(event) {   console.log('end');
            state.text(event.type); 
        })
    });"
);
?>

<p>
<pre>
<b>RENDERED SCRIPT : </b>

jQuery(document).ready(function () {
	jQuery(document).pjax("#pjax-test a, a.test", "#pjax-test", {
		"maxCacheLength" : 20,
		"type" : "GET",
		"dataType" : "html",
		"fragment" : "body",
		"push" : true,
		"replace" : false,
		"timeout" : false,
		"scrollTo" : false
	});
	jQuery(document).on('submit', "#pjax-form", function (event) {
		jQuery.pjax.submit(event, '#pjax-test', {
			"maxCacheLength" : 20,
			"type" : "GET",
			"dataType" : "html",
			"fragment" : "body",
			"push" : true,
			"replace" : false,
			"timeout" : false,
			"scrollTo" : false
		});
	});
	$('document').ready(function () {
		var opt = $('#option');
		var state = $('#state');
		var st_data = $('#state_data');

		jQuery(document).on('pjax:click', function (event, options) {
			console.log(event, options);
			state.text(event.type);
		})
		jQuery(document).on('pjax:send', function (event, xhr) {
			console.log('send : ', event, xhr);
			$('#loading').show();
			state.text(event.type);
			st_data.text(xhr.responseText);
		})
		.on('pjax:success', function (event, data, status, xhr) {
			console.log('success : ', event, data, status, xhr);
			state.text(event.type);
			st_data.text(xhr.responseText);
		})
		.on('pjax:error', function (event, xhr, textStatus, error) {
			console.log('error : ', event, xhr, textStatus, error);
			state.text(event.type);
			st_data.text(xhr.responseText);
		})
		.on('pjax:complete', function (event, xhr, textStatus) {
			console.log('complete: ', event, xhr, textStatus);
			$('#loading').hide();
			state.text(event.type);
			st_data.text(xhr.responseText);
		})
		.on('pjax:end', function (event) {
			console.log('end');
			state.text(event.type);
		})
	});
});
</pre>
</p>