<?php

namespace app\modules\track\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use app\modules\track\components\TrackLoggerBehavior;

/**
 * This is the model class for table "{{%track}}".
 *
 * @property int $id
 * @property string $track_number
 * @property string $status
 * @property int $created_at
 * @property int $updated_at
 */
class Track extends ActiveRecord
{
	const STATUS_NEW = 'new';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELED = 'canceled';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%track}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['track_number', 'status'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['track_number'], 'string', 'max' => 13],
			[['track_number'], 'unique'],
            [['status'], 'string', 'max' => 16],
            [['status'], 'in', 'range' => self::getStatuses()],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'track_number' => 'Номер трека',
            'status' => 'Статус',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }
	
	/**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_NEW,
            self::STATUS_IN_PROGRESS,
            self::STATUS_COMPLETED,
            self::STATUS_FAILED,
            self::STATUS_CANCELED,
        ];
    }
	
	/**
     * @return array
     */
	public static function getStatusLabels()
	{
		return [
			self::STATUS_NEW => 'Новый',
			self::STATUS_IN_PROGRESS => 'В процессе',
			self::STATUS_COMPLETED => 'Завершено',
			self::STATUS_FAILED => 'Не удалось',
			self::STATUS_CANCELED => 'Отменено',
		];
	}
	
	public function getLogs()
	{
		return $this->hasMany(TrackLog::class, ['track_id' => 'id']);
	}
	
	public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
			// Логирование изменений
			TrackLoggerBehavior::class,
        ];
    }

}
