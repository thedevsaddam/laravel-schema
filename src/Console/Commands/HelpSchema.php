<?php

namespace Thedevsaddam\LaravelSchema\Console\Commands;

use DB;
use Illuminate\Console\Command;


class HelpSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schema:help';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display help message for this package.';

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
        $this->showHelp();
    }

    /**
     * Display schema help information in a table
     * @return bool
     */
    public function showHelp()
    {
        $this->generateBody();
        $this->info('Laravel Schema version: ' . $this->version);
        $this->table($this->headers, $this->body);
        $this->comment('Visit https://packagist.org/packages/thedevsaddam/laravel-schema for more details.');
    }

    /**
     *  Generate the table body
     */
    private function generateBody()
    {
        $data = $this->readHelpGuide();
        $this->version = $data['version'];
        foreach ($data['help'] as $key => $value) {
            $data = [$key, $value];
            array_push($this->body, $data);
        }

    }

    /**
     * Read help.json file
     * @return mixed
     */
    private function readHelpGuide()
    {
        try {
            $help = file_get_contents(realpath(__DIR__ . "/../../Schema/help.json"));
            return json_decode($help, true);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

}