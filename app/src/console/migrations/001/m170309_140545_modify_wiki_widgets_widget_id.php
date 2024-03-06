<?php

use yii\db\Migration;

class m170309_140545_modify_wiki_widgets_widget_id extends Migration
{
    public function up()
    {
        // id виджета вики удобнее
        // хранить в виде строке, а не числа
        // CYB-3601
        $this->alterColumn('wiki_widgets', 'widgetId', $this->string() . ' NOT NULL');
    }

    public function down()
    {
        $this->alterColumn('wiki_widgets', 'widgetId', $this->integer() . ' NOT NULL');
        return true;
    }
}
