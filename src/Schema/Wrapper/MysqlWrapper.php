<?php
/**
 * Created by PhpStorm.
 * User: thedevsaddam
 * Date: 9/4/16
 * Time: 9:48 PM
 */

namespace Thedevsaddam\LaravelSchema\Schema\Wrapper;


use Thedevsaddam\LaravelSchema\Schema\BaseSchema;

class MysqlWrapper implements WrapperContract
{

    protected $baseSchema;

    public function __construct(BaseSchema $baseSchema)
    {
        $this->baseSchema = $baseSchema;
    }

    public function getTables()
    {
        $tables = $this->baseSchema->database->select('SHOW TABLES');
        $attribute = 'Tables_in_' . $this->baseSchema->getDatabaseName();
        return array_map(function ($table) use ($attribute) {
            return $table->$attribute;
        }, $tables);
    }

    public function getColumns($tableName)
    {
        return $this->transformColumns($this->baseSchema->database->select("SHOW COLUMNS FROM " . $tableName));
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