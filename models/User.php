<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
# Implemnt link -HATEOAS. Your resource classes may support HATEOAS by implementing the yii\web\Linkable interface. 
use yii\web\Link;
use yii\web\Linkable;
use yii\helpers\Url;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface, Linkable, \yii\filters\RateLimitInterface
{
    public $password;
    
    const STATUS_BANNED = 0;  // entah di pake buat apa?
    const STATUS_ACTIVE = 10;
    const STATUS_UNACTIVE = 20;
    const GROUP_ADMIN = 1;
    const GROUP_AUTHOR = 2;
    const GROUP_BANNED = 3;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['groups', 'default', 'value' => self::GROUP_AUTHOR],
            ['status', 'in', 'range' => [self::STATUS_BANNED, self::STATUS_ACTIVE, self::STATUS_UNACTIVE ]],
            //['status', 'in', 'range' => ['Active', 'Unactive']],
            [['username', 'auth_key', 'password_hash', 'email','status','groups', 'created_at', 'updated_at', 'password', 'access_token'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'string', 'min' => 2],
            ['username', 'unique', 'message' => 'This username has already been taken.'],
            ['password', 'string', 'min' => 4],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['email', 'unique', 'message' => 'This email address has already been taken.'],
            [['auth_key'], 'string', 'max' => 32],
            
           // [['username', 'email', 'password'], 'required', 'on'=>['create', 'update']], 
            //[['lastname'], 'safe', 'on'=>'search']
        ];
    }
    
    // Returns the list of fields that should be returned by default by [[toArray()]] when no specific fields are specified.
    // in your DB table or model attributes do not cause your field changes (to keep API backward compatibility).
    # see : http://localhost/yii2doc/guide-rest-resources.html#overriding-fields
    public function fields()
    {
        /*
        # override the attribute value yii\base\Model::fields()
        return [
            'id', // field name is the same as the attribute name
            // field name is "password", the corresponding attribute name is "password_hash"
            'password' => 'password_hash',
            // field name is "name", its value is defined by a PHP callback
            'name' => function ($model) {
                return $model->first_name . ' ' . $model->last_name;
            },
        ];
        */
       # filter out some fields, best used when you want to inherit the parent implementation and blacklist some sensitive fields. 
        $fields = parent::fields();
        // remove fields that contain sensitive information
        unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token'], $fields['access_token']);
        return $fields;
    }
    
    # Override yii\db\baseActiveRecord - returns the names of the relations that have been populated into this record.
    public function extraFields()
    {
        return ['profiles'];
    }
    
    
    # SCENARIO USED a model for different purpose. sample : used for login and register
    # By default, a model supports only a single scenario named "default".
    # By default, the scenarios supported by a model are determined by the validation rules declared in the model. 
    # However, you can customize this behavior by overriding the yii\base\Model::scenarios() method, like the following:
    /* // scenario is set as a property
    $model = new User;
    $model->scenario = 'login';

    // scenario is set through configuration
    $model = new User(['scenario' => 'login']);
    */
     public function scenarios()
    {   
        return array_merge(parent::scenarios(), [
            'login' => ['username', 'password'],
            'create' => ['username', 'email', 'password','status','groups'],
            'update' => ['username', 'email', 'status','groups'],
            'reset_password'=>['email'],
        ]);
    }
    
    /**
     * @inheritdoc
     * By default, attribute labels are automatically generated from attribute names. The generation is done by the method yii\base\Model::generateAttributeLabel().
     * echo $model->getAttributeLabel('name');
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
            'group' => Yii::t('app', 'Group'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'access_token' => Yii::t('app', 'Access Token'),
        ];
    }
    
    # CREATE RELATION
    public function getProfiles()
    {   
        return $this->hasOne(Profile::className(), ['id' => 'id']);
    }
    
    public function getPosts()
    {   
        return $this->hasMany(CodeSample::className(), ['create_by' => 'id']);
    }
    #VIA SAMPLE
    public function getPostComments()
    {
        return $this->hasMany(Comment::className(), ['id' => 'class_id'])->via('posts');
    }

    
    public function beforeSave($insert)
	{      
		if(parent::beforeSave($insert)){    
            $this->setPassword($this->password);
            $this->generateAuthKey();       // echo "<pre>";print_r($this->attributes); echo "</pre>";die();
            $this->generateAccessToken();
			return true;
		}
		else return false;  // IS UPDATE
	}
   
    public function getStatusText($status){  
        return $this->getStatusList(true)[$status];
    }
    
    public function getGroupText($group){  
        return $this->getGroupList(true)[$group];
     }
     
    public function getStatusList($view=false){       
        return ($this->isNewRecord && !$view) ? [self::STATUS_ACTIVE=>'Active', self::STATUS_UNACTIVE=>'Unactive'] : [self::STATUS_ACTIVE=>'Active', self::STATUS_UNACTIVE=>'Unactive', self::STATUS_BANNED=>'Banned'];
    }
    
    public function getGroupList($view=false){
        return ($this->isNewRecord && !$view) ? [self::GROUP_ADMIN=>'Administrator', self::GROUP_AUTHOR=>'Author'] : [self::GROUP_ADMIN=>'Administrator', self::GROUP_AUTHOR=>'Author', self::GROUP_BANNED=>'Banned'];
    }
    
    public static function getSuggest($keyword=null, $field='username'){
        // $this->find()->where('username=:param',[':param'=>'admin']);   print_r($user->all());
        $users = static::find()->where('username LIKE :name',[':name'=>"%$keyword%"])->asArray()->all();
        $suggest=[];
		foreach($users as $user) {
			$suggest[] = array(
				'value'=>$user[$field],
				'label'=>$user[$field],
			);
		}
        return $suggest;
    }
    
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }
    
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    
    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString() . '_' . $this->username;;
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    # interface Linkable --------------------------------------------------------------------------------------------------------------------------------
    # Linkable "yii\web\Linkable" is the interface that should be implemented by classes that typically represent locatable resources.
    # see : http://localhost/yii2doc/guide-rest-resources.html#links
    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['user/view', 'id' => $this->id], true),
        ];
    }
    
    # interface IdentityInterface ---------------------------------------------------------------------------------------------------------------------
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     * $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {   //echo "$token $type"; die();
        //throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        return static::findOne(['access_token' => $token]);
        //return static::findOne(['auth_key' => $token]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * This is required if [[User::enableAutoLogin]] is enabled.
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * This is required if [[User::enableAutoLogin]] is enabled.
     */
    public function validateAuthKey($authKey)
    {   
        return $this->getAuthKey() === $authKey;
    }
    
    # interface RateLimitInterface -------------------------------------------------------------------------------------------------------------
    /*
    # yii\filters\RateLimitInterface
    To enable rate limiting, the user identity class should implement yii\filters\RateLimitInterface. This interface requires implementation of three methods:

    You may want to use two columns in the user table to record the allowance and timestamp information. 
    With those defined, then loadAllowance() and saveAllowance() can be implemented to read and save the values of the two columns corresponding to the current authenticated user. 
    To improve performance, you may also consider storing these pieces of information in a cache or NoSQL storage.
    */
    /**
     * Returns the maximum number of allowed requests and the window size.
     * @param \yii\web\Request $request the current request
     * @param \yii\base\Action $action the action to be executed
     * @return array an array of two elements. The first element is the maximum number of allowed requests,
     * and the second element is the size of the window in seconds.
     
     getRateLimit(): returns the maximum number of allowed requests and the time period (e.g., [100, 600] means there can be at most 100 API calls within 600 seconds).
     */
    public function getRateLimit($request, $action){
        //print_r($request);  # yii\web\Request Object 
        # yii\rest\ViewAction Object - properties : $modelClass, $findModel, $checkAccess, $id, $controller. - method: getUniqueId(), runWithParams($params), beforeRun(), afterRun()
        # yii\rest\CreateAction Object, - properties : $scenario, $viewAction, $modelClass, $findModel, $checkAccess, $id, $controller
        //print_r($action->id);  // index, view, create, update, delete die();
        #NOTE : spertinya parameter bs gi gunakan buat kondisi nanti
        return [100, 600];
    }
    /** - loadAllowance(): returns the number of remaining requests allowed and the corresponding UNIX timestamp when the rate limit was last checked.
     * Loads the number of allowed requests and the corresponding timestamp from a persistent storage.
     * @param \yii\web\Request $request the current request
     * @param \yii\base\Action $action the action to be executed
     * @return array an array of two elements. The first element is the number of allowed requests,
     * and the second element is the corresponding UNIX timestamp.
     */
    public function loadAllowance($request, $action){   
        //return [100, 600];
        return array_values(Yii::$app->cache->mget(['allowance', 'timestamp']) );  // for all action request - ndex, view, create, update, delete die();
    }
    /** - saveAllowance(): saves both the number of remaining requests allowed and the current UNIX timestamp.
     * Saves the number of allowed requests and the corresponding timestamp to a persistent storage.
     * @param \yii\web\Request $request the current request
     * @param \yii\base\Action $action the action to be executed
     * @param integer $allowance the number of allowed requests remaining.
     * @param integer $timestamp the current timestamp.
     */
    public function saveAllowance($request, $action, $allowance, $timestamp){
       Yii::$app->cache->mset(['allowance'=>$allowance, 'timestamp'=>$timestamp]);
        //$mget = $class->mget(['allowance', 'timestamp']);  print_r($mget);
    }
}


/*  ----------------------------------- ORIGINAL FOR DEMO
class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];

    public static function findIdentity($id)
    {
        return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    public static function findByUsername($username)
    {
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public function validatePassword($password)
    {
        return $this->password === $password;
    } 
}
*/