<?php

namespace app\controllers;

use Yii;
use app\models\CodeSample;
use app\models\CodeSampleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use \yii\helpers\Json;
/**
 * CodeSampleController implements the CRUD actions for CodeSample model.
 */
class CodeSampleController extends Controller
{
    public $enableCsrfValidation = false;
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }


    /**
     * Lists all CodeSample models.
     * @return mixed
     */
    public function actionIndex()
    {
        // Yii::$app->view->on(yii\web\View::EVENT_END_BODY, function () {
            // echo date('Y-m-d');
        // });
    
        // $searchModel = new CodeSampleSearch();
        $searchModel = new CodeSample(['scenario'=>'search']);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    protected function findModelWithSlug($id)
    {
        if (($model = CodeSample::findOne($id)) !== null) {   //echo "<pre>-->"; print_r($model); echo"<pre>"; 
            return $model;
        } else {
            throw new NotFoundHttpException('Code Sample was not found.');
        }
    }

    public function actionView($id, $slug = null)
    {               //echo "id: {$id} slug: {$slug}";
        $model = $this->findModelWithSlug($id);   //echo "<pre>-->"; print_r($model); echo"<pre>";  die();
        $comment = $this->findModelComment($model);
        //$comment = new \app\models\Comment(['scenario' => 'addComment']); 
        
        if ($slug != $model->slug_url) {   //echo "REDIRECT id: {$id} slug: {$model->title}"; // sleep(5);
            return $this->redirect(['code-sample/view', 'id' => $id, 'slug' => $model->slug_url]);
        }
        else
            return $this->render('view', [
                'model' => $model,
                'comment' => $comment, 
            ]);
    }
    
    /**
	 * Creates a new comment 
	 */
	protected function findModelComment($post)
	{							
		$comment= new \app\models\Comment(['scenario' => 'addComment']); 
        if ($comment->load(Yii::$app->request->post()) && $comment->save())
                Yii::$app->session->setFlash('msg','Thank you for your comment. Your comment will be posted once it is approved.'); 
        elseif($comment->hasErrors())
            Yii::$app->session->setFlash('msg', Json::encode($comment->errors));
            //echo "<pre>"; print_r($comment->errors); echo "</pre>"; 
		return $comment;
	}
    

    /**
     * Creates a new CodeSample model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {   
        $model = new CodeSample(['scenario' => 'create']); 
        $modelClass = new \app\models\CodeClass;
        $request = Yii::$app->request;
        
        if( $model->load($request->post()) && $modelClass->load($request->post()) ) {     
            if($request->isAjax){   
                $res = Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $validate = \yii\widgets\ActiveForm::validate($model, $modelClass);  
                
                if($model->save())    
                    return ['status'=>true];
                else
                    return ['status'=>false, 'content'=>$validate ];
            }
            else{
                if($model->save())   return $this->redirect(['view', 'id' => $model->id]);
            }
        } 
        else {
            return $this->render('create', [
                'model' => $model,
                'modelClass'=>$modelClass,
            ]);
        }
    }

    /**
     * Updates an existing CodeSample model.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelClass = $model->getClass()->one();
        $model->scenario = 'update';
        
        if ($model->load(Yii::$app->request->post()) && $modelClass->load(Yii::$app->request->post()) && $model->save()) {
                return (Yii::$app->request->isAjax) ? Json::encode(['status'=>true]) : $this->redirect(['view', 'id' => $model->id]);
        } else {        print_r($model->errors);  echo $model->scenario;
            return $this->render('update', [
                'model' => $model,
                'modelClass' => $modelClass,
            ]);
        }
    }

    /**
     * Deletes an existing CodeSample model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CodeSample model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CodeSample the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CodeSample::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function loadModel()
	{	//dump(Yii::app()->controller->action); die();
		if($this->_model===null)
		{
			if(isset($_GET['id']))
			{
				if(Yii::app()->user->isGuest)
					$condition='status='.Post::STATUS_PUBLISHED.' OR status='.Post::STATUS_ARCHIVED;
				else
					$condition='';
				$this->_model=Post::model()->findByPk($_GET['id'], $condition);		//dump($this->_model); die();
			}
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
			
			if(Yii::app()->controller->action->id !='view'){
				//$this->checkOwner($this->_model);
				if(!Yii::app()->user->isSuperuser && Yii::app()->user->id !== $this->_model->author_id ){
					Yii::app()->user->setFlash('error', "You are not authorized to perform this ".Yii::app()->controller->action->id." action.");
					$this->redirect(array('index'));
				}
			}	
			
		}
		return $this->_model;
	}
	
	public function actionSuggest($model, $term, $order, $limit=20 )  
	{
        $query = $model::find();
        $query->where(['like', $order, $term]);   # ['like', 'name', ['test', 'sample']] will generate name LIKE '%test%' AND name LIKE '%sample%'
        $items = $query->all();
        
		$suggest=[];
		foreach($items as $item) {   //print_r($item->toarray());
            $i = $item->toArray();
			$suggest[] = array(
				'id'=>$i['id'], //$i['id'], BUAT DROP-DOWN LIST NTAR
				'value'=>$i[$order],
			);
		}
		//return $suggest;
        echo Json::encode($suggest);
	}
    
}
