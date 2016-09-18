<?php
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>

<?php $this->registerCssFile('@web/css/site.css'); ?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    
    <body>
        <?php $this->beginBody() ?>
        
        <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => ['class' => 'navbar-inverse navbar-fixed-top'],
        ]);

        if (!empty($this->params['top-menu']) && isset($this->params['nav-items'])) {
            echo Nav::widget([
                'options' => ['class' => 'nav navbar-nav'],
                'items' => $this->params['nav-items'],
            ]);
        }

        echo Nav::widget([
            'options' => ['class' => 'nav navbar-nav navbar-right'],
            'items' => [
                ['label' => 'Assignments Manager', 'url' => ['/authTest']],
                ['label' => 'Authorization Test', 'url' => ['/authTest/access-test/index']],
                ['label' => 'Signup', 'url' => ['/site/signup'], 'visible' =>Yii::$app->user->isGuest],
                Yii::$app->user->isGuest ?
                ['label' => 'Login', 'url' => ['/site/login']] :
                [
                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ],
            ],
         ]);
        NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p class="pull-right"><?= Yii::powered() ?></p>
            </div>
        </footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
