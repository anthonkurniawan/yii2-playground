<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Block');
$this->params['breadcrumbs'][] = $this->title;
?>

<h1>ext/Block</h1>
 
<span class="label label-primary">RENDER IN PLACE </span>
 <p>
<?php $this->beginBlock('block1', true) ?>
Nothing.
<?php $this->endBlock(); ?>
</p>

<p>
<span class="label label-primary">RENDER LATER IN THIS PLACE OR OTHER VIEWS </span>
<?php $this->beginBlock('block2') ?>
<div class="myBox">
	<b class="text-primary">SOME REUSE CLIP( CAN USE ON EVERWHERE</b>
</div>
<?php $this->endBlock() ?>
</p>

Then render block2 at here ..
<?php echo $this->blocks['block2']; ?>

Show all block :
<pre>
 <?php    
 print_r($this->blocks);
 ?>
</pre>