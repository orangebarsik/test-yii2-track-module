<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%track}}`.
 */
class m250620_071725_create_track_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%track}}', [
            'id' => $this->primaryKey(),
            'track_number' => $this->string(16)->notNull()->unique(),
            'status' => $this->string(16)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Добавляем индекс для статуса для ускорения фильтрации
        $this->createIndex('idx-track-status', '{{%track}}', 'status');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%track}}');
    }
}
