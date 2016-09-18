<?php
/**
They are objects of classes extending from yii\base\Controller and are responsible for processing requests and generating responses. In particular, after taking over the control from applications, controllers will analyze incoming request data, pass them to models, inject model results into views, and finally generate outgoing responses.

#CONTROLLER IDS
----------------------------
By default, controller IDs should contain these characters only: English letters in lower case, digits, underscores, dashes and forward slashes. For example, article and post-comment are both valid controller IDs, while article?, PostComment, admin\post are not.

A controller ID may also contain a subdirectory prefix. For example, admin/article stands for an article controller in the admin subdirectory under the controller namespace. Valid characters for subdirectory prefixes include: English letters in lower and upper cases, digits, underscores and forward slashes, where forward slashes are used as separators for multi-level subdirectories (e.g. panels/admin).

Controller Class Naming
Controller class names can be derived from controller IDs according to the following rules:

Turn the first letter in each word separated by dashes into upper case. Note that if the controller ID contains slashes, this rule only applies to the part after the last slash in the ID.
Remove dashes and replace any forward slashes with backward slashes.
Append the suffix Controller.
And prepend the controller namespace.
The followings are some examples, assuming the controller namespace takes the default value app\controllers:

article derives app\controllers\ArticleController;
post-comment derives app\controllers\PostCommentController;
admin/post-comment derives app\controllers\admin\PostCommentController;
adminPanels/post-comment derives app\controllers\adminPanels\PostCommentController.
Controller classes must be autoloadable. For this reason, in the above examples, the article controller class should be saved in the file whose alias is @app/controllers/ArticleController.php; while the admin/post2-comment controller should be in @app/controllers/admin/Post2CommentController.php.

Info: The last example admin/post2-comment shows how you can put a controller under a sub-directory of the controller namespace. This is useful when you want to organize your controllers into several categories and you do not want to use modules.

# Controller Map
------------------------
You can configure controller map to overcome the constraints of the controller IDs and class names described above. This is mainly useful when you are using some third-party controllers which you do not control over their class names.

You may configure controller map in the application configuration like the following:

[
    'controllerMap' => [
        // declares "account" controller using a class name
        'account' => 'app\controllers\UserController',

        // declares "article" controller using a configuration array
        'article' => [
            'class' => 'app\controllers\PostController',
            'enableCsrfValidation' => false,
        ],
    ],
]
*/
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

use app\models\Auth;
use app\models\User;
use app\models\Profile;
use app\models\LoginForm;
use app\models\ContactForm;
# add like from yii2-adv/fronted
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\SignupForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;


# Similarly in console applications, controllers should extend from yii\console\Controller or its child classes

# A controller ID may also contain a subdirectory prefix. For example, admin/article stands for an article controller in the admin subdirectory under the controller namespace. Valid characters for subdirectory prefixes include: English letters in lower and upper cases, digits, underscores and forward slashes, where forward slashes are used as separators for multi-level subdirectories (e.g. panels/admin).
# By default, controller IDs should contain these characters only: English letters in lower case, digits, underscores, dashes and forward slashes
class SiteController extends Controller # "Controller" extend from : "yii\web\Controller"  extends "\yii\base\Controller"
{
	# If you want to change the default value, simply override this property in the controller class, like the following:
	public $defaultAction = 'index';
	
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['login', 'signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [         # specifies which request method (e.g. GET, POST) this rule matches. The comparison is case-insensitive.
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',  // if page not found
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
              'class' => 'yii\authclient\AuthAction',
              'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function actionIndex()
    {	
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function onAuthSuccess($client)
    {
       $attributes = $client->getUserAttributes();  //echo "<pre>"; print_r($attributes); print_r($client); echo "</pre>"; die();

        /** @var Auth $auth */
        $auth = Auth::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();
        
        if (Yii::$app->user->isGuest) {
            if ($auth) { // login
                $user = $auth->user;
                Yii::$app->user->login($user);
            } else { // signup
                if (isset($attributes['email']) && isset($attributes['username']) && User::find()->where(['email' => $attributes['email']])->exists()) {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "User with the same email as in {client} account already exists but isn't linked to it. Login using email first to link it.", ['client' => $client->getTitle()]),
                    ]);
                } else {
                    $password = Yii::$app->security->generateRandomString(6);
                    $user = new User([
                        'scenario'=>'create',
                        'username' => $attributes['first_name'],
                        'email' => $attributes['email'],
                        'password' => $password,
                    ]);
                    $profile = new Profile([
                        'firstname' => isset($attributes['first_name']) ? $attributes['first_name'] : '',
                        'lastname' => isset($attributes['last_name']) ? $attributes['last_name'] : '',
                        //'fullname' => isset($attributes['name']) ? $attributes['name'] : '',
                        'address' => isset($attributes['location']) ? $attributes['location']['name'] : '',
                        'birthday' => isset($attributes['birthday']) ? $attributes['birthday'] : '', 
                        'bio' => isset($attributes['bio']) ? $attributes['bio'] : '',
                        'gender' => isset($attributes['gender']) ? $attributes['gender'] : '',
                    ]);
                    $user->generateAuthKey();
                    $user->generatePasswordResetToken();
                    $transaction = $user->getDb()->beginTransaction();
                    if ($user->save() && $profile->save()) {
                        $auth = new Auth([
                            'user_id' => $user->id,
                            'source' => $client->getId(),
                            'source_id' => (string)$attributes['id'],
                        ]);
                        if ($auth->save()) {
                            $transaction->commit();
                            Yii::$app->user->login($user);
                        } else {
                            print_r($auth->getErrors());
                        }
                    } else {
                        print_r($user->getErrors());
                        print_r($profile->getErrors());
                    }
                }
            }
        } else { // user already logged in
            if (!$auth) { // add auth provider
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $client->getId(),
                    'source_id' => $attributes['id'],
                ]);
                $auth->save();
            }
        }
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    # COPY FROM YII2-ADV fronted/controllers/siteController --------------------------------------------
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                //return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }
        
        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');
echo "<br><br><br><br><br><br><br><br><br><br> SUCCESS"; 
            return $this->goHome();
        }  else echo "<br><br><br><br><br><br><br><br><br><br>"; print_r($model->errors);

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}

/**
Controller Lifecycle
----------------------------
When processing a request, an application will create a controller based on the requested route. The controller will then undergo the following lifecycle to fulfill the request:

1. The yii\base\Controller::init() method is called after the controller is created and configured.
2. The controller creates an action object based on the requested action ID:
 - If the action ID is not specified, the default action ID will be used.
 - If the action ID is found in the action map, a standalone action will be created;
 - If the action ID is found to match an action method, an inline action will be created;
 - Otherwise an yii\base\InvalidRouteException exception will be thrown.
3. The controller sequentially calls the beforeAction() method of the application, the module (if the controller belongs to a module) and the controller.
 - If one of the calls returns false, the rest of the uncalled beforeAction() will be skipped and the action execution will be cancelled.
 - By default, each beforeAction() method call will trigger a beforeAction event to which you can attach a handler.
4. The controller runs the action:
 - The action parameters will be analyzed and populated from the request data;
5. The controller sequentially calls the afterAction() method of the controller, the module (if the controller belongs to a module) and the application.
 - By default, each afterAction() method call will trigger an afterAction event to which you can attach a handler.
6. The application will take the action result and assign it to the response.
*/

