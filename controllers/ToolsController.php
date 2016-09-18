<?php
namespace app\controllers;
use Yii;
use yii\helpers\VarDumper;
use yii\helpers\Json;
use yii\debug\Module;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
# FROM PHP FUNCTIONS
use ReflectionClass;
use ReflectionProperty;
use ReflectionMethod;

class ToolsController extends \yii\web\Controller
{	
    public function beforeAction($action)
    {
        // ...set `$this->enableCsrfValidation` here based on some conditions...
        // call parent method that will check CSRF if such property is true.
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    
	public $anyParam="test";
    
    public function actionTest1(){
        return "test1";
    }
    protected function test2(){
        return "test2";
    }
    public static function test3(){
        return "test static";
    }
    
	public function actionIndex()
    {		
        return $this->render('index');
    }

	public function actionKelas(){		
		return $this->render('kelas');
	}
    
    public function actionLoadSample(){
        // $query = \app\models\CodeClass::find()->joinWith('codeSamples')->all();
        // $func = function($model){
            // return [
                // 'title' => $model->title,
                // 'content'=>$model->content
            // ];
        // };
        // foreach($query as $key){
          // $code[$key->classname] = array_map($func, $key->codeSamples);       
        // }
        
        // ------------------------------------------
        // 'SELECT MAX(update_time) FROM tbl_post'
        $cond = (isset($_GET['class'])) ? "WHERE classname='{$_GET['class']}'" : null;
        $sql = "SELECT * FROM code_class LEFT JOIN code_sample ON code_class.id = code_sample.class_id {$cond} ORDER BY classname";
        $sql_depend = "SELECT MAX(FROM_UNIXTIME(update_time)) FROM code_sample";
        $depend = new \yii\caching\DbDependency;   
        $depend->sql = $sql_depend;           //print_r($depen);  die();
        $res = Yii::$app->db->createCommand($sql)->cache(3600, $depend)->queryAll();

        foreach($res as $item){
            $code[$item['classname']][] = [
                'id' => $item['id'],
                'title' => $item['title'],
                'tags' => $item['tags'],
                'content'=>$item['content']
            ];
        }
        //print_r($db->getQueryCacheInfo(3600,null) );  var_dump($db->enableQueryCache);
        echo json_encode($code); 
        //echo "<pre>"; print_r($code); echo "</pre>";
    } 
    
    public function actionCode_test(){		       //print_r(Yii::$app->request->headers['referer']);   die();
        $source = Yii::$app->viewPath.'\tools\_codesource.php';       
        // get existing code
		$cont = file_exists($source) ? trim(file_get_contents($source)) : null;   //echo $cont; //die();
        // int file_put_contents ( string $filename , mixed $data [, int $flags = 0 [, resource $context ]] )

        // [HTTP_REFERER] => http://127.0.0.1/yii2-test/tools/code_test
        // [REMOTE_ADDR] => 127.0.0.1
        // [HTTP_ORIGIN] => http://127.0.0.1
        //echo Yii::$app->request->url
        
        $request = Yii::$app->request;
        if($request->isAjax){    //$x = eval('$var = new app\models\Users; return $var->tableName();');  var_dump($x); die();
            if( ! $request->getBodyParam('cmd'))
                return  "command not set";
            
            $code = $request->getBodyParam('cmd');
            if($request->getBodyParam('mode')=='file'){
                file_put_contents($source, $code);  // save code to file
                return $this->renderAjax('_codesource');  // or can use path alias like '@app/runtime/_filephp'
            }
            else{
                # EVAL()
                $url = Yii::$app->request->headers['origin'] . Yii::$app->request->url;
                $ref = Yii::$app->request->headers['referer'];   //var_dump($url===$ref);
                if($url===$ref){
                    $cmd ='return '. $code. ';';   //echo $cmd; die();
                    $res = eval($cmd);
                    VarDumper::dump($res, 10); 
                }
                else{
                    //$res = eval($code);  //var_dump(is_object($res));
                    ob_start();
                    $res = eval($code);
                    $out = \yii\helpers\Html:: encode(ob_get_contents(), true);
                    ob_end_clean(); 
                
                    //Yii::$app->response->content = $res;
                    if($out) echo "<b>Output : </b><p>{$out}</p>";
                    if(is_object($res)){
                        echo "<strong style='color:chartreuse'>Value adalah Object : ". 
                            \yii\helpers\Html::a( get_class($res), ['tools/kelas', 'obj'=>$code], ['target'=>'_blank']) ."</strong> <i>(Fetch for more complete object in new tab.?)</i><br>";
                        VarDumper::dump($res, 10);  
                    }
                    elseif(is_array($res))
                        VarDumper::dump($res, 10);  
                    else
                        echo $res;
                }
            }
        }
        else
            return $this->render('codeTest', ['code'=>$cont]);
	}
	
	public function actionDeclare_class($var){
		# SAMPLE FIND STRING IN ARRAY & OBJECT
		// echo $this->array_preg_search('/useMemcached/', $arr) .'<hr>';
		// echo $this->array_preg_search('resource', $arr) .'<hr>';
		if($var=='class')
			$result = get_declared_classes();
		elseif($var=='interface')
			$result = get_declared_interfaces();	
		else
			$result = get_declared_traits();
			
		print_r($result);
	}
    
	#------------------------------------------------------ GET KELAS/OBJECT ATTRIBUTES -------------------------------------#
	public function actionFetch()
	{	
        $kelas = $_POST['kelas'];         //echo $kelas. "\n"; echo rawurldecode($kelas); die(); // echo $kelas; die();
        $option = Yii::$app->request->getBodyParam('option');   //print_r($option); die();
        
        if($kelas){    
            $out="";
            if($kelas=='this'){
                $data = $this;
                $cmd = 'return $controller = Yii::$app->controller;';
            }elseif( array_key_exists($kelas, Yii::$app->components ) ){
                $data = eval('return YII::$app->'.$kelas.';');
                // $cmd = 'return Yii::$app->'.$kelas.';';
                $cmd = 'return $'.$kelas.' = Yii::$app->'.$kelas.';';
            }
            elseif( Yii::$app->getModule($kelas) !=null){
                $data = Yii::$app->getModule($kelas);
                $cmd = 'return $'.$kelas.' = Yii::$app->getModule("'.$kelas.'");';
            }else{  // return+new+app\models\Auth;, return+Yii::$app;, return+new+Yii;
                $kelas = str_replace('+', ' ', $kelas);  //echo $kelas; die();

                // BUAT HINDARIN RENDER WIDGET SEPERTI ACTIVE FORM 
                ob_start();
                $data = eval($kelas.';');
                $out = \yii\helpers\Html:: encode(ob_get_contents(), true);
                ob_end_clean();   //echo "----->".$out;   die();
                // $cmd = str_replace(";","",$kelas).';';
                $split_cmd = preg_split( '/[^a-zA-Z]/', $kelas, 0, PREG_SPLIT_NO_EMPTY);
                $cmd = 'return $' . strtolower(end($split_cmd)) .' = '. str_replace('return', '', $kelas);  //echo $cmd; die();
            }                                                                                       
            
            if(is_object($data))
                $result = $this->getObject( $data, $option );			//echo "<pre>"; print_r($data); echo "</pre>"; die();
            elseif(is_string($data))
                $out = ($out !='') ? $out : $data;
            
            $result['cmd'] = $cmd;   //print_r($data); die(); // output for command console
            $result['output'] = $out;
            # CONVERT TO JSON / OBJ
            //$response = Yii::$app->response;
            //$response->content='';
            //$response->format = \yii\web\Response::FORMAT_JSON;
            echo Json::encode( $result );	# MORE DEEPLY --or // Yii::$app->end();
		}
		else echo "KELAS TIDAK DI DEFINE";
	}
	
    /** FETCH CLASS OBJECT COMPLETE  **/
    public $className;
	protected function getObject($obj, $opt)
	{			// MORE FUNC OF CLASS :  interface_exists , get_class_vars( get_class(Yii::$app)), is_subclass_of , call_user_method_array, call_user_func
			# CHECK OBJECT  # is_a — Checks if the object is of this class or has this class as one of its parents
			$this->className =  is_object($obj) ? get_class($obj) : false;  	//echo $this->className. get_parent_class($this->className); die();
                                                                                                     
			# CHECK IF "OBJ" IS CLASS OR ARRAY
			if($this->className){	//echo $this->className;
                $exclude_obj = ['app','panels','loadedModules', 'extensions','controllerMap','yiibaseModulemodules', 'yiidiServiceLocatorcomponents', 'yiidiServiceLocatordefinitions', 'yiibaseComponentevents', 'yiibaseComponentbehaviors','targets'];  //log need
				//$exclude_obj = ['loadedModules','yiibaseModulemodules', 'yiidiServiceLocatorcomponents','target'];
                $exclude_method = ['getLastInsertID'=>'throws yii\base\InvalidCallException', 'getLog'=>null, 'getLogger'=>null, 'getModules'=>null, 'getInstance'=>null, 'getView'=>null, 'getBehaviors'=>null];
				$parent = $this->kelas_parent($this->className);				//echo get_parent_class($obj); die();
                $class_properties = $this->getReflectClass($this->className);    //print_r($class_properties['Methods']); die();
				//$method = (array) get_class_methods($obj); 	
				//$method_filter = $this->filterMethod($method, 'get');  //print_r($method_filter);  die();# FILTER ARRAY WITH "get" method ASC
				// $method_val = ((boolean)$opt['show_method_val']) ? $this->getVal_method($obj, $method_filter, $exclude_method, $exclude_obj) : 'OFF';		//print_r($method_val );  die();
                $method_val = ((boolean)$opt['show_method_val']) ? $this->getReflecValueMethod($obj, $class_properties['Methods'], $exclude_method, $exclude_obj) : 'OFF';		//print_r($method_val );  die();
				// $ref_method = $this->getRefMethod($this->className, $method);		//print_r($ref_method); 
                $ref_method = $this->getRefMethod($this->className, $class_properties['Methods']);
				# $obj_vars = (array) get_object_vars($obj);	dump(get_class_vars($class)); $obj_vars = CVarDumper::dumpAsString($obj_vars, 10, true);
			}	
		
            $data['class'] = ($this->className) ? ['class_name'=>$this->className, 'parent_class'=>get_parent_class($this->className)] : null;
			$data['parent_label'] = ($this->className) ? $parent :null;
			$data['dump_obj'] = ((boolean)$opt['show_dump_obj']) ? VarDumper::dumpAsString($obj, 10, true) : 'OFF';	#CONVERT TO STRING # OR -- print_r($obj, true),	
                           
            $long_nesting_class = ['Yii', 'yii\web\Application','app\controllers\SiteController', 'yii\web\View', 'yii\log\Dispatcher', 'yii\gii\Module', 'yii\web\Controller'];
			#CONVERT OBJ TO ARRAY
            $tree_arr = ((boolean)$opt['show_tree_obj']) ? $this->getAllProperties($obj, $class_properties['Properties'], $class_properties['Constants'], $exclude_obj) : 'OFF';	//echo Json::encode($tree_arr); die(); 

            $data['obj'] = $tree_arr;  #panel fetch obj
			//$data['method'] = ($this->className) ? $method : null;
			$data['method_ref'] = ($this->className) ? $ref_method : null;
			$data['method_val'] = ($this->className) ? $method_val : null;		# FOR APP TO LONG IF GET Yii::$app ( TRY USING LAZY LOADING)
            $data['class_properties'] = $class_properties;

			return $data;
	}
	
	public function kelas_parent($class=null, $data=array())
	{
		$parent = get_parent_class($class);			
        $data[$class] = ['class_alias'=>$class, 'doc_url'=>"http://localhost/yii2doc/".strtolower( preg_replace( '/[^a-zA-Z]/', "-", $class) ).'.html',];
		while(class_exists($parent) &&! array_key_exists($parent, $data) ){	# cek class is exist & tidak ada nama yg sama
            $data[$parent] = [
                'class_alias'=>$parent, 
                'doc_url'=>"http://localhost/yii2doc/".strtolower( preg_replace( '/[^a-zA-Z]/', "-", $parent) ).'.html',
            ];
			$data = self::kelas_parent($parent, $data);  //exit();
		}		//print_r($data);  die();
		return $data;
	}
	
	function filterMethod($arr, $str){
        $arr_filter=[];
		foreach($arr as $m){		
			if (strpos($m, $str) !== false)
				$arr_filter[] = $m;
			else
				$other[] = $m;
		}
		return array_merge($arr_filter, $other);
	}

    function getReflecValueMethod($obj, $obj_m, $exclude_method=[], $exclude_obj=[]){   //print_r($obj_m);  print_r($exclude_method); print_r($exclude_obj); die();
        $filter = $this->filterMethod($obj_m, 'get');   // SORT BY "GET" prefix
        foreach($filter as $key=>$m){ 
            // if ((strpos($m->name, 'get') !== false && $m->getNumberOfRequiredParameters()==0) || $m->getNumberOfRequiredParameters()==0) 
            if (strpos($m->name, 'get') !== false && $m->getNumberOfRequiredParameters()==0) 
            {      
				try {
                    if( ! array_key_exists($m->name, $exclude_method) ){
                        if($m->isProtected()) $m->setAccessible(true);
                        $value = $m->invoke($obj);  //$value = $m->invoke($obj, $m->name);
                    }else{
                        $err = ($exclude_method[$m->name] !=null) ? $exclude_method[$m->name] : 'Maximum function nesting level of 100 reached.';
                        $value = "<b class='text-danger'>".$err."</b> <br> Try Manual : <a href='#' class='text-primary' onclick='getValue($(this), \"{$m->name}\",\"method\", {$m->isPublic()});'>{$m->name}</a>";
                    }
                                                                                                                                   
                    if(gettype($value)=='object' &&  get_class($value)=='Memcache')
						$value = $this->objectToArray($value, null, $exclude_obj);                    // echo $m->name."\n"; print_r($value);
				} 
                catch(Exception $e) {           
                     // catch (ErrorException $e) {
                    // Yii::warning("Division by zero.");
                    //echo 'xCaught exception: ',  $e->getMessage(), "\n";
					$value =  $e->getMessage();   //VarDumper::dump($value, 10, true);                
				}
				$data[$key]['value'] = $value;
                $data[$key]['class_value'] = (gettype($value)=='object') ? get_class($value) : $m->class;
			}
			else
                $data[$key]['value'] = "<b class='text-danger'>Bukan getter method / butuh parameter</b> <br> Try Manual : <a href='#' class='text-primary' onclick='getValue(\$(this), \"{$m->name}\",\"method\", {$m->isPublic()});'>{$m->name}</a>";
            
            $data[$key]['method'] = $m->name;
            $data[$key]['class'] =  $m->class;
            //$data[$key]['summary'] = $m->export($m->class, $m->name, true);
		}        //print_r($data); die();
        return $data;
    }
    
    function getReflecValue($obj, $arr, $data=[]){
        foreach($arr as $i=>$ref){
            $index = str_replace("get","", $ref);  // replace get word
            if(is_array($ref))
                $data[$i]= $this->getReflecValue($obj, $ref); 
            else{
                if($index=='Parameters')
                    $data[$index] = eval('return implode(",", $obj->$ref());');  //$class->$r.'()';
                else
                    $data[$index] = eval('return $obj->$ref();');  //$class->$r.'()';
            }
        }
		return $data;
    }
    
    function getReflectProperties($class, $obj=[]){  
        $ref =['getDocComment']; //,'isPrivate','isProtected','isPublic','isStatic','isDefault'];
        foreach($obj as $i){  //echo "\n {$i} {$class}";  if($i=='_csrf'){ die();}
            $p = new ReflectionProperty($class, $i);   //print_r($p);
            $exp['Pexp'] = $p->export($class, $i, true);
            $data= array_merge($exp, $this->getReflecValue($p, $ref));
        }   //echo Json::encode($data);  die();
        return $data;
    }
    
	function getReflectClass($class, $ref=[]){
		$obj = new ReflectionClass($class);                       //dump($class); 
        if( !$ref){
            $ref = ['getDocComment','getConstructor','getProperties','getConstants','getMethods','getExtension','getFileName','getStartLine','getEndLine','getModifiers','getNamespaceName', 'inNamespace'];    
            $ref['types'] =['isInterface','isTrait','isInternal','isIterateable','isUserDefined','isAbstract','isCloneable','isFinal','isInstantiable'];
        }
        //$except=['getMethods', 'getDefaultProperties','getStaticProperties','getExtensionName', 'isInstance'];
        $data = $this->getReflecValue($obj, $ref);  //echo "========>"; print_r($data);  die();
        $export = (string) $obj;
        //$start = (isset($data['types']['isInstantiable']) && $data['types']['isInstantiable'] ) ? stripos($export, 'Class [') : stripos($export, ' * '); 
        //$end= ($data['types']['isInstantiable']) ? stripos($export, '{', $start) : stripos($export, '*/', $start);
        if(isset($data['isInstantiable']) && ! $data['isInstantiable'] ){
            $start = stripos($export, ' * '); 
            $end = stripos($export, '*/', $start);
        }
        else{
            $start = stripos($export, 'Class [');
            $end = stripos($export, '{', $start);
        }
        $len=$end-$start; 
        $data['Pexp'] = substr($export, $start, $len);
        return $data;
	}
	
	function getRefMethod($obj, $method){	  //varDumper::dump($method, 10);  
        $ref = ['types'=>[],'getParameters','getDocComment','getNumberOfRequiredParameters','getNumberOfParameters','getNamespaceName','getStaticVariables','inNamespace','returnsReference','getDeclaringClass','getModifiers','getStartLine','getEndLine','getExtension','getExtensionName','getFileName'];
		$ref['types']= ['isUserDefined','isPublic','isProtected','isStatic','isPrivate','isClosure','isDeprecated','isInternal','isAbstract','isConstructor','isDestructor','isFinal'];
        foreach($method as $c){		// varDumper::dump($c, 10);   //echo "\n".$i ." ".gettype($c). "\n".$c;
            $data[$c->name]['summary'] = (string)$c;
            $data[$c->name]['detail']= array_merge( (array)$c, $this->getReflecValue($c, $ref) );
        }   //print_r($data);die();
		return $data;
	}

    protected function getAllProperties($obj,$c_properties, $c_constants, $exclude){
        $obj = (array) $obj;
        $p = (array)$c_properties;   //print_r($p); die();//print_r($obj);
        $ref_p=[];
        
        // CONVERT REFLECTION PROPERTY OBJECT TO NEW ARRAY
       foreach($p as $k=>$v){
            $ref_p[$v->name] = $v;
        }                                           //print_r($ref_p); die();

        // COMPARE OBJECT $OBJ WITH $REF_P IF KEY EXISTS ( Coz in $obj only public properties on there ) 
        foreach($obj as $k=>$v){                                               // ECHO "\n {$k} : ";
            if(array_key_exists($k, $ref_p)){
                $k_search = $k;     //echo "\n".$k." ADDDA"; 
                unset($ref_p[$k]);
            }
            else{
                //$c = explode('_',trim($k));                       print_r($c);
                $c = preg_split('/\\x00/', trim($k));                  //print_r($c);  if(count($c)==2){  echo preg_match('/[*]/', $c[0]); }
                
                if(count($c)==1)
                    $k_search = $c[0];  
                else{
                    $k_search = $c[1];  //echo "\n".$k." +++++++>".$k_search;  
                    //$k_search = (preg_match('/[*]/', $c[0])) ? $c[0] 
                }
                if(array_key_exists($k_search, $ref_p))
                    unset($ref_p[$k_search]);
            }
        }
        
         if(count($ref_p) >0)
            $obj = array_merge($obj, $ref_p);
                                                                                      //  print_r($ref_p);  print_r($obj) ;  dei();
        $obj = array_merge($obj, $c_constants);                 // die(); //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
        return $this->objectToArray($obj, $exclude);
    }
    
    public function objectToArray($obj, $longNesting=[], $class_exists=[]){   //print_r( $obj );  die(); //print_r($class_exists);
        // if ((! is_array($obj)) and (! is_object($obj))) 
            // return 'BUKAN ARRAY ATAU OBJECT COY!!'; //$obj;
               
        $result=[];
        $pclass = (is_object($obj)) ? get_class($obj) : $this->className;    // echo "\n class : {$pclass}"; //die();
        
        if( ! in_array($pclass, $class_exists)) 
            array_push($class_exists, $pclass );
                                                                                            //print_r($class_exists);
        // $result[$pclass] = $this->getReflectClass($pclass, ['getDocComment']);     
        $parent_key = preg_replace('/[^a-zA-Z]/', '', $pclass);
        $result[$parent_key] = $this->getReflectClass($pclass, ['getDocComment']);     
        $result[$parent_key]['Pname'] = $pclass;   
        
        foreach ($obj as $key => $value) 
        {	                  
            if( count($c = preg_split('/\\x00/', trim($key)))==2){     //echo "\n+++++++++ key : {$key} class : {$pclass}"; print_r($c); //$c = explode('_',$p);    
                $sub_key = preg_replace('/[^a-zA-Z]/', '', $key);
                $pclass = (preg_match('/[*]/', $c[0])) ?  $pclass : trim($c[0]);    //echo "{$pclass}";
                $pkey = $c[1];
            }
            else{ 
                $sub_key = $key;       $pkey = $key;                             
            }
            //echo "\n CLASS : {$pclass} KEY========>{$key}". preg_match('/\x00/', $key). preg_replace('/[^a-zA-Z]/', '', $key);
            
            //$sub_key = (preg_match('/\x00/', $key)) ? preg_replace('/[^a-zA-Z]/', '', $key) : $key;      //  if( in_array($sub_key, $ref_p)){ echo "ADDDA"; }
            //$result[$parent_key][$sub_key] = ($this->checkProperty($pclass, $key)) ?  $this->getReflectProperties($pclass, [$key]) : null; //['Pexp'=>"{$key} tidak ada/variable is constans pada {$pclass}"];  
            $result[$parent_key][$sub_key] = ($this->checkProperty($pclass, $pkey)) ?  $this->getReflectProperties($pclass, [$pkey]) : null; //['Pexp'=>"{$key} tidak ada/variable is constans pada {$pclass}"];  
            $result[$parent_key][$sub_key]['Pname'] = $key; 
            
            if( in_array($sub_key, $longNesting)) {     //echo "\n".$key ." ". preg_replace('/[^a-zA-Z]/', '+', $key);
                $res = '<strong class="text-danger"><small class="text-muted">('.gettype($value).')</small> Long nesting..'.
                    '<a href="#" class="text-primary" onclick="getValue($(this), \''.$sub_key.'\', \'property\', 1);">Try Manual</a></strong>'; 
            }
            else{
                if(is_object($value)){        
                    $class_val = get_class($value);   
                    if($class_val=='ReflectionProperty'){      //echo "ReflectionProperty {$key}";
                        $value->setAccessible(true);
                        $res = $value->getValue( $value->class);  
                    }
                    elseif(! in_array($class_val, $class_exists)){    //echo " \n\n >> LOOPING -> class: {$class_val} key: {$key}";
                        array_push($class_exists, $class_val); 
                        $res = self::objectToArray($value, $longNesting, $class_exists);   
                    }
                    else  {               //   ECHO ", VALUE object {$class_val} EXISTSSS!";       //   replace(/([.+^=!:${}()|\[\]\/\\])/g,"")
                        $res = $class_val . "( Class is recursive (LIHAT ATAS) )";  }
                }
                elseif(is_array($value)){   
                   // if(! in_array(preg_replace('/[^a-zA-Z]/' ,'', $sub_key), $class_exists)) { print_r($value);}
                    //$res= (! in_array($sub_key, $class_exists)) ? $value : $key;
                    $res = $value;
                }
                else{                                           //  ECHO "\n {$sub_key} VALUE :". gettype($value); echo " ".$value;
                    $res = (gettype($value) == 'resource') ? "{Resource #}" : $value .' <small class="text-muted">('.gettype($value).')</small>';
                }
            }
            $result[$parent_key][$sub_key]['nilai'] = $res;
        }		//echo Json::encode($result);
		return $result;
	}
    
    protected function checkProperty($class, $p){        
        //if(preg_match('/\x00/', $p)){
        // if( count($c = preg_split('/\\x00/', trim($p)))==2){    // echo "\nclass : {$class} p : {$p}"; print_r($c); //$c = explode('_',$p);    
            // $class = (preg_match('/[*]/', $c[0])) ?  $class : trim($c[0]);
            // $p = $c[1];
        // }
            
        $refp =new ReflectionClass($class);
        return $refp->hasProperty($p);
    }
    #---------------------------------------------------------------- FROM METADATA EXT YII-1
    /**
     * Get all information about application
     * if modules of your application have controllers with same name, it will raise fatall error
     *
     */
    public function getAll(){
        $meta = array(
            'models' => $this->getMyModels(),
            'controllers' => $this->getMyControllers(),
            'modules' => $this->getMyModules(),
        );
        foreach ($meta['controllers'] as &$controller) {
            $controller = array(
                'name' => $controller,
                'actions' => $this->getActions($controller)
            );
        }

        foreach ($meta['modules'] as &$module) {
            $controllers = $this->getMyControllers($module);

            foreach ($controllers as &$controller) {
                $controller = array(
                    'name' => $controller,
                    'actions' => $this->getActions($controller, $module)
                );
            }

            $module = array(
                'name' => $module,
                'controllers' => $controllers,
                'models' => $this->getMyModels($module),
            );
        }
        return $meta;
    }
    
    /** 
     * Scans controller directory & return array of MVC controllers
     *
     * @param mixed $module
     * @param mixed $include_classes
     * @return array
     */
    public function getMyControllers($module = null)
    {								
        if ($module != null) {
           $path = implode(DIRECTORY_SEPARATOR, array(Yii::$app->getModule($module)->basePath, 'controllers'));	
        }
        else {
            $path = Yii::$app->getControllerPath();
        }                                                            // echo $path; die();
		
        // $controllers = array_filter(scandir($path), array($this, 'isController'));	
		$dir_exist = is_dir($path);
		if($dir_exist){ 
			$controllers = array_filter(scandir($path), array($this, 'isController'));	
		   
			foreach ($controllers as &$c) {	
				$c = str_ireplace('.php', '', $c);	// remove '.php'
			}		
			return $controllers;				
		}
		return array();
    }
    
    /**
     * Get actions of controller - $user_actions = $this->getActions('UserController');
     * @param mixed $controller
     * @param mixed $module
     * @return mixed
     */
    public function getActions($controller, $module = null)
    {
        if ($module != null) {
            $path = join(DIRECTORY_SEPARATOR, array(Yii::$app->getModule($module)->basePath, 'controllers'));
            $this->setModuleIncludePaths($module);
        }
        else {
            $path = Yii::$app->getControllerPath();
        }

        $actions = array();
        $file = fopen($path . DIRECTORY_SEPARATOR . $controller . '.php', 'r');
        $lineNumber = 0;
        while (feof($file) === false) {
            ++$lineNumber;
            $line = fgets($file);
            preg_match('/public[ \t]+function[ \t]+action([A-Z]{1}[a-zA-Z0-9]+)[ \t]*\(/', $line, $matches);
            if ($matches !== array()) {
                $name = $matches[1];
                $actions[] = $matches[1];
            }
        }
        return $actions;
    }
    
    /**
     * Set php include paths for module - Sets the include_path configuration option for the duration of the script. 
     * @param mixed $module
     */
    private function setModuleIncludePaths($module)
    {
        $module_path =  Yii::getAlias('@app/modules');
        set_include_path(
            join(PATH_SEPARATOR, array(            // Yii::getAlias('@app/models');
                get_include_path(),
				join(DIRECTORY_SEPARATOR,array($module_path,$module,'controllers')),
				join(DIRECTORY_SEPARATOR, array($module_path, $module,'components')),
				join(DIRECTORY_SEPARATOR, array($module_path, $module,'models')),
				join(DIRECTORY_SEPARATOR, array($module_path, $module,'vendors')),
			)
		));
    }
    
    /**
     * Get list of controllers with actions
     * @param mixed $module
     * @return array
     */
    function getControllersActions($module = null)
    {
        $c = $this->getMyControllers($module);   //print_r($c); die();
        foreach ($c as $controller) {
            $res[$controller] = array(
                'name' => $controller,
                'actions' => $this->getActions($controller, $module)
            );
        }
        return $res;
    }
    
    /**
     * Scans models directory & return array of MVC models
     * @param mixed $module
     * @param mixed $include_classes
     * @return array
     */
    public function getMyModels($module = null, $include_classes = false)
    {
        if ($module != null) 
            $path = join(DIRECTORY_SEPARATOR, array(Yii::$app->getModule($module)->basePath, 'models'));
        else 
            $path = Yii::getAlias('@app/models');

        $models = array();
        if (is_dir($path)) {
            $files = scandir($path);
            foreach ($files as $f) {
                if (stripos($f, '.php') !== false) {
                    $models[] = str_ireplace('.php', '', $f);
                    if ($include_classes) {
                        include_once($path . DIRECTORY_SEPARATOR . $f);
                    }
                }
            }
        }
        return $models;
    }
    
    /**
     * Used in getMyModules() to filter array of files & directories
     * @param mixed $a
     */
    private function isController($a)
    {
        return stripos($a, 'Controller.php') !== false;
    }
    
    /**
     * Returns array of module names
     */
    public function getMyModules()
    {
        return array_keys(Yii::$app->modules);
    }

    /**
     * Used in getMyModules() to filter array of files & directories
     * @param mixed $a
     */
    private function isModule($a)
    {
        return $a != '.' and $a != '..' and is_dir(Yii::getAlias('@app/modules') . DIRECTORY_SEPARATOR . $a);
    }

    public function test_get_called_class(){
        return get_called_class();
    }
    

    public function actionGetFileTree()
    {    
        $alias = '@yii';
        $root = Yii::getAlias($alias); 
        $excludeDir = ['bin','messages','views','requirements', 'web', 'bower','ezyang','fzaninotto', 'composer', 'assets','nbproject', 'config', 'console', 'cubrid', 'mssql','mysql','oci','pgsql','sqlite'];              
        $excludeFile = ['yii','classes.php','extensions.php', 'mimeTypes.php', 'autoload.php','requirements.php','index-test.php','index.php','Controller.php'];

        $postDir = isset($_POST['dir']) ? rawurldecode($_POST['dir']) : null;      //echo "<br>postdir : {$postDir}";
        $dir = $root . $postDir;  //echo "<br>dir : {$dir}"; //echo "<br>postdir: $dir <br> realpath: " . realpath($postDir);
        $excludeParent = (preg_match('/[\w+]/', $postDir)) ?  [".", ".."] : ["."];
        
        //if(preg_match('/\.\./', $dir)) 
        $alias = $this->getAlias($dir);  //echo "<b>RES ALIAS : {$alias} </b>";   die();
                                                                                               
        // set checkbox if multiSelect set to true
        $checkbox = ( isset($_POST['multiSelect']) && $_POST['multiSelect'] == 'true' ) ? "<input type='checkbox' />" : null;
        $onlyFolders = ( isset($_POST['onlyFolders']) && $_POST['onlyFolders'] == 'true' ) ? true : false;
        $onlyFiles = ( isset($_POST['onlyFiles']) && $_POST['onlyFiles'] == 'true' ) ? true : false;

        if( file_exists($dir) ) {
            $files		= scandir($dir);
            $returnDir	= substr($dir, strlen($root));  //echo "postDir: $dir - root: $root - return dir: $returnDir";
            natcasesort($files);
            $files = $this->sortFiles($dir, $files);

            if( count($files) > 2 ) { // The 2 accounts for . and ..
                echo "<ul class='jqueryFileTree'>";
                foreach( $files as $file ) {
                    $htmlRel	= htmlentities($returnDir . $file) .'/';  
                    $htmlName	= htmlentities($file);
                    $ext		= preg_replace('/^.*\./', '', $file);    //echo "<br>alias: $alias <br> file: $file <br> returnDir: $returnDir <br> postDir: $dir <br> rel : $htmlRel <br> htmlName: $htmlName";

                    // if( file_exists($dir . $file) && $file != '.' && $file != '..' ) {
                    if( file_exists($dir . $file) && !in_array($file, $excludeParent)) {    //echo "FILE : $file <br>";
                        if( is_dir($dir . $file) && (!$onlyFiles || $onlyFolders) ){
                            if($file=='Yii.php' || $file=='yiisoft' || $file=='yii2')
                                $css = "bold";
                            else $css = ( in_array($file, $excludeDir) ) ? "hidden_file muted_file" : "";

                           echo "<li class='directory collapsed {$css}'> {$checkbox} <a href='#' rel='{$htmlRel}' class='{$css}'>" . $htmlName . "</a></li>";
                        }
                        else if (!$onlyFolders || $onlyFiles){  //echo "<br>-> $file";
                            $css="";
                            if($ext =='php' && ! in_array($file, $excludeFile)){    
                                $refClass = (! in_array($file, $excludeFile)) ? $this->getAliasClass($alias, $file, $returnDir) : null;   //print_r( $refClass );  //die();
                                $aliasLabel = "<small class='muted'>({$this->getAliasLabel($alias, $file)})</small>";
                                echo $this->getFormatElement($htmlRel, $htmlName, $refClass, $aliasLabel, $ext, $checkbox);
                            }
                            else{
                                $css="muted_file hidden_file";
                                $el =  "<a href='#' rel='".$htmlRel."' class='{$css}'>".$htmlName . "</a>"; 
                                echo "<li class='file ext_{$ext} {$css}' > {$checkbox} {$el} </li>";
                            }
                        }
                    }
                }
                echo "</ul>";
            }
        }
    }
    
    protected function getFormatElement($htmlRel, $htmlName, $refClass, $aliasLabel, $ext, $checkbox){  //print_r($refClass);  return; // echo "<b>==></b>"; print_r( array_filter($refClass['ref'])); 
        $tooltip = \yii\helpers\Html::encode("<pre class='tips'>". $refClass['ref']['Pexp'] . "</pre>");
        //$tooltip = "<pre class='tooltip'>". \yii\helpers\Html::encode($refClass['ref']['Pexp']) ."</pre>";
        $css= ( preg_match('/Base/', $htmlName)) ? "hidden_file muted_file" : null;
        if($refClass['url']){
            $info = "";
            if( !$refClass['ref']['isInstantiable']){                 
                $css = "not_instantiable_class hidden_file";      // echo "<b> KELAS TYPE : </b>";   print_r($refClass);
            }
            if($refClass['ref']['constructor']){       //print_r($refClass['ref']); 
                $constructor = $refClass['ref']['constructor'];    //print_r($constructor);
                if($constructor['req_param'] !=0){
                    $css = "need_param_cons";
                    $info .= "<span class='glyphicon glyphicon-info-sign text-danger tooltip_obj' title='Param Required : {$constructor['params']}'></span>";
                }
            }   
            $el = "<a href='".$refClass['url'] ."' class='{$css} tooltip_obj' onclick='classRequest($(this));' title='{$tooltip}'>".$htmlName." {$aliasLabel}</a>" . $info;
        }
        else{
            $css = "not_instantiable_class hidden_file";
            $el = "<a href='#' rel='{$htmlRel}' class='tooltip_obj {$css}' title='{$tooltip}'>".$htmlName . "</a>"; 
        }
        return "<li class='file ext_{$ext} {$css}' > {$checkbox} {$el} </li>"; 
    }

    
    protected function sortFiles($dir, $files){
        $remain =[];
        foreach($files as $file){
            if( in_array($file, ['.', '..']) || is_file($dir .'\\' . $file) )
                $res[] = $file;
            else
            $remain[] = $file;
        }
        return array_merge($res, $remain);    //print_r($res);
    }

    protected function getAlias($dir){        //echo "<br> GET ALIAS.. : $dir";
        $search = realpath( Yii::getAlias($dir));           //echo "<br> <b>SEARCH: $search</b>";

        if(! preg_match('/D:\\\WEB\\\YII2\\\YII2-BASIC-TEST/', $search))
            return "OUT OFF APPLICATION";
    
        $aliases = Yii::$aliases;    //echo "<br>GET ROOT ALIAS : $dir real: " . $search;
        $res = array_search($search, $aliases);       //ECHO "<br>1=> $res";
        if($res)
            return $res;
        else{
            $res = $this->getAliasRecursive($search, $aliases);     //ECHO "<br> <b>2=> $res</b>";
            if($res)
                return $res;
            else{
                $arr = explode('\\', $search);  //print_r($arr);
                foreach($arr as $a){   //echo "\n". $a;
                    $pop[] = array_pop($arr);  //echo " pop : $pop";
                    $path = implode('\\', $arr);  //echo "<br> <b>new path : $path </b>";   
                  
                    if(! preg_match('/D:\\\WEB\\\YII2\\\YII2-BASIC-TEST/', $search))
                        return "OUT OFF APPLICATION";
                    elseif($res = $this->getAliasRecursive($path, $aliases)){   //ECHO "<br> <b>4=> $res</b>"; var_dump($res);
                        return $res . '\\' . implode('\\', array_reverse($pop));
                    }
                    elseif($res = array_search($path, $aliases)){     // ECHO "<br> <b>3=> $res - ". implode('\\', array_reverse($pop)) . "</b>";
                        return $res . '\\' . implode('\\', array_reverse($pop));
                    }
                }
            }       
        }
    }
    
    public function getAliasRecursive($search, $aliases){  //echo "<br>SEARCH :{$search} "; //print_r($aliases); 
        if(is_array($aliases)){
            foreach($aliases as $key=>$alias){  //echo "<br>start key: $key"; //echo "$key : <pre>"; print_r($alias); echo "</pre>";                        echo "<br> <b>USING COMPARE VALUE</b>";
                if(is_array($alias))
                    $res = $this->getAliasRecursive($search, $alias);
                else
                    $res = ($search===realpath($alias)) ? $key : false;
                if($res)
                    return $res;
            }
        }
    }
    
    protected function getAliasLabel($alias, $file){ 
        return $alias .'\\'. str_replace( ".php", "",$file);   
    }
    
    protected function getAliasClass($alias, $file, $returnDir){    //echo "<br>GET ALIAS CLASS - alias : $alias - file: $file - returnDir: $returnDir"; 
        $alias = preg_replace('[@|\/]', '\\',$alias);       //echo " file===<>: $alias<br>";  return null;
        $file = str_replace( ".php", "",$file);   
        $f_alias = $alias . '\\'. $file;
        $classAlias = str_replace('\\\\', '\\', $f_alias);            //echo "<br> ALIAS: $classAlias<br>"; die();
      
        $class['ref'] = ['isInstantiable'=>1, 'constructor'=>null, 'Pexp'=>'Yii bootstrap file.'];
        if($file=='Yii'){  
            $class['url'] = Url::to(['tools/kelas', 'obj'=>"return new Yii;"]);
            
        }else{
            $c = class_exists($classAlias, true) ? $classAlias : null;
          //  if($c) 
                $class['ref'] = $this->checkConstructor($classAlias);
            
            $class['url'] = ($c) ? Url::to(['tools/kelas', 'obj'=>"return new {$c};"]) : null;
        }   //print_r($class);
        return $class;
    }

    protected function checkConstructor($class){        //echo "->{$class}";
        // $refclass = new ReflectionClass($class);
        // $refclass  = $this->getReflectClass($class, ['getConstructor', 'type'=>['isInterface','isTrait','isInternal','isIterateable','isAbstract','isCloneable','isFinal','isInstantiable']]);  
        $refclass  = $this->getReflectClass($class, ['getConstructor','isFinal','isInstantiable']);  
        $refclass = array_reverse($refclass);    
        $constructor = array_pop($refclass);   //echo "<br>===>"; var_dump($constructor) ; print_r($refclass);
        
        if($constructor){      
            $refclass['constructor']['req_param'] = $constructor->getNumberOfRequiredParameters();
            $refclass['constructor']['params'] = ($refclass['constructor']['req_param']) ? Json::encode($constructor->getParameters()) : null; 
        }else
            $refclass['constructor'] = null;
        return $refclass;
    }
    

}
