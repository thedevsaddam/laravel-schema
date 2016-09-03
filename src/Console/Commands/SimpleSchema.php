<?php

namespace Thedevsaddam\LaravelSchema\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Thedevsaddam\LaravelSchema\Schema\Schema;


class SimpleSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schema:simple';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display connected database table list';

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
        $this->showSchemaInSimpleTable();
    }

    /**
     * Display schema information in list
     * @return bool
     */
    public function showSchemaInSimpleTable()
    {
        $s = new Schema();
        if (!count($s->getTables())) {
            $this->warn('Database does not contain any table');
        }
        $headers = ['Table Name', 'Rows'];
        $body = [];
        foreach ($s->getSchema() as $key => $value) {
            $body[] = [ $key,   $value['rowsCount']];
        }
        $this->table($headers, $body);
    }

}