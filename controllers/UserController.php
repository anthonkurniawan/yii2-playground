<?php

namespace app\controllers;

use Yii;
use yii\helpers\Json;
use app\models\User;
use app\models\Profile;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
//ADD PAGINATION FOR CUSTOM PAGINATION GRINVIEW
use yii\data\Pagination;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new User();  // for create user
                                                                  
        if(isset($_GET['pageSize'])) 
            Yii::$app->session->set('pageSize', $_GET['pageSize']); 
        // if(isset($_GET['selected']) && $_GET['selected'] !=null)    
            // $this->deleteBatch($_GET['selected']);

        $searchModel = new \app\models\UserSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(\Yii::$app->request->isAjax)
            return $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model'=>$model,
            ]);
        else
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model'=>$model,
            ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if(Yii::$app->request->isPjax)
            return $this->renderAjax('view', ['model' => $this->findModel($id),'showPanel'=>false]);
        else
            return $this->render('view', ['model' => $this->findModel($id),'showPanel'=>true]);
    }

    /**
     * Creates a new User model.
     */
    public function actionCreate()
    {
        $model = new User(['scenario'=>'create']);   //print_r($model->newProfiles); die();
        $profile = new Profile;
        return $this->setResponse($model, $profile);
    }
    
    /**
     * Updates an existing User model.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario='update';
        $profile = $model->getProfiles()->one();
        return $this->setResponse($model, $profile);
    }
    
    protected function setResponse($model, $profile){       //print_r($this->action->id); die();
        $post = Yii::$app->request->post();
        if($post && $model->load($post) && $profile->load($post) && $model->validate() && $profile->validate())
        {  
            $save = $this->transaction($model, $profile, $post);
            if(Yii::$app->request->isPjax)
                return ($save) ? true : $this->renderAjax('_form', ['model' => $model, 'profile'=>$profile]); 
            elseif($save){
                Yii::$app->session->setFlash('msg', "$this->action->id Add new user success");
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }  

        if(Yii::$app->request->isAjax)
            return $this->renderAjax('_form', ['model' => $model, 'profile'=>$profile]);
        else 
            return $this->render($this->action->id, ['model' => $model, 'profile'=>$profile]);
    }
    
    /**
     * Deletes an existing User model.
     */
    public function actionDelete($id, $confirm=false)
    {           
        if(Yii::$app->request->isAjax){
            if($confirm){
                return (preg_match("/[$id]/", ',')) ? $this->deleteBatch($id) : $this->findModel($id)->delete();
            }else{
                $url = Yii::$app->request->url;
                return "<a href='#' onclick='\$.post(\"$url&confirm=1\", function(data){ if(data){ $(\"#modal-user\").modal(\"toggle\"); \$.pjax.reload({container:\"#pjax-con\", url: location.href, timeout:0, replace:false});} });' >Hapus</a>";
                //return "<a href='#' onclick='\$.pjax({url: \"$url&confirm=1\", type: \"post\", container: \"#user-form\", timeout:0,replace:false, push:false});' >Hapus</a>";
            }
        }
        else{
            $this->findModel($id)->delete();
            return $this->redirect(['index']);
        }
    }
    
    protected function deleteBatch($id)
    {   
        $user = explode(',', $id);  
        foreach($user as $uid){
            User::findOne($uid)->delete();
        } 
        return true;
    }
    
    protected function transaction($model, $profile){   //echo "user: $model->id profile:";  print_r($model->profiles->id); die();
        //if($model->load($post) && $model->validate() && $profile->load($post) && $profile->validate()) { 
            $transaction = Yii::$app->db->beginTransaction();
            $success = $model->save(false);
            // $profile->id = $model->id;
            //if($model->isNewRecord)
                $profile->id = $model->id;       
            $success = $success ? $profile->save(false) : $success;       
            if ($success){
                $transaction->commit();     //print_r($model->attributes); print_r($model->profiles->attributes); die();
                return true;
            }
            else
                $transaction->rollBack();
            return false;
       //} 
    }
    
    public function actionUpdateBatch($keys, $save=false){   //print_r($this->action->id); die();
        $ids = explode(',', $keys);  //print_r($ids);
        $models = User::findAll($ids);  //print_r($model);
        $profiles = Profile::findAll($ids);
        $this->setMultipleScenario($models, 'update');
        
        $post = Yii::$app->request->post();
        
        if (User::loadMultiple($models, $post) && Profile::loadMultiple($profiles, $post) && User::validateMultiple($models) && Profile::validateMultiple($profiles) && $save) 
        {                                                                         //echo "save";
            foreach ($models as $id=>$model) {         //echo "SAVE $id $model->username "; echo $model->profiles->firstname;
               $success = $this->transaction($model, $profiles[$id]);
            }
           if($success) return true;
        }    foreach ($models as $id=>$model){ echo $model->scenario; print_r($model->errors); }    
        return $this->renderAjax('_formBatch', ['models'=>$models, 'profiles'=>$profiles]);
    }
    
    public function actionCreateBatch($save=false)
    {
        $model = new User(['scenario'=>'create']);   //print_r($model); die();//print_r($model->newProfiles); die();
        $profile = new Profile;
            
        $count = (isset($_POST['User'][0])) ? count($_POST['User']) : 1;  //echo "count : $count";
        $post = Yii::$app->request->post();     //print_r($_POST);  die();
        
        if($count>1){        
            for($i = 1; $i <= $count; $i++) {
                $models[] = new User(['scenario'=>'create']);
                $profiles[] = new Profile;
            }        //echo count($models);  print_r($models[1]);  die();
            $load = User::loadMultiple($models, $post); //var_dump($load); die(); //print_r($model->attributes); die();
            Profile::loadMultiple($profiles, $post);
        }
        else{
            $model->load($post); //print_r($x); //print_r($model->attributes); die();
            $profile->load($post); 
            $models[] = $model;
            $profiles[] = $profile;
        }
        
        if($save && User::validateMultiple($models)){
            foreach ($models as $id=>$model) {         // echo "SAVE $model->username "; echo $profiles[$id]->firstname;
                $success = $this->transaction($model, $profiles[$id]);
            }
            if($success) return true;
        }
        
        $models = ($save) ? $models : array_merge($models, [new User(['scenario'=>'create'])] );  //print_r($models);  die();
        $profiles = ($save) ? $profiles : array_merge($profiles, [new Profile] );  //print_r($models);  die();
        
        if(Yii::$app->request->isPjax)
            return $this->renderAjax('_formBatch', ['count'=>$count, 'models'=>$models, 'profiles'=>$profiles]);
        else
            return $this->render('_formBatch', ['count'=>$count, 'models'=>$models, 'profiles'=>$profiles]);
    }
    
    protected function setMultipleScenario($models, $scenario){
        foreach($models as $model)
            $model->scenario = $scenario;
    }
    

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
