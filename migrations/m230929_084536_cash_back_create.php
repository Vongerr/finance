<?php

use app\components\Migration;

class m230929_084536_cash_back_create extends Migration
{
    protected $table = 'cash_back';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'month' => $this->tinyInteger(2)->notNull()->comment('Месяц'),
            'year' => $this->integer(4)->notNull()->comment('Год'),
            'category' => $this->string(20)->notNull()->comment('Категория'),
            'individual_category' => $this->string(25)->notNull()->comment('Индивидуальная категория'),
            'percent' => $this->tinyInteger(2)->notNull()->comment('Процент')
        ]);

        $this->createDateColumns();

        $this->createIndex("ix_{$this->tableName}_month", $this->table, ['month']);
        $this->createIndex("ix_{$this->tableName}_year", $this->table, ['year']);
        $this->createIndex("ix_{$this->tableName}_category", $this->table, ['category']);
    }

    public function safeDown()
    {
        $this->dropTable($this->table);
    }
}
