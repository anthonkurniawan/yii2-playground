<?php
namespace app\modules\rest\controllers;

use yii\rest\ActiveController;
use yii\web\Response;
# AUTH METHODS
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;

/**
# "ActiveController" WITH BUILT-IN ACTION DEFAULT
 * - `index`: list of models
 * - `view`: return the details of a model
 * - `create`: create a new model
 * - `update`: update an existing model
 * - `delete`: delete an existing model
 * - `options`: return the allowed HTTP methods
 * you may consider extending your controller classes from yii\rest\ActiveController, which will allow you to create powerful RESTful APIs with minimal code.
 **/
 
class UserController extends ActiveController 
{
    public $modelClass = 'app\models\User';
    public $updateScenario = 'update';
    public $createScenario = 'create';
    
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
    NOTE : You may override this method to configure individual filters, disable some of them, or add your own filters.
    DEFAULT BEHAVIORS PARENT :
        return [
            "contentNegotiator"=> ["class"=> "yii\filters\ContentNegotiator", "formats"=> ["application/json"=> "json", "application/xml"=> "xml"] ],
            "verbFilter"=> [
                "class"=> "yii\filters\VerbFilter", 
                "actions"=> [
                    "index"=> ["GET", "HEAD"], 
                    "view"=> ["GET", "HEAD"], 
                    "create"=> ["POST"], 
                    "update"=> ["PUT", "PATCH"], 
                    "delete"=> ["DELETE"]
                ],
            ],
            "authenticator"=> ["class"=> "yii\filters\auth\CompositeAuth"],
            "rateLimiter"=> ["class"=> "yii\filters\RateLimiter"],
        ]
    **/
    public function behaviors()
    {
        $behaviors = parent::behaviors();     //print_r($behaviors); die(); 
        # ADD NEW ACCECPT RESPONSE TYPE
        //$behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_HTML;   # RESULT ARRAY - ERROR : "Response content must not be an array."
        //\yii\helpers\VarDumper::dump($behaviors, 10); die();
        
        # SAMPLE SINGLE AUTH METHOD
        // $behaviors['authenticator'] = [
            // 'class' => HttpBasicAuth::className(),
            // 'auth' =>function ($username, $password) {
                // $model = new \app\models\LoginForm(['username'=>$username, 'password'=>$password]); //print_r($model->getUser()->password_hash); die();
                // if($user = $model->getUser()){
                    // $password_hash = $user->password_hash;
                     // return \app\models\User::findOne([
                         // 'username' => $username,
                         // 'password_hash' => $password_hash,
                     // ]);
                // }
                // return;
            // }
        // ];
        
        # SAMPLE MULTI AUTH METHODS
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            # Each element in authMethods should be an auth method class name or a configuration array.
            'authMethods' => [
                // HttpBasicAuth::className(),
                [
                    'class' => HttpBasicAuth::className(),
                    'auth' =>function ($username, $password) {
                        $model = new \app\models\LoginForm(['username'=>$username, 'password'=>$password]); //print_r($model->getUser()->password_hash); die();
                        if($user = $model->getUser()){
                            $password_hash = $user->password_hash;
                             return \app\models\User::findOne([
                                 'username' => $username,
                                 'password_hash' => $password_hash,
                             ]);
                        }
                        return;
                    }
                ],
                HttpBearerAuth::className(),
                QueryParamAuth::className(),
            ],
        ];
        
        # if wanto to hide response header X-Rate-Limit-xxx
        //$behaviors['rateLimiter']['enableRateLimitHeaders'] = false;

        return $behaviors;
    }
    
    #OVERRIDE PARENT
    /**
     * Checks the privilege of the current user.
     *
     * This method should be overridden to check whether the current user has the privilege
     * to run the specified action against the specified data model.
     * If the user does not have access, a [[ForbiddenHttpException]] should be thrown.
     *
     * @param string $action the ID of the action to be executed
     * @param object $model the model to be accessed. If null, it means no specific model is being accessed.
     * @param array $params additional parameters
     * @throws ForbiddenHttpException if the user does not have access
     */
    public function checkAccess($action, $model = null, $params = [])
    {  //echo \Yii::$app->user->id; die();
       //echo $model; 
       //print_r($action); die();
       $permission = $action .'User';
        if( ! \Yii::$app->user->can($permission, $params))
            // throw new \yii\web\HttpException(404, 'The requested Item could not be found.');
            throw new \yii\web\ForbiddenHttpException("You dont have permission for '$permission' request");
    }

}

/**
Authorization
After a user is authenticated, you probably want to check if he or she has the permission to perform the requested action for the requested resource. This process is called authorization which is covered in detail in the Authorization section.

If your controllers extend from yii\rest\ActiveController, you may override the yii\rest\Controller::checkAccess() method to perform authorization check. The method will be called by the built-in actions provided by yii\rest\ActiveController.
**/