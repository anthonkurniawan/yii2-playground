<?php
//Yii::setAlias( 'ext-coll',  __DIR__.'/../../yiiProject/YII-EXTENSIONS');	//echo  realpath(__DIR__.'/../../yiiProject/YII-EXTENSIONS');  die();

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic', // recommended that you use alphanumeric characters - The id property specifies a unique ID that differentiates an application from others. It is mainly used programmatically
    'basePath' => dirname(__DIR__),  // @app is alias for basePath - specifies the root directory of an application. It is the directory that contains all protected source code of an application system.
	'name'=>'YII2-BASIC-TEST',
	'language'=>'id',  //SET DEFAULT LANG ( for switclang menu)
	'timeZone' => "Asia/Jakarta", //'America/Los_Angeles',  // By configuring this property, you are essentially calling the PHP function date_default_timezone_set()
    'defaultRoute'=>'site', # specified via the yii\base\Application::$defaultRoute property
	'version' => '1.0',  // version of app
	'charset' => 'UTF-8', // The default value is 'UTF-8' which should be kept as is for most applications unless you are working with some legacy systems that use a lot of non-unicode data.
	'sourceLanguage' => 'en-US',
	'layout' => 'main', // This property specifies the name of the default layout that should be used when rendering a view. default : 'main',
    'layoutPath'=>'@app/views/layouts' , // specifies the path where layout files should be looked for.default :  '@app/views/layouts'
    'runtimePath'=>'@app/runtime' , // specifies the path where temporary files, such as log files, cache files, can be generated. default : '@app/runtime'
    'viewPath'=> '@app/views', // specifies the root directory where view files are located.. default : '@app/views'
    'vendorPath'=>'@app/vendor', // specifies the vendor directory managed by Composer. It contains all third party libraries used by your application, including the Yii framework. default: '@vendor'
	//'enableCoreCommands'=>true, // This property is supported by console applications only. It specifies whether the core commands included in the Yii release should be enabled. default: true.
    
    # Sometimes, however, you may want to instantiate an application component for every request, even if it is not explicitly accessed. 
	# To do so, you may list its ID in the bootstrap property of the application.
   // 'bootstrap' => ['log', 'linkPager'], // log, gii
	'aliases' => [
		// '@src' => __DIR__.'/../../../yiiProject/YII-EXTENSIONS/SrcCollect', // change this if necessary
        '@yiiExt' => __DIR__.'/../../../yiiProject/YII-EXTENSIONS', 
        '@YiiNodeSocket' => '@app/extensions/yii-node-socket/lib/php',
        '@nodeWeb' => '@app/extensions/yii-node-socket/lib/js'
		//'@name1' => 'D:\WEB\FRAMEWORKS\YII2-BASIC\vendor',
		// '@name2' => 'path/to/path2',
        # This property is provided such that you can define aliases in terms of application configurations instead of the method calls Yii::setAlias().
	],
		
	#You can register any component with an application, and the component can later be accessed globally using the expression \Yii::$app->ComponentID.
    ## OR ADD SEPARATE FILE "return array" LIKE : 'components' => require(__DIR__ . '/components.php'),
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'aj947OA_u4lNuCExVpxqcxhHvRjsbtEd',
        ],
        // 'response' => [
            // 'class' => 'yii\web\Response',
            // 'on beforeSend' => function ($event) {  //print_r($event); die(); //print_r(Yii::$app->request->get('suppress_response_code')); die();
                // $response = $event->sender;  //print_r($response->data); die();
                // // if ($response->data !== null && !empty(Yii::$app->request->get('suppress_response_code'))) {
                // if ($response->data !== null) {
                    // $response->data = [
                        // 'success' => $response->isSuccessful,
                        // 'data' => $response->data,
                    // ];
                    // $response->statusCode = 200;
                // }
            // },
        // ],
        'formatter' => [
            // 'defaultTimeZone' => "Asia/jakarta",
            'dateFormat' => 'dd.MM.yyyy',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'Rp',
       ],
       # default : ga di set
       'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
                'test' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    //'sourceLanguage' => 'en-US',
                ],
            ],
        ],
        'cache' => [
            //'class'=>'CMemCache',	//CMemCache, CDbCache
            'class' => 'yii\caching\FileCache',
            // 'class' => 'yii\caching\MemCache',
            // 'servers' => [
                // [
                    // 'host' => 'locakhost',
                    // 'port' => 11211,
                    // 'weight' => 100,
                // ],
                // [
                    // 'host' => 'server2',
                    // 'port' => 11211,
                    // 'weight' => 50,
                // ],
            // ],
        ],
        # yii\web\User class
        'user' => [
            'identityClass' => 'app\models\User',  // User must implement the IdentityInterface
            'enableAutoLogin' => true,  // def: false - whether to enable cookie-based login.  this property will be ignored if [[enableSession]] is false.
            # whether to use session to persist authentication status across multiple requests. set this property to be false if your application is stateless, which is often the case for RESTful APIs.
            'enableSession'=> true, // def: true 
            #  seconds in which the user will be logged out automatically if user remains inactive. If this property is not set, the user will be logged out after the current session expires (c.f. [[Session::timeout]]).
            'authTimeout' =>null, // def: null  - # Note that this will not work if [[enableAutoLogin]] is true.
            # nteger the number of seconds in which the user will be logged out automatically regardless of activity.
            'absoluteAuthTimeout'=>null, // def: null - Note that this will not work if [[enableAutoLogin]] is true.
            # whether to automatically renew the identity cookie each time a page is requested. is effective only when [[enableAutoLogin]] is true.
            # When this is false, the identity cookie will expire after the specified duration since the user is initially logged in. 
            # When this is true, the identity cookie will expire after the specified duration since the user visits the site the last time.
            'autoRenewCookie' => true, // def: true 
            'loginUrl' => ['site/login'], // Note : set null if dedicate for RESTful APIs.
        ],
        # AuthClient Extension for Yii 2
        /*
        CREATE TABLE auth (
            id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            user_id int(11) NOT NULL,
            source varchar(255) NOT NULL,
            source_id varchar(255) NOT NULL
        );

        ALTER TABLE auth ADD CONSTRAINT fk_auth_user
        FOREIGN KEY (user_id) REFERENCES user(id);
        */
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\GoogleOpenId'
                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '525925670852614',
                    'clientSecret' => '90ef2afb183b6c03268ff1a69a4c5648',
                ],
                // etc.
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            //'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['reader'], //['admin', 'author'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set  'useFileTransport' to false and configure a transport for the mailer to send real emails.
            'useFileTransport' => true,
            // 'transport' => [
                // 'class' => 'Swift_SmtpTransport',
                // 'host' => "ssl://smtp.gmail.com", //'localhost',
                // 'username' => 'blaquecry@gmail.com',
                // 'password' => 'valentine',
                // 'port' => '465', //'587',
                // 'encryption' => 'tls',
            // ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'db_yiitest' =>[
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yiiTest',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'linkPager' => \Yii::$container->set('yii\widgets\LinkPager', [
            'maxButtonCount' => 1,
        ]),
        // register "search" component using an anonymous function
        'search' => function () {
            return new app\components\SolrService;  // LOM DI SETUP
        },
        # http://www.yiiframework.com/doc-2.0/yii-web-urlmanager.html
		'urlManager' => [
			'enablePrettyUrl' => true,
            'showScriptName' => false,
            # whether to enable strict parsing. If strict parsing is enabled, the incoming requested URL must match at least one of the [[rules]] in order to be treated as a valid request.
            # Otherwise, the path info part of the request will be treated as the requested route.This property is used only when [[enablePrettyUrl]] is true.
            'enableStrictParsing' => false,  // def: false
			'rules' => [
				'dashboard' => 'site/index',
                'POST <controller:\w+>s' => '<controller>/create',
                '<controller:\w+>s' => '<controller>/index',
                //'POST <controller:\w+>/<id:\d+>' => '<controller>/update',
                'DELETE <controller:\w+>/<id:\d+>' => '<controller>/delete',
                'code-sample/<id:\d+>/<slug:[-a-zA-Z]+>' => 'code-sample/view',
                //'code-sample/<id:\d+>/<slug>' => 'code-sample/view',
                // 'product/<slug:[a-zA-Z0-9-]+>/'=>'product/view',
                // '<view:[a-zA-Z0-9-]+>/'=>'site/page',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                
                # REST api rules ( in main appa web config)
                // 'PUT,PATCH rest/users/<id>' => 'rest/user/update', # update a user
                // 'DELETE rest/users/<id>' => 'rest/user/delete', # delete a user
                // 'GET,HEAD rest/users/<id>' => 'rest/user/view', # return the details/overview/options of a user
                // 'POST rest/users' => 'rest/user/create', # create a new user
                // 'GET,HEAD rest/users' => 'rest/user/index', # return a list/overview/options of users
                // 'rest/users/<id>' => 'rest/user/options', # process all unhandled verbs of a user
                // 'rest/users' => 'rest/user/options', # process all unhandled verbs of user collection
			],
		],
        // NODE SOCKET
        // 'nodeSocket' => array(
            // 'class' => '@app/extensions/yii-node-socket/lib/php/NodeSocket',
            // 'host' => 'localhost',  // default is 127.0.0.1, can be ip or domain name, without http
            // 'port' => 3001      // default is 3001, should be integer
        // ),
       # Class yii\base\View
        'view' => [
            'theme' => [     # YII THEME CONGIG - THEN create the folder name as "themes" into web directory.
                'pathMap' => ['@app/views' => '@app/themes/basic'],
                'baseUrl' => '@web/themes/basic',
            ],
            'on endBody' => function ($event) {
                //Yii::info("Keyword searched: " . $event->keyword);
                echo date('Y-m-d');
            },
            # SAMPLE USING TEMPLATE ENGINE
            'renderers' => [
                'tpl' => [
                    'class' => 'yii\smarty\ViewRenderer',
                    //'cachePath' => '@runtime/Smarty/cache',
                ],
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/Twig/cache',
                    // Array of twig options:
                    'options' => [
                        'auto_reload' => true,
                    ],
                    'globals' => ['html' => '\yii\helpers\Html'],
                    'uses' => ['yii\bootstrap'],
                ],
                // ...
            ],
        ],
    ],
    #MODULES CONFIG
	'modules'=>[
		// a "comment" module specified with a configuration array
        // 'comment' => [
            // 'class' => 'app\modules\comment\CommentModule',
            // 'db' => 'db',
        // ],
        'modtest'=>[ 'class'=>'app\modules\modtest\Module'],
        'authTest'=>[ 'class'=>'app\modules\authTest\Module'],
        'rest' => [
            'class' => 'app\modules\rest\Module',
        ],
	],
    'bootstrap'=>['authTest', 'rest'],   # TEST ADD RULE URL IN MODULE "authTest"
	# This is mainly used when the application is in maintenance mode and needs to handle all incoming requests via a single action.
	'catchAll' => [
        // 'offline/notice',  // conroller/action
        // 'param1' => 'value1',  // given param
        // 'param2' => 'value2',
    ],
    # By configuring this property, you can break the convention for specific controllers. In the following example, account will be mapped to app\controllers\UserController
	'controllerMap' => [
        [
			'about'=>'app\controllers\UsersController',
            // 'account' => 'app\controllers\UserController',
            // 'article' => [
                // 'class' => 'app\controllers\PostController',
                // 'enableCsrfValidation' => false,
            // ],
        ],
    ],
    ## NOTE : IF SET AT HERE THEN DEBUG WILL NOT WORK
	#  By default, it will take the array returned by the file @vendor/yiisoft/extensions.php
	# The extensions.php file is generated and maintained automatically when you use Composer to install extensions. So in most cases, you do not need to configure this property.
	# In the special case when you want to maintain extensions manually, you may configure this property like the following:
	#  An extension may also define a few aliases.
    ## OR USE SEPARATE FILE  "return array" LIKE : 'extensions' => require(__DIR__ . '/../vendor/yiisoft/extensions.php'),
	// 'extensions' => [
        // [
            // 'name' => 'sideMenu',  // extension name
            // 'version' => '1.0',
			// # If an extension needs to run during the bootstrap process, a bootstrap element may be specified with a bootstrapping class name or a configuration array
            // //'bootstrap' => 'BootstrapClassName',  // optional, may also be a configuration array
            // 'alias' => [  // optional
                // '@my' => '@vendor/yiisoft/sideMenu',  //$vendorDir . '/yiisoft/sideMenu',
                // //'@alias2' => 'to/path2',
            // ],
        // ],
    // ],
	#Specifies an array of globally accessible application parameters
    'params' => $params,  // Call/access global param : \Yii::$app->params['item_param];
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

	# ADD GII MODULES
	# OR ADD ON ABOVE :
	// 'bootstrap' => ['gii'],
    // 'modules' => [ 'gii' => 'yii\gii\Module',]
		
    $config['bootstrap'][] = 'gii';
    // $config['modules']['gii'] = 'yii\gii\Module'; (DEFAULT)
    # Note: if you are accessing gii from an IP address other than localhost, access will be denied by default. 
    # To circumvent that default, add the allowed IP addresses to the configuration:
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.178.20'] // adjust this to your needs
    ];

    # TEST CREATED MODULES
    //$config['modules']['modtest'] = 'app\modules\modtest\Module'; 
    
}

return $config;
