<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
    public $profiles_lastname;
    public $profiles_fullname;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status','groups'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'profiles_lastname', 'profiles_fullname'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {   
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
        return array_merge(parent::scenarios(), ['search'=> ['id','username', 'password', 'role', 'email'],]);
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params=[])
    {
        //$query = User::find()->with('profiles'); 
        if( isset($params['sort']) && ($params['sort']=='profiles_fullname' || $params['sort']=='-profiles_fullname') ){
            //print_r($params);  die(); //echo $params['UserSearch2']['profiles.fullname'];  die();
            $query = User::find()->select(['*', 'profiles_fullname'=>'CONCAT(profiles.firstname, profiles.lastname)'] )
                ->joinWith(['profiles' => function($query) { $query->from(['profiles' => 'profile']); }]);       //print_r($query); die();
        }
        else  $query = User::find()-> joinWith(['profiles' => function($query) { $query->from(['profiles' => 'profile']);}]);    
            
        # SAMPE USING LEFT JOIN
        // $subQuery = Order::find()
        // ->select('customer_id, SUM(amount) as order_amount')
        // ->groupBy('customer_id');
        // $query->leftJoin(['orderSum' => $subQuery], 'orderSum.customer_id = id');
        
        // $query = Customer::find()->select(['tbl_customer.*', 'orderSum.orderAmount']);
        // $subQuery = Order::find()
            // ->select('customer_id, SUM(amount) as orderAmount')
            // ->groupBy('customer_id');
        // $query->leftJoin(['orderSum' => $subQuery], 'orderSum.customer_id = id');
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 5,
                'pageSize' => 5, //(Yii::$app->session->has('pageSize')) ? Yii::$app->session->get('pageSize') : Yii::$app->params['pageSize'],
            ],
            'sort'=>[
                'attributes'=>[
                    'id', 
                    'username', 
                    'groups',
                    'status',
                    'profiles_fullname',
                    'profiles_lastname'=>[
                        'asc' => ['profiles.lastname' => SORT_ASC],
                        'desc' => ['profiles.lastname' => SORT_DESC],
                         //'label' => 'Full Name',
                        //'default' => SORT_ASC
                    ],  'updated_at'
                ]
            ],
            //'db'=> //if not set, he default DB connection will be used.
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'user.id' => $this->id,
            'status' => $this->status,
            'groups' => $this->groups,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'profiles.lastname', $this->profiles_lastname]);
            
        if(isset($params['UserSearch']['profiles_fullname']) && $params['UserSearch']['profiles_fullname'] !=''){
            // $query->andWhere('firstname LIKE "%' . $this->getAttribute('fullname') . '%" ' .
                // 'OR lastname LIKE "%' . $this->getAttribute('fullname') . '%"'
            // );
            $query->andWhere('firstname LIKE "%' . $this->profiles_fullname . '%" ' .
                'OR lastname LIKE "%' . $this->profiles_fullname . '%"'
            );
        }
        return $dataProvider;
    }
}
