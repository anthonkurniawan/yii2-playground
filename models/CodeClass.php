<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "code_class".
 *
 * @property integer $id
 * @property string $classname
 *
 * @property CodeSample[] $codeSamples
 */
class CodeClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'code_class';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['classname'], 'required'],
            [['classname'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'classname' => Yii::t('app', 'Classname'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodeSamples()
    {
        return $this->hasMany(CodeSample::className(), ['class_id' => 'id']);
    }
    
    // public static function addNewClass($classname){
        // //echo $classname; die();
        // $this->classname = $classname;
        // if($this->save())
            // echo $this->primaryKey;  die();
    // }
}
