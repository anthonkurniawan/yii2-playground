<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property integer $code_id
 * @property string $content
 * @property integer $status
 * @property integer $create_time
 * @property integer $author
 *
 * @property CodeSample $code
 */
class Comment extends \yii\db\ActiveRecord
{
    const STATUS_PENDING=1;
	const STATUS_APPROVED=2;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code_id', 'content', 'status', 'create_time', 'author'], 'required'],
            [['code_id', 'status', 'create_time', 'author'], 'integer'],
            [['content'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code_id' => Yii::t('app', 'Code ID'),
            'content' => Yii::t('app', 'Content'),
            'status' => Yii::t('app', 'Status'),
            'create_time' => Yii::t('app', 'Create Time'),
            'author' => Yii::t('app', 'Author'),
        ];
    }
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['addComment'] =  ['code_id', 'content'];
        return $scenarios;
    }
    
    
	public function beforeSave($insert)
	{       //print_r($this->attributes);  die();
		if(parent::beforeSave($insert)){    
            $this->author = Yii::$app->user->id;
            $this->create_time = time();
            $this->status = (Yii::$app->params['commentNeedApproval']) ? self::STATUS_PENDING : self::STATUS_APPROVED;	
			return true;
		}
		else return false;  // IS UPDATE
	}
    
    // public function addComment($comment)
	// {																	//echo "Comment-->"; print_r($comment);
		// if(Yii::$app->params['commentNeedApproval'])  // true
			// $comment->status=Comment::STATUS_PENDING;
		// else
			// $comment->status=Comment::STATUS_APPROVED;				//echo "Comment-->"; dump($comment);
		// $comment->post_id=$this->id;	//echo $comment->post_id;
		// return $comment->save();
	// }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCode()
    {
        return $this->hasOne(CodeSample::className(), ['id' => 'code_id']);
    }
    
    public static function jumlah($id){
        return Comment::find()->where("code_id=$id AND status=".self::STATUS_APPROVED)->count();
    }
    
    public function getAuthorName(){
        return User::findOne($this->author)->username; //.$this->author);
    }
    
}
