<?php
/**
 * Created by PhpStorm.
 * User: thedevsaddam
 * Date: 8/31/16
 * Time: 12:10 PM
 */

namespace Thedevsaddam\LaravelSchema\Schema;

use DB;
use Illuminate\Pagination\Paginator;

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
     * Get table columns
     * @param $table
     * @return array
     */
    public function getTableColumns($table)
    {
        return $this->transformColumns(DB::select("SHOW COLUMNS FROM " . $table));

    }

    /**
     * Get table total row count
     * @param $table
     * @return mixed
     */
    public function getTableRowCount($table)
    {
        return DB::table($table)->count();
    }

    /**
     * Generate table information
     * @return $this
     */
    private function generateTableInfo()
    {
        foreach ($this->getTables() as $table) {
            $columns = $this->getTableColumns($table);
            $this->schema[$table]['attributes'] = $columns;
            $this->schema[$table]['rowsCount'] = $this->getTableRowCount($table);
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


    /**
     * Fetch data form table using pagination
     * @param $tableName
     * @param int $page
     * @param int $limit
     * @param null $orderAttribute
     * @param string $order
     * @return mixed
     */
    public function getPaginatedData($tableName, $page = 1, $limit = 10, $orderAttribute = null, $order = 'ASC')
    {
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        $data = DB::table($tableName);
        if (null === $orderAttribute) {
            return $data->paginate($limit)->toArray();
        }
        return $data->orderBy($orderAttribute, $order)->paginate($limit)->toArray();
    }

}