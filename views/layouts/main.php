<?php
# USING NAMESPACE - see the class for detail
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use giovdk21\yii2SyntaxHighlighter\SyntaxHighlighter;

/* @var $this \yii\web\View */
/* @var $content string */

# asset bundles instead of using CSS and JavaScript directly.. see asset manager  fro detail.
AppAsset::register($this);  //OR : \frontend\assets\AppAsset::register($this);


// $sc = new SyntaxHighlighter;
// $sc->run();

SyntaxHighlighter::widget(['brushes'=>['php']]);

//SyntaxHighlighter::begin(['brushes'=>['php']]);
// $code ='php $var="hellow word.."; ';
//SyntaxHighlighter::end();
// echo SyntaxHighlighter::getBlock($code, 'php');
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->registerCss(".container{ width: 100% } .ui-tabs .ui-tabs-panel, ui-tabs-panel pre{ font-size:70%}"); ?>
<!--
If you want to specify additional properties of the style tag, pass an array of name-values to the third argument. 
If you need to make sure there's only a single style tag use fourth argument as was mentioned in meta tags description.

$this->registerCssFile("http://example.com/css/themes/black-and-white.css", [
    'depends' => [BootstrapAsset::className()],
    'media' => 'print',
], 'css-print-theme');

$this->registerJs("var options = ".json_encode($options).";", View::POS_END, 'my-options');
$this->registerJsFile('http://example.com/js/main.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
registerCssFile()
--->

<?php $this->beginBody() ?>
    <div class="wrap">
        <!------------ MAIN MENU -------------->
		<?= $this->render('_menu'); ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

<?php
$this->registerJs("
    function create_toggle(obj, toggle){   
        var txt = (obj.text()=='Create') ? 'Cancel' : 'Create'; 
        obj.text(txt);
        if(toggle)
            $('#create_div').toggle(100);
        else $('#create_div').hide(100);
    }
    
    function search_toggle(obj, toggle){   
        var txt = (obj.text().toLowerCase()=='search') ? 'Cancel' : 'Search'; 
        obj.text(txt);
        if(toggle)
            $('#search_div').toggle(100);
        else $('#search_div').hide(100);
    }
    ",  \yii\web\View::POS_END
);
?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
