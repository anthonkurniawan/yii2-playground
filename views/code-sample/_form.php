<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\CodeSample */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
.ui-autocomplete-loading {
    background: white url('../images/loader/box_loading.gif'); right center no-repeat; 
    background-repeat: no-repeat;
    background-position: right center;
    padding-right:10px
 }
</style>

<div class="code-sample-form">
    <?php $form = ActiveForm::begin(['options'=>['class'=>'form-group-sm']]); ?>

    <?= $form->errorSummary($model, [
        'header'=>'<p>' . Yii::t('yii', 'Please fix the following errors :') . '</p>',
        'footer'=>'',
        'encode'=>true
    ] ); ?>
    
    <?=$form->field($model, 'class_id', ['template'=>"{input}"])->hiddenInput() ?>
    
    <div class="pull-left" style="width:40%">
    <?= $form->field($modelClass, 'classname')->widget(\yii\jui\AutoComplete::classname(), [
        'clientOptions' => [
            //'source' => ['USA', 'RUS'],   codesample-class_id
            'source'=> yii\helpers\Url::to(['suggest', 'model'=>$modelClass::classname(), 'order' => 'classname']),
            'response'=>new JsExpression('function( content, x ) {   
                console.log("RESP : ", content, x);
			}'),
            'select'=>new JsExpression('function( event, ui ) {   console.log("SELECT : ", ui);
                this.value = ui.item.value;
                $("#codesample-class_id").val( ui.item.id );
				return false;
			}'),
        ],
        'options'=>['class'=>'form-control'],
    ]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'tags')->widget(\yii\jui\AutoComplete::classname(), [
        'clientOptions' => [
            //'source'=> yii\helpers\Url::to(['suggest', 'model'=>'app\models\Tags', 'order' => 'name']),
            'source' => new JsExpression('function( request, response ) {
				$.getJSON( "'. yii\helpers\Url::to(['suggest', 'model'=>'app\models\Tags', 'order' => 'name']).'", {
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
    </div>
    
    <div class="pull-left" style="width:50%; margin-left:10px">
    <?= $form->field($model, 'content')->textarea(['rows' => 6, 'style'=>'height:170px']) ?>
    </div>
    
    <div class="clearfix"></div>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$this->registerJs("
    function split( val ) {
        return val.split( /,\s*/ );
	}
	function extractLast( term ) {
        return split(term).pop();
	}
   ",  \yii\web\View::POS_END);
?>
