<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%contract}}`.
 */
class m211209_045413_create_contract_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%contract}}', [
            'id' => $this->bigPrimaryKey(),
            'c1_id' => $this->string(),
            'user_id' => $this->string(),
            'date_from' => $this->date(),
        ], $tableOptions);
//        $this->addForeignKey('contract-user_id', 'contract', 'user_id', 'user', 'c1_id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
//        $this->dropForeignKey('contract-user_id', 'contract');
        $this->dropTable('{{%contract}}');

    }
}
