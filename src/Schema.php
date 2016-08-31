<?php
/**
 * Created by PhpStorm.
 * User: thedevsaddam
 * Date: 8/31/16
 * Time: 12:10 PM
 */

namespace Thedevsaddam\LaravelSchema;

use DB;

class Schema
{

    private $schema = [];
    private $dbName;
    private $tables = [];

    public function __construct()
    {
        $this->dbName = DB::connection()->getDatabaseName();
        $this->tables = DB::select('SHOW TABLES');
    }

    /**
     * @return mixed
     */
    public function getDbName()
    {
        return $this->dbName;
    }

    /**
     * @param mixed $dbName
     * @return $this
     */
    public function setDbName($dbName)
    {
        $this->dbName = $dbName;
        return $this;
    }


    /**
     * Fetch tables information
     * @return array
     */
    public function getTables()
    {
        $attribute = 'Tables_in_' . $this->dbName;
        return array_map(function ($table) use ($attribute) {
            return $table->$attribute;
        }, $this->tables);

    }

    /**
     * Generate table information
     * @return $this
     */
    private function generateTableInfo()
    {
        foreach ($this->getTables() as $table) {
            $columns = DB::select("SHOW COLUMNS FROM " . $table);
            $this->schema[$table]['attributes'] = $this->transformColumns($columns);
            $this->schema[$table]['rowsCount'] = DB::table($table)->count();
        }
        return $this;
    }

    /**
     * Transform columns
     * @param $columns
     * @return array
     */
    private function transformColumns($columns)
    {
        return array_map(function ($coumn) {
            return [
                'Field' => $coumn->Field,
                'Type' => $coumn->Type,
                'Null' => $coumn->Null,
                'Key' => $coumn->Key,
                'Default' => $coumn->Default,
                'Extra' => $coumn->Extra
            ];
        }, $columns);
    }

    /**
     * Fetch schema information
     * @return array
     */
    public function getSchema()
    {
        $this->generateTableInfo();
        return $this->schema;
    }

    /**
     * Perform raw sql query
     * @param $query
     * @return mixed
     */
    public function rawQuery($query)
    {
        return DB::select(DB::raw($query));
    }

}