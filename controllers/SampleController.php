<?php
namespace app\controllers;
use app\models\User;
use app\models\UserSearch;
use yii\web\View;
use Yii;

class SampleController extends \yii\web\Controller
{
    public function actions()
    {
        return [
        ];
    }
    
    /** Helpers Sample **/
    public function actionHelpers()
    {
        return $this->render('helpers/index');
    }
    
    public function actionHelpersAjaxContent($param)
    {
        //echo $param;
        // $this->clientScript->registerScript('syntaxhl', $js, CClientScript::POS_HEAD);
        //Yii::$app->getClientScript()->registerScript('sntax', 	"js: SyntaxHighlighter.highlight();");
        //$this->getView()->registerJs('SyntaxHighlighter.all();', View::POS_LOAD);
        // return $this->renderAjax('helpers/'.$param);
        return $this->renderPartial('helpers/'.$param);
    }
    
    
    public function actionPjax()
    {
        //if(\Yii::$app->request->isAjax){
        if(\Yii::$app->request->isPjax){    
            sleep(2);
            echo date('H:i:s').__FILE__;
        }
        else
            return $this->render('pjax');
    }

    public function actionBlock(){
        return $this->render('block');
    }
    
    public function actionBreadcrumbs(){
        return $this->render('breadcrumbs');
    }
    
    public function actionContentDecorator(){
        return $this->render('contentDecorator');
    }
    
    public function actionListview()
    {
        $view = isset($_GET['view']) ? $_GET['view'] : 'listview\basic';
        if(isset($_POST['pageSize'])) Yii::$app->session->set('pageSize', $_POST['pageSize']);
            
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render($view, [ 'searchModel' => $searchModel, 'dataProvider' => $dataProvider, ]);
    }
    
    public function actionGrid()
    {
        $view = isset($_GET['view']) ? $_GET['view'] : 'grid\gridview';
            
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render($view, [ 'searchModel' => $searchModel, 'dataProvider' => $dataProvider, ]);
    }
    
    public function actionGridMulti()
    {
        $userSearch = new UserSearch();
        $userProvider = $userSearch->search(Yii::$app->request->queryParams);
        $codeSearch = new \app\models\CodeSample(['scenario'=>'search']);
        $codeProvider = $codeSearch->search(Yii::$app->request->queryParams);

        return $this->render('grid\multi-grid', [
            'userSearch' => $userSearch,
            'userProvider' => $userProvider,
            'codeProvider' =>$codeProvider,
            'codeSearch' => $codeSearch,
        ]);
    }
    
    # CUSTO GRIDVIEW DATA & PAGINATION
	public function actionPagingSort()
    {
        $query = User::find();  // where(['status' => 10])

        $pagination = new \yii\data\Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);
        
        $sort = new \yii\data\Sort([
            'attributes' => [
                'id',
                'username' => [
                    // 'asc' => ['username' => SORT_ASC, 'last_name' => SORT_ASC],  # WITH TWO ORDER BY
                    'asc' => ['username' => SORT_ASC],
                    'desc' => ['username' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Name',
                ],
            ],
        ]);

        $data = $query->orderBy($sort->orders)  // OR USE IF NOT WITH NO SORT -> orderBy('username')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('paging_sort', [
            'data' => $data,
            'pagination' => $pagination,
            'sort' => $sort,
        ]);
    }
    
    public function actionCodemirror(){
        return $this->render('codemirror');
    }
    
    public function actionMyFileTree(){
        if(Yii::$app->request->isAjax){ 
            $root = 'd:\web\\html5\\'; //$_SERVER['DOCUMENT_ROOT']; 
            if( !$root ) exit("ERROR: Root filesystem directory not set in jqueryFileTree.php");

            $postDir = rawurldecode($root.(isset($_POST['dir']) ? $_POST['dir'] : null ));
            // set checkbox if multiSelect set to true
            $checkbox = ( isset($_POST['multiSelect']) && $_POST['multiSelect'] == 'true' ) ? "<input type='checkbox' />" : null;
            $onlyFolders = ( isset($_POST['onlyFolders']) && $_POST['onlyFolders'] == 'true' ) ? true : false;
            $onlyFiles = ( isset($_POST['onlyFiles']) && $_POST['onlyFiles'] == 'true' ) ? true : false;

            if( file_exists($postDir) ) {
                $files		= scandir($postDir);
                $returnDir	= substr($postDir, strlen($root));  echo "postDir: $postDir - root: $root - return dir: $returnDir";
                natcasesort($files);

                if( count($files) > 2 ) { // The 2 accounts for . and ..
                    echo "<ul class='jqueryFileTree'>";
                    foreach( $files as $file ) {
                        $htmlRel	= htmlentities($returnDir . $file);   echo "<br>rel : $htmlRel ";
                        $htmlName	= htmlentities($file);
                        $ext		= preg_replace('/^.*\./', '', $file);

                        // if( file_exists($postDir . $file) && $file != '.' && $file != '..' ) {
                        if( file_exists($postDir . $file) ) {
                            if( is_dir($postDir . $file) && (!$onlyFiles || $onlyFolders) )
                                echo "<li class='directory collapsed'>{$checkbox}<a href='#' rel='" .$htmlRel. "/'>" . $htmlName . "</a></li>";
                            else if (!$onlyFolders || $onlyFiles)
                                echo "<li class='file ext_{$ext}'>{$checkbox}<a href='#' rel='" . $htmlRel . "'>" . $htmlName . "</a></li>";
                        }
                    }
                    echo "</ul>";
                }
            }
        }
        else
            return $this->render('myFileTree');
    }
    
    # BELOM DI PAKE
    public function actionSendMail(){
        return \Yii::$app->mailer->compose(['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['user' => $user])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                    ->setTo($this->email)
                    ->setSubject('Password reset for ' . \Yii::$app->name)
                    ->send();
                    
                // echo \Yii::$app->mailer->compose(['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['user' => $user])
                    // ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                    // ->setTo($this->email)
                    // ->setSubject('Password reset for ' . \Yii::$app->name)
                    // ->toString();
    }
    
    # TWIG SAMPLE
    public function actionTwigSample(){
        $var = 'test';
        $items = [
            ['href'=>'#1', 'label'=>'test1'],
            ['href'=>'#2', 'label'=>'test2'],
        ];
        return $this->render('template_engine\sample.twig', ['var' => $var, 'items'=>$items]);
    }
    
    # SMARTY SAMPLE
    public function actionSmartySample(){
        $var = 'test';
        $items = [
            ['href'=>'#1', 'label'=>'test1'],
            ['href'=>'#2', 'label'=>'test2'],
        ];
        return $this->render('template_engine\sample.tpl', ['var' => $var, 'items'=>$items]);
    }
}
