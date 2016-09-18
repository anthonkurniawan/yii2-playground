<?php
namespace app\components;

class MyActiveRecord extends \yii\db\ActiveRecord
{
 
     // public function behaviors()
    // {
        // return [
            // 'timestamp' => [
                // 'class' => 'yii\behaviors\TimestampBehavior',
                // 'attributes' => [
                    // ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    // ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                // ],
                // 'value' => new Expression('NOW()'),
            // ],
            // # This is a super-convient way of doing things. Every time a model gets created or updated, we know who to blame.
            // 'blameable' => [
                // 'class' => BlameableBehavior::className(),
                // 'createdByAttribute' => 'created_by',
                // 'updatedByAttribute' => 'updated_by',
                // ],
             
        // ];
     // }
 
}

?>