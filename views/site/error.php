<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
        <p>EXCEPTION : <br> <?= nl2br(Html::encode($exception)); ?></p>
    </div>
                                                                                
    <p>
        The above error occurred while the Web server was processing your request.
    </p>
    <p>
        Please contact us if you think this is a server error. Thank you.
    </p>

    <strong> see yii/web/ErrorAction</strong><br>
    <code>Yii::$app->getErrorHandler()->exception</code>
    <button onclick="$('#toggle_view').toggle();">Show object</button>
    <pre id="toggle_view" style="display:none">
        <?php print_r(Yii::$app->getErrorHandler()->exception); ?>
        <?php //print_r($this); ?> 
    </pre>
</div>


<code>$this->_viewFiles</code>
<?php print_r($this->_viewFiles); //print_r($this); ?>
