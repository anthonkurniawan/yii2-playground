<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-left'],
    'items' => [
        [
            'label' => 'Home', 
            'items'=>[
                ['label' => 'Home', 'url' => ['/site/index'], 'visible' => Yii::$app->user->isGuest],
                ['label' => 'About', 'url' => ['/site/about'], 'visible' => Yii::$app->user->isGuest],
                ['label' => 'Contact', 'url' => ['/site/contact'], 'visible' => Yii::$app->user->isGuest],
            ],
        ],
        ['label'=>'Documetations', 'url'=>'http://127.0.0.1/YII2DOC/', 'linkOptions'=>['target'=>'_blank']],
    ],
]);

echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => [
        [
            'label' => 'Controller',
            'items' => [
                ['label' => 'Controller Details', 'url' => ['/test/index']],
                ['label' => 'Test Error Handler', 'url' => ['/test/fakeurl']],
                ['label' => 'standalone action', 'url' => ['/test/testStandaloneAction']],
                // '<li class="divider"></li>',
                // ['label' => 'Custom data paging', 'url' => ['/users/custom_data_paging']],
            ],
        ],
        [
            'label' => 'Model',
            'items' => [
                ['label' => 'Db Test', 'url' => ['/test/db']],
            ],
        ],
        [
            'label' => 'Sample',
            'items' => [
                ['label' => 'helpers', 'url' => ['/sample/helpers']],
                '<li class="divider"></li>',
                '<li class="dropdown-header">Widgets</li>',  
                ['label' => 'ListView', 'url' => ['/sample/listview']],
                ['label' => 'Grid', 'url' => ['/sample/grid']],
                ['label' => 'Grid Multi', 'url' => ['/sample/grid-multi']],
                ['label' => 'Paging & Sort', 'url' => ['/sample/paging-sort']],
                ['label' => 'PJAX', 'url' => ['/sample/pjax']],
                ['label' => 'Block', 'url' => ['/sample/block']],
                ['label' => 'Breadcrumbs', 'url' => ['/sample/breadcrumbs']],
                ['label' => 'ContentDecorator', 'url' => ['/sample/ContentDecorator']],
                ['label' => 'X', 'url' => ['/sample/X']],
                '<li class="divider"></li>',
                '<li class="dropdown-header">Helpers</li>',
                ['label' => 'Markdown', 'url' => ['/sample/markdown']],
                ['label' => 'X', 'url' => ['/sample/X']],
                '<li class="divider"></li>',
                '<li class="dropdown-header">Extensions</li>',
                ['label' => 'Code Mirror', 'url' => ['/sample/codemirror']],
                ['label' => 'My FileTree', 'url' => ['/sample/my-file-tree']],
                '<li class="divider"></li>',
                '<li class="dropdown-header">Template Engine</li>',
                ['label' => 'Twig Sample', 'url' => ['/sample/twig-sample']],
                ['label' => 'Smarty Sample', 'url' => ['/sample/smarty-sample']],
            ],
        ],
        [
            'label' => 'Tools',
            'items' => [
                ['label' => 'Fetch Kelas', 'url' => ['/tools/kelas']],
                ['label' => 'Code Test', 'url' => ['/tools/code_test']],
                ['label' => 'Code Sample Library', 'url' => ['/code-sample/index']],
            ],
        ],
        [
            'label' => 'Data',
            'items' => [
                ['label' => 'Users Yiitest', 'url' => ['/user_yiitest/index']],
                ['label' => 'User', 'url' => ['/user/index']],
                '<li class="divider"></li>',
                '<li class="dropdown-header">Dropdown Header</li>',
                
            ],
        ],
		[
            'label' => 'Module',
            'items' => [
                ['label' => 'Test Module', 'url' => ['/modtest'], 'linkOptions' => ['target' => '_blank']],
                ['label' => 'Auth Module', 'url' => ['/authTest'], 'linkOptions' => ['target' => '_blank']],
            ],
        ],
        ['label' => 'GII', 'url' => ['/gii'], 'linkOptions' => ['target' => '_blank'],],
        ['label' => 'Signup', 'url' => ['/site/signup'], 'visible' =>Yii::$app->user->isGuest],
        Yii::$app->user->isGuest ?
                ['label' => 'Login', 'url' => ['/site/login']] :
                ['label' => 'Logout (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']],
    ],
]);
NavBar::end();
?>