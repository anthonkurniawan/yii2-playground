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
       [
           'label' => 'Tab one',
           'content' => 'Mauris mauris ante, blandit et, ultrices a, suscipit eget...',
       ],
       [
           'label' => 'Tab two',
           'content' => 'Sed non urna. Phasellus eu ligula. Vestibulum sit amet purus...',
           'options' => ['tag' => 'div'],
           'headerOptions' => ['class' => 'my-class'],
       ],
       [
           'label' => 'Tab with custom id',
           'content' => 'Morbi tincidunt, dui sit amet facilisis feugiat...',
           'options' => ['id' => 'my-tab'],
       ],
       [
           'label' => 'Ajax tab',
           'url' => ['ajax/content'],
       ],
   ],
   'options' => ['tag' => 'div'],
   'itemOptions' => ['tag' => 'div'],
   'headerOptions' => ['class' => 'my-class'],
   'clientOptions' => ['collapsible' => false],
]);
 ?>
 
<?php
echo yii\bootstrap\Tabs::widget([
    'items' => [
        [
            'label' => 'One',
            'content' => 'Anim pariatur cliche...',
            'active' => true
        ],
        [
            'label' => 'Two',
            'content' => 'Anim pariatur cliche...',
            'headerOptions' => [],
            'options' => ['id' => 'myveryownID'],
        ],
        [
            'label' => 'Dropdown',
            'items' => [
                 [
                     'label' => 'DropdownA',
                     'content' => 'DropdownA, Anim pariatur cliche...',
                 ],
                 [
                     'label' => 'DropdownB',
                     'content' => 'DropdownB, Anim pariatur cliche...',
                 ],
            ],
        ],
    ],
]);
?>
 
