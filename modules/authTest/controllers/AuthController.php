<?php

namespace app\modules\authTest\controllers;
use Yii;
use yii\rbac\Item;
use app\modules\authTest\models\AuthItem;
use app\modules\authTest\models\AuthRule;;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

class AuthController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView($name, $type){      
        $model = $this->findModel($name, $type);   //echo "name: $name";  print_r($model);  die();
        return $this->renderAjax('view', ['type'=>$type, 'model'=>$model]);
    }
    
    public function actionUpdate($name, $type){     
        $model = $this->findModel($name, $type);   // echo "name: $name";  print_r($model);  die();
        return $this->sendResponse($model);
    }
    
    public function actionCreate($type)
    {   //var_dump( is_int($type) );  die();
        if($type=='Rule')
            $model = new AuthRule;
        else{
            $model = new AuthItem;     //print_r($model->validate());  print_r($model->errors);  die();
            $model->type = $type;
        }
            
        return $this->sendResponse($model);
    }
    
    public function actionDelete($name, $type)
    {
        $model = $this->findModel($name, $type);
        if(Yii::$app->getAuthManager()->remove($model->item))
            return Json::encode(['status'=>true]);
        //return $this->sendResponse($model);
    }
    
    protected function sendResponse($model){      
        $request = Yii::$app->request;
        $view = ($model->className() == 'app\modules\authTest\models\AuthItem') ? '_formItem' : '_formRule';
        if( $model->load($request->post()) ) {     
            if($request->isAjax){   
                $res = Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                //$validate = \yii\widgets\ActiveForm::validate($model);  
                
                if($model->save())    
                    return ['status'=>true];
                else
                    return ['status'=>false, 'content'=>$model->errors ];
            }
            else{
                if($model->save())   
                    return $this->redirect(['view', 'name' => $model->name]);
            }
        } 
        else{
            if($request->isAjax)
                return $this->renderAjax($view, ['model' => $model,]);
            else
                return $this->render($view, ['model' => $model,]);
        }
    }
    
    protected function findModel($name, $type)
    {         
        $model = ($type ==='Rule') ? new AuthRule(Yii::$app->authManager->getRule($name)) : AuthItem::loadModel($name, $type);  //print_r($model);
        return $model;
    }
    
    public function actionAutoComplete($type=null){
        $type = ($type==1) ? null : $type;
        $items = AuthItem::loadAuthItem($type);
        echo Json::encode(array_keys($items));
    }
    
    public function getChildren($role){
        $auth = Yii::$app->authManager; 
        $child = $auth->getChildren($role);    
        return (count($child) > 0) ? $this->formatString($child) : null;
    }
    
    protected function formatString($data, $attr='name'){
        $res =[];
        foreach($data as $val){
            $res[] = $val->$attr;
        }
        return implode(', ', $res);
    }
    
    /*

$class = new yii\helpers\FileHelper; 
echo $path = realpath(Yii::$app->basePath .'\rbac');
$file = $class->findFiles($path);   print_r($file);
*/
}
