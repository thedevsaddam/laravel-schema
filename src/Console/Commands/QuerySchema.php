<?php

namespace Thedevsaddam\LaravelSchema\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Thedevsaddam\LaravelSchema\Schema;


class QuerySchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schema:query {rawQuery?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Raw sql query to database';

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
        $this->performQuery();
    }

    /**
     * Perform raw sql query
     * @return bool
     */
    public function performQuery()
    {
        $rawQuery = $this->argument('rawQuery');
        if (empty($rawQuery)) {
            $this->warn('Please provide raw sql query as string (in single/double quote)!');
            return false;
        }

        $s = new Schema();
        $result = $s->rawQuery($rawQuery);
        if (!!$result) {
            dd($result);
        }
        $this->info('Query executed successfully!');
    }

}