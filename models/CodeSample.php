<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use \yii\helpers\Json;
//use Exception;
use yii\base\ErrorException;
use Yii;

/**
 * This is the model class for table "code_sample".
 *
 * @property integer $id
 * @property integer $class_id
 * @property string $title
 * @property string $content
 * @property string $tags
 * @property integer $create_by
 * @property string $update_by
 * @property integer $create_time
 * @property integer $update_time
 *
 * @property CodeClass $class
 */
class CodeSample extends \yii\db\ActiveRecord {

    private $_lastTags;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'code_sample';
    }

    /** OVERIDE attributes for add "classname" for search grid * */
    public function attributes() {
        return array_merge(parent::attributes(), ['classname', 'createby', 'updateby']);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['class_id', 'create_by', 'update_by', 'create_time', 'update_time', 'likes'], 'integer'],
            //[['title', 'content', 'create_by'], 'required'],
            [['title', 'content', 'create_by'], 'required', 'on' => ['default', 'create', 'update']],
            [['class_id'], 'default'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 128],
            [['title'], 'unique'],
            [['tags'], 'string', 'max' => 255],
            [['slug_url'], 'string', 'max' => 255],
            [['id', 'title', 'content', 'tags', 'update_by', 'classname', 'createby', 'updateby'], 'safe', 'on' => 'search'], // http://127.0.0.1/yii2doc/yii-validators-validator.html#$builtInValidators-detail
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'class_id' => Yii::t('app', 'Class ID'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'tags' => Yii::t('app', 'Tags'),
            'create_by' => Yii::t('app', 'Create By'),
            'update_by' => Yii::t('app', 'Update By'),
            'create_time' => Yii::t('app', 'Create Time'),
            'update_time' => Yii::t('app', 'Update Time'),
            'likes' => Yii::t('app', 'Likes'),
            'slug_url' => Yii::t('app', 'Slug URL'),
                // [['Format'], 'in', 'range' => ['MD', 'HTML']],
        ];
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['class_id', 'title', 'content', 'tags'];
        $scenarios['update'] = ['title', 'content', 'tags', 'update_by'];
        $scenarios['addComment'] = ['code_id', 'content'];
        return $scenarios;
    }

    public function behaviors() {
        return [
            'timestamp' => [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    $this::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    $this::EVENT_BEFORE_UPDATE => ['update_time'],
                // 'createdAtAttribute' => 'create_time',
                // 'updatedAtAttribute' => 'update_time',
                ],
                'value' => function ($event) {       //new \yii\db\Expression('NOW()'),
            return time();
        }
            ],
            # This is a super-convient way of doing things. Every time a model gets created or updated, we know who to blame.
            'blameable' => [
                'class' => 'yii\behaviors\BlameableBehavior', //BlameableBehavior::className(),
                // 'createdByAttribute' => 'create_by',
                // 'updatedByAttribute' => 'update_by',
                'attributes' => [
                    $this::EVENT_BEFORE_INSERT => ['create_by', 'update_by'],
                    $this::EVENT_BEFORE_UPDATE => ['update_by'],
                //ActiveRecord::EVENT_BEFORE_VALIDATE => ['update_by', 'create_by']
                ],
            // 'value'=>'test'
            ],
            'sluggable' => [
                'class' => 'yii\behaviors\SluggableBehavior',
                'attribute' => 'title', # default : 'name', attribute or list of attributes whose value will be converted into a slug
                'ensureUnique' => true, // So in case of John-Doe existense John-Doe-1 slug will be generated. Note that you can also specify your own unique generator by setting $uniqueSlugGenerator callable.
                // In case of attribute that contains slug has different name 'slugAttribute' => 'alias',
                'slugAttribute' => 'slug_url', # attribute that will receive the slug value
                'value' => function ($event) {
                    return str_replace(' ', '-', $this->slug);
                    //return str_replace(' ', '-', $event->owner->slug);
                }
            ],
        ];
    }

    /**
     * This is invoked before the record is saved.
     * @return boolean whether the record should be saved.
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            //echo "<pre>".$_POST['CodeClass']['classname'];  print_r(Yii::$app->request->post()); print_r($this->attributes); echo "</pre>"; var_dump($this->class_id==null); die();
            # KLO CLASS ID BELUM ADA, MAKA INSERT NEW CLASS ID, AND RETURN PK
            if ($this->class_id == null)
                $this->class_id = $this->checkClass($_POST['CodeClass']['classname']);
            return true;
        } else
            return false;  // IS UPDATE
    }

    protected function checkClass($classname) {
        $model = new CodeClass;
        $class = $model->findOne(['classname' => $classname]);
        if ($class != null)
            return $class->id;

        $model->classname = $classname;
        if ($model->save())
            return $model->primaryKey;
        else
            throw new ErrorException(Json::encode($model->errors));
    }

    /**
     * This is invoked when a record is populated with data from a find() call.
     */
    public function afterFind() {               //echo "<BR>AFTER FIND {$this->tags}";
        parent::afterFind();
        $this->_lastTags = $this->tags;
    }

    /**
     * This is invoked after the record is saved.
     */
    public function afterSave($insert, $changedAttributes) {                     //  echo "<BR>AFTER SAVE old {$this->_lastTags}, new {$this->tags}"; var_dump($insert); print_r($changedAttributes); print_r($this->attributes); die();
        parent::afterSave($insert, $changedAttributes);
        $tags = new Tags;
        $tags->updateFrequency($this->_lastTags, $this->tags);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClass() {   //print_r($this->hasOne(CodeClass::className(), ['id' => 'class_id']) );  die();
        return $this->hasOne(CodeClass::className(), ['id' => 'class_id']);
    }

    public function getComments() {
        return $this->hasMany(Comment::className(), ['code_id' => 'id']);
    }

    public function getUsers() {
        return $this->hasOne(User::className(), ['id' => 'create_by']);
    }

    public function getUpdateByUser() {
        return $this->hasOne(User::className(), ['id' => 'update_by']);
    }

    public function getCommentCount($id, $withStatus = false) {
        if ($withStatus) {
            $status = (Yii::$app->params['commentNeedApproval']) ? Comment::STATUS_PENDING : Comment::STATUS_APPROVED;
            return Comment::find()->where("code_id=$id AND status= $status")->count();
        } else
            return Comment::find()->where("code_id=$id")->count();
    }

    // public function getTags()
    // {
    // return $this->hasMany(Tags::className(), ['ID' => 'TagsID'])
    // ->viaTable(Articlestags::tableName(), ['ArticlesID' => 'ID']);
    // }

    protected function filterInput($params, $allow = ['sort', 'page', 'per-page', 'CodeSample']) {  //echo "<pre>";  print_r($params); echo "<pre>"; 
        foreach ($params as $key => $value) {
            if (!in_array($key, $allow)) {
                //Yii::$app->session->setFlash('msg', 'Invalid sort value.');
                throw new \yii\web\BadRequestHttpException("Params $key is not allowed");
            } elseif (is_array($value))
                self::filterInput($value, $this->activeAttributes());
            elseif ($key == 'sort') {
                if (!in_array($value, $this->activeAttributes()))
                    throw new \yii\web\BadRequestHttpException("Params $value is not allowed");
            }
        }
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params) {
        $this->filterInput($params);    # FILTER PARAMS INPUT ALLOWED TO THE SERVER 
        $query = CodeSample::find();
        $query->joinWith(['class' => function($query) {
                $query->from(['class' => 'code_class']);
            }]);
                if (isset($params['CodeSample']['updateby']) && $params['CodeSample']['updateby'] != '') {
                    $query->joinWith('updateByUser');  // CodeSample[updateby]  updateByUser
                    //$query->orderBy('code_sample.update_by');
                } else
                    $query->joinWith('users');  // CodeSample[updateby]  

                $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => [
                        'pageSize' => (Yii::$app->session->has('pageSize')) ? Yii::$app->session->get('pageSize') : Yii::$app->params['pageSize'],
                    ],
                    'sort' => [
                        'enableMultiSort' => true,
                        'attributes' => [
                            'id', 'classname', 'title', 'content', 'tags', 'updateby',
                            //'createby',
                            'createby' => [
                                'asc' => ['user.username' => SORT_ASC],
                                'desc' => ['user.username' => SORT_DESC],
                                //'label' => 'Full Name',
                                'default' => SORT_ASC
                            ],
                            'updateby' => [
                                'asc' => ['user.username' => SORT_ASC],
                                'desc' => ['user.username' => SORT_DESC],
                            ],
                        ]
                    ],
                        //'db'=> //if not set, he default DB connection will be used.
                ]);

                $this->load($params);     //echo "<pre>";  print_r($this->attributes); echo "<pre>";  die();

                if (!$this->validate()) {
                    print_r($this);
                    die();
                    // uncomment the following line if you do not want to any records when validation fails
                    // $query->where('0=1');
                    return $dataProvider;
                }

                $query->andFilterWhere([
                    'id' => $this->id,
                    'class_id' => $this->class_id,
                    'create_by' => $this->create_by,
                        //'create_time' => $this->create_time,
                        //'update_time' => $this->update_time,
                ]);

                $query->andFilterWhere(['like', 'title', $this->title])
                        ->andFilterWhere(['like', 'content', $this->content])
                        ->andFilterWhere(['like', 'tags', $this->tags])
                        ->andFilterWhere(['like', 'update_by', $this->update_by])
                        ->andFilterWhere(['like', 'classname', $this->classname])
                        ->andFilterWhere(['like', 'username', $this->createby])
                        ->andFilterWhere(['like', 'username', $this->updateby]);

                return $dataProvider;
            }

        }

        /*
\yii\helpers\Url::to(['article/view', 'id'=>$model->id, 'slug'=>$model->slug])
You could also add helpers in your model :

public function getRoute()
{
    return ['article/view', 'id'=>$this->id, 'slug'=>$this->slug];
}

public function getUrl()
{
    return \yii\helpers\Url::to($this->getRoute());
    
    */
    
