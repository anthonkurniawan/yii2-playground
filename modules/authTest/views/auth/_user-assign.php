<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\JsExpression;      print_r($roles);
?>

<?=Html::beginForm('', 'post', ['id'=>'form-auth']); ?>
<?= Html::checkboxList('assign', $assigments, $roles, ['unselect'=>'none', 'tag'=>'div', 'separator'=>'<hr style="margin:5px">', 'itemOptions'=>[], ]); ?>
  
<div class="modal-footer">
<?=Html::submitButton(Yii::t('app', 'Assign'), ['onclick'=>"submitAuth('./authTest/assign/$id', '#user-list');", 'class' => 'btn btn-primary']); ?>
 </div>
    
<?=Html::endForm(); ?> 

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