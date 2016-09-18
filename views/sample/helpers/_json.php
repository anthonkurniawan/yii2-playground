<?php
use yii\helpers\VarDumper;
use yii\helpers\Json;

$arr = [0=>'a', 1=>'b', 2=>[1,2]];
$obj = '["a","b",[1,2]]';

$e = Json::encode($arr, 320);  VarDumper::dump($e, 10, true);  echo "<br>";
$d = Json::decode($obj);   VarDumper::dump($d, 10, true);  
?>
