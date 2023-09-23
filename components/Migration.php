<?php

namespace app\components;

use yii\base\NotSupportedException;
use yii\db\ColumnSchemaBuilder;
use yii\db\Exception;

/**
 * Class Migration
 * @package core\components
 *
 * @property-read string $tableName - table without prefix
 */
class Migration extends \yii\db\Migration
{
    const TYPE_INNODB = 'InnoDB';

    const TYPE_MYISAM = 'MyISAM';

    const ACTION_CASCADE = 'CASCADE';

    const ACTION_RESTRICT = 'RESTRICT';

    protected string $action = 'RESTRICT';

    protected $dbType;

    protected $table;

    protected function getTableName(string $table = null): string
    {
        if ($table === null) $table = $this->table;

        return str_replace(['{', '}', '%'], '', $table);
    }

    protected function createDateColumns(string $table = null)
    {
        if ($table === null && $this->table) {

            $table = $this->table;
        } else {

            new Exception(sprintf('parameter "table" not defined in class "%s" method "%s"', get_called_class(), 'createDateColumns'));
        }

        $this->addColumn($table, 'created_at', $this->dateTime()->defaultExpression('NOW()'));
        $this->addColumn($table, 'updated_at', $this->timestamp()
            ->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP')
        );
    }

    protected function createUserColumns($table = null)
    {
        if ($table === null && $this->table) {

            $table = $this->table;
        } else {

            new Exception(sprintf('parameter "table" not defined in class "%s" method "%s"', get_called_class(), 'createUserColumns'));
        }

        $this->addColumn($table, 'created_by', $this->integer()->null());
        $this->addColumn($table, 'updated_by', $this->integer()->null());
    }

    protected function createLoginColumns($table = null)
    {
        if ($table === null && $this->table) {

            $table = $this->table;
        } else {

            new Exception(sprintf('parameter "table" not defined in class "%s" method "%s"', get_called_class(), 'createUserColumns'));
        }

        $this->addColumn($table, 'created_by', $this->string()->null());
        $this->addColumn($table, 'updated_by', $this->string()->null());
    }

    /**
     * @throws NotSupportedException
     */
    public function longText(): ColumnSchemaBuilder
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder('longtext');
    }
}