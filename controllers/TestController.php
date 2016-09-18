<?php

namespace app\controllers;

	# Standalone actions are defined in terms of action classes extending "yii\base\Action" or its child classes. 
	# For example, in the Yii releases, there are yii\web\ViewAction and yii\web\ErrorAction, both of which are standalone actions.
	/**
	To create a standalone action class, you should extend "yii\base\Action" or its child class, and implement a public method named "run()". 
	The role of the run() method is similar to that of an action method. For example,
	<?php
	namespace app\components;
	use yii\base\Action;

	class HelloWorldAction extends Action{
		public function run(){
			return "Hello World";
		}
	}
	*/
    
class TestController extends \yii\web\Controller
{
    public $defaultAction = 'index';
    
    /*
    Declares external actions for the controller.
    This method is meant to be overwritten to declare external actions for the controller. It should return an array, 
    with array keys being action IDs, and array values the corresponding action class names or action configuration arrays. For example,

    return [
        'action1' => 'app\components\Action1',
        'action2' => [
            'class' => 'app\components\Action2',
            'property1' => 'value1',
            'property2' => 'value2',
        ],
    ];
    */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',  // if page not found
            ],
            // CUSTOM ACTION VIEW
            'customView' => [
                'class' => 'yii\web\ViewAction',
                'viewPrefix' => '',
                'defaultView' => 'block',
                'layout'=>'',
                'viewParam'=>'',
                'behaviors'=>'',
            ],
            # STAND-ALONE ACTION SAMPLE
			'testStandaloneAction'=>[
                'class'=>'app\components\testStandaloneAction'
            ],
        ];
    }
        
    // If you want an action parameter to accept array values, you should type-hint it with array, like the following:
    // public function actionView(array $id, $version = null)
    // {
        // // ...
    // }

    public function actionIndex()
    {      
        return $this->render('index');
        // return $this->redirect('http://example.com');
    }
    
    public function actionForward()
	{
		// redirect the user browser to http://example.com
		return $this->redirect('http://example.com');
	}
    
    public function actionDb()
    {      
        return $this->render('db');
        // return $this->redirect('http://example.com');
    }
    
    public function actionGoto(){
        return $this->redirect('file:///D:/DOCUMENTS/MANUAL%20WEB%20DEVELOPER/YII2/www.yiiframework.com/doc-2.0/index.html');
    }

}
