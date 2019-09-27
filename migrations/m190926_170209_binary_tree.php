<?php

use yii\db\Migration;

/**
 * Class m190926_170209_binary_tree
 */
class m190926_170209_binary_tree extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('binary_tree', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer()->notNull(),
            'position' => $this->integer()->notNull(),
            'path' => $this->text()->notNull(),
            'level' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-binary-parent_id-position',
            'binary_tree',
            ['parent_id', 'position'],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('binary_tree');
    }

}
