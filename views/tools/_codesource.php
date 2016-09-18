<?php
use yii\helpers\VarDumper;
use yii\helpers\ArrayHelper;
use app\models as model;         
use app\components\MyBehavior;

//print_r(app\extensions\yii-node-socket\lib\php\YiiNodeSocket);
//print_r(YiiNodeSocket);
/*
# USE NAMESPACE NOTE :
//print_r(new app\extensions\SrcCollect);
//--OR --
//use app\extensions\SrcCollect;
//$src = new SrcCollect;  print_r($src);
//--OR --
//use app\extensions;
//print_r(new extensions\SrcCollect);
*/
/*
$source = Yii::$app->viewPath.'\sample\helpers\_x.php';       
$cont = file_exists($source) ? trim(file_get_contents($source)) : null;
//echo $cont ."\n";
$split = preg_split('/\?\>\s+\<\?php/', $cont);   print_r($split);
*/
//$x = $this->context;  print_r($x::test3());

//print_r(new MyBehavior);
//print_r($this->context);
//$component = $this->context->attachBehavior('myBehavior1', new MyBehavior);  print_r($component);
//$component->attachBehavior('myBehavior1', new MyBehavior);
//print_r($component);

//$mod = Yii::$app->getModules();
//print_r(Yii::$app->getModule('gii'));
//$mod = Yii::$app->getComponents();
//$mod = Yii::$app->coreComponents();
//$mod = Yii::$app->assetmanager;
//print_r(Yii::$app->getBehaviors())

//var_dump( array_key_exists('db', Yii::$app->components ));
//print_r( new app\controllers\UsersController("testID",null) )
//var_dump(array_key_exists('gii', Yii::$app->modules))

//$x =" a; ;";  $x=preg_match_all( '/[;]/', $x); print_r( $x );   //echo count($x);
//print_r($_SERVER);
//echo "\n" .Yii::$app->request->headers['origin'] . Yii::$app->request->url;
//echo "\n" .Yii::$app->request->headers['referer'];
//print_r(Yii::$app->request);
//echo Yii::$app->runtimePath;

//echo "\n". get_called_class();   
//echo "\n". Yii::getAlias('@app/modules');
//echo "\n". get_include_path();
//$con = Yii::$app->getControllerPath();  echo $con;  var_dump(is_dir($con));
//$mod = Yii::$app->getModule('gii')->basePath;  print_r($mod);   //echo DIRECTORY_SEPARATOR; die();
//print_r(Yii::$app->getModule('gii'));
//print_r(Yii::$aliases);
//VarDumper::dump(new Yii, 10);
//VarDumper::dump( get_class_vars( get_class(new Yii)));

/* DB TEST */
//$db = Yii::$app->db;
//VarDumper::dump($db, 10);
//print_r($db->getLastInsertID(''));
//print_r($db->getSchema()->tablenames);
//print_r($db->schema->tablenames)
//print_r($db->queryBuilder);
//print_r($db->queryCache);
//print_r($db->masterPdo);
//print_r($db->queryCacheInfo);

//print_r($model->findOne(1));

//$command = $db->createCommand('SELECT * FROM tbl_user_mysql');
//$model = new model\Users;  print_r($model->scenarios()); print_r($model->scenario);
//$user = $command->queryAll();   print_r($user);
//$user = model\Users::find()->where(['username' => 'admin'])->with('profiles')->one();   //print_r($user->profiles->lastname);  print_r($user->getRelatedRecords());  

/*
$a = new model\CodeClass;  
$a->classname = 'z';//var_dump($a->validate()); $a->save(false); print_r($a->errors); die(); //print_r($a); die();

$b = new model\CodeSample(['scenario' => 'create']); print_r($b->scenario); //print_r($user->scenarios()); //print_r($user->getValidators());  //print_r($user->activeAttributes());   
$b->title='c'; 
$b->content='x'; 
$b->tags='g, h';

if ($a->validate()) {
  $transaction = Yii::$app->db->beginTransaction();
  $success = $a->save();       var_dump($success);
  $b->class_id = $a->id;   // print_r($a->attributes); print_r($b->attributes);  //die();
  $success = $success ? $b->save() : $success;  var_dump($success);
  if ($success)
    $transaction->commit();
  else{
    $transaction->rollBack();
    print_r($b->attributes); print_r($a->errors); print_r($b->errors);
  }
}
*/

//$class = Yii::getAlias('@yii');  echo "\n". realpath($class) ."\n";   
//print_r(Yii::$aliases);
//var_dump( class_exists('yii\db\ActiveQueryInterface', true) );
//var_dump( class_exists( Yii::getAlias('@tona/fileTree'), true));
//print_r(get_declared_classes());

/*
$c = new ReflectionClass('yii\base\BootstrapInterface');  //var_dump($c->isInstantiable());   var_dump($c->getConstructor()); //$c->export(get_class(new yii\BaseYii));  //print_r($c->getDocComment() ); 
$refMethod = new ReflectionMethod('yii\db\ActiveQuery',  '__construct'); 
$req = $refMethod->getNumberOfRequiredParameters();  echo "\n".$req;
$params = $refMethod->getParameters();   print_r( $params);
*/
//print_r($c->getProperties());  
//$d = $c->getInterfaces();    VarDumper::dump($d, 10);
//$p = new ReflectionProperty('yii\web\Application', '_homeUrl');  print_r($p);  // EQUAL : new ReflectionProperty('yii\web\Application', '_homeUrl');
//$v = $p->getValue();  print_r($v); # ONLY PUBLIC PROPERTY
//$ex = $p->export(get_class(Yii::$app),'bootstrap', true);   echo $ex;
//$ex = $p->export('yii\base\Component','_events', false);   echo $ex;

//ReflectionObject::export(Yii::$app);  // show all       "getPreferredLanguage", class: "yii\web\Request"
//$x = new ReflectionProperty('yii\base\Component','_definitions'); $x->setAccessible(true); $x =$x->getValue( get_class(Yii::$app));    var_dump($x);
//$x = new ReflectionMethod('yii\web\Request','getPreferredLanguage');  print_r($x);   echo $x->invoke(Yii::$app->request);

$auth = Yii::$app->authManager;   
$roles = $auth->roles; //print_r($roles);

$permissions = $auth->permissions; //print_r($permissions);  // - admin
$permitByRole = $auth->getPermissionsByRole('author');   //print_r($permitByRole);

$rules = $auth->rules; //print_r($rules);
$rule = $auth->getRule('isAuthor');  //print_r($rule);
//print_r($auth->behaviors);
$items = $auth->getItems(2);  //type
$item = $auth->getItem('reader');  //print_r($item);
$child = $auth->getChildren('admin'); //print_r($child);   
$defaultRole = $auth->defaultRoles;   //print_r($defaultRole);

//user
$assigns = $auth->getAssignments(1);  //print_r($assigns);
$assign = $auth->getAssignment('author',2);   //print_r($assign);
$permitByUser = $auth->getPermissionsByUser(1);
$role = $auth->getRolesByUser(1);   //print_r($role);

echo Yii::$app->user->can('createPost');