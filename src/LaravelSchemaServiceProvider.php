<?php

namespace Thedevsaddam\LaravelSchema;

use Illuminate\Support\ServiceProvider;
use Thedevsaddam\LaravelSchema\Console\Commands\HelpSchema;
use Thedevsaddam\LaravelSchema\Console\Commands\ListSchema;
use Thedevsaddam\LaravelSchema\Console\Commands\MonitorSchema;
use Thedevsaddam\LaravelSchema\Console\Commands\QuerySchema;
use Thedevsaddam\LaravelSchema\Console\Commands\ShowSchema;
use Thedevsaddam\LaravelSchema\Console\Commands\SimpleSchema;
use Thedevsaddam\LaravelSchema\Console\Commands\TableSchema;

class LaravelSchemaServiceProvider extends ServiceProvider
{
    protected $commands = [
        HelpSchema::class,
        ListSchema::class,
        QuerySchema::class,
        ShowSchema::class,
        SimpleSchema::class,
        TableSchema::class,
        MonitorSchema::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //register the commands
        $this->commands($this->commands);
    }
}
