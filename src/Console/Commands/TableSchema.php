<?php

namespace Thedevsaddam\LaravelSchema\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Thedevsaddam\LaravelSchema\Schema;


class TableSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schema:table {tableName?} {page?} {orderBy?} {limit?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display connected database schema table definition';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->showSchemaDefinitionInTable();
    }

    /**
     * Display schema definition in table
     * @return bool
     */
    public function showSchemaDefinitionInTable()
    {
        //TODO: need to refactor the code
        $s = new Schema();
        $tables = $s->getTables();

        if (!count($tables)) {
            $this->warn('Database does not contain any table');
        }

        $tableName = $this->argument('tableName');
        if (empty($tableName)) {
            $this->warn('Table name is required!');
            return false;
        }

        if (!in_array($tableName, $tables)) {
            $this->warn('Table name is not correct!');
            return false;
        }

        $page = (!empty($this->argument('page'))) ? $this->argument('page') : 1;
        $limit = (!empty($this->argument('limit'))) ? $this->argument('limit') : 15;
        $orderBy = (!empty($this->argument('orderBy'))) ? $this->argument('orderBy') : null;
        $columns = $s->getTableColumns($tableName);
        $headers = array_map(function ($column) {
            return $column['Field'];
        }, $columns);

        $attributeName = null;
        $order = null;
        if (null !== $orderBy) {
            if (strpos($orderBy, ':') !== false) {
                $orderBy = explode(':', $orderBy);
            }
            $attributeName = $orderBy[0];
            if (isset($orderBy[1])) {
                $order = ('desc' == strtolower($orderBy[1])) ? 'DESC' : 'ASC';
            }
            if (!in_array($attributeName, $headers)) {
                $this->warn("There is no column named '$attributeName'. Please provide a correct column name for ordering!");
                return false;
            }
        }

        $rows = $s->getPaginatedData($tableName, $page, $limit, $attributeName, $order)['data'];
        $body = $this->makeTableBody($headers, $rows);
        $rowsCount = $s->getTableRowCount($tableName);
        $this->info($tableName . ' (rows ' . $rowsCount . ')');
        $this->table($headers, $body);
        return false;
    }

    /**
     * Make formatted body for table
     * @param $headers
     * @param $rows
     * @return array
     */
    private function makeTableBody($headers, $rows)
    {
        $body = [];
        for ($i = 0; $i < count($rows); $i++) {
            $bindedRow = [];
            for ($j = 0; $j < count($headers); $j++) {
                $column = $headers[$j];
                $bindedRow[$j] = str_limit($rows[$i]->$column, 10, '');
            }
            $body[$i] = $bindedRow;
        }
        return $body;
    }

}
