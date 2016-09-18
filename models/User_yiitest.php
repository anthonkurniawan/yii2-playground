<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User_yiitest;

/**
 * This is the model class for table "{{%tbl_user_mysql}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $role
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $create_time
 * @property string $update_time
 * @property string $reset_token
 * @property string $activation_key
 * @property string $validation_key
 * @property string $active_date
 * @property string $login_ip
 * @property integer $login_attemp
 * @property string $last_login
 * @property integer $active
 * @property integer $gallery_id
 */
class User_yiitest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tbl_user_mysql}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_yiitest');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'role', 'email', 'first_name'], 'required', 'on'=>'default'],  // buat create, update
            [['role'], 'string'],
            [['create_time', 'update_time', 'active_date', 'last_login'], 'safe'],
            [['login_attemp', 'active', 'gallery_id'], 'integer'],
            [['username', 'password', 'email', 'first_name'], 'string', 'max' => 50],
            [['last_name'], 'string', 'max' => 30],
            [['reset_token', 'activation_key'], 'string', 'max' => 100],
            [['validation_key'], 'string', 'max' => 255],
            [['login_ip'], 'string', 'max' => 32],
            [['id', 'username', 'password', 'role', 'email', 'first_name', 'last_name', 'create_time', 'update_time', 'reset_token', 'activation_key', 'validation_key', 'active_date', 'login_ip', 'last_login'], 'safe', 'on'=>'search']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'role' => Yii::t('app', 'Role'),
            'email' => Yii::t('app', 'Email'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'create_time' => Yii::t('app', 'Create Time'),
            'update_time' => Yii::t('app', 'Update Time'),
            'reset_token' => Yii::t('app', 'Reset Token'),
            'activation_key' => Yii::t('app', 'Activation Key'),
            'validation_key' => Yii::t('app', 'Validation Key'),
            'active_date' => Yii::t('app', 'Active Date'),
            'login_ip' => Yii::t('app', 'Login Ip'),
            'login_attemp' => Yii::t('app', 'Login Attemp'),
            'last_login' => Yii::t('app', 'Last Login'),
            'active' => Yii::t('app', 'Active'),
            'gallery_id' => Yii::t('app', 'Gallery ID'),
        ];
    }
    
    // public function scenarios()
    // {
        // //return Model::scenarios();
        // return array_merge(parent::scenarios(), ['search'=> ['id','username', 'password', 'role', 'email'],]);
    // }
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User_yiitest::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'active_date' => $this->active_date,
            'login_attemp' => $this->login_attemp,
            'last_login' => $this->last_login,
            'active' => $this->active,
            'gallery_id' => $this->gallery_id,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'role', $this->role])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'reset_token', $this->reset_token])
            ->andFilterWhere(['like', 'activation_key', $this->activation_key])
            ->andFilterWhere(['like', 'validation_key', $this->validation_key])
            ->andFilterWhere(['like', 'login_ip', $this->login_ip]);

        return $dataProvider;
    }
}
