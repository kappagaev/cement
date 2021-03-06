<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_items}}`.
 */
class m211213_040955_create_order_items_table extends Migration
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
        $this->createTable('{{%order_items}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->bigInteger()->notNull(),
            'product_id' => $this->bigInteger()->notNull(),
            'weight' => $this->integer()->notNull(),
            'order_date' => $this->date(),
            'order_time' => $this->time(),

            'wagon_type_id' => $this->bigInteger(),

        ], $tableOptions );
        $this->addForeignKey('order_items-order_id', 'order_items', 'order_id', 'order', 'id');
        $this->addForeignKey('order_items-product_id', 'order_items', 'product_id', 'product', 'id');
        $this->addForeignKey('order_items-wagon_type_id', 'order_items', 'wagon_type_id', 'wagon_type', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('order_items-order_id', 'order');
        $this->dropForeignKey('order_items-product_id', 'order');
        $this->dropForeignKey('order_items-wagon_type_id', 'order');
        $this->dropTable('{{%order_items}}');

    }
}
