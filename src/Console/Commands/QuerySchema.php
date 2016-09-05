<?php

namespace Thedevsaddam\LaravelSchema\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Debug\Dumper;
use Thedevsaddam\LaravelSchema\Schema\Schema;


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

         $result = $this->schema->rawQuery($rawQuery);
        if (!!$result) {
            (new Dumper)->dump($result);
        }
        $this->info('Query executed successfully!');
    }

}