<?php

namespace Thedevsaddam\LaravelSchema\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Thedevsaddam\LaravelSchema\Schema;


class ListSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schema:list';

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
        if (!count($s->getTables())) {
            $this->warn('Database does not contain any table');
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