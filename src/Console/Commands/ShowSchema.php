<?php

namespace Thedevsaddam\LaravelSchema\Console\Commands;

use Illuminate\Console\Command;
use Thedevsaddam\LaravelSchema\Schema\Helper;
use Thedevsaddam\LaravelSchema\Schema\Schema;
use Symfony\Component\Console\Input\InputOption;


class ShowSchema extends Command
{
    use Helper;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'schema:show';

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
        //change connection if provide
        if ($this->option('c')) {
            $this->schema->setConnection($this->option('c'));
            $this->schema->switchWrapper();
        }

        $tables = $this->schema->databaseWrapper->getTables();
        $headers = $this->schema->headers;

        if (!count($tables)) {
            $this->warn('Database does not contain any table');
        }

        $tableName = $this->option('t');
        if ($tableName) {
            if ($this->isNamespaceModel($tableName)) {
                $tableName = $this->tableNameFromModel($tableName);
            }
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

    protected function getOptions()
    {
        return [
            ['t', 't', InputOption::VALUE_OPTIONAL, 'Table name'],
            ['c', 'c', InputOption::VALUE_OPTIONAL, 'Connection name'],
        ];
    }

}