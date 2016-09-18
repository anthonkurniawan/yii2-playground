<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Helpers Sample');
$this->params['breadcrumbs'][] = $this->title;
?>

<h1>ext/<?=$this->title?></h1>

 <?php
echo yii\jui\Tabs::widget([
   'items' => [
       // [
           // 'label' => 'Markdown',
           // 'content'=>SyntaxHighlighter::getBlock($code, 'php'),
       // ],
       [
           'label' => 'Markdown',
           'url' => ['sample/helpers-ajax-content', 'param'=>'_markdown'],
       ],
       [
           'label' => 'Json',
           'url' => ['sample/helpers-ajax-content', 'param'=>'_json'],
       ],
       [
           'label' => 'Url',
           'url' => ['sample/helpers-ajax-content', 'param'=>'_url'],
       ],
       [
           'label' => 'ArrayHelper',
           'url' => ['sample/helpers-ajax-content', 'param'=>'_arrayhelper'],
       ],
       // [
           // 'label' => 'M',
           // 'url' => ['sample/helpers-ajax-content', 'param'=>'_'],
       // ],
   ],
   'options' => ['tag' => 'div'],
   'itemOptions' => ['tag' => 'div'],
   //'headerOptions' => ['class' => 'my-class'],
   'clientOptions' => [
        'collapsible' => false,
    ],
    'clientEvents' => [
        "beforeLoad"=>'function(){ console.log("load.."); }',
        "create"=> 'function( event, ui ) { console.log(event, ui);  }',
        "load"=>'function(){ SyntaxHighlighter.highlight(); }',
    ],
]);
 ?>
 
<?php
// echo yii\bootstrap\Tabs::widget([
    // 'items' => [
        // [
            // 'label' => 'One',
            // 'content' => 'Anim pariatur cliche...',
            // 'active' => true
        // ],
        // [
            // 'label' => 'Two',
            // 'content' => 'Anim pariatur cliche...',
            // 'headerOptions' => [],
            // 'options' => ['id' => 'myveryownID'],
        // ],
        // [
            // 'label' => 'Dropdown',
            // 'items' => [
                 // [
                     // 'label' => 'DropdownA',
                     // 'content' => 'DropdownA, Anim pariatur cliche...',
                 // ],
                 // [
                     // 'label' => 'DropdownB',
                     // 'content' => 'DropdownB, Anim pariatur cliche...',
                 // ],
            // ],
        // ],
    // ],
// ]);
?>
 
