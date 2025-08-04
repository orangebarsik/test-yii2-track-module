<?php

namespace app\modules\track\components;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use app\modules\track\models\TrackLog;

class TrackLoggerBehavior extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'logCreate',
            ActiveRecord::EVENT_AFTER_UPDATE => 'logUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'logDelete',
        ];
    }

    public function logCreate($event)
    {
        $this->saveLog('create', null, $this->owner->attributes);
    }

    public function logUpdate($event)
    {
        $this->saveLog('update', $this->owner->oldAttributes, $this->owner->attributes);
    }

    public function logDelete($event)
	{
		// Сохраняем данные до удаления
		$log = new TrackLog([
			'track_id' => $this->owner->id,
			'action' => 'delete',
			'old_attributes' => json_encode($this->owner->oldAttributes),
			'new_attributes' => null,
			'user_id' => Yii::$app->user->id,
			'changed_at' => new \yii\db\Expression('NOW()'),
		]);
		
		// Явно сохраняем лог ДО удаления трека
		if (!$log->save()) {
			Yii::error('Failed to save delete log: ' . print_r($log->errors, true));
		}
	}
	
	protected function saveLog($action, $oldAttributes, $newAttributes)
	{
		$log = new TrackLog();
		$log->track_id = $this->owner->id;
		$log->action = $action;
		$log->old_attributes = $oldAttributes ? json_encode($oldAttributes) : null;
		$log->new_attributes = $newAttributes ? json_encode($newAttributes) : null;
		$log->user_id = Yii::$app->user->id;

		if (!$log->save()) {
			Yii::error([
				'message' => 'Ошибка сохранения лога',
				'errors' => $log->getErrors(),
				'data' => $log->attributes,
			], 'track-logger');
		}
	}
	
}