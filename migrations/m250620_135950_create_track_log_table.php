<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%track_log}}`.
 */
class m250620_135950_create_track_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('track_log', [
			'id' => $this->primaryKey(),
			'track_id' => $this->integer()->null(),
			'action' => $this->string(20)->notNull(), // 'create', 'update', 'delete'
			'old_attributes' => $this->text(),
			'new_attributes' => $this->text(),
			'changed_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
			'user_id' => $this->integer(),
		]);

		$this->addForeignKey(
			'fk-track_log-track_id',
			'track_log',
			'track_id',
			'track',
			'id',
			'SET NULL'
		);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%track_log}}');
    }
}