/*
New and advanced usages

The Yii ActiveRecord, as I�ve described it so far, is straight forward. Let�s make it interesting and go into the new and changed functionality in Yii 2.0 a bit more.

Dirty attributes

Yii 2.0 introduced the ability to detect changed attributes. For ActiveRecord, these are called dirty attributes because they require a database update. This ability now by default allows you to see which attributes changed in a model and to act on that. When, for example, you�ve massively assigned all the attributes from a form POST you might want to get only the changed attributes:

//Get a attribute => value array for all changed values
$changedAttributes = $model->getDirtyAttributes();
 
//Boolean whether the specific attribute changed
$model->isAttributeChanged('someAttribute');
 
//Force an attribute to be marked as dirty to make sure the record is 
// updated in the database
$model->markAttributeDirty('someAttribute');
 
//Get on or all old attributes
$model->getOldAttribute('someAttribute');
$model->getOldAttributes();
Arrayable

The ActiveRecord, being extended from Model, now implements the \yii\base\Arrayable trait with it�s toArray() method. This allows you to convert the model with attributes to an array quickly. It also allows for some nice additions.

Normally a call to toArray() would call the fields() function and convert those to an array. The optional expand parameter of toArray() will additionally call extraFields() which dictates which fields will also be included.

These two fields methods are implemented by BaseActiveRecord and you can implement them in your own model to customize the output of the toArray() call.

I�d like, in my example, to have the extended array contain all the tags of an article available as a comma separated string in my array output as well;

public function extraFields()
{
    return [
        'tags'=>function() {
            if (isset($this->tags)) {
                $tags = [];
                foreach($this->tags as $articletag) {
                    $tags[] = $articletag->Tag;
                }
                return implode(', ', $tags);
            }
        }
    ];
}
And then get an array of all the fields and this extra field from the model;

1
2
//Returns all the attributes and the extra tags field
$article->toArray([], ['tags']);
Events

Yii 1.1 already implemented various events on the CActiveRecord and they�re still there in Yii 2.0. The ActiveRecord life cycle in the Yii 2.0 guide shows very nicely how all these events are fired when using an ActiveRecord. All the events are fired surrounding the normal actions of your ActiveRecord instance. The naming of the events is quite obvious so you should be able to figure out when they are fired; afterFind(), beforeValidate(), afterValidate(), beforeSave(), afterSave(), beforeDelete(), afterDelete().

In my example, the LastEdited attribute is a nice way to demonstrate the use of an event. I want to make sure LastEdited always reflects the last time the article was edited. I could set this on two events; beforeSave() and beforeValidate(). My model rules define LastEdited as a required attribute so we need to use the beforeValidate() event to make sure it is also set on new instances of the model;

public function beforeValidate($insert)
{
    if (parent::beforeValidate($insert)) {
        $this->LastEdited = new \yii\db\Expression('NOW()');
        return true;
    }
    return false;
}

Transactional operations

The last feature I want to touch is the possibility to automatically force the usage of transactions in a model. With the enforcement of foreign keys also comes the possibility for database queries to fail because of that. This can be handled more gracefully by wrapping them in a transaction. Yii allows you to specify operations that should be transactional by implementing a transactions() function in your model that specifies which operations in which scenarios should be enclosed in a transaction. Note that you should return a rule for the SCENARIO_DEFAULT if you want this to be done by default on operations.
public function transactions()
{
    return [
        //always enclose updates in a transaction
        \yii\base\Model::SCENARIO_DEFAULT => self::OP_UPDATE,        
        //include all operations in a transaction for the 'editor' scenario
        'editor' => self::OP_INSERT | self::OP_UPDATE | self::OP_DELETE,
    ];
}


*/
