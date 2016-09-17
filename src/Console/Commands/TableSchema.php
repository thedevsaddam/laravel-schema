<?php

namespace Thedevsaddam\LaravelSchema\Console\Commands;

use Illuminate\Console\Command;
use Thedevsaddam\LaravelSchema\Schema\Helper;
use Thedevsaddam\LaravelSchema\Schema\Schema;


class TableSchema extends Command
{
    use Helper;
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

    private $schema;

    public function __construct(Schema $schema)
    {
        parent::__construct();
        $this->schema = $schema;
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
        $tables = $this->schema->databaseWrapper->getTables();

        if (!count($tables)) {
            $this->warn('Database does not contain any table');
            return false;
        }

        $tableName = $this->argument('tableName');
        if (empty($tableName)) {
            $this->warn('Table name is required!');
            return false;
        }

        if ($this->isNamespaceModel($tableName)) {
            $tableName = $this->tableNameFromModel($tableName);
        }

        if (!in_array($tableName, $tables)) {
            $this->warn('Table name is not correct!');
            return false;
        }

        $page = (!empty($this->argument('page'))) ? $this->argument('page') : 1;
        $limit = (!empty($this->argument('limit'))) ? $this->argument('limit') : 15;
        $orderBy = (!empty($this->argument('orderBy'))) ? $this->argument('orderBy') : null;
        $columns = $this->schema->databaseWrapper->getColumns($tableName);
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

        $rows = $this->schema->getPaginatedData($tableName, $page, $limit, $attributeName, $order)['data'];
        $body = $this->makeTableBody($headers, $rows);
        $rowsCount = $this->schema->getTableRowCount($tableName);
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
            $row = [];
            for ($j = 0; $j < count($headers); $j++) {
                $column = $headers[$j];
                $row[$j] = str_limit($rows[$i]->$column, 10, '');
            }
            $body[$i] = $row;
        }
        return $body;
    }

}
