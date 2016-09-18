<?php 
use yii\bootstrap\Modal; 
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\web\JsExpression;
?>

<style>
.ui-autocomplete-loading {
    background: white url('../images/loader/box_loading.gif'); right center no-repeat; 
    background-repeat: no-repeat;
    background-position: right center;
    padding-right:10px
 }
 .ui-autocomplete {
    z-index: 5000;
}
</style>

<?php
Modal::begin([
    'id'=>'modal-new-code',
    'header' => '<h4 class="modal-tilte">Save new sample <span id="title-class"></span></h4>',
    'toggleButton' => false, //['label' => 'click me'],
]);
?>

<div id="code-sample-msg"></div>

<div class="code-sample-form">
    <?php 
    $model = new \app\models\CodeSample(['scenario' => 'create']); 
    $modelClass = new \app\models\CodeClass;
    //$form = ActiveForm::begin(['options'=>['id'=>'form-new-code', 'class'=>'form-group-sm']]); 
    $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-3',
                'offset' => 'col-sm-offset-3',
                'wrapper' => 'col-sm-8',
                'error' => '',
                'hint' => '',
            ],
        ],
        'enableAjaxValidation' => false,
        'options'=>['id'=>'form-new-code', 'class'=>'form-group-sm']
    ]);
    ?>

    <?php //echo $form->field($model, 'id', ['template'=>"{input}"])->TextInput() ?>
    <?php //$form->field($model, 'class_id', ['template'=>"{input}"])->textInput() ?>
    <?= $form->field($modelClass, 'classname')->textInput(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 4, 'style'=>'height:70px']) ?>

    <?= $form->field($model, 'tags')->widget(\yii\jui\AutoComplete::classname(), [
        'clientOptions' => [
            //'source'=> yii\helpers\Url::to(['suggest', 'model'=>'app\models\Tags', 'order' => 'name']),
            //'appendTo'=>new JsExpression('$("#codesample-tags")'),
            'source' => new JsExpression('function( request, response ) {
				$.getJSON( "'. yii\helpers\Url::to(['code-sample/suggest', 'model'=>'app\models\Tags', 'order' => 'name']).'", {
					term: extractLast( request.term )
				}, response );
			}'),
            'focus'=>new JsExpression("function(req, res) {  console.log(req, res);   //console.log(extractLast( req.term ));
                //$(this).val(res.item.value);
                return false;  // prevent value inserted on focus
            }"),
            'search'=>new JsExpression("function() {  
                // custom minLength
				var term = extractLast( this.value );   console.log('SEARCH : ', term);
				if ( term.length < 1 ) 
					return false;
            }"),
            'select'=>new JsExpression('function( event, ui ) {   
				var terms = split( this.value );  console.log("SELECT : ", terms);
                terms.pop();
				terms.push( ui.item.value );
				terms.push( "" );
				this.value = terms.join( ", " );
				return false;
			}'),
        ],
        'options'=>['class'=>'form-control', 'maxlength' => 255],
    ]) ?>
    <?php //echo  Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['onclick'=>'submitNewCode(event);', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
 
<div class="modal-footer">
      <div class="form-group">
        <?php //echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['onclick'=>"submitNewCode('../code-sample/create');", 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?=Html::submitButton(Yii::t('app', 'Create'), ['onclick'=>"submitNewCode('../code-sample/create');", 'class' =>'btn btn-success']) ?>
        <?=Html::submitButton(Yii::t('app', 'Update'), ['onclick'=>"sendAction('update');", 'class' =>'btn btn-warning no_dis']) ?>
    </div>
</div> 
<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>

<?php
$this->registerJs("
    function split( val ) {
        return val.split( /,\s*/ );
	}
	function extractLast( term ) {
        return split(term).pop();
	}
    
    $('#modal-new-code')
    .on('show.bs.modal', function (event) {        
        if($('#codesample-id').val()!='')
            $('button:contains(\"Update\")').show();
    })
    .on('hidden.bs.modal', function (event) {  
        $('#code-sample-msg').html('');
        $('button:contains(\"Update\")').hide();
    });
    
    
    // $('#form-new-code').on('beforeSubmit', function (event, jqXHR, settings){ 
        // console.log('MESSAGES ON BEFORE SUBMIT :' ,  jqXHR, settings); 
    // });
    // $('#form-new-code').on('beforeValidate', function (event, messages){ 
        // console.log('MESSAGES ON BEFORE VALIDATE :' ,messages) 
    // });
    
    function submitNewCode(url){  
        event.preventDefault();
        
        //$('#form-new-code').data('yiiActiveForm').submitting=true;
        //$('#form-new-code').yiiActiveForm('validate');
        
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: $('#form-new-code').serializeArray(),
            beforeSend: function (jqXHR, settings) {    //console.log(jqXHR, settings);
                //$('form').trigger('ajaxBeforeSend', [jqXHR, settings]);
            },
            success: function(data) {   console.log('DATA : ', data);
                if(data.status){
                    $('#modal-new-code').modal('hide');
                    initBtnAction('hide');
                    activeBtnCmdResponse();
                    if(url=='../code-sample/create') 
                        loadAllSample();
                }else{
                    var items =[];
                    $.each(data.content, function(k, i){ items.push('<li>'+i+'</li>'); });
                    
                    var tpl = '<div class=\'alert-danger alert fade in\'>'
                        +'<button type=\'button\' class=\'close\' data-dismiss=\'alert\'>&times;</button>'
                        +'<div style=\'max-height: 70px;overflow:auto\'>'+ items.join(\"\")+'</div></div>';
                    $('#code-sample-msg').html( tpl );
                    
                    // add class errors
                    $.each($('#form-new-code').find('.required :input'), function(i, item){  //console.log(item,  $(this).val(), $(this).parents('.required'));
                        if( $(this).val()=='') $(this).parents('.required').addClass('has-error'); 
                    });
                }

            },
            error: function(xhr, status, errorThrown) {	//console.log('error ', xhr); 
				$('#new-code-alert').html( xhr.responseText ).parent().show();
            },
        });
        return false;
    }
   ",  \yii\web\View::POS_END);
?>