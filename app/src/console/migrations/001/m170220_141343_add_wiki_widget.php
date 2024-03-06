<?php

use yii\db\Migration;

class m170220_141343_add_wiki_widget extends Migration
{
    public function up()
    {
        // Таблица для хранения связи между установленными на страницах виджетами
        // и статей из wiki
        // CYB-3561

        $this->createTable('wiki_widgets', [
            'id' => $this->primaryKey(),
            'pageId' => $this->integer(),
            'widgetId' => $this->integer()->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('wiki_widgets');
        return true;
    }
}
