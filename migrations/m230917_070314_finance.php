<?php

use app\components\Migration;

class m230917_070314_finance extends Migration
{
    protected $table = 'statistic';

    public function safeUp()
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'budget_category' => $this->string(20)->notNull()->comment('Категория бюджета'),
            'category' => $this->string(20)->notNull()->comment('Категория'),
            'date' => $this->string(10)->notNull()->comment('Дата операции'),
            'time' => $this->string(5)->comment('Время операции'),
            'username' => $this->char(30)->comment('Имя пользователя'),
            'money' => $this->float(14)->notNull()->comment('Средства'),
        ]);

        $this->createDateColumns();

        $this->createIndex("ix_{$this->tableName}_category", $this->table, ['category']);
        $this->createIndex("ix_{$this->tableName}_budget_category", $this->table, ['budget_category']);
        $this->createIndex("ix_{$this->tableName}_date", $this->table, ['date']);
        $this->createIndex("ix_{$this->tableName}_username", $this->table, ['username']);
    }

    public function safeDown()
    {
        $this->dropTable($this->table);
    }
}
