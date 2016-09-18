<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'breadcrumbs');
$this->params['breadcrumbs'][] = $this->title;
?>

<h1>ext/<?=$this->title?></h1>

Class : yii\widgets\Breadcrumbs

<?php
echo $breadcrumbs = yii\widgets\Breadcrumbs::widget([
    'itemTemplate' => "<li><i>{link}</i></li>\n", // template for all links
    'links' => [
        [
            'label' => 'Post Category',
            'url' => ['post-category/view', 'id' => 10],
            'template' => "<li><b>{link}</b></li>\n", // template for this link only
        ],
        ['label' => 'Sample Post', 'url' => ['post/edit', 'id' => 1]],
        'Edit',
    ],
    // tambahan /optional
    'tag'=> 'ul',  // default 'ul'
    'options'=> ['class' => 'breadcrumb'], // defult : ['class' => 'breadcrumb']
    'encodeLabels'=> true, // default : true
    'homeLink'=>null, // default : null - if not set a link pointing to [[\yii\web\Application::homeUrl]]
    'itemTemplate'=> "<li>{link}</li>\n",  // default : "<li>{link}</li>\n"
    'activeItemTemplate'=> "<li class=\"active\">{link}</li>\n",  // default : "<li class=\"active\">{link}</li>\n"
    
]);
 ?>
 