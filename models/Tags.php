<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tag".
 *
 * @property integer $id
 * @property string $name
 * @property integer $frequency
 */
class Tags extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tags';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['frequency'], 'integer'],
            [['name'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'frequency' => Yii::t('app', 'Frequency'),
        ];
    }
    
    public function updateFrequency($oldTags, $newTags)
	{
		$oldTags=self::string2array($oldTags);
		$newTags=self::string2array($newTags);  // echo "---->"; print_r($oldTags); print_r($newTags); var_dump(array_values(array_diff($oldTags,$newTags)));
		$this->addTags(array_values(array_diff($newTags,$oldTags)));
		$this->removeTags(array_values(array_diff($oldTags,$newTags)));
	}
    
    public static function string2array($tags)
	{
		return preg_split('/\s*,\s*/',trim($tags),-1,PREG_SPLIT_NO_EMPTY);
	}
    
	public function addTags($tags)
	{
        $this->updateAllCounters(array('frequency'=>1), ['in', 'name', $tags]);
        
		foreach($tags as $name)
		{
            if(!$this::find()->where(['name'=>$name])->exists())
			{
				$tag=new Tags;
				$tag->name=$name;
				$tag->frequency=1;
				$tag->save();
			}
		}
	}

	public function removeTags($tags)
	{
		if(empty($tags))
			return;
		// $criteria=new CDbCriteria;
		// $criteria->addInCondition('name',$tags);
		// $this->updateCounters(array('frequency'=>-1),$criteria);
		// $this->deleteAll('frequency<=0');
        
        $this->updateAllCounters(array('frequency'=>-1), ['in', 'name', $tags]);
        $this->deleteAll('frequency<=0');
	}
}
