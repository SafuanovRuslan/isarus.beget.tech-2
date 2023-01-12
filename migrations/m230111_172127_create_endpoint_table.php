<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%endpoint}}`.
 */
class m230111_172127_create_endpoint_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%endpoint}}', [
            'id'   => $this->primaryKey(),
            'date' => $this->string(10)->notNull()->unique(),
            'data' => $this->string()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%endpoint}}');
    }
}
