<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\JsExpression;
?>

<style>
.ui-autocomplete-loading {
    background: white url('<?=Yii::$app->homeUrl;?>/images/loader/box_loading.gif'); right center no-repeat; 
    background-repeat: no-repeat;
    background-position: right center;
    padding-right:10px
 }
 .ui-autocomplete {
    z-index: 5000;
}
</style>

<div>
    <?php    
    $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            // 'horizontalCssClasses' => [
                // 'label' => 'col-sm-3',
                // 'offset' => 'col-sm-offset-3',
                // 'wrapper' => 'col-sm-8',
                // 'error' => '',
                // 'hint' => '',
            // ],
        ],
        'options'=>['id'=>'form-auth', 'class'=>'form-group-sm']
    ]);
    ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 2]) ?>
    <?=
    $form->field($model, 'ruleName')->widget('yii\jui\AutoComplete', [
        'options' => [
            'class' => 'form-control',
        ],
        'clientOptions' => [
            'source' => array_keys(Yii::$app->authManager->getRules()),
        ]
    ])
    ?>
    <?= $form->field($model, 'data')->textarea(['rows' => 6]) ?>
    <?php //$form->field($model, 'child')->textarea(['rows' => 6]) ?>
    <?=
    $form->field($model, 'child')->widget('yii\jui\AutoComplete', [
        'options' => [
           'class' => 'form-control',
        ],
        'clientOptions' => [
            //'source' => $model->getAutoComplete(), //array_keys(Yii::$app->authManager->getItems($model->type)),
            'source' => new JsExpression('function( request, response ) {  console.log("REQUEST : ", request, response);
				$.getJSON( "'. yii\helpers\Url::to(['auto-complete', 'type'=>$model->type]).'", {
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
        ]
    ])
    ?>

    <div class="modal-footer">
            <?php //echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['onclick'=>'submitAuth(event);', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?php 
                if($model->isNewRecord)
                    echo Html::submitButton(Yii::t('app', 'Create'), ['onclick'=>"submitAuth('./authTest/auth/create/$model->type', '#auth-list');", 'class' => 'btn btn-success']);
                else
                    echo Html::submitButton(Yii::t('app', 'Update'), ['onclick'=>"submitAuth('./authTest/auth/update/$model->name/$model->type', '#auth-list');", 'class' => 'btn btn-primary']);
            ?>
    </div> 
    
</div>
<?php ActiveForm::end(); ?>

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