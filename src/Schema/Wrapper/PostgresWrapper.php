<?php
/**
 * Created by PhpStorm.
 * User: thedevsaddam
 * Date: 9/6/16
 * Time: 9:41 PM
 */

namespace Thedevsaddam\LaravelSchema\Schema\Wrapper;


use Thedevsaddam\LaravelSchema\Schema\BaseSchema;

class PostgresWrapper implements WrapperContract
{

    protected $baseSchema;

    public function __construct(BaseSchema $baseSchema)
    {
        $this->baseSchema = $baseSchema;
    }

    public function getTables()
    {
        $tables = $this->baseSchema->database->select("SELECT table_name FROM information_schema.tables WHERE table_schema='public'");
        return array_map(function ($table) {
            return $table->table_name;
        }, $tables);
    }

    public function getColumns($tableName)
    {
        return $this->transformColumns($this->baseSchema->database->select("SELECT ordinal_position, column_name, data_type, is_nullable, column_default FROM information_schema.columns WHERE table_name ='$tableName'"));
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
                'Field' => $column->column_name,
                'Type' => $column->data_type,
                'Null' => $column->is_nullable,
                'Key' => $column->ordinal_position,
                'Default' => $column->column_default,
//                'Extra' => $column->Extra
            ];
        }, $columns);
    }
}