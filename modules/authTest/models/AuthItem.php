<?php
namespace app\modules\authTest\models;

use Yii;
use yii\base\Model;
use yii\helpers\Json;
use yii\rbac\Item;
use yii\web\NotFoundHttpException;

/**
 * ContactForm is the model behind the contact form.
 */
class AuthItem extends Model
{
    public $name;
    public $type;
    public $description;
    public $ruleName;
    public $data;
    public $createdAt;
    public $updatedAt;
    public $child;
    
    private $_item;
    private $_oldChild;
    // public function __construct($item=null, $config = [])
    // {
        // $this->_item = $item;
        // if ($item !== null) {
            // $this->name = $item->name;
            // $this->type = $item->type;
            // $this->description = $item->description;
            // $this->ruleName = $item->ruleName;
            // $this->data = $item->data === null ? null : Json::encode($item->data);
            // $this->createdAt = $item->createdAt;
            // $this->updatedAt = $item->updatedAt;
            // $this->child = $this->getChildren($item->name);
        // }
        // parent::__construct($config);
    // }
   

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'description' => Yii::t('app', 'Description'),
            'ruleName' => Yii::t('app', 'Rule Name'),
            'data' => Yii::t('app', 'Data'),
            'child' => Yii::t('app', 'Children'),
        ];
    }
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['ruleName'], 'in',
                'range' => array_keys(Yii::$app->authManager->getRules()),
                'message' => 'Rule not exists'
            ],
            [['name', 'type'], 'required'],
           // [['name'], 'unique'],
            [['name'], 'unik', 'when' => function() {    //var_dump($this->isNewRecord);   var_dump($this->_item);
                return $this->isNewRecord || ($this->_item->name != $this->name);
            }],
            [['type'], 'integer'],
            [['description', 'data', 'ruleName'], 'default'],
            [['name'], 'string', 'max' => 64],
            [['child'], 'childExist', 'when' => function($model, $attribute) {
               return $this->child !== $this->_oldChild;
            }],
        ];
    }
    
    public function childExist($attribute, $params){   //echo "atr: $attribute \n";   echo "param: $params";
        if($this->child){
            $auth = Yii::$app->authManager;
            foreach(explode(',', $this->child) as $child){   
                $child = trim($child);  
                if($child!=null){
                    if(! $item = $auth->getItem( $child))
                        $this->addErrors(['child'=> "Adding child: $child item not exists"]);   //return $this->sendError($child);  
                }
            }
        }
    }

    public function unik()
    {
        $authManager = Yii::$app->authManager;
        $value = $this->name;
        if ($authManager->getRole($value) !== null || $authManager->getPermission($value) !== null) {
            $message = Yii::t('yii', '{attribute} "{value}" has already been taken.');
            $params = [
                'attribute' => $this->getAttributeLabel('name'),
                'value' => $value,
            ];
            $this->addError('name', Yii::$app->getI18n()->format($message, $params, Yii::$app->language));
        }
    }
    
    public static function loadModel($name, $type=null){  
        $auth = Yii::$app->authManager;
        $item = ($type ==='Rule') ? $auth->getRule($name) : $auth->getItem($name);  // "getItem()" BISA LOAD ALL TYPE. (permission, role, rule)  $auth->getRole($name) : $auth->getPermission($name);
                                                
        if ($item){    //echo "LOADDD...";  print_r($item);
            $model = new self(); 
            $model->setItem($item);     //print_r($obj);  die();
            return $model;
        }else {
            if(Yii::$app->request->isAjax){
                echo "Auth Item '$name' not found";
                die();
            }else
                throw new NotFoundHttpException("Auth Item '$name' not found");
        }
    }
    
    public static function loadAuthItem($type=null){  
        $auth = Yii::$app->authManager;
        $items = ($type) ? $auth->getItems($type) : array_merge( $auth->getItems(item::TYPE_ROLE), $auth->getItems(item::TYPE_PERMISSION));
        return $items;
    }
    
    // public function getAutoComplete(){ 
        // $type = ($this->type==1) ? null : $this->type;
        // $items = self::loadAuthItem($type);
        // return array_keys($items);
    // }
    
    public function getChildren($name){
        $auth = Yii::$app->authManager; 
        $child = $auth->getChildren($name);    
        return (count($child) > 0) ? $this->formatString($child) : null;
    }
    
    protected function formatString($data, $attr='name'){
        $res =[];
        foreach($data as $val){
            $res[] = $val->$attr;
        }
        return implode(', ', $res);
    }

    public function getIsNewRecord(){
        return $this->_item === null;
    }
    
    public function save()
    {       
        //var_dump($this->_item);   print_r($this); var_dump($this->isNewRecord);  die(); //print_r($this); die();
        if ($this->validate()) {
            $auth = Yii::$app->authManager;
            if ($this->isNewRecord) {
                $this->_item = ($this->type == Item::TYPE_ROLE) ? $auth->createRole($this->name) : $auth->createPermission($this->name);
                $isNew = true;
            } 
            else{
                $isNew = false;        
                $oldName = $this->_item->name;
            }

            $this->_item->name = $this->name;
            $this->_item->description = $this->description;
            $this->_item->ruleName = $this->ruleName;
            $this->_item->data = $this->data === null || $this->data === '' ? null : Json::decode($this->data);
                                                
            $item = ($isNew) ? $auth->add($this->_item) : $auth->update($oldName, $this->_item);
            
            if($this->child || $this->_oldChild)
                return $this->saveChild($auth, $this->child);  
            return true;
       } 
        else return false;
    }
    
    public function saveChild($auth){     // print_r($this->_item);  //print_r($auth);  
        if($this->child){
            $childs = explode(',', $this->child);
            $auth->removeChildren($this->_item);
            
            foreach($childs as $child){
                if(trim($child) !=null){
                    try{
                        $auth->addChild($this->_item, $auth->getItem( trim($child)));
                    }
                    catch (yii\base\InvalidCallException $e) {   
                        $this->addError('child', $e->getMessage());
                        return false;
                    }
                }
            }
        }
        return true;
    }
    
    public static function find($id)
    {
        $item = Yii::$app->authManager->getRole($id);
        if ($item !== null) {
            return new self($item);
        }
        return null;
    }
    
    public function getItem()
    {
        return $this->_item;
    }
    
    public function afterValidate(){
        $this->child = $_POST['AuthItem']['child'];
        //return true;
    }
    
    public function setItem($item){
        $this->_item = $item;
        $this->_oldChild = $this->getChildren($item->name);
        if ($item !== null) {
            $this->name = $item->name;
            $this->type = $item->type;
            $this->description = $item->description;
            $this->ruleName = $item->ruleName;
            $this->data = $item->data === null ? null : Json::encode($item->data);
            $this->createdAt = $item->createdAt;
            $this->updatedAt = $item->updatedAt;
            $this->child = $this->getChildren($item->name);
        }
    }

    public function sendError($item){
       // if(Yii::$app->request->isAjax)
            echo "$item not found";
        return false;
        
    }
}
