<?php

namespace Thedevsaddam\LaravelSchema\Console\Commands;

use Illuminate\Console\Command;
use Thedevsaddam\LaravelSchema\Schema\Schema;
use Symfony\Component\Console\Input\InputOption;


class SimpleSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'schema:simple';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display connected database table list';

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
        $this->showSchemaInSimpleTable();
    }

    /**
     * Display schema information in list
     * @return bool
     */
    public function showSchemaInSimpleTable()
    {
        //change connection if provide
        if ($this->option('c')) {
            $this->schema->setConnection($this->option('c'));
            $this->schema->switchWrapper();
        }

        if (!count($this->schema->databaseWrapper->getTables())) {
            $this->warn('Database does not contain any table');
            return false;
        }
        $headers = ['Table Name', 'Rows'];
        $body = [];
        foreach ($this->schema->databaseWrapper->getSchema() as $key => $value) {
            $body[] = [ $key,   $value['rowsCount']];
        }
        $this->table($headers, $body);
    }

    protected function getOptions()
    {
        return [
            ['c', 'c', InputOption::VALUE_OPTIONAL, 'Connection name'],
        ];
    }

}