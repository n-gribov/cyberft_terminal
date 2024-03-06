<?php

use yii\db\Migration;

class m160421_084949_module_pages_true extends Migration
{
    public function up()
    {
        $this->execute('drop table if exists `docs`');
        $this->execute('drop table if exists `docs_attachment`');

        $this->createTable('page', [
            'id'      => $this->primaryKey(),
            'pid'     => $this->integer(),
            'title'   => $this->string(),
            'slug'    => $this->string(),
            'preview'    => $this->text(),
            'body'    => $this->text(),
            'version' => $this->string(),
            'created' => $this->dateTime(),
            'updated' => $this->dateTime(),
        ]);

        $this->createIndex('title', 'page', 'title');
        $this->createIndex('slug', 'page', 'slug');
        $this->createIndex('parent', 'page', 'pid');

        $this->createTable('page_attachment', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer(),
            'type' => $this->string(7),
            'title' => $this->string(),
            'description' => $this->text(),
            'path' => $this->string(),
            'created' => $this->dateTime(),
        ]);

        $this->createIndex('page', 'page_attachment', 'page_id');

    }

    public function down()
    {
        $this->dropTable('page');
        $this->dropTable('page_attachment');

        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
