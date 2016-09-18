<?php
namespace app\modules\rest;

use yii\base\BootstrapInterface;

class Module extends \yii\base\Module //implements BootstrapInterface
{
    public $controllerNamespace = 'app\modules\rest\controllers';

    ## NOTE : 
    # 1. if "urlManager" using main app, ONLY CAN ACCESS "user/index", AND METHOD ALOWWED IS "GET, HEAD."
        // or edit "urlManager" and add rules in main app config manually
        // "init()" function GAP PERLU DI SET
    # 2. if using in "Module" set loading bootrap for overriding the "urlMAnager" like sample below, 
        // controller ID used in the pattern will be automatically pluralized (e.g. `user` becomes `users`

    public function init()
    {
        parent::init();
        // initialize the module with the configuration loaded from config.php
        //\Yii::configure($this, require(__DIR__ . '/config.php'));
                                                
        $config = [
            'components' => [
                // 'urlManager' => [
                    // 'class'=>'yii\web\UrlManager',
                    // 'enablePrettyUrl' => true,
                    // 'enableStrictParsing' => true,
                    // 'showScriptName' => false,
                    // 'rules' => [
                        // [
                            // 'class' => 'yii\rest\UrlRule', 
                            // 'controller' => ['rest/user', 'rest/code-sample'],
                            // // 'except' => [
                                // // 'delete',
                            // // ],
                            // // 'extraPatterns' => [
                                // // 'POST login' => 'login',
                                // // 'POST logout' => 'logout',
                                // // 'POST forgot' => 'forgot',
                            // // ],
                        // ],
                        // 'GET,HEAD rest/users/<id>' => 'rest/user/view', # return the details/overview/options of a user
                        // //$this->id . '/<id:\w+>' => $this->id . '/default/view',
                        // //$this->id . '/<controller:[\w\-]+>/<action>/<id:\w+>' => $this->id . '/<controller>/<action>',
                        // //'rest/user/<id:\w+>' =>'rest/user/view',
                    // ],
                // ],
                'user' => [
                    'class' => 'yii\web\User', 
                    'identityClass' => 'app\models\User', 
                    'enableSession'=> false,
                    'loginUrl' => null,
                ],
                'request' => [
                    # Without this configuration, the API would only recognize application/x-www-form-urlencoded and multipart/form-data input formats.
                    'class' => 'yii\rest\Request', 
                    'parsers' => [
                        'application/json' => 'yii\web\JsonParser',
                    ]
                ],
            ],
            
            'params' => [
                'test'=>'test',       // list of parameters
            ],
        ];
       // \Yii::configure($this, $config);
       
       //\Yii::$app->user->enableSession = false;   # WILL OVERRIDE MAIN CONFIG APP !!
       //\Yii::$app->urlManager->enableStrictParsing = true;
       \Yii::$app->urlManager->addRules([
            [
                'class' => 'yii\rest\UrlRule',
                'controller' => ['rest/user', 'rest/code-sample'], //'rest/user', # IF SINGLE
                // 'except' => [
                    // 'delete',
                // ],
                // 'extraPatterns' => [
                    // 'POST login' => 'login',
                    // 'POST logout' => 'logout',
                    // 'POST forgot' => 'forgot',
                // ],
            ],
        ], false);
    }
    
    // public function bootstrap($app)
    // {     
        // if ($app instanceof \yii\web\Application) {   // echo "xxxxxxx"; die();
            // //$app->getUrlManager()->enableStrictParsing=true;  # IF IN MODULE SET FALSE
            // $app->getUrlManager()->addRules([
               // [
                    // 'class' => 'yii\rest\UrlRule',
                    // 'controller' => ['rest/user', 'rest/code-sample'], //'rest/user', # IF SINGLE
                    // // 'except' => [
                        // // 'delete',
                    // // ],
                    // // 'extraPatterns' => [
                        // // 'POST login' => 'login',
                        // // 'POST logout' => 'logout',
                        // // 'POST forgot' => 'forgot',
                    // // ],
                // ],
            // ], false);
        // } elseif ($app instanceof \yii\console\Application) {
            // $app->controllerMap[$this->id] = [
                // 'class' => 'yii\gii\console\GenerateController',
                // 'generators' => array_merge($this->coreGenerators(), $this->generators),
                // 'module' => $this,
            // ];
        // }
    // }
}

/*
GET /users: list all users page by page;
HEAD /users: show the overview information of user listing;
POST /users: create a new user;
GET /users/123: return the details of the user 123;
HEAD /users/123: show the overview information of user 123;
PATCH /users/123 and PUT /users/123: update the user 123;
DELETE /users/123: delete the user 123;
OPTIONS /users: show the supported verbs regarding endpoint /users;
OPTIONS /users/123: show the supported verbs regarding endpoint /users/123.
Info: Yii will automatically pluralize controller names for use in endpoints. You can configure this using the yii\rest\UrlRule::$pluralize-property.
*/