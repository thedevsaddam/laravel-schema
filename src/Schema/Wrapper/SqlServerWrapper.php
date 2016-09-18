<?php
/**
 * Created by PhpStorm.
 * User: thedevsaddam
 * Date: 9/17/16
 * Time: 9:48 PM
 */

namespace Thedevsaddam\LaravelSchema\Schema\Wrapper;


use Thedevsaddam\LaravelSchema\Schema\BaseSchema;

class SqlServerWrapper implements WrapperContract
{

    protected $baseSchema;

    public function __construct(BaseSchema $baseSchema)
    {
        $this->baseSchema = $baseSchema;
    }

    public function getTables()
    {
        $databaseName = $this->baseSchema->getDatabaseName();
        $tables = $this->baseSchema->database->select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_CATALOG='$databaseName'");
        return array_map(function ($table) {
            return $table->TABLE_NAME;
        }, $tables);
    }

    public function getColumns($tableName)
    {
        return $this->transformColumns($this->baseSchema->database->select("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$tableName'ORDER BY ORDINAL_POSITION"));
    }

    public function getSchema()
    {
        foreach ($this->getTables() as $table) {
            $columns = $this->getColumns($table);
            $this->schema[$table]['attributes'] = $columns;
            $this->schema[$table]['rowsCount'] = $this->baseSchema->getTableRowCount($table);
        }
        return $this->schema;
    }

    /**
     * Transform columns
     * @param $columns
     * @return array
     */
    private function transformColumns($columns)
    {
        return array_map(function ($column) {
            return [
                'Field' => $column->COLUMN_NAME,
                'Type' => $column->DATA_TYPE,
                'Null' => $column->IS_NULLABLE,
                'Key' => $column->ORDINAL_POSITION,
                'Default' => $column->COLUMN_DEFAULT,
                'Char max len' => $column->CHARACTER_MAXIMUM_LENGTH
            ];
        }, $columns);
    }
}