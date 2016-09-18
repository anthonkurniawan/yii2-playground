<?php
namespace app\modules\authTest\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;
use yii\web\ForbiddenHttpException;

class AccessTestController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['guest-user', 'authenticated-user', 'ip-user'],
                // 'except'=>	array	List of action IDs that this filter should not apply to.
                'rules' => [
                    [
                        'actions' => ['guest-user'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['authenticated-user'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['ip-user'],
                        'allow' => false,
                        'ips' => ['192.168.*', '127.0.0.1'],  // IP LIST INI GA BOLEH
                        # callable a callback that will be called if this rule determines the access to the current action should be denied.
                        'denyCallback' => function ($rule, $action) {  
                            throw new ForbiddenHttpException(Yii::t('yii', 'Restricted IP'));
                        },
                        // The callback should return a boolean value indicating whether this rule should be applied.
                        'matchCallback' => function ($rule, $action) {  
                            return true;
                            // return date('d-m') === '31-10';
                        },
                    ],
                ],
                'denyCallback' => function ($rule, $action) {   
                    //throw new \Exception('You are `1 not allowed to access this page');
                    throw new ForbiddenHttpException(Yii::t('yii', 'kamohh ga bolee macuk tayang...'));
                },
            ],
            'verbs' => [         # specifies which request method (e.g. GET, POST) this rule matches. The comparison is case-insensitive.
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionGuestUser(){
        return "Access Guest Access - OK";
    }

    public function actionAuthenticatedUser(){
        return "Access Authenticated Users Access - OK";
    }
    public function actionIpUser(){
        return "Access All Users Access - OK";
    }
    
    # RBAC TEST
    public function actionRbacTest($actionID, $postID=null){   //print_r($this->action->id);
        if(Yii::$app->user->can($actionID, $postID)){
            return ($postID) ? "Access '$actionID' with param=$postID - OK" : "Access '$actionID' - OK";
        }else
            return Yii::t('yii', "kamohh ga bolee macuk ke '$actionID' tayang...");
    }
    
    // public function actionGuest_act(){
		// //echo "masuk guest-role access";
		// //Yii::app()->authManager->assign('administrator', Yii::app()->user->id);
		// $res = "masuk guest-role access"; 
		// $this->renderPartial('test_rbac', array('res'=>$res), false, true);
	// }
	
	// public function actionAuthenticated_act(){
		// $res = "masuk User-authenticated access";
		// $this->renderPartial('test_rbac', array('res'=>$res), false, true);
	// }
	
	// public function actionUserA_act(){
		// $res = "masuk User-A role access";
		// $this->renderPartial('test_rbac', array('res'=>$res), false, true);
	// }
	
	// public function actionUserB_act(){
		// $res = "masuk User-B role access";
		// $this->renderPartial('test_rbac', array('res'=>$res), false, true);
	// }
	
	// public function actionPowerUser_act(){
		// $res = "masuk Poweruser-role access";
		// $this->renderPartial('test_rbac', array('res'=>$res), false, true);
	// }
	
	// public function actionAdmin_act(){
		// $res = "masuk Administrator-role access";
		// $this->renderPartial('test_rbac', array('res'=>$res), false, true);
	// }
}
