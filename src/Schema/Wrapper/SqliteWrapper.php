<?php
/**
 * Created by PhpStorm.
 * User: thedevsaddam
 * Date: 9/5/16
 * Time: 12:16 PM
 */

namespace Thedevsaddam\LaravelSchema\Schema\Wrapper;


use Thedevsaddam\LaravelSchema\Schema\BaseSchema;

class SqliteWrapper implements WrapperContract
{
    protected $baseSchema;

    public function __construct(BaseSchema $baseSchema)
    {
        $this->baseSchema = $baseSchema;
    }

    public function getTables()
    {
        $tables = $this->baseSchema->database->select("SELECT name FROM sqlite_master WHERE type='table'");
        return array_map(function ($table) {
            return $table->name;
        }, $tables);
    }

    public function getColumns($tableName)
    {
        $columns = $this->baseSchema->database->select(\DB::raw("pragma table_info($tableName)"));
        return $this->transformColumns($columns);
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
                'CID' => $column->cid,
                'Field' => $column->name,
                'Type' => $column->type,
                'Null' => $column->notnull,
                'Key' => $column->pk,
                'Default' => $column->dflt_value,
            ];
        }, $columns);
    }
}