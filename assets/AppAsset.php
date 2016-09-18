<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}

/* --------------------------------------- How to Integrate Yii2 with fantastic theme AdminLTE
Add your composer.json
"bower-asset/admin-lte": "*"
Change your AppAsset.php and check to referring it in your views/layouts/main.php
class AppAsset extends AssetBundle
{
    public $sourcePath = '@bower/';
    public $css = ['admin-lte/css/AdminLTE.css'];
    public $js = ['admin-lte/js/AdminLTE/app.js'];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
Change your views adhering to html markup vendor/bower/admin-lte/pages
*/