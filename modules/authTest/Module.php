<?php

namespace app\modules\authTest;

use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface  # WITH bootstrap load
{
    public $controllerNamespace = 'app\modules\authTest\controllers';
    public $layout = 'main.php';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
    
     /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {     
        if ($app instanceof \yii\web\Application) {
            $app->getUrlManager()->addRules([
                $this->id => $this->id . '/default/index',
                $this->id . '/<id:\w+>' => $this->id . '/default/view',
                // $this->id . '/assign/<id:\d+>' => $this->id . '/default/assign',
                $this->id . '/<action:assign>/<id:\d+>' => $this->id . '/default/<action>',
                $this->id . '/<action:[\w\-]+>' => $this->id . '/default/<action>',  // http://127.0.0.1/yii2-test/authTest/load-auth
                $this->id . '/<controller:[\w\-]+>/<action:[\w\-]+>' => $this->id . '/<controller>/<action>',
                // $this->id . '/<controller:[\w\-]+>/<action:[\w\-]+>/<id:\d+>' => $this->id . '/<controller>/<action>',	
                $this->id . '/<controller:[\w\-]+>/<action:view|update|delete>/<name:[\w\s]+>/<type:.*?>' => $this->id . '/<controller>/<action>',	
                //$this->id . '/<controller:[\w\-]+>/<action:delete>/<name:[\w.]+>' => $this->id . '/<controller>/<action>',	
                $this->id . '/<controller:[\w\-]+>/create/<type:[\w.]+>' => $this->id . '/<controller>/create',	
            ], false);
        } elseif ($app instanceof \yii\console\Application) {
            $app->controllerMap[$this->id] = [
                'class' => 'yii\gii\console\GenerateController',
                'generators' => array_merge($this->coreGenerators(), $this->generators),
                'module' => $this,
            ];
        }
    }

}
