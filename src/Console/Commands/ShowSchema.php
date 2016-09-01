<?php

namespace Thedevsaddam\LaravelSchema\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Thedevsaddam\LaravelSchema\Schema;


class ShowSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schema:show {tableName?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display connected database schema information in tabular form';

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
        $this->showSchemaInTable();
    }

    /**
     * Display schema information in tabular form
     * @return bool
     */
    public function showSchemaInTable()
    {
        $s = new Schema();
        $tables = $s->getTables();
        $headers = ['Field', 'Type', 'Null', 'Key', 'Default', 'Extra'];

        if (!count($tables)) {
            $this->warn('Database does not contain any table');
        }

        $tableName = $this->argument('tableName');
        if (!empty($tableName)) {
            if (!in_array($tableName, $tables))
                return $this->warn('Table name is not correct!');

            $body = $s->getTableColumns($tableName);
            $rowsCount = $s->getTableRowCount($tableName);
            $this->info($tableName . ' (rows: ' . $rowsCount . ')');
            $this->table($headers, $body);
            return;
        }

        foreach ($s->getSchema() as $key => $value) {
            $this->info($key . ' (rows: ' . $value['rowsCount'] . ')');
            $this->table($headers, $value['attributes']);
            $this->line('');
        }
    }

}