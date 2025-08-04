<?php

namespace app\modules\track\tests\functional\api;

use Yii;
use app\modules\track\models\Track;
use FunctionalTester;

class TrackCest
{
    public function _before(FunctionalTester $I)
    {
        // Отключаем проверку внешних ключей
		Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();
		
		// Очищаем таблицы в правильном порядке
		Yii::$app->db->createCommand()->truncateTable('track_log')->execute();
		Yii::$app->db->createCommand()->truncateTable('track')->execute();
		
		// Включаем проверку обратно
		Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=1')->execute();
    }

	// Тест создания трека через API
    public function testCreateTrack(FunctionalTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
		$I->haveHttpHeader('Authorization', 'Bearer 100-token');
        $I->sendPOST('/track/api/track', [
            'track_number' => 'API123',
            'status' => Track::STATUS_NEW,
        ]);
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson([
            'track_number' => 'API123',
            'status' => Track::STATUS_NEW,
        ]);
    }

	// Проверка фильтрации по статусу
    public function testGetTracks(FunctionalTester $I)
    {
        $track = new Track([
            'track_number' => 'GET123',
            'status' => Track::STATUS_IN_PROGRESS,
        ]);
        $track->save();
        
		$I->haveHttpHeader('Authorization', 'Bearer 100-token');
        $I->sendGET('/track/api/track');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'track_number' => 'GET123',
            'status' => Track::STATUS_IN_PROGRESS,
        ]);
    }

	// Тест массового обновления статусов
    public function testFilterByStatus(FunctionalTester $I)
    {
        $track1 = new Track([
            'track_number' => 'FILTER1',
            'status' => Track::STATUS_NEW,
        ]);
        $track1->save();
        
        $track2 = new Track([
            'track_number' => 'FILTER2',
            'status' => Track::STATUS_COMPLETED,
        ]);
        $track2->save();
        
		$I->haveHttpHeader('Authorization', 'Bearer 100-token');
        $I->sendGET('/track/api/track?status=' . Track::STATUS_NEW);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'track_number' => 'FILTER1',
        ]);
        $I->dontSeeResponseContainsJson([
            'track_number' => 'FILTER2',
        ]);
    }
	
	// Тест ошибок валидации
	public function testValidationErrors(FunctionalTester $I)
	{
		$I->haveHttpHeader('Authorization', 'Bearer 100-token');
		$I->sendPOST('/track/api/track', [
			'track_number' => '', // Пустое значение
			'status' => 'invalid_status'
		]);
		$I->seeResponseCodeIs(422);
		$I->seeResponseContainsJson([
			'field' => 'track_number',
			'message' => 'Необходимо заполнить «Номер трека».'
		]);
	}
}