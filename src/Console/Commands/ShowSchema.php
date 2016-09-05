<?php

namespace Thedevsaddam\LaravelSchema\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Thedevsaddam\LaravelSchema\Schema\Schema;


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
        $this->showSchemaInTable();
    }

    /**
     * Display schema information in tabular form
     * @return bool
     */
    public function showSchemaInTable()
    {
        $tables = $this->schema->databaseWrapper->getTables();
        $headers = ['Field', 'Type', 'Null', 'Key', 'Default', 'Extra'];

        if (!count($tables)) {
            $this->warn('Database does not contain any table');
        }

        $tableName = $this->argument('tableName');
        if (!empty($tableName)) {
            if (!in_array($tableName, $tables)) {
                $this->warn('Table name is not correct!');
                return false;
            }

            $body = $this->schema->databaseWrapper->getColumns($tableName);
            $rowsCount = $this->schema->getTableRowCount($tableName);
            $this->info($tableName . ' (rows: ' . $rowsCount . ')');
            $this->table($headers, $body);
            return true;
        }

        foreach ($this->schema->databaseWrapper->getSchema() as $key => $value) {
            $this->info($key . ' (rows: ' . $value['rowsCount'] . ')');
            $this->table($headers, $value['attributes']);
            $this->line('');
        }
    }

}