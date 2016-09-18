<?php
use yii\helpers\ArrayHelper; 
use app\extensions\SrcCollect;

$sc = new SrcCollect;  //print_r($sc);

$sc->setStart(__LINE__); 

$ar1 = array("color" => array("favorite" => "red"), 5);
$ar2 = array(10, "color" => array("favorite" => "green", "blue"));
?>

<?php yii\helpers\VarDumper::dump( ArrayHelper::merge($ar1, $ar2), 10, true ); ?> 
<br>
<?php yii\helpers\VarDumper::dump(array_merge_recursive($ar1, $ar2), 10, true ); ?> 


<?php
$sc->collect('php', $sc->getSourceToLine(__LINE__, __FILE__));
$sc->renderSourceBox();
?>

<!----------------------------------- with model ---------------------------------->
<?php
$sc->setStart(__LINE__); 

$model = new app\models\Users;  // OR new model\Users;  
$model1 = $model->find()->where(['username' => 'admin'])->one()->toArray();    // OR print_r( yii\helpers\ArrayHelper::toArray($var));
$model2 = $model->find()->where(['username' => 'tona'])->one()->toArray(); 
//print_r($var['errors']); 
?>

<?php yii\helpers\VarDumper::dump( ArrayHelper::merge($model1, $model2), 10, true ); ?> 
<br>
<?php yii\helpers\VarDumper::dump(array_merge_recursive($model1, $model2), 10, true ); ?> 


<?php
$sc->collect('php', $sc->getSourceToLine(__LINE__, __FILE__));
$sc->renderSourceBox();
?>