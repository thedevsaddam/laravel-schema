<?php
/**
 * Created by PhpStorm.
 * User: thedevsaddam
 * Date: 9/5/16
 * Time: 12:16 PM
 */

namespace Thedevsaddam\LaravelSchema\Schema\Wrapper;


use Thedevsaddam\LaravelSchema\Schema\BaseSchema;

class SqliteWrapper extends BaseSchema implements WrapperContract
{

    public function getTables()
    {
        $tables = $this->database->select("SELECT name FROM sqlite_master WHERE type='table'");
        return array_map(function ($table) {
            return $table->name;
        }, $tables);
    }

    public function getColumns($tableName)
    {
        $columns = $this->database->select(\DB::raw("pragma table_info($tableName)"));
        return $this->transformColumns($columns);
    }

    public function getSchema()
    {
        // TODO: Implement getSchema() method.
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
                'CID' => $column->cid,
                'Field' => $column->name,
                'Type' => $column->type,
                'Null' => $column->notnull,
                'Key' => $column->Key,
                'Default' => $column->dflt_value,
                'Primary Key' => $column->pk
            ];
        }, $columns);
    }
}