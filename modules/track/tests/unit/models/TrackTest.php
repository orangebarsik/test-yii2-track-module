<?php

namespace app\modules\track\tests\unit\models;

use app\modules\track\models\Track;
use yii\base\InvalidConfigException;

class TrackTest extends \Codeception\Test\Unit
{
	// Проверка создания трека с валидными данными
    public function testCreateTrack()
    {
        $track = new Track([
            'track_number' => 'TEST123',
            'status' => Track::STATUS_NEW,
        ]);
        
        $this->assertTrue($track->save());
        $this->assertEquals(Track::STATUS_NEW, $track->status);
        $this->assertNotNull($track->created_at);
        $this->assertNotNull($track->updated_at);
    }

	// Тест уникальности номера трека
    public function testUniqueTrackNumber()
    {
        $track1 = new Track([
            'track_number' => 'TEST123',
            'status' => Track::STATUS_NEW,
        ]);
        $track1->save();
        
        $track2 = new Track([
            'track_number' => 'TEST123',
            'status' => Track::STATUS_NEW,
        ]);
        
        $this->assertFalse($track2->save());
        $this->assertArrayHasKey('track_number', $track2->errors);
    }

	// Проверка недопустимых статусов
    public function testInvalidStatus()
    {
        $track = new Track([
            'track_number' => 'TEST456',
            'status' => 'invalid_status',
        ]);
        
        $this->assertFalse($track->save());
        $this->assertArrayHasKey('status', $track->errors);
    }
	
	// Проверка автоматического заполнения дат
	public function testTimestamps()
	{
		$track = new Track(['track_number' => 'AUTO123', 'status' => 'new']);
		$this->assertNull($track->created_at);
		$this->assertNull($track->updated_at);
		
		$track->save();
		$this->assertNotNull($track->created_at);
		$this->assertNotNull($track->updated_at);
	}
}