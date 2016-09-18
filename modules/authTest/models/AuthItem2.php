<?php
namespace app\modules\authTest\models;

use Yii;
use yii\base\Model;
use yii\helpers\Json;
use yii\rbac\Item;

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
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['ruleName'], 'in',
                'range' => array_keys(Yii::$app->authManager->getRules()),
                'message' => 'Rule not exists'],
            [['name', 'type'], 'required'],
           // [['name'], 'unique'],
            [['name'], 'unik', 'when' => function() {    //var_dump($this->isNewRecord);   var_dump($this->_item);
                return $this->isNewRecord || ($this->_item->name != $this->name);
            }],
            [['type'], 'integer'],
            [['description', 'data', 'ruleName'], 'default'],
            [['name'], 'string', 'max' => 64]
        ];
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
        ];
    }

    public function getIsNewRecord()
    {
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
            //$this->_item->child = $this->child;
                                                print_r($this); die();
            if($isNew)
                $auth->add($this->_item);
            else
                $auth->update($oldName, $this->_item);
            return true;
        } 
        else return false;
    }
    
    public static function find($id)
    {
        $item = Yii::$app->authManager->getRole($id);
        if ($item !== null) {
            return new self($item);
        }
        return null;
    }
    
    // public function getItem()
    // {
        // return $this->_item;
    // }

}
