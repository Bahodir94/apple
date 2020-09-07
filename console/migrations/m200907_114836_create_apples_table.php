<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%apples}}`.
 */
class m200907_114836_create_apples_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%apples}}', [
            'id' => $this->primaryKey(),
            'color' => $this->string(10)->notNull(),
            'date_of_apperance' => $this->timestamp(),
            'date_of_fall' => $this->timestamp(),
            'status' => $this->integer()->notNull()->defaultValue(1),
            'status_of_ate' => $this->integer()->notNull()->defaultValue(100),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%apples}}');
    }
}
