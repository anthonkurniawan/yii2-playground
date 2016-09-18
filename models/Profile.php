<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "profile".
 *
 * @property integer $id
 * @property string $firstname
 * @property string $lastname
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname'], 'string', 'max' => 100],
            [['address', 'bio', 'gender', 'birthday'], 'string', 'max'=>255],
            
            //[['firstname', 'lastname'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'firstname' => Yii::t('app', 'Firstname'),
            'lastname' => Yii::t('app', 'Lastname'),
            'fullname' => Yii::t('app', 'Full Name'),
            'address' => Yii::t('app', 'Address'),
            'birthday' => Yii::t('app', 'Birthday'), 
            'bio' => Yii::t('app', 'Bio'),
            'gender' => Yii::t('app', 'Gender'),
        ];
    }
    
    public function getFullname() {
        return $this->firstname . ' ' . $this->lastname;
    }
 
}
