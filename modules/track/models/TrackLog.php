<?php

namespace app\modules\track\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class TrackLog extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%track_log}}';
    }

    public function rules()
    {
        return [
            [['track_id', 'action'], 'required'],
            [['track_id'], 'integer'],
            [['old_attributes', 'new_attributes'], 'string'],
            [['changed_at'], 'safe'],
            [['action'], 'string', 'max' => 20],
        ];
    }
	
	public function attributeLabels()
    {
        return [
            'track_id' => 'Посылка',
            'action' => 'Действие',
            'changed_at' => 'Изменено',
			'user_id' => 'Id пользователя',
        ];
    }
	
	public function getTrack()
	{
		return $this->hasOne(Track::class, ['id' => 'track_id']);
	}
	
	public function behaviors()
	{
		return [
			[
				'class' => TimestampBehavior::class,
				'createdAtAttribute' => 'changed_at',
				'updatedAtAttribute' => false,
				'value' => new \yii\db\Expression('NOW()'),
			],
		];
	}
	
}