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
    protected $headers = ["Name", "Value"];

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

        $startCreateDb = $this->getValueIfExist($startQueries, 'Com_create_db');
        $startCreateEvent = $this->getValueIfExist($startQueries, 'Com_create_event');
        $startCreateFunc = $this->getValueIfExist($startQueries, 'Com_create_function');
        $startCreateProcedure = $this->getValueIfExist($startQueries, 'Com_create_procedure');
        $startCreateServer = $this->getValueIfExist($startQueries, 'Com_create_server');
        $startCreateTable = $this->getValueIfExist($startQueries, 'Com_create_table');
        $startCreateTrigger = $this->getValueIfExist($startQueries, 'Com_create_trigger');
        $startCreateUDF = $this->getValueIfExist($startQueries, 'Com_create_udf');
        $startCreateUser = $this->getValueIfExist($startQueries, 'Com_create_user');
        $startCreateView = $this->getValueIfExist($startQueries, 'Com_create_view');

        sleep(2);

        // end queries
        $endQueries = $this->schema->rawQuery('show global status');
        $endSelect = $this->getValueIfExist($endQueries, 'Com_select');
        $endUpdate = $this->getValueIfExist($endQueries, 'Com_update');
        $endInsert = $this->getValueIfExist($endQueries, 'Com_insert');
        $endDelete = $this->getValueIfExist($endQueries, 'Com_delete');
        $endByteSent = $this->getValueIfExist($endQueries, 'Bytes_sent');
        $endByteReceived = $this->getValueIfExist($endQueries, 'Bytes_received');
        $endConnections = $this->getValueIfExist($endQueries, 'Connections');

        $endCreateDb = $this->getValueIfExist($endQueries, 'Com_create_db');
        $endCreateEvent = $this->getValueIfExist($endQueries, 'Com_create_event');
        $endCreateFunc = $this->getValueIfExist($endQueries, 'Com_create_function');
        $endCreateProcedure = $this->getValueIfExist($endQueries, 'Com_create_procedure');
        $endCreateServer = $this->getValueIfExist($endQueries, 'Com_create_server');
        $endCreateTable = $this->getValueIfExist($endQueries, 'Com_create_table');
        $endCreateTrigger = $this->getValueIfExist($endQueries, 'Com_create_trigger');
        $endCreateUDF = $this->getValueIfExist($endQueries, 'Com_create_udf');
        $endCreateUser = $this->getValueIfExist($endQueries, 'Com_create_user');
        $endCreateView = $this->getValueIfExist($endQueries, 'Com_create_view');

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
                'value' => round(($endConnections - $startConnections) / $timeSpan) . ' PS '
            ],
            [
                'name' => 'Total Connections',
                'value' => round($endConnections)
            ],
            [
                'name' => '',
                'value' => ''
            ],
            [
                'name' => 'Create DB',
                'value' => round(($endCreateDb - $startCreateDb) / $timeSpan) . ' PS'
            ],
            [
                'name' => 'Create Event',
                'value' => round(($endCreateEvent - $startCreateEvent) / $timeSpan) . ' PS'
            ],
            [
                'name' => 'Create Function',
                'value' => round(($endCreateFunc - $startCreateFunc) / $timeSpan) . ' PS'
            ],
            [
                'name' => 'Create Procedure',
                'value' => round(($endCreateProcedure - $startCreateProcedure) / $timeSpan) . ' PS'
            ],
            [
                'name' => 'Create Server',
                'value' => round(($endCreateServer - $startCreateServer) / $timeSpan) . ' PS'
            ],
            [
                'name' => 'Create Table',
                'value' => round(($endCreateTable - $startCreateTable) / $timeSpan) . ' PS'
            ],
            [
                'name' => 'Create Trigger',
                'value' => round(($endCreateTrigger - $startCreateTrigger) / $timeSpan) . ' PS'
            ],
            [
                'name' => 'Create UDF',
                'value' => round(($endCreateUDF - $startCreateUDF) / $timeSpan) . ' PS'
            ],
            [
                'name' => 'Create User',
                'value' => round(($endCreateUser - $startCreateUser) / $timeSpan) . ' PS'
            ],
            [
                'name' => 'Create View',
                'value' => round(($endCreateView - $startCreateView) / $timeSpan) . ' PS'
            ],
        ];
        $this->info('Iteration: ' . $iteration);
        $this->table($this->headers, $body);
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