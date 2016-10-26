<?php

namespace Thedevsaddam\LaravelSchema\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Thedevsaddam\LaravelSchema\Schema\Schema;


class MonitorSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schema:monitor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display database performance status (read/write etc)';

    /**
     * Schema package version
     * @var
     */
    protected $version;

    /**
     * Help table headers
     * @var array
     */
    protected $headers = ["Method Name", "Usage"];

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
        for ($i = 1; ; $i++) {
            $this->showDatabaseStatus($i);
        }
    }

    public function showDatabaseStatus($iteration = null)
    {
        $uptimeStart = time();
        // start queries
        $startQueries = $this->schema->rawQuery('show global status');
        $startSelect = $this->getValueIfExist($startQueries, 'Com_select');
        $startUpdate = $this->getValueIfExist($startQueries, 'Com_update');
        $startDelete = $this->getValueIfExist($startQueries, 'Com_delete');
        $startInsert = $this->getValueIfExist($startQueries, 'Com_insert');
        $startByteSent = $this->getValueIfExist($startQueries, 'Bytes_sent');
        $startByteReceived = $this->getValueIfExist($startQueries, 'Bytes_received');
        $startConnections = $this->getValueIfExist($startQueries, 'Connections');

        sleep(1);

        // end queries
        $endQueries = $this->schema->rawQuery('show global status');
        $endSelect = $this->getValueIfExist($endQueries, 'Com_select');
        $endUpdate = $this->getValueIfExist($startQueries, 'Com_update');
        $endInsert = $this->getValueIfExist($startQueries, 'Com_insert');
        $endDelete = $this->getValueIfExist($startQueries, 'Com_delete');
        $endByteSent = $this->getValueIfExist($startQueries, 'Bytes_sent');
        $endByteReceived = $this->getValueIfExist($startQueries, 'Bytes_received');
        $endConnections = $this->getValueIfExist($startQueries, 'Connections');
        $timeSpan = (time() - $uptimeStart);

        $headers = ['Name', 'Value'];
        $body = [
            [
                'name' => 'Select',
                'value' => round(($endSelect - $startSelect) / $timeSpan) . ' QPS'
            ],
            [
                'name' => 'Update',
                'value' => round(($endUpdate - $startUpdate) / $timeSpan) . ' QPS'
            ],
            [
                'name' => 'Insert',
                'value' => round(($endInsert - $startInsert) / $timeSpan) . ' QPS'
            ],
            [
                'name' => 'Delete',
                'value' => round(($endDelete - $startDelete) / $timeSpan) . ' QPS'
            ],
            [
                'name' => 'Byte sent',
                'value' => round(($endByteSent - $startByteSent) / $timeSpan)
            ],
            [
                'name' => 'Byte received',
                'value' => round(($endByteReceived - $startByteReceived) / $timeSpan)
            ],
            [
                'name' => 'Connections',
                'value' => round(($endConnections - $startConnections) / $timeSpan) . ' PS'
            ],
        ];
        $this->info('Iteration: ' . $iteration);
        $this->table($headers, $body);
        $this->line('');
    }

    private function getValueIfExist($queries, $keyString)
    {
        foreach ($queries as $query) {
            if ($query->Variable_name == $keyString) {
                return $query->Value;
            }
        }
    }

}