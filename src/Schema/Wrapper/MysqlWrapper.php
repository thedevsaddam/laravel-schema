<?php
/**
 * Created by PhpStorm.
 * User: thedevsaddam
 * Date: 9/4/16
 * Time: 9:48 PM
 */

namespace Thedevsaddam\LaravelSchema\Schema\Wrapper;


use Thedevsaddam\LaravelSchema\Schema\BaseSchema;

class MysqlWrapper extends BaseSchema implements WrapperContract
{

    public function getTables()
    {
        $tables = $this->database->select('SHOW TABLES');
        $attribute = 'Tables_in_' . $this->getDatabaseName();
        return array_map(function ($table) use ($attribute) {
            return $table->$attribute;
        }, $tables);
    }

    public function getColumns($tableName)
    {
        return $this->transformColumns($this->database->select("SHOW COLUMNS FROM " . $tableName));
    }

    public function getSchema()
    {
        foreach ($this->getTables() as $table) {
            $columns = $this->getColumns($table);
            $this->schema[$table]['attributes'] = $columns;
            $this->schema[$table]['rowsCount'] = $this->getTableRowCount($table);
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
                'Field' => $column->Field,
                'Type' => $column->Type,
                'Null' => $column->Null,
                'Key' => $column->Key,
                'Default' => $column->Default,
                'Extra' => $column->Extra
            ];
        }, $columns);
    }
}