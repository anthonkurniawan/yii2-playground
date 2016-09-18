<?php
use yii\helpers\Url;
echo Url::toRoute(['siteindex', 'param1' => 'value1', 'param2' => 'value2']);
echo "<br>".Url::toRoute(['siteindex', 'param1' => 'value1', 'param2' => 'value2'], true);
echo "<br>".Url::toRoute(['siteindex', 'param1' => 'value1', '#' => 'value2']);
echo "<br>".Url::toRoute(['siteindex', 'param1' => 'value1', '#' => 'value2'], 'https');
?>

<?php
echo "<br>".Url::to();
echo "<br>".Url::to(['siteindex', 'param1' => 'value1', '#' => 'value2']);
?>

<?php
echo "<br>".Url::base();
?>

<?php
Url::remember(['site/index']);
Url::remember(['site/post'], 'post');
echo "<br>".Url::previous();
echo "<br>".Url::previous('post');
?>

<?php
echo "<br>".Url::canonical();
?>