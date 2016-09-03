<?php

namespace Thedevsaddam\LaravelSchema\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Thedevsaddam\LaravelSchema\Schema\Schema;


class ListSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schema:list {tableName?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display connected database schema information in list';

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
        $this->showSchemaInList();
    }

    /**
     * Display schema information in list
     * @return bool
     */
    public function showSchemaInList()
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

            $attributes = $s->getTableColumns($tableName);
            $rowsCount = $s->getTableRowCount($tableName);
            $this->info($tableName . ' (rows: ' . $rowsCount . ')');
            foreach ($attributes as $attribute) {
                $this->line('  ' . $attribute['Field'] . '  ' . $attribute['Type']);
            }
            return;
        }

        foreach ($s->getSchema() as $key => $value) {
            $this->info($key . ' (rows: ' . $value['rowsCount'] . ')');
            foreach ($value['attributes'] as $attribute) {
                $this->line('  ' . $attribute['Field'] . '  ' . $attribute['Type']);
            }
            $this->line('');
        }
    }

}