<?php

namespace app\modules\authTest\controllers;

use yii\web\Controller;
use Yii;
use app\modules\authTest\models\AuthItem;
use yii\helpers\Json;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionLoadAuth()
    {
        //$model = new AuthItem;
        $auth = Yii::$app->authManager; 
        if(\Yii::$app->request->isAjax)
            return $this->renderAjax('_auth-list', ['auth'=>$auth]);
        else
            return $this->render('_auth-list', ['auth'=>$auth]);
    }
   
    public function actionLoadUser()
    {
        $model = new \app\models\User();  // for create user
                                                                    
        if(isset($_POST['pageSize'])) 
            Yii::$app->session->set('pageSize', $_POST['pageSize']);
        if(isset($_POST['selected']) && $_POST['selected'] !=null)    
            $this->deleteUser($_POST['selected']);

        if(Yii::$app->request->isPjax && $model->load(Yii::$app->request->post()) && $model->save())    
        {   
            //print_r($model->getErrors());
            Yii::$app->session->setFlash('msg', 'Add new user success');
            $model = new \app\models\User(); //reset model
        }
        
        //$searchModel = new UsersSearch();
        $searchModel = new \app\models\UserSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->renderAjax('_user-list', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model'=>$model,
            ]);
    }
    
    public function actionAssign($id){      
        if(Yii::$app->request->post()){
            $auth = Yii::$app->authManager;
            
            if($_POST['assign']=='none')
                $auth->revokeAll($id);
            else{
                $auth->revokeAll($id);
                foreach($_POST['assign'] as $assign){
                    $role = $auth->getItem($assign);
                    try{
                        $auth->assign($role, $id);
                    }
                    catch (yii\base\InvalidParamException $e) {   
                        return Json::encode(['status'=>false, 'content'=>$e->getMessage()]);
                    }
                }
            }
            return Json::encode(['status'=>true]);
        }
        else{
            $roles = array_keys($this->getAllRoles());  //echo "<pre>"; print_r($roles); echo "</pre>";die();
            foreach($roles as $key=>$val)
                $roleList[$val] = $val;                         
            $assigments = array_keys($this->getAssignUser($id));   //echo "<pre>"; print_r($assigments); echo "</pre>";die();
            if(\Yii::$app->request->isAjax)
                return $this->renderAjax('/auth/_user-assign', ['roles'=>$roleList, 'assigments'=>$assigments, 'id'=>$id]);
            else
                return $this->render('/auth/_user-assign', ['roles'=>$roleList, 'assigments'=>$assigments, 'id'=>$id]);
        }
    }
    
    public function getAllRoles($withDefault=false){
        $auth = Yii::$app->authManager; 
        $roles = $auth->roles;
        $defRoles = $this->getDefaultRole();
        return ($withDefault) ? array_merge($roles, $defRoles) : $roles;
    }
    
    public function getDefaultRole($asString=false){
        $auth = Yii::$app->authManager; 
        $defaultRole = $auth->defaultRoles;   //print_r($defaultRole);
        if(count($defaultRole) > 0){
            foreach($defaultRole as $role)
                $res[] = $auth->getItem($role); 
        }
        return ($asString) ? $this->formatString($res) : $res;
    }
    
    public function getChildren($role){
        $auth = Yii::$app->authManager; 
        $child = $auth->getChildren($role);    
        return (count($child) > 0) ? $this->formatString($child) : null;
    }
    
    public function getPermitRole($role){
        $auth = Yii::$app->authManager; 
        $permit = $auth->getPermissionsByRole($role); 
        return (count($permit) > 0) ? $this->formatString($permit) : null;
    }
    
    public function getPermitUser($id){
        $auth = Yii::$app->authManager; 
        $permit = $auth->getPermissionsByUser($id);
        return (count($permit) > 0) ? $this->formatString($permit) : null;
    }
    
    public function getRoleUser($id){
        $auth = Yii::$app->authManager; 
        $role = $auth->getRolesByUser($id); 
        return (count($role) > 0) ? $this->formatString($role) : null;
    }
    
    public function getAssignUser($id, $formatString=false){  
        $assign = Yii::$app->authManager->getAssignments($id);
        return ($formatString) ? $this->formatString($assign, 'roleName') : $assign;
    }
    
    protected function formatString($data, $attr='name'){
        $res =[];
        foreach($data as $val){
            $res[] = $val->$attr;
        }
        return implode(', ', $res);
    }
}
