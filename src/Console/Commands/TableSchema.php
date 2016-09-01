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
    protected $signature = 'schema:table {tableName} {page?} {limit?}';

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
        $s = new Schema();
        $tables = $s->getTables();

        if (!count($tables)) {
            $this->warn('Database does not contain any table');
        }

        $tableName = $this->argument('tableName');
        if (!empty($tableName)) {
            if (!in_array($tableName, $tables))
                return $this->warn('Table name is not correct!');

            $page = (!empty($this->argument('page'))) ? $this->argument('page') : 1;
            $limit = (!empty($this->argument('limit'))) ? $this->argument('limit') : 10;

            $columns = $s->getTableColumns($tableName);
            $headers = array_map(function ($column) {
                return $column['Field'];
            }, $columns);

            $rows = $s->getPaginatedData($tableName, $page, $limit)['data'];
            $body = [];
            for ($i = 0; $i < count($rows); $i++) {
                $bindedRow = [];
                for ($j = 0; $j < count($headers); $j++) {
                    $column = $headers[$j];
                    $bindedRow[$j] = str_limit($rows[$i]->$column, 10);
                }
                $body[$i] = $bindedRow;
            }
            $rowsCount = $s->getTableRowCount($tableName);
            $this->info($tableName . ' (rows ' . $rowsCount . ')');
            $this->table($headers, $body);
            return;
        }
    }

}