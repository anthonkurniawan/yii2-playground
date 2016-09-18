<?php
Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        // '@YiiNodeSocket' => '@vendor/oncesk/yii-node-socket/lib/php',
        '@YiiNodeSocket' => '@app/extensions/yii-node-socket/lib/php',
        '@nodeWeb' => '@app/extensions/yii-node-socket/lib/js'
    ],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'defaultRoles' => ['admin', 'author'],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'nodeSocket' => array(
            // 'class' => 'application.extensions.yii-node-socket.lib.php.NodeSocket',
            'class' => '@app/extensions/yii-node-socket/lib/php/NodeSocket',
            'host' => 'localhost',  // default is 127.0.0.1, can be ip or domain name, without http
            'port' => 3001      // default is 3001, should be integer
        )
    ],
    'params' => $params,
    'controllerMap' => array(
        //  'node-socket' => 'my\SideMenu'
        'node-socket' => '\YiiNodeSocket\NodeSocketCommand',
    )
];
