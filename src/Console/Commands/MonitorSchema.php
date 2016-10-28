<?php

namespace Thedevsaddam\LaravelSchema\Console\Commands;

use Illuminate\Console\Command;
use Thedevsaddam\LaravelSchema\Schema\Schema;
use Symfony\Component\Console\Input\InputOption;


class MonitorSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'schema:monitor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display database performance status (read/write/connection etc)';

    /**
     * Schema package version
     * @var
     */
    protected $version;

    /**
     * Help table headers
     * @var array
     */
    protected $headers = ["Query", "Value", "Create", "Value", "Alter", "Value", "Drop", "Value"];

    /**
     * Help table body
     * @var array
     */
    protected $body = [];

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
        //change connection if provide
        if ($this->option('c')) {
            $this->schema->setConnection($this->option('c'));
            $this->schema->switchWrapper();
            if ($this->schema->connection !== 'mysql') {
                $this->warn('Monitoring in currently supporting for mysql and updating.');
                return false;
            }
        }
        $timeInterval = 2;
        if (null !== $this->option('i') & is_integer($this->option('i'))) {
            $timeInterval = $this->option('i');
        }
        for ($i = 1; ; $i++) {
            $this->info("Fetching ($i):");
            $this->table($this->headers, $this->schema->databaseWrapper->showDatabaseStatus($timeInterval));
            $this->line('');
        }
    }

    protected function getOptions()
    {
        return [
            ['i', 'i', InputOption::VALUE_OPTIONAL, 'Interval time'],
            ['c', 'c', InputOption::VALUE_OPTIONAL, 'Connection name']
        ];
    }

}